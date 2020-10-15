// Vue directive for Date picker
Vue.directive( 'datepicker', {
    params: [ 'datedisable' ],
    twoWay: true,
    bind: function () {
        var vm = this.vm;
        var key = this.expression;

        if ( this.params.datedisable == 'previous' ) {
            jQuery( this.el ).datepicker( {
                minDate: 0,
                dateFormat: 'yy-mm-dd',
                changeMonth: true,
                changeYear: true,
                yearRange: '-100:+0',
                onClose: function ( date ) {
                    vm.$set( key, date );
                }
            } );
        } else if ( this.params.datedisable == 'upcomming' ) {
            jQuery( this.el ).datepicker( {
                maxDate: 0,
                dateFormat: 'yy-mm-dd',
                changeMonth: true,
                changeYear: true,
                yearRange: '-100:+0',
                onClose: function ( date ) {
                    vm.$set( key, date );
                }
            } );
        } else {
            jQuery( this.el ).datepicker( {
                dateFormat: 'yy-mm-dd',
                changeMonth: true,
                changeYear: true,
                yearRange: '-100:+0',
                onClose: function ( date ) {
                    if ( date.match( /^(0?[1-9]|[12][0-9]|3[01])[\/\-\.](0?[1-9]|1[012])[\/\-\.]\d{4}$/ ) )
                        vm.$set( key, date );
                    else {
                        vm.$set( key, "" );
                    }
                }
            } );
        }
        ;
    },
    update: function ( val ) {
        jQuery( this.el ).datepicker( 'setDate', val );
    }
} );

if ( jQuery( '#purchase-reports-wrapper' ).length > 0 ) {
    var purchaseReportZone = new Vue( {
        el: '#purchase-reports-wrapper',

        data: {
            purchaseReportData: [],
            itemDetailReportData: [],
            from_date: '',
            to_date: ''
        },

        ready: function () {
            //this.getPurchaseReportData();
        },

        computed: {
            totalQuantity: function () {
                var tqty = 0;
                var item;
                for ( item in this.purchaseReportData ) {
                    tqty = tqty + parseInt( this.purchaseReportData[ item ].quantity );
                }
                return tqty;
            },
            totalAmount: function () {
                var line_total = 0.00;
                var item;
                for ( item in this.purchaseReportData ) {
                    line_total = line_total + parseFloat( this.purchaseReportData[ item ].line_total );
                }
                return line_total.toFixed( 2 );
            }
        },

        methods: {
            getPurchaseReportData: function () {
                jQuery( '#purchase-reports-wrapper .spinner' ).css( {'visibility': 'visible'} );
                var fromDate = this.from_date;
                var toDate = this.to_date;
                // set new csv url
                var get_base_url = jQuery( '#hidden-base-url' ).val();
                var current_url = get_base_url + '&from_date=' + fromDate + '&to_date=' + toDate;
                jQuery( '#csv-dl-link' ).attr( 'href', current_url );
                // hide all reports except default
                jQuery( '.inv-reports' ).hide();
                jQuery( '#default-report' ).show();

                jQuery.get( ajaxurl,
                    {
                        action: 'erp-inv-get-purchase-report',
                        f_date: fromDate,
                        t_date: toDate,
                        _wpnonce: wpErpInv.nonce
                    },
                    function ( response ) {
                        if ( response.success === true ) {
                            purchaseReportZone.$set( 'purchaseReportData', response.data );
                            jQuery( '#purchase-reports-wrapper .spinner' ).css( {'visibility': 'hidden'} );
                        }
                    }
                );
            }
        }
    } );
}

if ( jQuery( '#sales-reports-wrapper' ).length > 0 ) {
    var salesReportZone = new Vue( {
        el: '#sales-reports-wrapper',

        data: {
            salesReportData: [],
            candidateReportData: [],
            from_date: '',
            to_date: ''
        },

        ready: function () {
            //this.getSalesReportData();
        },

        computed: {

            totalQuantity: function () {
                var tqty = 0;
                var item;
                for ( item in this.salesReportData ) {
                    tqty = tqty + parseInt( this.salesReportData[ item ].quantity );
                }
                return tqty;
            },
            totalAmount: function () {
                var line_total = 0.00;
                var item;
                for ( item in this.salesReportData ) {
                    line_total = line_total + parseFloat( this.salesReportData[ item ].line_total );
                }
                return line_total.toFixed( 2 );
            }
        },

        methods: {
            getSalesReportData: function () {
                jQuery( '#sales-reports-wrapper .spinner' ).css( {'visibility': 'visible'} );
                var fromDate = this.from_date;
                var toDate = this.to_date;

                // set new csv url
                var get_base_url = jQuery( '#hidden-base-url' ).val();
                var jobid = jQuery( '#job-title' ).val();
                var current_url = get_base_url + '&from_date=' + fromDate + '&to_date=' + toDate;
                jQuery( '#csv-dl-link' ).attr( 'href', current_url );

                jQuery.get( ajaxurl,
                    {action: 'erp-inv-get-sales-report', f_date: fromDate, t_date: toDate, _wpnonce: wpErpInv.nonce},
                    function ( response ) {
                        if ( response.success === true ) {
                            salesReportZone.$set( 'salesReportData', response.data );
                            jQuery( '#sales-reports-wrapper .spinner' ).css( {'visibility': 'hidden'} );
                        }
                    }
                );
            }
        }

    } );
}

if ( jQuery( '#item-reports-wrapper' ).length > 0 ) {
    var itemReportZone = new Vue( {
        el: '#item-reports-wrapper',

        data: {
            itemDetailReportData: [],
            itemListReportData: [],
            itemSummaryReportData: [],
            from_date: '',
            to_date: ''
        },

        computed: {
            totalvalue: function(){
                var line_value = 0.00;
                var item;
                for ( item in this.itemListReportData ) {
                    line_value = line_value + parseFloat( this.itemListReportData[ item ].total_value );
                }
                return line_value.toFixed( 2 );
            },
            totalQoH: function(){
                var total_qoh = 0;
                var item;
                for ( item in this.itemListReportData ) {
                    total_qoh = total_qoh + parseInt( this.itemListReportData[ item ].quantity_on_hand );
                }
                return total_qoh;
            },
            totalOpeningBalance: function(){
                var total_ob = 0.00;
                var item;
                for ( item in this.itemSummaryReportData ) {
                    total_ob = total_ob + parseFloat( this.itemSummaryReportData[ item ].opening_balance );
                }
                return total_ob.toFixed(2);
            },
            totalPurchases: function(){
                var total_purchases = 0.00;
                var item;
                for ( item in this.itemSummaryReportData ) {
                    total_purchases = total_purchases + parseFloat( this.itemSummaryReportData[ item ].purchases );
                }
                return total_purchases.toFixed(2);
            },
            totalCOGS: function(){
                var total_COGS = 0.00;
                var item;
                for ( item in this.itemSummaryReportData ) {
                    total_COGS = total_COGS + parseFloat( this.itemSummaryReportData[ item ].COGS );
                }
                return total_COGS.toFixed(2);
            },
            totalClosingBalance: function(){
                var total_cb = 0.00;
                var item;
                for ( item in this.itemSummaryReportData ) {
                    total_cb = total_cb + parseFloat( this.itemSummaryReportData[ item ].closing_balance );
                }
                return total_cb.toFixed(2);
            },
            totalSales: function(){
                var total_ts = 0.00;
                var item;
                for ( item in this.itemSummaryReportData ) {
                    total_ts = total_ts + parseFloat( this.itemSummaryReportData[ item ].sales );
                }
                return total_ts.toFixed(2);
            }
        },

        methods: {
            getItemReportData: function () {
                jQuery( '#item-reports-wrapper .spinner' ).css( {'visibility': 'visible'} );
                var fromDate = this.from_date;
                var toDate = this.to_date;
                var reportType = jQuery( '#purchase-report-selection' ).val();
                var get_base_url;
                var current_url;

                if ( reportType == 0 ) {
                    jQuery( '#item-reports-wrapper .spinner' ).css( {'visibility': 'hidden'} );
                    jQuery( '.inv-reports' ).hide();

                } else if ( reportType == 1 ) {
                    jQuery( '.inv-reports' ).hide();
                    jQuery( '#inventory-item-detail-report' ).show();
                    // set new csv url
                    get_base_url = jQuery( '#hidden-base-url' ).val();
                    current_url = get_base_url + '&from_date=' + fromDate + '&to_date=' + toDate;
                    jQuery( '#csv-dl-link' ).attr( 'href', current_url );

                    jQuery.get( ajaxurl,
                        {
                            action: 'erp-inv-get-item-detail-report',
                            f_date: fromDate,
                            t_date: toDate,
                            _wpnonce: wpErpInv.nonce
                        },
                        function ( response ) {
                            if ( response.success === true ) {
                                itemReportZone.$set( 'itemDetailReportData', response.data );
                                jQuery( '#item-reports-wrapper .spinner' ).css( {'visibility': 'hidden'} );
                            }
                        }
                    );
                } else if ( reportType == 2 ) {
                    jQuery( '.inv-reports' ).hide();
                    jQuery( '#inventory-item-list-report' ).show();
                    // set new csv url
                    get_base_url = jQuery( '#hidden-base-url' ).val();
                    current_url = get_base_url + '&from_date=' + fromDate + '&to_date=' + toDate;
                    jQuery( '#csv-dl-link' ).attr( 'href', current_url );

                    jQuery.get( ajaxurl,
                        {
                            action: 'erp-inv-get-item-list-report',
                            f_date: fromDate,
                            _wpnonce: wpErpInv.nonce
                        },
                        function ( response ) {
                            if ( response.success === true ) {
                                itemReportZone.$set( 'itemListReportData', response.data );
                                jQuery( '#item-reports-wrapper .spinner' ).css( {'visibility': 'hidden'} );
                            }
                        }
                    );
                } else if ( reportType == 3 ) {
                    jQuery( '.inv-reports' ).hide();
                    jQuery( '#inventory-item-summary-report' ).show();
                    // set new csv url
                    get_base_url = jQuery( '#hidden-base-url' ).val();
                    current_url = get_base_url + '&from_date=' + fromDate + '&to_date=' + toDate;
                    jQuery( '#csv-dl-link' ).attr( 'href', current_url );

                    jQuery.get( ajaxurl,
                        {
                            action: 'erp-inv-get-item-summary-report',
                            f_date: fromDate,
                            t_date: toDate,
                            _wpnonce: wpErpInv.nonce
                        },
                        function ( response ) {
                            if ( response.success === true ) {
                                itemReportZone.$set( 'itemSummaryReportData', response.data );
                                jQuery( '#item-reports-wrapper .spinner' ).css( {'visibility': 'hidden'} );
                            }
                        }
                    );
                }
            }
        }
    } );
}