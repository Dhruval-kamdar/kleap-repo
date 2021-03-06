<?php 
namespace BZFLUENT\Admin\License;
use BZFLUENT\Admin\FluentAdminSettings;

define('BZFLUENT_APP_API_URL',  'https://waas-pro.com/index.php');


class BZFLUENT_License {
	

 	public $productId;
 	public $productField;



 	public function init($productID=null) {

 		$this->productId = BZFLUENT_PRODUCT_ID;
 		$this->productField = 'software_license_key_' . $this->productId;
 		
 	}



 	public function bzfluent_license_status() {
		
 		if(is_multisite() ){
			if( is_plugin_active_for_network(BZFLUENT_PLUGIN_FILE) ) {
					$license_key1 = get_site_option($this->productField); 
			} else {
					$license_key1 = get_option($this->productField); 
			}
		} else {
				$license_key1 = get_option($this->productField); 
		}
			
 		//auto updater start
			Blitz_run_updater("https://waas-pro.com/index.php", 'blitz-rebrand-fluentforms-pro',BZFLUENT_PLUGIN_FILE,BZFLUENT_VERSION,$license_key1,$this->productId);
		//auto updater end
		
		return $this->bzfluent_license_check($license_key1,'status-check');
		
 	}


 	public function bzfluent_license_check($license_key,$action) {
		
			$protocol = (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off' || $_SERVER['SERVER_PORT'] == 443) ? "https://" : "http://";
			$protoco =           str_replace($protocol, "", network_site_url());
			$protoco =           str_replace("/", "", $protoco);
			
			// API query parameters
			$args = array(
					'woo_sl_action'     => $action,
					'licence_key'       => $license_key,
					'product_unique_id' => $this->productId,
					'domain'          	=> $protoco
			);

			$request_uri    = BZFLUENT_APP_API_URL . '?' . http_build_query( $args );
			$data           = wp_remote_post( $request_uri );

			$msg = '';
			$error = 1;
			// Check for error in the response
			if(is_wp_error( $data ) || $data['response']['code'] != 200){
					$msg = "Unexpected Error! The query returned with an error.";
			}else{
				// License data.
				$license_data1 = json_decode($data['body']);
				//print 'software:' . SL_PRODUCT_ID;
				$license_data = $license_data1[0];
				if(isset($license_data->status)){
						if( $license_data->status_code == 's205' && $action == 'status-check'){
							$error = 0;
						}else	if($license_data->status == 'success' && $action != 'status-check'){										
								//Uncomment the followng line to see the message that returned from the license server
								$msg = '<b>The following message was returned from the server : </b>'.$license_data->message;
								$error = 0;
						}
						else{
								//Uncomment the followng line to see the message that returned from the license server
								$msg = '<b><br />The following message was returned from the server : </b>'.$license_data->message;
						}

				}else{
					$msg = '<b><br />The following message was returned from the server: </b>'.$license_data->message;
				}
			}
			if($error == '1'){
				$response = array(False,$msg);
			}else{
				$response = array(True,$msg);
			}
			return $response;
	}



 	public function bzfluent_licenseBlock() {
		
			if(is_multisite() ){
				if( is_plugin_active_for_network(BZFLUENT_PLUGIN_FILE) ) {
					$license_key1 = get_site_option($this->productField); 
				} else {
					$license_key1 = get_option($this->productField); 
				}
			} else {
				$license_key1 = get_option($this->productField); 
			}
			
			echo '<div class="wrap">';
			echo '<h3>License Information</h3>';
			/*** License activate button was clicked ***/
			if (isset($_REQUEST['activate_license'])) {
					$license_key = $_REQUEST[$this->productField];
					$license_key = trim($license_key);
					$response = $this->bzfluent_license_check($license_key,'activate');
					echo $response[1];
					if($response[0]){
						if(is_multisite() ){
							if( is_plugin_active_for_network(BZFLUENT_PLUGIN_FILE) ) {
								update_site_option($this->productField, $license_key); 
							} else {
								update_option($this->productField, $license_key); 
							}
						} else {
							update_option($this->productField, $license_key); 
						}
					}
			}
			/*** End of license activation ***/
			
			/*** License activate button was clicked ***/
			if (isset($_REQUEST['deactivate_license'])) {
					//~ $license_key = $_REQUEST[$this->productField];
					$response = $this->bzfluent_license_check($license_key1,'deactivate');
					echo $response[1];
					if($response[0]){
						if(is_multisite() ){
							if( is_plugin_active_for_network(BZFLUENT_PLUGIN_FILE) ) {
								update_site_option($this->productField, ''); 
								update_site_option('fluent_license_expired', ''); 
							} else {
								update_option($this->productField, ''); 
								update_option('fluent_license_expired', ''); 
							}
						} else {
							update_option($this->productField, ''); 
							update_option('fluent_license_expired', ''); 
						}
					}
			}
			/*** End of license deactivation ***/
			
			/*** License reset button was clicked ***/
			if (isset($_REQUEST['reset_license'])) {
					//~ $license_key = $_REQUEST[$this->productField];
					if( $license_key1 != '' ) {
						$response = $this->bzfluent_license_check($license_key1,'deactivate');
						if($response[0]){
							if(is_multisite() ){
								if( is_plugin_active_for_network(BZFLUENT_PLUGIN_FILE) ) {
									update_site_option($this->productField, ''); 
									update_site_option('fluent_license', ''); 
									update_site_option('fluent_license_expired', ''); 
								} else {
									update_option($this->productField, ''); 
									update_option('fluent_license', ''); 
									update_option('fluent_license_expired', ''); 
								}
							} else {
								update_option($this->productField, ''); 
								update_option('fluent_license', ''); 
								update_option('fluent_license_expired', ''); 
							}
						} else {
							if(is_multisite() ){
								if( is_plugin_active_for_network(BZFLUENT_PLUGIN_FILE) ) {
									update_site_option($this->productField, ''); 
									update_site_option('fluent_license', ''); 
									update_site_option('fluent_license_expired', ''); 
								} else {
									update_option($this->productField, ''); 
									update_option('fluent_license', ''); 
									update_option('fluent_license_expired', ''); 
								}
							} else {
								update_option($this->productField, ''); 
								update_option('fluent_license', ''); 
								update_option('fluent_license_expired', ''); 
							}
						}
				   } else {
					   if(is_multisite() ){
								if( is_plugin_active_for_network(BZFLUENT_PLUGIN_FILE) ) {
									update_site_option($this->productField, ''); 
									update_site_option('fluent_license', ''); 
									update_site_option('fluent_license_expired', ''); 
								} else {
									update_option($this->productField, ''); 
									update_option('fluent_license', ''); 
									update_option('fluent_license_expired', ''); 
								}
						} else {
								update_option($this->productField, ''); 
								update_option('fluent_license', ''); 
								update_option('fluent_license_expired', ''); 
						}
				   }
			}
			/*** End of license reset ***/
			
			
			if(is_multisite() ){
				if( is_plugin_active_for_network(BZFLUENT_PLUGIN_FILE) ) {
					$license_key1 = get_site_option($this->productField); 
				} else {
					$license_key1 = get_option($this->productField); 
				}
			} else {
				$license_key1 = get_option($this->productField); 
			}
			//~ $responsecheck = $this->bzfluent_license_check($license_key1,'status-check');
			
			if( $license_key1 != '' ) {
				$adminSettings = new FluentAdminSettings(BZFLUENT_PRODUCT_ID);
				$responsecheck = $adminSettings->bzfluent_valid_license();
			} else {
				$responsecheck = False;
			}
			
			if ($responsecheck==1) {
				echo "<p>You have an active license.</p>";
			} else {
				echo "<p>Please enter the license key for this product to activate it. You were given a license key when you purchased this item.</p>";
			}	
	
			if( $license_key1 != '' ) {
				$license_key_final = str_repeat('*', strlen($license_key1) - 4) . substr($license_key1, -4);
			}				
			?>
			<style>.form-table p.licenseKey { font-size: 12px; float: left; width: 100%; font-style: italic; }</style>

			<form action="" method="post">
					<table class="form-table">
							<tr>
									<th style="width:100px;"><label for="sample_license_key">License Key</label></th>
									<td >
										<?php if($responsecheck){	?>
											<input class="regular-text" type="password" id="<?php echo $this->productField; ?>" name="<?php echo $this->productField; ?>"  value="<?php if (isset($license_key_final) ) { echo $license_key_final; } ?>" disabled> 	
										<?php } else { ?>
											<input class="regular-text" type="password" id="<?php echo $this->productField; ?>" name="<?php echo $this->productField; ?>" > 	

										<?php } ?>
										<?php if($responsecheck){	echo '<img style="margin-left: 10px;vertical-align: middle;width: 23px;margin-top: -6px;" src="'.plugin_dir_url( __FILE__ ) . 'assets/image/greentick.png'.'">'; 		} ?>
										<?php if($license_key1 != '' && !$responsecheck){	echo '<img style="margin-left: 10px;vertical-align: middle;width: 23px;margin-top: -6px;" src="'.plugin_dir_url( __FILE__ ) . 'assets/image/cross.png'.'" alt="cross">'; } ?>
										<p class="licenseKey"><?php if (isset($license_key_final) ) { echo $license_key_final; } ?></p>
										
									</td>
							</tr>
					</table>
					<p class="submit">
							<?php if($license_key1 != '' && $responsecheck){ ?>
							<input type="submit" name="deactivate_license" value="Deactivate" class="button" />
							<?php }else{ ?>
							<input type="submit" name="activate_license" value="Activate" class="button-primary" />
							<?php } ?>
							<input type="submit" name="reset_license" value="Reset" class="button-primary" />
					</p>
			</form>
			<?php
			
			echo '</div>';
			
	}
}
?>
