/* jshint devel:true */
/* global wpErpInv */
/* global wp */

;
(function ( $ ) {
    'use strict';

    var WeDevs_ERP_Invnetory = {

        /**
         * Initialize the events
         *
         * @return {void}
         */
        initialize: function () {
            $( 'body.accounting_page_erp-accounting-expense' ).on( 'change', '.inv-product-selection', this.change_product_in_expense_zone );
            $( 'body.accounting_page_erp-accounting-sales' ).on( 'change', '.inv-product-selection', this.change_product_in_sales_zone );
            $( 'body' ).on( 'click', '#product-import-button', this.importBtnClicked );
            $( 'body' ).on( 'change', '#erp-inv-product-import-input', this.uploadIProduct );

            $( '#purchase-dues' ).val( $( '#price_total' ).val() ); // at first total will be dues
            $( '#purchase-cash' ).keyup( function ( e ) {
                var cash = $( this ).val();
                $( '#purchase-dues' ).val( $( '#price_total' ).val() - cash );
            } );
            /*item report drop down control*/
            $( '#purchase-report-selection' ).change( function () {
                var reportType = $( this ).val();
                if ( reportType == 0 ) {
                    $( '#from_date' ).hide();
                    $( '#to_date' ).hide();
                } else if ( reportType == 1 ) {
                    $( '#from_date' ).show();
                    $( '#to_date' ).show();
                } else if ( reportType == 2 ) {
                    $( '#to_date' ).hide();
                } else if ( reportType == 3 ) {
                    $( '#from_date' ).show();
                    $( '#to_date' ).show();
                } else {
                    $( '#from_date' ).show();
                    $( '#to_date' ).show();
                }
            } );
            if ( $( '#item-reports-wrapper' ).length > 0 ) {
                var reportType = $( '#purchase-report-selection' ).val();
                if ( reportType == 0 ) {
                    $( '#from_date' ).hide();
                    $( '#to_date' ).hide();
                } else if ( reportType == 1 ) {
                    $( '#from_date' ).show();
                    $( '#to_date' ).show();
                } else if ( reportType == 2 ) {
                    $( '#to_date' ).hide();
                } else if ( reportType == 3 ) {
                    $( '#from_date' ).show();
                    $( '#to_date' ).show();
                } else {
                    $( '#from_date' ).show();
                    $( '#to_date' ).show();
                }
            }
            //hide import product button in product detail page
            if ( $( '.post-type-erp_inv_product form#post' ).length > 0 ) {
                $( '#product-import-button' ).hide();
            }
        },

        change_product_in_expense_zone: function () {
            // get unit purchase price and set to unit price cell
            var self = $( this );
            var postid = $( this ).val();
            $.get( ajaxurl, {post_id: postid, action: 'erp-inv-get-product-cost-price'}, function ( response ) {
                self.parent().parent().find( '.col-account select' ).val( response.data.purchase_account ).change();
                self.parent().parent().find( '.col-description input' ).val( response.data.product_content );
                self.parent().parent().find( 'input.line_price' ).val( response.data.cost_price );
                if ( postid == '' ) {
                    self.parent().parent().find( '.col-tax select' ).val( '-1' ).change();
                } else {
                    self.parent().parent().find( '.col-tax select' ).val( response.data.tax_on_purchase ).change();
                }
            } );
        },

        change_product_in_sales_zone: function () {
            // get unit sales price and set to unit price cell
            var self = $( this );
            var postid = $( this ).val();

            $.get( ajaxurl, {post_id: postid, action: 'erp-inv-get-product-sale-price'}, function ( response ) {
                self.parent().parent().find( '.col-account select' ).val( response.data.sales_account ).change();
                self.parent().parent().find( '.col-description input' ).val( response.data.product_content );
                self.parent().parent().find( 'input.line_price' ).val( response.data.sale_price );
                if ( postid == '' ) {
                    self.parent().parent().find( '.col-tax select' ).val( '-1' ).change();
                } else {
                    self.parent().parent().find( '.col-tax select' ).val( response.data.tax_on_sales ).change();
                }
            } );
        },

        importBtnClicked: function ( e ) {
            e.preventDefault();
            $( 'body #erp-inv-product-import-input' ).trigger( 'click' );
        },

        uploadIProduct: function ( e ) {
            e.preventDefault();

            // show spinner
            $( '.spinner' ).css( {'visibility': 'visible'} );

            var impFile = e.target.files[ 0 ],
                data = new FormData(),
                form = $( this ).parents( 'form' );

            data.append( 'imp', impFile );
            data.append( 'action', 'erp-inv-import-product' );
            data.append( '_wpnonce', wpErpInv.nonce );

            wp.ajax.send( {
                data: data,
                cache: false,
                processData: false,
                contentType: false,
                success: function () {
                    form[ 0 ].reset();
                    $( '.spinner' ).css( {'visibility': 'hidden'} );
                    swal( {
                        'title': 'Congrats',
                        'text': 'Product(s) added successfully',
                        'type': 'success',
                        'timer': 3000
                    } );
                    location.reload();
                },
                error: function ( error ) {
                    form[ 0 ].reset();
                    $( '.spinner' ).css( {'visibility': 'hidden'} );
                    swal( {
                        'title': 'Oops!',
                        'text': error,
                        'type': 'error'
                    } );
                }
            } );
        },

        initTimePicker: function () { // init timepicker
            $( '.erp-time-field' ).timepicker();
        },

        initDateField: function () { // date picker
            $( '.erp-date-field' ).datepicker( {
                dateFormat: 'yy-mm-dd',
                changeMonth: true,
                changeYear: true,
                yearRange: '-100:+0'
            } );
        }

    };

    $( function () {
        WeDevs_ERP_Invnetory.initialize();
    } );
})( jQuery );