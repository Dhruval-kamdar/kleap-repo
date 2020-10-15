<?php 
$edit = 0;
if(isset($_REQUEST['action']) && $_REQUEST['action'] == 'edit')
{
	$edit=1;
	$result = $obj_workout->MJ_gmgt_get_single_measurement($_REQUEST['measurment_id']);
}
?>
<script type="text/javascript">
$(document).ready(function()
{
	$('#workout_form').validationEngine({promptPosition : "bottomRight",maxErrorsPerField: 1});	
	$(".display-members").select2();
	var date = new Date();
	 date.setDate(date.getDate()-0);
	 $.fn.datepicker.defaults.format =" <?php echo get_option('gmgt_datepicker_format');?>";
	 $('#result_date').datepicker({
		<?php
		if(get_option('gym_enable_datepicker_privious_date')=='no')
		{
		?>
			startDate: date,
		<?php
		}
		?>	
	 autoclose: true
   });
   	
} );
</script>
<div class="panel-body"><!--PANEL BODY DIV STRAT-->
	<form name="workout_form" action="" method="post" class="form-horizontal" id="workout_form"><!--WORKOUT FORM STRAT-->
	   <?php $action = isset($_REQUEST['action'])?$_REQUEST['action']:'insert';?>
		<input type="hidden" name="action" value="<?php echo $action;?>">
		<input type="hidden" name="measurment_id" value="<?php if(isset($_REQUEST['measurment_id']))echo $_REQUEST['measurment_id'];?>">
    	<div class="form-group">
			<label class="col-sm-2 control-label" for="day"><?php _e('Member','gym_mgt');?><span class="require-field">*</span></label>	
			<div class="col-sm-8">
				<?php if($edit){ $member_id=$result->user_id; }elseif(isset($_REQUEST['user_id'])){$member_id=$_REQUEST['user_id'];}else{$member_id='';}?>
				<select id="member_list" class="display-members" required="true" name="user_id">
					<option value=""><?php _e('Select Member','gym_mgt');?></option>
						<?php $get_members = array('role' => 'member');
						$membersdata=get_users($get_members);
						 if(!empty($membersdata))
						 {
							foreach ($membersdata as $member){
								if( $member->membership_status == "Continue")
									{?>
								<option value="<?php echo $member->ID;?>" <?php selected($member_id,$member->ID);?>><?php echo $member->display_name." - ".$member->member_id; ?> </option>
							<?php } }
						 }?>
			   </select>
			</div>
		</div>
		<div class="form-group">
			<label class="col-sm-2 control-label" for="result_measurment"><?php _e('Result Measurement','gym_mgt');?> <span class="require-field">*</span></label>
			<div class="col-sm-8">
			<?php if($edit){
					$measument=$result->result_measurment;
				}
				elseif(isset($_REQUEST['result_measurment']))
				{
					$measument = $_REQUEST['result_measurment'];
				}
				else
				{
					$measument="";
				}?>
				<select name="result_measurment" class="form-control validate[required] " id="result_measurment">
					<option value=""><?php  _e('Select Result Measurement ','gym_mgt');?></option>
					<?php 	
					foreach(MJ_gmgt_measurement_array() as $key=>$element)
					{
					  if($element == 'Height')
						{
							$unit= get_option( 'gmgt_height_unit' );
						}
					   elseif($element == 'Weight')
					   {
						  $unit= get_option( 'gmgt_weight_unit' );
					   }
					   elseif($element == 'Chest')
					   {
						  $unit= get_option( 'gmgt_chest_unit' );
					   }
					   elseif($element == 'Waist')
					   {
						  $unit= get_option( 'gmgt_waist_unit' );
					   }
					   elseif($element == 'Thigh')
					   {
						  $unit= get_option( 'gmgt_thigh_unit' );
					   }
					   elseif($element == 'Arms')
					   {
						  $unit= get_option( 'gmgt_arms_unit' );
					   }
						elseif($element == 'Fat')
					   {
						  $unit= get_option( 'gmgt_fat_unit' );
					   }
						
						echo '<option value='.$key.' '.selected($measument,$key).'>'.$element.' - '.$unit.'</option>';
					}						
					?>
				</select>
			</div>
		</div>
		<div class="form-group">
			<label class="col-sm-2 control-label" for="result"><?php _e('Result','gym_mgt');?> <span class="require-field">*</span></label>
			<div class="col-sm-8">
				<input id="result" class="form-control validate[required] text-input decimal_number" min="0" step="0.01" onKeyPress="if(this.value.length==6) return false;" type="number" value="<?php if($edit){ echo $result->result;}elseif(isset($_POST['result'])) echo $_POST['result'];?>" name="result">
			</div>			
		</div>
		<!--nonce-->
		<?php wp_nonce_field( 'save_measurement_nonce' ); ?>
		<!--nonce-->
		<div class="form-group">
			<label class="col-sm-2 control-label" for="result_date"><?php _e('Record Date','gym_mgt');?>  <span class="require-field">*</span></label>
			<div class="col-sm-8">
				<input id="result_date" class="form-control validate[required]"  type="text" data-date-format="<?php echo MJ_gmgt_bootstrap_datepicker_dateformat(get_option('gmgt_datepicker_format'));?>" name="result_date" 
				value="<?php if($edit){ echo MJ_gmgt_getdate_in_input_box($result->result_date);}
				elseif(isset($_POST['result_date'])){ echo $_POST['result_date'];} 
				else echo MJ_gmgt_getdate_in_input_box(date("Y-m-d"));?>" readonly>
			</div>
		</div>
		<div class="form-group">
			<label class="col-sm-2 control-label" for="photo"><?php _e('Image','gym_mgt');?></label>
			<div class="col-sm-2">
				<input type="text" id="gmgt_user_avatar_url" class="form-control" name="gmgt_progress_image"  readonly
				value="<?php if($edit) echo esc_url( $result->gmgt_progress_image );elseif(isset($_POST['gmgt_progress_image'])) echo $_POST['gmgt_progress_image']; ?>" />
			</div>	
				<div class="col-sm-3">
       				 <input id="upload_user_avatar_button" type="button" class="button" value="<?php _e( 'Upload image', 'gym_mgt' ); ?>" />
       				 <span class="description"><?php _e('Upload image', 'gym_mgt' ); ?></span>
			</div>
			<div class="clearfix"></div>			
			<div class="col-sm-offset-2 col-sm-8">
				 <div id="upload_user_avatar_preview" >
					<?php 
					if($edit) 
					{						
						if($result->gmgt_progress_image == "")
						{ 
								?>
							<img class="image_preview_css" src="<?php echo get_option( 'gmgt_system_logo' ); ?>">
							<?php 
						}
						else 
						{
							?>
							<img class="image_preview_css" src="<?php if($edit) echo esc_url( $result->gmgt_progress_image ); ?>" />
							<?php 
						}
					}
					else 
					{
						?>
						<img class="image_preview_css" src="<?php echo get_option( 'gmgt_system_logo' ); ?>">
						<?php 
					}
					?>
				</div>
			</div>
		</div>
		<div class="col-sm-offset-2 col-sm-8">
        	<input type="submit" value="<?php if($edit){ _e('Save Measurement','gym_mgt'); }else{ _e('Save Measurement','gym_mgt');}?>" name="save_measurement" class="btn btn-success"/>
        </div>
    </form><!--WORKOUT FORM END-->
</div><!--PANEL BODY DIV END-->