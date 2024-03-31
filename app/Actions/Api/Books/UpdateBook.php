<?php

namespace App\Actions\Api\Books;

use App\Models\Book;
use Illuminate\Http\Request;
use App\Http\Resources\Book\BookResource;
use Lorisleiva\Actions\Concerns\AsAction;
use App\Http\Requests\Book\UpdateBookRequest;

class UpdateBook
{
    use AsAction;

    public function handle($request, $id)
    {
        $book = Book::find($id);
        if (!$book) {
            return $book;
        }
        $book->update($request->all());
        $book->authors()->sync($request->author_ids);
        return $book;
    }

    public function getControllerMiddleware(): array
    {
        return ['auth'];
    }

    public function asController(UpdateBookRequest $request, $id)
    {
        return $this->handle($request, $id);
    }

    public function jsonResponse($book, Request $request)
    {
        if($book){
            if ($request->wantsJson()) {
                return response()->json(['success' => true, 'message' => 'Book Updated Successfully', 'data' => new BookResource($book)], 200);
            }
            return new BookResource($book);
        }
        return response()->json(['success' => false, 'message' => 'Book not found'], 404);
       
    }
}
