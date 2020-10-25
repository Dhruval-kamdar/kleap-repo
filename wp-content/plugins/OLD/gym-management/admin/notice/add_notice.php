<?php ?>
<script type="text/javascript">
$(document).ready(function()
{
	$('#notice_form').validationEngine({promptPosition : "bottomRight",maxErrorsPerField: 1});	
	  	var date = new Date();
		date.setDate(date.getDate()-0);
		$.fn.datepicker.defaults.format =" <?php echo get_option('gmgt_datepicker_format');?>";
		  $('.start_date').datepicker({
			<?php
			if(get_option('gym_enable_datepicker_privious_date')=='no')
			{
			?>
				startDate: date,
			<?php
			}
			?>	
		 autoclose: true
	   }).on('changeDate', function(){
			$('.end_date').datepicker('setStartDate', new Date($(this).val()));
		});
	   
	   var date = new Date();
		date.setDate(date.getDate()-0);
		$.fn.datepicker.defaults.format =" <?php echo get_option('gmgt_datepicker_format');?>";
		  $('.end_date').datepicker({
			<?php
			if(get_option('gym_enable_datepicker_privious_date')=='no')
			{
			?>
				startDate: date,
			<?php
			}
			?>	
		 autoclose: true
	   }).on('changeDate', function(){
			$('.start_date').datepicker('setEndDate', new Date($(this).val()));
		});
} );
</script>
<?php 	
if($active_tab == 'addnotice')
{
	$notice_id=0;
	if(isset($_REQUEST['notice_id']))
		$notice_id=$_REQUEST['notice_id'];
		$edit=0;
		if(isset($_REQUEST['action']) && $_REQUEST['action'] == 'edit')
		{					
			$edit=1;
			$result = get_post($notice_id);
		}
		?>
        <div class="panel-body"><!--PANEL BODY DIV START-->
			<form name="notice_form" action="" method="post" class="form-horizontal" id="notice_form"><!--NOTICE FORM START-->
				<?php 
				$action = isset($_REQUEST['action'])?$_REQUEST['action']:'insert';?>
				<input type="hidden" name="action" value="<?php echo $action;?>">
				<input type="hidden" name="notice_id" value="<?php echo $notice_id;?>"  />
				<div class="form-group">
					<label class="col-sm-2 control-label" for="notice_title"><?php _e('Notice Title','gym_mgt');?><span class="require-field">*</span></label>
					<div class="col-sm-8">
						<input id="notice_title" class="form-control validate[required,custom[popup_category_validation]] text-input" maxlength="50" type="text" value="<?php if($edit){ echo $result->post_title;}?>" name="notice_title">
						 <input type="hidden" name="notice_id"   value="<?php if($edit){ echo $result->ID;}?>"/> 
					</div>
				</div>
				<!--nonce-->
				<?php wp_nonce_field( 'save_notice_nonce' ); ?>
				<!--nonce-->
				<div class="form-group">
					<label class="col-sm-2 control-label" for="notice_for"><?php _e('Notice For','gym_mgt');?></label>
					<div class="col-sm-8">
					 <select name="notice_for" id="notice_for" class="form-control notice_for">
					   <option value = "all"><?php _e('All','gym_mgt');?></option>
					   <option value="staff_member" <?php if($edit) echo selected(get_post_meta( $result->ID, 'notice_for',true),'staff_member');?>><?php _e('Staff Members','gym_mgt');?></option>
					   <option value="member" <?php if($edit) echo selected(get_post_meta( $result->ID, 'notice_for',true),'member');?>><?php _e('Member','gym_mgt');?></option>
					   <option value="accountant" <?php if($edit) echo selected(get_post_meta( $result->ID, 'notice_for',true),'accountant');?>><?php _e('Accountant','gym_mgt');?></option>
					</select>
						
					</div>
				</div>
				<div class="class_div">
					<div class="form-group">
						<label class="col-sm-2 control-label" for="class_id"><?php _e('Class','gym_mgt');?></label>
						<div class="col-sm-8">
							<?php 
							if($edit){
									$class_id=get_post_meta($result->ID,'gmgt_class_id',true);
								}
								elseif(isset($_POST['class_id'])){
									$class_id=$_POST['class_id'];
								}
								else{
									$class_id='';
								}
								?>
							<select id="class_id" class="form-control" name="class_id">
								<option value=""><?php _e('Select Class','gym_mgt');?></option>
								 <?php $classdata=$obj_class->MJ_gmgt_get_all_classes();
								 if(!empty($classdata))
								 {
									foreach ($classdata as $class){?>
										<option value="<?php echo $class->class_id;?>" <?php selected($class_id,$class->class_id);  ?>><?php echo $class->class_name; ?> </option>
							<?php } } ?>
							</select>
						</div>
					</div>
				</div>
				<div class="form-group">
					<label class="col-sm-2 control-label" for="notice_content"><?php _e('Notice Start Date','gym_mgt');?><span class="require-field">*</span></label>
					<div class="col-sm-8">
					<input id="notice_Start_date" class="start_date form-control validate[required] text-input" data-date-format="<?php echo MJ_gmgt_bootstrap_datepicker_dateformat(get_option('gmgt_datepicker_format'));?>" 
					type="text" value="<?php if($edit){ echo MJ_gmgt_getdate_in_input_box(get_post_meta($result->ID,'gmgt_start_date',true));}?>" name="start_date" readonly>
						
					</div>
				</div>
				<div class="form-group">
					<label class="col-sm-2 control-label" for="notice_content"><?php _e('Notice End Date','gym_mgt');?><span class="require-field">*</span></label>
					<div class="col-sm-8">
					<input id="notice_end_date" class="end_date form-control validate[required] text-input" data-date-format="<?php echo MJ_gmgt_bootstrap_datepicker_dateformat(get_option('gmgt_datepicker_format'));?>" type="text" 
					value="<?php if($edit){ echo MJ_gmgt_getdate_in_input_box(get_post_meta($result->ID,'gmgt_end_date',true));}?>" name="end_date" readonly>
						
					</div>
				</div>
				<div class="form-group">
					<label class="col-sm-2 control-label" for="notice_content"><?php _e('Notice Comment','gym_mgt');?></label>
					<div class="col-sm-8">
					<textarea name="notice_content" class="form-control validate[custom[address_description_validation]]" maxlength="500" id="notice_content"><?php if($edit){ echo $result->post_content;}?></textarea>
					</div>
				</div>
				<div class="col-sm-offset-2 col-sm-8">
					<input type="submit" value="<?php if($edit){ _e('Save Notice','gym_mgt'); }else{ _e('Save Notice','gym_mgt');}?>" name="save_notice" class="btn btn-success"/>
				</div>
			</form><!--NOTICE FORM END-->
        </div><!--PANEL BODY DIV START-->
<?php 
}
?>