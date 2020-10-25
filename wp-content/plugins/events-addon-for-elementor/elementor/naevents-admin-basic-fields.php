<?php

// About Me - Checkbox
if ( ! function_exists( 'naeafe_about_me_render' ) ) {
  function naeafe_about_me_render() {
    $options = get_option( 'eafe_bw_settings' );
    ?>
    <label class="switch">
      <input type='checkbox' name='eafe_bw_settings[naeafe_about_me]' id='naeafe_about_me-id' <?php checked( isset($options['naeafe_about_me']), 1 ); ?> value='1' />
      <span class="slider round"></span>
    </label>
    <?php
  }
}

// About Us - Checkbox
if ( ! function_exists( 'naeafe_about_us_render' ) ) {
  function naeafe_about_us_render() {
    $options = get_option( 'eafe_bw_settings' );
    ?>
    <label class="switch">
      <input type='checkbox' name='eafe_bw_settings[naeafe_about_us]' id='naeafe_about_us-id' <?php checked( isset($options['naeafe_about_us']), 1 ); ?> value='1' />
      <span class="slider round"></span>
    </label>
    <?php
  }
}

// Blog - Checkbox
if ( ! function_exists( 'naeafe_blog_render' ) ) {
  function naeafe_blog_render() {
    $options = get_option( 'eafe_bw_settings' );
    ?>
    <label class="switch">
      <input type='checkbox' name='eafe_bw_settings[naeafe_blog]' id='naeafe_blog-id' <?php checked( isset($options['naeafe_blog']), 1 ); ?> value='1' />
      <span class="slider round"></span>
    </label>
    <?php
  }
}

// Primary Button - Checkbox
if ( ! function_exists( 'naeafe_primary_button_render' ) ) {
  function naeafe_primary_button_render() {
    $options = get_option( 'eafe_bw_settings' );
    ?>
    <label class="switch">
      <input type='checkbox' name='eafe_bw_settings[naeafe_primary_button]' id='naeafe_primary_button-id' <?php checked( isset($options['naeafe_primary_button']), 1 ); ?> value='1' />
      <span class="slider round"></span>
    </label>
    <?php
  }
}

// Chart - Checkbox
if ( ! function_exists( 'naeafe_chart_render' ) ) {
  function naeafe_chart_render() {
    $options = get_option( 'eafe_bw_settings' );
    ?>
    <label class="switch">
      <input type='checkbox' name='eafe_bw_settings[naeafe_chart]' id='naeafe_chart-id' <?php checked( isset($options['naeafe_chart']), 1 ); ?> value='1' />
      <span class="slider round"></span>
    </label>
    <?php
  }
}

// Contact - Checkbox
if ( ! function_exists( 'naeafe_contact_render' ) ) {
  function naeafe_contact_render() {
    $options = get_option( 'eafe_bw_settings' );
    ?>
    <label class="switch">
      <input type='checkbox' name='eafe_bw_settings[naeafe_contact]' id='naeafe_contact-id' <?php checked( isset($options['naeafe_contact']), 1 ); ?> value='1' />
      <span class="slider round"></span>
    </label>
    <?php
  }
}

// Gallery - Checkbox
if ( ! function_exists( 'naeafe_gallery_render' ) ) {
  function naeafe_gallery_render() {
    $options = get_option( 'eafe_bw_settings' );
    ?>
    <label class="switch">
      <input type='checkbox' name='eafe_bw_settings[naeafe_gallery]' id='naeafe_gallery-id' <?php checked( isset($options['naeafe_gallery']), 1 ); ?> value='1' />
      <span class="slider round"></span>
    </label>
    <?php
  }
}

// Get Apps - Checkbox
if ( ! function_exists( 'naeafe_get_apps_render' ) ) {
  function naeafe_get_apps_render() {
    $options = get_option( 'eafe_bw_settings' );
    ?>
    <label class="switch">
      <input type='checkbox' name='eafe_bw_settings[naeafe_get_apps]' id='naeafe_get_apps-id' <?php checked( isset($options['naeafe_get_apps']), 1 ); ?> value='1' />
      <span class="slider round"></span>
    </label>
    <?php
  }
}

// History - Checkbox
if ( ! function_exists( 'naeafe_history_render' ) ) {
  function naeafe_history_render() {
    $options = get_option( 'eafe_bw_settings' );
    ?>
    <label class="switch">
      <input type='checkbox' name='eafe_bw_settings[naeafe_history]' id='naeafe_history-id' <?php checked( isset($options['naeafe_history']), 1 ); ?> value='1' />
      <span class="slider round"></span>
    </label>
    <?php
  }
}

// Image Compare - Checkbox
if ( ! function_exists( 'naeafe_image_compare_render' ) ) {
  function naeafe_image_compare_render() {
    $options = get_option( 'eafe_bw_settings' );
    ?>
    <label class="switch">
      <input type='checkbox' name='eafe_bw_settings[naeafe_image_compare]' id='naeafe_image_compare-id' <?php checked( isset($options['naeafe_image_compare']), 1 ); ?> value='1' />
      <span class="slider round"></span>
    </label>
    <?php
  }
}

// Process - Checkbox
if ( ! function_exists( 'naeafe_process_render' ) ) {
  function naeafe_process_render() {
    $options = get_option( 'eafe_bw_settings' );
    ?>
    <label class="switch">
      <input type='checkbox' name='eafe_bw_settings[naeafe_process]' id='naeafe_process-id' <?php checked( isset($options['naeafe_process']), 1 ); ?> value='1' />
      <span class="slider round"></span>
    </label>
    <?php
  }
}

// Separator - Checkbox
if ( ! function_exists( 'naeafe_separator_render' ) ) {
  function naeafe_separator_render() {
    $options = get_option( 'eafe_bw_settings' );
    ?>
    <label class="switch">
      <input type='checkbox' name='eafe_bw_settings[naeafe_separator]' id='naeafe_separator-id' <?php checked( isset($options['naeafe_separator']), 1 ); ?> value='1' />
      <span class="slider round"></span>
    </label>
    <?php
  }
}

// Services - Checkbox
if ( ! function_exists( 'naeafe_services_render' ) ) {
  function naeafe_services_render() {
    $options = get_option( 'eafe_bw_settings' );
    ?>
    <label class="switch">
      <input type='checkbox' name='eafe_bw_settings[naeafe_services]' id='naeafe_services-id' <?php checked( isset($options['naeafe_services']), 1 ); ?> value='1' />
      <span class="slider round"></span>
    </label>
    <?php
  }
}

// Slider - Checkbox
if ( ! function_exists( 'naeafe_slider_render' ) ) {
  function naeafe_slider_render() {
    $options = get_option( 'eafe_bw_settings' );
    ?>
    <label class="switch">
      <input type='checkbox' name='eafe_bw_settings[naeafe_slider]' id='naeafe_slider-id' <?php checked( isset($options['naeafe_slider']), 1 ); ?> value='1' />
      <span class="slider round"></span>
    </label>
    <?php
  }
}

// Subscribe Contact - Checkbox
if ( ! function_exists( 'naeafe_subscribe_contact_render' ) ) {
  function naeafe_subscribe_contact_render() {
    $options = get_option( 'eafe_bw_settings' );
    ?>
    <label class="switch">
      <input type='checkbox' name='eafe_bw_settings[naeafe_subscribe_contact]' id='naeafe_subscribe_contact-id' <?php checked( isset($options['naeafe_subscribe_contact']), 1 ); ?> value='1' />
      <span class="slider round"></span>
    </label>
    <?php
  }
}

// Table - Checkbox
if ( ! function_exists( 'naeafe_table_render' ) ) {
  function naeafe_table_render() {
    $options = get_option( 'eafe_bw_settings' );
    ?>
    <label class="switch">
      <input type='checkbox' name='eafe_bw_settings[naeafe_table]' id='naeafe_table-id' <?php checked( isset($options['naeafe_table']), 1 ); ?> value='1' />
      <span class="slider round"></span>
    </label>
    <?php
  }
}

// Team Single - Checkbox
if ( ! function_exists( 'naeafe_team_single_render' ) ) {
  function naeafe_team_single_render() {
    $options = get_option( 'eafe_bw_settings' );
    ?>
    <label class="switch">
      <input type='checkbox' name='eafe_bw_settings[naeafe_team_single]' id='naeafe_team_single-id' <?php checked( isset($options['naeafe_team_single']), 1 ); ?> value='1' />
      <span class="slider round"></span>
    </label>
    <?php
  }
}

// Team - Checkbox
if ( ! function_exists( 'naeafe_team_render' ) ) {
  function naeafe_team_render() {
    $options = get_option( 'eafe_bw_settings' );
    ?>
    <label class="switch">
      <input type='checkbox' name='eafe_bw_settings[naeafe_team]' id='naeafe_team-id' <?php checked( isset($options['naeafe_team']), 1 ); ?> value='1' />
      <span class="slider round"></span>
    </label>
    <?php
  }
}

// Testimonials - Checkbox
if ( ! function_exists( 'naeafe_testimonials_render' ) ) {
  function naeafe_testimonials_render() {
    $options = get_option( 'eafe_bw_settings' );
    ?>
    <label class="switch">
      <input type='checkbox' name='eafe_bw_settings[naeafe_testimonials]' id='naeafe_testimonials-id' <?php checked( isset($options['naeafe_testimonials']), 1 ); ?> value='1' />
      <span class="slider round"></span>
    </label>
    <?php
  }
}

// Typewriter - Checkbox
if ( ! function_exists( 'naeafe_typewriter_render' ) ) {
  function naeafe_typewriter_render() {
    $options = get_option( 'eafe_bw_settings' );
    ?>
    <label class="switch">
      <input type='checkbox' name='eafe_bw_settings[naeafe_typewriter]' id='naeafe_typewriter-id' <?php checked( isset($options['naeafe_typewriter']), 1 ); ?> value='1' />
      <span class="slider round"></span>
    </label>
    <?php
  }
}

// Video - Checkbox
if ( ! function_exists( 'naeafe_video_render' ) ) {
  function naeafe_video_render() {
    $options = get_option( 'eafe_bw_settings' );
    ?>
    <label class="switch">
      <input type='checkbox' name='eafe_bw_settings[naeafe_video]' id='naeafe_video-id' <?php checked( isset($options['naeafe_video']), 1 ); ?> value='1' />
      <span class="slider round"></span>
    </label>
    <?php
  }
}