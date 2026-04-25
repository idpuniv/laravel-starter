<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Str;

class MakePermissionFile extends Command
{
    protected $signature = 'make:permission
        {name? : Nom de la ressource, ex. User}
        {--module= : Nom du module}
        {--guard=web : Guard à utiliser}
        {--force : Forcer l\'écrasement du fichier s\'il existe}';

    protected $description = 'Génère un fichier de permissions et l\'enregistre dans PermissionsRegistry';

    private array $modules = [];
    private string $registryPath;
    private string $permissionsPath;

    public function __construct()
    {
        parent::__construct();
        $this->permissionsPath = app_path('Permissions');
        $this->registryPath = $this->permissionsPath . '/PermissionsRegistry.php';
    }

    public function handle(): int
    {
        // Create Permissions directory if it doesn't exist
        if (!File::exists($this->permissionsPath)) {
            File::makeDirectory($this->permissionsPath, 0755, true);
            $this->info("✓ Dossier créé : {$this->permissionsPath}");
        }

        // Load existing modules from registry
        $this->loadModules();

        // Get resource name
        $name = $this->argument('name');
        if (!$name) {
            $name = $this->ask('Nom de la ressource (ex: User, Product, Order)');
        }

        if (!$name) {
            $this->error('Le nom de la ressource est requis.');
            return 1;
        }

        $name = Str::studly($name);

        // Get or create module
        $module = $this->getModuleSelection();

        if ($module === null) {
            return 1;
        }

        $guard = $this->option('guard') ?: $this->ask('Guard à utiliser', 'web');

        // File path
        $permissionsFilePath = $this->permissionsPath . '/' . $name . 'Permissions.php';

        // Check if file exists
        if (File::exists($permissionsFilePath) && !$this->option('force')) {
            $this->warn("Le fichier {$permissionsFilePath} existe déjà !");
            if (!$this->confirm('Voulez-vous l\'écraser ?', false)) {
                return 0;
            }
        }

        // Generate permission file
        $permissionsContent = $this->generatePermissionContent($name, $guard);
        File::put($permissionsFilePath, $permissionsContent);
        $this->info("✓ Fichier de permissions généré : {$permissionsFilePath}");

        // Update registry
        $this->updatePermissionRegistry($module, $name);

        $this->newLine();

        // On demande confirmation avant de lancer l'autre commande
        if ($this->confirm('Voulez-vous ajouter ces permissions à un rôle maintenant ?', true)) {
            $this->line('<comment>Ajout de la permission à un rôle...</comment>');
            $this->newLine();
            
            $this->call('permission:add-to-role', [
                '--file' => 'App\\Permissions\\' . $name . 'Permissions',
            ]);
        } else {
            $this->info('Assignation au rôle ignorée.');
        }

        $this->newLine();
        $this->info('✨ Permission créée avec succès !');

        return 0;
    }

    /**
     * Load existing modules from PermissionsRegistry
     */
    private function loadModules(): void
    {
        $this->modules = [];

        if (File::exists($this->registryPath)) {
            $registry = require $this->registryPath;
            if (is_array($registry)) {
                $this->modules = array_keys($registry);
            }
        }
    }

    /**
     * Get module selection from user
     */
    private function getModuleSelection(): ?string
    {
        $moduleOption = $this->option('module');

        // If module is provided via option, use it
        if ($moduleOption) {
            return $moduleOption;
        }

        $this->newLine();
        $this->info('=== Sélection du module ===');

        $options = [];
        $options[] = 'Créer un nouveau module';

        foreach ($this->modules as $module) {
            $options[] = $module;
        }


        // Display menu manually to avoid choice() bug
        foreach ($options as $index => $option) {
            $displayIndex = $index + 1;
            $this->line("  {$displayIndex}. {$option}");
        }

        $selected = $this->ask('Choisissez un module (1-' . count($options) . ')');

        // Validate input
        if (!is_numeric($selected) || $selected < 1 || $selected > count($options)) {
            $this->error('Option invalide.');
            return $this->getModuleSelection();
        }

        $choice = $options[$selected - 1];

        // Parse choice
        if ($choice === 'Créer un nouveau module') {
            return $this->createNewModule();
        }

        return $choice;
    }

    /**
     * Create a new module interactively
     */
    private function createNewModule(): string
    {
        $this->newLine();
        $this->info('=== Création d\'un nouveau module ===');

        $moduleName = $this->ask('Nom du module (ex: Système, Utilisateurs, Commandes)');

        if (!$moduleName) {
            $this->error('Le nom du module est requis.');
            return $this->createNewModule();
        }

        $description = $this->ask('Description du module (optionnel)');
        $icon = $this->ask('Icône du module (optionnel)', 'heroicon-o-folder');
        $order = $this->ask('Ordre d\'affichage (optionnel)', '0');

        // Update registry with new module
        $this->addModuleToRegistry($moduleName, $description ?: "Gestion des {$moduleName}", $icon, (int)$order);

        $this->info("✓ Module '{$moduleName}' créé avec succès !");

        return $moduleName;
    }

    /**
     * Add a new module to the registry
     */
    private function addModuleToRegistry(string $moduleName, string $description, string $icon, int $order): void
    {
        $registry = [];

        // Load existing registry
        if (File::exists($this->registryPath)) {
            $registry = require $this->registryPath;
        }

        // Add new module
        $registry[$moduleName] = [
            'description' => $description,
            'permissions' => $registry[$moduleName]['permissions'] ?? [],
            'icon' => $icon,
            'order' => $order,
        ];

        // Sort by order
        uasort($registry, fn($a, $b) => ($a['order'] ?? 0) <=> ($b['order'] ?? 0));

        // Write registry
        $this->writeRegistryFile($registry);
    }

    /**
     * Write the registry file
     */
    private function writeRegistryFile(array $registry): void
    {
        $content = "<?php\n\nreturn [\n";

        foreach ($registry as $moduleName => $config) {
            $content .= "\n    '{$moduleName}' => [\n";
            $content .= "        'description' => '{$config['description']}',\n";

            // Permissions array
            $content .= "        'permissions' => [\n";
            foreach ($config['permissions'] as $permission) {
                $content .= "            {$permission}::class,\n";
            }
            $content .= "        ],\n";

            // Optional fields
            if (isset($config['icon'])) {
                $content .= "        'icon' => '{$config['icon']}',\n";
            }

            if (isset($config['order'])) {
                $content .= "        'order' => {$config['order']},\n";
            }

            $content .= "    ],\n";
        }

        $content .= "];\n";

        File::put($this->registryPath, $content);
        $this->info("✓ Registry mis à jour : {$this->registryPath}");
    }

    /**
     * Update registry with new permission
     */
    private function updatePermissionRegistry(string $module, string $name): void
    {
        $registry = [];

        if (File::exists($this->registryPath)) {
            $registry = require $this->registryPath;
        }

        $fullClassName = 'App\\Permissions\\' . $name . 'Permissions';

        if ($module === 'default') {
            $module = 'Général';
            if (!isset($registry[$module])) {
                $registry[$module] = [
                    'description' => 'Permissions générales du système',
                    'permissions' => [],
                    'icon' => 'heroicon-o-cog',
                    'order' => 999,
                ];
            }
        }

        // Add permission to module
        if (!isset($registry[$module])) {
            $registry[$module] = [
                'description' => "Gestion des {$module}",
                'permissions' => [],
            ];
        }

        if (!in_array($fullClassName, $registry[$module]['permissions'])) {
            $registry[$module]['permissions'][] = $fullClassName;
        }

        // Write updated registry
        $this->writeRegistryFile($registry);

        $this->info("✓ Permission ajoutée au module '{$module}' dans le registry");
    }

    /**
     * Generate permission file content
     */
    private function generatePermissionContent(string $name, string $guard): string
    {
        $resource = Str::snake($name);
        $className = $name . 'Permissions';

        return <<<PHP
<?php

namespace App\Permissions;

final class {$className}
{
    // Resource permissions
    public const VIEW   = '{$resource}.view';
    public const LIST   = '{$resource}.list';
    public const CREATE = '{$resource}.create';
    public const UPDATE = '{$resource}.update';
    public const DELETE = '{$resource}.delete';
    public const GUARD  = '{$guard}';

    /**
     * Get human-readable labels for permissions.
     */
    public static function labels(): array
    {
        return [
            self::VIEW   => 'Voir {$name}',
            self::LIST   => 'Lister les {$name}s',
            self::CREATE => 'Créer {$name}',
            self::UPDATE => 'Modifier {$name}',
            self::DELETE => 'Supprimer {$name}',
        ];
    }

    /**
     * Get read permissions (view, list).
     */
    public static function read(): array
    {
        return [self::VIEW, self::LIST];
    }

    /**
     * Get write permissions (create, update, delete).
     */
    public static function write(): array
    {
        return [self::CREATE, self::UPDATE, self::DELETE];
    }

    /**
     * Get the guard name.
     */
    public static function guard(): string
    {
        return self::GUARD;
    }

    /**
     * Get all permission names.
     */
    public static function all(): array
    {
        return array_keys(self::labels());
    }
}

PHP;
    }
}
