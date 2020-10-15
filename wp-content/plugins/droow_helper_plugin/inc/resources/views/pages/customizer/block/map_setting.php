<?php


Kirki::add_section( $dsn_section , array(
    'panel' => $dsn_panel ,
    'title' => esc_html__( 'Map Settings' , 'droow' ) ,
    'icon'=>'dashicons-yes'

) );


Kirki::add_field( $dsn_customize , [
    'type'     => 'image' ,
    'settings' => 'map_marker_icon' ,
    'label'    => esc_html__( 'Map marker' , 'droow' ) ,
    'section'  => $dsn_section ,
    'default'  => DROOW__PLUGIN_DIR_URL . '/assets/img/map-marker.png' ,
] );

Kirki::add_field( $dsn_customize , [
    'type'        => 'textarea' ,
    'settings'    => 'map_api' ,
    'label'       => esc_html__( 'Google Maps API Key' , 'droow' ) ,
    'description' => esc_html__( 'Without it, the map may not be displayed. If you have an api key paste it here. https://maps.googleapis.com/maps/api/js?key=YOUR_API_KEY ' , 'droow' ) ,
    'section'     => $dsn_section ,

] );



function my_acf_google_map_api( $api ){
    $api['key'] = get_theme_mod('map_api');
    return $api;
}
add_filter('acf/fields/google_map/api', 'my_acf_google_map_api');
