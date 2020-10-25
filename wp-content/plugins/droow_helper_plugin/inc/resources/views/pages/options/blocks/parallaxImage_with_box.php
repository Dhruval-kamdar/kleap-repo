<?php

acf_register_block( array(
    'name'            => 'parallaxImageWithBox' ,
    'title'           => esc_html__( 'Image With Box Description' , 'droow' ) ,
    'icon'            => 'format-image' ,
    'category'        => 'dsn-grid' ,

    'render_callback' => function ( $block , $content = '' , $is_preview = false , $post_id = 0 ) {

        $blocks = new DroowBlock();


//        if ( $is_preview ) {
//            printf( '<h1 class="dsn-title">-- %s</h1>' , $block[ 'title' ] );
//        }
        echo droow_shortcode_ImageWithBox( $blocks->getAttrBlock( $block ) , $blocks::AcfOption( 'style_box' ) );

    }
) );



