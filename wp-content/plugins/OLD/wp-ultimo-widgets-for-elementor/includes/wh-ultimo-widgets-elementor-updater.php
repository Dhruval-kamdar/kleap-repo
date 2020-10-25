<?php

// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
	die;
}

/**
 * The class responsible for checking for updates.
 */
if( !class_exists( 'EDD_SL_Plugin_Updater' ) ) {
	// load our custom updater
	require_once WH_ULTIMO_WIDGETS_DIR . 'includes/updater/EDD_SL_Plugin_Updater.php';
}	

$license_key = trim( get_site_option( 'wh_ultimo_widgets_license_key' ) );

$edd_updater = new EDD_SL_Plugin_Updater( WH_ULTIMO_WIDGETS_STORE_URL, WH_ULTIMO_WIDGETS_FILE, array( 
		'version' 	=> WH_ULTIMO_WIDGETS_VERSION,
		'license' 	=> $license_key, 
		'item_id'   => WH_ULTIMO_WIDGETS_ITEM_ID, 
		'author' 	=> 'J Hanlon - IDB Media',
		'url'       => home_url()
	)
);

/**
 * Adds Plugins Admin Menu
 *
 * @return void
 */
function wh_ultimo_widgets_license_menu() {
	$hook_suffix = add_menu_page(  __( WH_ULTIMO_WIDGETS_ITEM_NAME, 'wh-ultimo-widgets-elementor' ),  __( WH_ULTIMO_WIDGETS_ITEM_NAME, 'wh-ultimo-widgets-elementor' ), 'manage_network_options', 'wh-ultimo-widgets', 'wh_ultimo_widgets_license_page','dashicons-welcome-widgets-menus' );

	add_action( "load-{$hook_suffix}", 'wh_ultimo_widgets_admin_style' );

}
add_action( 'network_admin_menu', 'wh_ultimo_widgets_license_menu' ); 

/**
 * Register the stylesheets for the admin area.
 *
 * @since    1.0.0
 */
function wh_ultimo_widgets_admin_style() {

	wp_enqueue_style( WH_ULTIMO_WIDGETS_SLUG, WH_ULTIMO_WIDGETS_URL . 'assets/css/wh-ultimo-widgets-elementor-admin.css', array(), WH_ULTIMO_WIDGETS_VERSION, 'all' );
}


/**
 * Register the global widget stylesheets for the admin area.
 *
 * @since    1.0.0
 */
function wh_ultimo_widgets_global_style() {

	//wp_enqueue_style( 'waashero-widget-style', WH_ULTIMO_WIDGETS_URL . 'assets/css/widget-style.css', array(), WH_ULTIMO_WIDGETS_VERSION, 'all' );
	wp_enqueue_script( 'sweetalert2', 'https://cdn.jsdelivr.net/npm/sweetalert2@9', array('jquery'), WH_ULTIMO_WIDGETS_VERSION, false );
}
add_action( 'admin_enqueue_scripts', 'wh_ultimo_widgets_global_style' );

/**
 * Adds License Page
 *
 * @return void
 */
function wh_ultimo_widgets_license_page() {
	
	$license = get_site_option( 'wh_ultimo_widgets_license_key' );
	$status  = get_site_option( 'wh_ultimo_widgets_license_status' );
    ?> 


	<!-- !PAGE CONTENT! -->
	<div class="w3-main idb-container">

	<!-- Header -->
	<!-- Home -->
	<div class="w3-container" id="" style="margin-top:75px">
		<h1 class="w3-xxxlarge w3-text-grey"><b><?php echo WH_ULTIMO_WIDGETS_ITEM_NAME; ?></b></h1>
		<hr class="w3-round hr-wh-hero w3-text-grey">
		
			<form method="post" action="edit.php?action=idb_update_network_options">

				<?php settings_fields('wh-ultimo-widgets-license'); 
				do_settings_sections( 'wh-ultimo-widgets-license' );?>

				<div class="form-table">
					<div class="form-box">
						<div valign="top">
							<div scope="row" valign="top">
								<h2><?php _e('License Key'); ?></h2>
							</div>
							
							<div>
								
								<input id="wh_ultimo_widgets_license_key" name="wh_ultimo_widgets_license_key" type="text" class="regular-text" value="<?php esc_attr_e( $license ); ?>" />
								<p>Save your license first, then activate it using the Activate License button.</p>
							</div>
						
						</div>

						<?php if( false !== $license ) { ?>
							<div valign="top" class="form-box-activate">
								<div scope="row" valign="top">
									<h2><?php _e('Activate License'); ?></h2>
								</div>
								<div>
									<?php if( $status !== false && $status == 'valid' ) { ?>
									
										<?php wp_nonce_field( 'wh_ultimo_widgets_nonce', 'wh_ultimo_widgets_nonce' ); ?>
										<input type="submit" class="button-deactivate" name="edd_license_deactivate" value="<?php _e('Deactivate License'); ?>"/>
									<span class="idb-license-active"><?php _e('License Active'); ?></span>
									<?php } else {
										wp_nonce_field( 'wh_ultimo_widgets_nonce', 'wh_ultimo_widgets_nonce' ); ?>
										<input type="submit" class="button-activate" name="edd_license_activate" value="<?php _e('Activate License'); ?>"/>
									<?php }?>
								</div>
							</div>
						<?php } ?>
					</div>
				</div>
				<div class="wh-license-submit"><?php submit_button('Save License'); ?></div>

				</form>
			</div>
		</div>
	<?php
}
	
/**
 * Registers License Settings
 *
 * @return void
 */
function wh_ultimo_widgets_register_option() {
	register_setting('wh-ultimo-widgets-license', 'wh_ultimo_widgets_license_key', 'idb_nty_sanitize_license' );
}
add_action('admin_init', 'wh_ultimo_widgets_register_option');

/**
 * License Network Settongs
 *
 * @return void
 */
function idb_update_network_options() {

	check_admin_referer('wh-ultimo-widgets-license-options');

	// This is the list of registered options.
	global $new_whitelist_options;
	$options = $new_whitelist_options['wh-ultimo-widgets-license'];

	foreach ($options as $option) {
		if (isset($_POST[$option])) {
			update_site_option($option, $_POST[$option]);
		} else {
			delete_site_option($option);
		}
	}

	// At last we redirect back to our options page.
	wp_redirect(
		add_query_arg(array('page' => 'wh-ultimo-widgets',
		'updated' => 'true'), 
		network_admin_url('admin.php')
	));
	exit;
}
add_action('network_admin_edit_idb_update_network_options','idb_update_network_options');

/**
 * Does Sanitization
 *
 * @param [type] $new
 * @return void
 */
function idb_nty_sanitize_license( $new ) {
	$old = get_site_option( 'wh_ultimo_widgets_license_key' );
	if( $old && $old != $new ) {
		delete_site_option( 'wh_ultimo_widgets_license_status' ); // new license has been entered, so must reactivate
	}
	return $new;
}

/**
 * Initiates license activation process
 *
 * @return void
 */
function wh_ultimo_widgets_activate_license() {
	// listen for our activate button to be clicked
	if( isset( $_POST['edd_license_activate'] ) ) {
		// run a quick security check
		if( ! check_admin_referer( 'wh_ultimo_widgets_nonce', 'wh_ultimo_widgets_nonce' ) )
			return; // get out if we didn't click the Activate button
		// retrieve the license from the database
		$license = trim( get_site_option( 'wh_ultimo_widgets_license_key' ) );
		// data to send in our API request
		$api_params = array(
			'edd_action' => 'activate_license',
			'license'    => $license,
			'item_id'    => WH_ULTIMO_WIDGETS_ITEM_ID, // The ID of the item in EDD
			'url'        => home_url()
		);
		// Call the custom API.
		$response = wp_remote_post( WH_ULTIMO_WIDGETS_STORE_URL, array( 'timeout' => 15, 'sslverify' => false, 'body' => $api_params ) );
		// make sure the response came back okay
		if ( is_wp_error( $response ) || 200 !== wp_remote_retrieve_response_code( $response ) ) {
			$message =  ( is_wp_error( $response ) && ! empty( $response->get_error_message() ) ) ? $response->get_error_message() : __( 'An error occurred, please try again.' );
		} else {
			$license_data = json_decode( wp_remote_retrieve_body( $response ) );
			if ( false === $license_data->success ) {
				switch( $license_data->error ) {
					case 'expired' :
						$message = sprintf(
							__( 'Your license key expired on %s.' ),
							date_i18n( get_site_option( 'date_format' ), strtotime( $license_data->expires, current_time( 'timestamp' ) ) )
						);
						break;
					case 'revoked' :
						$message = __( 'Your license key has been disabled.' );
						break;
					case 'missing' :
						$message = __( 'Invalid license.' );
						break;
					case 'invalid' :
					case 'site_inactive' :
						$message = __( 'Your license is not active for this URL.' );
						break;
					case 'item_name_mismatch' :
						$message = sprintf( __( 'This appears to be an invalid license key for %s.' ), WH_ULTIMO_WIDGETS_ITEM_NAME );
						break;
					case 'no_activations_left':
						$message = __( 'Your license key has reached its activation limit.' );
						break;
					default :
						$message = __( 'An error occurred, please try again.' );
						break;
				}
			}
		}
		// Check if anything passed on a message constituting a failure
		if ( ! empty( $message ) ) {
		
			$base_url = network_admin_url( 'admin.php?page=' . 'wh-ultimo-widgets' );
			$redirect = add_query_arg( array( 'sl_activation' => 'false', 'message' => urlencode( $message ) ), $base_url );
			wp_redirect( $redirect );
			exit();
		}
		// $license_data->license will be either "valid" or "invalid"
		update_site_option( 'wh_ultimo_widgets_license_status', $license_data->license );
		wp_redirect( network_admin_url( 'admin.php?page=' . 'wh-ultimo-widgets' ) );
		exit();
	}
}
add_action( 'admin_init', 'wh_ultimo_widgets_activate_license' );

/**
 * License Activation notices for admin
 *
 * @return void
 */
function wh_ultimo_widgets_admin_notices() {
	
	if ( isset( $_GET['sl_activation'] ) && ! empty( $_GET['message'] ) ) {
		switch( $_GET['sl_activation'] ) {
			case 'false':
				
				$message = urldecode( $_GET['message'] );
				?>
				<div class="notice notice-error is-dismissible">
					<p><?php echo $message; ?></p>
				</div>
				<?php
				break;
			case 'true':
			default:
				echo '<div class="updated">Successfully activated!</div>'; 
		}
	}
}

/**
 * This is a means of catching errors from the activation method above and displaying it to the customer
 */
add_action( 'network_admin_notices', 'wh_ultimo_widgets_admin_notices' );