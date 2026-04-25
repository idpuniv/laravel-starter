<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\File;
use App\Roles\Roles;

// app/Console/Commands/AddPermissionToRole.php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\File;
use App\Roles\Roles;

class AddPermissionToRole extends Command
{
    protected $signature = 'permission:add-to-role
        {permission? : Nom de la permission (ex: user.view)}
        {--role= : Rôle cible}
        {--all : Ajouter toutes les permissions d\'un fichier}
        {--file= : Fichier de permission complet (ex: App\\Permissions\\UserPermissions)}';

    protected $description = 'Ajoute une permission à un rôle existant (guard détecté automatiquement)';

    public function handle(): int
    {
        // Obtenez la permission
        $permission = $this->argument('permission');
        $allFromFile = $this->option('all');
        $fileOption = $this->option('file');

        $permissionClass = null;
        $permissionsToAdd = [];
        $detectedGuard = 'web'; // Détecté automatiquement comme 'web'

        // Cas 1 : Ajouter toutes les permissions d'un fichier sélectionné
        if ($allFromFile) {
            $permissionClass = $this->selectPermissionFile();
            if (!$permissionClass) {
                return 1;
            }
            $permissionsToAdd = $permissionClass::all();
            $detectedGuard = $this->getGuardFromClass($permissionClass);

            $this->info("Fichier: " . class_basename($permissionClass));
            $this->info("Guard détecté: {$detectedGuard}");
            $this->info("Permissions: " . count($permissionsToAdd));
        }
        // Cas 2 : Ajouter des permissions d'un fichier spécifique
        elseif ($fileOption) {
            if (!class_exists($fileOption)) {
                $this->error("Classe '{$fileOption}' non trouvée.");
                return 1;
            }
            $permissionClass = $fileOption;
            $permissionsToAdd = $permissionClass::all();
            $detectedGuard = $this->getGuardFromClass($permissionClass);

            $this->info("Fichier: " . class_basename($permissionClass));
            $this->info("Guard détecté: {$detectedGuard}");
            $this->info("Permissions: " . count($permissionsToAdd));
        }
        // Cas 3 : Ajouter une seule permission (pas encore géré)
        elseif ($permission) {
            $this->error('Pour une permission simple, veuillez utiliser --file avec une classe de permission.');
            $this->info('Exemple: php artisan permission:add-to-role --file="App\\Permissions\\UserPermissions"');
            return 1;
        } else {
            $this->error('Aucune permission spécifiée.');
            return 1;
        }

        if (empty($permissionsToAdd)) {
            $this->error('Aucune permission valide à ajouter.');
            return 1;
        }

        // Obtenez les rôles pour ce guard
        $roles = Roles::of($detectedGuard); // Vérifiez si le guard 'web' a des rôles
        if (empty($roles)) {
            $this->error("Aucun rôle trouvé pour le guard '{$detectedGuard}'.");
            return 1;
        }

        // Sélectionner un rôle
        $role = $this->getRoleSelection($detectedGuard, $roles);
        if (!$role) {
            return 1;
        }

        $roleLabel = $roles[$role]['label'];
        $currentPermissions = $roles[$role]['permissions'];

        // Vérifiez les permissions existantes et nouvelles
        $existingPermissions = [];
        $newPermissions = [];

        foreach ($permissionsToAdd as $perm) {
            if (in_array($perm, $currentPermissions)) {
                $existingPermissions[] = $perm;
            } else {
                $newPermissions[] = $perm;
            }
        }

        if (!empty($existingPermissions)) {
            $this->warn("Ces permissions existent déjà :");
            foreach ($existingPermissions as $perm) {
                $this->warn("  - {$perm}");
            }
        }

        if (empty($newPermissions)) {
            $this->info('Aucune nouvelle permission à ajouter.');
            return 0;
        }

        // Affichez les permissions à ajouter
        $this->newLine();
        $this->info("Ajout au rôle '{$roleLabel}' (guard: {$detectedGuard}):");
        foreach ($newPermissions as $perm) {
            $this->line("  + {$perm}");
        }

        $this->newLine();
        if (!$this->confirm('Confirmez-vous l\'ajout de ces permissions ?', true)) {
            $this->info('Opération annulée.');
            return 0;
        }

        // Mettez à jour le fichier Roles.php
        if (!$this->updateRolesFile($detectedGuard, $role, $permissionClass, $newPermissions)) {
            return 1;
        }

        $this->newLine();
        $this->info("✓ " . count($newPermissions) . " permission(s) ajoutée(s) au rôle '{$roleLabel}'");

        return 0;
    }

    /**
     * Sélectionner un fichier de permissions interactif
     */
    private function selectPermissionFile(): ?string
    {
        $this->newLine();
        $this->info('=== Sélection du fichier de permissions ===');

        $permissionsPath = app_path('Permissions');

        if (!File::exists($permissionsPath)) {
            $this->error("Le dossier App/Permissions n'existe pas.");
            return null;
        }

        $files = File::files($permissionsPath);

        $permissionClasses = [];

        foreach ($files as $index => $file) {
            $className = 'App\\Permissions\\' . $file->getFilenameWithoutExtension();
            if (class_exists($className) && method_exists($className, 'all')) {
                $guard = $this->getGuardFromClass($className);
                $permissionClasses[] = $className;
                $this->line("  " . ($index + 1) . ". " . $file->getFilenameWithoutExtension() . " (guard: {$guard}, " . count($className::all()) . " permissions)");
            }
        }

        if (empty($permissionClasses)) {
            $this->error('Aucune classe de permission trouvée.');
            return null;
        }

        $choice = $this->ask('Choisissez un fichier (1-' . count($permissionClasses) . ')');

        if (!is_numeric($choice) || $choice < 1 || $choice > count($permissionClasses)) {
            $this->error('Option invalide.');
            return $this->selectPermissionFile();
        }

        return $permissionClasses[$choice - 1];
    }

    /**
     * Obtenir le guard à partir de la classe de permission
     */
    private function getGuardFromClass(string $class): string
    {
        if (method_exists($class, 'guard')) {
            return $class::guard();
        }

        if (defined("{$class}::GUARD")) {
            return $class::GUARD;
        }

        return 'web'; // Si aucun guard spécifié, utiliser 'web' par défaut
    }

    /**
     * Sélectionner un rôle à partir de la configuration des rôles
     */
    private function getRoleSelection(string $guard, array $roles): ?string
    {
        $roleOption = $this->option('role');

        if ($roleOption) {
            if (!isset($roles[$roleOption])) {
                $this->error("Rôle '{$roleOption}' invalide pour le guard '{$guard}'.");
                $this->line("Rôles disponibles: " . implode(', ', array_keys($roles)));
                return null;
            }
            return $roleOption;
        }

        $this->newLine();
        $this->info("=== Sélection du rôle ({$guard}) ===");

        $roleList = array_keys($roles);

        foreach ($roleList as $index => $role) {
            $label = $roles[$role]['label'];
            $permCount = count($roles[$role]['permissions']);
            $this->line("  " . ($index + 1) . ". {$role} ({$label}) - {$permCount} permissions");
        }

        $choice = $this->ask('Choisissez un rôle (1-' . count($roleList) . ')');

        if (!is_numeric($choice) || $choice < 1 || $choice > count($roleList)) {
            $this->error('Option invalide.');
            return $this->getRoleSelection($guard, $roles);
        }

        return $roleList[$choice - 1];
    }

    /**
     * Mettre à jour le fichier Roles.php avec la nouvelle permission
     */
    private function updateRolesFile(string $guard, string $role, string $permissionClass, array $newPermissions): bool
{
    $rolesPath = app_path('Roles/Roles.php');
    if (!File::exists($rolesPath)) {
        $this->error("Fichier Roles.php non trouvé");
        return false;
    }

    $content = File::get($rolesPath);

    // Préparation du FQN
    $fqnClass = '\\' . ltrim($permissionClass, '\\');
    $shortClass = class_basename($permissionClass);

    // Pattern précis pour capturer le contenu entre les crochets de 'permissions' => [ ... ]
    $rolePattern = '/(self::' . strtoupper($role) . '|[\'"]' . $role . '[\'"])\s*=>\s*\[([\s\S]*?)[\'"]permissions[\'"]\s*=>\s*\[([\s\S]*?)\]/i';

    if (!preg_match($rolePattern, $content, $matches)) {
        $this->error("Rôle '{$role}' non trouvé.");
        return false;
    }

    $fullMatch = $matches[0];
    $currentPermissionsContent = $matches[3];

    // Vérification de doublon
    if (str_contains($currentPermissionsContent, $shortClass . '::class')) {
        $this->warn("La classe {$shortClass} est déjà présente.");
        return true;
    }

    // --- LOGIQUE DE FORMATAGE ---
    $indent = "                        "; // 24 espaces pour l'alignement des classes
    $closingIndent = "                    ";   // 20 espaces pour le crochet de fermeture ]

    $cleaned = trim($currentPermissionsContent);
    
    if ($cleaned === '') {
        // Si le tableau est vide : [ ]
        $newContentBlock = "\n" . $indent . $fqnClass . "::class,\n" . $closingIndent;
    } else {
        // S'il y a déjà des données, on nettoie et on ajoute à la ligne suivante
        $newContentBlock = "\n" . $indent . rtrim($cleaned, ',') . ",\n" . $indent . $fqnClass . "::class,\n" . $closingIndent;
    }

    // Reconstruction du bloc complet sans déformer le reste du fichier
    $updatedBlock = $matches[1] . " => [" . $matches[2] . "'permissions' => [" . $newContentBlock . "]";

    $newContent = str_replace($fullMatch, $updatedBlock, $content);

    return File::put($rolesPath, $newContent) !== false;
}
}
