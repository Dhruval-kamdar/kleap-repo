<?php ?>
<div class="mailbox-content"><!--MAILBOX CONTENT DIV STRAT-->
 	<table class="table"><!--INBOX TABLE STRAT-->
 		<thead>
 			<tr>
 				<th class="text-right" colspan="5">
                 <?php $message = MJ_gmgt_get_inbox_message(get_current_user_id());
					
					$max = 10;
					if(isset($_GET['pg'])){
						$p = $_GET['pg'];
					}
					else{
						$p = 1;
					}
					$limit = ($p - 1) * $max;
					$prev = $p - 1;
					$next = $p + 1;
					$limits = (int)($p - 1) * $max;
					$totlal_message =count($message);
					$totlal_message = ceil($totlal_message / $max);
					$lpm1 = $totlal_message - 1;
					$offest_value = ($p-1) * $max;
					echo MJ_gmgt_admininbox_pagination($totlal_message,$p,$lpm1,$prev,$next);?>
				</th>
 			</tr>
 		</thead>
 		<tbody>
			<tr> 			
				<th class="hidden-xs"><span><?php _e('Message For','gym_mgt');?></span></th>           
				<th><?php _e('Subject','gym_mgt');?></th>
				<th> <?php _e('Description','gym_mgt');?></th>
				<th><?php _e('Date','gym_mgt');?></th>
			</tr>
 		<?php 
		//GET INBOX MESSAGE
 		$message = MJ_gmgt_get_inbox_message(get_current_user_id(),$limit,$max);	
		if(!empty($message))
		{
			foreach($message as $msg)
			{ ?>
				<tr> 			
					<td><?php echo MJ_gmgt_get_display_name($msg->sender);?></td>
					<td>
						 <a href="?dashboard=user&page=Gmgt_message&tab=view_message&from=inbox&id=<?php echo $msg->message_id;?>"> <?php echo $msg->subject;if($obj_message->MJ_gmgt_count_reply_item($msg->post_id)>=1){?><span class="badge badge-success pull-right"><?php echo $obj_message->MJ_gmgt_count_reply_item($msg->post_id);?></span><?php }?></a>
					</td>
					<td><?php echo wp_trim_words($msg->message_body,5);?></td>
					<td><?php  echo  MJ_gmgt_getdate_in_input_box($msg->date);?></td>
				</tr>
				<?php 
			} 
		}
		else
		{	?>
			<tr>
				<td colspan="4">
					<?php echo "No message available"; ?>
				</td>
			</tr>
		<?php
		} ?> 		
 		</tbody>
 	</table><!--INBOX TABLE END-->
 </div><!--MAILBOX CONTENT DIV END-->
 <?php ?>