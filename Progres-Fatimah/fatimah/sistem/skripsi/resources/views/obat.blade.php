@extends('main')

@section('tittle', 'dashboard')

@section('breadcrumbs')

@endsection

@section('content')


 <!-- Content Wrapper -->
 <div class="content-wrapper">
  <div class="content">
    <div class="container-fluid">
      <h1 class="my-4">Data Obat</h1>
<!-- Button to Open the Modal for Importing Obat -->
<button type="button" class="btn btn-success mb-3" data-toggle="modal" data-target="#importObatModal">
    Import Obat
</button>

<!-- The Modal for Importing Obat -->
<div class="modal fade" id="importObatModal">
    <div class="modal-dialog">
        <div class="modal-content">
            <!-- Modal Header -->
            <div class="modal-header">
                <h4 class="modal-title">Import Data Obat</h4>
                <button type="button" class="close" data-dismiss="modal">&times;</button>
            </div>
            
            <!-- Modal Body -->
            <div class="modal-body">
                <form action="{{ route('obat.import') }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    <div class="form-group">
                        <label for="file">Pilih File Excel</label>
                        <input type="file" class="form-control" id="file" name="file" required accept=".xlsx, .xls, .csv">
                    </div>

                    <button type="submit" class="btn btn-success">Import</button>
                </form>
            </div>
            
            <!-- Modal Footer -->
            <div class="modal-footer">
                <button type="button" class="btn btn-danger" data-dismiss="modal">Tutup</button>
            </div>
        </div>
    </div>
</div>

    <!-- Button to Open the Modal for Adding Obat -->
    <button type="button" class="btn btn-primary mb-3" data-toggle="modal" data-target="#addObatModal">
        Tambah Obat
    </button>

    <!-- The Modal for Adding Obat -->
    <div class="modal fade" id="addObatModal">
        <div class="modal-dialog">
            <div class="modal-content">
                <!-- Modal Header -->
                <div class="modal-header">
                    <h4 class="modal-title">Tambah Obat Baru</h4>
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                </div>
                
                <!-- Modal Body -->
                <div class="modal-body">
                    <form action="{{ route('obat.store') }}" method="POST">
                        @csrf
                        <div class="form-group">
                            <label for="kode_obat">Kode Obat</label>
                            <input type="text" class="form-control" id="kode_obat" name="kode_obat" required>
                        </div>
                        <div class="form-group">
                            <label for="nama_obat">Nama Obat</label>
                            <input type="text" class="form-control" id="nama_obat" name="nama_obat" required>
                        </div>
                        <div class="form-group">
                            <label for="stok">Stok</label>
                            <input type="number" class="form-control" id="stok" name="stok" value="0" required>
                        </div>

                        <button type="submit" class="btn btn-primary">Simpan</button>
                    </form>
                </div>
                
                <!-- Modal Footer -->
                <div class="modal-footer">
                    <button type="button" class="btn btn-danger" data-dismiss="modal">Tutup</button>
                </div>
            </div>
        </div>
    </div>

    <!-- The Modal for Editing Obat -->
    <div class="modal fade" id="editObatModal">
        <div class="modal-dialog">
            <div class="modal-content">
                <!-- Modal Header -->
                <div class="modal-header">
                    <h4 class="modal-title">Edit Obat</h4>
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                </div>
                
                <!-- Modal Body -->
                <div class="modal-body">
                    <form id="editObatForm" method="POST">
                        @csrf
                        @method('PUT')
                        <div class="form-group">
                            <label for="edit_kode_obat">Kode Obat</label>
                            <input type="text" class="form-control" id="edit_kode_obat" name="kode_obat" required>
                        </div>
                        <div class="form-group">
                            <label for="edit_nama_obat">Nama Obat</label>
                            <input type="text" class="form-control" id="edit_nama_obat" name="nama_obat" required>
                        </div>
                        <div class="form-group">
                            <label for="stok">Stok</label>
                            <input type="number" class="form-control" id="stok" name="stok" value="0" required>
                        </div>

                        <button type="submit" class="btn btn-primary">Update</button>
                    </form>
                </div>
                
                <!-- Modal Footer -->
                <div class="modal-footer">
                    <button type="button" class="btn btn-danger" data-dismiss="modal">Tutup</button>
                </div>
            </div>
        </div>
    </div>

    <!-- Table Data Obat -->
    <section class="content">
      <div class="container-fluid">
        <div class="row">
          <div class="col-12">
            <div class="card">
              <div class="card-body">
                <table class="table table-bordered">
                <thead style="background-color: #E36B16; color: white">
                <tr>
                <th>Kode Obat</th>
                <th>Nama Obat</th>
                <th>Total Pemakaian</th>
                <th>Aksi</th>
            </tr>
        </thead>
        <tbody>
            @foreach($obats as $obat)
            <tr>
                <td>{{ $obat->kode_obat }}</td>
                <td>{{ $obat->nama_obat }}</td>
                <td>{{ $obat->stok() }}</td></td> <!-- Stok dikurangi total pemakaian -->
                <td>
                    <!-- Trigger the Edit Modal -->
                    <button type="button" class="btn btn-warning btn-sm" 
                            data-toggle="modal" 
                            data-target="#editObatModal" 
                            data-id="{{ $obat->id }}"
                            data-kode_obat="{{ $obat->kode_obat }}"
                            data-nama_obat="{{ $obat->nama_obat }}"
                            data-stok="{{ $obat->stok }}">
                        Edit
                    </button>
                    <form action="{{ route('obat.destroy', $obat->id) }}" method="POST" style="display:inline;">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="btn btn-danger btn-sm">Hapus</button>
                    </form>
                    <a href="{{ route('obat.history', $obat->id) }}" class="btn btn-info btn-sm">History</a>
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>
    <!-- Tambahkan navigasi paginasi -->
<div class="d-flex justify-content-center mt-3">
    {{ $obats->links('pagination::bootstrap-4') }}
</div>
</div>
</div>
</div>
</div>
</div>
</div>
<!-- Control Sidebar -->
<aside class="control-sidebar control-sidebar-dark">
<!-- Control sidebar content goes here -->
</aside>
<!-- /.control-sidebar -->
</div>
<!-- ./wrapper -->
<!-- Include your scripts -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/admin-lte@3.1/dist/js/adminlte.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.16.1/umd/popper.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/4.6.0/js/bootstrap.min.js"></script>

<script>
    // Script to populate the edit modal with the current data
    $('#editObatModal').on('show.bs.modal', function (event) {
        var button = $(event.relatedTarget);
        var id = button.data('id');
        var kode_obat = button.data('kode_obat');
        var nama_obat = button.data('nama_obat');
        var stok = button.data('stok');
        
        var modal = $(this);
        modal.find('#edit_kode_obat').val(kode_obat);
        modal.find('#edit_nama_obat').val(nama_obat);
        modal.find('#edit_stok').val(stok);

        var formAction = '{{ route("obat.update", ":id") }}';
        formAction = formAction.replace(':id', id);
        $('#editObatForm').attr('action', formAction);
    });
</script>



<!-- jQuery -->
<script src="plugins/jquery/jquery.min.js"></script>
<!-- jQuery UI 1.11.4 -->
<script src="plugins/jquery-ui/jquery-ui.min.js"></script>
<!-- Resolve conflict in jQuery UI tooltip with Bootstrap tooltip -->
<script>
$.widget.bridge('uibutton', $.ui.button)
</script>
<!-- Bootstrap 4 -->
<script src="plugins/bootstrap/js/bootstrap.bundle.min.js"></script>
<!-- ChartJS -->
<script src="plugins/chart.js/Chart.min.js"></script>
<!-- Sparkline -->
<script src="plugins/sparklines/sparkline.js"></script>
<!-- JQVMap -->
<script src="plugins/jqvmap/jquery.vmap.min.js"></script>
<script src="plugins/jqvmap/maps/jquery.vmap.usa.js"></script>
<!-- jQuery Knob Chart -->
<script src="plugins/jquery-knob/jquery.knob.min.js"></script>
<!-- daterangepicker -->
<script src="plugins/moment/moment.min.js"></script>
<script src="plugins/daterangepicker/daterangepicker.js"></script>
<!-- Tempusdominus Bootstrap 4 -->
<script src="plugins/tempusdominus-bootstrap-4/js/tempusdominus-bootstrap-4.min.js"></script>
<!-- Summernote -->
<script src="plugins/summernote/summernote-bs4.min.js"></script>
<!-- overlayScrollbars -->
<script src="plugins/overlayScrollbars/js/jquery.overlayScrollbars.min.js"></script>
<!-- AdminLTE App -->
<script src="dist/js/adminlte.js"></script>
<!-- AdminLTE for demo purposes -->
<script src="dist/js/demo.js"></script>
<!-- AdminLTE dashboard demo (This is only for demo purposes) -->
<script src="dist/js/pages/dashboard.js"></script>

</body>
</html>

@endsection