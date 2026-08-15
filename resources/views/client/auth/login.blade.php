<?php
if (!isset($is_connected)) $is_connected = false;
if (!isset($user_nom)) $user_nom = '';
if (!isset($initiale)) $initiale = '';
if (!isset($erreur)) $erreur = '';
if (!isset($message)) $message = '';
if (!isset($success)) $success = '';
?>
<?php
$erreur = '';
$message = '';
$success = '';
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Connexion - La Réussite</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script>tailwind.config = { theme: { extend: { colors: { brand: { green: '#27ae60', light: '#f4fbf7', orange: '#F9A825' } } } } }</script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <link rel="stylesheet" href="/style.css">
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Poppins:wght@400;600;700;800&display=swap');
        .auth-page { font-family: 'Poppins', sans-serif; }
        .input-auth { @apply w-full bg-white/90 border-2 border-gray-100 rounded-2xl px-5 py-4 outline-none transition-all duration-300; }
        .input-auth { @apply border-gray-200 bg-white; }
        .input-auth:focus { @apply border-[#27ae60] ring-4 ring-[#27ae60]/20; }
        .btn-auth { @apply w-full bg-[#27ae60] hover:bg-[#219150] text-white font-bold py-4 rounded-2xl uppercase tracking-widest text-sm shadow-lg shadow-[#27ae60]/30 transition-all duration-300 hover:scale-[1.02] active:scale-[0.98]; }
        body.prevent-flash { opacity: 0; }
        body.loaded { opacity: 1; transition: opacity 0.3s ease; }
    </style>
</head>
<body class="auth-page min-h-screen flex bg-gradient-to-br from-[#f4fbf7] via-white to-[#e8f5ee] prevent-flash" id="body-el">

    <div id="page-loader" class="fixed inset-0 bg-[#f4fbf7] z-[99999] flex items-center justify-center">
        <img src="/logo-reussite.png" alt="" class="w-20 h-20 object-contain animate-loader">
    </div>

    <div class="flex-1 flex flex-col lg:flex-row min-h-screen w-full">
        <!-- Côté gauche : visuel / branding (responsive) -->
        <div class="flex lg:w-1/2 bg-gradient-to-br from-[#27ae60] to-[#1e8449] p-8 lg:p-16 flex-col justify-between relative overflow-hidden min-h-[180px] lg:min-h-screen">
            <div class="absolute inset-0 opacity-10">
                <div class="absolute top-20 left-20 w-64 h-64 rounded-full bg-white"></div>
                <div class="absolute bottom-32 right-20 w-48 h-48 rounded-full bg-white"></div>
            </div>
            <a href="{{ url('/') }}" class="relative flex items-center gap-3">
                <img src="/logo-reussite.png" class="w-12 h-12 lg:w-14 lg:h-14 object-cover rounded-full border-2 border-white/40 shadow-xl" alt="La Réussite">
                <span class="font-brand text-2xl lg:text-3xl text-white drop-shadow-sm">La Réussite</span>
            </a>
            <div class="relative space-y-4 lg:space-y-6 mt-4 lg:mt-0">
                <h2 class="text-2xl lg:text-4xl font-bold text-white leading-tight">Bon retour<br><span class="text-[#F9A825]">parmi nous</span></h2>
                <p class="text-white/90 text-sm lg:text-lg max-w-sm">Connectez-vous pour commander, gérer votre panier.</p>
            </div>
        </div>

        <!-- Côté droit : formulaire -->
        <div class="flex-1 flex items-center justify-center p-6 lg:p-12 overflow-y-auto">
            <div class="w-full max-w-md">
                <div class="bg-white rounded-[32px] shadow-2xl shadow-gray-200/50 border border-gray-200 p-8 lg:p-10">
                    <h1 class="text-2xl font-bold text-gray-800 mb-2">Connexion</h1>
                    <p class="text-gray-500 text-sm mb-8">Accédez à votre espace personnel</p>

                    <?php if(!empty($erreur)): ?>
                        <div class="bg-red-50 border border-red-200 text-red-700 p-4 rounded-2xl mb-6 text-sm font-bold flex items-center gap-2">
                            <i class="fas fa-exclamation-circle flex-shrink-0"></i>
                            <?php echo htmlspecialchars($erreur, ENT_QUOTES, 'UTF-8'); ?>
                        </div>
                    <?php endif; ?>

                    <form method="POST" class="space-y-5">
                        <div>
                            <label class="block text-gray-600 font-bold text-xs uppercase tracking-wider mb-2">Email</label>
                            <input type="email" name="email" required placeholder="votre@email.com" class="input-auth">
                        </div>
                        <div>
                            <label class="block text-gray-600 font-bold text-xs uppercase tracking-wider mb-2">Mot de passe</label>
                            <input type="password" name="password" required placeholder="••••••••" class="input-auth">
                        </div>
                        <button type="submit" class="btn-auth mt-2">
                            <i class="fas fa-sign-in-alt mr-2"></i> Se connecter
                        </button>
                    </form>

                    <p class="text-center mt-8 text-gray-500 text-sm">
                        Pas encore de compte ? <a href="{{ url('inscription') }}" class="text-[#27ae60] font-bold hover:underline">S'inscrire</a>
                    </p>
                </div>

                <a href="{{ url('/') }}" class="block text-center mt-6 text-gray-400 hover:text-[#27ae60] text-sm transition-colors">
                    <i class="fas fa-arrow-left mr-1"></i> Retour à l'accueil
                </a>
            </div>
        </div>
    </div>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const loader = document.getElementById('page-loader');
            const body = document.getElementById('body-el');
            if (loader) loader.style.display = 'none';
            if (body) { body.classList.remove('prevent-flash'); body.classList.add('loaded'); }
        });
    </script>
</body>
</html>
