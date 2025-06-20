/**
 * Script para o player de vídeo
 */
(function() {
    'use strict';

    // Configurações
    const config = {
        autoplay: false,
        controls: true,
        loop: false,
        muted: false,
        preload: 'auto',
        volume: 1,
        playbackRates: [0.5, 1, 1.5, 2],
        defaultPlaybackRate: 1,
        qualityLevels: ['auto', '1080p', '720p', '480p', '360p'],
        defaultQuality: 'auto'
    };

    // Função para criar o player
    function createPlayer(videoElement) {
        const player = document.createElement('div');
        player.className = 'video-player';
        
        // Criar container do player
        const container = document.createElement('div');
        container.className = 'video-container';
        
        // Adicionar vídeo ao container
        videoElement.parentNode.insertBefore(container, videoElement);
        container.appendChild(videoElement);
        
        // Criar controles
        const controls = document.createElement('div');
        controls.className = 'video-controls';
        
        // Barra de progresso
        const progress = document.createElement('div');
        progress.className = 'video-progress';
        
        const progressBar = document.createElement('div');
        progressBar.className = 'video-progress-bar';
        
        const progressLoaded = document.createElement('div');
        progressLoaded.className = 'video-progress-loaded';
        
        const progressPlayed = document.createElement('div');
        progressPlayed.className = 'video-progress-played';
        
        progress.appendChild(progressLoaded);
        progress.appendChild(progressPlayed);
        progress.appendChild(progressBar);
        
        // Botões de controle
        const buttons = document.createElement('div');
        buttons.className = 'video-buttons';
        
        // Play/Pause
        const playButton = document.createElement('button');
        playButton.className = 'video-play';
        playButton.innerHTML = '<i class="fas fa-play"></i>';
        playButton.setAttribute('aria-label', 'Play');
        
        // Volume
        const volumeButton = document.createElement('button');
        volumeButton.className = 'video-volume';
        volumeButton.innerHTML = '<i class="fas fa-volume-up"></i>';
        volumeButton.setAttribute('aria-label', 'Volume');
        
        const volumeSlider = document.createElement('input');
        volumeSlider.type = 'range';
        volumeSlider.className = 'video-volume-slider';
        volumeSlider.min = '0';
        volumeSlider.max = '1';
        volumeSlider.step = '0.1';
        volumeSlider.value = config.volume;
        
        // Tempo
        const time = document.createElement('div');
        time.className = 'video-time';
        time.textContent = '0:00 / 0:00';
        
        // Velocidade
        const speedButton = document.createElement('button');
        speedButton.className = 'video-speed';
        speedButton.textContent = '1x';
        speedButton.setAttribute('aria-label', 'Velocidade de reprodução');
        
        const speedMenu = document.createElement('div');
        speedMenu.className = 'video-speed-menu';
        
        config.playbackRates.forEach(rate => {
            const option = document.createElement('button');
            option.textContent = rate + 'x';
            option.setAttribute('data-rate', rate);
            if (rate === config.defaultPlaybackRate) {
                option.classList.add('active');
            }
            speedMenu.appendChild(option);
        });
        
        // Qualidade
        const qualityButton = document.createElement('button');
        qualityButton.className = 'video-quality';
        qualityButton.textContent = config.defaultQuality;
        qualityButton.setAttribute('aria-label', 'Qualidade do vídeo');
        
        const qualityMenu = document.createElement('div');
        qualityMenu.className = 'video-quality-menu';
        
        config.qualityLevels.forEach(quality => {
            const option = document.createElement('button');
            option.textContent = quality;
            option.setAttribute('data-quality', quality);
            if (quality === config.defaultQuality) {
                option.classList.add('active');
            }
            qualityMenu.appendChild(option);
        });
        
        // Tela cheia
        const fullscreenButton = document.createElement('button');
        fullscreenButton.className = 'video-fullscreen';
        fullscreenButton.innerHTML = '<i class="fas fa-expand"></i>';
        fullscreenButton.setAttribute('aria-label', 'Tela cheia');
        
        // Adicionar elementos aos controles
        buttons.appendChild(playButton);
        buttons.appendChild(volumeButton);
        buttons.appendChild(volumeSlider);
        buttons.appendChild(time);
        buttons.appendChild(speedButton);
        buttons.appendChild(speedMenu);
        buttons.appendChild(qualityButton);
        buttons.appendChild(qualityMenu);
        buttons.appendChild(fullscreenButton);
        
        controls.appendChild(progress);
        controls.appendChild(buttons);
        
        player.appendChild(container);
        player.appendChild(controls);
        
        // Adicionar event listeners
        videoElement.addEventListener('play', () => {
            playButton.innerHTML = '<i class="fas fa-pause"></i>';
            playButton.setAttribute('aria-label', 'Pause');
        });
        
        videoElement.addEventListener('pause', () => {
            playButton.innerHTML = '<i class="fas fa-play"></i>';
            playButton.setAttribute('aria-label', 'Play');
        });
        
        videoElement.addEventListener('timeupdate', () => {
            const percent = (videoElement.currentTime / videoElement.duration) * 100;
            progressPlayed.style.width = percent + '%';
            
            time.textContent = formatTime(videoElement.currentTime) + ' / ' + formatTime(videoElement.duration);
        });
        
        videoElement.addEventListener('progress', () => {
            if (videoElement.buffered.length > 0) {
                const percent = (videoElement.buffered.end(videoElement.buffered.length - 1) / videoElement.duration) * 100;
                progressLoaded.style.width = percent + '%';
            }
        });
        
        videoElement.addEventListener('volumechange', () => {
            volumeSlider.value = videoElement.volume;
            
            if (videoElement.muted || videoElement.volume === 0) {
                volumeButton.innerHTML = '<i class="fas fa-volume-mute"></i>';
            } else if (videoElement.volume < 0.5) {
                volumeButton.innerHTML = '<i class="fas fa-volume-down"></i>';
            } else {
                volumeButton.innerHTML = '<i class="fas fa-volume-up"></i>';
            }
        });
        
        // Event listeners dos controles
        playButton.addEventListener('click', () => {
            if (videoElement.paused) {
                videoElement.play();
            } else {
                videoElement.pause();
            }
        });
        
        volumeButton.addEventListener('click', () => {
            videoElement.muted = !videoElement.muted;
        });
        
        volumeSlider.addEventListener('input', () => {
            videoElement.volume = volumeSlider.value;
            videoElement.muted = false;
        });
        
        progress.addEventListener('click', (e) => {
            const rect = progress.getBoundingClientRect();
            const percent = (e.clientX - rect.left) / rect.width;
            videoElement.currentTime = percent * videoElement.duration;
        });
        
        speedButton.addEventListener('click', () => {
            speedMenu.classList.toggle('active');
            qualityMenu.classList.remove('active');
        });
        
        speedMenu.querySelectorAll('button').forEach(button => {
            button.addEventListener('click', () => {
                const rate = parseFloat(button.getAttribute('data-rate'));
                videoElement.playbackRate = rate;
                speedButton.textContent = rate + 'x';
                
                speedMenu.querySelectorAll('button').forEach(btn => btn.classList.remove('active'));
                button.classList.add('active');
                
                speedMenu.classList.remove('active');
            });
        });
        
        qualityButton.addEventListener('click', () => {
            qualityMenu.classList.toggle('active');
            speedMenu.classList.remove('active');
        });
        
        qualityMenu.querySelectorAll('button').forEach(button => {
            button.addEventListener('click', () => {
                const quality = button.getAttribute('data-quality');
                // Implementar mudança de qualidade
                qualityButton.textContent = quality;
                
                qualityMenu.querySelectorAll('button').forEach(btn => btn.classList.remove('active'));
                button.classList.add('active');
                
                qualityMenu.classList.remove('active');
            });
        });
        
        fullscreenButton.addEventListener('click', () => {
            if (document.fullscreenElement) {
                document.exitFullscreen();
            } else {
                player.requestFullscreen();
            }
        });
        
        // Fechar menus ao clicar fora
        document.addEventListener('click', (e) => {
            if (!speedButton.contains(e.target) && !speedMenu.contains(e.target)) {
                speedMenu.classList.remove('active');
            }
            
            if (!qualityButton.contains(e.target) && !qualityMenu.contains(e.target)) {
                qualityMenu.classList.remove('active');
            }
        });
        
        // Atualizar ícone de tela cheia
        document.addEventListener('fullscreenchange', () => {
            if (document.fullscreenElement) {
                fullscreenButton.innerHTML = '<i class="fas fa-compress"></i>';
            } else {
                fullscreenButton.innerHTML = '<i class="fas fa-expand"></i>';
            }
        });
        
        return player;
    }

    // Função para formatar o tempo
    function formatTime(seconds) {
        const minutes = Math.floor(seconds / 60);
        seconds = Math.floor(seconds % 60);
        return minutes + ':' + (seconds < 10 ? '0' : '') + seconds;
    }

    // Função para inicializar
    function init() {
        // Adicionar estilos dinâmicos
        const style = document.createElement('style');
        style.textContent = `
            .video-player {
                position: relative;
                width: 100%;
                background: #000;
                border-radius: 8px;
                overflow: hidden;
            }
            
            .video-container {
                position: relative;
                width: 100%;
                padding-top: 56.25%; /* 16:9 */
            }
            
            .video-container video {
                position: absolute;
                top: 0;
                left: 0;
                width: 100%;
                height: 100%;
                object-fit: cover;
            }
            
            .video-controls {
                position: absolute;
                bottom: 0;
                left: 0;
                right: 0;
                background: linear-gradient(transparent, rgba(0, 0, 0, 0.7));
                padding: 1rem;
                opacity: 0;
                transition: opacity 0.3s ease;
            }
            
            .video-player:hover .video-controls {
                opacity: 1;
            }
            
            .video-progress {
                position: relative;
                height: 4px;
                background: rgba(255, 255, 255, 0.2);
                cursor: pointer;
                margin-bottom: 1rem;
            }
            
            .video-progress-bar {
                position: absolute;
                top: 0;
                left: 0;
                height: 100%;
                width: 100%;
                cursor: pointer;
            }
            
            .video-progress-loaded {
                position: absolute;
                top: 0;
                left: 0;
                height: 100%;
                background: rgba(255, 255, 255, 0.3);
            }
            
            .video-progress-played {
                position: absolute;
                top: 0;
                left: 0;
                height: 100%;
                background: #007bff;
            }
            
            .video-buttons {
                display: flex;
                align-items: center;
                gap: 1rem;
            }
            
            .video-buttons button {
                background: none;
                border: none;
                color: #fff;
                cursor: pointer;
                padding: 0.5rem;
                font-size: 1rem;
            }
            
            .video-buttons button:hover {
                color: #007bff;
            }
            
            .video-volume-slider {
                width: 80px;
                height: 4px;
                -webkit-appearance: none;
                background: rgba(255, 255, 255, 0.2);
                border-radius: 2px;
                outline: none;
            }
            
            .video-volume-slider::-webkit-slider-thumb {
                -webkit-appearance: none;
                width: 12px;
                height: 12px;
                background: #fff;
                border-radius: 50%;
                cursor: pointer;
            }
            
            .video-time {
                color: #fff;
                font-size: 0.875rem;
            }
            
            .video-speed-menu,
            .video-quality-menu {
                position: absolute;
                bottom: 100%;
                left: 0;
                background: rgba(0, 0, 0, 0.8);
                border-radius: 4px;
                padding: 0.5rem;
                display: none;
            }
            
            .video-speed-menu.active,
            .video-quality-menu.active {
                display: block;
            }
            
            .video-speed-menu button,
            .video-quality-menu button {
                display: block;
                width: 100%;
                text-align: left;
                padding: 0.5rem 1rem;
                color: #fff;
                background: none;
                border: none;
                cursor: pointer;
                white-space: nowrap;
            }
            
            .video-speed-menu button:hover,
            .video-quality-menu button:hover {
                background: rgba(255, 255, 255, 0.1);
            }
            
            .video-speed-menu button.active,
            .video-quality-menu button.active {
                color: #007bff;
            }
            
            @media (max-width: 768px) {
                .video-controls {
                    padding: 0.5rem;
                }
                
                .video-buttons {
                    gap: 0.5rem;
                }
                
                .video-volume-slider {
                    width: 60px;
                }
                
                .video-time {
                    font-size: 0.75rem;
                }
            }
        `;
        
        document.head.appendChild(style);
        
        // Inicializar players
        document.querySelectorAll('video').forEach(video => {
            if (!video.parentNode.classList.contains('video-player')) {
                createPlayer(video);
            }
        });
    }

    // Inicializar quando o DOM estiver pronto
    document.addEventListener('DOMContentLoaded', init);

})(); 