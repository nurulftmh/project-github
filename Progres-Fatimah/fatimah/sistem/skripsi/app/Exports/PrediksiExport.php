<?php

namespace App\Exports;

use App\Models\Prediction;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;

class PrediksiExport implements FromCollection, WithHeadings
{
    protected $predictions;

    // Konstruktor untuk menerima data yang akan diekspor
    public function __construct($predictions)
    {
        $this->predictions = $predictions;
    }

    // Mengambil data yang akan diekspor
    public function collection()
    {
        return $this->predictions->map(function ($prediction, $index) {
            return [
                'No' => $index + 1,
                'Nama Obat' => $prediction->obat->nama_obat ?? 'Tidak Diketahui',
                'Bulan dan Tahun Prediksi' => $prediction->bulan_prediksi . ' ' . $prediction->tahun_prediksi,
                'Nilai Prediksi' => $prediction->nilai_prediksi,
            ];
        });
    }
    

    
    // Menentukan judul kolom untuk file Excel
    public function headings(): array
    {
        return [
            'No',
            'Nama Obat',
            'Bulan dan Tahun Prediksi',
            'Nilai Prediksi',
        ];
    }
}
