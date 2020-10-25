<?php 	
$result=get_option('gmgt_access_right_member');
//SAVE MEMBER ACCESS RIGHT DATA
if(isset($_POST['save_access_right']))
{
	$role_access_right = array();
	$result=get_option('gmgt_access_right_member');
	$role_access_right['member'] = [
							"staff_member"=>["menu_icone"=>plugins_url('gym-management/assets/images/icon/staff-member.png'),
							           "menu_title"=>'Staff Members',
							           "page_link"=>'staff_member',
									   "own_data" =>isset($_REQUEST['staff_member_own_data'])?$_REQUEST['staff_member_own_data']:0,
									   "add" =>isset($_REQUEST['staff_member_add'])?$_REQUEST['staff_member_add']:0,
										"edit"=>isset($_REQUEST['staff_member_edit'])?$_REQUEST['staff_member_edit']:0,
										"view"=>isset($_REQUEST['staff_member_view'])?$_REQUEST['staff_member_view']:0,
										"delete"=>isset($_REQUEST['staff_member_delete'])?$_REQUEST['staff_member_delete']:0
										],
												
						   "membership"=>['menu_icone'=>plugins_url( 'gym-management/assets/images/icon/membership-type.png'),
						              "menu_title"=>'Membership Type',
						              "page_link"=>'membership',
									 "own_data" => isset($_REQUEST['membership_own_data'])?$_REQUEST['membership_own_data']:0,
									 "add" => isset($_REQUEST['membership_add'])?$_REQUEST['membership_add']:0,
									 "edit"=>isset($_REQUEST['membership_edit'])?$_REQUEST['membership_edit']:0,
									 "view"=>isset($_REQUEST['membership_view'])?$_REQUEST['membership_view']:0,
									 "delete"=>isset($_REQUEST['membership_delete'])?$_REQUEST['membership_delete']:0
						  ],
									  
							"group"=>['menu_icone'=>plugins_url( 'gym-management/assets/images/icon/group.png'),
							        "menu_title"=>'Group',
									"page_link"=>'group',
									 "own_data" => isset($_REQUEST['group_own_data'])?$_REQUEST['group_own_data']:0,
									 "add" => isset($_REQUEST['group_add'])?$_REQUEST['group_add']:0,
									"edit"=>isset($_REQUEST['group_edit'])?$_REQUEST['group_edit']:0,
									"view"=>isset($_REQUEST['group_view'])?$_REQUEST['group_view']:0,
									"delete"=>isset($_REQUEST['group_delete'])?$_REQUEST['group_delete']:0
						  ],
									  
							  "member"=>['menu_icone'=>plugins_url( 'gym-management/assets/images/icon/member.png'),
							            "menu_title"=>'Member',
										"page_link"=>'member',
										"own_data" => isset($_REQUEST['member_own_data'])?$_REQUEST['member_own_data']:0,
										 "add" => isset($_REQUEST['member_add'])?$_REQUEST['member_add']:0,
										 "edit"=>isset($_REQUEST['member_edit'])?$_REQUEST['member_edit']:0,
										"view"=>isset($_REQUEST['member_view'])?$_REQUEST['member_view']:0,
										"delete"=>isset($_REQUEST['member_delete'])?$_REQUEST['member_delete']:0
							  ],
							  
							  "activity"=>['menu_icone'=>plugins_url( 'gym-management/assets/images/icon/activity.png'),
							             "menu_title"=>'Activity',
										 "page_link"=>'activity',
										 "own_data" => isset($_REQUEST['activity_own_data'])?$_REQUEST['activity_own_data']:0,
										 "add" => isset($_REQUEST['activity_add'])?$_REQUEST['activity_add']:0,
										"edit"=>isset($_REQUEST['activity_edit'])?$_REQUEST['activity_edit']:0,
										"view"=>isset($_REQUEST['activity_view'])?$_REQUEST['activity_view']:0,
										"delete"=>isset($_REQUEST['activity_delete'])?$_REQUEST['activity_delete']:0
							  ],
							  "class-schedule"=>['menu_icone'=>plugins_url( 'gym-management/assets/images/icon/class-schedule.png'),
							               "menu_title"=>'Class schedule',
										   "page_link"=>'class-schedule',
										 "own_data" => isset($_REQUEST['class_schedule_own_data'])?$_REQUEST['class_schedule_own_data']:0,
										 "add" => isset($_REQUEST['class_schedule_add'])?$_REQUEST['class_schedule_add']:0,
										"edit"=>isset($_REQUEST['class_schedule_edit'])?$_REQUEST['class_schedule_edit']:0,
										"view"=>isset($_REQUEST['class_schedule_view'])?$_REQUEST['class_schedule_view']:0,
										"delete"=>isset($_REQUEST['class_schedule_delete'])?$_REQUEST['class_schedule_delete']:0
							  ],
							  
							    "attendence"=>['menu_icone'=>plugins_url( 'gym-management/assets/images/icon/attandance.png'),
								         "menu_title"=>'Attendence',
										 "page_link"=>'attendence',
										 "own_data" => isset($_REQUEST['attendence_own_data'])?$_REQUEST['attendence_own_data']:0,
										 "add" => isset($_REQUEST['attendence_add'])?$_REQUEST['attendence_add']:0,
										"edit"=>isset($_REQUEST['attendence_edit'])?$_REQUEST['attendence_edit']:0,
										"view"=>isset($_REQUEST['attendence_view'])?$_REQUEST['attendence_view']:0,
										"delete"=>isset($_REQUEST['attendence_delete'])?$_REQUEST['attendence_delete']:0
							  ],						  
							  
							    "assign-workout"=>['menu_icone'=>plugins_url( 'gym-management/assets/images/icon/assigne-workout.png'),
								         "menu_title"=>'Assigned Workouts',
										 "page_link"=>'assign-workout',
										 "own_data" => isset($_REQUEST['assign_workout_own_data'])?$_REQUEST['assign_workout_own_data']:0,
										 "add" => isset($_REQUEST['assign_workout_add'])?$_REQUEST['assign_workout_add']:0,
										"edit"=>isset($_REQUEST['assign_workout_edit'])?$_REQUEST['assign_workout_edit']:0,
										"view"=>isset($_REQUEST['assign_workout_view'])?$_REQUEST['assign_workout_view']:0,
										"delete"=>isset($_REQUEST['assign_workout_delete'])?$_REQUEST['assign_workout_delete']:0
							  ],
							   "nutrition"=>['menu_icone'=>plugins_url( 'gym-management/assets/images/icon/nutrition-schedule.png'),
							            "menu_title"=>'Nutrition Schedule',
										"page_link"=>'nutrition',
										 "own_data" => isset($_REQUEST['nutrition_own_data'])?$_REQUEST['nutrition_own_data']:0,
										 "add" => isset($_REQUEST['nutrition_add'])?$_REQUEST['nutrition_add']:0,
										"edit"=>isset($_REQUEST['nutrition_edit'])?$_REQUEST['nutrition_edit']:0,
										"view"=>isset($_REQUEST['nutrition_view'])?$_REQUEST['nutrition_view']:0,
										"delete"=>isset($_REQUEST['nutrition_delete'])?$_REQUEST['nutrition_delete']:0
							  ],
							    "workouts"=>['menu_icone'=>plugins_url( 'gym-management/assets/images/icon/workout.png'),
								         "menu_title"=>'Workouts',
										 "page_link"=>'workouts',
										 "own_data" => isset($_REQUEST['workouts_own_data'])?$_REQUEST['workouts_own_data']:0,
										 "add" => isset($_REQUEST['workouts_add'])?$_REQUEST['workouts_add']:0,
										"edit"=>isset($_REQUEST['workouts_edit'])?$_REQUEST['workouts_edit']:0,
										"view"=>isset($_REQUEST['workouts_view'])?$_REQUEST['workouts_view']:0,
										"delete"=>isset($_REQUEST['workouts_delete'])?$_REQUEST['workouts_delete']:0
							  ],
							    "accountant"=>['menu_icone'=>plugins_url( 'gym-management/assets/images/icon/accountant.png'),
								          "menu_title"=>'Accountant',
										  "page_link"=>'accountant',
										 "own_data" => isset($_REQUEST['accountant_own_data'])?$_REQUEST['accountant_own_data']:0,
										 "add" => isset($_REQUEST['accountant_add'])?$_REQUEST['accountant_add']:0,
										"edit"=>isset($_REQUEST['accountant_edit'])?$_REQUEST['accountant_edit']:0,
										"view"=>isset($_REQUEST['accountant_view'])?$_REQUEST['accountant_view']:0,
										"delete"=>isset($_REQUEST['accountant_delete'])?$_REQUEST['accountant_delete']:0
							  ],
							  
							  "membership_payment"=>['menu_icone'=>plugins_url( 'gym-management/assets/images/icon/fee.png'),
							             "menu_title"=>'Membership Payment',
										 "page_link"=>'membership_payment',
										 "own_data" => isset($_REQUEST['membership_payment_own_data'])?$_REQUEST['membership_payment_own_data']:0,
										 "add" => isset($_REQUEST['membership_payment_add'])?$_REQUEST['membership_payment_add']:0,
										"edit"=>isset($_REQUEST['membership_payment_edit'])?$_REQUEST['membership_payment_edit']:0,
										"view"=>isset($_REQUEST['membership_payment_view'])?$_REQUEST['membership_payment_view']:0,
										"delete"=>isset($_REQUEST['membership_payment_delete'])?$_REQUEST['membership_payment_delete']:0
							  ],
							  
							  "payment"=>['menu_icone'=>plugins_url( 'gym-management/assets/images/icon/payment.png'),
							             "menu_title"=>'Payment',
										 "page_link"=>'payment',
										 "own_data" => isset($_REQUEST['payment_own_data'])?$_REQUEST['payment_own_data']:0,
										 "add" => isset($_REQUEST['payment_add'])?$_REQUEST['payment_add']:0,
										"edit"=>isset($_REQUEST['payment_edit'])?$_REQUEST['payment_edit']:0,
										"view"=>isset($_REQUEST['payment_view'])?$_REQUEST['payment_view']:0,
										"delete"=>isset($_REQUEST['payment_delete'])?$_REQUEST['payment_delete']:0
							  ],
							  "product"=>['menu_icone'=>plugins_url( 'gym-management/assets/images/icon/products.png'),
							           "menu_title"=>'Product',
									   "page_link"=>'product',
										 "own_data" => isset($_REQUEST['product_own_data'])?$_REQUEST['product_own_data']:0,
										 "add" => isset($_REQUEST['product_add'])?$_REQUEST['product_add']:0,
										"edit"=>isset($_REQUEST['product_edit'])?$_REQUEST['product_edit']:0,
										"view"=>isset($_REQUEST['product_view'])?$_REQUEST['product_view']:0,
										"delete"=>isset($_REQUEST['product_delete'])?$_REQUEST['product_delete']:0
							  ],
							  "store"=>['menu_icone'=>plugins_url( 'gym-management/assets/images/icon/store.png'),
							              "menu_title"=>'Store',
										  "page_link"=>'store',
										 "own_data" => isset($_REQUEST['store_own_data'])?$_REQUEST['store_own_data']:0,
										 "add" => isset($_REQUEST['store_add'])?$_REQUEST['store_add']:0,
										"edit"=>isset($_REQUEST['store_edit'])?$_REQUEST['store_edit']:0,
										"view"=>isset($_REQUEST['store_view'])?$_REQUEST['store_view']:0,
										"delete"=>isset($_REQUEST['store_delete'])?$_REQUEST['store_delete']:0
							  ],
							  "news_letter"=>['menu_icone'=>plugins_url( 'gym-management/assets/images/icon/newsletter.png'),
							            "menu_title"=>'Newsletter',
										"page_link"=>'news_letter',
										 "own_data" => isset($_REQUEST['news_letter_own_data'])?$_REQUEST['news_letter_own_data']:0,
										 "add" => isset($_REQUEST['news_letter_add'])?$_REQUEST['news_letter_add']:0,
										"edit"=>isset($_REQUEST['news_letter_edit'])?$_REQUEST['news_letter_edit']:0,
										"view"=>isset($_REQUEST['news_letter_view'])?$_REQUEST['news_letter_view']:0,
										"delete"=>isset($_REQUEST['news_letter_delete'])?$_REQUEST['news_letter_delete']:0
							  ],
							  "message"=>['menu_icone'=>plugins_url( 'gym-management/assets/images/icon/message.png'),
							             "menu_title"=>'Message',
										 "page_link"=>'message',
										 "own_data" => isset($_REQUEST['message_own_data'])?$_REQUEST['message_own_data']:0,
										 "add" => isset($_REQUEST['message_add'])?$_REQUEST['message_add']:0,
										"edit"=>isset($_REQUEST['message_edit'])?$_REQUEST['message_edit']:0,
										"view"=>isset($_REQUEST['message_view'])?$_REQUEST['message_view']:0,
										"delete"=>isset($_REQUEST['message_delete'])?$_REQUEST['message_delete']:0
							  ],
							  
							   "notice"=>['menu_icone'=>plugins_url( 'gym-management/assets/images/icon/notice.png'),
							           "menu_title"=>'Notice',
									   "page_link"=>'notice',
										 "own_data" => isset($_REQUEST['notice_own_data'])?$_REQUEST['notice_own_data']:0,
										 "add" => isset($_REQUEST['notice_add'])?$_REQUEST['notice_add']:0,
										"edit"=>isset($_REQUEST['notice_edit'])?$_REQUEST['notice_edit']:0,
										"view"=>isset($_REQUEST['notice_view'])?$_REQUEST['notice_view']:0,
										"delete"=>isset($_REQUEST['notice_delete'])?$_REQUEST['notice_delete']:0
							  ],
							  
							   "reservation"=>['menu_icone'=>plugins_url( 'gym-management/assets/images/icon/reservation.png'),							       
								         "menu_title"=>'Reservation',
										 "page_link"=>'reservation',
										 "own_data" => isset($_REQUEST['reservation_own_data'])?$_REQUEST['reservation_own_data']:0,
										 "add" => isset($_REQUEST['reservation_add'])?$_REQUEST['reservation_add']:0,
										"edit"=>isset($_REQUEST['reservation_edit'])?$_REQUEST['reservation_edit']:0,
										"view"=>isset($_REQUEST['reservation_view'])?$_REQUEST['reservation_view']:0,
										"delete"=>isset($_REQUEST['reservation_delete'])?$_REQUEST['reservation_delete']:0
							  ],
							  
							   "account"=>['menu_icone'=>plugins_url( 'gym-management/assets/images/icon/account.png'),
							              "menu_title"=>'Account',
										  "page_link"=>'account',
										 "own_data" => isset($_REQUEST['account_own_data'])?$_REQUEST['account_own_data']:0,
										 "add" => isset($_REQUEST['account_add'])?$_REQUEST['account_add']:0,
										"edit"=>isset($_REQUEST['account_edit'])?$_REQUEST['account_edit']:0,
										"view"=>isset($_REQUEST['account_view'])?$_REQUEST['account_view']:0,
										"delete"=>isset($_REQUEST['account_delete'])?$_REQUEST['account_delete']:0
							  ],
							   "subscription_history"=>['menu_icone'=>plugins_url( 'gym-management/assets/images/icon/subscription_history.png'),
							             "menu_title"=>'Subscription History',
										 "page_link"=>'subscription_history',
										 "own_data" => isset($_REQUEST['subscription_history_own_data'])?$_REQUEST['subscription_history_own_data']:0,
										 "add" => isset($_REQUEST['subscription_history_add'])?$_REQUEST['subscription_history_add']:0,
										"edit"=>isset($_REQUEST['subscription_history_edit'])?$_REQUEST['subscription_history_edit']:0,
										"view"=>isset($_REQUEST['subscription_history_view'])?$_REQUEST['subscription_history_view']:0,
										"delete"=>isset($_REQUEST['subscription_history_delete'])?$_REQUEST['subscription_history_delete']:0
							  ]							  
						];

						$result=update_option( 'gmgt_access_right_member',$role_access_right);
						wp_redirect ( admin_url() . 'admin.php?page=gmgt_access_right&tab=member&message=1');
}
$access_right=get_option('gmgt_access_right_member');

if(isset($_REQUEST['message']))
{
	$message =$_REQUEST['message'];
	if($message == 1)
	{?>
		<div id="message" class="updated below-h2 ">
			<p>
			<?php 
				_e('Member Access Right Updated Successfully.','gym_mgt');
			?></p>
		</div>
		<?php 
	}
}
?>
<div class="page-inner" style="min-height:1631px !important"> <!--- MAIN INNER DIV START-->
	<div id="main-wrapper"><!--- MAIN WRAPPER DIV START -->
	    <div class="panel panel-white"><!--- PANEL WHITE DIV START-->
			<div class="panel-body"><!--- PANEL BODY DIV START-->
				<h2>
					<?php echo esc_html( __( 'Member Access Right', 'gym_mgt')); ?>
				</h2>
		        <div class="panel-body"><!--- PANEL BODY DIV START-->
					<form name="student_form" action="" method="post" class="form-horizontal" id="access_right_form"><!--- MEMBER ACCESS RIGHT FORM START-->
						<div class="row access_right_hed">
							<div class="col-md-2 col-sm-2"><?php _e('Menu','gym_mgt');?></div>
							<div class="col-md-2 col-sm-2 padding_left_heading"><?php _e('Own Data','gym_mgt');?></div>
							<div class="col-md-2 col-sm-2 padding_left_22"><?php _e('View','gym_mgt');?></div>
							<div class="col-md-2 col-sm-2 padding_left_18"><?php _e('Add','gym_mgt');?></div>
							<div class="col-md-2 col-sm-2 padding_left_18"><?php _e('Edit','gym_mgt');?></div>
							<div class="col-md-2 col-sm-2 padding_left_12"><?php _e('Delete ','gym_mgt');?></div>
						</div>					
						<div class="access_right_menucroll row"><!--- Access ROW DIV START-->	
							<!-- staff_member module code  -->						
							<div class="row">
								<div class="col-sm-2 col-md-2">
									<span class="menu-label">
										<?php _e('Staff Members','gym_mgt');?>
									</span>
								</div>
								<div class="col-sm-2 col-md-2">
									<div class="checkbox">
										<label>
											<input type="checkbox" <?php echo checked($access_right['member']['staff_member']['own_data'],1);?> value="1" name="staff_member_own_data">	              
										</label>
									</div>
								</div>
								<div class="col-sm-2 col-md-2">
									<div class="checkbox">
										<label>
											<input type="checkbox" <?php echo checked($access_right['member']['staff_member']['view'],1);?> value="1" name="staff_member_view">	              
										</label>
									</div>
								</div>
								<div class="col-sm-2 col-md-2">
									<div class="checkbox">
										<label>
											<input type="checkbox" <?php echo checked($access_right['member']['staff_member']['add'],1);?> value="1" name="staff_member_add" disabled>	              
										</label>
									</div>
								</div>
								<div class="col-sm-2 col-md-2">
									<div class="checkbox">
										<label>
											<input type="checkbox" <?php echo checked($access_right['member']['staff_member']['edit'],1);?> value="1" name="staff_member_edit" disabled>	              
										</label>
									</div>
								</div>
								
								<div class="col-sm-2 col-md-1">
									<div class="checkbox">
										<label>
											<input type="checkbox" <?php echo checked($access_right['member']['staff_member']['delete'],1);?> value="1" name="staff_member_delete" disabled>	              
										</label>
									</div>
								</div>
								
							</div>
							
							<!-- staff_member module code end -->
							
							<!-- Membership Type module code  -->
							
							   <div class="row">
								<div class="col-sm-2 col-md-2">
									<span class="menu-label">
										<?php _e('Membership Type','gym_mgt');?>
									</span>
								</div>
								
								<div class="col-sm-2 col-md-2">
									<div class="checkbox">
										<label>
											<input type="checkbox" <?php echo checked($access_right['member']['membership']['own_data'],1);?> value="1" name="membership_own_data">	              
										</label>
									</div>
								</div>
								
								<div class="col-sm-2 col-md-2">
									<div class="checkbox">
										<label>
											<input type="checkbox" <?php echo checked($access_right['member']['membership']['view'],1);?> value="1" name="membership_view">	              
										</label>
									</div>
								</div>
								<div class="col-sm-2 col-md-2">
									<div class="checkbox">
										<label>
											<input type="checkbox" <?php echo checked($access_right['member']['membership']['add'],1);?> value="1" name="membership_add">	              
										</label>
									</div>
								</div>
								<div class="col-sm-2 col-md-2">
									<div class="checkbox">
										<label>
											<input type="checkbox" <?php echo checked($access_right['member']['membership']['edit'],1);?> value="1" name="membership_edit">	              
										</label>
									</div>
								</div>
								
								<div class="col-sm-2 col-md-1">
									<div class="checkbox">
										<label>
											<input type="checkbox" <?php echo checked($access_right['member']['membership']['delete'],1);?> value="1" name="membership_delete">	              
										</label>
									</div>
								</div>
								
							</div>
							
							<!-- Membership Type module code  -->
							
							   <div class="row">
								<div class="col-sm-2 col-md-2">
									<span class="menu-label">
										<?php _e('Group','gym_mgt');?>
									</span>
								</div>
								
								<div class="col-sm-2 col-md-2">
									<div class="checkbox">
										<label>
											<input type="checkbox" <?php echo checked($access_right['member']['group']['own_data'],1);?> value="1" name="group_own_data">	              
										</label>
									</div>
								</div>
								<div class="col-sm-2 col-md-2">
									<div class="checkbox">
										<label>
											<input type="checkbox" <?php echo checked($access_right['member']['group']['view'],1);?> value="1" name="group_view">	              
										</label>
									</div>
								</div>
								<div class="col-sm-2 col-md-2">
									<div class="checkbox">
										<label>
											<input type="checkbox" <?php echo checked($access_right['member']['group']['add'],1);?> value="1" name="group_add">	              
										</label>
									</div>
								</div>
								<div class="col-sm-2 col-md-2">
									<div class="checkbox">
										<label>
											<input type="checkbox" <?php echo checked($access_right['member']['group']['edit'],1);?> value="1" name="group_edit">	              
										</label>
									</div>
								</div>
								
								<div class="col-sm-2 col-md-1">
									<div class="checkbox">
										<label>
											<input type="checkbox" <?php echo checked($access_right['member']['group']['delete'],1);?> value="1" name="group_delete">	              
										</label>
									</div>
								</div>
								
							</div>
							
							
							<!-- Member module code  -->
							
							   <div class="row">
								<div class="col-sm-2 col-md-2">
									<span class="menu-label">
										<?php _e('Member','gym_mgt');?>
									</span>
								</div>
								
								<div class="col-sm-2 col-md-2">
									<div class="checkbox">
											<label>
												<input type="checkbox" <?php echo checked($access_right['member']['member']['own_data'],1);?> value="1"  name="member_own_data">
											</label>
									</div>
								</div>
								<div class="col-sm-2 col-md-2">
									<div class="checkbox">
										<label>
											<input type="checkbox" <?php echo checked($access_right['member']['member']['view'],1);?> value="1" name="member_view">	              
										</label>
									</div>
								</div>
								<div class="col-sm-2 col-md-2">
									<div class="checkbox">
										<label>
											<input type="checkbox" <?php echo checked($access_right['member']['member']['add'],1);?> value="1" name="member_add" disabled>	              
										</label>
									</div>
								</div>
								<div class="col-sm-2 col-md-2">
									<div class="checkbox">
										<label>
											<input type="checkbox" <?php echo checked($access_right['member']['member']['edit'],1);?> value="1" name="member_edit" disabled>	              
										</label>
									</div>
								</div>
								
								<div class="col-sm-2 col-md-1">
									<div class="checkbox">
										<label>
											<input type="checkbox" <?php echo checked($access_right['member']['member']['delete'],1);?> value="1" name="member_delete" disabled>	              
										</label>
									</div>
								</div>
								
							</div>
							
							
							<!-- Activity module code  -->
							
							   <div class="row">
								<div class="col-sm-2 col-md-2">
									<span class="menu-label">
										<?php _e('Activity','gym_mgt');?>
									</span>
								</div>
								<div class="col-sm-2 col-md-2">
									<div class="checkbox">
											<label>
												<input type="checkbox" <?php echo checked($access_right['member']['activity']['own_data'],1);?> value="1"  name="activity_own_data">
											</label>
									</div>
								</div>
								<div class="col-sm-2 col-md-2">
									<div class="checkbox">
										<label>
											<input type="checkbox" <?php echo checked($access_right['member']['activity']['view'],1);?> value="1" name="activity_view">	              
										</label>
									</div>
								</div>
								<div class="col-sm-2 col-md-2">
									<div class="checkbox">
										<label>
											<input type="checkbox" <?php echo checked($access_right['member']['activity']['add'],1);?> value="1" name="activity_add">	              
										</label>
									</div>
								</div>
								<div class="col-sm-2 col-md-2">
									<div class="checkbox">
										<label>
											<input type="checkbox" <?php echo checked($access_right['member']['activity']['edit'],1);?> value="1" name="activity_edit">	              
										</label>
									</div>
								</div>
								
								<div class="col-sm-2 col-md-1">
									<div class="checkbox">
										<label>
											<input type="checkbox" <?php echo checked($access_right['member']['activity']['delete'],1);?> value="1" name="activity_delete">	              
										</label>
									</div>
								</div>
								
							</div>
							
							
							<!--Class schedule module code  -->
							
							   <div class="row">
								<div class="col-sm-2 col-md-2">
									<span class="menu-label">
										<?php _e('Class schedule','gym_mgt');?>
									</span>
								</div>							
								<div class="col-sm-2 col-md-2">
									<div class="checkbox">
											<label>
												<input type="checkbox" <?php echo checked($access_right['member']['class-schedule']['own_data'],1);?> value="1"  name="class_schedule_own_data">
											</label>
									</div>
								</div>							
								<div class="col-sm-2 col-md-2">
									<div class="checkbox">
										<label>
											<input type="checkbox" <?php echo checked($access_right['member']['class-schedule']['view'],1);?> value="1" name="class_schedule_view" >	              
										</label>
									</div>
								</div>
								
								<div class="col-sm-2 col-md-2">
									<div class="checkbox">
										<label>
											<input type="checkbox" <?php echo checked($access_right['member']['class-schedule']['add'],1);?> value="1" name="class_schedule_add">	              
										</label>
									</div>
								</div>
								<div class="col-sm-2 col-md-2">
									<div class="checkbox">
										<label>
											<input type="checkbox" <?php echo checked($access_right['member']['class-schedule']['edit'],1);?> value="1" name="class_schedule_edit">	              
										</label>
									</div>
								</div>
								
								<div class="col-sm-2 col-md-1">
									<div class="checkbox">
										<label>
											<input type="checkbox" <?php echo checked($access_right['member']['class-schedule']['delete'],1);?> value="1" name="class_schedule_delete">	              
										</label>
									</div>
								</div>							
							</div>					
							
							<!-- attendence module code  -->
							
							   <div class="row">
								<div class="col-sm-2 col-md-2">
									<span class="menu-label">
										<?php _e('Attendence','gym_mgt');?>
									</span>
								</div>
								<div class="col-sm-2 col-md-2">
									<div class="checkbox">
											<label>
												<input type="checkbox" <?php echo checked($access_right['member']['attendence']['own_data'],1);?> value="1"  name="attendence_own_data" disabled>
											</label>
									</div>
								</div>
								<div class="col-sm-2 col-md-2">
									<div class="checkbox">
										<label>
											<input type="checkbox" <?php echo checked($access_right['member']['attendence']['view'],1);?> value="1" name="attendence_view" >	              
										</label>
									</div>
								</div>
								<div class="col-sm-2 col-md-2">
									<div class="checkbox">
										<label>
											<input type="checkbox" <?php echo checked($access_right['member']['attendence']['add'],1);?> value="1" name="attendence_add" disabled>	              
										</label>
									</div>
								</div>
								<div class="col-sm-2 col-md-2">
									<div class="checkbox">
										<label>
											<input type="checkbox" <?php echo checked($access_right['member']['attendence']['edit'],1);?> value="1" name="attendence_edit" disabled>	              
										</label>
									</div>
								</div>
								
								<div class="col-sm-2 col-md-1">
									<div class="checkbox">
										<label>
											<input type="checkbox" <?php echo checked($access_right['member']['attendence']['delete'],1);?> value="1" name="attendence_delete" disabled>	              
										</label>
									</div>
								</div>
								
							</div>
							
							
							<!-- Assigned Workouts module code  -->
							
							   <div class="row">
								<div class="col-sm-2 col-md-2">
									<span class="menu-label">
										<?php _e('Assigned Workouts','gym_mgt');?>
									</span>
								</div>
								
								<div class="col-sm-2 col-md-2">
									<div class="checkbox">
											<label>
												<input type="checkbox" <?php echo checked($access_right['member']['assign-workout']['own_data'],1);?> value="1"  name="assign_workout_own_data" disabled>
											</label>
									</div>
								</div>
								
								<div class="col-sm-2 col-md-2">
									<div class="checkbox">
										<label>
											<input type="checkbox" <?php echo checked($access_right['member']['assign-workout']['view'],1);?> value="1" name="assign_workout_view">	              
										</label>
									</div>
								</div>
								<div class="col-sm-2 col-md-2">
									<div class="checkbox">
										<label>
											<input type="checkbox" <?php echo checked($access_right['member']['assign-workout']['add'],1);?> value="1" name="assign_workout_add" >	              
										</label>
									</div>
								</div>
								<div class="col-sm-2 col-md-2">
									<div class="checkbox">
										<label>
											<input type="checkbox" <?php echo checked($access_right['member']['assign-workout']['edit'],1);?> value="1" name="assign_workout_edit" disabled>	              
										</label>
									</div>
								</div>
								
								<div class="col-sm-2 col-md-1">
									<div class="checkbox">
										<label>
											<input type="checkbox" <?php echo checked($access_right['member']['assign-workout']['delete'],1);?> value="1" name="assign_workout_delete" >	              
										</label>
									</div>
								</div>
								
							</div>
							<!-- Nutrition Schedule module code  -->
							
							   <div class="row">
								<div class="col-sm-2 col-md-2">
									<span class="menu-label">
										<?php _e('Nutrition Schedule','gym_mgt');?>
									</span>
								</div>
								
								<div class="col-sm-2 col-md-2">
									<div class="checkbox">
										<label>
											<input type="checkbox" <?php echo checked($access_right['member']['nutrition']['own_data'],1);?> value="1" name="nutrition_own_data" disabled>
										</label>
									</div>
								</div>
								<div class="col-sm-2 col-md-2">
									<div class="checkbox">
										<label>
											<input type="checkbox" <?php echo checked($access_right['member']['nutrition']['view'],1);?> value="1" name="nutrition_view">	              
										</label>
									</div>
								</div>
								<div class="col-sm-2 col-md-2">
									<div class="checkbox">
										<label>
											<input type="checkbox" <?php echo checked($access_right['member']['nutrition']['add'],1);?> value="1" name="nutrition_add">	              
										</label>
									</div>
								</div>
								<div class="col-sm-2 col-md-2">
									<div class="checkbox">
										<label>
											<input type="checkbox" <?php echo checked($access_right['member']['nutrition']['edit'],1);?> value="1" name="nutrition_edit" disabled>	              
										</label>
									</div>
								</div>
								
								<div class="col-sm-2 col-md-1">
									<div class="checkbox">
										<label>
											<input type="checkbox" <?php echo checked($access_right['member']['nutrition']['delete'],1);?> value="1" name="nutrition_delete">	              
										</label>
									</div>
								</div>							
							</div>
							
							<!-- workouts module code  -->
							
							   <div class="row">
								<div class="col-sm-2 col-md-2">
									<span class="menu-label">
										<?php _e('Workouts','gym_mgt');?>
									</span>
								</div>
								
								<div class="col-sm-2 col-md-2">
									<div class="checkbox">
										<label>
											<input type="checkbox" <?php echo checked($access_right['member']['workouts']['own_data'],1);?> value="1" name="workouts_own_data" disabled>
										</label>
									</div>
								</div>
								
								<div class="col-sm-2 col-md-2">
									<div class="checkbox">
										<label>
											<input type="checkbox" <?php echo checked($access_right['member']['workouts']['view'],1);?> value="1" name="workouts_view">	              
										</label>
									</div>
								</div>
								<div class="col-sm-2 col-md-2">
									<div class="checkbox">
										<label>
											<input type="checkbox" <?php echo checked($access_right['member']['workouts']['add'],1);?> value="1" name="workouts_add" >	              
										</label>
									</div>
								</div>
								<div class="col-sm-2 col-md-2">
									<div class="checkbox">
										<label>
											<input type="checkbox" <?php echo checked($access_right['member']['workouts']['edit'],1);?> value="1" name="workouts_edit" >	              
										</label>
									</div>
								</div>
								
								<div class="col-sm-2 col-md-1">
									<div class="checkbox">
										<label>
											<input type="checkbox" <?php echo checked($access_right['member']['workouts']['delete'],1);?> value="1" name="workouts_delete" >	              
										</label>
									</div>
								</div>
								
							</div>
							
							<!-- Accountant module code  -->
							
							   <div class="row">
								<div class="col-sm-2 col-md-2">
									<span class="menu-label">
										<?php _e('Accountant','gym_mgt');?>
									</span>
								</div>
								
								<div class="col-sm-2 col-md-2">
									<div class="checkbox">
											<label>
												<input type="checkbox" <?php echo checked($access_right['member']['accountant']['own_data'],1);?> value="1" name="accountant_own_data" disabled>
											</label>
									</div>
								</div>
								
								<div class="col-sm-2 col-md-2">
									<div class="checkbox">
										<label>
											<input type="checkbox" <?php echo checked($access_right['member']['accountant']['view'],1);?> value="1" name="accountant_view" >	              
										</label>
									</div>
								</div>
								<div class="col-sm-2 col-md-2">
									<div class="checkbox">
										<label>
											<input type="checkbox" <?php echo checked($access_right['member']['accountant']['add'],1);?> value="1" name="accountant_add" disabled>	              
										</label>
									</div>
								</div>
								<div class="col-sm-2 col-md-2">
									<div class="checkbox">
										<label>
											<input type="checkbox" <?php echo checked($access_right['member']['accountant']['edit'],1);?> value="1" name="accountant_edit" disabled>	              
										</label>
									</div>
								</div>
								
								<div class="col-sm-2 col-md-1">
									<div class="checkbox">
										<label>
											<input type="checkbox" <?php echo checked($access_right['member']['accountant']['delete'],1);?> value="1" name="accountant_delete" disabled>	              
										</label>
									</div>
								</div>
								
							</div>
							
							<!-- Fee Payment module code  -->
							
							   <div class="row">
								<div class="col-sm-2 col-md-2">
									<span class="menu-label">
										<?php _e('Membership Payment','gym_mgt');?>
									</span>
								</div>
								
								<div class="col-sm-2 col-md-2">
									<div class="checkbox">
											<label>
												<input type="checkbox" <?php echo checked($access_right['member']['membership_payment']['own_data'],1);?> value="1" name="membership_payment_own_data" >
											</label>
									</div>
								</div>
								
								<div class="col-sm-2 col-md-2">
									<div class="checkbox">
										<label>
											<input type="checkbox" <?php echo checked($access_right['member']['membership_payment']['view'],1);?> value="1" name="membership_payment_view" >	              
										</label>
									</div>
								</div>
								<div class="col-sm-2 col-md-2">
									<div class="checkbox">
										<label>
											<input type="checkbox" <?php echo checked($access_right['member']['membership_payment']['add'],1);?> value="1" name="membership_payment_add" disabled>	              
										</label>
									</div>
								</div>
								<div class="col-sm-2 col-md-2">
									<div class="checkbox">
										<label>
											<input type="checkbox" <?php echo checked($access_right['member']['membership_payment']['edit'],1);?> value="1" name="membership_payment_edit" disabled>	              
										</label>
									</div>
								</div>
								
								<div class="col-sm-2 col-md-1">
									<div class="checkbox">
										<label>
											<input type="checkbox" <?php echo checked($access_right['member']['membership_payment']['delete'],1);?> value="1" name="membership_payment_delete" disabled>	              
										</label>
									</div>
								</div>
								
							</div>
							
							
							<!-- Payment module code  -->
							
							   <div class="row">
								<div class="col-sm-2 col-md-2">
									<span class="menu-label">
										<?php _e('Payment','gym_mgt');?>
									</span>
								</div>
								
								<div class="col-sm-2 col-md-2">
									<div class="checkbox">
											<label>
												<input type="checkbox" <?php echo checked($access_right['member']['payment']['own_data'],1);?> value="1" name="payment_own_data" >
											</label>
									</div>
								</div>
								
								<div class="col-sm-2 col-md-2">
									<div class="checkbox">
										<label>
											<input type="checkbox" <?php echo checked($access_right['member']['payment']['view'],1);?> value="1" name="payment_view">	              
										</label>
									</div>
								</div>
								<div class="col-sm-2 col-md-2">
									<div class="checkbox">
										<label>
											<input type="checkbox" <?php echo checked($access_right['member']['payment']['add'],1);?> value="1" name="payment_add" >	              
										</label>
									</div>
								</div>
								<div class="col-sm-2 col-md-2">
									<div class="checkbox">
										<label>
											<input type="checkbox" <?php echo checked($access_right['member']['payment']['edit'],1);?> value="1" name="payment_edit" >	              
										</label>
									</div>
								</div>
								
								<div class="col-sm-2 col-md-1">
									<div class="checkbox">
										<label>
											<input type="checkbox" <?php echo checked($access_right['member']['payment']['delete'],1);?> value="1" name="payment_delete" >	              
										</label>
									</div>
								</div>
								
							</div>
							
							
							<!-- product module code  -->
							
							   <div class="row">
								<div class="col-sm-2 col-md-2">
									<span class="menu-label">
										<?php _e('Product','gym_mgt');?>
									</span>
								</div>
								
								<div class="col-sm-2 col-md-2">
									<div class="checkbox">
										<label>
											<input type="checkbox" <?php echo checked($access_right['member']['product']['own_data'],1);?> value="1" name="product_own_data" >
										</label>
									</div>
								</div>
								
								<div class="col-sm-2 col-md-2">
									<div class="checkbox">
										<label>
											<input type="checkbox" <?php echo checked($access_right['member']['product']['view'],1);?> value="1" name="product_view">	              
										</label>
									</div>
								</div>
								<div class="col-sm-2 col-md-2">
									<div class="checkbox">
										<label>
											<input type="checkbox" <?php echo checked($access_right['member']['product']['add'],1);?> value="1" name="product_add" >	              
										</label>
									</div>
								</div>
								<div class="col-sm-2 col-md-2">
									<div class="checkbox">
										<label>
											<input type="checkbox" <?php echo checked($access_right['member']['product']['edit'],1);?> value="1" name="product_edit" >	              
										</label>
									</div>
								</div>
								
								<div class="col-sm-2 col-md-1">
									<div class="checkbox">
										<label>
											<input type="checkbox" <?php echo checked($access_right['member']['product']['delete'],1);?> value="1" name="product_delete" >	              
										</label>
									</div>
								</div>
								
							</div>
							
							
							<!-- store module code  -->
							
							   <div class="row">
								<div class="col-sm-2 col-md-2">
									<span class="menu-label">
										<?php _e('Store','gym_mgt');?>
									</span>
								</div>
								
								<div class="col-sm-2 col-md-2">
									<div class="checkbox">
										<label>
											<input type="checkbox" <?php echo checked($access_right['member']['store']['own_data'],1);?> value="1" name="store_own_data" >
										</label>
									</div>
								</div>
								
								<div class="col-sm-2 col-md-2">
									<div class="checkbox">
										<label>
											<input type="checkbox" <?php echo checked($access_right['member']['store']['view'],1);?> value="1" name="store_view">	              
										</label>
									</div>
								</div>
								<div class="col-sm-2 col-md-2">
									<div class="checkbox">
										<label>
											<input type="checkbox" <?php echo checked($access_right['member']['store']['add'],1);?> value="1" name="store_add" >	              
										</label>
									</div>
								</div>
								<div class="col-sm-2 col-md-2">
									<div class="checkbox">
										<label>
											<input type="checkbox" <?php echo checked($access_right['member']['store']['edit'],1);?> value="1" name="store_edit" >	              
										</label>
									</div>
								</div>
								
								<div class="col-sm-2 col-md-1">
									<div class="checkbox">
										<label>
											<input type="checkbox" <?php echo checked($access_right['member']['store']['delete'],1);?> value="1" name="store_delete" >	              
										</label>
									</div>
								</div>
								
							</div>
							
							<!-- Newsletter module code  -->
							
							   <div class="row">
								<div class="col-sm-2 col-md-2">
									<span class="menu-label">
										<?php _e('Newsletter','gym_mgt');?>
									</span>
								</div>
								<div class="col-sm-2 col-md-2">
									<div class="checkbox">
										<label>
											<input type="checkbox" <?php echo checked($access_right['member']['news_letter']['own_data'],1);?> value="1" name="news_letter_own_data" disabled >
										</label>
									</div>
								</div>
								<div class="col-sm-2 col-md-2">
									<div class="checkbox">
										<label>
											<input type="checkbox" <?php echo checked($access_right['member']['news_letter']['view'],1);?> value="1" name="news_letter_view" >	              
										</label>
									</div>
								</div>
								
								<div class="col-sm-2 col-md-2">
									<div class="checkbox">
										<label>
											<input type="checkbox" <?php echo checked($access_right['member']['news_letter']['add'],1);?> value="1" name="news_letter_add" disabled>	              
										</label>
									</div>
								</div>
								<div class="col-sm-2 col-md-2">
									<div class="checkbox">
										<label>
											<input type="checkbox" <?php echo checked($access_right['member']['news_letter']['edit'],1);?> value="1" name="news_letter_edit" disabled>	              
										</label>
									</div>
								</div>
								
								<div class="col-sm-2 col-md-1">
									<div class="checkbox">
										<label>
											<input type="checkbox" <?php echo checked($access_right['member']['news_letter']['delete'],1);?> value="1" name="news_letter_delete" disabled>	              
										</label>
									</div>
								</div>							
							</div>						
							<!-- Message module code  -->						
							   <div class="row">
								<div class="col-sm-2 col-md-2">
									<span class="menu-label">
										<?php _e('Message','gym_mgt');?>
									</span>
								</div>							
								<div class="col-sm-2 col-md-2">
									<div class="checkbox">
										<label>
											<input type="checkbox" <?php echo checked($access_right['member']['message']['own_data'],1);?> value="1" name="message_own_data" disabled>
										</label>
									</div>
								</div>
								
								<div class="col-sm-2 col-md-2">
									<div class="checkbox">
										<label>
											<input type="checkbox" <?php echo checked($access_right['member']['message']['view'],1);?> value="1" name="message_view">	              
										</label>
									</div>
								</div>
								<div class="col-sm-2 col-md-2">
									<div class="checkbox">
										<label>
											<input type="checkbox" <?php echo checked($access_right['member']['message']['add'],1);?> value="1" name="message_add" >	              
										</label>
									</div>
								</div>
								<div class="col-sm-2 col-md-2">
									<div class="checkbox">
										<label>
											<input type="checkbox" <?php echo checked($access_right['member']['message']['edit'],1);?> value="1" name="message_edit" disabled>	              
										</label>
									</div>
								</div>
								
								<div class="col-sm-2 col-md-1">
									<div class="checkbox">
										<label>
											<input type="checkbox" <?php echo checked($access_right['member']['message']['delete'],1);?> value="1" name="message_delete" >	              
										</label>
									</div>
								</div>							
							</div>
							
							<!-- Notice module code  -->
							
							   <div class="row">
								<div class="col-sm-2 col-md-2">
									<span class="menu-label">
										<?php _e('Notice','gym_mgt');?>
									</span>
								</div>
								
								<div class="col-sm-2 col-md-2">
									<div class="checkbox">
										<label>
											<input type="checkbox" <?php echo checked($access_right['member']['notice']['own_data'],1);?> value="1" name="notice_own_data" >
										</label>
									</div>
								</div>
								<div class="col-sm-2 col-md-2">
									<div class="checkbox">
										<label>
											<input type="checkbox" <?php echo checked($access_right['member']['notice']['view'],1);?> value="1" name="notice_view" >	              
										</label>
									</div>
								</div>
								<div class="col-sm-2 col-md-2">
									<div class="checkbox">
										<label>
											<input type="checkbox" <?php echo checked($access_right['member']['notice']['add'],1);?> value="1" name="notice_add" disabled>	              
										</label>
									</div>
								</div>
								<div class="col-sm-2 col-md-2">
									<div class="checkbox">
										<label>
											<input type="checkbox" <?php echo checked($access_right['member']['notice']['edit'],1);?> value="1" name="notice_edit" disabled>	              
										</label>
									</div>
								</div>
								
								<div class="col-sm-2 col-md-1">
									<div class="checkbox">
										<label>
											<input type="checkbox" <?php echo checked($access_right['member']['notice']['delete'],1);?> value="1" name="notice_delete" disabled>	              
										</label>
									</div>
								</div>							
							</div>
							
							<!-- reservation module code  -->
							
							   <div class="row">
								<div class="col-sm-2 col-md-2">
									<span class="menu-label">
										<?php _e('Reservation','gym_mgt');?>
									</span>
								</div>
								<div class="col-sm-2 col-md-2">
									<div class="checkbox">
										<label>
											<input type="checkbox" <?php echo checked($access_right['member']['reservation']['own_data'],1);?> value="1" name="reservation_own_data" >
										</label>
									</div>
								</div>
								
								<div class="col-sm-2 col-md-2">
									<div class="checkbox">
										<label>
											<input type="checkbox" <?php echo checked($access_right['member']['reservation']['view'],1);?> value="1" name="reservation_view">	              
										</label>
									</div>
								</div>
								<div class="col-sm-2 col-md-2">
									<div class="checkbox">
										<label>
											<input type="checkbox" <?php echo checked($access_right['member']['reservation']['add'],1);?> value="1" name="reservation_add" >	              
										</label>
									</div>
								</div>
								<div class="col-sm-2 col-md-2">
									<div class="checkbox">
										<label>
											<input type="checkbox" <?php echo checked($access_right['member']['reservation']['edit'],1);?> value="1" name="reservation_edit" >	              
										</label>
									</div>
								</div>							
								<div class="col-sm-2 col-md-1">
									<div class="checkbox">
										<label>
											<input type="checkbox" <?php echo checked($access_right['member']['reservation']['delete'],1);?> value="1" name="reservation_delete" >	              
										</label>
									</div>
								</div>							
							</div>						
							
							<!-- Account module code  -->
							
							   <div class="row">
								<div class="col-sm-2 col-md-2">
									<span class="menu-label">
										<?php _e('Account','gym_mgt');?>
									</span>
								</div>
								<div class="col-sm-2 col-md-2">
									<div class="checkbox">
										<label>
											<input type="checkbox" <?php echo checked($access_right['member']['account']['own_data'],1);?> value="1" name="account_own_data" disabled>
										</label>
									</div>
								</div>
								<div class="col-sm-2 col-md-2">
									<div class="checkbox">
										<label>
											<input type="checkbox" <?php echo checked($access_right['member']['account']['view'],1);?> value="1" name="account_view">	              
										</label>
									</div>
								</div>
								<div class="col-sm-2 col-md-2">
									<div class="checkbox">
										<label>
											<input type="checkbox" <?php echo checked($access_right['member']['account']['add'],1);?> value="1" name="account_add" disabled>	              
										</label>
									</div>
								</div>
								<div class="col-sm-2 col-md-2">
									<div class="checkbox">
										<label>
											<input type="checkbox" <?php echo checked($access_right['member']['account']['edit'],1);?> value="1" name="account_edit" disabled>	              
										</label>
									</div>
								</div>
								
								<div class="col-sm-2 col-md-1">
									<div class="checkbox">
										<label>
											<input type="checkbox" <?php echo checked($access_right['member']['account']['delete'],1);?> value="1" name="account_delete" disabled>	              
										</label>
									</div>
								</div>
								
							</div>
							
							
							<!-- Subscription History module code  -->
							
							   <div class="row">
								<div class="col-sm-2 col-md-2">
									<span class="menu-label">
										<?php _e('Subscription History','gym_mgt');?>
									</span>
								</div>
								
								<div class="col-sm-2 col-md-2">
									<div class="checkbox">
										<label>
											<input type="checkbox" <?php echo checked($access_right['member']['subscription_history']['own_data'],1);?> value="1" name="subscription_history_own_data">
										</label>
									</div>
								</div>
								
								<div class="col-sm-2 col-md-2">
									<div class="checkbox">
										<label>
											<input type="checkbox" <?php echo checked($access_right['member']['subscription_history']['view'],1);?> value="1" name="subscription_history_view">	              
										</label>
									</div>
								</div>
								
								<div class="col-sm-2 col-md-2">
									<div class="checkbox">
										<label>
											<input type="checkbox" <?php echo checked($access_right['member']['subscription_history']['add'],1);?> value="1" name="subscription_history_add" disabled>	              
										</label>
									</div>
								</div>
								<div class="col-sm-2 col-md-2">
									<div class="checkbox">
										<label>
											<input type="checkbox" <?php echo checked($access_right['member']['subscription_history']['edit'],1);?> value="1" name="subscription_history_edit" disabled>	              
										</label>
									</div>
								</div>
								
								<div class="col-sm-2 col-md-1">
									<div class="checkbox">
										<label>
											<input type="checkbox" <?php echo checked($access_right['member']['subscription_history']['delete'],1);?> value="1" name="subscription_history_delete" disabled>	              
										</label>
									</div>
								</div>							
							</div>						
						</div>	<!--- Access ROW DIV END-->			
						<div class="col-sm-offset-2 col-sm-8 row_bottom">
							<input type="submit" value="<?php _e('Save', 'gym_mgt' ); ?>" name="save_access_right" class="btn btn-success"/>
						</div>
				    </form><!--- MEMBER Access RIGHT FORM END-->		
		        </div><!--- END PANEL BODY DIV -->
            </div><!--- END PANEL BODY DIV -->
        </div><!--- END PANEL WHITE DIV -->
    </div><!--- END MAIN WRAPPER DIV -->
</div><!--- END MAIN INNER DIV -->