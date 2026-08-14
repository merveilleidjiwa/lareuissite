<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Administration - @yield('title')</title>
    <link rel="stylesheet" href="{{ asset('backend/css/bootstrap.min.css') }}">
    <style>
        body { font-size: .875rem; }
        .sidebar { min-height: 100vh; box-shadow: inset -1px 0 0 rgba(0, 0, 0, .1); }
        .sidebar-sticky { position: sticky; top: 0; height: calc(100vh - 48px); padding-top: .5rem; overflow-x: hidden; overflow-y: auto; }
        .fade-in { animation: fadeIn 0.5s ease-in-out; }
        @keyframes fadeIn { 0% { opacity: 0; } 100% { opacity: 1; } }
    </style>
</head>
<body>
    
<header class="navbar navbar-dark sticky-top bg-dark flex-md-nowrap p-0 shadow">
  <a class="navbar-brand col-md-3 col-lg-2 me-0 px-3 fs-6" href="#">La reuisite Admin</a>
  <button class="navbar-toggler position-absolute d-md-none collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#sidebarMenu">
    <span class="navbar-toggler-icon"></span>
  </button>
  <div class="navbar-nav">
    <div class="nav-item text-nowrap">
      <a class="nav-link px-3" href="/">Retour au site</a>
    </div>
  </div>
</header>

<div class="container-fluid fade-in">
  <div class="row">
    @include('admin.layouts.sidebar')

    <main class="col-md-9 ms-sm-auto col-lg-10 px-md-4 py-4">
      <div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
        <h1 class="h2">@yield('title')</h1>
      </div>
      
      @if (session('success'))
          <div class="alert alert-success">{{ session('success') }}</div>
      @endif

      @yield('content')
    </main>
  </div>
</div>

<script src="{{ asset('backend/js/bootstrap.bundle.min.js') }}"></script>
</body>
</html>
