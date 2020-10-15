<?php
/**
 * Woo Left sidebar check.
 *
 * @package enova
 */

$enova_sidebar_pos = wt_get_option('woo_sidebar_position');

// If left sidebar active
if( 'left' === $enova_sidebar_pos ){
    if( is_active_sidebar( 'woo-left-sidebar' )){
        get_sidebar( 'woo-left' );
    }
}

// Sidebar position check
if( ! is_checkout() ){
    if ( 'right' === $enova_sidebar_pos ) {
        if ( ! is_active_sidebar( 'woo-right-sidebar' ) ) {
            echo '<div class="col-lg-12 content-area" id="primary">';
        }else{
            echo '<div class="col-lg-9 content-area" id="primary">';
        }
    }elseif('left' === $enova_sidebar_pos){
        if ( ! is_active_sidebar( 'woo-left-sidebar' ) ) {
            echo '<div class="col-lg-12 content-area" id="primary">';
        }else{
            echo '<div class="col-lg-9 content-area" id="primary">';
        }
    }else {
        echo '<div class="col-lg-12 content-area" id="primary">';
    }
}else{
    echo '<div class="col-lg-12 content-area" id="primary">';
}
