<?php if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

class AB_Installer
{
    private $notifications;
    private $options;

    /**
     * Constructor.
     */
    public function __construct()
    {
        // Load l10n for fixtures creating.
        load_plugin_textdomain( 'ab', false, dirname( plugin_basename( __FILE__ ) ) . '/languages' );

        /*
         * Notifications mail.
         */
        $this->notifications = array(
             array(
                 'gateway' => 'email',
                 'type'    => 'client_new_appointment',
                 'subject' => __( 'Your appointment information', 'ab' ),
                 'message' => wpautop( __( "Dear [[CLIENT_NAME]].\n\nThis is confirmation that you have booked [[SERVICE_NAME]].\n\nWe are waiting you at [[COMPANY_ADDRESS]] on [[APPOINTMENT_DATE]] at [[APPOINTMENT_TIME]].\n\nThank you for choosing our company.\n\n[[COMPANY_NAME]]\n[[COMPANY_PHONE]]\n[[COMPANY_WEBSITE]]", 'ab' ) ),
                 'active'  => 1,
            ),
            array(
                'gateway' => 'email',
                'type'    => 'staff_new_appointment',
                'subject' => __( 'New booking information', 'ab' ),
                'message' => wpautop( __( "Hello.\n\nYou have new booking.\n\nService: [[SERVICE_NAME]]\nDate: [[APPOINTMENT_DATE]]\nTime: [[APPOINTMENT_TIME]]\nClient name: [[CLIENT_NAME]]\nClient phone: [[CLIENT_PHONE]]\nClient email: [[CLIENT_EMAIL]]", 'ab' ) ),
                'active'  => 0,
            ),
            array(
                'gateway' => 'email',
                'type'    => 'client_reminder',
                'subject' => __( 'Your appointment at [[COMPANY_NAME]]', 'ab' ),
                'message' => wpautop( __( "Dear [[CLIENT_NAME]].\n\nWe would like to remind you that you have booked [[SERVICE_NAME]] tomorrow on [[APPOINTMENT_TIME]]. We are waiting you at [[COMPANY_ADDRESS]].\n\nThank you for choosing our company.\n\n[[COMPANY_NAME]]\n[[COMPANY_PHONE]]\n[[COMPANY_WEBSITE]]", 'ab' ) ),
                'active'  => 0,
            ),
            array(
                'gateway' => 'email',
                'type'    => 'client_follow_up',
                'subject' => __( 'Your visit to [[COMPANY_NAME]]', 'ab' ),
                'message' => wpautop( __( "Dear [[CLIENT_NAME]].\n\nThank you for choosing [[COMPANY_NAME]]. We hope you were satisfied with your [[SERVICE_NAME]].\n\nThank you and we look forward to seeing you again soon.\n\n[[COMPANY_NAME]]\n[[COMPANY_PHONE]]\n[[COMPANY_WEBSITE]]", 'ab' ) ),
                'active'  => 0,
            ),
            array(
                'gateway' => 'email',
                'type'    => 'staff_agenda',
                'subject' => __( 'Your agenda for [[TOMORROW_DATE]]', 'ab' ),
                'message' => wpautop( __( "Hello.\n\nYour agenda for tomorrow is:\n\n[[NEXT_DAY_AGENDA]]", 'ab' ) ),
                'active'  => 0,
            ),
            array(
                'gateway' => 'email',
                'type'    => 'staff_cancelled_appointment',
                'subject' => __( 'Booking cancellation', 'ab' ),
                'message' => wpautop( __( "Hello.\n\nThe following booking has been cancelled.\n\nService: [[SERVICE_NAME]]\nDate: [[APPOINTMENT_DATE]]\nTime: [[APPOINTMENT_TIME]]\nClient name: [[CLIENT_NAME]]\nClient phone: [[CLIENT_PHONE]]\nClient email: [[CLIENT_EMAIL]]", 'ab' ) ),
                'active'  => 0,
            ),
            array(
                'gateway' => 'email',
                'type'    => 'client_new_wp_user',
                'subject' => __( 'New customer', 'ab' ),
                'message' => wpautop( __( "Hello.\n\nAn account was created for you at [[SITE_ADDRESS]]\n\nYour user details:\nuser: [[NEW_USERNAME]]\npassword: [[NEW_PASSWORD]]\n\nThanks.", 'ab' ) ),
                'active'  => 1,
            ),


            array(
                'gateway' => 'sms',
                'type'    => 'client_new_appointment',
                'subject' => '',
                'message' => __( "Dear [[CLIENT_NAME]].\nThis is confirmation that you have booked [[SERVICE_NAME]].\nWe are waiting you at [[COMPANY_ADDRESS]] on [[APPOINTMENT_DATE]] at [[APPOINTMENT_TIME]].\nThank you for choosing our company.\n[[COMPANY_NAME]]\n[[COMPANY_PHONE]]\n[[COMPANY_WEBSITE]]", 'ab' ),
                'active'  => 1,
            ),
            array(
                'gateway' => 'sms',
                'type'    => 'staff_new_appointment',
                'subject' => '',
                'message' => __( "Hello.\nYou have new booking.\nService: [[SERVICE_NAME]]\nDate: [[APPOINTMENT_DATE]]\nTime: [[APPOINTMENT_TIME]]\nClient name: [[CLIENT_NAME]]\nClient phone: [[CLIENT_PHONE]]\nClient email: [[CLIENT_EMAIL]]", 'ab' ),
                'active'  => 0,
            ),
            array(
                'gateway' => 'sms',
                'type'    => 'client_reminder',
                'subject' => '',
                'message' => __( "Dear [[CLIENT_NAME]].\nWe would like to remind you that you have booked [[SERVICE_NAME]] tomorrow on [[APPOINTMENT_TIME]]. We are waiting you at [[COMPANY_ADDRESS]].\nThank you for choosing our company.\n[[COMPANY_NAME]]\n[[COMPANY_PHONE]]\n[[COMPANY_WEBSITE]]", 'ab' ),
                'active'  => 0,
            ),
            array(
                'gateway' => 'sms',
                'type'    =>'client_follow_up',
                'subject' => '',
                'message' => __( "Dear [[CLIENT_NAME]].\nThank you for choosing [[COMPANY_NAME]]. We hope you were satisfied with your [[SERVICE_NAME]].\nThank you and we look forward to seeing you again soon.\n[[COMPANY_NAME]]\n[[COMPANY_PHONE]]\n[[COMPANY_WEBSITE]]", 'ab' ),
                'active'  => 0,
            ),
            array(
                'gateway' => 'sms',
                'type'    => 'staff_agenda',
                'subject' => '',
                'message' => __( "Hello.\nYour agenda for tomorrow is:\n[[NEXT_DAY_AGENDA]]", 'ab' ),
                'active'  => 0,
            ),
            array(
                'gateway' => 'sms',
                'type'    => 'staff_cancelled_appointment',
                'subject' => '',
                'message' => __( "Hello.\nThe following booking has been cancelled.\nService: [[SERVICE_NAME]]\nDate: [[APPOINTMENT_DATE]]\nTime: [[APPOINTMENT_TIME]]\nClient name: [[CLIENT_NAME]]\nClient phone: [[CLIENT_PHONE]]\nClient email: [[CLIENT_EMAIL]]", 'ab' ),
                'active'  => 0,
            ),
            array(
                'gateway' => 'sms',
                'type'    => 'client_new_wp_user',
                'subject' => '',
                'message' => __( "Hello.\nAn account was created for you at [[SITE_ADDRESS]]\nYour user details:\nuser: [[NEW_USERNAME]]\npassword: [[NEW_PASSWORD]]\n\nThanks.", 'ab' ),
                'active'  => 1,
            ),
        );
        /**
         * Options.
         */
        $this->options = array(
            'ab_data_loaded'                         => '0',
            // DB version.
            'ab_db_version'                          => ab_plugin_get_version(),
            // Timestamp when the plugin was installed.
            'ab_installation_time'                   => time(),
            // Settings.
            'ab_settings_company_name'               => '',
            'ab_settings_company_logo'               => '',
            'ab_settings_company_logo_path'          => '',
            'ab_settings_company_logo_url'           => '',
            'ab_settings_company_address'            => '',
            'ab_settings_company_phone'              => '',
            'ab_settings_company_website'            => '',
            'ab_settings_pay_locally'                => '1',
            'ab_settings_sender_name'                => get_option( 'blogname' ),
            'ab_settings_sender_email'               => get_option( 'admin_email' ),
            'ab_settings_time_slot_length'           => '15',
            'ab_settings_minimum_time_prior_booking' => '0',
            'ab_settings_maximum_available_days_for_booking' => '365',
            'ab_settings_use_client_time_zone'       => '0',
            'ab_settings_create_account'             => '0',
            'ab_settings_coupons'                    => '0',
            'ab_settings_google_client_id'           => '',
            'ab_settings_google_client_secret'       => '',
            'ab_settings_google_two_way_sync'        => 1,
            'ab_settings_google_limit_events'        => 50,
            'ab_settings_google_event_title'         => '[[SERVICE_NAME]]',
            'ab_settings_final_step_url'             => '',
            'ab_settings_allow_staff_members_edit_profile' => 1,
            'ab_settings_link_assets_method'         => 'enqueue',
            // Business hours.
            'ab_settings_monday_start'               => '08:00',
            'ab_settings_monday_end'                 => '18:00',
            'ab_settings_tuesday_start'              => '08:00',
            'ab_settings_tuesday_end'                => '18:00',
            'ab_settings_wednesday_start'            => '08:00',
            'ab_settings_wednesday_end'              => '18:00',
            'ab_settings_thursday_start'             => '08:00',
            'ab_settings_thursday_end'               => '18:00',
            'ab_settings_friday_start'               => '08:00',
            'ab_settings_friday_end'                 => '18:00',
            'ab_settings_saturday_start'             => '',
            'ab_settings_saturday_end'               => '',
            'ab_settings_sunday_start'               => '',
            'ab_settings_sunday_end'                 => '',
            // Cancel appointment page url.
            'ab_settings_cancel_page_url'            => home_url(),
            // Appearance.
            'ab_appearance_text_info_first_step'     => __( 'Please select service: ', 'ab' ),
            'ab_appearance_text_info_second_step'    => __( "Below you can find a list of available time slots for [[SERVICE_NAME]] by [[STAFF_NAME]].\nClick on a time slot to proceed with booking.", 'ab' ),
            'ab_appearance_text_info_third_step'     => __( "You selected a booking for [[SERVICE_NAME]] by [[STAFF_NAME]] at [[SERVICE_TIME]] on [[SERVICE_DATE]]. The price for the service is [[SERVICE_PRICE]].\nPlease provide your details in the form below to proceed with booking.", 'ab' ),
            'ab_appearance_text_info_fourth_step'    => __( 'Please tell us how you would like to pay: ', 'ab' ),
            'ab_appearance_text_info_fifth_step'     => __( 'Thank you! Your booking is complete. An email with details of your booking has been sent to you.', 'ab' ),
            'ab_appearance_text_info_coupon'         => __( 'The price for the service is [[SERVICE_PRICE]].', 'ab' ),
            'ab_appearance_color'                    => '#f4662f',  // booking form color
            'ab_appearance_text_step_service'        => __( "Service", "ab" ),
            'ab_appearance_text_step_time'           => __( "Time", "ab" ),
            'ab_appearance_text_step_details'        => __( "Details", "ab" ),
            'ab_appearance_text_step_payment'        => __( "Payment", "ab" ),
            'ab_appearance_text_step_done'           => __( "Done", "ab" ),
            'ab_appearance_text_label_category'      => __( "Category", "ab" ),
            'ab_appearance_text_label_service'       => __( "Service", "ab" ),
            'ab_appearance_text_label_employee'      => __( "Employee", "ab" ),
            'ab_appearance_text_label_select_date'   => __( 'I\'m available on or after', 'ab' ),
            'ab_appearance_text_label_start_from'    => __( 'Start from', 'ab' ),
            'ab_appearance_text_label_finish_by'     => __( 'Finish by', 'ab' ),
            'ab_appearance_text_label_name'          => __( 'Name', 'ab' ),
            'ab_appearance_text_label_phone'         => __( 'Phone', 'ab' ),
            'ab_appearance_text_label_email'         => __( 'Email', 'ab' ),
            'ab_appearance_text_label_coupon'        => __( 'Coupon', 'ab' ),
            'ab_appearance_text_label_pay_locally'   => __( 'I will pay locally', 'ab' ),
            'ab_appearance_text_label_number_of_persons' => __( 'Number of persons', 'ab' ),
            'ab_appearance_text_option_service'      => __( 'Select service', 'ab' ),
            'ab_appearance_text_option_category'     => __( 'Select category', 'ab' ),
            'ab_appearance_text_option_employee'     => __( 'Any', 'ab' ),
            // Progress tracker.
            'ab_appearance_show_progress_tracker'    => '1',
            // Time slots setting.
            'ab_appearance_show_blocked_timeslots'   => '0',
            'ab_appearance_show_day_one_column'      => '0',
            'ab_appearance_show_calendar'            => '0',
            // Envato Marketplace Purchase Code.
            'ab_envato_purchase_code'                => '',
            // PayPal.
            'ab_paypal_api_username'                 => '',
            'ab_paypal_api_password'                 => '',
            'ab_paypal_api_signature'                => '',
            'ab_paypal_ec_mode'                      => '',  // ".sandbox" or ""
            'ab_paypal_type'                         => 'disabled',
            'ab_paypal_id'                           => '',
            'ab_paypal_currency'                     => 'USD',
            // Authorize.net
            'ab_authorizenet_api_login_id'           => '',
            'ab_authorizenet_transaction_key'        => '',
            'ab_authorizenet_sandbox'                => 0,
            'ab_authorizenet_type'                   => 'disabled',
            // Stripe.
            'ab_stripe'                              => '0',
            'ab_stripe_secret_key'                   => '',
            // Custom Fields.
            'ab_custom_fields'                       => '[{"type":"textarea","label":' . json_encode( __( 'Notes', 'ab' ) ) . ',"required":false,"id":1}]',
            // WooCommerce.
            'ab_woocommerce'                         => '0',
            'ab_woocommerce_product'                 => '',
            'ab_woocommerce_cart_info_name'          => __( 'Appointment', 'ab' ),
            'ab_woocommerce_cart_info_value'         => __( 'Date', 'ab' ) . ": [[APPOINTMENT_DATE]]\n" . __( 'Time', 'ab' ) . ": [[APPOINTMENT_TIME]]\n" . __( 'Service', 'ab' ) . ": [[SERVICE_NAME]]",
            // SMS.
            'ab_sms_token'                           => '',
            'ab_sms_username'                        => '',
            'ab_sms_administrator_phone'             => '',
            'ab_sms_default_country_code'            => '',
        );
    }

    /**
     * Install.
     */
    public function install()
    {
        // Create tables and load data if it hasn't been loaded yet.
        if ( ! get_option( 'ab_data_loaded' ) ) {
            $this->_create_tables();
            $this->_load_data();
        }
        update_option( 'ab_data_loaded', '1' );
    }

    /**
     * Uninstall.
     */
    public function uninstall()
    {
        $this->_remove_data();
        $this->_drop_tables();
    }

    /**
     * Load data.
     */
    private function _load_data()
    {
        /** @global wpdb $wpdb */
        global $wpdb;

        // Insert notifications.
        foreach ( $this->notifications as $data ) {
            $wpdb->insert( AB_Notification::getTableName(), array(
                'gateway' => $data['gateway'],
                'type'    => $data['type'],
                'subject' => $data['subject'],
                'message' => $data['message'],
                'active'  => $data['active'],
            ) );
        }

        // Add options.
        foreach ( $this->options as $name => $value ) {
            add_option( $name, $value, '', 'yes' );
        }
    }

    /**
     * Remove data.
     */
    private function _remove_data()
    {

        // Remove options.
        foreach ( $this->options as $name => $value ) {
            delete_option( $name );
        }

        // Remove user meta.
        delete_metadata( 'user', null, 'ab_dismiss_admin_notice', '', true );
    }

    /**
     * Create tables in database.
     */
    private function _create_tables()
    {
        /** @global wpdb $wpdb */
        global $wpdb;

        $wpdb->query(
            'CREATE TABLE IF NOT EXISTS `' . AB_Staff::getTableName() . '` (
                `id`                 INT UNSIGNED NOT NULL AUTO_INCREMENT PRIMARY KEY,
                `wp_user_id`         BIGINT(20) UNSIGNED,
                `avatar_url`         VARCHAR(255) DEFAULT "",
                `avatar_path`        VARCHAR(255) DEFAULT "",
                `full_name`          VARCHAR(128) DEFAULT "",
                `email`              VARCHAR(128) DEFAULT "",
                `phone`              VARCHAR(128) DEFAULT "",
                `google_data`        VARCHAR(255) DEFAULT "",
                `google_calendar_id` VARCHAR(255) DEFAULT "",
                `position`           INT NOT NULL DEFAULT 9999
            ) ENGINE = INNODB
            DEFAULT CHARACTER SET = utf8
            COLLATE = utf8_general_ci'
        );

        $wpdb->query(
            'CREATE TABLE IF NOT EXISTS `' . AB_Category::getTableName() . '` (
                `id`       INT UNSIGNED NOT NULL AUTO_INCREMENT PRIMARY KEY,
                `name`     VARCHAR(255) NOT NULL,
                `position` INT NOT NULL DEFAULT 9999
             ) ENGINE = INNODB
             DEFAULT CHARACTER SET = utf8
             COLLATE = utf8_general_ci'
        );

        $wpdb->query(
            'CREATE TABLE IF NOT EXISTS `' . AB_Service::getTableName() . '` (
                `id`          INT UNSIGNED NOT NULL AUTO_INCREMENT PRIMARY KEY,
                `title`       VARCHAR(255) DEFAULT "",
                `duration`    INT NOT NULL DEFAULT 900,
                `price`       DECIMAL(10,2) NOT NULL DEFAULT 0.00,
                `color`       VARCHAR(255) NOT NULL DEFAULT "#FFFFFF",
                `category_id` INT UNSIGNED,
                `capacity`    INT NOT NULL DEFAULT 1,
                `position`    INT NOT NULL DEFAULT 9999,
                CONSTRAINT
                    FOREIGN KEY (category_id)
                    REFERENCES ' . AB_Category::getTableName() . '(id)
                    ON DELETE SET NULL
                    ON UPDATE CASCADE
            ) ENGINE = INNODB
            DEFAULT CHARACTER SET = utf8
            COLLATE = utf8_general_ci'
        );

        $wpdb->query(
            'CREATE TABLE IF NOT EXISTS `' . AB_StaffService::getTableName() . '` (
                `id`         INT UNSIGNED NOT NULL AUTO_INCREMENT PRIMARY KEY,
                `staff_id`   INT UNSIGNED NOT NULL,
                `service_id` INT UNSIGNED NOT NULL,
                `price`      DECIMAL(10,2) NOT NULL DEFAULT 0.00,
                `capacity`   INT NOT NULL DEFAULT 1,
                UNIQUE KEY unique_ids_idx (staff_id, service_id),
                CONSTRAINT
                    FOREIGN KEY (staff_id)
                    REFERENCES ' . AB_Staff::getTableName() . '(id)
                    ON DELETE CASCADE
                    ON UPDATE CASCADE,
                CONSTRAINT
                    FOREIGN KEY (service_id)
                    REFERENCES ' . AB_Service::getTableName() . '(id)
                    ON DELETE CASCADE
                    ON UPDATE CASCADE
            ) ENGINE = INNODB
            DEFAULT CHARACTER SET = utf8
            COLLATE = utf8_general_ci'
        );

        $wpdb->query(
            'CREATE TABLE IF NOT EXISTS `' . AB_StaffScheduleItem::getTableName() . '` (
                `id`         INT UNSIGNED NOT NULL AUTO_INCREMENT PRIMARY KEY,
                `staff_id`   INT UNSIGNED NOT NULL,
                `day_index`  INT UNSIGNED NOT NULL,
                `start_time` TIME,
                `end_time`   TIME,
                UNIQUE KEY unique_ids_idx (staff_id, day_index),
                CONSTRAINT
                    FOREIGN KEY (staff_id)
                    REFERENCES ' . AB_Staff::getTableName() . '(id)
                    ON DELETE CASCADE
                    ON UPDATE CASCADE
             ) ENGINE = INNODB
             DEFAULT CHARACTER SET = utf8
             COLLATE = utf8_general_ci'
        );

        $wpdb->query(
            'CREATE TABLE IF NOT EXISTS `' . AB_ScheduleItemBreak::getTableName() . '` (
                `id`                     INT UNSIGNED NOT NULL AUTO_INCREMENT PRIMARY KEY,
                `staff_schedule_item_id` INT UNSIGNED NOT NULL,
                `start_time`             TIME,
                `end_time`               TIME,
                CONSTRAINT
                    FOREIGN KEY (staff_schedule_item_id)
                    REFERENCES ' . AB_StaffScheduleItem::getTableName() . '(id)
                    ON DELETE CASCADE
                    ON UPDATE CASCADE
             ) ENGINE = INNODB
             DEFAULT CHARACTER SET = utf8
             COLLATE = utf8_general_ci'
        );

        $wpdb->query(
            'CREATE TABLE IF NOT EXISTS `' . AB_Notification::getTableName() . '` (
                `id`          INT UNSIGNED NOT NULL AUTO_INCREMENT PRIMARY KEY,
                `gateway`     ENUM("email","sms") NOT NULL DEFAULT "email",
                `type`        VARCHAR(255) NOT NULL DEFAULT "",
                `active`      TINYINT(1) NOT NULL DEFAULT 0,
                `copy`        TINYINT(1) NOT NULL DEFAULT 0,
                `subject`     VARCHAR(255) NOT NULL DEFAULT "",
                `message`     TEXT
            ) ENGINE = INNODB
            DEFAULT CHARACTER SET = utf8
            COLLATE = utf8_general_ci'
        );

        $wpdb->query(
            'CREATE TABLE IF NOT EXISTS `' . AB_Customer::getTableName() . '` (
                `id`         INT UNSIGNED NOT NULL AUTO_INCREMENT PRIMARY KEY,
                `wp_user_id` BIGINT(20) UNSIGNED,
                `name`       VARCHAR(255) NOT NULL DEFAULT "",
                `phone`      VARCHAR(255) NOT NULL DEFAULT "",
                `email`      VARCHAR(255) NOT NULL DEFAULT "",
                `notes`      TEXT NOT NULL DEFAULT ""
            ) ENGINE = INNODB
            DEFAULT CHARACTER SET = utf8
            COLLATE = utf8_general_ci'
        );

        $wpdb->query(
            'CREATE TABLE IF NOT EXISTS `' . AB_Appointment::getTableName() . '` (
                `id`              INT UNSIGNED NOT NULL AUTO_INCREMENT PRIMARY KEY,
                `staff_id`        INT UNSIGNED NOT NULL,
                `service_id`      INT UNSIGNED,
                `start_date`      DATETIME NOT NULL,
                `end_date`        DATETIME NOT NULL,
                `google_event_id` VARCHAR(255) DEFAULT NULL,
                CONSTRAINT
                    FOREIGN KEY (staff_id)
                    REFERENCES ' . AB_Staff::getTableName() . '(id)
                    ON DELETE CASCADE
                    ON UPDATE CASCADE,
                CONSTRAINT
                    FOREIGN KEY (service_id)
                    REFERENCES ' . AB_Service::getTableName() . '(id)
                    ON DELETE CASCADE
                    ON UPDATE CASCADE
            ) ENGINE = INNODB
            DEFAULT CHARACTER SET = utf8
            COLLATE = utf8_general_ci'
        );

        $wpdb->query(
            'CREATE TABLE IF NOT EXISTS `' . AB_Holiday::getTableName() . '` (
                  `id`           INT UNSIGNED NOT NULL AUTO_INCREMENT PRIMARY KEY,
                  `staff_id`     INT UNSIGNED NULL DEFAULT NULL,
                  `parent_id`    INT UNSIGNED NULL DEFAULT NULL,
                  `date`         DATE NOT NULL,
                  `repeat_event` TINYINT(1) NOT NULL DEFAULT 0,
                  `title`        VARCHAR(255) NOT NULL DEFAULT "",
                  CONSTRAINT
                      FOREIGN KEY (staff_id)
                      REFERENCES ' . AB_Staff::getTableName() . '(id)
                      ON DELETE CASCADE
              ) ENGINE = INNODB
              DEFAULT CHARACTER SET = utf8
              COLLATE = utf8_general_ci'
        );

        $wpdb->query(
            'CREATE TABLE IF NOT EXISTS `' . AB_CustomerAppointment::getTableName() . '` (
                `id`                INT UNSIGNED NOT NULL AUTO_INCREMENT PRIMARY KEY,
                `customer_id`       INT UNSIGNED NOT NULL,
                `appointment_id`    INT UNSIGNED NOT NULL,
                `number_of_persons` INT UNSIGNED NOT NULL DEFAULT 1,
                `custom_fields`     TEXT,
                `coupon_code`       VARCHAR(255) DEFAULT NULL,
                `coupon_discount`   DECIMAL(10,2) DEFAULT NULL,
                `coupon_deduction`  DECIMAL(10,2) DEFAULT NULL,
                `token`             VARCHAR(255) DEFAULT NULL,
                `time_zone_offset`  INT,
                CONSTRAINT
                    FOREIGN KEY (customer_id)
                    REFERENCES  ' . AB_Customer::getTableName() . '(id)
                    ON DELETE   CASCADE
                    ON UPDATE   CASCADE,
                CONSTRAINT
                    FOREIGN KEY (appointment_id)
                    REFERENCES  ' . AB_Appointment::getTableName() . '(id)
                    ON DELETE   CASCADE
                    ON UPDATE   CASCADE
            ) ENGINE = INNODB
            DEFAULT CHARACTER SET = utf8
            COLLATE = utf8_general_ci'
        );

        $wpdb->query(
            'CREATE TABLE IF NOT EXISTS `' . AB_Payment::getTableName() . '` (
                `id`                      INT UNSIGNED NOT NULL AUTO_INCREMENT PRIMARY KEY,
                `created`                 DATETIME NOT NULL,
                `type`                    ENUM("local","coupon","paypal","authorizeNet","stripe") NOT NULL DEFAULT "local",
                `customer_appointment_id` INT UNSIGNED NOT NULL,
                `token`                   VARCHAR(255) NOT NULL,
                `transaction`             VARCHAR(255) NOT NULL,
                `total`                   DECIMAL(10,2) NOT NULL DEFAULT 0.00,
                CONSTRAINT
                    FOREIGN KEY (customer_appointment_id)
                    REFERENCES  ' . AB_CustomerAppointment::getTableName() . '(id)
                    ON DELETE   CASCADE
                    ON UPDATE   CASCADE
            ) ENGINE = INNODB
            DEFAULT CHARACTER SET = utf8
            COLLATE = utf8_general_ci'
        );

        $wpdb->query(
            'CREATE TABLE IF NOT EXISTS `' . AB_Coupon::getTableName() . '` (
                `id`          INT UNSIGNED NOT NULL AUTO_INCREMENT PRIMARY KEY,
                `code`        VARCHAR(255) NOT NULL DEFAULT "",
                `discount`    DECIMAL(3,0) NOT NULL DEFAULT 0,
                `deduction`   DECIMAL(10,2) NOT NULL DEFAULT 0,
                `usage_limit` INT UNSIGNED NOT NULL DEFAULT 1,
                `used`        INT UNSIGNED NOT NULL DEFAULT 0
            ) ENGINE = INNODB
            DEFAULT CHARACTER SET = utf8
            COLLATE = utf8_general_ci'
        );

        $wpdb->query(
            'CREATE TABLE IF NOT EXISTS `' . AB_SentNotification::getTableName() . '` (
                `id`                      INT UNSIGNED NOT NULL AUTO_INCREMENT PRIMARY KEY,
                `customer_appointment_id` INT UNSIGNED,
                `staff_id`                INT UNSIGNED,
                `gateway`                 ENUM("email","sms") NOT NULL DEFAULT "email",
                `type`                    VARCHAR(60) NOT NULL,
                `created`                 DATETIME NOT NULL,
                CONSTRAINT
                    FOREIGN KEY (customer_appointment_id)
                    REFERENCES  ' . AB_CustomerAppointment::getTableName() . '(id)
                    ON DELETE   CASCADE
                    ON UPDATE   CASCADE,
                CONSTRAINT
                    FOREIGN KEY (staff_id)
                    REFERENCES  ' . AB_Staff::getTableName() . '(id)
                    ON DELETE   CASCADE
                    ON UPDATE   CASCADE
              ) ENGINE = INNODB
              DEFAULT CHARACTER SET = utf8
              COLLATE = utf8_general_ci'
        );
    }

    private function _drop_fk( $ab_tables )
    {
        /** @var wpdb $wpdb */
        global $wpdb;

        $get_ab_foreign_keys =
            'SELECT table_name, constraint_name FROM information_schema.key_column_usage
                WHERE REFERENCED_TABLE_SCHEMA=SCHEMA() AND
                    REFERENCED_TABLE_NAME IN (' . implode( ', ', array_fill( 0, count( $ab_tables ), '%s' ) ) .
            ')';
        $schema = $wpdb->get_results( $wpdb->prepare( $get_ab_foreign_keys, $ab_tables ) );
        foreach ( $schema as $foreign_key )
        {
            $wpdb->query( "ALTER TABLE `$foreign_key->table_name` DROP FOREIGN KEY `$foreign_key->constraint_name`" );
        }
    }

    private function _drop_tables()
    {
        /** @var wpdb $wpdb */
        global $wpdb;

        $ab_tables = array(
            AB_Appointment::getTableName(),
            AB_Category::getTableName(),
            AB_Coupon::getTableName(),
            AB_Customer::getTableName(),
            AB_CustomerAppointment::getTableName(),
            AB_Holiday::getTableName(),
            AB_Notification::getTableName(),
            AB_Payment::getTableName(),
            AB_ScheduleItemBreak::getTableName(),
            AB_SentNotification::getTableName(),
            AB_Service::getTableName(),
            AB_Staff::getTableName(),
            AB_StaffScheduleItem::getTableName(),
            AB_StaffService::getTableName(),
        );
        $this->_drop_fk( $ab_tables );
        $wpdb->query( 'DROP TABLE IF EXISTS `'.implode( '`, `', $ab_tables ).'` CASCADE;' );
    }
}