<?php

namespace App\Http\Controllers;

use App\Http\Requests\Users\StoreUserRequest;
use App\Http\Requests\Users\UpdateUserRequest;
use App\Models\User;
use App\Services\Users\UsersService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Inertia\Response;

class UserController extends Controller
{
    /**
     * Create a new controller instance.
     */
    public function __construct(private UsersService $usersService) {}

    /**
     * List the members, one page at a time.
     */
    public function index(Request $request): Response
    {
        return Inertia::render('Members/Index', [
            'members' => $this->usersService->getUsers(
                perPage: (int) $request->query('per_page', 10),
                page: (int) $request->query('page', 1),
            ),
        ]);
    }

    /**
     * Create a member.
     */
    public function store(StoreUserRequest $request): RedirectResponse
    {
        $this->usersService->createUser($request->safe()->only(['name', 'email']));

        return redirect()->route('members.index');
    }

    /**
     * Update a member.
     */
    public function update(UpdateUserRequest $request, User $user): RedirectResponse
    {
        $this->usersService->updateUser($user, $request->safe()->only(['name', 'email']));

        return redirect()->route('members.index');
    }
}
