<?php
if (!isset($mes_produits)) $mes_produits = [];
?>
<?php
if (!isset($vendeur_nom)) $vendeur_nom = 'Ma Boutique';
if (!isset($message_v)) $message_v = '';
if (!isset($produits)) $produits = [];
?>
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
    <title>Espace Vendeur - <?php echo $vendeur_nom; ?></title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
</head>
<body class="bg-[#f4fbf7] min-h-screen">

    <header class="bg-white shadow-sm p-4 sticky top-0 z-50">
        <div class="max-w-6xl mx-auto flex justify-between items-center">
            <div class="flex items-center gap-3">
                <div class="w-10 h-10 bg-[#27ae60] rounded-full flex items-center justify-center text-white font-bold">
                    <?php echo substr($vendeur_nom, 0, 1); ?>
                </div>
                <span class="font-black text-[#27ae60] uppercase tracking-tighter">La Réussite <span class="text-gray-300 font-normal">| Vendeur</span></span>
            </div>
            <nav class="flex gap-4 items-center">
                <span class="text-xs font-bold text-gray-400 uppercase hidden md:block">Boutique : <?php echo $vendeur_nom; ?></span>
                <a href="{{ url('logout') }}" class="bg-red-50 text-red-500 px-4 py-2 rounded-xl text-xs font-bold">Quitter</a>
            </nav>
        </div>
    </header>

    <main class="max-w-6xl mx-auto py-10 px-4">
        <div class="grid lg:grid-cols-3 gap-10">
            
            <div class="lg:col-span-1">
                <div class="bg-white p-8 rounded-[40px] shadow-xl border-b-8 border-[#27ae60]">
                    <h2 class="text-xl font-black mb-6 uppercase text-gray-800 italic">Ajouter un article</h2>
                    
                    <?php if(isset($message_v)): ?>
                        <div class="bg-green-100 text-green-700 p-4 rounded-2xl mb-4 text-xs font-bold"><?php echo $message_v; ?></div>
                    <?php endif; ?>

                    <form method="POST" enctype="multipart/form-data" class="space-y-4 text-xs font-bold">
                        <div>
                            <label class="block text-gray-400 mb-1 ml-2 uppercase text-[9px]">Nom du produit</label>
                            <input type="text" name="nom" required class="w-full p-4 bg-gray-50 border-none rounded-2xl outline-none focus:ring-2 ring-[#27ae60]">
                        </div>
                        
                        <div>
                            <label class="block text-gray-400 mb-1 ml-2 uppercase text-[9px]">Catégorie</label>
                            <select name="categorie" required class="w-full p-4 bg-gray-50 border-none rounded-2xl outline-none focus:ring-2 ring-[#27ae60]">
                                <option value="Fruits et Légumes">Fruits et Légumes</option>
                                <option value="Viandes et Poissons">Viandes et Poissons</option>
                                <option value="Jus Naturels et Bio">Jus Naturels et Bio</option>
                            </select>
                        </div>

                        <div>
                            <label class="block text-gray-400 mb-1 ml-2 uppercase text-[9px]">Prix de vente (FCFA)</label>
                            <input type="number" name="prix_standard" required class="w-full p-4 bg-gray-50 border-none rounded-2xl outline-none focus:ring-2 ring-[#27ae60]">
                        </div>

                        <div>
                            <label class="block text-gray-400 mb-1 ml-2 uppercase text-[9px]">Description</label>
                            <textarea name="description" class="w-full p-4 bg-gray-50 border-none rounded-2xl outline-none focus:ring-2 ring-[#27ae60] h-24"></textarea>
                        </div>

                        <div>
                            <label class="block text-gray-400 mb-1 ml-2 uppercase text-[9px]">Photo du produit</label>
                            <input type="file" name="image" required class="text-[10px]">
                        </div>

                        <button type="submit" name="ajouter_produit" class="w-full bg-[#27ae60] text-white py-4 rounded-2xl font-black uppercase tracking-widest shadow-lg">Soumettre à l'Admin</button>
                    </form>
                </div>
            </div>

            <div class="lg:col-span-2">
                <h2 class="text-xl font-black mb-6 uppercase text-gray-400 tracking-widest">Mes Articles en stock</h2>
                
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <?php if(empty($mes_produits)): ?>
                        <div class="col-span-2 text-center py-20 opacity-20 italic">
                            <i class="fas fa-box-open text-6xl mb-4"></i>
                            <p>Vous n'avez pas encore de produits.</p>
                        </div>
                    <?php else: foreach($mes_produits as $p): ?>
                        <div class="bg-white p-4 rounded-3xl flex items-center gap-4 shadow-sm border border-gray-100">
                            <img src="<?php echo $p['image']; ?>" class="w-20 h-20 rounded-2xl object-cover shadow-inner">
                            <div class="flex-1">
                                <h4 class="font-bold text-sm text-gray-800"><?php echo $p['nom']; ?></h4>
                                <p class="text-[#27ae60] font-black text-xs"><?php echo $p['prix']; ?> FCFA</p>
                                <span class="text-[9px] px-2 py-1 rounded-lg font-bold uppercase mt-2 inline-block <?php echo ($p['statut'] === 'actif') ? 'bg-green-100 text-green-600' : 'bg-orange-100 text-orange-500'; ?>">
                                    <?php echo ($p['statut'] === 'actif') ? 'En ligne' : 'En attente'; ?>
                                </span>
                            </div>
                        </div>
                    <?php endforeach; endif; ?>
                </div>
            </div>

        </div>
    </main>

</body>
</html>