<?php

namespace BZ_SGN_WZ\Forms;

class Bztourform {
	
	public function addTourform() {
	
	$dataTours = false;
    if (isset($_GET['tour'])) {
       $dataTours = $this->blitz_tour_wizard_getTour($_GET['tour']);
    }
            
	?>
		<div class="bztsw_main">
				
				<?php if (isset($_GET['tour'])) { ?>
					<h2> <?php echo __('Edit tour','bztsw'); ?> <a href="admin.php?page=bztsw_tourmenu" class="add-new-h2">All Tours</a></h2>
				<?php } else { ?>
					<h2> <?php echo __('Add tour','bztsw'); ?> <a href="admin.php?page=bztsw_tourmenu" class="add-new-h2">All Tours</a></h2>
				<?php } ?>
				
                <div id="bztsw_response"></div>
                <form id="bztsw_add_tour" method="post" onsubmit="bztsw_save_tours(this);return false;">
					
                    <input id="bztsw_id" type="hidden" name="bztsw_id" value="<?php
                    if ($dataTours) {
                        echo $dataTours->bztsw_id;
                    } else {
                        echo '0';
                    }
                    ?>">
                    <table class="form-table">
                        <tbody>
                            <tr>
								<th><?php echo __('Title','bztsw'); ?></th>
								<td>
                                    <input id="bztsw_title" type="text" name="bztsw_title" placeholder="<?php echo __('Tour name','bztsw'); ?>" value="<?php
                                    if ($dataTours) {
                                        echo $dataTours->bztsw_title;
                                    }
                                    ?>">
                                 </td>
                            </tr>    
                            <tr>
								<th><?php echo __('Begin Strategy','bztsw'); ?></th>
                                <td>
                                    <select id="bztsw_begin" name="bztsw_begin" placeholder="<?php echo __('Select start method','bztsw'); ?>">
                                        <?php
                                        $opt1 = '';
                                        $opt2 = '';
                                        if ($dataTours && $dataTours->bztsw_begin == 'click') {
                                            $opt2 = 'selected';
                                        } else {
                                            $opt1 = 'selected';
                                        }
                                        echo '<option value="auto" ' . $opt1 . '>'.__('Start automatically','bztsw').'</option>';
                                        echo '<option value="click" ' . $opt2 . '>'.__('On click on an element','bztsw').'</option>';
                                        ?>
                                    </select>
                                 </td>
                            </tr>   
							<tr>
								<th><?php echo __('On site or dashboard ?','bztsw'); ?></th>
                                <td>
									
									<select id="bztsw_onDashboard" name="bztsw_onDashboard">
                                        <?php
                                        $opt1 = '';
                                        $opt2 = '';
                                        if ($dataTours && $dataTours->bztsw_onDashboard) {
                                            $opt2 = 'selected=selected';
                                        } else {
                                            $opt1 = 'selected=selected';
                                        }
                                        echo '<option value="0" '.$opt1.'>Website</option>';
                                        echo '<option value="1" '.$opt2.' >Dashboard</option>';
                                        ?>
                                    </select>
                                    
                                 </td>
                            </tr>   
                            
							<tr>
								<th><?php echo __('Target DOM element','bztsw'); ?></th>
                                <td>
                                    <span>
                                        <?php
                                        if ($dataTours && $dataTours->bztsw_domComponent != "") {
                                            echo  __('Element selected','bztsw');
                                        }
                                        ?>
                                    </span>
                                    <input type="text" id="bztsw_domComponent" name="bztsw_domComponent" value="<?php
                                    if ($dataTours) {
                                        echo $dataTours->bztsw_domComponent;
                                    }
                                    ?>" />
                                    <a href="javascript:" onclick="bztsw_selectTarget();" class="button-primary"><?php echo __('Choose','bztsw'); ?></a>
                                 </td>
                            </tr>
                            

                            <tr>
								<th><?php echo __('Page URL','bztsw'); ?></th>
                                <td>
                                    <input id="bztsw_pageurl" type="text" name="bztsw_pageurl" placeholder="http://" value="<?php
                                    if ($dataTours) {
                                        echo $dataTours->bztsw_pageurl;
                                    }
                                    ?>">
                                 </td>
                            </tr>  
                            
                            <?php if(class_exists('WP_Ultimo')) { ?>
								
								<tr>
									<th><?php _e('Select Plan', 'bztsw'); ?></th>
									<td>
									   <select id="bztsw_ultimoPlan" name="bztsw_ultimoPlan">
											<?php
											
												global $wpdb;											
												$prefix = $wpdb->prefix;
												$sql  = "SELECT DISTINCT ID, post_title FROM {$prefix}posts, {$prefix}postmeta";
												$sql .= " WHERE {$prefix}posts.ID = {$prefix}postmeta.post_id AND {$prefix}postmeta.meta_key = 'wpu_order' AND post_type = 'wpultimo_plan' && post_status = 'publish' ORDER BY {$prefix}postmeta.meta_value ASC";
												$plans = $wpdb->get_results($sql, 'ARRAY_A');
												
												echo '<option value="">Select Plan</option>';
												
												foreach($plans as $plan) {													
													$sel1 = '';
													if ($dataTours && $dataTours->bztsw_ultimoPlan == $plan['ID']) {
														$sel1 = 'selected';
													}
													echo '<option value="'.$plan['ID'].'" ' . $sel1 . '>'. __($plan['post_title'],'bztsw').'</option>';
												}
											?>
										</select>
									</td>
								</tr>
							<?php } ?>
							
                          <?php if( !class_exists('WP_Ultimo') )  { ?>

                            <tr>
								<th><?php _e('Check to set as default signup tour', 'bztsw'); ?></th>
								<td>
									<?php
									if ($dataTours && $dataTours->bztsw_defaultTour != "0") {
                                      $checked = 'checked';
									}  else {
									  $checked = '';
									}
									echo '<p><input name="bztsw_defaultTour" value="" '.$checked.' type="checkbox"/><span style="padding-left: 16px;">' . __('Check to set as default tour','bztsw') . '</span></p>';
										
									?>
								</td>
							</tr>
							
						<?php } ?>
                        
                            <tr>
								<th></th>
                                <td>
                                    <input type="submit" value="<?php echo __('Save','bztsw'); ?>" class="button-primary"/>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </form>
</div>

<?php } 


    /**
     * Get specific Step datas
     */
    public function blitz_tour_wizard_getTour($tour_id) {
        global $wpdb;
        if ( is_multisite() ) {
			switch_to_blog(1);
			$tableName = $wpdb->prefix . "bztsw_tours";
			$rows = $wpdb->get_results("SELECT * FROM $tableName WHERE bztsw_id=$tour_id LIMIT 1");
			restore_current_blog();
		} else {
			$tableName = $wpdb->prefix . "bztsw_tours";
			$rows = $wpdb->get_results("SELECT * FROM $tableName WHERE bztsw_id=$tour_id LIMIT 1");			
		}
        return $rows[0];
    }
    

}
