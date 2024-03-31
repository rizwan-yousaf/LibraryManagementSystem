<?php

namespace App\Http\Resources\Book;

use Illuminate\Http\Request;
use App\Http\Resources\Author\AuthorResource;
use Illuminate\Http\Resources\Json\JsonResource;

class BookResource extends JsonResource
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
            'title' => $this->title,
            'description' => $this->description,
            'isbn' => $this->isbn,
            'publication_date' => $this->publication_date,
            'created_at' => $this->created_at,
            'authors' => AuthorResource::collection($this->whenLoaded('authors')),
        ];
    }
}
