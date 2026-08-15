<?php
session_start();
if (!isset($_SESSION['admin_connecte'])) { header("Location: admin.php"); exit; }

require_once 'db.php'; // On appelle ton pont MySQL
$dossier_images = 'uploads/';

// On récupère les produits depuis la BASE DE DONNÉES au lieu du JSON
$stmt = $pdo->query("SELECT * FROM produits");
$produits = $stmt->fetchAll();

// AJOUTER UN PRODUIT
if (isset($_POST['ajouter_produit'])) {
    $nom = $_POST['nom'];
    $description = $_POST['description'];
    $categorie = $_POST['categorie'];
    
    // Initialisation par défaut pour éviter le 0.00
    $sous_categorie = NULL;
    $tarifs_json = NULL;
    $prix_final = (int)$_POST['prix_standard']; 

    // Logique spécifique si c'est de la viande
    if ($categorie === 'Viandes et Poissons') {
        $sous_categorie = $_POST['sous_categorie'];
        if ($sous_categorie === 'viande') {
            $data_tarifs = [
                'male' => (int)$_POST['prix_male'],
                'femelle' => (int)$_POST['prix_femelle'],
                'abattu' => (int)$_POST['prix_abattu']
            ];
            $tarifs_json = json_encode($data_tarifs);
            $prix_final = (int)$_POST['prix_abattu']; // On prend le prix abattu comme base
        }
    }

    // Gestion de l'image (pour éviter le NULL)
    $image_path = NULL;
    if (isset($_FILES['image']) && $_FILES['image']['error'] === UPLOAD_ERR_OK) {
        $nom_image = time() . '_' . basename($_FILES['image']['name']);
        if (move_uploaded_file($_FILES['image']['tmp_name'], 'uploads/' . $nom_image)) {
            $image_path = 'uploads/' . $nom_image;
        }
    }

    // Requête SQL pour insérer le produit dans la BASE DE DONNÉES
    $sql = "INSERT INTO produits (nom, description, prix, image, categorie, tarifs, sous_categorie, statut) 
            VALUES (?, ?, ?, ?, ?, ?, ?, 'actif')";
    $stmt = $pdo->prepare($sql);
    $stmt->execute([$nom, $description, $prix_final, $image_path, $categorie, $tarifs_json, $sous_categorie]);
    
    header("Location: admin_produits.php"); exit;
}

// SUPPRIMER UN PRODUIT
if (isset($_POST['supprimer_produit'])) {
    $id_a_supprimer = (int)$_POST['id_produit'];
    
    // 1. On cherche le chemin de l'image pour la supprimer du dossier
    $stmt = $pdo->prepare("SELECT image FROM produits WHERE id = ?");
    $stmt->execute([$id_a_supprimer]);
    $p = $stmt->fetch();
    if ($p && file_exists($p['image'])) { unlink($p['image']); }

    // 2. On supprime la ligne dans MySQL
    $stmt = $pdo->prepare("DELETE FROM produits WHERE id = ?");
    $stmt->execute([$id_a_supprimer]);

    header("Location: admin_produits.php"); exit;
}
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Gestion Stocks - La Réussite</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <script>
        function updateFormulairePrix() {
            const cat = document.getElementById('select-cat').value;
            const sousCatBox = document.getElementById('box-sous-cat');
            const prixStandardBox = document.getElementById('box-prix-standard');
            const prixViandeBox = document.getElementById('box-prix-viande');
            const sousCat = document.getElementById('select-sous-cat').value;

            if (cat === 'Viandes et Poissons') {
                sousCatBox.classList.remove('hidden');
                if(sousCat === 'viande') {
                    prixStandardBox.classList.add('hidden');
                    prixViandeBox.classList.remove('hidden');
                } else {
                    prixStandardBox.classList.remove('hidden');
                    prixViandeBox.classList.add('hidden');
                }
            } else {
                sousCatBox.classList.add('hidden');
                prixStandardBox.classList.remove('hidden');
                prixViandeBox.classList.add('hidden');
            }
        }
    </script>
</head>
<body class="bg-gray-50 p-6">
    <a href="admin.php" class="text-green-600 font-bold mb-6 block"><i class="fas fa-arrow-left"></i> RETOUR ACCUEIL</a>
    
    <div class="grid lg:grid-cols-2 gap-8">
        <div class="bg-white p-8 rounded-[30px] shadow-lg">
            <?php
// Logique pour sauvegarder le prix du KM
if (isset($_POST['maj_tarif_km'])) {
    file_put_contents('tarif_livraison.json', json_encode(['prix_km' => (int)$_POST['prix_km']]));
    $message_km = "Tarif mis à jour !";
}
$tarif_data = json_decode(file_get_contents('tarif_livraison.json'), true) ?: ['prix_km' => 100];
?>

<div class="bg-white p-6 rounded-[30px] shadow-lg border-l-8 border-blue-500 mb-8">
    <h3 class="font-bold uppercase text-xs text-blue-500 mb-4">Configuration Livraison</h3>
    <form method="POST" class="flex items-center gap-4">
        <div class="flex-1">
            <label class="text-[10px] font-bold text-gray-400">PRIX PAR KILOMÈTRE (FCFA)</label>
            <input type="number" name="prix_km" value="<?php echo $tarif_data['prix_km']; ?>" class="w-full p-2 border rounded-xl font-black">
        </div>
        <button type="submit" name="maj_tarif_km" class="bg-blue-600 text-white px-6 py-2 rounded-xl text-xs font-bold mt-4">VALIDER</button>
    </form>
    <?php if(!empty($message_km)) echo "<p class='text-[10px] text-green-600 mt-2 font-bold'>" . htmlspecialchars($message_km, ENT_QUOTES, 'UTF-8') . "</p>"; ?>
</div>
            <h2 class="text-2xl font-bold mb-6 text-green-600 uppercase">Ajouter un Produit</h2>
            <form method="POST" enctype="multipart/form-data" class="space-y-4">
                <input type="text" name="nom" placeholder="Nom du produit" required class="w-full p-3 border rounded-xl bg-gray-50">
                <select name="categorie" id="select-cat" onchange="updateFormulairePrix()" required class="w-full p-3 border rounded-xl bg-gray-50">
                    <option value="">-- Catégorie --</option>
                    <option value="Fruits et Légumes">Fruits et Légumes</option>
                    <option value="Viandes et Poissons">Viandes et Poissons</option>
                    <option value="Jus Naturels et Bio">Jus Naturels et Bio</option>
                </select>
                <div id="box-sous-cat" class="hidden"><select name="sous_categorie" id="select-sous-cat" onchange="updateFormulairePrix()" class="w-full p-3 border rounded-xl bg-blue-50"><option value="viande">Viande / Volaille</option><option value="poisson">Poisson</option></select></div>
                <div id="box-prix-standard"><input type="number" name="prix_standard" placeholder="Prix (FCFA)" class="w-full p-3 border rounded-xl font-bold"></div>
                <div id="box-prix-viande" class="hidden grid grid-cols-3 gap-2 bg-orange-50 p-4 rounded-xl"><input type="number" name="prix_male" placeholder="Mâle"><input type="number" name="prix_femelle" placeholder="Femelle"><input type="number" name="prix_abattu" placeholder="Abattu"></div>
                <textarea name="description" placeholder="Description..." required class="w-full p-3 border rounded-xl h-24"></textarea>
                <div class="mb-6">
                    <label class="text-xs font-bold uppercase text-gray-400 mb-2 block">Photo du produit</label>
                    
                    <div id="drop-zone" class="relative border-2 border-dashed border-green-200 rounded-[25px] p-6 text-center hover:bg-green-50 transition-all cursor-pointer bg-gray-50 overflow-hidden min-h-[180px] flex flex-col items-center justify-center">
                        
                        <div id="preview-default" class="flex flex-col items-center">
                            <i class="fas fa-cloud-upload-alt text-4xl text-green-300 mb-2"></i>
                            <p class="text-sm text-gray-500">Cliquez pour choisir une photo</p>
                        </div>

                        <div id="preview-container" class="hidden absolute inset-0 w-full h-full bg-white flex items-center justify-center">
                            <img id="image-preview" src="" class="w-full h-full object-cover">
                            
                            <button type="button" id="btn-remove" class="absolute top-3 right-3 bg-red-500 text-white w-8 h-8 rounded-full shadow-lg flex items-center justify-center hover:scale-110 transition-transform z-20">
                                <i class="fas fa-times"></i>
                            </button>
                        </div>

                        <input type="file" name="image" id="file-input" accept="image/*" required class="absolute inset-0 opacity-0 cursor-pointer z-10">
                    </div>
                </div>

                    <script>
                        const fileInput = document.getElementById('file-input');
                        const previewContainer = document.getElementById('preview-container');
                        const previewImage = document.getElementById('image-preview');
                        const previewDefault = document.getElementById('preview-default');
                        const btnRemove = document.getElementById('btn-remove');

                        // DÉTECTION DE L'IMAGE
                        fileInput.addEventListener('change', function() {
                            const file = this.files[0];
                            if (file) {
                                const reader = new FileReader();
                                reader.onload = function(e) {
                                    previewImage.src = e.target.result;
                                    previewContainer.classList.remove('hidden');
                                    previewDefault.classList.add('hidden');
                                    // On baisse le z-index de l'input pour pouvoir cliquer sur la croix
                                    fileInput.classList.add('hidden');
                                }
                                reader.readAsDataURL(file);
                            }
                        });

                        // SUPPRESSION DE L'IMAGE
                        btnRemove.addEventListener('click', function(e) {
                            e.preventDefault();
                            e.stopPropagation(); // Empêche d'ouvrir la galerie photo au clic sur la croix

                            fileInput.value = ""; // Vide le fichier
                            previewImage.src = ""; // Efface l'image
                            previewContainer.classList.add('hidden'); // Cache l'aperçu
                            previewDefault.classList.remove('hidden'); // Remet l'icône
                            fileInput.classList.remove('hidden'); // Réactive l'input
                        });
                    </script>
                <button type="submit" name="ajouter_produit" class="w-full bg-green-600 text-white font-bold py-4 rounded-full shadow-lg">PUBLIER</button>
            </form>
        </div>

        <div class="bg-white p-8 rounded-[30px] shadow-lg">
            <h2 class="text-2xl font-bold mb-6 text-gray-400 uppercase">Catalogue Actif</h2>
            <div class="space-y-3 max-h-[600px] overflow-y-auto">
                <?php foreach ($produits as $p): 
                    // RÈGLE DE SÉCURITÉ :
                    // 1. Si 'valide' n'existe pas (anciens produits), on affiche (true).
                    // 2. Si 'valide' existe, on affiche seulement si c'est true.
                    $afficher = isset($p['valide']) ? $p['valide'] : true;

                    if($afficher === true): ?>
                    <div class="flex items-center justify-between border-b p-2 hover:bg-gray-50 transition-colors">
                        <div class="flex items-center gap-3">
                            <img src="<?php echo $p['image']; ?>" class="w-10 h-10 rounded object-cover border shadow-sm">
                            <div>
                                <span class="text-xs font-bold block"><?php echo $p['nom']; ?></span>
                                <span class="text-[10px] text-green-600 font-bold"><?php echo $p['prix']; ?> FCFA</span>
                            </div>
                        </div>
                        <form method="POST" onsubmit="return confirm('Supprimer définitivement ce produit ?');">
                            <input type="hidden" name="id_produit" value="<?php echo $p['id']; ?>">
                            <button type="submit" name="supprimer_produit" class="text-red-300 hover:text-red-600 p-2 transition-colors">
                                <i class="fas fa-trash-alt"></i>
                            </button>
                        </form>
                    </div>
                <?php endif; endforeach; ?>
            </div>
        </div>
    </div>
    
</body>
</html>