<?php

class DroowBlock
{


    private static $setingElment = null;

    public static function AcfOption( $id, $default = false, $post_id = false, $format_value = true )
    {

        if ( self::$setingElment !== null ) {
            $output = droow_acf_option_array( self::$setingElment, $id );
        } else {
            $output = get_field( $id, $post_id, $format_value );


            if ( !$output && !is_numeric( $output ) ) {
                return $default;
            }
        }


        return $output;

    }

    public static function Image()
    {
        if ( self::$setingElment !== null ) {

            $ga = droow_acf_option_array( self::AcfOption( 'image' ), 'id', self::AcfOption( 'image' ) );
            if ( gettype( $ga ) === 'array' ):
                $out = array();
                for ( $index = 0; $index < count( $ga ); $index++ ) {
                    $out[] = droow_acf_option_array( $ga[ $index ], 'id' );
                }
                return $out;
            else :
                return $ga;
            endif;

        }

        return self::AcfOption( 'image' );
    }

    public static function ImageSize( $default = 'full' )
    {
        return self::AcfOption( 'image_size_acf', $default );
    }

    public static function Title()
    {
        return self::AcfOption( 'title' );
    }

    public static function TitleCaption()
    {

        $id = self::Image();
        $caption_type = self::AcfOption( 'caption' );
        if ( !$id || $caption_type === 'none' ) return '';
        if ( $caption_type === 'attachment' )
            return get_post( $id )->post_title;
        else
            return self::AcfOption( 'title_caption' );
    }

    public static function TitleCover()
    {
        return self::AcfOption( 'title_cover' );
    }


    public static function Link()
    {
        return self::AcfOption( 'link' );
    }

    public static function AttrAnimate( $id )
    {
        $val = self::AcfOption( $id );
        if ( $val )
            return 'data-dsn-animate=' . $val;
        return '';
    }

    public static function TitleAnimate()
    {
        return self::AttrAnimate( 'title_animate' );
    }

    public static function TitleAnimateCaption()
    {
        return self::AttrAnimate( 'title_animate_caption' );
    }

    public static function DescriptionAnimateCaption()
    {
        return self::AttrAnimate( 'description_animate_caption' );
    }


    public static function Description()
    {
        return self::AcfOption( 'description' );
    }

    public static function DescriptionCaption()
    {

        $id = self::Image();
        $caption_type = self::AcfOption( 'caption' );
        if ( !$id || $caption_type === 'none' ) return '';
        if ( $caption_type === 'attachment' )
            return get_post( $id )->post_content;
        else
            return self::AcfOption( 'description_caption' );
    }

    public static function Details()
    {
        $det = self::AcfOption( 'details', array() );
        if ( $det )
            return array_column( $det, 'description' );
        else
            return $det;
    }

    public static function DescriptionAnimate()
    {
        return self::AttrAnimate( 'description_animate' );
    }

    public static function Overlay()
    {

        return self::AcfOption( 'overlay', '0' );
    }

    public static function CustomCaption( $default = 'none' )
    {
        $id = self::Image();
        $caption_type = self::AcfOption( 'caption', $default );
        if ( !$id || $caption_type === 'none' ) return '';
        if ( $caption_type === 'attachment' )
            return wp_get_attachment_caption( $id );
        else
            return self::AcfOption( 'custom_caption', '' );
    }


    public static function Layout( $default = '' )
    {
        $layout = self::AcfOption( 'layout', $default );
        if ( !$layout ) return 'dsn-layout-default';
        return $layout;
    }


    public static function ParallaxType( $default = 'move' )
    {
        $parallax = self::AcfOption( 'animation_type', $default );
        if ( $parallax === 'move' )
            return 'has-top-bottom';
        return '';
    }

    public static function TranslateY( $default = 0 )
    {
        if ( $trnsy = self::AcfOption( 'translatey', $default ) )
            return $trnsy . "%";

        return '';
    }

    public static function Scale( $default = 1 )
    {
        $scale = self::AcfOption( 'scale', $default );

        if ( $scale != 1.1 && $scale )
            return $scale;

        return '';
    }


    public static function ChangeSectionColor()
    {
        $triggerhook = self::AcfOption( 'change_section_color' );

        if ( $triggerhook )
            return "data-dsn=color";

        return '';
    }

    public static function DurationColor()
    {
        $de = self::AcfOption( 'duration_color' );

        if ( $de && self::AcfOption( 'change_section_color' ) )
            return "data-dsn-duration={$de}";

        return '';
    }

    public static function styleVertical()
    {
        $val = self::AcfOption( 'style_vertical', 'left' );

        if ( $val === 'right' )
            return 'box-gallery-vertical-order';

        return '';
    }

    public static function Gallery()
    {
        $ga = self::AcfOption( 'gallery', array() );
        if ( self::$setingElment !== null && gettype( $ga ) === 'array' ) {
            $out = array();

            for ( $index = 0; $index < count( $ga ); $index++ ) {
                $out[] = droow_acf_option_array( $ga[ $index ], 'id' );
            }
            return $out;
        }

        return $ga;

    }

    public static function ShowCaption()
    {
        return self::AcfOption( 'show_caption', array() );
    }


    public static function Triggerhook()
    {
        $triggerhook = self::AcfOption( 'triggerhook' );

        if ( $triggerhook != 1 && $triggerhook !== false )
            return "data-dsn-triggerhook={$triggerhook}";

        return '';
    }

    public static function Duration()
    {
        $triggerhook = self::AcfOption( 'duration' );

        if ( $triggerhook && $triggerhook !== '200%' )
            return "data-dsn-duration={$triggerhook}";

        return '';
    }

    public static function Style()
    {
        $style_height = self::AcfOption( 'style_height' );
        $style_top = self::AcfOption( 'style_top' );
        $style_scale = self::AcfOption( 'style_scale' );
        $style_translatey = self::AcfOption( 'style_translatey' );
        $out = '';


        if ( !empty( $style_scale ) )
            $style_scale = "scale({$style_scale})";

        if ( !empty( $style_translatey ) )
            $style_translatey = "translateY({$style_translatey}%)";

        if ( $style_scale || $style_translatey )
            $out .= "transform:{$style_translatey} {$style_scale};";

        if ( !empty( $style_top ) )
            $out .= "top:{$style_top}%;";

        if ( !empty( $style_height ) )
            $out .= "height:{$style_height}%;";


//        dd(get_fields());
//        dd($style_height , $style_top , $style_scale , $style_translatey);


        return $out;
    }


    public static function getClassAttr( $block )
    {

        $class = droow_acf_option_array( $block, 'className' );
        if ( droow_acf_option_array( $block, 'align' ) )
            $class .= ' text-' . droow_acf_option_array( $block, 'align' );


        return $class . ' ' . self::UseAsUnderHeader();
    }

    public static function UseAsUnderHeader()
    {


        if ( self::AcfOption( 'use_as_under_header' ) )
            return 'dsn-under-header ' . self::AcfOption( 'layout_under_header' );
        return false;
    }


    public function preview( $block, $ex = 'png' )
    {

        printf( '<h1 class="dsn-title"> %s</h1>', $block[ 'icon' ] . $block[ 'title' ] );
        $path_name = DROOW__PLUGIN_DIR_URL . 'assets/img/' . $block[ 'name' ] . '.' . $ex;
        printf( '<img src="%s" class="dsn-preview" />', $path_name );

    }


    public function getAttrBlock( $block, $getType = 'acf' )
    {

        if ( $getType !== 'acf' ) {
            self::$setingElment = $getType;
            $link = array(
                'title' => droow_acf_option_array( $getType, 'link_title' ),
                'url'   => droow_acf_option_array( $getType, 'link_url' ),
            );
        } else {
            $link = self::Link();
        }


        return array(
            //--> Image
            'image'                       => self::Image(),
            'size-image'                  => self::ImageSize(),
            'overlay'                     => self::Overlay(),
            'caption'                     => self::CustomCaption(),
            'title_caption'               => self::TitleCaption(),
            'title_animate_caption'       => self::TitleAnimateCaption(),
            'description_caption'         => self::DescriptionCaption(),
            'description_animate_caption' => self::DescriptionAnimateCaption(),
            'layout'                      => self::Layout(),
            'parallax_type'               => self::ParallaxType(),
            'y'                           => self::TranslateY(),
            'scale'                       => self::Scale(),
            'style'                       => self::Style(),
            'triggerhook'                 => self::Triggerhook(),
            'duration'                    => self::Duration(),
            'Change-color'                => self::ChangeSectionColor(),
            'duration_color'              => self::DurationColor(),

            'style_vertical'      => self::styleVertical(),
            'gallery'             => self::Gallery(),
            'show_caption'        => self::ShowCaption(),

            //--> Description
            'title_cover'         => self::TitleCover(),
            'title'               => self::Title(),
            'title_animate'       => self::TitleAnimate(),
            'description'         => self::Description(),
            'description_animate' => self::DescriptionAnimate(),
            'title_link'          => droow_acf_option_array( $link, 'title' ),
            'url_link'            => droow_acf_option_array( $link, 'url' ),
            'className'           => self::getClassAttr( $block ),
            'number_columns'      => self::AcfOption( 'number_columns', 3 ),
            'choose_post_type'    => self::AcfOption( 'choose_post_type' ),
            'number_of_posts'     => self::AcfOption( 'number_of_posts' ),

        );
    }


}