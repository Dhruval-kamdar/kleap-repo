<div class="wrap erp-candidate-detail" xmlns:v-on="http://www.w3.org/1999/xhtml">
    <h1><?php _e( 'Inventory Report', 'erp-pro' ); ?></h1>

    <form method="post">
        <div id="dashboard-widgets-wrap" class="erp-grid-container">
            <div class="row">
                <div class="col-6">
                    <div class="postbox">
                        <div class="inside" style="margin-bottom:0;margin-top:0;overflow-y:hidden;padding-bottom:0;padding-left:0;min-height:800px;">
                            <div id="item-reports-wrapper" class="information-container">
                                <div id="candidate-overview-zone">
                                    <h1 style="border-bottom:1px solid #e1e1e1;padding-bottom:15px;margin-bottom:15px;">
                                        <i class="fa fa-bar-chart-o">&nbsp;</i><?php _e( 'Item Reports', 'erp-pro' ); ?>
                                    </h1>
                                    <div id="drop-n-date-picker-wrapper">
                                        <select id="purchase-report-selection">
                                            <option value="0"><?php _e( '-- Select desire report --', 'erp-pro' ); ?></option>
                                            <option value="1"><?php _e( 'Inventory item detail', 'erp-pro' ); ?></option>
                                            <option value="2"><?php _e( 'Inventory item list', 'erp-pro' ); ?></option>
                                            <option value="3"><?php _e( 'Inventory item summary', 'erp-pro' ); ?></option>
                                        </select>
                                        <input type="text" id="from_date" v-datepicker v-model="from_date" name="from_date" value="" placeholder="From date" class="erp-date-field">
                                        <input type="text" id="to_date" v-datepicker v-model="to_date" name="to_date" value="" placeholder="To date" class="erp-date-field">
                                        <button class="button" v-on:click.prevent="getItemReportData"><?php _e( 'Search', 'erp-pro' ); ?></button>
                                        <span class="spinner"></span>
                                        <!--                                    <div id="report-csv-link">-->
                                        <!--                                        <input type="hidden" id="hidden-base-url" value="--><?php //echo admin_url( 'admin.php?page=erp-accounting-reports&type=inventory-report&func=inv-report-csv' ); ?><!--">-->
                                        <!--                                        <a id="csv-dl-link" class="necessary-link dl-link" href="--><?php //echo $_SERVER['REQUEST_URI'] . '&func=inv-report-csv'; ?><!--">-->
                                        <!--                                            <i class="fa fa-download">&nbsp;</i>--><?php //_e( 'Export to CSV', 'erp-pro' ); ?>
                                        <!--                                        </a>-->
                                        <!--                                    </div>-->
                                    </div>

                                    <table id="inventory-item-detail-report" class="inv-reports wp-list-table widefat fixed striped table-rec-reports">
                                        <thead>
                                        <tr>
                                            <th><?php _e( 'Date', 'erp-pro' ); ?></th>
                                            <th><?php _e( 'Reference No', 'erp-pro' ); ?></th>
                                            <th><?php _e( 'Contact', 'erp-pro' ); ?></th>
                                            <th><?php _e( 'Value Movement', 'erp-pro' ); ?></th>
                                            <th><?php _e( 'QoH Movement', 'erp-pro' ); ?></th>
                                            <th><?php _e( 'Margin', 'erp-pro' ); ?></th>
                                            <th><?php _e( 'Profit per item', 'erp-pro' ); ?></th>
                                        </tr>
                                        </thead>
                                        <tbody class="not-loaded">
                                        <tr v-for="itddata in itemDetailReportData">
                                            <td class="align-center">
                                                <span class="product-name align-left">{{itddata.product_name}}</span>
                                                <span class="issue-date">{{itddata.issue_date}}</span>
                                            </td>
                                            <td class="align-left">{{itddata.ref_no}}</td>
                                            <td class="align-left">{{itddata.vendor_name}}</td>
                                            <td class="align-right">{{itddata.line_total}}</td>
                                            <td class="align-right">{{itddata.quantity}}</td>
                                            <td class="align-right">{{itddata.margin}}</td>
                                            <td class="align-right">{{itddata.profit_per_item}}</td>
                                        </tr>
                                        </tbody>
                                    </table>

                                    <table id="inventory-item-list-report" class="inv-reports wp-list-table widefat fixed striped table-rec-reports">
                                        <thead>
                                        <tr>
                                            <th><?php _e( 'Item Name', 'erp-pro' ); ?></th>
                                            <th><?php _e( 'Description', 'erp-pro' ); ?></th>
                                            <th><?php _e( 'Unit Cost Price', 'erp-pro' ); ?></th>
                                            <th><?php _e( 'Unit Sale Price', 'erp-pro' ); ?></th>
                                            <th><?php _e( 'Total Value', 'erp-pro' ); ?></th>
                                            <th><?php _e( 'Quantity on Hand', 'erp-pro' ); ?></th>
                                        </tr>
                                        </thead>
                                        <tbody class="not-loaded">
                                        <tr v-for="itldata in itemListReportData">
                                            <td class="align-left">{{itldata.product_name}}</td>
                                            <td class="align-left">{{itldata.description}}</td>
                                            <td class="align-right">{{itldata.unit_cost_price}}</td>
                                            <td class="align-right">{{itldata.unit_sale_price}}</td>
                                            <td class="align-right">{{itldata.total_value}}</td>
                                            <td class="align-right">{{itldata.quantity_on_hand}}</td>
                                        </tr>
                                        <tr>
                                            <td colspan="2" class="align-right"><?php _e( 'Total :', 'erp-pro' ); ?></td>
                                            <td class="align-right"></td>
                                            <td class="align-right"></td>
                                            <td class="align-right">{{totalvalue}}</td>
                                            <td class="align-right">{{totalQoH}}</td>
                                        </tr>
                                        </tbody>
                                    </table>

                                    <table id="inventory-item-summary-report" class="inv-reports wp-list-table widefat fixed striped table-rec-reports">
                                        <thead>
                                        <tr>
                                            <th><?php _e( 'Item Name', 'erp-pro' ); ?></th>
                                            <th><?php _e( 'Opening Balance', 'erp-pro' ); ?></th>
                                            <th><?php _e( 'Purchases', 'erp-pro' ); ?></th>
                                            <th><?php _e( 'COGS', 'erp-pro' ); ?></th>
                                            <th><?php _e( 'Closing Balance', 'erp-pro' ); ?></th>
                                            <th><?php _e( 'Sales', 'erp-pro' ); ?></th>
                                        </tr>
                                        </thead>
                                        <tbody class="not-loaded">
                                        <tr v-for="itsdata in itemSummaryReportData">
                                            <td class="align-left">{{itsdata.product_name}}</td>
                                            <td class="align-right">{{itsdata.opening_balance}}</td>
                                            <td class="align-right">{{itsdata.purchases}}</td>
                                            <td class="align-right">{{itsdata.COGS}}</td>
                                            <td class="align-right">{{itsdata.closing_balance}}</td>
                                            <td class="align-right">{{itsdata.sales}}</td>
                                        </tr>
                                        <tr>
                                            <td class="align-right"><?php _e( 'Total :', 'erp-pro' ); ?></td>
                                            <td class="align-right">{{totalOpeningBalance}}</td>
                                            <td class="align-right">{{totalPurchases}}</td>
                                            <td class="align-right">{{totalCOGS}}</td>
                                            <td class="align-right">{{totalClosingBalance}}</td>
                                            <td class="align-right">{{totalSales}}</td>
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
