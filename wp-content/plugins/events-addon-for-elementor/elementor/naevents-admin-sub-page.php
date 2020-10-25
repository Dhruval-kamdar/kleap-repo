<?php
if ( ! function_exists( 'eafe_bw_settings_init' ) ) {
  function eafe_bw_settings_init() {

    register_setting( 'naeventsBasicWidgets', 'eafe_bw_settings' );

    // Card Title - Basic Widgets
    add_settings_section(
      'eafe_naeventsBasicWidgets_section',
      __( 'Basic Widgets', 'events-addon-for-elementor' ),
      '',
      'naeventsBasicWidgets'
    );

    $naevents_basic_widgets['about_me'] = __( 'About Me', 'events-addon-for-elementor' );
    $naevents_basic_widgets['about_us'] = __( 'About Us', 'events-addon-for-elementor' );
    $naevents_basic_widgets['blog'] = __( 'Blog', 'events-addon-for-elementor' );
    $naevents_basic_widgets['primary_button'] = __( 'Primary Button', 'events-addon-for-elementor' );
    $naevents_basic_widgets['chart'] = __( 'Chart', 'events-addon-for-elementor' );
    $naevents_basic_widgets['contact'] = __( 'Contact', 'events-addon-for-elementor' );
    $naevents_basic_widgets['gallery'] = __( 'Gallery', 'events-addon-for-elementor' );
    $naevents_basic_widgets['get_apps'] = __( 'Get Apps', 'events-addon-for-elementor' );
    $naevents_basic_widgets['history'] = __( 'History', 'events-addon-for-elementor' );
    $naevents_basic_widgets['image_compare'] = __( 'Image Compare', 'events-addon-for-elementor' );
    $naevents_basic_widgets['process'] = __( 'Process', 'events-addon-for-elementor' );
    $naevents_basic_widgets['separator'] = __( 'Separator', 'events-addon-for-elementor' );
    $naevents_basic_widgets['services'] = __( 'Services', 'events-addon-for-elementor' );
    $naevents_basic_widgets['slider'] = __( 'Slider', 'events-addon-for-elementor' );
    $naevents_basic_widgets['subscribe_contact'] = __( 'Subscribe / Contact', 'events-addon-for-elementor' );
    $naevents_basic_widgets['table'] = __( 'Table', 'events-addon-for-elementor' );
    $naevents_basic_widgets['team_single'] = __( 'Team Single', 'events-addon-for-elementor' );
    $naevents_basic_widgets['team'] = __( 'Team', 'events-addon-for-elementor' );
    $naevents_basic_widgets['testimonials'] = __( 'Testimonials', 'events-addon-for-elementor' );
    $naevents_basic_widgets['typewriter'] = __( 'Typewriter', 'events-addon-for-elementor' );
    $naevents_basic_widgets['video'] = __( 'Video', 'events-addon-for-elementor' );
    foreach ($naevents_basic_widgets as $key => $value) {
      // Label
      add_settings_field(
        'naeafe_'. $key,
        $value,
        'naeafe_'. $key .'_render',
        'naeventsBasicWidgets',
        'eafe_naeventsBasicWidgets_section',
        array( 'label_for' => 'naeafe_'. $key .'-id' )
      );
    }

  }
}

if ( ! function_exists( 'eafe_unqw_settings_init' ) ) {
  function eafe_unqw_settings_init() {

    register_setting( 'naeventsUniqueWidgets', 'eafe_unqw_settings' );

    // Card Title - Unique Widgets
    add_settings_section(
      'eafe_naeventsUniqueWidgets_section',
      __( 'Unique Widgets', 'events-addon-for-elementor' ),
      '',
      'naeventsUniqueWidgets'
    );

    $naevents_unique_widgets['unique_accommodation'] = __( 'Accommodation', 'events-addon-for-elementor' );
    $naevents_unique_widgets['unique_calendar_button'] = __( 'Calendar Button', 'events-addon-for-elementor' );
    $naevents_unique_widgets['unique_call_action'] = __( 'Call Action', 'events-addon-for-elementor' );
    $naevents_unique_widgets['unique_conference'] = __( 'Conference', 'events-addon-for-elementor' );
    $naevents_unique_widgets['unique_countdown'] = __( 'Countdown', 'events-addon-for-elementor' );
    $naevents_unique_widgets['unique_discussion'] = __( 'Discussion', 'events-addon-for-elementor' );
    $naevents_unique_widgets['unique_event'] = __( 'Event', 'events-addon-for-elementor' );
    $naevents_unique_widgets['unique_infobox'] = __( 'Infobox', 'events-addon-for-elementor' );
    $naevents_unique_widgets['unique_organizer'] = __( 'Organizer', 'events-addon-for-elementor' );
    $naevents_unique_widgets['unique_pricing'] = __( 'Pricing', 'events-addon-for-elementor' );
    $naevents_unique_widgets['unique_schedule_list'] = __( 'Schedule List', 'events-addon-for-elementor' );
    $naevents_unique_widgets['unique_schedule_tab'] = __( 'Schedule Tab', 'events-addon-for-elementor' );
    $naevents_unique_widgets['unique_schedule'] = __( 'Schedule', 'events-addon-for-elementor' );
    $naevents_unique_widgets['unique_sessions'] = __( 'Sessions', 'events-addon-for-elementor' );
    $naevents_unique_widgets['unique_ticket'] = __( 'Ticket', 'events-addon-for-elementor' );
    $naevents_unique_widgets['unique_upcoming'] = __( 'Upcoming', 'events-addon-for-elementor' );
    $naevents_unique_widgets['unique_venues'] = __( 'Venues', 'events-addon-for-elementor' );
    foreach ($naevents_unique_widgets as $key => $value) {
      // Label
      add_settings_field(
        'naeafe_'. $key,
        $value,
        'naeafe_'. $key .'_render',
        'naeventsUniqueWidgets',
        'eafe_naeventsUniqueWidgets_section',
        array( 'label_for' => 'naeafe_'. $key .'-id' )
      );
    }

  }
}

if ( ! function_exists( 'eafe_prow_settings_init' ) ) {
  function eafe_prow_settings_init() {

    register_setting( 'naeventsProWidgets', 'eafe_prow_settings' );

    // Card Title - Pro Widgets
    add_settings_section(
      'eafe_naeventsProWidgets_section',
      __( 'Event & Pro Widgets', 'events-addon-for-elementor' ),
      '',
      'naeventsProWidgets'
    );

    $naevents_pro_widgets['pro_call_to_action'] = __( 'Call To Action (Free)', 'events-addon-for-elementor' );
    $naevents_pro_widgets['pro_events_list'] = __( 'Events List (Free)', 'events-addon-for-elementor' );
    $naevents_pro_widgets['pro_events_attendees'] = __( 'Events Attendees (Free)', 'events-addon-for-elementor' );
    $naevents_pro_widgets['pro_events_ticket_selector'] = __( 'Events Ticket Selector (Free)', 'events-addon-for-elementor' );
    $naevents_pro_widgets['pro_events_calendar'] = __( 'Events Calendar (Free)', 'events-addon-for-elementor' );
    $naevents_pro_widgets['pro_event_categories'] = __( 'Event Categories (Free)', 'events-addon-for-elementor' );
    $naevents_pro_widgets['pro_event_form'] = __( 'Event Form (Free)', 'events-addon-for-elementor' );
    $naevents_pro_widgets['pro_events_group'] = __( 'Events Group (Free)', 'events-addon-for-elementor' );
    $naevents_pro_widgets['pro_event_locations'] = __( 'Event Locations (Free)', 'events-addon-for-elementor' );
    $naevents_pro_widgets['pro_event_search'] = __( 'Event Search (Free)', 'events-addon-for-elementor' );
    $naevents_pro_widgets['pro_events_full_calendar'] = __( 'Events Full Calendar (Free)', 'events-addon-for-elementor' );
    $naevents_pro_widgets['pro_info_box'] = __( 'Info Box (Free)', 'events-addon-for-elementor' );
    $naevents_pro_widgets['pro_events_map'] = __( 'Events Map (Free)', 'events-addon-for-elementor' );
    $naevents_pro_widgets['pro_events_subscribe'] = __( 'Events Subscribe (Free)', 'events-addon-for-elementor' );
    $naevents_pro_widgets['pro_calendar_button'] = __( 'Calendar Button (Free)', 'events-addon-for-elementor' );
    $naevents_pro_widgets['pro_chart'] = __( 'Chart (Pro)', 'events-addon-for-elementor' );
    $naevents_pro_widgets['pro_pricing'] = __( 'Pricing (Pro)', 'events-addon-for-elementor' );
    $naevents_pro_widgets['pro_venues'] = __( 'Venues (Pro)', 'events-addon-for-elementor' );
    $naevents_pro_widgets['pro_conference'] = __( 'Conference (Pro)', 'events-addon-for-elementor' );
    $naevents_pro_widgets['pro_countdown'] = __( 'Countdown (Pro)', 'events-addon-for-elementor' );
    $naevents_pro_widgets['pro_schedule'] = __( 'Schedule (Pro)', 'events-addon-for-elementor' );
    $naevents_pro_widgets['pro_upcoming_events'] = __( 'Upcoming Events (Pro)', 'events-addon-for-elementor' );
    $naevents_pro_widgets['pro_event_search_listing'] = __( 'Event Search Listing (Pro)', 'events-addon-for-elementor' );
    $naevents_pro_widgets['pro_event_listing'] = __( 'Event Listing (Pro)', 'events-addon-for-elementor' );
    $naevents_pro_widgets['pro_event_slider'] = __( 'Event Slider (Pro)', 'events-addon-for-elementor' );
    $naevents_pro_widgets['pro_sessions'] = __( 'Sessions (Pro)', 'events-addon-for-elementor' );
    $naevents_pro_widgets['pro_event_category'] = __( 'Event Category (Pro)', 'events-addon-for-elementor' );
    $naevents_pro_widgets['pro_organizer'] = __( 'Organizer (Pro)', 'events-addon-for-elementor' );
    
    foreach ($naevents_pro_widgets as $key => $value) {
      // Label
      add_settings_field(
        'naeafe_'. $key,
        $value,
        'naeafe_'. $key .'_render',
        'naeventsProWidgets',
        'eafe_naeventsProWidgets_section',
        array( 'label_for' => 'naeafe_'. $key .'-id' )
      );
    }

  }
}

// Output on Admin Page
if ( ! function_exists( 'naevents_admin_sub_page' ) ) {
  function naevents_admin_sub_page() { ?>
    <h2 class="title">Enable & Disable - Primary Elementor Widgets</h2>
    <div class="card naevents-fields-card naevents-fields-basic">
      <form action='options.php' method='post'>
        <?php
        settings_fields( 'naeventsBasicWidgets' );
        do_settings_sections( 'naeventsBasicWidgets' );
        submit_button(__( 'Save Basic Widgets Settings', 'events-addon-for-elementor' ), 'basic-submit-class');
        ?>
      </form>
    </div>
    <div class="card naevents-fields-card naevents-fields-unique">
      <form action='options.php' method='post'>
        <?php
        settings_fields( 'naeventsUniqueWidgets' );
        do_settings_sections( 'naeventsUniqueWidgets' );
        submit_button(__( 'Save Unique Widgets Settings', 'events-addon-for-elementor' ), 'unique-submit-class');
        ?>
      </form>
    </div>
    <div class="card naevents-fields-card naevents-fields-pro">
      <form action='options.php' method='post'>
        <?php
        settings_fields( 'naeventsProWidgets' );
        do_settings_sections( 'naeventsProWidgets' );
        submit_button(__( 'Save Pro Widgets Settings', 'events-addon-for-elementor' ), 'pro-submit-class');
        ?>
      </form>
    </div>
    <?php
  }
}