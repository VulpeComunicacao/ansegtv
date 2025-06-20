/**
 * Scripts personalizados do tema ANSEGTV
 */
(function() {
    'use strict';

    // Função para verificar se um elemento está visível na viewport
    function isElementInViewport(el) {
        const rect = el.getBoundingClientRect();
        return (
            rect.top >= 0 &&
            rect.left >= 0 &&
            rect.bottom <= (window.innerHeight || document.documentElement.clientHeight) &&
            rect.right <= (window.innerWidth || document.documentElement.clientWidth)
        );
    }

    // Função para animar elementos quando entram na viewport
    function animateOnScroll() {
        const elements = document.querySelectorAll('.animate-on-scroll');
        
        elements.forEach(element => {
            if (isElementInViewport(element)) {
                element.classList.add('animated');
            }
        });
    }

    // Função para voltar ao topo
    function scrollToTop() {
        const button = document.querySelector('.scroll-to-top');
        
        if (button) {
            window.addEventListener('scroll', () => {
                if (window.pageYOffset > 300) {
                    button.classList.add('show');
                } else {
                    button.classList.remove('show');
                }
            });

            button.addEventListener('click', (e) => {
                e.preventDefault();
                window.scrollTo({
                    top: 0,
                    behavior: 'smooth'
                });
            });
        }
    }

    // Função para lazy loading de imagens
    function lazyLoadImages() {
        const images = document.querySelectorAll('img[data-src]');
        
        if ('IntersectionObserver' in window) {
            const imageObserver = new IntersectionObserver((entries, observer) => {
                entries.forEach(entry => {
                    if (entry.isIntersecting) {
                        const img = entry.target;
                        img.src = img.dataset.src;
                        img.removeAttribute('data-src');
                        imageObserver.unobserve(img);
                    }
                });
            });

            images.forEach(img => imageObserver.observe(img));
        } else {
            // Fallback para navegadores que não suportam IntersectionObserver
            images.forEach(img => {
                img.src = img.dataset.src;
                img.removeAttribute('data-src');
            });
        }
    }

    // Função para ajustar a altura do header
    function adjustHeaderHeight() {
        const header = document.querySelector('.site-header');
        const headerHeight = header.offsetHeight;
        
        document.documentElement.style.setProperty('--header-height', `${headerHeight}px`);
    }

    // Função para inicializar o lightbox
    function initLightbox() {
        const galleryLinks = document.querySelectorAll('.gallery a');
        
        galleryLinks.forEach(link => {
            link.addEventListener('click', (e) => {
                e.preventDefault();
                
                const imgSrc = link.getAttribute('href');
                const imgAlt = link.querySelector('img').getAttribute('alt');
                
                const lightbox = document.createElement('div');
                lightbox.className = 'lightbox';
                lightbox.innerHTML = `
                    <div class="lightbox-content">
                        <img src="${imgSrc}" alt="${imgAlt}">
                        <button class="lightbox-close">&times;</button>
                    </div>
                `;
                
                document.body.appendChild(lightbox);
                
                lightbox.addEventListener('click', (e) => {
                    if (e.target === lightbox || e.target.className === 'lightbox-close') {
                        lightbox.remove();
                    }
                });
            });
        });
    }

    // Função para inicializar o menu mobile
    function initMobileMenu() {
        const menuToggle = document.querySelector('.menu-toggle');
        const navMenu = document.querySelector('.nav-menu');
        
        if (menuToggle && navMenu) {
            menuToggle.addEventListener('click', () => {
                menuToggle.classList.toggle('active');
                navMenu.classList.toggle('toggled');
            });

            // Fechar menu ao clicar em um link
            const navLinks = navMenu.querySelectorAll('a');
            navLinks.forEach(link => {
                link.addEventListener('click', () => {
                    menuToggle.classList.remove('active');
                    navMenu.classList.remove('toggled');
                });
            });
        }
    }

    // Função para inicializar o formulário de busca
    function initSearchForm() {
        const searchForm = document.querySelector('.search-form');
        
        if (searchForm) {
            const searchInput = searchForm.querySelector('input[type="search"]');
            
            searchInput.addEventListener('focus', () => {
                searchForm.classList.add('focused');
            });
            
            searchInput.addEventListener('blur', () => {
                if (!searchInput.value) {
                    searchForm.classList.remove('focused');
                }
            });
        }
    }

    // Função para inicializar o carrossel
    function initCarousel() {
        const carousels = document.querySelectorAll('.carousel');
        
        carousels.forEach(carousel => {
            const items = carousel.querySelectorAll('.carousel-item');
            const prevButton = carousel.querySelector('.carousel-prev');
            const nextButton = carousel.querySelector('.carousel-next');
            let currentIndex = 0;
            
            function updateCarousel() {
                items.forEach((item, index) => {
                    item.style.transform = `translateX(${100 * (index - currentIndex)}%)`;
                });
            }
            
            if (prevButton) {
                prevButton.addEventListener('click', () => {
                    currentIndex = Math.max(currentIndex - 1, 0);
                    updateCarousel();
                });
            }
            
            if (nextButton) {
                nextButton.addEventListener('click', () => {
                    currentIndex = Math.min(currentIndex + 1, items.length - 1);
                    updateCarousel();
                });
            }
            
            updateCarousel();
        });
    }

    // Inicializar todas as funções quando o DOM estiver pronto
    document.addEventListener('DOMContentLoaded', () => {
        animateOnScroll();
        scrollToTop();
        lazyLoadImages();
        adjustHeaderHeight();
        initLightbox();
        initMobileMenu();
        initSearchForm();
        initCarousel();
        
        // Adicionar event listeners
        window.addEventListener('scroll', animateOnScroll);
        window.addEventListener('resize', adjustHeaderHeight);
    });

})(); 