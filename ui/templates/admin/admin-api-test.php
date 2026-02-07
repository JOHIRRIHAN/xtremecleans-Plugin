<?php
/**
 * Admin API Test Template
 *
 * @package XtremeCleans
 * @subpackage Admin Templates
 * @since 1.0.0
 *
 * @var array|null $test_result     Test result
 * @var bool       $api_configured  API configuration status
 */

// Exit if accessed directly
if (!defined('ABSPATH')) {
    exit;
}
?>
<style>
/* Unified Layout - All Admin Pages */
.wrap.xtremecleans-admin-wrap,
.wrap.xtremecleans-orders-page,
.wrap.xtremecleans-leads-page,
.wrap.xtremecleans-zip-zone-page,
.wrap.xtremecleans-service-items-page,
#wpbody-content .wrap.xtremecleans-admin-wrap,
#wpbody-content .wrap.xtremecleans-orders-page,
#wpbody-content .wrap.xtremecleans-leads-page,
#wpbody-content .wrap.xtremecleans-zip-zone-page,
#wpbody-content .wrap.xtremecleans-service-items-page {
    max-width: 1400px !important;
    width: 100% !important;
    margin-left: auto !important;
    margin-right: auto !important;
    box-sizing: border-box !important;
}

#wpbody-content:has(.xtremecleans-admin-wrap),
#wpbody-content:has(.xtremecleans-orders-page),
#wpbody-content:has(.xtremecleans-leads-page),
#wpbody-content:has(.xtremecleans-zip-zone-page),
#wpbody-content:has(.xtremecleans-service-items-page) {
    max-width: 1450px !important;
    width: 100% !important;
    margin-left: auto !important;
    margin-right: auto !important;
    float: none !important;
}
</style>

<div class="wrap xtremecleans-admin-wrap">
    <div class="xtremecleans-page-header-nav">
        <h1><?php echo esc_html(get_admin_page_title()); ?></h1>
        <div class="xtremecleans-page-nav">
            <a href="<?php echo esc_url(admin_url('admin.php?page=xtremecleans')); ?>" class="xtremecleans-nav-link"><?php esc_html_e('Dashboard', 'xtremecleans'); ?></a>
            <span class="xtremecleans-nav-separator">|</span>
            <a href="<?php echo esc_url(admin_url('admin.php?page=xtremecleans-orders')); ?>" class="xtremecleans-nav-link"><?php esc_html_e('Orders', 'xtremecleans'); ?></a>
            <span class="xtremecleans-nav-separator">|</span>
            <a href="<?php echo esc_url(admin_url('admin.php?page=xtremecleans-leads')); ?>" class="xtremecleans-nav-link"><?php esc_html_e('Leads', 'xtremecleans'); ?></a>
            <span class="xtremecleans-nav-separator">|</span>
            <a href="<?php echo esc_url(admin_url('admin.php?page=xtremecleans-zip-zone')); ?>" class="xtremecleans-nav-link"><?php esc_html_e('Zip Zone', 'xtremecleans'); ?></a>
            <span class="xtremecleans-nav-separator">|</span>
            <a href="<?php echo esc_url(admin_url('admin.php?page=xtremecleans-service-items')); ?>" class="xtremecleans-nav-link"><?php esc_html_e('Service Items', 'xtremecleans'); ?></a>
            <span class="xtremecleans-nav-separator">|</span>
            <a href="<?php echo esc_url(admin_url('admin.php?page=xtremecleans-settings')); ?>" class="xtremecleans-nav-link"><?php esc_html_e('Settings', 'xtremecleans'); ?></a>
            <span class="xtremecleans-nav-separator">|</span>
            <a href="<?php echo esc_url(admin_url('admin.php?page=xtremecleans-shortcodes')); ?>" class="xtremecleans-nav-link"><?php esc_html_e('Shortcodes', 'xtremecleans'); ?></a>
            <span class="xtremecleans-nav-separator">|</span>
            <a href="<?php echo esc_url(admin_url('admin.php?page=xtremecleans-api-test')); ?>" class="xtremecleans-nav-link active"><?php esc_html_e('API Test', 'xtremecleans'); ?></a>
        </div>
    </div>
    
    <?php if (!$api_configured): ?>
        <div class="notice notice-warning">
            <p>
                <?php esc_html_e('Please configure your API settings first.', 'xtremecleans'); ?>
                <a href="<?php echo esc_url(admin_url('admin.php?page=xtremecleans-settings')); ?>" class="xtremecleans-admin-link">
                    <?php esc_html_e('Go to Settings', 'xtremecleans'); ?>
                </a>
            </p>
        </div>
    <?php endif; ?>
    
    <form method="post" class="xtremecleans-api-test-form">
        <?php wp_nonce_field('xtremecleans_test_api'); ?>
        
        <table class="form-table">
            <tr>
                <th scope="row">
                    <label for="test_endpoint"><?php esc_html_e('Endpoint', 'xtremecleans'); ?></label>
                </th>
                <td>
                    <input type="text" id="test_endpoint" name="test_endpoint" value="/" class="regular-text" />
                    <p class="description"><?php esc_html_e('API endpoint path (e.g., /users, /data)', 'xtremecleans'); ?></p>
                </td>
            </tr>
            <tr>
                <th scope="row">
                    <label for="test_method"><?php esc_html_e('Method', 'xtremecleans'); ?></label>
                </th>
                <td>
                    <select id="test_method" name="test_method">
                        <option value="GET">GET</option>
                        <option value="POST">POST</option>
                        <option value="PUT">PUT</option>
                        <option value="DELETE">DELETE</option>
                    </select>
                </td>
            </tr>
            <tr id="test_body_row" style="display: none;">
                <th scope="row">
                    <label for="test_body"><?php esc_html_e('Request Body', 'xtremecleans'); ?></label>
                </th>
                <td>
                    <textarea id="test_body" name="test_body" rows="5" class="large-text code"></textarea>
                    <p class="description"><?php esc_html_e('JSON format for POST/PUT requests', 'xtremecleans'); ?></p>
                </td>
            </tr>
        </table>
        
        <p class="submit">
            <input type="submit" name="test_api" class="button button-primary" value="<?php esc_attr_e('Test API', 'xtremecleans'); ?>" />
        </p>
    </form>
    
    <?php if ($test_result): ?>
        <div class="xtremecleans-api-result <?php echo $test_result['success'] ? 'success' : 'error'; ?>">
            <h2><?php echo $test_result['success'] ? esc_html__('Success!', 'xtremecleans') : esc_html__('Error!', 'xtremecleans'); ?></h2>
            
            <?php if (isset($test_result['status_code'])): ?>
                <p>
                    <strong><?php esc_html_e('Status Code:', 'xtremecleans'); ?></strong> 
                    <?php echo esc_html($test_result['status_code']); ?>
                </p>
            <?php endif; ?>
            
            <?php if (isset($test_result['message'])): ?>
                <p>
                    <strong><?php esc_html_e('Message:', 'xtremecleans'); ?></strong> 
                    <?php echo esc_html($test_result['message']); ?>
                </p>
            <?php endif; ?>
            
            <?php if (isset($test_result['body'])): ?>
                <h3><?php esc_html_e('Response Body:', 'xtremecleans'); ?></h3>
                <pre class="xtremecleans-code-block"><?php echo esc_html($test_result['body']); ?></pre>
            <?php endif; ?>
        </div>
    <?php endif; ?>
    
    <!-- Logs Section -->
    <div class="xtremecleans-logs-section" style="margin-top: 30px;">
        <h2><?php esc_html_e('📋 Activity Logs', 'xtremecleans'); ?></h2>
        <p class="description">
            <?php esc_html_e('View recent plugin activity logs. Logs are stored in PHP error log file.', 'xtremecleans'); ?>
        </p>
        
        <div class="xtremecleans-logs-controls" style="margin: 15px 0;">
            <button type="button" id="xtremecleans-refresh-logs" class="button">
                <span class="dashicons dashicons-update" style="vertical-align: middle;"></span>
                <?php esc_html_e('Refresh Logs', 'xtremecleans'); ?>
            </button>
            <button type="button" id="xtremecleans-clear-logs-view" class="button">
                <span class="dashicons dashicons-dismiss" style="vertical-align: middle;"></span>
                <?php esc_html_e('Clear View', 'xtremecleans'); ?>
            </button>
            <span class="description" style="margin-left: 15px;">
                <?php esc_html_e('Note: This only clears the view. Actual log file is managed by your server.', 'xtremecleans'); ?>
            </span>
        </div>
        
        <div class="xtremecleans-logs-container" style="background: #1e1e1e; color: #d4d4d4; padding: 20px; border-radius: 8px; font-family: 'Courier New', monospace; font-size: 13px; line-height: 1.6; max-height: 600px; overflow-y: auto; position: relative;">
            <div id="xtremecleans-logs-content" style="white-space: pre-wrap; word-wrap: break-word;">
                <div style="color: #888; font-style: italic;">
                    <?php esc_html_e('Loading logs...', 'xtremecleans'); ?>
                </div>
            </div>
        </div>
        
        <div class="xtremecleans-logs-info" style="margin-top: 15px; padding: 10px; background: #f0f6fc; border-left: 4px solid #4caf50; border-radius: 4px;">
            <p style="margin: 0;">
                <strong><?php esc_html_e('ℹ️ How to View Full Logs:', 'xtremecleans'); ?></strong>
            </p>
            <ul style="margin: 10px 0 0 20px;">
                <li><?php esc_html_e('Logs are written to PHP error log file', 'xtremecleans'); ?></li>
                <li><?php esc_html_e('Check your server\'s error log location (usually in wp-content/debug.log or server error log)', 'xtremecleans'); ?></li>
                <li><?php esc_html_e('Enable logging in Settings → General → Enable Logging', 'xtremecleans'); ?></li>
                <li><?php esc_html_e('Look for entries starting with [XtremeCleans]', 'xtremecleans'); ?></li>
            </ul>
        </div>
    </div>
</div>

<script type="text/javascript">
jQuery(document).ready(function($) {
    var logsContainer = $('#xtremecleans-logs-content');
    var isLoading = false;
    
    function loadLogs() {
        if (isLoading) return;
        isLoading = true;
        
        logsContainer.html('<div style="color: #888; font-style: italic;"><?php esc_html_e('Loading logs...', 'xtremecleans'); ?></div>');
        
        $.ajax({
            url: '<?php echo admin_url('admin-ajax.php'); ?>',
            type: 'POST',
            data: {
                action: 'xtremecleans_get_recent_logs',
                nonce: '<?php echo wp_create_nonce('xtremecleans_logs'); ?>'
            },
            success: function(response) {
                isLoading = false;
                if (response.success) {
                    var logsHtml = '';
                    var logs = response.data.logs || [];
                    var logFile = response.data.log_file || null;
                    var message = response.data.message || '';
                    
                    if (logs.length === 0) {
                        if (logFile) {
                            logsHtml = '<div style="color: #888; font-style: italic;"><?php esc_html_e('No recent XtremeCleans logs found in log file. Make sure logging is enabled in Settings and try submitting an order.', 'xtremecleans'); ?></div>';
                        } else {
                            logsHtml = '<div style="color: #dcdcaa; padding: 10px; background: rgba(220, 220, 170, 0.1); border-left: 3px solid #dcdcaa; margin-bottom: 10px;">';
                            logsHtml += '<strong><?php esc_html_e('Log file not found.', 'xtremecleans'); ?></strong><br>';
                            logsHtml += '<?php esc_html_e('To enable logging:', 'xtremecleans'); ?><br>';
                            logsHtml += '1. <?php esc_html_e('Add this to wp-config.php:', 'xtremecleans'); ?><br>';
                            logsHtml += '<code style="background: rgba(0,0,0,0.3); padding: 2px 5px; border-radius: 3px;">define(\'WP_DEBUG\', true);<br>define(\'WP_DEBUG_LOG\', true);</code><br><br>';
                            logsHtml += '2. <?php esc_html_e('Enable logging in Settings → General → Enable Logging', 'xtremecleans'); ?><br>';
                            logsHtml += '3. <?php esc_html_e('Logs will be saved to wp-content/debug.log', 'xtremecleans'); ?>';
                            logsHtml += '</div>';
                        }
                    } else {
                        logs.forEach(function(log) {
                            var color = '#d4d4d4';
                            if (log.indexOf('[ERROR]') !== -1 || log.indexOf('[XTREMECLEANS ERROR]') !== -1) {
                                color = '#f48771';
                            } else if (log.indexOf('[WARNING]') !== -1 || log.indexOf('[XTREMECLEANS WARNING]') !== -1) {
                                color = '#dcdcaa';
                            } else if (log.indexOf('[INFO]') !== -1 || log.indexOf('[XTREMECLEANS INFO]') !== -1) {
                                color = '#4ec9b0';
                            }
                            logsHtml += '<div style="color: ' + color + '; margin-bottom: 5px; font-family: monospace; font-size: 12px;">' + escapeHtml(log) + '</div>';
                        });
                    }
                    
                    logsContainer.html(logsHtml);
                    // Auto-scroll to bottom
                    var container = logsContainer.parent();
                    container.scrollTop(container[0].scrollHeight);
                } else {
                    logsContainer.html('<div style="color: #f48771;"><?php esc_html_e('Error loading logs: ', 'xtremecleans'); ?>' + (response.data && response.data.message ? escapeHtml(response.data.message) : '<?php esc_html_e('Unknown error', 'xtremecleans'); ?>') + '</div>');
                }
            },
            error: function(xhr, status, error) {
                isLoading = false;
                var errorMsg = '<?php esc_html_e('Failed to load logs. Please check your server error log file directly.', 'xtremecleans'); ?>';
                if (xhr.responseJSON && xhr.responseJSON.data && xhr.responseJSON.data.message) {
                    errorMsg = xhr.responseJSON.data.message;
                }
                logsContainer.html('<div style="color: #f48771; padding: 10px; background: rgba(244, 135, 113, 0.1); border-left: 3px solid #f48771;">' + escapeHtml(errorMsg) + '</div>');
            }
        });
    }
    
    function escapeHtml(text) {
        var map = {
            '&': '&amp;',
            '<': '&lt;',
            '>': '&gt;',
            '"': '&quot;',
            "'": '&#039;'
        };
        return text.replace(/[&<>"']/g, function(m) { return map[m]; });
    }
    
    $('#xtremecleans-refresh-logs').on('click', function() {
        loadLogs();
    });
    
    $('#xtremecleans-clear-logs-view').on('click', function() {
        logsContainer.html('<div style="color: #888; font-style: italic;"><?php esc_html_e('Logs view cleared. Click Refresh to reload.', 'xtremecleans'); ?></div>');
    });
    
    // Load logs on page load
    loadLogs();
    
    // Auto-refresh every 10 seconds
    setInterval(loadLogs, 10000);
});
</script>

