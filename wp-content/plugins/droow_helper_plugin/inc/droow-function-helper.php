<?php
/**
 *
 *  ============================
 *        Custom Slug Project
 *  ============================
 *
 *  - Retrieves an option value based on an Slug Work name.
 *
 */
if ( !function_exists( 'droow_custom_project_slug' ) ) :

    function droow_custom_project_slug()
    {
        return get_theme_mod( 'droow-project-slug', droow_project_slug() );
    }

endif;

if ( !function_exists( 'droow_custom_category_slug' ) ) :

    function droow_custom_category_slug()
    {
        $cat = get_theme_mod( 'droow-category-slug', droow_category_slug() );
        if ( $cat === 'category' )
            return 'categories';
        else
            return get_theme_mod( 'droow-category-slug', droow_category_slug() );
    }

endif;


if ( !function_exists( 'droow_get_templete_render' ) ) :

    function droow_get_templete_render( $slug, array $param = array(), $is_render = true )
    {

        if ( !file_exists( plugin_dir_path( __FILE__ ) . '/' . $slug . '.php' ) )
            return "";

        if ( count( $param ) ):
            foreach ( $param as $key => $value ):
                set_query_var( $key, $value );
            endforeach;
        endif;

        if ( $is_render ):
            ob_start();
        endif;
        extract( $param );
        include plugin_dir_path( __FILE__ ) . '/' . $slug . '.php';
        if ( $is_render ):
            return ob_get_clean();
        endif;

    }

endif;
if ( !function_exists( 'droow_resources' ) ) :

    function droow_resources( $slug, array $param = array(), $is_render = false )
    {

        droow_get_templete_render( 'resources/' . $slug, $param, $is_render );
    }

endif;

if ( !function_exists( 'droow_resources_block' ) ) :

    function droow_resources_block( $slug, array $param = array(), $is_render = false )
    {

        droow_get_templete_render( 'resources/views/pages/options/blocks/' . $slug, $param, $is_render );
    }

endif;

if ( !function_exists( 'droow_view' ) ) :

    function droow_view( $slug, array $param = array(), $is_render = true )
    {

        return droow_get_templete_render( 'views/' . $slug, $param, $is_render );
    }

endif;

if ( !function_exists( 'droow_resources_customize' ) ) :

    function droow_resources_customize( $slug, array $param = array(), $is_render = false )
    {

        droow_get_templete_render( 'resources/views/pages/customizer/block/' . $slug, $param, $is_render );
    }

endif;
/**
 *
 * you can fill in defaults when needed.
 *
 * @param array $pairs The list of supported attributes and their defaults.
 * @param array $att User defined attributes .
 *
 * @return array .
 */
if ( !function_exists( 'droow_set_attr' ) ) :

    function droow_set_attr( array $pairs, array $att )
    {

        foreach ( $att as $key => $value ) {

            if ( !array_key_exists( $key, $pairs ) ) {

                $pairs[ $key ] = $value;

            }

        }

        return $pairs;
    }

endif;

/**
 *
 * you can fill in defaults when needed.
 *
 * @param array $pairs The list of supported attributes and their defaults.
 * @param array $att User defined attributes .
 *
 * @return string .
 */
if ( !function_exists( 'droow_get_attr' ) ) :

    function droow_get_attr( $att )
    {

        $out = '';

        if ( empty( $att ) ) {

            return $out;

        } elseif ( !is_array( $att ) ) {

            return $att;

        } elseif ( count( $att ) ) {

            foreach ( $att as $key => $value ) {
                $out .= $key . '="' . $value . '" ';
            }

        }

        return $out;
    }

endif;


/**
 * @return string  - Share Blog in Social media
 */
if ( !function_exists( 'droow_share_links' ) ) :

    function droow_share_links( $befor = '', $after = '' )
    {

        $share_link = get_theme_mod( 'share_link' );
        if ( !$share_link ) return '';

        $share_links = get_theme_mod( 'show_hide_share_link', array(
            'facebook',
            'twitter',
            'google-plus',
            'pinterest',
        ) );

        $url = get_the_permalink();

        $share_buttons = array(
            'facebook'    => droow_set_share_links( esc_html__( 'Facebook', 'droow' ), array( 'href' => 'https://www.facebook.com/sharer/sharer.php?u=' . esc_url( $url ) ) ),
            'twitter'     => droow_set_share_links( esc_html__( 'Twitter', 'droow' ), array( 'href' => 'https://twitter.com/share?url=' . esc_url( $url ) ) ),
            'google-plus' => droow_set_share_links( esc_html__( 'Google+', 'droow' ), array( 'href' => 'https://plus.google.com/share?url=' . esc_url( $url ) ) ),
            'pinterest'   => droow_set_share_links( esc_html__( 'Pinterest', 'droow' ), array( 'href' => 'https://www.pinterest.com/pin/create/button/?url=' . esc_url( $url ) ) ),
            'get-pocket'  => droow_set_share_links( esc_html__( 'Get Pocket', 'droow' ), array( 'href' => 'https://getpocket.com/save?url=' . esc_url( $url ) ) ),
            'telegram'    => droow_set_share_links( esc_html__( 'Telegram', 'droow' ), array( 'href' => 'https://t.me/share/url?url=' . esc_url( $url ) ) ),
        );
        $out = '';
        foreach ( $share_links as $link ):
            $out .= $befor . $share_buttons[ $link ] . $after;
        endforeach;


        return $out;
    }

endif;

if ( !function_exists( 'droow_set_share_links' ) ) :

    function droow_set_share_links( $content, $att = array() )
    {

        $att = droow_set_attr( $att, array(
            'rel'     => 'nofollow',
            'href'    => '',
            'onclick' => 'window.open(\'' . $att[ 'href' ] . '\' , \'share-dialog\', \'width=626,height=436\'); return false;',
        ) );

        $att = droow_get_attr( $att );


        return '<a ' . $att . ' >' . $content . '</a>';
    }

endif;

