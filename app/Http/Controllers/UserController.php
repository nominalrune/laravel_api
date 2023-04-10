<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Foundation\Http\FormRequest;

class UserController extends Controller
{
    public function index()
    {
        return User::all();
    }
    public function show(int $id)
    {
        return User::findOrFail($id);
    }
    public function store(FormRequest $request)
    {
        $user = User::create($request->validated());
        return $user;
    }
    public function update(FormRequest $request, int $id)
    {
        $user = User::findOrFail($id);
        $user->update($request->validated());
        return $user;
    }
    public function destroy(int $id)
    {
        $user = User::findOrFail($id);
        $user->delete();
        return response()->json(null, 204);
    }
}
