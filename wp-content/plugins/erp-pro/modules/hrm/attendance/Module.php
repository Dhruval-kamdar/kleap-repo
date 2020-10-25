<?php
namespace weDevs\ERP_PRO\HRM\Attendance;
/**
 * Plugin Name: WP ERP - Attendance
 * Description: Employee Attendance Add-On for WP ERP
 * Plugin URI: http://wperp.com/downloads/attendance
 * Author: weDevs
 * Author URI: http://wedevs.com
 * Version: 2.0.3
 * License: GPL2
 * Text Domain: erp-attendance
 * Domain Path: languages
 *
 * Copyright (c) 2016 weDevs (email: info@wperp.com). All rights reserved.
 *
 * Released under the GPL license
 * http://www.opensource.org/licenses/gpl-license.php
 *
 * This is an add-on for WordPress
 * http://wordpress.org/
 *
 * **********************************************************************
 * This program is free software; you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation; either version 2 of the License, or
 * (at your option) any later version.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License
 * along with this program; if not, write to the Free Software
 * Foundation, Inc., 51 Franklin St, Fifth Floor, Boston, MA  02110-1301  USA
 * **********************************************************************
 */

// don't call the file directly
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

class Module {

	/**
	 * Add-on Version
	 *
	 * @var  string
	 */
	public $version = '2.0.3';

	/**
	 * Initializes the WeDevs_ERP_HR_Attendance class
	 *
	 * Checks for an existing WeDevs_ERP_HR_Attendance instance
	 * and if it doesn't find one, creates it.
	 */
	public static function init() {

		static $instance = false;

		if ( ! $instance ) {
			$instance = new self();
		}

		return $instance;
	}

	/**
	 * Constructor for the WeDevs_ERP_HR_Attendance class
	 *
	 * Sets up all the appropriate hooks and actions
	 */
	private function __construct() {
		//Define constants
		$this->define_constants();

		include WPERP_ATTEND_INCLUDES . '/class-install.php';
		add_action( 'erp_loaded', [ $this, 'plugin_init' ] );
	}

	/**
	 * Execute if ERP is installed
	 *
	 * @return null
	 */
	public function plugin_init() {
		// Include files
		$this->includes();

		// Instantiate classes
		$this->init_classes();

		// Init action hooks
		$this->init_actions();

		// Init filter hooks
		$this->init_filters();
	}

	/**
	 * Define Add-on constants
	 *
	 * @return void
	 */
	public function define_constants() {
		if ( defined( 'WPERP_ATTEND_VERSION' ) ) {
			return;
		}

		define( 'WPERP_ATTEND_VERSION', $this->version );
		define( 'WPERP_ATTEND_FILE', __FILE__ );
		define( 'WPERP_ATTEND_PATH', dirname( WPERP_ATTEND_FILE ) );
		define( 'WPERP_ATTEND_INCLUDES', WPERP_ATTEND_PATH . '/includes' );
		define( 'WPERP_ATTEND_URL', plugins_url( '', WPERP_ATTEND_FILE ) );
		define( 'WPERP_ATTEND_ASSETS', WPERP_ATTEND_URL . '/assets' );
		define( 'WPERP_ATTEND_VIEWS', WPERP_ATTEND_PATH . '/views' );
		define( 'WPERP_ATTEND_JS_TMPL', WPERP_ATTEND_VIEWS . '/js-templates' );
	}

	/**
	 * Include the required files
	 *
	 * @return void
	 */
	public function includes() {
        require_once WPERP_ATTEND_INCLUDES . '/class-assets.php';

        if ( $this->is_request( 'admin' ) ) {
            require_once WPERP_ATTEND_INCLUDES . '/class-admin.php';
        }

        if ( $this->is_request( 'frontend' ) ) {
            require_once WPERP_ATTEND_INCLUDES . '/class-frontend.php';
        }

        if ( $this->is_request( 'ajax' ) ) {
            // require_once WPERP_ATTEND_INCLUDES . '/class-ajax.php';
        }

        if ( $this->is_request( 'rest' ) ) {
            require_once WPERP_ATTEND_INCLUDES . '/class-rest-api.php';
        }

		include_once WPERP_ATTEND_INCLUDES . '/class-attendance.php';
		include_once WPERP_ATTEND_INCLUDES . '/class-att-report-employee.php';
        include_once WPERP_ATTEND_INCLUDES . '/class-attendance-single.php';
        include_once WPERP_ATTEND_INCLUDES . '/class-manage-shifts.php';
        include_once WPERP_ATTEND_INCLUDES . '/class-att-shift-employee.php';
		include_once WPERP_ATTEND_INCLUDES . '/class-ip-utils.php';
		include_once WPERP_ATTEND_INCLUDES . '/functions-attendance.php';
		include_once WPERP_ATTEND_INCLUDES . '/functions-shift.php';
		include_once WPERP_ATTEND_INCLUDES . '/class-ajax.php';
		include_once WPERP_ATTEND_INCLUDES . '/class-form-handler.php';
		include_once WPERP_ATTEND_INCLUDES . '/class-widgets.php';
		include_once WPERP_ATTEND_INCLUDES . '/class-notification.php';
		include_once WPERP_ATTEND_INCLUDES . '/emails/class-emails.php';
		include_once WPERP_ATTEND_INCLUDES . '/emails/class-email-att-reminder.php';
		include_once WPERP_ATTEND_INCLUDES . '/api/class-attendance-controller.php';
        include_once WPERP_ATTEND_INCLUDES . '/class-action-filter.php';
        // queries
        include_once WPERP_ATTEND_INCLUDES . '/queries/class-attendance-by-date.php';

		//updater
		include_once WPERP_ATTEND_INCLUDES . '/wp-async-request.php';
		include_once WPERP_ATTEND_INCLUDES . '/wp-background-process.php';

		//updater
		include_once WPERP_ATTEND_INCLUDES . '/updates/bp/att-migrate-attendance.php';
		include_once WPERP_ATTEND_INCLUDES . '/class-updates.php';
		//export import
		require_once WPERP_INCLUDES . '/lib/parsecsv.lib.php';
	}

	/**
	 * Registers all the scripts to ERP init
	 *
	 * @since 1.1.1 Change the name from `register_scripts` to `attendance_scripts`.
	 *              Load scripts in specific pages
	 *
	 * @return void
	 */
	public function attendance_scripts( $hook ) {
		$hook = str_replace( sanitize_title( __( 'Attendance', 'erp-pro' ) ), 'attendance', $hook );

		$attendance_pages = [
			'toplevel_page_erp-hr-attendance',
			// 'admin_page_erp-new-attendance',
            // 'admin_page_erp-edit-attendance',
            // 'admin_page_erp-new-shift',
            // 'admin_page_erp-edit-shift',
			'hr-management_page_erp-hr-employee',
			'hr-management_page_erp-hr-reporting',
			// 'attendance_page_erp-hr-shifts',
			'attendance_page_erp-shfit-exim',
			'hr-management_page_erp-hr-my-profile'
		];

		if ( ! in_array( $hook, $attendance_pages ) ) {
			return;
		}

		wp_register_script( 'erp-att-sortablejs', WPERP_ATTEND_ASSETS . '/js/sortable.min.js', [], WPERP_ATTEND_VERSION, true );
        wp_register_script( 'erp-att-vuedraggable', WPERP_ATTEND_ASSETS . '/js/vuedragablefor.min.js', [], WPERP_ATTEND_VERSION, true );

		// Enqueue jQuery timepicker
		wp_enqueue_style( 'erp-timepicker' );

		// Enqueue main css style
		wp_enqueue_style( 'erp-attendance-main-style', WPERP_ATTEND_ASSETS . '/css/attendance.css' );

		if ( ! is_admin() ) {
			wp_enqueue_style( 'erp-attendance-frontend', WPERP_ATTEND_ASSETS . '/css/erp-attendance-frontend.css', [ 'erp-attendance-main-style' ], WPERP_ATTEND_VERSION );
		}

		// Register jQuery flot stack chart
		wp_register_script( 'erp-att-flot-stack', WPERP_ATTEND_ASSETS . '/js/jquery.flot.stack.js', [ 'erp-flotchart' ], WPERP_ATTEND_VERSION, true );

		// Register jQuery flot chart tick rotator
		wp_register_script( 'erp-att-flot-tickrotator', WPERP_ATTEND_ASSETS . '/js/jquery.flot.tickrotator.js', [ 'erp-flotchart' ], WPERP_ATTEND_VERSION, true );
		wp_enqueue_script( 'plot', '//cdn.jsdelivr.net/jquery.flot/0.8.3/jquery.flot.min.js', [ 'jquery' ], '', false );
		// Enqueue main js script
		wp_enqueue_script( 'erp-attendance-main-script', WPERP_ATTEND_ASSETS . '/js/attendance.js', [
			'jquery',
			'erp-momentjs',
			'jquery-ui-datepicker',
			'erp-timepicker',
			'erp-flotchart',
			'erp-flotchart-pie',
			'erp-att-flot-stack',
			'erp-att-flot-tickrotator',
			'erp-flotchart-time',
			'erp-flotchart-tooltip',
			'erp-flotchart-axislables',
			'erp-vuejs'
		], WPERP_ATTEND_VERSION, true );

		// if ( 'attendance_page_erp-hr-shifts' === $hook ) {
		// 	wp_register_script( 'erp-att-lodash', WPERP_ATTEND_ASSETS . '/js/lodash.min.js', [], WPERP_ATTEND_VERSION, true );
		// 	wp_enqueue_script( 'erp-shift-script', WPERP_ATTEND_ASSETS . '/js/shift.js', [ 'erp-attendance-main-script', 'erp-att-sortablejs', 'erp-att-lodash', 'erp-att-vuedraggable' ], WPERP_ATTEND_VERSION, true );
        // }

        // if ( 'admin_page_erp-new-shift' === $hook || 'admin_page_erp-edit-shift' === $hook ) {
        //     wp_enqueue_script( 'erp-new-shift-script', WPERP_ATTEND_ASSETS . '/js/new-shift.js', [], WPERP_ATTEND_VERSION, true );
        // }

		wp_enqueue_style( 'erp-timepicker' );

		$localize_scripts = [
			'scriptDebug'          => defined( 'SCRIPT_DEBUG' ) ? SCRIPT_DEBUG : false,
			'att_main_url'         => admin_url( 'admin.php?page=erp-hr-attendance' ),
			'att_shifts_list'      => admin_url( 'admin.php?page=erp-hr-shifts' ),
			'current_date'         => current_time( 'Y-m-d' ),
			'utc_offset'           => get_option( 'gmt_offset' ),
			'nonce'                => wp_create_nonce( 'wp-erp-attendance' ),
			'hook'                 => $hook,
			'shift_delete_warning' => __( "This shift and related attendance record will be deleted permanently and can't be undone. Are you sure?", 'erp-pro' ),
			'popup'                => [
				'attendanceNew'          => __( 'New Attendance', 'erp-pro' ),
				'attendanceNewSubmit'    => __( 'Submit Attendance', 'erp-pro' ),
				'attendanceEdit'         => __( 'Edit Attendance', 'erp-pro' ),
				'attendanceEditSubmit'   => __( 'Save Changes', 'erp-pro' ),
				'attendanceImport'       => __( 'Import Attendance', 'erp-pro' ),
				'attendanceImportSubmit' => __( 'Import', 'erp-pro' ),
			],
			'alert'                => [
				'somethingWrong' => __( 'Something went wrong', 'erp-pro' )
			],
			'selfService'          => [
				'checkoutMsg' => __( 'Do you want to checkout?', 'erp-pro' )
			]
		];

		if ( 'hr-management_page_erp-hr-employee' == $hook ) {

			$localize_scripts['user_id'] = isset( $_REQUEST['id'] ) ? $_REQUEST['id'] : '';
		}

		if ( isset( $_REQUEST['page'] ) && 'erp-edit-attendance' == $_REQUEST['page'] ) {
			$localize_scripts['current_date'] = esc_attr( $_REQUEST['edit_date'] );
		}

		// Localize scripts
		wp_localize_script( 'erp-attendance-main-script', 'wpErpAttendance', $localize_scripts );
	}

	/**
	 * Initialize the classes
	 *
	 * @since 1.0
	 *
	 * @return void
	 */
	public function init_classes() {
        if ( $this->is_request( 'admin' ) ) {
            $this->container['admin'] = new \App\Admin();
        }

        if ( $this->is_request( 'frontend' ) ) {
            $this->container['frontend'] = new \App\Frontend();
        }

        if ( $this->is_request( 'ajax' ) ) {
            // $this->container['ajax'] =  new \App\Ajax();
        }

        if ( $this->is_request( 'rest' ) ) {
            $this->container['rest'] = new \App\REST_API();
        }

        $this->container['assets'] = new \App\Assets();

		if ( defined( 'DOING_AJAX' ) && DOING_AJAX ) {
			new \WeDevs\ERP\HRM\Attendance\Ajax();
		}

		// Widget instance
		new \WeDevs\ERP\HRM\Attendance\Widgets();
		// Init mailer
		new \WeDevs\ERP\HRM\Attendance\Emailer();
		// Init notification
		new \WeDevs\ERP\HRM\Attendance\Notification();
		// Updater class
		new \WeDevs\ERP\HRM\Attendance\Updates();

		//migration v2
		//new \WeDevs\ERP\HRM\Attendance\Updates\ERP_Att_Migrate_Attendance();

        new \WeDevs\ERP\HRM\Attendance\ActionFilter();
	}

	/**
	 * Initializes action hooks to ERP
	 *
	 * @return void
	 */
	public function init_actions() {

		// Add attendance menu
//		add_action( 'admin_menu', [ $this, 'add_menu' ], 99 );

		// Load JS Templates
		// add_action( 'admin_footer', [ $this, 'admin_js_templates' ] );

		// Enqueue script files
		add_action( 'admin_enqueue_scripts', [ $this, 'attendance_scripts' ] );

		// Enqueue script files
		add_action( 'wp_enqueue_scripts', [ $this, 'attendance_scripts' ], 10 );

		// Adds a tab in a single employee page
		add_action( 'erp_hr_employee_single_tabs', [ $this, 'erp_hr_employee_single_attendance_callback' ], 12, 2 );

		// Attendance table bulk actions
		add_action( 'load-hr-management_page_erp-hr-attendance', [ $this, 'attendance_bulk_action' ] );

		// add frontend script
        add_action( 'erp_hr_frontend_load_script', [ $this, 'load_frontend_script' ] );

        // update day_type for user
        //add_action( 'erp_hr_leave_request_approved', [ $this, 'update_day_type_for_user' ], 10, 2 );
        //add_action( 'erp_hr_leave_request_pending', [ $this, 'update_employee_shift_by_leave_pending' ], 10, 2 );
	}

	/**
	 * Initializes action hooks to ERP
	 *
	 * @return void
	 */
	public function init_filters() {
		// Add a section to HR Settings
		add_filter( 'erp_settings_hr_sections', [ $this, 'add_att_sections' ] );

		// Add fields to ERP Settings Attendance section
		add_filter( 'erp_settings_hr_section_fields', [ $this, 'add_att_section_fields' ], 10, 2 );

		// Attendance tab in HR Settings
		add_filter( 'erp_hr_settings_tabs', [ $this, 'attendance_settings_page' ] );

		// Adds an option for Attendance report in HR reporting page
		add_filter( 'erp_hr_reports', [ $this, 'attendance_report' ] );

		// Creates a separate report page for attendance report
		add_filter( 'erp_hr_reporting_pages', [ $this, 'attendance_report_page' ], 10, 2 );

		// Add api support
		add_filter( 'erp_rest_api_controllers', [ $this, 'load_attendance_api_controller' ] );

        // Plugin action links
        add_filter( 'plugin_action_links_' . plugin_basename(__FILE__), [ $this, 'plugin_action_links' ] );
	}

    /**
     * Add action links
     *
     * @param $links
     *
     * @return array
     */
    public function plugin_action_links( $links ) {
        if ( version_compare( WPERP_VERSION, '1.4.0', '<' ) ) {
            $links[] = '<a href="' . admin_url( 'admin.php?page=erp-hr&section=attendance' ) . '">' . __( 'Manage Attendance', 'erp-pro' ) . '</a>';
        }

        $links[] = '<a href="' . admin_url( 'admin.php?page=erp-settings&tab=erp-hr&section=attendance' ) . '">' . __( 'Settings', 'erp-pro' ) . '</a>';
        return $links;
    }

	/**
	 * Attendance Main Page
	 *
	 * @since 1.0
	 *
	 * @return void
	 */
	// public function attendance_main_callback() {

	// 	$action = isset( $_GET['q'] ) ? $_GET['q'] : 'list';
	// 	$id     = isset( $_GET['id'] ) ? $_GET['id'] : 0;

	// 	switch ( $action ) {

	// 		case 'view':
	// 			$template = WPERP_ATTEND_VIEWS . '/attendance-single.php';
	// 			break;

	// 		default:
	// 			$template = WPERP_ATTEND_VIEWS . '/attendance.php';
	// 			break;
	// 	}

	// 	if ( file_exists( $template ) ) {

	// 		include $template;
	// 	}
	// }

	/**
	 * Attendace Shifts Page
	 *
	 * @since 1.2
	 *
	 * @return void
	 */
	public function attendance_manage_shifts() {
		include WPERP_ATTEND_VIEWS . '/manage-shifts-new.php';
	}

	/**
	 * Attendance import export
	 *
	 * @since 1.2
	 *
	 * @return void
	 */
	public function attendance_import_export() {
		include WPERP_ATTEND_VIEWS . '/import.php';
	}

	/**
	 * Include new attendance page
	 */
	// public function new_attendance() {
	// 	include WPERP_ATTEND_VIEWS . '/new-attendance.php';
	// }

	/**
	 * Include edit attendance page
	 */
	// public function edit_attendance() {
	// 	include WPERP_ATTEND_VIEWS . '/edit-attendance.php';
    // }

    /**
     * Include new shift page
     */
    // public function new_shift() {
    //     include WPERP_ATTEND_VIEWS . '/new-shift.php';
    // }

    /**
     * Include edit shift page
     */
    // public function edit_shift() {
    //     global $wpdb;

    //     $shift_id     = absint( $_GET['shift'] );

    //     $shifts       = erp_attendance_get_shifts();
    //     $shift        = erp_attendance_get_shift( $shift_id );

    //     $employees    = erp_hr_get_employees( [ 'number' => -1, 'no_object' => true ] );
    //     $departments  = erp_hr_get_departments( [ 'number' => -1, 'no_object' => true ] );
    //     $designations = erp_hr_get_designations( [ 'number' => -1, 'no_object' => true ] );

    //     if ( empty( $shift ) ) {
    //         die( '<h3>Shift not found</h3>' );
    //     }

    //     $shift_name   = $shift->name;

    //     include WPERP_ATTEND_VIEWS . '/edit-shift.php';
    // }

	/**
	 * Load JS Templates to appropriate page
	 *
	 * @since 1.0
	 *
	 * @return null
	 */
// 	public function admin_js_templates() {

// 		global $current_screen;

// 		switch ( $current_screen->base ) {

// 			case 'toplevel_page_erp-hr-attendance':
// 				erp_get_js_template( WPERP_ATTEND_JS_TMPL . '/attendance-new.php', 'erp-attendance-new' );
// //                erp_get_js_template( WPERP_ATTEND_JS_TMPL . '/attendance-import.php', 'erp-attendance-import' );
// 				break;

// //            case 'erp-settings_page_erp-settings':
// //                erp_get_js_template( WPERP_ATTEND_JS_TMPL . '/shift-new.php', 'erp-shift-new' );

// 			default:
// 				break;
// 		}
// 	}

	/**
	 * fucntion for Attendage Setting Tab
	 *
	 * @since 1.0
	 *
	 * @return mixed
	 */
	public function attendance_settings_page( $tabs ) {

		$tabs['attendance'] = [
			'title'    => __( 'Attendance', 'erp-pro' ),
			'callback' => array( $this, 'attendance_tab' )
		];

		return $tabs;
	}

	/**
	 * Attendance Tab in HR Settings
	 */
	public function attendance_tab() {
		include WPERP_ATTEND_VIEWS . '/tab-hr-settings-attendance.php';
	}

	/**
	 * Register Attendance Tab in Employee profile
	 */
	public function erp_hr_employee_single_attendance_callback( $tabs, $employee ) {

		// only show if HR manager or Valid employee
		if ( get_current_user_id() == $employee->id || current_user_can( 'erp_hr_manager' ) ) {
			$tabs['attendance'] = [
				'title'    => __( 'Attendance', 'erp-pro' ),
				'callback' => [ $this, 'erp_hr_employee_single_attendance_tab' ]
			];
		}

		return $tabs;
	}

	/**
	 *
	 */
	public function erp_hr_employee_single_attendance_tab() {
		include WPERP_ATTEND_VIEWS . '/tab-employee-single-status.php';
	}

	/**
	 * Check is current page actions
	 *
	 * @since 0.1
	 *
	 * @param  integer $page_id
	 * @param  integer $bulk_action
	 *
	 * @return boolean
	 */
	public function verify_current_page_screen( $page_id, $bulk_action ) {

		if ( ! isset( $_REQUEST['_wpnonce'] ) || ! isset( $_GET['page'] ) ) {
			return false;
		}

		if ( $_GET['page'] != $page_id ) {
			return false;
		}

		if ( ! wp_verify_nonce( $_REQUEST['_wpnonce'], $bulk_action ) ) {
			return false;
		}

		return true;
	}

	/**
	 *
	 */
	public function attendance_bulk_action() {

		if ( ! $this->verify_current_page_screen( 'erp-hr-attendance', 'bulk-attendances' ) ) {
			return;
		}

		$attendance_table = new \WeDevs\ERP\HRM\Attendance\Attendance();
		$action           = $attendance_table->current_action();

		if ( $action ) {

			$redirect = remove_query_arg( array( '_wp_http_referer', '_wpnonce' ), wp_unslash( $_SERVER['REQUEST_URI'] ) );

			switch ( $action ) {

				case 'filter_attendance':
					$redirect = remove_query_arg( array( 'filter_attendance', 'action', 'action2' ), $redirect );
					wp_redirect( $redirect );
					exit;

				default:
					exit;
			}
		}
	}

	/**
	 * Add attendance report to HR reporting page
	 *
	 * @param  $reports  array
	 *
	 * @return array
	 */
	public function attendance_report( $reports ) {
		$reports['attendance-report'] = [
			'title'       => __( 'Attendance (Date Based)', 'erp-pro' ),
			'description' => __( 'Reporting on employee attendance', 'erp-pro' )
		];

		$reports['att-report-employee'] = [
			'title'       => __( 'Attendance (Employee Based)', 'erp-pro' ),
			'description' => __( 'Reporting on employee attendance', 'erp-pro' )
		];

		return $reports;
	}

	/**
	 * Creates a separate page for attendance report
	 *
	 * @return mixed
	 */
	public function attendance_report_page( $template, $action ) {

		if ( 'attendance-report' == $action ) {
			$template = WPERP_ATTEND_VIEWS . '/attendance-reporting.php';
		} elseif ( 'att-report-employee' == $action ) {
			$template = WPERP_ATTEND_VIEWS . '/att-report-employee.php';
		}


		return $template;
	}

	/**
	 * Add Attendance Sections to ERP Settings Page
	 *
	 * @since 1.0
	 *
	 * @return array
	 */
	public function add_att_sections( $sections ) {

		$sections ['attendance'] = __( 'Attendance', 'erp-pro' );

		return $sections;
	}

	/**
	 * Add fields to Attendance Section in ERP Fields
	 *
	 * @since 1.0
	 *
	 * @return array
	 */
	public function add_att_section_fields( $fields, $section ) {

		if ( 'attendance' == $section ) {

			$fields['attendance'] = [
				[
					'title' => __( 'Grace Time', 'erp-pro' ),
					'type'  => 'title',
					'id'    => 'erp_att_grace'
				],
				[
					'title'   => __( 'Grace Before Checkin', 'erp-pro' ),
					'type'    => 'text',
					'id'      => 'grace_before_checkin',
					'desc'    => __( '(in minute) this time will not counted as overtime', 'erp-pro' ),
					'default' => 15
				],
				[
					'title'   => __( 'Grace After Checkin', 'erp-pro' ),
					'type'    => 'text',
					'id'      => 'grace_after_checkin',
					'desc'    => __( '(in minute) this time will not counted as late', 'erp-pro' ),
					'default' => 15
				],
                [
                    'title'   => __( 'Threshhold between checkout & checkin', 'erp-pro' ),
                    'type'    => 'text',
                    'id'      => 'erp_att_diff_threshhold',
                    'desc'    => __( '(in second) this time will prevent quick checkin after making a checkout', 'erp-pro' ),
                    'default' => 60
                ],
				[
					'title'   => __( 'Grace Before Checkout', 'erp-pro' ),
					'type'    => 'text',
					'id'      => 'grace_before_checkout',
					'desc'    => __( '(in minute) this time will not counted as early left', 'erp-pro' ),
					'default' => 15
				],
				[
					'title'   => __( 'Grace After Checkout', 'erp-pro' ),
					'type'    => 'text',
					'id'      => 'grace_after_checkout',
					'desc'    => __( '(in minute) this time will not counted as overtime', 'erp-pro' ),
					'default' => 15
				],
				[
					'title' => __( 'Self Attendance', 'erp-pro' ),
					'type'  => 'checkbox',
					'id'    => 'enable_self_att',
					'desc'  => __( 'Enable self attendance service for employees?', 'erp-pro' )
				],
				[
					'title' => __( 'IP Restriction', 'erp-pro' ),
					'type'  => 'checkbox',
					'id'    => 'erp_at_enable_ip_restriction',
					'desc'  => __( 'Enable IP restriction for checkin/checkout', 'erp-pro' )
				],
				[
					'title'             => __( 'Whitelisted IP\'s', 'erp-pro' ),
					'type'              => 'textarea',
					'id'                => 'erp_at_whitelisted_ips',
					'desc'              => __( 'Employees from this IP addresss will be able to self check-in. Put one IP in each line', 'erp-pro' ),
					'custom_attributes' => [
						'rows' => 4,
						'cols' => 45
					]
				],
				[
					'title' => __( 'Attendance Reminder', 'erp-pro' ),
					'type'  => 'checkbox',
					'id'    => 'attendance_reminder',
					'desc'  => __( 'Send email notification to remind Checking-in', 'erp-pro' )
				],
			];

			$fields['attendance'][] = [
				'type' => 'sectionend',
				'id'   => 'script_styling_options'
			];

		}

		return $fields;
	}

	/**
	 * Load scripts in frontend
	 *
	 * @since 1.1.1
	 *
	 * @return void
	 */
	public function load_frontend_script() {
		$this->attendance_scripts( 'toplevel_page_erp-hr-attendance' );
    }

    /**
     * Update day_type for user in date_shift_table
     *
     * @return void
     */
    public function update_day_type_for_user( $request_id, $request ) {
        global $wpdb;

        $sql = sprintf( "UPDATE {$wpdb->prefix}erp_attendance_date_shift
                SET day_type = 'leave'
                WHERE user_id = %d
                AND date BETWEEN '%s' AND '%s'
                AND day_type = 'working_day'",
                absint( $request['user_id'] ),
                date( 'Y-m-d', strtotime( $request['start_date'] ) ),
                date( 'Y-m-d', strtotime( $request['end_date'] ) )
            );

        $wpdb->query( $sql );
    }

	/**
	 * Register api files
	 * @since 1.1.3
	 *
	 * @param $controllers
	 *
	 * @return array
	 */
	public function load_attendance_api_controller( $controllers ) {
		$controllers[] = 'WeDevs\ERP\HRM\Attendance\Attendance_Controller';

		return $controllers;
    }

    private function is_request( $type ) {
        switch ( $type ) {
            case 'admin' :
                return is_admin();
            case 'ajax' :
                return defined( 'DOING_AJAX' );
            case 'rest' :
                return defined( 'REST_REQUEST' );
            case 'cron' :
                return defined( 'DOING_CRON' );
            case 'frontend' :
                return ( ! is_admin() || defined( 'DOING_AJAX' ) ) && ! defined( 'DOING_CRON' );
        }
    }

    public function update_employee_shift_by_leave_pending ( $request_id, $request ) {
        global $wpdb;

        $sql = sprintf( "UPDATE {$wpdb->prefix}erp_attendance_date_shift
                SET day_type = 'working_day'
                WHERE user_id = %d
                AND date BETWEEN '%s' AND '%s'
                AND day_type = 'leave'",
            absint( $request['user_id'] ),
            date( 'Y-m-d', strtotime( $request['start_date'] ) ),
            date( 'Y-m-d', strtotime( $request['end_date'] ) )
        );

        $wpdb->query( $sql );
    }

}
