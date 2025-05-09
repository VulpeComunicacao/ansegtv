/**
 * Script para o gerenciamento de cookies
 */
(function() {
    'use strict';

    // Configurações
    const config = {
        cookieName: 'ansegtv_cookies',
        cookieExpiry: 365, // dias
        cookiePath: '/',
        cookieDomain: window.location.hostname,
        cookieSecure: window.location.protocol === 'https:'
    };

    // Função para criar o banner de cookies
    function createCookieBanner() {
        const banner = document.createElement('div');
        banner.className = 'cookie-banner';
        banner.setAttribute('role', 'dialog');
        banner.setAttribute('aria-label', 'Política de Cookies');
        
        banner.innerHTML = `
            <div class="cookie-content">
                <p>Este site utiliza cookies para melhorar sua experiência. Ao continuar navegando, você concorda com nossa <a href="/politica-de-cookies">política de cookies</a>.</p>
                <div class="cookie-buttons">
                    <button class="cookie-accept" aria-label="Aceitar cookies">Aceitar</button>
                    <button class="cookie-settings" aria-label="Configurar cookies">Configurar</button>
                </div>
            </div>
        `;
        
        document.body.appendChild(banner);
        
        // Adicionar event listeners
        banner.querySelector('.cookie-accept').addEventListener('click', () => {
            acceptAllCookies();
            banner.remove();
        });
        
        banner.querySelector('.cookie-settings').addEventListener('click', () => {
            showCookieSettings();
        });
    }

    // Função para mostrar as configurações de cookies
    function showCookieSettings() {
        const modal = document.createElement('div');
        modal.className = 'cookie-modal';
        modal.setAttribute('role', 'dialog');
        modal.setAttribute('aria-label', 'Configurações de Cookies');
        
        modal.innerHTML = `
            <div class="cookie-modal-content">
                <h2>Configurações de Cookies</h2>
                <p>Selecione quais cookies você deseja aceitar:</p>
                
                <div class="cookie-options">
                    <div class="cookie-option">
                        <label>
                            <input type="checkbox" name="necessary" checked disabled>
                            Cookies Necessários
                            <span class="cookie-description">Essenciais para o funcionamento do site</span>
                        </label>
                    </div>
                    
                    <div class="cookie-option">
                        <label>
                            <input type="checkbox" name="analytics">
                            Cookies Analíticos
                            <span class="cookie-description">Nos ajudam a entender como você usa o site</span>
                        </label>
                    </div>
                    
                    <div class="cookie-option">
                        <label>
                            <input type="checkbox" name="marketing">
                            Cookies de Marketing
                            <span class="cookie-description">Usados para personalizar anúncios</span>
                        </label>
                    </div>
                </div>
                
                <div class="cookie-modal-buttons">
                    <button class="cookie-save">Salvar Preferências</button>
                    <button class="cookie-accept-all">Aceitar Todos</button>
                    <button class="cookie-reject-all">Rejeitar Todos</button>
                </div>
            </div>
        `;
        
        document.body.appendChild(modal);
        
        // Adicionar event listeners
        modal.querySelector('.cookie-save').addEventListener('click', () => {
            saveCookiePreferences(modal);
            modal.remove();
        });
        
        modal.querySelector('.cookie-accept-all').addEventListener('click', () => {
            acceptAllCookies();
            modal.remove();
        });
        
        modal.querySelector('.cookie-reject-all').addEventListener('click', () => {
            rejectAllCookies();
            modal.remove();
        });
        
        // Fechar ao clicar fora
        modal.addEventListener('click', (e) => {
            if (e.target === modal) {
                modal.remove();
            }
        });
        
        // Fechar com Escape
        document.addEventListener('keydown', function closeOnEscape(e) {
            if (e.key === 'Escape') {
                modal.remove();
                document.removeEventListener('keydown', closeOnEscape);
            }
        });
    }

    // Função para salvar as preferências de cookies
    function saveCookiePreferences(modal) {
        const preferences = {
            necessary: true, // Sempre true
            analytics: modal.querySelector('input[name="analytics"]').checked,
            marketing: modal.querySelector('input[name="marketing"]').checked,
            timestamp: new Date().getTime()
        };
        
        setCookie(config.cookieName, JSON.stringify(preferences));
    }

    // Função para aceitar todos os cookies
    function acceptAllCookies() {
        const preferences = {
            necessary: true,
            analytics: true,
            marketing: true,
            timestamp: new Date().getTime()
        };
        
        setCookie(config.cookieName, JSON.stringify(preferences));
    }

    // Função para rejeitar todos os cookies
    function rejectAllCookies() {
        const preferences = {
            necessary: true,
            analytics: false,
            marketing: false,
            timestamp: new Date().getTime()
        };
        
        setCookie(config.cookieName, JSON.stringify(preferences));
    }

    // Função para definir um cookie
    function setCookie(name, value) {
        const date = new Date();
        date.setTime(date.getTime() + (config.cookieExpiry * 24 * 60 * 60 * 1000));
        
        const cookie = [
            `${name}=${encodeURIComponent(value)}`,
            `expires=${date.toUTCString()}`,
            `path=${config.cookiePath}`,
            `domain=${config.cookieDomain}`
        ];
        
        if (config.cookieSecure) {
            cookie.push('secure');
        }
        
        document.cookie = cookie.join('; ');
    }

    // Função para obter um cookie
    function getCookie(name) {
        const cookies = document.cookie.split(';');
        
        for (let i = 0; i < cookies.length; i++) {
            const cookie = cookies[i].trim();
            if (cookie.startsWith(name + '=')) {
                try {
                    return JSON.parse(decodeURIComponent(cookie.substring(name.length + 1)));
                } catch (e) {
                    return null;
                }
            }
        }
        
        return null;
    }

    // Função para verificar se o banner deve ser mostrado
    function shouldShowBanner() {
        const preferences = getCookie(config.cookieName);
        return !preferences;
    }

    // Função para inicializar
    function init() {
        if (shouldShowBanner()) {
            createCookieBanner();
        }
        
        // Adicionar estilos dinâmicos
        const style = document.createElement('style');
        style.textContent = `
            .cookie-banner {
                position: fixed;
                bottom: 0;
                left: 0;
                right: 0;
                background: #fff;
                padding: 1rem;
                box-shadow: 0 -2px 10px rgba(0, 0, 0, 0.1);
                z-index: 1000;
            }
            
            .cookie-content {
                max-width: 1200px;
                margin: 0 auto;
                display: flex;
                align-items: center;
                justify-content: space-between;
                gap: 1rem;
            }
            
            .cookie-buttons {
                display: flex;
                gap: 0.5rem;
            }
            
            .cookie-banner button {
                padding: 0.5rem 1rem;
                border: none;
                border-radius: 4px;
                cursor: pointer;
                font-weight: 500;
            }
            
            .cookie-accept {
                background: #007bff;
                color: #fff;
            }
            
            .cookie-settings {
                background: #f8f9fa;
                color: #333;
            }
            
            .cookie-modal {
                position: fixed;
                top: 0;
                left: 0;
                right: 0;
                bottom: 0;
                background: rgba(0, 0, 0, 0.5);
                display: flex;
                align-items: center;
                justify-content: center;
                z-index: 1001;
            }
            
            .cookie-modal-content {
                background: #fff;
                padding: 2rem;
                border-radius: 8px;
                max-width: 500px;
                width: 90%;
                max-height: 90vh;
                overflow-y: auto;
            }
            
            .cookie-options {
                margin: 1.5rem 0;
            }
            
            .cookie-option {
                margin-bottom: 1rem;
            }
            
            .cookie-option label {
                display: flex;
                flex-direction: column;
                gap: 0.25rem;
            }
            
            .cookie-description {
                font-size: 0.875rem;
                color: #666;
            }
            
            .cookie-modal-buttons {
                display: flex;
                gap: 0.5rem;
                flex-wrap: wrap;
            }
            
            .cookie-modal-buttons button {
                padding: 0.5rem 1rem;
                border: none;
                border-radius: 4px;
                cursor: pointer;
                font-weight: 500;
            }
            
            .cookie-save {
                background: #28a745;
                color: #fff;
            }
            
            .cookie-accept-all {
                background: #007bff;
                color: #fff;
            }
            
            .cookie-reject-all {
                background: #dc3545;
                color: #fff;
            }
            
            @media (max-width: 768px) {
                .cookie-content {
                    flex-direction: column;
                    text-align: center;
                }
                
                .cookie-buttons {
                    width: 100%;
                    justify-content: center;
                }
            }
        `;
        
        document.head.appendChild(style);
    }

    // Inicializar quando o DOM estiver pronto
    document.addEventListener('DOMContentLoaded', init);

})(); 