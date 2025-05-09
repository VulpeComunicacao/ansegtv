<?php
/**
 * O template para exibir comentários
 */

/*
 * Se a página atual é protegida por senha e
 * o visitante ainda não inseriu a senha,
 * retornaremos sem carregar os comentários.
 */
if (post_password_required()) {
    return;
}
?>

<div id="comments" class="comments-area">
    <?php if (have_comments()) : ?>
        <h2 class="comments-title">
            <?php
            $ansegtv_comment_count = get_comments_number();
            if ('1' === $ansegtv_comment_count) {
                printf(
                    /* translators: 1: título. */
                    esc_html__('Um comentário em &ldquo;%1$s&rdquo;', 'ansegtv'),
                    '<span>' . wp_kses_post(get_the_title()) . '</span>'
                );
            } else {
                printf(
                    /* translators: 1: contagem de comentários, 2: título. */
                    esc_html(_nx('%1$s comentário em &ldquo;%2$s&rdquo;', '%1$s comentários em &ldquo;%2$s&rdquo;', $ansegtv_comment_count, 'comments title', 'ansegtv')),
                    number_format_i18n($ansegtv_comment_count),
                    '<span>' . wp_kses_post(get_the_title()) . '</span>'
                );
            }
            ?>
        </h2><!-- .comments-title -->

        <?php the_comments_navigation(); ?>

        <ol class="comment-list">
            <?php
            wp_list_comments(array(
                'style'      => 'ol',
                'short_ping' => true,
                'avatar_size' => 60,
            ));
            ?>
        </ol><!-- .comment-list -->

        <?php
        the_comments_navigation();

        // Se os comentários estão fechados e há comentários, deixe uma nota.
        if (!comments_open()) :
            ?>
            <p class="no-comments"><?php esc_html_e('Comentários estão fechados.', 'ansegtv'); ?></p>
            <?php
        endif;

    endif; // Check for have_comments().

    comment_form(array(
        'title_reply'        => esc_html__('Deixe um comentário', 'ansegtv'),
        'title_reply_before' => '<h3 id="reply-title" class="comment-reply-title">',
        'title_reply_after'  => '</h3>',
        'class_submit'       => 'btn btn-primary',
        'comment_field'      => '<p class="comment-form-comment"><label for="comment">' . _x('Comentário', 'noun', 'ansegtv') . '</label><textarea id="comment" name="comment" cols="45" rows="8" maxlength="65525" required></textarea></p>',
    ));
    ?>
</div><!-- #comments --> 