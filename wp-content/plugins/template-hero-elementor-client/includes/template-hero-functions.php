<?php
use Elementor\Plugin;
/**
 * @since  1.0.0
 *
 * @return void
 */
function wpkoi_elements_lite_elementor_init(){
    Plugin::instance()->elements_manager->add_category(
        'customs',
        [
            'title'  => 'Custom Templates',
            'icon' => 'font'
        ],
        1
    );
}
add_action( 'elementor/init','wpkoi_elements_lite_elementor_init' );

if ( !function_exists( 'jsonp_decode' ) ) {
    /**
     * Contains Supporting Functions for plugin
     * @since  1.0.0
     */
    function jsonp_decode( $jsonp, $assoc = false ) { // PHP 5.3 adds depth as third parameter to json_decode
        if( $jsonp[0] !== '[' && $jsonp[0] !== '{' ) { // we have JSONP
        $jsonp = substr( $jsonp, strpos( $jsonp, '(' ) );
        }
        return json_decode( trim( $jsonp,'();' ), $assoc );
    }
}

if ( !function_exists('template_hero_deslash') ) {
    /**
     * Deslashed double slashes
     * @since  1.1.0
     * @param [string] $content
     * @return void
     */
    function template_hero_deslash( $content ) {
        // Note: \\\ inside a regex denotes a single backslash.
    
        /*
        * Replace one or more backslashes followed by a single quote with
        * a single quote.
        */
        $content = preg_replace( "/\\\+'/", "'", $content );
    
        /*
        * Replace one or more backslashes followed by a double quote with
        * a double quote.
        */
        $content = preg_replace( '/\\\+"/', '"', $content );
    
        // Replace one or more backslashes with one backslash.
        $content = preg_replace( '/\\\+/', '\\', $content );
    
        return $content;
    }
}