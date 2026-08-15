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
    <title>Catalogue - La Réussite</title>
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
                <div class="hidden sm:flex flex-col justify-center mt-1"><span class="text-4xl font-brand text-[#27ae60] leading-none">La Réussite</span><span class="text-[0.65rem] text-[#F9A825] uppercase tracking-[0.25em] font-black pl-1 mt-0.5">Agronomique</span></div>
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

    <main class="flex-grow w-full max-w-[1200px] mx-auto px-4 py-10">
        <div class="mb-12 max-w-4xl mx-auto">
            <div class="relative mb-6 shadow-sm">
                <input type="text" id="search-bar" onkeyup="filtrerProduits()" placeholder="Rechercher un produit (ex: Pintade, Jus, Pack...)" class="w-full p-4 pl-12 border border-gray-200 rounded-full focus:outline-none focus:border-[#27ae60] text-lg">
                <i class="fas fa-search absolute left-5 top-1/2 transform -translate-y-1/2 text-gray-400 text-lg"></i>
            </div>

            <div class="flex flex-wrap justify-center gap-3" id="category-filters">
                <button onclick="changerCategorie('Tout')" class="cat-btn active bg-[#27ae60] text-white px-5 py-2 rounded-full font-bold text-sm shadow-md">Tout</button>
                <button onclick="changerCategorie('Fruits et Légumes')" class="cat-btn bg-white text-gray-600 border px-5 py-2 rounded-full font-bold text-sm hover:bg-gray-50">Fruits & Légumes</button>
                <button onclick="changerCategorie('Viandes et Poissons')" class="cat-btn bg-white text-gray-600 border px-5 py-2 rounded-full font-bold text-sm hover:bg-gray-50">Viandes & Poissons</button>
                <button onclick="changerCategorie('Jus Naturels et Bio')" class="cat-btn bg-white text-gray-600 border px-5 py-2 rounded-full font-bold text-sm hover:bg-gray-50">Jus & Bio</button>
                <button onclick="changerCategorie('Épices et Assaisonnements')" class="cat-btn bg-white text-gray-600 border px-5 py-2 rounded-full font-bold text-sm hover:bg-gray-50">Épices</button>
                <button onclick="changerCategorie('Packs Sur Mesure')" class="cat-btn bg-white text-[#F9A825] border border-[#F9A825] px-5 py-2 rounded-full font-bold text-sm hover:bg-yellow-50"><i class="fas fa-gift mr-1"></i> Packs</button>
            </div>
        </div>

        <div id="all-products-container" class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-10"></div>
    </main>

    <footer class="bg-[#333333] text-white py-12 mt-20 border-t-4 border-[#27ae60]">
        <div class="max-w-[1200px] mx-auto px-4 grid grid-cols-1 md:grid-cols-3 gap-10 text-center md:text-left">
            <div>
                <div class="flex items-center justify-center md:justify-start gap-3 mb-4">
                    <img src="/logo-reussite.png" class="w-12 h-12 rounded-full border border-white/20">
                    <div class="flex flex-col justify-center mt-1"><span class="text-2xl font-brand text-[#27ae60] leading-none">La Réussite</span><span class="text-[0.5rem] text-[#F9A825] uppercase tracking-[0.25em] font-black pl-0.5">Agronomique</span></div>
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