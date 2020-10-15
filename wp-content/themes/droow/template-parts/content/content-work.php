<?php
$use_as_gallery = get_query_var('use_as_gallery');
$link = get_the_permalink();
$effect_ajax = 'effect-ajax';
if ($use_as_gallery) {
    $link = get_the_post_thumbnail_url();
    $effect_ajax = '';
}


?>


<div id="post-<?php the_ID(); ?>" <?php post_class(
    array(
        'item',
        droow_project_category_slug()
    )) ?> >

    <a href="<?php echo esc_url($link) ?>" class="<?php echo esc_attr($effect_ajax) ?>" data-dsn-ajax="work"
       rel="bookmark"
       title="<?php echo esc_attr(droow_custom_title()) ?>"
       data-dsn-grid="move-up">

        <?php
        if ($use_as_gallery) {
            the_post_thumbnail('post-thumbnail', array('class' => 'has-top-bottom'));
        } else {
            droow_get_img(array(
                'before_v' => get_the_post_thumbnail(null, 'post-thumbnail', array('class' => 'hidden')) . '<div data-dsn="video" data-overlay="' . esc_attr(droow_overlay()) . '" style="height: 80vh">',
                'after_v' => '</div>',
                'type' => 'img',
                'class' => 'has-top-bottom',
                'attr' => array(
                    'class' => 'has-top-bottom'
                )
            ));
        }


        ?>

        <div class="item-border"></div>
        <div class="item-info">
            <h5 class="cat">
                <?php echo droow_post_category(); ?>
            </h5>
            <h4>
                <?php the_title() ?>
            </h4>
            <span><span><?php esc_html_e('View Project', 'droow') ?></span></span>
        </div>
    </a>
</div>