<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Faktur;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;

class FakturController extends Controller
{
    public function update(Request $request, Faktur $faktur)
    {
        Gate::authorize('edit-faktur');

        $validated = $request->validate([
            'tanggal' => 'required|date',
            'total' => 'required|numeric'
        ]);

        $faktur->update($validated);

        return response()->json([
            'message' => 'Faktur berhasil diupdate',
            'data' => $faktur
        ]);
    }

    public function destroy(Faktur $faktur)
    {
        Gate::authorize('delete-faktur');

        $faktur->delete();

        return response()->json([
            'message' => 'Faktur berhasil dihapus'
        ]);
    }
}
