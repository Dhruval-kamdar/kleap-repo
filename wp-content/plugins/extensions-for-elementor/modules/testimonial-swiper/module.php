<?php
namespace ElementorExtensions\Modules\TestimonialSwiper;

use ElementorExtensions\Base\Module_Base;

if ( ! defined( 'ABSPATH' ) ) exit; 

class Module extends Module_Base {

	public function get_name() {
		return 'ee-mb-testimonial-swiper';
	}

	public function get_widgets() {
		return [
			'Testimonial_Swiper',
		];
	}
}
