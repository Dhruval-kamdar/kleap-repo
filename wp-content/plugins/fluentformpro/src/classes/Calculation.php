<?php
namespace FluentFormPro\classes;

if ( ! defined( 'ABSPATH' ) ) {
    exit; // Exit if accessed directly.
}

class Calculation
{
    public function enqueueScripts()
    {
        wp_enqueue_script('fluentform-advanced');
        wp_enqueue_script('math-expression-evaluator', FLUENTFORMPRO_DIR_URL.'public/libs/math-expression-evaluator.min.js', array(), '1.2.17');
    }
}