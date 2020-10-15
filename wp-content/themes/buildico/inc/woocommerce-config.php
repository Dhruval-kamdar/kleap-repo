<?php
/**
 * Add WooCommerce support
 *
 * @package buildico
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}
add_action( 'after_setup_theme', 'buildico_woocommerce_support' );
if ( ! function_exists( 'buildico_woocommerce_support' ) ) {
	/**
	 * Declares WooCommerce theme support.
	 */
	function buildico_woocommerce_support() {
		add_theme_support( 'woocommerce' );

		// Add New Woocommerce 3.0.0 Product Gallery support.
		add_theme_support( 'wc-product-gallery-lightbox' );
		add_theme_support( 'wc-product-gallery-zoom' );
		add_theme_support( 'wc-product-gallery-slider' );

	}
}

/**
* First unhook the WooCommerce wrappers
*/
remove_action( 'woocommerce_before_main_content', 'woocommerce_output_content_wrapper', 10 );
remove_action( 'woocommerce_after_main_content', 'woocommerce_output_content_wrapper_end', 10 );

/**
* Then hook in your own functions to display the wrappers your theme requires
*/
add_action( 'woocommerce_before_main_content', 'buildico_woocommerce_wrapper_start', 10 );
add_action( 'woocommerce_after_main_content', 'buildico_woocommerce_wrapper_end', 10 );
if ( ! function_exists( 'buildico_woocommerce_wrapper_start' ) ) {
	function buildico_woocommerce_wrapper_start() {
		if( ! empty( wt_get_option( 'select_theme_layout' ) ) ){
			$container = wt_get_option( 'select_theme_layout' );
		}else{
			$container = 'container';
		}
		if( ! is_product() ){
			remove_action( 'woocommerce_before_main_content','woocommerce_breadcrumb', 20, 0);
		}
		echo '<div class="wrapper woocommerce-wrapper" id="woocommerce-wrapper">';
		echo '<div class="'. $container .'" id="content" tabindex="-1">';
		echo '<div class="row">';
		get_template_part( 'global-templates/woo-left-sidebar-check' );
		echo '<main class="site-main" id="main">';
	}
}
if ( ! function_exists( 'buildico_woocommerce_wrapper_end' ) ) {
	function buildico_woocommerce_wrapper_end() {
		echo '</main><!-- #main --></div><!-- #primary -->';
		get_template_part( 'global-templates/woo-right-sidebar-check' );
		echo '</div><!-- .row -->';
		echo '</div><!-- Container end -->';
		echo '</div><!-- Wrapper end -->';
	}
}

/**
 * Change number or products per row to 3
 */
add_filter('loop_shop_columns', 'buildico_loop_columns', 999);
if (!function_exists('buildico_loop_columns')) {
	function buildico_loop_columns() {
		$columns = wt_get_option('woo_product_column');
		if( ! empty( $columns ) ){
			return esc_html( $columns );
		}else{
			return 3;
		}
	}
}

/**
 * products per page
 */
add_filter( 'loop_shop_per_page', 'buildico_loop_shop_per_page', 20 );
if (!function_exists('buildico_loop_shop_per_page')) {
	function buildico_loop_shop_per_page( $ppp ) {
		$product_pp = wt_get_option('woo_ppp');
		if( ! empty( $product_pp ) ){
			$ppp = esc_html( $product_pp );
		}else{
			$ppp = 9;
		}
	  return $ppp;
	}
}

/**
 * Show cart contents / total Ajax
 */
add_filter( 'woocommerce_add_to_cart_fragments', 'buildico_woocommerce_header_add_to_cart_fragment' );
function buildico_woocommerce_header_add_to_cart_fragment( $fragments ) {
	global $woocommerce;

	ob_start();

	?>
	<a href="<?php echo esc_url(wc_get_cart_url()); ?>" title="<?php _e('View your shopping cart', 'buildico'); ?>" class="buildico-cart">
		<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" id="cart-20" width="100%" height="100%"><circle cx="7" cy="17" r="2"></circle><circle cx="15" cy="17" r="2"></circle><path d="M20 4.4V5l-1.8 6.3c-.1.4-.5.7-1 .7H6.7c-.4 0-.8-.3-1-.7L3.3 3.9c-.2-.6-.7-.9-1.2-.9H.4C.2 3 0 2.8 0 2.6V1.4c0-.2.2-.4.4-.4h2.5c1 0 1.8.6 2.1 1.6l.1.4 2.3 6.8c0 .1.2.2.3.2h8.6c.1 0 .3-.1.3-.2l1.3-4.4c0-.2-.2-.4-.4-.4H9.4c-.2 0-.4-.2-.4-.4V3.4c0-.2.2-.4.4-.4h9.2c.8 0 1.4.6 1.4 1.4z"></path></svg>
		<span class="itemCount"><?php echo esc_html($woocommerce->cart->cart_contents_count); ?></span>
	</a>
	<?php
	$fragments['a.buildico-cart'] = ob_get_clean();
	return $fragments;
}

add_action( 'wp_footer', 'buildico_cart_refresh_update_qty' );
function buildico_cart_refresh_update_qty() {
    if (is_cart()) {
        ?>
        <script type="text/javascript">
            jQuery('div.woocommerce').on('change', '.quantity .qty', function(){
                jQuery("[name='update_cart']").trigger("click");
            });
        </script>
        <?php
    }
}

// Related product
add_filter( 'woocommerce_output_related_products_args', 'buildico_change_number_related_products', 9999 );
function buildico_change_number_related_products( $args ) {
	$rppp = wt_get_option('woo_rp_ppp');
	$rppc = wt_get_option('woo_rp_column');
	if( ! empty( $rppp && $rppc ) ){
		$args['posts_per_page'] = esc_html( $rppp );
		$args['columns'] = esc_html( $rppc );
	}else{
		$args['posts_per_page'] = 4;
		$args['columns'] = 4;
	}
	return $args;
}

/**
 * Remove related products output
 */
if( wt_get_option('woo_rp_control') === true ){
	remove_action( 'woocommerce_after_single_product_summary', 'woocommerce_output_related_products', 20 );
}

/**
 * WooCommerce header cart
 */
if(! function_exists('buildico_woo_header_cart')){
	function buildico_woo_header_cart(){
		?>
		<div class="header-cart-wrap">
			<a href="<?php echo wc_get_cart_url(); ?>" title="<?php _e( 'View your shopping cart', 'buildico' ); ?>" class="buildico-cart">
				<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" id="cart-20" width="100%" height="100%"><circle cx="7" cy="17" r="2"></circle><circle cx="15" cy="17" r="2"></circle><path d="M20 4.4V5l-1.8 6.3c-.1.4-.5.7-1 .7H6.7c-.4 0-.8-.3-1-.7L3.3 3.9c-.2-.6-.7-.9-1.2-.9H.4C.2 3 0 2.8 0 2.6V1.4c0-.2.2-.4.4-.4h2.5c1 0 1.8.6 2.1 1.6l.1.4 2.3 6.8c0 .1.2.2.3.2h8.6c.1 0 .3-.1.3-.2l1.3-4.4c0-.2-.2-.4-.4-.4H9.4c-.2 0-.4-.2-.4-.4V3.4c0-.2.2-.4.4-.4h9.2c.8 0 1.4.6 1.4 1.4z"></path></svg>
				<?php if ( WC()->cart->get_cart_contents_count() != 0 ) {?>
				<span class="itemCount"><?php echo WC()->cart->get_cart_contents_count(); ?></span>
				<?php } ?>
			</a>
		</div>
		<?php
	}
}