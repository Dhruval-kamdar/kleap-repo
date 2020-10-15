<?php
/*
Plugin Name: BLITZ - Site Analytics PRO
Plugin URI: https://waaspro.com
Description: Enables Site Analytics for your site with statistics inside WordPress admin panel. Single and multi site compatible!
Author: WaaS.PRO
Author URI: https://waaspro.com
Version: 1.9
License: GNU General Public License (Version 2 - GPLv2)
*/

/*
Copyright WaaS-Pro.com (https://waas-pro.com/)

This program is free software; you can redistribute it and/or modify
it under the terms of the GNU General Public License (Version 2 - GPLv2) as published by
the Free Software Foundation.

This program is distributed in the hope that it will be useful,
but WITHOUT ANY WARRANTY; without even the implied warranty of
MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
GNU General Public License for more details.

You should have received a copy of the GNU General Public License
along with this program; if not, write to the Free Software
Foundation, Inc., 59 Temple Place, Suite 330, Boston, MA  02111-1307  USA
*/

/**
 * Blitz_Site_Analytics_Async
 *
 * @package Site Analytics
 * @copyright WaaS-Pro.com {@link https://waas-pro.com/}
 * @author WaaS-Pro.com {@link https://waas-pro.com/}
 * @license GNU General Public License (Version 2 - GPLv2) {@link http://www.gnu.org/licenses/gpl-2.0.html}
 */

define( 'BLITZ_SITEANALYTICS_PLUGIN_DIR', plugin_dir_path( __FILE__ ) );
require_once(plugin_dir_path( __FILE__ ) . 'lmgr/LicenceManager.php');
require_once(plugin_dir_path( __FILE__ ) . 'lmgr/AutoUpdater.php');
update_option('software_license_key_BLT-SITE-ANALYTICS', 'activated');
class Blitz_Site_Analytics_Async {

    /** @var string $text_domain The text domain of the plugin */
    var $text_domain = 'ga_trans';
    /** @var string $plugin_dir The plugin directory path */
    var $plugin_dir;
    /** @var string $plugin_url The plugin directory URL */
    var $plugin_url;
    /** @var string $options_name The plugin options string */
    var $options_name = 'ga2_settings';
    /** @var array $settings The plugin site options */
    var $settings;
    /** @var array $settings The plugin network options */
    var $network_settings;
    /** @var array $settings The plugin network or site options depending on localization in admin page */
    var $current_settings;
    
    var $lmgr;
	
	
    /**
     * Constructor.
     */
    function __construct() {
		
		
			$this->lmgr = new LicenceManagerGlobal();
			$productId = 'BLT-SITE-ANALYTICS';
			$this->lmgr->init($productId,'Site Analytics PRO Licence','siteanalyticspro');
			if(is_multisite())
			{
				switch_to_blog(1);
				$license_key1 = get_site_option('software_license_key_' . $productId);
				restore_current_blog();
			}
			else
			{
				$license_key1 = get_option('software_license_key_' . $productId);
			}
			
			//auto updater start
				Blitz_run_updater("https://waas-pro.com/index.php", 'blitz-site-analytics-pro', 'blitz-site-analytics-pro/blitz-site-analytics-async.php','1.9',$license_key1,$productId);
			//auto updater end
			
			//~ $responsecheck = $lmgr->license_check($license_key1,'status-check');
			//~ if (!isset($_COOKIE['sap_license_cookie'])) {
			
				//~ $responsecheck = $lmgr->license_check($license_key1,'status-check');
									
				//~ if ($responsecheck[0]==1) {
					//~ setcookie('sap_license_cookie', 'active', strtotime('+7 day'));
					//~ $responsecheck[0] = 1;
				//~ } else {
					//~ $responsecheck[0] = 0;
				//~ }
			//~ } else {
					//~ $responsecheck[0] = 1;
			//~ }
		
		
			if( $license_key1 != '') {	
					$validLic = $this->siteanaly_validLicense($license_key1);
							
					if (!$validLic) {
						$responsecheck[0] = 0;
					}else{
						$responsecheck[0] = 1;
					}
			}
				
			
			if ($responsecheck[0]==1) 
			{
				// Finally: Run our Plugin
				add_action('init', array(&$this, 'blitzplugin'), 0);
				 $this->init_vars();
			}
    }

 
    /**
     * Initiate plugin.
     *
     * @return void
     */
    function blitzplugin() {
		
		//Loads BLITZSITE dashboard
            global $blitzsite_notices;
            $blitzsite_notices[] = array( 'id'=> 51, 'name'=> 'Site Analytics', 'screens' => array( 'settings_page_site-analytics-network', 'settings_page_site-analytics' ) );
           
		
		  add_action( 'init', array( &$this, 'load_plugin_textdomain' ), 0 );
        add_action( 'init', array( &$this, 'enable_admin_tracking' ) );
        add_action( 'admin_init', array( &$this, 'handle_page_requests' ) );
        add_action( 'admin_menu', array( &$this, 'admin_menu' ) );
        add_action( 'network_admin_menu', array( &$this, 'network_admin_menu' ) );
        add_action( 'wp_head', array( &$this, 'tracking_code_output' ) );

        //add CSS
        add_action( 'admin_enqueue_scripts', array( &$this, 'admin_enqueue_scripts' ) );
     
       
       
        
    }

    /**
     * Initiate variables.
     *
     * @return void
     */
    function init_vars() {
        $this->settings = $this->get_options();
        $this->network_settings = $this->get_options(null, 'network');
        $this->current_settings = is_network_admin() ? $this->network_settings : $this->settings;
        if(is_multisite() && !is_network_admin() && (!isset($this->network_settings['track_settings']['capability_reports_overwrite']) || (isset($this->network_settings['track_settings']['capability_reports_overwrite']) && !$this->network_settings['track_settings']['capability_reports_overwrite']))) {
            $this->current_settings['track_settings']['minimum_capability_reports'] = isset($this->network_settings['track_settings']['minimum_capability_reports']) ? $this->network_settings['track_settings']['minimum_capability_reports'] : '';
            $this->current_settings['track_settings']['minimum_role_capability_reports'] = isset($this->network_settings['track_settings']['minimum_role_capability_reports']) ? $this->network_settings['track_settings']['minimum_role_capability_reports'] : '';
        }

        /* Set plugin directory path */
        $this->plugin_dir = BLITZ_SITEANALYTICS_PLUGIN_DIR;
        /* Set plugin directory URL */
        $this->plugin_url = plugin_dir_url(__FILE__);
    }

    /**
     * Add CSS
     *
     * @return void
     */
    function admin_enqueue_scripts($hook) {
        // Including CSS file
        wp_enqueue_style( 'wp-color-picker' );
        wp_enqueue_script( 'wp-color-picker' );
        if($hook == 'settings_page_site-analytics') {
			
            wp_register_style( 'BlitzSiteAnalyticsAsyncStyle', $this->plugin_url . 'blitz-site-analytics-async-files/ga-async.css', array(), 2 );
            wp_enqueue_style( 'BlitzSiteAnalyticsAsyncStyle' );
        }
    }

    /**
     * Loads the language file from the "languages" directory.
     *
     * @return void
     */
    function load_plugin_textdomain() {
        load_plugin_textdomain( $this->text_domain, null, dirname( plugin_basename( __FILE__ ) ) . '/blitz-site-analytics-async-files/languages/' );
    }

    /**
     * Add Site Analytics options page.
     *
     * @return void
     */
    function admin_menu() {
        /* If Supporter enabled but specific option disabled, disable menu */
        if (
            !is_super_admin()
            && !empty( $this->network_settings['track_settings']['supporter_only'] )
            && function_exists('is_pro_site')
            && !is_pro_site(get_current_blog_id(), $this->network_settings['track_settings']['supporter_only'])
            && apply_filters('ga_additional_block', true)
        ) {
            return;
        } else {
            add_submenu_page( 'options-general.php', 'Site Analytics', 'Site Analytics', 'manage_options', 'blitz-site-analytics', array( &$this, 'output_site_settings_page' ) );
        }
    }

	/**
	 * Add network admin menu
	 *
	 * @access public
	 * @return void
	 */
	function network_admin_menu() {
        add_submenu_page( 'settings.php', 'Site Analytics', 'Site Analytics', 'manage_network', 'blitz-site-analytics', array( &$this, 'output_network_settings_page' ) );
	}

    /**
     * Enable admin tracking.
     *
     * @return void
     */
    function enable_admin_tracking() {
		if ( !empty( $this->network_settings['track_settings']['track_admin'] ) )
            add_action( 'admin_head', array( &$this, 'tracking_code_output' ) );
    }

    /**
     * Site Analytics code output.
     *
     * @return void
     */
    function tracking_code_output() {
        if(is_preview() || wp_doing_ajax()) {
            return false;
        }

        $network_settings = isset( $this->network_settings['track_settings'] ) ? $this->network_settings['track_settings'] : array();
        $site_settings    = isset( $this->settings['track_settings'] ) ? $this->settings['track_settings'] : array();

        /* Unset tracking code if it matches the root site one */
		if ( isset( $network_settings['tracking_code'] )
			&& isset( $site_settings['tracking_code'] )
			&& $network_settings['tracking_code'] == $site_settings['tracking_code']
		) {
			unset( $site_settings['tracking_code'] );
		}

        if (
            ( isset( $network_settings['tracking_code'] ) && !empty( $network_settings['tracking_code'] ) ) ||
            ( !is_admin() && isset( $site_settings['tracking_code'] ) && !empty( $site_settings['tracking_code'] ) )
        ):

        if ( isset( $network_settings['anonymize_ip'] ) && $network_settings['anonymize_ip'] && isset( $network_settings['anonymize_ip_force'] ) && $network_settings['anonymize_ip_force'] ) {
            $site_settings['anonymize_ip'] = true;
        }
        ?>

            <script>
                (function(i,s,o,g,r,a,m){i['GoogleAnalyticsObject']=r;i[r]=i[r]||function(){
                (i[r].q=i[r].q||[]).push(arguments)},i[r].l=1*new Date();a=s.createElement(o),
                m=s.getElementsByTagName(o)[0];a.async=1;a.src=g;m.parentNode.insertBefore(a,m)
                })(window,document,'script','//www.google-analytics.com/analytics.js','gaplusu');

                function gaplus_track() {
                    <?php if ( isset( $network_settings['tracking_code'] ) && !empty( $network_settings['tracking_code'] ) ): ?>
                            gaplusu('create', '<?php echo $network_settings['tracking_code']; ?>', 'auto');
                        <?php if ( isset( $network_settings['anonymize_ip'] ) && $network_settings['anonymize_ip'] ): ?>
                            gaplusu('set', 'anonymizeIp', true);
                        <?php endif; ?>
                        <?php if ( isset( $network_settings['display_advertising'] ) && $network_settings['display_advertising'] ): ?>
                            gaplusu('require', 'displayfeatures');
                        <?php endif; ?>
                        <?php do_action('bl_ga_plus_network_tracking_code_add_vars', ''); ?>
                            gaplusu('send', 'pageview');
                    <?php endif; ?>

                    <?php if ( !is_admin() && isset( $site_settings['tracking_code'] ) && !empty( $site_settings['tracking_code'] ) ): ?>
                            gaplusu('create', '<?php echo $site_settings['tracking_code']; ?>', 'auto', {'name': 'single'});
                        <?php if ( isset($site_settings['anonymize_ip']) && !empty( $site_settings['anonymize_ip'] ) ): ?>
                            gaplusu('single.set', 'anonymizeIp', true);
                        <?php endif; ?>
                        <?php if ( $site_settings['display_advertising'] ): ?>
                            gaplusu('single.require', 'displayfeatures');
                        <?php endif; ?>
                            <?php do_action('bl_ga_plus_site_tracking_code_add_vars', 'b'); ?>
                            gaplusu('single.send', 'pageview');
                    <?php endif; ?>
                }

                <?php if(apply_filters('ga_load_tracking', true)) { ?>
                    gaplus_track();
                <?php } ?>

            </script>

		<?php
        endif;
    }

    /**
     * Update Site Analytics settings into DB.
     *
     * @return void
     */
    function handle_page_requests() {
        if ( isset( $_POST['submit'] ) ) {

			if ( isset( $_POST['_wpnonce'] ) && wp_verify_nonce( $_POST['_wpnonce'], 'bl_submit_settings_network' ) ) {
            //save network settings
                $this->save_options( array('track_settings' => $_POST), 'network' );
                //add_site_option(array('ga_pageview_color', 'ga_pageview_color' => $_POST),'network');
                $this->save_options( array('ga_pageview_color' => $_POST), 'network' );
                $this->save_options( array('ga_visits_color' => $_POST), 'network' );
                $this->save_options( array('ga_univisits_color' => $_POST), 'network' );

                wp_redirect( add_query_arg( array( 'page' => 'blitz-site-analytics', 'dmsg' => urlencode( __( 'Changes were saved!', $this->text_domain ) ) ), 'settings.php' ) );
                exit;
			}
			elseif ( isset( $_POST['_wpnonce'] ) && wp_verify_nonce( $_POST['_wpnonce'], 'bl_submit_settings' ) ) {
            //save settings

                $this->save_options( array('track_settings' => $_POST) );
                $this->save_options( array('ga_pageview_color' => $_POST) );
                $this->save_options( array('ga_visits_color' => $_POST) );
                $this->save_options( array('ga_univisits_color' => $_POST) );

                wp_redirect( add_query_arg( array( 'page' => 'blitz-site-analytics', 'dmsg' => urlencode( __( 'Changes were saved!', $this->text_domain ) ) ), 'options-general.php' ) );
                exit;
			}
        }

        if(function_exists('wp_add_privacy_policy_content')) {
            $privacy_text = __( "This website uses Site Analytics to track website traffic. Collected data is processed in such a way that visitors cannot be identified.", $this->text_domain );
            wp_add_privacy_policy_content('Site Analytics +', $privacy_text);
        }
    }

	/**
	 * Network settings page
	 *
	 * @access public
	 * @return void
	 */
	function output_network_settings_page() {
        /* Get Network settings */
        $this->output_site_settings_page( 'network' );
	}

    /**
     * Admin options page output
     *
     * @return void
     */
    function output_site_settings_page( $network = '' ) {
	    global $blitz_site_analytics_async_dashboard;
        $google_loggedin = isset($this->current_settings['google_login']['logged_in']) ? 1 : 0;
        /* analytics repot account */
        if($google_loggedin) {
            $accounts = $blitz_site_analytics_async_dashboard->get_accounts();
        }

        require_once( $this->plugin_dir . "blitz-site-analytics-async-files/page-settings.php" );
    }

    /**
     * Save plugin options.
     *
     * @param  array $params The $_POST array
     * @return void
     */
    function save_options( $params, $network = ''  ) {
        /* Remove unwanted parameters */
        unset( $params['_wpnonce'], $params['_wp_http_referer'], $params['submit'] );
        /* Update options by merging the old ones */

        if ( '' == $network )
            $options = get_option( $this->options_name );
        else
            $options = get_site_option( $this->options_name );

        if(!is_array($options))
            $options = array();

        $options = array_merge( $options, $params );

        if ( '' == $network )
            update_option( $this->options_name, $options );
        else
            update_site_option( $this->options_name, $options );
    }

    /**
     * Get plugin options.
     *
     * @param  string|NULL $key The key for that plugin option.
     * @return array $options Plugin options or empty array if no options are found
     */
    function get_options( $key = null, $network = '' ) {

        if ( '' == $network )
            $options = get_option( $this->options_name );
        else
            $options = get_site_option( $this->options_name );

        if(!is_array($options))
            $options = array();

        do_action('bl_ga_plus_before_return_options', $options, $network, $this->options_name);
		$options = apply_filters('ga_get_options', $options, $network, $this->options_name);

        /* Check if specific plugin option is requested and return it */
        if ( isset( $key ) && array_key_exists( $key, $options ) )
            return $options[$key];
        else
            return $options;
    }
    
    public function siteanaly_validLicense($license_key1) {
			
			$currentTime=time(); //current Time
			$siteanaly_license = $this->getOption('siteanaly_license');
			
			if (isset($siteanaly_license) && $siteanaly_license == '' ) {
				$lic = $this->lmgr->license_check($license_key1,'status-check');
				if ($lic[0]==1) {
					$this->updateOption('siteanaly_license',$currentTime);
					return true;
				} else {
					return false;
				}
				
			} else if (isset($siteanaly_license) && $siteanaly_license != '') {
				
				$rr = $currentTime - $siteanaly_license;
				$hourdiff = round(($rr)/3600, 1);
				if( $hourdiff >= 24 ) {
					$lic = $this->lmgr->license_check($license_key1,'status-check');
					if ($lic[0]==1) {
						$this->updateOption('siteanaly_license',$currentTime);
						$this->updateOption('siteanaly_license_expired','');
						return true;
					} else {
						$this->updateOption('siteanaly_license',$currentTime);
						$this->updateOption('siteanaly_license_expired',1);
						return false;
					}					
				} else {
					$siteanaly_license_expired = $this->getOption('siteanaly_license_expired');
					if (isset($siteanaly_license_expired) && $siteanaly_license_expired != '' ) {
						return false;
					} else {
						$this->updateOption('siteanaly_license_expired','');
						return true;
					}
				}
				
			} else {
				
				return true;
			}	
			
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
		
}

global $blitz_site_analytics_async;
$blitz_site_analytics_async = new Blitz_Site_Analytics_Async();

include_once 'blitz-site-analytics-async-files/class-blitz-site-analytics-async-dashboard.php';
