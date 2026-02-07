<?php
/**
 * Shortcodes Handler Class
 *
 * Handles shortcode registration
 *
 * @package XtremeCleans
 * @subpackage Shortcodes
 * @since 1.0.0
 */

// Exit if accessed directly
if (!defined('ABSPATH')) {
    exit;
}

/**
 * XtremeCleans_Shortcodes Class
 *
 * @since 1.0.0
 */
class XtremeCleans_Shortcodes {
    
    /**
     * API instance
     *
     * @since 1.0.0
     * @var XtremeCleans_API
     */
    private $api;
    
    /**
     * Constructor
     *
     * @since 1.0.0
     * @param XtremeCleans_API $api API instance
     */
    public function __construct($api = null) {
        $this->api = $api;
        $this->register_shortcodes();
    }
    
    /**
     * Register all shortcodes
     *
     * @since 1.0.0
     */
    private function register_shortcodes() {
        // Register main XtremeCleans design shortcode (only shortcode needed)
        $main_file = XTREMECLEANS_PLUGIN_DIR . 'core/shortcodes/class-xtremecleans-main.php';
        if (file_exists($main_file)) {
            require_once $main_file;
            if (class_exists('XtremeCleans_Main_Shortcode')) {
                XtremeCleans_Main_Shortcode::register();
            }
        }
    }
}
