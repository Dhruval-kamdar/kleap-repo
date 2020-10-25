!function(el){el(document).ready(function(){
	
	//run counter 
	el(document).find('.elementor-widget-cep-counter .elementor-counter-number').each(function(){
		
			var number = el(this),
				data = number.data();	
			var decimalDigits = data.toValue.toString().match(/\.(.*)/);
			if (decimalDigits) {
				data.rounding = decimalDigits[1].length;
			}
			number.numerator(data);

	});
	
	
	//run progress bar
	el(document).find('.elementor-widget-cep-progress .elementor-progress-bar').each(function(){
		var progressbar = el(this);
		progressbar.css('width', progressbar.data('max') + '%');
	});
	
	
	    var ProgressBar = function ($scope, $) {

			var $progressBar             = $scope.find('.eael-progress-bar-container').eq(0),
			$layout = maybe_note_undefined($progressBar, "layout"),
			$id = maybe_note_undefined($progressBar, "id"),
			$number = maybe_note_undefined($progressBar, "number"),
			$class = '.elementor-element-' + $scope.data('id'),
			$line_stroke_color = maybe_note_undefined($progressBar, "line-stroke-color"),
			$line_stroke_width = maybe_note_undefined($progressBar, "line-stroke-width"),
			$line_stroke_trail_color = maybe_note_undefined($progressBar, "line-stroke-trail-color"),
			$line_stroke_trail_width = maybe_note_undefined($progressBar, "line-stroke-trail-width"),
			$line_direction = maybe_note_undefined($progressBar, "line-direction"),

			$fan_stroke_color = maybe_note_undefined($progressBar, "fan-stroke-color"),
			$fan_stroke_width = maybe_note_undefined($progressBar, "fan-stroke-width"),
			$fan_stroke_trail_color = maybe_note_undefined($progressBar, "fan-stroke-trail-color"),
			$fan_stroke_trail_width = maybe_note_undefined($progressBar, "fan-stroke-trail-width"),
			$fan_direction = maybe_note_undefined($progressBar, "fan-direction"),
			
			$circle_stroke_color = maybe_note_undefined($progressBar, "circle-stroke-color"),
			$circle_stroke_width = maybe_note_undefined($progressBar, "circle-stroke-width"),
			$circle_stroke_trail_color = maybe_note_undefined($progressBar, "circle-stroke-trail-color"),
			$circle_stroke_trail_width = maybe_note_undefined($progressBar, "circle-stroke-trail-width"),
			$circle_direction = maybe_note_undefined($progressBar, "circle-direction"),
            
			$bubble_circle_color = maybe_note_undefined($progressBar, "bubble-circle-color"),
			$bubble_bg_color = maybe_note_undefined($progressBar, "bubble-bg-color"),
			$bubble_circle_width = maybe_note_undefined($progressBar, "bubble-circle-width"),
			$bubble_direction = maybe_note_undefined($progressBar, "bubble-direction"),

			$rainbow_stroke_width = maybe_note_undefined($progressBar, "rainbow-stroke-width"),
			$rainbow_stroke_trail_width = maybe_note_undefined($progressBar, "rainbow-stroke-trail-width"),
			$rainbow_color_one = maybe_note_undefined($progressBar, "rainbow-color-one"),
			$rainbow_color_two = maybe_note_undefined($progressBar, "rainbow-color-two"),
			$rainbow_color_three = maybe_note_undefined($progressBar, "rainbow-color-three"),
			$rainbow_color_four = maybe_note_undefined($progressBar, "rainbow-color-four"),
			$rainbow_color_five = maybe_note_undefined($progressBar, "rainbow-color-five"),
			$rainbow_direction = maybe_note_undefined($progressBar, "rainbow-direction");

             
            if('rainbow' == $layout){
                var bar = new ldBar($class + ' .inside-progressbar', {
                    "type": 'stroke', 
                    "path": 'M0 10L100 10', 
                    "stroke": 'data:ldbar/res,gradient(0,1,'+ $rainbow_color_one +','+ $rainbow_color_two +','+ $rainbow_color_three +','+ $rainbow_color_four +','+ $rainbow_color_five +')',
                    "aspect-ratio": 'none', 
                    "stroke-width": $rainbow_stroke_width,
                    "stroke-trail-width": $rainbow_stroke_trail_width,
                    "stroke-dir": $rainbow_direction
                  }).set($number);
            }
            else if('line' == $layout){
                var bar = new ldBar($class + ' .inside-progressbar', {
                    "type": 'stroke',
					"path": 'M0 10L100 10',
                    "stroke": $line_stroke_color,
                    "stroke-width": $line_stroke_width,
                    "stroke-trail": $line_stroke_trail_color,
                    "stroke-trail-width": $line_stroke_trail_width,
                    "aspect-ratio": 'none',
                    "stroke-dir": $line_direction 
                  }).set($number);
            }
            else if('fan' == $layout){
                var bar = new ldBar($class + ' .inside-progressbar', {
                    "type": 'stroke',
                    "path": 'M10 90A40 40 0 0 1 90 90',
                    "fill-dir": $fan_direction,
                    "fill":  $fan_stroke_color, 
                    "fill-background": $fan_stroke_trail_color, 
                    "fill-background-extrude": $fan_stroke_width, 
                    "stroke-dir": 'normal',
                    "stroke": $fan_stroke_color,
                    "stroke-width": $fan_stroke_width,
                    "stroke-trail": $fan_stroke_trail_color,
                    "stroke-trail-width": $fan_stroke_trail_width
                  }).set($number);
            }
            else if('circle' == $layout){
                var bar = new ldBar($class + ' .inside-progressbar', {
                    "type": 'stroke',
                    "path": 'M50 10A40 40 0 0 1 50 90A40 40 0 0 1 50 10',
                    "fill-dir": $circle_direction,
                    "fill":  $circle_stroke_color, 
                    "fill-background": $circle_stroke_trail_color, 
                    "fill-background-extrude": $circle_stroke_width, 
                    "stroke-dir": 'normal',
                    "stroke": $circle_stroke_color,
                    "stroke-width": $circle_stroke_width,
                    "stroke-trail": $circle_stroke_trail_color,
                    "stroke-trail-width": $circle_stroke_trail_width
                  }).set($number);
            }
            else if('bubble' == $layout){
                var bar = new ldBar($class + ' .inside-progressbar', {
                    "type": 'fill',
                    "path": 'M50 10A40 40 0 0 1 50 90A40 40 0 0 1 50 10',
                    "fill-dir": $bubble_direction,
                    "fill": 'data:ldbar/res,bubble('+ $bubble_bg_color +','+ $bubble_circle_color +')',
                    "pattern-size": $bubble_circle_width,
                    "fill-background": '#ddd',
                    "fill-background-extrude": 2,
                    "stroke-dir": 'normal',
                    "stroke": '#25b',
                    "stroke-width": '3',
                    "stroke-trail": '#ddd',
                    "stroke-trail-width": 0.5
                  }).set($number);
            }
		}
		
		function maybe_note_undefined($selector, $data_atts) {
		return ($selector.data($data_atts) !== undefined) ? $selector.data($data_atts) : '';
		}
	
		el(window).on('elementor/frontend/init', function () {

			if ( elementorFrontend.isEditMode() ) { isEditMode = true; }
			elementorFrontend.hooks.addAction('frontend/element_ready/cep-eael-progress-bar.default', ProgressBar);
		});
	
	
})}(jQuery);
