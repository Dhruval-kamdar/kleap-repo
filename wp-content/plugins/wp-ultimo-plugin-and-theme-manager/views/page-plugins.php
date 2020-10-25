<?php

$plugins = WP_Ultimo_PTM()->prepare_plugins_for_js();

wp_enqueue_style('wu-ptm');

wp_reset_vars( array( 'theme', 'search' ) );

wp_localize_script( 'theme', '_wpThemeSettings', array(
    'themes'   => $plugins,
    'settings' => array(
        'canInstall'    => ( ! is_multisite() && current_user_can( 'install_themes' ) ),
        'installURI'    => ( ! is_multisite() && current_user_can( 'install_themes' ) ) ? admin_url( 'theme-install.php' ) : null,
        'confirmDelete' => __( "Are you sure you want to delete this theme?\n\nClick 'Cancel' to go back, 'OK' to confirm the delete." ),
        'adminUrl'      => parse_url( admin_url(), PHP_URL_PATH ),
    ),
    'pluginl10n' => array(
        'addNew'            => __( 'Add New Plugin' ),
        'search'            => __( 'Search available plugins' ),
        'searchPlaceholder' => __( 'Search available plugins...' ), // placeholder (no ellipsis)
        'themesFound'       => __( 'Number of Plugins found: %d' ),
        'noThemesFound'     => __( 'No plugins found. Try a different search.' ),
    ),
) );

// set_current_screen('themes');

add_thickbox();

wp_enqueue_script('theme');
wp_enqueue_style('theme');
wp_enqueue_script( 'updates' );
wp_enqueue_script( 'customize-loader' );

$current_theme_actions = array();

?>
<div id="wpbody" role="main">

  <div id="wpbody-content">
  
  <div class="wrap">
    
    <h1><?php esc_html_e( 'Plugins' ); ?>
        <span class="title-count theme-count"><?php echo count( $plugins ); ?></span>
    </h1>

    <div class="wp-filter">
      <ul class="filter-links">

        <li>
            <a href="#" class="current" data-category=""><?php _e('All Plugins'); ?></a>
        </li>

        <li>
            <a href="#" class="" data-category="active"><?php _e('Active'); ?></a>
        </li>

        <li class="selector-inactive">
            <a href="#" data-category="inactive"><?php _e('Inactive'); ?></a>
        </li>

        <?php foreach (WP_Ultimo_PTM::get_categories($type_slug) as $cat_slug => $cat) { ?>

          <li>
              <a href="?s=<?php echo $cat_slug; ?>" class="" data-category="<?php echo $cat_slug; ?>"><?php echo $cat; ?></a>
          </li>

        <?php } ?>

      </ul>
    </div>

  <?php if ( isset($_GET['activate']) ) : ?>

    <div id="message2" class="updated notice is-dismissible">
      <p>
        <?php _e('Plugin activated successfully!', 'wu-ptm' ); ?>
      </p>
    </div>

  <?php elseif ( isset($_GET['deactivate']) ) : ?>

    <div id="message2" class="updated notice is-dismissible">
      <p>
        <?php _e('Plugin deactivated successfully!', 'wu-ptm' ); ?>
      </p>
    </div>

  <?php endif; ?>

  <?php
  /**
   * Display as Plugin
   */
  if (WU_Settings::get_setting('wu-ptm-display-type', 'theme') === 'theme') {

    WP_Ultimo_PTM()->render('templates/theme-template', compact('plugins'));

    WP_Ultimo_PTM()->render('templates/details-template', compact('current_theme_actions'));

  } else {

    WP_Ultimo_PTM()->render('templates/plugin-template', compact('plugins'));

  } // end if;

  ?>

  <?php if ($display_type == 'plugin'): ?>
  </div>
  <?php endif; ?>

  <?php
  
  wp_print_request_filesystem_credentials_modal();
  wp_print_admin_notice_templates();
  wp_print_update_row_templates();

  wp_localize_script( 'updates', '_wpUpdatesItemCounts', array(
      'totals'  => wp_get_update_data(),
  ) );

  require( ABSPATH . 'wp-admin/admin-footer.php' );

  exit;