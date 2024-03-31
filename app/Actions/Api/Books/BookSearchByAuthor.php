<?php

namespace App\Actions\Api\Books;

use App\Models\Book;
use Illuminate\Http\Request;
use App\Http\Resources\Book\BookResource;
use Lorisleiva\Actions\Concerns\AsAction;
use App\Http\Requests\Book\SearchBookRequest;

class BookSearchByAuthor
{
    use AsAction;

    public function handle(Request $request)
    {
        $author = $request->author;
        $books = Book::whereHas('authors', function ($query) use ($author) {
            $query->where('first_name', 'like', '%' . $author . '%')
                ->orWhere('last_name', 'like', '%' . $author . '%');
        })->get();

        return $books;
    }

    public function getControllerMiddleware(): array
    {
        return ['auth'];
    }

    public function asController(SearchBookRequest $request)
    {
        return $this->handle($request);
    }


    public function jsonResponse($books, Request $request)
    {
        // If author is null or the resulting collection is empty, return a custom response
        if (!$request->filled('author') || $books->isEmpty()) {
            return response()->json(['success' => false, 'message' => 'Books Not Available'], 404);
        }

        return response()->json(['success' => true, 'message' => 'Books Found Successfully', 'data' => BookResource::collection($books)], 200);
    }
}
