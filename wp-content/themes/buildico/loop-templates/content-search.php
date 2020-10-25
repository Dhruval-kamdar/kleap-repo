<?php
/**
 * Search results partial template.
 *
 * @package buildico
 */
$search_page_excerpt = wt_get_option( 'search_page_excerpt' );
$search_page_thumb = wt_get_option( 'search_page_thumb' );
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

		<?php
			if ( $search_page_thumb === true ) {
				echo get_the_post_thumbnail( $post->ID, '380x220-blog' );
			}
		 ?>
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
					if( $search_page_excerpt === true){
						the_excerpt();
					}
				?>

            </div><!-- .entry-content -->
        </div>
    </div>

</article><!-- #post-## -->
