/* ==========================================================
   1. VARIABLES GLOBALES ET CONFIGURATION
   ========================================================== */
let produitsBase = [];
let cart = JSON.parse(localStorage.getItem('cart')) || [];
let categorieActuelle = 'Tout';
let rechercheActuelle = '';
let currentProductForModal = null;
let prixCalculeModal = 0;

// Coordonnées de ta boutique (Exemple : Centre de Parakou)
const BOUTIQUE_LAT = 9.3372; 
const BOUTIQUE_LON = 2.6303;

/* ==========================================================
   2. MOTEUR DE CALCUL (GPS & LIVRAISON)
   ========================================================== */
async function calculerLivraison() {
    return new Promise((resolve) => {
        navigator.geolocation.getCurrentPosition(async (position) => {
            const clientLat = position.coords.latitude;
            const clientLon = position.coords.longitude;

            const R = 6371; // Rayon de la terre en km
            const dLat = (clientLat - BOUTIQUE_LAT) * Math.PI / 180;
            const dLon = (clientLon - BOUTIQUE_LON) * Math.PI / 180;
            const a = Math.sin(dLat/2) * Math.sin(dLat/2) +
                      Math.cos(BOUTIQUE_LAT * Math.PI / 180) * Math.cos(clientLat * Math.PI / 180) * Math.sin(dLon/2) * Math.sin(dLon/2);
            const c = 2 * Math.atan2(Math.sqrt(a), Math.sqrt(1-a));
            const distance = R * c; 

            try {
                const res = await fetch('tarif_livraison.json');
                const config = await res.json();
                const frais = Math.round(distance * config.prix_km);
                resolve({ distance: distance.toFixed(1), frais: frais });
            } catch(e) {
                resolve({ distance: distance.toFixed(1), frais: Math.round(distance * 100) });
            }
        }, () => {
            alert("Veuillez autoriser la localisation pour calculer les frais de livraison.");
            resolve({ distance: 0, frais: 500 });
        });
    });
}

/* ==========================================================
   3. INJECTION AUTOMATIQUE DES MODALES (DESIGN CONSERVÉ)
   ========================================================== */
function injecterModalOptions() {
    if (!document.querySelector('script[src*="kkiapay"]')) {
        const kkiapayScript = document.createElement('script');
        kkiapayScript.src = "https://cdn.kkiapay.me/k.js";
        document.head.appendChild(kkiapayScript);
    }

    if (!document.getElementById('options-modal')) {
        const modalHTML = `
        <div id="options-modal" class="fixed inset-0 bg-black/80 backdrop-blur-sm z-[70] flex justify-center items-end sm:items-center hidden transition-all">
            <div class="bg-white w-full max-w-md sm:rounded-3xl rounded-t-3xl p-6 relative shadow-2xl animate-fade-up">
                <button onclick="closeOptionsModal()" class="absolute top-4 right-4 w-8 h-8 bg-gray-100 rounded-full text-gray-500 hover:bg-red-100 hover:text-red-500 transition-colors flex items-center justify-center">
                    <i class="fas fa-times"></i>
                </button>
                <h2 id="modal-title" class="text-3xl font-display uppercase text-[#333333] mb-2 pr-8">Personnalisez</h2>
                <p id="modal-subtitle" class="text-sm text-[#27ae60] font-bold mb-6">Prix de base : <span id="modal-base-price"></span> FCFA</p>
                <div id="modal-dynamic-content" class="mb-8 space-y-5"></div>
                <div class="flex justify-between items-center border-t pt-4">
                    <div>
                        <span class="text-xs text-gray-500 uppercase font-bold block">Sous-total</span>
                        <span id="modal-calculated-price" class="text-2xl font-bold text-[#F9A825]">0 FCFA</span>
                    </div>
                    <button onclick="confirmAddToCart()" class="bg-[#27ae60] text-white font-bold py-3 px-8 rounded-full hover:bg-opacity-90 transition-all shadow-md flex items-center gap-2 uppercase tracking-wider">
                        <i class="fas fa-cart-plus"></i> Valider
                    </button>
                </div>
            </div>
        </div>`;
        document.body.insertAdjacentHTML('beforeend', modalHTML);
    }

    if (!document.getElementById('cart-modal')) {
        const cartModalHTML = `
        <div id="cart-modal" class="fixed inset-0 bg-black/60 backdrop-blur-sm z-[60] flex justify-end hidden">
            <div class="bg-white w-full max-w-md h-full shadow-2xl flex flex-col">
                <div class="p-6 border-b flex justify-between items-center bg-[#f4fbf7]">
                    <h2 class="text-3xl font-display tracking-wider text-[#333333] flex items-center gap-3"><i class="fas fa-shopping-cart text-[#27ae60]"></i> Panier</h2>
                    <button onclick="closeCart()" class="p-2 text-gray-500 hover:bg-red-100 hover:text-red-500 rounded-full transition-colors"><i class="fas fa-times text-2xl"></i></button>
                </div>
                <div id="cart-items" class="flex-grow overflow-y-auto p-6"></div>
                <div id="checkout-area" class="p-6 border-t bg-white shadow-inner hidden">
                    <div class="flex justify-between items-center mb-6 text-lg"><span class="font-display text-2xl tracking-wider text-[#333333]">Total</span><span id="cart-total" class="font-bold text-[#27ae60] text-2xl">0 FCFA</span></div>
                    <button onclick="handleCheckout()" class="w-full bg-[#27ae60] text-white font-bold py-4 rounded-full hover:scale-[1.02] transition-all shadow-lg text-lg uppercase tracking-wider">Passer la Commande</button>
                </div>
            </div>
        </div>`;
        document.body.insertAdjacentHTML('beforeend', cartModalHTML);
    }
}

/* ==========================================================
   4. CHARGEMENT DU CATALOGUE & PROMOTIONS (FIXÉ)
   ========================================================== */
async function chargerProduits() {
    const allContainer = document.getElementById('all-products-container');
    const homeContainer = document.getElementById('home-products-container');
    const promoContainer = document.getElementById('promo-products-container');

    try {
        const response = await fetch('get_produits.php');
        if (!response.ok) throw new Error("Fichier produits.json introuvable");
        const data = await response.json();

        produitsBase = data.filter(p => p.valide === true || p.valide === undefined);

        if (allContainer) appliquerFiltres();
        if (homeContainer) afficherProduits(produitsBase.slice(0, 6), homeContainer);
        
        //  Affichage des promotions
        if (promoContainer) {
            // ✅ On filtre avec la colonne MySQL 'en_promo' qui vaut 1
            const produitsEnPromo = produitsBase.filter(p => p.en_promo == 1 || p.en_promo == "1");
            
            if (produitsEnPromo.length === 0) {
                promoContainer.innerHTML = `
                    <div class="col-span-full text-center py-20 opacity-50">
                        <i class="fas fa-percentage text-5xl mb-4 text-gray-300"></i>
                        <p class="text-gray-400 font-bold uppercase tracking-widest text-xs">Aucune promotion active pour le moment</p>
                    </div>`;
            } else {
                afficherProduits(produitsEnPromo, promoContainer);
            }
}
    } catch (error) {
        console.error("Erreur catalogue:", error);
    }
}



/* ==========================================================
   5. FILTRAGE (RECHERCHE & CATÉGORIES)
   ========================================================== */
function changerCategorie(categorie) {
    categorieActuelle = categorie;
    const boutons = document.querySelectorAll('.cat-btn');
    boutons.forEach(btn => {
        if(btn.innerText.includes(categorie) || (categorie === 'Tout' && btn.innerText === 'Tout')) {
            btn.className = "cat-btn active bg-[#27ae60] text-white px-5 py-2 rounded-full font-bold text-sm shadow-md transition-all";
        } else {
            btn.className = "cat-btn bg-white text-gray-600 border border-gray-200 px-5 py-2 rounded-full font-bold text-sm hover:bg-gray-50 transition-all";
        }
    });
    appliquerFiltres();
}

function filtrerProduits() {
    rechercheActuelle = document.getElementById('search-bar').value.toLowerCase();
    appliquerFiltres();
}

function appliquerFiltres() {
    const container = document.getElementById('all-products-container');
    if (!container) return;
    let produitsFiltres = produitsBase;
    
    if (categorieActuelle !== 'Tout') { produitsFiltres = produitsFiltres.filter(p => p.categorie === categorieActuelle); }
    if (rechercheActuelle !== '') {
        produitsFiltres = produitsFiltres.filter(p => p.nom.toLowerCase().includes(rechercheActuelle) || p.description.toLowerCase().includes(rechercheActuelle));
    }
    if (produitsFiltres.length === 0) { container.innerHTML = `<p class="col-span-full text-center py-10 text-gray-500 text-xl">Aucun produit trouvé.</p>`; } 
    else { afficherProduits(produitsFiltres, container); }
}

/* ==========================================================
   6. AFFICHAGE DES CARTES PRODUITS (DESIGN D'ORIGINE)
   ========================================================== */
function afficherProduits(produits, container) {
    container.innerHTML = '';
    produits.forEach(product => {
        let borderClass = "border-gray-100"; 
        let promoBadge = "";
        let priceHTML = `<span class="text-2xl font-bold text-[#27ae60]">${product.prix} FCFA</span>`;

        if (product.en_promo == 1) {
            borderClass = "border-red-500 border-2"; 
            const prixPromo = Math.round(product.prix * (1 - (product.pourcentage_promo / 100)));
            promoBadge = `
                <div class="absolute top-2 right-2 bg-red-500 text-white font-bold text-xs py-1 px-2 rounded-full z-10">-${product.pourcentage_promo}%</div>
                <div class="absolute top-2 left-2 bg-[#F9A825] text-white font-bold text-[10px] py-1 px-3 rounded-md z-10 uppercase">${product.nom_promo}</div>
            `;
            priceHTML = `
                <div class="flex flex-col">
                    <span class="text-xs line-through text-gray-400">${product.prix} FCFA</span>
                    <span class="text-2xl font-bold text-red-500">${prixPromo} FCFA</span>
                </div>
            `;
        }

        container.innerHTML += `
            <div class="group bg-white rounded-[20px] overflow-hidden flex flex-col shadow-sm hover:shadow-xl transition-all border ${borderClass} animate-fade-up relative">
                ${promoBadge}
                <div class="h-[220px] w-full bg-gray-50 flex items-center justify-center overflow-hidden">
                    <img src="${product.image}" class="w-full h-full object-cover transition-transform duration-500 ease-in-out group-hover:scale-110" referrerpolicy="no-referrer">
                </div>
                <div class="p-5 flex flex-col flex-grow">
                    <div class="flex items-center gap-2 mb-2">
                        <i class="fas fa-tag text-[#27ae60] text-[10px]"></i>
                        <span class="text-[10px] font-bold uppercase text-[#27ae60] tracking-widest">${product.categorie}</span>
                    </div>
                    <h3 class="text-xl font-bold text-[#333333] uppercase mb-1 truncate">${product.nom}</h3>
                    <p class="text-gray-500 text-xs mb-6 line-clamp-2 italic">${product.description}</p>
                    <div class="flex justify-between items-center mt-auto">
                        ${priceHTML}
                        <button onclick="openOptionsModal(${product.id})" class="bg-[#27ae60] text-white font-bold py-3 px-6 rounded-xl hover:bg-opacity-90 transition-all shadow-md flex items-center gap-2 text-xs uppercase">
                            <i class="fas fa-cart-plus"></i> Ajouter
                        </button>
                    </div>
                </div>
            </div>`;
    });
}


    /* ==========================================================
   7. LOGIQUE DU POP-UP DE PERSONNALISATION
   ========================================================== */
function openOptionsModal(productId) {
    console.log("Clic détecté pour le produit : " + productId);
    // 🛡️ 1. LA BARRIÈRE DE SÉCURITÉ (Unique et propre)
    // On vérifie si la variable existe ET si elle est à false
    if (typeof isConnected === 'undefined' || isConnected === false) {
        alert("🚨 Stop ! Pour commander ces délices, vous devez d'abord vous connecter.");
        window.location.href = 'connexion.php';
        return; 
    }

    // 🔍 2. RECHERCHE DU PRODUIT
    currentProductForModal = produitsBase.find(p => p.id == productId);
    if (!currentProductForModal) return;

    // 💰 3. CALCUL DU PRIX (Promo ou Normal)
    let prixDeBaseAffichage = currentProductForModal.prix;
    if (currentProductForModal.promo) { 
        prixDeBaseAffichage = Math.round(currentProductForModal.prix * (1 - (currentProductForModal.promo.pourcentage / 100))); 
    }

    // 📝 4. MISE À JOUR DU TEXTE DANS LA MODALE
    document.getElementById('modal-title').innerText = currentProductForModal.nom;
    document.getElementById('modal-base-price').innerText = prixDeBaseAffichage;
    const content = document.getElementById('modal-dynamic-content');

    // 🥩 5. LOGIQUE DYNAMIQUE (Viande / Poisson / Standard)
    if (currentProductForModal.categorie === 'Viandes et Poissons' && currentProductForModal.sous_categorie === 'viande') {
        content.innerHTML = `
            <div class="bg-gray-50 p-4 rounded-xl border border-gray-200">
                <p class="font-bold text-sm mb-3 text-[#333333]">Préparation :</p>
                <div class="flex gap-4">
                    <label class="flex items-center gap-2 cursor-pointer font-medium"><input type="radio" name="etat" value="vivant" onchange="buildViandeOptions()" class="w-5 h-5 accent-[#27ae60]" checked> Vivant</label>
                    <label class="flex items-center gap-2 cursor-pointer font-medium"><input type="radio" name="etat" value="abattu" onchange="buildViandeOptions()" class="w-5 h-5 accent-[#27ae60]"> Abattu / Nettoyé</label>
                </div>
            </div>
            <div id="viande-sub-options" class="space-y-4 mt-4"></div>`;
        buildViandeOptions();
    } else if (currentProductForModal.categorie === 'Viandes et Poissons' && currentProductForModal.sous_categorie === 'poisson') {
        content.innerHTML = `
            <div><label class="text-sm font-bold block mb-2 text-[#333333]">Poids (Kg)</label><input type="number" id="opt_poids" value="1" min="0.5" step="0.5" oninput="calculateModalPrice()" class="w-full p-4 border border-gray-300 rounded-xl bg-gray-50 text-lg focus:border-[#27ae60]"></div>
            <div class="mt-4"><label class="text-sm font-bold block mb-2 text-[#333333]">Nettoyage / Découpe</label><input type="text" id="opt_decoupe" placeholder="Ex: Entier nettoyé" class="w-full p-4 border border-gray-300 rounded-xl bg-gray-50 focus:border-[#27ae60]"></div>`;
        calculateModalPrice();
    } else {
        content.innerHTML = `
            <div>
                <label class="text-sm font-bold block mb-2 text-[#333333]">Quantité</label>
                <div class="flex items-center gap-4">
                    <button onclick="document.getElementById('opt_qty').stepDown(); calculateModalPrice();" class="w-12 h-12 rounded-full bg-gray-200 font-bold text-xl hover:bg-gray-300 transition-colors">-</button>
                    <input type="number" id="opt_qty" value="1" min="1" oninput="calculateModalPrice()" class="w-20 p-3 border border-gray-300 rounded-xl bg-gray-50 text-xl text-center font-bold focus:border-[#27ae60] focus:outline-none">
                    <button onclick="document.getElementById('opt_qty').stepUp(); calculateModalPrice();" class="w-12 h-12 rounded-full bg-gray-200 font-bold text-xl hover:bg-gray-300 transition-colors">+</button>
                </div>
            </div>`;
        calculateModalPrice();
    }

    // 🎥 6. AFFICHAGE
    document.getElementById('options-modal').classList.remove('hidden');
}

function buildViandeOptions() {
    const etat = document.querySelector('input[name="etat"]:checked').value;
    const sub = document.getElementById('viande-sub-options');
    let coefPromo = currentProductForModal.promo ? 1 - (currentProductForModal.promo.pourcentage / 100) : 1;

    if (etat === 'vivant') {
        const prixM = Math.round((currentProductForModal.tarifs?.male || 0) * coefPromo);
        const prixF = Math.round((currentProductForModal.tarifs?.femelle || 0) * coefPromo);
        sub.innerHTML = `
            <div class="grid grid-cols-2 gap-4">
                <div class="bg-blue-50 p-4 rounded-xl border border-blue-200"><label class="text-sm font-bold text-blue-900 block mb-1">Mâle(s)</label><span class="text-xs text-blue-700 block mb-3 font-bold">${prixM} FCFA/tête</span><input type="number" id="opt_males" value="0" min="0" oninput="calculateModalPrice()" class="w-full p-3 border border-blue-300 rounded-lg text-center font-bold text-lg"></div>
                <div class="bg-pink-50 p-4 rounded-xl border border-pink-200"><label class="text-sm font-bold text-pink-900 block mb-1">Femelle(s)</label><span class="text-xs text-pink-700 block mb-3 font-bold">${prixF} FCFA/tête</span><input type="number" id="opt_femelles" value="0" min="0" oninput="calculateModalPrice()" class="w-full p-3 border border-pink-300 rounded-lg text-center font-bold text-lg"></div>
            </div>`;
    } else {
        const prixAbattu = Math.round((currentProductForModal.tarifs?.abattu || currentProductForModal.prix) * coefPromo);
        sub.innerHTML = `
            <div class="mb-4"><label class="text-sm font-bold block mb-1 text-[#333333]">Poids (Kg)</label><span class="text-xs text-gray-500 block mb-2 font-bold">${prixAbattu} FCFA / Kg</span><input type="number" id="opt_poids" value="1" min="0.5" step="0.5" oninput="calculateModalPrice()" class="w-full p-4 border border-gray-300 rounded-xl bg-white text-lg"></div>
            <div><label class="text-sm font-bold block mb-1 text-[#333333]">Découpe</label><input type="text" id="opt_decoupe" placeholder="Ex: Entier, coupé en 4..." class="w-full p-4 border border-gray-300 rounded-xl bg-white"></div>`;
    }
    calculateModalPrice();
}

function calculateModalPrice() {
    prixCalculeModal = 0;
    let coefPromo = currentProductForModal.promo ? 1 - (currentProductForModal.promo.pourcentage / 100) : 1;

    if (currentProductForModal.categorie === 'Viandes et Poissons' && currentProductForModal.sous_categorie === 'viande') {
        if (document.querySelector('input[name="etat"]:checked').value === 'vivant') {
            const m = parseInt(document.getElementById('opt_males').value) || 0;
            const f = parseInt(document.getElementById('opt_femelles').value) || 0;
            prixCalculeModal = (m * Math.round((currentProductForModal.tarifs?.male||0)*coefPromo)) + (f * Math.round((currentProductForModal.tarifs?.femelle||0)*coefPromo));
        } else {
            const kg = parseFloat(document.getElementById('opt_poids').value) || 0;
            prixCalculeModal = kg * Math.round((currentProductForModal.tarifs?.abattu || currentProductForModal.prix)*coefPromo);
        }
    } else if (currentProductForModal.categorie === 'Viandes et Poissons' && currentProductForModal.sous_categorie === 'poisson') {
        const kg = parseFloat(document.getElementById('opt_poids').value) || 0;
        prixCalculeModal = kg * Math.round(currentProductForModal.prix * coefPromo);
    } else {
        const qty = parseInt(document.getElementById('opt_qty').value) || 0;
        prixCalculeModal = qty * Math.round(currentProductForModal.prix * coefPromo);
    }
    document.getElementById('modal-calculated-price').innerText = prixCalculeModal + " FCFA";
}

function closeOptionsModal() { document.getElementById('options-modal').classList.add('hidden'); currentProductForModal = null; }

/* ==========================================================
   8. GESTION DU PANIER (AJOUT & AFFICHAGE)
   ========================================================== */
function confirmAddToCart() {
    if (prixCalculeModal <= 0) { alert("Veuillez sélectionner une quantité valide."); return; }
    let detailsText = ""; let quantiteLogique = 1;

    if (currentProductForModal.categorie === 'Viandes et Poissons' && currentProductForModal.sous_categorie === 'viande') {
        if (document.querySelector('input[name="etat"]:checked').value === 'vivant') {
            const m = parseInt(document.getElementById('opt_males').value) || 0; 
            const f = parseInt(document.getElementById('opt_femelles').value) || 0;
            detailsText = `Vivant (${m} Mâle(s), ${f} Femelle(s))`; quantiteLogique = m + f;
        } else {
            const kg = parseFloat(document.getElementById('opt_poids').value) || 0; 
            const dec = document.getElementById('opt_decoupe').value || 'Entier';
            detailsText = `Abattu (${kg} kg) - Découpe: ${dec}`; quantiteLogique = 1; 
        }
    } else if (currentProductForModal.categorie === 'Viandes et Poissons' && currentProductForModal.sous_categorie === 'poisson') {
        const kg = parseFloat(document.getElementById('opt_poids').value) || 0; 
        const dec = document.getElementById('opt_decoupe').value || 'Entier';
        detailsText = `${kg} kg - Découpe: ${dec}`; quantiteLogique = 1;
    } else {
        quantiteLogique = parseInt(document.getElementById('opt_qty').value) || 1; 
        detailsText = `Quantité: x${quantiteLogique}`;
    }

    cart.push({ 
        id_unique: Date.now().toString(), 
        product: currentProductForModal, 
        quantity: quantiteLogique, 
        custom_price: prixCalculeModal, 
        details: detailsText 
    });
    
    localStorage.setItem('cart', JSON.stringify(cart));
    updateCartUI();
    closeOptionsModal();
}

function removeFromCart(id) { 
    cart = cart.filter(i => i.id_unique !== id); 
    localStorage.setItem('cart', JSON.stringify(cart)); 
    updateCartUI(); 
}

function toggleCart() { document.getElementById('cart-modal').classList.toggle('hidden'); renderPanier(); }
function closeCart() { document.getElementById('cart-modal').classList.add('hidden'); }

/* ==========================================================
   9. INTERFACE DU PANIER ET SUIVI DE COMMANDE
   ========================================================== */
function renderPanier() {
    const container = document.getElementById('cart-items');
    const badge = document.getElementById('cart-count');
    const checkoutArea = document.getElementById('checkout-area');
    const idEnCours = localStorage.getItem('commande_active_id');
    const statutActuel = localStorage.getItem('commande_active_statut');


    if (idEnCours && cart.length === 0) {
        if(badge) {
            badge.innerHTML = '<i class="fas fa-bell animate-swing text-[10px]"></i>';
            badge.style.backgroundColor = "#F9A825";
            badge.classList.remove('hidden');
        }
        if(checkoutArea) checkoutArea.classList.add('hidden');

        if (statutActuel === "Livré") {
            container.innerHTML = `
                <div class="p-8 text-center space-y-4 animate-fade-up">
                    <div class="text-6xl mb-4">✅</div>
                    <h2 class="font-black text-[#27ae60] uppercase text-sm tracking-widest">Commande Reçue !</h2>
                    <p class="text-[11px] text-gray-500 italic">Merci pour votre confiance.</p>
                    <button onclick="reinitialiserTout()" class="w-full bg-[#333] text-white py-4 rounded-xl font-bold uppercase text-[10px] mt-4 shadow-lg">Nouvelle commande</button>
                </div>`;
        } else {
            container.innerHTML = `
                <div class="p-8 text-center space-y-6">
                    <div class="text-5xl text-[#27ae60] animate-bounce"><i class="fas fa-motorcycle"></i></div>
                    <div class="bg-gray-50 border-2 border-dashed border-gray-200 rounded-[30px] p-6">
                        <p class="text-[10px] font-bold text-gray-400 uppercase mb-2">Statut de livraison</p>
                        <p class="text-xl font-black text-[#27ae60] uppercase animate-pulse">${statutActuel || 'En attente'}</p>
                    </div>
                </div>`;
        }
    } else {
        updateCartUI();
    }
}

function updateCartUI() {
    const container = document.getElementById('cart-items');
    const badge = document.getElementById('cart-count');
    const totalEl = document.getElementById('cart-total');
    const checkoutArea = document.getElementById('checkout-area');
    if(!container) return;

    if (cart.length === 0) {
        container.innerHTML = `<div class="text-center py-20 opacity-30"><i class="fas fa-shopping-basket text-5xl mb-4"></i><p>Panier vide</p></div>`;
        if(badge) badge.classList.add('hidden');
        if(checkoutArea) checkoutArea.classList.add('hidden');
    } else {
        if(badge) {
            badge.classList.remove('hidden');
            badge.innerText = cart.length;
            badge.style.backgroundColor = "#27ae60";
        }
        if(checkoutArea) checkoutArea.classList.remove('hidden');
        
        container.innerHTML = '';
        let total = 0;
        cart.forEach(item => {
            total += item.custom_price;
            container.innerHTML += `
                <div class="flex gap-4 border-b border-gray-100 pb-4 mb-4 relative">
                    <img src="${item.product.image}" class="w-16 h-16 rounded-xl object-cover">
                    <div class="pr-8">
                        <h4 class="font-bold text-sm">${item.product.nom}</h4>
                        <p class="text-[10px] text-[#27ae60] font-bold uppercase">${item.details}</p>
                        <p class="font-black text-[#F9A825]">${item.custom_price} F</p>
                    </div>
                    <button onclick="removeFromCart('${item.id_unique}')" class="absolute top-0 right-0 text-red-200 hover:text-red-500"><i class="fas fa-trash-alt"></i></button>
                </div>`;
        });
        if(totalEl) totalEl.innerText = total + ' FCFA';
    }
}

/* ==========================================================
   10. PAIEMENT ET FINALISATION
   ========================================================== */
function handleCheckout() {
    if (cart.length === 0) return;
    const total = cart.reduce((sum, item) => sum + item.custom_price, 0);

    const choiceHTML = `
    <div id="payment-choice-modal" class="fixed inset-0 bg-black/80 backdrop-blur-sm z-[100] flex justify-center items-center p-4">
        <div class="bg-white w-full max-w-sm rounded-[30px] p-8 text-center shadow-2xl border-t-8 border-[#27ae60]">
            <h3 class="text-xl font-black uppercase mb-6 text-[#333]">Finaliser</h3>
            <button onclick="processKkiapay(${total})" class="w-full bg-[#27ae60] text-white py-4 rounded-xl mb-3 font-bold uppercase text-xs shadow-lg">Payer via Mobile Money</button>
            <button onclick="document.getElementById('payment-choice-modal').remove(); finaliserLaCommande('À LA LIVRAISON')" class="w-full bg-gray-100 text-gray-800 py-4 rounded-xl font-bold uppercase text-xs">Payer à la livraison</button>
            <button onclick="document.getElementById('payment-choice-modal').remove()" class="mt-4 text-xs text-gray-400 font-bold uppercase tracking-widest">Retour</button>
        </div>
    </div>`;
    document.body.insertAdjacentHTML('beforeend', choiceHTML);
}

function processKkiapay(amount) {
    const modal = document.getElementById('payment-choice-modal');
    if(modal) modal.remove();
    
    // 1. On ouvre le widget normalement
    openKkiapayWidget({
        amount: amount,
        position: "center",
        data: "Commande La Réussite",
        key: "5f3f148021db11f1b0a7d307afe1a8d2", 
        sandbox: true
    });

    // 2. LA SENTINELLE : On vérifie l'état du widget toutes les secondes
    const checkWidget = setInterval(() => {
        const widget = document.querySelector('iframe[src*="kkiapay"]');
        
        // Si le widget n'est plus là (fermé par l'utilisateur ou par le succès)
        if (!widget) {
            clearInterval(checkWidget);
            console.log("Widget fermé, passage à la suite...");
            onKkiapaySuccess({ status: "check_server" });
        }
    }, 1000);

    // On garde quand même l'écouteur au cas où il se réveille
    addKkiapayListener('success', (response) => {
        clearInterval(checkWidget);
        const widget = document.querySelector('iframe[src*="kkiapay"]');
        if(widget) widget.remove();
        onKkiapaySuccess(response);
    });
}





function onKkiapaySuccess(response) {
    console.log("Paiement réussi via Kkiapay !", response);
    // On appelle notre fonction habituelle en précisant que c'est déjà payé
    finaliserLaCommande("PAYÉ (MOBILE MONEY)", true); // true = ouvrir WhatsApp via modal (évite blocage popup)
}

async function finaliserLaCommande(methode, ouvrirWhatsAppViaModal) {
    const livraison = await calculerLivraison(); 
    
    const totalProduits = cart.reduce((sum, item) => sum + item.custom_price, 0);
    const totalFinal = totalProduits + livraison.frais;
    const commandeID = Date.now().toString(); 

    const statutComplet = (methode === 'PAYÉ (MOBILE MONEY)') ? 'PAYÉ - En attente' : 'En attente';

    // 1. Envoi à la base de données MySQL
    const formData = new FormData();
    formData.append('id', commandeID);
    formData.append('quartier', 'GPS (' + livraison.distance + ' km)');
    formData.append('total', totalFinal);
    formData.append('details', JSON.stringify(cart));
    formData.append('statut', statutComplet);
    formData.append('frais_livraison', livraison.frais);

    await fetch('sauvegarder_commande.php', { method: 'POST', body: formData });

    // 2. Enregistrement LOCAL pour le suivi client automatique
    localStorage.setItem('commande_active_id', commandeID);
    localStorage.setItem('commande_active_statut', statutComplet);
    localStorage.setItem('commande_distance', livraison.distance);
    localStorage.setItem('commande_frais', livraison.frais);

    // 3. Préparation du message WhatsApp
    let entete = (methode === 'PAYÉ (MOBILE MONEY)') ? `✅ *COMMANDE PAYÉE (KKIAPAY)*` : `🧾 *BON DE COMMANDE*`;
    let message = `${entete}\n`;
    message += `🆔 Commande : #${commandeID}\n`;
    message += `🛒 Articles : ${totalProduits} F\n`;
    message += `🛵 Livraison (${livraison.distance} km) : ${livraison.frais} F\n`;
    message += `💰 *TOTAL : ${totalFinal} FCFA*\n\n`;
    message += `📍 Type : ${methode}`;

    // 4. Nettoyage du panier
    cart = [];
    localStorage.removeItem('cart');

    // 5. OUVERTURE WHATSAPP
    const whatsappUrl = `https://wa.me/2290167424373?text=${encodeURIComponent(message)}`;
    if (ouvrirWhatsAppViaModal) {
        // Après paiement en ligne : le navigateur bloque souvent window.open dans un callback.
        // On affiche un bouton que l'utilisateur clique = action directe = pas de blocage.
        const modalHTML = `
        <div id="whatsapp-modal" class="fixed inset-0 bg-black/80 backdrop-blur-sm z-[110] flex justify-center items-center p-4">
            <div class="bg-white w-full max-w-sm rounded-[30px] p-8 text-center shadow-2xl border-t-8 border-[#25D366]">
                <div class="text-6xl mb-4">✅</div>
                <h3 class="text-xl font-black uppercase mb-2 text-[#333]">Paiement réussi !</h3>
                <p class="text-sm text-gray-600 mb-6">Cliquez pour confirmer votre commande via WhatsApp</p>
                <a href="${whatsappUrl}" target="_blank" onclick="document.getElementById('whatsapp-modal').remove(); setTimeout(()=>location.reload(), 300);" class="block w-full bg-[#25D366] text-white py-4 rounded-xl font-bold uppercase text-sm shadow-lg hover:bg-[#20bd5a] transition-colors">
                    <i class="fab fa-whatsapp mr-2"></i> Ouvrir WhatsApp
                </a>
                <button onclick="document.getElementById('whatsapp-modal').remove(); location.reload();" class="mt-4 text-xs text-gray-400 font-bold uppercase">Fermer</button>
            </div>
        </div>`;
        document.body.insertAdjacentHTML('beforeend', modalHTML);
    } else {
        window.open(whatsappUrl, '_blank');
        setTimeout(() => { location.reload(); }, 500);
    }
}



/* ==========================================================
   11. INITIALISATION ET SURVEILLANCE LIVE
   ========================================================== */
function reinitialiserTout() {
    localStorage.clear();
    location.reload();
}

document.addEventListener('DOMContentLoaded', () => {
    injecterNavigation();
    injecterModalOptions();
    chargerProduits();
    renderPanier();
    if(typeof marquerPageActive === 'function') marquerPageActive();
    if(typeof injecterBulleWhatsApp === 'function') injecterBulleWhatsApp();
    if(typeof activerMenuMobile === 'function') activerMenuMobile();
});

setInterval(async () => {
    const id = localStorage.getItem('commande_active_id');
    if (!id) return;
    try {
        // ✅ On interroge un nouveau fichier PHP que nous allons créer
        const res = await fetch(`check_statut.php?id=${id}`);
        const nouveauStatut = await res.text();
        
        if (nouveauStatut && nouveauStatut !== localStorage.getItem('commande_active_statut')) {
            localStorage.setItem('commande_active_statut', nouveauStatut);
            renderPanier(); // Met à jour l'affichage "En cours" ou "Livré"
        }
    } catch (e) {}
}, 5000);


function injecterNavigation() {
    const navContainer = document.querySelector('nav');
    if (!navContainer) return;

    // 1. Détecter la page actuelle
    const path = window.location.pathname;
    const page = path.split("/").pop() || "index.php";

    // 2. Définir les onglets
    const liens = [
        { nom: 'Accueil', url: 'index.php' },
        { nom: 'Produits', url: 'produits.php' },
        { nom: 'Promos', url: 'promos.php', special: 'text-red-500 animate-pulse' },
        { nom: 'À Propos', url: 'apropos.php' }
    ];

    // 3. Construire le HTML des onglets
    let htmlContent = '';
    liens.forEach(lien => {
        const isActive = (page === lien.url) ? 'text-[#27ae60] border-b-2 border-[#27ae60]' : 'text-gray-600 hover:text-[#27ae60]';
        const classeSpeciale = lien.special || '';
        htmlContent += `<a href="${lien.url}" class="pb-1 transition-all font-bold ${isActive} ${classeSpeciale}">${lien.nom}</a>`;
    });

    // 4. Ajouter le SÉPARATEUR pour éloigner la zone de connexion
    htmlContent += `<div class="h-6 w-[1px] bg-gray-200 mx-6 hidden md:block"></div>`;

    // 5. Zone de connexion (en utilisant la variable isConnected qu'on a créée)
    if (typeof isConnected !== 'undefined' && isConnected) {
        // Menu si connecté (avec l'initiale)
        htmlContent += `
            <div class="flex items-center gap-3 bg-gray-50 px-3 py-1 rounded-full border border-gray-100 cursor-pointer" onclick="openProfile()">
                <div class="w-8 h-8 bg-[#27ae60] text-white rounded-full flex items-center justify-center font-black shadow-sm text-xs">
                    ${userInitial || 'U'}
                </div>
                <span class="text-[10px] font-bold text-gray-400 uppercase hidden sm:block">Mon Espace</span>
            </div>`;
    } else {
        // Boutons Inscription et Connexion ÉLOIGNÉS
        htmlContent += `
            <div class="flex items-center gap-6">
                <a href="inscription.php" class="text-gray-400 hover:text-gray-800 transition-colors lowercase italic text-xs">S'inscrire</a>
                <a href="connexion.php" class="bg-[#27ae60] text-white px-8 py-2.5 rounded-full shadow-lg hover:scale-105 transition-all text-xs font-bold uppercase tracking-widest">Connexion</a>
            </div>`;
    }

    navContainer.innerHTML = htmlContent;
    navContainer.className = "hidden md:flex items-center gap-8 font-bold text-sm uppercase";
}



/* ==========================================================
   12. Gerer le profil utilisateur et les commandes en cours (BASIQUE)
   ========================================================== */


   async function openProfile() {
    const drawer = document.getElementById('profile-drawer');
    const overlay = document.getElementById('profile-overlay');
    
    // 1. Affichage du panneau
    drawer.classList.remove('translate-x-full');
    overlay.classList.remove('hidden');

    // 2. Récupération des infos via un petit fichier PHP
    try {
        const response = await fetch('get_user_info.php');
        const user = await response.json();

        if(user.error) {
            window.location.href = 'connexion.php';
            return;
        }

        // 3. Mise à jour du design
        document.getElementById('prof-nom').innerText = user.nom;
        document.getElementById('prof-email').innerText = user.email;
        document.getElementById('prof-tel').innerText = user.telephone || 'Non renseigné';
        document.getElementById('prof-initial').innerText = user.nom.charAt(0).toUpperCase();
        
    } catch (error) {
        console.error("Erreur profil:", error);
    }
}

function closeProfile() {
    document.getElementById('profile-drawer').classList.add('translate-x-full');
    document.getElementById('profile-overlay').classList.add('hidden');
}