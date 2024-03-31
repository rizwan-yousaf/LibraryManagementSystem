<?php

namespace App\Actions\Api\Books;

use App\Models\Book;
use Illuminate\Http\Request;
use App\Http\Resources\Book\BookResource;
use Lorisleiva\Actions\Concerns\AsAction;

class RetrieveBooks
{
    use AsAction;

    public function handle($request)
    {
        $books = Book::with('authors')->get();
        return $books;
    }

    public function getControllerMiddleware(): array
    {
        return ['auth'];
    }

    public function asController(Request $request)
    {
        return $this->handle($request);
    }

    public function jsonResponse($books, Request $request)
    {
        // If no books found, return a custom response
        if ($books->isEmpty()) {
            return response()->json(['success' => false, 'message' => 'No books found.'], 404);
        }

        if ($request->wantsJson()) {
            return response()->json(['success' => true, 'message' => 'Books Listing', 'data' => BookResource::collection($books)], 200);
        }
        return BookResource::collection($books);
    }
}
