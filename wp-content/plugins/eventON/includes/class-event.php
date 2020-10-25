<?php
/**
 * Event Class for one event
 * @version 2.6.13 
 * @updated 2.8.8
 */

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

class EVO_Event{
	public $event_id;
	public $ID;
	public $ri = 0;
	public $l = 'L1';
	private $edata = array();
	private $current_edata_field = '_edata';
	private $pmv ='';
	public $tax = array();
	private $DD;

	public $post_title ='';
	public $post_name = '';

	public function __construct($event_id, $event_pmv='', $ri = 0, $force_data_set = true, $post=false){
		$this->event_id = $this->ID = (int)$event_id;		
		if($force_data_set){			
			$this->set_event_data($event_pmv);
		} 		
		$this->localize_edata();
		$this->ri = $ri;

		// common date object
		$this->DD = new DateTime();
		$this->DD->setTimezone( EVO()->calendar->timezone0);

		// set event post to class if available
		if($post !== false){
			$this->author = $post->post_author;
			$this->post_date = $post->post_date;
			$this->content = $post->post_content;
			$this->excerpt = $post->post_excerpt;
			$this->post_name = $post->post_name;
			$this->post_title = $post->post_title;
		}

	}

	// event building @+2.6.10
		function set_lang($lang){ $this->l = $lang;}

	// permalinks
		// @~ 2.6.7
		function get_permalink($ri= '' , $l = ''){
			$event_link = get_the_permalink($this->event_id);

			$ri = (empty($ri) && $ri !== 0)? 
				( $this->ri == 0? 0: $this->ri): $ri;

			$l = (empty($l))? $this->l: $l;

			if($ri==0 && $l=='L1') return $event_link;

			$append = 'ri-'. $ri.'.l-'. $l;

			$permalink_last = substr($event_link, -1);
				$event_link = ($permalink_last == '/')? substr($event_link, 0,-1): $event_link;


			// processing
			$event_link = $this->_process_link( $event_link, $append, 'var');
			
			//$event_link = htmlentities($event_link, ENT_QUOTES | ENT_HTML5);

			return $event_link;
		}

		function get_ux_link(){
			$exlink_option = $this->get_prop('_evcal_exlink_option');	
		}

	// title
		function get_title(){
			if(!empty($this->post_title)) return $this->post_title;
			return get_the_title($this->ID);
		}
		function get_subtitle(){
			return apply_filters('evodata_subtitle', $this->get_prop('evcal_subtitle'), $this);
		}

		// @+ 2.6.12
		function edit_post_link(){
			return get_admin_url().'post.php?post='.$this->ID.'&action=edit';	
		}

		// @+ 2.8.4 
		function get_event_uniqid(){
			return $this->ID.'_'.$this->ri;
		}
	// time and date related
		// current and future
		function is_current_event( $cutoff='end', $current_time = ''){
			if(empty($current_time)){
				$current_time = EVO()->calendar->get_current_time();
			}

			$event_time = $this->get_event_time($cutoff);
			return $event_time > $current_time? true: false;
		}

		// if the event is live right now
		function is_event_live_now($CT=''){
			if(empty($CT)) $CT = EVO()->calendar->get_current_time();

			$ET = $this->get_start_end_times();
			extract($ET);

			return (  $CT >= $start && $CT <= $end) ? true: false;
		}

		function seconds_to_start_event($CT = ''){
			$start = $this->get_event_time();
			if(empty($CT)) $CT = EVO()->calendar->get_current_time();

			$t = $start - $CT;

			return ($t<=0) ? false: $t;
		}

		// @~ 2.8
		function is_past_event($cutoff = 'end'){			
			$is_current = $this->is_current_event($cutoff);
			return $is_current? false: true;
		}
		function is_all_day(){
			return $this->check_yn('evcal_allday');
		}
		function is_hide_endtime(){
			return $this->check_yn('evo_hide_endtime');
		}

		// @+2.8
		function is_event_in_date_range($S=0, $E=0, $start='' ,$end=''){
			if(empty($start) && empty($end) ){
				$event_times = $this->get_start_end_times();
				extract($event_times);
			}

			return EVO()->calendar->shell->is_in_range( $S, $E, $start, $end);
		}

	// DATE TIME
		// @+ 2.6.10
		function get_start_time(){
			return $this->get_event_time();
		}
		function get_end_time(){
			return $this->get_event_time('end');
		}

		// updated 2.6.7
		function get_event_time($type='start', $custom_ri=''){
			$output = '';
			if($this->is_repeating_event() ){	

				$repeat_interval = !empty($custom_ri)? (int)$custom_ri: (int)$this->ri;
				$intervals = $this->get_prop('repeat_intervals');

				if(sizeof($intervals)>0 && isset($intervals[$repeat_interval])){
					$output = ($type=='start')? 
						$intervals[$repeat_interval][0]:
						$intervals[$repeat_interval][1];
				}else{	$output = ($type=='start')? $this->get_prop('evcal_srow'):$this->get_prop('evcal_erow');	}				
			}else{	$output = ($type=='start')? $this->get_prop('evcal_srow'):$this->get_prop('evcal_erow');	}

			return $this->_year_month_long_filter( $output , $type);
		}

		// if its year long or month long event return correct start end unix
		// @+ 2.8
		function _year_month_long_filter($unix, $type='start'){
			if(empty($unix)) return $unix;
			if($this->is_year_long()){
				$this->DD->setTimestamp($unix);
				($type == 'start')? $this->DD->modify( 'first day of january this year') : 
					$this->DD->modify( 'last day of december this year');
				($type == 'start')? $this->DD->setTime(0,0,0): $this->DD->setTime(23,59,59);
				return $this->DD->format('U');
			}else{
				if($this->is_month_long()){
					$this->DD->setTimestamp($unix);
					($type == 'start') ? $this->DD->modify('first day of this month'):$this->DD->modify('last day of this month');
					($type == 'start')? $this->DD->setTime(0,0,0): $this->DD->setTime(23,59,59);
					return $this->DD->format('U');
				}else{
					return $unix;
				}
			}
		}

		function get_start_end_times($custom_ri=''){
			$start = $this->get_prop('evcal_srow');
			$end = $this->get_prop('evcal_erow')? $this->get_prop('evcal_erow'): $this->get_prop('evcal_srow');

			if($this->is_repeating_event() ){
				$repeat_interval = !empty($custom_ri)? (int)$custom_ri: (int)$this->ri;
				$intervals = $this->get_prop('repeat_intervals');

				if(sizeof($intervals)>0 ){
					$start = isset($intervals[$repeat_interval][0])? $intervals[$repeat_interval][0]: $intervals[0][0];
					$end = isset($intervals[$repeat_interval][1])? $intervals[$repeat_interval][1]:$intervals[0][1];
				}				
			}
			return array(
				'start'=> $this->_year_month_long_filter($start, 'start'),
				'end'=> $this->_year_month_long_filter($end, 'end')
			);
		}

		function get_formatted_smart_time($custom_ri=''){
			$wp_time_format = get_option('time_format');
			$wp_date_format = get_option('date_format');

			$times = $this->get_start_end_times($custom_ri);

			$start_ar = eventon_get_formatted_time($times['start']);
			$end_ar = eventon_get_formatted_time($times['end']);
			$_is_allday = $this->check_yn('evcal_allday');
			$hideend = $this->check_yn('evo_hide_endtime');

			$output = '';

			// reused
				$joint = $hideend?'':' - ';

			// same year
			if($start_ar['y']== $end_ar['y']){
				// same month
				if($start_ar['n']== $end_ar['n']){
					// same date
					if($start_ar['j']== $end_ar['j']){
						if($_is_allday){
							$output = $this->date($wp_date_format, $start_ar) .' ('.evo_lang_get('evcal_lang_allday','All Day').')';
						}else{
							$output = $this->date($wp_date_format.' '.$wp_time_format, $start_ar).$joint. 
								(!$hideend? $this->date($wp_time_format, $end_ar):'');
						}
					}else{// dif dates
						if($_is_allday){
							$output = $this->date($wp_date_format, $start_ar).' ('.evo_lang_get('evcal_lang_allday','All Day').')'.$joint.
								(!$hideend? $this->date($wp_date_format, $end_ar).' ('.evo_lang_get('evcal_lang_allday','All Day').')':'');
						}else{
							$output = $this->date($wp_date_format.' '.$wp_time_format, $start_ar).$joint.
								(!$hideend? $this->date($wp_date_format.' '.$wp_time_format, $end_ar):'');
						}
					}
				}else{// dif month
					if($_is_allday){
						$output = $this->date($wp_date_format, $start_ar).' ('.evo_lang_get('evcal_lang_allday','All Day').')'.$joint.
							(!$hideend? $this->date($wp_date_format, $end_ar).' ('.evo_lang_get('evcal_lang_allday','All Day').')':'');
					}else{// not all day
						$output = $this->date($wp_date_format.' '.$wp_time_format, $start_ar).$joint.
							(!$hideend? $this->date($wp_date_format.' '.$wp_time_format, $end_ar):'');
					}
				}
			}else{
				if($_is_allday){
					$output = $this->date($wp_date_format, $start_ar).' ('.evo_lang_get('evcal_lang_allday','All Day').')'.$joint.
						(!$hideend? $this->date($wp_date_format, $end_ar).' ('.evo_lang_get('evcal_lang_allday','All Day').')':'');
				}else{// not all day
					$output = $this->date($wp_date_format.' '.$wp_time_format, $start_ar). $joint .
						(!$hideend? $this->date($wp_date_format.' '.$wp_time_format, $end_ar):'');
				}
			}
			return $output;	
		}

		// return start and end time in array after adjusting time to UTC offset based on site timezone
		function get_utc_adjusted_times($start = '', $end='', $separate = true){
			if(empty($start) && empty($end)){
				$times = $this->get_start_end_times();
			}else{
				$times = array('start'=>$start, 'end'=>$end);
			}

			if(empty($times)) return false;

			$datetime = new evo_datetime();
			$utc_offset = $datetime->get_UTC_offset();

			$new_times = array('start'=> $times['start'], 'end'=> $times['end']);

			foreach($times as $key=>$unix){

				// if event is effected by daylight savings time
				if( $this->echeck_yn('day_light') ){
					$unix -= 3600;
				}

				if( !$separate){
					$new_times[$key] = $unix - $utc_offset;
					continue;
				}

				$new_unix = $unix - $utc_offset;
				$new_timeT = date("Ymd", $new_unix);
				$new_timeZ = date("Hi", $new_unix);

				$new_timeZ = (strlen($new_timeZ)<4)? '0'.$new_timeZ: $new_timeZ;

				$new_times[$key] = $new_timeT.'T'.$new_timeZ.'00Z';
			}

			return $new_times;
		}

		private function date($dateformat, $array){	
			return eventon_get_lang_formatted_timestr($dateformat, $array);
		}

	// Taxonomy @+2.8.1 @~2.8.5
		function get_tax_ids(){
			global $wpdb;

			if(count($this->tax)>0) return $this->tax;

			$OUT = array();

			$R = $wpdb->get_results( $wpdb->prepare(
				"SELECT term_taxonomy_id FROM {$wpdb->prefix}term_relationships WHERE object_id=%d", $this->ID
			));

			if($R && count($R)>0){
				foreach($R as $B){
					
					$Q1 = $wpdb->prepare(
						"SELECT t.term_id, t.taxonomy, t.description, tt.name
						FROM {$wpdb->prefix}term_taxonomy AS t
						INNER JOIN {$wpdb->prefix}terms AS tt ON (tt.term_id = t.term_id )
						WHERE t.term_taxonomy_id=%d", $B->term_taxonomy_id
					);
					$R1 = $wpdb->get_results( $Q1);

					if( count($R1) == 0) continue;

					foreach($R1 as $C){
						$O = $wpdb->prepare("SELECT op.option_value FROM {$wpdb->prefix}options AS op WHERE op.option_name ='evo_et_taxonomy_%d'", $C->term_id);
						$O1 = $wpdb->get_results( $O);

						if($O1 && count($O1)>0) $OUT[$C->taxonomy][$B->term_taxonomy_id] = unserialize( $O1[0]->option_value );

						$OUT[$C->taxonomy][$B->term_taxonomy_id]['description'] = $C->description;
						$OUT[$C->taxonomy][$B->term_taxonomy_id]['name'] = $C->name;
					}					
				}
			}

			//print_r($OUT);
			$this->tax = $OUT;
			return $OUT;
		}

	// GENERAL GET
		function is_year_long(){
			return $this->check_yn('evo_year_long');
		}
		function is_month_long(){
			if($this->is_year_long()) return false; // 
			return $this->check_yn('_evo_month_long');
		}
		function is_featured(){	 return apply_filters('evodata_featured', $this->check_yn('_featured') , $this);		}
		function is_completed(){ return apply_filters('evodata_completed', $this->check_yn('_completed') , $this);		}
		function is_cancelled(){ 
			$S = $this->get_event_status();
			return $S == 'cancelled'? true:false;
		}
		function get_event_status(){
			$S = apply_filters('evodata_event_status', $this->get_prop('_status'), $this);

			if( $this->check_yn('_cancel') ) return 'cancelled';
			return $S? $S : 'scheduled';
		}
		function get_event_status_l18n($S=''){
			$A = $this->get_status_array();

			if(empty($S)) $S = $this->get_event_status();
			return isset($A[ $S ]) ? $A[ $S ]: $S;
		}
		function get_event_status_lang($S=''){
			$A = $this->get_status_array('front');

			if(empty($S)) $S = $this->get_event_status();
			return isset($A[ $S ]) ? $A[ $S ]: $S;
		}
		function get_status_reason(){
			$S = $this->get_event_status();

			if($S == 'scheduled') return false;
			if($S == 'cancelled') $S = 'cancel';
			return apply_filters('evodata_event_status_reason', $this->get_prop('_'. $S . '_reason'), '_'. $S . '_reason', $this);
		}

		function get_status_array($end = 'back'){
			return EVO()->cal->get_status_array( $end);
		}

	// Virtual Event
		function is_virtual(){
			if(!$this->check_yn('_virtual') ) return false;

			$R = false;
			$vir_type = $this->virtual_type();	
			if( $this->get_prop('_vir_url')) $R = true;

			return $R;
		}
		function virtual_type(){
			return $this->get_prop('_virtual_type');
		}
		function virtual_url(){
			$url = $this->get_prop('_vir_url');
			if(!$url) return false;

			if( $this->check_yn('_vir_nohiding')) return $url;

			$event_link = get_the_permalink($this->event_id);
			$append = 'event_access';
			
			$event_link = $this->_process_link( $event_link, $append, 'var');			

			return $event_link;
		}
		function get_vir_url(){
			if(!$this->is_virtual()) return false;
			$url = $this->get_prop('_vir_url');
			if(!$url) return false;

			$VT = $this->get_prop('_virtual_type');
			
			if($VT == 'youtube_live'){
				$url = (strpos($url, '/') === false)? 'https://www.youtube.com/channel/'. $url .'/live': $url;
			}

			return $url;
		}
	
	// repeating events
		function is_repeating_event(){
			if(!$this->check_yn('evcal_repeat')) return false;
			if(empty($this->pmv['repeat_intervals'])) return false;

			$repeats = unserialize($this->pmv['repeat_intervals'][0]);
			if(!is_array($repeats)) return false;
			if(count($repeats)==1) return false;

			return true;
		}
		function get_repeats(){
			if(empty($this->pmv['repeat_intervals'])) return false;
			return unserialize($this->pmv['repeat_intervals'][0]);
		}
		function get_repeats_count(){
			if(!$this->check_yn('evcal_repeat')) return false;
			if(empty($this->pmv['repeat_intervals'])) return false;

			return count(unserialize($this->pmv['repeat_intervals'][0])) -1;
		}

		// next repeat instance that is current (not past)
		function get_next_current_repeat($current_ri_index, $check_by = 'start'){
			$repeats = $this->get_repeats();
			if(!$repeats) return false;

			date_default_timezone_set('UTC');	
			$current_time = current_time('timestamp');

			$return = false;
			
			foreach($repeats as $index=>$repeat){
				if($index<= $current_ri_index) continue;

				// check if start time of repeat is current
				if($check_by == 'start' && $repeat[0]>=  $current_time) $return = true;
				if($check_by != 'start' && $repeat[1]>=  $current_time) $return = true;

				if($return)	return array('ri'=>$index, 'times'=>$repeat);
			}
			return false;
		}

		function get_repeat_interval($key){
			$repeats = $this->get_repeats();
			if(!$repeats) return false;
				
			$all_repeats = count($repeats)-1;

			if($key == 'last'){
				return end($repeats);
			}

			if($key == 'first'){
				return $repeats[0];
			}

			foreach($repeats as $index=>$repeat){
				if($index< $key) continue;
				if($index == $key)	return $repeat;						
			}
			return false;
		}

	// EVENT DATA
		// @updated 2.9
		// localize edata for the event object to be used
		function localize_edata($data_field = ''){			
			$this->current_edata_field = !empty($data_field)? $data_field: '_edata';
			$edata = $this->get_prop( $this->current_edata_field  );
			$this->edata = ( !$edata)? array(): $edata;	
		}
		function get_all_edata(){
			return $this->edata;
		}
		function get_eprop($field){
			if(empty($this->edata[$field])) return false;
			if(!isset($this->edata[$field])) return false;
			return maybe_unserialize($this->edata[$field]);
		}
		function echeck_yn($field){
			if(empty($this->edata[$field])) return false;
			if($this->edata[$field]=='yes') return true;
			return false;
		}
		function set_eprop($field, $value, $update = true, $localize = false){
			$this->edata[$field] = $value;	
			if($update) update_post_meta($this->ID, $this->current_edata_field, $this->edata);
			if($localize)	$this->localize_edata();
		}
		function save_eprops($data_field = ''){
			if(!empty($data_field)) $this->current_edata_field = $data_field;
			update_post_meta($this->ID, $this->current_edata_field, $this->edata);
		}
		function delete_eprop($field, $update = false){
			if(empty($this->edata[$field])) return false;
			if(!isset($this->edata[$field])) return true;
			unset($this->edata[$field]);
			if($update) update_post_meta($this->ID, $this->current_edata_field, $this->edata);
		}
		function del_mul_eprop($array, $update_meta = true){
			if(!is_array($array)) return false;

			foreach($array as $f){
				$this->delete_eprop( $f );
			}

			if($update_meta) update_post_meta($this->ID, $this->current_edata_field, $this->edata);
		}


	// event post meta values
		private function set_event_data($pmv = ''){
			if(array_key_exists('EVO_props', $GLOBALS) ){
				global $EVO_props;
				if(isset($EVO_props[$this->event_id])){
					$this->pmv = $EVO_props[$this->event_id];
					return true;
				}				
			}

			// get event's post meta values and update global
			$this->pmv = (!empty($pmv))? $pmv : get_post_custom($this->event_id);
			$GLOBALS['EVO_props'][$this->event_id] = $this->pmv;
		}

		// update the local event data object with newly pulled values
		// @+2.6.13
		public function relocalize_event_data(){
			$this->pmv =  get_post_custom($this->event_id);
			$GLOBALS['EVO_props'][$this->event_id] = $this->pmv;
		}
		public function reglobalize_event_data_from_local(){
			$GLOBALS['EVO_props'][$this->event_id] = $this->pmv;
		}

		// pass event pmv value to private pmv and update globalized event PMV array 
		// @+2.6.11
		function globalize_event_pmv(){
			$GLOBALS['EVO_props'][$this->event_id] = $this->pmv;
		}

		function get_data(){ return $this->pmv;}
		function get_prop($field){
			if(empty($this->pmv[$field])) return false;
			if(!isset($this->pmv[$field][0])) return false;
			return maybe_unserialize($this->pmv[$field][0]);
		}
		// return null if the field is empty instead of false ver.2.8
		function get_prop_null($field){
			$F = $this->get_prop($field);
			return $F? $F: null; 
		}
		// return a sent value of the field is empty
		function get_prop_val($field, $val){
			$F = $this->get_prop($field);
			return $F? $F: $val; 
		}

		function set_prop($field, $value, $update = true, $update_obj = false){
			$this->pmv[$field][0] = $value;
	
			if($update) update_post_meta($this->ID, $field, $value);

			// update the global event data with new property
			if($update_obj)	$this->reglobalize_event_data_from_local();
		}

		function check_yn($field){
			if(empty($this->pmv[$field])) return false;
			if($this->pmv[$field][0]=='yes') return true;
			return false;
		}
		function del_prop($field){
			delete_post_meta($this->ID, $field);
		}
		// v2.9
		function del_mul_prop($A){
			if(!is_array($A)) return false;
			foreach($A as $f) $this->del_prop( $f );
		}
		function set_global(){
			$data = array(
				'id'=>$this->ID,
				'pmv'=>$this->pmv
			);
			$GLOBALS['EVO_Event'] = (object)$data;
		}
		// not initiated on load
		function get_event_post(){
			global $wpdb;

			$results = $wpdb->get_results("SELECT * FROM $wpdb->posts WHERE ID='{$this->event_id}'");
			
			if($results && count($results)>0){
				$results = $results[0];
				$this->author = $results->post_author;
				$this->post_date = $results->post_date;
				$this->content = $results->post_content;
				$this->excerpt = $results->post_excerpt;
				$this->post_name = $results->post_name;
				$this->post_title = $results->post_title; // @+ 2.6.10
			}
		}
		function get_start_unix(){	return (int)$this->get_prop('evcal_srow');	}
		function get_end_unix(){	return (int)$this->get_prop('evcal_erow');	}


	// LOCATION
		function is_hide_location_info(){
			//return EVO()->calendar->is_user_logged_in;
			$is_user_logged_in = EVO()->calendar->is_user_logged_in;

			$hide_location_info = false;
			$hide_location_info = ($this->check_yn('evo_access_control_location') && !$is_user_logged_in) ? true: false;

			$hide_location_info = (evo_settings_check_yn(EVO()->calendar->evopt1,'evo_hide_location') && !$is_user_logged_in)? true: $hide_location_info;
			return $hide_location_info;
		}
		function get_location_term_id($type='id'){ // @+2.8
			$location_terms = wp_get_post_terms($this->ID, 'event_location');
			if ( $location_terms && ! is_wp_error( $location_terms ) ){
				return ($type == 'id')? (int)$location_terms[0]->term_id: $location_terms[0];
			}
			return false;
		}
		public function get_location_data(){

			$location_term = apply_filters('evodata_location_term', $this->get_location_term_id('all'), $this);
			//$location_term = get_term( $location_term_id,'event_location' );

			if ( $location_term && ! is_wp_error( $location_term ) ){

				$output = array();

				$output['location_term_id'] = (int)$location_term->term_id;
				
				// check location term meta values on new and old
				$LocTermMeta = evo_get_term_meta( 'event_location', (int)$location_term->term_id, EVO()->calendar->tax_meta);
				
				// location name
					$output['name'] = stripslashes( $location_term->name );
					$output['location_name'] = stripslashes( $location_term->name );

				// URL
					$output['location_url'] = get_term_link($location_term,'event_location');

				// description
					$output['location_description'] = !empty($location_term->description)? $location_term->description:'';

				// meta values
				foreach(array(
					'location_address','location_lat','location_lon',
					'location_img_id'=>'evo_loc_img',
					'location_link'=>'evcal_location_link',
					'location_city','location_state','location_country',
					'location_link_target'=>'evcal_location_link_target',
					'location_getdir_latlng',
					'location_type'
				) as $I=>$key){	
					$K = is_integer($I)? $key: $I;				
					$output[$K] = (empty($LocTermMeta[$key]))? '': $LocTermMeta[$key];
				}			

				// latlng
				if(!empty($output['location_lat']) && !empty($output['location_lon'])){
					$output['location_latlng'] = $output['location_lat'].','.$output['location_lon'];
				}	

				// link target
				if(empty($output['location_link_target'])) $output['location_link_target'] = 'no';

				return $output;
				
			}else{
				return false;
			}
		}

	// Organizer
		function get_organizer_term_id($type='id'){ // @+2.8
			$O_terms = wp_get_post_terms($this->ID, 'event_organizer');
			if ( $O_terms && ! is_wp_error( $O_terms ) ){
				return ($type == 'id')? (int)$O_terms[0]->term_id: $O_terms[0];
			}
			return false;
		}
		function get_organizer_data(){
			$O_term = apply_filters('evodata_organizer_term', $this->get_organizer_term_id('all'), $this);
			if($O_term && !is_wp_error( $O_term)){
				$R = array();

				$org_term_meta = evo_get_term_meta( 'event_organizer', (int)$O_term->term_id, EVO()->calendar->tax_meta);
				
				$R['organizer'] = $O_term;
				$R['organizer_term'] = $O_term;
				$R['organizer_term_id'] = (int)$O_term->term_id;
				$R['organizer_name'] = $O_term->name;
				$R['organizer_description'] = $O_term->description;

				// meta values
				foreach(array(
					'organizer_img_id'=>'evo_org_img',
					'organizer_contact'=>'evcal_org_contact',
					'organizer_address'=>'evcal_org_address',
					'organizer_link'=>'evcal_org_exlink',
					'organizer_link_target'=>'_evocal_org_exlink_target',
				) as $I=>$key){	
					$K = is_integer($I)? $key: $I;				
					$R[$K] = (empty($org_term_meta[$key]))? '': $org_term_meta[$key];
				}

				return $R;
			}else{
				return false;
			}
		}

	// Custom Field data
		function get_custom_data($index){
			return apply_filters('evodata_custom_data', array(
				'value'=> $this->get_prop("_evcal_ec_f".$index."a1_cus"),
				'valueL'=> $this->get_prop("_evcal_ec_f".$index."a1_cusL"),
				'target'=> $this->get_prop("_evcal_ec_f".$index."_onw"),
			), $this, $index);
		}

	// supportive
		// process link
		function _process_link($event_link, $append, $var){
			if(strpos($event_link, '?')=== false){
				if( substr($event_link,-1) == '/') $event_link = substr($event_link,0,-1);
				$event_link .= "/".$var."/".$append;
			}else{
				$event_link .= "&".$var."=".$append;
			}
			return $event_link;
		}


}