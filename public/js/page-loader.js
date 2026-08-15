/**
 * Loader de transition - Élimine le flash visuel au chargement
 * Affiche le logo puis fondu au chargement du DOM
 */
(function() {
    function hideLoader() {
        var overlay = document.getElementById('page-transition');
        var loader = document.getElementById('page-loader');
        if (overlay) {
            overlay.classList.add('loaded');
            setTimeout(function() { overlay.style.display = 'none'; }, 450);
        }
        if (loader) {
            loader.style.opacity = '0';
            setTimeout(function() { loader.style.display = 'none'; }, 300);
        }
        document.body.classList.remove('prevent-flash', 'preload-flash');
        document.body.classList.add('loaded', 'loaded-content');
    }
    if (document.readyState === 'loading') {
        document.addEventListener('DOMContentLoaded', hideLoader);
    } else {
        hideLoader();
    }
})();
