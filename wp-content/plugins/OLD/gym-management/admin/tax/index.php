<?php 
$obj_tax=new MJ_Gmgttax;
$active_tab = isset($_GET['tab'])?$_GET['tab']:'taxlist';
?>
<div class="page-inner" style="min-height:1631px !important"><!--PAGE INNER DIV START-->	
    <div class="page-title">
		<h3><img src="<?php echo get_option( 'gmgt_system_logo' ) ?>" class="img-circle head_logo" width="40" height="40" /><?php echo get_option( 'gmgt_system_name' );?></h3>
	</div>
	<?php 
	//SAVE TAX DATA
	if(isset($_POST['save_tax']))
	{
		$nonce = $_POST['_wpnonce'];
		if (wp_verify_nonce( $nonce, 'save_tax_nonce' ) )
		{
			if(isset($_REQUEST['action'])&& $_REQUEST['action']=='edit')
			{				
				$result=$obj_tax->MJ_gmgt_add_taxes($_POST);
				if($result)
				{
					wp_redirect ( admin_url().'admin.php?page=gmgt_taxes&tab=taxlist&message=2');
				}			
			}
			else
			{		
				$result=$obj_tax->MJ_gmgt_add_taxes($_POST);
				if($result)
				{
					wp_redirect ( admin_url().'admin.php?page=gmgt_taxes&tab=taxlist&message=1');
				}			
			}
		}
	}
	//DELETE TAX DATA
	if(isset($_REQUEST['action'])&& $_REQUEST['action']=='delete')
	{
		$result=$obj_tax->MJ_gmgt_delete_taxes($_REQUEST['tax_id']);
		if($result)
		{
			wp_redirect ( admin_url().'admin.php?page=gmgt_taxes&tab=taxlist&message=3');
		}
	}
	//DELETE SELECTED TAX DATA
	if(isset($_REQUEST['delete_selected']))
    {		
		if(!empty($_REQUEST['selected_id']))
		{
			foreach($_REQUEST['selected_id'] as $id)
			{
				$delete_tax=$obj_tax->MJ_gmgt_delete_taxes($id);
				
			}
			if($delete_tax)
			{
				wp_redirect ( admin_url().'admin.php?page=gmgt_taxes&tab=taxlist&message=3');
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
					_e('Tax added successfully.','gym_mgt');
				?></p></div>
				<?php 
		}
		elseif($message == 2)
		{?><div id="message" class="updated below-h2 "><p><?php
					_e("Tax updated successfully.",'gym_mgt');
					?></p>
					</div>
				<?php 
		}
		elseif($message == 3) 
		{?>
		<div id="message" class="updated below-h2"><p>
		<?php 
			_e('Tax deleted successfully.','gym_mgt');
		?></div></p><?php
				
		}
	}
	?>
	<div id="main-wrapper"><!--MAIN WRAPPER DIV START-->	
		<div class="row"><!--ROW DIV START-->	
			<div class="col-md-12"><!--COL 12 DIV START-->	
				<div class="panel panel-white"><!--PANEL WHITE DIV START-->	
					<div class="panel-body"><!--PANEL BODY DIV START-->	
						<h2 class="nav-tab-wrapper"><!--NAV TAB WRAPPER DIV START-->	
							<a href="?page=gmgt_taxes&tab=taxlist" class="nav-tab <?php echo $active_tab == 'taxlist' ? 'nav-tab-active' : ''; ?>">
							<?php echo '<span class="dashicons dashicons-menu"></span> '.__('Tax List', 'gym_mgt'); ?>
							</a>
							<?php  if(isset($_REQUEST['action']) && $_REQUEST['action'] == 'edit')
							{?>
								<a href="?page=gmgt_taxes&tab=addtax&&action=edit&tax_id=<?php echo $_REQUEST['tax_id'];?>" class="nav-tab <?php echo $active_tab == 'addtax' ? 'nav-tab-active' : ''; ?>">
								<?php _e('Edit Tax', 'gym_mgt'); ?></a>  
								<?php 
							}
							else
							{?>
							  <a href="?page=gmgt_taxes&tab=addtax" class="nav-tab <?php echo $active_tab == 'addtax' ? 'nav-tab-active' : ''; ?>">
							<?php echo '<span class="dashicons dashicons-plus-alt"></span> '.__('Add Tax', 'gym_mgt'); ?></a>
							<?php  }?>
						</h2><!--NAV TAB WRAPPER DIV END-->	
						<?php 
						if($active_tab == 'taxlist')
						{ 
							?>	
							<script type="text/javascript">
							$(document).ready(function() {
								jQuery('#tax_list').DataTable({
									"responsive": true,
									"order": [[ 1, "asc" ]],
									"aoColumns":[											  
												  {"bSortable": false},
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
							<form name="wcwm_report" action="" method="post"><!--TAX LIST FORM START-->	
								<div class="panel-body"><!--PANEL BODY DIV START-->	
									<div class="table-responsive"><!--TABLE RESPONSIVE DIV START-->	
										<table id="tax_list" class="display" cellspacing="0" width="100%"><!--TAX LIST TABLE START-->	
											<thead>
												<tr>
													<th><input type="checkbox" class="select_all"></th>
													<th><?php _e( 'Tax Name', 'gym_mgt' ) ;?></th>
													<th> <?php _e( 'Tax Value', 'gym_mgt' ) ;?> (%)</th>
													<th><?php  _e( 'Action', 'gym_mgt' ) ;?></th>
												</tr>
											</thead>
											<tfoot>
												<tr>
													<th></th>
													<th><?php _e( 'Tax Name', 'gym_mgt' ) ;?></th>
													<th> <?php _e( 'Tax Value', 'gym_mgt' ) ;?> (%)</th>
													<th><?php  _e( 'Action', 'gym_mgt' ) ;?></th>
												</tr>
											</tfoot>
											<tbody>
												<?php 
												$taxdata=$obj_tax->MJ_gmgt_get_all_taxes();
												if(!empty($taxdata))
												{
													foreach ($taxdata as $retrieved_data)
													{
												 ?>
													<tr>
														<td class="title"><input type="checkbox" name="selected_id[]" class="sub_chk" value="<?php echo $retrieved_data->tax_id; ?>"></td>
														<td class=""><?php echo $retrieved_data->tax_title; ?></td>
														<td class=""><?php echo $retrieved_data->tax_value; ?></td>							
														<td class="action">							
															<a href="?page=gmgt_taxes&tab=addtax&action=edit&tax_id=<?php echo $retrieved_data->tax_id;?>" class="btn btn-info"> <?php _e('Edit', 'gym_mgt' ) ;?></a>							
															<a href="?page=gmgt_taxes&tab=taxlist&action=delete&tax_id=<?php echo $retrieved_data->tax_id;?>" class="btn btn-danger" onclick="return confirm('<?php _e('Are you sure you want to delete this record?','gym_mgt');?>');"><?php _e( 'Delete', 'gym_mgt' ) ;?> </a>
														</td>
													</tr>
													<?php } 
												}?>
											</tbody>
										</table><!--TAX LIST TABLE END-->	
										<div class="print-button pull-left">
											<input  type="submit" value="<?php _e('Delete Selected','gym_mgt');?>" name="delete_selected" class="btn btn-danger delete_selected "/>
										</div>
									</div><!--TABLE RESPONSIVE DIV END-->	
								</div><!--PANEL BODY DIV END-->	
							</form><!--TAX LIST FORM END-->	
							 <?php 
						}
						if($active_tab == 'addtax')
						{
							require_once GMS_PLUGIN_DIR. '/admin/tax/add_tax.php';
						}						
						?>
					</div><!--PANEL BODY DIV END-->	
	            </div><!--PANEL WHITE DIV END-->	
	        </div><!--COL 12 DIV END-->	
        </div><!--ROW DIV END-->	
    </div><!--MAIN WRAPPER DIV END-->	
</div><!--PAGE INNER DIV END-->	