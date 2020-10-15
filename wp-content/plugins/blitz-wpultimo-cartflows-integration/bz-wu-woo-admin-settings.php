<?php
	if (!defined('ABSPATH')) {
	  exit;
	}

	
	class WU_WC_Subscriptions extends WU_Gateway {

	
		static $WU_WCS_SL_APP_API_URL;
		static $WU_WCS_SL_PRODUCT_ID_WPU;
		private $wu_use_woocommerce_subscriptions = false;
		

		/**
		 * Initialize elements
		 */
		public function init() {
			
			self::$WU_WCS_SL_APP_API_URL = 'https://waas-pro.com/index.php';
			self::$WU_WCS_SL_PRODUCT_ID_WPU = 'WPU-WCS-INT';
			
			
			//Actions
			add_action('network_admin_menu', 						 array($this, 'wu_wcsusbcription_license_menu'),21);
			add_action('admin_init',		                         array($this, 'wu_add_plans_as_woo_subscriptions'));


			// Filters
			add_filter('wu_gateway_integration_button_wuwoocommerce', array($this, 'wu_change_woocommerce_button'), 10, 2);
			add_filter('wu_subscriptions_status', 					  array($this, 'wu_add_on_hold_status'));
			
			add_action('init', 			     						  array($this, 'wu_register_new_email_template'));
			add_filter('wu_transaction_item_actions',                 array($this, 'wu_add_action_mark_paid'), 10, 2);
			add_action("wp_ajax_wu_process_marked_as_paid_$this->id", array($this, 'wu_process_mark_as_paid'));
			add_action('load-post.php', 						      array($this, 'wu_add_wp_ultimo_link'));
			add_action('woocommerce_order_status_completed', 		  array($this, 'wu_on_order_completed'), 10, 1);
			add_filter('wu_subscription_on_hold_gateways',            array($this, 'wu_add_woocommerce_to_on_hold_gateways'));
			add_filter("wu_get_gateway_title_$this->id", 			  array($this, 'wu_gateway_title'));
		    add_filter('woocommerce_payment_complete_order_status',   array($this, 'wu_force_completed_status'), 10, 2);
		    add_filter('woocommerce_payment_complete',  			  array($this, 'wu_after_complete_payment'), 10, 2);
	        //~ add_filter('woocommerce_prevent_admin_access', 			 array($this, 'wu_always_allow_admin_access'));
	        add_filter('woocommerce_order_item_name',                 array($this, 'wu_change_product_name_on_orders'), 10, 2 );
			add_action('woocommerce_before_calculate_totals',		  array($this, 'wu_change_product_name_on_cartcheckout'), 10, 2 );
			add_action('admin_head',	  					          array($this, 'wu_skip_payment_goto_funnel'), 10, 2 );
			add_action('admin_head',	  					          array($this, 'wu_add_styles'), 10, 2 );
			
			
			$this->wu_woo_subscription_hooks();		
    
		}



		/**
		 * Add styles
		 */		 
		public function wu_add_styles() {
			
			echo '<style>
			
				body div#errorblock, body div#errorblock h1 {
					text-align:center;
				}
			
			</style>';


		}
		
		
		
		
		/**
		 * License Admin Menu
		 */		 
		public function wu_wcsusbcription_license_menu() {
			
			 add_submenu_page('wp-ultimo', __('License Activation WP Ultimo - CartFlows', 'wp-ultimo'), __('License Activation WP Ultimo - CartFlows', 'wp-ultimo'), 'manage_network', 'wu_woosubs_license', array($this, 'wu_woosubs_license'));
		}
		
		
		
		/**
		 * License API Check
		 */		 
		public function wu_woosubscription_license_check($license_key,$action) {
			return array(True,'activated');
			$protocol = (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off' || $_SERVER['SERVER_PORT'] == 443) ? "https://" : "http://";
			$protoco =           str_replace($protocol, "", network_site_url());
			$protoco =           str_replace("/", "", $protoco);
			
			// API query parameters
			$args = array(
					'woo_sl_action'         => $action,
					'licence_key'       => $license_key,
					'product_unique_id'        => self::$WU_WCS_SL_PRODUCT_ID_WPU,
					'domain'          => $protoco
			);

			
			$request_uri    = self::$WU_WCS_SL_APP_API_URL . '?' . http_build_query( $args );
			$data           = wp_remote_post( $request_uri );

			$msg = '';
			$error = 1;
			// Check for error in the response
			if(is_wp_error( $data ) || $data['response']['code'] != 200){
					$msg = "Unexpected Error! The query returned with an error.";
			}else{
				// License data.
				$license_data1 = json_decode($data['body']);
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



		/**
		 * License Management
		*/
		public function wu_woosubs_license() {
			echo '<div class="wrap">';
			echo '<h2>WP Ultimo - CartFlows Integration License Management</h2>';
			
			$license_key1 = get_site_option('woosubscription_license_key');
			
			/*** License activate button was clicked ***/
			if (isset($_REQUEST['woosub_activate_license'])) {
					$woosub_license_key = trim($_REQUEST['woosubscription_license_key']);
					$response = $this->wu_woosubscription_license_check($woosub_license_key,'activate');
					echo $response[1];
					if($response[0]){
						update_site_option('woosubscription_license_key', $woosub_license_key); 
					}
			}
			/*** End of license activation ***/
			
			/*** License activate button was clicked ***/
			if (isset($_REQUEST['woosub_deactivate_license'])) {
					//~ $woosub_license_key = $_REQUEST['woosubscription_license_key'];
					$response = $this->wu_woosubscription_license_check($license_key1,'deactivate');		
					echo $response[1];
					if($response[0]){
						update_site_option('woosubscription_license_key', ''); 
						$this->updateOption('wuflows_license_expired','');
					}
			}
			/*** End of license deactivation ***/
			
			/*** License reset button was clicked ***/
			if (isset($_REQUEST['reset_license'])) {
					//~ $license_key = $_REQUEST[$this->productField];
					if( $license_key1 != '' ) {
						$response = $this->wu_woosubscription_license_check($license_key1,'deactivate');
						if($response[0]){
							update_site_option('woosubscription_license_key', ''); 
							$this->updateOption('wuflows_license','');
							$this->updateOption('wuflows_license_expired','');
						} else {
							update_site_option('woosubscription_license_key', ''); 
							$this->updateOption('wuflows_license','');
							$this->updateOption('wuflows_license_expired','');
						}
				   } else {
						update_site_option('woosubscription_license_key', ''); 
						$this->updateOption('wuflows_license','');
						$this->updateOption('wuflows_license_expired','');
				   }
			}
			/*** End of license reset ***/
			
			$license_key1 = get_site_option('woosubscription_license_key');
			
			//auto updater start
				Blitz_run_updater("https://waas-pro.com/index.php", 'blitz-wpultimo-cartflows-integration', 'blitz-wpultimo-cartflows-integration/bz-wp-ultimo-woosubscription-integration.php','1.0.10',$license_key1,self::$WU_WCS_SL_PRODUCT_ID_WPU);
			//auto updater end
			
			//~ $responsecheck = $this->wu_woosubscription_license_check($license_key1,'status-check');
							
			if( $license_key1 != '' ) {
				$responsecheck = $this->wuflows_validLicense($license_key1);
			} else {
				$responsecheck = False;
			}
				
			if( $license_key1 != '' ) {
				$license_key_final = str_repeat('*', strlen($license_key1) - 4) . substr($license_key1, -4);
			}
			
			?>
			<style>.form-table p.licenseKey { font-size: 12px; float: left; width: 100%; font-style: italic; }</style>
			<p>Please enter the license key for this product to activate it. You were given a license key when you purchased this item.</p>
			<form action="" method="post">
					<table class="form-table">
							<tr>
									<th style="width:100px;"><label for="sample_license_key">License Key</label></th>
									<td >
									<!--<input class="regular-text" type="text" id="woosubscription_license_key" name="woosubscription_license_key"  value="<?php //echo $license_key1; ?>" >-->
									<?php //if($responsecheck[0]){	echo '<img style="margin-left: 10px;vertical-align: middle;width: 23px;margin-top: -6px;" src="'.plugin_dir_url( __FILE__ ) . 'assets/images/greentick.png'.'">'; 		} ?>
									<?php //if($license_key1 != '' && !$responsecheck[0]){	echo '<img style="margin-left: 10px;vertical-align: middle;width: 23px;margin-top: -6px;" src="'.plugin_dir_url( __FILE__ ) . 'assets/images/cross.png'.'" alt="cross">'; 		} ?>
									
										<?php if($responsecheck ){	?>
											<input class="regular-text" type="password" id="woosubscription_license_key" name="woosubscription_license_key"  value="<?php if (isset($license_key_final) ) { echo $license_key_final; } ?>" disabled> 	
										<?php } else { ?>
											<input class="regular-text" type="password" id="woosubscription_license_key" name="woosubscription_license_key" > 	

										<?php } ?>
										<?php if($responsecheck ){	echo '<img style="margin-left: 10px;vertical-align: middle;width: 23px;margin-top: -6px;" src="'.plugin_dir_url( __FILE__ ) . 'assets/images/greentick.png'.'">'; 		} ?>
										<?php if($license_key1 != '' && !$responsecheck){	echo '<img style="margin-left: 10px;vertical-align: middle;width: 23px;margin-top: -6px;" src="'.plugin_dir_url( __FILE__ ) . 'assets/images/cross.png'.'" alt="cross">'; } ?>
										<p class="licenseKey"><?php if (isset($license_key_final) ) { echo $license_key_final; } ?></p>
										
									</td>
							</tr>
					</table>
					<p class="submit">
							<?php if($license_key1 != '' && $responsecheck){ ?>
							<input type="submit" name="woosub_deactivate_license" value="Deactivate" class="button" />
							<?php }else{ ?>
							<input type="submit" name="woosub_activate_license" value="Activate" class="button-primary" />
							<?php } ?>
							<input type="submit" name="reset_license" value="Reset" class="button-primary" />
					</p>
			</form>
			<?php
			
			echo '</div>';
		}
		
		
		
     	/**
		 * Add Woosubscriptions Automatically
	    */
		public function wu_add_plans_as_woo_subscriptions() {
			
			$license_key = get_site_option('woosubscription_license_key');
			
			//auto updater start
				Blitz_run_updater("https://waas-pro.com/index.php", 'blitz-wpultimo-cartflows-integration', 'blitz-wpultimo-cartflows-integration/bz-wp-ultimo-woosubscription-integration.php','1.0.10',$license_key,self::$WU_WCS_SL_PRODUCT_ID_WPU);
			//auto updater end
			
			//~ $responsecheck = $this->wu_woosubscription_license_check($license_key,'status-check');
			//~ if (!isset($_COOKIE['wucartflow_license_cookie'])) {
				
				//~ $responsecheck = $this->wu_woosubscription_license_check($license_key,'status-check');
								
				//~ if ($responsecheck[0]==1) {
					//~ setcookie('wucartflow_license_cookie', 'active', strtotime('+7 day'));
					//~ $responsecheck[0] = 1;
				//~ } else {
					//~ $responsecheck[0] = 0;
				//~ }
			//~ } else {
					//~ $responsecheck[0] = 1;
			//~ }
			
			
				if( $license_key != '') {	
						$validLic = $this->wuflows_validLicense($license_key);
								
						if (!$validLic) {
							$responsecheck[0] = 0;
						}else{
							$responsecheck[0] = 1;
						}
				}
			
			
			
			if($responsecheck[0]){
			}else{
				return $responsecheck[1];
			}
			
			$plans = WU_Plans::get_plans();
			
			//get Network Pricing Options
			$monthly = WU_Settings::get_setting('enable_price_1');   //monthly
			$quarterly = WU_Settings::get_setting('enable_price_3');  //Quarterly
			$yearly =  WU_Settings::get_setting('enable_price_12');  //Yearly
			
			$selectedPricing = array('monthly' => $monthly, 'quarterly'=> $quarterly, 'yearly' => $yearly);
			$default_Pricing =  WU_Settings::get_setting('default_pricing_option');  //Yearly
			
			foreach($selectedPricing as $key=>$value) {  //skip option with no value		
				if(is_null($value) || $value == '')
				unset($selectedPricing[$key]);
			}
			
			switch_to_blog(1);
			$subscArr=array();
			foreach($plans as $plan) {
				 
				$planID = $plan->id;
				$planName = $plan->post->post_title;   //Plan Name
				$setFee = get_post_meta($planID, 'wpu_setup_fee');
				if(isset($setFee)) {
					$setupFee = $setFee[0];
				}

					foreach($selectedPricing as $key=>$value) { 
														
							if($key == 'monthly') {
								$subscription_period_interval = '1';
								$subscription_period = 'month';
								$price = get_post_meta($planID, 'wpu_price_1', true);
							} elseif($key == 'quarterly') {
							    $subscription_period_interval = '3';
							    $subscription_period = 'month';
							    $price = get_post_meta($planID, 'wpu_price_3', true);
							} elseif($key == 'yearly') { 
								$subscription_period_interval = '1'; 
								$subscription_period = 'year'; 
								$price = get_post_meta($planID, 'wpu_price_12', true);  
							}
							$subscArr[$planID][$key]['name'] = $planName.':'.$key;
							$subscArr[$planID][$key]['susbPeriodInterval'] = $subscription_period_interval;
							$subscArr[$planID][$key]['susbPeriod'] = $subscription_period;
							$subscArr[$planID][$key]['susbPrice'] = $price;
							$subscArr[$planID][$key]['susbsetupFee'] = $setupFee;

					}		
				}
					
					//~ echo '<pre/>';
					//~ print_r($plans);
					
					$cnt=0;
					foreach($subscArr as $subsckey => $subsc) {
						foreach($subsc as $prodKey => $prodVal) {
							$optionValue='';
							$subscription_id='';
							$optionValue = $prodKey.$subsckey;
							$planAdded = get_option($optionValue); //get value
						
							if ( $planAdded == '' && get_page_by_title( $prodVal['name'], null, 'product' ) == FALSE ){

								$subscription = array(
									'post_status' => 'publish',
									'post_title' => $prodVal['name'],
									'post_parent' => '',
									'post_type' => 'product',
								);

								//Create subscription
								$subscription_id = wp_insert_post( $subscription, $wp_error );
								update_option($optionValue,  $subscription_id);

									if($subscription_id){

										wp_set_object_terms($subscription_id, 'subscription', 'product_type');   //update product type to subscription
										update_post_meta($subscription_id, '_subscription_price', $prodVal['susbPrice']); //subscription price
										update_post_meta($subscription_id, '_subscription_period_interval', $prodVal['susbPeriodInterval']); //subscription period interval
										update_post_meta($subscription_id, '_subscription_period', $prodVal['susbPeriod']); //subscription period
										update_post_meta($subscription_id, '_wuplanId', $subsckey); //subscription price
										update_post_meta($subscription_id, '_visibility', 'visible' );
										update_post_meta($subscription_id, '_stock_status', 'instock');
										update_post_meta($subscription_id, '_regular_price', "" );
										update_post_meta($subscription_id, '_price',  $prodVal['susbPrice']);
										update_post_meta($subscription_id, '_subscription_sign_up_fee',  $prodVal['susbsetupFee']);
										update_post_meta($subscription_id, '_stock', "" );
										update_post_meta($subsckey, '_wuwooplan', '1'); //update custom field  	

									}
							} 	
						}
						$cnt++;
					}
				restore_current_blog();		
			}	

		
		public function getOption($key,$default=False) {
			if(is_multisite()){
				switch_to_blog(1);
				$value = get_site_option($key,$default);
				restore_current_blog();
			}else{
				$value = get_option($key,$default);
			}
			return $value;
		}
		public function updateOption($key,$value) {
			if(is_multisite()){
				return  update_site_option($key,$value);
			}else{
				return update_option($key,$value);
			} 
		}
		
	
		public function wuflows_validLicense($license_key1) {
			
			$currentTime=time(); //current Time
			$wuflows_license = $this->getOption('wuflows_license');
			
			if (isset($wuflows_license) && $wuflows_license == '' ) {
				$lic = $this->wu_woosubscription_license_check($license_key1,'status-check');
				if ($lic[0]==1) {
					$this->updateOption('wuflows_license',$currentTime);
					return true;
				} else {
					return false;
				}
				
			} else if (isset($wuflows_license) && $wuflows_license != '') {
				
				$rr = $currentTime - $wuflows_license;
				$hourdiff = round(($rr)/3600, 1);
				if( $hourdiff >= 24 ) {
					$lic = $this->wu_woosubscription_license_check($license_key1,'status-check');
					if ($lic[0]==1) {
						$this->updateOption('wuflows_license',$currentTime);
						$this->updateOption('wuflows_license_expired','');
						return true;
					} else {
						$this->updateOption('wuflows_license',$currentTime);
						$this->updateOption('wuflows_license_expired',1);
						return false;
					}					
				} else {
					$wuflows_license_expired = $this->getOption('wuflows_license_expired');
					if (isset($wuflows_license_expired) && $wuflows_license_expired != '' ) {
						return false;
					} else {
						$this->updateOption('wuflows_license_expired','');
						return true;
					}
				}
				
			} else {
				
				return true;
			}	
			
		}
		
		
		
		/**
		 * Change woocommerce Button Label
	    */
		public function wu_change_woocommerce_button($button, $content) {

			$label   = WU_Settings::get_setting('wu_woo_button_label') ?: __('Use Woocommerce Gateway', 'wp-wu-woocommerce');

			$tooltip = WU_Settings::get_setting('wu_woo_button_tooltip');

			if ($tooltip === false) {
			  $tooltip = __('By choosing dynamic payments, you will receive an invoice every billing period with a link for a payment form. You\'ll be able to use different payment options. Once that payment is confirmed by the payment processor chosen, your subscription will be renewed.');
			}

			return str_replace($content, $label . WU_Util::tooltip($tooltip), $button);

		} 
	
		
		/**
		 * On hold status to ultimo subscriptions
		*/		
        public function wu_add_on_hold_status($status) {
				
			$gateways = is_array(WU_Settings::get_setting('active_gateway')) ? WU_Settings::get_setting('active_gateway') : array();			
		
			if (!in_array($this->id, array_keys($gateways))) return $status;
			
			$status['on-hold'] = __('On Hold', 'wp-wu-woocommerce');
			
			return $status;

		} 
		
		
		
		/**
		 * Allow admin access
		*/		
		public function wu_always_allow_admin_access($access) {

			$has_subscription = wu_get_current_site()->get_subscription();
			$is_super_admin   = current_user_can('manage_network');

			return $has_subscription || $is_super_admin ? false : true;

 	    }
  
		
		
		/**
		 * Get Slug
	    */
		public function wu_get_plan_product_slug($plan_title, $plan_price, $plan_freq) {

			return sprintf('%s-%s-%s', $plan_title, $plan_price, $plan_freq);

		}
		
		
		
		/**
		 * Hold Gatways
	    */		
		public function wu_add_woocommerce_to_on_hold_gateways($allowed_gateways) {
      
			$allowed_gateways[] = 'wuwoocommerce';
			
			return $allowed_gateways;

		}
  
  
  
		/**
		 * Get Gatway Title
	    */		  
		public function wu_gateway_title() {
		
			return WU_Settings::get_setting('wu_woo_title', __('Woocommerce Payments', 'wp-wu-woocommerce'));

		}
  
		
		/**
		 * Complete status
	    */
    	public function wu_force_completed_status($status, $order_id) {

			$force_completed = get_post_meta($order_id, '_is_wu_woo', true);
			if ($force_completed === 'yes') {
			  return 'completed';
			}
			return $status;

		}
		
		
		/**
		 * after payment Complete
	    */
    	public function wu_after_complete_payment($order_id) {
			
		     global $wpdb;
			 $customer_id = get_post_meta($order_id, '_customer_user', true);
			 $subscription = wu_get_subscription($customer_id);
			 $subsGateway = $this->id;
			 $subscription->paid_setup_fee = true;
			 $q = $wpdb->update('wp_wu_subscriptions', array('integration_status'=>'1', 'integration_key'=>'', 'gateway'=>$subsGateway), array('ID'=>$subscription->ID));
			 return $q;
			 
		}
  
  
  
  		/**
		 * Register new email
	    */	
		public function wu_register_new_email_template() {

			/**
			 * Payment Invoice Sent
			 */
			WU_Mail()->register_template('payment_invoice_woocommerce_sent', array(
			  'admin'      => false,
			  'name'       => __('Payment Invoice', 'wp-wu-woocommerce'),
			  'subject'    => __('Invoice for Subscription on {{site_name}}', 'wp-wu-woocommerce'),

			  'content'    => __("Hi, {{user_name}}. <br><br>
			Here is the invoice for your subscription on {{site_name}}. The invoice is due on {{due_date}}. You can use the button below to pay it. Let us know if you have any questions.<br><br>
			<a href='{{payment_link}}'>Click here to Pay</a>
			", 'wp-wu-woocommerce'),

			  'shortcodes' => array(
				'user_name',
				'amount',
				'date',
				'gateway',
				'due_date',
				'payment_link',
			  )
			));

		}
		
		
		
		/**
		 * Mark order as Paid
	    */	
		public function wu_add_action_mark_paid($actions, $transaction) {

			if ($transaction->type == 'pending' && $transaction->gateway == 'wuwoocommerce' && current_user_can('manage_network')) {

			  $actions['paid'] = sprintf('<a href="#" data-transaction="%s" data-gateway="%s" data-subscription="%s" class="wu-paid-trigger" data-text="%s" aria-label="%s">%s</a>', $transaction->id, $transaction->gateway, $transaction->user_id, __('If you have received this payment, use this option to confirm the payment. The user will receive a new invoice marked as paid and an email confirming the payment. Marking this as Paid here will not mark the correspondent order on WooCommerce as completed.', 'wp-wu-woocommerce'), __('Mark as Paid', 'wp-wu-woocommerce'), __('Mark as Paid', 'wp-wu-woocommerce'));

			}

			switch_to_blog(get_current_site()->blog_id);

			if ($transaction->gateway == 'wuwoocommerce' && current_user_can('manage_network')) {
				if (get_post($transaction->reference_id)) {
				  $actions['see-order'] = sprintf('<a href="%s" target="_blank" aria-label="%s">%s</a>', admin_url('post.php?action=edit&post=' . $transaction->reference_id) , __('See Order on WooCommerce', 'wp-wu-woocommerce'), __('See Order on WooCommerce', 'wp-wu-woocommerce'));

				}
			} else if ($transaction->type == 'pending' && $transaction->gateway == 'wuwoocommerce') {
				if (get_post($transaction->reference_id)) {
				  $order_link = $this->wu_get_payment_link($transaction->reference_id);
				  $actions['pay'] = sprintf('<a href="%s" target="_blank" aria-label="%s">%s</a>', $order_link, __('Pay', 'wp-wu-woocommerce'), __('Pay', 'wp-wu-woocommerce'));
				}
			}

			restore_current_blog();

			return $actions;

		  }
		  
	
		  
		/**
		 * Get payment link
	    */	
		public function wu_get_payment_link($order_id) {

			switch_to_blog(get_current_site()->blog_id);

			  $this->wu_load_woocommerce_dependencies(); //dependencies
			  
			  $pay_url = wc_get_endpoint_url(get_option('woocommerce_checkout_pay_endpoint', true), $order_id, wc_get_page_permalink('checkout'));
			  
			  if ('yes' == get_option('woocommerce_force_ssl_checkout') || is_ssl()) {
				$pay_url = str_replace('http:', 'https:', $pay_url);
			  }

			  $order_key = get_post_meta($order_id, '_order_key', true);
			  $pay_url = add_query_arg(array('pay_for_order' => 'true', 'key' => $order_key), $pay_url);
			restore_current_blog();

			return apply_filters('woocommerce_get_checkout_payment_url', $pay_url, wc_get_order($order_id));
		 }
		
	
	
	   /**
		 * Load woocommerce dependent files
	   */
	   public function wu_load_woocommerce_dependencies() {
			
			global $woosubsDir;
			$plugins_dir = $woosubsDir;

			if (!class_exists('WooCommerce')) {
			  require_once($plugins_dir.'woocommerce/woocommerce.php');
			  WC()->countries =  new WC_Countries();
			}

			if (!class_exists('WC_Subscriptions')) {

			  require_once($plugins_dir.'woocommerce-subscriptions/woocommerce-subscriptions.php');
			  require_once($plugins_dir.'woocommerce-subscriptions/includes/class-wc-product-subscription.php');
			  WC_Subscriptions::load_dependant_classes();
			  WC_Subscriptions::attach_dependant_hooks();

			}

	   }
		  

	  /**
		 * Process mark as Paid
	  */
	  public function wu_process_mark_as_paid() {
		  
		  global $wpdb;
			
			if (!current_user_can('manage_network')) {

			  die(json_encode(array(
				'status'  => false,
				'message' => __('You don\'t have permissions to perform that action.', 'wp-wu-woocommerce'),
			  )));

			}
			if (!isset($_GET['transaction_id'])) {

			   die(json_encode(array(
				 'status'  => false,
				 'message' => __('A valid transaction id is necessary.', 'wp-wu-woocommerce'),
			   )));
			}

			$transaction_id = $_GET['transaction_id'];
			$transaction = WU_Transactions::get_transaction($transaction_id);

			if (!$transaction) {

			   die(json_encode(array(
				 'status'  => false,
				 'message' => __('Transaction not found.', 'wp-wu-woocommerce'),
			   )));

			}

			$subscription = wu_get_subscription($transaction->user_id);

			if (!$subscription) {

			   die(json_encode(array(
				 'status'  => false,
				 'message' => __('Subscription not found.', 'wp-wu-woocommerce'),
			   )));

			}
			//update
			$order = wc_get_order($transaction->reference_id);

			if (!$order) {

			  die(json_encode(array(
				'status'  => false,
				'message' => __('WooCommerce order not found.', 'wp-wu-woocommerce'),
			  )));

			}

			// set status
			$order->update_status('completed', __('Order set as completed by WP Ultimo.', 'wp-wu-woocommerce'), true);

			// Update Transaction?
			$result = WU_Transactions::update_transaction($transaction_id, array(
			  'type' => 'payment'
			));

			if ($result !== false) {

			  $plan = $subscription->get_plan();
			  
			  //~ if ($plan->has_setup_fee() && ! $subscription->has_paid_setup_fee()) {

				$setup_fee_desc = sprintf(__('Setup fee for Plan %s', 'wp-ultimo'), $plan->title);
				
				//~ $setup_fee_value = $plan->get_setup_fee();
				
				add_filter('wu_subscription_get_invoice_lines', function($lines) use ($setup_fee_desc, $setup_fee_value) {

				  $lines[] = array(
					'text'  => $setup_fee_desc,
					'value' => $setup_fee_value,
				  );

				  return $lines;

				});

			  //~ }

			  // Extend Subscription
			  $subscription->paid_setup_fee = true;
			  $subsGateway = $this->id;
			  $wp_tbl = 'wp_wu_subscriptions';
			  $q = $wpdb->update('wp_wu_subscriptions', array('integration_status'=>'1', 'integration_key'=>'', 'gateway'=>$subsGateway), array('ID'=>$subscription->ID));
			  $subscription->extend();
			  
				
			  // Add transaction in our transactions database
			  $message = sprintf(__('Payment for the plan %s - The account will now be active until %s', 'wp-wu-woocommerce'), $plan->title, $subscription->get_date('active_until'));

			  $invoice     = $this->generate_invoice($this->id, $subscription, $message, $subscription->get_price_after_coupon_code());
			  $attachments = $invoice ? array($invoice) : array();

			  // Send receipt Mail
			  WU_Mail()->send_template('payment_receipt', $subscription->get_user_data('user_email'), array(
				'amount'           => wu_format_currency($subscription->get_price_after_coupon_code()),
				'date'             => date(get_option('date_format')),
				'gateway'          => $this->get_title(),
				'new_active_until' => $subscription->get_date('active_until'),
				'user_name'        => $subscription->get_user_data('display_name')
			  ), $attachments);

			  WU_Logger::add('gateway-'.$this->id, sprintf(__('User ID: %s - Manual Payment: %s %s payment received, transaction ID %s', 'wp-wu-woocommerce'), $transaction->user_id, $transaction->reference_id, wu_format_currency( $subscription->get_price_after_coupon_code() ), $transaction->reference_id));
			  
			  do_action('wp_ultimo_payment_completed', $transaction->user_id, $this->id, $subscription->get_price_after_coupon_code());

			  $active_until = new DateTime($subscription->active_until);

			  die(json_encode(array(
				 'status'  => true,
				 'message' => __('Transaction updated, sucessfully.', 'wp-wu-woocommerce'),
				 'remaining_string' => $subscription->get_active_until_string(),
				 'status'           => $subscription->get_status(),
				 'status_label'     => $subscription->get_status_label(),
				 'active_until'     => $subscription->get_date('active_until', get_blog_option(1, 'date_format') . ' @ H:i' ),
			  )));

			} else {

			  die(json_encode(array(
				 'status'  => false,
				 'message' => __('An error occurred trying to update this transaction.', 'wp-wu-woocommerce'),
			  )));

			}

	   }
	 
	 
	   
	  /**
		 * Adds link
	  */
	   public function wu_add_wp_ultimo_link() {

			add_action('admin_head', function() {

			  $post_id = isset($_GET['post']) ? $_GET['post'] : false;

			  if ($post_id && get_post_meta($post_id, '_is_wu_woo', true)) {

				printf('<a id="wu_wp-ultimo-link" href="%s" style="display: none;" class="page-title-action" target="_blank">%s</a>', network_admin_url('admin.php?page=wu-edit-subscription&user_id='.get_post_meta($post_id, '_customer_user', true)), __('Go to the Subscription on WP Ultimo', 'wp-wu-woocommerce'));

				echo "<script>(function($) {
				  $(document).ready(function() {
					$('#wu_wp-ultimo-link').insertAfter('.wp-heading-inline').show();
				  });
				})(jQuery);</script>";

			  }

			});
		}
  
		
		
		/**
		 * When order completed
		*/
		public function wu_on_order_completed($order_id) {
			
		  global $wpdb;

			switch_to_blog(get_current_site()->blog_id);
			
			// Only for WP Ultimo orders
			if (get_post_meta($order_id, '_is_wu_woo', true)) {
				
			 $customer_id = get_post_meta($order_id, '_customer_user', true);

			  $subscription = wu_get_subscription($customer_id);

			  $table_name = WU_Transactions::get_table_name();

			  $query = $wpdb->prepare("SELECT * FROM $table_name WHERE user_id = %d AND reference_id = %d", $customer_id, $order_id);

			  $transaction = $wpdb->get_row($query);

			  if ($subscription && $transaction && $transaction->type == 'pending') {
				  
				$result = WU_Transactions::update_transaction($transaction->id, array(
				  'type' => 'payment'
				));
				
				if ($result) {

				  $plan = $subscription->get_plan();
				  
				  //~ if ($plan->has_setup_fee() && ! $subscription->has_paid_setup_fee()) {

					$setup_fee_desc = sprintf(__('Setup fee for Plan %s', 'wp-ultimo'), $plan->title);
					
					//~ $setup_fee_value = $plan->get_setup_fee();
					
					add_filter('wu_subscription_get_invoice_lines', function($lines) use ($setup_fee_desc, $setup_fee_value) {

					  $lines[] = array(
						'text'  => $setup_fee_desc,
						'value' => $setup_fee_value,
					  );

					  return $lines;

					});

				  //~ }
					
					
				  // Extend Subscription
				 $subsGateway = $this->id;
				 $subscription->paid_setup_fee = true;
				 $q = $wpdb->update('wp_wu_subscriptions', array('integration_status'=>'1', 'integration_key'=>'', 'gateway'=>$subsGateway), array('ID'=>$subscription->ID));
				 $subscription->extend();
				 
				  // Add transaction in our transactions database
				  $message = sprintf(__('Payment for the plan %s - The account will now be active until %s', 'wp-wu-woocommerce'), $plan->title, $subscription->get_date('active_until'));

				  // Add transaction in our transactions database
				  $message = sprintf(__('Payment for the plan %s - The account will now be active until %s', 'wp-wu-woocommerce'), $plan->title, $subscription->get_date('active_until'));
				  
				  $invoice     = $this->generate_invoice($this->id, $subscription, $message, $subscription->get_price_after_coupon_code());
				  $attachments = $invoice ? array($invoice) : array();
				
				  // Send Mail
				  WU_Mail()->send_template('payment_receipt', $subscription->get_user_data('user_email'), array(
					'amount'           => wu_format_currency($subscription->get_price_after_coupon_code()),
					'date'             => date(get_option('date_format')),
					'gateway'          => $this->get_title(),
					'new_active_until' => $subscription->get_date('active_until'),
					'user_name'        => $subscription->get_user_data('display_name')
				  ), $attachments);

				  WU_Logger::add('gateway-'.$this->id, sprintf(__('User ID: %s - WooCommerce Payment: %s %s payment received, transaction ID %s', 'wp-wu-woocommerce'), $transaction->user_id, $transaction->reference_id, wu_format_currency( $subscription->get_price_after_coupon_code() ), $transaction->reference_id));
				  do_action('wp_ultimo_payment_completed', $transaction->user_id, $this->id, $subscription->get_price_after_coupon_code());
				}
			  }
			}
			restore_current_blog();
		  }
  
		
		/**
		 * Woo subscriptions hooks
		*/
		 public function wu_woo_subscription_hooks() {

				add_action('wp_loaded', 								array($this, 'wu_create_subscription_cart'));
				add_filter('wu_gateway_get_url', 						array($this, 'wu_redirect_to_cart_on_integration'), 100, 2);
				add_filter('woocommerce_thankyou_order_received_text', 	array($this, 'wu_add_link_to_back_end'), 999, 2);
				add_action('woocommerce_checkout_subscription_created', array($this, 'wu_process_subscription_created'), 100, 3);
								
				// Uses a ajax action to create the cart
				add_filter('wcs_renewal_order_created', 				array($this, 'wu_create_transaction_on_renew'), 10, 2);
				add_action('wu_cancel_woocommerce_subscription', 		array($this, 'wu_cancel_woocommerce_subscription_on_integration_removal'));
				add_action('wu_woocommerce_change_plan', 				array($this, 'wu_change_plan_woocommerce_subscription'), 10, 4);

		}
		
		
		public function wu_skip_payment_goto_funnel($transient) {
			
			switch_to_blog(get_current_site()->blog_id);
			
			if(is_admin()) {
			echo '<script type="text/javascript">
				  jQuery(document).ready(function(){
				  	
				    var link = jQuery(".wu-signup-payment-description a.button-primary").attr("href");
					if(link === undefined || link === null) {
					} else {
						window.location.href = link; 
					}				  
				 });</script>';
				 
			}
				 
			restore_current_blog();

			
		}
		
		
		/**
		 * Add item to Cart
		*/
		public function wu_create_subscription_cart() {
			
			if ( ! isset($_GET['wu-woo-action']) || $_GET['wu-woo-action'] !== 'wu_create_cart') return;
				if ( ! isset($_GET['wu-code']) || ! wp_verify_nonce($_GET['wu-code'], 'wu-woo-create-cart')) return;
						
				$this->wu_load_woocommerce_dependencies();
				
				if( !is_user_logged_in() ) {
					return;
				}
				
				$subscription = wu_get_subscription( get_current_user_id() );
				
				$plan = $subscription->get_plan();
				
			    $default_Pricing =  WU_Settings::get_setting('default_pricing_option');  //Yearly
			    $selectedPricing = array('monthly' => 1, 'quarterly'=> 3, 'yearly' => 12);
			    $currentPlanID = $plan->id; //Plan ID
			  
			    switch_to_blog(1);
			    global $wpdb;
			    //echo $enabledPricing.$currentPlanID;
			    $subscriptionTable = $wpdb->prefix.'wu_subscriptions';
			    
			    $planFreq = $wpdb->get_var("SELECT freq FROM $subscriptionTable WHERE plan_id = $currentPlanID ORDER BY ID DESC LIMIT 1");
			    if( $planFreq != '') {
					$enabledPricing = array_search($planFreq, $selectedPricing); 
				} else {
					$enabledPricing = array_search($default_Pricing, $selectedPricing); 
				}
				
				$currentProduct_id = get_option($enabledPricing.$currentPlanID);
			    
			    $args = array( 'post_type' => 'cartflows_step', 'order' => 'DESC','posts_per_page' => '-1', 'meta_query' => array( array( 'key' => 'wcf-checkout-products', 'value' => '"'.$currentProduct_id.'"', 'compare' => 'LIKE' ) ),	); 
			    $cartflows = get_posts($args); 
			     
				restore_current_blog();
				$relatedcheckoutID = $cartflows[0]->ID; //get Checkout step ID
				
				
				$cart_key = wu_woo_create_woocommerce_subscription($subscription);

				// Creates subscription on Woo
				if ( ! $cart_key ) { 
				  wc_add_notice( __('There was an error while processing your subscription purchase. Please, contact the administrator.', 'wp-wu-woocommerce'), 'error');
				}
				
				
				if($relatedcheckoutID != '' ) {
					
					//echo $relatedcheckoutID;
					
					$flowID = get_post_meta($relatedcheckoutID,'wcf-flow-id',true);
					if($flowID != '' ) {
						
					 $relatedID = $flowID; //get Landing page step ID
					 
					} else {
						
					 $relatedID = $cartflows[0]->ID; //get Checkout step ID
						
					}
					
					$checkout_url = get_permalink($relatedID);
					
				} else {
					
					$checkout_url = wc_get_checkout_url();
					
				}
				
				WC()->session->set('is_wp_ultimo_cart', true);
				wp_redirect( $checkout_url );
				exit;			
	   }
		
		
		/**
		 * Redirect to Cart
		*/	
		public function wu_redirect_to_cart_on_integration($url, $type) {
			
			if ($type == 'process-integration') {
				return $this->wu_get_create_subscription_cart_url();
			}
			return $url;

	    }


	   /**
		 * Check nonce
	   */	    
       public function wu_get_create_subscription_cart_url() {

		return wp_nonce_url( network_site_url(), 'wu-woo-create-cart', 'wu-code') . '&wu-woo-action=wu_create_cart';

	   }
		
	  
	  
	   /**
		 * Process subscription
	   */
	   public function wu_process_subscription_created($subscription, $order, $recurring_cart) {
			
			$wu_subscription = wu_get_subscription( $order->get_user_id() );
			if( isset($wu_subscription) && empty($wu_subscription)) {
				return;
			}
			$plan = $wu_subscription->get_plan();
			foreach ($subscription->get_items() as $item) {
			  $product = wc_get_product( $item->get_product_id() );
			}
			// Add a simple note
			$subscription->add_order_note(__('Subscription created by WP Ultimo', 'wp-wu-woocommerce'));
			$order->add_order_note(__('Subscription created by WP Ultimo', 'wp-wu-woocommerce'));

			// ultimo order
			update_post_meta($subscription->get_id(), '_is_wu_woo', 'yes');
			update_post_meta($order->get_id(), '_is_wu_woo', 'yes');

			// Add WC Subscription to the WU Subscription
			$wu_subscription->set_meta('_wu_woo_subscription_id', $subscription->get_id());
			
			$message = sprintf(__('Payment for the plan %s using %s as the processor.', 'wp-wu-woocommerce'), $plan->title, $this->wu_gateway_title());
			WU_Transactions::add_transaction($order->get_user_id(), $order->get_id(), 'pending', $order->get_total(), $this->id, $message, false, $order->get_total());
	   }
   
	
	
	   /**
		 * Create transction when plan renewed
	   */
	   public function wu_create_transaction_on_renew($order, $subscription) {
		   
			$message = sprintf(__('Payment for the plan %s using %s as the processor.', 'wp-wu-woocommerce'), $plan->title, $this->wu_gateway_title());
			// Log Transaction
			WU_Transactions::add_transaction($order->get_user_id(), $order->get_id(), 'pending', $order->get_total(), $this->id, $message, false, $order->get_total());
			return $order;
		}
   
   
   
	  /**
		 * Create transction when plan renewed
	  */
	  public function wu_cancel_woocommerce_subscription_on_integration_removal($subscription) {

		switch_to_blog(get_current_site()->blog_id);
		  $wc_subscription = wc_get_order( $subscription->get_meta('_wu_woo_subscription_id') );
		  if ($wc_subscription) {
			$wc_subscription->cancel_order();
		  }
		restore_current_blog();

	  }
	  
	  	
	   /**
		 * Change plan subscription
	   */
	   public function wu_change_plan_woocommerce_subscription($subscription, $current_plan, $new_plan, $new_freq) {

			switch_to_blog(get_current_site()->blog_id);
	
			  $wc_subscription = wc_get_order( $subscription->get_meta('_wu_woo_subscription_id') );
			  
			  if ($wc_subscription) {
				  
				$subscription_item = array_pop($wc_subscription->get_items());
				$pro_rate = $this->wu_new_calculate_pro_rate($subscription->get_price_after_coupon_code(), $subscription->freq, $new_plan->get_price( $new_freq ), $new_freq, $subscription->get_date('active_until', 'Y-m-d H:i:s', date_interval_create_from_date_string("- $subscription->freq months")));

				if ($pro_rate > 0) {
				  $name = $new_plan->title . ' - ' .__('Pro-rate (Changed Plans)', 'wp-wu-woocommerce');
				  $price = $pro_rate;
				} else {
				  $name = $new_plan->title;
				  $price = $new_plan->get_price( $new_freq );
				}
				$this->wu_set_wc_subscription( $wc_subscription, $subscription_item, $name, $price);
				WC()->payment_gateways();
				do_action( 'woocommerce_scheduled_subscription_payment', $wc_subscription->get_id() );
				
			  }
			restore_current_blog();
	   }
	   
	   
	   
	 /**
		 * Change plan subscription
	 */  
     public function wu_set_wc_subscription($wc_subscription, $subscription_item, $name, $price) {

		$subscription_item->set_total( $price );
		$subscription_item->set_name( $name );
		$subscription_item->save();

		$wc_subscription->calculate_totals();

	  }
	
	
	  /**
		* Do upgrade or downgrade of plans
	  */
	  public function change_plan() {

			// Just return in the wrong pages
			if (!isset($_POST['wu_action']) || $_POST['wu_action'] !== 'wu_change_plan') return;

			// Security check
			if (!wp_verify_nonce($_POST['_wpnonce'], 'wu-change-plan')) {
				WP_Ultimo()->add_message(__('You don\'t have permissions to perform this action.', 'wp-ultimo'), 'error');
				return;
			}

			if (!isset($_POST['plan_id'])) {
				WP_Ultimo()->add_message(__('You need to select a valid plan to change to.', 'wp-ultimo'), 'error');
				return;
			}

			// Check frequency
			if (!isset($_POST['plan_freq']) || !$this->check_frequency($_POST['plan_freq'])) {
				WP_Ultimo()->add_message(__('You need to select a valid frequency to change to.', 'wp-ultimo'), 'error');
				return;
			}

			// Get Plans - Current and new one
			$current_plan = $this->plan;
			$new_plan     = new WU_Plan((int) $_POST['plan_id']);

			$new_price = $new_plan->{"price_".$_POST['plan_freq']};
			$new_freq  = (int) $_POST['plan_freq'];

			// var_dump($new_freq); die; 

			if (!$new_plan->id) {
				WP_Ultimo()->add_message(__('You need to select a valid plan to change to.', 'wp-ultimo'), 'error');
				return;
			}

			/**
			 * Refund the last transaction and create a new one
			 * @since 1.5.0
			 */
			$credit = $this->subscription->calculate_credit();
			
			$this->subscription->set_credit($credit);

			// We need to take the current subscription time out
			$this->subscription->withdraw(); 
			
			if ($new_plan->free) {
				
				// Set new plan
				$this->subscription->plan_id            = $new_plan->id;
				$this->subscription->freq               = 1;
				$this->subscription->price              = 0;
				$this->subscription->integration_status = false;

				$this->subscription->set_last_plan_change();
				$this->subscription->save();

				$this->subscription->extend();

				// Hooks, passing new plan
				do_action('wu_subscription_change_plan', $this->subscription, $new_plan, $current_plan);
				
				// Redirect to success page
				wp_redirect(WU_Gateway::get_url('plan-changed'));
				exit;

			}

			// Update our subscription object now
			$this->subscription->plan_id            = $new_plan->id;
			$this->subscription->freq               = $new_freq;
			$this->subscription->price              = $new_price;
			$this->subscription->integration_status = true;
			
			$this->subscription->set_last_plan_change();
			$this->subscription->save();
			$this->subscription->extend();

			/**
			 * Price to pay now, with the new plan
			 */
			$price_to_pay_now = $this->subscription->get_outstanding_amount();

			/**
			 * Recreate the invoice
			 */
			$message = sprintf(__('Payment for the plan %s.', 'wp-ultimo'), $new_plan->title) .' '. $this->subscription->get_formatted_invoice_lines();
			

			/**
			 * Sets the credit if the content is negative; and sets the price to zero.
			 */
			if ($price_to_pay_now > 0) {

				$transaction_type = 'pending';

				$price = $price_to_pay_now;

				$paid = false;

				$this->subscription->set_credit(0);

			} else {

				$transaction_type = 'payment';

				$price = 0;

				$paid = true;

				$this->subscription->set_credit(abs($price_to_pay_now));

				$this->subscription->extend();

			} // end if;

			// Generate Random ID
			$transaction_id = uniqid();

			// Log Transaction and the results
			WU_Transactions::add_transaction($this->subscription->user_id, $transaction_id, $transaction_type, $price, $this->id, $message, false, $this->subscription->get_price_after_coupon_code());

			/**
			 * @since  1.2.0 Send the invoice as an attachment
			 */
			$invoice     = $this->generate_invoice($this->id, $this->subscription, $message, $this->subscription->get_price_after_coupon_code(), $paid);
			$attachments = $invoice ? array($invoice) : array();

			// Send receipt Mail
			WU_Mail()->send_template('payment_invoice_sent', $this->subscription->get_user_data('user_email'), array(
				'amount'           => wu_format_currency( $price ),
				'date'             => date(get_option('date_format')),
				'gateway'          => $this->title,
				'due_date'         => $this->subscription->get_date('due_date'),
				'user_name'        => $this->subscription->get_user_data('display_name')
			), $attachments);

			// Mark as sent
			$meta               = $this->subscription->meta;
			$meta->invoice_sent = true;
			$this->subscription->meta = $meta;

			$this->subscription->save();

			// Hooks, passing new plan
			do_action('wu_subscription_change_plan', $this->subscription, $new_plan, $current_plan);

			// Redirect to success page
			wp_redirect(WU_Gateway::get_url('plan-changed'));

			exit;
		}
		  
	   
	   /**
		* Process integration
	   */  
	   public function process_integration() {
		
		$this->create_integration($this->subscription, $this->plan, $this->freq, '', array());
		wp_redirect(WU_Gateway::get_url('success'));
		exit;

	   }
  
		
		
	  /**
		* Remove the integration
	  */
	  public function remove_integration($redirect = true, $subscription = false) {
		  
		if (!$subscription) {
		  $subscription = $this->subscription;
		}

		if ($subscription) {
		  $subscription->meta->subscription_id = '';
		  $subscription->integration_status    = false;
		  $subscription->save();
		}

		if ($redirect) {
			
		  wp_redirect(WU_Gateway::get_url('integration-removed'));
		  exit;
		}

	  }
	  
	
	  /**
		* Refund process
	   */
	   public function wu_process_refund() {

			if (!current_user_can('manage_network')) {
			  die(json_encode(array(
				'status'  => false,
				'message' => __('You don\'t have permissions to perform that action.', 'wp-wu-woocommerce'),
			  )));
			}
			
			if (!isset($_GET['transaction_id'])) {
			   die(json_encode(array(
				 'status'  => false,
				 'message' => __('A valid transaction id is necessary.', 'wp-wu-woocommerce'),
			   )));
			}

			if (!isset($_GET['value']) || !is_numeric($_GET['value'])) {
			   die(json_encode(array(
				 'status'  => false,
				 'message' => __('A valid amount is necessary.', 'wp-wu-woocommerce'),
			   )));
			}

			$transaction_id = $_GET['transaction_id'];
			$transaction = WU_Transactions::get_transaction($transaction_id);

			if (!$transaction) {
			   die(json_encode(array(
				 'status'  => false,
				 'message' => __('Transaction not found.', 'wp-wu-woocommerce'),
			   )));
			}

			$subscription = wu_get_subscription($transaction->user_id);

			if (!$subscription) {
			   die(json_encode(array(
				 'status'  => false,
				 'message' => __('Subscription not found.', 'wp-wu-woocommerce'),
			   )));
			}
			
			$value = $_GET['value'];
			$refund = $this->wu_refund_woocommerce_order($transaction->reference_id, $value);

			if (!is_wp_error($refund)) {

			  WU_Logger::add('gateway-'.$this->id, sprintf(__('User ID: %s - WooCommerce Payment "%s" received: You refunded the payment with %s.', 'wp-wu-woocommerce'), $transaction->user_id, $transaction->reference_id, wu_format_currency($value)) . $transaction->reference_id);

			  $message = sprintf(__('A refund was issued to your account. Payment reference %s.', 'wp-wu-woocommerce'), $transaction->reference_id);

			  // Log Transaction and the results
			  WU_Transactions::add_transaction($transaction->user_id, $transaction->reference_id, 'refund', $value, $this->id, $message);

			  // Send refund Mail
			  WU_Mail()->send_template('refund_issued', $subscription->get_user_data('user_email'), array( 
				'amount'           => wu_format_currency($value),
				'date'             => date(get_option('date_format')),
				'gateway'          => $this->get_title(),
				'new_active_until' => $subscription->get_date('active_until'),
				'user_name'        => $subscription->get_user_data('display_name')
			  ));

			  do_action('wp_ultimo_payment_refunded', $transaction->user_id, $this->id, $value);
			  die(json_encode(array(
				  'status'  => true,
				  'message' => __('Refund issued successfully. It should appear on this panel shortly.', 'wp-wu-woocommerce'),
			  )));

			} else {
			  die(json_encode(array(
				'status'  => false,
				'message' => sprintf(__('We were not able to refund the WooCommerce Order: %s', 'wp-wu-woocommerce'), $refund->get_error_message()),
			  )));
			}

		}

	
	
	  /**
	   * Refunds a WooCommerce Order
	   */
	   public function wu_refund_woocommerce_order($order_id, $amount, $refund_reason = '') {

			$order = wc_get_order($order_id);
			if (!is_a($order, 'WC_Order')) {
			  return new WP_Error('wc-order', __( 'Provided ID is not a WC Order', 'wp-wu-woocommerce'));
			}
			if ('refunded' == $order->get_status()) {
			  return new WP_Error('wc-order', __( 'Order has been already refunded', 'wp-wu-woocommerce'));
			}

			// Get Items
			$order_items   = $order->get_items();
			// Refund Amount
			$refund_amount = 0;
			// Prepare line items which we are refunding
			$line_items = array();
			
			if ($order_items) {

			  foreach($order_items as $item_id => $item) {

				$tax_data = $item_meta['_line_tax_data'];

				$refund_tax = 0;

				if (is_array($tax_data[0])) {
				  $refund_tax = array_map( 'wc_format_decimal', $tax_data[0] );
				}

				$refund_amount = wc_format_decimal($refund_amount) + wc_format_decimal($item_meta['_line_total'][0]);

				$line_items[$item_id] = array(
				  'qty'          => $item_meta['_qty'][0],
				  'refund_total' => wc_format_decimal($item_meta['_line_total'][0]),
				  'refund_tax'   =>  $refund_tax
				);

			  }
			}

			if ($amount == $refund_amount) {
			  $final_refund_amount = $refund_amount;
			  $line_items          = $line_items;
			} else {
			  $final_refund_amount = $amount;
			  $line_items          = array();
			}
			$refund = wc_create_refund(array(
			  'amount'         => $final_refund_amount,
			  'reason'         => $refund_reason,
			  'order_id'       => $order_id,
			  'line_items'     => $line_items,
			  'refund_payment' => true
			));

			return $refund;
	    } 
  
	   
	  /**
		 * Change plan subscription
	  */
	  public function wu_add_link_to_back_end($text, $order) {
				
				$is_wp_ultimo_order = get_post_meta($order->get_id(), '_is_wu_woo', true) == 'yes';
				$default_site = get_active_blog_for_user( get_current_user_id() );
			 
				if ($default_site && $is_wp_ultimo_order) {
					
				  switch_to_blog( $default_site->blog_id );
					$panel_url = $this->wu_get_success_url();
				  restore_current_blog();

				  $text .= '<br><br>' . sprintf('<a class="button button-primary" href="%s">%s</a>', $panel_url, __('Goto Site\'s Dashboard &rarr;', 'wu-wc'));
				}

				return $text;
	   }
	
	
	  /**
		 * Get success URL
	  */	
      public function wu_get_success_url() {

		$security_code = wp_create_nonce('wu_woo_gateway_page');
		return admin_url(sprintf('admin.php?page=wu-my-account&action=%s&code=%s&gateway=%s', 'success', $security_code, $this->id));

	   }
	   
	 
	 
	   /**
		 * Calculate pro Rate
	   */
       public function wu_new_calculate_pro_rate($old_price, $old_freq, $new_price, $new_freq, $start_date) {

		$end_date_time = new DateTime($start_date);
		$end_date_time->add( date_interval_create_from_date_string("+ $new_freq months") );

		$old_end_date_time = new DateTime($start_date);
		$old_end_date_time->add( date_interval_create_from_date_string("+ $old_freq months") );

		$now = WU_Subscription::get_now();

		$days_in_the_new_subscription  = $this->wu_get_difference_in_days( $start_date, $end_date_time->format('Y-m-d H:i:s') );
		$days_in_the_old_subscription  = $this->wu_get_difference_in_days( $start_date, $old_end_date_time->format('Y-m-d H:i:s') );

		$days_until_the_end_of_the_new_subscription = $this->wu_get_difference_in_days( $now->format('Y-m-d H:i:s'), $end_date_time->format('Y-m-d H:i:s') );
		
		$days_from_the_start_until_now = $this->wu_get_difference_in_days( $start_date, $now->format('Y-m-d H:i:s') );
		$days_until_the_end_of_the_old_subscription = $this->wu_get_difference_in_days( $start_date, $old_end_date_time->format('Y-m-d H:i:s') );
		
		$credit = $old_price - ( $old_price * ( $days_from_the_start_until_now / $days_in_the_old_subscription ) );

		return ( ( $days_until_the_end_of_the_new_subscription / $days_in_the_new_subscription ) * $new_price ) - $credit;

	  }
	
	
	
	   /**
		 * get days difference
	   */
	  public function wu_get_difference_in_days($start_date, $end_date) {

		$start_date_time = new DateTime($start_date);
		$end_date_time   = new DateTime($end_date);

		return $start_date_time->diff( $end_date_time )->days;

	   }
  
	  /**
		 * Redirect to checkout page
	  */
	   public function wu_redirect_to_account_page_after_checkout($result, $order_id) {

			$order = wc_get_order($order_id);

			$is_wp_ultimo_order = get_post_meta($order_id, '_is_wp_ultimo', true) == 'yes';

			$default_site = get_active_blog_for_user( get_current_user_id() );
		 
			if ($default_site && $is_wp_ultimo_order) {

			  switch_to_blog( $default_site->blog_id );

				$result['redirect'] = $this->wu_get_success_url();

			  restore_current_blog();

			} // end if;

			return $result;

	   } 
	   
	   
	   
	  /**
		 * Woocommerce change product name on Order, Email & thank you
	  */
	   public function wu_change_product_name_on_orders( $name) {
		   
		    if( ! is_product() ) return $name;
				 if (strpos($name, ':') !== false) {
						$org_name = explode(':',$name); 
						$new_name=$org_name[0];				  
				  } else {
						$new_name = $name;
				  }
			return $name;
	   }
	   
	   
	   
	   
	  /**
		 * Woocommerce change product name on Cart & Checkout
	  */
	   public function wu_change_product_name_on_cartcheckout( $cart) {
		   
		     if ( is_admin() && ! defined( 'DOING_AJAX' ) )
				return;
		     if ( did_action( 'woocommerce_before_calculate_totals' ) >= 2 )
				return;
			 // Loop through cart items
			 foreach ( $cart->get_cart() as $cart_item ) {

				$product = $cart_item['data'];
				$original_name = method_exists( $product, 'get_name' ) ? $product->get_name() : $product->post->post_title;				
				    if (strpos($original_name, ':') !== false) {
						$org_name = explode(':',$original_name); 
						$new_name=$org_name[0];				  
					} else {
						$new_name = $original_name;
					}
				if( method_exists( $product, 'set_name' ) )
					$product->set_name( $new_name );
				else
					$product->post->post_title = $new_name;
			 }
	   }

	   
	   
  
	  /**
		 * Woocommerce Gateway Settings
	  */
	  public function settings() {
		  
		   if(is_network_admin()){

		  	$license_key = get_site_option('woosubscription_license_key');
		  	
		  	//auto updater start
				Blitz_run_updater("https://waas-pro.com/index.php", 'blitz-wpultimo-cartflows-integration', 'blitz-wpultimo-cartflows-integration/bz-wp-ultimo-woosubscription-integration.php','1.0.10',$license_key,self::$WU_WCS_SL_PRODUCT_ID_WPU);
			//auto updater end
			
			//~ $responsecheck = $this->wu_woosubscription_license_check($license_key,'status-check');
			//~ if (!isset($_COOKIE['wucartflow_license_cookie'])) {
				
				//~ $responsecheck = $this->wu_woosubscription_license_check($license_key,'status-check');
								
				//~ if ($responsecheck[0]==1) {
					//~ setcookie('wucartflow_license_cookie', 'active', strtotime('+7 day'));
					//~ $responsecheck[0] = 1;
				//~ } else {
					//~ $responsecheck[0] = 0;
				//~ }
			//~ } else {
					//~ $responsecheck[0] = 1;
			//~ }
			
			if( $license_key != '') {	
				$validLic = $this->wuflows_validLicense($license_key);
								
				if (!$validLic) {
							$responsecheck[0] = 0;
				}else{
							$responsecheck[0] = 1;
				}
			}
				
			
			if($responsecheck[0]){
				
				
				$default_settings_requirements = array('active_gateway[wuwoocommerce]' => true);

				// Defines this gateway settings field
				$default_settings = array(

				  'wu_woo_title' => array(
					'title'                       => __('Name', 'wp-wu-woocommerce'),
					'desc'                        => __('Select a display name to this particular Integration. The name you choose will be use when this option is presented to the user.', 'wp-wu-woocommerce'),
					'type'                        => 'text',
					'placeholder'                 => __('Integration Name', 'wp-wu-woocommerce'),
					'default'                     => __('Integration Name', 'wp-wu-woocommerce'),
					'require'                     => $default_settings_requirements,
				  ),

				  'wu_woo_button_label' => array(
					'title'                       => __('Button Text', 'wp-wu-woocommerce'),
					'desc'                        => __('Select the label to be used on the Integration button for this option.', 'wp-wu-woocommerce'),
					'type'                        => 'text',
					'placeholder'                 => __('Sign Up', 'wp-wu-woocommerce'),
					'default'                     => __('Sign Up', 'wp-wu-woocommerce'),
					'require'                     => $default_settings_requirements,
				  ),

				);

				return $default_settings;
			
			} else {
				
			    $styles = '<script type="text/javascript"> jQuery(document).ready(function() { jQuery("input#multiselect-wuwoocommerce").parent().parent().css("display","none"); }); </script>';

				  add_action('admin_footer',
                   function() use ( $styles ) {
                       $this->applygatewayScripts( $styles ); });
				

			}
		 }
	
		} // end settings;
		
		
		
		
		/**
		* Apply style for options of site owner end
		*/
		public function applygatewayScripts( $args ) {
			echo $args;
		}
  

		
		
	}
/**
 * Register the gateway
 */
wu_register_gateway('wuwoocommerce', __('WooCommerce', 'wp-wu-woocommerce'), __('This integartion method loads enabled woocommerce payment method!', 'wp-wu-woocommerce'), 'WU_WC_Subscriptions');
