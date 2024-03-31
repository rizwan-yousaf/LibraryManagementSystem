<?php

namespace App\Actions\Api\Auth;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use App\Http\Resources\User\UserResource;
use Lorisleiva\Actions\Concerns\AsAction;
use App\Http\Requests\Auth\RegisterRequest;

class AuthRegister
{
    use AsAction;

    public function handle($request)
    {
        $user = User::create([
            "name" => $request->name,
            "email" => $request->email,
            "password" => Hash::make($request->password),
        ]);
        return $user;
    }

    public function asController(RegisterRequest $request)
    {
        return $this->handle($request);
    }

    public function jsonResponse($user, Request $request)
    {
        if ($request->wantsJson()) {
            $token = $user->createToken("API TOKEN")->accessToken;
            return response()->json(['success' => true, 'message' => 'User created successfully', 'data' => new UserResource($user), 'token' => $token], 200);
        }
        return new UserResource($user);
    }
}
