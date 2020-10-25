<div class="payment-gateways">
    <script src="https://checkout.stripe.com/checkout.js"></script>
    <div class="gateway-container">

        <div class="payment-methods" style="display: none;">
            <form id="erp-pg-payment-gateway" action="<?php echo $_SERVER["REQUEST_URI"]; ?>" method="POST">

                <p>

                <ul class="erp-pg-payment-gateways">
                    <?php
                    if ( $active_gateways ) {
                        ?>
                        <label class="payment-gateway-main-label"
                               for="erp-pg-payment-method"><?php _e( 'Choose Your Payment Method', 'erp-pro' ); ?></label>
                        <br/>
                        <?php
                        foreach ( $active_gateways as $name => $data ) {
                            ?>
                            <li class="erp-pg-gateway">
                                <label>
                                    <input name="erp_pg_payment_method" type="radio"
                                           value="<?php echo $data['value']; ?>">
                                    <?php
                                    echo $data['title'];

                                    switch ( $data['value'] ) {
                                        case 'paypal':
                                            echo '<img src="https://www.paypalobjects.com/webstatic/mktg/logo/AM_mc_vs_dc_ae.jpg" width="120" alt="">';
                                            break;

                                        case 'stripe':
                                            echo '<img src="' . WPERP_PG_ASSETS . '/img/stripe.png' . '" width="120" alt="">';
                                            break;

                                        default:
                                            break;
                                    }
                                    ?>
                                </label>

                                <div class="erp-pg-payment-instruction">
                                    <div class="erp-pg-instruction">
                                        <?php echo $data['description']; ?>
                                    </div>
                                </div>
                            </li>
                            <?php
                        }
                    } else {
                        _e( 'No active gateways found', 'erp-pro' );
                    }
                    ?>
                </ul>

                </p>
                <button class="payment-submit-button" type="submit"
                        name="erp_payment_submit"><?php _e( 'PAY NOW', 'erp-pro' ); ?></button>
            </form>
        </div>

        <button class="payment-options-button"><?php _e( 'PAYMENT OPTIONS', 'erp-pro' ); ?></button>
    </div>
</div>

