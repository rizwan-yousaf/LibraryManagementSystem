<?php

namespace App\Actions\Api\Auth;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Lorisleiva\Actions\Concerns\AsAction;

class AuthLogout
{
    use AsAction;

    public function handle($request)
    {
        $user = Auth::user();
        $user->tokens()->delete();

        return true;
    }

    public function asController(Request $request)
    {
        return $this->handle($request);
    }

    public function jsonResponse($response, Request $request)
    {
        if ($request->wantsJson()) {
            return response()->json(['success' => true, 'message' => 'Successfully logged out.', 'data' => []], 200);
        }
    }
}
