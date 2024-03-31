<?php

namespace App\Actions\Api\Books;

use App\Models\Book;
use Illuminate\Http\Request;
use App\Http\Resources\Book\BookResource;
use Lorisleiva\Actions\Concerns\AsAction;
use App\Http\Requests\Book\SearchBookRequest;

class BookSearchByTitle
{
    use AsAction;

    public function handle(Request $request)
    {
        $query = Book::query();

        // Filter by title
        if ($request->filled('title')) {
            $query->where('title', 'like', '%' . $request->input('title') . '%');
        }

        // Filter by author
        if ($request->filled('author')) {
            $authorName = $request->input('author');
            $query->whereHas('authors', function ($authorQuery) use ($authorName) {
                $authorQuery->where(function ($query) use ($authorName) {
                    $query->where('first_name', 'like', '%' . $authorName . '%')
                        ->orWhere('last_name', 'like', '%' . $authorName . '%');
                });
            });
        }

        $books = $query->with('authors')->get();
        return $this->jsonResponse($books, $request);
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
        // If both title and author are null or the resulting collection is empty, return a custom response
        if (!$request->filled('title') && !$request->filled('author') || $books->isEmpty()) {
            return response()->json(['success' => false, 'message' => 'Books Not Available'], 404);
        }

        return response()->json(['success' => true, 'message' => 'Books Found Successfully', 'data' => BookResource::collection($books)], 200);
       
    }
}
