<?php

namespace App\Http\Resources\Patron;

use Illuminate\Http\Request;
use App\Http\Resources\Book\BookResource;
use App\Http\Resources\Patron\PatronResource;
use Illuminate\Http\Resources\Json\JsonResource;

class BookBorrowResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'borrowed_at' => $this->borrowed_at,
            'due_back_at' => $this->due_back_at,
            'returned_at' => $this->returned_at,
            'patron' => new PatronResource($this->whenLoaded('patron')),
            'book' => new BookResource($this->whenLoaded('book'))
        ];
    }
}
