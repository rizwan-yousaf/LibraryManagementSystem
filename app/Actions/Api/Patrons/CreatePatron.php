<?php

namespace App\Actions\Api\Patrons;

use App\Models\Patron;
use Illuminate\Http\Request;
use Lorisleiva\Actions\Concerns\AsAction;
use App\Http\Resources\Patron\PatronResource;
use App\Http\Requests\Patron\CreatePatronRequest;

class CreatePatron
{
    use AsAction;

    public function handle($request)
    {
        $patron = Patron::create($request->all());
        return $patron;
    }

    public function getControllerMiddleware(): array
    {
        return ['auth'];
    }

    public function asController(CreatePatronRequest $request)
    {
        return $this->handle($request);
    }

    public function jsonResponse($patron, Request $request)
    {
        if ($request->wantsJson()) {
            return response()->json(['success' => true, 'message' => 'Patron Created Successfully', 'data' => new PatronResource($patron)], 200);
        }
        return new PatronResource($patron);
    }
}
