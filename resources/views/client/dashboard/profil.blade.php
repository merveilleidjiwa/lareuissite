<?php
session_start();
require_once 'db.php';

// Sécurité : si pas connecté, retour à l'accueil
if (!isset($_SESSION['user_id'])) {
    header("Location: index.php");
    exit;
}

$user_nom = $_SESSION['user_nom'];
$initiale = strtoupper(substr($user_nom, 0, 1));

// On récupère les infos complètes depuis la base
$stmt = $pdo->prepare("SELECT email, telephone, date_inscription FROM users WHERE id = ?");
$stmt->execute([$_SESSION['user_id']]);
$user_infos = $stmt->fetch();
if (!$user_infos) {
    session_destroy();
    header("Location: connexion.php");
    exit;
}
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Mon Profil - La Réussite</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
</head>
<body class="bg-gray-50 min-h-screen font-sans">

    <header class="bg-white shadow-sm p-4">
        <div class="max-w-6xl mx-auto flex justify-between items-center">
            <a href="{{ url('/') }}" class="text-2xl font-black text-[#27ae60] uppercase">La Réussite</a>
            <a href="{{ url('logout') }}" class="text-red-500 text-sm font-bold"><i class="fas fa-sign-out-alt"></i> Quitter</a>
        </div>
    </header>

    <main class="max-w-4xl mx-auto py-12 px-4">
        <div class="bg-white p-10 rounded-[40px] shadow-lg border border-gray-100 flex flex-col md:flex-row items-center gap-10">
            
            <div class="w-32 h-32 bg-[#27ae60] text-white rounded-full flex items-center justify-center font-black text-6xl shadow-inner flex-shrink-0">
                <?php echo $initiale; ?>
            </div>

            <div class="flex-grow text-center md:text-left space-y-4">
                <p class="text-xs text-gray-400 font-bold uppercase tracking-widest">Mon Espace Personnel</p>
                <h1 class="text-4xl font-extrabold text-gray-900"><?php echo $user_nom; ?></h1>
                
                <div class="grid md:grid-cols-2 gap-4 pt-4 border-t border-gray-100 text-sm">
                    <p class="text-gray-600"><i class="fas fa-envelope text-[#27ae60] mr-2"></i> <?php echo $user_infos['email']; ?></p>
                    <p class="text-gray-600"><i class="fab fa-whatsapp text-[#27ae60] mr-2"></i> <?php echo $user_infos['telephone']; ?></p>
                    <p class="text-gray-600 col-span-2 text-xs italic">Membre depuis le : <?php echo date('d/m/Y', strtotime($user_infos['date_inscription'])); ?></p>
                </div>
            </div>
        </div>
    </main>

</body>
</html>