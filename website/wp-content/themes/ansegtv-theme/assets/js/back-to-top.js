/**
 * Script para o botão de voltar ao topo
 */
(function() {
    'use strict';

    // Função para criar o botão
    function createBackToTopButton() {
        const button = document.createElement('button');
        button.className = 'scroll-to-top';
        button.setAttribute('aria-label', 'Voltar ao topo');
        button.innerHTML = '<i class="fas fa-arrow-up"></i>';
        
        document.body.appendChild(button);
        
        return button;
    }

    // Função para inicializar o botão
    function initBackToTop() {
        const button = createBackToTopButton();
        let isScrolling = false;
        
        // Função para verificar a posição do scroll
        function checkScroll() {
            if (!isScrolling) {
                window.requestAnimationFrame(() => {
                    if (window.pageYOffset > 300) {
                        button.classList.add('show');
                    } else {
                        button.classList.remove('show');
                    }
                    isScrolling = false;
                });
            }
            isScrolling = true;
        }
        
        // Função para rolar suavemente ao topo
        function scrollToTop() {
            const currentPosition = window.pageYOffset;
            
            if (currentPosition > 0) {
                window.requestAnimationFrame(scrollToTop);
                window.scrollTo(0, currentPosition - currentPosition / 8);
            }
        }
        
        // Adicionar event listeners
        window.addEventListener('scroll', checkScroll);
        button.addEventListener('click', (e) => {
            e.preventDefault();
            scrollToTop();
        });
    }

    // Inicializar quando o DOM estiver pronto
    document.addEventListener('DOMContentLoaded', initBackToTop);

})(); 