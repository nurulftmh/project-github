<?php

namespace App\Http\Controllers;

use App\Models\Obat;
use App\Models\History;
use App\Models\Prediction;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\PrediksiExport;
use App\Models\PredictionHistory;
use App\Imports\ObatImport;
use App\Imports\HistoryImport;


class ObatController extends Controller
{
    public function import(Request $request)
    {
        // Validasi file yang diupload
        $request->validate([
            'file' => 'required|mimes:xlsx,xls,csv',
        ]);

        // Menggunakan ObatImport untuk mengimpor file
        Excel::import(new ObatImport, $request->file('file'));

        // Redirect atau berikan respons
        return redirect()->back()->with('success', 'Data berhasil diimpor!');
    }

    public function importHistory(Request $request, $id)
{
    $request->validate([
        'file' => 'required|mimes:xlsx,xls',
    ]);

    Excel::import(new HistoryImport($id), $request->file('file'));

    return redirect()->back()->with('success', 'Data berhasil diimport!');
}

    public function prediksiIndex()
{
    // Logika untuk menampilkan halaman prediksi
    return view('dashboard');
}
    // Menampilkan daftar obat
    public function index()
    {
        $obats = Obat::paginate(10); // Menampilkan 10 data per halaman
        return view('obat', compact('obats'));
    }
    

    public function store(Request $request)
{
    $request->validate([
        'kode_obat' => 'required',
        'nama_obat' => 'required',
    ]);

    // Tentukan stok secara eksplisit
    Obat::create([
        'kode_obat' => $request->kode_obat,
        'nama_obat' => $request->nama_obat,
        'stok' => 0, // Stok default jika tidak disediakan
    ]);

    return redirect()->route('obat.index')->with('success', 'Obat berhasil ditambahkan!');
}


    public function update(Request $request, Obat $obat)
    {
        $request->validate([
            'kode_obat' => 'required',
            'nama_obat' => 'required',
            
            
        ]);

        $obat->update($request->only(['kode_obat', 'nama_obat']));

        return redirect()->route('obat.index')->with('success', 'Obat berhasil diperbarui!');
    }

    public function destroy(Obat $obat)
    {
        $obat->delete();
        return redirect()->route('obat.index')->with('success', 'Obat berhasil dihapus!');
    }

    // Menampilkan riwayat pemakaian obat
    public function history($id)
    {
        $obat = Obat::findOrFail($id);
        $histories = $obat->histories; // Ambil data histories dari relasi
        $histories = History::where('obat_id', $obat->id)->paginate(10);
        return view('history', compact('obat', 'histories'));
    }

    // Menyimpan data history
    public function storeHistory(Request $request, $id)
    {
        $request->validate([
            'bulan' => 'required|date',
            'jumlah_pemakaian' => 'required|integer',
            
        ]);

        $obat = Obat::findOrFail($id);

        $obat->histories()->create([
            'bulan' => $request->bulan,
            'jumlah' => $request->jumlah_pemakaian, 
           
        ]);

        return redirect()->route('obat.history', $obat->id)->with('success', 'History berhasil ditambahkan!');
    }

    // Mengupdate data history
    public function updateHistory(Request $request, $obatId, $historyId)
{
    $request->validate([
        'bulan' => 'required|date',
        'jumlah_pemakaian' => 'required|integer',
    ]);

    $history = History::findOrFail($historyId);
    $history->update([
        'bulan' => $request->bulan,
        'jumlah' => $request->jumlah_pemakaian, // Pastikan ini ditambahkan
    ]);

    return redirect()->route('obat.history', $obatId)->with('success', 'History berhasil diperbarui!');
}

    // Menghapus data history
    public function destroyHistory($obatId, $historyId)
    {
        $history = History::findOrFail($historyId);
        $history->delete();

        return redirect()->route('obat.history', $obatId)->with('success', 'History berhasil dihapus!');
    }

    public function exportPredictionHistory()
    {
        // Ambil semua riwayat prediksi dari tabel prediction_history
        $predictions = PredictionHistory::with('obat')->get();
    
        // Jika tidak ada data, beri notifikasi
        if ($predictions->isEmpty()) {
            return redirect()->back()->with('error', 'Tidak ada data prediksi untuk diekspor.');
        }
    
        // Ekspor data ke file Excel
        return Excel::download(new PrediksiExport($predictions), 'riwayat_prediksi.xlsx');
    }
    

    
}
