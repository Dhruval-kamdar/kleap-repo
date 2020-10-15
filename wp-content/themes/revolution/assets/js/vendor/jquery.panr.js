/*
 	panr - v0.0.1
 	jQuery plugin for zoom & pan elements on mousemove
	by Robert Bue (@robert_bue)

	Powered by the Greensock Tweening Platform
	http://www.greensock.com
	Greensock License info at http://www.greensock.com/licensing/

 	Dual licensed under MIT and GPL.
 */

;(function ( $, window, document, undefined ) {

	// Create the defaults once
	var pluginName = "panr",
		defaults = {
			moveTarget: false,
			sensitivity: 30,
			scale: false,
			scaleOnHover: true,
			scaleTo: 1.1,
			scaleDuration: .25,
			panY: true,
			panX: true,
			panDuration: 1.25,
			resetPanOnMouseLeave: true,
			onEnter: function(){},
			onLeave: function(){}
		};

	// The actual plugin constructor
	function Plugin ( element, options ) {
		this.element = element;
		this.settings = $.extend( {}, defaults, options );
		this._defaults = defaults;
		this._name = pluginName;
		this.init();
	}

	Plugin.prototype = {
		init: function () {

			var settings = this.settings,
			target = $(this.element),
			w = target.width(),
			h = target.height(),
			targetWidth = target.width() - settings.sensitivity,
			cx = (w-targetWidth)/targetWidth,
			x,
			y,
			panVars,
			xPanVars,
			yPanVars,
			mouseleaveVars = { scale: 1.005, x: 0, y: 0, duration: settings.scaleDuration },
			video = target.parents('.portfolio-holder').find('.thb-portfolio-video');

			target = video.length ? video : target;
			if ( settings.scale || (!settings.scaleOnHover && settings.scale) ) {
				gsap.set(target, { scale: settings.scaleTo });

			}

			// moveTarget
			if ( jQuery.type(settings.moveTarget) === "string" ) {
				settings.moveTarget = $(this.element).parent(settings.moveTarget);
			}

			// If no target provided we'll use the hovered element
			if ( !settings.moveTarget ) {
				settings.moveTarget = $(this.element);
			}

			settings.moveTarget.on('mousemove', function(e){

				x = e.pageX - target.offset().left; // mouse x coordinate relative to the container
				y = e.pageY - target.offset().top; // mouse x coordinate relative to the container

				if ( settings.panX ) {
					xPanVars = { x: -cx*x };
				}

				if ( settings.panY ) {
					yPanVars = { y: -cx*y };
				}

				panVars = $.extend({}, xPanVars, yPanVars, { duration: settings.panDuration });

				// Pan element
				gsap.to(target, panVars);

			});

			// On mouseover
			settings.moveTarget.on('mouseenter', function(e){

				if ( settings.scaleOnHover ) {
					// Scale up element
					gsap.to(target, { duration: settings.scaleDuration, scale: settings.scaleTo });

				}

				settings.onEnter(target);
			});



			settings.moveTarget.on('mouseleave', function(e){
				// Reset element
				gsap.killTweensOf(target);
				gsap.to(target, mouseleaveVars);

			});
		}
	};

	$.fn[ pluginName ] = function ( options ) {
		return this.each(function() {
			if ( !$.data( this, "plugin_" + pluginName ) ) {
				$.data( this, "plugin_" + pluginName, new Plugin( this, options ) );
			}
		});
	};

})( jQuery, window, document );