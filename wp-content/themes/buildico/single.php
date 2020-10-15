<?php
/**
 * The template for displaying all single posts.
 *
 * @package buildico
 */

get_header();

if( ! empty( wt_get_option( 'select_theme_layout' ) ) ){
    $container = wt_get_option( 'select_theme_layout' );
}else{
    $container = 'container';
}
// Page Header Alignment
$ph_align = wt_get_option('page_header_align');
if( ! empty( $ph_align ) ){
	if( 'left' === $ph_align ){
		$page_align = 'col-md-7 text-left';
	}elseif ( 'right' === $ph_align ) {
		$page_align = 'col-md-7 offset-md-5 text-right';
	}else{
		$page_align = 'col-md-12 text-center';
	}
}else{
	$page_align = 'col-md-7';
}

$single_post_nav = wt_get_option( 'single_post_nav' );
$single_author_bio = wt_get_option( 'single_author_bio' );
$single_comment = wt_get_option( 'single_comment' );
$featured_img_post = wt_get_option( 'featured_img_post' );
$single_rel_post_select = wt_get_option( 'single_rel_post_select' );
$single_rel_post = wt_get_option( 'single_rel_post' );
$single_rel_count = wt_get_option( 'single_rel_count' );
$single_rel_post_orderby = wt_get_option( 'single_rel_post_orderby' );
?>
<?php

global $post;
$currentID = get_the_ID();
$featured_img = wp_get_attachment_image_src( get_post_thumbnail_id( $currentID ), 'large' );
$header_img = 'style="background-image: url('. $featured_img[0] .');"';
if( ! empty( $featured_img ) ){
	$header_img = $featured_img[0];
}else{
	$header_img = get_template_directory_uri(). '/assets/img/default-background.jpg';
}
?>
<?php if( $featured_img_post === 'in_header' || $featured_img_post === 'in_both' ) : ?>
<div class="page-header sine-post-header" style="background-image: url(<?php echo esc_url($header_img); ?>);">
	<div class="<?php echo esc_attr( $container ); ?>">
		<div class="<?php echo esc_attr( $page_align ); ?>">
			<?php
				the_title( '<h1 class="page-title">', '</h1>' );

				if( wt_get_option('breadcrumbs_enable') == true ){
					if(function_exists( 'bcn_display' ) ) {
						echo '<div class="breadcrumbs" typeof="BreadcrumbList" vocab="http://schema.org/">';
					    bcn_display();
					    echo '</div>';
					}
				}
			?>
		</div>
	</div>
</div><!-- /.page-header -->
<?php endif; ?>

<div class="wrapper" id="single-wrapper">

	<div class="<?php echo esc_attr( $container ); ?>" id="content" tabindex="-1">

		<div class="row">

			<!-- Do the left sidebar check -->
			<?php get_template_part( 'global-templates/left-sidebar-check' ); ?>

			<main class="site-main" id="main">

				<?php while ( have_posts() ) : the_post(); ?>

					<?php get_template_part( 'loop-templates/content', 'single' ); ?>

					<?php
						if ( $single_post_nav === true ) {
							buildico_post_nav();
						}
					?>

					<?php

					// Related Posts
					if( $single_rel_post === true ){
						buildico_related_posts( array(
						   'taxonomy' => esc_html( $single_rel_post_select ),
						   'limit' => esc_html( $single_rel_count ),
						   'orderby' => esc_html( $single_rel_post_orderby )
						));
					}

					//Author Bio
					if ( $single_author_bio === true ) {
						buildico_author_profile();
					}

					// If comments are open or we have at least one comment, load up the comment template.
					if( ! empty( wt_get_option( 'single_comment' ) ) ){
						if ( $single_comment === true ) {
							if ( comments_open() || get_comments_number() ) :
								comments_template();
							endif;
						}
					}else{
						if ( comments_open() || get_comments_number() ) :
							comments_template();
						endif;
					}
					?>

				<?php endwhile; // end of the loop. ?>

			</main><!-- #main -->

		</div><!-- #primary -->

		<!-- Do the right sidebar check -->
		<?php get_template_part( 'global-templates/right-sidebar-check' ); ?>

	</div><!-- .row -->

</div><!-- Container end -->

</div><!-- Wrapper end -->

<?php get_footer(); ?>
