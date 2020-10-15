<?php


if ( droow_acf_option( 'use_slider_only' ) === false || droow_acf_option( 'all_work' ) === false ) return;


$dsn_projects = droow_project_slug();

$myposts = new WP_Query( array(
    'posts_per_page' => -1 ,
    'post_type'      => $dsn_projects
) );

?>

<div class="view-all image-zoom" data-dsn="parallax">
    <div class="view-all-container">
        <div class="icon ">
            <div class="line-1"></div>
            <div class="line-2"></div>
        </div>
        <div class="text"><?php esc_html_e( 'All Works' , 'droow' ) ?></div>
    </div>
</div>

<div class="dsn-all-work">
    <div class="nav-work-box dsn-work-scrollbar">
        <div class="list-content">
            <ul class="list-main">
                <?php if ( $myposts->have_posts() ) :
                    $index = 1;
                    while ( $myposts->have_posts() ) :
                        $myposts->the_post();
                        $active = '';
                        if ( $index === 1 ) {
                            $active = 'dsn-active';
                        }
                        ?>
                        <li class="work-item <?php echo esc_attr( $active ) ?>">
                            <?php
                            if ( has_post_thumbnail() ):
                                the_post_thumbnail( 'post-thumbnail' , array( 'class' => 'dsn-animate-webgl' ) );
                            endif;
                            ?>
                            <a href="<?php the_permalink() ?>" class="effect-ajax" data-dsn-ajax="list">
                                <div class="num">
                                    <small><?php echo droow_get_num( $index ); ?></small>
                                </div>
                                <h3><?php the_title() ?></h3>

                                <div class="project-type">
                                    <span><?php echo droow_post_category( ' | ' , droow_category_slug() , false , get_post( $myposts->ID ) ) ?></span>
                                </div>
                            </a>
                        </li>
                        <?php
                        $index++;
                    endwhile;
                endif; ?>
            </ul>
        </div>
    </div>
    <div class="nav-work-img-box" data-overlay="5"></div>
</div>