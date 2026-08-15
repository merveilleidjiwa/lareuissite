<?php
session_start();
require_once 'db.php';

$erreur = "";

// Créer la table livreurs si elle n'existe pas (migration auto)
try {
    $pdo->exec("CREATE TABLE IF NOT EXISTS livreurs (
        id INT AUTO_INCREMENT PRIMARY KEY,
        nom VARCHAR(255) NOT NULL,
        telephone VARCHAR(20) NOT NULL UNIQUE,
        password VARCHAR(255) NOT NULL,
        date_creation TIMESTAMP DEFAULT CURRENT_TIMESTAMP
    )");
} catch (PDOException $e) { /* Table existe déjà */ }

// --- LOGIQUE DE CONNEXION LIVREUR (téléphone + mot de passe) ---
if (isset($_POST['login_livreur'])) {
    $telephone = trim($_POST['telephone'] ?? '');
    $password = $_POST['password'] ?? '';
    $stmt = $pdo->prepare("SELECT * FROM livreurs WHERE telephone = ?");
    $stmt->execute([$telephone]);
    $livreur = $stmt->fetch();
    if ($livreur && password_verify($password, $livreur['password'])) {
        $_SESSION['livreur_auth'] = true;
        $_SESSION['livreur_nom'] = $livreur['nom'];
        $_SESSION['livreur_id'] = $livreur['id'];
    } else {
        $erreur = "Téléphone ou mot de passe incorrect.";
    }
}

// --- LOGIQUE DE DÉCONNEXION ---
if (isset($_GET['logout'])) { session_destroy(); header("Location: livreur.php"); exit; }

// --- LOGIQUE DE VALIDATION PAR LE LIVREUR (MYSQL) ---
if (isset($_POST['finaliser_livraison']) && isset($_SESSION['livreur_auth'])) {
    $id_cmd = $_POST['id_commande'];
    
    // ✅ Mise à jour du statut dans la base de données
    $sql = "UPDATE commandes SET statut = 'Livré' WHERE id = ?";
    $stmt = $pdo->prepare($sql);
    $stmt->execute([$id_cmd]);
    
    header("Location: livreur.php"); exit;
}

// --- RÉCUPÉRATION DES MISSIONS DEPUIS MYSQL ---
// On récupère les commandes qui sont "En cours de livraison"
$missions = [];
if (isset($_SESSION['livreur_auth'])) {
    $stmt = $pdo->query("SELECT * FROM commandes WHERE statut LIKE '%En cours de livraison%' ORDER BY date_commande DESC");
    $missions = $stmt->fetchAll(PDO::FETCH_ASSOC);
}
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Espace Livreur - La Réussite</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <link rel="stylesheet" href="style.css">
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Poppins:wght@400;600;700;800&display=swap');
        .auth-page { font-family: 'Poppins', sans-serif; }
        .input-auth { @apply w-full bg-gray-50 border-2 border-gray-100 rounded-2xl px-5 py-4 outline-none transition-all duration-300; }
        .input-auth:focus { @apply border-[#27ae60] ring-4 ring-[#27ae60]/20; }
    </style>
</head>
<body class="auth-page min-h-screen">

    <?php if (!isset($_SESSION['livreur_auth'])): ?>
        <div class="flex justify-center items-center min-h-screen p-4 bg-gradient-to-br from-gray-900 via-gray-800 to-[#1e3a2f]">
            <div class="w-full max-w-md">
                <div class="bg-white rounded-[32px] shadow-2xl border border-gray-100 p-10">
                    <div class="text-center mb-8">
                        <img src="logo-reussite.png" alt="" class="w-16 h-16 mx-auto mb-4 rounded-2xl object-cover border-2 border-[#27ae60]/30 shadow-lg">
                        <h2 class="text-2xl font-bold text-gray-800">Connexion Livreur</h2>
                        <p class="text-gray-500 text-sm mt-1">Accès à l'espace missions</p>
                    </div>
                    <?php if(!empty($erreur)): ?>
                        <div class="bg-red-50 border border-red-200 text-red-700 p-4 rounded-2xl mb-6 text-sm font-bold flex items-center gap-2">
                            <i class="fas fa-exclamation-circle"></i>
                            <?php echo htmlspecialchars($erreur, ENT_QUOTES, 'UTF-8'); ?>
                        </div>
                    <?php endif; ?>
                    <form method="POST" class="space-y-5">
                        <div>
                            <label class="block text-gray-600 font-bold text-xs uppercase tracking-wider mb-2">Téléphone</label>
                            <input type="tel" name="telephone" placeholder="0167424373" required class="input-auth">
                        </div>
                        <div>
                            <label class="block text-gray-600 font-bold text-xs uppercase tracking-wider mb-2">Mot de passe</label>
                            <input type="password" name="password" placeholder="••••••" required class="input-auth">
                        </div>
                        <button type="submit" name="login_livreur" class="w-full bg-[#27ae60] hover:bg-[#219150] text-white font-bold py-4 rounded-2xl uppercase tracking-widest text-sm shadow-lg shadow-[#27ae60]/30 transition-all hover:scale-[1.02] active:scale-[0.98]">
                            <i class="fas fa-sign-in-alt mr-2"></i> Entrer
                        </button>
                    </form>
                    <p class="text-center mt-8 text-gray-500 text-sm">
                        Pas de compte ? <a href="{{ url('inscription_livreur') }}" class="text-[#27ae60] font-bold hover:underline">S'inscrire</a>
                    </p>
                </div>
                <a href="{{ url('/') }}" class="block text-center mt-6 text-gray-500 hover:text-[#27ae60] text-sm transition-colors">
                    <i class="fas fa-arrow-left mr-1"></i> Retour au site
                </a>
            </div>
        </div>
    <?php else: ?>
        <div class="bg-gray-900 text-white min-h-screen p-4">
        <header class="flex justify-between items-center mb-8">
            <h1 class="font-black uppercase text-xl text-[#27ae60]">Livreur Live<?php if(!empty($_SESSION['livreur_nom'])) echo ' • ' . htmlspecialchars($_SESSION['livreur_nom']); ?></h1>
            <div class="flex items-center gap-4">
                <span class="bg-green-500/20 text-green-500 px-3 py-1 rounded-full text-[10px] font-bold animate-pulse font-mono uppercase">Connecté</span>
                <a href="?logout=1" class="text-gray-500 text-xs hover:text-red-400 transition-colors"><i class="fas fa-sign-out-alt"></i> Quitter</a>
            </div>
        </header>

        <main class="space-y-4">
            <?php if(empty($missions)): ?>
                <div class="text-center py-20 opacity-30">
                    <i class="fas fa-motorcycle text-6xl mb-4"></i>
                    <p class="uppercase font-bold text-xs tracking-widest">Aucune mission en cours</p>
                </div>
            <?php else: foreach($missions as $m): ?>
                <div class="bg-gray-800 p-6 rounded-[30px] border-l-4 border-[#27ae60] shadow-2xl animate-fade-up">
                    <div class="flex justify-between items-start mb-4">
                        <div>
                            <p class="text-[10px] text-gray-500 uppercase font-bold">Destination</p>
                            <h2 class="text-lg font-black uppercase text-white"><?php echo htmlspecialchars($m['quartier']); ?></h2>
                        </div>
                        <div class="text-right">
                            <p class="text-[10px] text-gray-400 italic"><?php echo (strpos($m['statut'], 'PAYÉ') !== false) ? '✅ Déjà payé' : 'Total à encaisser'; ?></p>
                            <p class="text-xl font-black text-[#27ae60]"><?php echo $m['total']; ?> F</p>
                        </div>
                    </div>

                    <div class="bg-black/20 p-4 rounded-2xl mb-6 text-xs text-gray-300">
                        <p class="font-bold mb-1 uppercase text-[9px] text-[#27ae60]">Détails du colis :</p>
                        <?php 
                            $details = json_decode($m['details'] ?? '[]', true) ?? [];
                            foreach($details as $item) { 
                                $nomProd = $item['product']['nom'] ?? 'Article';
                                $det = $item['details'] ?? '';
                                echo "• " . htmlspecialchars($nomProd) . " (" . htmlspecialchars($det) . ")<br>"; 
                            }
                        ?>
                    </div>

                    <form method="POST">
                        <input type="hidden" name="id_commande" value="<?php echo $m['id']; ?>">
                        <button type="submit" name="finaliser_livraison" class="w-full bg-[#27ae60] py-4 rounded-2xl font-black uppercase tracking-widest text-sm shadow-lg active:scale-95 transition-all">
                            <i class="fas fa-check-circle mr-2"></i> Terminer la livraison
                        </button>
                    </form>
                </div>
            <?php endforeach; endif; ?>
        </main>
        </div>
    <?php endif; ?>
</body>
</html>