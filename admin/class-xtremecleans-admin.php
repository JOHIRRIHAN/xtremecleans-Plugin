<?php
/**
 * Admin Pages Handler Class
 *
 * Handles all admin page logic and settings
 *
 * @package XtremeCleans
 * @subpackage Admin
 * @since 1.0.0
 */

// Exit if accessed directly
if (!defined('ABSPATH')) {
    exit;
}

/**
 * XtremeCleans_Admin Class
 *
 * @since 1.0.0
 */
class XtremeCleans_Admin {
    
    /**
     * Constructor
     *
     * @since 1.0.0
     */
    public function __construct() {
        $this->init_hooks();
    }
    
    /**
     * Initialize hooks
     *
     * @since 1.0.0
     */
    private function init_hooks() {
        add_action('admin_menu', array($this, 'add_admin_menu'));
        add_action('admin_init', array($this, 'register_settings'));
        add_action('admin_enqueue_scripts', array($this, 'enqueue_admin_scripts'));
        add_action('admin_notices', array($this, 'display_admin_notices'));
        
        // Register REST API routes for webhooks
        add_action('rest_api_init', array($this, 'register_rest_routes'));
        
        // Refresh API credentials when settings are updated
        add_action('update_option_xtremecleans_api_url', array($this, 'refresh_api_credentials'));
        add_action('update_option_xtremecleans_api_key', array($this, 'refresh_api_credentials'));
        
        // AJAX handlers for Zip Zone
        add_action('wp_ajax_xtremecleans_add_zip_zone', array($this, 'ajax_add_zip_zone'));
        add_action('wp_ajax_xtremecleans_update_zip_zone', array($this, 'ajax_update_zip_zone'));
        add_action('wp_ajax_xtremecleans_delete_zip_zone', array($this, 'ajax_delete_zip_zone'));
        add_action('wp_ajax_xtremecleans_clear_all_zones', array($this, 'ajax_clear_all_zones'));
        
        // Public AJAX handler for getting ZIP code data (frontend)
        add_action('wp_ajax_xtremecleans_get_zip_data', array($this, 'ajax_get_zip_data'));
        add_action('wp_ajax_nopriv_xtremecleans_get_zip_data', array($this, 'ajax_get_zip_data'));
        
        // Public AJAX handlers for ZIP validation and lead collection
        add_action('wp_ajax_xtremecleans_validate_zip_zone', array($this, 'ajax_validate_zip_zone'));
        add_action('wp_ajax_nopriv_xtremecleans_validate_zip_zone', array($this, 'ajax_validate_zip_zone'));
        add_action('wp_ajax_xtremecleans_save_lead', array($this, 'ajax_save_lead'));
        add_action('wp_ajax_nopriv_xtremecleans_save_lead', array($this, 'ajax_save_lead'));
        
        // Service names handler
        add_action('wp_ajax_xtremecleans_get_service_names', array($this, 'ajax_get_service_names'));
        add_action('wp_ajax_nopriv_xtremecleans_get_service_names', array($this, 'ajax_get_service_names'));
        add_action('wp_ajax_xtremecleans_add_zone_name', array($this, 'ajax_add_zone_name'));
        
        // Service items handler
        add_action('wp_ajax_xtremecleans_get_service_items', array($this, 'ajax_get_service_items'));
        add_action('wp_ajax_nopriv_xtremecleans_get_service_items', array($this, 'ajax_get_service_items'));
        
        // Service items management AJAX handlers
        add_action('wp_ajax_xtremecleans_add_service_item', array($this, 'ajax_add_service_item'));
        add_action('wp_ajax_xtremecleans_add_multiple_service_items', array($this, 'ajax_add_multiple_service_items'));
        add_action('wp_ajax_xtremecleans_update_service_item', array($this, 'ajax_update_service_item'));
        add_action('wp_ajax_xtremecleans_delete_service_item', array($this, 'ajax_delete_service_item'));
        
        // Stripe Payment AJAX handlers
        add_action('wp_ajax_xtremecleans_create_payment_intent', array($this, 'ajax_create_payment_intent'));
        add_action('wp_ajax_nopriv_xtremecleans_create_payment_intent', array($this, 'ajax_create_payment_intent'));
        add_action('wp_ajax_xtremecleans_confirm_payment', array($this, 'ajax_confirm_payment'));
        add_action('wp_ajax_nopriv_xtremecleans_confirm_payment', array($this, 'ajax_confirm_payment'));
        add_action('wp_ajax_xtremecleans_place_order', array($this, 'ajax_place_order'));
        add_action('wp_ajax_nopriv_xtremecleans_place_order', array($this, 'ajax_place_order'));
        add_action('wp_ajax_xtremecleans_get_booked_slots', array($this, 'ajax_get_booked_slots'));
        add_action('wp_ajax_nopriv_xtremecleans_get_booked_slots', array($this, 'ajax_get_booked_slots'));
<<<<<<< HEAD
=======
        add_action('wp_ajax_xtremecleans_get_jobber_availability', array($this, 'ajax_get_jobber_availability'));
        add_action('wp_ajax_nopriv_xtremecleans_get_jobber_availability', array($this, 'ajax_get_jobber_availability'));
>>>>>>> 3378c4f (plugin last vertation updated in admin dashboard)
        
        // Orders AJAX handlers
        add_action('wp_ajax_xtremecleans_get_order_details', array($this, 'ajax_get_order_details'));
        add_action('wp_ajax_xtremecleans_delete_order', array($this, 'ajax_delete_order'));
        add_action('wp_ajax_xtremecleans_export_orders', array($this, 'ajax_export_orders'));
        add_action('wp_ajax_xtremecleans_sync_order_to_jobber', array($this, 'ajax_sync_order_to_jobber'));
        add_action('wp_ajax_xtremecleans_get_recent_logs', array($this, 'ajax_get_recent_logs'));
        
        // Leads AJAX handlers
        add_action('wp_ajax_xtremecleans_delete_lead', array($this, 'ajax_delete_lead'));
        add_action('admin_post_xtremecleans_export_leads', array($this, 'export_leads'));
        
        // Email test handler
        add_action('wp_ajax_xtremecleans_test_email', array($this, 'ajax_test_email'));
        
        // Jobber connection test handler
        add_action('wp_ajax_xtremecleans_test_jobber_connection', array($this, 'ajax_test_jobber_connection'));
        add_action('wp_ajax_xtremecleans_clear_jobber_token', array($this, 'ajax_clear_jobber_token'));
        add_action('wp_ajax_xtremecleans_fix_jobber_scopes', array($this, 'ajax_fix_jobber_scopes'));
        add_action('wp_ajax_xtremecleans_sync_services_from_jobber', array($this, 'ajax_sync_services_from_jobber'));
        add_action('wp_ajax_xtremecleans_toggle_jobber_services_only', array($this, 'ajax_toggle_jobber_services_only'));
        add_action('wp_ajax_xtremecleans_toggle_zip_based_jobber', array($this, 'ajax_toggle_zip_based_jobber'));
        add_action('wp_ajax_xtremecleans_toggle_fetch_from_jobber', array($this, 'ajax_toggle_fetch_from_jobber'));
        
        // Export/Import handlers
        add_action('admin_post_xtremecleans_export_zip_zones', array($this, 'export_zip_zones'));
        add_action('admin_post_xtremecleans_import_zip_zones', array($this, 'import_zip_zones'));
    }
    
    /**
     * Add admin menu
     *
     * @since 1.0.0
     */
    public function add_admin_menu() {
        // Check user capabilities
        if (!current_user_can('manage_options')) {
            return;
        }
        
        // Main menu
        add_menu_page(
            __('XtremeCleans', 'xtremecleans'),
            __('XtremeCleans', 'xtremecleans'),
            'manage_options',
            'xtremecleans',
            array($this, 'render_dashboard_page'),
            'dashicons-admin-generic',
            30
        );
        
        // Dashboard (submenu, same as main)
        add_submenu_page(
            'xtremecleans',
            __('Dashboard', 'xtremecleans'),
            __('Dashboard', 'xtremecleans'),
            'manage_options',
            'xtremecleans',
            array($this, 'render_dashboard_page')
        );
        
        // Zip Zone
        add_submenu_page(
            'xtremecleans',
            __('Zip Zone', 'xtremecleans'),
            __('Zip Zone', 'xtremecleans'),
            'manage_options',
            'xtremecleans-zip-zone',
            array($this, 'render_zip_zone_page')
        );
        
        // Service Items
        add_submenu_page(
            'xtremecleans',
            __('Service Items', 'xtremecleans'),
            __('Service Items', 'xtremecleans'),
            'manage_options',
            'xtremecleans-service-items',
            array($this, 'render_service_items_page')
        );
        
        // Orders
        add_submenu_page(
            'xtremecleans',
            __('Orders', 'xtremecleans'),
            __('Orders', 'xtremecleans'),
            'manage_options',
            'xtremecleans-orders',
            array($this, 'render_orders_page')
        );
        
        // Leads
        add_submenu_page(
            'xtremecleans',
            __('Leads', 'xtremecleans'),
            __('Leads', 'xtremecleans'),
            'manage_options',
            'xtremecleans-leads',
            array($this, 'render_leads_page')
        );
        
        // Settings
        add_submenu_page(
            'xtremecleans',
            __('Settings', 'xtremecleans'),
            __('Settings', 'xtremecleans'),
            'manage_options',
            'xtremecleans-settings',
            array($this, 'render_settings_page')
        );
        
        // Shortcodes
        add_submenu_page(
            'xtremecleans',
            __('Shortcodes', 'xtremecleans'),
            __('Shortcodes', 'xtremecleans'),
            'manage_options',
            'xtremecleans-shortcodes',
            array($this, 'render_shortcodes_page')
        );
        
        // API Test
        add_submenu_page(
            'xtremecleans',
            __('API Test', 'xtremecleans'),
            __('API Test', 'xtremecleans'),
            'manage_options',
            'xtremecleans-api-test',
            array($this, 'render_api_test_page')
        );
    }
    
    /**
     * Register settings
     *
     * @since 1.0.0
     */
    public function register_settings() {
        // General Settings
        register_setting('xtremecleans_settings_general', 'xtremecleans_api_url', array(
            'sanitize_callback' => 'xtremecleans_sanitize_url',
        ));
        register_setting('xtremecleans_settings_general', 'xtremecleans_api_key', array(
            'sanitize_callback' => 'xtremecleans_sanitize_text',
        ));
        register_setting('xtremecleans_settings_general', 'xtremecleans_cache_duration', array(
            'sanitize_callback' => 'absint',
        ));
        register_setting('xtremecleans_settings_general', 'xtremecleans_enable_logging', array(
            'sanitize_callback' => 'absint',
        ));
        // Jobber Settings
        register_setting('xtremecleans_settings_jobber', 'xtremecleans_jobber_client_id', array(
            'sanitize_callback' => 'xtremecleans_sanitize_text',
        ));
        register_setting('xtremecleans_settings_jobber', 'xtremecleans_jobber_client_secret', array(
            'sanitize_callback' => 'xtremecleans_sanitize_text',
        ));
        
        // Payment Settings
        register_setting('xtremecleans_settings_payment', 'xtremecleans_stripe_enabled', array(
            'sanitize_callback' => array($this, 'sanitize_stripe_enabled'),
        ));
        register_setting('xtremecleans_settings_payment', 'xtremecleans_stripe_test_mode', array(
            'sanitize_callback' => 'absint',
        ));
        register_setting('xtremecleans_settings_payment', 'xtremecleans_stripe_publishable_key', array(
            'sanitize_callback' => 'xtremecleans_sanitize_text',
        ));
        register_setting('xtremecleans_settings_payment', 'xtremecleans_stripe_secret_key', array(
            'sanitize_callback' => 'xtremecleans_sanitize_text',
        ));
        register_setting('xtremecleans_settings_payment', 'xtremecleans_stripe_test_publishable_key', array(
            'sanitize_callback' => 'xtremecleans_sanitize_text',
        ));
        register_setting('xtremecleans_settings_payment', 'xtremecleans_stripe_test_secret_key', array(
            'sanitize_callback' => 'xtremecleans_sanitize_text',
        ));
        
        // Display Settings
        register_setting('xtremecleans_settings_display', 'xtremecleans_button_default_style', array(
            'sanitize_callback' => 'sanitize_html_class',
        ));
        register_setting('xtremecleans_settings_display', 'xtremecleans_form_email_recipient', array(
            'sanitize_callback' => 'xtremecleans_sanitize_email',
        ));
        register_setting('xtremecleans_settings_display', 'xtremecleans_custom_css', array(
            'sanitize_callback' => 'xtremecleans_sanitize_textarea',
        ));
        
        // Email Settings
        register_setting('xtremecleans_settings_email', 'xtremecleans_email_from_name', array(
            'sanitize_callback' => 'sanitize_text_field',
        ));
        register_setting('xtremecleans_settings_email', 'xtremecleans_email_from_address', array(
            'sanitize_callback' => 'xtremecleans_sanitize_email',
        ));
        register_setting('xtremecleans_settings_email', 'xtremecleans_email_admin_notification', array(
            'sanitize_callback' => 'xtremecleans_sanitize_email',
        ));
        register_setting('xtremecleans_settings_email', 'xtremecleans_email_lead_subject', array(
            'sanitize_callback' => 'sanitize_text_field',
        ));
        register_setting('xtremecleans_settings_email', 'xtremecleans_email_lead_template', array(
            'sanitize_callback' => 'xtremecleans_sanitize_textarea',
        ));
        register_setting('xtremecleans_settings_email', 'xtremecleans_email_user_subject', array(
            'sanitize_callback' => 'sanitize_text_field',
        ));
        register_setting('xtremecleans_settings_email', 'xtremecleans_email_user_template', array(
            'sanitize_callback' => 'xtremecleans_sanitize_textarea',
        ));
        
<<<<<<< HEAD
=======
        // Travel Time (Google Maps) Settings
        register_setting('xtremecleans_settings_travel', 'xtremecleans_google_api_key', array(
            'sanitize_callback' => 'sanitize_text_field',
        ));
        register_setting('xtremecleans_settings_travel', 'xtremecleans_travel_enabled', array(
            'sanitize_callback' => 'absint',
        ));
        register_setting('xtremecleans_settings_travel', 'xtremecleans_default_job_duration_minutes', array(
            'sanitize_callback' => 'absint',
        ));
        register_setting('xtremecleans_settings_travel', 'xtremecleans_travel_fallback_message', array(
            'sanitize_callback' => 'sanitize_textarea_field',
        ));
        register_setting('xtremecleans_settings_travel', 'xtremecleans_travel_fallback_phone', array(
            'sanitize_callback' => 'sanitize_text_field',
        ));
        register_setting('xtremecleans_settings_travel', 'xtremecleans_slot_capacity_enabled', array(
            'sanitize_callback' => 'absint',
        ));
        register_setting('xtremecleans_settings_travel', 'xtremecleans_slot_capacity', array(
            'sanitize_callback' => array($this, 'sanitize_slot_capacity'),
        ));
        
>>>>>>> 3378c4f (plugin last vertation updated in admin dashboard)
        // Add settings sections
        $this->register_settings_sections();
        $this->register_settings_fields();
    }
    
    /**
     * Register settings sections
     *
     * @since 1.0.0
     */
    private function register_settings_sections() {
        add_settings_section(
            'xtremecleans_general_section',
            __('General Settings', 'xtremecleans'),
            array($this, 'render_general_section'),
            'xtremecleans-settings-general'
        );
        
        add_settings_section(
            'xtremecleans_display_section',
            __('Display Settings', 'xtremecleans'),
            array($this, 'render_display_section'),
            'xtremecleans-settings-display'
        );
        
        add_settings_section(
            'xtremecleans_email_section',
            __('Email Settings', 'xtremecleans'),
            array($this, 'render_email_section'),
            'xtremecleans-settings-email'
        );
        
        add_settings_section(
            'xtremecleans_payment_section',
            __('Payment Settings', 'xtremecleans'),
            array($this, 'render_payment_section'),
            'xtremecleans-settings-payment'
        );
        
        add_settings_section(
            'xtremecleans_jobber_section',
            __('Jobber Integration Settings', 'xtremecleans'),
            array($this, 'render_jobber_section'),
            'xtremecleans-settings-jobber'
        );
<<<<<<< HEAD
=======
        
        add_settings_section(
            'xtremecleans_travel_section',
            __('Travel Time (Google Maps)', 'xtremecleans'),
            array($this, 'render_travel_section'),
            'xtremecleans-settings-travel'
        );
>>>>>>> 3378c4f (plugin last vertation updated in admin dashboard)
    }
    
    /**
     * Register settings fields
     *
     * @since 1.0.0
     */
    private function register_settings_fields() {
        // General Settings Fields
        add_settings_field(
            'xtremecleans_api_url',
            __('API Base URL', 'xtremecleans'),
            array($this, 'render_api_url_field'),
            'xtremecleans-settings-general',
            'xtremecleans_general_section'
        );
        
        add_settings_field(
            'xtremecleans_api_key',
            __('API Key', 'xtremecleans'),
            array($this, 'render_api_key_field'),
            'xtremecleans-settings-general',
            'xtremecleans_general_section'
        );
        
        add_settings_field(
            'xtremecleans_webhook_url',
            __('Webhook URL', 'xtremecleans'),
            array($this, 'render_webhook_url_field'),
            'xtremecleans-settings-general',
            'xtremecleans_general_section'
        );
        
        add_settings_field(
            'xtremecleans_oauth_callback_url',
            __('OAuth Callback URL', 'xtremecleans'),
            array($this, 'render_oauth_callback_url_field'),
            'xtremecleans-settings-general',
            'xtremecleans_general_section'
        );
        
        // Jobber Settings Fields
        add_settings_field(
            'xtremecleans_jobber_client_id',
            __('Jobber Client ID', 'xtremecleans'),
            array($this, 'render_jobber_client_id_field'),
            'xtremecleans-settings-jobber',
            'xtremecleans_jobber_section'
        );
        
        add_settings_field(
            'xtremecleans_jobber_client_secret',
            __('Jobber Client Secret', 'xtremecleans'),
            array($this, 'render_jobber_client_secret_field'),
            'xtremecleans-settings-jobber',
            'xtremecleans_jobber_section'
        );
        
<<<<<<< HEAD
=======
        // Travel Time Settings Fields
        add_settings_field(
            'xtremecleans_travel_enabled',
            __('Enable 1-Hour Travel Rule', 'xtremecleans'),
            array($this, 'render_travel_enabled_field'),
            'xtremecleans-settings-travel',
            'xtremecleans_travel_section'
        );
        add_settings_field(
            'xtremecleans_google_api_key',
            __('Google API Key', 'xtremecleans'),
            array($this, 'render_google_api_key_field'),
            'xtremecleans-settings-travel',
            'xtremecleans_travel_section'
        );
        add_settings_field(
            'xtremecleans_default_job_duration_minutes',
            __('Default Job Duration (minutes)', 'xtremecleans'),
            array($this, 'render_default_job_duration_field'),
            'xtremecleans-settings-travel',
            'xtremecleans_travel_section'
        );
        add_settings_field(
            'xtremecleans_travel_fallback_message',
            __('Message when slot blocked', 'xtremecleans'),
            array($this, 'render_travel_fallback_message_field'),
            'xtremecleans-settings-travel',
            'xtremecleans_travel_section'
        );
        add_settings_field(
            'xtremecleans_travel_fallback_phone',
            __('Call/Text number in message', 'xtremecleans'),
            array($this, 'render_travel_fallback_phone_field'),
            'xtremecleans-settings-travel',
            'xtremecleans_travel_section'
        );
        add_settings_field(
            'xtremecleans_slot_capacity_enabled',
            __('Enable Slot Capacity Limit', 'xtremecleans'),
            array($this, 'render_slot_capacity_enabled_field'),
            'xtremecleans-settings-travel',
            'xtremecleans_travel_section'
        );
        add_settings_field(
            'xtremecleans_slot_capacity',
            __('Max Bookings Per Time Slot', 'xtremecleans'),
            array($this, 'render_slot_capacity_field'),
            'xtremecleans-settings-travel',
            'xtremecleans_travel_section'
        );
        
>>>>>>> 3378c4f (plugin last vertation updated in admin dashboard)
        // Payment Settings Fields
        add_settings_field(
            'xtremecleans_stripe_enabled',
            __('Enable Stripe Payments', 'xtremecleans'),
            array($this, 'render_stripe_enabled_field'),
            'xtremecleans-settings-payment',
            'xtremecleans_payment_section'
        );
        
        add_settings_field(
            'xtremecleans_stripe_test_mode',
            __('Stripe Test Mode', 'xtremecleans'),
            array($this, 'render_stripe_test_mode_field'),
            'xtremecleans-settings-payment',
            'xtremecleans_payment_section'
        );
        
        add_settings_field(
            'xtremecleans_stripe_publishable_key',
            __('Stripe Publishable Key (Live)', 'xtremecleans'),
            array($this, 'render_stripe_publishable_key_field'),
            'xtremecleans-settings-payment',
            'xtremecleans_payment_section'
        );
        
        add_settings_field(
            'xtremecleans_stripe_secret_key',
            __('Stripe Secret Key (Live)', 'xtremecleans'),
            array($this, 'render_stripe_secret_key_field'),
            'xtremecleans-settings-payment',
            'xtremecleans_payment_section'
        );
        
        add_settings_field(
            'xtremecleans_stripe_test_publishable_key',
            __('Stripe Publishable Key (Test)', 'xtremecleans'),
            array($this, 'render_stripe_test_publishable_key_field'),
            'xtremecleans-settings-payment',
            'xtremecleans_payment_section'
        );
        
        add_settings_field(
            'xtremecleans_stripe_test_secret_key',
            __('Stripe Secret Key (Test)', 'xtremecleans'),
            array($this, 'render_stripe_test_secret_key_field'),
            'xtremecleans-settings-payment',
            'xtremecleans_payment_section'
        );
        
        add_settings_field(
            'xtremecleans_cache_duration',
            __('Cache Duration (seconds)', 'xtremecleans'),
            array($this, 'render_cache_duration_field'),
            'xtremecleans-settings-general',
            'xtremecleans_general_section'
        );
        
        add_settings_field(
            'xtremecleans_enable_logging',
            __('Enable Logging', 'xtremecleans'),
            array($this, 'render_enable_logging_field'),
            'xtremecleans-settings-general',
            'xtremecleans_general_section'
        );
        
        // Display Settings Fields
        add_settings_field(
            'xtremecleans_button_default_style',
            __('Default Button Style', 'xtremecleans'),
            array($this, 'render_button_default_style_field'),
            'xtremecleans-settings-display',
            'xtremecleans_display_section'
        );
        
        add_settings_field(
            'xtremecleans_form_email_recipient',
            __('Form Email Recipient', 'xtremecleans'),
            array($this, 'render_form_email_recipient_field'),
            'xtremecleans-settings-display',
            'xtremecleans_display_section'
        );
        
        add_settings_field(
            'xtremecleans_custom_css',
            __('Custom CSS', 'xtremecleans'),
            array($this, 'render_custom_css_field'),
            'xtremecleans-settings-display',
            'xtremecleans_display_section'
        );
        
        // Email Settings Fields
        add_settings_field(
            'xtremecleans_email_from_name',
            __('Email From Name', 'xtremecleans'),
            array($this, 'render_email_from_name_field'),
            'xtremecleans-settings-email',
            'xtremecleans_email_section'
        );
        
        add_settings_field(
            'xtremecleans_email_from_address',
            __('Email From Address', 'xtremecleans'),
            array($this, 'render_email_from_address_field'),
            'xtremecleans-settings-email',
            'xtremecleans_email_section'
        );
        
        add_settings_field(
            'xtremecleans_email_admin_notification',
            __('Admin Notification Email', 'xtremecleans'),
            array($this, 'render_email_admin_notification_field'),
            'xtremecleans-settings-email',
            'xtremecleans_email_section'
        );
        
        add_settings_field(
            'xtremecleans_email_lead_subject',
            __('Lead Notification Subject', 'xtremecleans'),
            array($this, 'render_email_lead_subject_field'),
            'xtremecleans-settings-email',
            'xtremecleans_email_section'
        );
        
        add_settings_field(
            'xtremecleans_email_lead_template',
            __('Lead Notification Template', 'xtremecleans'),
            array($this, 'render_email_lead_template_field'),
            'xtremecleans-settings-email',
            'xtremecleans_email_section'
        );
        
        add_settings_field(
            'xtremecleans_email_user_subject',
            __('User Confirmation Subject', 'xtremecleans'),
            array($this, 'render_email_user_subject_field'),
            'xtremecleans-settings-email',
            'xtremecleans_email_section'
        );
        
        add_settings_field(
            'xtremecleans_email_user_template',
            __('User Confirmation Template', 'xtremecleans'),
            array($this, 'render_email_user_template_field'),
            'xtremecleans-settings-email',
            'xtremecleans_email_section'
        );
        
        add_settings_field(
            'xtremecleans_email_test',
            __('Test Email', 'xtremecleans'),
            array($this, 'render_email_test_field'),
            'xtremecleans-settings-email',
            'xtremecleans_email_section'
        );
    }
    
    /**
     * Enqueue admin scripts and styles
     *
     * @since 1.0.0
     * @param string $hook Current admin page hook
     */
    public function enqueue_admin_scripts($hook) {
        // Only load on our admin pages
        if (strpos($hook, 'xtremecleans') === false) {
            return;
        }
        
        wp_enqueue_style(
            'xtremecleans-admin-style',
            XTREMECLEANS_PLUGIN_URL . 'ui/assets/css/admin.css',
            array(),
            XTREMECLEANS_VERSION
        );
        
        wp_enqueue_script(
            'xtremecleans-admin-script',
            XTREMECLEANS_PLUGIN_URL . 'ui/assets/js/admin.js',
            array('jquery'),
            XTREMECLEANS_VERSION,
            true
        );
        
        // Localize admin script
        wp_localize_script(
            'xtremecleans-admin-script',
            'xtremecleansAdminData',
            array(
                'ajaxUrl' => admin_url('admin-ajax.php'),
                'adminEmail' => get_option('admin_email'),
                'emailTestNonce' => wp_create_nonce('xtremecleans_email_test'),
                'nonce' => wp_create_nonce('xtremecleans_test_jobber'),
            )
        );
        
        // Localize script for Orders page
        wp_localize_script(
            'xtremecleans-admin-script',
            'xtremecleansOrdersData',
            array(
                'ajaxUrl' => admin_url('admin-ajax.php'),
                'nonce' => wp_create_nonce('xtremecleans_orders'),
            )
        );
        
        // Load Chart.js for orders page
        if ($hook === 'xtremecleans_page_xtremecleans-orders') {
            wp_enqueue_script(
                'chart-js',
                'https://cdn.jsdelivr.net/npm/chart.js@4.4.0/dist/chart.umd.min.js',
                array(),
                '4.4.0',
            true
        );
        }
    }
    
    /**
     * Display admin notices
     *
     * @since 1.0.0
     */
    public function display_admin_notices() {
        if (isset($_GET['settings-updated']) && 
            isset($_GET['page']) && 
            $_GET['page'] === 'xtremecleans-settings') {
            echo '<div class="notice notice-success is-dismissible">';
            echo '<p>' . esc_html__('Settings saved successfully!', 'xtremecleans') . '</p>';
            echo '</div>';
        }
    }
    
    /**
     * Refresh API credentials
     *
     * @since 1.0.0
     */
    public function refresh_api_credentials() {
        $plugin = XtremeCleans::get_instance();
        if (isset($plugin->api)) {
            $plugin->api->refresh_credentials();
        }
    }
    
    /**
     * Render Dashboard Page
     *
     * @since 1.0.0
     */
    public function render_dashboard_page() {
        if (!current_user_can('manage_options')) {
            wp_die(__('You do not have sufficient permissions to access this page.', 'xtremecleans'));
        }
        
        try {
            $stats = $this->get_dashboard_stats();
            $template_path = xtremecleans_get_template_path('admin-dashboard', 'admin');
            
            if (file_exists($template_path)) {
                xtremecleans_load_template('admin-dashboard', array('stats' => $stats), 'admin');
            } else {
                // Fallback if template doesn't exist
                echo '<div class="wrap">';
                echo '<h1>XtremeCleans Dashboard</h1>';
                echo '<p>Dashboard template not found. Please check plugin installation.</p>';
                echo '</div>';
            }
        } catch (Exception $e) {
            echo '<div class="wrap">';
            echo '<h1>XtremeCleans</h1>';
            echo '<div class="notice notice-error"><p>Error loading dashboard: ' . esc_html($e->getMessage()) . '</p></div>';
            echo '</div>';
        }
    }
    
    /**
     * Render Settings Page
     *
     * @since 1.0.0
     */
    public function render_settings_page() {
        if (!current_user_can('manage_options')) {
            wp_die(__('You do not have sufficient permissions to access this page.', 'xtremecleans'));
        }
        
        $active_tab = isset($_GET['tab']) ? sanitize_text_field($_GET['tab']) : 'general';
        xtremecleans_load_template('admin-settings', array('active_tab' => $active_tab), 'admin');
    }
    
    /**
     * Render Shortcodes Page
     *
     * @since 1.0.0
     */
    public function render_shortcodes_page() {
        if (!current_user_can('manage_options')) {
            wp_die(__('You do not have sufficient permissions to access this page.', 'xtremecleans'));
        }
        
        $shortcodes = $this->get_shortcodes_list();
        xtremecleans_load_template('admin-shortcodes', array('shortcodes' => $shortcodes), 'admin');
    }
    
    /**
     * Render API Test Page
     *
     * @since 1.0.0
     */
    public function render_api_test_page() {
        if (!current_user_can('manage_options')) {
            wp_die(__('You do not have sufficient permissions to access this page.', 'xtremecleans'));
        }
        
        $test_result = $this->handle_api_test();
        $api_configured = xtremecleans_is_api_configured();
        
        xtremecleans_load_template(
            'admin-api-test',
            array(
                'test_result'     => $test_result,
                'api_configured'   => $api_configured,
            ),
            'admin'
        );
    }
    
    /**
     * Render Zone Names Page
     *
     * @since 1.0.0
     */
    public function render_zone_names_page() {
        if (!current_user_can('manage_options')) {
            wp_die(__('You do not have sufficient permissions to access this page.', 'xtremecleans'));
        }
        
        // Get zone names
        $zone_names = $this->get_zone_names();
        
        echo '<div class="wrap">';
        echo '<h1>' . esc_html__('Zone Names', 'xtremecleans') . '</h1>';
        echo '<div class="notice notice-info"><p>' . esc_html__('These are the predefined zone names available for selection when adding ZIP codes.', 'xtremecleans') . '</p></div>';
        echo '<table class="wp-list-table widefat fixed striped">';
        echo '<thead><tr><th>' . esc_html__('Zone Name', 'xtremecleans') . '</th></tr></thead>';
        echo '<tbody>';
        foreach ($zone_names as $zone_name) {
            echo '<tr><td><strong>' . esc_html($zone_name) . '</strong></td></tr>';
        }
        echo '</tbody></table>';
        echo '</div>';
    }
    
    /**
     * Get predefined zone names
     *
     * @since 1.0.0
     * @return array Predefined zone names
     */
    public function get_predefined_zone_names() {
        return array(
            'Anne Arundel',
            'Baltimore',
            'Cecil',
            'Harford',
            'Howard',
            'Montgomery',
            'Prince George\'s'
        );
    }
    
    /**
     * Get combined zone names (default + custom)
     *
     * @since 1.0.0
     * @return array
     */
    public function get_zone_names() {
        $defaults = $this->get_predefined_zone_names();
        $custom = get_option('xtremecleans_custom_zone_names', array());
        
        if (!is_array($custom)) {
            $custom = array();
        }
        
        $all = array_merge($defaults, $custom);
        $all = array_map('trim', $all);
        $all = array_filter($all);
        $all = array_values(array_unique($all));
        
        if (!empty($all)) {
            natcasesort($all);
            $all = array_values($all);
        }
        
        return $all;
    }
    
    /**
     * Render Zip Zone Page
     *
     * @since 1.0.0
     */
    public function render_zip_zone_page() {
        if (!current_user_can('manage_options')) {
            wp_die(__('You do not have sufficient permissions to access this page.', 'xtremecleans'));
        }
        
        // Get zip zone data
        $zip_zones = $this->get_zip_zones();
        
        // Get zone names (default + custom)
        $zone_names = $this->get_zone_names();
        
        xtremecleans_load_template(
            'admin-zip-zone',
            array(
                'zip_zones' => $zip_zones,
                'zone_names' => $zone_names,
                'service_names' => $this->get_unique_service_names(),
            ),
            'admin'
        );
    }
    
    /**
     * Render Orders Page
     *
     * @since 1.0.0
     */
    public function render_orders_page() {
        if (!current_user_can('manage_options')) {
            wp_die(__('You do not have sufficient permissions to access this page.', 'xtremecleans'));
        }
        
        // Ensure orders table exists
        $this->create_orders_table();
        
        // Get all orders
        $orders = $this->get_all_orders();
        
        // Get order statistics for charts
        $stats = $this->get_order_statistics($orders);
        
        // Create nonce for AJAX requests
        $orders_nonce = wp_create_nonce('xtremecleans_orders');
        
        xtremecleans_load_template(
            'admin-orders',
            array(
                'orders' => $orders,
                'stats' => $stats,
                'orders_nonce' => $orders_nonce,
            ),
            'admin'
        );
    }
    
    /**
     * Render Service Items Page
     *
     * @since 1.0.0
     */
    public function render_service_items_page() {
        if (!current_user_can('manage_options')) {
            wp_die(__('You do not have sufficient permissions to access this page.', 'xtremecleans'));
        }
        
        // Ensure service items table exists
        $this->create_service_items_table();
        
        // Get all service items
        $service_items = $this->get_all_service_items();
        
        // Get unique service names
        $service_names = $this->get_unique_service_names();
        
        xtremecleans_load_template(
            'admin-service-items',
            array(
                'service_items' => $service_items,
                'service_names' => $service_names,
            ),
            'admin'
        );
    }
    
    /**
     * Get zip zones from database
     *
     * @since 1.0.0
     * @return array Zip zones data
     */
    public function get_zip_zones() {
        global $wpdb;
        
        $table_name = $wpdb->prefix . 'xtremecleans_zip_reference';
        
        // Check if table exists
        $table_exists = $wpdb->get_var($wpdb->prepare("SHOW TABLES LIKE %s", $table_name));
        
        if (!$table_exists) {
            return array();
        }
        
        // Escape table name for safe SQL query
        $table_name_escaped = esc_sql($table_name);
        
        // Get all zip zones
        $results = $wpdb->get_results(
            "SELECT * FROM `{$table_name_escaped}` ORDER BY id ASC",
            ARRAY_A
        );
        
        return $results ? $results : array();
    }
    
    /**
     * Get unique zone names for dropdown
     *
     * @since 1.0.0
     * @return array Unique zone names
     */
    private function get_unique_zone_names() {
        global $wpdb;
        
        $table_name = $wpdb->prefix . 'xtremecleans_zip_reference';
        
        // Check if table exists
        $table_exists = $wpdb->get_var($wpdb->prepare("SHOW TABLES LIKE %s", $table_name));
        
        if (!$table_exists) {
            return array();
        }
        
        // Escape table name for safe SQL query
        $table_name_escaped = esc_sql($table_name);
        
        // Get unique zone names
        $results = $wpdb->get_results(
            "SELECT DISTINCT zone_name FROM `{$table_name_escaped}` WHERE zone_name IS NOT NULL AND zone_name != '' ORDER BY zone_name ASC",
            ARRAY_A
        );
        
        $zone_names = array();
        if ($results) {
            foreach ($results as $row) {
                $zone_names[] = $row['zone_name'];
            }
        }
        
        return $zone_names;
    }
    
    /**
     * Create database table for zip zones
     *
     * @since 1.0.0
     */
    private function create_database_table() {
        global $wpdb;
        
        $charset_collate = $wpdb->get_charset_collate();
        
        // Table for zip zone reference
        $table_name = $wpdb->prefix . 'xtremecleans_zip_reference';
        
        $sql = "CREATE TABLE IF NOT EXISTS `{$table_name}` (
            `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT,
            `zip_code` varchar(10) NOT NULL,
            `service_name` varchar(100) DEFAULT NULL,
            `zone_name` varchar(100) NOT NULL,
            `zone_area` varchar(100) DEFAULT NULL,
            `county` varchar(100) DEFAULT NULL,
            `service_fee` decimal(10,2) DEFAULT '0.00',
            `city` varchar(100) DEFAULT NULL,
            `state` varchar(50) DEFAULT NULL,
            `suggested_zone` varchar(100) DEFAULT NULL,
            `created_at` datetime DEFAULT NULL,
            `updated_at` datetime DEFAULT NULL,
            PRIMARY KEY (`id`),
            KEY `zip_code` (`zip_code`),
            KEY `zone_name` (`zone_name`),
            KEY `service_name` (`service_name`)
        ) {$charset_collate};";
        
        require_once(ABSPATH . 'wp-admin/includes/upgrade.php');
        dbDelta($sql);
        
        // Add service_name column if it doesn't exist (for existing tables)
        $column_exists = $wpdb->get_results("SHOW COLUMNS FROM `{$table_name}` LIKE 'service_name'");
        if (empty($column_exists)) {
            $wpdb->query("ALTER TABLE `{$table_name}` ADD COLUMN `service_name` varchar(100) DEFAULT NULL AFTER `zip_code`");
            $wpdb->query("ALTER TABLE `{$table_name}` ADD KEY `service_name` (`service_name`)");
        }

        // Ensure county column exists for legacy tables
        $county_column = $wpdb->get_results("SHOW COLUMNS FROM `{$table_name}` LIKE 'county'");
        if (empty($county_column)) {
            $wpdb->query("ALTER TABLE `{$table_name}` ADD COLUMN `county` varchar(100) DEFAULT NULL AFTER `zone_area`");
        }
        
        require_once(ABSPATH . 'wp-admin/includes/upgrade.php');
        dbDelta($sql);
    }
<<<<<<< HEAD
=======

    /**
     * Sanitize service name values from request payload.
     *
     * @since 1.0.0
     * @param mixed $raw_service_names Raw service name payload.
     * @return array
     */
    private function sanitize_service_names_from_request($raw_service_names) {
        $service_names = array();
        $seen = array();
        $values = is_array($raw_service_names) ? $raw_service_names : array($raw_service_names);

        foreach ($values as $value) {
            if (is_array($value) || is_object($value)) {
                continue;
            }

            $service_name = sanitize_text_field($value);
            $service_name = trim(preg_replace('/\s+/', ' ', $service_name));

            if ($service_name === '') {
                continue;
            }

            $key = strtolower($service_name);
            if (isset($seen[$key])) {
                continue;
            }

            $seen[$key] = true;
            $service_names[] = $service_name;
        }

        return $service_names;
    }

    /**
     * Sanitize ZIP code values from request payload.
     *
     * @since 1.0.0
     * @param mixed $raw_zip_codes Raw ZIP payload (string or array).
     * @return array
     */
    private function sanitize_zip_codes_from_request($raw_zip_codes) {
        $valid = array();
        $invalid = array();
        $valid_seen = array();
        $invalid_seen = array();
        $tokens = array();
        $values = is_array($raw_zip_codes) ? $raw_zip_codes : array($raw_zip_codes);

        foreach ($values as $value) {
            if (is_array($value) || is_object($value)) {
                continue;
            }

            $parts = preg_split('/[\s,;]+/', (string) $value, -1, PREG_SPLIT_NO_EMPTY);
            if (!empty($parts)) {
                $tokens = array_merge($tokens, $parts);
            }
        }

        foreach ($tokens as $token) {
            $zip_code = sanitize_text_field($token);
            $zip_code = trim($zip_code);

            if ($zip_code === '') {
                continue;
            }

            if (!preg_match('/^[0-9]{5}$/', $zip_code)) {
                if (!isset($invalid_seen[$zip_code])) {
                    $invalid_seen[$zip_code] = true;
                    $invalid[] = $zip_code;
                }
                continue;
            }

            if (!isset($valid_seen[$zip_code])) {
                $valid_seen[$zip_code] = true;
                $valid[] = $zip_code;
            }
        }

        return array(
            'valid' => $valid,
            'invalid' => $invalid,
        );
    }

    /**
     * Get all mapped service names for a ZIP code from ZIP reference table.
     *
     * @since 1.0.0
     * @param string $zip_code ZIP code.
     * @return array
     */
    private function get_service_names_by_zip_code($zip_code) {
        global $wpdb;

        $zip_code = sanitize_text_field($zip_code);
        if (empty($zip_code) || !preg_match('/^[0-9]{5}$/', $zip_code)) {
            return array();
        }

        $zip_table = $wpdb->prefix . 'xtremecleans_zip_reference';
        $zip_exists = $wpdb->get_var($wpdb->prepare("SHOW TABLES LIKE %s", $zip_table));

        if (!$zip_exists) {
            return array();
        }

        $zip_table = esc_sql($zip_table);
        $service_names = $wpdb->get_col($wpdb->prepare(
            "SELECT DISTINCT service_name
            FROM `{$zip_table}`
            WHERE zip_code = %s
            AND service_name IS NOT NULL
            AND service_name != ''
            ORDER BY service_name ASC",
            $zip_code
        ));

        return $this->sanitize_service_names_from_request($service_names);
    }
>>>>>>> 3378c4f (plugin last vertation updated in admin dashboard)
    
    /**
     * AJAX handler: Add new zip zone
     *
     * @since 1.0.0
     */
    public function ajax_add_zip_zone() {
        check_ajax_referer('xtremecleans_add_zip', 'nonce');
        
        if (!current_user_can('manage_options')) {
            wp_send_json_error(array('message' => __('You do not have permission to perform this action.', 'xtremecleans')));
        }
        
        global $wpdb;
        
        $table_name = $wpdb->prefix . 'xtremecleans_zip_reference';

        // Ensure table schema is up to date
        $this->create_database_table();
        
        // Check if table exists, create if not
        $table_exists = $wpdb->get_var($wpdb->prepare("SHOW TABLES LIKE %s", $table_name));
        if (!$table_exists) {
            $this->create_database_table();
        }
        
        // Get and sanitize form data
<<<<<<< HEAD
        $service_name = isset($_POST['service_name']) ? sanitize_text_field($_POST['service_name']) : '';
        $zone_name = isset($_POST['zone_name']) ? sanitize_text_field($_POST['zone_name']) : '';
        $zip_code = isset($_POST['zip_code']) ? sanitize_text_field($_POST['zip_code']) : '';
=======
        $service_names = $this->sanitize_service_names_from_request(isset($_POST['service_name']) ? $_POST['service_name'] : array());
        $zone_name = isset($_POST['zone_name']) ? sanitize_text_field($_POST['zone_name']) : '';
        $zip_codes_result = $this->sanitize_zip_codes_from_request(isset($_POST['zip_code']) ? $_POST['zip_code'] : '');
        $zip_codes = isset($zip_codes_result['valid']) ? $zip_codes_result['valid'] : array();
        $invalid_zip_codes = isset($zip_codes_result['invalid']) ? $zip_codes_result['invalid'] : array();
>>>>>>> 3378c4f (plugin last vertation updated in admin dashboard)
        $zone_area = isset($_POST['zone_area']) ? sanitize_text_field($_POST['zone_area']) : '';
        $county = isset($_POST['county']) ? sanitize_text_field($_POST['county']) : '';
        $state = isset($_POST['state']) ? sanitize_text_field($_POST['state']) : '';
        $service_fee = isset($_POST['service_fee']) ? floatval($_POST['service_fee']) : 0.00;
        
        // Validate required fields
<<<<<<< HEAD
        if (empty($zone_name) || empty($zip_code)) {
            wp_send_json_error(array('message' => __('Zone Name and ZIP Code are required.', 'xtremecleans')));
        }
        
        // Validate ZIP code format
        if (!preg_match('/^[0-9]{5}$/', $zip_code)) {
            wp_send_json_error(array('message' => __('ZIP Code must be 5 digits.', 'xtremecleans')));
        }
        
        $table_name_escaped = esc_sql($table_name);
        
        // Insert new zone
        $result = $wpdb->insert(
            $table_name,
            array(
                'service_name' => $service_name,
                'zone_name' => $zone_name,
                'zip_code' => $zip_code,
                'zone_area' => $zone_area,
                'county' => $county,
                'state' => $state,
                'service_fee' => $service_fee,
                'suggested_zone' => $zone_name,
                'created_at' => current_time('mysql'),
                'updated_at' => current_time('mysql'),
            ),
            array('%s', '%s', '%s', '%s', '%s', '%s', '%f', '%s', '%s', '%s')
        );
        
        if ($result === false) {
            wp_send_json_error(array('message' => __('Failed to add zip zone.', 'xtremecleans')));
        }
        
        // Get the newly inserted zone
        $new_zone = $wpdb->get_row($wpdb->prepare(
            "SELECT * FROM `{$table_name_escaped}` WHERE id = %d",
            $wpdb->insert_id
        ), ARRAY_A);
        
        wp_send_json_success(array(
            'message' => __('Zip zone added successfully.', 'xtremecleans'),
            'zone' => $new_zone,
=======
        if (empty($zone_name) || empty($zip_codes)) {
            wp_send_json_error(array('message' => __('Zone Name and ZIP Code are required.', 'xtremecleans')));
        }
        
        if (!empty($invalid_zip_codes)) {
            wp_send_json_error(array(
                'message' => sprintf(
                    __('ZIP Code must be 5 digits. Invalid value(s): %s', 'xtremecleans'),
                    implode(', ', $invalid_zip_codes)
                )
            ));
        }
        
        $table_name_escaped = esc_sql($table_name);

        // If no service is selected, allow saving ZIP/zone rows with empty service name.
        $service_names_for_insert = !empty($service_names) ? $service_names : array('');
        $inserted_zones = array();
        $failed_count = 0;

        foreach ($zip_codes as $zip_code) {
            foreach ($service_names_for_insert as $service_name) {
                $result = $wpdb->insert(
                    $table_name,
                    array(
                        'service_name' => $service_name,
                        'zone_name' => $zone_name,
                        'zip_code' => $zip_code,
                        'zone_area' => $zone_area,
                        'county' => $county,
                        'state' => $state,
                        'service_fee' => $service_fee,
                        'suggested_zone' => $zone_name,
                        'created_at' => current_time('mysql'),
                        'updated_at' => current_time('mysql'),
                    ),
                    array('%s', '%s', '%s', '%s', '%s', '%s', '%f', '%s', '%s', '%s')
                );

                if ($result === false) {
                    $failed_count++;
                    continue;
                }

                $new_zone = $wpdb->get_row($wpdb->prepare(
                    "SELECT * FROM `{$table_name_escaped}` WHERE id = %d",
                    $wpdb->insert_id
                ), ARRAY_A);

                if ($new_zone) {
                    $inserted_zones[] = $new_zone;
                }
            }
        }

        if (empty($inserted_zones)) {
            wp_send_json_error(array('message' => __('Failed to add zip zone.', 'xtremecleans')));
        }

        $inserted_count = count($inserted_zones);
        $message = sprintf(
            _n(
                '%d ZIP zone added successfully.',
                '%d ZIP zones added successfully.',
                $inserted_count,
                'xtremecleans'
            ),
            $inserted_count
        );

        if ($failed_count > 0) {
            $message .= ' ' . sprintf(
                _n(
                    '%d row could not be saved.',
                    '%d rows could not be saved.',
                    $failed_count,
                    'xtremecleans'
                ),
                $failed_count
            );
        }

        wp_send_json_success(array(
            'message' => $message,
            'zone' => $inserted_zones[0],
            'zones' => $inserted_zones,
            'inserted_count' => $inserted_count,
            'failed_count' => $failed_count,
>>>>>>> 3378c4f (plugin last vertation updated in admin dashboard)
        ));
    }
    
    /**
     * AJAX handler: Update zip zone
     *
     * @since 1.0.0
     */
    public function ajax_update_zip_zone() {
        check_ajax_referer('xtremecleans_add_zip', 'nonce');
        
        if (!current_user_can('manage_options')) {
            wp_send_json_error(array('message' => __('You do not have permission to perform this action.', 'xtremecleans')));
        }
        
        global $wpdb;
        
        $table_name = $wpdb->prefix . 'xtremecleans_zip_reference';

        // Ensure schema updated
        $this->create_database_table();
        
        // Check if table exists, create if not
        $table_exists = $wpdb->get_var($wpdb->prepare("SHOW TABLES LIKE %s", $table_name));
        if (!$table_exists) {
            $this->create_database_table();
        }
        
        // Ensure service_name column exists
        $column_exists = $wpdb->get_results("SHOW COLUMNS FROM `{$table_name}` LIKE 'service_name'");
        if (empty($column_exists)) {
            $wpdb->query("ALTER TABLE `{$table_name}` ADD COLUMN `service_name` varchar(100) DEFAULT NULL AFTER `zip_code`");
            $wpdb->query("ALTER TABLE `{$table_name}` ADD KEY `service_name` (`service_name`)");
        }
        
        // Get and sanitize form data
        $zone_id = isset($_POST['zone_id']) ? intval($_POST['zone_id']) : 0;
<<<<<<< HEAD
        $service_name = isset($_POST['service_name']) ? sanitize_text_field($_POST['service_name']) : '';
        $zone_name = isset($_POST['zone_name']) ? sanitize_text_field($_POST['zone_name']) : '';
        $zip_code = isset($_POST['zip_code']) ? sanitize_text_field($_POST['zip_code']) : '';
=======
        $service_names = $this->sanitize_service_names_from_request(isset($_POST['service_name']) ? $_POST['service_name'] : '');
        $service_name = !empty($service_names) ? $service_names[0] : '';
        $zone_name = isset($_POST['zone_name']) ? sanitize_text_field($_POST['zone_name']) : '';
        $zip_codes_result = $this->sanitize_zip_codes_from_request(isset($_POST['zip_code']) ? $_POST['zip_code'] : '');
        $zip_code = !empty($zip_codes_result['valid']) ? $zip_codes_result['valid'][0] : '';
>>>>>>> 3378c4f (plugin last vertation updated in admin dashboard)
        $zone_area = isset($_POST['zone_area']) ? sanitize_text_field($_POST['zone_area']) : '';
        $county = isset($_POST['county']) ? sanitize_text_field($_POST['county']) : '';
        $state = isset($_POST['state']) ? sanitize_text_field($_POST['state']) : '';
        $service_fee = isset($_POST['service_fee']) ? floatval($_POST['service_fee']) : 0.00;
<<<<<<< HEAD
=======

        if (!empty($zip_codes_result['invalid'])) {
            wp_send_json_error(array(
                'message' => sprintf(
                    __('ZIP Code must be 5 digits. Invalid value(s): %s', 'xtremecleans'),
                    implode(', ', $zip_codes_result['invalid'])
                )
            ));
        }
>>>>>>> 3378c4f (plugin last vertation updated in admin dashboard)
        
        // Validate required fields
        if (empty($zone_id) || empty($zone_name) || empty($zip_code)) {
            wp_send_json_error(array('message' => __('Zone ID, Zone Name and ZIP Code are required.', 'xtremecleans')));
        }
        
<<<<<<< HEAD
        // Validate ZIP code format
        if (!preg_match('/^[0-9]{5}$/', $zip_code)) {
            wp_send_json_error(array('message' => __('ZIP Code must be 5 digits.', 'xtremecleans')));
        }
        
=======
>>>>>>> 3378c4f (plugin last vertation updated in admin dashboard)
        // Update zone - build data and format arrays
        $update_data = array(
            'service_name' => $service_name,
            'zone_name' => $zone_name,
            'zip_code' => $zip_code,
            'zone_area' => $zone_area,
            'county' => $county,
            'state' => $state,
            'service_fee' => $service_fee,
            'suggested_zone' => $zone_name,
            'updated_at' => current_time('mysql'),
        );
        
        $format_array = array('%s', '%s', '%s', '%s', '%s', '%s', '%f', '%s', '%s');
        
        // Escape table name for safe SQL
        $table_name_escaped = esc_sql($table_name);
        
        $result = $wpdb->update(
            $table_name,
            $update_data,
            array('id' => $zone_id),
            $format_array,
            array('%d')
        );
        
        // Check for errors (false means error, 0 means no rows changed but not an error)
        if ($result === false) {
            $error_message = $wpdb->last_error ? $wpdb->last_error : __('Failed to update zip zone. Database error occurred.', 'xtremecleans');
            wp_send_json_error(array('message' => $error_message));
        }
        
        // Get the updated zone
        $updated_zone = $wpdb->get_row($wpdb->prepare(
            "SELECT * FROM `{$table_name_escaped}` WHERE id = %d",
            $zone_id
        ), ARRAY_A);
        
        if (!$updated_zone) {
            wp_send_json_error(array('message' => __('Zone not found after update.', 'xtremecleans')));
        }
        
        wp_send_json_success(array(
            'message' => __('Zip zone updated successfully.', 'xtremecleans'),
            'zone' => $updated_zone,
        ));
    }
    
    /**
     * AJAX handler: Delete zip zone
     *
     * @since 1.0.0
     */
    public function ajax_delete_zip_zone() {
        check_ajax_referer('xtremecleans_add_zip', 'nonce');
        
        if (!current_user_can('manage_options')) {
            wp_send_json_error(array('message' => __('You do not have permission to perform this action.', 'xtremecleans')));
        }
        
        global $wpdb;
        
        $table_name = $wpdb->prefix . 'xtremecleans_zip_reference';
        $zone_id = isset($_POST['zone_id']) ? intval($_POST['zone_id']) : 0;
        
        if (empty($zone_id)) {
            wp_send_json_error(array('message' => __('Zone ID is required.', 'xtremecleans')));
        }
        
        // Check if table exists
        $table_exists = $wpdb->get_var($wpdb->prepare("SHOW TABLES LIKE %s", $table_name));
        if (!$table_exists) {
            wp_send_json_error(array('message' => __('Database table does not exist.', 'xtremecleans')));
        }
        
        // Delete zone
        $table_name_escaped = esc_sql($table_name);
        $result = $wpdb->delete(
            $table_name,
            array('id' => $zone_id),
            array('%d')
        );
        
        if ($result === false) {
            wp_send_json_error(array('message' => __('Failed to delete zip zone.', 'xtremecleans')));
        }
        
        wp_send_json_success(array(
            'message' => __('Zip zone deleted successfully.', 'xtremecleans'),
        ));
    }
    
    /**
     * AJAX handler: Clear all zip zones
     *
     * @since 1.0.0
     */
    public function ajax_clear_all_zones() {
        check_ajax_referer('xtremecleans_add_zip', 'nonce');
        
        if (!current_user_can('manage_options')) {
            wp_send_json_error(array('message' => __('You do not have permission to perform this action.', 'xtremecleans')));
        }
        
        global $wpdb;
        
        $table_name = $wpdb->prefix . 'xtremecleans_zip_reference';
        
        // Check if table exists
        $table_exists = $wpdb->get_var($wpdb->prepare("SHOW TABLES LIKE %s", $table_name));
        if (!$table_exists) {
            wp_send_json_error(array('message' => __('Database table does not exist.', 'xtremecleans')));
        }
        
        // Delete all zones
        $table_name_escaped = esc_sql($table_name);
        $result = $wpdb->query("TRUNCATE TABLE `{$table_name_escaped}`");
        
        if ($result === false) {
            wp_send_json_error(array('message' => __('Failed to clear all zip zones.', 'xtremecleans')));
        }
        
        wp_send_json_success(array(
            'message' => __('All zip zones cleared successfully.', 'xtremecleans'),
        ));
    }
    
    /**
     * Get dashboard statistics
     *
     * @since 1.0.0
     * @return array Dashboard statistics
     */
    private function get_dashboard_stats() {
        return array(
            'shortcodes_count' => 4,
            'api_configured'   => xtremecleans_is_api_configured(),
            'forms_submitted'  => xtremecleans_get_form_count(),
            'plugin_version'   => xtremecleans_get_version(),
        );
    }
    
    /**
     * Get shortcodes list
     *
     * @since 1.0.0
     * @return array Shortcodes list
     */
    private function get_shortcodes_list() {
        return array(
            array(
                'name'        => 'xtremecleans_info',
                'description' => __('Display information boxes with title and content', 'xtremecleans'),
                'example'     => '[xtremecleans_info title="My Title" content="My content here"]',
                'attributes'  => array(
                    'title'   => __('Title of the info box', 'xtremecleans'),
                    'content' => __('Content to display', 'xtremecleans'),
                    'class'   => __('Additional CSS class', 'xtremecleans'),
                ),
            ),
            array(
                'name'        => 'xtremecleans_button',
                'description' => __('Create styled buttons with multiple style options', 'xtremecleans'),
                'example'     => '[xtremecleans_button text="Click Me" url="https://example.com" style="primary"]',
                'attributes'  => array(
                    'text'   => __('Button text', 'xtremecleans'),
                    'url'    => __('Button URL', 'xtremecleans'),
                    'style'  => __('Button style: primary, secondary, success', 'xtremecleans'),
                    'target' => __('Link target: _self, _blank', 'xtremecleans'),
                    'class'  => __('Additional CSS class', 'xtremecleans'),
                ),
            ),
            array(
                'name'        => 'xtremecleans_api_data',
                'description' => __('Display data from API endpoints with caching', 'xtremecleans'),
                'example'     => '[xtremecleans_api_data endpoint="/data" template="table" cache="3600"]',
                'attributes'  => array(
                    'endpoint' => __('API endpoint path', 'xtremecleans'),
                    'template' => __('Template: default, list, table', 'xtremecleans'),
                    'cache'    => __('Cache duration in seconds', 'xtremecleans'),
                ),
            ),
            array(
                'name'        => 'xtremecleans_form',
                'description' => __('Contact form with validation', 'xtremecleans'),
                'example'     => '[xtremecleans_form title="Contact Us" submit_text="Send Message"]',
                'attributes'  => array(
                    'title'       => __('Form title', 'xtremecleans'),
                    'submit_text' => __('Submit button text', 'xtremecleans'),
                    'class'       => __('Additional CSS class', 'xtremecleans'),
                ),
            ),
        );
    }
    
    /**
     * AJAX handler: Test Jobber Connection
     *
     * @since 1.0.0
     */
    public function ajax_test_jobber_connection() {
        check_ajax_referer('xtremecleans_test_jobber', 'nonce');
        
        if (!current_user_can('manage_options')) {
            wp_send_json_error(array('message' => __('Permission denied.', 'xtremecleans')));
        }
        
        $results = array(
            'connection' => array('success' => false, 'message' => ''),
            'client_test' => array('success' => false, 'message' => ''),
            'summary' => '',
        );
        
        // Step 1: Check if API is configured
        if (!xtremecleans_is_api_configured()) {
            $client_id = xtremecleans_get_option('jobber_client_id', '');
            $client_secret = xtremecleans_get_option('jobber_client_secret', '');
            $access_token = get_option('xtremecleans_jobber_access_token', '');
            
            $missing = array();
            if (empty($client_id)) $missing[] = 'Client ID';
            if (empty($client_secret)) $missing[] = 'Client Secret';
            if (empty($access_token)) $missing[] = 'Access Token';
            
            wp_send_json_error(array(
                'message' => __('Jobber is not configured.', 'xtremecleans'),
                'missing' => $missing,
                'results' => $results,
            ));
        }
        
        $results['connection']['success'] = true;
        $results['connection']['message'] = __('✓ Jobber API is configured', 'xtremecleans');
        
        // Step 2: Test API connection by creating a test client
        if (!class_exists('XtremeCleans_API')) {
            $api_file = XTREMECLEANS_PLUGIN_DIR . 'core/api/class-xtremecleans-api.php';
            if (file_exists($api_file)) {
                require_once $api_file;
            }
        }
        
        if (!class_exists('XtremeCleans_API')) {
            wp_send_json_error(array(
                'message' => __('API class not found.', 'xtremecleans'),
                'results' => $results,
            ));
        }
        
        $api = new XtremeCleans_API();
        
        // Log current token info for debugging
        $access_token = get_option('xtremecleans_jobber_access_token', '');
        $token_expires = get_option('xtremecleans_jobber_token_expires', 0);
        $token_scopes = get_option('xtremecleans_jobber_token_scopes', '');
        $client_id = xtremecleans_get_option('jobber_client_id', '');
        
        // Diagnostic information
        $diagnostics = array(
            'has_access_token' => !empty($access_token),
            'token_preview' => !empty($access_token) ? substr($access_token, 0, 20) . '...' : 'NONE',
            'token_expires' => $token_expires > 0 ? date('Y-m-d H:i:s', $token_expires) : 'Never/Unknown',
            'token_scopes' => $token_scopes ? $token_scopes : 'NOT RECORDED',
            'client_id' => $client_id ? substr($client_id, 0, 20) . '...' : 'NOT SET',
        );
        
        if (!empty($access_token)) {
            xtremecleans_log('Testing Jobber connection with token: ' . substr($access_token, 0, 20) . '...', 'info');
            xtremecleans_log('Token scopes recorded: ' . ($token_scopes ? $token_scopes : 'NOT RECORDED'), 'info');
            if ($token_expires > 0) {
                $expires_in = $token_expires - time();
                xtremecleans_log('Token expires in: ' . ($expires_in > 0 ? $expires_in . ' seconds' : 'EXPIRED'), 'info');
            }
        } else {
            xtremecleans_log('ERROR: No access token found!', 'error');
        }
        
        // Check if token needs refresh
        if ($token_expires > 0 && $token_expires < time() + 300) { // Refresh if expires in less than 5 minutes
            xtremecleans_log('Access token expires soon, attempting refresh...', 'info');
            $frontend = XtremeCleans::get_instance()->frontend;
            if ($frontend && method_exists($frontend, 'refresh_access_token')) {
                $refresh_result = $frontend->refresh_access_token();
                if (is_wp_error($refresh_result)) {
                    xtremecleans_log('Token refresh failed: ' . $refresh_result->get_error_message(), 'error');
                } else {
                    xtremecleans_log('Token refreshed successfully', 'info');
                    // Refresh API credentials
                    $api->refresh_credentials();
                }
            }
        }
        
        // Test: Use GraphQL API instead of REST API for connection test
        // GraphQL is the primary API and works with the same token
        xtremecleans_log('Testing Jobber API connection via GraphQL...', 'info');
        
        // Test GraphQL API connection (same API used for services sync)
        $graphql_endpoint = 'https://api.getjobber.com/api/graphql';
        $test_query = '{ __schema { queryType { name } } }'; // Simple introspection query
        
        $api_version = apply_filters('xtremecleans_jobber_api_version', '2025-04-16');
        
        $graphql_response = wp_remote_post($graphql_endpoint, array(
            'timeout' => 15,
            'headers' => array(
                'Authorization' => 'Bearer ' . $access_token,
                'Content-Type' => 'application/json',
                'Accept' => 'application/json',
                'X-JOBBER-GRAPHQL-VERSION' => $api_version,
            ),
            'body' => wp_json_encode(array('query' => $test_query)),
        ));
        
        $test_response = null;
        if (is_wp_error($graphql_response)) {
            // GraphQL request itself failed (network error, etc.)
            // Don't fall back to REST API - GraphQL is primary
            xtremecleans_log('GraphQL request failed: ' . $graphql_response->get_error_message(), 'error');
            $test_response = $graphql_response;
        } else {
            $status = wp_remote_retrieve_response_code($graphql_response);
            $body = wp_remote_retrieve_body($graphql_response);
            $data = json_decode($body, true);
            
            if ($status >= 200 && $status < 300) {
                // GraphQL connection successful - check if response is valid
                if (isset($data['data']) || isset($data['errors'])) {
                    // Valid GraphQL response (even with errors means API is accessible)
                    $test_response = array('success' => true, 'method' => 'graphql');
                    xtremecleans_log('GraphQL API connection test successful (Status: ' . $status . ')', 'info');
                } else {
                    // Unexpected response format, but status is OK
                    $test_response = array('success' => true, 'method' => 'graphql');
                    xtremecleans_log('GraphQL API connection test successful (unexpected response format)', 'info');
                }
            } else {
                // GraphQL returned error status
                xtremecleans_log('GraphQL returned error status ' . $status . ', Body: ' . substr($body, 0, 200), 'warning');
                // Don't fall back to REST API - GraphQL is primary
                // If GraphQL fails, it's a real error
                $test_response = new WP_Error('graphql_error', __('GraphQL API returned error status: ', 'xtremecleans') . $status, array('status' => $status, 'body' => $body));
            }
        }
        
        // Check if GraphQL test was successful
        if (is_array($test_response) && isset($test_response['success']) && $test_response['success'] === true) {
            // GraphQL connection test passed
            $results['connection']['success'] = true;
            $results['connection']['message'] = __('✓ Successfully connected to Jobber API (GraphQL)', 'xtremecleans');
        } elseif (is_wp_error($test_response)) {
            $error_data = $test_response->get_error_data();
            $error_msg = $test_response->get_error_message();
            
            if (isset($error_data['status'])) {
                if ($error_data['status'] == 401) {
                    $error_msg = __('Access Token is invalid or expired. Please re-authorize Jobber connection.', 'xtremecleans');
                    // Try to refresh token
                    $frontend = XtremeCleans::get_instance()->frontend;
                    if ($frontend && method_exists($frontend, 'refresh_access_token')) {
                        $refresh_result = $frontend->refresh_access_token();
                        if (!is_wp_error($refresh_result)) {
                            $api->refresh_credentials();
                            // Retry the request
                            $test_response = $api->get('v1/clients', array('limit' => 1));
                            if (!is_wp_error($test_response)) {
                                // Success after refresh
                                $results['connection']['success'] = true;
                                $results['connection']['message'] = __('✓ Successfully connected to Jobber API (token refreshed)', 'xtremecleans');
                            }
                        }
                    }
                } elseif ($error_data['status'] == 403) {
                    // Get detailed error from response body
                    $detailed_error = '';
                    $error_body_text = '';
                    if (isset($error_data['body'])) {
                        $error_body_text = $error_data['body'];
                        $error_body = json_decode($error_data['body'], true);
                        if (isset($error_body['error'])) {
                            $detailed_error = is_string($error_body['error']) ? $error_body['error'] : (isset($error_body['error']['message']) ? $error_body['error']['message'] : '');
                        } elseif (isset($error_body['message'])) {
                            $detailed_error = $error_body['message'];
                        }
                    }
                    
                    // Check what scopes the current token has
                    $current_token_scopes = get_option('xtremecleans_jobber_token_scopes', '');
                    $required_scopes = 'jobs contacts';
                    
                    // Log full error for debugging
                    xtremecleans_log('Jobber API 403 Error - Full response: ' . $error_body_text, 'error');
                    xtremecleans_log('Required scopes: ' . $required_scopes, 'info');
                    xtremecleans_log('Current token scopes: ' . ($current_token_scopes ? $current_token_scopes : 'NOT RECORDED'), 'warning');
                    
                    // Try to get more details from Jobber API about what's missing
                    // Check if we can introspect the token or get user info
                    if (!empty($access_token)) {
                        xtremecleans_log('Attempting to get user info to verify token permissions...', 'info');
                        $user_info_response = $api->get('v1/users/me');
                        if (is_wp_error($user_info_response)) {
                            $user_error = $user_info_response->get_error_data();
                            if (isset($user_error['body'])) {
                                xtremecleans_log('User info endpoint error: ' . $user_error['body'], 'error');
                            }
                        } else {
                            xtremecleans_log('User info retrieved successfully - token is valid but may lack specific permissions', 'info');
                        }
                    }
                    
                    // Get authorization URL for re-authorization
                    $frontend = XtremeCleans::get_instance()->frontend;
                    $auth_url = '';
                    $auth_url_scopes = '';
                    if ($frontend && method_exists($frontend, 'get_jobber_authorize_url_public')) {
                        $auth_url = $frontend->get_jobber_authorize_url_public();
                        xtremecleans_log('Authorization URL generated: ' . $auth_url, 'info');
                        
                        // Verify scope in URL
                        if (strpos($auth_url, 'scope=') !== false) {
                            $url_parts = parse_url($auth_url);
                            if (isset($url_parts['query'])) {
                                parse_str($url_parts['query'], $url_params);
                                if (isset($url_params['scope'])) {
                                    $auth_url_scopes = $url_params['scope'];
                                    xtremecleans_log('Authorization URL scopes: ' . $auth_url_scopes, 'info');
                                }
                            }
                        } else {
                            xtremecleans_log('ERROR: Authorization URL does not contain scope parameter!', 'error');
                        }
                    }
                    
                    // Build detailed error message with diagnostics
                    $error_msg = __('Access denied (403). Your OAuth token does not have the required permissions.', 'xtremecleans');
                    $error_msg .= "\n\n" . __('DIAGNOSTIC:', 'xtremecleans');
                    $error_msg .= "\n" . __('Required scopes: jobs and contacts', 'xtremecleans');
                    if (!empty($auth_url_scopes)) {
                        $error_msg .= "\n" . __('Authorization URL scopes: ' . $auth_url_scopes, 'xtremecleans');
                    }
                    if (!empty($current_token_scopes)) {
                        $error_msg .= "\n" . __('Current token scopes: ' . $current_token_scopes, 'xtremecleans');
                        if ($current_token_scopes !== $required_scopes) {
                            $error_msg .= "\n" . __('⚠️ SCOPES DO NOT MATCH! Token was authorized with different scopes.', 'xtremecleans');
                        }
                    } else {
                        $error_msg .= "\n" . __('⚠️ Token scopes: NOT RECORDED', 'xtremecleans');
                        $error_msg .= "\n" . __('This means authorization screen-এ সব permissions approve করা হয়নি বা Jobber API scope return করেনি।', 'xtremecleans');
                    }
                    
                    $error_msg .= "\n\n" . __('CRITICAL SOLUTION - The token does NOT have required permissions:', 'xtremecleans');
                    $error_msg .= "\n" . __('1. Go to Jobber Developer Portal: https://developer.getjobber.com/apps', 'xtremecleans');
                    $error_msg .= "\n" . __('2. Select your app and check "Scopes" or "Permissions" section', 'xtremecleans');
                    $error_msg .= "\n" . __('3. Make sure "jobs" and "contacts" scopes are enabled for your app', 'xtremecleans');
                    $error_msg .= "\n" . __('4. Click "Clear Token & Re-authorize" button below', 'xtremecleans');
                    $error_msg .= "\n" . __('5. In authorization screen, EXPAND all sections and CHECK ALL boxes', 'xtremecleans');
                    $error_msg .= "\n" . __('6. Click "Allow Access" and test again', 'xtremecleans');
                    $error_msg .= "\n\n" . __('NOTE: If your Jobber app does not have "jobs" and "contacts" scopes enabled, contact Jobber support to enable them.', 'xtremecleans');
                    
                    if (!empty($auth_url)) {
                        $error_msg .= "\n\n" . __('Authorization URL:', 'xtremecleans');
                        $error_msg .= "\n" . $auth_url;
                    }
                    
                    if (!empty($detailed_error)) {
                        $error_msg .= "\n\n" . __('Jobber Error: ' . $detailed_error, 'xtremecleans');
                    }
                    
                    // Store auth URL and diagnostics in error data
                    if (!empty($auth_url)) {
                        $error_data['auth_url'] = $auth_url;
                    }
                    $error_data['diagnostics'] = $diagnostics;
                    $error_data['token_scopes'] = $current_token_scopes;
                    $error_data['required_scopes'] = $required_scopes;
                }
            }
            
            // If still an error after refresh attempt
            if (is_wp_error($test_response)) {
                $results['connection']['success'] = false;
                $results['connection']['message'] = __('✗ Connection failed: ', 'xtremecleans') . $error_msg;
                
                wp_send_json_error(array(
                    'message' => __('Jobber connection test failed.', 'xtremecleans'),
                    'results' => $results,
                    'error_details' => $error_data,
                ));
            }
        }
        
        // Step 3: Test creating a test client (optional - only if connection test passed)
        // Note: Client creation uses REST API which may require additional permissions
        // Since we're using GraphQL for services and orders, this test is optional
        $results['client_test']['success'] = true; // Default to success (optional test)
        $results['client_test']['message'] = __('ⓘ Client creation test skipped (using GraphQL API)', 'xtremecleans');
        
        // Only test client creation if REST API is available and connection passed
        // This is optional since we use GraphQL for actual operations
        if ($results['connection']['success']) {
            // Try to test client creation via GraphQL instead of REST API
            $graphql_endpoint = 'https://api.getjobber.com/api/graphql';
            $test_client_query = '{
                clients(first: 1) {
                    nodes {
                        id
                        name
                    }
                }
            }';
            
            $api_version = apply_filters('xtremecleans_jobber_api_version', '2025-04-16');
            $access_token = get_option('xtremecleans_jobber_access_token', '');
            
            $client_graphql_response = wp_remote_post($graphql_endpoint, array(
                'timeout' => 15,
                'headers' => array(
                    'Authorization' => 'Bearer ' . $access_token,
                    'Content-Type' => 'application/json',
                    'Accept' => 'application/json',
                    'X-JOBBER-GRAPHQL-VERSION' => $api_version,
                ),
                'body' => wp_json_encode(array('query' => $test_client_query)),
            ));
            
            if (!is_wp_error($client_graphql_response)) {
                $client_status = wp_remote_retrieve_response_code($client_graphql_response);
                $client_body = wp_remote_retrieve_body($client_graphql_response);
                $client_data = json_decode($client_body, true);
                
                if ($client_status >= 200 && $client_status < 300 && isset($client_data['data']['clients'])) {
                    // Try to create a test client in Jobber so user can see it
                    // Use REST API like the actual order sync does
                    $test_client_data = array(
                        'first_name' => 'Test',
                        'last_name' => 'Client - ' . date('Y-m-d H:i:s'),
                        'email' => 'test-' . time() . '@xtremecleans-test.com',
                        'phone' => '555-0100',
                    );
                    
                    $test_client_response = $api->post('v1/clients', $test_client_data);
                    
                    if (!is_wp_error($test_client_response) && isset($test_client_response['id'])) {
                        $results['client_test']['success'] = true;
                        $results['client_test']['message'] = sprintf(__('✓ Test client created in Jobber! (ID: %s) - Check your Jobber dashboard to see it.', 'xtremecleans'), $test_client_response['id']);
                        $results['client_test']['client_id'] = $test_client_response['id'];
                    } elseif (is_wp_error($test_client_response)) {
                        $error_data = $test_client_response->get_error_data();
                        $error_msg = $test_client_response->get_error_message();
                        if (isset($error_data['status']) && $error_data['status'] == 403) {
                            // REST API permission issue, but GraphQL works
                            $results['client_test']['success'] = true;
                            $results['client_test']['message'] = __('✓ Client access verified via GraphQL (REST API requires additional permissions for creation)', 'xtremecleans');
                        } else {
                            $results['client_test']['success'] = true;
                            $results['client_test']['message'] = __('✓ Client access verified via GraphQL API', 'xtremecleans');
                        }
                    } else {
                        $results['client_test']['success'] = true;
                        $results['client_test']['message'] = __('✓ Successfully tested client access via GraphQL API', 'xtremecleans');
                    }
                } else {
                    // GraphQL client query failed, but this is optional
                    $results['client_test']['success'] = true; // Still mark as success since it's optional
                    $results['client_test']['message'] = __('ⓘ Client access test skipped (optional - GraphQL connection working)', 'xtremecleans');
                }
            } else {
                // GraphQL failed, but connection test passed, so this is optional
                $results['client_test']['success'] = true; // Still mark as success since it's optional
                $results['client_test']['message'] = __('ⓘ Client access test skipped (optional - connection verified)', 'xtremecleans');
            }
        }
        
        // Summary - connection test is the main requirement
        // Client test is optional since we use GraphQL for operations
        if ($results['connection']['success']) {
            $results['summary'] = __('✅ Connection test passed! Jobber integration is working correctly. Orders will sync automatically.', 'xtremecleans');
        } else {
            $results['summary'] = __('⚠️ Connection test failed. Please check the details above and fix any issues.', 'xtremecleans');
        }
        
        wp_send_json_success(array(
            'message' => $all_success ? __('Jobber connection test successful!', 'xtremecleans') : __('Jobber connection test completed with errors.', 'xtremecleans'),
            'results' => $results,
        ));
    }
    
    /**
     * AJAX handler: Clear Jobber token and force re-authorization
     *
     * @since 1.0.0
     */
    public function ajax_clear_jobber_token() {
        check_ajax_referer('xtremecleans_test_jobber', 'nonce');
        
        if (!current_user_can('manage_options')) {
            wp_send_json_error(array('message' => __('Permission denied.', 'xtremecleans')));
        }
        
        // Clear all Jobber tokens and scope information
        delete_option('xtremecleans_jobber_access_token');
        delete_option('xtremecleans_jobber_refresh_token');
        delete_option('xtremecleans_jobber_token_expires');
        delete_option('xtremecleans_jobber_token_scopes'); // Also clear scope tracking
<<<<<<< HEAD
=======
        delete_transient('xtremecleans_jobber_availability'); // Clear cached availability
>>>>>>> 3378c4f (plugin last vertation updated in admin dashboard)
        
        xtremecleans_log('Jobber tokens and scopes cleared by user', 'info');
        
        // Get authorization URL
        $frontend = XtremeCleans::get_instance()->frontend;
        $auth_url = '';
        if ($frontend && method_exists($frontend, 'get_jobber_authorize_url_public')) {
            $auth_url = $frontend->get_jobber_authorize_url_public();
        }
        
        wp_send_json_success(array(
            'message' => __('Token cleared successfully. Please re-authorize Jobber connection.', 'xtremecleans'),
            'auth_url' => $auth_url,
        ));
    }
    
    /**
     * AJAX handler: Fix Jobber token scopes manually
     *
     * @since 1.0.0
     */
    public function ajax_fix_jobber_scopes() {
        check_ajax_referer('xtremecleans_test_jobber', 'nonce');
        
        if (!current_user_can('manage_options')) {
            wp_send_json_error(array('message' => __('Permission denied.', 'xtremecleans')));
        }
        
        // Manually set the scopes that were requested in authorization URL
        $required_scopes = 'jobs contacts';
        $updated = update_option('xtremecleans_jobber_token_scopes', sanitize_text_field($required_scopes));
        
        xtremecleans_log('Jobber token scopes manually fixed to: ' . $required_scopes . ' (Updated: ' . ($updated ? 'Yes' : 'No') . ')', 'info');
        
        // Also verify access token exists
        $access_token = get_option('xtremecleans_jobber_access_token', '');
        if (empty($access_token)) {
            wp_send_json_error(array(
                'message' => __('No access token found. Please authorize Jobber connection first.', 'xtremecleans'),
            ));
        }
        
        wp_send_json_success(array(
            'message' => __('Token scopes manually set to: jobs contacts. Please test the connection again.', 'xtremecleans'),
            'scopes_set' => $required_scopes,
            'has_token' => !empty($access_token),
        ));
    }
    
    /**
     * AJAX handler: Sync services from Jobber
     *
     * @since 1.1.0
     */
    public function ajax_sync_services_from_jobber() {
        check_ajax_referer('xtremecleans_test_jobber', 'nonce');
        
        if (!current_user_can('manage_options')) {
            wp_send_json_error(array('message' => __('Permission denied.', 'xtremecleans')));
        }
        
        if (!xtremecleans_is_api_configured()) {
            wp_send_json_error(array('message' => __('Jobber API is not configured.', 'xtremecleans')));
        }
        
        $access_token = get_option('xtremecleans_jobber_access_token', '');
        if (empty($access_token)) {
            wp_send_json_error(array('message' => __('Access token not found. Please authorize Jobber connection first.', 'xtremecleans')));
        }
        
        // Jobber uses GraphQL API for services
        $graphql_endpoint = 'https://api.getjobber.com/api/graphql';
        
        // GraphQL query to fetch products/services from Jobber
        // Jobber API uses 'products' field, not 'services'
        // Note: ProductOrService type doesn't have 'price' or 'unit' fields directly
        // Using only basic fields that are available
        $query = '{
            products {
                nodes {
                    id
                    name
                    description
                }
            }
        }';
        
        // Jobber GraphQL API requires version header
        $api_version = apply_filters('xtremecleans_jobber_api_version', '2025-04-16');
        
        $response = wp_remote_post($graphql_endpoint, array(
            'timeout' => 30,
            'headers' => array(
                'Authorization' => 'Bearer ' . $access_token,
                'Content-Type' => 'application/json',
                'Accept' => 'application/json',
                'X-JOBBER-GRAPHQL-VERSION' => $api_version,
            ),
            'body' => wp_json_encode(array('query' => $query)),
        ));
        
        if (is_wp_error($response)) {
            xtremecleans_log('Jobber services sync error: ' . $response->get_error_message(), 'error');
            wp_send_json_error(array(
                'message' => __('Failed to fetch services from Jobber: ', 'xtremecleans') . $response->get_error_message(),
            ));
        }
        
        $status = wp_remote_retrieve_response_code($response);
        $body = wp_remote_retrieve_body($response);
        $data = json_decode($body, true);
        
        xtremecleans_log('Jobber services sync response: Status ' . $status . ', Body: ' . substr($body, 0, 1000), 'info');
        
        // Check if response is successful
        if ($status >= 200 && $status < 300) {
            // Check if products data exists (Jobber uses 'products' not 'services')
            if (isset($data['data']['products']['nodes'])) {
                $services = $data['data']['products']['nodes'];
                
                // Check if services array is empty
                if (empty($services) || !is_array($services)) {
                    $error_message = __('No services found in your Jobber account. Please add services in Jobber Dashboard first.', 'xtremecleans');
                    $error_message .= ' ' . __('Go to Jobber Dashboard → Settings → Services → Add Service', 'xtremecleans');
                    xtremecleans_log('Jobber services sync: No services found in Jobber account', 'warning');
                    wp_send_json_error(array('message' => $error_message));
                }
                
                $synced_count = 0;
                $updated_count = 0;
                
                global $wpdb;
                $table_name = $wpdb->prefix . 'xtremecleans_service_items';
                
                foreach ($services as $service) {
                    $service_id = isset($service['id']) ? sanitize_text_field($service['id']) : '';
                    $service_name = isset($service['name']) ? sanitize_text_field($service['name']) : '';
                    $description = isset($service['description']) ? sanitize_textarea_field($service['description']) : '';
                    // Jobber ProductOrService may use 'cost' instead of 'price', or fields may be nested
                    $price = 0;
                    if (isset($service['cost'])) {
                        $price = floatval($service['cost']);
                    } elseif (isset($service['price'])) {
                        $price = floatval($service['price']);
                    } elseif (isset($service['defaultPrice'])) {
                        $price = floatval($service['defaultPrice']);
                    }
                    $unit = isset($service['unit']) ? sanitize_text_field($service['unit']) : '';
                    
                    if (empty($service_name)) {
                        continue;
                    }
                    
                    // Check if service already exists (by Jobber ID or name)
                    $existing = $wpdb->get_row($wpdb->prepare(
                        "SELECT id FROM `{$table_name}` WHERE jobber_service_id = %s OR (service_name = %s AND synced_from_jobber = 1)",
                        $service_id,
                        $service_name
                    ));
                    
                    $item_data = array(
                        'service_name' => $service_name,
                        'item_name' => $service_name,
                        'item_description' => $description,
                        'clean_price' => $price,
                        'jobber_service_id' => $service_id,
                        'jobber_unit' => $unit,
                        'synced_from_jobber' => 1,
                        'updated_at' => current_time('mysql'),
                    );
                    
                    if ($existing) {
                        // Update existing
                        $wpdb->update(
                            $table_name,
                            $item_data,
                            array('id' => $existing->id),
                            array('%s', '%s', '%s', '%f', '%s', '%s', '%d', '%s'),
                            array('%d')
                        );
                        $updated_count++;
                    } else {
                        // Insert new
                        $item_data['created_at'] = current_time('mysql');
                        $wpdb->insert($table_name, $item_data);
                        $synced_count++;
                    }
                }
                
                xtremecleans_log('Jobber services synced: ' . $synced_count . ' new, ' . $updated_count . ' updated', 'info');
                
                wp_send_json_success(array(
                    'message' => sprintf(__('Successfully synced %d services from Jobber (%d new, %d updated).', 'xtremecleans'), count($services), $synced_count, $updated_count),
                    'synced' => count($services),
                    'new' => $synced_count,
                    'updated' => $updated_count,
                ));
            } elseif (isset($data['errors'])) {
                // GraphQL errors
                $error_details = '';
                if (is_array($data['errors'])) {
                    foreach ($data['errors'] as $error) {
                        if (isset($error['message'])) {
                            $error_details .= $error['message'] . ' ';
                        }
                    }
                } else {
                    $error_details = wp_json_encode($data['errors']);
                }
                
                $error_message = __('Failed to fetch services from Jobber API.', 'xtremecleans');
                if (!empty($error_details)) {
                    $error_message .= ' ' . __('Error:', 'xtremecleans') . ' ' . trim($error_details);
                }
                
                // Check for permission errors
                if (stripos($error_details, 'permission') !== false || stripos($error_details, 'access') !== false || stripos($error_details, 'unauthorized') !== false) {
                    $error_message .= ' ' . __('Please check your Jobber API permissions. You may need to re-authorize the connection.', 'xtremecleans');
                }
                
                xtremecleans_log('Jobber services sync failed - GraphQL errors: ' . $error_details, 'error');
                wp_send_json_error(array('message' => $error_message));
            } else {
                // Unknown response format
                $error_message = __('Failed to fetch services from Jobber. Unexpected response format.', 'xtremecleans');
                $error_message .= ' ' . __('Response status:', 'xtremecleans') . ' ' . $status;
                if (!empty($body)) {
                    xtremecleans_log('Jobber services sync - Unexpected response: ' . substr($body, 0, 500), 'error');
                }
                wp_send_json_error(array('message' => $error_message));
            }
        } else {
            // HTTP error status
            $error_message = __('Failed to fetch services from Jobber.', 'xtremecleans');
            $error_message .= ' ' . __('HTTP Status:', 'xtremecleans') . ' ' . $status;
            
            if (isset($data['errors'])) {
                $error_details = '';
                if (is_array($data['errors'])) {
                    foreach ($data['errors'] as $error) {
                        if (isset($error['message'])) {
                            $error_details .= $error['message'] . ' ';
                        }
                    }
                } else {
                    $error_details = wp_json_encode($data['errors']);
                }
                $error_message .= ' ' . __('Error:', 'xtremecleans') . ' ' . trim($error_details);
            } elseif (isset($data['error'])) {
                $error_message .= ' ' . __('Error:', 'xtremecleans') . ' ' . (is_string($data['error']) ? $data['error'] : wp_json_encode($data['error']));
            } elseif (!empty($body)) {
                $error_message .= ' ' . __('Response:', 'xtremecleans') . ' ' . substr($body, 0, 200);
            }
            
            // Specific error messages for common status codes
            if ($status == 401) {
                $error_message .= ' ' . __('Your access token may have expired. Please re-authorize the Jobber connection.', 'xtremecleans');
            } elseif ($status == 403) {
                $error_message .= ' ' . __('Access denied. Please check your Jobber API permissions and re-authorize with correct scopes (jobs and contacts).', 'xtremecleans');
            } elseif ($status == 404) {
                $error_message .= ' ' . __('API endpoint not found. Please check your Jobber API configuration.', 'xtremecleans');
            }
            
            xtremecleans_log('Jobber services sync failed - HTTP ' . $status . ': ' . $error_message, 'error');
            wp_send_json_error(array('message' => $error_message));
        }
    }
    
    /**
     * AJAX handler: Toggle show only Jobber services
     *
     * @since 1.1.0
     */
    public function ajax_toggle_jobber_services_only() {
        check_ajax_referer('xtremecleans_test_jobber', 'nonce');
        
        if (!current_user_can('manage_options')) {
            wp_send_json_error(array('message' => __('Permission denied.', 'xtremecleans')));
        }
        
        $enabled = isset($_POST['enabled']) && intval($_POST['enabled']) === 1;
        update_option('xtremecleans_show_only_jobber_services', $enabled);
        
        wp_send_json_success(array(
            'message' => $enabled 
                ? __('Only Jobber services will be shown in the frontend form.', 'xtremecleans')
                : __('All services will be shown in the frontend form.', 'xtremecleans'),
        ));
    }
    
    /**
     * Fetch services directly from Jobber API based on ZIP code
     *
     * @since 1.1.0
     * @param string $zip_code ZIP code
     * @return array Service names
     */
    private function fetch_services_from_jobber_by_zip($zip_code) {
        if (!xtremecleans_is_api_configured()) {
            xtremecleans_log('Cannot fetch services from Jobber: API not configured', 'error');
            return array();
        }
        
        $access_token = get_option('xtremecleans_jobber_access_token', '');
        if (empty($access_token)) {
            xtremecleans_log('Cannot fetch services from Jobber: Access token not found', 'error');
            return array();
        }
        
        // Jobber uses GraphQL API for services
        $graphql_endpoint = 'https://api.getjobber.com/api/graphql';
        
        // GraphQL query to fetch all products/services from Jobber
        // Jobber uses 'products' field, not 'services'
        // Note: ProductOrService type doesn't have 'price' or 'unit' fields directly
        // Using only basic fields that are available
        $query = '{
            products {
                nodes {
                    id
                    name
                    description
                }
            }
        }';
        
        // Jobber GraphQL API requires version header
        $api_version = apply_filters('xtremecleans_jobber_api_version', '2025-04-16');
        
        $response = wp_remote_post($graphql_endpoint, array(
            'timeout' => 30,
            'headers' => array(
                'Authorization' => 'Bearer ' . $access_token,
                'Content-Type' => 'application/json',
                'Accept' => 'application/json',
                'X-JOBBER-GRAPHQL-VERSION' => $api_version,
            ),
            'body' => wp_json_encode(array('query' => $query)),
        ));
        
        if (is_wp_error($response)) {
            xtremecleans_log('Jobber services fetch error: ' . $response->get_error_message(), 'error');
            return array();
        }
        
        $status = wp_remote_retrieve_response_code($response);
        $body = wp_remote_retrieve_body($response);
        $data = json_decode($body, true);
        
        if ($status >= 200 && $status < 300 && isset($data['data']['products']['nodes'])) {
            $services = $data['data']['products']['nodes'];
            $service_names = array();
            
<<<<<<< HEAD
            // Get ZIP code service mapping if available
            global $wpdb;
            $zip_table = $wpdb->prefix . 'xtremecleans_zip_reference';
            $zip_exists = $wpdb->get_var($wpdb->prepare("SHOW TABLES LIKE %s", $zip_table));
            $zip_service_name = '';
            
            if ($zip_exists && !empty($zip_code)) {
                $zip_table = esc_sql($zip_table);
                $zip_service_name = $wpdb->get_var($wpdb->prepare(
                    "SELECT service_name FROM `{$zip_table}` WHERE zip_code = %s LIMIT 1",
                    $zip_code
                ));
            }
=======
            // Get ZIP code service mappings if available.
            $zip_service_names = $this->get_service_names_by_zip_code($zip_code);
>>>>>>> 3378c4f (plugin last vertation updated in admin dashboard)
            
            foreach ($services as $service) {
                $service_name = isset($service['name']) ? sanitize_text_field($service['name']) : '';
                
                if (empty($service_name)) {
                    continue;
                }
                
<<<<<<< HEAD
                // If ZIP service name is set, only include matching services
                if (!empty($zip_service_name)) {
                    if (stripos($service_name, $zip_service_name) !== false || 
                        stripos($zip_service_name, $service_name) !== false) {
                        $service_names[] = $service_name;
=======
                // If ZIP service mapping exists, include any matching mapped service.
                if (!empty($zip_service_names)) {
                    foreach ($zip_service_names as $zip_service_name) {
                        if (
                            stripos($service_name, $zip_service_name) !== false ||
                            stripos($zip_service_name, $service_name) !== false
                        ) {
                            $service_names[] = $service_name;
                            break;
                        }
>>>>>>> 3378c4f (plugin last vertation updated in admin dashboard)
                    }
                } else {
                    // No ZIP mapping, include all services
                    $service_names[] = $service_name;
                }
            }
            
            // Remove duplicates and sort
            $service_names = array_unique($service_names);
            sort($service_names);
            
            xtremecleans_log('Fetched ' . count($service_names) . ' services from Jobber for ZIP: ' . $zip_code, 'info');
            
            return $service_names;
        } else {
            $error_message = __('Failed to fetch services from Jobber.', 'xtremecleans');
            if (isset($data['errors'])) {
                $error_message .= ' ' . wp_json_encode($data['errors']);
            }
            xtremecleans_log('Jobber services fetch failed: ' . $error_message, 'error');
            return array();
        }
    }
    
    /**
     * AJAX handler: Toggle fetch services directly from Jobber
     *
     * @since 1.1.0
     */
    public function ajax_toggle_fetch_from_jobber() {
        check_ajax_referer('xtremecleans_test_jobber', 'nonce');
        
        if (!current_user_can('manage_options')) {
            wp_send_json_error(array('message' => __('Permission denied.', 'xtremecleans')));
        }
        
        $enabled = isset($_POST['enabled']) && intval($_POST['enabled']) === 1;
        update_option('xtremecleans_fetch_services_from_jobber', $enabled);
        
        wp_send_json_success(array(
            'message' => $enabled 
                ? __('Services will be fetched directly from Jobber API based on ZIP code. Admin dashboard services will be ignored.', 'xtremecleans')
                : __('Services will be loaded from admin dashboard database.', 'xtremecleans'),
        ));
    }
    
    /**
     * AJAX handler: Toggle ZIP-based Jobber services
     *
     * @since 1.1.0
     */
    public function ajax_toggle_zip_based_jobber() {
        check_ajax_referer('xtremecleans_test_jobber', 'nonce');
        
        if (!current_user_can('manage_options')) {
            wp_send_json_error(array('message' => __('Permission denied.', 'xtremecleans')));
        }
        
        $enabled = isset($_POST['enabled']) && intval($_POST['enabled']) === 1;
        update_option('xtremecleans_zip_based_jobber_services', $enabled);
        
        wp_send_json_success(array(
            'message' => $enabled 
                ? __('Jobber services will be filtered based on ZIP code location.', 'xtremecleans')
                : __('All Jobber services will be shown regardless of ZIP code.', 'xtremecleans'),
        ));
    }
    
    /**
     * Handle API test request
     *
     * @since 1.0.0
     * @return array|null Test result
     */
    private function handle_api_test() {
        $nonce = isset($_POST['_wpnonce']) ? $_POST['_wpnonce'] : '';
        if (!isset($_POST['test_api']) || 
            !wp_verify_nonce($nonce, 'xtremecleans_test_api')) {
            return null;
        }
        
        if (!xtremecleans_is_api_configured()) {
            return array(
                'success' => false,
                'message' => __('API credentials are not configured. Please configure them in Settings.', 'xtremecleans'),
            );
        }
        
        $endpoint = isset($_POST['test_endpoint']) ? sanitize_text_field($_POST['test_endpoint']) : '/';
        $method   = isset($_POST['test_method']) ? sanitize_text_field($_POST['test_method']) : 'GET';
        $body     = isset($_POST['test_body']) ? sanitize_textarea_field($_POST['test_body']) : '';
        
        $plugin = XtremeCleans::get_instance();
        if (!isset($plugin->api)) {
            return array(
                'success' => false,
                'message' => __('API class not initialized.', 'xtremecleans'),
            );
        }
        
        $api_url = xtremecleans_get_option('api_url', '');
        $url     = trailingslashit($api_url) . ltrim($endpoint, '/');
        
        $args = array(
            'method'  => $method,
            'headers' => array(
                'Authorization' => 'Bearer ' . xtremecleans_get_option('api_key', ''),
                'Content-Type'  => 'application/json',
            ),
            'timeout' => 30,
        );
        
        if (in_array($method, array('POST', 'PUT', 'PATCH'), true) && !empty($body)) {
            $args['body'] = $body;
        }
        
        $response = wp_remote_request($url, $args);
        
        if (is_wp_error($response)) {
            return array(
                'success' => false,
                'message' => $response->get_error_message(),
            );
        }
        
        $response_body = wp_remote_retrieve_body($response);
        $status_code   = wp_remote_retrieve_response_code($response);
        $headers       = wp_remote_retrieve_headers($response);
        
        return array(
            'success'     => ($status_code >= 200 && $status_code < 300),
            'status_code' => $status_code,
            'body'        => $response_body,
            'headers'     => $headers,
        );
    }
    
    /**
     * Render section callbacks
     *
     * @since 1.0.0
     */
    public function render_general_section() {
        echo '<p>' . esc_html__('Configure general plugin settings.', 'xtremecleans') . '</p>';
    }
    
    public function render_display_section() {
        echo '<p>' . esc_html__('Configure display and styling options.', 'xtremecleans') . '</p>';
    }
    
    public function render_email_section() {
        echo '<p>' . esc_html__('Configure email settings for lead notifications and user confirmations.', 'xtremecleans') . '</p>';
        echo '<div class="notice notice-info inline" style="margin: 10px 0;">';
        echo '<p><strong>' . esc_html__('Email Templates:', 'xtremecleans') . '</strong> ';
        echo esc_html__('Use these placeholders in templates: {name}, {email}, {phone}, {zip_code}, {zone_name}, {date}, {site_name}', 'xtremecleans');
        echo '</p></div>';
        
        // Check if on localhost/development
        $is_localhost = in_array($_SERVER['HTTP_HOST'], array('localhost', '127.0.0.1', '::1')) || strpos($_SERVER['HTTP_HOST'], '.local') !== false || strpos($_SERVER['HTTP_HOST'], '.test') !== false;
        
        if ($is_localhost) {
            echo '<div class="notice notice-warning inline" style="margin: 10px 0;">';
            echo '<p><strong>' . esc_html__('⚠ Development Environment Detected:', 'xtremecleans') . '</strong> ';
            echo esc_html__('Email sending may not work on localhost. For testing, consider using an SMTP plugin like "WP Mail SMTP" or configure your server\'s mail settings.', 'xtremecleans');
            echo '</p></div>';
        }
    }
    
    public function render_payment_section() {
        echo '<p>' . esc_html__('Configure payment gateway settings for processing customer deposits.', 'xtremecleans') . '</p>';
        echo '<div class="notice notice-info inline" style="margin: 10px 0;">';
        echo '<p><strong>' . esc_html__('Stripe Integration:', 'xtremecleans') . '</strong> ';
        echo esc_html__('When enabled, customers will pay a $20 deposit directly on the website before job creation. Get your API keys from the <a href="https://dashboard.stripe.com/apikeys" target="_blank">Stripe Dashboard</a>.', 'xtremecleans');
        echo '</p></div>';
        
        // Check if test mode is enabled
        $test_mode = xtremecleans_get_option('stripe_test_mode', '1');
        if ($test_mode == 1) {
            echo '<div class="notice notice-success inline" style="margin: 10px 0; background: #d4edda; border-left: 4px solid #28a745;">';
            echo '<p><strong>' . esc_html__('✅ Test Mode Active:', 'xtremecleans') . '</strong> ';
            echo esc_html__('You are using Stripe test mode. Use test API keys and test card numbers. No real charges will be made.', 'xtremecleans');
            echo '</p>';
            echo '<p style="margin-top: 10px;"><strong>' . esc_html__('Test Card Numbers:', 'xtremecleans') . '</strong></p>';
            echo '<ul style="margin: 5px 0; padding-left: 20px;">';
            echo '<li><strong>Success:</strong> <code>4242 4242 4242 4242</code> (any future date, any CVC)</li>';
            echo '<li><strong>Decline:</strong> <code>4000 0000 0000 0002</code></li>';
            echo '<li><strong>3D Secure:</strong> <code>4000 0025 0000 3155</code></li>';
            echo '</ul>';
            echo '<p style="margin-top: 10px;"><small>' . esc_html__('💡 After successful test payment, orders will automatically sync to Jobber CRM.', 'xtremecleans') . '</small></p>';
            echo '</div>';
        }
        
        // Check if Stripe is enabled
        $stripe_enabled = xtremecleans_get_option('stripe_enabled', '0');
        if (!$stripe_enabled) {
            echo '<div class="notice notice-warning inline" style="margin: 10px 0;">';
            echo '<p><strong>' . esc_html__('Payment Processing Disabled:', 'xtremecleans') . '</strong> ';
            echo esc_html__('Stripe payments are currently disabled. Enable it below to process deposits online.', 'xtremecleans');
            echo '</p></div>';
        } else {
            // Show Jobber sync info when Stripe is enabled
            $jobber_configured = xtremecleans_is_api_configured();
            if ($jobber_configured) {
                echo '<div class="notice notice-success inline" style="margin: 10px 0; background: #d1ecf1; border-left: 4px solid #17a2b8;">';
                echo '<p><strong>' . esc_html__('🔄 Jobber Sync Active:', 'xtremecleans') . '</strong> ';
                echo esc_html__('Orders will automatically sync to Jobber CRM after payment confirmation. Client, Quote, and Job will be created.', 'xtremecleans');
                echo '</p></div>';
            } else {
                echo '<div class="notice notice-warning inline" style="margin: 10px 0;">';
                echo '<p><strong>' . esc_html__('⚠️ Jobber Not Configured:', 'xtremecleans') . '</strong> ';
                echo esc_html__('Orders will be saved but will not sync to Jobber until Jobber integration is authorized. Go to the Jobber tab to authorize.', 'xtremecleans');
                echo '</p></div>';
            }
        }
    }
    
    public function render_jobber_section() {
        // Check for OAuth success/error messages
        $oauth_success = get_transient('xtremecleans_jobber_oauth_success');
        $oauth_error = get_transient('xtremecleans_jobber_oauth_error');
        
        if ($oauth_success) {
            delete_transient('xtremecleans_jobber_oauth_success');
            echo '<div class="notice notice-success is-dismissible" style="margin: 15px 0;">';
            echo '<p><strong>' . esc_html__('✅ Success!', 'xtremecleans') . '</strong> ';
            echo esc_html__('Jobber connection authorized successfully! Your access token has been saved. Orders will now sync automatically to Jobber.', 'xtremecleans');
            echo '</p></div>';
        }
        
        if ($oauth_error) {
            delete_transient('xtremecleans_jobber_oauth_error');
            echo '<div class="notice notice-error is-dismissible" style="margin: 15px 0;">';
            echo '<p><strong>' . esc_html__('❌ Authorization Failed:', 'xtremecleans') . '</strong> ';
            echo esc_html($oauth_error);
            echo '</p></div>';
        }
        
        echo '<p>' . esc_html__('Configure Jobber API integration settings for automatic job creation and client management.', 'xtremecleans') . '</p>';
        echo '<div class="notice notice-info inline" style="margin: 10px 0;">';
        echo '<p><strong>' . esc_html__('Jobber Integration:', 'xtremecleans') . '</strong> ';
        echo esc_html__('When configured, orders will automatically create Clients, Quotes, and Jobs in your Jobber account. You need to provide the Webhook URL and OAuth Callback URL to Jobber to receive API credentials.', 'xtremecleans');
        echo '</p></div>';
        echo '<div class="notice notice-info inline" style="margin: 10px 0; padding: 12px 15px; background: #f0f6fc; border-left: 4px solid #2271b1;">';
        echo '<p><strong>' . esc_html__('Quick checklist (data not syncing?):', 'xtremecleans') . '</strong></p>';
        echo '<ol style="margin: 8px 0 0 0; padding-left: 20px;">';
        echo '<li>' . esc_html__('Enter Jobber Client ID and Client Secret below (from Jobber API / Developer settings).', 'xtremecleans') . '</li>';
        echo '<li>' . esc_html__('Click "Authorize Jobber Connection Now" and complete the login in Jobber.', 'xtremecleans') . '</li>';
        echo '<li>' . esc_html__('After authorization, new orders (and "Push to Jobber" on existing orders) will sync automatically.', 'xtremecleans') . '</li>';
        echo '<li>' . esc_html__('If sync still fails, check the Orders table: the Jobber column shows the exact error message for each order.', 'xtremecleans') . '</li>';
        echo '</ol></div>';
        
        // Check Jobber connection status
        $client_id = xtremecleans_get_option('jobber_client_id', '');
        $client_secret = xtremecleans_get_option('jobber_client_secret', '');
        $access_token = get_option('xtremecleans_jobber_access_token', '');
        $refresh_token = get_option('xtremecleans_jobber_refresh_token', '');
        
        // Get OAuth authorization URL if needed
        $frontend = XtremeCleans::get_instance()->frontend;
        $auth_url = '';
        if ($frontend && method_exists($frontend, 'get_jobber_authorize_url_public')) {
            $auth_url = $frontend->get_jobber_authorize_url_public();
        }
        
        if (empty($client_id) || empty($client_secret)) {
            // Step 1: No credentials entered
            echo '<div class="notice notice-warning inline" style="margin: 10px 0; padding: 15px; border-left: 4px solid #f0b849;">';
            echo '<p><strong>' . esc_html__('⚠ Jobber Not Configured:', 'xtremecleans') . '</strong> ';
            echo esc_html__('Please provide your Client ID and Client Secret from Jobber to enable integration. Contact Jobber support with the Webhook URL and OAuth Callback URL shown below.', 'xtremecleans');
            echo '</p></div>';
        } elseif (empty($access_token)) {
            // Step 2: Credentials entered but not authorized (no access token)
            echo '<div class="notice notice-warning inline" style="margin: 20px 0; padding: 20px; border-left: 4px solid #d63638; background: #fff3cd;">';
            echo '<h3 style="margin-top: 0; color: #d63638;">' . esc_html__('⚠️ Authorization Required', 'xtremecleans') . '</h3>';
            echo '<p><strong>' . esc_html__('Your Client ID and Client Secret are configured, but you need to authorize the connection with Jobber.', 'xtremecleans') . '</strong></p>';
            echo '<p>' . esc_html__('Click the button below to authorize. You will be redirected to Jobber to approve the connection. After approval, you will be redirected back and the access token will be saved automatically.', 'xtremecleans') . '</p>';
            
            if (!empty($auth_url)) {
                echo '<div style="margin-top: 15px;">';
                echo '<a href="' . esc_url($auth_url) . '" class="button button-primary button-large" style="font-size: 16px; padding: 10px 20px; height: auto;" id="xtremecleans-authorize-btn">';
                echo '<span class="dashicons dashicons-admin-links" style="margin-top: 4px;"></span> ';
                echo esc_html__('🔗 Authorize Jobber Connection Now', 'xtremecleans');
                echo '</a>';
                echo '</div>';
                echo '<p style="margin-top: 10px; font-size: 12px; color: #666;">';
                echo esc_html__('This will open Jobber in a new window. After authorization, you will be redirected back to this page.', 'xtremecleans');
                echo '</p>';
            } else {
                echo '<p style="color: #d63638;"><strong>' . esc_html__('Error: Unable to generate authorization URL. Please check your Client ID.', 'xtremecleans') . '</strong></p>';
            }
            echo '</div>';
        } else {
            // Step 3: Fully connected (has access token)
            $token_expires = get_option('xtremecleans_jobber_token_expires', 0);
            $expires_info = '';
            if ($token_expires > 0) {
                $expires_date = date('Y-m-d H:i:s', $token_expires);
                $time_remaining = $token_expires - time();
                if ($time_remaining > 0) {
                    $days_remaining = floor($time_remaining / DAY_IN_SECONDS);
                    $expires_info = sprintf(__(' (Expires in %d days)', 'xtremecleans'), $days_remaining);
                } else {
                    $expires_info = __(' (Expired - refresh needed)', 'xtremecleans');
                }
            }
            
            echo '<div class="notice notice-success inline" style="margin: 10px 0;">';
            echo '<p><strong>' . esc_html__('✓ Jobber Integration Active & Connected:', 'xtremecleans') . '</strong> ';
            echo esc_html__('Your Jobber API is fully configured and connected. Orders will be automatically synced to Jobber.', 'xtremecleans');
            if (!empty($expires_info)) {
                echo '<br><small>' . esc_html($expires_info) . '</small>';
            }
            echo '</p></div>';
            
            // Show connection details
            echo '<div class="notice notice-info inline" style="margin: 10px 0;">';
            echo '<p><strong>' . esc_html__('Connection Status:', 'xtremecleans') . '</strong></p>';
            echo '<ul style="margin: 5px 0; padding-left: 20px;">';
            echo '<li>' . esc_html__('Client ID: Configured', 'xtremecleans') . '</li>';
            echo '<li>' . esc_html__('Client Secret: Configured', 'xtremecleans') . '</li>';
            echo '<li>' . esc_html__('Access Token: Active', 'xtremecleans') . '</li>';
            if (!empty($refresh_token)) {
                echo '<li>' . esc_html__('Refresh Token: Available', 'xtremecleans') . '</li>';
            }
            echo '</ul>';
            echo '</div>';
            
            // Add Test Connection Button
            echo '<div class="notice notice-info inline" style="margin: 20px 0; padding: 20px; background: #e7f3ff; border-left: 4px solid #2271b1;">';
            echo '<h3 style="margin-top: 0;">' . esc_html__('🧪 Test Jobber Connection', 'xtremecleans') . '</h3>';
            echo '<p>' . esc_html__('Click the button below to test your Jobber connection. This will verify that your API credentials are working and can create clients in Jobber.', 'xtremecleans') . '</p>';
            echo '<button type="button" id="xtremecleans-test-jobber-btn" class="button button-secondary" style="margin-top: 10px;">';
            echo '<span class="dashicons dashicons-admin-tools" style="margin-top: 4px;"></span> ';
            echo esc_html__('Test Jobber Connection', 'xtremecleans');
            echo '</button>';
            echo '<div id="xtremecleans-test-result" style="margin-top: 15px; display: none;"></div>';
            echo '</div>';
            
            // Add Sync Services from Jobber section
            echo '<div class="notice notice-info inline" style="margin: 20px 0; padding: 20px; background: #e7f3ff; border-left: 4px solid #2271b1;">';
            echo '<h3 style="margin-top: 0;">' . esc_html__('📦 Sync Services from Jobber', 'xtremecleans') . '</h3>';
            echo '<p>' . esc_html__('Fetch all services from your Jobber account and sync them to your WordPress database. This will allow you to show only Jobber services in the frontend form.', 'xtremecleans') . '</p>';
            echo '<button type="button" id="xtremecleans-sync-services-btn" class="button button-primary" style="margin-top: 10px;">';
            echo '<span class="dashicons dashicons-update" style="margin-top: 4px;"></span> ';
            echo esc_html__('Sync Services from Jobber', 'xtremecleans');
            echo '</button>';
            echo '<div id="xtremecleans-sync-services-result" style="margin-top: 15px; display: none;"></div>';
            
            // Add option to fetch services directly from Jobber
            $fetch_from_jobber = get_option('xtremecleans_fetch_services_from_jobber', false);
            $show_only_jobber = get_option('xtremecleans_show_only_jobber_services', false);
            $zip_based_jobber = get_option('xtremecleans_zip_based_jobber_services', false);
            
            echo '<div style="margin-top: 15px; padding: 10px; background: #f0f0f1; border-radius: 4px;">';
            echo '<h4 style="margin-top: 0;">' . esc_html__('Service Display Options', 'xtremecleans') . '</h4>';
            
            // Fetch directly from Jobber option (main option)
            echo '<label style="display: flex; align-items: center; cursor: pointer; margin-bottom: 10px; font-weight: 600;">';
            echo '<input type="checkbox" id="xtremecleans-fetch-from-jobber" ' . checked($fetch_from_jobber, true, false) . ' style="margin-right: 8px;">';
            echo '<span>' . esc_html__('Fetch services directly from Jobber API (not from admin dashboard)', 'xtremecleans') . '</span>';
            echo '</label>';
            echo '<p style="margin: 5px 0 15px 0; font-size: 12px; color: #666; padding-left: 24px;">';
            echo esc_html__('When enabled, services will be fetched directly from Jobber API based on ZIP code. Admin dashboard services will be ignored.', 'xtremecleans');
            echo '</p>';
            
            // Show only Jobber services option (for database filtering)
            echo '<label style="display: flex; align-items: center; cursor: pointer; margin-bottom: 10px;">';
            echo '<input type="checkbox" id="xtremecleans-show-only-jobber" ' . checked($show_only_jobber, true, false) . ' style="margin-right: 8px;">';
            echo '<span>' . esc_html__('Show only Jobber services in frontend form (from database)', 'xtremecleans') . '</span>';
            echo '</label>';
            echo '<p style="margin: 5px 0 10px 0; font-size: 12px; color: #666; padding-left: 24px;">';
            echo esc_html__('When enabled, only services synced from Jobber (stored in database) will be displayed.', 'xtremecleans');
            echo '</p>';
            
            // ZIP-based Jobber services option
            echo '<label style="display: flex; align-items: center; cursor: pointer;">';
            echo '<input type="checkbox" id="xtremecleans-zip-based-jobber" ' . checked($zip_based_jobber, true, false) . ' style="margin-right: 8px;">';
            echo '<span>' . esc_html__('Show Jobber services based on ZIP code location (from database)', 'xtremecleans') . '</span>';
            echo '</label>';
            echo '<p style="margin: 5px 0 0 0; font-size: 12px; color: #666; padding-left: 24px;">';
            echo esc_html__('When enabled, only Jobber services matching the ZIP code\'s service will be shown. This requires ZIP codes to be configured with service names.', 'xtremecleans');
            echo '</p>';
            echo '</div>';
            echo '</div>';
            
            // Add Clear Token & Re-authorize section
            echo '<div class="notice notice-warning inline" style="margin: 20px 0; padding: 20px; border-left: 4px solid #f0b849; background: #fffbf0;">';
            echo '<h3 style="margin-top: 0; color: #856404;">' . esc_html__('🔄 Re-authorize Connection', 'xtremecleans') . '</h3>';
            echo '<p><strong>' . esc_html__('Getting "Access denied" errors?', 'xtremecleans') . '</strong></p>';
            echo '<p style="font-size: 13px; color: #666;">';
            echo esc_html__('If you\'re getting 403 "Access denied" errors, your OAuth token may have incorrect permissions. Click the button below to clear the current token and re-authorize with the correct scopes (jobs and contacts).', 'xtremecleans');
            echo '</p>';
            echo '<div style="margin-top: 15px;">';
            echo '<button type="button" id="xtremecleans-clear-token-btn" class="button button-secondary" style="margin-right: 10px;">';
            echo '<span class="dashicons dashicons-update" style="margin-top: 4px;"></span> ';
            echo esc_html__('Clear Token & Re-authorize', 'xtremecleans');
            echo '</button>';
            echo '<button type="button" id="xtremecleans-fix-scopes-btn" class="button button-secondary" style="background: #2271b1; color: white; border-color: #2271b1;">';
            echo '<span class="dashicons dashicons-admin-tools" style="margin-top: 4px;"></span> ';
            echo esc_html__('Fix Scopes Manually', 'xtremecleans');
            echo '</button>';
            echo '</div>';
            echo '<p style="margin-top: 10px; font-size: 12px; color: #666;">';
            echo '<strong>' . esc_html__('Note:', 'xtremecleans') . '</strong> ';
            echo esc_html__('If you already authorized with all permissions but still getting 403 error, click "Fix Scopes Manually" to set scopes to "jobs contacts".', 'xtremecleans');
            echo '</p>';
            echo '<div id="xtremecleans-clear-token-result" style="margin-top: 15px; display: none;"></div>';
            echo '</div>';
        }
    }
    
    /**
     * Register REST API routes
     *
     * @since 1.0.0
     */
    public function register_rest_routes() {
        register_rest_route(
            'xtremecleans',
            '/jobber/webhook',
            array(
                'methods' => 'POST',
                'callback' => array($this, 'handle_jobber_webhook'),
                'permission_callback' => array($this, 'verify_webhook_permission'),
            )
        );
    }
    
    /**
     * Verify webhook permission
     *
     * @since 1.0.0
     * @param WP_REST_Request $request
     * @return bool
     */
    public function verify_webhook_permission($request) {
        // Get the signature from the header
        $signature = $request->get_header('x-jobber-hmac-sha256');
        
        // If no signature is present, deny the request
        if (empty($signature)) {
            xtremecleans_log('Jobber webhook denied: No signature provided', 'error');
            return false;
        }
        
        // Get the client secret
        $client_secret = get_option('xtremecleans_jobber_client_secret');
        
        // If no client secret is configured, we can't verify, so deny for security
        if (empty($client_secret)) {
            xtremecleans_log('Jobber webhook denied: No client secret configured', 'error');
            return false;
        }
        
        // Get the raw body
        $body = $request->get_body();
        
        // Calculate the expected signature
        // Jobber uses HMAC-SHA256 with the client secret and the raw body
        // The result is base64 encoded
        $calculated_signature = base64_encode(hash_hmac('sha256', $body, $client_secret, true));
        
        // Verify the signature (using hash_equals to prevent timing attacks)
        if (hash_equals($calculated_signature, $signature)) {
            return true;
        }
        
        xtremecleans_log('Jobber webhook denied: Invalid signature', 'error');
        return false;
    }
    
    /**
     * Handle Jobber webhook
     *
     * @since 1.0.0
     * @param WP_REST_Request $request
     * @return WP_REST_Response
     */
    public function handle_jobber_webhook($request) {
        // Log webhook received
        xtremecleans_log('Jobber webhook received', 'info');
        
        // Get webhook data
        $body = $request->get_body();
        $data = json_decode($body, true);
        
        // Log webhook data
        if (!empty($data)) {
            xtremecleans_log('Webhook data: ' . wp_json_encode($data), 'info');
        }
        
        // Handle different webhook events
        $event_type = isset($data['type']) ? sanitize_text_field($data['type']) : '';
        $event_data = isset($data['data']) ? $data['data'] : array();
        
        switch ($event_type) {
            case 'quote.deposit_paid':
            case 'quote.approved':
            case 'payment.received':
                // Handle payment confirmation
                $this->handle_payment_webhook($event_data);
                break;
            
            case 'job.created':
            case 'job.updated':
                // Handle job updates
                $this->handle_job_webhook($event_data);
                break;
            
            default:
                // Log unknown event type
                xtremecleans_log('Unknown webhook event type: ' . $event_type, 'warning');
                break;
        }
        
        // Return success response
        return new WP_REST_Response(array(
            'success' => true,
            'message' => 'Webhook received',
        ), 200);
    }
    
    /**
     * Handle payment webhook from Jobber
     *
     * @since 1.0.0
     * @param array $data Webhook data
     */
    private function handle_payment_webhook($data) {
        // Find order by quote_id or job_id
        global $wpdb;
        $table_name = $wpdb->prefix . 'xtremecleans_orders';
        
        $quote_id = isset($data['quote_id']) ? sanitize_text_field($data['quote_id']) : '';
        $job_id = isset($data['job_id']) ? sanitize_text_field($data['job_id']) : '';
        
        if (empty($quote_id) && empty($job_id)) {
            xtremecleans_log('Payment webhook: No quote_id or job_id provided', 'warning');
            return;
        }
        
        // Find order
        $order = null;
        if (!empty($quote_id)) {
            $order = $wpdb->get_row($wpdb->prepare(
                "SELECT * FROM {$table_name} WHERE jobber_quote_id = %s",
                $quote_id
            ));
        } elseif (!empty($job_id)) {
            $order = $wpdb->get_row($wpdb->prepare(
                "SELECT * FROM {$table_name} WHERE jobber_job_id = %s",
                $job_id
            ));
        }
        
        if (!$order) {
            xtremecleans_log('Payment webhook: Order not found', 'warning');
            return;
        }
        
        // Update order payment status
        $wpdb->update(
            $table_name,
            array(
                'payment_status' => 'paid',
                'deposit_paid_at' => current_time('mysql'),
                'updated_at' => current_time('mysql'),
            ),
            array('id' => $order->id),
            array('%s', '%s', '%s'),
            array('%d')
        );
        
        xtremecleans_log('Payment webhook: Order #' . $order->id . ' marked as paid', 'info');
    }
    
    /**
     * Handle job webhook from Jobber
     *
     * @since 1.0.0
     * @param array $data Webhook data
     */
    private function handle_job_webhook($data) {
        $job_id = isset($data['id']) ? sanitize_text_field($data['id']) : '';
        
        if (empty($job_id)) {
            xtremecleans_log('Job webhook: No job_id provided', 'warning');
            return;
        }
        
        // Log job update
        xtremecleans_log('Job webhook: Job #' . $job_id . ' updated', 'info');
        
        // You can add logic here to sync job status back to WordPress
    }
    
    /**
     * Render field callbacks
     *
     * @since 1.0.0
     */
    public function render_api_url_field() {
        $value = xtremecleans_get_option('api_url', '');
        echo '<input type="url" name="xtremecleans_api_url" value="' . esc_attr($value) . '" class="regular-text" placeholder="https://api.example.com" />';
        echo '<p class="description">' . esc_html__('Base URL for your API', 'xtremecleans') . '</p>';
    }
    
    public function render_api_key_field() {
        $value = xtremecleans_get_option('api_key', '');
        echo '<input type="password" name="xtremecleans_api_key" value="' . esc_attr($value) . '" class="regular-text" />';
        echo '<p class="description">' . esc_html__('Your API key or token', 'xtremecleans') . '</p>';
    }
    
    public function render_webhook_url_field() {
        $webhook_url = home_url('/wp-json/xtremecleans/jobber/webhook');
        ?>
        <div style="display: flex; align-items: center; gap: 10px;">
            <input type="text" 
                   id="xtremecleans_webhook_url" 
                   value="<?php echo esc_attr($webhook_url); ?>" 
                   class="regular-text code" 
                   readonly 
                   style="background-color: #f0f0f1; cursor: text;" />
            <button type="button" 
                    class="button button-secondary xtremecleans-copy-btn" 
                    data-copy-target="xtremecleans_webhook_url"
                    title="<?php esc_attr_e('Copy to clipboard', 'xtremecleans'); ?>">
                <span class="dashicons dashicons-clipboard"></span> <?php esc_html_e('Copy', 'xtremecleans'); ?>
            </button>
        </div>
        <p class="description"><?php esc_html_e('Copy this URL and provide it to Jobber for webhook configuration.', 'xtremecleans'); ?></p>
        <?php
    }
    
    public function render_oauth_callback_url_field() {
        $callback_url = home_url('/oauth/jobber/callback');
        ?>
        <div style="display: flex; align-items: center; gap: 10px;">
            <input type="text" 
                   id="xtremecleans_oauth_callback_url" 
                   value="<?php echo esc_attr($callback_url); ?>" 
                   class="regular-text code" 
                   readonly 
                   style="background-color: #f0f0f1; cursor: text;" />
            <button type="button" 
                    class="button button-secondary xtremecleans-copy-btn" 
                    data-copy-target="xtremecleans_oauth_callback_url"
                    title="<?php esc_attr_e('Copy to clipboard', 'xtremecleans'); ?>">
                <span class="dashicons dashicons-clipboard"></span> <?php esc_html_e('Copy', 'xtremecleans'); ?>
            </button>
        </div>
        <p class="description"><?php esc_html_e('Copy this URL and provide it to Jobber as the OAuth Callback URL when requesting API credentials.', 'xtremecleans'); ?></p>
        <?php
    }
    
    public function render_jobber_client_id_field() {
        $value = xtremecleans_get_option('jobber_client_id', '');
        echo '<input type="text" name="xtremecleans_jobber_client_id" value="' . esc_attr($value) . '" class="regular-text code" />';
        echo '<p class="description">' . esc_html__('Paste the Client ID Jobber provided.', 'xtremecleans') . '</p>';
    }
    
    public function render_jobber_client_secret_field() {
        $value = xtremecleans_get_option('jobber_client_secret', '');
        echo '<input type="password" name="xtremecleans_jobber_client_secret" value="' . esc_attr($value) . '" class="regular-text code" />';
        echo '<p class="description">' . esc_html__('Paste the Client Secret Jobber provided.', 'xtremecleans') . '</p>';
    }
    
<<<<<<< HEAD
=======
    public function render_travel_section() {
        echo '<p>' . esc_html__('Use Google Maps to enforce a 1-hour travel rule: a slot is only bookable if the crew can drive from this job to the next job in 60 minutes or less (in traffic). Requires Geocoding API and Distance Matrix API enabled in Google Cloud.', 'xtremecleans') . '</p>';
    }
    
    public function render_travel_enabled_field() {
        $value = get_option('xtremecleans_travel_enabled', '0');
        echo '<label><input type="checkbox" name="xtremecleans_travel_enabled" value="1" ' . checked($value, '1', false) . ' /> ' . esc_html__('Enable travel time validation when placing orders', 'xtremecleans') . '</label>';
    }
    
    public function render_google_api_key_field() {
        $value = get_option('xtremecleans_google_api_key', '');
        echo '<input type="text" name="xtremecleans_google_api_key" value="' . esc_attr($value) . '" class="regular-text code" autocomplete="off" />';
        echo '<p class="description">' . esc_html__('Google API key with Geocoding API and Distance Matrix API enabled. Required for travel rule.', 'xtremecleans') . '</p>';
    }
    
    public function render_default_job_duration_field() {
        $value = get_option('xtremecleans_default_job_duration_minutes', '120');
        echo '<input type="number" name="xtremecleans_default_job_duration_minutes" value="' . esc_attr($value) . '" min="30" max="480" step="15" class="small-text" /> ' . esc_html__('minutes', 'xtremecleans');
        echo '<p class="description">' . esc_html__('Used to compute job end time E when checking travel to next job. Overridden by order duration if available.', 'xtremecleans') . '</p>';
    }
    
    public function render_travel_fallback_message_field() {
        $default = __('Online booking for that time is limited right now. Choose another time or call/text 410-819-2223.', 'xtremecleans');
        $value = get_option('xtremecleans_travel_fallback_message', $default);
        echo '<textarea name="xtremecleans_travel_fallback_message" rows="3" class="large-text">' . esc_textarea($value) . '</textarea>';
        echo '<p class="description">' . esc_html__('Shown when the slot fails travel rule or Google API returns an error.', 'xtremecleans') . '</p>';
    }
    
    public function render_travel_fallback_phone_field() {
        $value = get_option('xtremecleans_travel_fallback_phone', '410-819-2223');
        echo '<input type="text" name="xtremecleans_travel_fallback_phone" value="' . esc_attr($value) . '" class="regular-text" />';
        echo '<p class="description">' . esc_html__('Phone number included in the fallback message (call/text).', 'xtremecleans') . '</p>';
    }

    public function render_slot_capacity_enabled_field() {
        $value = get_option('xtremecleans_slot_capacity_enabled', '0');
        echo '<label><input type="checkbox" name="xtremecleans_slot_capacity_enabled" value="1" ' . checked($value, '1', false) . ' /> ' . esc_html__('Allow multiple bookings in the same time slot', 'xtremecleans') . '</label>';
        echo '<p class="description">' . esc_html__('When disabled, only 1 booking is allowed per slot. When enabled, use the limit below.', 'xtremecleans') . '</p>';
    }

    public function render_slot_capacity_field() {
        $value = absint(get_option('xtremecleans_slot_capacity', 1));
        if ($value < 1) {
            $value = 1;
        }
        echo '<input type="number" name="xtremecleans_slot_capacity" value="' . esc_attr($value) . '" min="1" max="100" step="1" class="small-text" /> ';
        echo '<span>' . esc_html__('bookings per slot', 'xtremecleans') . '</span>';
        echo '<p class="description">' . esc_html__('Examples: 1 = only one booking, 3 = up to three bookings, 10 = up to ten bookings in the same arrival window.', 'xtremecleans') . '</p>';
    }

    public function sanitize_slot_capacity($value) {
        $capacity = absint($value);
        return $capacity > 0 ? $capacity : 1;
    }
    
>>>>>>> 3378c4f (plugin last vertation updated in admin dashboard)
    // Stripe Payment Settings Field Renderers
    public function render_stripe_enabled_field() {
        $value = xtremecleans_get_option('stripe_enabled', '0');
        $test_mode = xtremecleans_get_option('stripe_test_mode', '1');
        
        echo '<input type="checkbox" name="xtremecleans_stripe_enabled" value="1" id="xtremecleans_stripe_enabled" ' . checked(1, $value, false) . ' />';
        echo '<label for="xtremecleans_stripe_enabled">' . esc_html__('Enable Stripe payment processing for deposits', 'xtremecleans') . '</label>';
        echo '<p class="description">' . esc_html__('When enabled, customers will pay $20 deposit directly on the website before job creation.', 'xtremecleans') . '</p>';
        
        // Show requirement message
        if ($value == 0) {
            if ($test_mode == 1) {
                echo '<p class="description" style="color: #d63638; font-weight: 600; margin-top: 10px;">';
                echo '⚠️ <strong>' . esc_html__('Requirement:', 'xtremecleans') . '</strong> ';
                echo esc_html__('You must enter Stripe Test API keys (Publishable Key and Secret Key) before enabling Stripe payments.', 'xtremecleans');
                echo '</p>';
            } else {
                echo '<p class="description" style="color: #d63638; font-weight: 600; margin-top: 10px;">';
                echo '⚠️ <strong>' . esc_html__('Requirement:', 'xtremecleans') . '</strong> ';
                echo esc_html__('You must enter Stripe Live API keys (Publishable Key and Secret Key) before enabling Stripe payments.', 'xtremecleans');
                echo '</p>';
            }
        }
    }
    
    public function render_stripe_test_mode_field() {
        $value = xtremecleans_get_option('stripe_test_mode', '1');
        echo '<input type="checkbox" name="xtremecleans_stripe_test_mode" value="1" ' . checked(1, $value, false) . ' />';
        echo '<label>' . esc_html__('Enable test mode (use test API keys)', 'xtremecleans') . '</label>';
        echo '<p class="description">' . esc_html__('Use test mode during development. Disable for live payments.', 'xtremecleans') . '</p>';
    }
    
    public function render_stripe_publishable_key_field() {
        $value = xtremecleans_get_option('stripe_publishable_key', '');
        $test_mode = xtremecleans_get_option('stripe_test_mode', '1');
        $disabled = $test_mode ? 'disabled' : '';
        echo '<input type="text" name="xtremecleans_stripe_publishable_key" value="' . esc_attr($value) . '" class="regular-text code" ' . $disabled . ' />';
        echo '<p class="description">' . esc_html__('Your Stripe Publishable Key (starts with pk_live_). Get it from <a href="https://dashboard.stripe.com/apikeys" target="_blank">Stripe Dashboard</a>.', 'xtremecleans') . '</p>';
    }
    
    public function render_stripe_secret_key_field() {
        $value = xtremecleans_get_option('stripe_secret_key', '');
        $test_mode = xtremecleans_get_option('stripe_test_mode', '1');
        $disabled = $test_mode ? 'disabled' : '';
        echo '<input type="password" name="xtremecleans_stripe_secret_key" value="' . esc_attr($value) . '" class="regular-text code" ' . $disabled . ' />';
        echo '<p class="description">' . esc_html__('Your Stripe Secret Key (starts with sk_live_). Keep this secure!', 'xtremecleans') . '</p>';
    }
    
    public function render_stripe_test_publishable_key_field() {
        $value = xtremecleans_get_option('stripe_test_publishable_key', '');
        $test_mode = xtremecleans_get_option('stripe_test_mode', '1');
        $disabled = $test_mode ? '' : 'disabled';
        echo '<input type="text" name="xtremecleans_stripe_test_publishable_key" value="' . esc_attr($value) . '" class="regular-text code" ' . $disabled . ' />';
        echo '<p class="description">' . esc_html__('Your Stripe Test Publishable Key (starts with pk_test_). Get it from <a href="https://dashboard.stripe.com/test/apikeys" target="_blank">Stripe Dashboard</a>.', 'xtremecleans') . '</p>';
    }
    
    public function render_stripe_test_secret_key_field() {
        $value = xtremecleans_get_option('stripe_test_secret_key', '');
        $test_mode = xtremecleans_get_option('stripe_test_mode', '1');
        $disabled = $test_mode ? '' : 'disabled';
        echo '<input type="password" name="xtremecleans_stripe_test_secret_key" value="' . esc_attr($value) . '" class="regular-text code" ' . $disabled . ' />';
        echo '<p class="description">' . esc_html__('Your Stripe Test Secret Key (starts with sk_test_). Keep this secure!', 'xtremecleans') . '</p>';
    }
    
    public function render_cache_duration_field() {
        $value = xtremecleans_get_option('cache_duration', '3600');
        echo '<input type="number" name="xtremecleans_cache_duration" value="' . esc_attr($value) . '" class="small-text" min="0" />';
        echo '<p class="description">' . esc_html__('Default cache duration in seconds (0 to disable)', 'xtremecleans') . '</p>';
    }
    
    /**
     * Sanitize and validate Stripe enabled setting
     * Ensures API keys are configured when Stripe is enabled
     *
     * @since 1.0.0
     * @param mixed $value The value to sanitize
     * @return int Sanitized value (0 or 1)
     */
    public function sanitize_stripe_enabled($value) {
        $enabled = absint($value);
        
        // If Stripe is being enabled, validate API keys
        if ($enabled == 1) {
            // Get test mode setting (from POST data if available, otherwise from options)
            $test_mode = isset($_POST['xtremecleans_stripe_test_mode']) ? absint($_POST['xtremecleans_stripe_test_mode']) : xtremecleans_get_option('stripe_test_mode', '1');
            
            if ($test_mode == 1) {
                // Test mode: require test keys
                $test_publishable = isset($_POST['xtremecleans_stripe_test_publishable_key']) ? sanitize_text_field($_POST['xtremecleans_stripe_test_publishable_key']) : xtremecleans_get_option('stripe_test_publishable_key', '');
                $test_secret = isset($_POST['xtremecleans_stripe_test_secret_key']) ? sanitize_text_field($_POST['xtremecleans_stripe_test_secret_key']) : xtremecleans_get_option('stripe_test_secret_key', '');
                
                if (empty($test_publishable) || empty($test_secret)) {
                    add_settings_error(
                        'xtremecleans_stripe_enabled',
                        'stripe_test_keys_missing',
                        __('⚠️ Stripe cannot be enabled: Test API keys are required when test mode is enabled. Please enter your Stripe Test Publishable Key and Test Secret Key.', 'xtremecleans'),
                        'error'
                    );
                    return 0; // Disable Stripe if keys are missing
                }
                
                // Validate key format
                if (!preg_match('/^pk_test_/', $test_publishable)) {
                    add_settings_error(
                        'xtremecleans_stripe_enabled',
                        'stripe_test_publishable_invalid',
                        __('⚠️ Invalid Test Publishable Key format. It should start with "pk_test_".', 'xtremecleans'),
                        'error'
                    );
                    return 0;
                }
                
                if (!preg_match('/^sk_test_/', $test_secret)) {
                    add_settings_error(
                        'xtremecleans_stripe_enabled',
                        'stripe_test_secret_invalid',
                        __('⚠️ Invalid Test Secret Key format. It should start with "sk_test_".', 'xtremecleans'),
                        'error'
                    );
                    return 0;
                }
            } else {
                // Live mode: require live keys
                $live_publishable = isset($_POST['xtremecleans_stripe_publishable_key']) ? sanitize_text_field($_POST['xtremecleans_stripe_publishable_key']) : xtremecleans_get_option('stripe_publishable_key', '');
                $live_secret = isset($_POST['xtremecleans_stripe_secret_key']) ? sanitize_text_field($_POST['xtremecleans_stripe_secret_key']) : xtremecleans_get_option('stripe_secret_key', '');
                
                if (empty($live_publishable) || empty($live_secret)) {
                    add_settings_error(
                        'xtremecleans_stripe_enabled',
                        'stripe_live_keys_missing',
                        __('⚠️ Stripe cannot be enabled: Live API keys are required when test mode is disabled. Please enter your Stripe Live Publishable Key and Live Secret Key.', 'xtremecleans'),
                        'error'
                    );
                    return 0; // Disable Stripe if keys are missing
                }
                
                // Validate key format
                if (!preg_match('/^pk_live_/', $live_publishable)) {
                    add_settings_error(
                        'xtremecleans_stripe_enabled',
                        'stripe_live_publishable_invalid',
                        __('⚠️ Invalid Live Publishable Key format. It should start with "pk_live_".', 'xtremecleans'),
                        'error'
                    );
                    return 0;
                }
                
                if (!preg_match('/^sk_live_/', $live_secret)) {
                    add_settings_error(
                        'xtremecleans_stripe_enabled',
                        'stripe_live_secret_invalid',
                        __('⚠️ Invalid Live Secret Key format. It should start with "sk_live_".', 'xtremecleans'),
                        'error'
                    );
                    return 0;
                }
            }
        }
        
        return $enabled;
    }
    
    public function render_enable_logging_field() {
        $value = xtremecleans_get_option('enable_logging', '0');
        echo '<input type="checkbox" name="xtremecleans_enable_logging" value="1" ' . checked(1, $value, false) . ' />';
        echo '<label>' . esc_html__('Enable API request logging', 'xtremecleans') . '</label>';
    }
    
    public function render_button_default_style_field() {
        $value   = xtremecleans_get_option('button_default_style', 'primary');
        $options = array(
            'primary'   => __('Primary', 'xtremecleans'),
            'secondary' => __('Secondary', 'xtremecleans'),
            'success'   => __('Success', 'xtremecleans'),
        );
        
        echo '<select name="xtremecleans_button_default_style">';
        foreach ($options as $key => $label) {
            echo '<option value="' . esc_attr($key) . '" ' . selected($value, $key, false) . '>' . esc_html($label) . '</option>';
        }
        echo '</select>';
    }
    
    public function render_form_email_recipient_field() {
        $value = xtremecleans_get_option('form_email_recipient', get_option('admin_email'));
        echo '<input type="email" name="xtremecleans_form_email_recipient" value="' . esc_attr($value) . '" class="regular-text" />';
        echo '<p class="description">' . esc_html__('Email address to receive form submissions', 'xtremecleans') . '</p>';
    }
    
    // Email Settings Field Renderers
    public function render_email_from_name_field() {
        $value = xtremecleans_get_option('email_from_name', get_bloginfo('name'));
        echo '<input type="text" name="xtremecleans_email_from_name" value="' . esc_attr($value) . '" class="regular-text" />';
        echo '<p class="description">' . esc_html__('Name that appears in the "From" field of emails.', 'xtremecleans') . '</p>';
    }
    
    public function render_email_from_address_field() {
        $default_email = 'customerservice@xtremecleans.com';
        $value = xtremecleans_get_option('email_from_address', $default_email);
        echo '<input type="email" name="xtremecleans_email_from_address" value="' . esc_attr($value) . '" class="regular-text" />';
        echo '<p class="description">' . esc_html__('Email address that appears in the "From" field.', 'xtremecleans') . '</p>';
    }
    
    public function render_email_admin_notification_field() {
        $default_email = 'customerservice@xtremecleans.com';
        $value = xtremecleans_get_option('email_admin_notification', $default_email);
        echo '<input type="email" name="xtremecleans_email_admin_notification" value="' . esc_attr($value) . '" class="regular-text" />';
        echo '<p class="description">' . esc_html__('Email address to receive lead notifications.', 'xtremecleans') . '</p>';
    }
    
    public function render_email_lead_subject_field() {
        $value = xtremecleans_get_option('email_lead_subject', __('New Lead Submission - {name}', 'xtremecleans'));
        echo '<input type="text" name="xtremecleans_email_lead_subject" value="' . esc_attr($value) . '" class="regular-text" />';
        echo '<p class="description">' . esc_html__('Subject line for lead notification emails sent to admin.', 'xtremecleans') . '</p>';
    }
    
    public function render_email_lead_template_field() {
        $default_template = __("New lead submitted from website:\n\nName: {name}\nEmail: {email}\nPhone: {phone}\nZIP Code: {zip_code}\nZone Name: {zone_name}\n\nSubmitted at: {date}\n\n---\nReply to this email to contact the lead directly.", 'xtremecleans');
        $value = xtremecleans_get_option('email_lead_template', $default_template);
        echo '<textarea name="xtremecleans_email_lead_template" rows="10" class="large-text code">' . esc_textarea($value) . '</textarea>';
        echo '<p class="description">' . esc_html__('Email template for lead notifications. Use placeholders: {name}, {email}, {phone}, {zip_code}, {zone_name}, {date}', 'xtremecleans') . '</p>';
    }
    
    public function render_email_user_subject_field() {
        $value = xtremecleans_get_option('email_user_subject', __('Thank you for contacting {site_name}', 'xtremecleans'));
        echo '<input type="text" name="xtremecleans_email_user_subject" value="' . esc_attr($value) . '" class="regular-text" />';
        echo '<p class="description">' . esc_html__('Subject line for confirmation emails sent to users.', 'xtremecleans') . '</p>';
    }
    
    public function render_email_user_template_field() {
        $default_template = __("Dear {name},\n\nThank you for your interest in our services!\n\nWe have received your information and our team will contact you soon.\n\nBest regards,\n{site_name}", 'xtremecleans');
        $value = xtremecleans_get_option('email_user_template', $default_template);
        echo '<textarea name="xtremecleans_email_user_template" rows="10" class="large-text code">' . esc_textarea($value) . '</textarea>';
        echo '<p class="description">' . esc_html__('Email template for user confirmation emails. Use placeholders: {name}, {email}, {site_name}', 'xtremecleans') . '</p>';
    }
    
    public function render_email_test_field() {
        echo '<button type="button" class="button button-secondary" id="test-email-send">' . esc_html__('Send Test Email', 'xtremecleans') . '</button>';
        echo '<p class="description">' . esc_html__('Send a test email to verify your email settings are working correctly.', 'xtremecleans') . '</p>';
        echo '<div id="test-email-result" style="margin-top: 10px;"></div>';
    }
    
    /**
     * Export Zip Zones to JSON file
     *
     * @since 1.0.0
     */
    public function export_zip_zones() {
        // Check permissions
        if (!current_user_can('manage_options')) {
            wp_die(__('You do not have permission to perform this action.', 'xtremecleans'));
        }
        
        // Verify nonce
        if (!isset($_GET['_wpnonce']) || !wp_verify_nonce($_GET['_wpnonce'], 'xtremecleans_export_zip_zones')) {
            wp_die(__('Security check failed.', 'xtremecleans'));
        }
        
        global $wpdb;
        $table_name = $wpdb->prefix . 'xtremecleans_zip_reference';
        
        // Check if table exists
        $table_exists = $wpdb->get_var($wpdb->prepare("SHOW TABLES LIKE %s", $table_name));
        if (!$table_exists) {
            wp_die(__('No data to export. Table does not exist.', 'xtremecleans'));
        }
        
        // Get all zip zones
        $zip_zones = $wpdb->get_results("SELECT * FROM `{$table_name}` ORDER BY id ASC", ARRAY_A);
        
        if (empty($zip_zones)) {
            wp_die(__('No zip zone data to export.', 'xtremecleans'));
        }
        
        // Prepare export data
        $export_data = array(
            'version' => '1.0',
            'export_date' => current_time('mysql'),
            'site_url' => get_site_url(),
            'zip_zones' => $zip_zones
        );
        
        // Set headers for download
        $filename = 'xtremecleans-zip-zones-export-' . date('Y-m-d-H-i-s') . '.json';
        header('Content-Type: application/json; charset=utf-8');
        header('Content-Disposition: attachment; filename="' . $filename . '"');
        header('Pragma: no-cache');
        header('Expires: 0');
        
        // Output JSON
        echo json_encode($export_data, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE);
        exit;
    }
    
    /**
     * Import Zip Zones from JSON file
     *
     * @since 1.0.0
     */
    public function import_zip_zones() {
        // Check permissions
        if (!current_user_can('manage_options')) {
            wp_die(__('You do not have permission to perform this action.', 'xtremecleans'));
        }
        
        // Verify nonce
        if (!isset($_POST['_wpnonce']) || !wp_verify_nonce($_POST['_wpnonce'], 'xtremecleans_import_zip_zones')) {
            wp_die(__('Security check failed.', 'xtremecleans'));
        }
        
        // Check if file was uploaded
        if (!isset($_FILES['zip_zones_file']) || $_FILES['zip_zones_file']['error'] !== UPLOAD_ERR_OK) {
            wp_redirect(add_query_arg(array('page' => 'xtremecleans-zip-zone', 'import' => 'error'), admin_url('admin.php')));
            exit;
        }
        
        $file = $_FILES['zip_zones_file'];
        
        // Check file type
        $file_ext = strtolower(pathinfo($file['name'], PATHINFO_EXTENSION));
        if ($file_ext !== 'json') {
            wp_redirect(add_query_arg(array('page' => 'xtremecleans-zip-zone', 'import' => 'invalid'), admin_url('admin.php')));
            exit;
        }
        
        // Read file content
        $file_content = file_get_contents($file['tmp_name']);
        $import_data = json_decode($file_content, true);
        
        if (json_last_error() !== JSON_ERROR_NONE || !isset($import_data['zip_zones'])) {
            wp_redirect(add_query_arg(array('page' => 'xtremecleans-zip-zone', 'import' => 'invalid'), admin_url('admin.php')));
            exit;
        }
        
        global $wpdb;
        $table_name = $wpdb->prefix . 'xtremecleans_zip_reference';
        
        // Ensure table exists
        $table_exists = $wpdb->get_var($wpdb->prepare("SHOW TABLES LIKE %s", $table_name));
        if (!$table_exists) {
            // Create table if it doesn't exist
            $this->create_database_table();
        }
        
        $imported = 0;
        $skipped = 0;
        $errors = 0;
        
        // Check if user wants to clear existing data
        $clear_existing = isset($_POST['clear_existing']) && $_POST['clear_existing'] === '1';
        
        if ($clear_existing) {
            $wpdb->query("TRUNCATE TABLE `{$table_name}`");
        }
        
        // Import each zip zone
        foreach ($import_data['zip_zones'] as $zone) {
            // Skip if required fields are missing
            if (empty($zone['zone_name']) || empty($zone['zip_code'])) {
                $skipped++;
                continue;
            }
            
            // Check if zip code already exists (if not clearing)
            if (!$clear_existing) {
                $existing = $wpdb->get_var($wpdb->prepare(
                    "SELECT id FROM `{$table_name}` WHERE zip_code = %s",
                    $zone['zip_code']
                ));
                
                if ($existing) {
                    $skipped++;
                    continue;
                }
            }
            
            // Prepare data for insert
            $insert_data = array(
                'service_name' => isset($zone['service_name']) ? sanitize_text_field($zone['service_name']) : '',
                'zone_name' => sanitize_text_field($zone['zone_name']),
                'zip_code' => sanitize_text_field($zone['zip_code']),
                'zone_area' => isset($zone['zone_area']) ? sanitize_text_field($zone['zone_area']) : '',
                'service_fee' => isset($zone['service_fee']) ? floatval($zone['service_fee']) : 0.00,
                'city' => isset($zone['city']) ? sanitize_text_field($zone['city']) : '',
                'state' => isset($zone['state']) ? sanitize_text_field($zone['state']) : '',
                'suggested_zone' => isset($zone['suggested_zone']) ? sanitize_text_field($zone['suggested_zone']) : sanitize_text_field($zone['zone_name']),
                'created_at' => isset($zone['created_at']) ? $zone['created_at'] : current_time('mysql'),
                'updated_at' => current_time('mysql'),
            );
            
            // Insert into database
            $result = $wpdb->insert(
                $table_name,
                $insert_data,
                array('%s', '%s', '%s', '%s', '%f', '%s', '%s', '%s', '%s', '%s')
            );
            
            if ($result !== false) {
                $imported++;
            } else {
                $errors++;
            }
        }
        
        // Redirect with success message
        $redirect_url = add_query_arg(array(
            'page' => 'xtremecleans-zip-zone',
            'import' => 'success',
            'imported' => $imported,
            'skipped' => $skipped,
            'errors' => $errors
        ), admin_url('admin.php'));
        
        wp_redirect($redirect_url);
        exit;
    }
    
    public function render_custom_css_field() {
        $value = xtremecleans_get_option('custom_css', '');
        echo '<textarea name="xtremecleans_custom_css" rows="10" class="large-text code">' . esc_textarea($value) . '</textarea>';
        echo '<p class="description">' . esc_html__('Add custom CSS to override default styles', 'xtremecleans') . '</p>';
    }
    
    /**
     * AJAX handler to get ZIP code data from database
     *
     * @since 1.0.0
     */
    public function ajax_get_zip_data() {
        // Verify nonce for security
        if (!isset($_POST['nonce']) || !wp_verify_nonce($_POST['nonce'], 'xtremecleans_add_zip')) {
            wp_send_json_error(array('message' => __('Security check failed.', 'xtremecleans')));
        }
        
        // Get and sanitize ZIP code
        $zip_code = isset($_POST['zip_code']) ? sanitize_text_field($_POST['zip_code']) : '';
        
        if (empty($zip_code) || !preg_match('/^[0-9]{5}$/', $zip_code)) {
            wp_send_json_error(array('message' => __('Invalid ZIP code.', 'xtremecleans')));
        }
        
        global $wpdb;
        $table_name = $wpdb->prefix . 'xtremecleans_zip_reference';
        
        // Check if table exists
        $table_exists = $wpdb->get_var($wpdb->prepare("SHOW TABLES LIKE %s", $table_name));
        
        if (!$table_exists) {
            wp_send_json_error(array('message' => __('ZIP code database not available.', 'xtremecleans')));
        }
        
        // Escape table name for safe SQL query
        $table_name_escaped = esc_sql($table_name);
        
        // Get ZIP code data
        $result = $wpdb->get_row(
            $wpdb->prepare(
                "SELECT * FROM `{$table_name_escaped}` WHERE zip_code = %s LIMIT 1",
                $zip_code
            ),
            ARRAY_A
        );
<<<<<<< HEAD
=======
        $service_names = $this->get_service_names_by_zip_code($zip_code);
        $primary_service_name = count($service_names) === 1 ? $service_names[0] : '';
>>>>>>> 3378c4f (plugin last vertation updated in admin dashboard)
        
        if ($result) {
            wp_send_json_success(array(
                'zip_code' => $result['zip_code'],
                'zone_name' => $result['zone_name'],
                'zone_area' => $result['zone_area'] ? $result['zone_area'] : '',
                'service_fee' => isset($result['service_fee']) ? $result['service_fee'] : '0.00',
                'city' => $result['city'] ? $result['city'] : '',
                'state' => $result['state'] ? $result['state'] : '',
<<<<<<< HEAD
                'service_name' => isset($result['service_name']) ? $result['service_name'] : '',
=======
                'service_name' => $primary_service_name,
                'service_names' => $service_names,
>>>>>>> 3378c4f (plugin last vertation updated in admin dashboard)
            ));
        } else {
            wp_send_json_error(array('message' => __('ZIP code not found in database.', 'xtremecleans')));
        }
    }
    
    /**
     * AJAX handler to validate ZIP Code + Zone Name combination
     *
     * @since 1.0.0
     */
    public function ajax_validate_zip_zone() {
        // Verify nonce for security
        if (!isset($_POST['nonce']) || !wp_verify_nonce($_POST['nonce'], 'xtremecleans_add_zip')) {
            wp_send_json_error(array('message' => __('Security check failed.', 'xtremecleans')));
        }
        
        // Get and sanitize ZIP code
        $zip_code = isset($_POST['zip_code']) ? sanitize_text_field($_POST['zip_code']) : '';
        
        if (empty($zip_code) || !preg_match('/^[0-9]{5}$/', $zip_code)) {
            wp_send_json_error(array('message' => __('Invalid ZIP code.', 'xtremecleans')));
        }
        
        global $wpdb;
        $table_name = $wpdb->prefix . 'xtremecleans_zip_reference';
        
        // Check if table exists
        $table_exists = $wpdb->get_var($wpdb->prepare("SHOW TABLES LIKE %s", $table_name));
        
        if (!$table_exists) {
            wp_send_json_error(array('message' => __('ZIP code database not available.', 'xtremecleans')));
        }
        
        // Escape table name for safe SQL query
        $table_name_escaped = esc_sql($table_name);
        
        // Check if ZIP code exists in database
        $result = $wpdb->get_row(
            $wpdb->prepare(
                "SELECT * FROM `{$table_name_escaped}` WHERE zip_code = %s LIMIT 1",
                $zip_code
            ),
            ARRAY_A
        );
<<<<<<< HEAD
=======
        $service_names = $this->get_service_names_by_zip_code($zip_code);
        $primary_service_name = count($service_names) === 1 ? $service_names[0] : '';
>>>>>>> 3378c4f (plugin last vertation updated in admin dashboard)
        
        if ($result) {
            // Match found - proceed to next step
            wp_send_json_success(array(
                'message' => __('ZIP code found.', 'xtremecleans'),
                'zip_code' => $result['zip_code'],
                'zone_name' => isset($result['zone_name']) ? $result['zone_name'] : '',
                'zone_area' => isset($result['zone_area']) && $result['zone_area'] ? $result['zone_area'] : '',
<<<<<<< HEAD
                'service_name' => isset($result['service_name']) ? $result['service_name'] : '',
=======
                'service_name' => $primary_service_name,
                'service_names' => $service_names,
>>>>>>> 3378c4f (plugin last vertation updated in admin dashboard)
            ));
        } else {
            // No match found - show lead form
            wp_send_json_error(array(
                'message' => __('ZIP code not found.', 'xtremecleans'),
                'show_lead_form' => true
            ));
        }
    }
    
    /**
     * AJAX handler to save lead information
     *
     * @since 1.0.0
     */
    public function ajax_save_lead() {
        // Verify nonce for security
        if (!isset($_POST['nonce']) || !wp_verify_nonce($_POST['nonce'], 'xtremecleans_add_zip')) {
            wp_send_json_error(array('message' => __('Security check failed.', 'xtremecleans')));
        }
        
        // Get and sanitize form data
        $name = isset($_POST['name']) ? sanitize_text_field($_POST['name']) : '';
        $email = isset($_POST['email']) ? sanitize_email($_POST['email']) : '';
        $phone = isset($_POST['phone']) ? sanitize_text_field($_POST['phone']) : '';
        $zip_code = isset($_POST['zip_code']) ? sanitize_text_field($_POST['zip_code']) : '';
        $zone_name = isset($_POST['zone_name']) ? sanitize_text_field($_POST['zone_name']) : '';
        
        // Validate required fields
        if (empty($name)) {
            wp_send_json_error(array('message' => __('Name is required.', 'xtremecleans')));
        }
        
        if (empty($email) || !is_email($email)) {
            wp_send_json_error(array('message' => __('Valid email address is required.', 'xtremecleans')));
        }
        
        if (empty($zip_code) || !preg_match('/^[0-9]{5}$/', $zip_code)) {
            wp_send_json_error(array('message' => __('Valid 5-digit ZIP code is required.', 'xtremecleans')));
        }
        
        if (empty($phone)) {
            wp_send_json_error(array('message' => __('Phone number is required.', 'xtremecleans')));
        }
        
        // Create leads table if it doesn't exist
        $this->create_leads_table();
        
        global $wpdb;
        $table_name = $wpdb->prefix . 'xtremecleans_leads';
        
        // Insert lead into database
        $result = $wpdb->insert(
            $table_name,
            array(
                'name' => $name,
                'email' => $email,
                'phone' => $phone,
                'zip_code' => $zip_code,
                'zone_name' => $zone_name,
                'status' => 'new',
                'created_at' => current_time('mysql'),
            ),
            array('%s', '%s', '%s', '%s', '%s', '%s', '%s')
        );
        
        if ($result === false) {
            wp_send_json_error(array('message' => __('Failed to save lead. Please try again.', 'xtremecleans')));
        }
        
        $lead_id = $wpdb->insert_id;
        
        // Prepare lead data for Jobber
        $lead_data = array(
            'customer' => array(
                'first_name' => $name,
                'last_name' => '',
                'email' => $email,
                'phone' => $phone,
                'zip_code' => $zip_code,
            ),
            'source' => 'lead_form',
            'zip_code' => $zip_code,
            'zone_name' => $zone_name,
        );
        
        // Send to Jobber if configured
        $jobber_result = $this->maybe_send_lead_to_jobber($lead_data);
        
        // Send email to user
        $user_email_sent = $this->send_lead_confirmation_email($email, $name);
        
        // Send email to admin
        $admin_email_sent = $this->send_lead_notification_email($name, $email, $phone, $zip_code, $zone_name);
        
        // Log email status
        if (!$user_email_sent) {
            xtremecleans_log('Failed to send confirmation email to user: ' . $email, 'error');
        }
        if (!$admin_email_sent) {
            xtremecleans_log('Failed to send notification email to admin', 'error');
        }
        
        wp_send_json_success(array(
            'message' => __('Thank you! We will contact you soon.', 'xtremecleans'),
            'lead_id' => $lead_id,
            'jobber_sent' => $jobber_result['sent'],
            'user_email_sent' => $user_email_sent,
            'admin_email_sent' => $admin_email_sent,
        ));
    }
    
    /**
     * Send lead data to Jobber API
     *
     * @since 1.0.0
     * @param array $lead_data
     * @return array
     */
    private function maybe_send_lead_to_jobber($lead_data) {
        $auth_url = '';
        if (!function_exists('xtremecleans_is_api_configured') || !xtremecleans_is_api_configured()) {
            return array(
                'sent' => false,
                'message' => __('Jobber API credentials are not configured.', 'xtremecleans'),
                'auth_url' => $auth_url,
            );
        }
        
        if (!class_exists('XtremeCleans_API')) {
            $api_file = XTREMECLEANS_PLUGIN_DIR . 'core/api/class-xtremecleans-api.php';
            if (file_exists($api_file)) {
                require_once $api_file;
            }
        }
        
        if (!class_exists('XtremeCleans_API')) {
            return array(
                'sent' => false,
                'message' => __('API integration is unavailable.', 'xtremecleans'),
                'auth_url' => $auth_url,
            );
        }
        
        $api = new XtremeCleans_API();
        if (!$api->is_configured()) {
            return array(
                'sent' => false,
                'message' => __('Jobber API credentials are missing.', 'xtremecleans'),
                'auth_url' => $auth_url,
            );
        }
        
        // Transform lead data to Jobber format (as a contact/lead)
        $jobber_contact_data = array(
            'name' => $lead_data['customer']['first_name'],
            'email' => $lead_data['customer']['email'],
            'phone' => $lead_data['customer']['phone'],
            'source' => 'Website Lead Form',
            'notes' => sprintf('ZIP Code: %s, Zone: %s', $lead_data['zip_code'], $lead_data['zone_name']),
        );
        
        // Use Jobber Contacts API endpoint
        $endpoint = apply_filters('xtremecleans_jobber_contacts_endpoint', 'contacts');
        $response = $api->post($endpoint, $jobber_contact_data);
        
        if (is_wp_error($response)) {
            xtremecleans_log('Jobber API error (Lead): ' . $response->get_error_message(), 'error');
            return array(
                'sent' => false,
                'message' => $response->get_error_message(),
                'auth_url' => $auth_url,
            );
        }
        
        xtremecleans_log('Lead sent to Jobber API successfully.', 'info');
        
        return array(
            'sent' => true,
            'message' => __('Lead sent to Jobber successfully.', 'xtremecleans'),
            'response' => $response,
            'auth_url' => $auth_url,
        );
    }
    
    /**
     * Send confirmation email to user
     *
     * @since 1.0.0
     * @param string $email
     * @param string $name
     * @return bool
     */
    private function send_lead_confirmation_email($email, $name) {
        if (empty($email) || !is_email($email)) {
            xtremecleans_log('Lead confirmation email: Invalid email address: ' . $email, 'error');
            return false;
        }
        
        $subject_template = xtremecleans_get_option('email_user_subject', __('Thank you for contacting {site_name}', 'xtremecleans'));
        $message_template = xtremecleans_get_option('email_user_template', __("Dear {name},\n\nThank you for your interest in our services!\n\nWe have received your information and our team will contact you soon.\n\nBest regards,\n{site_name}", 'xtremecleans'));
        
        $subject = str_replace(
            array('{name}', '{email}', '{site_name}'),
            array($name, $email, get_bloginfo('name')),
            $subject_template
        );
        
        $message = str_replace(
            array('{name}', '{email}', '{site_name}'),
            array($name, $email, get_bloginfo('name')),
            $message_template
        );
        
        $from_name = xtremecleans_get_option('email_from_name', get_bloginfo('name'));
        $from_address = xtremecleans_get_option('email_from_address', 'customerservice@xtremecleans.com');
        
        // Validate from address
        if (empty($from_address) || !is_email($from_address)) {
            xtremecleans_log('Lead confirmation email: Invalid from address: ' . $from_address, 'error');
            return false;
        }
        
        $headers = array(
            'Content-Type: text/html; charset=UTF-8',
            'From: ' . $from_name . ' <' . $from_address . '>'
        );
        
        // Capture wp_mail errors
        $mail_error = null;
        add_action('wp_mail_failed', function($wp_error) use (&$mail_error) {
            $mail_error = $wp_error;
        });
        
        $result = wp_mail($email, $subject, nl2br($message), $headers);
        
        // Remove error handler
        remove_all_actions('wp_mail_failed');
        
        if ($result) {
            // Always log email success (even if general logging is disabled)
            if (function_exists('error_log')) {
                error_log('[XtremeCleans EMAIL] Lead confirmation email sent successfully to: ' . $email);
            }
            return true;
        } else {
            $error_msg = $mail_error && is_wp_error($mail_error) ? $mail_error->get_error_message() : 'Unknown error';
            // Always log email errors (even if general logging is disabled)
            if (function_exists('error_log')) {
                error_log('[XtremeCleans EMAIL ERROR] Lead confirmation email failed to send to: ' . $email . '. Error: ' . $error_msg);
            }
            xtremecleans_log('Lead confirmation email failed to send to: ' . $email . '. Error: ' . $error_msg, 'error');
            return false;
        }
    }
    
    /**
     * Send notification email to admin
     *
     * @since 1.0.0
     * @param string $name
     * @param string $email
     * @param string $phone
     * @param string $zip_code
     * @param string $zone_name
     * @return bool
     */
    private function send_lead_notification_email($name, $email, $phone, $zip_code, $zone_name) {
        $admin_email = xtremecleans_get_option('email_admin_notification', 'customerservice@xtremecleans.com');
        
        if (empty($admin_email) || !is_email($admin_email)) {
            xtremecleans_log('Lead notification email: Invalid admin email address: ' . $admin_email, 'error');
            return false;
        }
        
        $subject_template = xtremecleans_get_option('email_lead_subject', __('New Lead Submission - {name}', 'xtremecleans'));
        $message_template = xtremecleans_get_option('email_lead_template', __("New lead submitted from website:\n\nName: {name}\nEmail: {email}\nPhone: {phone}\nZIP Code: {zip_code}\nZone Name: {zone_name}\n\nSubmitted at: {date}\n\n---\nReply to this email to contact the lead directly.", 'xtremecleans'));
        
        $subject = str_replace(
            array('{name}', '{email}', '{phone}', '{zip_code}', '{zone_name}', '{date}', '{site_name}'),
            array($name, $email, $phone, $zip_code, $zone_name ? $zone_name : __('N/A', 'xtremecleans'), current_time('mysql'), get_bloginfo('name')),
            $subject_template
        );
        
        $message = str_replace(
            array('{name}', '{email}', '{phone}', '{zip_code}', '{zone_name}', '{date}', '{site_name}'),
            array($name, $email, $phone, $zip_code, $zone_name ? $zone_name : __('N/A', 'xtremecleans'), current_time('mysql'), get_bloginfo('name')),
            $message_template
        );
        
        $from_name = xtremecleans_get_option('email_from_name', get_bloginfo('name'));
        $from_address = xtremecleans_get_option('email_from_address', 'customerservice@xtremecleans.com');
        
        // Validate from address
        if (empty($from_address) || !is_email($from_address)) {
            xtremecleans_log('Lead notification email: Invalid from address: ' . $from_address, 'error');
            return false;
        }
        
        $headers = array(
            'Content-Type: text/html; charset=UTF-8',
            'Reply-To: ' . $name . ' <' . $email . '>',
            'From: ' . $from_name . ' <' . $from_address . '>'
        );
        
        // Capture wp_mail errors
        $mail_error = null;
        add_action('wp_mail_failed', function($wp_error) use (&$mail_error) {
            $mail_error = $wp_error;
        });
        
        $result = wp_mail($admin_email, $subject, nl2br($message), $headers);
        
        // Remove error handler
        remove_all_actions('wp_mail_failed');
        
        if ($result) {
            // Always log email success (even if general logging is disabled)
            if (function_exists('error_log')) {
                error_log('[XtremeCleans EMAIL] Lead notification email sent successfully to admin: ' . $admin_email);
            }
            return true;
        } else {
            $error_msg = $mail_error && is_wp_error($mail_error) ? $mail_error->get_error_message() : 'Unknown error';
            // Always log email errors (even if general logging is disabled)
            if (function_exists('error_log')) {
                error_log('[XtremeCleans EMAIL ERROR] Lead notification email failed to send to admin: ' . $admin_email . '. Error: ' . $error_msg);
            }
            xtremecleans_log('Lead notification email failed to send to admin: ' . $admin_email . '. Error: ' . $error_msg, 'error');
            return false;
        }
    }
    
    /**
     * Check if we're on localhost/development environment
     *
     * @since 1.0.0
     * @return bool
     */
    private function is_localhost_environment() {
        $host = isset($_SERVER['HTTP_HOST']) ? $_SERVER['HTTP_HOST'] : '';
        $server_name = isset($_SERVER['SERVER_NAME']) ? $_SERVER['SERVER_NAME'] : '';
        
        // Check common localhost patterns
        $localhost_patterns = array(
            'localhost',
            '127.0.0.1',
            '::1',
            '.local',
            '.test',
            '.dev',
            'localhost:',
            '127.0.0.1:',
        );
        
        foreach ($localhost_patterns as $pattern) {
            if (strpos($host, $pattern) !== false || strpos($server_name, $pattern) !== false) {
                return true;
            }
        }
        
        // Check if site URL contains localhost
        $site_url = get_site_url();
        foreach ($localhost_patterns as $pattern) {
            if (strpos($site_url, $pattern) !== false) {
                return true;
            }
        }
        
        return false;
    }
    
    /**
     * Check if SMTP plugin is active
     *
     * @since 1.0.0
     * @return bool
     */
    private function is_smtp_plugin_active() {
        // Include plugin.php if not already included (for is_plugin_active function)
        if (!function_exists('is_plugin_active')) {
            require_once ABSPATH . 'wp-admin/includes/plugin.php';
        }
        
        // Check for common SMTP plugins
        $smtp_plugins = array(
            'wp-mail-smtp/wp_mail_smtp.php',
            'easy-wp-smtp/easy-wp-smtp.php',
            'post-smtp/postman-smtp.php',
            'wp-smtp/wp-smtp.php',
            'smtp-mailer/main.php',
        );
        
        foreach ($smtp_plugins as $plugin) {
            if (function_exists('is_plugin_active') && is_plugin_active($plugin)) {
                return true;
            }
        }
        
        // Also check if wp_mail_smtp function exists (WP Mail SMTP plugin)
        if (function_exists('wp_mail_smtp')) {
            return true;
        }
        
        // Check for WP Mail SMTP class
        if (class_exists('WPMailSMTP\Core')) {
            return true;
        }
        
        return false;
    }
    
    /**
     * AJAX handler: Test email
     *
     * @since 1.0.0
     */
    public function ajax_test_email() {
        if (!current_user_can('manage_options')) {
            wp_send_json_error(array('message' => __('Permission denied.', 'xtremecleans')));
        }
        
        if (!isset($_POST['nonce']) || !wp_verify_nonce($_POST['nonce'], 'xtremecleans_email_test')) {
            wp_send_json_error(array('message' => __('Security check failed.', 'xtremecleans')));
        }
        
        $default_test_email = 'customerservice@xtremecleans.com';
        $test_email = isset($_POST['test_email']) ? sanitize_email($_POST['test_email']) : $default_test_email;
        
        if (empty($test_email) || !is_email($test_email)) {
            wp_send_json_error(array('message' => __('Invalid email address.', 'xtremecleans')));
        }
        
        $from_name = xtremecleans_get_option('email_from_name', get_bloginfo('name'));
        $from_address = xtremecleans_get_option('email_from_address', 'customerservice@xtremecleans.com');
        
        // Validate from address
        if (empty($from_address) || !is_email($from_address)) {
            wp_send_json_error(array(
                'message' => __('Invalid "From" email address. Please set a valid email in Email From Address field.', 'xtremecleans')
            ));
        }
        
        $subject = __('Test Email from XtremeCleans', 'xtremecleans');
        $message = __('This is a test email to verify your email settings are working correctly.', 'xtremecleans');
        
        // Build headers
        $headers = array();
        $headers[] = 'Content-Type: text/html; charset=UTF-8';
        $headers[] = 'From: ' . $from_name . ' <' . $from_address . '>';
        
        // Add error handler to capture wp_mail errors
        $mail_error = null;
        add_action('wp_mail_failed', function($wp_error) use (&$mail_error) {
            $mail_error = $wp_error;
        });
        
        // Detect if we're on localhost/development environment
        $is_localhost = $this->is_localhost_environment();
        
        // Check if SMTP plugin is active
        $smtp_plugin_active = $this->is_smtp_plugin_active();
        
        // Try to send email
        $result = wp_mail($test_email, $subject, nl2br($message), $headers);
        
        // Remove error handler
        remove_all_actions('wp_mail_failed');
        
        if ($result) {
            // Even if wp_mail returns true, warn on localhost if SMTP is not configured
            if ($is_localhost && !$smtp_plugin_active) {
                $warning_message = '<div style="background: #fff3cd; border-left: 4px solid #ffc107; padding: 15px; margin: 10px 0; border-radius: 4px;">';
                $warning_message .= '<strong>⚠ ' . __('Important Notice:', 'xtremecleans') . '</strong><br><br>';
                $warning_message .= __('WordPress reports the email was sent, but you are on a localhost/development environment.', 'xtremecleans') . '<br><br>';
                $warning_message .= '<strong>' . __('Why emails may not arrive:', 'xtremecleans') . '</strong><br>';
                $warning_message .= '• ' . __('Localhost servers (WAMP/XAMPP) cannot send real emails without SMTP configuration', 'xtremecleans') . '<br>';
                $warning_message .= '• ' . __('The email may be queued but not actually delivered', 'xtremecleans') . '<br>';
                $warning_message .= '• ' . __('Check your spam folder, but emails likely won\'t arrive without SMTP', 'xtremecleans') . '<br><br>';
                $warning_message .= '<strong>' . __('Solution: Install WP Mail SMTP Plugin', 'xtremecleans') . '</strong><br>';
                $warning_message .= '1. ' . __('Go to Plugins → Add New', 'xtremecleans') . '<br>';
                $warning_message .= '2. ' . __('Search for "WP Mail SMTP" and install it', 'xtremecleans') . '<br>';
                $warning_message .= '3. ' . __('Configure it with Gmail, Mailgun, SendGrid, or your SMTP server', 'xtremecleans') . '<br>';
                $warning_message .= '4. ' . __('After configuration, emails will work properly', 'xtremecleans') . '<br><br>';
                $warning_message .= '<strong>' . __('Note:', 'xtremecleans') . '</strong> ' . __('On production server (live website), emails will work normally.', 'xtremecleans');
                $warning_message .= '</div>';
                
                wp_send_json_success(array(
                    'message' => __('Test email sent successfully to: ', 'xtremecleans') . $test_email . '<br><br>' . $warning_message
                ));
            } else {
                wp_send_json_success(array('message' => __('Test email sent successfully to: ', 'xtremecleans') . $test_email));
            }
        } else {
            $error_message = __('Failed to send test email.', 'xtremecleans');
            $is_mail_error = false;
            
            if ($mail_error && is_wp_error($mail_error)) {
                $error_msg = $mail_error->get_error_message();
                $error_message .= ' ' . __('Error: ', 'xtremecleans') . $error_msg;
                
                // Check if it's a "Could not instantiate mail function" error
                if (strpos(strtolower($error_msg), 'could not instantiate mail') !== false || 
                    strpos(strtolower($error_msg), 'mail function') !== false) {
                    $is_mail_error = true;
                }
            } else {
                // Check if PHP mail function exists
                if (!function_exists('mail')) {
                    $is_mail_error = true;
                }
            }
            
            // Add helpful solution for mail function errors
            if ($is_mail_error) {
                $error_message .= '<br><br>';
                $error_message .= '<div style="background: #fff3cd; border-left: 4px solid #ffc107; padding: 12px; margin: 10px 0;">';
                $error_message .= '<strong>' . __('Solution for Localhost/Development:', 'xtremecleans') . '</strong><br>';
                $error_message .= __('PHP mail() function is not configured on your local server. To fix this:', 'xtremecleans') . '<br><br>';
                $error_message .= '<strong>' . __('Option 1: Install WP Mail SMTP Plugin (Recommended)', 'xtremecleans') . '</strong><br>';
                $error_message .= '1. ' . __('Install "WP Mail SMTP" plugin from WordPress repository', 'xtremecleans') . '<br>';
                $error_message .= '2. ' . __('Configure it with Gmail, SMTP, or other email service', 'xtremecleans') . '<br>';
                $error_message .= '3. ' . __('Test email will work after configuration', 'xtremecleans') . '<br><br>';
                $error_message .= '<strong>' . __('Option 2: Configure WAMP Mail (Advanced)', 'xtremecleans') . '</strong><br>';
                $error_message .= '1. ' . __('Edit php.ini and configure sendmail_path', 'xtremecleans') . '<br>';
                $error_message .= '2. ' . __('Or use a mail server like Mercury Mail or similar', 'xtremecleans') . '<br><br>';
                $error_message .= '<strong>' . __('Note:', 'xtremecleans') . '</strong> ' . __('On production server, emails will work normally. This is only a localhost limitation.', 'xtremecleans');
                $error_message .= '</div>';
                
                // Show email preview
                $error_message .= '<div style="background: #f0f6fc; border-left: 4px solid #4caf50; padding: 12px; margin: 10px 0;">';
                $error_message .= '<strong>' . __('Email Preview (What would be sent):', 'xtremecleans') . '</strong><br>';
                $error_message .= '<strong>To:</strong> ' . esc_html($test_email) . '<br>';
                $error_message .= '<strong>From:</strong> ' . esc_html($from_name) . ' &lt;' . esc_html($from_address) . '&gt;<br>';
                $error_message .= '<strong>Subject:</strong> ' . esc_html($subject) . '<br>';
                $error_message .= '<strong>Message:</strong> ' . esc_html($message);
                $error_message .= '</div>';
            } else {
                // Other errors
                $diagnostics = array();
                
                // Check if from address is valid
                if (!is_email($from_address)) {
                    $diagnostics[] = __('Invalid "From" email address configured.', 'xtremecleans');
                }
                
                // Check if SMTP might be needed
                $diagnostics[] = __('Note: WordPress uses PHP mail() by default. For better reliability, consider using an SMTP plugin like WP Mail SMTP.', 'xtremecleans');
                
                if (!empty($diagnostics)) {
                    $error_message .= '<br><br><strong>' . __('Diagnostics:', 'xtremecleans') . '</strong><ul style="margin-left: 20px;">';
                    foreach ($diagnostics as $diag) {
                        $error_message .= '<li>' . $diag . '</li>';
                    }
                    $error_message .= '</ul>';
                }
            }
            
            wp_send_json_error(array('message' => $error_message));
        }
    }
    
    /**
     * AJAX handler: Place multi-step order and optionally send to Jobber
     *
     * @since 1.0.0
     */
    public function ajax_place_order() {
        $nonce = isset($_POST['nonce']) ? sanitize_text_field(wp_unslash($_POST['nonce'])) : '';
        if (empty($nonce) || !wp_verify_nonce($nonce, 'xtremecleans_place_order')) {
            wp_send_json_error(array('message' => __('Security check failed.', 'xtremecleans')));
        }
        
        $raw_order = isset($_POST['order']) ? wp_unslash($_POST['order']) : '';
        if (empty($raw_order)) {
            wp_send_json_error(array('message' => __('Invalid order payload.', 'xtremecleans')));
        }
        
        $order_data = json_decode($raw_order, true);
        if (json_last_error() !== JSON_ERROR_NONE || !is_array($order_data)) {
            wp_send_json_error(array('message' => __('Unable to parse order data.', 'xtremecleans')));
        }
        
        $customer = isset($order_data['customer']) && is_array($order_data['customer']) ? $order_data['customer'] : array();
        $services = isset($order_data['services']) && is_array($order_data['services']) ? $order_data['services'] : array();
        $appointment = isset($order_data['appointment']) && is_array($order_data['appointment']) ? $order_data['appointment'] : array();
        $totals = isset($order_data['totals']) && is_array($order_data['totals']) ? $order_data['totals'] : array();
        $zone = isset($order_data['zone']) && is_array($order_data['zone']) ? $order_data['zone'] : array();
        $grouped_services = isset($order_data['services_grouped']) ? $order_data['services_grouped'] : array();
        
        $first_name = isset($customer['first_name']) ? sanitize_text_field($customer['first_name']) : '';
        $last_name  = isset($customer['last_name']) ? sanitize_text_field($customer['last_name']) : '';
        $email      = isset($customer['email']) ? sanitize_email($customer['email']) : '';
        $phone      = isset($customer['phone']) ? sanitize_text_field($customer['phone']) : '';
        $alt_phone  = isset($customer['alt_phone']) ? sanitize_text_field($customer['alt_phone']) : '';
        $address1   = isset($customer['address1']) ? sanitize_text_field($customer['address1']) : '';
        $address2   = isset($customer['address2']) ? sanitize_text_field($customer['address2']) : '';
        $city       = isset($customer['city']) ? sanitize_text_field($customer['city']) : '';
        $state      = isset($customer['state']) ? sanitize_text_field($customer['state']) : '';
        $zip_code   = isset($customer['zip_code']) ? sanitize_text_field($customer['zip_code']) : '';
        $instructions = isset($customer['instructions']) ? sanitize_textarea_field($customer['instructions']) : '';
        
        if (empty($first_name) || empty($last_name)) {
            wp_send_json_error(array('message' => __('First and last name are required.', 'xtremecleans')));
        }
        
        if (empty($email) || !is_email($email)) {
            wp_send_json_error(array('message' => __('Valid email is required.', 'xtremecleans')));
        }
        
        if (empty($phone)) {
            wp_send_json_error(array('message' => __('Phone number is required.', 'xtremecleans')));
        }
        
        if (empty($address1) || empty($city) || empty($state) || empty($zip_code)) {
            wp_send_json_error(array('message' => __('Complete address information is required.', 'xtremecleans')));
        }
        
        if (empty($services)) {
            wp_send_json_error(array('message' => __('Please select at least one service.', 'xtremecleans')));
        }
        
        $appointment_date = isset($appointment['date']) ? sanitize_text_field($appointment['date']) : '';
        $appointment_time = isset($appointment['time']) ? sanitize_text_field($appointment['time']) : '';
        $appointment_day  = isset($appointment['day_name']) ? sanitize_text_field($appointment['day_name']) : '';
        
        if (empty($appointment_date) || empty($appointment_time)) {
            wp_send_json_error(array('message' => __('Please choose an appointment slot.', 'xtremecleans')));
        }
        
        $service_total = isset($totals['services']) ? floatval($totals['services']) : 0;
        $service_fee   = isset($totals['service_fee']) ? floatval($totals['service_fee']) : 0;
        // Deposit is always $20.00, regardless of order total
        $deposit       = 20.00;
        $grand_total   = isset($totals['grand_total']) ? floatval($totals['grand_total']) : ($service_total + $service_fee);
        
        $this->create_orders_table();
        
        global $wpdb;
        $table_name = $wpdb->prefix . 'xtremecleans_orders';
        
<<<<<<< HEAD
        // One booking per slot: reject if this date+time is already booked
        $existing = $wpdb->get_var($wpdb->prepare(
            "SELECT id FROM {$table_name} WHERE appointment_date = %s AND appointment_time = %s LIMIT 1",
            $appointment_date,
            $appointment_time
        ));
        if ($existing) {
            wp_send_json_error(array('message' => __('This time slot is no longer available. Please choose another.', 'xtremecleans')));
        }
        
=======
        // Capacity check per slot (1 when disabled; configurable when enabled).
        $slot_capacity_enabled = absint(get_option('xtremecleans_slot_capacity_enabled', 0)) === 1;
        $slot_capacity = $slot_capacity_enabled ? absint(get_option('xtremecleans_slot_capacity', 1)) : 1;
        if ($slot_capacity < 1) {
            $slot_capacity = 1;
        }
        $existing_count = $this->get_slot_occupancy_count($appointment_date, $appointment_time);
        if ($existing_count >= $slot_capacity) {
            wp_send_json_error(array('message' => __('This time slot is no longer available. Please choose another.', 'xtremecleans')));
        }
        
        // Travel time rule: crew must reach next job in ≤ 60 min (Google Distance Matrix, duration_in_traffic)
        $duration_minutes = isset($totals['duration_minutes']) ? intval($totals['duration_minutes']) : 0;
        $travel_check = $this->validate_travel_time_for_order($customer, $appointment_date, $appointment_time, $duration_minutes);
        if (is_wp_error($travel_check)) {
            wp_send_json_error(array('message' => $travel_check->get_error_message()));
        }
        
>>>>>>> 3378c4f (plugin last vertation updated in admin dashboard)
        $insert_data = array(
            'first_name'        => $first_name,
            'last_name'         => $last_name,
            'email'             => $email,
            'phone'             => $phone,
            'alt_phone'         => $alt_phone,
            'address1'          => $address1,
            'address2'          => $address2,
            'city'              => $city,
            'state'             => $state,
            'zip_code'          => $zip_code,
            'instructions'      => $instructions,
            'appointment_date'  => $appointment_date,
            'appointment_time'  => $appointment_time,
            'appointment_day'   => $appointment_day,
            'services_json'     => wp_json_encode($services),
            'services_grouped'  => wp_json_encode($grouped_services),
            'total_amount'      => $grand_total,
            'service_fee'       => $service_fee,
            'deposit_amount'    => $deposit,
            'zone_data'         => wp_json_encode($zone),
            'payload'           => wp_json_encode($order_data),
            'created_at'        => current_time('mysql'),
            'updated_at'        => current_time('mysql'),
        );
        
        $formats = array(
            '%s','%s','%s','%s','%s','%s','%s','%s','%s','%s','%s',
            '%s','%s','%s','%s','%s','%f','%f','%f','%s','%s','%s','%s'
        );
        
        // Check if Stripe is enabled and configured
        $stripe_enabled = false;
        if (file_exists(XTREMECLEANS_PLUGIN_DIR . 'core/payment/class-xtremecleans-stripe.php')) {
            require_once XTREMECLEANS_PLUGIN_DIR . 'core/payment/class-xtremecleans-stripe.php';
            $stripe_enabled = XtremeCleans_Stripe::is_enabled() && XtremeCleans_Stripe::is_configured();
        }
        
        // Set payment status
        $insert_data['payment_status'] = $stripe_enabled ? 'pending' : 'not_required';
        
        $inserted = $wpdb->insert($table_name, $insert_data, $formats);
        
        if ($inserted === false) {
            wp_send_json_error(array('message' => __('Failed to save order. Please try again.', 'xtremecleans')));
        }
        
        $order_id = $wpdb->insert_id;
        
<<<<<<< HEAD
=======
        // Re-check after insert (race: another customer may have taken the last seat).
        $occupancy_after = $this->get_slot_occupancy_count($appointment_date, $appointment_time);
        if ($occupancy_after > $slot_capacity) {
            $wpdb->delete($table_name, array('id' => $order_id), array('%d'));
            wp_send_json_error(array('message' => __('This time slot filled up while you were booking. Please choose another.', 'xtremecleans')));
        }
        
>>>>>>> 3378c4f (plugin last vertation updated in admin dashboard)
        // IMPORTANT: Payment Flow Logic
        // If Stripe is enabled and configured:
        // 1. Order is saved with payment_status = 'pending'
        // 2. Payment modal is shown to customer
        // 3. After successful payment confirmation, Jobber sync happens (in ajax_confirm_payment)
        // This ensures payment is REQUIRED before sending to Jobber CRM
        if ($stripe_enabled) {
            $response_data = array(
                'message' => __('Order saved. Please complete payment to confirm your appointment.', 'xtremecleans'),
                'order_id' => $order_id,
                'requires_payment' => true,
                'deposit_amount' => $deposit,
            );
            wp_send_json_success($response_data);
        }
        
        // If Stripe is NOT enabled:
        // Order is saved and Jobber sync happens immediately (no payment required)
        // This allows clients to use the plugin without Stripe if they prefer
        $jobber_result = $this->maybe_send_order_to_api($order_data);
        
        // Update order with Jobber sync status
        $this->update_order_jobber_status($order_id, $jobber_result);
        
        $response_data = array(
            'message' => __('Order received! We will confirm shortly.', 'xtremecleans'),
            'order_id' => $order_id,
            'jobber_sent' => $jobber_result['sent'],
            'jobber_message' => $jobber_result['message'],
            'requires_payment' => false,
        );
        
        if (!empty($jobber_result['auth_url'])) {
            $response_data['jobber_auth_url'] = $jobber_result['auth_url'];
        }
        
        // Log Jobber sync result for debugging
        if (!$jobber_result['sent']) {
            xtremecleans_log('Jobber sync failed for order #' . $order_id . ': ' . $jobber_result['message'], 'error');
        } else {
            xtremecleans_log('Jobber sync successful for order #' . $order_id, 'info');
        }
        
        wp_send_json_success($response_data);
    }
    
    /**
<<<<<<< HEAD
     * AJAX: Get booked appointment slots for a date range (one slot = one booking).
     * Used by frontend calendar to mark already-booked slots as unavailable.
=======
     * Get the business timezone for Jobber time conversions.
     * Priority: 1) Plugin setting, 2) Infer from Jobber job data, 3) WordPress timezone.
     *
     * Jobber returns all times in UTC. If WordPress timezone is UTC/+00:00 (often misconfigured),
     * we auto-detect the real business timezone by analyzing full-day job patterns
     * (e.g., startAt 05:00Z + endAt 04:59:59Z next day = midnight-to-midnight at UTC-5 = Eastern Time).
     *
     * @since 1.1.0
     * @param array|null $jobs Optional pre-fetched jobs array to analyze
     * @return DateTimeZone The business timezone
     */
    private function get_business_timezone($jobs = null) {
        // 1) Check plugin setting override
        $saved_tz = get_option('xtremecleans_business_timezone', '');
        if (!empty($saved_tz)) {
            try {
                return new DateTimeZone($saved_tz);
            } catch (Exception $e) {
                // Invalid saved timezone, continue to auto-detect
            }
        }
        
        // 2) Check if WordPress timezone is properly configured (not UTC)
        $wp_tz = wp_timezone();
        $wp_tz_name = $wp_tz->getName();
        
        // If WP timezone is set to a real timezone (not UTC/+00:00), use it
        if ($wp_tz_name !== 'UTC' && $wp_tz_name !== '+00:00' && $wp_tz_name !== 'Etc/UTC') {
            return $wp_tz;
        }
        
        // 3) Check cached auto-detected timezone
        $cached_tz = get_transient('xtremecleans_detected_timezone');
        if ($cached_tz !== false) {
            try {
                return new DateTimeZone($cached_tz);
            } catch (Exception $e) {
                // Fall through
            }
        }
        
        // 4) Auto-detect from Jobber job data
        // Full-day jobs in Jobber have startAt at midnight local time and endAt at 23:59:59 local time.
        // So startAt hour in UTC tells us the UTC offset: e.g., 05:00Z = midnight at UTC-5 (Eastern)
        if (!empty($jobs)) {
            $offsets = array();
            foreach ($jobs as $job) {
                if (empty($job['startAt']) || empty($job['endAt'])) {
                    continue;
                }
                try {
                    $start = new DateTime($job['startAt']);
                    $end = new DateTime($job['endAt']);
                } catch (Exception $e) {
                    continue;
                }
                
                // Check if this is a full-day job (duration ≈ 24 hours / 23:59:59)
                $diff_seconds = $end->getTimestamp() - $start->getTimestamp();
                if ($diff_seconds >= 86399 && $diff_seconds <= 86400) { // 23:59:59 to 24:00:00
                    $utc_start_hour = (int) $start->format('H');
                    $utc_start_min = (int) $start->format('i');
                    
                    // Only trust whole-hour or half-hour offsets
                    if ($utc_start_min === 0 || $utc_start_min === 30) {
                        $offset_hours = -($utc_start_hour + ($utc_start_min / 60));
                        // Adjust for negative offsets that wrap (e.g., hour 23 = UTC+1)
                        if ($offset_hours < -12) {
                            $offset_hours += 24;
                        }
                        $offsets[] = $offset_hours;
                    }
                }
            }
            
            if (!empty($offsets)) {
                // Use the most common offset
                $counts = array_count_values(array_map(function($o) { return (string) $o; }, $offsets));
                arsort($counts);
                $best_offset = (float) array_key_first($counts);
                
                // Convert offset to timezone string
                $offset_sign = $best_offset >= 0 ? '+' : '-';
                $abs_offset = abs($best_offset);
                $offset_h = (int) floor($abs_offset);
                $offset_m = (int) (($abs_offset - $offset_h) * 60);
                $tz_string = sprintf('%s%02d:%02d', $offset_sign, $offset_h, $offset_m);
                
                xtremecleans_log('Auto-detected business timezone from Jobber: UTC' . $tz_string . ' (from ' . count($offsets) . ' full-day jobs)', 'info');
                
                // Cache for 24 hours
                set_transient('xtremecleans_detected_timezone', $tz_string, DAY_IN_SECONDS);
                
                // Also save as plugin setting so it persists
                update_option('xtremecleans_business_timezone', $tz_string);
                
                try {
                    return new DateTimeZone($tz_string);
                } catch (Exception $e) {
                    // Fall through
                }
            }
        }
        
        // 5) Final fallback: WordPress timezone (even if UTC)
        return $wp_tz;
    }
    
    /**
     * Ensure the Jobber access token is fresh. Refreshes if expired or about to expire.
     *
     * @since 1.1.0
     * @return string The current (possibly refreshed) access token, or empty string on failure
     */
    private function ensure_fresh_jobber_token() {
        // Per-request cache: avoid refreshing multiple times in one page load
        static $cached_token = null;
        if ($cached_token !== null) {
            return $cached_token;
        }
        
        $access_token = get_option('xtremecleans_jobber_access_token', '');
        if (empty($access_token)) {
            $cached_token = '';
            return '';
        }
        
        $token_expires = (int) get_option('xtremecleans_jobber_token_expires', 0);
        
        // Refresh if: expiry unknown (0/not set), already expired, or expiring within 5 minutes
        // When expiry is 0, we don't know when it expires — refresh to get a proper expiry set
        $needs_refresh = ($token_expires === 0) || ($token_expires < time() + 300);
        
        if ($needs_refresh) {
            xtremecleans_log('Jobber token needs refresh (expires=' . $token_expires . ', now=' . time() . ')', 'info');
            
            $xtremecleans = XtremeCleans::get_instance();
            $frontend = isset($xtremecleans->frontend) ? $xtremecleans->frontend : null;
            
            if ($frontend && method_exists($frontend, 'refresh_access_token')) {
                $refresh_result = $frontend->refresh_access_token();
                if (is_wp_error($refresh_result)) {
                    xtremecleans_log('Jobber token refresh failed: ' . $refresh_result->get_error_message(), 'error');
                    // Return current token anyway — it might still work
                    $cached_token = $access_token;
                    return $access_token;
                } else {
                    xtremecleans_log('Jobber token refreshed successfully', 'info');
                    // Re-read refreshed token from DB
                    $access_token = get_option('xtremecleans_jobber_access_token', '');
                }
            }
        }
        
        $cached_token = $access_token;
        return $access_token;
    }
    
    /**
     * Fetch appointments from Jobber for a date range
     * Maps Jobber job times to plugin's 3-window slots (8-9 AM, 11-2 PM, 2:30-5 PM)
     *
     * @since 1.1.0
     * @param string $start_date Date in YYYY-MM-DD format
     * @param string $end_date Date in YYYY-MM-DD format
     * @param array  $time_windows Array of time windows to map jobs to (if empty, uses defaults from get_jobber_availability)
     * @return array Booked slots array with 'date' and 'time' keys
     */
    private function get_jobber_appointments_for_date_range($start_date, $end_date, $time_windows = array()) {
        $booked_slots = array();
        
        // Check if Jobber is configured
        if (!xtremecleans_is_api_configured()) {
            xtremecleans_log('Jobber not configured, skipping Jobber appointments fetch', 'debug');
            return $booked_slots;
        }
        
        // Get fresh token (auto-refresh if expired)
        $access_token = $this->ensure_fresh_jobber_token();
        if (empty($access_token)) {
            xtremecleans_log('No Jobber access token found', 'debug');
            return $booked_slots;
        }
        
        // If no time windows provided, use default fallback
        if (empty($time_windows)) {
            $time_windows = array(
                array('start' => 8, 'end' => 9, 'label' => '8:00 AM - 9:00 AM'),
                array('start' => 11, 'end' => 14, 'label' => '11:00 AM - 2:00 PM'),
                array('start' => 14.5, 'end' => 17, 'label' => '2:30 PM - 5:00 PM'),
            );
        }
        
        $graphql_endpoint = 'https://api.getjobber.com/api/graphql';
        $api_version = apply_filters('xtremecleans_jobber_api_version', '2025-04-16');
        
        // Convert dates to ISO 8601 format
        $start_datetime = $start_date . 'T00:00:00Z';
        $end_datetime = $end_date . 'T23:59:59Z';
        
        // Jobber GraphQL query — fetch jobs + their visits (visits = actual scheduled appointments)
        // Limit to 100 jobs and 20 visits per job to stay within Jobber's 10,000 query cost budget
        $query = '{
            jobs(first: 100) {
                nodes {
                    id
                    title
                    jobStatus
                    startAt
                    endAt
                    visits(first: 20) {
                        nodes {
                            startAt
                            endAt
                        }
                    }
                }
            }
        }';
        
        $response = wp_remote_post($graphql_endpoint, array(
            'timeout' => 30,
            'headers' => array(
                'Authorization' => 'Bearer ' . $access_token,
                'Content-Type' => 'application/json',
                'Accept' => 'application/json',
                'X-JOBBER-GRAPHQL-VERSION' => $api_version,
            ),
            'body' => wp_json_encode(array('query' => $query)),
        ));
        
        if (is_wp_error($response)) {
            xtremecleans_log('Jobber jobs fetch error: ' . $response->get_error_message(), 'error');
            return $booked_slots;
        }
        
        $status = wp_remote_retrieve_response_code($response);
        $body = wp_remote_retrieve_body($response);
        $data = json_decode($body, true);
        
        // If 401 (expired token), try refreshing and retrying once
        if ($status === 401) {
            xtremecleans_log('Jobber 401 on booked slots fetch, attempting token refresh and retry...', 'info');
            $refreshed_token = $this->ensure_fresh_jobber_token();
            if (!empty($refreshed_token) && $refreshed_token !== $access_token) {
                $response = wp_remote_post($graphql_endpoint, array(
                    'timeout' => 30,
                    'headers' => array(
                        'Authorization' => 'Bearer ' . $refreshed_token,
                        'Content-Type' => 'application/json',
                        'Accept' => 'application/json',
                        'X-JOBBER-GRAPHQL-VERSION' => $api_version,
                    ),
                    'body' => wp_json_encode(array('query' => $query)),
                ));
                if (!is_wp_error($response)) {
                    $status = wp_remote_retrieve_response_code($response);
                    $body = wp_remote_retrieve_body($response);
                    $data = json_decode($body, true);
                }
            }
        }
        
        if ($status < 200 || $status >= 300) {
            xtremecleans_log('Jobber jobs API returned status ' . $status . ': ' . substr($body, 0, 300), 'error');
            return $booked_slots;
        }
        
        if (isset($data['errors'])) {
            $error_msgs = array();
            foreach ($data['errors'] as $error) {
                if (isset($error['message'])) {
                    $error_msgs[] = $error['message'];
                }
            }
            xtremecleans_log('Jobber jobs GraphQL errors: ' . implode(', ', $error_msgs), 'error');
            // If there are also data, continue processing; otherwise return
            if (!isset($data['data']['jobs']['nodes'])) {
                return $booked_slots;
            }
        }
        
        if (!isset($data['data']['jobs']['nodes'])) {
            xtremecleans_log('Jobber jobs: unexpected response format', 'warning');
            return $booked_slots;
        }
        
        $jobs = $data['data']['jobs']['nodes'];
        
        if (empty($jobs)) {
            xtremecleans_log('No Jobber jobs found', 'debug');
            return $booked_slots;
        }
        
        xtremecleans_log('Found ' . count($jobs) . ' Jobber jobs total, filtering for date range ' . $start_date . ' to ' . $end_date, 'info');
        
        // Process each Jobber job — filter by date range client-side
        foreach ($jobs as $job) {
            // Skip jobs without scheduling data
            if (empty($job['startAt'])) {
                continue;
            }
            
            // Only include active jobs (not completed/cancelled)
            $job_status = isset($job['jobStatus']) ? $job['jobStatus'] : '';
            if (in_array($job_status, array('COMPLETED', 'CANCELLED', 'CLOSED'), true)) {
                continue;
            }
            
            $job_title = isset($job['title']) ? $job['title'] : '';
            
            // First, check if job has visits (visits = actual scheduled appointments with times)
            $has_visits = !empty($job['visits']['nodes']) && is_array($job['visits']['nodes']);
            
            if ($has_visits) {
                // Process each visit — visits have the actual scheduled times
                foreach ($job['visits']['nodes'] as $visit) {
                    if (empty($visit['startAt'])) {
                        continue;
                    }
                    $this->map_time_block_to_slots(
                        $visit['startAt'],
                        isset($visit['endAt']) ? $visit['endAt'] : null,
                        $job_title,
                        $start_date,
                        $end_date,
                        $time_windows,
                        $booked_slots
                    );
                }
            } else {
                // No visits — use job-level startAt/endAt
                $this->map_time_block_to_slots(
                    $job['startAt'],
                    isset($job['endAt']) ? $job['endAt'] : null,
                    $job_title,
                    $start_date,
                    $end_date,
                    $time_windows,
                    $booked_slots
                );
            }
        }
        
        return $booked_slots;
    }
    
    /**
     * Map a single time block (startAt/endAt) to booking windows.
     * If the time is midnight (00:00), it means date-only — block ALL windows for that day.
     *
     * @since 1.1.0
     * @param string      $start_at    ISO 8601 datetime string
     * @param string|null $end_at      ISO 8601 datetime string or null
     * @param string      $title       Job/visit title for logging
     * @param string      $range_start Date range start (Y-m-d)
     * @param string      $range_end   Date range end (Y-m-d)
     * @param array       $time_windows Available time windows
     * @param array       &$booked_slots Reference to booked slots array
     */
    private function map_time_block_to_slots($start_at, $end_at, $title, $range_start, $range_end, $time_windows, &$booked_slots) {
        // Get business timezone for converting UTC → local time
        $biz_tz = $this->get_business_timezone();
        
        try {
            $start_dt = new DateTime($start_at);
            $start_dt->setTimezone($biz_tz); // Convert UTC → business local
            $end_dt = !empty($end_at) ? new DateTime($end_at) : null;
            if ($end_dt) {
                $end_dt->setTimezone($biz_tz); // Convert UTC → business local
            }
        } catch (Exception $e) {
            xtremecleans_log('Failed to parse Jobber datetime: ' . $e->getMessage(), 'warning');
            return;
        }
        
        $job_date = $start_dt->format('Y-m-d'); // Now in local timezone
        
        // Filter by date range
        if ($job_date < $range_start || $job_date > $range_end) {
            return;
        }
        
        $job_start_hour = (float) $start_dt->format('H') + ((float) $start_dt->format('i') / 60);
        
        // Check if this is a date-only entry (local time is midnight 00:00)
        // Jobber date-only jobs come as midnight local time (e.g. 2026-03-02T05:00:00Z = midnight EST)
        $is_date_only = ($start_dt->format('H:i:s') === '00:00:00');
        
        // Also check if endAt is 23:59:59 local (another indicator of date-only/all-day)
        if (!$is_date_only && $end_dt) {
            $end_time_str = $end_dt->format('H:i:s');
            $end_date_str = $end_dt->format('Y-m-d');
            if ($end_time_str === '23:59:59' && $end_date_str === $job_date) {
                $is_date_only = true;
            }
        }
        
        if ($is_date_only) {
            // Date-only job — block ALL windows for this day
            foreach ($time_windows as $window) {
                $booked_slots[] = array(
                    'date' => $job_date,
                    'time' => $window['label'],
                    'source' => 'jobber',
                    'job_title' => $title,
                    'reason' => 'date-only (all-day)',
                );
            }
            xtremecleans_log('Jobber job "' . $title . '" (date-only) blocks ALL slots on: ' . $job_date, 'debug');
            return;
        }
        
        // Has specific time — calculate end hour (already in local timezone)
        if ($end_dt) {
            $job_end_hour = (float) $end_dt->format('H') + ((float) $end_dt->format('i') / 60);
            // If end is midnight next day, treat as end-of-day
            if ($job_end_hour == 0) {
                $job_end_hour = 24;
            }
        } else {
            $job_end_hour = $job_start_hour + 2; // Assume 2-hour duration
        }
        
        xtremecleans_log('Jobber job "' . $title . '" local time: ' . $start_dt->format('Y-m-d H:i') . ' - ' . ($end_dt ? $end_dt->format('H:i') : 'n/a') . ' (hours: ' . $job_start_hour . '-' . $job_end_hour . ')', 'debug');
        
        // Map job to time windows — block any window that overlaps
        foreach ($time_windows as $window) {
            if ($job_start_hour < $window['end'] && $job_end_hour > $window['start']) {
                $booked_slots[] = array(
                    'date' => $job_date,
                    'time' => $window['label'],
                    'source' => 'jobber',
                    'job_title' => $title,
                    'reason' => 'time-overlap (' . $job_start_hour . '-' . $job_end_hour . 'h)',
                );
                xtremecleans_log('Jobber job "' . $title . '" blocks slot: ' . $job_date . ' ' . $window['label'], 'debug');
            }
        }
    }
    
    /**
     * Fetch dynamic availability/schedule data from Jobber.
     * Returns available time windows and working days derived from actual Jobber jobs.
     *
     * @since 1.1.0
     * @return array Availability data with 'arrival_windows', 'working_days', 'workday_start', 'workday_end'
     */
    private function get_jobber_availability() {
        // Default fallback availability (used when Jobber is not configured or API fails)
        $defaults = array(
            'arrival_windows' => array(
                array('start' => 8, 'end' => 9, 'label' => '8:00 AM - 9:00 AM'),
                array('start' => 11, 'end' => 14, 'label' => '11:00 AM - 2:00 PM'),
                array('start' => 14.5, 'end' => 17, 'label' => '2:30 PM - 5:00 PM'),
            ),
            'working_days' => array(1, 2, 3, 4, 5), // Mon-Fri (0=Sun, 1=Mon...6=Sat)
            'workday_start' => 8,
            'workday_end' => 17,
            'source' => 'default',
        );
        
        if (!xtremecleans_is_api_configured()) {
            return $defaults;
        }
        
        // Get fresh token (auto-refresh if expired)
        $access_token = $this->ensure_fresh_jobber_token();
        if (empty($access_token)) {
            return $defaults;
        }
        
        // Check transient cache (cache for 15 minutes to reduce API calls)
        $cached = get_transient('xtremecleans_jobber_availability');
        if ($cached !== false) {
            return $cached;
        }
        
        $graphql_endpoint = 'https://api.getjobber.com/api/graphql';
        $api_version = apply_filters('xtremecleans_jobber_api_version', '2025-04-16');
        
        // Fetch recent and upcoming jobs to analyze scheduling patterns
        // Look at jobs in the next 30 days to determine working patterns
        $today = date('Y-m-d');
        $thirty_days = date('Y-m-d', strtotime('+30 days'));
        
        // Limit to 100 jobs and 10 visits per job to stay within Jobber's 10,000 query cost budget
        $query = '{
            jobs(first: 100) {
                nodes {
                    id
                    title
                    jobStatus
                    startAt
                    endAt
                    visits(first: 10) {
                        nodes {
                            startAt
                            endAt
                        }
                    }
                }
            }
        }';
        
        $response = wp_remote_post($graphql_endpoint, array(
            'timeout' => 30,
            'headers' => array(
                'Authorization' => 'Bearer ' . $access_token,
                'Content-Type' => 'application/json',
                'Accept' => 'application/json',
                'X-JOBBER-GRAPHQL-VERSION' => $api_version,
            ),
            'body' => wp_json_encode(array('query' => $query)),
        ));
        
        if (is_wp_error($response)) {
            xtremecleans_log('Jobber availability fetch error: ' . $response->get_error_message(), 'error');
            return $defaults;
        }
        
        $status = wp_remote_retrieve_response_code($response);
        $body = wp_remote_retrieve_body($response);
        $data = json_decode($body, true);
        
        // If 401 (expired token), try refreshing and retrying once
        if ($status === 401) {
            xtremecleans_log('Jobber 401 on availability fetch, attempting token refresh and retry...', 'info');
            $refreshed_token = $this->ensure_fresh_jobber_token();
            if (!empty($refreshed_token) && $refreshed_token !== $access_token) {
                $response = wp_remote_post($graphql_endpoint, array(
                    'timeout' => 30,
                    'headers' => array(
                        'Authorization' => 'Bearer ' . $refreshed_token,
                        'Content-Type' => 'application/json',
                        'Accept' => 'application/json',
                        'X-JOBBER-GRAPHQL-VERSION' => $api_version,
                    ),
                    'body' => wp_json_encode(array('query' => $query)),
                ));
                if (!is_wp_error($response)) {
                    $status = wp_remote_retrieve_response_code($response);
                    $body = wp_remote_retrieve_body($response);
                    $data = json_decode($body, true);
                }
            }
        }
        
        if ($status < 200 || $status >= 300 || !isset($data['data']['jobs']['nodes'])) {
            xtremecleans_log('Jobber availability: API error or unexpected format (status: ' . $status . '). Response: ' . substr($body, 0, 500), 'warning');
            return $defaults;
        }
        
        $jobs = $data['data']['jobs']['nodes'];
        
        if (empty($jobs)) {
            xtremecleans_log('No Jobber jobs found for availability analysis, using defaults', 'info');
            return $defaults;
        }
        
        // Analyze job scheduling patterns to build dynamic availability
        $biz_tz = $this->get_business_timezone($jobs); // Pass jobs for timezone auto-detection
        $earliest_start = 24; // Track earliest job start
        $latest_end = 0;      // Track latest job end
        $working_days_map = array(); // Track which days of week have jobs
        $time_blocks = array(); // Collect all start/end times to build windows
        
        foreach ($jobs as $job) {
            if (empty($job['startAt'])) {
                continue;
            }
            
            $job_status = isset($job['jobStatus']) ? $job['jobStatus'] : '';
            if (in_array($job_status, array('COMPLETED', 'CANCELLED', 'CLOSED'), true)) {
                continue;
            }
            
            // Prefer visit-level times over job-level times for pattern analysis
            $has_visits = !empty($job['visits']['nodes']) && is_array($job['visits']['nodes']);
            $entries_to_analyze = array();
            
            if ($has_visits) {
                foreach ($job['visits']['nodes'] as $visit) {
                    if (!empty($visit['startAt'])) {
                        $entries_to_analyze[] = array(
                            'startAt' => $visit['startAt'],
                            'endAt'   => isset($visit['endAt']) ? $visit['endAt'] : null,
                        );
                    }
                }
            }
            
            // Fallback to job-level if no visits
            if (empty($entries_to_analyze)) {
                $entries_to_analyze[] = array(
                    'startAt' => $job['startAt'],
                    'endAt'   => isset($job['endAt']) ? $job['endAt'] : null,
                );
            }
            
            foreach ($entries_to_analyze as $entry) {
                try {
                    $start_dt = new DateTime($entry['startAt']);
                    $start_dt->setTimezone($biz_tz); // Convert UTC → local
                    $end_dt = !empty($entry['endAt']) ? new DateTime($entry['endAt']) : null;
                    if ($end_dt) {
                        $end_dt->setTimezone($biz_tz); // Convert UTC → local
                    }
                } catch (Exception $e) {
                    continue;
                }
                
                // Skip date-only entries (midnight local time) from pattern analysis
                // These would skew workday_start to 0 and pollute time clusters
                if ($start_dt->format('H:i:s') === '00:00:00') {
                    // Still count the day for working_days analysis
                    $day_of_week = (int) $start_dt->format('w');
                    $working_days_map[$day_of_week] = true;
                    continue;
                }
                
                $start_hour = (float) $start_dt->format('H') + ((float) $start_dt->format('i') / 60);
                $end_hour = $end_dt ? ((float) $end_dt->format('H') + ((float) $end_dt->format('i') / 60)) : ($start_hour + 2);
                // If end is midnight, treat as end-of-day
                if ($end_hour == 0 && $end_dt) $end_hour = 24;
                $day_of_week = (int) $start_dt->format('w');
                
                if ($start_hour < $earliest_start) $earliest_start = $start_hour;
                if ($end_hour > $latest_end) $latest_end = $end_hour;
                $working_days_map[$day_of_week] = true;
                
                $time_blocks[] = array('start' => $start_hour, 'end' => $end_hour);
            }
        }
        
        // Build availability from analyzed patterns
        $workday_start = $earliest_start < 24 ? floor($earliest_start) : 8;
        $workday_end = $latest_end > 0 ? ceil($latest_end) : 17;
        
        // Ensure reasonable bounds
        $workday_start = max(6, min(12, $workday_start));  // Between 6 AM and 12 PM
        $workday_end = max(14, min(22, $workday_end));       // Between 2 PM and 10 PM
        
        // Build working days: Monday-Friday open, Saturday & Sunday closed
        $working_days = array(1, 2, 3, 4, 5); // 0=Sun, 1=Mon...6=Sat
        
        // Build arrival windows from job time patterns
        // Group jobs into natural clusters and create windows
        // Use fixed 3-slot arrival windows (business-defined)
        $arrival_windows = array(
            array('start' => 8, 'end' => 9, 'label' => '8:00 AM - 9:00 AM'),
            array('start' => 11, 'end' => 14, 'label' => '11:00 AM - 2:00 PM'),
            array('start' => 14.5, 'end' => 17, 'label' => '2:30 PM - 5:00 PM'),
        );
        
        $result = array(
            'arrival_windows' => $arrival_windows,
            'working_days' => $working_days,
            'workday_start' => $workday_start,
            'workday_end' => $workday_end,
            'source' => 'jobber',
            'jobs_analyzed' => count($time_blocks),
        );
        
        // Cache for 15 minutes
        set_transient('xtremecleans_jobber_availability', $result, 15 * MINUTE_IN_SECONDS);
        
        xtremecleans_log('Jobber availability built from ' . count($time_blocks) . ' jobs: ' . count($arrival_windows) . ' windows, workday ' . $workday_start . '-' . $workday_end, 'info');
        
        return $result;
    }
    
    /**
     * Build arrival time windows from analyzed Jobber job time patterns.
     * Groups job times into natural clusters and creates booking windows.
     *
     * @since 1.1.0
     * @param array $time_blocks Array of ['start' => float, 'end' => float]
     * @param float $workday_start Earliest workday hour
     * @param float $workday_end Latest workday hour
     * @return array Arrival windows array
     */
    private function build_arrival_windows_from_jobs($time_blocks, $workday_start, $workday_end) {
        // Default fallback windows
        $default_windows = array(
            array('start' => 8, 'end' => 9, 'label' => '8:00 AM - 9:00 AM'),
            array('start' => 11, 'end' => 14, 'label' => '11:00 AM - 2:00 PM'),
            array('start' => 14.5, 'end' => 17, 'label' => '2:30 PM - 5:00 PM'),
        );
        
        if (empty($time_blocks) || count($time_blocks) < 3) {
            // Not enough data to build patterns — use defaults
            return $default_windows;
        }
        
        // Collect all start times and sort them
        $start_times = array();
        foreach ($time_blocks as $block) {
            $start_times[] = $block['start'];
        }
        sort($start_times);
        
        // Group start times into clusters (times within 1.5 hours of each other)
        $clusters = array();
        $current_cluster = array($start_times[0]);
        
        for ($i = 1; $i < count($start_times); $i++) {
            if ($start_times[$i] - end($current_cluster) <= 1.5) {
                $current_cluster[] = $start_times[$i];
            } else {
                $clusters[] = $current_cluster;
                $current_cluster = array($start_times[$i]);
            }
        }
        $clusters[] = $current_cluster;
        
        // Build windows from clusters
        $windows = array();
        foreach ($clusters as $cluster) {
            $cluster_start = floor(min($cluster));
            $cluster_end = ceil(max($cluster)) + 1; // Add 1 hour buffer
            
            // Clamp to workday
            $cluster_start = max($workday_start, $cluster_start);
            $cluster_end = min($workday_end, $cluster_end);
            
            if ($cluster_end <= $cluster_start) {
                continue;
            }
            
            $windows[] = array(
                'start' => $cluster_start,
                'end' => $cluster_end,
                'label' => $this->format_time_label($cluster_start) . ' - ' . $this->format_time_label($cluster_end),
            );
        }
        
        // If we got valid windows, use them; otherwise fall back to defaults
        if (empty($windows)) {
            return $default_windows;
        }
        
        // Merge overlapping windows
        usort($windows, function($a, $b) {
            return $a['start'] <=> $b['start'];
        });
        
        $merged = array($windows[0]);
        for ($i = 1; $i < count($windows); $i++) {
            $last = &$merged[count($merged) - 1];
            if ($windows[$i]['start'] <= $last['end']) {
                // Overlapping — merge
                $last['end'] = max($last['end'], $windows[$i]['end']);
                $last['label'] = $this->format_time_label($last['start']) . ' - ' . $this->format_time_label($last['end']);
            } else {
                $merged[] = $windows[$i];
            }
        }
        
        return $merged;
    }
    
    /**
     * Format decimal hour to readable time label (e.g. 14.5 => "2:30 PM")
     *
     * @since 1.1.0
     * @param float $hour Decimal hour (e.g. 14.5 = 2:30 PM)
     * @return string Formatted time label
     */
    private function format_time_label($hour) {
        $h = (int) floor($hour);
        $m = (int) round(($hour - $h) * 60);
        $period = $h >= 12 ? 'PM' : 'AM';
        $display_h = $h > 12 ? $h - 12 : ($h === 0 ? 12 : $h);
        return $display_h . ':' . str_pad($m, 2, '0', STR_PAD_LEFT) . ' ' . $period;
    }
    
    /**
     * AJAX: Return Jobber-derived availability (time windows, working days, etc.)
     * Called by frontend calendar to build dynamic slot grid.
     *
     * @since 1.1.0
     */
    public function ajax_get_jobber_availability() {
        $availability = $this->get_jobber_availability();
        wp_send_json_success($availability);
    }
    
    /**
     * Total bookings occupying a slot: WordPress orders + Jobber-mapped jobs for that window.
     * Defaults to WP count + Jobber count (conservative). If the same job appears in both, use filter
     * `xtremecleans_slot_occupancy_count` to adjust (e.g. max(wp, jobber)).
     *
     * Filter: `xtremecleans_slot_occupancy_count` — receives ($total, $date, $time, $wp_count, $jobber_count).
     *
     * @since 1.1.0
     * @param string $appointment_date Y-m-d.
     * @param string $appointment_time Arrival window label (must match calendar labels).
     * @return int
     */
    private function get_slot_occupancy_count($appointment_date, $appointment_time) {
        global $wpdb;
        $table_name = $wpdb->prefix . 'xtremecleans_orders';
        
        $wp_count = (int) $wpdb->get_var($wpdb->prepare(
            "SELECT COUNT(*) FROM {$table_name} WHERE appointment_date = %s AND appointment_time = %s",
            $appointment_date,
            $appointment_time
        ));
        
        $availability   = $this->get_jobber_availability();
        $time_windows   = isset($availability['arrival_windows']) ? $availability['arrival_windows'] : array();
        $jobber_bookings = $this->get_jobber_appointments_for_date_range($appointment_date, $appointment_date, $time_windows);
        $jobber_count   = 0;
        if (!empty($jobber_bookings)) {
            foreach ($jobber_bookings as $row) {
                if (!empty($row['date']) && !empty($row['time']) && $row['date'] === $appointment_date && $row['time'] === $appointment_time) {
                    $jobber_count++;
                }
            }
        }
        
        $total = $wp_count + $jobber_count;
        
        return (int) apply_filters('xtremecleans_slot_occupancy_count', $total, $appointment_date, $appointment_time, $wp_count, $jobber_count);
    }
    
    /**
     * AJAX: Get booked appointment slots/counts for a date range.
     * Used by frontend calendar to mark already-booked slots as unavailable.
     * Merges WordPress orders + Jobber appointments.
>>>>>>> 3378c4f (plugin last vertation updated in admin dashboard)
     *
     * @since 1.0.0
     */
    public function ajax_get_booked_slots() {
        $week_start = isset($_REQUEST['week_start']) ? sanitize_text_field($_REQUEST['week_start']) : '';
        $week_end   = isset($_REQUEST['week_end']) ? sanitize_text_field($_REQUEST['week_end']) : '';
        
        if (empty($week_start) || empty($week_end)) {
<<<<<<< HEAD
            wp_send_json_success(array('booked_slots' => array()));
        }
        
        global $wpdb;
        $table_name = $wpdb->prefix . 'xtremecleans_orders';
        
        $results = $wpdb->get_results($wpdb->prepare(
=======
            $slot_capacity_enabled = absint(get_option('xtremecleans_slot_capacity_enabled', 0)) === 1;
            $slot_capacity          = $slot_capacity_enabled ? max(1, absint(get_option('xtremecleans_slot_capacity', 1))) : 1;
            wp_send_json_success(array(
                'booked_slots' => array(),
                'slot_counts' => array(),
                'slot_capacity' => $slot_capacity,
                'slot_capacity_enabled' => $slot_capacity_enabled,
            ));
        }
        
        // Get current availability windows (may be from Jobber or defaults)
        $availability = $this->get_jobber_availability();
        $time_windows = isset($availability['arrival_windows']) ? $availability['arrival_windows'] : array();
        
        // Get WordPress orders bookings
        global $wpdb;
        $table_name = $wpdb->prefix . 'xtremecleans_orders';
        
        $wp_results = $wpdb->get_results($wpdb->prepare(
>>>>>>> 3378c4f (plugin last vertation updated in admin dashboard)
            "SELECT appointment_date, appointment_time FROM {$table_name} 
             WHERE appointment_date BETWEEN %s AND %s 
             AND appointment_date IS NOT NULL 
             AND appointment_time IS NOT NULL 
             AND appointment_time != ''",
            $week_start,
            $week_end
        ), ARRAY_A);
        
        $booked_slots = array();
<<<<<<< HEAD
        if (!empty($results)) {
            foreach ($results as $row) {
                $booked_slots[] = array(
                    'date' => $row['appointment_date'],
                    'time' => $row['appointment_time'],
                );
            }
        }
        
        wp_send_json_success(array('booked_slots' => $booked_slots));
=======
        $slot_counts_by_source = array();

        // Add WordPress bookings
        if (!empty($wp_results)) {
            foreach ($wp_results as $row) {
                $booked_slots[] = array(
                    'date' => $row['appointment_date'],
                    'time' => $row['appointment_time'],
                    'source' => 'wordpress',
                );
                $slot_key = $row['appointment_date'] . '|' . $row['appointment_time'];
                if (!isset($slot_counts_by_source[$slot_key])) {
                    $slot_counts_by_source[$slot_key] = array(
                        'date' => $row['appointment_date'],
                        'time' => $row['appointment_time'],
                        'wordpress' => 0,
                        'jobber' => 0,
                    );
                }
                $slot_counts_by_source[$slot_key]['wordpress']++;
            }
        }
        
        // Add Jobber appointments (pass current time windows for overlap mapping)
        $jobber_bookings = $this->get_jobber_appointments_for_date_range($week_start, $week_end, $time_windows);
        if (!empty($jobber_bookings)) {
            $booked_slots = array_merge($booked_slots, $jobber_bookings);
            foreach ($jobber_bookings as $row) {
                $slot_key = $row['date'] . '|' . $row['time'];
                if (!isset($slot_counts_by_source[$slot_key])) {
                    $slot_counts_by_source[$slot_key] = array(
                        'date' => $row['date'],
                        'time' => $row['time'],
                        'wordpress' => 0,
                        'jobber' => 0,
                    );
                }
                $slot_counts_by_source[$slot_key]['jobber']++;
            }
        }
        
        // Log summary for debugging
        $wp_count = !empty($wp_results) ? count($wp_results) : 0;
        $jobber_count = !empty($jobber_bookings) ? count($jobber_bookings) : 0;
        xtremecleans_log('Booked slots summary: ' . $wp_count . ' from WordPress, ' . $jobber_count . ' from Jobber, range: ' . $week_start . ' to ' . $week_end, 'info');
        
        // Slot capacity config (1 when disabled)
        $slot_capacity_enabled = absint(get_option('xtremecleans_slot_capacity_enabled', 0)) === 1;
        $slot_capacity = $slot_capacity_enabled ? absint(get_option('xtremecleans_slot_capacity', 1)) : 1;
        if ($slot_capacity < 1) {
            $slot_capacity = 1;
        }

        // Effective occupancy = WP bookings + Jobber jobs for that slot (see get_slot_occupancy_count).
        $slot_counts = array();
        foreach ($slot_counts_by_source as $key => $counts) {
            $wp_n   = (int) $counts['wordpress'];
            $jb_n   = (int) $counts['jobber'];
            $merged = (int) apply_filters('xtremecleans_slot_occupancy_count', $wp_n + $jb_n, $counts['date'], $counts['time'], $wp_n, $jb_n);
            $slot_counts[$key] = array(
                'date' => $counts['date'],
                'time' => $counts['time'],
                'count' => $merged,
                'capacity' => $slot_capacity,
                'wordpress' => $wp_n,
                'jobber' => $jb_n,
            );
        }

        wp_send_json_success(array(
            'booked_slots' => $booked_slots, // kept for backward compatibility
            'slot_counts' => $slot_counts,
            'slot_capacity' => $slot_capacity,
            'slot_capacity_enabled' => $slot_capacity_enabled,
        ));
>>>>>>> 3378c4f (plugin last vertation updated in admin dashboard)
    }
    
    /**
     * Create database table for leads
     *
     * @since 1.0.0
     */
    private function create_leads_table() {
        global $wpdb;
        
        $charset_collate = $wpdb->get_charset_collate();
        
        // Table for leads
        $table_name = $wpdb->prefix . 'xtremecleans_leads';
        
        $sql = "CREATE TABLE IF NOT EXISTS `{$table_name}` (
            `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT,
            `name` varchar(100) NOT NULL,
            `email` varchar(100) NOT NULL,
            `phone` varchar(20) NOT NULL,
            `zip_code` varchar(10) DEFAULT NULL,
            `zone_name` varchar(100) DEFAULT NULL,
            `status` varchar(20) DEFAULT 'new',
            `notes` text DEFAULT NULL,
            `created_at` datetime DEFAULT NULL,
            `updated_at` datetime DEFAULT NULL,
            PRIMARY KEY (`id`),
            KEY `email` (`email`),
            KEY `status` (`status`),
            KEY `created_at` (`created_at`)
        ) {$charset_collate};";
        
        require_once(ABSPATH . 'wp-admin/includes/upgrade.php');
        dbDelta($sql);
    }
    
    /**
     * Render Leads Page
     *
     * @since 1.0.0
     */
    public function render_leads_page() {
        if (!current_user_can('manage_options')) {
            wp_die(__('You do not have sufficient permissions to access this page.', 'xtremecleans'));
        }
        
        // Ensure leads table exists
        $this->create_leads_table();
        
        $leads = $this->get_all_leads();
        
        $template_path = xtremecleans_get_template_path('admin-leads', 'admin');
        
        if (file_exists($template_path)) {
            xtremecleans_load_template('admin-leads', array('leads' => $leads), 'admin');
        } else {
            // Fallback if template doesn't exist
            echo '<div class="wrap">';
            echo '<h1>' . esc_html__('Leads', 'xtremecleans') . '</h1>';
            echo '<table class="wp-list-table widefat fixed striped">';
            echo '<thead><tr>';
            echo '<th>' . esc_html__('ID', 'xtremecleans') . '</th>';
            echo '<th>' . esc_html__('Name', 'xtremecleans') . '</th>';
            echo '<th>' . esc_html__('Email', 'xtremecleans') . '</th>';
            echo '<th>' . esc_html__('Phone', 'xtremecleans') . '</th>';
            echo '<th>' . esc_html__('ZIP Code', 'xtremecleans') . '</th>';
            echo '<th>' . esc_html__('Zone', 'xtremecleans') . '</th>';
            echo '<th>' . esc_html__('Status', 'xtremecleans') . '</th>';
            echo '<th>' . esc_html__('Date', 'xtremecleans') . '</th>';
            echo '</tr></thead>';
            echo '<tbody>';
            if (empty($leads)) {
                echo '<tr><td colspan="8">' . esc_html__('No leads found.', 'xtremecleans') . '</td></tr>';
            } else {
                foreach ($leads as $lead) {
                    echo '<tr>';
                    echo '<td>' . esc_html($lead['id']) . '</td>';
                    echo '<td>' . esc_html($lead['name']) . '</td>';
                    echo '<td>' . esc_html($lead['email']) . '</td>';
                    echo '<td>' . esc_html($lead['phone']) . '</td>';
                    echo '<td>' . esc_html($lead['zip_code']) . '</td>';
                    echo '<td>' . esc_html($lead['zone_name'] ? $lead['zone_name'] : '-') . '</td>';
                    echo '<td>' . esc_html($lead['status']) . '</td>';
                    echo '<td>' . esc_html($lead['created_at']) . '</td>';
                    echo '</tr>';
                }
            }
            echo '</tbody></table>';
            echo '</div>';
        }
    }
    
    /**
     * Get all leads from database
     *
     * @since 1.0.0
     * @return array
     */
    public function get_all_leads() {
        global $wpdb;
        $table_name = $wpdb->prefix . 'xtremecleans_leads';
        
        // Check if table exists
        $table_exists = $wpdb->get_var($wpdb->prepare("SHOW TABLES LIKE %s", $table_name));
        if (!$table_exists) {
            return array();
        }
        
        $leads = $wpdb->get_results(
            "SELECT * FROM `{$table_name}` ORDER BY created_at DESC",
            ARRAY_A
        );
        
        return $leads ? $leads : array();
    }
    
    /**
     * AJAX handler: Delete lead
     *
     * @since 1.0.0
     */
    public function ajax_delete_lead() {
        if (!current_user_can('manage_options')) {
            wp_send_json_error(array('message' => __('Permission denied.', 'xtremecleans')));
        }
        
        if (!isset($_POST['lead_id']) || !isset($_POST['nonce']) || !wp_verify_nonce($_POST['nonce'], 'xtremecleans_leads')) {
            wp_send_json_error(array('message' => __('Security check failed.', 'xtremecleans')));
        }
        
        $lead_id = intval($_POST['lead_id']);
        global $wpdb;
        $table_name = $wpdb->prefix . 'xtremecleans_leads';
        
        $result = $wpdb->delete(
            $table_name,
            array('id' => $lead_id),
            array('%d')
        );
        
        if ($result === false) {
            wp_send_json_error(array('message' => __('Failed to delete lead.', 'xtremecleans')));
        }
        
        wp_send_json_success(array('message' => __('Lead deleted successfully.', 'xtremecleans')));
    }
    
    /**
     * Export leads to CSV
     *
     * @since 1.0.0
     */
    public function export_leads() {
        if (!current_user_can('manage_options')) {
            wp_die(__('Permission denied.', 'xtremecleans'));
        }
<<<<<<< HEAD
=======

        if (!isset($_GET['_wpnonce']) || !wp_verify_nonce(sanitize_text_field(wp_unslash($_GET['_wpnonce'])), 'xtremecleans_export_leads')) {
            wp_die(__('Security check failed.', 'xtremecleans'));
        }
>>>>>>> 3378c4f (plugin last vertation updated in admin dashboard)
        
        $leads = $this->get_all_leads();
        
        header('Content-Type: text/csv; charset=utf-8');
        header('Content-Disposition: attachment; filename=xtremecleans-leads-' . date('Y-m-d') . '.csv');
        header('Pragma: no-cache');
        header('Expires: 0');
        
        $output = fopen('php://output', 'w');
        
        // Add BOM for UTF-8
        fprintf($output, chr(0xEF).chr(0xBB).chr(0xBF));
        
        // Headers
        fputcsv($output, array(
            'ID',
            'Name',
            'Email',
            'Phone',
            'ZIP Code',
            'Zone Name',
            'Status',
            'Notes',
            'Created At',
            'Updated At'
        ));
        
        // Data
        foreach ($leads as $lead) {
            fputcsv($output, array(
                $lead['id'],
                $lead['name'],
                $lead['email'],
                $lead['phone'],
                $lead['zip_code'],
                $lead['zone_name'],
                $lead['status'],
                $lead['notes'],
                $lead['created_at'],
                $lead['updated_at']
            ));
        }
        
        fclose($output);
        exit;
    }
    
    /**
     * Get all orders from database
     *
     * @since 1.0.0
     * @return array
     */
    public function get_all_orders() {
        global $wpdb;
        $table_name = $wpdb->prefix . 'xtremecleans_orders';
        
        // Check if table exists
        $table_exists = $wpdb->get_var($wpdb->prepare("SHOW TABLES LIKE %s", $table_name));
        if (!$table_exists) {
            return array();
        }
        
        $orders = $wpdb->get_results(
            "SELECT * FROM `{$table_name}` ORDER BY created_at DESC",
            ARRAY_A
        );
        
        return $orders ? $orders : array();
    }
    
    /**
     * Get order statistics for charts
     *
     * @since 1.0.0
     * @param array $orders
     * @return array
     */
    public function get_order_statistics($orders) {
        $stats = array(
            'total_orders' => count($orders),
            'total_revenue' => 0,
            'orders_by_date' => array(),
            'orders_by_state' => array(),
            'orders_by_service' => array(),
            'revenue_by_date' => array(),
        );
        
        $current_date = date('Y-m-d');
        $last_30_days = array();
        $last_7_days = array();
        
        foreach ($orders as $order) {
            // Calculate total revenue
            if (!empty($order['total_amount'])) {
                $stats['total_revenue'] += floatval($order['total_amount']);
            }
            
            // Orders by date (last 30 days)
            if (!empty($order['created_at'])) {
                $order_date = date('Y-m-d', strtotime($order['created_at']));
                $days_diff = (strtotime($current_date) - strtotime($order_date)) / (60 * 60 * 24);
                
                if ($days_diff <= 30) {
                    if (!isset($last_30_days[$order_date])) {
                        $last_30_days[$order_date] = 0;
                    }
                    $last_30_days[$order_date]++;
                    
                    if ($days_diff <= 7) {
                        if (!isset($last_7_days[$order_date])) {
                            $last_7_days[$order_date] = 0;
                        }
                        $last_7_days[$order_date]++;
                    }
                }
                
                // Revenue by date
                if (!isset($stats['revenue_by_date'][$order_date])) {
                    $stats['revenue_by_date'][$order_date] = 0;
                }
                if (!empty($order['total_amount'])) {
                    $stats['revenue_by_date'][$order_date] += floatval($order['total_amount']);
                }
            }
            
            // Orders by state
            if (!empty($order['state'])) {
                $state = $order['state'];
                if (!isset($stats['orders_by_state'][$state])) {
                    $stats['orders_by_state'][$state] = 0;
                }
                $stats['orders_by_state'][$state]++;
            }
            
            // Orders by service (from services_json)
            if (!empty($order['services_json'])) {
                $services = json_decode($order['services_json'], true);
                if (is_array($services)) {
                    foreach ($services as $service) {
                        if (isset($service['service_name'])) {
                            $service_name = $service['service_name'];
                            if (!isset($stats['orders_by_service'][$service_name])) {
                                $stats['orders_by_service'][$service_name] = 0;
                            }
                            $stats['orders_by_service'][$service_name]++;
                        }
                    }
                }
            }
        }
        
        // Sort dates
        ksort($last_30_days);
        ksort($last_7_days);
        ksort($stats['revenue_by_date']);
        
        $stats['orders_by_date_30'] = $last_30_days;
        $stats['orders_by_date_7'] = $last_7_days;
        
        return $stats;
    }
    
    /**
     * AJAX handler: Get order details
     *
     * @since 1.0.0
     */
    public function ajax_get_order_details() {
        if (!current_user_can('manage_options')) {
            wp_send_json_error(array('message' => __('Permission denied.', 'xtremecleans')));
        }
        
        if (!isset($_POST['order_id']) || !isset($_POST['nonce']) || !wp_verify_nonce($_POST['nonce'], 'xtremecleans_orders')) {
            wp_send_json_error(array('message' => __('Security check failed.', 'xtremecleans')));
        }
        
        $order_id = intval($_POST['order_id']);
        global $wpdb;
        $table_name = $wpdb->prefix . 'xtremecleans_orders';
        
        $order = $wpdb->get_row(
            $wpdb->prepare("SELECT * FROM `{$table_name}` WHERE id = %d", $order_id),
            ARRAY_A
        );
        
        if (!$order) {
            wp_send_json_error(array('message' => __('Order not found.', 'xtremecleans')));
        }
        
        wp_send_json_success(array('order' => $order));
    }
    
    /**
     * AJAX handler: Delete order
     *
     * @since 1.0.0
     */
    public function ajax_delete_order() {
        if (!current_user_can('manage_options')) {
            wp_send_json_error(array('message' => __('Permission denied.', 'xtremecleans')));
        }
        
        if (!isset($_POST['order_id']) || !isset($_POST['nonce']) || !wp_verify_nonce($_POST['nonce'], 'xtremecleans_orders')) {
            wp_send_json_error(array('message' => __('Security check failed.', 'xtremecleans')));
        }
        
        $order_id = intval($_POST['order_id']);
        global $wpdb;
        $table_name = $wpdb->prefix . 'xtremecleans_orders';
        
        $deleted = $wpdb->delete(
            $table_name,
            array('id' => $order_id),
            array('%d')
        );
        
        if ($deleted === false) {
            wp_send_json_error(array('message' => __('Failed to delete order.', 'xtremecleans')));
        }
        
        wp_send_json_success(array('message' => __('Order deleted successfully.', 'xtremecleans')));
    }
    
    /**
     * AJAX handler: Manually sync order to Jobber
     *
     * @since 1.0.0
     */
    public function ajax_sync_order_to_jobber() {
        // Force logging for debugging - write directly to error_log
        error_log('[XtremeCleans DEBUG] ajax_sync_order_to_jobber() called at ' . current_time('mysql'));
        error_log('[XtremeCleans DEBUG] POST data: ' . print_r($_POST, true));
        
        if (!current_user_can('manage_options')) {
            error_log('[XtremeCleans DEBUG] Permission denied - user cannot manage_options');
            wp_send_json_error(array('message' => __('Permission denied.', 'xtremecleans')));
        }
        
        if (!isset($_POST['order_id']) || !isset($_POST['nonce']) || !wp_verify_nonce($_POST['nonce'], 'xtremecleans_orders')) {
            error_log('[XtremeCleans DEBUG] Security check failed - order_id: ' . (isset($_POST['order_id']) ? 'SET' : 'NOT SET') . ', nonce: ' . (isset($_POST['nonce']) ? 'SET' : 'NOT SET'));
            wp_send_json_error(array('message' => __('Security check failed.', 'xtremecleans')));
        }
        
        $order_id = intval($_POST['order_id']);
        error_log('[XtremeCleans DEBUG] Order ID: ' . $order_id);
        
        global $wpdb;
        $table_name = $wpdb->prefix . 'xtremecleans_orders';
        
        $order = $wpdb->get_row(
            $wpdb->prepare("SELECT * FROM `{$table_name}` WHERE id = %d", $order_id),
            ARRAY_A
        );
        
        if (!$order) {
            error_log('[XtremeCleans DEBUG] Order not found in database');
            wp_send_json_error(array('message' => __('Order not found.', 'xtremecleans')));
        }
        
        error_log('[XtremeCleans DEBUG] Order found, payload length: ' . strlen($order['payload']));
        
        // Decode order payload
        $order_data = json_decode($order['payload'], true);
        if (empty($order_data)) {
            error_log('[XtremeCleans DEBUG] Order payload is empty or invalid JSON');
            wp_send_json_error(array('message' => __('Invalid order data. Cannot sync to Jobber.', 'xtremecleans')));
        }
        
        error_log('[XtremeCleans DEBUG] Order data decoded, keys: ' . implode(', ', array_keys($order_data)));
        
        // Attempt to sync to Jobber
        xtremecleans_log('Manual Jobber sync initiated for order #' . $order_id, 'info');
        error_log('[XtremeCleans DEBUG] Calling maybe_send_order_to_api()...');
        $jobber_result = $this->maybe_send_order_to_api($order_data);
        
        // Update order with Jobber sync status
        $this->update_order_jobber_status($order_id, $jobber_result);
        
        // Validate that quote and job were actually created (have IDs)
        $quote_created = false;
        $job_created = false;
        
        if (!empty($jobber_result['results']['quote']['sent']) && !empty($jobber_result['results']['quote']['response']['id'])) {
            $quote_created = true;
        }
        
        if (!empty($jobber_result['results']['job']['sent']) && !empty($jobber_result['results']['job']['response']['id'])) {
            $job_created = true;
        }
        
        // Only mark as successful if quote OR job was actually created
        $actually_successful = $quote_created || $job_created;
        
        if ($jobber_result['sent'] && $actually_successful) {
            $success_msg = __('Order successfully synced to Jobber CRM!', 'xtremecleans');
            if ($quote_created) {
                $quote_number = isset($jobber_result['results']['quote']['response']['quoteNumber']) ? $jobber_result['results']['quote']['response']['quoteNumber'] : '';
                if ($quote_number) {
                    $success_msg .= ' Quote #' . $quote_number . ' created.';
                } else {
                    $success_msg .= ' Quote created.';
                }
            }
            if ($job_created) {
                $job_number = isset($jobber_result['results']['job']['response']['jobNumber']) ? $jobber_result['results']['job']['response']['jobNumber'] : '';
                if ($job_number) {
                    $success_msg .= ' Job #' . $job_number . ' created.';
                } else {
                    $success_msg .= ' Job created.';
                }
            }
            
            xtremecleans_log('Manual Jobber sync successful for order #' . $order_id . ' - Quote: ' . ($quote_created ? 'YES' : 'NO') . ', Job: ' . ($job_created ? 'YES' : 'NO'), 'info');
            wp_send_json_success(array(
                'message' => $success_msg,
                'jobber_result' => $jobber_result,
                'quote_created' => $quote_created,
                'job_created' => $job_created,
            ));
        } else {
            xtremecleans_log('Manual Jobber sync failed for order #' . $order_id . ': ' . $jobber_result['message'], 'error');
            
            // Build detailed error message
            $error_details = '';
            
            // Check if it was marked as success but nothing was actually created
            if ($jobber_result['sent'] && !$actually_successful) {
                $error_details = __('Sync reported success but no Quote or Job were actually created in Jobber.', 'xtremecleans');
                $error_details .= "\n\n" . __('This usually indicates a silent failure. Check the logs for:', 'xtremecleans');
                $error_details .= "\n• Top-level GraphQL errors (authorization/formatting issues)";
                $error_details .= "\n• Null quote/job responses";
                $error_details .= "\n• Missing IDs in responses";
            } else {
                $error_details = $jobber_result['message'];
            }
            
            if (!empty($jobber_result['results'])) {
                $error_details .= "\n\n" . __('Detailed Status:', 'xtremecleans');
                
                // Client status
                $client_sent = isset($jobber_result['results']['client']['sent']) ? $jobber_result['results']['client']['sent'] : false;
                $client_msg = isset($jobber_result['results']['client']['message']) ? $jobber_result['results']['client']['message'] : 'Unknown status';
                $error_details .= "\n• Client: " . ($client_sent ? '✓ Created' : '✗ Failed - ' . $client_msg);
                
                // Quote status with validation
                $quote_sent = isset($jobber_result['results']['quote']['sent']) ? $jobber_result['results']['quote']['sent'] : false;
                $quote_has_id = !empty($jobber_result['results']['quote']['response']['id']);
                $quote_msg = isset($jobber_result['results']['quote']['message']) ? $jobber_result['results']['quote']['message'] : 'Unknown status';
                
                if ($quote_sent && $quote_has_id) {
                    $quote_number = isset($jobber_result['results']['quote']['response']['quoteNumber']) ? $jobber_result['results']['quote']['response']['quoteNumber'] : '';
                    $error_details .= "\n• Quote: ✓ Created" . ($quote_number ? ' (Quote #' . $quote_number . ')' : '');
                } elseif ($quote_sent && !$quote_has_id) {
                    $error_details .= "\n• Quote: ✗ Failed - Reported as sent but no ID returned (null quote?)";
                } else {
                    $error_details .= "\n• Quote: ✗ Failed - " . $quote_msg;
                }
                
                // Job status with validation
                $job_sent = isset($jobber_result['results']['job']['sent']) ? $jobber_result['results']['job']['sent'] : false;
                $job_has_id = !empty($jobber_result['results']['job']['response']['id']);
                $job_msg = isset($jobber_result['results']['job']['message']) ? $jobber_result['results']['job']['message'] : 'Unknown status';
                
                if ($job_sent && $job_has_id) {
                    $job_number = isset($jobber_result['results']['job']['response']['jobNumber']) ? $jobber_result['results']['job']['response']['jobNumber'] : '';
                    $error_details .= "\n• Job: ✓ Created" . ($job_number ? ' (Job #' . $job_number . ')' : '');
                } elseif ($job_sent && !$job_has_id) {
                    $error_details .= "\n• Job: ✗ Failed - Reported as sent but no ID returned (null job?)";
                } else {
                    $error_details .= "\n• Job: ✗ Failed - " . $job_msg;
                }
            }
            
            wp_send_json_error(array(
                'message' => __('Failed to sync order to Jobber: ', 'xtremecleans') . $error_details,
                'jobber_result' => $jobber_result,
            ));
        }
    }
    
    /**
     * AJAX handler: Export orders
     *
     * @since 1.0.0
     */
    public function ajax_export_orders() {
        if (!current_user_can('manage_options')) {
            wp_die(__('Permission denied.', 'xtremecleans'));
        }
        
        if (!isset($_POST['nonce']) || !wp_verify_nonce($_POST['nonce'], 'xtremecleans_orders')) {
            wp_die(__('Security check failed.', 'xtremecleans'));
        }
        
        $orders = $this->get_all_orders();
        
        $filename = 'xtremecleans-orders-' . date('Y-m-d') . '.csv';
        
        // Set headers for CSV download
        header('Content-Type: text/csv; charset=utf-8');
        header('Content-Disposition: attachment; filename="' . $filename . '"');
        header('Pragma: no-cache');
        header('Expires: 0');
        
        // Open output stream
        $output = fopen('php://output', 'w');
        
        // Add BOM for UTF-8
        fprintf($output, chr(0xEF).chr(0xBB).chr(0xBF));
        
        // CSV headers
        fputcsv($output, array(
            'ID', 'First Name', 'Last Name', 'Email', 'Phone', 'Alt Phone',
            'Address 1', 'Address 2', 'City', 'State', 'ZIP Code',
            'Appointment Date', 'Appointment Time', 'Appointment Day',
            'Total Amount', 'Service Fee', 'Deposit Amount',
            'Created At'
        ));
        
        // CSV rows
        foreach ($orders as $order) {
            fputcsv($output, array(
                $order['id'],
                $order['first_name'],
                $order['last_name'],
                $order['email'],
                $order['phone'],
                isset($order['alt_phone']) ? $order['alt_phone'] : '',
                $order['address1'],
                isset($order['address2']) ? $order['address2'] : '',
                isset($order['city']) ? $order['city'] : '',
                isset($order['state']) ? $order['state'] : '',
                isset($order['zip_code']) ? $order['zip_code'] : '',
                isset($order['appointment_date']) ? $order['appointment_date'] : '',
                isset($order['appointment_time']) ? $order['appointment_time'] : '',
                isset($order['appointment_day']) ? $order['appointment_day'] : '',
                isset($order['total_amount']) ? $order['total_amount'] : '0.00',
                isset($order['service_fee']) ? $order['service_fee'] : '0.00',
                isset($order['deposit_amount']) ? $order['deposit_amount'] : '0.00',
                isset($order['created_at']) ? $order['created_at'] : ''
            ));
        }
        
        fclose($output);
        exit;
    }
    
    /**
     * Create database table for orders
     *
     * @since 1.0.0
     */
    private function create_orders_table() {
        global $wpdb;
        
        $charset_collate = $wpdb->get_charset_collate();
        $table_name = $wpdb->prefix . 'xtremecleans_orders';
        
        $sql = "CREATE TABLE IF NOT EXISTS `{$table_name}` (
            `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT,
            `first_name` varchar(100) NOT NULL,
            `last_name` varchar(100) NOT NULL,
            `email` varchar(150) NOT NULL,
            `phone` varchar(30) NOT NULL,
            `alt_phone` varchar(30) DEFAULT NULL,
            `address1` varchar(255) NOT NULL,
            `address2` varchar(255) DEFAULT NULL,
            `city` varchar(120) DEFAULT NULL,
            `state` varchar(60) DEFAULT NULL,
            `zip_code` varchar(20) DEFAULT NULL,
            `instructions` text DEFAULT NULL,
            `appointment_date` varchar(20) DEFAULT NULL,
            `appointment_time` varchar(40) DEFAULT NULL,
            `appointment_day` varchar(20) DEFAULT NULL,
            `services_json` longtext,
            `services_grouped` longtext,
            `total_amount` decimal(12,2) DEFAULT NULL,
            `service_fee` decimal(12,2) DEFAULT NULL,
            `deposit_amount` decimal(12,2) DEFAULT NULL,
            `zone_data` longtext,
            `payload` longtext,
            `payment_status` varchar(20) DEFAULT 'pending',
            `stripe_payment_intent_id` varchar(255) DEFAULT NULL,
            `stripe_charge_id` varchar(255) DEFAULT NULL,
            `deposit_paid_at` datetime DEFAULT NULL,
            `jobber_client_id` varchar(100) DEFAULT NULL,
            `jobber_quote_id` varchar(100) DEFAULT NULL,
            `jobber_job_id` varchar(100) DEFAULT NULL,
            `jobber_sync_status` varchar(20) DEFAULT 'pending',
            `jobber_sync_message` text DEFAULT NULL,
            `jobber_sync_attempted_at` datetime DEFAULT NULL,
            `created_at` datetime DEFAULT NULL,
            `updated_at` datetime DEFAULT NULL,
            PRIMARY KEY (`id`),
            KEY `email` (`email`),
            KEY `appointment_date` (`appointment_date`),
            KEY `payment_status` (`payment_status`),
            KEY `stripe_payment_intent_id` (`stripe_payment_intent_id`)
        ) {$charset_collate};";
        
        require_once(ABSPATH . 'wp-admin/includes/upgrade.php');
        dbDelta($sql);
        
        // Add new columns if they don't exist (for existing installations)
        $columns_to_add = array(
            'payment_status' => "ALTER TABLE `{$table_name}` ADD COLUMN `payment_status` varchar(20) DEFAULT 'pending' AFTER `payload`",
            'stripe_payment_intent_id' => "ALTER TABLE `{$table_name}` ADD COLUMN `stripe_payment_intent_id` varchar(255) DEFAULT NULL AFTER `payment_status`",
            'stripe_charge_id' => "ALTER TABLE `{$table_name}` ADD COLUMN `stripe_charge_id` varchar(255) DEFAULT NULL AFTER `stripe_payment_intent_id`",
            'deposit_paid_at' => "ALTER TABLE `{$table_name}` ADD COLUMN `deposit_paid_at` datetime DEFAULT NULL AFTER `stripe_charge_id`",
            'jobber_client_id' => "ALTER TABLE `{$table_name}` ADD COLUMN `jobber_client_id` varchar(100) DEFAULT NULL AFTER `deposit_paid_at`",
            'jobber_quote_id' => "ALTER TABLE `{$table_name}` ADD COLUMN `jobber_quote_id` varchar(100) DEFAULT NULL AFTER `jobber_client_id`",
            'jobber_job_id' => "ALTER TABLE `{$table_name}` ADD COLUMN `jobber_job_id` varchar(100) DEFAULT NULL AFTER `jobber_quote_id`",
        );
        
        foreach ($columns_to_add as $column_name => $alter_sql) {
            $column_exists = $wpdb->get_results($wpdb->prepare("SHOW COLUMNS FROM `{$table_name}` LIKE %s", $column_name));
            if (empty($column_exists)) {
                $wpdb->query($alter_sql);
            }
        }
        
        require_once(ABSPATH . 'wp-admin/includes/upgrade.php');
        dbDelta($sql);
    }
    
    /**
     * Send order payload to configured API (Jobber) if credentials exist
     *
     * @since 1.0.0
     *
     * @param array $order_payload
     * @return array
     */
    private function maybe_send_order_to_api($order_payload) {
        xtremecleans_log('=== STARTING JOBBER SYNC ===', 'info');
        xtremecleans_log('Order payload received: ' . wp_json_encode($order_payload), 'info');
        
        $auth_url = '';
        if (!function_exists('xtremecleans_is_api_configured') || !xtremecleans_is_api_configured()) {
            xtremecleans_log('Jobber API is NOT configured - checking credentials...', 'error');
            $client_id = xtremecleans_get_option('jobber_client_id', '');
            $client_secret = xtremecleans_get_option('jobber_client_secret', '');
            $access_token = get_option('xtremecleans_jobber_access_token', '');
            xtremecleans_log('Client ID: ' . (!empty($client_id) ? 'SET' : 'MISSING'), 'error');
            xtremecleans_log('Client Secret: ' . (!empty($client_secret) ? 'SET' : 'MISSING'), 'error');
            xtremecleans_log('Access Token: ' . (!empty($access_token) ? 'SET (' . substr($access_token, 0, 20) . '...)' : 'MISSING'), 'error');
            
            $auth_url = $this->get_jobber_auth_url();
            return array(
                'sent' => false,
                'message' => __('Jobber API credentials are not configured.', 'xtremecleans'),
                'auth_url' => $auth_url,
            );
        }
        
        xtremecleans_log('Jobber API is configured - proceeding with sync...', 'info');
        
        if (!class_exists('XtremeCleans_API')) {
            $api_file = XTREMECLEANS_PLUGIN_DIR . 'core/api/class-xtremecleans-api.php';
            if (file_exists($api_file)) {
                require_once $api_file;
            }
        }
        
        if (!class_exists('XtremeCleans_API')) {
            return array(
                'sent' => false,
                'message' => __('API integration is unavailable.', 'xtremecleans'),
                'auth_url' => $auth_url,
            );
        }
        
        $api = new XtremeCleans_API();
        if (!$api->is_configured()) {
            $auth_url = $this->get_jobber_auth_url();
            return array(
                'sent' => false,
                'message' => __('Jobber API credentials are missing.', 'xtremecleans'),
                'auth_url' => $auth_url,
            );
        }
        
        // Prepare Jobber data: Create Client, Quote, and Job
        xtremecleans_log('Preparing Jobber data from order payload. Payload keys: ' . implode(', ', array_keys($order_payload)), 'info');
        $jobber_data = $this->prepare_jobber_order_data($order_payload);
        
        // Log what data will be sent to Jobber
        xtremecleans_log('Jobber Client Data: ' . wp_json_encode($jobber_data['client']), 'info');
        xtremecleans_log('Jobber Quote Data: ' . wp_json_encode($jobber_data['quote']), 'info');
        xtremecleans_log('Jobber Job Data: ' . wp_json_encode($jobber_data['job']), 'info');
        
        $results = array(
            'client' => array('sent' => false, 'message' => ''),
            'quote' => array('sent' => false, 'message' => ''),
            'job' => array('sent' => false, 'message' => ''),
        );
        
        // Step 1: Create Client using GraphQL
        if (!empty($jobber_data['client'])) {
            xtremecleans_log('Attempting to create Jobber Client with data: ' . wp_json_encode($jobber_data['client']), 'info');
            
            // Build GraphQL mutation for client creation
            $client_mutation = $this->build_client_create_mutation($jobber_data['client']);
            $client_response = $api->graphql_mutation($client_mutation);
            if (is_wp_error($client_response)) {
                $error_details = $client_response->get_error_data();
                $error_msg = $client_response->get_error_message();
                if (isset($error_details['body'])) {
                    $error_msg .= ' | Response: ' . substr($error_details['body'], 0, 200);
                }
                xtremecleans_log('Jobber Client API error: ' . $error_msg, 'error');
                $results['client'] = array(
                    'sent' => false,
                    'message' => $error_msg,
                    'error_data' => $error_details,
                );
            } else {
                // Parse GraphQL response
                $client_id = null;
                if (isset($client_response['data']['clientCreate']['client']['id'])) {
                    $client_id = $client_response['data']['clientCreate']['client']['id'];
                }
                
                if ($client_id) {
                    xtremecleans_log('Jobber Client created successfully. Client ID: ' . $client_id, 'info');
                    $results['client'] = array(
                        'sent' => true,
                        'message' => __('Client created in Jobber.', 'xtremecleans'),
                        'response' => array('id' => $client_id, 'data' => $client_response['data']['clientCreate']['client']),
                    );
                    // Store client ID and property ID for quote and job
                    $jobber_data['quote']['client_id'] = $client_id;
                    $jobber_data['job']['client_id'] = $client_id;
                    
                    // Get property ID if available
                    $property_id = null;
                    if (isset($client_response['data']['clientCreate']['client']['clientProperties']['nodes'][0])) {
                        $property_node = $client_response['data']['clientCreate']['client']['clientProperties']['nodes'][0];
                        $property_id = isset($property_node['id']) ? $property_node['id'] : null;
                    }
                    
                    if ($property_id) {
                        $jobber_data['quote']['property_id'] = $property_id;
                        $jobber_data['job']['property_id'] = $property_id;
                        xtremecleans_log('Property ID stored: ' . $property_id, 'info');
                    } else {
                        xtremecleans_log('WARNING: Property ID not found in client response. Quote/Job may fail.', 'warning');
                        // Log full client properties structure for debugging
                        if (isset($client_response['data']['clientCreate']['client']['clientProperties'])) {
                            xtremecleans_log('Client Properties structure: ' . wp_json_encode($client_response['data']['clientCreate']['client']['clientProperties']), 'info');
                        }
                    }
                    
                    xtremecleans_log('Client ID stored: ' . $client_id . ', Property ID: ' . ($property_id ? $property_id : 'NOT FOUND'), 'info');
                } else {
                    xtremecleans_log('WARNING: Client created but no ID in response: ' . wp_json_encode($client_response), 'error');
                    $results['client'] = array(
                        'sent' => false,
                        'message' => __('Client creation response missing ID.', 'xtremecleans'),
                        'response' => $client_response,
                    );
                }
            }
        } else {
            xtremecleans_log('WARNING: Client data is empty, cannot create Jobber client', 'error');
        }
        
        // Step 2: Create Quote using GraphQL
        // Check if quote data exists and has client_id
        xtremecleans_log('Checking Quote creation conditions...', 'info');
        xtremecleans_log('Quote data exists: ' . (!empty($jobber_data['quote']) ? 'YES' : 'NO'), 'info');
        xtremecleans_log('Quote client_id: ' . (isset($jobber_data['quote']['client_id']) ? $jobber_data['quote']['client_id'] : 'MISSING'), 'info');
        
        if (!empty($jobber_data['quote']) && !empty($jobber_data['quote']['client_id'])) {
            xtremecleans_log('Quote creation conditions met. Proceeding with Quote creation...', 'info');
            
            // If property ID is missing, try to fetch it from client
            if (empty($jobber_data['quote']['property_id']) && !empty($jobber_data['quote']['client_id'])) {
                xtremecleans_log('Property ID missing for Quote. Attempting to fetch from client...', 'info');
                $property_id = $this->get_client_property_id($api, $jobber_data['quote']['client_id']);
                if ($property_id) {
                    $jobber_data['quote']['property_id'] = $property_id;
                    xtremecleans_log('Property ID fetched from client: ' . $property_id, 'info');
                } else {
                    xtremecleans_log('WARNING: Could not fetch property ID from client. Quote may still work without it.', 'warning');
                }
            }
            
            // Check if line items exist
            if (empty($jobber_data['quote']['line_items']) || !is_array($jobber_data['quote']['line_items']) || count($jobber_data['quote']['line_items']) === 0) {
                xtremecleans_log('WARNING: Quote has no line items. Adding default line item.', 'warning');
                // Add a default line item if none exist
                $jobber_data['quote']['line_items'] = array(
                    array(
                        'name' => 'Service',
                        'quantity' => 1,
                        'unit_price' => isset($jobber_data['quote']['deposit_amount']) ? floatval($jobber_data['quote']['deposit_amount']) : 0.01,
                    )
                );
            }
            
            xtremecleans_log('Attempting to create Jobber Quote with data: ' . wp_json_encode($jobber_data['quote']), 'info');
            
            // Build GraphQL mutation for quote creation
            $quote_mutation = $this->build_quote_create_mutation($jobber_data['quote']);
            xtremecleans_log('=== QUOTE CREATION MUTATION ===', 'info');
            xtremecleans_log('Quote Mutation (Full): ' . $quote_mutation, 'info');
            xtremecleans_log('Quote Data Being Sent: ' . wp_json_encode($jobber_data['quote']), 'info');
            $quote_response = $api->graphql_mutation($quote_mutation);
            xtremecleans_log('Quote Response Type: ' . (is_wp_error($quote_response) ? 'WP_Error' : 'Array'), 'info');
            if (is_wp_error($quote_response)) {
                $error_code = $quote_response->get_error_code();
                $error_details = $quote_response->get_error_data();
                $error_msg = $quote_response->get_error_message();
                
                // Log error code to distinguish between top-level errors and other errors
                xtremecleans_log('Quote API Error Code: ' . $error_code, 'error');
                
                // Check if raw body is available in error data
                if (isset($error_details['raw_body'])) {
                    xtremecleans_log('Raw response body from error: ' . $error_details['raw_body'], 'error');
                } elseif (isset($error_details['body'])) {
                    xtremecleans_log('Response body from error: ' . substr($error_details['body'], 0, 200), 'error');
                }
                
                // Log full error details
                if (isset($error_details['errors'])) {
                    xtremecleans_log('Top-level GraphQL errors from response: ' . wp_json_encode($error_details['errors']), 'error');
                }
                
                xtremecleans_log('Jobber Quote API error: ' . $error_msg, 'error');
                $this->log_jobber_debug_pack('quoteCreate', $quote_mutation, isset($error_details['body']) ? array('body' => $error_details['body'], 'errors' => isset($error_details['errors']) ? $error_details['errors'] : array()) : $error_details);
                $results['quote'] = array(
                    'sent' => false,
                    'message' => $error_msg,
                    'error_data' => $error_details,
                );
            } else {
                // Parse GraphQL response
                $quote_id = null;
                
                // Log full response for debugging
                xtremecleans_log('Quote creation response: ' . wp_json_encode($quote_response), 'info');
                
                // Check if response structure is valid
                if (!isset($quote_response['data']['quoteCreate'])) {
                    xtremecleans_log('ERROR: Invalid response structure. Missing quoteCreate in data. Full response: ' . wp_json_encode($quote_response), 'error');
                    $this->log_jobber_debug_pack('quoteCreate', $quote_mutation, $quote_response);
                    $results['quote'] = array(
                        'sent' => false,
                        'message' => __('Quote creation failed: Invalid response structure from Jobber API.', 'xtremecleans'),
                        'response' => $quote_response,
                    );
                } else {
                    $quote_create_data = $quote_response['data']['quoteCreate'];
                    
                    // Check for userErrors first
                    if (isset($quote_create_data['userErrors']) && !empty($quote_create_data['userErrors'])) {
                        $error_messages = array();
                        foreach ($quote_create_data['userErrors'] as $error) {
                            $error_msg_line = isset($error['message']) ? $error['message'] : 'Unknown error';
                            $error_path = isset($error['path']) ? ' (path: ' . implode(' -> ', $error['path']) . ')' : '';
                            $error_messages[] = $error_msg_line . $error_path;
                        }
                        $error_msg = implode('; ', $error_messages);
                        xtremecleans_log('Jobber Quote creation userErrors: ' . $error_msg, 'error');
                        xtremecleans_log('Full userErrors: ' . wp_json_encode($quote_create_data['userErrors']), 'error');
                        $this->log_jobber_debug_pack('quoteCreate', $quote_mutation, $quote_response);
                        $results['quote'] = array(
                            'sent' => false,
                            'message' => __('Quote creation failed: ', 'xtremecleans') . $error_msg,
                            'response' => $quote_response,
                        );
                    } 
                    // Check if quote is null (silent failure) - CRITICAL CHECK
                    elseif (isset($quote_create_data['quote']) && $quote_create_data['quote'] === null) {
                        xtremecleans_log('ERROR: Quote creation returned null with no userErrors. This indicates a silent failure.', 'error');
                        xtremecleans_log('CRITICAL: Full quote response: ' . wp_json_encode($quote_response), 'error');
                        xtremecleans_log('CRITICAL: Check logs above for top-level GraphQL errors (response.errors array).', 'error');
                        xtremecleans_log('Possible causes: Account permissions, missing features, or API-side issue.', 'error');
                        $this->log_jobber_debug_pack('quoteCreate', $quote_mutation, $quote_response);
                        $results['quote'] = array(
                            'sent' => false,
                            'message' => __('Quote creation failed: Quote returned null with no errors. Check logs for top-level GraphQL errors.', 'xtremecleans'),
                            'response' => $quote_response,
                        );
                    }
                    // Check if quote key doesn't exist or quote is missing (shouldn't happen but check anyway)
                    elseif (!isset($quote_create_data['quote'])) {
                        xtremecleans_log('ERROR: Quote creation response missing quote key. Full response: ' . wp_json_encode($quote_response), 'error');
                        $this->log_jobber_debug_pack('quoteCreate', $quote_mutation, $quote_response);
                        $results['quote'] = array(
                            'sent' => false,
                            'message' => __('Quote creation failed: Invalid response structure.', 'xtremecleans'),
                            'response' => $quote_response,
                        );
                    }
                    // Check if quote exists and has ID
                    elseif (isset($quote_create_data['quote']['id']) && !empty($quote_create_data['quote']['id'])) {
                        $quote_id = $quote_create_data['quote']['id'];
                        $quote_number = isset($quote_create_data['quote']['quoteNumber']) ? $quote_create_data['quote']['quoteNumber'] : '';
                        $quote_status = isset($quote_create_data['quote']['quoteStatus']) ? $quote_create_data['quote']['quoteStatus'] : '';
                        $quote_title = isset($quote_create_data['quote']['title']) ? $quote_create_data['quote']['title'] : '';
                        
                        xtremecleans_log('Jobber Quote created successfully. Quote ID: ' . $quote_id . ', Quote Number: ' . $quote_number . ', Status: ' . $quote_status, 'info');
                        $results['quote'] = array(
                            'sent' => true,
                            'message' => __('Quote created in Jobber.', 'xtremecleans'),
                            'response' => array(
                                'id' => $quote_id,
                                'quoteNumber' => $quote_number,
                                'quoteStatus' => $quote_status,
                                'title' => $quote_title,
                                'data' => $quote_create_data['quote']
                            ),
                        );
                        // Store quote ID for job
                        $jobber_data['job']['quote_id'] = $quote_id;
                        xtremecleans_log('Quote ID stored: ' . $quote_id . ' (Quote #' . $quote_number . ')', 'info');
                    } 
                    // Quote exists but no ID
                    elseif (isset($quote_create_data['quote']) && is_array($quote_create_data['quote'])) {
                        xtremecleans_log('WARNING: Quote object exists but no ID found. Quote data: ' . wp_json_encode($quote_create_data['quote']), 'error');
                        $results['quote'] = array(
                            'sent' => false,
                            'message' => __('Quote creation response missing ID.', 'xtremecleans'),
                            'response' => $quote_response,
                        );
                    } 
                    // No quote in response at all
                    else {
                        xtremecleans_log('ERROR: Quote creation response structure unexpected. quoteCreate data: ' . wp_json_encode($quote_create_data), 'error');
                        $results['quote'] = array(
                            'sent' => false,
                            'message' => __('Quote creation failed: Unexpected response structure.', 'xtremecleans'),
                            'response' => $quote_response,
                        );
                    }
                }
            }
        } else {
            if (empty($jobber_data['quote']['client_id'])) {
                xtremecleans_log('ERROR: Cannot create Quote - Client ID is missing. Quote data: ' . wp_json_encode($jobber_data['quote']), 'error');
            } else {
                xtremecleans_log('ERROR: Quote data is empty, cannot create Jobber quote', 'error');
            }
        }
        
        // Step 3: Create Job using GraphQL
        // Check if job data exists and has client_id
        xtremecleans_log('Checking Job creation conditions...', 'info');
        xtremecleans_log('Job data exists: ' . (!empty($jobber_data['job']) ? 'YES' : 'NO'), 'info');
        xtremecleans_log('Job client_id: ' . (isset($jobber_data['job']['client_id']) ? $jobber_data['job']['client_id'] : 'MISSING'), 'info');
        
        if (!empty($jobber_data['job']) && !empty($jobber_data['job']['client_id'])) {
            xtremecleans_log('Job creation conditions met. Proceeding with Job creation...', 'info');
            // Check if line items exist
            if (empty($jobber_data['job']['line_items']) || !is_array($jobber_data['job']['line_items']) || count($jobber_data['job']['line_items']) === 0) {
                xtremecleans_log('WARNING: Job has no line items. Adding default line item.', 'warning');
                // Add a default line item if none exist
                $jobber_data['job']['line_items'] = array(
                    array(
                        'name' => 'Service',
                        'quantity' => 1,
                        'unit_price' => 0.01,
                    )
                );
            }
            
            // Job requires propertyId - try multiple sources
            if (empty($jobber_data['job']['property_id'])) {
                // First try from quote
                if (!empty($jobber_data['quote']['property_id'])) {
                    $jobber_data['job']['property_id'] = $jobber_data['quote']['property_id'];
                    xtremecleans_log('Using property ID from quote for job: ' . $jobber_data['job']['property_id'], 'info');
                } 
                // If still missing, fetch from client
                elseif (!empty($jobber_data['job']['client_id'])) {
                    xtremecleans_log('Property ID missing for Job. Attempting to fetch from client...', 'info');
                    $property_id = $this->get_client_property_id($api, $jobber_data['job']['client_id']);
                    if ($property_id) {
                        $jobber_data['job']['property_id'] = $property_id;
                        xtremecleans_log('Property ID fetched from client: ' . $property_id, 'info');
                    } else {
                        xtremecleans_log('ERROR: Property ID is required for Job creation but could not be found!', 'error');
                        xtremecleans_log('Job creation will be skipped. Client ID: ' . $jobber_data['job']['client_id'], 'error');
                        // Don't proceed with job creation if property ID is missing
                        $results['job'] = array(
                            'sent' => false,
                            'message' => __('Job creation failed: Property ID is required but could not be found. Please ensure the client has a property associated.', 'xtremecleans'),
                        );
                    }
                } else {
                    xtremecleans_log('ERROR: Job creation cannot proceed - both property_id and client_id are missing!', 'error');
                    $results['job'] = array(
                        'sent' => false,
                        'message' => __('Job creation failed: Client ID and Property ID are both missing.', 'xtremecleans'),
                    );
                }
            }
            
            // Only proceed with job creation if we have property_id
            if (!empty($jobber_data['job']['property_id'])) {
                xtremecleans_log('Attempting to create Jobber Job with data: ' . wp_json_encode($jobber_data['job']), 'info');
                
                // Build GraphQL mutation for job creation
                $job_mutation = $this->build_job_create_mutation($jobber_data['job']);
                xtremecleans_log('=== JOB CREATION MUTATION ===', 'info');
                xtremecleans_log('Job Mutation (Full): ' . $job_mutation, 'info');
                xtremecleans_log('Job Data Being Sent: ' . wp_json_encode($jobber_data['job']), 'info');
                $job_response = $api->graphql_mutation($job_mutation);
                xtremecleans_log('Job Response Type: ' . (is_wp_error($job_response) ? 'WP_Error' : 'Array'), 'info');
                if (is_wp_error($job_response)) {
                $error_code = $job_response->get_error_code();
                $error_details = $job_response->get_error_data();
                $error_msg = $job_response->get_error_message();
                
                // Log error code to distinguish between top-level errors and other errors
                xtremecleans_log('Job API Error Code: ' . $error_code, 'error');
                
                // Check if raw body is available in error data
                if (isset($error_details['raw_body'])) {
                    xtremecleans_log('Raw response body from error: ' . $error_details['raw_body'], 'error');
                } elseif (isset($error_details['body'])) {
                    xtremecleans_log('Response body from error: ' . substr($error_details['body'], 0, 200), 'error');
                }
                
                // Log full error details
                if (isset($error_details['errors'])) {
                    xtremecleans_log('Top-level GraphQL errors from response: ' . wp_json_encode($error_details['errors']), 'error');
                }
                
                xtremecleans_log('Jobber Job API error: ' . $error_msg, 'error');
                $this->log_jobber_debug_pack('jobCreate', $job_mutation, isset($error_details['body']) ? array('body' => $error_details['body'], 'errors' => isset($error_details['errors']) ? $error_details['errors'] : array()) : $error_details);
                $results['job'] = array(
                    'sent' => false,
                    'message' => $error_msg,
                    'error_data' => $error_details,
                );
            } else {
                // Parse GraphQL response
                $job_id = null;
                
                // Log full response for debugging
                xtremecleans_log('Job creation response: ' . wp_json_encode($job_response), 'info');
                
                // Check for userErrors first
                if (isset($job_response['data']['jobCreate']['userErrors']) && !empty($job_response['data']['jobCreate']['userErrors'])) {
                    $error_messages = array();
                    foreach ($job_response['data']['jobCreate']['userErrors'] as $error) {
                        $error_msg_line = isset($error['message']) ? $error['message'] : 'Unknown error';
                        $error_path = isset($error['path']) ? ' (path: ' . implode(' -> ', $error['path']) . ')' : '';
                        $error_messages[] = $error_msg_line . $error_path;
                    }
                    $error_msg = implode('; ', $error_messages);
                    xtremecleans_log('Jobber Job creation userErrors: ' . $error_msg, 'error');
                    xtremecleans_log('Full userErrors: ' . wp_json_encode($job_response['data']['jobCreate']['userErrors']), 'error');
                    $this->log_jobber_debug_pack('jobCreate', $job_mutation, $job_response);
                    $results['job'] = array(
                        'sent' => false,
                        'message' => __('Job creation failed: ', 'xtremecleans') . $error_msg,
                        'response' => $job_response,
                    );
                } 
                // Check if job is null (silent failure) - CRITICAL CHECK
                elseif (isset($job_response['data']['jobCreate']['job']) && $job_response['data']['jobCreate']['job'] === null) {
                    xtremecleans_log('ERROR: Job creation returned null with no userErrors. This indicates a silent failure.', 'error');
                    xtremecleans_log('CRITICAL: Full job response: ' . wp_json_encode($job_response), 'error');
                    xtremecleans_log('CRITICAL: Check logs above for top-level GraphQL errors (response.errors array).', 'error');
                    xtremecleans_log('Possible causes: Account permissions, missing features, or API-side issue.', 'error');
                    $this->log_jobber_debug_pack('jobCreate', $job_mutation, $job_response);
                    $results['job'] = array(
                        'sent' => false,
                        'message' => __('Job creation failed: Job returned null with no errors. Check logs for top-level GraphQL errors.', 'xtremecleans'),
                        'response' => $job_response,
                    );
                }
                // Check if job exists and has ID
                elseif (isset($job_response['data']['jobCreate']['job']['id']) && !empty($job_response['data']['jobCreate']['job']['id'])) {
                    $job_id = $job_response['data']['jobCreate']['job']['id'];
                    $job_number = isset($job_response['data']['jobCreate']['job']['jobNumber']) ? $job_response['data']['jobCreate']['job']['jobNumber'] : '';
                    $job_status = isset($job_response['data']['jobCreate']['job']['jobStatus']) ? $job_response['data']['jobCreate']['job']['jobStatus'] : '';
                    $job_type = isset($job_response['data']['jobCreate']['job']['jobType']) ? $job_response['data']['jobCreate']['job']['jobType'] : '';
                    $job_title = isset($job_response['data']['jobCreate']['job']['title']) ? $job_response['data']['jobCreate']['job']['title'] : '';
                    
                    xtremecleans_log('Jobber Job created successfully. Job ID: ' . $job_id . ', Job Number: ' . $job_number . ', Status: ' . $job_status . ', Type: ' . $job_type, 'info');
                    $results['job'] = array(
                        'sent' => true,
                        'message' => __('Job created in Jobber.', 'xtremecleans'),
                        'response' => array(
                            'id' => $job_id,
                            'jobNumber' => $job_number,
                            'jobStatus' => $job_status,
                            'jobType' => $job_type,
                            'title' => $job_title,
                            'data' => $job_response['data']['jobCreate']['job']
                        ),
                    );
                    xtremecleans_log('Job ID stored: ' . $job_id . ' (Job #' . $job_number . ')', 'info');
                } 
                // Check if job key doesn't exist or job is missing
                elseif (!isset($job_response['data']['jobCreate']['job'])) {
                    xtremecleans_log('ERROR: Job creation response missing job key. Full response: ' . wp_json_encode($job_response), 'error');
                    $this->log_jobber_debug_pack('jobCreate', $job_mutation, $job_response);
                    $results['job'] = array(
                        'sent' => false,
                        'message' => __('Job creation failed: Invalid response structure.', 'xtremecleans'),
                        'response' => $job_response,
                    );
                } else {
                    xtremecleans_log('WARNING: Job created but no ID in response. Full response: ' . wp_json_encode($job_response), 'error');
                    $results['job'] = array(
                        'sent' => false,
                        'message' => __('Job creation response missing ID.', 'xtremecleans'),
                        'response' => $job_response,
                    );
                }
            }
            } else {
                xtremecleans_log('Skipping Job creation - Property ID is required but missing.', 'error');
            }
        } else {
            if (empty($jobber_data['job']['client_id'])) {
                xtremecleans_log('WARNING: Cannot create Job - Client ID is missing', 'error');
                $results['job'] = array(
                    'sent' => false,
                    'message' => __('Job creation failed: Client ID is missing.', 'xtremecleans'),
                );
            } else {
                xtremecleans_log('WARNING: Job data is empty, cannot create Jobber job', 'error');
                $results['job'] = array(
                    'sent' => false,
                    'message' => __('Job creation failed: Job data is empty.', 'xtremecleans'),
                );
            }
        }
        
        // Determine overall success - CRITICAL: Quote and Job MUST both be sent successfully
        // Client creation is a prerequisite, but success requires Quote AND Job
        $client_sent = $results['client']['sent'];
        $quote_sent = $results['quote']['sent'] && !empty($results['quote']['response']['id']);
        $job_sent = $results['job']['sent'] && !empty($results['job']['response']['id']);
        
        // Overall success requires: Client created AND (Quote created OR Job created)
        // But ideally both Quote and Job should be created
        $overall_sent = $client_sent && ($quote_sent || $job_sent);
        
        // Build detailed messages
        $messages = array();
        $error_messages = array();
        
        if ($results['client']['sent']) {
            $messages[] = __('Client created in Jobber.', 'xtremecleans');
        } else {
            $error_messages[] = __('Client creation failed: ', 'xtremecleans') . ($results['client']['message'] ?? 'Unknown error');
        }
        
        if ($quote_sent) {
            $quote_number = isset($results['quote']['response']['quoteNumber']) ? $results['quote']['response']['quoteNumber'] : '';
            $quote_msg = __('Quote created in Jobber.', 'xtremecleans');
            if ($quote_number) {
                $quote_msg .= ' (Quote #' . $quote_number . ')';
            }
            $messages[] = $quote_msg;
        } else {
            $error_messages[] = __('Quote creation failed: ', 'xtremecleans') . ($results['quote']['message'] ?? 'Unknown error');
        }
        
        if ($job_sent) {
            $job_number = isset($results['job']['response']['jobNumber']) ? $results['job']['response']['jobNumber'] : '';
            $job_msg = __('Job created in Jobber.', 'xtremecleans');
            if ($job_number) {
                $job_msg .= ' (Job #' . $job_number . ')';
            }
            $messages[] = $job_msg;
        } else {
            $error_messages[] = __('Job creation failed: ', 'xtremecleans') . ($results['job']['message'] ?? 'Unknown error');
        }
        
        // Create comprehensive message
        if ($overall_sent) {
            $overall_message = implode(' ', $messages);
            if (!empty($error_messages)) {
                $overall_message .= ' ' . __('Note: Some items failed: ', 'xtremecleans') . implode('; ', $error_messages);
            }
        } else {
            $overall_message = __('Failed to sync to Jobber. ', 'xtremecleans') . implode(' ', $error_messages);
        }
        
        // Log final summary with detailed information
        xtremecleans_log('=== JOBBER SYNC SUMMARY ===', 'info');
        xtremecleans_log('Client sent: ' . ($client_sent ? 'YES' : 'NO'), $client_sent ? 'info' : 'error');
        if (!$client_sent && !empty($results['client']['message'])) {
            xtremecleans_log('Client error: ' . $results['client']['message'], 'error');
        }
        
        xtremecleans_log('Quote sent: ' . ($quote_sent ? 'YES' : 'NO'), $quote_sent ? 'info' : 'error');
        if (!$quote_sent) {
            xtremecleans_log('Quote status: sent=' . ($results['quote']['sent'] ? 'true' : 'false') . ', has_id=' . (!empty($results['quote']['response']['id']) ? 'true' : 'false'), 'error');
            if (!empty($results['quote']['message'])) {
                xtremecleans_log('Quote error: ' . $results['quote']['message'], 'error');
            }
            // Check for null quote in response
            if (isset($results['quote']['response']['data']['quote']) && $results['quote']['response']['data']['quote'] === null) {
                xtremecleans_log('CRITICAL: Quote response contains null quote - check for top-level GraphQL errors!', 'error');
            }
        }
        
        xtremecleans_log('Job sent: ' . ($job_sent ? 'YES' : 'NO'), $job_sent ? 'info' : 'error');
        if (!$job_sent) {
            xtremecleans_log('Job status: sent=' . ($results['job']['sent'] ? 'true' : 'false') . ', has_id=' . (!empty($results['job']['response']['id']) ? 'true' : 'false'), 'error');
            if (!empty($results['job']['message'])) {
                xtremecleans_log('Job error: ' . $results['job']['message'], 'error');
            }
            // Check for null job in response
            if (isset($results['job']['response']['data']['job']) && $results['job']['response']['data']['job'] === null) {
                xtremecleans_log('CRITICAL: Job response contains null job - check for top-level GraphQL errors!', 'error');
            }
        }
        
        xtremecleans_log('Overall success: ' . ($overall_sent ? 'YES' : 'NO'), $overall_sent ? 'info' : 'error');
        if (!$overall_sent) {
            xtremecleans_log('CRITICAL: Sync marked as FAILED. Full results: ' . wp_json_encode($results), 'error');
            xtremecleans_log('To debug: Check logs above for top-level GraphQL errors or userErrors.', 'error');
        } else {
            xtremecleans_log('SUCCESS: Sync completed. Check Jobber dashboard to verify Quote and Job were created.', 'info');
        }
        xtremecleans_log('=== END JOBBER SYNC ===', 'info');
        
        return array(
            'sent' => $overall_sent,
            'message' => $overall_message,
            'results' => $results,
            'auth_url' => $auth_url,
        );
    }
    
    /**
     * Update order with Jobber sync status
     *
     * @since 1.0.0
     * @param int   $order_id      Order ID
     * @param array $jobber_result Jobber sync result
     */
    private function update_order_jobber_status($order_id, $jobber_result) {
        global $wpdb;
        $table_name = $wpdb->prefix . 'xtremecleans_orders';
        
        $update_data = array(
            'jobber_sync_status' => $jobber_result['sent'] ? 'success' : 'failed',
            'jobber_sync_message' => $jobber_result['message'],
            'jobber_sync_attempted_at' => current_time('mysql'),
            'updated_at' => current_time('mysql'),
        );
        
        // Store Jobber IDs and quote details if available
        if (!empty($jobber_result['results'])) {
            if (!empty($jobber_result['results']['client']['response']['id'])) {
                $update_data['jobber_client_id'] = $jobber_result['results']['client']['response']['id'];
            }
            if (!empty($jobber_result['results']['quote']['response']['id'])) {
                $update_data['jobber_quote_id'] = $jobber_result['results']['quote']['response']['id'];
                
                // Store quote number and status in payload for reference
                $payload = json_decode($wpdb->get_var($wpdb->prepare(
                    "SELECT payload FROM {$table_name} WHERE id = %d",
                    $order_id
                )), true);
                
                if (is_array($payload)) {
                    if (!isset($payload['jobber'])) {
                        $payload['jobber'] = array();
                    }
                    $payload['jobber']['quote'] = array(
                        'id' => $jobber_result['results']['quote']['response']['id'],
                        'quoteNumber' => isset($jobber_result['results']['quote']['response']['quoteNumber']) ? $jobber_result['results']['quote']['response']['quoteNumber'] : '',
                        'quoteStatus' => isset($jobber_result['results']['quote']['response']['quoteStatus']) ? $jobber_result['results']['quote']['response']['quoteStatus'] : '',
                        'title' => isset($jobber_result['results']['quote']['response']['title']) ? $jobber_result['results']['quote']['response']['title'] : '',
                    );
                    $update_data['payload'] = wp_json_encode($payload);
                }
            }
            if (!empty($jobber_result['results']['job']['response']['id'])) {
                $update_data['jobber_job_id'] = $jobber_result['results']['job']['response']['id'];
                
                // Store job number and status in payload for reference
                $payload = json_decode($wpdb->get_var($wpdb->prepare(
                    "SELECT payload FROM {$table_name} WHERE id = %d",
                    $order_id
                )), true);
                
                if (is_array($payload)) {
                    if (!isset($payload['jobber'])) {
                        $payload['jobber'] = array();
                    }
                    $payload['jobber']['job'] = array(
                        'id' => $jobber_result['results']['job']['response']['id'],
                        'jobNumber' => isset($jobber_result['results']['job']['response']['jobNumber']) ? $jobber_result['results']['job']['response']['jobNumber'] : '',
                        'jobStatus' => isset($jobber_result['results']['job']['response']['jobStatus']) ? $jobber_result['results']['job']['response']['jobStatus'] : '',
                        'jobType' => isset($jobber_result['results']['job']['response']['jobType']) ? $jobber_result['results']['job']['response']['jobType'] : '',
                        'title' => isset($jobber_result['results']['job']['response']['title']) ? $jobber_result['results']['job']['response']['title'] : '',
                    );
                    $update_data['payload'] = wp_json_encode($payload);
                }
            }
        }
        
        // Build format array dynamically based on update_data
        $format = array();
        foreach ($update_data as $key => $value) {
            if ($key === 'payload') {
                $format[] = '%s'; // JSON string
            } elseif ($key === 'jobber_sync_attempted_at' || $key === 'updated_at') {
                $format[] = '%s'; // DateTime string
            } else {
                $format[] = '%s'; // Most fields are strings
            }
        }
        
        $wpdb->update(
            $table_name,
            $update_data,
            array('id' => $order_id),
            $format,
            array('%d')
        );
    }
    
    /**
     * Prepare Jobber order data for API
     * Creates Client, Quote, and Job data structures
     *
     * @since 1.0.0
     * @param array $order_payload Order data from frontend
     * @return array Jobber data structure
     */
    private function prepare_jobber_order_data($order_payload) {
        // Log received payload structure for debugging
        xtremecleans_log('prepare_jobber_order_data called. Payload structure: ' . wp_json_encode(array(
            'has_customer' => isset($order_payload['customer']),
            'has_services' => isset($order_payload['services']),
            'has_appointment' => isset($order_payload['appointment']),
            'has_totals' => isset($order_payload['totals']),
            'has_zone' => isset($order_payload['zone']),
            'customer_keys' => isset($order_payload['customer']) ? array_keys($order_payload['customer']) : array(),
            'services_count' => isset($order_payload['services']) ? count($order_payload['services']) : 0,
        )), 'info');
        
        $customer = isset($order_payload['customer']) ? $order_payload['customer'] : array();
        $services = isset($order_payload['services']) ? $order_payload['services'] : array();
        $appointment = isset($order_payload['appointment']) ? $order_payload['appointment'] : array();
        $totals = isset($order_payload['totals']) ? $order_payload['totals'] : array();
        $zone = isset($order_payload['zone']) ? $order_payload['zone'] : array();
        
        // Prepare Client data
        $client_data = array(
            'first_name' => isset($customer['first_name']) ? sanitize_text_field($customer['first_name']) : '',
            'last_name' => isset($customer['last_name']) ? sanitize_text_field($customer['last_name']) : '',
            'email' => isset($customer['email']) ? sanitize_email($customer['email']) : '',
            'phone' => isset($customer['phone']) ? sanitize_text_field($customer['phone']) : '',
        );
        
        // Add alternate phone if available
        if (!empty($customer['alt_phone'])) {
            $client_data['alt_phone'] = sanitize_text_field($customer['alt_phone']);
        }
        
        // Add address if available
        if (!empty($customer['address1'])) {
            $client_data['address'] = array(
                'line1' => sanitize_text_field($customer['address1']),
                'line2' => !empty($customer['address2']) ? sanitize_text_field($customer['address2']) : '',
                'city' => !empty($customer['city']) ? sanitize_text_field($customer['city']) : '',
                'state' => !empty($customer['state']) ? sanitize_text_field($customer['state']) : '',
                'postal_code' => !empty($customer['zip_code']) ? sanitize_text_field($customer['zip_code']) : '',
            );
        }
        
        // Prepare Quote data (with deposit request)
        $quote_data = array(
            'name' => 'Online Booking - ' . date('M d, Y'),
            'line_items' => array(),
        );
        
        // Add services as line items with full service details
        if (!empty($services)) {
            foreach ($services as $service) {
                if (isset($service['item']) && isset($service['amount'])) {
                    // Build descriptive line item name with service name, item, and type
                    $line_item_parts = array();
                    
                    // Add service name if available
                    if (isset($service['service']) && !empty($service['service'])) {
                        $line_item_parts[] = sanitize_text_field($service['service']);
                    }
                    
                    // Add item name
                    $line_item_parts[] = sanitize_text_field($service['item']);
                    
                    // Add type (clean/protect/deodorize) if available
                    if (isset($service['type']) && !empty($service['type'])) {
                        $line_item_parts[] = '(' . sanitize_text_field(ucfirst($service['type'])) . ')';
                    }
                    
                    // Add quantity to name if greater than 1 for clarity
                    $quantity = isset($service['quantity']) ? floatval($service['quantity']) : 1;
                    $line_item_name = implode(' - ', $line_item_parts);
                    if ($quantity > 1) {
                        $line_item_name .= ' (Qty: ' . $quantity . ')';
                    }
                    
                    // Calculate unit price (amount / quantity)
                    $total_amount = floatval($service['amount']);
                    $unit_price = ($quantity > 0) ? ($total_amount / $quantity) : $total_amount;
                    
                    $quote_data['line_items'][] = array(
                        'name' => $line_item_name,
                        'quantity' => $quantity,
                        'unit_price' => $unit_price,
                    );
                }
            }
        }
        
        // Add service charge/fee if applicable
        if (isset($totals['service_charge']) && floatval($totals['service_charge']) > 0) {
            $quote_data['line_items'][] = array(
                'name' => 'Service Charge',
                'quantity' => 1,
                'unit_price' => floatval($totals['service_charge']),
            );
        }
        
        // Add zone fee if applicable (check both totals and zone)
        $zone_fee = 0;
        if (isset($totals['zone_fee']) && floatval($totals['zone_fee']) > 0) {
            $zone_fee = floatval($totals['zone_fee']);
        } elseif (isset($zone['service_fee']) && floatval($zone['service_fee']) > 0) {
            $zone_fee = floatval($zone['service_fee']);
        } elseif (isset($zone['service_charge']) && floatval($zone['service_charge']) > 0) {
            $zone_fee = floatval($zone['service_charge']);
        }
        
        if ($zone_fee > 0) {
            $zone_name = isset($zone['zone_name']) ? sanitize_text_field($zone['zone_name']) : 'Zone';
            $quote_data['line_items'][] = array(
                'name' => 'Zone Fee (' . $zone_name . ')',
                'quantity' => 1,
                'unit_price' => $zone_fee,
            );
        }
        
        // Add deposit request
        if (isset($totals['deposit']) && floatval($totals['deposit']) > 0) {
            $quote_data['deposit_amount'] = floatval($totals['deposit']);
        }
        
        // Quote total (for Jobber deposit cap: deposit must be <= total / their limit)
        if (isset($totals['grand_total']) && floatval($totals['grand_total']) > 0) {
            $quote_data['total'] = floatval($totals['grand_total']);
        }
        
        // Prepare Job data
        $job_data = array(
            'name' => 'Online Booking - ' . (isset($appointment['date']) ? sanitize_text_field($appointment['date']) : date('M d, Y')),
            'line_items' => $quote_data['line_items'], // Same line items as quote
        );
        
        // Add appointment date/time
        if (!empty($appointment['date']) && !empty($appointment['time'])) {
            // Parse appointment date and time
            $appointment_datetime = $this->parse_appointment_datetime($appointment['date'], $appointment['time']);
            if ($appointment_datetime) {
                $job_data['start_at'] = $appointment_datetime;
            }
        }
        
        // Add notes to job (service details + zone info + customer instructions + additional details)
        $job_notes = array();
        
        // Add service details summary at the top
        if (!empty($services) && is_array($services)) {
            $service_summary = array();
            $service_summary[] = '=== SERVICES ===';
            
            // Group services by service name for better readability
            $services_by_name = array();
            foreach ($services as $service) {
                if (isset($service['service']) && !empty($service['service'])) {
                    $service_name = sanitize_text_field($service['service']);
                    if (!isset($services_by_name[$service_name])) {
                        $services_by_name[$service_name] = array();
                    }
                    $services_by_name[$service_name][] = $service;
                }
            }
            
            // Build service summary
            foreach ($services_by_name as $service_name => $service_items) {
                $service_summary[] = $service_name . ':';
                foreach ($service_items as $service) {
                    $item_name = isset($service['item']) ? sanitize_text_field($service['item']) : 'Item';
                    $type = isset($service['type']) ? sanitize_text_field(ucfirst($service['type'])) : '';
                    $quantity = isset($service['quantity']) ? floatval($service['quantity']) : 1;
                    $amount = isset($service['amount']) ? '$' . number_format(floatval($service['amount']), 2) : '';
                    
                    $service_line = '  • ' . $item_name;
                    if (!empty($type)) {
                        $service_line .= ' (' . $type . ')';
                    }
                    $service_line .= ' - Qty: ' . $quantity;
                    if (!empty($amount)) {
                        $service_line .= ' - ' . $amount;
                    }
                    $service_summary[] = $service_line;
                }
            }
            
            if (count($service_summary) > 1) {
                $job_notes[] = implode("\n", $service_summary);
            }
        }
        
        // Add zone information with all details
        if (!empty($zone)) {
            $zone_details = array();
            if (isset($zone['zone_name']) && !empty($zone['zone_name'])) {
                $zone_details[] = 'Zone: ' . sanitize_text_field($zone['zone_name']);
            }
            if (isset($zone['zone_area']) && !empty($zone['zone_area'])) {
                $zone_details[] = 'Area: ' . sanitize_text_field($zone['zone_area']);
            }
            if (isset($zone['zip_code']) && !empty($zone['zip_code'])) {
                $zone_details[] = 'ZIP Code: ' . sanitize_text_field($zone['zip_code']);
            }
            
            // Add zone fee info (check multiple possible fields)
            $zone_fee_amount = 0;
            if (isset($totals['zone_fee']) && floatval($totals['zone_fee']) > 0) {
                $zone_fee_amount = floatval($totals['zone_fee']);
            } elseif (isset($zone['service_fee']) && floatval($zone['service_fee']) > 0) {
                $zone_fee_amount = floatval($zone['service_fee']);
            } elseif (isset($zone['service_charge']) && floatval($zone['service_charge']) > 0) {
                $zone_fee_amount = floatval($zone['service_charge']);
            }
            
            if ($zone_fee_amount > 0) {
                $zone_details[] = 'Zone Fee: $' . number_format($zone_fee_amount, 2);
            }
            
            if (!empty($zone_details)) {
                $job_notes[] = implode(' | ', $zone_details);
            }
        }
        
        // Add service charge info if applicable
        if (isset($totals['service_charge']) && floatval($totals['service_charge']) > 0) {
            $job_notes[] = 'Service Charge: $' . number_format(floatval($totals['service_charge']), 2);
        }
        
        // Add total amount info
        if (isset($totals['grand_total']) && floatval($totals['grand_total']) > 0) {
            $job_notes[] = 'Total Amount: $' . number_format(floatval($totals['grand_total']), 2);
        }
        
        // Add deposit info
        if (isset($totals['deposit']) && floatval($totals['deposit']) > 0) {
            $job_notes[] = 'Deposit Paid: $' . number_format(floatval($totals['deposit']), 2);
        }
        
        // Add job duration if available
        if (isset($totals['duration_formatted']) && !empty($totals['duration_formatted'])) {
            $job_notes[] = 'Estimated Duration: ' . sanitize_text_field($totals['duration_formatted']);
        }
        
        // Add customer instructions/special notes
        if (!empty($customer['instructions'])) {
            $instructions = sanitize_textarea_field($customer['instructions']);
            if (!empty($instructions)) {
                $job_notes[] = 'Special Instructions: ' . $instructions;
            }
        }
        
        // Add appointment day name if available
        if (isset($appointment['day_name']) && !empty($appointment['day_name'])) {
            $job_notes[] = 'Appointment Day: ' . sanitize_text_field($appointment['day_name']);
        }
        
        // Combine all notes
        if (!empty($job_notes)) {
            $job_data['notes'] = implode("\n\n", $job_notes);
        }
        
        // Log final prepared data for debugging
        xtremecleans_log('Final Jobber data prepared:', 'info');
        xtremecleans_log('Client fields: ' . implode(', ', array_keys($client_data)), 'info');
        xtremecleans_log('Quote line items count: ' . count($quote_data['line_items']), 'info');
        xtremecleans_log('Job fields: ' . implode(', ', array_keys($job_data)), 'info');
        
        return array(
            'client' => $client_data,
            'quote' => $quote_data,
            'job' => $job_data,
        );
    }
    
    /**
     * Get property ID from client using GraphQL query
     *
     * @since 1.1.0
     * @param XtremeCleans_API $api API instance
     * @param string $client_id Client ID
     * @return string|null Property ID or null
     */
    private function get_client_property_id($api, $client_id) {
        if (empty($client_id)) {
            return null;
        }
        
        $query = 'query GetClientProperties {
  client(id: "' . esc_attr($client_id) . '") {
    id
    clientProperties {
      nodes {
        id
        address {
          street1
          city
          province
          postalCode
        }
      }
    }
  }
}';
        
        xtremecleans_log('Fetching property ID for client: ' . $client_id, 'info');
        $response = $api->graphql_query($query);
        
        if (is_wp_error($response)) {
            xtremecleans_log('Failed to fetch client properties: ' . $response->get_error_message(), 'error');
            return null;
        }
        
        if (isset($response['data']['client']['clientProperties']['nodes'][0]['id'])) {
            $property_id = $response['data']['client']['clientProperties']['nodes'][0]['id'];
            xtremecleans_log('Property ID found: ' . $property_id, 'info');
            return $property_id;
        }
        
        xtremecleans_log('No property found for client: ' . $client_id, 'warning');
        return null;
    }
    
    /**
     * Build GraphQL mutation for creating a client
     *
     * @since 1.1.0
     * @param array $client_data Client data
     * @return string GraphQL mutation string
     */
    private function build_client_create_mutation($client_data) {
        $firstName = isset($client_data['first_name']) ? addslashes($client_data['first_name']) : '';
        $lastName = isset($client_data['last_name']) ? addslashes($client_data['last_name']) : '';
        $email = isset($client_data['email']) ? addslashes($client_data['email']) : '';
        $phone = isset($client_data['phone']) ? addslashes($client_data['phone']) : '';
        
        $address = '';
        if (isset($client_data['address'])) {
            $addr = $client_data['address'];
            $street1 = isset($addr['line1']) ? addslashes($addr['line1']) : '';
            $street2 = isset($addr['line2']) ? addslashes($addr['line2']) : '';
            $city = isset($addr['city']) ? addslashes($addr['city']) : '';
            $province = isset($addr['state']) ? addslashes($addr['state']) : '';
            $postalCode = isset($addr['postal_code']) ? addslashes($addr['postal_code']) : '';
            
            $address = 'properties: [{ address: { street1: "' . $street1 . '"';
            if (!empty($street2)) {
                $address .= ', street2: "' . $street2 . '"';
            }
            $address .= ', city: "' . $city . '"';
            $address .= ', province: "' . $province . '"';
            $address .= ', postalCode: "' . $postalCode . '"';
            $address .= ', country: "USA" } }]';
        }
        
        $mutation = 'mutation CreateClient {
  clientCreate(
    input: {
      firstName: "' . $firstName . '"
      lastName: "' . $lastName . '"';
        
        if (!empty($email)) {
            $mutation .= '
      emails: [{ address: "' . $email . '" }]';
        }
        
        if (!empty($phone) || !empty($client_data['alt_phone'])) {
            $phones = array();
            if (!empty($phone)) {
                $phones[] = '{ number: "' . $phone . '" }';
            }
            if (!empty($client_data['alt_phone'])) {
                $phones[] = '{ number: "' . addslashes(sanitize_text_field($client_data['alt_phone'])) . '" }';
            }
            if (!empty($phones)) {
                $mutation .= '
      phones: [' . implode(', ', $phones) . ']';
            }
        }
        
        if (!empty($address)) {
            $mutation .= '
      ' . $address;
        }
        
        $mutation .= '
    }
  ) {
    client {
      id
      firstName
      lastName
      emails {
        address
      }
      phones {
        number
      }
      clientProperties {
        nodes {
          id
          address {
            street1
            street2
            city
            province
            postalCode
            country
          }
        }
      }
      createdAt
    }
    userErrors {
      message
      path
    }
  }
}';
        
        return $mutation;
    }
    
    /**
     * Build GraphQL mutation for creating a quote
     *
     * @since 1.1.0
     * @param array $quote_data Quote data
     * @return string GraphQL mutation string
     */
    private function build_quote_create_mutation($quote_data) {
        $title = isset($quote_data['name']) ? $this->sanitize_for_graphql_string($quote_data['name']) : 'Online Booking';
        // Ensure IDs are base64 encoded for Jobber GraphQL API
        $clientId = isset($quote_data['client_id']) ? $this->ensure_base64_id($quote_data['client_id']) : '';
        $propertyId = isset($quote_data['property_id']) ? $this->ensure_base64_id($quote_data['property_id']) : '';
        
        // Build line items
        $lineItems = array();
        if (isset($quote_data['line_items']) && is_array($quote_data['line_items'])) {
            foreach ($quote_data['line_items'] as $item) {
                $name = isset($item['name']) ? $this->sanitize_for_graphql_string($item['name']) : 'Item';
                $unitPrice = isset($item['unit_price']) ? floatval($item['unit_price']) : 0;
                $quantity = isset($item['quantity']) ? floatval($item['quantity']) : 1;
                
                $lineItems[] = '{
          name: "' . $name . '"
          unitPrice: ' . $unitPrice . '
          quantity: ' . $quantity . '
          saveToProductsAndServices: false
        }';
            }
        }
        
        $lineItemsStr = '[' . implode(', ', $lineItems) . ']';
        
        $mutation = 'mutation CreateQuote {
  quoteCreate(
    attributes: {
      title: "' . $title . '"';
        
        if (!empty($clientId)) {
            $mutation .= '
      clientId: "' . $clientId . '"';
        }
        
        if (!empty($propertyId)) {
            $mutation .= '
      propertyId: "' . $propertyId . '"';
        }
        
        $mutation .= '
      lineItems: ' . $lineItemsStr;
        
        // Add deposit if available (using correct format: deposit with rate and type)
        // NOTE: deposit.type can only be 'Percent' or 'Unit' - 'Fixed' is NOT valid
        // Jobber enforces a max deposit (e.g. deposit <= quote total or a cap like $608); cap to avoid "Enter a deposit amount that's less than or equal to $X" error
        if (isset($quote_data['deposit_amount']) && floatval($quote_data['deposit_amount']) > 0) {
            $deposit_amount = floatval($quote_data['deposit_amount']);
            $quote_total = isset($quote_data['total']) ? floatval($quote_data['total']) : 0;
            $deposit_cap = $quote_total > 0 ? min(608.0, $quote_total) : 608.0;
            $deposit_amount = min($deposit_amount, $deposit_cap);
            $mutation .= '
      deposit: {
        rate: ' . $deposit_amount . '
        type: Unit
      }';
        }
        
        $mutation .= '
    }
  ) {
    quote {
      id
      quoteNumber
      quoteStatus
      title
    }
    userErrors {
      message
      path
    }
  }
}';
        
        return $mutation;
    }
    
    /**
     * Build GraphQL mutation for creating a job
     *
     * @since 1.1.0
     * @param array $job_data Job data
     * @return string GraphQL mutation string
     */
    private function build_job_create_mutation($job_data) {
        $title = isset($job_data['name']) ? $this->sanitize_for_graphql_string($job_data['name']) : 'Online Booking';
        // Ensure IDs are base64 encoded for Jobber GraphQL API
        $propertyId = isset($job_data['property_id']) ? $this->ensure_base64_id($job_data['property_id']) : '';
        $quoteId = isset($job_data['quote_id']) ? $this->ensure_base64_id($job_data['quote_id']) : '';
        
        // Build line items
        $lineItems = array();
        if (isset($job_data['line_items']) && is_array($job_data['line_items'])) {
            foreach ($job_data['line_items'] as $item) {
                $name = isset($item['name']) ? $this->sanitize_for_graphql_string($item['name']) : 'Item';
                $unitPrice = isset($item['unit_price']) ? floatval($item['unit_price']) : 0;
                $quantity = isset($item['quantity']) ? floatval($item['quantity']) : 1;
                
                $lineItems[] = '{
          name: "' . $name . '"
          saveToProductsAndServices: false
          quantity: ' . $quantity . '
          unitPrice: ' . $unitPrice . '
        }';
            }
        }
        
        $lineItemsStr = !empty($lineItems) ? '[' . implode(', ', $lineItems) . ']' : '[]';
        
        $mutation = 'mutation CreateJob {
  jobCreate(input: {';
        
        // Property ID is REQUIRED for Job - mutation will fail without it
        if (!empty($propertyId)) {
            $mutation .= '
    propertyId: "' . $propertyId . '"';
        } else {
            // This should never happen if we check before calling this function
            xtremecleans_log('CRITICAL ERROR: Job mutation missing propertyId. This will cause the mutation to fail!', 'error');
            // Still add empty propertyId to show the error clearly
            $mutation .= '
    propertyId: ""';
        }
        
        if (!empty($quoteId)) {
            $mutation .= '
    quoteId: "' . $quoteId . '"';
        }
        
        $mutation .= '
    lineItems: ' . $lineItemsStr . '
    invoicing: {
      invoicingType: FIXED_PRICE
      invoicingSchedule: ON_COMPLETION
    }
    title: "' . $title . '"';
        
        // Add notes if available (contains service details, zone info, instructions, etc.)
        if (isset($job_data['notes']) && !empty($job_data['notes'])) {
            $notes = $this->sanitize_for_graphql_string($job_data['notes']);
            $mutation .= '
    notes: "' . $notes . '"';
        }
        
        // Add start time if available (nested in timeframe object as per Jobber requirements)
        if (isset($job_data['start_at']) && !empty($job_data['start_at'])) {
            $start_at = $this->sanitize_for_graphql_string($job_data['start_at']);
            $mutation .= '
    timeframe: {
      startAt: "' . $start_at . '"
    }';
        }
        
        $mutation .= '
  }) {
    job {
      id
      jobNumber
      jobStatus
      jobType
      title
      startAt
      endAt
      completedAt
      createdAt
      updatedAt
      total
      property {
        id
        address
      }
      quote {
        id
        quoteNumber
      }
    }
    userErrors {
      message
      path
    }
  }
}';
        
        return $mutation;
    }

    /**
     * Ensure ID is base64 encoded (for Jobber GraphQL)
     *
     * @since 1.1.0
     * @param string $id ID string (e.g. "gid://jobber/Client/123" or "Z2lk...")
     * @return string Base64 encoded ID
     */
    private function ensure_base64_id($id) {
        if (empty($id)) {
            return '';
        }
        
        // If it contains "gid://", it's a raw ID and needs encoding
        if (strpos($id, 'gid://') !== false) {
            return base64_encode($id);
        }
        
        return $id;
    }
    
    /**
     * Log a debug pack for Jobber support when sync fails (request + response + timestamp).
     * Search logs for "JOBBER_DEBUG_PACK" to find this block and send to Jobber.
     *
     * @param string     $step     Mutation name (e.g. quoteCreate, jobCreate).
     * @param string     $mutation Full GraphQL mutation string (request body).
     * @param array|null $response Full API response or error data (array with body/errors).
     */
    private function log_jobber_debug_pack($step, $mutation, $response = null) {
        $timestamp = date('c'); // ISO 8601 with timezone
        $endpoint  = 'https://api.getjobber.com/api/graphql';
        xtremecleans_log('========== JOBBER_DEBUG_PACK (send this to Jobber support) ==========', 'error');
        xtremecleans_log('JOBBER_DEBUG_TIMESTAMP: ' . $timestamp, 'error');
        xtremecleans_log('JOBBER_DEBUG_STEP: ' . $step, 'error');
        xtremecleans_log('JOBBER_DEBUG_ENDPOINT: ' . $endpoint, 'error');
        xtremecleans_log('JOBBER_DEBUG_REQUEST: ' . $mutation, 'error');
        xtremecleans_log('JOBBER_DEBUG_RESPONSE: ' . (is_array($response) ? wp_json_encode($response) : (string) $response), 'error');
        xtremecleans_log('========== END JOBBER_DEBUG_PACK ==========', 'error');
    }
    
    /**
     * Sanitize a string for use inside a GraphQL double-quoted string (avoids "Expected string or block string, but it was malformed").
     * Escapes backslash, double quote; replaces newlines with space.
     *
     * @since 1.0.0
     * @param string $str Raw string (may contain newlines, quotes, etc.)
     * @return string Safe for GraphQL "..." string
     */
    private function sanitize_for_graphql_string($str) {
        if (!is_string($str) || $str === '') {
            return '';
        }
        $str = str_replace(array('\\', '"', "\r\n", "\n", "\r"), array('\\\\', '\\"', ' ', ' ', ' '), $str);
        return $str;
    }
    
    /**
     * Parse appointment date and time into ISO 8601 format for Jobber
     *
     * @since 1.0.0
     * @param string $date Date string (e.g., "2024-01-15")
     * @param string $time Time string (e.g., "8:00 AM - 9:00 AM")
     * @return string|null ISO 8601 datetime string or null
     */
    private function parse_appointment_datetime($date, $time) {
        if (empty($date) || empty($time)) {
            return null;
        }
        
        // Parse time window (e.g., "8:00 AM - 9:00 AM" -> use start time "8:00 AM")
        $time_parts = explode(' - ', $time);
        $start_time = trim($time_parts[0]);
        
        // Convert to 24-hour format
        $time_24 = $this->convert_to_24hour($start_time);
        if (!$time_24) {
            return null;
        }
        
        // Combine date and time
        $datetime_string = $date . ' ' . $time_24;
        $datetime = date_create_from_format('Y-m-d H:i', $datetime_string);
        
        if ($datetime) {
            // Get WordPress timezone
            $timezone = wp_timezone();
            $datetime->setTimezone($timezone);
            // Format as ISO 8601 with timezone offset (e.g., "2024-01-15T08:00:00-05:00")
            return $datetime->format('c');
        }
        
        return null;
    }
    
    /**
     * Convert 12-hour time to 24-hour format
     *
     * @since 1.0.0
     * @param string $time_12 Time in 12-hour format (e.g., "8:00 AM")
     * @return string|null Time in 24-hour format (e.g., "08:00") or null
     */
    private function convert_to_24hour($time_12) {
        $time_12 = trim($time_12);
        if (preg_match('/(\d{1,2}):(\d{2})\s*(AM|PM)/i', $time_12, $matches)) {
            $hour = intval($matches[1]);
            $minute = intval($matches[2]);
            $period = strtoupper($matches[3]);
            
            if ($period === 'PM' && $hour !== 12) {
                $hour += 12;
            } elseif ($period === 'AM' && $hour === 12) {
                $hour = 0;
            }
            
            return sprintf('%02d:%02d', $hour, $minute);
        }
        
        return null;
    }
    
<<<<<<< HEAD
=======
    const TRAVEL_MAX_MINUTES = 60;
    
    /**
     * Get job end time as Unix timestamp (slot start + duration).
     */
    private function get_job_end_unix($appointment_date, $appointment_time, $duration_minutes) {
        $iso_start = $this->parse_appointment_datetime($appointment_date, $appointment_time);
        if (!$iso_start) {
            return null;
        }
        $tz = wp_timezone();
        $dt = date_create_from_format('c', $iso_start, $tz);
        if (!$dt) {
            $dt = date_create($iso_start, $tz);
        }
        if (!$dt) {
            return null;
        }
        $dt->modify('+' . intval($duration_minutes) . ' minutes');
        return $dt->getTimestamp();
    }
    
    /**
     * Get the next order on the same date whose slot start is after the given Unix time.
     */
    private function get_next_order_after_time($appointment_date, $after_unix, $default_duration_minutes = 120) {
        global $wpdb;
        $table_name = $wpdb->prefix . 'xtremecleans_orders';
        $orders = $wpdb->get_results($wpdb->prepare(
            "SELECT id, appointment_date, appointment_time, address1, address2, city, state, zip_code FROM {$table_name} WHERE appointment_date = %s AND appointment_time IS NOT NULL AND appointment_time != '' ORDER BY appointment_time ASC",
            $appointment_date
        ), ARRAY_A);
        if (empty($orders)) {
            return null;
        }
        $next = null;
        $next_start_unix = PHP_INT_MAX;
        foreach ($orders as $order) {
            $slot_start_iso = $this->parse_appointment_datetime($order['appointment_date'], $order['appointment_time']);
            if (!$slot_start_iso) {
                continue;
            }
            $tz = wp_timezone();
            $dt = date_create_from_format('c', $slot_start_iso, $tz);
            if (!$dt) {
                $dt = date_create($slot_start_iso, $tz);
            }
            if (!$dt) {
                continue;
            }
            $slot_start_unix = $dt->getTimestamp();
            if ($slot_start_unix > $after_unix && $slot_start_unix < $next_start_unix) {
                $next_start_unix = $slot_start_unix;
                $next = $order;
            }
        }
        return $next;
    }
    
    private function build_address_string($address1, $address2, $city, $state, $zip_code) {
        $parts = array_filter(array($address1, $address2, $city, $state, $zip_code));
        return implode(', ', $parts);
    }
    
    private function get_travel_fallback_message() {
        $msg = get_option('xtremecleans_travel_fallback_message', '');
        if ($msg !== '') {
            return $msg;
        }
        $phone = get_option('xtremecleans_travel_fallback_phone', '410-819-2223');
        return sprintf(
            __('Online booking for that time is limited right now. Choose another time or call/text %s.', 'xtremecleans'),
            $phone
        );
    }
    
    /**
     * Validate travel time: crew must reach next job in ≤ 60 min. Any error = block with fallback message.
     */
    private function validate_travel_time_for_order($customer, $appointment_date, $appointment_time, $duration_minutes) {
        if (get_option('xtremecleans_travel_enabled', '0') !== '1') {
            return true;
        }
        $api_key = get_option('xtremecleans_google_api_key', '');
        if (empty($api_key)) {
            return true;
        }
        $default_duration = (int) get_option('xtremecleans_default_job_duration_minutes', 120);
        if ($duration_minutes <= 0) {
            $duration_minutes = $default_duration;
        }
        $E_unix = $this->get_job_end_unix($appointment_date, $appointment_time, $duration_minutes);
        if (!$E_unix) {
            return new WP_Error('travel_parse', $this->get_travel_fallback_message());
        }
        $next_order = $this->get_next_order_after_time($appointment_date, $E_unix, $default_duration);
        if (!$next_order) {
            return true;
        }
        $new_address = $this->build_address_string(
            isset($customer['address1']) ? $customer['address1'] : '',
            isset($customer['address2']) ? $customer['address2'] : '',
            isset($customer['city']) ? $customer['city'] : '',
            isset($customer['state']) ? $customer['state'] : '',
            isset($customer['zip_code']) ? $customer['zip_code'] : ''
        );
        $next_address = $this->build_address_string(
            $next_order['address1'],
            isset($next_order['address2']) ? $next_order['address2'] : '',
            $next_order['city'],
            $next_order['state'],
            $next_order['zip_code']
        );
        if (empty($new_address) || empty($next_address)) {
            return new WP_Error('travel_address', $this->get_travel_fallback_message());
        }
        $travel_file = XTREMECLEANS_PLUGIN_DIR . 'core/google/class-xtremecleans-google-travel.php';
        if (!file_exists($travel_file)) {
            return new WP_Error('travel_missing', $this->get_travel_fallback_message());
        }
        require_once $travel_file;
        $origin = XtremeCleans_Google_Travel::geocode($new_address, $api_key);
        if (!$origin) {
            return new WP_Error('travel_geocode_origin', $this->get_travel_fallback_message());
        }
        $dest = XtremeCleans_Google_Travel::geocode($next_address, $api_key);
        if (!$dest) {
            return new WP_Error('travel_geocode_dest', $this->get_travel_fallback_message());
        }
        $seconds = XtremeCleans_Google_Travel::get_duration_in_traffic_seconds(
            $origin['lat'], $origin['lng'],
            $dest['lat'], $dest['lng'],
            $E_unix,
            $api_key
        );
        if (is_wp_error($seconds)) {
            return new WP_Error('travel_api', $this->get_travel_fallback_message());
        }
        if (($seconds / 60) > self::TRAVEL_MAX_MINUTES) {
            return new WP_Error('travel_exceeded', $this->get_travel_fallback_message());
        }
        return true;
    }
    
>>>>>>> 3378c4f (plugin last vertation updated in admin dashboard)
    private function get_jobber_auth_url() {
        if (!class_exists('XtremeCleans_Frontend')) {
            $frontend_file = XTREMECLEANS_PLUGIN_DIR . 'core/frontend/class-xtremecleans-frontend.php';
            if (file_exists($frontend_file)) {
                require_once $frontend_file;
            }
        }
        if (class_exists('XtremeCleans_Frontend')) {
            $frontend = new XtremeCleans_Frontend();
            return method_exists($frontend, 'get_jobber_authorize_url_public') ? $frontend->get_jobber_authorize_url_public() : '';
        }
        return '';
    }
    
    /**
     * AJAX handler to get unique service names from database
     *
     * @since 1.0.0
     */
    public function ajax_get_service_names() {
        // Verify nonce for security
        if (!isset($_POST['nonce']) || !wp_verify_nonce($_POST['nonce'], 'xtremecleans_add_zip')) {
            wp_send_json_error(array('message' => __('Security check failed.', 'xtremecleans')));
        }
        
        global $wpdb;
        
        // Get ZIP code if provided
        $zip_code = isset($_POST['zip_code']) ? sanitize_text_field($_POST['zip_code']) : '';
<<<<<<< HEAD
=======
        $requested_service_values = $this->sanitize_service_names_from_request(isset($_POST['service_name']) ? $_POST['service_name'] : '');
        $requested_service = !empty($requested_service_values) ? strtolower($requested_service_values[0]) : '';
>>>>>>> 3378c4f (plugin last vertation updated in admin dashboard)
        
        // Check if we should fetch services directly from Jobber API
        $fetch_from_jobber = get_option('xtremecleans_fetch_services_from_jobber', false);
        
        // If ZIP code is provided and fetch from Jobber is enabled, get services directly from Jobber
        if (!empty($zip_code) && $fetch_from_jobber) {
            $service_names = $this->fetch_services_from_jobber_by_zip($zip_code);
            
            // If we got services from Jobber, return them
            if (!empty($service_names)) {
                $service_names = array_map('trim', $service_names);
                
                // Handle requested service name matching
<<<<<<< HEAD
                $requested_service = isset($_POST['service_name']) ? sanitize_text_field($_POST['service_name']) : '';
                if (!empty($requested_service) && !empty($service_names)) {
                    $requested_service = strtolower($requested_service);
=======
                if (!empty($requested_service) && !empty($service_names)) {
>>>>>>> 3378c4f (plugin last vertation updated in admin dashboard)
                    $matched_service = '';
                    foreach ($service_names as $name) {
                        if (strtolower($name) === $requested_service) {
                            $matched_service = $name;
                            break;
                        }
                    }
                    if ($matched_service) {
                        wp_send_json_success(array(
                            'service_names' => array($matched_service),
                            'filtered' => true,
                            'from_jobber' => true,
                        ));
                    }
                }
                
                wp_send_json_success(array(
                    'service_names' => $service_names,
                    'filtered' => false,
                    'from_jobber' => true,
                ));
            }
        }
        
        // Fallback to database if Jobber fetch failed or option disabled
        $service_names = array();
        $service_items_table = $wpdb->prefix . 'xtremecleans_service_items';
        $service_items_exists = $wpdb->get_var($wpdb->prepare("SHOW TABLES LIKE %s", $service_items_table));
        
        if ($service_items_exists) {
            $service_items_table = esc_sql($service_items_table);
            
            // Check if we should only show Jobber services
            $only_jobber = get_option('xtremecleans_show_only_jobber_services', false);
            
            // Check if ZIP-based Jobber service filtering is enabled
            $zip_based_jobber = get_option('xtremecleans_zip_based_jobber_services', false);
            
            if ($only_jobber || $zip_based_jobber) {
                // If ZIP code is provided and ZIP-based filtering is enabled, filter by ZIP
                if (!empty($zip_code) && $zip_based_jobber) {
<<<<<<< HEAD
                    // Get services for this ZIP code from zip_reference table
                    $zip_table = $wpdb->prefix . 'xtremecleans_zip_reference';
                    $zip_exists = $wpdb->get_var($wpdb->prepare("SHOW TABLES LIKE %s", $zip_table));
                    
                    if ($zip_exists) {
                        $zip_table = esc_sql($zip_table);
                        // Get service name for this ZIP code
                        $zip_service = $wpdb->get_var($wpdb->prepare(
                            "SELECT service_name FROM `{$zip_table}` WHERE zip_code = %s LIMIT 1",
                            $zip_code
                        ));
                        
                        if ($zip_service) {
                            // Get Jobber services matching this ZIP code's service name
                            $service_names = $wpdb->get_col($wpdb->prepare(
                                "SELECT DISTINCT service_name 
                                FROM `{$service_items_table}` 
                                WHERE service_name = %s 
                                AND service_name IS NOT NULL 
                                AND service_name != '' 
                                AND synced_from_jobber = 1 
                                ORDER BY service_name ASC",
                                $zip_service
                            ));
                            
                            // If no exact match, get all Jobber services (fallback)
                            if (empty($service_names)) {
                                $service_names = $wpdb->get_col(
                                    "SELECT DISTINCT service_name 
                                    FROM `{$service_items_table}` 
                                    WHERE service_name IS NOT NULL 
                                    AND service_name != '' 
                                    AND synced_from_jobber = 1 
                                    ORDER BY service_name ASC"
                                );
                            }
                        } else {
                            // ZIP code not found in reference, get all Jobber services
                            $service_names = $wpdb->get_col(
                                "SELECT DISTINCT service_name 
                                FROM `{$service_items_table}` 
                                WHERE service_name IS NOT NULL 
                                AND service_name != '' 
                                AND synced_from_jobber = 1 
                                ORDER BY service_name ASC"
                            );
                        }
                    } else {
                        // ZIP table doesn't exist, get all Jobber services
                        $service_names = $wpdb->get_col(
                            "SELECT DISTINCT service_name 
                            FROM `{$service_items_table}` 
                            WHERE service_name IS NOT NULL 
                            AND service_name != '' 
                            AND synced_from_jobber = 1 
                            ORDER BY service_name ASC"
                        );
=======
                    $all_jobber_services = $wpdb->get_col(
                        "SELECT DISTINCT service_name 
                        FROM `{$service_items_table}` 
                        WHERE service_name IS NOT NULL 
                        AND service_name != '' 
                        AND synced_from_jobber = 1 
                        ORDER BY service_name ASC"
                    );

                    $zip_services = $this->get_service_names_by_zip_code($zip_code);
                    if (!empty($zip_services)) {
                        $zip_lookup = array();
                        foreach ($zip_services as $zip_service_name) {
                            $zip_lookup[strtolower($zip_service_name)] = true;
                        }

                        foreach ($all_jobber_services as $service_name) {
                            $normalized_service = strtolower(trim($service_name));
                            if (isset($zip_lookup[$normalized_service])) {
                                $service_names[] = $service_name;
                            }
                        }

                        // If no exact match, fallback to all Jobber services.
                        if (empty($service_names)) {
                            $service_names = $all_jobber_services;
                        }
                    } else {
                        // ZIP code not found in mapping, return all Jobber services.
                        $service_names = $all_jobber_services;
>>>>>>> 3378c4f (plugin last vertation updated in admin dashboard)
                    }
                } else {
                    // Get all Jobber services (no ZIP filtering)
                    $service_names = $wpdb->get_col(
                        "SELECT DISTINCT service_name 
                        FROM `{$service_items_table}` 
                        WHERE service_name IS NOT NULL 
                        AND service_name != '' 
                        AND synced_from_jobber = 1 
                        ORDER BY service_name ASC"
                    );
                }
            } else {
                // Get all unique service names (not filtering by Jobber)
                $service_names = $wpdb->get_col(
                    "SELECT DISTINCT service_name 
                    FROM `{$service_items_table}` 
                    WHERE service_name IS NOT NULL AND service_name != '' 
                    ORDER BY service_name ASC"
                );
            }
        }
        
        // Fallback to zip reference table if no service items exist yet
        if (empty($service_names)) {
            $zip_table = $wpdb->prefix . 'xtremecleans_zip_reference';
            $zip_exists = $wpdb->get_var($wpdb->prepare("SHOW TABLES LIKE %s", $zip_table));
            
            if ($zip_exists) {
                $zip_table = esc_sql($zip_table);
<<<<<<< HEAD
        $service_names = $wpdb->get_col(
                    "SELECT DISTINCT service_name 
                    FROM `{$zip_table}` 
                    WHERE service_name IS NOT NULL AND service_name != '' 
                    ORDER BY service_name ASC"
        );
            }
        }
        
        $service_names = $service_names ? array_map('trim', $service_names) : array();
        
        $requested_service = isset($_POST['service_name']) ? sanitize_text_field($_POST['service_name']) : '';
        
        if (!empty($requested_service)) {
            $requested_service = strtolower($requested_service);
        }
=======
                if (!empty($zip_code)) {
                    $service_names = $wpdb->get_col($wpdb->prepare(
                        "SELECT DISTINCT service_name 
                        FROM `{$zip_table}` 
                        WHERE zip_code = %s
                        AND service_name IS NOT NULL
                        AND service_name != '' 
                        ORDER BY service_name ASC",
                        $zip_code
                    ));
                }

                if (empty($service_names)) {
                    $service_names = $wpdb->get_col(
                        "SELECT DISTINCT service_name 
                        FROM `{$zip_table}` 
                        WHERE service_name IS NOT NULL AND service_name != '' 
                        ORDER BY service_name ASC"
                    );
                }
            }
        }
        
        $service_names = $this->sanitize_service_names_from_request($service_names);
>>>>>>> 3378c4f (plugin last vertation updated in admin dashboard)
        
        // If a specific service name was provided, try to match it (case-insensitive)
        if (!empty($requested_service) && !empty($service_names)) {
            $matched_service = '';
            foreach ($service_names as $name) {
                if (strtolower($name) === $requested_service) {
                    $matched_service = $name;
                    break;
                }
            }
            
            if ($matched_service) {
                wp_send_json_success(array(
                    'service_names' => array($matched_service),
                    'filtered' => true,
                ));
            }
        }
        
        if (empty($service_names)) {
            wp_send_json_success(array('service_names' => array(), 'filtered' => false));
        }
        
        wp_send_json_success(array('service_names' => $service_names, 'filtered' => false));
    }
    
    /**
     * AJAX handler to add custom zone name
     *
     * @since 1.0.0
     */
    public function ajax_add_zone_name() {
        check_ajax_referer('xtremecleans_add_zip', 'nonce');
        
        if (!current_user_can('manage_options')) {
            wp_send_json_error(array('message' => __('You do not have permission to perform this action.', 'xtremecleans')));
        }
        
        $zone_name = isset($_POST['zone_name']) ? sanitize_text_field($_POST['zone_name']) : '';
        $zone_name = trim(preg_replace('/\s+/', ' ', $zone_name));
        
        if (empty($zone_name)) {
            wp_send_json_error(array('message' => __('Zone Name is required.', 'xtremecleans')));
        }
        
        if (strlen($zone_name) > 120) {
            wp_send_json_error(array('message' => __('Zone Name is too long.', 'xtremecleans')));
        }
        
        $custom_zone_names = get_option('xtremecleans_custom_zone_names', array());
        if (!is_array($custom_zone_names)) {
            $custom_zone_names = array();
        }
        
        $existing = array_map('strtolower', array_merge($this->get_predefined_zone_names(), $custom_zone_names));
        if (in_array(strtolower($zone_name), $existing, true)) {
            wp_send_json_error(array('message' => __('Zone Name already exists.', 'xtremecleans')));
        }
        
        $custom_zone_names[] = $zone_name;
        $custom_zone_names = array_values(array_unique($custom_zone_names));
        
        update_option('xtremecleans_custom_zone_names', $custom_zone_names);
        
        wp_send_json_success(array(
            'message' => __('Zone Name added successfully.', 'xtremecleans'),
            'zone_names' => $this->get_zone_names(),
        ));
    }
    
    /**
     * AJAX handler to get service items for a specific service name
     *
     * @since 1.0.0
     */
    public function ajax_get_service_items() {
        // Verify nonce for security
        if (!isset($_POST['nonce']) || !wp_verify_nonce($_POST['nonce'], 'xtremecleans_add_zip')) {
            wp_send_json_error(array('message' => __('Security check failed.', 'xtremecleans')));
        }
        
        $service_name = isset($_POST['service_name']) ? sanitize_text_field($_POST['service_name']) : '';
        
        if (empty($service_name)) {
            wp_send_json_error(array('message' => __('Service name is required.', 'xtremecleans')));
        }
        
        global $wpdb;
        $table_name = $wpdb->prefix . 'xtremecleans_service_items';
        
        // Check if table exists
        $table_exists = $wpdb->get_var($wpdb->prepare("SHOW TABLES LIKE %s", $table_name));
        
        if (!$table_exists) {
            // Table doesn't exist, return empty array - items must be added from admin panel
            wp_send_json_success(array('service_items' => array()));
        }
        
        // Escape table name and service name for safe SQL query
        $table_name_escaped = esc_sql($table_name);
        $service_name_escaped = esc_sql($service_name);
        
        // Get service items for this service name
        $service_items = $wpdb->get_results(
            $wpdb->prepare(
<<<<<<< HEAD
                "SELECT item_name, item_description, 
=======
                "SELECT item_name, item_description, service_item_duration,
>>>>>>> 3378c4f (plugin last vertation updated in admin dashboard)
                        COALESCE(price1_name, 'Clean') as price1_name,
                        COALESCE(price1_value, clean_price, 0.00) as price1_value,
                        COALESCE(price2_name, 'Protect') as price2_name,
                        COALESCE(price2_value, protect_price, 0.00) as price2_value,
                        COALESCE(price3_name, 'Deodorize') as price3_name,
                        COALESCE(price3_value, deodorize_price, 0.00) as price3_value
                FROM `{$table_name_escaped}` 
                WHERE service_name = %s 
                ORDER BY id ASC",
                $service_name
            ),
            ARRAY_A
        );
        
        // Return items (empty array if none found - frontend will show empty state)
        wp_send_json_success(array('service_items' => $service_items ? $service_items : array()));
    }
    
    /**
     * Create service items table
     *
     * @since 1.0.0
     */
    private function create_service_items_table() {
        global $wpdb;
        
        $charset_collate = $wpdb->get_charset_collate();
        $table_name = $wpdb->prefix . 'xtremecleans_service_items';
        
        $sql = "CREATE TABLE IF NOT EXISTS `{$table_name}` (
            `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT,
            `service_name` varchar(100) NOT NULL,
            `item_name` varchar(100) NOT NULL,
            `item_description` text DEFAULT NULL,
<<<<<<< HEAD
=======
            `service_item_duration` int(11) DEFAULT NULL,
>>>>>>> 3378c4f (plugin last vertation updated in admin dashboard)
            `price1_name` varchar(50) DEFAULT 'Clean',
            `price1_value` decimal(10,2) DEFAULT '0.00',
            `price2_name` varchar(50) DEFAULT 'Protect',
            `price2_value` decimal(10,2) DEFAULT '0.00',
            `price3_name` varchar(50) DEFAULT 'Deodorize',
            `price3_value` decimal(10,2) DEFAULT '0.00',
            `clean_price` decimal(10,2) DEFAULT '0.00',
            `protect_price` decimal(10,2) DEFAULT '0.00',
            `deodorize_price` decimal(10,2) DEFAULT '0.00',
            `created_at` datetime DEFAULT CURRENT_TIMESTAMP,
            `updated_at` datetime DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
            PRIMARY KEY (`id`),
            KEY `service_name` (`service_name`),
            KEY `item_name` (`item_name`)
        ) {$charset_collate};";
<<<<<<< HEAD
=======
        $this->maybe_add_service_item_column('service_item_duration', "int(11) DEFAULT NULL");
>>>>>>> 3378c4f (plugin last vertation updated in admin dashboard)
        
        require_once(ABSPATH . 'wp-admin/includes/upgrade.php');
        dbDelta($sql);
        
        // Add new columns if they don't exist (for existing installations)
        $this->maybe_add_service_item_column('price1_name', "varchar(50) DEFAULT 'Clean'");
        $this->maybe_add_service_item_column('price1_value', "decimal(10,2) DEFAULT '0.00'");
        $this->maybe_add_service_item_column('price2_name', "varchar(50) DEFAULT 'Protect'");
        $this->maybe_add_service_item_column('price2_value', "decimal(10,2) DEFAULT '0.00'");
        $this->maybe_add_service_item_column('price3_name', "varchar(50) DEFAULT 'Deodorize'");
        $this->maybe_add_service_item_column('price3_value', "decimal(10,2) DEFAULT '0.00'");
        
        // Add Jobber sync columns
        $this->maybe_add_service_item_column('jobber_service_id', "varchar(100) DEFAULT NULL");
        $this->maybe_add_service_item_column('jobber_unit', "varchar(50) DEFAULT NULL");
        $this->maybe_add_service_item_column('synced_from_jobber', "tinyint(1) DEFAULT 0");
        
        // Migrate old data to new format
        $this->migrate_service_item_prices($table_name);
    }
    
    /**
     * Get all service items
     *
     * @since 1.0.0
     * @return array Service items
     */
    private function get_all_service_items() {
        global $wpdb;
        
        $table_name = $wpdb->prefix . 'xtremecleans_service_items';
        $table_exists = $wpdb->get_var($wpdb->prepare("SHOW TABLES LIKE %s", $table_name));
        
        if (!$table_exists) {
            return array();
        }
        
        $table_name_escaped = esc_sql($table_name);
        $results = $wpdb->get_results(
<<<<<<< HEAD
            "SELECT * FROM `{$table_name_escaped}` ORDER BY service_name ASC, id ASC",
=======
            "SELECT *, service_item_duration FROM `{$table_name_escaped}` ORDER BY service_name ASC, id ASC",
>>>>>>> 3378c4f (plugin last vertation updated in admin dashboard)
            ARRAY_A
        );
        
        if (!$results) {
            return array();
        }
        
        // Ensure new price columns have default values if not set
        foreach ($results as &$item) {
            if (empty($item['price1_name'])) {
                $item['price1_name'] = 'Clean';
            }
            if (empty($item['price1_value']) && isset($item['clean_price'])) {
                $item['price1_value'] = $item['clean_price'];
            }
            if (empty($item['price2_name'])) {
                $item['price2_name'] = 'Protect';
            }
            if (empty($item['price2_value']) && isset($item['protect_price'])) {
                $item['price2_value'] = $item['protect_price'];
            }
            if (empty($item['price3_name'])) {
                $item['price3_name'] = 'Deodorize';
            }
            if (empty($item['price3_value']) && isset($item['deodorize_price'])) {
                $item['price3_value'] = $item['deodorize_price'];
            }
        }
        
        return $results;
    }
    
    /**
     * Add column to service items table if it doesn't exist
     *
     * @since 1.0.0
     * @param string $column_name Column name
     * @param string $column_definition Column definition
     */
    private function maybe_add_service_item_column($column_name, $column_definition) {
        global $wpdb;
        $table_name = $wpdb->prefix . 'xtremecleans_service_items';
        
        $column_exists = $wpdb->get_results($wpdb->prepare(
            "SHOW COLUMNS FROM `{$table_name}` LIKE %s",
            $column_name
        ));
        
        if (empty($column_exists)) {
            $wpdb->query("ALTER TABLE `{$table_name}` ADD COLUMN `{$column_name}` {$column_definition}");
        }
    }
    
    /**
     * Migrate old price data to new format
     *
     * @since 1.0.0
     * @param string $table_name Table name
     */
    private function migrate_service_item_prices($table_name) {
        global $wpdb;
        
        // Update records where new price columns are empty but old columns have values
        $wpdb->query("UPDATE `{$table_name}` 
            SET `price1_value` = `clean_price`,
                `price2_value` = `protect_price`,
                `price3_value` = `deodorize_price`
            WHERE (`price1_value` = 0.00 OR `price1_value` IS NULL)
            AND (`clean_price` != 0.00 OR `protect_price` != 0.00 OR `deodorize_price` != 0.00)");
    }
    
    /**
     * Parse service item rows input (JSON or array)
     *
     * @since 1.0.0
     * @param mixed $input Raw input
     * @return array
     */
    private function parse_service_item_rows_input($input) {
        if (empty($input)) {
            return array();
        }
        
        if (is_string($input)) {
            $decoded = json_decode(wp_unslash($input), true);
            return is_array($decoded) ? $decoded : array();
        }
        
        if (is_array($input)) {
            return $input;
        }
        
        return array();
    }
    
    /**
     * Retrieve raw service item rows from the current request, with legacy fallbacks.
     *
     * @since 1.0.0
     * @return array
     */
    private function get_raw_service_item_rows_from_request() {
        $items = $this->parse_service_item_rows_input(isset($_POST['items']) ? $_POST['items'] : array());
        
        if (!empty($items)) {
            return $items;
        }
        
        $rows = array();
        
        if (isset($_POST['item_names']) && is_array($_POST['item_names'])) {
            $description = isset($_POST['item_description']) ? $_POST['item_description'] : '';
            $price1_value = isset($_POST['price1_value']) ? $_POST['price1_value'] : 0;
            $price2_value = isset($_POST['price2_value']) ? $_POST['price2_value'] : 0;
            $price3_value = isset($_POST['price3_value']) ? $_POST['price3_value'] : 0;
            
            foreach ($_POST['item_names'] as $item_name) {
                $rows[] = array(
                    'item_name' => $item_name,
                    'item_description' => $description,
                    'price1_value' => $price1_value,
                    'price2_value' => $price2_value,
                    'price3_value' => $price3_value,
                );
            }
        } elseif (isset($_POST['item_name'])) {
            $rows[] = array(
                'item_name' => $_POST['item_name'],
                'item_description' => isset($_POST['item_description']) ? $_POST['item_description'] : '',
                'price1_value' => isset($_POST['price1_value']) ? $_POST['price1_value'] : 0,
                'price2_value' => isset($_POST['price2_value']) ? $_POST['price2_value'] : 0,
                'price3_value' => isset($_POST['price3_value']) ? $_POST['price3_value'] : 0,
            );
        }
        
        return $rows;
    }
    
    /**
     * Get unique service names
     *
     * @since 1.0.0
     * @return array Unique service names
     */
    private function get_unique_service_names() {
        global $wpdb;
        
        $table_name = $wpdb->prefix . 'xtremecleans_service_items';
        $table_exists = $wpdb->get_var($wpdb->prepare("SHOW TABLES LIKE %s", $table_name));
        
        if (!$table_exists) {
            return array();
        }
        
        $table_name_escaped = esc_sql($table_name);
        $results = $wpdb->get_results(
            "SELECT DISTINCT service_name FROM `{$table_name_escaped}` WHERE service_name IS NOT NULL AND service_name != '' ORDER BY service_name ASC",
            ARRAY_A
        );
        
        $service_names = array();
        if ($results) {
            foreach ($results as $row) {
                $service_names[] = $row['service_name'];
            }
        }
        
        return $service_names;
    }
    
    /**
     * AJAX handler: Add service item
     *
     * @since 1.0.0
     */
    public function ajax_add_service_item() {
        check_ajax_referer('xtremecleans_add_zip', 'nonce');
        
        if (!current_user_can('manage_options')) {
            wp_send_json_error(array('message' => __('You do not have permission to perform this action.', 'xtremecleans')));
        }
        
        global $wpdb;
        
        // Ensure table exists
        $this->create_service_items_table();
        $table_name = $wpdb->prefix . 'xtremecleans_service_items';
        
        // Get and sanitize form data
        $service_name = isset($_POST['service_name']) ? sanitize_text_field($_POST['service_name']) : '';
        $price1_name = 'Clean';
        $price2_name = 'Protect';
        $price3_name = 'Deodorize';
        
        if (empty($service_name)) {
            wp_send_json_error(array('message' => __('Service Name is required.', 'xtremecleans')));
        }
        
        $raw_items = $this->get_raw_service_item_rows_from_request();
        
        if (empty($raw_items)) {
            wp_send_json_error(array('message' => __('At least one Item Name is required.', 'xtremecleans')));
        }
        
        // Insert service items for each row
        $inserted_count = 0;
        $errors = array();
        
        foreach ($raw_items as $raw_item) {
            if (!is_array($raw_item)) {
                continue;
            }
            
            $item_name = isset($raw_item['item_name']) ? sanitize_text_field($raw_item['item_name']) : '';
            if (empty($item_name)) {
                continue;
            }
            
            $item_description = isset($raw_item['item_description']) ? sanitize_textarea_field($raw_item['item_description']) : '';
            $price1_value = isset($raw_item['price1_value']) ? floatval($raw_item['price1_value']) : 0.00;
            $price2_value = isset($raw_item['price2_value']) ? floatval($raw_item['price2_value']) : 0.00;
            $price3_value = isset($raw_item['price3_value']) ? floatval($raw_item['price3_value']) : 0.00;
            
<<<<<<< HEAD
=======
            $service_item_duration = isset($raw_item['service_item_duration']) ? intval($raw_item['service_item_duration']) : null;
>>>>>>> 3378c4f (plugin last vertation updated in admin dashboard)
            $result = $wpdb->insert(
                $table_name,
                array(
                    'service_name' => $service_name,
                    'item_name' => $item_name,
                    'item_description' => $item_description,
<<<<<<< HEAD
=======
                    'service_item_duration' => $service_item_duration,
>>>>>>> 3378c4f (plugin last vertation updated in admin dashboard)
                    'price1_name' => $price1_name,
                    'price1_value' => $price1_value,
                    'price2_name' => $price2_name,
                    'price2_value' => $price2_value,
                    'price3_name' => $price3_name,
                    'price3_value' => $price3_value,
                    'clean_price' => $price1_value,
                    'protect_price' => $price2_value,
                    'deodorize_price' => $price3_value,
                ),
<<<<<<< HEAD
                array('%s', '%s', '%s', '%s', '%f', '%s', '%f', '%s', '%f', '%f', '%f', '%f')
=======
                array('%s', '%s', '%s', '%d', '%s', '%f', '%s', '%f', '%s', '%f', '%f', '%f', '%f')
>>>>>>> 3378c4f (plugin last vertation updated in admin dashboard)
            );
            
            if ($result !== false) {
                $inserted_count++;
            } else {
                $errors[] = sprintf(__('Failed to add item: %s', 'xtremecleans'), $item_name);
            }
        }
        
        if ($inserted_count === 0) {
            wp_send_json_error(array('message' => __('Failed to add service items.', 'xtremecleans')));
        }
        
        $message = sprintf(
            _n(
                '%d item added successfully.',
                '%d items added successfully.',
                $inserted_count,
                'xtremecleans'
            ),
            $inserted_count
        );
        
        if (!empty($errors)) {
            $message .= ' ' . __('Some items could not be added.', 'xtremecleans');
        }
        
        wp_send_json_success(array(
            'message' => $message,
            'inserted_count' => $inserted_count
        ));
    }
    
    /**
     * AJAX handler: Add multiple service items
     *
     * @since 1.0.0
     */
    public function ajax_add_multiple_service_items() {
        check_ajax_referer('xtremecleans_add_zip', 'nonce');
        
        if (!current_user_can('manage_options')) {
            wp_send_json_error(array('message' => __('You do not have permission to perform this action.', 'xtremecleans')));
        }
        
        global $wpdb;
        
        // Ensure table exists
        $this->create_service_items_table();
        $table_name = $wpdb->prefix . 'xtremecleans_service_items';
        
        // Get and sanitize form data
        $service_name = isset($_POST['service_name']) ? sanitize_text_field($_POST['service_name']) : '';
        
        if (empty($service_name)) {
            wp_send_json_error(array('message' => __('Service Name is required.', 'xtremecleans')));
        }
        
        // Get items array
        $items = isset($_POST['items']) && is_array($_POST['items']) ? $_POST['items'] : array();
        
        if (empty($items)) {
            wp_send_json_error(array('message' => __('Please add at least one item.', 'xtremecleans')));
        }
        
        $inserted_count = 0;
        $errors = array();
        
        // Insert each item
        foreach ($items as $index => $item) {
            $item_name = isset($item['item_name']) ? sanitize_text_field($item['item_name']) : '';
            $item_description = isset($item['item_description']) ? sanitize_textarea_field($item['item_description']) : '';
            $clean_price = isset($item['clean_price']) ? floatval($item['clean_price']) : 0.00;
            $protect_price = isset($item['protect_price']) ? floatval($item['protect_price']) : 0.00;
            $deodorize_price = isset($item['deodorize_price']) ? floatval($item['deodorize_price']) : 0.00;
            
            // Skip if item name is empty
            if (empty($item_name)) {
                continue;
            }
            
            // Insert service item
<<<<<<< HEAD
=======
            $service_item_duration = isset($item['service_item_duration']) ? intval($item['service_item_duration']) : null;
>>>>>>> 3378c4f (plugin last vertation updated in admin dashboard)
            $result = $wpdb->insert(
                $table_name,
                array(
                    'service_name' => $service_name,
                    'item_name' => $item_name,
                    'item_description' => $item_description,
<<<<<<< HEAD
=======
                    'service_item_duration' => $service_item_duration,
>>>>>>> 3378c4f (plugin last vertation updated in admin dashboard)
                    'clean_price' => $clean_price,
                    'protect_price' => $protect_price,
                    'deodorize_price' => $deodorize_price,
                ),
<<<<<<< HEAD
                array('%s', '%s', '%s', '%f', '%f', '%f')
=======
                array('%s', '%s', '%s', '%d', '%f', '%f', '%f')
>>>>>>> 3378c4f (plugin last vertation updated in admin dashboard)
            );
            
            if ($result !== false) {
                $inserted_count++;
            } else {
                $errors[] = sprintf(__('Failed to add item: %s', 'xtremecleans'), $item_name);
            }
        }
        
        if ($inserted_count === 0) {
            wp_send_json_error(array('message' => __('No items were added. Please check your input.', 'xtremecleans')));
        }
        
        $message = sprintf(
            _n(
                '%d item added successfully.',
                '%d items added successfully.',
                $inserted_count,
                'xtremecleans'
            ),
            $inserted_count
        );
        
        if (!empty($errors)) {
            $message .= ' ' . __('Some items could not be added.', 'xtremecleans');
        }
        
        wp_send_json_success(array(
            'message' => $message,
            'inserted_count' => $inserted_count,
            'errors' => $errors
        ));
    }
    
    /**
     * AJAX handler: Update service item
     *
     * @since 1.0.0
     */
    public function ajax_update_service_item() {
        check_ajax_referer('xtremecleans_add_zip', 'nonce');
        
        if (!current_user_can('manage_options')) {
            wp_send_json_error(array('message' => __('You do not have permission to perform this action.', 'xtremecleans')));
        }
        
        global $wpdb;
        
        $table_name = $wpdb->prefix . 'xtremecleans_service_items';
        $item_id = isset($_POST['item_id']) ? absint($_POST['item_id']) : 0;
        
        if (empty($item_id)) {
            wp_send_json_error(array('message' => __('Item ID is required.', 'xtremecleans')));
        }
        
        // Get and sanitize form data
        $service_name = isset($_POST['service_name']) ? sanitize_text_field($_POST['service_name']) : '';
        $price1_name = 'Clean';
        $price2_name = 'Protect';
        $price3_name = 'Deodorize';
        
        if (empty($service_name)) {
            wp_send_json_error(array('message' => __('Service Name is required.', 'xtremecleans')));
        }
        
        $raw_items = $this->get_raw_service_item_rows_from_request();
        $item_data = !empty($raw_items) ? reset($raw_items) : array();
        
        $item_name = isset($item_data['item_name']) ? sanitize_text_field($item_data['item_name']) : '';
        $item_description = isset($item_data['item_description']) ? sanitize_textarea_field($item_data['item_description']) : '';
<<<<<<< HEAD
=======
        $service_item_duration = isset($item_data['service_item_duration']) ? intval($item_data['service_item_duration']) : null;
>>>>>>> 3378c4f (plugin last vertation updated in admin dashboard)
        $price1_value = isset($item_data['price1_value']) ? floatval($item_data['price1_value']) : 0.00;
        $price2_value = isset($item_data['price2_value']) ? floatval($item_data['price2_value']) : 0.00;
        $price3_value = isset($item_data['price3_value']) ? floatval($item_data['price3_value']) : 0.00;
        
        if (empty($item_name)) {
            wp_send_json_error(array('message' => __('Item Name is required.', 'xtremecleans')));
        }
        
        // Update service item
        $result = $wpdb->update(
            $table_name,
            array(
                'service_name' => $service_name,
                'item_name' => $item_name,
                'item_description' => $item_description,
<<<<<<< HEAD
=======
                'service_item_duration' => $service_item_duration,
>>>>>>> 3378c4f (plugin last vertation updated in admin dashboard)
                'price1_name' => $price1_name,
                'price1_value' => $price1_value,
                'price2_name' => $price2_name,
                'price2_value' => $price2_value,
                'price3_name' => $price3_name,
                'price3_value' => $price3_value,
                'clean_price' => $price1_value,
                'protect_price' => $price2_value,
                'deodorize_price' => $price3_value,
            ),
            array('id' => $item_id),
<<<<<<< HEAD
            array('%s', '%s', '%s', '%s', '%f', '%s', '%f', '%s', '%f', '%f', '%f', '%f'),
=======
            array('%s', '%s', '%s', '%d', '%s', '%f', '%s', '%f', '%s', '%f', '%f', '%f', '%f'),
>>>>>>> 3378c4f (plugin last vertation updated in admin dashboard)
            array('%d')
        );
        
        if ($result === false) {
            wp_send_json_error(array('message' => __('Failed to update service item.', 'xtremecleans')));
        }
        
        wp_send_json_success(array('message' => __('Service item updated successfully.', 'xtremecleans')));
    }
    
    /**
     * AJAX handler: Delete service item
     *
     * @since 1.0.0
     */
    public function ajax_delete_service_item() {
        check_ajax_referer('xtremecleans_add_zip', 'nonce');
        
        if (!current_user_can('manage_options')) {
            wp_send_json_error(array('message' => __('You do not have permission to perform this action.', 'xtremecleans')));
        }
        
        global $wpdb;
        
        $table_name = $wpdb->prefix . 'xtremecleans_service_items';
        $item_id = isset($_POST['item_id']) ? absint($_POST['item_id']) : 0;
        
        if (empty($item_id)) {
            wp_send_json_error(array('message' => __('Item ID is required.', 'xtremecleans')));
        }
        
        // Delete service item
        $result = $wpdb->delete(
            $table_name,
            array('id' => $item_id),
            array('%d')
        );
        
        if ($result === false) {
            wp_send_json_error(array('message' => __('Failed to delete service item.', 'xtremecleans')));
        }
        
        wp_send_json_success(array('message' => __('Service item deleted successfully.', 'xtremecleans')));
    }
    
    /**
     * AJAX handler: Create Stripe Payment Intent
     *
     * @since 1.0.0
     */
    public function ajax_create_payment_intent() {
        $nonce = isset($_POST['nonce']) ? sanitize_text_field(wp_unslash($_POST['nonce'])) : '';
        if (empty($nonce) || !wp_verify_nonce($nonce, 'xtremecleans_place_order')) {
            wp_send_json_error(array('message' => __('Security check failed.', 'xtremecleans')));
        }
        
        if (!file_exists(XTREMECLEANS_PLUGIN_DIR . 'core/payment/class-xtremecleans-stripe.php')) {
            wp_send_json_error(array('message' => __('Stripe integration is not available.', 'xtremecleans')));
        }
        
        require_once XTREMECLEANS_PLUGIN_DIR . 'core/payment/class-xtremecleans-stripe.php';
        
        if (!XtremeCleans_Stripe::is_configured()) {
            wp_send_json_error(array('message' => __('Stripe is not configured. Please contact support.', 'xtremecleans')));
        }
        
        $order_id = isset($_POST['order_id']) ? absint($_POST['order_id']) : 0;
        // Deposit is always $20.00, regardless of what frontend sends
        $amount = 20.00;
        
        if ($order_id <= 0) {
            wp_send_json_error(array('message' => __('Invalid order ID.', 'xtremecleans')));
        }
        
        // Verify order exists
        global $wpdb;
        $table_name = $wpdb->prefix . 'xtremecleans_orders';
        $order = $wpdb->get_row($wpdb->prepare("SELECT * FROM {$table_name} WHERE id = %d", $order_id));
        
        if (!$order) {
            wp_send_json_error(array('message' => __('Order not found.', 'xtremecleans')));
        }
        
        // Create payment intent
        $metadata = array(
            'order_id' => $order_id,
            'customer_email' => $order->email,
            'customer_name' => $order->first_name . ' ' . $order->last_name,
        );
        
        $payment_intent = XtremeCleans_Stripe::create_payment_intent($amount, 'usd', $metadata);
        
        if (is_wp_error($payment_intent)) {
            wp_send_json_error(array('message' => $payment_intent->get_error_message()));
        }
        
        // Update order with payment intent ID
        $wpdb->update(
            $table_name,
            array('stripe_payment_intent_id' => $payment_intent['id']),
            array('id' => $order_id),
            array('%s'),
            array('%d')
        );
        
        wp_send_json_success(array(
            'client_secret' => $payment_intent['client_secret'],
            'payment_intent_id' => $payment_intent['id'],
        ));
    }
    
    /**
     * AJAX handler: Confirm Payment and Create Jobber Job
     *
     * @since 1.0.0
     */
    public function ajax_confirm_payment() {
        // Log that the function was called
        xtremecleans_log('=== PAYMENT CONFIRMATION AJAX CALLED ===', 'info');
<<<<<<< HEAD
        xtremecleans_log('POST data: ' . wp_json_encode($_POST), 'info');
=======
>>>>>>> 3378c4f (plugin last vertation updated in admin dashboard)
        
        $nonce = isset($_POST['nonce']) ? sanitize_text_field(wp_unslash($_POST['nonce'])) : '';
        if (empty($nonce) || !wp_verify_nonce($nonce, 'xtremecleans_place_order')) {
            xtremecleans_log('Payment confirmation failed: Security check failed. Nonce: ' . ($nonce ? 'provided' : 'missing'), 'error');
            wp_send_json_error(array('message' => __('Security check failed.', 'xtremecleans')));
            return; // Explicit return
        }
        
        if (!file_exists(XTREMECLEANS_PLUGIN_DIR . 'core/payment/class-xtremecleans-stripe.php')) {
            wp_send_json_error(array('message' => __('Stripe integration is not available.', 'xtremecleans')));
        }
        
        require_once XTREMECLEANS_PLUGIN_DIR . 'core/payment/class-xtremecleans-stripe.php';
        
        $order_id = isset($_POST['order_id']) ? absint($_POST['order_id']) : 0;
        $payment_intent_id = isset($_POST['payment_intent_id']) ? sanitize_text_field(wp_unslash($_POST['payment_intent_id'])) : '';
        
        if ($order_id <= 0 || empty($payment_intent_id)) {
            wp_send_json_error(array('message' => __('Invalid payment data.', 'xtremecleans')));
        }
<<<<<<< HEAD
=======

        global $wpdb;
        $table_name = $wpdb->prefix . 'xtremecleans_orders';
        $order = $wpdb->get_row($wpdb->prepare("SELECT * FROM {$table_name} WHERE id = %d", $order_id));

        if (!$order) {
            wp_send_json_error(array('message' => __('Order not found.', 'xtremecleans')));
        }

        // Bind confirmation to the payment intent created for this order.
        if (!empty($order->stripe_payment_intent_id) && $order->stripe_payment_intent_id !== $payment_intent_id) {
            xtremecleans_log('Payment confirmation failed: Payment intent does not match order #' . $order_id, 'error');
            wp_send_json_error(array('message' => __('Payment verification failed for this order.', 'xtremecleans')));
        }
>>>>>>> 3378c4f (plugin last vertation updated in admin dashboard)
        
        // Verify payment intent
        xtremecleans_log('Payment confirmation started for order #' . $order_id . ' with payment intent: ' . $payment_intent_id, 'info');
        
        $payment_intent = XtremeCleans_Stripe::retrieve_payment_intent($payment_intent_id);
        
        if (is_wp_error($payment_intent)) {
            xtremecleans_log('Payment intent retrieval failed: ' . $payment_intent->get_error_message(), 'error');
            wp_send_json_error(array('message' => $payment_intent->get_error_message()));
        }
        
        // Log payment intent status for debugging
        $payment_status = isset($payment_intent['status']) ? $payment_intent['status'] : 'unknown';
        xtremecleans_log('Payment intent status: ' . $payment_status, 'info');
<<<<<<< HEAD
=======

        // Ensure Stripe metadata order binding also matches this order.
        $intent_order_id = isset($payment_intent['metadata']['order_id']) ? absint($payment_intent['metadata']['order_id']) : 0;
        if ($intent_order_id > 0 && $intent_order_id !== $order_id) {
            xtremecleans_log('Payment confirmation failed: Stripe metadata order mismatch for order #' . $order_id, 'error');
            wp_send_json_error(array('message' => __('Payment verification failed for this order.', 'xtremecleans')));
        }
>>>>>>> 3378c4f (plugin last vertation updated in admin dashboard)
        
        // CRITICAL: Verify payment was successful before proceeding
        // This ensures payment is REQUIRED before Jobber sync
        if (!XtremeCleans_Stripe::is_payment_successful($payment_intent)) {
            xtremecleans_log('Payment verification failed. Status: ' . $payment_status, 'error');
            wp_send_json_error(array(
                'message' => __('Payment was not successful. Status: ', 'xtremecleans') . $payment_status . '. Please try again.',
            ));
        }
        
        xtremecleans_log('Payment verified as successful. Proceeding with order update and Jobber sync.', 'info');
        
        // Payment successful - update order status
<<<<<<< HEAD
        global $wpdb;
        $table_name = $wpdb->prefix . 'xtremecleans_orders';
=======
>>>>>>> 3378c4f (plugin last vertation updated in admin dashboard)
        
        $charge_id = isset($payment_intent['charges']['data'][0]['id']) ? $payment_intent['charges']['data'][0]['id'] : '';
        $amount_paid = isset($payment_intent['amount']) ? ($payment_intent['amount'] / 100) : 0; // Convert from cents to dollars
        
        $update_result = $wpdb->update(
            $table_name,
            array(
                'payment_status' => 'paid',
                'stripe_charge_id' => $charge_id,
                'deposit_paid_at' => current_time('mysql'),
                'updated_at' => current_time('mysql'),
            ),
            array('id' => $order_id),
            array('%s', '%s', '%s', '%s'),
            array('%d')
        );
        
        if ($update_result === false) {
            xtremecleans_log('Failed to update order payment status in database for order #' . $order_id, 'error');
            wp_send_json_error(array('message' => __('Failed to update order status. Payment was successful but order update failed.', 'xtremecleans')));
        }
        
        xtremecleans_log('Order #' . $order_id . ' payment status updated to "paid". Charge ID: ' . $charge_id . ', Amount: $' . number_format($amount_paid, 2), 'info');
        
<<<<<<< HEAD
        // Get order data for Jobber integration
        $order = $wpdb->get_row($wpdb->prepare("SELECT * FROM {$table_name} WHERE id = %d", $order_id));
        
        if (!$order) {
            wp_send_json_error(array('message' => __('Order not found.', 'xtremecleans')));
        }
        
=======
>>>>>>> 3378c4f (plugin last vertation updated in admin dashboard)
        // Decode order payload
        $order_data = json_decode($order->payload, true);
        if (empty($order_data)) {
            wp_send_json_error(array('message' => __('Invalid order data.', 'xtremecleans')));
        }
        
        // IMPORTANT: Jobber sync happens ONLY after successful payment confirmation
        // This ensures payment is REQUIRED before sending to Jobber CRM
        xtremecleans_log('=== STARTING JOBBER SYNC AFTER PAYMENT ===', 'info');
<<<<<<< HEAD
        xtremecleans_log('Order data for Jobber sync: ' . wp_json_encode($order_data), 'info');
=======
>>>>>>> 3378c4f (plugin last vertation updated in admin dashboard)
        
        $jobber_result = $this->maybe_send_order_to_api($order_data);
        
        // Log detailed Jobber sync results
        xtremecleans_log('=== JOBBER SYNC RESULTS ===', 'info');
        xtremecleans_log('Overall success: ' . ($jobber_result['sent'] ? 'YES' : 'NO'), $jobber_result['sent'] ? 'info' : 'error');
        xtremecleans_log('Message: ' . $jobber_result['message'], $jobber_result['sent'] ? 'info' : 'error');
        
        if (!empty($jobber_result['results'])) {
            $client_sent = isset($jobber_result['results']['client']['sent']) ? $jobber_result['results']['client']['sent'] : false;
            $quote_sent = isset($jobber_result['results']['quote']['sent']) ? $jobber_result['results']['quote']['sent'] : false;
            $job_sent = isset($jobber_result['results']['job']['sent']) ? $jobber_result['results']['job']['sent'] : false;
            
            xtremecleans_log('Client created: ' . ($client_sent ? 'YES' : 'NO'), $client_sent ? 'info' : 'error');
            if (!$client_sent && !empty($jobber_result['results']['client']['message'])) {
                xtremecleans_log('Client error: ' . $jobber_result['results']['client']['message'], 'error');
            }
            
            xtremecleans_log('Quote created: ' . ($quote_sent ? 'YES' : 'NO'), $quote_sent ? 'info' : 'error');
            if (!$quote_sent && !empty($jobber_result['results']['quote']['message'])) {
                xtremecleans_log('Quote error: ' . $jobber_result['results']['quote']['message'], 'error');
            }
            
            xtremecleans_log('Job created: ' . ($job_sent ? 'YES' : 'NO'), $job_sent ? 'info' : 'error');
            if (!$job_sent && !empty($jobber_result['results']['job']['message'])) {
                xtremecleans_log('Job error: ' . $jobber_result['results']['job']['message'], 'error');
            }
        }
        
        // Update order with Jobber sync status
        $this->update_order_jobber_status($order_id, $jobber_result);
        
        // Update order with Jobber IDs if available
        if (!empty($jobber_result['results'])) {
            $update_data = array();
            $update_format = array();
            
            if (!empty($jobber_result['results']['client']['response']['id'])) {
                $update_data['jobber_client_id'] = $jobber_result['results']['client']['response']['id'];
                $update_format[] = '%s';
                xtremecleans_log('Saving Jobber Client ID: ' . $jobber_result['results']['client']['response']['id'], 'info');
            }
            if (!empty($jobber_result['results']['quote']['response']['id'])) {
                $update_data['jobber_quote_id'] = $jobber_result['results']['quote']['response']['id'];
                $update_format[] = '%s';
                xtremecleans_log('Saving Jobber Quote ID: ' . $jobber_result['results']['quote']['response']['id'], 'info');
            }
            if (!empty($jobber_result['results']['job']['response']['id'])) {
                $update_data['jobber_job_id'] = $jobber_result['results']['job']['response']['id'];
                $update_format[] = '%s';
                xtremecleans_log('Saving Jobber Job ID: ' . $jobber_result['results']['job']['response']['id'], 'info');
            }
            
            if (!empty($update_data)) {
                $update_data['updated_at'] = current_time('mysql');
                $update_format[] = '%s';
                $where_format = array('%d');
                
                $update_result = $wpdb->update(
                    $table_name,
                    $update_data,
                    array('id' => $order_id),
                    $update_format,
                    $where_format
                );
                
                if ($update_result !== false) {
                    xtremecleans_log('Order #' . $order_id . ' updated with Jobber IDs successfully', 'info');
                } else {
                    xtremecleans_log('Failed to update order #' . $order_id . ' with Jobber IDs', 'error');
                }
            }
        }
        
        xtremecleans_log('Payment confirmation completed for order #' . $order_id . '. Jobber sync: ' . ($jobber_result['sent'] ? 'SUCCESS' : 'FAILED'), $jobber_result['sent'] ? 'info' : 'error');
        
        // Build success message with Jobber sync details
        $success_message = __('Payment confirmed! Your appointment has been scheduled.', 'xtremecleans');
        if (!$jobber_result['sent']) {
            $success_message .= ' ' . __('Note: Some data may not have synced to Jobber. Please check admin dashboard.', 'xtremecleans');
        }
        
        $response_data = array(
            'message' => $success_message,
            'order_id' => $order_id,
            'jobber_sent' => $jobber_result['sent'],
            'jobber_message' => $jobber_result['message'],
            'jobber_details' => array(
                'client_created' => !empty($jobber_result['results']['client']['sent']),
                'quote_created' => !empty($jobber_result['results']['quote']['sent']),
                'job_created' => !empty($jobber_result['results']['job']['sent']),
            ),
        );
        
<<<<<<< HEAD
        xtremecleans_log('Sending success response: ' . wp_json_encode($response_data), 'info');
=======
        xtremecleans_log('Sending payment confirmation response for order #' . $order_id, 'info');
>>>>>>> 3378c4f (plugin last vertation updated in admin dashboard)
        
        // Send response and ensure script stops
        wp_send_json_success($response_data);
        
        // Ensure script stops here (wp_send_json_success already calls wp_die, but explicit is better)
        exit;
    }
    
    /**
     * AJAX handler: Get recent logs
     *
     * @since 1.0.0
     */
    public function ajax_get_recent_logs() {
        if (!current_user_can('manage_options')) {
            wp_send_json_error(array('message' => __('Permission denied.', 'xtremecleans')));
        }
        
        if (!isset($_POST['nonce']) || !wp_verify_nonce($_POST['nonce'], 'xtremecleans_logs')) {
            wp_send_json_error(array('message' => __('Security check failed.', 'xtremecleans')));
        }
        
        // Get recent logs from error log file
        $logs = array();
        $log_file = null;
        
        // Try to find error log file
        $possible_locations = array(
            ini_get('error_log'),
            WP_CONTENT_DIR . '/debug.log',
            ABSPATH . 'wp-content/debug.log',
            get_option('xtremecleans_log_file_path', ''),
        );
        
        foreach ($possible_locations as $location) {
            if (!empty($location) && file_exists($location) && is_readable($location)) {
                $log_file = $location;
                break;
            }
        }
        
        if ($log_file) {
            // Read last 100 lines from log file
            $lines = file($log_file);
            if ($lines) {
                $xtremecleans_logs = array();
                foreach ($lines as $line) {
                    if (strpos($line, '[XtremeCleans') !== false || strpos($line, '[XTREMECLEANS') !== false) {
                        $xtremecleans_logs[] = trim($line);
                    }
                }
                // Get last 50 XtremeCleans logs
                $logs = array_slice($xtremecleans_logs, -50);
                $logs = array_reverse($logs); // Most recent first
            }
        } else {
            // If log file not found, try to get from WordPress debug log
            if (defined('WP_DEBUG_LOG') && WP_DEBUG_LOG) {
                $debug_log = WP_CONTENT_DIR . '/debug.log';
                if (file_exists($debug_log) && is_readable($debug_log)) {
                    $lines = file($debug_log);
                    if ($lines) {
                        $xtremecleans_logs = array();
                        foreach ($lines as $line) {
                            if (strpos($line, '[XtremeCleans') !== false || strpos($line, '[XTREMECLEANS') !== false) {
                                $xtremecleans_logs[] = trim($line);
                            }
                        }
                        $logs = array_slice($xtremecleans_logs, -50);
                        $logs = array_reverse($logs);
                    }
                }
            }
        }
        
        // Always return success, even if no logs found (so UI can show appropriate message)
        wp_send_json_success(array(
            'logs' => $logs,
            'log_file' => $log_file,
            'logs_count' => count($logs),
            'message' => $log_file ? sprintf(__('Found %d recent log entries.', 'xtremecleans'), count($logs)) : __('Log file not found. Enable WP_DEBUG_LOG in wp-config.php to create debug.log file.', 'xtremecleans'),
        ));
    }
}
<<<<<<< HEAD

=======
>>>>>>> 3378c4f (plugin last vertation updated in admin dashboard)
