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
    <title>La Réussite - Promos</title>
    <link rel="stylesheet" href="/style.css">
   <script src="/tailwind.js"></script>
    <script>tailwind.config = { theme: { extend: { colors: { brand: { green: '#27ae60', light: '#f4fbf7', orange: '#F9A825', dark: '#333333' } } } } }</script>
    <link rel="stylesheet" href="/css/all.min.css">
</head>

<script>
    var isConnected = <?php echo $is_connected ? 'true' : 'false'; ?>;
    var userInitial = "<?php echo $initiale; ?>";
</script>
<script src="/script.js"></script>


<body class="min-h-screen flex flex-col font-sans bg-[#f4fbf7] text-[#333333] preload-flash">

    <div id="page-transition" class="page-transition-overlay"><img src="/logo-reussite.png" alt=""></div>

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

    <main class="flex-grow w-full max-w-[1200px] mx-auto px-4 py-24">
        <h1 class="text-6xl font-display text-center text-[#d84315] mb-4 tracking-wider animate-fade-up">Offres Spéciales</h1>
        <p class="text-center text-gray-600 mb-16 text-xl animate-fade-up" style="animation-delay: 0.2s;">Profitez de nos réductions exclusives pour le Ramadan et Pâques !</p>
        <div id="promo-products-container" class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-10"></div>
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

<div id="options-modal" class="fixed inset-0 bg-black/80 backdrop-blur-sm z-[70] flex justify-center items-end sm:items-center hidden transition-all">
    <div class="bg-white w-full max-w-md sm:rounded-3xl rounded-t-3xl p-6 relative shadow-2xl animate-fade-up">
        <button onclick="closeOptionsModal()" class="absolute top-4 right-4 w-8 h-8 bg-gray-100 rounded-full text-gray-500 hover:bg-red-100 hover:text-red-500 transition-colors flex items-center justify-center">
            <i class="fas fa-times"></i>
        </button>
        
        <h2 id="modal-title" class="text-2xl font-display text-[#333333] mb-2 pr-8">Personnalisez votre commande</h2>
        <p id="modal-subtitle" class="text-sm text-[#27ae60] font-bold mb-6">Prix de base : <span id="modal-base-price"></span> FCFA</p>
        
        <div id="modal-dynamic-content" class="mb-8 space-y-5"></div>
        
        <div class="flex justify-between items-center border-t pt-4">
            <div>
                <span class="text-xs text-gray-500 uppercase font-bold block">Sous-total</span>
                <span id="modal-calculated-price" class="text-2xl font-bold text-[#F9A825]">0 FCFA</span>
            </div>
            <button onclick="confirmAddToCart()" class="bg-[#27ae60] text-white font-bold py-3 px-8 rounded-full hover:bg-opacity-90 transition-all shadow-md flex items-center gap-2">
                <i class="fas fa-check"></i> Valider
            </button>
        </div>
    </div>
</div>


    <div id="cart-modal" class="fixed inset-0 bg-black/60 backdrop-blur-sm z-[60] flex justify-end hidden">
        <div class="bg-white w-full max-w-md h-full shadow-2xl flex flex-col">
            <div class="p-6 border-b flex justify-between bg-[#f4fbf7]">
                <h2 class="text-3xl font-display text-[#333333]"><i class="fas fa-shopping-cart text-[#27ae60]"></i> Mon Panier</h2>
                <button onclick="closeCart()" class="text-gray-500 hover:text-red-500"><i class="fas fa-times text-2xl"></i></button>
            </div>
            <div id="cart-items" class="flex-grow overflow-y-auto p-6"></div>
            <div id="checkout-area" class="p-6 border-t bg-white hidden">
                <div class="flex justify-between mb-6"><span class="font-display text-2xl">Total</span><span id="cart-total" class="font-bold text-[#27ae60] text-2xl">0 FCFA</span></div>
                <button onclick="handleCheckout()" class="w-full bg-[#27ae60] text-white font-bold py-4 rounded-full">Commander via WhatsApp</button>
            </div>
        </div>
    </div>

    <script src="/js/page-loader.js"></script>
</body>
</html>