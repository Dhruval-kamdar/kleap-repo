<?php

// Call To Action (Event) - Checkbox
if ( ! function_exists( 'naeafe_pro_call_to_action_render' ) ) {
  function naeafe_pro_call_to_action_render() {
    $options = get_option( 'eafe_prow_settings' );
    ?>
    <label class="switch">
      <input type='checkbox' name='eafe_prow_settings[naeafe_pro_call_to_action]' id='naeafe_pro_call_to_action-id' <?php checked( isset($options['naeafe_pro_call_to_action']), 1 ); ?> value='1' />
      <span class="slider round"></span>
    </label>
    <?php
  }
}

// Events List (Event) - Checkbox
if ( ! function_exists( 'naeafe_pro_events_list_render' ) ) {
  function naeafe_pro_events_list_render() {
    $options = get_option( 'eafe_prow_settings' );
    ?>
    <label class="switch">
      <input type='checkbox' name='eafe_prow_settings[naeafe_pro_events_list]' id='naeafe_pro_events_list-id' <?php checked( isset($options['naeafe_pro_events_list']), 1 ); ?> value='1' />
      <span class="slider round"></span>
    </label>
    <?php
  }
}

// Events Attendees (Event) - Checkbox
if ( ! function_exists( 'naeafe_pro_events_attendees_render' ) ) {
  function naeafe_pro_events_attendees_render() {
    $options = get_option( 'eafe_prow_settings' );
    ?>
    <label class="switch">
      <input type='checkbox' name='eafe_prow_settings[naeafe_pro_events_attendees]' id='naeafe_pro_events_attendees-id' <?php checked( isset($options['naeafe_pro_events_attendees']), 1 ); ?> value='1' />
      <span class="slider round"></span>
    </label>
    <?php
  }
}

// Events Ticket Selector (Event) - Checkbox
if ( ! function_exists( 'naeafe_pro_events_ticket_selector_render' ) ) {
  function naeafe_pro_events_ticket_selector_render() {
    $options = get_option( 'eafe_prow_settings' );
    ?>
    <label class="switch">
      <input type='checkbox' name='eafe_prow_settings[naeafe_pro_events_ticket_selector]' id='naeafe_pro_events_ticket_selector-id' <?php checked( isset($options['naeafe_pro_events_ticket_selector']), 1 ); ?> value='1' />
      <span class="slider round"></span>
    </label>
    <?php
  }
}

// Events Calendar (Event) - Checkbox
if ( ! function_exists( 'naeafe_pro_events_calendar_render' ) ) {
  function naeafe_pro_events_calendar_render() {
    $options = get_option( 'eafe_prow_settings' );
    ?>
    <label class="switch">
      <input type='checkbox' name='eafe_prow_settings[naeafe_pro_events_calendar]' id='naeafe_pro_events_calendar-id' <?php checked( isset($options['naeafe_pro_events_calendar']), 1 ); ?> value='1' />
      <span class="slider round"></span>
    </label>
    <?php
  }
}

// Event Categories (Event) - Checkbox
if ( ! function_exists( 'naeafe_pro_event_categories_render' ) ) {
  function naeafe_pro_event_categories_render() {
    $options = get_option( 'eafe_prow_settings' );
    ?>
    <label class="switch">
      <input type='checkbox' name='eafe_prow_settings[naeafe_pro_event_categories]' id='naeafe_pro_event_categories-id' <?php checked( isset($options['naeafe_pro_event_categories']), 1 ); ?> value='1' />
      <span class="slider round"></span>
    </label>
    <?php
  }
}

// Event Form (Event) - Checkbox
if ( ! function_exists( 'naeafe_pro_event_form_render' ) ) {
  function naeafe_pro_event_form_render() {
    $options = get_option( 'eafe_prow_settings' );
    ?>
    <label class="switch">
      <input type='checkbox' name='eafe_prow_settings[naeafe_pro_event_form]' id='naeafe_pro_event_form-id' <?php checked( isset($options['naeafe_pro_event_form']), 1 ); ?> value='1' />
      <span class="slider round"></span>
    </label>
    <?php
  }
}

// Events Group (Event) - Checkbox
if ( ! function_exists( 'naeafe_pro_events_group_render' ) ) {
  function naeafe_pro_events_group_render() {
    $options = get_option( 'eafe_prow_settings' );
    ?>
    <label class="switch">
      <input type='checkbox' name='eafe_prow_settings[naeafe_pro_events_group]' id='naeafe_pro_events_group-id' <?php checked( isset($options['naeafe_pro_events_group']), 1 ); ?> value='1' />
      <span class="slider round"></span>
    </label>
    <?php
  }
}

// Event Locations (Event) - Checkbox
if ( ! function_exists( 'naeafe_pro_event_locations_render' ) ) {
  function naeafe_pro_event_locations_render() {
    $options = get_option( 'eafe_prow_settings' );
    ?>
    <label class="switch">
      <input type='checkbox' name='eafe_prow_settings[naeafe_pro_event_locations]' id='naeafe_pro_event_locations-id' <?php checked( isset($options['naeafe_pro_event_locations']), 1 ); ?> value='1' />
      <span class="slider round"></span>
    </label>
    <?php
  }
}

// Event Search (Event) - Checkbox
if ( ! function_exists( 'naeafe_pro_event_search_render' ) ) {
  function naeafe_pro_event_search_render() {
    $options = get_option( 'eafe_prow_settings' );
    ?>
    <label class="switch">
      <input type='checkbox' name='eafe_prow_settings[naeafe_pro_event_search]' id='naeafe_pro_event_search-id' <?php checked( isset($options['naeafe_pro_event_search']), 1 ); ?> value='1' />
      <span class="slider round"></span>
    </label>
    <?php
  }
}

// Event Search Listing (Event) - Checkbox
if ( ! function_exists( 'naeafe_pro_event_search_listing_render' ) ) {
  function naeafe_pro_event_search_listing_render() {
    $options = get_option( 'eafe_prow_settings' );
    ?>
    <label class="switch">
      <input type='checkbox' name='eafe_prow_settings[naeafe_pro_event_search_listing]' id='naeafe_pro_event_search_listing-id' <?php checked( isset($options['naeafe_pro_event_search_listing']), 1 ); ?> value='1' />
      <span class="slider round"></span>
    </label>
    <?php
  }
}

// Events Full Calendar (Event) - Checkbox
if ( ! function_exists( 'naeafe_pro_events_full_calendar_render' ) ) {
  function naeafe_pro_events_full_calendar_render() {
    $options = get_option( 'eafe_prow_settings' );
    ?>
    <label class="switch">
      <input type='checkbox' name='eafe_prow_settings[naeafe_pro_events_full_calendar]' id='naeafe_pro_events_full_calendar-id' <?php checked( isset($options['naeafe_pro_events_full_calendar']), 1 ); ?> value='1' />
      <span class="slider round"></span>
    </label>
    <?php
  }
}

// Info Box (Event) - Checkbox
if ( ! function_exists( 'naeafe_pro_info_box_render' ) ) {
  function naeafe_pro_info_box_render() {
    $options = get_option( 'eafe_prow_settings' );
    ?>
    <label class="switch">
      <input type='checkbox' name='eafe_prow_settings[naeafe_pro_info_box]' id='naeafe_pro_info_box-id' <?php checked( isset($options['naeafe_pro_info_box']), 1 ); ?> value='1' />
      <span class="slider round"></span>
    </label>
    <?php
  }
}

// Events Map (Event) - Checkbox
if ( ! function_exists( 'naeafe_pro_events_map_render' ) ) {
  function naeafe_pro_events_map_render() {
    $options = get_option( 'eafe_prow_settings' );
    ?>
    <label class="switch">
      <input type='checkbox' name='eafe_prow_settings[naeafe_pro_events_map]' id='naeafe_pro_events_map-id' <?php checked( isset($options['naeafe_pro_events_map']), 1 ); ?> value='1' />
      <span class="slider round"></span>
    </label>
    <?php
  }
}

// Events Subscribe (Event) - Checkbox
if ( ! function_exists( 'naeafe_pro_events_subscribe_render' ) ) {
  function naeafe_pro_events_subscribe_render() {
    $options = get_option( 'eafe_prow_settings' );
    ?>
    <label class="switch">
      <input type='checkbox' name='eafe_prow_settings[naeafe_pro_events_subscribe]' id='naeafe_pro_events_subscribe-id' <?php checked( isset($options['naeafe_pro_events_subscribe']), 1 ); ?> value='1' />
      <span class="slider round"></span>
    </label>
    <?php
  }
}

// Calendar Button (Event) - Checkbox
if ( ! function_exists( 'naeafe_pro_calendar_button_render' ) ) {
  function naeafe_pro_calendar_button_render() {
    $options = get_option( 'eafe_prow_settings' );
    ?>
    <label class="switch">
      <input type='checkbox' name='eafe_prow_settings[naeafe_pro_calendar_button]' id='naeafe_pro_calendar_button-id' <?php checked( isset($options['naeafe_pro_calendar_button']), 1 ); ?> value='1' />
      <span class="slider round"></span>
    </label>
    <?php
  }
}

// Chart (Pro) - Checkbox
if ( ! function_exists( 'naeafe_pro_chart_render' ) ) {
  function naeafe_pro_chart_render() {
    $options = get_option( 'eafe_prow_settings' );
    ?>
    <label class="switch">
      <input type='checkbox' name='eafe_prow_settings[naeafe_pro_chart]' id='naeafe_pro_chart-id' <?php checked( isset($options['naeafe_pro_chart']), 1 ); ?> value='1' />
      <span class="slider round"></span>
    </label>
    <?php
  }
}

// Pricing (Pro) - Checkbox
if ( ! function_exists( 'naeafe_pro_pricing_render' ) ) {
  function naeafe_pro_pricing_render() {
    $options = get_option( 'eafe_prow_settings' );
    ?>
    <label class="switch">
      <input type='checkbox' name='eafe_prow_settings[naeafe_pro_pricing]' id='naeafe_pro_pricing-id' <?php checked( isset($options['naeafe_pro_pricing']), 1 ); ?> value='1' />
      <span class="slider round"></span>
    </label>
    <?php
  }
}

// Venues (Pro) - Checkbox
if ( ! function_exists( 'naeafe_pro_venues_render' ) ) {
  function naeafe_pro_venues_render() {
    $options = get_option( 'eafe_prow_settings' );
    ?>
    <label class="switch">
      <input type='checkbox' name='eafe_prow_settings[naeafe_pro_venues]' id='naeafe_pro_venues-id' <?php checked( isset($options['naeafe_pro_venues']), 1 ); ?> value='1' />
      <span class="slider round"></span>
    </label>
    <?php
  }
}

// Conference (Pro) - Checkbox
if ( ! function_exists( 'naeafe_pro_conference_render' ) ) {
  function naeafe_pro_conference_render() {
    $options = get_option( 'eafe_prow_settings' );
    ?>
    <label class="switch">
      <input type='checkbox' name='eafe_prow_settings[naeafe_pro_conference]' id='naeafe_pro_conference-id' <?php checked( isset($options['naeafe_pro_conference']), 1 ); ?> value='1' />
      <span class="slider round"></span>
    </label>
    <?php
  }
}

// Countdown (Pro) - Checkbox
if ( ! function_exists( 'naeafe_pro_countdown_render' ) ) {
  function naeafe_pro_countdown_render() {
    $options = get_option( 'eafe_prow_settings' );
    ?>
    <label class="switch">
      <input type='checkbox' name='eafe_prow_settings[naeafe_pro_countdown]' id='naeafe_pro_countdown-id' <?php checked( isset($options['naeafe_pro_countdown']), 1 ); ?> value='1' />
      <span class="slider round"></span>
    </label>
    <?php
  }
}

// Schedule (Pro) - Checkbox
if ( ! function_exists( 'naeafe_pro_schedule_render' ) ) {
  function naeafe_pro_schedule_render() {
    $options = get_option( 'eafe_prow_settings' );
    ?>
    <label class="switch">
      <input type='checkbox' name='eafe_prow_settings[naeafe_pro_schedule]' id='naeafe_pro_schedule-id' <?php checked( isset($options['naeafe_pro_schedule']), 1 ); ?> value='1' />
      <span class="slider round"></span>
    </label>
    <?php
  }
}

// Upcoming Events (Pro) - Checkbox
if ( ! function_exists( 'naeafe_pro_upcoming_events_render' ) ) {
  function naeafe_pro_upcoming_events_render() {
    $options = get_option( 'eafe_prow_settings' );
    ?>
    <label class="switch">
      <input type='checkbox' name='eafe_prow_settings[naeafe_pro_upcoming_events]' id='naeafe_pro_upcoming_events-id' <?php checked( isset($options['naeafe_pro_upcoming_events']), 1 ); ?> value='1' />
      <span class="slider round"></span>
    </label>
    <?php
  }
}

// Event Listing (Pro) - Checkbox
if ( ! function_exists( 'naeafe_pro_event_listing_render' ) ) {
  function naeafe_pro_event_listing_render() {
    $options = get_option( 'eafe_prow_settings' );
    ?>
    <label class="switch">
      <input type='checkbox' name='eafe_prow_settings[naeafe_pro_event_listing]' id='naeafe_pro_event_listing-id' <?php checked( isset($options['naeafe_pro_event_listing']), 1 ); ?> value='1' />
      <span class="slider round"></span>
    </label>
    <?php
  }
}

// Event Slider (Pro) - Checkbox
if ( ! function_exists( 'naeafe_pro_event_slider_render' ) ) {
  function naeafe_pro_event_slider_render() {
    $options = get_option( 'eafe_prow_settings' );
    ?>
    <label class="switch">
      <input type='checkbox' name='eafe_prow_settings[naeafe_pro_event_slider]' id='naeafe_pro_event_slider-id' <?php checked( isset($options['naeafe_pro_event_slider']), 1 ); ?> value='1' />
      <span class="slider round"></span>
    </label>
    <?php
  }
}

// Sessions (Pro) - Checkbox
if ( ! function_exists( 'naeafe_pro_sessions_render' ) ) {
  function naeafe_pro_sessions_render() {
    $options = get_option( 'eafe_prow_settings' );
    ?>
    <label class="switch">
      <input type='checkbox' name='eafe_prow_settings[naeafe_pro_sessions]' id='naeafe_pro_sessions-id' <?php checked( isset($options['naeafe_pro_sessions']), 1 ); ?> value='1' />
      <span class="slider round"></span>
    </label>
    <?php
  }
}

// Event Category (Pro) - Checkbox
if ( ! function_exists( 'naeafe_pro_event_category_render' ) ) {
  function naeafe_pro_event_category_render() {
    $options = get_option( 'eafe_prow_settings' );
    ?>
    <label class="switch">
      <input type='checkbox' name='eafe_prow_settings[naeafe_pro_event_category]' id='naeafe_pro_event_category-id' <?php checked( isset($options['naeafe_pro_event_category']), 1 ); ?> value='1' />
      <span class="slider round"></span>
    </label>
    <?php
  }
}

// Organizer (Pro) - Checkbox
if ( ! function_exists( 'naeafe_pro_organizer_render' ) ) {
  function naeafe_pro_organizer_render() {
    $options = get_option( 'eafe_prow_settings' );
    ?>
    <label class="switch">
      <input type='checkbox' name='eafe_prow_settings[naeafe_pro_organizer]' id='naeafe_pro_organizer-id' <?php checked( isset($options['naeafe_pro_organizer']), 1 ); ?> value='1' />
      <span class="slider round"></span>
    </label>
    <?php
  }
}
