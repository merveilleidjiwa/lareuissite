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
    <title>Statistiques - La Réussite</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
</head>
<body class="bg-gray-50 p-6">
    <a href="{{ url('admin') }}" class="text-blue-600 font-bold mb-6 block"><i class="fas fa-arrow-left"></i> RETOUR ACCUEIL</a>

    <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
        <div class="bg-white p-8 rounded-[30px] shadow-lg border-l-8 border-green-500">
            <p class="text-gray-400 text-xs uppercase font-bold">Chiffre d'Affaire</p>
            <h3 class="text-3xl font-black text-green-600"><?php echo number_format($totalCA, 0, '.', ' '); ?> F</h3>
        </div>
        <div class="bg-white p-8 rounded-[30px] shadow-lg border-l-8 border-blue-500">
            <p class="text-gray-400 text-xs uppercase font-bold">Total Commandes</p>
            <h3 class="text-3xl font-black text-blue-600"><?php echo $nbCmd; ?></h3>
        </div>
        <div class="bg-white p-8 rounded-[30px] shadow-lg border-l-8 border-orange-500">
            <p class="text-gray-400 text-xs uppercase font-bold">Livreurs Actifs</p>
            <h3 class="text-3xl font-black text-orange-600">3</h3>
        </div>
    </div>

    <div class="bg-white p-8 rounded-[30px] shadow-lg">
        <h2 class="text-2xl font-bold mb-6 uppercase">Historique Complet (MySQL)</h2>
        <table class="w-full text-left">
            <thead>
                <tr class="text-gray-400 text-xs uppercase border-b">
                    <th class="pb-4">Date</th>
                    <th class="pb-4">Quartier</th>
                    <th class="pb-4">Montant</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach($commandes_db as $c): ?>
                <tr class="border-b">
                    <td class="py-4 text-xs"><?php echo date('d/m/Y H:i', strtotime($c['date_commande'])); ?></td>
                    <td class="py-4 font-bold"><?php echo $c['quartier']; ?></td>
                    <td class="py-4 text-green-600 font-bold"><?php echo number_format($c['total'], 0, '.', ' '); ?> F</td>
                </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
</body>
</html>