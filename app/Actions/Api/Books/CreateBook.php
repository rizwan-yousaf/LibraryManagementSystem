<?php

namespace App\Actions\Api\Books;

use App\Models\Book;
use Illuminate\Http\Request;
use App\Http\Resources\Book\BookResource;
use Lorisleiva\Actions\Concerns\AsAction;
use App\Http\Requests\Book\CreateBookRequest;

class CreateBook
{
    use AsAction;

    public function handle($request)
    {
        $book = Book::create($request->all());
        $book->authors()->attach($request->author_ids);
        return $book;
    }

    public function getControllerMiddleware(): array
    {
        return ['auth'];
    }

    public function asController(CreateBookRequest $request)
    {
        return $this->handle($request);
    }

    public function jsonResponse($book, Request $request)
    {
        if ($request->wantsJson()) {
            return response()->json(['success' => true, 'message' => 'Book Created Successfully', 'data' => new BookResource($book)], 200);
        }
        return new BookResource($book);
       
    }
}
