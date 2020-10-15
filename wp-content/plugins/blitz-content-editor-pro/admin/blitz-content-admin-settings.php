<?php 
require_once(BASE_CE_DIR . 'lmgr/blitz-content-licencemanager.php');
require_once(BASE_CE_DIR . 'lmgr/AutoUpdater.php');

class ceadminSettings{


		public $updated = false;
		public $celmgr;
		public $cepageslug = 'contenteditorsettings';
		public function __construct()
		{
			$this->celmgr = new Blitz_Content_LicenceManager();
			$this->celmgr->init(SL_PRODUCT_ID_CE);
		} 
		
		 
		 		      
		/**
		* Check if license valid
		*/ 
		public function ce_valid_license()
		{return true;
			$currentTime=time(); //current Time
			$cep_license = $this->getOption('cep_license');
			
			if (isset($cep_license) && $cep_license == '' ) {
				
				$lic = $this->celmgr->license_status();
				if ($lic[0]==1) {
					$this->updateOption('cep_license',$currentTime);
					return true;
				} else {
					return false;
				}
				
			} else if (isset($cep_license) && $cep_license != '') {
				$rr = $currentTime - $cep_license;
				$hourdiff = round(($rr)/3600, 1);
				if( $hourdiff >= 24 ) {
						$lic = $this->celmgr->license_status();
					if ($lic[0]==1) {
						$this->updateOption('cep_license',$currentTime);
						$this->updateOption('cep_license_expired','');
						return true;
					} else {
						$this->updateOption('cep_license',$currentTime);
						$this->updateOption('cep_license_expired',1);
						return false;
					}					
				} else {
					$cep_license_expired = $this->getOption('cep_license_expired');
					if (isset($cep_license_expired) && $cep_license_expired != '' ) {
						return false;
					} else {
						$this->updateOption('cep_license_expired','');
						return true;
					}
				}
				
			} else {
				
				return true;
			}	
			
			//~ if (!isset($_COOKIE['cep_license_cookie'])) {
				//~ $lic = $this->celmgr->license_status();
							
				//~ if ($lic[0]==1) {
					//~ setcookie('cep_license_cookie', 'active', strtotime('+7 day'));
					//~ return true;
				//~ } else {
					//~ return false;
				//~ }
			//~ } else {
				//~ return true;
			//~ }	
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
		
		
		
		/**
		* Add options page to network admin
		*/
		public function add_ce_settings_page(){

			  add_action('admin_init', array($this,'ce_update'));
				add_submenu_page(
					'settings.php',
					__('Content Editor PRO', 'my-plugin-domain'),
					__('Content Editor PRO'),
					'manage_options',
					$this->cepageslug,
					array($this, 'ce_admin_screen')
				);	        
		}
		
		
		
		/**
		* Add options page to network admin
		*/
		public function add_ce_ss_settings_page(){
	
			  add_action('admin_init', array($this,'ce_update'));
					
					
					add_submenu_page(
						'options-general.php', //options-general for single site
						__('Content Editor PRO', 'my-plugin-domain'),
						__('Content Editor PRO'),
						'manage_options',
						$this->cepageslug,
						array($this, 'ce_admin_screen')
					);	        
		}



		/**
		* License configuration form
		*/
		public function ce_admin_screen()
		{		
		?>

		<div class="wrap">


		    <h2><?php _e('Content Editor PRO', 'content-editor-settings'); ?></h2>

			<h2 class="nav-tab-wrapper">
				<a href="?page=<?php _e($this->cepageslug , 'content-editor-settings');?>&tab=config" class="nav-tab">Configuration</a>
				<a href="?page=<?php _e($this->cepageslug, 'content-editor-settings');?>&tab=lic" class="nav-tab">License</a>
			</h2>

			 <?php if ( $this->updated ) : ?>
		        <div class="updated notice is-dismissible">
		            <p><?php _e('Settings updated successfully!', 'content-editor-settings'); ?></p>
		        </div>
		    <?php endif; ?>

			<?php 

			
				if ( (isset($_GET['tab']) && $_GET['tab'] == 'config') || !isset($_GET['tab'])) { 
					
					if ($this->ce_valid_license())
					{		
						$this->ce_configuration_form();
					}
					else
					{
						?>

		    			<div>
		    				<p>Please activate the License for Content Editor PRO.</p>
		    			</div>

		    			<?php
					}


				} else if ( (isset($_GET['tab']) && $_GET['tab'] == 'lic') ) {
					$this->celmgr->licenseBlock();
				} 
			?>

			</div>
		<?php 
		}

		
		public function ce_configuration_form() {
			?>

			<form method="post">
				
		    	<?php 		    		
					$ceopt = get_site_option('blitz-ce-sitecat-settings-group');
					if(isset($ceopt['ce_enableacf'])) {
						$enableAcf = $ceopt['ce_enableacf'];
					} else {
						$enableAcf='0';
					}
				?>
	

		        <p>
		            <label>
		                <h3><?php _e('Conent Editor Features:', 'content-editor-settings'); ?></h3>
		                <br/>
		                
               			<div>
               				<?php if ( $enableAcf != '' && $enableAcf != '0' )  { ?>
		                	<input type="checkbox" id="ce_enableacf" name="ce_enableacf"
               				value="0"  <?php echo $enableAcf ? 'checked' : ''  ?>/>
               				
               				<?php } else { ?>
		                	<input type="checkbox" id="ce_enableacf" name="ce_enableacf"
               				value="1"/>
							<?php } ?>
							
               				<label for="ce_enableacf">Enable ACF Menu</label>
               				
               			</div>
               			
		            </label>
		        </p>	        

		        <?php wp_nonce_field('ce_websettings_nonce', 'ce_websettings_nonce'); ?>
		        <?php submit_button(); ?>

		    </form>
		    <?php
		}
		

		/**   
		* Update fields
		*/
		public function ce_update()
		{


			if ( isset($_POST['submit']) ) {


			    // verify authentication (nonce)
			    if ( !isset( $_POST['ce_websettings_nonce'] ) )
			        return;

			    // verify authentication (nonce)
			    if ( !wp_verify_nonce($_POST['ce_websettings_nonce'], 'ce_websettings_nonce') )
			        return;

			    return $this->ce_updateSettings();
			}
		}
		


		/**
		* Update api key & fields
		*/
		public function ce_updateSettings()
		{
			$setting1 = array();

			if ( isset($_POST['apiKey'])) {
			    $setting1['apiKey'] = esc_attr($_POST['apiKey']);
			}

			if ( isset($_POST['ce_enableacf'])) {
			    $setting1['ce_enableacf'] = $_POST['ce_enableacf'];
			}

			if ( $setting1 ) {
			    // update new settings
			    update_site_option('blitz-ce-sitecat-settings-group', $setting1);
			} else {
			    // empty settings, revert back to default
			    delete_site_option('blitz-ce-sitecat-settings-group');
			}
			

			$this->updated = true;			
		}
		
		
		
		public function acfmenuEnabled()
		{
			return ($this->ce_getSettings('ce_enableacf')==null) ? array() : $this->ce_getSettings('ce_enableacf');
		}



		public function ce_getSettings($setting1='')
		{
			global $my_plugin_settings1;

			if ( isset($my_plugin_settings1) ) {
				if ( $setting1 ) {
					return isset($my_plugin_settings1[$setting1]) ? $my_plugin_settings1[$setting1] : null;
				}
				return $my_plugin_settings1;
			}

			$my_plugin_settings1 = wp_parse_args(get_site_option('blitz-ce-sitecat-settings-group'), array(
				'apiKey'=>null,
				'ce_enableacf',
			));

			if ( $setting1 ) {
				return isset($my_plugin_settings1[$setting1]) ? $my_plugin_settings1[$setting1] : null;
			}
			return $my_plugin_settings1;
		}
		
		
		
		/**
		* CEP layout options form
		*/
		public function ce_layout_cat_to_site_form()
		{	
			global $wpdb, $table_prefix;
			echo '<div class="wrap">';
			printf( '<h1>%s</h1>', __('Assign Layouts') ); 
			printf( '<p>%s</p>', __('Please assign layout categories to sites') ); 
			
			
			if(isset($_POST['catsitemapping'])){
				
				$wp_tbl = $table_prefix.'CatSite';
				$error = 0;
				
				$categories = $_POST['selectcat'];
				$sites = $_POST['selectsite'];
			
				$enablesh = $_POST['enablesh'] ;
				$enablerows = $_POST['enablerows'];
				
				//~ print_r($categories);
				//~ print_r($sites);
				
				$cat_rows = count($categories);
				$site_rows = count($sites);
				
				if($cat_rows == $site_rows) {
				
					$rows = $site_rows-1; 
					
					for($i=0; $i <= $rows; $i++) {
						
					$site = $this->getCatSite($sites[$i]);
					
					if(count($site) > 0){
						$q = $wpdb->update( 
							$wp_tbl, 
							array( 
								'catID' => addslashes($categories[$i]),
								'siteID' => addslashes($sites[$i]),
								'enableShort' => addslashes($enablesh[$i]), 
								'enableRowsedit' => addslashes($enablerows[$i])
							), 
							
							array( 'siteID' => addslashes(trim($sites[$i])) ), 
							array( 
								'%s'
							), 
							array( '%d' )
						);
						$insertid = 1;
					}else{
						$data = array( 
							'catID' => addslashes($categories[$i]),
							'siteID' => addslashes($sites[$i]),
							'enableShort' => addslashes($enablesh[$i]),
							'enableRowsedit' => addslashes($enablerows[$i]),
						);
						
						$wpdb->insert( 
							$wp_tbl , 
							$data
						);
						$insertid = $wpdb->insert_id;
					}
					

					}
				}
			 
				if($insertid > 0){
					echo 'Mapping successfully done!';
				}else{
					echo 'Error in mapping!';
				}
							
							
			}
			
			?>
			
			<form method="post" action="" class="mappingSiteCat">
			<?php			
			$taxonomy = 'layoutscat';
            $args = array('hide_empty' => 0);
			$terms = get_terms($taxonomy,$args); // Get all terms of a taxonomy
			
			$records = $this->getCatSiteRows();
			$records_cnt = count($records);
			
			 //print_r($records);  
									
			?>
                <div id="repeater">
                    <div class="repeater-heading">
                        <button class="btn btn-primary pt-5 pull-right repeater-add-btn" type="button">Add More</button>
                    </div>
                    <div class="clearfix"></div>
                    
                   <?php if($records_cnt >= 1 ) {
					  $rc=0;
                   foreach ($records as $record) { ?>
                    
                    <div class="items" data-group="mapping">
                        <!-- Repeater Content -->
                        <div class="item-content col-lg-12">

                            <div class="form-group col-lg-3">
                                <label for="selectSite" class="col-lg-12 control-label">Select Site</label>
                                <div class="col-lg-12">
                                    <select data-name="selectsite" name="selectsite[]" class="form-control" id="selectSite">
                                    <?php
									$sites = get_sites(array('number'=>'500'));   //get all blog sites
									if ( !empty ($sites)) : ?>
									<option value="">Please select</option>
									<?php foreach ( $sites as $site ) {
									$blog_details = get_blog_details( $site->blog_id );
									switch_to_blog( $site->blog_id );
									if( $blog_details->blog_id == $record['siteID']) {	
									?>
									<option value="<?php echo $blog_details->blog_id; ?>" selected="selected"><?php echo $blog_details->blogname; ?></option>
									<?php 
									} else { 
									?>
									<option value="<?php echo $blog_details->blog_id; ?>"><?php echo $blog_details->blogname; ?></option>	
									<?php
									restore_current_blog();
									}
									}
									?>
									<?php endif;?>
                                    </select>
                                </div>
                            </div>
                            
                            <div class="form-group col-lg-3">
                                <label for="selectCategories" class="col-lg-12 control-label">Select Layout Category</label>
                                <div class="col-lg-12">
                                    <select data-name="selectcat" name="selectcat[]" class="form-control" id="selectCategories">
                                    <?php
									if ( $terms && !is_wp_error( $terms ) ) :
									?>
									<option value="">Please select</option>
									<?php foreach ( $terms as $term ) {
									if( $term->term_id == $record['catID']) {
									?>
									<option value="<?php echo $term->term_id; ?>" selected="selected"><?php echo $term->name; ?></option>
									<?php } else {  ?>
									<option value="<?php echo $term->term_id; ?>"><?php echo $term->name; ?></option>
									<?php } } ?>
									<?php endif;?>
                                    </select>
                                </div>
                            </div>
                            
                            <div class="form-group col-lg-3">
                                <div class="col-lg-12">
									<label for="enableSh" class="col-lg-12 control-label">Enable Shortcodes?</label>
									<select data-name="enableSh" name="enablesh[]" class="form-control" id="enableSh">
									<option value="">Please select</option>
									<option value="1" <?php if ($record['enableShort'] == 1 ) echo 'selected' ; ?> >Yes</option>
									<option value="0" <?php if ($record['enableShort'] == 0 ) echo 'selected' ; ?>>No</option>
                                    </select>
                                </div>
                            </div>
                            
                            <div class="form-group col-lg-3">
                                <div class="col-lg-12">
									<label for="enableRows" class="col-lg-12 control-label">Enable Rows Editing?</label>
									<select data-name="enablerows" name="enablerows[]" class="form-control" id="enableRows">
									<option value="">Please select</option>
									<option value="1" <?php if ($record['enableRowsedit'] == 1 ) echo 'selected' ; ?> >Yes</option>
									<option value="0" <?php if ($record['enableRowsedit'] == 0 ) echo 'selected' ; ?> >No</option>
                                    </select>
                                </div>
                            </div>
                            
							<!-- Repeater Remove Btn -->

							<div class="pull-right repeater-remove-btn">
								<span class="btn btn-danger remove-btn" id="remove-btn" data-id="<?php echo $record['id']; ?>">
									Remove
								</span>
							</div>
							<div class="clearfix"></div>
                            
                            
                        </div>
                        <div class="clearfix"></div>
                    </div>
                    <?php
                    $rc++;
                     } 
                    
                   } else { ?>
                    
                       <div class="items" data-group="mapping">
                        <!-- Repeater Content -->
                        <div class="item-content col-lg-12">
							
						     <div class="form-group col-lg-3">
                                <label for="selectSite" class="col-lg-12 control-label">Select Site</label>
                                <div class="col-lg-12">
                                    <select data-name="selectsite" name="selectsite[]" class="form-control" id="selectSite">
                                    <?php
									$sites = get_sites(array('number'=>'500'));   //get all blog sites
									if ( !empty ($sites)) : ?>
									<option value="">Please select</option>
									<?php foreach ( $sites as $site ) {
									$blog_details = get_blog_details( $site->blog_id );
									switch_to_blog( $site->blog_id );?>
									<option value="<?php echo $blog_details->blog_id; ?>"><?php echo $blog_details->blogname; ?></option>	
									<?php restore_current_blog(); } ?>
									<?php endif;?>
                                    </select>
                                </div>
                            </div>
							
                            <div class="form-group col-lg-3">
                                <label for="selectCategories" class="col-lg-12 control-label">Select Layout Category</label>
                                <div class="col-lg-12">
                                    <select data-name="selectcat" name="selectcat[]" class="form-control" id="selectCategories">
                                    <?php
									if ( $terms && !is_wp_error( $terms ) ) :
									?>
									<option value="">Please select</option>
									<?php foreach ( $terms as $term ) {	?>
									<option value="<?php echo $term->term_id; ?>"><?php echo $term->name; ?></option>
									<?php }  ?>
									<?php endif;?>
                                    </select>
                                </div>
                            </div>
                            
                            <div class="form-group col-lg-3">
                                <div class="col-lg-12">
									<label for="enableSh" class="col-lg-12 control-label">Enable Shortcodes?</label>
									<select data-name="enableSh" name="enablesh[]" class="form-control" id="enableSh">
									<option value="">Please select</option>
									<option value="1">Yes</option>
									<option value="0">No</option>
                                    </select>
                                </div>
                            </div>
                            
                            <div class="form-group col-lg-3">
                                <div class="col-lg-12">
									<label for="enableRows" class="col-lg-12 control-label">Enable Rows Editing?</label>
									<select data-name="enablerows" name="enablerows[]" class="form-control" id="enableRows">
									<option value="">Please select</option>
									<option value="1">Yes</option>
									<option value="0">No</option>
                                    </select>
                                </div>
                            </div>
                            
							<!-- Repeater Remove Btn -->
							<div class="pull-right repeater-remove-btn">
								<span class="btn btn-danger remove-btn" id="remove-btn" data-id="0">
									Remove
								</span>
							</div>
							<div class="clearfix"></div>
                            
                        </div>
                        <div class="clearfix"></div>
                    </div>
                    <?php } ?>
                    
                </div>
				<input class="btn btn-primary" type="submit" name="catsitemapping" value="Save Changes" />
			
			<?php
			echo '</form>'; 
		}
		
				
		
		
		/**
		* Hide CEP post type on sub sites
		*/
		public function remove_menus() {    

			global $blog_id, $menu;

			if( $blog_id != '1' ) {
				remove_menu_page('edit.php?post_type=layouts');
			}
		}
						
		
		
		
		/**
		* Get category ID from siteID
		*/
		public function getCatSite($id=0) {
			global $wpdb,$table_prefix;
			$wp_tbl = $table_prefix.'CatSite';
			$query = "select * from $wp_tbl ";
			$condition = array();
			if($id > 0){
				$condition[] = " siteID = '".$id."' ";
			}
			if(count($condition) == 1){
				$query.= " where ".$condition[0];
			}
			if(count($condition) > 1){
				$query.= " where ".implode(' and ',$condition);
			}
			$query.= " order by siteID ASC";
			$results = $wpdb->get_results($query,ARRAY_A);
			if($results){
				return $results;
			}
			return array();
			
		} 
		
				
		
		
		/**
		* Get all cat site relations
		*/
		public function getCatSiteRows() {  
			global $wpdb,$table_prefix;
			$wp_tbl = $table_prefix.'CatSite';
			$query = "select * from $wp_tbl ";
			$results = $wpdb->get_results($query,ARRAY_A);
			if($results){
				return $results;
			}
			return array();
			
		}
		
		
			
		/**
		* Delete cat site relation row
		*/
		public function delete_row($id){
			global $wpdb;
			$table_name = $wpdb->prefix . 'CatSite';
			$wpdb->query( 
			$wpdb->prepare( 
			"DELETE FROM $table_name
			 WHERE id = %d",
			 $id
			)
			);
			die();
		}
		
}

$adminSettings = new ceadminSettings();  //initialize object
if (isset($_POST['action'])=='delete_row') { if(isset($_POST['delId'])) { $adminSettings->delete_row($_POST['delId']); } }        //ajax call

