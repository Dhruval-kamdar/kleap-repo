<?php
define('BASE_CE_DIR', 	dirname(__FILE__) . '/');
define('BASE_CE_BASENAME', 	plugin_basename( __FILE__ ));
define('CE_SHORTCODE_IDENTIFIER', 'shortcontent');
define('CE_SHORTCODE_IDENTIFIER_CUSTOMER', 'shortce');
define('SL_PRODUCT_ID_CE',   'BLT-CNT-EDR');
define('CE_VERSION',   '1.0.15');
define('CE_PLUGIN_FILE',   'blitz-content-editor-pro/blitz-content-editor-pro.php');

require_once (BASE_CE_DIR. 'blitz-content-theme-settings.php');

class Blitzcontent_EditorPro_Settings extends ThemeSettings{ 
		
		public $optionslug 			= 'content-pro-settings';	
		public $cefilterHandlerPrefix = 'CEFilter__';	
		public $ceadminSettings;

		public function init($cethemeVars='') {
		     
		require_once (BASE_CE_DIR. 'acf/acf.php');				    // Activate ACF		
		require_once (BASE_CE_DIR. 'acf/fa/acf-font-awesome.php');				    // Activate ACF Font Awesome		
		
		require_once (BASE_CE_DIR .'blitz-content-customfields.php');		// ACF Custom Fields Class
		require_once (BASE_CE_DIR .'admin/blitz-content-admin-settings.php');	// Admin Panel			
		require_once (BASE_CE_DIR. 'blitz-content-media.php');                 // Media    

		if ( ! function_exists( 'is_plugin_active_for_network' ) ) {
		include_once( ABSPATH . '/wp-admin/includes/plugin.php' );
		}
		
		
		$this->mediaSettings 		=  new Blitz_Copy_Media_File_To_Network_Sites();


		$this->ceshortcodeidentifier  =  CE_SHORTCODE_IDENTIFIER;
		$this->ceshortcodeidentifiercust  =  CE_SHORTCODE_IDENTIFIER_CUSTOMER;
		
		$this->ceadminSettings 		=  new ceadminSettings();
		
		if ( is_multisite() && is_plugin_active_for_network('blitz-content-editor-pro/blitz-content-editor-pro.php') ) {

			add_action('network_admin_menu', array($this->ceadminSettings,'add_ce_settings_page'));
			
		} else {
			
			add_action('admin_menu', array($this->ceadminSettings,'add_ce_ss_settings_page'));
			
		}
		
		$cevalidLicense 				= $this->ceadminSettings->ce_valid_license();

		if ($cevalidLicense)
		{
		
			add_action('acf/init', 		array($this,'ce_add_setting_groups'));			// Initialize the Meta Fields on ACF Init
			add_action('admin_enqueue_scripts',array($this,'bz_ce_enquque_scripts_styles'));
			//~ add_action('wp_enqueue_scripts',   array($this, 'bz_load_frontend_scripts'));

			// name
			add_filter('acf/fields/flexible_content/layout_title/name=create_site_layout', array($this,'ce_acf_flexible_content_layout_title'), 10, 4);

			add_action('init', array($this,'wporg_custom_post_type'), 20);
			
			if ( is_multisite() && is_plugin_active_for_network('blitz-content-editor-pro/blitz-content-editor-pro.php') ) {
				add_action('admin_menu', array( $this, 'add_settings_page_to_layouts'));
				add_action('init', array($this,'create_layout_tax'), 20);
				
				if(get_current_blog_id() == 1) {
					
					add_action('admin_footer', array( $this, 'checktoradioLayoutCategory'));
					add_action('save_post', array($this, 'save_layout_callback'));
					
					CEACFGroup::setLocation('post_type', 'layouts');
					CEACFGroup::setShortCode($this->ceshortcodeidentifier);	
				
				} else {
					
					add_action('admin_menu', array($this,'ce_add_options_page'));			// Add Options Page  Only to Template Site
					add_action('admin_menu', array($this->ceadminSettings,'remove_menus'));
					add_action('admin_init', array($this,'ce_save_layoutData'),9999);    //save layout ID on every init
					add_action('wu_after_switch_template', array($this, 'preserve_content_editor_template_settings'), 10);

					
					CEACFGroup::setLocation('options_page', $this->optionslug);
					CEACFGroup::setShortCode($this->ceshortcodeidentifiercust);		
				
				} 
			
			} else {    //single site
				
					add_action( 'admin_menu', array($this,'ce_adjust_layouts_menu'), 999 );
	
					CEACFGroup::setLocation('post_type', 'layouts');
					CEACFGroup::setShortCode($this->ceshortcodeidentifier);		
				
			}
			

			parent::init($cethemeVars);

			foreach ( $this->elements  as $ceelement) {
									
					$cetheme = ucwords($cethemeVars).'Vars';
					add_filter( $cetheme::buildFilter($ceelement) , array($this, $this->cefilterHandlerPrefix . $ceelement));						
			}
			
				
		}
			
			
		}
		
				
		/**
		 * Add Options page for Site Owner
		*/
		public function ce_add_options_page() {
			
			if( function_exists('acf_add_options_page') ) {
				acf_add_options_page(array(
					'page_title' 	=> 'Edit Content PRO Settings',
					'menu_title'	=> 'Edit Content',
					'menu_slug' 	=> $this->optionslug,
					'capability'	=> 'edit_posts',
					'icon_url'		=> 'dashicons-admin-generic',
					'redirect'		=> false
				) );
			}	
			
		}
				
				
				 
		/**
		 * Call to JS & CSS 
		*/
		public function bz_ce_enquque_scripts_styles(){ 
			
		   wp_register_script( 'custom-script', plugins_url( 'assets/js/script.js', __FILE__ ) );
		   wp_enqueue_script( 'custom-script' );
		   
		   wp_register_script( 'custom-repeater-script', plugins_url( 'assets/js/repeater.js', __FILE__ ) );
		   wp_enqueue_script( 'custom-repeater-script' );
		   
		   wp_register_style( 'custom-css', plugins_url( 'assets/css/style.css', __FILE__ ) );
		   wp_enqueue_style( 'custom-css' );
		   
		   if(isset($_GET['page'])) {
			   if( $_GET['page'] == 'layouts_settings' ) {
				   wp_register_style( 'custom-repeater-css', plugins_url( 'assets/css/repeater.css', __FILE__ ) );
				   wp_enqueue_style( 'custom-repeater-css' );
			  }
		   }
        
		}
				
				
				 
		/**
		 * Call to FRONTEND JS & CSS 
		*/
		public function bz_load_frontend_scripts(){
			
		   wp_enqueue_script('jquery'); //include jQuery
		   
		   wp_register_script( 'custom-front-script', plugins_url( 'assets/js/frontend.js', __FILE__ ) );
		   wp_enqueue_script( 'custom-front-script' );
		   
		   wp_register_style( 'custom-front-style', plugins_url( 'assets/css/frontend.css', __FILE__ ) );
		   wp_enqueue_style( 'custom-front-style' );
		
		}
		
		
		
		/**
		 * Add sub SETTINGS page to LAYOUT post type
		*/
		public function add_settings_page_to_layouts() {
			
			add_submenu_page(
				'edit.php?post_type=layouts',
				__('Assign Layouts'),
				__('Assign Layouts'),
				'manage_options',
				'layouts_settings',
				array($this, 'bz_cep_options_display'));
				
		}
		
		
		
		/**
		 * Remove add layout link if a single layout is created (hides the link)
		*/
		public function ce_adjust_layouts_menu() {
			
			  //Get user id
			  $current_user = wp_get_current_user();
			  $user_id = $current_user->ID;
			  
			  $args = array('post_type' =>'layouts','author'=>$user_id,'fields'>'ids');
			  $count = count(get_posts($args));
			  
			  if( $count >= 1 ) {
				$page = remove_submenu_page( 'edit.php?post_type=layouts', 'post-new.php?post_type=layouts' );
				$styleArgs = '<style type="text/css">.post-type-layouts .wrap .wp-heading-inline+.page-title-action{ display: none;}</style>';
				
				//apply css to hide add layout Link 
				add_action('admin_footer',
                   function() use ( $styleArgs ) {
                       $this->applyLayoutStyles( $styleArgs ); });
			  }
		}

		
		
		/**
		 * Options page callback 
		 */
		public function bz_cep_options_display() {
			return $this->ceadminSettings->ce_layout_cat_to_site_form();
		}
		
		
		
		/**
		 * Get Blogwise Layout ID assigned
		*/
		public function ce_save_layoutData() {
				
				global $wpdb,$table_prefix;
				$blog_id = get_current_blog_id();
				switch_to_blog(1);
				$templateCheck = get_option('templateCheck'.$blog_id);
				restore_current_blog();
				
				if($templateCheck == '1'){
					
					
					$site_id = $blog_id; 
					$layoutData = $this->getceSettings($site_id);   //get ce data
					$this->deleteCeSettings($site_id);   //get ce data
					
					$Arr = $layoutData[0]['ceContent'];
					$finalArr1 = json_decode($Arr);
					
					//~ echo '<pre/>';
					//~ print_r($finalArr1);
					//~ die;
					
					switch_to_blog($site_id);
					if( ! empty ($finalArr1) ) {
						foreach ($finalArr1 as $resultArrkey => $resultArrval ) {
							
							//update fields
							$wpdb->insert($table_prefix.'options', array(
									'option_name' => $resultArrkey,
									'option_value' => $resultArrval
							));

							//~ echo "INSERT INTO `wp_111_options` (`option_id`, `option_name`, `option_value`, `autoload`) VALUES (NULL, '$resultArrkey', '$resultArrval', 'yes'";
							
							//~ $wpdb->query("INSERT INTO `wp_111_options` (`option_id`, `option_name`, `option_value`, `autoload`) VALUES (NULL, '$resultArrkey', '$resultArrval', 'yes'");
							
							//update_option ($resultArrkey, $resultArrval);
						}
					}
					
					//~ die;
					
					delete_option ('options_layout_category');  //delete category
					delete_option ('error_message');    //delete error message
					restore_current_blog();
					
					switch_to_blog(1);
					$templateCheck = get_option('templateCheck'.$site_id);
					update_option ('templateCheck'.$site_id, 0);
					restore_current_blog();
				}
				
				
				if(isset($_REQUEST['page']) &&  $_REQUEST['page'] == 'wu-new-template'){
					if(isset($_REQUEST['template'])) { return '';}
					
				}else{
					return '';
				}
				$finalLayoutData = array();
				$blog_id = get_current_blog_id();
				
				switch_to_blog($blog_id);
					
					$results = $wpdb->get_results('SELECT option_name,option_value FROM ' . $table_prefix . 'options WHERE (option_name LIKE "%options_create_site_layout%") or (option_name LIKE "%layout_category%")');					
														
				restore_current_blog();
				
				if(!empty($results)) {
					foreach($results as $fieldkey) {
							 $fieldkey_ce = $fieldkey->option_name;
							 $fieldval_options = $fieldkey->option_value;	 
							 $finalLayoutData[$fieldkey_ce] = $fieldval_options;
						 }
				} 
				
				//~ echo '<pre/>';
				//~ print_r($results);
				//~ print_r($finalLayoutData);
			
				$jsonArr = json_encode($finalLayoutData);
				
					//insert data into settings table
					switch_to_blog(1);
						$wp_tbl = $table_prefix.'bzce_settings';
					restore_current_blog();
					
					$site = $this->getceSettings($blog_id);   //get ce settings
					if(count($site) > 0){
					
						$wpdb->update( 
							$wp_tbl, 
							array( 
								'ceContent' => $jsonArr,
								'updated' => '1',

							), 
							array( 'siteid' => $blog_id ), 
							array( 
								'%s',
								'%d'
							), 
							array( '%d' ) 
						);
					}else{
						
						 $data = array( 
							'siteid' => $blog_id,
							'ceContent' => $jsonArr,
							'updated' => '0',
						  );
						  $wpdb->insert( 
										$wp_tbl , 
										$data
						  );
						  $insertid = $wpdb->insert_id;
					 }	
		}
		
		
		
		/**
		* Get saved site settings by Blog id
		*/
		public function getceSettings( $siteid=0 ) {
			global $wpdb,$table_prefix;
			if($siteid < 1){
				$siteid = 1;
			}
			switch_to_blog(1);
			$wp_tbl = $table_prefix.'bzce_settings';
			restore_current_blog();
			$query = "select * from $wp_tbl where siteid = '".$siteid."'";
			$results = $wpdb->get_results($query,ARRAY_A);
			if($results){
				return $results;
			}
			return array(); 
		}
		
		
			
		
		/**
		* Delete options from Blog 
		*/
		public function deleteCeSettings( $siteid=0 ) {
			global $wpdb,$table_prefix;
			if($siteid < 1){
				$siteid = 1;
			}
			switch_to_blog($siteid);
			$wp_tbl = $table_prefix.'options';
			restore_current_blog();
			$query = "Delete from $wp_tbl WHERE (`option_name` LIKE '%options_create_site_layout%') or (`option_name` LIKE '%options__create_site_layout%')or (`option_name` LIKE '%layout_category%')";
			$results = $wpdb->query($query);
		}
		
		
		
		
		/**
		 * Save settings when user switches template (WP ULTIMO HOOK)
		*/
		public function preserve_content_editor_template_settings( $site_id, $plan = null) {
				$refresh = true;
				switch_to_blog(1);
				$templateCheck = get_option('templateCheck'.$site_id);
				update_option ('templateCheck'.$site_id, 1);
				restore_current_blog();		
		}
		
		
		
		
		/**
		 * Create field structure to Layouts 
		*/
		public function ce_add_setting_groups() {
			
			
			if ( is_multisite() && is_plugin_active_for_network('blitz-content-editor-pro/blitz-content-editor-pro.php') ) {

			$blog_id = get_current_blog_id(); //current blog ID
			
			if($blog_id != 1 ) {
				
				global $wpdb;
				switch_to_blog(1);
				
				$sitecatoptions = $this->ceadminSettings->getCatSite($blog_id);			
				if(!empty($sitecatoptions)) {
					$currentCat =  $sitecatoptions[0]['catID'];
					$enableSh	=  $sitecatoptions[0]['enableShort'];
					$enableRows =  $sitecatoptions[0]['enableRowsedit'];
				}
				restore_current_blog();		
				
				if(!empty($sitecatoptions)) {
				$currentCat =  $sitecatoptions[0]['catID'];
				} else {
				$currentCat =1;
				}
				
				if( $currentCat != 1 ) {
					
					//get dynamic layout ID
					if($currentCat == ''){
						
						$errorMessage = 'Category not assigned to this site!!';
						update_option( 'error_message', $errorMessage);
						return;
						
					} else {
						
					   $layoutID = $this->getLayoutID($currentCat);
					}
					
					//Get Layout Data
					if($layoutID == ''){
						
						$errorMessage = 'No layout found with defualt category!!';
						update_option( 'error_message', $errorMessage);
						return;
						
					} else {
						
						$fieldData = $this->getData($layoutID); //static layout ID
					}
					
					//Check if Layout Created
					if(empty($fieldData)){
						$errorMessage = 'No layout created for assigned category!!';
						update_option( 'error_message', $errorMessage);
						return;
					} else {
						//Remove extra meta fields
						foreach ($fieldData as $index => $data) {
							if ($index == '_edit_lock' || $index == '_edit_last') {
							unset($fieldData[$index]);
							}
						}
						update_option( 'error_message', '');
					}
					
					
					//Check if Shortcodes Enable for current Blog
					if($enableSh != '' ) {
						if($enableSh == 1) {
							$this->loadOptionsStyles($blog_id,'shortcode');
						} else {
							$this->loadOptionsStyles1($blog_id,'shortcode');
						}
					}
					
					//Check if Add Rows Enable for current Blog
					if($enableRows != '' ) {
						if($enableRows == 1) {
							$this->loadOptionsStyles($blog_id,'rows');
						} 
					}
					
					switch_to_blog($blog_id); //switch to current blog
					
					$option_values = $wpdb->get_results("SELECT option_name,option_value FROM wp_" . $blog_id . "_options WHERE option_name LIKE '%options_create_site_layout%' ");
					
					$optionsValuesArr = array();
					$fieldval_options = array();
					$cnt=0;
				
					if(!empty($option_values)) {
							
						foreach($option_values as $fieldkey => $fieldval) {
							
							 $fieldkey_db = $option_values[$fieldkey]->option_name;
							 $fieldval_options = $option_values[$fieldkey]->option_value;	 
							 $fieldval_values[0]=$fieldval_options;
							 if (strpos($fieldkey_db, 'options_') !== false) {
								$fieldkey_db = str_replace('options_','',$fieldkey_db);
							 } else if (strpos($fieldkey_db, '_options_') !== false) {
								$fieldkey_db = str_replace('_options_','',$fieldkey_db); 
							 }
							$optionsValuesArr[$fieldkey_db] = $fieldval_values;
						}
						
					} else {
													
						$cnt=0;		
						foreach($fieldData as $fieldkey_lc => $fieldval1) {
							
							if($fieldkey_lc == 'create_site_layout' || $this->endsWith($fieldkey_lc,'_create_layout') ){
								$create_site_layout = get_field($fieldkey_lc, 'option');
								if($create_site_layout) {
								$fieldval_optionserialized = serialize($create_site_layout);
								$fieldval_options1 = $fieldval_optionserialized;
								}
							} else {
								$fieldval_options1 = get_field($fieldkey_lc, 'option');
							}
							if($fieldval_options1){
								$fieldval_values1[0]=$fieldval_options1;
								$optionsValuesArr[$fieldkey_lc] = $fieldval_values1;
							} 
							
							$cnt++;
						}
					}
					
					$postLayoutData = count($fieldData) ;
					$singleoptionData = count($optionsValuesArr);		
					if($singleoptionData > 0) {
					 $singleoptionData = $singleoptionData+1;				
					}
					 //~ echo $singleoptionData;
					
					//~ echo '<pre/>';
					//~ print_r($fieldData);
					//~ print_r($optionsValuesArr);
					//~ die;
					
					$finalArr='';
					if($postLayoutData != '' && $singleoptionData == '0' ) {   //first update
											
							
							//update layout for the first time only
							$option_exists = get_field('create_site_layout', 'option');
							if($option_exists) {
								if(!empty($optionsValuesArr)) { 
									$finalArr = $optionsValuesArr;
								}
								update_option( 'layout_from_db', '1');
							} else {	
								if(!empty($fieldData)) { 
									$finalArr = $fieldData;
								}
								update_option( 'layout_from_db', '0');
							}
							update_option( 'layout_changed', '0');
							
							
					} else if($postLayoutData == $singleoptionData) {   //when equal update
						
							
								if(!empty($optionsValuesArr)) {
								$finalArr = $optionsValuesArr;
								}
								update_option( 'layout_from_db', '1');
								update_option( 'layout_changed', '0');
								
						
					} else {
							if (array_key_exists('create_site_layout',$optionsValuesArr)) {
							

								update_option( 'layout_changed', '1');
							
								if(isset($_POST['loadlayout'])) {
								
								$loadLayout = $_POST['loadlayout'];
								
								if($loadLayout == 'yes') {
								
									//update layout for the first time only
									if(!empty($fieldData)) { 
										$respone=1;
										$finalArr = $fieldData; 
										$optiondeleted = $wpdb->query("DELETE FROM wp_" . $blog_id . "_options WHERE option_name LIKE '%options_create_site_layout%' ");
										$optiondeleted1 = $wpdb->query("DELETE FROM wp_" . $blog_id . "_options WHERE option_name LIKE '%_options_create_site_layout%' ");

										if($optiondeleted) {
											if ($this->updateFieldstoDB( $fieldData)) {   //insert update
												echo 'here'.$response;
											}
										}
									}

								} else {
									if(!empty($optionsValuesArr)) { 
										$finalArr = $optionsValuesArr; 
									}
								}
							
							 } else {
								if(!empty($optionsValuesArr)) {
									$finalArr = $optionsValuesArr;
								}
							 }
							 
						 } else {
							 
							 $finalArr = $fieldData;
						 }
					} 
					
					//~ print_r($finalArr);
					//~ die;
					
					
					
						//Update acf fields data to Options of Site Owner
						$k=0;
						if(!empty($finalArr)) {
							
						foreach($finalArr as $fieldkey2 => $fieldval2) {
											
							foreach($fieldval2 as $fieldFinalVal) {
								
								if($fieldkey2 == 'create_site_layout') {
									if(is_array($fieldFinalVal)) {
									$blocksOrder = $fieldFinalVal;
									} else {
									$blocksOrder = unserialize($fieldFinalVal);
									}
									// print_r($blocksOrder);
									$i=0;
									foreach($blocksOrder as $blockOrder) {
										
										if(get_field('layout_from_db', 'option') != 1) {   //only unset when loaded from layout for the first time
											$enable_sec =  $finalArr['create_site_layout_'.$i.'_enable'][0];
											if($enable_sec != 1) {
												unset($blocksOrder[$i]);        
											}
										}
										
										
									$i++;
									}
								update_option( 'options_'.$fieldkey2, $blocksOrder);	
									
								} else if($this->endsWith($fieldkey2,'_create_layout')) {
									if(is_array($fieldFinalVal)) {
									$innerblocksOrder = $fieldFinalVal;
									} else {
									$innerblocksOrder = unserialize($fieldFinalVal);
									}
									update_option( 'options_'.$fieldkey2, $innerblocksOrder);	
									
								} else if (substr($fieldkey2, 0, 1) != '_') {
									
									
									if (strpos($fieldkey2, '_medimage') !== false || strpos($fieldkey2, '_contentimage') !== false || strpos($fieldkey2, '_contentimage1') !== false || strpos($fieldkey2, '_contentimage2') !== false || strpos($fieldkey2, '_contentimage3') !== false || strpos($fieldkey2, '_testiimage') !== false) {
										
										if($fieldFinalVal != '') {			
										$file =  get_attached_file($fieldFinalVal);
										
										switch_to_blog(1);
										if ( !file_exists( $file ) ) {$media_inserted_ID = $this->mediaSettings->insert_media_file_into_sites($fieldFinalVal, $blog_id); //function to call while adding media to Another sites[LATER]
										$fieldFinalVal1 = $media_inserted_ID;
										} else {
											$fieldFinalVal1 = $fieldFinalVal;
										}
										restore_current_blog();
										
										update_option( 'options_'.$fieldkey2, $fieldFinalVal1);	
										
										} else {
										update_option( 'options_'.$fieldkey2, $fieldFinalVal);	
										}
										

									} else {
										update_option( 'options_'.$fieldkey2, $fieldFinalVal);	
									}
									
								} else {
										update_option( 'options_'.$fieldkey2, $fieldFinalVal);
								}
							}
						$k++;
						} 
					} 
					restore_current_blog();
				
				}   
			  }
			}
			
			if(isset($_GET['post'])) { $postID = $_GET['post']; } else { $postID = 'option'; }  //current ID
				
					global $wpdb;
				switch_to_blog(1);
				
				$sitecatoptions = $this->ceadminSettings->getCatSite($blog_id);			
				if(isset($sitecatoptions)) {
					$currentCat =  $sitecatoptions[0]['catID'];
					$enableSh	=  $sitecatoptions[0]['enableShort'];
					$enableRows =  $sitecatoptions[0]['enableRowsedit'];
				} 
				restore_current_blog();	
			
			
			/* Richtext Block */
			$hidden_group = new CEACFGroup();
			$hidden_group->enableShortCode(true);
			
			
			
			if( $blog_id != 1) {
				$hidden_group->add('enable_short', 'Enable shortcodes', 'hidden', array('width'=>'100%'),'',$postID);
			}
			
			
			/* Richtext Block */
			$rch_group = new CEACFGroup();
			$rch_group->enableShortCode(true);
			
			$rch_group->key('rich_text')->title('Rich Textarea Block')->position('acf_after_title')->instruction_placement('field');
			if ( is_super_admin() || $enableSh==1 ) {
			$richtext_subfield = array(array('key'=>'shDisable_richtext','label'=>'Shortcode?','name'=>'shDisable_richtext','type'=>'true_false','instructions' =>  '','default_value' => '0'),array('key'=>'richtext','label'=>'RichText Editor','name'=>'richtext','type'=>'wysiwyg','instructions' =>  '<small>['. ( CEACFGroup::$ceshortcode_identifier) .' item='.$postID.']</small>'));
		} else {
			
			$richtext_subfield = array(array('key'=>'richtext','label'=>'RichText Editor','name'=>'richtext','type'=>'wysiwyg'));
			
		}
			$rch_group->add('add_rich_text_area', 'Add Rich Text Area', 'repeater', array('width'=>'100%'),$richtext_subfield,$postID);
			
			
			
			
			/* SpinTax Block */
			$spn_group = new CEACFGroup();
			$spn_group->enableShortCode(true);
			
			$spn_group->key('spin_text')->title('SpinTax Block')->position('acf_after_title')->instruction_placement('field');
			if ( is_super_admin() || $enableSh==1 ) {
			$spin_subfield = array(array('key'=>'shDisable_spintax','label'=>'Shortcode?','name'=>'shDisable_spintax','type'=>'true_false','instructions' =>  '','default_value' => '0'),array('key'=>'paragraph','label'=>'Paragraph','name'=>'paragraph','type'=>'textarea','instructions' =>  '<small>['. ( CEACFGroup::$ceshortcode_identifier) .' type=spintex item='.$postID.']</small>'));
		} else{
			$spin_subfield = array(array('key'=>'paragraph','label'=>'Paragraph','name'=>'paragraph','type'=>'textarea'));
		}
			$spn_group->add('add_paragraph', 'Add Paragraph', 'repeater', array('width'=>'100%'),$spin_subfield,$postID);
		
			  
			
			
			/* Media Block */
			$med_group = new CEACFGroup();
			$med_group->enableShortCode(true);
			
			$med_group->key('media_block')->title('Media Block')->position('acf_after_title')->instruction_placement('field');
			if ( is_super_admin() || $enableSh==1 ) {
			$med_subfield = array(array('key'=>'shDisable_media','label'=>'Shortcode?','name'=>'shDisable_media','type'=>'true_false','instructions' =>  '','default_value' => '0'),array('key'=>'medimage','label'=>'Image','name'=>'medimage','type'=>'image','instructions' =>  '<small>['. ( CEACFGroup::$ceshortcode_identifier) .' type=medimage item='.$postID.' id="{media_id}" url="{media_url}"]</small>'));
		} else {
			$med_subfield = array(array('key'=>'medimage','label'=>'Image','name'=>'medimage','type'=>'image'));
			
		}
			$med_group->add('add_media', 'Add Media', 'repeater', array('width'=>'100%'),$med_subfield,$postID);
			
			
			
			
			/* Stat Block */
			$stat_group = new CEACFGroup();
			$stat_group->enableShortCode(true);
			
			$stat_group->key('stat_block')->title('Stat Block')->position('acf_after_title')->instruction_placement('field');
			if ( is_super_admin() || $enableSh==1 ) {
			$stat_subfield = array(array('key'=>'shDisable_statTitle','label'=>'Shortcode?','name'=>'shDisable_statTitle','type'=>'true_false','instructions' =>  '','default_value' => '0','wrapper' => array('width'=>'50%')),array('key'=>'shDisable_statNum','label'=>'Shortcode?','name'=>'shDisable_statNum','type'=>'true_false','instructions' =>  '','wrapper' => array('width'=>'50%'),'default_value' => '0'),array('key'=>'stattitle','label'=>'Stat Title','name'=>'stattitle','type'=>'text','wrapper' => array('width'=>'50%'),'instructions' =>  '<small>['. ( CEACFGroup::$ceshortcode_identifier) .' type=stattitle item='.$postID.']</small>'),array('key'=>'statnum','label'=>'Stat Number','name'=>'statnum','type'=>'range','wrapper' => array('width'=>'50%'),'instructions' =>  '<small>['. ( CEACFGroup::$ceshortcode_identifier) .' type=statnum item='.$postID.']</small>'));
		} else {
			
			$stat_subfield = array(
			array('key'=>'stattitle','label'=>'Stat Title','name'=>'stattitle','type'=>'text','wrapper' => array('width'=>'50%')),array('key'=>'statnum','label'=>'Stat Number','name'=>'statnum','type'=>'range','wrapper' => array('width'=>'50%')));
			
		}
			
			$stat_group->add('add_stat', 'Add Stat', 'repeater', array('width'=>'100%'),$stat_subfield,$postID);
			
			
			
			
			/* Content Block */
			$content_group = new CEACFGroup();
			$content_group->enableShortCode(true);
			
			$content_group->key('content_block')->title('Content Block')->position('acf_after_title')->instruction_placement('field');
			if ( is_super_admin() || $enableSh==1 ) {
			$content_subfield = array(
			
			array('key'=>'shDisable_contenttitle','label'=>'Shortcode?','name'=>'shDisable_contenttitle','type'=>'true_false','instructions' =>  '','wrapper' => array('width'=>'33%'),'default_value' => '0'),
			array('key'=>'shDisable_contenticon','label'=>'Shortcode?','name'=>'shDisable_contenticon','type'=>'true_false','instructions' =>  '','wrapper' => array('width'=>'33%'),'default_value' => '0'),
			array('key'=>'shDisable_contentimage','label'=>'Shortcode?','name'=>'shDisable_contentimage','type'=>'true_false','instructions' =>  '','wrapper' => array('width'=>'33%'),'default_value' => '0'),
			
			
			array('key'=>'contenttitle','label'=>'Title','name'=>'contenttitle','type'=>'text','instructions' =>  '<small>['. ( CEACFGroup::$ceshortcode_identifier) .' type=contenttitle item='.$postID.']</small>','wrapper' => array('width'=>'33%')),
			
			array('key'=>'contenticon','label'=>'Icon','name'=>'contenticon','type'=>'font-awesome','instructions' =>  '<small>['. ( CEACFGroup::$ceshortcode_identifier) .' type=contenticon item='.$postID.']</small>','wrapper' => array('width'=>'33%')),
			
			array('key'=>'contentimage','label'=>'Image','name'=>'contentimage','type'=>'image','instructions' =>  '<small>['. ( CEACFGroup::$ceshortcode_identifier) .' type=contentimage item='.$postID.']</small>','wrapper' => array('width'=>'33%')),
			
			array('key'=>'shDisable_contenttext','label'=>'Shortcode?','name'=>'shDisable_contenttext','type'=>'true_false','instructions' =>  '','default_value' => '0'),


			array('key'=>'contenttext','label'=>'Content','name'=>'contenttext','type'=>'textarea','instructions' =>  '<small>['. ( CEACFGroup::$ceshortcode_identifier) .' type=contenttext item='.$postID.']</small>','wrapper' => array('width'=>'100%')),
			
			array('key'=>'disableTitles','label'=>'Disable Titles?','name'=>'disableTitles','type'=>'true_false','wrapper' => array('width'=>'25%'),'ui'=>'1','default_value' => 1),
			array('key'=>'disableButtons','label'=>'Disable Buttons?','name'=>'disableButtons','type'=>'true_false','wrapper' => array('width'=>'25%'),'ui'=>'1','default_value' => 1),
			array('key'=>'disableImages','label'=>'Disable Images?','name'=>'disableImages','type'=>'true_false','wrapper' => array('width'=>'25%'),'ui'=>'1','default_value' => 1),
			array('key'=>'disableIcons','label'=>'Disable Icons?','name'=>'disableIcons','type'=>'true_false','wrapper' => array('width'=>'25%'),'ui'=>'1','default_value' => 1),
			
			
			array('key'=>'shDisable_titles','label'=>'Shortcode?','name'=>'shDisable_titles','type'=>'true_false','instructions' =>  '','wrapper' => array('width'=>'100%'),'conditional_logic' => array(array(array('field' => 'disableTitles','operator' => '==','value' => '0',),),),'default_value' => '0'),

			
			array('key'=>'contenttitle1','label'=>'Title1','name'=>'contenttitle1','type'=>'text','instructions' =>  '<small>['. ( CEACFGroup::$ceshortcode_identifier) .' type=contenttitle1 item='.$postID.']</small>','wrapper' => array('width'=>'33%'),'conditional_logic' => array(array(array('field' => 'disableTitles','operator' => '==','value' => '0',),),),),
			
			array('key'=>'contenttitle2','label'=>'Title2','name'=>'contenttitle2','type'=>'text','instructions' =>  '<small>['. ( CEACFGroup::$ceshortcode_identifier) .' type=contenttitle2 item='.$postID.']</small>','wrapper' => array('width'=>'33%'),'conditional_logic' => array(array(array('field' => 'disableTitles','operator' => '==','value' => '0',),),),),
			
			array('key'=>'contenttitle3','label'=>'Title3','name'=>'contenttitle3','type'=>'text','instructions' =>  '<small>['. ( CEACFGroup::$ceshortcode_identifier) .' type=contenttitle3 item='.$postID.']</small>','wrapper' => array('width'=>'33%'),'conditional_logic' => array(array(array('field' => 'disableTitles','operator' => '==','value' => '0',),),),),
			
			
			array('key'=>'shDisable_buttons','label'=>'Shortcode?','name'=>'shDisable_buttons','type'=>'true_false','instructions' =>  '','wrapper' => array('width'=>'100%'),'conditional_logic' => array(array(array('field' => 'disableButtons','operator' => '==','value' => '0',),),),'default_value' => '0'),
						
			array('key'=>'buttontitle1','label'=>'Button Text1','name'=>'buttontitle1','type'=>'text','instructions' =>  '<small>['. ( CEACFGroup::$ceshortcode_identifier) .' type=buttontitle1 item='.$postID.']</small>','wrapper' => array('width'=>'33%'),'conditional_logic' => array(array(array('field' => 'disableButtons','operator' => '==','value' => '0',),),),),
			
			array('key'=>'buttontitle2','label'=>'Button Text2','name'=>'buttontitle2','type'=>'text','instructions' =>  '<small>['. ( CEACFGroup::$ceshortcode_identifier) .' type=buttontitle2 item='.$postID.']</small>','wrapper' => array('width'=>'33%'),'conditional_logic' => array(array(array('field' => 'disableButtons','operator' => '==','value' => '0',),),),),
			
			array('key'=>'buttontitle3','label'=>'Button Text3','name'=>'buttontitle3','type'=>'text','instructions' =>  '<small>['. ( CEACFGroup::$ceshortcode_identifier) .' type=buttontitle3 item='.$postID.']</small>','wrapper' => array('width'=>'33%'),'conditional_logic' => array(array(array('field' => 'disableButtons','operator' => '==','value' => '0',),),),),
			
			
			array('key'=>'shDisable_images','label'=>'Shortcode?','name'=>'shDisable_images','type'=>'true_false','instructions' =>  '','wrapper' => array('width'=>'100%'),'conditional_logic' => array(array(array('field' => 'disableImages','operator' => '==','value' => '0',),),),'default_value' => '0'),
						
			array('key'=>'contentimage1','label'=>'Image1','name'=>'contentimage1','type'=>'image','instructions' =>  '<small>['. ( CEACFGroup::$ceshortcode_identifier) .' type=contentimage1 item='.$postID.']</small>','wrapper' => array('width'=>'33%'),'conditional_logic' => array(array(array('field' => 'disableImages','operator' => '==','value' => '0',),),),),
			
			array('key'=>'contentimage2','label'=>'Image2','name'=>'contentimage2','type'=>'image','instructions' =>  '<small>['. ( CEACFGroup::$ceshortcode_identifier) .' type=contentimage2 item='.$postID.']</small>','wrapper' => array('width'=>'33%'),'conditional_logic' => array(array(array('field' => 'disableImages','operator' => '==','value' => '0',),),),),
			
			array('key'=>'contentimage3','label'=>'Image3','name'=>'contentimage3','type'=>'image','instructions' =>  '<small>['. ( CEACFGroup::$ceshortcode_identifier) .' type=contentimage3 item='.$postID.']</small>','wrapper' => array('width'=>'33%'),'conditional_logic' => array(array(array('field' => 'disableImages','operator' => '==','value' => '0',),),),),
			
			
			array('key'=>'shDisable_icons','label'=>'Shortcode?','name'=>'shDisable_icons','type'=>'true_false','instructions' =>  '','wrapper' => array('width'=>'100%'),'conditional_logic' => array(array(array('field' => 'disableIcons','operator' => '==','value' => '0',),),),'default_value' => '0'),
						
			array('key'=>'contenticon1','label'=>'Icon1','name'=>'contenticon1','type'=>'font-awesome','instructions' =>  '<small>['. ( CEACFGroup::$ceshortcode_identifier) .' type=contenticon1 item='.$postID.']</small>','wrapper' => array('width'=>'33%'),'conditional_logic' => array(array(array('field' => 'disableIcons','operator' => '==','value' => '0',),),),),
			
			array('key'=>'contenticon2','label'=>'Icon2','name'=>'contenticon2','type'=>'font-awesome','instructions' =>  '<small>['. ( CEACFGroup::$ceshortcode_identifier) .' type=contenticon2 item='.$postID.']</small>','wrapper' => array('width'=>'33%'),'conditional_logic' => array(array(array('field' => 'disableIcons','operator' => '==','value' => '0',),),),),
			
			array('key'=>'contenticon3','label'=>'Icon3','name'=>'contenticon3','type'=>'font-awesome','instructions' =>  '<small>['. ( CEACFGroup::$ceshortcode_identifier) .' type=contenticon3 item='.$postID.']</small>','wrapper' => array('width'=>'33%'),'conditional_logic' => array(array(array('field' => 'disableIcons','operator' => '==','value' => '0',),),),)
			
			);
		} else {
$content_subfield = array(
			array('key'=>'contenttitle','label'=>'Title','name'=>'contenttitle','type'=>'text','wrapper' => array('width'=>'33%')),
			
			array('key'=>'contenticon','label'=>'Icon','name'=>'contenticon','type'=>'font-awesome','wrapper' => array('width'=>'33%')),
			
			array('key'=>'contentimage','label'=>'Image','name'=>'contentimage','type'=>'image','wrapper' => array('width'=>'33%')),
			
			array('key'=>'contenttext','label'=>'Content','name'=>'contenttext','type'=>'textarea','wrapper' => array('width'=>'100%')),
			
			array('key'=>'disableTitles','label'=>'Disable Titles?','name'=>'disableTitles','type'=>'true_false','wrapper' => array('width'=>'25%'),'ui'=>'1','default_value' => 1),
			array('key'=>'disableButtons','label'=>'Disable Buttons?','name'=>'disableButtons','type'=>'true_false','wrapper' => array('width'=>'25%'),'ui'=>'1','default_value' => 1),
			array('key'=>'disableImages','label'=>'Disable Images?','name'=>'disableImages','type'=>'true_false','wrapper' => array('width'=>'25%'),'ui'=>'1','default_value' => 1),
			array('key'=>'disableIcons','label'=>'Disable Icons?','name'=>'disableIcons','type'=>'true_false','wrapper' => array('width'=>'25%'),'ui'=>'1','default_value' => 1),

			array('key'=>'contenttitle1','label'=>'Title1','name'=>'contenttitle1','type'=>'text','wrapper' => array('width'=>'33%'),'conditional_logic' => array(array(array('field' => 'disableTitles','operator' => '==','value' => '0',),),),),
			
			array('key'=>'contenttitle2','label'=>'Title2','name'=>'contenttitle2','type'=>'text','wrapper' => array('width'=>'33%'),'conditional_logic' => array(array(array('field' => 'disableTitles','operator' => '==','value' => '0',),),),),
			
			array('key'=>'contenttitle3','label'=>'Title3','name'=>'contenttitle3','type'=>'text','wrapper' => array('width'=>'33%'),'conditional_logic' => array(array(array('field' => 'disableTitles','operator' => '==','value' => '0',),),),),

						
			array('key'=>'buttontitle1','label'=>'Button Text1','name'=>'buttontitle1','type'=>'text','wrapper' => array('width'=>'33%'),'conditional_logic' => array(array(array('field' => 'disableButtons','operator' => '==','value' => '0',),),),),
			
			array('key'=>'buttontitle2','label'=>'Button Text2','name'=>'buttontitle2','type'=>'text','wrapper' => array('width'=>'33%'),'conditional_logic' => array(array(array('field' => 'disableButtons','operator' => '==','value' => '0',),),),),
			
			array('key'=>'buttontitle3','label'=>'Button Text3','name'=>'buttontitle3','type'=>'text','wrapper' => array('width'=>'33%'),'conditional_logic' => array(array(array('field' => 'disableButtons','operator' => '==','value' => '0',),),),),
						
			array('key'=>'contentimage1','label'=>'Image1','name'=>'contentimage1','type'=>'image','wrapper' => array('width'=>'33%'),'conditional_logic' => array(array(array('field' => 'disableImages','operator' => '==','value' => '0',),),),),
			
			array('key'=>'contentimage2','label'=>'Image2','name'=>'contentimage2','type'=>'image','wrapper' => array('width'=>'33%'),'conditional_logic' => array(array(array('field' => 'disableImages','operator' => '==','value' => '0',),),),),
			
			array('key'=>'contentimage3','label'=>'Image3','name'=>'contentimage3','type'=>'image','wrapper' => array('width'=>'33%'),'conditional_logic' => array(array(array('field' => 'disableImages','operator' => '==','value' => '0',),),),),
						
			array('key'=>'contenticon1','label'=>'Icon1','name'=>'contenticon1','type'=>'font-awesome','wrapper' => array('width'=>'33%'),'conditional_logic' => array(array(array('field' => 'disableIcons','operator' => '==','value' => '0',),),),),
			
			array('key'=>'contenticon2','label'=>'Icon2','name'=>'contenticon2','type'=>'font-awesome','wrapper' => array('width'=>'33%'),'conditional_logic' => array(array(array('field' => 'disableIcons','operator' => '==','value' => '0',),),),),
			
			array('key'=>'contenticon3','label'=>'Icon3','name'=>'contenticon3','type'=>'font-awesome','wrapper' => array('width'=>'33%'),'conditional_logic' => array(array(array('field' => 'disableIcons','operator' => '==','value' => '0',),),),)
			
			);
			
		}

			$content_group->add('add_content', 'Add Content', 'repeater', array('width'=>'100%','layout' => 'block'),$content_subfield,$postID);
			
			
			
			/* Testimonial Block */
			$testimonial_group = new CEACFGroup();
			$testimonial_group->enableShortCode(true);
			
			$testimonial_group->key('testimonial_block')->title('Testimonial Block')->position('acf_after_title')->instruction_placement('field');
			if ( is_super_admin() || $enableSh==1 ) {
			$testimonial_subfield = array(array('key'=>'shDisable_testiname','label'=>'Shortcode?','name'=>'shDisable_testiname','type'=>'true_false','instructions' =>  '','wrapper' => array('width'=>'25%'),'default_value' => '0'),array('key'=>'shDisable_testijob','label'=>'Shortcode?','name'=>'shDisable_testijob','type'=>'true_false','instructions' =>  '','wrapper' => array('width'=>'25%'),'default_value' => '0'),array('key'=>'shDisable_testiicon','label'=>'Shortcode?','name'=>'shDisable_testiicon','type'=>'true_false','instructions' =>  '','wrapper' => array('width'=>'25%'),'default_value' => '0'),array('key'=>'shDisable_testiimage','label'=>'Shortcode?','name'=>'shDisable_testiimage','type'=>'true_false','instructions' =>  '','wrapper' => array('width'=>'25%'),'default_value' => '0'),array('key'=>'testiname','label'=>'Name','name'=>'testiname','type'=>'text','instructions' =>  '<small>['. ( CEACFGroup::$ceshortcode_identifier) .' type=testiname item='.$postID.']</small>','wrapper' => array('width'=>'25%')),array('key'=>'testijob','label'=>'Job','name'=>'testijob','type'=>'text','instructions' =>  '<small>['. ( CEACFGroup::$ceshortcode_identifier) .' type=testijob item='.$postID.']</small>','wrapper' => array('width'=>'25%')),array('key'=>'testiicon','label'=>'Icon','name'=>'testiicon','type'=>'font-awesome','instructions' =>  '<small>['. ( CEACFGroup::$ceshortcode_identifier) .' type=testiicon item='.$postID.']</small>','wrapper' => array('width'=>'25%')),array('key'=>'testiimage','label'=>'Image','name'=>'testiimage','type'=>'image','instructions' =>  '<small>['. ( CEACFGroup::$ceshortcode_identifier) .' type=testiimage item='.$postID.']</small>','wrapper' => array('width'=>'25%')),array('key'=>'shDisable_testicontent','label'=>'Shortcode?','name'=>'shDisable_testicontent','type'=>'true_false','instructions' =>  '','default_value' => '0'),array('key'=>'testicontent','label'=>'Content','name'=>'testicontent','type'=>'textarea','instructions' =>  '<small>['. ( CEACFGroup::$ceshortcode_identifier) .' type=testicontent item='.$postID.']</small>','wrapper' => array('width'=>'100%'),'default_value' => '0'));
		} else {
			
				$testimonial_subfield = array(
				array('key'=>'testiname','label'=>'Name','name'=>'testiname','type'=>'text','wrapper' => array('width'=>'25%')),array('key'=>'testijob','label'=>'Job','name'=>'testijob','type'=>'text','wrapper' => array('width'=>'25%')),
				array('key'=>'testiicon','label'=>'Icon','name'=>'testiicon','type'=>'font-awesome','wrapper' => array('width'=>'25%')),
				array('key'=>'testiimage','label'=>'Image','name'=>'testiimage','type'=>'image','wrapper' => array('width'=>'25%')),
				array('key'=>'testicontent','label'=>'Content','name'=>'testicontent','type'=>'textarea','wrapper' => array('width'=>'100%'),'default_value' => '0'));
			
		}
			$testimonial_group->add('add_testimonial', 'Add Testimonial', 'repeater', array('width'=>'100%','layout' => 'block'),$testimonial_subfield,$postID);
	
	
	
			/* Flexible Content Layouts*/
			$rch_layout	= CEACFGroup::add_flexiblelayout('richtextBlock','Rich Textarea Block', $rch_group->get()['fields'],  'left');	
			$spn_layout	= CEACFGroup::add_flexiblelayout('spintaxBlock','SpinTax Block', $spn_group->get()['fields'],  'left');	
			$med_layout	= CEACFGroup::add_flexiblelayout('mediaBlock','Media Block', $med_group->get()['fields'],  'left');	
			$stat_layout= CEACFGroup::add_flexiblelayout('statBlock','Stat Block', $stat_group->get()['fields'],  'left');	
			$content_layout= CEACFGroup::add_flexiblelayout('contentBlock','Content Block', $content_group->get()['fields'],  'left');	
			$testimonial_layout= CEACFGroup::add_flexiblelayout('testimonialBlock','Testimonial Block', $testimonial_group->get()['fields'],  'left');	
			
			
			$allLayouts = array_merge($rch_layout,$spn_layout,$med_layout,$stat_layout,$content_layout,$testimonial_layout);
			
			
			
			/* Site Page Block */
			$sitepage_group = new CEACFGroup();
			$sitepage_group->enableShortCode(true);
			
			$sitepage_group->key('sitepage_block')->title('Add Page')->position('acf_after_title')->instruction_placement('field');
			$sitepage_group->addTrueFalse('enable_page', 'Enable?','enable', 'true_false', array('width'=>'50%'),'',$postID);
			$sitepage_group->add('sitepage_blockname', 'Page Name', 'text', array('width'=>'50%'),'',$postID);
			
			//inner flexible content
			$sitepage_group ->add('create_layout', 'Create Layout', 'flexible_content',array('layouts'=> $allLayouts),'',$postID);		

	

			/* MAIN LAYOUT GROUP */
			$ce_main_group = new CEACFGroup();
			$ce_main_group  ->key('ce_main_group')->title('Layout Group')->menu_order(0);
			
			$create_site_page_layout= CEACFGroup::add_flexiblelayout('sitepageBlock','Add Page', $sitepage_group->get()['fields'],  'left');	
			
			/* Main Flexible Content */
			$ce_main_group ->add('create_site_layout', 'Create Site Layout', 'flexible_content',array('layouts'=> $create_site_page_layout),'',$postID);	

			 	
			acf_add_local_field_group($ce_main_group->get('seamless'));
			
		} 
		 
		
		
		
		/**
		 * Add LAYOUT post type for Developer Purpose
		*/
		public function wporg_custom_post_type(){
			register_post_type('layouts',
							   array(
								   'labels'      => array(
									   'name'          => __('Edit Content'),
									   'singular_name' => __('Layout'),
									   'add_new' => __('Add New Layout'),  
								   ),
								   'public'      => true,
								   'has_archive' => true,
								   'show_in_menu' => true,          
								   'supports' => array('title','thumbnail'),
							   )
			);
		}
		
		
		
		/**
		 * Add Taxonomy to LAYOUT post type
		*/
		public function create_layout_tax() {
			register_taxonomy(
				'layoutscat',
				'layouts',
				array(
					'label' => __( 'Categories' ),
					'rewrite' => array( 'slug' => 'layoutscat' ),
					'hierarchical' => true,
					'public' => true,
					'query_var' => true

				)
			);
		}
		
		  
		
		/**
		 * change Checkboxes to Radio Buttons for Layout Category
		*/
		public function checktoradioLayoutCategory() { 
			echo '<script type="text/javascript">jQuery("#layouts_cat-pop input, #layouts_catchecklist input, .categorychecklist input").each(function(){this.type="radio"});</script>';
		}
		
		
		
		/**
		 * SAVE layout category as META
		*/		
		public function save_layout_callback($post_id){
			global $post;   
			if(isset($post)) {
				if ($post->post_type != 'layouts'){
					return;
				}
			
			
				$term_obj_list = get_the_terms( $post->ID, 'layoutscat' );
				foreach($term_obj_list as $term) {
					$firsttermSelected = $term->term_id;
				}
				update_post_meta( $post->ID, 'layout_category', $firsttermSelected );
			}
			
		}
		
		
		/**
		 * Check if straing ends with
		*/		
		public function endsWith($currentString, $target) {
			
			$length = strlen($target);
			if ($length == 0) {
				return true;
			}
		 
			return (substr($currentString, -$length) === $target);
		}

		
		
		/**
		 * Update fields to options
		*/		
		public function updateFieldstoDB($fieldsArray=array()) {
			
				//~ //Update acf fields data to Options of Site Owner
				$k=0;
				if(!empty($fieldsArray)) {
					
				foreach($fieldsArray as $fieldkey3 => $fieldval3) {
									
					foreach($fieldval3 as $fieldFinalVal1) {
					
						if($fieldkey3 == 'create_site_layout') {
							$blocksOrder1 = unserialize($fieldFinalVal1);
							$i=0;
							foreach($blocksOrder1 as $blockOrder1) {
								
								if(get_option('layout_from_db') != 1) {   //only unset when loaded from layout for the first time
									$enable_sec1 =  $fieldsArray['create_site_layout_'.$i.'_enable'][0];
									if($enable_sec1 != 1) {
										unset($blocksOrder1[$i]);        
									}
								}
							$i++;
							}
						update_option('options_'.$fieldkey3, $blocksOrder1);	
							
						} else if($this->endsWith($fieldkey3,'_create_layout')) {
							$innerblocksOrder1 = unserialize($fieldFinalVal1);
							update_option('options_'.$fieldkey3, $innerblocksOrder1);	
							
						} else if (substr($fieldkey3, 0, 1) != '_') {
							
							
								if (strpos($fieldkey2, '_medimage') !== false || strpos($fieldkey2, '_contentimage') !== false || strpos($fieldkey2, '_contentimage1') !== false || strpos($fieldkey2, '_contentimage2') !== false || strpos($fieldkey2, '_contentimage3') !== false || strpos($fieldkey2, '_testiimage') !== false) {
								
								if($fieldFinalVal1 != '') {			
								$file1 =  get_attached_file($fieldFinalVal1);
								
								switch_to_blog(1);
								if ( !file_exists( $file1 ) ) {$media_inserted_ID1 = $this->mediaSettings->insert_media_file_into_sites($fieldFinalVal1, $blog_id); //function to call while adding media to Another sites[LATER]
								$fieldFinalVal11 = $media_inserted_ID1;
								} else {
									$fieldFinalVal11 = $fieldFinalVal1;
								}
								restore_current_blog();
								
								update_option( 'options_'.$fieldkey3, $fieldFinalVal11);	
								
								} else {
								update_option( 'options_'.$fieldkey3, $fieldFinalVal1);	
								}
								

							} else {
								update_option( 'options_'.$fieldkey3, $fieldFinalVal1);	
							}
							
						} else {
								update_option( 'options_'.$fieldkey3, $fieldFinalVal1);
						}
					}
				$k++;
				} 
			}
		}
		
		
		
		/**
		 * Change Page title from Add page to page name given
		*/		
		public function ce_acf_flexible_content_layout_title( $title, $field, $layout, $i ) {
			
			// remove layout title from text
			$title = '';
			
			if( $blockname = get_sub_field('sitepage_blockname') ) {
				$title .= $blockname;
			} else {
				$title .= 'Add Page';
			}
			// return
			return $title;
			
		}



}
