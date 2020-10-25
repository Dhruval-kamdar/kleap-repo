var map;
var infowindow;
var autocomplete;
var selectedTypes = [];

var getProperties = {

	init: function () {
		var scope = jQuery(document).find('.single_property_page');

		getProperties.addContactFromAjax(scope);
		getProperties.addClassOnResize();
		getProperties.socialSharingButtons();
		getProperties.tabsSlider();
		getProperties.schoolChecker(scope);
	},
	addContactFromAjax: function (scope) {

		var settings = scope.data('settings');

		if(settings){
			var agent_email = '';
			if(settings['agent_email']){
				agent_email = settings['agent_email'];
			}

			jQuery('#btn_send_agent').on('click', function (e) {
				e.preventDefault();
				getProperties.showLoading();
				var ajaxurl = ElementorExtensionsFrontendConfig.ajaxurl;
				jQuery.ajax({
					url: ajaxurl,
					data: {
						'action': 'eeMbAgentMailSendAjax',
						'name': document.getElementById('name').value,
						'email': document.getElementById('email').value,
						'message': document.getElementById('message').value,
						'phone': document.getElementById('telephone').value,
						'property_link': document.getElementById('property_link').value,
						'sendto': agent_email,
					}, 
					method: 'POST',
					success: function (data) {
						if (data.success) {
							jQuery(document).find('.form_error').text('');
							jQuery('#btn_send_agent').after('<span class="form_success"><?php echo (get_field("mail_success","option")) ? get_field("mail_success","option") : "You message has been sent."; ?></span>');
							jQuery('#agent_contact_form').trigger("reset");
						} else {
							jQuery(document).find('.form_error').remove();
							jQuery.each(data.error, function (index, value) {
								jQuery('#' + index).after('<span class="form_error">' + value + '</span>');
							});
						}
						getProperties.hideLoading();
					},
					error: function () {
						alert('Something went wrong, please contact administrator');
						getProperties.hideLoading();
					}
				});
			});
		}
	},
	showLoading: function () {
		jQuery(document).find('.elementor_siteset_loading_overlay.property_search').show();
	},
	hideLoading: function () {
		jQuery(document).find('.elementor_siteset_loading_overlay.property_search').hide();
	},
	addClassOnResize: function(){
		var $window = jQuery(window),
		$html = jQuery('body');

		$window.resize(function resize() {
			if ($window.width() < 1024) {
				return $html.addClass('property-search-page');
			}

			$html.removeClass('property-search-page');
		}).trigger('resize');
	},
	socialSharingButtons:function(){
		(function (d, s, id) {
			var js, fjs = d.getElementsByTagName(s)[0];
			if (d.getElementById(id)) return;
			js = d.createElement(s);
			js.id = id;
			js.src = "https://connect.facebook.net/en_US/sdk.js#xfbml=1&version=v3.0";
			fjs.parentNode.insertBefore(js, fjs);
		}(document, 'script', 'facebook-jssdk'));

		! function (d, s, id) {
			var js, fjs = d.getElementsByTagName(s)[0],
				p = /^http:/.test(d.location) ? 'http' : 'https';
			if (!d.getElementById(id)) {
				js = d.createElement(s);
				js.id = id;
				js.src = p + '://platform.twitter.com/widgets.js';
				fjs.parentNode.insertBefore(js, fjs);
			}
		}(document, 'script', 'twitter-wjs');
	},
	tabsSlider:function(){
		/*jQuery for tabs*/
		jQuery('ul.tabs li').click(function () {
			var tab_id = jQuery(this).attr('data-tab');

			jQuery('ul.tabs li').removeClass('current');
			jQuery('.tab-content').removeClass('current');

			jQuery(this).addClass('current');
			jQuery("#" + tab_id).addClass('current');
		});

		/*@ Slick slider*/
		var slides_counter = jQuery('.slider-for > div.slider_item').length;
		if( slides_counter > 0 ){

			//custom code
			var slideCount = jQuery('.slideCount');
			jQuery('.slider-for,.slider-nav').on('init reInit afterChange', function (event, slick, currentSlide, nextSlide) {
				//currentSlide is undefined on init -- set it to 0 in this case (currentSlide is 0 based)
				var slider_caption = jQuery('.slick-current').attr('data-image-caption');
				if (slider_caption) {
					jQuery('#slider_caption_text').text(slider_caption);
				}
				var i = (currentSlide ? currentSlide : 0) + 1;
				jQuery('.slideCount').html('<span class="slideCountItem">' + i + '</span> ' + 'of' + ' <span class="slideCountAll">' + slick.slideCount + '</span>');
			});

			jQuery('.slider-for').slick({
				slidesToShow: 1,
				slidesToScroll: 1,
				arrows: true,
				fade: true,
				asNavFor: '.slider-nav',
				slide: '.slider_item',
				adaptiveHeight: true,
			});

			jQuery('.slider-nav').slick({
				slidesToShow: 5, //(slides_counter > 5) ? (6 - 1) : (slides_counter == 1) ? 1 : (slides_counter - 1),
				slidesToScroll: 1,
				asNavFor: '.slider-for',
				dots: false,
				arrows: true,
				centerMode: false,
				focusOnSelect: true,
			});

			jQuery("#btn_enlarge").click(function () {
				var photos = new Array();

				jQuery(".slider_item").each(function () {
					href = jQuery(this).children('img').attr("src");
					photos.push({
						'src': href,
					})
				});

				jQuery.fancybox.open(photos, {
					loop: true,
					hash: "test"
				});
			});
		}
	},
	schoolChecker: function(scope){
		var settings = scope.data('settings');
		
		var address = school_checker = '';
		if(settings['addresses']){
			address = settings['addresses']['address'];
		}

		if(settings['school_checker_tab']){
			school_checker = settings['school_checker_tab'];
		}

		if(address && school_checker !== 'yes'){
			scope.find('.school_checker_tab').on('click', function(){
				getProperties.renderMap(address);
			});
		}
	},
	renderMap: function(data_address) {
		// Get the user defined values
		var address = data_address;
		var radius = 2 * 1000;

		// get the selected type
		selectedTypes = ['school'];

		var geocoder = new google.maps.Geocoder();
		var selLocLat = 0;
		var selLocLng = 0;

		geocoder.geocode({
			'address': address
		}, function (results, status) {

			if (status === 'OK') {

				selLocLat = results[0].geometry.location.lat();
				selLocLng = results[0].geometry.location.lng();

				var pyrmont = new google.maps.LatLng(selLocLat, selLocLng);

				map = new google.maps.Map(document.getElementById('map'), {
					center: pyrmont,
					zoom: 14
				});

				var request = {
					location: pyrmont,
					radius: radius,
					types: selectedTypes
				};

				infowindow = new google.maps.InfoWindow();

				var service = new google.maps.places.PlacesService(map);
				service.nearbySearch(request, getProperties.mapCallback);
			} else {
				alert('Geocode was not successful for the following reason: ' + status);
			}
		});
	},
	mapCallback: function(results, status) {

		if (status == google.maps.places.PlacesServiceStatus.OK) {
			for (var i = 0; i < results.length; i++) {
				getProperties.createMarker(results[i], results[i].icon);
			}
		}
	},
	createMarker: function(place, icon) {
		var placeLoc = place.geometry.location;

		var marker = new google.maps.Marker({
			map: map,
			position: place.geometry.location,
			icon: {
				url: icon,
				scaledSize: new google.maps.Size(40, 40) // pixels
			},
			animation: google.maps.Animation.DROP
		});

		google.maps.event.addListener(marker, 'click', function () {
			infowindow.setContent(place.name + '<br>' + place.vicinity);
			infowindow.open(map, this);
		});
	}
}
getProperties.init();
