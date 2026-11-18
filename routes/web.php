<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\BookController;

Route::get('/', fn () => redirect('/books'));

// Perbaikan: nama route POST diubah dari 'books.simpan' menjadi 'books.store'.
// Blade create.blade.php memanggil route('books.store'), sehingga nama harus sama.
Route::post('/books', [BookController::class, 'store'])->name('books.store');

// Perbaikan: hapus buku memakai HTTP DELETE, bukan PUT.
// Blade mengirim @method('DELETE'), jadi method route harus 'delete'.
Route::delete('/books/{id}', [BookController::class, 'destroy'])->name('books.destroy');

Route::get('/books', [BookController::class, 'index'])->name('books.index');
Route::get('/books/create', [BookController::class, 'create'])->name('books.create');
