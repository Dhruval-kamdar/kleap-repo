<?php


/**
 * Define the demo import files (remote files).
 *
 * To define imports, you just have to add the following code structure,
 * with your own values to your theme (using the 'merlin_import_files' filter).
 */

add_filter( 'merlin_import_files', 'droow_merlin_import_files' );
function droow_merlin_import_files()
{
    return array(
        array(
            'import_file_name'           => 'Demo Import',
            'import_file_url'            => 'http://dsngrid.com/plugins/droow/droow-content.xml',
            'import_widget_file_url'     => 'http://dsngrid.com/plugins/droow/droow-widgets.wie',
            'import_customizer_file_url' => 'http://dsngrid.com/plugins/droow/droow-export.dat',
            'preview_url'                => 'https://www.dsngrid.com/droow/',
        ),
    );
}

/**
 * Setup Elementor
 */
add_filter( 'merlin_after_all_import', 'droow_merlin_setup_elementor' );
add_filter( 'pt-ocdi/after_import', 'droow_merlin_setup_elementor' );
function droow_merlin_setup_elementor()
{

    $cpt_support = get_option( 'elementor_cpt_support' );

    // Update CPT Support
    if ( !$cpt_support ) {
        update_option( 'elementor_cpt_support', array( 'page', 'post', droow_project_slug() ) );
    } elseif ( !in_array( droow_project_slug(), $cpt_support ) ) {
        $cpt_support[] = droow_project_slug();
        update_option( 'elementor_cpt_support', $cpt_support );

    }

    // Update Default space between widgets
    update_option( 'elementor_space_between_widgets', '80' );

    // Update Content width
    update_option( 'elementor_container_width', '1140' );

    // Update Breakpoints
    update_option( 'elementor_viewport_lg', '992' );
    update_option( 'elementor_viewport_md', '768' );

    // Update Page title selector
    update_option( 'elementor_page_title_selector', 'header .dsn-title-header ,.fullscreen-slider .slider-item .content-inner .content,.headefr-fexid .project-title .title-text-header .title-text-header-inner' );


    // Update CSS Print Method
    update_option( 'elementor_css_print_method', 'internal' );

}

/**
 * Setup Menu
 */
add_filter( 'merlin_after_all_import', 'droow_merlin_setup_menu' );
add_filter( 'pt-ocdi/after_import', 'droow_merlin_setup_menu' );
function droow_merlin_setup_menu()
{

    $top_menu = get_term_by( 'name', 'Droow Menu', 'nav_menu' );

    set_theme_mod(
        'nav_menu_locations', array(
            'primary' => $top_menu->term_id,
        )
    );
}

/**
 * Setup Front Pages
 */
add_filter( 'merlin_after_all_import', 'droow_merlin_setup_front_blog_pages' );
add_filter( 'pt-ocdi/after_import', 'droow_merlin_setup_front_blog_pages' );
function droow_merlin_setup_front_blog_pages()
{

    $front_page_id = get_page_by_title( 'One Page 1' );

    update_option( 'show_on_front', 'page' );
    update_option( 'page_on_front', $front_page_id->ID );

}

/**
 * Setup Date Format
 */
add_filter( 'merlin_after_all_import', 'droow_merlin_setup_date_format' );
add_filter( 'pt-ocdi/after_import', 'droow_merlin_setup_date_format' );
function droow_merlin_setup_date_format()
{
    update_option( 'date_format', 'd M Y' );
}


/**
 * Setup permalinks format
 * Needed to make AJAX transitions work
 */
add_filter( 'merlin_after_all_import', 'droow_merlin_setup_permalinks' );
add_filter( 'pt-ocdi/after_import', 'droow_merlin_setup_permalinks' );
function droow_merlin_setup_permalinks()
{

    global $wp_rewrite;

    // Set permalink structure
    $wp_rewrite->set_permalink_structure( '/%postname%/' );

    // Recreate rewrite rules
    $wp_rewrite->rewrite_rules();
    $wp_rewrite->wp_rewrite_rules();
    $wp_rewrite->flush_rules();

}

/**
 * Setup Intuitive Custom Post Order
 * Define sortable post types
 */
add_filter( 'merlin_after_all_import', 'droow_merlin_setup_sortable_post' );
add_filter( 'pt-ocdi/after_import', 'droow_merlin_setup_sortable_post' );
function droow_merlin_setup_sortable_post()
{

    $hicpo_options = get_option( 'hicpo_options' );

    if ( !$hicpo_options ) {
        return;
    }

    $hicpo_objects = $hicpo_options[ 'objects' ];

    if ( !$hicpo_objects ) {
        $hicpo_options[ 'objects' ] = array( droow_project_slug() );
        update_option( 'hicpo_options', $hicpo_options );

    } elseif ( !in_array( droow_project_slug(), $hicpo_objects ) ) {
        $hicpo_objects[] = droow_project_slug();
        $hicpo_options[ 'objects' ] = $hicpo_objects;
        update_option( 'hicpo_options', $hicpo_options );
    };

}
