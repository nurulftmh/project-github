<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Obat extends Model
{
    use HasFactory;

    protected $fillable = ['kode_obat', 'nama_obat', 'stok'];

    // Relasi dengan model History
    public function histories()
    {
        return $this->hasMany(History::class);
    }

    // Fungsi untuk menghitung stok berdasarkan total pemakaian
    public function totalPemakaian()
{
    return $this->hasMany(History::class)->sum('jumlah');
}

public function stok()
{
    return $this->totalPemakaian();  // Assuming you store initial stock
}
public function predictionHistories()
    {
        return $this->hasMany(PredictionHistory::class);
    }

}

