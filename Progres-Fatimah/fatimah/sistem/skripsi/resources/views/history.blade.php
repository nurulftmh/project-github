<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>PharmaPredict | History Pemakaian Obat</title>

  <!-- Include CSS -->
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
  <style>
    .pagination {
      justify-content: center; /* Align pagination to the center */
    }

    .pagination li {
      display: inline-block;
      margin: 0 5px;
    }

    .pagination a, .pagination span {
      padding: 10px 15px;
      font-size: 14px;
      background-color: #f1f1f1;
      border: 1px solid #ddd;
      color: #333;
    }

    .pagination a:hover, .pagination span:hover {
      background-color: #007bff;
      color: white;
      border-color: #007bff;
    }

    .pagination .active span {
      background-color: #007bff;
      border-color: #007bff;
      color: white;
    }

    .pagination .disabled span {
      color: #ccc;
    }

    /* Adjusting arrow sizes */
    .pagination svg {
      width: 16px;
      height: 16px;
    }
  </style>
</head>
<body>
<div class="wrapper">
    <!-- Navbar and Sidebar -->
    @include('navbar')
    {{-- @include('sidebar') --}}

   
<aside class="main-sidebar sidebar-dark-primary elevation-4" style="background-color: #00296B">
    <!-- Brand Logo -->
    <a href="index3.html" class="brand-link">
        <img src="{{ asset('dist/img/kendari.png') }}" alt="Admin Logo" class="brand-image img-circle elevation-3">
      <span class="brand-text font-weight-light" style="color:white">PharmaPredict</span>
    </a>
  
    <!-- Sidebar -->
    <div class="sidebar" style="background-color: #00296B">
      <!-- Sidebar user panel (optional) -->
      <div class="user-panel mt-3 pb-3 mb-3 d-flex">
        <div class="image">
          <img src="{{ asset ('dist/img/user8-128x128.jpg')}}" class="img-circle elevation-2" alt="User Image">
        </div>
        <div class="info">
          <a href="#" class="d-block" style="color: white">Alexander Pierce</a>
        </div>
      </div>
  
      <!-- Sidebar Menu -->
      <nav class="mt-2">
        <ul class="nav nav-pills nav-sidebar flex-column" data-widget="treeview" role="menu" data-accordion="false">
          <!-- Add icons to the links using the .nav-icon class
               with font-awesome or any other icon font library -->
          <li class="nav-item">
            <a class="nav-link {{ request()->is('admin') ? 'active' : '' }}" href="{{ url('dashboard') }}">
              <i class="nav-icon fas fa-tachometer-alt"></i>
              <p style="color: white">
                Dashboard
              </p>
            </a>
          </li>
          <li class="nav-item">
            <a class="nav-link {{ request()->is('obat') ? 'active' : '' }}" href="{{ url('obat') }}">
              <i class="nav-icon fas fa-copy"></i>
              <p style="color: white">
                Data Obat
              </p>
            </a>
          </li>
          <li class="nav-item">
            <a class="nav-link {{ request()->is('moving-average') ? 'active' : '' }}" href="{{ url('moving-average') }}">
              <i class="nav-icon fas fa-chart-pie"></i>
              <p style="color: white">
                Prediksi
              </p>
            </a>
          </li>
            
        </ul>
      </nav>

             <!-- Logout button at the bottom -->
      <div class="sidebar-footer mt-auto pb-4">
        <a href="{{ route('logout') }}" class="nav-link" onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
            <i class="nav-icon fas fa-sign-out-alt"></i>
            <p style="color: white">Logout</p>
        </a>
        <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
            @csrf
        </form>
    </div>
      
      
      <!-- /.sidebar-menu -->
    </div>
    <!-- /.sidebar -->
  </aside>
  
    <!-- Content Wrapper -->
    <div class="content-wrapper">
        <div class="content">
            <div class="container-fluid">
                <h1 class="my-4">History Pemakaian Obat: {{ $obat->nama_obat }}</h1>

        <!-- Button Kembali -->
        <a href="{{ route('obat.index') }}" class="btn btn-secondary mb-3">Kembali</a>

        <!-- Button Tambah Data -->
        <button type="button" class="btn btn-primary mb-3" data-toggle="modal" data-target="#createModal">
          Tambah History
        </button>

        <button type="button" class="btn btn-success mb-3" data-toggle="modal" data-target="#importModal">
    Import History
</button>

<!-- Import Modal -->
<div class="modal fade" id="importModal" tabindex="-1" role="dialog" aria-labelledby="importModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="importModalLabel">Import History Pemakaian</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form action="{{ route('obat.importHistory', $obat->id) }}" method="POST" enctype="multipart/form-data">
                @csrf
                <div class="modal-body">
                    <div class="form-group">
                        <label for="file">Pilih File Excel</label>
                        <input type="file" class="form-control" id="file" name="file" accept=".xlsx, .xls" required>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Tutup</button>
                    <button type="submit" class="btn btn-primary">Import</button>
                </div>
            </form>
        </div>
    </div>
</div>



        <!-- Table History Pemakaian -->
        <table class="table table-bordered">
            <thead style="background-color: #E36B16; color: white">
                <tr>
                    <th>Bulan</th>
                    <th>Jumlah Pemakaian</th>
                    
                    <th>Aksi</th>
                </tr>
            </thead>
            <tbody>
                @foreach($histories as $history)
                <tr>
                    <td>{{ $history->bulan }}</td>
                    <td>{{ $history->jumlah }}</td>
                    
                    <td>
                        <button class="btn btn-warning btn-sm" data-toggle="modal" data-target="#editModal" 
                                data-id="{{ $history->id }}" 
                                data-bulan="{{ $history->bulan }}" 
                                data-jumlah_pemakaian="{{ $history->jumlah }}">Edit</button>
                        <form action="{{ route('obat.destroyHistory', [$obat->id, $history->id]) }}" method="POST" style="display:inline;">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-danger btn-sm">Hapus</button>
                        </form>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>

        <!-- Pagination Links -->
        <div class="d-flex justify-content-center">
            {{ $histories->links('pagination::bootstrap-4') }}
        </div>
    </div>

    <!-- Create Modal -->
    <div class="modal fade" id="createModal" tabindex="-1" role="dialog" aria-labelledby="createModalLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="createModalLabel">Tambah History Pemakaian</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <form action="{{ route('obat.storeHistory', $obat->id) }}" method="POST">
                    @csrf
                    <div class="modal-body">
                        <div class="form-group">
                            <label for="bulan">Bulan</label>
                            <input type="date" class="form-control" id="bulan" name="bulan" required>
                        </div>
                        <div class="form-group">
                            <label for="jumlah_pemakaian">Jumlah Pemakaian</label>
                            <input type="number" class="form-control" id="jumlah_pemakaian" name="jumlah_pemakaian" required>
                        </div>
                       
                        <input type="hidden" name="obat_id" value="{{ $obat->id }}">
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Tutup</button>
                        <button type="submit" class="btn btn-primary">Simpan</button>
                    </div>
                </form>
                
            </div>
        </div>
    </div>

    <!-- Edit Modal -->
    <div class="modal fade" id="editModal" tabindex="-1" role="dialog" aria-labelledby="editModalLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="editModalLabel">Edit History Pemakaian</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <form id="editForm" method="POST">
                    @csrf
                    @method('PUT')
                    <div class="modal-body">
                        <div class="form-group">
                            <label for="edit_bulan">Bulan</label>
                            <input type="date" class="form-control" id="edit_bulan" name="bulan" required>
                        </div>
                        <div class="form-group">
                            <label for="edit_jumlah_pemakaian">Jumlah Pemakaian</label>
                            <input type="number" class="form-control" id="edit_jumlah_pemakaian" name="jumlah_pemakaian" required>
                        </div>
                        

                        <input type="hidden" id="edit_id" name="id">
                        <input type="hidden" name="obat_id" value="{{ $obat->id }}">
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Tutup</button>
                        <button type="submit" class="btn btn-primary">Simpan</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
        </div>

    <!-- Include your scripts -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/admin-lte@3.1/dist/js/adminlte.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.16.1/umd/popper.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/4.6.0/js/bootstrap.min.js"></script>

    <script>
        // Populate Edit Modal with data
        $('#editModal').on('show.bs.modal', function (event) {
            var button = $(event.relatedTarget); // Button that triggered the modal
            var id = button.data('id'); // Extract info from data-* attributes
            var bulan = button.data('bulan');
            var jumlah_pemakaian = button.data('jumlah_pemakaian');

            var modal = $(this);
            modal.find('#edit_id').val(id);
            modal.find('#edit_bulan').val(bulan);
            modal.find('#edit_jumlah_pemakaian').val(jumlah_pemakaian);
            modal.find('form').attr('action', '{{ route('obat.updateHistory', [$obat->id, '']) }}/' + id);
        });
    </script>

</body>
</html>
