<?php 
$obj_class=new MJ_Gmgtclassschedule;
$obj_notice=new MJ_Gmgtnotice;
$active_tab = isset($_GET['tab'])?$_GET['tab']:'noticelist';
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
	//SAVE NOTICE DATA
	if(isset($_POST['save_notice']))
	{
		$nonce = $_POST['_wpnonce'];
		if (wp_verify_nonce( $nonce, 'save_notice_nonce' ) )
		{		
			if(isset($_REQUEST['action'])&& $_REQUEST['action']=='edit')
			{				
				$result=$obj_notice->MJ_gmgt_add_notice($_POST);
				if($result)
				{
					wp_redirect ( admin_url().'admin.php?page=gmgt_notice&tab=noticelist&message=2');
				}
			}
			else
			{					
				$result=$obj_notice->MJ_gmgt_add_notice($_POST);
				if($result)
				{
					wp_redirect ( admin_url().'admin.php?page=gmgt_notice&tab=noticelist&message=1');
				}
			} 
		}
	}
   //DELETE NOTICE DATA
	if(isset($_REQUEST['action'])&& $_REQUEST['action']=='delete')
	{
		$result=$obj_notice->MJ_gmgt_delete_notice($_REQUEST['notice_id']);
		if($result)
		{
			wp_redirect ( admin_url().'admin.php?page=gmgt_notice&tab=noticelist&message=3');
		}
	}
	//Delete SELECTED NOTICE DATA	
	if(isset($_REQUEST['delete_selected']))
    {		
		if(!empty($_REQUEST['selected_id']))
		{
			foreach($_REQUEST['selected_id'] as $id)
			{
				$delete_notice=$obj_notice->MJ_gmgt_delete_notice($id);
			}
			if($delete_notice)
			{
				wp_redirect ( admin_url().'admin.php?page=gmgt_notice&tab=noticelist&message=3');
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
					_e('Notice added successfully.','gym_mgt');
				?></p></div>
				<?php 
		}
		elseif($message == 2)
		{?><div id="message" class="updated below-h2 "><p><?php
					_e("Notice updated successfully.",'gym_mgt');
					?></p>
					</div>
				<?php 
		}
		elseif($message == 3) 
		{?>
		<div id="message" class="updated below-h2"><p>
		<?php 
			_e('Notice deleted successfully.','gym_mgt');
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
							<a href="?page=gmgt_notice&tab=noticelist" class="nav-tab <?php echo $active_tab == 'noticelist' ? 'nav-tab-active' : ''; ?>">
							<?php echo '<span class="dashicons dashicons-menu"></span> '.__('Notice List', 'gym_mgt'); ?></a>
							
							<?php  if(isset($_REQUEST['action']) && $_REQUEST['action'] == 'edit')
							{?>
							<a href="?page=gmgt_notice&tab=addnotice&&action=edit&notice_id=<?php echo $_REQUEST['notice_id'];?>" class="nav-tab <?php echo $active_tab == 'addnotice' ? 'nav-tab-active' : ''; ?>">
							<?php _e('Edit Notice', 'gym_mgt'); ?></a>  
							<?php 
							}
							else
							{?>
								<a href="?page=gmgt_notice&tab=addnotice" class="nav-tab <?php echo $active_tab == 'addnotice' ? 'nav-tab-active' : ''; ?>">
							<?php echo '<span class="dashicons dashicons-plus-alt"></span> '.__('Add Notice', 'gym_mgt'); ?></a>
								
							<?php  }?>
						   
						</h2><!--NAV TAB WRAPPER MENU END-->
						<?php 						
						if($active_tab == 'noticelist')
						{ 
							?>	
							<script type="text/javascript">
							   $(document).ready(function() 
							   {
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
							<form name="wcwm_report" action="" method="post"><!--NOTICE LIST FORM START-->
								<div class="panel-body"><!--PANEL BODY DIV START-->
									<div class="table-responsive"><!--TABLE RESPONSIVE DIV START-->
										<table id="product_list" class="display" cellspacing="0" width="100%"><!--NOTICE LIST FORM START-->
											<thead>
												<tr>
													<th><input type="checkbox" class="select_all"></th>
													<th><?php  _e( 'Notice Title', 'gym_mgt' ) ;?></th>
													<th><?php  _e( 'Notice Comment', 'gym_mgt' ) ;?></th>
													<th><?php  _e( 'Notice For', 'gym_mgt' ) ;?></th>
													<th><?php  _e( 'Class', 'gym_mgt' ) ;?></th>
													<th><?php  _e( 'Start Date', 'gym_mgt' ) ;?></th>
													<th><?php  _e( 'End Date', 'gym_mgt' ) ;?></th>
													<th><?php  _e( 'Action', 'gym_mgt' ) ;?></th>
												</tr>
											</thead>
											<tfoot>
												<tr>
													<th></th>
													<th><?php  _e( 'Notice Title', 'gym_mgt' ) ;?></th>
													<th><?php  _e( 'Notice Comment', 'gym_mgt' ) ;?></th>
													<th><?php  _e( 'Notice For', 'gym_mgt' ) ;?></th>
													<th><?php  _e( 'Class', 'gym_mgt' ) ;?></th>
													<th><?php  _e( 'Start Date', 'gym_mgt' ) ;?></th>
													<th><?php  _e( 'End Date', 'gym_mgt' ) ;?></th>
													<th><?php  _e( 'Action', 'gym_mgt' ) ;?></th>
												</tr>
											</tfoot>
											<tbody>
											<?php 
											//GET NOTICE DATA
											$args['post_type'] = 'gmgt_notice';
											$args['posts_per_page'] = -1;
											$args['post_status'] = 'public';
											$q = new WP_Query();
											$noticedata = $q->query( $args );
											if(!empty($noticedata))
											{
												foreach ($noticedata as $retrieved_data)
												{
												?>
												<tr>
													<td class="title"><input type="checkbox" name="selected_id[]" class="sub_chk" value="<?php echo $retrieved_data->ID; ?>"></td>
													<td class="noticetitle"><a href="?page=gmgt_notice&tab=addnotice&action=edit&notice_id=<?php echo $retrieved_data->ID;?>"><?php echo $retrieved_data->post_title;?></a></td>
													<td class="noticecontent"><?php $strlength= strlen($retrieved_data->post_content);
														if($strlength > 60)
															echo substr($retrieved_data->post_content, 0,60).'...';
														else
															echo $retrieved_data->post_content;?></td>
													<td class="productquentity"><?php echo ucwords(str_replace("_"," ",get_post_meta( $retrieved_data->ID, 'notice_for',true)));?></td>
													<td>
													 <?php 
													 if(get_post_meta( $retrieved_data->ID, 'gmgt_class_id',true) !="" && get_post_meta( $retrieved_data->ID, 'gmgt_class_id',true) =="all")
													 {
														 _e('All','gym_mgt');
													 }
													 elseif(get_post_meta( $retrieved_data->ID, 'gmgt_class_id',true) !="")
													 {
														echo MJ_gmgt_get_class_name(get_post_meta( $retrieved_data->ID, 'gmgt_class_id',true));
													 }
													 else
													 {
														 echo '-';
													 }
													 ?></td>
													 <td><?php echo MJ_gmgt_getdate_in_input_box(get_post_meta($retrieved_data->ID,'gmgt_start_date',true));?></td>
													 <td><?php echo MJ_gmgt_getdate_in_input_box(get_post_meta($retrieved_data->ID,'gmgt_end_date',true));?></td>
													
													<td class="action"> 
													<a href="#" class="view_details_popup btn btn-default" id="<?php echo $retrieved_data->ID?>" type="<?php echo 'view_notice';?>"><i class="fa fa-eye"> </i><?php _e('View', 'gym_mgt' ) ;?> </a>
													<a href="?page=gmgt_notice&tab=addnotice&action=edit&notice_id=<?php echo $retrieved_data->ID?>" class="btn btn-info"> <?php _e('Edit', 'gym_mgt' ) ;?></a>
													<a href="?page=gmgt_notice&tab=noticelist&action=delete&notice_id=<?php echo $retrieved_data->ID;?>" class="btn btn-danger" 
													onclick="return confirm('<?php _e('Do you really want to delete this record?','gym_mgt');?>');">
													<?php _e( 'Delete', 'gym_mgt' ) ;?> </a>
													</td>
												</tr>
												<?php 
												} 
											}
											?>
											</tbody>
										</table><!--NOTICE LIST FORM END-->
										<div class="print-button pull-left">
											<input  type="submit" value="<?php _e('Delete Selected','gym_mgt');?>" name="delete_selected" class="btn btn-danger delete_selected "/>
										</div>
									</div><!--TABLE RESPONSIVE DIV END-->
								</div><!--PANEL BODY DIV END-->
							</form><!--NOTICE LIST FORM END-->
							<?php 
						}
						if($active_tab == 'addnotice')
						{	
							require_once GMS_PLUGIN_DIR. '/admin/notice/add_notice.php';
					    }
						?>
					</div><!--PANEL BODY DIV END-->
				</div><!--PANEL WHITE DIV END-->
			</div><!--COL 12 DIV END-->
		</div><!--ROW DIV END-->
    </div><!--MAIN WRAPPER DIV END-->
</div><!--page inner DIV end-->