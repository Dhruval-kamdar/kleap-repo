<?php
acf_register_block( array(
    'name'     => 'dsn_paragraph' ,
    'title'    => esc_html__( 'DsnGrid Paragraph' , 'Droow' ) ,
    'icon'     => DroowSVG::Paragraph() ,
    'supports' => array( 'align' => true ) ,
    'keywords' => array( 'dsn' , 'paragraph' , 'introduction' ) ,
    'category' => 'dsn-grid' ,
    'example'  => true ,

    'render_callback' => function ( $block , $content = '' , $is_preview = false , $post_id = 0 ) {

        $blocks = new DroowBlock();

//        if ( $is_preview ) {
//            $blocks->preview( $block );
//        }


        echo droow_shortcode_paragraph( $blocks->getAttrBlock( $block ) , $content );

    }

) );


