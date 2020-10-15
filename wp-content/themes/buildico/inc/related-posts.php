<?php
/**
 * Related Posts
 *
 *
 * @package buildico
 */

function buildico_related_posts($args = array()) {

    global $post;
    $rel_posts_title = wt_get_option('single_rel_title');

    // default args
    $args = wp_parse_args($args, array(
        'post_id' => !empty($post) ? $post->ID : '',
        'taxonomy' => !empty( wt_get_option('single_rel_post_select') ) ? wt_get_option('single_rel_post_select') : 'category',
        'limit' => !empty( wt_get_option('single_rel_count') ) ? wt_get_option('single_rel_count') : 4,
        'post_type' => !empty($post) ? $post->post_type : 'post',
        'orderby' => !empty( wt_get_option('single_rel_post_orderby') ) ? wt_get_option('single_rel_post_orderby') : 'date',
        'order' => 'DESC'
    ));

    // check taxonomy
    if (!taxonomy_exists($args['taxonomy'])) {
        return;
    }

    // post taxonomies
    $taxonomies = wp_get_post_terms($args['post_id'], $args['taxonomy'], array('fields' => 'ids'));

    if (empty($taxonomies)) {
        return;
    }

    // query
    $related_posts = get_posts(array(
        'post__not_in' => (array) $args['post_id'],
        'post_type' => $args['post_type'],
        'tax_query' => array(
            array(
                'taxonomy' => $args['taxonomy'],
                'field' => 'term_id',
                'terms' => $taxonomies
            ),
        ),
        'posts_per_page' => $args['limit'],
        'orderby' => $args['orderby'],
        'order' => $args['order']
    ));

    if (!empty($related_posts)) { ?>

    <div class="related-posts">
    	<?php if( ! empty( $rel_posts_title ) ) : ?>
        <h3 class="widget-title"><?php echo esc_html( $rel_posts_title ); ?></h3>
        <?php else: ?>
            <h3 class="widget-title"><?php echo esc_html__( 'Related Posts', 'buildico' ); ?></h3>
		<?php endif; ?>
        <ul class="related-posts-list">
            <?php
            foreach ( $related_posts as $post ) {
                setup_postdata( $post );
            ?>
            <li>
                <a class="title" href="<?php the_permalink(); ?>" title="<?php the_title_attribute(); ?>">
                    <?php if ( has_post_thumbnail() ) { ?>
                    <div class="thumb">
                        <?php echo get_the_post_thumbnail( null, '450x260-blog', array( 'alt' => the_title_attribute( array( 'echo' => false ) ) ) ); ?>
                    </div>
                    <?php } ?>
                    <h4><?php the_title(); ?></h4>
                </a>
            </li>
            <?php } ?>
        </ul>
        <div class="clearfix"></div>
    </div>
	<?php
	}
    wp_reset_postdata();
}