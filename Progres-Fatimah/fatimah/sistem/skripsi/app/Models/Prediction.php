<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Prediction extends Model
{
    use HasFactory;

    protected $fillable = [
        'obat_id',
        'predicted_values',
        'alpha',
        'beta',
        'gamma',
        'months',
        'mae',
        'mse',
        'mape',
    ];

    protected $casts = [
        'predicted_values' => 'array', // Konversi JSON ke array saat diambil dari database
    ];

    // Relasi ke tabel Obat
    public function obat()
    {
        return $this->belongsTo(Obat::class);
    }
}
