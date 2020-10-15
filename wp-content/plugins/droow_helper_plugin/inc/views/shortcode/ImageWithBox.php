<?php
$attr = get_query_var('attr');
$content = get_query_var('content');

$shortCode = new DroowShortCode($attr);

if (gettype($shortCode->Image()) !== 'array')
    $ids = explode(',', $shortCode->Image());
else
    $ids = array();

$title = $shortCode->Title();
$description = $shortCode->Description();
$url_link = $shortCode->UrlLink();


$arrayImage = array_filter(array(
    'class' => $shortCode->parallaxType(),
    'data-dsn-scale' => $shortCode->scale(),
    'data-dsn-y' => $shortCode->transY(),
    'style' => $shortCode->style()

));

$moveSection = '';


if ($content === 'move' || !$content) {
    $moveSection = 'data-dsn-grid=move-section';
    $content = '';
}


foreach ($ids as $id): if ($id && wp_get_attachment_image_src($id)):
    ?>


    <div class="box-seat <?php echo esc_attr($content . ' ' . $shortCode->className()) ?>" <?php echo esc_attr($shortCode->changeColor()) ?>>
        <div class="<?php echo esc_attr($shortCode->layout()) ?>">

            <div class="inner-img" data-dsn-grid="move-up"
                 data-overlay="<?php echo esc_attr($shortCode->overlay(0)) ?>"
                <?php echo esc_attr($shortCode->triggerhook()) ?> <?php echo esc_attr($shortCode->duration()) ?>>

                <?php
                echo wp_get_attachment_image($id, $shortCode->sizeImage('full'), false, $arrayImage);
                ?>
            </div>

            <div class="pro-text" <?php echo esc_attr($moveSection) ?>>
                <?php if ($shortCode->TitleCover()): ?>
                    <div class="title-cover" data-dsn-grid="move-section" data-dsn-opacity="0.1"
                         data-dsn-duration="170%" data-dsn-move="0%">
                        <?php echo esc_html($shortCode->TitleCover()); ?>
                    </div>
                <?php endif; ?>
                <?php
                if ($title)
                    printf('<h3 %s>%s</h3>', $shortCode->titleAnimate(), $title);
                if ($description)
                    printf('<p class="span-small" %s>%s</p>', $shortCode->DescriptionAnimate(), $description);

                if ($url_link): ?>
                    <div class="link-custom" data-dsn-animate="up">
                        <a class="image-zoom effect-ajax" href="<?php echo esc_url($url_link) ?>"
                           data-dsn="parallax">
                            <span><?php echo esc_html($shortCode->TitleLink('Learn More')) ?></span>
                        </a>
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </div>


<?php endif; endforeach; ?>
