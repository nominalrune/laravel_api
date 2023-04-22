<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\LoginRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AuthenticatedSessionController extends Controller
{
    public function show()
    {
        return response()->json(['user' => Auth::user()], 200);
    }

    /**
     * Handle an incoming authentication request.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function store(LoginRequest $request)
    {
        try {
            $request->authenticate();

            $request->session()->regenerate();
            $user = Auth::user();

            return response()->json(['user' => $user], 200);
        } catch (\Exception $e) {
            return response()->json($e, 401);
        }
    }

    /**
     * Destroy an authenticated session.
     *
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $request)
    {
        Auth::guard('web')->logout();

        $request->session()->invalidate();

        $request->session()->regenerateToken();

        return response()->noContent();
    }
}
