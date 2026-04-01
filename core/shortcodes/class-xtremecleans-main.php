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
<<<<<<< HEAD
=======
        add_shortcode('xtremecleans_zip_button', array(__CLASS__, 'render_zip_button'));
>>>>>>> 3378c4f (plugin last vertation updated in admin dashboard)
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
<<<<<<< HEAD
=======

    /**
     * Render ZIP field + button only shortcode
     * Usage: [xtremecleans_zip_button zip_placeholder="ZIP Code" button_text="See Price"]
     *
     * @since 1.1.0
     * @param array $atts Shortcode attributes
     * @return string Shortcode output
     */
    public static function render_zip_button($atts) {
        $atts = shortcode_atts(array(
            'zip_placeholder' => 'ZIP Code',
            'button_text' => 'See Price',
            'input_id' => 'xtremecleans-zip-input-shortcode',
            'wrapper_class' => '',
            // Keep flow compatibility with existing templates.
            'lead_title' => 'Your ZIP code is outside our service area. Please provide your details below',
            'name_label' => 'Name',
            'name_placeholder' => 'Enter your full name',
            'email_label' => 'Email',
            'email_placeholder' => 'Enter your email address',
            'phone_label' => 'Phone',
            'phone_placeholder' => 'Enter your phone number',
            'submit_btn' => 'Submit',
            'service_title' => 'WHAT CAN WE CLEAN FOR YOU?',
            'service_instruction' => 'PLEASE SELECT ALL ITEMS AND SERVICES FROM THE DROP DOWNS BELOW FOR AN ACCURATE QUOTE AND ANY DISCOUNT THAT MAY APPLY.',
            'quote_title' => 'YOUR QUOTE',
            'clear_text' => 'Clear',
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
        ), $atts, 'xtremecleans_zip_button');

        $zip_placeholder = esc_attr($atts['zip_placeholder']);
        $button_text = esc_html($atts['button_text']);
        $input_id = esc_attr($atts['input_id']);
        $wrapper_class = trim('xtremecleans-zip-button-shortcode ' . sanitize_html_class($atts['wrapper_class']));

        ob_start();
        ?>
        <div class="<?php echo esc_attr($wrapper_class); ?>" style="width:100%;">
            <div class="xtremecleans-zip-field-wrapper" style="display:flex;align-items:center;gap:10px;flex-wrap:nowrap;width:100%;">
                <div class="xtremecleans-zip-field" style="flex:1 1 auto;min-width:0;">
                    <input
                        type="text"
                        class="xtremecleans-zip-input"
                        placeholder="<?php echo $zip_placeholder; ?>"
                        id="<?php echo $input_id; ?>"
                        maxlength="5"
                        style="width:100%;height:48px;padding:0 14px;box-sizing:border-box;"
                    />
                </div>
                <button class="xtremecleans-continue-btn" type="button" style="flex:0 0 auto;white-space:nowrap;height:48px;padding:0 18px;box-sizing:border-box;">
                    <span><?php echo $button_text; ?></span>
                </button>
            </div>
        </div>
        <?php
        $template_atts = array(
            'lead_title' => $atts['lead_title'],
            'name_label' => $atts['name_label'],
            'name_placeholder' => $atts['name_placeholder'],
            'email_label' => $atts['email_label'],
            'email_placeholder' => $atts['email_placeholder'],
            'phone_label' => $atts['phone_label'],
            'phone_placeholder' => $atts['phone_placeholder'],
            'submit_btn' => $atts['submit_btn'],
            'service_title' => $atts['service_title'],
            'service_instruction' => $atts['service_instruction'],
            'quote_title' => $atts['quote_title'],
            'clear_text' => $atts['clear_text'],
            'info_title' => $atts['info_title'],
            'first_name_label' => $atts['first_name_label'],
            'first_name_placeholder' => $atts['first_name_placeholder'],
            'last_name_label' => $atts['last_name_label'],
            'last_name_placeholder' => $atts['last_name_placeholder'],
            'email_address_label' => $atts['email_address_label'],
            'email_address_placeholder' => $atts['email_address_placeholder'],
            'phone_label_customer' => $atts['phone_label_customer'],
            'phone_placeholder_customer' => $atts['phone_placeholder_customer'],
            'alt_phone_label' => $atts['alt_phone_label'],
            'alt_phone_placeholder' => $atts['alt_phone_placeholder'],
        );

        xtremecleans_load_template('service-selection', $template_atts, 'frontend');
        xtremecleans_load_template('lead-collection-form', $template_atts, 'frontend');

        return ob_get_clean();
    }
>>>>>>> 3378c4f (plugin last vertation updated in admin dashboard)
}

