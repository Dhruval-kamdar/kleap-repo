<?php
/**
 * Partial template for content in page.php
 *
 * @package buildico
 */

?>
<article <?php post_class(); ?> id="post-<?php the_ID(); ?>">

	<div class="entry-content">

		<?php the_content(); ?>

		<?php
		wp_link_pages( array(
			'before' => '<div class="page-links">' . __( 'Pages:', 'buildico' ),
			'after'  => '</div>',
		) );
		?>

	</div><!-- .entry-content -->

	<div class="entry-footer">

		<?php edit_post_link( __( 'Edit', 'buildico' ), '<span class="edit-link">', '</span>' ); ?>

	</div><!-- .entry-footer -->

</article><!-- #post-## -->
