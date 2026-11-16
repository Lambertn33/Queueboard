<?php

namespace App\Services\Users;

use App\Enums\RoleName;
use App\Models\Role;
use App\Models\User;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\DB;

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

    /**
     * Create a member with the default password.
     *
     * @param  array{name: string, email: string, roles: list<string>}  $attributes
     */
    public function createUser(array $attributes): User
    {
        return DB::transaction(function () use ($attributes): User {
            $user = User::query()->create([
                'name' => $attributes['name'],
                'email' => $attributes['email'],
                'password' => 'password',
            ]);

            $this->syncMemberRoles($user, $attributes['roles']);

            return $user;
        });
    }

    /**
     * Update a member's name, email, and roles.
     *
     * @param  array{name: string, email: string, roles: list<string>}  $attributes
     */
    public function updateUser(User $user, array $attributes): User
    {
        return DB::transaction(function () use ($user, $attributes): User {
            $user->update([
                'name' => $attributes['name'],
                'email' => $attributes['email'],
            ]);

            $this->syncMemberRoles($user, $attributes['roles']);

            return $user;
        });
    }

    /**
     * @param  list<string>  $roles
     */
    private function syncMemberRoles(User $user, array $roles): void
    {
        $roleIds = Role::query()
            ->whereIn('name', $roles)
            ->pluck('id');

        $user->roles()->sync($roleIds);
    }
}
