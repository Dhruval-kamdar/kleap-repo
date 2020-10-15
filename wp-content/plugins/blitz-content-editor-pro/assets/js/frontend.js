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
	

})}(jQuery);
