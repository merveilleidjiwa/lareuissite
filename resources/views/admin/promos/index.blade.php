<?php
$erreur = '';
$message = '';
$success = '';
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Promotions - La Réussite</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
</head>
<body class="bg-gray-50 p-6">
    <a href="{{ url('admin') }}" class="text-orange-600 font-bold mb-6 block"><i class="fas fa-arrow-left"></i> RETOUR ACCUEIL</a>
    
    <div class="grid lg:grid-cols-2 gap-8">
        <div class="bg-white p-8 rounded-[30px] shadow-lg">
            <h2 class="text-2xl font-bold mb-6 text-orange-500 uppercase">Lancer une Promo</h2>
            <form method="POST" class="space-y-4">
                <div class="grid grid-cols-2 gap-4">
                    <input type="text" name="nom_promo" placeholder="Nom de la Promo" required class="p-3 border rounded-xl outline-none focus:border-orange-500">
                    <input type="number" name="pourcentage" placeholder="-%" required class="p-3 border rounded-xl outline-none focus:border-orange-500">
                </div>
                
                <p class="text-xs font-bold text-gray-400 uppercase ml-1">Sélectionner les produits :</p>
                <div class="max-h-[250px] overflow-y-auto border p-4 rounded-xl bg-gray-50">
                    <?php 
                    $compteur = 0;
                    foreach ($produits as $p): 
                        if($p['en_promo'] == 0): 
                            $compteur++;
                    ?>
                    <label class="flex items-center gap-3 mb-3 text-sm cursor-pointer hover:bg-white p-2 rounded-lg transition-all">
                        <input type="checkbox" name="produits_promo[]" value="<?php echo $p['id']; ?>" class="w-4 h-4 accent-orange-500"> 
                        <span class="font-medium text-gray-700"><?php echo htmlspecialchars($p['nom']); ?></span>
                    </label>
                    <?php endif; endforeach; 
                    if($compteur == 0) echo "<p class='text-center text-gray-400 text-xs italic py-4'>Tous les produits sont déjà en promo.</p>";
                    ?>
                </div>
                <button type="submit" name="creer_promo" class="w-full bg-orange-500 text-white font-bold py-4 rounded-full shadow-lg hover:bg-orange-600 transition-all">ACTIVER LA PROMOTION</button>
            </form>
        </div>

        <div class="bg-white p-8 rounded-[30px] shadow-lg">
            <h2 class="text-2xl font-bold mb-6 text-red-500 uppercase">Promos Actives</h2>
            <div class="space-y-4">
                <?php 
                // On regroupe les produits par nom de promo pour l'affichage
                $promos_actives = [];
                foreach($produits as $p) {
                    if($p['en_promo'] == 1 && !empty($p['nom_promo'])) {
                        $promos_actives[$p['nom_promo']] = $p['pourcentage_promo'];
                    }
                }

                if(empty($promos_actives)): ?>
                    <p class="text-center text-gray-400 py-10 italic">Aucune promotion active pour le moment.</p>
                <?php else: 
                    foreach($promos_actives as $nom => $pct): ?>
                    <div class="flex justify-between items-center p-5 bg-red-50 rounded-2xl border-l-8 border-red-500 shadow-sm">
                        <div>
                            <p class="font-black text-red-600 uppercase text-sm"><?php echo htmlspecialchars($nom); ?></p>
                            <p class="text-xs font-bold text-gray-500">Réduction de -<?php echo $pct; ?>%</p>
                        </div>
                        <form method="POST" onsubmit="return confirm('Arrêter cette promotion ?');">
                            <input type="hidden" name="nom_a_supprimer" value="<?php echo htmlspecialchars($nom); ?>">
                            <button type="submit" name="supprimer_promo" class="bg-white text-red-500 px-4 py-2 rounded-xl text-[10px] font-black uppercase shadow-sm hover:bg-red-500 hover:text-white transition-all">
                                <i class="fas fa-stop-circle mr-1"></i> Arrêter
                            </button>
                        </form>
                    </div>
                <?php endforeach; endif; ?>
            </div>
        </div>
    </div>
</body>
</html>