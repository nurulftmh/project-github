@extends('main')

@section('title', 'Prediksi Moving Average')

@section('content')
<div class="content-wrapper">
    <h1 class="mb-4 text-center">Hasil Prediksi Moving Average</h1>

    <!-- Form Pilih Obat dan Jumlah Bulan Prediksi -->
    <form method="GET" action="{{ route('moving_average.index') }}" class="mb-4">
        @csrf
        <div class="row mb-3">
            <div class="col-md-6">
                <label for="obat_id" class="form-label">Pilih Obat</label>
                <select name="obat_id" id="obat_id" class="form-select" onchange="this.form.submit()">
                    <option value="" disabled selected>Pilih Obat</option>
                    @foreach($obats as $obat)
                        <option value="{{ $obat->id }}" {{ $selectedObatId == $obat->id ? 'selected' : '' }}>
                            {{ $obat->nama_obat }}
                        </option>
                    @endforeach
                </select>
            </div>
        </div>
    </form>

    <form method="POST" action="{{ route('predict_months') }}" class="mb-4">
        @csrf
        <input type="hidden" name="obat_id" value="{{ $selectedObatId }}">
        <div class="row align-items-end">
            <div class="col-md-6">
                <label for="months" class="form-label">Jumlah Bulan Prediksi Selanjutnya:</label>
                <input type="number" name="months" id="months" class="form-control" min="1" required>
            </div>
            <div class="col-md-2">
                <button type="submit" class="btn btn-success">Prediksi</button>
            </div>
        </div>
    </form>

    @if(isset($error))
        <div class="alert alert-danger">{{ $error }}</div>
    @else
        <!-- Evaluasi Metrik -->
        <div class="card mb-4">
            <div class="card-header bg-info text-white">
                <h5 class="m-0">Evaluasi Model</h5>
            </div>
            <div class="card-body">
                <table class="table table-striped table-bordered">
                    <thead class="table-dark">
                        <tr>
                            <th>Metode</th>
                            <th>Nilai</th>
                        </tr>
                    </thead>
                    <tbody>
                        <!-- <tr>
                            <td>MAE</td>
                            <td>{{ round($mae, 2) }}</td>
                        </tr>
                        <tr>
                            <td>MSE</td>
                            <td>{{ round($mse, 2) }}</td>
                        </tr> -->
                        <tr>
                            <td>MAPE</td>
                            <td>{{ round($mape, 2) }}%</td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>

        <!-- Grid Layout for Data Training and Data Testing -->
        <div class="row">
            <!-- Data Training -->
            <div class="col-md-6">
                <div class="card">
                    <div class="card-header bg-primary text-white">
                        <h5 class="m-0">Data Training</h5>
                    </div>
                    <div class="card-body">
                        <table class="table table-striped table-bordered">
                            <thead class="table-dark">
                                <tr>
                                    <th>No</th>
                                    <th>Nilai</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($trainingData as $index => $value)
                                    <tr>
                                        <td>{{ $index + 1 }}</td>
                                        <td>{{ $value }}</td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

            <!-- Data Testing -->
            <div class="col-md-6">
                <div class="card">
                    <div class="card-header bg-success text-white">
                        <h5 class="m-0">Data Testing dan Prediksi</h5>
                    </div>
                    <div class="card-body">
                        <table class="table table-striped table-bordered">
                            <thead class="table-dark">
                                <tr>
                                    <th>No</th>
                                    <th>Data Aktual</th>
                                    <th>Prediksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($testingData as $index => $value)
                                    <tr>
                                        <td>{{ $index + 1 }}</td>
                                        <td>{{ $value }}</td>
                                        <td>{{ $predictedValue[$index] ?? '-' }}</td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    @endif
</div>
@endsection
