<?php 

namespace BZ_RCP\Admin;

require_once(BZRCP_BASE_DIR . 'lmgr/blitz-rcp-licence.php');
require_once(BZRCP_BASE_DIR . 'lmgr/AutoUpdater.php');

class RCPAdminSettings {


		public $ss_mods = array();
		public $bzrcp_updated = false;
		public $lmgr;
		public $bzrcp_pageslug = 'bzrcpsettings';
		
		public function __construct($prodid) {
			
			$this->lmgr = new License\BZRCP_License();
			$this->lmgr->init($prodid);
			
		}
		
		

		public function bzrcp_valid_license() {
			return true;
			//~ if (!isset($_COOKIE['rcp_license_cookie'])) {
				//~ $lic = $this->lmgr->bzrcp_license_status();
							
				//~ if ($lic[0]==1) {
					//~ setcookie('rcp_license_cookie', 'active', strtotime('+7 day'));
					//~ return true;
				//~ } else {
					//~ return false;
				//~ }
			//~ } else {
				//~ return true;
			//~ }	
			
			$currentTime=time(); //current Time
			$rcp_license = $this->getOption('rcp_license');
			
			if (isset($rcp_license) && $rcp_license == '' ) {
				
				$lic = $this->lmgr->bzrcp_license_status();
				if ($lic[0]==1) {
					$this->updateOption('rcp_license',$currentTime);
					return true;
				} else {
					return false;
				}
				
			} else if (isset($rcp_license) && $rcp_license != '') {
				$rr = $currentTime - $rcp_license;
				$hourdiff = round(($rr)/3600, 1);
				if( $hourdiff >= 24 ) {
						$lic = $this->lmgr->bzrcp_license_status();
					if ($lic[0]==1) {
						$this->updateOption('rcp_license',$currentTime);
						$this->updateOption('rcp_license_expired','');
						return true;
					} else {
						$this->updateOption('rcp_license',$currentTime);
						$this->updateOption('rcp_license_expired',1);
						return false;
					}					
				} else {
					$rcp_license_expired = $this->getOption('rcp_license_expired');
					if (isset($rcp_license_expired) && $rcp_license_expired != '' ) {
						return false;
					} else {
						$this->updateOption('rcp_license_expired','');
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



		public function bzrcp_license_settings_page() {
			
			add_action('admin_init', array($this,'bzrcp_update'));
			add_submenu_page(
	            'settings.php',
	            __('Reduce Churn PRO', 'my-plugin-domain'),
	            __('Reduce Churn PRO'),
	            'manage_options',
	            $this->bzrcp_pageslug,
	            array($this, 'bzrcp_admin_screen')
	        );	        
	        
		}



		public function bzrcp_add_license_settings_page() {

			add_action('admin_init', array($this,'bzrcp_update'));
			add_submenu_page(
	            'options-general.php',
	            __('Reduce Churn PRO', 'my-plugin-domain'),
	            __('Reduce Churn PRO'),
	            'manage_options',
	            $this->bzrcp_pageslug,
	            array($this, 'bzrcp_admin_screen')
	        );	        
	        
		}




		public function bzrcp_admin_screen()	{		
		?>

		<div class="wrap">
			<h2><?php _e('Reduce Churn PRO Configuration', 'bzrcp-wizard-domain'); ?></h2>
			<h2 class="nav-tab-wrapper">
				<a href="?page=<?php _e($this->bzrcp_pageslug, 'bzrcp-wizard-domain');?>&tab=bzrcp_license" class="nav-tab">License</a>
			</h2>
			<?php if ( $this->bzrcp_updated ) : ?>
		        <div class="updated notice is-dismissible">
		            <p><?php _e('Settings updated successfully!', 'bzrcp-wizard-domain'); ?></p>
		        </div>
		    <?php endif; ?>
		    
		    <?php 
				if ( (isset($_GET['tab']) && $_GET['tab'] == 'bzrcp_license') || !isset($_GET['tab'])) { 
					$this->lmgr->bzrcp_licenseBlock();
				} 
			?>

			</div>
		<?php 
		}




		public function bzrcp_update() {
			
			if ( isset($_POST['submit']) ) {
				return $this->bzrcp_updateSettings();
			}
		}




		public function bzrcp_updateSettings() {
			
			$bzrcp_settings = array();
			if ( isset($_POST['apiKey'])) {
			    $bzrcp_settings['apiKey'] = esc_attr($_POST['apiKey']);
			}
			
			if ( $bzrcp_settings ) {
			    // update new settings
			    if( is_multisite() ) {
					if( is_plugin_active_for_network(BZRCP_PLUGIN_FILE) ) {
						update_site_option('bzrcp_wizard_config',$bzrcp_settings);
					} else {
						update_option('bzrcp_wizard_config',$bzrcp_settings);
					}
				} else {
					update_option('bzrcp_wizard_config',$bzrcp_settings);
				}
			} else {
			    // empty settings, revert back to default
			    if( is_multisite() ) {
					if( is_plugin_active_for_network(BZRCP_PLUGIN_FILE) ) {
						delete_site_option('bzrcp_wizard_config');
					} else {
						delete_option('bzrcp_wizard_config');
					}
				} else {
					delete_option('bzrcp_wizard_config');
				}
			}
			$this->bzrcp_updated = true;			
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

		public function bzrcp_getSettings($bzrcpsetting='') {
			
			global $bzrcp_plugin_settings;

			if ( isset($bzrcp_plugin_settings) ) {
				if ( $bzrcpsetting ) {
					return isset($bzrcp_plugin_settings[$bzrcpsetting]) ? $bzrcp_plugin_settings[$bzrcpsetting] : null;
				}				
				return $bzrcp_plugin_settings;
			}

			$bzrcp_plugin_settings = wp_parse_args(get_site_option('bzrcp_wizard_config'), array(
				'apiKey'=>null,
			));
			
			if( is_multisite() ) {
				if( is_plugin_active_for_network(BZRCP_PLUGIN_FILE) ) {
					
					$bzrcp_plugin_settings = wp_parse_args(get_site_option('bzrcp_wizard_config'), array(
						'apiKey'=>null,
					));
					
				} else {
						
					$bzrcp_plugin_settings = wp_parse_args(get_option('bzrcp_wizard_config'), array(
						'apiKey'=>null,
					));
				}
			} else {
					
					$bzrcp_plugin_settings = wp_parse_args(get_option('bzrcp_wizard_config'), array(
						'apiKey'=>null,
					));
			}

			if ( $bzrcpsetting ) {
				return isset($bzrcp_plugin_settings[$bzrcpsetting]) ? $bzrcp_plugin_settings[$bzrcpsetting] : null;
			}
			return $bzrcp_plugin_settings;
			
		}

} 
