/**
 * Script para o slider
 */
(function() {
    'use strict';

    // Configurações
    const config = {
        animationDuration: 500,
        swipeThreshold: 50,
        autoplay: true,
        autoplayDelay: 5000,
        loop: true,
        showArrows: true,
        showDots: true,
        showCounter: true,
        showFullscreen: true,
        showPlayPause: true,
        showThumbnails: true,
        thumbnailSize: 100,
        thumbnailGap: 10,
        transition: 'fade', // fade, slide, zoom
        theme: 'light', // light, dark
        language: 'pt-BR'
    };

    // Traduções
    const translations = {
        'pt-BR': {
            next: 'Próximo',
            prev: 'Anterior',
            play: 'Reproduzir',
            pause: 'Pausar',
            fullscreen: 'Tela cheia',
            slide: 'Slide',
            of: 'de',
            loading: 'Carregando...',
            error: 'Erro ao carregar o slide'
        }
    };

    // Função para criar o slider
    function createSlider(container) {
        const slider = document.createElement('div');
        slider.className = 'slider';
        
        // Container dos slides
        const slidesContainer = document.createElement('div');
        slidesContainer.className = 'slider-slides';
        
        // Loading
        const loading = document.createElement('div');
        loading.className = 'slider-loading';
        loading.innerHTML = translations[config.language].loading;
        
        // Controles
        const controls = document.createElement('div');
        controls.className = 'slider-controls';
        
        // Botões de navegação
        const prevButton = document.createElement('button');
        prevButton.className = 'slider-prev';
        prevButton.innerHTML = '<i class="fas fa-chevron-left"></i>';
        prevButton.setAttribute('aria-label', translations[config.language].prev);
        
        const nextButton = document.createElement('button');
        nextButton.className = 'slider-next';
        nextButton.innerHTML = '<i class="fas fa-chevron-right"></i>';
        nextButton.setAttribute('aria-label', translations[config.language].next);
        
        // Botões de ação
        const actions = document.createElement('div');
        actions.className = 'slider-actions';
        
        const playPauseButton = document.createElement('button');
        playPauseButton.className = 'slider-play-pause';
        playPauseButton.innerHTML = '<i class="fas fa-pause"></i>';
        playPauseButton.setAttribute('aria-label', translations[config.language].pause);
        
        const fullscreenButton = document.createElement('button');
        fullscreenButton.className = 'slider-fullscreen';
        fullscreenButton.innerHTML = '<i class="fas fa-expand"></i>';
        fullscreenButton.setAttribute('aria-label', translations[config.language].fullscreen);
        
        // Informações
        const info = document.createElement('div');
        info.className = 'slider-info';
        
        const counter = document.createElement('div');
        counter.className = 'slider-counter';
        
        // Dots
        const dots = document.createElement('div');
        dots.className = 'slider-dots';
        
        // Miniaturas
        const thumbnails = document.createElement('div');
        thumbnails.className = 'slider-thumbnails';
        
        // Adicionar elementos
        slidesContainer.appendChild(loading);
        
        controls.appendChild(prevButton);
        controls.appendChild(nextButton);
        
        actions.appendChild(playPauseButton);
        actions.appendChild(fullscreenButton);
        
        info.appendChild(counter);
        
        slider.appendChild(slidesContainer);
        slider.appendChild(controls);
        slider.appendChild(actions);
        slider.appendChild(info);
        slider.appendChild(dots);
        slider.appendChild(thumbnails);
        
        container.appendChild(slider);
        
        // Estado do slider
        let currentIndex = 0;
        let slides = [];
        let autoplayInterval = null;
        let isPlaying = config.autoplay;
        
        // Função para carregar slides
        function loadSlides() {
            slides = Array.from(container.querySelectorAll('.slide')).map(slide => ({
                content: slide.innerHTML,
                title: slide.getAttribute('data-title') || '',
                description: slide.getAttribute('data-description') || ''
            }));
            
            // Criar slides
            slidesContainer.innerHTML = '';
            slides.forEach((slide, index) => {
                const slideElement = document.createElement('div');
                slideElement.className = 'slider-slide';
                slideElement.innerHTML = slide.content;
                
                if (index === currentIndex) {
                    slideElement.classList.add('active');
                }
                
                slidesContainer.appendChild(slideElement);
            });
            
            // Criar dots
            if (config.showDots) {
                dots.innerHTML = '';
                slides.forEach((_, index) => {
                    const dot = document.createElement('button');
                    dot.className = 'slider-dot';
                    dot.setAttribute('aria-label', `${translations[config.language].slide} ${index + 1}`);
                    
                    if (index === currentIndex) {
                        dot.classList.add('active');
                    }
                    
                    dot.addEventListener('click', () => {
                        showSlide(index);
                    });
                    
                    dots.appendChild(dot);
                });
            }
            
            // Criar miniaturas
            if (config.showThumbnails) {
                thumbnails.innerHTML = '';
                slides.forEach((slide, index) => {
                    const thumb = document.createElement('div');
                    thumb.className = 'slider-thumbnail';
                    thumb.innerHTML = slide.content;
                    thumb.setAttribute('aria-label', `${translations[config.language].slide} ${index + 1}`);
                    
                    if (index === currentIndex) {
                        thumb.classList.add('active');
                    }
                    
                    thumb.addEventListener('click', () => {
                        showSlide(index);
                    });
                    
                    thumbnails.appendChild(thumb);
                });
            }
            
            // Atualizar contador
            if (config.showCounter) {
                counter.textContent = `${currentIndex + 1} ${translations[config.language].of} ${slides.length}`;
            }
        }
        
        // Função para mostrar slide
        function showSlide(index) {
            if (index < 0) {
                index = config.loop ? slides.length - 1 : 0;
            } else if (index >= slides.length) {
                index = config.loop ? 0 : slides.length - 1;
            }
            
            currentIndex = index;
            
            // Atualizar slides
            slidesContainer.querySelectorAll('.slider-slide').forEach((slide, i) => {
                slide.classList.toggle('active', i === currentIndex);
            });
            
            // Atualizar dots
            if (config.showDots) {
                dots.querySelectorAll('.slider-dot').forEach((dot, i) => {
                    dot.classList.toggle('active', i === currentIndex);
                });
            }
            
            // Atualizar miniaturas
            if (config.showThumbnails) {
                thumbnails.querySelectorAll('.slider-thumbnail').forEach((thumb, i) => {
                    thumb.classList.toggle('active', i === currentIndex);
                });
            }
            
            // Atualizar contador
            if (config.showCounter) {
                counter.textContent = `${currentIndex + 1} ${translations[config.language].of} ${slides.length}`;
            }
        }
        
        // Função para iniciar autoplay
        function startAutoplay() {
            if (autoplayInterval) {
                clearInterval(autoplayInterval);
            }
            
            autoplayInterval = setInterval(() => {
                showSlide(currentIndex + 1);
            }, config.autoplayDelay);
            
            isPlaying = true;
            playPauseButton.innerHTML = '<i class="fas fa-pause"></i>';
            playPauseButton.setAttribute('aria-label', translations[config.language].pause);
        }
        
        // Função para parar autoplay
        function stopAutoplay() {
            if (autoplayInterval) {
                clearInterval(autoplayInterval);
                autoplayInterval = null;
            }
            
            isPlaying = false;
            playPauseButton.innerHTML = '<i class="fas fa-play"></i>';
            playPauseButton.setAttribute('aria-label', translations[config.language].play);
        }
        
        // Event listeners
        prevButton.addEventListener('click', () => {
            showSlide(currentIndex - 1);
        });
        
        nextButton.addEventListener('click', () => {
            showSlide(currentIndex + 1);
        });
        
        playPauseButton.addEventListener('click', () => {
            if (isPlaying) {
                stopAutoplay();
            } else {
                startAutoplay();
            }
        });
        
        fullscreenButton.addEventListener('click', () => {
            if (document.fullscreenElement) {
                document.exitFullscreen();
            } else {
                slider.requestFullscreen();
            }
        });
        
        // Event listeners de teclado
        document.addEventListener('keydown', (e) => {
            if (!slider.contains(document.activeElement)) return;
            
            switch (e.key) {
                case 'ArrowLeft':
                    showSlide(currentIndex - 1);
                    break;
                case 'ArrowRight':
                    showSlide(currentIndex + 1);
                    break;
                case ' ':
                    if (isPlaying) {
                        stopAutoplay();
                    } else {
                        startAutoplay();
                    }
                    break;
            }
        });
        
        // Event listeners de touch
        let touchStartX = 0;
        let touchEndX = 0;
        
        slidesContainer.addEventListener('touchstart', (e) => {
            touchStartX = e.touches[0].clientX;
        });
        
        slidesContainer.addEventListener('touchend', (e) => {
            touchEndX = e.changedTouches[0].clientX;
            
            const diff = touchStartX - touchEndX;
            
            if (Math.abs(diff) > config.swipeThreshold) {
                if (diff > 0) {
                    showSlide(currentIndex + 1);
                } else {
                    showSlide(currentIndex - 1);
                }
            }
        });
        
        // Event listener de fullscreen
        document.addEventListener('fullscreenchange', () => {
            if (document.fullscreenElement) {
                fullscreenButton.innerHTML = '<i class="fas fa-compress"></i>';
            } else {
                fullscreenButton.innerHTML = '<i class="fas fa-expand"></i>';
            }
        });
        
        // Inicializar
        loadSlides();
        
        if (config.autoplay) {
            startAutoplay();
        }
        
        return slider;
    }

    // Função para inicializar
    function init() {
        // Adicionar estilos dinâmicos
        const style = document.createElement('style');
        style.textContent = `
            .slider {
                position: relative;
                width: 100%;
                background: #000;
                border-radius: 8px;
                overflow: hidden;
            }
            
            .slider-slides {
                position: relative;
                width: 100%;
                padding-top: 56.25%; /* 16:9 */
            }
            
            .slider-slide {
                position: absolute;
                top: 0;
                left: 0;
                width: 100%;
                height: 100%;
                opacity: 0;
                transition: opacity ${config.animationDuration}ms ease;
            }
            
            .slider-slide.active {
                opacity: 1;
            }
            
            .slider-slide img {
                width: 100%;
                height: 100%;
                object-fit: cover;
            }
            
            .slider-loading {
                position: absolute;
                top: 50%;
                left: 50%;
                transform: translate(-50%, -50%);
                color: #fff;
                font-size: 1.2rem;
                display: none;
            }
            
            .slider-controls {
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
            
            .slider-controls button {
                background: none;
                border: none;
                color: #fff;
                font-size: 2rem;
                cursor: pointer;
                padding: 1rem;
                pointer-events: auto;
                transition: opacity 0.3s ease;
            }
            
            .slider-controls button:hover {
                opacity: 0.7;
            }
            
            .slider-actions {
                position: absolute;
                top: 1rem;
                right: 1rem;
                display: flex;
                gap: 0.5rem;
            }
            
            .slider-actions button {
                background: none;
                border: none;
                color: #fff;
                font-size: 1.2rem;
                cursor: pointer;
                padding: 0.5rem;
                transition: opacity 0.3s ease;
            }
            
            .slider-actions button:hover {
                opacity: 0.7;
            }
            
            .slider-info {
                position: absolute;
                bottom: 1rem;
                left: 1rem;
                right: 1rem;
                color: #fff;
                text-align: center;
            }
            
            .slider-counter {
                font-size: 0.875rem;
                margin-bottom: 0.5rem;
            }
            
            .slider-dots {
                position: absolute;
                bottom: 3rem;
                left: 50%;
                transform: translateX(-50%);
                display: flex;
                gap: 0.5rem;
            }
            
            .slider-dot {
                width: 10px;
                height: 10px;
                border-radius: 50%;
                background: rgba(255, 255, 255, 0.5);
                border: none;
                cursor: pointer;
                transition: background-color 0.3s ease;
            }
            
            .slider-dot:hover {
                background: rgba(255, 255, 255, 0.8);
            }
            
            .slider-dot.active {
                background: #fff;
            }
            
            .slider-thumbnails {
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
            
            .slider-thumbnail {
                width: ${config.thumbnailSize}px;
                height: ${config.thumbnailSize}px;
                border-radius: 4px;
                cursor: pointer;
                opacity: 0.5;
                transition: opacity 0.3s ease;
                overflow: hidden;
            }
            
            .slider-thumbnail img {
                width: 100%;
                height: 100%;
                object-fit: cover;
            }
            
            .slider-thumbnail:hover {
                opacity: 0.8;
            }
            
            .slider-thumbnail.active {
                opacity: 1;
                border: 2px solid #fff;
            }
            
            @media (max-width: 768px) {
                .slider-controls button {
                    font-size: 1.5rem;
                    padding: 0.5rem;
                }
                
                .slider-actions {
                    top: 0.5rem;
                    right: 0.5rem;
                }
                
                .slider-actions button {
                    font-size: 1rem;
                    padding: 0.25rem;
                }
                
                .slider-info {
                    bottom: 0.5rem;
                    left: 0.5rem;
                    right: 0.5rem;
                }
                
                .slider-thumbnails {
                    bottom: 4rem;
                }
                
                .slider-thumbnail {
                    width: ${config.thumbnailSize * 0.75}px;
                    height: ${config.thumbnailSize * 0.75}px;
                }
            }
        `;
        
        document.head.appendChild(style);
        
        // Inicializar sliders
        document.querySelectorAll('.slider-container').forEach(container => {
            createSlider(container);
        });
    }

    // Inicializar quando o DOM estiver pronto
    document.addEventListener('DOMContentLoaded', init);

})(); 