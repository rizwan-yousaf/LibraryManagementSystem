<?php

namespace App\Actions\Api\Books;

use App\Models\Book;
use Lorisleiva\Actions\Concerns\AsAction;
use Illuminate\Database\Eloquent\ModelNotFoundException;

class DeleteBook
{
    use AsAction;

    public function handle(Book $book): Book
    {
        $book->authors()->detach();
        $book->delete();
        return $book;
    }

    public function getControllerMiddleware(): array
    {
        return ['auth'];
    }

    public function asController($id)
    {
        try {
            $book = Book::findOrFail($id);
            return $this->handle($book);
        } catch (ModelNotFoundException $e) {
            return response([
                'error' => 'Key Not Found'
            ], 404);
        }
    }

    public function jsonResponse($response)
    {
        if (isset($response->id)) {
            $message = $response == true ? 'Book Deleted Successfully' : 'Sorry! Unable to delete';
            return response()->json(['success' => true, 'message' => $message], 200);
        } else {
            return response()->json(['success' => false, 'message' => 'Sorry! Unable to delete'], 404);
        }
    }
}
