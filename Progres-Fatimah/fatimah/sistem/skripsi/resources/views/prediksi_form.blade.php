<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>PharmaTrack | Data Obat</title>

  <!-- Google Font: Source Sans Pro -->
  <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,400i,700&display=fallback">
  <!-- Font Awesome -->
  <link rel="stylesheet" href="{{ asset('plugins/fontawesome-free/css/all.min.css') }}">
  <!-- Ionicons -->
  <link rel="stylesheet" href="https://code.ionicframework.com/ionicons/2.0.1/css/ionicons.min.css">
  <!-- Tempusdominus Bootstrap 4 -->
  <link rel="stylesheet" href="{{ asset('plugins/tempusdominus-bootstrap-4/css/tempusdominus-bootstrap-4.min.css') }}">
  <!-- iCheck -->
  <link rel="stylesheet" href="{{ asset('plugins/icheck-bootstrap/icheck-bootstrap.min.css') }}">
  <!-- JQVMap -->
  <link rel="stylesheet" href="{{ asset('plugins/jqvmap/jqvmap.min.css') }}">
  <!-- Theme style -->
  <link rel="stylesheet" href="{{ asset('dist/css/adminlte.min.css') }}">
  <!-- overlayScrollbars -->
  <link rel="stylesheet" href="{{ asset('plugins/overlayScrollbars/css/OverlayScrollbars.min.css') }}">
  <!-- Daterange picker -->
  <link rel="stylesheet" href="{{ asset('plugins/daterangepicker/daterangepicker.css') }}">
  <!-- summernote -->
  <link rel="stylesheet" href="{{ asset('plugins/summernote/summernote-bs4.min.css') }}">
</head>
<body>
    <!-- Navbar and Sidebar -->
    @include('navbar')
    @include('sidebar')

    @section('content')
    <div class="container">
        <h1>Prediksi Persediaan Stok Obat: {{ $obat->nama_obat }}</h1>
    
        <form action="{{ route('obat.predictNextStock', $obat->id) }}" method="POST">
            @csrf
            <div class="form-group">
                <label for="alpha">Alpha (α)</label>
                <input type="number" class="form-control" id="alpha" name="alpha" step="0.01" min="0" max="1" required>
            </div>
            <div class="form-group">
                <label for="beta">Beta (β)</label>
                <input type="number" class="form-control" id="beta" name="beta" step="0.01" min="0" max="1" required>
            </div>
            <div class="form-group">
                <label for="gamma">Gamma (γ)</label>
                <input type="number" class="form-control" id="gamma" name="gamma" step="0.01" min="0" max="1" required>
            </div>
            <div class="form-group">
                <label for="months">Jumlah Bulan Prediksi</label>
                <input type="number" class="form-control" id="months" name="months" min="1" max="12" required>
            </div>
    
            <button type="submit" class="btn btn-primary">Prediksi</button>
        </form>
    </div>
    @endsection