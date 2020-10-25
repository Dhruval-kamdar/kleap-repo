<?php ?>
<script type="text/javascript">
$(document).ready(function() 
{
	$('#group_form').validationEngine({promptPosition : "bottomRight",maxErrorsPerField: 1});	
} );
</script>
<?php 	
if($active_tab == 'addgroup')
{
	$group_id=0;
	if(isset($_REQUEST['group_id']))
		$group_id=$_REQUEST['group_id'];
	$edit=0;
	if(isset($_REQUEST['action']) && $_REQUEST['action'] == 'edit')
	{
		$edit=1;
		$result = $obj_group->MJ_gmgt_get_single_group($group_id);
	}
	?>
    <div class="panel-body"><!-- PANEL BODY DIV START-->
		<form name="group_form"  action="" method="post" class="form-horizontal" id="group_form"><!-- GROUP FORM START-->
			<?php $action = isset($_REQUEST['action'])?$_REQUEST['action']:'insert';?>
			<input type="hidden" name="action" value="<?php echo $action;?>">
			<input type="hidden" name="group_id" value="<?php echo $group_id;?>"  />
			<div class="form-group">
				<label class="col-sm-2 control-label" for="group_name"><?php _e('Group Name','gym_mgt');?><span class="require-field">*</span></label>
				<div class="col-sm-8">
					<input id="group_name" class="form-control validate[required,custom[popup_category_validation]] text-input"  maxlength="50" type="text" value="<?php if($edit){ echo $result->group_name;}elseif(isset($_POST['group_name'])) echo $_POST['group_name'];?>" name="group_name">
				</div>
			</div>
			<!--nonce-->
			<?php wp_nonce_field( 'save_group_nonce' ); ?>
			<!--nonce-->
			<div class="form-group">
				<label class="col-sm-2 control-label" for=""><?php _e('Group Description','gym_mgt');?></label>
				<div class="col-sm-8">
				<textarea name="group_description" class="form-control validate[custom[address_description_validation]]" maxlength="500" ><?php if($edit){ echo $result->group_description;}?></textarea>
				</div>
			</div>
			<div class="form-group">
				<label class="col-sm-2 control-label" for="gmgt_membershipimage"><?php _e('Group Image','gym_mgt');?></label>
				<div class="col-sm-8">
				<input type="text" id="gmgt_gym_background_image" class="group_image_upload" name="gmgt_groupimage" readonly
				value="<?php if($edit){ echo $result->gmgt_groupimage;}elseif(isset($_POST['gmgt_groupimage'])) echo $_POST['gmgt_groupimage'];?>" />	
						  <input id="upload_image_button" type="button" class="button upload_user_cover_button" value="<?php _e( 'Upload Cover Image', 'gym_mgt' ); ?>" />
						 <span class="description"><?php _e('Upload Group Image', 'gym_mgt' ); ?></span>
					<div id="upload_gym_cover_preview" style="min-height: 100px;">
					<img style="max-width:100%;" 
					src="<?php if($edit && $result->gmgt_groupimage != ''){ echo $result->gmgt_groupimage;}elseif(isset($_POST['gmgt_groupimage'])) echo $_POST['gmgt_groupimage']; else echo get_option( 'gmgt_system_logo' );?>" />
					</div>
				</div>
			</div>
			<div class="col-sm-offset-2 col-sm-8">
				<input type="submit" value="<?php if($edit){ _e('Save','gym_mgt'); }else{ _e('Save','gym_mgt');}?>" name="save_group" class="btn btn-success"/>
			</div>
		</form><!-- GROUP FORM END-->
	</div><!-- PANEL BODY DIV END-->
 <?php 
}
?>