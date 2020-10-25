<?php
$attr = get_query_var('attr');
$content = get_query_var('content');

$shortcode = new DroowShortCode($attr);

$num_col = $shortcode->number_columns();
$items = $shortcode->getItems($content);
?>
<?php
//--> show Caption  = show Image
if ($shortcode->showCaption()):?>
    <section
            class="our-services-2 <?php echo esc_attr($shortcode->className()) ?>" <?php echo esc_attr($shortcode->changeColor()) ?>>
        <div class="<?php echo esc_attr($shortcode->layout()) ?>">
            <?php $shortcode->getBlocTitle() ?>


            <div class="row">
                <?php
                if (count($items)):
                    foreach ($items as $item):
                        $shortcode = new DroowShortCode($item);
                        ?>
                        <div class="col-md-<?php echo esc_attr($num_col) ?>">
                            <div class="services-item">
                                <div class="corner corner-top"></div>
                                <div class="corner corner-bottom"></div>
                                <?php

                                if ($id = $shortcode->Image())
                                    printf('<div class="icon" >%s</div>', wp_get_attachment_image($id));

                                if ($title = $shortcode->Title())
                                    printf('<div class="services-header"><h3 data-dsn-animate="%s">%s</h3></div>', $shortcode->titleAnimate(), esc_html($title));

                                if ($description = $shortcode->Description())
                                    printf('<p data-dsn-animate="%s">%s</p>', $shortcode->DescriptionAnimate(), esc_html($description));


                                ?>

                            </div>
                        </div>
                    <?php
                    endforeach;
                endif;
                ?>
            </div>
        </div>
    </section>
<?php else: ?>
    <section
            class="our-services <?php echo esc_attr($shortcode->className()) ?>" <?php echo esc_attr($shortcode->changeColor()) ?>>
        <div class="<?php echo esc_attr($shortcode->layout()) ?>">
            <?php $shortcode->getBlocTitle() ?>
            <div class="row">
                <?php
                if (count($items)):
                    foreach ($items as $item):
                        $shortcode = new DroowShortCode($item);
                        ?>
                        <div class="col-md-6">
                            <div class="services-item">
                                <div class="line-before"></div>
                                <?php
                                if ($title = $shortcode->Title())
                                    printf('<h4 class="subtitle" data-dsn-animate="%s">%s</h4>', $shortcode->titleAnimate(), esc_html($title));
                                if ($description = $shortcode->Description())
                                    printf('<p data-dsn-animate="%s">%s</p>', $shortcode->DescriptionAnimate(), esc_html($description));
                                ?>
                            </div>
                        </div>
                    <?php
                    endforeach;
                endif;
                ?>
            </div>
        </div>
    </section>
<?php endif; ?>
