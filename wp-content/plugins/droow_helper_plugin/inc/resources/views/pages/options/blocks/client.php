<?php
acf_register_block( array(
    'name'            => 'dsnClient' ,
    'title'           => esc_html__( 'Client' , 'miao' ) ,
    'icon'            => DroowSVG::Review() ,
    'category'        => 'dsn-grid' ,
    'render_callback' => function ( $block , $content = '' , $is_preview = false , $post_id = 0 ) {

        $blocks = new DroowBlock();

//        if ( $is_preview ) {
//            printf( '<h1 class="dsn-title">-- %s</h1>' , $block[ 'title' ] );
//        }

        echo droow_shortcode_client( $blocks->getAttrBlock( $block ) , $blocks::AcfOption('item') );


    }

) );

