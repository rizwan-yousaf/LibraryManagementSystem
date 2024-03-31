<?php

namespace App\Actions\Api\Authors;

use App\Models\Author;
use Illuminate\Http\Request;
use Lorisleiva\Actions\Concerns\AsAction;
use App\Http\Resources\Author\AuthorResource;
use App\Http\Requests\Author\CreateAuthorRequest;
use Illuminate\Database\Eloquent\ModelNotFoundException;

class UpdateAuthor
{
    use AsAction;

    public function handle($request, $id)
    {
        try {
            $author = Author::findOrFail($id);
            $author->update($request->all());
            return $author;
        } catch (ModelNotFoundException $e) {
            return null; 
        }
    }

    public function getControllerMiddleware(): array
    {
        return ['auth'];
    }

    public function asController(CreateAuthorRequest $request, $id)
    {
        return $this->handle($request, $id);
    }

    public function jsonResponse($author, Request $request)
    {
        if (is_null($author)) {
            return response()->json(['success' => false, 'message' => 'Author not found.'], 404);
        }

        if ($request->wantsJson()) {
            return response()->json(['success' => true, 'message' => 'Author Updated Successfully', 'data' => new AuthorResource($author)], 200);
        }
        return new AuthorResource($author);
    }
}
