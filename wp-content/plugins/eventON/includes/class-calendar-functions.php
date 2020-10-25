<?php
/**
 * Calendar functions
 * @version 2.5.5
 * DEPRECATING
 */

class evo_fnc{

// construct
	public function __construct(){
		$this->options_1 = get_option('evcal_options_evcal_1');
	}
    function get_field_login_message(){
        return EVO()->calendar->helper->get_field_login_message();
    }
    function time_since($old_time, $new_time){
        return EVO()->calendar->helper->time_since($old_time, $new_time);
    }
    function htmlspecialchars_decode($D){
        return EVO()->calendar->helper->htmlspecialchars_decode($D);
    }
}