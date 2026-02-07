<?php
/**
 * Lead Collection Form Template
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

<div class="xtremecleans-lead-form-wrapper" id="xtremecleans-lead-form-wrapper" style="display: none;">
    <div class="xtremecleans-lead-form-overlay"></div>
    <div class="xtremecleans-lead-form-modal">
        <div class="xtremecleans-lead-form-content">
            <h2 class="xtremecleans-lead-form-title"><?php echo isset($lead_title) ? esc_html($lead_title) : 'Your ZIP code is outside our service area. Please provide your details below'; ?></h2>
            
            <form id="xtremecleans-lead-form" class="xtremecleans-lead-form">
                <div class="xtremecleans-lead-form-field">
                    <label for="xtremecleans-lead-name"><?php echo isset($name_label) ? esc_html($name_label) : 'Name'; ?> <span class="required">*</span></label>
                    <input type="text" 
                           id="xtremecleans-lead-name" 
                           name="name" 
                           class="xtremecleans-lead-input" 
                           placeholder="<?php echo isset($name_placeholder) ? esc_attr($name_placeholder) : 'Enter your full name'; ?>"
                           required />
                </div>
                
                <div class="xtremecleans-lead-form-field">
                    <label for="xtremecleans-lead-email"><?php echo isset($email_label) ? esc_html($email_label) : 'Email'; ?> <span class="required">*</span></label>
                    <input type="email" 
                           id="xtremecleans-lead-email" 
                           name="email" 
                           class="xtremecleans-lead-input" 
                           placeholder="<?php echo isset($email_placeholder) ? esc_attr($email_placeholder) : 'Enter your email address'; ?>"
                           required />
                </div>
                
                <div class="xtremecleans-lead-form-field">
                    <label for="xtremecleans-lead-zip-code-input">Zip code <span class="required">*</span></label>
                    <input type="text" 
                           id="xtremecleans-lead-zip-code-input" 
                           name="zip_code_input" 
                           class="xtremecleans-lead-input" 
                           placeholder="Enter your zip code"
                           maxlength="5"
                           pattern="[0-9]{5}"
                           required />
                    <div class="xtremecleans-zip-validation-message" id="xtremecleans-zip-validation-message" style="display: none; margin-top: 5px; font-size: 12px;"></div>
                </div>
                
                <div class="xtremecleans-lead-form-field">
                    <label for="xtremecleans-lead-phone"><?php echo isset($phone_label) ? esc_html($phone_label) : 'Phone Number'; ?> <span class="required">*</span></label>
                    <input type="tel" 
                           id="xtremecleans-lead-phone" 
                           name="phone" 
                           class="xtremecleans-lead-input" 
                           placeholder="<?php echo isset($phone_placeholder) ? esc_attr($phone_placeholder) : 'Enter your phone number'; ?>"
                           required />
                </div>
                
                <input type="hidden" id="xtremecleans-lead-zip-code" name="zip_code" value="" />
                <input type="hidden" id="xtremecleans-lead-zone-name" name="zone_name" value="" />
                
                <div class="xtremecleans-lead-form-actions">
                    <button type="submit" class="xtremecleans-lead-submit-btn">
                        <span><?php echo isset($submit_btn) ? esc_html($submit_btn) : 'Submit'; ?></span>
                    </button>
                    <button type="button" class="xtremecleans-lead-cancel-btn">
                        <span>Cancel</span>
                    </button>
                </div>
                
                <div class="xtremecleans-lead-form-message" id="xtremecleans-lead-form-message"></div>
            </form>
        </div>
    </div>
</div>

