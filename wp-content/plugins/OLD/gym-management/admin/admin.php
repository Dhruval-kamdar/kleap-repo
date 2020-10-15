<?php 
 // This is adminside main First page of Gym management management plug in 
add_action( 'admin_head', 'MJ_gmgt_admin_menu_icon' );
//ADMIN MENU ICON FUNCTION
function MJ_gmgt_admin_menu_icon()
{
	?>
	<style type="text/css">
	#adminmenu #toplevel_page_hospital div.wp-menu-image:before {
	  content: "\f512";
	}
	</style>
 <?php 
}
add_action( 'admin_menu', 'MJ_gmgt_system_menu' );
//ADMIN SIDE MENU FUNCTION
function MJ_gmgt_system_menu()
{
	if (function_exists('MJ_gmgt_setup'))  
	{		
		add_menu_page('Gym Management', __('WPGYM','gym_mgt'),'manage_options','gmgt_system','gmgt_system_dashboard',plugins_url('gym-management/assets/images/gym-1.png' )); 
		if($_SESSION['gmgt_verify'] == '')
		{
			add_submenu_page('gmgt_system', __('License Settings','gym_mgt'), __( 'License Settings', 'gym_mgt' ),'manage_options','gmgt_setup','gmgt_options_page');
		} 
		add_submenu_page('gmgt_system', 'Dashboard', __( 'Dashboard', 'gym_mgt' ), 'administrator', 'gmgt_system', 'gmgt_system_dashboard');
		
		add_submenu_page('gmgt_system', 'Membership Type', __( 'Membership Type', 'gym_mgt' ), 'administrator', 'gmgt_membership_type', 'membership_manage');
		
		add_submenu_page('gmgt_system', 'Group', __( 'Groups', 'gym_mgt' ), 'administrator', 'gmgt_group', 'group_manage');
		
		add_submenu_page('gmgt_system', 'Staff Members', __( 'Staff Members', 'gym_mgt' ), 'administrator', 'gmgt_staff', 'staff_manage');
		
		add_submenu_page('gmgt_system', 'Class Schedule', __( 'Class Schedule', 'gym_mgt' ), 'administrator', 'gmgt_class', 'class_manage');
		
		add_submenu_page('gmgt_system', 'Member', __( 'Member', 'gym_mgt' ), 'administrator', 'gmgt_member', 'member_manage');
		
		add_submenu_page('gmgt_system', 'Activity', __( 'Activity', 'gym_mgt' ), 'administrator', 'gmgt_activity', 'activity_manage');
		
		add_submenu_page('gmgt_system', 'Assign Workout', __( 'Assign Workout', 'gym_mgt' ), 'administrator', 'gmgt_workouttype', 'workouttype_manage');
		
		add_submenu_page('gmgt_system', 'Nutrition Schedule', __( 'Nutrition Schedule', 'gym_mgt' ), 'administrator', 'gmgt_nutrition', 'nutrition_manage');
		
		add_submenu_page('gmgt_system', 'Daily Workout', __( 'Daily Workout', 'gym_mgt' ), 'administrator', 'gmgt_workout', 'workout_manage');
		
		add_submenu_page('gmgt_system', 'Product', __( 'Product', 'gym_mgt' ), 'administrator', 'gmgt_product', 'product_manage');
		
		add_submenu_page('gmgt_system', 'Store', __( 'Store', 'gym_mgt' ), 'administrator', 'gmgt_store', 'store_manage');
				
		add_submenu_page('gmgt_system', 'Reservation', __( 'Reservation', 'gym_mgt' ), 'administrator', 'gmgt_reservation', 'reservation_manage');
		
		add_submenu_page('gmgt_system', 'Attendance', __( 'Attendance', 'gym_mgt' ), 'administrator', 'gmgt_attendence', 'attendence_manage');
		
		add_submenu_page('gmgt_system', 'Accountant', __( 'Accountant', 'gym_mgt' ), 'administrator', 'gmgt_accountant', 'accountant_manage');
		add_submenu_page('gmgt_system', 'Tax', __( 'Tax', 'gym_mgt' ), 'administrator', 'gmgt_taxes', 'gmgt_taxes');
		add_submenu_page('gmgt_system', 'Fees Payment', __( 'Membership Payment', 'gym_mgt' ), 'administrator', 'gmgt_fees_payment', 'gmgt_fees_payment');
		add_submenu_page('gmgt_system', 'Payment', __( 'Payment', 'gym_mgt' ), 'administrator', 'gmgt_payment', 'payment_manage');
		
		add_submenu_page('gmgt_system', 'Message', __( 'Message', 'gym_mgt' ), 'administrator', 'Gmgt_message', 'message_manage');
		
		add_submenu_page('gmgt_system', 'Newsletter', __( 'Newsletter', 'gym_mgt' ), 'administrator', 'gmgt_newsletter', 'newsletter_manage');
		
		add_submenu_page('gmgt_system', 'Notice', __( 'Notice', 'gym_mgt' ), 'administrator', 'gmgt_notice', 'notice_manage');
		
		add_submenu_page('gmgt_system', 'Report', __( 'Report', 'gym_mgt' ), 'administrator', 'gmgt_report', 'report_manage');
		
		add_submenu_page('gmgt_system', 'Email Template', __( 'Email Template', 'gym_mgt' ), 'administrator', 'gmgt_mail_template', 'mail_template_manage');
		
		add_submenu_page('gmgt_system', 'Gnrl_setting', __( 'General Settings', 'gym_mgt' ), 'administrator', 'gmgt_gnrl_settings', 'gym_gnrl_settings');
		
		add_submenu_page('gmgt_system', 'access_right', __( 'Access Right', 'gym_mgt' ), 'administrator', 'gmgt_access_right', 'gym_access_right');
    }  
	else
	{ 		      
		die;
	}
}
//BELOW ALL PAGE CALL BY MENU FUNCTIONS
function gmgt_options_page()
{
	require_once GMS_PLUGIN_DIR. '/admin/setupform/index.php';
}
function gmgt_system_dashboard()
{
	require_once GMS_PLUGIN_DIR. '/admin/dasboard.php';
}	
function membership_manage()
{
	require_once GMS_PLUGIN_DIR. '/admin/membership/index.php';
}
function group_manage()
{
	require_once GMS_PLUGIN_DIR. '/admin/group/index.php';
}
function staff_manage()
{
	require_once GMS_PLUGIN_DIR. '/admin/staff-members/index.php';
}
function accountant_manage()
{
	require_once GMS_PLUGIN_DIR. '/admin/accountant/index.php';
}
function class_manage()
{
	require_once GMS_PLUGIN_DIR. '/admin/class-schedule/index.php';
}
function member_manage()
{
	require_once GMS_PLUGIN_DIR. '/admin/member/index.php';
}
function product_manage()
{
	require_once GMS_PLUGIN_DIR. '/admin/product/index.php';
}
function store_manage()
{
	require_once GMS_PLUGIN_DIR. '/admin/store/index.php';
}
function nutrition_manage()
{
	require_once GMS_PLUGIN_DIR. '/admin/nutrition/index.php';
}
function reservation_manage()
{
	require_once GMS_PLUGIN_DIR. '/admin/reservation/index.php';
}
function attendence_manage()
{
	require_once GMS_PLUGIN_DIR. '/admin/attendence/index.php';
}
function gmgt_taxes()
{
	require_once GMS_PLUGIN_DIR. '/admin/tax/index.php';
}
function gmgt_fees_payment()
{
	require_once GMS_PLUGIN_DIR. '/admin/membership_payment/index.php';
}
function payment_manage()
{
	require_once GMS_PLUGIN_DIR. '/admin/payment/index.php';
}
function message_manage()
{
	require_once GMS_PLUGIN_DIR. '/admin/message/index.php';
}
function newsletter_manage()
{
	require_once GMS_PLUGIN_DIR. '/admin/news-letter/index.php';
}
function activity_manage()
{
	require_once GMS_PLUGIN_DIR. '/admin/activity/index.php';
}
function workouttype_manage()
{
	require_once GMS_PLUGIN_DIR. '/admin/workout-type/index.php';
}
function workout_manage()
{
	require_once GMS_PLUGIN_DIR. '/admin/workout/index.php';
}
function notice_manage()
{
	require_once GMS_PLUGIN_DIR. '/admin/notice/index.php';
}
function report_manage()
{
	require_once GMS_PLUGIN_DIR. '/admin/report/index.php';
}
function mail_template_manage()
{
	require_once GMS_PLUGIN_DIR. '/admin/email-template/index.php';
}
function gym_gnrl_settings()
{
	require_once GMS_PLUGIN_DIR. '/admin/general-settings.php';
}
function gym_access_right()
{
	require_once GMS_PLUGIN_DIR. '/admin/access_right/index.php';
}
?>