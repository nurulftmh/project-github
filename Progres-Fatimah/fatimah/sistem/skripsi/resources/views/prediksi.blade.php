@extends('main')

@section('title', 'Prediksi Moving Average')

@section('breadcrumbs')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
@endsection

@section('content')
<div class="container">
    <h1>Prediksi Moving Average</h1>

    @if(session('success'))
        <div class="alert alert-success">
            {{ session('success') }}
        </div>
    @endif

    <!-- Form untuk memilih obat dan jumlah bulan untuk prediksi -->
    <form action="{{ route('moving-average.predict') }}" method="POST">
        @csrf
        <div class="form-group">
            <label for="obat_id">Pilih Obat:</label>
            <select name="obat_id" id="obat_id" class="form-control" required>
                @foreach($obats as $obat)
                    <option value="{{ $obat->id }}" {{ old('obat_id') == $obat->id ? 'selected' : '' }}>
                        {{ $obat->name }}
                    </option>
                @endforeach
            </select>
        </div>

        <div class="form-group">
            <label for="months">Jumlah Bulan untuk Prediksi:</label>
            <input type="number" name="months" id="months" class="form-control" required min="1" value="{{ old('months', 3) }}">
        </div>

        <button type="submit" class="btn btn-primary">Prediksi</button>
    </form>

    <!-- Menampilkan hasil prediksi jika ada -->
    @if(!empty($predictedValue))
        <h3>Hasil Prediksi untuk Obat: {{ $obat->name }}</h3>
        <table class="table table-striped mt-3">
            <thead>
                <tr>
                    <th>Bulan</th>
                    <th>Tahun</th>
                    <th>Prediksi Jumlah</th>
                </tr>
            </thead>
            <tbody>
                @foreach($predictedValue as $index => $value)
                    @php
                        $predictedMonth = ($index + 1) % 12;
                        $predictedYear = 2024 + floor(($index) / 12);
                    @endphp
                    <tr>
                        <td>{{ \Carbon\Carbon::createFromDate($predictedYear, $predictedMonth, 1)->format('F') }}</td>
                        <td>{{ $predictedYear }}</td>
                        <td>{{ number_format($value, 2) }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>

        <h4>Metode Evaluasi:</h4>
        <ul>
            <li><strong>Akurasi:</strong> {{ $accuracy }}%</li>
            <li><strong>MAE (Mean Absolute Error):</strong> {{ $mae }}</li>
            <li><strong>MSE (Mean Squared Error):</strong> {{ $mse }}</li>
            <li><strong>MAPE (Mean Absolute Percentage Error):</strong> {{ $mape }}%</li>
        </ul>
    @endif
</div>
@endsection
