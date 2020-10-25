<?php
acf_register_block( array(
    'name'     => 'dsn_service' ,
    'title'    => esc_html__( 'Service' , 'droow' ) ,
    'icon'     => DroowSVG::Service() ,
    'supports' => array( 'align' => true ) ,
    'keywords' => array( 'dsn' , 'paragraph' , 'introduction' , 'experience' , 'service' ) ,
    'category' => 'dsn-grid' ,


    'render_callback' => function ( $block , $content = '' , $is_preview = false , $post_id = 0 ) {

//        if ( $is_preview ) {
//            printf( '<h1 class="dsn-title">-- %s</h1>' , $block[ 'title' ] );
//        }

        $droow = new DroowBlock();
        echo droow_shortcode_Service( $droow->getAttrBlock( $block ) , $droow::AcfOption( 'item' ) );

    }

) );

