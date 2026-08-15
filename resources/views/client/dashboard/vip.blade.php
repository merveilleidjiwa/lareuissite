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
    <title>Club VIP - La Réussite</title>
    <script src="/tailwind.js"></script>
    <script>tailwind.config = { theme: { extend: { colors: { brand: { green: '#27ae60', light: '#f4fbf7', orange: '#F9A825', dark: '#333333' } } } } }</script>
    <link rel="stylesheet" href="/css/all.min.css">
    <link rel="stylesheet" href="/style.css">
    <script>
        // FONCTION POUR AFFICHER/CACHER LE MOT DE PASSE (Thème VIP)
        function togglePassword(inputId, icon) {
            const input = document.getElementById(inputId);
            if (input.type === "password") {
                input.type = "text";
                icon.classList.remove("fa-eye");
                icon.classList.add("fa-eye-slash", "text-[#F9A825]");
            } else {
                input.type = "password";
                icon.classList.remove("fa-eye-slash", "text-[#F9A825]");
                icon.classList.add("fa-eye");
            }
        }
    </script>
</head>
<body class="min-h-screen flex flex-col font-sans bg-gray-900 text-white preload-flash">

    <div id="page-transition" class="page-transition-overlay bg-gray-900"><img src="/logo-reussite.png" alt="" class="w-20 h-20 object-contain"></div>

    <header class="sticky top-0 z-50 bg-gray-900 shadow-md border-b border-gray-800">
        <div class="max-w-[1200px] mx-auto px-4 py-3 flex justify-between items-center">
            <div class="flex items-center gap-4 cursor-pointer" onclick="window.location.href="{{ url('/') }}"">
                <img src="/logo-reussite.png" class="w-16 h-16 object-cover rounded-full border-2 border-[#F9A825]">
                <span class="text-4xl font-brand text-[#F9A825] hidden sm:block">Club VIP</span>
            </div>
            <nav class="hidden md:flex items-center gap-8 font-bold text-sm uppercase">
                <a href="{{ url('/') }}" class="text-gray-300 hover:text-[#F9A825] transition-colors">Retour public</a>
                <?php if (isset($_SESSION['vip_connecte'])): ?>
                    <a href="?logout_vip=1" class="text-red-500 bg-red-900/30 px-4 py-2 rounded-full hover:bg-red-500 hover:text-white transition-colors"><i class="fas fa-sign-out-alt"></i> Quitter</a>
                <?php endif; ?>
            </nav>
            <button onclick="toggleCart()" class="relative p-2 text-[#F9A825] hover:text-white transition-colors cursor-pointer">
                <i class="fas fa-shopping-cart text-3xl"></i>
                <span id="cart-count" class="absolute top-0 right-0 bg-red-600 text-white text-xs font-bold rounded-full h-6 w-6 flex items-center justify-center shadow-md hidden">0</span>
            </button>
        </div>
    </header>

    <main class="flex-grow w-full max-w-[1200px] mx-auto px-4 py-16 animate-fade-up">
        
        <?php if (!isset($_SESSION['vip_connecte'])): ?>
            
            <div class="bg-gray-800 p-10 rounded-[20px] shadow-2xl border-t-4 border-[#F9A825] max-w-md mx-auto mt-12 text-center">
                <i class="fas fa-crown text-6xl text-[#F9A825] mb-6"></i>
                
                <?php if ($action === 'register'): ?>
                    <h2 class="text-3xl font-display text-white mb-6 tracking-wider">Rejoindre le Club</h2>
                    <?php if(isset($erreur_reg)) echo "<p class='text-red-500 mb-4 font-bold'>$erreur_reg</p>"; ?>
                    
                    <form method="POST">
                        <input type="text" name="nom" placeholder="Votre Nom (ex: Resto La Mer)..." required class="w-full p-4 border border-gray-600 rounded-xl mb-4 bg-gray-700 text-white text-center focus:border-[#F9A825] focus:outline-none">
                        <input type="text" name="telephone" placeholder="Numéro WhatsApp..." required class="w-full p-4 border border-gray-600 rounded-xl mb-4 bg-gray-700 text-white text-center focus:border-[#F9A825] focus:outline-none">
                        
                        <div class="relative mb-6">
                            <input type="password" name="password" id="reg_vip_pwd" placeholder="Créer un mot de passe VIP..." required class="w-full p-4 border border-gray-600 rounded-xl bg-gray-700 text-white text-center focus:border-[#F9A825] focus:outline-none pr-12">
                            <i class="fas fa-eye absolute right-4 top-1/2 transform -translate-y-1/2 text-gray-400 cursor-pointer hover:text-[#F9A825] text-xl transition-colors" onclick="togglePassword('reg_vip_pwd', this)"></i>
                        </div>

                        <button type="submit" name="register_vip" class="w-full bg-[#F9A825] text-gray-900 font-bold py-4 rounded-xl hover:bg-opacity-90 uppercase tracking-wider mb-4 shadow-[0_0_15px_rgba(249,168,37,0.4)]">Devenir VIP</button>
                    </form>
                    <a href="?action=login" class="text-sm text-[#F9A825] hover:underline">Déjà membre ? Se connecter.</a>

                <?php else: ?>
                    <h2 class="text-3xl font-display text-white mb-6 tracking-wider">Accès Privilège</h2>
                    <?php if(isset($erreur_login)) echo "<p class='text-red-500 mb-4 font-bold'>$erreur_login</p>"; ?>
                    <?php if($message_reg) echo "<p class='text-green-400 mb-4 font-bold bg-green-900/30 p-2 rounded border border-green-800'>$message_reg</p>"; ?>
                    
                    <form method="POST">
                        <input type="text" name="telephone" placeholder="Numéro de compte VIP..." required class="w-full p-4 border border-gray-600 rounded-xl mb-4 bg-gray-700 text-white text-center focus:border-[#F9A825] focus:outline-none">
                        
                        <div class="relative mb-6">
                            <input type="password" name="password" id="log_vip_pwd" placeholder="Mot de passe..." required class="w-full p-4 border border-gray-600 rounded-xl bg-gray-700 text-white text-center focus:border-[#F9A825] focus:outline-none pr-12">
                            <i class="fas fa-eye absolute right-4 top-1/2 transform -translate-y-1/2 text-gray-400 cursor-pointer hover:text-[#F9A825] text-xl transition-colors" onclick="togglePassword('log_vip_pwd', this)"></i>
                        </div>

                        <button type="submit" name="login_vip" class="w-full bg-[#F9A825] text-gray-900 font-bold py-4 rounded-xl hover:bg-opacity-90 uppercase tracking-wider shadow-[0_0_15px_rgba(249,168,37,0.4)] mb-4">Entrer dans le Club</button>
                    </form>
                    <a href="?action=register" class="text-sm text-gray-400 hover:text-white hover:underline">Devenir un partenaire VIP.</a>
                <?php endif; ?>
            </div>
        
        <?php else: ?>
            
            <div class="text-center mb-16">
                <h1 class="text-5xl font-display text-[#F9A825] mb-4 tracking-wider">
                    Bienvenue, <span class="text-white"><?php echo $_SESSION['vip_nom']; ?></span> <i class="fas fa-star text-3xl mb-1 ml-2"></i>
                </h1>
                <p class="text-gray-400 text-xl">Vous bénéficiez de <strong class="text-white">-30%</strong> sur tout le catalogue (cumulable avec nos promos !).</p>
            </div>

            <div id="vip-products-container" class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-10"></div>

        <?php endif; ?>
        
    </main>

    <div id="cart-modal" class="fixed inset-0 bg-black/80 backdrop-blur-sm z-[60] flex justify-end hidden">
        <div class="bg-gray-800 w-full max-w-md h-full shadow-2xl flex flex-col text-white transform transition-transform duration-300">
            <div class="p-6 border-b border-gray-700 flex justify-between items-center bg-gray-900">
                <h2 class="text-3xl font-display tracking-wider flex items-center gap-3 text-[#F9A825]">
                    <i class="fas fa-shopping-cart"></i> Mon Panier VIP
                </h2>
                <button onclick="closeCart()" class="p-2 text-gray-400 hover:text-red-500 transition-colors bg-gray-700 rounded-full hover:bg-gray-600">
                    <i class="fas fa-times text-xl"></i>
                </button>
            </div>
            <div id="cart-items" class="flex-grow overflow-y-auto p-6 text-gray-200"></div>
            <div id="checkout-area" class="p-6 border-t border-gray-700 bg-gray-900 hidden">
                <div class="flex justify-between items-center mb-6 text-lg">
                    <span class="font-display text-2xl tracking-wider">Total</span>
                    <span id="cart-total" class="font-bold text-[#F9A825] text-2xl">0 FCFA</span>
                </div>
                <button onclick="handleCheckout()" class="w-full bg-[#F9A825] text-gray-900 font-bold py-4 px-4 rounded-full hover:bg-opacity-90 transition-all transform hover:scale-[1.02] shadow-[0_0_15px_rgba(249,168,37,0.4)] flex justify-center items-center gap-2 text-lg uppercase tracking-wider">
                    Commander via WhatsApp
                </button>
            </div>
        </div>
    </div>

    <script src="/script.js"></script>

    <?php if (isset($_SESSION['vip_connecte'])): ?>
    <script>
        async function chargerProduitsVIP() {
            try {
                const response = await fetch('get_produits.php?t=' + new Date().getTime());
                const produits = await response.json();
                const container = document.getElementById('vip-products-container');
                container.innerHTML = '';

                produits.forEach(product => {
                    let pourcentageVIP = 30; 
                    let badgePromo = '';
                    
                    if (product.en_promo == 1 && product.pourcentage_promo) {
                        pourcentageVIP = 30 + parseInt(product.pourcentage_promo);
                        badgePromo = `<div class="absolute top-4 left-4 bg-red-500 text-white font-bold text-xs py-1 px-3 rounded-md z-10 shadow-sm uppercase">${product.nom_promo || 'Promo'} (+${product.pourcentage_promo}%)</div>`;
                    }

                    let prixVIP = Math.round(product.prix * (1 - (pourcentageVIP / 100)));
                    let badgeVIP = `<div class="absolute top-4 right-4 bg-[#F9A825] text-gray-900 font-display text-xl py-1 px-4 rounded-full z-10 shadow-lg tracking-wider">VIP -${pourcentageVIP}%</div>`;

                    container.innerHTML += `
                    <div class="product-card bg-gray-800 rounded-[20px] overflow-hidden flex flex-col relative border border-gray-700 animate-fade-up">
                        ${badgeVIP}
                        ${badgePromo}
                        
                        <div class="product-image-container h-[250px] w-full">
                            <img src="${product.image}" class="product-image w-full h-full object-cover opacity-90" referrerpolicy="no-referrer">
                        </div>
                        <div class="p-6 flex flex-col flex-grow">
                            <h3 class="text-2xl font-display text-white mb-2 tracking-wide">${product.nom}</h3>
                            <p class="text-gray-400 text-sm mb-6 flex-grow leading-relaxed">${product.description}</p>
                            <div class="flex justify-between items-center mt-auto">
                                <div class="flex flex-col">
                                    <span class="text-sm line-through text-gray-500">${product.prix} FCFA</span>
                                    <span class="text-xl font-bold text-[#F9A825]">${prixVIP} FCFA</span>
                                </div>
                                <button onclick="addToCart(${product.id}, ${prixVIP})" class="bg-[#27ae60] text-white font-bold py-2 px-6 rounded-full hover:bg-opacity-90 transition-colors text-sm uppercase shadow-md">
                                    Ajouter
                                </button>
                            </div>
                        </div>
                    </div>`;
                });
            } catch (error) {
                console.error("Erreur de chargement VIP :", error);
            }
        }
        document.addEventListener('DOMContentLoaded', chargerProduitsVIP);
    </script>
    <?php endif; ?>
    <script src="/js/page-loader.js"></script>
</body>
</html>