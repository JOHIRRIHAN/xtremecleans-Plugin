/**
 * XtremeCleans Frontend JavaScript - Modern Enhancements
 */

(function($) {
    'use strict';

    $(document).ready(function() {
        
        // Initialize plugin
        initXtremeCleans();
        
        // Add scroll effect to navigation
        initScrollEffects();
        
        /**
         * Initialize XtremeCleans features
         */
        function initXtremeCleans() {
            initFormValidation();
            initAjaxForms();
            initButtonAnimations();
        }
        
        /**
         * Form validation
         */
        function initFormValidation() {
            $('.xtremecleans-form').on('submit', function(e) {
                var form = $(this);
                var isValid = true;
                
                // Clear previous errors
                form.find('.error').removeClass('error');
                form.find('.error-message').remove();
                
                // Validate required fields
                form.find('input[required], textarea[required]').each(function() {
                    var field = $(this);
                    var value = field.val().trim();
                    
                    if (!value) {
                        isValid = false;
                        field.addClass('error');
                        showFieldError(field, 'This field is required.');
                    } else if (field.attr('type') === 'email' && !isValidEmail(value)) {
                        isValid = false;
                        field.addClass('error');
                        showFieldError(field, 'Please enter a valid email address.');
                    }
                });
                
                if (!isValid) {
                    e.preventDefault();
                    showFormError(form, 'Please correct the errors below.');
                }
            });
        }
        
        /**
         * Show field error
         */
        function showFieldError(field, message) {
            field.after('<span class="error-message xtremecleans-field-error">' + message + '</span>');
        }
        
        /**
         * Show form error
         */
        function showFormError(form, message) {
            var errorDiv = $('<div class="xtremecleans-error">' + message + '</div>');
            form.prepend(errorDiv);
            
            // Scroll to error
            $('html, body').animate({
                scrollTop: errorDiv.offset().top - 100
            }, 500);
            
            // Remove error after 5 seconds
            setTimeout(function() {
                errorDiv.fadeOut(function() {
                    $(this).remove();
                });
            }, 5000);
        }
        
        /**
         * Validate email
         */
        function isValidEmail(email) {
            var emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
            return emailRegex.test(email);
        }
        
        /**
         * AJAX form submission (if needed)
         */
        function initAjaxForms() {
            $('.xtremecleans-form-ajax').on('submit', function(e) {
                e.preventDefault();
                
                var form = $(this);
                var formData = form.serialize();
                var submitButton = form.find('button[type="submit"]');
                var originalText = submitButton.text();
                
                // Disable submit button
                submitButton.prop('disabled', true).text('Submitting...');
                
                $.ajax({
                    url: xtremecleansData.ajaxUrl,
                    type: 'POST',
                    data: formData + '&action=xtremecleans_submit_form&nonce=' + xtremecleansData.nonce,
                    success: function(response) {
                        if (response.success) {
                            form.html('<div class="xtremecleans-success">' + response.data.message + '</div>');
                        } else {
                            showFormError(form, response.data.message || 'An error occurred. Please try again.');
                            submitButton.prop('disabled', false).text(originalText);
                        }
                    },
                    error: function() {
                        showFormError(form, 'An error occurred. Please try again.');
                        submitButton.prop('disabled', false).text(originalText);
                    }
                });
            });
        }
        
        /**
         * Button animations
         */
        function initButtonAnimations() {
            $('.xtremecleans-button').on('mouseenter', function() {
                $(this).addClass('hover');
            }).on('mouseleave', function() {
                $(this).removeClass('hover');
            });
        }
        
        /**
         * Success message handler
         */
        if (getUrlParameter('xtremecleans_success') === '1') {
            var successMessage = $('<div class="xtremecleans-success">Thank you! Your message has been sent successfully.</div>');
            $('body').prepend(successMessage);
            
            // Scroll to top
            $('html, body').animate({
                scrollTop: 0
            }, 500);
            
            // Remove after 5 seconds
            setTimeout(function() {
                successMessage.fadeOut(function() {
                    $(this).remove();
                });
            }, 5000);
        }
        
        /**
         * Get URL parameter
         */
        function getUrlParameter(name) {
            name = name.replace(/[\[]/, '\\[').replace(/[\]]/, '\\]');
            var regex = new RegExp('[\\?&]' + name + '=([^&#]*)');
            var results = regex.exec(location.search);
            return results === null ? '' : decodeURIComponent(results[1].replace(/\+/g, ' '));
        }
        
        /**
         * Initialize scroll effects for modern navigation
         */
        function initScrollEffects() {
            var $nav = $('.xtremecleans-main-nav');
            var lastScroll = 0;
            
            $(window).on('scroll', function() {
                var currentScroll = $(this).scrollTop();
                
                // Add scrolled class for enhanced shadow
                if (currentScroll > 50) {
                    $nav.addClass('scrolled');
                } else {
                    $nav.removeClass('scrolled');
                }
                
                lastScroll = currentScroll;
            });
            
            // Smooth scroll for anchor links
            $('a[href^="#"]').on('click', function(e) {
                var target = $(this.getAttribute('href'));
                if (target.length) {
                    e.preventDefault();
                    $('html, body').stop().animate({
                        scrollTop: target.offset().top - 80
                    }, 800, 'swing');
                }
            });
        }
        
        /**
         * Initialize Quote Popup Modal
         */
        function initQuotePopup() {
            var $popup = $('#xtremecleans-quote-popup');
            var $serviceSelection = $('#xtremecleans-service-selection');
            var $overlay = $('.xtremecleans-popup-overlay');
            var $zipInput = $('#xtremecleans-zip-input');
            
            // Ensure popup is hidden on initialization
            $popup.removeClass('active');
            var serviceStateKey = 'xtremecleansServiceSelectionActive';
            var serviceZipKey = 'xtremecleansServiceZip';
            var serviceNameKey = 'xtremecleansServiceName';
            var selectedServiceName = '';
            
            function setSelectedServiceName(name) {
                selectedServiceName = name ? name.trim() : '';
                try {
                    sessionStorage.setItem(serviceNameKey, selectedServiceName);
                } catch (err) {}
            }
            
            function getSelectedServiceName() {
                return selectedServiceName;
            }
            
            function openServiceSelection(zipCode, serviceName) {
                // Reset progress bar to step 1
                $('.xtremecleans-progress-steps').attr('data-step', '1');
                if (zipCode) {
                    $('.xtremecleans-zip-display').text(zipCode);
                    try {
                        sessionStorage.setItem(serviceZipKey, zipCode);
                    } catch (err) {}
                    
                    // Fetch ZIP code data from database
                    fetchZipCodeData(zipCode);
                }
                
                if (serviceName) {
                    setSelectedServiceName(serviceName);
                } else {
                    try {
                        selectedServiceName = sessionStorage.getItem(serviceNameKey) || '';
                    } catch (err) {
                        selectedServiceName = '';
                    }
                }
                
                $popup.removeClass('active');
                $serviceSelection.addClass('active');
                $('body').css('overflow', 'hidden');
                // Hide hero section when service selection is active
                $('.xtremecleans-hero-section').hide();
                initMultiStepFlow(true);
                try {
                    sessionStorage.setItem(serviceStateKey, '1');
                } catch (err) {}
                
                // Reset quote to $0.00 when opening service selection
                $('.xtremecleans-estimated-amount').text('$0.00');
                $('.xtremecleans-quote-lines').html('<div class="xtremecleans-quote-empty">Add services to build your quote.</div>');
                
                // Reset duration display
                $('.xtremecleans-job-duration').text('0 minutes');
                $('.xtremecleans-job-duration-minutes').text('0');
                
                // Hide minimum service charge notice initially (will show after ZIP data is fetched)
                $('.xtremecleans-minimum-service-charge-notice').hide();
                $('.xtremecleans-minimum-charge-warning').hide();
                
                // Load unique service names from database
                loadServiceNames(getSelectedServiceName());
            }
            
            // Function to load unique service names from database
            function loadServiceNames(preferredService) {
                var ajaxUrl = typeof xtremecleansData !== 'undefined' ? xtremecleansData.ajaxUrl : '/wp-admin/admin-ajax.php';
                var nonce = typeof xtremecleansData !== 'undefined' ? xtremecleansData.nonce : '';
                
                // Get current ZIP code from the form
                var currentZipCode = $('#xtremecleans-zip-input').val() || 
                                    $('.xtremecleans-zip-display').text().trim() || 
                                    '';
                
                $.ajax({
                    url: ajaxUrl,
                    type: 'POST',
                    data: {
                        action: 'xtremecleans_get_service_names',
                        nonce: nonce,
                        service_name: preferredService || '',
                        zip_code: currentZipCode
                    },
                    success: function(response) {
                        if (response.success && response.data && response.data.service_names) {
                            renderServicePanels(
                                response.data.service_names,
                                preferredService || '',
                                !!response.data.filtered
                            );
                        } else {
                            $('#xtremecleans-service-accordions').html('<p style="padding: 20px; text-align: center;">No services available.</p>');
                        }
                    },
                    error: function() {
                        $('#xtremecleans-service-accordions').html('<p style="padding: 20px; text-align: center;">Error loading services. Please refresh the page.</p>');
                    }
                });
            }
            
            // Function to render service panels dynamically
            function renderServicePanels(serviceNames, preferredService, isFiltered) {
                var $accordions = $('#xtremecleans-service-accordions');
                $accordions.empty();
                
                if (serviceNames.length === 0) {
                    if (preferredService) {
                        var safePreferred = $('<div>').text(preferredService).html();
                        $accordions.html('<p style="padding: 20px; text-align: center;">No services are configured for the selected ZIP code\'s service (' + safePreferred + ').</p>');
                    } else {
                    $accordions.html('<p style="padding: 20px; text-align: center;">No services available.</p>');
                    }
                    return;
                }
                
                // Create a service panel for each unique service name
                serviceNames.forEach(function(serviceName, index) {
                    if (!serviceName || serviceName.trim() === '') {
                        return; // Skip empty service names
                    }
                    
                    // Escape service name for HTML
                    var escapedServiceName = $('<div>').text(serviceName).html();
                    
                    // Create a safe ID from service name
                    var panelId = 'xtremecleans-panel-' + serviceName.toLowerCase().replace(/\s+/g, '-').replace(/[^a-z0-9-]/g, '');
                    
                    // Create panel HTML with column headers and loading placeholder
                    var defaultItemHtml = '<div class="xtremecleans-card-columns">' +
                        '<div class="xtremecleans-card-column">' +
                        '<span class="xtremecleans-card-column-label">Clean</span>' +
<<<<<<< HEAD
                        '<span class="xtremecleans-card-column-info">i</span>' +
                        '</div>' +
                        '<div class="xtremecleans-card-column">' +
                        '<span class="xtremecleans-card-column-label">Protect</span>' +
                        '<span class="xtremecleans-card-column-info">i</span>' +
                        '</div>' +
                        '<div class="xtremecleans-card-column">' +
                        '<span class="xtremecleans-card-column-label">Deodorize</span>' +
                        '<span class="xtremecleans-card-column-info">i</span>' +
                        '</div>' +
                        '</div>' +
=======
                        '<a href="#" class="xtremecleans-card-column-info xtremecleans-info-tooltip" data-tooltip="Professional deep cleaning service for your selected areas." title="Clean Info">i</a>' +
                        '</div>' +
                        '<div class="xtremecleans-card-column">' +
                        '<span class="xtremecleans-card-column-label">Protect</span>' +
                        '<a href="#" class="xtremecleans-card-column-info xtremecleans-info-tooltip" data-tooltip="Carpet &amp; fabric protection treatment. Protection applies to ALL selected items — if you choose to protect, all your selected rooms/entries/hallways will be protected. It\'s all or nothing!" title="Protect Info">i</a>' +
                        '</div>' +
                        '<div class="xtremecleans-card-column">' +
                        '<span class="xtremecleans-card-column-label">Deodorize</span>' +
                        '<a href="#" class="xtremecleans-card-column-info xtremecleans-info-tooltip" data-tooltip="Odor elimination treatment to remove deep-set smells from carpets and upholstery." title="Deodorize Info">i</a>' +
                        '</div>' +
                        '</div>' +
                        '' +
>>>>>>> 3378c4f (plugin last vertation updated in admin dashboard)
                        '<div class="xtremecleans-service-cards">' +
                        '<div class="xtremecleans-service-items-loading" style="padding: 20px; text-align: center; color: #666;">Loading service items...</div>' +
                        '</div>';
                    
                    // Create panel HTML with default structure - service items will be loaded/updated when panel is expanded
                    var panelHtml = '<div class="xtremecleans-service-panel" id="' + panelId + '" data-service-name="' + escapedServiceName + '">' +
                        '<button class="xtremecleans-panel-header" type="button">' +
                        '<span class="xtremecleans-panel-title">' + escapedServiceName + '</span>' +
                        '<span class="xtremecleans-panel-icon">▼</span>' +
                        '</button>' +
                        '<div class="xtremecleans-panel-body" style="display: none;">' +
                        defaultItemHtml +
                        '</div>' +
                        '</div>';
                    
                    $accordions.append(panelHtml);
                });
                
                // Initialize accordion functionality for dynamically added panels
                initServiceAccordions();
            }
            
            // Function to load and render service items for a specific service
            function loadServiceItems(serviceName, $panelBody) {
                var ajaxUrl = typeof xtremecleansData !== 'undefined' ? xtremecleansData.ajaxUrl : '/wp-admin/admin-ajax.php';
                var nonce = typeof xtremecleansData !== 'undefined' ? xtremecleansData.nonce : '';
                
                // Don't show loading state - default structure is already visible
                // Silently load items from database in background and update if different
                
                $.ajax({
                    url: ajaxUrl,
                    type: 'POST',
                    data: {
                        action: 'xtremecleans_get_service_items',
                        service_name: serviceName,
                        nonce: nonce
                    },
                    success: function(response) {
                        if (response.success && response.data && response.data.service_items) {
                            // Always render items from database (even if empty array)
                                renderServiceItems(serviceName, response.data.service_items, $panelBody);
                        } else {
                            // No items found, show empty state
                            renderServiceItems(serviceName, [], $panelBody);
                            }
                    },
                    error: function() {
                        // On error, show empty state
                        renderServiceItems(serviceName, [], $panelBody);
                    }
                });
            }
            
            // Function to render service items for a service (replaces entire content)
            function renderServiceItems(serviceName, serviceItems, $panelBody) {
                var escapedServiceName = $('<div>').text(serviceName).html();
                
                // Find the existing service cards container and column headers
                var $serviceCards = $panelBody.find('.xtremecleans-service-cards');
                var $cardColumns = $panelBody.find('.xtremecleans-card-columns');
                
                // If service cards container doesn't exist, something went wrong - don't proceed
                if ($serviceCards.length === 0) {
                    return;
                }
                
                // Clear existing content (loading message or previous items)
                $serviceCards.empty();
                
                // If no items, show empty state
                if (!serviceItems || serviceItems.length === 0) {
                    $serviceCards.html('<div class="xtremecleans-service-items-empty" style="padding: 20px; text-align: center; color: #666;">No service items available. Please add items from the admin panel.</div>');
                    return;
                }
                
                // Get price field names from first item (all items in same service have same price names)
                var firstItem = serviceItems[0];
                var price1Name = firstItem.price1_name || 'Clean';
                var price2Name = firstItem.price2_name || 'Protect';
                var price3Name = firstItem.price3_name || 'Deodorize';
                
                var escapedPrice1Name = $('<div>').text(price1Name).html();
                var escapedPrice2Name = $('<div>').text(price2Name).html();
                var escapedPrice3Name = $('<div>').text(price3Name).html();
                
                // Update column headers with dynamic price names
                if ($cardColumns.length > 0) {
                    var $columns = $cardColumns.find('.xtremecleans-card-column');
                    if ($columns.length >= 3) {
                        $columns.eq(0).find('.xtremecleans-card-column-label').text(price1Name);
                        $columns.eq(1).find('.xtremecleans-card-column-label').text(price2Name);
                        $columns.eq(2).find('.xtremecleans-card-column-label').text(price3Name);
                    }
                }
                
                // Create HTML for service items from database
                var itemsHtml = '';
                
                // Create a service card for each service item from database
                serviceItems.forEach(function(item) {
                    var escapedItemName = $('<div>').text(item.item_name || 'Service Item').html();
                    var escapedItemDesc = $('<div>').text(item.item_description || '').html();
                    
                    // Get price values (use new format if available, fallback to old)
                    var price1Value = item.price1_value || item.clean_price || '0';
                    var price2Value = item.price2_value || item.protect_price || '0';
                    var price3Value = item.price3_value || item.deodorize_price || '0';
                    
<<<<<<< HEAD
=======
                    var itemDuration = item.service_item_duration || 0;
>>>>>>> 3378c4f (plugin last vertation updated in admin dashboard)
                    itemsHtml += '<div class="">' +
                        '<div class="xtremecleans-card-header">' +
                        '<div class="xtremecleans-card-name">' + escapedItemName + '</div>' +
                        (escapedItemDesc ? '<span class="xtremecleans-card-note">' + escapedItemDesc + '</span>' : '') +
                        '</div>' +
                        '<div class="xtremecleans-card-options">' +
                        '<div class="xtremecleans-card-option">' +
                        '<span class="xtremecleans-option-label">' + escapedPrice1Name + '</span>' +
<<<<<<< HEAD
                        '<div class="xtremecleans-qty-control" data-service="' + escapedServiceName + '" data-item="' + escapedItemName + '" data-type="' + escapedPrice1Name + '" data-price="' + price1Value + '">' +
=======
                        '<div class="xtremecleans-qty-control" data-service="' + escapedServiceName + '" data-item="' + escapedItemName + '" data-type="' + escapedPrice1Name + '" data-price="' + price1Value + '" data-duration="' + itemDuration + '">' +
>>>>>>> 3378c4f (plugin last vertation updated in admin dashboard)
                        '<button class="xtremecleans-qty-btn xtremecleans-qty-minus" type="button" aria-label="Remove ' + escapedPrice1Name + '">-</button>' +
                        '<span class="xtremecleans-qty-value">0</span>' +
                        '<button class="xtremecleans-qty-btn xtremecleans-qty-plus" type="button" aria-label="Add ' + escapedPrice1Name + '">+</button>' +
                        '</div>' +
                        '</div>' +
                        '<div class="xtremecleans-card-option">' +
                        '<span class="xtremecleans-option-label">' + escapedPrice2Name + '</span>' +
<<<<<<< HEAD
                        '<div class="xtremecleans-qty-control" data-service="' + escapedServiceName + '" data-item="' + escapedItemName + '" data-type="' + escapedPrice2Name + '" data-price="' + price2Value + '">' +
=======
                        '<div class="xtremecleans-qty-control" data-service="' + escapedServiceName + '" data-item="' + escapedItemName + '" data-type="' + escapedPrice2Name + '" data-price="' + price2Value + '" data-duration="' + itemDuration + '">' +
>>>>>>> 3378c4f (plugin last vertation updated in admin dashboard)
                        '<button class="xtremecleans-qty-btn xtremecleans-qty-minus" type="button" aria-label="Remove ' + escapedPrice2Name + '">-</button>' +
                        '<span class="xtremecleans-qty-value">0</span>' +
                        '<button class="xtremecleans-qty-btn xtremecleans-qty-plus" type="button" aria-label="Add ' + escapedPrice2Name + '">+</button>' +
                        '</div>' +
                        '</div>' +
                        '<div class="xtremecleans-card-option">' +
                        '<span class="xtremecleans-option-label">' + escapedPrice3Name + '</span>' +
<<<<<<< HEAD
                        '<div class="xtremecleans-qty-control" data-service="' + escapedServiceName + '" data-item="' + escapedItemName + '" data-type="' + escapedPrice3Name + '" data-price="' + price3Value + '">' +
=======
                        '<div class="xtremecleans-qty-control" data-service="' + escapedServiceName + '" data-item="' + escapedItemName + '" data-type="' + escapedPrice3Name + '" data-price="' + price3Value + '" data-duration="' + itemDuration + '">' +
>>>>>>> 3378c4f (plugin last vertation updated in admin dashboard)
                        '<button class="xtremecleans-qty-btn xtremecleans-qty-minus" type="button" aria-label="Remove ' + escapedPrice3Name + '">-</button>' +
                        '<span class="xtremecleans-qty-value">0</span>' +
                        '<button class="xtremecleans-qty-btn xtremecleans-qty-plus" type="button" aria-label="Add ' + escapedPrice3Name + '">+</button>' +
                        '</div>' +
                        '</div>' +
                        '</div>' +
                        '</div>';
                });
                
                // Replace content with items from database
                $serviceCards.html(itemsHtml);
                
<<<<<<< HEAD
=======
                // Add Pet Odor checkbox at the bottom of the service panel (if not already present)
                var $panelBody = $serviceCards.closest('.xtremecleans-panel-body');
                if ($panelBody.find('.xtremecleans-pet-odor-section').length === 0) {
                    var petOdorPrice = 50; // Pet Odor Treatment price
                    var petOdorHtml = '<div class="xtremecleans-pet-odor-section">' +
                        '<label class="xtremecleans-pet-odor-label">' +
                        '<input type="checkbox" class="xtremecleans-pet-odor-checkbox" ' +
                        'data-service="' + escapedServiceName + '" ' +
                        'data-item="Pet Odor Treatment" ' +
                        'data-price="' + petOdorPrice + '" />' +
                        '<span class="xtremecleans-pet-odor-checkmark"></span>' +
                        '<span class="xtremecleans-pet-odor-text">' +
                        '<span class="xtremecleans-pet-odor-title">🐾 Pet Odor Treatment</span>' +
                        '<span class="xtremecleans-pet-odor-desc">Add pet odor elimination treatment — $' + petOdorPrice.toFixed(2) + '</span>' +
                        '</span>' +
                        '</label>' +
                        '</div>';
                    $panelBody.append(petOdorHtml);
                }
                
>>>>>>> 3378c4f (plugin last vertation updated in admin dashboard)
                // Re-initialize quantity controls for dynamically added panels
                initQuantityControls();
            }
            
            // Function to initialize service accordion functionality
            function initServiceAccordions() {
                // Use event delegation for dynamically added panels
                $(document).off('click', '.xtremecleans-panel-header').on('click', '.xtremecleans-panel-header', function(e) {
                    e.preventDefault();
                    e.stopPropagation();
                    
                    var $panel = $(this).closest('.xtremecleans-service-panel');
                    var $body = $panel.find('.xtremecleans-panel-body');
                    var $icon = $(this).find('.xtremecleans-panel-icon');
                    var serviceName = $panel.data('service-name');
                    
                    // Check current state
                    var isCurrentlyActive = $panel.hasClass('active');
                    
                    // Toggle active state
                    $panel.toggleClass('active');
                    
                    // Toggle body visibility
                    if ($panel.hasClass('active')) {
                        // Panel is being expanded
                        // Check if service items are already loaded from database (check for service-cards div)
                        var $serviceCards = $body.find('.xtremecleans-service-cards');
                        
                        // Always try to load service items from database to replace default "Room(s)" if available
                        // But show default structure immediately (no loading state)
                        if ($serviceCards.length > 0) {
                            // Items exist (either default or loaded), try to update from database
                            loadServiceItems(serviceName, $body);
                        } else {
                            // No items structure, load from database
                            loadServiceItems(serviceName, $body);
                        }
                        
                        // Show the panel body - CSS .active class will handle display with !important
                        // Use show() instead of slideDown to work with !important CSS
                        $body.show();
                        
                        // Initialize quantity controls
                        initQuantityControls();
                        
                        $icon.text('▲');
                    } else {
                        // Panel is being collapsed
                        $body.hide();
                        $icon.text('▼');
                    }
                });
            }
            
            // Function to initialize quantity controls for dynamically added panels
            function initQuantityControls() {
                // Quantity controls are handled by event delegation in the main handler
                // This function is kept for compatibility but the handler is already set up
                // The event delegation at line ~876 handles all quantity buttons (static and dynamic)
            }
            
            // Function to fetch ZIP code data from database
            function fetchZipCodeData(zipCode) {
                // Get AJAX URL and nonce
                var ajaxUrl = typeof xtremecleansData !== 'undefined' ? xtremecleansData.ajaxUrl : '/wp-admin/admin-ajax.php';
                // Use the nonce from localized data, or create one inline
                var nonce = typeof xtremecleansData !== 'undefined' ? xtremecleansData.nonce : '';
                
                $.ajax({
                    url: ajaxUrl,
                    type: 'POST',
                    data: {
                        action: 'xtremecleans_get_zip_data',
                        zip_code: zipCode,
                        nonce: nonce
                    },
                    success: function(response) {
                        if (response.success && response.data) {
                            // Display ZIP code data
                            $('.xtremecleans-zip-display').text(response.data.zip_code);
                            
                            // Display zone name and zone area
                            if (response.data.zone_name) {
                                // Capitalize first letter
                                var zoneName = response.data.zone_name;
                                var capitalizedZoneName = zoneName.charAt(0).toUpperCase() + zoneName.slice(1).toLowerCase();
                                $('.xtremecleans-zone-name-display').text(capitalizedZoneName);
                            } else {
                                $('.xtremecleans-zone-name-display').text('-');
                            }
                            if (response.data.zone_area) {
                                $('.xtremecleans-zone-area-display').text(response.data.zone_area);
                            } else {
                                $('.xtremecleans-zone-area-display').text('-');
                            }
                            
                            // Display service charge (always show dynamic value from database)
                            var serviceFee = 0;
                            if (response.data.service_fee !== undefined && response.data.service_fee !== null) {
                                serviceFee = parseFloat(response.data.service_fee) || 0;
                            }
                            
                            // Always display service charge value (even if $0.00)
                            var serviceFeeFormatted = serviceFee.toFixed(2);
                            $('.xtremecleans-service-charge-display').text('$' + serviceFeeFormatted);
                            
                            // Update minimum service charge notice (use Service Fee value as minimum)
                            var zipCodeDisplay = response.data.zip_code || zipCode || '-';
                            $('.xtremecleans-minimum-charge-zip').text(zipCodeDisplay);
                            
                            // Minimum service charge = Service Fee value (or $199 if service fee is 0)
                            var MINIMUM_SERVICE_CHARGE = serviceFee > 0 ? serviceFee : 199.00;
                            $('.xtremecleans-minimum-charge-amount').text('$' + MINIMUM_SERVICE_CHARGE.toFixed(2));
                            
                            // Show the minimum service charge notice
                            $('.xtremecleans-minimum-service-charge-notice').fadeIn(300);
                            
                            // Store service fee in data attribute for quote calculation
                            $('#xtremecleans-service-selection, .xtremecleans-service-selection').data('service-fee', serviceFee);
                            
                            // Update location card in Step 1
                            if (response.data.city && response.data.state) {
                                $('.xtremecleans-location-name-display').text(response.data.city + ', ' + response.data.state);
                            } else if (response.data.zone_area) {
                                $('.xtremecleans-location-name-display').text(response.data.zone_area);
                            } else {
                                $('.xtremecleans-location-name-display').text('-');
                            }
                            
                            // Format address for location card
                            var addressParts = [];
                            if (response.data.city) {
                                addressParts.push(response.data.city);
                            }
                            if (response.data.state) {
                                addressParts.push(response.data.state);
                            }
                            if (response.data.zip_code) {
                                addressParts.push(response.data.zip_code);
                            }
                            if (addressParts.length > 0) {
                                $('.xtremecleans-location-address-display').html(addressParts.join(', '));
                            } else {
                                $('.xtremecleans-location-address-display').text('-');
                            }
                            
                            if (response.data.service_name) {
                                setSelectedServiceName(response.data.service_name);
                            }
                            
                            // Show zone info section
                            $('.xtremecleans-zone-info').show();
                            
                            if ($serviceSelection && $serviceSelection.length) {
                                $serviceSelection.data('zone-info', response.data);
                            }
                            if (response.data.zip_code) {
                                $('#xtremecleans-info-zip').val(response.data.zip_code);
                            }
                            if (response.data.city) {
                                $('#xtremecleans-info-city').val(response.data.city);
                            }
                            if (response.data.state) {
                                $('#xtremecleans-info-state').val(response.data.state);
                            }
                            
                            // Update quote summary to include service charge
                            if (typeof window.updateQuoteSummary === 'function') {
                                window.updateQuoteSummary();
                            } else if (typeof updateQuoteSummary === 'function') {
                                updateQuoteSummary();
                            }
                            
                            // Update service information section
                            if (typeof updateServiceInformation === 'function') {
                                updateServiceInformation();
                            }
                        } else {
                            // Hide zone info if not found
                            $('.xtremecleans-zone-info').hide();
                        }
                    },
                    error: function() {
                        // Hide zone info on error
                        $('.xtremecleans-zone-info').hide();
                    }
                });
            }
            
            function clearServiceState() {
                try {
                    sessionStorage.removeItem(serviceStateKey);
                    sessionStorage.removeItem(serviceZipKey);
                    sessionStorage.removeItem(serviceNameKey);
                    sessionStorage.removeItem('xtremecleans_selected_slot');
                } catch (err) {}
                selectedServiceName = '';
                if (typeof updateSelectionSummary === 'function') {
                    updateSelectionSummary();
                }
            }
            
            // Popup handlers removed - Change ZIP now navigates to Step 1 instead of opening popup
            
            // Handle ZIP code submission (works for both popup and hero form)
            // Store original button text in data attribute to prevent duplication
            $('.xtremecleans-continue-btn').each(function() {
                var $btn = $(this);
                var $span = $btn.find('span');
                if (!$btn.data('original-text')) {
                    // Store the original text from the span
                    var originalText = $span.text().trim();
                    // If text is already duplicated, extract just "CONTINUE"
                    if (originalText.indexOf('CONTINUE') !== -1) {
                        originalText = 'CONTINUE';
                        $span.text(originalText);
                    }
                    $btn.data('original-text', originalText);
                }
            });
            
            // Use event delegation to prevent duplicate handlers
            $(document).off('click', '.xtremecleans-continue-btn').on('click', '.xtremecleans-continue-btn', function(e) {
                e.preventDefault();
                
                // Determine which input to use based on button context
                var $button = $(this);
                var zipCode = '';
<<<<<<< HEAD
                
                // Check if button is in hero form or popup form
                if ($button.closest('.xtremecleans-hero-form-content').length > 0) {
=======
                var $localZipInput = $button
                    .closest('.xtremecleans-zip-field-wrapper')
                    .find('.xtremecleans-zip-input')
                    .first();
                
                // Prefer ZIP input next to the clicked button (works for custom shortcode too).
                if ($localZipInput.length) {
                    zipCode = $localZipInput.val().trim();
                } else if ($button.closest('.xtremecleans-hero-form-content').length > 0) {
>>>>>>> 3378c4f (plugin last vertation updated in admin dashboard)
                    // Hero form - get ZIP from hero input
                    zipCode = $('#xtremecleans-zip-input-hero').val().trim();
                } else {
                    // Popup form - get ZIP from popup input
                    zipCode = $zipInput.val().trim();
                }
                
                // Validate ZIP code format
                if (!zipCode || zipCode.length !== 5 || !/^\d{5}$/.test(zipCode)) {
                    alert('Please enter a valid 5-digit ZIP code');
                    return;
                }
                
                // Validate ZIP code against database
                validateZipCode(zipCode);
            });
            
            // Function to validate ZIP Code
            function validateZipCode(zipCode) {
                var ajaxUrl = typeof xtremecleansData !== 'undefined' ? xtremecleansData.ajaxUrl : '/wp-admin/admin-ajax.php';
                var nonce = typeof xtremecleansData !== 'undefined' ? xtremecleansData.nonce : '';
                
                // Show loading state
                var $continueBtn = $('.xtremecleans-continue-btn');
                $continueBtn.prop('disabled', true);
                
                // Get original text from data attribute, or fallback to current text (cleaned)
                var originalText = $continueBtn.data('original-text');
                if (!originalText) {
                    // If not stored, get current text and clean it
                    var currentText = $continueBtn.find('span').text().trim();
                    // Extract just "CONTINUE" if duplicated
                    if (currentText.indexOf('CONTINUE') !== -1) {
                        originalText = 'CONTINUE';
                    } else {
                        originalText = currentText;
                    }
                    $continueBtn.data('original-text', originalText);
                }
                
                // Set loading text
                $continueBtn.find('span').text('Checking...');
                
                $.ajax({
                    url: ajaxUrl,
                    type: 'POST',
                    data: {
                        action: 'xtremecleans_validate_zip_zone',
                        zip_code: zipCode,
                        nonce: nonce
                    },
                    success: function(response) {
                        // Re-enable button and restore original text
                        var $span = $continueBtn.find('span');
                        $span.text(originalText);
                        $continueBtn.prop('disabled', false);
                        
                        if (response.success) {
                            var matchedService = response.data && response.data.service_name ? response.data.service_name : '';
                            if (matchedService) {
                                setSelectedServiceName(matchedService);
                            }
                            // ZIP code found - proceed to service selection
                            openServiceSelection(zipCode, matchedService);
                        } else {
                            // ZIP code not found - show lead form popup
                            showLeadForm(zipCode);
                        }
                    },
                    error: function(xhr, status, error) {
                        // Re-enable button and restore original text
                        var $span = $continueBtn.find('span');
                        $span.text(originalText);
                        $continueBtn.prop('disabled', false);
                        
                        // On error, show lead form as fallback
                        console.log('Validation error:', error);
                        showLeadForm(zipCode);
                    }
                });
            }
            
            // Function to show lead collection form
            function showLeadForm(zipCode) {
                // Set the ZIP code in hidden field and visible input
                $('#xtremecleans-lead-zip-code').val(zipCode);
                $('#xtremecleans-lead-zip-code-input').val(zipCode);
                $('#xtremecleans-lead-zone-name').val(''); // Empty since we're not using zone name
                
                // Reset form and clear any previous messages
                $('#xtremecleans-lead-form')[0].reset();
                // Re-set the zip code after reset
                $('#xtremecleans-lead-zip-code').val(zipCode);
                $('#xtremecleans-lead-zip-code-input').val(zipCode);
                $('#xtremecleans-lead-form-message').html('').removeClass('success error');
                
                // Show the lead form wrapper
                var $leadForm = $('#xtremecleans-lead-form-wrapper');
                if ($leadForm.length) {
                    $leadForm.addClass('show').fadeIn(300);
                    $('body').css('overflow', 'hidden');
                } else {
                    console.error('Lead form wrapper not found');
                    alert('Lead form not available. Please refresh the page.');
                }
            }
            
            // Function to hide lead collection form
            function hideLeadForm() {
                $('#xtremecleans-lead-form-wrapper').fadeOut(300, function() {
                    $(this).removeClass('show');
                });
                $('body').css('overflow', '');
                $('#xtremecleans-lead-form')[0].reset();
                $('#xtremecleans-lead-form-message').html('');
            }
            
            // Handle lead form submission
            $('#xtremecleans-lead-form').on('submit', function(e) {
                e.preventDefault();
                
                var form = $(this);
                var submitBtn = form.find('.xtremecleans-lead-submit-btn');
                var originalText = submitBtn.find('span').text();
                var messageDiv = $('#xtremecleans-lead-form-message');
                
                // Disable submit button
                submitBtn.prop('disabled', true).find('span').text('Submitting...');
                messageDiv.html('').removeClass('success error');
                
                var ajaxUrl = typeof xtremecleansData !== 'undefined' ? xtremecleansData.ajaxUrl : '/wp-admin/admin-ajax.php';
                var nonce = typeof xtremecleansData !== 'undefined' ? xtremecleansData.nonce : '';
                
                // Get zip code from visible input field, fallback to hidden field
                var zipCodeValue = $('#xtremecleans-lead-zip-code-input').val().trim() || $('#xtremecleans-lead-zip-code').val();
                
                $.ajax({
                    url: ajaxUrl,
                    type: 'POST',
                    data: {
                        action: 'xtremecleans_save_lead',
                        name: $('#xtremecleans-lead-name').val().trim(),
                        email: $('#xtremecleans-lead-email').val().trim(),
                        phone: $('#xtremecleans-lead-phone').val().trim(),
                        zip_code: zipCodeValue,
                        zone_name: $('#xtremecleans-lead-zone-name').val(),
                        nonce: nonce
                    },
                    success: function(response) {
                        if (response.success) {
                            messageDiv.html('<p class="success">' + response.data.message + '</p>').addClass('success');
                            form[0].reset();
                            // Hide form after 3 seconds
                            setTimeout(function() {
                                hideLeadForm();
                            }, 3000);
                        } else {
                            messageDiv.html('<p class="error">' + (response.data.message || 'Failed to submit. Please try again.') + '</p>').addClass('error');
                            submitBtn.prop('disabled', false).find('span').text(originalText);
                        }
                    },
                    error: function() {
                        messageDiv.html('<p class="error">An error occurred. Please try again.</p>').addClass('error');
                        submitBtn.prop('disabled', false).find('span').text(originalText);
                    }
                });
            });
            
            // Sync visible zip code input with hidden field
            $(document).on('input', '#xtremecleans-lead-zip-code-input', function() {
                var zipValue = $(this).val().trim();
                $('#xtremecleans-lead-zip-code').val(zipValue);
            });
            
            // Validate ZIP code when user finishes entering it (blur or Enter key)
            $(document).on('blur', '#xtremecleans-lead-zip-code-input', function() {
                var zipCode = $(this).val().trim();
                if (zipCode.length === 5 && /^\d{5}$/.test(zipCode)) {
                    validateZipCodeFromLeadForm(zipCode);
                }
            });
            
            // Also validate on Enter key in ZIP code field
            $(document).on('keypress', '#xtremecleans-lead-zip-code-input', function(e) {
                if (e.which === 13) {
                    e.preventDefault();
                    var zipCode = $(this).val().trim();
                    if (zipCode.length === 5 && /^\d{5}$/.test(zipCode)) {
                        validateZipCodeFromLeadForm(zipCode);
                    } else {
                        alert('Please enter a valid 5-digit ZIP code');
                    }
                }
            });
            
            // Function to validate ZIP code from lead form and proceed to next step
            function validateZipCodeFromLeadForm(zipCode) {
                var ajaxUrl = typeof xtremecleansData !== 'undefined' ? xtremecleansData.ajaxUrl : '/wp-admin/admin-ajax.php';
                var nonce = typeof xtremecleansData !== 'undefined' ? xtremecleansData.nonce : '';
                var $zipInput = $('#xtremecleans-lead-zip-code-input');
                var $messageDiv = $('#xtremecleans-zip-validation-message');
                
                // Show loading state
                $zipInput.prop('disabled', true);
                var originalPlaceholder = $zipInput.attr('placeholder');
                $zipInput.attr('placeholder', 'Checking...');
                $messageDiv.html('<span style="color: #2271b1;">Checking ZIP code...</span>').show();
                
                $.ajax({
                    url: ajaxUrl,
                    type: 'POST',
                    data: {
                        action: 'xtremecleans_validate_zip_zone',
                        zip_code: zipCode,
                        nonce: nonce
                    },
                    success: function(response) {
                        // Re-enable input
                        $zipInput.prop('disabled', false);
                        $zipInput.attr('placeholder', originalPlaceholder);
                        var $messageDiv = $('#xtremecleans-zip-validation-message');
                        
                        if (response.success) {
                            var matchedService = response.data && response.data.service_name ? response.data.service_name : '';
                            if (matchedService) {
                                setSelectedServiceName(matchedService);
                            }
                            // ZIP code found - hide lead form and proceed to service selection
                            $messageDiv.html('<span style="color: #00a32a;">✓ ZIP code found! Redirecting...</span>').show();
                            setTimeout(function() {
                                hideLeadForm();
                                openServiceSelection(zipCode, matchedService);
                            }, 500);
                        } else {
                            // ZIP code not found - show message but keep form open
                            $messageDiv.html('<span style="color: #d63638;">ZIP code not found. Please continue filling the form.</span>').show();
                        }
                    },
                    error: function() {
                        // Re-enable input
                        $zipInput.prop('disabled', false);
                        $zipInput.attr('placeholder', originalPlaceholder);
                        var $messageDiv = $('#xtremecleans-zip-validation-message');
                        $messageDiv.html('<span style="color: #d63638;">Error checking ZIP code. Please try again.</span>').show();
                    }
                });
            }
            
            // Handle cancel button
            $('.xtremecleans-lead-cancel-btn, .xtremecleans-lead-form-overlay').on('click', function() {
                hideLeadForm();
            });
            
            // Close lead form on ESC key
            $(document).on('keydown', function(e) {
                if (e.key === 'Escape' && $('#xtremecleans-lead-form-wrapper').is(':visible')) {
                    hideLeadForm();
                }
            });
            
            // Allow Enter key to submit ZIP code (works for both inputs)
            $zipInput.on('keypress', function(e) {
                if (e.which === 13) {
                    e.preventDefault();
                    $('.xtremecleans-continue-btn').click();
                }
            });
            
            // Also handle Enter key for hero form input
            $('#xtremecleans-zip-input-hero').on('keypress', function(e) {
                if (e.which === 13) {
                    e.preventDefault();
                    $('.xtremecleans-continue-btn').click();
                }
            });
<<<<<<< HEAD
=======

            // Support Enter key for any ZIP input used by shortcode variants.
            $(document).on('keypress', '.xtremecleans-zip-input', function(e) {
                if (e.which === 13) {
                    e.preventDefault();
                    $(this).closest('.xtremecleans-zip-field-wrapper').find('.xtremecleans-continue-btn').first().trigger('click');
                }
            });
>>>>>>> 3378c4f (plugin last vertation updated in admin dashboard)
            
            // Handle change ZIP link in service selection
            $(document).on('click', '.xtremecleans-change-link', function(e) {
                e.preventDefault();
                
                console.log('Change ZIP link clicked');
                
                // Clear all selected services
                $('.xtremecleans-qty-control').each(function() {
                    var $control = $(this);
                    var $qtyValue = $control.find('.xtremecleans-qty-value');
                    $qtyValue.text('0');
                });
                $('.xtremecleans-airduct-checkbox').prop('checked', false);
                
                // Clear ZIP input fields
                $('#xtremecleans-zip-input').val('');
                $('#xtremecleans-zip-input-hero').val('');
                
                // Clear ZIP display
                $('.xtremecleans-zip-display').text('');
                
                // Clear zone info
                $('.xtremecleans-zone-info').hide();
                $('.xtremecleans-zone-name-display').text('-');
                $('.xtremecleans-zone-area-display').text('-');
                $('.xtremecleans-service-charge-display').text('-');
                
                // Clear service fee data
                $('#xtremecleans-service-selection, .xtremecleans-service-selection').data('service-fee', 0);
                
                // Reset quote summary
                $('.xtremecleans-estimated-amount').text('$0.00');
                $('.xtremecleans-quote-lines').html('<div class="xtremecleans-quote-empty">Add services to build your quote.</div>');
                $('.xtremecleans-job-duration').text('0 minutes');
                $('.xtremecleans-job-duration-minutes').text('0');
                
                // Update quote summary
                if (typeof window.updateQuoteSummary === 'function') {
                    window.updateQuoteSummary();
                }
                
                // Use multi-step flow's setStep function if available
                var $wizard = $('#xtremecleans-service-selection, .xtremecleans-service-selection');
                if ($wizard.length) {
                    // Try to use the exposed setStep function
                    if (typeof window.xtremecleansSetStep === 'function') {
                        console.log('Using multi-step setStep function');
                        window.xtremecleansSetStep(1);
                    } else {
                        // Fallback: Manual step navigation
                        console.log('Using fallback step navigation');
                        $('.xtremecleans-progress-steps').attr('data-step', '1');
                        $wizard.find('.xtremecleans-step-content').removeClass('active').attr('aria-hidden', 'true');
                        $wizard.find('.xtremecleans-step-content[data-step="1"]').addClass('active').attr('aria-hidden', 'false');
                        $wizard.find('.xtremecleans-progress-step').removeClass('xtremecleans-step-active xtremecleans-step-complete');
                        $wizard.find('.xtremecleans-progress-step:first').addClass('xtremecleans-step-active');
                    }
                }
                
                // Scroll to top of service selection area
                if ($serviceSelection && $serviceSelection.length) {
                    $('html, body').animate({
                        scrollTop: $serviceSelection.offset().top - 100
                    }, 300);
                }
                
                // Show ZIP input field if it exists in step 1
                var $zipInput = $('#xtremecleans-zip-input');
                if ($zipInput.length) {
                    // Make sure the input is visible
                    $zipInput.closest('.xtremecleans-popup-form, .xtremecleans-zip-field-wrapper').show();
                    // Focus on ZIP input after a short delay
                    setTimeout(function() {
                        $zipInput.focus().select();
                    }, 400);
                } else {
                    // If ZIP input not found in step 1, try hero section input
                    var $zipInputHero = $('#xtremecleans-zip-input-hero');
                    if ($zipInputHero.length) {
                        setTimeout(function() {
                            $zipInputHero.focus().select();
                        }, 400);
                    }
                }
            });
            
            // Handle dedicated service back button
            $('.xtremecleans-service-back-btn').on('click', function(e) {
                e.preventDefault();
                closeServiceSelection();
                
                // Scroll to hero section with ZIP Code form
                var $heroSection = $('.xtremecleans-hero-section');
                if ($heroSection.length) {
                    $('html, body').animate({
                        scrollTop: $heroSection.offset().top - 100
                    }, 500);
                    
                    // Focus on ZIP code input after a short delay
                    setTimeout(function() {
                        $('#xtremecleans-zip-input-hero').focus();
                    }, 600);
                }
            });
            
            // Handle service selection close
            $('.xtremecleans-service-overlay').on('click', function() {
                closeServiceSelection();
            });
            
            // Handle ESC key for service selection
            $(document).on('keydown', function(e) {
                if (e.key === 'Escape' && $serviceSelection.hasClass('active')) {
                    closeServiceSelection();
                }
            });
            
            // closePopup function removed - no longer needed
            
            function closeServiceSelection() {
                $serviceSelection.removeClass('active');
                $('body').css('overflow', '');
                // Show hero section when service selection is closed
                $('.xtremecleans-hero-section').show();
                clearServiceState();
            }
            
            // Restore service selection state on reload
            try {
                if (sessionStorage.getItem(serviceStateKey) === '1') {
                    var savedZip = sessionStorage.getItem(serviceZipKey);
                    var savedServiceName = sessionStorage.getItem(serviceNameKey) || '';
                    if (savedZip) {
                        $('.xtremecleans-zip-display').text(savedZip);
                        $zipInput.val(savedZip);
                    }
                    if (savedServiceName) {
                        selectedServiceName = savedServiceName;
                    }
                    openServiceSelection(savedZip || '', savedServiceName);
                }
            } catch (err) {}
        }
        
        // Initialize quote popup
        initQuotePopup();

        /**
         * Initialize Service Panels and Quote Summary
         */
        function collectSelectedServicesData() {
            var serviceLines = [];
            var servicesByGroup = {};
            var totalServicesAmount = 0;

            function addEntry(serviceName, itemName, type, quantity, price) {
                var qty = parseInt(quantity, 10) || 0;
                if (qty <= 0) {
                    return;
                }
                var unitPrice = parseFloat(price) || 0;
                var amount = unitPrice * qty;
                totalServicesAmount += amount;

                serviceLines.push({
                    label: serviceName + ' - ' + itemName + ' (' + type + ')',
                    amount: amount,
                    service: serviceName,
                    item: itemName,
                    type: type,
                    quantity: qty,
                    unit_price: unitPrice
                });

                if (!servicesByGroup[serviceName]) {
                    servicesByGroup[serviceName] = [];
                }

                servicesByGroup[serviceName].push({
                    item: itemName,
                    type: type,
                    quantity: qty,
                    price: unitPrice,
                    amount: amount
                });
            }

            $('.xtremecleans-qty-control').each(function () {
                var $control = $(this);
                var qty = parseInt($control.find('.xtremecleans-qty-value').text(), 10);
                if (qty > 0) {
                    addEntry(
                        $control.data('service'),
                        $control.data('item'),
                        $control.data('type'),
                        qty,
                        $control.data('price')
                    );
                }
            });

            $('.xtremecleans-airduct-checkbox:checked').each(function () {
                var $box = $(this);
                addEntry(
                    $box.data('service'),
                    $box.data('item'),
                    'Service',
                    1,
                    $box.data('price')
                );
            });

<<<<<<< HEAD
=======
            // Collect Pet Odor Treatment checkboxes
            $('.xtremecleans-pet-odor-checkbox:checked').each(function () {
                var $box = $(this);
                addEntry(
                    $box.data('service'),
                    $box.data('item'),
                    'Treatment',
                    1,
                    $box.data('price')
                );
            });

>>>>>>> 3378c4f (plugin last vertation updated in admin dashboard)
            return {
                serviceLines: serviceLines,
                servicesByGroup: servicesByGroup,
                totalServicesAmount: totalServicesAmount
            };
        }
        
        /**
         * Calculate Job Duration based on selected services
         * Formula: D = 90 + (20 × additional_rooms) + (20 × staircases) + (5 × protection_areas)
         */
        function calculateJobDuration() {
            var selection = collectSelectedServicesData();
            var serviceLines = selection.serviceLines;
            
            // If no services selected, return 0
            if (!serviceLines || serviceLines.length === 0) {
                console.log('No services selected, duration = 0');
                return 0;
            }
            
<<<<<<< HEAD
            var rooms = 0;
            var staircases = 0;
            var protectionAreas = 0;
            
            console.log('Calculating duration for', serviceLines.length, 'service lines');
            
            // Parse service items to identify rooms, staircases, and protection
            serviceLines.forEach(function(line) {
                // Safely convert to string and handle null/undefined/numbers
                var itemName = (line.item != null ? String(line.item) : '').toLowerCase();
                var serviceName = (line.service != null ? String(line.service) : '').toLowerCase();
                var type = (line.type != null ? String(line.type) : '').toLowerCase();
                var quantity = parseInt(line.quantity, 10) || 0;
                
                // Check if this is a protection type - count as protection, not room
                var isProtection = type.indexOf('protect') !== -1 || 
                                   type.indexOf('protection') !== -1;
                
                // Check for protection areas (carpet protection, protect type, etc.)
                if (isProtection) {
                    protectionAreas += quantity;
                }
                
                // Check for rooms (room, bedroom, living room, etc.)
                // Only count as room if NOT a protection type
                // Handle "Room(s)" format by removing parentheses and checking
                var cleanItemName = itemName.replace(/[()]/g, '').trim();
                var isRoom = false;
                
                // Check if item name contains room-related keywords
                if (cleanItemName.indexOf('room') !== -1 || 
                    itemName.indexOf('room') !== -1 ||
                    itemName.indexOf('bedroom') !== -1 || 
                    itemName.indexOf('living room') !== -1 ||
                    itemName.indexOf('dining room') !== -1 ||
                    itemName.indexOf('family room') !== -1 ||
                    itemName.indexOf('office') !== -1 ||
                    itemName.indexOf('den') !== -1 ||
                    itemName.indexOf('bath') !== -1 ||
                    itemName.indexOf('laundry') !== -1) {
                    isRoom = true;
                }
                
                // Only count as room if NOT a protection type
                if (!isProtection && isRoom) {
                    rooms += quantity;
                    console.log('✓ Room detected:', itemName, 'type:', type, 'quantity:', quantity, '→ Total rooms:', rooms);
                }
                
                // Check for staircases (only if not protection)
                if (!isProtection && (
                    itemName.indexOf('stair') !== -1 || 
                    itemName.indexOf('staircase') !== -1 ||
                    itemName.indexOf('stairs') !== -1 ||
                    itemName.indexOf('step') !== -1)) {
                    staircases += quantity;
                }
            });
            
            // Debug logging (can be removed in production)
            console.log('Duration Calculation:', {
                rooms: rooms,
                staircases: staircases,
                protectionAreas: protectionAreas,
                serviceLines: serviceLines.length,
                serviceLinesData: serviceLines.map(function(l) { 
                    return { item: l.item, type: l.type, quantity: l.quantity }; 
                })
            });
            
            // Calculate duration using formula
            // First room: 90 min, additional rooms: 20 min each
            var additionalRooms = Math.max(0, rooms - 1);
            var duration = 0;
            
            if (rooms > 0) {
                // At least one room selected
                duration = 90 + (20 * additionalRooms) + (20 * staircases) + (5 * protectionAreas);
            } else if (staircases > 0 || protectionAreas > 0) {
                // No rooms but has staircases or protection - still calculate
                duration = (20 * staircases) + (5 * protectionAreas);
            }
            
            console.log('📊 Duration Calculation Result:', {
                rooms: rooms,
                additionalRooms: additionalRooms,
                staircases: staircases,
                protectionAreas: protectionAreas,
                duration: duration + ' minutes',
                formula: rooms > 0 ? '90 + (20 × ' + additionalRooms + ') + (20 × ' + staircases + ') + (5 × ' + protectionAreas + ')' : 'No rooms'
            });
            
            return duration;
=======
            // Sum up admin-set durations for selected items
            var totalDuration = 0;
            $('.xtremecleans-qty-control').each(function () {
                var $control = $(this);
                var qty = parseInt($control.find('.xtremecleans-qty-value').text(), 10);
                var itemDuration = parseInt($control.data('duration'), 10) || 0;
                if (qty > 0 && itemDuration > 0) {
                    totalDuration += itemDuration * qty;
                }
            });
            return totalDuration;
>>>>>>> 3378c4f (plugin last vertation updated in admin dashboard)
        }
        
        /**
         * Format duration in minutes to readable format (e.g., "2 hours 30 minutes")
         */
        function formatDuration(minutes) {
            if (minutes <= 0) {
                return '0 minutes';
            }
            
            var hours = Math.floor(minutes / 60);
            var mins = minutes % 60;
            
            var parts = [];
            if (hours > 0) {
                parts.push(hours + (hours === 1 ? ' hour' : ' hours'));
            }
            if (mins > 0) {
                parts.push(mins + (mins === 1 ? ' minute' : ' minutes'));
            }
            
            return parts.join(' ') || '0 minutes';
        }

        function collectOrderPayload() {
            var selection = collectSelectedServicesData();
            var depositText = $('.xtremecleans-deposit-title').text() || '';
            var depositAmount = parseFloat(depositText.replace(/[^0-9.]/g, '')) || 0;
            
<<<<<<< HEAD
            // Get service charge from ZIP code data
            var $wizard = $('#xtremecleans-service-selection, .xtremecleans-service-selection');
            var serviceFee = parseFloat($wizard.data('service-fee')) || 0;
            
            // Minimum service charge = Service Fee value (or $199 if service fee is 0)
            var MINIMUM_SERVICE_CHARGE = serviceFee > 0 ? serviceFee : 199.00;
            
            // Calculate total with service charge first
            var totalWithServiceCharge = selection.totalServicesAmount;
            if (selection.totalServicesAmount > 0 && serviceFee > 0) {
                totalWithServiceCharge += serviceFee;
            }
            
            // Check if TOTAL (services + service charge) is less than minimum service charge
            var isBelowMinimum = totalWithServiceCharge > 0 && totalWithServiceCharge < MINIMUM_SERVICE_CHARGE;
            
            // Calculate grand total: services + service charge
            var finalGrandTotal = selection.totalServicesAmount;
            
            // Add service charge if services are selected
            if (selection.totalServicesAmount > 0 && serviceFee > 0) {
                finalGrandTotal += serviceFee;
            }
            
            // Apply minimum service charge if total is below minimum
            if (isBelowMinimum) {
                // Use minimum service charge (without adding service fee again)
                finalGrandTotal = MINIMUM_SERVICE_CHARGE;
            }
=======
            // Get service charge from ZIP code data (this is the MINIMUM SERVICE CHARGE, not an add-on)
            var $wizard = $('#xtremecleans-service-selection, .xtremecleans-service-selection');
            var serviceFee = parseFloat($wizard.data('service-fee')) || 0;
            
            // Service Charge = Minimum Service Charge threshold (NOT added to total)
            var MINIMUM_SERVICE_CHARGE = serviceFee > 0 ? serviceFee : 199.00;
            
            // Grand total = services only (service charge is NOT added)
            var finalGrandTotal = selection.totalServicesAmount;
            
            // Check if services total is below the minimum
            var isBelowMinimum = finalGrandTotal > 0 && finalGrandTotal < MINIMUM_SERVICE_CHARGE;
>>>>>>> 3378c4f (plugin last vertation updated in admin dashboard)
            
            // Calculate job duration
            var durationMinutes = calculateJobDuration();
            var durationFormatted = formatDuration(durationMinutes);
            
            var totals = {
                services: selection.totalServicesAmount,
                service_charge: serviceFee,
                minimum_service_charge: MINIMUM_SERVICE_CHARGE,
                minimum_applied: isBelowMinimum,
                deposit: depositAmount,
                grand_total: finalGrandTotal,
                duration_minutes: durationMinutes,
                duration_formatted: durationFormatted
            };

            function getFieldValue(selector) {
                var value = $(selector).val();
                return value ? $.trim(value) : '';
            }

            var customer = {
                first_name: getFieldValue('#xtremecleans-info-first-name'),
                last_name: getFieldValue('#xtremecleans-info-last-name'),
                email: getFieldValue('#xtremecleans-info-email'),
                phone: getFieldValue('#xtremecleans-info-phone'),
                alt_phone: getFieldValue('#xtremecleans-info-alt-phone'),
                address1: getFieldValue('#xtremecleans-info-address1'),
                address2: getFieldValue('#xtremecleans-info-address2'),
                city: getFieldValue('#xtremecleans-info-city'),
                state: getFieldValue('#xtremecleans-info-state'),
                zip_code: getFieldValue('#xtremecleans-info-zip'),
                instructions: getFieldValue('#xtremecleans-info-instructions')
            };

            if (!customer.zip_code) {
                customer.zip_code = $('.xtremecleans-zip-display').text().trim();
            }

            var slotData = null;
            try {
                var storedSlot = sessionStorage.getItem('xtremecleans_selected_slot');
                if (storedSlot) {
                    slotData = JSON.parse(storedSlot);
                }
            } catch (err) {}

            var zoneInfo = $wizard.data('zone-info') || {};
            var zoneMeta = {
                zip_code: $('.xtremecleans-zip-display').text().trim(),
                zone_name: $('.xtremecleans-zone-name-display').text().trim(),
                zone_area: $('.xtremecleans-zone-area-display').text().trim(),
                service_charge: $('.xtremecleans-service-charge-display').text().trim()
            };

            return {
                customer: customer,
                services: selection.serviceLines,
                services_grouped: selection.servicesByGroup,
                totals: totals,
                appointment: slotData,
                zone: $.extend({}, zoneInfo, zoneMeta),
                meta: {
                    source_url: window.location.href,
                    submitted_at: new Date().toISOString()
                }
            };
        }

        function initServicePanels() {
            var $panels = $('.xtremecleans-service-panel');

            $panels.each(function () {
                var $panel = $(this);
                var $body = $panel.find('.xtremecleans-panel-body');
                if ($panel.hasClass('open')) {
                    $body.show();
                } else {
                    $body.hide();
                }
            });

            $('.xtremecleans-panel-header').on('click', function () {
                var $panel = $(this).closest('.xtremecleans-service-panel');
                var $body = $panel.find('.xtremecleans-panel-body');
                $panel.toggleClass('open');
                $body.slideToggle(200);
            });

            $('.xtremecleans-protect-toggle').on('click', function () {
                var $toggle = $(this);
                var $detail = $toggle.next('.xtremecleans-protect-detail');
                $toggle.toggleClass('open');
                $toggle.text($toggle.hasClass('open') ? 'Close' : 'Show Protect information');
                $detail.stop(true, true).slideToggle(200);
            });

<<<<<<< HEAD
=======
            // Info tooltip click handler for Clean/Protect/Deodorize "i" icons
            $(document).off('click', '.xtremecleans-info-tooltip').on('click', '.xtremecleans-info-tooltip', function(e) {
                e.preventDefault();
                e.stopPropagation();
                
                // Remove any existing tooltip popup
                $('.xtremecleans-tooltip-popup').remove();
                
                var tooltipText = $(this).data('tooltip') || '';
                if (!tooltipText) return;
                
                // Create tooltip popup
                var $popup = $('<div class="xtremecleans-tooltip-popup">' +
                    '<div class="xtremecleans-tooltip-content">' +
                    '<span class="xtremecleans-tooltip-close">&times;</span>' +
                    '<p>' + tooltipText + '</p>' +
                    '</div></div>');
                
                $('body').append($popup);
                
                // Position near the clicked icon
                var offset = $(this).offset();
                var popupWidth = 280;
                var left = offset.left - (popupWidth / 2) + 10;
                var top = offset.top + 30;
                
                // Keep within viewport
                if (left < 10) left = 10;
                if (left + popupWidth > $(window).width() - 10) {
                    left = $(window).width() - popupWidth - 10;
                }
                
                $popup.find('.xtremecleans-tooltip-content').css({
                    left: left + 'px',
                    top: top + 'px'
                });
                
                $popup.fadeIn(200);
                
                // Close on click outside or X button
                $popup.on('click', function(ev) {
                    if ($(ev.target).hasClass('xtremecleans-tooltip-popup') || $(ev.target).hasClass('xtremecleans-tooltip-close')) {
                        $popup.fadeOut(150, function() { $popup.remove(); });
                    }
                });
                
                // Auto-close after 6 seconds
                setTimeout(function() {
                    $popup.fadeOut(150, function() { $popup.remove(); });
                }, 6000);
            });

>>>>>>> 3378c4f (plugin last vertation updated in admin dashboard)
            // Use event delegation for quantity buttons (works with dynamically added elements)
            $(document).off('click', '.xtremecleans-qty-btn').on('click', '.xtremecleans-qty-btn', function (e) {
                e.preventDefault();
                e.stopPropagation();
                
                var $control = $(this).closest('.xtremecleans-qty-control');
                var $value = $control.find('.xtremecleans-qty-value');
                var qty = parseInt($value.text(), 10) || 0;
<<<<<<< HEAD

                if ($(this).hasClass('xtremecleans-qty-plus')) {
                    // Increase quantity
                    qty += 1;
                } else if ($(this).hasClass('xtremecleans-qty-minus')) {
                    // Decrease quantity (but not below 0)
                    if (qty > 0) {
                        qty -= 1;
=======
                var isPlus = $(this).hasClass('xtremecleans-qty-plus');
                
                // Get the type of this control and all sibling controls for the same item
                var currentType = ($control.data('type') || '').toString().toLowerCase();
                var $allControls = $control.closest('.xtremecleans-card-options').find('.xtremecleans-qty-control');
                var $cleanControl = $allControls.eq(0);  // First column = Clean (price1)
                var $protectControl = $allControls.eq(1); // Second column = Protect (price2)
                
                // Check if this is a Protect-type control
                var isProtectType = currentType.indexOf('protect') !== -1;
                // Check if this is the Clean (first column) control
                var isCleanType = $control.is($cleanControl);
                
                if (isProtectType) {
                    // --- PROTECT COLUMN: All-or-nothing rule ---
                    var cleanQty = parseInt($cleanControl.find('.xtremecleans-qty-value').text(), 10) || 0;
                    
                    if (isPlus) {
                        if (cleanQty === 0) {
                            // Can't add protection without selecting clean first
                            alert('Please select the number of items to clean first, then add protection.');
                            return;
                        }
                        // Set protect qty to match clean qty (all or nothing)
                        qty = cleanQty;
                    } else {
                        // Clicking minus on protect = remove all protection (set to 0)
                        qty = 0;
                    }
                } else if (isCleanType) {
                    // --- CLEAN COLUMN: Normal +/- behavior ---
                    if (isPlus) {
                        qty += 1;
                    } else {
                        if (qty > 0) {
                            qty -= 1;
                        }
                    }
                    
                    // If protect is active, auto-sync protect qty to new clean qty
                    var protectQty = parseInt($protectControl.find('.xtremecleans-qty-value').text(), 10) || 0;
                    if (protectQty > 0) {
                        if (qty === 0) {
                            // Clean dropped to 0, remove protection too
                            $protectControl.find('.xtremecleans-qty-value').text('0');
                        } else {
                            // Auto-update protect to match new clean qty
                            $protectControl.find('.xtremecleans-qty-value').text(qty);
                        }
                    }
                } else {
                    // --- OTHER COLUMNS (Deodorize, etc.): Normal +/- behavior ---
                    if (isPlus) {
                        qty += 1;
                    } else {
                        if (qty > 0) {
                            qty -= 1;
                        }
>>>>>>> 3378c4f (plugin last vertation updated in admin dashboard)
                    }
                }

                $value.text(qty);
                
                // Update quote summary if function exists
                if (typeof updateQuoteSummary === 'function') {
                    updateQuoteSummary();
                }
                
                // Update service information
                if (typeof updateServiceInformation === 'function') {
                    updateServiceInformation();
                }
                
                // Update selection summary
                if (typeof updateSelectionSummary === 'function') {
                    updateSelectionSummary();
                }
            });

            $('.xtremecleans-airduct-checkbox').on('change', function () {
                updateQuoteSummary();
                if (typeof updateServiceInformation === 'function') {
                    updateServiceInformation();
                }
            });

<<<<<<< HEAD
=======
            // Pet Odor checkbox handler (event delegation for dynamic checkboxes)
            $(document).off('change', '.xtremecleans-pet-odor-checkbox').on('change', '.xtremecleans-pet-odor-checkbox', function () {
                updateQuoteSummary();
                if (typeof updateServiceInformation === 'function') {
                    updateServiceInformation();
                }
                if (typeof updateSelectionSummary === 'function') {
                    updateSelectionSummary();
                }
            });

>>>>>>> 3378c4f (plugin last vertation updated in admin dashboard)
            $('.xtremecleans-clear-link').on('click', function (e) {
                e.preventDefault();
                $('.xtremecleans-qty-value').text('0');
                $('.xtremecleans-airduct-checkbox').prop('checked', false);
<<<<<<< HEAD
=======
                $('.xtremecleans-pet-odor-checkbox').prop('checked', false);
>>>>>>> 3378c4f (plugin last vertation updated in admin dashboard)
                // Reset duration display
                $('.xtremecleans-job-duration').text('0 minutes');
                $('.xtremecleans-job-duration-minutes').text('0');
                updateQuoteSummary();
                if (typeof updateServiceInformation === 'function') {
                    updateServiceInformation();
                }
                // Update selection summary
                if (typeof updateSelectionSummary === 'function') {
                    updateSelectionSummary();
                }
            });

            function updateQuoteSummary() {
                var selection = collectSelectedServicesData();
                var totalServicesAmount = selection.totalServicesAmount;
                var displayLines = selection.serviceLines.map(function(line) {
                    return {
                        label: line.label,
                        amount: line.amount
                    };
                });

<<<<<<< HEAD
                // Get service charge from ZIP code data
                var $wizard = $('#xtremecleans-service-selection, .xtremecleans-service-selection');
                var serviceFee = parseFloat($wizard.data('service-fee')) || 0;
                
                // Minimum service charge = Service Fee value (or $199 if service fee is 0)
                var MINIMUM_SERVICE_CHARGE = serviceFee > 0 ? serviceFee : 199.00;
                
                // Calculate total
                var total = totalServicesAmount;
                
                // Add service charge if services are selected
                if (totalServicesAmount > 0 && serviceFee > 0) {
                    total += serviceFee;
                    displayLines.push({ 
                        label: 'Service Charge', 
                        amount: serviceFee 
                    });
                }
                
                // Check if TOTAL (services + service charge) is less than minimum service charge
=======
                // Get service charge from ZIP code data (this is the MINIMUM SERVICE CHARGE, not an add-on)
                var $wizard = $('#xtremecleans-service-selection, .xtremecleans-service-selection');
                var serviceFee = parseFloat($wizard.data('service-fee')) || 0;
                
                // Service Charge = Minimum Service Charge threshold (NOT added to total)
                var MINIMUM_SERVICE_CHARGE = serviceFee > 0 ? serviceFee : 199.00;
                
                // Total = services only (service charge is NOT added)
                var total = totalServicesAmount;
                
                // Check if services total is below the minimum service charge
>>>>>>> 3378c4f (plugin last vertation updated in admin dashboard)
                var isBelowMinimum = total > 0 && total < MINIMUM_SERVICE_CHARGE;
                
                // If services are selected
                if (totalServicesAmount > 0) {
<<<<<<< HEAD
                    // If total (with service charge) is below minimum, show notification (but not popup)
                    if (isBelowMinimum) {
                        // Show warning notification only (no popup)
                        // Show total including service charge in warning message
=======
                    if (isBelowMinimum) {
                        // Show warning: services total is below minimum
>>>>>>> 3378c4f (plugin last vertation updated in admin dashboard)
                        $('.xtremecleans-minimum-charge-warning').fadeIn(300);
                        $('.xtremecleans-current-services-total').text('$' + total.toFixed(2));
                        $('.xtremecleans-warning-minimum-amount').text('$' + MINIMUM_SERVICE_CHARGE.toFixed(2));
                        $('.xtremecleans-warning-final-amount').text('$' + MINIMUM_SERVICE_CHARGE.toFixed(2));
                    } else {
<<<<<<< HEAD
                        // Total (with service charge) meets or exceeds minimum ($199 or more)
                        $('.xtremecleans-minimum-charge-warning').fadeOut(300);
                    }
                } else {
                    // No services selected, hide warning (popup will show on Next click)
=======
                        // Services total meets or exceeds minimum
                        $('.xtremecleans-minimum-charge-warning').fadeOut(300);
                    }
                } else {
                    // No services selected, hide warning
>>>>>>> 3378c4f (plugin last vertation updated in admin dashboard)
                    $('.xtremecleans-minimum-charge-warning').fadeOut(300);
                }

                var $lines = $('.xtremecleans-quote-lines');
                if (displayLines.length === 0) {
                    $lines.html('<div class="xtremecleans-quote-empty">Add services to build your quote.</div>');
                } else {
                    var html = displayLines.map(function (line) {
                        var lineClass = line.is_minimum ? 'xtremecleans-quote-line xtremecleans-minimum-charge-line' : 'xtremecleans-quote-line';
                        return '<div class="' + lineClass + '"><span>' + line.label + '</span><span>$' + line.amount.toFixed(2) + '</span></div>';
                    }).join('');
                    $lines.html(html);
                }

                // Always show total (will be $0.00 if no services selected)
                $('.xtremecleans-estimated-amount').text('$' + total.toFixed(2));
                
                // Update total services amount (without service charge)
                $('.xtremecleans-quote-total-services').text('$' + totalServicesAmount.toFixed(2));
                
                // Calculate and display job duration
                var durationMinutes = calculateJobDuration();
                var durationFormatted = formatDuration(durationMinutes);
                
                console.log('🕐 Updating duration display:', durationMinutes, 'minutes =', durationFormatted);
                
                // Update duration display
                var $durationElement = $('.xtremecleans-job-duration');
                if ($durationElement.length > 0) {
                    $durationElement.text(durationFormatted);
                    $('.xtremecleans-job-duration-minutes').text(durationMinutes);
                    console.log('✓ Duration updated successfully to:', durationFormatted);
                } else {
                    console.error('❌ Duration element (.xtremecleans-job-duration) not found in DOM!');
                }
                
                // Update service information section in Step 1
                updateServiceInformation();
            }
            
            function updateServiceInformation() {
<<<<<<< HEAD
                // Get service charge from ZIP code data
                var $wizard = $('#xtremecleans-service-selection, .xtremecleans-service-selection');
                var serviceFee = parseFloat($wizard.data('service-fee')) || 0;
                
                // Minimum service charge = Service Fee value (or $199 if service fee is 0)
=======
                // Get service charge from ZIP code data (this is the MINIMUM SERVICE CHARGE, not an add-on)
                var $wizard = $('#xtremecleans-service-selection, .xtremecleans-service-selection');
                var serviceFee = parseFloat($wizard.data('service-fee')) || 0;
                
                // Service Charge = Minimum Service Charge threshold (NOT added to total)
>>>>>>> 3378c4f (plugin last vertation updated in admin dashboard)
                var MINIMUM_SERVICE_CHARGE = serviceFee > 0 ? serviceFee : 199.00;
                
                var selection = collectSelectedServicesData();
                var totalServicesAmount = selection.totalServicesAmount;
                
<<<<<<< HEAD
                // Calculate total with service charge
                var totalWithServiceCharge = totalServicesAmount;
                if (totalServicesAmount > 0 && serviceFee > 0) {
                    totalWithServiceCharge += serviceFee;
                }
                
                // Check if total (services + service charge) is below minimum
                var isBelowMinimum = totalWithServiceCharge > 0 && totalWithServiceCharge < MINIMUM_SERVICE_CHARGE;
                
                // Display service charge (dynamic from ZIP code)
                if (selection.totalServicesAmount > 0) {
                    if (serviceFee > 0) {
                        // Show actual service charge from ZIP code
                        $('.xtremecleans-review-service-charge').text('$' + serviceFee.toFixed(2));
                    } else {
                        $('.xtremecleans-review-service-charge').text('$0.00');
                    }
=======
                // Check if services total is below the minimum
                var isBelowMinimum = totalServicesAmount > 0 && totalServicesAmount < MINIMUM_SERVICE_CHARGE;
                
                // Display minimum service charge info (for reference, not added to total)
                if (selection.totalServicesAmount > 0) {
                    $('.xtremecleans-review-service-charge').text('$' + MINIMUM_SERVICE_CHARGE.toFixed(2) + ' (minimum)');
>>>>>>> 3378c4f (plugin last vertation updated in admin dashboard)
                } else {
                    $('.xtremecleans-review-service-charge').text('-');
                }
                
                // Continue with rest of function
                var servicesByGroup = selection.servicesByGroup;
                
                // Build HTML for service groups
                var $serviceGroups = $('.xtremecleans-service-groups');
                if (Object.keys(servicesByGroup).length === 0) {
                    $serviceGroups.html('<div class="xtremecleans-no-services">No services selected</div>');
                } else {
                    var html = '';
                    $.each(servicesByGroup, function(serviceName, items) {
                        var serviceTotal = 0;
                        var itemsHtml = '';
                        
                        $.each(items, function(index, item) {
                            serviceTotal += item.amount;
                            itemsHtml += '<div class="xtremecleans-service-item">' +
                                '<span class="xtremecleans-service-item-name">' + item.item + ' - ' + item.type + '</span>' +
                                '<span class="xtremecleans-service-item-qty">Qty: ' + item.quantity + '</span>' +
                                '<span class="xtremecleans-service-item-price">$' + item.amount.toFixed(2) + '</span>' +
                                '</div>';
                        });
                        
                        html += '<div class="xtremecleans-service-group">' +
                            '<h5 class="xtremecleans-service-group-title">' + serviceName + '</h5>' +
                            '<div class="xtremecleans-service-items">' + itemsHtml + '</div>' +
                            '<div class="xtremecleans-service-group-total">' +
                                '<span class="xtremecleans-group-total-label">Subtotal:</span>' +
                                '<span class="xtremecleans-group-total-value">$' + serviceTotal.toFixed(2) + '</span>' +
                            '</div>' +
                            '</div>';
                    });
                    
                    $serviceGroups.html(html);
                }
                
                // Update subtotal (services amount only, without service charge)
                $('.xtremecleans-review-subtotal-amount').text('$' + totalServicesAmount.toFixed(2));
                
                // Update Total Services Amount (services only, without service charge)
                // This should show just the services amount, not including service charge
                $('.xtremecleans-review-total-services-amount').text('$' + totalServicesAmount.toFixed(2));
                
<<<<<<< HEAD
                // Calculate final total: services + service charge
                var finalTotal = totalServicesAmount;
                
                // Add service charge if services are selected
                if (totalServicesAmount > 0 && serviceFee > 0) {
                    finalTotal += serviceFee;
                }
                
                // Apply minimum service charge if total (services + service charge) is below minimum
                if (isBelowMinimum) {
                    finalTotal = MINIMUM_SERVICE_CHARGE;
                }
                
                // Update other total amount displays (if any) with final total including service charge
=======
                // Total = services only (service charge is NOT added to price)
                var finalTotal = totalServicesAmount;
                
                // Update total amount displays
>>>>>>> 3378c4f (plugin last vertation updated in admin dashboard)
                $('.xtremecleans-review-total-amount').not('.xtremecleans-review-total-services-amount').text('$' + finalTotal.toFixed(2));
            }
            
            // Make updateQuoteSummary accessible globally
            window.updateQuoteSummary = updateQuoteSummary;
            
            // Make updateServiceInformation accessible globally
            window.updateServiceInformation = updateServiceInformation;
            
            // Initialize service information on load
            updateServiceInformation();

            updateQuoteSummary();
        }

        initServicePanels();
        initMultiStepFlow();
        
        function initMultiStepFlow(force) {
            var $wizard = $('#xtremecleans-service-selection');
            if (!$wizard.length) {
                return;
            }

            var alreadyInit = $wizard.data('xtremecleansStepsInit');
            if (alreadyInit && !force) {
                return;
            }

            var $stepContents = $wizard.find('.xtremecleans-step-content');
            if (!$stepContents.length) {
                return;
            }

            $wizard.data('xtremecleansStepsInit', true);

            var stepNames = ['Select Services', 'Scheduling', 'Your Information', 'Review Your Order'];
            var totalSteps = $stepContents.length;
            var currentStep = 1;
            var $nextBtn = $wizard.find('.xtremecleans-step-next');
            var $prevBtn = $wizard.find('.xtremecleans-step-prev');
            var $progressSteps = $wizard.find('.xtremecleans-progress-step');
            var $modal = $wizard.find('.xtremecleans-service-modal');
            var $successModal = $('#xtremecleans-success-modal');
            var $successOverlay = $successModal.find('.xtremecleans-success-overlay');
            var $successClose = $successModal.find('.xtremecleans-success-close, .xtremecleans-success-dismiss');
            var $placeOrderBtn = $wizard.find('.xtremecleans-place-order');
            if ($placeOrderBtn.length && !$placeOrderBtn.data('original-text')) {
                $placeOrderBtn.data('original-text', $placeOrderBtn.text());
            }
            var $orderFeedback = $('.xtremecleans-order-feedback');
            var orderSubmitting = false;
            
            // Expose setStep function globally for use by other handlers
            window.xtremecleansSetStep = null;

            $nextBtn.off('.xtremecleansSteps');
            $prevBtn.off('.xtremecleansSteps');
            $progressSteps.off('.xtremecleansSteps');
            $successClose.off('.xtremecleansSteps');
            $successOverlay.off('.xtremecleansSteps');

            function setStep(step) {
                currentStep = Math.min(Math.max(step, 1), totalSteps);
                
                // Expose setStep function globally for use by other handlers
                window.xtremecleansSetStep = setStep;
                
<<<<<<< HEAD
=======
                // Stop calendar auto-refresh when leaving Step 2
                if (currentStep !== 2 && typeof stopCalendarAutoRefresh === 'function') {
                    stopCalendarAutoRefresh();
                }
                
>>>>>>> 3378c4f (plugin last vertation updated in admin dashboard)
                // If moving to step 2, generate dynamic calendar
                if (currentStep === 2) {
                    // Reset week start when entering Step 2
                    currentWeekStart = null;
                    // Wait for DOM update, then generate calendar
                    setTimeout(function() {
                        var $calendar = $('#xtremecleans-dynamic-calendar');
                        if ($calendar.length > 0) {
                            console.log('Step 2 activated, generating calendar...');
                            generateDynamicCalendar();
<<<<<<< HEAD
=======
                            // Start auto-refresh for live Jobber booking updates (every 60s)
                            if (typeof startCalendarAutoRefresh === 'function') {
                                startCalendarAutoRefresh();
                            }
>>>>>>> 3378c4f (plugin last vertation updated in admin dashboard)
                        } else {
                            console.error('Calendar container not found when Step 2 activated');
                        }
                        // Update selection summary when entering Step 2
                        if (typeof updateSelectionSummary === 'function') {
                            updateSelectionSummary();
                        }
                    }, 300);
                }
                
                // If moving to step 4, populate review section with step 3 data
                if (currentStep === 4) {
                    populateReviewStep();
                }
                
                $stepContents
                    .removeClass('active')
                    .attr('aria-hidden', 'true');
                $stepContents
                    .filter('[data-step="' + currentStep + '"]')
                    .addClass('active')
                    .attr('aria-hidden', 'false');
                updateProgress();
                updateButtons();
                if (currentStep !== totalSteps) {
                    clearOrderFeedback();
                }
                if ($modal.length) {
                    $modal.animate({ scrollTop: 0 }, 250);
                }
            }
            
            function clearOrderFeedback() {
                if ($orderFeedback.length) {
                    $orderFeedback.removeClass('is-error is-success').text('').hide();
                }
            }
            
            function displayOrderFeedback(message, isError) {
                if ($orderFeedback.length) {
                    $orderFeedback
                        .removeClass('is-error is-success')
                        .addClass(isError ? 'is-error' : 'is-success')
                        .html(message)
                        .show();
                } else if (isError) {
                    alert(message);
                } else {
                    console.log(message);
                }
            }
            
            function setSubmittingState(isSubmitting) {
                orderSubmitting = isSubmitting;
                if (isSubmitting) {
                    $nextBtn.prop('disabled', true).text('Submitting...');
                    $prevBtn.prop('disabled', true);
                    if ($placeOrderBtn.length) {
                        $placeOrderBtn.prop('disabled', true).text('Submitting...');
                    }
                } else {
                    $nextBtn.prop('disabled', false);
                    $prevBtn.prop('disabled', false);
                    if ($placeOrderBtn.length) {
                        $placeOrderBtn.prop('disabled', false).text($placeOrderBtn.data('original-text') || 'Place Order');
                    }
                    updateButtons();
                }
            }
            
            /**
             * Generate dynamic calendar based on workday rules
             * SECTION 1 — WORKDAY RULES: 8:00 AM - 5:00 PM
             * SECTION 2 — ARRIVAL WINDOWS: 8-9 AM, 11 AM-2 PM, 2:30-5 PM
             * Saturday-Sunday: Closed
             */
            var currentWeekStart = null;
            var isGeneratingCalendar = false; // Guard to prevent multiple simultaneous calls
            
<<<<<<< HEAD
=======
            // Cache Jobber availability so we only fetch once per page load
            var cachedJobberAvailability = null;
            
            function fetchJobberAvailability(callback) {
                // Return cached if available
                if (cachedJobberAvailability) {
                    callback(cachedJobberAvailability);
                    return;
                }
                
                $.ajax({
                    url: typeof xtremecleansData !== 'undefined' ? xtremecleansData.ajaxUrl : '',
                    type: 'GET',
                    data: {
                        action: 'xtremecleans_get_jobber_availability'
                    },
                    success: function(res) {
                        if (res && res.success && res.data) {
                            cachedJobberAvailability = res.data;
                            console.log('Jobber availability loaded (source: ' + (res.data.source || 'unknown') + '):', res.data);
                        } else {
                            // Fallback defaults
                            cachedJobberAvailability = {
                                arrival_windows: [
                                    { start: 8, end: 9, label: '8:00 AM - 9:00 AM' },
                                    { start: 11, end: 14, label: '11:00 AM - 2:00 PM' },
                                    { start: 14.5, end: 17, label: '2:30 PM - 5:00 PM' }
                                ],
                                working_days: [1, 2, 3, 4, 5],
                                workday_start: 8,
                                workday_end: 17,
                                source: 'fallback'
                            };
                            console.log('Using fallback availability (API returned no data)');
                        }
                        callback(cachedJobberAvailability);
                    },
                    error: function() {
                        // Fallback defaults on error
                        cachedJobberAvailability = {
                            arrival_windows: [
                                { start: 8, end: 9, label: '8:00 AM - 9:00 AM' },
                                { start: 11, end: 14, label: '11:00 AM - 2:00 PM' },
                                { start: 14.5, end: 17, label: '2:30 PM - 5:00 PM' }
                            ],
                            working_days: [1, 2, 3, 4, 5],
                            workday_start: 8,
                            workday_end: 17,
                            source: 'fallback'
                        };
                        console.log('Using fallback availability (API error)');
                        callback(cachedJobberAvailability);
                    }
                });
            }
            
>>>>>>> 3378c4f (plugin last vertation updated in admin dashboard)
            function generateDynamicCalendar(weekOffset) {
                // Prevent multiple simultaneous calls
                if (isGeneratingCalendar) {
                    console.log('Calendar generation already in progress, skipping...');
                    return;
                }
                
                weekOffset = weekOffset || 0;
                console.log('=== generateDynamicCalendar called ===');
                console.log('weekOffset:', weekOffset);
                
                var $calendar = $('#xtremecleans-dynamic-calendar');
                console.log('Calendar container found:', $calendar.length > 0);
                
                if ($calendar.length === 0) {
                    console.error('Calendar container #xtremecleans-dynamic-calendar not found');
                    isGeneratingCalendar = false; // Reset flag on early return
                    return;
                }
                
                // Set guard flag
                isGeneratingCalendar = true;
                
<<<<<<< HEAD
                // Use try-finally to ensure flag is reset even on errors
                try {
                
                console.log('Generating calendar, weekOffset:', weekOffset);
=======
                // Fetch dynamic availability from Jobber, then build calendar
                fetchJobberAvailability(function(availability) {
                    try {
                        buildCalendarWithAvailability(weekOffset, availability, $calendar);
                    } catch (error) {
                        console.error('Error generating calendar:', error);
                        isGeneratingCalendar = false;
                    }
                });
            }
            
            function buildCalendarWithAvailability(weekOffset, availability, $calendar) {
                console.log('Building calendar with dynamic availability...');
>>>>>>> 3378c4f (plugin last vertation updated in admin dashboard)
                
                // Calculate week start (Monday)
                var today = new Date();
                today.setHours(0, 0, 0, 0);
                
                // Always calculate from today when weekOffset is 0, or adjust from current week
                if (currentWeekStart === null) {
                    // Find Monday of current week
                    var dayOfWeek = today.getDay(); // 0 = Sunday, 1 = Monday, etc.
                    var daysToMonday = dayOfWeek === 0 ? -6 : 1 - dayOfWeek;
                    
                    currentWeekStart = new Date(today);
                    currentWeekStart.setDate(today.getDate() + daysToMonday);
                    currentWeekStart.setHours(0, 0, 0, 0);
                }
                
                // Apply week offset
                if (weekOffset !== 0) {
                    currentWeekStart = new Date(currentWeekStart);
                    currentWeekStart.setDate(currentWeekStart.getDate() + (weekOffset * 7));
                }
                
<<<<<<< HEAD
                // SECTION 1 — WORKDAY RULES
                // Workday is 8:00 AM – 5:00 PM
                // No job may start before 8:00 AM or end after 5:00 PM
                var workdayStart = 8;  // 8:00 AM
                var workdayEnd = 17;   // 5:00 PM (17:00 in 24-hour format)
                
                // SECTION 2 — ARRIVAL WINDOWS (Customer-Facing)
                // These are arrival labels only. Work may extend beyond the label if allowed
                var arrivalWindows = [
                    {
                        start: 8,   // 8:00 AM
                        end: 9,     // 9:00 AM
                        label: '8:00 AM - 9:00 AM'
                    },
                    {
                        start: 11,  // 11:00 AM
                        end: 14,    // 2:00 PM (14:00 in 24-hour format)
                        label: '11:00 AM - 2:00 PM'
                    },
                    {
                        start: 14.5, // 2:30 PM (14.5 = 14:30)
                        end: 17,     // 5:00 PM
                        label: '2:30 PM - 5:00 PM'
                    }
=======
                // DYNAMIC: Use Jobber-derived workday settings (or defaults)
                var workdayStart = availability.workday_start || 8;
                var workdayEnd = availability.workday_end || 17;
                
                // DYNAMIC: Use Jobber-derived working days (default Mon-Fri)
                // working_days array: 0=Sun, 1=Mon...6=Sat
                var workingDays = availability.working_days || [1, 2, 3, 4, 5];
                
                // DYNAMIC: Use Jobber-derived arrival windows (or defaults)
                var arrivalWindows = availability.arrival_windows || [
                    { start: 8, end: 9, label: '8:00 AM - 9:00 AM' },
                    { start: 11, end: 14, label: '11:00 AM - 2:00 PM' },
                    { start: 14.5, end: 17, label: '2:30 PM - 5:00 PM' }
>>>>>>> 3378c4f (plugin last vertation updated in admin dashboard)
                ];
                
                // Generate time slots based on arrival windows
                var timeSlots = [];
                arrivalWindows.forEach(function(window) {
<<<<<<< HEAD
                    // Ensure window fits within workday boundary (8 AM - 5 PM)
=======
                    // Ensure window fits within workday boundary
>>>>>>> 3378c4f (plugin last vertation updated in admin dashboard)
                    var windowStart = Math.max(window.start, workdayStart);
                    var windowEnd = Math.min(window.end, workdayEnd);
                    
                    // Only add if window is valid and within workday
                    if (windowStart < windowEnd && windowStart >= workdayStart && windowEnd <= workdayEnd) {
                        timeSlots.push({
                            start: windowStart,
                            end: windowEnd,
                            label: window.label
                        });
                    }
                });
                
                // Debug: Log generated time slots
<<<<<<< HEAD
                console.log('Generated Arrival Windows:', timeSlots.map(function(s) { return s.label; }));
                console.log('Workday: 8:00 AM - 5:00 PM');
=======
                console.log('Dynamic Arrival Windows:', timeSlots.map(function(s) { return s.label; }));
                console.log('Workday: ' + workdayStart + ':00 - ' + workdayEnd + ':00 (source: ' + (availability.source || 'unknown') + ')');
                console.log('Working Days:', workingDays);
>>>>>>> 3378c4f (plugin last vertation updated in admin dashboard)
                
                // Generate days for the week
                var days = [];
                var dayNames = ['Mon', 'Tue', 'Wed', 'Thu', 'Fri', 'Sat', 'Sun'];
                var todayDateOnly = new Date(today.getFullYear(), today.getMonth(), today.getDate());
                
                for (var i = 0; i < 7; i++) {
                    var date = new Date(currentWeekStart);
                    date.setDate(currentWeekStart.getDate() + i);
                    var dateOnly = new Date(date.getFullYear(), date.getMonth(), date.getDate());
                    var isPast = dateOnly < todayDateOnly;
                    
<<<<<<< HEAD
                    days.push({
                        date: date,
                        dayName: dayNames[i],
                        dayOfWeek: date.getDay(),
                        isWeekend: date.getDay() === 0 || date.getDay() === 6,
=======
                    // DYNAMIC: Check if this day is a working day (based on Jobber data)
                    var jsDay = date.getDay(); // 0=Sun, 1=Mon...6=Sat
                    var isOffDay = workingDays.indexOf(jsDay) === -1; // Not in working days list
                    
                    days.push({
                        date: date,
                        dayName: dayNames[i],
                        dayOfWeek: jsDay,
                        isWeekend: isOffDay, // Renamed from isWeekend to isOffDay logic
>>>>>>> 3378c4f (plugin last vertation updated in admin dashboard)
                        isPast: isPast
                    });
                }
                
                // Build calendar header
                var $header = $('#xtremecleans-calendar-header tr');
                if ($header.length === 0) {
                    console.error('Calendar header row not found');
                    isGeneratingCalendar = false; // Reset flag on early return
                    return;
                }
                $header.empty();
                $header.append('<th>Arrival Windows</th>');
                
                days.forEach(function(day) {
                    var dateStr = formatDateShort(day.date);
                    var dayClass = day.isWeekend ? 'closed' : '';
                    $header.append('<th class="' + dayClass + '">' + dateStr + '<br><span>' + day.dayName + '</span></th>');
                });
                
                // Update month/year display
                var monthYear = formatMonthYear(days[0].date);
                $('.xtremecleans-calendar-month-year').text(monthYear);
                
                // Update week range display (e.g., "1/12 - 1/18" means Jan 12 to Jan 18)
                var weekStartStr = formatDateShort(days[0].date);
                var weekEndStr = formatDateShort(days[6].date);
                $('.xtremecleans-calendar-week-range').text(weekStartStr + ' - ' + weekEndStr);
                
                // Build calendar body (after fetching booked slots so one booking per slot)
                var $body = $('#xtremecleans-calendar-body');
                if ($body.length === 0) {
                    console.error('Calendar body not found');
                    isGeneratingCalendar = false;
                    return;
                }
                $body.empty();
                
                var weekStartYmd = formatDate(days[0].date);
                var weekEndYmd = formatDate(days[6].date);
<<<<<<< HEAD
=======
                var slotCapacity = 1;
>>>>>>> 3378c4f (plugin last vertation updated in admin dashboard)
                
                function buildCalendarBody(bookedSlotsMap) {
                    $body.empty();
                    timeSlots.forEach(function(slot) {
                        var $row = $('<tr></tr>');
                        $row.append('<td class="xtremecleans-window-label">' + slot.label + '</td>');
                        
                        days.forEach(function(day) {
                            var $cell = $('<td></td>');
                            var slotKey = formatDate(day.date) + '|' + slot.label;
<<<<<<< HEAD
                            var isBooked = bookedSlotsMap[slotKey];
                            
                            // Saturday and Sunday are closed
=======
                            var bookedCount = parseInt(bookedSlotsMap[slotKey], 10) || 0;
                            var isBooked = bookedCount >= slotCapacity;
                            var isToday = !day.isPast && day.date.toDateString() === today.toDateString();
                            var bookingLabel = '';
                            if (slotCapacity > 1 && bookedCount > 0) {
                                bookingLabel = '<div class="xtremecleans-slot-capacity-label" style="font-size:10px;line-height:1.2;margin-top:4px;color:#1d2327;text-align:center;font-weight:600;">Booked: ' + bookedCount + '/' + slotCapacity + '</div>';
                            }
                            var availTitle = (slotCapacity > 1 && bookedCount > 0)
                                ? ('Booked ' + bookedCount + '/' + slotCapacity + ' — click to select')
                                : 'Click to select';
                            
                            // Off-days (weekends or non-working days from Jobber) are closed
>>>>>>> 3378c4f (plugin last vertation updated in admin dashboard)
                            if (day.isWeekend) {
                                $cell.addClass('xtremecleans-slot unavailable closed');
                                $cell.attr('title', 'Closed');
                            }
                            // Past dates are unavailable
                            else if (day.isPast) {
                                $cell.addClass('xtremecleans-slot unavailable past');
                                $cell.attr('title', 'Past date');
                            }
<<<<<<< HEAD
                            // Already booked by someone else
                            else if (isBooked) {
                                $cell.addClass('xtremecleans-slot unavailable booked');
                                $cell.attr('title', 'Already booked');
                            }
                            // Check if today's slot is in the past (respecting workday boundaries)
                            else if (!day.isPast && day.date.toDateString() === today.toDateString()) {
                                var now = new Date();
                                var currentHour = now.getHours();
                                var currentMinutes = now.getMinutes();
                                var currentTimeDecimal = currentHour + (currentMinutes / 60);
                                
                                if (currentTimeDecimal >= slot.start) {
                                    $cell.addClass('xtremecleans-slot unavailable past');
                                    $cell.attr('title', 'Time slot has passed');
                                } else if (slot.start < workdayStart) {
                                    $cell.addClass('xtremecleans-slot unavailable');
                                    $cell.attr('title', 'Outside workday hours (8 AM - 5 PM)');
                                } else if (slot.end > workdayEnd) {
                                    $cell.addClass('xtremecleans-slot unavailable');
                                    $cell.attr('title', 'Outside workday hours (8 AM - 5 PM)');
                                } else {
                                    $cell.addClass('xtremecleans-slot available');
                                    $cell.append('<span class="xtremecleans-slot-dot"></span>');
                                    $cell.attr('data-date', formatDate(day.date));
                                    $cell.attr('data-time', slot.label);
                                    $cell.attr('title', 'Click to select');
                                    $cell.on('click', function() { selectCalendarSlot($(this), day, slot); });
                                }
                            }
                            // Available slots for Monday-Friday (future dates)
                            else {
                                if (slot.start < workdayStart || slot.end > workdayEnd) {
                                    $cell.addClass('xtremecleans-slot unavailable');
                                    $cell.attr('title', 'Outside workday hours (8 AM - 5 PM)');
                                } else {
                                    $cell.addClass('xtremecleans-slot available');
                                    $cell.append('<span class="xtremecleans-slot-dot"></span>');
                                    $cell.attr('data-date', formatDate(day.date));
                                    $cell.attr('data-time', slot.label);
                                    $cell.attr('title', 'Click to select');
=======
                            // Today — check time first, then booked status
                            else if (isToday) {
                                var now = new Date();
                                var currentTimeDecimal = now.getHours() + (now.getMinutes() / 60);
                                
                                // Time slot already passed — show as "past" (not "booked")
                                if (currentTimeDecimal >= slot.start) {
                                    $cell.addClass('xtremecleans-slot unavailable past');
                                    $cell.attr('title', 'Time slot has passed');
                                }
                                // Future slot today that is booked in Jobber/WP
                                else if (isBooked) {
                                    $cell.addClass('xtremecleans-slot unavailable booked');
                                    $cell.attr('title', 'Booked (' + bookedCount + '/' + slotCapacity + ')');
                                    if (bookingLabel) {
                                        $cell.append(bookingLabel);
                                    }
                                }
                                // Outside workday hours
                                else if (slot.start < workdayStart || slot.end > workdayEnd) {
                                    $cell.addClass('xtremecleans-slot unavailable');
                                    $cell.attr('title', 'Outside workday hours');
                                }
                                // Available today
                                else {
                                    $cell.addClass('xtremecleans-slot available');
                                    $cell.append('<span class="xtremecleans-slot-dot"></span>');
                                    if (bookingLabel) {
                                        $cell.append(bookingLabel);
                                    }
                                    $cell.attr('data-date', formatDate(day.date));
                                    $cell.attr('data-time', slot.label);
                                    $cell.attr('title', availTitle);
                                    $cell.on('click', function() { selectCalendarSlot($(this), day, slot); });
                                }
                            }
                            // Future dates — already booked by someone else
                            else if (isBooked) {
                                $cell.addClass('xtremecleans-slot unavailable booked');
                                $cell.attr('title', 'Booked (' + bookedCount + '/' + slotCapacity + ')');
                                if (bookingLabel) {
                                    $cell.append(bookingLabel);
                                }
                            }
                            // Future dates — available slots
                            else {
                                if (slot.start < workdayStart || slot.end > workdayEnd) {
                                    $cell.addClass('xtremecleans-slot unavailable');
                                    $cell.attr('title', 'Outside workday hours');
                                } else {
                                    $cell.addClass('xtremecleans-slot available');
                                    $cell.append('<span class="xtremecleans-slot-dot"></span>');
                                    if (bookingLabel) {
                                        $cell.append(bookingLabel);
                                    }
                                    $cell.attr('data-date', formatDate(day.date));
                                    $cell.attr('data-time', slot.label);
                                    $cell.attr('title', availTitle);
>>>>>>> 3378c4f (plugin last vertation updated in admin dashboard)
                                    $cell.on('click', function() { selectCalendarSlot($(this), day, slot); });
                                }
                            }
                            
                            $row.append($cell);
                        });
                        $body.append($row);
                    });
                    $calendar.show();
                    $('.xtremecleans-calendar-week-range').text(weekStartStr + ' - ' + weekEndStr);
<<<<<<< HEAD
=======
                    
                    // Restore selected slot after rebuild (preserves selection during auto-refresh)
                    var savedSlot = sessionStorage.getItem('xtremecleans_selected_slot');
                    if (savedSlot) {
                        try {
                            var parsed = JSON.parse(savedSlot);
                            var $savedCell = $body.find('.xtremecleans-slot.available[data-date="' + parsed.date + '"][data-time="' + parsed.time + '"]');
                            if ($savedCell.length > 0) {
                                $savedCell.addClass('selected');
                            } else {
                                // Slot no longer available (booked by someone else) — clear selection
                                sessionStorage.removeItem('xtremecleans_selected_slot');
                                if (typeof updateSelectionSummary === 'function') {
                                    updateSelectionSummary();
                                }
                            }
                        } catch (e) {}
                    }
                    
>>>>>>> 3378c4f (plugin last vertation updated in admin dashboard)
                    isGeneratingCalendar = false;
                }
                
                $.ajax({
                    url: typeof xtremecleansData !== 'undefined' ? xtremecleansData.ajaxUrl : '',
                    type: 'GET',
                    data: {
                        action: 'xtremecleans_get_booked_slots',
                        week_start: weekStartYmd,
                        week_end: weekEndYmd
                    },
                    success: function(res) {
                        var booked = {};
<<<<<<< HEAD
                        if (res && res.data && res.data.booked_slots && res.data.booked_slots.length) {
                            res.data.booked_slots.forEach(function(s) {
                                booked[(s.date || '') + '|' + (s.time || '')] = true;
                            });
=======
                        if (res && res.data) {
                            slotCapacity = parseInt(res.data.slot_capacity, 10) || 1;
                            if (slotCapacity < 1) {
                                slotCapacity = 1;
                            }

                            // Preferred: backend provides exact count per slot.
                            if (res.data.slot_counts && typeof res.data.slot_counts === 'object') {
                                Object.keys(res.data.slot_counts).forEach(function(key) {
                                    var slot = res.data.slot_counts[key] || {};
                                    booked[key] = parseInt(slot.count, 10) || 0;
                                });
                            } else if (res.data.booked_slots && res.data.booked_slots.length) {
                                // Backward compatibility: derive counts from flat list.
                                res.data.booked_slots.forEach(function(s) {
                                    var key = (s.date || '') + '|' + (s.time || '');
                                    if (!booked[key]) {
                                        booked[key] = 0;
                                    }
                                    booked[key]++;
                                });
                            }
>>>>>>> 3378c4f (plugin last vertation updated in admin dashboard)
                        }
                        buildCalendarBody(booked);
                    },
                    error: function() {
                        buildCalendarBody({});
                    }
                });
<<<<<<< HEAD
                
                } catch (error) {
                    console.error('Error generating calendar:', error);
                    isGeneratingCalendar = false;
                }
            }
            
            // Global function for testing (can be called from console)
            window.xtremecleansGenerateCalendar = function() {
                console.log('Manual calendar generation triggered');
                currentWeekStart = null;
=======
            }
            
            // Global function for testing (can be called from console)
            window.xtremecleansGenerateCalendar = function(forceRefresh) {
                console.log('Manual calendar generation triggered' + (forceRefresh ? ' (force refresh)' : ''));
                currentWeekStart = null;
                if (forceRefresh) {
                    cachedJobberAvailability = null; // Force re-fetch from Jobber
                }
>>>>>>> 3378c4f (plugin last vertation updated in admin dashboard)
                generateDynamicCalendar();
            };
            
            /**
             * Format time for display (7:00 AM, 9:00 AM, etc.)
             */
            function formatTime(hour) {
                var period = hour >= 12 ? 'PM' : 'AM';
                var displayHour = hour > 12 ? hour - 12 : (hour === 0 ? 12 : hour);
                return displayHour + ':00 ' + period;
            }
            
            /**
             * Format date as short string (MM/DD)
             */
            function formatDateShort(date) {
                var month = date.getMonth() + 1;
                var day = date.getDate();
                return month + '/' + day;
            }
            
            /**
             * Format month and year (e.g., "December 2024")
             */
            function formatMonthYear(date) {
                var months = ['January', 'February', 'March', 'April', 'May', 'June', 
                             'July', 'August', 'September', 'October', 'November', 'December'];
                var month = months[date.getMonth()];
                var year = date.getFullYear();
                return month + ' ' + year;
            }
            
            /**
             * Format date as Y-m-d
             */
            function formatDate(date) {
                var year = date.getFullYear();
                var month = String(date.getMonth() + 1).padStart(2, '0');
                var day = String(date.getDate()).padStart(2, '0');
                return year + '-' + month + '-' + day;
            }
            
            /**
             * Handle calendar slot selection
             */
            function selectCalendarSlot($cell, day, slot) {
                // Remove previous selection
                $('.xtremecleans-slot').removeClass('selected');
                $cell.addClass('selected');
                
                // Store selected slot
                var selectedDate = formatDate(day.date);
                var selectedTime = slot.label;
                
                sessionStorage.setItem('xtremecleans_selected_slot', JSON.stringify({
                    date: selectedDate,
                    time: selectedTime,
                    datetime: selectedDate + ' ' + selectedTime,
                    day_name: day.dayName
                }));
                
                // Update selection summary
                updateSelectionSummary();
            }
            
            /**
             * Update selection summary card with services and appointment
             */
            function updateSelectionSummary() {
                var $summary = $('#xtremecleans-selection-summary');
                if ($summary.length === 0) {
                    return;
                }
                
                var selection = collectSelectedServicesData();
                var selectedServices = selection.serviceLines || [];
                
                // Get selected appointment
                var appointmentInfo = null;
                var slotData = sessionStorage.getItem('xtremecleans_selected_slot');
                if (slotData) {
                    try {
                        var slot = JSON.parse(slotData);
                        var dateObj = new Date(slot.date);
                        var dateStr = formatDateShort(dateObj) + ' (' + slot.day_name + ')';
                        appointmentInfo = {
                            date: dateStr,
                            time: slot.time,
                            full: dateStr + ' at ' + slot.time
                        };
                    } catch (e) {
                        console.error('Error parsing slot data:', e);
                    }
                }
                
                // Update services section
                var $servicesList = $summary.find('.xtremecleans-summary-services-list');
                if (selectedServices.length === 0) {
                    $servicesList.html('<p class="xtremecleans-summary-empty">No services selected yet</p>');
                } else {
                    var servicesHtml = '<ul class="xtremecleans-summary-list">';
                    selectedServices.forEach(function(service) {
                        servicesHtml += '<li>' + 
                            '<strong>' + service.service + '</strong> - ' + 
                            service.item + ' (' + service.type + ') ' +
                            '<span class="xtremecleans-summary-qty">x' + service.quantity + '</span>' +
                            '</li>';
                    });
                    servicesHtml += '</ul>';
                    $servicesList.html(servicesHtml);
                }
                
                // Update appointment section
                var $appointmentInfo = $summary.find('.xtremecleans-summary-appointment-info');
                if (!appointmentInfo) {
                    $appointmentInfo.html('<p class="xtremecleans-summary-empty">No appointment selected yet</p>');
                } else {
                    $appointmentInfo.html('<p class="xtremecleans-summary-appointment-text">' + appointmentInfo.full + '</p>');
                }
            }
            
            /**
             * Initialize calendar navigation
             */
            $(document).on('click', '.xtremecleans-calendar-prev', function() {
<<<<<<< HEAD
=======
                // Prevent navigating to past weeks
                if (currentWeekStart) {
                    var now = new Date();
                    now.setHours(0, 0, 0, 0);
                    var dow = now.getDay();
                    var daysToMon = dow === 0 ? -6 : 1 - dow;
                    var thisWeekMon = new Date(now);
                    thisWeekMon.setDate(now.getDate() + daysToMon);
                    
                    var prevWeekStart = new Date(currentWeekStart);
                    prevWeekStart.setDate(prevWeekStart.getDate() - 7);
                    
                    if (prevWeekStart < thisWeekMon) {
                        return; // Can't go to past weeks
                    }
                }
>>>>>>> 3378c4f (plugin last vertation updated in admin dashboard)
                generateDynamicCalendar(-1);
            });
            
            $(document).on('click', '.xtremecleans-calendar-next', function() {
<<<<<<< HEAD
                generateDynamicCalendar(1);
            });
            
=======
                // Limit to 12 weeks ahead
                if (currentWeekStart) {
                    var now = new Date();
                    now.setHours(0, 0, 0, 0);
                    var maxDate = new Date(now);
                    maxDate.setDate(maxDate.getDate() + 84); // 12 weeks
                    
                    var nextWeekStart = new Date(currentWeekStart);
                    nextWeekStart.setDate(nextWeekStart.getDate() + 7);
                    
                    if (nextWeekStart > maxDate) {
                        return; // Can't go more than 12 weeks ahead
                    }
                }
                generateDynamicCalendar(1);
            });
            
            // ── Auto-refresh booked slots every 60s for live Jobber updates ──
            var calendarRefreshInterval = null;
            
            function startCalendarAutoRefresh() {
                stopCalendarAutoRefresh();
                calendarRefreshInterval = setInterval(function() {
                    var $step2 = $('[data-step="2"]');
                    if ($step2.length > 0 && ($step2.hasClass('active') || $step2.is(':visible'))) {
                        // Don't reset currentWeekStart — refresh the same week view
                        if (!isGeneratingCalendar) {
                            generateDynamicCalendar(0);
                        }
                    } else {
                        stopCalendarAutoRefresh();
                    }
                }, 60000); // 60 seconds
            }
            
            function stopCalendarAutoRefresh() {
                if (calendarRefreshInterval) {
                    clearInterval(calendarRefreshInterval);
                    calendarRefreshInterval = null;
                }
            }
            
>>>>>>> 3378c4f (plugin last vertation updated in admin dashboard)
            // Initialize calendar on page load if Step 2 is visible
            function tryGenerateCalendar() {
                // Prevent excessive calls - only check if not already generating
                if (isGeneratingCalendar) {
                    return false;
                }
                
                var $calendar = $('#xtremecleans-dynamic-calendar');
                var $step2 = $('[data-step="2"]');
                
                console.log('tryGenerateCalendar called');
                console.log('Calendar container found:', $calendar.length > 0);
                console.log('Step 2 element found:', $step2.length > 0);
                
                if ($calendar.length > 0 && $step2.length > 0) {
                    var isActive = $step2.hasClass('active');
                    var isVisible = $step2.is(':visible');
                    console.log('Step 2 active:', isActive, 'visible:', isVisible);
                    
                    if (isActive || isVisible) {
                        var $body = $calendar.find('#xtremecleans-calendar-body');
                        var hasContent = $body.length > 0 && $body.children().length > 0;
                        console.log('Calendar body has content:', hasContent);
                        
                        if (!hasContent) {
                            console.log('Step 2 is active, generating calendar...');
                            currentWeekStart = null;
                            generateDynamicCalendar();
                            return true;
                        } else {
                            console.log('Calendar already has content, skipping generation');
                            return true;
                        }
                    }
                } else {
                    if ($calendar.length === 0) {
                        console.log('Calendar container not found');
                    }
                    if ($step2.length === 0) {
                        console.log('Step 2 element not found');
                    }
                }
                return false;
            }
            
            // Try multiple times to catch calendar initialization
            $(document).ready(function() {
                console.log('Document ready - checking for calendar initialization');
                
                // Try immediately
                if (!tryGenerateCalendar()) {
                    // Try after short delay
                    setTimeout(function() {
                        if (!tryGenerateCalendar()) {
                            // Try after longer delay
                            setTimeout(function() {
                                if (!tryGenerateCalendar()) {
                                    console.log('Calendar not generated yet, setting up interval check');
                                    // Set up interval to check every 500ms for 5 seconds
                                    var attempts = 0;
                                    var maxAttempts = 10;
                                    var checkInterval = setInterval(function() {
                                        attempts++;
                                        if (tryGenerateCalendar() || attempts >= maxAttempts) {
                                            clearInterval(checkInterval);
                                        }
                                    }, 500);
                                }
                            }, 1000);
                        }
                    }, 300);
                }
                
                // Also watch for Step 2 becoming visible
                var observer = new MutationObserver(function(mutations) {
                    // Prevent excessive calls - only check if not already generating
                    if (isGeneratingCalendar) {
                        return;
                    }
                    
                    var $step2 = $('[data-step="2"]');
                    if ($step2.length > 0 && ($step2.hasClass('active') || $step2.is(':visible'))) {
                        var $calendar = $('#xtremecleans-dynamic-calendar');
                        if ($calendar.length > 0) {
                            var $body = $calendar.find('#xtremecleans-calendar-body');
                            if ($body.length > 0 && $body.children().length === 0) {
                                console.log('Step 2 became visible, generating calendar...');
                                currentWeekStart = null;
                                generateDynamicCalendar();
                            }
                        }
                    }
                });
                
                // Observe wizard container for changes
                var $wizard = $('.xtremecleans-service-wizard, #xtremecleans-service-selection');
                if ($wizard.length > 0) {
                    console.log('Setting up MutationObserver for wizard');
                    observer.observe($wizard[0], {
                        attributes: true,
                        attributeFilter: ['class'],
                        childList: true,
                        subtree: true
                    });
                } else {
                    console.warn('Wizard container not found for MutationObserver');
                }
            });
            
            function populateReviewStep() {
                // Collect data from step 3 form
                var firstName = $('#xtremecleans-info-first-name').val() || '-';
                var lastName = $('#xtremecleans-info-last-name').val() || '-';
                var email = $('#xtremecleans-info-email').val() || '-';
                var phone = $('#xtremecleans-info-phone').val() || '-';
                var altPhone = $('#xtremecleans-info-alt-phone').val() || '-';
                var address1 = $('#xtremecleans-info-address1').val() || '-';
                var address2 = $('#xtremecleans-info-address2').val() || '-';
                var zip = $('#xtremecleans-info-zip').val() || '-';
                var city = $('#xtremecleans-info-city').val() || '-';
                var state = $('#xtremecleans-info-state').val() || '-';
                var instructions = $('#xtremecleans-info-instructions').val() || '-';
                
                // Populate review section
                $('.xtremecleans-review-first-name').text(firstName);
                $('.xtremecleans-review-last-name').text(lastName);
                $('.xtremecleans-review-email').text(email);
                $('.xtremecleans-review-phone').text(phone);
                $('.xtremecleans-review-alt-phone').text(altPhone || '-');
                $('.xtremecleans-review-address1').text(address1);
                $('.xtremecleans-review-address2').text(address2 || '-');
                $('.xtremecleans-review-zip').text(zip);
                $('.xtremecleans-review-city').text(city);
                $('.xtremecleans-review-state').text(state);
                $('.xtremecleans-review-instructions').text(instructions || '-');
            }

            function updateProgress() {
                // Hide all steps first, then show only current step
                $progressSteps.each(function(index) {
                    var stepIndex = index + 1;
                    $(this)
                        .removeClass('xtremecleans-step-active xtremecleans-step-complete')
                        .hide(); // Hide all steps by default
                    
                    // Show only the current step
                    if (stepIndex === currentStep) {
                        $(this).addClass('xtremecleans-step-active').show();
                    }
                });
                
                // Update progress line based on current step
                $('.xtremecleans-progress-steps').attr('data-step', currentStep);
            }

            function updateButtons() {
                if (!$nextBtn.length || !$prevBtn.length) {
                    return;
                }
                $prevBtn.prop('disabled', currentStep === 1);
                if (currentStep >= totalSteps) {
                    $nextBtn.text('Place Order');
                } else {
                    var nextLabel = stepNames[currentStep] || 'Next';
                    $nextBtn.text('Next: ' + nextLabel);
                }
            }

            function submitOrder() {
                if (orderSubmitting) {
                    return;
                }
                
                var payload = collectOrderPayload();
                
                if (!payload.services || !payload.services.length) {
                    alert('Please select at least one service before continuing.');
                    setStep(1);
                    return;
                }
                
                if (!payload.appointment || !payload.appointment.date || !payload.appointment.time) {
                    alert('Please choose an appointment window in Step 2.');
                    setStep(2);
                    return;
                }
                
                if (!payload.customer || !payload.customer.first_name || !payload.customer.last_name || !payload.customer.email || !payload.customer.phone) {
                    alert('Please complete all required fields in Step 3.');
                    setStep(3);
                    return;
                }
                
                clearOrderFeedback();
                setSubmittingState(true);
                
                $.ajax({
                    url: xtremecleansData.ajaxUrl,
                    type: 'POST',
                    data: {
                        action: 'xtremecleans_place_order',
                        nonce: xtremecleansData.placeOrderNonce || xtremecleansData.nonce,
                        order: JSON.stringify(payload)
                    },
                    success: function(response) {
                        setSubmittingState(false);
                        if (response && response.success) {
                            // Check if payment is required
                            if (response.data && response.data.requires_payment) {
                                // Show payment modal - deposit is always $20.00
                                var depositAmount = 20.00;
                                showPaymentModal(response.data.order_id, depositAmount);
                                return;
                            }
                            
                            var message = (response.data && response.data.message) ? response.data.message : 'Order placed successfully!';
                            displayOrderFeedback(message, false);
                    
                    if (response.data && response.data.jobber_auth_url) {
                        var authLink = '<a href="' + response.data.jobber_auth_url + '" target="_blank" rel="noopener">' +
                            'Connect Jobber' + '</a>';
                        displayOrderFeedback('Please authorize Jobber access to finish syncing. ' + authLink, true);
                        return;
                    }
                    
                            $wizard.trigger('xtremecleansStepsComplete', [payload, response]);
                            showSuccessModal();
                            try {
                                sessionStorage.removeItem('xtremecleans_selected_slot');
                            } catch (err) {}
                        } else {
                            var errorMessage = (response && response.data && response.data.message) ? response.data.message : 'Failed to place order. Please try again.';
                    var needsAuth = response && response.data && response.data.jobber_auth_url;
                    if (needsAuth) {
                        errorMessage += ' <a href="' + response.data.jobber_auth_url + '" target="_blank" rel="noopener">Connect Jobber</a>';
                    }
                            displayOrderFeedback(errorMessage, true);
                        }
                    },
                    error: function() {
                        setSubmittingState(false);
                        displayOrderFeedback('Failed to place order. Please try again.', true);
                    }
                });
            }

            $nextBtn.on('click.xtremecleansSteps', function() {
                if (orderSubmitting) {
                    return;
                }
                
                // Validate step 1 (service selection) - check minimum service charge
                if (currentStep === 1 && !validateStep1MinimumCharge()) {
                    return;
                }
<<<<<<< HEAD
=======

                // Validate step 2 (appointment slot) before proceeding to step 3
                if (currentStep === 2 && !validateStep2Appointment()) {
                    return;
                }
>>>>>>> 3378c4f (plugin last vertation updated in admin dashboard)
                
                // Validate step 3 before proceeding to step 4
                if (currentStep === 3 && !validateStep3()) {
                    return;
                }
                
                if (currentStep < totalSteps) {
                    setStep(currentStep + 1);
                } else {
                    submitOrder();
                }
            });
            
<<<<<<< HEAD
=======
            function validateStep2Appointment() {
                var slotData = null;
                try {
                    slotData = sessionStorage.getItem('xtremecleans_selected_slot');
                } catch (e) {}
                
                if (!slotData) {
                    alert('Please select an appointment date and arrival window before continuing to Step 3.');
                    return false;
                }
                
                try {
                    var parsed = JSON.parse(slotData);
                    if (!parsed || !parsed.date || !parsed.time) {
                        alert('Please select an appointment date and arrival window before continuing to Step 3.');
                        return false;
                    }
                } catch (e) {
                    alert('Please select an appointment date and arrival window before continuing to Step 3.');
                    return false;
                }
                
                return true;
            }
            
>>>>>>> 3378c4f (plugin last vertation updated in admin dashboard)
            function validateStep1MinimumCharge() {
                var selection = collectSelectedServicesData();
                var totalServicesAmount = selection.totalServicesAmount;
                
                // Get service charge from ZIP code data (dynamic value)
                var $wizard = $('#xtremecleans-service-selection, .xtremecleans-service-selection');
                var serviceFee = parseFloat($wizard.data('service-fee')) || 0;
                
                // Minimum service charge = Service Fee value (or $199 if service fee is 0)
                var MINIMUM_SERVICE_CHARGE = serviceFee > 0 ? serviceFee : 199.00;
                
                // Check if no services are selected (total is 0)
                if (totalServicesAmount === 0) {
                    showMinimumChargePopup('Please select at least one service to continue. Minimum service charge is $' + MINIMUM_SERVICE_CHARGE.toFixed(2) + ', so please select additional items. Otherwise, any services less than that amount, will be changed to become $' + MINIMUM_SERVICE_CHARGE.toFixed(2) + '.');
                    return false; // Prevent proceeding
                }
<<<<<<< HEAD
                
                // Calculate total with service charge
                var totalWithServiceCharge = totalServicesAmount;
                if (totalServicesAmount > 0 && serviceFee > 0) {
                    totalWithServiceCharge += serviceFee;
                }
                
                // Check if TOTAL (services + service charge) is less than minimum service charge
                if (totalWithServiceCharge > 0 && totalWithServiceCharge < MINIMUM_SERVICE_CHARGE) {
                    showMinimumChargePopup('Minimum service charge is $' + MINIMUM_SERVICE_CHARGE.toFixed(2) + ', so please select additional items. Otherwise, any services less than that amount, will be changed to become $' + MINIMUM_SERVICE_CHARGE.toFixed(2) + '.');
=======

                // IMPORTANT: compare selected services total directly against minimum threshold.
                // Do NOT add serviceFee here; serviceFee is the minimum threshold value.
                if (totalServicesAmount > 0 && totalServicesAmount < MINIMUM_SERVICE_CHARGE) {
                    showMinimumChargePopup(
                        'Your selected services total $' + totalServicesAmount.toFixed(2) +
                        ', which is less than the minimum service charge of $' + MINIMUM_SERVICE_CHARGE.toFixed(2) +
                        '. Please select additional items to continue.'
                    );
>>>>>>> 3378c4f (plugin last vertation updated in admin dashboard)
                    return false; // Prevent proceeding
                }
                
                return true; // Allow proceeding (total meets or exceeds minimum)
            }
            
            function showMinimumChargePopup(message) {
                var $popup = $('#xtremecleans-minimum-charge-popup');
                if ($popup.length === 0) {
                    console.error('Popup element not found');
                    return;
                }
                $popup.find('#xtremecleans-popup-message-text').text(message);
                $popup.css('display', 'flex').fadeIn(300);
                $('body').css('overflow', 'hidden');
            }
            
            function hideMinimumChargePopup() {
                var $popup = $('#xtremecleans-minimum-charge-popup');
                if ($popup.length === 0) {
                    return;
                }
                $popup.fadeOut(300, function() {
                    $(this).css('display', 'none');
                });
                $('body').css('overflow', '');
            }
            
            // Handle popup close button
            $(document).on('click', '#xtremecleans-minimum-charge-popup .xtremecleans-popup-close', function() {
                hideMinimumChargePopup();
            });
            
            // Handle overlay backdrop click
            $(document).on('click', '#xtremecleans-minimum-charge-popup .xtremecleans-popup-overlay-backdrop', function() {
                hideMinimumChargePopup();
            });
            
            // Handle OK button
            $(document).on('click', '#xtremecleans-popup-ok-btn', function() {
                hideMinimumChargePopup();
            });
            
            // Prevent closing when clicking inside modal
            $(document).on('click', '#xtremecleans-minimum-charge-popup .xtremecleans-popup-modal', function(e) {
                e.stopPropagation();
            });
            
            function validateStep3() {
                var isValid = true;
                var $step3 = $wizard.find('[data-step="3"]');
                
                // Clear previous error messages
                $step3.find('.xtremecleans-field-error').remove();
                $step3.find('.xtremecleans-input-error').removeClass('xtremecleans-input-error');
                
                // Required fields
                var requiredFields = [
                    { id: '#xtremecleans-info-first-name', label: 'First Name' },
                    { id: '#xtremecleans-info-last-name', label: 'Last Name' },
                    { id: '#xtremecleans-info-email', label: 'Email Address' },
                    { id: '#xtremecleans-info-phone', label: 'Phone' },
                    { id: '#xtremecleans-info-address1', label: 'Address 1' },
                    { id: '#xtremecleans-info-zip', label: 'Zip Code' },
                    { id: '#xtremecleans-info-city', label: 'City' },
                    { id: '#xtremecleans-info-state', label: 'State' }
                ];
                
                requiredFields.forEach(function(field) {
                    var $field = $(field.id);
                    var value = $field.val().trim();
                    
                    if (!value) {
                        isValid = false;
                        $field.addClass('xtremecleans-input-error');
                        var $label = $field.closest('label');
                        if ($label.length) {
                            $label.append('<span class="xtremecleans-field-error" style="color: #d63638 !important;">' + field.label.toUpperCase() + ' IS REQUIRED</span>');
                        }
                    } else if (field.id === '#xtremecleans-info-email') {
                        // Validate email format
                        var emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
                        if (!emailRegex.test(value)) {
                            isValid = false;
                            $field.addClass('xtremecleans-input-error');
                            var $label = $field.closest('label');
                            if ($label.length) {
                                $label.append('<span class="xtremecleans-field-error" style="color: #d63638 !important;">PLEASE ENTER A VALID EMAIL ADDRESS</span>');
                            }
                        }
                    }
                });
                
                if (!isValid) {
                    // Scroll to first error
                    var $firstError = $step3.find('.xtremecleans-input-error').first();
                    if ($firstError.length) {
                        $modal.animate({
                            scrollTop: $firstError.offset().top - $modal.offset().top + $modal.scrollTop() - 100
                        }, 500);
                        $firstError.focus();
                    }
                }
                
                return isValid;
            }

            $prevBtn.on('click.xtremecleansSteps', function() {
                setStep(currentStep - 1);
            });

            $progressSteps.on('click.xtremecleansSteps', function() {
                var targetStep = $(this).index() + 1;
                setStep(targetStep);
            });

            setStep(currentStep);
            
            // Generate calendar if Step 2 is already active
            if (currentStep === 2) {
                setTimeout(function() {
                    var $calendar = $('#xtremecleans-dynamic-calendar');
                    if ($calendar.length > 0) {
                        console.log('Wizard initialized with Step 2 active, generating calendar...');
                        currentWeekStart = null;
                        generateDynamicCalendar();
                    } else {
                        console.error('Calendar container not found during wizard initialization');
                    }
                    // Update selection summary when Step 2 is active
                    if (typeof updateSelectionSummary === 'function') {
                        updateSelectionSummary();
                    }
                }, 400);
            }

            function showSuccessModal() {
                if (!$successModal.length) {
                    return;
                }
                $successModal.addClass('active').attr('aria-hidden', 'false');
            }

            function redirectHome() {
                var target = (window.xtremecleansData && xtremecleansData.homeUrl)
                    ? xtremecleansData.homeUrl
                    : (window.location.origin ? window.location.origin + '/' : '/');
                window.location.href = target;
            }

            function hideSuccessModal(redirect) {
                if (!$successModal.length) {
                    return;
                }
                $successModal.removeClass('active').attr('aria-hidden', 'true');
                if (redirect) {
                    redirectHome();
                }
            }

            $successClose.on('click.xtremecleansSteps', function() {
                hideSuccessModal(true);
            });
            $successOverlay.on('click.xtremecleansSteps', function() {
                hideSuccessModal(true);
            });

            $wizard.off('xtremecleansStepsComplete').on('xtremecleansStepsComplete', function() {
                // Placeholder for external hooks after successful submission.
            });

            $('.xtremecleans-place-order').off('.xtremecleansSteps').on('click.xtremecleansSteps', function(e) {
                e.preventDefault();
                submitOrder();
            });
        }
        
    });
    
    // Stripe Payment Functions
    var stripe = null;
    var stripeElements = null;
    var cardElement = null;
    var currentOrderId = null;
    var currentPaymentIntentId = null;
    var currentPaymentIntentClientSecret = null;
    
    // Initialize Stripe if enabled
    if (typeof xtremecleansData !== 'undefined' && xtremecleansData.stripeEnabled && typeof Stripe !== 'undefined') {
        stripe = Stripe(xtremecleansData.stripePublishableKey);
        stripeElements = stripe.elements();
    }
    
    function showPaymentModal(orderId, amount) {
        currentOrderId = orderId;
        currentPaymentIntentId = null; // Reset payment intent ID
        currentPaymentIntentClientSecret = null; // Reset client secret
        var $modal = $('#xtremecleans-payment-modal');
        $('#xtremecleans-payment-amount-value').text(amount.toFixed(2));
        $('#xtremecleans-payment-submit').text('Pay $' + amount.toFixed(2)).prop('disabled', true);
        
        $modal.css('display', 'flex').hide().fadeIn(300);
        $('body').css('overflow', 'hidden');
        
        // Check if Stripe is available
        if (typeof xtremecleansData === 'undefined' || !xtremecleansData.stripeEnabled) {
            $('#xtremecleans-stripe-card-errors').text('Stripe payment is not enabled. Please contact support.');
            return;
        }
        
        if (typeof Stripe === 'undefined') {
            $('#xtremecleans-stripe-card-errors').text('Stripe.js is not loaded. Please refresh the page and try again.');
            return;
        }
        
        // Initialize Stripe if not already done
        if (!stripe) {
            stripe = Stripe(xtremecleansData.stripePublishableKey);
            stripeElements = stripe.elements();
        }
        
        // Initialize Stripe Elements if not already done
        if (stripe && !cardElement) {
            var style = {
                base: {
                    color: '#32325d',
                    fontFamily: '"Helvetica Neue", Helvetica, sans-serif',
                    fontSmoothing: 'antialiased',
                    fontSize: '16px',
                    '::placeholder': {
                        color: '#aab7c4'
                    }
                },
                invalid: {
                    color: '#fa755a',
                    iconColor: '#fa755a'
                }
            };
            
            cardElement = stripeElements.create('card', { style: style });
            cardElement.mount('#xtremecleans-stripe-card-element');
            
            // Handle real-time validation errors
            cardElement.on('change', function(event) {
                var displayError = document.getElementById('xtremecleans-stripe-card-errors');
                if (event.error) {
                    displayError.textContent = event.error.message;
                } else {
                    displayError.textContent = '';
                }
            });
        } else if (cardElement) {
            // Clear previous card data
            cardElement.clear();
        }
        
        // Clear any previous errors
        $('#xtremecleans-stripe-card-errors').text('');
        
        // Create payment intent
        createPaymentIntent(orderId, amount);
    }
    
    function hidePaymentModal() {
        var $modal = $('#xtremecleans-payment-modal');
        $modal.fadeOut(300, function() {
            $(this).css('display', 'none');
        });
        $('body').css('overflow', '');
        
        // Reset form
        if (cardElement) {
            cardElement.clear();
        }
        $('#xtremecleans-stripe-card-errors').text('');
        $('#xtremecleans-payment-loading').hide();
        $('#xtremecleans-payment-submit').prop('disabled', false);
    }
    
    function createPaymentIntent(orderId, amount) {
        $('#xtremecleans-payment-loading').show();
        $('#xtremecleans-payment-submit').prop('disabled', true);
        
        $.ajax({
            url: xtremecleansData.ajaxUrl,
            type: 'POST',
            data: {
                action: 'xtremecleans_create_payment_intent',
                nonce: xtremecleansData.placeOrderNonce,
                order_id: orderId,
                amount: amount
            },
            success: function(response) {
                $('#xtremecleans-payment-loading').hide();
                if (response && response.success) {
                    currentPaymentIntentId = response.data.payment_intent_id;
                    // CRITICAL: Store client_secret, not just payment_intent_id
                    // Stripe.confirmCardPayment requires client_secret, not payment_intent_id
                    currentPaymentIntentClientSecret = response.data.client_secret || response.data.payment_intent_id;
                    
                    // Payment intent created, ready for payment
                    $('#xtremecleans-payment-submit').prop('disabled', false);
                    console.log('Payment intent created:', currentPaymentIntentId);
                    console.log('Client secret:', currentPaymentIntentClientSecret ? 'Set' : 'Missing');
                } else {
                    var errorMessage = response.data && response.data.message ? response.data.message : 'Failed to initialize payment. Please try again.';
                    $('#xtremecleans-stripe-card-errors').text(errorMessage);
                    $('#xtremecleans-payment-submit').prop('disabled', true);
                    console.error('Payment intent creation failed:', response);
                }
            },
            error: function(xhr, status, error) {
                $('#xtremecleans-payment-loading').hide();
                var errorMessage = 'Failed to initialize payment. Please try again.';
                if (xhr.responseJSON && xhr.responseJSON.data && xhr.responseJSON.data.message) {
                    errorMessage = xhr.responseJSON.data.message;
                }
                $('#xtremecleans-stripe-card-errors').text(errorMessage);
                $('#xtremecleans-payment-submit').prop('disabled', true);
                console.error('Payment intent AJAX error:', status, error, xhr);
            }
        });
    }
    
    function processPayment() {
        // Check all required components
        var missing = [];
        if (!stripe) missing.push('Stripe');
        if (!cardElement) missing.push('Card Element');
        if (!currentOrderId) missing.push('Order ID');
        if (!currentPaymentIntentId) missing.push('Payment Intent');
        
        if (missing.length > 0) {
            console.error('Payment system not ready. Missing:', missing.join(', '));
            var errorMsg = 'Payment system not ready. Missing: ' + missing.join(', ') + '. Please wait a moment and try again, or refresh the page.';
            $('#xtremecleans-stripe-card-errors').text(errorMsg);
            alert(errorMsg);
            return;
        }
        
        $('#xtremecleans-payment-submit').prop('disabled', true);
        $('#xtremecleans-payment-loading').show();
        $('#xtremecleans-stripe-card-errors').text('');
        
        // Use client_secret for confirmCardPayment, not payment_intent_id
        var clientSecret = currentPaymentIntentClientSecret || currentPaymentIntentId;
        
        if (!clientSecret) {
            console.error('Missing client secret for payment confirmation');
            $('#xtremecleans-stripe-card-errors').text('Payment initialization error. Please refresh and try again.');
            $('#xtremecleans-payment-submit').prop('disabled', false);
            $('#xtremecleans-payment-loading').hide();
            return;
        }
        
        console.log('Confirming payment with client secret:', clientSecret.substring(0, 20) + '...');
        
        stripe.confirmCardPayment(clientSecret, {
            payment_method: {
                card: cardElement
            }
        }).then(function(result) {
            if (result.error) {
                // Show error to customer
                $('#xtremecleans-stripe-card-errors').text(result.error.message);
                $('#xtremecleans-payment-submit').prop('disabled', false);
                $('#xtremecleans-payment-loading').hide();
                console.error('Stripe payment error:', result.error);
            } else {
                // Payment succeeded, confirm with server
                console.log('Stripe payment succeeded, confirming with server...');
                console.log('Order ID:', currentOrderId, 'Payment Intent ID:', currentPaymentIntentId);
                // Ensure loading is visible before confirming
                $('#xtremecleans-payment-loading').show();
                confirmPayment(currentOrderId, currentPaymentIntentId);
            }
        }).catch(function(error) {
            // Handle unexpected errors
            console.error('Stripe payment confirmation error:', error);
            $('#xtremecleans-stripe-card-errors').text('An unexpected error occurred. Please try again.');
            $('#xtremecleans-payment-submit').prop('disabled', false);
            $('#xtremecleans-payment-loading').hide();
        });
    }
    
    function confirmPayment(orderId, paymentIntentId) {
        // Validate required data
        if (!orderId || !paymentIntentId) {
            console.error('Missing required payment data:', { orderId: orderId, paymentIntentId: paymentIntentId });
            $('#xtremecleans-payment-loading').hide();
            $('#xtremecleans-stripe-card-errors').text('Missing payment information. Please try again.');
            $('#xtremecleans-payment-submit').prop('disabled', false);
            return;
        }
        
        // Check if xtremecleansData is available
        if (typeof xtremecleansData === 'undefined') {
            console.error('xtremecleansData is not defined!');
            $('#xtremecleans-payment-loading').hide();
            $('#xtremecleans-stripe-card-errors').text('Payment system error. Please refresh the page and try again.');
            $('#xtremecleans-payment-submit').prop('disabled', false);
            return;
        }
        
        var ajaxUrl = xtremecleansData.ajaxUrl || '/wp-admin/admin-ajax.php';
        var nonce = xtremecleansData.placeOrderNonce || xtremecleansData.nonce || '';
        
        console.log('Confirming payment:', {
            orderId: orderId,
            paymentIntentId: paymentIntentId,
            ajaxUrl: ajaxUrl,
            hasNonce: !!nonce
        });
        
        // Set timeout to prevent infinite loading
        var timeoutId = setTimeout(function() {
            $('#xtremecleans-payment-loading').hide();
            $('#xtremecleans-payment-submit').prop('disabled', false);
            $('#xtremecleans-stripe-card-errors').text('Payment confirmation is taking longer than expected. Please check your order status or contact support.');
            console.error('Payment confirmation timeout after 30 seconds');
        }, 30000); // 30 second timeout
        
        $.ajax({
            url: ajaxUrl,
            type: 'POST',
            timeout: 25000, // 25 second AJAX timeout
            data: {
                action: 'xtremecleans_confirm_payment',
                nonce: nonce,
                order_id: orderId,
                payment_intent_id: paymentIntentId
            },
            success: function(response) {
                clearTimeout(timeoutId); // Clear timeout on success
                $('#xtremecleans-payment-loading').hide();
                console.log('Payment confirmation response:', response);
                
                if (response && response.success) {
                    // Payment confirmed, show success
                    console.log('Payment confirmed successfully! Order ID:', response.data && response.data.order_id);
                    console.log('Jobber sync status:', response.data && response.data.jobber_sent ? 'Sent' : 'Not sent');
                    
                    hidePaymentModal();
                    var message = response.data && response.data.message ? response.data.message : 'Payment confirmed! Your appointment has been scheduled.';
                    
                    // Show success message
                    alert(message);
                    
                    // Show success modal
                    if (typeof showSuccessModal === 'function') {
                        showSuccessModal();
                    }
                    
                    // Clear session storage
                    try {
                        sessionStorage.removeItem('xtremecleans_selected_slot');
                        sessionStorage.removeItem('xtremecleans_order_data');
                    } catch (err) {
                        console.error('Error clearing session storage:', err);
                    }
                } else {
                    var errorMessage = response.data && response.data.message ? response.data.message : 'Failed to confirm payment. Please contact support.';
                    $('#xtremecleans-stripe-card-errors').text(errorMessage);
                    $('#xtremecleans-payment-submit').prop('disabled', false);
                    console.error('Payment confirmation error:', response);
                    alert('Payment Error: ' + errorMessage);
                }
            },
            error: function(xhr, status, error) {
                clearTimeout(timeoutId); // Clear timeout on error
                $('#xtremecleans-payment-loading').hide();
                $('#xtremecleans-payment-submit').prop('disabled', false);
                
                var errorMessage = 'Failed to confirm payment. Please contact support.';
                
                console.error('Payment confirmation AJAX error:', {
                    status: status,
                    error: error,
                    xhrStatus: xhr.status,
                    responseText: xhr.responseText,
                    responseJSON: xhr.responseJSON
                });
                
                if (status === 'timeout') {
                    errorMessage = 'Payment confirmation timed out. Please check your order status or contact support.';
                } else if (xhr.status === 0) {
                    errorMessage = 'Network error. Please check your internet connection and try again.';
                } else if (xhr.status === 403) {
                    errorMessage = 'Security check failed. Please refresh the page and try again.';
                } else if (xhr.status === 500) {
                    errorMessage = 'Server error occurred. Please contact support.';
                } else if (xhr.responseJSON && xhr.responseJSON.data && xhr.responseJSON.data.message) {
                    errorMessage = xhr.responseJSON.data.message;
                } else if (xhr.responseJSON && xhr.responseJSON.message) {
                    errorMessage = xhr.responseJSON.message;
                } else if (xhr.responseText) {
                    try {
                        var errorData = JSON.parse(xhr.responseText);
                        if (errorData.message) {
                            errorMessage = errorData.message;
                        } else if (errorData.data && errorData.data.message) {
                            errorMessage = errorData.data.message;
                        }
                    } catch (e) {
                        console.error('Could not parse error response:', e);
                    }
                }
                
                $('#xtremecleans-stripe-card-errors').text(errorMessage);
                alert('Payment Error: ' + errorMessage);
            },
            complete: function() {
                // Always clear timeout and hide loading when request completes
                clearTimeout(timeoutId);
                $('#xtremecleans-payment-loading').hide();
            }
        });
    }
    
    // Payment modal event handlers
    $(document).ready(function() {
        $('#xtremecleans-payment-close, #xtremecleans-payment-cancel').on('click', function() {
            hidePaymentModal();
        });
        
        $('#xtremecleans-payment-modal .xtremecleans-popup-overlay-backdrop').on('click', function() {
            hidePaymentModal();
        });
        
        $('#xtremecleans-payment-submit').on('click', function() {
            processPayment();
        });
    });

})(jQuery);

