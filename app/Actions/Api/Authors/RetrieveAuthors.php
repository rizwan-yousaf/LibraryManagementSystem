<?php

namespace App\Actions\Api\Authors;

use App\Http\Resources\Author\AuthorResource;
use App\Models\Author;
use Illuminate\Http\Request;
use Lorisleiva\Actions\Concerns\AsAction;

class RetrieveAuthors
{
    use AsAction;

    public function handle($request)
    {
        // $books = Author::with('books')->get();
        $authors = Author::get();
        return $authors;
    }

    public function getControllerMiddleware(): array
    {
        return ['auth'];
    }

    public function asController(Request $request)
    {
        return $this->handle($request);
    }

    public function jsonResponse($authors, Request $request)
    {
        // If no authors found, return a custom response
        if ($authors->isEmpty()) {
            return response()->json(['success' => false, 'message' => 'No Authors Found.'], 404);
        }

        if ($request->wantsJson()) {
            return response()->json(['success' => true, 'message' => 'Authors Listing', 'data' => AuthorResource::collection($authors)], 200);
        }
        return AuthorResource::collection($authors);
    }
}
