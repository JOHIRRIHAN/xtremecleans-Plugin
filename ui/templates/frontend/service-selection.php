<?php
if (!defined('ABSPATH')) {
    exit;
}
?>

<div class="xtremecleans-service-selection" id="xtremecleans-service-selection">
    <div class="xtremecleans-service-overlay"></div>
    <div class="xtremecleans-service-modal">
        <div class="xtremecleans-service-topbar">
            <button class="xtremecleans-service-back-btn" type="button">
                <span class="xtremecleans-service-back-icon">←</span>
                <span class="xtremecleans-service-back-label">Back</span>
            </button>
        </div>

        <div class="xtremecleans-progress-bar">
            <div class="xtremecleans-progress-steps" data-step="1">
                <div class="xtremecleans-progress-step xtremecleans-step-active">
                    <span class="xtremecleans-step-number">1</span>
                    <span class="xtremecleans-step-label">Select Services</span>
                </div>
                <div class="xtremecleans-progress-step">
                    <span class="xtremecleans-step-number">2</span>
                    <span class="xtremecleans-step-label">Scheduling</span>
                </div>
                <div class="xtremecleans-progress-step">
                    <span class="xtremecleans-step-number">3</span>
                    <span class="xtremecleans-step-label">Your Information</span>
                </div>
                <div class="xtremecleans-progress-step">
                    <span class="xtremecleans-step-number">4</span>
                    <span class="xtremecleans-step-label">Review Your Order</span>
                </div>
            </div>
        </div>

        <div class="xtremecleans-step-content active" data-step="1">
        <div class="xtremecleans-service-container">
            <div class="xtremecleans-service-sidebar-left">
                <div class="xtremecleans-location-section">
                    <div class="xtremecleans-location-header">
                        <span class="xtremecleans-location-icon">📍</span>
                        <span class="xtremecleans-location-text">ZIP: <span class="xtremecleans-zip-display">14201</span></span>
                        <a href="#" class="xtremecleans-change-link">Change</a>
                    </div>
                    <div class="xtremecleans-zone-info" style="display: none;">
                        <div class="xtremecleans-zone-name">
                            <strong>Zone Name:</strong> <span class="xtremecleans-zone-name-display">-</span>
                        </div>
                        <div class="xtremecleans-zone-area">
                            <strong>Zone Area:</strong> <span class="xtremecleans-zone-area-display">-</span>
                        </div>
                        <div class="xtremecleans-service-charge">
                            <strong>Service Charge:</strong> <span class="xtremecleans-service-charge-display">-</span>
                            <div class="xtremecleans-service-charge-description">Service charges may vary based on your specific location.</div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="xtremecleans-service-main">
                <h2 class="xtremecleans-service-main-title"><?php echo isset($service_title) ? esc_html($service_title) : 'WHAT CAN WE CLEAN FOR YOU?'; ?></h2>
                <p class="xtremecleans-service-instruction"><?php echo isset($service_instruction) ? esc_html($service_instruction) : 'PLEASE SELECT ALL ITEMS AND SERVICES FROM THE DROP DOWNS BELOW FOR AN ACCURATE QUOTE AND ANY DISCOUNT THAT MAY APPLY.'; ?></p>

                <div class="xtremecleans-service-accordions" id="xtremecleans-service-accordions">
                    <!-- Services will be loaded dynamically from database -->
                </div>
            </div>

            <div class="xtremecleans-service-sidebar-right">
                <div class="xtremecleans-quote-section">
                    <div class="xtremecleans-quote-header">
                        <h3 class="xtremecleans-quote-title"><?php echo isset($quote_title) ? esc_html($quote_title) : 'YOUR QUOTE'; ?></h3>
                        <a href="#" class="xtremecleans-clear-link"><?php echo isset($clear_text) ? esc_html($clear_text) : 'Clear'; ?></a>
                    </div>
                    
                    <!-- Minimum Service Charge Notice -->
<<<<<<< HEAD
                    <div class="xtremecleans-minimum-service-charge-notice" style="display: none;">
=======
                    <!-- <div class="xtremecleans-minimum-service-charge-notice" style="display: none;">
>>>>>>> 3378c4f (plugin last vertation updated in admin dashboard)
                        <div class="xtremecleans-minimum-charge-content">
                            <span class="xtremecleans-minimum-charge-icon">ℹ️</span>
                            <div class="xtremecleans-minimum-charge-text">
                                <strong>Minimum service charge for your ZIP code <span class="xtremecleans-minimum-charge-zip">-</span> is <span class="xtremecleans-minimum-charge-amount">$0.00</span></strong>
                                <p class="xtremecleans-minimum-charge-description">Please select services that total at least this amount.</p>
                            </div>
                        </div>
<<<<<<< HEAD
                    </div>
=======
                    </div> -->
>>>>>>> 3378c4f (plugin last vertation updated in admin dashboard)
                    
                    <!-- Minimum Service Charge Warning (shown when services < minimum) -->
                    <div class="xtremecleans-minimum-charge-warning" style="display: none;">
                        <div class="xtremecleans-warning-content">
                            <span class="xtremecleans-warning-icon">⚠️</span>
                            <div class="xtremecleans-warning-text">
                                <strong>Minimum Service Charge Required</strong>
                                <p class="xtremecleans-warning-message">Your selected services total <span class="xtremecleans-current-services-total">$0.00</span>, which is less than the minimum service charge of <span class="xtremecleans-warning-minimum-amount">$199.00</span>.</p>
                                <p class="xtremecleans-warning-action">Please add more services to reach the minimum service charge of <span class="xtremecleans-warning-final-amount">$199.00</span> to proceed.</p>
                            </div>
                        </div>
                    </div>
                    
                    
                    <!-- Minimum Service Charge Popup Modal -->
                    <div id="xtremecleans-minimum-charge-popup" class="xtremecleans-popup-overlay" style="display: none;">
                        <div class="xtremecleans-popup-overlay-backdrop"></div>
                        <div class="xtremecleans-popup-modal xtremecleans-minimum-charge-popup">
                            <div class="xtremecleans-popup-header">
<<<<<<< HEAD
                                <h3 class="xtremecleans-popup-title">Minimum Service Charge Required</h3>
=======
                                <h3 class="xtremecleans-popup-title"><?php echo isset($minimum_charge_title) ? esc_html($minimum_charge_title) : 'Minimum Service Charge Required'; ?></h3>
>>>>>>> 3378c4f (plugin last vertation updated in admin dashboard)
                                <button type="button" class="xtremecleans-popup-close" aria-label="Close">&times;</button>
                            </div>
                            <div class="xtremecleans-popup-content">
                                <div class="xtremecleans-popup-icon-wrapper">
<<<<<<< HEAD
                                    <span class="xtremecleans-popup-icon">⚠️</span>
=======
                                    <span class="xtremecleans-popup-icon"><?php echo isset($minimum_charge_icon) ? $minimum_charge_icon : '⚠️'; ?></span>
>>>>>>> 3378c4f (plugin last vertation updated in admin dashboard)
                                </div>
                                <div class="xtremecleans-popup-message">
                                    <p class="xtremecleans-popup-text" id="xtremecleans-popup-message-text">
                                        <!-- Dynamic content will be inserted here -->
                                    </p>
                                </div>
                            </div>
                            <div class="xtremecleans-popup-actions">
                                <button type="button" class="xtremecleans-popup-btn xtremecleans-popup-btn-primary" id="xtremecleans-popup-ok-btn">
                                    OK
                                </button>
                            </div>
                        </div>
                    </div>
                    
                    <div class="xtremecleans-estimated-total">
<<<<<<< HEAD
                        <span class="xtremecleans-estimated-label">ESTIMATED TOTAL:</span>
                        <span class="xtremecleans-estimated-amount">$0.00</span>
=======
                        <span class="xtremecleans-estimated-label"><?php echo isset($estimated_total_label) ? esc_html($estimated_total_label) : 'ESTIMATED TOTAL:'; ?></span>
                        <span class="xtremecleans-estimated-amount"><?php echo isset($estimated_total_amount) ? esc_html($estimated_total_amount) : '$0.00'; ?></span>
>>>>>>> 3378c4f (plugin last vertation updated in admin dashboard)
                    </div>
                    
                    <!-- Job Duration Display -->
                    <div class="xtremecleans-job-duration-section">
<<<<<<< HEAD
                        <span class="xtremecleans-job-duration-label">ESTIMATED DURATION:</span>
                        <span class="xtremecleans-job-duration">0 minutes</span>
=======
                        <span class="xtremecleans-job-duration-label"><?php echo isset($estimated_duration_label) ? esc_html($estimated_duration_label) : 'ESTIMATED DURATION:'; ?></span>
                        <span class="xtremecleans-job-duration"><?php echo isset($estimated_duration) ? esc_html($estimated_duration) : '0 minutes'; ?></span>
>>>>>>> 3378c4f (plugin last vertation updated in admin dashboard)
                        <span class="xtremecleans-job-duration-minutes" style="display: none;">0</span>
                    </div>
                    
                    <div class="xtremecleans-quote-lines">
<<<<<<< HEAD
                        <div class="xtremecleans-quote-empty">Add services to build your quote.</div>
=======
                        <div class="xtremecleans-quote-empty"><?php echo isset($quote_empty_text) ? esc_html($quote_empty_text) : 'Add services to build your quote.'; ?></div>
>>>>>>> 3378c4f (plugin last vertation updated in admin dashboard)
                    </div>
                </div>

                <!-- Deposit Section -->
                <div class="xtremecleans-promo-section compact xtremecleans-deposit-section">
<<<<<<< HEAD
                    <h3 class="xtremecleans-promo-title xtremecleans-deposit-title">Deposit: $20</h3>
                    <p class="xtremecleans-promo-description">A $20 payment is required to confirm your appointment.</p>
=======
                    <h3 class="xtremecleans-promo-title xtremecleans-deposit-title"><?php echo isset($deposit_title) ? esc_html($deposit_title) : 'Deposit: $20'; ?></h3>
                    <p class="xtremecleans-promo-description"><?php echo isset($deposit_description) ? esc_html($deposit_description) : 'A $20 payment is required to confirm your appointment.'; ?></p>
>>>>>>> 3378c4f (plugin last vertation updated in admin dashboard)
                </div>

                <div class="xtremecleans-estimate-details">
                    <div class="xtremecleans-estimate-header">
<<<<<<< HEAD
                        <h3 class="xtremecleans-estimate-title">For a more accurate estimate, please check all that apply:</h3>
                        <span class="xtremecleans-estimate-arrow">▲</span>
=======
                        <h3 class="xtremecleans-estimate-title"><?php echo isset($estimate_title) ? esc_html($estimate_title) : 'For a more accurate estimate, please check all that apply:'; ?></h3>
                        <span class="xtremecleans-estimate-arrow"><?php echo isset($estimate_arrow_icon) ? $estimate_arrow_icon : '▲'; ?></span>
>>>>>>> 3378c4f (plugin last vertation updated in admin dashboard)
                    </div>
                    <div class="xtremecleans-estimate-checkboxes">
                        <label class="xtremecleans-checkbox-label">
                            <input type="checkbox" class="xtremecleans-checkbox" />
<<<<<<< HEAD
                            <span>I do not have parking nearby.</span>
                        </label>
                        <label class="xtremecleans-checkbox-label">
                            <input type="checkbox" class="xtremecleans-checkbox" />
                            <span>Area is on 3rd floor or higher.</span>
                        </label>
                        <label class="xtremecleans-checkbox-label">
                            <input type="checkbox" class="xtremecleans-checkbox" />
                            <span>I have guaranteed parking.</span>
                        </label>
                    </div>
                    <p class="xtremecleans-estimate-note">(If we need to park across or down the street, or clean above the 2nd floor, portable equipment may be required.)</p>
=======
                            <span><?php echo isset($estimate_checkbox1) ? esc_html($estimate_checkbox1) : 'I do not have parking nearby.'; ?></span>
                        </label>
                        <label class="xtremecleans-checkbox-label">
                            <input type="checkbox" class="xtremecleans-checkbox" />
                            <span><?php echo isset($estimate_checkbox2) ? esc_html($estimate_checkbox2) : 'Area is on 3rd floor or higher.'; ?></span>
                        </label>
                        <label class="xtremecleans-checkbox-label">
                            <input type="checkbox" class="xtremecleans-checkbox" />
                            <span><?php echo isset($estimate_checkbox3) ? esc_html($estimate_checkbox3) : 'I have guaranteed parking.'; ?></span>
                        </label>
                    </div>
                    <p class="xtremecleans-estimate-note"><?php echo isset($estimate_note) ? esc_html($estimate_note) : '(If we need to park across or down the street, or clean above the 2nd floor, portable equipment may be required.)'; ?></p>
>>>>>>> 3378c4f (plugin last vertation updated in admin dashboard)
                </div>
            </div>
        </div>
        </div>

        <div class="xtremecleans-step-content" data-step="2">
        <div class="xtremecleans-scheduling-section">
            <div class="xtremecleans-scheduling-inner">
                <div class="xtremecleans-scheduling-heading">
                    <span class="xtremecleans-heading-tag">Step 2</span>
                    <h2 class="xtremecleans-scheduling-title">APPOINTMENT DATE &amp; ARRIVAL WINDOW</h2>
                    <p class="xtremecleans-scheduling-subtitle">Please schedule your appointment below. This is a window of time for our crew to arrive, not start-to-finish time.</p>
                </div>

                <div class="xtremecleans-calendar-card" id="xtremecleans-dynamic-calendar">
                    <div class="xtremecleans-calendar-toolbar">
                        <button class="xtremecleans-calendar-nav xtremecleans-calendar-prev" type="button">
                            <span class="xtremecleans-calendar-arrow">‹</span>
                            <span>Prev Week</span>
                        </button>
                        <div class="xtremecleans-calendar-instruction">
                            <div class="xtremecleans-calendar-month-year"></div>
                            <span class="xtremecleans-calendar-week-range">Loading...</span>
                        </div>
                        <button class="xtremecleans-calendar-nav xtremecleans-calendar-next" type="button">
                            <span>Next Week</span>
                            <span class="xtremecleans-calendar-arrow">›</span>
                        </button>
                    </div>

                    <div class="xtremecleans-calendar-table">
                        <table id="xtremecleans-calendar-table">
                            <thead id="xtremecleans-calendar-header">
                                <tr>
                                    <th>Arrival Windows</th>
                                    <!-- Days will be populated by JavaScript -->
                                </tr>
                            </thead>
                            <tbody id="xtremecleans-calendar-body">
                                <!-- Time slots will be populated by JavaScript -->
                            </tbody>
                        </table>
                    </div>

                    <div class="xtremecleans-calendar-legend">
                        <div class="xtremecleans-legend-item">
                            <span class="legend-dot available"></span> Available Time
                        </div>
                        <div class="xtremecleans-legend-item">
                            <span class="legend-dot unavailable"></span> Not Available Time
                        </div>
                        <div class="xtremecleans-legend-item">
                            <span class="legend-dot booked"></span> Already Booked
                        </div>
                        <div class="xtremecleans-legend-item">
                            <span class="legend-dot selected"></span> Selected Time
                        </div>
                    </div>
                </div>
                
                <!-- Selection Summary Card -->
                <div class="xtremecleans-selection-summary-card" id="xtremecleans-selection-summary">
                    <div class="xtremecleans-summary-header">
                        <h3>Your Selection Summary</h3>
                    </div>
                    <div class="xtremecleans-summary-content">
                        <div class="xtremecleans-summary-section" id="xtremecleans-summary-services">
                            <h4>Selected Services:</h4>
                            <div class="xtremecleans-summary-services-list">
                                <p class="xtremecleans-summary-empty">No services selected yet</p>
                            </div>
                        </div>
                        <div class="xtremecleans-summary-section" id="xtremecleans-summary-appointment">
                            <h4>Appointment:</h4>
                            <div class="xtremecleans-summary-appointment-info">
                                <p class="xtremecleans-summary-empty">No appointment selected yet</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        </div>

        <div class="xtremecleans-step-content" data-step="3">
            <div class="xtremecleans-info-wrapper">
                <div class="xtremecleans-info-left">
                    <div class="xtremecleans-info-title-row">
                        <span class="xtremecleans-heading-tag">Step 3</span>
                        <h2 class="xtremecleans-info-title"><?php echo isset($info_title) ? esc_html($info_title) : 'YOUR INFORMATION'; ?></h2>
                        <div class="xtremecleans-info-underline"></div>
                    </div>

                    <div class="xtremecleans-guest-form">
                        <div class="xtremecleans-info-row xtremecleans-row-2">
                            <label>
                                <span><?php echo isset($first_name_label) ? esc_html($first_name_label) : 'First Name'; ?>*</span>
                                <input type="text" id="xtremecleans-info-first-name" placeholder="<?php echo isset($first_name_placeholder) ? esc_attr($first_name_placeholder) : 'First Name'; ?>" />
                            </label>
                            <label>
                                <span><?php echo isset($last_name_label) ? esc_html($last_name_label) : 'Last Name'; ?>*</span>
                                <input type="text" id="xtremecleans-info-last-name" placeholder="<?php echo isset($last_name_placeholder) ? esc_attr($last_name_placeholder) : 'Last Name'; ?>" />
                            </label>
                        </div>

                        <div class="xtremecleans-info-row xtremecleans-row-3">
                            <label>
                                <span><?php echo isset($email_address_label) ? esc_html($email_address_label) : 'Email Address'; ?>*</span>
                                <input type="email" id="xtremecleans-info-email" placeholder="<?php echo isset($email_address_placeholder) ? esc_attr($email_address_placeholder) : 'name@email.com'; ?>" />
                            </label>
                            <label>
                                <span><?php echo isset($phone_label_customer) ? esc_html($phone_label_customer) : 'Phone'; ?>*</span>
                                <input type="text" id="xtremecleans-info-phone" placeholder="<?php echo isset($phone_placeholder_customer) ? esc_attr($phone_placeholder_customer) : '(555) 123-4567'; ?>" />
                            </label>
                            <label>
                                <span><?php echo isset($alt_phone_label) ? esc_html($alt_phone_label) : 'Alternate Phone'; ?></span>
                                <input type="text" id="xtremecleans-info-alt-phone" placeholder="<?php echo isset($alt_phone_placeholder) ? esc_attr($alt_phone_placeholder) : 'Optional'; ?>" />
                            </label>
                        </div>

                        <div class="xtremecleans-info-row">
                            <label class="xtremecleans-full-field">
                                <span>Address 1*</span>
                                <input type="text" id="xtremecleans-info-address1" placeholder="123 Main Street" />
                            </label>
                        </div>

                        <div class="xtremecleans-info-row">
                            <label class="xtremecleans-full-field">
                                <span>Address 2</span>
                                <input type="text" id="xtremecleans-info-address2" placeholder="Unit, Floor, etc." />
                            </label>
                        </div>
<<<<<<< HEAD

                        

=======
                        
>>>>>>> 3378c4f (plugin last vertation updated in admin dashboard)
                        <div class="xtremecleans-info-row xtremecleans-row-3">
                            <label>
                                <span>Zip Code*</span>
                                <input type="text" id="xtremecleans-info-zip" value="14201" />
                            </label>
                            <label>
                                <span>City*</span>
                                <input type="text" id="xtremecleans-info-city" placeholder="City" />
                            </label>
                            <label>
                                <span>State*</span>
                                <input type="text" id="xtremecleans-info-state" placeholder="State" />
                            </label>
                        </div>

                        <label class="xtremecleans-notes-label xtremecleans-full-field">
                            <span>Special Instructions or Requests</span>
                            <textarea rows="4" id="xtremecleans-info-instructions" placeholder="Tell us the best way to access your home, pet instructions, parking notes, etc."></textarea>
                        </label>
<<<<<<< HEAD
                        <label class="xtremecleans-checkbox-inline">
                            <input type="checkbox" />
                            <span>Sign up to receive occasional emails with promotional offers for future cleanings.</span>
                        </label>
=======
>>>>>>> 3378c4f (plugin last vertation updated in admin dashboard)
                    </div>
                </div>

                <div class="xtremecleans-info-summary">
                    <div class="xtremecleans-quote-section">
                        <div class="xtremecleans-quote-header">
                            <h3 class="xtremecleans-quote-title">YOUR QUOTE</h3>
                            <a href="#" class="xtremecleans-clear-link">Edit</a>
                        </div>
                        <div class="xtremecleans-estimated-total">
                            <span class="xtremecleans-estimated-label">ESTIMATED TOTAL:</span>
                            <span class="xtremecleans-estimated-amount">$1,171.12</span>
                        </div>
                        <div class="xtremecleans-quote-lines">
                            <div class="xtremecleans-quote-line">
                                <span>Carpet · Clean (3)</span>
                                <span>$160.00</span>
                            </div>
                            <div class="xtremecleans-quote-line">
                                <span>Upholstery · Clean (6)</span>
                                <span>$672.00</span>
                            </div>
                            <div class="xtremecleans-quote-line">
                                <span>LVT · Clean (1)</span>
                                <span>$90.00</span>
                            </div>
                            <div class="xtremecleans-quote-line">
                                <span>Hardwood · Clean (1)</span>
                                <span>$115.00</span>
                            </div>
                            <div class="xtremecleans-quote-line">
                                <span>Leather · Clean (1)</span>
                                <span>$109.00</span>
                            </div>
                        </div>
                    </div>

                    <div class="xtremecleans-promo-section compact xtremecleans-deposit-section">
                        <h3 class="xtremecleans-promo-title xtremecleans-deposit-title">Deposit: $20</h3>
                        <p class="xtremecleans-promo-description">A $20 payment is required to confirm your appointment.</p>
                    </div>
                </div>
            </div>
        </div>

        <div class="xtremecleans-step-content" data-step="4">
            <div class="xtremecleans-review-section">
                <div class="xtremecleans-review-left">
                    <!-- Client Details Section -->
                    <div class="xtremecleans-review-block">
                        <div class="xtremecleans-service-columns">
                            <div class="xtremecleans-service-card">
                                <h4 class="xtremecleans-heading">Client Details</h4>
                                <ul>
                                    <li><span class="label">First Name:</span><span class="value xtremecleans-review-first-name">-</span></li>
                                    <li><span class="label">Last Name:</span><span class="value xtremecleans-review-last-name">-</span></li>
                                    <li><span class="label">Email:</span><span class="value xtremecleans-review-email">-</span></li>
                                    <li><span class="label">Phone:</span><span class="value xtremecleans-review-phone">-</span></li>
                                    <li><span class="label">Alternate Phone:</span><span class="value xtremecleans-review-alt-phone">-</span></li>
                                    <li><span class="label">Address 1:</span><span class="value xtremecleans-review-address1">-</span></li>
                                    <li><span class="label">Address 2:</span><span class="value xtremecleans-review-address2">-</span></li>
                                    <li><span class="label">Zip Code:</span><span class="value xtremecleans-review-zip">-</span></li>
                                    <li><span class="label">City:</span><span class="value xtremecleans-review-city">-</span></li>
                                    <li><span class="label">State:</span><span class="value xtremecleans-review-state">-</span></li>
                                    <li><span class="label">Special Instructions:</span><span class="value xtremecleans-review-instructions">-</span></li>
                                </ul>
                            </div>
                        </div>
                    </div>

                    <!-- Service Information Section -->
                    <div class="xtremecleans-review-block" style="margin-top: 20px;">
                        <h4 class="xtremecleans-heading">Service Information</h4>
                        <div class="xtremecleans-service-information-container">
                            <div class="xtremecleans-service-groups">
                                <!-- Service groups will be dynamically populated here -->
                            </div>
                            <div class="xtremecleans-service-summary">
                                <div class="xtremecleans-summary-row">
                                    <span class="xtremecleans-summary-label">Service Charge:</span>
                                    <span class="xtremecleans-summary-value xtremecleans-review-service-charge">-</span>
                                </div>
                                <div class="xtremecleans-summary-row">
                                    <span class="xtremecleans-summary-label">Subtotal:</span>
                                    <span class="xtremecleans-summary-value xtremecleans-review-subtotal-amount">$0.00</span>
                                </div>
                                <div class="xtremecleans-summary-row xtremecleans-summary-total">
                                    <span class="xtremecleans-summary-label">Total Services Amount:</span>
                                    <span class="xtremecleans-summary-value xtremecleans-review-total-services-amount">$0.00</span>
                                </div>
                            </div>
                        </div>
                    </div>

                </div>

                <div class="xtremecleans-review-right">
                    <div class="xtremecleans-review-total-card">
                        <h3 class="xtremecleans-heading">Your Quote</h3>
                        <div class="xtremecleans-review-total-row">
                            <span>Deposit</span>
                            <span>$20</span>
                        </div>
                        <button class="xtremecleans-review-submit xtremecleans-place-order" type="button">Place Order</button>
                        <div class="xtremecleans-order-feedback" role="alert" aria-live="polite" style="display:none;"></div>
                        <p class="xtremecleans-review-note">You’ll receive a confirmation email as soon as we finalize the appointment.</p>
                    </div>
                </div>
            </div>
        </div>

            <div class="xtremecleans-service-footer">
            <div class="xtremecleans-step-controls">
                <button class="xtremecleans-step-btn xtremecleans-step-prev" type="button" disabled>
                    ← Back
                </button>
                <button class="xtremecleans-step-btn xtremecleans-step-next" type="button">
                    Next: Scheduling
                </button>
            </div>
        </div>
    </div>
</div>

<!-- Stripe Payment Modal -->
<div id="xtremecleans-payment-modal" class="xtremecleans-popup-overlay" style="display: none;">
    <div class="xtremecleans-popup-overlay-backdrop"></div>
    <div class="xtremecleans-popup-modal xtremecleans-payment-modal">
        <div class="xtremecleans-popup-header">
            <h3 class="xtremecleans-popup-title">Complete Payment</h3>
            <button type="button" class="xtremecleans-popup-close" id="xtremecleans-payment-close" aria-label="Close">&times;</button>
        </div>
        <div class="xtremecleans-popup-content">
            <div class="xtremecleans-payment-info">
                <p class="xtremecleans-payment-amount">Deposit Amount: <strong>$<span id="xtremecleans-payment-amount-value">20.00</span></strong></p>
                <p class="xtremecleans-payment-description">A $20 deposit is required to confirm your appointment.</p>
            </div>
            <div id="xtremecleans-stripe-card-element" class="xtremecleans-stripe-card-element">
                <!-- Stripe Elements will create form elements here -->
            </div>
            <div id="xtremecleans-stripe-card-errors" class="xtremecleans-stripe-card-errors" role="alert"></div>
            <div class="xtremecleans-payment-loading" id="xtremecleans-payment-loading" style="display: none;">
                <p>Processing payment...</p>
            </div>
        </div>
        <div class="xtremecleans-popup-actions">
            <button type="button" class="xtremecleans-popup-btn xtremecleans-popup-btn-secondary" id="xtremecleans-payment-cancel">Cancel</button>
            <button type="button" class="xtremecleans-popup-btn xtremecleans-popup-btn-primary" id="xtremecleans-payment-submit">Pay $20.00</button>
        </div>
    </div>
</div>

<div class="xtremecleans-success-modal" id="xtremecleans-success-modal" aria-hidden="true">
    <div class="xtremecleans-success-overlay"></div>
    <div class="xtremecleans-success-content" role="dialog" aria-modal="true">
        <button class="xtremecleans-success-close" type="button" aria-label="Close">×</button>
        <div class="xtremecleans-success-icon">✓</div>
        <h3 class="xtremecleans-heading">Order Placed Successfully</h3>
        <p class="xtremecleans-copy">You will be contacted by a representative to confirm your booking appointment.</p>
        <button class="xtremecleans-review-submit xtremecleans-success-dismiss" type="button">Close</button>
    </div>
</div>

