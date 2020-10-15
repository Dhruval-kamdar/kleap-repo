<?php //======FRONT END TEMPLATE PAGE=========//
require_once(ABSPATH.'wp-admin/includes/user.php' );
global $current_user;
$user = wp_get_current_user ();
$obj_dashboard= new MJ_Gmgtdashboard;
$user_id=get_current_user_id(); 
 
//GET USER ROLE //
$user_roles = $current_user->roles;
$user_role = array_shift($user_roles);
$frontend_class_booking=get_option('gym_frontend_class_booking');
//CHECK USER APPROVE OR NOT //
if(MJ_gmgt_check_approve_user($user_id)!='')
{
	wp_logout();
	wp_redirect(site_url().'/index.php/gym-management-login-page/?na=1');
	
}
$obj_gym = new MJ_Gym_management(get_current_user_id());
//CHECK USER LOGIN IF LOGIN SO REDIRECT IN PAGE //
if (! is_user_logged_in ())
{
	$page_id = get_option ( 'gmgt_login_page' );
	
	wp_redirect ( home_url () . "?page_id=" . $page_id );
}
//CHECK USER LOGIN IF LOGIN SO REDIRECT ADMIN SIDE //
if (is_super_admin ()) 
{
	wp_redirect ( admin_url () . 'admin.php?page=gmgt_system' );
}
?>
<!--task-event POP up code -->
  <div class="popup-bg">
    <div class="overlay-content content_width">
		<div class="modal-content" style="border-top: 5px solid #22baa0;">
			<div class="task_event_list">
			</div>     
		</div>
    </div>     
  </div>
 <!-- End task-event POP-UP Code -->
<!-- CLASS BOOK IN CALANDER POPUP HTML CODE -->
<div id="eventContent" class="modal-body" style="display:none;"><!--MODAL BODY DIV START-->
	<style>
	   .ui-dialog .ui-dialog-titlebar-close
	   {
		  margin: -15px -4px 0px 0px !important;
	   }
	</style>
			<p><b><?php _e('Class Name:','gym_mgt');?></b> <span id="class_name"></span></p><br>
			<p><b><?php _e('Start Date & Time:','gym_mgt');?> </b> <span id="startTime"></span></p><br>
			<p><b><?php _e('End Date & Time:','gym_mgt');?></b> <span id="endTime"></span></p><br>
			<p><b><?php _e('Trainer Name:','gym_mgt');?></b> <span id="staff_member_name"></span></p><br>
			<p><b><?php _e('Member Limit In CLass:','gym_mgt');?></b> <span id="Member_limit"></span></p><br>
			<p><b><?php _e('Remaining Member In Class:','gym_mgt');?></b> <span id="Remaining_Member_limit"></span></p><br>
			<form method="post" accept-charset="utf-8" action="?dashboard=user&page=class-schedule&tab=class_booking&action=book_now">
				<input type="hidden" id="class_name_1" name="class_name_1" value="" />
				<input type="hidden" id="startTime_1" name="startTime_1" value="" />
				<input type="hidden" id="endTime_1" name="endTime_1" value="" />
				<input type="hidden" id="class_id1" name="class_id1" value="" />
				<input type="hidden" id="day_id1" name="day_id1" value="" />
				<input type="hidden" id="Remaining_Member_limit_1" name="Remaining_Member_limit_1" value="" />
				<input type="hidden" id="class_date1" name="class_date" value="" />
				<?php if($frontend_class_booking == 'yes')
				{?>
				<div class="submit"><input type="submit" name="Book_Class" id="d_id" class="btn btn-primary sumit display" value="Book Class"/></div>
				<?php 
				} ?>				
			</form>		
</div><!--MODAL BODY DIV END-->
<!-- END CLASS BOOK IN CALANDER POPUP HTML CODE -->
<?php 
if($user_role == 'member')
{
	 //START GET CLASS DATA CODE//
	 $obj_class=new MJ_Gmgtclassschedule;
	 
	 $class_data_all=$obj_class->MJ_gmgt_get_all_classes_by_user_membership(); 
	 if(!empty($class_data_all))
	 {
		foreach ($class_data_all as $classdatas)			
		{
			$user_data= get_userdata($classdatas->staff_id);
			$staff_member_name=$user_data->display_name;
			$date_from = $classdatas->start_date;
			if($date_from == "0000-00-00")
			{
				$date_from = date('Y-m-d');
				$date_from = strtotime($date_from);
			}	
			else
			{
				$date_from = $classdatas->start_date; 
				$date_from = strtotime($date_from);
			}				
			$date_check = $classdatas->end_date; 
			$member_limit = $classdatas->member_limit; 
			if($date_check == "0000-00-00")
			{
				$date_to = 2050-12-31;
				$date_to = strtotime($date_to);
			}	
			else
			{
				$date_to = $classdatas->end_date; 
				$date_to = strtotime($date_to);
			}
			$new_time = DateTime::createFromFormat('h:i A', $classdatas->start_time);
			$startTime_24 = $new_time->format('H:i:s');
			$new_time_end = DateTime::createFromFormat('h:i A', $classdatas->end_time);
			$endTime_24 = $new_time_end->format('H:i:s');
			for ($i=$date_from; $i<=$date_to; $i+=86400)
			{  
				$date1=date("Y-m-d", $i);
				$day = date("l", strtotime($date1));
				$day_array=json_decode($classdatas->day);
				$class_id=$classdatas->class_id;
				$booked_class_data=$obj_class->MJ_gmgt_get_book_class_bydate($class_id,$date1); 
				$remaning_memmber=$member_limit -  $booked_class_data;
				if (in_array($day, $day_array))
				{
					$cal_array [] = array (
					 'type' =>  'class',
					 'class_id' => $classdatas->class_id,
					  'day' => $day,
					  'title' => $classdatas->class_name,
					  'trainer' => $staff_member_name,
					  'start' => $date1."T".$startTime_24,
					  'end' => $date1."T".$endTime_24,
					  'color' => $classdatas->color,
					  'Member_limit' => $member_limit,
					  'remaning_memmber' => $remaning_memmber,
					  'class_date' => $date1,
					 );
				}
			}
		} 
	  }
	   //END START GET CLASS DATA CODE //
	   //START  GET RESERVATION DATA CODE //
		$obj_reservation = new MJ_Gmgtreservation;
		$reservationdata = $obj_reservation->MJ_gmgt_get_all_reservation();
		if(!empty($reservationdata))
		{
		   foreach ($reservationdata as $retrieved_data){
		   $start_time_array = explode(":",$retrieved_data->start_time);
		   $start_time_array_new = $start_time_array[0].":".$start_time_array[1]."".$start_time_array[2];
		   $start_time_formate =  date("H:i:s", strtotime($start_time_array_new)); 
		   $start_time_data = new DateTime($start_time_formate); 
		   $starttime=date_format($start_time_data,'H:i:s');
		   $event_start_date=date('Y-m-d',strtotime($retrieved_data->event_date));
		   $aevent_start_date_new=$event_start_date." ".$starttime;
		   
		   $end_time_array = explode(":",$retrieved_data->end_time);
		   $abcnew = $end_time_array[0].":".$end_time_array[1]."".$end_time_array[2];
		   $Hour_new =  date("H:i:s", strtotime($abcnew)); 
		   $dnew = new DateTime($Hour_new); 
		   $endtime=date_format($dnew,'H:i:s');
		   $event__end_date=$event_start_date." ".$endtime; 
		   
			$i=1;
			 $cal_array [] = array (
					'type' =>  'reservationdata',
					'title' => $retrieved_data->event_name,
					'start' => $aevent_start_date_new,
					'end' => $event__end_date,
			); 
		}
	 }
	//END GET RESERVATION DATA CODE //
	//START GET BIRTHDAY DATA CODE //
	$birthday_boys=get_users(array('role'=>'member'));
	$boys_list="";
	if (! empty ( $birthday_boys )) 
	{
		foreach ( $birthday_boys as $boys ) 
		{
			$cal_array [] = array (
			'type' =>  'Birthday',
					'title' => __($boys->display_name.' Birthday','gym_mgt'),
					'start' =>mysql2date('Y-m-d', $boys->birth_date) ,
					'end' => mysql2date('Y-m-d', $boys->birth_date),
					'backgroundColor' => '#F25656'
			);	
		}		
	}
	//END GET BIRTHDAY DATA CODE //
	//END GET NOTICE DATA CODE //
	if (! empty ( $obj_gym->notice )) 
	{
		foreach ( $obj_gym->notice as $notice ) 
		{
			$notice_start_date=get_post_meta($notice->ID,'gmgt_start_date',true);
			 $notice_end_date=get_post_meta($notice->ID,'gmgt_end_date',true);
			$i=1;
			
			$cal_array[] = array (
				   'type' =>  'notice',
					'title' => $notice->post_title,
					'start' => mysql2date('Y-m-d', $notice_start_date ),
					'end' => date('Y-m-d',strtotime($notice_end_date.' +'.$i.' days')),
					'color' => '#12AFCB'
			);	
		}
	}
	//END GET NOTICE DATA CODE //
}
else
{
	//START GET RESERVATION DATA CODE //
	$obj_reservation = new MJ_Gmgtreservation;
	$reservationdata = $obj_reservation->MJ_gmgt_get_all_reservation();
	$cal_array = array();
	if(!empty($reservationdata))
	{
		foreach ($reservationdata as $retrieved_data){
			
		   $start_time_array = explode(":",$retrieved_data->start_time);
		   $start_time_array_new = $start_time_array[0].":".$start_time_array[1]."".$start_time_array[2];
		   $start_time_formate =  date("H:i:s", strtotime($start_time_array_new)); 
		   $start_time_data = new DateTime($start_time_formate); 
		   $starttime=date_format($start_time_data,'H:i:s');		   
		   $event_start_date=date('Y-m-d',strtotime($retrieved_data->event_date));
		   $aevent_start_date_new=$event_start_date." ".$starttime;
		   
		   $end_time_array = explode(":",$retrieved_data->end_time);
		   $abcnew = $end_time_array[0].":".$end_time_array[1]."".$end_time_array[2];
		   $Hour_new =  date("H:i:s", strtotime($abcnew)); 
		   $dnew = new DateTime($Hour_new); 
		   $endtime=date_format($dnew,'H:i:s');
		   $event__end_date=$event_start_date." ".$endtime; 
		   
			$i=1;
			 $cal_array [] = array (
					'type' =>  'reservationdata',
					'title' => $retrieved_data->event_name,
					'start' => $aevent_start_date_new,
					'end' => $event__end_date,
			); 
		}
	}
	//END GET RESERVATION DATA CODE //
	//START GET BIRTHDAY DATA CODE //
	$birthday_boys=get_users(array('role'=>'member'));
	$boys_list="";
	if (! empty ( $birthday_boys )) 
	{
			foreach ( $birthday_boys as $boys ) 
			{
				$cal_array [] = array (
				'type' =>  'Birthday',
						'title' => __($boys->display_name.' Birthday','gym_mgt'),
						'start' =>mysql2date('Y-m-d', $boys->birth_date) ,
						'end' => mysql2date('Y-m-d', $boys->birth_date),
						'backgroundColor' => '#F25656'
				);	
				
				
			}
			
	}
	//END GET BIRTHDAY DATA CODE //
	//START GET NOTICE DATA CODE //
	if (! empty ( $obj_gym->notice )) 
	{
		foreach ( $obj_gym->notice as $notice ) 
		{
			$notice_start_date=get_post_meta($notice->ID,'gmgt_start_date',true);
			 $notice_end_date=get_post_meta($notice->ID,'gmgt_end_date',true);
			$i=1;
			
			$cal_array[] = array (
				   'type' =>  'notice',
					'title' => $notice->post_title,
					'start' => mysql2date('Y-m-d', $notice_start_date ),
					'end' => date('Y-m-d',strtotime($notice_end_date.' +'.$i.' days')),
					'color' => '#12AFCB'
			);	
				
		}
	}
	//START GET NOTICE DATA CODE //
}
?>
<script>
jQuery(document).ready(function() 
 {
	jQuery('#calendar').fullCalendar(
	{
		header: {
					left: 'prev,next today',
					center: 'title',
					right: 'month,agendaWeek,agendaDay'
				},
				defaultView: 'month',
				editable: false,
				slotEventOverlap: false,
				timeFormat: 'h(:mm)a',
			eventLimit:1,
			lockOverflow: function(scrollbarWidths) { return;
				
			},// allow "more" link when too many events
			events: <?php echo json_encode($cal_array);?>,
			forceEventDuration : true,
			
			//start add class in pop up//
	        eventClick:  function(event, jsEvent, view) {
			   
			<?php $dformate=get_option('gmgt_datepicker_format'); ?>
				var dateformate_value='<?php echo $dformate;?>';	
				if(dateformate_value == 'yy-mm-dd')
				{	
				  var dateformate='YYYY-MM-DD h:mm A';
				}
				if(dateformate_value == 'yy/mm/dd')
				{	
				  var dateformate='YYYY/MM/DD h:mm A';	
				}
				if(dateformate_value == 'dd-mm-yy')
				{	
				  var dateformate='DD-MM-YYYY h:mm A';
				}
				if(dateformate_value == 'mm-dd-yy')
				{	
				  var dateformate='MM-DD-YYYY h:mm A';
				}
				if(dateformate_value == 'mm/dd/yy')
				{	
				  var dateformate='MM/DD/YYYY h:mm A';	
				}
				
				$("#eventContent #class_name").html(event.title);
				$("#eventContent #startTime").html(moment(event.start).format(dateformate));
				$("#eventContent #endTime").html(moment(event.end).format(dateformate)); 
				$("#eventContent #staff_member_name ").html(event.trainer);
				$("#eventContent #Member_limit ").html(event.Member_limit);
				$("#eventContent #Remaining_Member_limit ").html(event.remaning_memmber);
				$("#eventContent #class_date_id ").html(event.class_date);
				
				$("#class_name_1").val(event.title);
				$("#startTime_1").val(moment(event.start).format(dateformate));
				$("#endTime_1").val(moment(event.end).format(dateformate));
				
				$("#staff_member_name_1").val(event.trainer);
				$("#Member_limit_1").val(event.Member_limit);
				$("#Remaining_Member_limit_1").val(event.remaning_memmber);
				$("#class_id1").val(event.class_id);
				$("#day_id1").val(event.day);
				$("#class_date1").val(event.class_date);
				$("#d_id").html();
				
			  
				var today = new Date();
				var class_dates= event.class_date;
				var dd = today.getDate();
				var mm = today.getMonth()+1; 
				var yyyy = today.getFullYear();
				if(dd<10) 
				{
					dd='0'+dd;
				} 

				if(mm<10) 
				{
					mm='0'+mm;
				} 
				var new_today = yyyy+'-'+mm+'-'+dd;
				if(new_today <= class_dates )
				{
					$("#eventLink").attr('href', event.url);
					$("#eventContent").dialog({ modal: true, title: 'Class Book',width:340, height:410 });
					$(".ui-dialog-titlebar-close").text('x');
					$(".ui-dialog-titlebar-close").css('height',30);
				}
		    },
			//end  add class in pop up//
			//start add mouse over event only notice,birthday and reservation event//
			eventMouseover: function (event, jsEvent, view) 
			{
				<?php $dformate=get_option('gmgt_datepicker_format'); ?>
				var dateformate_value='<?php echo $dformate;?>';	
				if(dateformate_value == 'yy-mm-dd')
				{	
				var dateformate='YYYY-MM-DD';
				}
				if(dateformate_value == 'yy/mm/dd')
				{	
				var dateformate='YYYY/MM/DD';	
				}
				if(dateformate_value == 'dd-mm-yy')
				{	
				var dateformate='DD-MM-YYYY';
				}
				if(dateformate_value == 'mm-dd-yy')
				{	
				var dateformate='MM-DD-YYYY';
				}
				if(dateformate_value == 'mm/dd/yy')
				{	
				var dateformate='MM/DD/YYYY';	
				}
				 var newstartdate = event.start;
				 var date = new Date(newstartdate);
				 var startdate = new Date(date);
				 var dateObjstart = new Date(startdate);
				 var momentObjstart = moment(dateObjstart);
				 var momentStringstart = momentObjstart.format(dateformate);

				 var newdate = event.end;
				  var type = event.type;
				 var date = new Date(newdate);
				 var newdate1 = new Date(date);
				 if(type == 'reservationdata')
				 {
					newdate1.setDate(newdate1.getDate());
				 }
				 else
				 {
					 newdate1.setDate(newdate1.getDate() - 1);
				 }
				 var dateObj = new Date(newdate1);
				 var momentObj = moment(dateObj);
				 var momentString = momentObj.format(dateformate);
				 var data_type=event.type;
	             if(data_type == 'Birthday' || data_type == 'reservationdata' || data_type == 'notice' )
				{
					tooltip = '<div class="tooltiptopicevent" style="width:auto;height:auto;background:#feb811;position:absolute;z-index:10001;padding:10px 10px 10px 10px ;  line-height: 200%;">' + '<?php _e('Title Name','gym_mgt'); ?>' + ': ' + event.title + '</br>' + ' <?php _e('Start Date','gym_mgt'); ?>' + ': ' + momentStringstart + '</br>' + '<?php _e('End Date','gym_mgt'); ?>' + ': ' + momentString + '</br>' +  ' </div>';
					$("body").append(tooltip);
					$(this).mouseover(function (e) {
						$(this).css('z-index', 10000);
						$('.tooltiptopicevent').fadeIn('500');
						$('.tooltiptopicevent').fadeTo('10', 1.9);
					}).mousemove(function (e) {
						$('.tooltiptopicevent').css('top', e.pageY + 10);
						$('.tooltiptopicevent').css('left', e.pageX + 20);
					});
				}
            },
			eventMouseout: function (data, event, view)
			{
				$(this).css('z-index', 8);
				$('.tooltiptopicevent').remove();
			},
			
			//end add mouse over event only notice,birthday and reservation event//
	})
});
</script>
<html lang="en"><!--HTML START-->
	<head>
	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<link rel="stylesheet"	href="<?php echo GMS_PLUGIN_URL.'/assets/css/dataTables.css'; ?>">
	<link rel="stylesheet"	href="<?php echo GMS_PLUGIN_URL.'/assets/css/dataTables.editor.min.css'; ?>">
	<link rel="stylesheet"	href="<?php echo GMS_PLUGIN_URL.'/assets/css/dataTables.tableTools.css'; ?>">
	<link rel="stylesheet"	href="<?php echo GMS_PLUGIN_URL.'/assets/css/dataTables.responsive.css'; ?>">
	<link rel="stylesheet"	href="<?php echo GMS_PLUGIN_URL.'/assets/css/jquery-ui.css'; ?>">
	<link rel="stylesheet"	href="<?php echo GMS_PLUGIN_URL.'/assets/css/font-awesome.min.css'; ?>">
	<link rel="stylesheet"	href="<?php echo GMS_PLUGIN_URL.'/assets/css/popup.css'; ?>">
	<link rel="stylesheet"	href="<?php echo GMS_PLUGIN_URL.'/assets/css/style.css'; ?>">
	<link rel="stylesheet"	href="<?php echo GMS_PLUGIN_URL.'/assets/css/dashboard.css'; ?>">
	<link rel="stylesheet"	href="<?php echo GMS_PLUGIN_URL.'/assets/css/custom.css'; ?>">
	<link rel="stylesheet"	href="<?php echo GMS_PLUGIN_URL.'/assets/css/fullcalendar.css'; ?>">
	<link rel="stylesheet"	href="<?php echo GMS_PLUGIN_URL.'/assets/css/bootstrap.min.css'; ?>">	
	<link rel="stylesheet"	href="<?php echo GMS_PLUGIN_URL.'/assets/css/datepicker.min.css'; ?>">
	<link rel="stylesheet"	href="<?php echo GMS_PLUGIN_URL.'/assets/css/bootstrap-multiselect.css'; ?>">	
	<link rel="stylesheet"	href="<?php echo GMS_PLUGIN_URL.'/assets/css/white.css'; ?>">
		
	<link rel="stylesheet"	href="<?php echo GMS_PLUGIN_URL.'/assets/css/gymmgt.min.css'; ?>">
		<?php  
			if (is_rtl())
			{
			?>
			<link rel="stylesheet"	href="<?php echo GMS_PLUGIN_URL.'/assets/css/bootstrap-rtl.min.css'; ?>">
		<?php  
			} ?>
	<link rel="stylesheet"	href="<?php echo GMS_PLUGIN_URL.'/lib/validationEngine/css/validationEngine.jquery.css'; ?>">
	<link rel="stylesheet"	href="<?php echo GMS_PLUGIN_URL.'/lib/select2-3.5.3/select2.css'; ?>">
	<link rel="stylesheet"	href="<?php echo GMS_PLUGIN_URL.'/assets/css/gym-responsive.css'; ?>">

	<script type="text/javascript"	src="<?php echo GMS_PLUGIN_URL.'/assets/js/jquery-1.11.1.min.js'; ?>"></script>
	<script type="text/javascript"	src="<?php echo GMS_PLUGIN_URL.'/assets/js/jquery-ui.js'; ?>"></script>
	<script type="text/javascript"	src="<?php echo GMS_PLUGIN_URL.'/assets/js/moment.min.js'; ?>"></script>
	<script type="text/javascript"	src="<?php echo GMS_PLUGIN_URL.'/assets/js/fullcalendar.min.js'; ?>"></script>
	<?php /*--------Full calendar multilanguage---------*/
		$lancode=get_locale();
		$code=substr($lancode,0,2);
	?>
	<script type="text/javascript"	src="<?php echo GMS_PLUGIN_URL.'/assets/js/calendar-lang/'.$code.'.js'; ?>"></script>
	<script type="text/javascript"	src="<?php echo GMS_PLUGIN_URL.'/lib/select2-3.5.3/select2.min.js'; ?>"></script>
	<script type="text/javascript"	src="<?php echo GMS_PLUGIN_URL.'/assets/js/jquery.dataTables.min.js'; ?>"></script>
	<script type="text/javascript"	src="<?php echo GMS_PLUGIN_URL.'/assets/js/dataTables.tableTools.min.js'; ?>"></script>
	<script type="text/javascript"	src="<?php echo GMS_PLUGIN_URL.'/assets/js/dataTables.editor.min.js'; ?>"></script>
	<script type="text/javascript"	src="<?php echo GMS_PLUGIN_URL.'/assets/js/dataTables.responsive.js'; ?>"></script>
	<script type="text/javascript"	src="<?php echo GMS_PLUGIN_URL.'/assets/js/bootstrap.min.js'; ?>"></script>
	<script type="text/javascript"	src="<?php echo GMS_PLUGIN_URL.'/assets/js/bootstrap-datepicker.js'; ?>"></script>
	<script type="text/javascript"	src="<?php echo GMS_PLUGIN_URL.'/assets/js/bootstrap-multiselect.js'; ?>"></script>
	<script type="text/javascript"	src="<?php echo GMS_PLUGIN_URL.'/assets/js/responsive-tabs.js'; ?>"></script>
	<script type="text/javascript"	src="<?php echo GMS_PLUGIN_URL.'/lib/validationEngine/js/languages/jquery.validationEngine-'.$code.'.js'; ?>"></script>
	<script type="text/javascript"	src="<?php echo GMS_PLUGIN_URL.'/lib/validationEngine/js/jquery.validationEngine.js'; ?>"></script>
	 <script type="text/javascript" src="<?php echo GMS_PLUGIN_URL.'/assets/js/jssor.slider.mini.js';?>"></script>
	</head>
	<body class="gym-management-content"><!--BODY START-->
		<div class="container-fluid mainpage"><!--MAINPAGE DIV START-->
			<div class="navbar">
			<div class="col-md-8 col-sm-8 col-xs-6">
				<h3 class="logo-image"><img src="<?php echo get_option( 'gmgt_system_logo' ) ?>" class="img-circle head_logo" width="40" height="40" />
				<span><?php echo get_option( 'gmgt_system_name' );?> </span>
				</h3>
			</div>
				<ul class="nav navbar-right col-md-4 col-sm-4 col-xs-6">
						<!-- BEGIN USER LOGIN DROPDOWN -->
						<li class="dropdown"><a data-toggle="dropdown"
							class="dropdown-toggle" href="javascript:;">
								<?php
								$userimage = get_user_meta( $user->ID,'gmgt_user_avatar',true );
								if (empty ( $userimage )){
									echo '<img src='.get_option( 'gmgt_system_logo' ).' height="40px" width="40px" class="img-circle" />';
								}
								else	
									echo '<img src=' . $userimage . ' height="40px" width="40px" class="img-circle"/>';
								?>
									<span>	<?php echo $user->display_name;?> </span> <b class="caret"></b>
						</a>
							<ul class="dropdown-menu extended logout">
								<li><a href="?dashboard=user&page=account"><i class="fa fa-user"></i>
										<?php _e('My Profile','gym_mgt');?></a></li>
								<li><a href="<?php echo wp_logout_url(home_url()); ?>"><i
										class="fa fa-sign-out m-r-xs"></i><?php _e('Log Out','gym_mgt');?> </a></li>
							</ul>
						</li>
						<!-- END USER LOGIN DROPDOWN -->
				</ul>
			
			</div>
		</div><!--MAINPAGE DIV END-->
		<div class="container-fluid">
		<div class="row"><!--ROW DIV START-->
			<div class="col-sm-2 nopadding gym_left nav-side-menu">	<!--  Left Side -->
				<div class="brand"><?php _e('Menu',''); ?>    
					<i data-target="#menu-content" data-toggle="collapse" 
					class="fa fa-bars fa-2x toggle-btn collapsed" aria-expanded="false"></i>
				</div>
				<?php				
				$menu = MJ_gmgt_userwise_access_right();
				
				$class = "";
				if (! isset ( $_REQUEST ['page'] ))	
					$class = 'class = "active"';
					?>
				<ul class="nav nav-pills nav-stacked collapsed collapse" id="menu-content">
							<li><a href="<?php echo site_url();?>"><span class="icone"><img src="<?php echo plugins_url( 'gym-management/assets/images/icon/home.png' )?>"/></span><span class="title"><?php _e('Home','gym_mgt');?></span></a></li>
							<li <?php echo $class;?>><a href="?dashboard=user"><span class="icone"><img src="<?php echo plugins_url('gym-management/assets/images/icon/dashboard.png' )?>"/></span><span
									class="title"><?php _e('Dashboard','gym_mgt');?></span></a></li>
									<?php											
										$role = $obj_gym->role;
										$access_page_view_array=array();
										if(!empty($menu))	
										{											
											foreach ( $menu as $key1=>$value1 ) 
											{									
												foreach ( $value1 as $key=>$value ) 
												{														
													if($value['view']=='1')
													{
														$access_page_view_array[]=$value ['page_link'];
														
														if (isset ( $_REQUEST ['page'] ) && $_REQUEST ['page'] == $value ['page_link'])
															$class = 'class = "active"';
														else
															$class = "";	
														
														echo '<li ' . $class . '><a href="?dashboard=user&page=' . $value ['page_link'] . '" class="left-tooltip" data-tooltip="'. $value ['menu_title'] . '" title="'. $value ['menu_title'] . '"><span class="icone"> <img src="' .$value ['menu_icone'].'" /></span><span class="title">'.MJ_gmgt_change_menutitle($key).'</span></a></li>';
													}
												}									
											}
										}												
							?>
											
				</ul>
			</div>
			<div class="page-inner" style="min-height:1050px;"><!--PAGE INNER DIV START-->
					<div class="right_side <?php if(isset($_REQUEST['page']))echo $_REQUEST['page'];?>">
					   <?php 
					if (isset ( $_REQUEST ['page'] ))
					{
						require_once GMS_PLUGIN_DIR . '/template/' . $_REQUEST['page'] . '.php';
						return false;
					}?>
					<!---start new dashboard------>
					<?php  
					if($role == 'accountant')
					{
						$menu_user_wise=$menu['accountant'][$role];
					}
					else if($role == 'staff_member')
					{
					  $menu_user_wise=$menu['staff_member'][$role];
					}
					else
					{
						$menu_user_wise=$menu['member'][$role];
						 
					}
					?>
					<div class="row "><!-- Start Row2 -->
						
					<!-- Start Row2 -->
						<?php
						$page='member';
						$access=MJ_gmgt_page_access_rolewise_accessright_dashboard($page);
						if($access)
						{
						?>
							<div class="col-lg-2 col-md-2 col-xs-6 col-sm-6">
							<a href="<?php if($menu_user_wise){ echo home_url().'?dashboard=user&page=member';  }
							else
							{ echo "#"; }?>">
								<div class="panel info-box panel-white">
									<div class="panel-body member">
										<img src="<?php echo GMS_PLUGIN_URL."/assets/images/dashboard/Member_new.png"?>" class="dashboard_background">
										<img src="<?php echo GMS_PLUGIN_URL."/assets/images/dashboard/Member_new_black.png"?>" class="dashboard_background_second">
										<div class="info-box-stats">
											<p class="counter"><?php echo MJ_gmgt_count_total_member_dashboard_by_access_right($page);?> <span class="info-box-title"><?php echo esc_html( __( 'Members', 'gym_mgt' ) );?></span>
											</p>
										</div>
									</div>
								</div>
							</a>
							</div>
						<?php
						}
						if($obj_gym->role!='member')
						{
							$page='staff_member';
							$access=MJ_gmgt_page_access_rolewise_accessright_dashboard($page);
							if($access)
							{					
							?>					
								<div class="col-lg-2 col-md-2 col-xs-6 col-sm-6">
								<a href="<?php if($menu_user_wise){ echo home_url().'?dashboard=user&page=staff_member';  }
								else
								{ echo "#"; }?>">
									<div class="panel info-box panel-white">
										<div class="panel-body staff-member">
											<img src="<?php echo GMS_PLUGIN_URL."/assets/images/dashboard/Staff_Member.png"?>" class="dashboard_background">
											<img src="<?php echo GMS_PLUGIN_URL."/assets/images/dashboard/Staff_Member_black.png"?>" class="dashboard_background_second staff_black_img">
											<div class="info-box-stats">
												<p class="counter">
												<?php echo MJ_gmgt_count_total_staff_member_dashboard_by_access_right($page);?>
												<span class="info-box-title"><?php echo esc_html( __( 'Staff Members', 'gym_mgt' ) );?>
												</span>
												</p>
											</div>
										</div>
									</div>
								</a>
								</div>
								
							<?php
							}
						}	
						if($obj_gym->role!='member')
						{						
							$page='accountant';
							$access=MJ_gmgt_page_access_rolewise_accessright_dashboard($page);
							if($access)
							{					
							?>	
								<div class="col-lg-2 col-md-2 col-xs-6 col-sm-6">
								<a href="<?php if($menu_user_wise){ echo home_url().'?dashboard=user&page=accountant';  }
								else
								{ echo "#"; }?>">
									<div class="panel info-box panel-white">
										<div class="panel-body accountant">
											<img src="<?php echo GMS_PLUGIN_URL."/assets/images/dashboard/Accountant_new.png"?>" class="dashboard_background">
											<img src="<?php echo GMS_PLUGIN_URL."/assets/images/dashboard/Accountant_new_black.png"?>" class="dashboard_background_second">
											<div class="info-box-stats">
												<p class="counter"><?php echo count(get_users(array('role'=>'accountant')));?><span class="info-box-title"><?php echo esc_html( __( 'Accountant', 'gym_mgt' ) );?></span>
												</p>
											</div>
										</div>
									</div>
								</a>
								</div>
								
							<?php
							}	
						}							
						$page='group';
						$access=MJ_gmgt_page_access_rolewise_accessright_dashboard($page);
						if($access)
						{					
						?>		
							<div class="col-lg-2 col-md-2 col-xs-6 col-sm-6">
							<a href="<?php if($menu_user_wise){ echo home_url().'?dashboard=user&page=group'; }
							else
							{ echo "#"; }?>">
								<div class="panel info-box panel-white">
									<div class="panel-body group">
										<img src="<?php echo GMS_PLUGIN_URL."/assets/images/dashboard/Group_new.png"?>" class="dashboard_background">
										<img src="<?php echo GMS_PLUGIN_URL."/assets/images/dashboard/Group_new_black.png"?>" class="dashboard_background_second">
										<div class="info-box-stats groups-label">
											<p class="counter"><?php echo MJ_gmgt_count_total_group_dashboard_by_access_right($page); ?> <span class="info-box-title"><?php echo esc_html( __( 'Groups', 'gym_mgt' ) );?></span></p>
										</div>	
									</div>
								</div>
								</a>
							</div>
						<?php
						}
						$page='message';
						$access=MJ_gmgt_page_access_rolewise_accessright_dashboard($page);
						if($access)
						{					
						?>		
							<div class="col-lg-2 col-md-2 col-xs-6 col-sm-6">
							<a href="<?php if($menu_user_wise){ echo home_url().'?dashboard=user&page=message&tab=inbox';  }
							else
							{ echo "#"; }?>">
								<div class="panel info-box panel-white">
									<div class="panel-body message">
										<img src="<?php echo GMS_PLUGIN_URL."/assets/images/dashboard/Message_new.png"?>" class="dashboard_background">
										<img src="<?php echo GMS_PLUGIN_URL."/assets/images/dashboard/Message_new_black.png"?>" class="dashboard_background_second">
										<div class="info-box-stats">
											<p class="counter"><?php echo count(MJ_gmgt_count_inbox_item(get_current_user_id()));?> <span class="info-box-title"><?php echo esc_html( __( 'Messages', 'gym_mgt' ) );?></span></p>
										</div>
										
									</div>
								</div>
								</a>
							</div>
						
						<?php
						}
						
						$page='membership';
						$access=MJ_gmgt_page_access_rolewise_accessright_dashboard($page);
						if($access)
						{	
						?>
							<div class="col-lg-2 col-md-2 col-xs-6 col-sm-6">
							<a href="<?php if($menu_user_wise){ echo home_url().'?dashboard=user&page=membership';  }
							else
							{ echo "#"; }?>">
								<div class="panel info-box panel-white">
									<div class="panel-body membership">
										<img src="<?php echo GMS_PLUGIN_URL."/assets/images/dashboard/Membership_new.png"?>" class="dashboard_background">
										<img src="<?php echo GMS_PLUGIN_URL."/assets/images/dashboard/Membership_new_black.png"?>" class="dashboard_background_second">
										<div class="info-box-stats">
											<p class="counter"><?php echo MJ_gmgt_count_total_membership_dashboard_by_access_right($page);?><span class="info-box-title"><?php echo esc_html( __( 'Memberships', 'gym_mgt' ) );?></span></p> 
										</div>
									</div>
								</div>
							</a>
							</div>
						<?php
						}
						$page='notice';
						$access=MJ_gmgt_page_access_rolewise_accessright_dashboard($page);
						if($access)
						{	
						?>	
							<div class="col-lg-2 col-md-2 col-xs-6 col-sm-6">
							<a href="<?php if($menu_user_wise){ echo home_url().'?dashboard=user&page=notice';  }
							else
							{ echo "#"; }?>">
								<div class="panel info-box panel-white">
									<div class="panel-body notice_event">
										<img src="<?php echo GMS_PLUGIN_URL."/assets/images/dashboard/Notice.png"?>" class="dashboard_background">
										<img src="<?php echo GMS_PLUGIN_URL."/assets/images/dashboard/Notice_black.png"?>" class="dashboard_background_second notice_black_img">
										<div class="info-box-stats">
											<p class="counter"><?php echo MJ_gmgt_count_total_notice_dashboard_by_access_right($page);?><span class="info-box-title"><?php echo esc_html( __( 'Notice', 'gym_mgt' ) );?></span></p> 
										</div>
									</div>
								</div>
							</a>
							</div>
						
						<?php
						}
						$page='class-schedule';
						$access=MJ_gmgt_page_access_rolewise_accessright_dashboard($page);
						if($access)
						{
						?>
							<div class="col-lg-2 col-md-2 col-xs-6 col-sm-6">
							<a href="<?php if($menu_user_wise){ echo home_url().'?dashboard=user&page=class-schedule';  }
							else
							{ echo "#"; }?>">
								<div class="panel info-box panel-white">
									<div class="panel-body class">
										<img src="<?php echo GMS_PLUGIN_URL."/assets/images/dashboard/Class.png"?>" class="dashboard_background">
										<img src="<?php echo GMS_PLUGIN_URL."/assets/images/dashboard/Class_black.png"?>" class="dashboard_background_second">
										<div class="info-box-stats">
											<p class="counter"><?php echo MJ_gmgt_count_total_class_dashboard_by_access_right($page);?> <span class="info-box-title"><?php echo esc_html( __( 'Class', 'gym_mgt' ) );?></span></p>
										</div>
									</div>
								</div>
							</a>
							</div>
						
						<?php
						}
						$page='reservation';
						$access=MJ_gmgt_page_access_rolewise_accessright_dashboard($page);
						if($access)
						{
						?>
							<div class="col-lg-2 col-md-2 col-xs-6 col-sm-6">
							<a href="<?php if($menu_user_wise){ echo home_url().'?dashboard=user&page=reservation';  }
							else
							{ echo "#"; }?>">
								<div class="panel info-box panel-white">
									<div class="panel-body nutrition">
										<img src="<?php echo GMS_PLUGIN_URL."/assets/images/dashboard/reservation.png"?>" class="dashboard_background">
										<img src="<?php echo GMS_PLUGIN_URL."/assets/images/dashboard/reservation_black.png"?>" class="dashboard_background_second">
										<div class="info-box-stats">
											<p class="counter"><?php echo MJ_gmgt_count_total_reservation_dashboard_by_access_right($page);?> <span class="info-box-title"><?php echo esc_html( __( 'Reservation', 'gym_mgt' ) );?></span></p>
										</div>
									</div>
								</div>
							</a>
							</div>	
							
						<?php
						}
						$page='product';
						$access=MJ_gmgt_page_access_rolewise_accessright_dashboard($page);
						if($access)
						{
						?>
							<div class="col-lg-2 col-md-2 col-xs-6 col-sm-6">
							<a href="<?php if($menu_user_wise){ echo home_url().'?dashboard=user&page=product';  }
							else
							{ echo "#"; }?>">
								<div class="panel info-box panel-white">
									<div class="panel-body product">
										<img src="<?php echo GMS_PLUGIN_URL."/assets/images/dashboard/Product.png"?>" class="dashboard_background">
										<img src="<?php echo GMS_PLUGIN_URL."/assets/images/dashboard/Product_black.png"?>" class="dashboard_background_second">
										<div class="info-box-stats">
											<p class="counter"><?php echo MJ_gmgt_count_total_product_dashboard_by_access_right($page);?> 
											<span class="info-box-title"><?php echo esc_html( __( 'Products', 'gym_mgt' ) );?>
											</span>
											</p>
										</div>
									</div>
								</div>
							</a>
							</div>		
							
						<?php
						}
						if($obj_gym->role!='member')
						{
						?>
							<div class="col-lg-2 col-md-2 col-xs-6 col-sm-6">
							<?php if($obj_gym->role=='staff_member')
							{ ?>
							<a href="<?php print site_url().'/?dashboard=user&page=attendence' ?>">
							<?php 
							}
							?>
								<div class="panel info-box panel-white">
									<div class="panel-body attendance">
										<img src="<?php echo GMS_PLUGIN_URL."/assets/images/dashboard/TodayAttendance.png"?>" class="dashboard_background">
										<img src="<?php echo GMS_PLUGIN_URL."/assets/images/dashboard/TodayAttendance_black.png"?>" class="dashboard_background_second">
										<div class="info-box-stats">
											<p class="counter"><?php echo $obj_dashboard->today_presents();?> <span class="info-box-title"><?php echo esc_html( __( 'Today Attendance', 'gym_mgt' ) );?></span></p>
										</div>
									</div>
								</div>
							</a>
							</div>	
						<?php
						}
						?>
						
					</div>	<!-- End Row2 -->
						<!--rinkal changes start entry list-->
					
					<?php if($obj_gym->role=='member')
					{
						
						?>
						<!---End new dashboard------>
					<div class="col-md-6 report_all"><!-- Start Row3 -->
							<?php 
							$work_out = $obj_gym->MJ_gmgt_get_today_workout(get_current_user_id());
							if(!empty($work_out))
							{	
								?>
							<div class="col-md-12 col-sm-6 col-xs-12" style="padding: 0px;">
								<div class="panel Today_workout_div panel-white">
									<div class="panel-heading workout_report">
										<h3 class="panel-title"><i class="fa fa-building" aria-hidden="true"></i><?php _e('Today Workout','gym_mgt');?></h3>	
										<ul class="nav navbar-right panel_toolbox">
											<li class="margin_dasboard"><a href="<?php echo home_url().'?dashboard=user&page=assign-workout&tab=workoutassignlist';?>"><i class="fa fa-external-link" aria-hidden="true"></i></a>
											</li>                  
										</ul>
									</div>
									<div class="panel-body">
									<table class="table table-borderless workout_today">
									 <thead>
										<tr>
										  <th scope="col" style="border-bottom: 0;border-bottom: 1px solid #f4f4f4;"><?php _e('Activity','gym_mgt');?></th>
										  <th scope="col" style="border-bottom: 0;border-bottom: 1px solid #f4f4f4;"><?php _e('Sets','gym_mgt');?></th>
										  <th scope="col" style="border-bottom: 0;border-bottom: 1px solid #f4f4f4;"><?php _e('Reps','gym_mgt');?></th>
										</tr>
									</thead>
									<tbody>
									<?php 
									foreach($work_out as $retrive)
									{
										echo "<tr><td>".$retrive->workout_name."</td>";
										echo "<td>"." Sets ".$retrive->sets."</td>";
										echo "<td>"." Reps ".$retrive->reps."</td></tr>";
									}
									?>
									</tbody>
									</table>
								</div>
								</div>
							</div>
						<?php 
						}
						$weight_data = $obj_gym->MJ_gmgt_get_weight_report('Weight',$user_id);
						$option =  $obj_gym->MJ_gmgt_report_option('Weight');
						require_once GMS_PLUGIN_DIR. '/lib/chart/GoogleCharts.class.php';
						$GoogleCharts = new GoogleCharts;
						$chart = $GoogleCharts->load( 'LineChart' , 'wait_reort' )->get( $weight_data , $option);	
						?>
							<div class="col-md-12 col-sm-6 col-xs-12" style="padding: 0px;">
								<div class="panel panel-white">
									<div class="panel-heading Weight_report">
										<h3 class="panel-title"><i class="fa fa-truck" aria-hidden="true"></i><?php _e('Weight Progress Report','gym_mgt');?></h3>						
									</div>
									<div class="panel-body">
										<?php 
										if(!empty($weight_data) && count($weight_data) > 1)
										{
											
										?>
										<div id="wait_reort" style="width: 100%; height: 250px;"></div>
									  
										  <!-- Javascript --> 
										  <script type="text/javascript" src="https://www.google.com/jsapi"></script> 
										  <script type="text/javascript">
													<?php echo $chart;?>
										  </script>
									  <?php 
										}
										if(empty($weight_data) || count($weight_data) == 1)
										{?>
											<div class="clear col-md-12 error_msg"><?php _e("No data available.",'gym_mgt');?>
											</div>
										<?php }?>	
									</div>
								</div>
							</div>
							<?php 
							$thigh_data = $obj_gym->MJ_gmgt_get_weight_report('Waist',$user_id);
							$option =  $obj_gym->MJ_gmgt_report_option('Waist');
							$GoogleCharts = new GoogleCharts;
							if(!empty($thigh_data))	
							$chart = $GoogleCharts->load( 'LineChart' , 'chart_div1' )->get( $thigh_data , $option );
							?>
							<div class="col-md-12 col-sm-6 col-xs-12" style="padding: 0px;">
								<div class="panel panel-white">
									<div class="panel-heading waist_report">
										<h3 class="panel-title"><i class="fa fa-area-chart"></i><?php _e('Waist Progress Report','gym_mgt');?></h3>						
									</div>
									<div class="panel-body">
										<?php 
										if(!empty($thigh_data) && count($thigh_data) > 1)
										{
											
										?>
											<div id="chart_div1" style="width: 100%; height: 250px;"></div>
									  
										  <!-- Javascript --> 
										  <script type="text/javascript" src="https://www.google.com/jsapi"></script> 
										  <script type="text/javascript">
													<?php echo $chart;?>
										  </script>
									  <?php 
										}
										if(empty($thigh_data) || count($thigh_data) == 1)
										{?>
											<div class="clear col-md-12 error_msg"><?php _e("No data available.",'gym_mgt');?></div>
										<?php }?>	
									</div>
									 
								</div>
							</div>
					 
						<?php 
							$height_data = $obj_gym->MJ_gmgt_get_weight_report('Height',$user_id);
							$option =  $obj_gym->MJ_gmgt_report_option('Height');
						
							$GoogleCharts = new GoogleCharts;
							$chart = $GoogleCharts->load( 'LineChart' , 'height_report' )->get( $height_data , $option );
						?>
							<div class="col-md-12 col-sm-6 col-xs-12" style="padding: 0px;">
								<div class="panel panel-white">
									<div class="panel-heading height_report">
										<h3 class="panel-title"><i class="fa fa-bar-chart" aria-hidden="true"></i><?php _e('Height Progress Report','gym_mgt');?></h3>						
									</div>
									<div class="panel-body">
										<?php 
										if(!empty($height_data) && count($height_data) > 1)
										{
											
										?>
											<div id="height_report" style="width: 100%; height: 250px;"></div>
									  
										  <!-- Javascript --> 
										  <script type="text/javascript" src="https://www.google.com/jsapi"></script> 
										  <script type="text/javascript">
													<?php echo $chart;?>
										  </script>
									  <?php 
										}
										if(empty($height_data) || count($height_data) == 1)
										{?>
											<div class="clear col-md-12 error_msg"><?php _e("No data available.",'gym_mgt'); ?></div>
										<?php }?>	
									</div>
								</div>
							 </div>
							<?php 
								$chest_data = $obj_gym->MJ_gmgt_get_weight_report('Chest',$user_id);
								$option =  $obj_gym->MJ_gmgt_report_option('Chest');
								$GoogleCharts = new GoogleCharts;
								$chart = $GoogleCharts->load( 'LineChart' , 'chart_chest' )->get( $chest_data , $option );
							
							?>
							<div class="col-md-12 col-sm-6 col-xs-12" style="padding: 0px;">
								<div class="panel panel-white">
									<div class="panel-heading chest_report">
										<h3 class="panel-title"><i class="fa fa-bar-chart" aria-hidden="true"></i><?php _e('Chest Progress Report','gym_mgt');?></h3>						
									</div>
									<div class="panel-body">
										<?php 
										if(!empty($chest_data) && count($chest_data) > 1)
										{
											
										?>
											<div id="chart_chest" style="width: 100%; height: 250px;"></div>
									  
										  <!-- Javascript --> 
										  <script type="text/javascript" src="https://www.google.com/jsapi"></script> 
										  <script type="text/javascript">
													<?php echo $chart;?>
										  </script>
									  <?php 
										}
										if(empty($chest_data) || count($chest_data) == 1)
										{?>
											<div class="clear col-md-12 error_msg"><?php _e("No data available.",'gym_mgt');?></div>
										<?php }?>	
									</div>
								</div>
							</div>
					   <!--THIGH REPORT  -->	
						<?php 
						$thigh_data = $obj_gym->MJ_gmgt_get_weight_report('Thigh',$user_id);
						$option =  $obj_gym->MJ_gmgt_report_option('Thigh');
						require_once GMS_PLUGIN_DIR. '/lib/chart/GoogleCharts.class.php';
						$GoogleCharts = new GoogleCharts;		
						$thigh_chart = $GoogleCharts->load( 'LineChart' , 'thigh_report' )->get( $thigh_data , $option );		
						?>
						<div class="col-md-12 col-sm-6 col-xs-12" style="padding: 0px;">
								<div class="panel panel-white">
									<div class="panel-heading thigh_report">
										<h3 class="panel-title"><i class="fa fa-bar-chart" aria-hidden="true"></i><?php _e('Thigh Progress Report','gym_mgt');?></h3>						
									</div>
									<div class="panel-body">
										<?php 
										if(!empty($thigh_data) && count($thigh_data) > 1)
										{
										?>
											<div id="thigh_report" style="width: 100%; height: 250px;"></div>
									  
										  <!-- Javascript --> 
										  <script type="text/javascript" src="https://www.google.com/jsapi"></script> 
										  <script type="text/javascript">
													<?php echo $thigh_chart;?>
										  </script>
									  <?php 
										}
										if(empty($thigh_data) || count($thigh_data) == 1)
										{?>
											<div class="clear col-md-12 error_msg"><?php _e("No data available.",'gym_mgt');?></div>
										<?php }?>	
									</div>
								</div>
						</div>
						<!--ARMS REPORT  -->	
						<?php 
						$arm_data = $obj_gym->MJ_gmgt_get_weight_report('Arms',$user_id);
						$option =  $obj_gym->MJ_gmgt_report_option('Arms');
						require_once GMS_PLUGIN_DIR. '/lib/chart/GoogleCharts.class.php';
						$GoogleCharts = new GoogleCharts;		
						$arm_chart = $GoogleCharts->load( 'LineChart' , 'arm_report' )->get( $arm_data , $option );
						?>
						<div class="col-md-12 col-sm-6 col-xs-12" style="padding: 0px;">
								<div class="panel panel-white">
									<div class="panel-heading arm_report">
										<h3 class="panel-title"><i class="fa fa-bar-chart" aria-hidden="true"></i><?php _e('Arms Progress Report','gym_mgt');?></h3>						
									</div>
									<div class="panel-body">
										<?php 
										if(!empty($arm_data) && count($arm_data) > 1)
										{
											
										?>
											<div id="arm_report" style="width: 100%; height: 250px;"></div>
									  
										  <!-- Javascript --> 
										  <script type="text/javascript" src="https://www.google.com/jsapi"></script> 
										  <script type="text/javascript">
													<?php echo $arm_chart;?>
										  </script>
									  <?php 
										}
										if(empty($arm_data) || count($arm_data) == 1)
										{?>
											<div class="clear col-md-12 error_msg"><?php _e("No data available.",'gym_mgt');?></div>
										<?php }?>	
									</div>
								</div>
						</div>
						<!--FAT REPORT -->	
						<?php 
						$fat_data = $obj_gym->MJ_gmgt_get_weight_report('Fat',$user_id);
						$option =  $obj_gym->MJ_gmgt_report_option('Fat');
						require_once GMS_PLUGIN_DIR. '/lib/chart/GoogleCharts.class.php';
						$GoogleCharts = new GoogleCharts;		
						$fat_chart = $GoogleCharts->load( 'LineChart' , 'fat_report' )->get( $fat_data , $option );		
						?>
						<div class="col-md-12 col-sm-6 col-xs-12" style="padding: 0px;">
								<div class="panel panel-white">
									<div class="panel-heading fat_report">
										<h3 class="panel-title"><i class="fa fa-bell" aria-hidden="true"></i><?php _e('Fat Progress Report','gym_mgt');?></h3>						
									</div>
									<div class="panel-body">
										<?php 
										if(!empty($fat_data) && count($fat_data) > 1)
										{
										?>
										<div id="fat_report" style="width: 100%; height: 250px;"></div>
									  
										  <!-- Javascript --> 
										  <script type="text/javascript" src="https://www.google.com/jsapi"></script> 
										  <script type="text/javascript">
													<?php echo $fat_chart;?>
										  </script>
									  <?php 
										}
										if(empty($fat_data) || count($fat_data) == 1)
										{?>
											<div class="clear col-md-12 error_msg"><?php _e("No data available.",'gym_mgt');?></div>
										<?php }?>	
									</div>
								</div>
						</div>
					</div>	
					
						<?php 
					}
					
					if($obj_gym->role!='member')
						{	
					?>
			<div class="col-md-6 staff_report_all">
					
			   <div class="col-md-12 col-sm-12 col-xs-12 report_panel" style="padding: 0px;">
					<div class="panel panel-body panel-white result_report student_report">
						<div class="panel-heading member_report">
							<h3 class="panel-title"><i class="fa fa-file-text" aria-hidden="true"></i><?php _e('Member Attendance Report','gym_mgt');?></h3>						
						</div>
							<?php 
							global $wpdb;
							$table_attendance = $wpdb->prefix .'gmgt_attendence';
							$table_class = $wpdb->prefix .'gmgt_class_schedule';
							$chart_array = array();
							$report_2 =$wpdb->get_results("SELECT  at.class_id,
									SUM(case when `status` ='Present' then 1 else 0 end) as Present,
									SUM(case when `status` ='Absent' then 1 else 0 end) as Absent
									from $table_attendance as at,$table_class as cl where at.attendence_date >  DATE_SUB(NOW(), INTERVAL 1 WEEK) AND at.class_id = cl.class_id  AND at.role_name = 'member' GROUP BY at.class_id") ;
									$chart_array[] = array(__('Class','gym_mgt'),__('Present','gym_mgt'),__('Absent','gym_mgt'));
									if(!empty($report_2))
										foreach($report_2 as $result)
										{
											$class_id =MJ_gmgt_get_class_name($result->class_id);
											$chart_array[] = array("$class_id",(int)$result->Present,(int)$result->Absent);
										}
										$options = Array(
															'title' => __('Member Attendance Report','gym_mgt'),
															'titleTextStyle' => Array('color' => '#4e5e6a','fontSize' => 16,'bold'=>false,'italic'=>false,'fontName' =>'-apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, "Helvetica Neue", sans-serif;'),
															'legend' =>Array('position' => 'right',
															'textStyle'=> Array('color' => '#4e5e6a','fontSize' => 13,'italic'=>false,'fontName' =>'-apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, "Helvetica Neue", sans-serif;')),
																
															'hAxis' => Array(
																				'title' =>  __('Class','gym_mgt'),
																				'titleTextStyle' => Array('color' => '#4e5e6a','fontSize' => 16,'italic'=>false,'fontName' =>'-apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, "Helvetica Neue", sans-serif;'),
																				'textStyle' => Array('color' => '#4e5e6a','fontSize' => 13,'italic'=>false,'fontName' =>'-apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, "Helvetica Neue", sans-serif;'),
																				'maxAlternation' => 2
																			),
															'vAxis' => Array(
																				'title' =>  __('No of Member','gym_mgt'),
																				'minValue' => 0,
																				'maxValue' => 4,
																				'format' => '#',
																				'titleTextStyle' => Array('color' => '#4e5e6a','fontSize' => 16,'italic'=>false,'fontName' =>'-apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, "Helvetica Neue", sans-serif;'),
																				'textStyle' => Array('color' => '#4e5e6a','fontSize' => 13,'italic'=>false,'fontName' =>'-apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, "Helvetica Neue", sans-serif;')
																			),
															'colors' => array('#22BAA0','#f25656')
																						);

					require_once GMS_PLUGIN_DIR. '/lib/chart/GoogleCharts.class.php';
					$GoogleCharts = new GoogleCharts;
					if(!empty($report_2))
					{
						$chart = $GoogleCharts->load( 'column' , 'attendance_report' )->get( $chart_array , $options );
					}
						if(isset($report_2) && count($report_2) >0)
						{
							
						?>
							<div id="attendance_report" style="width: 100%; height: 500px;"></div>
					  
						  <!-- Javascript --> 
						  <script type="text/javascript" src="https://www.google.com/jsapi"></script> 
						  <script type="text/javascript">
									<?php echo $chart;?>
							</script>
					  <?php 
						}
					 if(isset($report_2) && empty($report_2))
					 {?>
						<div class="clear col-md-12 error_msg"><?php _e("No data available.",'gym_mgt');?></div>
					<?php }?>
				 
				    </div>
				</div>
					
				<div class="col-md-12 col-sm-12 col-xs-12 report_panel" style="padding: 0px;">
					<div class="panel panel-body panel-white result_report student_report">
						<div class="panel-heading staff_report">
							<h3 class="panel-title"><i class="fa fa-users" aria-hidden="true"></i><?php _e('Staff Attendance Report','gym_mgt');?></h3>						
						</div>
								<?php 
								global $wpdb;
								$table_attendance = $wpdb->prefix .'gmgt_attendence';
								$table_class = $wpdb->prefix .'gmgt_class_schedule';
							
								$sdate = '2015-09-01';
								$edate = '2015-09-10';
								$chart_array = array();
								$report_3 =$wpdb->get_results("SELECT  at.user_id,
										SUM(case when `status` ='Present' then 1 else 0 end) as Present,
										SUM(case when `status` ='Absent' then 1 else 0 end) as Absent
										from $table_attendance as at where at.attendence_date >  DATE_SUB(NOW(), INTERVAL 1 WEEK)  AND at.role_name = 'staff_member' GROUP BY at.user_id") ;
								
								$chart_array[] = array(__('Staff Member','gym_mgt'),__('Present','gym_mgt'),__('Absent','gym_mgt'));
										if(!empty($report_3))
											foreach($report_3 as $result)
											{
								
											$user_name = MJ_gmgt_get_display_name($result->user_id);
											$chart_array[] = array("$user_name",(int)$result->Present,(int)$result->Absent);
								}
								$options = Array(
													'title' => __('Staff Attendance Report','gym_mgt'),
													'titleTextStyle' => Array('color' => '#4e5e6a','fontSize' => 16,'bold'=>false,'italic'=>false,'fontName' =>'-apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, "Helvetica Neue", sans-serif;'),
													'legend' =>Array('position' => 'right',
															'textStyle'=> Array('color' => '#4e5e6a','fontSize' => 13,'bold'=>false,'italic'=>false,'fontName' =>'-apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, "Helvetica Neue", sans-serif;')),
															'hAxis' => Array(
															'title' =>  __('Staff Member','gym_mgt'),
															'titleTextStyle' => Array('color' => '#4e5e6a','fontSize' => 16,'bold'=>false,'italic'=>false,'fontName' =>'-apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, "Helvetica Neue", sans-serif;'),
															'textStyle' => Array('color' => '#4e5e6a','fontSize' => 13,'bold'=>false,'italic'=>false,'fontName' =>'-apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, "Helvetica Neue", sans-serif;'),
															'maxAlternation' => 2
															),
															'vAxis' => Array(
															'title' =>  __('Number of Staff Members','gym_mgt'),
															'minValue' => 0,
															'maxValue' => 4,
															'format' => '#',
															'titleTextStyle' => Array('color' => '#4e5e6a','fontSize' => 16,'bold'=>false,'italic'=>false,'fontName' =>'-apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, "Helvetica Neue", sans-serif;'),
															'textStyle' => Array('color' => '#4e5e6a','fontSize' => 13,'bold'=>false,'italic'=>false,'fontName' =>'-apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, "Helvetica Neue", sans-serif;')
															),
															'colors' => array('#22BAA0','#f25656')
												);
						require_once GMS_PLUGIN_DIR. '/lib/chart/GoogleCharts.class.php';			 
					 $GoogleCharts = new GoogleCharts;
					if(!empty($report_3))
					{
						$chart = $GoogleCharts->load( 'column' , 'attendance_report_staff' )->get( $chart_array , $options );
					}
						if(isset($report_3) && count($report_3) >0)
						{
							
						?>
							<div id="attendance_report_staff" style="width: 100%; height: 500px;"></div>
					  
						  <!-- Javascript --> 
						  <script type="text/javascript" src="https://www.google.com/jsapi"></script> 
						  <script type="text/javascript">
									<?php echo $chart;?>
						  </script>
					  <?php 
						}
					 if(isset($report_3) && empty($report_3))
					 {?>
						<div class="clear col-md-12 error_msg"><?php _e("No data available.",'gym_mgt');?></div>
					<?php }?>
					 
				    </div>
			    </div>
			<div class="clear"></div>
					
			<div class="col-md-12 col-sm-12 col-xs-12 report_panel" style="padding: 0px;">
				<div class="panel panel-body panel-white result_report student_report">
						<div class="panel-heading fee_report">
							<h3 class="panel-title"><i class="fa fa-money" aria-hidden="true"></i><?php _e('Fee Payment Report','gym_mgt');?></h3>						
						</div> 
							<?php $month =array('1'=>"January ",'2'=>"February",'3'=>"March",'4'=>"April",'5'=>"May",'6'=>"June",'7'=>"July",'8'=>"August",
							'9'=>"September",'10'=>"October",'11'=>"November",'12'=>"December",);
									 
									$year =isset($_POST['year'])?$_POST['year']:date('Y');
									global $wpdb;

									$table_name = $wpdb->prefix."gmgt_membership_payment_history";
									$q="SELECT EXTRACT(MONTH FROM paid_by_date) as date,sum(amount) as count FROM ".$table_name." WHERE YEAR(paid_by_date) =".$year." group by month(paid_by_date) ORDER BY paid_by_date ASC";
									 
									 
									$result_payment=$wpdb->get_results($q);
								/* 	var_dump($result_payment);
									die; */
									$chart_array = array();
									$chart_array[] = array(__('Month','gym_mgt'),__('Fee Payment','gym_mgt'));
									foreach($result_payment as $r)
									{
										
									$chart_array[]=array( $month[$r->date],(int)$r->count);
									//$chart_array[]=array(date('F', mktime(0,0,0,$r->date)),(int)$r->count);
									}
									$options = Array(
												'title' => __('Fee Payment Report By Month','gym_mgt'),
												'titleTextStyle' => Array('color' => '#4e5e6a','fontSize' => 16,'bold'=>false,'italic'=>false,'fontName' =>'-apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, "Helvetica Neue", sans-serif;'),
												'legend' =>Array('position' => 'right',
												
												'textStyle'=> Array('color' => '#4e5e6a','fontSize' => 13,'bold'=>false,'italic'=>false,'fontName' =>'-apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, "Helvetica Neue", sans-serif;')),
												 
												'hAxis' => Array(
													'title' => __('Month','gym_mgt'),
													'Data Type'=>'date',
													 'format' => 'MMM',
													'titleTextStyle' => Array('color' => '#4e5e6a','fontSize' => 16,'bold'=>false,'italic'=>false,'fontName' =>'-apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, "Helvetica Neue", sans-serif;'),
													'textStyle'=> Array('color' => '#4e5e6a','fontSize' => 13,'bold'=>false,'italic'=>false,'fontName' =>'-apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, "Helvetica Neue", sans-serif;'),
													'maxAlternation' => 2

													),
												'vAxis' => Array(
													'title' => __('Fee Payment','gym_mgt'),
													 'minValue' => 0,
													'maxValue' => 6,
													 'format' => html_entity_decode($currency),
													'titleTextStyle' => Array('color' => '#4e5e6a','fontSize' => 16,'bold'=>false,'italic'=>false,'fontName' =>'-apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, "Helvetica Neue", sans-serif;'),
													'textStyle'=> Array('color' => '#4e5e6a','fontSize' => 13,'bold'=>false,'italic'=>false,'fontName' =>'-apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, "Helvetica Neue", sans-serif;')
													),
											'colors' => array('#22BAA0')
												);
									require_once GMS_PLUGIN_DIR. '/lib/chart/GoogleCharts.class.php';
									 
									
					$GoogleCharts = new GoogleCharts;
					if(!empty($result_payment))
					{
						$chart = $GoogleCharts->load( 'column' , 'chart_div_fees' )->get( $chart_array , $options ); 
					}
						if(isset($result_payment) && count($result_payment) >0)
						{
						?>
							<div id="chart_div_fees" style="width: 100%; height: 500px;"></div>
					  
						  <!-- Javascript --> 
						  <script type="text/javascript" src="https://www.google.com/jsapi"></script> 
						  <script type="text/javascript">
									<?php echo $chart;?>
							</script>
					  <?php 
						}
					 if(isset($result_payment) && empty($result_payment))
					 {?>
						<div class="clear col-md-12 error_msg"><?php _e("No data available .",'gym_mgt');?></div>
					<?php }?>
					 
				</div>
			</div>			
			<div class="col-md-12 col-sm-12 col-xs-12 report_panel" style="padding: 0px;">
				<div class="panel panel-body panel-white result_report student_report">
						<div class="panel-heading income_report">
							<h3 class="panel-title"><i class="fa fa-shopping-cart" aria-hidden="true"></i><?php _e('Income Payment Report','gym_mgt');?></h3>						
						</div>
							<?php 
							  $month =array('1'=>"January ",'2'=>"February",'3'=>"March",'4'=>"April",'5'=>"May",'6'=>"June",'7'=>"July",'8'=>"August",
							'9'=>"September",'10'=>"October",'11'=>"November",'12'=>"December",);	 
							$year =isset($_POST['year'])?$_POST['year']:date('Y');
							$currency=MJ_gmgt_get_currency_symbol(get_option( 'gmgt_currency_code' ));
							global $wpdb;
							$table_name = $wpdb->prefix."gmgt_income_payment_history";
							$table_name1 = $wpdb->prefix."gmgt_sales_payment_history";

							$q="SELECT EXTRACT(MONTH FROM paid_by_date) as date,sum(amount) as count FROM ".$table_name." WHERE YEAR(paid_by_date) =".$year." group by month(paid_by_date) ORDER BY paid_by_date ASC";

							$q1="SELECT EXTRACT(MONTH FROM paid_by_date) as date,sum(amount) as count FROM ".$table_name1." WHERE YEAR(paid_by_date) =".$year." group by month(paid_by_date) ORDER BY paid_by_date ASC";

							$result=$wpdb->get_results($q);
							$result1=$wpdb->get_results($q1);

							$result_merge_array=array_merge($result,$result1);

							$sumArray = array(); 
							foreach ($result_merge_array as $value) 
							{ 
								if(isset($sumArray[$value->date]))
								{
									$sumArray[$value->date] = $sumArray[$value->date] + (int)$value->count;
								}
								else
								{
									$sumArray[$value->date] = (int)$value->count; 
								}
										
							} 

							$chart_array = array();
							$chart_array[] = array(__('Month','gym_mgt'),__('Income Payment','gym_mgt'));
							$i=1;

							foreach($sumArray as $month_value=>$count)
							{
								/* var_dump($month_value);
								die; */
								$chart_array[]=array($month[$month_value],(int)$count);
								//$chart_array[]=array(date('F', mktime(0,0,0,$month_value)),(int)$count);
							}
							$options = Array(
										'title' => __('Income Payment Report By Month','gym_mgt'),
										'titleTextStyle' => Array('color' => '#4e5e6a','fontSize' => 16,'bold'=>false,'italic'=>false,'fontName' =>'-apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, "Helvetica Neue", sans-serif;'),
										'legend' =>Array('position' => 'right',
												
										'textStyle'=> Array('color' => '#4e5e6a','fontSize' => 13,'bold'=>false,'italic'=>false,'fontName' =>'-apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, "Helvetica Neue", sans-serif;')),
										'hAxis' => Array(
											'title' => __('Month','gym_mgt'),
											 'format' => '#',
											'titleTextStyle' => Array('color' => '#4e5e6a','fontSize' => 16,'bold'=>false,'italic'=>false,'fontName' =>'-apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, "Helvetica Neue", sans-serif;'),
											'textStyle'=> Array('color' => '#4e5e6a','fontSize' => 13,'bold'=>false,'italic'=>false,'fontName' =>'-apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, "Helvetica Neue", sans-serif;'),
											'maxAlternation' => 2
											
											),
										'vAxis' => Array(
											'title' => __('Income Payment','gym_mgt'),
											'minValue' => 0,
											'maxValue' => 6,
											'format' => html_entity_decode($currency),
											'titleTextStyle' => Array('color' => '#4e5e6a','fontSize' => 16,'bold'=>false,'italic'=>false,'fontName' =>'-apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, "Helvetica Neue", sans-serif;'),
											'textStyle'=> Array('color' => '#4e5e6a','fontSize' => 13,'bold'=>false,'italic'=>false,'fontName' =>'-apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, "Helvetica Neue", sans-serif;')
											),
									'colors' => array('#22BAA0')
										);
							require_once GMS_PLUGIN_DIR. '/lib/chart/GoogleCharts.class.php';
							$GoogleCharts = new GoogleCharts;
					if(!empty($result_merge_array))
					{
						$chart = $GoogleCharts->load( 'column' , 'chart_div_payment' )->get( $chart_array , $options );
					}
						if(isset($result_merge_array) && count($result_merge_array) >0)
						{
							
						?>
							<div id="chart_div_payment" style="width: 100%; height: 500px;"></div>
					  
						  <!-- Javascript --> 
						  <script type="text/javascript" src="https://www.google.com/jsapi"></script> 
						  <script type="text/javascript">
									<?php echo $chart;?>
							</script>
					  <?php 
						}
					 if(isset($result_merge_array) && empty($result_merge_array))
					 {?>
						<div class="clear col-md-12 error_msg"><?php _e("No data available.",'gym_mgt');?></div>
					<?php }?>
				 
			</div>
		</div>
				
		<div class="clear"></div>	
			
		<div class="col-md-12 col-sm-12 col-xs-12 report_panel" style="padding: 0px;">
			<div class="panel panel-body panel-white result_report student_report">
						<div class="panel-heading sells_report">
							<h3 class="panel-title"><i class="fa fa-bar-chart"></i><?php _e('Sells product Report','gym_mgt');?></h3>						
						</div>
							<?php 
						  $month =array('1'=>"January ",'2'=>"February",'3'=>"March",'4'=>"April",'5'=>"May",'6'=>"June",'7'=>"July",'8'=>"August",
							'9'=>"September",'10'=>"October",'11'=>"November",'12'=>"December",); 
							$year =isset($_POST['year'])?$_POST['year']:date('Y');
							global $wpdb;
							 
							$table_name = $wpdb->prefix."gmgt_store";
							$q="SELECT * FROM ".$table_name." WHERE YEAR(sell_date) =".$year." AND payment_status='Fully Paid' ORDER BY sell_date ASC";

							$result_sell=$wpdb->get_results($q);
							 
							$month_wise_count=array();
							foreach($result_sell as $key=>$value)
							{
								$total_quantity=0;
								$all_entry=json_decode($value->entry);
								foreach($all_entry as $entry)
								{
									$total_quantity+=$entry->quentity;
								}	
								$sell_date = date_parse_from_format("Y-m-d",$value->sell_date);
								
								$month_wise_count[]=array('sell_date'=>$sell_date["month"],'quentity'=>$total_quantity);
							}
							$sumArray = array(); 
							foreach ($month_wise_count as $value1) 
							{ 
								$value2=(object)$value1;
								if(isset($sumArray[$value2->sell_date]))
								{
									$sumArray[$value2->sell_date] = $sumArray[$value2->sell_date] + (int)$value2->quentity;
								}
								else
								{
									$sumArray[$value2->sell_date] = (int)$value2->quentity; 
								}		
							} 
							$chart_array = array();
							$chart_array[] = array('Month','Sells Product');
							foreach($sumArray as $month_value=>$quentity)
							{
								$chart_array[]=array( $month[$month_value],(int)$quentity);
								//$chart_array[]=array(date('F', mktime(0,0,0,$month_value)),(int)$quentity);
							}
							$options = Array(
										'title' => __('Sells Product Report By Month','gym_mgt'),
										'titleTextStyle' => Array('color' => '#4e5e6a','fontSize' => 16,'bold'=>false,'italic'=>false,'fontName' =>'-apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, "Helvetica Neue", sans-serif;'),
										'legend' =>Array('position' => 'right',
												
										'textStyle'=> Array('color' => '#4e5e6a','fontSize' => 13,'bold'=>false,'italic'=>false,'fontName' =>'-apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, "Helvetica Neue", sans-serif;')),

										'hAxis' => Array(
											'title' => __('Month','gym_mgt'),
											'format' => '#',
											'titleTextStyle' => Array('color' => '#4e5e6a','fontSize' => 16,'bold'=>false,'italic'=>false,'fontName' =>'-apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, "Helvetica Neue", sans-serif;'),
											'textStyle'=> Array('color' => '#4e5e6a','fontSize' => 13,'bold'=>false,'italic'=>false,'fontName' =>'-apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, "Helvetica Neue", sans-serif;'),
											'maxAlternation' => 2				
											),
										'vAxis' => Array(
											'title' => __('Sells Product','gym_mgt'),
											 'minValue' => 0,
											'maxValue' => 6,
											 'format' => '#',
											'titleTextStyle' => Array('color' => '#4e5e6a','fontSize' => 16,'bold'=>false,'italic'=>false,'fontName' =>'-apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, "Helvetica Neue", sans-serif;'),
											'textStyle'=> Array('color' => '#4e5e6a','fontSize' => 13,'bold'=>false,'italic'=>false,'fontName' =>'-apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, "Helvetica Neue", sans-serif;')
											),
									'colors' => array('#22BAA0')
										);
							require_once GMS_PLUGIN_DIR. '/lib/chart/GoogleCharts.class.php';
							 
							$GoogleCharts = new GoogleCharts;
					if(!empty($result_sell))
					{
						$chart = $GoogleCharts->load( 'column' , 'chart_div_sell' )->get( $chart_array , $options);
					}
						if(isset($result_sell) && count($result_sell) >0)
						{
							
						?>
						  <div id="chart_div_sell" style="width: 100%; height: 500px;">
						  </div>
					  
						  <!-- Javascript --> 
						  <script type="text/javascript" src="https://www.google.com/jsapi"></script> 
						  <script type="text/javascript">
									<?php echo $chart;?>
						  </script>
					  <?php 
						}
					 if(isset($result_sell) && empty($result_sell))
					 {?>
						<div class="clear col-md-12 error_msg"><?php _e("No data available.",'gym_mgt');?></div>
					<?php }?>
				 
			</div>
		</div>
		
		
		<!--rinkal changes end dashboard entry list-->
				<div class="col-md-12 col-sm-12 col-xs-12 report_panel" style="padding: 0px;">
					<div class="panel panel-body panel-white result_report student_report">
						<div class="panel-heading expense_report">
							<h3 class="panel-title"><i class="fa fa-user" aria-hidden="true"></i><?php _e('Membership Report','gym_mgt');?></h3>						
						</div>
							<?php 
							global $wpdb;
							 $table_name = $wpdb->prefix."gmgt_membershiptype";
							 $q="SELECT * From $table_name";
							 $member_ship_array = array();
							 $result_membership=$wpdb->get_results($q);
								foreach($result_membership as $retrive)
								{
									$membership_id = $retrive->membership_id;		
									$member_ship_count =  count(get_users(array('role'=>'member','meta_key' => 'membership_id', 'meta_value' => $retrive->membership_id)));
									$member_ship_array[] = array('member_ship_id'=>$membership_id,
																 'member_ship_count'=>	$member_ship_count							
																);
								}
							$chart_array = array();
							$chart_array[] = array(__('Membership','gym_mgt'),__('Number Of Member','gym_mgt'));	
							foreach($member_ship_array as $r)
							{
								$chart_array[]=array( MJ_gmgt_get_membership_name($r['member_ship_id']),$r['member_ship_count']);
							}
							$options = Array(
									'title' => __('Membership Report','gym_mgt'),
									'titleTextStyle' => Array('color' => '#4e5e6a','fontSize' => 16,'bold'=>false,'italic'=>false,'fontName' =>'-apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, "Helvetica Neue", sans-serif;'),
										'legend' =>Array('position' => 'right',
												
										'textStyle'=> Array('color' => '#4e5e6a','fontSize' => 13,'bold'=>false,'italic'=>false,'fontName' =>'-apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, "Helvetica Neue", sans-serif;')),

									'hAxis' => Array(
											'title' =>  __('Membership Name','gym_mgt'),
											'format' => '#',
											'titleTextStyle' => Array('color' => '#4e5e6a','fontSize' => 16,'bold'=>false,'italic'=>false,'fontName' =>'-apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, "Helvetica Neue", sans-serif;'),
											'textStyle'=> Array('color' => '#4e5e6a','fontSize' => 13,'bold'=>false,'italic'=>false,'fontName' =>'-apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, "Helvetica Neue", sans-serif;'),
											'maxAlternation' => 2


									),
									'vAxis' => Array(
											'title' =>  __('No of Member','gym_mgt'),
											'minValue' => 0,
											'maxValue' => 6,
											'format' => '#',
											'titleTextStyle' => Array('color' => '#4e5e6a','fontSize' => 16,'bold'=>false,'italic'=>false,'fontName' =>'-apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, "Helvetica Neue", sans-serif;'),
											'textStyle'=> Array('color' => '#4e5e6a','fontSize' => 13,'bold'=>false,'italic'=>false,'fontName' =>'-apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, "Helvetica Neue", sans-serif;'),
											
									),
									'colors' => array('#22BAA0')
							);
							require_once GMS_PLUGIN_DIR. '/lib/chart/GoogleCharts.class.php';
							
							$GoogleCharts = new GoogleCharts;
					if(!empty($result_membership))
					{
						$chart = $GoogleCharts->load( 'column' , 'chart_div_membership' )->get( $chart_array , $options );
					}
						if(isset($result_membership) && count($result_membership) >0)
						{
							
						?>
							<div id="chart_div_membership" style="width: 100%; height: 500px;"></div>
					  
						  <!-- Javascript --> 
						  <script type="text/javascript" src="https://www.google.com/jsapi"></script> 
						  <script type="text/javascript">
									<?php echo $chart;?>
							</script>
					  <?php 
						}
					 if(isset($result_membership) && empty($result_membership))
					 {?>
						<div class="clear col-md-12 error_msg"><?php _e("No data available.",'gym_mgt');?></div>
					<?php }?>
					</div>
				</div>
		
			
	</div>
	<?php
		}
		?>
				<div class="row">

						<?php
						 if($obj_gym->role=='member')
						{
							$page='nutrition';
							$access=MJ_gmgt_page_access_rolewise_accessright_dashboard($page);
							if($access)
							{
								$nutrition_data = $obj_gym->MJ_gmgt_get_today_nutrition(get_current_user_id());
								/* var_dump($nutrition_data);
								die; */
								if(!empty($nutrition_data))
								{	
								?>
							<div class="col-md-6 col-sm-12 col-xs-12">
								<div class="panel dashboard_height panel-white">
									<div class="panel-heading nutrition">
										<h3 class="panel-title"><i class="fa fa-briefcase" aria-hidden="true"></i><?php _e('Today Nutrition','gym_mgt');?></h3>
										<ul class="nav navbar-right panel_toolbox">
											<li class="margin_dasboard"><a href="<?php echo home_url().'?dashboard=user&page=nutrition';?>"><i class="fa fa-external-link" aria-hidden="true"></i></a>
											</li>                  
										</ul>
									</div>
									<div class="panel-body">
										<table class="table table-borderless activity_btn">
											 <thead>
													<tr>
													  <th scope="col" style="border-bottom: 0;border-bottom: 1px solid #f4f4f4;"><?php _e(' Time','gym_mgt');?></th>
													  <th scope="col" style="border-bottom: 0;border-bottom: 1px solid #f4f4f4;"><?php _e('Description','gym_mgt');?></th>
													</tr>
											 </thead>
											<tbody>
												<?php
												foreach($nutrition_data as $retrieved_data)
												{
												 ?>
												<tr>
													<td class="unit"><?php echo $retrieved_data->nutrition_time;?><br/></td>
													<td class="unit"><?php echo $retrieved_data->nutrition_value;?><br/></td>
												</tr>
												<?php 
												}
												?>
											</tbody>
										</table>
									</div>
								</div>
							</div>
						<?php
								}
							}
						}
						$page='activity';
						$access=MJ_gmgt_page_access_rolewise_accessright_dashboard($page);
						if($access)
						{					
						?>	
						<div class="col-md-6 col-sm-12 col-xs-12">							
							<div class="panel dashboard_height  panel-white activet">
								<div class="panel-heading activities">
									<h3 class="panel-title"><i class="fa fa-truck" aria-hidden="true"></i><?php _e('Activities','gym_mgt');?></h3>
									<ul class="nav navbar-right panel_toolbox">
										<li class="margin_dasboard"><a href="<?php echo home_url().'?dashboard=user&page=activity';?>"><i class="fa fa-external-link" aria-hidden="true"></i></a>
										</li>                  
									</ul>
								</div>
									<div class="panel-body">
										<table class="table table-borderless activity_btn">
									<?php 
											$obj_activity=new MJ_Gmgtactivity;
											$user_access=MJ_gmgt_get_userrole_wise_manually_page_access_right_array($page);
											if($user_access['own_data']=='1')
											{
												$user_id=get_current_user_id();							
												$activitydata=$obj_activity->MJ_gmgt_get_all_activity_by_activity_added_by_dashboard($user_id);
											}
											else
											{
												$activitydata=$obj_activity->MJ_get_all_activity_dashboard();
											}
											if(!empty($activitydata))
											{ ?>
											  <thead>
												<tr>
												  <th scope="col" style="border-bottom: 0;border-bottom: 1px solid #f4f4f4;"><?php _e('Activity Name','gym_mgt');?></th>
												  <th scope="col" style="border-bottom: 0;border-bottom: 1px solid #f4f4f4;"><?php _e('Activity Category','gym_mgt');?></th>
												  <th scope="col" style="border-bottom: 0;border-bottom: 1px solid #f4f4f4;"><?php _e('Activity Trainer','gym_mgt');?></th>
												</tr>
											  </thead>
											  <tbody>
											  <?php
													foreach ($activitydata as $retrieved_data)
													{
													?>	
												<tr>
												  <td class="unit">	<?php echo $retrieved_data->activity_title;?></td>
												  <td class="unit"><?php echo get_the_title($retrieved_data->activity_cat_id);?></td>
												  <td class="unit"><?php 
													$user=get_userdata($retrieved_data->activity_assigned_to);
													echo $user->display_name;?> 
												  </td>
												</tr>
													<?php
													}  ?>
											  </tbody>
									<?php
											} 
											else 
												_e("No Upcoming Activity",'gym_mgt');
											?>		
										 </table>
									</div>
							</div>
						</div>
						<?php
						}
						
						$page='group';
						$access=MJ_gmgt_page_access_rolewise_accessright_dashboard($page);
						if($access)
						{	 					
						?>	
						<div class="col-md-6 col-sm-12 col-xs-12">		
							 	<div class="panel panel-white dashboard_height group_btn">
									<div class="panel-heading grp_list">
										<h3 class="panel-title"><i class="fa fa-users" aria-hidden="true"></i><?php _e('Group List','gym_mgt');?></h3>
										<ul class="nav navbar-right panel_toolbox">
											<li class="margin_dasboard"><a href="<?php echo home_url().'?dashboard=user&page=group';?>"><i class="fa fa-external-link" aria-hidden="true"></i></a>
											</li>                  
										</ul>
									</div>
									<div class="panel-body grp">
										<div class="events">
										<?php 
										$obj_group=new MJ_Gmgtgroup;
										$user_access=MJ_gmgt_get_userrole_wise_manually_page_access_right_array($page);
										if($obj_gym->role == 'member')
											{	
												if($user_access['own_data']=='1')
												{
													$user_id=get_current_user_id();
													$groupdata=$obj_group->MJ_gmgt_get_member_all_groups_dashboard($user_id);			
												}
												else
												{
													$groupdata=$obj_group->MJ_gmgt_get_all_groups_dashboard();
												}	
											}
											elseif($obj_gym->role == 'staff_member' || $obj_gym->role == 'accountant')
											{
												if($user_access['own_data']=='1')
												{
													$user_id=get_current_user_id();							
													$groupdata=$obj_group->MJ_gmgt_get_all_groups_by_created_by_dashboard($user_id);			
												}
												else
												{
													$groupdata=$obj_group->MJ_gmgt_get_all_groups_dashboard();
												}
											}
										
										if(!empty($groupdata))
										{ 
										?>
									  <?php
										foreach ($groupdata as $retrieved_data)
										{
											$group_count=$obj_group->MJ_gmgt_count_group_members($retrieved_data->id);
										?>
								<div class="calendar-event view-complaint"> 
								<p class="remainder_title_pr Bold show_task_event" id="<?php echo $retrieved_data->id;?>" model="Group Details"><?php _e(' Group Name : ','gym_mgt');?>
								<?php echo $retrieved_data->group_name;?></p>							
								<?php if(!empty($group_count) ) { ?>
										  <span class="btn btn-success btn-xs"><?php echo $group_count;?></span></td>
										   <?php 
											}
											else{
												?>  <span class="btn btn-success btn-xs"><?php echo "0";?></span><?php
											}
											?>
								<p class="">
								 <?php _e('Description : ','gym_mgt');?> 
								<?php $strlength= strlen($retrieved_data->group_description);
									if($strlength > 90)
										echo substr($retrieved_data->group_description, 0,90).'...';
									else
										echo $retrieved_data->group_description;?></p>
								</div>	
								<?php 
									}
								?>
									
						  <?php
								} 
								else 
									_e("No Upcoming group",'gym_mgt');
								?>
										</div>
									</div>
							</div>
					</div>
				<?php
				}
				$page='membership';
				$access=MJ_gmgt_page_access_rolewise_accessright_dashboard($page);
				if($access)
				{	 					
				?>	
				<div class="col-md-6 membership-list col-sm-12 col-xs-12">
					<div class="panel panel-white dashboard_height membership_btn">
						<div class="panel-heading membership">
							<h3 class="panel-title"><i class="fa fa-user" aria-hidden="true"></i><?php _e('Membership','gym_mgt');?></h3>
							<ul class="nav navbar-right panel_toolbox">
								<li class="margin_dasboard"><a href="<?php echo home_url().'?dashboard=user&page=membership';?>"><i class="fa fa-external-link" aria-hidden="true"></i></a>
								</li>                  
							</ul>
						</div>
						<div class="panel-body">
						<table class="table table-borderless">
						<?php 
							$obj_membership=new MJ_Gmgtmembership;
							$user_access=MJ_gmgt_get_userrole_wise_manually_page_access_right_array($page);
							if($obj_gym->role == 'member')
							{	
								if($user_access['own_data']=='1')
								{
									$user_id=get_current_user_id();
									$membership_id = get_user_meta( $user_id,'membership_id', true ); 
									$membershipdata=$obj_membership->MJ_gmgt_get_member_own_membership_dashboard($membership_id);			
								}
								else
								{
									$membershipdata=$obj_membership->MJ_gmgt_get_all_membership_dashboard();
								}	
							}
							elseif($obj_gym->role == 'staff_member' || $obj_gym->role == 'accountant')
							{
								if($user_access['own_data']=='1')
								{
									$user_id=get_current_user_id();							
									$membershipdata=$obj_membership->MJ_gmgt_get_membership_by_created_by_dashboard($user_id);			
								}
								else
								{
									$membershipdata=$obj_membership->MJ_gmgt_get_all_membership_dashboard();
								}
							}
							if(!empty($membershipdata))
							{
							?>
									  <thead>
										<tr>
										  <th scope="col" style="border-bottom: 0;border-bottom: 1px solid #f4f4f4;"><?php _e('Membership Name','gym_mgt');?></th>
										  <th scope="col" style="border-bottom: 0;border-bottom: 1px solid #f4f4f4;"><?php _e('Membership Period(Days)','gym_mgt');?></th>
										  <th scope="col" style="border-bottom: 0;border-bottom: 1px solid #f4f4f4;"><?php _e('Amount','gym_mgt');?>(<?php echo MJ_gmgt_get_currency_symbol(get_option( 'gmgt_currency_code' )); ?>)</th>
										</tr>
									  </thead>
									  
									  <tbody>
									   <?php
											foreach ($membershipdata as $retrieved_data)
											{
											?>
								   		<tr>
										  <td class="unit remainder_title_pr_cursor show_task_event " id="<?php echo $retrieved_data->membership_id;?>" model="Membership Details"><?php echo $retrieved_data->membership_label;  ?></td>
										  <td class="unit"><?php echo $retrieved_data->membership_length_id; ?></td>
										  <td class="unit"><span class="btn btn-success btn-xs"><?php echo $retrieved_data->membership_amount; ?></span></td>
										</tr>
										<?php
											}
											?>
									  </tbody>
								   <?php
							} 
							else 
								_e("No Upcoming Membership",'gym_mgt');
							?>
						</table>
						</div>
					</div>
				</div>
				<?php
				}
				$page='payment';
				$access=MJ_gmgt_page_access_rolewise_accessright_dashboard($page);
				if($access)
				{	  
				?>
				<div class="col-md-6 col-sm-12 col-xs-12">		
					<div class="panel panel-white dashboard_height invoice_btn">
							<div class="panel-heading invoice">
								<h3 class="panel-title"><i class="fa fa-list-alt" aria-hidden="true"></i><?php _e('Invoice List','gym_mgt');?>
								</h3>
								<ul class="nav navbar-right panel_toolbox">
									<li class="margin_dasboard"><a href="<?php echo home_url().'?dashboard=user&page=payment&tab=incomelist';?>"><i class="fa fa-external-link" aria-hidden="true"></i></a>
									</li>                  
								</ul>
							</div>
							<div class="panel-body">
								<div class="events">
									<table class="table table-borderless">
										  <?php 
											$obj_payment=new MJ_Gmgtpayment;
											$user_access=MJ_gmgt_get_userrole_wise_manually_page_access_right_array($page);
											if($obj_gym->role == 'member')
											{
												if($user_access['own_data']=='1')
												{
													$paymentdata=$obj_payment->MJ_gmgt_get_all_income_data_by_member_dashboard();		
												}
												else
												{
													$paymentdata=$obj_payment->MJ_gmgt_get_all_income_data_dashboard();
												}							
											}
											else
											{
												if($user_access['own_data']=='1')
												{
													$user_id=get_current_user_id();
													$paymentdata=$obj_payment->MJ_gmgt_get_all_income_data_by_created_by_dashboard($user_id);		
												}
												else
												{
													$paymentdata=$obj_payment->MJ_gmgt_get_all_income_data_dashboard();
												}							
											}	
											 
											if(!empty($paymentdata))
											{
											?>
											  <thead>
												<tr>
												  <th scope="col" style="border-bottom: 0;    border-bottom: 1px solid #f4f4f4;"><?php _e('Invoice No','gym_mgt');?></th>
												  <th scope="col" style="border-bottom: 0;    border-bottom: 1px solid #f4f4f4;"><?php _e('Member Name','gym_mgt');?></th>
												  <th scope="col" style="border-bottom: 0;    border-bottom: 1px solid #f4f4f4;"><?php _e('Total Amount','gym_mgt');?>(<?php echo MJ_gmgt_get_currency_symbol(get_option( 'gmgt_currency_code' )); ?>)</th>
												  <th scope="col" style="border-bottom: 0;    border-bottom: 1px solid #f4f4f4;"><?php _e('Payment Status','gym_mgt');?></th>
												  
												</tr>
											  </thead>
											  <tbody>
											  <?php
											  foreach ($paymentdata as $retrieved_data)
												{
													?>
												<tr>
													<td class="unit remainder_title_pr_cursor show_task_event " id="<?php echo $retrieved_data->invoice_id;?>" model="Invoice Details"><?php echo $retrieved_data->invoice_no;?></td>
													<td class="unit">
													<?php 	$user=get_userdata($retrieved_data->supplier_name);
															$memberid=get_user_meta($retrieved_data->supplier_name,'member_id',true);
															$display_label=$user->display_name;
															if($memberid)
															$display_label.=" (".$memberid.")";
															echo $display_label;
													?>
													</td>
													<td class="unit"><?php echo MJ_gmgt_get_currency_symbol(get_option( 'gmgt_currency_code' )); ?><?php echo number_format($retrieved_data->total_amount,2); ?> </td>
													<td class="unit"><span class="btn btn-success btn-xs"><?php echo $retrieved_data->payment_status; ?></span></td>
												 </tr>
											 <?php
												}
												?>
											  </tbody>
										<?php
												
										} 
										else 
											_e("No Upcoming Invoice",'gym_mgt');
										?>
								</table>
							</div>
						</div>
					</div>
				</div>
				<?php
				}
				?>
				
				<?php
				$page='reservation';
				$access=MJ_gmgt_page_access_rolewise_accessright_dashboard($page);
				if($access)
				{	  
				?>
					<div class="col-md-6 col-sm-12 col-xs-12">		
						<!--rinkal changes start entry list-->	
						<div class="panel dashboard_height  panel-white">
							<div class="panel-heading res_list">
								<h3 class="panel-title"><i class="fa fa-ticket" aria-hidden="true"></i><?php _e('Reservation List','gym_mgt');?></h3>	
								<ul class="nav navbar-right panel_toolbox">
									<li class="margin_dasboard"><a href="<?php echo home_url().'?dashboard=user&page=reservation';?>"><i class="fa fa-external-link" aria-hidden="true"></i></a>
									</li>                  
								</ul>
							</div>
							<div class="panel-body">
									
									<div class="events">
								<?php 
									$obj_reservation=new MJ_Gmgtreservation;
									$user_access=MJ_gmgt_get_userrole_wise_manually_page_access_right_array($page);
									if($user_access['own_data']=='1')
									{
										$reservationdata=$obj_reservation->MJ_gmgt_get_reservation_by_created_by_dashboard();
									}
									else
									{
										$reservationdata=$obj_reservation->MJ_gmgt_get_all_reservation_dashboard();
									}	
									if(!empty($reservationdata))
									{
										foreach ($reservationdata as $retrieved_data)
										{
											?>
										<div class="calendar-event view-complaint"> 
											<p class="remainder_title_pr Bold viewpriscription show_task_event" id="<?php echo $retrieved_data->id;?>" model="Reservation Details"><?php _e('Event Name :','gym_mgt');?>
											<?php echo $retrieved_data->event_name;?>							
											</p><p class="remainder_date_pr"><?php echo MJ_gmgt_getdate_in_input_box($retrieved_data->event_date);?></p>
											<p class="">
											<?php _e('Event Place :','gym_mgt');?> 
											<?php echo  get_the_title( $retrieved_data->place_id );?></p>
										</div>	
									<?php
										}	
									} 
									else 
										_e("No Upcoming Reservation",'gym_mgt');
									?>
								</div>
							</div>
						</div>
					</div>
				<?php
				}
				$page='notice';
				$access=MJ_gmgt_page_access_rolewise_accessright_dashboard($page);
				if($access)
				{	  
				?>				
					<div class="col-md-6 col-sm-12 col-xs-12">			
						<div class="panel dashboard_height panel-white">
							<div class="panel-heading nt_ev">
								<h3 class="panel-title"><i class="fa fa-calendar-o" aria-hidden="true"></i><?php _e('Notice','gym_mgt');?></h3>
								<ul class="nav navbar-right panel_toolbox">
									<li class="margin_dasboard"><a href="<?php echo home_url().'?dashboard=user&page=notice';?>"><i class="fa fa-external-link" aria-hidden="true"></i></a>
									</li>                  
								</ul>
							</div>
							<div class="panel-body">
								<div class="events">
									<?php
										$obj_notice=new MJ_Gmgtnotice;
										$user_access=MJ_gmgt_get_userrole_wise_manually_page_access_right_array($page);
										if($user_access['own_data']=='1')
										{
											$noticedata =$obj_notice->MJ_gmgt_get_notice_dashboard($obj_gym->role);
										}
										else	
										{
											$noticedata =$obj_notice->MJ_gmgt_get_all_notice_dashboard();
										}	
										if(!empty($noticedata))
										{
											foreach ($noticedata as $retrieved_data)
											{
												?>
										<div class="calendar-event"> 
											<p class="remainder_title Bold viewdetail show_task_event" id="<?php echo $retrieved_data->ID;?>" model="Notice Details">	
												<?php echo $retrieved_data->post_title;?>
											</p>
											<p class="remainder_date"><?php echo MJ_gmgt_getdate_in_input_box(get_post_meta($retrieved_data->ID,'gmgt_start_date',true));?> <b>|</b> <?php echo MJ_gmgt_getdate_in_input_box(get_post_meta($retrieved_data->ID,'gmgt_end_date',true));?></p>
											<p class="notice_des">
											<?php $strlength= strlen($retrieved_data->post_content);
											if($strlength > 90)
												echo substr($retrieved_data->post_content, 0,90).'...';
											else
												echo $retrieved_data->post_content;?></p>
										</div><?php
											}	
										} 
										else 
											_e("No Upcoming Notice",'gym_mgt');
										?>
								</div>
							</div>
						</div>
					</div>
				<?php
				}
				$page='class-schedule';
				$access=MJ_gmgt_page_access_rolewise_accessright_dashboard($page);
				if($access)
				{	  
				?>	
					<div class="col-md-6 col-sm-12 col-xs-12">	
						<div class="panel dashboard_height panel-white membership_btn">
							<div class="panel-heading class_list">
								<h3 class="panel-title"><i class="fa fa-list" aria-hidden="true"></i><?php _e('Class List','gym_mgt');?></h3>
								<ul class="nav navbar-right panel_toolbox">
									<li class="margin_dasboard"><a href="<?php echo home_url().'?dashboard=user&page=class-schedule&tab=classlist';?>"><i class="fa fa-external-link" aria-hidden="true"></i></a>
									</li>                  
								</ul>
							</div>
							<div class="panel-body">
								<table class="table table-borderless class_btn">
										<?php 
												$user_access=MJ_gmgt_get_userrole_wise_manually_page_access_right_array($page);
												$obj_class=new MJ_Gmgtclassschedule;
												//GET CLASS LIST DATA
												if($obj_gym->role == 'staff_member')
												{
													if($user_access['own_data']=='1')
													{
														$user_id=get_current_user_id();							
														$classdata_list=$obj_class->MJ_gmgt_get_all_classes_by_staffmember_dashboard($user_id);
														
													}
													else
													{
														$classdata_list=$obj_class->MJ_gmgt_get_all_classes_dashboard();
													}
												}
												elseif($obj_gym->role == 'member')
												{		
													if($user_access['own_data']=='1')
													{
														 
														$cur_user_class_id = array();	
														$curr_user_id=get_current_user_id();
														$cur_user_class_id = MJ_gmgt_get_current_user_classis($curr_user_id);				
														$classdata_list=$obj_class->MJ_gmgt_get_all_classes_by_member_dashboard($cur_user_class_id);
													}
													else
													{
														$classdata_list=$obj_class->MJ_gmgt_get_all_classes_dashboard();
													}
												}
												else
												{		
													if($user_access['own_data']=='1')
													{
														$user_id=get_current_user_id();							
														$classdata_list=$obj_class->MJ_gmgt_get_all_classes_by_class_created_id_dashboard($user_id);	
													}
													else
													{
														$classdata_list=$obj_class->MJ_gmgt_get_all_classes_dashboard();
													}
												}
												if(!empty($classdata_list))
												{
													?>
											  <thead>
												<tr>
												  <th scope="col" style="border-bottom: 0;border-bottom: 1px solid #f4f4f4;"><?php _e('Class Name','gym_mgt');?></th>
												  <th scope="col" style="border-bottom: 0;border-bottom: 1px solid #f4f4f4;"><?php _e('Staff Name','gym_mgt');?></th>
												  <th scope="col" style="border-bottom: 0;border-bottom: 1px solid #f4f4f4;"><?php _e('Day','gym_mgt');?></th>
												  <th scope="col" style="border-bottom: 0;border-bottom: 1px solid #f4f4f4;"><?php _e('Time','gym_mgt');?></th>
												</tr>
											  </thead>
											  <tbody>
											  <?php
												foreach($classdata_list as $retrieved_data)
												{
												?>
												<tr>
												  <td class="unit Bold remainder_title_pr_cursor show_task_event" style="width:100px" id="<?php echo $retrieved_data->class_id;?>" model="Class Details"><?php echo $retrieved_data->class_name;?></td>
												  <td class="unit" style="width:100px"><?php  $userdata=get_userdata( $retrieved_data->staff_id );
												echo $userdata->display_name;?></td>
												<td class="unit"><?php $days_array=json_decode($retrieved_data->day); 
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
												  <td class="unit" style="width:32%"><button class="btn btn-primary"><span class="period_box"><span class="time"> <?php echo MJ_gmgt_timeremovecolonbefoream_pm($retrieved_data->start_time);?> - <?php echo MJ_gmgt_timeremovecolonbefoream_pm($retrieved_data->end_time); ?></span></span></button></td>
												</tr>
												 <?php
												}
													?>
												</tbody>
												<?php
												}
												else
												{
													_e("No data available",'gym_mgt');
												} ?>
								 </table>
							</div>
						</div>
					</div>
					<?php
					$curr_user_id=get_current_user_id();
					$obj_gym=new MJ_Gym_management($curr_user_id);					
					if( $obj_gym->role == 'member')
					{ ?>
					<div class="col-md-6 col-sm-12 col-xs-12">	
						<div class="panel panel-white dashboard_height membership_btn">
							<div class="panel-heading booking">
									<h3 class="panel-title"><i class="fa fa-book" aria-hidden="true"></i><?php _e('Booking List','gym_mgt');?></h3>
									<ul class="nav navbar-right panel_toolbox">
										<li class="margin_dasboard"><a href="<?php echo home_url().'?dashboard=user&page=class-schedule&tab=class_booking';?>"><i class="fa fa-external-link" aria-hidden="true"></i></a>
										</li>                  
									</ul>
							</div>
							<div class="panel-body booking_list">
								<table class="table table-borderless calendar-event">
											<?php 
												$obj_class=new MJ_Gmgtclassschedule;
												$booking_data=$obj_class->MJ_gmgt_get_member_book_class_dashboard(get_current_user_id());
												if(!empty($booking_data))
												{
												?>
												 <thead>
													<tr>
													  <th scope="col" style="border-bottom: 0;border-bottom: 1px solid #f4f4f4;"><?php _e('Member Name','gym_mgt');?></th>
													  <th scope="col" style="border-bottom: 0;border-bottom: 1px solid #f4f4f4;"><?php _e('Class Name','gym_mgt');?></th>
													  <th scope="col" style="border-bottom: 0;border-bottom: 1px solid #f4f4f4;"><?php _e('Class Date','gym_mgt');?></th>
													  <th scope="col" style="border-bottom: 0;border-bottom: 1px solid #f4f4f4;"><?php _e('Booking Date','gym_mgt');?></th>
													</tr>
												  </thead>
												  <tbody>
													<?php
														foreach($booking_data as $retrieved_data)
														{ ?>
														<tr>
														  <td class="unit remainder_title_pr_cursor show_task_event" id="<?php echo $retrieved_data->id;?>" model="Booking Details"><?php echo MJ_gmgt_get_display_name($retrieved_data->member_id); ?></td>
														  <td class="unit"><?php print  $obj_class->MJ_gmgt_get_class_name($retrieved_data->class_id);?></td>
														  <td class="unit"><p class="remainder_date_pr"><?php print  str_replace('00:00:00',"",$retrieved_data->class_booking_date)?></p></td>
														  <td class="unit"><p class="remainder_date_pr"><?php print  str_replace('00:00:00',"",$retrieved_data->booking_date);?></p></td>
														</tr>
													 <?php 
														}
														?>
													</tbody>
												<?php 
												}
												else
												{
													_e("No data available",'gym_mgt');
												} ?>
								</table>
							</div>
						</div>
					</div>
				<?php
					}
					?>
				<?php		
				if( $obj_gym->role != 'member')
				{
				?>
					<div class="col-md-6 col-sm-12 col-xs-12">	
					<div class="panel dashboard_height panel-white membership_btn schedule_list">
					<div class="panel-heading activities">
						<h3 class="panel-title"><i class="fa fa-sun-o" aria-hidden="true"></i><?php _e('Schedule List','gym_mgt');?></h3>
						<ul class="nav navbar-right panel_toolbox">
							<li class="margin_dasboard"><a href="<?php echo home_url().'?dashboard=user&page=class-schedule&tab=schedulelist';?>"><i class="fa fa-external-link" aria-hidden="true"></i></a>
							</li>                  
						</ul>
					</div>
					<div class="panel-body">
						<table class="table table-borderless schedule_btn">
								<?php		   
								foreach(MJ_gmgt_days_array() as $daykey => $dayname)
								{
								?>			  
									<tr>
										<th width="100"><?php echo $dayname;?></th>
										<td>
										<?php
										$period = $obj_class->MJ_gmgt_get_schedule_byday($daykey);
										$curr_user_id=get_current_user_id();
										$obj_gym=new MJ_Gym_management($curr_user_id);
										if(!empty($period))
											foreach($period as $period_data)
											{			
													
												if(!empty($period_data))
												{						
													if($obj_gym->role=='staff_member')
													{
														if($user_access['own_data']=='1')
														{
															if($period_data['staff_id']==$curr_user_id || $period_data['asst_staff_id']==$curr_user_id)
															{
															echo '<div class="btn-group ">';
															echo '<button class="btn btn-primary dropdown-toggle" aria-expanded="false" data-toggle="dropdown"><span class="period_box" id='.$period_data['class_id'].'>'.MJ_gmgt_get_single_class_name($period_data['class_id']);
															echo '<span class="time"> ('.MJ_gmgt_timeremovecolonbefoream_pm($period_data['start_time']).'- '.MJ_gmgt_timeremovecolonbefoream_pm($period_data['end_time']).') </span>';
															echo '</div>';
															}
														}
														else
														{
															
															echo '<div class="btn-group ">';
															echo '<button class="btn btn-primary dropdown-toggle" aria-expanded="false" data-toggle="dropdown"><span class="period_box" id='.$period_data['class_id'].'>'.MJ_gmgt_get_single_class_name($period_data['class_id']);
															echo '<span class="time"> ('.MJ_gmgt_timeremovecolonbefoream_pm($period_data['start_time']).'- '.MJ_gmgt_timeremovecolonbefoream_pm($period_data['end_time']).') </span>';
															
															echo '</div>';
														}		
													}
													elseif($obj_gym->role == 'member')
													{												
														if(in_array($period_data['class_id'],$cur_user_class_id))
														{
															echo '<div class="btn-group m-b-sm">';
															echo '<button class="btn btn-primary dropdown-toggle" aria-expanded="false" data-toggle="dropdown"><span class="period_box" id='.$period_data['class_id'].'>'.MJ_gmgt_get_single_class_name($period_data['class_id']);
															echo '<span class="time"> ('.MJ_gmgt_timeremovecolonbefoream_pm($period_data['start_time']).'- '.MJ_gmgt_timeremovecolonbefoream_pm($period_data['end_time']).') </span>';
															echo '</div>';
														}												
													}
													else
													{		
														if($user_access['own_data']=='1')
														{													
															if($period_data['class_created_id'] == $curr_user_id)		
															{													
															echo '<div class="btn-group ">';
															echo '<button class="btn btn-primary dropdown-toggle" aria-expanded="false" data-toggle="dropdown"><span class="period_box" id='.$period_data['class_id'].'>'.MJ_gmgt_get_single_class_name($period_data['class_id']);
															echo '<span class="time"> ('.MJ_gmgt_timeremovecolonbefoream_pm($period_data['start_time']).'- '.MJ_gmgt_timeremovecolonbefoream_pm($period_data['end_time']).') </span>';
															
															echo '</div>';	
															}												
														}
														else
														{
															echo '<div class="btn-group ">';
															echo '<button class="btn btn-primary dropdown-toggle" aria-expanded="false" data-toggle="dropdown"><span class="period_box" id='.$period_data['class_id'].'>'.MJ_gmgt_get_single_class_name($period_data['class_id']);
															echo '<span class="time"> ('.MJ_gmgt_timeremovecolonbefoream_pm($period_data['start_time']).'- '.MJ_gmgt_timeremovecolonbefoream_pm($period_data['end_time']).') </span>';
															
															echo '</div>';
														}	
													}		
												}						
											}
										?>
										</td>
									</tr>
								<?php	
								}
								?>
						</table>
					</div>
				</div>
				</div>
				<?php
				}
				}
				?>
				
					<div class="col-md-6 col-sm-12 col-xs-12">		
						<div class="panel panel-white cad">
							<div class="panel-body cal">
								<div id="calendar"></div>
								 <br>
									 <mark class="mark_Notice_fronend">&nbsp;&nbsp;&nbsp;</mark>
									<span><?php _e('Notice', 'gym_mgt');?> <span>
									  <br/>
									  <br/>
									   <mark class="mark_Reservation_fronend">&nbsp;&nbsp;&nbsp;</mark>
									<span><?php _e('Reservation', 'gym_mgt');?> <span>
							</div>
						</div>
					</div>
				</div>
				
				
			</div><!--PAGE INNER DIV START-->
		</div><!--ROW DIV END-->
	</body><!--BODY END-->
</html><!--HTML END-->