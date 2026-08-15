<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>La Réussite - Contact</title>
    <link rel="stylesheet" href="style.css">
    <script src="tailwind.js"></script>
    <script>
        tailwind.config = { theme: { extend: { colors: { brand: { green: '#27ae60', light: '#f4fbf7', orange: '#F9A825', dark: '#333333' } } } } }
    </script>
    <link rel="stylesheet" href="css/all.min.css">
<body class="min-h-screen flex flex-col font-sans bg-[#f4fbf7] text-[#333333] preload-flash">

    <div id="page-transition" class="page-transition-overlay"><img src="logo-reussite.png" alt=""></div>

    <header class="sticky top-0 z-50 bg-white shadow-md">
        <div class="max-w-[1200px] mx-auto px-4 py-3 flex justify-between items-center">
            
            <div class="flex items-center gap-4 cursor-pointer" onclick="window.location.href='index.html'">
                <img src="logo-reussite.png" class="w-16 h-16 object-cover rounded-full border-2 border-[#27ae60]">
                <span class="text-4xl font-brand text-[#27ae60] hidden sm:block">La Réussite</span>
            </div>
            
            <nav class="hidden md:flex items-center gap-8 font-bold text-sm uppercase">
                <a href="index.html" class="hover:text-[#27ae60] transition-colors">Accueil</a>
                <a href="produits.html" class="hover:text-[#27ae60] transition-colors">Produits</a>
                <a href="promos.html" class="text-red-500 hover:text-red-700 transition-colors animate-pulse-fast"><i class="fas fa-fire"></i> Promos</a>
                <a href="apropos.html" class="hover:text-[#27ae60] transition-colors">À Propos</a>
                <a href="admin.php" class="text-[#F9A825] hover:text-yellow-600 transition-colors">Admin</a>
            </nav>
            
            <button onclick="toggleCart()" class="relative p-2 text-[#333333] hover:text-[#27ae60] transition-colors cursor-pointer">
                <i class="fas fa-shopping-cart text-3xl"></i>
                <span id="cart-count" class="absolute top-0 right-0 bg-[#F9A825] text-white text-xs font-bold rounded-full h-6 w-6 flex items-center justify-center shadow-md hidden transition-transform">0</span>
            </button>
            
        </div>
    </header>

    <main class="flex-grow w-full max-w-[800px] mx-auto px-4 py-24 animate-fade-up">
        <h1 class="text-5xl font-display text-center text-[#333333] mb-4 tracking-wider">Contactez-nous</h1>
        <p class="text-center text-gray-600 mb-12 text-lg">Une question ? Une commande spéciale ? N'hésitez pas à nous écrire.</p>

        <div class="flex justify-center gap-8 mb-12">
            <a href="#" class="w-16 h-16 rounded-full bg-white flex items-center justify-center text-[#1877F2] hover:scale-110 transition-all shadow-lg text-4xl"><i class="fab fa-facebook"></i></a>
            <a href="https://wa.me/2290167424373" class="w-16 h-16 rounded-full bg-white flex items-center justify-center text-[#25D366] hover:scale-110 transition-all shadow-lg text-4xl"><i class="fab fa-whatsapp"></i></a>
        </div>

        <form id="contact-form" class="bg-white p-10 rounded-[20px] shadow-[0_10px_30px_rgba(0,0,0,0.05)] border-t-4 border-[#27ae60]">
            <div class="mb-8">
                <input type="text" id="contact-nom" placeholder="Votre Nom" required class="w-full p-4 border border-gray-200 rounded-xl focus:outline-none focus:border-[#27ae60] bg-gray-50" />
            </div>
            <div class="mb-8">
                <input type="text" id="contact-objet" placeholder="Objet" required class="w-full p-4 border border-gray-200 rounded-xl focus:outline-none focus:border-[#27ae60] bg-gray-50" />
            </div>
            <div class="mb-10">
                <textarea id="contact-message" placeholder="Votre message..." required rows="6" class="w-full p-4 border border-gray-200 rounded-xl focus:outline-none focus:border-[#27ae60] bg-gray-50 resize-none"></textarea>
            </div>
            <button type="submit" class="w-full bg-[#F9A825] text-white font-bold py-4 rounded-full text-lg uppercase tracking-wider shadow-lg hover:bg-opacity-90 transition-all">
                <i class="fab fa-whatsapp mr-2"></i> Envoyer sur WhatsApp
            </button>
        </form>
    </main>

    <footer class="bg-[#333333] text-white py-12 mt-20 border-t-4 border-[#27ae60]">
        <div class="max-w-[1200px] mx-auto px-4 grid grid-cols-1 md:grid-cols-3 gap-10 text-center md:text-left">
            <div>
                <div class="flex items-center justify-center md:justify-start gap-3 mb-4">
                    <img src="logo-reussite.png" class="w-12 h-12 rounded-full border border-white/20">
                    <span class="text-2xl font-brand text-[#27ae60]">La Réussite</span>
                </div>
                <p class="text-gray-400 text-sm leading-relaxed">
                    Votre plateforme innovante de produits agroalimentaires frais et biologiques, directement de la ferme à votre assiette.
                </p>
            </div>

            <div>
                <h4 class="text-lg font-bold mb-4 uppercase tracking-wider text-[#F9A825]">Navigation</h4>
                <ul class="text-gray-400 space-y-2 text-sm">
                    <li><a href="index.html" class="hover:text-white transition-colors">Accueil</a></li>
                    <li><a href="produits.html" class="hover:text-white transition-colors">Nos Produits</a></li>
                    <li><a href="promos.html" class="hover:text-white transition-colors text-red-400">Offres Spéciales</a></li>
                    <li><a href="apropos.html" class="hover:text-white transition-colors">À Propos de nous</a></li>
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

    <div id="cart-modal" class="fixed inset-0 bg-black/60 backdrop-blur-sm z-[60] flex justify-end hidden">
        <div class="bg-white w-full max-w-md h-full shadow-2xl flex flex-col transform transition-transform duration-300">
            <div class="p-6 border-b flex justify-between items-center bg-[#f4fbf7]">
                <h2 class="text-3xl font-display tracking-wider text-[#333333] flex items-center gap-3">
                    <i class="fas fa-shopping-cart text-[#27ae60]"></i> Mon Panier
                </h2>
                <button onclick="closeCart()" class="p-2 text-gray-500 hover:text-red-500 transition-colors bg-gray-200 rounded-full hover:bg-red-100">
                    <i class="fas fa-times text-2xl"></i>
                </button>
            </div>
            
            <div id="cart-items" class="flex-grow overflow-y-auto p-6">
                </div>
            
            <div id="checkout-area" class="p-6 border-t bg-white shadow-[0_-10px_20px_rgba(0,0,0,0.05)] hidden">
                <div class="flex justify-between items-center mb-6 text-lg">
                    <span class="font-display text-2xl tracking-wider text-[#333333]">Total</span>
                    <span id="cart-total" class="font-bold text-[#27ae60] text-2xl">0 FCFA</span>
                </div>
                <button onclick="handleCheckout()" class="w-full bg-[#27ae60] text-white font-bold py-4 px-4 rounded-full hover:bg-opacity-90 transition-all transform hover:scale-[1.02] shadow-lg flex justify-center items-center gap-2 text-lg uppercase tracking-wider">
                    Commander via WhatsApp
                </button>
            </div>
        </div>
    </div>

    <script src="script.js"></script>
    <script>
        document.getElementById('contact-form').addEventListener('submit', function(e) {
            e.preventDefault(); 
            const nom = document.getElementById('contact-nom').value;
            const objet = document.getElementById('contact-objet').value;
            const msg = document.getElementById('contact-message').value;
            const text = `*Nouveau message du site web*\n👤 De : ${nom}\n📌 Objet : ${objet}\n\n💬 Message :\n${msg}`;
            window.open(`https://wa.me/2290167424373?text=${encodeURIComponent(text)}`, '_blank');
        });
    </script>
    <script src="js/page-loader.js"></script>
</body>
</html>