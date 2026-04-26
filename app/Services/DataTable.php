<?php

namespace App\Services;

use Illuminate\Http\Request;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\Log;
use Throwable;

class DataTable
{
    protected Builder $query;
    protected Builder $originalQuery;
    protected Request $request;
    protected array $searchable = [];
    protected array $filters = [];
    protected ?string $exportView = null;
    protected ?int $pagination = null;
    protected string $tableView = 'partials.table';
    protected array $columns = [];

    public static function make(...$args): static
    {
        return new static(...$args);
    }

    public function __construct(Builder $query, Request $request)
    {
        $this->query = $query;
        $this->originalQuery = clone $query; // Sauvegarde en cas d'erreur de filtre
        $this->request = $request;
    }

    // --- API Fluide ---
    public function columns(array $columns): self { $this->columns = $columns; return $this; }
    public function searchable(array $fields): self { $this->searchable = $fields; return $this; }
    public function filters(array $filters): self { $this->filters = $filters; return $this; }
    public function views(string $tableView): self { $this->tableView = $tableView; return $this; }
    public function export(string $view): self { $this->exportView = $view; return $this; }
    public function paginate(?int $count): self { $this->pagination = $count; return $this; }

    public function render(string $view, array $additionalData = [])
    {
        try {
            $this->applySearch();
            $this->applyFilters();
            $this->applySorting();
        } catch (Throwable $e) {
            Log::error("DataTable Filter Error: " . $e->getMessage(), [
                'params' => $this->request->all(),
                'url' => $this->request->fullUrl()
            ]);
            $this->query = $this->originalQuery; // Repli sur la requête sans filtres
            $additionalData['table_error'] = "Certains filtres n'ont pas pu être appliqués.";
        }

        if ($this->request->filled('export')) {
            return $this->handleExport();
        }

        $limit = $this->request->get('table_length', $this->pagination);
        $data = $limit ? $this->query->paginate($limit)->withQueryString() : $this->query->get();

        $viewData = array_merge([
            'data'    => $data,
            'columns' => $this->columns,
            'filters' => $this->filters,
        ], $additionalData);

        $tableHtml = view($this->tableView, $viewData)->render();

        return $this->request->ajax() 
            ? response($tableHtml) 
            : view($view, array_merge($viewData, ['table' => $tableHtml]));
    }

    protected function applyFilters(): void
    {
        foreach ($this->filters as $key => $config) {
            $hasStart = isset($config['start_key']) && $this->request->filled($config['start_key']);
            $hasEnd = isset($config['end_key']) && $this->request->filled($config['end_key']);

            if (!$this->request->filled($key) && !$hasStart && !$hasEnd) continue;

            $value = $this->request->get($key);
            $type = $config['type'] ?? 'single';
            $column = $config['column'] ?? $key;

            match ($type) {
                'single', 'compare' => $this->query->where($column, $config['operator'] ?? '=', $value),
                'range' => $this->query->where(function ($q) use ($column, $config) {
                    $start = $this->request->get($config['start_key']);
                    $end   = $this->request->get($config['end_key']);
                    if ($start) ($config['is_date'] ?? false) ? $q->whereDate($column, '>=', $start) : $q->where($column, '>=', $start);
                    if ($end) ($config['is_date'] ?? false) ? $q->whereDate($column, '<=', $end) : $q->where($column, '<=', $end);
                }),
                'multi', 'relation' => $this->applyRelationOrMulti($type, $column, $value, $config),
                'no_relation' => ($value == '1') ? $this->query->whereDoesntHave($config['relation']) : null,
                'has_count' => $this->query->has($config['relation'], $config['operator'] ?? '>=', $config['count'] ?? 1),
                'callback' => $config['callback']($this->query, $value),
                default => null,
            };
        }
    }

    protected function applyRelationOrMulti($type, $column, $value, $config): void
    {
        $values = is_array($value) ? $value : explode(',', $value);
        $values = array_filter($values);

        if ($type === 'multi') {
            $this->query->whereIn($column, $values);
        } else {
            $this->query->whereHas($config['relation'], fn($q) => $q->whereIn($column, $values));
        }
    }

    protected function applySearch(): void
    {
        if ($this->request->filled('search') && !empty($this->searchable)) {
            $search = $this->request->search;
            $this->query->where(function ($q) use ($search) {
                foreach ($this->searchable as $field) {
                    $q->orWhere($field, 'like', "%{$search}%");
                }
            });
        }
    }

    protected function applySorting(): void
    {
        if ($this->request->filled('sort')) {
            $this->query->orderBy($this->request->get('sort'), $this->request->get('direction', 'asc'));
        }
    }

    protected function handleExport()
    {
        $scope = $this->request->get('export_scope', 'current');
        $data = ($scope === 'all') ? $this->query->get() : $this->query->skip(($this->request->get('page', 1) - 1) * $this->request->get('table_length', 25))->take($this->request->get('table_length', 25))->get();

        $format = $this->request->get('export');
        $filename = "export_" . now()->format('d_m_Y');

        if ($format === 'pdf') {
            return \Barryvdh\DomPDF\Facade\Pdf::loadView($this->exportView, compact('data'))->download($filename . '.pdf');
        }

        return \Maatwebsite\Excel\Facades\Excel::download(new class($this->exportView, $data) implements \Maatwebsite\Excel\Concerns\FromView {
            public function __construct(private $v, private $d) {}
            public function view(): \Illuminate\Contracts\View\View { return view($this->v, ['data' => $this->d]); }
        }, $filename . '.xlsx');
    }
}