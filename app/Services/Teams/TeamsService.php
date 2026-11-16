<?php

namespace App\Services\Teams;

use App\Models\Team;
use App\Models\User;
use Illuminate\Pagination\LengthAwarePaginator;

class TeamsService
{
    /**
     * List the teams for the requested page.
     *
     * @return LengthAwarePaginator<int, array{id: int, name: string, members_count: int, projects_count: int}>
     */
    public function getTeams(int $perPage, int $page): LengthAwarePaginator
    {
        return Team::query()
            ->withCount(['members', 'projects'])
            ->orderBy('name')
            ->orderBy('id')
            ->paginate(perPage: $perPage, page: $page)
            ->through(fn (Team $team): array => [
                'id' => $team->id,
                'name' => $team->name,
                'members_count' => (int) $team->members_count,
                'projects_count' => (int) $team->projects_count,
            ]);
    }

    /**
     * Create a team.
     *
     * @param  array{name: string}  $attributes
     */
    public function createTeam(array $attributes): Team
    {
        return Team::query()->create([
            'name' => $attributes['name'],
        ]);
    }

    /**
     * Update a team's name.
     *
     * @param  array{name: string}  $attributes
     */
    public function updateTeam(Team $team, array $attributes): Team
    {
        $team->update([
            'name' => $attributes['name'],
        ]);

        return $team;
    }

    /**
     * Add a member to the team.
     */
    public function addMember(Team $team, User $user): void
    {
        $team->members()->syncWithoutDetaching([$user->id]);
    }

    /**
     * Remove a member from the team.
     */
    public function removeMember(Team $team, User $user): void
    {
        $team->members()->detach($user->id);
    }
}
