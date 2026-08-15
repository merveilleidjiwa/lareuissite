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
    <title>Créer un compte - La Réussite</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Poppins:wght@400;600;700;800&display=swap');
        .auth-page { font-family: 'Poppins', sans-serif; }
        .input-auth { @apply w-full bg-gray-50 border border-gray-200 rounded-xl px-4 py-3 outline-none transition-all duration-300 text-gray-800 font-medium; }
        .input-auth:focus { @apply border-[#27ae60] ring-4 ring-[#27ae60]/20 bg-white; }
        .btn-auth { @apply w-full bg-[#27ae60] hover:bg-[#219150] text-white font-bold py-3.5 rounded-xl uppercase tracking-widest text-sm shadow-lg shadow-[#27ae60]/40 transition-all duration-300 hover:-translate-y-1 hover:shadow-[#27ae60]/50 active:translate-y-0; }
        .btn-google { @apply w-full flex items-center justify-center gap-3 bg-white border-2 border-gray-100 hover:bg-gray-50 text-gray-700 font-bold py-3.5 rounded-xl text-sm transition-all duration-300 hover:-translate-y-1; }
    </style>
</head>
<body class="auth-page min-h-screen flex relative overflow-hidden bg-gradient-to-br from-[#27ae60] to-[#1e8449]">

    <!-- Éléments de décor du fond (Cercles) -->
    <div class="absolute inset-0 pointer-events-none opacity-20">
        <div class="absolute top-[-10%] left-[-5%] w-96 h-96 rounded-full bg-white blur-3xl"></div>
        <div class="absolute bottom-[-10%] right-[-5%] w-[500px] h-[500px] rounded-full bg-white blur-3xl"></div>
        <div class="absolute top-20 left-20 w-64 h-64 rounded-full bg-white opacity-40"></div>
        <div class="absolute bottom-32 right-20 w-48 h-48 rounded-full bg-white opacity-40"></div>
    </div>

    <!-- Conteneur central -->
    <div class="flex-1 flex flex-col items-center justify-center p-6 relative z-10">
        
        <!-- Logo -->
        <a href="{{ url('/') }}" class="mb-8 flex flex-col items-center gap-2 group">
            <img src="/logo-reussite.png" class="w-16 h-16 object-cover rounded-full border-2 border-white/60 shadow-2xl group-hover:scale-110 transition-transform duration-300" alt="Logo">
            <div class="text-center">
                <span class="font-brand text-3xl text-white drop-shadow-md font-bold tracking-tight">La Réussite</span>
                <span class="block text-xs text-[#F9A825] uppercase tracking-[0.3em] font-black mt-1">Agronomique</span>
            </div>
        </a>

        <!-- Carte Formulaire -->
        <div class="w-full max-w-md bg-white rounded-3xl shadow-2xl shadow-black/20 p-8 lg:p-10 transform transition-all">
            <div class="text-center mb-8">
                <h1 class="text-2xl font-black text-gray-800 mb-1">Créer un compte</h1>
                <p class="text-gray-500 text-sm font-medium">Rejoignez La Réussite</p>
            </div>

            <?php if(!empty($erreur)): ?>
                <div class="bg-red-50 border-l-4 border-red-500 text-red-700 p-4 rounded-r-xl mb-6 text-sm font-bold flex items-center gap-3">
                    <i class="fas fa-exclamation-circle text-lg"></i>
                    <?php echo htmlspecialchars($erreur, ENT_QUOTES, 'UTF-8'); ?>
                </div>
            <?php endif; ?>

            <form method="POST" class="space-y-6">
                                        <div class="space-y-4">
                            <div class="flex gap-4">
                                <div class="flex-1">
                                    <label class="block text-gray-700 font-bold text-xs uppercase tracking-wider mb-2">Prénom</label>
                                    <div class="relative">
                                        <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none"><i class="fas fa-user text-gray-400"></i></div>
                                        <input type="text" name="prenom" required placeholder="Prénom" class="pl-11 input-auth">
                                    </div>
                                </div>
                                <div class="flex-1">
                                    <label class="block text-gray-700 font-bold text-xs uppercase tracking-wider mb-2">Nom</label>
                                    <input type="text" name="nom" required placeholder="Nom" class="input-auth">
                                </div>
                            </div>
                            <div>
                                <label class="block text-gray-700 font-bold text-xs uppercase tracking-wider mb-2">Email</label>
                                <div class="relative">
                                    <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none"><i class="fas fa-envelope text-gray-400"></i></div>
                                    <input type="email" name="email" required placeholder="votre@email.com" class="pl-11 input-auth">
                                </div>
                            </div>
                            <div>
                                <label class="block text-gray-700 font-bold text-xs uppercase tracking-wider mb-2">Téléphone</label>
                                <div class="relative">
                                    <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none"><i class="fas fa-phone text-gray-400"></i></div>
                                    <input type="text" name="telephone" required placeholder="Ex: 0167424373" class="pl-11 input-auth">
                                </div>
                            </div>
                            <div>
                                <label class="block text-gray-700 font-bold text-xs uppercase tracking-wider mb-2">Mot de passe</label>
                                <div class="relative">
                                    <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none"><i class="fas fa-lock text-gray-400"></i></div>
                                    <input type="password" id="password" name="password" required placeholder="••••••••" class="pl-11 input-auth">
                                    <button type="button" onclick="togglePassword()" class="absolute inset-y-0 right-0 pr-4 flex items-center text-gray-400 hover:text-gray-600 focus:outline-none">
                                        <i id="eye-icon" class="fas fa-eye"></i>
                                    </button>
                                </div>
                            </div>
                        </div>

                <button type="submit" class="btn-auth">
                    <i class="fas fa-sign-in-alt mr-2"></i> S'inscrire
                </button>
            </form>

            <div class="mt-6 flex items-center gap-4">
                <div class="flex-1 h-px bg-gray-200"></div>
                <span class="text-gray-400 text-xs font-bold uppercase tracking-wider">OU</span>
                <div class="flex-1 h-px bg-gray-200"></div>
            </div>

            <div class="mt-6">
                <button type="button" class="btn-google">
                    <img src="https://www.svgrepo.com/show/475656/google-color.svg" alt="Google" class="w-5 h-5">
                    Continuer avec Google
                </button>
            </div>

            <p class="text-center mt-8 text-gray-500 text-sm font-medium">
                Déjà un compte ? <a href="{{ url('connexion') }}" class="text-[#27ae60] font-bold hover:underline">Se connecter</a>
            </p>
        </div>

        <a href="{{ url('/') }}" class="mt-8 text-white/80 hover:text-white text-sm font-medium transition-colors flex items-center gap-2">
            <i class="fas fa-arrow-left"></i> Retour à l'accueil
        </a>
    </div>

    <script>
        function togglePassword() {
            const pwd = document.getElementById('password');
            const icon = document.getElementById('eye-icon');
            if (pwd.type === 'password') {
                pwd.type = 'text';
                icon.classList.remove('fa-eye');
                icon.classList.add('fa-eye-slash');
            } else {
                pwd.type = 'password';
                icon.classList.remove('fa-eye-slash');
                icon.classList.add('fa-eye');
            }
        }
    </script>
</body>
</html>