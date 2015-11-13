<?php

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

class AB_PaymentController extends AB_Controller {

    protected $query = "";

    public function __construct()
    {
        $this->query = "
        SELECT
            p.*,
            c.name         customer,
            st.full_name   provider,
            s.title        service,
            ca.coupon_code coupon,
            a.start_date
        FROM " . AB_Payment::getTableName() . " p
        LEFT JOIN " . AB_CustomerAppointment::getTableName() . " ca ON ca.id = p.customer_appointment_id
        LEFT JOIN " . AB_Customer::getTableName() . " c ON c.id = ca.customer_id
        LEFT JOIN " . AB_Appointment::getTableName() . " a ON ca.appointment_id = a.id
        LEFT JOIN " . AB_Service::getTableName() . " s ON a.service_id = s.id
        LEFT JOIN " . AB_Staff::getTableName() . " st ON st.id = a.staff_id";

        parent::__construct();
    }
    /**
     * @param $request
     * @return string
     */
    public function createQuery( $request )
    {
        $wpdb = $this->getWpdb();

        $query_part = "";
        $where = array();

        if ( isset( $request[ 'type' ] ) and $request[ 'type' ] != -1 ) {
            $where[] = sprintf(
                'p.type = "%s"',
                $wpdb->_real_escape( $request[ 'type' ] )
            );
        }

        if ( isset( $request[ 'customer' ] ) and $request[ 'customer' ] != -1 ) {
            $where[] = sprintf(
                'c.name = "%s"',
                $wpdb->_real_escape( $request[ 'customer' ] )
            );
        }

        if ( isset( $request[ 'provider' ] ) and $request[ 'provider' ] != -1 ) {
            $where[] = sprintf(
                'st.full_name = "%s"',
                $wpdb->_real_escape( $request[ 'provider' ] )
            );
        }

        if ( isset( $request[ 'service' ] ) and $request[ 'service' ]  != -1 ) {
            $where[] = sprintf(
                's.title = "%s"',
                $wpdb->_real_escape( $request[ 'service' ] )
            );
        }

        if ( isset( $request[ 'range' ] ) and !empty( $request[ 'range' ] ) ) {
            $dates = explode( '-', $request['range'], 2 );
            $start_date_timestamp = strtotime($dates[0]);
            $end_date_timestamp   = strtotime($dates[1]);

            $start = date( 'Y-m-d', $start_date_timestamp );
            $end   = date( 'Y-m-d', strtotime('+1 day', $end_date_timestamp));

            $where[] = "p.created BETWEEN '{$start}' AND '{$end}'";
        }

        if ( !empty( $where ) ) {
            $query_part = ' WHERE ' . implode(' AND ', $where);
        }

        if (
            ! empty( $request['sort_order'] ) &&
            in_array( $request['order_by'], array( 'created', 'type', 'customer', 'provider', 'service', 'total', 'start_date', 'coupon' ) )
        ) {
            $query_part = $query_part . sprintf(
                ' ORDER BY %s %s',
                $request[ 'order_by' ],
                $request[ 'sort_order' ] == 'desc' ? 'DESC' : 'ASC'
            );
        }

        return $this->query . $query_part;
    }

    public function index() {
        /** @var WP_Locale $wp_locale */
        global $wp_locale;

        $this->enqueueStyles( array(
            'backend' => array(
                'css/bookly.main-backend.css',
                'bootstrap/css/bootstrap.min.css',
                'css/daterangepicker.css',
                'css/bootstrap-select.min.css',
            )
        ) );

        $this->enqueueScripts( array(
            'backend' => array(
                'bootstrap/js/bootstrap.min.js' => array( 'jquery' ),
                'js/moment.min.js',
                'js/moment-format-php.js' => array( 'ab-moment.min.js' ),
                'js/daterangepicker.js' => array( 'jquery', 'ab-moment-format-php.js' ),
                'js/bootstrap-select.min.js',
            )
        ) );

        wp_localize_script( 'ab-daterangepicker.js', 'BooklyL10n', array(
            'today'         => __( 'Today', 'ab' ),
            'yesterday'     => __( 'Yesterday', 'ab' ),
            'last_7'        => __( 'Last 7 Days', 'ab' ),
            'last_30'       => __( 'Last 30 Days', 'ab' ),
            'this_month'    => __( 'This Month', 'ab' ),
            'last_month'    => __( 'Last Month', 'ab' ),
            'custom_range'  => __( 'Custom Range', 'ab' ),
            'apply'         => __( 'Apply' ),
            'cancel'        => __( 'Cancel' ),
            'to'            => __( 'To', 'ab' ),
            'from'          => __( 'From', 'ab' ),
            'months'        => array_values( $wp_locale->month ),
            'days'          => array_values( $wp_locale->weekday_abbrev ),
            'start_of_week' => get_option( 'start_of_week' ),
        ));

        $request = array(
            'range'      => date( 'F j, Y', strtotime( '-29 days' ) ) . '-' . date( 'F j, Y' ),
            'order_by'   => 'created',
            'sort_order' => 'desc',
        );
        $this->collection = $this->getWpdb()->get_results( $this->createQuery( $request ) );

        $payments = array();
        foreach ( $this->collection as $key => $value ) {
            $payments[] = $value->type;
        }

        $customers = array();
        foreach ( $this->collection as $key => $value ) {
            $customers[] = $value->customer;
        }

        $providers = array();
        foreach ( $this->collection as $key => $value ) {
            $providers[] = $value->provider;
        }

        $services = array();
        foreach ( $this->collection as $key => $value ) {
            $services[] = $value->service;
        }

        $this->types     = array_unique( $payments );
        $this->customers = array_unique( $customers );
        $this->providers = array_unique( $providers );
        $this->services  = array_unique( $services );

        $this->render( 'index' );
    }

    /**
     *
     */
    public function executeSortPayments()
    {
        $data = $this->getParameter( 'data' );
        if ( ! empty( $data ) ) {
            $this->collection = $this->getWpdb()->get_results( $this->createQuery( $data ) );
            $this->render( '_body' );
            exit;
        }

        $this->collection = array();
        $this->render( '_body' );
        exit;
    }

    // ab_filter_payments
    public function executeFilterPayments()
    {
        $data = $this->getParameter( 'data' );
        if ( ! empty( $data ) ) {
            $this->collection = $this->getWpdb()->get_results( $this->createQuery( $data ) );
            $this->render( '_body' );
            exit;
        }

        $this->collection = array();
        $this->render( '_body' );
        exit;
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