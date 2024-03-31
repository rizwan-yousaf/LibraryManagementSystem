<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Patron extends Model
{
    use HasFactory;

    protected $fillable = ['name', 'email'];

    public function borrowedBooks()
    {
        return $this->belongsToMany(Book::class, 'book_patron')
        ->withPivot('borrowed_at', 'due_back_at', 'returned_at')
        ->withTimestamps();
    }
}
