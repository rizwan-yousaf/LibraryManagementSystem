<?php

namespace App\Actions\Api\Patrons;

use App\Models\Patron;
use Illuminate\Http\Request;
use Lorisleiva\Actions\Concerns\AsAction;
use App\Http\Resources\Patron\PatronResource;
use App\Http\Requests\Patron\UpdatePatronRequest;
use Illuminate\Database\Eloquent\ModelNotFoundException;

class UpdatePatron
{
    use AsAction;

    public function handle($request, $id)
    {
        try {
            $patron = Patron::findOrFail($id);
            $patron->update($request->all());
            return $patron;
        } catch (ModelNotFoundException $e) {
            return null;
        }
    }

    public function getControllerMiddleware(): array
    {
        return ['auth'];
    }

    public function asController(UpdatePatronRequest $request, $id)
    {
        return $this->handle($request, $id);
    }

    public function jsonResponse($patron, Request $request)
    {
        if (is_null($patron)) {
            return response()->json(['success' => false, 'message' => 'Patron not found.'], 404);
        }

        if ($request->wantsJson()) {
            return response()->json(['success' => true, 'message' => 'Patron Updated Successfully', 'data' => new PatronResource($patron)], 200);
        }
        return new PatronResource($patron);
    }
}
