<?php
require_once 'db.php'; 

$message = "";

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $prenom = htmlspecialchars(trim($_POST['prenom'] ?? ''));
    $nom = htmlspecialchars(trim($_POST['nom'] ?? ''));
    $date_naissance = !empty($_POST['date_naissance']) ? $_POST['date_naissance'] : null;
    $email = htmlspecialchars(trim($_POST['email'] ?? ''));
    $telephone = trim($_POST['telephone'] ?? '');
    $role = in_array($_POST['role'] ?? '', ['client', 'vendeur']) ? $_POST['role'] : 'client';
    $password = $_POST['password'] ?? '';
    $password_confirm = $_POST['password_confirm'] ?? '';

    if (empty($prenom) || empty($nom)) {
        $message = "Le prénom et le nom sont obligatoires.";
    } elseif (empty($email) || empty($telephone)) {
        $message = "L'email et le téléphone sont obligatoires.";
    } elseif (strlen($password) < 4) {
        $message = "Le mot de passe doit contenir au moins 4 caractères.";
    } elseif ($password !== $password_confirm) {
        $message = "Les mots de passe ne correspondent pas.";
    } else {
        $password_hash = password_hash($password, PASSWORD_DEFAULT);
        try {
            $cols = $pdo->query("SHOW COLUMNS FROM users")->fetchAll(PDO::FETCH_COLUMN);
            if (in_array('prenom', $cols) && in_array('date_naissance', $cols)) {
                $stmt = $pdo->prepare("INSERT INTO users (prenom, nom, email, telephone, date_naissance, mot_de_passe, role) VALUES (?, ?, ?, ?, ?, ?, ?)");
                $stmt->execute([$prenom, $nom, $email, $telephone, $date_naissance ?: null, $password_hash, $role]);
            } else {
                $stmt = $pdo->prepare("INSERT INTO users (nom, email, telephone, mot_de_passe, role) VALUES (?, ?, ?, ?, ?)");
                $stmt->execute([trim($prenom . ' ' . $nom), $email, $telephone, $password_hash, $role]);
            }
            $message = "success";
        } catch (PDOException $e) {
            error_log("Inscription: " . $e->getMessage());
            if ($e->getCode() == 23000) { 
                $message = "L'email est déjà utilisé par un autre compte.";
            } else {
                $message = "Une erreur est survenue. Vérifiez vos informations ou réessayez.";
            }
        }
    }
}
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>S'inscrire - La Réussite</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script>tailwind.config = { theme: { extend: { colors: { brand: { green: '#27ae60', light: '#f4fbf7', orange: '#F9A825' } } } } }</script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <link rel="stylesheet" href="style.css">
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Poppins:wght@400;600;700;800&display=swap');
        .auth-page { font-family: 'Poppins', sans-serif; }
        .input-auth { @apply w-full bg-white border-2 border-gray-200 rounded-2xl px-5 py-4 outline-none transition-all duration-300 text-gray-800; }
        .input-auth:focus { @apply border-[#27ae60] ring-4 ring-[#27ae60]/20; }
        .input-auth::placeholder { @apply text-gray-400; }
        .btn-auth { @apply w-full bg-[#27ae60] hover:bg-[#219150] text-white font-bold py-4 rounded-2xl uppercase tracking-widest text-sm shadow-lg shadow-[#27ae60]/30 transition-all duration-300 hover:scale-[1.02] active:scale-[0.98]; }
        .role-btn { @apply flex-1 py-4 px-4 rounded-2xl border-2 font-bold text-sm uppercase transition-all cursor-pointer; }
        .role-btn:not(.selected) { @apply border-gray-200 bg-gray-50 text-gray-600 hover:border-gray-300; }
        .role-btn.selected { @apply border-[#27ae60] bg-[#27ae60]/10 text-[#27ae60]; }
        body.prevent-flash { opacity: 0; }
        body.loaded { opacity: 1; transition: opacity 0.3s ease; }
    </style>
</head>
<body class="auth-page min-h-screen flex bg-gradient-to-br from-[#f4fbf7] via-white to-[#e8f5ee] prevent-flash" id="body-el">

    <div id="page-loader" class="fixed inset-0 bg-[#f4fbf7] z-[99999] flex items-center justify-center">
        <img src="logo-reussite.png" alt="" class="w-20 h-20 object-contain animate-loader" onerror="this.style.display='none'">
    </div>

    <div class="flex-1 flex flex-col lg:flex-row min-h-screen w-full">
        <!-- Côté gauche : visuel / branding (responsive) -->
        <div class="flex lg:w-1/2 bg-gradient-to-br from-[#27ae60] to-[#1e8449] p-8 lg:p-16 flex-col justify-between relative overflow-hidden min-h-[200px] lg:min-h-screen">
            <div class="absolute inset-0 opacity-10">
                <div class="absolute top-20 left-20 w-64 h-64 rounded-full bg-white"></div>
                <div class="absolute bottom-32 right-20 w-48 h-48 rounded-full bg-white"></div>
            </div>
            <a href="{{ url('/') }}" class="relative flex items-center gap-3">
                <img src="logo-reussite.png" class="w-12 h-12 lg:w-14 lg:h-14 object-cover rounded-full border-2 border-white/40 shadow-xl" alt="La Réussite">
                <span class="font-brand text-2xl lg:text-3xl text-white drop-shadow-sm">La Réussite</span>
            </a>
            <div class="relative space-y-4 lg:space-y-6 mt-6 lg:mt-0">
                <h2 class="text-2xl lg:text-4xl font-bold text-white leading-tight">Rejoignez la communauté<br><span class="text-[#F9A825]">locale & fraîche</span></h2>
                <p class="text-white/90 text-sm lg:text-lg max-w-sm">Produits du terroir, livraison rapide.</p>
                <div class="flex gap-4 text-white/80 text-xs lg:text-base">
                    <span><i class="fas fa-truck text-[#F9A825] mr-2"></i> Livraison rapide</span>
                    <span><i class="fas fa-leaf text-[#F9A825] mr-2"></i> Produits frais</span>
                </div>
            </div>
        </div>

        <!-- Côté droit : formulaire -->
        <div class="flex-1 flex items-center justify-center p-6 lg:p-12 overflow-y-auto">
            <div class="w-full max-w-md">
                <div class="bg-white rounded-[32px] shadow-2xl shadow-gray-200/50 border border-gray-200 p-8 lg:p-10">
                    <h1 class="text-2xl font-bold text-gray-800 mb-2">Créer un compte</h1>
                    <p class="text-gray-500 text-sm mb-8">Remplissez le formulaire pour rejoindre La Réussite</p>

                    <?php if ($message === "success"): ?>
                        <div class="bg-green-50 border border-green-200 text-green-800 p-5 rounded-2xl mb-6 text-center animate-fade-up">
                            <i class="fas fa-check-circle text-3xl mb-2 text-[#27ae60]"></i>
                            <p class="font-bold mb-3">Inscription réussie !</p>
                            <a href="{{ url('connexion') }}" class="inline-block bg-[#27ae60] text-white px-6 py-2 rounded-xl font-bold text-sm hover:bg-[#219150] transition-colors">
                                Se connecter maintenant
                            </a>
                        </div>
                    <?php elseif ($message !== ""): ?>
                        <div class="bg-red-50 border border-red-200 text-red-700 p-4 rounded-2xl mb-6 text-sm font-bold flex items-center gap-2">
                            <i class="fas fa-exclamation-circle flex-shrink-0"></i>
                            <?php echo htmlspecialchars($message, ENT_QUOTES, 'UTF-8'); ?>
                        </div>
                    <?php endif; ?>

                    <form method="POST" class="space-y-5" id="inscription-form">
                        <div class="grid grid-cols-2 gap-4">
                            <div>
                                <label class="block text-gray-700 font-bold text-xs uppercase tracking-wider mb-2">Prénom</label>
                                <input type="text" name="prenom" required placeholder="Merveille" class="input-auth" value="<?php echo htmlspecialchars($_POST['prenom'] ?? '', ENT_QUOTES); ?>">
                            </div>
                            <div>
                                <label class="block text-gray-700 font-bold text-xs uppercase tracking-wider mb-2">Nom</label>
                                <input type="text" name="nom" required placeholder="Kouassi" class="input-auth" value="<?php echo htmlspecialchars($_POST['nom'] ?? '', ENT_QUOTES); ?>">
                            </div>
                        </div>
                        <div>
                            <label class="block text-gray-700 font-bold text-xs uppercase tracking-wider mb-2">Date de naissance</label>
                            <input type="date" name="date_naissance" class="input-auth" value="<?php echo htmlspecialchars($_POST['date_naissance'] ?? '', ENT_QUOTES); ?>">
                        </div>
                        <div>
                            <label class="block text-gray-700 font-bold text-xs uppercase tracking-wider mb-2">E-mail</label>
                            <input type="email" name="email" required placeholder="votre@email.com" class="input-auth" value="<?php echo htmlspecialchars($_POST['email'] ?? '', ENT_QUOTES); ?>">
                        </div>
                        <div>
                            <label class="block text-gray-700 font-bold text-xs uppercase tracking-wider mb-2">Téléphone (WhatsApp)</label>
                            <input type="tel" name="telephone" required placeholder="Ex: 0167424373" class="input-auth" value="<?php echo htmlspecialchars($_POST['telephone'] ?? '', ENT_QUOTES); ?>">
                        </div>
                        <div>
                            <label class="block text-gray-700 font-bold text-xs uppercase tracking-wider mb-2">Mot de passe</label>
                            <input type="password" name="password" required placeholder="••••••••" class="input-auth">
                        </div>
                        <div>
                            <label class="block text-gray-700 font-bold text-xs uppercase tracking-wider mb-2">Confirmation mot de passe</label>
                            <input type="password" name="password_confirm" required placeholder="••••••••" class="input-auth">
                        </div>
                        <div>
                            <label class="block text-gray-700 font-bold text-xs uppercase tracking-wider mb-3">Je suis un...</label>
                            <div class="flex gap-3">
                                <label class="role-btn selected" id="role-acheteur">
                                    <input type="radio" name="role" value="client" class="hidden" checked>
                                    <i class="fas fa-shopping-cart mr-2"></i> Acheteur
                                </label>
                                <label class="role-btn" id="role-vendeur">
                                    <input type="radio" name="role" value="vendeur" class="hidden">
                                    <i class="fas fa-store mr-2"></i> Vendeur
                                </label>
                            </div>
                        </div>
                        <button type="submit" class="btn-auth mt-2">
                            <i class="fas fa-user-plus mr-2"></i> S'inscrire
                        </button>
                    </form>

                    <p class="text-center mt-8 text-gray-500 text-sm">
                        Déjà un compte ? <a href="{{ url('connexion') }}" class="text-[#27ae60] font-bold hover:underline">Se connecter</a>
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
            if (loader) { loader.style.display = 'none'; }
            if (body) { body.classList.remove('prevent-flash'); body.classList.add('loaded'); }
            document.querySelectorAll('.role-btn').forEach(btn => {
                btn.addEventListener('click', function() {
                    document.querySelectorAll('.role-btn').forEach(b => b.classList.remove('selected'));
                    this.classList.add('selected');
                    this.querySelector('input').checked = true;
                });
            });
        });
    </script>
</body>
</html>
