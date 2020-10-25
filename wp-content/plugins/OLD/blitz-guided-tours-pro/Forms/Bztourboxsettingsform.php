<?php

namespace BZ_SGN_WZ\Forms;

class Bztourboxsettingsform {
	
	public function addTourboxform() {
	
        global $wpdb;
        
        if ( is_multisite() ) {
			switch_to_blog(1);
			$tableName = $wpdb->prefix . "bztsw_settings";
			$bzsettings = $wpdb->get_results("SELECT * FROM $tableName WHERE bztsw_id=1 LIMIT 1");
			restore_current_blog();
		} else {
			$tableName = $wpdb->prefix . "bztsw_settings";
			$bzsettings = $wpdb->get_results("SELECT * FROM $tableName WHERE bztsw_id=1 LIMIT 1");
		}
        $bzsettings = $bzsettings[0];
            
	?>
	      <div class="bztsw_main testSettings">
			  
                <h2><?php echo __('Tour Box Settings','bztsw'); ?></h2>
                <div id="bztsw_response"></div>
                <form id="form_settings" method="post" action="#" onsubmit="bztsw_saveSettings(this);
                                return false;">
                    <input id="bztsw_id" type="hidden" name="bztsw_id" value="1">
                    <table class="form-table">
                        <tbody>
							
							<!-- Tooltip box -->
                            <tr>
                                <th scope="row"><?php echo __('Tooltip Box - Typography','bztsw'); ?></th>
                                <td>
									
							   <div class="main_row">
									<div class="color1">
                                    <input id="bztsw_bgcolor" class="colorpick" type="color" name="bztsw_bgcolor" placeholder="Choose a color" value="<?php
                                    echo $bzsettings->bztsw_bgcolor;
                                    ?>">
                                    <span>Box - bg color</span>
                                    </div>
                                    
                                    <div class="color1">
                                    <input id="bztsw_boxRadius" type="number" name="bztsw_boxRadius" placeholder="2" value="<?php
                                    echo $bzsettings->bztsw_boxRadius;
                                    ?>">
                                    <span>Box - border radius</span>
                                    </div>
									
									<div class="color1">
                                    <select id="bztsw_disaplyConti" name="bztsw_disaplyConti">
                                        <?php
                                        $opt1 = '';
                                        $opt2 = '';
                                        if ($bzsettings && $bzsettings->bztsw_disaplyConti) {
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
                                    <select id="bztsw_disaplyCancel" name="bztsw_disaplyCancel">
                                        <?php
                                        $opt1 = '';
                                        $opt2 = '';
                                        if ($bzsettings && $bzsettings->bztsw_disaplyCancel) {
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
                                    <input id="bztsw_titleFont" class="googlefont" type="text" name="bztsw_titleFont" placeholder="Arial" value="<?php
                                    echo $bzsettings->bztsw_titleFont;
                                    ?>">
                                    <span>Title - font</span>
                                    </div>
                                    
                                     <div class="color1">
                                    <input id="bztsw_titleSize" type="number" name="bztsw_titleSize" placeholder="20" value="<?php
                                    echo $bzsettings->bztsw_titleSize;
                                    ?>">
                                    <span>Title - font size</span>
                                    </div>

                                    <div class="color1">
                                    <input id="bztsw_titlecolor" class="colorpick" type="color" name="bztsw_titlecolor" placeholder="Choose a color" value="<?php
                                    echo $bzsettings->bztsw_titlecolor;
                                    ?>">
                                    <span>Title - color</span>
                                    </div>
                                 </div>    
                                    
                                 <div class="main_row">
                                     <div class="color1">
                                    <input id="bztsw_textFont" class="googlefont" type="text" name="bztsw_textFont" placeholder="Arial" value="<?php
                                    echo $bzsettings->bztsw_textFont;
                                    ?>">
                                    <span>Text - font</span>
                                    </div>
                                      
									<div class="color1">
                                    <input id="bztsw_textSize" type="number" name="bztsw_textSize" placeholder="14" value="<?php
                                    echo $bzsettings->bztsw_textSize;
                                    ?>">
                                    <span>Text - font size</span>
                                    </div>
                                    
                                    <div class="color1">
                                    <input id="bztsw_textcolor" class="colorpick" type="color" name="bztsw_textcolor" placeholder="Choose a color" value="<?php
                                    echo $bzsettings->bztsw_textcolor;
                                    ?>">
                                    <span>Text - color</span>
                                    </div>
                                  </div>  
                                    
                                 <div class="main_row">
                                     <div class="color1">
                                    <input id="bztsw_btnFont" class="googlefont" type="text" name="bztsw_btnFont" placeholder="Arial" value="<?php
                                    echo $bzsettings->bztsw_btnFont;
                                    ?>">
                                    <span>Button1 - font</span>
                                    </div>
                                    
                                    <div class="color1">
                                    <input id="bztsw_btnSize" type="number" name="bztsw_btnSize" placeholder="14" value="<?php
                                    echo $bzsettings->bztsw_btnSize;
                                    ?>">
                                    <span>Button1 - font size</span>
                                    </div>
                                    
                                    <div class="color1">
                                    <input id="bztsw_btnBg" type="color" name="bztsw_btnBg" placeholder="2" value="<?php
                                    echo $bzsettings->bztsw_btnBg;
                                    ?>">
                                    <span>Button1 - bg color</span>
                                    </div>
                                    
                                    <div class="color1">
                                    <input id="bztsw_btnColor" type="color" name="bztsw_btnColor" placeholder="2" value="<?php
                                    echo $bzsettings->bztsw_btnColor;
                                    ?>">
                                    <span>Button1 - text color</span>
                                    </div>
                                    
									<div class="color1">
                                    <input id="bztsw_btnRadius" type="number" name="bztsw_btnRadius" placeholder="2" value="<?php
                                    echo $bzsettings->bztsw_btnRadius;
                                    ?>">
                                    <span>Button1 - border radius</span>
                                    </div>
                                    
                                    <div class="color1">
									<select id="bztsw_btnTSize" name="bztsw_btnTSize">
                                        <?php
                                        $opt1 = '';
                                        $opt2 = '';
                                        $opt3 = '';
                                        if ($bzsettings && $bzsettings->bztsw_btnTSize == 'small') {
                                            $opt1 = 'selected=selected';
                                        } else if ($bzsettings && $bzsettings->bztsw_btnTSize == 'medium') { 
                                            $opt2 = 'selected=selected';
                                        } else if ($bzsettings && $bzsettings->bztsw_btnTSize == 'large') { 
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
                                    <input id="bztsw_stop_btnFont" class="googlefont" type="text" name="bztsw_stop_btnFont" placeholder="Arial" value="<?php
                                    echo $bzsettings->bztsw_stop_btnFont;
                                    ?>">
                                    <span>Button2 - font</span>
                                    </div>
                                    
                                    <div class="color1">
                                    <input id="bztsw_stop_btnSize" type="number" name="bztsw_stop_btnSize" placeholder="14" value="<?php
                                    echo $bzsettings->bztsw_stop_btnSize;
                                    ?>">
                                    <span>Button2 - font size</span>
                                    </div>
                                    
                                    <div class="color1">
                                    <input id="bztsw_stop_btnBg" type="color" name="bztsw_stop_btnBg" placeholder="2" value="<?php
                                    echo $bzsettings->bztsw_stop_btnBg;
                                    ?>">
                                    <span>Button2 - bg color</span>
                                    </div>
                                    
                                    <div class="color1">
                                    <input id="bztsw_stop_btnColor" type="color" name="bztsw_stop_btnColor" placeholder="2" value="<?php
                                    echo $bzsettings->bztsw_stop_btnColor;
                                    ?>">
                                    <span>Button2 - text color</span>
                                    </div>
                                    
									<div class="color1">
                                    <input id="bztsw_stop_btnRadius" type="number" name="bztsw_stop_btnRadius" placeholder="2" value="<?php
                                    echo $bzsettings->bztsw_stop_btnRadius;
                                    ?>">
                                    <span>Button2 - border radius</span>
                                    </div>
									<div class="color1">
									<select id="bztsw_stop_btnTSize" name="bztsw_stop_btnTSize">
                                        <?php
                                        $opt1 = '';
                                        $opt2 = '';
                                        $opt3 = '';
                                        if ($bzsettings && $bzsettings->bztsw_stop_btnTSize == 'small') {
                                            $opt1 = 'selected=selected';
                                        } else if ($bzsettings && $bzsettings->bztsw_stop_btnTSize == 'medium') { 
                                            $opt2 = 'selected=selected';
                                        } else if ($bzsettings && $bzsettings->bztsw_stop_btnTSize == 'large') { 
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
                            
                            
                            <!-- Dialog Box -->
                           
                               <tr>
                                <th scope="row"><?php echo __('Dialog Box - Typography','bztsw'); ?></th>
                                <td>
									
							   <div class="main_row">	 
									<div class="color1">
                                    <input id="bztsw_dia_bgcolor" class="colorpick" type="color" name="bztsw_dia_bgcolor" placeholder="Choose a color" value="<?php
                                    echo $bzsettings->bztsw_dia_bgcolor;
                                    ?>">
                                    <span>Box - bg color</span>
                                    </div>
                                    
                                    <div class="color1">
                                    <input id="bztsw_dia_boxRadius" type="number" name="bztsw_dia_boxRadius" placeholder="2" value="<?php
                                    echo $bzsettings->bztsw_dia_boxRadius;
                                    ?>">
                                    <span>Box - border radius</span>
                                    </div>
									
									<div class="color1">
                                    <select id="bztsw_dia_disaplyConti" name="bztsw_dia_disaplyConti">
                                        <?php
                                        $opt1 = '';
                                        $opt2 = '';
                                        if ($bzsettings && $bzsettings->bztsw_dia_disaplyConti) {
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
                                    <select id="bztsw_dia_disaplyCancel" name="bztsw_dia_disaplyCancel">
                                        <?php
                                        $opt1 = '';
                                        $opt2 = '';
                                        if ($bzsettings && $bzsettings->bztsw_dia_disaplyCancel) {
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
                                    <input id="bztsw_dia_titleFont" class="googlefont" type="text" name="bztsw_dia_titleFont" placeholder="Arial" value="<?php
                                    echo $bzsettings->bztsw_dia_titleFont;
                                    ?>">
                                    <span>Title - font</span>
                                    </div>
                                    
                                     <div class="color1">
                                    <input id="bztsw_dia_titleSize" type="number" name="bztsw_dia_titleSize" placeholder="20" value="<?php
                                    echo $bzsettings->bztsw_dia_titleSize;
                                    ?>">
                                    <span>Title - font size</span>
                                    </div>
                                    
                                    <div class="color1">
                                    <input id="bztsw_dia_titlecolor" class="colorpick" type="color" name="bztsw_dia_titlecolor" placeholder="Choose a color" value="<?php
                                    echo $bzsettings->bztsw_dia_titlecolor;
                                    ?>">
                                    <span>Title - color</span>
                                    </div>
                                  </div>
                                    
                                   <div class="main_row"> 	 
                                    <div class="color1">
                                    <input id="bztsw_dia_textFont" class="googlefont" type="text" name="bztsw_dia_textFont" placeholder="Arial" value="<?php
                                    echo $bzsettings->bztsw_dia_textFont;
                                    ?>">
                                    <span>Text - font</span>
                                    </div>
                                    
                                    <div class="color1">
                                    <input id="bztsw_dia_textSize" type="number" name="bztsw_dia_textSize" placeholder="14" value="<?php
                                    echo $bzsettings->bztsw_dia_textSize;
                                    ?>">
                                    <span>Text - font size</span>
                                    </div>

                                    <div class="color1">
                                    <input id="bztsw_dia_textcolor" class="colorpick" type="color" name="bztsw_dia_textcolor" placeholder="Choose a color" value="<?php
                                    echo $bzsettings->bztsw_dia_textcolor;
                                    ?>">
                                    <span>Text - color</span>
                                    </div> 
                                 </div>
                                 
                                 <!-- button1 -->
                                  <div class="main_row"> 
									<div class="color1">
                                    <input id="bztsw_dia_btnFont" class="googlefont" type="text" name="bztsw_dia_btnFont" placeholder="Arial" value="<?php
                                    echo $bzsettings->bztsw_dia_btnFont;
                                    ?>">
                                    <span>Button1 - font</span>
                                    </div>
                                    
                                    <div class="color1">
                                    <input id="bztsw_dia_btnSize" type="number" name="bztsw_dia_btnSize" placeholder="14" value="<?php
                                    echo $bzsettings->bztsw_dia_btnSize;
                                    ?>">
                                    <span>Button1 - font size</span>
                                    </div>
                                    
									<div class="color1">
                                    <input id="bztsw_dia_btnBg" type="color" name="bztsw_dia_btnBg" placeholder="2" value="<?php
                                    echo $bzsettings->bztsw_dia_btnBg;
                                    ?>">
                                    <span>Button1 - bg color</span>
                                    </div>
                                    
									<div class="color1">
                                    <input id="bztsw_dia_btnColor" type="color" name="bztsw_dia_btnColor" placeholder="2" value="<?php
                                    echo $bzsettings->bztsw_dia_btnColor;
                                    ?>">
                                    <span>Button1 - text color</span>
                                    </div>
                                    
                                    <div class="color1">
                                    <input id="bztsw_dia_btnRadius" type="number" name="bztsw_dia_btnRadius" placeholder="2" value="<?php
                                    echo $bzsettings->bztsw_dia_btnRadius;
                                    ?>">
                                    <span>Button1 - border radius</span>
                                    </div>
                                    
                                    <div class="color1">
                                    <select id="bztsw_dia_btnTSize" name="bztsw_dia_btnTSize">
                                        <?php
                                        $opt1 = '';
                                        $opt2 = '';
                                        $opt3 = '';
                                        if ($bzsettings && $bzsettings->bztsw_dia_btnTSize == 'small') {
                                            $opt1 = 'selected=selected';
                                        } else if ($bzsettings && $bzsettings->bztsw_dia_btnTSize == 'medium') { 
                                            $opt2 = 'selected=selected';
                                        } else if ($bzsettings && $bzsettings->bztsw_dia_btnTSize == 'large') { 
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
                                 <!-- /button1 --> 
                                 
                                 <!-- button2 -->
                                  <div class="main_row"> 
									     <div class="color1">
                                    <input id="bztsw_dia_stop_btnFont" class="googlefont" type="text" name="bztsw_dia_stop_btnFont" placeholder="Arial" value="<?php
                                    echo $bzsettings->bztsw_dia_stop_btnFont;
                                    ?>">
                                    <span>Button2 - font</span>
                                    </div>
                                    
                                    <div class="color1">
                                    <input id="bztsw_dia_stop_btnSize" type="number" name="bztsw_dia_stop_btnSize" placeholder="14" value="<?php
                                    echo $bzsettings->bztsw_dia_stop_btnSize;
                                    ?>">
                                    <span>Button2 - font size</span>
                                    </div>
                                    
									<div class="color1">
                                    <input id="bztsw_dia_stop_btnBg" type="color" name="bztsw_dia_stop_btnBg" placeholder="2" value="<?php
                                    echo $bzsettings->bztsw_dia_stop_btnBg;
                                    ?>">
                                    <span>Button2 - bg color</span>
                                    </div>
                                    
									<div class="color1">
                                    <input id="bztsw_dia_stop_btnColor" type="color" name="bztsw_dia_stop_btnColor" placeholder="2" value="<?php
                                    echo $bzsettings->bztsw_dia_stop_btnColor;
                                    ?>">
                                    <span>Button2 - text color</span>
                                    </div>
                                    
                                    <div class="color1">
                                    <input id="bztsw_dia_stop_btnRadius" type="number" name="bztsw_dia_stop_btnRadius" placeholder="2" value="<?php
                                    echo $bzsettings->bztsw_dia_stop_btnRadius;
                                    ?>">
                                    <span>Button2 - border radius</span>
                                    </div>
                                    
                                    <div class="color1">
                                    <select id="bztsw_dia_stop_btnTSize" name="bztsw_dia_stop_btnTSize">
                                        <?php
                                        $opt1 = '';
                                        $opt2 = '';
                                        $opt3 = '';
                                        if ($bzsettings && $bzsettings->bztsw_dia_stop_btnTSize == 'small') {
                                            $opt1 = 'selected=selected';
                                        } else if ($bzsettings && $bzsettings->bztsw_dia_stop_btnTSize == 'medium') { 
                                            $opt2 = 'selected=selected';
                                        } else if ($bzsettings && $bzsettings->bztsw_dia_stop_btnTSize == 'large') { 
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
                                  <!-- /button2 -->
                                    
                                    </td>
                            </tr>     
                            	
                             <!-- text box settings -->
                             
							<tr>
                                <th scope="row"><?php echo __('Text Box - Typography','bztsw'); ?></th>
                                <td>
									
								 <div class="main_row"> 	
									<div class="color1">
                                    <select id="bztsw_text_disaplyConti" name="bztsw_text_disaplyConti">
                                        <?php
                                        $opt1 = '';
                                        $opt2 = '';
                                        if ($bzsettings && $bzsettings->bztsw_text_disaplyConti) {
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
                                    <select id="bztsw_text_disaplyCancel" name="bztsw_text_disaplyCancel">
                                        <?php
                                        $opt1 = '';
                                        $opt2 = '';
                                        if ($bzsettings && $bzsettings->bztsw_text_disaplyCancel) {
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
                                    <input id="bztsw_text_titleFont" class="googlefont" type="text" name="bztsw_text_titleFont" placeholder="Arial" value="<?php
                                    echo $bzsettings->bztsw_text_titleFont;
                                    ?>">
                                    <span>Title - font</span>
                                    </div>
                                    
                                     <div class="color1">
                                    <input id="bztsw_text_titleSize" type="number" name="bztsw_text_titleSize" placeholder="20" value="<?php
                                    echo $bzsettings->bztsw_text_titleSize;
                                    ?>">
                                    <span>Title - font size( Tooltip )</span>
                                    </div>
                                    
                                    <div class="color1">
                                    <input id="bztsw_text_titlecolor" class="colorpick" type="color" name="bztsw_text_titlecolor" placeholder="Choose a color" value="<?php
                                    echo $bzsettings->bztsw_text_titlecolor;
                                    ?>">
                                    <span>Title - color</span>
                                    </div> 
                                 </div> 
                                 <div class="main_row">    
                                    <div class="color1">
                                    <input id="bztsw_text_textFont" class="googlefont" type="text" name="bztsw_text_textFont" placeholder="Arial" value="<?php
                                    echo $bzsettings->bztsw_text_textFont;
                                    ?>">
                                    <span>Text - font</span>
                                    </div>
                                    
                                    <div class="color1">
                                    <input id="bztsw_text_textSize" type="number" name="bztsw_text_textSize" placeholder="14" value="<?php
                                    echo $bzsettings->bztsw_text_textSize;
                                    ?>">
                                    <span>Text - font size</span>
                                    </div>
                                    
                                    <div class="color1">
                                    <input id="bztsw_text_textcolor" class="colorpick" type="color" name="bztsw_text_textcolor" placeholder="Choose a color" value="<?php
                                    echo $bzsettings->bztsw_text_textcolor;
                                    ?>">
                                    <span>Text - color</span>
                                    </div>
                                  </div> 
                                   
                                   <div class="main_row"> 
									    <div class="color1">
                                    <input id="bztsw_text_btnFont" class="googlefont" type="text" name="bztsw_text_btnFont" placeholder="Arial" value="<?php
                                    echo $bzsettings->bztsw_text_btnFont;
                                    ?>">
                                    <span>Button - font</span>
                                    </div>
                                    
                                    <div class="color1">
                                    <input id="bztsw_text_btnSize" type="number" name="bztsw_text_btnSize" placeholder="14" value="<?php
                                    echo $bzsettings->bztsw_text_btnSize;
                                    ?>">
                                    <span>Button - font size</span>
                                    </div>
                                                          
									<div class="color1">
                                    <input id="bztsw_text_btnBg" type="color" name="bztsw_text_btnBg" placeholder="2" value="<?php
                                    echo $bzsettings->bztsw_text_btnBg;
                                    ?>">
                                    <span>Button - bg color</span>
                                    </div>
                                    
									<div class="color1">
                                    <input id="bztsw_text_btnColor" type="color" name="bztsw_text_btnColor" placeholder="2" value="<?php
                                    echo $bzsettings->bztsw_text_btnColor;
                                    ?>">
                                    <span>Button - text color</span>
                                    </div>
                                    
                                    <div class="color1">
                                    <input id="bztsw_text_btnRadius" type="number" name="bztsw_text_btnRadius" placeholder="2" value="<?php
                                    echo $bzsettings->bztsw_text_btnRadius;
                                    ?>">
                                    <span>Button - border radius</span>
                                    </div>
                                 </div> 
                                   
                                    </td>
                            </tr>     
                            
                             
                            <tr>
                                <th scope="row"></th>
                                <td>
                                    <input type="submit" value="<?php echo __('Save','bztsw'); ?>" class="button-primary"/>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                    <script>

                    </script>
                </form>
            </div>
		 <script type="text/javascript">
				jQuery('.googlefont').fontselect();
		 </script>
<?php } 

}
