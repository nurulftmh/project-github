<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\History;
use App\Models\Obat;
use App\Models\PredictionHistory;

class MovingAverageController extends Controller
{
    public function index(Request $request)
    {
        $obats = Obat::select('id', 'nama_obat')->get();

        // Ambil ID obat yang dipilih, default ke obat pertama
        $selectedObatId = $request->get('obat_id', $obats->first()->id ?? null);
        if (!$selectedObatId) {
            return view('moving_average', ['error' => 'Tidak ada data obat.', 'obats' => $obats]);
        }

        // Ambil data pemakaian dari History berdasarkan obat yang dipilih
        $dataPemakaian = History::where('obat_id', $selectedObatId)
            ->orderBy('bulan', 'asc')
            ->pluck('jumlah')
            ->toArray();

        if (count($dataPemakaian) < 5) {
            return view('moving_average', [
                'error' => 'Data pemakaian tidak cukup untuk prediksi.',
                'obats' => $obats,
                'selectedObatId' => $selectedObatId
            ]);
        }

        // Membagi data menjadi 80% training dan 20% testing
        $totalData = count($dataPemakaian);
        $trainingSize = floor($totalData * 0.8);
        $trainingData = array_slice($dataPemakaian, 0, $trainingSize);
        $testingData = array_slice($dataPemakaian, $trainingSize);

        $window = 3;

        // Prediksi data testing menggunakan Moving Average
        $predictedValue = $this->movingAverage($trainingData, $window, count($testingData));

        // Hitung metrik evaluasi
        $mae = $this->calculateMAE($predictedValue, $testingData);
        $mse = $this->calculateMSE($predictedValue, $testingData);
        $mape = $this->calculateMAPE($predictedValue, $testingData);

        return view('moving_average', compact(
            'trainingData',
            'testingData',
            'predictedValue',
            'mae',
            'mse',
            'mape',
            'obats',
            'selectedObatId'
        ));
    }

    public function predictMonths(Request $request)
    {
        // Validate the input to ensure 'months' is an integer and at least 1
        $request->validate([
            'months' => 'required|integer|min:1', // Validasi input jumlah bulan
        ]);
    
        // Retrieve the number of months for prediction
        $months = $request->input('months');
        $obats = Obat::select('id', 'nama_obat')->get();
    
        // Get the selected 'obat_id' or default to the first available obat
        $selectedObatId = $request->input('obat_id', $obats->first()->id ?? null);
    
        if (!$selectedObatId) {
            return redirect()->route('moving_average.index')->with('error', 'Tidak ada data obat.');
        }
    
        // Retrieve the historical usage data for the selected obat
        $dataPemakaian = History::where('obat_id', $selectedObatId)
            ->orderBy('bulan', 'asc')
            ->pluck('jumlah')
            ->toArray();
    
        if (count($dataPemakaian) < 3) {
            return redirect()->route('moving_average.index')->with('error', 'Data tidak cukup untuk prediksi.');
        }
    
        // Get the last recorded month and year for the selected obat
        $lastMonth = History::where('obat_id', $selectedObatId)->max('bulan');
        $lastYear = History::where('obat_id', $selectedObatId)->where('bulan', $lastMonth)->first()->tahun ?? now()->year;
    
        // If $lastMonth or $lastYear is invalid, default to the current month and year
        $lastMonth = is_numeric($lastMonth) ? $lastMonth : now()->month;
        $lastYear = is_numeric($lastYear) ? $lastYear : now()->year;
    
        // Define the window size for moving average calculation
        $window = 3; // Moving average window size
        $predictedValue = $this->movingAverage($dataPemakaian, $window, $months);
    
        // Store prediction data temporarily in the session for later use
        session([
            'predictedValues' => $predictedValue,
            'selectedObatId' => $selectedObatId,
            'lastMonth' => $lastMonth,
            'lastYear' => $lastYear
        ]);
    
        // Return the prediction results page with the calculated values
        return view('prediction_result', compact(
            'predictedValue', 
            'months', 
            'obats', 
            'selectedObatId', 
            'lastMonth', 
            'lastYear'
        ));
    }
    


    private function movingAverage($data, $window, $months)
{
    $prediksi = [];
    $jumlahData = count($data);

    if ($jumlahData < $window) {
        return $prediksi;
    }

    // Hitung prediksi Moving Average
    for ($i = $jumlahData; $i < $jumlahData + $months; $i++) {
        $sum = 0;
        for ($j = $i - $window; $j < $i; $j++) {
            $sum += $data[$j % $jumlahData];
        }
        // Gunakan round() untuk mengubah hasil menjadi bilangan bulat
        $prediksi[] = round($sum / $window);
    }

    return $prediksi;
}


    private function calculateMAE($predicted, $actual)
    {
        $totalError = 0;
        $totalPredictions = count($predicted);

        if ($totalPredictions == 0) {
            return 0;
        }

        for ($i = 0; $i < $totalPredictions; $i++) {
            $totalError += abs($predicted[$i] - $actual[$i]);
        }

        return $totalError / $totalPredictions;
    }

    private function calculateMSE($predicted, $actual)
    {
        $totalError = 0;
        $totalPredictions = count($predicted);

        if ($totalPredictions == 0) {
            return 0;
        }

        for ($i = 0; $i < $totalPredictions; $i++) {
            $totalError += pow(($predicted[$i] - $actual[$i]), 2);
        }

        return $totalError / $totalPredictions;
    }

    private function calculateMAPE($predicted, $actual)
    {
        $totalError = 0;
        $totalPredictions = count($predicted);

        if ($totalPredictions == 0) {
            return 0;
        }

        for ($i = 0; $i < $totalPredictions; $i++) {
            if ($actual[$i] != 0) {
                $totalError += abs(($predicted[$i] - $actual[$i]) / $actual[$i]);
            }
        }

        return ($totalError / $totalPredictions) * 100;
    }

    public function riwayatPrediksi()
    {
        // Ambil data riwayat prediksi dengan relasi ke tabel Obat
        $predictions = PredictionHistory::with('obat')
            ->orderBy('tahun_prediksi', 'desc')
            ->orderBy('bulan_prediksi', 'desc')
            ->get();

        // Menampilkan view riwayat prediksi
        return view('riwayat-prediksi', compact('predictions'));
    }

    public function savePrediction(Request $request)
{
    $predictedValues = session('predictedValues', []);
    $selectedObatId = session('selectedObatId');
    $lastMonth = session('lastMonth');
    $lastYear = session('lastYear');

    

    // Simpan setiap nilai prediksi ke database
    foreach ($predictedValues as $index => $value) {
        $bulan = ($lastMonth + $index) % 12 ?: 12;
        $tahun = $lastYear + intval(($lastMonth + $index - 1) / 12);

        // Simpan riwayat prediksi
        PredictionHistory::create([
            'obat_id' => $selectedObatId,
            'bulan_prediksi' => $bulan,
            'tahun_prediksi' => $tahun,
            'nilai_prediksi' => $value,
        ]);
    }

    return redirect()->route('riwayat-prediksi')->with('success', 'Prediksi berhasil disimpan.');
}




}
