<?php
/**
 * MindBody WordPress Integration  Options
 *
 * Displays the MindBody WordPress Integration  Options.
 *
 * @author   J Hanlon
 * @category Admin
 * @package  MindBody WordPress Integration Options /Plugin Options
 * @version  1.0.0
 */

namespace The_WP_Ultimo\The_Wu_Integration;
if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

/**
 * Class MindBody WordPress Integration
 */

class Options {
    public $page_tab;

    /**
     * Hook in tabs.
     */
    public function __construct () {
        $this->page_tab  = isset( $_GET['tab'] ) ? $_GET['tab'] : 'license';
    }
    /**
     * Adds admin notices
     *
     * @return void
     */
    public function the_wu_admin_notices() {
        $status = get_option( '_the_wu_license_key_status', 'not' );
        if ( $status != 'active' ) {
            $class = 'notice notice-error is-dismissible';
            $message = __( 'Please activate your license to get feature updates, premium support and unlimited access to the wp ultimo libraries.',  'the-wu-integration'  );
            printf( '<div class="%1$s"><p>%2$s</p></div>', esc_attr( $class ), esc_html( $message ) );
        }
        $screen = get_current_screen();
		$is_elementor_screen = ( $screen && false !== strpos( $screen->id, 'the-wu-options' ) );
		if ( ! $is_elementor_screen ) {
			
			return;
		}
        if( isset( $_POST['the_wu_advance_settings_submit'] ) || ( isset( $_GET['advance-settings-updated'] ) && $_GET['advance-settings-updated'] == 'true' ) ) {
            $class = 'notice notice-success is-dismissible';
            $message = __( 'Advance Settings Saved', 'template-hero-elementor' );
            printf( '<div class="%1$s"><p>%2$s</p></div>', esc_attr( $class ), esc_html( $message ) );
        } elseif( isset( $_POST['the_wu_advance_settings_submit'] ) || ( isset( $_GET['advance-settings-updated'] ) && $_GET['advance-settings-updated'] == 'false' ) ) {
            $class = 'notice notice-error is-dismissible';
            $message = __( 'Advance Settings Not Saved', 'template-hero-elementor' );
            printf( '<div class="%1$s"><p>%2$s</p></div>', esc_attr( $class ), esc_html( $message ) );
        } elseif( ( isset( $_GET['nonce-verified'] ) && $_GET['nonce-verified'] == 'false' ) ) {
            $class = 'notice notice-error is-dismissible';
            $message = __( 'Security Issues', 'template-hero-elementor' );
            printf( '<div class="%1$s"><p>%2$s</p></div>', esc_attr( $class ), esc_html( $message ) );
        }
    }
   
    /**
     * Add plugin's menu
     */
    public function the_wu_network_menu() {
        $the_wu = get_site_option( 'the_wu_network_menu_title', 'THE Wp Ultimo' );
        $the_wu = apply_filters( 'the_wu_admin_menu_title', $the_wu );
        add_menu_page(  
            __( $the_wu , 'the-wu-integration' ), 
            __( $the_wu , 'the-wu-integration' ), 
            'manage_network_options', 
            'the-wu-options', 
            [ $this, 'the_wu_options' ],
            'dashicons-welcome-widgets-menus' 
        );
    }

     /**
     * Advance settings save
     *
     * @return void
     */
    public function the_wu_admin_advance_settings_save() {
        $uploads  = 'false';
        $the_wu_option  = get_option( 'the_wu_advance_options', array() );
        $nonce    = isset( $_POST['the_wu_admin_advance_settings_action'] ) ? $_POST['the_wu_admin_advance_settings_action'] : '';
        $action   = 'the_wu_admin_advance_settings_action';
        if( isset( $_POST['the_wu_advance_settings_submit'] ) && wp_verify_nonce( $nonce, $action ) ) {

            $n_title    = isset( $_POST['the_wu_network_menu_title'] ) ? $_POST['the_wu_network_menu_title'] : 'THE Wp Ultimo';
           
            update_site_option( 'the_wu_network_menu_title', $n_title );
            $uploads  = 'true';
        } else {
            wp_safe_redirect( add_query_arg( 'nonce-verified', $uploads, $_POST['_wp_http_referer'] ) );
            exit;
        }
        wp_safe_redirect( add_query_arg( 'advance-settings-updated', $uploads, $_POST['_wp_http_referer'] ) );
        exit;
    }
    

    /**
     * Fields Generator
     *
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
     * Setting page data
     */
    public function the_wu_options() {
        ?>
        <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.4.1/css/bootstrap.min.css" integrity="sha384-Vkoo8x4CGsO3+Hhxv8T/Q5PaXtkKtu6ug5TOeNV6gBiFeWPGFN9MuhOf23Q9Ifjh" crossorigin="anonymous">


        <div class="wrap the-wu-settings-wrapper">
            <div id="icon-options-general" class="icon32"></div>
            <h1><?php echo __( 'Template Hero Wp Ultimo Settings',  'the-wu-integration' ); ?></h1>

            <div class="the-wu-tab-box">

            <div class="nav-tab-wrapper">
                <?php
                $the_wu_settings_sections = $this->the_wu_settings_sections();
                foreach( $the_wu_settings_sections as $key => $the_wu_settings_section ) {
                    ?>
                    <a href="?page=the-wu-options&tab=<?php echo $key; ?>"
                       class="nav-tab <?php echo $this->page_tab == $key ? 'hero-active' : ''; ?>">
                        <i class="fa <?php echo $the_wu_settings_section['icon']; ?>" aria-hidden="true"></i>
                        <?php _e( $the_wu_settings_section['title'],  'the-wu-integration' ); ?>
                    </a>
                    <?php
                }
                ?>
            </div>
            <div class="e2m-mbo-tab-innerbox">

            <?php
            foreach( $the_wu_settings_sections as $key => $the_wu_settings_section ) {
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
     * E2B MBO Settings Sections
     *
     * @return mixed|void
     */
    public function the_wu_settings_sections() {

        $the_wu_settings_sections = array(
            'license' => array(
                'title' => __( 'License',  'the-wu-integration' ),
                'icon' => 'fa-hashtag',
            ),
            'advance' => array(
                'title' => __( 'Advanced',  'the-wu-integration' ),
                'icon' => 'fa-hashtag',
            )
        );

        return apply_filters( 'the_wu_settings_sections', $the_wu_settings_sections );
    }

    /**
     * Add footer branding
     *
     * @param $footer_text
     * @return mixed
     */
    function the_wu_remove_footer_admin ( $footer_text ) {
        $current_screen = get_current_screen();
        if( isset( $_GET['page'] ) && ( $_GET['page'] == 'the-wu-options' ) ) {
            return _e( 'Built & Supported by <a href=" https://waashero.com/" target="_blank">WaaS Hero</a></p>', 
             'the-wu-integration' 
            );
        } else {
            return $footer_text;
        }
    }
}
