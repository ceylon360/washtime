<?php if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly ?>
<form method="post">
    <div id="reportrange_sms" class="pull-left ab-reportrange" style="margin-bottom: 10px">
        <i class="glyphicon glyphicon-calendar"></i>
        <input type="hidden" name="form-purchases">
        <span data-date="<?php echo date( 'Y-m-d', strtotime( 'first day of this month' ) ) ?> - <?php echo date( 'Y-m-d' ) ?>"><?php echo date_i18n( get_option( 'date_format' ), strtotime( 'first day of this month' ) ) ?> - <?php echo date_i18n( get_option( 'date_format' ) ) ?></span> <b style="margin-top: 8px;" class=caret></b>
    </div>
    <div class="btn btn-info" id="get_list_sms"><?php _e( 'Filter', 'ab' ) ?></div>
</form>
<table class="table table-striped">
    <thead>
    <tr>
        <th><?php _e( 'Date', 'ab' ) ?></th>
        <th><?php _e( 'Time', 'ab' ) ?></th>
        <th><?php _e( 'Text', 'ab' ) ?></th>
        <th><?php _e( 'Phone', 'ab' ) ?></th>
        <th class="text-right"><?php _e( 'Cost', 'ab' ) ?></th>
        <th><?php _e( 'Status', 'ab' ) ?></th>
    </tr>
    </thead>
    <tbody id="list_sms">
    <tr><td colspan="6" class="text-center"><img src="<?php echo includes_url( 'js/tinymce/skins/lightgray/img/loader.gif' ) ?>" alt="<?php echo esc_attr( __( 'Loading...', 'ab' ) ) ?>" /></td></tr>
    </tbody>
</table>
