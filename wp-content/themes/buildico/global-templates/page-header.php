<?php
/**
 * Page Header Template
 *
 * This is the template that displays page header on all pages.
 *
 * @package buildico
 */
if(get_post_meta( get_the_ID(), "custom_page_options", true ) ){
	$buildico_page_settings = get_post_meta( get_the_ID(), "custom_page_options", true );
}else{
	$buildico_page_settings = array();
}
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
global $wp_query;
$postid = $wp_query->post->ID;
if ( has_post_thumbnail( $postid ) ) :
    $image_array = wp_get_attachment_image_src( get_post_thumbnail_id( $postid ), 'large' );
    $header_img = $image_array[0];
else :
	$header_img = get_template_directory_uri() . '/assets/img/default-background.jpg';
endif;
?>
<?php if( array_key_exists( 'hide_page_title', $buildico_page_settings ) && $buildico_page_settings['hide_page_title'] == false ) : ?>
<div class="page-header" style="background-image: url(<?php echo esc_url( $header_img ); ?>);">
	<div class="<?php echo esc_attr( $container ); ?>">

		<div class="<?php echo esc_attr( $page_align ); ?>">
			<?php
			if( array_key_exists( 'page_title_color', $buildico_page_settings ) ){
				$title_color = 'style="color: '. esc_html( $buildico_page_settings['page_title_color'] ) .';"';
			}else{
				$title_color = 'style="color: '. esc_html( wt_get_option('ph_heading_color') ) .';"';;
			}
			the_title( '<h1 class="page-title" '. $title_color .'>', '</h1>' );
			?>
			<?php
			if( wt_get_option('ph_desc_hide') === true ){
				if( array_key_exists( 'page_description', $buildico_page_settings ) && ! empty( $buildico_page_settings['page_description'] ) ){
					if( array_key_exists( 'page_desc_color', $buildico_page_settings ) ){
						$desc_color = 'style="color: '. esc_html($buildico_page_settings['page_desc_color']) .';"';
					}else{ 
						$desc_color = 'style="color: '. esc_html( wt_get_option('ph_desc_text_color') ) .';"'; 
					}
					echo '<p '. $desc_color .'>'. esc_html( $buildico_page_settings[ 'page_description' ] ) .'</p>';
				}
			}
				if( array_key_exists( 'enable_breadcrumb', $buildico_page_settings ) && $buildico_page_settings['enable_breadcrumb'] == 'enable' ){
					if(function_exists( 'bcn_display' ) ) {
						echo '<div class="breadcrumbs" typeof="BreadcrumbList" vocab="http://schema.org/">';
					    bcn_display();
					    echo '</div>';
					}
				}elseif( array_key_exists( 'enable_breadcrumb', $buildico_page_settings ) && $buildico_page_settings['enable_breadcrumb'] == 'default' ){
					if( wt_get_option('breadcrumbs_enable') == true ){
						if(function_exists( 'bcn_display' ) ) {
							echo '<div class="breadcrumbs" typeof="BreadcrumbList" vocab="http://schema.org/">';
						    bcn_display();
						    echo '</div>';
						}
					}
				}
			?>
		</div>
	</div>
</div><!-- /.page-header -->
<?php
endif;
wp_reset_query(); ?>