<?php
/**
 * Main XtremeCleans Design Template
 *
 * Complete design with header, hero, reviews, and sidebar
 *
 * @package XtremeCleans
 * @subpackage Frontend Templates
 * @since 1.0.0
 */

// Exit if accessed directly
if (!defined('ABSPATH')) {
    exit;
}
?>
<?php
// Get attributes passed from shortcode/widget
$hero_bg = isset($hero_bg) ? esc_url($hero_bg) : 'https://xtremecleans.com/wp-content/uploads/2024/12/Carpet-Cleaning-Services-2.jpg';
$zip_placeholder = isset($zip_placeholder) ? esc_attr($zip_placeholder) : 'ZIP Code';
$continue_btn = isset($continue_btn) ? esc_html($continue_btn) : 'CONTINUE';
?>
<div class="xtremecleans-wrapper">
<!-- Hero Section -->
<section class="xtremecleans-hero-section" style="background-image: url('<?php echo $hero_bg; ?>');">
    <div class="xtremecleans-hero-overlay"></div>
    <div class="xtremecleans-hero-container">
        <div class="xtremecleans-hero-promo-box">
            <div class="xtremecleans-hero-form-content">
                <div class="xtremecleans-popup-form">
                    <div class="xtremecleans-zip-field-wrapper">
                        <div class="xtremecleans-zip-field">
                            <input type="text" 
                                   class="xtremecleans-zip-input" 
                                   placeholder="<?php echo $zip_placeholder; ?>"
                                   id="xtremecleans-zip-input-hero"
                                   maxlength="5" />
                        </div>
                        <button class="xtremecleans-continue-btn" type="button">
                            <span><?php echo $continue_btn; ?></span>
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

</div>
<!-- End XtremeCleans Wrapper -->

<?php
// Get all attributes for passing to child templates
$template_atts = array(
    'lead_title' => isset($lead_title) ? $lead_title : 'Your ZIP code is outside our service area. Please provide your details below',
    'name_label' => isset($name_label) ? $name_label : 'Name',
    'name_placeholder' => isset($name_placeholder) ? $name_placeholder : 'Enter your full name',
    'email_label' => isset($email_label) ? $email_label : 'Email',
    'email_placeholder' => isset($email_placeholder) ? $email_placeholder : 'Enter your email address',
    'phone_label' => isset($phone_label) ? $phone_label : 'Phone',
    'phone_placeholder' => isset($phone_placeholder) ? $phone_placeholder : 'Enter your phone number',
    'submit_btn' => isset($submit_btn) ? $submit_btn : 'Submit',
    'service_title' => isset($service_title) ? $service_title : 'WHAT CAN WE CLEAN FOR YOU?',
    'service_instruction' => isset($service_instruction) ? $service_instruction : 'PLEASE SELECT ALL ITEMS AND SERVICES FROM THE DROP DOWNS BELOW FOR AN ACCURATE QUOTE AND ANY DISCOUNT THAT MAY APPLY.',
    'quote_title' => isset($quote_title) ? $quote_title : 'YOUR QUOTE',
    'clear_text' => isset($clear_text) ? $clear_text : 'Clear',
    'info_title' => isset($info_title) ? $info_title : 'YOUR INFORMATION',
    'first_name_label' => isset($first_name_label) ? $first_name_label : 'First Name',
    'first_name_placeholder' => isset($first_name_placeholder) ? $first_name_placeholder : 'First Name',
    'last_name_label' => isset($last_name_label) ? $last_name_label : 'Last Name',
    'last_name_placeholder' => isset($last_name_placeholder) ? $last_name_placeholder : 'Last Name',
    'email_address_label' => isset($email_address_label) ? $email_address_label : 'Email Address',
    'email_address_placeholder' => isset($email_address_placeholder) ? $email_address_placeholder : 'name@email.com',
    'phone_label_customer' => isset($phone_label_customer) ? $phone_label_customer : 'Phone',
    'phone_placeholder_customer' => isset($phone_placeholder_customer) ? $phone_placeholder_customer : '(555) 123-4567',
    'alt_phone_label' => isset($alt_phone_label) ? $alt_phone_label : 'Alternate Phone',
    'alt_phone_placeholder' => isset($alt_phone_placeholder) ? $alt_phone_placeholder : 'Optional',
);

// Load service selection template
xtremecleans_load_template('service-selection', $template_atts, 'frontend');

// Load lead collection form template
xtremecleans_load_template('lead-collection-form', $template_atts, 'frontend');
?>

