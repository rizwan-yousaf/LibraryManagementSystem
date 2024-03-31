<?php

namespace App\Actions\Api\Auth;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Http\Requests\Auth\LoginRequest;
use App\Http\Resources\User\UserResource;
use Lorisleiva\Actions\Concerns\AsAction;

class AuthLogin
{
    use AsAction;

    public function handle($request)
    {
        $credentials = $request->only(['email', 'password']);

        if (Auth::attempt($credentials)) {
            $user = User::where('email', $credentials['email'])->first();
            return $user;
        }

        return false;
    }

    public function asController(LoginRequest $request)
    {
        return $this->handle($request);
    }

    public function jsonResponse($user, Request $request)
    {
        if ($request->wantsJson()) {
            if ($user) {
                $token = $user->createToken("API TOKEN")->accessToken;
                
                return response()->json(['success' => true, 'message' => 'User login successfully', 'data' => new UserResource($user), 'token' => $token], 200);
                
            }

            return response()->json(['success' => false, 'message' => 'User authentication failed', 'data' => null], 401); //401 you can change, current need 200

        }

        return new UserResource($user);
    }
}
