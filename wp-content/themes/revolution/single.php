<?php get_header(); ?>
<?php
if ( have_posts() ) :
	while ( have_posts() ) :
		the_post();
		?>
			<?php
			$article_style = ot_get_option( 'article_style', 'style1' );
			get_template_part( 'inc/templates/article/' . $article_style );
			?>
			<?php if ( comments_open() || get_comments_number() ) : ?>
<!-- Start #comments -->
				<?php comments_template( '', true ); ?>
<!-- End #comments -->
<?php endif; ?>
		<?php
	endwhile;
endif;
?>
<?php
get_footer();
