<?php if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly ?>
<form method="post">
<div class="panel panel-default">

    <div class="panel-heading">
        <h3 class="panel-title"><?php _e('Email Notifications','ab') ?></h3>
    </div>
    <div class="panel-body">
        <?php if ( isset( $message ) ) : ?>
            <div id="message" style="margin: 0px!important;" class="updated below-h2"><p><?php echo $message ?></p></div>
        <?php endif ?>
        <div class="ab-notifications">
            <?php
            $sender_name  = get_option( 'ab_settings_sender_name' ) == '' ?
                get_option( 'blogname' )    : get_option( 'ab_settings_sender_name' );
            $sender_email = get_option( 'ab_settings_sender_email' ) == ''  ?
                get_option( 'admin_email' ) : get_option( 'ab_settings_sender_email' );
            ?>
            <table>
                <tr><!-- sender name -->
                    <td>
                        <label for="sender_name" style="display: inline;"><?php _e( 'Sender name', 'ab' ) ?></label>
                    </td>
                    <td>
                        <input id="sender_name" name="sender_name" class="form-control ab-inline-block ab-auto-w ab-sender" type="text" value="<?php echo esc_attr( $sender_name ) ?>"/>
                    </td>
                </tr>
                <tr><!-- sender email -->
                    <td>
                        <label for="sender_email" style="display: inline;"><?php _e( 'Sender email', 'ab' ) ?></label>
                    </td>
                    <td>
                        <input id="sender_email" name="sender_email" class="form-control ab-inline-block ab-auto-w ab-sender" type="text" value="<?php echo esc_attr( $sender_email ) ?>"/>
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
                        <?php echo $form->renderSubject( $type ) ?>
                    </div>
                    <div id="message_editor" class="ab-form-row">
                        <label class="ab-form-label" style="margin-top: 35px;"><?php _e( 'Message', 'ab' ) ?></label>
                        <?php echo $form->renderMessage( $type ) ?>
                    </div>
                    <?php if ( $type == 'provider_info' || $type == 'cancel_appointment' ): ?>
                        <?php echo $form->renderCopy( $type ) ?>
                    <?php endif ?>
                    <div class="ab-form-row">
                        <label class="ab-form-label"><?php _e( 'Codes','ab' ) ?></label>
                        <div class="ab-codes left">
                            <table>
                                <tbody>
                                <?php
                                switch ( $type ) {
                                    case 'staff_agenda':       include '_codes_staff_agenda.php'; break;
                                    case 'client_new_wp_user': include '_codes_client_new_wp_user.php'; break;
                                    default:                   include '_codes.php';
                                }
                                ?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        <?php endforeach; ?>
        <div class="ab-notifications">
            <?php
            echo '<i>' . __( 'To send scheduled notifications please execute the following script hourly with your cron:', 'ab' ) . '</i><br />';
            echo '<b>php -f ' . realpath( AB_PATH . '/lib/utils/send_notifications_cron.php' ) . '</b>';
            ?>
        </div>
    </div>
    <div class="panel-footer">
        <input type="submit" value="<?php echo esc_attr( __( 'Save Changes', 'ab' ) ) ?>" class="btn btn-info ab-update-button" />
        <button class="ab-reset-form btn btn-info" type="reset"><?php _e( 'Reset', 'ab' ) ?></button>
    </div>
</div>
</form>
