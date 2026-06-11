<?php

namespace App\Traits;

use Livewire\WithPagination;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

trait WithDataTable
{
    use WithPagination;

    public string $search = '';
    public string $statusFilter = '';
    public string $roleFilter = '';
    public int $perPage = 15;

    /**
     * Initialize the trait and dynamically register event listeners 
     * for custom nested pagination components.
     *
     * @return void
     */
    public function initializeWithDataTable(): void
    {
        $this->listeners = array_merge($this->listeners ?? [], [
            'goToPage'     => 'goToPage',
            'previousPage' => 'previousPage',
            'nextPage'     => 'nextPage',
        ]);
    }

    /**
     * Livewire Lifecycle Hook: Reset pagination automatically 
     * whenever a filtering property is modified.
     *
     * @param string $propertyName
     * @return void
     */
    public function updatedWithDataTable(string $propertyName): void
    {
        if (in_array($propertyName, ['search', 'statusFilter', 'roleFilter', 'perPage'])) {
            $this->resetPage();
        }
    }

    /**
     * Resolve the target Eloquent model class defined in the host component.
     *
     * @throws \Exception If the $model property is missing.
     * @return string
     */
    public function getModelClass(): string
    {
        if (property_exists($this, 'model') && !empty($this->model)) {
            return $this->model;
        }
        
        throw new \Exception("The \$model property must be explicitly defined in the host Livewire component [" . static::class . "].");
    }

    /**
     * Build the base Eloquent query, injecting database-agnostic search terms 
     * and dynamic scope hooks based on the pluralized model name.
     *
     * @return Builder
     */
    public function getBaseQuery(): Builder
    {
        try {
            $modelClass = $this->getModelClass();
            $query = $modelClass::query();

            // Dynamically invoke a model-specific search hook if implemented by the component.
            $modelNamePlural = Str::plural(class_basename($modelClass));
            $dynamicMethod = 'search' . $modelNamePlural;

            if (method_exists($this, $dynamicMethod)) {
                $query = $this->$dynamicMethod($query);
            }

            if (empty($this->search) || !property_exists($this, 'searchable') || empty($this->searchable)) {
                return $query;
            }

            // Fallback to safe connection driver inspection
            $isPostgres = false;
            try {
                $isPostgres = DB::connection()->getDriverName() === 'pgsql';
            } catch (\Throwable $dbEx) {
                Log::warning('WithDataTable Trait: Could not detect DB driver, defaulting to standard LIKE.', ['exception' => $dbEx->getMessage()]);
            }

            $operator = $isPostgres ? 'ILIKE' : 'LIKE';
            $searchTerm = '%' . $this->search . '%';

            return $query->where(function (Builder $subQuery) use ($operator, $searchTerm) {
                foreach ($this->searchable as $field) {
                    if (str_contains($field, '.')) {
                        [$relation, $relationField] = explode('.', $field);
                        $subQuery->orWhereHas($relation, function ($q) use ($relationField, $operator, $searchTerm) {
                            $q->where($relationField, $operator, $searchTerm);
                        });
                    } else {
                        $subQuery->orWhere($field, $operator, $searchTerm);
                    }
                }
            });

        } catch (\Throwable $e) {
            Log::critical('WithDataTable Trait: Failed constructing base query.', [
                'component' => static::class,
                'exception' => $e->getMessage()
            ]);
            
            // Return an empty query builder state fallback to avoid broken UI bindings
            throw $e;
        }
    }

    /**
     * Placeholder method to be overridden in the host component 
     * to apply specific business rule filters.
     *
     * @param Builder $query
     * @return Builder
     */
    public function applyFilters(Builder $query): Builder
    {
        return $query;
    }

    /**
     * Fetch processed dataset as either a paginated instance or a flat collection.
     *
     * @param bool $all
     * @return mixed
     */
    public function getData(bool $all = false)
    {
        try {
            $query = $this->applyFilters($this->getBaseQuery())->latest();
            return $all ? $query->get() : $query->paginate($this->perPage);
        } catch (\Throwable $e) {
            Log::error('WithDataTable Trait: Error executing dataset query extraction.', [
                'component' => static::class,
                'exception' => $e->getMessage()
            ]);
            
            // Returns an empty collection as an ultimate safety net to keep layouts intact
            return $all ? collect() : new \Illuminate\Pagination\LengthAwarePaginator([], 0, $this->perPage);
        }
    }

    /**
     * Reset all data table state filters and rewind pagination.
     *
     * @return void
     */
    public function resetFilters(): void
    {
        $this->reset(['search', 'statusFilter', 'roleFilter', 'perPage']);
        $this->resetPage();
    }
}