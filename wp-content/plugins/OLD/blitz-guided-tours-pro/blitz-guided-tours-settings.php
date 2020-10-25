<?php
namespace BZ_SGN_WZ;

define('BZ_SGN_WZ_BASE_DIR', 	dirname(__FILE__) . '/');
define('BZ_SGN_WZ_SL_PRODUCT_ID',   	'TRWI-LL');
define('BZ_SGN_WZ_NS','BZ_SGN_WZ');
define('GT_VERSION','1.0.15');
define('GT_PLUGIN_FILE','blitz-guided-tours-pro/blitz-guided-tours-pro.php');
			
use BZ_SGN_WZ\Steps\BzTourstable;
use BZ_SGN_WZ\Steps\BzStepstable;
use BZ_SGN_WZ\Forms\Bztourform;
use BZ_SGN_WZ\Forms\Bzstepform;

class BZ_TourWizardSettings {
		
		public function init() {
		
			require_once (BZ_SGN_WZ_BASE_DIR .'admin/blitz-guided-tours-adminsettings.php');		// Admin Panel
			
			$this->touradminSettings 		=  new Admin\BZ_TourWizardadminSettings(BZ_SGN_WZ_SL_PRODUCT_ID);
			
			if ( is_multisite() ) {
				add_action('network_admin_menu', 	array($this->touradminSettings,'bz_tour_wizard_license_settings_page'));
			} else {
				add_action('admin_menu', 	array($this->touradminSettings,'bz_tour_wizard_license_settings_page'));
			}
			
			$validLicense 				= $this->touradminSettings->bz_tour_wizard_valid_license();
			
			if ($validLicense ) { 
					
					if(get_current_blog_id() == 1) { 
						
						add_action('wp_ajax_nopriv_bztsw_tour_save',  		 array($this,  'bz_tour_wizard_tour_save'));
						add_action('wp_ajax_bztsw_tour_save',		 		 array($this,  'bz_tour_wizard_tour_save'));
						add_action('wp_ajax_nopriv_bztsw_step_save',  		 array($this,  'bz_tour_wizard_step_save'));
						add_action('wp_ajax_bztsw_step_save', 		 		 array($this,  'bz_tour_wizard_step_save'));
						add_action('wp_ajax_nopriv_bztsw_settings_save',	 array($this,  'bz_tour_wizard_boxsettings_save'));
						add_action('wp_ajax_bztsw_settings_save', 	 		 array($this,  'bz_tour_wizard_boxsettings_save'));
						
					} 	else   {
						
						add_filter('admin_init', 							 array($this,  'bz_tour_wizard_runSignupTour'), 10, 3 );

					}
					
					add_action('admin_menu',					 	 	 array($this,  'bz_tour_wizard_admin_menu' ) );
					add_action('admin_head', 							 array($this,  'bz_tour_wizard_options_custom_styles'));
					add_action('wp_head', 							 	 array($this,  'bz_tour_wizard_options_custom_styles'));
					add_action('admin_head', 							 array($this,  'bz_tour_wizard_display_fonts'));
					add_action('wp_head', 							 	 array($this,  'bz_tour_wizard_display_fonts'));
					add_action('wp_enqueue_scripts',   					 array($this,  'bz_tour_wizard_load_frontend_scripts'));
					add_action('admin_enqueue_scripts',					 array($this,  'bz_tour_wizard_admin_enqueue_scripts'), 10, 1);
					add_action('admin_enqueue_scripts', 				 array($this,  'bz_tour_wizard_admin_enqueue_styles'), 10, 1);
					

			}

		}

	
		/**
		 * Add Setup page
		*/
		public function bz_tour_wizard_admin_menu() {
			
			
			$blogID = get_current_blog_id();
			
			if( $blogID == 1) {
							
				add_menu_page('Guided Tours', __('Guided Tours','bztsw'), 'manage_options', 'bztsw_tourmenu', array($this, 'bz_tour_wizard_alltours'), 'dashicons-star-filled');
				$bzmenuSlug = 'bztsw_tourmenu';
				add_submenu_page($bzmenuSlug, '', '', 'manage_options', 'bztsw-tours', array($this, 'bz_tour_wizard_alltours'));
				
				// Add/Edit Tour
				if (isset($_GET['tour'])) {
					
					add_submenu_page($bzmenuSlug, 'Edit Tour', __('Edit Tour','bztsw'), 'manage_options', 'bztsw-tour-add', array($this, 'bz_tour_wizard_add_tour'));
				
				} else {
					
					add_submenu_page($bzmenuSlug, 'Add Tour', __('Add Tour','bztsw'), 'manage_options', 'bztsw-tour-add', array($this, 'bz_tour_wizard_add_tour'));
					
				}
				
				add_submenu_page($bzmenuSlug, 'Steps', __('Steps','bztsw'), 'manage_options', 'bztsw-steps', array($this, 'bz_tour_wizard_allSteps'));
				
				// Add/Edit Step
				if (isset($_GET['step'])) { 
					
					add_submenu_page($bzmenuSlug, 'Edit Step', __('Edit Step','bztsw'), 'manage_options', 'bztsw-step-add', array($this, 'bz_tour_wizard_add_step'));
				
				} else {
					
					add_submenu_page($bzmenuSlug, 'Add Step', __('Add Step','bztsw'), 'manage_options', 'bztsw-step-add', array($this, 'bz_tour_wizard_add_step'));
				}
				
				add_submenu_page($bzmenuSlug, 'Tour Settings', __('Settings','bztsw'), 'manage_options', 'bztsw_settings', array($this, 'bz_tour_wizard_box_settings'));
				add_submenu_page($bzmenuSlug, 'Import/Export', __('Import/Export','bztsw'), 'manage_options', 'bztsw-import-export', array($this, 'bz_tour_wizard_submenu_importExport'));
			
				
			} else {
				
				add_menu_page('Tour Guides', __('Tour Guides','bztsw'), 'manage_options', 'bztsw_tourmenu', array($this, 'bz_tour_wizard_alltours'), 'dashicons-star-filled');
			    add_meta_box('bztsw-tours', __('Tour Guides', 'bztsw'), array($this, 'bz_tour_wizard_alltours_meta_box'), 'dashboard', 'normal', 'high');
			    
			}
		
			
		}
		
    
		
		/**
		 * Submenu Settings
		*/
		public function bz_tour_wizard_box_settings() {
			
			global $wpdb;
			
			if ( is_multisite() ) {
				switch_to_blog(1);
				$tableName = $wpdb->prefix . "bztsw_settings";
				$bzsettings = $wpdb->get_results("SELECT * FROM $tableName WHERE bztsw_id=1 LIMIT 1");
				restore_current_blog();
			} else {
				$tableName = $wpdb->prefix . "bztsw_settings";
				$bzsettings = $wpdb->get_results("SELECT * FROM $tableName WHERE bztsw_id=1 LIMIT 1");
			}
			$bzsettings = $bzsettings[0];
			
			$this->settings = new Forms\Bztourboxsettingsform(); // Tour html form
			echo $this->settings->addTourboxform();    // tour box settings form
		}
    

    /**
     * Submenu Steps
    */
    public function bz_tour_wizard_alltours() {
			
			if ( is_multisite() ) {
				switch_to_blog(1);
					if (isset($_GET['remove'])) {
						$this->bz_tour_wizard_remove_tour($_GET['remove']);
						wp_redirect( admin_url( 'admin.php?page=bztsw-tours' ) );
						exit;
					}
					
					if (isset($_GET['turnoff'])) {
						$this->bz_tour_wizard_turnoff_tour($_GET['turnoff']);
						wp_redirect( admin_url( 'admin.php?page=bztsw-tours' ) );
						exit;
					}
					
					if (isset($_GET['turnon'])) {
						$this->bz_tour_wizard_turnon_tour($_GET['turnon']);
						wp_redirect( admin_url( 'admin.php?page=bztsw-tours' ) );
						exit;
					}
					
					if (isset($_GET['duplicate'])) {
						
						$toursArr = $this->bz_tour_wizard_get_tour($_GET['duplicate']);
						
						$toursArr = json_decode(json_encode($toursArr), true);
						
						foreach($toursArr as $tourkey => $tourval) {
							foreach($tourval as $tourkey1 => $tourval1) {
								
								if( $tourkey1 == 'bztsw_id') {
									unset($tourval[$tourkey1]); 
								}
							}	
						}
						//~ echo '<pre/>';
						//~ print_r($tourval);
						$this->bz_tour_wizard_tour_duplicate($tourval);  //call to duplicate action
						
					}
					
					$this->bzstepTable = new Steps\BzTourstable();
					$this->bzstepTable->prepare_items();
				restore_current_blog();
			} else {
					if (isset($_GET['remove'])) {
						$this->bz_tour_wizard_remove_tour($_GET['remove']);
						wp_redirect( admin_url( 'admin.php?page=bztsw-tours' ) );
						exit;
					}
					
					
					if (isset($_GET['turnoff'])) {
						$this->bz_tour_wizard_turnoff_tour($_GET['turnoff']);
						wp_redirect( admin_url( 'admin.php?page=bztsw-tours' ) );
						exit;
					}
					
					if (isset($_GET['duplicate'])) {
						
						$toursArr = $this->bz_tour_wizard_get_tour($_GET['duplicate']);
						
						$toursArr = json_decode(json_encode($toursArr), true);
						
						foreach($toursArr as $tourkey => $tourval) {
							foreach($tourval as $tourkey1 => $tourval1) {
								
								if( $tourkey1 == 'bztsw_id') {
									unset($tourval[$tourkey1]); 
								}
							}	
						}
						$this->bz_tour_wizard_tour_duplicate($tourval);  //call to duplicate action
					}
					
					$this->bzstepTable = new Steps\BzTourstable();
					$this->bzstepTable->prepare_items();
			}
            ?>
            <div class="wrap">
                <div id="icon-users" class="icon32"></div>
                
                <?php if ( get_current_blog_id() == 1 ) {  ?>
					<h2> <?php echo __('Guided Tours','bztsw'); ?> <a href="admin.php?page=bztsw-tour-add" class="add-new-h2"><?php echo __('Add New','bztsw'); ?></a></h2>
                <?php } else {  ?>
					<h2> <?php echo __('Guided Tours','bztsw'); ?></h2>					
				<?php } ?>
                

                <?php $this->bzstepTable->display(); ?>
            </div>
            <?php
    }
    
    
    
    /**
     * Subsite Dashboard Meta Box
    */
    public function bz_tour_wizard_alltours_meta_box() {
			
			global $wpdb;
			if ( is_multisite() ) {
				switch_to_blog(1);
					$tableName = $wpdb->prefix . "bztsw_tours";
					$tours = $wpdb->get_results("SELECT * FROM $tableName ORDER BY bztsw_id ASC");
				restore_current_blog();
			} else {					
					$tableName = $wpdb->prefix . "bztsw_tours";
					$tours = $wpdb->get_results("SELECT * FROM $tableName ORDER BY bztsw_steporder ASC");
			}
			
			if (!empty($tours)) {
				echo '<table id="the-list" class="metabox_tours">';
				foreach($tours as $tour) {
					if($tour->bztsw_onDashboard != '0' && $tour->bztsw_isActive != '1') {
						echo '<tr>';
						echo '<td class="title column-title">'.$tour->bztsw_title.'</td>';
						echo '<td class="view column-view"><a data-id="'.$tour->bztsw_id.'" href="admin.php?page=bztsw-tours&view=' . $tour->bztsw_id. '">Start Tour</a></td>';
						echo '</tr>';
					}
				}			
				echo '</table>';
			} else {
				echo 'No tour guides created yet!';
			}
    }
    
    
    
    /**
     * Submenu Items
     */
    public function bz_tour_wizard_allSteps() {
			
            if (isset($_GET['remove'])) {
                $this->bz_tour_wizard_remove_step($_GET['remove']);
                wp_redirect( admin_url( 'admin.php?page=bztsw-steps' ) );
				exit;
            }
            
            if (isset($_GET['duplicate'])) {
						
						$stepArr = $this->bz_tour_wizard_get_step($_GET['duplicate']);
						
						$stepArr = json_decode(json_encode($stepArr), true);
						
						foreach($stepArr as $stepkey => $stepval) {
							foreach($stepval as $stepkey1 => $stepval1) {
								
								if( $stepkey1 == 'bztsw_id') {
									unset($stepval[$stepkey1]); 
								}
							}	
						}
						//~ echo '<pre/>';
						//~ print_r($stepval);
						$this->bz_tour_wizard_step_duplicate($stepval);  //call to duplicate action
			}
            
            $tourID = 0;
            if (isset($_GET['tour'])) {
                $tourID = $_GET['tour'];
            }
            $this->bzitemTable = new Steps\BzStepstable();
            $this->bzitemTable->tourID = $tourID;
            $this->bzitemTable->prepare_items();            
            ?>
            <div class="wrap">
                <div id="icon-users" class="icon32"></div>
                <h2><?php echo __('Steps','bztsw'); ?> <a href="admin.php?page=bztsw-step-add" class="add-new-h2"><?php echo __('Add New','bztsw'); ?></a></h2>

                <?php $this->bzitemTable->display(); ?>
            </div>

            <?php
    }


	
	/**
     * Remove a Tour
    */
    public function bz_tour_wizard_remove_tour($step_id) {
        global $wpdb;
        
        if ( is_multisite() ) {
			switch_to_blog(1);
			$tableName = $wpdb->prefix . "bztsw_steps";
			$wpdb->delete($tableName, array('bztsw_tourID' => $step_id));
			$tableName = $wpdb->prefix . "bztsw_tours";
			$wpdb->delete($tableName, array('bztsw_id' => $step_id));
			restore_current_blog();
		} else {
			$tableName = $wpdb->prefix . "bztsw_steps";
			$wpdb->delete($tableName, array('bztsw_tourID' => $step_id));
			$tableName = $wpdb->prefix . "bztsw_tours";
			$wpdb->delete($tableName, array('bztsw_id' => $step_id));
		}
    }
    
    
    
	/**
     * Turn off Tour
    */
    public function bz_tour_wizard_turnoff_tour($tourID) {
        global $wpdb;
        
        if ( is_multisite() ) {
			switch_to_blog(1);
			$tableName = $wpdb->prefix . "bztsw_tours";
			$wpdb->update($tableName, array('bztsw_isActive' => '1'), array('bztsw_id' => $tourID));
			restore_current_blog();
		} else {			
			$tableName = $wpdb->prefix . "bztsw_tours";
			$wpdb->update($tableName, array('bztsw_isActive' => '1'), array('bztsw_id' => $tourID));
		}
    }
    
    
	/**
     * Turn on Tour
    */
    public function bz_tour_wizard_turnon_tour($tourID) {
        global $wpdb;
        
        if ( is_multisite() ) {
			switch_to_blog(1);
			$tableName = $wpdb->prefix . "bztsw_tours";
			$wpdb->update($tableName, array('bztsw_isActive' => '0'), array('bztsw_id' => $tourID));
			restore_current_blog();
		} else {			
			$tableName = $wpdb->prefix . "bztsw_tours";
			$wpdb->update($tableName, array('bztsw_isActive' => '0'), array('bztsw_id' => $tourID));
		}
    }
		
		
	
	
	/**
     * Get a Tour
    */
	public function bz_tour_wizard_get_tour($tour_id) {
        global $wpdb;
        
        if ( is_multisite() ) {
			switch_to_blog(1);
			$tableName = $wpdb->prefix . "bztsw_tours";
			$tours = $wpdb->get_results("SELECT * FROM $tableName WHERE bztsw_id=$tour_id LIMIT 1");
			restore_current_blog();
			return $tours;
		} else {
			$tableName = $wpdb->prefix . "bztsw_tours";
			$tours = $wpdb->get_results("SELECT * FROM $tableName WHERE bztsw_id=$tour_id LIMIT 1");
			return $tours;
		}
    }
    
    
    
	/**
     * Get a Step
    */
	public function bz_tour_wizard_get_step($step_id) {
        global $wpdb;
        
        if ( is_multisite() ) {
			switch_to_blog(1);
			$tableName = $wpdb->prefix . "bztsw_steps";
			$steps = $wpdb->get_results("SELECT * FROM $tableName WHERE bztsw_id=$step_id LIMIT 1");
			restore_current_blog();
			return $steps;
		} else {
			$tableName = $wpdb->prefix . "bztsw_steps";
			$steps = $wpdb->get_results("SELECT * FROM $tableName WHERE bztsw_id=$step_id LIMIT 1");
			return $steps;
		}
    }
    
    
	/**
     * Remove a Step
    */
	public function bz_tour_wizard_remove_step($item_id) {
        global $wpdb;
        if ( is_multisite() ) {
			switch_to_blog(1);
			$tableName = $wpdb->prefix . "bztsw_steps";
			$wpdb->delete($tableName, array('bztsw_id' => $item_id));
			restore_current_blog();
		} else {
			$tableName = $wpdb->prefix . "bztsw_steps";
			$wpdb->delete($tableName, array('bztsw_id' => $item_id));			
		}
    }
    
    
    /**
     * Menu add step
    */
    public function bz_tour_wizard_add_tour() {
            
            $this->tours = new Forms\Bztourform(); // Tour html form
			echo $this->tours->addTourform();
    }
    
    
    
    
    /**
     * Step add item
    */
    public function bz_tour_wizard_add_step() {
			
            $this->steps = new Forms\Bzstepform(); // Tour html form
			echo $this->steps->addStepform();
    }



    
    /**
     * save step
    */
    public function bz_tour_wizard_step_save() {
        global $wpdb;
        
        if ( is_multisite() ) {
			switch_to_blog(1);
			$message = "Error, step not saved.";
			$tableName = $wpdb->prefix . "bztsw_steps";
			$dbData = array();
			foreach ($_POST as $key => $value) {
				if ($key != 'action' && $key != 'bztsw_id' && $key != 'pll_ajax_backend') {
					if ($key == 'bztsw_pageurl') {
						if (strrpos($value, site_url()) === false) {
							
						} else {
							$value = substr($value, strlen(site_url()) + 1);
						}
						if (substr($value, -2, 2) == '//') {
							$value = substr($value, 0, -1);
						}
					}
					$dbData[$key] = stripslashes($value);
				}
			}
			if ($_POST['bztsw_id'] > 0) {
				$wpdb->update($tableName, $dbData, array('bztsw_id' => $_POST['bztsw_id']));
				$message = $_POST['bztsw_id'];
			} else {
				$rows_affected = $wpdb->insert($tableName, $dbData);
				$lastid = $wpdb->insert_id;
				$message = $lastid;
			}
		restore_current_blog();
		echo $message;
        die();
        
		} else {
			$message = "Error, step not saved.";
			$tableName = $wpdb->prefix . "bztsw_steps";
			$dbData = array();
			
			foreach ($_POST as $key => $value) {
				if ($key != 'action' && $key != 'bztsw_id' && $key != 'pll_ajax_backend') {
					if ($key == 'bztsw_pageurl') {
						if (strrpos($value, site_url()) === false) {
							
						} else {
							$value = substr($value, strlen(site_url()) + 1);
						}
						if (substr($value, -2, 2) == '//') {
							$value = substr($value, 0, -1);
						}
					}
					$dbData[$key] = stripslashes($value);
				}
			}
			if ($_POST['bztsw_id'] > 0) {
				$wpdb->update($tableName, $dbData, array('bztsw_id' => $_POST['bztsw_id']));
				$message = $_POST['bztsw_id'];
			} else {
				$rows_affected = $wpdb->insert($tableName, $dbData);
				$lastid = $wpdb->insert_id;
				$message = $lastid;
			}
		echo $message;
        die();
		}
	

    }
    
    
    
    
    /**
     * duplicate step
    */
    public function bz_tour_wizard_step_duplicate($stepsArr) {
        global $wpdb;
        
        if ( is_multisite() ) {
			switch_to_blog(1);
			$message = "Error, step not saved.";
			$tableName = $wpdb->prefix . "bztsw_steps";
			$dbData = array();
			
			foreach ($stepsArr as $key => $value) {
				if ($key != 'action' && $key != 'bztsw_id' && $key != 'pll_ajax_backend') {
					if ($key == 'bztsw_pageurl') {
						if (strrpos($value, site_url()) === false) {
							
						} else {
							$value = substr($value, strlen(site_url()) + 1);
						}
						if (substr($value, -2, 2) == '//') {
							$value = substr($value, 0, -1);
						}
					}
					$dbData[$key] = stripslashes($value);
				}
			}
			if ($_POST['bztsw_id'] > 0) {
				$wpdb->update($tableName, $dbData, array('bztsw_id' => $_POST['bztsw_id']));
				$message = $_POST['bztsw_id'];
			} else {
				$rows_affected = $wpdb->insert($tableName, $dbData);
				$lastid = $wpdb->insert_id;
				$message = $lastid;
			}
		restore_current_blog();
        if($message) {
			wp_redirect( admin_url( 'admin.php?page=bztsw-steps' ) );
			exit;
		}
        
		} else {
			$message = "Error, step not saved.";
			$tableName = $wpdb->prefix . "bztsw_steps";
			$dbData = array();
			
			foreach ($stepsArr as $key => $value) {
				if ($key != 'action' && $key != 'bztsw_id' && $key != 'pll_ajax_backend') {
					if ($key == 'bztsw_pageurl') {
						if (strrpos($value, site_url()) === false) {
							
						} else {
							$value = substr($value, strlen(site_url()) + 1);
						}
						if (substr($value, -2, 2) == '//') {
							$value = substr($value, 0, -1);
						}
					}
					$dbData[$key] = stripslashes($value);
				}
			}
			if ($_POST['bztsw_id'] > 0) {
				$wpdb->update($tableName, $dbData, array('bztsw_id' => $_POST['bztsw_id']));
				$message = $_POST['bztsw_id'];
			} else {
				$rows_affected = $wpdb->insert($tableName, $dbData);
				$lastid = $wpdb->insert_id;
				$message = $lastid;
			}
        if($message) {
			wp_redirect( admin_url( 'admin.php?page=bztsw-steps' ) );
			exit;
		}
		}
    }
    
    
    
   /**
     * Save tour
   */
   public function bz_tour_wizard_tour_save() {
        global $wpdb;
       
        if ( is_multisite() ) {
		switch_to_blog(1);
        $message = "Error, tour not saved.";
        $tableName = $wpdb->prefix . "bztsw_tours";
        $dbData = array();
        foreach ($_POST as $key => $value) {
			
			if ($key != 'action' && $key != 'bztsw_id' && $key != 'pll_ajax_backend') {
                if ($key == 'bztsw_pageurl') {
                    if (strrpos($value, site_url()) === false) {
                        
                    } else {
                        if (strlen($value)>0 &&  ($value == site_url()|| $value == site_url().'/' || $value =='/')){
                            $value = '/';
                        } else {
                            $value = substr($value, strlen(site_url()) + 1);                            
                        }
                    }
                    if (substr($value, -2, 2) == '//') {
                        $value = substr($value, 0, -1);
                    }
                    $wpdb->query("UPDATE " . $wpdb->prefix . "bztsw_steps SET bztsw_pageurl='$value' WHERE bztsw_tourID=" . $_POST['bztsw_id'] . " AND bztsw_stepTy!='tooltip' ");
                }
                $dbData[$key] = stripslashes($value);
            }
        }
        
        if ($_POST['bztsw_id'] > 0) {
            $wpdb->update($tableName, $dbData, array('bztsw_id' => $_POST['bztsw_id']));
            $message = $_POST['bztsw_id'];
        } else {
			$rows_affected = $wpdb->insert($tableName, $dbData);
            $lastid = $wpdb->insert_id;
			$message = $lastid;
        }
        restore_current_blog();
        echo $message;
        die();
	} else {
		$message = "Error, tour not saved.";
        $tableName = $wpdb->prefix . "bztsw_tours";
        $dbData = array();
        
        foreach ($_POST as $key => $value) {
			
			if ($key != 'action' && $key != 'bztsw_id' && $key != 'pll_ajax_backend') {
                if ($key == 'bztsw_pageurl') {
                    if (strrpos($value, site_url()) === false) {
                        
                    } else {
                        if (strlen($value)>0 &&  ($value == site_url()|| $value == site_url().'/' || $value =='/')){
                            $value = '/';
                        } else {
                            $value = substr($value, strlen(site_url()) + 1);                            
                        }
                    }
                    if (substr($value, -2, 2) == '//') {
                        $value = substr($value, 0, -1);
                    }
                    $wpdb->query("UPDATE " . $wpdb->prefix . "bztsw_steps SET bztsw_pageurl='$value' WHERE bztsw_tourID=" . $_POST['bztsw_id'] . " AND bztsw_stepTy!='tooltip' ");
                }
                $dbData[$key] = stripslashes($value);
            }
        }
			
        if ($_POST['bztsw_id'] > 0) {
            $wpdb->update($tableName, $dbData, array('bztsw_id' => $_POST['bztsw_id']));
            $message = $_POST['bztsw_id'];
        } else {
			$rows_affected = $wpdb->insert($tableName, $dbData);
            $lastid = $wpdb->insert_id;
			$message = $lastid;
        }
        echo $message;
        die();
	}

    }
    
    
    
   /**
     * Duplicate step
   */
   public function bz_tour_wizard_tour_duplicate($tourArr) {
        global $wpdb;
       
        if ( is_multisite() ) {
		switch_to_blog(1);
        $message = "Error, tour not saved.";
        $tableName = $wpdb->prefix . "bztsw_tours";
        $dbData = array();
        foreach ($tourArr as $key => $value) {
			
			if ($key != 'action' && $key != 'bztsw_id' && $key != 'pll_ajax_backend') {
                if ($key == 'bztsw_pageurl') {
                    if (strrpos($value, site_url()) === false) {
                        
                    } else {
                        if (strlen($value)>0 &&  ($value == site_url()|| $value == site_url().'/' || $value =='/')){
                            $value = '/';
                        } else {
                            $value = substr($value, strlen(site_url()) + 1);                            
                        }
                    }
                    if (substr($value, -2, 2) == '//') {
                        $value = substr($value, 0, -1);
                    }
                    $wpdb->query("UPDATE " . $wpdb->prefix . "bztsw_steps SET bztsw_pageurl='$value' WHERE bztsw_tourID=" . $_POST['bztsw_id'] . " AND bztsw_stepTy!='tooltip' ");
                }
                $dbData[$key] = stripslashes($value);
            }
        }
        
        if ($_POST['bztsw_id'] > 0) {
            $wpdb->update($tableName, $dbData, array('bztsw_id' => $_POST['bztsw_id']));
            $message = $_POST['bztsw_id'];
        } else {
			$rows_affected = $wpdb->insert($tableName, $dbData);
            $lastid = $wpdb->insert_id;
			$message = $lastid;
        }
        restore_current_blog();
        if($message) {
			wp_redirect( admin_url( 'admin.php?page=bztsw_tourmenu' ) );
			exit;
		}
	} else {
		$message = "Error, tour not saved.";
        $tableName = $wpdb->prefix . "bztsw_tours";
        $dbData = array();
        
        foreach ($tourArr as $key => $value) {
			
			if ($key != 'action' && $key != 'bztsw_id' && $key != 'pll_ajax_backend') {
                if ($key == 'bztsw_pageurl') {
                    if (strrpos($value, site_url()) === false) {
                        
                    } else {
                        if (strlen($value)>0 &&  ($value == site_url()|| $value == site_url().'/' || $value =='/')){
                            $value = '/';
                        } else {
                            $value = substr($value, strlen(site_url()) + 1);                            
                        }
                    }
                    if (substr($value, -2, 2) == '//') {
                        $value = substr($value, 0, -1);
                    }
                    $wpdb->query("UPDATE " . $wpdb->prefix . "bztsw_steps SET bztsw_pageurl='$value' WHERE bztsw_tourID=" . $_POST['bztsw_id'] . " AND bztsw_stepTy!='tooltip' ");
                }
                $dbData[$key] = stripslashes($value);
            }
        }
			
        if ($_POST['bztsw_id'] > 0) {
            $wpdb->update($tableName, $dbData, array('bztsw_id' => $_POST['bztsw_id']));
            $message = $_POST['bztsw_id'];
        } else {
			$rows_affected = $wpdb->insert($tableName, $dbData);
            $lastid = $wpdb->insert_id;
			$message = $lastid;
        }
		if($message) {
			wp_redirect( admin_url( 'admin.php?page=bztsw_tourmenu' ) );
			exit;
		}
	}

    }
    
    
    
    
   /**
     * save settings
   */
   public function bz_tour_wizard_boxsettings_save() {
        global $wpdb;
        
        $message = "Error, settings not saved.";
        if ( is_multisite() ) {
			switch_to_blog(1);
				$tableName = $wpdb->prefix . "bztsw_settings";
			restore_current_blog();
		} else {
			$tableName = $wpdb->prefix . "bztsw_settings";
		}
		
        $dbData = array();
        foreach ($_POST as $key => $value) {
            if ($key != 'action' && $key != 'pll_ajax_backend') {
                $dbData[$key] = stripslashes($value);
            }
        }
		$wpdb->update($tableName, $dbData, array('bztsw_id' => 1));
        $message = '<div id="message" class="updated"><p>Step <strong>saved</strong>.</p></div>';        
        echo $message;
        die();
    }
    
    
    
    
    /**
     * Return settings
    */
    public function bz_tour_wizard_getTourBoxSettings() {
        global $wpdb;
        if ( is_multisite() ) {
			switch_to_blog(1);
			$tableName = $wpdb->prefix . "bztsw_settings";
			$settings = $wpdb->get_results("SELECT * FROM $tableName WHERE bztsw_id=1 LIMIT 1");
			restore_current_blog();
		} else {
			$tableName = $wpdb->prefix . "bztsw_settings";
			$settings = $wpdb->get_results("SELECT * FROM $tableName WHERE bztsw_id=1 LIMIT 1");
		}
        if (count($settings) > 0) {
            return $settings[0];
        } else {
            return false;
        }
    }
    
    
    
    
    /**
     * Return steps settings
    */
    public function bz_tour_wizard_getStepBoxSettings() {
        global $wpdb;
        if ( is_multisite() ) {
			switch_to_blog(1);
			$tableName = $wpdb->prefix . "bztsw_steps";
			$settings = $wpdb->get_results("SELECT * FROM $tableName WHERE bztsw_overrideSettings=1");
			restore_current_blog();
		} else {
			$tableName = $wpdb->prefix . "bztsw_steps";
			$settings = $wpdb->get_results("SELECT * FROM $tableName WHERE bztsw_overrideSettings=1");
		}
        if (count($settings) > 0) {
            return $settings;
        } else {
            return false;
        }
    }

    
   
   /**
     *  Get Font family name
   */
    public function bz_tour_wizard_getFontName( $fontname ) {
		
			$googleFont = str_replace('+', ' ', $fontname);    //replace + with empty space
			if (strpos($googleFont, ':') !== false) {     // :exists
				$fonts = explode(':', $googleFont);
				$fontFamily = $fonts[0];
				$fontWeight = $fonts[1];
			} else {
				$fontFamily = $googleFont; 
			}
			return $fontFamily;
	}
	
	
	
   /**
     *  Get Font family weight
   */
    public function bz_tour_wizard_getFontWeight( $fontname ) {
		
			$googleFont = str_replace('+', ' ', $fontname);    //replace + with empty space
			if (strpos($googleFont, ':') !== false) {     // :exists
				$fonts = explode(':', $googleFont);
				$fontWeight = $fonts[1];
			} else {
				$fontWeight = 300; 
			}
			return $fontWeight;
	}
	
	
	
	
   /**
     * update option Style
   */
    public function bz_tour_wizard_display_fonts() {
		
		$fontStyles = '';
        $bzsettings = $this->bz_tour_wizard_getTourBoxSettings(); //get settings
        $bzstepssettings = $this->bz_tour_wizard_getStepBoxSettings(); //get steps settings
        
        $bzstepssettings = json_decode(json_encode($bzstepssettings), True); // step settings as array

        //~ echo '<pre/>';
        //~ print_r($bzstepssettings);
        if(isset($bzstepssettings) && $bzstepssettings!=""){
        foreach($bzstepssettings as $stepsetting) {
			
			$stepType = $stepsetting['bztsw_stepTy']; 
			if( $stepsetting['bztsw_overrideSettings'] == 1) {
				
				if($stepType == 'tooltip') {
					
						$googleFontsArr = array($stepsetting['bztsw_tooltip_titleFont'],$stepsetting['bztsw_tooltip_textFont'],$stepsetting['bztsw_tooltip_btnFont'],$stepsetting['bztsw_tooltip_titleFont'],$stepsetting['bztsw_tooltip_textFont'],$stepsetting['bztsw_tooltip_btnFont'],$stepsetting['bztsw_tooltip_titleFont'],$stepsetting['bztsw_tooltip_textFont'],$stepsetting['bztsw_tooltip_btnFont']);
				
						foreach( $googleFontsArr as $googleFont) {
							$fontFamily = $this->bz_tour_wizard_getFontName($googleFont);
							if($fontFamily != '' ) {
								echo '<link href="https://fonts.googleapis.com/css?family='.$fontFamily.'" rel="stylesheet" type="text/css">';
							}
						}
						
						// Tooltip Box
						$fontStyles .= '.bztsw_item.bztsw_tooltip.tourBox_'.$stepsetting['bztsw_id'].' {';
						$fontStyles .= ' background-color:' . $stepsetting['bztsw_tooltip_bgcolor'] . '; ';
						$fontStyles .= ' border-radius:' . $stepsetting['bztsw_tooltip_boxRadius'] . 'px; ';
						$fontStyles .= '}';
						$fontStyles .= "\n";
						$fontStyles .= ' .tourBox_'.$stepsetting['bztsw_id'].'.bztsw_item.bztsw_tooltip[data-position="right"] .bztsw_arrow, .bztsw_item.tourBox_'.$stepsetting['bztsw_id'].'.bztsw_tooltip[data-position="left"] .bztsw_arrow, .bztsw_item.tourBox_'.$stepsetting['bztsw_id'].'.bztsw_tooltip[data-position="bottom"] .bztsw_arrow, .bztsw_item.tourBox_'.$stepsetting['bztsw_id'].'.bztsw_tooltip[data-position="top"] .bztsw_arrow {';
						$fontStyles .= ' border-color: transparent transparent '.$stepsetting['bztsw_tooltip_bgcolor'].' transparent';
						$fontStyles .= '}';
						$fontStyles .= "\n";
						$fontStyles .= '.bztsw_item.tourBox_'.$stepsetting['bztsw_id'].'.bztsw_tooltip .bztsw_tooltip_text h3 {';
						$fontStyles .= ' font-size:' . $stepsetting['bztsw_tooltip_titleSize'] . 'px; ';
						$fontStyles .= ' font-family:' . $this->bz_tour_wizard_getFontName($stepsetting['bztsw_tooltip_titleFont']) . '; ';
						$fontStyles .= ' font-weight:' . $this->bz_tour_wizard_getFontWeight($stepsetting['bztsw_tooltip_titleFont']) . '; ';
						$fontStyles .= ' color:' . $stepsetting['bztsw_tooltip_titlecolor'] . '; ';
						$fontStyles .= '}';
						$fontStyles .= "\n";
						$fontStyles .= '.bztsw_item.bztsw_tooltip.tourBox_'.$stepsetting['bztsw_id'].' .bztsw_content p {';
						$fontStyles .= ' font-size:' . $stepsetting['bztsw_tooltip_textSize'] . 'px; ';
						$fontStyles .= ' font-family:' . $this->bz_tour_wizard_getFontName($stepsetting['bztsw_tooltip_textFont']) . '; ';
						$fontStyles .= ' font-weight:' . $this->bz_tour_wizard_getFontWeight($stepsetting['bztsw_tooltip_textFont']) . '; ';
						$fontStyles .= ' color:' . $stepsetting['bztsw_tooltip_textcolor'] . '; ';
						$fontStyles .= '}';
						$fontStyles .= "\n";
						$fontStyles .= '.bztsw_item.bztsw_tooltip.tourBox_'.$stepsetting['bztsw_id'].' .bztsw_btns .bztsw_button.bztsw_continue {';
						$fontStyles .= ' font-size:' . $stepsetting['bztsw_tooltip_btnSize'] . 'px; ';
						$fontStyles .= ' font-family:' . $this->bz_tour_wizard_getFontName($stepsetting['bztsw_tooltip_btnFont']) . '; ';
						$fontStyles .= ' font-weight:' . $this->bz_tour_wizard_getFontWeight($stepsetting['bztsw_tooltip_btnFont']) . '; ';
						$fontStyles .= ' color:' . $stepsetting['bztsw_tooltip_btnColor'] . '; ';
						$fontStyles .= ' background-color:' . $stepsetting['bztsw_tooltip_btnBg'] . '; ';
						$fontStyles .= ' border-radius:' . $stepsetting['bztsw_tooltip_btnRadius'] . 'px; ';
						$fontStyles .= '}';
						$fontStyles .= "\n";
						$fontStyles .= '.bztsw_item.bztsw_tooltip.tourBox_'.$stepsetting['bztsw_id'].' .bztsw_btns .bztsw_button.bztsw_button_stop {';
						$fontStyles .= ' font-size:' . $stepsetting['bztsw_tooltip_stop_btnSize'] . 'px; ';
						$fontStyles .= ' font-family:' . $this->bz_tour_wizard_getFontName($stepsetting['bztsw_tooltip_stop_btnFont']) . '; ';
						$fontStyles .= ' font-weight:' . $this->bz_tour_wizard_getFontWeight($stepsetting['bztsw_tooltip_stop_btnFont']) . '; ';
						$fontStyles .= ' color:' . $stepsetting['bztsw_tooltip_stop_btnColor'] . '; ';
						$fontStyles .= ' background-color:' . $stepsetting['bztsw_tooltip_stop_btnBg'] . '; ';
						$fontStyles .= ' border-radius:' . $stepsetting['bztsw_tooltip_stop_btnRadius'] . 'px; ';
						$fontStyles .= '}';
						$fontStyles .= "\n";
						
					
					} else if ( $stepType == 'dialog' ) {
						
						$googleFontsArr = array($stepsetting['bztsw_dialog_titleFont'],$stepsetting['bztsw_dialog_textFont'],$stepsetting['bztsw_dialog_btnFont'],$stepsetting['bztsw_dialog_titleFont'],$stepsetting['bztsw_dialog_textFont'],$stepsetting['bztsw_dialog_btnFont'],$stepsetting['bztsw_dialog_titleFont'],$stepsetting['bztsw_dialog_textFont'],$stepsetting['bztsw_dialog_btnFont']);
 				
						foreach( $googleFontsArr as $googleFont) {
							$fontFamily = $this->bz_tour_wizard_getFontName($googleFont);
							if($fontFamily != '' ) {
								echo '<link href="https://fonts.googleapis.com/css?family='.$fontFamily.'" rel="stylesheet" type="text/css">';
							}
						}
						
						// dialog Box
						$fontStyles .= '.bztsw_item.bztsw_dialog.tourBox_'.$stepsetting['bztsw_id'].' {';
						$fontStyles .= ' background-color:' . $stepsetting['bztsw_dialog_bgcolor'] . '; ';
						$fontStyles .= ' border-radius:' . $stepsetting['bztsw_dialog_boxRadius'] . 'px; ';
						$fontStyles .= '}';
						$fontStyles .= "\n";
						$fontStyles .= '.bztsw_item.tourBox_'.$stepsetting['bztsw_id'].'.bztsw_dialog h3 {';
						$fontStyles .= ' font-size:' . $stepsetting['bztsw_dialog_titleSize'] . 'px; ';
						$fontStyles .= ' font-family:' . $this->bz_tour_wizard_getFontName($stepsetting['bztsw_dialog_titleFont']) . '; ';
						$fontStyles .= ' font-weight:' . $this->bz_tour_wizard_getFontWeight($stepsetting['bztsw_dialog_titleFont']) . '; ';
						$fontStyles .= ' color:' . $stepsetting['bztsw_dialog_titlecolor'] . '; ';
						$fontStyles .= '}';
						$fontStyles .= "\n";
						$fontStyles .= '.bztsw_item.bztsw_dialog.tourBox_'.$stepsetting['bztsw_id'].' .bztsw_content p {';
						$fontStyles .= ' font-size:' . $stepsetting['bztsw_dialog_textSize'] . 'px; ';
						$fontStyles .= ' font-family:' . $this->bz_tour_wizard_getFontName($stepsetting['bztsw_dialog_textFont']) . '; ';
						$fontStyles .= ' font-weight:' . $this->bz_tour_wizard_getFontWeight($stepsetting['bztsw_dialog_textFont']) . '; ';
						$fontStyles .= ' color:' . $stepsetting['bztsw_dialog_textcolor'] . '; ';
						$fontStyles .= '}';
						$fontStyles .= "\n";
						
						//continue button
						$fontStyles .= '.bztsw_item.bztsw_dialog.tourBox_'.$stepsetting['bztsw_id'].' .bztsw_btns .bztsw_button.bztsw_continue {';
						if( $stepsetting['bztsw_dialog_disaplyConti'] == 0) {
							
							$fontStyles .= ' font-size:' . $stepsetting['bztsw_dialog_btnSize'] . 'px; ';
							$fontStyles .= ' font-family:' . $this->bz_tour_wizard_getFontName($stepsetting['bztsw_dialog_btnFont']) . '; ';
							$fontStyles .= ' font-weight:' . $this->bz_tour_wizard_getFontWeight($stepsetting['bztsw_dialog_btnFont']) . '; ';
							$fontStyles .= ' color:' . $stepsetting['bztsw_dialog_btnColor'] . '; ';
							$fontStyles .= ' background-color:' . $stepsetting['bztsw_dialog_btnBg'] . '; ';
							$fontStyles .= ' border-radius:' . $stepsetting['bztsw_dialog_btnRadius'] . 'px; ';
							$fontStyles .= '}';
							
						} else {
							$fontStyles .= ' display: none; ';
							$fontStyles .= '}';

						}
						$fontStyles .= "\n";
						
						//stop button
						$fontStyles .= '.bztsw_item.bztsw_dialog.tourBox_'.$stepsetting['bztsw_id'].' .bztsw_btns .bztsw_button.bztsw_button_stop {';
						$fontStyles .= ' font-size:' . $stepsetting['bztsw_dialog_stop_btnSize'] . 'px; ';
						$fontStyles .= ' font-family:' . $this->bz_tour_wizard_getFontName($stepsetting['bztsw_dialog_stop_btnFont']) . '; ';
						$fontStyles .= ' font-weight:' . $this->bz_tour_wizard_getFontWeight($stepsetting['bztsw_dialog_stop_btnFont']) . '; ';
						$fontStyles .= ' color:' . $stepsetting['bztsw_dialog_stop_btnColor'] . '; ';
						$fontStyles .= ' background-color:' . $stepsetting['bztsw_dialog_stop_btnBg'] . '; ';
						$fontStyles .= ' border-radius:' . $stepsetting['bztsw_dialog_stop_btnRadius'] . 'px; ';
						$fontStyles .= '}';
						$fontStyles .= "\n";
						
						//cancel button
						if( $stepsetting['bztsw_dialog_disaplyCancel'] != 0) {
							$fontStyles .= '.bztsw_dialog.bztsw_item.tourBox_'.$stepsetting['bztsw_id'].' .bztsw_btns a#bztsw_closeHelperBtn {';
							$fontStyles .= ' display: none !important; ';
							$fontStyles .= '}';	
							$fontStyles .= "\n";
						}

						
					}
			}
		}
	}
        
        
               
        $googleFontsArr = array($bzsettings->bztsw_titleFont,$bzsettings->bztsw_textFont,$bzsettings->bztsw_btnFont,$bzsettings->bztsw_dia_titleFont,$bzsettings->bztsw_dia_textFont,$bzsettings->bztsw_dia_btnFont,$bzsettings->bztsw_text_titleFont,$bzsettings->bztsw_text_textFont,$bzsettings->bztsw_text_btnFont);
        
        foreach( $googleFontsArr as $googleFont) {
			$fontFamily = $this->bz_tour_wizard_getFontName($googleFont);
			if($fontFamily != '' ) {
				echo '<link href="https://fonts.googleapis.com/css?family='.$fontFamily.'" rel="stylesheet" type="text/css">';
			}
		}
		
		// Tooltip Box
		$fontStyles .= '.bztsw_item.bztsw_tooltip {';
        $fontStyles .= ' background-color:' . $bzsettings->bztsw_bgcolor . '; ';
        $fontStyles .= ' border-radius:' . $bzsettings->bztsw_boxRadius . 'px; ';
        $fontStyles .= '}';
        $fontStyles .= "\n";
        $fontStyles .= ' .bztsw_item.bztsw_tooltip[data-position="right"] .bztsw_arrow, .bztsw_item.bztsw_tooltip[data-position="left"] .bztsw_arrow, .bztsw_item.bztsw_tooltip[data-position="bottom"] .bztsw_arrow, .bztsw_item.bztsw_tooltip[data-position="top"] .bztsw_arrow {';
        $fontStyles .= ' border-color: '.$bzsettings->bztsw_bgcolor.' #ffffff00 transparent;';
        $fontStyles .= '}';
        $fontStyles .= "\n";
        $fontStyles .= ' .bztsw_arrow, .bztsw_item.bztsw_tooltip[data-position="bottom"] .bztsw_arrow {';
        $fontStyles .= ' border-color: transparent transparent '.$bzsettings->bztsw_bgcolor .'; ';
        $fontStyles .= '}';
        $fontStyles .= "\n";
		$fontStyles .= '.bztsw_item.bztsw_tooltip .bztsw_tooltip_text h3 {';
        $fontStyles .= ' font-size:' . $bzsettings->bztsw_titleSize . 'px; ';
        $fontStyles .= ' font-family:' . $this->bz_tour_wizard_getFontName($bzsettings->bztsw_titleFont) . '; ';
        $fontStyles .= ' font-weight:' . $this->bz_tour_wizard_getFontWeight($bzsettings->bztsw_titleFont) . '; ';
        $fontStyles .= ' color:' . $bzsettings->bztsw_titlecolor . '; ';
        $fontStyles .= '}';
        $fontStyles .= "\n";
		$fontStyles .= '.bztsw_item.bztsw_tooltip .bztsw_content p {';
        $fontStyles .= ' font-size:' . $bzsettings->bztsw_textSize . 'px; ';
        $fontStyles .= ' font-family:' . $this->bz_tour_wizard_getFontName($bzsettings->bztsw_textFont) . '; ';
        $fontStyles .= ' font-weight:' . $this->bz_tour_wizard_getFontWeight($bzsettings->bztsw_textFont) . '; ';
        $fontStyles .= ' color:' . $bzsettings->bztsw_textcolor . '; ';
        $fontStyles .= '}';
        $fontStyles .= "\n";
		$fontStyles .= '.bztsw_item.bztsw_tooltip .bztsw_btns .bztsw_button {';
        $fontStyles .= ' font-size:' . $bzsettings->bztsw_btnSize . 'px; ';
        $fontStyles .= ' font-family:' . $this->bz_tour_wizard_getFontName($bzsettings->bztsw_btnFont) . '; ';
        $fontStyles .= ' font-weight:' . $this->bz_tour_wizard_getFontWeight($bzsettings->bztsw_btnFont) . '; ';
        $fontStyles .= ' color:' . $bzsettings->bztsw_btnColor . '; ';
        $fontStyles .= ' background-color:' . $bzsettings->bztsw_btnBg . '; ';
        $fontStyles .= ' border-radius:' . $bzsettings->bztsw_btnRadius . 'px; ';
        $fontStyles .= '}';
        $fontStyles .= "\n";
        
        
		// Dialog Box
		$fontStyles .= '.bztsw_item.bztsw_dialog {';
        $fontStyles .= ' background-color:' . $bzsettings->bztsw_dia_bgcolor . '; ';
        $fontStyles .= ' border-radius:' . $bzsettings->bztsw_dia_boxRadius . 'px; ';
        $fontStyles .= '}';
        $fontStyles .= "\n";
		$fontStyles .= '.bztsw_item.bztsw_dialog h3 {';
        $fontStyles .= ' font-size:' . $bzsettings->bztsw_dia_titleSize . 'px; ';
        $fontStyles .= ' font-family:' . $this->bz_tour_wizard_getFontName($bzsettings->bztsw_dia_titleFont) . '; ';
        $fontStyles .= ' font-weight:' . $this->bz_tour_wizard_getFontWeight($bzsettings->bztsw_dia_titleFont) . '; ';
        $fontStyles .= ' color:' . $bzsettings->bztsw_dia_titlecolor . '; ';
        $fontStyles .= '}';
        $fontStyles .= "\n";
		$fontStyles .= '.bztsw_item.bztsw_dialog .bztsw_content p {';
        $fontStyles .= ' font-size:' . $bzsettings->bztsw_dia_textSize . 'px; ';
        $fontStyles .= ' font-family:' . $this->bz_tour_wizard_getFontName($bzsettings->bztsw_dia_textFont) . '; ';
        $fontStyles .= ' font-weight:' . $this->bz_tour_wizard_getFontWeight($bzsettings->bztsw_dia_textFont) . '; ';
        $fontStyles .= ' color:' . $bzsettings->bztsw_dia_textcolor . '; ';
        $fontStyles .= '}';
        $fontStyles .= "\n";
		$fontStyles .= '.bztsw_item.bztsw_dialog .bztsw_btns .bztsw_button {';
        $fontStyles .= ' font-size:' . $bzsettings->bztsw_dia_btnSize . 'px; ';
        $fontStyles .= ' font-family:' . $this->bz_tour_wizard_getFontName($bzsettings->bztsw_dia_btnFont) . '; ';
        $fontStyles .= ' font-weight:' . $this->bz_tour_wizard_getFontWeight($bzsettings->bztsw_dia_btnFont) . '; ';
        $fontStyles .= ' color:' . $bzsettings->bztsw_dia_btnColor . '; ';
        $fontStyles .= ' background-color:' . $bzsettings->bztsw_dia_btnBg . '; ';
        $fontStyles .= ' border-radius:' . $bzsettings->bztsw_dia_btnRadius . 'px; ';
        $fontStyles .= '}';
        $fontStyles .= "\n";
        
        
		// Text Box
		$fontStyles .= '.bztsw_item.bztsw_text h2 {';
        $fontStyles .= ' font-size:' . $bzsettings->bztsw_text_titleSize . 'px; ';
        $fontStyles .= ' font-family:' . $this->bz_tour_wizard_getFontName($bzsettings->bztsw_text_titleFont) . '; ';
        $fontStyles .= ' font-weight:' . $this->bz_tour_wizard_getFontWeight($bzsettings->bztsw_text_titleFont) . '; ';
        $fontStyles .= ' color:' . $bzsettings->bztsw_text_titlecolor . '; ';
        $fontStyles .= '}';
        $fontStyles .= "\n";
		$fontStyles .= '.bztsw_item.bztsw_text p {';
        $fontStyles .= ' font-size:' . $bzsettings->bztsw_text_textSize . 'px; ';
        $fontStyles .= ' font-family:' . $this->bz_tour_wizard_getFontName($bzsettings->bztsw_text_textFont) . '; ';
        $fontStyles .= ' font-weight:' . $this->bz_tour_wizard_getFontWeight($bzsettings->bztsw_text_textFont) . '; ';
        $fontStyles .= ' color:' . $bzsettings->bztsw_text_textcolor . '; ';
        $fontStyles .= '}';
        $fontStyles .= "\n";
		$fontStyles .= '.bztsw_item.bztsw_text .bztsw_btns .bztsw_button {';
        $fontStyles .= ' font-size:' . $bzsettings->bztsw_text_btnSize . 'px; ';
        $fontStyles .= ' font-family:' . $this->bz_tour_wizard_getFontName($bzsettings->bztsw_text_btnFont) . '; ';
        $fontStyles .= ' font-weight:' . $this->bz_tour_wizard_getFontWeight($bzsettings->bztsw_text_btnFont) . '; ';
        $fontStyles .= ' color:' . $bzsettings->bztsw_text_btnColor . '; ';
        $fontStyles .= ' background-color:' . $bzsettings->bztsw_text_btnBg . '; ';
        $fontStyles .= ' border-radius:' . $bzsettings->bztsw_text_btnRadius . 'px; ';
        $fontStyles .= '}';
        $fontStyles .= "\n";
        
        if ($fontStyles != '') {
            $fontStyles = "\n<style id=\"bztsw_styles\" >\n" . $fontStyles . "</style>\n";
            echo $fontStyles;
        }
	}
    
    
    
   /**
     * update option Style
   */
    public function bz_tour_wizard_options_custom_styles() {
		
		$bzoutput = '';
        $bzsettings = $this->bz_tour_wizard_getTourBoxSettings();

        if( $bzsettings->bztsw_disaplyConti != 0) {
		$bzoutput .= '.bztsw_item .bztsw_btns .bztsw_button {';
        $bzoutput .= ' display: none !important; ';
        $bzoutput .= '}';
        $bzoutput .= "\n";
		}
        if( $bzsettings->bztsw_disaplyCancel != 0) {
		$bzoutput .= '.bztsw_item .bztsw_btns #bztsw_closeHelperBtn {';
        $bzoutput .= ' display: none !important; ';
        $bzoutput .= '}';
        $bzoutput .= "\n";
		}

        if ($bzoutput != '') {
            $bzoutput = "\n<style id=\"bztsw_styles\" >\n" . $bzoutput . "</style>\n";
            echo $bzoutput;
        }
        
	}
	

    
   /**
     * Run tour for First time only
   */
	public function bz_tour_wizard_runSignupTour() {
		
 		global $wpdb;
		$currentUID = get_current_user_id(); //current user id
		
		$args = array(
			'orderby'      => 'registered', // registered date
			'order'        => 'DESC', // last registered goes first
			'number'       => 1 // limit to the last one, not required
		);
		$users = get_users( $args );
		$last_user_registered = $users[0]; // the first user from the list
		$userRegistered = $last_user_registered->ID; // print user ID
		
		if ( is_multisite() ) {
			switch_to_blog(1);
			$tableName = $wpdb->prefix . "bztsw_tours";
			$logincontrol = get_user_meta($currentUID, '_bz_new_user', 'TRUE');
			restore_current_blog();
		} else {
			$tableName = $wpdb->prefix . "bztsw_tours";
			$logincontrol = get_user_meta($currentUID, '_bz_new_user', 'TRUE');
		}
		
		if(class_exists('WP_Ultimo')) {
			
			if ( is_multisite() ) {
				switch_to_blog(1);
				$planSelected = get_user_meta( $currentUID, 'plan_id', true ); //selected Plan
				if ($planSelected) {
					$tour = $wpdb->get_results("SELECT * FROM $tableName WHERE bztsw_ultimoPlan=$planSelected LIMIT 1");
				}
				restore_current_blog();
			} else {
				$planSelected = get_user_meta( $currentUID, 'plan_id', true ); //selected Plan
				if ($planSelected) {
					$tour = $wpdb->get_results("SELECT * FROM $tableName WHERE bztsw_ultimoPlan=$planSelected LIMIT 1");				
				}
			}
			
			if(!empty($tour)) {
				$defaultHelper = $tour[0]->bztsw_id;
			}	
		
		} else {
				
				if ( is_multisite() ) {
					switch_to_blog(1);
					$defaultHelp = $wpdb->get_results("SELECT * FROM $tableName WHERE bztsw_defaultTour=1 LIMIT 1");
					restore_current_blog();
				} else {
					$defaultHelp = $wpdb->get_results("SELECT * FROM $tableName WHERE bztsw_defaultTour=1 LIMIT 1");
				}
				if(!empty($defaultHelp)) {
				$defaultHelper =  $defaultHelp[0]->bztsw_id;
				}
		}
        
        if(isset($defaultHelper) &&  $defaultHelper != '' ) {
			 if ( $logincontrol == '' || $logincontrol != 1 ) {

					if(class_exists('WP_Ultimo')) {
						
						$pageURL = $_SERVER['REQUEST_URI'];
							if (strpos($pageURL, 'page=wu-my-account&action=success') !== false) {
								 $runScript = '<script type="text/javascript">
									   jQuery(window).load(function () {
											jQuery(".sa-confirm-button-container button.confirm").click(function(){
												var currenttour = bztsw_getHelperByID('.$defaultHelper.');
												if(currenttour.bztsw_start == "click") {
														jQuery(currenttour.bztsw_domElement).click();
											 	} else {
														bztsw_initFinaltours('.$defaultHelper.');
												}
											});
									   });
									   </script>';
									   add_action('admin_footer',
									   function() use ( $runScript ) {
										   $this->bz_tour_wizard_applyOptionsScripts( $runScript ); });
									update_user_meta( $currentUID, '_bz_new_user', '1' );
							}
							
					} else {
						
						$runScript = '<script type="text/javascript">
								   jQuery(window).load(function () {
										console.log("m working");
										bztsw_initFinaltours('.$defaultHelper.');
								   });
								   </script>';
								   add_action('admin_footer',
								   function() use ( $runScript ) {
									   $this->bz_tour_wizard_applyOptionsScripts( $runScript ); });
							update_user_meta( $currentUID, '_bz_new_user', '1' );
					}
		 }
		 
		}  //default tour end
		
	}


	
	/**
	* Apply style for options of site owner end
	*/
	public function bz_tour_wizard_applyOptionsScripts( $args ) {
			echo $args;
	}



    /**
     * Import/Export Tours using JSON File
     */
    public function bz_tour_wizard_submenu_importExport() {
		
        global $wpdb;
        $pluginsDir = plugin_dir_path(__FILE__);

        ?>
        <div class="bztsw_importExport">
			<div class="bztsw_main testImport">
            <h2><?php echo __('Import tours','bztsw');?></h2>
            <?php
            $displayForm = true;
            $settings = $this->bz_tour_wizard_getTourBoxSettings();
            
            if (isset($_GET['bztsw_import']) && isset($_FILES['bztsw_importFile'])) {
                $error = false;
                if (!is_dir($pluginsDir . 'bztmp')) {
                    mkdir($pluginsDir . 'bztmp');
                    chmod($pluginsDir . 'bztmp', 0775);
                }
                $target_path = $pluginsDir . 'bztmp/export_tours.json';
                if (@move_uploaded_file($_FILES['bztsw_importFile']['tmp_name'], $target_path)) {
				$upload_dir = wp_upload_dir();
                    if (!is_dir($upload_dir['path'])) {
                        mkdir($upload_dir['path']);
                    }
                        $jsonfilename = 'export_tours.json';
                        if (!file_exists($pluginsDir . 'bztmp/export_tours.json')) {
                            $jsonfilename = 'export_tours';
                        }
                        if (file_exists($pluginsDir . 'bztmp/export_tours_creator.json')) {
                            $jsonfilename = 'export_tours_creator.json';
                        }

                        $file = file_get_contents($pluginsDir . 'bztmp/' . $jsonfilename);
                        $dataJson = json_decode($file, true);
							
							if ( is_multisite() ) {
								
								switch_to_blog(1);
								$tableName = $wpdb->prefix . "bztsw_settings";
								//~ $wpdb->query("TRUNCATE TABLE $tableName");
								$wpdb->insert($tableName, $dataJson['settings'][0]);

								$tableName = $wpdb->prefix . "bztsw_tours";
								//~ $wpdb->query("TRUNCATE TABLE $tableName");
								restore_current_blog();
								
							}  else {
								
								$tableName = $wpdb->prefix . "bztsw_settings";
								//~ $wpdb->query("TRUNCATE TABLE $tableName");
								$wpdb->insert($tableName, $dataJson['settings'][0]);

								$tableName = $wpdb->prefix . "bztsw_tours";
								//~ $wpdb->query("TRUNCATE TABLE $tableName");
							}
				
							foreach ($dataJson['tours'] as $key => $value) {
								foreach ($value as $keyV => $valueV) {
									if ($keyV == 'bztsw_pageurl') {
										if (strrpos($valueV, site_url()) === false) {
											
										} else {
											$valueV = substr($valueV, strlen(site_url()) + 1);
											$value[$keyV] = $valueV;
										}
									}
								}
								$wpdb->insert($tableName, $value);
							}
							
							if ( is_multisite() ) {
								
								switch_to_blog(1);
								$tableName = $wpdb->prefix . "bztsw_steps";
								//~ $wpdb->query("TRUNCATE TABLE $tableName");
								restore_current_blog();
								
							} else {
								$tableName = $wpdb->prefix . "bztsw_steps";
								//~ $wpdb->query("TRUNCATE TABLE $tableName");
							}
							
							foreach ($dataJson['steps'] as $key => $value) {
								foreach ($value as $keyV => $valueV) {
									if ($keyV == 'bztsw_pageurl') {
										if (strrpos($valueV, site_url()) === false) {
											
										} else {
											$valueV = substr($valueV, strlen(site_url()) + 1);
											$value[$keyV] = $valueV;
										}
									}
								}
								$wpdb->insert($tableName, $value);
							}
							$files = glob($pluginsDir . 'bztmp/*');
							foreach ($files as $file) {
								if (is_file($file))
									unlink($file);
							}
                } else {
					$error = true;
                }
                if ($error) {
                    echo '<div class="error">An error occurred during the transfer</div>';
                } else {
                    $displayForm = false;
                    echo '<div class="updated">Data has been imported.</div>';
                }
            }
            if ($displayForm) {
                ?>
                <p>
                    <?php echo __('Upload here the JSON file.','bztsw');?>
                    
                </p>
                <div class="error" style="color: red;">
                    <?php echo __('WARNING: importing tours will overwrite existing ones!','bztsw'); ?>
                    
                </div>
                <form action="admin.php?page=bztsw-import-export&bztsw_import=1" method="post" enctype="multipart/form-data">
                    <p>
                        <input id="bztsw_importFile" type="file" name="bztsw_importFile" placeholder="Select file"/>
                        <label for="bztsw_importFile"> <span class="description">
                    <?php echo __('Select file','bztsw'); ?></span> </label>
                    </p>
                    <p>
                        <button type="submit" class="button-primary">
                            <?php echo __('Import','bztsw'); ?>
                        </button>
                    </p>
                </form>
                <?php
            }
            ?>
        </div>
        <?php
        
        
        //export Starts Here
        
        if (!is_dir($pluginsDir . 'bztmp')) {
			mkdir($pluginsDir . 'bztmp');
            chmod($pluginsDir . 'bztmp', 0775);
        }
		
        $destination = $pluginsDir . 'bztmp/export_tours.json';
        if (file_exists($destination)) {
            unlink($destination);
        }

        $jsonExport = array();
        
        if ( is_multisite() ) {
			switch_to_blog(1);
			$tableName = $wpdb->prefix . "bztsw_settings";
			$settings = $wpdb->get_results("SELECT * FROM $tableName ORDER BY bztsw_id ASC LIMIT 1");
			restore_current_blog();
		} else {
			$tableName = $wpdb->prefix . "bztsw_settings";
			$settings = $wpdb->get_results("SELECT * FROM $tableName ORDER BY bztsw_id ASC LIMIT 1");			
		}

        if(count($settings)>0){
            $settings = $settings[0];
            $settings->purchaseCode = '';
            $settings->updated = 0;
            $jsonExport['settings'] = $settings;
        } else {
             $jsonExport['settings'] = array();
        }
		
		if ( is_multisite() ) {
			switch_to_blog(1);
			$tableName = $wpdb->prefix . "bztsw_tours";
			$tours = array();
			foreach ($wpdb->get_results("SELECT * FROM $tableName") as $key => $row) {
				$tours[] = $row;
			}
			restore_current_blog();
		} else {
			$tableName = $wpdb->prefix . "bztsw_tours";
			$tours = array();
			foreach ($wpdb->get_results("SELECT * FROM $tableName") as $key => $row) {
				$tours[] = $row;
			}			
		}
		 
		 
		if ( is_multisite() ) {
			switch_to_blog(1);
			$jsonExport['tours'] = $tours;
			$tableName = $wpdb->prefix . "bztsw_steps";
			$steps = array();
			foreach ($wpdb->get_results("SELECT * FROM $tableName") as $key => $row) {
				$steps[] = $row;
			}
			restore_current_blog();
		} else {
			$jsonExport['tours'] = $tours;
			$tableName = $wpdb->prefix . "bztsw_steps";
			$steps = array();
			foreach ($wpdb->get_results("SELECT * FROM $tableName") as $key => $row) {
				$steps[] = $row;
			}			
		}
		
        $jsonExport['steps'] = $steps;
        $fp = fopen($pluginsDir . 'bztmp/export_tours.json', 'w');
        fwrite($fp, json_encode($jsonExport));
        fclose($fp);
        ?>
        <div class="bztsw_main testExport">
            <h2><?php echo __('Export tours','bztsw'); ?></h2>
            <p>
                <?php echo __('Export all data to a JSON file which  can be imported on another website.','bztsw'); ?>
            </p>
            <p>
                <a download class="button-primary" href="<?php echo esc_url(trailingslashit(plugins_url('/', BZ_SGN_WZ_BASE_DIR))) . 'blitz-guided-tours-pro/bztmp/export_tours.json'; ?>"><?php echo __('Export','bztsw'); ?></a>
            </p>
        </div>
        </div>			
        <?php
    }
    
    
    	/**
		 * Call to FRONTEND JS & CSS 
		*/
		public function bz_tour_wizard_load_frontend_scripts(){
			
		   wp_enqueue_script('jquery'); //include jQuery
		   
		   wp_register_script( 'bz-front-script', plugins_url( 'assets/js/tours.min.js', __FILE__ ) );
		   wp_enqueue_script( 'bz-front-script' );
		   
		   wp_register_style( 'bz-front-style', plugins_url( 'assets/css/tours.min.css', __FILE__ ) );
		   wp_enqueue_style( 'bz-front-style' );
		  
						
		   global $wpdb;

			$validTours = array();
			$tours = array();
			
			if ( is_multisite() ) {
				switch_to_blog(1);
				$tableName = $wpdb->prefix . "bztsw_tours";
				$tours = $wpdb->get_results('SELECT * FROM ' . $tableName . ' ORDER BY bztsw_id');

				$tableName1 = $wpdb->prefix . "bztsw_settings";
				$toursettings = $wpdb->get_results('SELECT * FROM ' . $tableName1 . ' ORDER BY bztsw_id');
				
				restore_current_blog();
			} else {
				$tableName = $wpdb->prefix . "bztsw_tours";
				$tours = $wpdb->get_results('SELECT * FROM ' . $tableName . ' ORDER BY bztsw_id');				
				
				$tableName1 = $wpdb->prefix . "bztsw_settings";
				$toursettings = $wpdb->get_results('SELECT * FROM ' . $tableName1 . ' ORDER BY bztsw_id');
			}
			
			foreach ($tours as $tour) {            
				
				if ( is_multisite() ) {
					switch_to_blog(1);
					$tableName = $wpdb->prefix . "bztsw_steps";
					$tour->items = $wpdb->get_results('SELECT * FROM ' . $tableName . ' WHERE bztsw_tourID=' . $tour->bztsw_id . ' ORDER BY bztsw_steporder');    
					restore_current_blog();
				} else {
			      	$tableName = $wpdb->prefix . "bztsw_steps";
					$tour->items = $wpdb->get_results('SELECT * FROM ' . $tableName . ' WHERE bztsw_tourID=' . $tour->bztsw_id . ' ORDER BY bztsw_steporder');
				}
				
				foreach ( $tour->items as $item) {
					$item->content = do_shortcode($item->bztsw_stepCont);
				}
				$validTours[] = $tour;                
			}
			
			wp_localize_script('bz-front-script', 'globaltoursettings', $toursettings);
			wp_localize_script('bz-front-script', 'tours', $validTours);
			$settings = $this->bz_tour_wizard_getTourBoxSettings();
			wp_localize_script('bz-front-script', 'toururl', site_url() . '/');
			
			
			if ( is_multisite() ) {
				$blog_id = get_current_blog_id();
				switch_to_blog($blog_id);
				wp_localize_script('bz-front-script', 'tourblogid', $blog_id);
				restore_current_blog();
			}
        	
		}
		
		
		
		/**
		 * Load admin CSS
		*/
		public function bz_tour_wizard_admin_enqueue_styles() {
			
			wp_register_style('bztsw-admin',  plugins_url( 'assets/css/tour-admin.min.css', __FILE__ ) );
			wp_enqueue_style('bztsw-admin');
			
			wp_register_style( 'bz-admin-style', plugins_url( 'assets/css/tours.min.css', __FILE__ ) );
		    wp_enqueue_style( 'bz-admin-style' );
		    
			wp_register_style( 'bz-admin-font-style', plugins_url( 'assets/css/fontselect-default.css', __FILE__ ) );
		    wp_enqueue_style( 'bz-admin-font-style' );
		}



		/**
		 * Load admin Javascript
		*/
		public function bz_tour_wizard_admin_enqueue_scripts() {
		
				wp_register_script( 'bz-back-script', plugins_url( 'assets/js/tours.min.js', __FILE__ ) );
				wp_enqueue_script( 'bz-back-script' );
		   
				wp_register_script('bztsw-admin', plugins_url( 'assets/js/touradmin.min.js', __FILE__ ) );
				wp_enqueue_script('bztsw-admin');
				
				wp_register_script('bztsw-admin-font', plugins_url( 'assets/js/jquery.fontselect.js', __FILE__ ) );
				wp_enqueue_script('bztsw-admin-font');

				$settings = $this->bz_tour_wizard_getTourBoxSettings();
				wp_localize_script('bztsw-admin', 'frontpageurl', site_url() . '/');
				wp_localize_script('bztsw-admin', 'dashboardurl', admin_url() . '/');
				
			
				global $wpdb;

				$validTours = array();
				$tours = array();
				
				if ( is_multisite() ) {
					switch_to_blog(1);
					$tableName = $wpdb->prefix . "bztsw_tours";
					$tours = $wpdb->get_results('SELECT * FROM ' . $tableName . ' ORDER BY bztsw_id');
					
					$tableName1 = $wpdb->prefix . "bztsw_settings";
					$toursettings = $wpdb->get_results('SELECT * FROM ' . $tableName1 . ' ORDER BY bztsw_id');
					restore_current_blog();
				} else {
					$tableName = $wpdb->prefix . "bztsw_tours";
					$tours = $wpdb->get_results('SELECT * FROM ' . $tableName . ' ORDER BY bztsw_id');
					
					$tableName1 = $wpdb->prefix . "bztsw_settings";
					$toursettings = $wpdb->get_results('SELECT * FROM ' . $tableName1 . ' ORDER BY bztsw_id');
				}
				
				foreach ($tours as $tour) {     
					
					if ( is_multisite() ) {
						switch_to_blog(1);
						$tableName = $wpdb->prefix . "bztsw_steps";
						$tour->items = $wpdb->get_results('SELECT * FROM ' . $tableName . ' WHERE bztsw_tourID=' . $tour->bztsw_id . ' ORDER BY bztsw_steporder');    
						restore_current_blog();
					} else {
						$tableName = $wpdb->prefix . "bztsw_steps";
						$tour->items = $wpdb->get_results('SELECT * FROM ' . $tableName . ' WHERE bztsw_tourID=' . $tour->bztsw_id . ' ORDER BY bztsw_steporder');    
					}
					
					foreach ( $tour->items as $item) {
						$item->content = do_shortcode($item->bztsw_stepCont);
					}
						$validTours[] = $tour;                
				}
				
				wp_localize_script('bz-back-script', 'tours', $validTours);
				wp_localize_script('bz-back-script', 'globaltoursettings', $toursettings);
				wp_localize_script('bz-back-script', 'toururl', site_url() . '/');
				if ( is_multisite() ) {
					$blog_id = get_current_blog_id();
					switch_to_blog($blog_id);
					wp_localize_script('bz-back-script', 'tourblogid', array($blog_id));
					restore_current_blog();
				}
			
			
		}
	
 }
?>
