<?php

/**
 * The admin-specific functionality of the plugin.
 *
 * @link       https://waashero.com
 * @since      1.0.0
 *
 * @package    Template_Hero_Elementor_Client
 * @subpackage Template_Hero_Elementor_Client/admin
 */

/**
 * The admin-specific functionality of the plugin.
 *
 * Defines the plugin name, version, and two examples hooks for how to
 * enqueue the admin-specific stylesheet and JavaScript.
 *
 * @package    Template_Hero_Elementor_Client
 * @subpackage Template_Hero_Elementor_Client/admin
 * @author     J Hanlon | Waas Hero <info@waashero.com>
 */
namespace TemplateHero\Plugin_Client;
use Elementor\Api;
use Elementor\Core\Common\Modules\Ajax\Module;
use Elementor\Core\Common\Modules\Ajax\Module as Ajax;
use Elementor\Core\Debug\Loading_Inspection_Manager;
use Elementor\Core\Files\Assets\Files_Upload_Handler;
use Elementor\Core\Responsive\Responsive;
use Elementor\Core\Schemes\Manager as Schemes_Manager;
use Elementor\Core\Settings\Manager as SettingsManager;
use Elementor\Icons_Manager;
use Elementor\Plugin;
use Elementor\Settings;
use Elementor\Shapes;
use Elementor\TemplateLibrary\Source_Local;
use Elementor\Tools;
use Elementor\User;
use Elementor\Utils;
use Elementor\Core\Editor\Data;
class Admin {

	/**
	 * The ID of this plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      string    $plugin_name    The ID of this plugin.
	 */
	private $plugin_name;

	/**
	 * The version of this plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      string    $version    The current version of this plugin.
	 */
	private $version;

	public static $config;
	/**
	 * Initialize the class and set its properties.
	 *
	 * @since    1.0.0
	 * @param      string    $plugin_name       The name of this plugin.
	 * @param      string    $version    The version of this plugin.
	 */
	public function __construct( $plugin_name, $version ) {

		$this->plugin_name = $plugin_name;
		$this->version     = $version;
	
	}

	/**
	 * Register the stylesheets for the admin area.
	 *
	 * @since    1.0.0
	 */
	public function enqueue_styles() {
		$screen = get_current_screen();
	
		$is_elementor_screen = ( $screen && false !== strpos( $screen->id, 'the' ) );
        if( !$is_elementor_screen  && $screen->id != 'plans_page_wu-edit-plan-network' && $screen->base != 'settings_page_template-hero-elementor-options' && $screen->base != 'settings_page_template-hero-elementor-options-network' && $screen->base != 'toplevel_page_template-hero-elementor-options-network' ) {
            
            return;
		}
		
		wp_enqueue_style( 
			$this->plugin_name, 
			plugin_dir_url( __DIR__ ). 'assets/css/template-hero-elementor-admin.css', 
			array(), 
			$this->version, 
			'all' 
		);
		if ( isset( $_GET['tab'] ) ) {
            $tab  = $_GET['tab'];
        } else {
            $tab  = '';
		}
		if ( $tab == 'general' || $tab == 'mu_general' ) {
			wp_enqueue_style( 
				$this->plugin_name.'-swal', 
				plugin_dir_url( __DIR__ ). 'assets/css/template-hero-elementor-swal2.css', 
				array(), 
				$this->version, 
				'all' 
			);
		}
	}

	/**
	 * Get Elementor Config Data
	 *
	 * @param [type] $config
	 * @since 1.1.4
	 * @return void
	 */
	public static function set_elementor_config_data( $config ) {
		self::$config = $config;
	}
	/**
	* Dequeue the jQuery UI script.
	* @since 1.1.4
	* Hooked to the wp_print_scripts action, with a late priority (100),
	* so that it is after the script was enqueued.
	
	*/
	public function th_docs_dequeue_script() {
	
		if ( ! ( Plugin::$instance->editor->is_edit_mode() ) ) {
			return;
	   	} 
		wp_dequeue_script( 'elementor-editor' );
		$suffix = ( defined( 'SCRIPT_DEBUG' ) && SCRIPT_DEBUG || defined( 'ELEMENTOR_TESTS' ) && ELEMENTOR_TESTS ) ? '' : '.min';
		if( ELEMENTOR_VERSION < 3 ) {
            $js_resource = plugin_dir_url( __DIR__ ). 'assets/js/editor-legacy'.$suffix.'.js';
        } else {
            $js_resource = plugin_dir_url( __DIR__ ). 'assets/js/editor'.$suffix.'.js';
        }

		wp_register_script(
			'elementor-editors',
            $js_resource,
			[
				'elementor-common',
				'elementor-editor-modules',
				'elementor-editor-document',
				'wp-auth-check',
				'jquery-ui-sortable',
				'jquery-ui-resizable',
				'perfect-scrollbar',
				'nprogress',
				'tipsy',
				'imagesloaded',
				'heartbeat',
				'jquery-elementor-select2',
				'flatpickr',
				'ace',
				'ace-language-tools',
				'jquery-hover-intent',
				'nouislider',
				'pickr',
				'react',
				'react-dom',
			],
			ELEMENTOR_VERSION,
			true
		);
		
		$plugin              = Plugin::$instance;
        if( ELEMENTOR_VERSION >= 3 ) {
            $page_title_selector = Plugin::$instance->kits_manager->get_current_settings( 'page_title_selector' );
        } else {
            $kit = Plugin::$instance->kits_manager->get_active_kit();
            if( $kit ) {
                $page_title_selector = $kit->get_settings( 'page_title_selector' );
            }
        }
        if( !empty( $page_title_selector ) ) {
			$page_title_selector .= ', .elementor-page-title';
		}

		$settings = SettingsManager::get_settings_managers_config();
		// Moved to document since 2.9.0.
		//unset( $settings['page'] );
		$kits_manager = Plugin::$instance->kits_manager;
		if ( empty( $page_title_selector ) ) {
			$page_title_selector = 'h1.entry-title';
		}
		if ( self::$config == '' || empty( self::$config ) ) {
			$settings = SettingsManager::get_settings_managers_config();
			// Moved to document since 2.9.0.
			unset( $settings['page'] );

			$document     = Plugin::$instance->documents->get_doc_or_auto_save( $_REQUEST['post'] );
			$library      = __( 'Library', 'template-hero-elementor' );
			$pages        = __( 'Pages', 'template-hero-elementor' );
			$blocks       = __( 'Blocks', 'template-hero-elementor' );
			$my_templates = __( 'My Templates', 'template-hero-elementor' );
			$custom_templates = get_site_option( 'th_cl_tab_title', 'Custom Templates' );
			$custom_templates = __( $custom_templates, 'template-hero-elementor' );

			$pages        = apply_filters( "th_elementor_library_pages_tab_title", $pages  );
			$blocks       = apply_filters( "th_elementor_library_blocks_tab_title", $blocks  );
			$my_templates = apply_filters( "th_elementor_library_my_templates_tab_title", $my_templates  );

			$custom_templates = apply_filters( "th_elementor_library_custom_templates_tab_title", $custom_templates  );
			$library          = apply_filters( "th_elementor_library_title", $library );
			
			$config = [
				'initial_document'  => $document->get_config(),
				'custom_templates_title' => $custom_templates,
				'version'           => ELEMENTOR_VERSION,
				'home_url'          => home_url(),
				'autosave_interval' => AUTOSAVE_INTERVAL,
				'tabs'     => $plugin->controls_manager->get_tabs(),
				'controls' => $plugin->controls_manager->get_controls_data(),
				'elements' => $plugin->elements_manager->get_element_types_config(),
				'schemes'  => [
					'items'           => $plugin->schemes_manager->get_registered_schemes_data(),
					'enabled_schemes' => Schemes_Manager::get_enabled_schemes(),
				],
				'icons' => [
					'libraries' => Icons_Manager::get_icon_manager_tabs_config(),
					'goProURL'  => Utils::get_pro_link( 'https://elementor.com/pro/?utm_source=icon-library&utm_campaign=gopro&utm_medium=wp-dash' ),
				],
               'globals' => [
                    'defaults_enabled' => [
                        'colors'     => (ELEMENTOR_VERSION >= 3 ? $kits_manager->is_custom_colors_enabled() : ! get_option( 'elementor_disable_color_schemes' )),
                        'typography' => (ELEMENTOR_VERSION >= 3 ? $kits_manager->is_custom_typography_enabled() : ! get_option( 'elementor_disable_typography_schemes' )),
                    ],
                ],
                'filesUpload' => [
                    'unfilteredFiles' => (ELEMENTOR_VERSION >= 3 ? Files_Upload_Handler::is_enabled() : ''),
                ],
				'fa4_to_fa5_mapping_url' => ELEMENTOR_ASSETS_URL . 'lib/font-awesome/migration/mapping.js',
				'default_schemes'      => $plugin->schemes_manager->get_schemes_defaults(),
				'settings'             => $settings,
				'system_schemes'       => $plugin->schemes_manager->get_system_schemes(),
				'wp_editor'            => $this->get_wp_editor_config(),
				'settings_page_link'   => Settings::get_url(),
				'tools_page_link'      => Tools::get_url(),
				'elementor_site'       => 'https://go.elementor.com/about-elementor/',
				'docs_elementor_site'  => 'https://go.elementor.com/docs/',
				'help_the_content_url' => 'https://go.elementor.com/the-content-missing/',
				'help_right_click_url' => 'https://go.elementor.com/meet-right-click/',
				'help_flexbox_bc_url'  => 'https://go.elementor.com/flexbox-layout-bc/',
				'elementPromotionURL'  => 'https://go.elementor.com/go-pro-%s',
				'dynamicPromotionURL'  => 'https://go.elementor.com/go-pro-dynamic-tag',
				'additional_shapes'    => Shapes::get_additional_shapes_for_config(),
				'user' => [
					'restrictions'     => $plugin->role_manager->get_user_restrictions_array(),
					'is_administrator' => current_user_can( 'manage_options' ),
					'introduction'     => User::get_introduction_meta(),
				],
				'preview' => [
					'help_preview_error_url'          => 'https://go.elementor.com/preview-not-loaded/',
					'help_preview_http_error_url'     => 'https://go.elementor.com/preview-not-loaded/#permissions',
					'help_preview_http_error_500_url' => 'https://go.elementor.com/500-error/',
					'debug_data'                      => Loading_Inspection_Manager::instance()->run_inspections(),
				],
				'locale'                 => get_locale(),
				'rich_editing_enabled'   => filter_var( get_user_meta( get_current_user_id(), 'rich_editing', true ), FILTER_VALIDATE_BOOLEAN ),
				'page_title_selector'    => $page_title_selector,
				'tinymceHasCustomConfig' => class_exists( 'Tinymce_Advanced' ),
				'inlineEditing'          => Plugin::$instance->widgets_manager->get_inline_editing_config(),
				'dynamicTags'            => Plugin::$instance->dynamic_tags->get_config(),
				'ui' => [
					'darkModeStylesheetURL' => ELEMENTOR_ASSETS_URL . 'css/editor-dark-mode' . $suffix . '.css',
				],
					// Legacy Mode - for backwards compatibility of older HTML markup.
                'legacyMode' => [
                    'elementWrappers' => (ELEMENTOR_VERSION >= 3 ? Plugin::instance()->get_legacy_mode( 'elementWrappers' ) : ''),
                ],
				'i18n' => [
					'template-hero-elementor' => __( 'Elementor', 'template-hero-elementor' ),
					'edit'      => __( 'Edit', 'template-hero-elementor' ),
					'delete'    => __( 'Delete', 'template-hero-elementor' ),
					'cancel'    => __( 'Cancel', 'template-hero-elementor' ),
					'clear'     => __( 'Clear', 'template-hero-elementor' ),
					'done'      => __( 'Done', 'template-hero-elementor' ),
					'got_it'    => __( 'Got It', 'template-hero-elementor' ),
					/* translators: %s: Element type. */
					'add_element'       => __( 'Add %s', 'template-hero-elementor' ),
					/* translators: %s: Element name. */
					'edit_element'      => __( 'Edit %s', 'template-hero-elementor' ),
					/* translators: %s: Element type. */
					'duplicate_element' => __( 'Duplicate %s', 'template-hero-elementor' ),
					/* translators: %s: Element type. */
					'delete_element'            => __( 'Delete %s', 'template-hero-elementor' ),
					'flexbox_attention_header'  => __( 'Note: Flexbox Changes', 'template-hero-elementor' ),
					'flexbox_attention_message' => __( 'Elementor 2.5 introduces key changes to the layout using CSS Flexbox. Your existing pages might have been affected, please review your page before publishing.', 'template-hero-elementor' ),
					'add_picked_color'          => __( 'Add Picked Color', 'template-hero-elementor' ),
					'saved_colors'              => __( 'Saved Colors', 'template-hero-elementor' ),
					'drag_to_delete'            => __( 'Drag To Delete', 'template-hero-elementor' ),

					// Menu.
					'about_elementor'    => __( 'About Elementor', 'template-hero-elementor' ),
					'elementor_settings' => __( 'Dashboard Settings', 'template-hero-elementor' ),
					'global_colors'      => __( 'Default Colors', 'template-hero-elementor' ),
					'global_fonts'       => __( 'Default Fonts', 'template-hero-elementor' ),
					'global_style'       => __( 'Global Style', 'template-hero-elementor' ),
					'global_settings'    => __( 'Global Settings', 'template-hero-elementor' ),
					'preferences'        => __( 'Preferences', 'template-hero-elementor' ),
					'settings'           => __( 'Settings', 'template-hero-elementor' ),
					'more'               => __( 'More', 'template-hero-elementor' ),
					'view_page'          => __( 'View Page', 'template-hero-elementor' ),
					'exit_to_dashboard'  => __( 'Exit To Dashboard', 'template-hero-elementor' ),

					// Elements.
					'inner_section' => __( 'Inner Section', 'template-hero-elementor' ),

					// Control Order.
					'asc'  => __( 'Ascending order', 'template-hero-elementor' ),
					'desc' => __( 'Descending order', 'template-hero-elementor' ),

					// Clear Page.
					'clear_page'                => __( 'Delete All Content', 'template-hero-elementor' ),
					'dialog_confirm_clear_page' => __( 'Attention: We are going to DELETE ALL CONTENT from this page. Are you sure you want to do that?', 'template-hero-elementor' ),

					// Enable SVG uploads.
					'enable_svg'                => __( 'Enable SVG Uploads', 'template-hero-elementor' ),
					'dialog_confirm_enable_svg' => __( 'Before you enable SVG upload, note that SVG files include a security risk. Elementor does run a process to remove possible malicious code, but there is still risk involved when using such files.', 'template-hero-elementor' ),

					// Enable fontawesome 5 if needed.
					'enable_fa5'                => __( 'Elementor\'s New Icon Library', 'template-hero-elementor' ),
					'dialog_confirm_enable_fa5' => __( 'Elementor v2.6 includes an upgrade from Font Awesome 4 to 5. In order to continue using icons, be sure to click "Upgrade".', 'template-hero-elementor' ) . ' <a href="https://go.elementor.com/fontawesome-migration/" target="_blank">' . __( 'Learn More', 'template-hero-elementor' ) . '</a>',

					// Panel Preview Mode.
					'back_to_editor' => __( 'Show Panel', 'template-hero-elementor' ),
					'preview'        => __( 'Hide Panel', 'template-hero-elementor' ),

					// Inline Editing.
					'type_here' => __( 'Type Here', 'template-hero-elementor' ),

					// Library.
					'an_error_occurred'                        => __( 'An error occurred', 'template-hero-elementor' ),
					'category'                                 => __( 'Category', 'template-hero-elementor' ),
					'delete_template'                          => __( 'Delete Template', 'template-hero-elementor' ),
					'delete_template_confirm'                  => __( 'Are you sure you want to delete this template?', 'template-hero-elementor' ),
					'import_template_dialog_header'            => __( 'Import Document Settings', 'template-hero-elementor' ),
					'import_template_dialog_message'           => __( 'Do you want to also import the document settings of the template?', 'template-hero-elementor' ),
					'import_template_dialog_message_attention' => __( 'Attention: Importing may override previous settings.', 'template-hero-elementor' ),
					'library' => $library,
					'no'      => __( 'No', 'template-hero-elementor' ),
					'page'    => __( 'Page', 'template-hero-elementor' ),
					/* translators: %s: Template type. */
					'save_your_template'             => __( 'Save Your %s to Library', 'template-hero-elementor' ),
					'save_your_template_description' => __( 'Your designs will be available for export and reuse on any page or website', 'template-hero-elementor' ),
					'section'                        => __( 'Section', 'template-hero-elementor' ),
					'templates_empty_message'        => __( 'This is where your templates should be. Design it. Save it. Reuse it.', 'template-hero-elementor' ),
					'templates_empty_title'          => __( 'Haven’t Saved Templates Yet?', 'template-hero-elementor' ),
					'templates_no_favorites_message' => __( 'You can mark any pre-designed template as a favorite.', 'template-hero-elementor' ),
					'templates_no_favorites_title'   => __( 'No Favorite Templates', 'template-hero-elementor' ),
					'templates_no_results_message'   => __( 'Please make sure your search is spelled correctly or try a different words.', 'template-hero-elementor' ),
					'templates_no_results_title'     => __( 'No Results Found', 'template-hero-elementor' ),
					'templates_request_error'        => __( 'The following error(s) occurred while processing the request:', 'template-hero-elementor' ),
					'yes'                            => __( 'Yes', 'template-hero-elementor' ),
					'blocks'                         => $blocks,
					'pages'                          => $pages,
					'th_elementor_custom'            => __( 'Custom Templates', 'template-hero-elementor' ),
					'my_templates'                   => $my_templates,

					// Incompatible Device.
					'device_incompatible_header'  => __( 'Your browser isn\'t compatible', 'template-hero-elementor' ),
					'device_incompatible_message' => __( 'Your browser isn\'t compatible with all of Elementor\'s editing features. We recommend you switch to another browser like Chrome or Firefox.', 'template-hero-elementor' ),
					'proceed_anyway'              => __( 'Proceed Anyway', 'template-hero-elementor' ),

					// Preview not loaded.
					'learn_more'                   => __( 'Learn More', 'template-hero-elementor' ),
					'preview_el_not_found_header'  => __( 'Sorry, the content area was not found in your page.', 'template-hero-elementor' ),
					'preview_el_not_found_message' => __( 'You must call \'the_content\' function in the current template, in order for Elementor to work on this page.', 'template-hero-elementor' ),

					// Gallery.
					'delete_gallery'                => __( 'Reset Gallery', 'template-hero-elementor' ),
					'dialog_confirm_gallery_delete' => __( 'Are you sure you want to reset this gallery?', 'template-hero-elementor' ),
					/* translators: %s: The number of images. */
					'gallery_images_selected'    => __( '%s Images Selected', 'template-hero-elementor' ),
					'gallery_no_images_selected' => __( 'No Images Selected', 'template-hero-elementor' ),
					'insert_media'               => __( 'Insert Media', 'template-hero-elementor' ),

					// Take Over.
					/* translators: %s: User name. */
					'dialog_user_taken_over' => __( '%s has taken over and is currently editing. Do you want to take over this page editing?', 'template-hero-elementor' ),
					'go_back'                => __( 'Go Back', 'template-hero-elementor' ),
					'take_over'              => __( 'Take Over', 'template-hero-elementor' ),

					// Revisions.
					/* translators: %s: Template type. */
					'dialog_confirm_delete' => __( 'Are you sure you want to remove this %s?', 'template-hero-elementor' ),

					// Saver.
					'before_unload_alert' => __( 'Please note: All unsaved changes will be lost.', 'template-hero-elementor' ),
					'published' => __( 'Published', 'template-hero-elementor' ),
					'publish'   => __( 'Publish', 'template-hero-elementor' ),
					'save'      => __( 'Save', 'template-hero-elementor' ),
					'saved'     => __( 'Saved', 'template-hero-elementor' ),
					'update'    => __( 'Update', 'template-hero-elementor' ),
					'enable'    => __( 'Enable', 'template-hero-elementor' ),
					'submit'    => __( 'Submit', 'template-hero-elementor' ),
					'working_on_draft_notification' => __( 'This is just a draft. Play around and when you\'re done - click update.', 'template-hero-elementor' ),
					'keep_editing'       => __( 'Keep Editing', 'template-hero-elementor' ),
					'have_a_look'        => __( 'Have a look', 'template-hero-elementor' ),
					'view_all_revisions' => __( 'View All Revisions', 'template-hero-elementor' ),
					'dismiss'            => __( 'Dismiss', 'template-hero-elementor' ),
					'saving_disabled'    => __( 'Saving has been disabled until you’re reconnected.', 'template-hero-elementor' ),

					// Ajax
					'server_error'       => __( 'Server Error', 'template-hero-elementor' ),
					'server_connection_lost' => __( 'Connection Lost', 'template-hero-elementor' ),
					'unknown_error'      => __( 'Unknown Error', 'template-hero-elementor' ),

					// Context Menu
					'duplicate'          => __( 'Duplicate', 'template-hero-elementor' ),
					'copy'               => __( 'Copy', 'template-hero-elementor' ),
					'paste'              => __( 'Paste', 'template-hero-elementor' ),
					'copy_style'         => __( 'Copy Style', 'template-hero-elementor' ),
					'paste_style'        => __( 'Paste Style', 'template-hero-elementor' ),
					'reset_style'        => __( 'Reset Style', 'template-hero-elementor' ),
					'save_as_global'     => __( 'Save as a Global', 'template-hero-elementor' ),
					'save_as_block'      => __( 'Save as Template', 'template-hero-elementor' ),
					'new_column'         => __( 'Add New Column', 'template-hero-elementor' ),
					'copy_all_content'   => __( 'Copy All Content', 'template-hero-elementor' ),
					'delete_all_content' => __( 'Delete All Content', 'template-hero-elementor' ),
					'navigator'          => __( 'Navigator', 'template-hero-elementor' ),

					// Right Click Introduction
					'meet_right_click_header'  => __( 'Meet Right Click', 'template-hero-elementor' ),
					'meet_right_click_message' => __( 'Now you can access all editing actions using right click.', 'template-hero-elementor' ),

					// Hotkeys screen
					'keyboard_shortcuts' => __( 'Keyboard Shortcuts', 'template-hero-elementor' ),

					// Deprecated Control
					'deprecated_notice'              => __( 'The <strong>%1$s</strong> widget has been deprecated since %2$s %3$s.', 'template-hero-elementor' ),
					'deprecated_notice_replacement'  => __( 'It has been replaced by <strong>%1$s</strong>.', 'template-hero-elementor' ),
					'deprecated_notice_last'         => __( 'Note that %1$s will be completely removed once %2$s %3$s is released.', 'template-hero-elementor' ),

					//Preview Debug
					'preview_debug_link_text' => __( 'Click here for preview debug', 'template-hero-elementor' ),

					'icon_library'       => __( 'Icon Library', 'template-hero-elementor' ),
					'my_libraries'       => __( 'My Libraries', 'template-hero-elementor' ),
					'upload'             => __( 'Upload', 'template-hero-elementor' ),
					'icons_promotion'    => __( 'Become a Pro user to upload unlimited font icon folders to your website.', 'template-hero-elementor' ),
					'go_pro'             => __( 'Go Pro', 'template-hero-elementor' ),
					'custom_positioning' => __( 'Custom Positioning', 'template-hero-elementor' ),

					'element_promotion_dialog_header'  => __( '%s Widget', 'template-hero-elementor' ),
					'element_promotion_dialog_message' => __( 'Use %s widget and dozens more pro features to extend your toolbox and build sites faster and better.', 'template-hero-elementor' ),
					'see_it_in_action'          => __( 'See it in Action', 'template-hero-elementor' ),
					'dynamic_content'           => __( 'Dynamic Content', 'template-hero-elementor' ),
					'dynamic_promotion_message' => __( 'Create more personalized and dynamic sites by populating data from various sources with dozens of dynamic tags to choose from.', 'template-hero-elementor' ),
					'available_in_pro_v29'      => __( 'Available in Pro V2.9.', 'template-hero-elementor' ),

					// TODO: Remove.
					'autosave'                => __( 'Autosave', 'template-hero-elementor' ),
					'elementor_docs'          => __( 'Documentation', 'template-hero-elementor' ),
					'reload_page'             => __( 'Reload Page', 'template-hero-elementor' ),
					'session_expired_header'  => __( 'Timeout', 'template-hero-elementor' ),
					'session_expired_message' => __( 'Your session has expired. Please reload the page to continue editing.', 'template-hero-elementor' ),
					'soon'                    => __( 'Soon', 'template-hero-elementor' ),
					'unknown_value'           => __( 'Unknown Value', 'template-hero-elementor' ),
				],
			];
			if ( ! Utils::has_pro() && current_user_can( 'manage_options' ) ) {
				$config['promotionWidgets'] = Api::get_promotion_widgets();
			}
			$this->bc_move_document_filters();
			self::set_elementor_config_data( $config );
		}
	
		self::$config = apply_filters( 'elementor/editor/localize_settings', self::$config );

		Utils::print_js_config( 'elementor-editors', 'ElementorConfig', self::$config );

		wp_enqueue_script( 'elementor-editors' );
		
		$plugin->controls_manager->enqueue_control_scripts();
	}

	private function bc_move_document_filters() {
		global $wp_filter;

		$old_tag = 'elementor/editor/localize_settings';
		$new_tag = 'elementor/document/config';

		if ( ! has_filter( $old_tag ) ) {
			return;
		}

		foreach ( $wp_filter[ $old_tag ] as $priority => $filters ) {
			foreach ( $filters as $filter_id => $filter_args ) {
				if ( 2 === $filter_args['accepted_args'] ) {
					remove_filter( $old_tag, $filter_id, $priority );

					add_filter( $new_tag, $filter_args['function'], $priority, 2 );

					// TODO: Hard deprecation
					// _deprecated_hook( '`' . $old_tag . ` is no longer using post_id', '2.9.0', $new_tag' );
				}
			}
		}
	}
	/**
	 * Hide Elementor Library Connect Pop up
	 *
	 * @param [array] $settings
	 * @since 1.1.4
	 * @return void
	 */
	public function th_localize_setting( $settings ) {
		
		$is_connected = true;

		return array_replace_recursive( $settings, [
			'i18n' => [
				// Route: library/connect
				'library/connect:title'   => __( 'Connect to Template Library', 'template-hero-elementor' ),
				'library/connect:message' => __( 'Access this template and our entire library by creating a free personal account', 'template-hero-elementor' ),
				'library/connect:button'  => __( 'Get Started', 'template-hero-elementor' ),
			],
			'library_connect' => [
				'is_connected' => $is_connected,
			],
		] );
	
	}
	/**
	 * Register the JavaScript for the admin area.
	 *
	 * @since    1.0.0
	 */
	public function enqueue_scripts() {
		$screen = get_current_screen();
		$is_elementor_screen = ( $screen && false !== strpos( $screen->id, 'the' ) );
        if( !$is_elementor_screen && $screen->id != 'plans_page_wu-edit-plan-network' && $screen->base != 'settings_page_template-hero-elementor-options' && $screen->base != 'settings_page_template-hero-elementor-options-network' && $screen->base != 'toplevel_page_template-hero-elementor-options-network' ) {
            
            return;
        }
		wp_enqueue_script( 
			$this->plugin_name, 
			plugin_dir_url( __DIR__ ). 'assets/js/template-hero-elementor-admin.js', 
			array( 'jquery' ), 
			$this->version, 
			true 
		);

		wp_enqueue_script( 
			$this->plugin_name.'-license', 
			plugin_dir_url( __DIR__ ). 'assets/js/license-ajax.js', 
			array( 'jquery' ), 
			$this->version, 
			true 
		);

		wp_enqueue_script( 
			'sweetalert2' , 
			'https://cdn.jsdelivr.net/npm/sweetalert2@9', 
			array( 'jquery' ), 
			$this->version, 
			true 
		);

		if ( isset( $_GET['tab'] ) ) {
            $tab  = $_GET['tab'];
        } else {
            $tab  = '';
        }
        $user_id =  get_current_user_id();
        wp_localize_script( $this->plugin_name, 'templatehero_ajax_obj',
            array( 
                'ajaxurl' => admin_url( 'admin-ajax.php' ),
                'user_id' => $user_id,
                'blog_id' => get_current_blog_id(),
				'tab'     => $tab,
				'th_create_token_security' => wp_create_nonce( 'create-token-' . $user_id )
            )
        );
	}

	/**
	 * Get WordPress editor config.
	 *
	 * Config the default WordPress editor with custom settings for Elementor use.
	 *
	 * @since 1.1.4
	 * @access private
	 */
	private function get_wp_editor_config() {
		// Remove all TinyMCE plugins.
		remove_all_filters( 'mce_buttons', 10 );
		remove_all_filters( 'mce_external_plugins', 10 );

		if ( ! class_exists( '\_WP_Editors', false ) ) {
			require ABSPATH . WPINC . '/class-wp-editor.php';
		}

		// WordPress 4.8 and higher
		if ( method_exists( '\_WP_Editors', 'print_tinymce_scripts' ) ) {
			\_WP_Editors::print_default_editor_scripts();
			\_WP_Editors::print_tinymce_scripts();
		}
		ob_start();

		wp_editor(
			'%%EDITORCONTENT%%',
			'elementorwpeditor',
			[
				'editor_class'     => 'elementor-wp-editor',
				'editor_height'    => 250,
				'drag_drop_upload' => true,
			]
		);

		$config = ob_get_clean();

		// Don't call \_WP_Editors methods again
		remove_action( 'admin_print_footer_scripts', [ '_WP_Editors', 'editor_js' ], 50 );
		remove_action( 'admin_print_footer_scripts', [ '_WP_Editors', 'print_default_editor_scripts' ], 45 );

		\_WP_Editors::editor_js();

		return $config;
	}
	

	/**
	 * License Network Settongs
	 * @since 1.0.0
	 * @return void
	 */
	public function th_update_license_options() {

		$user_id = get_current_user_id();
		$nonce = $_REQUEST['th_create_token_security'];
        if ( ! wp_verify_nonce( $nonce, 'create-token-' . $user_id ) ) {
            die( __( 'Failed security check.', 'template-hero-elementor') ); 
		}

		$license_key = $_REQUEST['th_form_license_input'];
        if ( empty($_REQUEST['th_form_license_input']) && !$_REQUEST['th_form_license_input'] ) {
			echo 'Empty license input.';
            die( __( 'Failed validation check.', 'template-hero-elementor') ); 
		}

		$active_license = self::activateLicense( $license_key );

		// Successful api response. $license_data->license will be either "valid" or "invalid"
		if( isset($active_license['license'] ) ) {

			update_option( '_template_hero_license_key_status', 'active' );

		} elseif( isset($active_license['status'] )  == 'error') {

			delete_option( '_template_hero_license_key_status' );
			switch_to_blog( 1 );
			delete_option( '_template_hero_license_key_status' );
			restore_current_blog();

		}

		update_option( '_template_hero_license_key', $license_key );
		wp_die( json_encode($active_license) );

	}
	

	/**
	 * Does Sanitization
	 * @since 1.0.0
	 * @param [type] $new
	 * @return void
	 */
	static private function sanitizeLicense( $new ) {

		$old = get_option( '_template_hero_license_key' );
		if( $old && $old != $new ) {
			delete_option( '_template_hero_license_key_status' ); 
		}

		return $new;
	}

	/**
	 * check for license
	 */
	public static function check_for_license( $license_key ) {
		// trim and sanitize the license 
		$license = trim( self::sanitizeLicense( $license_key ) );

		// set data to send in our API request
		$api_params = array(
			'edd_action' => 'check_license',
			'license'    => $license,
			'item_id'    => TEMPLATE_HERO_ELEMENTOR_ITEM_ID, // The ID of the item in EDD
			'url'        => home_url()
		);

			// Call the update API.
		$response = wp_remote_post( TEMPLATE_HERO_ELEMENTOR_STORE_URL, array( 'timeout' => 15, 'sslverify' => false, 'body' => $api_params ) );

			// Check the response for errors
		if ( empty($response) || is_wp_error( $response ) || 200 !== wp_remote_retrieve_response_code( $response ) ) {
			$message =  ( is_wp_error( $response ) && ! empty( $response->get_error_message() ) ) ? $response->get_error_message() : __( 'An error occurred, please try again.' );
		} else {
			$license_data = json_decode( wp_remote_retrieve_body( $response ) );
			if ( false === $license_data->success ) {
				switch( $license_data->error ) {
					case 'expired' :
						$message = sprintf(
							__( 'Your license key expired on %s.' ),
							date_i18n( get_site_option( 'date_format' ), strtotime( $license_data->expires, current_time( 'timestamp' ) ) )
						);
						break;
					case 'revoked' :
						$message = __( 'Your license key has been disabled.', 'template-hero-elementor' );
						break;
					case 'missing' :
						$message = __( 'Invalid license.', 'template-hero-elementor' );
						break;
					case 'invalid' :
						$message = __( 'Your license is invalid.',  );
						break;
					case 'site_inactive' :
						$message = __( 'Your license is not active for this URL.' );
						break;
					case 'item_name_mismatch' :
						$message = sprintf( __( 'This appears to be an invalid license key for %s.' ), TEMPLATE_HERO_ELEMENTOR_ITEM_NAME );
						break;
					case 'no_activations_left':
						$message = __( 'Your license key has reached its activation limit.' );
						break;
					default :
						$message = __( 'An error occurred, please try again.' );
						break;

				}
			}

			// Check for errror messages and return if true
			if ( ! empty( $message ) ) {
			
				return [
					'status' => 'error',
					'message' => $message
				];
				
			}

			return [
				'status' => 'updated',
				'license' => $license_data->license
			];
		}
	}

	/**
	 * Initiates license activation process
	 * @since 1.0.0
	 * @return void
	 */
	private static function activateLicense( $license_key ) {
		
		// trim and sanitize the license 
		$license = trim( self::sanitizeLicense( $license_key ) );

		// set data to send in our API request
		$api_params = array(
			'edd_action' => 'activate_license',
			'license'    => $license,
			'item_id'    => TEMPLATE_HERO_ELEMENTOR_ITEM_ID, // The ID of the item in EDD
			'url'        => home_url()
		);

			// Call the update API.
		$response = wp_remote_post( TEMPLATE_HERO_ELEMENTOR_STORE_URL, array( 'timeout' => 15, 'sslverify' => false, 'body' => $api_params ) );

			// Check the response for errors
		if ( empty($response) || is_wp_error( $response ) || 200 !== wp_remote_retrieve_response_code( $response ) ) {
			$message =  ( is_wp_error( $response ) && ! empty( $response->get_error_message() ) ) ? $response->get_error_message() : __( 'An error occurred, please try again.' );
		} else {
			$license_data = json_decode( wp_remote_retrieve_body( $response ) );
			if ( false === $license_data->success ) {
				switch( $license_data->error ) {
					case 'expired' :
						$message = sprintf(
							__( 'Your license key expired on %s.', 'template-hero-elementor' ),
							date_i18n( get_site_option( 'date_format' ), strtotime( $license_data->expires, current_time( 'timestamp' ) ) )
						);
						break;
					case 'revoked' :
						$message = __( 'Your license key has been disabled.', 'template-hero-elementor' );
						break;
					case 'missing' :
						$message = __( 'Invalid license.', 'template-hero-elementor' );
						break;
					case 'invalid' :
						$message = __( 'Your license is invalid.', 'template-hero-elementor' );
						break;
					case 'site_inactive' :
						$message = __( 'Your license is not active for this URL.', 'template-hero-elementor' );
						break;
					case 'item_name_mismatch' :
						$message = sprintf( __( 'This appears to be an invalid license key for %s.', 'template-hero-elementor' ), TEMPLATE_HERO_ELEMENTOR_ITEM_NAME );
						break;
					case 'no_activations_left':
						$message = __( 'Your license key has reached its activation limit.', 'template-hero-elementor' );
						break;
					default :
						$message = __( 'An error occurred, please try again.', 'template-hero-elementor' );
						break;

				}
			}
	
			// Check for errror messages and return if true
			if ( ! empty( $message ) ) {
			
				return [
					'status' => 'error',
					'message' => $message
				];
				
			}

			return [
				'status' => 'updated',
				'license' => $license_data->license
			];
		}
	}
}
