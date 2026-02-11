import './stimulus_bootstrap.js';
/*
 * Welcome to your app's main JavaScript file!
 *
 * This file will be included onto the page via the importmap() Twig function,
 * which should already be in your base.html.twig.
 */

console.log('This log comes from assets/app.js - welcome to AssetMapper! 🎉');

// Hamburger menu toggle - usando múltiples métodos para asegurar que funcione
function initHamburgerMenu() {
    console.log('Inicializando menú hamburguesa...');
    const hamburger = document.getElementById('hamburger');
    const mobileMenu = document.getElementById('mobile-menu');
    
    console.log('Hamburger:', hamburger);
    console.log('Mobile Menu:', mobileMenu);
    
    if (hamburger && mobileMenu) {
        console.log('Elementos encontrados, agregando eventos...');
        
        hamburger.addEventListener('click', (e) => {
            e.preventDefault();
            e.stopPropagation();
            console.log('Click en hamburger!');
            mobileMenu.classList.toggle('hidden');
            console.log('Menu hidden:', mobileMenu.classList.contains('hidden'));
        });
        
        // Close menu when clicking on a link
        const navLinks = mobileMenu.querySelectorAll('a');
        navLinks.forEach(link => {
            link.addEventListener('click', () => {
                mobileMenu.classList.add('hidden');
            });
        });
        
        // Close menu when clicking outside
        document.addEventListener('click', (e) => {
            if (!hamburger.contains(e.target) && !mobileMenu.contains(e.target)) {
                mobileMenu.classList.add('hidden');
            }
        });
    } else {
        console.error('No se encontraron los elementos del menú');
    }
}

// Intentar inicializar en diferentes momentos
if (document.readyState === 'loading') {
    document.addEventListener('DOMContentLoaded', initHamburgerMenu);
} else {
    // DOM ya está listo
    initHamburgerMenu();
}

// También intentar después de que todo se haya cargado
window.addEventListener('load', () => {
    console.log('Window loaded, verificando menú...');
    if (!document.getElementById('hamburger').onclick) {
        initHamburgerMenu();
    }
});