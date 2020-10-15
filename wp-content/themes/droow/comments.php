<?php

if ( post_password_required() ):
    return;
endif;
?>
<div class="comments-post m-section">

    <div class="comments-area section-margin">


        <?php if ( $titleComment = droow_post_count_comment( false , '' , false ) ): ?>
            <div class="comments-title">
                <h4 class="subtitle">
                    <?php echo esc_html( $titleComment ); ?>
                </h4>
            </div>
        <?php endif;

        if ( have_comments() ) :
            echo '<ol class="comment-list">';
            wp_list_comments( array(
                'walker'      => new Droow_Walker_Comment() ,
                'type'        => 'all' ,
                'style'       => 'ol' ,
                'avatar_size' => 120 ,

            ) );
            $comment_pagination = paginate_comments_links(
                array(
                    'echo'      => false ,
                    'end_size'  => 0 ,
                    'mid_size'  => 0 ,
                    'next_text' => esc_html__( 'Newer Comments' , 'droow' ) . ' <span aria-hidden="true">&rarr;</span>' ,
                    'prev_text' => '<span aria-hidden="true">&larr;</span> ' . esc_html__( 'Older Comments' , 'droow' ) ,
                )
            );

            if ( $comment_pagination ) {
                $pagination_classes = '';

                // If we're only showing the "Next" link, add a class indicating so.
                if ( strpos( $comment_pagination , 'prev page-numbers' ) === false ) {
                    $pagination_classes = ' only-next';
                }
                ?>

                <nav class="comments-pagination pagination<?php echo esc_attr( $pagination_classes ); ?>"
                     aria-label="<?php esc_attr_e( 'Comments' , 'droow' ); ?>">
                    <?php echo wp_kses_post( $comment_pagination ); ?>
                </nav>

                <?php
            }

            echo '</ol>';

        endif;

        /* If comments are closed and there are comments, let's leave a little note, shall we? */
        if ( !comments_open() && get_comments_number() && post_type_supports( get_post_type() , 'comments' ) ) :
            printf( '<div class="m-section">%s</div>' , esc_html__( 'Comment Are Closed' , 'droow' ) );
        endif;


        ?>


    </div>


    <?php
    $commenter = wp_get_current_commenter();

    $rowstart    = '<div class="entry-form">';
    $rowText     = '<div class="entry-form">';
    $rowend      = '</div>';
    $autor       = '<input id="author" name="author" type="text" placeholder="' . esc_attr__( 'Type your name' , 'droow' ) . '"  value="' . esc_attr( $commenter[ 'comment_author' ] ) . '" maxlength="245" required>';
    $email       = '<input id="email"  name="email" type="email" placeholder="' . esc_attr__( 'Type your Email Address' , 'droow' ) . '" value="' . esc_attr( $commenter[ 'comment_author_email' ] ) . '" maxlength="245" required>';
    $textComment = '<textarea id="comment" name="comment" placeholder="' . esc_attr__( 'Comment' , 'droow' ) . '" required="required"></textarea>';


    $fields = array(
        'author' => $rowstart . '<label>' . esc_html__( 'What\'s your name?' , 'droow' ) . '</label>' . $autor . $rowend ,
        'email'  => $rowstart . '<label>' . esc_html__( 'What\'s your email address?' , 'droow' ) . '</label>' . $email . $rowend
    );

    $commenter_args_form = array(
        'class_form'           => '' ,
        'label_submit'         => esc_html__( 'Post Comment' , 'droow' ) ,
        'title_reply'          => esc_html__( 'Leave A Comment' , 'droow' ) ,
        'title_reply_before'   => '<div class="comments-title"><h4 class="subtitle">' ,
        'title_reply_after'    => '</h4></div>' ,
        'cancel_reply_before'  => '' ,
        'cancel_reply_after'   => '' ,
        'title_reply_to'       => esc_html__( 'Leave a reply to' , 'droow' ) . ' %s' ,
        'cancel_reply_link'    => '<span class="cancel-comment">' . esc_html__( 'Cancel' , 'droow' ) . '</span>' ,
        'class_submit'         => 'custom-btn' ,
        'fields'               => $fields ,
        'comment_field'        => $rowText . '<label>' . esc_html__( 'comment?' , 'droow' ) . '</label>' . $textComment . $rowend ,
        'comment_notes_before' => '' ,
        'comment_notes_after'  => '' ,
        'submit_field'         => '<div class="submit-div image-zoom" data-dsn="parallax"> %1$s %2$s</div>' ,
        'format'               => 'xhtml' ,
    );
    ?>


    <div class="comments-form">
        <?php comment_form( $commenter_args_form ) ?>
    </div>


</div>