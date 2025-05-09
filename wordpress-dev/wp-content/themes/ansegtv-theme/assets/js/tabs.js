/**
 * Script para as abas
 */
(function() {
    'use strict';

    // Configurações
    const config = {
        animationDuration: 300,
        defaultTab: 0,
        hashNavigation: true,
        language: 'pt-BR'
    };

    // Traduções
    const translations = {
        'pt-BR': {
            tab: 'Aba',
            next: 'Próxima',
            prev: 'Anterior'
        }
    };

    // Função para criar as abas
    function createTabs(container) {
        const tabs = document.createElement('div');
        tabs.className = 'tabs';
        
        // Lista de abas
        const tabsList = document.createElement('div');
        tabsList.className = 'tabs-list';
        tabsList.setAttribute('role', 'tablist');
        
        // Container de conteúdo
        const tabsContent = document.createElement('div');
        tabsContent.className = 'tabs-content';
        
        // Adicionar elementos
        tabs.appendChild(tabsList);
        tabs.appendChild(tabsContent);
        
        container.appendChild(tabs);
        
        // Estado das abas
        let currentTab = config.defaultTab;
        let tabItems = [];
        
        // Função para criar aba
        function createTabItem(index, title) {
            const tabItem = document.createElement('button');
            tabItem.className = 'tabs-item';
            tabItem.setAttribute('role', 'tab');
            tabItem.setAttribute('aria-selected', index === currentTab);
            tabItem.setAttribute('aria-controls', `tab-panel-${index}`);
            tabItem.setAttribute('id', `tab-${index}`);
            tabItem.textContent = title;
            
            if (index === currentTab) {
                tabItem.classList.add('active');
            }
            
            tabItem.addEventListener('click', () => {
                showTab(index);
            });
            
            return tabItem;
        }
        
        // Função para criar painel
        function createTabPanel(index, content) {
            const tabPanel = document.createElement('div');
            tabPanel.className = 'tabs-panel';
            tabPanel.setAttribute('role', 'tabpanel');
            tabPanel.setAttribute('aria-labelledby', `tab-${index}`);
            tabPanel.setAttribute('id', `tab-panel-${index}`);
            tabPanel.innerHTML = content;
            
            if (index === currentTab) {
                tabPanel.classList.add('active');
            }
            
            return tabPanel;
        }
        
        // Função para mostrar aba
        function showTab(index) {
            if (index === currentTab) return;
            
            // Atualizar estado
            currentTab = index;
            
            // Atualizar abas
            tabsList.querySelectorAll('.tabs-item').forEach((item, i) => {
                item.classList.toggle('active', i === currentTab);
                item.setAttribute('aria-selected', i === currentTab);
            });
            
            // Atualizar painéis
            tabsContent.querySelectorAll('.tabs-panel').forEach((panel, i) => {
                panel.classList.toggle('active', i === currentTab);
            });
            
            // Atualizar URL
            if (config.hashNavigation) {
                const hash = tabItems[currentTab].title.toLowerCase().replace(/\s+/g, '-');
                history.pushState(null, null, `#${hash}`);
            }
            
            // Disparar evento
            const event = new CustomEvent('tabChange', {
                detail: {
                    index: currentTab,
                    title: tabItems[currentTab].title
                }
            });
            
            tabs.dispatchEvent(event);
        }
        
        // Função para inicializar
        function init() {
            // Obter itens das abas
            tabItems = Array.from(container.querySelectorAll('.tab-item')).map(item => ({
                title: item.getAttribute('data-title') || `Aba ${tabItems.length + 1}`,
                content: item.innerHTML
            }));
            
            // Limpar container
            container.innerHTML = '';
            
            // Criar abas
            tabItems.forEach((item, index) => {
                tabsList.appendChild(createTabItem(index, item.title));
                tabsContent.appendChild(createTabPanel(index, item.content));
            });
            
            // Verificar hash na URL
            if (config.hashNavigation) {
                const hash = window.location.hash.slice(1);
                if (hash) {
                    const index = tabItems.findIndex(item => 
                        item.title.toLowerCase().replace(/\s+/g, '-') === hash
                    );
                    
                    if (index !== -1) {
                        showTab(index);
                    }
                }
            }
        }
        
        // Event listeners
        tabsList.addEventListener('keydown', (e) => {
            const items = tabsList.querySelectorAll('.tabs-item');
            const currentIndex = Array.from(items).indexOf(document.activeElement);
            
            switch (e.key) {
                case 'ArrowLeft':
                    e.preventDefault();
                    showTab(currentIndex > 0 ? currentIndex - 1 : items.length - 1);
                    items[currentIndex > 0 ? currentIndex - 1 : items.length - 1].focus();
                    break;
                    
                case 'ArrowRight':
                    e.preventDefault();
                    showTab(currentIndex < items.length - 1 ? currentIndex + 1 : 0);
                    items[currentIndex < items.length - 1 ? currentIndex + 1 : 0].focus();
                    break;
                    
                case 'Home':
                    e.preventDefault();
                    showTab(0);
                    items[0].focus();
                    break;
                    
                case 'End':
                    e.preventDefault();
                    showTab(items.length - 1);
                    items[items.length - 1].focus();
                    break;
            }
        });
        
        // Inicializar
        init();
        
        return tabs;
    }

    // Função para inicializar
    function init() {
        // Adicionar estilos dinâmicos
        const style = document.createElement('style');
        style.textContent = `
            .tabs {
                width: 100%;
            }
            
            .tabs-list {
                display: flex;
                gap: 0.5rem;
                border-bottom: 1px solid var(--border-color);
                margin-bottom: 1rem;
            }
            
            .tabs-item {
                background: none;
                border: none;
                padding: 0.75rem 1.5rem;
                color: var(--text-color);
                font-size: 1rem;
                cursor: pointer;
                position: relative;
                transition: color ${config.animationDuration}ms ease;
            }
            
            .tabs-item::after {
                content: '';
                position: absolute;
                bottom: -1px;
                left: 0;
                right: 0;
                height: 2px;
                background: var(--primary-color);
                transform: scaleX(0);
                transition: transform ${config.animationDuration}ms ease;
            }
            
            .tabs-item:hover {
                color: var(--primary-color);
            }
            
            .tabs-item.active {
                color: var(--primary-color);
            }
            
            .tabs-item.active::after {
                transform: scaleX(1);
            }
            
            .tabs-panel {
                display: none;
                animation: fadeIn ${config.animationDuration}ms ease;
            }
            
            .tabs-panel.active {
                display: block;
            }
            
            @keyframes fadeIn {
                from {
                    opacity: 0;
                    transform: translateY(10px);
                }
                to {
                    opacity: 1;
                    transform: translateY(0);
                }
            }
            
            @media (max-width: 768px) {
                .tabs-list {
                    flex-wrap: wrap;
                }
                
                .tabs-item {
                    flex: 1;
                    min-width: 120px;
                    text-align: center;
                    padding: 0.5rem 1rem;
                }
            }
        `;
        
        document.head.appendChild(style);
        
        // Inicializar abas
        document.querySelectorAll('.tabs-container').forEach(container => {
            createTabs(container);
        });
    }

    // Inicializar quando o DOM estiver pronto
    document.addEventListener('DOMContentLoaded', init);

})(); 