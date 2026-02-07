<?php
/**
 * Hero Section Template
 *
 * @package XtremeCleans
 * @subpackage Frontend Templates
 * @since 1.0.0
 *
 * @var string $title            Hero title
 * @var string $price             Price amount
 * @var string $rooms             Number of rooms
 * @var string $reviews            Number of reviews
 * @var string $background_image   Background image URL
 * @var string $button_text        Button text
 * @var string $button_url         Button URL
 */

// Exit if accessed directly
if (!defined('ABSPATH')) {
    exit;
}

?>
<section class="xtremecleans-hero-section" style="background-image: url('https://xtremecleans.com/wp-content/uploads/2024/12/Carpet-Cleaning-Services-2.jpg');">
    <div class="xtremecleans-hero-overlay"></div>
    <div class="xtremecleans-hero-container">
        <div class="xtremecleans-hero-promo-box">
            <div class="xtremecleans-hero-form-content">
                <div class="xtremecleans-popup-form">
                    <div class="xtremecleans-zip-field-wrapper">
                        <div class="xtremecleans-zip-field">
                            <input type="text" 
                                   class="xtremecleans-zip-input" 
                                   placeholder="ZIP Code"
                                   id="xtremecleans-zip-input-hero"
                                   maxlength="5" />
                        </div>
                        <button class="xtremecleans-continue-btn" type="button"><span>CONTINUE</span></button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

