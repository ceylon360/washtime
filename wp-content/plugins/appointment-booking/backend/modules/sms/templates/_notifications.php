<?php if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly
$administrator_phone = get_option( 'ab_sms_administrator_phone' );
?>
<form action="<?php echo esc_url( remove_query_arg( 'paypal_result' ) ) ?>" method="post">
    <div class="ab-notifications form-inline">
        <table>
            <tr>
                <td>
                    <label for="country_code" style="display: inline"><?php _e( 'Default country code', 'ab' ) ?></label>
                </td>
                <td>
                    <input id="country_code" name="ab_sms_default_country_code" class="form-control ab-inline-block ab-auto-w ab-sender" type="text" placeholder="<?php echo esc_attr( __( 'Enter your country code', 'ab' ) ) ?>" value="<?php echo esc_attr( get_option( 'ab_sms_default_country_code' ) ) ?>"/>
                    <img src="<?php echo esc_attr( plugins_url( 'backend/resources/images/help.png', AB_PATH . '/main.php' ) ) ?>" alt="" class="ab-popover" data-content="<?php echo esc_attr( __( 'Your clients must have their phone numbers in international format in order to receive text messages. However you can specify a default country code that will be used as a prefix for all phone numbers that do not start with "+" or "00". E.g. if you enter "1" as the default country code and a client enters their phone as "(600) 555-2222" the resulting phone number to send the SMS to will be "+1600555222".', 'ab' ) ) ?>" data-original-title="" title="">
                </td>
            </tr>
            <tr>
                <td>
                    <label for="admin_phone" style="display: inline"><?php _e( 'Administrator phone', 'ab' ) ?></label>
                </td>
                <td>
                    <div class="input-group">
                        <input id="admin_phone" name="administrator_phone" class="form-control ab-inline-block ab-auto-w ab-sender" type="text" placeholder="<?php echo esc_attr( __( 'Enter phone number', 'ab' ) ) ?>" value="<?php echo esc_attr( $administrator_phone ) ?>"/>
                        <span class="input-group-btn">
                            <button class="btn btn-info" <?php if ( $administrator_phone == '' ): ?> disabled="disabled" <?php else: ?> id="send_test_sms"<?php endif ?> style="padding-top: 4px; padding-bottom: 4px;"><?php echo __( 'Send test SMS', 'ab' ) ?></button>
                        </span>
                    </div>
                    <img src="<?php echo esc_attr( plugins_url( 'backend/resources/images/help.png', AB_PATH . '/main.php' ) ) ?>" alt="" class="ab-popover" data-content="<?php echo esc_attr( __( 'Enter a phone number in international format. E.g. for the United States a valid phone number would be +17327572923.', 'ab' ) ) ?>" data-original-title="" title="">
                </td>
            </tr>
        </table>
    </div>
    <?php $data = $form->getData() ?>
    <?php foreach ( $form->types as $type ): ?>
        <div class="ab-notifications">
            <div class="ab-toggle-arrow"></div>
            <?php echo $form->renderActive( $type ) ?>
            <div class="ab-form-field">
                <div class="ab-form-row">
                    <label class="ab-form-label" style="margin-top: 35px;"><?php _e( 'Message', 'ab' ) ?></label>
                    <div class='ab-sms-holder'>
                        <?php echo $form->renderMessage( $type ) ?>
                        <span></span>
                    </div>
                </div>
                <div class="ab-form-row">
                    <label class="ab-form-label"><?php _e( 'Codes', 'ab' ) ?></label>
                    <div class="ab-codes left">
                        <table>
                            <tbody>
                            <?php
                            switch ( $type ) {
                                case 'staff_agenda':
                                    include '_notif_codes_staff_agenda.php';
                                    break;
                                case 'client_new_wp_user':
                                    include '_notif_codes_client_new_wp_user.php';
                                    break;
                                default:
                                    include '_notif_codes.php';
                            }
                            ?>
                            </tbody>
                        </table>
                    </div>
                </div>
                <?php if ($type == 'provider_info' || $type == 'cancel_appointment'): ?>
                    <?php echo $form->renderCopy( $type ) ?>
                <?php endif ?>
            </div>
        </div>
    <?php endforeach; ?>
    <div class="ab-notifications" style="border: 0">
        <button type="submit" name="form-notifications" class="btn btn-info ab-update-button"><?php _e( 'Save Changes', 'ab' ) ?></button>
        <button class="ab-reset-form" type="reset"><?php _e( 'Reset', 'ab' ) ?></button>
    </div>
</form>
<div class="ab-notification-info">
    <i><?php _e( 'To send scheduled notifications please execute the following script hourly with your cron:', 'ab' ) ?></i><br />
    <b>php -f <?php echo realpath( AB_PATH.'/lib/utils/send_notifications_cron.php' ) ?></b>
</div>
