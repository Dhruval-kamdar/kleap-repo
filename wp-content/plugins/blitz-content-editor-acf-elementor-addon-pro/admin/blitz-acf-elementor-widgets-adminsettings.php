<?php 

namespace BZ_ACF_WZ\Admin;

require_once(BZ_ACF_WZ_BASE_DIR . 'lmgr/blitz-acf-elementor-widgets-licencemanager.php');
require_once(BZ_ACF_WZ_BASE_DIR . 'lmgr/AutoUpdater.php');

class BZ_ACFWidgetadminSettings{

		public $updated = false;
		public $lmgr;
		public $pageslug = 'bzacfelementorwidgetssettings';
		
		public function __construct($prodid)
		{
			
			$this->lmgr = new License\BZ_ACFWidgetLicenceManager();
			
			$this->lmgr->init($prodid);
		}

		public function bz_acf_wz_valid_license()
		{	return true;
			//~ if (!isset($_COOKIE['cep_elem_addon_license_cookie'])) {
				//~ $lic = $this->lmgr->bz_acf_wz_license_status();
							
				//~ if ($lic[0]==1) {
					//~ setcookie('cep_elem_addon_license_cookie', 'active', strtotime('+7 day'));
					//~ return true;
				//~ } else {
					//~ return false;
				//~ }
			//~ } else {
				//~ return true;
			//~ }	
			
			$currentTime=time(); //current Time
			$cep_elem_addon_license = $this->getOption('cep_elem_addon_license');
			
			if (isset($cep_elem_addon_license) && $cep_elem_addon_license == '' ) {
				
				$lic = $this->lmgr->bz_acf_wz_license_status();
				if ($lic[0]==1) {
					$this->updateOption('cep_elem_addon_license',$currentTime);
					return true;
				} else {
					return false;
				}
				
			} else if (isset($cep_elem_addon_license) && $cep_elem_addon_license != '') {
				$rr = $currentTime - $cep_elem_addon_license;
				$hourdiff = round(($rr)/3600, 1);
				if( $hourdiff >= 24 ) {
						$lic = $this->lmgr->bz_acf_wz_license_status();
					if ($lic[0]==1) {
						$this->updateOption('cep_elem_addon_license',$currentTime);
						$this->updateOption('cep_elem_addon_license_expired','');
						return true;
					} else {
						$this->updateOption('cep_elem_addon_license',$currentTime);
						$this->updateOption('cep_elem_addon_license_expired',1);
						return false;
					}					
				} else {
					$cep_elem_addon_license_expired = $this->getOption('cep_elem_addon_license_expired');
					if (isset($cep_elem_addon_license_expired) && $cep_elem_addon_license_expired != '' ) {
						return false;
					} else {
						$this->updateOption('cep_elem_addon_license_expired','');
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
		
		
		public function bz_acf_wz_license_settings_page(){

			add_action('admin_init', array($this,'bz_acf_wz_update'));
			add_submenu_page(
	            'settings.php', //options-general for single site
	            __('Content Editor/ACF Add-on', 'my-plugin-domain'),
	            __('Content Editor/ACF Add-on'),
	            'manage_options',
	            $this->pageslug,
	            array($this, 'bz_acf_wz_admin_screen')
	        );	        
		}

		public function bz_acf_wz_admin_screen()
		{		
		?>

		<div class="wrap">


		    <h2><?php _e('Content Editor/ACF Add-on Configuration', 'bz-acf-elementor-widgets-domain'); ?></h2>

			 <?php if ( $this->updated ) : ?>
		        <div class="updated notice is-dismissible">
		            <p><?php _e('Settings updated successfully!', 'bz-acf-elementor-widgets-domain'); ?></p>
		        </div>
		    <?php endif; ?>

			<?php 					
					//~ if (! $this->bz_acf_wz_valid_license())
					//~ {		
						$this->lmgr->bz_acf_wz_licenseBlock();
					//~ } 
			?>

			</div>
		<?php 
		}


		public function bz_acf_wz_update()
		{
			if ( isset($_POST['submit']) ) {
				return $this->bz_acf_wz_updateSettings();
			}
		}


		public function bz_acf_wz_updateSettings()
		{
			$settings = array();

			if ( isset($_POST['apiKey'])) {
			    $settings['apiKey'] = esc_attr($_POST['apiKey']);
			}

			if ( $settings ) {
			    // update new settings
			    update_site_option('bz_acf_elementor_widgets_config', $settings);
			} else {
			    // empty settings, revert back to default
			    delete_site_option('bz_acf_elementor_widgets_config');
			}

			$this->updated = true;			
		}

		/**
		* Updates settings
		*
		* @param $setting string optional setting name
		*/

		public function bz_acf_wz_getSettings($setting='')
		{
		global $my_plugin_settings;

		if ( isset($my_plugin_settings) ) {
		    if ( $setting ) {
		        return isset($my_plugin_settings[$setting]) ? $my_plugin_settings[$setting] : null;
		    }
		    return $my_plugin_settings;
		}

		$my_plugin_settings = wp_parse_args(get_site_option('bz_acf_elementor_widgets_config'), array(
		    'apiKey'=>null,
		));

		if ( $setting ) {
		    return isset($my_plugin_settings[$setting]) ? $my_plugin_settings[$setting] : null;
		}
		return $my_plugin_settings;
		}

} 
