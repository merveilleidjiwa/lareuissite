<?php
if (!isset($is_connected)) $is_connected = false;
if (!isset($user_nom)) $user_nom = '';
if (!isset($initiale)) $initiale = '';
if (!isset($erreur)) $erreur = '';
if (!isset($message)) $message = '';
if (!isset($success)) $success = '';
if (!isset($cmds)) $cmds = [];
if (!isset($produits)) $produits = [];
if (!isset($attente)) $attente = 0;
if (!isset($erreur_login)) $erreur_login = '';
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
    <title>Dashboard Admin - La Réussite</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
</head>
<body class="bg-[#f4fbf7] min-h-screen">

    <?php if (false): ?>
        <div class="flex items-center justify-center min-h-screen p-4 bg-gradient-to-br from-[#f4fbf7] via-white to-[#e8f5ee]">
            <div class="w-full max-w-md">
                <div class="bg-white rounded-[32px] shadow-2xl shadow-gray-200/50 border border-gray-100 p-10">
                    <div class="text-center mb-8">
                        <img src="/logo-reussite.png" alt="" class="w-16 h-16 mx-auto mb-4 rounded-2xl object-cover border-2 border-[#27ae60]/30 shadow-lg">
                        <h2 class="text-2xl font-bold text-gray-800">Connexion Admin</h2>
                        <p class="text-gray-500 text-sm mt-1">Accès au tableau de bord</p>
                    </div>
                    <?php if(!empty($erreur_login)): ?>
                        <div class="bg-red-50 border border-red-200 text-red-700 p-4 rounded-2xl mb-6 text-sm font-bold flex items-center gap-2">
                            <i class="fas fa-exclamation-circle"></i>
                            <?php echo htmlspecialchars($erreur_login, ENT_QUOTES, 'UTF-8'); ?>
                        </div>
                    <?php endif; ?>
                    <form method="POST" class="space-y-5">
                        <div>
                            <label class="block text-gray-600 font-bold text-xs uppercase tracking-wider mb-2">Téléphone</label>
                            <input type="text" name="telephone" placeholder="0167424373" required class="w-full bg-gray-50 border-2 border-gray-100 rounded-2xl px-5 py-4 outline-none focus:border-[#27ae60] focus:ring-4 focus:ring-[#27ae60]/20 transition-all">
                        </div>
                        <div>
                            <label class="block text-gray-600 font-bold text-xs uppercase tracking-wider mb-2">Mot de passe</label>
                            <input type="password" name="password" placeholder="••••••" required class="w-full bg-gray-50 border-2 border-gray-100 rounded-2xl px-5 py-4 outline-none focus:border-[#27ae60] focus:ring-4 focus:ring-[#27ae60]/20 transition-all">
                        </div>
                        <button type="submit" name="login_admin" class="w-full bg-[#27ae60] hover:bg-[#219150] text-white font-bold py-4 rounded-2xl uppercase tracking-widest text-sm shadow-lg shadow-[#27ae60]/30 transition-all hover:scale-[1.02] active:scale-[0.98]">
                            <i class="fas fa-sign-in-alt mr-2"></i> Entrer
                        </button>
                    </form>
                    <p class="text-center mt-8 text-gray-500 text-sm">
                        Pas de compte admin ? <a href="{{ url('admin/ajouter-admin') }}" class="text-[#27ae60] font-bold hover:underline">S'inscrire</a>
                    </p>
                </div>
                <a href="{{ url('/') }}" class="block text-center mt-6 text-gray-400 hover:text-[#27ae60] text-sm transition-colors">
                    <i class="fas fa-arrow-left mr-1"></i> Retour au site
                </a>
            </div>
        </div>
    <?php else: ?>

        <header class="bg-white shadow-sm p-4 sticky top-0 z-50">
            <div class="max-w-[1200px] mx-auto flex justify-between items-center">
                <div class="flex items-center gap-3">
                    <img src="/logo-reussite.png" alt="" class="w-10 h-10 rounded-full object-cover border-2 border-[#27ae60]/30">
                    <span class="font-black uppercase tracking-tighter text-sm">La Réussite <span class="text-[#27ae60]">Admin</span></span>
                </div>
                <a href="?logout=1" class="text-red-500 font-bold text-[10px] uppercase border border-red-100 px-3 py-1 rounded-full hover:bg-red-50 transition-all"><i class="fas fa-power-off mr-1"></i> Quitter</a>
            </div>
        </header>

        <main class="max-w-[1200px] mx-auto p-6 lg:p-10">
            
            <div class="grid lg:grid-cols-2 gap-8 mb-12">
                
                <div class="bg-white p-6 rounded-[35px] shadow-lg border-t-4 border-orange-500">
                    <div class="flex justify-between items-center mb-6">
                        <h2 class="font-black text-orange-600 uppercase text-xs tracking-widest"><i class="fas fa-truck-loading mr-2"></i> Commandes Live</h2>
                        <span class="bg-orange-100 text-orange-600 text-[9px] px-3 py-1 rounded-full font-bold animate-pulse">TEMPS RÉEL</span>
                    </div>
                    
                    <div class="space-y-4 max-h-[400px] overflow-y-auto pr-2">
                        <?php if(empty($cmds)): echo "<p class='text-gray-400 text-xs italic text-center py-10'>Aucune commande pour le moment.</p>";
                        else: foreach($cmds as $c): ?>
                            <div class="p-4 bg-gray-50 rounded-[25px] border border-gray-100 shadow-sm transition-all hover:bg-white hover:shadow-md">
                                <div class="flex justify-between items-start mb-3">
                                    <div>
                                        <p class="font-black text-xs uppercase"><?php echo htmlspecialchars($c['quartier']); ?></p>
                                        <p class="text-[9px] text-gray-400 font-bold"><?php echo $c['date_commande']; ?></p>
                                        <p class="text-[8px] text-blue-500 font-bold mt-1 uppercase">
                                            <?php 
                                            $details = json_decode($c['details'] ?? '[]', true) ?: [];
                                            foreach($details as $item) { 
                                                echo htmlspecialchars($item['product']['nom'] ?? 'Article', ENT_QUOTES, 'UTF-8') . " (" . (int)($item['quantity'] ?? 0) . "), "; 
                                            }
                                            ?>
                                        </p>
                                    </div>
                                    <span class="text-[10px] font-black text-[#27ae60]"><?php echo $c['total']; ?> FCFA</span>
                                </div>
                                
                                <div class="flex justify-between items-center mt-4">
                                    <span class="text-[9px] font-bold px-3 py-1 rounded-full bg-orange-100 text-orange-600 uppercase italic">
                                        <?php echo htmlspecialchars($c['statut'] ?? '', ENT_QUOTES, 'UTF-8'); ?>
                                    </span>
                                    
                                    <form method="POST" class="flex gap-2">
                                        <input type="hidden" name="id_commande" value="<?php echo $c['id']; ?>">
                                        
                                        <?php if($c['statut'] == 'En attente' || $c['statut'] == 'PAYÉ - En attente'): ?>
                                            <button type="submit" name="changer_statut" value="En cours de livraison" class="bg-blue-600 text-white px-4 py-2 rounded-xl text-[9px] font-bold uppercase shadow-sm">
                                                <i class="fas fa-motorcycle mr-1"></i> Envoyer au Livreur
                                            </button>

                                        <?php elseif($c['statut'] == 'En cours de livraison'): ?>
                                            <div class="bg-gray-100 text-gray-400 px-4 py-2 rounded-xl text-[9px] font-bold uppercase border border-gray-200 cursor-not-allowed">
                                                <i class="fas fa-clock mr-1"></i> En main propre du livreur...
                                            </div>

                                        <?php elseif($c['statut'] == 'Livré'): ?>
                                            <span class="text-[9px] font-bold text-[#27ae60] uppercase">
                                                <i class="fas fa-check-double mr-1"></i> Commande Terminée
                                            </span>
                                        <?php endif; ?>
                                    </form>
                                </div>
                            </div>
                        <?php endforeach; endif; ?>
                    </div>
                </div>

                <div class="bg-white p-6 rounded-[35px] shadow-lg border-t-4 border-blue-500">
                    <h2 class="font-black text-blue-600 uppercase text-xs tracking-widest mb-6"><i class="fas fa-user-check mr-2"></i> Produits Vendeurs</h2>
                    <div class="space-y-4 max-h-[400px] overflow-y-auto pr-2">
                        <?php 
                        $attente = array_filter($produits, function($p) { return $p['statut'] === 'en_attente'; });
                        if(empty($attente)): echo "<p class='text-gray-400 text-xs italic text-center py-10'>Rien à valider pour l'instant.</p>";
                        else: foreach($attente as $p): ?>
                            <div class="flex justify-between items-center p-4 bg-blue-50 rounded-[25px] border border-blue-100 shadow-sm">
                                <div class="flex items-center gap-4">
                                    <img src="<?php echo $p['image']; ?>" class="w-10 h-10 rounded-xl object-cover shadow-sm border border-white">
                                    <div>
                                        <p class="font-black text-[11px] uppercase tracking-tighter"><?php echo htmlspecialchars($p['nom']); ?></p>
                                        <p class="text-[9px] text-blue-400 font-bold uppercase">Prix: <?php echo $p['prix']; ?> FCFA</p>
                                    </div>
                                </div>
                                <form method="POST">
                                    <input type="hidden" name="id_v" value="<?php echo $p['id']; ?>">
                                    <button type="submit" name="valider_produit" class="bg-blue-600 text-white px-4 py-2 rounded-full text-[9px] font-bold uppercase shadow-md hover:scale-105 transition-transform">Valider</button>
                                </form>
                            </div>
                        <?php endforeach; endif; ?>
                    </div>
                </div>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
                <a href="{{ url('admin/produits') }}" class="group bg-white p-10 rounded-[40px] shadow-lg hover:shadow-2xl transition-all border-b-[10px] border-green-500 text-center">
                    <div class="w-20 h-20 bg-green-50 text-green-500 rounded-full flex items-center justify-center mx-auto mb-6 group-hover:scale-110 group-hover:bg-green-500 group-hover:text-white transition-all">
                        <i class="fas fa-boxes text-3xl"></i>
                    </div>
                    <h3 class="font-black text-gray-800 uppercase text-sm tracking-widest">Ajouter Produits</h3>
                </a>
                <a href="{{ url('admin/promos') }}" class="group bg-white p-10 rounded-[40px] shadow-lg hover:shadow-2xl transition-all border-b-[10px] border-red-500 text-center">
                    <div class="w-20 h-20 bg-red-50 text-red-500 rounded-full flex items-center justify-center mx-auto mb-6 group-hover:scale-110 group-hover:bg-red-500 group-hover:text-white transition-all">
                        <i class="fas fa-fire text-3xl"></i>
                    </div>
                    <h3 class="font-black text-gray-800 uppercase text-sm tracking-widest">Lancer Promo</h3>
                </a>
                <a href="{{ url('admin/stats') }}" class="group bg-white p-10 rounded-[40px] shadow-lg hover:shadow-2xl transition-all border-b-[10px] border-blue-500 text-center">
                    <div class="w-20 h-20 bg-blue-50 text-blue-500 rounded-full flex items-center justify-center mx-auto mb-6 group-hover:scale-110 group-hover:bg-blue-500 group-hover:text-white transition-all">
                        <i class="fas fa-chart-pie text-3xl"></i>
                    </div>
                    <h3 class="font-black text-gray-800 uppercase text-sm tracking-widest">Statistiques</h3>
                </a>
            </div>

        </main>
    <?php endif; ?>

    <audio id="audio-notif" src="https://www.soundjay.com/buttons/sounds/button-3.mp3" preload="auto"></audio>
    
    <script>
        // Système de notification temps réel via MySQL
        let lastCount = <?php echo count($cmds); ?>;
        setInterval(() => {
            fetch('get_order_count.php') // Petit script à créer pour compter les commandes
                .then(r => r.text())
                .then(count => {
                    if (parseInt(count) > lastCount) {
                        document.getElementById('audio-notif').play();
                        setTimeout(() => location.reload(), 1500);
                    }
                });
        }, 10000);
    </script>
</body>
</html>