<?php

namespace App\Imports;

use App\Models\History;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use PhpOffice\PhpSpreadsheet\Shared\Date;
use Carbon\Carbon;

class HistoryImport implements ToModel, WithHeadingRow
{
    protected $obatId;

    public function __construct($obatId)
    {
        $this->obatId = $obatId;
    }

    public function model(array $row)
    {
        // Konversi nilai Excel date ke format Y-m-d
        $formattedDate = null;

        if (is_numeric($row['bulan'])) {
            $formattedDate = Carbon::instance(Date::excelToDateTimeObject($row['bulan']))->format('Y-m-d');
        } else {
            $formattedDate = Carbon::parse($row['bulan'])->format('Y-m-d');
        }

        return new History([
            'obat_id' => $this->obatId,
            'bulan' => $formattedDate,
            'jumlah' => $row['jumlah'],
        ]);
    }
}
