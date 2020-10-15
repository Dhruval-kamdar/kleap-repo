<script type="text/javascript">
$(document).ready(function() 
{
	$('#message_form').validationEngine({promptPosition : "bottomRight",maxErrorsPerField: 1});	
	$('[data-toggle="tooltip"]').tooltip(); 
} );
</script>
<div class="mailbox-content"><!--MAILBOX CONTENT DIV STRAT-->
	<h2>
		<?php  
		$edit=0;
		if(isset($_REQUEST['action']) && $_REQUEST['action'] == 'edit')
		{
			 echo esc_html( __( 'Edit Message', 'gym_mgt') );
			 $edit=1;
			 $exam_data= get_exam_by_id($_REQUEST['exam_id']);
		}
		?>
	</h2>
	<?php
	if(isset($message))
		echo '<div id="message" class="updated below-h2"><p>'.$message.'</p></div>';
	?>
	<form name="class_form" action="" method="post" class="form-horizontal" id="message_form" style="padding-bottom: 50px;"><!--COMPOSE MAIL FORM STRAT-->
		<?php $action = isset($_REQUEST['action'])?$_REQUEST['action']:'insert';?>
		<input type="hidden" name="action" value="<?php echo $action;?>">
		<div class="form-group">
			<label class="col-sm-2 control-label" for="to"><?php _e('Message To','gym_mgt');?><span class="require-field">*</span></label>
				<div class="col-sm-8">
					<select name="receiver" class="form-control validate[required] text-input receiver message_to" id="to">
						<option value="member"><?php _e('Members','gym_mgt');?></option>	
						<option value="staff_member"><?php _e('Staff Members','gym_mgt');?></option>	
						<option value="accountant"><?php _e('Accountant','gym_mgt');?></option>	
						<?php echo MJ_gmgt_get_all_user_in_message();?>
					</select>
				</div>	
		</div>
		<!--nonce-->
		<?php wp_nonce_field( 'save_message_nonce' ); ?>
		<!--nonce-->								
		<div id="smgt_select_class" class="display_class_css">
			<div class="form-group">
				<label class="col-sm-2 control-label " for="sms_template"><?php _e('Select Class','gym_mgt');?></label>
				<div class="col-sm-8">
				
					 <select name="class_id"  id="class_list" class="form-control">
						<option value="all"><?php _e('All','gym_mgt');?></option>
						<?php
						  foreach(MJ_gmgt_get_allclass() as $classdata)
						  {  
						  ?>
						   <option  value="<?php echo $classdata['class_id'];?>" ><?php echo $classdata['class_name'];?></option>
					 <?php }?>
					</select>
				</div>
			</div>
		</div>
		<div class="form-group">
			<label class="col-sm-2 control-label" for="subject"><?php _e('Subject','gym_mgt');?><span class="require-field">*</span></label>
			<div class="col-sm-8">
			   <input id="subject" class="form-control validate[required,custom[popup_category_validation]] text-input onlyletter_number_space_validation" maxlength="100" type="text" name="subject" >
			</div>
		</div>
		<div class="form-group">
			<label class="col-sm-2 control-label" for="subject"><?php _e('Message Comment','gym_mgt');?><span class="require-field">*</span></label>
			<div class="col-sm-8">
			  <textarea name="message_body" id="message_body" class="form-control validate[required,custom[address_description_validation]] text-input" maxlength="500"></textarea>
			</div>
		</div>
										
		<div id="hmsg_message_sent" class="hmsg_message_none">
			<div class="form-group">
				<label class="col-sm-2 control-label" for="sms_template"><?php _e('SMS Text','gym_mgt');?><span class="require-field">*</span></label>
				<div class="col-sm-8">
					<textarea name="sms_template" class="form-control validate[required,custom[address_description_validation]]" maxlength="160"></textarea>
					<label><?php _e('Max. 160 Character','gym_mgt');?></label>
				</div>
			</div>
		</div>			
		<div class="form-group">
			<div class="col-sm-10">
				<div class="pull-right">
				<input type="submit" value="<?php if($edit){ _e('Save Message','gym_mgt'); }else{ _e('Send Message','gym_mgt');}?>" name="save_message" class="btn btn-success"/>
				</div>
			</div>
		</div>
	</form><!--COMPOSE MAIL FORM END-->
</div><!--MAILBOX CONTENT DIV END-->