/**
 * Script para o carrossel de imagens
 */
(function() {
    'use strict';

    // Função para inicializar o carrossel
    function initCarousel() {
        const carousels = document.querySelectorAll('.carousel');
        
        carousels.forEach(carousel => {
            const items = carousel.querySelectorAll('.carousel-item');
            const prevButton = carousel.querySelector('.carousel-prev');
            const nextButton = carousel.querySelector('.carousel-next');
            const indicators = carousel.querySelectorAll('.carousel-indicator');
            
            let currentIndex = 0;
            let isAnimating = false;
            let autoplayInterval = null;
            
            // Função para atualizar o carrossel
            function updateCarousel() {
                if (isAnimating) return;
                isAnimating = true;
                
                items.forEach((item, index) => {
                    item.style.transform = `translateX(${100 * (index - currentIndex)}%)`;
                });
                
                // Atualizar indicadores
                indicators.forEach((indicator, index) => {
                    indicator.classList.toggle('active', index === currentIndex);
                });
                
                // Atualizar botões
                prevButton.disabled = currentIndex === 0;
                nextButton.disabled = currentIndex === items.length - 1;
                
                setTimeout(() => {
                    isAnimating = false;
                }, 500);
            }
            
            // Função para mostrar o slide anterior
            function showPrevSlide() {
                if (currentIndex > 0) {
                    currentIndex--;
                    updateCarousel();
                }
            }
            
            // Função para mostrar o próximo slide
            function showNextSlide() {
                if (currentIndex < items.length - 1) {
                    currentIndex++;
                    updateCarousel();
                }
            }
            
            // Função para mostrar um slide específico
            function showSlide(index) {
                if (index >= 0 && index < items.length) {
                    currentIndex = index;
                    updateCarousel();
                }
            }
            
            // Função para iniciar o autoplay
            function startAutoplay() {
                if (autoplayInterval) return;
                
                autoplayInterval = setInterval(() => {
                    if (currentIndex < items.length - 1) {
                        showNextSlide();
                    } else {
                        showSlide(0);
                    }
                }, 5000);
            }
            
            // Função para parar o autoplay
            function stopAutoplay() {
                if (autoplayInterval) {
                    clearInterval(autoplayInterval);
                    autoplayInterval = null;
                }
            }
            
            // Adicionar event listeners
            if (prevButton) {
                prevButton.addEventListener('click', () => {
                    stopAutoplay();
                    showPrevSlide();
                });
            }
            
            if (nextButton) {
                nextButton.addEventListener('click', () => {
                    stopAutoplay();
                    showNextSlide();
                });
            }
            
            indicators.forEach((indicator, index) => {
                indicator.addEventListener('click', () => {
                    stopAutoplay();
                    showSlide(index);
                });
            });
            
            // Adicionar suporte para touch
            let touchStartX = 0;
            let touchEndX = 0;
            
            carousel.addEventListener('touchstart', (e) => {
                touchStartX = e.changedTouches[0].screenX;
            });
            
            carousel.addEventListener('touchend', (e) => {
                touchEndX = e.changedTouches[0].screenX;
                handleSwipe();
            });
            
            function handleSwipe() {
                const swipeThreshold = 50;
                const diff = touchStartX - touchEndX;
                
                if (Math.abs(diff) > swipeThreshold) {
                    if (diff > 0) {
                        showNextSlide();
                    } else {
                        showPrevSlide();
                    }
                }
            }
            
            // Adicionar suporte para teclado
            carousel.addEventListener('keydown', (e) => {
                switch (e.key) {
                    case 'ArrowLeft':
                        showPrevSlide();
                        break;
                    case 'ArrowRight':
                        showNextSlide();
                        break;
                }
            });
            
            // Inicializar o carrossel
            updateCarousel();
            
            // Iniciar autoplay se configurado
            if (carousel.dataset.autoplay === 'true') {
                startAutoplay();
                
                // Pausar autoplay quando o mouse estiver sobre o carrossel
                carousel.addEventListener('mouseenter', stopAutoplay);
                carousel.addEventListener('mouseleave', startAutoplay);
            }
        });
    }

    // Inicializar quando o DOM estiver pronto
    document.addEventListener('DOMContentLoaded', initCarousel);

})(); 