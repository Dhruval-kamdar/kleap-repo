<?php

// Accommodation (Unique) - Checkbox
if ( ! function_exists( 'naeafe_unique_accommodation_render' ) ) {
  function naeafe_unique_accommodation_render() {
    $options = get_option( 'eafe_unqw_settings' );
    ?>
    <label class="switch">
      <input type='checkbox' name='eafe_unqw_settings[naeafe_unique_accommodation]' id='naeafe_unique_accommodation-id' <?php checked( isset($options['naeafe_unique_accommodation']), 1 ); ?> value='1' />
      <span class="slider round"></span>
    </label>
    <?php
  }
}

// Calendar Button (Unique) - Checkbox
if ( ! function_exists( 'naeafe_unique_calendar_button_render' ) ) {
  function naeafe_unique_calendar_button_render() {
    $options = get_option( 'eafe_unqw_settings' );
    ?>
    <label class="switch">
      <input type='checkbox' name='eafe_unqw_settings[naeafe_unique_calendar_button]' id='naeafe_unique_calendar_button-id' <?php checked( isset($options['naeafe_unique_calendar_button']), 1 ); ?> value='1' />
      <span class="slider round"></span>
    </label>
    <?php
  }
}

// Call Action (Unique) - Checkbox
if ( ! function_exists( 'naeafe_unique_call_action_render' ) ) {
  function naeafe_unique_call_action_render() {
    $options = get_option( 'eafe_unqw_settings' );
    ?>
    <label class="switch">
      <input type='checkbox' name='eafe_unqw_settings[naeafe_unique_call_action]' id='naeafe_unique_call_action-id' <?php checked( isset($options['naeafe_unique_call_action']), 1 ); ?> value='1' />
      <span class="slider round"></span>
    </label>
    <?php
  }
}

// Conference (Unique) - Checkbox
if ( ! function_exists( 'naeafe_unique_conference_render' ) ) {
  function naeafe_unique_conference_render() {
    $options = get_option( 'eafe_unqw_settings' );
    ?>
    <label class="switch">
      <input type='checkbox' name='eafe_unqw_settings[naeafe_unique_conference]' id='naeafe_unique_conference-id' <?php checked( isset($options['naeafe_unique_conference']), 1 ); ?> value='1' />
      <span class="slider round"></span>
    </label>
    <?php
  }
}

// Countdown (Unique) - Checkbox
if ( ! function_exists( 'naeafe_unique_countdown_render' ) ) {
  function naeafe_unique_countdown_render() {
    $options = get_option( 'eafe_unqw_settings' );
    ?>
    <label class="switch">
      <input type='checkbox' name='eafe_unqw_settings[naeafe_unique_countdown]' id='naeafe_unique_countdown-id' <?php checked( isset($options['naeafe_unique_countdown']), 1 ); ?> value='1' />
      <span class="slider round"></span>
    </label>
    <?php
  }
}

// Discussion (Unique) - Checkbox
if ( ! function_exists( 'naeafe_unique_discussion_render' ) ) {
  function naeafe_unique_discussion_render() {
    $options = get_option( 'eafe_unqw_settings' );
    ?>
    <label class="switch">
      <input type='checkbox' name='eafe_unqw_settings[naeafe_unique_discussion]' id='naeafe_unique_discussion-id' <?php checked( isset($options['naeafe_unique_discussion']), 1 ); ?> value='1' />
      <span class="slider round"></span>
    </label>
    <?php
  }
}

// Event (Unique) - Checkbox
if ( ! function_exists( 'naeafe_unique_event_render' ) ) {
  function naeafe_unique_event_render() {
    $options = get_option( 'eafe_unqw_settings' );
    ?>
    <label class="switch">
      <input type='checkbox' name='eafe_unqw_settings[naeafe_unique_event]' id='naeafe_unique_event-id' <?php checked( isset($options['naeafe_unique_event']), 1 ); ?> value='1' />
      <span class="slider round"></span>
    </label>
    <?php
  }
}

// Infobox (Unique) - Checkbox
if ( ! function_exists( 'naeafe_unique_infobox_render' ) ) {
  function naeafe_unique_infobox_render() {
    $options = get_option( 'eafe_unqw_settings' );
    ?>
    <label class="switch">
      <input type='checkbox' name='eafe_unqw_settings[naeafe_unique_infobox]' id='naeafe_unique_infobox-id' <?php checked( isset($options['naeafe_unique_infobox']), 1 ); ?> value='1' />
      <span class="slider round"></span>
    </label>
    <?php
  }
}

// Organizer (Unique) - Checkbox
if ( ! function_exists( 'naeafe_unique_organizer_render' ) ) {
  function naeafe_unique_organizer_render() {
    $options = get_option( 'eafe_unqw_settings' );
    ?>
    <label class="switch">
      <input type='checkbox' name='eafe_unqw_settings[naeafe_unique_organizer]' id='naeafe_unique_organizer-id' <?php checked( isset($options['naeafe_unique_organizer']), 1 ); ?> value='1' />
      <span class="slider round"></span>
    </label>
    <?php
  }
}

// Pricing (Unique) - Checkbox
if ( ! function_exists( 'naeafe_unique_pricing_render' ) ) {
  function naeafe_unique_pricing_render() {
    $options = get_option( 'eafe_unqw_settings' );
    ?>
    <label class="switch">
      <input type='checkbox' name='eafe_unqw_settings[naeafe_unique_pricing]' id='naeafe_unique_pricing-id' <?php checked( isset($options['naeafe_unique_pricing']), 1 ); ?> value='1' />
      <span class="slider round"></span>
    </label>
    <?php
  }
}

// Schedule List (Unique) - Checkbox
if ( ! function_exists( 'naeafe_unique_schedule_list_render' ) ) {
  function naeafe_unique_schedule_list_render() {
    $options = get_option( 'eafe_unqw_settings' );
    ?>
    <label class="switch">
      <input type='checkbox' name='eafe_unqw_settings[naeafe_unique_schedule_list]' id='naeafe_unique_schedule_list-id' <?php checked( isset($options['naeafe_unique_schedule_list']), 1 ); ?> value='1' />
      <span class="slider round"></span>
    </label>
    <?php
  }
}

// Schedule Tab (Unique) - Checkbox
if ( ! function_exists( 'naeafe_unique_schedule_tab_render' ) ) {
  function naeafe_unique_schedule_tab_render() {
    $options = get_option( 'eafe_unqw_settings' );
    ?>
    <label class="switch">
      <input type='checkbox' name='eafe_unqw_settings[naeafe_unique_schedule_tab]' id='naeafe_unique_schedule_tab-id' <?php checked( isset($options['naeafe_unique_schedule_tab']), 1 ); ?> value='1' />
      <span class="slider round"></span>
    </label>
    <?php
  }
}

// Schedule (Unique) - Checkbox
if ( ! function_exists( 'naeafe_unique_schedule_render' ) ) {
  function naeafe_unique_schedule_render() {
    $options = get_option( 'eafe_unqw_settings' );
    ?>
    <label class="switch">
      <input type='checkbox' name='eafe_unqw_settings[naeafe_unique_schedule]' id='naeafe_unique_schedule-id' <?php checked( isset($options['naeafe_unique_schedule']), 1 ); ?> value='1' />
      <span class="slider round"></span>
    </label>
    <?php
  }
}

// Sessions (Unique) - Checkbox
if ( ! function_exists( 'naeafe_unique_sessions_render' ) ) {
  function naeafe_unique_sessions_render() {
    $options = get_option( 'eafe_unqw_settings' );
    ?>
    <label class="switch">
      <input type='checkbox' name='eafe_unqw_settings[naeafe_unique_sessions]' id='naeafe_unique_sessions-id' <?php checked( isset($options['naeafe_unique_sessions']), 1 ); ?> value='1' />
      <span class="slider round"></span>
    </label>
    <?php
  }
}

// Ticket (Unique) - Checkbox
if ( ! function_exists( 'naeafe_unique_ticket_render' ) ) {
  function naeafe_unique_ticket_render() {
    $options = get_option( 'eafe_unqw_settings' );
    ?>
    <label class="switch">
      <input type='checkbox' name='eafe_unqw_settings[naeafe_unique_ticket]' id='naeafe_unique_ticket-id' <?php checked( isset($options['naeafe_unique_ticket']), 1 ); ?> value='1' />
      <span class="slider round"></span>
    </label>
    <?php
  }
}

// Upcoming (Unique) - Checkbox
if ( ! function_exists( 'naeafe_unique_upcoming_render' ) ) {
  function naeafe_unique_upcoming_render() {
    $options = get_option( 'eafe_unqw_settings' );
    ?>
    <label class="switch">
      <input type='checkbox' name='eafe_unqw_settings[naeafe_unique_upcoming]' id='naeafe_unique_upcoming-id' <?php checked( isset($options['naeafe_unique_upcoming']), 1 ); ?> value='1' />
      <span class="slider round"></span>
    </label>
    <?php
  }
}

// Venues (Unique) - Checkbox
if ( ! function_exists( 'naeafe_unique_venues_render' ) ) {
  function naeafe_unique_venues_render() {
    $options = get_option( 'eafe_unqw_settings' );
    ?>
    <label class="switch">
      <input type='checkbox' name='eafe_unqw_settings[naeafe_unique_venues]' id='naeafe_unique_venues-id' <?php checked( isset($options['naeafe_unique_venues']), 1 ); ?> value='1' />
      <span class="slider round"></span>
    </label>
    <?php
  }
}
