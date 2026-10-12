<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PredictionHistory extends Model
{
    use HasFactory;

    // Tentukan nama tabel jika tidak mengikuti konvensi Laravel
    protected $table = 'prediction_history';

    // Tentukan atribut yang boleh diisi secara mass-assignment
    protected $fillable = [
        'obat_id',
        'bulan_prediksi',
        'tahun_prediksi',
        'nilai_prediksi',
    ];

    // Tentukan relasi jika diperlukan, misalnya relasi dengan model Obat
    public function obat()
    {
        return $this->belongsTo(Obat::class);
    }
}
