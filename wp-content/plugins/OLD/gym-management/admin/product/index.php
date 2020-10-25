<?php 
$obj_product=new MJ_Gmgtproduct;
$active_tab = isset($_GET['tab'])?$_GET['tab']:'productlist';
?>
<!-- POP up code -->
<div class="popup-bg">
    <div class="overlay-content">
		<div class="modal-content">
			<div class="category_list"></div>     
		</div>
    </div>    
</div>
<!-- End POP-UP Code -->
<div class="page-inner" style="min-height:1631px !important"><!--PAGE INNER DIV STRAT-->
	<div class="page-title">
			<h3><img src="<?php echo get_option( 'gmgt_system_logo' ) ?>" class="img-circle head_logo" width="40" height="40" /><?php echo get_option( 'gmgt_system_name' );?></h3>
	</div>
	<?php 
	//SAVE PRODUCT DATA
	if(isset($_POST['save_product']))
	{
		$nonce = $_POST['_wpnonce'];
		if (wp_verify_nonce( $nonce, 'save_product_nonce' ) )
		{
		$txturl=$_POST['product_image'];
		$ext=MJ_gmgt_check_valid_extension($txturl);
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
						$result=$obj_product->MJ_gmgt_add_product($_POST,$_POST['product_image']);
						if($result)
						{
							wp_redirect ( admin_url().'admin.php?page=gmgt_product&tab=productlist&message=2');
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
						$result=$obj_product->MJ_gmgt_add_product($_POST,$_POST['product_image']);
						if($result)
						{
							wp_redirect ( admin_url().'admin.php?page=gmgt_product&tab=productlist&message=1');
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
	//DELETE Product DATA
	if(isset($_REQUEST['action'])&& $_REQUEST['action']=='delete')
	{
		$result=$obj_product->MJ_gmgt_delete_product($_REQUEST['product_id']);
		if($result)
		{
			wp_redirect ( admin_url().'admin.php?page=gmgt_product&tab=productlist&message=3');
		}
	}
	//DELETE SELECTED Product DATA
	if(isset($_REQUEST['delete_selected']))
	{		
		if(!empty($_REQUEST['selected_id']))
		{
			foreach($_REQUEST['selected_id'] as $id)
			{
				$delete_product=$obj_product->MJ_gmgt_delete_product($id);
				
			}
			if($delete_product)
			{
				wp_redirect ( admin_url().'admin.php?page=gmgt_product&tab=productlist&message=3');
			}
		}
		else
		{

			echo '<script language="javascript">';
			echo 'alert("'.__('Please select at least one record.','gym_mgt').'")';
			echo '</script>';
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
	<div id="main-wrapper"><!--MAIN WRAPPER DIV STRAT-->
		<div class="row"><!--ROW DIV STRAT-->
			<div class="col-md-12"><!--COL 12 DIV STRAT-->
				<div class="panel panel-white"><!--PANEL WHITE DIV STRAT-->
					<div class="panel-body"><!--PANEL BODY DIV STRAT-->
						<h2 class="nav-tab-wrapper"><!--NAV TAB WRAPPER MENU STRAT-->
							<a href="?page=gmgt_product&tab=productlist" class="nav-tab 
							<?php echo $active_tab == 'productlist' ? 'nav-tab-active' : ''; ?>">
							<?php echo '<span class="dashicons dashicons-menu"></span> '.__('Product List', 'gym_mgt'); ?></a>
							
							<?php  if(isset($_REQUEST['action']) && $_REQUEST['action'] == 'edit')
							{?>
							<a href="?page=gmgt_product&tab=addproduct&action=edit&product_id=<?php echo $_REQUEST['product_id'];?>" class="nav-tab <?php echo $active_tab == 'addproduct' ? 'nav-tab-active' : ''; ?>">
							<?php _e('Edit Product', 'gym_mgt'); ?></a>  
							<?php 
							}
							else
							{?>
								<a href="?page=gmgt_product&tab=addproduct" class="nav-tab <?php echo $active_tab == 'addproduct' ? 'nav-tab-active' : ''; ?>">
							<?php echo '<span class="dashicons dashicons-plus-alt"></span> '.__('Add Product', 'gym_mgt'); ?></a>
								
							<?php  }?>
						   
						</h2><!--NAV TAB WRAPPER MENU END-->
						<?php
						if($active_tab == 'productlist')
						{ 
							?>	
							<script type="text/javascript">
							$(document).ready(function() {
								jQuery('#product_list').DataTable({
									"responsive": true,
									"order": [[ 1, "asc" ]],
									"aoColumns":[
												  {"bSortable": false},
												  {"bSortable": true},
												  {"bSortable": true},
												  {"bSortable": true},
												  {"bSortable": true},
												  {"bSortable": true},
												  {"bSortable": true},
												  {"bSortable": false}],
										language:<?php echo MJ_gmgt_datatable_multi_language();?>			  
									});
								$('.select_all').on('click', function(e)
								{
									 if($(this).is(':checked',true))  
									 {
										$(".sub_chk").prop('checked', true);  
									 }  
									 else  
									 {  
										$(".sub_chk").prop('checked',false);  
									 } 
								});
							
								$('.sub_chk').change(function()
								{ 
									if(false == $(this).prop("checked"))
									{ 
										$(".select_all").prop('checked', false); 
									}
									if ($('.sub_chk:checked').length == $('.sub_chk').length )
									{
										$(".select_all").prop('checked', true);
									}
							  });
							} );
							</script>
							<form name="wcwm_report" action="" method="post"><!--PRODUCT LIST FORM START-->		
								<div class="panel-body"><!--PANEL BODY DIV START-->		
									<div class="table-responsive"><!--TABLE RESPONSIVE START-->		
										<table id="product_list" class="display" cellspacing="0" width="100%"><!--PRODUCT LIST  TABLE START-->	
											<thead>
												<tr>
													<th><input type="checkbox" class="select_all"></th>
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
													<th></th>
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
												$productdata=$obj_product->MJ_gmgt_get_all_product();
												if(!empty($productdata))
												{
													foreach ($productdata as $retrieved_data){
												 ?>
													<tr>
														<td class="title"><input type="checkbox" name="selected_id[]" class="sub_chk" value="<?php echo $retrieved_data->id; ?>"></td>
														<td class="user_image">
															<?php
																if(empty($retrieved_data->product_image))
																{
																	echo '<img src='.get_option( 'gmgt_system_logo' ).' height="50px" width="50px" class="img-circle" />';
																}
																else
																	echo '<img src='.$retrieved_data->product_image.' height="50px" width="50px" class="img-circle"/>';
														?></td>
														<td class="productname"><a href="?page=gmgt_product&tab=addproduct&action=edit&product_id=<?php echo $retrieved_data->id;?>"><?php echo $retrieved_data->product_name;?></a></td>													
														<td class="productname"><?php echo $retrieved_data->sku_number;?></td>
														<td class="productname"><?php echo get_the_title($retrieved_data->product_cat_id);?></td>
														<td class="productprice"><?php echo MJ_gmgt_get_currency_symbol(get_option( 'gmgt_currency_code' )); ?> <?php echo $retrieved_data->price;?></td>
														<td class="productquentity"><?php echo $retrieved_data->quentity;?></td>
														<td class="action"> 
														<a href="#" class="view_details_popup btn btn-default" id="<?php echo $retrieved_data->id?>" type="<?php echo 'view_product';?>"><i class="fa fa-eye"> </i><?php _e('View', 'gym_mgt' ) ;?> </a>
														<a href="?page=gmgt_product&tab=addproduct&action=edit&product_id=<?php echo $retrieved_data->id?>" class="btn btn-info"> <?php _e('Edit', 'gym_mgt' ) ;?></a>
														<a href="?page=gmgt_product&tab=productlist&action=delete&product_id=<?php echo $retrieved_data->id;?>" class="btn btn-danger" 
														onclick="return confirm('<?php _e('Do you really want to delete this record?','gym_mgt');?>');">
														<?php _e( 'Delete', 'gym_mgt' ) ;?> </a>
														</td>
													</tr>
													<?php } 
												}?>
											</tbody>
										</table><!--PRODUCT LIST  TABLE END-->	
										<div class="print-button pull-left">
										   <input  type="submit" value="<?php _e('Delete Selected','gym_mgt');?>" name="delete_selected" class="btn btn-danger delete_selected "/>
										</div>
									</div><!--TABLE RESPONSIVE DIV END-->
								</div><!--PANEL BODY DIV END-->
							</form><!--PRODUCT LIST FORM END-->
							<?php 
						}
						if($active_tab == 'addproduct')
						{
							require_once GMS_PLUGIN_DIR. '/admin/product/add_product.php';
						}
						?>
					</div><!--PANEL BODY DIV END-->							
				</div><!--PANEL WHITE DIV END-->
			</div><!--COL 12 DIV END-->
		</div><!--ROW DIV END-->
	</div><!--MAIN WRAPPER DIV END-->
</div><!--PAGE INNER DIV END-->