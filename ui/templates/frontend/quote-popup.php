<?php
if (!defined('ABSPATH')) {
    exit;
}
?>

<div class="xtremecleans-quote-popup" id="xtremecleans-quote-popup">
    <div class="xtremecleans-popup-overlay"></div>
    <div class="xtremecleans-popup-modal">
        <button class="xtremecleans-popup-back" aria-label="Go back">
<<<<<<< HEAD
            <span class="xtremecleans-back-arrow">←</span>
            <span class="xtremecleans-back-text">BACK</span>
        </button>
        
        <div class="xtremecleans-popup-content">
            <h2 class="xtremecleans-popup-title">CHANGE YOUR ZIP CODE</h2>
            <p class="xtremecleans-popup-description">Enter a new ZIP code to update your service area.</p>
=======
            <span class="xtremecleans-back-arrow"><?php echo isset($back_arrow_icon) ? $back_arrow_icon : '←'; ?></span>
            <span class="xtremecleans-back-text"><?php echo isset($back_text) ? esc_html($back_text) : 'BACK'; ?></span>
        </button>
        
        <div class="xtremecleans-popup-content">
            <h2 class="xtremecleans-popup-title"><?php echo isset($popup_title) ? esc_html($popup_title) : 'CHANGE YOUR ZIP CODE'; ?></h2>
            <p class="xtremecleans-popup-description"><?php echo isset($popup_description) ? esc_html($popup_description) : 'Enter a new ZIP code to update your service area.'; ?></p>
>>>>>>> 3378c4f (plugin last vertation updated in admin dashboard)
            
            <div class="xtremecleans-popup-form">
                <div class="xtremecleans-zip-field-wrapper">
                    <div class="xtremecleans-zip-field">
<<<<<<< HEAD
                        <input type="text" 
                               class="xtremecleans-zip-input" 
                               placeholder="ZIP Code"
=======
                           <input type="text" 
                               class="xtremecleans-zip-input" 
                               placeholder="<?php echo isset($zip_placeholder) ? esc_attr($zip_placeholder) : 'ZIP Code'; ?>"
>>>>>>> 3378c4f (plugin last vertation updated in admin dashboard)
                               id="xtremecleans-zip-input"
                               maxlength="5" />
                    </div>
                    <button class="xtremecleans-continue-btn" type="button">
<<<<<<< HEAD
                        <span>CONTINUE</span>
=======
                        <span><?php echo isset($continue_button_text) ? esc_html($continue_button_text) : 'CONTINUE'; ?></span>
>>>>>>> 3378c4f (plugin last vertation updated in admin dashboard)
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>

