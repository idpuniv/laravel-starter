<?php

namespace App\Traits;

use App\Models\Team;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Support\Collection;

/**
 * @mixin \Illuminate\Database\Eloquent\Model
 */
trait HasTeams
{
    /**
     * Teams relation
     */
    public function teams(): BelongsToMany
    {
        return $this->belongsToMany(Team::class, 'team_user')
            ->withTimestamps();
    }

    /**
     * Get all teams
     */
    public function getTeams(): Collection
    {
        return $this->teams()->get();
    }

    /**
     * Check team membership
     */
    public function hasTeam(int|string|Team $team): bool
    {
        $teamId = $this->resolveTeamId($team);

        if (! $teamId) {
            return false;
        }

        return $this->teams()
            ->where('teams.id', $teamId)
            ->exists();
    }

    /**
     * Assign team (FLUENT)
     */
    public function assignTeam(int|string|Team $team): static
    {
        $teamModel = Team::firstWhere('name', $team)
            ?? Team::firstWhere('id', $team);

        if (! $teamModel) {
            return $this;
        }

        if (! $this->teams()->where('teams.id', $teamModel->id)->exists()) {
            $this->teams()->attach($teamModel->id);
        }

        $this->refresh();

        return $this;
    }

    /**
     * Assign multiple teams (FLUENT)
     */
    public function assignTeams(array $teams): static
    {
        try {
            $teamIds = collect($teams)
                ->map(function ($team) {
                    $teamModel = \App\Models\Team::firstWhere('name', $team)
                        ?? \App\Models\Team::firstWhere('id', $team);

                    return $teamModel?->id;
                })
                ->filter()
                ->unique()
                ->values()
                ->toArray();

            if (empty($teamIds)) {
                return $this;
            }

            $existing = $this->teams()
                ->whereIn('teams.id', $teamIds)
                ->pluck('teams.id')
                ->toArray();

            $toAttach = array_diff($teamIds, $existing);

            if (! empty($toAttach)) {
                $this->teams()->attach($toAttach);
            }

            $this->refresh();
        } catch (\Throwable $e) {
            report($e);
        }

        return $this;
    }

    /**
     * Detach team (FLUENT)
     */
    public function detachTeam(int|string|Team $team): static
    {
        try {
            $teamModel = $this->resolveTeamModel($team);

            if ($teamModel) {
                $this->teams()->detach($teamModel->id);
            }
        } catch (\Throwable $e) {
            report($e);
        }

        return $this;
    }

    /**
     * Sync teams (FLUENT)
     */
    public function syncTeams(array $teams): static
    {
        $teamIds = collect($teams)
            ->map(fn($team) => $this->resolveTeamModel($team)?->id)
            ->filter()
            ->values()
            ->toArray();

        $this->teams()->sync($teamIds);

        return $this;
    }

    /**
     * Primary team
     */
    public function primaryTeam(): ?Team
    {
        return $this->teams()->first();
    }

    /**
     * Resolve team model (clean version using firstWhere)
     */
    protected function resolveTeamModel(int|string|Team $team): ?Team
    {
        if ($team instanceof Team) {
            return $team;
        }

        return Team::query()
            ->where('id', $team)
            ->orWhere('name', $team)
            ->first();
    }

    /**
     * Resolve team ID
     */
    protected function resolveTeamId(int|string|Team $team): ?int
    {
        return $this->resolveTeamModel($team)?->id;
    }
}
