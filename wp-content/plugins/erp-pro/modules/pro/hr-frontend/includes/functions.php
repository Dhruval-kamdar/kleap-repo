<?php
/**
 * Get erp Dashboard slug
 *
 * @since 1.0.0
 * @return string
 */
function get_erp_dashboard_slug() {
    $slug = get_option( 'hr_frontend_slug' );
    if ( ! $slug ) {
        $slug = 'wp-erp';
    }

    return sanitize_title( $slug );
}

/**
 * Get erp dashboard url
 *
 * @since 1.0.0
 * @return string
 */
function get_erp_dashboard_url() {
    $site_url       = get_site_url();
    $dashboard_slug = ltrim( get_erp_dashboard_slug(), '/' );

    return $url = trailingslashit( $site_url ) . $dashboard_slug;
}

/**
 * Get dashboard title
 * 
 * @return string
 */
function get_erp_dashboard_title() {
    $dashboard_title = get_option( 'hr_frontend_dashboard_title' );

    return $dashboard_title;
}

/**
 * Dashboard Logo
 *
 * @return void
 */
function get_erp_dashboard_logo() {
    $logo = get_option( 'hr_frontend_logo' );
    if ( $logo ) {
        return wp_get_attachment_url( $logo );
    }

    return ERP_DASHBOARD_ASSETS . '/images/wperp-logo.png';
}

/**
 *  Visit erp dashboard url
 *
 * @since 1.0.0
 * @return string
 */
function visit_erp_dashboard( $wp_admin_bar ) {
    $args = array(
        'id'     => 'hr-frontend',
        'title'  => 'HR Frontend',
        'href'   => get_erp_dashboard_url(),
        'parent' => 'site-name-default'
    );

    $wp_admin_bar->add_node( $args );
}

add_action( 'admin_bar_menu', 'visit_erp_dashboard', 999 );

/**
 *  Get attendance version to check backward compitability
 *
 * @since 1.0.0
 * @return string
 */
function get_attendance_version() {
    $active_plugins = get_option( 'active_plugins' );
    if ( in_array( 'erp-attendance/erp-attendance.php', $active_plugins ) ) {
        $att_data                   = get_plugin_data( WP_PLUGIN_DIR . '/erp-attendance/erp-attendance.php' );
        $att_data['is_new_version'] = version_compare( '1.2.0', $att_data['Version'], '<' );
        return apply_filters( 'erp_attendance_meta_info', $att_data );
    }
}

/**
 * Redirect user to hr frontend after login
 *
 * @since 1.0.0
 * @return string
 */
function redirect_to_frontend( $redirect_to, $request , $user ) {

    $is_redirect      = get_option( 'hr_frontend_redirect', 'no' );
    $hr_frontend_slug = get_option( 'hr_frontend_slug', 'wp-erp' );
    $frontend_url     = site_url() . '/' . $hr_frontend_slug;
    $hr_admin_url     = admin_url( 'admin.php?page=erp-hr' );
    $crm_admin_url    = admin_url( 'admin.php?page=erp-crm' );

    if ( isset( $user->roles ) && is_array( $user->roles ) ) {
        if ( in_array( 'employee', $user->roles ) &&
                !in_array( 'erp_hr_manager ', $user->roles ) &&
                    !in_array( 'erp_crm_manager', $user->roles ) &&
                        !in_array( 'erp_crm_agent', $user->roles ) &&
                            !in_array( 'erp_ac_manager', $user->roles ) &&
                                !in_array( 'administrator', $user->roles ) ) {
            if ( $is_redirect == 'yes' && is_plugin_active('erp-hr-frontend/erp-hr-frontend.php') ) {
                $redirect_to = $frontend_url;
            }
        }
    }
    return $redirect_to;
}
add_filter( 'login_redirect', 'redirect_to_frontend', 10, 3 );