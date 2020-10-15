<?php
namespace WeDevs\ERP\CRM\Deals;

use WeDevs\ERP\Framework\Traits\Hooker;
use WeDevs\ERP\CRM\Deals\Helpers;

/**
 * Class responsible for admin panel functionalities
 *
 * @since 1.0.0
 */
class Admin {

    use Hooker;

    /**
     * Constructor for the class
     *
     * Sets up all the appropriate hooks and actions
     *
     * @since 1.0.0
     *
     * @return void
     */
    public function __construct() {
        $this->includes();
        $this->hooks();
    }

    /**
     * Include the required files
     *
     * @since 1.0.0
     *
     * @return void
     */
    private function includes() {
        include_once WPERP_DEALS_INCLUDES . '/class-ajax.php';
    }

    /**
     * Initializes action hooks to ERP
     *
     * @since 1.0.0
     *
     * @return void
     */
    private function hooks() {
        $this->action( 'admin_print_styles', 'admin_print_styles' );
        $this->action( 'admin_menu', 'admin_menu' );
        $this->action( 'admin_enqueue_scripts', 'admin_scripts' );

        // settings
        $this->action( 'erp_settings_crm_sections', 'register_settings_subsection' );
        $this->action( 'erp_settings_crm_section_fields', 'register_settings_fields', 10, 2 );
        $this->action( 'erp_admin_field_deal_settings_fields', 'register_settings_field_type' ); // option field type
    }

    /**
     * Add inline css in wp admin panel
     *
     * @since 1.0.0
     *
     * @return void
     */
    public function admin_print_styles() {
        ?>
            <style>
                .toplevel_page_erp-deals .dashicons-admin-generic:before {
                    font: normal normal normal 16px/1.3 FontAwesome;
                    content: "\f155";
                }
            </style>
        <?php
    }

    /**
     * Add admin panel menu item
     *
     * @since 1.0.0
     *
     * @return void
     */
    public function admin_menu() {

        if ( version_compare( WPERP_VERSION, "1.4.0", '>=' ) ) {
            $this->load_new_menu();
            return;
        }
        // main plugin page. erp_crm_add_contact is a cap for CRM Manager and CRM Agent
        add_menu_page( __( 'Deals', 'erp-pro' ), __( 'Deals', 'erp-pro' ), 'erp_crm_add_contact', 'erp-deals', [ $this, 'admin_view_deals' ] );
        add_submenu_page( 'erp-deals', __( 'Overview', 'erp-pro' ), __( 'Overview', 'erp-pro' ), 'erp_crm_add_contact', 'erp-deals', [ $this, 'admin_view_deals' ] );
        add_submenu_page( 'erp-deals', __( 'Deals', 'erp-pro' ), __( 'Deals', 'erp-pro' ), 'erp_crm_add_contact', 'erp-deals-admin-page', [ $this, 'admin_view_deals' ] );
        add_submenu_page( 'erp-deals', __( 'Activities', 'erp-pro' ), __( 'Activities', 'erp-pro' ), 'erp_crm_add_contact', 'erp-deals-activities', [ $this, 'admin_view_deals' ] );
        add_submenu_page( 'erp-deals', __( 'Settings', 'erp-pro' ), __( 'Settings', 'erp-pro' ), 'manage_options', 'admin.php?page=erp-settings&tab=erp-crm&section=erp_deals' );

        // experimental menu to list the all pipedings icons
        // add_submenu_page( 'erp-pro', __( 'Pipedings', 'erp-pro' ), __( 'Pipedings', 'erp-pro' ), 'erp_crm_add_contact', 'erp-deals-pipedings', [ $this, 'pipedings_list' ] );
    }

    /**
     * Load new menu prior to 1.4.0
     */
    public function load_new_menu() {

        erp_add_menu( 'crm', [
            'title'      => __( 'Deals', 'erp-pro' ),
            'capability' => 'erp_crm_add_contact',
            'slug'       => 'deals',
            'callback'   => [ $this, 'admin_view_deals' ],
            'position'   => 11
        ] );

        erp_add_submenu( 'crm','deals', [
            'title'      => __( 'Dashboard', 'erp' ),
            'capability' => 'erp_crm_add_contact',
            'slug'       => 'dashboard',
            'callback'   => [ $this, 'admin_view_deals' ],
            'position'   => 1
        ] );

        erp_add_submenu( 'crm','deals', [
            'title'      => __( 'All Deals', 'erp' ),
            'capability' => 'erp_crm_add_contact',
            'slug'       => 'all-deals',
            'callback'   => [ $this, 'admin_view_deals' ],
            'position'   => 5
        ] );

        erp_add_submenu( 'crm','deals', [
            'title'      => __( 'Activities', 'erp' ),
            'capability' => 'erp_crm_add_contact',
            'slug'       => 'activities',
            'callback'   => [ $this, 'admin_view_deals' ],
            'position'   => 10
        ] );

        erp_add_submenu( 'crm','deals', [
            'title'      => __( 'Settings', 'erp' ),
            'capability' => 'create_users',
            'direct_link'=> admin_url( 'admin.php?page=erp-settings&tab=erp-crm&section=erp_deals' ),
            'slug'       => 'settings',
            'callback'   => '',
            'position'   => 15
        ] );
    }

    /**
     * Register admin scripts
     *
     * @since 1.0.0
     *
     * @return void
     */
    public function admin_scripts( $hook_suffix ) {

        if ( version_compare( WPERP_VERSION, "1.4.0", '>=' ) ) {
            $this->load_new_scripts( $hook_suffix );
            return;
        }

        $time_format = get_option( 'time_format', 'g:i a' );

        $erp_deals_global = [
            'ajaxurl'           => admin_url( 'admin-ajax.php' ),
            'nonce'             => wp_create_nonce( 'erp-deals' ),
            'scriptDebug'       => defined( 'SCRIPT_DEBUG' ) ? SCRIPT_DEBUG : false,
            'date'              => [
                'format'        => Helpers::js_date_format(),
                'placeholder'   => erp_format_date( 'now' )
            ],
            'time'              => [
                'format'        => $time_format,
                'placeholder'   => date( $time_format )
            ],
        ];

        $menu = sanitize_title( __( 'Deals', 'erp-pro' ) );
        $deal_menu_pages = [ "toplevel_page_erp-deals", "{$menu}_page_erp-deals-admin-page", "{$menu}_page_erp-deals-activities" ];

        if ( in_array( $hook_suffix , $deal_menu_pages ) ) { // Dashboard, All Deals, Activities
            wp_enqueue_script( 'erp-moment-tz', WPERP_DEALS_ASSETS . '/vendor/moment/moment-timezone-with-data.js', ['erp-momentjs'], WPERP_DEALS_VERSION, true );

            $style_deps = [
                'erp-styles', 'erp-timepicker', 'erp-fontawesome',
                'erp-sweetalert', 'erp-nprogress', 'erp-trix-editor'
            ];

            $script_deps = [
                'jquery', 'erp-vuejs', 'jquery-ui-datepicker', 'jquery-ui-sortable',
                'erp-timepicker', 'erp-sweetalert', 'erp-nprogress', 'erp-trix-editor',
                'erp-moment-tz'
            ];

            $erp_deals_global['isUserAnAdmin']  = current_user_can( 'administrator' );
            $erp_deals_global['isUserAManager'] = erp_crm_is_current_user_manager();
            $erp_deals_global['isUserAnAgent']  = erp_crm_is_current_user_crm_agent();
            $erp_deals_global['currentUserId']  = get_current_user_id();
            $erp_deals_global['i18n']           = $this->i18n();
            $erp_deals_global['activityTypes']  = Helpers::get_activity_types();
            $erp_deals_global['lostReasons']    = Helpers::get_lost_reasons();
            $erp_deals_global['pipelineURL']    = Helpers::admin_url( [], 'erp-deals-admin-page' );
            $erp_deals_global['pluginURL']      = WPERP_DEALS_URL;
            $erp_deals_global['singlePageURL']  = Helpers::admin_url( [ 'action' => 'view-deal', 'id' => 'DEALID' ], 'erp-deals-admin-page' );
            $erp_deals_global['pipes']          = Helpers::get_pipelines_with_stages();
            $erp_deals_global['wpTimezone']     = Helpers::get_wp_timezone();

            if ( "toplevel_page_erp-deals" === $hook_suffix ) { // overview page
                $erp_deals_global['crmAgents'] = Helpers::get_crm_agents( [], true, true );

                $script_deps[] = 'erp-flotchart';
                $script_deps[] = 'erp-flotchart-categories';
                $script_deps[] = 'erp-flotchart-stack';
            }

            if ( "{$menu}_page_erp-deals-activities" === $hook_suffix ) { // activities page
                $erp_deals_global['users']          = Helpers::get_crm_agents_with_current_user();
                $erp_deals_global['activitiesURL']  = Helpers::admin_url( [], 'erp-deals-activities' );
            }

            if ( isset( $_GET['action'] ) && 'view-deal' === $_GET['action'] ) { // single deal page
                wp_enqueue_style( 'tiny-mce', site_url( '/wp-includes/css/editor.css' ), [], WPERP_DEALS_VERSION );
                $style_deps[] = 'tiny-mce';

                wp_enqueue_script( 'tiny-mce', site_url( '/wp-includes/js/tinymce/tinymce.min.js' ), [] );
                wp_enqueue_script( 'tiny-mce-code', WPERP_DEALS_ASSETS . '/vendor/tinymce/plugins/code/plugin.min.js', [ 'tiny-mce' ], WPERP_DEALS_VERSION, true );
                wp_enqueue_script( 'tiny-mce-hr', WPERP_DEALS_ASSETS . '/vendor/tinymce/plugins/hr/plugin.min.js', [ 'tiny-mce' ], WPERP_DEALS_VERSION, true );
                $script_deps[] = 'tiny-mce';
                $script_deps[] = 'tiny-mce-code';
                $script_deps[] = 'tiny-mce-hr';

                $erp_deals_global['emailTemplates'] = \WeDevs\ERP\CRM\Models\Save_Replies::orderBy( 'name', 'asc' )->get();
                $erp_deals_global['shortcodes'] = deal_shortcodes()->shortcodes();
            }

            // countUp js
            wp_enqueue_script( 'erp-deals-countup', WPERP_DEALS_ASSETS . '/vendor/countUp/countUp.js', [], WPERP_DEALS_VERSION, true );
            $script_deps[] = 'erp-deals-countup';

            // pipedings
            wp_enqueue_style( 'erp-deals-pipedings', WPERP_DEALS_ASSETS . '/vendor/pipedings/css/pipedings.css', [], WPERP_DEALS_VERSION );
            $style_deps[] = 'erp-deals-pipedings';

            // plugin assets
            wp_enqueue_style( 'erp-deals', WPERP_DEALS_ASSETS . '/css/erp-deals.css', $style_deps, WPERP_DEALS_VERSION );
            wp_enqueue_script( 'erp-deals', WPERP_DEALS_ASSETS . '/js/erp-deals.js', $script_deps, WPERP_DEALS_VERSION, true );
            wp_localize_script( 'erp-deals', 'erpDealsGlobal', $erp_deals_global );
        }


        if ( "{$menu}_page_erp-deals-pipedings" === $hook_suffix ) { // Pipedings
            wp_enqueue_style( 'erp-deals-pipedings', WPERP_DEALS_ASSETS . '/vendor/pipedings/css/pipedings.css', [], WPERP_DEALS_VERSION );
        }

        $menu = sanitize_title( __( 'ERP Settings', 'erp' ) );
        if ( "{$menu}_page_erp-settings" === $hook_suffix ) { // settings page
            $style_deps = [
                'erp-styles', 'erp-fontawesome', 'erp-sweetalert', 'erp-nprogress',
            ];

            $script_deps = [
                'jquery', 'erp-vuejs', 'jquery-ui-sortable', 'erp-sweetalert', 'erp-nprogress',
                'underscore'
            ];

            wp_enqueue_style( 'erp-deals-pipedings', WPERP_DEALS_ASSETS . '/vendor/pipedings/css/pipedings.css', [], WPERP_DEALS_VERSION );
            wp_enqueue_style( 'erp-deals-settings', WPERP_DEALS_ASSETS . '/css/erp-deals-settings.css', $style_deps, WPERP_DEALS_VERSION );

            wp_enqueue_script( 'erp-deals-settings', WPERP_DEALS_ASSETS . '/js/erp-deals-settings.js', $script_deps, WPERP_DEALS_VERSION, true );

            $erp_deals_global['i18n'] = $this->i18n();
            wp_localize_script( 'erp-deals-settings', 'erpDealsGlobal', $erp_deals_global );
        }
    }

    /**
     * Load scripts for new version
     *
     * @param $hook_suffix
     */
    public function load_new_scripts( $hook_suffix ) {
        $time_format = get_option( 'time_format', 'g:i a' );
        $erp_deals_global = [
            'ajaxurl'           => admin_url( 'admin-ajax.php' ),
            'nonce'             => wp_create_nonce( 'erp-deals' ),
            'scriptDebug'       => defined( 'SCRIPT_DEBUG' ) ? SCRIPT_DEBUG : false,
            'date'              => [
                'format'        => Helpers::js_date_format(),
                'placeholder'   => erp_format_date( 'now' )
            ],
            'time'              => [
                'format'        => $time_format,
                'placeholder'   => date( $time_format )
            ],
        ];

        if ( "wp-erp_page_erp-settings" === $hook_suffix ) { // settings page
            $style_deps = [
                'erp-styles', 'erp-fontawesome', 'erp-sweetalert', 'erp-nprogress',
            ];

            $script_deps = [
                'jquery', 'erp-vuejs', 'jquery-ui-sortable', 'erp-sweetalert', 'erp-nprogress',
                'underscore'
            ];

            wp_enqueue_style( 'erp-deals-pipedings', WPERP_DEALS_ASSETS . '/vendor/pipedings/css/pipedings.css', [], WPERP_DEALS_VERSION );
            wp_enqueue_style( 'erp-deals-settings', WPERP_DEALS_ASSETS . '/css/erp-deals-settings.css', $style_deps, WPERP_DEALS_VERSION );

            wp_enqueue_script( 'erp-deals-settings', WPERP_DEALS_ASSETS . '/js/erp-deals-settings.js', $script_deps, WPERP_DEALS_VERSION, true );

            $erp_deals_global['i18n'] = $this->i18n();
            wp_localize_script( 'erp-deals-settings', 'erpDealsGlobal', $erp_deals_global );
        }

        $is_crm_page = ( !empty( $_GET['page'] ) && ( 'erp-crm' == $_GET['page'] ) ) ? true : false;

        $is_deals_page = ( !empty( $_GET['section'] ) && ( 'deals' == $_GET['section'] ) ) ? true : false;

        $sub_section   = !empty( $_GET['sub-section'] ) ? $_GET['sub-section'] : 'dashboard';

        if ( $is_crm_page && $is_deals_page ) { // Dashboard, All Deals, Activities
            wp_enqueue_script( 'erp-moment-tz', WPERP_DEALS_ASSETS . '/vendor/moment/moment-timezone-with-data.js', ['erp-momentjs'], WPERP_DEALS_VERSION, true );

            $style_deps = [
                'erp-styles', 'erp-timepicker', 'erp-fontawesome',
                'erp-sweetalert', 'erp-nprogress', 'erp-trix-editor'
            ];

            $script_deps = [
                'jquery', 'erp-vuejs', 'jquery-ui-datepicker', 'jquery-ui-sortable',
                'erp-timepicker', 'erp-sweetalert', 'erp-nprogress', 'erp-trix-editor',
                'erp-moment-tz'
            ];

            $erp_deals_global['isUserAnAdmin']  = current_user_can( 'administrator' );
            $erp_deals_global['isUserAManager'] = erp_crm_is_current_user_manager();
            $erp_deals_global['isUserAnAgent']  = erp_crm_is_current_user_crm_agent();
            $erp_deals_global['currentUserId']  = get_current_user_id();
            $erp_deals_global['i18n']           = $this->i18n();
            $erp_deals_global['activityTypes']  = Helpers::get_activity_types();
            $erp_deals_global['lostReasons']    = Helpers::get_lost_reasons();
            $erp_deals_global['pipelineURL']    = Helpers::admin_url( [] );
            $erp_deals_global['pluginURL']      = WPERP_DEALS_URL;
            $erp_deals_global['singlePageURL']  = Helpers::admin_url( [ 'sub-section' => 'all-deals', 'action' => 'view-deal', 'id' => 'DEALID' ] );
            $erp_deals_global['pipes']          = Helpers::get_pipelines_with_stages();
            $erp_deals_global['wpTimezone']     = Helpers::get_wp_timezone();
            $erp_deals_global['subSection']    = $sub_section;

            if ( "dashboard" === $sub_section ) { // overview page
                $erp_deals_global['crmAgents'] = Helpers::get_crm_agents( [], true, true );

                $script_deps[] = 'erp-flotchart';
                $script_deps[] = 'erp-flotchart-categories';
                $script_deps[] = 'erp-flotchart-stack';
            }

            if ( "activities" === $sub_section ) { // activities page
                $erp_deals_global['users']          = Helpers::get_crm_agents_with_current_user();
                $erp_deals_global['activitiesURL']  = Helpers::admin_url( [] );
            }

            if ( isset( $_GET['action'] ) && 'view-deal' === $_GET['action'] ) { // single deal page
                wp_enqueue_style( 'tiny-mce', site_url( '/wp-includes/css/editor.css' ), [], WPERP_DEALS_VERSION );
                $style_deps[] = 'tiny-mce';

                wp_enqueue_script( 'tiny-mce', site_url( '/wp-includes/js/tinymce/tinymce.min.js' ), [] );
                wp_enqueue_script( 'tiny-mce-code', WPERP_DEALS_ASSETS . '/vendor/tinymce/plugins/code/plugin.min.js', [ 'tiny-mce' ], WPERP_DEALS_VERSION, true );
                wp_enqueue_script( 'tiny-mce-hr', WPERP_DEALS_ASSETS . '/vendor/tinymce/plugins/hr/plugin.min.js', [ 'tiny-mce' ], WPERP_DEALS_VERSION, true );
                $script_deps[] = 'tiny-mce';
                $script_deps[] = 'tiny-mce-code';
                $script_deps[] = 'tiny-mce-hr';

                $erp_deals_global['emailTemplates'] = \WeDevs\ERP\CRM\Models\Save_Replies::orderBy( 'name', 'asc' )->get();
                $erp_deals_global['shortcodes'] = deal_shortcodes()->shortcodes();
            }

            // countUp js
            wp_enqueue_script( 'erp-deals-countup', WPERP_DEALS_ASSETS . '/vendor/countUp/countUp.js', [], WPERP_DEALS_VERSION, true );
            $script_deps[] = 'erp-deals-countup';

            // pipedings
            wp_enqueue_style( 'erp-deals-pipedings', WPERP_DEALS_ASSETS . '/vendor/pipedings/css/pipedings.css', [], WPERP_DEALS_VERSION );
            $style_deps[] = 'erp-deals-pipedings';

            // plugin assets
            wp_enqueue_style( 'erp-deals', WPERP_DEALS_ASSETS . '/css/erp-deals.css', $style_deps, WPERP_DEALS_VERSION );
            wp_enqueue_script( 'erp-deals', WPERP_DEALS_ASSETS . '/js/erp-deals.js', $script_deps, WPERP_DEALS_VERSION, true );
            wp_localize_script( 'erp-deals', 'erpDealsGlobal', $erp_deals_global );

        }

    }
    /**
     * Print notices for WordPress
     *
     * @since 1.0.0
     *
     * @param string $text
     * @param string $type
     *
     * @return void
     */
    public function display_notice( $text, $type = 'updated' ) {
        printf( '<div class="%s"><p>%s</p></div>', esc_attr( $type ), $text );
    }

    /**
     * Admin notices
     *
     * @since 1.0.0
     *
     * @return void
     */
    public function admin_notices() {
    }


    /**
     * Deals Admin Page
     *
     * @since 1.0.0
     *
     * @return void
     */
    public function admin_view_deals() {
        require_once WPERP_DEALS_VIEWS . '/deals.php';
    }

    /**
     * Experimental page contains all pipedings icon list
     *
     * @since 1.0.0
     *
     * @return void
     */
    public function pipedings_list() {
        require_once WPERP_DEALS_VIEWS . '/pipedings.php';
    }

    /**
     * i18n strings for main admin pages
     *
     * @since 1.0.0
     *
     * @return array
     */
    private function i18n() {
        $templateSettingsURL = Helpers::admin_url( [ 'tab' => 'erp-crm', 'section' => 'templates' ], $page = 'erp-settings' );

        return [
            'deal'                          => __( 'Deal', 'erp-pro' ),
            'deals'                         => __( 'Deals', 'erp-pro' ),
            'addNew'                        => __( 'Add New', 'erp-pro' ),
            'scheduleAnActivity'            => __( 'Schedule an activity', 'erp-pro' ),
            'editActivity'                  => __( 'Edit activity', 'erp-pro' ),
            'markAsDone'                    => __( 'Mark as done', 'erp-pro' ),
            'markAsIncomplete'              => __( 'Mark as incomplete', 'erp-pro' ),
            'overdue'                       => __( 'Overdue', 'erp-pro' ),
            'planned'                       => __( 'Planned', 'erp-pro' ),
            'noActivityMsg'                 => __( 'You have no activities scheduled for this deal', 'erp-pro' ),
            'cancel'                        => __( 'Cancel', 'erp-pro' ),
            'saveActivity'                  => __( 'Save Activity', 'erp-pro' ),
            'date'                          => __( 'Date', 'erp-pro' ),
            'time'                          => __( 'Time', 'erp-pro' ),
            'duration'                      => __( 'Duration', 'erp-pro' ),
            'addNewDeal'                    => __( 'Add New Deal', 'erp-pro' ),
            'save'                          => __( 'Save', 'erp-pro' ),
            'noContactFound'                => __( 'No contact found', 'erp-pro' ),
            'noCompanyFound'                => __( 'No company found', 'erp-pro' ),
            'contact'                       => __( 'Contact', 'erp-pro' ),
            'company'                       => __( 'Company', 'erp-pro' ),
            'dealTitle'                     => __( 'Deal Title', 'erp-pro' ),
            'dealValue'                     => __( 'Deal Value', 'erp-pro' ),
            'pipelineStage'                 => __( 'Pipeline Stage', 'erp-pro' ),
            'expectedCloseDate'             => __( 'Expected close date', 'erp-pro' ),
            'expectedCloseDateOverdue'      => __( 'Overdue %s days', 'erp-pro' ),
            'you'                           => __( 'you', 'erp-pro' ),
            'owner'                         => __( 'Owner', 'erp-pro' ),
            'dealCreateSuccessMsg'          => __( 'Deal created successfully', 'erp-pro' ),
            'assignedTo'                    => __( 'Assigned To', 'erp-pro' ),
            'won'                           => __( 'Won', 'erp-pro' ),
            'markAsWon'                     => __( 'Mark as Won', 'erp-pro' ),
            'lost'                          => __( 'Lost', 'erp-pro' ),
            'markAsLost'                    => __( 'Mark as Lost', 'erp-pro' ),
            'delete'                        => __( 'Delete', 'erp-pro' ),
            'trash'                         => __( 'Trash', 'erp-pro' ),
            'moveToTrash'                   => __( 'Move to Trash', 'erp-pro' ),
            'moveToAnotherPipeline'         => __( 'Move to Another Pipeline', 'erp-pro' ),
            'selectAReason'                 => __( 'Select a reason', 'erp-pro' ),
            'lostReason'                    => __( 'Lost reason', 'erp-pro' ),
            'otherLostReason'               => __( 'Other lost reason', 'erp-pro' ),
            'other'                         => __( 'Other', 'erp-pro' ),
            'lostReasonComment'             => __( 'Lost reason comment', 'erp-pro' ),
            'optional'                      => __( 'optional', 'erp-pro' ),
            'filterOpen'                    => __( 'Open deals', 'erp-pro' ),
            'filterWon'                     => __( 'Won deals', 'erp-pro' ),
            'filterLost'                    => __( 'Lost deals', 'erp-pro' ),
            'filterDeleted'                 => __( 'Trashed deals', 'erp-pro' ),
            'transferOwnerShip'             => __( 'Transfer ownership', 'erp-pro' ),
            'selectOwner'                   => __( 'Select Owner', 'erp-pro' ),
            'days'                          => __( 'days', 'erp-pro' ),
            'hours'                         => __( 'hours', 'erp-pro' ),
            'minutes'                       => __( 'minutes', 'erp-pro' ),
            'seconds'                       => __( 'seconds', 'erp-pro' ),
            'beenHereFor'                   => __( 'Been here for', 'erp-pro' ),
            'notYetInStage'                 => __( 'This deal has not been in this stage yet', 'erp-pro' ),
            'beenHereForFewSecs'            => __( 'Been here for few seconds', 'erp-pro' ),
            'beenHereForFewMins'            => __( 'Been here for few minutes', 'erp-pro' ),
            'beenHereForFewHours'           => __( 'Been here for few hours', 'erp-pro' ),
            'errorInvalidValueFormat'       => __( 'This should be a number with or without 2 decimal places.', 'erp-pro' ),
            'pipeline'                      => __( 'Pipeline ', 'erp-pro' ),
            'selectPipeline'                => __( 'Select Pipeline ', 'erp-pro' ),
            'errorNoPipelineStage'          => __( 'There is no stage for this pipeline.', 'erp-pro' ),
            'setExpectedCloseDate'          => __( 'Set expected close date', 'erp-pro' ),
            'street1'                       => __( 'Street 1', 'erp-pro' ),
            'street2'                       => __( 'Street 2', 'erp-pro' ),
            'city'                          => __( 'City', 'erp-pro' ),
            'country'                       => __( 'Country', 'erp-pro' ),
            'state'                         => __( 'State', 'erp-pro' ),
            'postalCode'                    => __( 'Postal Code', 'erp-pro' ),
            'switchcontact'                 => __( 'Switch to another contact', 'erp-pro' ),
            'switchcompany'                 => __( 'Switch to another company', 'erp-pro' ),
            'participants'                  => __( 'Participants', 'erp-pro' ),
            'addParticipants'               => __( 'Add participants', 'erp-pro' ),
            'addParticipant'                => __( 'Add participant', 'erp-pro' ),
            'remove'                        => __( 'Remove', 'erp-pro' ),
            'noParticipantsMsg'             => __( 'There are no participants linked to this deal', 'erp-pro' ),
            'removeParticipantWarnMsg'      => __( 'Are you sure you want to remove this participant?', 'erp-pro' ),
            'yesRemoveIt'                   => __( 'Yes, remove it!', 'erp-pro' ),
            'removeParticipant'             => __( 'Remove participant', 'erp-pro' ),
            'close'                         => __( 'Close', 'erp-pro' ),
            'add'                           => __( 'Add', 'erp-pro' ),
            'agents'                        => __( 'Agents', 'erp-pro' ),
            'addAgents'                     => __( 'Add agents', 'erp-pro' ),
            'noAgentMsg'                    => __( 'There are no additional agents for this deal.', 'erp-pro' ),
            'removeAgentWarnMsg'            => __( 'Are you sure you want to remove this agent?', 'erp-pro' ),
            'note'                          => __( 'Note', 'erp-pro' ),
            'notes'                         => __( 'Notes', 'erp-pro' ),
            'searchMinCharMsg'              => __( 'enter 3 or more characters', 'erp-pro' ),
            'hour'                          => __( 'Hour', 'erp-pro' ),
            'min'                           => __( 'Min', 'erp-pro' ),
            'addAttachmentMsg'              => __( 'Add attachments for this deal', 'erp-pro' ),
            'attachments'                   => __( 'Attachments', 'erp-pro' ),
            'addAttachment'                 => __( 'Add Attachment', 'erp-pro' ),
            'addAttachments'                => __( 'Add attachments', 'erp-pro' ),
            'selectFiles'                   => __( 'Select files', 'erp-pro' ),
            'attachMoreFiles'               => __( 'Attach more files', 'erp-pro' ),
            'emailTemplate'                 => __( 'Email template', 'erp-pro' ),
            'saveTemplate'                  => __( 'Save template', 'erp-pro' ),
            'saveThisTemplate'              => __( 'Save this template', 'erp-pro' ),
            'sendEmail'                     => __( 'Send email', 'erp-pro' ),
            'selectEmailTemplate'           => __( 'Select email template', 'erp-pro' ),
            'from'                          => __( 'From', 'erp-pro' ),
            'to'                            => __( 'To', 'erp-pro' ),
            'subject'                       => __( 'Subject', 'erp-pro' ),
            'noTemplateMsg'                 => sprintf( __( 'No template found. You can create email template in <a href="%s" target="_blank">CRM Settings</a>', 'erp-pro' ), $templateSettingsURL ),
            'emailAttachments'              => __( 'Email Attachments', 'erp-pro' ),
            'dealAttachments'               => __( 'Deal Attachments', 'erp-pro' ),
            'noEmailAttachmentMsg'          => __( "You haven't attached any files for this email yet", 'erp-pro' ),
            'noEmailAttachmentMsg'          => __( 'Select deal attachment from list below', 'erp-pro' ),
            'allDealFilesAttachedMsg'       => __( "You've attached all deal files to your email", 'erp-pro' ),
            'filename'                      => __( 'File Name', 'erp-pro' ),
            'size'                          => __( 'Size', 'erp-pro' ),
            'chooseEmailAttachmentMsg'      => __( 'Choose email attachment from the deal attachment list below', 'erp-pro' ),
            'pinThisNote'                   => __( 'Pin this note', 'erp-pro' ),
            'unpinThisNote'                 => __( 'Unpin this note', 'erp-pro' ),
            'unpin'                         => __( 'Unpin', 'erp-pro' ),
            'title'                         => __( 'Title', 'erp-pro' ),
            'status'                        => __( 'Status', 'erp-pro' ),
            'done'                          => __( 'Done', 'erp-pro' ),
            'type'                          => __( 'Type', 'erp-pro' ),
            'dueDate'                       => __( 'Due date', 'erp-pro' ),
            'edit'                          => __( 'Edit', 'erp-pro' ),
            'openActivities'                => __( 'Open Activities', 'erp-pro' ),
            'noUpcomingActivityMsg'         => __( 'You have no upcoming activity for this deal.', 'erp-pro' ),
            'deleteActivityWarningMsg'      => __( 'Are you sure you want to delete this activity?', 'erp-pro' ),
            'yesDeleteIt'                   => __( 'Yes, delete it', 'erp-pro' ),
            'competitor'                    => __( 'Competitor', 'erp-pro' ),
            'competitors'                   => __( 'Competitors', 'erp-pro' ),
            'competitorName'                => __( 'Competitor Name', 'erp-pro' ),
            'website'                       => __( 'Website', 'erp-pro' ),
            'strengths'                     => __( 'Strengths', 'erp-pro' ),
            'weaknesses'                    => __( 'Weaknesses', 'erp-pro' ),
            'noCompetitorsMsg'              => __( 'No competitor record found', 'erp-pro' ),
            'addNewCompetitor'              => __( 'Add New Competitor', 'erp-pro' ),
            'editCompetitor'                => __( 'Edit Competitor', 'erp-pro' ),
            'name'                          => __( 'Name', 'erp-pro' ),
            'deleteCompetitorWarningMsg'    => __( 'Are you sure you want to delete this competitor?', 'erp-pro' ),
            'all'                           => __( 'All', 'erp-pro' ),
            'activities'                    => __( 'Activities', 'erp-pro' ),
            'activity'                      => __( 'Activity', 'erp-pro' ),
            'emails'                        => __( 'Emails', 'erp-pro' ),
            'email'                         => __( 'Email', 'erp-pro' ),
            'attachment'                    => __( 'Attachment', 'erp-pro' ),
            'changelog'                     => __( 'Changelog', 'erp-pro' ),
            'markAsToDo'                    => __( 'Mark as To Do', 'erp-pro' ),
            'download'                      => __( 'Download', 'erp-pro' ),
            'noTimelineItemMsg'             => __( 'No item found for this timeline', 'erp-pro' ),
            'pinnedNote'                    => __( 'Pinned Note', 'erp-pro' ),
            'removeAttachmentWarningMsg'    => __( 'Are you sure you want to remove this attachment?', 'erp-pro' ),
            'deleteNoteWarningMsg'          => __( 'Are you sure you want to delete this note?', 'erp-pro' ),
            'loading'                       => __( 'Loading', 'erp-pro' ),
            'dealCreated'                   => __( 'Deal created', 'erp-pro' ),
            'activityCreated'               => __( 'Activity created', 'erp-pro' ),
            'notSet'                        => __( 'not set', 'erp-pro' ),
            'removed'                       => __( 'removed', 'erp-pro' ),
            'oldValue'                      => __( 'Old Value', 'erp-pro' ),
            'newValue'                      => __( 'New Value', 'erp-pro' ),
            'field'                         => __( 'Field', 'erp-pro' ),
            'markedAsDone'                  => __( 'Marked as done', 'erp-pro' ),
            'markedAsTodo'                  => __( 'Marked as todo', 'erp-pro' ),
            'stage'                         => __( 'Stage', 'erp-pro' ),
            'value'                         => __( 'Value', 'erp-pro' ),
            'currency'                      => __( 'Currency', 'erp-pro' ),
            'deleted'                       => __( 'deleted', 'erp-pro' ),
            'addedNewNote'                  => __( 'Added new note', 'erp-pro' ),
            'activityTypes'                 => __( 'Activity Types', 'erp-pro' ),
            'lostReasons'                   => __( 'Lost Reasons', 'erp-pro' ),
            'customizeSalesStages'          => __( 'Customize sales stages', 'erp-pro' ),
            'editStage'                     => __( 'Edit stage', 'erp-pro' ),
            'addNewPipeline'                => __( 'Add new pipeline', 'erp-pro' ),
            'addStage'                      => __( 'Add Stage', 'erp-pro' ),
            'deletePipeline'                => __( 'Delete Pipeline', 'erp-pro' ),
            'deleteDeals'                   => __( 'Delete deals', 'erp-pro' ),
            'moveDealsToAnotherPipeline'    => __( 'Move deals to another pipeline', 'erp-pro' ),
            'deletePipelineAndDeals'        => __( 'Delete pipeline and deals', 'erp-pro' ),
            'doNotChange'                   => __( 'Do not change', 'erp-pro' ),
            'lifeStage'                     => __( 'Life Stage', 'erp-pro' ),
            'stageName'                     => __( 'Stage Name', 'erp-pro' ),
            'deleteThisStage'               => __( 'Yes, delete this stage', 'erp-pro' ),
            'deleteStage'                   => __( 'Delete stage', 'erp-pro' ),
            'editPipeline'                  => __( 'Edit Pipeline', 'erp-pro' ),
            'pipelineTitle'                 => __( 'Pipeline title', 'erp-pro' ),
            'addPipeline'                   => __( 'Add Pipeline', 'erp-pro' ),
            'deleteThisPipeline'            => __( 'Yes, delete this pipeline', 'erp-pro' ),
            'active'                        => __( 'Active', 'erp-pro' ),
            'trashed'                       => __( 'Trashed', 'erp-pro' ),
            'trash'                         => __( 'Trash', 'erp-pro' ),
            'restore'                       => __( 'Restore', 'erp-pro' ),
            'editActivityType'              => __( 'Edit Activity Type', 'erp-pro' ),
            'addActivityType'               => __( 'Add Activity Type', 'erp-pro' ),
            'icon'                          => __( 'Icon', 'erp-pro' ),
            'trashTypeWarningMsg'           => __( 'Are you sure you want to trash this activity? The activities related to this type will also send to trash.', 'erp-pro' ),
            'yesTrashIt'                    => __( 'Yes, Trash it', 'erp-pro' ),
            'noActiveTypeMsg'               => __( 'No active activity type is found', 'erp-pro' ),
            'noTrashedTypeMsg'              => __( 'No trashed activity type is found', 'erp-pro' ),
            'nolostReasonsMsg'              => __( 'You do not have any pre-defined lost reason', 'erp-pro' ),
            'lostReasonsTips'               => __( 'Here you can manage lost reasons. When a deal is marked as lost, users can choose between these options.', 'erp-pro' ),
            'deleteReasonWarningMsg'        => __( 'Are you sure you want to delete this lost reason?', 'erp-pro' ),
            'markedAsWon'                   => __( 'Marked as won', 'erp-pro' ),
            'reopen'                        => __( 'Reopen', 'erp-pro' ),
            'reopened'                      => __( 'Reopened', 'erp-pro' ),
            'restored'                      => __( 'Restored', 'erp-pro' ),
            'markedAsLost'                  => __( 'Marked as lost', 'erp-pro' ),
            'trashDealWarningMsg'           => __( 'Are you sure you want to trash this deal? The activities related to this deal will also send to trash.', 'erp-pro' ),
            'deleteDealWarningTitle'        => __( 'Are you sure you want to delete this deal?', 'erp-pro' ),
            'deleteDealWarningMsg'          => __( 'All activities, notes, attachments etc associated with it will also permenently delete.', 'erp-pro' ),
            'contacts'                      => __( 'Contacts', 'erp-pro' ),
            'emptyActivityListMsg'          => __( 'No activities found', 'erp-pro' ),
            'loadMore'                      => __( 'Load More', 'erp-pro' ),
            'selectPeriod'                  => __( 'Select Period', 'erp-pro' ),
            'todo'                          => __( 'Todo', 'erp-pro' ),
            'completed'                     => __( 'Completed', 'erp-pro' ),
            'apply'                         => __( 'Apply', 'erp-pro' ),
            'overview'                      => __( 'Overview', 'erp-pro' ),
            'newDeals'                      => __( 'New Deals', 'erp-pro' ),
            'wonDeals'                      => __( 'Won Deals', 'erp-pro' ),
            'lostDeals'                     => __( 'Lost Deals', 'erp-pro' ),
            'day'                           => __( 'Day', 'erp-pro' ),
            'today'                         => __( 'Today', 'erp-pro' ),
            'yesterday'                     => __( 'Yesterday', 'erp-pro' ),
            'month'                         => __( 'Month', 'erp-pro' ),
            'thisMonth'                     => __( 'This month', 'erp-pro' ),
            'lastMonth'                     => __( 'Last month', 'erp-pro' ),
            'week'                          => __( 'Week', 'erp-pro' ),
            'thisWeek'                      => __( 'This week', 'erp-pro' ),
            'lastWeek'                      => __( 'Last week', 'erp-pro' ),
            'thisYear'                      => __( 'This year', 'erp-pro' ),
            'lastYear'                      => __( 'Last year', 'erp-pro' ),
            'dealProgress'                  => __( 'Deal Progress', 'erp-pro' ),
            'allAgents'                     => __( 'All Agents', 'erp-pro' ),
            'allOwners'                     => __( 'All Owners', 'erp-pro' ),
            'countsOfDealsReachedTheStage'  => __( 'Counts of deals reached the stage', 'erp-pro' ),
            'valuesOfDealsReachedTheStage'  => __( 'Values of deals reached the stage', 'erp-pro' ),
            'averageDealValue'              => __( 'Average deal value', 'erp-pro' ),
            'avrgTimeToReachStg'            => __( 'Average time until the stage reached (days)', 'erp-pro' ),
            'noStatFound'                   => __( 'No statistic found. Try different filter.', 'erp-pro' ),
            'count'                         => __( 'Count', 'erp-pro' ),
            'total'                         => __( 'Total', 'erp-pro' ),
            'activityProgress'              => __( 'Activity Progress', 'erp-pro' ),
            'open'                          => __( 'Open', 'erp-pro' ),
            'mostRecentOpenDeals'           => __( 'Most recent open deals', 'erp-pro' ),
            'mostRecentWonDeals'            => __( 'Most recent won deals', 'erp-pro' ),
            'noDealFound'                   => __( 'No deal found', 'erp-pro' ),
            'newDealPeopleError'            => __( 'You have to input either contact or company name', 'erp-pro' ),
            'noCompLinkedToDealMsg'         => __( 'No company is linked to this deal', 'erp-pro' ),
            'noContLinkedToDealMsg'         => __( 'No contact is linked to this deal', 'erp-pro' ),
            'removeDealCompWarnMsg'         => __( 'Are you sure you want to remove this company from this deal?', 'erp-pro' ),
            'removeDealContWarnMsg'         => __( 'Are you sure you want to remove this contact from this deal?', 'erp-pro' ),
            'contactRemoved'                => __( 'removed contact', 'erp-pro' ),
            'companyRemoved'                => __( 'removed company', 'erp-pro' ),
            'contactAdded'                  => __( 'added contact', 'erp-pro' ),
            'companyAdded'                  => __( 'added company', 'erp-pro' ),
            'sentAnEmailTo'                 => __( 'sent an email to', 'erp-pro' ),
            'on'                            => __( 'on', 'erp-pro' ),
            'repliedTo'                     => __( 'replied to', 'erp-pro' ),
            'reply'                         => __( 'Reply', 'erp-pro' ),
            'replyMessage'                  => __( 'Reply Message', 'erp-pro' ),
            'selectEmailTemplate'           => __( 'select email template', 'erp-pro' ),
            'expCloseDate'                  => __( 'Exp. close date', 'erp-pro' ),
            'createdAt'                     => __( 'Created at', 'erp-pro' ),
            'dealHasNoActMsg'               => __( 'This deal has no activity', 'erp-pro' ),
            'lostAt'                        => __( 'Lost at', 'erp-pro' ),
            'wonAt'                         => __( 'Won at', 'erp-pro' ),
        ];
    }

    /**
     * Add plugin settings area in CRM settings tab
     *
     * @since 1.0.0
     *
     * @param array $sections
     *
     * @return array
     */
    public function register_settings_subsection( $sections ) {
        $sections['erp_deals'] = __( 'Deals', 'erp-pro' );

        return $sections;
    }

    /**
     * Settings fields for deals
     *
     * @since 1.0.0
     *
     * @param array  $fields
     * @param string $section
     *
     * @return array
     */
    public function register_settings_fields( $fields, $section ) {

        $fields['erp_deals'][] = [
            'type'  => 'title',
        ];

        $fields['erp_deals'][] = [
            'type'  => 'deal_settings_fields',
            'id'    => 'deal_settings_fields'
        ];

        $fields['erp_deals'][] = [
            'type' => 'sectionend',
            'id'   => 'script_styling_options'
        ];

        return $fields;
    }

    /**
     * Special field type for settings field
     *
     * @since 1.0.0
     *
     * @return void
     */
    public function register_settings_field_type() {
        require_once WPERP_DEALS_VIEWS . '/settings-page.php';
    }
}

new Admin();
