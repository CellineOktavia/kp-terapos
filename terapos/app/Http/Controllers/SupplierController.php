<?php

namespace App\Http\Controllers;

use App\Models\Supplier;
use Illuminate\Http\Request;

class SupplierController extends Controller
{
    public function index(Request $request)
    {
        $search = $request->search;

        $suppliers = Supplier::query()

            ->when($search, function ($query) use ($search) {

                $query->where(
                    'nama_supplier',
                    'like',
                    "%{$search}%"
                )

                    ->orWhere(
                        'kode_supplier',
                        'like',
                        "%{$search}%"
                    );
            })

            ->latest()

            ->paginate(10)

            ->withQueryString();

        return view(
            'supplier.index',
            compact(
                'suppliers',
                'search'
            )
        );
    }

    public function create()
    {
        return view('supplier.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([

            'nama_supplier' => 'required',

            'nama_kontak' => 'required',

            'telepon' => 'required',

            'email' => 'nullable|email',

            'alamat' => 'nullable',

            'aktif' => 'required',

        ]);

        $lastSupplier = Supplier::select('kode_supplier')
            ->get()
            ->sortByDesc(function ($item) {
                return (int) preg_replace(
                    '/[^0-9]/',
                    '',
                    $item->kode_supplier
                );
            })
            ->first();

        if ($lastSupplier) {

            $lastNumber = (int) preg_replace(
                '/[^0-9]/',
                '',
                $lastSupplier->kode_supplier
            );

            $newNumber = $lastNumber + 1;
        } else {

            $newNumber = 1;
        }

        $validated['kode_supplier'] =
            'SUP' .
            str_pad(
                $newNumber,
                3,
                '0',
                STR_PAD_LEFT
            );

        Supplier::create($validated);

        return redirect()
            ->route('supplier.index')
            ->with(
                'success',
                'Supplier berhasil ditambahkan'
            );
    }

    public function edit(Supplier $supplier)
    {
        return view(
            'supplier.edit',
            compact('supplier')
        );
    }

    public function update(
        Request $request,
        Supplier $supplier
    ) {

        $validated = $request->validate([

            'nama_supplier' => 'required',

            'nama_kontak' => 'required',

            'telepon' => 'required',

            'email' => 'nullable|email',

            'alamat' => 'nullable',

            'aktif' => 'required',

        ]);

        $supplier->update($validated);

        return redirect()
            ->route('supplier.index')
            ->with(
                'success',
                'Supplier berhasil diperbarui'
            );
    }

    public function destroy(Supplier $supplier)
    {
        $supplier->delete();

        return redirect()
            ->route('supplier.index')
            ->with(
                'success',
                'Supplier berhasil dihapus'
            );
    }

    public function show(
        Supplier $supplier
    ) {
        $supplier->load('products');

        return view(
            'supplier.show',
            compact('supplier')
        );
    }
}
