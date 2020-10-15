/*
	buildico Script
*/
(function($){
    "use strict";

    // Preloader
    $(window).on('load', function() {
        $('body').addClass('loaded');
    });

    $(document).ready(function(){

        //Sticky Header
            $(function() {
                var header = $("#header"),
                    height = header.height(),
                    yOffset = 0,
                    triggerPoint = height,
                    fixedHeader = $('#fixed-header');

                $(window).on( "scroll", function() {
                    yOffset = $(window).scrollTop();

                    if (yOffset >= triggerPoint) {
                        fixedHeader.addClass("fixed-header-active");
                    } else {
                        fixedHeader.removeClass("fixed-header-active");
                    }

                });
            });

        // Slick Nav Active
    	var menuSelector = $('.main-menu-top ul.main-menu');
    	menuSelector.slicknav({
            prependTo: '.main-menu-top .main-header-inner',
            label: '',
            closeOnClick: true
        });

        // Slick Nav Active
    	var fixedMenuSelector = $('.fixed-header ul.main-menu');
    	fixedMenuSelector.slicknav({
            prependTo: '.fixed-header .fixed-header-inner',
            label: '',
            closeOnClick: true
        });

        // Fullscreen Search Box
        $('#search-trigger, #fixed-header-search-trigger').on('click', function(event) {
            //event.preventDefault();
            $('#wt-search').addClass('open');
            $('#wt-search > form > input[type="text"]').focus();
        });

        $('#wt-search, #wt-search button.close').on('click keyup', function(event) {
            if (event.target == this || event.target.className == 'close' || event.keyCode == 27) {
                $(this).removeClass('open');
            }
        });

        // Blog Masonry Layout
        var grid = $('.blog-grid, .blog-items');
        grid.imagesLoaded( function() {
          grid.isotope({
                itemSelector: '.blog-box',
                layoutMode: 'masonry'
          });
        });

        /* Footer Widget Masonry */
        var widgetGrid = $('.footer-widgets');
        widgetGrid.isotope({
            itemSelector: '.footer-widget',
            layoutMode: 'masonry'
        });

        /* Project Carousel */
        $('#projectsingle-carousel').owlCarousel({
            loop: true,
            autoplay: true,
            smartSpeed: 500,
            items: 1,
            dots: true,
            nav: true,
            navText: ['<i class="ti-angle-left"></i>', '<i class="ti-angle-right"></i>']
        });

        /* Smooth Scrolling */
        $("a").on('click', function(event) {
            // Make sure this.hash has a value before overriding default behavior
            if (this.hash !== "") {
              // Prevent default anchor click behavior
              event.preventDefault();

              // Store hash
              var hash = this.hash;

              $('html, body').animate({
                scrollTop: $(hash).offset().top
              }, 800, function(){

                // Add hash (#) to URL when done scrolling (default click behavior)
                window.location.hash = hash;
              });
            } // End if
        });

        //Check to see if the window is top if not then display button
		var windowHeight = $(window).height();
        $(window).on( 'scroll', function(){
            if ($(this).scrollTop() > windowHeight+50) {
                $('#scroll-top').fadeIn();
            } else {
                $('#scroll-top').fadeOut();
            }
        });

        //Click event to scroll to top
        $('#scroll-top').on( 'click', function(){
            $('html, body').animate({scrollTop : 0},800);
            return false;
        });

    });

})(jQuery);
