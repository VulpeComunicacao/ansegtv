/**
 * Script para o lightbox
 */
(function() {
    'use strict';

    // Configurações
    const config = {
        animationDuration: 300,
        swipeThreshold: 50,
        keyboardEnabled: true,
        touchEnabled: true,
        mouseEnabled: true,
        loop: true,
        showCounter: true,
        showFullscreen: true,
        showDownload: true,
        showShare: true,
        showInfo: true,
        infoPosition: 'bottom',
        transition: 'fade', // fade, slide, zoom
        theme: 'light', // light, dark
        language: 'pt-BR'
    };

    // Traduções
    const translations = {
        'pt-BR': {
            next: 'Próxima',
            prev: 'Anterior',
            close: 'Fechar',
            fullscreen: 'Tela cheia',
            download: 'Download',
            share: 'Compartilhar',
            info: 'Informações',
            image: 'Imagem',
            of: 'de',
            loading: 'Carregando...',
            error: 'Erro ao carregar a imagem'
        }
    };

    // Função para criar o lightbox
    function createLightbox() {
        const lightbox = document.createElement('div');
        lightbox.className = 'lightbox';
        
        // Container da imagem
        const imageContainer = document.createElement('div');
        imageContainer.className = 'lightbox-image-container';
        
        // Imagem
        const image = document.createElement('img');
        image.className = 'lightbox-image';
        
        // Loading
        const loading = document.createElement('div');
        loading.className = 'lightbox-loading';
        loading.innerHTML = translations[config.language].loading;
        
        // Controles
        const controls = document.createElement('div');
        controls.className = 'lightbox-controls';
        
        // Botões de navegação
        const prevButton = document.createElement('button');
        prevButton.className = 'lightbox-prev';
        prevButton.innerHTML = '<i class="fas fa-chevron-left"></i>';
        prevButton.setAttribute('aria-label', translations[config.language].prev);
        
        const nextButton = document.createElement('button');
        nextButton.className = 'lightbox-next';
        nextButton.innerHTML = '<i class="fas fa-chevron-right"></i>';
        nextButton.setAttribute('aria-label', translations[config.language].next);
        
        // Botões de ação
        const actions = document.createElement('div');
        actions.className = 'lightbox-actions';
        
        const fullscreenButton = document.createElement('button');
        fullscreenButton.className = 'lightbox-fullscreen';
        fullscreenButton.innerHTML = '<i class="fas fa-expand"></i>';
        fullscreenButton.setAttribute('aria-label', translations[config.language].fullscreen);
        
        const downloadButton = document.createElement('button');
        downloadButton.className = 'lightbox-download';
        downloadButton.innerHTML = '<i class="fas fa-download"></i>';
        downloadButton.setAttribute('aria-label', translations[config.language].download);
        
        const shareButton = document.createElement('button');
        shareButton.className = 'lightbox-share';
        shareButton.innerHTML = '<i class="fas fa-share"></i>';
        shareButton.setAttribute('aria-label', translations[config.language].share);
        
        const closeButton = document.createElement('button');
        closeButton.className = 'lightbox-close';
        closeButton.innerHTML = '<i class="fas fa-times"></i>';
        closeButton.setAttribute('aria-label', translations[config.language].close);
        
        // Informações
        const info = document.createElement('div');
        info.className = 'lightbox-info';
        
        const counter = document.createElement('div');
        counter.className = 'lightbox-counter';
        
        const title = document.createElement('div');
        title.className = 'lightbox-title';
        
        const description = document.createElement('div');
        description.className = 'lightbox-description';
        
        // Adicionar elementos
        imageContainer.appendChild(image);
        imageContainer.appendChild(loading);
        
        controls.appendChild(prevButton);
        controls.appendChild(nextButton);
        
        actions.appendChild(fullscreenButton);
        actions.appendChild(downloadButton);
        actions.appendChild(shareButton);
        actions.appendChild(closeButton);
        
        info.appendChild(counter);
        info.appendChild(title);
        info.appendChild(description);
        
        lightbox.appendChild(imageContainer);
        lightbox.appendChild(controls);
        lightbox.appendChild(actions);
        lightbox.appendChild(info);
        
        document.body.appendChild(lightbox);
        
        // Estado do lightbox
        let currentIndex = 0;
        let images = [];
        
        // Função para carregar imagens
        function loadImages() {
            images = Array.from(document.querySelectorAll('a[data-lightbox]')).map(link => ({
                src: link.href,
                title: link.getAttribute('title') || '',
                description: link.getAttribute('data-description') || '',
                width: link.getAttribute('data-width') || '',
                height: link.getAttribute('data-height') || ''
            }));
            
            // Atualizar contador
            if (config.showCounter) {
                counter.textContent = `${currentIndex + 1} ${translations[config.language].of} ${images.length}`;
            }
        }
        
        // Função para mostrar imagem
        function showImage(index) {
            if (index < 0) {
                index = config.loop ? images.length - 1 : 0;
            } else if (index >= images.length) {
                index = config.loop ? 0 : images.length - 1;
            }
            
            currentIndex = index;
            
            // Atualizar contador
            if (config.showCounter) {
                counter.textContent = `${currentIndex + 1} ${translations[config.language].of} ${images.length}`;
            }
            
            // Atualizar informações
            title.textContent = images[currentIndex].title;
            description.textContent = images[currentIndex].description;
            
            // Mostrar loading
            loading.style.display = 'flex';
            
            // Carregar imagem
            image.src = images[currentIndex].src;
            image.onload = () => {
                loading.style.display = 'none';
            };
            
            image.onerror = () => {
                loading.textContent = translations[config.language].error;
            };
        }
        
        // Função para abrir lightbox
        function openLightbox(index) {
            document.body.style.overflow = 'hidden';
            lightbox.classList.add('active');
            showImage(index);
        }
        
        // Função para fechar lightbox
        function closeLightbox() {
            document.body.style.overflow = '';
            lightbox.classList.remove('active');
        }
        
        // Event listeners
        document.querySelectorAll('a[data-lightbox]').forEach((link, index) => {
            link.addEventListener('click', (e) => {
                e.preventDefault();
                loadImages();
                openLightbox(index);
            });
        });
        
        prevButton.addEventListener('click', () => {
            showImage(currentIndex - 1);
        });
        
        nextButton.addEventListener('click', () => {
            showImage(currentIndex + 1);
        });
        
        closeButton.addEventListener('click', closeLightbox);
        
        fullscreenButton.addEventListener('click', () => {
            if (document.fullscreenElement) {
                document.exitFullscreen();
            } else {
                lightbox.requestFullscreen();
            }
        });
        
        downloadButton.addEventListener('click', () => {
            const link = document.createElement('a');
            link.href = images[currentIndex].src;
            link.download = images[currentIndex].title || 'image';
            link.click();
        });
        
        shareButton.addEventListener('click', () => {
            if (navigator.share) {
                navigator.share({
                    title: images[currentIndex].title,
                    text: images[currentIndex].description,
                    url: images[currentIndex].src
                });
            }
        });
        
        // Event listeners de teclado
        if (config.keyboardEnabled) {
            document.addEventListener('keydown', (e) => {
                if (!lightbox.classList.contains('active')) return;
                
                switch (e.key) {
                    case 'Escape':
                        closeLightbox();
                        break;
                    case 'ArrowLeft':
                        showImage(currentIndex - 1);
                        break;
                    case 'ArrowRight':
                        showImage(currentIndex + 1);
                        break;
                }
            });
        }
        
        // Event listeners de touch
        if (config.touchEnabled) {
            let touchStartX = 0;
            let touchEndX = 0;
            
            imageContainer.addEventListener('touchstart', (e) => {
                touchStartX = e.touches[0].clientX;
            });
            
            imageContainer.addEventListener('touchend', (e) => {
                touchEndX = e.changedTouches[0].clientX;
                
                const diff = touchStartX - touchEndX;
                
                if (Math.abs(diff) > config.swipeThreshold) {
                    if (diff > 0) {
                        showImage(currentIndex + 1);
                    } else {
                        showImage(currentIndex - 1);
                    }
                }
            });
        }
        
        // Event listener de fullscreen
        document.addEventListener('fullscreenchange', () => {
            if (document.fullscreenElement) {
                fullscreenButton.innerHTML = '<i class="fas fa-compress"></i>';
            } else {
                fullscreenButton.innerHTML = '<i class="fas fa-expand"></i>';
            }
        });
        
        return lightbox;
    }

    // Função para inicializar
    function init() {
        // Adicionar estilos dinâmicos
        const style = document.createElement('style');
        style.textContent = `
            .lightbox {
                position: fixed;
                top: 0;
                left: 0;
                width: 100%;
                height: 100%;
                background: rgba(0, 0, 0, 0.9);
                z-index: 9999;
                display: none;
                opacity: 0;
                transition: opacity ${config.animationDuration}ms ease;
            }
            
            .lightbox.active {
                display: flex;
                opacity: 1;
            }
            
            .lightbox-image-container {
                position: relative;
                flex: 1;
                display: flex;
                align-items: center;
                justify-content: center;
                overflow: hidden;
            }
            
            .lightbox-image {
                max-width: 100%;
                max-height: 100%;
                object-fit: contain;
                transition: transform ${config.animationDuration}ms ease;
            }
            
            .lightbox-loading {
                position: absolute;
                top: 50%;
                left: 50%;
                transform: translate(-50%, -50%);
                color: #fff;
                font-size: 1.2rem;
                display: none;
            }
            
            .lightbox-controls {
                position: absolute;
                top: 50%;
                left: 0;
                right: 0;
                transform: translateY(-50%);
                display: flex;
                justify-content: space-between;
                padding: 0 1rem;
                pointer-events: none;
            }
            
            .lightbox-controls button {
                background: none;
                border: none;
                color: #fff;
                font-size: 2rem;
                cursor: pointer;
                padding: 1rem;
                pointer-events: auto;
                transition: opacity 0.3s ease;
            }
            
            .lightbox-controls button:hover {
                opacity: 0.7;
            }
            
            .lightbox-actions {
                position: absolute;
                top: 1rem;
                right: 1rem;
                display: flex;
                gap: 0.5rem;
            }
            
            .lightbox-actions button {
                background: none;
                border: none;
                color: #fff;
                font-size: 1.2rem;
                cursor: pointer;
                padding: 0.5rem;
                transition: opacity 0.3s ease;
            }
            
            .lightbox-actions button:hover {
                opacity: 0.7;
            }
            
            .lightbox-info {
                position: absolute;
                bottom: 1rem;
                left: 1rem;
                right: 1rem;
                color: #fff;
                text-align: center;
            }
            
            .lightbox-counter {
                font-size: 0.875rem;
                margin-bottom: 0.5rem;
            }
            
            .lightbox-title {
                font-size: 1.2rem;
                font-weight: bold;
                margin-bottom: 0.5rem;
            }
            
            .lightbox-description {
                font-size: 1rem;
                opacity: 0.8;
            }
            
            @media (max-width: 768px) {
                .lightbox-controls button {
                    font-size: 1.5rem;
                    padding: 0.5rem;
                }
                
                .lightbox-actions {
                    top: 0.5rem;
                    right: 0.5rem;
                }
                
                .lightbox-actions button {
                    font-size: 1rem;
                    padding: 0.25rem;
                }
                
                .lightbox-info {
                    bottom: 0.5rem;
                    left: 0.5rem;
                    right: 0.5rem;
                }
                
                .lightbox-title {
                    font-size: 1rem;
                }
                
                .lightbox-description {
                    font-size: 0.875rem;
                }
            }
        `;
        
        document.head.appendChild(style);
        
        // Criar lightbox
        createLightbox();
    }

    // Inicializar quando o DOM estiver pronto
    document.addEventListener('DOMContentLoaded', init);

})(); 