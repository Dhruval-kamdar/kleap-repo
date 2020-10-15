<?php
/**
 * Custom comment walker for this theme.
 *
 * @package WordPress
 * @subpackage Droow
 * @since 1.0.0
 */

if ( !class_exists( 'Droow_Walker_Comment' ) ) {
    /**
     * CUSTOM COMMENT WALKER
     * A custom walker for comments, based on the walker in Droow.
     */
    class Droow_Walker_Comment extends Walker_Comment
    {

        /**
         * Outputs a comment in the HTML5 format.
         *
         * @param WP_Comment $comment Comment to display.
         * @param int $depth Depth of the current comment.
         * @param array $args An array of arguments.
         * @see https://developer.wordpress.org/reference/functions/get_avatar/
         * @see https://developer.wordpress.org/reference/functions/get_comment_reply_link/
         * @see https://developer.wordpress.org/reference/functions/get_edit_comment_link/
         *
         * @see wp_list_comments()
         * @see https://developer.wordpress.org/reference/functions/get_comment_author_url/
         * @see https://developer.wordpress.org/reference/functions/get_comment_author/
         */

        protected function html5_comment( $comment , $depth , $args )
        {
            $avtar_img          = droow_acf_option( 'avetar_img_profile' , false , 'user_' . $comment->user_id );
            $auther_comment_url = get_comment_author_url( $comment );


            $comment_class = 'comment-list';
            if ( $depth !== 1 )
                $comment_class = 'comment-list blog_comments-rep';
            switch ( $comment->comment_type ) :
                case 'pingback':
                case 'trackback':
                    ?>

                    <li id="comment-<?php comment_ID(); ?>" class="comment-pingback comment-list comment">
                    <div class="pingback ">
                        <div class="pingback ms-author-name "><?php comment_author_link(); ?></div>
                        <div class="comment-meta">
                            <?php edit_comment_link( '<i class="fas fa-pencil-alt"></i> ' . esc_html__( 'Edit' , 'droow' ) , '<span class="edit-link">' , '</span>' ); ?>
                        </div>
                    </div>


                    <?php break;
                default:
                    ?>
                <li id="comment-<?php comment_ID(); ?>" <?php comment_class( esc_attr( $comment_class ) ); ?> >
                    <div class="comment-body">
                        <div class="comment-author">
                            <?php
                            if ( $avtar_img && wp_attachment_is_image( $avtar_img ) )
                                echo wp_get_attachment_image( $avtar_img );
                            else
                                echo get_avatar( $comment , 50 , $default = '' );
                            ?>
                        </div>
                        <div class="comment-text">

                            <div class="comment-date">
                                <?php echo get_comment_date( 'F d, Y' ) . esc_html__( ' at ' , 'droow' ) . get_comment_time(); ?>
                            </div>
                            <div class="comment-info">
                                <h6 class="comment-name">
                                    <?php
                                    if ( empty( $auther_comment_url ) ) :
                                        the_author();
                                    else:
                                        printf( '<a href="%s" rel="external nofollow" class="url">%s</a>' , esc_url( $auther_comment_url ) , get_the_author() );
                                    endif;
                                    ?>

                                </h6>
                                <div class="comment-edit">
                                    <?php edit_comment_link( '<i class="fas fa-pencil-alt"></i> ' . esc_html__( 'Edit' , 'droow' ) , ' ' , '' ); ?>
                                </div>
                            </div>
                            <div class="post-full-content single-post text-holder">
                                <?php if ( $comment->comment_approved == '0' ): ?>
                                    <i class="moderate">
                                        <?php esc_html_e( 'Your comment is awaiting moderation.' , 'droow' ); ?>
                                    </i><br/>
                                <?php endif; ?>
                                <?php comment_text(); ?>
                            </div>
                            <?php
                            comment_reply_link( array_merge( $args , array(
                                'depth'      => $depth ,
                                'max_depth'  => $args[ 'max_depth' ] ,
                                'reply_text' => '<i class="fas fa-reply"></i> ' . esc_html__( 'Reply' , 'droow' ) ,
                                'before'     => '<div class="reply">' ,
                                'after'      => '</div>'
                            ) ) );
                            ?>

                        </div>
                    </div>

                <?php endswitch;


        }
    }
}