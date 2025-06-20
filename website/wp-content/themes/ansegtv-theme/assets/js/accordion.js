/**
 * Script para o acordeão
 */
(function() {
    'use strict';

    // Configurações
    const config = {
        animationDuration: 300,
        multipleOpen: false,
        language: 'pt-BR'
    };

    // Traduções
    const translations = {
        'pt-BR': {
            expand: 'Expandir',
            collapse: 'Recolher'
        }
    };

    // Função para criar o acordeão
    function createAccordion(container) {
        const accordion = document.createElement('div');
        accordion.className = 'accordion';
        
        // Adicionar elementos
        container.appendChild(accordion);
        
        // Estado do acordeão
        let openItems = new Set();
        
        // Função para criar item
        function createItem(index, title, content) {
            const item = document.createElement('div');
            item.className = 'accordion-item';
            
            // Cabeçalho
            const header = document.createElement('button');
            header.className = 'accordion-header';
            header.setAttribute('aria-expanded', 'false');
            header.setAttribute('aria-controls', `accordion-content-${index}`);
            header.setAttribute('id', `accordion-header-${index}`);
            header.innerHTML = `
                <span class="accordion-title">${title}</span>
                <span class="accordion-icon"></span>
            `;
            
            // Conteúdo
            const contentElement = document.createElement('div');
            contentElement.className = 'accordion-content';
            contentElement.setAttribute('aria-labelledby', `accordion-header-${index}`);
            contentElement.setAttribute('id', `accordion-content-${index}`);
            contentElement.innerHTML = content;
            
            // Adicionar elementos
            item.appendChild(header);
            item.appendChild(contentElement);
            
            // Event listeners
            header.addEventListener('click', () => {
                toggleItem(index);
            });
            
            return item;
        }
        
        // Função para alternar item
        function toggleItem(index) {
            const item = accordion.children[index];
            const header = item.querySelector('.accordion-header');
            const content = item.querySelector('.accordion-content');
            const isOpen = header.getAttribute('aria-expanded') === 'true';
            
            if (isOpen) {
                // Fechar item
                header.setAttribute('aria-expanded', 'false');
                header.setAttribute('aria-label', translations[config.language].expand);
                content.style.maxHeight = '0';
                item.classList.remove('active');
                openItems.delete(index);
            } else {
                // Fechar outros itens se não permitir múltiplos abertos
                if (!config.multipleOpen) {
                    openItems.forEach(i => {
                        if (i !== index) {
                            const otherItem = accordion.children[i];
                            const otherHeader = otherItem.querySelector('.accordion-header');
                            const otherContent = otherItem.querySelector('.accordion-content');
                            
                            otherHeader.setAttribute('aria-expanded', 'false');
                            otherHeader.setAttribute('aria-label', translations[config.language].expand);
                            otherContent.style.maxHeight = '0';
                            otherItem.classList.remove('active');
                            openItems.delete(i);
                        }
                    });
                }
                
                // Abrir item
                header.setAttribute('aria-expanded', 'true');
                header.setAttribute('aria-label', translations[config.language].collapse);
                content.style.maxHeight = content.scrollHeight + 'px';
                item.classList.add('active');
                openItems.add(index);
            }
            
            // Disparar evento
            const event = new CustomEvent('accordionChange', {
                detail: {
                    index,
                    isOpen: !isOpen,
                    title
                }
            });
            
            accordion.dispatchEvent(event);
        }
        
        // Função para inicializar
        function init() {
            // Obter itens do acordeão
            const items = Array.from(container.querySelectorAll('.accordion-item')).map(item => ({
                title: item.getAttribute('data-title') || `Item ${items.length + 1}`,
                content: item.innerHTML
            }));
            
            // Limpar container
            container.innerHTML = '';
            
            // Criar itens
            items.forEach((item, index) => {
                accordion.appendChild(createItem(index, item.title, item.content));
            });
        }
        
        // Event listeners
        accordion.addEventListener('keydown', (e) => {
            const items = accordion.querySelectorAll('.accordion-item');
            const currentIndex = Array.from(items).indexOf(e.target.closest('.accordion-item'));
            
            switch (e.key) {
                case 'ArrowUp':
                    e.preventDefault();
                    if (currentIndex > 0) {
                        items[currentIndex - 1].querySelector('.accordion-header').focus();
                    }
                    break;
                    
                case 'ArrowDown':
                    e.preventDefault();
                    if (currentIndex < items.length - 1) {
                        items[currentIndex + 1].querySelector('.accordion-header').focus();
                    }
                    break;
                    
                case 'Home':
                    e.preventDefault();
                    items[0].querySelector('.accordion-header').focus();
                    break;
                    
                case 'End':
                    e.preventDefault();
                    items[items.length - 1].querySelector('.accordion-header').focus();
                    break;
            }
        });
        
        // Inicializar
        init();
        
        return accordion;
    }

    // Função para inicializar
    function init() {
        // Adicionar estilos dinâmicos
        const style = document.createElement('style');
        style.textContent = `
            .accordion {
                width: 100%;
            }
            
            .accordion-item {
                border: 1px solid var(--border-color);
                border-radius: 4px;
                margin-bottom: 0.5rem;
            }
            
            .accordion-item:last-child {
                margin-bottom: 0;
            }
            
            .accordion-header {
                width: 100%;
                background: none;
                border: none;
                padding: 1rem;
                text-align: left;
                font-size: 1rem;
                cursor: pointer;
                display: flex;
                justify-content: space-between;
                align-items: center;
                transition: background-color ${config.animationDuration}ms ease;
            }
            
            .accordion-header:hover {
                background-color: var(--hover-color);
            }
            
            .accordion-icon {
                width: 12px;
                height: 12px;
                border-right: 2px solid currentColor;
                border-bottom: 2px solid currentColor;
                transform: rotate(45deg);
                transition: transform ${config.animationDuration}ms ease;
            }
            
            .accordion-item.active .accordion-icon {
                transform: rotate(-135deg);
            }
            
            .accordion-content {
                max-height: 0;
                overflow: hidden;
                transition: max-height ${config.animationDuration}ms ease;
            }
            
            .accordion-content > * {
                padding: 1rem;
                margin: 0;
            }
            
            .accordion-content > *:first-child {
                padding-top: 0;
            }
            
            .accordion-content > *:last-child {
                padding-bottom: 0;
            }
        `;
        
        document.head.appendChild(style);
        
        // Inicializar acordeões
        document.querySelectorAll('.accordion-container').forEach(container => {
            createAccordion(container);
        });
    }

    // Inicializar quando o DOM estiver pronto
    document.addEventListener('DOMContentLoaded', init);

})(); 