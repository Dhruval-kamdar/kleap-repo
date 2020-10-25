<?php
namespace WeDevs\ERP_PRO\Install;

// don't call the file directly
if ( ! defined('ABSPATH') ) {
    exit;
}

/**
 * ERP Pro Installer file
 */

class Installer {

    /**
     * Load automatically when class initiate
     *
     * @since 0.0.1
     */
    public function do_install() {
        // add your required files here, this method will be called during
    }

    /**
     * Maybe Activate modules
     *
     * For the first time activation after installation,
     * activate all pro modules.
     *
     * @since 0.0.1
     *
     * @return void
     * */
    public function maybe_activate_modules() {
        global $wpdb;

        $modules = ! empty( wp_erp_pro()->module ) ? wp_erp_pro()->module : \WeDevs\ERP_PRO\Module::init();

        $has_installed = $wpdb->get_var( $wpdb->prepare(
            "select option_id from {$wpdb->options} where option_name = %s",
            $modules::ACTIVE_MODULES_DB_KEY
        ) );

        if ( $has_installed ) {
            return;
        }

        // install all available modules
        $modules->activate_modules( $modules->get_available_modules( false ) );
    }
}

