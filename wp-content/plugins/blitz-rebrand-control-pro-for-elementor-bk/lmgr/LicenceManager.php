<?php 

if(!class_exists('LicenceManagerGlobal')){
	class LicenceManagerGlobal{

		public $productId,$pluginTitle,$pluginUrl;
		public $productField;
		public $SL_APP_API_URL;

		public function init($productID=null,$pluginTitle=null,$pluginUrl=null)
		{
			$this->productId = ($productID==null) ? 'SL_PRODUCT_ID' : $productID;
			$this->pluginTitle = ($pluginTitle==null) ? 'Pro License' : $pluginTitle;
			$this->pluginUrl = ($pluginUrl==null) ? 'websettingslicense' : $pluginUrl;
			$this->productField = 'software_license_key_' . $this->productId;
			$this->SL_APP_API_URL ='https://waas-pro.com/index.php';
			if(is_multisite()){
				add_action('network_admin_menu', array($this,'license_menu'), 21);
			}else{
				add_action('admin_menu', array($this,'license_menu'), 21);
			}
		}
		public function getOption($key,$default=False) {
			if(is_multisite()){
				switch_to_blog(1);
				$value = get_site_option($key,$default);
				restore_current_blog();
				return $value;
			}else{
				return get_option($key,$default);
			}
		}
		public function updateOption($key,$value) {
			if(is_multisite()){
				return  update_site_option($key,$value);
			}else{
				return update_option($key,$value);
			}
		}
		public function license_status()
		{
			$license_key1 = $this->getOption($this->productField);
			return $this->license_check($license_key1,'status-check');
		}

		public function license_menu() {
			if(is_multisite()){
				$page = 'settings.php';
			}else{
				$page = 'options-general.php';
			}
				add_submenu_page(
									$page,
									__($this->pluginTitle, 'my-plugin-domain'),
									__($this->pluginTitle),
									'manage_options',
									$this->pluginUrl,
									array($this, 'licenseBlock')
							);    
			}

		public function license_check($license_key,$action) {
return array(True,'activated');
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
				$request_uri    = $this->SL_APP_API_URL . '?' . http_build_query( $args );
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


		public function licenseBlock() {
				echo '<div class="wrap">';
				echo '<h3>License Information</h3>';
				$license_key1 = 	$this->getOption($this->productField);
								
				/*** License activate button was clicked ***/
				if (isset($_REQUEST['activate_license'])) {
						$license_key = trim($_REQUEST[$this->productField]);
						$response = $this->license_check($license_key,'activate');
						echo $response[1];
						if($response[0]){
							$this->updateOption($this->productField, $license_key); 
						}
				}
				/*** End of license activation ***/
				
				/*** License activate button was clicked ***/
				if (isset($_REQUEST['deactivate_license'])) {
						//~ $license_key = $_REQUEST[$this->productField];
						$response = $this->license_check($license_key1,'deactivate');
						echo $response[1];
						if($response[0]){
								$this->updateOption($this->productField, ''); 
						}
				}
				/*** End of license deactivation ***/
				
				/*** License reset button was clicked ***/
				if (isset($_REQUEST['reset_license'])) {
						//~ $license_key = $_REQUEST[$this->productField];
						if( $license_key1 != '' ) {
							$response = $this->license_check($license_key1,'deactivate');
							if($response[0]){
								$this->updateOption($this->productField,'');
								$this->updateOption('adminuiflat_license','');
								$this->updateOption('adminuiflat_license_expired','');
							} else {
								$this->updateOption($this->productField,'');
								$this->updateOption('adminuiflat_license','');
								$this->updateOption('adminuiflat_license_expired','');
							}
					   } else {
						   	$this->updateOption($this->productField,'');
							$this->updateOption('adminuiflat_license','');
							$this->updateOption('adminuiflat_license_expired','');
					   }
				}
				/*** End of license reset ***/
				
				
				$license_key1 = 	$this->getOption($this->productField);
				//~ $responsecheck = $this->license_check($license_key1,'status-check');
				
				
				if( $license_key1 != '' ) {
					$adminSettings = new AdminUIPROFlat();
					$responsecheck = $adminSettings->adminuiflat_validLicense($license_key1);
				} else {
					$responsecheck = False;
				}
				
				if ($responsecheck) {
					echo "<p>You have an active license.</p>";
					echo "<p>Please refresh your browser after you've activated your license</p>";
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
												<input class="regular-text" type="password" id="<?php echo $this->productField; ?>" name="<?php echo $this->productField; ?>"  value="<?php if (isset($license_key_final) ) { echo $license_key_final; } ?>"  disabled> 	
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
}
