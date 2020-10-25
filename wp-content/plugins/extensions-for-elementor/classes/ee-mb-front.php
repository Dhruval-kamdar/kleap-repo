<?php
namespace ElementorExtensions\Classes;

if ( ! defined( 'ABSPATH' ) ) {
	exit; 
}

class EE_MB_Front {

	private static $_instance;

    public static function instance() {
        if ( is_null( self::$_instance ) ) {
            self::$_instance = new self();
        }

        return self::$_instance;
    }

	public function eeMbaddCookieMessage(){
		$cookie = stripslashes_deep(get_option('ee_mb_cookie_message'));
		require_once(ELEMENTOR_EXTENSIONS_PATH . 'admin/views/front/cookie.php');
	}

	public function __construct(){
		add_action( 'wp_head', array( $this, 'eeMbaddCookieMessage' ), 1000 );
	}
}
