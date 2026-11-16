<?php

namespace App\Http\Controllers;

use App\Http\Requests\Teams\RemoveTeamMemberRequest;
use App\Http\Requests\Teams\StoreTeamMemberRequest;
use App\Models\Team;
use App\Models\User;
use App\Services\Teams\TeamsService;
use Illuminate\Http\RedirectResponse;

class TeamMemberController extends Controller
{
    /**
     * Create a new controller instance.
     */
    public function __construct(private TeamsService $teamsService) {}

    /**
     * Add a member to the team.
     */
    public function store(StoreTeamMemberRequest $request, Team $team): RedirectResponse
    {
        /** @var User $member */
        $member = User::query()->findOrFail($request->integer('user_id'));

        $this->teamsService->addMember($team, $member);

        return redirect()->route('teams.index');
    }

    /**
     * Remove a member from the team.
     */
    public function destroy(RemoveTeamMemberRequest $request, Team $team, User $user): RedirectResponse
    {
        abort_unless(
            $team->members()->where('users.id', $user->id)->exists(),
            404,
        );

        $this->teamsService->removeMember($team, $user);

        return redirect()->route('teams.index');
    }
}
