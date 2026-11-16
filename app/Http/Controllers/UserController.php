<?php

namespace App\Http\Controllers;

use App\Services\Users\UsersService;
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
}
