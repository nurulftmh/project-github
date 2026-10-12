<?php

namespace App\Exports;

use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;

class PredictionsExport implements FromView
{
    protected $predictedValue;
    protected $obatName;
    protected $months;
    protected $lastMonth;
    protected $lastYear;

    public function __construct($predictedValue, $obatName, $months, $lastMonth, $lastYear)
    {
        $this->predictedValue = $predictedValue;
        $this->obatName = $obatName;
        $this->months = $months;
        $this->lastMonth = $lastMonth;
        $this->lastYear = $lastYear;
    }

    public function view(): View
    {
        return view('prediction_result', [
            'predictedValue' => $this->predictedValue,
            'obatName' => $this->obatName,
            'months' => $this->months,
            'lastMonth' => $this->lastMonth,
            'lastYear' => $this->lastYear,
        ]);
    }
}
