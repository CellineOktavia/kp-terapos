<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\Supplier;
use App\Models\Customer;
use App\Models\Faktur;
use App\Models\Penjualan;
use Barryvdh\DomPDF\Facade\Pdf;

class LaporanController extends Controller
{
    public function index()
    {
        return view('laporan.index');
    }

    public function produk()
    {
        $products =
            Product::latest()->get();

        return view(
            'laporan.produk',
            compact('products')
        );
    }

    public function supplier()
    {
        $suppliers =
            Supplier::latest()->get();

        return view(
            'laporan.supplier',
            compact('suppliers')
        );
    }

    public function customer()
    {
        $customers =
            Customer::latest()->get();

        return view(
            'laporan.customer',
            compact('customers')
        );
    }

    public function pembelian()
    {
        $fakturs =
            Faktur::latest()->get();

        return view(
            'laporan.pembelian',
            compact('fakturs')
        );
    }

    public function penjualan()
    {
        $penjualans =
            Penjualan::latest()->get();

        return view(
            'laporan.penjualan',
            compact('penjualans')
        );
    }

    public function stok()
    {
        $products =
            Product::latest()->get();

        return view(
            'laporan.stok',
            compact('products')
        );
    }

    public function produkPdf()
    {
        $products =
            Product::latest()->get();

        $pdf =
            Pdf::loadView(
                'laporan.pdf.produk',
                compact('products')
            );

        return $pdf->download(
            'laporan-produk.pdf'
        );
    }

    public function supplierPdf()
    {
        $suppliers =
            Supplier::latest()
            ->get();

        $pdf =
            Pdf::loadView(
                'laporan.pdf.supplier',
                compact('suppliers')
            );

        return $pdf->download(
            'laporan-supplier.pdf'
        );
    }

    public function customerPdf()
    {
        $customers =
            Customer::latest()
            ->get();

        $pdf =
            Pdf::loadView(
                'laporan.pdf.customer',
                compact('customers')
            );

        return $pdf->download(
            'laporan-customer.pdf'
        );
    }

    public function pembelianPdf()
    {
        $fakturs =
            Faktur::latest()
            ->get();

        $pdf =
            Pdf::loadView(
                'laporan.pdf.pembelian',
                compact('fakturs')
            );

        return $pdf->download(
            'laporan-pembelian.pdf'
        );
    }

    public function penjualanPdf()
    {
        $penjualans =
            Penjualan::latest()
            ->get();

        $pdf =
            Pdf::loadView(
                'laporan.pdf.penjualan',
                compact('penjualans')
            );

        return $pdf->download(
            'laporan-penjualan.pdf'
        );
    }

    public function stokPdf()
    {
        $products =
            Product::latest()
            ->get();

        $pdf =
            Pdf::loadView(
                'laporan.pdf.stok',
                compact('products')
            );

        return $pdf->download(
            'laporan-stok.pdf'
        );
    }
}
