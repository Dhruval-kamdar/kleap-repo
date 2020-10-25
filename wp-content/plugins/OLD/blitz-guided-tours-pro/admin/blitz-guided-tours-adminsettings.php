<?php 
namespace BZ_SGN_WZ\Admin;

require_once(BZ_SGN_WZ_BASE_DIR . 'lmgr/blitz-guided-tours-licencemanager.php');
require_once(BZ_SGN_WZ_BASE_DIR . 'lmgr/AutoUpdater.php');

class BZ_TourWizardadminSettings{

		public $updated = false;
		public $lmgr;
		public $pageslug = 'bztourwizardsettings';
		
		
		
		public function __construct($prodid) {
			
			$this->lmgr = new License\BZ_TourWizardLicenceManager();
			$this->lmgr->init($prodid);
		}



		public function bz_tour_wizard_valid_license() {
			return true;
			//~ if (!isset($_COOKIE['gtp_license_cookie'])) {
				//~ $lic = $this->lmgr->bz_tour_wizard_license_status();
							
				//~ if ($lic[0]==1) {
					//~ setcookie('gtp_license_cookie', 'active', strtotime('+7 day'));
					//~ return true;
				//~ } else {
					//~ return false;
				//~ }
			//~ } else {
				//~ return true;
			//~ }
			
			
			$currentTime=time(); //current Time
			$gtp_license = $this->getOption('gtp_license');
			
			if (isset($gtp_license) && $gtp_license == '' ) {
				
				$lic = $this->lmgr->bz_tour_wizard_license_status();
				if ($lic[0]==1) {
					$this->updateOption('gtp_license',$currentTime);
					return true;
				} else {
					return false;
				}
				
			} else if (isset($gtp_license) && $gtp_license != '') {
				$rr = $currentTime - $gtp_license;
				$hourdiff = round(($rr)/3600, 1);
				if( $hourdiff >= 24 ) {
						$lic = $this->lmgr->bz_tour_wizard_license_status();
					if ($lic[0]==1) {
						$this->updateOption('gtp_license',$currentTime);
						$this->updateOption('gtp_license_expired','');
						return true;
					} else {
						$this->updateOption('gtp_license',$currentTime);
						$this->updateOption('gtp_license_expired',1);
						return false;
					}					
				} else {
					$gtp_license_expired = $this->getOption('gtp_license_expired');
					if (isset($gtp_license_expired) && $gtp_license_expired != '' ) {
						return false;
					} else {
						$this->updateOption('gtp_license_expired','');
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
		
		



		public function bz_tour_wizard_license_settings_page(){

			add_action('admin_init', array($this,'bz_tour_wizard_update'));
				
			if ( is_multisite() ) {
				add_submenu_page(
					'settings.php', //options-general for single site
					__('Guided Tours Pro', 'my-plugin-domain'),
					__('Guided Tours Pro'),
					'manage_options',
					$this->pageslug,
					array($this, 'bz_tour_wizard_admin_screen')
				);	        
			} else {
				add_submenu_page(
					'options-general.php', //options-general for single site
					__('Guided Tours Pro', 'my-plugin-domain'),
					__('Guided Tours Pro'),
					'manage_options',
					$this->pageslug,
					array($this, 'bz_tour_wizard_admin_screen')
				);	        
			}
		}



		public function bz_tour_wizard_admin_screen() {		
			
		?>

		<div class="wrap">


		    <h2><?php _e('Guided Tours Pro', 'bz-tour-wizard-domain'); ?></h2>

			 <?php if ( $this->updated ) : ?>
		        <div class="updated notice is-dismissible">
		            <p><?php _e('Settings updated successfully!', 'bz-tour-wizard-domain'); ?></p>
		        </div>
		    <?php endif; ?>

			<?php 				
				$this->lmgr->bz_tour_wizard_licenseBlock();
			?>

			</div>
		<?php 
		}



		public function bz_tour_wizard_update() {
			
			if ( isset($_POST['submit']) ) {
				return $this->bz_tour_wizard_updateSettings();
			}
		}



		public function bz_tour_wizard_updateSettings() {
			
			$settings = array();

			if ( isset($_POST['apiKey'])) {
			    $settings['apiKey'] = esc_attr($_POST['apiKey']);
			}

			if ( $settings ) {
			    // update new settings
			    update_site_option('bz_tour_wizard_config', $settings);
			} else {
			    // empty settings, revert back to default
			    delete_site_option('bz_tour_wizard_config');
			}

			$this->updated = true;			
		}



		/**
		* Updates settings
		*
		* @param $setting string optional setting name
		*/

		public function bz_tour_wizard_getSettings($setting='')	{
			
			global $my_plugin_settings;

			if ( isset($my_plugin_settings) ) {
				if ( $setting ) {
					return isset($my_plugin_settings[$setting]) ? $my_plugin_settings[$setting] : null;
				}
				return $my_plugin_settings;
			}

			$my_plugin_settings = wp_parse_args(get_site_option('bz_tour_wizard_config'), array(
				'apiKey'=>null,
			));

			if ( $setting ) {
				return isset($my_plugin_settings[$setting]) ? $my_plugin_settings[$setting] : null;
			}
			return $my_plugin_settings;
		}


} 
