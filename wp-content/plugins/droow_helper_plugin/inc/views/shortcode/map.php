<?php
$attr    = get_query_var( 'attr' );
$content = get_query_var( 'content' );

$shortcode = new DroowShortCode( $attr );


if ( !$content ) return;

$lat  = droow_acf_option_array( $content , 'lat' );
$lang = droow_acf_option_array( $content , 'lng' );
$zoom = droow_acf_option_array( $content , 'zoom' , 14 );


?>


<div class="<?php echo esc_attr( $shortcode->className() . ' ' . $shortcode->layout() ) ?> " <?php echo esc_attr( $shortcode->changeColor() ) ?>>
    <div class="map-custom" data-dsn-lat="<?php echo esc_attr( $lat ) ?>"
         data-dsn-len="<?php echo esc_attr( $lang ) ?>" data-dsn-zoom="<?php echo esc_attr( $zoom ) ?>">
    </div>
</div>