<?php
$attr    = get_query_var( 'attr' );
$content = get_query_var( 'content' );
$title   = miao_acf_option_array( $attr , 'title' , 'What We Do' );
$column  = miao_acf_option_array( $attr , 'dsn-column' , '10' );

$index  = 1;
$active = 'active';

?>


<section class="services" data-aos="fade-up">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-lg-<?php echo esc_attr( $column ) ?>">
                <div class="heading">
                    <h2>
                        <?php echo esc_html( $title ) ?>
                        <span><img src="<?php echo MIAO__PLUGIN_DIR_URL ?>/assets/img/arrow-down.png" alt=""/></span>
                    </h2>
                </div>

                <div class="services-wp">
                    <?php if ( count( $content ) ): ?>

                        <div class="box-title-services">
                            <ul>
                                <?php
                                foreach ( $content as $con ):
                                    if ( $index > 1 )
                                        $active = '';
                                    printf( '<li id="tabs-%s" class="link-click %s">%s</li>' , $index , $active , miao_acf_option_array( $con , 'title' ) );
                                    $index++;
                                endforeach; ?>
                            </ul>
                        </div>

                        <div class="content">
                            <?php
                            $index           = 1;
                            foreach ( $content as $con ):
                                $num = miao_get_num( $index );
                                $title       = miao_acf_option_array( $con , 'title' );
                                $description = miao_acf_option_array( $con , 'description' );
                                $list        = miao_acf_option_array( $con , 'list' , array() );

                                ?>
                                <div id="tabs-<?php echo esc_attr( $index ) ?>-content" class="services-item-info">
                                    <h4>
                                        <span><?php echo esc_html( $num ); ?> /</span> <?php echo esc_html( $title ) ?>
                                    </h4>
                                    <?php if ( $description ) printf( '<p >%s</p>' , $description ); ?>
                                    <?php if ( count( $list ) ): ?>
                                        <ul>
                                            <?php
                                            foreach ( $list as $item ):
                                                printf( '<li>%s</li>' , miao_acf_option_array( $item , 'item' ) );
                                            endforeach;
                                            ?>
                                        </ul>
                                    <?php endif; ?>
                                </div>
                                <?php $index++; endforeach; ?>


                        </div>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>
</section>
