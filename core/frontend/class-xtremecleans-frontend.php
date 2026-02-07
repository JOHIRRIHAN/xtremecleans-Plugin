<?php
/**
 * Frontend Features Handler Class
 *
 * Handles all frontend functionality and assets
 *
 * @package XtremeCleans
 * @subpackage Frontend
 * @since 1.0.0
 */

// Exit if accessed directly
if (!defined('ABSPATH')) {
    exit;
}

/**
 * XtremeCleans_Frontend Class
 *
 * @since 1.0.0
 */
class XtremeCleans_Frontend {
    
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
        add_action('wp_enqueue_scripts', array($this, 'enqueue_scripts'));
        add_action('wp_footer', array($this, 'add_inline_scripts'));
        add_action('init', array($this, 'handle_form_submission'));
        add_action('init', array($this, 'handle_jobber_oauth_callback'));
        add_action('init', array($this, 'add_rewrite_rules'));
        add_filter('query_vars', array($this, 'add_query_vars'));
        add_action('template_redirect', array($this, 'handle_oauth_callback_redirect'));
    }
    
    /**
     * Enqueue scripts and styles
     *
     * @since 1.0.0
     */
    public function enqueue_scripts() {
        // Enqueue CSS
        wp_enqueue_style(
            'xtremecleans-style',
            XTREMECLEANS_PLUGIN_URL . 'ui/assets/css/xtremecleans.css',
            array(),
            XTREMECLEANS_VERSION
        );
        
        // Check if Stripe is enabled and load Stripe.js
        $stripe_enabled = false;
        $stripe_publishable_key = '';
        
        if (file_exists(XTREMECLEANS_PLUGIN_DIR . 'core/payment/class-xtremecleans-stripe.php')) {
            require_once XTREMECLEANS_PLUGIN_DIR . 'core/payment/class-xtremecleans-stripe.php';
            if (XtremeCleans_Stripe::is_enabled() && XtremeCleans_Stripe::is_configured()) {
                $stripe_enabled = true;
                $keys = XtremeCleans_Stripe::get_api_keys();
                $stripe_publishable_key = $keys['publishable_key'];
                
                // Load Stripe.js
                wp_enqueue_script(
                    'stripe-js',
                    'https://js.stripe.com/v3/',
                    array(),
                    null,
                    false
                );
            }
        }
        
        // Enqueue JavaScript
        wp_enqueue_script(
            'xtremecleans-script',
            XTREMECLEANS_PLUGIN_URL . 'ui/assets/js/xtremecleans.js',
            $stripe_enabled ? array('jquery', 'stripe-js') : array('jquery'),
            XTREMECLEANS_VERSION,
            true
        );
        
        // Localize script for AJAX
        wp_localize_script(
            'xtremecleans-script',
            'xtremecleansData',
            array(
                'ajaxUrl' => admin_url('admin-ajax.php'),
                'nonce'   => wp_create_nonce('xtremecleans_add_zip'),
                'placeOrderNonce' => wp_create_nonce('xtremecleans_place_order'),
                'homeUrl' => home_url('/'),
                'jobberAuthUrl' => $this->get_jobber_authorize_url(),
                'stripeEnabled' => $stripe_enabled,
                'stripePublishableKey' => $stripe_publishable_key,
            )
        );
        
        // Add custom CSS if set
        $custom_css = xtremecleans_get_option('custom_css', '');
        if (!empty($custom_css)) {
            wp_add_inline_style('xtremecleans-style', $custom_css);
        }
    }
    
    /**
     * Add inline scripts
     *
     * @since 1.0.0
     */
    public function add_inline_scripts() {
        xtremecleans_load_template('frontend-inline-scripts', array(), 'frontend');
    }
    
    /**
     * Handle form submission
     *
     * @since 1.0.0
     */
    public function handle_form_submission() {
        if (!isset($_POST['xtremecleans_nonce']) || 
            !wp_verify_nonce($_POST['xtremecleans_nonce'], 'xtremecleans_form_submit')) {
            return;
        }
        
        if (!isset($_POST['xtremecleans_name']) || 
            !isset($_POST['xtremecleans_email']) || 
            !isset($_POST['xtremecleans_message'])) {
            return;
        }
        
        $name    = xtremecleans_sanitize_text($_POST['xtremecleans_name']);
        $email   = xtremecleans_sanitize_email($_POST['xtremecleans_email']);
        $message = xtremecleans_sanitize_textarea($_POST['xtremecleans_message']);
        
        // Validate email
        if (!is_email($email)) {
            add_action('wp_footer', function() {
                echo '<script>alert("' . esc_js(__('Invalid email address.', 'xtremecleans')) . '");</script>';
            });
            return;
        }
        
        // Process form data
        $this->process_form_data($name, $email, $message);
        
        // Redirect to prevent resubmission
        $redirect_url = add_query_arg(
            'xtremecleans_success',
            '1',
            wp_get_referer() ?: home_url()
        );
        wp_safe_redirect($redirect_url);
        exit;
    }
    
    /**
     * Add rewrite rules for OAuth callback
     *
     * @since 1.0.0
     */
    public function add_rewrite_rules() {
        add_rewrite_rule(
            '^oauth/jobber/callback/?$',
            'index.php?xtremecleans_jobber_oauth=1',
            'top'
        );
    }
    
    /**
     * Add custom query vars
     *
     * @since 1.0.0
     * @param array $vars
     * @return array
     */
    public function add_query_vars($vars) {
        $vars[] = 'xtremecleans_jobber_oauth';
        return $vars;
    }
    
    /**
     * Handle OAuth callback redirect
     *
     * @since 1.0.0
     */
    public function handle_oauth_callback_redirect() {
        if (get_query_var('xtremecleans_jobber_oauth') === '1') {
            $this->process_jobber_oauth_callback();
            exit;
        }
    }
    
    /**
     * Process Jobber OAuth callback
     *
     * @since 1.0.0
     */
    private function process_jobber_oauth_callback() {
        $code = isset($_GET['code']) ? sanitize_text_field($_GET['code']) : '';
        $state = isset($_GET['state']) ? sanitize_text_field($_GET['state']) : '';
        $error = isset($_GET['error']) ? sanitize_text_field($_GET['error']) : '';
        $error_description = isset($_GET['error_description']) ? sanitize_text_field($_GET['error_description']) : '';
        
        if (!empty($error)) {
            $error_message = $error;
            if (!empty($error_description)) {
                $error_message .= ': ' . $error_description;
            }
            set_transient('xtremecleans_jobber_oauth_error', $error_message, HOUR_IN_SECONDS);
            wp_safe_redirect(admin_url('admin.php?page=xtremecleans-settings&jobber_oauth=error'));
            exit;
        }
        
        if (empty($code)) {
            set_transient('xtremecleans_jobber_oauth_error', 'Authorization code not received from Jobber.', HOUR_IN_SECONDS);
            wp_safe_redirect(admin_url('admin.php?page=xtremecleans-settings&jobber_oauth=error'));
            exit;
        }
        
        $token_response = $this->exchange_jobber_code_for_token($code);
        if (is_wp_error($token_response)) {
            $error_message = $token_response->get_error_message();
            if ($token_response->get_error_data()) {
                $error_data = $token_response->get_error_data();
                if (is_array($error_data) && isset($error_data['error_description'])) {
                    $error_message = $error_data['error_description'];
                } elseif (is_array($error_data) && isset($error_data['error'])) {
                    $error_message = $error_data['error'];
                }
            }
            set_transient('xtremecleans_jobber_oauth_error', $error_message, HOUR_IN_SECONDS);
            wp_safe_redirect(admin_url('admin.php?page=xtremecleans-settings&jobber_oauth=error'));
        } else {
            set_transient('xtremecleans_jobber_oauth_success', true, HOUR_IN_SECONDS);
            wp_safe_redirect(admin_url('admin.php?page=xtremecleans-settings&jobber_oauth=success'));
        }
        exit;
    }
    
    /**
     * Handle Jobber OAuth callback (legacy method for direct query param)
     *
     * @since 1.0.0
     */
    public function handle_jobber_oauth_callback() {
        // Check if this is the OAuth callback URL
        $request_uri = isset($_SERVER['REQUEST_URI']) ? $_SERVER['REQUEST_URI'] : '';
        if (strpos($request_uri, '/oauth/jobber/callback') !== false) {
            $this->process_jobber_oauth_callback();
            return;
        }
        
        // Legacy check for query param
        if (!isset($_GET['xtremecleans_jobber_oauth']) || $_GET['xtremecleans_jobber_oauth'] !== '1') {
            return;
        }
        
        $this->process_jobber_oauth_callback();
    }
    
    private function exchange_jobber_code_for_token($code) {
        $client_id = xtremecleans_get_option('jobber_client_id', '');
        $client_secret = xtremecleans_get_option('jobber_client_secret', '');
        $redirect_uri = xtremecleans_get_option('jobber_oauth_callback', home_url('/oauth/jobber/callback'));
        
        if (empty($client_id) || empty($client_secret)) {
            return new WP_Error('missing_credentials', __('Jobber OAuth credentials are missing.', 'xtremecleans'));
        }
        
        $token_endpoint = apply_filters('xtremecleans_jobber_token_endpoint', 'https://api.getjobber.com/api/oauth/token');
        
        $response = wp_remote_post($token_endpoint, array(
            'timeout' => 30,
            'headers' => array(
                'Accept' => 'application/json',
                'Content-Type' => 'application/x-www-form-urlencoded',
            ),
            'body' => http_build_query(array(
                'grant_type' => 'authorization_code',
                'code' => $code,
                'redirect_uri' => $redirect_uri,
                'client_id' => $client_id,
                'client_secret' => $client_secret,
            )),
        ));
        
        if (is_wp_error($response)) {
            xtremecleans_log('Jobber token exchange error: ' . $response->get_error_message(), 'error');
            return $response;
        }
        
        $status = wp_remote_retrieve_response_code($response);
        $body = wp_remote_retrieve_body($response);
        $data = json_decode($body, true);
        
        xtremecleans_log('Jobber token exchange response: Status ' . $status . ', Body: ' . $body, 'info');
        
        if ($status >= 200 && $status < 300 && isset($data['access_token'])) {
            update_option('xtremecleans_jobber_access_token', sanitize_text_field($data['access_token']));
            if (isset($data['refresh_token'])) {
                update_option('xtremecleans_jobber_refresh_token', sanitize_text_field($data['refresh_token']));
            }
            if (isset($data['expires_in'])) {
                update_option('xtremecleans_jobber_token_expires', time() + absint($data['expires_in']));
            } else {
                // If no expires_in, set to 0 (never expires or unknown)
                update_option('xtremecleans_jobber_token_expires', 0);
            }
            
            // Log token scopes if available (for debugging)
            if (isset($data['scope'])) {
                xtremecleans_log('Jobber token received with scopes: ' . $data['scope'], 'info');
                update_option('xtremecleans_jobber_token_scopes', sanitize_text_field($data['scope']));
            } else {
                // Jobber API sometimes doesn't return scope in token response
                // But we requested 'jobs contacts quotes' in authorization URL, so save that
                $requested_scopes = apply_filters('xtremecleans_jobber_oauth_scope', 'jobs contacts quotes');
                xtremecleans_log('WARNING: Jobber token response did not include scope information. Using requested scopes: ' . $requested_scopes, 'warning');
                update_option('xtremecleans_jobber_token_scopes', sanitize_text_field($requested_scopes));
            }
            
            xtremecleans_log('Jobber OAuth tokens saved successfully. Access token: ' . substr($data['access_token'], 0, 20) . '...', 'info');
            return $data;
        }
        
        $error_message = __('Failed to retrieve Jobber access token.', 'xtremecleans');
        if (isset($data['error_description'])) {
            $error_message = $data['error_description'];
        } elseif (isset($data['error'])) {
            $error_message = $data['error'];
        }
        
        return new WP_Error('token_error', $error_message, $data);
    }
    
    private function get_jobber_authorize_url() {
        $client_id = xtremecleans_get_option('jobber_client_id', '');
        $redirect_uri = xtremecleans_get_option('jobber_oauth_callback', home_url('/oauth/jobber/callback'));
        
        if (empty($client_id)) {
            return '';
        }
        
        // CRITICAL: Jobber requires 'jobs', 'clients', and 'quotes' scopes
        // 'jobs' - for creating jobs
        // 'clients' - for creating and reading clients (replaces 'contacts' in newer apps)
        // 'quotes' - for creating quotes (REQUIRED for quoteCreate mutation)
        // Note: Scopes must be space-separated, not comma-separated
        $required_scopes = 'jobs clients quotes';
        $scope = apply_filters('xtremecleans_jobber_oauth_scope', $required_scopes);
        
        // Ensure scopes are space-separated (not comma-separated)
        $scope = str_replace(',', ' ', $scope);
        $scope = preg_replace('/\s+/', ' ', trim($scope));
        
        // Log the authorization URL for debugging
        xtremecleans_log('Generating Jobber authorization URL with scopes: ' . $scope, 'info');
        
        $base = apply_filters('xtremecleans_jobber_authorize_endpoint', 'https://api.getjobber.com/api/oauth/authorize');
        $params = array(
            'response_type' => 'code',
            'client_id' => $client_id,
            'redirect_uri' => $redirect_uri,
            'state' => wp_create_nonce('xtremecleans_jobber_oauth_state'),
            'scope' => $scope,
        );
        
        $auth_url = esc_url(add_query_arg($params, $base));
        xtremecleans_log('Jobber authorization URL: ' . $auth_url, 'info');
        
        return $auth_url;
    }
    
    public function get_jobber_authorize_url_public() {
        return $this->get_jobber_authorize_url();
    }
    
    /**
     * Refresh Jobber access token using refresh token
     *
     * @since 1.0.0
     * @return array|WP_Error New token data or error
     */
    public function refresh_access_token() {
        $refresh_token = get_option('xtremecleans_jobber_refresh_token', '');
        $client_id = xtremecleans_get_option('jobber_client_id', '');
        $client_secret = xtremecleans_get_option('jobber_client_secret', '');
        
        if (empty($refresh_token) || empty($client_id) || empty($client_secret)) {
            return new WP_Error('missing_credentials', __('Refresh token or OAuth credentials are missing.', 'xtremecleans'));
        }
        
        $token_endpoint = apply_filters('xtremecleans_jobber_token_endpoint', 'https://api.getjobber.com/api/oauth/token');
        
        $response = wp_remote_post($token_endpoint, array(
            'timeout' => 30,
            'headers' => array(
                'Accept' => 'application/json',
                'Content-Type' => 'application/x-www-form-urlencoded',
            ),
            'body' => http_build_query(array(
                'grant_type' => 'refresh_token',
                'refresh_token' => $refresh_token,
                'client_id' => $client_id,
                'client_secret' => $client_secret,
            )),
        ));
        
        if (is_wp_error($response)) {
            xtremecleans_log('Jobber token refresh error: ' . $response->get_error_message(), 'error');
            return $response;
        }
        
        $status = wp_remote_retrieve_response_code($response);
        $body = wp_remote_retrieve_body($response);
        $data = json_decode($body, true);
        
        if ($status >= 200 && $status < 300 && isset($data['access_token'])) {
            update_option('xtremecleans_jobber_access_token', sanitize_text_field($data['access_token']));
            if (isset($data['refresh_token'])) {
                update_option('xtremecleans_jobber_refresh_token', sanitize_text_field($data['refresh_token']));
            }
            if (isset($data['expires_in'])) {
                update_option('xtremecleans_jobber_token_expires', time() + absint($data['expires_in']));
            }
            
            // Update scopes if provided in refresh response
            if (isset($data['scope'])) {
                update_option('xtremecleans_jobber_token_scopes', sanitize_text_field($data['scope']));
                xtremecleans_log('Jobber token refreshed with scopes: ' . $data['scope'], 'info');
            }
            
            xtremecleans_log('Jobber access token refreshed successfully', 'info');
            return $data;
        }
        
        $error_message = __('Failed to refresh Jobber access token.', 'xtremecleans');
        if (isset($data['error_description'])) {
            $error_message = $data['error_description'];
        } elseif (isset($data['error'])) {
            $error_message = $data['error'];
        }
        
        return new WP_Error('token_refresh_error', $error_message, $data);
    }
    
    /**
     * Process form data
     *
     * @since 1.0.0
     * @param string $name    Name
     * @param string $email   Email
     * @param string $message Message
     */
    private function process_form_data($name, $email, $message) {
        // Send email notification
        $to      = xtremecleans_get_option('form_email_recipient', get_option('admin_email'));
        $subject = sprintf(
            __('New Contact Form Submission from %s', 'xtremecleans'),
            get_bloginfo('name')
        );
        $body = sprintf(
            __("Name: %s\nEmail: %s\n\nMessage:\n%s", 'xtremecleans'),
            $name,
            $email,
            $message
        );
        
        wp_mail($to, $subject, $body);
        
        // Increment form submission counter
        xtremecleans_increment_form_counter();
        
        // Log submission
        xtremecleans_log(
            sprintf('Form submitted by: %s (%s)', $name, $email),
            'info'
        );
    }
}

