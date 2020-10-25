<?php 

namespace BZFLUENT\Admin;

require_once(BZFLUENT_BASE_DIR . 'lmgr/blitz-fluentforms-licence.php');
require_once(BZFLUENT_BASE_DIR . 'lmgr/AutoUpdater.php');

class FluentAdminSettings {


		public $ss_mods = array();
		public $bzfluent_updated = false;
		public $lmgr;
		public $bzfluent_pageslug = 'bzfluentsettings';
		
		public function __construct($prodid) {
			
			$this->lmgr = new License\BZFLUENT_License();
			$this->lmgr->init($prodid);
			
		}
		
		

		public function bzfluent_valid_license() {
			return true;
			//~ if (!isset($_COOKIE['fluent_license_cookie'])) {
				//~ $lic = $this->lmgr->bzfluent_license_status();
							
				//~ if ($lic[0]==1) {
					//~ setcookie('fluent_license_cookie', 'active', strtotime('+7 day'));
					//~ return true;
				//~ } else {
					//~ return false;
				//~ }
			//~ } else {
				//~ return true;
			//~ }	
				
			$currentTime=time(); //current Time
			$fluent_license = $this->getOption('fluent_license');
			
			if (isset($fluent_license) && $fluent_license == '' ) {
				
				$lic = $this->lmgr->bzfluent_license_status();
				if ($lic[0]==1) {
					$this->updateOption('fluent_license',$currentTime);
					return true;
				} else {
					return false;
				}
				
			} else if (isset($fluent_license) && $fluent_license != '') {
				$rr = $currentTime - $fluent_license;
				$hourdiff = round(($rr)/3600, 1);
				if( $hourdiff >= 24 ) {
						$lic = $this->lmgr->bzfluent_license_status();
					if ($lic[0]==1) {
						$this->updateOption('fluent_license',$currentTime);
						$this->updateOption('fluent_license_expired','');
						return true;
					} else {
						$this->updateOption('fluent_license',$currentTime);
						$this->updateOption('fluent_license_expired',1);
						return false;
					}					
				} else {
					$fluent_license_expired = $this->getOption('fluent_license_expired');
					if (isset($fluent_license_expired) && $fluent_license_expired != '' ) {
						return false;
					} else {
						$this->updateOption('fluent_license_expired','');
						return true;
					}
				}
				
			} else {
				
				return true;
			}	
		}



	
		/**
		* get options
		*/
		
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
		
		
		
		/**
		* update options
		*/
		
		public function updateOption($key,$value) {
			if(is_multisite()){
				return  update_site_option($key,$value);
			}else{
				return update_option($key,$value);
			}
		}
		
		
		public function bzfluent_license_settings_page() {
			
			add_action('admin_init', array($this,'bzfluent_update'));
			add_submenu_page(
	            'settings.php',
	            __('Rebrand Fluent Forms PRO', 'my-plugin-domain'),
	            __('Rebrand Fluent Forms PRO'),
	            'manage_options',
	            $this->bzfluent_pageslug,
	            array($this, 'bzfluent_admin_screen')
	        );	        
	        
		}



		public function bzfluent_add_license_settings_page() {

			add_action('admin_init', array($this,'bzfluent_update'));
			add_submenu_page(
	            'options-general.php',
	            __('Rebrand Fluent Forms PRO', 'my-plugin-domain'),
	            __('Rebrand Fluent Forms PRO'),
	            'manage_options',
	            $this->bzfluent_pageslug,
	            array($this, 'bzfluent_admin_screen')
	        );	        
	        
		}




		public function bzfluent_admin_screen()	{		
		?>

		<div class="wlms">
	
			<?php if ( $this->bzfluent_updated ) : ?>
		        <div class="updated notice is-dismissible">
		            <p><?php _e('Settings updated successfully!', 'bzfluent-wizard-domain'); ?></p>
		        </div>
		    <?php endif; ?>
		    
		    <?php 
				if ( (isset($_GET['tab']) && $_GET['tab'] == 'bzfluent_license') || !isset($_GET['tab'])) { 
					$this->lmgr->bzfluent_licenseBlock();
				} 
			?>

			</div>
		<?php 
		}




		public function bzfluent_update() {
			
			if ( isset($_POST['submit']) ) {
				return $this->bzfluent_updateSettings();
			}
		}




		public function bzfluent_updateSettings() {
			
			$bzfluent_settings = array();
			if ( isset($_POST['apiKey'])) {
			    $bzfluent_settings['apiKey'] = esc_attr($_POST['apiKey']);
			}
			
			if ( $bzfluent_settings ) {
			    // update new settings
			    if( is_multisite() ) {
					if( is_plugin_active_for_network(BZFLUENT_PLUGIN_FILE) ) {
						update_site_option('bzfluent_wizard_config',$bzfluent_settings);
					} else {
						update_option('bzfluent_wizard_config',$bzfluent_settings);
					}
				} else {
					update_option('bzfluent_wizard_config',$bzfluent_settings);
				}
			} else {
			    // empty settings, revert back to default
			    if( is_multisite() ) {
					if( is_plugin_active_for_network(BZFLUENT_PLUGIN_FILE) ) {
						delete_site_option('bzfluent_wizard_config');
					} else {
						delete_option('bzfluent_wizard_config');
					}
				} else {
					delete_option('bzfluent_wizard_config');
				}
			}
			$this->bzfluent_updated = true;			
		}
		
		
		
		

		/**
		* Updates settings
		*
		* @param $setting string optional setting name
		*/

		/**
		* Updates settings
		*
		* @param $setting string optional setting name
		*/

		public function bzfluent_getSettings($bzfluentsetting='') {
			
			global $bzfluent_plugin_settings;

			if ( isset($bzfluent_plugin_settings) ) {
				if ( $bzfluentsetting ) {
					return isset($bzfluent_plugin_settings[$bzfluentsetting]) ? $bzfluent_plugin_settings[$bzfluentsetting] : null;
				}				
				return $bzfluent_plugin_settings;
			}

			$bzfluent_plugin_settings = wp_parse_args(get_site_option('bzfluent_wizard_config'), array(
				'apiKey'=>null,
			));
			
			if( is_multisite() ) {
				if( is_plugin_active_for_network(BZFLUENT_PLUGIN_FILE) ) {
					
					$bzfluent_plugin_settings = wp_parse_args(get_site_option('bzfluent_wizard_config'), array(
						'apiKey'=>null,
					));
					
				} else {
						
					$bzfluent_plugin_settings = wp_parse_args(get_option('bzfluent_wizard_config'), array(
						'apiKey'=>null,
					));
				}
			} else {
					
					$bzfluent_plugin_settings = wp_parse_args(get_option('bzfluent_wizard_config'), array(
						'apiKey'=>null,
					));
			}

			if ( $bzfluentsetting ) {
				return isset($bzfluent_plugin_settings[$bzfluentsetting]) ? $bzfluent_plugin_settings[$bzfluentsetting] : null;
			}
			return $bzfluent_plugin_settings;
			
		}

} 
