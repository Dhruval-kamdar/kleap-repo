<?php

/**
 * Plugin Name: Droow Functionality Plugin & Gutenberg Blocks
 * Plugin URI: https://themeforest.net/user/design_grid
 * Description: Gutenberg editor blocks  , Shortcodes and Custom Post Type for Droow Wordpress Theme
 * Version: 1.2.6
 * Author: Design Grid
 * Author URI: https://themeforest.net/user/design_grid
 * License:
 */

if ( !defined( 'ABSPATH' ) ) {
    die( '-1' );
}

define( 'DROOW__PLUGIN_BASENAME' , plugin_basename( __FILE__ ) );
define( 'DROOW__PLUGIN_DIR' , plugin_dir_path( __FILE__ ) );
define( 'DROOW__PLUGIN_DIR_URL' , plugin_dir_url( __FILE__ ) );
define( 'DROOW_BLOCK_BOXES' , 'dsn-block-boxes' );


class DroowHelperPlugin
{

    public function __construct()
    {

        $this->include_helper_files();

    }

    public function include_files( $files , $suffix = '' )
    {
        foreach ( $files as $file ) {
            $filepath = DROOW__PLUGIN_DIR . $suffix . $file . '.php';
            if ( !file_exists( $filepath ) ) :
                trigger_error( sprintf( esc_html__( 'Error locating %s for inclusion' , 'droow' ) , $file ) , E_USER_ERROR );
            endif;
            require_once $filepath;
        }
        unset( $file , $filepath );
    }


    public function include_helper_files()
    {
        $files = array(
            'droow-function-helper' ,
            'droow-admin-post' ,
            'views/shortcode/DroowShortCode' ,
            'droow-shortcut-code' ,
            'droow-required-plugins' ,

        );
        $this->include_files( $files , 'inc/' );


        /**
         *
         *  page Tempelete
         *
         */
//        $classes = array( 'MiaoPageTemplete');
//        $this->include_files( $classes );


    }


}


function DroowDev()
{
    return new DroowHelperPlugin();
}

add_action( 'droow_developer' , function ( $array = array() ) {
    DroowDev();

    foreach ( $array as $value ):
        droow_resources( $value );
    endforeach;
} );

