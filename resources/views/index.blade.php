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
    <title>La Réussite - Agronomic Smart</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script>tailwind.config = { theme: { extend: { colors: { brand: { green: '#27ae60', light: '#f4fbf7', orange: '#F9A825', dark: '#333333' } } } } }</script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <link rel="stylesheet" href="style.css">
</head>

<body class="min-h-screen flex flex-col font-sans bg-[#f4fbf7] text-[#333333]">

   <header class="sticky top-0 z-50 bg-white shadow-md">
        <div class="max-w-[1200px] mx-auto px-4 py-3 flex justify-between items-center">
            
            <div class="flex items-center gap-4 cursor-pointer" onclick="window.location.href="{{ url('/') }}"">
                <img src="logo-reussite.png" class="w-16 h-16 object-cover rounded-full border-2 border-[#27ae60]">
                <span class="text-4xl font-brand text-[#27ae60] hidden sm:block">La Réussite</span>
            </div>
            
            <nav class="hidden md:flex items-center gap-8 font-bold text-sm uppercase">
                <a href="{{ url('/') }}" class="text-[#27ae60]">Accueil</a>
                <a href="{{ url('produits') }}" class="hover:text-[#27ae60] transition-colors">Produits</a>
                <a href="{{ url('promos') }}" class="text-red-500 hover:text-red-700 transition-colors animate-pulse"><i class="fas fa-fire"></i> Promos</a>
                <a href="{{ url('apropos') }}" class="hover:text-[#27ae60] transition-colors">À Propos</a>
                
                <?php if($is_connected): ?>
                    <div class="relative group flex items-center gap-2 cursor-pointer bg-gray-50 px-3 py-1 rounded-full border border-gray-100">
                        <div class="w-8 h-8 bg-[#27ae60] text-white rounded-full flex items-center justify-center font-black shadow-sm">
                            <?php echo $initiale; ?>
                        </div>
                        <i class="fas fa-chevron-down text-[10px] text-gray-400"></i>
                        
                        <div class="hidden group-hover:block absolute top-10 right-0 bg-white shadow-2xl rounded-2xl p-4 w-48 border border-gray-100 animate-fade-up z-50">
                            <p class="text-[9px] text-gray-400 uppercase tracking-widest mb-1">Session active</p>
                            <p class="text-xs font-black text-gray-800 mb-3 truncate"><?php echo $user_nom; ?></p>
                            <hr class="mb-2">
                            <a href="{{ url('profil') }}" class="block py-2 text-xs hover:text-[#27ae60]"><i class="fas fa-user-circle mr-2"></i> Mon Profil</a>
                            
                            <?php if($user_role === 'vendeur'): ?>
                                <a href="{{ url('vendeur') }}" class="block py-2 text-xs hover:text-[#27ae60]"><i class="fas fa-store mr-2"></i> Ma Boutique</a>
                            <?php elseif($user_role === 'admin'): ?>
                                <a href="{{ url('admin') }}" class="block py-2 text-xs hover:text-yellow-600"><i class="fas fa-user-shield mr-2"></i> Admin</a>
                            <?php elseif($user_role === 'livreur'): ?>
                                <a href="{{ url('livreur') }}" class="block py-2 text-xs hover:text-blue-500"><i class="fas fa-motorcycle mr-2"></i> Livraisons</a>
                            <?php endif; ?>

                            <a href="{{ url('logout') }}" class="block py-2 text-xs text-red-500 mt-2 border-t border-gray-50 font-bold"><i class="fas fa-sign-out-alt mr-2"></i> Déconnexion</a>
                        </div>
                    </div>
                <?php else: ?>
                    <a href="{{ url('inscription') }}" class="hover:text-[#27ae60] transition-colors">S'inscrire</a>
                    <a href="{{ url('connexion') }}" class="bg-[#27ae60] text-white px-6 py-2 rounded-full shadow-lg hover:scale-105 transition-all">Connexion</a>
                <?php endif; ?>
            </nav>
            
            <button onclick="toggleCart()" class="relative p-2 text-[#333333] hover:text-[#27ae60] transition-colors cursor-pointer">
                <i class="fas fa-shopping-cart text-3xl"></i>
                <span id="cart-count" class="absolute top-0 right-0 bg-[#F9A825] text-white text-xs font-bold rounded-full h-6 w-6 flex items-center justify-center shadow-md hidden">0</span>
            </button>
            
        </div>
    </header>

    <main class="flex-grow w-full">
        <section class="relative h-[60vh] min-h-[400px] bg-black overflow-hidden flex items-center justify-center">
            <div id="hero-carousel" class="absolute inset-0 w-full h-full z-0"></div>
            <div class="relative z-10 text-center px-4 animate-fade-up bg-black/40 p-10 rounded-3xl backdrop-blur-sm border border-white/10">
                <h1 class="text-5xl md:text-7xl font-display text-white mb-6 tracking-wider shadow-black drop-shadow-2xl uppercase font-black">De la ferme à l'assiette</h1>
                <p class="text-xl text-white mb-10 max-w-2xl mx-auto font-medium drop-shadow-md">Produits locaux ultra-frais, viandes préparées sur mesure et livraison rapide à Parakou.</p>
                <a href="{{ url('produits') }}" class="bg-[#F9A825] text-[#333333] font-bold py-4 px-10 rounded-full text-lg uppercase tracking-wider hover:bg-white hover:text-[#27ae60] transition-all shadow-xl transform hover:scale-105 inline-block">Voir le Catalogue</a>
            </div>
        </section>

        <section class="bg-white border-b border-gray-200 shadow-sm py-6 relative z-20 -mt-6 mx-4 md:mx-auto max-w-[1000px] rounded-2xl">
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6 text-center divide-y md:divide-y-0 md:divide-x divide-gray-100">
                <div class="p-2">
                    <i class="fas fa-temperature-low text-3xl text-blue-500 mb-2"></i>
                    <h4 class="font-bold text-[#333333]">100% Ultra-Frais</h4>
                    <p class="text-xs text-gray-500 mt-1">Zéro congélation, préparé à la commande.</p>
                </div>
                <div class="p-2">
                    <i class="fas fa-shield-alt text-3xl text-green-500 mb-2"></i>
                    <h4 class="font-bold text-[#333333]">Qualité ABSSA</h4>
                    <p class="text-xs text-gray-500 mt-1">Hygiène rigoureuse (citron/vinaigre).</p>
                </div>
                <div class="p-2">
                    <i class="fas fa-motorcycle text-3xl text-[#F9A825] mb-2"></i>
                    <h4 class="font-bold text-[#333333]">Livraison Rapide</h4>
                    <p class="text-xs text-gray-500 mt-1">Directement au bureau ou à domicile.</p>
                </div>
            </div>
        </section>

        <section class="max-w-[1200px] mx-auto px-4 py-16">
            <h2 class="text-4xl font-display text-center text-[#333333] mb-10 tracking-wider">Nos Coups de Cœur ❤️</h2>
            <div id="home-products-container" class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-10"></div>
            <div class="text-center mt-12">
                <a href="{{ url('produits') }}" class="inline-block border-2 border-[#27ae60] text-[#27ae60] font-bold py-3 px-8 rounded-full hover:bg-[#27ae60] hover:text-white transition-colors uppercase tracking-wider">Voir tout le catalogue</a>
            </div>
        </section>
    </main>

    <script>
        // 1. On définit la connexion
        var isConnected = <?php echo $is_connected ? 'true' : 'false'; ?>;
        var userInitial = "<?php echo $initiale; ?>"; // On récupère l'initiale PHP

        // 2. Logique du carrousel
        const carouselImages = ["hero1.jpg", "hero2.jpg", "hero3.jpg", "hero4.jpg", "hero5.jpg"];
        const carousel = document.getElementById('hero-carousel');
        
        carouselImages.forEach((img, i) => {
            const opacityClass = i === 0 ? 'opacity-60' : 'opacity-0';
            carousel.innerHTML += `<img id="slide-${i}" src="${img}" class="absolute inset-0 w-full h-full object-cover transition-opacity duration-1000 ease-in-out ${opacityClass}">`;
        });

        let currentSlide = 0;
        setInterval(() => {
            const currentEl = document.getElementById(`slide-${currentSlide}`);
            if(currentEl) currentEl.classList.replace('opacity-60', 'opacity-0');
            
            currentSlide = (currentSlide + 1) % carouselImages.length;
            
            const nextEl = document.getElementById(`slide-${currentSlide}`);
            if(nextEl) nextEl.classList.replace('opacity-0', 'opacity-60');
        }, 4000);
    </script>
    
    <script src="script.js"></script>

    
    <div id="profile-drawer" class="fixed inset-y-0 right-0 w-full sm:w-96 bg-white shadow-2xl z-[100] transform translate-x-full transition-transform duration-300 ease-in-out">
        <div class="p-6 border-b flex justify-between items-center bg-white">
            <h2 class="font-black text-sm uppercase tracking-widest text-gray-900">Mon Compte</h2>
            
            <button onclick="closeProfile()" class="group bg-gray-100 p-2 rounded-full hover:bg-red-50 transition-all duration-300">
                <i class="fas fa-times text-gray-400 group-hover:text-red-500 group-hover:rotate-90 transition-all duration-300"></i>
            </button>
        </div>
        <div class="p-6 border-b flex justify-between items-center bg-[#f4fbf7]">
            <button onclick="closeProfile()" class="text-gray-500 hover:text-[#27ae60] transition-colors flex items-center gap-2 font-bold text-xs uppercase">
                <i class="fas fa-arrow-right"></i> Retour
            </button>
            <h2 class="font-black text-sm uppercase tracking-widest text-[#27ae60]">Mon Profil</h2>
        </div>

        <div id="profile-content" class="p-8">
            <div class="flex flex-col items-center text-center space-y-6">
                <div id="prof-initial" class="w-24 h-24 bg-[#27ae60] text-white rounded-full flex items-center justify-center font-black text-4xl shadow-lg">
                    </div>
                <div>
                    <h1 id="prof-nom" class="text-2xl font-extrabold text-gray-900">Chargement...</h1>
                    <p class="text-[10px] text-gray-400 font-bold uppercase tracking-widest mt-1">Client Privilégié</p>
                </div>
                
                <div class="w-full space-y-4 pt-6 border-t border-gray-100">
                    <div class="flex items-center gap-4 text-sm text-gray-600">
                        <i class="fas fa-envelope w-5 text-[#27ae60]"></i>
                        <span id="prof-email">...</span>
                    </div>
                    <div class="flex items-center gap-4 text-sm text-gray-600">
                        <i class="fab fa-whatsapp w-5 text-[#27ae60]"></i>
                        <span id="prof-tel">...</span>
                    </div>
                </div>

                <a href="{{ url('logout') }}" class="w-full mt-10 bg-red-50 text-red-500 py-4 rounded-2xl font-bold uppercase text-[10px] hover:bg-red-500 hover:text-white transition-all text-center">
                    <i class="fas fa-sign-out-alt mr-2"></i> Déconnexion
                </a>
            </div>
        </div>
    </div>

<div id="profile-overlay" onclick="closeProfile()" class="fixed inset-0 bg-black/50 z-[90] hidden backdrop-blur-sm"></div>
</body>
</html>