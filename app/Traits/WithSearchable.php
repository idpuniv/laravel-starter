<?php

namespace App\Traits;

use Livewire\WithPagination;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Str;

trait WithSearchable
{
    use WithPagination;

    public $search = '';
    public $perPage = 15;
    public array $filters = [];

    public function updatedSearch()
    {
        $this->resetPage();
    }

    public function updatedPerPage()
    {
        $this->resetPage();
    }
    
    public function updatedFilters()
    {
        $this->resetPage();
    }

    public function resetAll()
    {
        $this->reset(['search', 'perPage', 'filters']);
        $this->resetPage();
    }

    // ========== RECHERCHE ==========
    
    protected function applySearch(Builder $query): Builder
    {
        if (empty(trim($this->search))) {
            return $query;
        }

        $fields = property_exists($this, 'searchable') ? $this->searchable : [];
        
        if (empty($fields)) {
            return $query;
        }

        $operator = DB::getDriverName() === 'pgsql' ? 'ILIKE' : 'LIKE';
        $term = '%' . trim($this->search) . '%';

        return $query->where(function ($q) use ($fields, $operator, $term) {
            foreach ($fields as $field) {
                if (str_contains($field, '.')) {
                    [$relation, $column] = explode('.', $field, 2);
                    $q->orWhereHas($relation, fn($sub) => $sub->where($column, $operator, $term));
                } else {
                    $q->orWhere($field, $operator, $term);
                }
            }
        });
    }

    // ========== FILTRES ==========
    
    protected function applyFilters(Builder $query): Builder
    {
        if (empty($this->filters)) {
            return $query;
        }

        foreach ($this->filters as $key => $config) {
            // Skip si pas de valeur
            if (!isset($config['value']) || $config['value'] === '' || $config['value'] === null) {
                continue;
            }
            
            $value = $config['value'];
            $type = $config['type'] ?? $this->guessFilterType($value);
            
            $query = $this->applyFilter($query, $key, $value, $type, $config);
        }
        
        return $query;
    }

    protected function guessFilterType($value): string
    {
        if (is_array($value)) return 'in';
        if (str_contains($value, ',')) return 'in';
        if (str_contains($value, '|')) return 'between';
        if (str_starts_with($value, '>')) return 'greater';
        if (str_starts_with($value, '<')) return 'less';
        
        return 'where';
    }

    protected function applyFilter(Builder $query, string $key, $value, string $type, array $config): Builder
    {
        // Priorité à une méthode dédiée
        $method = 'filter' . ucfirst(Str::camel($key));
        if (method_exists($this, $method)) {
            return $this->$method($query, $value);
        }
        
        // Application selon le type
        return match($type) {
            'where' => $this->filterWhere($query, $key, $value, $config),
            'in' => $this->filterIn($query, $key, $value, $config),
            'between' => $this->filterBetween($query, $key, $value, $config),
            'has' => $this->filterHas($query, $key, $value, $config),
            'whereHas' => $this->filterWhereHas($query, $key, $value, $config),
            'greater' => $this->filterGreater($query, $key, $value, $config),
            'less' => $this->filterLess($query, $key, $value, $config),
            'like' => $this->filterLike($query, $key, $value, $config),
            'date' => $this->filterDate($query, $key, $value, $config),
            default => $query,
        };
    }

    // Types de filtres
    protected function filterWhere(Builder $query, string $key, $value, array $config): Builder
    {
        $column = $config['column'] ?? $key;
        $operator = $config['operator'] ?? '=';
        
        return $query->where($column, $operator, $value);
    }
    
    protected function filterIn(Builder $query, string $key, $value, array $config): Builder
    {
        $column = $config['column'] ?? $key;
        $values = is_array($value) ? $value : explode(',', $value);
        
        return $query->whereIn($column, $values);
    }
    
    protected function filterBetween(Builder $query, string $key, $value, array $config): Builder
    {
        $column = $config['column'] ?? $key;
        
        if (is_string($value) && str_contains($value, '|')) {
            [$min, $max] = explode('|', $value);
            return $query->whereBetween($column, [$min, $max]);
        }
        
        if (is_array($value) && count($value) === 2) {
            return $query->whereBetween($column, $value);
        }
        
        return $query;
    }
    
    protected function filterHas(Builder $query, string $key, $value, array $config): Builder
    {
        $relation = $config['relation'] ?? $key;
        $has = $value === true || $value === 'true' || $value === 1 || $value === 'yes';
        
        return $has ? $query->has($relation) : $query->doesntHave($relation);
    }
    
    protected function filterWhereHas(Builder $query, string $key, $value, array $config): Builder
    {
        $relation = $config['relation'];
        $column = $config['column'] ?? null;
        $operator = $config['operator'] ?? '=';
        
        if ($column) {
            return $query->whereHas($relation, fn($q) => $q->where($column, $operator, $value));
        }
        
        return $query->whereHas($relation);
    }
    
    protected function filterGreater(Builder $query, string $key, $value, array $config): Builder
    {
        $column = $config['column'] ?? $key;
        $cleanValue = ltrim($value, '> ');
        
        return $query->where($column, '>', $cleanValue);
    }
    
    protected function filterLess(Builder $query, string $key, $value, array $config): Builder
    {
        $column = $config['column'] ?? $key;
        $cleanValue = ltrim($value, '< ');
        
        return $query->where($column, '<', $cleanValue);
    }
    
    protected function filterLike(Builder $query, string $key, $value, array $config): Builder
    {
        $column = $config['column'] ?? $key;
        $operator = DB::getDriverName() === 'pgsql' ? 'ILIKE' : 'LIKE';
        
        return $query->where($column, $operator, '%' . $value . '%');
    }
    
    protected function filterDate(Builder $query, string $key, $value, array $config): Builder
    {
        $column = $config['column'] ?? $key;
        $operator = $config['operator'] ?? '=';
        
        return $query->whereDate($column, $operator, $value);
    }
}