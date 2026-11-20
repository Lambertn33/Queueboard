<?php

namespace App\Http\Controllers;

use App\Http\Requests\Teams\StoreTeamRequest;
use App\Http\Requests\Teams\UpdateTeamRequest;
use App\Models\Team;
use App\Services\Teams\TeamsService;
use App\Services\Users\UsersService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Inertia\Response;

class TeamController extends Controller
{
    /**
     * Create a new controller instance.
     */
    public function __construct(
        private TeamsService $teamsService,
        private UsersService $usersService,
    ) {}

    /**
     * List the teams, one page at a time.
     */
    public function index(Request $request): Response
    {
        return Inertia::render('Teams/Index', [
            'teams' => $this->teamsService->getTeams(
                perPage: (int) $request->query('per_page', 10),
                page: (int) $request->query('page', 1),
            ),
            'assignableMembers' => $request->user()?->can('addTeamMember')
                ? $this->usersService->getAssignableMembers()
                : [],
        ]);
    }

    /**
     * Create a team.
     */
    public function store(StoreTeamRequest $request): RedirectResponse
    {
        $this->teamsService->createTeam($request->safe()->only(['name']));

        return redirect()->route('teams.index');
    }

    /**
     * Update a team's name.
     */
    public function update(UpdateTeamRequest $request, Team $team): RedirectResponse
    {
        $this->teamsService->updateTeam($team, $request->safe()->only(['name']));

        return redirect()->route('teams.index');
    }
}
