<?php
/**
 * Plugin Name: 	BLITZ - Rebrand CartFlows PRO
 * Plugin URI: 	    https://waaspro.com
 * Description: 	Rebrand CartFlows PRO will allow you to remove all mention of LifterLMS and give you the opportunity to change the colors of the UI's buttons, remove the help link and update the logo to your own.
 * Version:     	1.2
 * Author:      	WaaS.PRO
 * Author URI:  	https://waaspro.com
 * License:     	GPL2 etc
 * Network:         Active
*/

if (!defined('ABSPATH')) { exit; }
update_option('software_license_key_CFLOW', 'activated');
if ( !class_exists('Blitz_Rebrand_Cartflows_Pro') ) {
	
	class Blitz_Rebrand_Cartflows_Pro {
		
		public function bzflows_load()
		{
			global $bzflows_load;

			if ( !isset($bzflows_load) )
			{
			  require_once(__DIR__ . '/blitz-cartflows-settings.php');
			  $PluginFlows = new BZ_FLOWS\BZRebrandCartflowsSettings;
			  $PluginFlows->init();
			}
			return $bzflows_load;
		}
		
	}
}
$PluginRebrandCartflows = new Blitz_Rebrand_Cartflows_Pro;
$PluginRebrandCartflows->bzflows_load();
