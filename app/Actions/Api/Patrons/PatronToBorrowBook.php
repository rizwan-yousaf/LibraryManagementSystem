<?php

namespace App\Actions\Api\Patrons;

use App\Models\Book;
use App\Models\Patron;
use App\Models\BookPatron;
use Illuminate\Http\Request;
use Lorisleiva\Actions\Concerns\AsAction;
use App\Http\Requests\Patron\BookBorrowRequest;
use App\Http\Resources\Patron\BookBorrowResource;

class PatronToBorrowBook
{
    use AsAction;

    public function handle(Request $request)
    {
        $patron = Patron::find($request->patronId);
        $book = Book::find($request->bookId);

        if ($book && $book->is_available) {
            
            $dueBackDate = now()->addWeeks(2);

            $patron->borrowedBooks()->attach($book, [
                'borrowed_at' => now(),
                'due_back_at' => $dueBackDate,
            ]);

            $borrowing = BookPatron::with(['patron', 'book'])->latest()->first();

            // Update book status
            $book->is_available = false;
            $book->save();

            return $borrowing;
        } else{
            // Book is not available for borrowing
            return $borrowing = null;
        }
    }

    public function asController(BookBorrowRequest $request)
    {
        return $this->handle($request);
    }

    
    public function jsonResponse($borrowing, Request $request)
    {
        if ($borrowing) {
            if ($request->wantsJson()) {
                return response()->json(['success' => true, 'message' => 'Book borrowed successfully.', 'data' => new BookBorrowResource($borrowing)], 200);
            }
            return new BookBorrowResource($borrowing);
        } else {
            return response()->json(['success' => false, 'message' => 'Book is not available for borrowing.'], 404);
        }
    }
}
