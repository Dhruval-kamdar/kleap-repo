<?php
class BuildicoActivation {
	private $activation_options;

	public function __construct() {
		add_action( 'admin_menu', [ $this, 'buildico_activation_add_plugin_page' ] );
		add_action( 'admin_init', [ $this, 'buildico_activation_page_init' ] );
		add_action( 'after_setup_theme', [ $this, 'verify_purchase' ] );
		if(get_option('dl_verify') != 'valid'){
			add_action( 'admin_notices', [$this, 'admin_notice_active_theme'] );
		}
	}

	public function buildico_activation_add_plugin_page() {
		add_theme_page(
			__('Buildico Activation', 'buildico'),
			__('Buildico Activation', 'buildico'),
			'manage_options',
			'dl-activation',
			array( $this, 'buildico_activation_create_admin_page' ) // function
		);
	}

	public function buildico_activation_create_admin_page() {
		$this->activation_options = get_option( 'buildico_activation_option_name' ); ?>
		<div class="wrap">
			<h2><b><?php echo 'Buildico'; ?></b> <?php _e('Construction WP Theme', 'buildico'); ?></h2>
			<?php
				$purchase_code = $this->activation_options['purchase_code'];
				$url      = "https://secure.dynamiclayers.net/api/en/bverify.php?pc={$purchase_code}";
				$response = wp_remote_get( $url );
				if( is_wp_error( $response ) ) {
					return false;
				}
				$body = wp_remote_retrieve_body( $response );
				$data = explode(',',$body,8);
			?>
			<?php settings_errors(); ?>
			<form method="post" action="options.php">
				<?php
					settings_fields( 'buildico_activation_option_group' );
					do_settings_sections( 'activation-admin' );
					if(get_option('dl_verify') != 'valid'){
						submit_button();
					}
				?>
			</form>
			<h2><?php _e('License Info', 'buildico'); ?></h2>
			<table class="form-table" role="presentation">
				<tbody>
				<?php if(get_option('dl_verify') === 'valid') : ?>
					<tr>
						<th scope="row" style="width: 140px;"><?php _e('Status', 'buildico'); ?><span style="float:right;">:</span></th>
						<td><span style="background-color: green;color:#fff;padding:5px 10px;"><?php _e('Verified', 'buildico'); ?></span></td>
					</tr>
					<tr>
						<th scope="row" style="width: 140px;"><?php _e('Automatic Update', 'buildico'); ?><span style="float:right;">:</span></th>
						<td><span style="background-color: green;color:#fff;padding:5px 10px;"><?php _e('Enable', 'buildico'); ?></span></td>
					</tr>
					<tr>
						<th scope="row" style="width: 140px;"><?php _e('Product ID', 'buildico'); ?><span style="float:right;">:</span></th>
						<td><span><?php echo esc_html($data[1]); ?></span></td>
					</tr>
					<tr>
						<th scope="row" style="width: 140px;"><?php _e('Product Name', 'buildico'); ?><span style="float:right;">:</span></th>
						<td><span><?php echo esc_html($data[2]); ?></span></td>
					</tr>
					<tr>
						<th scope="row" style="width: 140px;"><?php _e('Buyer', 'buildico'); ?><span style="float:right;">:</span></th>
						<td><span><?php echo esc_html($data[3]); ?></span></td>
					</tr>
					<tr>
						<th scope="row" style="width: 140px;"><?php _e('License', 'buildico'); ?><span style="float:right;">:</span></th>
						<td><span><?php echo esc_html($data[4]); ?></span></td>
					</tr>
					<tr>
						<th scope="row" style="width: 140px;"><?php _e('Purchase Date', 'buildico'); ?><span style="float:right;">:</span></th>
						<td>
							<span><?php 
							$pcdate = str_split($data[5], 10);
							echo esc_html($pcdate[0]); ?></span>
						</td>
					</tr>
					<tr>
						<th scope="row" style="width: 140px;"><?php _e('Support', 'buildico'); ?><span style="float:right;">:</span></th>
						<td>
							<span><?php 
							$supportend = str_split($data[6], 10);
							$expiry_date = $supportend[0];
							$today = date('d-m-Y'); 
							$exp = date('d-m-Y',strtotime($expiry_date));
							$expDate =  date_create($exp);
							$todayDate = date_create($today);
							$diff =  date_diff($todayDate, $expDate);
							if($diff->format("%R%a")>0){
								$support_time_re = $diff->format("%a "). __("Days Remaining", "buildico");
							}else{
								$support_time_re = __('<b style="color: red;">Expired</b> <a href="https://themeforest.net/item/buildico-construction-and-building-wordpress-theme/21812344" target="_blank">Renew Support</a>', 'buildico');
							}
							
							echo wp_kses_post($support_time_re); ?></span>
						</td>
					</tr>
					<?php else : ?>
					<tr>
						<th scope="row" style="width: 140px;"><?php _e('Status', 'buildico'); ?><span style="float:right;">:</span></th>
						<td><span style="background-color: #ff0000;color:#fff;padding:5px 10px;"><?php _e('Not Verified', 'buildico'); ?></span></td>
					</tr>
					<tr>
						<th scope="row" style="width: 140px;"><?php _e('Automatic Update', 'buildico'); ?><span style="float:right;">:</span></th>
						<td><span style="background-color: #ff0000;color:#fff;padding:5px 10px;"><?php _e('Disable', 'buildico'); ?></span></td>
					</tr>
					<?php endif; ?>
				</tbody>
			</table>
			
		</div>
	<?php }

	public function buildico_activation_page_init() {
		register_setting(
			'buildico_activation_option_group', // option_group
			'buildico_activation_option_name', // option_name
			array( $this, 'activation_sanitize' ) // sanitize_callback
		);

		add_settings_section(
			'buildico_activation_setting_section', // id
			__('Activate with purchase code', 'buildico'), // title
			array( $this, 'activation_section_info' ), // callback
			'activation-admin' // page
		);

		add_settings_field(
			'purchase_code', // id
			'Purchase Code', // title
			array( $this, 'purchase_code_callback' ), // callback
			'activation-admin', // page
			'buildico_activation_setting_section' // section
		);
	}

	public function activation_sanitize($input) {
		$sanitary_values = array();
		if ( isset( $input['purchase_code'] ) ) {
			$sanitary_values['purchase_code'] = sanitize_text_field( $input['purchase_code'] );
		}

		return $sanitary_values;
	}

	public function activation_section_info() {
		echo '<p class="description">To enable <b>Buildico</b> Theme auto update enter your ThemeForest purchase code in the below field. <a href="https://help.market.envato.com/hc/en-us/articles/202822600-Where-Is-My-Purchase-Code-" target="_blank">Click here</a> to find theme purchase code.</p>';
	}

	public function purchase_code_callback() {
		if(!empty( $this->activation_options['purchase_code'] )){
			if(get_option('dl_verify') === 'valid'){
				$info = '<p class="description" style="color: green;">'. __('Theme is activated!', 'buildico') .'</p>';
			}else{
				$info = '<p class="description" style="color: #cc0000;">'. __('Invalid purchase code!', 'buildico') .'</p>';
			}
		}else{
			$info = '<p class="description">'. __('Please enter your purchase code to enable automatic theme update.', 'buildico') .'</p>';
		}
		if(get_option('dl_verify') === 'valid'){
			printf(
				'<input class="regular-text" type="password" name="buildico_activation_option_name[purchase_code]" id="purchase_code" value="%s" readonly>'. $info .'','************************************'
			);
		}else{
			printf(
				'<input class="regular-text" type="text" name="buildico_activation_option_name[purchase_code]" id="purchase_code" value="%s" placeholder="'. __('Purchase code goes here...', 'buildico') .'">'. $info .'',
				isset( $this->activation_options['purchase_code'] ) ? esc_attr( $this->activation_options['purchase_code']) : ''
			);
		}
		
	}

	public function verify_purchase() {
		$options = get_option( 'buildico_activation_option_name' );
		$purchase_code = $options['purchase_code'];
		if ( $purchase_code != '' ) {
			$url      = "https://secure.dynamiclayers.net/api/en/bverify.php?pc={$purchase_code}";
			$response = wp_remote_get( $url );
			if( is_wp_error( $response ) ) {
				return false;
			}
			$body = wp_remote_retrieve_body( $response );
			$data = explode(',',$body,8);
			$token = $data[0];
			$base_p = !empty($data[7]) ? $data[7] : '';
			$product_id = !empty($data[1]) ? $data[1] : '';
			if ( 'error' != $body && $product_id === $base_p) {
				update_option( 'dl_verify', 'valid' );
				update_option( 'dl_token', $token );
			} else {
				update_option( 'dl_verify', 'invalid' );
				update_option( 'dl_token', '' );
			}
		} else {
			update_option( 'dl_verify', 'invalid' );
			update_option( 'dl_token', '' );
		}
	
		$dl_token = get_option( 'dl_token' );
		if(get_option('dl_verify') === 'valid'){
			require get_template_directory() . '/inc/update/check-update.php';
			$dl_url = "https://secure.dynamiclayers.net/api/bupdate.php?pc={$purchase_code}&token={$dl_token}";
			$dl_response = wp_remote_get( $dl_url );
			if( is_wp_error( $dl_response ) ) {
				return false;
			}
			$dl_body     = $dl_response['body'];
			$DlThemeUpdateChecker = new ThemeUpdateChecker( 'buildico', $dl_body );
		}
	}
	public function admin_notice_active_theme() {
        $message = __('To unlock buildico updates, please activate the theme using purchase code from', 'buildico').'<a href="'. admin_url( 'themes.php?page=dl-activation') .'">'.__('here', 'buildico').'</a>.';
        printf( '<div class="notice notice-warning is-dismissible"><p>%1$s</p></div>', $message );
    }
}
if ( is_admin() ){
	$activation = new BuildicoActivation();
}