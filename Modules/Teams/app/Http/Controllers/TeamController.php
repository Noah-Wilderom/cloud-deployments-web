<?php

namespace Modules\Teams\Http\Controllers;

use Illuminate\Http\RedirectResponse;
use Illuminate\Routing\Controller;
use Modules\Teams\Http\Requests\StoreTeamRequest;
use Modules\Teams\Models\Team;
use Inertia\Response as InertiaResponse;

class TeamController extends Controller
{
    public function index(): InertiaResponse {
        return inertia("Teams::Teams/Index", [
            "teams" => Team::all(),
        ]);
    }
    public function show(Team $team): InertiaResponse {
        return inertia("Teams::Teams/Show", [
            "team" => $team,
        ]);
    }
    public function store(StoreTeamRequest $request): RedirectResponse {
        $team = auth()->user()->teams()->create([
            "name" => $request->get("name"),
        ]);

        return redirect()->back()->with("notification", [
            "type" => "success",
            "title" => "Team created",
            "message" => sprintf("Team %s has been created", $team->name),
        ]);
    }
}
