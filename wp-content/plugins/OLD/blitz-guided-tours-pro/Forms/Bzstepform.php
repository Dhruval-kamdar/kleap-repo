<?php

namespace BZ_SGN_WZ\Forms;

class Bzstepform {
	
	public function addStepform() {
	
	$toursData = $this->blitz_tour_wizard_getTours();
    $dataSteps = false;            
    if (isset($_GET['step'])) {
	   $dataSteps = $this->blitz_tour_wizard_getSteps($_GET['step']);
	   $helper = $this->blitz_tour_wizard_getTour($dataSteps->bztsw_tourID);
    }
            
	?>
          <div class="bztsw_main">
			  
				<?php if (isset($_GET['step'])) { ?>
					<h2> <?php echo __('Edit step','bztsw'); ?> <a href="admin.php?page=bztsw-steps" class="add-new-h2">All Steps</a></h2>
                <?php } else { ?>
					<h2> <?php echo __('Add step','bztsw'); ?> <a href="admin.php?page=bztsw-steps" class="add-new-h2">All Steps</a></h2>
				<?php } ?>
                
                <div id="bztsw_response"></div>
                <form id="bztsw_add_step" method="post" action="#" onsubmit="bztsw_save_steps(this); return false;">
                    <input id="bztsw_id" type="hidden" name="bztsw_id" value="<?php
                    if ($dataSteps) {
                        echo $dataSteps->bztsw_id;
                    } else {
                        echo '0';
                    }
                    ?>"/>
                    <table class="form-table">
                        <tbody>
							
                            <tr>
                                <th scope="row"><?php echo __('Select Tour','bztsw'); ?></th>
                                <td>
                                    <select id="bztsw_tourID" name="bztsw_tourID" placeholder="<?php echo __('Select tour','bztsw'); ?>">
                                        <?php
                                        foreach ($toursData as $tour) {
                                            $sel = '';
                                            if ($dataSteps && $tour['id'] == $dataSteps->bztsw_tourID) {
                                                $sel = 'selected';
                                            }
                                            echo '<option value="' . $tour['id'] . '" ' . $sel . ' data-page="' . $tour['page'] . '" data-admin="' . $step['onAdmin'] . '">' . $tour['title'] . '</option>';
                                        }
                                        ?>
                                    </select>
                                </td>
                            </tr>
                            
                            <tr>
                                <th scope="row"><?php echo __('Arrange','bztsw'); ?></th>
                                <td>
                                    <input id="bztsw_steporder" type="number" name="bztsw_steporder" placeholder="Item order" value="<?php
                                    if ($dataSteps) {
                                        echo $dataSteps->bztsw_steporder;
                                    } else {
                                        echo '0';
                                    }
                                    ?>">
                                </td>
                            </tr>      
                            
                            <tr>
                                <th scope="row"><?php echo __('Step name','bztsw'); ?></th>
                                <td>
                                    <input id="bztsw_title" type="text" name="bztsw_title" placeholder="Step title" value="<?php
                                    if ($dataSteps) {
                                        echo $dataSteps->bztsw_title;
                                    }
                                    ?>">
                            </tr>                           
                            
                            <tr>
                                <th scope="row"><?php echo __('Step type','bztsw'); ?></th>
                                <td>
                                    <select id="bztsw_stepTy" name="bztsw_stepTy" placeholder="Select type">
                                        <?php
                                        $opt1 = '';
                                        $opt2 = '';
                                        $opt3 = '';
                                        if ($dataSteps && $dataSteps->bztsw_stepTy == 'dialog') {
                                            $opt2 = 'selected';
                                        } else if ($dataSteps && $dataSteps->bztsw_stepTy == 'text') {
                                            $opt3 = 'selected';
                                        } else {
                                            $opt1 = 'selected';
                                        }
                                        echo '<option value="tooltip" ' . $opt1 . '>'.__('Tooltip','bztsw').'</option>';
                                        echo '<option value="dialog" ' . $opt2 . '>'.__('Dialog','bztsw').'</option>';
                                        echo '<option value="text" ' . $opt3 . '>'.__('Text','bztsw').'</option>';
                                        ?>
                                    </select>
                                </td>
                            </tr>        
                                               
                            <tr class="only_tooltip">
                                <th scope="row"><?php echo __('Target DOM Component','bztsw'); ?></th>
                                <td>
                                    <input type="text" id="bztsw_domComponent" name="bztsw_domComponent" value="<?php
                                    if ($dataSteps) {
                                        echo $dataSteps->bztsw_domComponent;
                                    }
                                    ?>" />
                                    <span style="display:none;">
                                        <?php
                                        if ($dataSteps && $dataSteps->bztsw_domComponent != "") {
                                            echo __('Component selected','bztsw');
                                        } else {
                                            echo __('Nothing selected','bztsw');
                                        }
                                        ?>
                                    </span>
                                    <a href="javascript:" onclick="bztsw_selectTarget();" class="button-primary"><?php echo __('Selection','bztsw'); ?></a>
                              </tr>
                            
                            <tr >
                                <th scope="row"><?php echo __('Page URL','bztsw'); ?></th>
                                <td>

                                    <input id="bztsw_pageurl" type="text" name="bztsw_pageurl" placeholder="<?php echo __('Page','bztsw'); ?>" value="<?php
                                    if ($dataSteps) {
                                        echo $dataSteps->bztsw_pageurl;
                                    }
                                    ?>">
                                </td>
                            </tr>

                            <tr style="display: none;" >
                                <th scope="row"><?php echo __('Tour position ?','bztsw'); ?></th>
                                <td>
                                    <select id="bztsw_onDashboard" name="bztsw_onDashboard">
                                        <?php
                                        echo '<option value="0" ' . $opt1 . '>Frontend</option>';
                                        echo '<option value="1" ' . $opt2 . '>Admin</option>';
                                        ?>
                                    </select>
								</td>
                            </tr>   
                            
                            <tr class="only_tooltip">
                                <th scope="row"><?php echo __('Tooltip position','bztsw'); ?></th>
                                <td>
                                    <select id="bztsw_tooltipPos" name="bztsw_tooltipPos" >
                                        <?php
                                        $opt1 = '';
                                        $opt2 = '';
                                        if ($dataSteps && $dataSteps->bztsw_tooltipPos == 'bottom') {
                                            $opt1 = 'selected';
                                        } else if($dataSteps && $dataSteps->bztsw_tooltipPos == 'top') {
                                            $opt2 = 'selected';
                                        } else if($dataSteps && $dataSteps->bztsw_tooltipPos == 'right') {
                                            $opt3 = 'selected';
                                        } else {
											$opt4 = 'selected';
										}
                                        echo '<option value="bottom" ' . $opt1 . '>'.__('Bottom','bztsw').'</option>';
                                        echo '<option value="top" ' . $opt2 . '>'.__('Top','bztsw').'</option>';
                                        echo '<option value="right" ' . $opt3 . '>'.__('Right','bztsw').'</option>';
                                        echo '<option value="left" ' . $opt4 . '>'.__('Left','bztsw').'</option>';
                                        ?>
                                    </select>
                                </td>
                            </tr>
                            
                            <tr class="only_tooltip content" style="display:none;">
                                <th scope="row"><?php echo __('Step text','bztsw'); ?></th>
                                <td>
                                    <?php
                                    $stepCont = "";
                                    if ($dataSteps) {
                                        $stepCont = $dataSteps->bztsw_stepCont;
                                    }
                                    wp_editor($stepCont, 'bztsw_stepCont_tooltip', array(
                                        'tinymce' => array(
                                            'height' => 80
                                        ))
                                    );
                                    ?>
                                </td>
                            </tr>
                            
                            <tr class="only_dialog text">
                                <th scope="row"><?php echo __('Step text','bztsw'); ?></th>
                                <td>
									<?php
                                    $stepCont = "";
                                    if ($dataSteps) {
                                        $stepCont = $dataSteps->bztsw_stepCont;
                                    }
                                    wp_editor($stepCont, 'bztsw_stepCont', array(
                                        'tinymce' => array(
                                            'height' => 80
                                        ))
                                    );
                                    ?>
                                  </td>
                            </tr>
                            
                            <tr>
                                <th scope="row"><?php echo __('Overlay','bztsw'); ?></th>
                                <td>
                                    <select id="bztsw_item_overlay" name="bztsw_item_overlay">
                                        <?php
                                        $opt1 = '';
                                        $opt2 = '';
                                        if ($dataSteps && !$dataSteps->bztsw_item_overlay) {
                                            $opt1 = 'selected';
                                        } else {
                                            $opt2 = 'selected';
                                        }
                                        echo '<option value="0" ' . $opt1 . '>'.__('No','bztsw').'</option>';
                                        echo '<option value="1" ' . $opt2 . '>'.__('Yes','bztsw').'</option>';
                                        ?>
                                    </select>
                                </td>
                            </tr>   
                            
                            <tr>
                                <th scope="row"><?php echo __('Add button to close the tour ?','bztsw'); ?></th>
                                <td>
                                    <select id="bztsw_item_closeHelperBtn" name="bztsw_item_closeHelperBtn">
                                        <?php
                                        $opt1 = '';
                                        $opt2 = '';
                                        if ($dataSteps && !$dataSteps->bztsw_item_closeHelperBtn) {
                                            $opt1 = 'selected';
                                        } else {
                                            $opt2 = 'selected';
                                        }
                                        echo '<option value="0" ' . $opt1 . '>'.__('No','bztsw').'</option>';
                                        echo '<option value="1" ' . $opt2 . '>'.__('Yes','bztsw').'</option>';
                                        ?>
                                    </select>
                                </td>
                            </tr>                    

                            <tr class="only_tooltip">
                                <th scope="row"><?php echo __('Action to continue','bztsw'); ?></th>
                                <td>
                                    <select id="bztsw_stepAction" name="bztsw_stepAction" placeholder="Select an action">
                                        <?php
                                        $opt1 = '';
                                        $opt2 = '';
                                        $opt3 = '';
                                        $opt4 = '';
                                        if ($dataSteps && $dataSteps->bztsw_stepAction == 'click') {
                                            $opt1 = 'selected';
                                        } else {
                                            $opt2 = 'selected';
                                        }
                                        echo '<option value="click" ' . $opt1 . '>'.__('Click','bztsw').'</option>';
                                        echo '<option value="delay" ' . $opt2 . '>'.__('Duration','bztsw').'</option>';
                                        ?>
                                    </select>
                                </td>
                            </tr>

                            <tr class="only_dialog">
                                <th scope="row"><?php echo __('Continue button - Text','bztsw'); ?></th>
                                <td>
                                    <input id="bztsw_stepConbtn" type="text" name="bztsw_stepConbtn" placeholder="Continue button label" value="<?php
                                    if ($dataSteps) {
                                        echo $dataSteps->bztsw_stepConbtn;
                                    } else {
                                        echo __('Continue','bztsw');
                                    }
                                    ?>">
                                </td>
                            </tr>
                            
                            <tr class="only_dialog">
                                <th scope="row"><?php echo __('Button "Stop" text','bztsw'); ?></th>
                                <td>
                                    <input id="bztsw_stepStopbtn" type="text" name="bztsw_stepStopbtn" placeholder="Stop button label" value="<?php
                                    if ($dataSteps) {
                                        echo $dataSteps->bztsw_stepStopbtn;
                                    }
                                    ?>">
                                </td>
                            </tr>
                            
                            <tr>
                                <th scope="row"><?php echo __('Duration','bztsw'); ?></th>
                                <td>
                                    <input id="bztsw_stepDly" type="number" name="bztsw_stepDly" step="0.1" placeholder="Duration" value="<?php
                                    if ($dataSteps) {
                                        echo $dataSteps->bztsw_stepDly;
                                    } else {
                                        echo '5';
                                    }
                                    ?>">
                                </td>
                            </tr>
                            
                            <tr class="only_tooltip">
                                <th scope="row"><?php echo __('Delay before showing tooltip','bztsw'); ?></th>
                                <td>
                                    <input id="bztsw_stepDlySrt" type="number" step="0.1" name="bztsw_stepDlySrt" placeholder="<?php echo __('Delay','bztsw'); ?>" value="<?php
                                    if ($dataSteps) {
                                        echo $dataSteps->bztsw_stepDlySrt;
                                    } else {
                                        echo '0';
                                    }
                                    ?>">
                                </td>
                            </tr>
                            
                            <tr>
                                <th scope="row"><?php echo __('Override Font/Color Settings','bztsw'); ?></th>
                                <td>
									<?php 
									    $chk = '';
                                        if ($dataSteps && $dataSteps->bztsw_overrideSettings == 1) {
                                            $chk = 'checked = checked';
                                        }
									?>
                                    <input id="bztsw_overrideSettings" type="checkbox" <?php echo $chk; ?> name="bztsw_overrideSettings" value="<?php
                                    if ($dataSteps) {
                                        echo $dataSteps->bztsw_overrideSettings;
                                    } else {
                                        echo '0';
                                    }
                                    ?>">
                                </td>
                            </tr>
                            
                            <tr class="tooltip_typography" style="display:none;">
								
								<th scope="row"><?php echo __('Tooltip Box - Typography','bztsw'); ?></th>
								
								<td>
									<div class="main_row">
									<div class="color1">
                                    <input id="bztsw_tooltip_bgcolor" class="colorpick" type="color" name="bztsw_tooltip_bgcolor" placeholder="Choose a color" value="<?php
                                    echo $dataSteps->bztsw_tooltip_bgcolor;
                                    ?>">
                                    <span>Box - bg color</span>
                                    </div>
                                    
                                    <div class="color1">
                                    <input id="bztsw_tooltip_boxRadius" type="number" name="bztsw_tooltip_boxRadius" placeholder="2" value="<?php
                                    echo $dataSteps->bztsw_tooltip_boxRadius;
                                    ?>">
                                    <span>Box - border radius</span>
                                    </div>
									
									<div class="color1">
                                    <select id="bztsw_tooltip_disaplyConti" name="bztsw_tooltip_disaplyConti">
                                        <?php
                                        $opt1 = '';
                                        $opt2 = '';
                                        if ($dataSteps && $dataSteps->bztsw_tooltip_disaplyConti) {
                                            $opt2 = 'selected=selected';
                                        } else {
                                            $opt1 = 'selected=selected';
                                        }
                                        echo '<option value="0" '.$opt1.'>Yes</option>';
                                        echo '<option value="1" '.$opt2.' >No</option>';
                                        ?>
                                    </select>
                                    <span>Enable continue button?</span>
                                    </div>
                                    
                                   <div class="color1">
                                    <select id="bztsw_tooltip_disaplyCancel" name="bztsw_tooltip_disaplyCancel">
                                        <?php
                                        $opt1 = '';
                                        $opt2 = '';
                                        if ($dataSteps && $dataSteps->bztsw_tooltip_disaplyCancel) {
                                            $opt2 = 'selected=selected';
                                        } else {
                                            $opt1 = 'selected=selected';
                                        }
                                        echo '<option value="0" '.$opt1.'>Yes</option>';
                                        echo '<option value="1" '.$opt2.' >No</option>';
                                        ?>
                                    </select>
                                    <span>Enable cancel button?</span>
                                    </div>   
                                </div>
                                    
                                 <div class="main_row">       
                                    <div class="color1">
                                    <input id="bztsw_tooltip_titleFont" class="googlefont" type="text" name="bztsw_tooltip_titleFont" placeholder="Arial" value="<?php
                                    echo $dataSteps->bztsw_tooltip_titleFont;
                                    ?>">
                                    <span>Title - font</span>
                                    </div>
                                    
                                     <div class="color1">
                                    <input id="bztsw_tooltip_titleSize" type="number" name="bztsw_tooltip_titleSize" placeholder="20" value="<?php
                                    echo $dataSteps->bztsw_tooltip_titleSize;
                                    ?>">
                                    <span>Title - font size</span>
                                    </div>

                                    <div class="color1">
                                    <input id="bztsw_tooltip_titlecolor" class="colorpick" type="color" name="bztsw_tooltip_titlecolor" placeholder="Choose a color" value="<?php
                                    echo $dataSteps->bztsw_tooltip_titlecolor;
                                    ?>">
                                    <span>Title - color</span>
                                    </div>
                                 </div>    
                                    
                                 <div class="main_row">
                                     <div class="color1">
                                    <input id="bztsw_tooltip_textFont" class="googlefont" type="text" name="bztsw_tooltip_textFont" placeholder="Arial" value="<?php
                                    echo $dataSteps->bztsw_tooltip_textFont;
                                    ?>">
                                    <span>Text - font</span>
                                    </div>
                                      
									<div class="color1">
                                    <input id="bztsw_tooltip_textSize" type="number" name="bztsw_tooltip_textSize" placeholder="14" value="<?php
                                    echo $dataSteps->bztsw_tooltip_textSize;
                                    ?>">
                                    <span>Text - font size</span>
                                    </div>
                                    
                                    <div class="color1">
                                    <input id="bztsw_tooltip_textcolor" class="colorpick" type="color" name="bztsw_tooltip_textcolor" placeholder="Choose a color" value="<?php
                                    echo $dataSteps->bztsw_tooltip_textcolor;
                                    ?>">
                                    <span>Text - color</span>
                                    </div>
                                  </div>  
                                    
                                 <div class="main_row">
                                     <div class="color1">
                                    <input id="bztsw_tooltip_btnFont" class="googlefont" type="text" name="bztsw_tooltip_btnFont" placeholder="Arial" value="<?php
                                    echo $dataSteps->bztsw_tooltip_btnFont;
                                    ?>">
                                    <span>Button1 - font</span>
                                    </div>
                                    
                                    <div class="color1">
                                    <input id="bztsw_tooltip_btnSize" type="number" name="bztsw_tooltip_btnSize" placeholder="14" value="<?php
                                    echo $dataSteps->bztsw_tooltip_btnSize;
                                    ?>">
                                    <span>Button1 - font size</span>
                                    </div>
                                    
                                    <div class="color1">
                                    <input id="bztsw_tooltip_btnBg" type="color" name="bztsw_tooltip_btnBg" placeholder="2" value="<?php
                                    echo $dataSteps->bztsw_tooltip_btnBg;
                                    ?>">
                                    <span>Button1 - bg color</span>
                                    </div>
                                    
                                    <div class="color1">
                                    <input id="bztsw_tooltip_btnColor" type="color" name="bztsw_tooltip_btnColor" placeholder="2" value="<?php
                                    echo $dataSteps->bztsw_tooltip_btnColor;
                                    ?>">
                                    <span>Button1 - text color</span>
                                    </div>
                                    
									<div class="color1">
                                    <input id="bztsw_tooltip_btnRadius" type="number" name="bztsw_tooltip_btnRadius" placeholder="2" value="<?php
                                    echo $dataSteps->bztsw_tooltip_btnRadius;
                                    ?>">
                                    <span>Button1 - border radius</span>
                                    </div>
                                    
                                    <div class="color1">
									<select id="bztsw_tooltip_btnTSize" name="bztsw_tooltip_btnTSize">
                                        <?php
                                        $opt1 = '';
                                        $opt2 = '';
                                        $opt3 = '';
                                        if ($dataSteps && $dataSteps->bztsw_tooltip_btnTSize == 'small') {
                                            $opt1 = 'selected=selected';
                                        } else if ($dataSteps && $dataSteps->bztsw_tooltip_btnTSize == 'medium') { 
                                            $opt2 = 'selected=selected';
                                        } else if ($dataSteps && $dataSteps->bztsw_tooltip_btnTSize == 'large') { 
											$opt3 = 'selected=selected';
										}
                                        echo '<option value="small" '.$opt1.'>Small</option>';
                                        echo '<option value="medium" '.$opt2.' >Medium</option>';
                                        echo '<option value="large" '.$opt3.' >Large</option>';
                                        ?>
                                    </select>
                                    <span>Button1 - size</span>
                                    </div>
                                    
                                </div>   
                                <div class="main_row">
                                     <div class="color1">
                                    <input id="bztsw_tooltip_stop_btnFont" class="googlefont" type="text" name="bztsw_tooltip_stop_btnFont" placeholder="Arial" value="<?php
                                    echo $dataSteps->bztsw_tooltip_stop_btnFont;
                                    ?>">
                                    <span>Button2 - font</span>
                                    </div>
                                    
                                    <div class="color1">
                                    <input id="bztsw_tooltip_stop_btnSize" type="number" name="bztsw_tooltip_stop_btnSize" placeholder="14" value="<?php
                                    echo $dataSteps->bztsw_tooltip_stop_btnSize;
                                    ?>">
                                    <span>Button2 - font size</span>
                                    </div>
                                    
                                    <div class="color1">
                                    <input id="bztsw_tooltip_stop_btnBg" type="color" name="bztsw_tooltip_stop_btnBg" placeholder="2" value="<?php
                                    echo $dataSteps->bztsw_tooltip_stop_btnBg;
                                    ?>">
                                    <span>Button2 - bg color</span>
                                    </div>
                                    
                                    <div class="color1">
                                    <input id="bztsw_tooltip_stop_btnColor" type="color" name="bztsw_tooltip_stop_btnColor" placeholder="2" value="<?php
                                    echo $dataSteps->bztsw_tooltip_stop_btnColor;
                                    ?>">
                                    <span>Button2 - text color</span>
                                    </div>
                                    
									<div class="color1">
                                    <input id="bztsw_tooltip_stop_btnRadius" type="number" name="bztsw_tooltip_stop_btnRadius" placeholder="2" value="<?php
                                    echo $dataSteps->bztsw_tooltip_stop_btnRadius;
                                    ?>">
                                    <span>Button2 - border radius</span>
                                    </div>
									<div class="color1">
									<select id="bztsw_tooltip_stop_btnTSize" name="bztsw_tooltip_stop_btnTSize">
                                        <?php
                                        $opt1 = '';
                                        $opt2 = '';
                                        $opt3 = '';
                                        if ($dataSteps && $dataSteps->bztsw_tooltip_stop_btnTSize == 'small') {
                                            $opt1 = 'selected=selected';
                                        } else if ($dataSteps && $dataSteps->bztsw_tooltip_stop_btnTSize == 'medium') { 
                                            $opt2 = 'selected=selected';
                                        } else if ($dataSteps && $dataSteps->bztsw_tooltip_stop_btnTSize == 'large') { 
											$opt3 = 'selected=selected';
										}
                                        echo '<option value="small" '.$opt1.'>Small</option>';
                                        echo '<option value="medium" '.$opt2.' >Medium</option>';
                                        echo '<option value="large" '.$opt3.' >Large</option>';
                                        ?>
                                    </select>
                                    <span>Button2 - size</span>
                                    </div>
                                    
                                </div>   
                                </td>
                            </tr>


                            <tr class="dialog_typography" style="display:none;">
								
								<th scope="row"><?php echo __('Dialog Box - Typography','bztsw'); ?></th>
								
								<td>
								<div class="main_row">
									<div class="color1">
                                    <input id="bztsw_dialog_bgcolor" class="colorpick" type="color" name="bztsw_dialog_bgcolor" placeholder="Choose a color" value="<?php
                                    echo $dataSteps->bztsw_dialog_bgcolor;
                                    ?>">
                                    <span>Box - bg color</span>
                                    </div>
                                    
                                    <div class="color1">
                                    <input id="bztsw_dialog_boxRadius" type="number" name="bztsw_dialog_boxRadius" placeholder="2" value="<?php
                                    echo $dataSteps->bztsw_dialog_boxRadius;
                                    ?>">
                                    <span>Box - border radius</span>
                                    </div>
									
									<div class="color1">
                                    <select id="bztsw_dialog_disaplyConti" name="bztsw_dialog_disaplyConti">
                                        <?php
                                        $opt1 = '';
                                        $opt2 = '';
                                        if ($dataSteps && $dataSteps->bztsw_dialog_disaplyConti) {
                                            $opt2 = 'selected=selected';
                                        } else {
                                            $opt1 = 'selected=selected';
                                        }
                                        echo '<option value="0" '.$opt1.'>Yes</option>';
                                        echo '<option value="1" '.$opt2.' >No</option>';
                                        ?>
                                    </select>
                                    <span>Enable continue button?</span>
                                    </div>
                                    
                                   <div class="color1">
                                    <select id="bztsw_dialog_disaplyCancel" name="bztsw_dialog_disaplyCancel">
                                        <?php
                                        $opt1 = '';
                                        $opt2 = '';
                                        if ($dataSteps && $dataSteps->bztsw_dialog_disaplyCancel) {
                                            $opt2 = 'selected=selected';
                                        } else {
                                            $opt1 = 'selected=selected';
                                        }
                                        echo '<option value="0" '.$opt1.'>Yes</option>';
                                        echo '<option value="1" '.$opt2.' >No</option>';
                                        ?>
                                    </select>
                                    <span>Enable cancel button?</span>
                                    </div>   
                                </div>
                                    
                                 <div class="main_row">       
                                    <div class="color1">
                                    <input id="bztsw_dialog_titleFont" class="googlefont" type="text" name="bztsw_dialog_titleFont" placeholder="Arial" value="<?php
                                    echo $dataSteps->bztsw_dialog_titleFont;
                                    ?>">
                                    <span>Title - font</span>
                                    </div>
                                    
                                     <div class="color1">
                                    <input id="bztsw_dialog_titleSize" type="number" name="bztsw_dialog_titleSize" placeholder="20" value="<?php
                                    echo $dataSteps->bztsw_dialog_titleSize;
                                    ?>">
                                    <span>Title - font size</span>
                                    </div>

                                    <div class="color1">
                                    <input id="bztsw_dialog_titlecolor" class="colorpick" type="color" name="bztsw_dialog_titlecolor" placeholder="Choose a color" value="<?php
                                    echo $dataSteps->bztsw_dialog_titlecolor;
                                    ?>">
                                    <span>Title - color</span>
                                    </div>
                                 </div>    
                                    
                                 <div class="main_row">
                                     <div class="color1">
                                    <input id="bztsw_dialog_textFont" class="googlefont" type="text" name="bztsw_dialog_textFont" placeholder="Arial" value="<?php
                                    echo $dataSteps->bztsw_dialog_textFont;
                                    ?>">
                                    <span>Text - font</span>
                                    </div>
                                      
									<div class="color1">
                                    <input id="bztsw_dialog_textSize" type="number" name="bztsw_dialog_textSize" placeholder="14" value="<?php
                                    echo $dataSteps->bztsw_dialog_textSize;
                                    ?>">
                                    <span>Text - font size</span>
                                    </div>
                                    
                                    <div class="color1">
                                    <input id="bztsw_dialog_textcolor" class="colorpick" type="color" name="bztsw_dialog_textcolor" placeholder="Choose a color" value="<?php
                                    echo $dataSteps->bztsw_dialog_textcolor;
                                    ?>">
                                    <span>Text - color</span>
                                    </div>
                                  </div>  
                                    
                                 <div class="main_row">
                                     <div class="color1">
                                    <input id="bztsw_dialog_btnFont" class="googlefont" type="text" name="bztsw_dialog_btnFont" placeholder="Arial" value="<?php
                                    echo $dataSteps->bztsw_dialog_btnFont;
                                    ?>">
                                    <span>Button1 - font</span>
                                    </div>
                                    
                                    <div class="color1">
                                    <input id="bztsw_dialog_btnSize" type="number" name="bztsw_dialog_btnSize" placeholder="14" value="<?php
                                    echo $dataSteps->bztsw_dialog_btnSize;
                                    ?>">
                                    <span>Button1 - font size</span>
                                    </div>
                                    
                                    <div class="color1">
                                    <input id="bztsw_dialog_btnBg" type="color" name="bztsw_dialog_btnBg" placeholder="2" value="<?php
                                    echo $dataSteps->bztsw_dialog_btnBg;
                                    ?>">
                                    <span>Button1 - bg color</span>
                                    </div>
                                    
                                    <div class="color1">
                                    <input id="bztsw_dialog_btnColor" type="color" name="bztsw_dialog_btnColor" placeholder="2" value="<?php
                                    echo $dataSteps->bztsw_dialog_btnColor;
                                    ?>">
                                    <span>Button1 - text color</span>
                                    </div>
                                    
									<div class="color1">
                                    <input id="bztsw_dialog_btnRadius" type="number" name="bztsw_dialog_btnRadius" placeholder="2" value="<?php
                                    echo $dataSteps->bztsw_dialog_btnRadius;
                                    ?>">
                                    <span>Button1 - border radius</span>
                                    </div>
                                    
                                    <div class="color1">
									<select id="bztsw_dialog_btnTSize" name="bztsw_dialog_btnTSize">
                                        <?php
                                        $opt1 = '';
                                        $opt2 = '';
                                        $opt3 = '';
                                        if ($dataSteps && $dataSteps->bztsw_dialog_btnTSize == 'small') {
                                            $opt1 = 'selected=selected';
                                        } else if ($dataSteps && $dataSteps->bztsw_dialog_btnTSize == 'medium') { 
                                            $opt2 = 'selected=selected';
                                        } else if ($dataSteps && $dataSteps->bztsw_dialog_btnTSize == 'large') { 
											$opt3 = 'selected=selected';
										}
                                        echo '<option value="small" '.$opt1.'>Small</option>';
                                        echo '<option value="medium" '.$opt2.' >Medium</option>';
                                        echo '<option value="large" '.$opt3.' >Large</option>';
                                        ?>
                                    </select>
                                    <span>Button1 - size</span>
                                    </div>
                                    
                                </div>   
                                <div class="main_row">
                                     <div class="color1">
                                    <input id="bztsw_dialog_stop_btnFont" class="googlefont" type="text" name="bztsw_dialog_stop_btnFont" placeholder="Arial" value="<?php
                                    echo $dataSteps->bztsw_dialog_stop_btnFont;
                                    ?>">
                                    <span>Button2 - font</span>
                                    </div>
                                    
                                    <div class="color1">
                                    <input id="bztsw_dialog_stop_btnSize" type="number" name="bztsw_dialog_stop_btnSize" placeholder="14" value="<?php
                                    echo $dataSteps->bztsw_dialog_stop_btnSize;
                                    ?>">
                                    <span>Button2 - font size</span>
                                    </div>
                                    
                                    <div class="color1">
                                    <input id="bztsw_dialog_stop_btnBg" type="color" name="bztsw_dialog_stop_btnBg" placeholder="2" value="<?php
                                    echo $dataSteps->bztsw_dialog_stop_btnBg;
                                    ?>">
                                    <span>Button2 - bg color</span>
                                    </div>
                                    
                                    <div class="color1">
                                    <input id="bztsw_dialog_stop_btnColor" type="color" name="bztsw_dialog_stop_btnColor" placeholder="2" value="<?php
                                    echo $dataSteps->bztsw_dialog_stop_btnColor;
                                    ?>">
                                    <span>Button2 - text color</span>
                                    </div>
                                    
									<div class="color1">
                                    <input id="bztsw_dialog_stop_btnRadius" type="number" name="bztsw_dialog_stop_btnRadius" placeholder="2" value="<?php
                                    echo $dataSteps->bztsw_dialog_stop_btnRadius;
                                    ?>">
                                    <span>Button2 - border radius</span>
                                    </div>
									<div class="color1">
									<select id="bztsw_dialog_stop_btnTSize" name="bztsw_dialog_stop_btnTSize">
                                        <?php
                                        $opt1 = '';
                                        $opt2 = '';
                                        $opt3 = '';
                                        if ($dataSteps && $dataSteps->bztsw_dialog_stop_btnTSize == 'small') {
                                            $opt1 = 'selected=selected';
                                        } else if ($dataSteps && $dataSteps->bztsw_dialog_stop_btnTSize == 'medium') { 
                                            $opt2 = 'selected=selected';
                                        } else if ($dataSteps && $dataSteps->bztsw_dialog_stop_btnTSize == 'large') { 
											$opt3 = 'selected=selected';
										}
                                        echo '<option value="small" '.$opt1.'>Small</option>';
                                        echo '<option value="medium" '.$opt2.' >Medium</option>';
                                        echo '<option value="large" '.$opt3.' >Large</option>';
                                        ?>
                                    </select>
                                    <span>Button2 - size</span>
                                    </div>
                                    
                                </div>   
                                </td>
                                
                            </tr>


                            <tr>
                                <th scope="row"></th>
                                <td>
                                    <input type="submit" value="Save" class="button-primary"/>
                                </td>
                            </tr>
                            
                        </tbody>
                    </table>
                </form>
                <?php //echo site_url();        ?>
            </div>
		 <script type="text/javascript">
				jQuery('.googlefont').fontselect();
		 </script>

<?php } 


    /**
    * Get Steps data
    */
    private function blitz_tour_wizard_getTours() {
        global $wpdb;
        if ( is_multisite() ) {
			switch_to_blog(1);
			$tableName = $wpdb->prefix . "bztsw_tours";
			$rows = $wpdb->get_results("SELECT * FROM $tableName");
			restore_current_blog();
		} else {
			$tableName = $wpdb->prefix . "bztsw_tours";
			$rows = $wpdb->get_results("SELECT * FROM $tableName");			
		}

        $data = array();
        foreach ($rows as $row) {
            $data[] = array('id' => $row->bztsw_id, 'title' => $row->bztsw_title, 'order' => $row->bztsw_steporder, 'page' => $row->bztsw_pageurl, 'onDashboard' => $row->bztsw_onDashboard);
        }
        return $data;
    }
    
    
    
    /**
    * Get specific Item datas
    */
    private function blitz_tour_wizard_getSteps($step_id) {
        global $wpdb;
        $tableName = $wpdb->prefix . "bztsw_steps";
        $rows = $wpdb->get_results("SELECT * FROM $tableName WHERE bztsw_id=$step_id LIMIT 1");
        return $rows[0];
    }
    
    


    /**
     * Get specific Step datas
     */
    private function blitz_tour_wizard_getTour($tour_id) {
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
