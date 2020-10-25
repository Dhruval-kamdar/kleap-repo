<div class="mailbox-content"><!--MAILBOX CONTENT DIV STRAT-->
	<table class="table"><!--SENDBOX TABLE STRAT-->
		<thead>
			<tr>
				<th class="text-right" colspan="5">
					<?php 
					$max = 10;
					if(isset($_GET['pg'])){
						$p = $_GET['pg'];
					}else{
						$p = 1;
					}
				   
					$limit = ($p - 1) * $max;
					$prev = $p - 1;
					$next = $p + 1;
					$limits = (int)($p - 1) * $max;
					$totlal_message = MJ_gmgt_count_send_item(get_current_user_id());
					$totlal_message = ceil($totlal_message / $max);
					$lpm1 = $totlal_message - 1;               	
					$offest_value = ($p-1) * $max;
					echo MJ_gmgt_pagination($totlal_message,$p,$lpm1,$prev,$next);
					?>
				</th>
			</tr>
		</thead>
		<tbody>
			<tr> 			
				<th class="hidden-xs"><span><?php _e('Message For','gym_mgt');?></span></th>
				<th class="hidden-xs"><span><?php _e('Class','gym_mgt');?></span></th>
				<th><?php _e('Subject','gym_mgt');?></th>
				<th><?php _e('Description','gym_mgt');?></th>
			</tr>
			<?php 
			//GET SENDBOX DATA
			$offset = 0;
			if(isset($_REQUEST['pg']))
				$offset = $_REQUEST['pg'];
			$message = MJ_gmgt_get_send_message(get_current_user_id(),$max,$offset);
			foreach($message as $msg_post)
			{
				if($msg_post->post_author==get_current_user_id())
				{
				?>
				<tr>
					<td>
						<span><?php 
						if(get_post_meta( $msg_post->ID, 'message_for',true) == 'user')
						{
							echo MJ_gmgt_get_display_name(get_post_meta( $msg_post->ID, 'message_gmgt_user_id',true));
						}
						else
						{							
							$rolename=get_post_meta( $msg_post->ID, 'message_for',true);
							echo MJ_gmgt_GetRoleName($rolename);
						}
						?>
						</span>
					</td>
					<td>
						<span><?php 
					
						if(get_post_meta( $msg_post->ID, 'gmgt_class_id',true) !="" && get_post_meta( $msg_post->ID, 'gmgt_class_id',true) == 'all')
						{
							
							_e('All','gym_mgt');
						}
						elseif(get_post_meta( $msg_post->ID, 'gmgt_class_id',true) !="")
						{
							echo MJ_gmgt_get_class_name(get_post_meta( $msg_post->ID, 'gmgt_class_id',true)); 
						}?></span>
					</td>
					<td><a href="?page=Gmgt_message&tab=view_message&from=sendbox&id=<?php echo  $msg_post->ID;?>"><?php echo $msg_post->post_title;?></a></td>
					 <td>
						  <?php echo $msg_post->post_content;?>
					</td>
				</tr>
				<?php 
				}
			}
			?>			
		</tbody>
	</table><!--SENDBOX TABLE END-->
</div><!--MAILBOX CONTENT DIV END-->