<?php
/**
 * Deprecated items for eventon
 */

// evo version 2.6.2
class evo_this_event extends EVO_Event{

	public function __construct($event_id){
		_deprecated_function( 'evo_this_event()', 'EventON 2.6.1' ,'EVO_Event()');
		_deprecated_function( 'separate_eventlist_to_months()', 'EventON 2.8' ,'_generate_events()');
		_deprecated_function( 'load_google_maps_api()', 'EventON 2.8.10' ,'shell->load_google_maps_api()');

		parent::__construct($event_id);

		// deprecated filters		
	}
}