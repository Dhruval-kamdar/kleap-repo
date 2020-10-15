<?php /* Template Name: Miao Contact Template */ ?>
<?php
add_action( 'wp_head' , function () {
    ?>
    <style>
        .header-single-post + .dsn-page-content .contact {
            margin-top: 80px;
        }
    </style>
    <?php
} );

?>
<?php get_header(); ?>


    <section class="contact">
        <?php
        if ( have_posts() ): the_post();
            echo miao_view( 'shortcode/contactModel' , array( 'attr' => array( 'post_id' => get_the_ID() ) , 'content' => '' ) );
        endif;
        ?>
    </section>

<?php get_footer() ?>