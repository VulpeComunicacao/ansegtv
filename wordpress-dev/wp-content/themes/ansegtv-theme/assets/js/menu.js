/**
 * Script para o menu
 */
(function() {
    'use strict';

    // Configurações
    const config = {
        animationDuration: 300,
        breakpoint: 768,
        sticky: true,
        stickyOffset: 0,
        dropdownHover: true,
        dropdownDelay: 200,
        language: 'pt-BR'
    };

    // Traduções
    const translations = {
        'pt-BR': {
            menu: 'Menu',
            close: 'Fechar',
            open: 'Abrir',
            submenu: 'Submenu'
        }
    };

    // Função para criar o menu
    function createMenu(container) {
        const menu = document.createElement('nav');
        menu.className = 'menu';
        
        // Botão do menu mobile
        const menuButton = document.createElement('button');
        menuButton.className = 'menu-button';
        menuButton.setAttribute('aria-label', translations[config.language].menu);
        menuButton.setAttribute('aria-expanded', 'false');
        menuButton.innerHTML = `
            <span class="menu-button-icon"></span>
            <span class="menu-button-text">${translations[config.language].menu}</span>
        `;
        
        // Lista do menu
        const menuList = document.createElement('ul');
        menuList.className = 'menu-list';
        
        // Adicionar elementos
        menu.appendChild(menuButton);
        menu.appendChild(menuList);
        
        container.appendChild(menu);
        
        // Estado do menu
        let isOpen = false;
        let isSticky = false;
        let dropdownTimeout = null;
        
        // Função para abrir o menu
        function openMenu() {
            menu.classList.add('active');
            menuButton.setAttribute('aria-expanded', 'true');
            document.body.style.overflow = 'hidden';
            isOpen = true;
        }
        
        // Função para fechar o menu
        function closeMenu() {
            menu.classList.remove('active');
            menuButton.setAttribute('aria-expanded', 'false');
            document.body.style.overflow = '';
            isOpen = false;
        }
        
        // Função para verificar se o menu deve ser sticky
        function checkSticky() {
            if (!config.sticky) return;
            
            const header = container.closest('header');
            if (!header) return;
            
            const headerRect = header.getBoundingClientRect();
            const shouldBeSticky = headerRect.top <= config.stickyOffset;
            
            if (shouldBeSticky !== isSticky) {
                isSticky = shouldBeSticky;
                header.classList.toggle('sticky', isSticky);
            }
        }
        
        // Função para criar dropdown
        function createDropdown(item) {
            const link = item.querySelector('a');
            const submenu = item.querySelector('ul');
            
            if (!link || !submenu) return;
            
            // Adicionar botão de toggle
            const toggle = document.createElement('button');
            toggle.className = 'menu-dropdown-toggle';
            toggle.setAttribute('aria-label', translations[config.language].submenu);
            toggle.setAttribute('aria-expanded', 'false');
            toggle.innerHTML = '<i class="fas fa-chevron-down"></i>';
            
            link.after(toggle);
            
            // Event listeners
            if (config.dropdownHover) {
                item.addEventListener('mouseenter', () => {
                    if (dropdownTimeout) {
                        clearTimeout(dropdownTimeout);
                    }
                    
                    toggle.setAttribute('aria-expanded', 'true');
                    item.classList.add('active');
                });
                
                item.addEventListener('mouseleave', () => {
                    dropdownTimeout = setTimeout(() => {
                        toggle.setAttribute('aria-expanded', 'false');
                        item.classList.remove('active');
                    }, config.dropdownDelay);
                });
            } else {
                toggle.addEventListener('click', (e) => {
                    e.preventDefault();
                    
                    const isExpanded = toggle.getAttribute('aria-expanded') === 'true';
                    
                    toggle.setAttribute('aria-expanded', !isExpanded);
                    item.classList.toggle('active');
                });
            }
        }
        
        // Event listeners
        menuButton.addEventListener('click', () => {
            if (isOpen) {
                closeMenu();
            } else {
                openMenu();
            }
        });
        
        // Event listener de clique fora
        document.addEventListener('click', (e) => {
            if (isOpen && !menu.contains(e.target)) {
                closeMenu();
            }
        });
        
        // Event listener de teclado
        menu.addEventListener('keydown', (e) => {
            if (e.key === 'Escape' && isOpen) {
                closeMenu();
            }
        });
        
        // Event listener de scroll
        if (config.sticky) {
            window.addEventListener('scroll', checkSticky);
            window.addEventListener('resize', checkSticky);
        }
        
        // Event listener de resize
        window.addEventListener('resize', () => {
            if (window.innerWidth > config.breakpoint && isOpen) {
                closeMenu();
            }
        });
        
        // Inicializar dropdowns
        menuList.querySelectorAll('.menu-item-has-children').forEach(createDropdown);
        
        // Inicializar sticky
        if (config.sticky) {
            checkSticky();
        }
        
        return menu;
    }

    // Função para inicializar
    function init() {
        // Adicionar estilos dinâmicos
        const style = document.createElement('style');
        style.textContent = `
            .menu {
                position: relative;
            }
            
            .menu-button {
                display: none;
                background: none;
                border: none;
                color: inherit;
                font-size: 1rem;
                cursor: pointer;
                padding: 0.5rem;
            }
            
            .menu-button-icon {
                display: block;
                width: 24px;
                height: 2px;
                background: currentColor;
                position: relative;
                transition: background-color ${config.animationDuration}ms ease;
            }
            
            .menu-button-icon::before,
            .menu-button-icon::after {
                content: '';
                position: absolute;
                width: 100%;
                height: 100%;
                background: currentColor;
                transition: transform ${config.animationDuration}ms ease;
            }
            
            .menu-button-icon::before {
                transform: translateY(-8px);
            }
            
            .menu-button-icon::after {
                transform: translateY(8px);
            }
            
            .menu.active .menu-button-icon {
                background: transparent;
            }
            
            .menu.active .menu-button-icon::before {
                transform: rotate(45deg);
            }
            
            .menu.active .menu-button-icon::after {
                transform: rotate(-45deg);
            }
            
            .menu-list {
                display: flex;
                list-style: none;
                margin: 0;
                padding: 0;
            }
            
            .menu-item {
                position: relative;
            }
            
            .menu-item a {
                display: block;
                padding: 0.5rem 1rem;
                color: inherit;
                text-decoration: none;
                transition: color ${config.animationDuration}ms ease;
            }
            
            .menu-item a:hover {
                color: var(--primary-color);
            }
            
            .menu-dropdown-toggle {
                display: none;
                background: none;
                border: none;
                color: inherit;
                font-size: 0.75rem;
                cursor: pointer;
                padding: 0.25rem;
                margin-left: 0.25rem;
                transition: transform ${config.animationDuration}ms ease;
            }
            
            .menu-item-has-children > a {
                display: inline-flex;
                align-items: center;
            }
            
            .menu-item-has-children > a::after {
                content: '';
                display: inline-block;
                width: 0;
                height: 0;
                border-left: 4px solid transparent;
                border-right: 4px solid transparent;
                border-top: 4px solid currentColor;
                margin-left: 0.5rem;
                transition: transform ${config.animationDuration}ms ease;
            }
            
            .menu-item-has-children.active > a::after {
                transform: rotate(180deg);
            }
            
            .sub-menu {
                position: absolute;
                top: 100%;
                left: 0;
                min-width: 200px;
                background: #fff;
                box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
                border-radius: 4px;
                opacity: 0;
                visibility: hidden;
                transform: translateY(10px);
                transition: all ${config.animationDuration}ms ease;
                z-index: 100;
            }
            
            .menu-item-has-children.active .sub-menu {
                opacity: 1;
                visibility: visible;
                transform: translateY(0);
            }
            
            .sub-menu .menu-item a {
                padding: 0.75rem 1rem;
            }
            
            .sub-menu .sub-menu {
                top: 0;
                left: 100%;
            }
            
            header.sticky {
                position: fixed;
                top: 0;
                left: 0;
                right: 0;
                z-index: 1000;
                background: #fff;
                box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
                animation: slideDown ${config.animationDuration}ms ease;
            }
            
            @keyframes slideDown {
                from {
                    transform: translateY(-100%);
                }
                to {
                    transform: translateY(0);
                }
            }
            
            @media (max-width: ${config.breakpoint}px) {
                .menu-button {
                    display: block;
                }
                
                .menu-list {
                    position: fixed;
                    top: 0;
                    left: 0;
                    right: 0;
                    bottom: 0;
                    background: #fff;
                    flex-direction: column;
                    padding: 4rem 1rem 1rem;
                    transform: translateX(-100%);
                    transition: transform ${config.animationDuration}ms ease;
                    z-index: 1000;
                }
                
                .menu.active .menu-list {
                    transform: translateX(0);
                }
                
                .menu-item {
                    margin: 0.5rem 0;
                }
                
                .menu-dropdown-toggle {
                    display: block;
                }
                
                .menu-item-has-children > a::after {
                    display: none;
                }
                
                .sub-menu {
                    position: static;
                    background: none;
                    box-shadow: none;
                    opacity: 1;
                    visibility: visible;
                    transform: none;
                    max-height: 0;
                    overflow: hidden;
                    transition: max-height ${config.animationDuration}ms ease;
                }
                
                .menu-item-has-children.active .sub-menu {
                    max-height: 1000px;
                }
                
                .sub-menu .menu-item a {
                    padding-left: 2rem;
                }
                
                .sub-menu .sub-menu .menu-item a {
                    padding-left: 3rem;
                }
            }
        `;
        
        document.head.appendChild(style);
        
        // Inicializar menus
        document.querySelectorAll('.menu-container').forEach(container => {
            createMenu(container);
        });
    }

    // Inicializar quando o DOM estiver pronto
    document.addEventListener('DOMContentLoaded', init);

})(); 