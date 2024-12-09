<ul class="navbar-nav bg-gradient-primary sidebar sidebar-dark accordion" id="accordionSidebar">

    <!-- Sidebar - Brand -->
    <a class="sidebar-brand d-flex align-items-center justify-content-center" href="{{ route('dashboard') }}">
      <div class="sidebar-brand-icon rotate-n-15">
        {{-- <i class="fas fa-laugh-wink"></i> --}}
      </div>
      <div class="sidebar-brand-text mx-3">CV Bina<br>Nusa Prima</div>
    </a>

    <hr class="sidebar-divider my-0">

    <li class="nav-item">
      <a class="nav-link" href="{{ route('dashboard') }}">
        <i class="fas fa-home"></i>
        <span>Dashboard</span>
      </a>
    </li>

    @if (Auth::check() && Auth::user()->hasRole('admin'))
    <!-- Nav Item - User -->
    <li class="nav-item">
      <a class="nav-link" href="{{ route('users') }}">
        <i class="fas fa-users"></i>
        <span>User</span>
      </a>
    </li>
    @endif

    <!-- Nav Item - Absensi -->
    <li class="nav-item">
      <a class="nav-link" href="{{ route('attendance') }}">
        <i class="fas fa-calendar-check"></i>
        <span>Absensi</span>
      </a>
    </li>

    @if (Auth::check() && (Auth::user()->hasPosition('Admin') || Auth::user()->hasPosition('Marketing')))
    <!-- Nav Item - Prospect -->
    <li class="nav-item">
      <a class="nav-link" href="{{ route('prospects') }}">
        <i class="fas fa-briefcase"></i>
        <span>Prospect</span>
      </a>
    </li>
    @endif
    @if (Auth::check() && !Auth::user()->hasPosition('Pengawas'))
    <li class="nav-item">
      <a class="nav-link" href="{{ route('surveys') }}">
        <i class="fas fa-poll"></i>
        <span>Survey</span>
      </a>
    </li>

    <!-- Nav Item - Penawaran Project -->
    <li class="nav-item">
      <a class="nav-link" href="{{ route('penawaran_projects') }}">
        <i class="fas fa-handshake"></i>
        <span>Penawaran Project</span>
      </a>
    </li>

    <!-- Nav Item - Deal Project -->
    <li class="nav-item">
      <a class="nav-link" href="{{ route('deal_projects') }}">
        <i class="fas fa-check-circle"></i>
        <span>Deal Project</span>
      </a>
    </li>
    @endif

    <!-- Nav Item - Report Project -->
    <li class="nav-item">
      <a class="nav-link" href="{{ route('report_projects') }}">
        <i class="fas fa-chart-line"></i>
        <span>Report Project</span>
      </a>
    </li>

    <!-- Nav Item - Material -->
    <li class="nav-item">
      <a class="nav-link" href="{{ route('materials') }}">
        <i class="fas fa-cubes"></i>
        <span>Material</span>
      </a>
    </li>

    <!-- Nav Item - Opnam -->
    <li class="nav-item">
      <a class="nav-link" href="{{ route('opnams') }}">
        <i class="fas fa-tools"></i>
        <span>Opnam</span>
      </a>
    </li>

    <!-- Nav Item - Kendala -->
    <li class="nav-item">
      <a class="nav-link" href="{{ route('constraints') }}">
        <i class="fas fa-exclamation-triangle"></i>
        <span>Kendala</span>
      </a>
    </li>

    <hr class="sidebar-divider d-none d-md-block">

    <div class="text-center d-none d-md-inline">
      <button class="rounded-circle border-0" id="sidebarToggle"></button>
    </div>

  </ul>
