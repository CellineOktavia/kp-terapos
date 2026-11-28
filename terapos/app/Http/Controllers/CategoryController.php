<?php

namespace App\Http\Controllers;

use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;

class CategoryController extends Controller
{
    public function index()
    {
        $categories = Category::withCount(['products' => fn ($query) => $query->where('aktif', true)])
            ->orderBy('nama_kategori')
            ->get();

        return view('kategori.index', compact('categories'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'nama_kategori' => ['required', 'string', 'max:100'],
        ]);
        $duplicate = Category::whereRaw('LOWER(nama_kategori) = ?', [mb_strtolower(trim($validated['nama_kategori']))])->exists();
        if ($duplicate) {
            throw ValidationException::withMessages(['nama_kategori' => 'Nama kategori sudah digunakan.']);
        }

        Category::create($validated);

        return redirect()
            ->route('kategori.index')
            ->with('success', 'Kategori berhasil ditambahkan.');
    }

    public function update(Request $request, Category $category)
    {
        $validated = $request->validate([
            'nama_kategori' => [
                'required',
                'string',
                'max:100',
            ],
        ]);
        $duplicate = Category::where('id', '<>', $category->id)->whereRaw('LOWER(nama_kategori) = ?', [mb_strtolower(trim($validated['nama_kategori']))])->exists();
        if ($duplicate) {
            throw ValidationException::withMessages(['nama_kategori' => 'Nama kategori sudah digunakan.']);
        }

        $category->update($validated);

        return redirect()
            ->route('kategori.index')
            ->with('success', 'Kategori berhasil diperbarui.');
    }

    public function destroy(Category $category)
    {
        $category->delete();

        return redirect()
            ->route('kategori.index')
            ->with('success', 'Kategori berhasil dihapus. Produk terkait kini tanpa kategori.');
    }
}
