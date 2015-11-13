<?php if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

/**
 * Class AB_SmsController
 */
class AB_SmsController extends AB_Controller
{
    const page_slug = "ab-sms";

    public function index()
    {
        global $wp_locale;

        $this->enqueueStyles(
            array(
                'backend' => array(
                    'css/bookly.main-backend.css',
                    'bootstrap/css/bootstrap.min.css',
                    'css/daterangepicker.css',
                ),
                'module'  => array(
                    'css/sms.css',
                    'css/flags.css',
                )
            )
        );

        $this->enqueueScripts(
            array(
                'backend' => array(
                    'bootstrap/js/bootstrap.min.js' => array( 'jquery' ),
                    'js/moment.min.js',
                    'js/moment-format-php.js' => array( 'ab-moment.min.js' ),
                    'js/daterangepicker.js'   => array( 'jquery', 'ab-moment-format-php.js' ),
                ),
                'module'  => array(
                    'js/sms.js' => array( 'jquery' ),
                )
            )
        );

        wp_localize_script( 'ab-daterangepicker.js', 'BooklyL10n',
            array(
                'today'         => __( 'Today', 'ab' ),
                'yesterday'     => __( 'Yesterday', 'ab' ),
                'last_7'        => __( 'Last 7 Days', 'ab' ),
                'last_30'       => __( 'Last 30 Days', 'ab' ),
                'this_month'    => __( 'This Month', 'ab' ),
                'last_month'    => __( 'Last Month', 'ab' ),
                'custom_range'  => __( 'Custom Range', 'ab' ),
                'apply'         => __( 'Apply', 'ab' ),
                'cancel'        => __( 'Cancel', 'ab' ),
                'to'            => __( 'To', 'ab' ),
                'from'          => __( 'From', 'ab' ),
                'months'        => array_values( $wp_locale->month ),
                'days'          => array_values( $wp_locale->weekday_abbrev ),
                'start_of_week' => get_option( 'start_of_week' ),
                'Credit'        => __( 'Credit', 'ab' ),
                'date_format'   => get_option( 'date_format' ),
                'current_tab'   => 'notifications',

                'passwords_no_same'  => __( 'Passwords must be the same.', 'ab' ),
                'input_old_password' => __( 'Please enter old password.', 'ab' ),
            )
        );

        $this->is_logged_in = false;
        $this->prices       = array();
        $this->form         = new AB_NotificationsForm( 'sms' );
        $this->sms          = new AB_SMS();

        $errors   = array();
        $messages = array();

        switch ( $this->getParameter( 'paypal_result' ) ) {
            case 'success':
                $messages[] = __( 'Your payment has been accepted for processing.', 'ab' );
                break;
            case 'cancel':
                $errors[] = __( 'Your payment has been interrupted.', 'ab' );
                break;
        }

        if ( $this->hasParameter( 'form-login' ) ) {
            $this->is_logged_in = $this->sms->login( $this->getParameter( 'username' ), $this->getParameter( 'password' ) );

        } elseif ( $this->hasParameter( 'form-logout' ) ) {
            $this->sms->logout();

        } elseif ( $this->hasParameter( 'form-registration' ) ) {
            if ( $this->getParameter( 'accept_tos', false ) ) {
                $this->is_logged_in = $this->sms->register(
                    $this->getParameter( 'username' ),
                    $this->getParameter( 'password' ),
                    $this->getParameter( 'password_repeat' )
                );
            } else {
                $errors[] = __( 'Please accept terms and conditions.', 'ab' );
            }

        } else {
            $this->is_logged_in = $this->sms->loadProfile();
        }

        if ( ! $this->is_logged_in ) {
            if( $response = $this->sms->getPriceList() ){
                $this->prices = $response->list;
            }
            if( $_SERVER['REQUEST_METHOD'] == 'GET' ) {
                // Hide authentication errors on auto login.
                $this->sms->clearErrors();
            }
        }

        if ( $this->hasParameter( 'form-notifications' ) ) {
            update_option( 'ab_sms_administrator_phone', $this->getParameter( 'administrator_phone' ) );
            update_option( 'ab_sms_default_country_code', $this->getParameter( 'ab_sms_default_country_code' ) );

            $this->form->bind( $this->getPostParameters(), $_FILES );
            $this->form->save();
            $messages[] = __( 'Notification settings were updated successfully.', 'ab' );
        }

        $this->render( 'index', array( 'errors' => array_merge( $errors, $this->sms->getErrors() ), 'messages' => $messages ) );
    } // index

    public function executeGetPurchasesList()
    {
        $sms = new AB_SMS();
        if ( $this->hasParameter( 'range' ) ) {
            $dates = explode( ' - ', $this->getParameter( 'range' ), 2 );
            $start = AB_DateTimeUtils::applyTimeZoneOffset( $dates[0], 0 );
            $end   = AB_DateTimeUtils::applyTimeZoneOffset( date( 'Y-m-d', strtotime( '+1 day', strtotime( $dates[1] ) ) ), 0 );
        } else {
            $start = AB_DateTimeUtils::applyTimeZoneOffset( date( 'Y-m-d', strtotime( 'first day of this month' ) ), 0 );
            $end   = AB_DateTimeUtils::applyTimeZoneOffset( date( 'Y-m-d', strtotime( 'first day of next month' ) ), 0 );
        }

        $list  = $sms->getPurchasesList($start, $end);
        if ( empty ( $list ) ) {
            wp_send_json_error();
        } else {
            wp_send_json( $list );
        }
    }

    public function executeGetSmsList()
    {
        $sms = new AB_SMS();
        if ( $this->hasParameter( 'range' ) ) {
            $dates = explode( ' - ', $this->getParameter( 'range' ), 2 );
            $start = AB_DateTimeUtils::applyTimeZoneOffset( $dates[0], 0 );
            $end   = AB_DateTimeUtils::applyTimeZoneOffset( date( 'Y-m-d', strtotime( '+1 day', strtotime( $dates[1] ) ) ), 0 );
        } else {
            $start = AB_DateTimeUtils::applyTimeZoneOffset( date( 'Y-m-d', strtotime( 'first day of this month' ) ), 0 );
            $end   = AB_DateTimeUtils::applyTimeZoneOffset( date( 'Y-m-d', strtotime( 'first day of next month' ) ), 0 );
        }

        $list  = $sms->getSmsList( $start, $end );
        if ( empty ( $list ) ) {
            wp_send_json_error();
        } else {
            wp_send_json( $list );
        }
    }

    public function executeGetPriceList()
    {
        $sms  = new AB_SMS();
        $list = $sms->getPriceList();
        if ( empty ( $list ) ) {
            wp_send_json_error();
        } else {
            wp_send_json( $list );
        }
    }

    public function executeChangePassword()
    {
        $sms  = new AB_SMS();
        $old_password = $this->getParameter( 'old_password' );
        $new_password = $this->getParameter( 'new_password' );

        $result = $sms->changePassword( $new_password, $old_password );
        if ( $result === false ) {
            wp_send_json_error( array( 'message' => current( $sms->getErrors() ) ) );
        } else {
            wp_send_json_success();
        }
    }

    public function executeSendTestSms()
    {
        $sms = new AB_SMS();
        $administrator_phone = trim( get_option( 'ab_sms_administrator_phone' ) );
        if ( $administrator_phone != '' ) {
            $response = array( 'success' => $sms->sendSms( $administrator_phone, 'Bookly test SMS.' ) );
            if ( $response['success'] ) {
                $response['message'] = __( 'SMS has been sent successfully.', 'ab' );
            } else {
                $response['message'] = __( 'Failed to send SMS.', 'ab' );
            }
            wp_send_json( $response );
        } else {
            wp_send_json( array( 'success' => false, 'message' => __( 'Administrator phone number is empty.', 'ab' ) ) );
        }
    }

    public function executeForgotPassword()
    {
        $sms      = new AB_SMS();
        $step     = $this->getParameter( 'step' );
        $code     = $this->getParameter( 'code' );
        $username = $this->getParameter( 'username' );
        $password = $this->getParameter( 'password' );
        $result   = $sms->forgotPassword( $username, $step, $code, $password );
        if ( $result === false ) {
            wp_send_json_error( array( 'message' => current( $sms->getErrors() ) ) );
        } else {
            wp_send_json_success();
        }
    }

    /**
     * Override parent method to add 'wp_ajax_ab_' prefix
     * so current 'execute*' methods look nicer.
     */
    protected function registerWpActions( $prefix = '' )
    {
        parent::registerWpActions( 'wp_ajax_ab_' );
    }
}