<?php
/**
 * Single post partial template.
 *
 * @package buildico
 */
$featured_img_post = wt_get_option( 'featured_img_post' );
?>
<article <?php post_class( array( 'blog-single' ) ); ?> id="post-<?php the_ID(); ?>">
	
	<?php 
	if( ! empty( $featured_img_post ) ) : 
	if( $featured_img_post === 'in_body' || $featured_img_post === 'in_both' ) : ?>
		<div class="featured-img">
			<?php echo get_the_post_thumbnail( $post->ID, '850x420-large' ); ?>
		</div>

		<header class="entry-header">

			<?php the_title( '<h1 class="entry-title">', '</h1>' ); ?>

			<div class="entry-meta">

				<?php buildico_posted_on(); ?>

			</div><!-- .entry-meta -->

		</header><!-- .entry-header -->

	<?php endif;

		else : ?>

		<div class="featured-img">
			<?php echo get_the_post_thumbnail( $post->ID, '850x420-large' ); ?>
		</div>

		<header class="entry-header">

			<?php the_title( '<h1 class="entry-title">', '</h1>' ); ?>

			<div class="entry-meta">

				<?php buildico_posted_on(); ?>

			</div><!-- .entry-meta -->

		</header><!-- .entry-header -->
		
	<?php endif; ?>

	<?php if( $featured_img_post === 'in_header' ) : ?>

		<header class="entry-header in-header-style">

			<div class="entry-meta">

				<?php buildico_posted_on(); ?>

			</div><!-- .entry-meta -->

		</header><!-- .entry-header -->

	<?php endif; ?>
	

	<div class="entry-content">

		<?php the_content(); ?>

		<?php
		wp_link_pages( array(
			'before' => '<div class="page-links">' . __( 'Pages:', 'buildico' ),
			'after'  => '</div>',
		) );
		?>

	</div><!-- .entry-content -->

	<footer class="entry-footer d-flex">
		<div class="footer-tags">
		<?php
			buildico_entry_footer();
		?>
		</div>
		<?php 
			if( function_exists( 'buildico_social_share' ) ){
				buildico_social_share();
			}
		?>
	</footer><!-- .entry-footer -->

</article><!-- #post-## -->
