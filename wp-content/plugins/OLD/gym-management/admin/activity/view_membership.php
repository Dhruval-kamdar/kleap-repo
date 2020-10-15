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
	                  {"bSortable": true}],
			language:<?php echo MJ_gmgt_datatable_multi_language();?>			  
		});
});
</script>
<form name="wcwm_report" action="" method="post"> <!-- MEMBERSHIP LIST FORM START-->   
    <div class="panel-body"><!-- PANEL BODY DIV START-->   
        <div class="table-responsive"><!-- TABLE RESPONSIVE DIV START-->   
			<table id="membership_list" class="display" cellspacing="0" width="100%"><!-- TABLE MEMBERSHIP START-->   
				<thead>
					<tr>
					<th style="width: 50px;height:50px;"><?php  _e( 'Photo', 'gym_mgt' ) ;?></th>
					<th><?php  _e( 'Membership Name', 'gym_mgt' ) ;?></th>
					<th><?php  _e( 'Membership Amount', 'gym_mgt' ) ;?></th>
					  <th><?php _e( 'Membership Period', 'gym_mgt' ) ;?></th>
					  <th> <?php _e( 'Installment Plan', 'gym_mgt' ) ;?></th>
						<th> <?php _e( 'Signup Fee', 'gym_mgt' ) ;?></th>
					   
					</tr>
				</thead>
				<tfoot>
					<tr>
					<th><?php  _e( 'Photo', 'gym_mgt' ) ;?></th>
					<th><?php  _e( 'Membership Name', 'gym_mgt' ) ;?></th>
					<th><?php  _e( 'Membership Amount', 'gym_mgt' ) ;?></th>
					  <th><?php _e( 'Membership Period', 'gym_mgt' ) ;?></th>
					  <th> <?php _e( 'Installment Plan', 'gym_mgt' ) ;?></th>
						<th> <?php _e( 'Signup Fee', 'gym_mgt' ) ;?></th>              
					</tr>
				</tfoot>
		 
				<tbody>
				 <?php 
				 if(isset($_REQUEST['activity_id']))
					$activity_id=$_REQUEST['activity_id'];
					$activity_membership_list = $obj_activity->MJ_gmgt_get_activity_membership($activity_id);
					
				 if(!empty($activity_membership_list))
				 {
					foreach ($activity_membership_list as $retrieved_data)
					{
						$obj_membership=new MJ_Gmgtmembership;
						$membership_data = $obj_membership->MJ_gmgt_get_single_membership($retrieved_data);				
					?>
					<tr>
						<td class="user_image"><?php $userimage=$membership_data->gmgt_membershipimage;		
							if(empty($userimage))
							{
									echo '<img src='.get_option( 'gmgt_system_logo' ).' height="25px" width="25px" class="img-circle" />';
							}
							else
								echo '<img src='.$userimage.' height="25px" width="25px" class="img-circle"/>';
						?>
						</td>
						<td class="membershipname"><a href="?page=gmgt_membership_type&tab=addmembership&action=edit&membership_id=<?php echo $membership_data->membership_id;?>"><?php echo $membership_data->membership_label;?></a></td>
						<td class=""><?php echo MJ_gmgt_get_currency_symbol(get_option( 'gmgt_currency_code' )); ?> <?php echo $membership_data->membership_amount; ?></td>
						<td class="membershiperiod"><?php echo $membership_data->membership_length_id;?></td>
						<td class="installmentplan"><?php echo MJ_gmgt_get_currency_symbol(get_option( 'gmgt_currency_code' )); ?> <?php echo $membership_data->installment_amount." ".get_the_title( $membership_data->install_plan_id );?></td>
						<td class="signup_fee"><?php echo MJ_gmgt_get_currency_symbol(get_option( 'gmgt_currency_code' )); ?> <?php echo $membership_data->signup_fee;?></td>             
					</tr>
					<?php 
					}			
				}
				?>     
				</tbody>        
			</table><!-- TABLE MEMBERSHIP END-->   
        </div><!-- TABLE RESPONSIVE DIV END-->   
	</div><!-- PANEL BODY DIV END-->   
</form><!-- MEMBERSHIP LIST FORM END-->   