<?php

namespace App\DataTables;

use App\Models\User;
use App\Models\Person;
use App\Models\Role;
use App\Services\AbstractDataTable;
use Illuminate\Database\Eloquent\Builder;

class UsersDataTable extends AbstractDataTable
{
    /**
     * Définition de la requête de base
     */
    protected function query(): Builder
    {
        return Person::query()->with(['user', 'user.roles']);
    }

    /**
     * Configuration des colonnes cherchables (Global Search)
     */
    protected function defineSearchable(): array
    {
        return ['first_name', 'last_name', 'phone', 'user.email', 'user.username', 'user.roles.name'];
    }

    /**
     * Mappage des filtres avec la logique du moteur
     */
    protected function defineFilters(): array
    {
        return [
            'status' => [
            'type'   => 'single',
            'column' => 'user.status' 
        ],
            'roles'  => [
                'type' => 'relation',
                'relation' => 'user.roles',
                'column' => 'id'
            ],
            'date_range' => [
                'type'      => 'range',
                'column'    => 'created_at',
                'is_date'   => true,
                'start_key' => 'date_start',
                'end_key'   => 'date_end'
            ],
            'no_role' => [
                'type' => 'no_relation',
                'relation' => 'user.roles'
            ],
            'multi_roles' => [
                'type' => 'has_count',
                'relation' => 'user.roles',
                'operator' => '>',
                'count' => 1
            ],
        ];
    }

    /**
     * Vues et Exports
     */
    protected function defineIndexView(): string { return 'admin.users.index'; }
    protected function defineTableView(): string { return 'admin.users.partials.table'; }
    protected function defineExportView(): ?string { return 'exports.users-pdf'; }

    /**
     * Paramètres de pagination
     */
    protected function definePerPage(): int { return 25; }

    /**
     * Surcharge de render pour injecter les données spécifiques à cette page
     */
    public function render(?string $view = null, array $data = [])
    {
        $additionalData = array_merge([
            'roles'        => Role::all(),
            'total_active' => User::where('status', 'active')->count(),
            'page_title'   => 'Gestion des utilisateurs'
        ], $data);

        return parent::render($view, $additionalData);
    }
}