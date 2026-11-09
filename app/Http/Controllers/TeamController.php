<?php

namespace App\Http\Controllers;

use App\Http\Requests\Teams\StoreTeamRequest;
use App\Services\Teams\TeamsService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Inertia\Response;

class TeamController extends Controller
{
    /**
     * Create a new controller instance.
     */
    public function __construct(private TeamsService $teamsService) {}

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
}
