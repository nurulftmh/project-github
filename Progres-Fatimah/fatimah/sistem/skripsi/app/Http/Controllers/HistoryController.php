<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Obat;
use App\Models\History;

class HistoryController extends Controller
{
    // Menampilkan riwayat pemakaian obat
    public function index(Obat $obat)
    {
        $histories = $obat->histories; // Pastikan relasi sudah didefinisikan di model Obat
        return view('history', compact('obat', 'histories'));
    }

    // Menyimpan data history baru
    public function store(Request $request, Obat $obat)
    {
        $request->validate([
            'bulan' => 'required|date',
            'jumlah_pemakaian' => 'required|integer',
        ]);

        $obat->histories()->create([
            'bulan' => $request->bulan,
            'jumlah_pemakaian' => $request->jumlah_pemakaian,
        ]);

        return redirect()->route('obat.history', $obat)->with('success', 'History added successfully');
    }

    // Mengupdate data history
    public function update(Request $request, Obat $obat, History $history)
    {
        $request->validate([
            'bulan' => 'required|date',
            'jumlah_pemakaian' => 'required|integer',
        ]);

        $history->update([
            'bulan' => $request->bulan,
            'jumlah_pemakaian' => $request->jumlah_pemakaian,
        ]);

        return redirect()->route('obat.history', $obat)->with('success', 'History updated successfully');
    }

    // Menghapus data history
    public function destroy(Obat $obat, History $history)
    {
        $history->delete();

        return redirect()->route('obat.history', $obat)->with('success', 'History deleted successfully');
    }
}
