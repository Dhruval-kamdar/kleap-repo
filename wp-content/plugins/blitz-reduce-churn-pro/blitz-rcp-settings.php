<?php
namespace BZ_RCP;

define('BZRCP_BASE_DIR', 	dirname(__FILE__) . '/');
define('BZRCP_PRODUCT_ID',   'RCHP');
define('BZRCP_VERSION',   	'1.3');
define('BZRCP_DIR_PATH', plugin_dir_path( __DIR__ ));
define('BZ_RCP_NS','BZ_RCP');
define('BZRCP_PLUGIN_FILE', 'blitz-reduce-churn-pro/blitz-reduce-churn-pro.php');   //Main base file

require_once (BZRCP_BASE_DIR .'Questions/BzQuestionstable.php');		// Admin Panel
require_once (BZRCP_BASE_DIR .'Questions/BzQuestionshtml.php');		// Admin Panel

use BZ_RCP\Questions\BzQuestionstable;
use BZ_RCP\Questions\BzQuestionshtml;

class BZReduceChurnSettings {
		
		public $pageslug 	   = 'bzrcp-questions';
	
		public function init() { 
		
			$blog_id = get_current_blog_id();
			
			require_once (BZRCP_BASE_DIR .'admin/blitz-rcp-adminSettings.php');		// Admin Panel
			$this->bzrcpAdminsettings 		=  new Admin\RCPAdminSettings(BZRCP_PRODUCT_ID);
			
			if ( ! function_exists( 'is_plugin_active_for_network' ) ) {
				require_once( ABSPATH . '/wp-admin/includes/plugin.php' );
			} 

			if ( is_multisite() ) {
				if( is_plugin_active_for_network(BZRCP_PLUGIN_FILE) ) {
					add_action('network_admin_menu', array($this->bzrcpAdminsettings,'bzrcp_license_settings_page'));
				} else  {
					add_action('admin_menu', array($this->bzrcpAdminsettings,'bzrcp_add_license_settings_page'));
				}
			} else {
				add_action('admin_menu', array($this->bzrcpAdminsettings,'bzrcp_add_license_settings_page'));
			}
			
			$bzrcpValidLicense 				= $this->bzrcpAdminsettings->bzrcp_valid_license();
			
			if ($bzrcpValidLicense ) { 
					$this->bzrcp_activation_hooks();	
			} 
			
		}
		
	
		
		/**
		 * Init Hooks
		*/
		public function bzrcp_activation_hooks() {
			
			add_action('admin_menu', 					      array($this, 'bzrcp_reduce_churn_questions_menu'));
			add_action('admin_enqueue_scripts',				  array($this, 'bzrcp_adminloadStyles'));
			add_action('admin_init',						  array($this, 'bzrcp_add'));
			add_action('admin_init',						  array($this, 'bzrcp_edit'));
			add_action('admin_footer', 						  array($this, 'bzrcp_showModal_html'), 100);
			add_action('admin_notices', 					  array($this, 'bzrcp_question_notices' ));
			add_action('wp_ajax_bzrcp_save_modalData', 		  array($this, 'bzrcp_save_modalData_call' ));
			add_action('wp_ajax_bzrcp_search_questions', 	  array($this, 'bzrcp_search_questions_call' ));
			add_filter('set-screen-option',					  array($this, 'bzrcp_set_screen_option'), 10, 3);
			
		}
	
		
		
		/**
		* Loads admin styles & scripts
		*/
		public function bzrcp_adminloadStyles(){
			
			if(isset($_REQUEST['page'])){
				
				if($_REQUEST['page'] == $this->pageslug || $_REQUEST['page'] == 'wu-my-account'){
					
					wp_register_style( 'bzrcp_css', plugins_url('assets/css/bzrcp-main.css', __FILE__) );
					wp_enqueue_style( 'bzrcp_css' );
					
					wp_register_style( 'bzrcp_bootstrap_css', plugins_url( '/assets/css/bzrcp-bootstrap.min.css', __FILE__ ) );
					wp_enqueue_style('bzrcp_bootstrap_css');	
						
					wp_register_script( 'bzrcp_multistep_js', plugins_url( '/assets/js/bzrcp-multi-step-modal.js', __FILE__ ) );
					wp_enqueue_script('bzrcp_multistep_js');		
					
					if(is_multisite()) {
						switch_to_blog(1);
						  $adminurl = admin_url('admin-ajax.php');
						restore_current_blog();
					} else {
						$adminurl = admin_url('admin-ajax.php');
					}
					wp_localize_script( 'bzrcp_multistep_js', 'bzrcp_ajaxurl', $adminurl );
					
					if(is_multisite()) {
						$blogId = get_current_blog_id();
						if($blogId != 1) {
							switch_to_blog($blogId);
								$wuRemoveLink = admin_url('admin.php?page=wu-remove-account');
								wp_localize_script( 'bzrcp_multistep_js', 'bzrcp_removeLink', $wuRemoveLink );
							restore_current_blog();
						}

					} 

				}
			}
		}
		
		
		
		
		/**
		 * Add Questions Setup page
		*/
		public function bzrcp_reduce_churn_questions_menu() { 
			
			if( is_multisite() ) {
				
				$blog_id = get_current_blog_id();
				if( is_plugin_active_for_network(BZRCP_PLUGIN_FILE) ) {
					if( $blog_id == 1 ) {
						$hook = add_menu_page('Reduce Churn', 'Reduce Churn', __('Reduce Churn','bztsw'), $this->pageslug, array($this, 'bzrcp_questions'));
					}
				} else {
					switch_to_blog($blog_id);
						$hook = add_menu_page('Reduce Churn', 'Reduce Churn', 'read', $this->pageslug, array($this, 'bzrcp_questions') );
					restore_current_blog();
				}
			} else {
					$hook = add_menu_page('Reduce Churn', 'Reduce Churn', 'read', $this->pageslug, array($this, 'bzrcp_questions') );
			}
			if(isset($hook)) {
				add_action( "load-$hook", array($this,'bzrcp_add_options') );   //Add options
			}
			
		} 
		
		
				
		/**
		 * Questions listing
		*/
		public function bzrcp_questions() { 
			
            if (isset($_GET['page']) && $_GET['page']== $this->pageslug) {
				
				if ( isset($_GET['action']) && $_GET['action'] == 'add' ) {
					$this->bzrcp_add_question();
				} else	if ( isset($_GET['action']) && $_GET['action'] == 'edit' ) {
					$ques_id = $_GET['question'];
					$this->bzrcp_edit_question($ques_id);
				} else if ( isset($_GET['action']) && $_GET['action'] == 'delete' ) {
					$ques_id = $_GET['question'];
					$this->bzrcp_delete_question($ques_id);
					wp_redirect( admin_url( 'admin.php?page='.$this->pageslug ) );
					exit();
				} else {

				$questions = new BzQuestionstable();
				$questionHtml = new BzQuestionshtml();   
				?>
				
				<div class="wrap">
					<script type="text/javascript">
						jQuery(document).ready(function(){
							jQuery('.toplevel_page_bzrcp-questions #screen-options-link-wrap' ).click(function(){
								jQuery('.toplevel_page_bzrcp-questions #screen-options-wrap').removeClass('hidden');
							});
						});
					</script>
					<div id="icon-users" class="icon32"></div>
					<h1 class="wp-heading-inline"><?php echo __('Questions','bztsw'); ?></h1>
					<a href="admin.php?page=<?php echo $this->pageslug; ?>&action=add" class="add-new-h2 page-title-action"><?php echo __('Add New','bzrcp'); ?></a>
					
					<form method="post">
					  <input type="hidden" name="page" value="<?php echo $this->pageslug; ?>" />
					  <?php 
						  $questions->prepare_items();     
						  $questions->search_box('Search', 'question'); 
						  $questions->display();
					  ?>	    
					</form>
					
				</div>
            <?php } } 
		}
		
		

	
		/**
		 * Show Popup
		*/
		public function bzrcp_showModal_html() {
			if( is_multisite() ) {
			  if(class_exists('WP_Ultimo')) {
				if(isset($_REQUEST['page'])){
					if($_REQUEST['page'] == 'wu-my-account'){
							// Modal starts here
							$allQus = $this->bzrcp_getAllquestions();
							if( isset($allQus) && is_array($allQus) ){ 
								$questionHtml = new BzQuestionshtml();   
							?>
							<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.2.0/css/bootstrap-theme.min.css">
								<script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.2.0/js/bootstrap.min.js"></script>
								<form class="modal multiStep" id="bzrcp_feedmodal">
									<div class="modal-dialog">
										<div class="modal-content">
											<div class="modal-header">
												<h4>Quick Feedback</h4>
												<span class="closeModal" data-dismiss="modal">&times;</span>
											</div>
											<?php 
											 $cnt=1;
											 foreach($allQus as $Qus) { ?>
												<div id="q<?php echo $Qus->id; ?>" class="modal-body step-<?php echo $cnt; ?>" data-step="<?php echo $cnt; ?>" data-type="<?php echo $Qus->question_type; ?>">
												<?php
													$quesId = $Qus->id;
													$quesTitle = $Qus->question;
													$quesType = $Qus->question_type;
													$quesOptions = $Qus->question_options;
													echo $questionHtml->render_html($quesId,$quesType,$quesOptions,$quesTitle);         
												?>
												</div>		
											<?php $cnt++; }
											 ?>
											
											<div class="modal-footer">
												
												<p class="cancel closeModal" data-dismiss="modal">Cancel Deletion</p>
												
												<p class="skipFeedback"><a href="<?php echo admin_url('admin.php?page=wu-remove-account'); ?>"> Skip </a></p>	
												
												<?php $newcnt=1; foreach($allQus as $Qus) { ?>
														
													<div id="q<?php echo $Qus->id; ?>" class="modal-body step-<?php echo $newcnt; ?>" data-step="<?php echo $newcnt; ?>">
													
														<?php if($newcnt!="1") { ?>
															
															<button type="button" id="bzrcp_backBtn" class="btn btn-primary step step-<?php echo $newcnt; ?>" data-step="<?php echo $newcnt; ?>" onclick="sendEvent('back','#bzrcp_feedmodal', <?php echo $newcnt-1; ?>, 'bzrcp_backBtn',<?php echo $Qus->id; ?>, '<?php echo $Qus->question_type; ?>')">Back</button>
														
														<?php }
														// if ($newcnt==count($allQus)) { ?>
															
<!--
															<button type="button" id="bzrcp_submitBtn" class="btn btn-primary" onclick="sendEvent('submit','#bzrcp_feedmodal','','','','')">Submit</button>																								
-->
														<?php// } else { ?>
															
															<button type="button" id="bzrcp_nextBtn" class="btn btn-primary feedback_conti step step-<?php echo $newcnt; ?>" data-step="<?php echo $newcnt; ?>" onclick="sendEvent('next','#bzrcp_feedmodal', <?php echo $newcnt+1; ?>, 'bzrcp_nextBtn',<?php echo $Qus->id; ?>, '<?php echo $Qus->question_type; ?>')">Continue</button>																								
														<?php //}
														
														 $newcnt++; ?>
													</div>
													
												<?php } ?>
											</div>
											
										</div>
									</div>
								</form>
						<?php } 
					}
				}
			  }
			}
		}
		
		
		
		
		/**
		 * Questions - Screen options 
		*/
		public function bzrcp_add_options() {
		  $option1 = 'per_page';
		  $args = array(
				 'label' => 'Questions',
				 'default' => 10,
				 'option' => 'bzrcp_questions_per_page'
				 );
		  add_screen_option( $option1, $args );
		}

		
		
		
		/**
		 * Questions - Screen options Save
		*/
		public function bzrcp_set_screen_option($status, $option, $value) {
			if ( 'bzrcp_questions_per_page' == $option ) return $value;
			return $status;
		}
		
			
		
		
		/**
		 * Add new question page callback
		*/
		public function bzrcp_add_question(){
			?>
		   <div class="wrap">
              <div id="icon-users" class="icon32"></div>
               <h1 class="wp-heading-inline"><?php echo __('Questions','bztsw'); ?></h1>
				<a href="admin.php?page=<?php echo $this->pageslug; ?>" class="add-new-h2 page-title-action"><?php echo __('All Questions','bzrcp'); ?></a>					
				
			<form method="post" id="addQues">
				<div class="bzrcp_ques_main">
					<h3><?php _e('Add Question', 'bzrcp'); ?></h3>
					<?php if ( isset($this->quesAdded )) : ?>
						<div class="updated notice is-dismissible">
							<p><?php _e	('Question added successfully!', 'bzrcp'); ?></p>
						</div>
					<?php endif; ?>
		    
					<div class="bzrcp_field">
						<h3><?php _e('Question', 'bzrcp'); ?></h3>
						<?php
							$settings = array('editor_height'=>'200');
							$customfield = wp_editor( '', 'bzrcp_question', $settings );
						?>
					</div>
					<div class="bzrcp_field">
						<h3><?php _e('Question Type', 'bzrcp'); ?></h3>
						<select name="bzrcp_quesType" id="bzrcp_quesType">
							<option value="mcq">Multiple Choice Question</option>
							<option value="message_box">Message Box</option>
							<option value="star_rating">Star Rating</option>
							<option value="true_false">True False</option>
						</select>
					</div>

					
					<div class="bzrcp_field" id="bzrcp_main_quesOptions">
						<h3><?php _e('Question Choices', 'bzrcp'); ?></h3>

						<span><?php _e('Enter each answer on a new line with its redirect url (if any ).','bzrcp'); ?></span>
				
						<div id="answersUrls">
							<div class="entry input-group col-xs-12">
								<div class="bzrcp_option1">
									<input class="form-control answers" name="bzrcp_answers[]" type="text" placeholder="Answer*" />
									<span class="example">Required*</span>
								</div>
								<div class="bzrcp_option2">
									<input class="form-control urls" name="bzrcp_urls[]" type="text" placeholder="Redirect URL" />
									<span class="example">Optional</span>
								</div>
								<div class="bzrcp_option3">
									<input class="form-control related_ques bzrcp_related_ques" name="bzrcp_related_ques[]" type="text" placeholder="Enter first 4 charcters of question title to search" />
									<input class="bzrcp_related_ques_id" name="bzrcp_related_ques_id[]" type="hidden" />
									<ul class="list-gpfrm" id="bzrcp_related_ques_search"></ul>
									<span class="example">Optional( Add related question to answer here )</span>
								</div>
								<span class="input-group-btn">
									<button type="button" class="btn btn-success btn-lg btn-add">
										<span class="glyphicon glyphicon-plus" aria-hidden="true"></span>
									</button>
								</span>
							</div>
						</div>	 
					</div>
					
					<div class="bzrcp_field" id="bzrcp_main_quesOptions1" style="display:none;">
						<h3><?php _e('Question Options', 'bzrcp'); ?></h3>

						<span><?php _e('Enter Redirect URL & related question (if any ).','bzrcp'); ?></span>
				
						<div id="answersUrls">
							<div class="entry input-group col-xs-12">
								<div class="bzrcp_option2">
									<input class="form-control urls" name="bzrcp_urls1[]" type="text" placeholder="Redirect URL" />
									<input class="form-control answers" name="bzrcp_answers1[]" type="hidden" placeholder="Answer*" />
									<span class="example">Optional</span>
								</div> 
								<div class="bzrcp_option3">
									<input class="form-control related_ques bzrcp_related_ques1" name="bzrcp_related_ques1[]" type="text" placeholder="Enter first 4 charcters of question title to search" />
									<input class="bzrcp_related_ques_id1" name="bzrcp_related_ques_id1[]" type="hidden" />
									<ul class="list-gpfrm other1" id="bzrcp_related_ques_search"></ul>
									<span class="example">Optional( Add related question to answer here )</span>
								</div>
							</div>
						</div>	 
					</div>
					
					<?php wp_nonce_field('questions_nonce', 'questions_nonce'); ?>
					<?php submit_button(); ?>			
				</div>
			</form>
		   </div>
			<?php 
		}
		

		
		/**
		 * Get question call
		*/
		public function bzrcp_get_question($ques_id){
				
			global $wpdb;
			if ( is_multisite() ) {
				switch_to_blog(1);
				$tableName = $wpdb->prefix . "bzrcp_questions";
				$quesDetails = $wpdb->get_row("SELECT * FROM $tableName WHERE id=".$ques_id);
				restore_current_blog();
			} else {
				$tableName = $wpdb->prefix . "bzrcp_questions";
				$quesDetails = $wpdb->get_row("SELECT * FROM $tableName WHERE id=".$ques_id);
			}
			return $quesDetails;
			
		}

		
		
		/**
		 * Get all questions call
		*/
		public function bzrcp_getAllquestions(){
				
			global $wpdb;
			if ( is_multisite() ) {
				switch_to_blog(1);
				$tableName = $wpdb->prefix . "bzrcp_questions";
				$allquesDetails = $wpdb->get_results("SELECT * FROM ".$tableName);
				restore_current_blog();
			} else {
				$tableName = $wpdb->prefix . "bzrcp_questions";
				$allquesDetails = $wpdb->get_results("SELECT * FROM ".$tableName);
			}
			return $allquesDetails;
			
		}

		
		
		
		/**
		 * Remove question call
		*/
		public function bzrcp_delete_question($ques_id){
			
			global $wpdb;
			
			if ( is_multisite() ) {
				switch_to_blog(1);
					$tableName = $wpdb->prefix . "bzrcp_questions";
					$wpdb->delete($tableName, array('id' => $ques_id));
				restore_current_blog();
			} else {
				$tableName = $wpdb->prefix . "bzrcp_questions";
				$wpdb->delete($tableName, array('id' => $ques_id));
			}
		}
		
		
		
		
		/**
		 * Edit new question page callback
		*/
		public function bzrcp_edit_question($ques_id){
			
			$quesDetail = $this->bzrcp_get_question($ques_id);
			?>
			<div class="wrap">
              <div id="icon-users" class="icon32"></div>
               <h1 class="wp-heading-inline"><?php echo __('Questions','bztsw'); ?></h1>
			   <a href="admin.php?page=<?php echo $this->pageslug; ?>" class="add-new-h2 page-title-action"><?php echo __('All Questions','bzrcp'); ?></a>					
			<form method="post" id="addQues">
				<div class="bzrcp_ques_main">
					<h3><?php _e('Edit Question', 'bzrcp'); ?></h3>
					<?php if ( isset($this->quesUpdated) ) : ?>
						<div class="updated notice is-dismissible">
							<p><?php _e	('Question updated successfully!', 'bzrcp'); ?></p>
						</div>
					<?php endif; ?>
		    
					<div class="bzrcp_field">
						<h3><?php _e('Question', 'bzrcp'); ?></h3>
						<?php
							$content = stripslashes($quesDetail->question);
							$settings = array('editor_height'=>'200');
							$customfield = wp_editor( $content, 'bzrcp_question', $settings );
						?>
					</div>
					<div class="bzrcp_field">
						<h3><?php _e('Question Type', 'bzrcp'); ?></h3>
						<select name="bzrcp_quesType" id="bzrcp_quesType">
							<option <?php echo ($quesDetail->question_type == 'mcq')?"selected":"" ?> value="mcq">Multiple Choice Question</option>
							<option <?php echo ($quesDetail->question_type == 'message_box')?"selected":"" ?> value="message_box">Message Box</option>
							<option <?php echo ($quesDetail->question_type == 'star_rating')?"selected":"" ?> value="star_rating">Star Rating</option>
							<option <?php echo ($quesDetail->question_type == 'true_false')?"selected":"" ?> value="true_false">True False</option>
						</select>
					</div>
					
					<?php if ( $quesDetail->question_type == 'star_rating' || $quesDetail->question_type == 'true_false' || $quesDetail->question_type == 'message_box' ) { ?>
		
						
						<div class="bzrcp_field" id="bzrcp_main_quesOptions1" style="display:block;">
							<h3><?php _e('Question Options', 'bzrcp'); ?></h3>

							<span><?php _e('Enter Redirect URL & related question (if any ).','bzrcp'); ?></span>
					
							<div id="answersUrls">
								
								<?php  
									$choices = $quesDetail->question_options;
									if( !empty($choices ) ){
									$answeroptions = unserialize($choices);
									if( isset($answeroptions) && is_array($answeroptions) ){
									$i = 0;
									$len = count($answeroptions);
									
									//~ print_r($answeroptions);
									
									foreach ($answeroptions as $qkey => $qval){
										
										$newqkey = stripslashes($qkey);
										if( isset ($qval['rel_ques']) ) {
											$quesDetailhidden = $this->bzrcp_get_question($qval['rel_ques']);
										}
								?>
								
								<div class="entry input-group col-xs-12">
									<div class="bzrcp_option2">
										<input class="form-control urls" name="bzrcp_urls1[]" value="<?php if(isset($qval['url'])){ echo $qval['url']; } ?>" type="text" placeholder="Redirect URL" />
										<input class="form-control answers" name="bzrcp_answers1[]" type="hidden" placeholder="Answer*" />
										<span class="example">Optional</span>
									</div>
									<div class="bzrcp_option3">
										<input class="form-control related_ques bzrcp_related_ques1" value="<?php if(isset( $quesDetailhidden )){ echo $quesDetailhidden->question; } ?>" name="bzrcp_related_ques1[]" type="text" placeholder="Enter first 4 charcters of question title to search" />
										<input class="bzrcp_related_ques_id1" name="bzrcp_related_ques_id1[]" type="hidden" />
										<ul class="list-gpfrm other1" id="bzrcp_related_ques_search"></ul>
										<span class="example">Optional( Add related question to answer here )</span>
									</div>
								</div>
								
								<?php 
								$i++; } }
								 } else {
								?>
								<div class="entry input-group col-xs-12">
									<div class="bzrcp_option2">
										<input class="form-control urls" name="bzrcp_urls1[]" value="<?php if(isset($qval['url'])){ echo $qval['url']; } ?>" type="text" placeholder="Redirect URL" />
										<input class="form-control answers" name="bzrcp_answers1[]" type="hidden" placeholder="Answer*" />
										<span class="example">Optional</span>
									</div>
									<div class="bzrcp_option3">
										<input class="form-control related_ques bzrcp_related_ques1" value="<?php if(isset( $quesDetailhidden )){ echo $quesDetailhidden->question; } ?>" name="bzrcp_related_ques1[]" type="text" placeholder="Enter first 4 charcters of question title to search" />
										<input class="bzrcp_related_ques_id1" name="bzrcp_related_ques_id1[]" type="hidden" />
										<ul class="list-gpfrm other1" id="bzrcp_related_ques_search"></ul>
										<span class="example">Optional( Add related question to answer here )</span>
									</div>
								</div>
											
								
								<?php } ?>
							</div>	 
						</div>
					
					
					
						<div class="bzrcp_field"  id="bzrcp_main_quesOptions" style="display:none;">
							<h3><?php _e('Question Choices', 'bzrcp'); ?></h3>
							<span><?php _e('Enter each answer on a new line with its redirect url (if any ).','bzrcp'); ?></span>
							<div id="answersUrls">
								<?php  
								$choices = $quesDetail->question_options;
								if( !empty($choices ) ){
								$answeroptions = unserialize($choices);
								if( isset($answeroptions) && is_array($answeroptions) ){
								$i = 0;
								$len = count($answeroptions);
								
								//~ print_r($answeroptions);
								
								foreach ($answeroptions as $qkey => $qval){
									
									$newqkey = stripslashes($qkey);
									if( isset ($qval['rel_ques']) ) {
										$quesDetailhidden = $this->bzrcp_get_question($qval['rel_ques']);
									}
								?>
								<div class="entry input-group col-xs-12">
									<div class="bzrcp_option1">
										<input class="form-control answers" value="<?php echo stripslashes($newqkey); ?>" name="bzrcp_answers[]" type="text" placeholder="Answer*" />
										<span class="example">Required*</span>
									</div>
									<div class="bzrcp_option2">
										<input class="form-control urls" value="<?php if(isset($qval['url'])){ echo $qval['url']; } ?>" name="bzrcp_urls[]" type="text" placeholder="Redirect URL" />
										<span class="example">Optional</span>
									</div>
									<div class="bzrcp_option3">
										<input class="form-control related_ques bzrcp_related_ques" value="<?php if(isset( $quesDetailhidden )){ echo $quesDetailhidden->question; } ?>" name="bzrcp_related_ques[]" type="text" placeholder="Enter first 4 charcters of question title to search" />
										<input type="hidden" class="bzrcp_related_ques_id" value="<?php if(isset( $qval['rel_ques'] )){ echo $qval['rel_ques'];} ?>" name="bzrcp_related_ques_id[]" />
										<ul class="list-gpfrm" id="bzrcp_related_ques_search"></ul>
										<span class="example">Optional( Add related question to answer here )</span>
									</div>
								
									<?php  if ($i == $len - 1) { ?>
										<span class="input-group-btn">
											<button type="button" class="btn btn-success btn-lg btn-add">
												<span class="glyphicon glyphicon-plus" aria-hidden="true"></span>
											</button>
										</span>
									<?php } else { ?>
										<span class="input-group-btn">
											<button type="button" class="btn btn-lg btn-remove btn-danger">
												<span class="glyphicon glyphicon-minus" aria-hidden="true"></span>
											</button>
										</span>	
									<?php } ?>
								</div>
								
								<?php $i++; } }
								 }
								?>
							</div>	 
						</div>
					
					
					<?php }  else { ?>
						
						<div class="bzrcp_field"  id="bzrcp_main_quesOptions" style="display:block;">
							<h3><?php _e('Question Choices', 'bzrcp'); ?></h3>
							<span><?php _e('Enter each answer on a new line with its redirect url (if any ).','bzrcp'); ?></span>
							<div id="answersUrls">
								<?php  
								$choices = $quesDetail->question_options;
								if( !empty($choices ) ){
								$answeroptions = unserialize($choices);
								if( isset($answeroptions) && is_array($answeroptions) ){
								$i = 0;
								$len = count($answeroptions);
								
								//~ print_r($answeroptions);
								
								foreach ($answeroptions as $qkey => $qval){
									
									$newqkey = stripslashes($qkey);
									if( isset ($qval['rel_ques']) ) {
										$quesDetailhidden = $this->bzrcp_get_question($qval['rel_ques']);
									}
								?>
								<div class="entry input-group col-xs-12">
									<div class="bzrcp_option1">
										<input class="form-control answers" value="<?php echo stripslashes($newqkey); ?>" name="bzrcp_answers[]" type="text" placeholder="Answer*" />
										<span class="example">Required*</span>
									</div>
									<div class="bzrcp_option2">
										<input class="form-control urls" value="<?php if(isset($qval['url'])){ echo $qval['url']; } ?>" name="bzrcp_urls[]" type="text" placeholder="Redirect URL" />
										<span class="example">Optional</span>
									</div>
									<div class="bzrcp_option3">
										<input class="form-control related_ques bzrcp_related_ques" value="<?php if(isset( $quesDetailhidden )){ echo $quesDetailhidden->question; } ?>" name="bzrcp_related_ques[]" type="text" placeholder="Enter first 4 charcters of question title to search" />
										<input type="hidden" class="bzrcp_related_ques_id" value="<?php if(isset( $qval['rel_ques'] )){ echo $qval['rel_ques'];} ?>" name="bzrcp_related_ques_id[]" />
										<ul class="list-gpfrm" id="bzrcp_related_ques_search"></ul>
										<span class="example">Optional( Add related question to answer here )</span>
									</div>
								
									<?php  if ($i == $len - 1) { ?>
										<span class="input-group-btn">
											<button type="button" class="btn btn-success btn-lg btn-add">
												<span class="glyphicon glyphicon-plus" aria-hidden="true"></span>
											</button>
										</span>
									<?php } else { ?>
										<span class="input-group-btn">
											<button type="button" class="btn btn-lg btn-remove btn-danger">
												<span class="glyphicon glyphicon-minus" aria-hidden="true"></span>
											</button>
										</span>	
									<?php } ?>
								</div>
								
								<?php $i++; } }
								 }
								?>
							</div>	 
						</div>
						
						
						
						
						<div class="bzrcp_field" id="bzrcp_main_quesOptions1" style="display:none;">
							<h3><?php _e('Question Options', 'bzrcp'); ?></h3>

							<span><?php _e('Enter Redirect URL & related question (if any ).','bzrcp'); ?></span>
					
							<div id="answersUrls">
								
								<?php  
									$choices = $quesDetail->question_options;
									if( !empty($choices ) ){
									$answeroptions = unserialize($choices);
									if( isset($answeroptions) && is_array($answeroptions) ){
									$i = 0;
									$len = count($answeroptions);
									
									//~ print_r($answeroptions);
									
									foreach ($answeroptions as $qkey => $qval){
										
										$newqkey = stripslashes($qkey);
										if( isset ($qval['rel_ques']) ) {
											$quesDetailhidden = $this->bzrcp_get_question($qval['rel_ques']);
										}
								?>
								
								<div class="entry input-group col-xs-12">
									<div class="bzrcp_option2">
										<input class="form-control urls" name="bzrcp_urls1[]" value="<?php if(isset($qval['url'])){ echo $qval['url']; } ?>" type="text" placeholder="Redirect URL" />
										<input class="form-control answers" name="bzrcp_answers1[]" type="hidden" placeholder="Answer*" />
										<span class="example">Optional</span>
									</div>
									<div class="bzrcp_option3">
										<input class="form-control related_ques bzrcp_related_ques1" value="<?php if(isset( $quesDetailhidden )){ echo $quesDetailhidden->question; } ?>" name="bzrcp_related_ques1[]" type="text" placeholder="Enter first 4 charcters of question title to search" />
										<input class="bzrcp_related_ques_id1" name="bzrcp_related_ques_id1[]" type="hidden" />
										<ul class="list-gpfrm other1" id="bzrcp_related_ques_search"></ul>
										<span class="example">Optional( Add related question to answer here )</span>
									</div>
								</div>
								
								<?php $i++; } }
								 } else {
								?>
								
								<div class="entry input-group col-xs-12">
									<div class="bzrcp_option2">
										<input class="form-control urls" name="bzrcp_urls1[]" value="<?php if(isset($qval['url'])){ echo $qval['url']; } ?>" type="text" placeholder="Redirect URL" />
										<input class="form-control answers" name="bzrcp_answers1[]" type="hidden" placeholder="Answer*" />
										<span class="example">Optional</span>
									</div>
									<div class="bzrcp_option3">
										<input class="form-control related_ques bzrcp_related_ques1" value="<?php if(isset( $quesDetailhidden )){ echo $quesDetailhidden->question; } ?>" name="bzrcp_related_ques1[]" type="text" placeholder="Enter first 4 charcters of question title to search" />
										<input class="bzrcp_related_ques_id1" name="bzrcp_related_ques_id1[]" type="hidden" />
										<ul class="list-gpfrm other1" id="bzrcp_related_ques_search"></ul>
										<span class="example">Optional( Add related question to answer here )</span>
									</div>
								</div>
											
								<?php } ?>
							</div>	 
						</div>
						
						
						<?php } ?>
					<?php wp_nonce_field('questions_editnonce', 'questions_editnonce'); ?>
					<?php submit_button(); ?>			
				</div>
			</form>
		</div>
			<?php 
		}
		
		

		
		/**
		 * Add question func callback
		*/
		public function bzrcp_add() {
			
			if ( isset($_POST['submit']) ) {

			    // verify authentication (nonce)
			    if ( !isset( $_POST['questions_nonce'] ) )
			        return;
			    // verify authentication (nonce)
			    if ( !wp_verify_nonce($_POST['questions_nonce'], 'questions_nonce') )
			        return;
			    return $this->bzrcp_updateSettings();
			}
		}



		
		
		/**
		 * Edit question func callback
		*/
		public function bzrcp_edit() {
			
			if ( isset($_POST['submit']) ) {

			    // verify authentication (nonce)
			    if ( !isset( $_POST['questions_editnonce'] ) )
			        return;
			    // verify authentication (nonce)
			    if ( !wp_verify_nonce($_POST['questions_editnonce'], 'questions_editnonce') )
			        return;
			    return $this->bzrcp_editQuestion($_GET['question']);
			}
		}
	
			
		
		
		/**
		 * Add question settings func callback
		*/
		public function bzrcp_updateSettings() {	
			
			global $wpdb;
			
			if( is_multisite() ) {
				switch_to_blog(1);
					$wpdb->prefix;
				restore_current_blog();
			} else {
				$wpdb->prefix;
			}
			
			$quesTable = $wpdb->prefix.'bzrcp_questions';
			
			$quesOpt = array();

			if( $_POST['bzrcp_quesType'] != 'mcq' ) {
				
				$urls = $_POST['bzrcp_urls1'];
				$requestions = $_POST['bzrcp_related_ques_id1'];
				
				foreach ($requestions as $id => $key) {
					$quesOpt[$key] = array(
						'rel_ques'  => $key,
						'url' => $urls[$id],
					);
				}
				
			} else {
		
				$answers = $_POST['bzrcp_answers'];	
				$urls = $_POST['bzrcp_urls'];
				$requestions = $_POST['bzrcp_related_ques_id'];
			
				foreach ($answers as $id => $key) {
					$quesOpt[$key] = array(
						'answer' => $key,
						'url'  => $urls[$id],
						'rel_ques' => $requestions[$id],
					);
				}
			}
			
			if( isset($quesOpt) && is_array($quesOpt) ) {
				$quesOptions = serialize($quesOpt);
			} else {
				$quesOptions = '';
			}
			
			if( $_POST['bzrcp_question'] != '' && $_POST['bzrcp_quesType'] != '' && ( isset($quesOptions) && $quesOptions != '' ) ) {
				
				$wpdb->insert( 
					$quesTable, 
					array( 
						'question' => $_POST['bzrcp_question'],
						'question_type' => $_POST['bzrcp_quesType'],
						'question_options' => $quesOptions,
					)
				);
				$insert = $wpdb->insert_id; //recent insert ID 
			}      
			
			if( $insert > 0 ) {
				$this->quesAdded = true;	
			} 
			
		}
		
		
		
		
		/**
		 * Update question settings func callback
		*/
		public function bzrcp_editQuestion($qid) {	
			
			global $wpdb;
			
			if( is_multisite() ) {
				switch_to_blog(1);
					$wpdb->prefix;
				restore_current_blog();
			} else {
				$wpdb->prefix;
			}
			
			$quesTable = $wpdb->prefix.'bzrcp_questions';
			$quesOptedit = array();

			
			if( $_POST['bzrcp_quesType'] != 'mcq' ) {

				$urlsedit = $_POST['bzrcp_urls1'];
				$requestionsedit = $_POST['bzrcp_related_ques_id1'];
							
				foreach ($requestionsedit as $id => $key) {
					$quesOptedit[$key] = array(
						'url'  => $urlsedit[$id],
						'rel_ques' => $key,
					);
				}
				
				
			} else {
				
				$answersedit = $_POST['bzrcp_answers'];
				$urlsedit = $_POST['bzrcp_urls'];
				$requestionsedit = $_POST['bzrcp_related_ques_id'];
			
				foreach ($answersedit as $id => $key) {
					$quesOptedit[$key] = array(
						'answer' => $key,
						'url'  => $urlsedit[$id],
						'rel_ques' => $requestionsedit[$id],
					);
				}
			}
			

			if( isset($quesOptedit) && is_array($quesOptedit) ) {
				$quesOptionsedit = serialize($quesOptedit);
			} else {
				$quesOptionsedit = '';
			}
			
			if( $_POST['bzrcp_question'] != '' && $_POST['bzrcp_quesType'] != '' && ( isset($quesOptionsedit) && $quesOptionsedit != '' ) ) {

				$wpdb->update( 
					$quesTable, 
					array( 
						'question' => $_POST['bzrcp_question'], 
						'question_type' => $_POST['bzrcp_quesType'],
						'question_options' => $quesOptionsedit,
					),
					array( 'id' => $qid), 
					array( 
						'%s'
					), 
					array( '%d' )
				);
				$update = 1; //recent insert ID 	
			}      
			if( $update > 0 ) {
				$this->quesUpdated = true;	
			} 
			
		}
	   
	   
		
		
		
		/**
		 * Question Notices
		*/	   
		public function bzrcp_question_notices(){
			
			$status = (isset($_REQUEST['status'])) ? sanitize_text_field( $_REQUEST['status'] ) : '';

			if ( empty( $status ) )
				return;

			if ( 'deleted' == $status )
				$updated_message = esc_html( __( 'Questions deleted.', 'bzrcp' ) );

			if ( empty( $updated_message ) )
				return;

			?>
			<div class="notice notice-success is-dismissible">
				<p> <?php echo $updated_message; ?> </p>
			</div>
			<?php
		}
		
		
	   
		
		/**
		 * Search questions data Ajax Call
		*/	   
		public function bzrcp_search_questions_call(){
			
			global $wpdb;
			
			if( isset($_POST) ) {
				
				if ( is_multisite() ) {
					switch_to_blog(1);
					$tableName = $wpdb->prefix . "bzrcp_questions";
					$allques = $wpdb->get_results("SELECT id,question FROM ".$tableName." WHERE question LIKE '%".$_POST['searchKey']."%'");
					restore_current_blog();
				} else {
					$tableName = $wpdb->prefix . "bzrcp_questions";
					$allques = $wpdb->get_results("SELECT id,question FROM ".$tableName." WHERE question LIKE '%".$_POST['searchKey']."%'");
				}
			}
			if(isset($allques) && !empty($allques)) {
				if(count($allques) > 0) {
					foreach($allques as $allqu) {
						$html ='<li class="list-gpfrm-list" id="'.$allqu->id.'" data-title="'.$allqu->question.'">'.$allqu->question.'</li>';
					}
				} else {
						$html ='<li class="list-gpfrm-list">No results found.</li>';
				}
			}
			echo $html;
			die();
		}
		
		
		
		
		
		
		/**
		 * Save Modal data Ajax Call
		*/	   
		public function bzrcp_save_modalData_call(){
			
			global $wpdb;
			$blog_id = get_current_blog_id(); //current blog
			$current_user = wp_get_current_user();
			
			$userId = $current_user->ID;
			
			if( isset($_POST) ) {	
				
				parse_str($_POST['modalData'], $fields);   //parse string
				if( isset($fields) ) {
					foreach($fields as $fieldk => $fieldv) {
						$finalArr[$fieldk]= $fieldv;  //val
					}
				}
				
				//if not empty valyes
				if( isset($finalArr) ) {
					
					$stats = array();
					foreach( $finalArr as $statkey => $statval) {
						
						if( $statval == '' ) { continue; } 
						$qkey = explode('_',$statkey);
						$qId = $qkey[1];
						
						$stats[] = array(
							'user_id' => $userId,
							'question_id' => $qId,
							'answer_id' => $statval,
						);
					}
					
					
					if( isset($stats) && !empty($stats) ) {
					
						//Save user stat in table
						if( is_multisite() ) {
							
							switch_to_blog(1);
							
								foreach ( $stats as $stat ) {
									
									$query = $qres = $qcnt = '';
									$query = "SELECT * from  ".$wpdb->prefix."bzrcp_questions_stat WHERE user_id=".$stat['user_id']." AND question_id=".$stat['question_id'];
									$qres = $wpdb->get_results($query, ARRAY_A);
																	
									if(isset($qres) && !empty($qres)) {
										
										$qcnt = count($qres);
										if( $qcnt > 0 ) {
											$execute= $wpdb->query( 'UPDATE '.$wpdb->prefix.'bzrcp_questions_stat SET answer_id ="'.$stat['answer_id'].'"  WHERE user_id ='.$stat['user_id'].' AND question_id='.$stat['question_id'] );
											if($execute){
												$statId = 1; //recent insert ID 
											} 
										} else {
											$wpdb->insert( $wpdb->prefix.'bzrcp_questions_stat', $stat );
											$statId = $wpdb->insert_id; //recent insert ID 
										}
										
									} else {
										$wpdb->insert( $wpdb->prefix.'bzrcp_questions_stat', $stat );
										$statId = $wpdb->insert_id; //recent insert ID 
									}
								}
							
							restore_current_blog();
							
						} else {	
								
							foreach ( $stats as $stat ) {
									
									$query = $qres = $qcnt = '';
									$query = "SELECT * from  ".$wpdb->prefix."bzrcp_questions_stat WHERE user_id=".$stat['user_id']." AND question_id=".$stat['question_id'];
									$qres = $wpdb->get_results($query, ARRAY_A);
									
									if(isset($qres) && !empty($qres)) {
										
										$qcnt = count($qres);
										if( $qcnt > 0 ) {
											$execute= $wpdb->query( 'UPDATE '.$wpdb->prefix.'bzrcp_questions_stat SET answer_id ="'.$stat['answer_id'].'"  WHERE user_id ='.$stat['user_id'].' AND question_id='.$stat['question_id'] );
											if($execute){
												$statId = 1; //recent insert ID 
											}
										} else {
											$wpdb->insert( $wpdb->prefix.'bzrcp_questions_stat', $stat );
											$statId = $wpdb->insert_id; //recent insert ID 
										}
										
									} else {
										$wpdb->insert( $wpdb->prefix.'bzrcp_questions_stat', $stat );
										$statId = $wpdb->insert_id; //recent insert ID 
									}
								}	
						}
						if( $statId == '' ) { 
							echo '0';
						} else if ( $statId > 0 ) {
							echo '1';
						}
					}
			 }
			}
			die();
		}
					
		
		
	   
	}  //end Class
?>
