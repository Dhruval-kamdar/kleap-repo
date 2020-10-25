<?php 
$obj_membership=new MJ_Gmgtmembership;
$obj_activity=new MJ_Gmgtactivity;
$active_tab = isset($_REQUEST['tab'])?$_REQUEST['tab']:'membershiplist';
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
//SAVE MEMBERSHIP DATA
if(isset($_POST['save_membership']))
{
	$nonce = $_POST['_wpnonce'];
	if (wp_verify_nonce( $nonce, 'save_membership_nonce' ) )
	{
		if(isset($_FILES['gmgt_membershipimage']) && !empty($_FILES['gmgt_membershipimage']) && $_FILES['gmgt_membershipimage']['size'] !=0)
		{
			if($_FILES['gmgt_membershipimage']['size'] > 0)
			{
				$member_image=MJ_gmgt_load_documets($_FILES['gmgt_membershipimage'],'gmgt_membershipimage','pimg');
				$member_image_url=content_url().'/uploads/gym_assets/'.$member_image;
			}
						
		}
		else{
			
			if(isset($_REQUEST['hidden_upload_user_avatar_image']))
			{
				$member_image=$_REQUEST['hidden_upload_user_avatar_image'];
				$member_image_url=$member_image;
			}
		}
		
		$ext=MJ_gmgt_check_valid_extension($member_image_url);
		
		if(!$ext == 0)
		{			
			if(isset($_REQUEST['action'])&& $_REQUEST['action']=='edit')
			{
				$result=$obj_membership->MJ_gmgt_add_membership($_POST,$member_image_url);
				if($result)
				{
					wp_redirect ( home_url().'?dashboard=user&page=membership&tab=membershiplist&message=2');
				}
			}
			else
			{
			
				$result=$obj_membership->MJ_gmgt_add_membership($_POST,$member_image_url);
				if($result)
				{
					wp_redirect ( home_url().'?dashboard=user&page=membership&tab=membershiplist&message=1');
				}
			}	
		}			
		else
		{
			?>
			<div id="message" class="updated below-h2 ">
			<p>
				<?php _e('Sorry, only JPG, JPEG, PNG & GIF And BMP files are allowed.','gym_mgt');?>
			</p></div>				 
			<?php 
		}
	}
}
//Delete MEMBERSHIP DATA
if(isset($_REQUEST['action'])&& $_REQUEST['action']=='delete')
{
	
	$result=$obj_membership->MJ_gmgt_delete_membership($_REQUEST['membership_id']);
	if($result)
	{
		wp_redirect ( home_url().'?dashboard=user&page=membership&tab=membershiplist&message=3');
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
				_e('Membership added successfully.','gym_mgt');
			?></p></div>
			<?php 
		
	}
	elseif($message == 2)
	{?><div id="message" class="updated below-h2 "><p><?php
				_e("Membership updated successfully.",'gym_mgt');
				?></p>
				</div>
			<?php 
		
	}
	elseif($message == 3) 
	{?>
	<div id="message" class="updated below-h2"><p>
	<?php 
		_e('Membership deleted successfully.','gym_mgt');
	?></div></p><?php
			
	}
}?>
<script type="text/javascript">
$(document).ready(function() 
{
	jQuery('#membership_list').DataTable({
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
					  {"bSortable": false}
					  ],
				language:<?php echo MJ_gmgt_datatable_multi_language();?>		  
		});
	$('#membership_form').validationEngine({promptPosition : "bottomRight",maxErrorsPerField: 1});	
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
<div class="panel-body panel-white"><!--PANEL WHITE DIV START--> 
    <ul class="nav nav-tabs panel_tabs" role="tablist"><!--NAV TABS MENU START--> 
		<li class="<?php if($active_tab == 'membershiplist') echo "active";?>">
			  <a href="?dashboard=user&page=membership&tab=membershiplist">
				 <i class="fa fa-align-justify"></i> <?php _e('Membership', 'gym_mgt'); ?></a>
			  </a>
		</li>		  
		<li class="<?php if($active_tab=='addmembership'){?>active<?php }?>">
			  <?php 
				if(isset($_REQUEST['action']) && $_REQUEST['action'] == 'edit' && isset($_REQUEST['membership_id']))
				{
				?>
					<a href="?dashboard=user&page=membership&tab=addmembership&action=edit&membership_id=<?php echo $_REQUEST['membership_id'];?>" class="nav-tab <?php echo $active_tab == 'addmembership' ? 'nav-tab-active' : ''; ?>">
					<i class="fa fa"></i> <?php _e('Edit Membership', 'gym_mgt'); ?></a>
				 <?php 
				}
				else
				{
					if($user_access['add']=='1')
					{
					?>
						<a href="?dashboard=user&page=membership&tab=addmembership&&action=insert" class="nav-tab <?php echo $active_tab == 'addmembership' ? 'nav-tab-active' : ''; ?>">
						<i class="fa fa-plus-circle"></i> <?php _e('Add Membership', 'gym_mgt'); ?></a>
					<?php 
					} 
				}
			?>	  
		</li>
		<?php 
		if(isset($_REQUEST['tab']) && $_REQUEST['tab'] == 'view-activity')
		{ ?>
	
			<li class="<?php if($active_tab=='view-activity'){?>active<?php }?>">
				  <a href="?dashboard=user&page=membership&tab=view-activity&membership_id=<?php echo $_REQUEST['membership_id'];?>">
					 <i class="fa fa-align-justify"></i> <?php _e('View Activity', 'gym_mgt'); ?></a>
				  </a>
			</li>	
		<?php	  
		}
		?>
    </ul><!--NAV TABS MENU END--> 
	<div class="tab-content"><!--TAB CONTENT DIV  START--> 
    	<?php 
		if($active_tab == 'membershiplist')
		{ ?>
			<div class="panel-body"><!--PANEL BODY DIV START--> 
				<div class="table-responsive"><!--TABLE RESPONSIVE DIV START--> 
				   <table id="membership_list" class="display dataTable " cellspacing="0" width="100%"><!--MEMBERSHIP LIST TABLE START--> 
						<thead>
							<tr>
								<th style="width: 50px;height:50px;"><?php  _e( 'Photo', 'gym_mgt' ) ;?></th>
								<th><?php  _e( 'Membership Name', 'gym_mgt' ) ;?></th>
								<th><?php _e( 'Membership Period', 'gym_mgt' ) ;?></th>
								 <th><?php _e( 'Membership Amount', 'gym_mgt' ) ;?>(<?php echo MJ_gmgt_get_currency_symbol(get_option( 'gmgt_currency_code' )); ?>)</th>
								<th> <?php _e( 'Installment Plan', 'gym_mgt' ) ;?>(<?php echo MJ_gmgt_get_currency_symbol(get_option( 'gmgt_currency_code' )); ?>)</th>
								<th> <?php _e( 'Signup Fee', 'gym_mgt' ) ;?>(<?php echo MJ_gmgt_get_currency_symbol(get_option( 'gmgt_currency_code' )); ?>)</th>
								<th> <?php _e( 'Tax', 'gym_mgt' ) ;?>(%)</th>												
								<th><?php  _e( 'Action', 'gym_mgt' ) ;?></th>						
							</tr>
							
						</thead>
						<tfoot>
							<tr>
								<th><?php  _e( 'Photo', 'gym_mgt' ) ;?></th>
								<th><?php  _e( 'Membership Name', 'gym_mgt' ) ;?></th>
								<th><?php _e( 'Membership Period', 'gym_mgt' ) ;?></th>
								 <th><?php _e( 'Membership Amount', 'gym_mgt' ) ;?>(<?php echo MJ_gmgt_get_currency_symbol(get_option( 'gmgt_currency_code' )); ?>)</th>
								<th> <?php _e( 'Installment Plan', 'gym_mgt' ) ;?>(<?php echo MJ_gmgt_get_currency_symbol(get_option( 'gmgt_currency_code' )); ?>)</th>
								<th> <?php _e( 'Signup Fee', 'gym_mgt' ) ;?>(<?php echo MJ_gmgt_get_currency_symbol(get_option( 'gmgt_currency_code' )); ?>)</th>
								<th> <?php _e( 'Tax', 'gym_mgt' ) ;?>(%)</th>
								<th><?php  _e( 'Action', 'gym_mgt' ) ;?></th>	
							</tr>
						</tfoot>
						<tbody>
							<?php
							if($obj_gym->role == 'member')
							{	
								if($user_access['own_data']=='1')
								{
									$user_id=get_current_user_id();
									$membership_id = get_user_meta( $user_id,'membership_id', true ); 
									$membershipdata=$obj_membership->MJ_gmgt_get_member_own_membership($membership_id);			
								}
								else
								{
									$membershipdata=$obj_membership->MJ_gmgt_get_all_membership();
								}	
							}
							elseif($obj_gym->role == 'staff_member' || $obj_gym->role == 'accountant')
							{
								if($user_access['own_data']=='1')
								{
									$user_id=get_current_user_id();							
									$membershipdata=$obj_membership->MJ_gmgt_get_membership_by_created_by($user_id);			
								}
								else
								{
									$membershipdata=$obj_membership->MJ_gmgt_get_all_membership();
								}
							}
							
							if(!empty($membershipdata))
							{
								foreach ($membershipdata as $retrieved_data)
								{
				
									if($retrieved_data->install_plan_id == 0)
									{
										$plan_id="";
									}
									else
									{
										$plan_id=get_the_title( $retrieved_data->install_plan_id );
									}
								?>
								<tr>
									<td class="user_image"><?php $userimage=$retrieved_data->gmgt_membershipimage;
												
											if(empty($userimage))
											{
												echo '<img src='.get_option( 'gmgt_system_logo' ).' height="25px" width="25px" class="img-circle" />';
											}
											else
												echo '<img src='.$userimage.' height="25px" width="25px" class="img-circle"/>';
									?></td>
									<td class="membershipname">
									<?php 
										if($obj_gym->role == 'staff_member' || $obj_gym->role == 'accountant')
									   {?>
											<a href="?dashboard=user&page=membership&tab=addmembership&action=edit&membership_id=<?php echo $retrieved_data->membership_id;?>"><?php echo $retrieved_data->membership_label;?></a>
									   <?php
									   }
									   else
									   {?>
										   <?php echo $retrieved_data->membership_label;?>
									   <?php }?>
									</td>
									<td class="membershiperiod"><?php echo $retrieved_data->membership_length_id;?></td>
									<td class=""><?php echo $retrieved_data->membership_amount;?></td>
									<td class="installmentplan"><?php   echo $retrieved_data->installment_amount." ".$plan_id;?></td>
									<td class="signup_fee"><?php echo $retrieved_data->signup_fee;?></td>
									<td class=""><?php if(!empty($retrieved_data->tax)) { echo MJ_gmgt_tax_name_by_tax_id_array($retrieved_data->tax); }else{ echo '-'; } ?></td>
											
									<td class="action">
									<?php
									if($user_access['edit']=='1')
									{
									?>
										<a href="?dashboard=user&page=membership&tab=addmembership&action=edit&membership_id=<?php echo $retrieved_data->membership_id?>" class="btn btn-info"> <?php _e('Edit', 'gym_mgt' ) ;?></a>
									<?php
									}
									if($user_access['delete']=='1')
									{
									?>	
										<a href="?dashboard=user&page=membership&tab=membershiplist&action=delete&membership_id=<?php echo $retrieved_data->membership_id;?>" class="btn btn-danger" 
										onclick="return confirm('<?php _e('Do you really want to delete this record?','gym_mgt');?>');">
										<?php _e( 'Delete', 'gym_mgt' ) ;?> </a>				
									<?php
									}
									?>	
									<a href="#" class="view_details_popup btn btn-default" id="<?php echo $retrieved_data->membership_id?>" type="<?php echo 'view_membership';?>"><i class="fa fa-eye"> </i><?php _e('View', 'gym_mgt' ) ;?> </a>
									<a href="?dashboard=user&page=membership&tab=view-activity&membership_id=<?php echo $retrieved_data->membership_id?>" class="btn btn-success"> 
									<?php _e('View Activities', 'gym_mgt' );?></a>
									</td>
								</tr>
								<?php
								} 
							}?>
						</tbody>
					</table><!--MEMBERSHIP LIST TABLE END-->
				</div><!--TABLE RESPONSIVE DIV END-->
			</div><!--PANEL BODY DIV END-->
			<?php
		}
		if($active_tab == 'addmembership')
		{  ?>
			<script type="text/javascript">
			$(document).ready(function()
			{
				var member_limit='';
				$('#membership_form').validationEngine({promptPosition : "bottomRight",maxErrorsPerField: 1});	
				$('#activity_id').multiselect(
				{
					nonSelectedText :'<?php _e('Select Activity','gym_mgt');?>',
					includeSelectAllOption: true,
					enableFiltering: true,
					enableCaseInsensitiveFiltering: true,
					filterPlaceholder: '<?php _e('Search for activity...','gym_mgt');?>'
				}); 
				$('#activity_category').multiselect(
				{
					nonSelectedText :'<?php _e('Select Activity Category','gym_mgt');?>',
					includeSelectAllOption: true,
					enableFiltering: true,
					allowClear: true,
					enableCaseInsensitiveFiltering: true,
					filterPlaceholder: '<?php _e('Search for activity category...','gym_mgt');?>'
				}); 
				$('.tax_charge').multiselect({
				nonSelectedText :'<?php _e('Select Tax','gym_mgt'); ?>',
				includeSelectAllOption: true,
				selectAllText : '<?php _e('Select all','gym_mgt'); ?>'
			 });	
			});
			</script>
			<?php 
			$obj_membership=new MJ_Gmgtmembership;
			$obj_activity=new MJ_Gmgtactivity;
			$membership_id=0;
			if(isset($_REQUEST['membership_id']))
				$membership_id=$_REQUEST['membership_id'];
				$edit=0;
				if(isset($_REQUEST['action']) && $_REQUEST['action'] == 'edit')
				{
					$edit=1;
					$result = $obj_membership->MJ_gmgt_get_single_membership($membership_id);
				}?>		
				<div class="panel-body"><!-- PANEL BODY DIV START -->
					<form name="membership_form" action="" method="post" class="form-horizontal" id="membership_form" enctype="multipart/form-data"><!-- MEMBERSHIP FORM START -->
						<?php $action = isset($_REQUEST['action'])?$_REQUEST['action']:'insert';?>
						<input type="hidden" name="action" value="<?php echo $action;?>">
						<input type="hidden" name="membership_id" class="membership_id_activity" value="<?php echo $membership_id;?>"  />
						<div class="form-group">
							<label class="col-sm-2 control-label" for="membership_name"><?php _e('Membership Name','gym_mgt');?><span class="require-field">*</span></label>
							<div class="col-sm-8">
								<input id="membership_name" class="form-control validate[required,custom[popup_category_validation]] text-input"  maxlength="50" type="text" value="<?php if($edit){ echo $result->membership_label;}elseif(isset($_POST['membership_name'])) echo $_POST['membership_name'];?>" name="membership_name">
							</div>
						</div>
						<div class="form-group">
							<label class="col-sm-2 control-label" for="membership_category"><?php _e('Membership Category','gym_mgt');?><span class="require-field">*</span></label>
							<div class="col-sm-8">
								<select class="form-control" name="membership_category" id="membership_category">
									<option value=""><?php _e('Select Membership Category','gym_mgt');?></option>
									<?php 
									if(isset($_REQUEST['membership_category']))
										$category =$_REQUEST['membership_category'];  
									elseif($edit)
										$category =$result->membership_cat_id;
									else 
										$category = "";
									
									$mambership_category=MJ_gmgt_get_all_category('membership_category');
									if(!empty($mambership_category))
									{
										foreach ($mambership_category as $retrive_data)
										{
											echo '<option value="'.$retrive_data->ID.'" '.selected($category,$retrive_data->ID).'>'.$retrive_data->post_title.'</option>';
										}
									}
									?>
								</select>
							</div>
							<div class="col-sm-2 add_category_padding_0"><button id="addremove" model="membership_category"><?php _e('Add Or Remove','gym_mgt');?></button></div>
						</div>
						<div class="form-group">
							<label class="col-sm-2 control-label" for="membership_period"><?php _e('Membership Period(Days)','gym_mgt');?><span class="require-field">*</span></label>
							<div class="col-sm-8">	
								<input id="membership_period" class="form-control validate[required,custom[number]] text-input" type="number" min="0" onKeyPress="if(this.value.length==3) return false;" value="<?php if($edit){ echo $result->membership_length_id;}elseif(isset($_POST['membership_period'])) echo $_POST['membership_period'];?>" name="membership_period" placeholder="<?php _e('Enter Total Number of Days','gym_mgt');?>">
						</div>						
						<div class="form-group">
							<label class="col-sm-2 control-label" for="member_limit"><?php _e('Members Limit','gym_mgt');?></label>
							<div class="col-sm-8">
							<?php $limitval = "unlimited"; if($edit){ $limitval=$result->membership_class_limit; }elseif(isset($_POST['gender'])) {$limitval=$_POST['gender'];}?>
								<label class="radio-inline">
								 <input type="radio" value="limited" class="tog radio_class_member"  name="member_limit"  <?php  checked( 'limited', $limitval);  ?>/><?php _e('limited','gym_mgt');?>
								</label>
								<label class="radio-inline">
								  <input type="radio" value="unlimited" class="tog radio_class_member"  name="member_limit"  <?php  checked( 'unlimited', $limitval);  ?>/><?php _e('unlimited','gym_mgt');?> 
								</label>
							</div>
						</div>			
						<?php 
						if($edit)
						{
							if($result->membership_class_limit!='unlimited')
							{ ?>
									<div id="on_of_member_box">
								<div class="form-group ">
								<label class="col-sm-2 control-label" for="on_of_member"><?php _e('No Of Member','gym_mgt');?></label>
								<div class="col-sm-8">
									<input id="on_of_member" class="form-control text-input" type="number" min="0" onkeypress="if(this.value.length==4) return false;"  value="<?php print $result->on_of_member ?>" name="on_of_member">
								</div>
								</div>				
							</div>
							<?php 
							} 		
						} 		
						?>
						<div id="member_limit"></div>		
						<div class="form-group">
							<label class="col-sm-2 control-label " for="classis_limit"><?php _e('Class Limit','gym_mgt');?></label>
							<div class="col-sm-8">
							<?php $limitvals = "unlimited"; if($edit){ $limitvals=$result->classis_limit; }elseif(isset($_POST['gender'])) {$limitvals=$_POST['gender'];}?>
								<label class="radio-inline">
								 <input type="radio" value="limited" class="classis_limit" style="margin-top: 2px;" name="classis_limit"  <?php  checked( 'limited', $limitvals);  ?>/><?php _e('limited','gym_mgt');?>
								</label>
								<label class="radio-inline">
								  <input type="radio" value="unlimited" class="classis_limit validate[required]" style="margin-top: 2px;" name="classis_limit"  <?php  checked( 'unlimited', $limitvals);  ?>/><?php _e('unlimited','gym_mgt');?> 
								</label>
							</div>
						</div>
						<div id="classis_limit"></div>	
						<?php
						if($edit)
						{ 
							if($result->classis_limit!='unlimited')
							{ 
							?>
							<div id="on_of_classis_box">
								<div class="form-group ">
								<label class="col-sm-2 control-label radio_class_member" for="on_of_classis"><?php _e('No Of Class','gym_mgt');?></label>
								<div class="col-sm-8">
									<input id="on_of_classis" class="form-control  text-input" type="number" min="0" onkeypress="if(this.value.length==4) return false;" value="<?php print $result->on_of_classis ?>" name="on_of_classis">
								</div>
								</div>				
							</div>
							<?php 
							} 
						} 
						?>
						<div class="form-group">
							<label class="col-sm-2 control-label" for="installment_amount"><?php _e('Membership Amount','gym_mgt');?>(<?php echo MJ_gmgt_get_currency_symbol(get_option( 'gmgt_currency_code' )); ?>)<span class="require-field">*</span></label>
							<div class="col-sm-8">
								<input id="membership_amount" class="form-control text-input validate[required]" type="number" min="0"  onkeypress="if(this.value.length==8) return false;" step="0.01" value="<?php if($edit){ echo $result->membership_amount;}elseif(isset($_POST['membership_amount'])) echo $_POST['membership_amount'];?>" name="membership_amount" >
							</div>
						</div>
						<div class="form-group">
							<label class="col-sm-2 control-label" for="installment_plan"><?php _e('Installment Plan','gym_mgt');?>(<?php echo MJ_gmgt_get_currency_symbol(get_option( 'gmgt_currency_code' )); ?>)<span class="require-field">*</span></label>
							<div class="col-sm-2">
								<input id="installment_amount" class="form-control text-input validate[required]" type="number" min="0"  onkeypress="if(this.value.length==8) return false;" step="0.01" value="<?php if($edit){ echo $result->installment_amount;}elseif(isset($_POST['installment_amount'])) echo $_POST['installment_amount'];?>" name="installment_amount" placeholder="<?php _e('Amount','gym_mgt');?>">
							</div>
							<div class="col-sm-6">
							
								<select class="form-control" name="installment_plan" id="installment_plan">
									<option value=""><?php _e('Select Installment Plan','gym_mgt');?></option>
									<?php 
									
									if(isset($_REQUEST['installment_plan']))
										$category =$_REQUEST['installment_plan'];  
									elseif($edit)
										$category =$result->install_plan_id;
									else 
										$category = "";
									
									$installment_plan=MJ_gmgt_get_all_category('installment_plan');
									if(!empty($installment_plan))
									{
										foreach ($installment_plan as $retrive_data)
										{
											echo '<option value="'.$retrive_data->ID.'" '.selected($category,$retrive_data->ID).'>'.$retrive_data->post_title.'</option>';
										}
									}
									?>
								</select>
							</div>
							<div class="col-sm-2 add_category_padding_0"><button id="addremove" model="installment_plan"><?php _e('Add Or Remove','gym_mgt');?></button></div>
						</div>
						
						<div class="form-group">
							<label class="col-sm-2 control-label" for="signup_fee"><?php _e('Signup Fee','gym_mgt');?>(<?php echo MJ_gmgt_get_currency_symbol(get_option( 'gmgt_currency_code' )); ?>)</label>
							<div class="col-sm-8">
								<input id="signup_fee" class="form-control text-input" type="number" min="0"  onkeypress="if(this.value.length==8) return false;" step="0.01" value="<?php if($edit){ echo $result->signup_fee;}elseif(isset($_POST['membership_name'])) echo $_POST['membership_name'];?>" name="signup_fee">
							</div>
						</div>
						<div class="form-group">
							<label class="col-sm-2 control-label" for=""><?php _e('Tax','gym_mgt');?>(%)</label>
							<div class="col-sm-3">
								<select  class="form-control tax_charge" name="tax[]" multiple="multiple">					
									<?php					
									if($edit)
									{
										$tax_id=explode(',',$result->tax);
									}
									else
									{	
										$tax_id[]='';
									}
									$obj_tax=new MJ_Gmgttax;
									$gmgt_taxs=$obj_tax->MJ_gmgt_get_all_taxes();	
									
									if(!empty($gmgt_taxs))
									{
										foreach($gmgt_taxs as $data)
										{
											$selected = "";
											if(in_array($data->tax_id,$tax_id))
												$selected = "selected";
											?>
											<option value="<?php echo $data->tax_id; ?>" <?php echo $selected; ?> ><?php echo $data->tax_title;?>-<?php echo $data->tax_value;?></option>
										<?php 
										}
									}
									?>
								</select>		
							</div>
						</div>
						<div class="form-group">
							<label class="col-sm-2 control-label" for="activity_category"><?php _e('Select Activity Category','gym_mgt');?></label>
							<div class="col-sm-8">
								<?php
								if($edit)
								{
								?>
									<input type="hidden" class="action_membership" value="edit_membership">
								<?php
								}
								else
								{
								?>
									<input type="hidden" class="action_membership" value="add_membership">
								<?php
								}
								?>
								<select class="form-control activity_category_list" name="activity_cat_id[]" multiple="multiple" id="activity_category"><?php 					
									$activity_category=MJ_gmgt_get_all_category('activity_category');
									if($edit)
									{
										$activity_category_array=explode(',',$result->activity_cat_id);
									}
									else
									{	
										$activity_category_array[]='';
									}
									
									if(!empty($activity_category))
									{
										foreach ($activity_category as $retrive_data)
										{		
											$selected = "";
											if(in_array($retrive_data->ID,$activity_category_array))
												$selected = "selected";
											?>
												<option value="<?php echo $retrive_data->ID;?>" <?php echo $selected; ?>><?php echo $retrive_data->post_title;?></option>
											<?php
										}
									}
								?>				
								</select>
							</div>		
						</div>
						<div class="form-group">
							<label class="col-sm-2 control-label" for="signup_fee"><?php _e('Select Activity','gym_mgt');?></label>
							<div class="col-sm-8">
								<?php 
									$activitydata=$obj_activity->MJ_gmgt_get_all_activity_by_activity_category($activity_category_array); ?>
								<select name="activity_id[]" id="activity_id" multiple="multiple" class="activity_list_from_category_type">		 <?php 
									$activity_array = $obj_activity->MJ_gmgt_get_membership_activity($membership_id);
									if(!empty($activitydata))
									{
										foreach($activitydata as $activity)
										{
											?>
											<option value="<?php echo $activity->activity_id;?>" <?php if(in_array($activity->activity_id,$activity_array)) echo "selected";?>><?php echo $activity->activity_title;?></option>
										<?php
										}
									}
									?>
								</select>
							</div>
						</div>
						<div class="form-group">
							<label class="col-sm-2 control-label" for="photo"><?php _e('Membership Image','gym_mgt');?></label>
							<div class="col-sm-2">
								<input type="text" id="gmgt_user_avatar_url" class="form-control" name="gmgt_membershipimage"  readonly
								value="<?php if($edit)echo esc_url( $result->gmgt_membershipimage );elseif(isset($_POST['gmgt_membershipimage'])) echo $_POST['gmgt_membershipimage']; ?>" />
							</div>	
							<div class="col-sm-3">
								<input type="hidden" name="hidden_upload_user_avatar_image" value="<?php if($edit){ echo $result->gmgt_membershipimage;}elseif(isset($_POST['gmgt_membershipimage'])) echo $_POST['gmgt_membershipimage'];?>">
									 <input id="upload_user_avatar_image" name="gmgt_membershipimage" onchange="fileCheck(this);" type="file" class="form-control file image_upload_change" value="<?php _e( 'Upload image', 'gym_mgt' ); ?>" />
							</div>
							<div class="clearfix"></div>
							
							<div class="col-sm-offset-2 col-sm-8">
								<div id="upload_user_avatar_preview" >
									 <?php
										if($edit) 
										{
											if($result->gmgt_membershipimage == "")
											{?>
												<img class="image_preview_css" src="<?php echo get_option( 'gmgt_system_logo' ); ?>">
											<?php 
											}
											else 
											{
												?>
												<img class="image_preview_css" src="<?php if($edit)echo esc_url( $result->gmgt_membershipimage ); ?>" />
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
						<!--nonce-->
						<?php wp_nonce_field( 'save_membership_nonce' ); ?>
						<!--nonce-->
						<div class="col-sm-offset-2 col-sm-8">
							<input type="submit" value="<?php if($edit){ _e('Save Membership','gym_mgt'); }else{ _e('Add Membership','gym_mgt');}?>" name="save_membership" class="btn btn-success"/>
						</div>
					</form><!-- MEMBERSHIP FORM END -->
				</div><!-- PANEL BODY DIV END -->
		<?php 
		}
		if($active_tab == 'view-activity')
		{
			?>
			<script type="text/javascript">
			$(document).ready(function() {
				$('#activity_id').multiselect();
				$('#acitivity_form').validationEngine({promptPosition : "bottomRight",maxErrorsPerField: 1});	
				jQuery('#activity_list').DataTable({
					"responsive": true,
					"order": [[ 0, "asc" ]],
					"aoColumns":[
								  {"bSortable": true},
								  {"bSortable": true},
								  {"bSortable": true},
								  {"bSortable": true},
								 ],
						language:<?php echo MJ_gmgt_datatable_multi_language();?>			 
					});
			} );
			</script>
			<?php
			$membership_id=0;
			if(isset($_REQUEST['membership_id']))
				$membership_id=$_REQUEST['membership_id'];
			$activity_result = $obj_membership->MJ_gmgt_get_membership_activities($membership_id); 
			?>			
			<form name="wcwm_report" action="" method="post">    <!-- ACTIVITY LIST FORM START -->
				<div class="panel-body"><!-- PANEL BODY DIV START -->
					<div class="table-responsive"><!-- TABLE RESPONSIVE DIV START -->
						<table id="activity_list" class="display" cellspacing="0" width="100%"><!-- TABLE ACTIVITY LIST START -->
							<thead>
								<tr>
								<th><?php  _e( 'Activity Name', 'gym_mgt' ) ;?></th>
								<th><?php  _e( 'Activity Category', 'gym_mgt' ) ;?></th>
								<th><?php  _e( 'Activity Trainer', 'gym_mgt' ) ;?></th>
								   <th><?php  _e( 'Membership Name', 'gym_mgt' ) ;?></th>
								</tr>
							</thead>
					 
							<tfoot>
								<tr>
								<th><?php  _e( 'Activity Name', 'gym_mgt' ) ;?></th>
								<th><?php  _e( 'Activity Category', 'gym_mgt' ) ;?></th>
								<th><?php  _e( 'Activity Trainer', 'gym_mgt' ) ;?></th>
								<th><?php  _e( 'Membership Name', 'gym_mgt' ) ;?></th>					
								</tr>
							</tfoot>
					 
							<tbody>
								<?php 				
								if(!empty($activity_result))
								{					 
									foreach ($activity_result as $activities)
									{ 					
										$retrieved_data=$obj_activity->MJ_gmgt_get_single_activity($activities->activity_id);
										?>
										<tr>
											<td class="activityname"><a href="?page=gmgt_activity&tab=addactivity&action=edit&activity_id=<?php echo $retrieved_data->activity_id;?>"><?php echo $retrieved_data->activity_title;?></a></td>
											<td class="category"><?php echo get_the_title($retrieved_data->activity_cat_id);?></td>
											<td class="productquentity"><?php $user=get_userdata($retrieved_data->activity_assigned_to);
											echo $user->display_name;?></td>
											<td class="membership"><?php echo MJ_gmgt_get_membership_name($activities->membership_id);?>						
											</td>
										 </tr>
										<?php
									} 									
								} ?>						 
							</tbody>				
						</table><!-- TABLE ACTIVITY LIST END -->
					</div><!-- TABLE RESPONSIVE DIV END -->
				</div> <!-- PANEL BODY DIV END -->   
			</form><!-- FORM ACTIVITY LIST END -->
			<?php
		}	
		?>
	</div><!--TAB CONTENT DIV END--> 
</div><!--PANEL WHITE DIV END--> 
<script type="text/javascript">
function fileCheck(obj) 
{
	var fileExtension = ['jpeg', 'jpg', 'png', 'bmp',''];
	if ($.inArray($(obj).val().split('.').pop().toLowerCase(), fileExtension) == -1)
	{
		alert("<?php _e("Only '.jpeg','.jpg', '.png', '.bmp' formats are allowed.",'gym_mgt');?>");
		$(obj).val('');
	}	
			
}
</script>