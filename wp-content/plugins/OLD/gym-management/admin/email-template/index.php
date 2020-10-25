<?php
//UPDATE MAIL NOTIFICATION OPTION DATA
if(isset($_REQUEST['save_registration_template']))
{
	update_option('registration_title',$_REQUEST['registration_title']);
	update_option('registration_mailtemplate',$_REQUEST['registration_mailtemplate']);
} 
if(isset($_REQUEST['save_Member_Approved_Template'])){
	update_option('Member_Approved_Template_Subject',$_REQUEST['Member_Approved_Template_Subject']);
	update_option('Member_Approved_Template',$_REQUEST['Member_Approved_Template']);
} 
if(isset($_REQUEST['save_Other_User_in_system'])){
	update_option('Add_Other_User_in_System_Subject',$_REQUEST['Add_Other_User_in_System_Subject']);
	update_option('Add_Other_User_in_System_Template',$_REQUEST['Add_Other_User_in_System_Template']);
}
if(isset($_REQUEST['Save_Notice_Template'])){
	update_option('Add_Notice_Subject',$_REQUEST['Add_Notice_Subject']);
	update_option('Add_Notice_Template',$_REQUEST['Add_Notice_Template']);
}

if(isset($_REQUEST['Save_Member_Added_In_Group'])){
	update_option('Member_Added_In_Group_subject',$_REQUEST['Member_Added_In_Group_subject']);
	update_option('Member_Added_In_Group_Template',$_REQUEST['Member_Added_In_Group_Template']);
}

if(isset($_REQUEST['Save_Assign_Workouts'])){
	update_option('Assign_Workouts_Subject',$_REQUEST['Assign_Workouts_Subject']);
	update_option('Assign_Workouts_Template',$_REQUEST['Assign_Workouts_Template']);
}
if(isset($_REQUEST['save_sell_product'])){
	update_option('sell_product_subject',$_REQUEST['sell_product_subject']);
	update_option('sell_product_template',$_REQUEST['sell_product_template']);
}

if(isset($_REQUEST['Save_Reservation_Template'])){
	update_option('Add_Reservation_Subject',$_REQUEST['Add_Reservation_Subject']);
	update_option('Add_Reservation_Template',$_REQUEST['Add_Reservation_Template']);
}

if(isset($_REQUEST['save_generate_invoice'])){
	update_option('generate_invoice_subject',$_REQUEST['generate_invoice_subject']);
	update_option('generate_invoice_template',$_REQUEST['generate_invoice_template']);
}

if(isset($_REQUEST['Save_Assign_Nutrition_Schedule'])){
	update_option('Assign_Nutrition_Schedule_Subject',$_REQUEST['Assign_Nutrition_Schedule_Subject']);
	update_option('Assign_Nutrition_Schedule_Template',$_REQUEST['Assign_Nutrition_Schedule_Template']);
}
if(isset($_REQUEST['Save_Assign_Nutrition_Schedule'])){
	update_option('Assign_Nutrition_Schedule_Subject',$_REQUEST['Assign_Nutrition_Schedule_Subject']);
	update_option('Assign_Nutrition_Schedule_Template',$_REQUEST['Assign_Nutrition_Schedule_Template']);
}
if(isset($_REQUEST['save_add_income_template'])){
	update_option('add_income_subject',$_REQUEST['add_income_subject']);
	update_option('add_income_template',$_REQUEST['add_income_template']);
}
if(isset($_REQUEST['save_add_income_template'])){
	update_option('add_income_subject',$_REQUEST['add_income_subject']);
	update_option('add_income_template',$_REQUEST['add_income_template']);
}
if(isset($_REQUEST['savePaymentReceivedAgainstInvoiceTemplate'])){
	update_option('payment_received_against_invoice_subject',$_REQUEST['payment_received_against_invoice_subject']);
	update_option('payment_received_against_invoice_template',$_REQUEST['payment_received_against_invoice_template']);
}
if(isset($_REQUEST['saveMessageReceiveTemplate'])){
	update_option('message_received_subject',$_REQUEST['message_received_subject']);
	update_option('message_received_template',$_REQUEST['message_received_template']);
}

if(isset($_REQUEST['Submit_Workouts_Save'])){
	update_option('Submit_Workouts_Subject',$_REQUEST['Submit_Workouts_Subject']);
	update_option('Submit_Workouts_Template',$_REQUEST['Submit_Workouts_Template']);
}
?>
<div class="page-inner" style="min-height:1088px !important"><!-- PAGE INNNER DIV START-->	
	<div class="page-title">
		<h3><img src="<?php echo get_option( 'gmgt_system_logo' ) ?>" class="img-circle head_logo" width="40" height="40" /><?php echo get_option( 'gmgt_system_name' );?>
		</h3>
	</div>
	<div id="main-wrapper"><!-- MAIN WRAPPER DIV START-->	
		<div class="row"><!-- ROW DIV START-->	
			<div class="col-md-12"><!-- COL 12 DIV START-->	
				<div class="panel panel-white"><!-- PANEL WHITE DIV START-->	
					<div class="panel-body"><!--PANEL BODY DIV START-->	
						<div class="panel-group" id="accordion"><!--PANEL GROUP DIV START-->	
							<div class="panel panel-default"><!--PANEL DEFAULT DIV START-->	
								<div class="panel-heading">
									<h4 class="panel-title">
										<a class="accordion-toggle" data-toggle="collapse" data-parent="#accordion" href="#collapseOne">
										<?php _e('Member Registration Template','gym_mgt'); ?>
										</a>
									</h4>
								</div>
								<!---------Member REGISTRATION MAIL Notification Template--------->
								<div id="collapseOne" class="panel-collapse collapse in">
									<div class="panel-body">
										<form id="registration_email_template_form" class="form-horizontal" method="post" action="" name="parent_form">
											<div class="form-group">
												<label for="first_name" class="col-sm-3 control-label"><?php _e('Email Subject','gym_mgt');?> </label>
												<div class="col-md-8">
													<input class="form-control validate[required]" name="registration_title" id="registration_title" placeholder="<?php _e('Enter email subject','gym_mgt');?>" value="<?php print get_option('registration_title'); ?>">
												</div>
											</div>
											<div class="form-group">
												<label for="first_name" class="col-sm-3 control-label"><?php _e('Member Registration Template','gym_mgt'); ?> </label>
												<div class="col-md-8">
													<textarea style="min-height:200px;" name="registration_mailtemplate" class="form-control validate[required]"><?php print get_option('registration_mailtemplate'); ?></textarea>
												</div>
											</div>
											<div class="form-group">
												<div class="col-sm-offset-3 col-md-8">
													<label><?php _e('You can use following variables in the email template:','gym_mgt');?></label><br>				
													<label><strong>[GMGT_MEMBERNAME] - </strong><?php _e('The member name','gym_mgt');?></label><br>
													<label><strong>[GMGT_MEMBERID] - </strong><?php _e('The member id','gym_mgt');?></label><br>
													<label><strong>[GMGT_MEMBERSHIP] - </strong><?php _e('Membership name','gym_mgt');?></label><br>
													<label><strong>[GMGT_STARTDATE] - </strong><?php _e('Membership start date','gym_mgt');?></label><br>
													<label><strong>[GMGT_ENDDATE] - </strong><?php _e('Membership end date','gym_mgt');?></label><br>
													<label><strong>[GMGT_GYM_NAME] - </strong><?php _e('Gym Name','gym_mgt');?></label><br>
												</div>
											</div>
											<div class="col-sm-offset-3 col-sm-8">        	
												<input value="<?php  _e('Save','gym_mgt')?>" name="save_registration_template" class="btn btn-success" type="submit">
											</div>
										</form>
									</div>
								</div>
							</div><!--PANEL DEFAULT DIV END-->
							<!---------Member Approved by admin MAIL Notification Template--------->
							<div class="panel panel-default"><!--PANEL DEFAULT DIV START-->
								<div class="panel-heading">
								  <h4 class="panel-title">
									<a class="accordion-toggle" data-toggle="collapse" data-parent="#accordion" href="#collapsetwo">
									  <?php _e('Member Approved Template ','gym_mgt'); ?>
									</a>
								  </h4>
								</div>
								<div id="collapsetwo" class="panel-collapse collapse">
									<div class="panel-body">
										<form id="email_template_form" class="form-horizontal" method="post" action="" name="parent_form">
											<div class="form-group">
												<label for="learner_complete_quiz_notification_title" class="col-sm-3 control-label"><?php _e('Email Subject','gym_mgt');?> <span class="require-field">*</span></label>
												<div class="col-md-8">
													<input id="Member_Approved_Template_Subject" class="form-control validate[required]" name="Member_Approved_Template_Subject" id="Member_Approved_Template_Subject" placeholder="<?php _e('Enter email subject','gym_mgt');?>" value="<?php echo get_option('Member_Approved_Template_Subject'); ?>">
												</div>
											</div>
											<div class="form-group">
												<label for="learner_complete_quiz_notification_mailcontent" class="col-sm-3 control-label"><?php _e('Member Approved Template','gym_mgt');?><span class="require-field">*</span></label>
												<div class="col-md-8">
													<textarea id="Member_Approved_Template" name="Member_Approved_Template" class="form-control validate[required]"><?php echo get_option('Member_Approved_Template');?></textarea>
												</div>
											</div>
											<div class="form-group">
												<div class="col-sm-offset-3 col-md-8">
													<label><?php _e('You can use following variables in the email template:','gym_mgt');?></label><br>				
													<label><strong>[GMGT_MEMBERNAME] - </strong><?php _e('The member name','gym_mgt');?></label><br>
													<label><strong>[GMGT_GYM_NAME] - </strong><?php _e('Gym Name','gym_mgt');?></label><br>
													<label><strong>[GMGT_LOGIN_LINK] - </strong><?php _e('Login Link','gym_mgt');?></label><br>
												</div>
											</div>
											<div class="col-sm-offset-3 col-sm-8">
												<input type="submit" value="<?php  _e('Save','gym_mgt')?>" name="save_Member_Approved_Template" class="btn btn-success"/>
											 </div>
										</form>
									</div>
								</div>
							</div>  <!--PANEL DEFAULT DIV END-->					
							 <!---------Add Other User in system MAIL Notification Template--------->
							<div class="panel panel-default">  <!--PANEL DEFAULT DIV START-->		
								<div class="panel-heading">
								  <h4 class="panel-title">
									<a class="accordion-toggle" data-toggle="collapse" data-parent="#accordion" href="#collapsethree">
									  <?php _e('Add Other User in System Template ','gym_mgt'); ?>
									</a>
								  </h4>
								</div>
								<div id="collapsethree" class="panel-collapse collapse">
									<div class="panel-body">
										<form id="email_template_form" class="form-horizontal" method="post" action="" name="parent_form">
											<div class="form-group">
												<label for="learner_complete_quiz_notification_title" class="col-sm-3 control-label"><?php _e('Email Subject','gym_mgt');?> <span class="require-field">*</span></label>
												<div class="col-md-8">
													<input id="Add_Other_User_in_System_Subject" class="form-control validate[required]" name="Add_Other_User_in_System_Subject" id="Add_Other_User_in_System_Subject" placeholder="<?php _e('Enter email subject','gym_mgt');?>" value="<?php echo get_option('Add_Other_User_in_System_Subject'); ?>">
												</div>
											</div>
											<div class="form-group">
												<label for="learner_complete_quiz_notification_mailcontent" class="col-sm-3 control-label"><?php _e('Add Other User in System Template','gym_mgt');?><span class="require-field">*</span></label>
												<div class="col-md-8">
													<textarea id="Add_Other_User_in_System_Template" name="Add_Other_User_in_System_Template" class="form-control validate[required]"><?php echo get_option('Add_Other_User_in_System_Template');?></textarea>
												</div>
											</div>
											<div class="form-group">
												<div class="col-sm-offset-3 col-md-8">
													<label><?php _e('You can use following variables in the email template:','gym_mgt');?></label><br>				
													<label><strong>[GMGT_USERNAME] - </strong><?php _e('The User name','gym_mgt');?></label><br>
													<label><strong>[GMGT_GYM_NAME] - </strong><?php _e('Gym Name','gym_mgt');?></label><br>
													<label><strong>[GMGT_ROLE_NAME] - </strong><?php _e('Role Name','gym_mgt');?></label><br>
													<label><strong>[GMGT_Username] - </strong><?php _e('Username Name','gym_mgt');?></label><br>
													<label><strong>[GMGT_PASSWORD] - </strong><?php _e('Password','gym_mgt');?></label><br>
													<label><strong>[GMGT_LOGIN_LINK] - </strong><?php _e('Login Link','gym_mgt');?></label><br>
												</div>
											</div>
											<div class="col-sm-offset-3 col-sm-8">
												<input type="submit" value="<?php  _e('Save','gym_mgt')?>" name="save_Other_User_in_system" class="btn btn-success"/>
											</div>
										</form>
									</div>
								</div>
							</div>    <!--PANEL DEFAULT DIV END-->		
							 <!-----------Add Notice Template -->
							<div class="panel panel-default">  <!--PANEL DEFAULT DIV START-->		
								<div class="panel-heading">
								  <h4 class="panel-title">
									<a class="accordion-toggle" data-toggle="collapse" data-parent="#accordion" href="#collapseFour">
									  <?php _e('Add Notice Template','gym_mgt'); ?>
									</a>
								  </h4>
								</div>
								<div id="collapseFour" class="panel-collapse collapse">
									<div class="panel-body">
										<form id="email_template_form" class="form-horizontal" method="post" action="" name="parent_form">
											<div class="form-group">
												<label for="learner_complete_quiz_notification_title" class="col-sm-3 control-label"><?php _e('Email Subject','gym_mgt');?> <span class="require-field">*</span></label>
												<div class="col-md-8">
													<input class="form-control validate[required]" name="Add_Notice_Subject" id="Add_Notice_Subject" placeholder="<?php _e('Enter email subject','gym_mgt');?>" value="<?php echo get_option('Add_Notice_Subject'); ?>">
												</div>
											</div>
											<div class="form-group">
												<label for="learner_complete_quiz_notification_mailcontent" class="col-sm-3 control-label"><?php _e('Add Notice Template','gym_mgt');?><span class="require-field">*</span></label>
												<div class="col-md-8">
													<textarea id="Add_Notice_Template" name="Add_Notice_Template" class="form-control validate[required]"><?php echo get_option('Add_Notice_Template');?></textarea>
												</div>
											</div>
											<div class="form-group">
												<div class="col-sm-offset-3 col-md-8">
													<label><?php _e('You can use following variables in the email template:','gym_mgt');?></label><br>				
													<label><strong>[GMGT_USERNAME] - </strong><?php _e('The User name','gym_mgt');?></label><br>
													<label><strong>[GMGT_MEMBERNAME] - </strong><?php _e('The Member name','gym_mgt');?></label><br>
													<label><strong>[GMGT_NOTICE_TITLE] - </strong><?php _e('Notice Title','gym_mgt');?></label><br>
													<label><strong>[GMGT_NOTICE_FOR] - </strong><?php _e('Notice FOR ','gym_mgt');?></label><br>
													<label><strong>[GMGT_STARTDATE] - </strong><?php _e('Notice Start Date ','gym_mgt');?></label><br>
													<label><strong>[GMGT_ENDDATE] - </strong><?php _e('Notice End Date ','gym_mgt');?></label><br>
													<label><strong>[GMGT_COMMENT] - </strong><?php _e('Notice Description ','gym_mgt');?></label><br>
													<label><strong>[GMGT_NOTICE_LINK] - </strong><?php _e('Notice Link ','gym_mgt');?></label><br>
													<label><strong>[GMGT_GYM_NAME] - </strong><?php _e('Gym Name','gym_mgt');?></label><br>
												</div>
											</div>
											<div class="col-sm-offset-3 col-sm-8">
												<input type="submit" value="<?php  _e('Save','gym_mgt')?>" name="Save_Notice_Template" class="btn btn-success"/>
											 </div>
										</form>
									</div>
								</div>
							</div>   <!--PANEL DEFAULT DIV END-->		
						 <!-----------Member Added In Group ----------->
							<div class="panel panel-default">  <!--PANEL DEFAULT DIV START-->		
								<div class="panel-heading">
									<h4 class="panel-title">
										<a class="accordion-toggle" data-toggle="collapse" data-parent="#accordion" href="#collapseFive">
									  <?php _e('Member Added In Group Template','gym_mgt'); ?>
										</a>
									</h4>
								</div>
								<div id="collapseFive" class="panel-collapse collapse">
									<div class="panel-body">
										<form id="email_template_form" class="form-horizontal" method="post" action="" name="parent_form">
											<div class="form-group">
												<label for="learner_complete_quiz_notification_title" class="col-sm-3 control-label"><?php _e('Email Subject','gym_mgt');?> <span class="require-field">*</span></label>
												<div class="col-md-8">
													<input class="form-control validate[required]" name="Member_Added_In_Group_subject" id="Member_Added_In_Group_subject" placeholder="<?php _e('Enter email subject','gym_mgt');?>" value="<?php echo get_option('Member_Added_In_Group_subject'); ?>">
												</div>
											</div>
											<div class="form-group">
												<label for="learner_complete_quiz_notification_mailcontent" class="col-sm-3 control-label"><?php _e('Member Added In Group Template','gym_mgt');?><span class="require-field">*</span></label>
												<div class="col-md-8">
													<textarea id="Member_Added_In_Group_Template" name="Member_Added_In_Group_Template" class="form-control validate[required]"><?php echo get_option('Member_Added_In_Group_Template');?></textarea>
												</div>
											</div>
											<div class="form-group">
												<div class="col-sm-offset-3 col-md-8">
													<label><?php _e('You can use following variables in the email template:','gym_mgt');?></label><br>				
													<label><strong>[GMGT_USERNAME] - </strong><?php _e('The User name','gym_mgt');?></label><br>
													<label><strong>[GMGT_GROUPNAME] - </strong><?php _e('The Group name','gym_mgt');?></label><br>
													<label><strong>[GMGT_GYM_NAME] - </strong><?php _e('Gym Name','gym_mgt');?></label><br>
													
												</div>
											</div>
											<div class="col-sm-offset-3 col-sm-8">
												<input type="submit" value="<?php  _e('Save','gym_mgt')?>" name="Save_Member_Added_In_Group" class="btn btn-success"/>
											 </div>
										</form>
									</div>
								</div>
							</div> <!--PANEL DEFAULT DIV END-->							  
							 <!---Assign Workouts -->
							<div class="panel panel-default">  <!--PANEL DEFAULT DIV START-->		
								<div class="panel-heading">
								  <h4 class="panel-title">
									<a class="accordion-toggle" data-toggle="collapse" data-parent="#accordion" href="#collapseSix">
									  <?php _e('Assign Workouts Template','gym_mgt'); ?>
									</a>
								  </h4>
								</div>
								<div id="collapseSix" class="panel-collapse collapse">
									<div class="panel-body">
										<form id="email_template_form" class="form-horizontal" method="post" action="" name="parent_form">
											<div class="form-group">
												<label for="learner_complete_quiz_notification_title" class="col-sm-3 control-label"><?php _e('Email Subject','gym_mgt');?> <span class="require-field">*</span></label>
												<div class="col-md-8">
													<input class="form-control validate[required]" name="Assign_Workouts_Subject" id="Assign_Workouts_Subject" placeholder="<?php _e('Enter email subject','gym_mgt');?>" value="<?php echo get_option('Assign_Workouts_Subject'); ?>">
												</div>
											</div>
											<div class="form-group">
												<label for="learner_complete_quiz_notification_mailcontent" class="col-sm-3 control-label"><?php _e('Assign Workouts Template','gym_mgt');?><span class="require-field">*</span></label>
												<div class="col-md-8">
													<textarea id="Assign_Workouts_Template" name="Assign_Workouts_Template" class="form-control validate[required]"><?php echo get_option('Assign_Workouts_Template');?></textarea>
												</div>
											</div>
											<div class="form-group">
												<div class="col-sm-offset-3 col-md-8">
													<label><?php _e('You can use following variables in the email template:','gym_mgt');?></label><br>				
													<label><strong>[GMGT_MEMBERNAME] - </strong><?php _e('The Member Name','gym_mgt');?></label><br>
													<label><strong>[GMGT_STARTDATE] - </strong><?php _e('Workouts Start Date ','gym_mgt');?></label><br>
													<label><strong>[GMGT_ENDDATE] - </strong><?php _e('Workouts End Date ','gym_mgt');?></label><br>
													<label><strong>[GMGT_GYM_NAME] - </strong><?php _e('Gym Name','gym_mgt');?></label><br>
													<label><strong>[GMGT_PAGE_LINK] - </strong><?php _e('Page Link','gym_mgt');?></label><br>
												</div>
											</div>
											<div class="col-sm-offset-3 col-sm-8">
												<input type="submit" value="<?php  _e('Save','gym_mgt')?>" name="Save_Assign_Workouts" class="btn btn-success"/>
											 </div>
										</form>
									</div>
								</div>
							</div>   <!--PANEL DEFAULT DIV END-->								  
						  <!-------Sell Product------>
							<div class="panel panel-default">  <!--PANEL DEFAULT DIV START-->		
								<div class="panel-heading">
								  <h4 class="panel-title">
									<a class="accordion-toggle" data-toggle="collapse" data-parent="#accordion" href="#collapseseven">
									  <?php _e('Sell Product','gym_mgt'); ?>
									</a>
								  </h4>
								</div>
								<div id="collapseseven" class="panel-collapse collapse">
									<div class="panel-body">
										<form id="email_template_form" class="form-horizontal" method="post" action="" name="parent_form">
											<div class="form-group">
												<label for="learner_complete_quiz_notification_title" class="col-sm-3 control-label"><?php _e('Email Subject','gym_mgt');?> <span class="require-field">*</span></label>
												<div class="col-md-8">
													<input class="form-control validate[required]" name="sell_product_subject" id="sell_product_subject" placeholder="<?php _e('Enter email subject','gym_mgt');?>" value="<?php echo get_option('sell_product_subject'); ?>">
												</div>
											</div>
											<div class="form-group">
												<label for="learner_complete_quiz_notification_mailcontent" class="col-sm-3 control-label"><?php _e('Sell Product Template','gym_mgt');?><span class="require-field">*</span></label>
												<div class="col-md-8">
													<textarea id="sell_product_template" name="sell_product_template" class="form-control validate[required]"><?php echo get_option('sell_product_template');?></textarea>
												</div>
											</div>
											<div class="form-group">
												<div class="col-sm-offset-3 col-md-8">
													<label><?php _e('You can use following variables in the email template:','gym_mgt');?></label><br>				
													<label><strong>[GMGT_USERNAME] - </strong><?php _e('The User name','gym_mgt');?></label><br>
													<label><strong>[GMGT_PRODUCTNAME] - </strong><?php _e('The Product name','gym_mgt');?></label><br>
													<label><strong>[GMGT_GYM_NAME] - </strong><?php _e('Gym Name','gym_mgt');?></label><br>
													
												</div>
											</div>
											<div class="col-sm-offset-3 col-sm-8">
												<input type="submit" value="<?php  _e('Save','gym_mgt')?>" name="save_sell_product" class="btn btn-success"/>
											 </div>
										</form>
									</div>
								</div>
							</div>  <!--PANEL DEFAULT DIV END-->		
							<!------Generate Invoice----->
							<div class="panel panel-default">  <!--PANEL DEFAULT DIV START-->		
								<div class="panel-heading">
								  <h4 class="panel-title">
									<a class="accordion-toggle" data-toggle="collapse" data-parent="#accordion" href="#collapseeight">
									  <?php _e('Generate Invoice','gym_mgt'); ?>
									</a>
								  </h4>
								</div>
								<div id="collapseeight" class="panel-collapse collapse">
									<div class="panel-body">
										<form id="email_template_form" class="form-horizontal" method="post" action="" name="parent_form">
											<div class="form-group">
												<label for="learner_complete_quiz_notification_title" class="col-sm-3 control-label"><?php _e('Email Subject','gym_mgt');?> <span class="require-field">*</span></label>
												<div class="col-md-8">
													<input class="form-control validate[required]" name="generate_invoice_subject" id="generate_invoice_subject" placeholder="<?php _e('Enter email subject','gym_mgt');?>" value="<?php echo get_option('generate_invoice_subject'); ?>">
												</div>
											</div>
											<div class="form-group">
												<label for="learner_complete_quiz_notification_mailcontent" class="col-sm-3 control-label"><?php _e('Generate Invoice Template','gym_mgt');?><span class="require-field">*</span></label>
												<div class="col-md-8">
													<textarea id="generate_invoice_template" name="generate_invoice_template" class="form-control validate[required]"><?php echo get_option('generate_invoice_template');?></textarea>
												</div>
											</div>
											<div class="form-group">
												<div class="col-sm-offset-3 col-md-8">
													<label><?php _e('You can use following variables in the email template:','gym_mgt');?></label><br>				
													<label><strong>[GMGT_USERNAME] - </strong><?php _e('The User name','gym_mgt');?></label><br>
													<label><strong>[GMGT_GYM_NAME] - </strong><?php _e('Gym Name','gym_mgt');?></label><br>
													<label><strong>[GMGT_PAYMENT_LINK] - </strong><?php _e('Payment Link','gym_mgt');?></label><br>
												</div>
											</div>
											<div class="col-sm-offset-3 col-sm-8">
												<input type="submit" value="<?php  _e('Save','gym_mgt')?>" name="save_generate_invoice" class="btn btn-success"/>
											 </div>
										</form>
									</div>
								</div>
							</div>  <!--PANEL DEFAULT DIV END-->		
							<!------Add Income----->
							<div class="panel panel-default">  <!--PANEL DEFAULT DIV START-->		
								<div class="panel-heading">
								  <h4 class="panel-title">
									<a class="accordion-toggle" data-toggle="collapse" data-parent="#accordion" href="#collapsenine">
									  <?php _e('Add Income ','gym_mgt'); ?>
									</a>
								  </h4>
								</div>
								<div id="collapsenine" class="panel-collapse collapse">
									<div class="panel-body">
										<form id="email_template_form" class="form-horizontal" method="post" action="" name="parent_form">
											<div class="form-group">
												<label for="learner_complete_quiz_notification_title" class="col-sm-3 control-label"><?php _e('Email Subject','gym_mgt');?> <span class="require-field">*</span></label>
												<div class="col-md-8">
													<input class="form-control validate[required]" name="add_income_subject" id="add_income_subject" placeholder="<?php _e('Enter email subject','gym_mgt');?>" value="<?php echo get_option('add_income_subject'); ?>">
												</div>
											</div>
											<div class="form-group">
												<label for="learner_complete_quiz_notification_mailcontent" class="col-sm-3 control-label"><?php _e('Add Income Template','gym_mgt');?><span class="require-field">*</span></label>
												<div class="col-md-8">
													<textarea id="add_income_template" name="add_income_template" class="form-control validate[required]"><?php echo get_option('add_income_template');?></textarea>
												</div>
											</div>
											<div class="form-group">
												<div class="col-sm-offset-3 col-md-8">
													<label><?php _e('You can use following variables in the email template:','gym_mgt');?></label><br>				
													<label><strong>[GMGT_USERNAME] - </strong><?php _e('The User name','gym_mgt');?></label><br>
													<label><strong>[GMGT_GYM_NAME] - </strong><?php _e('Gym Name','gym_mgt');?></label><br>
													<label><strong>[GMGT_ROLE_NAME] - </strong><?php _e('Role Name','gym_mgt');?></label><br>
												</div>
											</div>
											<div class="col-sm-offset-3 col-sm-8">
												<input type="submit" value="<?php  _e('Save','gym_mgt')?>" name="save_add_income_template" class="btn btn-success"/>
											 </div>
										</form>
									</div>
								</div>
							</div>  <!--PANEL DEFAULT DIV END-->									
							 <!-----Assign Workouts -------->
							<div class="panel panel-default">  <!--PANEL DEFAULT DIV START-->		
								<div class="panel-heading">
								  <h4 class="panel-title">
									<a class="accordion-toggle" data-toggle="collapse" data-parent="#accordion" href="#collapseTen">
									  <?php _e('Add Reservation Template','gym_mgt'); ?>
									</a>
								  </h4>
								</div>
								<div id="collapseTen" class="panel-collapse collapse">
									<div class="panel-body">
										<form id="email_template_form" class="form-horizontal" method="post" action="" name="parent_form">
											<div class="form-group">
												<label for="learner_complete_quiz_notification_title" class="col-sm-3 control-label"><?php _e('Email Subject','gym_mgt');?> <span class="require-field">*</span></label>
												<div class="col-md-8">
													<input class="form-control validate[required]" name="Add_Reservation_Subject" id="Add_Reservation_Subject" placeholder="<?php _e('Enter email subject','gym_mgt');?>" value="<?php echo get_option('Add_Reservation_Subject'); ?>">
												</div>
											</div>
											<div class="form-group">
												<label for="learner_complete_quiz_notification_mailcontent" class="col-sm-3 control-label"><?php _e('Add Reservation Template','gym_mgt');?><span class="require-field">*</span></label>
												<div class="col-md-8">
													<textarea id="Add_Reservation_Template" name="Add_Reservation_Template" class="form-control validate[required]"><?php echo get_option('Add_Reservation_Template');?></textarea>
												</div>
											</div>
											<div class="form-group">
												<div class="col-sm-offset-3 col-md-8">
													<label><?php _e('You can use following variables in the email template:','gym_mgt');?></label><br>				
													<label><strong>[GMGT_STAFF_MEMBERNAME] - </strong><?php _e('Staff member name','gym_mgt');?></label><br>
													<label><strong>[GMGT_EVENT_NAME] - </strong><?php _e('Event Name','gym_mgt');?></label><br>
													<label><strong>[GMGT_EVENT_DATE] - </strong><?php _e('Event Date','gym_mgt');?></label><br>
													<label><strong>[GMGT_EVENT_PLACE] - </strong><?php _e('Event Place','gym_mgt');?></label><br>
													<label><strong>[GMGT_START_TIME] - </strong><?php _e('Event Start Time','gym_mgt');?></label><br>
													<label><strong>[GMGT_END_TIME] - </strong><?php _e('Event End  Time','gym_mgt');?></label><br>
													<label><strong>[GMGT_GYM_NAME] - </strong><?php _e('Gym Name','gym_mgt');?></label><br>
													<label><strong>[GMGT_PAGE_LINK] - </strong><?php _e('Page Link','gym_mgt');?></label><br>
													
												</div>
											</div>
											<div class="col-sm-offset-3 col-sm-8">
												<input type="submit" value="<?php  _e('Save','gym_mgt')?>" name="Save_Reservation_Template" class="btn btn-success"/>
											 </div>
										</form>
									</div>
								</div>
							</div>   <!--PANEL DEFAULT DIV END-->	
							<!-----------Assign_Nutrition_Schedule_Subject -->
							<div class="panel panel-default">  <!--PANEL DEFAULT DIV START-->		
								<div class="panel-heading">
								  <h4 class="panel-title">
									<a class="accordion-toggle" data-toggle="collapse" data-parent="#accordion" href="#collapseElevan">
									  <?php _e('Assign Nutrition Schedule Template','gym_mgt'); ?>
									</a>
								  </h4>
								</div>
								<div id="collapseElevan" class="panel-collapse collapse">
									<div class="panel-body">
										<form id="email_template_form" class="form-horizontal" method="post" action="" name="parent_form">
											<div class="form-group">
												<label for="learner_complete_quiz_notification_title" class="col-sm-3 control-label"><?php _e('Email Subject','gym_mgt');?> <span class="require-field">*</span></label>
												<div class="col-md-8">
													<input class="form-control validate[required]" name="Assign_Nutrition_Schedule_Subject" id="Assign_Nutrition_Schedule_Subject" placeholder="<?php _e('Enter email subject','gym_mgt');?>" value="<?php echo get_option('Assign_Nutrition_Schedule_Subject'); ?>">
												</div>
											</div>
											<div class="form-group">
												<label for="learner_complete_quiz_notification_mailcontent" class="col-sm-3 control-label"><?php _e('Generate Invoice Template','gym_mgt');?><span class="require-field">*</span></label>
												<div class="col-md-8">
													<textarea id="Assign_Nutrition_Schedule_Template" name="Assign_Nutrition_Schedule_Template" class="form-control validate[required]"><?php echo get_option('Assign_Nutrition_Schedule_Template');?></textarea>
												</div>
											</div>
											<div class="form-group">
												<div class="col-sm-offset-3 col-md-8">
													<label><?php _e('You can use following variables in the email template:','gym_mgt');?></label><br>				
													<label><strong>[GMGT_MEMBERNAME] - </strong><?php _e('The Member Name','gym_mgt');?></label><br>
													<label><strong>[GMGT_STARTDATE] - </strong><?php _e('Nutrition Start Date ','gym_mgt');?></label><br>
													<label><strong>[GMGT_ENDDATE] - </strong><?php _e('Nutrition End Date ','gym_mgt');?></label><br>
													<label><strong>[GMGT_GYM_NAME] - </strong><?php _e('Gym Name','gym_mgt');?></label><br>
													<label><strong>[GMGT_PAGE_LINK] - </strong><?php _e('Page Link','gym_mgt');?></label><br>												
												</div>
											</div>
											<div class="col-sm-offset-3 col-sm-8">
												<input type="submit" value="<?php  _e('Save','gym_mgt')?>" name="Save_Assign_Nutrition_Schedule" class="btn btn-success"/>
											 </div>
										</form>
									</div>
								</div>
							</div>   <!--PANEL DEFAULT DIV END-->								  
							<!-----------Submit Workouts Mail Notification -->
							<div class="panel panel-default">  <!--PANEL DEFAULT DIV START-->		
								<div class="panel-heading">
								  <h4 class="panel-title">
									<a class="accordion-toggle" data-toggle="collapse" data-parent="#accordion" href="#collapseTewlve">
									  <?php _e('Submit Workouts Template','gym_mgt'); ?>
									</a>
								  </h4>
								</div>
								<div id="collapseTewlve" class="panel-collapse collapse">
									 <div class="panel-body">
										<form id="email_template_form" class="form-horizontal" method="post" action="" name="parent_form">
											<div class="form-group">
												<label for="learner_complete_quiz_notification_title" class="col-sm-3 control-label"><?php _e('Email Subject','gym_mgt');?> <span class="require-field">*</span></label>
												<div class="col-md-8">
													<input class="form-control validate[required]" name="Submit_Workouts_Subject" id="Submit_Workouts_Subject" placeholder="<?php _e('Enter email subject','gym_mgt');?>" value="<?php echo get_option('Submit_Workouts_Subject'); ?>">
												</div>
											</div>
											<div class="form-group">
												<label for="learner_complete_quiz_notification_mailcontent" class="col-sm-3 control-label"><?php _e('Submit Workouts Template','gym_mgt');?><span class="require-field">*</span></label>
												<div class="col-md-8">
													<textarea id="Submit_Workouts_Template" name="Submit_Workouts_Template" class="form-control validate[required]"><?php echo get_option('Submit_Workouts_Template');?></textarea>
												</div>
											</div>
											<div class="form-group">
												<div class="col-sm-offset-3 col-md-8">
													<label><?php _e('You can use following variables in the email template:','gym_mgt');?></label><br>				
													<label><strong>[GMGT_STAFF_MEMBERNAME]  - </strong><?php _e(' Staff Member Name','gym_mgt');?></label><br>
													<label><strong>[GMGT_DAY_NAME] - </strong><?php _e('Workout Day Name ','gym_mgt');?></label><br>
													<label><strong>[GMGT_DATE] - </strong><?php _e('Workout Day','gym_mgt');?></label><br>
													<label><strong>[GMGT_GYM_NAME] - </strong><?php _e('Gym Name','gym_mgt');?></label><br>												
												</div>
											</div>
											<div class="col-sm-offset-3 col-sm-8">
												<input type="submit" value="<?php  _e('Save','gym_mgt')?>" name="Submit_Workouts_Save" class="btn btn-success"/>
											 </div>
									   </form>
									</div>
								</div>
							</div>   <!--PANEL DEFAULT DIV END-->		
							<!------Payment Received against Invoice----->
							<div class="panel panel-default"><!--PANEL DEFAULT DIV START-->		
								<div class="panel-heading">
								  <h4 class="panel-title">
									<a class="accordion-toggle" data-toggle="collapse" data-parent="#accordion" href="#collapsetwelve">
									  <?php _e('Payment Received against Invoice','gym_mgt'); ?>
									</a>
								  </h4>
								</div>
								<div id="collapsetwelve" class="panel-collapse collapse">
									<div class="panel-body">
										<form id="email_template_form" class="form-horizontal" method="post" action="" name="parent_form">
											<div class="form-group">
												<label for="learner_complete_quiz_notification_title" class="col-sm-3 control-label"><?php _e('Email Subject','gym_mgt');?> <span class="require-field">*</span></label>
												<div class="col-md-8">
													<input class="form-control validate[required]" name="payment_received_against_invoice_subject" id="payment_received_against_invoice_subject" placeholder="<?php _e('Enter email subject','gym_mgt');?>" value="<?php echo get_option('payment_received_against_invoice_subject'); ?>">
												</div>
											</div>
											<div class="form-group">
												<label for="learner_complete_quiz_notification_mailcontent" class="col-sm-3 control-label"><?php _e('Add Income Template','gym_mgt');?><span class="require-field">*</span></label>
												<div class="col-md-8">
													<textarea id="payment_received_against_invoice_template" name="payment_received_against_invoice_template" class="form-control validate[required]"><?php echo get_option('payment_received_against_invoice_template');?></textarea>
												</div>
											</div>
											<div class="form-group">
												<div class="col-sm-offset-3 col-md-8">
													<label><?php _e('You can use following variables in the email template:','gym_mgt');?></label><br>				
													<label><strong>[GMGT_USERNAME] - </strong><?php _e('The User name','gym_mgt');?></label><br>
													<label><strong>[GMGT_GYM_NAME] - </strong><?php _e('Gym Name','gym_mgt');?></label><br>
												</div>
											</div>
											<div class="col-sm-offset-3 col-sm-8">
												<input type="submit" value="<?php  _e('Save','gym_mgt')?>" name="savePaymentReceivedAgainstInvoiceTemplate" class="btn btn-success"/>
											 </div>
										</form>
									</div>
								</div>
							</div><!--PANEL DEFAULT DIV END-->		
							<!------Message Received----->
							<div class="panel panel-default"><!--PANEL DEFAULT DIV START-->		
								<div class="panel-heading">
									<h4 class="panel-title">
										<a class="accordion-toggle" data-toggle="collapse" data-parent="#accordion" href="#collapsethirteen">
									  <?php _e('Message Received','gym_mgt'); ?>
										</a>
									</h4>
								</div>
								<div id="collapsethirteen" class="panel-collapse collapse">
									<div class="panel-body">
										<form id="email_template_form" class="form-horizontal" method="post" action="" name="parent_form">
											<div class="form-group">
												<label for="learner_complete_quiz_notification_title" class="col-sm-3 control-label"><?php _e('Email Subject','gym_mgt');?> <span class="require-field">*</span></label>
												<div class="col-md-8">
													<input class="form-control validate[required]" name="message_received_subject" id="message_received_subject" placeholder="<?php _e('Enter email subject','gym_mgt');?>" value="<?php echo get_option('message_received_subject'); ?>">
												</div>
											</div>
											<div class="form-group">
												<label for="learner_complete_quiz_notification_mailcontent" class="col-sm-3 control-label"><?php _e('Add Income Template','gym_mgt');?><span class="require-field">*</span></label>
												<div class="col-md-8">
													<textarea id="message_received_template" name="message_received_template" class="form-control validate[required]"><?php echo get_option('message_received_template');?></textarea>
												</div>
											</div>
											<div class="form-group">
												<div class="col-sm-offset-3 col-md-8">
													<label><?php _e('You can use following variables in the email template:','gym_mgt');?></label><br>				
													<label><strong>[GMGT_RECEIVER_NAME] - </strong><?php _e('The Receiver name','gym_mgt');?></label><br>
													<label><strong>[GMGT_GYM_NAME] - </strong><?php _e('Gym Name','gym_mgt');?></label><br>
													<label><strong>[GMGT_SENDER_NAME] - </strong><?php _e('Sender Name','gym_mgt');?></label><br>
													<label><strong>[GMGT_MESSAGE_CONTENT] - </strong><?php _e('Message Content','gym_mgt');?></label><br>
												</div>
											</div>
											<div class="col-sm-offset-3 col-sm-8">
												<input type="submit" value="<?php  _e('Save','gym_mgt')?>" name="saveMessageReceiveTemplate" class="btn btn-success"/>
											 </div>
										</form>
									</div>
								</div>
							</div><!--PANEL DEFAULT DIV END-->		
						</div><!--PANEL GROUP DIV END-->	
					</div><!-- PANEL BODY DIV END-->	
				</div><!-- PANEL WHITE DIV END-->	
			</div><!-- COL 12 DIV END-->	
		</div><!-- ROW DIV END-->	
	</div><!-- MAIN WRAPPER DIV END-->	
</div><!-- PAGE INNNER DIV END-->	