<?php ?>
<script type="text/javascript">
$(document).ready(function() {
	$('#tax_form').validationEngine({promptPosition : "bottomRight",maxErrorsPerField: 1});	
} );
</script>
<?php 	
if($active_tab == 'addtax')
{
	$tax_id=0;
	if(isset($_REQUEST['tax_id']))
		$tax_id=$_REQUEST['tax_id'];
		$edit=0;
		if(isset($_REQUEST['action']) && $_REQUEST['action'] == 'edit')
		{
			$edit=1;
			$result = $obj_tax->MJ_gmgt_get_single_tax_data($tax_id);
		}?>
		<div class="panel-body"><!--PANEL BODY DIV START-->	
			<form name="tax_form"  action="" method="post" class="form-horizontal" id="tax_form"><!--TAX FORM START-->	
					<?php $action = isset($_REQUEST['action'])?$_REQUEST['action']:'insert';?>
					<input type="hidden" name="action" value="<?php echo $action;?>">
					<input type="hidden" name="tax_id" value="<?php echo $tax_id;?>"  />
					<div class="form-group">
						<label class="col-sm-2 control-label" for=""><?php _e("Tax Name","gym_mgt");?><span class="require-field">*</span></label>
						<div class="col-sm-8">
						<input id="" maxlength="30" class="form-control validate[required,custom[address_description_validation]] text-input" type="text" value="<?php if($edit){ echo $result->tax_title;}elseif(isset($_POST['tax_title'])) echo $_POST['tax_title'];?>" name="tax_title">
					   </div>
					</div>  
					<div class="form-group margin_bottom_5px">
					   <label class="col-sm-2 control-label" for=""><?php _e("Tax Value","gym_mgt");?>(%)<span class="require-field">*</span></label>
						<div class="col-sm-8">
							<input id="tax" class="form-control validate[required,custom[number]] text-input" onkeypress="if(this.value.length==6) return false;" step="0.01" type="number" value="<?php if($edit){ echo $result->tax_value;}elseif(isset($_POST['tax_value'])) echo $_POST['tax_value'];?>" name="tax_value" min="0" max="100">
						</div>
					</div>
					<!--nonce-->
					<?php wp_nonce_field( 'save_tax_nonce' ); ?>
					<!--nonce-->
					<div class="col-sm-offset-2 col-sm-8">
						<input type="submit" value="<?php if($edit){ _e('Save','gym_mgt'); }else{ _e('Save','gym_mgt');}?>" name="save_tax" class="btn btn-success"/>
					</div>
			</form><!--TAX FORM END-->	
		</div><!--PANEL BODY DIV END-->	
 <?php 
}
?>