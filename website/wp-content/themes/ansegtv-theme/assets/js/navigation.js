/**
 * Manipulação do menu responsivo
 */
(function() {
    const siteNavigation = document.getElementById('site-navigation');

    // Retornar mais cedo se o menu não existir
    if (!siteNavigation) {
        return;
    }

    const button = siteNavigation.getElementsByTagName('button')[0];

    // Retornar mais cedo se o botão não existir
    if ('undefined' === typeof button) {
        return;
    }

    const menu = siteNavigation.getElementsByTagName('ul')[0];

    // Esconder o menu se não existir
    if ('undefined' === typeof menu) {
        button.style.display = 'none';
        return;
    }

    if (!menu.classList.contains('nav-menu')) {
        menu.classList.add('nav-menu');
    }

    // Alternar a classe .toggled e a atribuição aria-expanded cada vez que o botão é clicado
    button.addEventListener('click', function() {
        siteNavigation.classList.toggle('toggled');

        if (button.getAttribute('aria-expanded') === 'true') {
            button.setAttribute('aria-expanded', 'false');
        } else {
            button.setAttribute('aria-expanded', 'true');
        }
    });

    // Remover a classe .toggled e definir aria-expanded como false quando o usuário clicar fora da navegação
    document.addEventListener('click', function(event) {
        const isClickInside = siteNavigation.contains(event.target);

        if (!isClickInside) {
            siteNavigation.classList.remove('toggled');
            button.setAttribute('aria-expanded', 'false');
        }
    });

    // Obter todos os links dentro do menu
    const links = menu.getElementsByTagName('a');

    // Obter todos os links com submenus
    const linksWithChildren = menu.querySelectorAll('.menu-item-has-children > a, .page_item_has_children > a');

    // Alternar o foco cada vez que um link com submenu é tocado
    for (const link of linksWithChildren) {
        link.addEventListener('touchstart', function(event) {
            const menuItem = this.parentNode;

            event.preventDefault();
            for (const sibling of menuItem.parentNode.children) {
                if (sibling !== menuItem) {
                    sibling.classList.remove('focus');
                }
            }
            menuItem.classList.toggle('focus');
        });
    }

    // Fechar o menu quando um link é clicado
    for (const link of links) {
        link.addEventListener('click', function() {
            siteNavigation.classList.remove('toggled');
            button.setAttribute('aria-expanded', 'false');
        });
    }
})(); 