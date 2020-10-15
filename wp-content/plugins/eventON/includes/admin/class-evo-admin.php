<?php
/**
 * all wp-admin functions for admin side of eventon
 *
 * @author 		AJDE
 * @category 	Admin
 * @package 	eventon/Admin
 * @version     2.4.9
 */

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

if ( ! class_exists( 'evo_admin' ) ) :

/** evo_admin Class */
class evo_admin {

	private $class_name;
	/** Constructor */
	public function __construct() {

		$this->opt = evo_get_options('1');

		add_action('admin_menu', array($this,'eventon_admin_menu'), 5);
		add_action( 'admin_head', array($this,'eventon_admin_menu_highlight'), 5);
		add_action('admin_init', array($this,'eventon_admin_init'));
		
		add_action('admin_action_duplicate_event', array($this,'eventon_duplicate_event_action'));
		add_filter("plugin_action_links_".AJDE_EVCAL_BASENAME, array($this,'eventon_plugin_links') );

		add_action('media_buttons',  array($this,'eventon_shortcode_button'));
		add_filter( 'tiny_mce_version', array($this,'eventon_refresh_mce') ); 

		add_filter('display_post_states', array($this,'post_state'),10,2);

		$tt = strtotime( 'first day of 2 months ago');
		//echo date('y-m-d', $tt);

		//add_action( 'admin_enqueue_scripts', array($this,'eventon_admin_scripts') );
		//add_action( 'admin_enqueue_scripts', array($this,'eventon_all_backend_files') );

		// eventon elements
		EVO()->elements->register_styles_scripts();
	}

	function post_state( $states, $post){
		//print_r($post);
		if (  'page' == get_post_type( $post->ID ) &&  $post->post_name == 'event-directory'){
	        $states[] = __('Events Page'); 
	    } 

	    return $states;
	}

// admin init
	function eventon_admin_init() {				
		// Includes
			require_once(AJDE_EVCAL_PATH.'/includes/products/class-evo_plugins_api_data.php');

		global $pagenow, $typenow, $wpdb, $post;	

		$postType = !empty($_GET['post_type'])? $_GET['post_type']: false;
	   
	    if(!$postType && !empty($_GET['post']))  $postType = get_post_type($_GET['post']);
			
		// EVENT POSTS
		if ( $postType && $postType == "ajde_events" ) {		
			// Event Post Only
			$print_css_on = array( 'post-new.php', 'post.php' );

			foreach ( $print_css_on as $page ){
				add_action( 'admin_print_styles-'. $page, array($this,'eventon_admin_post_css') );
				add_action( 'admin_print_scripts-'. $page, array($this,'eventon_admin_post_script') );			
			}
						
			// taxonomy only page
			if($pagenow =='edit-tags.php' || $pagenow == 'term.php'){
				$this->eventon_load_colorpicker();
				wp_enqueue_script('taxonomy',AJDE_EVCAL_URL.'/assets/js/admin/taxonomy.js' ,array('jquery'),'1.0', true);
			}
		}else{
			$this->eventon_shortcode_button_init();
		}

		// event edit page content
			include_once(  AJDE_EVCAL_PATH.'/includes/admin/post_types/class-meta_boxes.php' );
			$this->metaboxes = new evo_event_metaboxes();

		// Includes for admin
			if(defined('DOING_AJAX')){	include_once( 'class-admin-ajax.php' );		}			

		// evneton settings only 
			if($pagenow =='admin.php' && isset($_GET['page']) && ($_GET['page']=='eventon' || $_GET['page']=='action_user')){
				global $ajde;
				$ajde->load_styles_to_page();
			}

		// all eventon wp-admin pages
			$this->wp_admin_scripts_styles();
					
		// create necessary pages	
			$_eventon_create_pages = get_option('_eventon_create_pages'); // get saved status for creating pages
			if(empty($_eventon_create_pages) || $_eventon_create_pages!= 1){
				evo_install::create_pages();
			}

		// force update checking on wp-admin
			if($pagenow =='update-core.php' && isset($_REQUEST['force-check']) && $_REQUEST['force-check']==1){
				EVO_Prods()->get_remote_prods_data(true);
			}

		// RTL styles for wp-admin
			if( is_rtl() ){
				wp_enqueue_style( 'rtl_styles',AJDE_EVCAL_URL.'/assets/css/admin/wp_admin_rtl.css',array(), EVO()->version);
			}
			
		// when an addon is updated or installed - since 2.5
			add_action('evo_addon_version_change', array($this, 'update_addon_styles'), 10);

		// Deactivate single events addon
			deactivate_plugins('eventon-single-event/eventon-single-event.php');
			deactivate_plugins('eventon-search/eventon-search.php');
	}
	
// admin menus
	function eventon_admin_menu() {
	    global $menu, $pagenow;

	    if ( current_user_can( 'manage_eventon' ) )
	    $menu[] = array( '', 'read', 'separator-eventon', '', 'wp-menu-separator eventon' );
			
		$menu_icon = 'data:image/svg+xml;base64,'. base64_encode('<svg width="20" height="20" viewBox="0 0 32 32" xmlns="http://www.w3.org/2000/svg"><path fill="black" d="'. $this->get_svg_evo().'"/></svg>');
		// Create admin menu page 
		$main_page = add_menu_page(
			__('EventON - Event Calendar','eventon'), 
			'myEventON',
			'manage_eventon',
			'eventon',
			array($this,'eventon_settings_page'), 
			$menu_icon
		);

	    add_action( 'load-' . $main_page, array($this,'eventon_admin_help_tab') );	
		
		
		// add submenus to the eventon menu
		add_submenu_page( 'eventon', 'Language', 'Language', 'manage_eventon', 'admin.php?page=eventon&tab=evcal_2', '' );
		add_submenu_page( 'eventon', 'Styles', 'Styles', 'manage_eventon', 'admin.php?page=eventon&tab=evcal_3', '' );
		add_submenu_page( 'eventon', 'Addons & Licenses', 'Addons & Licenses', 'manage_eventon', 'admin.php?page=eventon&tab=evcal_4', '' );
		add_submenu_page( 'eventon', 'Support', 'Support', 'manage_eventon', 'admin.php?page=eventon&tab=evcal_5', '' );
	}	
	/** Include and display the settings page. */
		function eventon_settings_page() {
			include_once(  AJDE_EVCAL_PATH.'/ajde/class-ajde_plugin_settings.php' );
			include_once(  AJDE_EVCAL_PATH.'/includes/admin/settings/eventon-admin-settings.php' );
			include_once(  AJDE_EVCAL_PATH.'/includes/admin/settings/class-settings-appearance.php' );
			eventon_settings();
		}

// evo icon SVG
	function get_svg_el(){
		ob_start();?>
		<svg id="evo_icon" viewBox="0 0 32 32">
		<path d="<?php echo $this->get_svg_evo();?>"></path>
		</svg>
		<?php
		return ob_get_clean();
	}
		function get_svg_evo(){
			return "M24.102 1.227h-16.578c-3.596 0-6.511 2.915-6.511 6.511v16.578c0 3.596 2.915 6.511 6.511 6.511h16.578c3.596 0 6.511-2.915 6.511-6.511v-16.578c0-3.596-2.915-6.511-6.511-6.511zM11.467 6.88h0.381l0.896 1.44h0.008c-0.002-0.053 0.002-0.095-0.004-0.216s-0.001-0.221-0.001-0.288v-0.936h0.267v1.813h-0.372l-0.899-1.44h-0.010l-0.005 0.070c0.012 0.155 0.006 0.285 0.006 0.413v0.957h-0.267v-1.813zM9.603 7.072c0.149-0.162 0.361-0.242 0.637-0.242 0.272 0 0.482 0.082 0.63 0.246s0.223 0.395 0.223 0.694c0 0.298-0.074 0.529-0.223 0.694s-0.359 0.248-0.632 0.248c-0.276 0-0.488-0.082-0.636-0.246s-0.222-0.396-0.222-0.698 0.074-0.533 0.223-0.695zM7.093 6.88h0.435l0.508 1.44h0.008l0.522-1.44h0.447v1.813h-0.32v-0.911c0-0.091 0.006-0.217 0.011-0.363s0.012-0.22 0.016-0.273h-0.010l-0.549 1.547h-0.264l-0.53-1.547h-0.010c0.014 0.267 0.003 0.452 0.003 0.651v0.896h-0.267v-1.813zM14.453 23.52h-6.773v-1.173h1.714c0.21 0 0.391-0.062 0.483-0.153 0.111-0.109 0.149-0.275 0.149-0.384v-8.112c0-0.054-0.027-0.127-0.12-0.208-0.077-0.067-0.202-0.102-0.341-0.102h-2.206v-1.127l0.243-0.032c0.917-0.117 1.613-0.261 2.132-0.44 0.48-0.166 0.957-0.399 1.419-0.707l0.070-0.041h0.776v10.993c0 0.070 0.012 0.135 0.054 0.166 0.122 0.089 0.264 0.148 0.437 0.148h1.962v1.173zM24.311 23.52h-8.258v-0.863l2.582-2.932c1.443-1.568 2.235-2.467 2.496-2.83 0.384-0.532 0.666-1.051 0.839-1.542 0.172-0.486 0.259-0.93 0.259-1.321 0-0.635-0.166-1.115-0.506-1.468-0.337-0.35-0.802-0.52-1.422-0.52-0.713 0-1.298 0.169-1.739 0.502-0.416 0.315-0.627 0.638-0.627 0.961 0 0.061 0.011 0.108 0.028 0.126l0.008 0.008c0.001 0.001 0.059 0.053 0.308 0.115 0.895 0.213 1.084 0.819 1.084 1.29 0 0.37-0.127 0.682-0.377 0.929-0.249 0.245-0.568 0.37-0.948 0.37-0.438 0-0.821-0.199-1.14-0.591-0.305-0.374-0.459-0.861-0.459-1.448 0-0.636 0.167-1.224 0.495-1.747 0.328-0.521 0.824-0.952 1.476-1.281 0.645-0.326 1.348-0.491 2.089-0.491 0.735 0 1.428 0.161 2.059 0.478 0.639 0.321 1.127 0.751 1.452 1.279s0.489 1.112 0.489 1.736c0 0.427-0.081 0.865-0.24 1.302-0.159 0.435-0.394 0.847-0.699 1.225-0.501 0.625-1.026 1.168-1.56 1.614l-2.267 1.91c-0.467 0.393-0.831 0.739-1.085 1.033 0.305 0.085 1.158 0.208 3.542 0.208 0.34 0 0.575-0.077 0.698-0.227 0.084-0.103 0.27-0.454 0.57-1.725l0.052-0.207h1.125l-0.324 4.107zM10.238 8.456c0.178 0 0.311-0.057 0.402-0.172s0.136-0.286 0.136-0.514c0-0.224-0.045-0.394-0.134-0.51s-0.223-0.174-0.401-0.174c-0.179 0-0.314 0.058-0.406 0.174s-0.137 0.286-0.137 0.51c0 0.225 0.045 0.396 0.136 0.512s0.225 0.174 0.404 0.174z";
		}
// correct menu highlight
	function eventon_admin_menu_highlight() {
		global $submenu;

		//print_r($submenu);

		if ( isset( $submenu['eventon'] )  )  {
			$submenu['eventon'][0][0] = 'Settings';
			//unset( $submenu['eventon'][2] );
		}
		ob_start();
		?>
			<style>
				.evo_yn_btn .btn_inner:before{content:"<?php _e('NO','eventon');?>";}
				.evo_yn_btn .btn_inner:after{content:"<?php _e('YES','eventon');?>";}
			</style>
		<?php
		echo ob_get_clean();
	}
// admin styles and scripts
	// for event posts
		function eventon_admin_post_css() {
			global $wp_scripts;
			$protocol = is_ssl() ? 'https' : 'http';

			// JQ UI styles
			$jquery_version = isset( $wp_scripts->registered['jquery-ui-core']->ver ) ? $wp_scripts->registered['jquery-ui-core']->ver : '1.10.4';		
			
			wp_enqueue_style("jquery-ui-css", $protocol."://ajax.googleapis.com/ajax/libs/jqueryui/{$jquery_version}/themes/smoothness/jquery-ui.min.css");
			
			wp_enqueue_style( 'backend_evcal_post',AJDE_EVCAL_URL.'/assets/css/admin/backend_evcal_post.css', array(), EVO()->version );
			wp_enqueue_style( 'select2',AJDE_EVCAL_URL.'/assets/lib/select2/select2.css',array(), EVO()->version);

			
		}
		function eventon_admin_post_script() {
			global $pagenow, $typenow, $post, $ajde;	
			
			if ( $typenow == 'post' && ! empty( $_GET['post'] ) ) {
				$typenow = $post->post_type;
			} elseif ( empty( $typenow ) && ! empty( $_GET['post'] ) ) {
		        $post = get_post( $_GET['post'] );
		        $typenow = $post->post_type;
		    }
			
			if ( $typenow == '' || $typenow == "ajde_events" ) {

				// load color picker files
				$ajde->load_colorpicker();

				$eventon_JQ_UI_tp = AJDE_EVCAL_URL.'/assets/lib/jqtimepicker/jquery.timepicker.css';
				wp_enqueue_style( 'eventon_JQ_UI_tp',$eventon_JQ_UI_tp);
			
				// other scripts 
				wp_enqueue_script( 'evo_handlebars',EVO()->assets_path.'handlebars.js',array('jquery'), EVO()->version, true);
				wp_enqueue_script('select2',AJDE_EVCAL_URL.'/assets/lib/select2/select2.min.js');
				wp_enqueue_script('evcal_backend_post_timepicker',AJDE_EVCAL_URL.'/assets/lib/jqtimepicker/jquery.timepicker.js');
				wp_enqueue_script('evcal_backend_post',AJDE_EVCAL_URL.'/assets/js/admin/event-post.js', array('jquery','jquery-ui-core','jquery-ui-datepicker'), EVO()->version, true );
				wp_enqueue_script("jquery-ui-core");
				
				wp_localize_script( 'evcal_backend_post', 'the_ajax_script', array( 'ajaxurl' => admin_url( 'admin-ajax.php' )));	
				
				// hook for plugins
				do_action('eventon_admin_post_script');
			}
		}

	// scripts and styles for wp-admin
		function wp_admin_scripts_styles(){
			global $pagenow, $wp_version;

			if($pagenow == 'term.php')
				wp_enqueue_media();
			wp_enqueue_script('evo_wp_admin',AJDE_EVCAL_URL.'/assets/js/admin/wp_admin.js',array('jquery'), EVO()->version,true);
			wp_localize_script( 
				'evo_wp_admin', 
				'evo_admin_ajax_handle', 
				array( 
					'ajaxurl' => admin_url( 'admin-ajax.php' ), 
					'postnonce' => wp_create_nonce( 'eventon_admin_nonce' ),
					'select_from_list'=> esc_html__('Select from list', 'eventon'),
					'add_new_item'=> esc_html__('Add new item', 'eventon'),
					'edit_item'=> esc_html__('Edit item', 'eventon'),
				)
			);

			if( (!empty($pagenow) && $pagenow=='admin.php')
			 && (isset($_GET['page']) && ($_GET['page']=='eventon'|| $_GET['page']=='action_user'|| $_GET['page']=='evo-sync') ) 
			){

				// only addons page
			 	if(!empty($_GET['tab']) && $_GET['tab']=='evcal_4'){
			 		wp_enqueue_script('evcal_addons',AJDE_EVCAL_URL.'/assets/js/admin/settings_addons_licenses.js',array('jquery'), EVO()->version,true);
			 	}
			 	// only troubleshoot page
			 	if(!empty($_GET['tab']) && $_GET['tab']=='evcal_5'){
			 		wp_enqueue_script('evcal_troubleshoot',AJDE_EVCAL_URL.'/assets/js/admin/settings_troubleshoot.js',array('jquery'), EVO()->version,true);
			 	}
			 	
			 	// wp-admin script			 		
			 		wp_localize_script( 'evo_wp_admin', 'the_ajax_script', array( 'ajaxurl' => admin_url( 'admin-ajax.php' )));			 		

			 	// LOAD thickbox
					if(isset($_GET['tab']) && ( $_GET['tab']=='evcal_5' || $_GET['tab']=='evcal_4') ){
						wp_enqueue_script('thickbox');
						wp_enqueue_style('thickbox');
					}

				// LOAD custom google fonts for skins		
					$gfont="http://fonts.googleapis.com/css?family=Open+Sans:300italic,400,300";
					wp_register_style( 'evcal_google_fonts', $gfont, '', '', 'screen' );
			}
			
			// ALL wp-admin
			wp_register_style('evo_font_icons',AJDE_EVCAL_URL.'/assets/fonts/font-awesome.css',array(), EVO()->version);
			wp_enqueue_style( 'evo_font_icons' );

			// wp-admin styles
			 	wp_enqueue_style( 'evo_wp_admin',AJDE_EVCAL_URL.'/assets/css/admin/wp_admin.css',array(), EVO()->version);
			 	wp_enqueue_style( 'evo_wp_admin_widgets',AJDE_EVCAL_URL.'/assets/css/admin/widgets.css',array(), EVO()->version);


			// styles for WP>=3.8
			if($wp_version>=3.8)
				wp_enqueue_style( 'newwp',AJDE_EVCAL_URL.'/assets/css/admin/wp3.8.css',array(), EVO()->version);
			// styles for WP<3.8
			if($wp_version<3.8)
				wp_enqueue_style( 'newwp',AJDE_EVCAL_URL.'/assets/css/admin/wp_old.css',array(), EVO()->version);

			EVO()->elements->enqueue();

		}

// Dynamic Style Related
	/*	Dynamic styles generation */
		function generate_dynamic_styles_file($newdata='') {
		 
			/** Define some vars **/
			$data = $newdata; 
			$uploads = wp_upload_dir();
			
			//$css_dir = get_template_directory() . '/css/'; // Shorten code, save 1 call
			$css_dir = AJDE_EVCAL_DIR . '/'. EVENTON_BASE.  '/assets/css/'; 
			//$css_dir = plugin_dir_path( __FILE__ ).  '/assets/css/'; 
			
			//echo $css_dir;

			/** Save on different directory if on multisite **/
			if(is_multisite()) {
				$aq_uploads_dir = trailingslashit($uploads['basedir']);
			} else {
				$aq_uploads_dir = $css_dir;
			}
			
			/** Capture CSS output **/
			ob_start();
			require($css_dir . 'dynamic_styles.php');
			$css = ob_get_clean();

			//print_r($css);
			
			/** Write to options.css file **/
			WP_Filesystem();
			global $wp_filesystem;
			if ( ! $wp_filesystem->put_contents( $aq_uploads_dir . 'eventon_dynamic_styles.css', $css, 0777) ) {
			    return true;
			}	

			// also update concatenated addon styles
				$this->update_addon_styles();	
		}

	/**
	 * Update and save addon styles passed via pluggable function
	 * @since   2.5
	 */
		function update_addon_styles(){
			// check if enabled via eventon settings
			if( evo_settings_val('evcal_concat_styles',$this->opt, true)) return false;
			
			/** Define some vars **/
			//$data = $newdata; 
			$uploads = wp_upload_dir();
			
			//$css_dir = get_template_directory() . '/css/'; // Shorten code, save 1 call
			$css_dir = AJDE_EVCAL_DIR . '/'. EVENTON_BASE.  '/assets/css/'; 
			//$css_dir = plugin_dir_path( __FILE__ ).  '/assets/css/'; 
			
			//echo $css_dir;

			/** Save on different directory if on multisite **/
			if(is_multisite()) {
				$aq_uploads_dir = trailingslashit($uploads['basedir']);
			} else {
				$aq_uploads_dir = $css_dir;
			}
			
			/** Capture CSS output **/
			ob_start();
			require($css_dir . 'styles_evo_addons.php');
			$css = ob_get_clean();
				
			// if there is nothing on css
			if(empty($css)) return false;

			// save a version number for this
				$ver = get_option('eventon_addon_styles_version');
				(empty($ver))? 
					add_option('eventon_addon_styles_version', 1.00001):
					update_option('eventon_addon_styles_version', ($ver+0.00001));

			
			require_once(ABSPATH . 'wp-admin/includes/file.php');
			/** Write to options.css file **/
			WP_Filesystem();
			global $wp_filesystem;
			if ( ! $wp_filesystem->put_contents( $aq_uploads_dir . 'eventon_addon_styles.css', $css, 0777) ) {
			    return true;
			}		
		}

	// update the dynamic styles file with updates styles val
	// @ 2.5
		function update_dynamic_styles(){
			ob_start();
			include(AJDE_EVCAL_PATH.'/assets/css/dynamic_styles.php');
			$evo_dyn_css = ob_get_clean();						
			update_option('evo_dyn_css', $evo_dyn_css);
		}	

// Shortcode on Editor
	function eventon_shortcode_button_init() {

	   	//Abort early if the user will never see TinyMCE
	    if ( ! current_user_can('edit_posts') && ! current_user_can('edit_pages') && get_user_option('rich_editing') == 'true')
	    	return;

	    //Add a callback to regiser our tinymce plugin   
	    add_filter("mce_external_plugins", array($this,"eventon_register_tinymce_plugin")); 

	    // Add a callback to add our button to the TinyMCE toolbar
	    add_filter('mce_buttons', array($this,'eventon_add_tinymce_button'));
	}
	//This callback registers our plug-in
	function eventon_register_tinymce_plugin($plugin_array) {
	    $plugin_array['eventon_shortcode_button'] = AJDE_EVCAL_URL.'/assets/js/admin/shortcode.js';
	    return $plugin_array;
	}

	//This callback adds our button to the toolbar
	function eventon_add_tinymce_button($buttons) {
	            //Add the button ID to the $button array
	    $buttons[] = "eventon_shortcode_button";

	    $this->eventon_shortcode_pop_content();
	    return $buttons;
	}

// shortcode generator
	function eventon_shortcode_button($context) {	
		global $pagenow, $typenow, $post;	
		
		if ( $typenow == 'post' && ! empty( $_GET['post'] ) ) {
			$typenow = $post->post_type;
		} elseif ( empty( $typenow ) && ! empty( $_GET['post'] ) ) {
	        $post = get_post( $_GET['post'] );
	        $typenow = $post->post_type;
	    }
		
		if ( $typenow == '' || $typenow == "ajde_events" ) return;

		if(evo_settings_check_yn($this->opt, 'evo_hide_shortcode_btn') ) return;

		//our popup's title
	  	$text = '[ ] ADD EVENTON';
	  	$title = __('eventON Shortcode generator','eventon');

	  	//append the icon
	  	$context .= "<a id='evo_shortcode_btn' class='ajde_popup_trig evo_admin_btn btn_prime' data-popc='eventon_shortcode' title='{$title}' href='#'>{$text}</a>";
		
		$this->eventon_shortcode_pop_content();
		
	  	return $context;
	}
	function eventon_shortcode_pop_content(){		
		$content = EVO()->shortcode_gen->get_content();		
		// eventon popup box
		EVO()->lightbox->admin_lightbox_content(array(
			'content'=>$content, 
			'class'=>'eventon_shortcode', 
			'attr'=>'clear="false"', 
			'title'=> __('Shortcode Generator','eventon'),			
			//'subtitle'=>'Select option to customize shortcode variable values'
		));
	}

// Supporting functions
	function get_image($size='', $placeholder=true){
		global $postid;

		$size = (!empty($size))? $size: 'thumbnail';

		$thumb = get_post_thumbnail_id($postid);

		if(!empty($thumb)){
			$img = wp_get_attachment_image_src($thumb, $size);
			return ( $img && isset($img[0]) )? $img[0]: false;
		}else if($placeholder){
			return AJDE_EVCAL_URL.'/assets/images/placeholder.png';
		}else{
			return false;
		}
	}

	function get_color($pmv=''){
		if(!empty($pmv['evcal_event_color'])){
			if( strpos($pmv['evcal_event_color'][0], '#') !== false ){
				return $pmv['evcal_event_color'][0];
			}else{
				return '#'.$pmv['evcal_event_color'][0];
			}
		}else{
			$opt = get_option('evcal_options_evcal_1');
			$cl = (!empty($opt['evcal_hexcode']))? $opt['evcal_hexcode']: '4bb5d8';
			return '#'.$cl;
		}
	}

	public function addon_exists($slug){
		$addon = new EVO_Product($slug);
		return $addon->is_installed();
	}
	function eventon_load_colorpicker(){
		global $ajde;
		$ajde->load_colorpicker();
	}

	// help dropdown
		function eventon_admin_help_tab() {
			include_once( AJDE_EVCAL_PATH.'/includes/admin/eventon-admin-content.php' );
			eventon_admin_help_tab_content();
		}
	// duplicate events action
		function eventon_duplicate_event_action() {			
			include_once( AJDE_EVCAL_PATH.'/includes/admin/post_types/duplicate_event.php');
			eventon_duplicate_event();
		}

	// plugin settings page additional links
		function eventon_plugin_links($links) { 
		  	$settings_link = '<a href="admin.php?page=eventon">'.__('Settings','eventon').'</a>'; 	  
		  	$docs_link = '<a href="http://www.myeventon.com/documentation/" target="_blank">'.__('Docs','eventon').'</a>';
		  	$news_link = '<a href="http://www.myeventon.com/news/" target="_blank">'.__('News','eventon').'</a>'; 
		  	array_unshift($links, $settings_link, $docs_link, $news_link); 
		  	return $links; 
		}

	// form mc refresh
	function eventon_refresh_mce( $ver ) {
		$ver += 3;
		return $ver;
	}


		
}

endif;