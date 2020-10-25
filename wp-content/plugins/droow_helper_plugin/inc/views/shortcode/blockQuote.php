<?php
$attr    = get_query_var( 'attr' , array( 'title' => '' ) );
$content = get_query_var( 'content' );

$title     = $attr[ 'title' ];
$className = miao_acf_option_array( $attr , 'className' );


?>

<div class="dsn-quote <?php echo esc_attr( $className ) ?>">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-lg-10">
                <div class="quote-content text-center">
                    <?php printf( '<h4 data-dsn-animate="text">%s</h4>' , $content ) ?>

                    <?php if ( $title ) printf( '<span class="mt-30">%s</span>' , $title ) ?>
                </div>
            </div>
        </div>
    </div>
</div>
