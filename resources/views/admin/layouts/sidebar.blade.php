<?php
if (!isset($is_connected)) $is_connected = false;
if (!isset($user_nom)) $user_nom = '';
if (!isset($initiale)) $initiale = '';
if (!isset($erreur)) $erreur = '';
if (!isset($message)) $message = '';
if (!isset($success)) $success = '';
?>
<nav id="sidebarMenu" class="col-md-3 col-lg-2 d-md-block bg-light sidebar collapse">
  <div class="position-sticky pt-3 sidebar-sticky">
    <ul class="nav flex-column">
      <li class="nav-item">
        <a class="nav-link {{ request()->routeIs('admin.dashboard') ? 'active text-primary fw-bold' : 'text-dark' }}" href="{{ route('admin.dashboard') }}">
          Tableau de bord
        </a>
      </li>
      <li class="nav-item">
        <a class="nav-link {{ request()->routeIs('admin.produits.*') ? 'active text-primary fw-bold' : 'text-dark' }}" href="{{ route('admin.produits.index') }}">
          Produits
        </a>
      </li>
    </ul>
  </div>
</nav>
