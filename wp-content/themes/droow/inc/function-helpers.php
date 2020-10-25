<?php
/**
 *  get Logo Style
 */
if ( !function_exists( 'droow_get_logo' ) ) :

    function droow_get_logo()
    {
        echo ' <a href="' . esc_url( home_url( '/' ) ) . '" rel="home">';
        if ( has_custom_logo() ):
            echo wp_get_attachment_image( get_theme_mod( 'custom_logo' ), 'full', false, array(
                'class' => 'custom-logo light-logo',
                'alt'   => get_bloginfo( 'name', 'display' )
            ) );
        else:
            echo '<h4 class="light-logo">' . esc_html( get_bloginfo( 'name' ) ) . '</h4>';
        endif;

        if ( get_theme_mod( 'custom_logo_dark' ) ):
            echo wp_get_attachment_image( get_theme_mod( 'custom_logo_dark' ), 'full', false, array(
                'class' => 'custom-logo dark-logo',
                'alt'   => get_bloginfo( 'name', 'display' )
            ) );
        else:
            echo '<h4 class="dark-logo">' . esc_html( get_bloginfo( 'name' ) ) . '</h4>';
        endif;

        echo '</a>';
    }

endif;


/**
 *
 *  ============================
 *         Slug Project
 *  ============================
 *
 *  - Retrieves an option value based on an Slug Work name.
 *
 */
if ( !function_exists( 'droow_project_slug' ) ) :

    function droow_project_slug()
    {
        return 'droow-portfolio';
    }

endif;

/**
 *
 *  ============================
 *         Slug Category Project
 *  ============================
 *
 *  - Retrieves an option value based on an Slug Work name.
 *
 */
if ( !function_exists( 'droow_category_slug' ) ) :

    function droow_category_slug()
    {

        return 'dsn-droow-categories';

    }

endif;


if ( !function_exists( 'droow_is_work' ) ) :

    function droow_is_work( $post = null )
    {
        return get_post_type( $post ) === droow_project_slug();
    }

endif;

/**
 *
 *  ============================
 *         Is Page Slider
 *  ============================
 *
 *
 *
 */
if ( !function_exists( 'droow_is_slider' ) ) :

    function droow_is_slider( $post = null )
    {
        return get_page_template_slug( $post ) === 'page-slider.php';
    }

endif;

/**
 *
 *  ============================
 *     Get Value From ACF
 *  ============================
 *
 *
 *
 */
if ( !function_exists( 'droow_acf_option' ) ) :

    function droow_acf_option( $id, $default = false, $post_id = false, $format_value = true )
    {


        $output = '';
        if ( class_exists( 'acf' ) ) {
            $output = get_field( $id, $post_id, $format_value );
        }


        if ( !$output ) {
            return $default;
        }

        return $output;

    }

endif;


if ( !function_exists( 'droow_acf_option_array' ) ) :

    function droow_acf_option_array( $options, $id, $default = false )
    {


        $options = (object)$options;
        if ( isset( $options->{$id} ) and !empty( $options->{$id} ) ) {
            return $options->{$id};
        }

        return $default;
    }

endif;


if ( !function_exists( 'droow_author_full_name' ) ) :

    function droow_author_full_name()
    {
        $first_name = get_the_author_meta( 'first_name' );
        $last_name = get_the_author_meta( 'last_name' );

        if ( !empty( $first_name ) or !empty( $last_name ) ) {
            return esc_html( $first_name ) . ' ' . esc_html( $last_name );
        }


        return esc_html( get_the_author() );
    }

endif;

/**
 * @return array [id , name , title , description]
 */
if ( !function_exists( 'droow_get_archives' ) ) :

    function droow_get_archives()
    {


        if ( is_category() ) {
            $cat = get_category( get_query_var( 'cat' ) );

            return array(
                'id'          => 'category',
                'name'        => esc_html__( 'Categories Archives', 'droow' ),
                'title'       => $cat->name,
                'description' => $cat->category_description,
            );
        } else if ( is_tag() ) {
            return array(
                'id'          => 'tag',
                'name'        => esc_html__( 'Tags Archives', 'droow' ),
                'title'       => single_tag_title( '', false ),
                'description' => '',

            );
        } elseif ( is_search() ) {
            return array(
                'id'          => 'search',
                'title'       => esc_html__( 'Search', 'droow' ),
                'name'        => get_search_query(),
                'description' => '',
            );
        } elseif ( is_author() ) {
            return array(
                'id'          => 'author',
                'name'        => esc_html__( 'Author Archives', 'droow' ),
                'title'       => droow_author_full_name(),
                'description' => get_the_author_meta( 'description' ),
            );
        } else if ( is_day() ) {
            return array(
                'id'          => 'day',
                'name'        => esc_html__( 'Daily Archives', 'droow' ),
                'title'       => get_the_date(),
                'description' => '',
            );
        } else if ( is_month() ) {

            return array(
                'id'          => 'month',
                'name'        => esc_html__( 'Monthly Archives', 'droow' ),
                'title'       => get_the_date( 'F Y' ),
                'description' => '',
            );
        } else if ( is_year() ) {
            return array(
                'id'          => 'year',
                'name'        => esc_html__( 'Yearly Archives', 'droow' ),
                'title'       => get_the_date( 'Y' ),
                'description' => '',
            );
        } else if ( is_404() ) {
            return array(
                'id'          => 'error400',
                'name'        => esc_html__( '404', 'droow' ),
                'title'       => esc_html__( 'Oops! Not Found', 'droow' ),
                'description' => esc_html__( 'It looks like nothing was found at this location. Maybe try a search?', 'droow' ),
            );
        } else if ( is_archive() && get_queried_object() !== null && isset( get_queried_object()->taxonomy ) ) {
            $obj = get_queried_object();

            return array(
                'id'          => $obj->taxonomy,
                'name'        => $obj->description,
                'title'       => $obj->name,
                'description' => ''
            );
        }


        return array(
            'id'          => '',
            'name'        => '',
            'title'       => '',
            'description' => ''
        );

    }

endif;


/**
 *
 *  ============================
 *         Custom Filed
 *  ============================
 *
 *  - Replace title page custom Title.
 *
 */
if ( !function_exists( 'droow_subtitle_head' ) ) :

    function droow_subtitle_head( $default = '', $post_id = false )
    {
        if ( is_home() ) {
            return get_theme_mod( 'home_custom_subtitle', 'NEWS & IDEAS' );
        } elseif ( is_archive() || is_search() ) {
            return droow_acf_option_array( droow_get_archives(), 'name' );
        }
        return droow_acf_option( 'subtitle', $default, $post_id );
    }

endif;


if ( !function_exists( 'droow_overlay' ) ) :

    function droow_overlay( $default = '0', $post_id = false )
    {

        return droow_acf_option( 'opacity_overlay', $default, $post_id );
    }

endif;


/**
 *
 *  ============================
 *         Custom Filed
 *  ============================
 *
 *  - Replace title page custom Title.
 *
 */
if ( !function_exists( 'droow_custom_title' ) ) :

    function droow_custom_title( $default = '', $post = 0 )
    {


        if ( is_home() ) {
            return get_theme_mod( 'home_custom_title', 'Our Blog' );
        } elseif ( is_archive() || is_search() ) {
            return droow_acf_option_array( droow_get_archives(), 'title' );

        }

        if ( $default ) {
            if ( get_the_title( $post ) ) {
                return get_the_title( $post );
            }
            return $default;
        }

        $custom_title = droow_acf_option( 'custom_title', false, $post );

        if ( !$custom_title ):
            if ( get_the_title( $post ) ) {
                return get_the_title( $post );
            }
        endif;

        return $custom_title;

    }

endif;


/**
 *
 *  ============================
 *         Custom Filed
 *  ============================
 *
 *  - Replace title page custom Title.
 *
 */
if ( !function_exists( 'droow_description_head' ) ) :

    function droow_description_head( $default = '', $post_id = false )
    {

        return droow_acf_option( 'description_header', $default, $post_id );
    }

endif;

if ( !function_exists( 'droow_section_padding' ) ) :

    function droow_section_padding( $default = 'single-post', $post_id = false )
    {
        return droow_acf_option( 'section_padding', $default, $post_id );
    }

endif;

/**
 * @return string The permalink for the specified day, month, and year archive.
 */
if ( !function_exists( 'droow_date_link' ) ) :

    function droow_date_link()
    {
        $archive_year = get_the_time( 'Y' );
        $archive_month = get_the_time( 'm' );
        $archive_day = get_the_time( 'd' );

        return get_day_link( $archive_year, $archive_month, $archive_day );
    }

endif;

/**
 * @return string links of category
 */
if ( !function_exists( 'droow_post_category' ) ) :

    function droow_post_category( $seperate = ', ', $terms = false, $link = true, $current_post = false, $after = '', $before = '' )
    {

        if ( $terms === false ) {
            $terms = 'category';
            if ( droow_is_work( $current_post ) ) {
                $terms = droow_category_slug();
                $link = false;
            }
        }


        $categores = get_the_terms( $current_post, $terms );
        $outPut = '';
        $i = 0;
        if ( !empty( $categores ) ) {
            foreach ( $categores as $cat ) {
                $cat_link = get_category_link( $cat->term_id );
                $alt = esc_attr__( 'View All Posts in', 'droow' ) . ' ' . $cat->description;
                $cat_name = $cat->name;

                if ( $i > 0 && !$link ) {
                    $outPut .= $seperate;
                }

                $outPut .= $after;

                if ( $link ) {
                    $s = $seperate;
                    if ( $i === count( $categores ) - 1 )
                        $s = '';
                    $outPut .= '<a href="' . esc_url( $cat_link ) . '" title="' . esc_attr( $alt ) . '" class="effect-ajax">' . esc_html( $cat_name ) . $s . '</a>';
                } else {
                    $outPut .= esc_html( $cat_name );
                }

                $outPut .= $before;
                $i++;
            }

        }

        return $outPut;
    }

endif;

/**
 * @return string links of category
 */
if ( !function_exists( 'droow_project_category_slug' ) ) :

    function droow_project_category_slug()
    {
        $categores = get_the_terms( false, droow_category_slug() );

        $outPut = '';
        $i = 0;

        if ( !empty( $categores ) ) {
            foreach ( $categores as $cat ) {
                $cat_slug = $cat->slug;
                if ( $i > 0 ) {
                    $outPut .= ' ';
                }
                $outPut .= esc_html( $cat_slug );
                $i++;
            }
        }

        return $outPut;
    }

endif;


/**
 *
 *  ============================
 *     Get Value From ACF
 *  ============================
 *
 *
 *
 */
if ( !function_exists( 'droow_get_img' ) ) :

    function droow_get_img( $attr = array() )
    {
        $attr = shortcode_atts( array(
            'size'     => 'post-thumbnail',
            'type'     => true,
            'after'    => '',
            'before'   => '',
            'after_v'  => '',
            'before_v' => '',
            'post'     => null,
            'attr'     => array(),
            'class'    => '',
            'post_bg'  => true
        ), $attr );

        $is_has_thum = has_post_thumbnail( $attr[ 'post' ] );
        if ( $is_has_thum ) {
            printf( $attr[ 'before' ] );
        }

        if ( droow_acf_option( 'show_background_video' ) && droow_acf_option( 'background_video' ) ) {
            printf( $attr[ 'before_v' ] );
            droow_background_video(
                droow_get_scr_img( $attr[ 'post' ], $attr[ 'size' ], $attr[ 'post_bg' ] ),
                $attr[ 'class' ]
            );
            printf( $attr[ 'after_v' ] );
            printf( $attr[ 'after' ] );
            return;
        }

        if ( $attr[ 'type' ] === 'img' ) {
            the_post_thumbnail( $attr[ 'size' ], $attr[ 'attr' ] );
        } else {
            $img_src_mobile = wp_get_attachment_image_src( droow_acf_option( 'featured_image_in_mobile' ), 'post-thumbnail' );
            if ( isset( $img_src_mobile[ 0 ] ) ) {
                $img_src_mobile = 'data-img-mobile="' . $img_src_mobile[ 0 ] . '"';
            }

            printf( '<div data-image-src="%s" %s %s></div>',
                droow_get_scr_img( $attr[ 'post' ], $attr[ 'size' ], $attr[ 'post_bg' ] ),
                $img_src_mobile,
                droow_get_data_attr( $attr[ 'attr' ] )
            );
        }


        if ( $is_has_thum ) {
            printf( $attr[ 'after' ] );
        }

    }

endif;


/**
 *
 *  ============================
 *     Get Value From ACF
 *  ============================
 *
 *
 *
 */


if ( !function_exists( 'droow_background_video' ) ) :

    function droow_background_video( $img, $class = '', $post_id = false, $attr = array() )
    {

        $bg_video = droow_acf_option_array( $attr, 'background_video' );
        if ( !is_array( $bg_video ) )
            $bg_video = droow_acf_option( 'background_video', array(), $post_id );

        if ( $bg_video ) :
            echo '<video class="image-bg cover-bg dsn-video ' . esc_attr( $class ) . '" poster="' . esc_url( $img ) . '" autoplay loop muted playsinline>';
            foreach ( $bg_video as $video ):
                $is_video_external = $video[ 'use_url' ];
                $url = 'url';
                if ( $is_video_external ) {
                    $url = 'upload';
                }

                if ( $type = droow_get_type_video( $video[ $url ] ) ) {
                    printf( '<source src="%s" type="%s">', esc_url( $video[ $url ] ), esc_attr( $type ) );
                }

            endforeach;
            echo esc_html__( 'Your browser does not support HTML5 video.', 'droow' );
            echo '</video>';
        endif;

    }

endif;


if ( !function_exists( 'droow_get_type_video' ) ) :

    function droow_get_type_video( $url )
    {
        $format = array( 'webm', 'mp4', 'ogg' );
        $type = wp_check_filetype( $url );
        if ( isset( $type[ 'ext' ] ) and in_array( $type[ 'ext' ], $format ) ) {
            return $type [ 'type' ];
        }

        return false;

    }

endif;


if ( !function_exists( 'droow_get_data_attr' ) ) :

    function droow_get_data_attr( $attr = array() )
    {
        $out = '';
        foreach ( $attr as $key => $value ) {
            $out .= $key . '="' . $value . '"';
        }
        return $out;
    }

endif;


if ( !function_exists( 'droow_get_scr_img' ) ) :

    function droow_get_scr_img( $post = null, $size = 'post-thumbnail', $post_bg = true )
    {

        if ( $post_bg && has_post_thumbnail( $post ) ) {
            return get_the_post_thumbnail_url( $post, $size );
        }
    }

endif;


if ( !function_exists( 'droow_type_header' ) ) :

    function droow_type_header()
    {

        $default = get_post_type();
        if ( $default === 'page' ):
            $default = 'post';
        elseif ( $default === droow_project_slug() ):
            $default = 'project';
        endif;

        $type_header = droow_acf_option( 'header_type', 'post' );
        $style = '';
        if ( $type_header === 'post' ) {
            $style = droow_acf_option( 'header_style_img', $default );

        } else if ( $type_header === 'normal' ) {
            $style = droow_acf_option( 'header_style_normal', 'normal' );
        }

        if ( is_home() || is_archive() || is_search() ) {
            $type_header = 'normal';
            $style = 'normal2';
        } elseif ( droow_is_slider() || is_404() ) {
            $type_header = 'none';
        }

        return array(
            'type'  => $type_header,
            'style' => $style,
        );

    }

endif;

/**
 *
 *  ============================
 *         LayOut Pages
 *  ============================
 *
 *  - get page layout --> full , con , half
 *
 */
if ( !function_exists( 'droow_layout_pages' ) ) :

    function droow_layout_pages( $isHeader = true )
    {

        if ( is_404() || droow_is_slider() ) {
            return false;
        }
        $default = 'container';
        if ( droow_is_work() )
            $default = 'full';


        $layout = droow_acf_option( 'page_layout', $default );

        if ( is_home() || is_archive() || is_search() )
            $layout = 'container';


        if ( $layout !== 'full' ) {
            $isHalf = $layout === 'half';
            if ( $isHeader ):
                echo droow_layout_header( $isHalf );
            else :
                echo droow_layout_footer( $isHalf );
            endif;
        }

    }

endif;

if ( !function_exists( 'droow_layout_header' ) ) :

    function droow_layout_header( $isHalf = true )
    {
        $out = '<section class="page-content ">';
        $out .= '<div class="container">';
        if ( $isHalf ):
            $out .= '<div class="row justify-content-center">';
            $out .= '<div class="col-lg-10">';
        endif;

        return $out;
    }
endif;


if ( !function_exists( 'droow_layout_footer' ) ) :

    function droow_layout_footer( $isHalf = true )
    {
        $out = '';
        if ( $isHalf ):
            $out .= '</div><!--close col-lg-10-->';
            $out .= '</div><!--close row-->';
        endif;
        $out .= '</div><!--close container-->';
        $out .= '</section><!--close Section-->';


        return $out;
    }
endif;

/**
 * @return string links of tag
 */
if ( !function_exists( 'droow_post_tag' ) ) :

    function droow_post_tag( $seperate = ', ' )
    {

        $tags = get_the_tags();

        $outPut = '';
        $i = 0;

        if ( !empty( $tags ) ) {
            $outPut .= '<div class="post-tags">';
            foreach ( $tags as $tag ) {
                $tag_link = get_tag_link( $tag->term_id );
                $alt = esc_attr__( 'View All Posts in Tag', 'droow' ) . ' ' . $tag->description;
                $tag_name = $tag->name;
                $post_meta = $tag->taxonomy;

                if ( $i > 0 ) {
                    $outPut .= $seperate;
                }


                $outPut .= '<a href="' . esc_url( $tag_link ) . '" title="' . esc_attr( $alt ) . '" rel="tag"><span class="post_tag ' . esc_attr( $post_meta ) . '">' . esc_html( $tag_name ) . '</span></a>';
                $i++;
            }
            $outPut .= '</div>';
        }

        return $outPut;
    }

endif;


/**
 * -Retrieves the amount of comments a post has.
 * @return string  - the numeric string representing the number of comments  the post has
 */
if ( !function_exists( 'droow_post_count_comment' ) ) :

    function droow_post_count_comment( $link = true, $in_h3 = '', $show_num = true )
    {
        $comment_num = get_comments_number();
        $s_link_open = '<a href="' . esc_url( get_comments_link() ) . '">';
        $s_link_close = '</a>';
        $comment = '';
        $s_title = '' . $in_h3;
        $e_title = '';
        $s_number = '<span class="num-comment">';
        $e_number = '</span>';

        // comment link
        if ( $show_num ):
            if ( $comment_num == 0 ):
                $comment = $s_title . esc_html__( 'Comment', 'droow' ) . $e_title . $s_number . '0' . $e_number;
                $s_link_open = '';
                $s_link_close = '';
            elseif ( $comment_num > 1 ) :
                $comment = $s_title . esc_html__( 'Comments', 'droow' ) . $e_title . $s_number . esc_html( $comment_num ) . $e_number;
            else:
                $comment = $s_title . esc_html__( 'Comment', 'droow' ) . $e_title . $s_number . esc_html( $comment_num ) . $e_number;
            endif;
        else:
            if ( $comment_num > 0 ):
                $comment = $s_title . esc_html__( 'Comments', 'droow' ) . $e_title;
            else:
                $comment = $s_title . esc_html__( 'No Comments', 'droow' ) . $e_title;
            endif;

        endif;


        if ( $link ):
            $comments = $s_link_open . $comment . $s_link_close;
        else:
            $comments = $comment;
        endif;


        return $comments;
    }

endif;


if ( !function_exists( 'droow_get_next_posts' ) ) :

    function droow_get_next_posts( $post_id = false )
    {

        $next_post = droow_get_next( $post_id );
        ?>

        <section class="contact-up next-post-up section-margin section-padding">
            <div class="container">
                <div class="c-wapp">
                    <a href="<?php echo esc_url( get_permalink( $next_post->ID ) ) ?>" class="effect-ajax">
                        <span class="hiring">
                            <?php
                            if ( get_the_title( $next_post->ID ) ):
                                echo get_the_title( $next_post->ID );
                            else:
                                esc_html_e( 'Next Post', 'droow' );
                            endif;

                            ?>
                        </span>
                        <span class="career">
                            <?php esc_html_e( 'Next Post', 'droow' ); ?>
                        </span>
                    </a>
                </div>
            </div>
        </section>
        <?php

    }

endif;

if ( !function_exists( 'droow_get_next_page' ) ) :

    function droow_get_next_page()
    {
        $next_post = droow_acf_option( 'next_page' );
        if ( !$next_post ) return "";
        $title = droow_acf_option( 'title_next_page' );
        $subtitle = droow_acf_option( 'subtitle_next_page' );
        ?>

        <section class="contact-up next-post-up section-margin section-padding">
            <div class="container">
                <div class="c-wapp">
                    <a href="<?php echo esc_url( get_permalink( $next_post->ID ) ) ?>" class="effect-ajax">
                        <span class="hiring">
                            <?php
                            if ( $title ):
                                echo esc_html( $title );
                            elseif ( get_the_title( $next_post->ID ) ):
                                echo get_the_title( $next_post->ID );
                            else:
                                esc_html_e( 'Next Page', 'droow' );
                            endif;

                            ?>
                        </span>
                        <span class="career">
                             <?php
                             if ( $subtitle ):
                                 echo esc_html( $subtitle );
                             elseif ( droow_subtitle_head( '', $next_post->ID ) ):
                                 echo droow_subtitle_head( '', $next_post->ID );
                             endif;
                             ?>
                        </span>
                    </a>
                </div>
            </div>
        </section>
        <?php

    }

endif;

if ( !function_exists( 'droow_get_next_project' ) ) :

    function droow_get_next_project( $post_id = false )
    {

        $next_post = droow_get_next( $post_id );
        ?>
        <div class="next-project" data-dsn-footer="project">
            <div id="dsn-next-parallax-img" class="bg">
                <?php $fe_img = get_the_post_thumbnail_url( $next_post ) ?>
                <div class="bg-image cover-bg"
                     data-overlay="<?php echo esc_attr( droow_overlay( 4 ), $next_post->ID ) ?>"
                     data-image-src="<?php echo esc_url( $fe_img ) ?>"></div>
            </div>

            <div id="dsn-next-parallax-title" class="project-title f-column f-center-h">
                <a href="<?php echo esc_url( get_permalink( $next_post->ID ) ) ?>" class="effect-ajax"
                   data-dsn-ajax="next-project">
                    <div class="title-text-header">
                        <div class="title-text-header-inner">
                            <span class="dsn-title-header"><?php echo get_the_title( $next_post->ID ); ?></span>
                        </div>
                    </div>
                    <div class="sub-text-header">
                        <h5><?php esc_html_e( 'Next Project', 'droow' ) ?></h5>
                    </div>
                </a>
            </div>
        </div>

        <?php

    }

endif;


if ( !function_exists( 'droow_get_next' ) ) :

    function droow_get_next( $post_id = false )
    {

        if ( $post_id ) {
            $next_post = $post_id;
        } else {
            $next_post = get_Next_post();
        }


        if ( !$next_post && !isset( $next_post->ID ) ) {

            global $post;
            $isOrder = class_exists( 'Hicpo' );
            $get_num = 0;
            $args = array(
                'post_type'      => $post->post_type,
                'order'          => 'ASC',
                'posts_per_page' => -1,
            );
            if ( $isOrder ) {
                $args[ 'orderby' ] = 'menu_order';
                $args[ 'order' ] = 'DESC';
            }

            $recent = wp_get_recent_posts( $args, OBJECT );
            if ( $isOrder )
                $get_num = count( $recent ) - 1;


            if ( !empty( $recent ) && isset( $recent[ $get_num ] ) ) {
                $next_post = $recent[ $get_num ];
            } else {
                return false;
            }
        }

        return $next_post;
    }

endif;


/**
 * @return string  Buttons pagination
 */
if ( !function_exists( 'droow_buttons_pagination' ) ) :

    function droow_buttons_pagination( $label = '', $class = '' )
    {

        $out = '<span class="button-m ' . esc_attr( $class ) . '" >';
        $out .= '<svg viewBox="0 0 52 52" xmlns="http://www.w3.org/2000/svg" width="100%" height="100%">';
        $out .= '<path d="M3 26.7h39.5v3.5c0 .3.1.5.4.6.2.1.5.1.7-.1l5.9-4.2c.2-.1.3-.3.3-.5s-.1-.4-.3-.5l-5.9-4.2c-.1-.1-.3-.1-.4-.1-.1 0-.2 0-.3.1-.2.1-.4.3-.4.6v3.5H3c-.4 0-.7.3-.7.7 0 .3.3.6.7.6z"></path>';
        $out .= '</svg>';
        $out .= '<span>' . esc_html( $label ) . '</span>';
        $out .= '</span>';

        return $out;

    }

endif;


/**
 * @return string  links pagination
 */
if ( !function_exists( 'droow_pagination' ) ) :

    function droow_pagination( $args = '' )
    {
        global $wp_query;

        $num = droow_acf_option_array( $args, 'total', 0 );

        if ( $wp_query->max_num_pages > 1 || $num > 1 ) {
            $out = '<div class="dsn-pagination">';
            $out .= paginate_links( $args );
            $out .= '</div>';
        }

        echo isset( $out ) ? $out : '';

    }

endif;


if ( !function_exists( 'droow_space_header' ) ) :

    /**
     * @return string  links pagination
     */
    function droow_space_header( $ppend = '' )
    {

        echo !droow_is_work() && droow_section_padding() === 'single-post' ? 'mb-section ' . $ppend : '';

    }

endif;


if ( !function_exists( 'droow_space_footer' ) ) :

    /**
     * @return string  links pagination
     */
    function droow_space_footer( $ppend = '' )
    {

        echo !droow_is_work() && droow_section_padding() === 'single-post' ? 'm-section ' . $ppend : '';

    }

endif;


/**
 *
 *  - Is Version Light
 *
 */
if ( !function_exists( 'droow_filtering' ) ) :

    function droow_filtering()
    {

        if ( get_page_template_slug() !== 'page-work.php' ) return '';
        $style_work = droow_acf_option( 'style_work', 'work' );

        ?>


        <div class="filterings">
            <div class="filtering-wrap">
                <div class="filtering ">
                    <div class="selector"></div>

                    <?php
                    $terms = get_terms( droow_category_slug() );

                    if ( !empty( $terms ) ): ?>
                        <button type="button" data-filter='*' class="active">
                            <?php esc_html_e( 'All', 'droow' ) ?>
                        </button>
                        <?php
                        foreach ( $terms as $term ):?>
                            <button type="button" data-filter='.<?php echo esc_attr( $term->slug ); ?>'>
                                <?php echo esc_html( $term->name ); ?>
                            </button>
                        <?php endforeach; endif;
                    ?>

                </div>

            </div>
        </div>
        <?php


    }

endif;

add_action( 'droow_style_version', function () {

    if ( is_home() || is_archive() || is_search() )
        return;

    if ( droow_acf_option( 'background_color' ) === 'light' ):
        echo '<div data-dsn-temp="light"></div>';
    endif;
} );


add_action( 'droow_menu_version', function () {
    if ( is_home() || is_archive() || is_search() )
        return;
    if ( droow_acf_option( 'menu_type', 'hamburger-menu' ) !== 'hamburger-menu' ):
        echo '<div class="main-menu"></div>';
    endif;
} );

/**
 *
 *  ============================
 *         Custom Filed
 *  ============================
 *
 *  - Replace title page custom Title.
 *
 */
if ( !function_exists( 'droow_get_num' ) ) :

    function droow_get_num( $num )
    {
        if ( $num <= 9 ) {
            return '0' . $num;
        }
        return $num;
    }
endif;

if ( !function_exists( 'droow_social_setting' ) ) :
    function droow_social_setting( $show_in_footer = true )
    {

        $default = array();

        $so = get_theme_mod( 'dsn_social', $default );
        $display = '';
        if ( $show_in_footer )
            $display = get_theme_mod( 'display_social_footer', 'icon' );
        else
            $display = get_theme_mod( 'display_social_slider', 'icon' );

        $output = '';
        foreach ( $so as $s ):
            $link = droow_acf_option_array( $s, 'link', '' );
            $show_footer = droow_acf_option_array( $s, 'show_in_footer' );
            $show_slider = droow_acf_option_array( $s, 'show_in_slider' );
            $name = droow_acf_option_array( $s, 'name' );
            $type = droow_acf_option_array( $s, $display );


            if ( ( $show_in_footer and $show_footer ) or ( !$show_in_footer and $show_slider ) ) {
                $label = $type;
                if ( $display === 'icon' ) {
                    $label = '<i class="' . esc_attr( $type ) . '"></i>';
                }
                if ( $type ) {
                    $output .= '<li class="image-zoom" data-dsn="parallax" ><a href="' . esc_url( $link ) . '" target="_blank" title="' . esc_attr( $name ) . '" rel="nofollow">' . $label . '</a></li>';
                }

            }
        endforeach;

        return $output;
    }
endif;

if ( !function_exists( 'droow_get_social' ) ) :
    function droow_get_social( $display = 'name', $so = array() )
    {


        $output = '';
        foreach ( $so as $s ):
            $link = droow_acf_option_array( $s, 'link', '' );
            $name = droow_acf_option_array( $s, 'name' );
            $type = droow_acf_option_array( $s, $display );

            $label = $type;
            if ( $display === 'icon' ) {
                $label = '<i class="' . esc_attr( $type ) . '"></i>';
            }
            if ( $type ) {
                $output .= '<li class="image-zoom" data-dsn="parallax" ><a href="' . esc_url( $link ) . '" target="_blank" title="' . esc_attr( $name ) . '" rel="nofollow">' . $label . '</a></li>';
            }
        endforeach;

        return $output;
    }
endif;
