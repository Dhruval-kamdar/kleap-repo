<?php
if(!class_exists('AutoUpdateManagerGlobal')){

class AutoUpdateManagerGlobal {
	
			public $api_url,$product_unique_id,$licence_key,$plugin;
			private $slug;
			private $version;
 
			function __construct($api_url, $slug, $plugin,$version,$licence_key,$product_unique_id) {
				
				$this->api_url = $api_url;
				$this->slug = $slug;
				$this->plugin = $plugin;
				$this->version =   $version;
				$this->licence_key =   $licence_key;
				$this->product_unique_id =   $product_unique_id;
				
			}
 
 
 
			public function check_for_plugin_update($checked_data) {
				
				if (empty($checked_data->checked) || !isset($checked_data->checked[$this->plugin])) return $checked_data;
				$request_string = $this->prepare_request('plugin_update');
				if ($request_string === FALSE) return $checked_data;
 
				// Start checking for an update
 
				$request_uri = $this->api_url . '?' . http_build_query($request_string, '', '&');
				$data = wp_remote_get($request_uri);
				if (is_wp_error($data) || $data['response']['code'] != 200) return $checked_data;
				$response_block = json_decode($data['body']);
				if (!is_array($response_block) || count($response_block) < 1)
				{
					return $checked_data;
				}
				// retrieve the last message within the $response_block
				$response_block = $response_block[count($response_block) - 1];
				$response = isset($response_block->message) ? $response_block->message : '';
				if (is_object($response) && !empty($response)) // Feed the update data into WP updater
				{
					$response  =   $this->postprocess_response( $response );
					$checked_data->response[$this->plugin] = $response;
				}
				return $checked_data;
				
			}
 
 
 
			public function plugins_api_call($def, $action, $args) {
				
				if (!is_object($args) || !isset($args->slug) || $args->slug != $this->slug) return $def;

				// $args->package_type = $this->package_type;
 
				$request_string = $this->prepare_request($action, $args);					
				if ($request_string === FALSE) return new WP_Error('plugins_api_failed', __('An error occour when try to identify the pluguin.', 'apto') . '&lt;/p> &lt;p>&lt;a href=&quot;?&quot; onclick=&quot;document.location.reload(); return false;&quot;>' . __('Try again', 'apto') . '&lt;/a>');;
				$request_uri = $this->api_url . '?' . http_build_query($request_string, '', '&');
				$data = wp_remote_get($request_uri);
				if (is_wp_error($data) || $data['response']['code'] != 200) return new WP_Error('plugins_api_failed', __('An Unexpected HTTP Error occurred during the API request.', 'apto') . '&lt;/p> &lt;p>&lt;a href=&quot;?&quot; onclick=&quot;document.location.reload(); return false;&quot;>' . __('Try again', 'apto') . '&lt;/a>', $data->get_error_message());
				$response_block = json_decode($data['body']);
 
				// retrieve the last message within the $response_block

				$response_block = $response_block[count($response_block) - 1];
				$response = $response_block->message;
				if (is_object($response) && !empty($response)) // Feed the update data into WP updater
				{
					$response  =   $this->postprocess_response( $response );
					return $response;
				}
				
			}
 
 
 
			public function prepare_request($action, $args = array()) {
				
				global $wp_version;
				$protocol = (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off' || $_SERVER['SERVER_PORT'] == 443) ? "https://" : "http://";
				if(is_multisite()){
					$protoco =           str_replace($protocol, "", network_site_url());
				}else{
					$protoco =           str_replace($protocol, "", site_url());
				}
				$protoco =           str_replace("/", "", $protoco);
				return array(
					'woo_sl_action'         => $action,
					'product_unique_id'     => $this->product_unique_id,
					'licence_key'           => $this->licence_key,
					'domain'                => $protoco,
					'wp-version'            => $wp_version,
					'api_version'           => '1.0',
					'version'           => $this->version
				);
				
			}
			 
			 
			 
			private function postprocess_response( $response ) {
				
				//include slug and plugin data
				$response->slug    =   $this->slug;
				$response->plugin  =   $this->plugin;
					  
				//if sections are being set
				if ( isset ( $response->sections ) )
				$response->sections = (array)$response->sections;
					  
				//if banners are being set
				if ( isset ( $response->banners ) )
				$response->banners = (array)$response->banners;
						
				//if icons being set, convert to array
				if ( isset ( $response->icons ) )
				$response->icons    =   (array)$response->icons;
					  
				return $response;
					  
		}
			
	}
 
 
	function Blitz_run_updater($api_url, $slug, $plugin,$version,$licence_key,$product_unique_id) 	{

				$wp_plugin_auto_update = new AutoUpdateManagerGlobal($api_url, $slug, $plugin,$version,$licence_key,$product_unique_id);
 
				// Take over the update check
 
				add_filter('pre_set_site_transient_update_plugins', array(
					$wp_plugin_auto_update,
					'check_for_plugin_update'
				));
 
				// Take over the Plugin info screen
 
				add_filter('plugins_api', array(
					$wp_plugin_auto_update,
					'plugins_api_call'
				) , 10, 3);
	}
		//add_action('after_setup_theme', 'Blitz_run_updater');
}
