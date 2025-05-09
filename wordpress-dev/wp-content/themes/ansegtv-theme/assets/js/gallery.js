/**
 * Script para a galeria de imagens
 */
(function() {
    'use strict';

    // Configurações
    const config = {
        animationDuration: 300,
        swipeThreshold: 50,
        zoomLevel: 2,
        maxZoomLevel: 4,
        minZoomLevel: 1,
        zoomStep: 0.5,
        keyboardEnabled: true,
        touchEnabled: true,
        mouseEnabled: true,
        loop: true,
        autoplay: false,
        autoplayDelay: 5000,
        showThumbnails: true,
        thumbnailSize: 100,
        thumbnailGap: 10,
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
            zoomIn: 'Aumentar zoom',
            zoomOut: 'Diminuir zoom',
            rotate: 'Rotacionar',
            info: 'Informações',
            image: 'Imagem',
            of: 'de',
            loading: 'Carregando...',
            error: 'Erro ao carregar a imagem'
        }
    };

    // Função para criar a galeria
    function createGallery(container) {
        const gallery = document.createElement('div');
        gallery.className = 'gallery';
        
        // Criar lightbox
        const lightbox = document.createElement('div');
        lightbox.className = 'gallery-lightbox';
        
        // Container da imagem
        const imageContainer = document.createElement('div');
        imageContainer.className = 'gallery-image-container';
        
        // Imagem
        const image = document.createElement('img');
        image.className = 'gallery-image';
        
        // Loading
        const loading = document.createElement('div');
        loading.className = 'gallery-loading';
        loading.innerHTML = translations[config.language].loading;
        
        // Controles
        const controls = document.createElement('div');
        controls.className = 'gallery-controls';
        
        // Botões de navegação
        const prevButton = document.createElement('button');
        prevButton.className = 'gallery-prev';
        prevButton.innerHTML = '<i class="fas fa-chevron-left"></i>';
        prevButton.setAttribute('aria-label', translations[config.language].prev);
        
        const nextButton = document.createElement('button');
        nextButton.className = 'gallery-next';
        nextButton.innerHTML = '<i class="fas fa-chevron-right"></i>';
        nextButton.setAttribute('aria-label', translations[config.language].next);
        
        // Botões de ação
        const actions = document.createElement('div');
        actions.className = 'gallery-actions';
        
        const fullscreenButton = document.createElement('button');
        fullscreenButton.className = 'gallery-fullscreen';
        fullscreenButton.innerHTML = '<i class="fas fa-expand"></i>';
        fullscreenButton.setAttribute('aria-label', translations[config.language].fullscreen);
        
        const downloadButton = document.createElement('button');
        downloadButton.className = 'gallery-download';
        downloadButton.innerHTML = '<i class="fas fa-download"></i>';
        downloadButton.setAttribute('aria-label', translations[config.language].download);
        
        const shareButton = document.createElement('button');
        shareButton.className = 'gallery-share';
        shareButton.innerHTML = '<i class="fas fa-share"></i>';
        shareButton.setAttribute('aria-label', translations[config.language].share);
        
        const zoomInButton = document.createElement('button');
        zoomInButton.className = 'gallery-zoom-in';
        zoomInButton.innerHTML = '<i class="fas fa-search-plus"></i>';
        zoomInButton.setAttribute('aria-label', translations[config.language].zoomIn);
        
        const zoomOutButton = document.createElement('button');
        zoomOutButton.className = 'gallery-zoom-out';
        zoomOutButton.innerHTML = '<i class="fas fa-search-minus"></i>';
        zoomOutButton.setAttribute('aria-label', translations[config.language].zoomOut);
        
        const rotateButton = document.createElement('button');
        rotateButton.className = 'gallery-rotate';
        rotateButton.innerHTML = '<i class="fas fa-redo"></i>';
        rotateButton.setAttribute('aria-label', translations[config.language].rotate);
        
        const closeButton = document.createElement('button');
        closeButton.className = 'gallery-close';
        closeButton.innerHTML = '<i class="fas fa-times"></i>';
        closeButton.setAttribute('aria-label', translations[config.language].close);
        
        // Informações
        const info = document.createElement('div');
        info.className = 'gallery-info';
        
        const counter = document.createElement('div');
        counter.className = 'gallery-counter';
        
        const title = document.createElement('div');
        title.className = 'gallery-title';
        
        const description = document.createElement('div');
        description.className = 'gallery-description';
        
        // Miniaturas
        const thumbnails = document.createElement('div');
        thumbnails.className = 'gallery-thumbnails';
        
        // Adicionar elementos
        imageContainer.appendChild(image);
        imageContainer.appendChild(loading);
        
        controls.appendChild(prevButton);
        controls.appendChild(nextButton);
        
        actions.appendChild(fullscreenButton);
        actions.appendChild(downloadButton);
        actions.appendChild(shareButton);
        actions.appendChild(zoomInButton);
        actions.appendChild(zoomOutButton);
        actions.appendChild(rotateButton);
        actions.appendChild(closeButton);
        
        info.appendChild(counter);
        info.appendChild(title);
        info.appendChild(description);
        
        lightbox.appendChild(imageContainer);
        lightbox.appendChild(controls);
        lightbox.appendChild(actions);
        lightbox.appendChild(info);
        lightbox.appendChild(thumbnails);
        
        gallery.appendChild(lightbox);
        
        // Estado da galeria
        let currentIndex = 0;
        let images = [];
        let isZoomed = false;
        let isRotated = false;
        let rotation = 0;
        let autoplayInterval = null;
        
        // Função para carregar imagens
        function loadImages() {
            images = Array.from(container.querySelectorAll('img')).map(img => ({
                src: img.src,
                title: img.getAttribute('title') || '',
                description: img.getAttribute('alt') || '',
                width: img.naturalWidth,
                height: img.naturalHeight
            }));
            
            // Criar miniaturas
            if (config.showThumbnails) {
                thumbnails.innerHTML = '';
                images.forEach((img, index) => {
                    const thumb = document.createElement('img');
                    thumb.src = img.src;
                    thumb.className = 'gallery-thumbnail';
                    thumb.setAttribute('aria-label', `${translations[config.language].image} ${index + 1}`);
                    
                    if (index === currentIndex) {
                        thumb.classList.add('active');
                    }
                    
                    thumb.addEventListener('click', () => {
                        showImage(index);
                    });
                    
                    thumbnails.appendChild(thumb);
                });
            }
            
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
            
            // Atualizar miniaturas
            if (config.showThumbnails) {
                thumbnails.querySelectorAll('.gallery-thumbnail').forEach((thumb, i) => {
                    thumb.classList.toggle('active', i === currentIndex);
                });
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
                
                // Resetar zoom e rotação
                isZoomed = false;
                isRotated = false;
                rotation = 0;
                image.style.transform = 'scale(1) rotate(0deg)';
                
                // Iniciar autoplay
                if (config.autoplay) {
                    startAutoplay();
                }
            };
            
            image.onerror = () => {
                loading.textContent = translations[config.language].error;
            };
        }
        
        // Função para iniciar autoplay
        function startAutoplay() {
            if (autoplayInterval) {
                clearInterval(autoplayInterval);
            }
            
            autoplayInterval = setInterval(() => {
                showImage(currentIndex + 1);
            }, config.autoplayDelay);
        }
        
        // Função para parar autoplay
        function stopAutoplay() {
            if (autoplayInterval) {
                clearInterval(autoplayInterval);
                autoplayInterval = null;
            }
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
            stopAutoplay();
        }
        
        // Função para zoom
        function zoom(level) {
            if (level < config.minZoomLevel) {
                level = config.minZoomLevel;
            } else if (level > config.maxZoomLevel) {
                level = config.maxZoomLevel;
            }
            
            isZoomed = level > 1;
            image.style.transform = `scale(${level}) rotate(${rotation}deg)`;
        }
        
        // Função para rotacionar
        function rotate() {
            rotation = (rotation + 90) % 360;
            image.style.transform = `scale(${isZoomed ? config.zoomLevel : 1}) rotate(${rotation}deg)`;
        }
        
        // Event listeners
        container.querySelectorAll('img').forEach((img, index) => {
            img.addEventListener('click', () => {
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
        
        zoomInButton.addEventListener('click', () => {
            zoom(config.zoomLevel + config.zoomStep);
        });
        
        zoomOutButton.addEventListener('click', () => {
            zoom(config.zoomLevel - config.zoomStep);
        });
        
        rotateButton.addEventListener('click', rotate);
        
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
                    case '+':
                        zoom(config.zoomLevel + config.zoomStep);
                        break;
                    case '-':
                        zoom(config.zoomLevel - config.zoomStep);
                        break;
                    case 'r':
                        rotate();
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
        
        // Event listeners de mouse
        if (config.mouseEnabled) {
            imageContainer.addEventListener('wheel', (e) => {
                if (e.deltaY < 0) {
                    zoom(config.zoomLevel + config.zoomStep);
                } else {
                    zoom(config.zoomLevel - config.zoomStep);
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
        
        return gallery;
    }

    // Função para inicializar
    function init() {
        // Adicionar estilos dinâmicos
        const style = document.createElement('style');
        style.textContent = `
            .gallery {
                position: relative;
            }
            
            .gallery-lightbox {
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
            
            .gallery-lightbox.active {
                display: flex;
                opacity: 1;
            }
            
            .gallery-image-container {
                position: relative;
                flex: 1;
                display: flex;
                align-items: center;
                justify-content: center;
                overflow: hidden;
            }
            
            .gallery-image {
                max-width: 100%;
                max-height: 100%;
                object-fit: contain;
                transition: transform ${config.animationDuration}ms ease;
            }
            
            .gallery-loading {
                position: absolute;
                top: 50%;
                left: 50%;
                transform: translate(-50%, -50%);
                color: #fff;
                font-size: 1.2rem;
                display: none;
            }
            
            .gallery-controls {
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
            
            .gallery-controls button {
                background: none;
                border: none;
                color: #fff;
                font-size: 2rem;
                cursor: pointer;
                padding: 1rem;
                pointer-events: auto;
                transition: opacity 0.3s ease;
            }
            
            .gallery-controls button:hover {
                opacity: 0.7;
            }
            
            .gallery-actions {
                position: absolute;
                top: 1rem;
                right: 1rem;
                display: flex;
                gap: 0.5rem;
            }
            
            .gallery-actions button {
                background: none;
                border: none;
                color: #fff;
                font-size: 1.2rem;
                cursor: pointer;
                padding: 0.5rem;
                transition: opacity 0.3s ease;
            }
            
            .gallery-actions button:hover {
                opacity: 0.7;
            }
            
            .gallery-info {
                position: absolute;
                bottom: 1rem;
                left: 1rem;
                right: 1rem;
                color: #fff;
                text-align: center;
            }
            
            .gallery-counter {
                font-size: 0.875rem;
                margin-bottom: 0.5rem;
            }
            
            .gallery-title {
                font-size: 1.2rem;
                font-weight: bold;
                margin-bottom: 0.5rem;
            }
            
            .gallery-description {
                font-size: 1rem;
                opacity: 0.8;
            }
            
            .gallery-thumbnails {
                position: absolute;
                bottom: 5rem;
                left: 50%;
                transform: translateX(-50%);
                display: flex;
                gap: ${config.thumbnailGap}px;
                padding: 1rem;
                background: rgba(0, 0, 0, 0.5);
                border-radius: 4px;
                overflow-x: auto;
                max-width: 100%;
            }
            
            .gallery-thumbnail {
                width: ${config.thumbnailSize}px;
                height: ${config.thumbnailSize}px;
                object-fit: cover;
                border-radius: 4px;
                cursor: pointer;
                opacity: 0.5;
                transition: opacity 0.3s ease;
            }
            
            .gallery-thumbnail:hover {
                opacity: 0.8;
            }
            
            .gallery-thumbnail.active {
                opacity: 1;
                border: 2px solid #fff;
            }
            
            @media (max-width: 768px) {
                .gallery-controls button {
                    font-size: 1.5rem;
                    padding: 0.5rem;
                }
                
                .gallery-actions {
                    top: 0.5rem;
                    right: 0.5rem;
                }
                
                .gallery-actions button {
                    font-size: 1rem;
                    padding: 0.25rem;
                }
                
                .gallery-info {
                    bottom: 0.5rem;
                    left: 0.5rem;
                    right: 0.5rem;
                }
                
                .gallery-title {
                    font-size: 1rem;
                }
                
                .gallery-description {
                    font-size: 0.875rem;
                }
                
                .gallery-thumbnails {
                    bottom: 4rem;
                }
                
                .gallery-thumbnail {
                    width: ${config.thumbnailSize * 0.75}px;
                    height: ${config.thumbnailSize * 0.75}px;
                }
            }
        `;
        
        document.head.appendChild(style);
        
        // Inicializar galerias
        document.querySelectorAll('.gallery-container').forEach(container => {
            createGallery(container);
        });
    }

    // Inicializar quando o DOM estiver pronto
    document.addEventListener('DOMContentLoaded', init);

})(); 