/**
 * Script para o carregamento preguiçoso de imagens
 */
(function() {
    'use strict';

    // Configurações
    const config = {
        rootMargin: '50px 0px',
        threshold: 0.01
    };

    // Função para carregar a imagem
    function loadImage(image) {
        const src = image.dataset.src;
        const srcset = image.dataset.srcset;
        const sizes = image.dataset.sizes;
        
        if (!src) return;
        
        // Carregar a imagem
        const img = new Image();
        
        img.onload = function() {
            image.src = src;
            if (srcset) image.srcset = srcset;
            if (sizes) image.sizes = sizes;
            
            image.classList.add('loaded');
            image.classList.remove('lazy');
        };
        
        img.onerror = function() {
            image.classList.add('error');
            image.classList.remove('lazy');
        };
        
        img.src = src;
    }

    // Função para inicializar o lazy loading
    function initLazyLoad() {
        // Verificar suporte ao IntersectionObserver
        if ('IntersectionObserver' in window) {
            const imageObserver = new IntersectionObserver((entries, observer) => {
                entries.forEach(entry => {
                    if (entry.isIntersecting) {
                        const image = entry.target;
                        loadImage(image);
                        observer.unobserve(image);
                    }
                });
            }, config);
            
            // Observar todas as imagens com a classe 'lazy'
            document.querySelectorAll('img.lazy').forEach(image => {
                imageObserver.observe(image);
            });
        } else {
            // Fallback para navegadores que não suportam IntersectionObserver
            let lazyLoadThrottleTimeout;
            
            function lazyLoad() {
                if (lazyLoadThrottleTimeout) {
                    clearTimeout(lazyLoadThrottleTimeout);
                }
                
                lazyLoadThrottleTimeout = setTimeout(() => {
                    const scrollTop = window.pageYOffset;
                    
                    document.querySelectorAll('img.lazy').forEach(image => {
                        if (image.offsetTop < (window.innerHeight + scrollTop)) {
                            loadImage(image);
                        }
                    });
                    
                    if (document.querySelectorAll('img.lazy').length === 0) {
                        document.removeEventListener('scroll', lazyLoad);
                        window.removeEventListener('resize', lazyLoad);
                        window.removeEventListener('orientationChange', lazyLoad);
                    }
                }, 20);
            }
            
            document.addEventListener('scroll', lazyLoad);
            window.addEventListener('resize', lazyLoad);
            window.addEventListener('orientationChange', lazyLoad);
            
            // Carregar imagens visíveis inicialmente
            lazyLoad();
        }
        
        // Adicionar estilos dinâmicos
        const style = document.createElement('style');
        style.textContent = `
            img.lazy {
                opacity: 0;
                transition: opacity 0.3s ease-in-out;
            }
            
            img.lazy.loaded {
                opacity: 1;
            }
            
            img.lazy.error {
                opacity: 1;
                filter: grayscale(100%);
            }
        `;
        
        document.head.appendChild(style);
    }

    // Inicializar quando o DOM estiver pronto
    document.addEventListener('DOMContentLoaded', initLazyLoad);

})(); 