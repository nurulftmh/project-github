<?php

namespace App\Services;

class HoltWinters
{
    private $alpha;  // Nilai Alpha
    private $beta;   // Nilai Beta
    private $gamma;  // Nilai Gamma
    private $seasonLength;  // Panjang Musim (misalnya 12 untuk data bulanan)
    private $level;
    private $trend;
    private $seasonals;

    public function __construct($alpha, $beta, $gamma, $seasonLength)
    {
        $this->alpha = $alpha;
        $this->beta = $beta;
        $this->gamma = $gamma;
        $this->seasonLength = $seasonLength;
        $this->seasonals = [];
    }

    // Fungsi untuk melatih model dengan data aktual
    public function train(array $data)
    {
        $n = count($data);
        $this->initialTrend($data);
        $this->initialSeasonalComponents($data);

        for ($i = $this->seasonLength; $i < $n; $i++) {
            $lastLevel = $this->level;
            $lastTrend = $this->trend;

            // Update level
            $this->level = $this->alpha * ($data[$i] / $this->seasonals[$i % $this->seasonLength]) + (1 - $this->alpha) * ($lastLevel + $lastTrend);

            // Update trend
            $this->trend = $this->beta * ($this->level - $lastLevel) + (1 - $this->beta) * $lastTrend;

            // Update seasonal
            $this->seasonals[$i % $this->seasonLength] = $this->gamma * ($data[$i] / $this->level) + (1 - $this->gamma) * $this->seasonals[$i % $this->seasonLength];
        }
    }

    // Fungsi untuk menghitung tren awal
    private function initialTrend(array $data)
    {
        $sum = 0;
        for ($i = 0; $i < $this->seasonLength; $i++) {
            $sum += ($data[$i + $this->seasonLength] - $data[$i]) / $this->seasonLength;
        }
        $this->trend = $sum / $this->seasonLength;
    }

    // Fungsi untuk menghitung komponen musiman awal
    private function initialSeasonalComponents(array $data)
    {
        $seasonAverages = [];
        $nSeasons = count($data) / $this->seasonLength;
        for ($j = 0; $j < $nSeasons; $j++) {
            $seasonSum = 0;
            for ($i = 0; $i < $this->seasonLength; $i++) {
                $seasonSum += $data[$j * $this->seasonLength + $i];
            }
            $seasonAverages[] = $seasonSum / $this->seasonLength;
        }

        for ($i = 0; $i < $this->seasonLength; $i++) {
            $sumOverAvg = 0;
            for ($j = 0; $j < $nSeasons; $j++) {
                $sumOverAvg += $data[$j * $this->seasonLength + $i] / $seasonAverages[$j];
            }
            $this->seasonals[$i] = $sumOverAvg / $nSeasons;
        }
    }

    // Fungsi untuk melakukan prediksi
    public function forecast($numForecasts)
    {
        $forecast = [];
        for ($i = 0; $i < $numForecasts; $i++) {
            $m = $i + 1;
            $forecast[] = ($this->level + $m * $this->trend) * $this->seasonals[$m % $this->seasonLength];
        }
        return $forecast;
    }
}
