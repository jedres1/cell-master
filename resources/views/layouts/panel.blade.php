<!DOCTYPE html>
<html>

<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
  <meta name="description" content="Start your development with a Dashboard for Bootstrap 4.">
  <meta name="author" content="Creative Tim">
  <title>Movidev</title>
  <!-- Favicon -->
  <link href="{{ asset('img/theme/main.png')}}" rel="icon" type="image/png">
  <!-- Fonts -->
  <link href="https://fonts.googleapis.com/css?family=Open+Sans:300,400,600,700" rel="stylesheet">
  <!-- Icons -->
  <link href="{{ asset('vendor/nucleo/css/nucleo.css')}}" rel="stylesheet">
  <link href="{{ asset('vendor/@fortawesome/fontawesome-free/css/all.min.css')}}" rel="stylesheet">
  <!-- Argon CSS -->
  <link type="text/css" href="{{ asset('css/argon.css?v=1.0.0')}}" rel="stylesheet">

  
</head>

<body>
  @php
    $currentPath = request()->path();
    $pageTitle = trim($__env->yieldContent('page_title')) ?: 'Panel';
    $pageSubtitle = trim($__env->yieldContent('page_subtitle'));
  @endphp
  <div id="app">
  <!-- Sidenav -->
  <nav class="navbar navbar-vertical fixed-left navbar-expand-md navbar-light bg-white" id="sidenav-main">
    <div class="container-fluid">
      <!-- Toggler -->
      <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#sidenav-collapse-main" aria-controls="sidenav-main" aria-expanded="false" aria-label="Toggle navigation">
        <span class="navbar-toggler-icon"></span>
      </button>
      <!-- Brand -->
      <a class="navbar-brand pt-0" href="./index.html">
        <img src="{{ asset('img/theme/app.png')}}" class="navbar-brand-img" alt="...">
      </a>
      <!-- User -->
      <ul class="nav align-items-center d-md-none">
        <li class="nav-item dropdown">
          <a class="nav-link nav-link-icon" href="#" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
            <i class="ni ni-bell-55"></i>
          </a>
          <div class="dropdown-menu dropdown-menu-arrow dropdown-menu-right" aria-labelledby="navbar-default_dropdown_1">
            <a class="dropdown-item" href="#">Action</a>
            <a class="dropdown-item" href="#">Another action</a>
            <div class="dropdown-divider"></div>
            <a class="dropdown-item" href="#">Something else here</a>
          </div>
        </li>
        <li class="nav-item dropdown">
          <a class="nav-link" href="#" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
            <div class="media align-items-center">
              <span class="avatar avatar-sm rounded-circle">
                <img alt="Image placeholder" src="{{ asset('img/theme/man.png')}}">
              </span>
            </div>
          </a>
          <div class="dropdown-menu dropdown-menu-arrow dropdown-menu-right">
            <div class=" dropdown-header noti-title">
              <h6 class="text-overflow m-0">Welcome!</h6>
            </div>
            <a href="./examples/profile.html" class="dropdown-item">
              <i class="ni ni-single-02"></i>
              <span>My profile</span>
            </a>
            <a href="./examples/profile.html" class="dropdown-item">
              <i class="ni ni-settings-gear-65"></i>
              <span>Settings</span>
            </a>
            <a href="./examples/profile.html" class="dropdown-item">
              <i class="ni ni-calendar-grid-58"></i>
              <span>Activity</span>
            </a>
            <a href="./examples/profile.html" class="dropdown-item">
              <i class="ni ni-support-16"></i>
              <span>Support</span>
            </a>
            <div class="dropdown-divider"></div>
            <a href="#!" class="dropdown-item">
              <i class="ni ni-user-run"></i>
              <span>Logout</span>
            </a>
          </div>
        </li>
      </ul>
      <!-- Collapse -->
      <div class="collapse navbar-collapse" id="sidenav-collapse-main">
        <!-- Collapse header -->
        <div class="navbar-collapse-header d-md-none">
          <div class="row">
            <div class="col-6 collapse-brand">
              <a href="./index.html">
                <img src="{{ asset('img/brand/blue.png')}}">
              </a>
            </div>
            <div class="col-6 collapse-close">
              <button type="button" class="navbar-toggler" data-toggle="collapse" data-target="#sidenav-collapse-main" aria-controls="sidenav-main" aria-expanded="false" aria-label="Toggle sidenav">
                  
              </button>
            </div>
          </div>
        </div>
        <!-- Form -->
        <form class="mt-4 mb-3 d-md-none" action="{{ url()->current() }}" method="GET">
          <div class="input-group input-group-rounded input-group-merge">
            <input type="search" name="search" value="{{ !empty($filters) ? $filters['search'] : '' }}" class="form-control form-control-rounded form-control-prepended" placeholder="Buscar" aria-label="Buscar">
            <div class="input-group-prepend">
              <div class="input-group-text">
                <span class="fa fa-search"></span>
              </div>
            </div>
          </div>
          <button type="submit" class="btn btn-primary btn-sm mt-3">Enviar</button>
        </form>
        <!-- Navigation -->
        <ul class="navbar-nav">
          <li class="nav-item">
            <a class="nav-link {{ request()->is('home') ? 'active bg-primary text-white rounded' : '' }}" href="{{url('/home')}}/">
              <i class="ni ni-tv-2 text-primary"></i> Home
            </a>
          </li>
          <li class="nav-item">
            <a class="nav-link {{ request()->is('numbers*') ? 'active bg-primary text-white rounded' : '' }}" href="/numbers">
              <i class="ni ni-badge text-blue"></i> Numeros
            </a>
          </li>
          <li class="nav-item">
            <a class="nav-link {{ request()->is('cellphones*') ? 'active bg-primary text-white rounded' : '' }}" href="{{ url('/cellphones') }}">
              <i class="ni ni-mobile-button text-blue"></i> Celulares
            </a>
          </li>
          <li class="nav-item">
            <a class="nav-link {{ request()->is('employees*') ? 'active bg-primary text-white rounded' : '' }}" href="/employees">
              <i class="ni ni-single-02 text-orange"></i> Empleados
              
            </a>
          </li>
          <li class="nav-item">
            <a class="nav-link {{ request()->is('assignments*') ? 'active bg-primary text-white rounded' : '' }}" href="/assignments">
              <i class="ni ni-badge text-blue"></i> Asignaciones
            </a>
          </li>
          <li class="nav-item">
            <a class="nav-link" href="{{ route('logout') }}" onclick="event.preventDefault(); document.getElementById('formLogout').submit();">
              <i class="ni ni-key-25 "></i> Cerrar Sesion
            </a>
            <form action="{{ route('logout') }}" method="POST" style="display:none;" id="formLogout">
              @csrf
          </form>
          </li>
        </ul>
        <!-- Divider -->
        <hr class="my-3">
        <!-- Heading -->
        
      </div>
    </div>
  </nav>
  <!-- Main content -->
  <div class="main-content">
    <!-- Top navbar -->
    <nav class="navbar navbar-top navbar-expand-md navbar-dark" id="navbar-main">
      <div class="container-fluid">
        <!-- Brand -->
        <a class="h4 mb-0 text-darkblue text-uppercase d-none d-lg-inline-block" href="/">{{ $pageTitle }}</a>
        <!-- Form -->
        <form class="navbar-search navbar-search-dark form-inline mr-3 d-none d-md-flex ml-lg-auto" method="GET" action="{{ url()->current() }}">
          <div class="form-group mb-0">
            <div class="input-group input-group-alternative">
              <div class="input-group-prepend">
                <span class="input-group-text"><i class="fas fa-search"></i></span>
              </div>
              <input class="form-control" name="search" value="{{ !empty($filters) ? $filters['search'] : '' }}" placeholder="Buscar" type="text">
            </div>
          </div>
          <button type="submit" class="btn btn-primary btn-sm ml-2">Enviar</button>
        </form>
        <!-- User -->
        <ul class="navbar-nav align-items-center d-none d-md-flex">
          <li class="nav-item dropdown">
            <a class="nav-link pr-0" href="#" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
              <div class="media align-items-center">
                <span class="avatar avatar-sm rounded-circle">
                  <img alt="Image placeholder" src="{{ asset('img/theme/man.png')}}">
                </span>
                <div class="media-body ml-2 d-none d-lg-block">
                  <span class="mb-0 text-sm  font-weight-bold">{{ auth()->user()->email }}</span>
                </div>
              </div>
            </a>
            <div class="dropdown-menu dropdown-menu-arrow dropdown-menu-right">
              <div class=" dropdown-header noti-title">
                <h6 class="text-overflow m-0">Welcome! {{auth()->user()->name}}</h6>
              </div>
              
              <div class="dropdown-divider"></div>
              <a href="{{ route('logout') }} " class="dropdown-item"onclick="event.preventDefault(); document.getElementById('formLogout').submit();">
                <i class="ni ni-user-run"></i>
                <span>Cerrar Sesion</span>
              </a>
            </div>
          </li>
        </ul>
      </div>
    </nav>
    <!-- Header -->
    <div class="header bg-gradient-primary2 pb-8 pt-5 pt-md-5">
    </div>
    <!-- Page content -->
    <div class="container-fluid mt--5">
      <div class="row mb-4">
        <div class="col-12">
          <div class="card shadow-sm border-0">
            <div class="card-body">
              <h1 class="h3 mb-1">{{ $pageTitle }}</h1>
              @if($pageSubtitle)
                <p class="mb-0 text-muted">{{ $pageSubtitle }}</p>
              @endif
            </div>
          </div>
        </div>
      </div>
      @yield('content')
      <!-- Footer -->
      <footer class="footer">
        <div class="row align-items-center justify-content-xl-between">
          <div class="col-xl-6">
            <div class="copyright text-center text-xl-left text-muted">
              &copy; 2018 <a href="/" class="font-weight-bold ml-1" target="_blank">Cell</a>
            </div>
          </div>
          <div class="col-xl-6">
            <ul class="nav nav-footer justify-content-center justify-content-xl-end">
              <li class="nav-item">
                <a href="https://www.creative-tim.com" class="nav-link" target="_blank">Template</a>
              </li>
              
            </ul>
          </div>
        </div>
      </footer>
    </div>
  </div>
</div>
  <!-- Argon Scripts 
  <script src="{{ asset('/js/app.js')}}"></script>-->
  <!-- Core -->
  <script src="{{ asset('vendor/jquery/dist/jquery.min.js')}}"></script>
  <script src="{{ asset('vendor/bootstrap/dist/js/bootstrap.bundle.min.js')}}"></script>
  <!-- Optional JS -->
  <script src="{{ asset('vendor/chart.js/dist/Chart.min.js')}}"></script>
  <script src="{{ asset('vendor/chart.js/dist/Chart.extension.js')}}"></script>
  
  <!-- Argon JS -->
  <script src="{{ asset('js/argon.js?v=1.0.0')}}"></script>
  @stack('scripts')
  @yield('scripts')
</body>

</html>
