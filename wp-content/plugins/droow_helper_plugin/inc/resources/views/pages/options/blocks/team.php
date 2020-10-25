<?php
acf_register_block( array(
    'name'     => 'dsnTeam' ,
    'title'    => esc_html__( 'Team' , 'droow' ) ,
    'icon'     => DroowSVG::Team() ,
    'category' => 'dsn-grid' ,

    'render_callback' => function ( $block , $content = '' , $is_preview = false , $post_id = 0 ) {


//        if ( $is_preview ) {
//            printf( '<h1 class="dsn-title">-- %s</h1>' , $block[ 'title' ] );
//        }

        $droow = new DroowBlock();
        echo droow_shortcode_team( $droow->getAttrBlock( $block ) , $droow::AcfOption( 'item' ) );


    }
) );


