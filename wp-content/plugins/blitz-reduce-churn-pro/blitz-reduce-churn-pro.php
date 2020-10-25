<?php
/**
 * Plugin Name: 	BLITZ - Reduce Churn PRO
 * Plugin URI: 	    https://waaspro.com
 * Description: 	Reduce Churn PRO introduces a feedback form with questions when some user cancels subscription in WP Ultimo.
 * Version:     	1.3
 * Author:      	WaaS.PRO
 * Author URI:  	https://waaspro.com
 * License:     	GPL2 etc
 * Network:         Active
*/

if (!defined('ABSPATH')) { exit; }
update_option('software_license_key_RCHP', 'activated');
register_activation_hook(__FILE__, 'bzrcp_activation' );   //Activation Hook
register_uninstall_hook(__FILE__, 'bzrcp_deleteTable');

if ( !class_exists('Blitz_Reduce_Churn_Pro') ) {
	
	class Blitz_Reduce_Churn_Pro {
		
		public function bzrcp_load()
		{
			global $bzrcp_load;

			if ( !isset($bzrcp_load) )
			{
			  require_once(__DIR__ . '/blitz-rcp-settings.php');
			  $PluginRCP = new BZ_RCP\BZReduceChurnSettings;
			  $PluginRCP->init();
			}
			return $bzrcp_load;
		}
		
	}
}
$PluginReduceChurn = new Blitz_Reduce_Churn_Pro;
$PluginReduceChurn->bzrcp_load();


/**
 * Creates table on plugin activation
*/
function bzrcp_activation() {
			
	global $wpdb;
	require_once(ABSPATH . '/wp-admin/includes/upgrade.php');
	
	
	// create Questions table
	$db_tableName1 = $wpdb->prefix . 'bzrcp_questions';
	if ($wpdb->get_var("SHOW TABLES LIKE '$db_tableName1'") != $db_tableName1) {
		
			if (!empty($wpdb->charset))
			$charset_collate = 'DEFAULT CHARACTER SET '.$wpdb->charset;
			if (!empty($wpdb->collate))
			$charset_collate .= ' COLLATE '.$wpdb->collate;

			$sql = "CREATE TABLE $db_tableName1 (
				id mediumint(9) NOT NULL AUTO_INCREMENT,
				question VARCHAR(255) NOT NULL,
				question_type VARCHAR(120) NOT NULL,
				question_options TEXT NOT NULL,
				question_order VARCHAR(120) NOT NULL,
				UNIQUE KEY id (id)
				) $charset_collate;";
				dbDelta($sql);
				
			$pluginsDir = plugin_dir_path(__FILE__);	
			$file = file_get_contents($pluginsDir . 'bzrcpQuestions.json');
			$dataJson = json_decode($file, true);

			foreach ($dataJson as $value) {
				$wpdb->insert($db_tableName1, $value);
			}
	} 
	

	
	// create Question Stat table
	$db_tableName2 = $wpdb->prefix . 'bzrcp_questions_stat';
	if ($wpdb->get_var("SHOW TABLES LIKE '$db_tableName2'") != $db_tableName2) {
						
		if (!empty($wpdb->charset))
		$charset_collate = 'DEFAULT CHARACTER SET '.$wpdb->charset;
		if (!empty($wpdb->collate))
		$charset_collate .= ' COLLATE '.$wpdb->collate;

		$sql = "CREATE TABLE $db_tableName2 (
			id mediumint(9) NOT NULL AUTO_INCREMENT,
			user_id VARCHAR(255) NOT NULL,
			question_id VARCHAR(120) NOT NULL,
			answer_id VARCHAR(255) NOT NULL,
			UNIQUE KEY id (id)
			) $charset_collate;";
			dbDelta($sql);
	} else {
		
	}
	
}

/**
 * Deletes table on plugin uninstall
*/
function bzrcp_deleteTable() {
	
	global $wpdb;
	
	$db_tableName3 = $wpdb->prefix . 'bzrcp_questions';
	$wpdb->query( "DROP TABLE IF EXISTS $db_tableName3");

}
