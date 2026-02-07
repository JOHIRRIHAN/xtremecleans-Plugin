/**
 * XtremeCleans Admin JavaScript
 */

(function($) {
    'use strict';

    $(document).ready(function() {
        
        // Copy shortcode functionality
        $('.copy-shortcode').on('click', function(e) {
            e.preventDefault();
            var shortcode = $(this).data('shortcode');
            var $temp = $('<input>');
            $('body').append($temp);
            $temp.val(shortcode).select();
            document.execCommand('copy');
            $temp.remove();
            
            // Show feedback
            var $button = $(this);
            var originalText = $button.text();
            $button.text('Copied!').addClass('button-primary');
            
            setTimeout(function() {
                $button.text(originalText).removeClass('button-primary');
            }, 2000);
        });
        
        // Copy URL functionality for Webhook and OAuth Callback URLs
        $(document).on('click', '.xtremecleans-copy-btn', function(e) {
            e.preventDefault();
            e.stopPropagation();
            
            var $button = $(this);
            var targetId = $button.data('copy-target');
            var $input = $('#' + targetId);
            
            if (!$input.length) {
                console.error('Input field not found:', targetId);
                return;
            }
            
            var url = $input.val();
            
            if (!url) {
                console.error('URL is empty');
                return;
            }
            
            // Store original button HTML
            var originalHtml = $button.html();
            
            // Fallback copy function
            var fallbackCopy = function(text) {
                try {
                    var $temp = $('<input>');
                    $temp.css({
                        position: 'fixed',
                        left: '-9999px',
                        top: '0'
                    });
                    $('body').append($temp);
                    $temp.val(text).select();
                    var success = document.execCommand('copy');
                    $temp.remove();
                    
                    if (success) {
                        $button.html('<span class="dashicons dashicons-yes-alt"></span> Copied!').addClass('button-primary');
                        setTimeout(function() {
                            $button.html(originalHtml).removeClass('button-primary');
                        }, 2000);
                    } else {
                        alert('Failed to copy. Please select and copy manually.');
                    }
                } catch (err) {
                    console.error('Copy failed:', err);
                    alert('Failed to copy. Please select and copy manually.');
                }
            };
            
            // Use modern Clipboard API if available
            if (navigator.clipboard && navigator.clipboard.writeText) {
                navigator.clipboard.writeText(url).then(function() {
                    // Show feedback
                    $button.html('<span class="dashicons dashicons-yes-alt"></span> Copied!').addClass('button-primary');
                    
                    setTimeout(function() {
                        $button.html(originalHtml).removeClass('button-primary');
                    }, 2000);
                }).catch(function(err) {
                    console.error('Clipboard API failed:', err);
                    // Fallback to old method
                    fallbackCopy(url);
                });
            } else {
                // Fallback for older browsers
                fallbackCopy(url);
            }
        });
        
        // Show/hide request body field based on method
        $('#test_method').on('change', function() {
            var method = $(this).val();
            if (method === 'POST' || method === 'PUT' || method === 'PATCH') {
                $('#test_body_row').show();
            } else {
                $('#test_body_row').hide();
            }
        });
        
        // Trigger on page load
        $('#test_method').trigger('change');
        
        // Format JSON in request body
        $('#test_body').on('blur', function() {
            var value = $(this).val().trim();
            if (value) {
                try {
                    var obj = JSON.parse(value);
                    $(this).val(JSON.stringify(obj, null, 2));
                } catch (e) {
                    // Not valid JSON, leave as is
                }
            }
        });
        
        // Initialize charts on orders page (wait for Chart.js to load)
        if (typeof xtremecleansChartData !== 'undefined') {
            function initChartsWhenReady() {
                if (typeof Chart !== 'undefined') {
                    initOrdersCharts();
                } else {
                    setTimeout(initChartsWhenReady, 100);
                }
            }
            initChartsWhenReady();
        }
        
        // Orders page functionality
        if ($('.xtremecleans-orders-page').length) {
            initOrdersPage();
        }
        
        // Stripe Payment Settings: Update requirement message based on test mode
        function updateStripeRequirementMessage() {
            var $stripeEnabled = $('#xtremecleans_stripe_enabled');
            var $testMode = $('input[name="xtremecleans_stripe_test_mode"]');
            var $requirementMsg = $stripeEnabled.closest('tr').find('.description').last();
            
            if ($stripeEnabled.length && $testMode.length) {
                var isTestMode = $testMode.is(':checked');
                var isEnabled = $stripeEnabled.is(':checked');
                
                // Update requirement message if Stripe is not enabled
                if (!isEnabled && $requirementMsg.length && $requirementMsg.text().indexOf('⚠️') !== -1) {
                    var keyType = isTestMode ? 'Test' : 'Live';
                    var newText = '⚠️ <strong>Requirement:</strong> You must enter Stripe ' + keyType + 
                                 ' API keys (Publishable Key and Secret Key) before enabling Stripe payments.';
                    $requirementMsg.html(newText);
                }
            }
        }
        
        // Update requirement message when test mode changes
        $('input[name="xtremecleans_stripe_test_mode"]').on('change', function() {
            updateStripeRequirementMessage();
        });
        
        // Update requirement message when Stripe enabled changes
        $('#xtremecleans_stripe_enabled').on('change', function() {
            updateStripeRequirementMessage();
        });
        
        // Initialize on page load
        updateStripeRequirementMessage();
        
        // Clear Jobber Token & Re-authorize
        // Clear Token & Re-authorize button
        $('#xtremecleans-clear-token-btn').on('click', function() {
            var $button = $(this);
            var $result = $('#xtremecleans-clear-token-result');
            
            if (!confirm('Are you sure you want to clear the current Jobber token? You will need to re-authorize the connection.')) {
                return;
            }
            
            $button.prop('disabled', true).html('<span class="spinner is-active" style="float: none; margin: 0 5px 0 0;"></span> Clearing...');
            $result.hide().html('');
            
            $.ajax({
                url: ajaxurl,
                type: 'POST',
                data: {
                    action: 'xtremecleans_clear_jobber_token',
                    nonce: (typeof xtremecleansAdminData !== 'undefined' && xtremecleansAdminData.nonce) ? xtremecleansAdminData.nonce : ''
                },
                success: function(response) {
                    $button.prop('disabled', false).html('<span class="dashicons dashicons-update" style="margin-top: 4px;"></span> Clear Token & Re-authorize');
                    
                    if (response.success) {
                        var html = '<div style="background: #d1ecf1; border: 1px solid #17a2b8; padding: 15px; border-radius: 4px; color: #0c5460;">';
                        html += '<p><strong>✓ Token cleared successfully!</strong></p>';
                        html += '<p>Please click the button below to re-authorize with Jobber:</p>';
                        if (response.data && response.data.auth_url) {
                            html += '<p><a href="' + response.data.auth_url + '" class="button button-primary" target="_blank" style="margin-top: 10px;">';
                            html += '<span class="dashicons dashicons-admin-links" style="margin-top: 4px;"></span> Authorize Jobber Connection Now';
                            html += '</a></p>';
                            html += '<p style="font-size: 12px; margin-top: 10px; color: #666;">After authorization, you will be redirected back and the page will refresh automatically.</p>';
                        }
                        html += '</div>';
                        $result.html(html).show();
                        
                        // Reload page after 3 seconds to show authorization button
                        setTimeout(function() {
                            location.reload();
                        }, 3000);
                    } else {
                        var errorMsg = response.data?.message || 'Failed to clear token';
                        $result.html('<div style="background: #f8d7da; border: 1px solid #f5c6cb; padding: 15px; border-radius: 4px; color: #721c24;"><strong>Error:</strong> ' + errorMsg + '</div>').show();
                    }
                },
                error: function() {
                    $button.prop('disabled', false).html('<span class="dashicons dashicons-update" style="margin-top: 4px;"></span> Clear Token & Re-authorize');
                    $result.html('<div style="background: #f8d7da; border: 1px solid #f5c6cb; padding: 15px; border-radius: 4px; color: #721c24;"><strong>Error:</strong> Failed to clear token. Please try again.</div>').show();
                }
            });
        });
        
        // Fix Scopes Manually button
        $('#xtremecleans-fix-scopes-btn').on('click', function() {
            var $button = $(this);
            var $result = $('#xtremecleans-clear-token-result');
            
            if (!confirm('This will manually set token scopes to "jobs contacts". Make sure you already authorized with all permissions. Continue?')) {
                return;
            }
            
            $button.prop('disabled', true).html('<span class="spinner is-active" style="float: none; margin: 0 5px 0 0;"></span> Fixing...');
            $result.hide().html('');
            
            $.ajax({
                url: ajaxurl,
                type: 'POST',
                data: {
                    action: 'xtremecleans_fix_jobber_scopes',
                    nonce: (typeof xtremecleansAdminData !== 'undefined' && xtremecleansAdminData.nonce) ? xtremecleansAdminData.nonce : ''
                },
                success: function(response) {
                    $button.prop('disabled', false).html('<span class="dashicons dashicons-admin-tools" style="margin-top: 4px;"></span> Fix Scopes Manually');
                    
                    if (response.success) {
                        var html = '<div style="background: #d4edda; border: 1px solid #28a745; padding: 15px; border-radius: 4px; color: #155724;">';
                        html += '<p><strong>✓ Scopes fixed successfully!</strong></p>';
                        html += '<p>' + (response.data.message || 'Token scopes set to: jobs contacts') + '</p>';
                        html += '<p style="margin-top: 10px;"><button type="button" id="xtremecleans-test-after-fix" class="button button-primary">Test Connection Now</button></p>';
                        html += '</div>';
                        $result.html(html).show();
                        
                        // Test connection after fix
                        $('#xtremecleans-test-after-fix').on('click', function() {
                            $('#xtremecleans-test-jobber-btn').trigger('click');
                        });
                    } else {
                        var html = '<div style="background: #f8d7da; border: 1px solid #dc3545; padding: 15px; border-radius: 4px; color: #721c24;">';
                        html += '<p><strong>✗ Error:</strong> ' + (response.data.message || 'Failed to fix scopes') + '</p>';
                        html += '</div>';
                        $result.html(html).show();
                    }
                },
                error: function(xhr, status, error) {
                    $button.prop('disabled', false).html('<span class="dashicons dashicons-admin-tools" style="margin-top: 4px;"></span> Fix Scopes Manually');
                    var html = '<div style="background: #f8d7da; border: 1px solid #dc3545; padding: 15px; border-radius: 4px; color: #721c24;">';
                    html += '<p><strong>✗ Error:</strong> ' + error + '</p>';
                    html += '</div>';
                    $result.html(html).show();
                }
            });
        });
        
        // Sync Services from Jobber
        $('#xtremecleans-sync-services-btn').on('click', function() {
            var $button = $(this);
            var $result = $('#xtremecleans-sync-services-result');
            
            $button.prop('disabled', true).html('<span class="spinner is-active" style="float: none; margin: 0 5px 0 0;"></span> Syncing...');
            $result.hide().html('');
            
            $.ajax({
                url: ajaxurl,
                type: 'POST',
                data: {
                    action: 'xtremecleans_sync_services_from_jobber',
                    nonce: (typeof xtremecleansAdminData !== 'undefined' && xtremecleansAdminData.nonce) ? xtremecleansAdminData.nonce : ''
                },
                success: function(response) {
                    $button.prop('disabled', false).html('<span class="dashicons dashicons-update" style="margin-top: 4px;"></span> Sync Services from Jobber');
                    
                    if (response.success) {
                        $result.html('<div class="notice notice-success inline" style="margin: 0;"><p><strong>✓ Success:</strong> ' + response.data.message + '</p></div>').show();
                    } else {
                        $result.html('<div class="notice notice-error inline" style="margin: 0;"><p><strong>✗ Error:</strong> ' + (response.data && response.data.message ? response.data.message : 'Failed to sync services.') + '</p></div>').show();
                    }
                },
                error: function(xhr, status, error) {
                    $button.prop('disabled', false).html('<span class="dashicons dashicons-update" style="margin-top: 4px;"></span> Sync Services from Jobber');
                    $result.html('<div class="notice notice-error inline" style="margin: 0;"><p><strong>✗ Error:</strong> An error occurred while syncing services. Please try again.</p></div>').show();
                    console.error('Sync services error:', error);
                }
            });
        });
        
        // Toggle show only Jobber services
        $('#xtremecleans-show-only-jobber').on('change', function() {
            var enabled = $(this).is(':checked');
            $.ajax({
                url: ajaxurl,
                type: 'POST',
                data: {
                    action: 'xtremecleans_toggle_jobber_services_only',
                    enabled: enabled ? 1 : 0,
                    nonce: (typeof xtremecleansAdminData !== 'undefined' && xtremecleansAdminData.nonce) ? xtremecleansAdminData.nonce : ''
                },
                success: function(response) {
                    if (response.success) {
                        // Show success message
                        var $checkbox = $('#xtremecleans-show-only-jobber');
                        var $msg = $('<div class="notice notice-success inline" style="margin: 10px 0 0 0; padding: 5px 10px;"><p style="margin: 0;">' + response.data.message + '</p></div>');
                        $checkbox.closest('div').after($msg);
                        setTimeout(function() {
                            $msg.fadeOut(function() { $(this).remove(); });
                        }, 3000);
                    }
                }
            });
        });
        
        // Toggle fetch from Jobber
        $('#xtremecleans-fetch-from-jobber').on('change', function() {
            var enabled = $(this).is(':checked');
            $.ajax({
                url: ajaxurl,
                type: 'POST',
                data: {
                    action: 'xtremecleans_toggle_fetch_from_jobber',
                    enabled: enabled ? 1 : 0,
                    nonce: (typeof xtremecleansAdminData !== 'undefined' && xtremecleansAdminData.nonce) ? xtremecleansAdminData.nonce : ''
                },
                success: function(response) {
                    if (response.success) {
                        // Show success message
                        var $checkbox = $('#xtremecleans-fetch-from-jobber');
                        var $msg = $('<div class="notice notice-success inline" style="margin: 10px 0 0 0; padding: 5px 10px;"><p style="margin: 0;">' + response.data.message + '</p></div>');
                        $checkbox.closest('div').after($msg);
                        setTimeout(function() {
                            $msg.fadeOut(function() { $(this).remove(); });
                        }, 3000);
                    }
                }
            });
        });
        
        // Toggle ZIP-based Jobber services
        $('#xtremecleans-zip-based-jobber').on('change', function() {
            var enabled = $(this).is(':checked');
            $.ajax({
                url: ajaxurl,
                type: 'POST',
                data: {
                    action: 'xtremecleans_toggle_zip_based_jobber',
                    enabled: enabled ? 1 : 0,
                    nonce: (typeof xtremecleansAdminData !== 'undefined' && xtremecleansAdminData.nonce) ? xtremecleansAdminData.nonce : ''
                },
                success: function(response) {
                    if (response.success) {
                        // Show success message
                        var $checkbox = $('#xtremecleans-zip-based-jobber');
                        var $msg = $('<div class="notice notice-success inline" style="margin: 10px 0 0 0; padding: 5px 10px;"><p style="margin: 0;">' + response.data.message + '</p></div>');
                        $checkbox.closest('div').after($msg);
                        setTimeout(function() {
                            $msg.fadeOut(function() { $(this).remove(); });
                        }, 3000);
                    }
                }
            });
        });
        
        // Jobber Connection Test
        $('#xtremecleans-test-jobber-btn').on('click', function() {
            var $button = $(this);
            var $result = $('#xtremecleans-test-result');
            
            $button.prop('disabled', true).html('<span class="spinner is-active" style="float: none; margin: 0 5px 0 0;"></span> Testing...');
            $result.hide().html('');
            
            $.ajax({
                url: ajaxurl,
                type: 'POST',
                data: {
                    action: 'xtremecleans_test_jobber_connection',
                    nonce: (typeof xtremecleansAdminData !== 'undefined' && xtremecleansAdminData.nonce) ? xtremecleansAdminData.nonce : ''
                },
                success: function(response) {
                    $button.prop('disabled', false).html('<span class="dashicons dashicons-admin-tools" style="margin-top: 4px;"></span> Test Jobber Connection');
                    
                    if (response.success && response.data) {
                        var results = response.data.results || {};
                        var html = '<div style="background: #fff; border: 1px solid #ddd; padding: 15px; border-radius: 4px;">';
                        html += '<h4 style="margin-top: 0;">' + (response.data.results.summary || 'Test Results') + '</h4>';
                        
                        if (results.connection) {
                            html += '<p><strong>Connection:</strong> ' + results.connection.message + '</p>';
                        }
                        if (results.client_test) {
                            html += '<p><strong>Client Test:</strong> ' + results.client_test.message + '</p>';
                        }
                        
                        html += '</div>';
                        $result.html(html).show();
                    } else {
                        var errorMsg = response.data?.message || 'Test failed';
                        var missing = response.data?.missing || [];
                        var html = '<div style="background: #fff3cd; border: 1px solid #ffc107; padding: 15px; border-radius: 4px; color: #856404;">';
                        html += '<h4 style="margin-top: 0; color: #856404;">❌ ' + errorMsg + '</h4>';
                        if (missing.length > 0) {
                            html += '<p><strong>Missing:</strong> ' + missing.join(', ') + '</p>';
                        }
                        if (response.data?.results) {
                            var results = response.data.results;
                            if (results.connection) {
                                html += '<p>' + results.connection.message + '</p>';
                            }
                        }
                        html += '</div>';
                        $result.html(html).show();
                    }
                },
                error: function() {
                    $button.prop('disabled', false).html('<span class="dashicons dashicons-admin-tools" style="margin-top: 4px;"></span> Test Jobber Connection');
                    $result.html('<div style="background: #f8d7da; border: 1px solid #f5c6cb; padding: 15px; border-radius: 4px; color: #721c24;"><strong>Error:</strong> Failed to test connection. Please try again.</div>').show();
                }
            });
        });
        
    });
    
    /**
     * Initialize Orders Page
     */
    function initOrdersPage() {
        // Order details modal
        $(document).on('click', '.view-order-details', function() {
            var orderId = $(this).data('order-id');
            showOrderDetails(orderId);
        });
        
        // Delete order
        $(document).on('click', '.delete-order', function(e) {
            e.preventDefault();
            var orderId = $(this).data('order-id');
            var $button = $(this);
            
            if (!confirm('Are you sure you want to delete this order? This action cannot be undone.')) {
                return;
            }
            
            $button.prop('disabled', true).html('<span class="spinner is-active" style="float: none; margin: 0;"></span>');
            
            $.ajax({
                url: xtremecleansOrdersData.ajaxUrl,
                type: 'POST',
                data: {
                    action: 'xtremecleans_delete_order',
                    order_id: orderId,
                    nonce: xtremecleansOrdersData.nonce
                },
                success: function(response) {
                    if (response.success) {
                        $button.closest('tr').fadeOut(300, function() {
                            $(this).remove();
                            updateOrdersCount();
                            if ($('#orders-tbody tr').length === 0) {
                                location.reload();
                            }
                        });
                    } else {
                        alert(response.data.message || 'Failed to delete order.');
                        $button.prop('disabled', false).html('<span class="dashicons dashicons-trash"></span>');
                    }
                },
                error: function() {
                    alert('An error occurred. Please try again.');
                    $button.prop('disabled', false).html('<span class="dashicons dashicons-trash"></span>');
                }
            });
        });
        
        // Search orders
        $('#orders-search').on('keyup', function() {
            filterOrders();
        });
        
        // Filter by state
        $('#orders-filter-state').on('change', function() {
            filterOrders();
        });
        
        // Sort orders
        $('#orders-sort').on('change', function() {
            sortOrders();
        });
        
        // Refresh orders
        $('#refresh-orders').on('click', function() {
            location.reload();
        });
        
        // Toggle Jobber documentation
        $('.xtremecleans-toggle-docs').on('click', function() {
            var $button = $(this);
            var $content = $('.xtremecleans-docs-content');
            var $icon = $button.find('.dashicons');
            
            $content.slideToggle(300, function() {
                if ($content.is(':visible')) {
                    $icon.removeClass('dashicons-arrow-up-alt2').addClass('dashicons-arrow-down-alt2');
                } else {
                    $icon.removeClass('dashicons-arrow-down-alt2').addClass('dashicons-arrow-up-alt2');
                }
            });
        });
        
        // Test Jobber connection
        $('#test-jobber-connection').on('click', function() {
            var $button = $(this);
            var originalText = $button.html();
            $button.prop('disabled', true).html('<span class="spinner is-active" style="float: none; margin: 0;"></span> Testing...');
            
            $.ajax({
                url: xtremecleansOrdersData.ajaxUrl,
                type: 'POST',
                data: {
                    action: 'xtremecleans_test_jobber_connection',
                    nonce: xtremecleansOrdersData.nonce
                },
                success: function(response) {
                    if (response.success) {
                        var result = response.data.result;
                        var summary = response.data.summary;
                        var details = result.details.join('<br>');
                        var authUrl = response.data.auth_url;
                        
                        var message = '<div style="padding: 15px;">';
                        message += '<h3 style="margin-top: 0;">Jobber Connection Test</h3>';
                        message += '<p><strong>Status:</strong> ' + summary + '</p>';
                        message += '<div style="background: #f6f7f7; padding: 10px; border-radius: 4px; margin: 10px 0;">';
                        message += '<strong>Details:</strong><br>' + details;
                        message += '</div>';
                        
                        if (authUrl) {
                            message += '<p><a href="' + authUrl + '" target="_blank" class="button button-primary">Authorize Jobber Access</a></p>';
                        }
                        
                        message += '</div>';
                        
                        // Show in modal
                        $('#xtremecleans-order-details').html(message);
                        $('#xtremecleans-order-modal').show();
                    } else {
                        alert('Test failed: ' + (response.data.message || 'Unknown error'));
                    }
                },
                error: function() {
                    alert('An error occurred while testing Jobber connection.');
                },
                complete: function() {
                    $button.prop('disabled', false).html(originalText);
                }
            });
        });
        
        // Resend order to Jobber (manual sync)
        // Handle both old class name (.resend-to-jobber) and new class name (.sync-to-jobber) for backward compatibility
        $(document).on('click', '.resend-to-jobber, .sync-to-jobber', function() {
            var orderId = $(this).data('order-id');
            var $button = $(this);
            var $row = $button.closest('tr');
            var $statusCell = $row.find('.col-jobber');
            
            console.log('[Push to Jobber] Button clicked for order ID:', orderId);
            console.log('[Push to Jobber] xtremecleansOrdersData:', xtremecleansOrdersData);
            
            if (!confirm('Push this order (Quote & Job) to Jobber CRM?\n\nThis will create/update:\n- Client\n- Quote\n- Job')) {
                console.log('[Push to Jobber] User cancelled');
                return;
            }
            
            var originalHtml = $button.html();
            var buttonText = $button.find('.button-text').text() || $button.text().trim() || 'Push to Jobber';
            $button.prop('disabled', true).html('<span class="spinner is-active" style="float: none; margin: 0 5px 0 0;"></span> Pushing...');
            $statusCell.html('<span class="jobber-status pending"><span class="spinner is-active" style="float: none; margin: 0 5px 0 0;"></span> Syncing...</span>');
            
            console.log('[Push to Jobber] Sending AJAX request...');
            
            $.ajax({
                url: xtremecleansOrdersData.ajaxUrl,
                type: 'POST',
                data: {
                    action: 'xtremecleans_sync_order_to_jobber',
                    order_id: orderId,
                    nonce: xtremecleansOrdersData.nonce
                },
                success: function(response) {
                    console.log('[Push to Jobber] AJAX Success:', response);
                    if (response.success) {
                        var jobberResult = response.data.jobber_result;
                        var jobId = '';
                        var jobNumber = '';
                        var quoteId = '';
                        var quoteNumber = '';
                        
                        // Extract job information
                        if (jobberResult && jobberResult.results && jobberResult.results.job && jobberResult.results.job.response) {
                            jobId = jobberResult.results.job.response.id || '';
                            jobNumber = jobberResult.results.job.response.jobNumber || '';
                        }
                        
                        // Extract quote information
                        if (jobberResult && jobberResult.results && jobberResult.results.quote && jobberResult.results.quote.response) {
                            quoteId = jobberResult.results.quote.response.id || '';
                            quoteNumber = jobberResult.results.quote.response.quoteNumber || '';
                        }
                        
                        var statusHtml = '<span class="jobber-status success" title="' + (response.data.message || 'Synced successfully') + '">';
                        statusHtml += '<span class="dashicons dashicons-yes-alt" style="color: #46b450;"></span> Synced';
                        
                        var details = [];
                        if (jobNumber) {
                            details.push('Job #' + jobNumber);
                        }
                        if (quoteNumber) {
                            details.push('Quote #' + quoteNumber);
                        }
                        if (details.length > 0) {
                            statusHtml += '<br><small style="color: #666;">' + details.join(' | ') + '</small>';
                        } else if (jobId) {
                            statusHtml += '<br><small style="color: #666;">Job ID: ' + jobId.substring(0, 12) + '...</small>';
                        }
                        statusHtml += '</span>';
                        $statusCell.html(statusHtml);
                        
                        var successMsg = 'Order successfully pushed to Jobber CRM!\n\n';
                        if (quoteNumber) {
                            successMsg += '✓ Quote #' + quoteNumber + ' created\n';
                        }
                        if (jobNumber) {
                            successMsg += '✓ Job #' + jobNumber + ' created\n';
                        }
                        successMsg += '\nYou can now view them in Jobber dashboard.';
                        
                        alert(successMsg);
                    } else {
                        var errorMsg = response.data.message || 'Failed to sync order to Jobber.';
                        
                        var statusHtml = '<span class="jobber-status failed" title="' + errorMsg + '">';
                        statusHtml += '<span class="dashicons dashicons-warning" style="color: #dc3232;"></span> Failed';
                        statusHtml += '</span>';
                        $statusCell.html(statusHtml);
                        
                        if (response.data.jobber_result && response.data.jobber_result.auth_url) {
                            errorMsg += '\n\nClick OK to authorize Jobber access.';
                            if (confirm(errorMsg)) {
                                window.open(response.data.jobber_result.auth_url, '_blank');
                            }
                        } else {
                            alert(errorMsg);
                        }
                    }
                },
                error: function(xhr, status, error) {
                    console.error('[Push to Jobber] AJAX Error:', {xhr: xhr, status: status, error: error});
                    console.error('[Push to Jobber] Response Text:', xhr.responseText);
                    $statusCell.html('<span class="jobber-status failed"><span class="dashicons dashicons-warning" style="color: #dc3232;"></span> Error</span>');
                    alert('An error occurred while syncing to Jobber. Please try again.');
                },
                complete: function() {
                    $button.prop('disabled', false).html(originalHtml);
                }
            });
        });
        
        // Export orders
        $('#export-orders').on('click', function() {
            var $button = $(this);
            var originalText = $button.html();
            $button.prop('disabled', true).html('<span class="spinner is-active" style="float: none; margin: 0;"></span> Exporting...');
            
            // Create form and submit
            var form = $('<form>', {
                method: 'POST',
                action: xtremecleansOrdersData.ajaxUrl
            });
            
            form.append($('<input>', {
                type: 'hidden',
                name: 'action',
                value: 'xtremecleans_export_orders'
            }));
            
            form.append($('<input>', {
                type: 'hidden',
                name: 'nonce',
                value: xtremecleansOrdersData.nonce
            }));
            
            $('body').append(form);
            form.submit();
            
            setTimeout(function() {
                $button.prop('disabled', false).html(originalText);
                form.remove();
            }, 1000);
        });
        
        // Close modal
        $('.xtremecleans-modal-close').on('click', function() {
            $('#xtremecleans-order-modal').hide();
        });
        
        $(window).on('click', function(e) {
            if ($(e.target).hasClass('xtremecleans-modal')) {
                $('#xtremecleans-order-modal').hide();
            }
        });
        
        // ESC key to close modal
        $(document).on('keydown', function(e) {
            if (e.key === 'Escape' && $('#xtremecleans-order-modal').is(':visible')) {
                $('#xtremecleans-order-modal').hide();
            }
        });
    }
    
    /**
     * Filter orders based on search and filters
     */
    function filterOrders() {
        var searchTerm = $('#orders-search').val().toLowerCase();
        var stateFilter = $('#orders-filter-state').val().toLowerCase();
        var visibleCount = 0;
        
        $('#orders-tbody tr').each(function() {
            var $row = $(this);
            var orderData = $row.data('order-data');
            var text = '';
            
            if (orderData) {
                text += (orderData.first_name || '') + ' ';
                text += (orderData.last_name || '') + ' ';
                text += (orderData.email || '') + ' ';
                text += (orderData.phone || '') + ' ';
                text += (orderData.city || '') + ' ';
                text += (orderData.state || '') + ' ';
                text += (orderData.zip_code || '') + ' ';
                text += (orderData.id || '');
            } else {
                text = $row.text();
            }
            
            var matchesSearch = !searchTerm || text.toLowerCase().indexOf(searchTerm) !== -1;
            var matchesState = !stateFilter || (orderData && orderData.state && orderData.state.toLowerCase() === stateFilter);
            
            if (matchesSearch && matchesState) {
                $row.show();
                visibleCount++;
            } else {
                $row.hide();
            }
        });
        
        updateOrdersCount(visibleCount);
    }
    
    /**
     * Sort orders
     */
    function sortOrders() {
        var sortValue = $('#orders-sort').val();
        var $tbody = $('#orders-tbody');
        var $rows = $tbody.find('tr').toArray();
        
        $rows.sort(function(a, b) {
            var aData = $(a).data('order-data');
            var bData = $(b).data('order-data');
            
            if (!aData || !bData) return 0;
            
            switch(sortValue) {
                case 'date-desc':
                    return new Date(bData.created_at || 0) - new Date(aData.created_at || 0);
                case 'date-asc':
                    return new Date(aData.created_at || 0) - new Date(bData.created_at || 0);
                case 'amount-desc':
                    return parseFloat(bData.total_amount || 0) - parseFloat(aData.total_amount || 0);
                case 'amount-asc':
                    return parseFloat(aData.total_amount || 0) - parseFloat(bData.total_amount || 0);
                default:
                    return 0;
            }
        });
        
        $tbody.empty().append($rows);
    }
    
    /**
     * Update orders count
     */
    function updateOrdersCount(count) {
        if (count !== undefined) {
            $('#orders-count').text(count);
        } else {
            var visibleCount = $('#orders-tbody tr:visible').length;
            $('#orders-count').text(visibleCount);
        }
    }
    
    /**
     * Initialize all charts
     */
    function initOrdersCharts() {
        var data = xtremecleansChartData;
        
        // Orders over time (30 days)
        if ($('#ordersChart30').length && data.orders30) {
            var orders30Labels = Object.keys(data.orders30).sort();
            var orders30Values = orders30Labels.map(function(date) {
                return data.orders30[date] || 0;
            });
            
            new Chart(document.getElementById('ordersChart30'), {
                type: 'line',
                data: {
                    labels: orders30Labels,
                    datasets: [{
                        label: 'Orders',
                        data: orders30Values,
                        borderColor: 'rgb(75, 192, 192)',
                        backgroundColor: 'rgba(75, 192, 192, 0.2)',
                        tension: 0.1
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: true,
                    scales: {
                        y: {
                            beginAtZero: true,
                            ticks: {
                                stepSize: 1
                            }
                        }
                    }
                }
            });
        }
        
        // Revenue over time (30 days)
        if ($('#revenueChart30').length && data.revenue30) {
            var revenue30Labels = Object.keys(data.revenue30).sort();
            var revenue30Values = revenue30Labels.map(function(date) {
                return data.revenue30[date] || 0;
            });
            
            new Chart(document.getElementById('revenueChart30'), {
                type: 'bar',
                data: {
                    labels: revenue30Labels,
                    datasets: [{
                        label: 'Revenue ($)',
                        data: revenue30Values,
                        backgroundColor: 'rgba(54, 162, 235, 0.6)',
                        borderColor: 'rgba(54, 162, 235, 1)',
                        borderWidth: 1
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: true,
                    scales: {
                        y: {
                            beginAtZero: true,
                            ticks: {
                                callback: function(value) {
                                    return '$' + value.toFixed(2);
                                }
                            }
                        }
                    }
                }
            });
        }
        
        // Orders by state
        if ($('#stateChart').length && data.states) {
            var stateLabels = Object.keys(data.states);
            var stateValues = stateLabels.map(function(state) {
                return data.states[state] || 0;
            });
            
            new Chart(document.getElementById('stateChart'), {
                type: 'doughnut',
                data: {
                    labels: stateLabels,
                    datasets: [{
                        data: stateValues,
                        backgroundColor: [
                            'rgba(255, 99, 132, 0.6)',
                            'rgba(54, 162, 235, 0.6)',
                            'rgba(255, 206, 86, 0.6)',
                            'rgba(75, 192, 192, 0.6)',
                            'rgba(153, 102, 255, 0.6)',
                            'rgba(255, 159, 64, 0.6)'
                        ],
                        borderColor: [
                            'rgba(255, 99, 132, 1)',
                            'rgba(54, 162, 235, 1)',
                            'rgba(255, 206, 86, 1)',
                            'rgba(75, 192, 192, 1)',
                            'rgba(153, 102, 255, 1)',
                            'rgba(255, 159, 64, 1)'
                        ],
                        borderWidth: 1
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: true
                }
            });
        }
        
        // Orders by service
        if ($('#serviceChart').length && data.services) {
            var serviceLabels = Object.keys(data.services);
            var serviceValues = serviceLabels.map(function(service) {
                return data.services[service] || 0;
            });
            
            new Chart(document.getElementById('serviceChart'), {
                type: 'pie',
                data: {
                    labels: serviceLabels,
                    datasets: [{
                        data: serviceValues,
                        backgroundColor: [
                            'rgba(255, 99, 132, 0.6)',
                            'rgba(54, 162, 235, 0.6)',
                            'rgba(255, 206, 86, 0.6)',
                            'rgba(75, 192, 192, 0.6)',
                            'rgba(153, 102, 255, 0.6)',
                            'rgba(255, 159, 64, 0.6)'
                        ],
                        borderColor: [
                            'rgba(255, 99, 132, 1)',
                            'rgba(54, 162, 235, 1)',
                            'rgba(255, 206, 86, 1)',
                            'rgba(75, 192, 192, 1)',
                            'rgba(153, 102, 255, 1)',
                            'rgba(255, 159, 64, 1)'
                        ],
                        borderWidth: 1
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: true
                }
            });
        }
    }
    
    /**
     * Show order details in modal
     */
    function showOrderDetails(orderId) {
        $('#xtremecleans-order-modal').show();
        $('#xtremecleans-order-details').html('<div class="xtremecleans-loading"><span class="spinner is-active"></span><p>Loading order details...</p></div>');
        
        $.ajax({
            url: xtremecleansOrdersData.ajaxUrl,
            type: 'POST',
            data: {
                action: 'xtremecleans_get_order_details',
                order_id: orderId,
                nonce: xtremecleansOrdersData.nonce
            },
            success: function(response) {
                if (response.success && response.data.order) {
                    renderOrderDetails(response.data.order);
                } else {
                    $('#xtremecleans-order-details').html('<div class="notice notice-error"><p>' + (response.data.message || 'Failed to load order details.') + '</p></div>');
                }
            },
            error: function() {
                $('#xtremecleans-order-details').html('<div class="notice notice-error"><p>An error occurred while loading order details. Please try again.</p></div>');
            }
        });
    }
    
    /**
     * Render order details in modal
     */
    function renderOrderDetails(order) {
        var services = [];
        if (order.services_json) {
            try {
                services = JSON.parse(order.services_json);
            } catch(e) {
                services = [];
            }
        }
        
        var html = '<div class="xtremecleans-order-details-grid">';
        
        // Customer Information
        html += '<div class="xtremecleans-order-detail-section">';
        html += '<h3>Customer Information</h3>';
        html += '<div class="xtremecleans-order-detail-item"><span class="xtremecleans-order-detail-label">Name</span><span class="xtremecleans-order-detail-value">' + escapeHtml(order.first_name + ' ' + order.last_name) + '</span></div>';
        html += '<div class="xtremecleans-order-detail-item"><span class="xtremecleans-order-detail-label">Email</span><span class="xtremecleans-order-detail-value"><a href="mailto:' + escapeHtml(order.email) + '">' + escapeHtml(order.email) + '</a></span></div>';
        html += '<div class="xtremecleans-order-detail-item"><span class="xtremecleans-order-detail-label">Phone</span><span class="xtremecleans-order-detail-value"><a href="tel:' + escapeHtml(order.phone) + '">' + escapeHtml(order.phone) + '</a></span></div>';
        if (order.alt_phone) {
            html += '<div class="xtremecleans-order-detail-item"><span class="xtremecleans-order-detail-label">Alternate Phone</span><span class="xtremecleans-order-detail-value"><a href="tel:' + escapeHtml(order.alt_phone) + '">' + escapeHtml(order.alt_phone) + '</a></span></div>';
        }
        html += '</div>';
        
        // Address Information
        html += '<div class="xtremecleans-order-detail-section">';
        html += '<h3>Address</h3>';
        html += '<div class="xtremecleans-order-detail-item"><span class="xtremecleans-order-detail-label">Address Line 1</span><span class="xtremecleans-order-detail-value">' + escapeHtml(order.address1 || '—') + '</span></div>';
        if (order.address2) {
            html += '<div class="xtremecleans-order-detail-item"><span class="xtremecleans-order-detail-label">Address Line 2</span><span class="xtremecleans-order-detail-value">' + escapeHtml(order.address2) + '</span></div>';
        }
        html += '<div class="xtremecleans-order-detail-item"><span class="xtremecleans-order-detail-label">City</span><span class="xtremecleans-order-detail-value">' + escapeHtml(order.city || '—') + '</span></div>';
        html += '<div class="xtremecleans-order-detail-item"><span class="xtremecleans-order-detail-label">State</span><span class="xtremecleans-order-detail-value">' + escapeHtml(order.state || '—') + '</span></div>';
        html += '<div class="xtremecleans-order-detail-item"><span class="xtremecleans-order-detail-label">ZIP Code</span><span class="xtremecleans-order-detail-value">' + escapeHtml(order.zip_code || '—') + '</span></div>';
        if (order.instructions) {
            html += '<div class="xtremecleans-order-detail-item"><span class="xtremecleans-order-detail-label">Special Instructions</span><span class="xtremecleans-order-detail-value">' + escapeHtml(order.instructions) + '</span></div>';
        }
        html += '</div>';
        
        // Appointment Information
        html += '<div class="xtremecleans-order-detail-section">';
        html += '<h3>Appointment</h3>';
        html += '<div class="xtremecleans-order-detail-item"><span class="xtremecleans-order-detail-label">Date</span><span class="xtremecleans-order-detail-value">' + (order.appointment_date ? formatDate(order.appointment_date) : '—') + '</span></div>';
        html += '<div class="xtremecleans-order-detail-item"><span class="xtremecleans-order-detail-label">Time</span><span class="xtremecleans-order-detail-value">' + (order.appointment_time || '—') + '</span></div>';
        if (order.appointment_day) {
            html += '<div class="xtremecleans-order-detail-item"><span class="xtremecleans-order-detail-label">Day</span><span class="xtremecleans-order-detail-value">' + escapeHtml(order.appointment_day) + '</span></div>';
        }
        html += '</div>';
        
        // Payment Information
        html += '<div class="xtremecleans-order-detail-section">';
        html += '<h3>Payment</h3>';
        html += '<div class="xtremecleans-order-detail-item"><span class="xtremecleans-order-detail-label">Total Amount</span><span class="xtremecleans-order-detail-value"><strong style="color: #00a32a; font-size: 18px;">$' + parseFloat(order.total_amount || 0).toFixed(2) + '</strong></span></div>';
        if (order.service_fee) {
            html += '<div class="xtremecleans-order-detail-item"><span class="xtremecleans-order-detail-label">Service Fee</span><span class="xtremecleans-order-detail-value">$' + parseFloat(order.service_fee).toFixed(2) + '</span></div>';
        }
        if (order.deposit_amount) {
            html += '<div class="xtremecleans-order-detail-item"><span class="xtremecleans-order-detail-label">Deposit</span><span class="xtremecleans-order-detail-value">$' + parseFloat(order.deposit_amount).toFixed(2) + '</span></div>';
        }
        html += '</div>';
        
        html += '</div>';
        
        // Services
        if (services.length > 0) {
            html += '<div class="xtremecleans-order-detail-section" style="grid-column: 1 / -1;">';
            html += '<h3>Services (' + services.length + ')</h3>';
            html += '<ul class="xtremecleans-order-services-list">';
            services.forEach(function(service) {
                html += '<li>';
                html += '<strong>' + escapeHtml(service.service_name || 'Service') + '</strong>';
                if (service.items && service.items.length > 0) {
                    html += '<ul style="margin: 8px 0 0 20px; list-style: disc;">';
                    service.items.forEach(function(item) {
                        html += '<li>' + escapeHtml(item.item_name || 'Item');
                        if (item.selected_prices) {
                            var prices = [];
                            if (item.selected_prices.clean) prices.push('Clean: $' + parseFloat(item.selected_prices.clean).toFixed(2));
                            if (item.selected_prices.protect) prices.push('Protect: $' + parseFloat(item.selected_prices.protect).toFixed(2));
                            if (item.selected_prices.deodorize) prices.push('Deodorize: $' + parseFloat(item.selected_prices.deodorize).toFixed(2));
                            if (prices.length > 0) {
                                html += ' (' + prices.join(', ') + ')';
                            }
                        }
                        html += '</li>';
                    });
                    html += '</ul>';
                }
                html += '</li>';
            });
            html += '</ul>';
            html += '</div>';
        }
        
        // Order Metadata
        html += '<div class="xtremecleans-order-detail-section" style="grid-column: 1 / -1; margin-top: 20px;">';
        html += '<h3>Order Information</h3>';
        html += '<div class="xtremecleans-order-detail-item"><span class="xtremecleans-order-detail-label">Order ID</span><span class="xtremecleans-order-detail-value">#' + order.id + '</span></div>';
        html += '<div class="xtremecleans-order-detail-item"><span class="xtremecleans-order-detail-label">Created At</span><span class="xtremecleans-order-detail-value">' + (order.created_at ? formatDateTime(order.created_at) : '—') + '</span></div>';
        if (order.updated_at && order.updated_at !== order.created_at) {
            html += '<div class="xtremecleans-order-detail-item"><span class="xtremecleans-order-detail-label">Last Updated</span><span class="xtremecleans-order-detail-value">' + formatDateTime(order.updated_at) + '</span></div>';
        }
        html += '</div>';
        
        $('#xtremecleans-order-details').html(html);
    }
    
    /**
     * Escape HTML
     */
    function escapeHtml(text) {
        if (!text) return '—';
        var map = {
            '&': '&amp;',
            '<': '&lt;',
            '>': '&gt;',
            '"': '&quot;',
            "'": '&#039;'
        };
        return String(text).replace(/[&<>"']/g, function(m) { return map[m]; });
    }
    
    /**
     * Format date
     */
    function formatDate(dateString) {
        if (!dateString) return '—';
        var date = new Date(dateString);
        return date.toLocaleDateString('en-US', { year: 'numeric', month: 'short', day: 'numeric' });
    }
    
    /**
     * Format date and time
     */
    function formatDateTime(dateString) {
        if (!dateString) return '—';
        var date = new Date(dateString);
        return date.toLocaleString('en-US', { year: 'numeric', month: 'short', day: 'numeric', hour: 'numeric', minute: '2-digit' });
    }
    
    // Test email functionality
    $('#test-email-send').on('click', function() {
        var $button = $(this);
        var $result = $('#test-email-result');
        var originalText = $button.html();
        var adminEmail = typeof xtremecleansAdminData !== 'undefined' ? xtremecleansAdminData.adminEmail : 'customerservice@xtremecleans.com';
        var testEmail = prompt('Enter email address to send test email:', adminEmail || 'customerservice@xtremecleans.com');
        
        if (!testEmail) {
            return;
        }
        
        $button.prop('disabled', true).html('<span class="spinner is-active" style="float: none; margin: 0;"></span> Sending...');
        $result.html('').removeClass('notice-success notice-error');
        
        var ajaxUrl = typeof ajaxurl !== 'undefined' ? ajaxurl : (typeof xtremecleansAdminData !== 'undefined' ? xtremecleansAdminData.ajaxUrl : '/wp-admin/admin-ajax.php');
        var nonce = typeof xtremecleansAdminData !== 'undefined' ? xtremecleansAdminData.emailTestNonce : '';
        
        $.ajax({
            url: ajaxUrl,
            type: 'POST',
            data: {
                action: 'xtremecleans_test_email',
                test_email: testEmail,
                nonce: nonce
            },
            success: function(response) {
                if (response.success) {
                    $result.html('<div class="notice notice-success inline"><p>' + response.data.message + '</p></div>');
                } else {
                    var errorMsg = response.data.message || 'Failed to send test email.';
                    $result.html('<div class="notice notice-error inline"><p>' + errorMsg + '</p></div>');
                }
            },
            error: function() {
                $result.html('<div class="notice notice-error inline"><p>An error occurred while sending test email.</p></div>');
            },
            complete: function() {
                $button.prop('disabled', false).html(originalText);
            }
        });
    });

})(jQuery);

