<?php

namespace App\Http\Controllers;

use App\Models\Permission;
use App\Models\User;
use App\Services\PermissionService;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\HTTP\Request;

class UserController extends Controller
{
    public function index(Request $request)
    {
        $users = $request->user()->permissions()->where('permissionable_type', User::class)->with('permissionable')->get()->pluck('permissionable');

        return $users;
    }

    public function show(Request $request, int $id)
    {
        $user = User::findOrFail($id);
        if (! PermissionService::can($request->user(), 'view', $user)) {
            abort(404);
        }

        return response()->json($user);
    }

    public function store(FormRequest $request)
    {
        if (! PermissionService::can($request->user(), Permission::CREATE, User::class)) {
            abort(404);
        }
        $user = User::create($request->validated());

        return response()->json($user);
    }

    public function update(FormRequest $request, int $id)
    {
        $user = User::findOrFail($id);
        if (! PermissionService::can($request->user(), Permission::UPDATE, $user)) {
            abort(404);
        }
        $user->update($request->validated());

        return $user;
    }

    public function destroy(Request $request, int $id)
    {
        $user = User::findOrFail($id);
        if (! PermissionService::can($request->user(), Permission::DELETE, $user)) {
            abort(404);
        }
        $user->delete();

        return response()->json(null, 204);
    }
}
