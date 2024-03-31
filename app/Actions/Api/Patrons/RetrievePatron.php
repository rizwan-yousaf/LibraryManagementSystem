<?php

namespace App\Actions\Api\Patrons;

use App\Models\Patron;
use Illuminate\Http\Request;
use Lorisleiva\Actions\Concerns\AsAction;
use App\Http\Resources\Patron\PatronResource;

class RetrievePatron
{
    use AsAction;

    public function handle($request)
    {
        $patrons = Patron::get();
        return $patrons;
    }

    public function getControllerMiddleware(): array
    {
        return ['auth'];
    }

    public function asController(Request $request)
    {
        return $this->handle($request);
    }

    public function jsonResponse($patrons, Request $request)
    {
        // If no patrons found, return a custom response
        if ($patrons->isEmpty()) {
            return response()->json(['success' => false, 'message' => 'No Patrons Found.'], 404);
        }

        if ($request->wantsJson()) {
            return response()->json(['success' => true, 'message' => 'Patrons Listing', 'data' => PatronResource::collection($patrons)], 200);
        }
        return PatronResource::collection($patrons);
    }
}
