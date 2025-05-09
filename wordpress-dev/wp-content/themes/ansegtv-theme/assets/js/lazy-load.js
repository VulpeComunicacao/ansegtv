/**
 * Script para carregamento preguiçoso de imagens
 */
(function() {
    'use strict';

    // Função para verificar se o navegador suporta IntersectionObserver
    function supportsIntersectionObserver() {
        return 'IntersectionObserver' in window &&
               'IntersectionObserverEntry' in window &&
               'intersectionRatio' in window.IntersectionObserverEntry.prototype;
    }

    // Função para criar um fallback para navegadores que não suportam IntersectionObserver
    function createFallback() {
        const images = document.querySelectorAll('img[data-src]');
        
        function loadImage(img) {
            const src = img.getAttribute('data-src');
            if (!src) return;
            
            img.src = src;
            img.removeAttribute('data-src');
        }
        
        function handleScroll() {
            images.forEach(img => {
                if (isElementInViewport(img)) {
                    loadImage(img);
                }
            });
        }
        
        // Verificar se um elemento está na viewport
        function isElementInViewport(el) {
            const rect = el.getBoundingClientRect();
            return (
                rect.top >= 0 &&
                rect.left >= 0 &&
                rect.bottom <= (window.innerHeight || document.documentElement.clientHeight) &&
                rect.right <= (window.innerWidth || document.documentElement.clientWidth)
            );
        }
        
        // Adicionar event listeners
        window.addEventListener('scroll', handleScroll);
        window.addEventListener('resize', handleScroll);
        window.addEventListener('orientationchange', handleScroll);
        
        // Carregar imagens visíveis inicialmente
        handleScroll();
    }

    // Função para inicializar o IntersectionObserver
    function initIntersectionObserver() {
        const images = document.querySelectorAll('img[data-src]');
        
        const imageObserver = new IntersectionObserver((entries, observer) => {
            entries.forEach(entry => {
                if (entry.isIntersecting) {
                    const img = entry.target;
                    const src = img.getAttribute('data-src');
                    
                    if (src) {
                        img.src = src;
                        img.removeAttribute('data-src');
                        observer.unobserve(img);
                    }
                }
            });
        }, {
            rootMargin: '50px 0px',
            threshold: 0.01
        });
        
        images.forEach(img => imageObserver.observe(img));
    }

    // Função para inicializar o lazy loading
    function initLazyLoad() {
        if (supportsIntersectionObserver()) {
            initIntersectionObserver();
        } else {
            createFallback();
        }
    }

    // Inicializar quando o DOM estiver pronto
    document.addEventListener('DOMContentLoaded', initLazyLoad);

})(); 