<?php
/**
 * Admin Leads Template
 *
 * @package XtremeCleans
 * @subpackage Admin Templates
 * @since 1.0.0
 *
 * @var array $leads All leads from database
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

<div class="wrap xtremecleans-admin-wrap xtremecleans-leads-page">
    <div class="xtremecleans-leads-header">
        <h1><?php echo esc_html(get_admin_page_title()); ?></h1>
        <div class="xtremecleans-page-nav">
            <a href="<?php echo esc_url(admin_url('admin.php?page=xtremecleans')); ?>" class="xtremecleans-nav-link"><?php esc_html_e('Dashboard', 'xtremecleans'); ?></a>
            <span class="xtremecleans-nav-separator">|</span>
            <a href="<?php echo esc_url(admin_url('admin.php?page=xtremecleans-orders')); ?>" class="xtremecleans-nav-link"><?php esc_html_e('Orders', 'xtremecleans'); ?></a>
            <span class="xtremecleans-nav-separator">|</span>
            <a href="<?php echo esc_url(admin_url('admin.php?page=xtremecleans-leads')); ?>" class="xtremecleans-nav-link active"><?php esc_html_e('Leads', 'xtremecleans'); ?></a>
            <span class="xtremecleans-nav-separator">|</span>
            <a href="<?php echo esc_url(admin_url('admin.php?page=xtremecleans-zip-zone')); ?>" class="xtremecleans-nav-link"><?php esc_html_e('Zip Zone', 'xtremecleans'); ?></a>
            <span class="xtremecleans-nav-separator">|</span>
            <a href="<?php echo esc_url(admin_url('admin.php?page=xtremecleans-service-items')); ?>" class="xtremecleans-nav-link"><?php esc_html_e('Service Items', 'xtremecleans'); ?></a>
            <span class="xtremecleans-nav-separator">|</span>
            <a href="<?php echo esc_url(admin_url('admin.php?page=xtremecleans-settings')); ?>" class="xtremecleans-nav-link"><?php esc_html_e('Settings', 'xtremecleans'); ?></a>
            <span class="xtremecleans-nav-separator">|</span>
            <a href="<?php echo esc_url(admin_url('admin.php?page=xtremecleans-shortcodes')); ?>" class="xtremecleans-nav-link"><?php esc_html_e('Shortcodes', 'xtremecleans'); ?></a>
            <span class="xtremecleans-nav-separator">|</span>
            <a href="<?php echo esc_url(admin_url('admin.php?page=xtremecleans-api-test')); ?>" class="xtremecleans-nav-link"><?php esc_html_e('API Test', 'xtremecleans'); ?></a>
        </div>
        <div class="xtremecleans-leads-actions">
            <button type="button" class="button button-secondary" id="refresh-leads">
                <span class="dashicons dashicons-update"></span> <?php esc_html_e('Refresh', 'xtremecleans'); ?>
            </button>
            <button type="button" class="button button-primary" id="export-leads">
                <span class="dashicons dashicons-download"></span> <?php esc_html_e('Export CSV', 'xtremecleans'); ?>
            </button>
        </div>
    </div>
    
    <!-- Statistics Cards -->
    <div class="xtremecleans-stats-grid xtremecleans-leads-stats">
        <div class="xtremecleans-stat-card stat-card-primary">
            <div class="stat-icon">
                <span class="dashicons dashicons-groups"></span>
            </div>
            <div class="stat-content">
                <h3><?php echo esc_html(count($leads)); ?></h3>
                <p><?php esc_html_e('Total Leads', 'xtremecleans'); ?></p>
            </div>
        </div>
        
        <div class="xtremecleans-stat-card stat-card-success">
            <div class="stat-icon">
                <span class="dashicons dashicons-yes-alt"></span>
            </div>
            <div class="stat-content">
                <h3><?php echo esc_html(count(array_filter($leads, function($lead) { return $lead['status'] === 'new'; }))); ?></h3>
                <p><?php esc_html_e('New Leads', 'xtremecleans'); ?></p>
            </div>
        </div>
        
        <div class="xtremecleans-stat-card stat-card-info">
            <div class="stat-icon">
                <span class="dashicons dashicons-email-alt"></span>
            </div>
            <div class="stat-content">
                <h3><?php echo esc_html(count(array_unique(array_column($leads, 'email')))); ?></h3>
                <p><?php esc_html_e('Unique Emails', 'xtremecleans'); ?></p>
            </div>
        </div>
        
        <div class="xtremecleans-stat-card stat-card-warning">
            <div class="stat-icon">
                <span class="dashicons dashicons-calendar-alt"></span>
            </div>
            <div class="stat-content">
                <h3><?php 
                    $today_leads = array_filter($leads, function($lead) {
                        return date('Y-m-d', strtotime($lead['created_at'])) === date('Y-m-d');
                    });
                    echo esc_html(count($today_leads));
                ?></h3>
                <p><?php esc_html_e('Today\'s Leads', 'xtremecleans'); ?></p>
            </div>
        </div>
    </div>
    
    <!-- Leads Table -->
    <div class="xtremecleans-table-container">
        <div class="xtremecleans-table-header">
            <h2><?php esc_html_e('All Leads', 'xtremecleans'); ?></h2>
            <div class="xtremecleans-table-controls">
                <input type="text" id="leads-search" class="xtremecleans-search-input" placeholder="<?php esc_attr_e('Search leads...', 'xtremecleans'); ?>" />
                <select id="leads-status-filter" class="xtremecleans-filter-select">
                    <option value=""><?php esc_html_e('All Statuses', 'xtremecleans'); ?></option>
                    <option value="new"><?php esc_html_e('New', 'xtremecleans'); ?></option>
                    <option value="contacted"><?php esc_html_e('Contacted', 'xtremecleans'); ?></option>
                    <option value="converted"><?php esc_html_e('Converted', 'xtremecleans'); ?></option>
                    <option value="closed"><?php esc_html_e('Closed', 'xtremecleans'); ?></option>
                </select>
            </div>
        </div>
        
        <table class="wp-list-table widefat fixed striped xtremecleans-leads-table">
            <thead>
                <tr>
                    <th class="col-id"><?php esc_html_e('ID', 'xtremecleans'); ?></th>
                    <th class="col-name"><?php esc_html_e('Name', 'xtremecleans'); ?></th>
                    <th class="col-email"><?php esc_html_e('Email', 'xtremecleans'); ?></th>
                    <th class="col-phone"><?php esc_html_e('Phone', 'xtremecleans'); ?></th>
                    <th class="col-zip"><?php esc_html_e('ZIP Code', 'xtremecleans'); ?></th>
                    <th class="col-zone"><?php esc_html_e('Zone', 'xtremecleans'); ?></th>
                    <th class="col-status"><?php esc_html_e('Status', 'xtremecleans'); ?></th>
                    <th class="col-date"><?php esc_html_e('Date', 'xtremecleans'); ?></th>
                    <th class="col-actions"><?php esc_html_e('Actions', 'xtremecleans'); ?></th>
                </tr>
            </thead>
            <tbody>
                <?php if (empty($leads)): ?>
                    <tr>
                        <td colspan="9" class="xtremecleans-empty-state">
                            <span class="dashicons dashicons-info"></span>
                            <?php esc_html_e('No leads found.', 'xtremecleans'); ?>
                        </td>
                    </tr>
                <?php else: ?>
                    <?php foreach ($leads as $lead): ?>
                        <tr data-lead-id="<?php echo esc_attr($lead['id']); ?>" data-status="<?php echo esc_attr($lead['status']); ?>">
                            <td class="col-id"><?php echo esc_html($lead['id']); ?></td>
                            <td class="col-name">
                                <strong><?php echo esc_html($lead['name']); ?></strong>
                            </td>
                            <td class="col-email">
                                <a href="mailto:<?php echo esc_attr($lead['email']); ?>"><?php echo esc_html($lead['email']); ?></a>
                            </td>
                            <td class="col-phone">
                                <a href="tel:<?php echo esc_attr($lead['phone']); ?>"><?php echo esc_html($lead['phone']); ?></a>
                            </td>
                            <td class="col-zip"><?php echo esc_html($lead['zip_code']); ?></td>
                            <td class="col-zone"><?php echo esc_html($lead['zone_name'] ? $lead['zone_name'] : '-'); ?></td>
                            <td class="col-status">
                                <span class="xtremecleans-status-badge status-<?php echo esc_attr($lead['status']); ?>">
                                    <?php echo esc_html(ucfirst($lead['status'])); ?>
                                </span>
                            </td>
                            <td class="col-date">
                                <?php echo esc_html(date_i18n(get_option('date_format') . ' ' . get_option('time_format'), strtotime($lead['created_at']))); ?>
                            </td>
                            <td class="col-actions">
                                <div class="xtremecleans-action-buttons">
                                    <button type="button" 
                                            class="button button-small button-primary view-lead-details" 
                                            data-lead-id="<?php echo esc_attr($lead['id']); ?>"
                                            title="<?php esc_attr_e('View Details', 'xtremecleans'); ?>">
                                        <span class="dashicons dashicons-visibility"></span>
                                    </button>
                                    <button type="button" 
                                            class="button button-small button-link-delete delete-lead" 
                                            data-lead-id="<?php echo esc_attr($lead['id']); ?>"
                                            title="<?php esc_attr_e('Delete Lead', 'xtremecleans'); ?>">
                                        <span class="dashicons dashicons-trash"></span>
                                    </button>
                                </div>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>

<!-- Lead Details Modal -->
<div id="xtremecleans-lead-modal" class="xtremecleans-modal" style="display: none;">
    <div class="xtremecleans-modal-content">
        <div class="xtremecleans-modal-header">
            <h2><?php esc_html_e('Lead Details', 'xtremecleans'); ?></h2>
            <button type="button" class="xtremecleans-modal-close">&times;</button>
        </div>
        <div class="xtremecleans-modal-body" id="xtremecleans-lead-details">
            <!-- Lead details will be loaded here -->
        </div>
    </div>
</div>

<script type="text/javascript">
jQuery(document).ready(function($) {
    // Localize script data
    var xtremecleansLeadsData = {
        ajaxUrl: '<?php echo esc_js(admin_url('admin-ajax.php')); ?>',
        nonce: '<?php echo esc_js(wp_create_nonce('xtremecleans_leads')); ?>'
    };
    
    // Refresh leads
    $('#refresh-leads').on('click', function() {
        location.reload();
    });
    
    // Search functionality
    $('#leads-search').on('keyup', function() {
        var searchTerm = $(this).val().toLowerCase();
        $('.xtremecleans-leads-table tbody tr').each(function() {
            var text = $(this).text().toLowerCase();
            $(this).toggle(text.indexOf(searchTerm) > -1);
        });
    });
    
    // Status filter
    $('#leads-status-filter').on('change', function() {
        var status = $(this).val();
        $('.xtremecleans-leads-table tbody tr').each(function() {
            if (!status) {
                $(this).show();
            } else {
                $(this).toggle($(this).data('status') === status);
            }
        });
    });
    
    // View lead details
    $('.view-lead-details').on('click', function() {
        var leadId = $(this).data('lead-id');
        // Load lead details via AJAX
        $('#xtremecleans-lead-modal').show();
    });
    
    // Close modal
    $('.xtremecleans-modal-close, .xtremecleans-modal').on('click', function(e) {
        if (e.target === this) {
            $('#xtremecleans-lead-modal').hide();
        }
    });
    
    // Delete lead
    $('.delete-lead').on('click', function() {
        if (!confirm('<?php echo esc_js(__('Are you sure you want to delete this lead?', 'xtremecleans')); ?>')) {
            return;
        }
        var leadId = $(this).data('lead-id');
        var $row = $(this).closest('tr');
        
        $.ajax({
            url: xtremecleansLeadsData.ajaxUrl,
            type: 'POST',
            data: {
                action: 'xtremecleans_delete_lead',
                lead_id: leadId,
                nonce: xtremecleansLeadsData.nonce
            },
            success: function(response) {
                if (response.success) {
                    $row.fadeOut(300, function() {
                        $(this).remove();
                    });
                } else {
                    alert(response.data.message || 'Failed to delete lead.');
                }
            }
        });
    });
    
    // Export leads
    $('#export-leads').on('click', function() {
        window.location.href = '<?php echo esc_url(admin_url('admin-post.php?action=xtremecleans_export_leads')); ?>';
    });
});
</script>

