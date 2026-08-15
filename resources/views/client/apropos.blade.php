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
    <title>À Propos - La Réussite</title>
    <script src="/tailwind.js"></script>
    <script>tailwind.config = { theme: { extend: { colors: { brand: { green: '#27ae60', light: '#f4fbf7', orange: '#F9A825', dark: '#333333' } } } } }</script>
    <link rel="stylesheet" href="/css/all.min.css">
    <link rel="stylesheet" href="/style.css">
</head>

<script>
    var isConnected = <?php echo $is_connected ? 'true' : 'false'; ?>;
    var userInitial = "<?php echo $initiale; ?>";
</script>
<script src="/script.js"></script>


<body class="min-h-screen flex flex-col font-sans bg-[#f4fbf7] text-[#333333]">

   <header class="sticky top-0 z-50 bg-white shadow-md">
        <div class="max-w-[1200px] mx-auto px-4 py-3 flex justify-between items-center">
            
            <div class="flex items-center gap-4 cursor-pointer" onclick="window.location.href="{{ url('/') }}"">
                <img src="/logo-reussite.png" class="w-16 h-16 object-cover rounded-full border-2 border-[#27ae60]">
                <span class="text-4xl font-brand text-[#27ae60] hidden sm:block">La Réussite</span>
            </div>
            
            <nav class="hidden md:flex items-center gap-8 font-bold text-sm uppercase">
                <a href="{{ url('/') }}" class="hover:text-[#27ae60] transition-colors">Accueil</a>
                <a href="{{ url('produits') }}" class="hover:text-[#27ae60] transition-colors">Produits</a>
                <a href="{{ url('promos') }}" class="text-red-500 hover:text-red-700 transition-colors animate-pulse-fast"><i class="fas fa-fire"></i> Promos</a>
                <a href="{{ url('apropos') }}" class="hover:text-[#27ae60] transition-colors">À Propos</a>
                <a href="{{ url('admin') }}" class="text-[#F9A825] hover:text-yellow-600 transition-colors">Admin</a>
            </nav>
            
            <button onclick="toggleCart()" class="relative p-2 text-[#333333] hover:text-[#27ae60] transition-colors cursor-pointer">
                <i class="fas fa-shopping-cart text-3xl"></i>
                <span id="cart-count" class="absolute top-0 right-0 bg-[#F9A825] text-white text-xs font-bold rounded-full h-6 w-6 flex items-center justify-center shadow-md hidden transition-transform">0</span>
            </button>
            
        </div>
    </header>

    <main class="flex-grow w-full max-w-[1000px] mx-auto px-4 py-16 animate-fade-up">
        
        <div class="text-center mb-16">
            <span class="text-[#F9A825] font-bold tracking-widest uppercase text-sm mb-2 block"><i class="fas fa-leaf"></i> Notre Vision</span>
            <h1 class="text-5xl font-display text-[#333333] mb-6 tracking-wider">De la Ferme à l'Assiette</h1>
            <p class="text-xl text-gray-600 leading-relaxed max-w-3xl mx-auto">
                LA RÉUSSITE-AGRONOMIC-SMART est une plateforme béninoise innovante spécialisée dans la distribution de produits agroalimentaires frais et biologiques.
            </p>
        </div>

        <div class="grid md:grid-cols-2 gap-10 mb-16">
            <div class="bg-white p-8 rounded-[20px] shadow-sm border-t-4 border-[#27ae60]">
                <i class="fas fa-snowflake text-4xl text-blue-400 mb-4"></i>
                <h3 class="text-2xl font-bold mb-3">L'Ultra-Fraîcheur (Zéro Congélation)</h3>
                <p class="text-gray-600">Contrairement aux boucheries classiques, nous misons sur l'ultra-fraîcheur. Les animaux ne sont pas congelés à l'avance ; ils sont préparés après votre commande. Nettoyés avec soin (citron, vinaigre), ils sont livrés "prêts pour la cuisine".</p>
            </div>
            <div class="bg-white p-8 rounded-[20px] shadow-sm border-t-4 border-[#F9A825]">
                <i class="fas fa-shield-alt text-4xl text-[#F9A825] mb-4"></i>
                <h3 class="text-2xl font-bold mb-3">Qualité & Certification</h3>
                <p class="text-gray-600">Nous assurons l'entretien rigoureux de notre abattoir. Nos produits sont en cours de certification par l'ABSSA (Agence Béninoise de Sécurité Sanitaire des Aliments) pour vous garantir une sécurité alimentaire totale.</p>
            </div>
        </div>

        <div class="bg-[#27ae60] text-white p-10 rounded-[30px] shadow-lg text-center relative overflow-hidden">
            <i class="fas fa-hands-helping text-[150px] absolute -right-10 -bottom-10 opacity-10"></i>
            <h2 class="text-3xl font-display mb-4 relative z-10">Soutenir les Producteurs Locaux</h2>
            <p class="text-lg relative z-10 max-w-2xl mx-auto opacity-90">
                Notre objectif est de faciliter la mise en relation entre nos agriculteurs béninois et vous. Sans fausses promesses médicales, nous vous offrons simplement ce que la nature a de meilleur : du miel pur, des farines locales, des légumes bio et des viandes tracées.
            </p>
        </div>

    </main>

   <footer class="bg-[#333333] text-white py-12 mt-20 border-t-4 border-[#27ae60]">
        <div class="max-w-[1200px] mx-auto px-4 grid grid-cols-1 md:grid-cols-3 gap-10 text-center md:text-left">
            <div>
                <div class="flex items-center justify-center md:justify-start gap-3 mb-4">
                    <img src="/logo-reussite.png" class="w-12 h-12 rounded-full border border-white/20">
                    <span class="text-2xl font-brand text-[#27ae60]">La Réussite</span>
                </div>
                <p class="text-gray-400 text-sm leading-relaxed">
                    Votre plateforme innovante de produits agroalimentaires frais et biologiques, directement de la ferme à votre assiette.
                </p>
            </div>

            <div>
                <h4 class="text-lg font-bold mb-4 uppercase tracking-wider text-[#F9A825]">Navigation</h4>
                <ul class="text-gray-400 space-y-2 text-sm">
                    <li><a href="{{ url('/') }}" class="hover:text-white transition-colors">Accueil</a></li>
                    <li><a href="{{ url('produits') }}" class="hover:text-white transition-colors">Nos Produits</a></li>
                    <li><a href="{{ url('promos') }}" class="hover:text-white transition-colors text-red-400">Offres Spéciales</a></li>
                    <li><a href="{{ url('apropos') }}" class="hover:text-white transition-colors">À Propos de nous</a></li>
                </ul>
            </div>

            <div>
                <h4 class="text-lg font-bold mb-4 uppercase tracking-wider text-[#F9A825]">Contact</h4>
                <ul class="text-gray-400 space-y-3 text-sm">
                    <li class="flex items-center justify-center md:justify-start gap-2">
                        <i class="fas fa-map-marker-alt text-[#27ae60]"></i> Parakou, Bénin
                    </li>
                    <li class="flex items-center justify-center md:justify-start gap-2">
                        <i class="fab fa-whatsapp text-[#27ae60]"></i> +229 01 67 42 43 73
                    </li>
                    <li class="flex items-center justify-center md:justify-start gap-2">
                        <i class="fas fa-certificate text-[#27ae60]"></i> Qualité certifiée ABSSA
                    </li>
                </ul>
            </div>
        </div>
        
        <div class="border-t border-white/10 mt-10 pt-6 text-center text-gray-500 text-xs uppercase tracking-[3px]">
            © 2026 La Réussite Agronomic Smart. Tous droits réservés.
        </div>
    </footer>

    <script src="/script.js"></script>


</body>
</html>