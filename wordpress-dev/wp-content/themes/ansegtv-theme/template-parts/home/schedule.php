<?php
/**
 * Template para a seção de programação da página inicial
 */

$schedule_title = get_theme_mod('schedule_title', 'Programação');
$schedule_days = array(
    'Segunda-feira',
    'Terça-feira',
    'Quarta-feira',
    'Quinta-feira',
    'Sexta-feira',
    'Sábado',
    'Domingo'
);
?>

<section class="schedule-section">
    <div class="container">
        <h2 class="section-title animate-on-scroll"><?php echo esc_html($schedule_title); ?></h2>
        
        <div class="schedule-tabs">
            <ul class="nav nav-tabs" role="tablist">
                <?php foreach ($schedule_days as $index => $day) : ?>
                    <li class="nav-item">
                        <a class="nav-link <?php echo $index === 0 ? 'active' : ''; ?>" 
                           data-bs-toggle="tab" 
                           href="#day-<?php echo $index; ?>" 
                           role="tab">
                            <?php echo esc_html($day); ?>
                        </a>
                    </li>
                <?php endforeach; ?>
            </ul>

            <div class="tab-content">
                <?php foreach ($schedule_days as $index => $day) : ?>
                    <div class="tab-pane fade <?php echo $index === 0 ? 'show active' : ''; ?>" 
                         id="day-<?php echo $index; ?>" 
                         role="tabpanel">
                        
                        <?php
                        $schedule_items = get_posts(array(
                            'post_type' => 'schedule',
                            'meta_key' => 'day',
                            'meta_value' => $index,
                            'orderby' => 'meta_value',
                            'order' => 'ASC',
                            'posts_per_page' => -1
                        ));

                        if ($schedule_items) :
                            foreach ($schedule_items as $item) :
                                $time = get_post_meta($item->ID, 'time', true);
                                $duration = get_post_meta($item->ID, 'duration', true);
                                ?>
                                <div class="schedule-item animate-on-scroll">
                                    <div class="schedule-time">
                                        <span class="time"><?php echo esc_html($time); ?></span>
                                        <?php if ($duration) : ?>
                                            <span class="duration"><?php echo esc_html($duration); ?></span>
                                        <?php endif; ?>
                                    </div>
                                    
                                    <div class="schedule-content">
                                        <h3 class="schedule-title">
                                            <a href="<?php echo get_permalink($item->ID); ?>">
                                                <?php echo get_the_title($item->ID); ?>
                                            </a>
                                        </h3>
                                        
                                        <div class="schedule-description">
                                            <?php echo get_the_excerpt($item->ID); ?>
                                        </div>
                                    </div>
                                </div>
                                <?php
                            endforeach;
                        else :
                            ?>
                            <div class="no-schedule">
                                <?php esc_html_e('Nenhuma programação cadastrada para este dia.', 'ansegtv'); ?>
                            </div>
                            <?php
                        endif;
                        ?>
                    </div>
                <?php endforeach; ?>
            </div>
        </div>
    </div>
</section> 