/**
 * Script para funcionalidades de scroll
 */
(function() {
    'use strict';

    // Configurações
    const config = {
        scrollThreshold: 100,
        scrollDuration: 800,
        scrollOffset: 0
    };

    // Função para scroll suave
    function smoothScroll(target, duration = config.scrollDuration) {
        const targetPosition = target.getBoundingClientRect().top + window.pageYOffset - config.scrollOffset;
        const startPosition = window.pageYOffset;
        const distance = targetPosition - startPosition;
        let startTime = null;

        function animation(currentTime) {
            if (startTime === null) startTime = currentTime;
            const timeElapsed = currentTime - startTime;
            const run = ease(timeElapsed, startPosition, distance, duration);
            window.scrollTo(0, run);
            if (timeElapsed < duration) requestAnimationFrame(animation);
        }

        // Função de easing
        function ease(t, b, c, d) {
            t /= d / 2;
            if (t < 1) return c / 2 * t * t + b;
            t--;
            return -c / 2 * (t * (t - 2) - 1) + b;
        }

        requestAnimationFrame(animation);
    }

    // Função para inicializar o scroll suave
    function initSmoothScroll() {
        document.querySelectorAll('a[href^="#"]').forEach(anchor => {
            anchor.addEventListener('click', function(e) {
                e.preventDefault();
                
                const target = document.querySelector(this.getAttribute('href'));
                if (target) {
                    smoothScroll(target);
                }
            });
        });
    }

    // Função para o botão de voltar ao topo
    function initBackToTop() {
        const button = document.createElement('button');
        button.className = 'back-to-top';
        button.innerHTML = '&uarr;';
        button.setAttribute('aria-label', 'Voltar ao topo');
        document.body.appendChild(button);

        // Mostrar/ocultar o botão
        function toggleButton() {
            if (window.pageYOffset > config.scrollThreshold) {
                button.classList.add('visible');
            } else {
                button.classList.remove('visible');
            }
        }

        // Event listeners
        window.addEventListener('scroll', toggleButton);
        button.addEventListener('click', () => {
            smoothScroll(document.documentElement);
        });
    }

    // Função para o menu fixo
    function initStickyHeader() {
        const header = document.querySelector('.site-header');
        if (!header) return;

        const headerHeight = header.offsetHeight;
        let lastScroll = 0;

        function toggleHeader() {
            const currentScroll = window.pageYOffset;

            if (currentScroll <= 0) {
                header.classList.remove('scroll-up');
                return;
            }

            if (currentScroll > lastScroll && !header.classList.contains('scroll-down')) {
                // Scroll para baixo
                header.classList.remove('scroll-up');
                header.classList.add('scroll-down');
            } else if (currentScroll < lastScroll && header.classList.contains('scroll-down')) {
                // Scroll para cima
                header.classList.remove('scroll-down');
                header.classList.add('scroll-up');
            }

            lastScroll = currentScroll;
        }

        window.addEventListener('scroll', toggleHeader);
    }

    // Função para animações no scroll
    function initScrollAnimations() {
        const elements = document.querySelectorAll('.animate-on-scroll');
        
        const observer = new IntersectionObserver((entries) => {
            entries.forEach(entry => {
                if (entry.isIntersecting) {
                    entry.target.classList.add('animated');
                    observer.unobserve(entry.target);
                }
            });
        }, {
            threshold: 0.1
        });

        elements.forEach(element => {
            observer.observe(element);
        });
    }

    // Função para inicializar todas as funcionalidades de scroll
    function initScroll() {
        initSmoothScroll();
        initBackToTop();
        initStickyHeader();
        initScrollAnimations();
        
        // Adicionar estilos dinâmicos
        const style = document.createElement('style');
        style.textContent = `
            .back-to-top {
                position: fixed;
                bottom: 20px;
                right: 20px;
                width: 40px;
                height: 40px;
                background-color: #007bff;
                color: #fff;
                border: none;
                border-radius: 50%;
                cursor: pointer;
                opacity: 0;
                visibility: hidden;
                transition: all 0.3s ease;
                z-index: 1000;
            }
            
            .back-to-top.visible {
                opacity: 1;
                visibility: visible;
            }
            
            .back-to-top:hover {
                background-color: #0056b3;
                transform: translateY(-2px);
            }
            
            .site-header {
                transition: transform 0.3s ease;
            }
            
            .site-header.scroll-down {
                transform: translateY(-100%);
            }
            
            .site-header.scroll-up {
                transform: translateY(0);
                box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
            }
            
            .animate-on-scroll {
                opacity: 0;
                transform: translateY(20px);
                transition: all 0.6s ease;
            }
            
            .animate-on-scroll.animated {
                opacity: 1;
                transform: translateY(0);
            }
        `;
        
        document.head.appendChild(style);
    }

    // Inicializar quando o DOM estiver pronto
    document.addEventListener('DOMContentLoaded', initScroll);

})(); 