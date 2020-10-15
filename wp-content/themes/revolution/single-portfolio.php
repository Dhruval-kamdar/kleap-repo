<?php get_header(); ?>
<?php
if ( have_posts() ) :
	while ( have_posts() ) :
		the_post();
		?>
			<?php
			if ( post_password_required() ) {
				get_template_part( 'inc/templates/password-protected' ); } else {
				?>
		<div class="post-content">
				<?php the_content(); ?>
			</div>
				<?php
				if ( comments_open() || get_comments_number() ) {
					comments_template( '', true ); }
				?>
				<?php do_action( 'thb_related_portfolio' ); ?>
				<?php do_action( 'thb_portfolio_nav' ); ?>
		<?php } ?>
		<?php
	endwhile;
endif;
?>
<?php
get_footer();
