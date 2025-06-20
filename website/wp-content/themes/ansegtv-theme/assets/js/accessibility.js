/**
 * Funcionalidades de acessibilidade
 */

(function() {
    'use strict';

    // Adiciona suporte a navegação por teclado
    document.addEventListener('keydown', function(e) {
        // Tab
        if (e.key === 'Tab') {
            document.body.classList.add('user-is-tabbing');
        }
    });

    document.addEventListener('mousedown', function() {
        document.body.classList.remove('user-is-tabbing');
    });

    // Adiciona suporte a skip links
    const skipLinks = document.querySelectorAll('.skip-link');
    skipLinks.forEach(function(skipLink) {
        skipLink.addEventListener('click', function(e) {
            e.preventDefault();
            const targetId = this.getAttribute('href');
            const targetElement = document.querySelector(targetId);
            if (targetElement) {
                targetElement.setAttribute('tabindex', '-1');
                targetElement.focus();
                targetElement.removeAttribute('tabindex');
            }
        });
    });

    // Adiciona suporte a alto contraste
    const highContrastToggle = document.createElement('button');
    highContrastToggle.setAttribute('aria-label', 'Alternar modo de alto contraste');
    highContrastToggle.setAttribute('aria-pressed', 'false');
    highContrastToggle.classList.add('high-contrast-toggle');
    highContrastToggle.innerHTML = '<span class="screen-reader-text">Alto Contraste</span>';
    document.body.appendChild(highContrastToggle);

    highContrastToggle.addEventListener('click', function() {
        const isPressed = this.getAttribute('aria-pressed') === 'true';
        this.setAttribute('aria-pressed', !isPressed);
        document.body.classList.toggle('high-contrast');
    });

    // Adiciona suporte a aria-expanded
    const expandableElements = document.querySelectorAll('[aria-expanded]');
    expandableElements.forEach(function(element) {
        element.addEventListener('click', function() {
            const isExpanded = this.getAttribute('aria-expanded') === 'true';
            this.setAttribute('aria-expanded', !isExpanded);
        });
    });

    // Adiciona suporte a aria-controls
    const controlledElements = document.querySelectorAll('[aria-controls]');
    controlledElements.forEach(function(element) {
        const targetId = element.getAttribute('aria-controls');
        const targetElement = document.getElementById(targetId);
        if (targetElement) {
            element.addEventListener('click', function() {
                const isExpanded = this.getAttribute('aria-expanded') === 'true';
                this.setAttribute('aria-expanded', !isExpanded);
                targetElement.setAttribute('aria-hidden', isExpanded);
            });
        }
    });

    // Adiciona suporte a aria-live
    const liveRegions = document.querySelectorAll('[aria-live]');
    liveRegions.forEach(function(region) {
        const observer = new MutationObserver(function(mutations) {
            mutations.forEach(function(mutation) {
                if (mutation.type === 'childList') {
                    region.setAttribute('aria-busy', 'true');
                    setTimeout(function() {
                        region.setAttribute('aria-busy', 'false');
                    }, 100);
                }
            });
        });

        observer.observe(region, {
            childList: true,
            subtree: true
        });
    });

    // Adiciona suporte a aria-describedby
    const describedElements = document.querySelectorAll('[aria-describedby]');
    describedElements.forEach(function(element) {
        const descriptionId = element.getAttribute('aria-describedby');
        const descriptionElement = document.getElementById(descriptionId);
        if (descriptionElement) {
            element.addEventListener('focus', function() {
                descriptionElement.classList.add('visible');
            });

            element.addEventListener('blur', function() {
                descriptionElement.classList.remove('visible');
            });
        }
    });

    // Adiciona suporte a aria-current
    const currentElements = document.querySelectorAll('[aria-current]');
    currentElements.forEach(function(element) {
        element.addEventListener('click', function(e) {
            currentElements.forEach(function(el) {
                el.removeAttribute('aria-current');
            });
            this.setAttribute('aria-current', 'page');
        });
    });

    // Adiciona suporte a aria-selected
    const tabElements = document.querySelectorAll('[role="tab"]');
    tabElements.forEach(function(tab) {
        tab.addEventListener('click', function(e) {
            e.preventDefault();
            const tabList = this.closest('[role="tablist"]');
            const tabs = tabList.querySelectorAll('[role="tab"]');
            const targetId = this.getAttribute('aria-controls');
            const targetPanel = document.getElementById(targetId);

            tabs.forEach(function(t) {
                t.setAttribute('aria-selected', 'false');
            });
            this.setAttribute('aria-selected', 'true');

            if (targetPanel) {
                const panels = targetPanel.parentElement.querySelectorAll('[role="tabpanel"]');
                panels.forEach(function(p) {
                    p.setAttribute('aria-hidden', 'true');
                });
                targetPanel.setAttribute('aria-hidden', 'false');
            }
        });
    });

    // Adiciona suporte a aria-checked
    const checkableElements = document.querySelectorAll('[role="checkbox"], [role="radio"], [role="switch"]');
    checkableElements.forEach(function(element) {
        element.addEventListener('click', function() {
            const isChecked = this.getAttribute('aria-checked') === 'true';
            this.setAttribute('aria-checked', !isChecked);
        });
    });

    // Adiciona suporte a aria-haspopup
    const popupElements = document.querySelectorAll('[aria-haspopup]');
    popupElements.forEach(function(element) {
        element.addEventListener('click', function() {
            const popupId = this.getAttribute('aria-controls');
            const popupElement = document.getElementById(popupId);
            if (popupElement) {
                const isExpanded = this.getAttribute('aria-expanded') === 'true';
                this.setAttribute('aria-expanded', !isExpanded);
                popupElement.setAttribute('aria-hidden', isExpanded);
            }
        });
    });

    // Adiciona suporte a aria-invalid
    const formElements = document.querySelectorAll('input, textarea, select');
    formElements.forEach(function(element) {
        element.addEventListener('invalid', function() {
            this.setAttribute('aria-invalid', 'true');
        });

        element.addEventListener('input', function() {
            if (this.validity.valid) {
                this.setAttribute('aria-invalid', 'false');
            }
        });
    });

    // Adiciona suporte a aria-required
    const requiredElements = document.querySelectorAll('[required]');
    requiredElements.forEach(function(element) {
        element.setAttribute('aria-required', 'true');
    });

    // Adiciona suporte a aria-disabled
    const disabledElements = document.querySelectorAll('[disabled]');
    disabledElements.forEach(function(element) {
        element.setAttribute('aria-disabled', 'true');
    });

    // Adiciona suporte a aria-hidden
    const hiddenElements = document.querySelectorAll('[hidden]');
    hiddenElements.forEach(function(element) {
        element.setAttribute('aria-hidden', 'true');
    });

    // Adiciona suporte a aria-label
    const labeledElements = document.querySelectorAll('[aria-label]');
    labeledElements.forEach(function(element) {
        const label = element.getAttribute('aria-label');
        if (label) {
            element.setAttribute('title', label);
        }
    });

    // Adiciona suporte a aria-describedby
    const describedElements2 = document.querySelectorAll('[aria-describedby]');
    describedElements2.forEach(function(element) {
        const descriptionId = element.getAttribute('aria-describedby');
        const descriptionElement = document.getElementById(descriptionId);
        if (descriptionElement) {
            element.setAttribute('title', descriptionElement.textContent);
        }
    });

    // Adiciona suporte a aria-live
    const liveRegions2 = document.querySelectorAll('[aria-live]');
    liveRegions2.forEach(function(region) {
        const observer = new MutationObserver(function(mutations) {
            mutations.forEach(function(mutation) {
                if (mutation.type === 'childList') {
                    region.setAttribute('aria-busy', 'true');
                    setTimeout(function() {
                        region.setAttribute('aria-busy', 'false');
                    }, 100);
                }
            });
        });

        observer.observe(region, {
            childList: true,
            subtree: true
        });
    });

    // Adiciona suporte a aria-busy
    const busyElements = document.querySelectorAll('[aria-busy]');
    busyElements.forEach(function(element) {
        element.addEventListener('click', function() {
            this.setAttribute('aria-busy', 'true');
            setTimeout(function() {
                element.setAttribute('aria-busy', 'false');
            }, 1000);
        });
    });

    // Adiciona suporte a aria-relevant
    const relevantElements = document.querySelectorAll('[aria-relevant]');
    relevantElements.forEach(function(element) {
        const observer = new MutationObserver(function(mutations) {
            mutations.forEach(function(mutation) {
                if (mutation.type === 'childList') {
                    element.setAttribute('aria-busy', 'true');
                    setTimeout(function() {
                        element.setAttribute('aria-busy', 'false');
                    }, 100);
                }
            });
        });

        observer.observe(element, {
            childList: true,
            subtree: true
        });
    });

    // Adiciona suporte a aria-atomic
    const atomicElements = document.querySelectorAll('[aria-atomic]');
    atomicElements.forEach(function(element) {
        const observer = new MutationObserver(function(mutations) {
            mutations.forEach(function(mutation) {
                if (mutation.type === 'childList') {
                    element.setAttribute('aria-busy', 'true');
                    setTimeout(function() {
                        element.setAttribute('aria-busy', 'false');
                    }, 100);
                }
            });
        });

        observer.observe(element, {
            childList: true,
            subtree: true
        });
    });

    // Adiciona suporte a aria-dropeffect
    const dropeffectElements = document.querySelectorAll('[aria-dropeffect]');
    dropeffectElements.forEach(function(element) {
        element.addEventListener('dragstart', function() {
            this.setAttribute('aria-dropeffect', 'move');
        });

        element.addEventListener('dragend', function() {
            this.removeAttribute('aria-dropeffect');
        });
    });

    // Adiciona suporte a aria-grabbed
    const grabbedElements = document.querySelectorAll('[aria-grabbed]');
    grabbedElements.forEach(function(element) {
        element.addEventListener('dragstart', function() {
            this.setAttribute('aria-grabbed', 'true');
        });

        element.addEventListener('dragend', function() {
            this.setAttribute('aria-grabbed', 'false');
        });
    });

    // Adiciona suporte a aria-sort
    const sortElements = document.querySelectorAll('[aria-sort]');
    sortElements.forEach(function(element) {
        element.addEventListener('click', function() {
            const currentSort = this.getAttribute('aria-sort');
            let newSort;

            switch (currentSort) {
                case 'none':
                    newSort = 'ascending';
                    break;
                case 'ascending':
                    newSort = 'descending';
                    break;
                case 'descending':
                    newSort = 'none';
                    break;
                default:
                    newSort = 'none';
            }

            this.setAttribute('aria-sort', newSort);
        });
    });

    // Adiciona suporte a aria-valuemin, aria-valuemax, aria-valuenow
    const rangeElements = document.querySelectorAll('input[type="range"]');
    rangeElements.forEach(function(element) {
        element.setAttribute('aria-valuemin', element.min);
        element.setAttribute('aria-valuemax', element.max);
        element.setAttribute('aria-valuenow', element.value);

        element.addEventListener('input', function() {
            this.setAttribute('aria-valuenow', this.value);
        });
    });

    // Adiciona suporte a aria-valuetext
    const valuetextElements = document.querySelectorAll('[aria-valuetext]');
    valuetextElements.forEach(function(element) {
        element.addEventListener('input', function() {
            const value = this.value;
            let text;

            switch (value) {
                case '0':
                    text = 'Muito baixo';
                    break;
                case '25':
                    text = 'Baixo';
                    break;
                case '50':
                    text = 'Médio';
                    break;
                case '75':
                    text = 'Alto';
                    break;
                case '100':
                    text = 'Muito alto';
                    break;
                default:
                    text = value;
            }

            this.setAttribute('aria-valuetext', text);
        });
    });

    // Adiciona suporte a aria-orientation
    const orientationElements = document.querySelectorAll('[aria-orientation]');
    orientationElements.forEach(function(element) {
        element.addEventListener('keydown', function(e) {
            const orientation = this.getAttribute('aria-orientation');
            const currentValue = parseInt(this.getAttribute('aria-valuenow'));

            switch (e.key) {
                case 'ArrowUp':
                case 'ArrowRight':
                    if (orientation === 'vertical' || orientation === 'horizontal') {
                        e.preventDefault();
                        this.setAttribute('aria-valuenow', currentValue + 1);
                    }
                    break;
                case 'ArrowDown':
                case 'ArrowLeft':
                    if (orientation === 'vertical' || orientation === 'horizontal') {
                        e.preventDefault();
                        this.setAttribute('aria-valuenow', currentValue - 1);
                    }
                    break;
            }
        });
    });

    // Adiciona suporte a aria-posinset
    const posinsetElements = document.querySelectorAll('[aria-posinset]');
    posinsetElements.forEach(function(element, index) {
        element.setAttribute('aria-posinset', index + 1);
    });

    // Adiciona suporte a aria-setsize
    const setsizeElements = document.querySelectorAll('[aria-setsize]');
    setsizeElements.forEach(function(element) {
        const parent = element.parentElement;
        const siblings = parent.querySelectorAll(element.tagName);
        element.setAttribute('aria-setsize', siblings.length);
    });

    // Adiciona suporte a aria-level
    const levelElements = document.querySelectorAll('[aria-level]');
    levelElements.forEach(function(element) {
        const level = parseInt(element.getAttribute('aria-level'));
        element.style.fontSize = (1 + (level * 0.2)) + 'em';
        element.style.fontWeight = 600 + (level * 100);
    });

    // Adiciona suporte a aria-multiline
    const multilineElements = document.querySelectorAll('[aria-multiline]');
    multilineElements.forEach(function(element) {
        if (element.getAttribute('aria-multiline') === 'true') {
            element.style.whiteSpace = 'pre-wrap';
        }
    });

    // Adiciona suporte a aria-multiselectable
    const multiselectableElements = document.querySelectorAll('[aria-multiselectable]');
    multiselectableElements.forEach(function(element) {
        if (element.getAttribute('aria-multiselectable') === 'true') {
            element.addEventListener('click', function(e) {
                if (e.ctrlKey || e.metaKey) {
                    e.preventDefault();
                    const target = e.target;
                    if (target.getAttribute('aria-selected') === 'true') {
                        target.setAttribute('aria-selected', 'false');
                    } else {
                        target.setAttribute('aria-selected', 'true');
                    }
                }
            });
        }
    });

    // Adiciona suporte a aria-readonly
    const readonlyElements = document.querySelectorAll('[aria-readonly]');
    readonlyElements.forEach(function(element) {
        if (element.getAttribute('aria-readonly') === 'true') {
            element.setAttribute('readonly', 'readonly');
        }
    });

    // Adiciona suporte a aria-required
    const requiredElements2 = document.querySelectorAll('[aria-required]');
    requiredElements2.forEach(function(element) {
        if (element.getAttribute('aria-required') === 'true') {
            element.setAttribute('required', 'required');
        }
    });

    // Adiciona suporte a aria-selected
    const selectedElements = document.querySelectorAll('[aria-selected]');
    selectedElements.forEach(function(element) {
        if (element.getAttribute('aria-selected') === 'true') {
            element.classList.add('selected');
        }
    });

    // Adiciona suporte a aria-sort
    const sortElements2 = document.querySelectorAll('[aria-sort]');
    sortElements2.forEach(function(element) {
        const sort = element.getAttribute('aria-sort');
        if (sort) {
            element.classList.add('sort-' + sort);
        }
    });

    // Adiciona suporte a aria-valuemin
    const valueminElements = document.querySelectorAll('[aria-valuemin]');
    valueminElements.forEach(function(element) {
        const min = element.getAttribute('aria-valuemin');
        if (min) {
            element.setAttribute('min', min);
        }
    });

    // Adiciona suporte a aria-valuemax
    const valuemaxElements = document.querySelectorAll('[aria-valuemax]');
    valuemaxElements.forEach(function(element) {
        const max = element.getAttribute('aria-valuemax');
        if (max) {
            element.setAttribute('max', max);
        }
    });

    // Adiciona suporte a aria-valuenow
    const valuenowElements = document.querySelectorAll('[aria-valuenow]');
    valuenowElements.forEach(function(element) {
        const now = element.getAttribute('aria-valuenow');
        if (now) {
            element.setAttribute('value', now);
        }
    });

    // Adiciona suporte a aria-valuetext
    const valuetextElements2 = document.querySelectorAll('[aria-valuetext]');
    valuetextElements2.forEach(function(element) {
        const text = element.getAttribute('aria-valuetext');
        if (text) {
            element.setAttribute('title', text);
        }
    });

    // Adiciona suporte a aria-busy
    const busyElements2 = document.querySelectorAll('[aria-busy]');
    busyElements2.forEach(function(element) {
        if (element.getAttribute('aria-busy') === 'true') {
            element.classList.add('busy');
        }
    });

    // Adiciona suporte a aria-checked
    const checkedElements = document.querySelectorAll('[aria-checked]');
    checkedElements.forEach(function(element) {
        if (element.getAttribute('aria-checked') === 'true') {
            element.classList.add('checked');
        }
    });

    // Adiciona suporte a aria-disabled
    const disabledElements2 = document.querySelectorAll('[aria-disabled]');
    disabledElements2.forEach(function(element) {
        if (element.getAttribute('aria-disabled') === 'true') {
            element.classList.add('disabled');
        }
    });

    // Adiciona suporte a aria-expanded
    const expandedElements = document.querySelectorAll('[aria-expanded]');
    expandedElements.forEach(function(element) {
        if (element.getAttribute('aria-expanded') === 'true') {
            element.classList.add('expanded');
        }
    });

    // Adiciona suporte a aria-hidden
    const hiddenElements2 = document.querySelectorAll('[aria-hidden]');
    hiddenElements2.forEach(function(element) {
        if (element.getAttribute('aria-hidden') === 'true') {
            element.classList.add('hidden');
        }
    });

    // Adiciona suporte a aria-invalid
    const invalidElements = document.querySelectorAll('[aria-invalid]');
    invalidElements.forEach(function(element) {
        if (element.getAttribute('aria-invalid') === 'true') {
            element.classList.add('invalid');
        }
    });

    // Adiciona suporte a aria-pressed
    const pressedElements = document.querySelectorAll('[aria-pressed]');
    pressedElements.forEach(function(element) {
        if (element.getAttribute('aria-pressed') === 'true') {
            element.classList.add('pressed');
        }
    });

    // Adiciona suporte a aria-selected
    const selectedElements2 = document.querySelectorAll('[aria-selected]');
    selectedElements2.forEach(function(element) {
        if (element.getAttribute('aria-selected') === 'true') {
            element.classList.add('selected');
        }
    });

})(); 