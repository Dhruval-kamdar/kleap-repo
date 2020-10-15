<?php
/**
 * Woo Right sidebar check.
 *
 * @package enova
 */

$enova_sidebar_pos = wt_get_option('woo_sidebar_position');

if( 'right' === $enova_sidebar_pos ){
    get_sidebar( 'woo-right' );
}else{
    get_sidebar( 'woo-right' );
}
