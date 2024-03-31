<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BookPatron extends Model
{
    use HasFactory;

    protected $table = 'book_patron';

    protected $guarded = [];

    public function patron()
    {
        return $this->belongsTo(Patron::class);
    }

    public function book()
    {
        return $this->belongsTo(Book::class);
    }
}
