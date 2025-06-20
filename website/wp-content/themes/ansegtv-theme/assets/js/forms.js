/**
 * Script para o tratamento dos formulários
 */
(function() {
    'use strict';

    // Função para inicializar os formulários
    function initForms() {
        const forms = document.querySelectorAll('form');
        
        forms.forEach(form => {
            // Função para validar o formulário
            function validateForm() {
                const requiredFields = form.querySelectorAll('[required]');
                let isValid = true;
                
                requiredFields.forEach(field => {
                    if (!field.value.trim()) {
                        isValid = false;
                        showError(field, 'Este campo é obrigatório.');
                    } else {
                        clearError(field);
                    }
                });
                
                // Validar email
                const emailFields = form.querySelectorAll('input[type="email"]');
                emailFields.forEach(field => {
                    if (field.value.trim() && !isValidEmail(field.value)) {
                        isValid = false;
                        showError(field, 'Por favor, insira um email válido.');
                    }
                });
                
                // Validar telefone
                const phoneFields = form.querySelectorAll('input[type="tel"]');
                phoneFields.forEach(field => {
                    if (field.value.trim() && !isValidPhone(field.value)) {
                        isValid = false;
                        showError(field, 'Por favor, insira um telefone válido.');
                    }
                });
                
                return isValid;
            }
            
            // Função para mostrar erro
            function showError(field, message) {
                const errorElement = field.parentElement.querySelector('.error-message') || document.createElement('div');
                errorElement.className = 'error-message';
                errorElement.textContent = message;
                
                if (!field.parentElement.querySelector('.error-message')) {
                    field.parentElement.appendChild(errorElement);
                }
                
                field.classList.add('error');
            }
            
            // Função para limpar erro
            function clearError(field) {
                const errorElement = field.parentElement.querySelector('.error-message');
                if (errorElement) {
                    errorElement.remove();
                }
                
                field.classList.remove('error');
            }
            
            // Função para validar email
            function isValidEmail(email) {
                const re = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
                return re.test(email);
            }
            
            // Função para validar telefone
            function isValidPhone(phone) {
                const re = /^\(\d{2}\) \d{4,5}-\d{4}$/;
                return re.test(phone);
            }
            
            // Função para formatar telefone
            function formatPhone(input) {
                let value = input.value.replace(/\D/g, '');
                
                if (value.length > 11) {
                    value = value.slice(0, 11);
                }
                
                if (value.length > 0) {
                    if (value.length <= 2) {
                        value = `(${value}`;
                    } else if (value.length <= 6) {
                        value = `(${value.slice(0, 2)}) ${value.slice(2)}`;
                    } else if (value.length <= 10) {
                        value = `(${value.slice(0, 2)}) ${value.slice(2, 6)}-${value.slice(6)}`;
                    } else {
                        value = `(${value.slice(0, 2)}) ${value.slice(2, 7)}-${value.slice(7)}`;
                    }
                }
                
                input.value = value;
            }
            
            // Função para enviar o formulário
            async function submitForm(e) {
                e.preventDefault();
                
                if (!validateForm()) {
                    return;
                }
                
                const submitButton = form.querySelector('button[type="submit"]');
                const originalText = submitButton.textContent;
                
                try {
                    submitButton.disabled = true;
                    submitButton.textContent = 'Enviando...';
                    
                    const formData = new FormData(form);
                    const response = await fetch(form.action, {
                        method: form.method,
                        body: formData
                    });
                    
                    if (!response.ok) {
                        throw new Error('Erro ao enviar o formulário');
                    }
                    
                    const data = await response.json();
                    
                    if (data.success) {
                        showSuccess(form, data.message || 'Formulário enviado com sucesso!');
                        form.reset();
                    } else {
                        throw new Error(data.message || 'Erro ao enviar o formulário');
                    }
                } catch (error) {
                    showError(form, error.message);
                } finally {
                    submitButton.disabled = false;
                    submitButton.textContent = originalText;
                }
            }
            
            // Função para mostrar mensagem de sucesso
            function showSuccess(form, message) {
                const successElement = document.createElement('div');
                successElement.className = 'success-message';
                successElement.textContent = message;
                
                form.insertBefore(successElement, form.firstChild);
                
                setTimeout(() => {
                    successElement.remove();
                }, 5000);
            }
            
            // Adicionar event listeners
            form.addEventListener('submit', submitForm);
            
            // Adicionar máscara de telefone
            const phoneInputs = form.querySelectorAll('input[type="tel"]');
            phoneInputs.forEach(input => {
                input.addEventListener('input', () => formatPhone(input));
            });
            
            // Adicionar validação em tempo real
            const inputs = form.querySelectorAll('input, textarea, select');
            inputs.forEach(input => {
                input.addEventListener('blur', () => {
                    if (input.hasAttribute('required')) {
                        if (!input.value.trim()) {
                            showError(input, 'Este campo é obrigatório.');
                        } else {
                            clearError(input);
                        }
                    }
                    
                    if (input.type === 'email' && input.value.trim()) {
                        if (!isValidEmail(input.value)) {
                            showError(input, 'Por favor, insira um email válido.');
                        } else {
                            clearError(input);
                        }
                    }
                    
                    if (input.type === 'tel' && input.value.trim()) {
                        if (!isValidPhone(input.value)) {
                            showError(input, 'Por favor, insira um telefone válido.');
                        } else {
                            clearError(input);
                        }
                    }
                });
            });
        });
        
        // Adicionar estilos dinâmicos
        const style = document.createElement('style');
        style.textContent = `
            .error-message {
                color: #dc3545;
                font-size: 0.875rem;
                margin-top: 0.25rem;
            }
            
            .success-message {
                color: #28a745;
                font-size: 0.875rem;
                margin-bottom: 1rem;
                padding: 0.5rem;
                background-color: #d4edda;
                border: 1px solid #c3e6cb;
                border-radius: 0.25rem;
            }
            
            input.error,
            textarea.error,
            select.error {
                border-color: #dc3545;
            }
            
            input.error:focus,
            textarea.error:focus,
            select.error:focus {
                border-color: #dc3545;
                box-shadow: 0 0 0 0.2rem rgba(220, 53, 69, 0.25);
            }
            
            button[type="submit"]:disabled {
                opacity: 0.65;
                cursor: not-allowed;
            }
        `;
        
        document.head.appendChild(style);
    }

    // Inicializar quando o DOM estiver pronto
    document.addEventListener('DOMContentLoaded', initForms);

})(); 