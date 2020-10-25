<?php
/**
* Plugin Name:	BLITZ - Live Editor PRO for Elementor
* Plugin URI: 	https://waaspro.com
* Description:	An Editor Your Customers Will Love. Give your clients a fast and easy way to edit content without needing to touch the Elementor page builder! Build Your Pages with Elementor, Edit Them with Live Editor Pro.
* Version:			1.46
* Author:			WaaS.PRO
* Author URI:		https://waaspro.com
* License:			GPL2 etc
* Network:			True
*/
use Elementor\Core\Files\CSS\Post as Post_CSS;
if (!defined('ABSPATH')) {
	exit;
}
if (!defined('WPINC')) {
	exit;
}
update_option('software_license_key_LEPE', 'activated');
require_once(plugin_dir_path( __FILE__ ) . 'lmgr/LicenceManager.php');
require_once(plugin_dir_path( __FILE__ ) . 'lmgr/AutoUpdater.php');
require_once(plugin_dir_path( __FILE__ ) . 'icons.php');
require_once(plugin_dir_path( __FILE__ ) . 'dashicons.php');

define( 'EDITPRO_PlUGIN_MAIN_FILE', 'blitz-live-editor-pro-elementor/elementor-editor.php' );
define('EDITPRO_VERSION','1.46');

if(!class_exists('ElementorEditor')){
	class ElementorEditor {
		public $imageID;
		public $roleID;
		public $elementID;
		public $moduleformData;
		public $audioData;
		public $lmgr;
		
		public function __construct() {
			// Check if this is network enabled
			$this->lmgr = new LicenceManagerGlobal();
			$productId = 'LEPE';
			$this->lmgr->init($productId,'Live Editor PRO for Elementor','editpro');
			
			if(is_multisite()){
				switch_to_blog(1);
				$license_key1 = get_site_option('software_license_key_' . $productId);
				restore_current_blog();
			}else{
				$license_key1 = get_option('software_license_key_' . $productId);
			}
			//auto updater start
			Blitz_run_updater("https://waas-pro.com/index.php", 'editpro', EDITPRO_PlUGIN_MAIN_FILE,EDITPRO_VERSION,$license_key1,$productId);
			//auto updater end
			
			
			//~ if (!isset($_COOKIE['lepelementor_license_cookie'])) {
			
				//~ $this->responsecheck = $lmgr->license_check($license_key1,'status-check');
								
				//~ if ($this->responsecheck[0]==1) {
					//~ setcookie('lepelementor_license_cookie', 'active', strtotime('+7 day'));
					//~ $this->responsecheck[0] = 1;
				//~ } else {
					//~ $this->responsecheck[0] = 0;
				//~ }
			//~ } else {
					//~ $this->responsecheck[0] = 1;
			//~ }
		
			if( $license_key1 != '') {	
				$validLic = $this->lepElem_validLicense($license_key1);
						
				if (!$validLic) {
					if ( is_multisite()) { 
						add_action('network_admin_notices', array($this,'editpro_notice'));
					}else if(!is_multisite()){
						add_action('admin_notices', array($this,'editpro_notice'));
					}
				}else{
					add_action('init', array(&$this, 'Plugin'), 0);
				}
			}
			
			$this->imageID = array();
			$this->roleID = 0;
		}
		public function editpro_notice() {
			$class = 'notice notice-error';
			$message = __( "Don't have a license key? Purchase Blitz Live Editor PRO for Elementor Now.");
			printf( '<div class="%1$s"><p>%2$s <a href="https://adminuipro.com">Click Here</a></p></div>', esc_attr( $class ), esc_html( $message ) ); 
		}
		
		public function Plugin() {
			if(isset($_REQUEST['page'])){
				if($_REQUEST['page'] == 'editpro'){
					wp_enqueue_style('editor_select_style', plugin_dir_url( __FILE__ ) . 'assets/select2/select2.min.css', array(), EDITPRO_VERSION);
					wp_enqueue_script('editor_select_script', plugin_dir_url( __FILE__ ) . 'assets/select2/select2.min.js', array(), EDITPRO_VERSION,true);
					wp_enqueue_style( 'editpro_style_colorpicker', plugin_dir_url( __FILE__ ) . 'assets/colorpicker/css/colorpicker.css',array(),EDITPRO_VERSION);
					wp_enqueue_script( 'editpro_script_colorpicker',plugin_dir_url( __FILE__ ) . 'assets/colorpicker/colorpicker.js',array(),EDITPRO_VERSION,true);
					add_action('admin_footer', array($this, 'settingsPage'),1000000);
					//$this->settingsPage();
				}
			}
			add_action('wp_head',array($this, 'add_editor'));
			add_action( 'wp_ajax_save_editor', array($this, 'save_editor'));
			add_action( 'wp_ajax_nopriv_save_editor', array($this, 'save_editor'));
			add_action( 'wp_ajax_save_editor_permission', array($this, 'save_editor_permission'));
			add_filter( 'upload_mimes', array($this, 'save_editor_custom_mime_types'));
			//add_filter( 'ajax_query_attachments_args',  array($this, 'filter_media_for_user_only') );
		}
		public function save_editor_custom_mime_types($mimes){
			$enable = get_option('elementor_allow_svg', false);
			if($enable){
				$mimes['svg'] = 'image/svg+xml';
				$mimes['svgz'] = 'image/svg+xml';
			}
			unset( $mimes['exe'] );
			return $mimes;
		} 
		public function settingsPage(){
			require_once(plugin_dir_path( __FILE__ ) . 'settingsform.php');
		}
		
		
		private function array_find_deep($array, $search, $keys = array())
		{
			foreach($array as $key => $value) {
				if (is_array($value)) {
					$sub = $this->array_find_deep($value, $search, array_merge($keys, array($key)));
					if (count($sub)) {
						return $sub;
					}
				} elseif ($value === (string)$search) {
					return array_merge($keys, array($key));
				}else{
					//~ if($value == '8304534'){
						//~ echo '<br>'.gettype($value).'<---->'.gettype($search);
						//~ echo '<br>'.($value).'<---->'.($search);
					//~ }
				}
			}

			return array();
		}
		public function setArray(&$array, $keys, $value) {
			$keys = explode(".", $keys);
			if($value=='-121||'){
				$ukey = $keys[count($keys )-1];
				unset($keys[count($keys )-1]);
			}
			$current = &$array;
			foreach($keys as $key) {
				$current = &$current[$key];
			}
			if($value=='-121||'){
				unset($current[$ukey]);
			}else{
				$current = wp_unslash($value);
			}
		}
		private function array_find_images($array, $search, $keys = array())
		{
			if(is_array($array)){
				foreach($array as $key => $value) {  
					if (is_array($value)) {
						if(isset($value['settings'][$search])){
							$this->imageID[$search][] = $value['id'];
						}
						$this->array_find_images($value, $search, $keys);
					}
				}
			}
		}
		private function array_find_elements($array, $keys = array())
		{
			if(is_array($array)){
				foreach($array as $key => $value) {  
					if (is_array($value)) {
						if(isset($value['settings'])){
							$this->elementID[] = $value['id'];
							if(isset($value['widgetType'])){
								if($value['widgetType'] == 'form'){
									$this->moduleformData[$value['id']] = array('email_to' => $value['settings']['email_to'],'email_from' => $value['settings']['email_from'],'email_from_name' => $value['settings']['email_from_name'],'email_to_2' => $value['settings']['email_to_2'],'email_from_2' => $value['settings']['email_from_2'],'email_from_name_2' => $value['settings']['email_from_name_2']);
								}
								if($value['widgetType'] == 'audio'){
									$this->audioData[$value['id']] = $value['settings']['link']['url'];
								}
							}
						}
						$this->array_find_elements($value, $keys);
					}
				}
			}
		}
		public function filter_media_for_user_only( $query ) {
			$user = wp_get_current_user();
			if(!in_array('administrator',$user->roles)){
				$query['author'] = get_current_user_id();
			}
			return $query;
		}
		public function backgroundColor($colorPT,$vertical=0) {
		 
			if(strstr($colorPT,'||')){
				$colorPTs = explode('||',$colorPT);
				$colors = array();
				$lastcolor = '';
				foreach($colorPTs as $colorPTs1){
					$color = explode(' ',$colorPTs1);
					$colors[] = $color[0];
					$lastcolor = $color[0];
				}
				$final = str_replace('||',', ',$colorPT);
			}else{
				$final = $colorPT;
				$color = explode(' ',$colorPT);
				$colors[] = $color[0];
				$lastcolor = $color[0];
			}
			if($vertical == '1'){
				$html = "background: ".$colors[0]." !important; /* Old browsers */
	background: -moz-linear-gradient(top, ".$final.") !important;
	background: -webkit-linear-gradient(top, ".$final.") !important;
	background: linear-gradient(to bottom, ".$final.") !important;
	filter: progid:DXImageTransform.Microsoft.gradient( startColorstr='".$colors[0]."', endColorstr='".$lastcolor."',GradientType=1 ) !important;";
			}else{
				$html = "background: ".$colors[0]." !important; /* Old browsers */
	background: -moz-linear-gradient(left, ".$final.") !important;
	background: -webkit-linear-gradient(left, ".$final.") !important;
	background: linear-gradient(to right, ".$final.") !important;
	filter: progid:DXImageTransform.Microsoft.gradient( startColorstr='".$colors[0]."', endColorstr='".$lastcolor."',GradientType=1 ) !important;";
		}
		return $html;
		
		}
		public function user_permissions() {
			global $post,$wp_roles;
			$enable = 0;
			$full_permission = $this->getOption('full_permission');
			$limited_permission = $this->getOption('limited_permission');
			$invidual_permission = get_post_meta( $post->ID, 'invidual_permission', true );
			$user = wp_get_current_user();
			$check = 1;
			if(empty($user->roles)){
				$check = 2;
			}
			if(!is_array($full_permission)){
				$full_permission = array();
			}
			if(!is_array($limited_permission)){
				$limited_permission = array();
			}
			if(!is_array($invidual_permission)){
				$invidual_permission = array();
			}
			#invidual Permissions
			if(is_array($invidual_permission)){
				if(count($invidual_permission) > 0){
					if(in_array($user->ID,$invidual_permission)){
						$enable = 1;
						$this->roleID = 3;
					}
				}
			}
			#Limited Permissions
			if(is_array($limited_permission)){
				if(count($limited_permission) > 0){
					if($check == '1'){
						$result = array_intersect($limited_permission, $user->roles);
						if(count($result) > 0){
							$enable = 1;
							$this->roleID = 2;
						}
					}
					if($check == '2'){
						$yes = 0;
						foreach($limited_permission as $per){
							if(current_user_can($per)){
								$yes = 1;
							}
						}
						if($yes == '1'){
							$enable = 1;
							$this->roleID = 2;
						}
					}
				}
			}
			#full Permissions
			if(is_array($full_permission)){
				if(count($full_permission) > 0){
					if($check == '1'){
						$result = array_intersect($full_permission, $user->roles);
						if(count($result) > 0){
							$enable = 1;
							$this->roleID = 1;
						}
					}
					if($check == '2'){
						$yes = 0;
						foreach($full_permission as $per){
							if(current_user_can($per)){
								$yes = 1;
							}
						}
						if($yes == '1'){
							$enable = 1;
							$this->roleID = 1;
						}
					}
				}
			}
			
			if ( is_super_admin() ) {
				$enable = 1;
				$this->roleID = 1;
			}
			return $enable;
		}
		public function add_editor() {
			global $post,$wp_roles;
			$data = get_post_meta( $post->ID, '_elementor_data', true );
			
			$lockeddata = get_post_meta( $post->ID, '_elementor_lock', true );
			$enable = 0;
			$enable = $this->user_permissions();
			if( $enable == '1' ) {
				if(isset($_REQUEST['showData'])){print_r($data);die;}
				if(!current_user_can('administrator')){
					$user = new WP_User($user->ID);
					$user->add_cap('upload_files');
				}
				
				if( $data ) { 
					if(is_array($data)){
						$obj = $data;
					}else{
						$obj = json_decode($data, true);
					}
					
					$this->array_find_elements($obj);
					wp_enqueue_style('thickbox');
					wp_enqueue_script('thickbox');
					if(function_exists('wp_enqueue_media')){
						wp_enqueue_media();
					}else{
						wp_enqueue_script( 'editor' );
					}
					wp_enqueue_style('editor_style', plugin_dir_url( __FILE__ ) . 'assets/top.css', array(), EDITPRO_VERSION);
					wp_enqueue_style('editor_font_style', plugins_url() . '/elementor/assets/lib/font-awesome/css/all.min.css', array(), EDITPRO_VERSION);
					
					if(is_multisite()){
						switch_to_blog(1);
						$floating = get_option('floatingSetting');
						restore_current_blog();
					}else{
						$floating = get_option('floatingSetting');
					}
					$customStyle = '';
					if(isset($floating['backgroundColorPT'])){
						$customStyle .='.floatingbar {'.$this->backgroundColor($floating['backgroundColorPT'],1).'}';
					}
					if(isset($floating['color'])){
						$customStyle .='.front span,.floatingbar .lock::before,.floatingbar .icon,.floatingbar .submit, .floatingbar .btn ,.floatingbar .btn.publish, .floatingbar .btn.discard,.floatingbar .bgimg::before,.floatingbar .iconChange::before{color:'.$floating['color'].';}';
						$customStyle .='.floatingbar .icon,.floatingbar .submit, .floatingbar .btn ,.floatingbar .btn.publish, .floatingbar .btn.discard{border-color:'.$floating['color'].';}';
						
					}
					if(isset($floating['btnColor'])){
						$customStyle .='.floatingbar .submit, .floatingbar .btn.cancel{color:'.$floating['btnColor'].';}';
					}
					if(isset($floating['btnColorBg'])){
						$customStyle .='.floatingbar .submit, .floatingbar .btn.cancel{background-color:'.$floating['btnColorBg'].';}';
					}
					if($customStyle != ''){
						wp_add_inline_style( 'editor_style', $customStyle );
					}
					
					wp_enqueue_script('editortmce_script', plugin_dir_url( __FILE__ ) . 'assets/tinymce/tinymce.min.js', array(), EDITPRO_VERSION,true);
					wp_enqueue_style('editor_select_style', plugin_dir_url( __FILE__ ) . 'assets/select2/select2.min.css', array(), EDITPRO_VERSION);
					$j_siteurl = get_site_url();
					$custom_script = 'var j_siteurl = "'.$j_siteurl.'";';
					
					if(isset($floating['bgimg'])){
						$custom_script .= 'var j_bgimg = " '.$floating['bgimg'].' ";';
					}else{
						$custom_script .= 'var j_bgimg = " dashicons-admin-appearance ";';
					}
					if(isset($floating['iconChange'])){
						$custom_script .= 'var j_iconChange = " '.$floating['iconChange'].' ";';
					}else{
						$custom_script .= 'var j_iconChange = " dashicons-admin-tools ";';
					}
					if(isset($floating['hidemenu'])){
						$custom_script .= 'var j_hidemenu = '.$floating['hidemenu'].';';
					}else{
						$custom_script .= 'var j_hidemenu = 0;';
					}
					//wp_add_inline_script( 'editortmce_script', $custom_script );
					wp_enqueue_script('editor_select_script', plugin_dir_url( __FILE__ ) . 'assets/select2/select2.min.js', array(), EDITPRO_VERSION,true);
					wp_enqueue_script('editor_script', plugin_dir_url( __FILE__ ) . 'assets/top.js', array(), EDITPRO_VERSION,true);
					
					//add module scripts
					$editor_script_module = '';
					$mydir = plugin_dir_path( __FILE__ ).'/modules/';
					$moduleDir = scandir($mydir); 
					
					foreach($moduleDir as $moduleDir1){
						if($moduleDir1 == '.' || $moduleDir1 == '..'){
							continue;
						}
						if(is_dir($mydir.'/'.$moduleDir1)){
							$customDir = scandir($mydir.'/'.$moduleDir1); 
							foreach($customDir as $customDir1){
								if($customDir1 == '.' || $customDir1 == '..'){
									continue;
								}
								if(strstr($customDir1,'.js')){
									wp_enqueue_script('editor_script_module_'.$moduleDir1, plugin_dir_url( __FILE__ ) . 'modules/'.$moduleDir1.'/'.$customDir1, array(), EDITPRO_VERSION,true);
									//$editor_script_module .= @file_get_contents($mydir.'/'.$moduleDir1.'/'.$customDir1);
								}
							}

						}
					}

					//wp_add_inline_script( 'editor_script_module', ' /* <![CDATA[ */ '.$editor_script_module.' /* ]]> */ ' );
					$this->array_find_images($obj,'background_image'); 
					$this->array_find_images($obj,'_background_image');
					$this->array_find_images($obj,'image'); 
					//$this->array_find_images($obj,'testimonial_image');
					
					$lockeddata1 = array();
					if(is_array($lockeddata)){
						foreach($lockeddata  as $key=>$value){
							$lockeddata1[] = $key;
						}
					}
					$custom_script .= ' var imagesupload = '.json_encode($this->imageID).';var save_ajax_url="'.admin_url('admin-ajax.php').'";var postID="'.$post->ID.'"; var lockelement='.json_encode($lockeddata1).'; var elements_data = '.json_encode($this->elementID).'; var elements_form_data = '.json_encode($this->moduleformData).';  var elements_audio_data = '.json_encode($this->audioData).';';
					
					wp_add_inline_script( 'editortmce_script', ' /* <![CDATA[ */ '.$custom_script.' /* ]]> */ ' );
					add_action( 'wp_footer', array(&$this, 'floatingbarBox'));
					
				 }
			}
		}
		public function floatingbarBox() { 
			global $post,$wp_roles;
			$full_permission = $this->getOption('full_permission');
			$limited_permission = $this->getOption('limited_permission');
			$invidual_permission = get_post_meta( $post->ID, 'invidual_permission', true );
				if(!is_array($full_permission)){
					$full_permission = array();
				}
				if(!is_array($limited_permission)){
					$limited_permission = array();
				}
				if(!is_array($invidual_permission)){
					$invidual_permission = array();
				}
			?>
			<?php if($this->roleID != '0'){ ?>
				<div id="modal-window-googlemap" style="display:none;">
				<div class="selectIcons-map">
					<div class="selectbox-icon-box2">
						<p><label>Location</label> <input id="map_location"  name="map_location" type="text" placeholder=""></p>
						<p><label> Zoom </label> <input id="map_zoom"  name="map_zoom"  type="number" placeholder=""></p>
						<p><label> Height </label> <input id="map_height"  name="map_height"  type="number" placeholder=""></p>
					</div>
				</div>
			</div>
			<div id="modal-window-id" style="display:none;">
				<div class="selectIcons-main">
					<div class="selectbox-icon">
						<div class="selectbox-icon-box1">
							<ul id="myListTab" class="myListTab" >
								<li rel="fa-" class="active">All Icons</li>
								<li rel="far ">Font Awesome - Regular</li>
								<li rel="fas ">Font Awesome - Solid</li>
								<li rel="fab ">Font Awesome - Brands</li>
							</ul>
							<div class="selectbox-icon-upload">
								<div  class="media-svg-select">Upload SVG</div>
							</div>
						</div>
						<div class="selectbox-icon-box2">
							<div class="search-box">
								<input id="myInput" type="text" placeholder="Filter by name">
								<i class="fab fa-searchengin"></i>

							</div>
							<div class="search-box-result">
								<div class="search-box-title">All Icons</div>
								<ul id="myList" class="myList">
									<?php 
									$iconslist = ElementorIconList::get_icons(); 
									
									foreach($iconslist as $key=>$value){
										if(strstr($key,'fal')) {continue;}
										echo '<li rel="'.$key.'" ><label><i class="'.$key.'"></i><span>'.$value.'</span></label></li>';
									}
									?>
								</ul>
							</div>
						</div>
					</div>
				</div>
			</div>
			<div style="display:none;">
				<a href="#TB_inline?width=600&inlineId=modal-window-id" class="thickbox" id="iconSelect" >Icon</a>
			</div>
			<div id="modal-window-socialIconSelect" style="display:none;">
				<div class="selectIcons-main selectIcons-main-social">
					<div class="selectbox-icon">
						<div class="selectbox-icon-box1">
							<ul id="myListTab" class="myListTab" >
								<li rel="recom-ed" class="active">Recommended</li>
								<li rel="fa-" >All Icons</li>
								<li rel="far ">Font Awesome - Regular</li>
								<li rel="fas ">Font Awesome - Solid</li>
								<li rel="fab ">Font Awesome - Brands</li>
							</ul>
							<div class="selectbox-icon-upload">
								<div  class="media-svg-select">Upload SVG</div>
							</div>
							<div class="selectbox-icon-upload">
								<input type="text" name = "url" id="selectSocialUrl" placeholder=" Target url"/>
							</div>
							<div class="selectbox-icon-upload">
								Separate Window : <input type="checkbox" name = "separate" id="selectSocialSep" />
							</div>
						</div>
						<div class="selectbox-icon-box2">
							<div class="search-box">
								<input id="myInput" type="text" placeholder="Filter by name">
								<i class="fab fa-searchengin"></i>

							</div>
							<div class="search-box-result">
								<div class="search-box-title">All Icons</div>
								<ul id="myList" class="myList">
									<?php 
									$iconslist = ElementorIconList::get_icons(); 
									$riconslist = ElementorIconList::get_recommended_icons();
									
									foreach($iconslist as $key=>$value){
										if(strstr($key,'fal')) {continue;}
										$rclass = '';
										if(in_array($key,$riconslist)){ $rclass = ' recom-ed'; }
										echo '<li rel="'.$key.$rclass.'" ><label><i class="'.$key.'"></i><span>'.$value.'</span></label></li>';
									}
									?>
								</ul>
							</div>
						</div>
					</div>
				</div>
			</div>
			<div style="display:none;">
				<a href="#TB_inline?width=600&inlineId=modal-window-socialIconSelect" class="thickbox" id="socialIconSelect" >Icon</a>
			</div>
			<?php } ?>
			<?php if($this->roleID == '1'){ ?>
			
			<div class="floatingbar_box">
				<div class="inner-content">
					<h3>Manage Roles</h3>
					
					<div class="admin_box">
						<h4>Total Access</h4>
						<p>Total Access is generally reserved for Site Admins. This role gets full access, including managing user permissions.</p>
						<select class="basic-multiple-select" name="full_permission" multiple="multiple">
							<?php 
								foreach($wp_roles->role_names as $key=>$roles){
									if(in_array($key,$full_permission)){
										echo '<option value="'.$key.'" selected>'.$roles.'</option>';
									}else{
										echo '<option value="'.$key.'">'.$roles.'</option>';
									}
									
								}
							?>
						</select>
					</div>
					
					<div class="limited_box">
						<h4>Limited Access</h4>
						<p>This access level is managed by Total Access users and are unable to edit other user’s permissions.</p>
						<select class="basic-multiple-select" name="limited_permission" multiple="multiple">
							<?php 
								foreach($wp_roles->role_names as $key=>$roles){
									if(in_array($key,$limited_permission)){
										echo '<option value="'.$key.'" selected>'.$roles.'</option>';
									}else{
										echo '<option value="'.$key.'">'.$roles.'</option>';
									}
								}
							?>
						</select>
					</div>
					<div class="otheruser_box">
						<h4>Limited Access on This Page Only</h4>
						<p>Allow individual users to receive limited access to features only available on this page.</p>
						<select class="basic-multiple-select" name="invidual_permission" multiple="multiple">
							<?php 
								$blogusers = get_users();
								foreach($blogusers as $key=>$users){
									if(in_array($users->ID,$invidual_permission)){
										echo '<option value="'. $users->ID.'" selected>'. $users->display_name .' ('.$users->ID.')</option>';
									}else{
										echo '<option value="'. $users->ID.'">'. $users->display_name .' ('.$users->ID.')</option>';
									}
								}
							?>
						</select>
					</div>
					
				</div>
			</div>
			<?php } ?>
		<?php }
		public function shortce($shotcode) {
			$attr = array();
			$type = 0;
			if(strstr($shotcode,"shortce")){
				$type = 1;
				$shotcodeA = explode("[shortce",$shotcode);
				$shotcodeB = explode("]",$shotcodeA[1]);
				$shotcode1 = explode(' ',$shotcodeB[0]);
				
				if(is_array($shotcode1)){
					foreach($shotcode1 as $shotcode2){
						if(trim($shotcode2) != ''){
							if(strstr($shotcode2,"=")){
								$shotcode3 = explode('=',trim($shotcode2));
								$attr[trim($shotcode3[0])] = trim($shotcode3[1]);
							}else{
								$attr[trim($shotcode2)] = '';
							}
							
						}
					}
				}
				$blog_id = get_current_blog_id();
				$key = $this->bzgetcontentShCustKey($attr,$blog_id);
				return array($key,$attr,$type);
			}
			if(strstr($shotcode,"shortcontent")){
				$type = 2;
				$shotcodeA = explode("[shortcontent",$shotcode);
				$shotcodeB = explode("]",$shotcodeA[1]);
				$shotcode1 = explode(' ',$shotcodeB[0]);
				
				if(is_array($shotcode1)){
					foreach($shotcode1 as $shotcode2){
						if(trim($shotcode2) != ''){
							if(strstr($shotcode2,"=")){
								$shotcode3 = explode('=',trim($shotcode2));
								$attr[trim($shotcode3[0])] = trim($shotcode3[1]);
							}else{
								$attr[trim($shotcode2)] = '';
							}
							
						}
					}
				}
				$key = $this->bzgetcontentShKey($attr);
				return array($key,$attr,$type);
			}
			return '';
		}
		public function save_editor() {
			$postID = $_REQUEST['postID'];
			$data = get_post_meta( $postID, '_elementor_data', true );
			if( $data ) {
				$obj = json_decode($data, true);
				if(isset($_REQUEST['settings'])){
					foreach($_REQUEST['settings'] as $keyn=>$value){
						$bg = 0;
						$key = $keyn;
						$keyValue = $this->array_find_deep($obj,$key);
						unset($keyValue[count($keyValue )-1]);
						$mydir = 'modules';
						$x = $obj;
						foreach($keyValue as $k)
						{
							$x = $x[$k];
						}
						try{
							if(isset($x['widgetType'])){
								$filename =  $mydir.'/'.$x['widgetType']; 
								if (is_dir(plugin_dir_path( __FILE__ ).'/'.$filename)) {
									require($mydir.'/'.$x['widgetType'] . '/code.php');
								}
							}
							if(isset($x['elType'])){
								$filename =  $mydir.'/'.$x['elType']; 
								if (is_dir(plugin_dir_path( __FILE__ ).'/'.$filename)) {
									require($mydir.'/'.$x['elType'] . '/code.php');
								}
							}
						}
						catch(Exception $e) {
							print_r($e);
						}
					}
				}
				//~ die;
				//$obj1 = wp_slash( wp_unslash(wp_json_encode( $obj )) );
				$obj1 = wp_slash(wp_json_encode( $obj ));
				update_post_meta( $postID, '_elementor_data', $obj1);
				// Remove Post CSS
				$post_css = new Post_CSS( $postID );
				$post_css->delete();
			}
			die('success');
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
		public function save_editor_permission() {
			$postID = $_REQUEST['postID'];
			if(isset($_REQUEST['name'])){
				$name = $_REQUEST['name'];
				$value = $_REQUEST['value'];
				if($name == 'full_permission' || $name == 'limited_permission') {
					$this->updateOption($name, $value);
				}else{
					update_post_meta( $postID, $name, $value);
				}
			}
			if(isset($_REQUEST['lock'])){
				$data = get_post_meta( $postID, '_elementor_lock', true );
				$id = $_REQUEST['id'];
				$lock = $_REQUEST['lock'];
				if(is_array($data)){
					if(isset($data[$id])){
						if($lock == '0'){
							unset($data[$id]);
						}
					}					
				}else{
					$data = array();
				}
				if($lock == '1'){
					$data[$id] = '1';
				}
				
				update_post_meta( $postID, '_elementor_lock', $data);
			}
			die('success');
		}
		
		/** 
		* Shortcode method for Developers of single site
		*/
		public function bzgetcontentShKey($atts) {
			
			$type = $atts['type'];  //field type
			$rowID  = explode('_',$atts['item']); //item ID
			$row_ID  = $rowID[1]; //item ID
			$postId  = $rowID[0]; //post ID
			
			$pageId = $atts['page'];  //pageId

			$isImg = 1;
			if(isset($atts['isImg'])){
				$isImg = $atts['isImg'];
			}
			
			$type1 = explode('_',$type);
			$fieldType = $type1[0];
			$fieldBlockID = $type1[1];

			
			$pageID = get_the_ID();
			$classes = get_body_class();
			$innerFlexContent = 'create_site_layout_'.$pageId.'_create_layout'; //inner layout Key
			$blocks = get_post_meta( $postId, $innerFlexContent , true );
			
			//~ $blocks = get_field($innerFlexContent,$postId); //layout Arr
			
			if(is_array($blocks)) {
				$innerblocks = $blocks;
			} else {
				$innerblocks = unserialize($blocks);
			}
						
			$keys_of_duplicated = array();
			$array_keys = array();
			
			$sh_key='';
					
			if(!empty($innerblocks)) {
				foreach($innerblocks as $key => $value) {
					$array_keys = array_keys($innerblocks, $value);

					if(count($array_keys) > 1) {
						foreach($array_keys as $key_registered) {
							if(!in_array($key_registered,  $keys_of_duplicated)) {
								 $keys_of_duplicated[] = $key_registered;
							}
						}
					}
					
					}
			

			
			if($fieldType == 'richtext') {
				
				$repeaterField = 'add_rich_text_area';
				$array_keys = array_keys($innerblocks, 'richtextBlock');
				count($array_keys);
				if(count($array_keys) > 1) {
					$key = $keys_of_duplicated[$fieldBlockID];
				} else {
					$key = array_search('richtextBlock', $innerblocks);
				}
						
			} else if ($fieldType == 'paragraph') {
				
				$repeaterField = 'add_paragraph';
				$array_keys = array_keys($innerblocks, 'spintaxBlock');
				if(count($array_keys) > 1) {
					$key = $keys_of_duplicated[$fieldBlockID];
				} else {
					$key = array_search('spintaxBlock', $innerblocks);
				}
				
			}  else if ($fieldType == 'stattitle') {
				
				$repeaterField = 'add_stat';
				$array_keys = array_keys($innerblocks, 'statBlock');
				if(count($array_keys) > 1) {
					$key = $keys_of_duplicated[$fieldBlockID];
				} else {
					$key = array_search('statBlock', $innerblocks);
				}
				
			}  else if ($fieldType == 'statnum') {
				
				$repeaterField = 'add_stat';
				$array_keys = array_keys($innerblocks, 'statBlock');
				if(count($array_keys) > 1) {
					$key = $keys_of_duplicated[$fieldBlockID];
				} else {
					$key = array_search('statBlock', $innerblocks);
				}
				
			} else if ($fieldType == 'medimage') {
				
				$repeaterField = 'add_media';
				$array_keys = array_keys($innerblocks, 'mediaBlock');
				if(count($array_keys) > 1) {
					$key = $keys_of_duplicated[$fieldBlockID];
				} else {
					$key = array_search('mediaBlock', $innerblocks);
				}
				
			} else if ($fieldType == 'contenttitle') {
				
				$repeaterField = 'add_content';
				$array_keys = array_keys($innerblocks, 'contentBlock');
				if(count($array_keys) > 1) {
					$key = $keys_of_duplicated[$fieldBlockID];
				} else {
					$key = array_search('contentBlock', $innerblocks);
				}
				
			} else if ($fieldType == 'contenticon') {
				
				$repeaterField = 'add_content';
				$array_keys = array_keys($innerblocks, 'contentBlock');
				if(count($array_keys) > 1) {
					$key = $keys_of_duplicated[$fieldBlockID];
				} else {
					$key = array_search('contentBlock', $innerblocks);
				}
				
			} else if ($fieldType == 'contenticon1') {
				
				$repeaterField = 'add_content';
				$array_keys = array_keys($innerblocks, 'contentBlock');
				if(count($array_keys) > 1) {
					$key = $keys_of_duplicated[$fieldBlockID];
				} else {
					$key = array_search('contentBlock', $innerblocks);
				}
				
			} else if ($fieldType == 'contenticon2') {
				
				$repeaterField = 'add_content';
				$array_keys = array_keys($innerblocks, 'contentBlock');
				if(count($array_keys) > 1) {
					$key = $keys_of_duplicated[$fieldBlockID];
				} else {
					$key = array_search('contentBlock', $innerblocks);
				}
				
			} else if ($fieldType == 'contenticon3') {
				
				$repeaterField = 'add_content';
				$array_keys = array_keys($innerblocks, 'contentBlock');
				if(count($array_keys) > 1) {
					$key = $keys_of_duplicated[$fieldBlockID];
				} else {
					$key = array_search('contentBlock', $innerblocks);
				}
				
			} else if ($fieldType == 'contentimage') {
				
				$repeaterField = 'add_content';
				$array_keys = array_keys($innerblocks, 'contentBlock');
				if(count($array_keys) > 1) {
					$key = $keys_of_duplicated[$fieldBlockID];
				} else {
					$key = array_search('contentBlock', $innerblocks);
				}
			
			} else if ($fieldType == 'contenttext') {
				
				$repeaterField = 'add_content';
				$array_keys = array_keys($innerblocks, 'contentBlock');
				if(count($array_keys) > 1) {
					$key = $keys_of_duplicated[$fieldBlockID];
				} else {
					$key = array_search('contentBlock', $innerblocks);
				}
				
			} else if ($fieldType == 'contenttitle1') {
				
				$repeaterField = 'add_content';
				$array_keys = array_keys($innerblocks, 'contentBlock');
				if(count($array_keys) > 1) {
					$key = $keys_of_duplicated[$fieldBlockID];
				} else {
					$key = array_search('contentBlock', $innerblocks);
				}
				
			} else if ($fieldType == 'contenttitle2') {
				
				$repeaterField = 'add_content';
				$array_keys = array_keys($innerblocks, 'contentBlock');
				if(count($array_keys) > 1) {
					$key = $keys_of_duplicated[$fieldBlockID];
				} else {
					$key = array_search('contentBlock', $innerblocks);
				}
				
			} else if ($fieldType == 'contenttitle3') {
				
				$repeaterField = 'add_content';
				$array_keys = array_keys($innerblocks, 'contentBlock');
				if(count($array_keys) > 1) {
					$key = $keys_of_duplicated[$fieldBlockID];
				} else {
					$key = array_search('contentBlock', $innerblocks);
				}
				
			} else if ($fieldType == 'buttontitle1') {
				
				$repeaterField = 'add_content';
				$array_keys = array_keys($innerblocks, 'contentBlock');
				if(count($array_keys) > 1) {
					$key = $keys_of_duplicated[$fieldBlockID];
				} else {
					$key = array_search('contentBlock', $innerblocks);
				}
				
			} else if ($fieldType == 'buttontitle2') {
				
				$repeaterField = 'add_content';
				$array_keys = array_keys($innerblocks, 'contentBlock');
				if(count($array_keys) > 1) {
					$key = $keys_of_duplicated[$fieldBlockID];
				} else {
					$key = array_search('contentBlock', $innerblocks);
				}
				
			} else if ($fieldType == 'buttontitle3') {
				
				$repeaterField = 'add_content';
				$array_keys = array_keys($innerblocks, 'contentBlock');
				if(count($array_keys) > 1) {
					$key = $keys_of_duplicated[$fieldBlockID];
				} else {
					$key = array_search('contentBlock', $innerblocks);
				}
				
			} else if ($fieldType == 'contentimage1') {
				
				$repeaterField = 'add_content';
				$array_keys = array_keys($innerblocks, 'contentBlock');
				if(count($array_keys) > 1) {
					$key = $keys_of_duplicated[$fieldBlockID];
				} else {
					$key = array_search('contentBlock', $innerblocks);
				}
				
			} else if ($fieldType == 'contentimage2') {
				
				$repeaterField = 'add_content';
				$array_keys = array_keys($innerblocks, 'contentBlock');
				if(count($array_keys) > 1) {
					$key = $keys_of_duplicated[$fieldBlockID];
				} else {
					$key = array_search('contentBlock', $innerblocks);
				}
				
			} else if ($fieldType == 'contentimage3') {
				
				$repeaterField = 'add_content';
				$array_keys = array_keys($innerblocks, 'contentBlock');
				if(count($array_keys) > 1) {
					$key = $keys_of_duplicated[$fieldBlockID];
				} else {
					$key = array_search('contentBlock', $innerblocks);
				}
				
			} else if ($fieldType == 'testiname') {
				
				$repeaterField = 'add_testimonial';
				$array_keys = array_keys($innerblocks, 'testimonialBlock');
				if(count($array_keys) > 1) {
					$key = $keys_of_duplicated[$fieldBlockID];
				} else {
					$key = array_search('testimonialBlock', $innerblocks);
				}
				
			} else if ($fieldType == 'testijob') {
				
				$repeaterField = 'add_testimonial';
				$array_keys = array_keys($innerblocks, 'testimonialBlock');
				if(count($array_keys) > 1) {
					$key = $keys_of_duplicated[$fieldBlockID];
				} else {
					$key = array_search('testimonialBlock', $innerblocks);
				}
				
			} else if ($fieldType == 'testiicon') {
				
				$repeaterField = 'add_testimonial';
				$array_keys = array_keys($innerblocks, 'testimonialBlock');
				if(count($array_keys) > 1) {
					$key = $keys_of_duplicated[$fieldBlockID];
				} else {
					$key = array_search('testimonialBlock', $innerblocks);
				}

			} else if ($fieldType == 'testiimage') {
				
				$repeaterField = 'add_testimonial';
				$array_keys = array_keys($innerblocks, 'testimonialBlock');
				if(count($array_keys) > 1) {
					$key = $keys_of_duplicated[$fieldBlockID];
				} else {
					$key = array_search('testimonialBlock', $innerblocks);
				}

			} else if ($fieldType == 'testicontent') {
				
				$repeaterField = 'add_testimonial';
				$array_keys = array_keys($innerblocks, 'testimonialBlock');
				if(count($array_keys) > 1) {
					$key = $keys_of_duplicated[$fieldBlockID];
				} else {
					$key = array_search('testimonialBlock', $innerblocks);
				}
			}
					
			$sh_key = 'create_site_layout_'.$pageId.'_create_layout_'.$key.'_'.$repeaterField.'_'.$row_ID.'_'.$fieldType;		
		}
		return $sh_key;
			
		}
		
		/**
		* Shortcode method for Site owners
		*/
		public function bzgetcontentShCustKey($atts,$blog_id) {
			
			$type = $atts['type'];  //field type
			$row_ID  = $atts['item']; //item ID
			$pageId = $atts['page'];  //pageId
			$isImg = 1;
			if(isset($atts['isImg'])){
				$isImg = $atts['isImg'];
			}
			
			$type1 = explode('_',$type);
			$fieldType = $type1[0];
			$fieldBlockID = $type1[1];

			
			switch_to_blog($blog_id);
			$pageID = get_the_ID();
			$classes = get_body_class();
			
			$innerFlexContent = 'create_site_layout_'.$pageId.'_create_layout'; //inner layout Key
			
			$blocks = get_option('options_'.$innerFlexContent); //layout Arr
			
			if(is_array($blocks)) {
				$innerblocks = $blocks;
			} else {
				$innerblocks = unserialize($blocks);
			}
						
			$keys_of_duplicated = array();
			$array_keys = array();
			
			$shortcodeData='';
					
			if(!empty($innerblocks)) {
				foreach($innerblocks as $key => $value) {
					$array_keys = array_keys($innerblocks, $value);

					if(count($array_keys) > 1) {
						foreach($array_keys as $key_registered) {
							if(!in_array($key_registered,  $keys_of_duplicated)) {
								 $keys_of_duplicated[] = $key_registered;
							}
						}
					}
					
					}
			

			
			if($fieldType == 'richtext') {
				
				$repeaterField = 'add_rich_text_area';
				$array_keys = array_keys($innerblocks, 'richtextBlock');
				count($array_keys);
				if(count($array_keys) > 1) {
					$key = $keys_of_duplicated[$fieldBlockID];
				} else {
					$key = array_search('richtextBlock', $innerblocks);
				}
						
			} else if ($fieldType == 'paragraph') {
				
				$repeaterField = 'add_paragraph';
				$array_keys = array_keys($innerblocks, 'spintaxBlock');
				if(count($array_keys) > 1) {
					$key = $keys_of_duplicated[$fieldBlockID];
				} else {
					$key = array_search('spintaxBlock', $innerblocks);
				}
				
			}  else if ($fieldType == 'stattitle') {
				
				$repeaterField = 'add_stat';
				$array_keys = array_keys($innerblocks, 'statBlock');
				if(count($array_keys) > 1) {
					$key = $keys_of_duplicated[$fieldBlockID];
				} else {
					$key = array_search('statBlock', $innerblocks);
				}
				
			}  else if ($fieldType == 'statnum') {
				
				$repeaterField = 'add_stat';
				$array_keys = array_keys($innerblocks, 'statBlock');
				if(count($array_keys) > 1) {
					$key = $keys_of_duplicated[$fieldBlockID];
				} else {
					$key = array_search('statBlock', $innerblocks);
				}
				
			} else if ($fieldType == 'medimage') {
				
				$repeaterField = 'add_media';
				$array_keys = array_keys($innerblocks, 'mediaBlock');
				if(count($array_keys) > 1) {
					$key = $keys_of_duplicated[$fieldBlockID];
				} else {
					$key = array_search('mediaBlock', $innerblocks);
				}
				
			} else if ($fieldType == 'contenttitle') {
				
				$repeaterField = 'add_content';
				$array_keys = array_keys($innerblocks, 'contentBlock');
				if(count($array_keys) > 1) {
					$key = $keys_of_duplicated[$fieldBlockID];
				} else {
					$key = array_search('contentBlock', $innerblocks);
				}
				
			} else if ($fieldType == 'contenticon') {
				
				$repeaterField = 'add_content';
				$array_keys = array_keys($innerblocks, 'contentBlock');
				if(count($array_keys) > 1) {
					$key = $keys_of_duplicated[$fieldBlockID];
				} else {
					$key = array_search('contentBlock', $innerblocks);
				}
				
			} else if ($fieldType == 'contenticon1') {
				
				$repeaterField = 'add_content';
				$array_keys = array_keys($innerblocks, 'contentBlock');
				if(count($array_keys) > 1) {
					$key = $keys_of_duplicated[$fieldBlockID];
				} else {
					$key = array_search('contentBlock', $innerblocks);
				}
				
			} else if ($fieldType == 'contenticon2') {
				
				$repeaterField = 'add_content';
				$array_keys = array_keys($innerblocks, 'contentBlock');
				if(count($array_keys) > 1) {
					$key = $keys_of_duplicated[$fieldBlockID];
				} else {
					$key = array_search('contentBlock', $innerblocks);
				}
				
			} else if ($fieldType == 'contenticon3') {
				
				$repeaterField = 'add_content';
				$array_keys = array_keys($innerblocks, 'contentBlock');
				if(count($array_keys) > 1) {
					$key = $keys_of_duplicated[$fieldBlockID];
				} else {
					$key = array_search('contentBlock', $innerblocks);
				}
				
			} else if ($fieldType == 'contentimage') {
				
				$repeaterField = 'add_content';
				$array_keys = array_keys($innerblocks, 'contentBlock');
				if(count($array_keys) > 1) {
					$key = $keys_of_duplicated[$fieldBlockID];
				} else {
					$key = array_search('contentBlock', $innerblocks);
				}
			
			} else if ($fieldType == 'contenttext') {
				
				$repeaterField = 'add_content';
				$array_keys = array_keys($innerblocks, 'contentBlock');
				if(count($array_keys) > 1) {
					$key = $keys_of_duplicated[$fieldBlockID];
				} else {
					$key = array_search('contentBlock', $innerblocks);
				}
				
			} else if ($fieldType == 'contenttitle1') {
				
				$repeaterField = 'add_content';
				$array_keys = array_keys($innerblocks, 'contentBlock');
				if(count($array_keys) > 1) {
					$key = $keys_of_duplicated[$fieldBlockID];
				} else {
					$key = array_search('contentBlock', $innerblocks);
				}
				
			} else if ($fieldType == 'contenttitle2') {
				
				$repeaterField = 'add_content';
				$array_keys = array_keys($innerblocks, 'contentBlock');
				if(count($array_keys) > 1) {
					$key = $keys_of_duplicated[$fieldBlockID];
				} else {
					$key = array_search('contentBlock', $innerblocks);
				}
				
			} else if ($fieldType == 'contenttitle3') {
				
				$repeaterField = 'add_content';
				$array_keys = array_keys($innerblocks, 'contentBlock');
				if(count($array_keys) > 1) {
					$key = $keys_of_duplicated[$fieldBlockID];
				} else {
					$key = array_search('contentBlock', $innerblocks);
				}
				
			} else if ($fieldType == 'buttontitle1') {
				
				$repeaterField = 'add_content';
				$array_keys = array_keys($innerblocks, 'contentBlock');
				if(count($array_keys) > 1) {
					$key = $keys_of_duplicated[$fieldBlockID];
				} else {
					$key = array_search('contentBlock', $innerblocks);
				}
				
			} else if ($fieldType == 'buttontitle2') {
				
				$repeaterField = 'add_content';
				$array_keys = array_keys($innerblocks, 'contentBlock');
				if(count($array_keys) > 1) {
					$key = $keys_of_duplicated[$fieldBlockID];
				} else {
					$key = array_search('contentBlock', $innerblocks);
				}
				
			} else if ($fieldType == 'buttontitle3') {
				
				$repeaterField = 'add_content';
				$array_keys = array_keys($innerblocks, 'contentBlock');
				if(count($array_keys) > 1) {
					$key = $keys_of_duplicated[$fieldBlockID];
				} else {
					$key = array_search('contentBlock', $innerblocks);
				}
				
			} else if ($fieldType == 'contentimage1') {
				
				$repeaterField = 'add_content';
				$array_keys = array_keys($innerblocks, 'contentBlock');
				if(count($array_keys) > 1) {
					$key = $keys_of_duplicated[$fieldBlockID];
				} else {
					$key = array_search('contentBlock', $innerblocks);
				}
				
			} else if ($fieldType == 'contentimage2') {
				
				$repeaterField = 'add_content';
				$array_keys = array_keys($innerblocks, 'contentBlock');
				if(count($array_keys) > 1) {
					$key = $keys_of_duplicated[$fieldBlockID];
				} else {
					$key = array_search('contentBlock', $innerblocks);
				}
				
			} else if ($fieldType == 'contentimage3') {
				
				$repeaterField = 'add_content';
				$array_keys = array_keys($innerblocks, 'contentBlock');
				if(count($array_keys) > 1) {
					$key = $keys_of_duplicated[$fieldBlockID];
				} else {
					$key = array_search('contentBlock', $innerblocks);
				}
				
			} else if ($fieldType == 'testiname') {
				
				$repeaterField = 'add_testimonial';
				$array_keys = array_keys($innerblocks, 'testimonialBlock');
				if(count($array_keys) > 1) {
					$key = $keys_of_duplicated[$fieldBlockID];
				} else {
					$key = array_search('testimonialBlock', $innerblocks);
				}
				
			} else if ($fieldType == 'testijob') {
				
				$repeaterField = 'add_testimonial';
				$array_keys = array_keys($innerblocks, 'testimonialBlock');
				if(count($array_keys) > 1) {
					$key = $keys_of_duplicated[$fieldBlockID];
				} else {
					$key = array_search('testimonialBlock', $innerblocks);
				}
				
			} else if ($fieldType == 'testiicon') {
				
				$repeaterField = 'add_testimonial';
				$array_keys = array_keys($innerblocks, 'testimonialBlock');
				if(count($array_keys) > 1) {
					$key = $keys_of_duplicated[$fieldBlockID];
				} else {
					$key = array_search('testimonialBlock', $innerblocks);
				}

			} else if ($fieldType == 'testiimage') {
				
				$repeaterField = 'add_testimonial';
				$array_keys = array_keys($innerblocks, 'testimonialBlock');
				if(count($array_keys) > 1) {
					$key = $keys_of_duplicated[$fieldBlockID];
				} else {
					$key = array_search('testimonialBlock', $innerblocks);
				}

			} else if ($fieldType == 'testicontent') {
				
				$repeaterField = 'add_testimonial';
				$array_keys = array_keys($innerblocks, 'testimonialBlock');
				if(count($array_keys) > 1) {
					$key = $keys_of_duplicated[$fieldBlockID];
				} else {
					$key = array_search('testimonialBlock', $innerblocks);
				}
			}
					
			$sh_key = 'create_site_layout_'.$pageId.'_create_layout_'.$key.'_'.$repeaterField.'_'.$row_ID.'_'.$fieldType;
			restore_current_blog();
			return $sh_key;
			}
		}
		
	
		public function lepElem_validLicense($license_key1) {
			
			$currentTime=time(); //current Time
			$lepelementor_license = $this->getOption('lepelementor_license');
			
			if (isset($lepelementor_license) && $lepelementor_license == '' ) {
				$lic = $this->lmgr->license_check($license_key1,'status-check');
				if ($lic[0]==1) {
					$this->updateOption('lepelementor_license',$currentTime);
					return true;
				} else {
					return false;
				}
				
			} else if (isset($lepelementor_license) && $lepelementor_license != '') {
				
				$rr = $currentTime - $lepelementor_license;
				$hourdiff = round(($rr)/3600, 1);
				if( $hourdiff >= 24 ) {
					$lic = $this->lmgr->license_check($license_key1,'status-check');
					if ($lic[0]==1) {
						$this->updateOption('lepelementor_license',$currentTime);
						$this->updateOption('lepelementor_license_expired','');
						return true;
					} else {
						$this->updateOption('lepelementor_license',$currentTime);
						$this->updateOption('lepelementor_license_expired',1);
						return false;
					}					
				} else {
					$lepelementor_license_expired = $this->getOption('lepelementor_license_expired');
					if (isset($lepelementor_license_expired) && $lepelementor_license_expired != '' ) {
						return false;
					} else {
						$this->updateOption('lepelementor_license_expired','');
						return true;
					}
				}
				
			} else {
				
				return true;
			}	
			
		}  
		
	}
	$ElementorEditor = new ElementorEditor();

}
