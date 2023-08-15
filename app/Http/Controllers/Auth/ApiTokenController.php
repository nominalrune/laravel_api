<?php
namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\CreateApiTokenRequest;
use Illuminate\Support\Facades\Request;

class ApiTokenController extends Controller{
    public function showApiTokens(){

        return response()->json(auth()->user()->tokens);
        // return response()->json(auth()->user()->tokens->map(fn ($item)=>$item->toString()));
    }
    public function createApiToken(CreateApiTokenRequest $request, int $id){
        $token = auth()->user()->createToken($request->name);
        return response()->json(['token' => $token->plainTextToken]);
    }
    public function deleteApiToken(Request $request, int $id, int $token){
        $token = auth()->user()->tokens()->where('id', $token)->first();
        if($token){
            $token->revoke();
            return response()->json(null, 204);
        }
        return response()->json(null, 404);
    }
}
