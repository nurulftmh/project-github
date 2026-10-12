<?php

namespace App\Http\Controllers;

use App\Models\Obat;
use Illuminate\Http\Request;
use Carbon\Carbon;
use App\Models\History;
use App\Models\PredictionHistory;

class PredictionController extends Controller
{
    public function prediksiIndex()
    {
        // Logika untuk menampilkan halaman prediksi
        return view('prediksi');
    }

    public function index()
    {
        $obats = Obat::all(); // Ambil data obat dari model Obat
        $totalObat = $obats->count(); // Menghitung total obat yang ada

        // Kirimkan $totalObat dan $obats ke view
        return view('prediksi', compact('obats', 'totalObat'));
    }

    public function predict(Request $request)
    {
        // Validasi input
        $request->validate([
            'obat_id' => 'required|exists:obats,id',
            'months' => 'required|integer|min:1',
        ]);

        $obatId = $request->input('obat_id');
        $months = $request->input('months');

        // Ambil data obat dan lakukan prediksi (contoh menggunakan moving average sederhana)
        $obat = Obat::findOrFail($obatId);
        $data = $this->getDataForPrediction($obatId); // Ambil data penjualan berdasarkan obat_id
        $predictedValue = $this->movingAverage($data, $months);

        // Evaluasi model prediksi
        $accuracy = $this->calculateAccuracy($data, $predictedValue);
        $mae = $this->meanAbsoluteError($data, $predictedValue);
        $mse = $this->meanSquaredError($data, $predictedValue);
        $mape = $this->meanAbsolutePercentageError($data, $predictedValue);

        // Siapkan data untuk ditampilkan
        $trainingData = $data['training'];
        $testingData = $data['testing'];

        // Kirimkan data yang diperlukan ke view
        return view('dashboard', compact(
            'obat', 
            'obats', 
            'predictedValue', 
            'accuracy', 
            'mae', 
            'mse', 
            'mape', 
            'trainingData', 
            'testingData'
        ));
    }

    private function getDataForPrediction($obatId)
    {
        // Ambil data penjualan berdasarkan obat_id dan pisahkan menjadi data pelatihan dan pengujian
        $data = Obat::find($obatId)->salesData(); 

        $trainingData = array_slice($data, 0, (int)(count($data) * 0.8)); // 80% untuk pelatihan
        $testingData = array_slice($data, (int)(count($data) * 0.8)); // 20% untuk pengujian

        return ['training' => $trainingData, 'testing' => $testingData];
    }

    private function movingAverage($data, $months)
    {
        // Contoh perhitungan moving average (gunakan logika sesuai metode Anda)
        $predictedValue = [];
        $length = count($data);
        for ($i = 0; $i < $months; $i++) {
            $sum = 0;
            for ($j = $length - $i - 1; $j >= $length - $i - 12; $j--) {
                $sum += $data[$j];
            }
            $predictedValue[] = $sum / 12;
        }

        return $predictedValue;
    }

    private function calculateAccuracy($data, $predictedValue)
    {
        // Implementasi akurasi model prediksi (misalnya, koefisien korelasi)
        return 95; // Contoh: 95%
    }

    private function meanAbsoluteError($data, $predictedValue)
    {
        // Implementasi MAE
        return 10.5; // Contoh nilai MAE
    }

    private function meanSquaredError($data, $predictedValue)
    {
        // Implementasi MSE
        return 50.0; // Contoh nilai MSE
    }

    private function meanAbsolutePercentageError($data, $predictedValue)
    {
        // Implementasi MAPE
        return 8.0; // Contoh nilai MAPE
    }
}
