<?php
/**
 * Plugin Name: 	BLITZ - Rebrand Fluent Forms PRO
 * Plugin URI: 	    https://waaspro.com
 * Description: 	Rebrand Fluent Forms PRO will allow you to remove all mention of Fluent Forms and give you the opportunity to change the colors of the UI's buttons, remove the help link and update the logo to your own.
 * Version:     	1.2
 * Author:      	WaaS.PRO
 * Author URI:  	https://waaspro.com
 * License:     	GPL2 etc
 * Network:         Active
*/

if (!defined('ABSPATH')) { exit; }
update_option('software_license_key_BZFF', 'activated');
if ( !class_exists('Blitz_Rebrand_FluentForms_Pro') ) {
	
	class Blitz_Rebrand_FluentForms_Pro {
		
		public function bzfluent_load()
		{
			global $bzfluent_load;

			if ( !isset($bzfluent_load) )
			{
			  require_once(__DIR__ . '/blitz-fluentforms-settings.php');
			  $PluginFluent = new BZFLUENT\BZRebrandFluentFormsSettings;
			  $PluginFluent->init();
			}
			return $bzfluent_load;
		}
		
	}
}
$PluginRebrandFluentForms = new Blitz_Rebrand_FluentForms_Pro;
$PluginRebrandFluentForms->bzfluent_load();
