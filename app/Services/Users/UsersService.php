<?php

namespace App\Services\Users;

use App\Enums\RoleName;
use App\Enums\TeamRole;
use App\Models\Team;
use App\Models\User;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Pagination\LengthAwarePaginator;

class UsersService
{
    /**
     * List the members for the requested page.
     *
     * @return LengthAwarePaginator<int, array{id: int, name: string, email: string, memberships: list<array{team: string, role: string}>}>
     */
    public function getUsers(int $perPage, int $page): LengthAwarePaginator
    {
        return $this->membersQuery()
            ->with(['teams' => fn ($query) => $query->orderBy('name')->orderBy('id')])
            ->paginate(perPage: $perPage, page: $page)
            ->through(fn (User $user): array => [
                'id' => $user->id,
                'name' => $user->name,
                'email' => $user->email,
                'memberships' => $user->teams
                    ->map(function (Team $team): array {
                        /** @var TeamRole $role */
                        $role = $team->pivot->role;

                        return [
                            'team' => $team->name,
                            'role' => $role->value,
                        ];
                    })
                    ->values()
                    ->all(),
            ]);
    }

    /**
     * List every person who can be assigned to a team.
     *
     * @return list<array{id: int, name: string, email: string}>
     */
    public function getAssignableMembers(): array
    {
        return $this->membersQuery()
            ->get(['id', 'name', 'email'])
            ->map(fn (User $user): array => [
                'id' => $user->id,
                'name' => $user->name,
                'email' => $user->email,
            ])
            ->all();
    }

    /**
     * @return Builder<User>
     */
    private function membersQuery(): Builder
    {
        return User::query()
            ->whereDoesntHave('roles', function ($query): void {
                $query->where('name', RoleName::Admin);
            })
            ->orderBy('name')
            ->orderBy('id');
    }

    /**
     * Create a member with the default password.
     *
     * @param  array{name: string, email: string}  $attributes
     */
    public function createUser(array $attributes): User
    {
        return User::query()->create([
            'name' => $attributes['name'],
            'email' => $attributes['email'],
            'password' => 'password',
        ]);
    }

    /**
     * Update a member's name and email.
     *
     * @param  array{name: string, email: string}  $attributes
     */
    public function updateUser(User $user, array $attributes): User
    {
        $user->update([
            'name' => $attributes['name'],
            'email' => $attributes['email'],
        ]);

        return $user;
    }
}
