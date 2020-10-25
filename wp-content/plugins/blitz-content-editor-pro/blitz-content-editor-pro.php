<?php
/**
 * Plugin Name: 	BLITZ - Content Editor PRO
 * Plugin URI: 	    https://waaspro.com
 * Description: 	Content Editor PRO Plugin is for content settings of website.
 * Version:     	1.0.15
 * Author:      	WaaS.PRO
 * Author URI:  	https://waaspro.com
 * License:     	GPL2 etc
*/


if (!defined('ABSPATH')) {
  exit;
}

update_option('software_license_key_BLT-CNT-EDR', 'activated');
if (!function_exists('bootload_blitzcontenteditorsettings_pro'))
{
	
	function bootload_blitzcontenteditorsettings_pro()
	{
		include_once(__DIR__ . '/blitz-content-layout-settings.php');
		$Plugin = new Blitzcontent_EditorPro_Settings;
		$Plugin->init('astra');
	}
	
}

function contentpro_plugin_activate() {
	
	global $wpdb, $table_prefix;
	
	if ( ! function_exists( 'is_plugin_active_for_network' ) ) {
    include_once( ABSPATH . '/wp-admin/includes/plugin.php' );
	}
  
	require_once( ABSPATH . '/wp-admin/includes/upgrade.php' );
	if($wpdb->get_var( "show tables like ".$table_prefix."CatSite") != $table_prefix.'CatSite') 
	{
		$sql = "CREATE TABLE `".$table_prefix."CatSite". "` ( ";
		$sql .= "  `id` int(11) NOT NULL AUTO_INCREMENT, ";
		$sql .= "  `siteID` int(11) NULL, ";
		$sql .= "  `catID` text NULL, ";
		$sql .= "  `enableShort` int(11) NULL, ";
		$sql .= "  `enableRowsedit` int(11) NULL, ";
		$sql .= "  PRIMARY KEY (`id`) ";
		$sql .= "  ) ENGINE=MyISAM DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ; ";
		dbDelta($sql);
	}
	
	
	if ($wpdb->get_var('SHOW TABLES LIKE ' . $wpdb->prefix . 'bzce_settings') === $wpdb->prefix . 'bzce_settings') {
	} else {
		// create tours table
		$db_tableName = $wpdb->prefix . 'bzce_settings';
		if ($wpdb->get_var('SHOW TABLES LIKE '.$db_tableName) != $db_tableName) {
						
		if (!empty($wpdb->charset))
		$charset_collate = 'DEFAULT CHARACTER SET '.$wpdb->charset;
		if (!empty($wpdb->collate))
		$charset_collate .= ' COLLATE '.$wpdb->collate;

		$sql = "CREATE TABLE $db_tableName (
			id mediumint(9) NOT NULL AUTO_INCREMENT,
			siteid mediumint(9) NOT NULL,
			ceContent text NOT NULL,
			updated mediumint(9) NOT NULL,
			UNIQUE KEY bztsw_id (id)
			) $charset_collate;";
			dbDelta($sql);
		} 
	}

	
}

function plugin_deactivate() {
	
		//~ global $wpdb, $table_prefix;
	    //~ $sql = "DROP TABLE IF EXISTS ".$table_prefix.'CatSite';
	    //~ $wpdb->query($sql);
	    
}
		

register_activation_hook( __FILE__, 'contentpro_plugin_activate' );
register_deactivation_hook(  __FILE__, 'plugin_deactivate' );

bootload_blitzcontenteditorsettings_pro();
