<?php
/**
 * Plugin Name: WP Ultimo: Plugin and Theme Manager
 * Description: Edit or hide the meta information (title, author, thumbnail and description) of installed plugins and themes. Create categories for plugins and themes to allow your users to filter them and add a beautiful custom Plugins page to your users' panel.
 * Plugin URI: http://wpultimo.com
 * Text Domain: wu-ptm
 * Version: 1.2.5
 * Author: Arindo Duque - NextPress
 * Author URI: http://nextpress.co/
 * Copyright: Arindo Duque, NextPress
 * Network: true
 */

if (!defined('ABSPATH')) {
  exit; // Exit if accessed directly.
}

if (!class_exists('WP_Ultimo_PTM')) :

/**
 * Here starts our plugin.
 */
class WP_Ultimo_PTM {

  /**
   * Version of the Plugin
   * @var string
   */
  public $version = '1.2.5';

  /**
   * Makes sure we are only using one instance of the plugin
   * @var object WP_Ultimo_PTM
   */
  public static $instance;

  /**
   * Get the post type we are going to use to save the extensions meta
   * @var string
   */
  public $post_type = 'wu_extension';

  /**
   * List of elements to hide using JS AND CSS
   * @var array
   */
  public $elements_to_hide = array();

  /**
   * List of plugin extensions
   *
   * @var array
   */
  public $extensions_plugin_list = array();

  /**
   * Returns the instance of WP_Ultimo_PTM
   * @return object A WP_Ultimo_PTM instance
   */
  public static function get_instance() {

    if (null === self::$instance) self::$instance = new self();

    return self::$instance;

  } // end get_instance;

  /**
   * Initializes the plugin
   */
  public function __construct() {

    load_plugin_textdomain('wu-ptm', false, dirname(plugin_basename(__FILE__)) . '/lang');

    // Required files
    require_once $this->path('inc/class-wu-model-extension.php');

    // Updater
    require_once $this->path('inc/class-wu-addon-updater.php');

    /**
     * @since 1.2.0 Creates the updater
     * @var WU_Addon_Updater
     */
    $updater = new WU_Addon_Updater('wp-ultimo-plugin-and-theme-manager', __('WP Ultimo: Plugin and Theme Manager', 'wp-ptm'), __FILE__);

    // Run Rorest, run!
    $this->hooks();

  } // end construct;

  /**
   * Return url to some plugin subdirectory
   * @return string Url to passed path
   */
  public function path($dir) {
    return plugin_dir_path(__FILE__).'/'.$dir;
  }

  /**
   * Return url to some plugin subdirectory
   * @return string Url to passed path
   */
  public function url($dir) {
    return plugin_dir_url(__FILE__).'/'.$dir;
  }

  /**
   * Return full URL relative to some file in assets
   * @return string Full URL to path
   */
  public function get_asset($asset, $assetsDir = 'img') {
    return $this->url("assets/$assetsDir/$asset");
  }

  /**
   * Render Views
   * @param string $view View to be rendered.
   * @param Array $vars Variables to be made available on the view escope, via extract().
   */
  public function render($view, $vars = false) {

    // Make passed variables available
    if (is_array($vars)) extract($vars);

    // Load our view
    include $this->path("views/$view.php");

  }

  /**
   * Install our default settings after activation
   * @return
   */
  public function on_activation() {

    if (class_exists('WU_Settings')) { WU_Settings::save_settings(false, true); }

  } // end on_activation;

  /**
   * Add the hooks we need to make this work
   */
  public function hooks() {

    register_activation_hook(__FILE__, array($this, 'on_activation'));

    add_action('wu_settings_sections', array($this, 'add_settings'));

    add_action('init', array($this, 'create_custom_post_type'));

    add_action('in_admin_header', array($this, 'add_inline_form'));

    add_action('admin_enqueue_scripts', array($this, 'enqueue_scripts'));

    add_action('current_screen', array($this, 'replace_wp_core_scripts'), 10000);

    add_action('wp_ajax_wu_save_extension', array($this, 'wu_save_extension'));

    add_action('wp_ajax_wu_get_extension', array($this, 'wu_get_extension'));

    add_filter('all_plugins', array($this, 'change_plugin_info'), 10);

    add_filter('all_themes', array($this, 'change_theme_info'), 10);

    add_filter('wp_prepare_themes_for_js', array($this, 'change_theme_info_js'), 10);

    add_filter('manage_plugins-network_columns', array($this, 'add_categories_column'));

    add_filter('manage_themes-network_columns', array($this, 'add_categories_column'));

    add_filter('network_admin_plugin_action_links', array($this, 'add_inline_edit_link'), 10, 4);

    add_filter('theme_action_links', array($this, 'add_inline_edit_link'), 10, 3);

    add_action('manage_plugins_custom_column' , array($this, 'render_categories_column'), 10, 3);

    add_action('manage_themes_custom_column' , array($this, 'render_categories_column'), 10, 3);

    add_filter('plugin_row_meta', array($this, 'clean_meta_row'), 10, 2);

    add_filter('theme_row_meta', array($this, 'clean_meta_row'), 10, 2);

    add_action('in_admin_header', array($this, 'replace_plugins_page'));

    add_action('in_admin_header', array($this, 'add_filters_to_theme_page'), 10, 2);

    add_filter('admin_body_class', array($this, 'add_admin_class'));

    add_action('delete_plugin', array($this, 'clean_extension_on_plugin_deletion'), 20);
    
    add_action('delete_site_transient_update_themes', array($this, 'clean_extension_on_theme_deletion'), 20);

  } // end hooks;

  /**
   * Remove plugin or theme data on deletion
   *
   * @since 1.2.2
   * @param string $plugin_file
   * @param boolean $deleted
   * @return void
   */
  public function clean_extension_on_plugin_deletion($plugin_file) {

    $extension = wu_get_extension_by_plugin_file($plugin_file);

    if ($extension->id) {

      $extension->delete();

    } // end if;

  } // end clean_extension_on_plugin_deletion;

  /**
   * Remove theme or theme data on deletion
   *
   * @since 1.2.2
   * @return void
   */
  public function clean_extension_on_theme_deletion() {

    if (isset($_REQUEST['slug']) && isset($_REQUEST['action']) && $_REQUEST['action'] == 'delete-theme') {

      $extension = wu_get_extension(strtolower($_REQUEST['slug']));

      if ($extension->id) {

        $extension->delete();

      } // end if;

    } // end if;

  } // end clean_extension_on_theme_deletion;

  /**
   * Add the processing page
   */
  function add_payment_processing_page() {

    $site_id = get_current_blog_id();

    $cap = 'manage_wu_'. $site_id .'_account';

    add_submenu_page(null, __('Unlock Extension', 'wp-ultimo'), __('Unlock Extension', 'wp-ultimo'), $cap, 'wu-buy-extension', array($this, 'render_payment_processing_page'));

  } // end add_payment_processing_page;

  /**
   * Render the Processing page of payments
   * @return
   */
  function render_payment_processing_page() {

    $this->render('page-payment');

  } // end render_payment_processing_page;

  /**
   * Adds the filter bar for the themes page
   */
  function add_filters_to_theme_page() {

    if (get_current_screen()->id !== 'themes') return;

    $type_slug = 'theme';

    wp_enqueue_style('wu-ptm');

    $this->dump_hide_scripts();

    ?>

    <div class="wp-filter" style="display: none;">
      <ul class="filter-links">

        <li class="selector-inactive">
            <a href="#" class="current" data-category=""><?php _e('All Themes'); ?></a>
        </li>

        <?php

        $cats = WP_Ultimo_PTM::get_categories($type_slug);

        foreach ($cats as $cat_slug => $cat) { ?>

            <li>
                <a href="?s=<?php echo $cat_slug; ?>" class="" data-category="<?php echo $cat_slug; ?>"><?php echo $cat; ?></a>
            </li>

        <?php } ?>

      </ul>
    </div>

    <script type="text/javascript">
      (function($){
        $(document).ready(function() {
          $('.wp-filter').insertAfter($( '#wpbody h1:first' )).show();
        });
      })(jQuery);
    </script>


  <?php } // end add_filters_to_theme_page;

  /**
   * Adds the custom settings to the add-on section of our settings page on WP Ultimo
   * @param array $sections Sections;
   */
  function add_settings($sections) {

    /**
     * Check if section for add-ons already exists
     */

    if (!isset($sections['addons-settings'])) {

      $sections['addons-settings'] = array(
        'title'  => __('Add-on Settings', 'wp-ultimo'),
        'desc'   => __('Add-on Settings', 'wp-ultimo'),
        'fields' => array(
          'advanced'        => array(
            'title'         => __('Add-on Settings', 'wu-ptm'),
            'desc'          => __('Settings added by add-ons installed.', 'wu-ptm'),
            'type'          => 'heading',
          ),
        )
      );

    } // end if;

    /**
     * Add the fields
     */
    $sections['addons-settings']['fields']['wu-ptm-options'] = array(
      'title'          => __('WP Ultimo: Plugin and Theme Manager Options', 'wp-ultimo'),
      'desc'           => __('', 'wp-ultimo'),
      'type'           => 'heading_collapsible',
    );

    $sections['addons-settings']['fields']['wu-ptm-replace-plugins-page'] = array(
      'title'         => __('Replace the Plugin Page', 'wp-ultimo'),
      'desc'          => __('Check this option if you want to replace the default WordPress Plugin Page of your clients\’ site with a custom theme-like page, including advanced filtering and display.', 'wp-ultimo') .' '. sprintf('<a href="%s" target="_blank">%s</a>', $this->get_asset('preview-plugin-page.png'), __('Here\'s how the page will look', 'wp-ultimo')),
      'tooltip'       => '',
      'type'          => 'checkbox',
      'default'       => true,
    );

    $sections['addons-settings']['fields']['wu-ptm-display-type'] = array(
      'title'         => __('Display Type', 'wp-ultimo'),
      'desc'          => __('', 'wp-ptm'),
      'tooltip'       => '',
      'type'          => 'select',
      'default'       => 'plugin-card',
      'options'       => array(
        'plugin-card' => __('Plugin Style', 'wp-ptm'),
        'theme'       => __('Theme Style', 'wp-ptm'),
      )
    );

    $sections['addons-settings']['fields']['wu-ptm-all-sites'] = array(
      'title'         => __('Apply Changes to All Sites', 'wp-ultimo'),
      'desc'          => __('By default, we only display the changes made to the plugin and theme\'s metadata for Ultimo sites (sites onwed by users with an WP Ultimo subscription). Use this option to apply the changes to all sites in the network.', 'wp-ultimo'),
      'tooltip'       => '',
      'type'          => 'checkbox',
      'default'       => false,
    );

    $sections['addons-settings']['fields']['wu-ptm-display-author'] = array(
      'title'         => __('Display Plugin\'s Author', 'wp-ultimo'),
      'desc'          => __('Check this box if you want to display the plugin\’s author info to your users.', 'wp-ultimo'),
      'tooltip'       => '',
      'type'          => 'checkbox',
      'default'       => true,
    );

    $sections['addons-settings']['fields']['wu-ptm-display-version'] = array(
      'title'         => __('Display Plugin\'s Version', 'wp-ultimo'),
      'desc'          => __('Check this box if you want to display the plugin\’s version to your users.', 'wp-ultimo'),
      'tooltip'       => '',
      'type'          => 'checkbox',
      'default'       => true,
    );

    $sections['addons-settings']['fields']['wu-ptm-display-details'] = array(
      'title'         => __('Display Plugin\'s Details', 'wp-ultimo'),
      'desc'          => __('Check this box if you want to display the plugin\’s details modal to your users. This option is only used if you do not use the custom plugin page.', 'wp-ultimo'),
      'tooltip'       => '',
      'type'          => 'checkbox',
      'default'       => true,
    );

    $sections['addons-settings']['fields']['wu-ptm-display-extra-links'] = array(
      'title'         => __('Display Plugin\'s Extra Links', 'wp-ultimo'),
      'desc'          => __('Check this box if you want to display the plugin\’s extra links to your users. This option is only used if you do not use the custom plugin page.', 'wp-ultimo'),
      'tooltip'       => '',
      'type'          => 'checkbox',
      'default'       => true,
      // 'require'       => array('wu-ptm-replace-plugins-page' => 0)
    );

    return $sections;

  } // end add_settings;

  /**
   * Add classes of control to the body class
   * @param string $classes
   */
  function add_admin_class($classes) {

    $screen = get_current_screen();

    if ($this->should_replace_screen() || $screen->id == 'themes') {

      return "wu-ptm-page $classes themes-php";

    } // end if;

    else return $classes;

  } // end add_admin_class;

  /**
   * Check if a given user is a ultimo user
   * @param  interger $user_id
   * @return boolean
   */
  public function should_apply_changes($user_id = false) {

    if (!function_exists('get_current_screen')) return false;

    $allowed = array('themes-network', 'plugins-network', 'plugins', 'themes');

    $screen = get_current_screen();

    if ($screen && in_array($screen->id, $allowed)) return true;

    if (WU_Settings::get_setting('wu-ptm-all-sites', true) === true) return true;

    $user_id = $user_id ? $user_id : wu_get_current_site()->site_owner_id;

    return wu_get_subscription($user_id);

  } // end is_ultimo_site;

  /**
   * Check if we should replace screen
   *
   * @since 1.1.1
   * @return boolean
   */
  public function should_replace_screen() {
    if (WU_Settings::get_setting('wu-ptm-replace-plugins-page', true) && $this->should_apply_changes()) {

      $screen = get_current_screen();

      if ($screen->id !== 'plugins') return false;

      return true;

    } // end if;

    return false;

  } // end should_replace_screen;

  /**
   * Replace the plugins page, if that option is selected
   */
  public function replace_plugins_page() {

    if ($this->should_replace_screen()) {

      $screen = get_current_screen();

      $check_base = array(
        $screen->parent_base,
        $screen->base,
        $screen->id
      );
   
      $this->render('page-plugins', array(
        'type_slug'    => in_array('plugins', $check_base) ? 'plugin' : 'theme',
        'display_type' => WU_Settings::get_setting('wu-ptm-display-type', 'theme'),
      ));

      exit;

    } // end if;

  } // end replace_plugins_page;

  /**
   * Prepare Plugins for JS on our new plugins page
   * @return array
   */
  public function prepare_plugins_for_js() {

    /**
     * We need to get the user plan
     * @since 1.1.0
     */
    $site = wu_get_current_site();

    $plan_id = $site ? $site->plan_id : 0;

    $all_plugins = apply_filters( 'all_plugins', get_plugins() );

    $prepared_plugins = array();

    foreach($all_plugins as $plugin_file => $plugin) {

      $slug = $this->get_slug_from_file($plugin_file);

      $extension = wu_get_extension($slug);

      if ($extension) {

        $active = is_plugin_active($plugin_file);

        $prepared_plugins[] = array(
          'id'           => $slug,
          'name'         => $plugin['Name'],
          'screenshot'   => array($extension ? $extension->get_thumbnail() : WP_Ultimo_PTM()->get_asset('extension-placeholder.png')), // @todo multiple
          'description'  => $plugin['Description'],
          'author'       => $extension && $extension->display_author ? $plugin['Author'] : '',
          'authorAndUri' => $extension && $extension->display_author ? $plugin['Author'] : '',
          'version'      => $extension && $extension->display_version ? $plugin['Version'] : '',
          'tags'         => $extension ? $extension->get_categories_string() : __('None', 'wp-ptm'),
          'parent'       => false,
          'active'       => $active, // $slug === $current_theme,
          'hasUpdate'    => false,
          'hasPackage'   => false,
          'update'       => false,
          'network'      => isset($plugin['Network']) ? $plugin['Network'] : false,
          'actions'      => array(
            'activate'      => wp_nonce_url('plugins.php?action=activate&amp;plugin=' . $plugin_file, 'activate-plugin_' . $plugin_file),
            'deactivate'    => wp_nonce_url('plugins.php?action=deactivate&amp;plugin=' . $plugin_file, 'deactivate-plugin_' . $plugin_file),
            'buy'           => wp_nonce_url('admin.php?page=wu-buy-extension&extension=' . $plugin_file, 'buy_extension_' . $plugin_file),
          ),

          /**
           * @since  1.1.0 Add the pricing things
           */
          'isFree'     => $extension->is_free($plan_id),
          'isUnlocked' => $extension->is_unlocked(),
          'price'      => wu_format_currency($extension->get_price($plan_id)),

        );

        $this->maybe_hide_element($slug, $extension);

      } // end if;

    } // end foreach;

    return $prepared_plugins;

  } // end prepare_prepare_for_js;

  /**
   * Add elements to hide, if we need
   * @param  string       $slug      Slug of the theme
   * @param  WU_Extension $extension
   * @return
   */
  public function maybe_hide_element($slug, $extension) {

    foreach (array('display-author', 'display-version') as $check) {

      // Checks for global settings
      if (WU_Settings::get_setting("wu-ptm-$check") == false) {

        $element = "." . str_replace('display', 'theme', $check);

        if (!in_array($element, $this->elements_to_hide)) $this->elements_to_hide[] = $element;

        continue;

      }

      $check2 = str_replace('-', '_', $check);

      if ($extension && $extension->{"$check2"} == false) {

        $this->elements_to_hide[] = "[data-slug='$slug'] ." . str_replace('display', 'theme', $check);

      } // end if;

    } // end foreach;

  } // end maybe_hide_element;

  /**
   * Display the hide scripts
   */
  public function dump_hide_scripts() {

    $elements = implode(', ', $this->elements_to_hide);

    echo "<style>$elements {display: none !important;}</style>";

    echo "<script type='text/javascript'>(function($) {
      $(document).ready(function() {
        $(\"$elements\").remove();
      });
    })(jQuery);</script>";

  } // end dump_hide_scripts;

  /**
   * Remove some elements of the plugin Meta List
   * @param  array  $plugin_meta Array containing the plugin meta links
   * @param  string $plugin_file Plugin file
   * @return array               Modified plugin meta array
   */
  public function clean_meta_row($plugin_meta, $plugin_file) {

    /** Only run for Ultimo Users */
    if (!$this->should_apply_changes()) return $plugin_meta;

    $options = array('display_version', 'display_author', 'display_details');

    $extension = get_extension_by_name_in_array($this->get_slug_from_file($plugin_file), $this->extensions_plugin_list);

    if ($extension) {

      if (!$extension->display_other && !is_network_admin()) {
        $plugin_meta = array($plugin_meta[0], $plugin_meta[1], $plugin_meta[2]);
      }

      foreach ($options as $key => $option) {

        if (!$extension->{"$option"}) unset($plugin_meta[$key]);

      } // end foreach;

    } // end if;

    return $plugin_meta;

  } // end clean_meta_row;

  /**
   * Adds the categories columns to the plugins list table
   * @param [type] $columns [description]
   */
  function add_categories_column($columns) {
    $columns['wu_categories'] = __('Categories', 'wu-ptm');
    return $columns;
  }

  /**
   * Renders the custom column for the categories
   * @param  string $column_name
   * @param  string $plugin_file
   * @param  array  $plugin_data
   * @return
   */
  function render_categories_column($column_name, $plugin_file, $plugin_data) {

    if ('wu_categories' !== $column_name) return;

    $extension = wu_get_extension( $this->get_slug_from_file($plugin_file) );

    if ($extension) {

      /**
       * Display the fields with the default
       */
      $fields = array_merge($extension->meta_fields, array('title', 'description'));

      foreach ($fields as $field) {

        if (is_array($extension->{"$field"})) {
          continue;
        }

        echo "<input type='hidden' name='field_$field' value='". $extension->{"$field"} ."'>";

      }

      /**
       * Display the categories field
       */
      $term_list = wp_get_post_terms($extension->id, $extension->get_taxonomy(), array("fields" => "names"));

      if (!is_array($term_list)) $term_list = array();

      echo "<input type='hidden' name='field_". $extension->get_taxonomy() ."' value='". implode(', ', $term_list) ."'>";

      echo "<input type='hidden' name='field_extension_thumbnail-preview' value='". $extension->get_thumbnail() ."'>";

      echo "<input type='hidden' name='field_extension_thumbnail' value='". $extension->thumbnail ."'>";

      /**
       * Get The terms list
       */
      $term_list = wp_get_post_terms($extension->id, $extension->get_taxonomy(), array("fields" => "names"));

      if (!is_array($term_list) || empty($term_list)) {

        echo __('No categories', 'wu-ptm');

      } else {

        echo implode(', ', $term_list);

      }

    } else {

      echo __('No categories', 'wu-ptm');

      echo "<input type='hidden' name='field_extension_thumbnail-preview' value='". WP_Ultimo_PTM()->get_asset('extension-placeholder.png') ."'>";

      echo "<input type='hidden' name='field_extension_thumbnail' value=''>";

    }

  } // end render_categories_column;

  /**
   * Get the slug for the plugin from the filename
   * @param  string $file Full Path
   * @return atring       Slug used by WordPress
   */
  function get_slug_from_file($file) {

    if (($pos = strpos($file, "/")) !== FALSE) {
      $slug = strtolower(substr($file, 0, $pos));
    } else {
      $slug = $file;
    }

    return str_replace('.php', '', $slug);

  } // end get_slug_from_file;

  /**
   * Change Plugin Info
   * @param  array $all_plugins The list of plugins
   * @return array
   */
  function change_plugin_info($all_plugins) {

    /** Only run for Ultimo Users */
    if (!$this->should_apply_changes()) return $all_plugins;

    $multiple_slugs = array();

    foreach ($all_plugins as $file => $plugin) {

      $multiple_slugs[] = $this->get_slug_from_file($file);

    } // end foreach;

    $this->clean_deleted_extensions('plugin', $multiple_slugs);

    $extensions = wu_get_multiple_extensions($multiple_slugs);

    $index = 0;

    foreach ($all_plugins as $file => $plugin) {

      $slug = $multiple_slugs[$index];

      $plugin['slug'] = $slug;

      $all_plugins[$file] = $this->change_data($slug, $plugin, get_extension_by_name_in_array($slug, $extensions), is_network_admin());

      $index++;
    } // end foreach;

    $this->extensions_plugin_list = $extensions;

    return $all_plugins;

  } // end change_plugin_info;

  /**
   * Change Theme Infos
   * @param  array $all_themes All themes
   * @return array
   */
  public function change_theme_info($all_themes) {

    /** Only run for Ultimo Users */
    if (!$this->should_apply_changes()) return $all_themes;

    $multiple_slugs = array();
    foreach ($all_themes as $theme => $plugin) {
      $multiple_slugs[] = $this->get_slug_from_file($theme);
    }

    $this->clean_deleted_extensions('theme', $multiple_slugs);

    $extensions = wu_get_multiple_extensions($multiple_slugs);

    $index = 0;
    foreach ($all_themes as $theme => $plugin) {

      $slug = $multiple_slugs[$index];

      $data = wp_get_theme($slug);

      $all_themes[$theme] = $this->change_data($slug, $data, get_extension_by_name_in_array($slug, $extensions), is_network_admin());

      $index++;
    } // end foreach;

    return $all_themes;

  } // end change_theme_info;

  /**
   * Trash `wu_extension` posts related to deleted extensions.
   * 
   * @since 1.2.5
   *
   * @param string $type  Extension type: `theme` or `plugin`.
   * @param array  $slugs List of slugs of still existent extensions.
   */
  public function clean_deleted_extensions($type, $slugs) {

    // Switch to main blog
    switch_to_blog(get_current_site()->blog_id);

    $extensions = new WP_Query(array(
      'post_type'              => 'wu_extension',
      'posts_per_page'         => -1,
      'no_found_rows'          => true,
      'update_post_term_cache' => false,
      'meta_key'               => 'wpu_type',
      'meta_value'             => $type,
    ));

    if (!empty($extensions->posts)) {
      
      $extensions = wp_list_pluck( $extensions->posts, 'post_name', 'ID' );
      
      $deleted_extensions = array_diff( $extensions, $slugs );

      foreach ($deleted_extensions as $deleted_extension_id => $deleted_extension) {
        
        wp_delete_post($deleted_extension_id);

      } // end foreach;
  
    } // end if;

    restore_current_blog();

  } // end clean_deleted_extensions;

  /**
   * Change the info for the front end display of the users
   * @param  array $all_themes Array containing prepared themes for JS
   * @return array             Array with modified data
   */
  public function change_theme_info_js($all_themes) {

    /** Only run for Ultimo Users */
    if (!$this->should_apply_changes()) return $all_themes;

    foreach ($all_themes as $slug => &$theme) {

      $extension = wu_get_extension($slug);

      $author = $extension->author;

      if ($extension) {

        /**
         * We need to get the user plan
         * @since 1.1.0
         */
        $site = wu_get_current_site();

        $plan_id = $site ? $site->plan_id : 0;

        if ($extension->title)
          $theme['name'] = $extension->title;

        if ($extension->description)
          $theme['description'] = $extension->description;

        if ($extension->author) {
          $theme['author'] = $extension->author;
          $theme['authorAndUri'] = $extension->author;
        }

        if ($extension->thumbnail)
          $theme['screenshot'] = array($extension->get_thumbnail());

        $theme['tags'] = array($extension->get_categories_string());

        /**
         * @since  1.1.0 We add the extra actions =D
         */

        $theme['actions']['buy'] = wp_nonce_url('admin.php?page=wu-buy-extension&extension=' . $slug, 'buy_extension_' . $slug);

        /**
         * @since  1.1.0 Add the pricing things
         */
        $theme['isFree']     = $extension->is_free($plan_id);
        $theme['isUnlocked'] = $extension->is_unlocked();
		$theme['price']      = wu_format_currency($extension->get_price($plan_id));
		
		/**
		 * Check if is a parent theme
		 */
		if($theme['parent']) {
			/** 
			 * Search for the main theme name based on theme parent name.
			 */
			foreach ($all_themes as $theme_main) {

				if($theme_main['id'] == strtolower(str_replace(' ', '', $theme['parent'] ))){

					/**
					 * Set the main theme name in the parent info.
					 */
					$theme['parent'] = $theme_main['name'];
					
				}
			}
		}
      } // end if;

      $this->maybe_hide_element($slug, $extension);

	} // end foreach;
	


    return $all_themes;

  } // end change_theme_info_js;

  /**
   * Return the categories for the extensions
   * @return array Array containing the terms
   */
  public function get_categories($type = 'plugins') {

    switch_to_blog(1);

      $terms_to_return = array();

      $terms = get_terms(array(
        'taxonomy'   => "wu_extension_category_$type",
        'hide_empty' => true,
      ));

      foreach ($terms as $term) {

        $terms_to_return[ $term->name ] = ucfirst($term->name);

      } // end foreach;

    restore_current_blog();

    return $terms_to_return;

  } // end wu_get_categories;

  /**
   * Get the extension object and serve it via ajax
   * @return string
   */
  public function wu_get_extension() {

    // Get the post
    $extension = wu_get_extension($_POST['extension_id']);

    if (!$extension) $extension = new WU_Extension();

    echo json_encode($extension);

    exit;

  } // end wu_get_extension;

  /**
   * Handles the saving of the extension
   * @return
   */
  public function wu_save_extension() {

    check_ajax_referer('wu_extension_inline_edit');

    // Get the post
    $extension = wu_get_extension($_POST['extension_id']);

    if (!$extension) {

      $extension = new WU_Extension();

    } // end if;

    if (is_bool($extension->type)) {

      $extension->type = $_POST['extension_type'];

    } // end if;

    $cats_from_post = isset($_POST[ $extension->get_taxonomy() ]) ? $_POST[ $extension->get_taxonomy() ] : '';

    // Saves the Taxonomies
    $cats = !empty($cats_from_post) ? explode(',', trim($cats_from_post)) : array();

    $cats_to_add = array();

    foreach($cats as $cat) {

      $cat = trim($cat);

      if ($cat == '') continue;

      $term = term_exists($cat, $extension->get_taxonomy());

      if (!$term) $term = wp_insert_term($cat, $extension->get_taxonomy());

      $cats_to_add[] = (int) $term['term_id'];

    } // end foreach;

    // Adds the elements
    $extension->title       = $_POST['title'];
    $extension->author      = $_POST['author'];
    $extension->description = $_POST['description'];
    $extension->slug        = $_POST['extension_id'];
    $extension->thumbnail   = $_POST['extension_thumbnail'];

    $extension->display_author  = isset($_POST['display_author']);
    $extension->display_version = isset($_POST['display_version']);
    $extension->display_details = isset($_POST['display_details']);
    $extension->display_other   = isset($_POST['display_other']);

    $extension->type = $_POST['extension_type'];

    $extension->plugin_file = $_POST['extension_file'];

    $extension->cats = $cats_to_add;

    $extension_id = $extension->save();

    /**
     * Now things vary a bit depending on the type
     */
    $this->displays_table_network($extension);
    

    // Exits
    die;

  } // end wu_save_extension;


  public function displays_table_network($extension){

    /** Depending on the type, we get a certain type of table */
    if ($extension->type === 'theme') {

      $theme_file = $_POST['extension_file']?: $extension->plugin_file;

      $wp_list_table = _get_list_table('WP_MS_Themes_List_Table', array('screen' => 'themes-network'));

      $data = wp_get_theme($extension->slug);

      $slug = $this->get_slug_from_file($theme_file);

      $args = $this->change_data($slug, $data, $extension, true);

    } else if ($extension->type === 'plugin') {

      $plugin_file = $_POST['extension_file']?: $extension->plugin_file;

      $wp_list_table = _get_list_table('WP_Plugins_List_Table', array('screen' => 'plugins-network'));

      $data = get_plugin_data(WP_PLUGIN_DIR.'/'.$plugin_file);

      $slug = $this->get_slug_from_file($plugin_file);

      $args = array($plugin_file, $this->change_data($slug, $data, $extension, true));

    } // case plugin;

    else die(-1);

    // Displays
    $wp_list_table->single_row($args);
  }

  /**
   * Get the headers so we can change its value
   * @param  WP_Theme $original_wp_theme
   * @return
   */
  public function get_headers($original_wp_theme) {

    if (!is_object($original_wp_theme)) return $original_wp_theme;

    $reflector = new ReflectionClass($original_wp_theme);

    $headers = $reflector->getProperty('headers');

    $headers->setAccessible(true);

    return $headers->getValue($original_wp_theme);

  } // end get_headers;

  /**
   * Uses reflection to edit protected elements of WP Theme
   * @param  WP_Theme $original_wp_theme
   * @param  array    $modified_headers
   * @return
   */
  public function replace_headers($original_wp_theme, $modified_headers) {

    if (!is_object($original_wp_theme)) return $original_wp_theme;

    $reflector = new ReflectionClass($original_wp_theme);

    //get the attribute object
    $headers = $reflector->getProperty('headers');

    //set it as "accessible" and change it's value
    $headers->setAccessible(true);

    $headers->setValue($original_wp_theme, $modified_headers);

    return $original_wp_theme;

  } // end replace_headers;

  /**
   * Modifies the plugin or theme data, based on the context
   * @param  string  $slug        
   * @param  array   $plugin_data 
   * @param  object  $extension   
   * @param  boolean $network     
   * @return array              
   */
  public function change_data($slug, $data, $extension, $network = false) {

    if ($extension) {

      $data['slug'] = $slug;

      // Check for the type
	  if ($extension->type == 'theme') { $save = $data; $data = $this->get_headers($save); }


      /**
       * Changes for Network Admins
       */
      if ($network) {

        if ($extension->title)
          $data['Name'] = sprintf("%s (%s)", $extension->title, $data['Name']);

        if ($extension->description)
          $data['Description'] = sprintf("%s \n(\"%s\")", $extension->description, $data['Description']);

        if ($extension->author)
          $data['Author'] = sprintf("%s (%s)", $extension->author, $data['Author']);

      } // end if;

      /**
       * Changes for Normal Admins
       */
      else {

        if ($extension->title)
          $data['Name'] = $extension->title;

        if ($extension->description)
          $data['Description'] = $extension->description;

        if ($extension->author)
          $data['Author'] = $extension->author;

      } // end if;

      // Check for the type
      if ($extension->type == 'theme') {

        return $this->replace_headers($save, $data);

      }

    } // if extension;

    return $data;

  } // end change_data;

  /**
   * Creates the custom post types with taxonomies we are going to use
   * @return
   */
  public function create_custom_post_type() {

    $args                  = array(
      'label'              => 'WU Extension (Theme or Plugin)',
      'public'             => true,
      'public'             => false,
      'publicly_queryable' => false,
      'show_ui'            => false,
      'show_in_menu'       => false,
      'can_export'         => false,
      'taxonomies'         => array('wu_extension_category_plugin', 'wu_extension_category_theme'),
      'supports'           => array('title', 'thumbnail', 'editor'),
    );

    register_taxonomy('wu_extension_category_plugin', $this->post_type, array(
      'label'                 => __('Plugin Category', 'wu-ptm'),
      'hierarchical'          => false,
      'show_ui'               => false,
      'query_var'             => true,
      'show_in_quick_edit'    => true,
      'update_count_callback' => '_update_post_term_count',
      'rewrite'               => array('slug' => 'category-theme'),
    ));

    register_taxonomy('wu_extension_category_theme', $this->post_type, array(
      'label'                 => __('Theme Category', 'wu-ptm'),
      'hierarchical'          => false,
      'show_ui'               => false,
      'query_var'             => true,
      'show_in_quick_edit'    => true,
      'update_count_callback' => '_update_post_term_count',
      'rewrite'               => array('slug' => 'category-plugin'),
    ));
    
    register_post_type($this->post_type, $args);

  } // end create_custom_post_type;

  /**
   * Register and enqueue our scripts
   */
  public function enqueue_scripts() {

    // Register scripts
    wp_register_style('wu-ptm', $this->get_asset('wu-ptm.min.css', 'css'), array(), $this->version);

    // Register scripts
    wp_register_script('wu-ptm-inline-edit', $this->get_asset('inline-edit.min.js', 'js'), array('jquery'), $this->version);

    wp_localize_script('wu-ptm-inline-edit', 'inlineEditL10n', array(
      'error'      => __( 'Error while saving the changes.' ),
      'ntdeltitle' => __( 'Remove From Bulk Edit' ),
      'notitle'    => __( '(no title)' ),
      'saved'      => __( 'Changes saved.' ),
      'comma'      => trim( _x( ',', 'tag delimiter' ) ),
    ));

  } // end enqueue_scripts;

  /**
   * Replace the CORE theme script
   * @param  WP_Screen $screen
   * @return
   */
  public function replace_wp_core_scripts($screen) {

    global $wp_scripts;


    /**
     * Deregister default WordPress Themes and enqueue our own code, if necessary
     */
    if (in_array($screen->id, array('themes', 'plugins')) && $this->should_apply_changes()) {

      if (isset($wp_scripts->registered['theme'])) {

        $wp_scripts->registered['theme']->src = WP_Ultimo_PTM()->get_asset('plugins.min.js', 'js');

      } // end if;

      if (isset($wp_scripts->registered['customize-loader'])) {

        unset($wp_scripts->registered['customize-loader']);

      } // end if;

      $display_type = $screen->id == 'themes' ? 'theme' : WU_Settings::get_setting('wu-ptm-display-type', 'theme');

      wp_localize_script('theme', 'wu_extensions', array(
        'type'         => $screen->id == 'plugins' ? 'plugins' : 'themes',
        'display_type' => $display_type,
      ));

    } // end if;

  } // end replace_wp_core_scripts;

  /**
   * Add the inline edit link to one of the options
   * @param array $actions     Actions of the row
   */
  function add_inline_edit_link($actions) {

    $actions['inline hide-if-no-js'] = sprintf(
      '<a href="#" class="editinline" aria-label="%s">%s</a>',
      /* translators: %s: post title */
      esc_attr(__('Quick edit inline', 'wu_ptm')),
      sprintf(__( 'Edit Information', 'wu_ptm'))
    );

    return $actions;

  } // end add_inline_edit_link;

  /**
   * Render the inline edit form when necessary
   */
  function add_inline_form() {

    // Check for the screen
    $allowed = array('themes-network', 'plugins-network');

    $screen = get_current_screen();

    if (!in_array($screen->id, $allowed)) return;

    // Enqueue needed Scripts and Styles
    wp_enqueue_script('wu-ptm-inline-edit');

    wp_enqueue_style('wu-ptm');

    $post                    = get_default_post_to_edit($this->post_type);
    $post_type_object        = get_post_type_object($this->post_type);
    $taxonomy_names          = get_object_taxonomies($this->post_type);
    $hierarchical_taxonomies = array();
    $flat_taxonomies         = array();

    foreach ($taxonomy_names as $taxonomy_name) {

      $taxonomy = get_taxonomy( $taxonomy_name );

      $show_in_quick_edit = $taxonomy->show_in_quick_edit;

      /**
       * Filters whether the current taxonomy should be shown in the Quick Edit panel.
       *
       * @since 4.2.0
       *
       * @param bool   $show_in_quick_edit Whether to show the current taxonomy in Quick Edit.
       * @param string $taxonomy_name      Taxonomy name.
       * @param string $post_type          Post type of current Quick Edit post.
       */
      if (!apply_filters('quick_edit_show_taxonomy', $show_in_quick_edit, $taxonomy_name, $this->post_type)) {
        continue;
      }

      if ( $taxonomy->hierarchical )
        $hierarchical_taxonomies[] = $taxonomy;
      else
        $flat_taxonomies[] = $taxonomy;
    }

    // Render the template
    $this->render('inline-edit', array(
      'type'                    => $screen->base == 'plugins-network' ? __('Plugin') : __('Theme'),
      'type_slug'               => $screen->base == 'plugins-network' ? 'plugin' : 'theme',
      'flat_taxonomies'         => $flat_taxonomies,
      'hierarchical_taxonomies' => $hierarchical_taxonomies,
    ));

  } // end add_inline_form;

} // end WP_Ultimo_PTM;

/**
 * Initialize the Plugin
 */
add_action('plugins_loaded', 'wu_ptm_init', 1);

function WP_Ultimo_PTM() {

  return WP_Ultimo_PTM::get_instance();

}

function wu_ptm_init() {

  if (!class_exists('WP_Ultimo') || !WP_Ultimo()->check_before_run()) return; // We require WP Ultimo, baby

  // Set global
  $GLOBALS['WP_Ultimo_PTM'] = WP_Ultimo_PTM();

}

endif;