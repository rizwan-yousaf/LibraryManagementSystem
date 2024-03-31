<?php

namespace App\Actions\Api\Patrons;

use App\Models\Book;
use App\Models\BookPatron;
use Illuminate\Http\Request;
use Lorisleiva\Actions\Concerns\AsAction;
use App\Http\Requests\Patron\BookBorrowRequest;
use App\Http\Resources\Patron\BookBorrowResource;

class PatronToReturnBook
{
    use AsAction;

    public function handle(Request $request)
    {
        $bookId = $request->input('bookId');
        $patronId = $request->input('patronId');

        $bookReturn = BookPatron::where('book_id', $bookId)
            ->where('patron_id', $patronId)
            ->whereNull('returned_at')
            ->first();

        if ($bookReturn) {
            // Update the returned_at timestamp
            $bookReturn->returned_at = now();
            $bookReturn->save();

            // Update book status to indicate availability
            $book = Book::findOrFail($bookId);
            $book->is_available = true;
            $book->save();

            return $bookReturn;
           
        }
        return $bookReturn;
    }

    public function asController(BookBorrowRequest $request)
    {
        return $this->handle($request);
    }


    public function jsonResponse($bookReturn, Request $request)
    {
        if ($bookReturn) {
            if ($request->wantsJson()) {
                return response()->json(['success' => true, 'message' => 'Book returned successfully.', 'data' => new BookBorrowResource($bookReturn)], 200);
            }
        } 
        else {
            return response()->json(['success' => false, 'message' => 'Book has already been returned or was not borrowed by the patron.'], 404);
        }
    }
}
