<?php

namespace Tests\Feature;

use App\Models\Book;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class BookCrudTest extends TestCase
{
    use RefreshDatabase;

    /**
     * Gejala 1: "Halaman daftar buku tidak dapat dibuka dengan benar."
     * Penyebab: Controller mengirim $daftarBuku, Blade meminta $books.
     */
    public function test_halaman_daftar_buku_dapat_diakses()
    {
        Book::create([
            'title' => 'Laravel untuk Pemula',
            'author' => 'Andi',
            'published_year' => 2024,
        ]);

        $response = $this->get('/books');

        $response->assertOk();
        $response->assertViewHas('books');
        $response->assertSee('Laravel untuk Pemula');
        $response->assertSee('Andi');
    }

    /**
     * Gejala 2: "Form tambah buku memiliki perilaku yang tidak sesuai."
     * Penyebab: route bernama books.simpan tidak pernah dipanggil Blade.
     */
    public function test_halaman_form_tambah_buku_dapat_diakses()
    {
        $response = $this->get('/books/create');

        $response->assertOk();
        $response->assertViewIs('books.create');
        $response->assertSee('Tambah Buku');
    }

    /**
     * Gejala 3: "Data buku tidak tampil setelah proses penyimpanan."
     * Penyebab: store() tidak pernah memanggil save(), sehingga data hilang.
     */
    public function test_buku_berhasil_disimpan_dan_muncul_di_daftar()
    {
        $response = $this->post('/books', [
            'title' => 'Belajar PHP',
            'author' => 'Budi',
            'published_year' => 2023,
        ]);

        $response->assertRedirect(route('books.index'));
        $response->assertSessionHas('success');

        // Data benar-benar tersimpan di database.
        $this->assertDatabaseHas('books', [
            'title' => 'Belajar PHP',
            'author' => 'Budi',
            'published_year' => 2023,
        ]);

        // Dan tampil saat daftar dibuka kembali.
        $this->get('/books')->assertSee('Belajar PHP');
    }

    /**
     * Gejala 3 (validasi): form menolak data tidak lengkap.
     * Penyebab: versi awal tidak punya validasi sama sekali.
     */
    public function test_validasi_menolak_data_tidak_lengkap()
    {
        $response = $this->post('/books', [
            'title' => '',
            'author' => '',
            'published_year' => '',
        ]);

        $response->assertSessionHasErrors(['title', 'author', 'published_year']);
        $this->assertDatabaseCount('books', 0);
    }

    public function test_validasi_menolak_tahun_terbit_tidak_valid()
    {
        $response = $this->post('/books', [
            'title' => 'Buku Uji',
            'author' => 'Citra',
            'published_year' => 'abc',
        ]);

        $response->assertSessionHasErrors('published_year');
        $this->assertDatabaseCount('books', 0);
    }

    /**
     * Gejala 4: "Tombol hapus tidak bekerja sebagaimana mestinya."
     * Penyebab: route hapus memakai method PUT, sedangkan Blade mengirim DELETE.
     */
    public function test_buku_berhasil_dihapus()
    {
        $book = Book::create([
            'title' => 'Buku Yang Dihapus',
            'author' => 'Dewi',
            'published_year' => 2022,
        ]);

        $response = $this->delete('/books/' . $book->id);

        $response->assertRedirect(route('books.index'));
        $response->assertSessionHas('success');

        $this->assertDatabaseMissing('books', ['id' => $book->id]);
    }

    public function test_hapus_buku_yang_tidak_ada_menghasilkan_404()
    {
        $this->delete('/books/999')->assertNotFound();
    }

    /**
     * Memastikan route destroy terdaftar dengan method DELETE,
     * bukan PUT seperti versi awal.
     */
    public function test_route_destroy_terdaftar_dengan_method_delete()
    {
        $route = \Illuminate\Support\Facades\Route::getRoutes()->getByName('books.destroy');

        $this->assertNotNull($route, 'Route books.destroy tidak terdaftar.');
        $this->assertContains('DELETE', $route->methods());
        $this->assertNotContains('PUT', $route->methods());
    }
}
