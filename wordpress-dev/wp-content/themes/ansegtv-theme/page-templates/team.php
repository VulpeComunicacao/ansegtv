<?php
/**
 * Template Name: Página da Equipe
 * Template Post Type: page
 */

get_header();
?>

<main id="primary" class="site-main team-page">
    <?php
    while (have_posts()) :
        the_post();
        ?>
        <article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>
            <header class="entry-header">
                <?php the_title('<h1 class="entry-title">', '</h1>'); ?>
            </header>

            <div class="entry-content">
                <?php
                the_content();

                wp_link_pages(array(
                    'before' => '<div class="page-links">' . esc_html__('Páginas:', 'ansegtv'),
                    'after'  => '</div>',
                ));
                ?>

                <?php
                $team_members = get_posts(array(
                    'post_type' => 'team',
                    'posts_per_page' => -1,
                    'orderby' => 'menu_order',
                    'order' => 'ASC'
                ));

                if ($team_members) :
                    ?>
                    <div class="team-grid">
                        <?php foreach ($team_members as $member) : ?>
                            <div class="team-member animate-on-scroll">
                                <?php if (has_post_thumbnail($member->ID)) : ?>
                                    <div class="team-photo">
                                        <?php echo get_the_post_thumbnail($member->ID, 'medium'); ?>
                                    </div>
                                <?php endif; ?>

                                <div class="team-info">
                                    <h3 class="team-name">
                                        <?php echo get_the_title($member->ID); ?>
                                    </h3>

                                    <?php if ($position = get_post_meta($member->ID, 'position', true)) : ?>
                                        <div class="team-position">
                                            <?php echo esc_html($position); ?>
                                        </div>
                                    <?php endif; ?>

                                    <div class="team-bio">
                                        <?php echo get_the_excerpt($member->ID); ?>
                                    </div>

                                    <div class="team-social">
                                        <?php
                                        $social_networks = array(
                                            'facebook' => 'fa-facebook',
                                            'twitter' => 'fa-twitter',
                                            'linkedin' => 'fa-linkedin',
                                            'instagram' => 'fa-instagram',
                                            'youtube' => 'fa-youtube'
                                        );

                                        foreach ($social_networks as $network => $icon) :
                                            if ($url = get_post_meta($member->ID, $network, true)) :
                                                ?>
                                                <a href="<?php echo esc_url($url); ?>" 
                                                   class="social-link" 
                                                   target="_blank" 
                                                   rel="noopener noreferrer">
                                                    <i class="fa <?php echo esc_attr($icon); ?>"></i>
                                                </a>
                                                <?php
                                            endif;
                                        endforeach;
                                        ?>
                                    </div>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    </div>
                    <?php
                endif;
                ?>
            </div>
        </article>
        <?php
    endwhile;
    ?>
</main>

<?php
get_footer(); 