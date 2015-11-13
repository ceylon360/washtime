<?php if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly ?>
<form method="post" action="<?php echo esc_url( add_query_arg( 'type', '_payments' ) ) ?>" class="ab-settings-form">
    <?php if (isset($message_p)) : ?>
    <div id="message" style="margin: 0px!important;" class="updated below-h2">
        <button type="button" class="close" data-dismiss="alert">&times;</button>
        <p><?php echo $message_p ?></p>
    </div>
    <?php endif ?>
    <table class="form-horizontal">
        <tr>
            <td style="width: 170px;">
                <label for="ab_paypal_currency"><?php _e( 'Currency','ab' ) ?></label>
            </td>
            <td>
                <select id="ab_paypal_currency" class="form-control" name="ab_paypal_currency">
                    <?php foreach ( PayPal::getCurrencyCodes() as $code ): ?>
                        <option value="<?php echo $code ?>" <?php selected( get_option( 'ab_paypal_currency' ), $code ); ?> ><?php echo $code ?></option>
                    <?php endforeach ?>
                </select>
            </td>
        </tr>
        <tr>
            <td style="width: 170px;">
                <label for="ab_settings_coupons"><?php _e( 'Coupons','ab' ) ?></label>
            </td>
            <td>
                <select id="ab_settings_coupons" class="form-control" name="ab_settings_coupons">
                    <?php foreach ( array( __( 'Disabled', 'ab' ) => '0', __( 'Enabled', 'ab' ) => '1' ) as $text => $mode ): ?>
                        <option value="<?php echo $mode ?>" <?php selected( get_option( 'ab_settings_coupons' ), $mode ); ?> ><?php echo $text ?></option>
                    <?php endforeach ?>
                </select>
            </td>
        </tr>
        <tr>
            <td colspan="2"><div class="ab-payments-title"><?php _e( 'Service paid locally','ab' ) ?></div></td>
        </tr>
        <tr>
            <td colspan="2">
                <select class="form-control" name="ab_settings_pay_locally">
                    <?php foreach ( array( __( 'Disabled', 'ab' ) => '0', __( 'Enabled', 'ab' ) => '1' ) as $text => $mode ): ?>
                        <option value="<?php echo $mode ?>" <?php selected( get_option( 'ab_settings_pay_locally' ), $mode ); ?> ><?php echo $text ?></option>
                    <?php endforeach ?>
                </select>
            </td>
        </tr>
        <tr>
            <td colspan="2"><div class="ab-payments-title">PayPal</div></td>
        </tr>
        <tr>
            <td colspan="2">
                <select class="form-control" name="ab_paypal_type" id="ab_paypal_type">
                    <?php foreach ( array( __( 'Disabled', 'ab' ) => 'disabled', 'PayPal Express Checkout' => 'ec' ) as $text => $mode ): ?>
                        <option value="<?php echo $mode ?>" <?php selected( get_option( 'ab_paypal_type' ), $mode ); ?> ><?php echo $text ?></option>
                    <?php endforeach ?>
                </select>
            </td>
        </tr>
        <tr class="paypal_ec">
            <td><label for="ab_paypal_api_username"><?php _e( 'API Username','ab' ) ?></label></td>
            <td><input id="ab_paypal_api_username" class="form-control" type="text" size="33" name="ab_paypal_api_username" value="<?php echo get_option( 'ab_paypal_api_username' ) ?>"/></td>
        </tr>
        <tr class="paypal_ec">
            <td><label for="ab_paypal_api_password"><?php _e( 'API Password','ab' ) ?></label></td>
            <td><input id="ab_paypal_api_password" class="form-control" type="text" size="33" name="ab_paypal_api_password" value="<?php echo get_option( 'ab_paypal_api_password' ) ?>"/></td>
        </tr>
        <tr class="paypal_ec">
            <td><label for="ab_paypal_api_signature"><?php _e( 'API Signature','ab' ) ?></label></td>
            <td><input id="ab_paypal_api_signature" class="form-control" type="text" size="33" name="ab_paypal_api_signature" value="<?php echo get_option( 'ab_paypal_api_signature' ) ?>"/></td>
        </tr>
        <tr class="paypal_ec">
            <td><label for="ab_paypal_ec_mode"><?php _e( 'Sandbox Mode','ab' ) ?></label></td>
            <td>
                <select id="ab_paypal_ec_mode" class="form-control" name="ab_paypal_ec_mode">
                    <?php foreach ( array( 'Yes' => '.sandbox', 'No' => '' ) as $text => $mode ): ?>
                        <option value="<?php echo $mode ?>" <?php selected( get_option( 'ab_paypal_ec_mode' ), $mode ); ?> ><?php echo $text ?></option>
                    <?php endforeach ?>
                </select>
            </td>
        </tr>
        <tr>
            <td colspan="2"><div class="ab-payments-title">Authorize.Net</div></td>
        </tr>
        <tr>
            <td colspan="2">
                <select class="form-control" name="ab_authorizenet_type" id="ab_authorizenet_type">
                    <?php foreach ( array( __( 'Disabled', 'ab' ) => 'disabled', 'Authorize.Net AIM' => 'aim' ) as $text => $mode ): ?>
                        <option value="<?php echo $mode ?>" <?php selected( get_option( 'ab_authorizenet_type' ), $mode ); ?> ><?php echo $text ?></option>
                    <?php endforeach ?>
                </select>
            </td>
        </tr>
        <tr class="authorizenet">
            <td><label for="ab_authorizenet_api_login_id"><?php _e( 'API Login ID','ab' ) ?></label></td>
            <td><input id="ab_authorizenet_api_login_id" class="form-control" type="text" size="33" name="ab_authorizenet_api_login_id" value="<?php echo get_option( 'ab_authorizenet_api_login_id' ) ?>"/></td>
        </tr>
        <tr class="authorizenet">
            <td><label for="ab_authorizenet_transaction_key"><?php _e( 'API Transaction Key','ab' ) ?></label></td>
            <td><input id="ab_authorizenet_transaction_key" class="form-control" type="text" size="33" name="ab_authorizenet_transaction_key" value="<?php echo get_option( 'ab_authorizenet_transaction_key' ) ?>"/></td>
        </tr>
        <tr class="authorizenet">
            <td><label for="ab_authorizenet_sandbox"><?php _e( 'Sandbox Mode','ab' ) ?></label></td>
            <td>
                <select id="ab_authorizenet_sandbox" class="form-control" name="ab_authorizenet_sandbox">
                    <?php foreach ( array( 'Yes' => 1, 'No' => 0 ) as $text => $mode ): ?>
                        <option value="<?php echo $mode ?>" <?php selected( get_option( 'ab_authorizenet_sandbox' ), $mode ); ?> ><?php echo $text ?></option>
                    <?php endforeach ?>
                </select>
            </td>
        </tr>

        <tr>
            <td colspan="2"><div class="ab-payments-title">Stripe</div></td>
        </tr>
        <tr>
            <td colspan="2">
                <select class="form-control" name="ab_stripe" id="ab_stripe">
                    <?php foreach ( array( __( 'Disabled', 'ab' ) => 0, __( 'Enabled', 'ab' ) => 1 ) as $text => $mode ): ?>
                        <option value="<?php echo $mode ?>" <?php selected( get_option( 'ab_stripe' ), $mode ); ?> ><?php echo $text ?></option>
                    <?php endforeach ?>
                </select>
            </td>
        </tr>
        <tr class="ab-stripe">
            <td><label for="ab_stripe_secret_key"><?php _e( 'Secret Key','ab' ) ?></label></td>
            <td><input id="ab_stripe_secret_key" class="form-control" type="text" size="33" name="ab_stripe_secret_key" value="<?php echo get_option( 'ab_stripe_secret_key' ) ?>"/></td>
        </tr>
        <tr>
            <td colspan="2">
                <input type="submit" value="<?php _e( 'Save', 'ab' ) ?>" class="btn btn-info ab-update-button" />
                <button id="ab-payments-reset" class="ab-reset-form" type="reset"><?php _e( 'Reset', 'ab' ) ?></button>
            </td>
            <td></td>
        </tr>
    </table>
</form>