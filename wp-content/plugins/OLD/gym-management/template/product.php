<?php
$curr_user_id=get_current_user_id();
$obj_gym=new MJ_Gym_management($curr_user_id);
$obj_product=new MJ_Gmgtproduct;
$active_tab = isset($_GET['tab'])?$_GET['tab']:'productlist';
//access right
$user_access=MJ_gmgt_get_userrole_wise_page_access_right_array();
if (isset ( $_REQUEST ['page'] ))
{	
	if($user_access['view']=='0')
	{	
		MJ_gmgt_access_right_page_not_access_message();
		die;
	}
	if(!empty($_REQUEST['action']))
	{
		if (isset ( $_REQUEST ['page'] ) && $_REQUEST ['page'] == $user_access['page_link'] && ($_REQUEST['action']=='edit'))
		{
			if($user_access['edit']=='0')
			{	
				MJ_gmgt_access_right_page_not_access_message();
				die;
			}			
		}
		if (isset ( $_REQUEST ['page'] ) && $_REQUEST ['page'] == $user_access['page_link'] && ($_REQUEST['action']=='delete'))
		{
			if($user_access['delete']=='0')
			{	
				MJ_gmgt_access_right_page_not_access_message();
				die;
			}	
		}
		if (isset ( $_REQUEST ['page'] ) && $_REQUEST ['page'] == $user_access['page_link'] && ($_REQUEST['action']=='insert'))
		{
			if($user_access['add']=='0')
			{	
				MJ_gmgt_access_right_page_not_access_message();
				die;
			}	
		}
	}
}
//SAVE Product DATA
if(isset($_POST['save_product']))
{
	$nonce = $_POST['_wpnonce'];
	if (wp_verify_nonce( $nonce, 'save_product_nonce' ) )
	{
	if(isset($_FILES['product_image']) && !empty($_FILES['product_image']) && $_FILES['product_image']['size'] !=0)
	{
			
		if($_FILES['product_image']['size'] > 0)
		{
			 $product_image=MJ_gmgt_load_documets($_FILES['product_image'],'product_image','pimg');
			 $product_image_url=content_url().'/uploads/gym_assets/'.$product_image;
		}
						
	}
	else
	{			
		if(isset($_REQUEST['hidden_upload_user_avatar_image']))
		{
			$product_image=$_REQUEST['hidden_upload_user_avatar_image'];
			$product_image_url=$product_image;
		}
	}
	$ext=MJ_gmgt_check_valid_extension($product_image_url);
	
	if(!$ext == 0)
	{
		if(isset($_REQUEST['action'])&& $_REQUEST['action']=='edit')
		{
			$data=$obj_product->MJ_gmgt_get_all_product_by_name_count($_POST['product_name'],$_POST['product_id']);
			$data1=$obj_product->MJ_gmgt_get_all_product_by_sku_number_count($_POST['sku_number'],$_POST['product_id']);
			$data2=$obj_product->MJ_gmgt_get_all_product_by_name_and_sku_number_Count($_POST['product_name'],$_POST['sku_number'],$_POST['product_id']);
	
			if(!empty($data2))
			{
				  echo '<script type="text/javascript">alert("'.__('This product name and SKU Number already Use so please enter another product name and SKU Number.','gym_mgt').'");</script>';
			}
			else
			{
				if(!empty($data))
				{
					  echo '<script type="text/javascript">alert("'.__('This product name already store so please enter another product name.','gym_mgt').'");</script>';
				}				
				elseif(!empty($data1))
				{
					  echo '<script type="text/javascript">alert("'.__('This SKU Number already Use so please enter another SKU Number.','gym_mgt').'");</script>';
				}				
				else
				{
					$result=$obj_product->MJ_gmgt_add_product($_POST,$product_image_url);
					if($result)
					{
						wp_redirect ( home_url().'?dashboard=user&page=product&tab=productlist&message=2');
					}
				}
			}	
		}
		else
		{
			$data=$obj_product->MJ_gmgt_get_all_product_by_name($_POST['product_name']);
			$data1=$obj_product->MJ_gmgt_get_all_product_by_sku_number($_POST['sku_number']);
			$data2=$obj_product->MJ_gmgt_get_all_product_by_name_and_sku_number($_POST['product_name'],$_POST['sku_number']);
	
			if(!empty($data2))
			{
				  echo '<script type="text/javascript">alert("'.__('This product name and SKU Number already Use so please enter another product name and SKU Number.','gym_mgt').'");</script>';
			}
			else
			{
				if(!empty($data))
				{
					  echo '<script type="text/javascript">alert("'.__('This product name already store so please enter another product name.','gym_mgt').'");</script>';
				}				
				elseif(!empty($data1))
				{
					  echo '<script type="text/javascript">alert("'.__('This SKU Number already Use so please enter another SKU Number.','gym_mgt').'");</script>';
				}				
				else
				{				
					$result=$obj_product->MJ_gmgt_add_product($_POST,$product_image_url);
					if($result)
					{
						wp_redirect ( home_url().'?dashboard=user&page=product&tab=productlist&message=1');
					}
				}
			}
		}
	}	
	else
	{ ?>
		<div id="message" class="updated below-h2 ">
		<p>
			<?php _e('Sorry, only JPG, JPEG, PNG & GIF And BMP files are allowed.','gym_mgt');?>
		</p></div>				 
		<?php 
	}
}
}
//Delete PRODUCT DATA
if(isset($_REQUEST['action'])&& $_REQUEST['action']=='delete')
{
	$result=$obj_product->MJ_gmgt_delete_product($_REQUEST['product_id']);
	if($result)
	{
		wp_redirect ( home_url().'?dashboard=user&page=product&tab=productlist&message=3');
	}
}
if(isset($_REQUEST['message']))
{
	$message =$_REQUEST['message'];
	if($message == 1)
	{?>
			<div id="message" class="updated below-h2 ">
			<p>
			<?php 
				_e('Product added successfully.','gym_mgt');
			?></p></div>
			<?php 
		
	}
	elseif($message == 2)
	{?><div id="message" class="updated below-h2 "><p><?php
				_e("Product updated successfully.",'gym_mgt');
				?></p>
				</div>
			<?php 
	}
	elseif($message == 3) 
	{?>
	<div id="message" class="updated below-h2"><p>
	<?php 
		_e('Product deleted successfully.','gym_mgt');
	?></div></p><?php
	}
}
?>
<script type="text/javascript">
$(document).ready(function() 
{
	jQuery('#product_list').DataTable({
		responsive: true,
		language:<?php echo MJ_gmgt_datatable_multi_language();?>	
		});
		$('#product_form').validationEngine({promptPosition : "bottomRight",maxErrorsPerField: 1});	
		$.fn.datepicker.defaults.format =" <?php echo get_option('gmgt_datepicker_format');?>";
	$('.manufacture_date').datepicker(
	{
		endDate: '+0d',
		autoclose: true
	}); 
} );
</script>
<!-- POP up code -->
<div class="popup-bg">
    <div class="overlay-content">
		<div class="modal-content">
			<div class="category_list"></div>     
		</div>
    </div>    
</div>
<!-- End POP-UP Code -->
<div class="panel-body panel-white"><!-- PANEL BODY DIV START-->
    <ul class="nav nav-tabs panel_tabs" role="tablist"><!-- NAV TABS MENU START-->
	  	<li class="<?php if($active_tab=='productlist'){?>active<?php }?>">
			<a href="?dashboard=user&page=product&tab=productlist" class="tab <?php echo $active_tab == 'productlist' ? 'active' : ''; ?>">
             <i class="fa fa-align-justify"></i> <?php _e('Product List', 'gym_mgt'); ?></a>
          </a>
        </li>
        <li class="<?php if($active_tab=='addproduct'){?>active<?php }?>">
		  <?php  if(isset($_REQUEST['action']) && $_REQUEST['action'] == 'edit' && isset($_REQUEST['product_id']))
			{?>
			<a href="?dashboard=user&page=product&tab=addproduct&&action=edit&product_id=<?php echo $_REQUEST['product_id'];?>" class="nav-tab <?php echo $active_tab == 'addproduct' ? 'nav-tab-active' : ''; ?>">
             <i class="fa fa"></i> <?php _e('Edit  Product', 'gym_mgt'); ?></a>
			 <?php }
			else
			{
				if($user_access['add']=='1')
				{
				?>
				<a href="?dashboard=user&page=product&tab=addproduct&&action=insert" class="tab <?php echo $active_tab == 'addproduct' ? 'active' : ''; ?>">
				<i class="fa fa-plus-circle"></i> <?php _e('Add Product', 'gym_mgt'); ?></a>
				<?php 	
				} 
			}
		?>	  
	   </li>	  
	</ul><!-- NAV TABS MENU END-->
	<div class="tab-content"><!-- TAB CONTENT DIV START-->
		<?php 
		if($active_tab == 'productlist')
		{ 
			?>	
			 <div class="panel-body"><!--PANEL BODY DIV START-->
				<div class="table-responsive"><!--TABLE RESPONSIVE DIV START-->
					<table id="product_list" class="display" cellspacing="0" width="100%"><!--TABLE PRODUCT LIST START-->
						<thead>
							<tr>
							<th><?php  _e( 'Product Image', 'gym_mgt' ) ;?></th>
							<th><?php  _e( 'Product Name', 'gym_mgt' ) ;?></th>
							<th><?php  _e( 'SKU Number', 'gym_mgt' ) ;?></th>
							<th><?php  _e( 'Product Category', 'gym_mgt' ) ;?></th>
							<th><?php  _e( 'Product Price', 'gym_mgt' ) ;?></th>
							<th><?php  _e( 'Product Quantity', 'gym_mgt' ) ;?></th>					
							<th><?php  _e( 'Action', 'gym_mgt' ) ;?></th>
							</tr>
						</thead>
						<tfoot>
							<tr>
							<th><?php  _e( 'Product Image', 'gym_mgt' ) ;?></th>
							<th><?php  _e( 'Product Name', 'gym_mgt' ) ;?></th>
							<th><?php  _e( 'SKU Number', 'gym_mgt' ) ;?></th>
							<th><?php  _e( 'Product Category', 'gym_mgt' ) ;?></th>
							<th><?php  _e( 'Product Price', 'gym_mgt' ) ;?></th>
							<th><?php  _e( 'Product Quantity', 'gym_mgt' ) ;?></th>
							<th><?php  _e( 'Action', 'gym_mgt' ) ;?></th>
							
							</tr>
						</tfoot>
						<tbody>
						<?php 
						if($user_access['own_data']=='1')
						{
							$user_id=get_current_user_id();
							$productdata=$obj_product->MJ_gmgt_get_all_product_by_created_by($user_id);
						}
						else
						{
							$productdata=$obj_product->MJ_gmgt_get_all_product();
						}	
						
						if(!empty($productdata))
						{
							foreach ($productdata as $retrieved_data){?>
							<tr>
							
							<td class="user_image">
									<?php
										if(empty($retrieved_data->product_image))
										{
											echo '<img src='.get_option( 'gmgt_system_logo' ).' height="50px" width="50px" class="img-circle" />';
										}
										else
											echo '<img src='.$retrieved_data->product_image.' height="50px" width="50px" class="img-circle"/>';
								?></td>
									<?php
									if($obj_gym->role == 'staff_member' || $obj_gym->role == 'accountant')
								   {?>
								<td class="productname"><a href="?dashboard=user&page=product&tab=addproduct&action=edit&product_id=<?php echo $retrieved_data->id;?>"><?php echo $retrieved_data->product_name;?></a></td>
								   <?php }
								   else
								   {?>
									   <td class="productname"><?php echo $retrieved_data->product_name;?></td>
								   <?php } ?>
								 <td class="productname"><?php echo $retrieved_data->sku_number;?></td>
								<td class="productname"><?php echo get_the_title($retrieved_data->product_cat_id);?></td>  
								<td class="productprice"><?php echo MJ_gmgt_get_currency_symbol(get_option( 'gmgt_currency_code' )); ?> <?php echo  $retrieved_data->price;?></td>
								<td class="productquentity"><?php echo $retrieved_data->quentity;?></td>
							
								<td class="action">
								<a href="#" class="view_details_popup btn btn-default" id="<?php echo $retrieved_data->id?>" type="<?php echo 'view_product';?>"><i class="fa fa-eye"> </i><?php _e('View', 'gym_mgt' ) ;?> </a>
								<?php
								if($user_access['edit']=='1')
								{
								?>
									<a href="?dashboard=user&page=product&tab=addproduct&action=edit&product_id=<?php echo $retrieved_data->id?>" class="btn btn-info"> <?php _e('Edit', 'gym_mgt' ) ;?></a>
								<?php
								}
								if($user_access['delete']=='1')
								{
								?>		
									<a href="?dashboard=user&page=product&tab=productlist&action=delete&product_id=<?php echo $retrieved_data->id;?>" class="btn btn-danger" 
									onclick="return confirm('<?php _e('Do you really want to delete this record?','gym_mgt');?>');">
									<?php _e( 'Delete', 'gym_mgt' ) ;?> </a>
								<?php
								}
								?>
								</td>					
							</tr>
							<?php						
							} 					
						}
						?>
						</tbody>
					</table><!--TABLE PRODUCT LIST END-->
				</div><!--TABLE RESPONSIVE DIV  END-->
			</div><!--PANEL BODY DIV END-->
			<?php 
		}
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
				}
				?>
			
		<div class="panel-body"><!--PANEL BODY DIV START-->
			<form name="product_form" action="" method="post" class="form-horizontal" id="product_form" enctype="multipart/form-data"><!--PRODUCT FORM START-->
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
				<div class="form-group">
					<label class="col-sm-2 control-label" for="product_price"><?php _e('Product Price','gym_mgt');?>(<?php echo MJ_gmgt_get_currency_symbol(get_option( 'gmgt_currency_code' )); ?>)<span class="require-field">*</span></label>
					<div class="col-sm-8">
						<input id="product_price" class="form-control validate[required] text-input" min="0" step="0.01" onkeypress="if(this.value.length==8) return false;"   type="number" value="<?php if($edit){ echo $result->price;}elseif(isset($_POST['product_price'])) echo $_POST['product_price'];?>" name="product_price">
					</div>
				</div>
				<div class="form-group">
					<label class="col-sm-2 control-label" for="quentity"><?php _e('Product Quantity','gym_mgt');?><span class="require-field">*</span></label>
					<div class="col-sm-8">
						<input id="group_name" class="form-control validate[required] text-input" min="0" onkeypress="if(this.value.length==4) return false;" type="number"  value="<?php if($edit){ echo $result->quentity;}elseif(isset($_POST['quentity'])) echo $_POST['quentity'];?>" name="quentity">
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
					<input type="text" id="gmgt_user_avatar_url" class="form-control" name="product_image"  readonly
					value="<?php if($edit)echo esc_url( $result->product_image );elseif(isset($_POST['product_image'])) echo $_POST['product_image']; ?>" />
				</div>	
				<div class="col-sm-3">
					<input type="hidden" name="hidden_upload_user_avatar_image" value="<?php if($edit){ echo $result->product_image;}elseif(isset($_POST['product_image'])) echo $_POST['product_image'];?>">
						 <input id="upload_user_avatar_image" name="product_image" onchange="fileCheck(this);" type="file" class="form-control file image_upload_change" value="<?php _e( 'Upload image', 'gym_mgt' ); ?>" />
				</div>
				<div class="clearfix"></div>
				<div class="col-sm-offset-2 col-sm-8">
					<div id="upload_user_avatar_preview" >
								<?php
								if($edit) 
								{
									if($result->product_image == "")
									{?>
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
								}?>
					</div>
				</div>
			</div>
			<!--nonce-->
			<?php wp_nonce_field( 'save_product_nonce' ); ?>
			<!--nonce-->
				<div class="col-sm-offset-2 col-sm-8">
					
					<input type="submit" value="<?php if($edit){ _e('Save','gym_mgt'); }else{ _e('Save','gym_mgt');}?>" name="save_product" class="btn btn-success"/>
				</div>
			</form><!--PRODUCT FORM END-->
		</div><!--PANEL BODY DIV END-->
		<?php 
		}
		?>
	</div><!-- TAB CONTENT DIV END-->
</div><!-- PANEL BODY DIV END-->
<script type="text/javascript">
function fileCheck(obj)
{
	var fileExtension = ['jpeg', 'jpg', 'png', 'bmp',''];
	if ($.inArray($(obj).val().split('.').pop().toLowerCase(), fileExtension) == -1)
	{
		alert("Only '.jpeg','.jpg', '.png', '.bmp' formats are allowed.");
		$(obj).val('');
	}	
}
</script>