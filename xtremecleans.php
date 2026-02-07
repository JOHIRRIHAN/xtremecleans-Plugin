<?php
/**
 * Plugin Name: XtremeCleans
 * Plugin URI: https://example.com/xtremecleans
 * Description: A professional WordPress plugin with shortcodes, API integrations, and frontend features.
 * Version: 1.1.0
 * Author: Your Name
 * Author URI: https://example.com
 * License: GPL v2 or later
 * License URI: https://www.gnu.org/licenses/gpl-2.0.html
 * Text Domain: xtremecleans
 * Domain Path: /languages
 * Requires at least: 5.0
 * Requires PHP: 7.4
 *
 * @package XtremeCleans
 * @since 1.0.0
 */

// Exit if accessed directly
if (!defined('ABSPATH')) {
    exit;
}

// Define plugin constants
define('XTREMECLEANS_VERSION', '1.1.0');
define('XTREMECLEANS_PLUGIN_DIR', plugin_dir_path(__FILE__));
define('XTREMECLEANS_PLUGIN_URL', plugin_dir_url(__FILE__));
define('XTREMECLEANS_PLUGIN_FILE', __FILE__);
define('XTREMECLEANS_PLUGIN_BASENAME', plugin_basename(__FILE__));

// Register uninstall hook (must be at top level)
register_uninstall_hook(XTREMECLEANS_PLUGIN_FILE, 'xtremecleans_uninstall');

/**
 * Main XtremeCleans Plugin Class
 *
 * @since 1.0.0
 */
class XtremeCleans
{
    
    /**
     * Instance of this class
     *
     * @since 1.0.0
     * @var XtremeCleans
     */
    private static $instance = null;
    
    /**
     * API instance
     *
     * @since 1.0.0
     * @var XtremeCleans_API
     */
    public $api;
    
    /**
     * Shortcodes instance
     *
     * @since 1.0.0
     * @var XtremeCleans_Shortcodes
     */
    public $shortcodes;
    
    /**
     * Frontend instance
     *
     * @since 1.0.0
     * @var XtremeCleans_Frontend
     */
    public $frontend;
    
    /**
     * Admin instance
     *
     * @since 1.0.0
     * @var XtremeCleans_Admin
     */
    public $admin;
    
    /**
     * Get instance of this class
     *
     * @since 1.0.0
     * @return XtremeCleans Instance
     */
    public static function get_instance() {
        if (null === self::$instance) {
            self::$instance = new self();
        }
        return self::$instance;
    }
    
    /**
     * Constructor
     *
     * @since 1.0.0
     */
    private function __construct() {
        $this->init();
    }
    
    /**
     * Initialize the plugin
     *
     * @since 1.0.0
     */
    private function init() {
        // Load core functions first
        $this->load_core();
        
        // Load plugin textdomain
        add_action('plugins_loaded', array($this, 'load_textdomain'));
        
        // Add activation and deactivation hooks
        register_activation_hook(XTREMECLEANS_PLUGIN_FILE, array($this, 'activate'));
        register_deactivation_hook(XTREMECLEANS_PLUGIN_FILE, array($this, 'deactivate'));
        
        // Initialize plugin functionality
        $this->load_dependencies();
        
        // Load admin using hook to ensure proper timing
        add_action('admin_init', array($this, 'load_admin'), 1);
        add_action('plugins_loaded', array($this, 'maybe_load_admin'), 1);
    }
    
    /**
     * Load core functions
     *
     * @since 1.0.0
     */
    private function load_core() {
        $functions_file = XTREMECLEANS_PLUGIN_DIR . 'core/functions/xtremecleans-functions.php';
        if (file_exists($functions_file)) {
            require_once $functions_file;
        }
    }
    
    /**
     * Load plugin textdomain for translations
     *
     * @since 1.0.0
     */
    public function load_textdomain() {
        load_plugin_textdomain(
            'xtremecleans',
            false,
            dirname(XTREMECLEANS_PLUGIN_BASENAME) . '/languages'
        );
    }
    
    /**
     * Activation hook
     *
     * @since 1.0.0
     */
    public function activate() {
        try {
            // Create database tables
            $this->create_database_tables();
            
            // Set default options
            if (false === get_option('xtremecleans_cache_duration')) {
                update_option('xtremecleans_cache_duration', 3600);
            }
            
            if (false === get_option('xtremecleans_button_default_style')) {
                update_option('xtremecleans_button_default_style', 'primary');
            }
            
            // Flush rewrite rules
            flush_rewrite_rules();
            
            // Ensure OAuth callback rewrite rules are added
            if (class_exists('XtremeCleans_Frontend')) {
                $frontend = new XtremeCleans_Frontend();
                if (method_exists($frontend, 'add_rewrite_rules')) {
                    $frontend->add_rewrite_rules();
                    flush_rewrite_rules();
                }
            }
            
            // Log activation (only if function exists)
            if (function_exists('xtremecleans_log')) {
                xtremecleans_log('Plugin activated', 'info');
            }
        } catch (Exception $e) {
            // Log error if logging function exists
            if (function_exists('xtremecleans_log')) {
                xtremecleans_log('Activation error: ' . $e->getMessage(), 'error');
            }
            // Don't throw - allow activation to complete
        }
    }
    
    /**
     * Deactivation hook
     *
     * @since 1.0.0
     */
    public function deactivate() {
        // Flush rewrite rules
        flush_rewrite_rules();
        
        // Log deactivation (only if function exists)
        if (function_exists('xtremecleans_log')) {
            xtremecleans_log('Plugin deactivated', 'info');
        }
    }
    
    /**
     * Create database tables
     *
     * @since 1.0.0
     */
    private function create_database_tables() {
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
        
        // Also create the alternative table if needed
        $table_name_alt = $wpdb->prefix . 'xc_zip_reference';
        
        $sql_alt = "CREATE TABLE IF NOT EXISTS `{$table_name_alt}` (
            `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT,
            `zip_code` varchar(10) NOT NULL,
            `service_name` varchar(100) DEFAULT NULL,
            `zone_name` varchar(100) NOT NULL,
            `zone_area` varchar(100) DEFAULT NULL,
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
        
        dbDelta($sql_alt);
        
        // Create service items table
        $service_items_table = $wpdb->prefix . 'xtremecleans_service_items';
        
        $sql_service_items = "CREATE TABLE IF NOT EXISTS `{$service_items_table}` (
            `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT,
            `service_name` varchar(100) NOT NULL,
            `item_name` varchar(100) NOT NULL,
            `item_description` varchar(255) DEFAULT NULL,
            `clean_price` decimal(10,2) DEFAULT '0.00',
            `protect_price` decimal(10,2) DEFAULT '0.00',
            `deodorize_price` decimal(10,2) DEFAULT '0.00',
            `created_at` datetime DEFAULT NULL,
            `updated_at` datetime DEFAULT NULL,
            PRIMARY KEY (`id`),
            KEY `service_name` (`service_name`)
        ) {$charset_collate};";
        
        dbDelta($sql_service_items);
        
        // Create leads table
        $leads_table_name = $wpdb->prefix . 'xtremecleans_leads';
        
        $sql_leads = "CREATE TABLE IF NOT EXISTS `{$leads_table_name}` (
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
        
        dbDelta($sql_leads);
        
        // Create orders table
        $orders_table = $wpdb->prefix . 'xtremecleans_orders';
        $sql_orders = "CREATE TABLE IF NOT EXISTS `{$orders_table}` (
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
            `created_at` datetime DEFAULT NULL,
            `updated_at` datetime DEFAULT NULL,
            PRIMARY KEY (`id`),
            KEY `email` (`email`),
            KEY `appointment_date` (`appointment_date`)
        ) {$charset_collate};";
        
        dbDelta($sql_orders);
    }
    
    /**
     * Load plugin dependencies
     *
     * @since 1.0.0
     */
    private function load_dependencies() {
        // Load API class
        $api_file = XTREMECLEANS_PLUGIN_DIR . 'core/api/class-xtremecleans-api.php';
        if (file_exists($api_file)) {
            require_once $api_file;
            if (class_exists('XtremeCleans_API')) {
                $this->api = new XtremeCleans_API();
            }
        }
        
        // Load Shortcodes class
        $shortcodes_file = XTREMECLEANS_PLUGIN_DIR . 'core/shortcodes/class-xtremecleans-shortcodes.php';
        if (file_exists($shortcodes_file)) {
            require_once $shortcodes_file;
            if (class_exists('XtremeCleans_Shortcodes') && isset($this->api)) {
                $this->shortcodes = new XtremeCleans_Shortcodes($this->api);
            }
        }
        
        // Load Frontend class
        $frontend_file = XTREMECLEANS_PLUGIN_DIR . 'core/frontend/class-xtremecleans-frontend.php';
        if (file_exists($frontend_file)) {
            require_once $frontend_file;
            if (class_exists('XtremeCleans_Frontend')) {
                $this->frontend = new XtremeCleans_Frontend();
            }
        }

        // Load Elementor integration if Elementor is active
        add_action('elementor/init', array($this, 'load_elementor'));
    }

    /**
     * Load Elementor integration
     *
     * @since 1.1.0
     */
    public function load_elementor() {
        $elementor_file = XTREMECLEANS_PLUGIN_DIR . 'core/elementor/class-xtremecleans-elementor.php';
        if (file_exists($elementor_file)) {
            require_once $elementor_file;
            if (class_exists('XtremeCleans_Elementor')) {
                XtremeCleans_Elementor::get_instance();
            }
        }
    }
    
    /**
     * Maybe load admin functionality
     *
     * @since 1.0.0
     */
    public function maybe_load_admin() {
        if (is_admin() || (defined('DOING_AJAX') && DOING_AJAX)) {
            $this->load_admin();
        }
    }
    
    /**
     * Load admin functionality
     *
     * @since 1.0.0
     */
    public function load_admin() {
        // Prevent double loading
        if (isset($this->admin) && $this->admin instanceof XtremeCleans_Admin) {
            return;
        }
        
        // Load Admin class
        $admin_file = XTREMECLEANS_PLUGIN_DIR . 'admin/class-xtremecleans-admin.php';
        if (file_exists($admin_file)) {
            try {
                require_once $admin_file;
                if (class_exists('XtremeCleans_Admin')) {
                    $this->admin = new XtremeCleans_Admin();
                }
            } catch (Exception $e) {
                // Silently fail - will be handled by fallback
                if (defined('WP_DEBUG') && WP_DEBUG) {
                    error_log('XtremeCleans Admin Error: ' . $e->getMessage());
                }
            } catch (Error $e) {
                // Silently fail - will be handled by fallback
                if (defined('WP_DEBUG') && WP_DEBUG) {
                    error_log('XtremeCleans Admin Fatal Error: ' . $e->getMessage());
                }
            }
        }
    }
}

/**
 * Initialize the plugin
 *
 * @since 1.0.0
 * @return XtremeCleans Plugin instance
 */
function xtremecleans_init() {
    return XtremeCleans::get_instance();
}

// Start the plugin
xtremecleans_init();

// Ensure admin menu registers (fallback with late priority)
add_action('admin_menu', 'xtremecleans_ensure_menu', 999);
function xtremecleans_ensure_menu() {
    if (!is_admin() || !current_user_can('manage_options')) {
        return;
    }
    
    // Check if menu already exists
    global $menu;
    $menu_exists = false;
    if (is_array($menu)) {
        foreach ($menu as $item) {
            if (isset($item[2]) && $item[2] === 'xtremecleans') {
                $menu_exists = true;
                break;
            }
        }
    }
    
    // If menu doesn't exist, create it
    if (!$menu_exists) {
        $instance = XtremeCleans::get_instance();
        
        // Try to load admin class
        if (!isset($instance->admin)) {
            $instance->load_admin();
        }
        
        // If admin class loaded, let it register menu
        if (isset($instance->admin) && $instance->admin instanceof XtremeCleans_Admin) {
            if (method_exists($instance->admin, 'add_admin_menu')) {
                // Remove any existing fallback menu first
                remove_menu_page('xtremecleans');
                $instance->admin->add_admin_menu();
            }
        } else {
            // Fallback: register basic menu (only if not already registered)
            if (!$menu_exists) {
                add_menu_page(
                    'XtremeCleans',
                    'XtremeCleans',
                    'manage_options',
                    'xtremecleans',
                    'xtremecleans_fallback_page',
                    'dashicons-admin-generic',
                    30
                );
                
                // Add submenu items
                add_submenu_page(
                    'xtremecleans',
                    'Dashboard',
                    'Dashboard',
                    'manage_options',
                    'xtremecleans',
                    'xtremecleans_fallback_page'
                );
                
                add_submenu_page(
                    'xtremecleans',
                    'Zip Zone',
                    'Zip Zone',
                    'manage_options',
                    'xtremecleans-zip-zone',
                    'xtremecleans_fallback_page'
                );
                
                add_submenu_page(
                    'xtremecleans',
                    'Settings',
                    'Settings',
                    'manage_options',
                    'xtremecleans-settings',
                    'xtremecleans_fallback_page'
                );
            }
        }
    }
}

// Fallback page function
function xtremecleans_fallback_page() {
    $instance = XtremeCleans::get_instance();
    
    // Try to load admin class if not loaded
    if (!isset($instance->admin)) {
        $instance->load_admin();
    }
    
    // If admin class is now loaded, use it
    if (isset($instance->admin) && $instance->admin instanceof XtremeCleans_Admin) {
        if (method_exists($instance->admin, 'render_dashboard_page')) {
            try {
                $instance->admin->render_dashboard_page();
                return;
            } catch (Exception $e) {
                // Continue to fallback
            } catch (Error $e) {
                // Continue to fallback
            }
        }
    }
    
    // If still not loaded, try to load admin file directly
    $admin_file = XTREMECLEANS_PLUGIN_DIR . 'admin/class-xtremecleans-admin.php';
    if (file_exists($admin_file)) {
        try {
            require_once $admin_file;
            if (class_exists('XtremeCleans_Admin')) {
                $instance->admin = new XtremeCleans_Admin();
                if (method_exists($instance->admin, 'render_dashboard_page')) {
                    $instance->admin->render_dashboard_page();
                    return;
                }
            }
        } catch (Exception $e) {
            // Continue to error message
        } catch (Error $e) {
            // Continue to error message
        }
    }
    
    // Show basic dashboard if template loading fails
    $page = isset($_GET['page']) ? sanitize_text_field($_GET['page']) : 'xtremecleans';
    
    echo '<div class="wrap">';
    echo '<h1>XtremeCleans</h1>';
    
    // Try one more time to load admin class
    if (!isset($instance->admin)) {
        $instance->load_admin();
    }
    
    // If admin class is now available, try to render the specific page
    if (isset($instance->admin) && $instance->admin instanceof XtremeCleans_Admin) {
        switch ($page) {
            case 'xtremecleans-zip-zone':
                if (method_exists($instance->admin, 'render_zip_zone_page')) {
                    $instance->admin->render_zip_zone_page();
                    echo '</div>';
                    return;
                }
                break;
            case 'xtremecleans-settings':
                if (method_exists($instance->admin, 'render_settings_page')) {
                    $instance->admin->render_settings_page();
                    echo '</div>';
                    return;
                }
                break;
            case 'xtremecleans':
            default:
                if (method_exists($instance->admin, 'render_dashboard_page')) {
                    $instance->admin->render_dashboard_page();
                    echo '</div>';
                    return;
                }
                break;
        }
    }
    
    // Fallback content
    echo '<div class="notice notice-info"><p>Welcome to XtremeCleans Dashboard</p></div>';
    echo '<h2>Quick Links</h2>';
    echo '<ul>';
    echo '<li><a href="' . admin_url('admin.php?page=xtremecleans-zip-zone') . '">Zip Zone</a></li>';
    echo '<li><a href="' . admin_url('admin.php?page=xtremecleans-settings') . '">Settings</a></li>';
    echo '<li><a href="' . admin_url('admin.php?page=xtremecleans-shortcodes') . '">Shortcodes</a></li>';
    echo '</ul>';
    echo '<p><a href="' . admin_url('admin.php?page=xtremecleans') . '" class="button button-primary">Refresh Dashboard</a></p>';
    echo '</div>';
}

/**
 * Uninstall hook - Clean up all plugin data
 *
 * This function is called when the plugin is deleted from WordPress.
 * It removes all options, transients, and any other data created by the plugin.
 *
 * @since 1.0.0
 */
function xtremecleans_uninstall() {
    // Verify user has permission to uninstall
    if (!current_user_can('activate_plugins')) {
        return;
    }
    
    // Check if WordPress is loaded
    if (!defined('ABSPATH') || !defined('WPINC')) {
        return;
    }
    
    global $wpdb;
    
    // Get table prefix
    $table_prefix = $wpdb->prefix;
    
    // Drop database tables created by this plugin
    $tables_to_drop = array(
        $table_prefix . 'xtremecleans_zip_reference',
        $table_prefix . 'xc_zip_reference',
        $table_prefix . 'xtremecleans_service_items',
        $table_prefix . 'xtremecleans_leads',
    );
    
    foreach ($tables_to_drop as $table) {
        // Escape table name for safe SQL query
        $table_name = esc_sql($table);
        
        // Drop table if it exists (DROP TABLE IF EXISTS is safe)
        $wpdb->query("DROP TABLE IF EXISTS `{$table_name}`");
    }
    
    // Delete all plugin options
    $options_to_delete = array(
        'xtremecleans_api_url',
        'xtremecleans_api_key',
        'xtremecleans_cache_duration',
        'xtremecleans_enable_logging',
        'xtremecleans_button_default_style',
        'xtremecleans_form_email_recipient',
        'xtremecleans_custom_css',
        'xtremecleans_forms_submitted',
    );
    
    foreach ($options_to_delete as $option) {
        delete_option($option);
    }
    
    // Clean up any transients (if they exist)
    // Note: WordPress stores transients with specific prefixes
    
    // Delete transients with our prefix
    $wpdb->query(
        "DELETE FROM {$wpdb->options} 
        WHERE option_name LIKE '_transient_xtremecleans_%' 
        OR option_name LIKE '_transient_timeout_xtremecleans_%'"
    );
    
    // Also clean up site transients (for multisite)
    if (is_multisite()) {
        $wpdb->query(
            "DELETE FROM {$wpdb->sitemeta} 
            WHERE meta_key LIKE '_transient_xtremecleans_%' 
            OR meta_key LIKE '_transient_timeout_xtremecleans_%'"
        );
    }
    
    // Flush rewrite rules
    flush_rewrite_rules();
}