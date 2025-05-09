/**
 * Script para a funcionalidade de busca
 */
(function() {
    'use strict';

    // Configurações
    const config = {
        minLength: 3,
        delay: 300,
        maxResults: 5
    };

    // Função para inicializar a busca
    function initSearch() {
        const searchForm = document.querySelector('.search-form');
        const searchInput = document.querySelector('.search-field');
        const searchResults = document.createElement('div');
        searchResults.className = 'search-results';
        searchForm.appendChild(searchResults);

        let searchTimeout;

        // Função para realizar a busca
        async function performSearch(query) {
            if (query.length < config.minLength) {
                searchResults.innerHTML = '';
                searchResults.style.display = 'none';
                return;
            }

            try {
                const response = await fetch(`/wp-json/wp/v2/search?search=${encodeURIComponent(query)}&per_page=${config.maxResults}`);
                
                if (!response.ok) {
                    throw new Error('Erro ao realizar a busca');
                }

                const results = await response.json();
                displayResults(results);
            } catch (error) {
                console.error('Erro na busca:', error);
                searchResults.innerHTML = '<div class="search-error">Erro ao realizar a busca. Tente novamente.</div>';
                searchResults.style.display = 'block';
            }
        }

        // Função para exibir os resultados
        function displayResults(results) {
            if (results.length === 0) {
                searchResults.innerHTML = '<div class="no-results">Nenhum resultado encontrado.</div>';
                searchResults.style.display = 'block';
                return;
            }

            const resultsList = document.createElement('ul');
            resultsList.className = 'results-list';

            results.forEach(result => {
                const item = document.createElement('li');
                item.className = 'result-item';

                const link = document.createElement('a');
                link.href = result.url;
                link.textContent = result.title;
                link.className = 'result-link';

                const type = document.createElement('span');
                type.className = 'result-type';
                type.textContent = getResultType(result.subtype);

                item.appendChild(link);
                item.appendChild(type);
                resultsList.appendChild(item);
            });

            searchResults.innerHTML = '';
            searchResults.appendChild(resultsList);
            searchResults.style.display = 'block';
        }

        // Função para obter o tipo do resultado
        function getResultType(type) {
            const types = {
                post: 'Artigo',
                page: 'Página',
                attachment: 'Mídia',
                category: 'Categoria',
                post_tag: 'Tag',
                author: 'Autor'
            };

            return types[type] || type;
        }

        // Event listeners
        searchInput.addEventListener('input', function() {
            clearTimeout(searchTimeout);
            searchTimeout = setTimeout(() => {
                performSearch(this.value.trim());
            }, config.delay);
        });

        searchInput.addEventListener('focus', function() {
            if (this.value.trim().length >= config.minLength) {
                performSearch(this.value.trim());
            }
        });

        // Fechar resultados ao clicar fora
        document.addEventListener('click', function(e) {
            if (!searchForm.contains(e.target)) {
                searchResults.style.display = 'none';
            }
        });

        // Navegação por teclado
        searchInput.addEventListener('keydown', function(e) {
            const results = searchResults.querySelectorAll('.result-link');
            const currentIndex = Array.from(results).findIndex(link => link === document.activeElement);

            switch (e.key) {
                case 'ArrowDown':
                    e.preventDefault();
                    if (currentIndex < results.length - 1) {
                        results[currentIndex + 1].focus();
                    }
                    break;
                case 'ArrowUp':
                    e.preventDefault();
                    if (currentIndex > 0) {
                        results[currentIndex - 1].focus();
                    }
                    break;
                case 'Escape':
                    searchResults.style.display = 'none';
                    this.blur();
                    break;
            }
        });
    }

    // Função para inicializar todas as funcionalidades de busca
    function init() {
        initSearch();
        
        // Adicionar estilos dinâmicos
        const style = document.createElement('style');
        style.textContent = `
            .search-form {
                position: relative;
            }
            
            .search-results {
                position: absolute;
                top: 100%;
                left: 0;
                right: 0;
                background: #fff;
                border: 1px solid #ddd;
                border-radius: 4px;
                box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
                z-index: 1000;
                display: none;
                margin-top: 5px;
            }
            
            .results-list {
                list-style: none;
                margin: 0;
                padding: 0;
            }
            
            .result-item {
                padding: 10px;
                border-bottom: 1px solid #eee;
            }
            
            .result-item:last-child {
                border-bottom: none;
            }
            
            .result-link {
                display: block;
                color: #333;
                text-decoration: none;
                font-weight: 500;
            }
            
            .result-link:hover,
            .result-link:focus {
                color: #007bff;
            }
            
            .result-type {
                display: block;
                font-size: 0.8em;
                color: #666;
                margin-top: 2px;
            }
            
            .no-results,
            .search-error {
                padding: 10px;
                color: #666;
                text-align: center;
            }
            
            .search-error {
                color: #dc3545;
            }
            
            .search-field:focus {
                outline: none;
                border-color: #007bff;
                box-shadow: 0 0 0 0.2rem rgba(0, 123, 255, 0.25);
            }
        `;
        
        document.head.appendChild(style);
    }

    // Inicializar quando o DOM estiver pronto
    document.addEventListener('DOMContentLoaded', init);

})(); 