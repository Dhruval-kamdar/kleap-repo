<?php
$my_post = get_query_var( 'my_post', array() );
$is_custom = get_query_var( 'is_custom' );

$id = droow_acf_option_array( droow_acf_option_array( $my_post, 'choose_post', $my_post ), 'ID' );

if ( $id === false ) return '';

$show_background_video = droow_acf_option_array( $my_post, 'show_background_video', droow_acf_option( 'show_background_video', false, $id ) );

$class_img = 'image-bg cover-bg';
if ( $show_background_video )
    $class_img = 'cover-bg hidden';

$img = droow_acf_option_array( $my_post, 'image', get_the_post_thumbnail( $id ) );
$img_src = droow_acf_option_array( $my_post, 'image', get_the_post_thumbnail_url( $id ) );
$img_src_mobile = droow_acf_option_array( $my_post, 'image', wp_get_attachment_image_src( droow_acf_option( 'featured_image_in_mobile', false, $id ), 'post-thumbnail' ) );
$overlay = droow_overlay( 4, $id );

if ( is_numeric( $img ) ) {
    $img_src = wp_get_attachment_image_src( $img, 'post-thumbnail' )[ 0 ];
    $img = wp_get_attachment_image( $img, 'post-thumbnail' );
    $overlay = droow_acf_option_array( $my_post, 'opacity_overlay', 4 );
}

if ( isset( $img_src_mobile[ 0 ] ) ) {
    $img_src_mobile = 'data-img-mobile="' . $img_src_mobile[ 0 ] . '"';
}


$title = droow_acf_option_array( $my_post, 'title', droow_custom_title( '', $id ) );
$subTitle = droow_acf_option_array( $my_post, 'subtitle', droow_subtitle_head( '', $id ) );
if ( !$subTitle )
    $subTitle = droow_post_category( ', ', false, false, $id );

$description = droow_acf_option_array( $my_post, 'description_header' );

if ( !$description || !$is_custom ):
    $description = droow_acf_option( 'description_for_slider', droow_description_head( '', $id ), $id );

endif;

$ajax_slider = 'slider';
if ( $is_custom ) $ajax_slider = '';


?>
<div class="slide-item swiper-slide">
    <div class="slide-content">
        <div class="slide-content-inner">
            <?php
            if ( $subTitle )
                printf( '<div class="project-metas"><div class="project-meta-box project-work cat"><span>%s</span></div></div>', esc_html( $subTitle ) );
            ?>


            <div class="title-text-header">
                <h1 class="title-text-header-inner">
                    <a href="<?php the_permalink( $id ) ?>" class="effect-ajax"
                       data-dsn-ajax="<?php echo esc_attr( $ajax_slider ) ?>">
                        <?php echo esc_html( $title ) ?>
                    </a>
                </h1>
            </div>


            <?php printf( '<p>%s</p>', esc_html( strip_tags( $description ) ) ) ?>


            <div class="link-custom">
                <a href="<?php the_permalink( $id ) ?>" class="image-zoom effect-ajax" data-dsn="parallax"
                   data-dsn-ajax="<?php echo esc_attr( $ajax_slider ) ?>">
                    <span><?php esc_html_e( 'View Case', 'droow' ) ?></span>
                </a>
            </div>

        </div>
    </div>
    <div class="image-container">


        <?php
        printf( '<div class="%s" data-image-src="%s" data-overlay="%s" %s>%s</div>', esc_attr( $class_img ), esc_url( $img_src ), esc_attr( $overlay ), $img_src_mobile, '' );
        ?>

        <?php if ( $show_background_video ) : ?>
            <div data-dsn="video" data-overlay="<?php echo esc_attr( $overlay ) ?>">
                <?php droow_background_video( $img_src, '', $id, $my_post ) ?>
            </div>
        <?php endif; ?>


    </div>
</div>
