<?php
if (!isset($initiale)) $initiale = 'U';
if (!isset($user_nom)) $user_nom = 'Utilisateur';
if (!isset($user_infos)) $user_infos = ['email'=>'user@example.com', 'telephone'=>'00000000'];
if (!isset($historique)) $historique = [];
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
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Mon Profil - La Réussite</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
</head>
<body class="bg-gray-50 min-h-screen font-sans">

    <header class="bg-white shadow-sm p-4">
        <div class="max-w-6xl mx-auto flex justify-between items-center">
            <a href="{{ url('/') }}" class="flex flex-col justify-center"><span class="text-3xl font-brand text-[#27ae60] leading-none">La Réussite</span><span class="text-[0.55rem] text-[#F9A825] uppercase tracking-[0.25em] font-black pl-1 mt-0.5">Agronomique</span></a>
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