<?php
/**
 * Calendar body parts class
 *
 * @class  		evo_cal_body
 * @version		2.6.15
 * @package		EventON/Classes
 * @category	Class
 * @author 		AJDE
 */
class evo_cal_body{
	private $cal;
	private $args;

	public $redirect_no_login = false;

	// construct the calendar body 
		public function __construct(){
			$this->cal = EVO()->evo_generator;
			$this->rtl = (!empty($this->cal->evopt1['evo_rtl'])  && $this->cal->evopt1['evo_rtl']=='yes')? true: false;			
		}

	// Above the mail calendar header HTML content/
		public function cal_above_header($args){
			
			if($this->calendar_nonlogged()) return false;

			extract($args);

			// jump months section
			$jumper_content ='';


			if($jumper =='yes'){
				$focused_year = (int)$focused_year;

				$jumper_content.= "<div class='evo_j_container' style='display:".($exp_jumper=='yes'?'block':'none')."'>
						<div class='evo_j_months evo_j_dates' data-val='m'>
							<div class='legend evo_jumper_months'>";

					// months list
					$lang = (!empty($args['lang']))? $args['lang']: 'L1';
					$evo_lang_options = $this->cal->evopt2;
					$__months = eventon_get_oneL_months( !empty($evo_lang_options[$lang])? $evo_lang_options[$lang]:'');	
					$fullMonther = evo_get_long_month_names( !empty($evo_lang_options[$lang])? $evo_lang_options[$lang]:'' );	
								
					$count = 1;
					foreach($fullMonther as $m){
						$_current = ($focused_month_num == $count)? 'class="current set"':null;
						$monthNAME = eventon_return_timely_names_('month_num_to_name', $count ,'full',$lang);
						$jumper_content.= "<a data-val='{$count}' {$_current} title='". $monthNAME.	"' >{$monthNAME}</a>";
						$count ++;
					}

					// if jumper offset is set
						$__a='';
						$start_year = $focused_year-2+$jumper_offset;
						$number_of_years = apply_filters('eventon_jumper_years_count', (!empty($jumper_count)?$jumper_count:5));

						for($x=1; $x <= $number_of_years; $x++){
							$__a .= '<a'. ( $start_year == $focused_year?" class='current set'":null ).' data-val="'.$start_year.'">'.$start_year.'</a>';
							$start_year++;
						}


						$jumper_content.= "</div><div class='clear'></div></div>
						
						<div class='evo_j_years evo_j_dates' data-val='y'>
							<p class='legend'>".$__a."</p><div class='clear'></div>
						</div>
					</div>";
			}// end jump months

			// go to today or current month
				$gototoday_content = '';
				$gototoday_content .= "";

			// above calendar buttons
				$above_head = apply_filters('evo_cal_above_header_btn', 
					array(
						'evo-jumper-btn'=>eventon_get_custom_language($this->cal->evopt2, 'evcal_lang_jumpmonths','Jump Months'),
						'evo-gototoday-btn'=>eventon_get_custom_language($this->cal->evopt2, 'evcal_lang_gototoday','Current Month'),
					), $args
				);

				// update array based on whether jumper is active or not
					if($jumper!='yes'){
						unset($above_head['evo-jumper-btn']);
					}

				$above_heade_content = apply_filters('evo_cal_above_header_content', 
					array(
						'evo-jumper-btn'=>$jumper_content,
						'evo-gototoday-btn'=>$gototoday_content,
					), $args
				);

				ob_start();
				
				// above header tag type items
				if(count($above_head)>0){
					echo "<div class='evo_cal_above'>";
						foreach($above_head as $ff=>$v){
							if($ff=='evo-gototoday-btn'){
								echo "<span class='".$ff."' style='display:none' data-mo='{$focused_month_num}' data-yr='{$focused_year}' data-dy=''>".$v."</span>";
							}else{
								echo "<span class='".$ff."'>".$v."</span>";
							}							
						}
						do_action('evo_cal_above_header_btns_end', $args);

						// View switcher - Beta feature
						if( $view_switcher == 'yes' && $calendar_type != 'list'){
							echo "<div class='evo_cal_above_right evo_cal_view_switcher'>
							<span class='evo_vSW evoD ". ($calendar_type == 'default'?'focus':'') ."'>List</span>";

							do_action('evo_cal_view_switcher_end', $args);
							echo "</div>";
						}
						
					echo "</div>";
				}
					
				// content for evo_cal_above
				if(count($above_heade_content)>0){
					echo "<div class='evo_cal_above_content'>";
					foreach($above_heade_content as $cc){
						echo $cc;
					}
					echo "</div>";
				}

			return ob_get_clean();
		}


	// Calendar header content
		function get_calendar_header($arguments=''){

			if($this->calendar_nonlogged()) return false;
			
			// SHORTCODE
			// at this point shortcode arguments are processed
			$args = $this->cal->shortcode_args;

			// FUNCTION
			$defaults = array(
				'focused_month_num'=> $args['fixed_month'],
				'focused_year'=> $args['fixed_year'],
				'range_end'=>0,
				'send_unix'=>false,
				'header_title'=>'',
				'date_header'=>true,
				'_html_evcal_list'=>true,
				'_classes_evcal_list'=>'',
				'_classes_calendar'=>'',
				'sortbar'=>true,
				'_html_sort_section'=>true,				
				'external'=>false,
				'unique_classes'=>array(),
				'search_btn'=>true,
				'initial_ajax_loading_html' => false
			);

			$arguments = empty($arguments)? array(): $arguments;

			// $arguments contain focused month num and focused year values
			// that need to be merged with existing values
			$arg_y = array_merge($defaults, $args, $arguments);
			extract($arg_y);
			$this->args = $arg_y; //@+2.6.11

			// CONNECTION with action user addon
			do_action('eventon_cal_variable_action_au', $arg_y);	

			// if hidden sortbar
				if(!$sortbar) $arg_y['hide_so'] = 'yes';
			
			//BASE settings to pass to calendar		
				$eventcard_open = ($this->cal->is_eventcard_open)? 'eventcard="1"':null;	

			// calendar class names			
				$boxCal = (!empty($args['tiles']) && $args['tiles'] =='yes')?'boxy':null;
			
				$__cal_classes = array('ajde_evcal_calendar', $boxCal);
				
				if( $this->rtl)
					$__cal_classes[] = 'evortl';

				// Tile design

				if(!empty($args['tile_style']) && $args['tile_style'] !='0'){
					$__cal_classes[] = 'boxstyle'.$args['tile_style'];
				}else{
					if($boxCal == 'boxy') $__cal_classes[] = 'boxstyle0';
				}


				// Eventtop styles
				if( $boxCal != 'boxy'){
					if($args['eventtop_style'] == 1){ 
						$__cal_classes[] = 'cev';
					}
					if($args['eventtop_style'] == 2){ 
						$__cal_classes[] = 'sev';
						$__cal_classes[] = 'cev';
					}
					if($args['eventtop_style'] == 3){ 
						$__cal_classes[] = 'sev';
						$__cal_classes[] = 'cev';
						$__cal_classes[] = 'bub';
					}
				}


				// hiding end time
				if(!empty($args['hide_end_time']) && $args['hide_end_time'] == 'yes'){
					$__cal_classes[] = 'het';
				}

				// upcoming list
				if($this->cal->is_upcoming_list)	$__cal_classes[] = 'ul';

				// tile count
					if(!empty($args['tile_count']) && $args['tile_count'])
						$__cal_classes[] = 'box_'.$args['tile_count'];

			// plugin hook
			if(sizeof($unique_classes)>0) $__cal_classes = array_merge($unique_classes, $__cal_classes);
			$__cal_classes = apply_filters('eventon_cal_class', $__cal_classes);

			$_cal_classes_string = implode(' ', $__cal_classes).' '.$_classes_calendar;
			
			$lang = (!empty($args['lang']))? $args['lang']: 'L1';
			$cal_header_title = get_eventon_cal_title_month($focused_month_num, $focused_year, $lang);
					

			// random cal id
				$cal_id = (empty($cal_id))? rand(100,900): $cal_id;
				$cal_id = str_replace(' ', '-', $cal_id);
				$this->cal->cal_id = $cal_id;


			ob_start();
			// Calendar SHELL
			echo "<!-- EventON Calendar -->";
			echo "<div id='evcal_calendar_".$cal_id."' class='".$_cal_classes_string."' >";

				
				if(!$external){

					// layout changer
					echo $this->cal_parts_layout_changer($args);
						$sort_class = ($this->cal->evcal_hide_sort=='yes')?'evcal_nosort':null;
						
					// HTML 
						echo "<div id='evcal_head' class='calendar_header ".$sort_class."' >";


					// if the calendar arrows and headers are to show 
						if($date_header){
							$hide_arrows = (!empty($this->cal->evopt1['evcal_arrow_hide']) && $this->cal->evopt1['evcal_arrow_hide']=='yes' || (!empty($args['hide_arrows']) && $args['hide_arrows']=='yes') )?true:false;					
							
							echo  $this->cal_above_header($arg_y);	

							echo "<p id='evcal_cur' class='evo_month_title'> ".$cal_header_title."</p>";	
							// arrows
							if(!$hide_arrows) echo $this->cal_parts_arrows();

						}else{ // without the date header
							$arg_y['jumper'] = 'no';
							echo  $this->cal_above_header($arg_y);	

							if(!empty($header_title)) echo "<p class='evo_cal_other_header'>". $header_title ."</p>";
						}
						
					// (---) Hook for addon
						do_action('eventon_calendar_header_content',  $args);
					
						echo "<div class='clear'></div></div>";
					
									
					// SORT BAR
						$sortbar =($hide_so=='yes')? false:$sortbar;
						if($_html_sort_section) echo $this->cal->filtering->get_content($args, $sortbar);


					// Other ending
						$content = '';
						// (---) Hook for addon
						echo  do_action('eventon_below_sorts', $content, $args);

						// load bar for calendar
						echo "<div id='eventon_loadbar_section'><div id='eventon_loadbar'></div></div>";


					// ajax loading
						if($initial_ajax_loading_html){
							echo "<div class='evo_ajax_load_events'><span></span><span></span><span></span></div>";
						}

						// (---) Hook for addon
						echo  do_action('eventon_after_loadbar', $content, $args);
				}
		
			// RTL 
				$evcal_list_classes = array();
				$evcal_list_classes[] = 'eventon_events_list';
				if($arg_y['sep_month'] == 'yes') $evcal_list_classes[] = 'sep_months';
				if($this->rtl) $evcal_list_classes[] ='evortl';

				echo ($_html_evcal_list)? "<!-- Events List --><div id='evcal_list' class='". implode(' ', $evcal_list_classes).' '.$_classes_evcal_list."'>":null;

			return ob_get_clean();
		}

		// calendar parts
			function cal_parts_arrows($args=''){
				$opt = $this->cal->evopt1;
				return "<p class='evo_arrows". ((!empty($opt['evo_arrow_right']) && $opt['evo_arrow_right']=='yes')? ' right':'') ."'><span id='evcal_prev' class='evcal_arrows evcal_btn_prev' ><i></i></span><span id='evcal_next' class='evcal_arrows evcal_btn_next' ><i></i></span></p>";
			}

			// layout changing buttons
				function cal_parts_layout_changer($args){
					if($args['layout_changer']=='yes')
						return "<p class='evo_layout_changer'><i data-type='row' class='fa fa-reorder'></i><i data-type='tile' class='fa fa-th-large'></i></p>";
				}

		// calendar data set // DEP
			function get_cal_data($args){
				if(is_array($args)) extract($args);

				// ux_val altering
				if( $eventtop_style && $eventtop_style == 3) $ux_val = 3;

				return apply_filters('eventon_cal_jqdata', array(
					'cyear'		=>$focused_year,
					'cmonth'	=>$focused_month_num,
					'runajax'	=>'1',
					'evc_open'	=>((!empty($evc_open) && $evc_open=='yes')? '1':'0'),
					'cal_ver'	=>	EVO()->version,					
					'ev_cnt'	=>$event_count, // event count
					'show_limit'=>$show_limit,
					'tiles'		=>$tiles,
					'sort_by'	=>$sort_by,
					'filters_on'=>$this->cal->filters,
					'range_start'=>$range_start,
					'range_end'	=>$range_end,
					'send_unix'=>( ($send_unix)?'1':'0'),
					'ux_val'	=> $ux_val,
					'accord'	=>( (!empty($accord) && $accord== 'yes' )? '1': '0'),
					'rtl'		=> ($this->rtl)?'yes':'no',				
				), $this->cal->evopt1, $args);

			}

	// Independant components of the calendar body -- DEP 2.8
		public function calendar_shell_header($arg){

			if($this->calendar_nonlogged()) return false;

			$defaults = array(
				'sort_bar'=> true,
				'title'=>'none',
				'date_header'=>true,
				'month'=>'1',
				'year'=>2014,
				'date_range_start'=>0,
				'date_range_end'=>0,
				'send_unix'=>false,
				'external'=>false,
			);

			$args = array_merge($defaults, $arg);

			$date_range_start =($args['date_range_start']!=0)? $args['date_range_start']: '0';
			$date_range_end =($args['date_range_end']!=0)? $args['date_range_end']: '0';

			$content ='';

			$content .= $this->get_calendar_header(
				array(
					'focused_month_num'=>$args['month'], 
					'focused_year'=>$args['year'], 
					'sortbar'=>$args['sort_bar'], 
					'date_header'=>$args['date_header'],
					'range_start'=>$date_range_start, 
					'range_end'=>$date_range_end , 
					'send_unix'=>$args['send_unix'],
					'header_title'=>$args['title'],
					'external'=>$args['external'],
				)
			);

			return $content;
		}

	// Footer
		// @+2.8
		function get_calendar_footer(){
			return $this->calendar_shell_footer();
		}
		public function calendar_shell_footer(){

			if($this->calendar_nonlogged()) return false;
			
			ob_start();
			do_action('evo_cal_footer');

			$args = EVO()->calendar->shortcode_args;
			?>
			<div class='clear'></div>
			</div>
			<div class='clear'></div>
			<?php $this->print_evo_cal_data();?>	
			<?php
				if(!empty($args['ics']) && $args['ics']=='yes'){
					
					$link = admin_url('admin-ajax.php').'?action=eventon_export_events_ics&amp;nonce='. wp_create_nonce('eventon_download_events')."&s={$args['focus_start_date_range']}&e={$args['focus_end_date_range']}";

					echo '<a class="evcal_btn download_ics" href="'.$link.'" style="margin-top:10px"><em class="fa fa-calendar-plus-o" ></em> '. evo_lang('Download all events as ICS file').'</a>';
				}
			?>

			<?php do_action('evo_cal_after_footer', EVO()->evo_generator->shortcode_args);?>
			</div><!-- EventON End -->
			<?php

			return ob_get_clean();
		}

	// footer evocal data
	// @+ 2.6.10
		function print_evo_cal_data($data = array()){

			$SC = $this->cal->shortcode_args;

			// Other additions
			$SC['maps_load'] = $this->cal->google_maps_load? 'yes':'no';

			$SC['_cver'] = EVO()->version;

			$str = '';
			$str .= " data-sc='". json_encode( $SC )."'";

			$other_data = apply_filters('evo_cal_OD', array(
				'lang_no_events'=> $this->cal->lang_array['no_event']
			));
			$str .= " data-od='". json_encode( $other_data )."'";

			?>
		 	<div id='evcal_footer' class='evo_bottom' style='display:none'>
		 		<div class='evo_cal_data' <?php echo $str;?>></div>
		 		<div class='evo_cal_events' data-events=""></div>
		 	</div>
			<?php
		}

	// HTML to show when the user is not logged in and calendar is not set to display then
		function calendar_nonlogged(){
			$this->redirect_no_login = (!empty($this->cal->evopt1['evcal_only_loggedin'])  && $this->cal->evopt1['evcal_only_loggedin']=='yes')? true: false;

			//echo "<p>You need to login</p>";

			return false;
		}
	




}