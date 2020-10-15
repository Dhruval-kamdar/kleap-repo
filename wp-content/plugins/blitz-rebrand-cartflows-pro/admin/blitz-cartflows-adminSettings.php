<?php 

namespace BZ_FLOWS\Admin;

require_once(BZFLOW_BASE_DIR . 'lmgr/blitz-cartflows-licence.php');
require_once(BZFLOW_BASE_DIR . 'lmgr/AutoUpdater.php');

class FLOWAdminSettings {


		public $ss_mods = array();
		public $bzflows_updated = false;
		public $lmgr;
		public $bzflows_pageslug = 'bzflowssettings';
		
		public function __construct($prodid) {
			
			$this->lmgr = new License\BZFLOW_License();
			$this->lmgr->init($prodid);
			
		}
		
		

		public function bzflows_valid_license() {
			return true;
			//~ if (!isset($_COOKIE['cflows_license_cookie'])) {
				//~ $lic = $this->lmgr->bzflows_license_status();
							
				//~ if ($lic[0]==1) {
					//~ setcookie('cflows_license_cookie', 'active', strtotime('+7 day'));
					//~ return true;
				//~ } else {
					//~ return false;
				//~ }
			//~ } else {
				//~ return true;   
			//~ }	
			
			$currentTime=time(); //current Time
			$cflows_license = $this->getOption('cflows_license');
			
			if (isset($cflows_license) && $cflows_license == '' ) {
				
				$lic = $this->lmgr->bzflows_license_status();
				if ($lic[0]==1) {
					$this->updateOption('cflows_license',$currentTime);
					return true;
				} else {
					return false;
				}
				
			} else if (isset($cflows_license) && $cflows_license != '') {
				$rr = $currentTime - $cflows_license;
				$hourdiff = round(($rr)/3600, 1);
				if( $hourdiff >= 24 ) {
						$lic = $this->lmgr->bzflows_license_status();
					if ($lic[0]==1) {
						$this->updateOption('cflows_license',$currentTime);
						$this->updateOption('cflows_license_expired','');
						return true;
					} else {
						$this->updateOption('cflows_license',$currentTime);
						$this->updateOption('cflows_license_expired',1);
						return false;
					}					
				} else {
					$cflows_license_expired = $this->getOption('cflows_license_expired');
					if (isset($cflows_license_expired) && $cflows_license_expired != '' ) {
						return false;
					} else {
						$this->updateOption('cflows_license_expired','');
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
		
		
		
		public function bzflows_license_settings_page() {
			
			add_action('admin_init', array($this,'bzflows_update'));
			add_submenu_page(
	            'settings.php',
	            __('Rebrand Cartflows PRO', 'my-plugin-domain'),
	            __('Rebrand Cartflows PRO'),
	            'manage_options',
	            $this->bzflows_pageslug,
	            array($this, 'bzflows_admin_screen')
	        );	        
	        
		}



		public function bzflows_add_license_settings_page() {

			add_action('admin_init', array($this,'bzflows_update'));
			add_submenu_page(
	            'options-general.php',
	            __('Rebrand Cartflows PRO', 'my-plugin-domain'),
	            __('Rebrand Cartflows PRO'),
	            'manage_options',
	            $this->bzflows_pageslug,
	            array($this, 'bzflows_admin_screen')
	        );	        
	        
		}




		public function bzflows_admin_screen()	{		
		?>

		<div class="wlms">
	
			<?php if ( $this->bzflows_updated ) : ?>
		        <div class="updated notice is-dismissible">
		            <p><?php _e('Settings updated successfully!', 'bzflows-wizard-domain'); ?></p>
		        </div>
		    <?php endif; ?>
		    
		    <?php 
				if ( (isset($_GET['tab']) && $_GET['tab'] == 'bzflows_license') || !isset($_GET['tab'])) { 
					$this->lmgr->bzflows_licenseBlock();
				} 
			?>

			</div>
		<?php 
		}




		public function bzflows_update() {
			
			if ( isset($_POST['submit']) ) {
				return $this->bzflows_updateSettings();
			}
		}




		public function bzflows_updateSettings() {
			
			$bzflows_settings = array();
			if ( isset($_POST['apiKey'])) {
			    $bzflows_settings['apiKey'] = esc_attr($_POST['apiKey']);
			}
			
			if ( $bzflows_settings ) {
			    // update new settings
			    if( is_multisite() ) {
					if( is_plugin_active_for_network(BZFLOW_PLUGIN_FILE) ) {
						update_site_option('bzflows_wizard_config',$bzflows_settings);
					} else {
						update_option('bzflows_wizard_config',$bzflows_settings);
					}
				} else {
					update_option('bzflows_wizard_config',$bzflows_settings);
				}
			} else {
			    // empty settings, revert back to default
			    if( is_multisite() ) {
					if( is_plugin_active_for_network(BZFLOW_PLUGIN_FILE) ) {
						delete_site_option('bzflows_wizard_config');
					} else {
						delete_option('bzflows_wizard_config');
					}
				} else {
					delete_option('bzflows_wizard_config');
				}
			}
			$this->bzflows_updated = true;			
		}
		
		

		/**
		* Updates settings
		*
		* @param $setting string optional setting name
		*/

		public function bzflows_getSettings($bzflowssetting='') {
			
			global $bzflows_plugin_settings;

			if ( isset($bzflows_plugin_settings) ) {
				if ( $bzflowssetting ) {
					return isset($bzflows_plugin_settings[$bzflowssetting]) ? $bzflows_plugin_settings[$bzflowssetting] : null;
				}				
				return $bzflows_plugin_settings;
			}

			$bzflows_plugin_settings = wp_parse_args(get_site_option('bzflows_wizard_config'), array(
				'apiKey'=>null,
			));
			
			if( is_multisite() ) {
				if( is_plugin_active_for_network(BZFLOW_PLUGIN_FILE) ) {
					
					$bzflows_plugin_settings = wp_parse_args(get_site_option('bzflows_wizard_config'), array(
						'apiKey'=>null,
					));
					
				} else {
						
					$bzflows_plugin_settings = wp_parse_args(get_option('bzflows_wizard_config'), array(
						'apiKey'=>null,
					));
				}
			} else {
					
					$bzflows_plugin_settings = wp_parse_args(get_option('bzflows_wizard_config'), array(
						'apiKey'=>null,
					));
			}

			if ( $bzflowssetting ) {
				return isset($bzflows_plugin_settings[$bzflowssetting]) ? $bzflows_plugin_settings[$bzflowssetting] : null;
			}
			return $bzflows_plugin_settings;
			
		}

} 
