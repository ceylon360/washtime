<?php if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly ?>

<div class="panel panel-default">
    <div class="panel-heading">
        <h3 class="panel-title"><?php _e( 'Settings', 'ab' ) ?></h3>
    </div>
    <div class="panel-body">
        <div class="row">
            <div class="ab-settings ab-left-bar col-md-3 col-sm-3 col-xs-12 col-lg-3">
                <?php $type = isset ( $_GET[ 'type' ] ) ? $_GET[ 'type' ] : '_general' ?>
                <div id="ab_settings_general" class="ab-left-tab <?php echo $type == '_general' ? 'ab-active' : '' ?>"><?php _e( 'General','ab' ) ?></div>
                <div id="ab_settings_company" class="ab-left-tab <?php echo $type == '_company' ? 'ab-active' : '' ?>"><?php _e( 'Company','ab' ) ?></div>
                <div id="ab_settings_google_calendar" class="ab-left-tab <?php $type == '_google_calendar' ? 'ab-active' : '' ?>"><?php _e( 'Google Calendar','ab' ) ?></div>
                <div id="ab_settings_woocommerce" class="ab-left-tab <?php echo $type == '_woocommerce' ? 'ab-active' : '' ?>"><?php _e( 'WooCommerce','ab' ) ?></div>
                <div id="ab_settings_payments" class="ab-left-tab <?php echo $type == '_payments' ? 'ab-active' : '' ?>"><?php _e( 'Payments','ab' ) ?></div>
                <div id="ab_settings_hours" class="ab-left-tab <?php echo $type == '_hours' ? 'ab-active' : '' ?>"><?php _e( 'Business hours','ab' ) ?></div>
                <div id="ab_settings_holidays" class="ab-left-tab <?php echo $type == '_holidays' ? 'ab-active' : '' ?>"><?php _e( 'Holidays','ab' ) ?></div>
                <div id="ab_settings_purchase_code" class="ab-left-tab <?php echo $type == '_purchase_code' ? 'ab-active' : '' ?>"><?php _e( 'Purchase Code','ab' ) ?></div>
            </div>
            <div class="ab-right-content col-md-9 col-sm-9 col-xs-12 col-lg-9" id="content_wrapper">
                <div id="general-form" class="<?php echo ( $type == '_general' ) ? '' : 'hidden' ?> ab-setting-tab-content">
                    <?php include '_generalForm.php' ?>
                </div>
                <div id="company-form" class="<?php echo ( $type == '_company' ) ? '' : 'hidden' ?>">
                    <?php include '_companyForm.php' ?>
                </div>
                <div id="google-calendar-form" class="<?php echo ( $type == '_google_calendar' ) ? '' : 'hidden' ?>">
                    <?php include '_googleCalendarForm.php' ?>
                </div>
                <div id="payments-form" class="<?php echo ( $type == '_payments' ) ? '' : 'hidden' ?>">
                    <?php include '_paymentsForm.php' ?>
                </div>
                <div id="hours-form" class="<?php echo ( $type == '_hours' ) ? '' : 'hidden' ?>">
                    <?php include '_hoursForm.php' ?>
                </div>
                <div id="holidays-form" class="<?php echo ( $type == '_holidays' ) ? '' : 'hidden' ?> ab-setting-tab-content">
                    <?php include '_holidaysForm.php' ?>
                </div>
                <div id="purchase-code-form" class="<?php echo ( $type == '_purchase_code' ) ? '' : 'hidden' ?> ab-setting-tab-content">
                    <?php include '_purchaseCodeForm.php' ?>
                </div>
                <div id="woocommerce-form" class="<?php echo ( $type == '_woocommerce' ) ? '' : 'hidden' ?> ab-setting-tab-content">
                    <?php include '_woocommerce.php' ?>
                </div>
            </div>
        </div>
    </div>
</div>