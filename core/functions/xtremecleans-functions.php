<?php
/**
 * XtremeCleans Utility Functions
 *
 * @package XtremeCleans
 * @since 1.0.0
 */

// Exit if accessed directly
if (!defined('ABSPATH')) {
    exit;
}

/**
 * Get plugin option with default value
 *
 * @since 1.0.0
 * @param string $option_name Option name
 * @param mixed  $default      Default value
 * @return mixed Option value
 */
function xtremecleans_get_option($option_name, $default = '') {
    return get_option('xtremecleans_' . $option_name, $default);
}

/**
 * Update plugin option
 *
 * @since 1.0.0
 * @param string $option_name  Option name
 * @param mixed  $option_value Option value
 * @return bool Success status
 */
function xtremecleans_update_option($option_name, $option_value) {
    return update_option('xtremecleans_' . $option_name, $option_value);
}

/**
 * Sanitize text field
 *
 * @since 1.0.0
 * @param string $value Value to sanitize
 * @return string Sanitized value
 */
function xtremecleans_sanitize_text($value) {
    return sanitize_text_field($value);
}

/**
 * Sanitize textarea field
 *
 * @since 1.0.0
 * @param string $value Value to sanitize
 * @return string Sanitized value
 */
function xtremecleans_sanitize_textarea($value) {
    return sanitize_textarea_field($value);
}

/**
 * Sanitize URL
 *
 * @since 1.0.0
 * @param string $value Value to sanitize
 * @return string Sanitized URL
 */
function xtremecleans_sanitize_url($value) {
    return esc_url_raw($value);
}

/**
 * Sanitize email
 *
 * @since 1.0.0
 * @param string $value Value to sanitize
 * @return string Sanitized email
 */
function xtremecleans_sanitize_email($value) {
    return sanitize_email($value);
}

/**
 * Get plugin version
 *
 * @since 1.0.0
 * @return string Plugin version
 */
function xtremecleans_get_version() {
    return XTREMECLEANS_VERSION;
}

/**
 * Get plugin directory path
 *
 * @since 1.0.0
 * @return string Plugin directory path
 */
function xtremecleans_get_plugin_dir() {
    return XTREMECLEANS_PLUGIN_DIR;
}

/**
 * Get plugin directory URL
 *
 * @since 1.0.0
 * @return string Plugin directory URL
 */
function xtremecleans_get_plugin_url() {
    return XTREMECLEANS_PLUGIN_URL;
}

/**
 * Get template path
 *
 * @since 1.0.0
 * @param string $template_name Template name
 * @param string $type          Template type (admin/frontend)
 * @return string Template path
 */
function xtremecleans_get_template_path($template_name, $type = 'frontend') {
    $template_path = xtremecleans_get_plugin_dir() . 'ui/templates/' . $type . '/' . $template_name . '.php';
    
    // Allow theme override
    $theme_template = get_template_directory() . '/xtremecleans/' . $type . '/' . $template_name . '.php';
    
    if (file_exists($theme_template)) {
        return $theme_template;
    }
    
    return $template_path;
}

/**
 * Load template
 *
 * @since 1.0.0
 * @param string $template_name Template name
 * @param array  $args          Template arguments
 * @param string $type          Template type (admin/frontend)
 */
function xtremecleans_load_template($template_name, $args = array(), $type = 'frontend') {
    $template_path = xtremecleans_get_template_path($template_name, $type);
    
    if (file_exists($template_path)) {
        extract($args);
        include $template_path;
    }
}

/**
 * Log message (if logging is enabled)
 *
 * @since 1.0.0
 * @param string $message Log message
 * @param string $level   Log level (info, warning, error)
 */
function xtremecleans_log($message, $level = 'info') {
    // Always log if WP_DEBUG_LOG is enabled, regardless of plugin setting
    // This ensures we can debug issues even if plugin logging is disabled
    $should_log = false;
    
    // Check if plugin logging is enabled
    if (xtremecleans_get_option('enable_logging', false)) {
        $should_log = true;
    }
    
    // Also log if WP_DEBUG_LOG is enabled (WordPress debug mode)
    if (defined('WP_DEBUG_LOG') && WP_DEBUG_LOG) {
        $should_log = true;
    }

    // Force log if running in CLI
    if (php_sapi_name() === 'cli') {
        $should_log = true;
    }
    
    if (!$should_log) {
        return;
    }
    
    if (function_exists('error_log')) {
        $log_message = sprintf(
            '[XtremeCleans %s] %s: %s',
            strtoupper($level),
            current_time('mysql'),
            $message
        );
        
        // Echo to stdout if running in CLI
        if (php_sapi_name() === 'cli') {
            echo $log_message . "\n";
        }
        
        // Try to write to WordPress debug log first
        if (defined('WP_DEBUG_LOG') && WP_DEBUG_LOG) {
            $debug_log = WP_CONTENT_DIR . '/debug.log';
            // Use error_log with 3rd parameter to specify file
            error_log($log_message, 3, $debug_log);
        } else {
            // Fallback to default error_log
            error_log($log_message);
        }
    }
}

/**
 * Increment form submission counter
 *
 * @since 1.0.0
 */
function xtremecleans_increment_form_counter() {
    $count = xtremecleans_get_option('forms_submitted', 0);
    xtremecleans_update_option('forms_submitted', $count + 1);
}

/**
 * Get form submission count
 *
 * @since 1.0.0
 * @return int Form submission count
 */
function xtremecleans_get_form_count() {
    return (int) xtremecleans_get_option('forms_submitted', 0);
}

/**
 * Check if API is configured
 *
 * @since 1.0.0
 * @return bool True if configured
 */
function xtremecleans_is_api_configured() {
    // Check for Jobber OAuth first (priority)
    $client_id = xtremecleans_get_option('jobber_client_id', '');
    $client_secret = xtremecleans_get_option('jobber_client_secret', '');
    $access_token = get_option('xtremecleans_jobber_access_token', '');
    
    if (!empty($client_id) && !empty($client_secret) && !empty($access_token)) {
        return true;
    }
    
    // Fallback to legacy API credentials
    $api_url = xtremecleans_get_option('api_url', '');
    $api_key = xtremecleans_get_option('api_key', '');
    
    return !empty($api_url) && !empty($api_key);
}

