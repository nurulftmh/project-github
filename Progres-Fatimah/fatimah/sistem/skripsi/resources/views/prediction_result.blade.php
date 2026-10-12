@extends('main')

@section('title', 'Hasil Prediksi')

@section('content')
<div class="content-wrapper">
    <h1 class="mb-4 text-center">Hasil Prediksi Bulan Selanjutnya</h1>

    <div class="card mb-4">
        <div class="card-header">
            <h2 class="h5 m-0">Prediksi untuk {{ $months }} Bulan Mendatang</h2>
        </div>
        <div class="card-body">
            <!-- Tampilkan Nama Obat -->
            <h4 class="text-center">Nama Obat: {{ $obats->firstWhere('id', $selectedObatId)->nama_obat }}</h4>

            <table class="table table-bordered mt-3">
                <thead>
                    <tr>
                        <th>No</th>
                        <th>Bulan dan Tahun</th>
                        <th>Prediksi</th>
                    </tr>
                </thead>
                <tbody>
                    @php
                        // Menggunakan bulan dan tahun yang sudah dihitung dari controller
                        $lastMonth = $lastMonth ?? now()->month;
                        $lastYear = $lastYear ?? now()->year;
                    @endphp

                    @foreach ($predictedValue as $index => $value)
                        @php
                            // Hitung bulan dan tahun prediksi
                            $currentMonth = ($lastMonth + $index) % 12 ?: 12;
                            $currentYear = $lastYear + intval(($lastMonth + $index - 1) / 12);
                        @endphp
                        <tr>
                            <td>{{ $index + 1 }}</td>
                            <td>{{ DateTime::createFromFormat('!m', $currentMonth)->format('F') }} {{ $currentYear }}</td>
                            <td>{{ number_format($value, 2) }}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>

            <form action="{{ route('save_prediction') }}" method="POST">
                @csrf
                <button type="submit" class="btn btn-success">Simpan Riwayat Prediksi</button>
            </form>
        </div>
    </div>

    <a href="{{ route('moving_average.index') }}" class="btn btn-primary">Kembali ke Halaman Prediksi</a>
</div>
@endsection
