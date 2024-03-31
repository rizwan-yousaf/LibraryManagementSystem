<?php

namespace App\Actions\Api\Patrons;

use App\Models\Patron;
use Lorisleiva\Actions\Concerns\AsAction;
use Illuminate\Database\Eloquent\ModelNotFoundException;

class DeletePatron
{
    use AsAction;

    public function handle(Patron $patron): Patron
    {
        $patron->delete();
        return $patron;
    }

    public function getControllerMiddleware(): array
    {
        return ['auth'];
    }

    public function asController($id)
    {
        try {
            $patron = Patron::findOrFail($id);
            return $this->handle($patron);
        } catch (ModelNotFoundException $e) {
            return response([
                'error' => 'Key Not Found'
            ], 404);
        }
    }

    public function jsonResponse($response)
    {
        if (isset($response->id)) {
            $message = $response == true ? 'Patron Deleted Successfully' : 'Sorry! Unable to delete';
            return response()->json(['success' => true, 'message' => $message], 200);
        } else {
            return response()->json(['success' => false, 'message' => 'Sorry! Unable to delete'], 404);
        }
    }
}
