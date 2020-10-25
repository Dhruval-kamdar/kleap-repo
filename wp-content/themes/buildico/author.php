<?php
/**
 * The template for displaying the author pages.
 *
 * Learn more: https://codex.wordpress.org/Author_Templates
 *
 * @package buildico
 */

get_header();

if( ! empty( wt_get_option( 'select_theme_layout' ) ) ){
    $container = wt_get_option( 'select_theme_layout' );
}else{
    $container = 'container';
}
$author_socail_acc = wt_get_option( 'author_socail_acc' );
$buildico_header_img = wt_get_option( 'woo_archive_header_img' );
if(isset($buildico_header_img)){
	$buildico_img_src = wp_get_attachment_image_src( $buildico_header_img, 'full');
	$buildico_header_css = 'background-image: url('. $buildico_img_src[0] .')';
}else{
	$buildico_header_css = '';
}
?>

<div class="page-header author-page" style="<?php echo esc_attr($buildico_header_css); ?> ">
	<div class="display-table">
		<div class="table-cell">
			<div class="<?php echo esc_attr( $container ); ?>">
				<div class="col-sm-6 offset-sm-3">
					<div class="author-info">
						<?php
						$curauth = ( isset( $_GET['author_name'] ) ) ? get_user_by( 'slug',
							$author_name ) : get_userdata( intval( $author ) );
						?>
						<?php if ( ! empty( $curauth->ID ) ) : ?>
							<?php echo get_avatar( $curauth->ID ); ?>
						<?php endif; ?>

						<ul>
							<?php if ( ! empty( $curauth->first_name || $curauth->last_name ) ) : ?>
								<li><strong><?php echo esc_html__( 'Full Name:', 'buildico' ); ?></strong> <?php echo esc_html( $curauth->first_name ." ". $curauth->last_name ); ?></li>
							<?php endif; ?>

							<li><strong><?php echo esc_html__( 'Nickname:', 'buildico' ); ?></strong> <?php echo esc_html( $curauth->nickname ); ?></li>

							<?php if ( ! empty( $curauth->user_url ) ) : ?>
								<li><strong><?php echo esc_html__( 'Website:', 'buildico' ); ?></strong> <a href="<?php echo esc_url( $curauth->user_url ); ?>"><?php echo esc_html( $curauth->user_url ); ?></a></li>
							<?php endif; ?>

							<?php if ( ! empty( $curauth->user_description ) ) : ?>
								<li><strong><?php echo esc_html__( 'Author Bio:', 'buildico' ); ?></strong> <?php echo esc_html( $curauth->user_description ); ?></li>
							<?php endif; ?>
						</ul>
						<?php if( $author_socail_acc === true ) : ?>
						<ul class="author-socials">
							<?php if ( ! empty( $curauth->facebook_profile ) ) : ?>
								<li><a href="<?php echo esc_url( $curauth->facebook_profile ); ?>"><i class="fa fa-facebook"></i></a></li>
							<?php endif; ?>
							<?php if ( ! empty( $curauth->twitter_profile ) ) : ?>
								<li><a href="<?php echo esc_url( $curauth->twitter_profile ); ?>"><i class="fa fa-twitter"></i></a></li>
							<?php endif; ?>
							<?php if ( ! empty( $curauth->google_profile ) ) : ?>
								<li><a href="<?php echo esc_url( $curauth->google_profile ); ?>"><i class="fa fa-google-plus"></i></a></li>
							<?php endif; ?>
							<?php if ( ! empty( $curauth->pinterest_profile ) ) : ?>
								<li><a href="<?php echo esc_url( $curauth->pinterest_profile ); ?>"><i class="fa fa-pinterest"></i></a></li>
							<?php endif; ?>
							<?php if ( ! empty( $curauth->linkedin_profile ) ) : ?>
								<li><a href="<?php echo esc_url( $curauth->linkedin_profile ); ?>"><i class="fa fa-linkedin"></i></a></li>
							<?php endif; ?>
							<?php if ( ! empty( $curauth->tumblr_profile ) ) : ?>
								<li><a href="<?php echo esc_url( $curauth->tumblr_profile ); ?>"><i class="fa fa-tumblr"></i></a></li>
							<?php endif; ?>
						</ul>
						<?php endif; ?>
					</div>
				</div>
			</div>
		</div>
	</div>
</div><!-- /.page-header -->

<div class="wrapper" id="author-wrapper">

	<div class="<?php echo esc_attr( $container ); ?>" id="content" tabindex="-1">

		<div class="row">

			<!-- Do the left sidebar check -->
			<?php get_template_part( 'global-templates/left-sidebar-check' ); ?>

			<main class="site-main" id="main">
				<h2 class="author-posts-title"><?php esc_html__( 'All Posts by', 'buildico' ); ?> <?php echo esc_html( $curauth->nickname ); ?> :</h2>

				<div class="row blog-grid">

				<?php if ( have_posts() ) : ?>

					<?php /* Start the Loop */ ?>

					<?php while ( have_posts() ) : the_post(); ?>

						<?php

						/*
						 * Include the Post-Format-specific template for the content.
						 * If you want to override this in a child theme, then include a file
						 * called content-___.php (where ___ is the Post Format name) and that will be used instead.
						 */
						get_template_part( 'loop-templates/content', get_post_format() );
						?>

					<?php endwhile; ?>

				<?php else : ?>

					<?php get_template_part( 'loop-templates/content', 'none' ); ?>

				<?php endif; ?>
				</div>

			</main><!-- #main -->

			<!-- The pagination component -->
			<?php buildico_pagination(); ?>

		</div><!-- #primary -->

		<!-- Do the right sidebar check -->
		<?php get_template_part( 'global-templates/right-sidebar-check' ); ?>

	</div> <!-- .row -->

</div><!-- Container end -->

</div><!-- Wrapper end -->

<?php get_footer(); ?>
