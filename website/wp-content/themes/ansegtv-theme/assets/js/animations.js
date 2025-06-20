/**
 * Script para as animações do tema
 */
(function() {
    'use strict';

    // Função para verificar se um elemento está visível na viewport
    function isElementInViewport(el) {
        const rect = el.getBoundingClientRect();
        return (
            rect.top >= 0 &&
            rect.left >= 0 &&
            rect.bottom <= (window.innerHeight || document.documentElement.clientHeight) &&
            rect.right <= (window.innerWidth || document.documentElement.clientWidth)
        );
    }

    // Função para inicializar as animações
    function initAnimations() {
        const elements = document.querySelectorAll('.animate-on-scroll');
        
        // Função para animar elementos
        function animateElements() {
            elements.forEach(element => {
                if (isElementInViewport(element)) {
                    element.classList.add('animated');
                }
            });
        }
        
        // Adicionar event listeners
        window.addEventListener('scroll', animateElements);
        window.addEventListener('resize', animateElements);
        window.addEventListener('load', animateElements);
        
        // Adicionar estilos dinâmicos
        const style = document.createElement('style');
        style.textContent = `
            .animate-on-scroll {
                opacity: 0;
                transform: translateY(20px);
                transition: opacity 0.6s ease-out, transform 0.6s ease-out;
            }
            
            .animate-on-scroll.animated {
                opacity: 1;
                transform: translateY(0);
            }
            
            /* Fade In */
            .fade-in {
                opacity: 0;
                transition: opacity 0.6s ease-out;
            }
            
            .fade-in.animated {
                opacity: 1;
            }
            
            /* Slide In Left */
            .slide-in-left {
                opacity: 0;
                transform: translateX(-50px);
                transition: opacity 0.6s ease-out, transform 0.6s ease-out;
            }
            
            .slide-in-left.animated {
                opacity: 1;
                transform: translateX(0);
            }
            
            /* Slide In Right */
            .slide-in-right {
                opacity: 0;
                transform: translateX(50px);
                transition: opacity 0.6s ease-out, transform 0.6s ease-out;
            }
            
            .slide-in-right.animated {
                opacity: 1;
                transform: translateX(0);
            }
            
            /* Slide In Up */
            .slide-in-up {
                opacity: 0;
                transform: translateY(50px);
                transition: opacity 0.6s ease-out, transform 0.6s ease-out;
            }
            
            .slide-in-up.animated {
                opacity: 1;
                transform: translateY(0);
            }
            
            /* Slide In Down */
            .slide-in-down {
                opacity: 0;
                transform: translateY(-50px);
                transition: opacity 0.6s ease-out, transform 0.6s ease-out;
            }
            
            .slide-in-down.animated {
                opacity: 1;
                transform: translateY(0);
            }
            
            /* Zoom In */
            .zoom-in {
                opacity: 0;
                transform: scale(0.8);
                transition: opacity 0.6s ease-out, transform 0.6s ease-out;
            }
            
            .zoom-in.animated {
                opacity: 1;
                transform: scale(1);
            }
            
            /* Zoom Out */
            .zoom-out {
                opacity: 0;
                transform: scale(1.2);
                transition: opacity 0.6s ease-out, transform 0.6s ease-out;
            }
            
            .zoom-out.animated {
                opacity: 1;
                transform: scale(1);
            }
            
            /* Rotate In */
            .rotate-in {
                opacity: 0;
                transform: rotate(-180deg) scale(0.8);
                transition: opacity 0.6s ease-out, transform 0.6s ease-out;
            }
            
            .rotate-in.animated {
                opacity: 1;
                transform: rotate(0) scale(1);
            }
            
            /* Bounce In */
            .bounce-in {
                opacity: 0;
                transform: scale(0.3);
                transition: opacity 0.6s ease-out, transform 0.6s cubic-bezier(0.68, -0.55, 0.265, 1.55);
            }
            
            .bounce-in.animated {
                opacity: 1;
                transform: scale(1);
            }
            
            /* Flip In */
            .flip-in {
                opacity: 0;
                transform: perspective(400px) rotateY(90deg);
                transition: opacity 0.6s ease-out, transform 0.6s ease-out;
                transform-origin: 50% 50%;
            }
            
            .flip-in.animated {
                opacity: 1;
                transform: perspective(400px) rotateY(0);
            }
            
            /* Delay classes */
            .delay-100 { transition-delay: 0.1s; }
            .delay-200 { transition-delay: 0.2s; }
            .delay-300 { transition-delay: 0.3s; }
            .delay-400 { transition-delay: 0.4s; }
            .delay-500 { transition-delay: 0.5s; }
            .delay-600 { transition-delay: 0.6s; }
            .delay-700 { transition-delay: 0.7s; }
            .delay-800 { transition-delay: 0.8s; }
            .delay-900 { transition-delay: 0.9s; }
            .delay-1000 { transition-delay: 1s; }
        `;
        
        document.head.appendChild(style);
    }

    // Inicializar quando o DOM estiver pronto
    document.addEventListener('DOMContentLoaded', initAnimations);

})(); 