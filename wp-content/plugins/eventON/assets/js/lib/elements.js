/**
 * EventON elements
 * version: 2.9
 */
jQuery(document).ready(function($){

// yes no button afterstatement hook
	$('body').on('evo_yesno_changed', function(event, newval, obj, afterstatement){
		console.log( newval);
		if(newval == 'yes'){
			obj.closest('.evo_elm_row').next().show();
		}else{
			obj.closest('.evo_elm_row').next().hide();
		}
	});
// self hosted tooltips
	$('body').find('.ajdethistooltip').each(function(){
		tipContent = $(this).find('.ajdeToolTip em').html();
		toolTip = $(this).find('.ajdeToolTip');
		classes = toolTip.attr('class').split('ajdeToolTip');
		toolTip.remove();
		$(this).append('<em>' +tipContent +'</em>').addClass(classes[1]);
	});
// Select in a row	 
	 $('.evo_row_select').on('click','span.opt',function(){
		var V = $(this).attr('value');
		var O = $(this);
		var P = O.closest('p');
		P.find('span.opt').removeClass('select');
		O.addClass('select');

		P.find('input').val( V );

		$('body').trigger('evo_row_select_selected',[P, V]);			
	});
// lightbox select
	$('body').on('click','.evo_elm_lb_field input',function(event){
		const lb = $(this).closest('.evo_elm_lb_select');
		$('body').find('.evo_elm_lb_window.show').removeClass('show').fadeOut(300);
		lb.find('.evo_elm_lb_window').show().delay(100).queue(function(){
		    $(this).addClass("show").dequeue();
		});
	});

	// close lightbox
		$(window).on('click', function(event) {
			if( !($(event.target).hasClass('evo_elm_lb_field_input')) )
				$('body').find('.evo_elm_lb_window').removeClass('show').fadeOut(300);
		});
		$(window).blur(function(){
			//$('body').find('.evo_elm_lb_window').removeClass('show').fadeOut(250);
		});

	// selecting options in lightbox select field
	$('body')
		.on('click','.eelb_in span',function(){
			const field = $(this).closest('.evo_elm_lb_select').find('input');
			if($(this).hasClass('select')){
				$(this).removeClass('select');
			}else{
				$(this).addClass('select');
			}

			var V = '';

			$(this).parent().find('span.select').each(function(){
				V += $(this).attr('value')+',';
			});

			field.val( V ).trigger('change');
			$('body').trigger('evo_elm_lb_option_selected',[ $(this), V]);
		})
		.on('click','.evo_elm_lb_window',function(event){
			event.stopPropagation();
		})
	;
});