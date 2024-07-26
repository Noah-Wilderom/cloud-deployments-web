<?php

namespace Modules\Teams\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Permission;
use App\Models\Role;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Inertia\Inertia;
use Modules\Teams\Tables\Roles\Users;

class RoleController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return inertia("Teams::Roles/Index", [
            "roles" => Role::query()
                ->withCount(["permissions", "users"])
                ->orderByDesc("users_count")
                ->get()
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request): RedirectResponse
    {
        //
    }

    /**
     * Show the specified resource.
     */
    public function show(Role $role)
    {
        $role->loadMissing(["permissions", "users"]);
        return inertia("Teams::Roles/Show", [
            "role" => $role,
            "permissions" => Permission::query()
                ->orderByDesc("group")
                ->get()
                ->groupBy("group")
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id)
    {
        return view('teams::edit');
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Role $role): RedirectResponse
    {
        $permissions = collect($request->get("permissions"))
            ->reject(fn ($permission) => is_null($permission) || $permission === false)
            ->keys();

        $role->syncPermissions($permissions);

        return back()->with("notification", [
            "type" => "success",
            "title" => sprintf("%s updated", $role->name),
            "description" => sprintf("%s now has %d permissions", $role->name, $role->permissions()->count())
        ]);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        //
    }
}
