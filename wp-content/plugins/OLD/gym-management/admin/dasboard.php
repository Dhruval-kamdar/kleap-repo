<?php 
require_once GMS_PLUGIN_DIR . '/lib/chart/GoogleCharts.class.php';
$GoogleCharts = new GoogleCharts;
$obj_dashboard= new MJ_Gmgtdashboard;
$obj_reservation = new MJ_Gmgtreservation;
$reservationdata = $obj_reservation->MJ_gmgt_get_all_reservation();
$cal_array = array();
//GET RESERVATION DATA
if(!empty($reservationdata))
{
	foreach ($reservationdata as $retrieved_data)
	{		
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
	   
		$cal_array [] = array (
		        'type' =>  'reservationdata',
				'title' => $retrieved_data->event_name,
				'start' => $aevent_start_date_new,
				'end' => $event__end_date,
		); 
	}
}
//GET USER BIRTHDATE
$birthday_boys=get_users(array('role'=>'member'));

if (! empty ( $birthday_boys ))
{
	foreach ( $birthday_boys as $boys )
	{
		$startdate = date("Y",strtotime($boys->birth_date));
		$enddate = $startdate + 90;
		$years = range($startdate,$enddate,1);
		foreach($years as $year)
		{	
			$startdate1=date("m-d",strtotime($boys->birth_date));
			 $cal_array [] = array (
			 'type' =>  'Birthday',
			'title' => $boys->first_name."'s Birthday",
			'start' =>"{$year}-{$startdate1}",
			'end' =>"{$year}-{$startdate1}",
			'backgroundColor' => '#F25656');
		} 

	}
}
//GET NOTICE DATA
$all_notice = "";
$args['post_type'] = 'gmgt_notice';
$args['posts_per_page'] = -1;
$args['post_status'] = 'public';
$q = new WP_Query();
$all_notice = $q->query( $args );
if (! empty ( $all_notice ))
{
	foreach ( $all_notice as $notice ) 
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
?>
<script>
	$(document).ready(function()
	{
		$('#calendar').fullCalendar(
		{
			 header: {
					left: 'prev,next today',
					center: 'title',
					right: 'month,agendaWeek,agendaDay'
				},
				editable: false,
				slotEventOverlap: false,
				timeFormat: 'h(:mm)a',
			eventLimit:1, // allow "more" link when too many events
			events: <?php echo json_encode($cal_array);?>,
			forceEventDuration : true,
	        eventMouseover: function (event, jsEvent, view) {
			//end date change with minus 1 day
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
			 
			 var newstartdate = event.start;
			 var date = new Date(newstartdate);
			 var startdate = new Date(date);
			 var dateObjstart = new Date(startdate);
			 var momentObjstart = moment(dateObjstart);
			 var momentStringstart = momentObjstart.format(dateformate);
					if(type == 'Birthday')
					{
						tooltip = "<div class='tooltiptopicevent' style='width:auto;height:auto;background:#feb811;position:absolute;z-index:10001;padding:10px 10px 10px 10px ;  line-height: 200%;'>" + "<?php _e("Title Name","gym_mgt"); ?>" + " : " + event.title + "</br>" + " <?php _e("Birthday Date","gym_mgt"); ?> " + " : " + momentStringstart + " </div>";	
					}
					else
					{
						tooltip = "<div class='tooltiptopicevent' style='width:auto;height:auto;background:#feb811;position:absolute;z-index:10001;padding:10px 10px 10px 10px ;  line-height: 200%;'>" + "<?php _e("Title Name","gym_mgt"); ?>" + " : " + event.title + "</br>" + " <?php _e("Start Date","gym_mgt"); ?> " + " : " + momentStringstart + "</br>" + "<?php _e("End Date","gym_mgt"); ?>" + " : " + momentString + "</br>" +  " </div>";	
					}
						$("body").append(tooltip);
						$(this).mouseover(function (e) {
							$(this).css('z-index', 10000);
							$('.tooltiptopicevent').fadeIn('500');
							$('.tooltiptopicevent').fadeTo('10', 1.9);
						}).mousemove(function (e) {
							$('.tooltiptopicevent').css('top', e.pageY + 10);
							$('.tooltiptopicevent').css('left', e.pageX + 20);
						});

					},
					eventMouseout: function (data, event, view) {
						$(this).css('z-index', 8);

						$('.tooltiptopicevent').remove();

					},
		});
	});
</script>
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
<div class="page-inner" style="min-height:1088px !important"><!--PAGE INNNER DIV START-->
	<div class="page-title">
		<h3><img src="<?php echo get_option( 'gmgt_system_logo' ) ?>" class="img-circle head_logo" width="40" height="40" /><?php echo get_option( 'gmgt_system_name' );?>
		</h3>
	</div>
	<div id="main-wrapper">	<!--MAIN WRAPPER DIV START-->	
		<div class="row"><!-- Start Row2 -->
		    
			    <div class="col-lg-2 col-md-2 col-xs-6 col-sm-6">
					<a href="<?php echo admin_url().'admin.php?page=gmgt_member';?>">
						<div class="panel info-box panel-white">
							<div class="panel-body member action">
								<img src="<?php echo GMS_PLUGIN_URL."/assets/images/dashboard/Member_new.png"?>" class="dashboard_background">
								<img src="<?php echo GMS_PLUGIN_URL."/assets/images/dashboard/Member_new_black.png"?>" class="dashboard_background_second">
								<div class="info-box-stats">
									<p class="counter"><?php echo count(get_users(array('role'=>'member')));?><span class="info-box-title"><?php echo esc_html( __( 'Members', 'gym_mgt' ) );?></span></p>	
								</div>
							</div>
						</div>
					</a>
			    </div>
				<div class="col-lg-2 col-md-2 col-xs-6 col-sm-6">
					<a href="<?php echo admin_url().'admin.php?page=gmgt_staff';?>">
						<div class="panel info-box panel-white">
							<div class="panel-body staff-member">
								<img src="<?php echo GMS_PLUGIN_URL."/assets/images/dashboard/Staff_Member.png"?>" class="dashboard_background">
								<img src="<?php echo GMS_PLUGIN_URL."/assets/images/dashboard/Staff_Member_black.png"?>" class="dashboard_background_second staff_black_img">
								<div class="info-box-stats">
									<p class="counter"><?php echo count(get_users(array('role'=>'staff_member')));?><span class="info-box-title"><?php echo esc_html( __( 'Staff Members', 'gym_mgt' ) );?></span></p>
								</div>
							</div>
						</div>
					</a>
				</div>
				
				
				<div class="col-lg-2 col-md-2 col-xs-6 col-sm-6">
					<a href="<?php echo admin_url().'admin.php?page=gmgt_accountant';?>">
						<div class="panel info-box panel-white">
							<div class="panel-body accountant">
								<img src="<?php echo GMS_PLUGIN_URL."/assets/images/dashboard/Accountant_new.png"?>" class="dashboard_background">
								<img src="<?php echo GMS_PLUGIN_URL."/assets/images/dashboard/Accountant_new_black.png"?>" class="dashboard_background_second">
								<div class="info-box-stats">
									<p class="counter"><?php echo count(get_users(array('role'=>'accountant')));?><span class="info-box-title"><?php echo esc_html( __( 'Accountant', 'gym_mgt' ) );?></span></p>
								</div>
							</div>
						</div>
					</a>
				</div>

				
				<div class="col-lg-2 col-md-2 col-xs-6 col-sm-6">
					<a href="<?php echo admin_url().'admin.php?page=gmgt_group';?>">
						<div class="panel info-box panel-white">
							<div class="panel-body group">
								<img src="<?php echo GMS_PLUGIN_URL."/assets/images/dashboard/Group_new.png"?>" class="dashboard_background">
								<img src="<?php echo GMS_PLUGIN_URL."/assets/images/dashboard/Group_new_black.png"?>" class="dashboard_background_second">
								<div class="info-box-stats groups-label">
									<p class="counter"><?php echo $obj_dashboard->MJ_gmgt_count_group();?><span class="info-box-title"><?php echo esc_html( __( 'Groups', 'gym_mgt' ) );?></span>
									</p>
								</div>
							</div>
						</div>
					</a>
				</div>
				
				<div class="col-lg-2 col-md-2 col-xs-6 col-sm-6">
					<a href="<?php echo admin_url().'admin.php?page=gmgt_membership_type';?>">
						<div class="panel info-box panel-white">
							<div class="panel-body membership">
								<img src="<?php echo GMS_PLUGIN_URL."/assets/images/dashboard/Membership_new.png"?>" class="dashboard_background">
								<img src="<?php echo GMS_PLUGIN_URL."/assets/images/dashboard/Membership_new_black.png"?>" class="dashboard_background_second">
								<div class="info-box-stats">
								<?php 
									global $wpdb;
									$gmgt_membershiptype = $wpdb->prefix. 'gmgt_membershiptype';
									$total_membership = $wpdb->get_row("SELECT COUNT(*) as  total_membership FROM $gmgt_membershiptype");	
								?>
									<p class="counter"><?php echo $total_membership->total_membership;?><span class="info-box-title"><?php echo esc_html( __( 'Memberships', 'gym_mgt' ) );?></span>
									</p>
								</div>
							</div>
						</div>
					</a>
				</div>
				<div class="col-lg-2 col-md-2 col-xs-6 col-sm-6">
					<a href="<?php echo admin_url().'admin.php?page=gmgt_gnrl_settings';?>">
						<div class="panel info-box panel-white">
							<div class="panel-body setting">
								<img src="<?php echo GMS_PLUGIN_URL."/assets/images/dashboard/Settings.png"?>" class="dashboard_background">
								<img src="<?php echo GMS_PLUGIN_URL."/assets/images/dashboard/Settings_black.png"?>" class="dashboard_background_second">
								<div class="info-box-stats">
									<p class="counter"><?php echo "";?><span class="info-box-title"><?php echo esc_html( __( 'Settings', 'gym_mgt' ) );?></span>
									</p>
								</div>
							</div>
						</div>
					</a>
				</div>
				<div class="col-lg-2 col-md-2 col-xs-6 col-sm-6">
					<a href="<?php echo admin_url().'admin.php?page=gmgt_class';?>">
						<div class="panel info-box panel-white">
							<div class="panel-body class">
								<img src="<?php echo GMS_PLUGIN_URL."/assets/images/dashboard/Class.png"?>" class="dashboard_background">
								<img src="<?php echo GMS_PLUGIN_URL."/assets/images/dashboard/Class_black.png"?>" class="dashboard_background_second">
								<div class="info-box-stats groups-label">
									<p class="counter"><?php echo $obj_dashboard->MJ_gmgt_count_class();?><span class="info-box-title"><?php echo esc_html( __( 'Class', 'gym_mgt' ) );?></span>
									</p>
								</div>
							</div>
						</div>
					</a>
				</div>
				<div class="col-lg-2 col-md-2 col-xs-6 col-sm-6">
					<a href="<?php echo admin_url().'admin.php?page=gmgt_notice';?>">
						<div class="panel info-box panel-white">
							<div class="panel-body notice_event">
								<img src="<?php echo GMS_PLUGIN_URL."/assets/images/dashboard/Notice.png"?>" class="dashboard_background">
								<img src="<?php echo GMS_PLUGIN_URL."/assets/images/dashboard/Notice_black.png"?>" class="dashboard_background_second notice_black_img">
								<div class="info-box-stats">
								<?php 
									global $wpdb;
									$table_post= $wpdb->prefix. 'posts';
									$total_notice = $wpdb->get_row("SELECT COUNT(*) as  total_notice FROM $table_post where post_type='gmgt_notice' ");		
								?>
									<p class="counter"><?php echo $total_notice->total_notice;;?><span class="info-box-title"><?php echo esc_html( __( 'Notice', 'gym_mgt' ) );?></span></p>
								</div>
							</div>
						</div>
					</a>
				</div>
				<div class="col-lg-2 col-md-2 col-xs-6 col-sm-6">
					<a href="<?php echo admin_url().'admin.php?page=Gmgt_message';?>">
						<div class="panel info-box panel-white">
							<div class="panel-body message">
								<img src="<?php echo GMS_PLUGIN_URL."/assets/images/dashboard/Message_new.png"?>" class="dashboard_background">
								<img src="<?php echo GMS_PLUGIN_URL."/assets/images/dashboard/Message_new_black.png"?>" class="dashboard_background_second">
								<div class="info-box-stats">
									<p class="counter"><?php echo count(MJ_gmgt_count_inbox_item(get_current_user_id()));?><span class="info-box-title"><?php echo esc_html( __( 'Messages', 'gym_mgt' ) );?></span></p>
								</div>
							</div>
						</div>
					</a>
				</div>
				<div class="col-lg-2 col-md-2 col-xs-6 col-sm-6">
					<a href="<?php echo admin_url().'admin.php?page=gmgt_reservation';?>">
						<div class="panel info-box panel-white">
							<div class="panel-body nutrition">
								<img src="<?php echo GMS_PLUGIN_URL."/assets/images/dashboard/reservation.png"?>" class="dashboard_background">
								<img src="<?php echo GMS_PLUGIN_URL."/assets/images/dashboard/reservation_black.png"?>" class="dashboard_background_second">
								<div class="info-box-stats groups-label">
									<p class="counter"><?php echo $obj_dashboard->MJ_gmgt_count_reservation();?><span class="info-box-title"><?php echo esc_html( __( 'Reservation', 'gym_mgt' ) );?></span></p>
								</div>
							</div>
						</div>
					</a>
				</div>
				
				<div class="col-lg-2 col-md-2 col-xs-6 col-sm-6">
					<a href="<?php echo admin_url().'admin.php?page=gmgt_product';?>">
						<div class="panel info-box panel-white">
							<div class="panel-body product">
								<img src="<?php echo GMS_PLUGIN_URL."/assets/images/dashboard/Product.png"?>" class="dashboard_background">
								<img src="<?php echo GMS_PLUGIN_URL."/assets/images/dashboard/Product_black.png"?>" class="dashboard_background_second">
								<div class="info-box-stats groups-label">
									<p class="counter"><?php echo $obj_dashboard->MJ_gmgt_count_Products();?><span class="info-box-title"><?php echo esc_html( __( 'Products', 'gym_mgt' ) );?></span></p>
								</div>
							</div>
						</div>
					</a>
				</div>
			<div class="col-lg-2 col-md-2 col-xs-6 col-sm-6">
					<a href="<?php echo admin_url().'admin.php?page=gmgt_attendence';?>">
						<div class="panel info-box panel-white">
							<div class="panel-body attendance">
								<img src="<?php echo GMS_PLUGIN_URL."/assets/images/dashboard/TodayAttendance.png"?>" class="dashboard_background">
								<img src="<?php echo GMS_PLUGIN_URL."/assets/images/dashboard/TodayAttendance_black.png"?>" class="dashboard_background_second">
								<div class="info-box-stats groups-label">
									<p class="counter"><?php echo $obj_dashboard->today_presents();?><span class="info-box-title"><?php echo esc_html( __( 'Today Attendance', 'gym_mgt' ) );?></span></p>
								</div>
							</div>
						</div>
					</a>
			</div>
				
	<div class="col-md-6">
					
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

					// require_once SMS_PLUGIN_DIR. '/lib/chart/GoogleCharts.class.php';
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
							<?php  $month =array('1'=>"January ",'2'=>"February",'3'=>"March",'4'=>"April",'5'=>"May",'6'=>"June",'7'=>	"July",'8'=>"August",
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
							 $month =array('1'=>"January ",'2'=>"February",'3'=>"March",'4'=>"April",'5'=>"May",'6'=>"June",'7'=>	"July",'8'=>"August",
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
						 	$month =array('1'=>"January ",'2'=>"February",'3'=>"March",'4'=>"April",'5'=>"May",'6'=>"June",'7'=>	"July",'8'=>"August",
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
			
			<div class="col-md-6 col-sm-12 col-xs-12 left_side_dashboard">
				
				<!--rinkal changes start dashboard entry list-->
				<div class="panel dashboard_height panel-white activet">
					<div class="panel-heading activities">
						<h3 class="panel-title"><i class="fa fa-truck" aria-hidden="true"></i><?php _e('Activities','gym_mgt');?></h3>
						<ul class="nav navbar-right panel_toolbox">
							<li class="margin_dasboard"><a href="<?php echo admin_url().'admin.php?&page=gmgt_activity';?>"><i class="fa fa-external-link" aria-hidden="true"></i></a>
							</li>                  
						</ul>
					</div>
					<div class="panel-body">
						<table class="table table-borderless activity_btn">
						<?php 
								$obj_activity=new MJ_Gmgtactivity;
								$activitydata=$obj_activity->MJ_get_all_activity_dashboard();
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
				
				<div class="panel dashboard_height panel-white">
					<div class="panel-heading res_list">
						<h3 class="panel-title"><i class="fa fa-ticket" aria-hidden="true"></i><?php _e('Reservation List','gym_mgt');?></h3>
						<ul class="nav navbar-right panel_toolbox">
							<li class="margin_dasboard"><a href="<?php echo admin_url().'admin.php?&page=gmgt_reservation&tab=reservationlist';?>"><i class="fa fa-external-link" aria-hidden="true"></i></a>
							</li>                  
						</ul>
					</div>
					<div class="panel-body">
						<div class="events">
						<?php 
							$obj_reservation=new MJ_Gmgtreservation;
							$reservationdata=$obj_reservation->MJ_gmgt_get_all_reservation_dashboard();
							if(!empty($reservationdata))
							{
								foreach ($reservationdata as $retrieved_data)
								{
									?>
							<div class="calendar-event view-complaint"> 
								<p class="remainder_title_pr Bold viewpriscription show_task_event" id="<?php echo $retrieved_data->id;?>" model="Reservation Details"><?php _e('Event Name : ','gym_mgt');?>
								<?php echo $retrieved_data->event_name;?>							
								</p><p class="remainder_date_pr"><?php echo MJ_gmgt_getdate_in_input_box($retrieved_data->event_date);?></p>
								<p class="">
								<?php _e('Event Place : ','gym_mgt');?>
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
						
				
				<div class="panel dashboard_height panel-white">
						<div class="panel-heading nt_ev">
							<h3 class="panel-title"><i class="fa fa-calendar-o" aria-hidden="true"></i><?php _e('Notice','gym_mgt');?></h3>	
							<ul class="nav navbar-right panel_toolbox">
								<li class="margin_dasboard"><a href="<?php echo admin_url().'admin.php?&page=gmgt_notice';?>"><i class="fa fa-external-link" aria-hidden="true"></i></a>
								</li>                  
							</ul>
						</div>
						<div class="panel-body">
							<div class="events">
							<?php 
								$args['post_type'] = 'gmgt_notice';
								$args['posts_per_page'] = 3;
								$args['post_status'] = 'public';
								$q = new WP_Query();
								$noticedata = $q->query( $args );
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
									_e("No Upcoming notice",'gym_mgt');
								?>
							</div>
						</div>
					
				</div>
				
			<div class="panel panel-white group_btn dashboard_height">
					<div class="panel-heading grp_list">
						<h3 class="panel-title"><i class="fa fa-users" aria-hidden="true"></i><?php _e('Group List','gym_mgt');?></h3>
						<ul class="nav navbar-right panel_toolbox">
							<li class="margin_dasboard"><a href="<?php echo admin_url().'admin.php?&page=gmgt_group';?>"><i class="fa fa-external-link" aria-hidden="true"></i></a>
							</li>                  
						</ul>
					</div>
					<div class="panel-body grp">
							<div class="events">
							<?php 
								$obj_group=new MJ_Gmgtgroup;
								$groupdata=$obj_group->MJ_gmgt_get_all_groups_dashboard();
								if(!empty($groupdata))
								{
										foreach ($groupdata as $retrieved_data)
										{
											$group_count=$obj_group->MJ_gmgt_count_group_members($retrieved_data->id);
										?>
								<div class="calendar-event view-complaint"> 
								<p class="remainder_title_pr Bold viewdetail show_task_event" id="<?php echo $retrieved_data->id;?>" model="Group Details"> <?php _e('Group Name : ','gym_mgt');?>
								<?php echo $retrieved_data->group_name;?></p>							
								<?php if(!empty($group_count) ) { ?>
										  <span class="btn btn-success btn-xs"><?php echo $group_count;?></span></td>
										   <?php 
											}
											else{
												?>  <span class="btn btn-success btn-xs"><?php echo "0";?></span><?php
											}
											?>
								<p class="grp_des">
								 <?php _e('Description : ','gym_mgt');?> 
								<?php 
								$strlength= strlen($retrieved_data->group_description);
									if($strlength > 90)
										echo substr($retrieved_data->group_description, 0,90).'...';
									else
										echo $retrieved_data->group_description;
								?>
								</p>
								</div>	
							<?php
								}	
							} 
							else 
								_e("No Upcoming Group",'gym_mgt');
							?>
					</div>
				</div>				
			</div>
	
				
				<!--rinkal changes end dashboard entry list-->
				
				
			</div>
			<div class="col-md-6 membership-list col-sm-12 col-xs-12 left_side_dashboard">
				<div class="panel panel-white dashboard_height membership_btn">
					<div class="panel-heading membership">
							<h3 class="panel-title"><i class="fa fa-user" aria-hidden="true"></i><?php _e('Membership','gym_mgt');?></h3>
							<ul class="nav navbar-right panel_toolbox">
								<li class="margin_dasboard"><a href="<?php echo admin_url().'admin.php?&page=gmgt_membership_type';?>"><i class="fa fa-external-link" aria-hidden="true"></i></a>
								</li>                  
							</ul>
					</div>
					<div class="panel-body">
						<table class="table table-borderless">
						<?php 
							$obj_membership=new MJ_Gmgtmembership;
							$membershipdata=$obj_membership->MJ_gmgt_get_all_membership_dashboard();
							 
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
				<div class="panel panel-white dashboard_height invoice_btn">
					<div class="panel-heading invoice">
						<h3 class="panel-title"><i class="fa fa-list-alt" aria-hidden="true"></i><?php _e('Invoice List','gym_mgt');?></h3>
						<ul class="nav navbar-right panel_toolbox">
							<li class="margin_dasboard"><a href="<?php echo admin_url().'admin.php?&page=gmgt_payment';?>"><i class="fa fa-external-link" aria-hidden="true"></i></a>
							</li>                  
						</ul>
					</div>
					<div class="panel-body">
						<div class="events">
							      <table class="table table-borderless">
								  <?php 
									$obj_payment=new MJ_Gmgtpayment;
									$paymentdata=$obj_payment->MJ_gmgt_get_all_income_data_dashboard();
								 
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
									_e("No Upcoming Invoice list",'gym_mgt');
								?>
								
						
								</table>
															
						</div>
					</div>
				</div>
				
				<div class="panel dashboard_height panel-white membership_btn">
					<div class="panel-heading class_list">
						<h3 class="panel-title"><i class="fa fa-list" aria-hidden="true"></i><?php _e('Class List','gym_mgt');?></h3>
						<ul class="nav navbar-right panel_toolbox">
							<li class="margin_dasboard"><a href="<?php echo admin_url().'admin.php?&page=gmgt_class';?>"><i class="fa fa-external-link" aria-hidden="true"></i></a>
							</li>                  
						</ul>
					</div>
					<div class="panel-body">
						<table class="table table-borderless class_btn">
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
										$obj_class=new MJ_Gmgtclassschedule;
										$class_data=$obj_class->MJ_gmgt_get_all_classes_dashboard();
										 
										if(!empty($class_data))
										{
											foreach($class_data as $retrieved_data)
											{
										?>
								   		<tr>
										  <td class="unit remainder_title_pr_cursor show_task_event" style="width:100px" id="<?php echo $retrieved_data->class_id;?>" model="Class Details"><?php echo $retrieved_data->class_name;?></td>
										  <td class="unit" style="width:100px"><?php  $userdata=get_userdata( $retrieved_data->staff_id );echo $userdata->display_name;?></td>
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
											?></td>		
										  <td class="unit" style="width:30%"><button class="btn btn-primary"><span class="period_box"><span class="time"> <?php echo MJ_gmgt_timeremovecolonbefoream_pm($retrieved_data->start_time);?> - <?php echo MJ_gmgt_timeremovecolonbefoream_pm($retrieved_data->end_time); ?></span></span></button></td>
										</tr>
										 <?php
											}
										}
										else { ?>
										
											<tr>
												<td colspan="4" ><?php  _e('No data available.','gym_mgt');?></td>
											</tr>
										<?php }
										?>
									  </tbody>
						 </table>
					</div>
				</div>
				<div class="panel panel-white dashboard_height membership_btn">
					<div class="panel-heading booking">
							<h3 class="panel-title"><i class="fa fa-book" aria-hidden="true"></i><?php _e('Booking List','gym_mgt');?></h3>
							<ul class="nav navbar-right panel_toolbox">
								<li class="margin_dasboard"><a href="<?php echo admin_url().'admin.php?&page=gmgt_class&tab=booking_list'; ?>"><i class="fa fa-external-link" aria-hidden="true"></i></a>
								</li>                  
							</ul>
					</div>
					<div class="panel-body booking_list">
						<table class="table table-borderless calendar-event">
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
										$obj_class=new MJ_Gmgtclassschedule;
										$booking_data=$obj_class->MJ_gmgt_get_all_booked_class_dashboard();
										 
										if(!empty($booking_data))
										{
											foreach($booking_data as $retrieved_data)
											{
										?>
									   	<tr>
										  <td class="unit remainder_title_pr_cursor show_task_event" id="<?php echo $retrieved_data->id;?>" model="Booking Details"><?php echo MJ_gmgt_get_display_name($retrieved_data->member_id); ?></td>
										  <td class="unit"><?php print  $obj_class->MJ_gmgt_get_class_name($retrieved_data->class_id);?></td>
										  <td class="unit"><p class="remainder_date_pr"><?php print  str_replace('00:00:00',"",$retrieved_data->class_booking_date)?></p></td>
										  <td class="unit"><p class="remainder_date_pr"><?php print  str_replace('00:00:00',"",$retrieved_data->booking_date);?></p></td>
										</tr>
										 <?php 
											}
										}
										else { ?>
										
											<tr>
												<td colspan="4" ><?php  _e('No data available.','gym_mgt');?></td>
											</tr>
										<?php }
										?>
									  </tbody>
						</table>
					</div>
				</div>
				
				<div class="panel dashboard_height panel-white membership_btn schedule_list">
					<div class="panel-heading activities">
						<h3 class="panel-title"><i class="fa fa-sun-o" aria-hidden="true"></i><?php _e('Schedule List','gym_mgt');?></h3>
						<ul class="nav navbar-right panel_toolbox">
							<li class="margin_dasboard"><a href="<?php echo admin_url().'admin.php?&page=gmgt_class&tab=schedulelist';?>"><i class="fa fa-external-link" aria-hidden="true"></i></a>
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
												$obj_class=new MJ_Gmgtclassschedule;
												$period = $obj_class->MJ_gmgt_get_schedule_byday($daykey);
												if(!empty($period))
												{
													foreach($period as $period_data)
													{
														if(!empty($period_data))
														{
															echo '<div class="btn-group m-b-sm">';
															echo '<button class="btn btn-primary dropdown-toggle" aria-expanded="false" data-toggle="dropdown"><span class="period_box" id='.$period_data['class_id'].'>'.MJ_gmgt_get_single_class_name($period_data['class_id']);
															echo '<span class="time"> ('.MJ_gmgt_timeremovecolonbefoream_pm($period_data['start_time']).' - '.MJ_gmgt_timeremovecolonbefoream_pm($period_data['end_time']).') </span>';
															echo '</div>';
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
			
				
				<!--rinkal changes start dashboard entry list-->
		
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
			
		</div>	<!-- End row2 -->
		<div class="row"><!-- Start Row3 -->
			<!-- rinkal chages report-->
				<!-- changes rinkal end-->
				
		</div><!-- End Row3 -->
	</div>	<!--MAIN WRAPPER DIV END-->	
</div><!--  End page-inner -->
<?php ?>