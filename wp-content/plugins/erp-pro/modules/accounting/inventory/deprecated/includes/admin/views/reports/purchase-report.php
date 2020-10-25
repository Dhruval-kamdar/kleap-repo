<div class="wrap erp-candidate-detail" xmlns:v-on="http://www.w3.org/1999/xhtml">
    <h1><?php _e( 'Inventory Report', 'erp-pro' ); ?></h1>

    <form method="post">
        <div id="dashboard-widgets-wrap" class="erp-grid-container">
            <div class="row">
                <div class="col-6">
                    <div class="postbox">
                        <div class="inside" style="margin-bottom:0;margin-top:0;overflow-y:hidden;padding-bottom:0;padding-left:0;min-height:500px;">
                            <div id="purchase-reports-wrapper" class="information-container">
                                <div id="candidate-overview-zone">
                                    <h1 style="border-bottom:1px solid #e1e1e1;padding-bottom:15px;margin-bottom:15px;">
                                        <i class="fa fa-bar-chart-o">&nbsp;</i><?php _e( 'Purchase Reports', 'erp-pro' ); ?>
                                    </h1>
                                    <input type="text" id="from_date" v-datepicker v-model="from_date" name="from_date" value="" placeholder="From date" class="erp-date-field">
                                    <input type="text" id="to_date" v-datepicker v-model="to_date" name="to_date" value="" placeholder="To date" class="erp-date-field">
                                    <button class="button" v-on:click.prevent="getPurchaseReportData"><?php _e( 'Search', 'erp-pro' ); ?></button>
                                    <span class="spinner"></span>

                                    <div id="report-csv-link">
                                        <input type="hidden" id="hidden-base-url" value="<?php echo admin_url( 'admin.php?page=erp-accounting&section=reports&type=purchase-report&func=purchase-report-csv' ); ?>">
                                        <a id="csv-dl-link" class="necessary-link dl-link" href="<?php echo $_SERVER['REQUEST_URI'] . '&func=purchase-report-csv'; ?>">
                                            <i class="fa fa-download">&nbsp;</i><?php _e( 'Export to CSV', 'erp-pro' ); ?>
                                        </a>
                                    </div>

                                    <table id="default-report" class="inv-reports wp-list-table widefat fixed striped table-rec-reports">
                                        <thead>
                                        <tr>
                                            <th><?php _e( 'Date', 'erp-pro' ); ?></th>
                                            <th><?php _e( 'Reference No', 'erp-pro' ); ?></th>
                                            <th><?php _e( 'Vendor', 'erp-pro' ); ?></th>
                                            <th><?php _e( 'Tax Rate %', 'erp-pro' ); ?></th>
                                            <th><?php _e( 'Tax Amount', 'erp-pro' ); ?></th>
                                            <th><?php _e( 'Discount %', 'erp-pro' ); ?></th>
                                            <th><?php _e( 'Quantity', 'erp-pro' ); ?></th>
                                            <th><?php _e( 'Unit Price', 'erp-pro' ); ?></th>
                                            <th><?php _e( 'Amount', 'erp-pro' ); ?></th>
                                        </tr>
                                        </thead>
                                        <tbody class="not-loaded">
                                        <tr v-for="rdata in purchaseReportData">
                                            <td class="align-center">{{rdata.purchase_date}}</td>
                                            <td class="align-right">{{rdata.ref_no}}</td>
                                            <td class="align-right">{{rdata.vendor_name}}</td>
                                            <td class="align-right">{{rdata.tax_rate}}</td>
                                            <td class="align-right">{{rdata.tax_amount}}</td>
                                            <td class="align-right">{{rdata.discount}}</td>
                                            <td class="align-right">{{rdata.quantity}}</td>
                                            <td class="align-right">{{rdata.unit_price}}</td>
                                            <td class="align-right">{{rdata.line_total}}</td>
                                        </tr>
                                        <tr>
                                            <td colspan="3" class="align-right"><?php _e( 'Total :', 'erp-pro' ); ?></td>
                                            <td class="align-right"></td>
                                            <td class="align-right"></td>
                                            <td class="align-right"></td>
                                            <td class="align-right">{{totalQuantity}}</td>
                                            <td class="align-right"></td>
                                            <td class="align-right">{{totalAmount}}</td>
                                        </tr>
                                        </tbody>
                                    </table>

                                </div>
                            </div>
                        </div>
                        <!-- inside -->
                    </div>
                    <!-- postbox -->
                </div>
                <!-- col-6 -->
            </div>
            <!-- row -->
        </div>
        <!-- erp-grid-container -->
    </form>
</div>
