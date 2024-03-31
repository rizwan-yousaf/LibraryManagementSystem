<?php

namespace App\Actions\Api\Authors;

use App\Models\Author;
use Lorisleiva\Actions\Concerns\AsAction;
use Illuminate\Database\Eloquent\ModelNotFoundException;

class DeleteAuthor
{
    use AsAction;

    public function handle(Author $author): Author
    {
        $author->books()->detach();
        $author->delete();
        return $author;
    }

    public function getControllerMiddleware(): array
    {
        return ['auth'];
    }

    public function asController($id)
    {
        try {
            $author = Author::findOrFail($id);
            return $this->handle($author);
        } catch (ModelNotFoundException $e) {
            return response([
                'error' => 'Key Not Found'
            ], 404);
        }
    }

    public function jsonResponse($response)
    {
        if (isset($response->id)) {
            $message = $response == true ? 'Author Deleted Successfully' : 'Sorry! Unable to delete';
            return response()->json(['success' => true, 'message' => $message], 200);
        } else {
            return response()->json(['success' => false, 'message' => 'Sorry! Unable to delete'], 404);
        }
    }
}
