<?php

use Illuminate\Http\Request;
use App\Actions\Api\Auth\AuthLogin;
use App\Actions\Api\Auth\AuthLogout;
use App\Actions\Api\Books\CreateBook;
use App\Actions\Api\Books\DeleteBook;
use App\Actions\Api\Books\UpdateBook;
use Illuminate\Support\Facades\Route;
use App\Actions\Api\Auth\AuthRegister;
use App\Actions\Api\Books\RetrieveBooks;
use App\Actions\Api\Authors\CreateAuthor;
use App\Actions\Api\Authors\DeleteAuthor;
use App\Actions\Api\Authors\UpdateAuthor;
use App\Actions\Api\Patrons\CreatePatron;
use App\Actions\Api\Patrons\DeletePatron;
use App\Actions\Api\Patrons\UpdatePatron;
use App\Actions\Api\Patrons\RetrievePatron;
use App\Actions\Api\Authors\RetrieveAuthors;
use App\Actions\Api\Books\BookSearchByTitle;
use App\Actions\Api\Books\BookSearchByAuthor;
use App\Actions\Api\Patrons\PatronToBorrowBook;
use App\Actions\Api\Patrons\PatronToReturnBook;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

// Auth Api's
Route::group(['prefix' => 'v1/auth', 'middleware' => 'throttle:15,3'], function () {

    Route::post('register', AuthRegister::class)->name('auth.register');
    Route::post('login', AuthLogin::class)->name('auth.login');
   
});

Route::middleware(['auth:api', 'throttle:15,3'])->group(function () {

    // Book Api's
    Route::group(['prefix' => 'v1/book'], function () {

        Route::POST('/store', CreateBook::class)->name('book.store');
        Route::POST('/{id}/update', UpdateBook::class)->name('book.update');
        Route::DELETE('/{id}/delete', DeleteBook::class)->name('book.delete');
        Route::GET('/listing', RetrieveBooks::class)->name('book.listing');
        Route::GET('/search/{title?}/{author?}', BookSearchByTitle::class)->name('book.search');
        Route::GET('/by-author/{author?}', BookSearchByAuthor::class)->name('book.search.by.author');
        
    });

    // Author Api's
    Route::group(['prefix' => 'v1/author'], function () {

        Route::POST('/store', CreateAuthor::class)->name('author.store');
        Route::POST('/{id}/update', UpdateAuthor::class)->name('author.update');
        Route::DELETE('/{id}/delete', DeleteAuthor::class)->name('author.delete');
        Route::GET('/listing', RetrieveAuthors::class)->name('author.listing');
        
    });

    // Patron Api's
    Route::group(['prefix' => 'v1/patron'], function () {

        Route::POST('/store', CreatePatron::class)->name('patron.store');
        Route::POST('/{id}/update', UpdatePatron::class)->name('patron.update');
        Route::DELETE('/{id}/delete', DeletePatron::class)->name('patron.delete');
        Route::GET('/listing', RetrievePatron::class)->name('patron.listing');
        Route::POST('/books-borrow', PatronToBorrowBook::class)->name('books.borrow');
        Route::POST('/books-return', PatronToReturnBook::class)->name('books.return');
        
    });

    // Auth Logout Api's
    Route::group(['prefix' => 'v1/auth'], function () {

        Route::post('logout', AuthLogout::class)->name('auth.logout');
        
    });

});
