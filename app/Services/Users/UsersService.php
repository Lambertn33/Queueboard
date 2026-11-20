<?php

namespace App\Services\Users;

use App\Enums\RoleName;
use App\Models\User;
use Illuminate\Pagination\LengthAwarePaginator;

class UsersService
{
    /**
     * List the members for the requested page.
     *
     * @return LengthAwarePaginator<int, array{id: int, name: string, email: string}>
     */
    public function getUsers(int $perPage, int $page): LengthAwarePaginator
    {
        return User::query()
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
            ]);
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
