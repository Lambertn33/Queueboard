<?php

namespace App\Services\Teams;

use App\Models\Team;
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
}
