<?php
acf_register_block( array(
    'name'     => 'dsn_contact' ,
    'title'    => esc_html__( 'Contact' , 'droow' ) ,
    'icon'     => DroowSVG::Contact() ,
    'supports' => array( 'align' => false ) ,
    'keywords' => array( 'dsn' , 'contact' , 'service' ) ,
    'category' => 'dsn-grid' ,


    'render_callback' => function ( $block , $content = '' , $is_preview = false , $post_id = 0 ) {

//        if ( $is_preview ) {
//            printf( '<h1 class="dsn-title">-- %s</h1>' , $block[ 'title' ] );
//        }

        $droow = new DroowBlock();
        echo droow_shortcode_contact( $droow->getAttrBlock( $block ) , $droow::AcfOption( 'item' ) );

    }

) );

