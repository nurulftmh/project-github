@extends('main')

@section('title', 'Riwayat Prediksi')

@section('content')
<div class="content-wrapper">
    <!-- Tombol Export -->
    <div class="d-flex justify-content-between mb-3">
        

    </div>
    
    <h1 class="mb-4 text-center">Riwayat Prediksi Obat</h1>

    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            <strong><i class="fas fa-check-circle"></i> Sukses!</strong> {{ session('success') }}
            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                <span aria-hidden="true">&times;</span>
            </button>
        </div>
    @endif

    <div class="card mb-4">
        <div class="card-header">
            <h2 class="h5 m-0"></h2>
            <a href="{{ route('moving_average.index') }}" class="btn btn-primary">Kembali ke Halaman Prediksi</a>
            <a href="{{ route('prediksi.export') }}" class="btn btn-success btn-sm">Export ke Excel</a>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-bordered table-striped">
                    <thead>
                        <tr>
                            <th>No</th>
                            <th>Nama Obat</th>
                            <th>Bulan dan Tahun Prediksi</th>
                            <th>Nilai Prediksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($predictions as $index => $history)
                            <tr>
                                <td>{{ $index + 1 }}</td>
                                <td>{{ $history->obat->nama_obat }}</td>
                                <td>{{ \Carbon\Carbon::createFromDate($history->tahun_prediksi, $history->bulan_prediksi, 1)->format('F Y') }}</td>
                                <td>{{ number_format($history->nilai_prediksi, 0) }}</td> <!-- Ubah menjadi 0 jika ingin bulat -->
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
@endsection
