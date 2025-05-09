/**
 * Script para a funcionalidade de comentários
 */
(function() {
    'use strict';

    // Configurações
    const config = {
        replyText: 'Responder',
        cancelText: 'Cancelar',
        submitText: 'Enviar Comentário',
        loadingText: 'Enviando...',
        errorText: 'Erro ao enviar comentário. Tente novamente.',
        successText: 'Comentário enviado com sucesso!',
        minLength: 3,
        maxLength: 5000
    };

    // Função para inicializar os comentários
    function initComments() {
        const commentForm = document.querySelector('#commentform');
        if (!commentForm) return;

        // Adicionar contador de caracteres
        const commentField = commentForm.querySelector('#comment');
        if (commentField) {
            const counter = document.createElement('div');
            counter.className = 'comment-counter';
            counter.textContent = `0/${config.maxLength} caracteres`;
            commentField.parentNode.appendChild(counter);

            commentField.addEventListener('input', function() {
                const length = this.value.length;
                counter.textContent = `${length}/${config.maxLength} caracteres`;
                
                if (length > config.maxLength) {
                    counter.classList.add('error');
                } else {
                    counter.classList.remove('error');
                }
            });
        }

        // Adicionar botões de resposta
        document.querySelectorAll('.comment-reply-link').forEach(link => {
            link.addEventListener('click', function(e) {
                e.preventDefault();
                
                const commentId = this.getAttribute('data-commentid');
                const respondId = this.getAttribute('data-respondid');
                const postId = this.getAttribute('data-postid');
                
                const respond = document.getElementById(respondId);
                const cancel = document.createElement('button');
                cancel.type = 'button';
                cancel.className = 'cancel-comment-reply';
                cancel.textContent = config.cancelText;
                
                respond.parentNode.insertBefore(cancel, respond);
                
                // Mover o formulário para o local correto
                const parent = document.getElementById(`comment-${commentId}`);
                parent.appendChild(respond);
                
                // Adicionar event listener para o botão cancelar
                cancel.addEventListener('click', function() {
                    respond.parentNode.removeChild(respond);
                    this.parentNode.removeChild(this);
                });
                
                // Focar no campo de comentário
                respond.querySelector('#comment').focus();
            });
        });

        // Função para validar o formulário
        function validateForm() {
            const comment = commentField.value.trim();
            const author = commentForm.querySelector('#author')?.value.trim();
            const email = commentForm.querySelector('#email')?.value.trim();
            const url = commentForm.querySelector('#url')?.value.trim();
            
            if (comment.length < config.minLength) {
                showError('O comentário deve ter pelo menos ' + config.minLength + ' caracteres.');
                return false;
            }
            
            if (comment.length > config.maxLength) {
                showError('O comentário não pode ter mais de ' + config.maxLength + ' caracteres.');
                return false;
            }
            
            if (author && author.length < 2) {
                showError('O nome deve ter pelo menos 2 caracteres.');
                return false;
            }
            
            if (email && !isValidEmail(email)) {
                showError('Por favor, insira um email válido.');
                return false;
            }
            
            if (url && !isValidUrl(url)) {
                showError('Por favor, insira uma URL válida.');
                return false;
            }
            
            return true;
        }

        // Função para validar email
        function isValidEmail(email) {
            const re = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
            return re.test(email);
        }

        // Função para validar URL
        function isValidUrl(url) {
            try {
                new URL(url);
                return true;
            } catch {
                return false;
            }
        }

        // Função para mostrar erro
        function showError(message) {
            const errorDiv = document.createElement('div');
            errorDiv.className = 'comment-error';
            errorDiv.textContent = message;
            
            const existingError = commentForm.querySelector('.comment-error');
            if (existingError) {
                existingError.remove();
            }
            
            commentForm.insertBefore(errorDiv, commentForm.firstChild);
            
            setTimeout(() => {
                errorDiv.remove();
            }, 5000);
        }

        // Função para mostrar sucesso
        function showSuccess(message) {
            const successDiv = document.createElement('div');
            successDiv.className = 'comment-success';
            successDiv.textContent = message;
            
            const existingSuccess = commentForm.querySelector('.comment-success');
            if (existingSuccess) {
                existingSuccess.remove();
            }
            
            commentForm.insertBefore(successDiv, commentForm.firstChild);
            
            setTimeout(() => {
                successDiv.remove();
            }, 5000);
        }

        // Função para enviar o comentário
        async function submitComment(e) {
            e.preventDefault();
            
            if (!validateForm()) {
                return;
            }
            
            const submitButton = commentForm.querySelector('#submit');
            const originalText = submitButton.value;
            
            try {
                submitButton.disabled = true;
                submitButton.value = config.loadingText;
                
                const formData = new FormData(commentForm);
                const response = await fetch(commentForm.action, {
                    method: 'POST',
                    body: formData
                });
                
                if (!response.ok) {
                    throw new Error(config.errorText);
                }
                
                const data = await response.json();
                
                if (data.success) {
                    showSuccess(config.successText);
                    commentForm.reset();
                    
                    if (data.redirect) {
                        window.location.href = data.redirect;
                    }
                } else {
                    throw new Error(data.message || config.errorText);
                }
            } catch (error) {
                showError(error.message);
            } finally {
                submitButton.disabled = false;
                submitButton.value = originalText;
            }
        }

        // Adicionar event listener para o envio do formulário
        commentForm.addEventListener('submit', submitComment);
    }

    // Função para inicializar
    function init() {
        initComments();
        
        // Adicionar estilos dinâmicos
        const style = document.createElement('style');
        style.textContent = `
            .comment-counter {
                font-size: 0.875rem;
                color: #666;
                margin-top: 0.25rem;
            }
            
            .comment-counter.error {
                color: #dc3545;
            }
            
            .comment-error,
            .comment-success {
                padding: 1rem;
                margin-bottom: 1rem;
                border-radius: 4px;
            }
            
            .comment-error {
                background-color: #f8d7da;
                border: 1px solid #f5c6cb;
                color: #721c24;
            }
            
            .comment-success {
                background-color: #d4edda;
                border: 1px solid #c3e6cb;
                color: #155724;
            }
            
            .cancel-comment-reply {
                display: block;
                margin-bottom: 1rem;
                padding: 0.5rem 1rem;
                background: #f8f9fa;
                border: 1px solid #ddd;
                border-radius: 4px;
                cursor: pointer;
                font-size: 0.875rem;
            }
            
            .cancel-comment-reply:hover {
                background: #e9ecef;
            }
            
            #commentform {
                margin-top: 2rem;
            }
            
            #commentform label {
                display: block;
                margin-bottom: 0.5rem;
                font-weight: 500;
            }
            
            #commentform input[type="text"],
            #commentform input[type="email"],
            #commentform input[type="url"],
            #commentform textarea {
                width: 100%;
                padding: 0.5rem;
                border: 1px solid #ddd;
                border-radius: 4px;
                margin-bottom: 1rem;
            }
            
            #commentform input[type="text"]:focus,
            #commentform input[type="email"]:focus,
            #commentform input[type="url"]:focus,
            #commentform textarea:focus {
                outline: none;
                border-color: #007bff;
                box-shadow: 0 0 0 0.2rem rgba(0, 123, 255, 0.25);
            }
            
            #submit {
                padding: 0.5rem 1rem;
                background: #007bff;
                color: #fff;
                border: none;
                border-radius: 4px;
                cursor: pointer;
                font-weight: 500;
            }
            
            #submit:hover {
                background: #0056b3;
            }
            
            #submit:disabled {
                opacity: 0.65;
                cursor: not-allowed;
            }
            
            .comment-reply-link {
                font-size: 0.875rem;
                color: #666;
                text-decoration: none;
            }
            
            .comment-reply-link:hover {
                color: #007bff;
            }
            
            @media (max-width: 768px) {
                #commentform {
                    padding: 1rem;
                }
            }
        `;
        
        document.head.appendChild(style);
    }

    // Inicializar quando o DOM estiver pronto
    document.addEventListener('DOMContentLoaded', init);

})(); 