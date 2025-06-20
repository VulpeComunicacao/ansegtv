/**
 * Script para gerenciar as respostas aos comentários
 */
(function() {
    'use strict';

    // Função para inicializar o sistema de respostas
    function initCommentReply() {
        const commentLinks = document.querySelectorAll('.comment-reply-link');
        
        commentLinks.forEach(link => {
            link.addEventListener('click', (e) => {
                e.preventDefault();
                
                const commentId = link.getAttribute('data-commentid');
                const respondId = link.getAttribute('data-respondid');
                const postId = link.getAttribute('data-postid');
                
                // Mover o formulário de resposta
                const respond = document.getElementById(respondId);
                const parent = document.getElementById(`comment-${commentId}`);
                
                if (respond && parent) {
                    // Remover qualquer formulário de resposta existente
                    const existingRespond = document.querySelector('.comment-respond');
                    if (existingRespond) {
                        existingRespond.remove();
                    }
                    
                    // Inserir o novo formulário
                    parent.after(respond);
                    
                    // Atualizar o campo parent
                    const parentInput = respond.querySelector('input[name="comment_parent"]');
                    if (parentInput) {
                        parentInput.value = commentId;
                    }
                    
                    // Atualizar o campo post_id
                    const postInput = respond.querySelector('input[name="comment_post_ID"]');
                    if (postInput) {
                        postInput.value = postId;
                    }
                    
                    // Rolar até o formulário
                    respond.scrollIntoView({ behavior: 'smooth', block: 'center' });
                    
                    // Focar no campo de comentário
                    const commentField = respond.querySelector('textarea[name="comment"]');
                    if (commentField) {
                        commentField.focus();
                    }
                }
            });
        });
    }

    // Função para cancelar a resposta
    function initCancelReply() {
        const cancelLinks = document.querySelectorAll('.cancel-comment-reply-link');
        
        cancelLinks.forEach(link => {
            link.addEventListener('click', (e) => {
                e.preventDefault();
                
                const respond = link.closest('.comment-respond');
                if (respond) {
                    respond.remove();
                }
            });
        });
    }

    // Inicializar quando o DOM estiver pronto
    document.addEventListener('DOMContentLoaded', () => {
        initCommentReply();
        initCancelReply();
    });

})(); 