<?php ?>
<script type="text/javascript">
$(document).ready(function() {
	$('#product_form').validationEngine({promptPosition : "bottomRight",maxErrorsPerField: 1});	
	$.fn.datepicker.defaults.format =" <?php echo get_option('gmgt_datepicker_format');?>";
		$('.manufacture_date').datepicker(
		{
			endDate: '+0d',
			autoclose: true
		}); 
} );
</script>
<?php 	
if($active_tab == 'addproduct')
{
	$product_id=0;
	if(isset($_REQUEST['product_id']))
		$product_id=$_REQUEST['product_id'];
		$edit=0;
		if(isset($_REQUEST['action']) && $_REQUEST['action'] == 'edit')
		{
			$edit=1;
			$result = $obj_product->MJ_gmgt_get_single_product($product_id);
		}?>
        <div class="panel-body"><!--PANEL BODY DIV STRAT-->
		    <form name="product_form" action="" method="post" class="form-horizontal" id="product_form"><!--PRODUCT FORM STRAT-->
				<?php $action = isset($_REQUEST['action'])?$_REQUEST['action']:'insert';?>
				<input type="hidden" name="action" value="<?php echo $action;?>">
				<input type="hidden" name="product_id" value="<?php echo $product_id;?>"  />
				<div class="form-group">
					<label class="col-sm-2 control-label"><?php _e('Product Category','gym_mgt');?><span class="require-field">*</span></label>
					<div class="col-sm-8">			
						<select class="form-control validate[required]"  name="product_category" id="product_category">
						<option value=""><?php _e('Select Product Category','gym_mgt');?></option>
						<?php 				
						if(isset($_REQUEST['product_category']))
							$category =$_REQUEST['product_category'];  
						elseif($edit)
							$category =$result->product_cat_id;
						else 
							$category = "";
						
						$product_category=MJ_gmgt_get_all_category('product_category');
						if(!empty($product_category))
						{
							foreach ($product_category as $retrive_data)
							{
								echo '<option value="'.$retrive_data->ID .'" '.selected($category,$retrive_data->ID).'>'.$retrive_data->post_title .'</option>';
							}
						}
						?>				
						</select>
					</div>
					<div class="col-sm-2 add_category_padding_0"><button id="addremove" model="product_category"><?php _e('Add Or Remove','gym_mgt');?></button></div>
				</div>
				<div class="form-group">
					<label class="col-sm-2 control-label" for="product_name"><?php _e('Product Name','gym_mgt');?><span class="require-field">*</span></label>
					<div class="col-sm-8">
						<input id="product_name" class="form-control validate[required,custom[popup_category_validation]] text-input" maxlength="50" type="text" value="<?php if($edit){ echo $result->product_name;}elseif(isset($_POST['product_name'])) echo $_POST['product_name'];?>" name="product_name">
					</div>
				</div>
				<!--nonce-->
				<?php wp_nonce_field( 'save_product_nonce' ); ?>
				<!--nonce-->
				<div class="form-group">
					<label class="col-sm-2 control-label" for="product_price"><?php _e('Product Price','gym_mgt');?>(<?php echo MJ_gmgt_get_currency_symbol(get_option( 'gmgt_currency_code' )); ?>)<span class="require-field">*</span></label>
					<div class="col-sm-8">
						<input id="product_price" class="form-control validate[required] text-input" min="0" step="0.01"  onkeypress="if(this.value.length==8) return false;" type="number" value="<?php if($edit){ echo $result->price;}elseif(isset($_POST['product_price'])) echo $_POST['product_price'];?>" name="product_price">
					</div>
				</div>
				<div class="form-group">
					<label class="col-sm-2 control-label" for="quentity"><?php _e('Product Quantity','gym_mgt');?><span class="require-field">*</span></label>
					<div class="col-sm-8">
						<input id="group_name" class="form-control validate[required] text-input" min="0" onkeypress="if(this.value.length==4) return false;" type="number" value="<?php if($edit){ echo $result->quentity;}elseif(isset($_POST['quentity'])) echo $_POST['quentity'];?>" name="quentity">
					</div>
				</div>
				<div class="form-group">
					<label class="col-sm-2 control-label"><?php _e('SKU Number','gym_mgt');?><span class="require-field">*</span></label>
					<div class="col-sm-8">
						<input  class="form-control validate[required,custom[popup_category_validation]] text-input" maxlength="20" type="text" value="<?php if($edit){ echo $result->sku_number;}elseif(isset($_POST['sku_number'])) echo $_POST['sku_number'];?>" name="sku_number">
					</div>
				</div>
				<div class="form-group">
					<label class="col-sm-2 control-label"><?php _e('Manufacturer Company Name','gym_mgt');?></label>
					<div class="col-sm-8">
						<input  class="form-control validate[custom[popup_category_validation]] text-input" maxlength="50" type="text" value="<?php if($edit){ echo $result->manufacture_company_name;}elseif(isset($_POST['manufacture_company_name'])) echo $_POST['manufacture_company_name'];?>" name="manufacture_company_name">
					</div>
				</div>
				<div class="form-group">
					<label class="col-sm-2 control-label"><?php _e('Manufacturer Date','gym_mgt');?></label>
					<div class="col-sm-8">
						<input id="manufacture_date" class="form-control manufacture_date" type="text" data-date-format="<?php echo MJ_gmgt_bootstrap_datepicker_dateformat(get_option('gmgt_datepicker_format'));?>" name="manufacture_date" 
						value="<?php if($edit){  if($result->manufacture_date!='0000-00-00'){ echo MJ_gmgt_getdate_in_input_box($result->manufacture_date); } } elseif(isset($_POST['manufacture_date'])) echo MJ_gmgt_getdate_in_input_box($_POST['manufacture_date']);?>" readonly>
					</div>
				</div>
				<div class="form-group">
					<label class="col-sm-2 control-label"><?php _e('Product Description','gym_mgt');?></label>
					<div class="col-sm-8">
						<textarea name="product_description" class="form-control validate[custom[address_description_validation]]" maxlength="150"><?php if($edit){ echo trim($result->product_description);}elseif(isset($_POST['product_description'])) echo $_POST['product_description'];?></textarea>
					</div>
				</div>
				<div class="form-group">
					<label class="col-sm-2 control-label"><?php _e('Product Specification','gym_mgt');?></label>
					<div class="col-sm-8">
						<textarea name="product_specification" class="form-control validate[custom[address_description_validation]]" maxlength="150"><?php if($edit){ echo trim($result->product_specification);}elseif(isset($_POST['product_specification'])) echo $_POST['product_specification'];?></textarea>						
					</div>
				</div>							
				<div class="form-group">
					<label class="col-sm-2 control-label" for="photo"><?php _e('Product Image','gym_mgt');?></label>
					<div class="col-sm-2">
						<input type="text" id="gmgt_user_avatar_url1" readonly class="form-control gmgt_user_avatar_url" name="product_image"  
						value="<?php if($edit)echo esc_url( $result->product_image );elseif(isset($_POST['product_image'])) echo $_POST['product_image']; ?>" />
					</div>	
					<div class="col-sm-3">
						 <input id="upload_user_avatar_button1" type="button" class="button upload_user_avatar_button" value="<?php _e( 'Upload image', 'gym_mgt' ); ?>" />
						 <span class="description"><?php _e('Upload image', 'gym_mgt' ); ?></span>
					</div>
					<div class="clearfix"></div>
					
					<div class="col-sm-offset-2 col-sm-8">
						 <div id="upload_user_avatar_preview1" class="upload_user_avatar_preview">
							<?php 	
								if($edit) 
								{
									if($result->product_image == "")
									{ ?>
										<img class="image_preview_css" src="<?php echo get_option( 'gmgt_system_logo' ); ?>">
									<?php 
									}
									else
									{
										?>
										<img class="image_preview_css" src="<?php if($edit)echo esc_url( $result->product_image ); ?>" />
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
				<input type="submit" value="<?php if($edit){ _e('Save','gym_mgt'); }else{ _e('Save','gym_mgt');}?>" name="save_product" class="btn btn-success"/>
				</div>
		    </form><!--PRODUCT FORM END-->
        </div><!--PANEL BODY DIV END-->
<?php 
}
?>