<?php
/**
 * Plugin Name: 	BLITZ - Guided Tours PRO
 * Plugin URI: 	    https://waaspro.com/
 * Description: 	Guided Tours PRO allows you to create custom tours that train new users how to manage their websites.
 * Version:     	1.0.15
 * Author:      	WaaS-Pro.com
 * Author URI:  	https://waaspro.com/
 * License:     	GPL2 etc
*/ 

if (!defined('ABSPATH')) { exit; }

register_activation_hook(__FILE__, array( 'Blitz_Tour_Wizard_Pro', 'bztsw_activation' ));
//register_uninstall_hook(__FILE__, array( 'Blitz_Tour_Wizard_Pro',  'bztsw_uninstallAction' ));
update_option('software_license_key_TRWI-LL', 'activated');
if( file_exists( dirname( __FILE__ ) . '/vendor/autoload.php' ) ) {
		require_once( dirname( __FILE__ ) . '/vendor/autoload.php' );
}

if (!class_exists('Blitz_Tour_Wizard_Pro'))
{
	class Blitz_Tour_Wizard_Pro {
		
		
		/**
		 * Called when plugin loads
		*/
		public function bz_tour_wizard_load()
		{
			global $bz_acf_elementor_load;

			if ( !isset($bz_acf_elementor_load))
			{
				  include_once(__DIR__ . '/blitz-guided-tours-settings.php');
				  $Plugin = new BZ_SGN_WZ\BZ_TourWizardSettings;
				  $Plugin->init();
			}
			return $bz_acf_elementor_load;
		}
		
		
		/**
		 * Called on plugin Activation
		*/
		public function bztsw_activation() {
			
			global $wpdb;
			require_once(ABSPATH . '/wp-admin/includes/upgrade.php');
	
			$maintableName = $wpdb->prefix . 'bztsw_settings';
			
			if ($wpdb->get_var('SHOW TABLES LIKE "' . $maintableName . '"') === $maintableName) {
				
				// create steps table
				$db_tableName1 = $wpdb->prefix . "bztsw_steps";
				$row1 = $wpdb->get_row("SELECT * FROM $db_tableName1 ");
				if(!isset($row1->bztsw_overrideSettings)){
				   $wpdb->query("ALTER TABLE $db_tableName1 ADD bztsw_overrideSettings VARCHAR(32) NOT NULL");
				} 
				//dialog override fields
				if(!isset($row1->bztsw_dialog_bgcolor)){
				   $wpdb->query("ALTER TABLE $db_tableName1 ADD bztsw_dialog_bgcolor VARCHAR(32) NOT NULL");
				}
				if(!isset($row1->bztsw_dialog_boxRadius)){
				   $wpdb->query("ALTER TABLE $db_tableName1 ADD bztsw_dialog_boxRadius VARCHAR(32) NOT NULL");
				}
				if(!isset($row1->bztsw_dialog_disaplyConti)){
				   $wpdb->query("ALTER TABLE $db_tableName1 ADD bztsw_dialog_disaplyConti VARCHAR(32) NOT NULL");
				}
				if(!isset($row1->bztsw_dialog_disaplyCancel)){
				   $wpdb->query("ALTER TABLE $db_tableName1 ADD bztsw_dialog_disaplyCancel VARCHAR(32) NOT NULL");
				}
				if(!isset($row1->bztsw_dialog_titleFont)){
				   $wpdb->query("ALTER TABLE $db_tableName1 ADD bztsw_dialog_titleFont VARCHAR(32) NOT NULL");
				}
				if(!isset($row1->bztsw_dialog_titleSize)){
				   $wpdb->query("ALTER TABLE $db_tableName1 ADD bztsw_dialog_titleSize VARCHAR(32) NOT NULL");
				}
				if(!isset($row1->bztsw_dialog_titlecolor)){
				   $wpdb->query("ALTER TABLE $db_tableName1 ADD bztsw_dialog_titlecolor VARCHAR(32) NOT NULL");
				}
				if(!isset($row1->bztsw_dialog_textFont)){
				   $wpdb->query("ALTER TABLE $db_tableName1 ADD bztsw_dialog_textFont VARCHAR(32) NOT NULL");
				}
				if(!isset($row1->bztsw_dialog_textSize)){
				   $wpdb->query("ALTER TABLE $db_tableName1 ADD bztsw_dialog_textSize VARCHAR(32) NOT NULL");
				}
				if(!isset($row1->bztsw_dialog_btnFont)){
				   $wpdb->query("ALTER TABLE $db_tableName1 ADD bztsw_dialog_btnFont VARCHAR(32) NOT NULL");
				}
				if(!isset($row1->bztsw_dialog_btnSize)){
				   $wpdb->query("ALTER TABLE $db_tableName1 ADD bztsw_dialog_btnSize VARCHAR(32) NOT NULL");
				}
				if(!isset($row1->bztsw_dialog_btnBg)){
				   $wpdb->query("ALTER TABLE $db_tableName1 ADD bztsw_dialog_btnBg VARCHAR(32) NOT NULL");
				}
				if(!isset($row1->bztsw_dialog_btnColor)){
				   $wpdb->query("ALTER TABLE $db_tableName1 ADD bztsw_dialog_btnColor VARCHAR(32) NOT NULL");
				}
				if(!isset($row1->bztsw_dialog_btnRadius)){
				   $wpdb->query("ALTER TABLE $db_tableName1 ADD bztsw_dialog_btnRadius VARCHAR(32) NOT NULL");
				}
				if(!isset($row1->bztsw_dialog_btnTSize)){
				   $wpdb->query("ALTER TABLE $db_tableName1 ADD bztsw_dialog_btnTSize VARCHAR(32) NOT NULL");
				}
				if(!isset($row1->bztsw_dialog_stop_btnFont)){
				   $wpdb->query("ALTER TABLE $db_tableName1 ADD bztsw_dialog_stop_btnFont VARCHAR(32) NOT NULL");
				}
				if(!isset($row1->bztsw_dialog_stop_btnSize)){
				   $wpdb->query("ALTER TABLE $db_tableName1 ADD bztsw_dialog_stop_btnSize VARCHAR(32) NOT NULL");
				}
				if(!isset($row1->bztsw_dialog_stop_btnBg)){
				   $wpdb->query("ALTER TABLE $db_tableName1 ADD bztsw_dialog_stop_btnBg VARCHAR(32) NOT NULL");
				}
				if(!isset($row1->bztsw_dialog_stop_btnColor)){
				   $wpdb->query("ALTER TABLE $db_tableName1 ADD bztsw_dialog_stop_btnColor VARCHAR(32) NOT NULL");
				}
				if(!isset($row1->bztsw_dialog_stop_btnRadius)){
				   $wpdb->query("ALTER TABLE $db_tableName1 ADD bztsw_dialog_stop_btnRadius VARCHAR(32) NOT NULL");
				}
				if(!isset($row1->bztsw_dialog_stop_btnTSize)){
				   $wpdb->query("ALTER TABLE $db_tableName1 ADD bztsw_dialog_stop_btnTSize VARCHAR(32) NOT NULL");
				}
				if(!isset($row1->bztsw_dialog_textcolor)){
				   $wpdb->query("ALTER TABLE $db_tableName1 ADD bztsw_dialog_textcolor VARCHAR(32) NOT NULL");
				}


				//tooltip override fields
				if(!isset($row1->bztsw_tooltip_bgcolor)){
				   $wpdb->query("ALTER TABLE $db_tableName1 ADD bztsw_tooltip_bgcolor VARCHAR(32) NOT NULL");
				}
				if(!isset($row1->bztsw_tooltip_boxRadius)){
				   $wpdb->query("ALTER TABLE $db_tableName1 ADD bztsw_tooltip_boxRadius VARCHAR(32) NOT NULL");
				}
				if(!isset($row1->bztsw_tooltip_disaplyConti)){
				   $wpdb->query("ALTER TABLE $db_tableName1 ADD bztsw_tooltip_disaplyConti VARCHAR(32) NOT NULL");
				}
				if(!isset($row1->bztsw_tooltip_disaplyCancel)){
				   $wpdb->query("ALTER TABLE $db_tableName1 ADD bztsw_tooltip_disaplyCancel VARCHAR(32) NOT NULL");
				}
				if(!isset($row1->bztsw_tooltip_titleFont)){
				   $wpdb->query("ALTER TABLE $db_tableName1 ADD bztsw_tooltip_titleFont VARCHAR(32) NOT NULL");
				}
				if(!isset($row1->bztsw_tooltip_titleSize)){
				   $wpdb->query("ALTER TABLE $db_tableName1 ADD bztsw_tooltip_titleSize VARCHAR(32) NOT NULL");
				}
				if(!isset($row1->bztsw_tooltip_titlecolor)){
				   $wpdb->query("ALTER TABLE $db_tableName1 ADD bztsw_tooltip_titlecolor VARCHAR(32) NOT NULL");
				}
				if(!isset($row1->bztsw_tooltip_textFont)){
				   $wpdb->query("ALTER TABLE $db_tableName1 ADD bztsw_tooltip_textFont VARCHAR(32) NOT NULL");
				}
				if(!isset($row1->bztsw_tooltip_textSize)){
				   $wpdb->query("ALTER TABLE $db_tableName1 ADD bztsw_tooltip_textSize VARCHAR(32) NOT NULL");
				}
				if(!isset($row1->bztsw_tooltip_btnFont)){
				   $wpdb->query("ALTER TABLE $db_tableName1 ADD bztsw_tooltip_btnFont VARCHAR(32) NOT NULL");
				}
				if(!isset($row1->bztsw_tooltip_btnSize)){
				   $wpdb->query("ALTER TABLE $db_tableName1 ADD bztsw_tooltip_btnSize VARCHAR(32) NOT NULL");
				}
				if(!isset($row1->bztsw_tooltip_btnBg)){
				   $wpdb->query("ALTER TABLE $db_tableName1 ADD bztsw_tooltip_btnBg VARCHAR(32) NOT NULL");
				}
				if(!isset($row1->bztsw_tooltip_btnColor)){
				   $wpdb->query("ALTER TABLE $db_tableName1 ADD bztsw_tooltip_btnColor VARCHAR(32) NOT NULL");
				}
				if(!isset($row1->bztsw_tooltip_btnRadius)){
				   $wpdb->query("ALTER TABLE $db_tableName1 ADD bztsw_tooltip_btnRadius VARCHAR(32) NOT NULL");
				}
				if(!isset($row1->bztsw_tooltip_btnTSize)){
				   $wpdb->query("ALTER TABLE $db_tableName1 ADD bztsw_tooltip_btnTSize VARCHAR(32) NOT NULL");
				}
				if(!isset($row1->bztsw_tooltip_stop_btnFont)){
				   $wpdb->query("ALTER TABLE $db_tableName1 ADD bztsw_tooltip_stop_btnFont VARCHAR(32) NOT NULL");
				}
				if(!isset($row1->bztsw_tooltip_stop_btnSize)){
				   $wpdb->query("ALTER TABLE $db_tableName1 ADD bztsw_tooltip_stop_btnSize VARCHAR(32) NOT NULL");
				}
				if(!isset($row1->bztsw_tooltip_stop_btnBg)){
				   $wpdb->query("ALTER TABLE $db_tableName1 ADD bztsw_tooltip_stop_btnBg VARCHAR(32) NOT NULL");
				}
				if(!isset($row1->bztsw_tooltip_stop_btnColor)){
				   $wpdb->query("ALTER TABLE $db_tableName1 ADD bztsw_tooltip_stop_btnColor VARCHAR(32) NOT NULL");
				}
				if(!isset($row1->bztsw_tooltip_stop_btnRadius)){
				   $wpdb->query("ALTER TABLE $db_tableName1 ADD bztsw_tooltip_stop_btnRadius VARCHAR(32) NOT NULL");
				}
				if(!isset($row1->bztsw_tooltip_stop_btnTSize)){
				   $wpdb->query("ALTER TABLE $db_tableName1 ADD bztsw_tooltip_stop_btnTSize VARCHAR(32) NOT NULL");
				}
				if(!isset($row1->bztsw_tooltip_textcolor)){
				   $wpdb->query("ALTER TABLE $db_tableName1 ADD bztsw_tooltip_textcolor VARCHAR(32) NOT NULL");
				}
				
				
				
				//Add columns [HCOLOR & HFONT] if not added 
				$db_tableName2 = $wpdb->prefix . "bztsw_settings";
				
				
				
				$row2 = $wpdb->get_row("SELECT * FROM $db_tableName2 ");
				if(!isset($row2->bztsw_titleFont)){
				   $wpdb->query("ALTER TABLE $db_tableName2 ADD bztsw_titleFont VARCHAR(32) NOT NULL");
				} 
				if (!isset($row2->bztsw_textFont)) {
				   $wpdb->query("ALTER TABLE $db_tableName2 ADD bztsw_textFont VARCHAR(32) NOT NULL");
				} 
				if (!isset($row2->bztsw_btnFont)) {
				   $wpdb->query("ALTER TABLE $db_tableName2 ADD bztsw_btnFont VARCHAR(32) NOT NULL");
				}
				if (!isset($row2->bztsw_dia_bgcolor)) {
				  $wpdb->query("ALTER TABLE $db_tableName2 ADD bztsw_dia_bgcolor VARCHAR(32) NOT NULL");
				} 
				if (!isset($row2->bztsw_dia_boxRadius)) {
				  $wpdb->query("ALTER TABLE $db_tableName2 ADD bztsw_dia_boxRadius VARCHAR(32) NOT NULL");
				} 
				if (!isset($row2->bztsw_dia_titlecolor)) {
				  $wpdb->query("ALTER TABLE $db_tableName2 ADD bztsw_dia_titlecolor VARCHAR(32) NOT NULL");
				}
				if (!isset($row2->bztsw_dia_textcolor)) {
				  $wpdb->query("ALTER TABLE $db_tableName2 ADD bztsw_dia_textcolor VARCHAR(32) NOT NULL");
				}
				if (!isset($row2->bztsw_dia_titleFont)) {
				  $wpdb->query("ALTER TABLE $db_tableName2 ADD bztsw_dia_titleFont VARCHAR(32) NOT NULL");
				}
				if (!isset($row2->bztsw_dia_textFont)) {
				  $wpdb->query("ALTER TABLE $db_tableName2 ADD bztsw_dia_textFont VARCHAR(32) NOT NULL");
				} 
				if (!isset($row2->bztsw_dia_btnFont)) {
				  $wpdb->query("ALTER TABLE $db_tableName2 ADD bztsw_dia_btnFont VARCHAR(32) NOT NULL");
				} 
				if (!isset($row2->bztsw_dia_titleSize)) {
				  $wpdb->query("ALTER TABLE $db_tableName2 ADD bztsw_dia_titleSize VARCHAR(32) NOT NULL");
				}
				if (!isset($row2->bztsw_dia_textSize)) {
				  $wpdb->query("ALTER TABLE $db_tableName2 ADD bztsw_dia_textSize VARCHAR(32) NOT NULL");
				}
				if (!isset($row2->bztsw_dia_btnSize)) {
				  $wpdb->query("ALTER TABLE $db_tableName2 ADD bztsw_dia_btnSize VARCHAR(32) NOT NULL");
				} 
				if (!isset($row2->bztsw_dia_btnRadius)) {
				  $wpdb->query("ALTER TABLE $db_tableName2 ADD bztsw_dia_btnRadius VARCHAR(32) NOT NULL");
				}
				if (!isset($row2->bztsw_dia_btnBg)) {
				  $wpdb->query("ALTER TABLE $db_tableName2 ADD bztsw_dia_btnBg VARCHAR(32) NOT NULL");
				} 
				if (!isset($row2->bztsw_dia_btnColor)) {
				  $wpdb->query("ALTER TABLE $db_tableName2 ADD bztsw_dia_btnColor VARCHAR(32) NOT NULL");
				} 
				if (!isset($row2->bztsw_dia_disaplyConti)) {
				  $wpdb->query("ALTER TABLE $db_tableName2 ADD bztsw_dia_disaplyConti VARCHAR(32) NOT NULL");
				}
				if (!isset($row2->bztsw_dia_disaplyCancel)) {
				  $wpdb->query("ALTER TABLE $db_tableName2 ADD bztsw_dia_disaplyCancel VARCHAR(32) NOT NULL");
				}
				if (!isset($row2->bztsw_text_titlecolor)) {
				  $wpdb->query("ALTER TABLE $db_tableName2 ADD bztsw_text_titlecolor VARCHAR(32) NOT NULL");
				} 
				if (!isset($row2->bztsw_text_textcolor)) {
				  $wpdb->query("ALTER TABLE $db_tableName2 ADD bztsw_text_textcolor VARCHAR(32) NOT NULL");
				}
				if (!isset($row2->bztsw_text_titleFont)) {
				  $wpdb->query("ALTER TABLE $db_tableName2 ADD bztsw_text_titleFont VARCHAR(32) NOT NULL");
				} 
				if (!isset($row2->bztsw_text_textFont)) {
				  $wpdb->query("ALTER TABLE $db_tableName2 ADD bztsw_text_textFont VARCHAR(32) NOT NULL");
				}
				if (!isset($row2->bztsw_text_btnFont)) {
				  $wpdb->query("ALTER TABLE $db_tableName2 ADD bztsw_text_btnFont VARCHAR(32) NOT NULL");
				}
				if (!isset($row2->bztsw_text_titleSize)) {
				  $wpdb->query("ALTER TABLE $db_tableName2 ADD bztsw_text_titleSize VARCHAR(32) NOT NULL");
				}
				if (!isset($row2->bztsw_text_textSize)) {
				  $wpdb->query("ALTER TABLE $db_tableName2 ADD bztsw_text_textSize VARCHAR(32) NOT NULL");
				}
				if (!isset($row2->bztsw_text_btnSize)) {
				  $wpdb->query("ALTER TABLE $db_tableName2 ADD bztsw_text_btnSize VARCHAR(32) NOT NULL");
				}
				if (!isset($row2->bztsw_text_btnRadius)) {
				  $wpdb->query("ALTER TABLE $db_tableName2 ADD bztsw_text_btnRadius VARCHAR(32) NOT NULL");
				}
				if (!isset($row2->bztsw_text_btnBg)) {
				  $wpdb->query("ALTER TABLE $db_tableName2 ADD bztsw_text_btnBg VARCHAR(32) NOT NULL");
				}
				if (!isset($row2->bztsw_text_btnColor)) {
				  $wpdb->query("ALTER TABLE $db_tableName2 ADD bztsw_text_btnColor VARCHAR(32) NOT NULL");
				}
				if (!isset($row2->bztsw_text_disaplyConti)) {
				  $wpdb->query("ALTER TABLE $db_tableName2 ADD bztsw_text_disaplyConti VARCHAR(32) NOT NULL");
				}
				if (!isset($row2->bztsw_text_disaplyCancel)) {
				  $wpdb->query("ALTER TABLE $db_tableName2 ADD bztsw_text_disaplyCancel VARCHAR(32) NOT NULL");
				}
				
				/* 21 feb */
				// for tooltip
				if (!isset($row2->bztsw_stop_btnSize)) {
				  $wpdb->query("ALTER TABLE $db_tableName2 ADD bztsw_stop_btnSize VARCHAR(32) NOT NULL");
				}
				if (!isset($row2->bztsw_stop_btnRadius)) {
				  $wpdb->query("ALTER TABLE $db_tableName2 ADD bztsw_stop_btnRadius VARCHAR(32) NOT NULL");
				}
				if (!isset($row2->bztsw_stop_btnBg)) {
				  $wpdb->query("ALTER TABLE $db_tableName2 ADD bztsw_stop_btnBg VARCHAR(32) NOT NULL");
				}
				if (!isset($row2->bztsw_stop_btnColor)) {
				  $wpdb->query("ALTER TABLE $db_tableName2 ADD bztsw_stop_btnColor VARCHAR(32) NOT NULL");
				}
				if (!isset($row2->bztsw_stop_btnFont)) {
				  $wpdb->query("ALTER TABLE $db_tableName2 ADD bztsw_stop_btnFont VARCHAR(32) NOT NULL");
				}
				if (!isset($row2->bztsw_stop_btnTSize)) {
				  $wpdb->query("ALTER TABLE $db_tableName2 ADD bztsw_stop_btnTSize VARCHAR(32) NOT NULL");
				}
				if (!isset($row2->bztsw_btnTSize)) { //button 1 size for tooltip
				  $wpdb->query("ALTER TABLE $db_tableName2 ADD bztsw_btnTSize VARCHAR(32) NOT NULL");
				}
				
				// for dilog
				if (!isset($row2->bztsw_dia_stop_btnSize)) {
				  $wpdb->query("ALTER TABLE $db_tableName2 ADD bztsw_dia_stop_btnSize VARCHAR(32) NOT NULL");
				}
				if (!isset($row2->bztsw_dia_stop_btnRadius)) {
				  $wpdb->query("ALTER TABLE $db_tableName2 ADD bztsw_dia_stop_btnRadius VARCHAR(32) NOT NULL");
				}
				if (!isset($row2->bztsw_dia_stop_btnBg)) {
				  $wpdb->query("ALTER TABLE $db_tableName2 ADD bztsw_dia_stop_btnBg VARCHAR(32) NOT NULL");
				}
				if (!isset($row2->bztsw_dia_stop_btnColor)) {
				  $wpdb->query("ALTER TABLE $db_tableName2 ADD bztsw_dia_stop_btnColor VARCHAR(32) NOT NULL");
				}
				if (!isset($row2->bztsw_dia_stop_btnFont)) {
				  $wpdb->query("ALTER TABLE $db_tableName2 ADD bztsw_dia_stop_btnFont VARCHAR(32) NOT NULL");
				}
				if (!isset($row2->bztsw_dia_stop_btnTSize)) {
				  $wpdb->query("ALTER TABLE $db_tableName2 ADD bztsw_dia_stop_btnTSize VARCHAR(32) NOT NULL");
				}
				if (!isset($row2->bztsw_dia_btnTSize)) { //button 1 size for dilog
				  $wpdb->query("ALTER TABLE $db_tableName2 ADD bztsw_dia_btnTSize VARCHAR(32) NOT NULL");
				}
				
				$rows_affected = $wpdb->insert($db_tableName2, array('bztsw_id' => 1, 'bztsw_bgcolor' => '#ffffff', 'bztsw_titlecolor' => '#34495e', 'bztsw_textcolor' => '#bdc3c7','bztsw_titleSize' => '20','bztsw_textSize' => '14','bztsw_btnSize' => '14','bztsw_btnRadius' => '2','bztsw_boxRadius' => '2'));
				
			} else {
				
				// create tours table
				$db_tableName = $wpdb->prefix . 'bztsw_tours';
				if ($wpdb->get_var('SHOW TABLES LIKE "'.$db_tableName.'"') != $db_tableName) {
					
				if (!empty($wpdb->charset))
						$charset_collate = 'DEFAULT CHARACTER SET '.$wpdb->charset;
					if (!empty($wpdb->collate))
						$charset_collate .= ' COLLATE '.$wpdb->collate;

					$sql = "CREATE TABLE $db_tableName (
						bztsw_id mediumint(9) NOT NULL AUTO_INCREMENT,
						bztsw_title VARCHAR(120) NOT NULL,
						bztsw_begin VARCHAR(32) NOT NULL,
						bztsw_onDashboard BOOL NOT NULL,
						bztsw_domComponent TEXT NOT NULL,
						bztsw_pageurl TEXT NOT NULL,
						bztsw_defaultTour BOOL NOT NULL DEFAULT '0',                
						bztsw_ultimoPlan VARCHAR(32) NOT NULL,                
						bztsw_isDraft INT(10) NOT NULL,
						bztsw_isActive INT(10) NOT NULL,
				UNIQUE KEY bztsw_id (bztsw_id)
				) $charset_collate;";
					dbDelta($sql);
				} 
				
				
				//Add columns [HCOLOR & HFONT] if not added 
				$db_tableName2 = $wpdb->prefix . "bztsw_settings";
				$row2 = $wpdb->get_row("SELECT * FROM $db_tableName2 ");
				if(!isset($row2->bztsw_titleFont)){
				   $wpdb->query("ALTER TABLE $db_tableName2 ADD bztsw_titleFont VARCHAR(32) NOT NULL");
				} 
				if (!isset($row2->bztsw_textFont)) {
				   $wpdb->query("ALTER TABLE $db_tableName2 ADD bztsw_textFont VARCHAR(32) NOT NULL");
				} 
				if (!isset($row2->bztsw_btnFont)) {
				   $wpdb->query("ALTER TABLE $db_tableName2 ADD bztsw_btnFont VARCHAR(32) NOT NULL");
				}
				if (!isset($row2->bztsw_dia_bgcolor)) {
				  $wpdb->query("ALTER TABLE $db_tableName2 ADD bztsw_dia_bgcolor VARCHAR(32) NOT NULL");
				} 
				if (!isset($row2->bztsw_dia_boxRadius)) {
				  $wpdb->query("ALTER TABLE $db_tableName2 ADD bztsw_dia_boxRadius VARCHAR(32) NOT NULL");
				} 
				if (!isset($row2->bztsw_dia_titlecolor)) {
				  $wpdb->query("ALTER TABLE $db_tableName2 ADD bztsw_dia_titlecolor VARCHAR(32) NOT NULL");
				}
				if (!isset($row2->bztsw_dia_textcolor)) {
				  $wpdb->query("ALTER TABLE $db_tableName2 ADD bztsw_dia_textcolor VARCHAR(32) NOT NULL");
				}
				if (!isset($row2->bztsw_dia_titleFont)) {
				  $wpdb->query("ALTER TABLE $db_tableName2 ADD bztsw_dia_titleFont VARCHAR(32) NOT NULL");
				}
				if (!isset($row2->bztsw_dia_textFont)) {
				  $wpdb->query("ALTER TABLE $db_tableName2 ADD bztsw_dia_textFont VARCHAR(32) NOT NULL");
				} 
				if (!isset($row2->bztsw_dia_btnFont)) {
				  $wpdb->query("ALTER TABLE $db_tableName2 ADD bztsw_dia_btnFont VARCHAR(32) NOT NULL");
				} 
				if (!isset($row2->bztsw_dia_titleSize)) {
				  $wpdb->query("ALTER TABLE $db_tableName2 ADD bztsw_dia_titleSize VARCHAR(32) NOT NULL");
				}
				if (!isset($row2->bztsw_dia_textSize)) {
				  $wpdb->query("ALTER TABLE $db_tableName2 ADD bztsw_dia_textSize VARCHAR(32) NOT NULL");
				}
				if (!isset($row2->bztsw_dia_btnSize)) {
				  $wpdb->query("ALTER TABLE $db_tableName2 ADD bztsw_dia_btnSize VARCHAR(32) NOT NULL");
				} 
				if (!isset($row2->bztsw_dia_btnRadius)) {
				  $wpdb->query("ALTER TABLE $db_tableName2 ADD bztsw_dia_btnRadius VARCHAR(32) NOT NULL");
				}
				if (!isset($row2->bztsw_dia_btnBg)) {
				  $wpdb->query("ALTER TABLE $db_tableName2 ADD bztsw_dia_btnBg VARCHAR(32) NOT NULL");
				} 
				if (!isset($row2->bztsw_dia_btnColor)) {
				  $wpdb->query("ALTER TABLE $db_tableName2 ADD bztsw_dia_btnColor VARCHAR(32) NOT NULL");
				} 
				if (!isset($row2->bztsw_dia_disaplyConti)) {
				  $wpdb->query("ALTER TABLE $db_tableName2 ADD bztsw_dia_disaplyConti VARCHAR(32) NOT NULL");
				}
				if (!isset($row2->bztsw_dia_disaplyCancel)) {
				  $wpdb->query("ALTER TABLE $db_tableName2 ADD bztsw_dia_disaplyCancel VARCHAR(32) NOT NULL");
				}
				if (!isset($row2->bztsw_text_titlecolor)) {
				  $wpdb->query("ALTER TABLE $db_tableName2 ADD bztsw_text_titlecolor VARCHAR(32) NOT NULL");
				} 
				if (!isset($row2->bztsw_text_textcolor)) {
				  $wpdb->query("ALTER TABLE $db_tableName2 ADD bztsw_text_textcolor VARCHAR(32) NOT NULL");
				}
				if (!isset($row2->bztsw_text_titleFont)) {
				  $wpdb->query("ALTER TABLE $db_tableName2 ADD bztsw_text_titleFont VARCHAR(32) NOT NULL");
				} 
				if (!isset($row2->bztsw_text_textFont)) {
				  $wpdb->query("ALTER TABLE $db_tableName2 ADD bztsw_text_textFont VARCHAR(32) NOT NULL");
				}
				if (!isset($row2->bztsw_text_btnFont)) {
				  $wpdb->query("ALTER TABLE $db_tableName2 ADD bztsw_text_btnFont VARCHAR(32) NOT NULL");
				}
				if (!isset($row2->bztsw_text_titleSize)) {
				  $wpdb->query("ALTER TABLE $db_tableName2 ADD bztsw_text_titleSize VARCHAR(32) NOT NULL");
				}
				if (!isset($row2->bztsw_text_textSize)) {
				  $wpdb->query("ALTER TABLE $db_tableName2 ADD bztsw_text_textSize VARCHAR(32) NOT NULL");
				}
				if (!isset($row2->bztsw_text_btnSize)) {
				  $wpdb->query("ALTER TABLE $db_tableName2 ADD bztsw_text_btnSize VARCHAR(32) NOT NULL");
				}
				if (!isset($row2->bztsw_text_btnRadius)) {
				  $wpdb->query("ALTER TABLE $db_tableName2 ADD bztsw_text_btnRadius VARCHAR(32) NOT NULL");
				}
				if (!isset($row2->bztsw_text_btnBg)) {
				  $wpdb->query("ALTER TABLE $db_tableName2 ADD bztsw_text_btnBg VARCHAR(32) NOT NULL");
				}
				if (!isset($row2->bztsw_text_btnColor)) {
				  $wpdb->query("ALTER TABLE $db_tableName2 ADD bztsw_text_btnColor VARCHAR(32) NOT NULL");
				}
				if (!isset($row2->bztsw_text_disaplyConti)) {
				  $wpdb->query("ALTER TABLE $db_tableName2 ADD bztsw_text_disaplyConti VARCHAR(32) NOT NULL");
				}
				if (!isset($row2->bztsw_text_disaplyCancel)) {
				  $wpdb->query("ALTER TABLE $db_tableName2 ADD bztsw_text_disaplyCancel VARCHAR(32) NOT NULL");
				}
				
				/* 21 feb */
				// for tooltip
				if (!isset($row2->bztsw_stop_btnSize)) {
				  $wpdb->query("ALTER TABLE $db_tableName2 ADD bztsw_stop_btnSize VARCHAR(32) NOT NULL");
				}
				if (!isset($row2->bztsw_stop_btnRadius)) {
				  $wpdb->query("ALTER TABLE $db_tableName2 ADD bztsw_stop_btnRadius VARCHAR(32) NOT NULL");
				}
				if (!isset($row2->bztsw_stop_btnBg)) {
				  $wpdb->query("ALTER TABLE $db_tableName2 ADD bztsw_stop_btnBg VARCHAR(32) NOT NULL");
				}
				if (!isset($row2->bztsw_stop_btnColor)) {
				  $wpdb->query("ALTER TABLE $db_tableName2 ADD bztsw_stop_btnColor VARCHAR(32) NOT NULL");
				}
				if (!isset($row2->bztsw_stop_btnFont)) {
				  $wpdb->query("ALTER TABLE $db_tableName2 ADD bztsw_stop_btnFont VARCHAR(32) NOT NULL");
				}
				if (!isset($row2->bztsw_stop_btnTSize)) {
				  $wpdb->query("ALTER TABLE $db_tableName2 ADD bztsw_stop_btnTSize VARCHAR(32) NOT NULL");
				}
				if (!isset($row2->bztsw_btnTSize)) { //button 1 size for tooltip
				  $wpdb->query("ALTER TABLE $db_tableName2 ADD bztsw_btnTSize VARCHAR(32) NOT NULL");
				}
				
				// for dilog
				if (!isset($row2->bztsw_dia_stop_btnSize)) {
				  $wpdb->query("ALTER TABLE $db_tableName2 ADD bztsw_dia_stop_btnSize VARCHAR(32) NOT NULL");
				}
				if (!isset($row2->bztsw_dia_stop_btnRadius)) {
				  $wpdb->query("ALTER TABLE $db_tableName2 ADD bztsw_dia_stop_btnRadius VARCHAR(32) NOT NULL");
				}
				if (!isset($row2->bztsw_dia_stop_btnBg)) {
				  $wpdb->query("ALTER TABLE $db_tableName2 ADD bztsw_dia_stop_btnBg VARCHAR(32) NOT NULL");
				}
				if (!isset($row2->bztsw_dia_stop_btnColor)) {
				  $wpdb->query("ALTER TABLE $db_tableName2 ADD bztsw_dia_stop_btnColor VARCHAR(32) NOT NULL");
				}
				if (!isset($row2->bztsw_dia_stop_btnFont)) {
				  $wpdb->query("ALTER TABLE $db_tableName2 ADD bztsw_dia_stop_btnFont VARCHAR(32) NOT NULL");
				}
				if (!isset($row2->bztsw_dia_stop_btnTSize)) {
				  $wpdb->query("ALTER TABLE $db_tableName2 ADD bztsw_dia_stop_btnTSize VARCHAR(32) NOT NULL");
				}
				if (!isset($row2->bztsw_dia_btnTSize)) { //button 1 size for dilog
				  $wpdb->query("ALTER TABLE $db_tableName2 ADD bztsw_dia_btnTSize VARCHAR(32) NOT NULL");
				}
				
				
				
				if ($wpdb->get_var('SHOW TABLES LIKE "'.$db_tableName2.'"') != $db_tableName2) {
					if (!empty($wpdb->charset))
						$charset_collate = "DEFAULT CHARACTER SET $wpdb->charset";
					if (!empty($wpdb->collate))
						$charset_collate .= " COLLATE $wpdb->collate";

					$sql = "CREATE TABLE $db_tableName2 (
						bztsw_id mediumint(9) NOT NULL AUTO_INCREMENT,      
						bztsw_bgcolor VARCHAR(32) NOT NULL,
						bztsw_boxRadius VARCHAR(32) NOT NULL,						
						bztsw_titlecolor VARCHAR(32) NOT NULL,
						bztsw_titleFont VARCHAR(32) NOT NULL,
						bztsw_titleSize VARCHAR(32) NOT NULL,
						bztsw_textFont VARCHAR(32) NOT NULL,
						bztsw_textcolor VARCHAR(32) NOT NULL,
						bztsw_textSize VARCHAR(32) NOT NULL,
						bztsw_btnFont VARCHAR(32) NOT NULL,
						bztsw_btnSize VARCHAR(32) NOT NULL,
						bztsw_btnRadius VARCHAR(32) NOT NULL,
						bztsw_btnBg VARCHAR(32) NOT NULL,
						bztsw_btnColor VARCHAR(32) NOT NULL,
						bztsw_disaplyConti VARCHAR(32) NOT NULL,
						bztsw_disaplyCancel VARCHAR(32) NOT NULL,
						bztsw_dia_bgcolor VARCHAR(32) NOT NULL,
						bztsw_dia_boxRadius VARCHAR(32) NOT NULL,
						bztsw_dia_titlecolor VARCHAR(32) NOT NULL,
						bztsw_dia_textcolor VARCHAR(32) NOT NULL,
						bztsw_dia_titleFont VARCHAR(32) NOT NULL,
						bztsw_dia_textFont VARCHAR(32) NOT NULL,
						bztsw_dia_btnFont VARCHAR(32) NOT NULL,
						bztsw_dia_titleSize VARCHAR(32) NOT NULL,
						bztsw_dia_textSize VARCHAR(32) NOT NULL,
						bztsw_dia_btnSize VARCHAR(32) NOT NULL,
						bztsw_dia_btnRadius VARCHAR(32) NOT NULL,
						bztsw_dia_btnBg VARCHAR(32) NOT NULL,
						bztsw_dia_btnColor VARCHAR(32) NOT NULL,
						bztsw_dia_disaplyConti VARCHAR(32) NOT NULL,
						bztsw_dia_disaplyCancel VARCHAR(32) NOT NULL,
						bztsw_text_titlecolor VARCHAR(32) NOT NULL,
						bztsw_text_textcolor VARCHAR(32) NOT NULL, 
						bztsw_text_titleFont VARCHAR(32) NOT NULL,
						bztsw_text_textFont VARCHAR(32) NOT NULL,
						bztsw_text_btnFont VARCHAR(32) NOT NULL,
						bztsw_text_titleSize VARCHAR(32) NOT NULL,
						bztsw_text_textSize VARCHAR(32) NOT NULL,
						bztsw_text_btnSize VARCHAR(32) NOT NULL,
						bztsw_text_btnRadius VARCHAR(32) NOT NULL,
						bztsw_text_btnBg VARCHAR(32) NOT NULL,
						bztsw_text_btnColor VARCHAR(32) NOT NULL,
						bztsw_text_disaplyConti VARCHAR(32) NOT NULL,
						bztsw_text_disaplyCancel VARCHAR(32) NOT NULL,
						bztsw_stop_btnSize VARCHAR(32) NOT NULL, 
						bztsw_stop_btnRadius VARCHAR(32) NOT NULL, 
						bztsw_stop_btnBg VARCHAR(32) NOT NULL, 
						bztsw_stop_btnFont VARCHAR(32) NOT NULL, 
						bztsw_stop_btnTSize VARCHAR(32) NOT NULL, 
						bztsw_btnTSize VARCHAR(32) NOT NULL, 
						bztsw_dia_stop_btnSize VARCHAR(32) NOT NULL, 
						bztsw_dia_stop_btnRadius VARCHAR(32) NOT NULL, 
						bztsw_dia_stop_btnBg VARCHAR(32) NOT NULL, 
						bztsw_dia_stop_btnColor VARCHAR(32) NOT NULL, 
						bztsw_dia_stop_btnFont VARCHAR(32) NOT NULL, 
						bztsw_dia_stop_btnTSize VARCHAR(32) NOT NULL, 
						bztsw_dia_btnTSize VARCHAR(32) NOT NULL, 
					UNIQUE KEY bztsw_id (bztsw_id)
				) $charset_collate;";
					dbDelta($sql);
				} 
				
				
				// create steps table
				$db_tableName1 = $wpdb->prefix . "bztsw_steps";
				$row1 = $wpdb->get_row("SELECT * FROM $db_tableName1 ");
				if(!isset($row1->bztsw_overrideSettings)){
				   $wpdb->query("ALTER TABLE $db_tableName1 ADD bztsw_overrideSettings VARCHAR(32) NOT NULL");
				} 
				//dialog override fields
				if(!isset($row1->bztsw_dialog_bgcolor)){
				   $wpdb->query("ALTER TABLE $db_tableName1 ADD bztsw_dialog_bgcolor VARCHAR(32) NOT NULL");
				}
				if(!isset($row1->bztsw_dialog_boxRadius)){
				   $wpdb->query("ALTER TABLE $db_tableName1 ADD bztsw_dialog_boxRadius VARCHAR(32) NOT NULL");
				}
				if(!isset($row1->bztsw_dialog_disaplyConti)){
				   $wpdb->query("ALTER TABLE $db_tableName1 ADD bztsw_dialog_disaplyConti VARCHAR(32) NOT NULL");
				}
				if(!isset($row1->bztsw_dialog_disaplyCancel)){
				   $wpdb->query("ALTER TABLE $db_tableName1 ADD bztsw_dialog_disaplyCancel VARCHAR(32) NOT NULL");
				}
				if(!isset($row1->bztsw_dialog_titleFont)){
				   $wpdb->query("ALTER TABLE $db_tableName1 ADD bztsw_dialog_titleFont VARCHAR(32) NOT NULL");
				}
				if(!isset($row1->bztsw_dialog_titleSize)){
				   $wpdb->query("ALTER TABLE $db_tableName1 ADD bztsw_dialog_titleSize VARCHAR(32) NOT NULL");
				}
				if(!isset($row1->bztsw_dialog_titlecolor)){
				   $wpdb->query("ALTER TABLE $db_tableName1 ADD bztsw_dialog_titlecolor VARCHAR(32) NOT NULL");
				}
				if(!isset($row1->bztsw_dialog_textFont)){
				   $wpdb->query("ALTER TABLE $db_tableName1 ADD bztsw_dialog_textFont VARCHAR(32) NOT NULL");
				}
				if(!isset($row1->bztsw_dialog_textSize)){
				   $wpdb->query("ALTER TABLE $db_tableName1 ADD bztsw_dialog_textSize VARCHAR(32) NOT NULL");
				}
				if(!isset($row1->bztsw_dialog_btnFont)){
				   $wpdb->query("ALTER TABLE $db_tableName1 ADD bztsw_dialog_btnFont VARCHAR(32) NOT NULL");
				}
				if(!isset($row1->bztsw_dialog_btnSize)){
				   $wpdb->query("ALTER TABLE $db_tableName1 ADD bztsw_dialog_btnSize VARCHAR(32) NOT NULL");
				}
				if(!isset($row1->bztsw_dialog_btnBg)){
				   $wpdb->query("ALTER TABLE $db_tableName1 ADD bztsw_dialog_btnBg VARCHAR(32) NOT NULL");
				}
				if(!isset($row1->bztsw_dialog_btnColor)){
				   $wpdb->query("ALTER TABLE $db_tableName1 ADD bztsw_dialog_btnColor VARCHAR(32) NOT NULL");
				}
				if(!isset($row1->bztsw_dialog_btnRadius)){
				   $wpdb->query("ALTER TABLE $db_tableName1 ADD bztsw_dialog_btnRadius VARCHAR(32) NOT NULL");
				}
				if(!isset($row1->bztsw_dialog_btnTSize)){
				   $wpdb->query("ALTER TABLE $db_tableName1 ADD bztsw_dialog_btnTSize VARCHAR(32) NOT NULL");
				}
				if(!isset($row1->bztsw_dialog_stop_btnFont)){
				   $wpdb->query("ALTER TABLE $db_tableName1 ADD bztsw_dialog_stop_btnFont VARCHAR(32) NOT NULL");
				}
				if(!isset($row1->bztsw_dialog_stop_btnSize)){
				   $wpdb->query("ALTER TABLE $db_tableName1 ADD bztsw_dialog_stop_btnSize VARCHAR(32) NOT NULL");
				}
				if(!isset($row1->bztsw_dialog_stop_btnBg)){
				   $wpdb->query("ALTER TABLE $db_tableName1 ADD bztsw_dialog_stop_btnBg VARCHAR(32) NOT NULL");
				}
				if(!isset($row1->bztsw_dialog_stop_btnColor)){
				   $wpdb->query("ALTER TABLE $db_tableName1 ADD bztsw_dialog_stop_btnColor VARCHAR(32) NOT NULL");
				}
				if(!isset($row1->bztsw_dialog_stop_btnRadius)){
				   $wpdb->query("ALTER TABLE $db_tableName1 ADD bztsw_dialog_stop_btnRadius VARCHAR(32) NOT NULL");
				}
				if(!isset($row1->bztsw_dialog_stop_btnTSize)){
				   $wpdb->query("ALTER TABLE $db_tableName1 ADD bztsw_dialog_stop_btnTSize VARCHAR(32) NOT NULL");
				}
				if(!isset($row1->bztsw_dialog_textcolor)){
				   $wpdb->query("ALTER TABLE $db_tableName1 ADD bztsw_dialog_textcolor VARCHAR(32) NOT NULL");
				}


				//tooltip override fields
				if(!isset($row1->bztsw_tooltip_bgcolor)){
				   $wpdb->query("ALTER TABLE $db_tableName1 ADD bztsw_tooltip_bgcolor VARCHAR(32) NOT NULL");
				}
				if(!isset($row1->bztsw_tooltip_boxRadius)){
				   $wpdb->query("ALTER TABLE $db_tableName1 ADD bztsw_tooltip_boxRadius VARCHAR(32) NOT NULL");
				}
				if(!isset($row1->bztsw_tooltip_disaplyConti)){
				   $wpdb->query("ALTER TABLE $db_tableName1 ADD bztsw_tooltip_disaplyConti VARCHAR(32) NOT NULL");
				}
				if(!isset($row1->bztsw_tooltip_disaplyCancel)){
				   $wpdb->query("ALTER TABLE $db_tableName1 ADD bztsw_tooltip_disaplyCancel VARCHAR(32) NOT NULL");
				}
				if(!isset($row1->bztsw_tooltip_titleFont)){
				   $wpdb->query("ALTER TABLE $db_tableName1 ADD bztsw_tooltip_titleFont VARCHAR(32) NOT NULL");
				}
				if(!isset($row1->bztsw_tooltip_titleSize)){
				   $wpdb->query("ALTER TABLE $db_tableName1 ADD bztsw_tooltip_titleSize VARCHAR(32) NOT NULL");
				}
				if(!isset($row1->bztsw_tooltip_titlecolor)){
				   $wpdb->query("ALTER TABLE $db_tableName1 ADD bztsw_tooltip_titlecolor VARCHAR(32) NOT NULL");
				}
				if(!isset($row1->bztsw_tooltip_textFont)){
				   $wpdb->query("ALTER TABLE $db_tableName1 ADD bztsw_tooltip_textFont VARCHAR(32) NOT NULL");
				}
				if(!isset($row1->bztsw_tooltip_textSize)){
				   $wpdb->query("ALTER TABLE $db_tableName1 ADD bztsw_tooltip_textSize VARCHAR(32) NOT NULL");
				}
				if(!isset($row1->bztsw_tooltip_btnFont)){
				   $wpdb->query("ALTER TABLE $db_tableName1 ADD bztsw_tooltip_btnFont VARCHAR(32) NOT NULL");
				}
				if(!isset($row1->bztsw_tooltip_btnSize)){
				   $wpdb->query("ALTER TABLE $db_tableName1 ADD bztsw_tooltip_btnSize VARCHAR(32) NOT NULL");
				}
				if(!isset($row1->bztsw_tooltip_btnBg)){
				   $wpdb->query("ALTER TABLE $db_tableName1 ADD bztsw_tooltip_btnBg VARCHAR(32) NOT NULL");
				}
				if(!isset($row1->bztsw_tooltip_btnColor)){
				   $wpdb->query("ALTER TABLE $db_tableName1 ADD bztsw_tooltip_btnColor VARCHAR(32) NOT NULL");
				}
				if(!isset($row1->bztsw_tooltip_btnRadius)){
				   $wpdb->query("ALTER TABLE $db_tableName1 ADD bztsw_tooltip_btnRadius VARCHAR(32) NOT NULL");
				}
				if(!isset($row1->bztsw_tooltip_btnTSize)){
				   $wpdb->query("ALTER TABLE $db_tableName1 ADD bztsw_tooltip_btnTSize VARCHAR(32) NOT NULL");
				}
				if(!isset($row1->bztsw_tooltip_stop_btnFont)){
				   $wpdb->query("ALTER TABLE $db_tableName1 ADD bztsw_tooltip_stop_btnFont VARCHAR(32) NOT NULL");
				}
				if(!isset($row1->bztsw_tooltip_stop_btnSize)){
				   $wpdb->query("ALTER TABLE $db_tableName1 ADD bztsw_tooltip_stop_btnSize VARCHAR(32) NOT NULL");
				}
				if(!isset($row1->bztsw_tooltip_stop_btnBg)){
				   $wpdb->query("ALTER TABLE $db_tableName1 ADD bztsw_tooltip_stop_btnBg VARCHAR(32) NOT NULL");
				}
				if(!isset($row1->bztsw_tooltip_stop_btnColor)){
				   $wpdb->query("ALTER TABLE $db_tableName1 ADD bztsw_tooltip_stop_btnColor VARCHAR(32) NOT NULL");
				}
				if(!isset($row1->bztsw_tooltip_stop_btnRadius)){
				   $wpdb->query("ALTER TABLE $db_tableName1 ADD bztsw_tooltip_stop_btnRadius VARCHAR(32) NOT NULL");
				}
				if(!isset($row1->bztsw_tooltip_stop_btnTSize)){
				   $wpdb->query("ALTER TABLE $db_tableName1 ADD bztsw_tooltip_stop_btnTSize VARCHAR(32) NOT NULL");
				}
				if(!isset($row1->bztsw_tooltip_textcolor)){
				   $wpdb->query("ALTER TABLE $db_tableName1 ADD bztsw_tooltip_textcolor VARCHAR(32) NOT NULL");
				}
				
				if ($wpdb->get_var('SHOW TABLES LIKE "'.$db_tableName1.'"') != $db_tableName1) {
					if (!empty($wpdb->charset))
						$charset_collate = "DEFAULT CHARACTER SET $wpdb->charset";
					if (!empty($wpdb->collate))
						$charset_collate .= " COLLATE $wpdb->collate";

					$sql = "CREATE TABLE $db_tableName1 (
						bztsw_id mediumint(9) NOT NULL AUTO_INCREMENT,
						bztsw_tourID mediumint(9) NOT NULL,
						bztsw_steporder mediumint(9) NOT NULL,
						bztsw_title VARCHAR(120) NOT NULL,
						bztsw_stepTy VARCHAR(120) NOT NULL,
						bztsw_domComponent TEXT NOT NULL,
						bztsw_pageurl TEXT NOT NULL,
						bztsw_tooltipPos VARCHAR(120) NOT NULL,
						bztsw_stepCont TEXT NOT NULL,
						bztsw_stepAction VARCHAR(32) NOT NULL DEFAULT 'delay',
						bztsw_stepConbtn VARCHAR(250) NOT NULL DEFAULT 'Continue',
						bztsw_stepStopbtn VARCHAR(250) NOT NULL,
						bztsw_stepDly FLOAT NOT NULL DEFAULT '5.0',
						bztsw_stepDlySrt FLOAT NOT NULL DEFAULT 0,
						bztsw_item_overlay BOOL DEFAULT 1,
						bztsw_isDraft INT(10) NOT NULL,
						bztsw_item_closeHelperBtn BOOL DEFAULT 0,
						bztsw_overrideSettings VARCHAR(32) NOT NULL,
						bztsw_dialog_bgcolor VARCHAR(32) NOT NULL,
						bztsw_dialog_boxRadius VARCHAR(32) NOT NULL,
						bztsw_dialog_disaplyConti VARCHAR(32) NOT NULL,
						bztsw_dialog_disaplyCancel VARCHAR(32) NOT NULL,
						bztsw_dialog_titleFont VARCHAR(32) NOT NULL,
						bztsw_dialog_titleSize VARCHAR(32) NOT NULL,
						bztsw_dialog_titlecolor VARCHAR(32) NOT NULL,
						bztsw_dialog_textFont VARCHAR(32) NOT NULL,
						bztsw_dialog_textSize VARCHAR(32) NOT NULL,
						bztsw_dialog_btnFont VARCHAR(32) NOT NULL,
						bztsw_dialog_btnSize VARCHAR(32) NOT NULL,
						bztsw_dialog_btnBg VARCHAR(32) NOT NULL,
						bztsw_dialog_btnColor VARCHAR(32) NOT NULL,
						bztsw_dialog_btnRadius VARCHAR(32) NOT NULL,
						bztsw_dialog_btnTSize VARCHAR(32) NOT NULL,
						bztsw_dialog_stop_btnFont VARCHAR(32) NOT NULL,
						bztsw_dialog_stop_btnSize VARCHAR(32) NOT NULL,
						bztsw_dialog_stop_btnBg VARCHAR(32) NOT NULL,
						bztsw_dialog_stop_btnColor VARCHAR(32) NOT NULL,
						bztsw_dialog_stop_btnRadius VARCHAR(32) NOT NULL,
						bztsw_dialog_stop_btnTSize VARCHAR(32) NOT NULL,
						bztsw_tooltip_bgcolor VARCHAR(32) NOT NULL,
						bztsw_tooltip_boxRadius VARCHAR(32) NOT NULL,
						bztsw_tooltip_disaplyConti VARCHAR(32) NOT NULL,
						bztsw_tooltip_disaplyCancel VARCHAR(32) NOT NULL,
						bztsw_tooltip_titleFont VARCHAR(32) NOT NULL,
						bztsw_tooltip_titleSize VARCHAR(32) NOT NULL,
						bztsw_tooltip_titlecolor VARCHAR(32) NOT NULL,
						bztsw_tooltip_textFont VARCHAR(32) NOT NULL,
						bztsw_tooltip_textSize VARCHAR(32) NOT NULL,
						bztsw_tooltip_btnFont VARCHAR(32) NOT NULL,
						bztsw_tooltip_btnSize VARCHAR(32) NOT NULL,
						bztsw_tooltip_btnBg VARCHAR(32) NOT NULL,
						bztsw_tooltip_btnColor VARCHAR(32) NOT NULL,
						bztsw_tooltip_btnRadius VARCHAR(32) NOT NULL,
						bztsw_tooltip_btnTSize VARCHAR(32) NOT NULL,
						bztsw_tooltip_stop_btnFont VARCHAR(32) NOT NULL,
						bztsw_tooltip_stop_btnSize VARCHAR(32) NOT NULL,
						bztsw_tooltip_stop_btnBg VARCHAR(32) NOT NULL,
						bztsw_tooltip_stop_btnColor VARCHAR(32) NOT NULL,
						bztsw_tooltip_stop_btnRadius VARCHAR(32) NOT NULL,
						bztsw_tooltip_stop_btnTSize VARCHAR(32) NOT NULL,
						bztsw_tooltip_textcolor VARCHAR(32) NOT NULL,
						bztsw_dialog_textcolor VARCHAR(32) NOT NULL,
				  UNIQUE KEY bztsw_id (bztsw_id)
				) $charset_collate;";
					dbDelta($sql);
				}
				// create settings table
				
				
				
	

				//~ // default settings
				$rows_affected = $wpdb->insert($db_tableName2, array('bztsw_id' => 1, 'bztsw_bgcolor' => '#ffffff', 'bztsw_titlecolor' => '#34495e', 'bztsw_textcolor' => '#bdc3c7','bztsw_titleSize' => '20','bztsw_textSize' => '14','bztsw_btnSize' => '14','bztsw_btnRadius' => '2','bztsw_boxRadius' => '2'));
			}
			
			
			$rows_affected = $wpdb->insert($wpdb->prefix.'bztsw_settings', array('bztsw_id' => 1, 'bztsw_bgcolor' => '#ffffff', 'bztsw_titlecolor' => '#34495e', 'bztsw_textcolor' => '#bdc3c7','bztsw_titleSize' => '20','bztsw_textSize' => '14','bztsw_btnSize' => '14','bztsw_btnRadius' => '2','bztsw_boxRadius' => '2'));

		}

		
		/**
		 * Called on plugin Dectivation
		*/
		public function bztsw_uninstallAction() {
			
			global $wpdb;
			$tableName = $wpdb->prefix . "bztsw_tours";
			$wpdb->query("DROP TABLE IF EXISTS $tableName");
			$tableName = $wpdb->prefix . "bztsw_steps";
			$wpdb->query("DROP TABLE IF EXISTS $tableName");
			$tableName = $wpdb->prefix . "bztsw_settings";
			$wpdb->query("DROP TABLE IF EXISTS $tableName");
		}
	}

}
$PluginTourWizard = new Blitz_Tour_Wizard_Pro;
$PluginTourWizard->bz_tour_wizard_load();
