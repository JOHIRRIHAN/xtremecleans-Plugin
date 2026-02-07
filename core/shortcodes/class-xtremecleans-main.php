<?php
/**
 * Main XtremeCleans Design Shortcode
 *
 * Single shortcode that creates the complete XtremeCleans design
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
 * XtremeCleans_Main_Shortcode Class
 *
 * @since 1.0.0
 */
class XtremeCleans_Main_Shortcode {
    
    /**
     * Register main shortcode
     *
     * @since 1.0.0
     */
    public static function register() {
        add_shortcode('xtremecleans', array(__CLASS__, 'render'));
    }
    
    /**
     * Render main shortcode
     * Usage: [xtremecleans hero_bg="url" zip_placeholder="text" continue_btn="text" ...]
     *
     * @since 1.0.0
     * @param array $atts Shortcode attributes
     * @return string Shortcode output
     */
    public static function render($atts) {
        // Parse shortcode attributes
        $atts = shortcode_atts(array(
            // Hero section
            'hero_bg' => 'https://xtremecleans.com/wp-content/uploads/2024/12/Carpet-Cleaning-Services-2.jpg',
            'zip_placeholder' => 'ZIP Code',
            'continue_btn' => 'CONTINUE',
            
            // Lead form
            'lead_title' => 'Your ZIP code is outside our service area. Please provide your details below',
            'name_label' => 'Name',
            'name_placeholder' => 'Enter your full name',
            'email_label' => 'Email',
            'email_placeholder' => 'Enter your email address',
            'phone_label' => 'Phone',
            'phone_placeholder' => 'Enter your phone number',
            'submit_btn' => 'Submit',
            
            // Service selection
            'service_title' => 'WHAT CAN WE CLEAN FOR YOU?',
            'service_instruction' => 'PLEASE SELECT ALL ITEMS AND SERVICES FROM THE DROP DOWNS BELOW FOR AN ACCURATE QUOTE AND ANY DISCOUNT THAT MAY APPLY.',
            'quote_title' => 'YOUR QUOTE',
            'clear_text' => 'Clear',
            
            // Customer info
            'info_title' => 'YOUR INFORMATION',
            'first_name_label' => 'First Name',
            'first_name_placeholder' => 'First Name',
            'last_name_label' => 'Last Name',
            'last_name_placeholder' => 'Last Name',
            'email_address_label' => 'Email Address',
            'email_address_placeholder' => 'name@email.com',
            'phone_label_customer' => 'Phone',
            'phone_placeholder_customer' => '(555) 123-4567',
            'alt_phone_label' => 'Alternate Phone',
            'alt_phone_placeholder' => 'Optional',
        ), $atts, 'xtremecleans');

        // Store attributes globally so templates can access them
        global $xtremecleans_shortcode_atts;
        $xtremecleans_shortcode_atts = $atts;

        ob_start();
        xtremecleans_load_template('main-design', $atts, 'frontend');
        return ob_get_clean();
    }
}

