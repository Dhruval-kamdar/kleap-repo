;(function($) {
    $(document).ready(function() {

        var selectedPaymentGateway;

        $('.payment-options-button').on('click', function(e) {
            e.preventDefault();

            if ( $('.payment-methods').is(':hidden') ) {
                $('.payment-methods').slideDown();
                $('.payment-options-button').hide();
            }
        });

        // payment gateway selection
        $('ul.erp-pg-payment-gateways').on('click', 'input[type=radio]', function(e) {
            $('.erp-pg-payment-instruction').slideUp(250);

            var el = $(this).parents('li').find('.erp-pg-payment-instruction');
            el.slideDown(250);
            selectedPaymentGateway = el.closest('li').find('input[type=radio]').val();
        });

        if( !$('ul.erp-pg-payment-gateways li').find('input[type=radio]').is(':checked') ) {
            var el = $('ul.erp-pg-payment-gateways li').first().find('input[type=radio]');
            el.click();
        } else {
            var el = $('ul.erp-pg-payment-gateways li').find('input[type=radio]:checked');
            el.parents('li').find('.erp-pg-payment-instruction').slideDown(250);
        }
    });
})(jQuery);