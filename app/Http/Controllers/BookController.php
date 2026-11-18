<?php

namespace App\Http\Controllers;

use App\Models\Book;
use Illuminate\Http\Request;

class BookController extends Controller
{
    public function index()
    {
        // Perbaikan: nama variabel yang dikirim ke view diubah dari
        // $daftarBuku menjadi $books, karena Blade index.blade.php
        // memakai @forelse ($books as $book).
        $books = Book::latest()->get();

        return view('books.index', compact('books'));
    }

    public function create()
    {
        return view('books.create');
    }

    public function store(Request $request)
    {
        // Perbaikan: tambahkan validasi agar data tidak kosong/berkelebihan.
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'author' => 'required|string|max:255',
            'published_year' => 'required|integer|min:1000|max:'.(date('Y') + 1),
        ], [
            'title.required' => 'Judul buku wajib diisi.',
            'author.required' => 'Penulis wajib diisi.',
            'published_year.required' => 'Tahun terbit wajib diisi.',
            'published_year.integer' => 'Tahun terbit harus berupa angka.',
            'published_year.min' => 'Tahun terbit minimal 1000.',
        ]);

        // Perbaikan: data harus benar-benar disimpan ke database.
        // Versi awal hanya membuat objek Book tanpa save(), sehingga
        // buku tidak pernah masuk tabel dan halaman daftar tetap kosong.
        $book = new Book($validated);
        $book->save();

        return redirect()->route('books.index')
            ->with('success', 'Buku berhasil ditambahkan.');
    }

    public function destroy($id)
    {
        $book = Book::findOrFail($id);
        $book->delete();

        return redirect()->route('books.index')
            ->with('success', 'Buku berhasil dihapus.');
    }
}
