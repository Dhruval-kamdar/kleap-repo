<?php
/**
 * Template_Hero_Elementor  Options
 *
 * Displays the Template_Hero_Elementor  Options.
 *
 * @author   J Hanlon
 * @category Admin
 * @package  Template_Hero_Elementor Options /Plugin Options
 * @version  1.0.0
 */
namespace TemplateHero\Plugin_Client;
use Elementor\TemplateLibrary\Template_Hero_Remote_Source as Api;
use Elementor\Plugin;
use Elementor\TemplateLibrary\Source_Local;


if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

/**
 * Class Template_Hero_Elementor_opions
 * @since 1.0.0
 */
class Options {
    public $page_tab;

    public static $network_wide;
    /**
     * Hook in tabs.
     * @since 1.0.0
     */
    public function __construct () {
        $network_wide         = !empty( get_site_option('template_hero_elementor_networkwide') ) ? get_site_option('template_hero_elementor_networkwide') : 'no';
        $this::$network_wide  = apply_filters( "template_hero_set_network_wide", $network_wide );
        $this->page_tab = isset( $_GET['tab'] ) ? $_GET['tab'] : 'advance';
        if( is_multisite() && is_network_admin() ) {
            $this->page_tab  = isset( $_GET['tab'] ) ? $_GET['tab'] : 'mu_general';
        }
        
    }

    /**
     * Adds admin notices
     * @since 1.0.0
     * @return void
     */
    public function template_hero_elementor_admin_notices() {
        $status = get_option( '_template_hero_license_key_status', 'not' );
        if( $status != 'active' && is_multisite() ) {
            switch_to_blog( 1 );
            $status = get_option( '_template_hero_license_key_status', 'not' );
            restore_current_blog();
        }
        if ( $status != 'active' ) {
            $class = 'notice notice-error is-dismissible';
            $message = __( 'Please activate your license to get feature updates, premium support and unlimited access to the elementor template hero library.', 'template-hero-elementor' );
            printf( '<div class="%1$s"><p>%2$s</p></div>', esc_attr( $class ), esc_html( $message ) );
        }
        $screen = get_current_screen();
        if( $screen->base != 'template-hero-elementor-options' && $screen->base != 'settings_page_template-hero-elementor-options' && $screen->base != 'settings_page_template-hero-elementor-options-network' && $screen->base != 'toplevel_page_template-hero-elementor-options-network' ) {
            
            return;
        }
       
        if( isset( $_POST['template_hero_elementor_settings_submit'] ) || ( isset( $_GET['settings-updated'] ) && $_GET['settings-updated'] == 'true' ) ) {
            $class = 'notice notice-success is-dismissible';
            $message = __( 'Settings Saved', 'template-hero-elementor' );
            printf( '<div class="%1$s"><p>%2$s</p></div>', esc_attr( $class ), esc_html( $message ) );
        } elseif( isset( $_POST['template_hero_elementor_settings_submit'] ) || ( isset( $_GET['token-updated'] ) && $_GET['token-updated'] == 'true' ) ) {
            $class = 'notice notice-success is-dismissible';
            $message = __( 'Settings Saved , Token created And Library Activated', 'template-hero-elementor' );
            printf( '<div class="%1$s"><p>%2$s</p></div>', esc_attr( $class ), esc_html( $message ) );
        } elseif( isset( $_POST['template_hero_elementor_settings_submit'] ) || ( isset( $_GET['token-updated'] ) && $_GET['token-updated'] == 'false' ) ) {
            $class = 'notice notice-error is-dismissible';
            $message = __( 'Token Not Created And Library Not Activated', 'template-hero-elementor' );
            printf( '<div class="%1$s"><p>%2$s</p></div>', esc_attr( $class ), esc_html( $message ) );
        } elseif( isset( $_POST['template_hero_elementor_settings_submit'] ) || ( isset( $_GET['token-already-created'] ) && $_GET['token-already-created'] == 'true' ) ) {
            $class = 'notice notice-error is-dismissible';
            $message = __( 'Token Already Created And Library Already Activated', 'template-hero-elementor' );
            printf( '<div class="%1$s"><p>%2$s</p></div>', esc_attr( $class ), esc_html( $message ) );
        } elseif( isset( $_POST['template_hero_see_log'] ) || ( isset( $_GET['logs-settings-updated'] ) && $_GET['logs-settings-updated'] == 'true' ) ) {
            $class = 'notice notice-success is-dismissible';
            $message = __( 'Logs Settings Saved', 'template-hero-elementor' );
            printf( '<div class="%1$s"><p>%2$s</p></div>', esc_attr( $class ), esc_html( $message ) );
        } elseif( isset( $_POST['template_hero_see_log'] ) || ( isset( $_GET['logs-settings-updated'] ) && $_GET['logs-settings-updated'] == 'false' ) ) {
            $class = 'notice notice-error is-dismissible';
            $message = __( 'Logs Settings Not Saved', 'template-hero-elementor' );
            printf( '<div class="%1$s"><p>%2$s</p></div>', esc_attr( $class ), esc_html( $message ) );
        } elseif( isset( $_POST['template_hero_elementor_advance_settings_submit'] ) || ( isset( $_GET['advance-settings-updated'] ) && $_GET['advance-settings-updated'] == 'true' ) ) {
            $class = 'notice notice-success is-dismissible';
            $message = __( 'Advance Settings Saved', 'template-hero-elementor' );
            printf( '<div class="%1$s"><p>%2$s</p></div>', esc_attr( $class ), esc_html( $message ) );
        } elseif( isset( $_POST['template_hero_elementor_advance_settings_submit'] ) || ( isset( $_GET['advance-settings-updated'] ) && $_GET['advance-settings-updated'] == 'false' ) ) {
            $class = 'notice notice-error is-dismissible';
            $message = __( 'Advance Settings Not Saved', 'template-hero-elementor' );
            printf( '<div class="%1$s"><p>%2$s</p></div>', esc_attr( $class ), esc_html( $message ) );
        } elseif( ( isset( $_GET['nonce-verified'] ) && $_GET['nonce-verified'] == 'false' ) ) {
            $class = 'notice notice-error is-dismissible';
            $message = __( 'Security Issues', 'template-hero-elementor' );
            printf( '<div class="%1$s"><p>%2$s</p></div>', esc_attr( $class ), esc_html( $message ) );
        } elseif( ( isset( $_GET['library-created'] ) && $_GET['library-created'] == 'true' ) ) {
            $class = 'notice notice-success is-dismissible';
            $message = __( 'Settings Saved & Library Added', 'template-hero-elementor' );
            printf( '<div class="%1$s"><p>%2$s</p></div>', esc_attr( $class ), esc_html( $message ) );
        } elseif( ( isset( $_GET['library-created'] ) && $_GET['library-created'] == 'exits' ) ) {
            $class = 'notice notice-success is-dismissible';
            $message = __( 'Settings Saved & Library Already Exists', 'template-hero-elementor' );
            printf( '<div class="%1$s"><p>%2$s</p></div>', esc_attr( $class ), esc_html( $message ) );
        } elseif( ( isset( $_GET['library-created'] ) && $_GET['library-created'] == 'false' ) ) {
            $class = 'notice notice-error is-dismissible';
            $message = __( 'Settings Updated & Library Can Not Be Created', 'template-hero-elementor' );
            printf( '<div class="%1$s"><p>%2$s</p></div>', esc_attr( $class ), esc_html( $message ) );
        }
    }

    /**
     * Advance settings save
     * @since 1.0.0
     * @return void
     */
    public function template_hero_elementor_admin_advance_settings_save() {
        $uploads  = 'false';
        $template_hero_elementor_option  = get_option( 'template_hero_elementor_advance_options', array() );
        $nonce    = isset( $_POST['template_hero_elementor_admin_advance_settings_action'] ) ? $_POST['template_hero_elementor_admin_advance_settings_action'] : '';
        $action   = 'template_hero_elementor_admin_advance_settings_action';
        if( isset( $_POST['template_hero_elementor_advance_settings_submit'] ) && wp_verify_nonce( $nonce, $action ) ) {
            $template_hero_elementor_options    = array();

            
            $del_data            = isset( $_POST['template_hero_elementor_delete_data'] ) ? $_POST['template_hero_elementor_delete_data'] : '';
            $allowed_extensions  = isset( $_POST['template_hero_elementor_allowed_extensions'] ) ? $_POST['template_hero_elementor_allowed_extensions'] : '';
            $lib_creation        = isset( $_POST['template_hero_elementor_admin_create_lib'] ) ? $_POST['template_hero_elementor_admin_create_lib'] : '';
           
            $template_hero_elementor_options['template_hero_elementor_admin_create_lib']  =  sanitize_text_field( $lib_creation );
            $template_hero_elementor_options['template_hero_elementor_delete_data']         =  sanitize_text_field( $del_data );
            $template_hero_elementor_options['template_hero_elementor_allowed_extensions']  =  sanitize_text_field( $allowed_extensions );
            $template_hero_elementor_options = apply_filters( "template_hero_update_advance_options", $template_hero_elementor_options );
            $tab_title  = isset( $_POST['th_cl_tab_title'] ) ? $_POST['th_cl_tab_title'] : 'Custom Templates';
            $ad_title   = isset( $_POST['th_cl_admin_menu_title'] ) ? $_POST['th_cl_admin_menu_title'] : 'Template Hero Client';
            $n_title    = isset( $_POST['th_cl_network_menu_title'] ) ? $_POST['th_cl_network_menu_title'] : 'Template Hero Client';
           
            update_site_option( 'th_cl_admin_menu_title', $ad_title );
            update_site_option( 'th_cl_tab_title', $tab_title );
            update_site_option( 'th_cl_network_menu_title', $n_title );
            update_option( 'template_hero_elementor_advance_options', $template_hero_elementor_options );
            $uploads  = 'true';
        } else {
            wp_safe_redirect( add_query_arg( 'nonce-verified', $uploads, $_POST['_wp_http_referer'] ) );
            exit;
        }
        wp_safe_redirect( add_query_arg( 'advance-settings-updated', $uploads, $_POST['_wp_http_referer'] ) );
        exit;
    }
    
    /**
     * Ajax function to sync library
     * @since 1.0.0
     */
    public function template_hero_sync_library() {
        check_ajax_referer( 'elementor_reset_library', '_nonce' );
        $data = Api::get_library_data( true );

		if ( empty( $data ) ) {
			echo json_encode([
                'success' => false,
                'message' => __( 'Library Could Not Be Synced.', 'template-hero-elementor' )
            ]);
		} else {
            echo json_encode([
                'success' => true,
                'message' => __( 'Synced Successfully.', 'template-hero-elementor' )
            ]);
        }
        wp_die();
    }


    /**
     * Add plugin's menu
     * @since 1.0.0
     */
    public function template_hero_elementor_menu() {
        $admin_menu_title = get_site_option( 'th_cl_admin_menu_title', 'Template Hero Client' );
        $admin_menu_title = apply_filters( 'th_elementor_admin_menu_title', $admin_menu_title );
        add_options_page(
            __( $admin_menu_title, 'template-hero-elementor' ),
            __( $admin_menu_title, 'template-hero-elementor' ),
            'manage_options',
            'template-hero-elementor-options',
            [ $this, 'template_hero_elementor_options' ]
        );
    }

    /**
     * Add plugin's network menu
     * @since 1.0.0
     */
    public function template_hero_elementor_mu_menu() {
        $admin_menu_title = get_site_option( 'th_cl_network_menu_title', 'Template Hero Client' );
        $admin_menu_title = apply_filters( 'th_elementor_network_admin_menu_title', $admin_menu_title );
        $hook_suffix = add_menu_page(  
            __( $admin_menu_title, 'template-hero-elementor' ), 
            __( $admin_menu_title, 'template-hero-elementor' ), 
            'manage_network_options', 
            'template-hero-elementor-options', 
            [ $this, 'template_hero_elementor_mu_options' ],
            'dashicons-welcome-widgets-menus' 
        );
    }

    /**
     * Setting page data
     * @since 1.0.0
     */
    public function template_hero_elementor_mu_options() {
        if( class_exists('\\The_WP_Ultimo\\The_Wu_Integration') ) {
            $hide = 'true';
        } else {
            $hide = 'false';
        }
        ?>
        <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.4.1/css/bootstrap.min.css" integrity="sha384-Vkoo8x4CGsO3+Hhxv8T/Q5PaXtkKtu6ug5TOeNV6gBiFeWPGFN9MuhOf23Q9Ifjh" crossorigin="anonymous">

        <div class="wrap template-hero-elementor-settings-wrapper">
            <div id="icon-options-general" class="icon32"></div>
            <h1><?php echo __( 'Template Hero Settings', 'template-hero-elementor' ); ?></h1>

            <div class="template-hero-elementor-tab-box">

            <div class="nav-tab-wrapper">
                <?php
                $template_hero_elementor_sections = $this->template_hero_elementor_get_mu_setting_sections();
                foreach( $template_hero_elementor_sections as $key => $template_hero_elementor_section ) {
                 
                    if( $hide == 'true' && $key == 'connect' ) {
                     } else {
                         ?>
                        <a href="?page=template-hero-elementor-options&tab=<?php echo $key; ?>"
                        class="nav-tab <?php echo $this->page_tab == $key ? 'nav-tab-active hero-active' : ''; ?>">
                            <i class="fa <?php echo $template_hero_elementor_section['icon']; ?>" aria-hidden="true"></i>
                            <?php _e( $template_hero_elementor_section['title'], 'template-hero-elementor' ); ?>
                        </a>
                        <?php
                    }
                }
                ?>
            </div>
            <div class="template-hero-elementor-tab-innerbox">

            <?php
            foreach( $template_hero_elementor_sections as $key => $template_hero_elementor_section ) {
                if( $this->page_tab == $key ) {
                    include( 'templates/' . $key . '.php' );
                }
            }
            ?>
            </div>
            </div>
        </div>
        <?php
    }

    /**
     * Fields Generator
     * @since 1.0.0
     * @param string $label
     * @param $name
     * @param $field_type
     * @param string $field_value
     * @param string $hint
     * @param string $before_text
     * @param string $after_text
     */
    public function create_fields( $label = '', $name, $field_type, $field_value = '', $checked = '', $hint = '', $before_text = '', $after_text = '' ) {

        if( empty( $field_type ) || is_null( $field_type ) ) return;
        if( empty( $name ) || is_null( $name ) ) return;

        if( 'checkbox' === $field_type ) {

            if( !empty( $label ) ) {
                echo '<td>';
                echo '<label for="'. $name .'" class="label">'. $label . '</label>';
                echo '</td>';
            } else {
                echo '';
            }

            echo '<td>';
            echo $before_text . ' <input type="' . $field_type . '" '. $checked .'  class="checkbox" id="'. $name .'" name="' . $name . '" /> ' .$after_text;
            if( !empty( $hint ) ) {
                echo '<span class="hint">'. $hint .'</span>';
            }
            echo '</td>';
        } elseif( 'text' === $field_type || 'number' === $field_type ) {
            echo '<td>';
            if( !empty( $label ) ) {
                echo '<label for="'. $name .'" class="label">'. $label . '</label>';
            } else {
                echo '&nbsp;';
            }
            echo '</td>';
            echo '<td>';
            $description_text = ( empty( $field_value ) ? 'Quiz Content' : $field_value );
            echo $before_text . ' <input type="' . $field_type . '" id="'. $name .'" value="' . $description_text . '" name="' . $name . '" /> ' .$after_text;
            if( !empty( $hint ) ) {
                echo '<span class="hint">'. $hint .'</span>';
            }
            echo '</td>';
        } elseif( 'textarea' === $field_type ) {
            if( !empty( $label ) ) {
                echo '<label for="'. $name .'" class="label-textarea">'. $label . '</label>';
            }
            echo $before_text . ' <textarea id="'. $name .'" cols="100" rows="7" name="' . $name . '" />'.$field_value.'</textarea> ' .$after_text;
            if( !empty( $hint ) ) {
                echo '<span class="hint">'. $hint .'</span>';
            }
        } elseif( 'radio' === $field_type ) {
            echo $before_text . ' <input type="' . $field_type . '" '. $checked .' class="'. $name .'" value="' . $field_value . '" name="' . $name . '" /> ' .$after_text;
            if( !empty( $hint ) ) {
                echo '<span class="hint">'. $hint .'</span>';
            }
        }
    }

    /**
     * Template Hero Elementor Settings Sections
     * @since 1.0.0
     * @return mixed|void
     */
    public function template_hero_elementor_get_mu_setting_sections() {
        $network_wide     = $this::$network_wide;
        $activate_library = 'Activate Library';
        $activate_library = apply_filters( 'th_elementor_activate_library_tab_title', $activate_library );

        $netwrok_library  = 'Create Network Wide Library';
        $netwrok_library  = apply_filters( 'th_elementor_network_library_tab_title', $netwrok_library );

        $api_token = 'Api Token';
        $api_token = apply_filters( 'th_elementor_api_token_tab_title', $api_token );

        $license = 'License & Support';
        $license = apply_filters( 'th_elementor_license_tab_title', $license );

        $logs  = 'Logs & Error Files';
        $logs  = apply_filters( 'th_elementor_logs_tab_title', $logs  );

        $advanced = 'Advanced';
        $advanced = apply_filters( 'th_elementor_advanced_tab_title', $advanced  );

        if( $network_wide != 'no' ) {
            
            $template_hero_elementor_settings_sections = array(
                'connect' => array(
                    'title' => __( $activate_library,'template-hero-elementor' ),
                    'icon' => 'fa-hashtag',
                ),
                'mu_general' => array(
                    'title' => __( $netwrok_library, 'template-hero-elementor' ),
                    'icon' => 'fa-hashtag',
                ),
                'token' => array(
                    'title' => __( $api_token, 'template-hero-elementor' ),
                    'icon' => 'fa-hashtag',
                ),
                'license' => array(
                    'title' => __( $license, 'template-hero-elementor' ),
                    'icon' => 'fa-hashtag',
                ),
                'logs' => array(
                    'title' => __( $logs, 'template-hero-elementor' ),
                    'icon' => 'fa-hashtag',
                ),
                'advance' => array(
                    'title' => __( $advanced, 'template-hero-elementor' ),
                    'icon' => 'fa-hashtag',
                )
            );
        } else {
            $template_hero_elementor_settings_sections = array(
                'mu_general' => array(
                    'title' => __( 'General', 'template-hero-elementor' ),
                    'icon' => 'fa-hashtag',
                ),
                'license' => array(
                    'title' => __( $license, 'template-hero-elementor' ),
                    'icon' => 'fa-hashtag',
                ),
                'logs' => array(
                    'title' => __( $logs, 'template-hero-elementor' ),
                    'icon' => 'fa-hashtag',
                ),
                'advance' => array(
                    'title' => __( $advanced, 'template-hero-elementor' ),
                    'icon' => 'fa-hashtag',
                )
            );
        }

        return apply_filters( 'template_hero_elementor_settings_sections', $template_hero_elementor_settings_sections );
    }

    /**
     * Setting page data
     * @since 1.0.0
     */
    public function template_hero_elementor_options() {
        if( class_exists('\\The_WP_Ultimo\\The_Wu_Integration') ) {
            $hide = 'true';
        } else {
            $hide = 'false';
        }
        ?>
        <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.4.1/css/bootstrap.min.css" integrity="sha384-Vkoo8x4CGsO3+Hhxv8T/Q5PaXtkKtu6ug5TOeNV6gBiFeWPGFN9MuhOf23Q9Ifjh" crossorigin="anonymous">


        <div class="wrap template-hero-elementor-settings-wrapper">
            <div id="icon-options-general" class="icon32"></div>
            <h1><?php echo __( 'Template Hero Settings', 'template-hero-elementor' ); ?></h1>

            <div class="template-hero-elementor-tab-box">

            <div class="nav-tab-wrapper">
                <?php
                $template_hero_elementor_sections = $this->template_hero_elementor_get_setting_sections();
                foreach( $template_hero_elementor_sections as $key => $template_hero_elementor_section ) {
                    if( $hide == 'true' && ( $key == 'connect' || $key == 'general' || $key == 'token' ) ) {
                       
                    } else {
                        if( is_multisite() && $key == 'license' ) {
                            //continue;
                        }
                        ?>
                        <a href="?page=template-hero-elementor-options&tab=<?php echo $key; ?>"
                        class="nav-tab <?php echo $this->page_tab == $key ? 'nav-tab-active hero-active' : ''; ?>">
                            <i class="fa <?php echo $template_hero_elementor_section['icon']; ?>" aria-hidden="true"></i>
                            <?php _e( $template_hero_elementor_section['title'], 'template-hero-elementor' ); ?>
                        </a>
                        <?php
                    }
                }
                ?>
            </div>
            <div class="template-hero-elementor-tab-innerbox">

            <?php
            foreach( $template_hero_elementor_sections as $key => $template_hero_elementor_section ) {
                if( $this->page_tab == $key ) {
                    
                    $url = 'templates/' . $key . '.php' ;
                    apply_filters( 'template_hero_elementor_template_url', $url );
                    include( $url );
                }
            }
            ?>
            </div>
            </div>
        </div>
        <?php
    }

    /**
     * Template Hero Settings Sections
     * @since 1.0.0
     *
     * @return mixed|void
     */
    public function template_hero_elementor_get_setting_sections() {
        $activate_library = 'Activate Library';
        $activate_library = apply_filters( 'th_elementor_activate_library_tab_title', $activate_library );

        $create_library = 'Create Library';
        $create_library = apply_filters( 'th_elementor_create_library_tab_title', $create_library );

        $netwrok_library  = 'Create Network Wide Library';
        $netwrok_library  = apply_filters( 'th_elementor_network_library_tab_title', $netwrok_library );

        $api_token = 'Api Token';
        $api_token = apply_filters( 'th_elementor_api_token_tab_title', $api_token );

        $license = 'License & Support';
        $license = apply_filters( 'th_elementor_license_tab_title', $license );

        $logs  = 'Logs & Error Files';
        $logs  = apply_filters( 'th_elementor_logs_tab_title', $logs  );

        $advanced = 'Advanced';
        $advanced = apply_filters( 'th_elementor_advanced_tab_title', $advanced  );
        $network_wide     = $this::$network_wide;
        if ( $network_wide != 'on'  ) {

            $template_hero_elementor_settings_sections = array(
                'general' => array(
                    'title' => __( $create_library, 'template-hero-elementor' ),
                    'icon' => 'fa-hashtag'
                ),
                'connect' => array(
                    'title' => __( $activate_library, 'template-hero-elementor' ),
                    'icon' => 'fa-hashtag'
                ),
                'token' => array(
                    'title' => __( $api_token , 'template-hero-elementor' ),
                    'icon' => 'fa-hashtag'
                ),
                'logs' => array(
                    'title' => __( $logs, 'template-hero-elementor' ),
                    'icon' => 'fa-hashtag'
                ),
                'license' => array(
                    'title' => __( $license, 'template-hero-elementor' ),
                    'icon'  => 'fa-hashtag'
                ),
                'advance' => array(
                    'title' => __( $advanced, 'template-hero-elementor' ),
                    'icon' => 'fa-hashtag'
                )
            );

        } else {

            $template_hero_elementor_settings_sections = array(
                'logs' => array(
                    'title' => __( $logs, 'template-hero-elementor' ),
                    'icon'  => 'fa-hashtag'
                ),
                'license' => array(
                    'title' => __( $license, 'template-hero-elementor' ),
                    'icon'  => 'fa-hashtag'
                ),
                'advance' => array(
                    'title' => __( $advanced, 'template-hero-elementor' ),
                    'icon'  => 'fa-hashtag'
                )
            );
        }

        return apply_filters( 'template_hero_elementor_settings_sections', $template_hero_elementor_settings_sections );
    }

    /**
     * Add footer branding
     * @since 1.0.0
     * @param $footer_text
     * @return mixed
     */
    function template_hero_elementor_remove_footer_admin ( $footer_text ) {
        if( isset( $_GET['page'] ) && ( $_GET['page'] == 'template-hero-elementor-options' ) ) {
            return _e( 'Built & Supported by <a href="https://waashero.com" target="_blank">WaaS Hero</a></p>', 
            'template-hero-elementor' 
            );
        } else {
            return $footer_text;
        }
    }
}
