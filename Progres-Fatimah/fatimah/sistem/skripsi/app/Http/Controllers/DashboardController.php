<?php

namespace App\Http\Controllers;

use App\Models\Obat;
use App\Models\PredictionHistory;
use App\Models\History;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function index()
    {
        // Query to get the count of obat and prediksi
        $totalObat = Obat::count();
        $totalPrediksi = PredictionHistory::count();
        $totalPemakaian = History::sum('jumlah');
        $totalStok = Obat::sum('stok');

        // Pass both data to the view
        return view('dashboard', compact('totalObat', 'totalPrediksi','totalPemakaian','totalStok'));
    }
}
