<?php
//DATE FORAMTE FUNCTION//
function MJ_gmgt_datepicker_dateformat()
{
	$date_format_array = array(
	'Y-m-d'=>'yy-mm-dd',
	'Y/m/d'=>'yy/mm/dd',
	'd-m-Y'=>'dd-mm-yy',
	'm-d-Y'=>'mm-dd-yy',
	'm/d/Y'=>'mm/dd/yy');
	return $date_format_array;
}
//DATE FORAMTE FUNCTION //
function MJ_gmgt_bootstrap_datepicker_dateformat($key)
{
	$date_format_array = array(
	'yy-mm-dd'=>'yyyy-mm-dd',
	'yy/mm/dd'=>'yyyy/mm/dd',
	'dd-mm-yy'=>'dd-mm-yyyy',
	'mm-dd-yy'=>'mm-dd-yyyy',
	'mm/dd/yy'=>'mm/dd/yyyy');
	return $date_format_array[$key]; 
}
//GET CURENT USER CLASSIS FUNCTION//
function MJ_gmgt_get_current_user_classis($member_id)
{
	global $wpdb;
	$table_memberclass = $wpdb->prefix. 'gmgt_member_class';
	$class_id = array();
	$ClassData = $wpdb->get_results("SELECT * FROM $table_memberclass WHERE member_id=$member_id");
	if(!empty($ClassData))
	{
		foreach($ClassData as $key=>$class_id)
		{
			$classids[]= $class_id->class_id;
		}
		return $classids;
	}		
}
//GET MEMBER_BY_CLASS_ID//
function MJ_gmgt_get_member_by_class_id($class_id)
{
	global $wpdb;
	$table_memberclass = $wpdb->prefix. 'gmgt_member_class';
	return $MemberClass = $wpdb->get_results("SELECT * FROM $table_memberclass WHERE class_id=$class_id ");
}

//GET MEMBERSHIP CLASS FUNCTION//
function MJ_gmgt_get_membership_class($membership_id)
{
	global $wpdb;
	$table_membership = $wpdb->prefix. 'gmgt_membershiptype';
	$result = $wpdb->get_row("Select * from $table_membership where membership_id=$membership_id ");
	return $result;

}
//GET MEMBERSHIP BY CLASS ID FUNCTION
function MJ_gmgt_get_class_id_by_membership_id($membership_id)
{
	global $wpdb;
	$table_gmgt_membership_class = $wpdb->prefix. 'gmgt_membership_class';
	$ClassMetaData = $wpdb->get_results("SELECT * FROM $table_gmgt_membership_class WHERE membership_id=$membership_id");
	$class_id =array();
	foreach($ClassMetaData as $key=>$value)
	{
		$class_id[]=$value->class_id;
	}
	return $class_id;	
}

//GET MEMBERSHIP STATUS FUNCTION
function MJ_gmgt_get_membership_class_status($membership_id)
{
	global $wpdb;
	$table_gmgt_membershiptype = $wpdb->prefix. 'gmgt_membershiptype';
	 $class_limit = $wpdb->get_row("SELECT classis_limit FROM $table_gmgt_membershiptype WHERE membership_id=$membership_id");
	return $class_limit->classis_limit;
}

function MJ_gmgt_get_user_used_membership_class($membership_id,$member_id)
{
	global $wpdb;
	$result=0;
	$tbl_gmgt_booking_class = $wpdb->prefix . 'gmgt_booking_class';
	$begin_date = date('Y-m-d 00:00:00',strtotime(get_user_meta($member_id,'begin_date',true)));	 
	$end_date = date('Y-m-d 00:00:00',strtotime( get_user_meta($member_id,'end_date',true)));
	$sql =  "SELECT COUNT(*) FROM $tbl_gmgt_booking_class WHERE booking_date >= '$begin_date' AND booking_date <= '$end_date' AND member_id=$member_id AND membership_id=$membership_id";	
	$result = $wpdb->get_var($sql);
	return $result;	
}

//GET PHPDATE FORAMTE FUNCTION
function MJ_gmgt_get_phpdateformat($dateformat_value)
{
	$date_format_array = MJ_gmgt_datepicker_dateformat();
	$php_format = array_search($dateformat_value, $date_format_array);  
	return  $php_format;
}

//GET DATE IN DIAPLAY TIME FUNCTION
function MJ_gmgt_getdate_in_input_box($date)
{	
	return date(MJ_gmgt_get_phpdateformat(get_option('gmgt_datepicker_format')),strtotime($date));	
}
//GET CURENCY SYMBOL FUNCTION
function MJ_gmgt_get_currency_symbol( $currency = '' ) 
{		

			switch ( $currency ) 
			{
			case 'AED' :
			$currency_symbol = 'د.إ';
			break;
			case 'AUD' :
			$currency_symbol = '&#36;';
			break;
			case 'CAD' :
			$currency_symbol = 'C&#36;';
			break;
			case 'CLP' :
			case 'COP' :
			case 'HKD' :
			$currency_symbol = '&#36';
			break;
			case 'MXN' :
			$currency_symbol = '&#36';
			break;
			case 'NZD' :
			$currency_symbol = '&#36';
			break;
			case 'SGD' :
			case 'USD' :
			$currency_symbol = '&#36;';
			break;
			case 'BDT':
			$currency_symbol = '&#2547;&nbsp;';
			break;
			case 'BGN' :
			$currency_symbol = '&#1083;&#1074;.';
			break;
			case 'BRL' :
			$currency_symbol = '&#82;&#36;';
			break;
			case 'CHF' :
			$currency_symbol = '&#67;&#72;&#70;';
			break;
			case 'CNY' :
			case 'JPY' :
			case 'RMB' :
			$currency_symbol = '&yen;';
			break;
			case 'CZK' :
			$currency_symbol = '&#75;&#269;';
			break;
			case 'DKK' :
			$currency_symbol = 'kr.';
			break;
			case 'DOP' :
			$currency_symbol = 'RD&#36;';
			break;
			case 'EGP' :
			$currency_symbol = 'EGP';
			break;
			case 'EUR' :
			$currency_symbol = '&euro;';
			break;
			case 'GBP' :
			$currency_symbol = '&pound;';
			break;
			case 'HRK' :
			$currency_symbol = 'Kn';
			break;
			case 'HUF' :
			$currency_symbol = '&#70;&#116;';
			break;
			case 'IDR' :
			$currency_symbol = 'Rp';
			break;
			case 'ILS' :
			$currency_symbol = '&#8362;';
			break;
			case 'INR' :
			$currency_symbol = 'Rs.';
			break;
			case 'ISK' :
			$currency_symbol = 'Kr.';
			break;
			case 'KIP' :
			$currency_symbol = '&#8365;';
			break;
			case 'KRW' :
			$currency_symbol = '&#8361;';
			break;
			case 'MYR' :
			$currency_symbol = '&#82;&#77;';
			break;
			case 'NGN' :
			$currency_symbol = '&#8358;';
			break;
			case 'NOK' :
			$currency_symbol = '&#107;&#114;';
			break;
			case 'NPR' :
			$currency_symbol = 'Rs.';
			break;
			case 'PHP' :
			$currency_symbol = '&#8369;';
			break;
			case 'PLN' :
			$currency_symbol = '&#122;&#322;';
			break;
			case 'PYG' :
			$currency_symbol = '&#8370;';
			break;
			case 'RON' :
			$currency_symbol = 'lei';
			break;
			case 'RUB' :
			$currency_symbol = '&#1088;&#1091;&#1073;.';
			break;
			case 'SEK' :
			$currency_symbol = '&#107;&#114;';
			break;
			case 'THB' :
			$currency_symbol = '&#3647;';
			break;
			case 'TRY' :
			$currency_symbol = '&#8378;';
			break;
			case 'TWD' :
			$currency_symbol = '&#78;&#84;&#36;';
			break;
			case 'UAH' :
			$currency_symbol = '&#8372;';
			break;
			case 'VND' :
			$currency_symbol = '&#8363;';
			break;
			case 'ZAR' :
			$currency_symbol = '&#82;';
			break;
			default :
			$currency_symbol = $currency;
			break;
	}
	return $currency_symbol;

}

function MJ_gmgt_gym_change_dateformat($date)
{
	return mysql2date(get_option('date_format'),$date);
}

function MJ_gmgt_check_table_isempty($tablename)
{
     global	$wpdb;
	return $rows=$wpdb->get_row("select * from ".$tablename);	 
}
//GET REMOTE FILE FUNCTION

function MJ_gmgt_get_remote_file($url, $timeout = 30)
{
	$ch = curl_init();
	curl_setopt ($ch, CURLOPT_URL, $url);
	curl_setopt ($ch, CURLOPT_RETURNTRANSFER, 1);
	curl_setopt ($ch, CURLOPT_CONNECTTIMEOUT, $timeout);
	$file_contents = curl_exec($ch);
	curl_close($ch);
	return ($file_contents) ? $file_contents : FALSE;
}
//CHANGE MENU IN FRONTEND SIDE FUNCTION
function MJ_gmgt_change_menutitle($key)
{
	$menu_titlearray=array('staff_member'=>__('Staff Members','gym_mgt'),'membership'=>__('Membership Type','gym_mgt'),'group'=>__('Group','gym_mgt'),'member'=>__('Member','gym_mgt'),'activity'=>__('Activity','gym_mgt'),'class-schedule'=>__('Class Schedule','gym_mgt'),'attendence'=>__('Attendance','gym_mgt'),'assign-workout'=>__('Assigned Workouts','gym_mgt'),'workouts'=>__('Workouts','gym_mgt'),'accountant'=>__('Accountant','gym_mgt'),'membership_payment'=>__('Membership Payment','gym_mgt'),'payment'=>__('Payment','gym_mgt'),'product'=>__('Product','gym_mgt'),'store'=>__('Store','gym_mgt'),'news_letter'=>__('Newsletter','gym_mgt'),'message'=>__('Message','gym_mgt'),'notice'=>__('Notice','gym_mgt'),'nutrition'=>__('Nutrition Schedule','gym_mgt'),'reservation'=>__('Reservation','gym_mgt'),'subscription_history'=>__('Subscription History','gym_mgt'),'alumni'=>__('Alumni','gym_mgt'),'prospect'=>__('Prospect','gym_mgt'),'account'=>__('Account','gym_mgt'));
	
	return $menu_titlearray[$key];
}

//STATUS  FUNCTION
function MJ_gmgt_change_read_status($id)
{
	global $wpdb;
	$table_name = $wpdb->prefix . "Gmgt_message";
	$data['status']=1;
	$whereid['message_id']=$id;
	return $retrieve_subject = $wpdb->update($table_name,$data,$whereid);
}
//IMAGE/DOCUMENT UPLOAD  FUNCTION
function MJ_gmgt_user_avatar_image_upload($type) 
{
	 $imagepath ="";
	 $parts = pathinfo($_FILES[$type]['name']);
	 $inventoryimagename = time()."-"."member".".".$parts['extension'];
	 $document_dir = WP_CONTENT_DIR ;
	 $document_dir .= '/uploads/gym_assets/';
	 $document_path = $document_dir;

	if($imagepath != "")
	{	
		if(file_exists(WP_CONTENT_DIR.$imagepath))
		unlink(WP_CONTENT_DIR.$imagepath);
	}
	if (!file_exists($document_path))
	{
		mkdir($document_path, 0777, true);
	}	
       if (move_uploaded_file($_FILES[$type]['tmp_name'], $document_path.$inventoryimagename)) 
	   {
          $imagepath= $inventoryimagename;	
       }
	return $imagepath;
}
//LOAD DOCUMENT FUNCTION
function MJ_gmgt_load_documets($file,$type,$nm) 
{
	 $imagepath =$file;
	 $parts = pathinfo($_FILES[$type]['name']);
	 $inventoryimagename = time()."-".$nm."-"."in".".".$parts['extension'];
	 $document_dir = WP_CONTENT_DIR ;
	 $document_dir .= '/uploads/gym_assets/';
	 $document_path = $document_dir;

	if (!file_exists($document_path)) 
	{
		mkdir($document_path, 0777, true);
	}	
		   if (move_uploaded_file($_FILES[$type]['tmp_name'], $document_path.$inventoryimagename))
			{
			  $imagepath= $inventoryimagename;	
		    }
	return $imagepath;
}
add_action( 'wp_login_failed', 'MJ_gmgt_login_failed' ); // hook failed login 

function MJ_gmgt_get_lastmember_id($role)
{
	global $wpdb;
	$this_role = "'[[:<:]]".$role."[[:>:]]'";
	$table_name = $wpdb->prefix .'usermeta';
	$metakey=$wpdb->prefix .'capabilities';
	$userid=$wpdb->get_row("SELECT MAX(user_id)as uid FROM $table_name where meta_key = '$metakey' AND meta_value RLIKE $this_role");
	return get_user_meta($userid->uid,'member_id',true);
	
}
add_action( 'authenticate', 'MJ_gmgt_check_username_password', 1, 3);
function MJ_gmgt_check_username_password( $login, $username, $password ) 
{
// Getting URL of the login page
$referrer = $_SERVER['HTTP_REFERER'];

// if there's a valid referrer, and it's not the default log-in screen
if( !empty( $referrer ) && !strstr( $referrer,'wp-login' ) && !strstr( $referrer,'wp-admin' ) ) {
    if( $username == "" || $password == "" ){
        wp_redirect( get_permalink( get_option('gmgt_login_page') ) . "?login=empty" ); 
     exit;
    }
} 

}

if(isset($_GET['login']) && $_GET['login'] == 'empty')
{?>

<div id="login-error" class="login-error" >
  <p><?php _e('Login Failed: Username and/or Password is empty, please try again.','gym_mgt');?></p>
</div>
<?php	
}
//LOGIN FAILD FUNCTION
function MJ_gmgt_login_failed( $user ) 
{

	// check what page the login attempt is coming from
	$referrer = $_SERVER['HTTP_REFERER'];
	
	 $curr_args = array(
				'page_id' => get_option('gmgt_login_page'),
				'login' => 'failed'
				);
				print_r($curr_args);
				$referrer_faild = add_query_arg( $curr_args, get_permalink( get_option('gmgt_login_page') ) );
			
	// check that were not on the default login page
	if ( !empty($referrer) && !strstr($referrer,'wp-login') && !strstr($referrer,'wp-admin') && $user!=null )
	{
		// make sure we don't already have a failed login attempt
		if ( !strstr($referrer, 'login=failed' )) {
			// Redirect to the login page and append a querystring of login failed
			wp_redirect( $referrer_faild);
		} else {
			wp_redirect( $referrer );
		}
		exit;
	}
}
//GMGT MENU FUNCTION

function MJ_gmgt_menu()
{
	$user_menu = array();
	$user_menu[] = array('menu_icone'=>plugins_url( 'gym-management/assets/images/icon/staff-member.png' ),'menu_title'=>__( 'Staff Members', 'gym_mgt' ),'member'=>1,'staff_member' => 1,'accountant'=>1,'page_link'=>'staff_member');
	
	$user_menu[] = array('menu_icone'=>plugins_url( 'gym-management/assets/images/icon/membership-type.png' ),'menu_title'=>__( 'Membership Type', 'gym_mgt' ),'member'=>1,'staff_member' => 1,'accountant'=>0,'page_link'=>'membership');
	
	$user_menu[] = array('menu_icone'=>plugins_url( 'gym-management/assets/images/icon/group.png' ),'menu_title'=>__( 'Group', 'gym_mgt' ),'member'=>1,'staff_member' => 1,'accountant'=>0,'page_link'=>'group');
	
	$user_menu[] = array('menu_icone'=>plugins_url( 'gym-management/assets/images/icon/member.png' ),'menu_title'=>__( 'Member', 'gym_mgt' ),'member'=>1,'staff_member' => 1,'accountant'=>1,'page_link'=>'member');
	
	$user_menu[] = array('menu_icone'=>plugins_url( 'gym-management/assets/images/icon/activity.png' ),'menu_title'=>__( 'Activity', 'gym_mgt' ),'member'=>1,'staff_member' => 1,'accountant'=>0,'page_link'=>'activity');
	
	$user_menu[] = array('menu_icone'=>plugins_url( 'gym-management/assets/images/icon/class-schedule.png' ),'menu_title'=>__( 'Class schedule', 'gym_mgt' ),'member'=>1,'staff_member' => 1,'accountant'=>0,'page_link'=>'class-schedule');
	 
	 $user_menu[] = array('menu_icone'=>plugins_url( 'gym-management/assets/images/icon/attandance.png' ),'menu_title'=>__( 'Attendence', 'gym_mgt' ),'member'=>0,'staff_member' =>1,'accountant'=>0,'page_link'=>'attendence');
	
	$user_menu[] = array('menu_icone'=>plugins_url( 'gym-management/assets/images/icon/assigne-workout.png' ),'menu_title'=>__( 'Assigned Workouts', 'gym_mgt' ),'member'=>1,'staff_member' => 1,'accountant'=>0,'page_link'=>'assign-workout');
	
	$user_menu[] = array('menu_icone'=>plugins_url( 'gym-management/assets/images/icon/workout.png' ),'menu_title'=>__( 'Workouts', 'gym_mgt' ),'member'=>1,'staff_member' => 1,'accountant'=>0,'page_link'=>'workouts');
	
	$user_menu[] = array('menu_icone'=>plugins_url( 'gym-management/assets/images/icon/accountant.png' ),'menu_title'=>__( 'Accountant', 'gym_mgt' ),'member'=>1,'staff_member' => 1,'accountant'=>1,'page_link'=>'accountant');
	
	$user_menu[] = array('menu_icone'=>plugins_url( 'gym-management/assets/images/icon/fee.png' ),'menu_title'=>__( 'Membership Payment', 'gym_mgt' ),'member'=>1,'staff_member' => 0,'accountant'=>1,'page_link'=>'membership_payment');
	
	$user_menu[] = array('menu_icone'=>plugins_url( 'gym-management/assets/images/icon/payment.png' ),'menu_title'=>__( 'Payment', 'gym_mgt' ),'member'=>1,'staff_member' => 0,'accountant'=>1,'page_link'=>'payment');
	
	$user_menu[] = array('menu_icone'=>plugins_url( 'gym-management/assets/images/icon/products.png' ),'menu_title'=>__( 'Product', 'gym_mgt' ),'member'=>0,'staff_member' => 1,'accountant'=>1,'page_link'=>'product');
	
	$user_menu[] = array('menu_icone'=>plugins_url( 'gym-management/assets/images/icon/store.png' ),'menu_title'=>__( 'Store', 'gym_mgt' ),'member'=>0,'staff_member' => 1,'accountant'=>1,'page_link'=>'store');
	
	$user_menu[] = array('menu_icone'=>plugins_url( 'gym-management/assets/images/icon/newsletter.png' ),'menu_title'=>__( 'Newsletter', 'gym_mgt' ),'member'=>0,'staff_member' => 1,'accountant'=>0,'page_link'=>'news_letter');
	
	$user_menu[] = array('menu_icone'=>plugins_url( 'gym-management/assets/images/icon/message.png' ),'menu_title'=>__( 'Message', 'gym_mgt' ),'member'=>1,'staff_member' => 1,'accountant'=>1,'page_link'=>'message');
	
	$user_menu[] = array('menu_icone'=>plugins_url( 'gym-management/assets/images/icon/notice.png' ),'menu_title'=>__( 'Notice', 'gym_mgt' ),'member'=>1,'staff_member' => 1,'accountant'=>1,'page_link'=>'notice');
	
	$user_menu[] = array('menu_icone'=>plugins_url( 'gym-management/assets/images/icon/nutrition-schedule.png' ),'menu_title'=>__( 'Nutrition Schedule', 'gym_mgt' ),'member'=>1,'staff_member' => 1,'accountant'=>0,'page_link'=>'nutrition');
	
	$user_menu[] = array('menu_icone'=>plugins_url( 'gym-management/assets/images/icon/reservation.png' ),'menu_title'=>__( 'Reservation', 'gym_mgt' ),'member'=>1,'staff_member' => 1,'accountant'=>0,'page_link'=>'reservation');
	
	$user_menu[] = array('menu_icone'=>plugins_url( 'gym-management/assets/images/icon/account.png' ),'menu_title'=>__( 'Account', 'gym_mgt' ),'member'=>1,'staff_member' => 1,'accountant'=>1,'page_link'=>'account');
	
	return $user_menu;

}
/*--------- FRONTEND SIDE MENU LIST--------------------*/
function MJ_gmgt_frontend_menu_list()
{
	$access_array=array('staff_member' => 
    array (
      'menu_icone' =>plugins_url( 'gym-management/assets/images/icon/staff-member.png'),
      'menu_title' =>'Staff Members',
      'member' =>'1',
      'staff_member' =>'1',
      'accountant' =>'1',
      'page_link' =>'staff_member'),
	  
	  'membership' => 
    array (
      'menu_icone' =>plugins_url( 'gym-management/assets/images/icon/membership-type.png'),
     'menu_title' =>'Membership Type',
      'member' =>'1',
      'staff_member' =>'1',
      'accountant' =>'0',
      'page_link' =>'membership'),
	  
	    'group' => 
    array (
      'menu_icone' =>plugins_url( 'gym-management/assets/images/icon/group.png'),
     'menu_title' =>'group',
      'member' =>'1',
      'staff_member' =>'1',
      'accountant' =>'0',
      'page_link' =>'group'),
	  
	    'member' => 
    array (
      'menu_icone' =>plugins_url( 'gym-management/assets/images/icon/member.png'),
     'menu_title' =>'Member',
      'member' =>'1',
      'staff_member' =>'1',
      'accountant' =>'1',
      'page_link' =>'member'),
	  
	 'activity' => 
    array (
      'menu_icone' =>plugins_url( 'gym-management/assets/images/icon/activity.png'),
     'menu_title' =>'Activity',
      'member' =>'1',
      'staff_member' =>'1',
      'accountant' =>'0',
      'page_link' =>'activity'),
	  
	    'class-schedule' => 
    array (
      'menu_icone' =>plugins_url( 'gym-management/assets/images/icon/class-schedule.png'),
     'menu_title' =>'Class schedule',
      'member' =>'1',
      'staff_member' =>'1',
      'accountant' =>'0',
      'page_link' =>'class-schedule'),
	  
	    'attendence' => 
    array (
      'menu_icone' =>plugins_url( 'gym-management/assets/images/icon/attandance.png'),
     'menu_title' =>'Attendence',
      'member' =>'0',
      'staff_member' =>'1',
      'accountant' =>'0',
      'page_link' =>'attendence'),
	  
	    'assign-workout' => 
    array (
      'menu_icone' =>plugins_url( 'gym-management/assets/images/icon/assigne-workout.png'),
     'menu_title' =>'Assigned Workouts',
      'member' =>'1',
      'staff_member' =>'1',
      'accountant' =>'0',
      'page_link' =>'assign-workout'),
	  
	    'workouts' => 
    array (
      'menu_icone' =>plugins_url( 'gym-management/assets/images/icon/workout.png'),
     'menu_title' =>'Workouts',
      'member' =>'1',
      'staff_member' =>'1',
      'accountant' =>'0',
      'page_link' =>'workouts'),
	  
	    'accountant' => 
    array (
      'menu_icone' =>plugins_url( 'gym-management/assets/images/icon/accountant.png'),
     'menu_title' =>'Accountant',
      'member' =>'1',
      'staff_member' =>'1',
      'accountant' =>'1',
      'page_link' =>'accountant'),
	  
	    'membership_payment' => 
    array (
      'menu_icone' =>plugins_url( 'gym-management/assets/images/icon/fee.png'),
     'menu_title' =>'Membership Payment',
      'member' =>'1',
      'staff_member' =>'0',
      'accountant' =>'1',
      'page_link' =>'membership_payment'),
	  
	    'payment' => 
    array (
      'menu_icone' =>plugins_url( 'gym-management/assets/images/icon/payment.png'),
     'menu_title' =>'Payment',
      'member' =>'1',
      'staff_member' =>'0',
      'accountant' =>'1',
      'page_link' =>'payment'),
	  
	     'product' => 
    array (
      'menu_icone' =>plugins_url( 'gym-management/assets/images/icon/products.png'),
     'menu_title' =>'Product',
      'member' =>'0',
      'staff_member' =>'1',
      'accountant' =>'1',
      'page_link' =>'product'),
	  
	     'store' => 
    array (
      'menu_icone' =>plugins_url( 'gym-management/assets/images/icon/store.png'),
     'menu_title' =>'Store',
      'member' =>'0',
      'staff_member' =>'1',
      'accountant' =>'1',
      'page_link' =>'store'),
	  
	     'news_letter' => 
    array (
      'menu_icone' =>plugins_url( 'gym-management/assets/images/icon/newsletter.png'),
     'menu_title' =>'Newsletter',
      'member' =>'0',
      'staff_member' =>'1',
      'accountant' =>'0',
      'page_link' =>'news_letter'),
	  
	     'message' => 
    array (
      'menu_icone' =>plugins_url( 'gym-management/assets/images/icon/message.png'),
     'menu_title' =>'Message',
      'member' =>'1',
      'staff_member' =>'1',
      'accountant' =>'1',
      'page_link' =>'message'),
	  
	  
	     'notice' => 
    array (
      'menu_icone' =>plugins_url( 'gym-management/assets/images/icon/notice.png'),
     'menu_title' =>'Notice',
      'member' =>'1',
      'staff_member' =>'1',
      'accountant' =>'1',
      'page_link' =>'notice'),
	  
	     'nutrition' => 
    array (
      'menu_icone' =>plugins_url( 'gym-management/assets/images/icon/nutrition-schedule.png'),
     'menu_title' =>'Nutrition Schedule',
      'member' =>'1',
      'staff_member' =>'1',
      'accountant' =>'0',
      'page_link' =>'nutrition'),
	  
	     'reservation' => 
    array (
      'menu_icone' =>plugins_url( 'gym-management/assets/images/icon/reservation.png'),
     'menu_title' =>'Reservation',
      'member' =>'1',
      'staff_member' =>'1',
      'accountant' =>'0',
      'page_link' =>'reservation'),
	  
	     'account' => 
    array (
      'menu_icone' =>plugins_url( 'gym-management/assets/images/icon/account.png'),
     'menu_title' =>'Account',
      'member' =>'1',
      'staff_member' =>'1',
      'accountant' =>'1',
      'page_link' =>'account'),
	  
	     'membership' => 
    array (
      'menu_icone' =>plugins_url( 'gym-management/assets/images/icon/membership-type.png'),
     'menu_title' =>'Membership Type',
      'member' =>'1',
      'staff_member' =>'1',
      'accountant' =>'0',
      'page_link' =>'membership'),
	  
	'subscription_history' => 
    array (
      'menu_icone' =>plugins_url( 'gym-management/assets/images/icon/subscription_history.png'),
     'menu_title' =>'Subscription History',
      'member' =>'1',
      'staff_member' =>'0',
      'accountant' =>'0',
      'page_link' =>'subscription_history'),
	  
	 
	  
	 );
	
	if ( !get_option('gmgt_access_right') )
	{
		update_option( 'gmgt_access_right', $access_array );
	}
	
}
add_action('init','MJ_gmgt_frontend_menu_list');
/*--------- GET SINGLE MEMBRSHIP PAYMENT RECORD --------------------*/
function MJ_gmgt_get_single_membership_payment_record($mp_id)
{
	global $wpdb;
		$table_gmgt_membership_payment = $wpdb->prefix. 'Gmgt_membership_payment';
		$result = $wpdb->get_row("SELECT * FROM $table_gmgt_membership_payment where mp_id=".$mp_id);
		return $result;
}
/*--------- GET SINGLE PAYMENT HISTORY--------------------*/
function MJ_gmgt_get_payment_history_by_mpid($mp_id)
{
	global $wpdb;
	$result=array();
	$table_gmgt_membership_payment_history = $wpdb->prefix .'gmgt_membership_payment_history';
	
	$result =$wpdb->get_results("SELECT * FROM $table_gmgt_membership_payment_history WHERE mp_id=$mp_id ORDER BY payment_history_id DESC");
	return $result;
}
/*--------- GET INCOME PAYMENT HISTORY--------------------*/
function MJ_gmgt_get_income_payment_history_by_mpid($mp_id)
{
	global $wpdb;
	$result=array();
	$table_gmgt_income_payment_history = $wpdb->prefix .'gmgt_income_payment_history';
	
	$result =$wpdb->get_results("SELECT * FROM $table_gmgt_income_payment_history WHERE invoice_id=$mp_id ORDER BY payment_history_id DESC");
	return $result;
}
/*--------- GET SALE PAYMENT HISTORY BY MEMBERSHIP ID--------------------*/
function MJ_gmgt_get_sell_payment_history_by_mpid($mp_id)
{
	global $wpdb;
	$result=array();
	$table_gmgt_sales_payment_history = $wpdb->prefix .'gmgt_sales_payment_history';
	
	$result =$wpdb->get_results("SELECT * FROM $table_gmgt_sales_payment_history WHERE sell_id=$mp_id ORDER BY payment_history_id DESC");
	return $result;
}

function MJ_gmgt_pay_membership_amount_frontend_side()
{
	
	if(isset($_REQUEST['pay_id']) && isset($_REQUEST['amount']) && isset($_REQUEST['payment_request_id']) && isset($_REQUEST['customer_id']))
	{       
		$membership_id = $_REQUEST['pay_id'];
		$amount = $_REQUEST['amount'];
		$member_id = $_REQUEST['customer_id'];
		$trasaction_id ='';
		$payment_method='Instamojo';
		$result=MJ_frontend_side_membership_payment_function($membership_id,$member_id,$amount,$trasaction_id,$payment_method);
		if($result)
		{
			wp_redirect(home_url() .'/?action=success');	
		} 
	}

	if(isset($_REQUEST['skrill_mp_id']) && isset($_REQUEST['amount']) && isset($_REQUEST['member_id']))
	{
		$membership_id = $_REQUEST['skrill_mp_id'];
		$amount =  $_REQUEST['amount'];
		$member_id = $_REQUEST['member_id'];
		$trasaction_id ='';
		$payment_method='Skrill';
		$result=MJ_frontend_side_membership_payment_function($membership_id,$member_id,$amount,$trasaction_id,$payment_method);
		if($result)
		{
			wp_redirect(home_url() .'/?action=success');	
		} 
		
	} 
	
	if(isset($_REQUEST['pay_method']) && $_REQUEST['pay_method']=="ideal")
	{
		$membership_id = $_REQUEST['pay_id'];
		$amount =  $_REQUEST['amount'];
		$member_id = $_REQUEST['member_id'];
		$trasaction_id ='';
		$payment_method='iDeal';
		$result=MJ_frontend_side_membership_payment_function($membership_id,$member_id,$amount,$trasaction_id,$payment_method);
		if($result)
		{
			wp_redirect(home_url() .'/?action=success');	
		}
		
	}

}
/*--------- LOGIN LINK--------------------*/
function MJ_gmgt_login_link()
{
	$args = array( 'redirect' => site_url() );
	
	if(isset($_GET['login']) && $_GET['login'] == 'failed')
	{?>

	<div id="login-error" class="login-error" >
	  <p><?php _e('Login failed: You have entered an incorrect Username or password, please try again.','gym_mgt');?></p>
	</div>
    <?php	
	}
	
	if(isset($_GET['login']) && $_GET['login'] == 'empty')
	{?>

	<div id="login-error" class="login-error" >
	  <p><?php _e('Login Failed: Username and/or Password is empty, please try again.','gym_mgt');?></p>
	</div>
    <?php	
	}
	if(isset($_GET['action']) && $_GET['action'] == 'success')
	{ ?>

<div id="login-error" class="login-error">
  <p><?php _e('Payment successfull.','gym_mgt');?></p>
</div>
<?php
	} elseif(isset($_GET['action']) && $_GET['action'] == 'cencal')
	{ ?>
		<div id="login-error" class="login-error">
			<p><?php _e('Payment Cancel.','gym_mgt');?></p>
		</div>
	<?php
	}	
	 $args = array(
			'echo' => true,
			'redirect' => site_url( $_SERVER['REQUEST_URI'] ),
			'form_id' => 'loginform',
			'label_username' => __( 'Username' , 'gym_mgt'),
			'label_password' => __( 'Password', 'gym_mgt' ),
			'label_remember' => __( 'Remember Me' , 'gym_mgt'),
			'label_log_in' => __( 'Log In' , 'gym_mgt'),
			'id_username' => 'user_login',
			'id_password' => 'user_pass',
			'id_remember' => 'rememberme',
			'id_submit' => 'wp-submit',
			'remember' => true,
			'value_username' => NULL,
	        'value_remember' => false ); 
			if(isset($_REQUEST['membership_id']))
			{
				$page_id = get_option ( 'gmgt_membership_pay_page' );
					$referrer_ipn = array(				
						'page_id' => $page_id,
						'membership_id'=>$_REQUEST['membership_id']
					);
				$referrer_ipn = add_query_arg( $referrer_ipn, home_url() );
				$args = array('redirect' =>$referrer_ipn);
			}
			else
			{
				$args = array('redirect' => site_url('/?dashboard=user') );		
			}
			
			if(isset($_REQUEST['na']) && $_REQUEST['na']=='1')
			{ ?>
				<div id="login-error" class="login-error">
					<p><?php _e('You can login after admin approve your registration.','gym_mgt');?></p>
				</div>
			<?php 
			}
	if ( is_user_logged_in() )
	{
	 ?>
		<a href="<?php echo home_url('/')."?dashboard=user"; ?>">
		<?php _e('Dashboard','gym_mgt');?>
		</a>
		<br /><a href="<?php echo wp_logout_url(); ?>"><?php _e('Logout','gyn_mgt');?></a> 
		<?php 
	 }
	else 
	{ 
		wp_login_form( $args );
		echo '<a href="'.wp_lostpassword_url().'" title="Lost Password">'.__('Forgot your password?','gym_mgt').'</a> ';		
	}	 
}
add_action( 'wp_ajax_MJ_gmgt_add_or_remove_category', 'MJ_gmgt_add_or_remove_category');
add_action( 'wp_ajax_MJ_gmgt_add_category', 'MJ_gmgt_add_category');
add_action( 'wp_ajax_MJ_gmgt_remove_category', 'MJ_gmgt_remove_category');
add_action( 'wp_ajax_MJ_gmgt_load_user', 'MJ_gmgt_load_user');
add_action( 'wp_ajax_MJ_gmgt_invoice_view', 'MJ_gmgt_invoice_view');
add_action( 'wp_ajax_MJ_gmgt_load_activity', 'MJ_gmgt_load_activity');
add_action( 'wp_ajax_MJ_gmgt_nutrition_schedule_view', 'MJ_gmgt_nutrition_schedule_view');
add_action( 'wp_ajax_MJ_gmgt_load_workout_measurement', 'MJ_gmgt_load_workout_measurement');
add_action( 'wp_ajax_MJ_gmgt_view_details_popup', 'MJ_gmgt_view_details_popup');
add_action( 'wp_ajax_MJ_gmgt_add_workout', 'MJ_gmgt_add_workout');
add_action( 'wp_ajax_MJ_gmgt_delete_workout', 'MJ_gmgt_delete_workout');
add_action( 'wp_ajax_MJ_gmgt_today_workouts', 'MJ_gmgt_today_workouts');
add_action( 'wp_ajax_MJ_gmgt_measurement_view', 'MJ_gmgt_measurement_view');
add_action( 'wp_ajax_MJ_gmgt_measurement_delete', 'MJ_gmgt_measurement_delete');
add_action( 'wp_ajax_MJ_gmgt_load_enddate', 'MJ_gmgt_load_enddate');
add_action( 'wp_ajax_nopriv_MJ_gmgt_load_enddate', 'MJ_gmgt_load_enddate');
add_action( 'wp_ajax_MJ_gmgt_add_nutrition', 'MJ_gmgt_add_nutrition');
add_action( 'wp_ajax_MJ_gmgt_delete_nutrition', 'MJ_gmgt_delete_nutrition');
add_action( 'wp_ajax_MJ_gmgt_paymentdetail_bymembership', 'MJ_gmgt_paymentdetail_bymembership');
add_action( 'wp_ajax_MJ_gmgt_member_add_payment',  'MJ_gmgt_member_add_payment');
add_action( 'wp_ajax_MJ_gmgt_member_view_paymenthistory',  'MJ_gmgt_member_view_paymenthistory');
add_action( 'wp_ajax_MJ_gmgt_verify_pkey', 'MJ_gmgt_verify_pkey');
add_action( 'wp_ajax_MJ_gmgt_timeperiod_for_class_number', 'MJ_gmgt_timeperiod_for_class_number');

add_action( 'wp_ajax_MJ_gmgt_get_class_id_by_membership', 'MJ_gmgt_get_class_id_by_membership');
add_action( 'wp_ajax_nopriv_MJ_gmgt_get_class_id_by_membership', 'MJ_gmgt_get_class_id_by_membership');

add_action( 'wp_ajax_MJ_gmgt_check_membership_limit_status', 'MJ_gmgt_check_membership_limit_status');
add_action( 'wp_ajax_nopriv_MJ_gmgt_check_membership_limit_status', 'MJ_gmgt_check_membership_limit_status');

add_action( 'wp_ajax_MJ_gmgt_timeperiod_for_class_member', 'MJ_gmgt_timeperiod_for_class_member');
add_action( 'wp_ajax_MJ_gmgt_add_staff_member', 'MJ_gmgt_add_staff_member');
add_action( 'wp_ajax_MJ_gmgt_add_group', 'MJ_gmgt_add_group');
add_action( 'wp_ajax_MJ_gmgt_add_ajax_membership', 'MJ_gmgt_add_ajax_membership');
add_action( 'wp_ajax_MJ_gmgt_add_ajax_class', 'MJ_gmgt_add_ajax_class');
add_action( 'wp_ajax_MJ_gmgt_add_ajax_product', 'MJ_gmgt_add_ajax_product');
add_action( 'wp_ajax_MJ_gmgt_count_store_total', 'MJ_gmgt_count_store_total');
add_action( 'wp_ajax_MJ_gmgt_check_product_stock', 'MJ_gmgt_check_product_stock');
add_action( 'wp_ajax_MJ_gmgt_get_activity_from_category_type', 'MJ_gmgt_get_activity_from_category_type');
add_action( 'wp_ajax_nopriv_MJ_gmgt_get_activity_from_category_type', 'MJ_gmgt_get_activity_from_category_type');
add_action( 'wp_ajax_MJ_gmgt_get_staff_member_list_by_specilization_category_type', 'MJ_gmgt_get_staff_member_list_by_specilization_category_type');
add_action( 'wp_ajax_nopriv_MJ_gmgt_get_staff_member_list_by_specilization_category_type', 'MJ_gmgt_get_staff_member_list_by_specilization_category_type');
add_action( 'wp_ajax_MJ_gmgt_get_member_current_membership_activity_list', 'MJ_gmgt_get_member_current_membership_activity_list');
add_action( 'wp_ajax_MJ_gmgt_show_event_task', 'MJ_gmgt_show_event_task');
add_action( 'wp_ajax_nopriv_MJ_gmgt_show_event_task', 'MJ_gmgt_show_event_task');
//check product stock FUNCTION
function MJ_gmgt_check_product_stock()
{
	$product_id=$_REQUEST['product_id'];
	$quantity=$_REQUEST['quantity'];
	$row_no=$_REQUEST['row_no'];
	
	global $wpdb;
	$table_product = $wpdb->prefix. 'gmgt_product';
	$result = $wpdb->get_row("SELECT * FROM $table_product where id=".$product_id);
	
	$before_quantity=$result->quentity;
	if($quantity > $before_quantity)
	{		
		echo $row_no;
	}
	else
	{
		var_dump("dss");
		 
		echo '';
	}
	die();
}
//----------ADD MEMBERSHIP AJAX CODE FUNCTION-----------
function MJ_gmgt_add_ajax_product()
{
	
	$obj_product=new MJ_Gmgtproduct;
	$result=$obj_product->MJ_gmgt_add_product($_POST);
	$option ="";
	$product_info=$obj_product->MJ_gmgt_get_single_product($result);
	
	if(!empty($product_info)){
		$option = "<option value='".$product_info->id."'>".$product_info->product_name."</option>";
	}
	echo $option;
	die();
}

//----------ADD MEMBERSHIP AJAX CODE FUNCTION-----------
function MJ_gmgt_add_ajax_class()
{	
	$time_validation=0;
	if(!empty($_POST['start_time']))
	{
		foreach($_POST['start_time'] as $key=>$start_time)
		{
			if($_POST['start_ampm'][$key] == $_POST['end_ampm'][$key] )
			{				
				if($_POST['end_time'][$key] < $start_time)
				{
					$time_validation=$time_validation+1;
				
				}
				elseif($_POST['end_time'][$key] == $start_time && $_POST['start_min'][$key] > $_POST['end_min'][$key] )
				{
					$time_validation=$time_validation+1;
				}				
			}
			else
			{
				if($_POST['start_ampm'][$key]!='am')
				{
					$time_validation= $time_validation+1;
				}	
			}	
		}
	}
	
	if($time_validation > 0)
	{
		echo '1';
	}
	else
	{ 
		$obj_class=new MJ_Gmgtclassschedule;
		$result=$obj_class->MJ_gmgt_add_class($_POST);
		
		$option ="";
		$class_info=$obj_class->MJ_gmgt_get_single_class($result);
		
		if(!empty($class_info))
		{
			$option = "<option value='".$class_info->class_id."'>".$class_info->class_name."</option>";
		}
		echo $option;
	}
	die();
}

//----------ADD MEMBERSHIP AJAX CODE-----------
function MJ_gmgt_add_ajax_membership()
{
	$txturl=$_POST['gmgt_membershipimage'];
	$ext=MJ_gmgt_check_valid_extension($txturl);
	if(!$ext == 0)
	{
		$obj_membership=new MJ_Gmgtmembership;
		$result=$obj_membership->MJ_gmgt_add_membership($_POST,$_POST['gmgt_membershipimage']);
		$option ="";
		$membership_info=$obj_membership->MJ_gmgt_get_single_membership($result);
		
		if(!empty($membership_info))
		{
			$option = "<option value='".$membership_info->membership_id."'>".$membership_info->membership_label."</option>";
		}
		echo $option;
	}
	else
	{
		echo 0;
	}	
	
	die();
}
//----------ADD GROUP AJAX CODE-----------
function MJ_gmgt_add_group()
{
	$obj_group=new MJ_Gmgtgroup;
	$result=$obj_group->MJ_gmgt_add_group($_POST,$_POST['gmgt_groupimage']);
	$option ="";
	$group_info=$obj_group->MJ_gmgt_get_single_group($result);

	if(!empty($group_info)){
		$option = "<option value='".$group_info->id."'>".$group_info->group_name."</option>";
	}
	echo $option;
	die();
}
//----------ADD STAFF MEMBER AJAX CODE-----------
function MJ_gmgt_add_staff_member()
{	
	$txturl=$_POST['gmgt_user_avatar'];
	$ext=MJ_gmgt_check_valid_extension($txturl);
	if(!$ext == 0)
	{
		$obj_member=new MJ_Gmgtmember;
		$result=$obj_member->MJ_gmgt_gmgt_add_user($_POST);
		$user_info = get_userdata($result);
		$option ="";
		if(!empty($user_info)){
			$option = "<option value='".$user_info->ID."'>".$user_info->first_name." ".$user_info->last_name."</option>";
		}
		echo $option;
	}
	else
	{
		echo 0;
	}	
	die();
}
//---------- GET TODAY WORKOUT FOR MEMBER----------//
function MJ_gmgt_today_workouts()
{
	    $user_id=$_POST['uid'];
		global $wpdb;
		$table_name = $wpdb->prefix."gmgt_assign_workout";
		$table_gmgt_workout_data = $wpdb->prefix."gmgt_workout_data";
		$date = date('Y-m-d');
		$record_date = $curr_date=MJ_gmgt_get_format_for_db($_POST['record_date']);
		$day_name = date('l', strtotime($date));
		
		$sql = "Select *From $table_name as workout,$table_gmgt_workout_data as workoutdata where  workout.user_id = $user_id 
		AND  workout.workout_id = workoutdata.workout_id 
		AND workoutdata.day_name = '$day_name'
		AND '".$record_date."' between workout.Start_date and workout.End_date ";
		$result = $wpdb->get_results($sql);		
		
		if(!empty($result))
		{
			echo $option="<div class='work_out_datalist_header'><div class='col-md-12 col-sm-12 col-xs-12'>
					<span class='col-md-3 col-sm-3 col-xs-3 no-padding'>".__('Activity','gym_mgt')."</span>
					<span class='col-md-2 col-sm-2 col-xs-2'>".__('Sets','gym_mgt')."</span>
					<span class='col-md-2 col-sm-2 col-xs-2'>".__('Reps','gym_mgt')."</span>
					<span class='col-md-2 col-sm-2 col-xs-2'>".__('KG','gym_mgt')."</span>
					<span class='col-md-3 col-sm-3 col-xs-3'>".__('Rest Time','gym_mgt')."</span>
					</div></div>";
			foreach ($result as $retrieved_data)
			{
				$workout_id=$retrieved_data->workout_id;
				echo $option="<div class='work_out_datalist'><div class='col-sm-12 col-md-12 col-xs-12'>
					<input type='hidden' name='asigned_by' value='".$retrieved_data->create_by."'>
					<input type='hidden' name='workouts_array[]' value='".$retrieved_data->id."'>
					<input type='hidden' name='workout_name_".$retrieved_data->id."' value='".$retrieved_data->workout_name."'>
					<span class='col-md-3 col-sm-3 col-xs-3 no-padding'>".$retrieved_data->workout_name."</span>
					<span class='col-md-2 col-sm-2 col-xs-2'>".$retrieved_data->sets." ".__('Sets','gym_mgt')."</span>
					<span class='col-md-2 col-sm-2 col-xs-2'>".$retrieved_data->reps."  ".__('Reps','gym_mgt')."</span>
					<span class='col-md-2 col-sm-2 col-xs-2'>".$retrieved_data->kg."  ".__('Kg','gym_mgt')."</span>
					<span class='col-md-2 col-sm-2 col-xs-2'>".$retrieved_data->time."  ".__('Min','gym_mgt')."</span>
				</div>";
				echo $option="<div class='col-md-12 col-sm-12 col-xs-12'>
					<span class='col-md-3 col-sm-3 col-xs-3 no-padding'>".__('Your Workout','gym_mgt')."</span>
					<span class='col-md-2 col-sm-2 col-xs-2'><input type='number' class='my-workouts validate[required]' min='0' onKeyPress='if(this.value.length==3) return false;' id='sets' name='sets_".$retrieved_data->id."' width='50px'></span>
					<span class='col-md-2 col-sm-2 col-xs-2'><input type='number' class='my-workouts validate[required]' min='0' onKeyPress='if(this.value.length==3) return false;' id='reps' name='reps_".$retrieved_data->id."' width='50px'></span>
					<span class='col-md-2 col-sm-2 col-xs-2'><input type='number' class='my-workouts validate[required]' min='0' onKeyPress='if(this.value.length==6) return false;' step='0.01' value=".$retrieved_data->kg." id='kg' name='kg_".$retrieved_data->id."' width='50px'></span>
					<span class='col-md-2 col-sm-2 col-xs-2'><input type='number' class='my-workouts validate[required]' min='0' onKeyPress='if(this.value.length==3) return false;' id='rest' value=".$retrieved_data->time." name='rest_".$retrieved_data->id."' width='50px'></span>
				</div></div>";
			
			}
				echo $option="<input type='hidden' value='$workout_id' name='user_workout_id'>";
		}
		else
		{
			echo $option = "<div class='work_out_datalist'><div class='col-sm-10'><span class='col-md-10'>".__('No Workout assigned for today','gym_mgt')."</span></div></div>";
		}
		die();
}
//---------- LOAD MEASUREMENT FUNCTION----------//
function MJ_gmgt_load_workout_measurement()
{
	
	global $wpdb;
		$table_workout = $wpdb->prefix. 'gmgt_workouts';
	$result = $wpdb->get_row("SELECT measurment_id FROM $table_workout where id=". $_REQUEST['workout_id']);
	echo get_the_title($result->measurment_id);	
	die();
}
//---------- ADD CATEGORY TYPE FUNCTION----------//
function MJ_gmgt_add_categorytype($data)
{
	global $wpdb;
	$result = wp_insert_post( array(

			'post_status' => 'publish',

			'post_type' => $data['category_type'],

			'post_title' => MJ_gmgt_strip_tags_and_stripslashes($data['category_name']) ));

	$id = $wpdb->insert_id;
	return $id;
}

//---------- ADD CATEGORY FUNCTION----------//
function MJ_gmgt_add_category($data)
{
	
	global $wpdb;
	$model = $_REQUEST['model'];
	$data = array();
	$status=1;
	$status_msg= __('You have entered value already exists. Please enter some other value.','gym_mgt');
	$array_var = array();
	$data = array();
	$data['category_name'] = MJ_gmgt_strip_tags_and_stripslashes($_POST['category_name']);
	$data['category_type'] = $_POST['model'];
    $posttitle =$_REQUEST['category_name'];
	
    $post = $wpdb->get_row( "SELECT * FROM $wpdb->posts WHERE post_title = '".$posttitle."' AND  post_type ='". $model."'" );
    $postname=$post->post_title;
	
   if($postname == $posttitle )
   {
	   $status=0;
   }
   else
   { 
	$id = MJ_gmgt_add_categorytype($data);
	$row1 = '<tr id="cat-'.$id.'"><td>'.MJ_gmgt_strip_tags_and_stripslashes($_REQUEST['category_name']).'</td><td><a class="btn-delete-cat badge badge-delete" href="#" id='.$id.' model="'.$model.'">X</a></td></tr>';
	$option = "<option value='$id'>".MJ_gmgt_strip_tags_and_stripslashes($_REQUEST['category_name'])."</option>";

	$array_var[] = $row1;

	$array_var[] = $option;
   }
    $array_var[2]=$status;
    $array_var[3]=$status_msg;
	echo json_encode($array_var);

	die();
	
}
//----------GET CLASS NAME FUNCTION----------//
function MJ_gmgt_get_class_name($cid)
{
	
	global $wpdb;
	$table_name = $wpdb->prefix .'gmgt_class_schedule';
	$classname =$wpdb->get_row("SELECT class_name FROM $table_name WHERE class_id=".$cid);
	if(!empty($classname))
	{
		return $classname->class_name;
	}
	else
	{ 
	  return " ";
	}
}
//----------GET MEMBERSHIP NAME FUNCTION---------//
function MJ_gmgt_get_membership_name($mid)
{
	if($mid == '')
		return '';
	global $wpdb;
	$table_name = $wpdb->prefix .'gmgt_membershiptype';

	$result =$wpdb->get_row("SELECT membership_label FROM $table_name WHERE membership_id=".$mid);
	if(!empty($result))
	{
		return $result->membership_label;
	}
	else
	{
		return " ";
	}
}
//----------GET MEMBERSHIP AMOUNT FUNCTION--------//
function MJ_gmgt_get_membership_price($mid)
{
	if($mid == '')
	{
		return '';
	}
	global $wpdb;
	$table_name = $wpdb->prefix .'gmgt_membershiptype';
	$result =$wpdb->get_row("SELECT membership_amount FROM $table_name WHERE membership_id=".$mid);
	if(!empty($result))
	{
		return $result->membership_amount;
	}
	else
	{
		return " ";
	}
}
//----------GET MEMBERSHIP SIGNUP AMOUNT FUNCTION--------//
function MJ_gmgt_get_membership_signup_amount($mid)
{
	if($mid == '')
	{
		return '';
	}
	global $wpdb;
	$table_name = $wpdb->prefix .'gmgt_membershiptype';
	$result =$wpdb->get_row("SELECT signup_fee FROM $table_name WHERE membership_id=".$mid);
	if(!empty($result))
	{
		return $result->signup_fee;
	}
	else
	{
		return " ";
	}
}
//----------GET MEMBERSHIP Tax Amount FUNCTION--------//
function MJ_gmgt_get_membership_tax_amount($mid)
{
	if($mid == '')
	{
		return 0;
	}
	global $wpdb;
	$table_name = $wpdb->prefix .'gmgt_membershiptype';
	$result =$wpdb->get_row("SELECT * FROM $table_name WHERE membership_id=".$mid);
	if(!empty($result))
	{
		$membership_amount=$result->membership_amount;
		$signup_fee=$result->signup_fee;
		$membership_and_signup_fee_amount=$membership_amount+$signup_fee;
		$tax_array=explode(",",$result->tax);
		
		if(!empty($tax_array))
		{
			$total_tax=0;
			foreach($tax_array as $tax_id)
			{
				$tax_percentage=MJ_gmgt_tax_percentage_by_tax_id($tax_id);
				$tax_amount=$membership_and_signup_fee_amount * $tax_percentage / 100;
				
				$total_tax=$total_tax + $tax_amount;				
			}
			
			$total_tax_amount=$total_tax;
		}
		else
		{
			$total_tax_amount=0;			
		}
		
		return $total_tax_amount;
	}
	else
	{
		return 0;
	}
}
//----------GET MEMBERSHIP Tax Amount FUNCTION--------//
function MJ_gmgt_get_membership_tax($mid)
{
	if($mid == '')
	{
		return '';
	}
	global $wpdb;
	$table_name = $wpdb->prefix .'gmgt_membershiptype';
	$result =$wpdb->get_row("SELECT * FROM $table_name WHERE membership_id=".$mid);
	if(!empty($result))
	{		
		$tax_id=$result->tax;
				
		return $tax_id;
	}
	else
	{
		return '';
	}
}
//----------GET MEMBERSHIP DAY FUNCTION--------//
function MJ_gmgt_get_membership_days($mid)
{
	if($mid == '')
		return '';
	global $wpdb;
	$table_name = $wpdb->prefix .'gmgt_membershiptype';

	$result =$wpdb->get_row("SELECT membership_length_id FROM $table_name WHERE membership_id=".$mid);
	if(!empty($result))
		return $result->membership_length_id;
	else
		return " ";
}
//----------GET MEMBERSHIP PAYMENT STATUS FUNCTION--------//
function MJ_gmgt_get_membership_paymentstatus($mp_id)
{
	global $wpdb;
		$table_gmgt_membership_payment = $wpdb->prefix. 'Gmgt_membership_payment';		
		$result = $wpdb->get_row("SELECT * FROM $table_gmgt_membership_payment where mp_id = $mp_id");		
	if($result->paid_amount >= $result->membership_amount)
		return 'Fully Paid';
	elseif($result->paid_amount > 0)
		return 'Partially Paid';
	else
		return 'Unpaid';
}
//GET ALL MEMBERRSHIP PAYMENT BY USER ID FUNCTION//
function MJ_gmgt_get_all_membership_payment_byuserid($member_id)
{
	global $wpdb;
	$table_gmgt_membership_payment = $wpdb->prefix. 'Gmgt_membership_payment';
	
	$result = $wpdb->get_results("SELECT * FROM $table_gmgt_membership_payment where member_id = $member_id");
	return $result;
}
//----------GET GROUP MEMBER --------//
function MJ_gmgt_get_groupmember($group_id)
{
	global $wpdb;
	$table_gmgt_groupmember = $wpdb->prefix. 'gmgt_groupmember';
	$result = $wpdb->get_results("SELECT member_id FROM $table_gmgt_groupmember where group_id=".$group_id);
	return $result;
}

//----------GETACTIVITY BY CATEGORY --------//
function MJ_gmgt_get_activity_by_category($cat_id)
{

	global $wpdb;
	$table_activity = $wpdb->prefix. 'gmgt_activity';
	$activitydata = $wpdb->get_results("SELECT * FROM $table_activity where activity_cat_id=".$cat_id);
	return $activitydata;
}
//GET ACTIVITY BY Staff_member FUNCTION//
function MJ_gmgt_get_activity_by_staffmember($staff_memberid)
{
	global $wpdb;
	$table_gmgt_activity = $wpdb->prefix. 'gmgt_activity';
	$result = $wpdb->get_results("SELECT * FROM $table_gmgt_activity where activity_assigned_to=".$staff_memberid);
	return $result;
}

//----------REMOVE  CATEGORY FUNCTION--------//
function MJ_gmgt_remove_category()
{
	wp_delete_post($_REQUEST['cat_id']);
	die();
}
//----------GET ALL CATEGORY FUNCTION --------//
function MJ_gmgt_get_all_category($model)
{
	$args= array('post_type'=> $model,'posts_per_page'=>-1,'orderby'=>'post_title','order'=>'Asc');

	$cat_result = get_posts( $args );

	return $cat_result;

}

//----------ADD OR REMOVE  CATEGORY FUNCTION--------//
function MJ_gmgt_add_or_remove_category()
{

	$model = $_REQUEST['model'];
	 
		$title = __("title",'gym_mgt');

		$table_header_title =  __("header",'gym_mgt');

		$button_text=  __("Add category",'gym_mgt');

		$label_text =  __("category Name",'gym_mgt');


	if($model == 'membership_category')
	{

		$title = __("Add Membership Category",'gym_mgt');

		$table_header_title =  __("Category Name",'gym_mgt');

		$button_text=  __("Add Category",'gym_mgt');

		$label_text =  __("Category Name",'gym_mgt');	

	}
	if($model == 'installment_plan')
	{

		$title = __("Add Installment Plan",'gym_mgt');

		$table_header_title =  __("Plan Name",'gym_mgt');

		$button_text=  __("Add Plan",'gym_mgt');

		$label_text =  __("Installment  Plan Name",'gym_mgt');	

	}
	if($model == 'membership_period')
	{

		$title = __("Add Membership Period",'gym_mgt');

		$table_header_title =  __("Membership Period Name",'gym_mgt');

		$button_text=  __("Add Membership Period",'gym_mgt');

		$label_text =  __("Membership Period Name",'gym_mgt');	
		
		$placeholder_text=__("Only Number of Days",'gym_mgt');

	}
	if($model == 'role_type')
	{

		$title = __("Add Role Type",'gym_mgt');

		$table_header_title =  __("Role Name",'gym_mgt');

		$button_text=  __("Add Role",'gym_mgt');

		$label_text =  __("Role Name",'gym_mgt');	

	}
	if($model == 'specialization')
	{

		$title = __("Add Specialization",'gym_mgt');

		$table_header_title =  __("Specialization Name",'gym_mgt');

		$button_text=  __("Add Specialization",'gym_mgt');

		$label_text =  __("Specialization Name",'gym_mgt');	

	}
	if($model == 'intrest_area')
	{

		$title = __("Add Intrest Area",'gym_mgt');

		$table_header_title =  __("Intrest Area Name",'gym_mgt');

		$button_text=  __("Add Intrest Area",'gym_mgt');

		$label_text =  __("Intrest Area Name",'gym_mgt');	

	}
	if($model == 'source')
	{

		$title = __("Add Source",'gym_mgt');

		$table_header_title =  __("Source Name",'gym_mgt');

		$button_text=  __("Add Source",'gym_mgt');

		$label_text =  __("Source Name",'gym_mgt');	

	}
	if($model == 'event_place')
	{

		$title = __("Add Event Place",'gym_mgt');

		$table_header_title =  __("Place Name",'gym_mgt');

		$button_text=  __("Add Place",'gym_mgt');

		$label_text =  __("Place Name",'gym_mgt');	

	}
	if($model == 'activity_category')
	{

		$title = __("Add Activity Category",'gym_mgt');

		$table_header_title =  __("Activity Category Name",'gym_mgt');

		$button_text=  __("Add Activity Category",'gym_mgt');

		$label_text =  __("Activity Category Name",'gym_mgt');	

	}
	if($model == 'measurment')
	{

		$title = __("Add Measurement",'gym_mgt');

		$table_header_title =  __("Measurement Name",'gym_mgt');

		$button_text=  __("Add Measurement",'gym_mgt');

		$label_text =  __("Measurement Name",'gym_mgt');	

	}
	if($model == 'level_type')
	{

		$title = __("Add Level Type",'gym_mgt');

		$table_header_title =  __("Level Name",'gym_mgt');

		$button_text=  __("Add Level",'gym_mgt');

		$label_text =  __("Level Name",'gym_mgt');	

	}
	if($model == 'workout_limit')
	{

		$title = __("Add Workout Limit",'gym_mgt');

		$table_header_title =  __("Workout Limit",'gym_mgt');

		$button_text=  __("Add Workout Limit",'gym_mgt');

		$label_text =  __("Workout Limit",'gym_mgt');	

	}
	if($model == 'calories_category')
	{

		$title = __("Add Calories Category",'gym_mgt');

		$table_header_title =  __("Calories",'gym_mgt');

		$button_text=  __("Add Calories Category",'gym_mgt');

		$label_text =  __("Calories",'gym_mgt');	

	}
	if($model == 'product_category')
	{

		$title = __("Product Category",'gym_mgt');

		$table_header_title =  __("Category Name",'gym_mgt');

		$button_text=  __("Add Product Category",'gym_mgt');

		$label_text =  __("Category Name",'gym_mgt');	

	}
	$cat_result = MJ_gmgt_get_all_category( $model );
	?>
	<div class="modal-header"> <a href="#" class="close-btn badge badge-success pull-right <?php echo $model ;?>">X</a>

  		<h4 id="myLargeModalLabel" class="modal-title"><?php echo $title;?></h4>
	</div>
	<div class="panel panel-white">	
  		<div class="category_listbox"><!--  CATEGORY LIST BOX DIV    -->
  			<div class="table-responsive">
		  		<table class="table">
			  		<thead>
			  			<tr>
			                <!--  <th>#</th> -->

			                <th><?php echo $table_header_title;?></th>

			                <th><?php _e('Action','gym_mgt');?></th>

			            </tr>

			        </thead>
			         <?php 
					$i = 1;

					if(!empty($cat_result))
					{
						foreach ($cat_result as $retrieved_data)
						{
							echo '<tr id="cat-'.$retrieved_data->ID.'">';

							echo '<td>'.$retrieved_data->post_title.'</td>';

							echo '<td id='.$retrieved_data->ID.'><a class="btn-delete-cat badge badge-delete" model='.$model.' href="#" id='.$retrieved_data->ID.'>X</a></td>';

							echo '</tr>';

							$i++;
						}

					}
				  ?>
		        </table>

		     </div>

  		</div><!--  END CATEGORY LIST BOX DIV    -->			
  		<form name="category_form" action="" method="post" class="form-horizontal" id="category_form">

	  	 	<div class="form-group add_or_remove">

				<label class="col-sm-4 control-label" for="category_name"><?php echo $label_text;?><span class="require-field">*</span></label>

				<div class="col-sm-4">

					<input id="category_name" class="form-control validate[required,custom[popup_category_validation]]"  value="" name="category_name" maxlength="50" <?php if(isset($placeholder_text)){?> type="number" placeholder="<?php  echo $placeholder_text;}else{?>" type="text" <?php }?>>

				</div>

				<div class="col-sm-4">
						<input type="button" value="<?php echo $button_text;?>" name="save_category" class="btn btn-success" model="<?php echo $model;?>" id="btn-add-cat"/>
				</div>

			</div>

  		</form>

  	</div>
	<?php 
	die();	
}

// GET PHONE CODE BY COUNTRY CODE FUNCTION //
function MJ_gmgt_get_countery_phonecode($country_name)
{
	$url = plugins_url( 'countrylist.xml', __FILE__ );
	$xml =simplexml_load_string(MJ_gmgt_get_remote_file($url));
	foreach($xml as $country)
	{
		if($country_name == $country->name)
			return $country->phoneCode;
	}
}
// GET DAY FUNCTION //
function MJ_gmgt_days_array()
{
	return $week=array(	'Sunday'=>__('Sunday','gym_mgt'),
						'Monday'=>__('Monday','gym_mgt'),
						'Tuesday'=>__('Tuesday','gym_mgt'),
						'Wednesday'=>__('Wednesday','gym_mgt'),
						'Thursday'=>__('Thursday','gym_mgt'),
						'Friday'=>__('Friday','gym_mgt'),
						'Saturday'=>__('Saturday','gym_mgt'));
}
// GET MEMBER TYPE //
function MJ_gmgt_member_type_array()
{
	return $membertype=array('Member'=>__('Active Member','gym_mgt'),
						'Prospect'=>__('Prospect','gym_mgt'),
						'Alumni'=>__('Alumni','gym_mgt'));
}

// GET MINUITE AARAY FUNCTION //
function MJ_gmgt_minute_array()
{
	return $minute=array('00'=>'00','05'=>'05','10'=>'10','15'=>'15','20'=>'20','25'=>'25','30'=>'30','35'=>'35','40'=>'40','45'=>'45','50'=>'50','55'=>'55');
}

// GET MEASUREMENT AARAY FUNCTION //
function MJ_gmgt_measurement_array()
{
	return $measurment=array(	'Height'=>__('Height','gym_mgt'),
								'Weight'=>__('Weight','gym_mgt'),
								'Chest'=>__('Chest','gym_mgt'),
								'Waist'=>__('Waist','gym_mgt'),
								'Thigh'=>__('Thigh','gym_mgt'),
								'Arms'=>__('Arms','gym_mgt'),
								'Fat'=>__('Fat','gym_mgt'));
}
function MJ_gmgt_get_single_class_name($class_id)
{
	global $wpdb;
		$table_class = $wpdb->prefix. 'gmgt_class_schedule';
	return $retrieve_subject = $wpdb->get_var( "SELECT class_name FROM $table_class WHERE class_id=".$class_id);	
}
//LOAD USER FUNCTION
function MJ_gmgt_load_user()
{	
	$class_id =$_POST['class_list'];
	
	global $wpdb;
	$retrieve_data=get_users(array('meta_key' => 'class_id', 'meta_value' => $class_id,'role'=>'member'));
	$defaultmsg=__( 'Select Member' , 'gym_mgt');
	echo "<option value=''>".$defaultmsg."</option>";	
	foreach($retrieve_data as $users)
	{
		echo "<option value=".$users->id.">".$users->display_name."</option>";
	}
	die();	
}
//LOAD ALL ACTIVITY FUNCTION
function MJ_gmgt_load_activity()
{
	
	global $wpdb;
		$table_activity = $wpdb->prefix. 'gmgt_activity';
	
		$activitydata = $wpdb->get_results("SELECT * FROM $table_activity where activity_cat_id=".$_REQUEST['activity_list']);
		$defaultmsg=__( 'Select Activity', 'gym_mgt');
		echo "<option value=''>".$defaultmsg."</option>";	
		foreach($activitydata as $activity)
		{
			echo "<option value=".$activity->activity_id.">".$activity->activity_title."</option>";
		}
		die();
}
//GET INVOICE DATA FUNCTION
function MJ_gmgt_get_invoice_data($invoice_id)
{
	global $wpdb;
		$table_invoice= $wpdb->prefix. 'gmgt_payment';
		$result = $wpdb->get_row("SELECT *FROM $table_invoice where payment_id = ".$invoice_id);
		return $result;
}
 
//VIEW INVOICE  FUNCTION BY INVOICE TYPE FUNCTION
function MJ_gmgt_invoice_view()
{
	$obj_payment= new MJ_Gmgtpayment();
	if($_POST['invoice_type']=='membership_invoice')
	{		
		$obj_membership_payment=new MJ_Gmgt_membership_payment;	
		$membership_data=$obj_membership_payment->MJ_gmgt_get_single_membership_payment($_POST['idtest']);
		$history_detail_result = MJ_gmgt_get_payment_history_by_mpid($_POST['idtest']);
	}
	if($_POST['invoice_type']=='income')
	{
		$income_data=$obj_payment->MJ_gmgt_get_income_data($_POST['idtest']);
		$history_detail_result = MJ_gmgt_get_income_payment_history_by_mpid($_POST['idtest']);
	}
	if($_POST['invoice_type']=='expense')
	{
		$expense_data=$obj_payment->MJ_gmgt_get_income_data($_POST['idtest']);
	}
	if($_POST['invoice_type']=='sell_invoice')
	{
		$obj_store=new MJ_Gmgtstore;
		$selling_data=$obj_store->MJ_gmgt_get_single_selling($_POST['idtest']);
		$history_detail_result = MJ_gmgt_get_sell_payment_history_by_mpid($_POST['idtest']);
	}
	?>	
	<div class="modal-header">
		<a href="#" class="close-btn badge badge-success pull-right">X</a>
		<h4 class="modal-title"><?php echo get_option('gmgt_system_name','gym_mgt'); ?></h4>		
	</div>
	<div class="modal-body invoice_body">
		<div id="invoice_print">
			<img class="invoicefont1" style="vertical-align:top;background-repeat:no-repeat;" src="<?php echo plugins_url('/gym-management/assets/images/invoice.jpg'); ?>" width="100%">
			<div class="main_div">					
				<table class="width_100" border="0">					
					<tbody>
						<tr>
							<td class="width_1">
								<img class="system_logo" src="<?php echo get_option( 'gmgt_system_logo' ); ?>">
							</td>							
							<td class="only_width_20">
								<?php
								 echo "A. ".chunk_split(get_option('gmgt_gym_address'),30,"<BR>"); 
								 echo "E. ".get_option( 'gmgt_email' )."<br>"; 
								 echo "P. " .get_option( 'gmgt_contact_number' )."<br>"; 
								?> 
							</td>
							<td align="right" class="width_24">
							</td>
						</tr>
					</tbody>
				</table>
				<table class="width_50" border="0">
					<tbody>				
						<tr>
							<td colspan="2"  class="billed_to" align="center">								
								<h3 class="billed_to_lable"><?php _e('| Bill To.','gym_mgt');?> </h3>
							</td>
							<td class="width_40">								
							<?php 
								if(!empty($expense_data))
								{
								   echo "<h3 class='display_name'>".chunk_split(ucwords($expense_data->supplier_name),30,"<BR>"). "</h3>"; 
								}
								else
								{
									if(!empty($income_data))
										$member_id=$income_data->supplier_name;
									 if(!empty($membership_data))
										$member_id=$membership_data->member_id;
									 if(!empty($selling_data))
										$member_id=$selling_data->member_id;
									$patient=get_userdata($member_id);
									 echo "<h3 style='font-weight: bold;'>".chunk_split(ucwords($patient->display_name),30,"<BR>"). "</h3>"; 
									 $address=get_user_meta( $member_id,'address',true);
									 echo chunk_split($address,30,"<BR>"); 									 
									 echo get_user_meta( $member_id,'city_name',true ).","; 
									 echo get_user_meta( $member_id,'zip_code',true )."<br>"; 
									echo get_user_meta( $member_id,'mobile',true )."<br>"; 
								}
							?>			
							</td>
						</tr>									
					</tbody>
				</table>
					<?php 
					$issue_date='DD-MM-YYYY';
					if(!empty($income_data))
					{
						$issue_date=$income_data->invoice_date;
						$payment_status=$income_data->payment_status;
						$invoice_no=$income_data->invoice_no;
					}
					if(!empty($membership_data))
					{
						$issue_date=$membership_data->created_date;
						if($membership_data->payment_status!='0')
						{	
							$payment_status=$membership_data->payment_status;
						}
						else
						{
							$payment_status='Unpaid';
						}		
						$invoice_no=$membership_data->invoice_no;
					}
					if(!empty($expense_data))
					{
						$issue_date=$expense_data->invoice_date;
						$payment_status=$expense_data->payment_status;
						$invoice_no=$expense_data->invoice_no;
					}
					if(!empty($selling_data))
					{
						$issue_date=$selling_data->sell_date;	
						if(!empty($selling_data->payment_status))
						{
							$payment_status=$selling_data->payment_status;
						}	
						else
						{
							$payment_status='Fully Paid';
						}		
						
						$invoice_no=$selling_data->invoice_no;
					}			
						
					?>
				<table class="width_50" border="0">
					<tbody>				
						<tr>	
							<td class="width_30">
							</td>
							<td class="width_20" align="left">
								<?php
								if($_POST['invoice_type']!='expense')
								{
								?>	
									<h3 class="invoice_lable"  ><?php echo __('INVOICE','gym_mgt')." </br> #".$invoice_no;?></h3>								
								<?php
								}
								?>								
								<h5><?php echo __('Date','gym_mgt')." : ".MJ_gmgt_getdate_in_input_box($issue_date);?></h5>
								<h5><?php echo __('Status','gym_mgt')." : ". __($payment_status,'gym_mgt');?></h5>									
							</td>							
						</tr>									
					</tbody>
				</table>						
				<?php
				if($_POST['invoice_type']=='membership_invoice')
				{ 
				?>	
					<table class="width_100">	
						<tbody>	
							<tr>
								<td>
									<h3  class="entry_lable"><?php _e('Membership Entries','gym_mgt');?></h3>
								</td>	
							</tr>	
						</tbody>
					</table>	
					
				<?php 	
				}				
				elseif($_POST['invoice_type']=='income')
				{ 
				?>	
					<table class="width_100">	
						<tbody>	
							<tr>
								<td>
									<h3  class="entry_lable"><?php _e('Income Entries','gym_mgt');?></h3>
								</td>	
							</tr>	
						</tbody>
					</table>
				
				<?php 	
				}
				elseif($_POST['invoice_type']=='sell_invoice')
				{ 
				   ?>
				   <table class="width_100">	
						<tbody>	
							<tr>
								<td>
									<h3  class="entry_lable"><?php _e('Sells Product','gym_mgt');?></h3>
								</td>	
							</tr>	
						</tbody>
					</table>
					
				  <?php
				}
				else
				{ ?>
					<table class="width_100">	
						<tbody>	
							<tr>
								<td>
									<h3  class="entry_lable"><?php _e('Expense Entries','gym_mgt');?></h3>
								</td>	
							</tr>	
						</tbody>
					</table>	
					 
				<?php 	
				}
			   ?>
					
				<table class="table table-bordered" class="width_93" border="1">
					<thead class="entry_heading">
						<?php
						if($_POST['invoice_type']=='membership_invoice')
						{
						?>
							<tr>
								<th class="color_white align_center">#</th>
								<th class="color_white align_center"> <?php _e('DATE','gym_mgt');?></th>
								<th class="width_40 color_white"><?php _e('Fees Type','gym_mgt');?> </th>
								<th class="color_white align_right"><?php _e('Amount','gym_mgt');?> (<?php echo MJ_gmgt_get_currency_symbol(get_option( 'gmgt_currency_code' )); ?>)</th>								
							</tr>	
						<?php
						}
						elseif($_POST['invoice_type']=='sell_invoice')
						{  
						?>
							<tr>
								<th class="color_white align_center">#</th>
								<th class="color_white align_center"> <?php _e('DATE','gym_mgt');?></th>
								<th class="width_40 color_white"><?php _e('PRODUCT NAME','gym_mgt');?> </th>
								<th class="width_3 color_white"><?php _e('QUANTITY','gym_mgt');?></th>
								<th class="color_white"><?php _e('PRICE','gym_mgt');?> (<?php echo MJ_gmgt_get_currency_symbol(get_option( 'gmgt_currency_code' )); ?>)</th>
								<th class="color_white align_right"><?php _e('TOTAL','gym_mgt');?> (<?php echo MJ_gmgt_get_currency_symbol(get_option( 'gmgt_currency_code' )); ?>)</th>								
							</tr>
						<?php 
						} 
						else
						{ 
						?>
							<tr>
								<th class="color_white align_center">#</th>
								<th class="color_white align_center"> <?php _e('DATE','gym_mgt');?></th>
								<th class="width_40 color_white"><?php _e('ENTRY','gym_mgt');?> </th>
								<th class="color_white align_right"><?php _e('Amount','gym_mgt');?> (<?php echo MJ_gmgt_get_currency_symbol(get_option( 'gmgt_currency_code' )); ?>)</th>								
							</tr>	 
						<?php 
						}	
						?>
					</thead>
					<tbody>
						<?php 
							$id=1;
							$i=1;
							$total_amount=0;
						if(!empty($income_data) || !empty($expense_data))
						{
							if(!empty($expense_data))
								$income_data=$expense_data;							
							$member_income=$obj_payment->MJ_gmgt_get_oneparty_income_data_incomeid($income_data->invoice_id);
							
							foreach($member_income as $result_income)
							{
								$income_entries=json_decode($result_income->entry);
								$discount_amount=$result_income->discount;
								$paid_amount=$result_income->paid_amount;
								$total_discount_amount= $result_income->amount - $discount_amount;
								
								if($result_income->tax_id!='')
								{									
									$total_tax=0;
									$tax_array=explode(',',$result_income->tax_id);
									foreach($tax_array as $tax_id)
									{
										$tax_percentage=MJ_gmgt_tax_percentage_by_tax_id($tax_id);
															
										$tax_amount=$total_discount_amount * $tax_percentage / 100;
										
										$total_tax=$total_tax + $tax_amount;				
									}
								}
								else
								{
									$total_tax=$total_discount_amount * $result_income->tax/100;
								}				
								
								$due_amount=0;
								$due_amount=$result_income->total_amount - $result_income->paid_amount;
								$grand_total=$total_discount_amount + $total_tax;
								
							   foreach($income_entries as $each_entry)
							   {
									$total_amount+=$each_entry->amount;
									?>
									<tr class="entry_list">
										<td class="align_center"><?php echo $id;?></td>
										<td class="align_center"><?php echo MJ_gmgt_getdate_in_input_box($result_income->invoice_date);?></td>
										<td><?php echo $each_entry->entry; ?> </td>
										<td class="align_right"><?php echo number_format($each_entry->amount,2); ?></td>
									</tr>
									<?php
									$id+=1;
									$i+=1;
								}
								if($grand_total=='0')									
								{	
									if($income_data->payment_status=='Paid')
									{
										
										$grand_total=$total_amount;
										$paid_amount=$total_amount;
										$due_amount=0;										
									}
									else
									{
										
										$grand_total=$total_amount;
										$paid_amount=0;
										$due_amount=$total_amount;															
									}
								}
							}
						}
							
						if(!empty($membership_data))
						{
							//$total_amount=$membership_data->total_amount;							
							$membership_signup_amounts=$membership_data->membership_signup_amount;
							?>
							<tr class="entry_list">
								<td class="align_center"><?php echo $i;?></td> 
								<td class="align_center"><?php echo MJ_gmgt_getdate_in_input_box($membership_data->created_date);?></td> 
								<td><?php echo MJ_gmgt_get_membership_name($membership_data->membership_id);?></td>								
								<td class="align_right"><?php echo number_format($membership_data->membership_fees_amount,2); ?></td>
							</tr>
							<?php 
							if( $membership_signup_amounts  > 0) 
							{
							?>
							<tr class="entry_list">
								<td class="align_center"><?php echo 2 ;?></td> 
								<td class="align_center"><?php echo MJ_gmgt_getdate_in_input_box($membership_data->created_date);?></td> 
								<td><?php _e('Membership Signup Fee','gym_mgt');?></td>								
								<td class="align_right"><?php echo number_format($membership_data->membership_signup_amount,2); ?></td>
							</tr>
							<?php
							}
						}
						if(!empty($selling_data))
						{								
							$all_entry=json_decode($selling_data->entry);
							
							if(!empty($all_entry))
							{
								foreach($all_entry as $entry)
								{
									$obj_product=new MJ_Gmgtproduct;
									$product = $obj_product->MJ_gmgt_get_single_product($entry->entry);
									
										$product_name=$product->product_name;					
										$quentity=$entry->quentity;	
										$price=$product->price;										
									?>
									<tr class="entry_list">										
										<td class="align_center"><?php echo $i;?></td> 
										<td class="align_center"><?php echo MJ_gmgt_getdate_in_input_box($selling_data->sell_date);?></td>
										<td><?php echo $product_name;?> </td>
										<td  class="width_3"> <?php echo $quentity; ?></td>
										<td><?php echo $price; ?></td>
										<td class="align_right"><?php echo number_format($quentity * $price,2); ?></td>
									</tr>
								<?php 
								$id+=1;
								$i+=1;
								}
							}
							else
							{
								$obj_product=new MJ_Gmgtproduct;
								$product = $obj_product->MJ_gmgt_get_single_product($selling_data->product_id); 
								
								$product_name=$product->product_name;					
								$quentity=$selling_data->quentity;	
								$price=$product->price;	
								?>
								<tr class="entry_list">										
									<td class="align_center"><?php echo $i;?></td> 
									<td class="align_center"><?php echo MJ_gmgt_getdate_in_input_box($selling_data->sell_date);?></td>
									<td><?php echo $product_name;?> </td>
									<td  class="width_3"> <?php echo $quentity; ?></td>
									<td> <?php echo $price; ?></td>
									<td class="align_right"> <?php echo number_format($quentity * $price,2); ?></td>
								</tr>
								<?php
								$id+=1;
								$i+=1;
							}	
						}
							
						?>							
					</tbody>
				</table>
				<table class="width_54" border="0">
					<tbody>
						<?php 
						if(!empty($membership_data))
						{
							$total_amount=$membership_data->membership_fees_amount+$membership_data->membership_signup_amount;
							$total_tax=$membership_data->tax_amount;							
							$paid_amount=$membership_data->paid_amount;
							$due_amount=abs($membership_data->membership_amount - $paid_amount);
							$grand_total=$membership_data->membership_amount;							
						}
						if(!empty($expense_data))
						{
							$grand_total=$total_amount;
						}
						if(!empty($selling_data))
						{
							$all_entry=json_decode($selling_data->entry);
							
							if(!empty($all_entry))
							{
								$total_amount=$selling_data->amount;
								$discount_amount=$selling_data->discount;
								$total_discount_amount=$total_amount-$discount_amount;
								
								if($selling_data->tax_id!='')
								{									
									$total_tax=0;
									$tax_array=explode(',',$selling_data->tax_id);
									foreach($tax_array as $tax_id)
									{
										$tax_percentage=MJ_gmgt_tax_percentage_by_tax_id($tax_id);
															
										$tax_amount=$total_discount_amount * $tax_percentage / 100;
										
										$total_tax=$total_tax + $tax_amount;				
									}
								}
								else
								{
									$tax_per=$selling_data->tax;
									$total_tax=$total_discount_amount * $tax_per/100;
								}
								
								$paid_amount=$selling_data->paid_amount;
								$due_amount=abs($selling_data->total_amount - $paid_amount);
								$grand_total=$selling_data->total_amount;
							}
							else
							{	
								$obj_product=new MJ_Gmgtproduct;
								$product = $obj_product->MJ_gmgt_get_single_product($selling_data->product_id);
								$price=$product->price;	
								
								$total_amount=$price*$selling_data->quentity;
								$discount_amount=$selling_data->discount;
								$total_discount_amount=$total_amount-$discount_amount;
								
								if($selling_data->tax_id!='')
								{									
									$total_tax=0;
									$tax_array=explode(',',$selling_data->tax_id);
									foreach($tax_array as $tax_id)
									{
										$tax_percentage=MJ_gmgt_tax_percentage_by_tax_id($tax_id);
															
										$tax_amount=$total_discount_amount * $tax_percentage / 100;
										
										$total_tax=$total_tax + $tax_amount;				
									}
								}
								else
								{
									$tax_per=$selling_data->tax;
									$total_tax=$total_discount_amount * $tax_per/100;
								}
																
								$paid_amount=$total_amount;
								$due_amount='0';
								$grand_total=$total_amount;								
							}		
						}							
						?>
						<tr>
							<h4><td class="width_70 align_right"><h4 class="margin"><?php _e('Subtotal :','gym_mgt');?></h4></td>
							<td class="align_right"> <h4 class="margin"><span style=""><?php echo MJ_gmgt_get_currency_symbol(get_option( 'gmgt_currency_code' ));?></span><?php echo number_format($total_amount,2);?></h4></td>
						</tr>
						<?php
						if($_POST['invoice_type']!='expense')
						{
							if($_POST['invoice_type']!='membership_invoice')
							{
							?>	
							<tr>
								<td class="width_70 align_right"><h4><?php _e('Discount Amount :','gym_mgt');?></h4></td>
								<td class="align_right"> <h4 class="margin"><span ><?php  echo "-";echo MJ_gmgt_get_currency_symbol(get_option( 'gmgt_currency_code' ));?></span><?php echo number_format($discount_amount,2); ?></h4></td>
							</tr>
							<?php
							}
							?>
							<tr>
								<td class="width_70 align_right"><h4><?php _e('Tax Amount :','gym_mgt');?></h4></td>
								<td class="align_right"><h4 class="margin"> <span ><?php  echo "+";echo MJ_gmgt_get_currency_symbol(get_option( 'gmgt_currency_code' ));?></span><?php echo number_format($total_tax,2); ?></h4></td>
							</tr>							
							<tr>
								<td class="width_70 align_right"><h4><?php _e('Due Amount :','gym_mgt');?></h4></td>
								<td class="align_right"> <h4 class="margin"><span ><?php  echo MJ_gmgt_get_currency_symbol(get_option( 'gmgt_currency_code' ));?></span><?php echo number_format($due_amount,2); ?></h4></td>
							</tr>
							<tr>
								<td class="width_70 align_right"><h4><?php _e('Paid Amount :','gym_mgt');?></h4></td>
								<td class="align_right"> <h4 class="margin"><span ><?php  echo MJ_gmgt_get_currency_symbol(get_option( 'gmgt_currency_code' ));?></span><?php echo number_format($paid_amount,2); ?></h4></td>
							</tr>
						<?php
						}
						?>
						<tr>							
							<td class="width_70 align_right grand_total_lable" style="margin-right: 2px;"><h3 class="color_white margin"><?php _e('Grand Total :','gym_mgt');?></h3></td>
							<td class="align_right grand_total_amount"><h3 class="color_white margin">  <span><?php echo MJ_gmgt_get_currency_symbol(get_option( 'gmgt_currency_code' )); ?> <?php echo number_format($grand_total,2); ?> </span></h3></td>
						</tr>							
					</tbody>
				</table>
				<?php
				if($_POST['invoice_type']!='expense')
				{
				?>		
				<table class="width_46" border="0">
					<tbody>						
						<tr>
							<td colspan="2">
								<h3 class="payment_method_lable"><?php _e('Payment Method','gym_mgt');?>
								</h3>
							</td>								
						</tr>							
						<tr>
							<td class="width_31 font_12"><?php _e('Bank Name ','gym_mgt');  ?></td>
							<td class="font_12">: <?php echo get_option( 'gmgt_bank_name' );?></td>
						</tr>
						<tr>
							<td class="width_31 font_12"><?php _e('Account No ','gym_mgt'); ?></td>
							<td class="font_12">: <?php echo get_option( 'gmgt_bank_acount_number' );?></td>
						</tr>
						
						<tr>
							<td class="width_31 font_12"><?php _e('IFSC Code ','gym_mgt'); ?></td>
							<td class="font_12">: <?php echo get_option( 'gmgt_bank_ifsc_code' );?></td>
						</tr>
						
						<tr>
							<td class="width_31 font_12"> <?php _e('Paypal Id ','gym_mgt'); ?></td>
							<td class="font_12">: <?php echo get_option( 'gmgt_paypal_email' );?></td>
						</tr>
					</tbody>
				</table>
					<?php				
					if(!empty($history_detail_result))
					{
					?>
						<hr class="width_100 flot_left_invoice_history_hr">
						<table class="width_100">	
							<tbody>	
								<tr>
									<td>
										<h3  class="entry_lable"><?php _e('Payment History','gym_mgt');?></h3>
									</td>	
								</tr>	
							</tbody>
						</table>
						<table class="table table-bordered" width="100%" border="1" style="border-collapse:collapse;">
							<thead class="entry_heading">
								<tr>							
									<th class="color_white align_center"> <?php _e('Date','gym_mgt');?></th>
									<th class="width_40 color_white align_center"><?php _e('Amount','gym_mgt');?> (<?php echo MJ_gmgt_get_currency_symbol(get_option( 'gmgt_currency_code' )); ?>)</th>
									<th class="color_white align_center"><?php _e('Method','gym_mgt');?></th>
									<th class="color_white align_center"><?php _e('Payment Details','gym_mgt');?></th>
								</tr>	
							</thead>
							<tbody>
								<?php 
								foreach($history_detail_result as  $retrive_data)
								{
									?>
									<tr class="entry_list">
										<td class="align_center"><?php echo MJ_gmgt_getdate_in_input_box($retrive_data->paid_by_date);?></td> 
										<td class="align_center"><?php echo $retrive_data->amount; ?></td> 
										<td class="align_center"><?php echo  $retrive_data->payment_method; ?></td>
										<td class="align_center"><?php if(!empty($retrive_data->payment_description)){ echo  $retrive_data->payment_description; }else{ echo '-'; }?></td>
									</tr>
									<?php 
								}
								?>
							</tbody>
						</table>
					<?php 
					}
				}
				?>
			</div>
		</div>
		<div class="print-button pull-left">
			<a  href="?page=invoice&print=print&invoice_id=<?php echo $_POST['idtest'];?>&invoice_type=<?php echo $_POST['invoice_type'];?>" target="_blank"class="btn btn-success"><?php _e('Print','gym_mgt');?></a>
			<?php
			if($_POST['invoice_type']!='expense')
			{
			?>	
				<a  href="?page=invoice&pdf=pdf&invoice_id=<?php echo $_POST['idtest'];?>&invoice_type=<?php echo $_POST['invoice_type'];?>" target="_blank"class="btn btn-success"><?php _e('PDF','gym_mgt');?></a>			
			<?php
			}
			?>
		</div>
	</div>		
	<?php 
	die();
}
//PRINT INIT FUNCTION
function MJ_gmgt_print_init()
{
	if(isset($_REQUEST['print']) && $_REQUEST['print'] == 'print' && $_REQUEST['page'] == 'invoice')
	{
		?>
		<script>window.onload = function(){ window.print(); };</script>
		<?php 
				
		MJ_gmgt_invoice_print($_REQUEST['invoice_id'],$_REQUEST['invoice_type']);
		exit;
	}			
}

add_action('init','MJ_gmgt_print_init');
//print invoice FUNCTION
function MJ_gmgt_invoice_print($invoice_id,$type)
{
	$obj_payment= new MJ_Gmgtpayment();
	if($type=='membership_invoice')
	{		
		$obj_membership_payment=new MJ_Gmgt_membership_payment;	
		$membership_data=$obj_membership_payment->MJ_gmgt_get_single_membership_payment($invoice_id);
		$history_detail_result = MJ_gmgt_get_payment_history_by_mpid($invoice_id);		
	}
	if($type=='income')
	{
		$income_data=$obj_payment->MJ_gmgt_get_income_data($invoice_id);
		$history_detail_result = MJ_gmgt_get_income_payment_history_by_mpid($invoice_id);
		
	}
	if($type=='expense')
	{
		$expense_data=$obj_payment->MJ_gmgt_get_income_data($invoice_id);
	}
	if($type=='sell_invoice')
	{
		$obj_store=new MJ_Gmgtstore;
		$selling_data=$obj_store->MJ_gmgt_get_single_selling($invoice_id);
		$history_detail_result = MJ_gmgt_get_sell_payment_history_by_mpid($invoice_id);
	}
  echo '<link rel="stylesheet" href="'.plugins_url( '/assets/css/style.css', __FILE__).'"></link>';	
	?>	
	<div class="modal-header">		
		<h4 class="modal-title"><?php echo get_option('gmgt_system_name','gym_mgt'); ?></h4>			
	</div>
	<div class="modal-body invoice_body">
		<div id="invoice_print1">
			<img class="invoicefont1" src="<?php echo plugins_url('/gym-management/assets/images/invoice.jpg'); ?>" width="100%">
			<div class="main_div">					
				<table class="width_100" border="0">					
					<tbody>
						<tr>
							<td class="width_1">
								<img class="system_logo" src="<?php echo get_option( 'gmgt_system_logo' ); ?>">
							</td>							
							<td class="only_width_20">
								<?php
								 echo "A. ".chunk_split(get_option( 'gmgt_gym_address' ),30,"<BR>"); 
								 echo "E. ".get_option( 'gmgt_email' )."<br>"; 
								 echo "P. " .get_option( 'gmgt_contact_number' )."<br>"; 
								?> 
							</td>
							<td align="right" class="width_24">
							</td>
						</tr>
					</tbody>
				</table>
				<table class="width_50" border="0">
					<tbody>				
						<tr>
							<td colspan="2"  class="billed_to1" align="center">								
								<h3 class="billed_to_lable"><?php _e('| Bill To.','gym_mgt');?> </h3>
							</td>
							<td class="width_40">								
							<?php 
								if(!empty($expense_data))
								{
								   echo "<h3 class='display_name'>".chunk_split(ucwords($expense_data->supplier_name),30,"<BR>"). "</h3>"; 
								}
								else
								{
									if(!empty($income_data))
										$member_id=$income_data->supplier_name;
									 if(!empty($membership_data))
										$member_id=$membership_data->member_id;
									 if(!empty($selling_data))
										$member_id=$selling_data->member_id;
									$patient=get_userdata($member_id);
									 echo "<h3 style='font-weight: bold;'>".chunk_split(ucwords($patient->display_name),30,"<BR>"). "</h3>"; 
									 $address=get_user_meta( $member_id,'address',true);
									 echo chunk_split($address,30,"<BR>"); 	
									 echo get_user_meta( $member_id,'city_name',true ).","; 
									 echo get_user_meta( $member_id,'zip_code',true )."<br>"; 
									echo get_user_meta( $member_id,'mobile',true )."<br>"; 
								}
							?>			
							</td>
						</tr>									
					</tbody>
				</table>
					<?php 
					$issue_date='DD-MM-YYYY';
					if(!empty($income_data))
					{
						$issue_date=$income_data->invoice_date;
						$payment_status=$income_data->payment_status;
						$invoice_no=$income_data->invoice_no;
					}
					if(!empty($membership_data))
					{
						$issue_date=$membership_data->created_date;
						if($membership_data->payment_status!='0')
						{	
							$payment_status=$membership_data->payment_status;
						}
						else
						{
							$payment_status='Unpaid';
						}			
						$invoice_no=$membership_data->invoice_no;
					}
					if(!empty($expense_data))
					{
						$issue_date=$expense_data->invoice_date;
						$payment_status=$expense_data->payment_status;
						$invoice_no=$expense_data->invoice_no;
					}
					if(!empty($selling_data))
					{
						$issue_date=$selling_data->sell_date;						
						if(!empty($selling_data->payment_status))
						{
							$payment_status=$selling_data->payment_status;
						}	
						else
						{
							$payment_status='Fully Paid';
						}	
						$invoice_no=$selling_data->invoice_no;
					} 
					?>
				<table class="width_50" border="0">
					<tbody>				
						<tr>	
							<td class="width_30">
							</td>
							<td class="width_20" align="left">
								<?php
								if($type!='expense')
								{
								?>	
									<h3 class="invoice_color"><?php echo __('INVOICE','gym_mgt')." </br> #".$invoice_no;?></h3>								
								<?php
								}
								?>								
								<h5 class="invoice_date_status"> <?php   echo __('Date','gym_mgt')." : ".MJ_gmgt_getdate_in_input_box($issue_date);?></h5>
								<h5 class="invoice_date_status"><?php echo __('Status','gym_mgt')." : ". __($payment_status,'gym_mgt');?></h5>									
							</td>							
						</tr>									
					</tbody>
				</table>						
				<?php
				if($type=='membership_invoice')
				{ 
				?>	
					<table class="width_100">	
						<tbody>	
							<tr>
								<td>
									<h3  class="entry_lable"><?php _e('Membership Entries','gym_mgt');?></h3>
								</td>	
							</tr>	
						</tbody>
					</table>	
					
				<?php 	
				}				
				elseif($type=='income')
				{ 
				?>	
					<table class="width_100">	
						<tbody>	
							<tr>
								<td>
									<h3  class="entry_lable"><?php _e('Income Entries','gym_mgt');?></h3>
								</td>	
							</tr>	
						</tbody>
					</table>
				
				<?php 	
				}
				elseif($type=='sell_invoice')
				{ 
				   ?>
				   <table class="width_100">	
						<tbody>	
							<tr>
								<td>
									<h3  class="entry_lable"><?php _e('Sells Product','gym_mgt');?></h3>
								</td>	
							</tr>	
						</tbody>
					</table>
					
				  <?php
				}
				else
				{ ?>
					<table class="width_100">	
						<tbody>	
							<tr>
								<td>
									<h3  class="entry_lable"><?php _e('Expense Entries','gym_mgt');?></h3>
								</td>	
							</tr>	
						</tbody>
					</table>	
					 
				<?php 	
				}
			   ?>					
				<table class="table table-bordered width_100" class="width_93" border="1" style="border-collapse:collapse;">
					<thead class="entry_heading_print">
						<?php
						if($type=='membership_invoice')
						{
						?>
							<tr>
								<th class="color_white align_center">#</th>
								<th class="color_white align_center"> <?php _e('DATE','gym_mgt');?></th>
								<th class="width_40 color_white"><?php _e('Fees Type','gym_mgt');?> </th>
								<th class="align_right color_white"><?php _e('Amount','gym_mgt');?> (<?php echo MJ_gmgt_get_currency_symbol(get_option( 'gmgt_currency_code' )); ?>)</th>								
							</tr>	
						<?php
						}
						elseif($type=='sell_invoice')
						{  
						?>
							<tr>
								<th class="color_white align_center">#</th>
								<th class="color_white align_center"> <?php _e('DATE','gym_mgt');?></th>
								<th class="width_40 color_white"><?php _e('PRODUCT NAME','gym_mgt');?> </th>
								<th class="width_3 color_white"><?php _e('QUANTITY','gym_mgt');?></th>
								<th class="color_white"><?php _e('PRICE','gym_mgt');?> (<?php echo MJ_gmgt_get_currency_symbol(get_option( 'gmgt_currency_code' )); ?>)</th>
								<th class="color_white align_right"><?php _e('TOTAL','gym_mgt');?> (<?php echo MJ_gmgt_get_currency_symbol(get_option( 'gmgt_currency_code' )); ?>)</th>								
							</tr>
						<?php 
						} 
						else
						{ 
						?>
							<tr>
								<th class="color_white align_center">#</th>
								<th class="color_white align_center"> <?php _e('DATE','gym_mgt');?></th>
								<th class="width_40 color_white"><?php _e('ENTRY','gym_mgt');?> </th>
								<th class="color_white align_right"><?php _e('Amount','gym_mgt');?> (<?php echo MJ_gmgt_get_currency_symbol(get_option( 'gmgt_currency_code' )); ?>)</th>								
							</tr>	 
						<?php 
						}	
						?>
					</thead>
					<tbody>
						<?php 
							$id=1;
							$i=1;
							$total_amount=0;
						if(!empty($income_data) || !empty($expense_data))
						{
							if(!empty($expense_data))
								$income_data=$expense_data;							
							$member_income=$obj_payment->MJ_gmgt_get_oneparty_income_data_incomeid($income_data->invoice_id);
							
							foreach($member_income as $result_income)
							{
								$income_entries=json_decode($result_income->entry);
								$discount_amount=$result_income->discount;
								$paid_amount=$result_income->paid_amount;
								$total_discount_amount= $result_income->amount - $discount_amount;								
				               
								if($result_income->tax_id!='')
								{									
									$total_tax=0;
									$tax_array=explode(',',$result_income->tax_id);
									foreach($tax_array as $tax_id)
									{
										$tax_percentage=MJ_gmgt_tax_percentage_by_tax_id($tax_id);
															
										$tax_amount=$total_discount_amount * $tax_percentage / 100;
										
										$total_tax=$total_tax + $tax_amount;				
									}
								}
								else
								{
									$total_tax=$total_discount_amount * $result_income->tax/100;
								}
								$due_amount=0;
								$due_amount=$result_income->total_amount - $result_income->paid_amount;
								$grand_total=$total_discount_amount + $total_tax;
								
							   foreach($income_entries as $each_entry)
							   {
									$total_amount+=$each_entry->amount;
									?>
									<tr class="entry_list">
										<td class="align_center"><?php echo $id;?></td>
										<td class="align_center"><?php echo MJ_gmgt_getdate_in_input_box($result_income->invoice_date);?></td>
										<td><?php echo $each_entry->entry; ?> </td>
										<td class="align_right"><?php echo number_format($each_entry->amount,2); ?></td>
									</tr>
									<?php $id+=1;
									$i+=1;
								}
								if($grand_total=='0')									
								{
									if($income_data->payment_status=='Paid')
									{
										
										$grand_total=$total_amount;
										$paid_amount=$total_amount;
										$due_amount=0;										
									}
									else
									{
										
										$grand_total=$total_amount;
										$paid_amount=0;
										$due_amount=$total_amount;															
									}
								}
							}
						}
							
						if(!empty($membership_data))
						{
							//$total_amount=$membership_data->total_amount;
							$membership_signup_amounts=$membership_data->membership_signup_amount;
							?>
							<tr class="entry_list">
								<td class="align_center"><?php echo $i;?></td> 
								<td class="align_center"><?php echo MJ_gmgt_getdate_in_input_box($membership_data->created_date);?></td> 
								<td><?php echo MJ_gmgt_get_membership_name($membership_data->membership_id);?></td>								
								<td class="align_right"><?php echo number_format($membership_data->membership_fees_amount,2); ?></td>
							</tr>
							
							<?php 
							if( $membership_signup_amounts  > 0) 
							{
							?>
							<tr class="entry_list">
								<td class="align_center"><?php echo 2 ;?></td> 
								<td class="align_center"><?php echo MJ_gmgt_getdate_in_input_box($membership_data->created_date);?></td> 
								<td><?php _e('Membership Signup Fee','gym_mgt');?></td>								
								<td class="align_right"><?php echo number_format($membership_data->membership_signup_amount,2); ?></td>
							</tr>
							<?php
							}
						}
						if(!empty($selling_data))
						{
								
							$all_entry=json_decode($selling_data->entry);
							
							if(!empty($all_entry))
							{
								foreach($all_entry as $entry)
								{
									$obj_product=new MJ_Gmgtproduct;
									$product = $obj_product->MJ_gmgt_get_single_product($entry->entry);
									
									$product_name=$product->product_name;					
									$quentity=$entry->quentity;	
									$price=$product->price;	
									
									?>
									<tr class="entry_list">										
										<td class="align_center"><?php echo $i;?></td> 
										<td class="align_center"><?php echo MJ_gmgt_getdate_in_input_box($selling_data->sell_date);?></td>
										<td><?php echo $product_name;?> </td>
										<td  class="width_3"> <?php echo $quentity; ?></td>
										<td><?php echo $price; ?></td>
										<td class="align_right"><?php echo number_format($quentity * $price,2); ?></td>
									</tr>
								<?php 
								$id+=1;
								$i+=1;
								}
							}
							else
							{
								$obj_product=new MJ_Gmgtproduct;
								$product = $obj_product->MJ_gmgt_get_single_product($selling_data->product_id); 
								
								$product_name=$product->product_name;					
								$quentity=$selling_data->quentity;	
								$price=$product->price;	
								?>
								<tr class="entry_list">										
									<td class="align_center"><?php echo $i;?></td> 
									<td class="align_center"><?php echo MJ_gmgt_getdate_in_input_box($selling_data->sell_date);?></td>
									<td><?php echo $product_name;?> </td>
									<td  class="width_3"> <?php echo $quentity; ?></td>
									<td> <?php echo $price; ?></td>
									<td class="align_right"><?php echo number_format($quentity * $price,2); ?></td>
								</tr>
								<?php
								$id+=1;
								$i+=1;
							}		
						}
						?>							
					</tbody>
				</table>
				<table class="width_54" border="0">
					<tbody>
						<?php 
						if(!empty($membership_data))
						{							
							$total_amount=$membership_data->membership_fees_amount+$membership_data->membership_signup_amount;
							$total_tax=$membership_data->tax_amount;		
							$paid_amount=$membership_data->paid_amount;
							$due_amount=abs($membership_data->membership_amount - $paid_amount);
							$grand_total=$membership_data->membership_amount;
							
						}
						if(!empty($expense_data))
						{
							$grand_total=$total_amount;
						}
						if(!empty($selling_data))
						{
							$all_entry=json_decode($selling_data->entry);
							
							if(!empty($all_entry))
							{
								$total_amount=$selling_data->amount;
								$discount_amount=$selling_data->discount;
								$total_discount_amount=$total_amount-$discount_amount;
								
								if($selling_data->tax_id!='')
								{									
									$total_tax=0;
									$tax_array=explode(',',$selling_data->tax_id);
									foreach($tax_array as $tax_id)
									{
										$tax_percentage=MJ_gmgt_tax_percentage_by_tax_id($tax_id);
															
										$tax_amount=$total_discount_amount * $tax_percentage / 100;
										
										$total_tax=$total_tax + $tax_amount;				
									}
								}
								else
								{
									$tax_per=$selling_data->tax;
									$total_tax=$total_discount_amount * $tax_per/100;
								}
																
								$paid_amount=$selling_data->paid_amount;
								$due_amount=abs($selling_data->total_amount - $paid_amount);
								$grand_total=$selling_data->total_amount;
							}
							else
							{
								$obj_product=new MJ_Gmgtproduct;
								$product = $obj_product->MJ_gmgt_get_single_product($selling_data->product_id);								
								
								$price=$product->price;	
								
								$total_amount=$price*$selling_data->quentity;
								$discount_amount=$selling_data->discount;
								$total_discount_amount=$total_amount-$discount_amount;
								
								if($selling_data->tax_id!='')
								{									
									$total_tax=0;
									$tax_array=explode(',',$selling_data->tax_id);
									foreach($tax_array as $tax_id)
									{
										$tax_percentage=MJ_gmgt_tax_percentage_by_tax_id($tax_id);
															
										$tax_amount=$total_discount_amount * $tax_percentage / 100;
										
										$total_tax=$total_tax + $tax_amount;				
									}
								}
								else
								{
									$tax_per=$selling_data->tax;
									$total_tax=$total_discount_amount * $tax_per/100;
								}
								
								$paid_amount=$total_amount;
								$due_amount='0';
								$grand_total=$total_amount;
							}
							
						}							
						?>
						<tr>
							<h4><td class="width_70 align_right"><h4 class="margin"><?php _e('Subtotal :','gym_mgt');?></h4></td>
							<td class="align_right"> <h4 class="margin"><span style=""><?php echo MJ_gmgt_get_currency_symbol(get_option( 'gmgt_currency_code' ));?></span><?php echo number_format($total_amount,2);?></h4></td>
						</tr>
						<?php
						if($type!='expense')
						{
							if($type!='membership_invoice')
							{
							?>	
							<tr>
								<td class="width_70 align_right"><h4 class="margin"><?php _e('Discount Amount :','gym_mgt');?></h4></td>
								<td class="align_right"> <h4 class="margin"><span ><?php  echo "-";echo MJ_gmgt_get_currency_symbol(get_option( 'gmgt_currency_code' ));?></span><?php echo number_format($discount_amount,2); ?></h4></td>
							</tr>
							<?php
							}
							?>
							<tr>
								<td class="width_70 align_right"><h4 class="margin"><?php _e('Tax Amount :','gym_mgt');?></h4></td>
								<td class="align_right"><h4 class="margin"> <span ><?php  echo "+";echo MJ_gmgt_get_currency_symbol(get_option( 'gmgt_currency_code' ));?></span><?php echo number_format($total_tax,2); ?></h4></td>
							</tr>							
							<tr>
								<td class="width_70 align_right"><h4 class="margin"><?php _e('Due Amount :','gym_mgt');?></h4></td>
								<td class="align_right"> <h4 class="margin"><span ><?php  echo MJ_gmgt_get_currency_symbol(get_option( 'gmgt_currency_code' ));?></span><?php echo number_format($due_amount,2); ?></h4></td>
							</tr>
							<tr>
								<td class="width_70 align_right"><h4 class="margin"><?php _e('Paid Amount :','gym_mgt');?></h4></td>
								<td class="align_right"> <h4 class="margin"><span ><?php  echo MJ_gmgt_get_currency_symbol(get_option( 'gmgt_currency_code' ));?></span><?php echo number_format($paid_amount,2); ?></h4></td>
							</tr>
						<?php
						}
						?>
						<tr>							
							<td class="width_70 align_right grand_total_lable1" style="margin-right: 2px;"><h3 class="color_white margin"><?php _e('Grand Total :','gym_mgt');?></h3></td>
							<td class="align_right grand_total_amount1"><h3 class="color_white margin">  <span><?php echo MJ_gmgt_get_currency_symbol(get_option( 'gmgt_currency_code' )); ?> <?php echo number_format($grand_total,2); ?> </span></h3></td>
						</tr>							
					</tbody>
				</table>
				<?php
				if($type!='expense')
				{
				?>		
				<table class="width_46" border="0">
					<tbody>						
						<tr>
							<td colspan="2">
								<h3 class="payment_method_lable"><?php _e('Payment Method','gym_mgt');?>
								</h3>
							</td>								
						</tr>							
						<tr>
							<td class="width_31 font_12"><?php _e('Bank Name ','gym_mgt');  ?></td>
							<td class="font_12">: <?php echo get_option( 'gmgt_bank_name' );?></td>
						</tr>
						<tr>
							<td class="width_31 font_12"><?php _e('Account No ','gym_mgt'); ?></td>
							<td class="font_12">: <?php echo get_option( 'gmgt_bank_acount_number' );?></td>
						</tr>
						
						<tr>
							<td class="width_31 font_12"><?php _e('IFSC Code ','gym_mgt'); ?></td>
							<td class="font_12">: <?php echo get_option( 'gmgt_bank_ifsc_code' );?></td>
						</tr>
						
						<tr>
							<td class="width_31 font_12"> <?php _e('Paypal Id ','gym_mgt'); ?></td>
							<td class="font_12">: <?php echo get_option( 'gmgt_paypal_email' );?></td>
						</tr>
					</tbody>
				</table>				
					<?php				
					if(!empty($history_detail_result))
					{
					?>
						<hr class="width_100 flot_left_invoice_history_hr">						
						<table class="width_100">	
							<tbody>	
								<tr>
									<td>
										<h3  class="entry_lable"><?php _e('Payment History','gym_mgt');?></h3>
									</td>	
								</tr>	
							</tbody>
						</table>
						<table class="table table-bordered" width="100%" border="1" style="border-collapse:collapse;">
							<thead class="entry_heading_print">
								<tr>							
									<th class="color_white align_center"> <?php _e('Date','gym_mgt');?></th>
									<th class="width_40 color_white align_center"><?php _e('Amount','gym_mgt');?> (<?php echo MJ_gmgt_get_currency_symbol(get_option( 'gmgt_currency_code' )); ?>)</th>
									<th class="color_white align_center"><?php _e('Method','gym_mgt');?></th>
									<th class="color_white align_center"><?php _e('Payment Details','gym_mgt');?></th>
								</tr>	
							</thead>
							<tbody>
								<?php 
								foreach($history_detail_result as  $retrive_data)
								{
									?>
									<tr class="entry_list">
										<td class="align_center"><?php echo MJ_gmgt_getdate_in_input_box($retrive_data->paid_by_date);?></td> 
										<td class="align_center"><?php echo $retrive_data->amount; ?></td> 
										<td class="align_center"><?php echo  $retrive_data->payment_method; ?></td>
										<td class="align_center"><?php if(!empty($retrive_data->payment_description)){ echo  $retrive_data->payment_description; }else{ echo '-'; }?></td>
									</tr>
									<?php 
								}
								?>
							</tbody>
						</table>
					<?php 
					}
				}
				?>
			</div>
		</div>
	<?php
	die();
}
// pdf fuction call on init
function MJ_gmgt_pdf_init()
{
	if (is_user_logged_in ()) 
	{
		if(isset($_REQUEST['pdf']) && $_REQUEST['pdf'] == 'pdf')
		{			
			MJ_gmgt_invoice_pdf($_REQUEST['invoice_id'],$_REQUEST['invoice_type']);
			exit;
		}	
	}
}
add_action('init','MJ_gmgt_pdf_init');

// invoice pdf FUNCTION
function MJ_gmgt_invoice_pdf($id,$type)
{
	error_reporting(0);
	$obj_payment= new MJ_Gmgtpayment();
	if($type=='membership_invoice')
	{		
		$obj_membership_payment=new MJ_Gmgt_membership_payment;	
		$membership_data=$obj_membership_payment->MJ_gmgt_get_single_membership_payment($id);
		$history_detail_result = MJ_gmgt_get_payment_history_by_mpid($id);		
	}
	if($type=='income')
	{
		$income_data=$obj_payment->MJ_gmgt_get_income_data($id);
		$history_detail_result = MJ_gmgt_get_income_payment_history_by_mpid($id);
	}
	{
	if($type=='expense')
		$expense_data=$obj_payment->MJ_gmgt_get_income_data($id);
	}
	if($type=='sell_invoice')
	{
		$obj_store=new MJ_Gmgtstore;
		$selling_data=$obj_store->MJ_gmgt_get_single_selling($id);
		$history_detail_result = MJ_gmgt_get_sell_payment_history_by_mpid($id);
	}
    echo '<link rel="stylesheet" href="'.plugins_url( '/assets/css/bootstrap.min.css', __FILE__).'"></link>';
    echo '<script  rel="javascript" src="'.plugins_url( '/assets/js/bootstrap.min.js', __FILE__).'"></script>';
	ob_clean();
	header('Content-type: application/pdf');
	header('Content-Disposition: inline; filename="invoice.pdf"');
	header('Content-Transfer-Encoding: binary');
	header('Accept-Ranges: bytes');	
	require GMS_PLUGIN_DIR. '/lib/mpdf/mpdf.php';	
	$stylesheet = file_get_contents(GMS_PLUGIN_DIR. '/assets/css/custom.css'); // Get css content
	$stylesheet1 = file_get_contents(GMS_PLUGIN_DIR. '/assets/css/style.css'); // Get css content
    $mpdf	=	new mPDF('c','A4','','' , 5 , 5 , 5 , 0 , 0 , 0); 
	$mpdf->debug = true;
	$mpdf->WriteHTML('<html>');
	$mpdf->WriteHTML('<head>');
	$mpdf->WriteHTML('<style></style>');
	$mpdf->WriteHTML($stylesheet,1); // Writing style to pdf
	$mpdf->WriteHTML($stylesheet1,1); // Writing style to pdf
	$mpdf->WriteHTML('</head>');
	$mpdf->WriteHTML('<body>');		
	$mpdf->SetTitle('Income Invoice');
		$mpdf->WriteHTML('<div class="modal-header">');
		$mpdf->WriteHTML('<h4 class="modal-title">'.get_option('gmgt_system_name').'</h4>');
		$mpdf->WriteHTML('</div>');
		$mpdf->WriteHTML('<div id="invoice_print">');		
			$mpdf->WriteHTML('<img class="invoicefont1" src="'.plugins_url('/gym-management/assets/images/invoice.jpg').'" width="100%">');
			$mpdf->WriteHTML('<div class="main_div">');	
			
					$mpdf->WriteHTML('<table class="width_100_print" border="0">');					
					$mpdf->WriteHTML('<tbody>');
						$mpdf->WriteHTML('<tr>');
							$mpdf->WriteHTML('<td class="width_1_print">');
								$mpdf->WriteHTML('<img class="system_logo" style="padding-left:15px;"  src="'.get_option( 'gmgt_system_logo' ).'">');
							$mpdf->WriteHTML('</td>');							
							$mpdf->WriteHTML('<td class="only_width_20_print">');								
								$mpdf->WriteHTML(''.__('A','gym_mgt').'. '.chunk_split(get_option('gmgt_gym_address'),30,"<BR>").'<br>'); 
								 $mpdf->WriteHTML(''.__('E','gym_mgt').'. '.get_option( 'gmgt_email' ).'<br>'); 
								 $mpdf->WriteHTML(''.__('P','gym_mgt').'. '.get_option( 'gmgt_contact_number' ).'<br>'); 
							$mpdf->WriteHTML('</td>');
							$mpdf->WriteHTML('<td align="right" class="width_24">');
							$mpdf->WriteHTML('</td>');
						$mpdf->WriteHTML('</tr>');
					$mpdf->WriteHTML('</tbody>');
				$mpdf->WriteHTML('</table>');
				
				$mpdf->WriteHTML('<table>');
			 $mpdf->WriteHTML('<tr>');
				$mpdf->WriteHTML('<td>');
				
					$mpdf->WriteHTML('<table class="width_50_print"  border="0">');
						$mpdf->WriteHTML('<tbody>');				
						$mpdf->WriteHTML('<tr>');
							$mpdf->WriteHTML('<td colspan="2" class="billed_to_print" align="center">');								
								$mpdf->WriteHTML('<h3 class="billed_to_lable"> | '.__('Bill To','gym_mgt').'. </h3>');
							$mpdf->WriteHTML('</td>');
							$mpdf->WriteHTML('<td class="width_40_print">');								
							
								if(!empty($expense_data))
								{
								  $mpdf->WriteHTML('<h3 class="display_name">'.chunk_split(ucwords($expense_data->supplier_name),30,"<BR>").'</h3>'); 
								}
								else
								{
									if(!empty($income_data))
										$member_id=$income_data->supplier_name;
									 if(!empty($membership_data))
										$member_id=$membership_data->member_id;
									 if(!empty($selling_data))
										$member_id=$selling_data->member_id;
									$patient=get_userdata($member_id);
									
									$mpdf->WriteHTML('<h3 class="display_name">'.chunk_split(ucwords($patient->display_name),30,"<BR>").'</h3>'); 
									 $address=get_user_meta( $member_id,'address',true);									
									 $mpdf->WriteHTML(''.chunk_split($address,30,"<BR>").''); 
									  $mpdf->WriteHTML(''.get_user_meta( $member_id,'city_name',true ).','); 
									 $mpdf->WriteHTML(''.get_user_meta( $member_id,'zip_code',true ).'<br>'); 
									 $mpdf->WriteHTML(''.get_user_meta( $member_id,'mobile',true ).'<br>'); 
								}
									
							 $mpdf->WriteHTML('</td>');
						 $mpdf->WriteHTML('</tr>');									
					 $mpdf->WriteHTML('</tbody>');
				 $mpdf->WriteHTML('</table>');
				
				$mpdf->WriteHTML('</td>');
				$mpdf->WriteHTML('<td>');
				
				   $mpdf->WriteHTML('<table class="width_50_print"  border="0">');
					 $mpdf->WriteHTML('<tbody>');				
						 $mpdf->WriteHTML('<tr>');	
							 $mpdf->WriteHTML('<td class="width_30_print">');
							 $mpdf->WriteHTML('</td>');
							 $mpdf->WriteHTML('<td class="width_20_print invoice_lable" style="padding-right:30px;" align="left">');
								
								$issue_date='DD-MM-YYYY';
								if(!empty($income_data))
								{
									$issue_date=$income_data->invoice_date;
									$payment_status=$income_data->payment_status;
									$invoice_no=$income_data->invoice_no;
								}
								if(!empty($membership_data))
								{
									$issue_date=$membership_data->created_date;
									$payment_status=$membership_data->payment_status;
									$invoice_no=$membership_data->invoice_no;									
								}
								if(!empty($expense_data))
								{
									$issue_date=$expense_data->invoice_date;
									$payment_status=$expense_data->payment_status;
									$invoice_no=$expense_data->invoice_no;
								}
								if(!empty($selling_data))
								{
									$issue_date=$selling_data->sell_date;									
									if(!empty($selling_data->payment_status))
									{
										$payment_status=$selling_data->payment_status;
									}	
									else
									{
										$payment_status='Fully Paid';
									}	
									$invoice_no=$selling_data->invoice_no;
								} 
								
								if($type!='expense')
								{								
									$mpdf->WriteHTML('<h3>'.__('INVOICE','gym_mgt').' <br> #'.$invoice_no.'</h3>');										
								}																			
							 $mpdf->WriteHTML('</td>');							
						 $mpdf->WriteHTML('</tr>');
						 $mpdf->WriteHTML('<tr>');	
							 $mpdf->WriteHTML('<td class="width_30_print">');
							 $mpdf->WriteHTML('</td>');
							 $mpdf->WriteHTML('<td class="width_20_print" style="padding-right:30px;" align="left">');
								$mpdf->WriteHTML('<h5>'.__('Date','gym_mgt').' : '.MJ_gmgt_getdate_in_input_box($issue_date).'</h5>');
							$mpdf->WriteHTML('<h5>'.__('Status','gym_mgt').' : '.__(''.$payment_status.'','gym_mgt').'</h5>');											
							 $mpdf->WriteHTML('</td>');							
						 $mpdf->WriteHTML('</tr>');						
					 $mpdf->WriteHTML('</tbody>');
				 $mpdf->WriteHTML('</table>');	
				$mpdf->WriteHTML('</td>');
			  $mpdf->WriteHTML('</tr>');
			$mpdf->WriteHTML('</table>');
			
				if($type=='membership_invoice')
				{	
					$mpdf->WriteHTML('<table class="width_100_print">');	
						$mpdf->WriteHTML('<tbody>');	
							$mpdf->WriteHTML('<tr>');
								$mpdf->WriteHTML('<td style="padding-left:20px;">');
									$mpdf->WriteHTML('<h3 class="entry_lable">'.__('Membership Entries','gym_mgt').'</h3>');
								$mpdf->WriteHTML('</td>');	
							$mpdf->WriteHTML('</tr>');	
						$mpdf->WriteHTML('</tbody>');
					$mpdf->WriteHTML('</table>');				
					
				}		
				elseif($type=='income')
				{ 
					$mpdf->WriteHTML('<table class="width_100_print">');	
						$mpdf->WriteHTML('<tbody>');	
							$mpdf->WriteHTML('<tr>');
								$mpdf->WriteHTML('<td style="padding-left:20px;">');
									$mpdf->WriteHTML('<h3 class="entry_lable">'.__('Income Entries','gym_mgt').'</h3>');
								$mpdf->WriteHTML('</td>');	
							$mpdf->WriteHTML('</tr>');	
						$mpdf->WriteHTML('</tbody>');
					$mpdf->WriteHTML('</table>');
				
				}
				elseif($type=='sell_invoice')
				{ 
				  $mpdf->WriteHTML('<table class="width_100_print">');	
						$mpdf->WriteHTML('<tbody>');	
							$mpdf->WriteHTML('<tr>');
								$mpdf->WriteHTML('<td style="padding-left:20px;">');
									$mpdf->WriteHTML('<h3 class="entry_lable">'.__('Sells Product','gym_mgt').'</h3>');
								$mpdf->WriteHTML('</td>');	
							$mpdf->WriteHTML('</tr>');	
						$mpdf->WriteHTML('</tbody>');
					$mpdf->WriteHTML('</table>');
				  
				}
				else
				{ 
					$mpdf->WriteHTML('<table class="width_100_print">');	
						$mpdf->WriteHTML('<tbody>');	
							$mpdf->WriteHTML('<tr>');
								$mpdf->WriteHTML('<td style="padding-left:20px;">');
									$mpdf->WriteHTML('<h3 class="entry_lable">'.__('Expense Entries','gym_mgt').'</h3>');
								$mpdf->WriteHTML('</td>');	
							$mpdf->WriteHTML('</tr>');	
						$mpdf->WriteHTML('</tbody>');
					$mpdf->WriteHTML('</table>');	
				}		  
					
				$mpdf->WriteHTML('<table class="table table-bordered" class="width_93" border="1">');
					$mpdf->WriteHTML('<thead>');
						
						if($type=='membership_invoice')
						{						
							$mpdf->WriteHTML('<tr>');
								$mpdf->WriteHTML('<th class="color_white entry_heading align_center">#</th>');
								$mpdf->WriteHTML('<th class="color_white entry_heading align_center">'.__('DATE','gym_mgt').'</th>');
								$mpdf->WriteHTML('<th class="color_white entry_heading align_left">'.__('Fees Type','gym_mgt').'</th>');
								$mpdf->WriteHTML('<th class="color_white entry_heading align_right">'.__('Amount','gym_mgt').'('.MJ_gmgt_get_currency_symbol(get_option( 'gmgt_currency_code' )).')</th>');								
							$mpdf->WriteHTML('</tr>');
						}
						elseif($type=='sell_invoice')
						{  
						
							$mpdf->WriteHTML('<tr>');
								$mpdf->WriteHTML('<th class="color_white entry_heading align_center">#</th>');
								$mpdf->WriteHTML('<th class="color_white entry_heading align_center">'.__('DATE','gym_mgt').'</th>');
								$mpdf->WriteHTML('<th class="color_white entry_heading align_left">'.__('PRODUCT NAME','gym_mgt').'</th>');
								$mpdf->WriteHTML('<th class="color_white entry_heading align_left">'.__('QUANTITY','gym_mgt').'</th>');
								$mpdf->WriteHTML('<th class="color_white entry_heading align_left">'.__('PRICE','gym_mgt').'('.MJ_gmgt_get_currency_symbol(get_option( 'gmgt_currency_code' )).')</th>');
								$mpdf->WriteHTML('<th class="color_white entry_heading align_right">'.__('TOTAL','gym_mgt').'('.MJ_gmgt_get_currency_symbol(get_option( 'gmgt_currency_code' )).')</th>');
								
							$mpdf->WriteHTML('</tr>');
						
						} 
						else
						{ 						
							$mpdf->WriteHTML('<tr>');
								$mpdf->WriteHTML('<th class="color_white entry_heading align_center">#</th>');
								$mpdf->WriteHTML('<th class="color_white entry_heading align_center">'.__('DATE','gym_mgt').'</th>');
								$mpdf->WriteHTML('<th class="color_white entry_heading align_left">'.__('ENTRY','gym_mgt').'</th>');
								$mpdf->WriteHTML('<th class="color_white entry_heading align_right">'.__('Amount','gym_mgt').'('.MJ_gmgt_get_currency_symbol(get_option( 'gmgt_currency_code' )).')</th>');								
							$mpdf->WriteHTML('</tr>');
						}	
						
					$mpdf->WriteHTML('</thead>');
					$mpdf->WriteHTML('<tbody>');
						
							$id=1;
							$i=1;
							$total_amount=0;
						if(!empty($income_data) || !empty($expense_data))
						{
							if(!empty($expense_data))
								$income_data=$expense_data;
						
							$member_income=$obj_payment->MJ_gmgt_get_oneparty_income_data_incomeid($income_data->invoice_id);
							
							foreach($member_income as $result_income)
							{
								$income_entries=json_decode($result_income->entry);
								$discount_amount=$result_income->discount;
								$paid_amount=$result_income->paid_amount;
								$total_discount_amount= $result_income->amount - $discount_amount;								
				               
								if($result_income->tax_id!='')
								{									
									$total_tax=0;
									$tax_array=explode(',',$result_income->tax_id);
									foreach($tax_array as $tax_id)
									{
										$tax_percentage=MJ_gmgt_tax_percentage_by_tax_id($tax_id);
															
										$tax_amount=$total_discount_amount * $tax_percentage / 100;
										
										$total_tax=$total_tax + $tax_amount;				
									}
								}
								else
								{
									$total_tax=$total_discount_amount * $result_income->tax/100;
								}
								$due_amount=0;
								$due_amount=$result_income->total_amount - $result_income->paid_amount;
								$grand_total=$total_discount_amount + $total_tax;
								
							   foreach($income_entries as $each_entry)
							   {
									$total_amount+=$each_entry->amount;
									
									$mpdf->WriteHTML('<tr class="entry_list">');
										$mpdf->WriteHTML('<td class="align_center">'.$id.'</td>');
										$mpdf->WriteHTML('<td class="align_center">'.MJ_gmgt_getdate_in_input_box($result_income->invoice_date).'</td>');
										$mpdf->WriteHTML('<td >'.$each_entry->entry.'</td>');
										$mpdf->WriteHTML('<td class="align_right">'.number_format($each_entry->amount,2).'</td>');
									$mpdf->WriteHTML('</tr>');
									 $id+=1;
									$i+=1;
								}
								if($grand_total=='0')									
								{
									if($income_data->payment_status=='Paid')
									{
										
										$grand_total=$total_amount;
										$paid_amount=$total_amount;
										$due_amount=0;										
									}
									else
									{
										
										$grand_total=$total_amount;
										$paid_amount=0;
										$due_amount=$total_amount;															
									}
								}
							}
						}
						
						if(!empty($membership_data))
						{
							//$total_amount=$membership_data->total_amount;
							$membership_signup_amounts=$membership_data->membership_signup_amount;
							
							$mpdf->WriteHTML('<tr class="entry_list">');
								$mpdf->WriteHTML('<td class="align_center">'.$i.'</td>'); 
								$mpdf->WriteHTML('<td class="align_center">'.MJ_gmgt_getdate_in_input_box($membership_data->created_date).'</td>'); 
								$mpdf->WriteHTML('<td>'.MJ_gmgt_get_membership_name($membership_data->membership_id).'</td>');								
								$mpdf->WriteHTML('<td class="align_right">'.number_format($membership_data->membership_fees_amount,2).'</td>');
							$mpdf->WriteHTML('</tr>');
							
							if( $membership_signup_amounts  > 0) 
							{
                                $mpdf->WriteHTML('<tr class="entry_list">');
								$mpdf->WriteHTML('<td class="align_center">2</td>'); 
								$mpdf->WriteHTML('<td class="align_center">'.MJ_gmgt_getdate_in_input_box($membership_data->created_date).'</td>'); 
								$mpdf->WriteHTML('<td>Membership Signup Fee</td>');								
								$mpdf->WriteHTML('<td class="align_right">'.number_format($membership_data->membership_signup_amount,2).'</td>');
							$mpdf->WriteHTML('</tr>');
							
							}
						}
						if(!empty($selling_data))
						{
								
							$all_entry=json_decode($selling_data->entry);
							
							if(!empty($all_entry))
							{
								foreach($all_entry as $entry)
								{
									$obj_product=new MJ_Gmgtproduct;
									$product = $obj_product->MJ_gmgt_get_single_product($entry->entry);
									
									$product_name=$product->product_name;					
									$quentity=$entry->quentity;	
									$price=$product->price;	
									
									
									$mpdf->WriteHTML('<tr class="entry_list">');										
										$mpdf->WriteHTML('<td class="align_center">'.$i.'</td> ');
										$mpdf->WriteHTML('<td class="align_center">'.MJ_gmgt_getdate_in_input_box($selling_data->sell_date).'</td>');
										$mpdf->WriteHTML('<td>'.$product_name.'</td>');
										$mpdf->WriteHTML('<td>'.$quentity.'</td>');
										$mpdf->WriteHTML('<td><span>'.$price.'</td>');
										$mpdf->WriteHTML('<td class="align_right">'.number_format($quentity * $price,2).'</td>');
										
									$mpdf->WriteHTML('</tr>');		
								$id+=1;
								$i+=1;									
								}
							}
							else
							{
								$obj_product=new MJ_Gmgtproduct;
								$product = $obj_product->MJ_gmgt_get_single_product($selling_data->product_id); 
								
								$product_name=$product->product_name;					
								$quentity=$selling_data->quentity;	
								$price=$product->price;	
								
								$mpdf->WriteHTML('<tr class="entry_list">');										
									$mpdf->WriteHTML('<td class="align_center">'.$i.'</td> ');
									$mpdf->WriteHTML('<td class="align_center">'.MJ_gmgt_getdate_in_input_box($selling_data->sell_date).'</td>');
									$mpdf->WriteHTML('<td>'.$product_name.'</td>');
									$mpdf->WriteHTML('<td>'.$quentity.'</td>');
									$mpdf->WriteHTML('<td>'.$price.'</td>');
									$mpdf->WriteHTML('<td class="align_right">'.number_format($quentity * $price,2).'</td>');
									
								$mpdf->WriteHTML('</tr>');	
								
								$id+=1;
								$i+=1;
							}	
						}
										
					$mpdf->WriteHTML('</tbody>');
				$mpdf->WriteHTML('</table>');
				
				$mpdf->WriteHTML('<table>');
				 $mpdf->WriteHTML('<tr>');
				 $mpdf->WriteHTML('<td>');
					  $mpdf->WriteHTML('<table class="width_46_print" border="0">');
						$mpdf->WriteHTML('<tbody>');						
							$mpdf->WriteHTML('<tr>');
								$mpdf->WriteHTML('<td colspan="2" style="padding-left:15px;">');
									$mpdf->WriteHTML('<h3 class="payment_method_lable">'.__('Payment Method','gym_mgt').'');
								$mpdf->WriteHTML('</h3>');
								$mpdf->WriteHTML('</td>');								
							$mpdf->WriteHTML('</tr>');							
							$mpdf->WriteHTML('<tr>');
								$mpdf->WriteHTML('<td  class="width_311 font_12" style="padding-left:15px;">'.__('Bank Name ','gym_mgt').' </td>');
								$mpdf->WriteHTML('<td class="font_12">: '.get_option( 'gmgt_bank_name' ).'</td>');
							$mpdf->WriteHTML('</tr>');
							$mpdf->WriteHTML('<tr>');
								$mpdf->WriteHTML('<td class="width_311 font_12" style="padding-left:15px;">'.__('Account No ','gym_mgt').'</td>');
								$mpdf->WriteHTML('<td class="font_12">: '.get_option( 'gmgt_bank_acount_number' ).'</td>');
							$mpdf->WriteHTML('</tr>');						
						$mpdf->WriteHTML('<tr>');
								$mpdf->WriteHTML('<td class="width_311 font_12" style="padding-left:15px;">'.__('IFSC Code ','gym_mgt').' </td>');
								$mpdf->WriteHTML('<td class="font_12">: '.get_option( 'gmgt_bank_ifsc_code' ).'</td>');
							$mpdf->WriteHTML('</tr>');						
							$mpdf->WriteHTML('<tr>');
								$mpdf->WriteHTML('<td class="width_311 font_12" style="padding-left:15px;">'.__('Paypal Id ','gym_mgt').' </td>');
								$mpdf->WriteHTML('<td class="font_12">: '.get_option( 'gmgt_paypal_email' ).'</td>');
							$mpdf->WriteHTML('</tr>');
						$mpdf->WriteHTML('</tbody>');
					$mpdf->WriteHTML('</table>'); 
					$mpdf->WriteHTML('</td>');
					$mpdf->WriteHTML('<td>');
					$mpdf->WriteHTML('<table class="width_54_print"  border="0">');
					$mpdf->WriteHTML('<tbody>');
						
						if(!empty($membership_data))
						{							
							$total_amount=$membership_data->membership_fees_amount+$membership_data->membership_signup_amount;
							$total_tax=$membership_data->tax_amount;	
							$paid_amount=$membership_data->paid_amount;
							$due_amount=abs($membership_data->membership_amount - $paid_amount);
							$grand_total=$membership_data->membership_amount;
							
						}
						if(!empty($expense_data))
						{
							$grand_total=$total_amount;
						} 
						if(!empty($selling_data))
						{
							$all_entry=json_decode($selling_data->entry);
							
							if(!empty($all_entry))
							{
								$total_amount=$selling_data->amount;
								$discount_amount=$selling_data->discount;
								$total_discount_amount=$total_amount-$discount_amount;
								
								if($selling_data->tax_id!='')
								{									
									$total_tax=0;
									$tax_array=explode(',',$selling_data->tax_id);
									foreach($tax_array as $tax_id)
									{
										$tax_percentage=MJ_gmgt_tax_percentage_by_tax_id($tax_id);
															
										$tax_amount=$total_discount_amount * $tax_percentage / 100;
										
										$total_tax=$total_tax + $tax_amount;				
									}
								}
								else
								{
									$tax_per=$selling_data->tax;
									$total_tax=$total_discount_amount * $tax_per/100;
								}
								
								$paid_amount=$selling_data->paid_amount;
								$due_amount=abs($selling_data->total_amount - $paid_amount);
								$grand_total=$selling_data->total_amount;
							}
							else
							{
								$obj_product=new MJ_Gmgtproduct;
								$product = $obj_product->MJ_gmgt_get_single_product($selling_data->product_id); 							
								
								$price=$product->price;	
								
								$total_amount=$price*$selling_data->quentity;
								$discount_amount=$selling_data->discount;
								$total_discount_amount=$total_amount-$discount_amount;
								if($selling_data->tax_id!='')
								{									
									$total_tax=0;
									$tax_array=explode(',',$selling_data->tax_id);
									foreach($tax_array as $tax_id)
									{
										$tax_percentage=MJ_gmgt_tax_percentage_by_tax_id($tax_id);
															
										$tax_amount=$total_discount_amount * $tax_percentage / 100;
										
										$total_tax=$total_tax + $tax_amount;				
									}
								}
								else
								{
									$tax_per=$selling_data->tax;
									$total_tax=$total_discount_amount * $tax_per/100;
								}
								
								$paid_amount=$total_amount;
								$due_amount='0';
								$grand_total=$total_amount;
							}
							
						}		
						$mpdf->WriteHTML('<tr>');
								$mpdf->WriteHTML('<h4><td  class="width_70 align_right"><h4 class="margin">'.__('Subtotal :','gym_mgt').'</h4></td>');
								$mpdf->WriteHTML('<td class="align_right"> <h4 class="margin"><span style="">'.MJ_gmgt_get_currency_symbol(get_option( 'gmgt_currency_code' )).'</span>'.number_format($total_amount,2).'</h4></td>');
							$mpdf->WriteHTML('</tr>');
						if($type!='membership_invoice')
						{
							$mpdf->WriteHTML('<tr>');
								$mpdf->WriteHTML('<td class="width_70 align_right"><h4 class="margin">'.__('Discount Amount :','gym_mgt').' </h4></td>');
								$mpdf->WriteHTML('<td class="align_right"><h4 class="margin"> <span >+ '.MJ_gmgt_get_currency_symbol(get_option( 'gmgt_currency_code' )).'</span>'.number_format($discount_amount,2).'</h4></td>');
							$mpdf->WriteHTML('</tr>'); 
						}	
							$mpdf->WriteHTML('<tr>');
								$mpdf->WriteHTML('<td class="width_70 align_right"><h4 class="margin">'.__('Tax Amount :','gym_mgt').' </h4></td>');
								$mpdf->WriteHTML('<td class="align_right"><h4 class="margin"> <span >+ '.MJ_gmgt_get_currency_symbol(get_option( 'gmgt_currency_code' )).'</span>'.number_format($total_tax,2).'</h4></td>');
							$mpdf->WriteHTML('</tr>');
						
							$mpdf->WriteHTML('<tr>');
								$mpdf->WriteHTML('<td class="width_70 align_right"><h4 class="margin">'.__('Due Amount :','gym_mgt').' </h4></td>');
								$mpdf->WriteHTML('<td class="align_right"> <h4 class="margin"><span >'.MJ_gmgt_get_currency_symbol(get_option( 'gmgt_currency_code' )).'</span>'.number_format($due_amount,2).'</h4></td>');
							$mpdf->WriteHTML('</tr>');
							$mpdf->WriteHTML('<tr>');
								$mpdf->WriteHTML('<td class="width_70 align_right"><h4 class="margin">'.__('Paid Amount :','gym_mgt').' </h4></td>');
								$mpdf->WriteHTML('<td class="align_right"> <h4 class="margin"><span >'.MJ_gmgt_get_currency_symbol(get_option( 'gmgt_currency_code' )).'</span>'.number_format($paid_amount,2).'</h4></td>');
							$mpdf->WriteHTML('</tr>');
							$mpdf->WriteHTML('<tr>');							
								$mpdf->WriteHTML('<td  class="width_70 align_right grand_total_lable"><h3 class="color_white margin">'.__('Grand Total :','gym_mgt').' </h3></td>');
								$mpdf->WriteHTML('<td class="align_right grand_total_amount"><h3 class="color_white margin">  <span>'.MJ_gmgt_get_currency_symbol(get_option( 'gmgt_currency_code' )).'</span>'.number_format($grand_total,2).'</h3></td>');
							$mpdf->WriteHTML('</tr>');
						$mpdf->WriteHTML('</tbody>');
					$mpdf->WriteHTML('</table>');			
					$mpdf->WriteHTML('</td>');					
				  $mpdf->WriteHTML('</tr>');
				$mpdf->WriteHTML('</table>');
				
				if(!empty($history_detail_result))
				{
					$mpdf->WriteHTML('<hr>');					
					$mpdf->WriteHTML('<table class="width_100_print">');	
						$mpdf->WriteHTML('<tbody>');	
							$mpdf->WriteHTML('<tr>');
								$mpdf->WriteHTML('<td style="padding-left:20px;">');
									$mpdf->WriteHTML('<h3 class="entry_lable">'.__('Payment History','gym_mgt').'</h3>');
								$mpdf->WriteHTML('</td>');	
							$mpdf->WriteHTML('</tr>');	
						$mpdf->WriteHTML('</tbody>');
					$mpdf->WriteHTML('</table>');					
					$mpdf->WriteHTML('<table class="table table-bordered" class="width_93" border="1">');
					$mpdf->WriteHTML('<thead>');
						$mpdf->WriteHTML('<tr>');
							$mpdf->WriteHTML('<th class="color_white entry_heading align_center">'.__('DATE','gym_mgt').'</th>');
							$mpdf->WriteHTML('<th class="color_white entry_heading align_center">'.__('Amount','gym_mgt').'</th>');
							$mpdf->WriteHTML('<th class="color_white entry_heading align_center">'.__('Method','gym_mgt').'</th>');
							$mpdf->WriteHTML('<th class="color_white entry_heading align_center">'.__('Payment Details','gym_mgt').'</th>');
						$mpdf->WriteHTML('</tr>');
					$mpdf->WriteHTML('</thead>');
					$mpdf->WriteHTML('<tbody>');
						
						foreach($history_detail_result as  $retrive_data)
						{						
							$mpdf->WriteHTML('<tr>');
							$mpdf->WriteHTML('<td class="align_center">'.$retrive_data->paid_by_date.'</td>');
							$mpdf->WriteHTML('<td class="align_center">'.$retrive_data->amount.'</td>');
							$mpdf->WriteHTML('<td class="align_center">'.$retrive_data->payment_method.'</td>');
							$mpdf->WriteHTML('<td class="align_center">'.$retrive_data->payment_description.'</td>');
							$mpdf->WriteHTML('</tr>');
						}
					$mpdf->WriteHTML('</tbody>');
					$mpdf->WriteHTML('</table>');
				}				
				$mpdf->WriteHTML('</div>');
			$mpdf->WriteHTML('</div>'); 
			$mpdf->WriteHTML('</body>'); 
			$mpdf->WriteHTML('</html>'); 
	
	$mpdf->Output();	
	ob_end_flush();
	unset($mpdf);	

}
//send mail for generated invoice FUNCTION  
function MJ_gmgt_send_invoice_generate_mail($emails,$subject,$message,$invoice_id,$type)
{		
	error_reporting(0);
	
	$obj_payment= new MJ_Gmgtpayment();
	if($type=='membership_invoice')
	{		
		$obj_membership_payment=new MJ_Gmgt_membership_payment;	
		$membership_data=$obj_membership_payment->MJ_gmgt_get_single_membership_payment($invoice_id);
		$history_detail_result = MJ_gmgt_get_payment_history_by_mpid($invoice_id);		
	}
	if($type=='income')
	{
		$income_data=$obj_payment->MJ_gmgt_get_income_data($invoice_id);
		$history_detail_result = MJ_gmgt_get_income_payment_history_by_mpid($invoice_id);
	}	
	if($type=='sell_invoice')
	{
		$obj_store=new MJ_Gmgtstore;
		$selling_data=$obj_store->MJ_gmgt_get_single_selling($invoice_id);
		$history_detail_result = MJ_gmgt_get_sell_payment_history_by_mpid($invoice_id);
	}

	echo '<link rel="stylesheet" href="'.plugins_url( '/assets/css/bootstrap.min.css', __FILE__).'"></link>';
  
	echo '<script  rel="javascript" src="'.plugins_url( '/assets/js/bootstrap.min.js', __FILE__).'"></script>';

	ob_clean();
	header('Content-type: application/pdf');
	header('Content-Disposition: inline; filename="invoice.pdf"');
	header('Content-Transfer-Encoding: binary');
	header('Accept-Ranges: bytes');	
	require GMS_PLUGIN_DIR. '/lib/mpdf/mpdf.php';	
	$stylesheet = file_get_contents(GMS_PLUGIN_DIR. '/assets/css/custom.css'); // Get css content
	$stylesheet1 = file_get_contents(GMS_PLUGIN_DIR. '/assets/css/style.css'); // Get css content
	 $mpdf	=	new mPDF('c','A4','','' , 5 , 5 , 5 , 0 , 0 , 0); 

	$mpdf->debug = true;
	$mpdf->WriteHTML('<html>');
	$mpdf->WriteHTML('<head>');
	$mpdf->WriteHTML('<style></style>');
	$mpdf->WriteHTML($stylesheet,1); // Writing style to pdf
	$mpdf->WriteHTML($stylesheet1,1); // Writing style to pdf
	$mpdf->WriteHTML('</head>');
	$mpdf->WriteHTML('<body>');		
	$mpdf->SetTitle('Income Invoice');	
		$mpdf->WriteHTML('<div class="modal-header">');
			$mpdf->WriteHTML('<h4 class="modal-title">'.get_option('gmgt_system_name').'</h4>');
		$mpdf->WriteHTML('</div>');
		$mpdf->WriteHTML('<div id="invoice_print">');		
			$mpdf->WriteHTML('<img class="invoicefont1" src="'.plugins_url('/gym-management/assets/images/invoice.jpg').'" width="100%">');
			$mpdf->WriteHTML('<div class="main_div">');	
			
					$mpdf->WriteHTML('<table class="width_100_print" border="0">');					
					$mpdf->WriteHTML('<tbody>');
						$mpdf->WriteHTML('<tr>');
							$mpdf->WriteHTML('<td class="width_1_print">');
								$mpdf->WriteHTML('<img class="system_logo" style="padding-left:15px;"  src="'.get_option( 'gmgt_system_logo' ).'">');
							$mpdf->WriteHTML('</td>');							
							$mpdf->WriteHTML('<td class="only_width_20_print">');								
								$mpdf->WriteHTML('A. '.chunk_split(get_option( 'gmgt_gym_address' ),30,"<BR>").'<br>'); 
								 $mpdf->WriteHTML('E. '.get_option( 'gmgt_email' ).'<br>'); 
								 $mpdf->WriteHTML('P. '.get_option( 'gmgt_contact_number' ).'<br>'); 
							$mpdf->WriteHTML('</td>');
							$mpdf->WriteHTML('<td align="right" class="width_24">');
							$mpdf->WriteHTML('</td>');
						$mpdf->WriteHTML('</tr>');
					$mpdf->WriteHTML('</tbody>');
				$mpdf->WriteHTML('</table>');
				
				$mpdf->WriteHTML('<table>');
			 $mpdf->WriteHTML('<tr>');
				$mpdf->WriteHTML('<td>');
				
					$mpdf->WriteHTML('<table class="width_50_print"  border="0">');
						$mpdf->WriteHTML('<tbody>');				
						$mpdf->WriteHTML('<tr>');
							$mpdf->WriteHTML('<td colspan="2" class="billed_to_print" align="center">');								
								$mpdf->WriteHTML('<h3 class="billed_to_lable"> |'.__('Bill To','gym_mgt').'. </h3>');
							$mpdf->WriteHTML('</td>');
							$mpdf->WriteHTML('<td class="width_40_print">');								
							
								if(!empty($expense_data))
								{
								  $mpdf->WriteHTML('<h3 class="display_name">'.chunk_split(ucwords($expense_data->supplier_name),30,"<BR>").'</h3>'); 
								}
								else
								{
									if(!empty($income_data))
										$member_id=$income_data->supplier_name;
									 if(!empty($membership_data))
										$member_id=$membership_data->member_id;
									 if(!empty($selling_data))
										$member_id=$selling_data->member_id;
									$patient=get_userdata($member_id);
									
									$mpdf->WriteHTML('<h3 class="display_name">'.chunk_split(ucwords($patient->display_name),30,"<BR>").'</h3>'); 
									$address=get_user_meta( $member_id,'address',true);									
									$mpdf->WriteHTML(''.chunk_split($address,30,"<BR>").''); 
									  $mpdf->WriteHTML(''.get_user_meta( $member_id,'city_name',true ).','); 
									 $mpdf->WriteHTML(''.get_user_meta( $member_id,'zip_code',true ).'<br>'); 
									 $mpdf->WriteHTML(''.get_user_meta( $member_id,'mobile',true ).'<br>'); 
								}
									
							 $mpdf->WriteHTML('</td>');
						 $mpdf->WriteHTML('</tr>');									
					 $mpdf->WriteHTML('</tbody>');
				 $mpdf->WriteHTML('</table>');
				
				$mpdf->WriteHTML('</td>');
				$mpdf->WriteHTML('<td>');
				
				   $mpdf->WriteHTML('<table class="width_50_print"  border="0">');
					 $mpdf->WriteHTML('<tbody>');				
						 $mpdf->WriteHTML('<tr>');	
							 $mpdf->WriteHTML('<td class="width_30_print">');
							 $mpdf->WriteHTML('</td>');
							 $mpdf->WriteHTML('<td class="width_20_print invoice_lable" style="padding-right:30px;" align="left">');
								
								$issue_date='DD-MM-YYYY';
								if(!empty($income_data))
								{
									$issue_date=$income_data->invoice_date;
									$payment_status=$income_data->payment_status;
									$invoice_no=$income_data->invoice_no;
								}
								if(!empty($membership_data))
								{
									$issue_date=$membership_data->created_date;
									if($membership_data->payment_status!='0')
									{	
										$payment_status=$membership_data->payment_status;
									}
									else
									{
										$payment_status='Unpaid';
									}		
									$invoice_no=$membership_data->invoice_no;									
								}
								if(!empty($expense_data))
								{
									$issue_date=$expense_data->invoice_date;
									$payment_status=$expense_data->payment_status;
									$invoice_no=$expense_data->invoice_no;
								}
								if(!empty($selling_data))
								{
									$issue_date=$selling_data->sell_date;									
									$payment_status=$selling_data->payment_status;
									$invoice_no=$selling_data->invoice_no;
								} 
								
								if($type!='expense')
								{								
									$mpdf->WriteHTML('<h3>'.__('INVOICE','gym_mgt').' <br> #'.$invoice_no.'</h3>');										
								}																			
							 $mpdf->WriteHTML('</td>');							
						 $mpdf->WriteHTML('</tr>');
						 $mpdf->WriteHTML('<tr>');	
							 $mpdf->WriteHTML('<td class="width_30_print">');
							 $mpdf->WriteHTML('</td>');
							 $mpdf->WriteHTML('<td class="width_20_print" style="padding-right:30px;" align="left">');
								$mpdf->WriteHTML('<h5>'.__('Date','gym_mgt').' : '.MJ_gmgt_getdate_in_input_box($issue_date).'</h5>');
							$mpdf->WriteHTML('<h5>'.__('Status','gym_mgt').' : '.__(''.$payment_status.'','gym_mgt').'</h5>');											
							 $mpdf->WriteHTML('</td>');							
						 $mpdf->WriteHTML('</tr>');						
					 $mpdf->WriteHTML('</tbody>');
				 $mpdf->WriteHTML('</table>');	
				$mpdf->WriteHTML('</td>');
			  $mpdf->WriteHTML('</tr>');
			$mpdf->WriteHTML('</table>');
			
				if($type=='membership_invoice')
				{	
					$mpdf->WriteHTML('<table class="width_100_print">');	
						$mpdf->WriteHTML('<tbody>');	
							$mpdf->WriteHTML('<tr>');
								$mpdf->WriteHTML('<td style="padding-left:20px;">');
									$mpdf->WriteHTML('<h3 class="entry_lable">'.__('Membership Entries','gym_mgt').'</h3>');
								$mpdf->WriteHTML('</td>');	
							$mpdf->WriteHTML('</tr>');	
						$mpdf->WriteHTML('</tbody>');
					$mpdf->WriteHTML('</table>');				
					
				}		
				elseif($type=='income')
				{ 
					$mpdf->WriteHTML('<table class="width_100_print">');	
						$mpdf->WriteHTML('<tbody>');	
							$mpdf->WriteHTML('<tr>');
								$mpdf->WriteHTML('<td style="padding-left:20px;">');
									$mpdf->WriteHTML('<h3 class="entry_lable">'.__('Income Entries','gym_mgt').'</h3>');
								$mpdf->WriteHTML('</td>');	
							$mpdf->WriteHTML('</tr>');	
						$mpdf->WriteHTML('</tbody>');
					$mpdf->WriteHTML('</table>');
				
				}
				elseif($type=='sell_invoice')
				{ 
				  $mpdf->WriteHTML('<table class="width_100_print">');	
						$mpdf->WriteHTML('<tbody>');	
							$mpdf->WriteHTML('<tr>');
								$mpdf->WriteHTML('<td style="padding-left:20px;">');
									$mpdf->WriteHTML('<h3 class="entry_lable">'.__('Sells Product','gym_mgt').'</h3>');
								$mpdf->WriteHTML('</td>');	
							$mpdf->WriteHTML('</tr>');	
						$mpdf->WriteHTML('</tbody>');
					$mpdf->WriteHTML('</table>');
				  
				}
				else
				{ 
					$mpdf->WriteHTML('<table class="width_100_print">');	
						$mpdf->WriteHTML('<tbody>');	
							$mpdf->WriteHTML('<tr>');
								$mpdf->WriteHTML('<td style="padding-left:20px;">');
									$mpdf->WriteHTML('<h3 class="entry_lable">'.__('Expense Entries','gym_mgt').'</h3>');
								$mpdf->WriteHTML('</td>');	
							$mpdf->WriteHTML('</tr>');	
						$mpdf->WriteHTML('</tbody>');
					$mpdf->WriteHTML('</table>');	
				}		  
					
				$mpdf->WriteHTML('<table class="table table-bordered" class="width_93" border="1">');
					$mpdf->WriteHTML('<thead>');
						
						if($type=='membership_invoice')
						{						
							$mpdf->WriteHTML('<tr>');
								$mpdf->WriteHTML('<th class="color_white entry_heading align_center">#</th>');
								$mpdf->WriteHTML('<th class="color_white entry_heading align_center">'.__('DATE','gym_mgt').'</th>');
								$mpdf->WriteHTML('<th class="color_white entry_heading align_left">'.__('Fees Type','gym_mgt').'</th>');
								$mpdf->WriteHTML('<th class="color_white entry_heading align_right">'.__('Amount','gym_mgt').'('.MJ_gmgt_get_currency_symbol(get_option( 'gmgt_currency_code' )).')</th>');								
							$mpdf->WriteHTML('</tr>');
						}
						elseif($type=='sell_invoice')
						{  
						
							$mpdf->WriteHTML('<tr>');
								$mpdf->WriteHTML('<th class="color_white entry_heading align_center">#</th>');
								$mpdf->WriteHTML('<th class="color_white entry_heading align_center">'.__('DATE','gym_mgt').'</th>');
								$mpdf->WriteHTML('<th class="color_white entry_heading align_left">'.__('PRODUCT NAME','gym_mgt').'</th>');
								$mpdf->WriteHTML('<th class="color_white entry_heading align_left">'.__('QUANTITY','gym_mgt').'</th>');
								$mpdf->WriteHTML('<th class="color_white entry_heading align_left">'.__('PRICE','gym_mgt').'('.MJ_gmgt_get_currency_symbol(get_option( 'gmgt_currency_code' )).')</th>');
								$mpdf->WriteHTML('<th class="color_white entry_heading align_right">'.__('TOTAL','gym_mgt').'('.MJ_gmgt_get_currency_symbol(get_option( 'gmgt_currency_code' )).')</th>');
								
							$mpdf->WriteHTML('</tr>');
						
						} 
						else
						{ 						
							$mpdf->WriteHTML('<tr>');
								$mpdf->WriteHTML('<th class="color_white entry_heading align_center">#</th>');
								$mpdf->WriteHTML('<th class="color_white entry_heading align_center">'.__('DATE','gym_mgt').'</th>');
								$mpdf->WriteHTML('<th class="color_white entry_heading align_left">'.__('ENTRY','gym_mgt').'</th>');
								$mpdf->WriteHTML('<th class="color_white entry_heading align_right">'.__('Amount','gym_mgt').'('.MJ_gmgt_get_currency_symbol(get_option( 'gmgt_currency_code' )).')</th>');								
							$mpdf->WriteHTML('</tr>');
						}	
						
					$mpdf->WriteHTML('</thead>');
					$mpdf->WriteHTML('<tbody>');
						
							$id=1;
							$i=1;
							$total_amount=0;
						if(!empty($income_data) || !empty($expense_data))
						{
							if(!empty($expense_data))
								$income_data=$expense_data;
						
							$member_income=$obj_payment->MJ_gmgt_get_oneparty_income_data_incomeid($income_data->invoice_id);
							
							foreach($member_income as $result_income)
							{
								$income_entries=json_decode($result_income->entry);
								$discount_amount=$result_income->discount;
								$paid_amount=$result_income->paid_amount;
								$total_discount_amount= $result_income->amount - $discount_amount;								
				               
								if($result_income->tax_id!='')
								{									
									$total_tax=0;
									$tax_array=explode(',',$result_income->tax_id);
									foreach($tax_array as $tax_id)
									{
										$tax_percentage=MJ_gmgt_tax_percentage_by_tax_id($tax_id);
															
										$tax_amount=$total_discount_amount * $tax_percentage / 100;
										
										$total_tax=$total_tax + $tax_amount;				
									}
								}
								else
								{
									$total_tax=$total_discount_amount * $result_income->tax/100;
								}
								
								$due_amount=0;
								$due_amount=$result_income->total_amount - $result_income->paid_amount;
								$grand_total=$total_discount_amount + $total_tax;
								
							   foreach($income_entries as $each_entry)
							   {
									$total_amount+=$each_entry->amount;
									
									$mpdf->WriteHTML('<tr class="entry_list">');
										$mpdf->WriteHTML('<td class="align_center">'.$id.'</td>');
										$mpdf->WriteHTML('<td class="align_center">'.MJ_gmgt_getdate_in_input_box($result_income->invoice_date).'</td>');
										$mpdf->WriteHTML('<td >'.$each_entry->entry.'</td>');
										$mpdf->WriteHTML('<td class="align_right">'.number_format($each_entry->amount,2).'</td>');
									$mpdf->WriteHTML('</tr>');
									 $id+=1;
									$i+=1;
								}
							}
						}
						
						if(!empty($membership_data))
						{
							
							//$total_amount=$membership_data->total_amount;
							$membership_signup_amounts=$membership_data->membership_signup_amount;
							
							$mpdf->WriteHTML('<tr class="entry_list">');
								$mpdf->WriteHTML('<td class="align_center">'.$i.'</td>'); 
								$mpdf->WriteHTML('<td class="align_center">'.MJ_gmgt_getdate_in_input_box($membership_data->created_date).'</td>'); 
								$mpdf->WriteHTML('<td>'.MJ_gmgt_get_membership_name($membership_data->membership_id).'</td>');								
								$mpdf->WriteHTML('<td class="align_right">'.number_format($membership_data->membership_fees_amount,2).'</td>');
							$mpdf->WriteHTML('</tr>');
							// sign up entry //
							if( $membership_signup_amounts  > 0) 
							{
								$mpdf->WriteHTML('<tr class="entry_list">');
								$mpdf->WriteHTML('<td class="align_center">2</td>'); 
								$mpdf->WriteHTML('<td class="align_center">'.MJ_gmgt_getdate_in_input_box($membership_data->created_date).'</td>'); 
								$mpdf->WriteHTML('<td>'.MJ_gmgt_get_membership_name($membership_data->membership_id).'</td>');								
								$mpdf->WriteHTML('<td class="align_right">'.number_format($membership_data->membership_signup_amount,2).'</td>');
							$mpdf->WriteHTML('</tr>');
							
							}
						}
						if(!empty($selling_data))
						{
								
							$all_entry=json_decode($selling_data->entry);
							
							if(!empty($all_entry))
							{
								foreach($all_entry as $entry)
								{
									$obj_product=new MJ_Gmgtproduct;
									$product = $obj_product->MJ_gmgt_get_single_product($entry->entry);
									
									$product_name=$product->product_name;					
									$quentity=$entry->quentity;	
									$price=$product->price;	
									
									
									$mpdf->WriteHTML('<tr class="entry_list">');										
										$mpdf->WriteHTML('<td class="align_center">'.$i.'</td> ');
										$mpdf->WriteHTML('<td class="align_center">'.MJ_gmgt_getdate_in_input_box($selling_data->sell_date).'</td>');
										$mpdf->WriteHTML('<td>'.$product_name.'</td>');
										$mpdf->WriteHTML('<td>'.$quentity.'</td>');
										$mpdf->WriteHTML('<td>'.$price.'</td>');
										$mpdf->WriteHTML('<td class="align_right">'.number_format($quentity * $price,2).'</td>');
										
									$mpdf->WriteHTML('</tr>');								
								}
							}	
						}
										
					$mpdf->WriteHTML('</tbody>');
				$mpdf->WriteHTML('</table>');
				
				$mpdf->WriteHTML('<table>');
				 $mpdf->WriteHTML('<tr>');
				 $mpdf->WriteHTML('<td>');
					  $mpdf->WriteHTML('<table border="0">');
						$mpdf->WriteHTML('<tbody>');						
							$mpdf->WriteHTML('<tr>');
								$mpdf->WriteHTML('<td colspan="2" style="padding-left:15px;">');
									$mpdf->WriteHTML('<h3 class="payment_method_lable">'.__('Payment Method','gym_mgt').'');
								$mpdf->WriteHTML('</h3>');
								$mpdf->WriteHTML('</td>');								
							$mpdf->WriteHTML('</tr>');							
							$mpdf->WriteHTML('<tr>');
								$mpdf->WriteHTML('<td  class="width_311 font_12" style="padding-left:15px;">'.__('Bank Name ','gym_mgt').' </td>');
								$mpdf->WriteHTML('<td class="font_12">: '.get_option( 'gmgt_bank_name' ).'</td>');
							$mpdf->WriteHTML('</tr>');
							$mpdf->WriteHTML('<tr>');
								$mpdf->WriteHTML('<td class="width_311 font_12" style="padding-left:15px;">'.__('Account No ','gym_mgt').'</td>');
								$mpdf->WriteHTML('<td class="font_12">: '.get_option( 'gmgt_bank_acount_number' ).'</td>');
							$mpdf->WriteHTML('</tr>');						
						$mpdf->WriteHTML('<tr>');
								$mpdf->WriteHTML('<td class="width_311 font_12" style="padding-left:15px;">'.__('IFSC Code ','gym_mgt').' </td>');
								$mpdf->WriteHTML('<td class="font_12">: '.get_option( 'gmgt_bank_ifsc_code' ).'</td>');
							$mpdf->WriteHTML('</tr>');						
							$mpdf->WriteHTML('<tr>');
								$mpdf->WriteHTML('<td class="width_311 font_12" style="padding-left:15px;">'.__('Paypal Id ','gym_mgt').' </td>');
								$mpdf->WriteHTML('<td class="font_12">: '.get_option( 'gmgt_paypal_email' ).'</td>');
							$mpdf->WriteHTML('</tr>');
						$mpdf->WriteHTML('</tbody>');
					$mpdf->WriteHTML('</table>'); 
					$mpdf->WriteHTML('</td>');
					$mpdf->WriteHTML('<td>');
					$mpdf->WriteHTML('<table class="width_54_print"  border="0">');
					$mpdf->WriteHTML('<tbody>');
						
						if(!empty($membership_data))
						{							
							$total_amount=$membership_data->membership_fees_amount+$membership_data->membership_signup_amount;
							$total_tax=$membership_data->tax_amount;	
							$paid_amount=$membership_data->paid_amount;
							$due_amount=abs($membership_data->membership_amount - $paid_amount);
							$grand_total=$membership_data->membership_amount;
							
						}
						if(!empty($expense_data))
						{
							$grand_total=$total_amount;
						} 
						if(!empty($selling_data))
						{
							$total_amount=$selling_data->amount;
							$discount_amount=$selling_data->discount;
							$total_discount_amount=$total_amount-$discount_amount;
							
							if($selling_data->tax_id!='')
							{									
								$total_tax=0;
								$tax_array=explode(',',$selling_data->tax_id);
								foreach($tax_array as $tax_id)
								{
									$tax_percentage=MJ_gmgt_tax_percentage_by_tax_id($tax_id);
														
									$tax_amount=$total_discount_amount * $tax_percentage / 100;
									
									$total_tax=$total_tax + $tax_amount;				
								}
							}
							else
							{
								$tax_per=$selling_data->tax;
								$total_tax=$total_discount_amount * $tax_per/100;
							}
							
							$paid_amount=$selling_data->paid_amount;
							$due_amount=abs($selling_data->total_amount - $paid_amount);
							$grand_total=$selling_data->total_amount;
						}		
						$mpdf->WriteHTML('<tr>');
								$mpdf->WriteHTML('<h4><td  class="width_70 align_right"><h4 class="margin">'.__('Subtotal :','gym_mgt').'</h4></td>');
								$mpdf->WriteHTML('<td class="align_right"> <h4 class="margin"><span style="">'.MJ_gmgt_get_currency_symbol(get_option( 'gmgt_currency_code' )).'</span>'.number_format($total_amount,2).'</h4></td>');
							$mpdf->WriteHTML('</tr>');
						if($type!='membership_invoice')
						{
							$mpdf->WriteHTML('<tr>');
								$mpdf->WriteHTML('<td class="width_70 align_right"><h4 class="margin">'.__('Discount Amount :','gym_mgt').' </h4></td>');
								$mpdf->WriteHTML('<td class="align_right"><h4 class="margin"> <span >+ '.MJ_gmgt_get_currency_symbol(get_option( 'gmgt_currency_code' )).'</span>'.number_format($discount_amount,2).'</h4></td>');
							$mpdf->WriteHTML('</tr>'); 
						}
							$mpdf->WriteHTML('<tr>');
								$mpdf->WriteHTML('<td class="width_70 align_right"><h4 class="margin">'.__('Tax Amount :','gym_mgt').' </h4></td>');
								$mpdf->WriteHTML('<td class="align_right"><h4 class="margin"> <span >+ '.MJ_gmgt_get_currency_symbol(get_option( 'gmgt_currency_code' )).'</span>'.number_format($total_tax,2).'</h4></td>');
							$mpdf->WriteHTML('</tr>');
						
							$mpdf->WriteHTML('<tr>');
								$mpdf->WriteHTML('<td class="width_70 align_right"><h4 class="margin">'.__('Due Amount :','gym_mgt').' </h4></td>');
								$mpdf->WriteHTML('<td class="align_right"> <h4 class="margin"><span >'.MJ_gmgt_get_currency_symbol(get_option( 'gmgt_currency_code' )).'</span>'.number_format($due_amount,2).'</h4></td>');
							$mpdf->WriteHTML('</tr>');
							$mpdf->WriteHTML('<tr>');
								$mpdf->WriteHTML('<td class="width_70 align_right"><h4 class="margin">'.__('Paid Amount :','gym_mgt').' </h4></td>');
								$mpdf->WriteHTML('<td class="align_right"> <h4 class="margin"><span >'.MJ_gmgt_get_currency_symbol(get_option( 'gmgt_currency_code' )).'</span>'.number_format($paid_amount,2).'</h4></td>');
							$mpdf->WriteHTML('</tr>');
							$mpdf->WriteHTML('<tr>');							
								$mpdf->WriteHTML('<td  class="width_70 align_right grand_total_lable"><h3 class="color_white margin">'.__('Grand Total :','gym_mgt').' </h3></td>');
								$mpdf->WriteHTML('<td class="align_right grand_total_amount"><h3 class="color_white margin">  <span>'.MJ_gmgt_get_currency_symbol(get_option( 'gmgt_currency_code' )).'</span>'.number_format($grand_total,2).'</h3></td>');
							$mpdf->WriteHTML('</tr>');
						$mpdf->WriteHTML('</tbody>');
					$mpdf->WriteHTML('</table>');			
					$mpdf->WriteHTML('</td>');					
				  $mpdf->WriteHTML('</tr>');
				$mpdf->WriteHTML('</table>');	

				if(!empty($history_detail_result))
				{
					$mpdf->WriteHTML('<hr>');					
					$mpdf->WriteHTML('<table class="width_100_print">');	
						$mpdf->WriteHTML('<tbody>');	
							$mpdf->WriteHTML('<tr>');
								$mpdf->WriteHTML('<td style="padding-left:20px;">');
									$mpdf->WriteHTML('<h3 class="entry_lable">'.__('Payment History','gym_mgt').'</h3>');
								$mpdf->WriteHTML('</td>');	
							$mpdf->WriteHTML('</tr>');	
						$mpdf->WriteHTML('</tbody>');
					$mpdf->WriteHTML('</table>');					
					$mpdf->WriteHTML('<table class="table table-bordered" class="width_93" border="1">');
					$mpdf->WriteHTML('<thead>');
						$mpdf->WriteHTML('<tr>');
							$mpdf->WriteHTML('<th class="color_white entry_heading align_center">'.__('DATE','gym_mgt').'</th>');
							$mpdf->WriteHTML('<th class="color_white entry_heading align_center">'.__('Amount','gym_mgt').'</th>');
							$mpdf->WriteHTML('<th class="color_white entry_heading align_center">'.__('Method','gym_mgt').'</th>');
							$mpdf->WriteHTML('<th class="color_white entry_heading align_center">'.__('Payment Details','gym_mgt').'</th>');
						$mpdf->WriteHTML('</tr>');
					$mpdf->WriteHTML('</thead>');
					$mpdf->WriteHTML('<tbody>');
						
						foreach($history_detail_result as  $retrive_data)
						{						
							$mpdf->WriteHTML('<tr>');
							$mpdf->WriteHTML('<td class="align_center">'.$retrive_data->paid_by_date.'</td>');
							$mpdf->WriteHTML('<td class="align_center">'.$retrive_data->amount.'</td>');
							$mpdf->WriteHTML('<td class="align_center">'.$retrive_data->payment_method.'</td>');
							$mpdf->WriteHTML('<td class="align_center">'.$retrive_data->payment_description.'</td>');
							$mpdf->WriteHTML('</tr>');
						}
					$mpdf->WriteHTML('</tbody>');
					$mpdf->WriteHTML('</table>');
				}
				$mpdf->WriteHTML('</div>');
			$mpdf->WriteHTML('</div>'); 
			
			$mpdf->WriteHTML('</body>'); 
			$mpdf->WriteHTML('</html>'); 
				 	
	
	$mpdf->Output( WP_CONTENT_DIR . '/uploads/'.$invoice_id.'-'.$type.'.pdf','F');
	
	ob_end_flush();
	unset($mpdf);	
	
	$system_name=get_option('gmgt_system_name');
	
	$headers = "From: ".$system_name.' <noreplay@gmail.com>' . "\r\n";	
	
	$mail_attachment = array(WP_CONTENT_DIR . '/uploads/'.$invoice_id.'-'.$type.'.pdf');
	
	wp_mail($emails,$subject,$message,$headers,$mail_attachment); 
	
	unlink(WP_CONTENT_DIR . '/uploads/'.$invoice_id.'-'.$type.'.pdf');

}
//VIEW Nutrition FUNCTION
function MJ_gmgt_nutrition_schedule_view()
{	
	$obj_nutrition=new MJ_Gmgtnutrition;
	$result = $obj_nutrition->MJ_gmgt_get_single_nutrition($_REQUEST['nutrition_id']);
	 ?>
			<div class="form-group"> 	<a href="#" class="close-btn badge badge-success pull-right">X</a>
			  <h4 class="modal-title" id="myLargeModalLabel">
				<?php echo $result->day.' '. __('Nutrition Schedule','gym_mgt'); ?>
			  </h4>
			</div>
			<hr>
			<div class="panel panel-white form-horizontal">
			  <div class="form-group">
				<label class="col-sm-3" for="Breakfast"><strong>
				<?php _e(' Breakfast','gym_mgt');?></strong>
				: </label>
				<div class="col-sm-9"> <?php echo $result->breakfast;?> </div>
			  </div>
			  <div class="form-group">
				<label class="col-sm-3" for="notice_title"><strong>
				<?php _e('Midmorning Snack','gym_mgt');?></strong>
				: </label>
				<div class="col-sm-9"> <?php echo $result->midmorning_snack;?> </div>
			  </div>
			  <div class="form-group">
				<label class="col-sm-3" for="lunch"><strong>
				<?php _e('Lunch','gym_mgt');?></strong>
				: </label>
				<div class="col-sm-9"> <?php echo $result->lunch;?> </div>
			  </div>
			  <div class="form-group">
				<label class="col-sm-3" for="afternoon_snack"><strong>
				<?php _e('Afternoon Snack','gym_mgt');?></strong>
				: </label>
				<div class="col-sm-9"> <?php echo $result->afternoon_snack;?> </div>
			  </div>
			   <div class="form-group">
				<label class="col-sm-3" for="dinner"><strong>
				<?php _e('Dinner','gym_mgt');?></strong>
				: </label>
				<div class="col-sm-9"> <?php echo $result->dinner;?> </div>
			  </div>
			   <div class="form-group">
				<label class="col-sm-3" for="afterdinner_snack"><strong>
				<?php _e('Afterdinner Snack','gym_mgt');?></strong>
				: </label>
				<div class="col-sm-9"> <?php echo $result->afterdinner_snack;?> </div>
			  </div>
			  			   <div class="form-group">
				<label class="col-sm-3" for="afterdinner_snack"><strong>
				<?php _e('Afterdinner Snack','gym_mgt');?></strong>
				: </label>
				<div class="col-sm-9"> <?php echo $result->start_date;?> </div>
			  </div>
			  			   <div class="form-group">
				<label class="col-sm-3" for="afterdinner_snack"><strong>
				<?php _e('Afterdinner Snack','gym_mgt');?></strong>
				: </label>
				<div class="col-sm-9"> <?php echo $result->expire_date;?> </div>
			  </div>
			<?php 
				die();
}
//VIEW DETAILS POPUP FUNCTION
function MJ_gmgt_view_details_popup()
{	
	$recoed_id = $_REQUEST['record_id'];
	$type= $_REQUEST['type'];
	if($type == 'view_group')
	{ 
		$allmembers =MJ_gmgt_get_groupmember($recoed_id);
		?>
		<div class="form-group"> 	
			<a href="#" class="close-btn badge badge-success pull-right">X</a>
			<h4 class="modal-title" id="myLargeModalLabel">
				<?php echo  __('Group Member','gym_mgt'); ?>
			</h4>
		</div>
		<hr>
		<div class="panel-body">
			<div class="slimScrollDiv">
				<div class="inbox-widget slimscroll">
					<?php 
					if(!empty($allmembers))
					foreach ($allmembers as $retrieved_data){
					?>
						<div class="inbox-item">
							<div class="inbox-item-img">
					<?php 
						$uid=$retrieved_data->member_id;
						 
						$userimage=get_user_meta($uid, 'gmgt_user_avatar', true);
						if(empty($userimage))
						{
							echo '<img src='.get_option( 'gmgt_system_logo' ).' height="50px" width="50px" class="img-circle" />';
						}
						else
							echo '<img src='.$userimage.' height="50px" width="50px" class="img-circle"/>';	
					?>
						</div>
						<p class="inbox-item-author"><?php echo MJ_gmgt_get_display_name($retrieved_data->member_id);?></p>
						</div>
						
					<?php 
					}
					else 
					{
						?>
					<p><?php _e('No members yet','gym_mgt');?></p>
					<?php
					} 
					?>				
				</div>
			</div>
		</div>
	<?php 
	}
	elseif($type == 'view_membership')
	{ 
		$obj_membership=new MJ_Gmgtmembership;
		$membership_data = $obj_membership->MJ_gmgt_get_single_membership($recoed_id);	
		?>
		<div class="form-group"> 	
			<a href="#" class="close-btn badge badge-success pull-right">X</a>
			<h4 class="modal-title" id="myLargeModalLabel">
				<?php echo  __('Membership Details','gym_mgt'); ?>
			</h4>
		</div>
		<hr>		
		<div class="panel-body view_details_popup_body">
			<table id="membesrship_list" class="table table-striped" cellspacing="0" width="100%" align="center">
			<tbody>
				<tr>
					<td>
						<b><?php _e('Membership Name :','gym_mgt');?></b>
						<?php echo $membership_data->membership_label; ?>
					</td>
					<td>
						<b><?php _e('Membership Category :','gym_mgt');?></b></label>
						<?php echo get_the_title($membership_data->membership_cat_id); ?>
					</td>
				</tr>			
			    
				<tr>	
					<td>
						<b><?php _e('Membership Period(Days) :','gym_mgt');?></b>
						<?php echo $membership_data->membership_length_id; ?>
					</td>
					<td>
						<b><?php _e('Members Limit :','gym_mgt');?></b>
						<?php 
							if($membership_data->membership_class_limit!='unlimited')
							{
								echo $membership_data->on_of_member;
							}
							else
							{
								_e('Unlimited','gym_mgt');
							}				
						?>
					</td>
				<tr/>
				<tr>
					<td>
						<b><?php _e('Class Limit :','gym_mgt');?></b></label>
						<?php 
							if($membership_data->classis_limit!='unlimited')
							{
								echo $membership_data->on_of_classis;
							}
							else
							{
								_e('Unlimited','gym_mgt');
							}				
						?>					
					</td>
					<td>
						<b><?php _e('Membership Amount :','gym_mgt');?></b>
						<?php echo MJ_gmgt_get_currency_symbol(get_option( 'gmgt_currency_code' )); ?><?php echo $membership_data->membership_amount; ?>
					</td>		
				</tr>
				
				<tr>
					<td>
						<b><?php _e('Installment Plan :','gym_mgt');?></b>
						<?php echo MJ_gmgt_get_currency_symbol(get_option( 'gmgt_currency_code' )); ?><?php echo $membership_data->installment_amount." ".get_the_title( $membership_data->install_plan_id );?>
					</td>
					<td>
						<b><?php _e('Signup Fee :','gym_mgt');?></b>
						<?php echo MJ_gmgt_get_currency_symbol(get_option( 'gmgt_currency_code' )); ?><?php echo $membership_data->signup_fee; ?>
					</td>
				</tr>
				<tr>
					<td>
						<b><?php _e('Tax','gym_mgt');?>(%) :</b>
						<?php echo MJ_gmgt_tax_name_by_tax_id_array($membership_data->tax); ?>
					</td>
					<td>
						<b><?php _e('Membership Description :','gym_mgt');?></b>
						<?php echo stripslashes($membership_data->membership_description); ?>
					</td>
				</tr>
			</tbody>
			</table>	
		</div>	
	<?php 
	}
	elseif($type == 'view_class')
	{ 
		$obj_class=new MJ_Gmgtclassschedule;
		
		$class_data = $obj_class->MJ_gmgt_get_single_class($recoed_id);
		?>
		<div class="form-group"> 	
			<a href="#" class="close-btn badge badge-success pull-right">X</a>
			<h4 class="modal-title" id="myLargeModalLabel">
				<?php echo  __('Class Details','gym_mgt'); ?>
			</h4>
		</div>
		<hr>		
		<div class="panel-body view_details_popup_body">
			<table id="membesrship_list" class="table table-striped" cellspacing="0" width="100%" align="center">
				<tbody>
					<tr>
						<td>
							<b><?php _e('Class Name :','gym_mgt');?></b>
							<?php echo $class_data->class_name; ?>
						</td>
						<td>
							<b><?php _e('Staff Member :','gym_mgt');?></b>
								<?php 
								$userdata=get_userdata( $class_data->staff_id );
								echo $userdata->display_name;
								?>
						</td>
					</tr>
					<tr>
						<td>
							<b><?php _e('Start Date :','gym_mgt');?></b>
							<?php echo MJ_gmgt_getdate_in_input_box($class_data->start_date);?>
						</td>
						<td>
							<b><?php _e('End Date :','gym_mgt');?></b></label>
							<?php echo MJ_gmgt_getdate_in_input_box($class_data->end_date);?>
						</td>
					</tr>
			
					<tr>
						<td>
							<b><?php _e('Starting Time :','gym_mgt');?></b>
							<?php echo MJ_gmgt_timeremovecolonbefoream_pm($class_data->start_time);?>
						</td>
						<td>
							<b><?php _e('Ending Time :','gym_mgt');?></b>
							<?php echo MJ_gmgt_timeremovecolonbefoream_pm($class_data->end_time);?>
						</td>
					</tr>
					<tr>
						<td>
							<b><?php _e('Day :','gym_mgt');?></b>	
							<?php 
							$days_array=json_decode($class_data->day); 
							$days_string=array();
							if(!empty($days_array))
							{
								foreach($days_array as $day)
								{
									$days_string[]=substr($day,0,3);
								}
							}
							echo implode(", ",$days_string);
							?>
						</td>
						<td>
							<b><?php _e('Membership Name :','gym_mgt');?></b>
							<?php
								$membersdata=array();
								$membersdata = $obj_class->MJ_gmgt_get_class_members($recoed_id);
								if(!empty($membersdata))
								{	
									foreach($membersdata as $key=>$val)
									{
										$data[]= MJ_gmgt_get_membership_name($val->membership_id);
									}
								}	
								echo implode(',',$data); 
							?>
						</td>			
					</tr>
					<tr>
						<td>	
							<b><?php _e('Member Limit :','gym_mgt');?></b>
							<?php echo $class_data->member_limit; ?>
						</td>
						<td></td>
					</tr>
				</tbody>
			</table>
		</div>	
	<?php 
	}
	elseif($type == 'view_product')
	{ 
		$obj_product=new MJ_Gmgtproduct;
		$product_data = $obj_product->MJ_gmgt_get_single_product($recoed_id);
	  
		?>
		<div class="form-group"> 	
			<a href="#" class="close-btn badge badge-success pull-right">X</a>
			<h4 class="modal-title" id="myLargeModalLabel">
				<?php echo  __('Product Details','gym_mgt'); ?>
			</h4>
		</div>
		<hr>		
		<div class="panel-body view_details_popup_body">
			<table id="product_list" class="table table-striped" cellspacing="0" width="100%" align="center">
				<tbody>
					<tr>
						<td>
							<b><?php _e('Product Image :','gym_mgt');?></b></label>
								<?php
									if(empty($product_data->product_image))
									{
										echo '<img src='.get_option( 'gmgt_system_logo' ).' height="50px" width="50px" class="img-circle" />';
									}
									else
									{
										echo '<img src='.$product_data->product_image.' height="50px" width="50px" class="img-circle"/>';
									}	
								?>
						</td>
						<td>
							<b><?php _e('Product Name :','gym_mgt');?></b>
							<?php echo $product_data->product_name; ?>
						</td>
					</tr>
					<tr>
						<td>
							<b><?php _e('Product Category :','gym_mgt');?></b>
							<?php 					
								echo get_the_title($product_data->product_cat_id);
							?>
						</td>
						<td>
							<b><?php _e('Product Price :','gym_mgt');?></b>
							<?php echo MJ_gmgt_get_currency_symbol(get_option( 'gmgt_currency_code' )); ?><?php echo $product_data->price; ?>
						</td>
					</tr>
					<tr>
						<td>
							<b><?php _e('Product Quantity :','gym_mgt');?></b>
							<?php echo $product_data->quentity;	?>
						</td>
						<td>
							<b><?php _e('SKU Number :','gym_mgt');?></b>
							<?php echo $product_data->sku_number; ?>
						</td>
					</tr>
					<tr>
						<td>
							<b><?php _e('Manufacturer Company Name :','gym_mgt');?></b>
							<?php
								if(!empty($product_data->manufacture_company_name))
								{ 					
									echo $product_data->manufacture_company_name;
								}
								else
								{
									echo '-';
								}
							?>
						</td>
						<td>
							<b><?php _e('Manufacturer Date :','gym_mgt');?></b>
							<?php 
								if(!empty($product_data->manufacture_date))
								{
									echo MJ_gmgt_getdate_in_input_box($product_data->manufacture_date); 
								}	
								else
								{
									echo '-';
								}
							?>
						</td>
					</tr>	
					<tr>
						<td>
							<b><?php _e('Product Description :','gym_mgt');?></b>
							<?php 
							if(!empty($product_data->product_description))
							{	
								echo $product_data->product_description;
							}
							else
							{
								echo '-';
							}		
							?>
						</td>
						<td>
							<b><?php _e('Product Specification :','gym_mgt');?></b>
							<?php 
								if(!empty($product_data->product_specification))
								{				
									echo $product_data->product_specification; 
								}
								else
								{
									echo '-';
								}		
							?>
						</td>
					</tr>
				</tbody>
			</table>
		</div>	
	<?php 
	}
	elseif($type == 'view_notice')
	{ 		
		$notice_data = get_post($recoed_id);		
		?>
		<div class="form-group"> 	
			<a href="#" class="close-btn badge badge-success pull-right">X</a>
			<h4 class="modal-title" id="myLargeModalLabel">
				<?php echo  __('Notice Details','gym_mgt'); ?>
			</h4>
		</div>
		<hr>		
		<div class="panel-body view_details_popup_body">
			<table id="notice_list" class="table table-striped" cellspacing="0" width="100%" align="center">
				<tbody>
					<tr>
						<td>
							<b><?php _e('Notice Title :','gym_mgt'); ?></b>
							<?php echo $notice_data->post_title; ?>
						</td>
					</tr>
					<tr>
						<td>
							<b><?php _e('Notice Comment :','gym_mgt'); ?></b>
							<?php 					
							echo $notice_data->post_content;
							?>
						</td>
					</tr>
					<tr>
						<td>
							<b><?php _e('Notice For :','gym_mgt'); ?></b>
							<?php echo MJ_gmgt_GetRoleName(get_post_meta( $notice_data->ID, 'notice_for',true)); ?>	
						</td>							
					</tr>
			<?php
			if(get_post_meta( $notice_data->ID, 'notice_for',true) == 'member')
			{
			?>
			<tr>
				<td>
					<b><?php _e('Class Name:','gym_mgt'); ?></b>
					<?php if(!empty($notice_data->ID)) { echo MJ_gmgt_get_class_name(get_post_meta( $notice_data->ID, 'gmgt_class_id',true)); } ?>
				</td>
			</tr>	
			<?php
			}
			?>
			<tr>
				<td>
					<b><?php _e('Start Date :','gym_mgt'); ?></b>
					<?php echo MJ_gmgt_getdate_in_input_box(get_post_meta($notice_data->ID,'gmgt_start_date',true)); ?>
				</td>
			</tr>	
			<tr>
				<td>
					<b><?php _e('End Date :','gym_mgt'); ?></b>
					<?php echo MJ_gmgt_getdate_in_input_box(get_post_meta( $notice_data->ID, 'gmgt_end_date',true)); ?>
				</td>
			</tr>
				</tbody>
			</table>
			</div>
		</div>	
	<?php 
	}
	die();
}
//MEASUREMENT DELETE FUNCTION
function MJ_gmgt_measurement_delete()
{
	$obj_workout = new MJ_Gmgtworkout();
	$measurement_id = $_REQUEST['measurement_id'];
	$measurement_data = $obj_workout->MJ_gmgt_get_measurement_deleteby_id($measurement_id);
	die();
}

// MEMBRSHIP LOAD END DATE FUNTION 
function MJ_gmgt_load_enddate()
{
$date = trim($_POST['start_date']);
$new_date = DateTime::createFromFormat(MJ_gmgt_get_phpdateformat(get_option('gmgt_datepicker_format')), $date); 
$joiningdate=$new_date->format('Y-m-d');

$membership_id = $_POST['membership_id'];
$obj_membership=new MJ_Gmgtmembership;	
$membership=$obj_membership->MJ_gmgt_get_single_membership($membership_id);
$validity=$membership->membership_length_id;
$expiredate= date(MJ_gmgt_get_phpdateformat(get_option('gmgt_datepicker_format')), strtotime($joiningdate. ' + '.$validity.' days'));
echo $expiredate;
die();
}

//VIEW MEASUREMENT FUNCTION
function MJ_gmgt_measurement_view()
{
	$obj_workout = new MJ_Gmgtworkout();
	$curr_user_id=get_current_user_id();
	$obj_gym=new MJ_Gym_management($curr_user_id);
	$user_id = $_REQUEST['user_id'];	
	$measurement_data = $obj_workout->MJ_gmgt_get_all_measurement_by_userid($user_id);
	//access right
	$page_name='workouts';
	$user_access=MJ_gmgt_get_userrole_wise_manually_page_access_right_array($page_name);
	
	?>
	<div class="form-group"> 	<a href="#" class="close-btn badge badge-success pull-right">X</a>
		<h4 class="modal-title" id="myLargeModalLabel">
			<?php 
				$userimage=get_user_meta($user_id, 'gmgt_user_avatar', true);
				if(empty($userimage))
				{
					echo '<img src='.get_option( 'gmgt_system_logo' ).' height="50px" width="50px" class="img-circle" />';
				}
				else
					echo '<img src='.$userimage.' height="50px" width="50px" class="img-circle"/> '; 
				?>
				<div style="display: inline;padding-left: 10px;">
				<?php
				echo  MJ_gmgt_get_display_name($user_id).__('\'s Measurement','gym_mgt'); ?>
				</div>
		</h4>
	</div>
	<hr>
	<div class="panel-body">
		<div class="table-responsive box-scroll">
       		<table id="measurement_list" class="display table" cellspacing="0" width="100%">
	        	 <thead>
	            	<tr>						
						<th><?php  _e( 'Photo', 'gym_mgt' ) ;?></th>
						<th><?php  _e( 'Measurement', 'gym_mgt' ) ;?></th>
						<th><?php  _e( 'Result', 'gym_mgt' ) ;?></th>			
					    <th><?php  _e( 'Record Date', 'gym_mgt' ) ;?></th>
						<?php if($user_access['edit']=='1' || $user_access['delete']=='1')
						{  ?>
					     <th><?php  _e( 'Action', 'gym_mgt' ) ;?></th>	
						<?php
						}
						?>
		            </tr>		            	 
		        </thead>
		        <tbody>
		        <?php 
		        	
		        if(!empty($measurement_data))
		        {
		        	foreach ($measurement_data as $retrieved_data)
		        	{ ?>
			        <tr id="row_<?php echo $retrieved_data->measurment_id?>">
						<td class="user_image"><?php $userimage=$retrieved_data->gmgt_progress_image;
							if(empty($userimage)){
								echo '<img src='.get_option( 'gmgt_system_logo' ).' height="50px" width="50px" class="img-circle" />';
							}
							else
								echo '<img src='.$userimage.' height="50px" width="50px" class="img-circle"/>';
						?>
						</td>
			        	<td class="recorddate"><?php echo $retrieved_data->result_measurment;?></td>			  
						<td class="duration"><?php echo $retrieved_data->result." ".MJ_gmgt_measurement_counts_lable_array($retrieved_data->result_measurment);?></td>
						<td class="result"><?php echo MJ_gmgt_getdate_in_input_box($retrieved_data->result_date);?></td>
						<td class="result">
							<?php if($obj_gym->role=='administrator'){?>
							<a href="?page=gmgt_workout&tab=addmeasurement&action=edit&measurment_id=<?php echo $retrieved_data->measurment_id?>" class="btn btn-info"><?php _e('Edit', 'gym_mgt' ) ;?></a>
							<?php
							}	
							else
							{							
								if($user_access['edit']=='1')
								{
								?>
									<a href="?dashboard=user&page=workouts&tab=addmeasurement&action=edit&measurment_id=<?php echo $retrieved_data->measurment_id?>" class="btn btn-info">
									<?php _e('Edit', 'gym_mgt' ) ;?></a>
								<?php
								}
								if($user_access['delete']=='1')
								{
								?>		
									<a href="#" class="btn btn-danger measurement_delete" data-val="<?php echo $retrieved_data->measurment_id?>"><?php _e('Delete','gym_mgt');?></a>
								<?php
								}
							}	
								?>	
						</td>
			        </tr>
					<?php 
					}
				}
				else 
				{
				?>
					<tr>
					<td colspan=3> <?php _e('No Record Found','gym_mgt');?></td>
					</tr>
				<?php 
				}
				?>
		        </tbody>
		        	
		        </table>
		</div>
		<?php
		die(); 
}

//ADD WORKOUT FUNCTION
function MJ_gmgt_add_workout()
{	
	if(isset($_REQUEST['data_array']))
	{
		$data_array = $_REQUEST['data_array'];
		$data_value = json_encode($data_array);
		
		echo "<input type='hidden' value='".htmlspecialchars($data_value,ENT_QUOTES)."' name='activity_list[]'>";
	}
	die();
}
//ADD Nutrition FUNCTION
function MJ_gmgt_add_nutrition()
{
	if(isset($_REQUEST['data_array']))
	{		
		$data_array =$_REQUEST['data_array'];
	
		$data_value = json_encode($data_array);
	
		echo "<input type='hidden' value='".htmlspecialchars($data_value,ENT_QUOTES)."' name='nutrition_list[]'>";
	}
	die();
}
//DELETE WORKOUT FUNCTION 
function MJ_gmgt_delete_workout()
{

	
	$work_out_id = $_REQUEST['workout_id'];
	global $wpdb;
	$table_workout = $wpdb->prefix. 'gmgt_assign_workout';
	$table_workout_data = $wpdb->prefix. 'gmgt_workout_data';
	$result = $wpdb->query("DELETE FROM $table_workout_data where workout_id= ".$work_out_id);
	$result = $wpdb->query("DELETE FROM $table_workout where workout_id= ".$work_out_id);
	die();
}
//DELETE nutrition FUNCTION
function MJ_gmgt_delete_nutrition()
{
	$work_out_id = $_REQUEST['workout_id'];
	global $wpdb;
	$table_gmgt_nutrition = $wpdb->prefix. 'gmgt_nutrition';
	$table_gmgt_nutrition_data = $wpdb->prefix. 'gmgt_nutrition_data';
	$result = $wpdb->query("DELETE FROM $table_gmgt_nutrition_data where nutrition_id= ".$work_out_id);
	$result = $wpdb->query("DELETE FROM $table_gmgt_nutrition where id = ".$work_out_id);
	die();
}

//GET PAYMENT DETAILS BY MEMBERSHIP
function MJ_gmgt_paymentdetail_bymembership()
{
	$membership_id = $_POST['membership_id'];
	global $wpdb;
	$gmgt_membershiptype = $wpdb->prefix.'gmgt_membershiptype';
	$sql = "SELECT * From $gmgt_membershiptype where membership_id = $membership_id";
	$result = $wpdb->get_row($sql);
	
	$membership_amount=$result->membership_amount;
	$signup_fee=$result->signup_fee;
	$membership_and_fees_amount=$membership_amount+$signup_fee;
	$tax_array=explode(',',$result->tax);
	
	if(!empty($tax_array))
	{
		$total_tax=0;
		foreach($tax_array as $tax_id)
		{
			$tax_percentage=MJ_gmgt_tax_percentage_by_tax_id($tax_id);
			$tax_amount=$membership_and_fees_amount * $tax_percentage / 100;
			
			$total_tax=$total_tax + $tax_amount;				
		}
		
		$total_membership_amount=$membership_and_fees_amount+$total_tax;
	}
	else
	{
		$total_tax=0;
		$total_membership_amount=$membership_and_fees_amount;
	}
		
	$payment_detail = array();
	$payment_detail['title'] = $result->membership_label;
	$payment_detail['price'] = str_replace(',','',number_format($total_membership_amount,2));
	
	echo json_encode($payment_detail);
	die();
}
//ADD PAYMENT POPUP FUNCTION
function MJ_gmgt_member_add_payment()
{ ?>
	
	<script type="text/javascript">
	$(document).ready(function() {
		$('#expense_form').validationEngine({promptPosition : "bottomRight",maxErrorsPerField: 1});	
	} );
	</script>
	<?php 
		$mp_id = $_POST['idtest'];
		$member_id= $_POST['member_id'];
		$due_amount = $_POST['due_amount'];
		$view_type = $_POST['view_type'];	
		
	?>
	<div class="modal-header">
			<a href="#" class="close-btn badge badge-success pull-right">X</a>
			<h4 class="modal-title"><?php echo get_option('gmgt_system_name');?></h4>
	</div>
	<div class="modal-body">
		 <form name="expense_form" action="" method="post" class="form-horizontal" id="expense_form">
         <?php $action = isset($_REQUEST['action'])?$_REQUEST['action']:'insert';?>
		<input type="hidden" name="action" value="<?php echo $action;?>">
		
		<input type="hidden" name="mp_id" value="<?php echo $mp_id;?>">
		<input type="hidden" name="member_id" value="<?php echo $member_id;?>">
		<input type="hidden" name="view_type" value="<?php echo $view_type;?>">
		
		<input type="hidden" name="created_by" value="<?php echo get_current_user_id();?>">
		<div class="form-group">
			<label class="col-sm-3 control-label" for="amount"><?php _e('Paid Amount','gym_mgt');?>(<?php echo MJ_gmgt_get_currency_symbol(get_option( 'gmgt_currency_code' )); ?>)<span class="require-field">*</span></label>
			<div class="col-sm-8">
				<input id="amount" class="form-control validate[required] text-input" type="number" onkeypress="if(this.value.length==10) return false;" step="0.01" min="0" max="<?php echo $due_amount ?>" value="<?php echo $due_amount ?>" name="amount">
			</div>
		</div>
		<div class="form-group">
			<input type="hidden" name="payment_status" value="paid">
			<label class="col-sm-3 control-label" for="payment_method"><?php _e('Payment By','gym_mgt');?><span class="require-field">*</span></label>
			<div class="col-sm-8">
				<?php 
				global $current_user;
				$user_roles = $current_user->roles;
				$user_role = array_shift($user_roles);
				?>
				<select name="payment_method" id="payment_method" class="form-control">
					<?php if($user_role != 'member'){?>
					<option value="Cash"><?php _e('Cash','gym_mgt');?></option>
					<option value="Cheque"><?php _e('Cheque','gym_mgt');?></option>
					<option value="Bank Transfer"><?php _e('Bank Transfer','gym_mgt');?></option>		
					<?php
					} 
					else 
					{					
						if(is_plugin_active('paymaster/paymaster.php') && get_option('gmgt_paymaster_pack')=="yes"){ 
						$payment_method = get_option('pm_payment_method');
						print '<option value="'.$payment_method.'">'.$payment_method.'</option>';
						} else{
							print '<option value="Paypal">Paypal</option>';
						} 
					}
					?>						
			</select>
			</div>
		</div>
		<div class="form-group payment_description">			
			<label class="col-sm-3 control-label" for=""><?php _e('Payment Details','gym_mgt');?></label>
			<div class="col-sm-8">			
				<textarea name="payment_description" class="form-control validate[custom[address_description_validation]]" maxlength="150"></textarea>					
			</div>
		</div>
		<div class="col-sm-offset-2 col-sm-8">
        	 <input type="submit" value="<?php _e('Add Payment','gym_mgt');?>" name="add_fee_payment" class="btn btn-success"/>
        </div>
		</form>
	</div>
<?php
	die();
}
//VIEW PAYMENT HISTORY
function MJ_gmgt_member_view_paymenthistory()
{
	$mp_id = $_REQUEST['idtest'];
	$fees_detail_result = MJ_gmgt_get_single_membership_payment_record($mp_id);
	$fees_history_detail_result = MJ_gmgt_get_payment_history_by_mpid($mp_id);
	?>
	<div class="modal-header">
			<a href="#" class="close-btn badge badge-success pull-right">X</a>
			<h4 class="modal-title"><?php echo get_option('gmgt_system_name');?></h4>
	</div>
	<div class="modal-body">
	
	<div id="invoice_print"> 
		<table width="100%" border="0">
						<tbody>
							<tr>
								<td width="70%">
									<img style="max-height:80px;" src="<?php echo get_option( 'gmgt_system_logo' ); ?>">
								</td>
								<td align="right" width="24%">
									
									<h5><?php $issue_date='DD-MM-YYYY';
												
													$issue_date=$fees_detail_result->created_date;
													
									
									echo __('Issue Date','gym_mgt')." : ".MJ_gmgt_getdate_in_input_box($issue_date);?></h5>
									
						<h5><?php echo __('Status','gym_mgt')." : "; echo "<span class='btn btn-success btn-xs'>";
					echo MJ_gmgt_get_membership_paymentstatus($fees_detail_result->mp_id);
					echo "</span>";?></h5>
								</td>
							</tr>
						</tbody>
					</table>
					<hr>
					<table width="100%" border="0">
						<tbody>
							<tr>
								<td align="left">
									<h4><?php _e('Payment To','gym_mgt');?> </h4>
								</td>
								<td align="right">
									<h4><?php _e('Bill To','gym_mgt');?> </h4>
								</td>
							</tr>
							<tr>
								<td valign="top" align="left">
									<?php echo get_option( 'gmgt_system_name' )."<br>"; 
									 echo get_option( 'gmgt_gym_address' ).","; 
									 echo get_option( 'gmgt_contry' )."<br>"; 
									 echo get_option( 'gmgt_contact_number' )."<br>"; 
									?>
									
								</td>
								<td valign="top" align="right">
									<?php
									$member_id=$fees_detail_result->member_id;								
										
										$patient=get_userdata($member_id);
												
										echo $patient->display_name."<br>"; 
										 echo get_user_meta( $member_id,'address',true ).","; 
										 echo get_user_meta( $member_id,'city_name',true ).","; 
										 echo get_user_meta( $member_id,'zip_code',true ).",<BR>"; 
										 echo get_user_meta( $member_id,'state_name',true ).","; 
										 echo get_option( 'gmgt_contry' ).","; 
										 echo get_user_meta( $member_id,'mobile',true )."<br>"; 
									
									?>
								</td>
							</tr>
						</tbody>
					</table>
					<hr>
					<table class="table table-bordered" width="100%" border="1" style="border-collapse:collapse;">
						<thead>
							<tr>
								<th class="text-center">#</th>
								<th class="text-center"> <?php _e('Fees Type','gym_mgt');?></th>
								<th><?php _e('Total','gym_mgt');?> </th>
								
							</tr>
						</thead>
						<tbody>
							<td>1</td>
							<td><?php echo MJ_gmgt_get_membership_name($fees_detail_result->membership_id);?></td>
							<td><?php echo MJ_gmgt_get_currency_symbol(get_option( 'gmgt_currency_code' )); ?> <?php echo $fees_detail_result->membership_amount;?></td>
						</tbody>
						</table>
						<table width="100%" border="0">
						<tbody>
							
							<tr>
								<td width="80%" align="right"><?php _e('Subtotal :','gym_mgt');?></td>
								<td align="right"><?php echo MJ_gmgt_get_currency_symbol(get_option( 'gmgt_currency_code' )); ?> <?php echo $fees_detail_result->membership_amount;?></td>
							</tr>
							<tr>
								<td width="80%" align="right"><?php _e('Payment Made :','gym_mgt');?></td>
								<td align="right"><?php echo MJ_gmgt_get_currency_symbol(get_option( 'gmgt_currency_code' )); ?> <?php echo $fees_detail_result->paid_amount;?></td>
							</tr>
							<tr>
								<td width="80%" align="right"><?php _e('Due Amount  :','gym_mgt');?></td>
								<td align="right"><?php echo MJ_gmgt_get_currency_symbol(get_option( 'gmgt_currency_code' )); ?> <?php  $dueamount=abs($fees_detail_result->membership_amount - $fees_detail_result->paid_amount); echo number_format($dueamount,2); ?></td>
							</tr>
							
						</tbody>
					</table>
					
					<?php if(!empty($fees_history_detail_result))
					{?>
					<hr class="width_100 flot_left_invoice_history_hr">
					<h4><?php _e('Payment History','gym_mgt');?></h4>
					<table class="table table-bordered" width="100%" border="1" style="border-collapse:collapse;">
					<thead>
							<tr>
								<th class="text-center"><?php _e('Date','gym_mgt');?></th>
								<th class="text-center"> <?php _e('Amount','gym_mgt');?></th>
								<th><?php _e('Method','gym_mgt');?> </th>
								
							</tr>
						</thead>
						<tbody>
							<?php 
							foreach($fees_history_detail_result as  $retrive_date)
							{
							?>
							<tr>
							<td><?php echo MJ_gmgt_getdate_in_input_box($retrive_date->paid_by_date);?></td>
							<td><?php  echo MJ_gmgt_get_currency_symbol(get_option( 'gmgt_currency_code' )); ?> <?php echo $retrive_date->amount;?></td>
							<td><?php echo  $retrive_date->payment_method;?></td>
							</tr>
							<?php }?>
						</tbody>
					</table>
					<?php }?>
	</div>
	</div>
	<?php
	die();
}
//CHECK MEMBERRSHIP FUNCTION
function MJ_gmgt_check_membership($userid)
{
	$validity=0;
	$obj_membership=new MJ_Gmgtmembership;
	$membershipid=get_user_meta($userid,'membership_id',true);
	$membershistatus=get_user_meta($userid,'membership_status',true);
	$joiningdate=get_user_meta($userid,'begin_date',true);
	$autorenew=get_user_meta($userid,'auto_renew',true);
	$membership=$obj_membership->MJ_gmgt_get_single_membership($membershipid);
	if(!empty($membership))
		$validity=$membership->membership_length_id;
	$expiredate="";
	$today = date("Y-m-d");
	 $expiredate= date('Y-m-d', strtotime($joiningdate. ' + '.$validity.' days'));
	if($membershistatus!="Dropped")
	{
		if($today < $expiredate)
		{
			$returnans=update_user_meta( $userid, 'membership_status','Continue');		 
			 return $expiredate;
		}	 
		elseif($autorenew=="Yes")
		{
			 $returnans=update_user_meta( $userid, 'begin_date',$today );
			  $bigindate=get_user_meta($userid,'begin_date',true);
			return $expiredate= date('Y-m-d', strtotime($bigindate. ' + '.$validity.' days'));
		}
		else
		{
			  $returnans=update_user_meta( $userid, 'membership_status','Expired');
			  return $expiredate;
		}
	}
	else
	{
		return $expiredate;
	}
}
add_action('init','MJ_gmgt_send_alert_message');
//SEND REMINDER MAIL FUNCTION
function MJ_gmgt_send_alert_message()
{
	$enable_service=get_option('gym_enable_membership_alert_message');
	if($enable_service=='yes')
	{
		$gmgt_system_name=get_option('gmgt_system_name');
		$search=array('[GMGT_MEMBERNAME]','[GMGT_STARTDATE]','[GMGT_ENDDATE]','[GMGT_MEMBERSHIP]','[GMGT_GYM_NAME]');
		
		$before_days=get_option('gmgt_reminder_before_days');
		$today=date('Y-m-d');
		$get_members = array('role' => 'member');
				$membersdata=get_users($get_members);
			 if(!empty($membersdata))
			 {
				foreach ($membersdata as $retrieved_data){
					
					$expiredate=MJ_gmgt_check_membership($retrieved_data->ID);
					$start_date=$retrieved_data->begin_date;
					$membership_id=get_user_meta($retrieved_data->ID,'membership_id',true);
					$membership_name=MJ_gmgt_get_membership_name($membership_id);
					// reminder subject value//
					$subject_search=array('[GMGT_GYM_NAME]');
					$subject_content=get_option('gmgt_reminder_subject');
					$subject_replace = array($gmgt_system_name);
					$subject_content = str_replace($subject_search, $subject_replace, $subject_content);
					
					//reminder message value//
					$message_content=get_option('gym_reminder_message');
					$replace = array($retrieved_data->display_name,$retrieved_data->begin_date,$expiredate,$membership_name);
					$message_content = str_replace($search, $replace, $message_content);
					
					$mail_sent=MJ_gmgt_check_alert_mail_send($retrieved_data->ID,$expiredate,$start_date);
					$date1=date_create($today);
					$date2=date_create($expiredate);
					$interval = $date1->diff($date2);
					$difference=$interval->format('%R%a');
					
					if($difference<= +$before_days && $difference > 0)
					{					
						if($mail_sent==0)
						{
							$to=$retrieved_data->user_email;							
							$from=get_option('admin_email');
							$headers = 'From: <'.$from.'>' . "\r\n";
							$success=wp_mail( $to, $subject_content, $message_content, $headers ); 
							if($success)
								MJ_gmgt_insert_alert_mail($retrieved_data->ID,$expiredate,$start_date,$membership_id);
						}
					}					
				}
			 }
	}
	
}
//SEND REMINDER MAIL CHECK  FUNCTION
function MJ_gmgt_check_alert_mail_send($member_id,$expiredate,$start_date)
{
	global $wpdb;
	$table_gmgt_alert_mail_log = $wpdb->prefix . 'gmgt_alert_mail_log';
	
	$result= $wpdb->get_var("SELECT count(*) FROM ".$table_gmgt_alert_mail_log." WHERE member_id =".$member_id." and start_date='".$start_date."' and end_date='".$expiredate."'");
	return $result;
}
//INSER REMINDER MESGAE FUNCTION
function MJ_gmgt_insert_alert_mail($member_id,$expiredate,$start_date,$membership_id)
{
	global $wpdb;
	$table_gmgt_alert_mail_log = $wpdb->prefix . 'gmgt_alert_mail_log';
	$alertdata['member_id']=$member_id;
	$alertdata['membership_id']=$membership_id;
	$alertdata['start_date']=$start_date;
	$alertdata['end_date']=$expiredate;
	$alertdata['alert_date']=date("Y-m-d");
	$result=$wpdb->insert( $table_gmgt_alert_mail_log, $alertdata );
	return $result;
	
}

//GET MEMBER Attendance
function MJ_gmgt_view_member_attendance($start_date,$end_date,$user_id)
{
	
	global $wpdb;
	$tbl_name = $wpdb->prefix .'gmgt_attendence';
	
	$result =$wpdb->get_results("SELECT *  FROM $tbl_name where user_id=$user_id AND role_name = 'member' and attendence_date between '$start_date' and '$end_date'");
	return $result;
}
function MJ_gmgt_get_attendence($userid,$curr_date)
{
	global $wpdb;
	$table_name = $wpdb->prefix . "gmgt_attendence";
	
	$result=$wpdb->get_var("SELECT status FROM $table_name WHERE attendence_date='$curr_date'  and user_id=$userid");
	return $result;

}
//GET CURENT USER CLASS
function MJ_gmgt_get_current_userclass($id)
{
	global $wpdb;
	$table_name = $wpdb->prefix .'gmgt_class_schedule';
	$result =$wpdb->get_results("SELECT *  FROM $table_name where staff_id=$id OR asst_staff_id =$id");
	return $result;
}
//GET INBOX MESSGAE FUNCTION
function MJ_gmgt_get_inbox_message($user_id,$p=0,$lpm1=10)
{
	
	global $wpdb;
	$tbl_name_message = $wpdb->prefix .'Gmgt_message';
	$tbl_name_message_replies = $wpdb->prefix .'gmgt_message_replies';
	//$inbox =$wpdb->get_results("SELECT *  FROM $tbl_name_message where receiver = $user_id limit $p , $lpm1");
	
	$inbox = $wpdb->get_results("SELECT DISTINCT b.message_id, a.* FROM $tbl_name_message a LEFT JOIN $tbl_name_message_replies b ON a.message_id = b.message_id WHERE ( a.receiver = $user_id OR b.receiver_id =$user_id)  ORDER BY 	date DESC limit $p , $lpm1");
	
	return $inbox;
}
//COUNT UNREAD FUNCTION
function MJ_gmgt_count_unread_message($user_id)
{
	
	global $wpdb;
	$tbl_name = $wpdb->prefix .'Gmgt_message';
	
	$inbox =$wpdb->get_results("SELECT *  FROM $tbl_name where receiver = $user_id and status=0");
	return $inbox;
}
//ADMIN SIDE INBOX PAGINATION FUNCTION
function MJ_gmgt_admininbox_pagination($totalposts,$p,$lpm1,$prev,$next)
{
	$adjacents = 1;
	$page_order = "";
	$pagination = "";
	$form_id = 1;
	if(isset($_REQUEST['form_id']))
		$form_id=$_REQUEST['form_id'];
	if(isset($_GET['orderby']))
	{
		$page_order='&orderby='.$_GET['orderby'].'&order='.$_GET['order'];
	}
	if($totalposts > 1)
	{
		$pagination .= '<div class="btn-group">';
		
		if ($p > 1)
			$pagination.= "<a href=\"?page=smgt_message&tab=inbox&pg=$prev\" class=\"btn btn-default\"><i class=\"fa fa-angle-left\"></i></a> ";
		else
			$pagination.= "<a class=\"btn btn-default disabled\"><i class=\"fa fa-angle-left\"></i></a> ";

		if ($p < $totalposts)
			$pagination.= " <a href=\"?page=smgt_message&tab=inbox&pg=$next\" class=\"btn btn-default next-page\"><i class=\"fa fa-angle-right\"></i></a>";
		else
			$pagination.= " <a class=\"btn btn-default disabled\"><i class=\"fa fa-angle-right\"></i></a>";
		$pagination.= "</div>\n";
	}
	return $pagination;
}
//GET DISPLAY NAME BY ID FUNCTION
function MJ_gmgt_get_display_name($user_id) 
{
	if (!$user = get_userdata($user_id))
		return false;
	return $user->data->display_name;
}
//GET USER ALL MESSAGE FUNCTION
function MJ_gmgt_get_all_user_in_message()
{
	$staff_member = get_users(array('role'=>'staff_member'));
	$accountant = get_users(array('role'=>'accountant'));
	$member = get_users(array('role'=>'member'));
	
	$obj_gym = new MJ_Gym_management(get_current_user_id());
		
	$all_user = array('member'=>$member,
			'staff_member'=>$staff_member,
			'accountant'=>$accountant,
			
	);
	$return_array = array();
	
	foreach($all_user as $key => $value)
	{
		if(!empty($value))
		{
		 echo '<optgroup label="'.$key.'" style = "text-transform: capitalize;">';
		 foreach($value as $user)
		 {
		 	echo '<option value="'.$user->ID.'">'.$user->display_name.'</option>';
		 }
		}
	}
}
//GET ALL CLASSES FUNCTION
function MJ_gmgt_get_allclass()
{	
	global $wpdb;
	$table_name = $wpdb->prefix .'gmgt_class_schedule';
	
	return $classdata =$wpdb->get_results("SELECT * FROM $table_name", ARRAY_A);
	//print_r($classdata);
}
//GET USER NOTICE BY ROLE WISE FUNCTION
function MJ_gmgt_get_user_notice($role,$class_id)
{		
	if($class_id == 'all')
	{
		$userdata=get_users(array('role'=>$role));
	}
	else
	{			
		if($role=='member')
		{				
			foreach(MJ_gmgt_get_member_by_class_id($class_id) as $key=>$member_id)
			{
				$userdata[] = get_userdata($member_id->member_id);					
			}				
		}		
		else
		{	
			$userdata=get_users(array('role'=>$role));
		}		
	}
	
	return $userdata;
}
function MJ_gmgt_insert_record($tablenm,$records)
{
	global $wpdb;
	$table_name = $wpdb->prefix . $tablenm;
	return $result=$wpdb->insert( $table_name, $records);
	
}
//PAGINATION FUNCTION
function MJ_gmgt_pagination($totalposts,$p,$lpm1,$prev,$next)
{
	$adjacents = 1;
	$page_order = "";
	$pagination = "";
	$form_id = 1;
	if(isset($_REQUEST['form_id']))
		$form_id=$_REQUEST['form_id'];
	if(isset($_GET['orderby']))
	{
		$page_order='&orderby='.$_GET['orderby'].'&order='.$_GET['order'];
	}
	if($totalposts > 1)
	{
		$pagination .= '<div class="btn-group">';
		
		if ($p > 1)
			$pagination.= "<a href=\"?page=smgt_message&tab=sentbox&form_id=$form_id&pg=$prev$page_order\" class=\"btn btn-default\"><i class=\"fa fa-angle-left\"></i></a> ";
		else
			$pagination.= "<a class=\"btn btn-default disabled\"><i class=\"fa fa-angle-left\"></i></a> ";
		
		if ($p < $totalposts)
			$pagination.= " <a href=\"?page=smgt_message&tab=sentbox&form_id=$form_id&pg=$next\" class=\"btn btn-default next-page\"><i class=\"fa fa-angle-right\"></i></a>";
		else
			$pagination.= " <a class=\"btn btn-default disabled\"><i class=\"fa fa-angle-right\"></i></a>";
		$pagination.= "</div>\n";
	}
	return $pagination;
}
//COUNT SEND MESSAGE IN MESSAGE BOX
function MJ_gmgt_count_send_item($id)
{
	global $wpdb;
	$posts = $wpdb->prefix."posts";
	$total =$wpdb->get_var("SELECT Count(*) FROM ".$posts." Where post_type = 'message' AND post_author = $id");
	return $total;
}
//SEND MESSAGE FUNCTION
function MJ_gmgt_get_send_message($user_id,$max=10,$offset=0)
{
	
	global $wpdb;
	$tbl_name = $wpdb->prefix .'Gmgt_message';
	
	$obj_gym = new MJ_Gym_management($user_id);
	
	if(is_admin() || $obj_gym->role=='staff_member' || $obj_gym->role=='accountant' || $obj_gym->role == 'member' && get_option('gym_enable_member_can_message')=='yes')
	{
		
		$args['post_type'] = 'message';
		$args['posts_per_page'] =$max;
		$args['offset'] = $offset;
		$args['post_status'] = 'public';
		$args['author'] = $user_id;
		
		$q = new WP_Query();
		$sent_message = $q->query( $args );
	
	}
	else 
	{
		$sent_message =$wpdb->get_results("SELECT *  FROM $tbl_name where sender = $user_id ");
	}
	return $sent_message;
}
//GET EMAIL ID BY  USER ID FUNCTION
function MJ_gmgt_get_emailid_byuser_id($id)
{
	if (!$user = get_userdata($id))
		return false;
	return $user->data->user_email;
}
//INBOX MESSAGE COUNT FUNCTION
function MJ_gmgt_count_inbox_item($id)
{
	global $wpdb;
	$tbl_name = $wpdb->prefix .'Gmgt_message';
	$inbox =$wpdb->get_results("SELECT *  FROM $tbl_name where receiver = $id and status=0");
	return $inbox;
}
//INBOX PAGINATION FUNCTION
function MJ_gmgt_inbox_pagination($totalposts,$p,$lpm1,$prev,$next)
{
	$adjacents = 1;
	$page_order = "";
	$pagination = "";
	$form_id = 1;
	if(isset($_REQUEST['form_id']))
		$form_id=$_REQUEST['form_id'];
	if(isset($_GET['orderby']))
	{
		$page_order='&orderby='.$_GET['orderby'].'&order='.$_GET['order'];
	}
	if($totalposts > 1)
	{
		$pagination .= '<div class="btn-group">';
		
		if ($p > 1)
			$pagination.= "<a href=\"?dashboard=user&page=message&tab=inbox&pg=$prev\" class=\"btn btn-default\"><i class=\"fa fa-angle-left\"></i></a> ";
		else
			$pagination.= "<a class=\"btn btn-default disabled\"><i class=\"fa fa-angle-left\"></i></a> ";
	
		if ($p < $totalposts)
			$pagination.= " <a href=\"?dashboard=user&page=message&tab=inbox&pg=$next\" class=\"btn btn-default next-page\"><i class=\"fa fa-angle-right\"></i></a>";
		else
			$pagination.= "<a class=\"btn btn-default disabled\"><i class=\"fa fa-angle-right\"></i></a>";
		$pagination.= "</div>\n";
	}
	return $pagination;
}
//GET MESSAGE BY ID FUNCTION
function MJ_gmgt_get_message_by_id($id)
{
	global $wpdb;
	$table_name = $wpdb->prefix . "Gmgt_message";
	//return $retrieve_subject = $wpdb->get_row( "SELECT * FROM $table_name WHERE message_id=".$id);
	$qry = $wpdb->prepare( "SELECT * FROM $table_name WHERE message_id= %d ",$id);
	return $retrieve_subject = $wpdb->get_row($qry);

}
//FRONTED SIDE SENTBOX PAGINATIOBN FUNCTION
function MJ_gmgt_fronted_sentbox_pagination($totalposts,$p,$lpm1,$prev,$next)
{
	$adjacents = 1;
	$page_order = "";
	$pagination = "";
	$form_id = 1;
	if(isset($_REQUEST['form_id']))
		$form_id=$_REQUEST['form_id'];
	if(isset($_GET['orderby']))
	{
		$page_order='&orderby='.$_GET['orderby'].'&order='.$_GET['order'];
	}
	if($totalposts > 1)
	{
		$pagination .= '<div class="btn-group">';
		
		if ($p > 1)
			$pagination.= "<a href=\"?dashboard=user&page=message&tab=sentbox&pg=$prev$page_order\" class=\"btn btn-default\"><i class=\"fa fa-angle-left\"></i></a> ";
		else
			$pagination.= "<a class=\"btn btn-default disabled\"><i class=\"fa fa-angle-left\"></i></a> ";

		if ($p < $totalposts)
			$pagination.= " <a href=\"?dashboard=user&page=message&tab=sentbox&pg=$next\" class=\"btn btn-default next-page\"><i class=\"fa fa-angle-right\"></i></a>";
		else
			$pagination.= " <a class=\"btn btn-default disabled\"><i class=\"fa fa-angle-right\"></i></a>";
		$pagination.= "</div>\n";
	}
	return $pagination;
}
//GET USER WORKOUT FUNCTION
function MJ_gmgt_get_userworkout($id)
{
	global $wpdb;
	$workouttable = $wpdb->prefix."gmgt_assign_workout";
	$workoutdata =$wpdb->get_results("SELECT *FROM ".$workouttable." Where user_id = $id");
	return $workoutdata;
}

//GET USER WORKOUT DATA FUNCTION
function MJ_gmgt_get_workoutdata($id)
{	
	global $wpdb;
	$workouttable = $wpdb->prefix."gmgt_workout_data";
	$workoutdata =$wpdb->get_results("SELECT *FROM ".$workouttable." Where workout_id = $id");
	
	return $workoutdata;
	
}
//SET WORKOUT AARAY FUNCTION
function MJ_gmgt_set_workoutarray($data)
{
	$workout_array=array();
	foreach($data as $row)
	{
			$workout_array[$row->day_name][]= "<span class='col-md-3 col-sm-3 col-xs-12'>".$row->workout_name."</span>   
				<span class='col-md-3 col-sm-3 col-xs-6'>".$row->sets." ".__('Sets','gym_mgt')."</span>
			<span class='col-md-2 col-sm-2 col-xs-6'> ".$row->reps." ".__('Reps','gym_mgt')."</span>
				<span class='col-md-2 col-sm-2 col-xs-6'> ".$row->kg." ".__('KG','gym_mgt')."</span>
			<span class='col-md-2 col-sm-2  col-xs-6'> ".$row->time." ".__('Min','gym_mgt')."</span>";
		
	}
	return $workout_array;
	
}
//CHECK USER WORKOUT
function MJ_gmgt_check_user_workouts($id,$date)
{
	global $wpdb;
	$workouttable = $wpdb->prefix."gmgt_daily_workouts";
	$count_rec =$wpdb->get_var("SELECT COUNT(*) FROM ".$workouttable." Where member_id = $id AND record_date='$date'");
	return $count_rec;
}
//GET USER NUTRISION
function MJ_gmgt_get_user_nutrition($id)
{
	global $wpdb;
	$workouttable = $wpdb->prefix."gmgt_nutrition";
	$workoutdata =$wpdb->get_results("SELECT *FROM ".$workouttable." Where user_id = $id");
	return $workoutdata;
}
//GET NUTRISION DATA FUNCTION
function MJ_gmgt_get_nutritiondata($id)
{
	global $wpdb;
	$workouttable = $wpdb->prefix."gmgt_nutrition_data";
	$workoutdata =$wpdb->get_results("SELECT *FROM ".$workouttable." Where nutrition_id = $id");

	return $workoutdata;

}
//SET NUTRISION AARAY FUNCTION
function MJ_gmgt_set_nutrition_array($data)
{
	$workout_array=array();
	foreach($data as $row)
	{
		$workout_array[$row->day_name][]= "<span class='col-md-3 col-sm-3 col-xs-12 nutrition_time'>".$row->nutrition_time."</span>
			<span class='col-md-9 col-sm-9 col-xs-12'>".$row->nutrition_value." </span>";
		
	}
	return $workout_array;

}
//----------LICENCE KEY REGISTRAION CODE-------------
function MJ_gmgt_verify_pkey()
{
	$api_server = 'license.dasinfomedia.com';
	$fp = fsockopen($api_server,80, $errno, $errstr, 2);
	$location_url = admin_url().'admin.php?page=gmgt_system';
	if (!$fp)
              $server_rerror = 'Down';
        else
              $server_rerror = "up";
	if($server_rerror == "up")
	{
	$domain_name= $_SERVER['SERVER_NAME'];
	$licence_key = $_REQUEST['licence_key'];
	$email = $_REQUEST['enter_email'];
	$data['domain_name']= $domain_name;
	$data['licence_key']= $licence_key;
	$data['enter_email']= $email;
	$result = MJ_gmgt_check_productkey($domain_name,$licence_key,$email);
	if($result == '1')
	{
		$message = 'Please provide correct Envato purchase key.';
			$_SESSION['gmgt_verify'] = '1';
	}
	elseif($result == '2')
	{
		$message = 'This purchase key is already registered with the different domain. If have any issue please contact us at sales@dasinfomedia.com ';
			$_SESSION['gmgt_verify'] = '2';
	}
	elseif($result == '3')
	{
		$message = 'There seems to be some problem please try after sometime or contact us on sales@dasinfomedia.com';
			$_SESSION['gmgt_verify'] = '3';
	}
	elseif($result == '4')
	{
		$message = 'Please provide correct Envato purchase key for this plugin.';
			$_SESSION['gmgt_verify'] = '4';
	}
	else{
		update_option('domain_name',$domain_name,true);
	update_option('licence_key',$licence_key,true);
	update_option('gmgt_setup_email',$email,true);
		$message = 'Success fully register';
			$_SESSION['gmgt_verify'] = '0';
	}
	
	
		$result_array = array('message'=>$message,'gmgt_verify'=>$_SESSION['gmgt_verify'],'location_url'=>$location_url);
		echo json_encode($result_array);
	}
	else
	{
		$message = 'Server is down Please wait some time';
		$_SESSION['gmgt_verify'] = '3';
		$result_array = array('message'=>$message,'gmgt_verify'=>$_SESSION['gmgt_verify'],'location_url'=>$location_url);
	echo json_encode($result_array);
	}
	die();
}
//CHECK SYSTEM SEVER FUNCTION
function MJ_gmgt_check_ourserver()
{
	$api_server = 'license.dasinfomedia.com';
	$fp = @fsockopen($api_server,80, $errno, $errstr, 2);
	$location_url = admin_url().'admin.php?page=gmgt_system';
	if (!$fp)
              return false; /*server down*/
        else
              return true; /*Server up*/
}
//CHECK PRODUCT KEY FUNCTION
function MJ_gmgt_check_productkey($domain_name,$licence_key,$email)
{
	$api_server = 'license.dasinfomedia.com';
	$fp = @fsockopen($api_server,80, $errno, $errstr, 2);
	$location_url = admin_url().'admin.php?page=gmgt_system';
	if (!$fp)
              $server_rerror = 'Down';
        else
              $server_rerror = "up";
	if($server_rerror == "up")
	{
		$url = 'http://license.dasinfomedia.com/index.php';
		$fields = 'result=2&domain='.$domain_name.'&licence_key='.$licence_key.'&email='.$email.'&item_name=gym';
		//open connection
		$ch = curl_init();

		//set the url, number of POST vars, POST data
		curl_setopt($ch,CURLOPT_URL, $url);
		curl_setopt($ch,CURLOPT_POST,1);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
		curl_setopt($ch,CURLOPT_POSTFIELDS, $fields);

		//execute post
		$result = curl_exec($ch);
		
		curl_close($ch);
		return $result;
	}
	else
	{
		return '3';
	}		
}
/* Setup form submit*/
function MJ_gmgt_submit_setupform($data)
{
	$domain_name= $data['domain_name'];
	$licence_key = $data['licence_key'];
	$email = $data['enter_email'];
	
	$result = MJ_gmgt_check_productkey($domain_name,$licence_key,$email);
	if($result == '1')
	{
		$message = 'Please provide correct Envato purchase key.';
			$_SESSION['gmgt_verify'] = '1';
	}
	elseif($result == '2')
	{
		$message = 'This purchase key is already registered with the different domain. If have any issue please contact us at sales@dasinfomedia.com';
			$_SESSION['gmgt_verify'] = '2';
	}
	elseif($result == '3')
	{
		$message = 'There seems to be some problem please try after sometime or contact us on sales@dasinfomedia.com';
			$_SESSION['gmgt_verify'] = '3';
	}
	elseif($result == '4')
	{
		$message = 'Please provide correct Envato purchase key for this plugin.';
			$_SESSION['gmgt_verify'] = '1';
	}
	else
	{
		update_option('domain_name',$domain_name,true);
		update_option('licence_key',$licence_key,true);
		update_option('gmgt_setup_email',$email,true);
		$message = 'Success fully register';
			$_SESSION['gmgt_verify'] = '0';
	}		
	
	$result_array = array('message'=>$message,'gmgt_verify'=>$_SESSION['gmgt_verify']);
	return $result_array;
}
/* check server live */
function MJ_gmgt_chekserver($server_name)
{
	if($server_name == 'localhost')
	{
		return true;	
	}		
}
/*Check is_verify*/
function MJ_gmgt_check_verify_or_not($result)
{	
	
	$server_name = $_SERVER['SERVER_NAME'];
	$current_page = isset($_REQUEST['page'])?$_REQUEST['page']:'';
	$pos = strrpos($current_page, "gmgt_");	
	
	if($pos !== false)			
	{
		if($server_name == 'localhost')
		{
			return true;
		}
		else
		{
			if($result == '0')
			{
				return true;
			}
		}
		return false;
	}
	
}
//GET PAGE FUNCTION
function MJ_gmgt_is_gmgtpage()
{
	$current_page = isset($_REQUEST['page'])?$_REQUEST['page']:'';
	$pos = strrpos($current_page, "gmgt_");	
	
	if($pos !== false)			
	{
		return true;
	}
	return false;
}
//GET TIME PERIOD FOR CLASS FUNCTUION
function MJ_gmgt_timeperiod_for_class_member()
{
	if($_REQUEST['timeperiod']=='limited'){ ?>
		<div class="form-group">
			<label class="col-sm-2 control-label" for="on_of_member"><?php _e('No Of Member','gym_mgt');?></label>
			<div class="col-sm-8">
				<input id="on_of_member" class="form-control text-input" type="number" min="0" onkeypress="if(this.value.length==4) return false;" value="<?php if($edit){ echo $result->on_of_member;}elseif(isset($_POST['on_of_member'])) echo $_POST['on_of_member'];?>" name="on_of_member">
			</div>
		</div>
<?php }
die;
}
//NO OF CLASS IN MEMBRSHIP FUNCTION
function MJ_gmgt_timeperiod_for_class_number()
{
	if($_REQUEST['timeperiod']=='limited'){ ?>
		<div class="form-group">
			<label class="col-sm-2 control-label " for="on_of_classis "><?php _e('No Of Class','gym_mgt');?></label>
			<div class="col-sm-8">
				<input id="on_of_classis" class="form-control text-input phone_validation" type="number" min="0" onkeypress="if(this.value.length==4) return false;" value="<?php if($edit){ echo $result->on_of_classis;}elseif(isset($_POST['on_of_classis'])) echo $_POST['on_of_classis'];?>" name="on_of_classis">
			</div>
		</div>
<?php }
die;
}
//GET CLASS BY MEMER ID
function MJ_gmgt_get_class_id_by_membership()
{ 
	global $wpdb;
	$tbl_gmgt_membershiptype = $wpdb->prefix."gmgt_membershiptype";	
	$membershipdata = $wpdb->get_row("SELECT * FROM $tbl_gmgt_membershiptype WHERE membership_id=".$_REQUEST['membership_id']);
	if($membershipdata->membership_class_limit == 'limited')
	{
		if($_REQUEST['membership_hidden'] == 0)
		{
			$membership_id = $_REQUEST['membership_id'];

			$assigned_membership = get_users(
						array(
							'role' => 'member',
							'meta_query' => array(
							array(
									'key' => 'membership_status',
									'value' =>'Continue',
									'compare' => '='
								),
							array(
									'key' => 'membership_id',
									'value' =>$membership_id,
									'compare' => '='
								),
							)
						));	
			$size_of_membershipdata_array=sizeof($assigned_membership);			
			
			if((string)$size_of_membershipdata_array >= $membershipdata->on_of_member)
			{
				echo '1'; die;
			} 		
		}
		else
		{
			if($_REQUEST['membership_hidden'] != $_REQUEST['membership_id'])
			{
				$membership_id = $_REQUEST['membership_id'];

				$assigned_membership = get_users(
							array(
								'role' => 'member',
								'meta_query' => array(
								array(
										'key' => 'membership_status',
										'value' =>'Continue',
										'compare' => '='
									),
								array(
										'key' => 'membership_id',
										'value' =>$membership_id,
										'compare' => '='
									),
								)
							));	
				$size_of_membershipdata_array=sizeof($assigned_membership);			
				
				if((string)$size_of_membershipdata_array >= $membershipdata->on_of_member)
				{
					echo '1'; die;
				} 		
			}
		}
	}
	$obj_class=new MJ_Gmgtclassschedule;
	$tbl_gmgt_membership_class = $wpdb->prefix."gmgt_membership_class";	
	$table_class = $wpdb->prefix. 'gmgt_class_schedule';
	$retrive_data = $wpdb->get_results("SELECT * FROM $tbl_gmgt_membership_class WHERE membership_id=".$_REQUEST['membership_id']);
	if(!empty($retrive_data))
	{
		foreach($retrive_data as $key=>$value)
		{
			$class_data=$obj_class->MJ_gmgt_get_single_class($value->class_id);
			print '<option value="'.$value->class_id.'">'.MJ_gmgt_get_class_name($value->class_id).' ( '.MJ_gmgt_timeremovecolonbefoream_pm($class_data->start_time).' - '.MJ_gmgt_timeremovecolonbefoream_pm($class_data->end_time).')</option>';
		}
	} 	 
die;
}
//CHECK MEMBERSHIP LIMIT STATUS FUNCTION
function MJ_gmgt_check_membership_limit_status()
{	
	global $wpdb;
	$obj_membership = new MJ_Gmgtmembership();
	$tbl_membership = $wpdb->prefix .'gmgt_membershiptype';
	$result = $wpdb->get_row("SELECT * FROM $tbl_membership WHERE membership_id=".$_REQUEST['membership_id']);
	if($result->membership_class_limit=='limited')
	{		
		print '<input name="no_of_class" type="hidden" value="'.$result->on_of_classis .'">';
	}	
	die;
}
//GET USER ROLE FUNCTION
function MJ_gmgt_GetRoleName($rolename)
{
	$return_role="";
	if($rolename=="staff_member")
		$return_role=__('Staff Members','gym_mgt');
	if($rolename=="accountant")
		$return_role=__('Accountant','gym_mgt');
	if($rolename=="member")
		$return_role=__('Member','gym_mgt');
	if($rolename=="all")
		$return_role=__('All','gym_mgt');
	return $return_role;
}
function MJ_gmgt_check_approve_user($user_id)
{	
	return $userdata = get_user_meta($user_id,'gmgt_hash',true);
}
//GET MEASUREMENT LABLE ARRAY FUNCTION//
function MJ_gmgt_measurement_counts_lable_array($key)
{
	 $measurement_counts=array(	'Height'=>get_option('gmgt_height_unit'),
								'Weight'=>get_option('gmgt_weight_unit'),
								'Chest'=>get_option('gmgt_chest_unit'),
								'Waist'=>get_option('gmgt_waist_unit'),
								'Thigh'=>get_option('gmgt_thigh_unit'),
								'Arms'=>get_option('gmgt_arms_unit'),
								'Fat'=>get_option('gmgt_fat_unit'));
			
	return $measurement_counts[$key];		
}
//GET STAFF MEMBER BY ID FUNCTION
function MJ_gmgt_GetStaffMemberById($id)
{		
		global $wpdb;
		$table_class = $wpdb->prefix. 'gmgt_class_schedule';
		$result = $wpdb->get_results("select *FROM $table_class where class_id= ".$id);
		return $result;
}

// REPLACE STRING FUNTION FOR MAIL TEMPLATE
function MJ_gmgt_string_replacemnet($arr,$message)
{
	$data = str_replace(array_keys($arr),array_values($arr),$message);
	return $data;
}

// REPLACE STRING FUNTION FOR MAIL TEMPLATE
function MJ_gmgt_subject_string_replacemnet($sub_arr,$subject)
{
	$data = str_replace(array_keys($sub_arr),array_values($sub_arr),$subject);
	return $data;
} 
// SEND MAIL FUNCTION FOR NOTIFICATION
function MJ_gmgt_send_mail($emails,$subject,$message)
{	
	$gymname=get_option('gmgt_system_name');
	$headers="";
    $headers.= 'From: '.$gymname.' <noreplay@gmail.com>' . "\r\n";
	$headers.= "MIME-Version: 1.0\r\n";
    $headers.= 'Content-type: text/plain; charset=iso-8859-1' . "\r\n";
	$enable_notofication=get_option('gym_enable_notifications');
	if($enable_notofication=='yes')
	{
		return wp_mail($emails,$subject,$message,$headers);
	}
}  
// SEND MAIL WITH HTML FUNCTION FOR NOTIFICATION
function MJ_gmgt_send_mail_text_html($emails,$subject,$message)
{
    $gymname=get_option('gmgt_system_name');
	$headers="";
    $headers.= 'From: '.$gymname.' <noreplay@gmail.com>' . "\r\n";
	$headers .= "MIME-Version: 1.0\r\n";
	$headers .= "Content-Type: text/html; charset=iso-8859-1\r\n";
	$enable_notofication=get_option('gym_enable_notifications');
	if($enable_notofication=='yes'){
	return wp_mail($emails,$subject,$message,$headers);
	}
} 
//ASSIGNED WORKOUT HTML CONTENT FUNCTION
function MJ_Assign_Workouts_Add_Html_Content($assign_workout_id)
{
	$message='';
	$message.='<html>
          <head>
         <title>A Responsive Email Template</title>
          <meta charset="utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<meta http-equiv="X-UA-Compatible" content="IE=edge" />
	<style type="text/css">
		/* CLIENT-SPECIFIC STYLES */
		body, table, td, a{-webkit-text-size-adjust: 100%; -ms-text-size-adjust: 100%;} /* Prevent WebKit and Windows mobile changing default text sizes */
		table, td{mso-table-lspace: 0pt; mso-table-rspace: 0pt;} /* Remove spacing between tables in Outlook 2007 and up */
		img{-ms-interpolation-mode: bicubic;} /* Allow smoother rendering of resized image in Internet Explorer */

		/* RESET STYLES */
		img{border: 0; height: auto; line-height: 100%; outline: none; text-decoration: none;}
		table{border-collapse: collapse !important;}
		body{height: 100% !important; margin: 0 !important; padding: 0 !important; width: 100% !important;}

		/* iOS BLUE LINKS */
		a[x-apple-data-detectors] {
			color: inherit !important;
			text-decoration: none !important;
			font-size: inherit !important;
			font-family: inherit !important;
			font-weight: inherit !important;
			line-height: inherit !important;
		}

		/* MOBILE STYLES */
		@media screen and (max-width: 525px) {

			/* ALLOWS FOR FLUID TABLES */
			.wrapper {
			  width: 100% !important;
				max-width: 100% !important;
			}

			/* ADJUSTS LAYOUT OF LOGO IMAGE */
			.logo img {
			  margin: 0 auto !important;
			}

			/* USE THESE CLASSES TO HIDE CONTENT ON MOBILE */
			.mobile-hide {
			  display: none !important;
			}

			.img-max {
			  max-width: 100% !important;
			  width: 100% !important;
			  height: auto !important;
			}

			/* FULL-WIDTH TABLES */
			.responsive-table {
			  width: 100% !important;
			}
			

			/* UTILITY CLASSES FOR ADJUSTING PADDING ON MOBILE */
			.padding {
			  padding: 10px 5% 15px 5% !important;
			  
			}

			.padding-meta {
			  padding: 30px 5% 0px 5% !important;
			  text-align: center;
			}

			.padding-copy {
				padding: 10px 5% 10px 5% !important;
			  text-align: center;
			}

			.no-padding {
			  padding: 0 !important;
			}

			.section-padding {
			  padding: 50px 15px 50px 15px !important;
			}

			/* ADJUST BUTTONS ON MOBILE */
			.mobile-button-container {
				margin: 0 auto;
				width: 100% !important;
			}

			.mobile-button {
				padding: 15px !important;
				border: 0 !important;
				font-size: 16px !important;
				display: block !important;
			}

		}

		/* ANDROID CENTER FIX */
		div[style*="margin: 16px 0;"] { margin: 0 !important; }
	</style>
	<!--[if gte mso 12]>
	<style type="text/css">
	.mso-right {
		padding-left: 20px;
	}
	</style>
	<![endif]-->
	</head>
	<body style="margin: 0 !important; padding: 0 !important;">';
		          
						global $wpdb;
						$table_workout = $wpdb->prefix. 'gmgt_assign_workout';
						$result = $wpdb->get_row("SELECT * FROM $table_workout where workout_id=".$assign_workout_id);
						
						$workoutid=$result->workout_id;
						
						$workouttable = $wpdb->prefix."gmgt_workout_data";
						$all_logdata =$wpdb->get_results("SELECT *FROM ".$workouttable." Where workout_id = $workoutid");
						
						$arranged_workout=MJ_gmgt_set_workoutarray($all_logdata); 
						$message.=__('Start From ','gym_mgt');
					 $message.='<span style="color: #f25656;
             font-style: italic;" >'.MJ_gmgt_getdate_in_input_box($result->start_date).'</span>';
					$message.=__(' To ','gym_mgt');
			  $message.='<span style="color: #f25656;
              font-style: italic;">'.MJ_gmgt_getdate_in_input_box($result->end_date).'</span> ';
					
					$message.='<table style="border-collapse: collapse; width: 100%; float: left;">
					  <thead>
						<tr>
							<th style="padding: 8px; text-align: left; border-bottom: 2px solid #ddd;">'. __("Day Name","gym_mgt") .'</th>
							<th  style="padding: 3px; text-align: left; border-bottom: 2px solid #ddd;"> '.__("Activity","gym_mgt").'</th>
							<th  style="padding: 3px; text-align: left; border-bottom: 2px solid #ddd;">'. __("Sets","gym_mgt").'</th>
							<th  style="padding: 3px; text-align: left; border-bottom: 2px solid #ddd;">'. __("Reps","gym_mgt").'</th>
							<th  style="padding: 3px; text-align: left; border-bottom: 2px solid #ddd;">'.__("KG","gym_mgt").'</th>
							<th  style="padding: 3px; text-align: left; border-bottom: 2px solid #ddd;">'.__("Rest Time","gym_mgt").'</th>
						</tr>
					  </thead> <tbody>  ';
							  
				foreach($arranged_workout as $key=>$rowdata)
				{ 
					
					$i=count($rowdata)+1;
					$message.='<tr>
                      <td rowspan="'.$i.'" style="padding: 8px; text-align: left; border-bottom: 1px solid #ddd; "> '.$key.'</td>';
					 
						foreach($rowdata as $row)
						{						
							$asd = explode('<span',$row);
	 								$message.='<tr>
									<td style="padding: 8px;border-bottom: 1px solid #ddd;"><span'.$asd[1].'</td>
									<td style="padding: 8px;border-bottom: 1px solid #ddd;"><span'.$asd[2].'</td>
									<td style="padding: 8px;border-bottom: 1px solid #ddd;"><span'.$asd[3].'</td>
									<td style="padding: 8px;border-bottom: 1px solid #ddd;"><span'.$asd[4].'</td>
									<td style="padding: 8px;border-bottom: 1px solid #ddd;"><span'.$asd[5].'</td></tr>';
									
						} 
						 $message.='</tr>';						
				
			    }
				$message.='
					</tbody>
	 				</table>';
					
		return $message;
}
//Assign Nutrition CONTENT MAIL FUNCTION
function MJ_asign_nutristion_content_send_mail($id)
{
		 $message='';
		 $message.='<html>
         <head>
         <title>A Responsive Email Template</title>
         <meta charset="utf-8">
		<meta name="viewport" content="width=device-width, initial-scale=1">
	   <meta http-equiv="X-UA-Compatible" content="IE=edge" />
	   <style type="text/css">
		/* CLIENT-SPECIFIC STYLES */
		body, table, td, a{-webkit-text-size-adjust: 100%; -ms-text-size-adjust: 100%;} /* Prevent WebKit and Windows mobile changing default text sizes */
		table, td{mso-table-lspace: 0pt; mso-table-rspace: 0pt;} /* Remove spacing between tables in Outlook 2007 and up */
		img{-ms-interpolation-mode: bicubic;} /* Allow smoother rendering of resized image in Internet Explorer */

		
		.panel-title{
		font-size: 14px;
		float: left;
		margin: 0;
		padding: 0;
		font-weight: 600;
	}

		/* RESET STYLES */
		img{border: 0; height: auto; line-height: 100%; outline: none; text-decoration: none;}
		table{border-collapse: collapse !important;}
		body{height: 100% !important; margin: 0 !important; padding: 0 !important; width: 100% !important;}

		/* iOS BLUE LINKS */
		a[x-apple-data-detectors] {
			color: inherit !important;
			text-decoration: none !important;
			font-size: inherit !important;
			font-family: inherit !important;
			font-weight: inherit !important;
			line-height: inherit !important;
		}

		/* MOBILE STYLES */
		@media screen and (max-width: 525px) {

			/* ALLOWS FOR FLUID TABLES */
			.wrapper {
			  width: 100% !important;
				max-width: 100% !important;
			}

			/* ADJUSTS LAYOUT OF LOGO IMAGE */
			.logo img {
			  margin: 0 auto !important;
			}

			/* USE THESE CLASSES TO HIDE CONTENT ON MOBILE */
			.mobile-hide {
			  display: none !important;
			}

			.img-max {
			  max-width: 100% !important;
			  width: 100% !important;
			  height: auto !important;
			}

			/* FULL-WIDTH TABLES */
			.responsive-table {
			  width: 100% !important;
			}

			/* UTILITY CLASSES FOR ADJUSTING PADDING ON MOBILE */
			.padding {
			  padding: 10px 5% 15px 5% !important;
			}

			.padding-meta {
			  padding: 30px 5% 0px 5% !important;
			  text-align: center;
			}

			.padding-copy {
				padding: 10px 5% 10px 5% !important;
			  text-align: center;
			}

			.no-padding {
			  padding: 0 !important;
			}

			.section-padding {
			  padding: 50px 15px 50px 15px !important;
			}

			/* ADJUST BUTTONS ON MOBILE */
			.mobile-button-container {
				margin: 0 auto;
				width: 100% !important;
			}

			.mobile-button {
				padding: 15px !important;
				border: 0 !important;
				font-size: 16px !important;
				display: block !important;
			}

		}
		/* ANDROID CENTER FIX */
		div[style*="margin: 16px 0;"] { margin: 0 !important; }
		</style>
		<!--[if gte mso 12]>
		<style type="text/css">
		.mso-right {
			padding-left: 20px;
		}
		</style>
		<![endif]-->
		</head>
	    <body style="margin: 0 !important; padding: 0 !important;">';
		$obj_nutrition=new MJ_Gmgtnutrition;
		
		$result=$obj_nutrition->MJ_gmgt_get_single_nutrition($id);
		$all_logdata=MJ_gmgt_get_nutritiondata($result->id);
		$arranged_workout=MJ_gmgt_set_nutrition_array($all_logdata);
	   $message.=' Start From <span style="color: #f25656;
	   font-style: italic;" >'.MJ_gmgt_getdate_in_input_box($result->start_date).'</span> To <span style="color: #f25656;
	   font-style: italic;">'.MJ_gmgt_getdate_in_input_box($result->expire_date).'</span> ';
        
			if(!empty($arranged_workout))
			{
			$message.='<table style="border-collapse: collapse; width: 100%; float: left;">
						  <thead>
							<tr>
								<th style="padding: 8px; text-align: left; border-bottom: 2px solid #ddd;">'. __("Day Name","gym_mgt") .'</th>
								<th  style="padding: 8px; text-align: left; border-bottom: 2px solid #ddd;">'. __("Time","gym_mgt").'</th>
								<th  style="padding: 8px; text-align: left; border-bottom: 2px solid #ddd;">'. __("Description","gym_mgt").'</th>
							</tr>
						  </thead> <tbody> ';
			foreach($arranged_workout as $key=>$rowdata){ 
		   $message.='<tr>
		  <td rowspan=4 style="padding: 8px; text-align: left; border-bottom: 1px solid #ddd; "> '.$key.'</td>
			';
				 foreach($rowdata as $row)
				 {
					  $asd = explode('<span',$row);					  
						$message.='<tr>
						<td rowspan=1 style="padding: 8px;border-bottom: 1px solid #ddd;"><span'.$asd[1].'</td>
						<td rowspan=1 style="padding: 8px;border-bottom: 1px solid #ddd;"><span'.$asd[2].'</td></tr>';
				 }
				$message.=' </tr>';
				 }
		$message.='
		</tbody>
		</table>';
			}
	$message.='</body>
</html>';		
	return $message;
}
//SUBMIT WORKOUT HTML CONTENT FUNCTION
function MJ_submit_workout_html_content($workoutmember_id,$tcurrent_date)
{
	     $message='';
		 $message.='<html>
          <head>
         <title>A Responsive Email Template</title>
          <meta charset="utf-8">
		   <meta name="viewport" content="width=device-width, initial-scale=1">
		   <meta http-equiv="X-UA-Compatible" content="IE=edge" />
		   <style type="text/css">

			*   {
			/* CLIENT-SPECIFIC STYLES */
			body, table, td, a{-webkit-text-size-adjust: 100%; -ms-text-size-adjust: 100%;} /* Prevent WebKit and Windows mobile changing default text sizes */
			table, td{mso-table-lspace: 0pt; mso-table-rspace: 0pt;} /* Remove spacing between tables in Outlook 2007 and up */
			img{-ms-interpolation-mode: bicubic;} /* Allow smoother rendering of resized image in Internet Explorer */

			/* RESET STYLES */
			img{border: 0; height: auto; line-height: 100%; outline: none; text-decoration: none;}
			table{border-collapse: collapse !important;}
			body{height: 100% !important; margin: 0 !important; padding: 0 !important; width: 100% !important;}
			
			/* iOS BLUE LINKS */
			a[x-apple-data-detectors] 
			{
				color: inherit !important;
				text-decoration: none !important;
				font-size: inherit !important;
				font-family: inherit !important;
				font-weight: inherit !important;
				line-height: inherit !important;
			}
				
			/* MOBILE STYLES */
			@media screen and (max-width: 525px) 
			{				
				.activity_width
				{
					width:80%;
				}
				/* ALLOWS FOR FLUID TABLES */
				.wrapper {
				  width: 100% !important;
					max-width: 100% !important;
				}

				/* ADJUSTS LAYOUT OF LOGO IMAGE */
				.logo img {
				  margin: 0 auto !important;
				}

				/* USE THESE CLASSES TO HIDE CONTENT ON MOBILE */
				.mobile-hide {
				  display: none !important;
				}

				.img-max {
				  max-width: 100% !important;
				  width: 100% !important;
				  height: auto !important;
				}

				/* FULL-WIDTH TABLES */
				.responsive-table {
				  width: 100% !important;
				}

				/* UTILITY CLASSES FOR ADJUSTING PADDING ON MOBILE */
				.padding {
				  padding: 10px 5% 15px 5% !important;
				}

				.padding-meta {
				  padding: 30px 5% 0px 5% !important;
				  text-align: center;
				}

				.padding-copy {
					padding: 10px 5% 10px 5% !important;
				  text-align: center;
				}

				.no-padding {
				  padding: 0 !important;
				}

				.section-padding {
				  padding: 50px 15px 50px 15px !important;
				}

				/* ADJUST BUTTONS ON MOBILE */
				.mobile-button-container {
					margin: 0 auto;
					width: 100% !important;
				}

				.mobile-button {
					padding: 15px !important;
					border: 0 !important;
					font-size: 16px !important;
					display: block !important;
				}
			}

				/* ANDROID CENTER FIX */
				div[style*="margin: 16px 0;"] { margin: 0 !important; }
			</style>
			<!--[if gte mso 12]>
			<style type="text/css">
			.mso-right {
				padding-left: 20px;
			}
			.activity_width
			{
				width:20%;
			}
			</style>
			<![endif]-->
			</head>
		    <body style="margin: 0 !important; padding: 0 !important;">';
		           
		    $obj_workout=new MJ_Gmgtworkout;
		    $message='';
			$today_workouts=$obj_workout->MJ_gmgt_get_member_today_workouts($workoutmember_id,$tcurrent_date); 
			
				foreach($today_workouts as $value)
				{
					$workoutid=$value->user_workout_id;
					$activity_name=$value->workout_name;
					$workflow_category=$obj_workout->MJ_gmgt_get_user_workouts($workoutid,$activity_name);
					if($workflow_category->sets!='0')
						{
							$sets_progress=$value->sets*100/$workflow_category->sets;
						}
						else
						{
							$sets_progress=100;
						}
						if($workflow_category->reps!='0')
						{							
							$reps_progress=$value->reps*100/$workflow_category->reps;
						}
						else
						{
							$reps_progress=100;
						}
						if($workflow_category->kg!='0')
						{
							$kg_progress=$value->kg*100/$workflow_category->kg;
						}
						else
						{
							$kg_progress=100;
						}
						if($workflow_category->time!='0')
						{
							$rest_time_progress=$value->rest_time*100/$workflow_category->time;
						}
						else
						{
							$rest_time_progress=100;
						}
					
				       $message.='<table style="border-collapse: collapse; margin-bottom: 20px; width: 100%;">
						<thead style="float: left;width: 100%;">
							<tr>
							<h2>
								<th style="float: left;font-weight: bold;font-size: 22px;">'.$value->workout_name .'</th></h2>
							</tr>
						</thead>
						<tbody style="float: left;width: 100%;">';
	
					$message.='<tr style="margin-top: 10px; float: left;width: 100%;margin-left: 10px;">
						<td style="float: left;position: relative;z-index: 1 !important;font-size: 20px;margin-top: 40px;padding-left: 13px;"><div style="margin-right: 10px;"><h2 style="margin: 0px;background-color: #02967d;border-radius: 50%;color: #ffffff;height: 40px;width: 40px;padding: 5px;text-align: center;">'.'1'.'</h2></div></td>
						<td class="activity_width" style="background-color: #1db198; padding: 8px; border-radius: 15px;  text-align: left; float:left;width:40%; position: relative; color: #fff;left: -39px;margin-bottom: 10px;"><p style="font-size: 20px;font-weight: 500; margin-left: 45px;margin-bottom: 5px;">'.__('Sets','gym_mgt').'</p><div style="background: #676767;margin-left: 45px;"><div style="height: 3px;background-color: #fff;width:'.$sets_progress.'%;"></div></div><p style="color: #fff;margin-left: 45px;font-size: 16px;margin-bottom: 0px;">'. $value->sets.' '.__('Out Of','gym_mgt').' '.$workflow_category->sets .' '.__('Sets','gym_mgt').'</p></td>
						
						<td style="float: left;position: relative;z-index: 1 !important;font-size: 20px;margin-top: 40px;padding-left: 13px;"><div style="margin-right: 10px;"><h2 style="margin: 0px;background-color: #02967d;border-radius: 50%;color: #ffffff;height: 40px;width: 40px;padding: 5px;text-align: center;">'.'2'.'</h2></div></td>
						<td class="activity_width" style="background-color: #1db198; padding: 8px; border-radius: 15px;  text-align: left; float:left; width:40%;position: relative; color: #fff;left: -39px;margin-bottom: 10px;"><p style="font-size: 20px;font-weight: 500; margin-left: 45px;margin-bottom: 5px;">'.__('Reps','gym_mgt').'</p><div style="background: #676767;margin-left: 45px;"><div style="height: 3px;background-color: #fff;width:'.$reps_progress.'%;"></div></div><p style="color: #fff;margin-left: 45px;font-size: 16px;margin-bottom: 0px;">'. $value->reps.' '.__('Out Of','gym_mgt').' '.$workflow_category->reps .' '.__('Reps','gym_mgt').'</p></td>
						
						<td style="float: left;position: relative;z-index: 1 !important;font-size: 20px;margin-top: 40px;padding-left: 13px;"><div style="margin-right: 10px;"><h2 style="margin: 0px;background-color: #02967d;border-radius: 50%;color: #ffffff;height: 40px;width: 40px;padding: 5px;text-align: center;">'.'3'.'</h2></div></td>
						<td class="activity_width" style="background-color: #1db198; padding: 8px; border-radius: 15px;  text-align: left;float:left;width:40%; position: relative; color: #fff;left: -39px;margin-bottom: 10px;"><p style="font-size: 20px;font-weight: 500; margin-left: 45px;margin-bottom: 5px;">'.__('Kg','gym_mgt').'</p><div style="background: #676767;margin-left: 45px;"><div style="height: 3px;background-color: #fff;width:'.$kg_progress.'%;"></div></div><p style="color: #fff;margin-left: 45px;font-size: 16px;margin-bottom: 0px;">'. $value->kg.' '.__('Out Of','gym_mgt').' '.$workflow_category->kg .' '.__('Kg','gym_mgt').'</p></td>
						
						<td style="float: left;position: relative;z-index: 1 !important;font-size: 20px;margin-top: 40px;padding-left: 13px;"><div style="margin-right: 10px;"><h2 style="margin: 0px;background-color: #02967d;border-radius: 50%;color: #ffffff;height: 40px;width: 40px;padding: 5px;text-align: center;">'.'4'.'</h2></div></td>
						<td class="activity_width" style="background-color: #1db198; padding: 8px; border-radius: 15px;  text-align: left; float:left;width:40%; position: relative; color: #fff;left: -39px;margin-bottom: 10px;"><p style="font-size: 20px;font-weight: 500; margin-left: 45px;margin-bottom: 5px;">'.__('Rest Time','gym_mgt').'</p><div style="background: #676767;margin-left: 45px;"><div style="height: 3px;background-color: #fff;width:'.$rest_time_progress.'%;"></div></div><p style="color: #fff;margin-left: 45px;font-size: 16px;margin-bottom: 0px;">'. $value->rest_time.' '.__('Out Of','gym_mgt').' '.$workflow_category->time .' '.__('Rest Time','gym_mgt').'</p></td>
						</tr>';					
										
					$message.='</tbody>
					</table>';
				} 				
		return $message;			 
}
//this function use in image validation in add time
function MJ_gmgt_check_valid_extension($filename)
{
	$flag = 2; 
	if($filename != '')
	{
		 $flag = 0;
		 $ext = pathinfo($filename, PATHINFO_EXTENSION);
		 $valid_extension = ['gif','png','jpg','jpeg','bmp',""];
		if(in_array($ext,$valid_extension) )
		{
		  $flag = 1;
		}
	}
      return $flag;
}			
//This function use in document validation in add time
function MJ_gmgt_check_valid_file_extension($filename)
{
	$flag = 2; 
	if($filename != '')
	{
		$flag = 0;
		$ext = pathinfo($filename, PATHINFO_EXTENSION);
		$valid_extension = ['pdf',""];
		if(in_array($ext,$valid_extension) )
		{
			$flag = 1;
		}
	}
	return $flag;
}
//count total in tax module
function MJ_gmgt_count_store_total()
{ 
	$total_amount_withtax=0;
	$discount=$_POST['discount_amount'];
	$quantity=$_POST['quantity'];
	$Product=$_POST['Product'];
	$tax=$_POST['tax'];
	$obj_product=new MJ_Gmgtproduct();
	 $product_data=$obj_product->MJ_gmgt_get_single_product($Product);
	 $price=$product_data->price;
	 $total_price=(int)$price * (int)$quantity;
	 $total_amount_minusdiscount=$total_price - $discount;
	 $total_tax=$total_amount_minusdiscount * $tax/100;
	 $total_amount_withtax=$total_amount_minusdiscount + $total_tax;
	echo $total_amount_withtax;
	die();
} 
//GET DATE FORMATE FOR DATABASE FUNCTION
function MJ_gmgt_get_format_for_db($date)
{
	if(!empty($date))
	{	
		$date = trim($date);
		$new_date = DateTime::createFromFormat(MJ_gmgt_get_phpdateformat(get_option('gmgt_datepicker_format')), $date);
		$new_date=$new_date->format('Y-m-d');
		return $new_date;
	}
	else
	{
		$new_date ='';
		return $new_date;
	}
}
//userwise access Right array
function MJ_gmgt_userwise_access_right()
{
	$curr_user_id=get_current_user_id();
	$obj_gym=new MJ_Gym_management($curr_user_id);

	$role = $obj_gym->role;
		
	if($role=='member')
	{ 
		$menu = get_option( 'gmgt_access_right_member');
	
	}
	elseif($role=='staff_member')
	{
		$menu = get_option( 'gmgt_access_right_staff_member');
	}
	elseif($role=='accountant')
	{
		$menu = get_option( 'gmgt_access_right_accountant');
	}
	return $menu;
}
//page wise access right
function MJ_gmgt_get_userrole_wise_page_access_right_array()
{
	$curr_user_id=get_current_user_id();
	$obj_gym=new MJ_Gym_management($curr_user_id);

	$role = $obj_gym->role;
		
	if($role=='member')
	{ 
		$menu = get_option( 'gmgt_access_right_member');
	
	}
	elseif($role=='staff_member')
	{
		$menu = get_option( 'gmgt_access_right_staff_member');
	}
	elseif($role=='accountant')
	{
		$menu = get_option( 'gmgt_access_right_accountant');
	}
	
	foreach ( $menu as $key1=>$value1 ) 
	{									
		foreach ( $value1 as $key=>$value ) 
		{				
			if ($_REQUEST ['page'] == $value['page_link'])
			{				
				return $value;
			}
		}
	}	
}
//manually page wise access right
function MJ_gmgt_get_userrole_wise_manually_page_access_right_array($page)
{
	$curr_user_id=get_current_user_id();
	$obj_gym=new MJ_Gym_management($curr_user_id);

	$role = $obj_gym->role;
		
	if($role=='member')
	{ 
		$menu = get_option( 'gmgt_access_right_member');
	
	}
	elseif($role=='staff_member')
	{
		$menu = get_option( 'gmgt_access_right_staff_member');
	}
	elseif($role=='accountant')
	{
		$menu = get_option( 'gmgt_access_right_accountant');
	}
	
	foreach ( $menu as $key1=>$value1 ) 
	{									
		foreach ( $value1 as $key=>$value ) 
		{				
			if ($page == $value['page_link'])
			{				
				return $value;
			}
		}
	}	
}
//dashboard page access right
function MJ_gmgt_page_access_rolewise_accessright_dashboard($page)
{
	$curr_user_id=get_current_user_id();
	$obj_gym=new MJ_Gym_management($curr_user_id);

	$role = $obj_gym->role;
		
	if($role=='member')
	{ 
		$menu = get_option( 'gmgt_access_right_member');
	
	}
	elseif($role=='staff_member')
	{
		$menu = get_option( 'gmgt_access_right_staff_member');
	}
	elseif($role=='accountant')
	{
		$menu = get_option( 'gmgt_access_right_accountant');
	}	
	
	foreach ( $menu as $key1=>$value1 ) 
	{									
		foreach ( $value1 as $key=>$value ) 
		{	
			if ($page == $value['page_link'])
			{				
				if($value['view']=='0')
				{			
					$flage=0;
				}
				else
				{
					$flage=1;
				}
			}
		}
	}	
	
	return $flage;
} 
//dashboard  count total member by access right
function MJ_gmgt_count_total_member_dashboard_by_access_right($page)
{
	$curr_user_id=get_current_user_id();
	 
	 
	$obj_gym=new MJ_Gym_management($curr_user_id);

	$role = $obj_gym->role;
	 
 
	if($role=='member')
	{ 
		$menu = get_option( 'gmgt_access_right_member');
		
	}
	elseif($role=='staff_member')
	{
		$menu = get_option( 'gmgt_access_right_staff_member');
	}
	elseif($role=='accountant')
	{
		$menu = get_option( 'gmgt_access_right_accountant');
	}	
	
	foreach ( $menu as $key1=>$value1 ) 
	{		
		/* echo '<pre>';
		print_r($value1);
		echo '</pre>';
		die; */
		foreach ( $value1 as $key=>$value ) 
		{	
		/* echo '<pre>';
		print_r($value);
		echo '</pre>';
		die;  */
			if ($page == $value['page_link'])
			{		
				if($obj_gym->role == 'member')
				{	
					if($value['own_data']=='1')
					{
						$user_id=get_current_user_id();
						$membersdata=array();
						$membersdata[] = get_userdata($user_id);			
					}
					else
					{
						$membersdata =get_users( array('role' => 'member'));
					}	
				}
				elseif($obj_gym->role == 'staff_member')
				{
					if($value['own_data']=='1')
					{
						$membersdata = get_users(array('meta_key' => 'staff_id', 'meta_value' =>$curr_user_id ,'role'=>'member'));		
					}
					else
					{
						$membersdata =get_users( array('role' => 'member'));
					}
				}
				else
				{
					$membersdata =get_users( array('role' => 'member'));
				}
				$membersdata_count= count($membersdata);
				return $membersdata_count;
			}
		}
	}	
}
//dashboard count total staff member by access right
function MJ_gmgt_count_total_staff_member_dashboard_by_access_right($page)
{
	$curr_user_id=get_current_user_id();
	$obj_gym=new MJ_Gym_management($curr_user_id);

	$role = $obj_gym->role;
		
	if($role=='member')
	{ 
		$menu = get_option( 'gmgt_access_right_member');	
	}
	elseif($role=='staff_member')
	{
		$menu = get_option( 'gmgt_access_right_staff_member');
	}
	elseif($role=='accountant')
	{
		$menu = get_option( 'gmgt_access_right_accountant');
	}	
	
	foreach ( $menu as $key1=>$value1 ) 
	{									
		foreach ( $value1 as $key=>$value ) 
		{	
			if ($page == $value['page_link'])
			{		
				if($obj_gym->role == 'member')
				{	
					if($value['own_data']=='1')
					{
						
						$user_id=get_current_user_id();
						$staff_id = get_user_meta( $user_id,'staff_id', true ); 
						$staffdata=array();
						$staffdata[] = get_userdata($staff_id);
						
					}
					else
					{
						
						$get_staff = array('role' => 'Staff_member');
						$staffdata=get_users($get_staff);
					}	
				}
				elseif($obj_gym->role == 'staff_member')
				{
					if($value['own_data']=='1')
					{
						$staff_id=get_current_user_id();
						
						$staffdata=array();
						$staffdata[] = get_userdata($staff_id);
						
					}
					else
					{
						$get_staff = array('role' => 'Staff_member');
						$staffdata=get_users($get_staff);
					}
				}	
				else
				{
					$get_staff = array('role' => 'Staff_member');
					$staffdata=get_users($get_staff);
				}
				
				$staffdata_count= count($staffdata);
				return $staffdata_count;
			}
		}
	}	
}
//dashboard count total GROUP by access right
function MJ_gmgt_count_total_group_dashboard_by_access_right($page)
{
	$obj_group=new MJ_Gmgtgroup;
	$curr_user_id=get_current_user_id();
	$obj_gym=new MJ_Gym_management($curr_user_id);

	$role = $obj_gym->role;
		
	if($role=='member')
	{ 
		$menu = get_option( 'gmgt_access_right_member');	
	}
	elseif($role=='staff_member')
	{
		$menu = get_option( 'gmgt_access_right_staff_member');
	}
	elseif($role=='accountant')
	{
		$menu = get_option( 'gmgt_access_right_accountant');
	}	
	
	foreach ( $menu as $key1=>$value1 ) 
	{									
		foreach ( $value1 as $key=>$value ) 
		{	
			if ($page == $value['page_link'])
			{		
				if($obj_gym->role == 'member')
				{	
					if($value['own_data']=='1')
					{
						$user_id=get_current_user_id();
						$groupdata=$obj_group->MJ_gmgt_get_member_all_groups($user_id);			
					}
					else
					{
						$groupdata=$obj_group->MJ_gmgt_get_all_groups();
					}	
				}
				elseif($obj_gym->role == 'staff_member' || $obj_gym->role == 'accountant')
				{
					if($value['own_data']=='1')
					{
						$user_id=get_current_user_id();							
						$groupdata=$obj_group->MJ_gmgt_get_all_groups_by_created_by($user_id);			
					}
					else
					{
						$groupdata=$obj_group->MJ_gmgt_get_all_groups();
					}
				}
				
				$groupdata_count= count($groupdata);
				return $groupdata_count;
			}
		}
	}	
}
//dashboard count total MEMBERRSHIP by access right
function MJ_gmgt_count_total_membership_dashboard_by_access_right($page)
{
	$obj_membership=new MJ_Gmgtmembership;
	$curr_user_id=get_current_user_id();
	$obj_gym=new MJ_Gym_management($curr_user_id);

	$role = $obj_gym->role;
		
	if($role=='member')
	{ 
		$menu = get_option( 'gmgt_access_right_member');	
	}
	elseif($role=='staff_member')
	{
		$menu = get_option( 'gmgt_access_right_staff_member');
	}
	elseif($role=='accountant')
	{
		$menu = get_option( 'gmgt_access_right_accountant');
	}	
	
	foreach ( $menu as $key1=>$value1 ) 
	{									
		foreach ( $value1 as $key=>$value ) 
		{	
			if ($page == $value['page_link'])
			{		
				if($obj_gym->role == 'member')
				{	
					if($value['own_data']=='1')
					{
						$user_id=get_current_user_id();
						$membership_id = get_user_meta( $user_id,'membership_id', true ); 
						$membershipdata=$obj_membership->MJ_gmgt_get_member_own_membership($membership_id);		
						 
					}
					else
					{
						$membershipdata=$obj_membership->MJ_gmgt_get_all_membership();
					}	
				}
				elseif($obj_gym->role == 'staff_member' || $obj_gym->role == 'accountant')
				{
					if($value['own_data']=='1')
					{
						$user_id=get_current_user_id();							
						$membershipdata=$obj_membership->MJ_gmgt_get_membership_by_created_by($user_id);			
					}
					else
					{
						$membershipdata=$obj_membership->MJ_gmgt_get_all_membership();
					}
				}
				
				$membershipdata_count= count($membershipdata);
				return $membershipdata_count;
			}
		}
	}	
}
//dashboard count total class by access right
function MJ_gmgt_count_total_class_dashboard_by_access_right($page)
{
	$obj_class=new MJ_Gmgtclassschedule;
	$curr_user_id=get_current_user_id();
	$obj_gym=new MJ_Gym_management($curr_user_id);

	$role = $obj_gym->role;
		
	if($role=='member')
	{ 
		$menu = get_option( 'gmgt_access_right_member');	
	}
	elseif($role=='staff_member')
	{
		$menu = get_option( 'gmgt_access_right_staff_member');
	}
	elseif($role=='accountant')
	{
		$menu = get_option( 'gmgt_access_right_accountant');
	}	
	
	foreach ( $menu as $key1=>$value1 ) 
	{									
		foreach ( $value1 as $key=>$value ) 
		{	
			if ($page == $value['page_link'])
			{		
				//GET CLASS LIST DATA
				if($obj_gym->role == 'staff_member')
				{
					if($value['own_data']=='1')
					{
						$user_id=get_current_user_id();							
						$classdata=$obj_class->MJ_gmgt_get_all_classes_by_staffmember($user_id);	
					}
					else
					{
						$classdata=$obj_class->MJ_gmgt_get_all_classes();
					}
				}
				elseif($obj_gym->role == 'member')
				{		
					if($value['own_data']=='1')
					{
						$cur_user_class_id = array();
						$curr_user_id=get_current_user_id();
						$cur_user_class_id = MJ_gmgt_get_current_user_classis($curr_user_id);
						$classdata=$obj_class->MJ_gmgt_get_all_classes_by_member($cur_user_class_id);	
					}
					else
					{
						$classdata=$obj_class->MJ_gmgt_get_all_classes();
					}
				}
				else
				{		
					if($value['own_data']=='1')
					{
						$user_id=get_current_user_id();							
						$classdata=$obj_class->MJ_gmgt_get_all_classes_by_class_created_id($user_id);	
					}
					else
					{
						$classdata=$obj_class->MJ_gmgt_get_all_classes();
					}
				}
			 
				$classdata_count= count($classdata);
				return $classdata_count;
			}
		}
	}	
}
//dashboard count total reservation by access right
function MJ_gmgt_count_total_reservation_dashboard_by_access_right($page)
{
	$obj_reservation=new MJ_Gmgtreservation;
	$curr_user_id=get_current_user_id();
	$obj_gym=new MJ_Gym_management($curr_user_id);

	$role = $obj_gym->role;
	/* var_dump($role);
	die; */	
	if($role=='member')
	{ 
		$menu = get_option( 'gmgt_access_right_member');	
	}
	elseif($role=='staff_member')
	{
		$menu = get_option( 'gmgt_access_right_staff_member');
	}
	elseif($role=='accountant')
	{
		$menu = get_option( 'gmgt_access_right_accountant');
	}	
	/*   echo '<pre>';
		print_r($menu);
		echo '</pre>';
		die;   */
	foreach ( $menu as $key1=>$value1 ) 
	{									
		foreach ( $value1 as $key=>$value ) 
		{	
			if ($page == $value['page_link'])
			{		
				//GET CLASS LIST DATA
				if($obj_gym->role == 'staff_member')
				{
					if($value['own_data']=='1')
					{
						$reservationdata=$obj_reservation->MJ_gmgt_get_reservation_by_created_by();
					}
					else
					{
						$reservationdata=$obj_reservation->MJ_gmgt_get_all_reservation();
					}	
				}
				elseif($obj_gym->role == 'member')
				{		
					if($value['own_data']=='1')
					{
						$reservationdata=$obj_reservation->MJ_gmgt_get_reservation_by_created_by();
					}
					else
					{
						$reservationdata=$obj_reservation->MJ_gmgt_get_all_reservation();
					}	
				}
				else
				{		
					if($value['own_data']=='1')
					{
						$reservationdata=$obj_reservation->MJ_gmgt_get_reservation_by_created_by();
					}
					else
					{
						$reservationdata=$obj_reservation->MJ_gmgt_get_all_reservation();
					}	
				}
			 
				$reservationdata_count= count($reservationdata);
				return $reservationdata_count;
			}
		}
	}	
}
//dashboard count total product by access right
function MJ_gmgt_count_total_product_dashboard_by_access_right($page)
{
	$obj_product=new MJ_Gmgtproduct;
	$curr_user_id=get_current_user_id();
	$obj_gym=new MJ_Gym_management($curr_user_id);

	$role = $obj_gym->role;
	/* var_dump($role);
	die; */	
	if($role=='member')
	{ 
		$menu = get_option( 'gmgt_access_right_member');	
	}
	elseif($role=='staff_member')
	{
		$menu = get_option( 'gmgt_access_right_staff_member');
	}
	elseif($role=='accountant')
	{
		$menu = get_option( 'gmgt_access_right_accountant');
	}	
	/*   echo '<pre>';
		print_r($menu);
		echo '</pre>';
		die;   */
	foreach ( $menu as $key1=>$value1 ) 
	{									
		foreach ( $value1 as $key=>$value ) 
		{	
			if ($page == $value['page_link'])
			{		
				//GET CLASS LIST DATA
				if($obj_gym->role == 'staff_member')
				{
					if($value['own_data']=='1')
					{
						$user_id=get_current_user_id();
						$productdata=$obj_product->MJ_gmgt_get_all_product_by_created_by($user_id);
					}
					else
					{
						$productdata=$obj_product->MJ_gmgt_get_all_product();
					}	
				}
				elseif($obj_gym->role == 'member')
				{		
					if($value['own_data']=='1')
					{
						$user_id=get_current_user_id();
						$productdata=$obj_product->MJ_gmgt_get_all_product_by_created_by($user_id);
					}
					else
					{
						$productdata=$obj_product->MJ_gmgt_get_all_product();
					}
				}
				else
				{		
					if($value['own_data']=='1')
						{
							$user_id=get_current_user_id();
							$productdata=$obj_product->MJ_gmgt_get_all_product_by_created_by($user_id);
						}
						else
						{
							$productdata=$obj_product->MJ_gmgt_get_all_product();
						}	
				}
			 
				$productdata_count= count($productdata);
				return $productdata_count;
			}
		}
	}	
}
//dashboard count total product by access right
function MJ_gmgt_count_total_notice_dashboard_by_access_right($page)
{
	$obj_notice=new MJ_Gmgtnotice;
	$curr_user_id=get_current_user_id();
	$obj_gym=new MJ_Gym_management($curr_user_id);

	$role = $obj_gym->role;
	/* var_dump($role);
	die; */	
	if($role=='member')
	{ 
		$menu = get_option( 'gmgt_access_right_member');	
	}
	elseif($role=='staff_member')
	{
		$menu = get_option( 'gmgt_access_right_staff_member');
	}
	elseif($role=='accountant')
	{
		$menu = get_option( 'gmgt_access_right_accountant');
	}	
	/*   echo '<pre>';
		print_r($menu);
		echo '</pre>';
		die;   */
	foreach ( $menu as $key1=>$value1 ) 
	{									
		foreach ( $value1 as $key=>$value ) 
		{	
			if ($page == $value['page_link'])
			{		
				//GET CLASS LIST DATA
				if($obj_gym->role == 'staff_member')
				{
					if($value['own_data']=='1')
					{
						$noticedata =$obj_notice->MJ_gmgt_get_notice($obj_gym->role);
					}
					else	
					{
						$noticedata =$obj_notice->MJ_gmgt_get_all_notice();
					}	
				}
				elseif($obj_gym->role == 'member')
				{		
					if($value['own_data']=='1')
					{
						$noticedata =$obj_notice->MJ_gmgt_get_notice($obj_gym->role);
					}
					else	
					{
						$noticedata =$obj_notice->MJ_gmgt_get_all_notice();
					}	
				}
				else
				{		
					if($value['own_data']=='1')
					{
						$noticedata =$obj_notice->MJ_gmgt_get_notice($obj_gym->role);
					}
					else	
					{
						$noticedata =$obj_notice->MJ_gmgt_get_all_notice();
					}	
				}
			 
				$noticedata_count= count($noticedata);
				return $noticedata_count;
			}
		}
	}	
}
//ACCESS PERMISION ALERT MESSAGE FUNCTION
function MJ_gmgt_access_right_page_not_access_message()
{
	?>
	<script type="text/javascript">
		$(document).ready(function() 
		{	
			alert("<?php _e('You do not have permission to perform this operation.','gym_mgt');?>");
			window.location.href='?dashboard=user';
		});
	</script>
<?php
}	
//REMOVE TAG AND SLASH FROM STRING FUNCTION 
function MJ_gmgt_strip_tags_and_stripslashes($post_string)
{
	$string = str_replace('&nbsp;', ' ', $post_string);
    $string = html_entity_decode($string, ENT_QUOTES | ENT_COMPAT , 'UTF-8');
    $string = html_entity_decode($string, ENT_HTML5, 'UTF-8');
    $string = html_entity_decode($string);
    $string = htmlspecialchars_decode($string);
    $string = strip_tags($string);
	//$replace_string=preg_replace('/[^\x00-\x80]|[^0-9a-zA-Z\\u0180-\u024F\u0100-\u017F\u0080-\u00FF\u00C0-\u00D6\u00D8-\u00f6\u00f8-\u00ff\u0000-\u007F\s\_\,\`\.\'\^\-\&\@\()\{}\|\|\=\%\*\#\!\~\$\+\n]/s', '', $string);
	return $string;
}
function MJ_gmgt_password_validation($post_string)
{
	$string = str_replace('&nbsp;', ' ', $post_string);
    $string = html_entity_decode($string, ENT_QUOTES | ENT_COMPAT , 'UTF-8');
    $string = html_entity_decode($string, ENT_HTML5, 'UTF-8');
    $string = html_entity_decode($string);
    $string = htmlspecialchars_decode($string);
    $string = strip_tags($string);
	//$replase_string=preg_replace('/[^0-9a-zA-Z\u0180-\u024F\u0100-\u017F\u0080-\u00FF\u00C0-\u00D6\u00D8-\u00f6\u00f8-\u00ff\u0000-\u007F\_\,\`\.\'\^\-\&\@\()\{}\[]\|\|\=\%\*\%\#\!\~\$\<>\+\n]/s', '', $string);
	return $string;
}
//TIME AM PM BEFORE COLON REMOVE FUNCTION
function MJ_gmgt_timeremovecolonbefoream_pm($timevalue)
{
	if (strpos($timevalue, 'am') == true)
	{
		$time=str_replace(":am"," am",$timevalue);
	}
	elseif (strpos($timevalue, 'pm') == true)
	{
		$time=str_replace(":pm"," pm",$timevalue);
	}
	return $time;
}
//FRONTED MEMBERRSHIP PAYMENT FUNCTION
function MJ_frontend_side_membership_payment_function($pay_id,$customer_id,$amount,$trasaction_ids,$payment_method)
{
   global $wpdb;
	$obj_membership_payment=new MJ_Gmgt_membership_payment;
	$obj_membership=new MJ_Gmgtmembership;	
	$obj_member=new MJ_Gmgtmember;
	if(isset($trasaction_ids))
	{
		$trasaction_id  = $trasaction_ids;
	}
	else
	{
	  $trasaction_id  = '';
	}
	$joiningdate=date("Y-m-d");
	$membership=$obj_membership->MJ_gmgt_get_single_membership($pay_id);
	$validity=$membership->membership_length_id;
	$expiredate= date('Y-m-d', strtotime($joiningdate. ' + '.$validity.' days'));
	$membership_status = 'continue';
	$payment_data = array();
	$membershippayment=$obj_membership_payment->MJ_gmgt_checkMembershipBuyOrNot($customer_id,$joiningdate,$expiredate);
	
	if(!empty($membershippayment))
	{
		global $wpdb;
		$table_gmgt_membership_payment=$wpdb->prefix.'Gmgt_membership_payment';
		$payment_data['payment_status'] = 0;
		$whereid['mp_id']=$membershippayment->mp_id;
		$wpdb->update( $table_gmgt_membership_payment, $payment_data ,$whereid);
		$plan_id =$membershippayment->mp_id;
	}
	else
	{
		global $wpdb;
		//invoice number generate
		$table_income=$wpdb->prefix.'gmgt_income_expense';
		$result_invoice_no=$wpdb->get_results("SELECT * FROM $table_income");						
		
		if(empty($result_invoice_no))
		{							
			$invoice_no='00001';
		}
		else
		{							
			$result_no=$wpdb->get_row("SELECT invoice_no FROM $table_income where invoice_id=(SELECT max(invoice_id) FROM $table_income)");
			$last_invoice_number=$result_no->invoice_no;
			$invoice_number_length=strlen($last_invoice_number);
			
			if($invoice_number_length=='5')
			{
				$invoice_no = str_pad($last_invoice_number+1, 5, 0, STR_PAD_LEFT);
			}
			else	
			{
				$invoice_no='00001';
			}				
		}
				
		$payment_data['invoice_no']=$invoice_no;
		$payment_data['member_id'] = $customer_id;
		$payment_data['membership_id'] = $pay_id;
		$payment_data['membership_fees_amount'] = MJ_gmgt_get_membership_price($pay_id);
		$payment_data['membership_signup_amount'] = MJ_gmgt_get_membership_signup_amount($pay_id);
		$payment_data['tax_amount'] = MJ_gmgt_get_membership_tax_amount($pay_id);
		$membership_amount=$payment_data['membership_fees_amount'] + $payment_data['membership_signup_amount']+$payment_data['tax_amount'];
		$payment_data['membership_amount'] = $membership_amount;
		$payment_data['start_date'] = $joiningdate;
		$payment_data['end_date'] = $expiredate;
		$payment_data['membership_status'] = $membership_status;
		$payment_data['payment_status'] = 0;
		$payment_data['created_date'] = date("Y-m-d");
		$payment_data['created_by'] = $customer_id;
		$plan_id = $obj_member->MJ_gmgt_add_membership_payment_detail($payment_data);
		
		//save membership payment data into income table							
		$membership_name=MJ_gmgt_get_membership_name($pay_id);
		$entry_array[]=array('entry'=>$membership_name,'amount'=>MJ_gmgt_get_membership_price($pay_id));
		$entry_array1[]=array('entry'=>__("Membership Signup Fee","gym_mgt"),'amount'=>MJ_gmgt_get_membership_signup_amount($pay_id));	
		$entry_array_merge=array_merge($entry_array,$entry_array1);		
		$incomedata['entry']=json_encode($entry_array_merge);	
		
		$incomedata['invoice_type']='income';
		$incomedata['invoice_label']=__("Fees Payment","gym_mgt");
		$incomedata['supplier_name']=$customer_id;
		$incomedata['invoice_date']=date('Y-m-d');
		$incomedata['receiver_id']=$customer_id;
		$incomedata['amount']=$membership_amount;
		$incomedata['total_amount']=$membership_amount;
		$incomedata['invoice_no']=$invoice_no;
		$incomedata['paid_amount']=$amount;
		$incomedata['tax_id']=MJ_gmgt_get_membership_tax($pay_id);
		$incomedata['payment_status']='Fully Paid';
		$result_income=$wpdb->insert( $table_income,$incomedata); 
	}
	$feedata['mp_id']=$plan_id;	
	$feedata['amount']=$amount;
	$feedata['payment_method']=$payment_method;		
	$feedata['trasaction_id']=$trasaction_id;
	$feedata['created_by']=$customer_id;
	$result=$obj_membership_payment->MJ_gmgt_add_feespayment_history($feedata);
	
	if($result)
	{
		$u = new WP_User($customer_id);
		$u->remove_role( 'subscriber' );
		$u->add_role( 'member' );
		$gmgt_hash=delete_user_meta($customer_id, 'gmgt_hash');
		update_user_meta( $customer_id, 'membership_id', $pay_id );					
	}
    return $result;			
}
//get tax Name by tax id array
function MJ_gmgt_tax_name_by_tax_id_array($tax_id_string)
{
	$obj_tax=new MJ_Gmgttax;
	
	$tax_name=array();
	$tax_id_array=explode(",",$tax_id_string);
	$tax_name_string="";
	if(!empty($tax_id_string))
	{
		foreach($tax_id_array as $tax_id)
		{
			$gmgt_taxs=$obj_tax->MJ_gmgt_get_single_tax_data($tax_id);		
			$tax_name[]=$gmgt_taxs->tax_title.'-'.$gmgt_taxs->tax_value;
		}	
		$tax_name_string=implode(",",$tax_name);		
	}	
	return $tax_name_string;
	die;
}
//get tax percentage by tax id
function MJ_gmgt_tax_percentage_by_tax_id($tax_id)
{
	$obj_tax=new MJ_Gmgttax;
	if(!empty($tax_id))
	{	
		$gmgt_taxs=$obj_tax->MJ_gmgt_get_single_tax_data($tax_id);		
	}
	else
	{
		$gmgt_taxs='';
	}
	
	if(!empty($gmgt_taxs))
	{
		return $gmgt_taxs->tax_value;
	}
	else
	{
		return 0;
	}
	die;
}
//get reservation time convert in 24 hour
function MJ_gmgt_get_reservation_time_in_24_hours($time)
{
	$time_array = explode(":",$time);
	$time_array_new = $time_array[0].":".$time_array[1]."".$time_array[2];
	$time_formate =  date("H:i", strtotime($time_array_new)); 
	return $time_formate;
	die;
}
//Html Tags special character remove from sring
function MJ_gmgt_remove_tags_and_special_characters($string)
{	
	$search = array('!','@','#','$','%','^','&','*','(',')','.','{','}','<','>',',','+','-','*');
	$replace = array('','','','','','','','','','','','','','','','','','','');
	$new_string=str_replace($search, $replace,strip_tags($string));

	return $new_string;
}
//activity category list from activity category type in membership
function MJ_gmgt_get_activity_from_category_type()
{
	$obj_activity=new MJ_Gmgtactivity;
	$action_membership=$_REQUEST['action_membership'];
	$membership_id_activity=$_REQUEST['membership_id_activity'];
	$selected_activity_category_list=$_REQUEST['selected_activity_category_list'];
	$category_array_to_string = implode("','",$selected_activity_category_list);
	$array_var= array();
	
	global $wpdb;
	$table_gmgt_activity = $wpdb->prefix. 'gmgt_activity';
	$activities = $wpdb->get_results("SELECT * FROM $table_gmgt_activity where activity_cat_id IN ('".$category_array_to_string."')");
		
	if(!empty($activities))
	{
		foreach($activities as $activity)
		{			
			if($action_membership=='add_membership')
			{
				$array_var[]='<option value="'.$activity->activity_id.'">'.$activity->activity_title.'</option>';		 
			}
			else
			{
				$activity_array = $obj_activity->MJ_gmgt_get_membership_activity($membership_id_activity);
				$selected = "";
				if(in_array($activity->activity_id,$activity_array))
					$selected = "selected";
				
				$array_var[]='<option value="'.$activity->activity_id.'" '.$selected.'>'.$activity->activity_title.'</option>';
			}		
		}	
	}	
	
	echo json_encode($array_var);
	die();
}
// activity category on change to  specialization staff member list in activity//
function MJ_gmgt_get_staff_member_list_by_specilization_category_type()
{
	$obj_activity=new MJ_Gmgtactivity;
	$activity_category=$_REQUEST['activity_category'];	
	$array_var= array();
	
	$get_staff = array('role' => 'Staff_member');
	$staffdata=get_users($get_staff);		
	
	$staff_data=$result->activity_assigned_to;
	$array_var[]='<option value="">'.__('Select Staff Member','gym_mgt').'</option>';
	if(!empty($staffdata))
	{
		foreach($staffdata as $staff)
		{		
			$staff_specialization=explode(',',$staff->activity_category);
			if(in_array($activity_category,$staff_specialization))
			{	
				$array_var[]='<option value="'.$staff->ID.'">'.$staff->display_name.'</option>';
			}			
		}	
	}	
	echo json_encode($array_var);
	die();
}
//Get member Current membership  Activity list in Assign Workout //
function MJ_gmgt_get_member_current_membership_activity_list()
{
	global $wpdb;
	$table_membership = $wpdb->prefix. 'gmgt_membershiptype';
	$table_gmgt_membership_activities = $wpdb->prefix. 'gmgt_membership_activities';
	
	$member_id=$_REQUEST['member_id'];	
	$array_var= array();
	$membersip_activity_array= array();
	$membership_id=get_user_meta( $member_id,'membership_id',true);
	
	$result = $wpdb->get_row("SELECT * FROM $table_membership where membership_id= $membership_id");
	$result1 = $wpdb->get_results("SELECT * FROM $table_gmgt_membership_activities where membership_id= $membership_id");
	if(!empty($result1))
	{
		foreach ($result1 as $data)
		{
			$membersip_activity_array[]=$data->activity_id;
		}
	}	
	if(!empty($result->activity_cat_id))
	{
		$activity_cat_id=explode(',',$result->activity_cat_id);	
	}
	else
	{
		$activity_cat_id='';
	}
	$membersip_activity_category_array=$activity_cat_id;
	
	$activity_category=MJ_gmgt_get_all_category('activity_category');
	
	if(!empty($membersip_activity_category_array))
	{
		if(!empty($activity_category))
		{
			foreach ($activity_category as $retrive_data)
			{	
				if (in_array((string)$retrive_data->ID, $membersip_activity_category_array))
				{					
					$array_var[]='<label class="activity_title"><strong>'.$retrive_data->post_title.'</strong></label>';			
					$activitydata =MJ_gmgt_get_activity_by_category($retrive_data->ID);
					foreach($activitydata as $activity)
					{ 	
						if (in_array((string)$activity->activity_id, $membersip_activity_array))
						{				
							$array_var[]='<div class="checkbox child">
								<label class="col-sm-2" style="padding-top: 7px;padding-bottom: 7px;">
								<input type="checkbox"   value="" name="avtivity_id[]" value="'.$activity->activity_id.'" class="activity_check" id="'.$activity->activity_id.'" data-val="activity" activity_title = "'.$activity->activity_title.'">'.$activity->activity_title.'</label><div id="reps_sets_'.$activity->activity_id.'" class="col-sm-10" style="padding:0px;"></div>
							</div><div class="clear"></div>';	
						}						
					}
								
					$array_var[]='<div class="clear"></div>';				
				}
			}
		}
	}
	else
	{
		$array_var[]='<p>'.__('No Any Activity Added In This Member Current Membership Please Add Activity Into This Member Current Membership.','gym_mgt').'</p>';
	}

	echo json_encode($array_var); 
	die();
}
//-------DATA TABLE MULTILANGUAGE----------- //
function MJ_gmgt_datatable_multi_language()
{
	$datatable_attr=array("sEmptyTable"=> __("No data available in table","gym_mgt"),
						"sInfo"=>__("Showing _START_ to _END_ of _TOTAL_ entries","gym_mgt"),
						"sInfoEmpty"=>__("Showing 0 to 0 of 0 entries","gym_mgt"),
						"sInfoFiltered"=>__("(filtered from _MAX_ total entries)","gym_mgt"),
						"sInfoPostFix"=> "",
						"sInfoThousands"=>",",
						"sLengthMenu"=>__("Show _MENU_ entries","gym_mgt"),
						"sLoadingRecords"=>__("Loading...","gym_mgt"),
						"sProcessing"=>__("Processing...","gym_mgt"),
						"sSearch"=>__("Search:","gym_mgt"),
						"sZeroRecords"=>__("No matching records found","gym_mgt"),
						"oPaginate"=>array(
							"sFirst"=>__("First","gym_mgt"),
							"sLast"=>__("Last","gym_mgt"),
							"sNext"=>__("Next","gym_mgt"),
							"sPrevious"=>__("Previous","gym_mgt")
						),
						"oAria"=>array(
							"sSortAscending"=>__(": activate to sort column ascending","gym_mgt"),
							"sSortDescending"=>__(": activate to sort column descending","gym_mgt")
						)
	);
	
	return $data=json_encode( $datatable_attr);
}
//show event and task model code
function MJ_gmgt_show_event_task()
{	
	$id = $_REQUEST['id'];	
	 
	$model = $_REQUEST['model'];
	$obj_class=new MJ_Gmgtclassschedule;
	if($model=='Membership Details')
	{
		$obj_membership=new MJ_Gmgtmembership;
		$membershipdata=$obj_membership->MJ_gmgt_get_single_membership($id);
	}
	if($model=='Invoice Details')
	{
		$obj_payment=new MJ_Gmgtpayment;
		$paymentdata=$obj_payment->MJ_gmgt_update_incomedata_bymp_id($id);
	}
	if($model=='Reservation Details')
	{
		$obj_reservation=new MJ_Gmgtreservation;
		$reservationdata=$obj_reservation->MJ_gmgt_get_single_reservation($id);
	/* 	var_dump($reservationdata);
		die; */
	 
	}
	if($model=='Notice Details')
	{
		$noticedata =get_post($id);
	}
	if($model=='Group Details')
	{
		$obj_group=new MJ_Gmgtgroup;
		$groupdata =$obj_group->MJ_gmgt_get_single_group($id);
	}
	if($model=='Class Details')
	{
		$classdata =$obj_class->MJ_gmgt_get_single_class($id);
	}
	if($model=='Booking Details')
	{
		$bookingdata =$obj_class->MJ_gmgt_get_single_booked_class_($id);
	}
?>
     <div class="modal-header model_header_padding"> <a href="#" class="event_close-btn badge badge-success pull-right">X</a>
  		<h4 id="myLargeModalLabel" class="modal-title"><?php if($model=='Membership Details'){ _e('Membership Details','gym_mgt'); } elseif($model=='Invoice Details'){ _e('Invoice Details','gym_mgt'); } elseif($model=='Reservation Details'){ _e('Reservation Details','gym_mgt'); } elseif($model=='Notice Details'){ _e('Notice Details','gym_mgt'); } elseif($model=='Group Details'){ _e('Group Details','gym_mgt'); } elseif($model=='Class Details'){ _e('Class Details','gym_mgt'); } elseif($model=='Booking Details'){ _e('Booking Details','gym_mgt'); }?></h4>
	</div>
	<div class="panel panel-white">
	<?php
	if($model=='Membership Details')
	{
	?>
		<div class="modal-body">
			<table id="examlist" class="table table-striped" cellspacing="0" width="100%" align="center">
				<tbody>
					<tr>
						<td><?php  _e( 'Membership Name ', 'gym_mgt' ) ;?></td>
						<td><?php echo $membershipdata->membership_label; ?></td>
					</tr>
					<tr>
						<td><?php  _e( 'Membership Short Code ', 'gym_mgt' ) ;?></td>
						<td><?php echo "[MembershipCode id=".$membershipdata->membership_id."]"; ?></td>
					</tr>
					<tr>
						<td><?php _e( 'Membership Period(Days) ', 'gym_mgt' ) ;?></td>
						<td><?php echo $membershipdata->membership_length_id; ?></td>
					</tr>
					<tr>
						<td><?php _e( 'Membership Amount ', 'gym_mgt' ) ;?></td>
						<td><?php echo MJ_gmgt_get_currency_symbol(get_option( 'gmgt_currency_code' )); ?><?php echo $membershipdata->membership_amount; ?></td>
					</tr>
					<tr>
						<td><?php _e( 'Installment Plan ', 'gym_mgt' ) ;?></td>
						<td><?php echo MJ_gmgt_get_currency_symbol(get_option( 'gmgt_currency_code' )); ?><?php echo $membershipdata->installment_amount." ".get_the_title( $membershipdata->install_plan_id ); ?></td>
					</tr>
					<tr>
						<td><?php _e( 'Signup Fee ', 'gym_mgt' ) ;?></td>
						<td><?php echo MJ_gmgt_get_currency_symbol(get_option( 'gmgt_currency_code' )); ?><?php echo $membershipdata->signup_fee; ?></td>
					</tr>
					<tr>
						<td><?php _e( 'Tax ', 'gym_mgt' ) ;?>(%) </td>
						<td><?php if(!empty($membershipdata->tax)) { echo MJ_gmgt_tax_name_by_tax_id_array($membershipdata->tax); }else{ echo '-'; } ?></td>
					</tr>
				</tbody>
			</table>
        </div>  		
	 <?php
	
	}
	?>
	<?php
	if($model=='Invoice Details')
	{
	?>
		<div class="modal-body">
			<table id="examlist" class="table table-striped" cellspacing="0" width="100%" align="center">
				<tbody>
					<tr>
						<td><?php _e( 'Invoice No', 'gym_mgt' ) ;?></td>
						<td><?php echo $paymentdata->invoice_no; ?></td>
					</tr>
					<tr>
						<td> <?php _e( 'Income Name', 'gym_mgt' ) ;?></td>
						<td><?php echo $paymentdata->invoice_label; ?></td>
					</tr>
					<t	r>
						<td><?php _e( 'Member Name', 'gym_mgt' ) ;?></td>
						<td><?php $user=get_userdata($paymentdata->supplier_name);
									$memberid=get_user_meta($paymentdata->supplier_name,'member_id',true);
									$display_label=$user->display_name;
									if($memberid)
										$display_label.=" (".$memberid.")";
									echo $display_label;?></td>
					</tr>
					 <tr>
						<td><?php _e( 'Amount', 'gym_mgt' ) ;?></td>
						<td><?php echo MJ_gmgt_get_currency_symbol(get_option( 'gmgt_currency_code' )); ?> <?php echo number_format($paymentdata->total_amount,2);?></td>
					</tr>
					<tr>
						<td><?php  _e( 'Paid Amount', 'gym_mgt' ) ;?></td>
						<td><?php echo MJ_gmgt_get_currency_symbol(get_option( 'gmgt_currency_code' )); ?> <?php echo number_format($paymentdata->paid_amount,2);?></td>
					</tr>
					<tr>
						<td><?php  _e( 'Due Amount', 'gym_mgt' ) ;?></td>
						<td><?php  
						$due_amount=abs($paymentdata->total_amount-$paymentdata->paid_amount);
						echo MJ_gmgt_get_currency_symbol(get_option( 'gmgt_currency_code' )); ?> <?php echo number_format($due_amount,2);?></td>
					</tr>
					<tr>
						<td><?php _e( 'Date', 'gym_mgt' ) ;?></td>
						<td><?php echo MJ_gmgt_getdate_in_input_box($paymentdata->invoice_date); ?></td>
					</tr>
					<tr>
						<td><?php  _e( 'Payment Status', 'gym_mgt' ) ;?></td>
						<td><?php echo $paymentdata->payment_status; ?></td>
					</tr>
				</tbody>
			</table>
        </div>  		
	 <?php
	}
	?>
	<?php
	if($model=='Reservation Details')
	{
	?>
		<div class="modal-body">
			<table id="examlist" class="table table-striped" cellspacing="0" width="100%" align="center">
				<tbody>
					<tr>
						<td><?php _e( 'Event Name', 'gym_mgt' ) ;?></td>
						<td><?php echo $reservationdata->event_name; ?></td>
					</tr>
					<tr>
						<td> <?php _e( 'Event Date', 'gym_mgt' ) ;?></td>
						<td><?php echo MJ_gmgt_getdate_in_input_box($reservationdata->event_date); ?></td>
					</tr>
					 <tr>
						<td> <?php _e( 'Place', 'gym_mgt' ) ;?></td>
						<td><?php echo get_the_title( $reservationdata->place_id ); ?></td>
					</tr>
					 <tr>
						<td> <?php _e( 'Starting Time', 'gym_mgt' ) ;?></td>
						<td><?php echo MJ_gmgt_timeremovecolonbefoream_pm($reservationdata->start_time); ?></td>
					</tr>
					<tr>
						<td> <?php _e( 'Ending Time', 'gym_mgt' ) ;?></td>
						<td><?php echo MJ_gmgt_timeremovecolonbefoream_pm($reservationdata->end_time); ?></td>
					</tr>
					<tr>
						<td><?php  _e( 'Reserved By', 'gym_mgt' ) ;?></td>
						<td><?php echo MJ_gmgt_get_display_name($reservationdata->staff_id); ?></td>
					</tr>
				</tbody>
			</table>
        </div>  		
	 <?php
	
	}
	?>
	<?php
	if($model=='Notice Details')
	{
	?>
		<div class="modal-body">
			<table id="examlist" class="table table-striped" cellspacing="0" width="100%" align="center">
				<tbody>
					<tr>
						<td><?php _e( 'Notice Title', 'gym_mgt' ) ;?></td>
						<td><?php echo $noticedata->post_title; ?></td>
					</tr>
					<tr>
						<td> <?php _e( 'Notice Comment', 'gym_mgt' ) ;?></td>
						<td><?php  
							echo $noticedata->post_content; ?></td>
					</tr>
					 <tr>
						<td> <?php _e( 'Notice For', 'gym_mgt' ) ;?></td>
						<td><?php echo ucwords(str_replace("_"," ",get_post_meta( $noticedata->ID, 'notice_for',true)));?></td>
					</tr>
					 <tr>
						<td> <?php _e( 'Class', 'gym_mgt' ) ;?></td>
						<td><?php if(get_post_meta( $noticedata->ID, 'gmgt_class_id',true) !="" && get_post_meta( $noticedata->ID, 'gmgt_class_id',true) =="all")
						 {
							 _e('All','gym_mgt');
						 }
						 elseif(get_post_meta( $noticedata->ID, 'gmgt_class_id',true) !="")
						 {
							echo MJ_gmgt_get_class_name(get_post_meta( $noticedata->ID, 'gmgt_class_id',true));
						 }
						 else
						 {
							 echo '-';
						 } ?></td>
					</tr>
					<tr>
						<td> <?php _e( 'Start Date', 'gym_mgt' ) ;?></td>
						<td><?php echo MJ_gmgt_getdate_in_input_box(get_post_meta($noticedata->ID,'gmgt_start_date',true)); ?></td>
					</tr>
					<tr>
						<td> <?php _e( 'End Date', 'gym_mgt' ) ;?></td>
						<td><?php echo MJ_gmgt_getdate_in_input_box(get_post_meta($noticedata->ID,'gmgt_end_date',true)); ?></td>
					</tr>
					 
				</tbody>
			</table>
        </div>  		
	 <?php
	
	}
	?>
	<?php
	if($model=='Group Details')
	{
	?>
		<div class="modal-body">
			<table id="examlist" class="table table-striped" cellspacing="0" width="100%" align="center">
				<tbody>
					<tr>
						<td><?php _e( 'Group Name', 'gym_mgt' ) ;?></td>
						<td><?php echo $groupdata->group_name; ?></td>
					</tr>
					<tr>
						<td> <?php _e( 'Group Description', 'gym_mgt' ) ;?></td>
						<td><?php  
							if(!empty($groupdata->group_description)) { echo $groupdata->group_description; }else{ echo '-'; }?></td>
					</tr>
					<tr>
						<td> <?php _e( 'Total Group Members', 'gym_mgt' ) ;?></td>
						<td><?php  
							echo $obj_group->MJ_gmgt_count_group_members($groupdata->id); ?></td>
					</tr>
					 
				</tbody>
			</table>
        </div>  		
	 <?php
	
	}
	?>
	<?php
	if($model=='Class Details')
	{
	?>
		<div class="modal-body">
			<table id="examlist" class="table table-striped" cellspacing="0" width="100%" align="center">
				<tbody>
					<tr>
						<td><?php _e( 'Class Name', 'gym_mgt' ) ;?></td>
						<td><?php echo $classdata->class_name; ?></td>
					</tr>
					<tr>
						<td> <?php _e( 'Staff Name', 'gym_mgt' ) ;?></td>
						<td><?php  
							$userdata=get_userdata( $classdata->staff_id );
							echo $userdata->display_name;?></td>
					</tr>
					<tr>
						<td> <?php _e( 'Starting Time', 'gym_mgt' ) ;?></td>
						<td><?php  
							echo MJ_gmgt_timeremovecolonbefoream_pm($classdata->start_time); ?></td>
					</tr>
					<tr>
						<td> <?php _e( 'Ending Time', 'gym_mgt' ) ;?></td>
						<td><?php  
							echo MJ_gmgt_timeremovecolonbefoream_pm($classdata->end_time); ?></td>
					</tr>
					<tr>
						<td> <?php _e( 'Day', 'gym_mgt' ) ;?></td>
						<td><?php $days_array=json_decode($classdata->day); 
							$days_string=array();
							if(!empty($days_array))
							{
								foreach($days_array as $day)
								{
									$days_string[]=substr($day,0,3);
								}
							}
							echo implode(", ",$days_string);
							?>
						</td>
					</tr>
					<tr>
						<td> <?php _e( 'Membership Name', 'gym_mgt' ) ;?></td>
						<td><?php  
							$membersdata=array();
								$membersdata = $obj_class->MJ_gmgt_get_class_members($id);
								if(!empty($membersdata))
								{	
									foreach($membersdata as $key=>$val)
									{
										$data[]= MJ_gmgt_get_membership_name($val->membership_id);
									}
								}	
								echo implode(',',$data); ?></td>
					</tr>
					<tr>
						<td> <?php _e( 'Member Limit', 'gym_mgt' ) ;?></td>
						<td><?php  
							echo $classdata->member_limit; ?>
						</td>
					</tr>
					
				</tbody>
			</table>
        </div>  		
	 <?php
	
	}
	?>
	<?php
	if($model=='Booking Details')
	{
	?>
		<div class="modal-body">
			<table id="examlist" class="table table-striped" cellspacing="0" width="100%" align="center">
				<tbody>
					<tr>
						<td><?php _e( 'Member Name', 'gym_mgt' ) ;?></td>
						<td><?php  echo MJ_gmgt_get_display_name($bookingdata->member_id);?></td>
					</tr>
					<tr>
						<td> <?php _e( 'Class Name', 'gym_mgt' ) ;?></td>
						<td><?php  
							print  $obj_class->MJ_gmgt_get_class_name($bookingdata->class_id);?></td>
					</tr>
					<tr>
						<td> <?php _e( 'Class Date', 'gym_mgt' ) ;?></td>
						<td><?php  
							print  str_replace('00:00:00',"",$bookingdata->class_booking_date);?></td>
					</tr>
					<tr>
						<td> <?php _e( 'Booking Date', 'gym_mgt' ) ;?></td>
						<td><?php  
							 print  str_replace('00:00:00',"",$bookingdata->booking_date); ?></td>
					</tr>
					<tr>
						<td> <?php _e( 'Day', 'gym_mgt' ) ;?></td>
						<td><?php echo $bookingdata->booking_day; ?>
						</td>
					</tr>
					 <tr>
					 <?php $class_data = $obj_class->MJ_gmgt_get_single_class($bookingdata->class_id); ?>
						<td> <?php _e( 'Starting Time', 'gym_mgt' ) ;?></td>
						<td><?php  
							echo MJ_gmgt_timeremovecolonbefoream_pm($class_data->start_time);?></td>
					</tr>
					<tr>
						<td> <?php _e( 'Ending Time', 'gym_mgt' ) ;?></td>
						<td><?php  
							echo MJ_gmgt_timeremovecolonbefoream_pm($class_data->end_time); ?></td>
					</tr>
				</tbody>
			</table>
        </div>  		
	 <?php
	
	}
	?>
    </div> 
	<?php   
	die();	 
}
?>
 