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
    <title>Inscription Livreur - La Réussite</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script>tailwind.config = { theme: { extend: { colors: { brand: { green: '#27ae60' } } } } }</script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <link rel="stylesheet" href="style.css">
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Poppins:wght@400;600;700;800&display=swap');
        .auth-page { font-family: 'Poppins', sans-serif; }
        .input-auth { @apply w-full bg-white/90 border-2 border-gray-100 rounded-2xl px-5 py-4 outline-none transition-all duration-300; }
        .input-auth:focus { @apply border-[#27ae60] ring-4 ring-[#27ae60]/20; }
        .btn-auth { @apply w-full bg-[#27ae60] hover:bg-[#219150] text-white font-bold py-4 rounded-2xl uppercase tracking-widest text-sm shadow-lg shadow-[#27ae60]/30 transition-all duration-300 hover:scale-[1.02] active:scale-[0.98]; }
    </style>
</head>
<body class="auth-page min-h-screen flex bg-gradient-to-br from-gray-900 via-gray-800 to-[#1e3a2f]">

    <div class="flex-1 flex flex-col lg:flex-row min-h-screen">
        <!-- Côté gauche : branding livreur -->
        <div class="hidden lg:flex lg:w-1/2 bg-gradient-to-br from-[#27ae60]/25 to-[#1e8449]/20 p-16 flex-col justify-between relative overflow-hidden border-r border-white/5">
            <div class="absolute inset-0">
                <div class="absolute top-20 right-20 w-64 h-64 rounded-full bg-[#27ae60]/10 blur-3xl"></div>
                <div class="absolute bottom-32 left-20 w-48 h-48 rounded-full bg-[#F9A825]/10 blur-3xl"></div>
            </div>
            <a href="{{ url('/') }}" class="relative flex items-center gap-3">
                <img src="logo-reussite.png" alt="" class="w-14 h-14 rounded-full object-cover border-2 border-white/40 shadow-xl">
                <span class="font-brand text-2xl text-white">La Réussite</span>
                <span class="text-[#27ae60] text-xs font-bold uppercase ml-2 bg-[#27ae60]/20 px-2 py-0.5 rounded"><i class="fas fa-motorcycle mr-1"></i> Livreur</span>
            </a>
            <div class="relative space-y-6">
                <h2 class="text-4xl font-bold text-white leading-tight">Espace<br><span class="text-[#27ae60]">Livreur</span></h2>
                <p class="text-gray-400 text-lg max-w-sm">Rejoignez l'équipe de livraison. Gérez vos missions en temps réel.</p>
                <div class="flex gap-4 text-gray-500">
                    <span><i class="fas fa-motorcycle text-[#27ae60] mr-2"></i> Missions en direct</span>
                    <span><i class="fas fa-map-marker-alt text-[#27ae60] mr-2"></i> Livraisons</span>
                </div>
            </div>
        </div>

        <!-- Côté droit : formulaire -->
        <div class="flex-1 flex items-center justify-center p-6 lg:p-12">
            <div class="w-full max-w-md">
                <a href="{{ url('/') }}" class="lg:hidden flex items-center gap-3 mb-8 text-white">
                    <img src="logo-reussite.png" alt="" class="w-10 h-10 rounded-full object-cover border-2 border-white/40">
                    <span class="font-brand text-xl">La Réussite Livreur</span>
                </a>

                <div class="bg-white rounded-[32px] shadow-2xl border border-gray-100 p-8 lg:p-10">
                    <h1 class="text-2xl font-bold text-gray-800 mb-2">Créer un compte Livreur</h1>
                    <p class="text-gray-500 text-sm mb-8">Inscrivez-vous pour accéder à l'espace livraison</p>

                    <?php if ($message === "success"): ?>
                        <div class="bg-green-50 border border-green-200 text-green-800 p-5 rounded-2xl mb-6 text-center animate-fade-up">
                            <i class="fas fa-check-circle text-3xl mb-2 text-[#27ae60]"></i>
                            <p class="font-bold mb-3">Compte livreur créé !</p>
                            <p class="text-sm text-gray-600 mb-4">Connectez-vous maintenant avec vos identifiants.</p>
                            <a href="{{ url('livreur') }}" class="inline-block bg-[#27ae60] text-white px-6 py-2 rounded-xl font-bold text-sm hover:bg-[#219150] transition-colors">
                                Se connecter à l'espace livreur
                            </a>
                        </div>
                    <?php elseif (!empty($erreur)): ?>
                        <div class="bg-red-50 border border-red-200 text-red-700 p-4 rounded-2xl mb-6 text-sm font-bold flex items-center gap-2">
                            <i class="fas fa-exclamation-circle flex-shrink-0"></i>
                            <?php echo htmlspecialchars($erreur, ENT_QUOTES, 'UTF-8'); ?>
                        </div>
                    <?php endif; ?>

                    <?php if ($message !== "success"): ?>
                    <form method="POST" class="space-y-5">
                        <div>
                            <label class="block text-gray-600 font-bold text-xs uppercase tracking-wider mb-2">Nom complet</label>
                            <input type="text" name="nom" required placeholder="Ex: Jean Dossou" class="input-auth" value="<?php echo htmlspecialchars($_POST['nom'] ?? '', ENT_QUOTES); ?>">
                        </div>
                        <div>
                            <label class="block text-gray-600 font-bold text-xs uppercase tracking-wider mb-2">Téléphone</label>
                            <input type="tel" name="telephone" required placeholder="Ex: 0167424373" class="input-auth" value="<?php echo htmlspecialchars($_POST['telephone'] ?? '', ENT_QUOTES); ?>">
                        </div>
                        <div>
                            <label class="block text-gray-600 font-bold text-xs uppercase tracking-wider mb-2">Mot de passe</label>
                            <input type="password" name="password" required placeholder="••••••" class="input-auth">
                        </div>
                        <div>
                            <label class="block text-gray-600 font-bold text-xs uppercase tracking-wider mb-2">Confirmer le mot de passe</label>
                            <input type="password" name="password_confirm" required placeholder="••••••" class="input-auth">
                        </div>
                        <button type="submit" class="btn-auth mt-2">
                            <i class="fas fa-motorcycle mr-2"></i> Créer mon compte Livreur
                        </button>
                    </form>

                    <p class="text-center mt-8 text-gray-500 text-sm">
                        Déjà un compte ? <a href="{{ url('livreur') }}" class="text-[#27ae60] font-bold hover:underline">Se connecter</a>
                    </p>
                    <?php endif; ?>
                </div>

                <a href="{{ url('livreur') }}" class="block text-center mt-6 text-gray-500 hover:text-[#27ae60] text-sm transition-colors">
                    <i class="fas fa-arrow-left mr-1"></i> Retour à la connexion livreur
                </a>
            </div>
        </div>
    </div>
</body>
</html>
