<?php /* Template Name: Work Template */ ?>


<?php
$numPage = (int)droow_acf_option('blog_pages_show_at_most', get_option('posts_per_page'));
$CurrentPage = (get_query_var('paged')) ? get_query_var('paged') : 1;
$style_work = droow_acf_option('style_work', 'work');

$myposts = new WP_Query(array('paged' => $CurrentPage, 'posts_per_page' => $numPage, 'post_type' => droow_project_slug()));

$count_posts = wp_count_posts(droow_project_slug())->publish;

$showLoadMore = ($count_posts > $numPage && $numPage !== -1);
$is_ajax = droow_acf_option('ajax_load_more');

$classes = '';

$use_title_cover = droow_acf_option('use_title_cover', 'show') === 'show';
$titlle_cover_work = droow_acf_option('titlle_cover_work', 'Project');


if ($style_work === 'work-1') {
    $classes = 'gallery-2-col';
}

$is_g = '';
if (droow_acf_option('use_as_gallery') && !$showLoadMore)
    $is_g = 'dsn-as-popup-gallery';


get_header();
if ($myposts->have_posts()) :
    echo '<div class="root-work ">';
    if ($use_title_cover) {
        printf('<div class="box-title" data-dsn-title="cover"><h2 class="title-cover" data-dsn-grid="move-section" data-dsn-move="-70">%s</h2></div>', esc_html($titlle_cover_work));
    }

    droow_filtering();
    echo '<div class="projects-list gallery ' . esc_attr($classes . ' ' . $is_g) . '" data-append="ajax">';
    if (!$showLoadMore)
        set_query_var('use_as_gallery', droow_acf_option('use_as_gallery'));

    while ($myposts->have_posts()) :
        $myposts->the_post();
        get_template_part('template-parts/content/content', 'work');
    endwhile;

    echo '</div>';
    if ($myposts->have_posts() && $showLoadMore) : ?>
        <div class="image-zoom button-loadmore loadmore-work" data-type="projects" data-page="2" data-dsn="parallax"
             data-id="<?php echo esc_attr($numPage) ?>"
             data-layout="work">
            <span class="progress-text progress-load-more"><?php esc_html_e('Load More', 'droow') ?></span>
            <span class="progress-text progress-no-more"><?php esc_html_e('NO MORE', 'droow') ?></span>
            <div class="dsn-load-progress-ajax"></div>
        </div>
    <?php endif;
    echo '</div>';
else:
    get_template_part('template-parts/content/content', 'none');
endif;
wp_reset_postdata();

get_footer();