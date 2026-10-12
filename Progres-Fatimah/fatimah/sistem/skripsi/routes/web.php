<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ObatController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\MovingAverageController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\PredictionController;
use App\Exports\PredictionsExport;
use Maatwebsite\Excel\Facades\Excel;


// Rute untuk halaman utama
Route::get('/', function () {
    return view('welcome');
});

// Rute yang membutuhkan autentikasi
Route::middleware('auth')->group(function () {
    Route::get('/dashboard', function () {
        return view('dashboard');
    })->name('dashboard');

    // Rute untuk profile
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
    // routes/web.php


    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    // Rute untuk CRUD Obat
    Route::resource('obat', ObatController::class);
    Route::get('/{id}/history', [ObatController::class, 'history'])->name('obat.history');
    Route::post('/{id}/history', [ObatController::class, 'storeHistory'])->name('obat.storeHistory');
    Route::put('/{id}/history/{historyId}', [ObatController::class, 'updateHistory'])->name('obat.updateHistory');
    Route::delete('/{id}/history/{historyId}', [ObatController::class, 'destroyHistory'])->name('obat.destroyHistory');

    // Route untuk prediksi
    Route::get('/prediksi', [ObatController::class, 'prediksiIndex'])->name('prediksi.index');
    Route::post('/prediksi', [ObatController::class, 'predict'])->name('prediksi.store');

    Route::get('/riwayat-prediksi', [MovingAverageController::class, 'riwayatPrediksi'])->name('riwayat-prediksi');
    
    Route::delete('/riwayat-prediksi/{id}', [MovingAverageController::class, 'destroy'])->name('riwayat-prediksi.destroy');

});

Route::get('/prediksi', [PredictionController::class, 'index'])->name('moving-average.index');
Route::post('/prediksi/predict', [PredictionController::class, 'predict'])->name('moving-average.predict');

Route::post('/save-prediction', [MovingAverageController::class, 'savePrediction'])->name('save_prediction');

Route::get('/export-predictions', function (Request $request) {
    $predictedValue = session('predictedValue'); // Ambil data dari session atau controller
    $obatName = session('obatName');
    $months = session('months');
    $lastMonth = session('lastMonth');
    $lastYear = session('lastYear');

    return Excel::download(
        new PredictionsExport($predictedValue, $obatName, $months, $lastMonth, $lastYear),
        'prediksi.xlsx'
    );
})->name('export.predictions');

Route::get('/prediksi/export', [ObatController::class, 'exportPredictionHistory'])->name('prediksi.export');
Route::post('/obat/import', [ObatController::class, 'import'])->name('obat.import');
Route::post('obat/{id}/import-history', [ObatController::class, 'importHistory'])->name('obat.importHistory');




// web.php

Route::get('/moving-average', [MovingAverageController::class, 'index'])->name('moving_average.index');
Route::post('/moving-average/predict', [MovingAverageController::class, 'predict'])->name('moving_average.predict');
Route::post('/predict', [MovingAverageController::class, 'predictMonths'])->name('predict_months');
// Rute untuk halaman login dan logout, dan halaman lainnya yang tidak memerlukan autentikasi
Route::view('/login', 'auth.login')->name('login');
Route::post('/logout', [\App\Http\Controllers\Auth\AuthenticatedSessionController::class, 'destroy'])->name('logout');

require __DIR__.'/auth.php';

