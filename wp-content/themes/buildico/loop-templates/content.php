<?php
/**
 * Post rendering content according to caller of get_template_part.
 *
 * @package buildico
 */
$archive_hide_excerpt = wt_get_option( 'archive_excerpt' );

if( ! empty( wt_get_option('sidebar_position') ) ){
	if( wt_get_option('sidebar_position') === 'none' ){
		$class = 'col-lg-4 col-sm-6';
	}else{
		if( wt_get_option( 'select_theme_layout' ) == 'container-fluid'){
			$class = 'col-lg-4 col-sm-6';
		}else{
			$class = 'col-sm-6';
		}
	}
}else{
	$class = 'col-sm-6';
}
?>

<article <?php post_class( array($class, 'blog-box') ); ?> id="post-<?php the_ID(); ?>">
    
    <div class="article-inner">

        <?php echo get_the_post_thumbnail( $post->ID, '380x220-blog' ); ?>
        <div class="article-content">
            <div class="entry-header">

                <?php if ( 'post' == get_post_type() ) : ?>

                    <div class="entry-meta">
                        <?php buildico_posted_on(); ?>
                    </div><!-- .entry-meta -->

                <?php endif; ?>
                
                <?php the_title( sprintf( '<h2 class="entry-title"><a href="%s" rel="bookmark">', esc_url( get_permalink() ) ),
                '</a></h2>' ); ?>

            </div><!-- .entry-header -->


            <div class="entry-content">

                <?php
                if( is_archive() ){
                    if( $archive_hide_excerpt === true){
                        echo excerpt(20);
                    }
                } else{
                    echo excerpt(20);
                }
                ?>

                <?php
                wp_link_pages( array(
                    'before' => '<div class="page-links">' . __( 'Pages:', 'buildico' ),
                    'after'  => '</div>',
                ) );
                ?>

            </div><!-- .entry-content -->
        </div>
    </div>

</article><!-- #post-## -->
