  <!-- Main Sidebar Container -->
  <aside class="main-sidebar sidebar-dark-primary elevation-4" style="background-color: #00296B">
    <!-- Brand Logo -->
    <a href="index3.html" class="brand-link">
      <img src="dist/img/logo puskesmas.png" alt="AdminLTE Logo" class="brand-image img-circle elevation-3">
      <span class="brand-text font-weight-light" style="color:white">PharmaTrack</span>
    </a>

    <!-- Sidebar -->
    <div class="sidebar" style="background-color: #00296B">
      <!-- Sidebar user panel (optional) -->
      <div class="user-panel mt-3 pb-3 mb-3 d-flex">
        <div class="image">
          <img src="dist/img/user2-160x160.jpg" class="img-circle elevation-2" alt="User Image">
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
          <li class="nav-item menu-open">
            <a class="nav-link {{ request()->is('dashboard') ? 'active' : '' }}" href="{{ route('dashboard') }}">
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
            <a class="nav-link {{ request()->is('prediksi') ? 'active' : '' }}" href="{{ url('prediksi') }}">
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

