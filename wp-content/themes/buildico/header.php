<?php
/**
 * The header for our theme.
 *
 * Displays all of the <head> section and everything up till <div id="content">
 *
 * @package buildico
 */

$container = wt_get_option( 'select_theme_layout' );
$select_header = wt_get_option( 'select_header' );
?>
<!DOCTYPE html>
<html <?php language_attributes(); ?>>
<head>
	<meta charset="<?php bloginfo( 'charset' ); ?>">
	<meta http-equiv="X-UA-Compatible" content="IE=edge">
	<meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
	<meta name="mobile-web-app-capable" content="yes">
	<meta name="apple-mobile-web-app-capable" content="yes">
	<meta name="apple-mobile-web-app-title" content="<?php bloginfo( 'name' ); ?> - <?php bloginfo( 'description' ); ?>">
	<link rel="profile" href="http://gmpg.org/xfn/11">
	<link rel="pingback" href="<?php bloginfo( 'pingback_url' ); ?>">
	<?php
	if ( ! function_exists( 'has_site_icon' ) || ! has_site_icon() ) {
	   echo '<link rel="shortcut icon" type="image/png" href="'. get_template_directory_uri() .'/assets/img/favicon.png"/>';
	} ?>
	<?php wp_head(); ?>
</head>

<body <?php body_class(); ?>>
<?php wp_body_open(); ?>
<div class="hfeed site" id="page">

	<?php
		if( get_post_meta( get_the_ID(), "custom_page_options", true ) ){
			$buildico_page_settings = get_post_meta( get_the_ID(), "custom_page_options", true );
		}else{
			$buildico_page_settings = array();
		}

		if( wt_get_option('preloader_control') === 'show_all' ){
			buildico_preloader();
		}elseif ( wt_get_option('preloader_control') === 'only_home' && is_home() ) {
			buildico_preloader();
		}
	?>

	<!-- ******************* The Navbar Area ******************* -->
	<?php
	if( ! empty( $buildico_page_settings['header_select'] ) ){
		if( $buildico_page_settings['header_select'] == 'header-1' ){
			get_template_part( 'global-templates/header-1' );
		}elseif( $buildico_page_settings['header_select'] == 'header-2' ){
			get_template_part( 'global-templates/header-2' );
		}elseif ( $buildico_page_settings['header_select'] == '' || $buildico_page_settings['header_select'] == 'default') {
			if(!empty( wt_get_option('select_header') ) ){
				get_template_part( 'global-templates/'. esc_html( wt_get_option('select_header') ) .'' );
			}else{
				get_template_part( 'global-templates/header-1' );
			}
		}
	}else{
		if(!empty( wt_get_option('select_header') ) ){
			get_template_part( 'global-templates/'. esc_html( wt_get_option('select_header') ) .'' );
		}else{
			get_template_part( 'global-templates/header-1' );
		}
	}
	?>

<?php if( wt_get_option('hideheader_search_icon') === true ) : ?>
	<div id="wt-search">
		<button type="button" class="close"></button>
		<form id="fullscreen-search" method="get" action="<?php echo esc_url( home_url( '/' ) ); ?>" role="search">
			<input type="text" value="<?php echo get_search_query(); ?>" name="s" id="s" placeholder="<?php _e('type keywords here', 'buildico'); ?>" />
			<input type="submit" class="search-btn" value="<?php _e('Search', 'buildico'); ?>">
		</form>
	</div>
<?php endif; ?>
