<?php
if (!isset($is_connected)) $is_connected = false;
if (!isset($user_nom)) $user_nom = '';
if (!isset($initiale)) $initiale = '';
if (!isset($erreur)) $erreur = '';
if (!isset($message)) $message = '';
if (!isset($success)) $success = '';
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>La reuisite agronomique - @yield('title')</title>
    <!-- Bootstrap CSS local -->
    <link rel="stylesheet" href="{{ asset('frontend/css/bootstrap.min.css') }}">
    <link rel="stylesheet" href="{{ asset('frontend/css/style.css') }}">
    <!-- Ajout de styles personnalisés pour des animations -->
    <style>
        .fade-in { animation: fadeIn 1s ease-in-out; }
        @keyframes fadeIn {
            0% { opacity: 0; transform: translateY(20px); }
            100% { opacity: 1; transform: translateY(0); }
        }
    </style>
</head>
<body>
    @include('client.layouts.header')

    <main class="container my-5 fade-in">
        @yield('content')
    </main>

    @include('client.layouts.footer')

    <!-- Bootstrap JS local -->
    <script src="{{ asset('frontend/js/bootstrap.bundle.min.js') }}"></script>
    <script src="{{ asset('frontend/js/script.js') }}"></script>
    @yield('scripts')
</body>
</html>
