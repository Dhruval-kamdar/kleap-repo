<?php
/**
 * Template Name: Blank Page Template
 *
 * Template for displaying a blank page.
 *
 * @package buildico
 */

?>
<!DOCTYPE html>
<html <?php language_attributes(); ?>>
<head>
	<meta charset="<?php bloginfo( 'charset' ); ?>">
	<meta http-equiv="X-UA-Compatible" content="IE=edge, chrome=1">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<meta name="mobile-web-app-capable" content="yes">
	<meta name="apple-mobile-web-app-capable" content="yes">
	<meta name="apple-mobile-web-app-title"
		content="<?php bloginfo( 'name' ); ?> - <?php bloginfo( 'description' ); ?>">
	<link rel="profile" href="http://gmpg.org/xfn/11">
	<link rel="pingback" href="<?php bloginfo( 'pingback_url' ); ?>">
	<?php
	if ( ! function_exists( 'has_site_icon' ) || ! has_site_icon() ) {
	   echo '<link rel="shortcut icon" type="image/png" href="'. get_template_directory_uri() .'/assets/img/favicon.png"/>';
	} ?>
	<?php wp_head(); ?>
</head>
<body>
	<?php
		if( wt_get_option('preloader_control') === 'show_all' ){
			buildico_preloader();
		}elseif ( wt_get_option('preloader_control') === 'only_home' && is_home() ) {
			buildico_preloader();
		}
	?>
<?php while ( have_posts() ) : the_post(); ?>

	<?php get_template_part( 'loop-templates/content', 'blank' ); ?>

<?php endwhile; // end of the loop. ?>
<?php wp_footer(); ?>
</body>
</html>
