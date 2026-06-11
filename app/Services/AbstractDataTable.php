<?php

namespace App\Services;

use Illuminate\Http\Request;
use Illuminate\Database\Eloquent\Builder;

abstract class AbstractDataTable extends DataTable
{
    public function __construct(Request $request)
    {
        $query = $this->query();
        
        parent::__construct($query, $request);

        // On utilise les méthodes de définition (préfixées par "define")
        // pour nourrir les méthodes de configuration du parent
        $this->searchable($this->defineSearchable());
        $this->filters($this->defineFilters());
        $this->export($this->defineExportView());
        $this->paginate($this->definePerPage());
        $this->views($this->defineTableView());
        $this->columns($this->defineColumns());
    }

    public function render(?string $view = null, array $data = [])
    {
        return parent::render($view ?? $this->defineIndexView(), $data);
    }

    // --- Nouveaux noms de contrats pour éviter les conflits ---

    abstract protected function query(): Builder;
    abstract protected function defineIndexView(): string;
    abstract protected function defineTableView(): string;
    abstract protected function defineSearchable(): array;
    abstract protected function defineFilters(): array;
    abstract protected function defineExportView(): ?string;

    // Valeurs par défaut
    protected function defineColumns(): array { return []; }
    protected function definePerPage(): int { return 25; }
}