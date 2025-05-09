<?php
/**
 * Template para exibir o formulário de busca
 */
?>

<form role="search" method="get" class="search-form" action="<?php echo esc_url(home_url('/')); ?>">
    <div class="input-group">
        <input type="search" class="form-control" placeholder="<?php echo esc_attr_x('Buscar &hellip;', 'placeholder', 'ansegtv'); ?>" value="<?php echo get_search_query(); ?>" name="s" />
        <div class="input-group-append">
            <button type="submit" class="btn btn-primary">
                <i class="fa fa-search"></i>
                <span class="screen-reader-text"><?php echo _x('Buscar', 'submit button', 'ansegtv'); ?></span>
            </button>
        </div>
    </div>
</form> 