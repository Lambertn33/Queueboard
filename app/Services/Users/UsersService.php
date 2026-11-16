<?php

namespace App\Services\Users;

use App\Enums\RoleName;
use App\Models\Role;
use App\Models\User;
use Illuminate\Pagination\LengthAwarePaginator;

class UsersService
{
    /**
     * List the members for the requested page.
     *
     * @return LengthAwarePaginator<int, array{id: int, name: string, email: string, roles: list<string>}>
     */
    public function getUsers(int $perPage, int $page): LengthAwarePaginator
    {
        return User::query()
            ->with('roles')
            ->whereHas('roles', function ($query): void {
                $query->whereIn('name', [RoleName::Member, RoleName::ProjectLead]);
            })
            ->whereDoesntHave('roles', function ($query): void {
                $query->where('name', RoleName::Admin);
            })
            ->orderBy('name')
            ->orderBy('id')
            ->paginate(perPage: $perPage, page: $page)
            ->through(fn (User $user): array => [
                'id' => $user->id,
                'name' => $user->name,
                'email' => $user->email,
                'roles' => $user->roles
                    ->map(fn (Role $role): string => $role->name->value)
                    ->sort()
                    ->values()
                    ->all(),
            ]);
    }
}
