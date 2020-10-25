!function(a){a(document).ready(function(){
	a(document).on("liveEditor",function (e) {
		a('.elementor-widget-icon .elementor-icon').each(function () {
			var dataid = a(this).parent().parent().parent().attr('data-id');
			if(!a('.elementor-element-'+dataid).hasClass('element-locked')){
				a(this).addClass('changeIcon');
				a('.elementor-element-'+dataid).addClass('element-border');
				a(this).attr('rel',dataid);
			}
		})
	});
	
})}(jQuery);
