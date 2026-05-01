<?php

namespace App\Services;

use Illuminate\Http\Request;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\{Log, DB, Cache};
use Illuminate\Pagination\LengthAwarePaginator;
use Throwable;

class DataTable
{
    // Propriétés de base
    protected Builder $query;
    protected Builder $originalQuery;
    protected Request $request;

    // Configuration du tableau
    protected array $columns = [];
    protected array $searchable = [];
    protected array $filters = [];

    // Paramètres optionnels (Définis ici pour éviter les erreurs "undefined property")
    protected ?string $exportView = null;
    protected ?int $pagination = null;
    protected string $tableView = 'partials.table';

    // Optimisations
    protected array $eagerLoads = [];
    protected array $sortWhitelist = [];
    protected int $cacheTtl = 0;

    /**
     * Initialisation statique
     */
    public static function make(...$args): static
    {
        return new static(...$args);
    }

    public function __construct(Builder $query, Request $request)
    {
        $this->query = $query;
        $this->originalQuery = clone $query;
        $this->request = $request;

        // Protection des colonnes de la table principale
        $mainTable = $this->query->getModel()->getTable();
        if (empty($this->query->getQuery()->columns)) {
            $this->query->select("{$mainTable}.*");
        }
    }

    // --- API Fluide ---
    public function with(array $relations): self
    {
        $this->eagerLoads = $relations;
        return $this;
    }
    public function sortable(array $fields): self
    {
        $this->sortWhitelist = $fields;
        return $this;
    }
    public function columns(array $columns): self
    {
        $this->columns = $columns;
        return $this;
    }
    public function searchable(array $fields): self
    {
        $this->searchable = $fields;
        return $this;
    }
    public function filters(array $filters): self
    {
        $this->filters = $filters;
        return $this;
    }
    public function views(string $tableView): self
    {
        $this->tableView = $tableView;
        return $this;
    }
    public function export(string $view): self
    {
        $this->exportView = $view;
        return $this;
    }
    public function paginate(?int $count): self
    {
        $this->pagination = $count;
        return $this;
    }
    public function withCache(int $seconds = 60): self
    {
        $this->cacheTtl = $seconds;
        return $this;
    }

    /**
     * Rendu final de la DataTable
     */
    public function render(string $view, array $additionalData = [])
    {
        if ($this->request->filled('export')) {
            $this->applyLogic();
            return $this->handleExport();
        }

        $fetchData = function () {
            $this->applyLogic();

            if (!empty($this->eagerLoads)) {
                $this->query->with($this->eagerLoads);
            }

            $limit = $this->request->get('table_length', $this->pagination);

            /** @var LengthAwarePaginator|\Illuminate\Database\Eloquent\Collection $res */
            return $limit ? $this->query->paginate((int)$limit)->withQueryString() : $this->query->get();
        };

        // Gestion du cache avec la clé sécurisée
        $data = ($this->cacheTtl > 0 && !$this->request->filled('search'))
            ? Cache::remember($this->generateCacheKey(), $this->cacheTtl, $fetchData)
            : $fetchData();

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

    protected function applyLogic(): void
    {
        try {
            $this->applySearch();
            $this->applyFilters();
            $this->applySorting();
        } catch (Throwable $e) {
            Log::error("DataTable Logic Error: " . $e->getMessage());
            $this->query = $this->originalQuery;
        }
    }

    protected function applyFilters(): void
    {
        foreach ($this->filters as $key => $config) {
            $hasRange = ($config['type'] === 'range' && ($this->request->filled($config['start_key'] ?? '') || $this->request->filled($config['end_key'] ?? '')));
            if (!$this->request->filled($key) && !$hasRange) continue;

            $value = $this->request->get($key);
            $column = $config['column'] ?? $key;
            $type = $config['type'] ?? 'single';

            $this->applySmartQuery($this->query, $column, function ($q, $col) use ($type, $value, $config) {
                match ($type) {
                    'single', 'compare' => $q->where($col, $config['operator'] ?? '=', $value),
                    'range' => $q->where(function ($sub) use ($col, $config) {
                        $start = $this->request->get($config['start_key']);
                        $end   = $this->request->get($config['end_key']);
                        if ($start) ($config['is_date'] ?? false) ? $sub->whereDate($col, '>=', $start) : $sub->where($col, '>=', $start);
                        if ($end) ($config['is_date'] ?? false) ? $sub->whereDate($col, '<=', $end) : $sub->where($col, '<=', $end);
                    }),
                    'multi', 'relation' => $q->whereIn($col, is_array($value) ? $value : explode(',', $value)),
                    'no_relation' => $q->whereDoesntHave($config['relation'] ?? $col),
                    'has_count' => isset($config['relation']) ? $q->has($config['relation'], $config['operator'] ?? '>=', $config['count'] ?? 1) : null,
                    'callback' => is_callable($config['callback'] ?? null) ? $config['callback']($q, $value) : null,
                    default => null,
                };
            });
        }
    }

    protected function applySearch(): void
    {
        if ($this->request->filled('search') && !empty($this->searchable)) {
            $search = $this->request->search;
            $op = DB::getDriverName() === 'pgsql' ? 'ILIKE' : 'LIKE';

            $this->query->where(function ($parentQuery) use ($search, $op) {
                foreach ($this->searchable as $field) {
                    $parentQuery->orWhere(function ($orQuery) use ($field, $search, $op) {
                        $this->applySmartQuery($orQuery, $field, function ($q, $col) use ($search, $op) {
                            $q->where($col, $op, "%{$search}%");
                        });
                    });
                }
            });
        }
    }

    protected function applySmartQuery($query, string $column, callable $callback): void
    {
        if (str_contains($column, '.')) {
            $parts = explode('.', $column);
            $targetColumn = array_pop($parts);
            $relation = implode('.', $parts);
            $query->whereHas($relation, fn($q) => $callback($q, $targetColumn));
        } else {
            $callback($query, $column);
        }
    }

    protected function applySorting(): void
    {
        $sort = $this->request->get('sort');
        if ($sort && in_array($sort, $this->sortWhitelist)) {
            $dir = strtolower($this->request->get('direction')) === 'desc' ? 'desc' : 'asc';
            if (!str_contains($sort, '.')) {
                $this->query->orderBy($sort, $dir);
            }
        }
    }

    protected function generateCacheKey(): string
    {
        $inputs = $this->request->all();
        ksort($inputs);

        $identifier = auth()->check()
            ? 'u_' . auth()->id()
            : 'g_' . session()->getId();

        return 'dt_' . md5(json_encode($inputs) . $identifier);
    }

    protected function handleExport()
    {
        if (!$this->exportView) {
            throw new \Exception("Export view not defined. Use ->export('view_path')");
        }

        $format = $this->request->get('export');
        $scope  = $this->request->get('export_scope', 'current'); // 'current' par défaut

        // --- LOGIQUE DE RÉGRESSION CORRIGÉE ---
        if ($scope === 'all') {
            // On récupère tout ce qui matche les filtres sans limite
            $data = $this->query->get();
        } else {
            // On respecte la pagination actuelle de l'utilisateur
            $limit = (int) $this->request->get('table_length', $this->pagination ?? 25);
            $page  = (int) $this->request->get('page', 1);

            $data = $this->query
                ->skip(($page - 1) * $limit)
                ->take($limit)
                ->get();
        }

        $filename = "export_" . now()->format('d_m_Y_H_i');

        if ($format === 'pdf') {
            return \Barryvdh\DomPDF\Facade\Pdf::loadView($this->exportView, ['data' => $data])
                ->download($filename . '.pdf');
        }

        return \Maatwebsite\Excel\Facades\Excel::download(new class($this->exportView, $data) implements \Maatwebsite\Excel\Concerns\FromView {
            public function __construct(private $v, private $d) {}
            public function view(): \Illuminate\Contracts\View\View
            {
                return view($this->v, ['data' => $this->d]);
            }
        }, $filename . '.xlsx');
    }
}
