<?php
/**
 * Stripe Payment Handler
 *
 * @package XtremeCleans
 * @since 1.0.0
 */

// Exit if accessed directly
if (!defined('ABSPATH')) {
    exit;
}

/**
 * XtremeCleans_Stripe Class
 *
 * Handles Stripe payment processing for deposits
 *
 * @since 1.0.0
 */
class XtremeCleans_Stripe {
    
    /**
     * Check if Stripe is enabled
     *
     * @since 1.0.0
     * @return bool
     */
    public static function is_enabled() {
        return (bool) xtremecleans_get_option('stripe_enabled', '0');
    }
    
    /**
     * Check if test mode is enabled
     *
     * @since 1.0.0
     * @return bool
     */
    public static function is_test_mode() {
        return (bool) xtremecleans_get_option('stripe_test_mode', '1');
    }
    
    /**
     * Get Stripe API keys
     *
     * @since 1.0.0
     * @return array
     */
    public static function get_api_keys() {
        $test_mode = self::is_test_mode();
        
        if ($test_mode) {
            return array(
                'publishable_key' => xtremecleans_get_option('stripe_test_publishable_key', ''),
                'secret_key' => xtremecleans_get_option('stripe_test_secret_key', ''),
            );
        } else {
            return array(
                'publishable_key' => xtremecleans_get_option('stripe_publishable_key', ''),
                'secret_key' => xtremecleans_get_option('stripe_secret_key', ''),
            );
        }
    }
    
    /**
     * Check if Stripe is configured
     *
     * @since 1.0.0
     * @return bool
     */
    public static function is_configured() {
        if (!self::is_enabled()) {
            return false;
        }
        
        $keys = self::get_api_keys();
        return !empty($keys['publishable_key']) && !empty($keys['secret_key']);
    }
    
    /**
     * Create Payment Intent
     *
     * @since 1.0.0
     * @param float $amount Amount in dollars
     * @param string $currency Currency code (default: usd)
     * @param array $metadata Additional metadata
     * @return array|WP_Error
     */
    public static function create_payment_intent($amount, $currency = 'usd', $metadata = array()) {
        if (!self::is_configured()) {
            return new WP_Error('stripe_not_configured', __('Stripe is not configured.', 'xtremecleans'));
        }
        
        $keys = self::get_api_keys();
        $secret_key = $keys['secret_key'];
        
        // Convert amount to cents
        $amount_cents = round($amount * 100);
        
        $body = array(
            'amount' => $amount_cents,
            'currency' => strtolower($currency),
            'payment_method_types' => array('card'),
            'metadata' => $metadata,
        );
        
        $response = wp_remote_post('https://api.stripe.com/v1/payment_intents', array(
            'headers' => array(
                'Authorization' => 'Bearer ' . $secret_key,
                'Content-Type' => 'application/x-www-form-urlencoded',
            ),
            'body' => http_build_query($body),
            'timeout' => 30,
        ));
        
        if (is_wp_error($response)) {
            return $response;
        }
        
        $status_code = wp_remote_retrieve_response_code($response);
        $body = json_decode(wp_remote_retrieve_body($response), true);
        
        if ($status_code !== 200) {
            $error_message = isset($body['error']['message']) ? $body['error']['message'] : __('Payment intent creation failed.', 'xtremecleans');
            return new WP_Error('stripe_error', $error_message, $body);
        }
        
        return $body;
    }
    
    /**
     * Confirm Payment Intent
     *
     * @since 1.0.0
     * @param string $payment_intent_id Payment Intent ID
     * @param string $payment_method_id Payment Method ID
     * @return array|WP_Error
     */
    public static function confirm_payment_intent($payment_intent_id, $payment_method_id) {
        if (!self::is_configured()) {
            return new WP_Error('stripe_not_configured', __('Stripe is not configured.', 'xtremecleans'));
        }
        
        $keys = self::get_api_keys();
        $secret_key = $keys['secret_key'];
        
        $body = array(
            'payment_method' => $payment_method_id,
        );
        
        $response = wp_remote_post('https://api.stripe.com/v1/payment_intents/' . $payment_intent_id . '/confirm', array(
            'headers' => array(
                'Authorization' => 'Bearer ' . $secret_key,
                'Content-Type' => 'application/x-www-form-urlencoded',
            ),
            'body' => http_build_query($body),
            'timeout' => 30,
        ));
        
        if (is_wp_error($response)) {
            return $response;
        }
        
        $status_code = wp_remote_retrieve_response_code($response);
        $body = json_decode(wp_remote_retrieve_body($response), true);
        
        if ($status_code !== 200) {
            $error_message = isset($body['error']['message']) ? $body['error']['message'] : __('Payment confirmation failed.', 'xtremecleans');
            return new WP_Error('stripe_error', $error_message, $body);
        }
        
        return $body;
    }
    
    /**
     * Retrieve Payment Intent
     *
     * @since 1.0.0
     * @param string $payment_intent_id Payment Intent ID
     * @return array|WP_Error
     */
    public static function retrieve_payment_intent($payment_intent_id) {
        if (!self::is_configured()) {
            return new WP_Error('stripe_not_configured', __('Stripe is not configured.', 'xtremecleans'));
        }
        
        $keys = self::get_api_keys();
        $secret_key = $keys['secret_key'];
        
        $response = wp_remote_get('https://api.stripe.com/v1/payment_intents/' . $payment_intent_id, array(
            'headers' => array(
                'Authorization' => 'Bearer ' . $secret_key,
            ),
            'timeout' => 30,
        ));
        
        if (is_wp_error($response)) {
            return $response;
        }
        
        $status_code = wp_remote_retrieve_response_code($response);
        $body = json_decode(wp_remote_retrieve_body($response), true);
        
        if ($status_code !== 200) {
            $error_message = isset($body['error']['message']) ? $body['error']['message'] : __('Failed to retrieve payment intent.', 'xtremecleans');
            return new WP_Error('stripe_error', $error_message, $body);
        }
        
        return $body;
    }
    
    /**
     * Check if payment is successful
     *
     * @since 1.0.0
     * @param array $payment_intent Payment Intent data
     * @return bool
     */
    public static function is_payment_successful($payment_intent) {
        return isset($payment_intent['status']) && $payment_intent['status'] === 'succeeded';
    }
    
    /**
     * Get client secret for frontend
     *
     * @since 1.0.0
     * @param string $payment_intent_id Payment Intent ID
     * @return string|WP_Error
     */
    public static function get_client_secret($payment_intent_id) {
        $payment_intent = self::retrieve_payment_intent($payment_intent_id);
        
        if (is_wp_error($payment_intent)) {
            return $payment_intent;
        }
        
        return isset($payment_intent['client_secret']) ? $payment_intent['client_secret'] : '';
    }
}

