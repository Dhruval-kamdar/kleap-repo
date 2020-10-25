<?php 
namespace ElementorExtensions\Classes;

if ( ! defined( 'ABSPATH' ) ) exit;

Class Cpt_Generator{

    private static $instance = null;

    /*@ CPT generation code */
    public static function elementorExtensionCptGeneration($custom_args){
        $custom_label_args = $custom_args['labels'];
        $user_args = $custom_args['args'];

        $singular = $custom_label_args['singular'];
        $plural = $custom_label_args['plural'];

        $slug = str_replace(' ', '_', strtolower($singular));
        if(!empty($user_args['slug'])):
            $slug = $user_args['slug'];
        endif;
        
        $labels = array(
            'name'               => _x($plural, 'post type general name', 'elementor-extensions'),
            'singular_name'      => _x($singular, 'post type singular name', 'elementor-extensions'),
            'menu_name'          => _x($plural, 'admin menu', 'elementor-extensions'),
            'name_admin_bar'     => _x($singular, 'add new on admin bar', 'elementor-extensions'),
            'add_new'            => _x('Add New', strtolower($singular), 'elementor-extensions'),
            'add_new_item'       => __('Add New '.$singular, 'elementor-extensions'),
            'new_item'           => __('New '.$singular, 'elementor-extensions'),
            'edit_item'          => __('Edit '.$singular, 'elementor-extensions'),
            'view_item'          => __('View '.$singular, 'elementor-extensions'),
            'all_items'          => __($plural, 'elementor-extensions'),
            'search_items'       => __('Search '.$plural, 'elementor-extensions'),
            'parent_item_colon'  => __('Parent '.$plural.':', 'elementor-extensions'),
            'not_found'          => __('No '.strtolower($plural).' found.', 'elementor-extensions'),
            'not_found_in_trash' => __('No '.strtolower($plural).' found in Trash.', 'elementor-extensions')
        );

        $args = array(
            'labels'             => $labels,
            'description'        => __('Description.', 'elementor-extensions'),
            'public'             => true,
            'publicly_queryable' => true,
            'show_ui'            => true,
            'show_in_nav_menus'  => true,
            'query_var'          => true,
            'rewrite'            => array( 'slug' => $slug ),
            'capability_type'    => 'post',
            'has_archive'        => true,
            'hierarchical'       => true,
            'menu_position'      => null,
            'menu_icon'			 => 'dashicons-admin-post',
            'exclude_from_search' => false,
            'can_export' => true,
            'map_meta_cap' => true
        );

        $merged_args = array_merge($args,$user_args);

        register_post_type($slug, $merged_args);
    }

    public static function elementorExtensionTaxonomyGeneration($custom_args){

        $singular = $custom_args['singular'];
        $plural = $custom_args['plural'];

        $taxonomy_name = $custom_args['taxonomy_name'];
        $post_type     = $custom_args['post_type'];

        
        $labels = array(
            'name'              => _x( $plural, 'taxonomy general name', 'elementor-extensions' ),
            'singular_name'     => _x( $singular, 'taxonomy singular name', 'elementor-extensions' ),
            'search_items'      => __( 'Search '.$plural, 'elementor-extensions' ),
            'all_items'         => __( 'All '.$plural, 'elementor-extensions' ),
            'parent_item'       => __( 'Parent '.$singular, 'elementor-extensions' ),
            'parent_item_colon' => __( 'Parent ' . $singular . ':', 'elementor-extensions' ),
            'edit_item'         => __( 'Edit '.$singular, 'elementor-extensions' ),
            'update_item'       => __( 'Update '.$singular, 'elementor-extensions' ),
            'add_new_item'      => __( 'Add New '.$singular, 'elementor-extensions' ),
            'new_item_name'     => __( 'New '.$singular.' Name', 'elementor-extensions' ),
            'menu_name'         => __( $plural, 'elementor-extensions' ),
        );
    
        $args = array(
            'hierarchical'      => true,
            'labels'            => $labels,
            'show_ui'           => true,
            'show_admin_column' => true,
            'query_var'         => true             
        );

        if(!empty($custom_args['args'])):
            $args = array_merge($args,$custom_args['args']);
        endif;
    
        register_taxonomy( $taxonomy_name, $post_type, $args );
    }

    public static function get_instance( $shortcodes = array() ) {

        // If the single instance hasn't been set, set it now.
        if ( null == self::$instance ) {
            self::$instance = new self( $shortcodes );
        }
        return self::$instance;
    }
}
