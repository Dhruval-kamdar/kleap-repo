<?php

add_action( 'wp_ajax_nopriv_droow_post_query' , 'droow_ajax_post_query' );
add_action( 'wp_ajax_droow_post_query' , 'droow_ajax_post_query' );


function droow_ajax_post_query()
{

    if ( isset( $_POST[ 'type' ] ) && isset( $_POST[ 'page' ] ) && isset( $_POST[ 'layout' ] ) && is_numeric( $_POST[ 'page' ] ) && defined( 'DOING_AJAX' ) && DOING_AJAX ) {
        global $post;

        $type         = wp_strip_all_tags( $_POST[ 'type' ] );
        $page         = wp_strip_all_tags( $_POST[ 'page' ] );
        $dsn_projects = droow_project_slug();

        $layout = $_POST[ 'layout' ];
        $type   = ( $type === 'projects' ) ? $dsn_projects : '';

        if ( isset( $_POST[ 'dsnId' ] ) )
            $numPage = $_POST[ 'dsnId' ];
        else
            $numPage = get_option( 'posts_per_page' );


        $myposts = get_posts( array( 'post_type' => $type , 'paged' => $page , 'posts_per_page' => $numPage ) );
        $html    = '';



        if ( count( $myposts ) ):
            foreach ( $myposts as $post ) :
                setup_postdata( $post );
                ob_start();

                get_template_part( 'template-parts/content/content' , $layout );
                $html .= ob_get_clean();
            endforeach;
        endif;


        $encoded = array(
            'status'   => true ,
            'html'     => $html ,
            'ss'       => $_POST[ 'dsnId' ] ,
            'has_next' => ( $numPage <= count( $myposts ) )
        );

        header( 'Content-Type: application/json' );

        echo json_encode( $encoded );
        wp_reset_postdata();

    }

    die();
}