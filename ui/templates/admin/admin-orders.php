<?php
/**
 * Admin Orders Template
 *
 * @package XtremeCleans
 * @subpackage Admin Templates
 * @since 1.0.0
 *
 * @var array $orders All orders from database
 * @var array $stats Order statistics for charts
 * @var string $orders_nonce Nonce for AJAX requests
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
<<<<<<< HEAD
</style>

<div class="wrap xtremecleans-admin-wrap xtremecleans-orders-page">
    <div class="xtremecleans-page-header-nav">
        <h1><?php echo esc_html(get_admin_page_title()); ?></h1>
=======
.xtremecleans-page-title {
    font-size: 38px !important;
    font-weight: 600 !important;
    padding-top: 30px !important;
}
.xtremecleans-orders-actions {
    display: block !important;
    gap: 50px !important;
}
.xtremecleans-admin-wrap .button:not(.button-link):not(.button-link-delete) {
    border-color: var(--xtremecleans-primary);
    color: var(--xtremecleans-primary);
    margin: 5px auto !important;
}
</style>

<div class="wrap xtremecleans-admin-wrap xtremecleans-orders-page">
    <h1 class="xtremecleans-page-title"><?php echo esc_html(get_admin_page_title()); ?></h1>
    <div class="xtremecleans-page-header-nav">
>>>>>>> 3378c4f (plugin last vertation updated in admin dashboard)
        <div class="xtremecleans-page-nav">
            <a href="<?php echo esc_url(admin_url('admin.php?page=xtremecleans')); ?>" class="xtremecleans-nav-link"><?php esc_html_e('Dashboard', 'xtremecleans'); ?></a>
            <span class="xtremecleans-nav-separator">|</span>
            <a href="<?php echo esc_url(admin_url('admin.php?page=xtremecleans-orders')); ?>" class="xtremecleans-nav-link active"><?php esc_html_e('Orders', 'xtremecleans'); ?></a>
            <span class="xtremecleans-nav-separator">|</span>
            <a href="<?php echo esc_url(admin_url('admin.php?page=xtremecleans-leads')); ?>" class="xtremecleans-nav-link"><?php esc_html_e('Leads', 'xtremecleans'); ?></a>
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
            <a href="<?php echo esc_url(admin_url('admin.php?page=xtremecleans-api-test')); ?>" class="xtremecleans-nav-link"><?php esc_html_e('API Test', 'xtremecleans'); ?></a>
        </div>
        <div class="xtremecleans-orders-actions">
<<<<<<< HEAD
            <button type="button" class="button button-secondary" id="test-jobber-connection">
                <span class="dashicons dashicons-admin-links"></span> <?php esc_html_e('Test Jobber', 'xtremecleans'); ?>
            </button>
            <button type="button" class="button button-secondary" id="refresh-orders">
                <span class="dashicons dashicons-update"></span> <?php esc_html_e('Refresh', 'xtremecleans'); ?>
            </button>
            <button type="button" class="button button-primary" id="export-orders">
=======
            <button type="button" class="button button-secondary" id="test-jobber-connection" style="background: none !important;">
                <span class="dashicons dashicons-admin-links"></span>
            </button>
            <button type="button" class="button button-secondary" id="refresh-orders" style="background: none !important;">
                <span class="dashicons dashicons-update"></span> <?php esc_html_e('Refresh', 'xtremecleans'); ?>
            </button>
            <button type="button" class="button button-primary" id="export-orders" style="background: none !important;">
>>>>>>> 3378c4f (plugin last vertation updated in admin dashboard)
                <span class="dashicons dashicons-download"></span> <?php esc_html_e('Export CSV', 'xtremecleans'); ?>
            </button>
        </div>
    </div>
    
    <!-- Jobber Documentation Info Box -->
    <div class="xtremecleans-jobber-info-box" style="margin: 20px 0; padding: 15px; background: #f0f6fc; border-left: 4px solid #4caf50; border-radius: 4px; position: relative;">
        <button type="button" class="xtremecleans-toggle-docs" style="position: absolute; top: 10px; right: 10px; background: none; border: none; cursor: pointer; font-size: 18px; color: #666;" title="<?php esc_attr_e('Toggle Documentation', 'xtremecleans'); ?>">
            <span class="dashicons dashicons-arrow-down-alt2"></span>
        </button>
        <h3 style="margin-top: 0; color: #1e1e1e; padding-right: 30px;">
            <span class="dashicons dashicons-info" style="vertical-align: middle; color: #4caf50;"></span>
            <?php esc_html_e('📋 Where to View Orders in Jobber', 'xtremecleans'); ?>
        </h3>
        <div class="xtremecleans-docs-content" style="display: block; margin-top: 15px;">
            <p><strong><?php esc_html_e('Step 1:', 'xtremecleans'); ?></strong> <?php esc_html_e('Login to your Jobber account:', 'xtremecleans'); ?> 
                <a href="https://app.getjobber.com" target="_blank" style="color: #4caf50; text-decoration: none;">https://app.getjobber.com</a>
            </p>
            <p><strong><?php esc_html_e('Step 2:', 'xtremecleans'); ?></strong> <?php esc_html_e('Navigate to the "Jobs" section from the left sidebar menu', 'xtremecleans'); ?></p>
            <p><strong><?php esc_html_e('Step 3:', 'xtremecleans'); ?></strong> <?php esc_html_e('All orders submitted from your website will appear as new jobs in this section', 'xtremecleans'); ?></p>
            <p style="margin-top: 15px; padding-top: 15px; border-top: 1px solid #ddd;">
                <strong><?php esc_html_e('What Data is Sent:', 'xtremecleans'); ?></strong><br>
                <?php esc_html_e('Customer Information, Services, Appointment Date/Time, Special Instructions, and Pricing', 'xtremecleans'); ?>
            </p>
            <p style="margin-top: 10px;">
                <strong><?php esc_html_e('Verify Order Status:', 'xtremecleans'); ?></strong><br>
                <?php esc_html_e('Check the "Jobber Sent" column in the table below. A success message indicates the order was sent to Jobber.', 'xtremecleans'); ?>
            </p>
            <p style="margin-top: 10px; color: #d63638;">
                <strong>⚠ <?php esc_html_e('Note:', 'xtremecleans'); ?></strong> 
                <?php esc_html_e('OAuth authorization is required. Go to Settings → General to authorize Jobber access.', 'xtremecleans'); ?>
            </p>
        </div>
    </div>
    
    <!-- Statistics Cards -->
    <div class="xtremecleans-stats-grid xtremecleans-orders-stats">
        <div class="xtremecleans-stat-card stat-card-primary">
            <div class="stat-icon">
                <span class="dashicons dashicons-cart"></span>
            </div>
            <div class="stat-content">
                <h3><?php echo esc_html($stats['total_orders']); ?></h3>
                <p><?php esc_html_e('Total Orders', 'xtremecleans'); ?></p>
            </div>
        </div>
        
        <div class="xtremecleans-stat-card stat-card-success">
            <div class="stat-icon">
                <span class="dashicons dashicons-money-alt"></span>
            </div>
            <div class="stat-content">
                <h3>$<?php echo number_format($stats['total_revenue'], 2); ?></h3>
                <p><?php esc_html_e('Total Revenue', 'xtremecleans'); ?></p>
            </div>
        </div>
        
        <div class="xtremecleans-stat-card stat-card-info">
            <div class="stat-icon">
                <span class="dashicons dashicons-calendar-alt"></span>
            </div>
            <div class="stat-content">
                <h3><?php echo count($stats['orders_by_date_7']); ?></h3>
                <p><?php esc_html_e('Orders (Last 7 Days)', 'xtremecleans'); ?></p>
            </div>
        </div>
        
        <div class="xtremecleans-stat-card stat-card-warning">
            <div class="stat-icon">
                <span class="dashicons dashicons-chart-line"></span>
            </div>
            <div class="stat-content">
                <h3><?php echo count($stats['orders_by_state']); ?></h3>
                <p><?php esc_html_e('States', 'xtremecleans'); ?></p>
            </div>
        </div>
    </div>
    
    <!-- Charts Section -->
    <div class="xtremecleans-charts-section">
        <div class="xtremecleans-chart-card">
            <div class="chart-header">
                <h2><?php esc_html_e('Orders Over Time (Last 30 Days)', 'xtremecleans'); ?></h2>
            </div>
            <div class="chart-container">
                <canvas id="ordersChart30"></canvas>
            </div>
        </div>
        
        <div class="xtremecleans-chart-card">
            <div class="chart-header">
                <h2><?php esc_html_e('Revenue Over Time (Last 30 Days)', 'xtremecleans'); ?></h2>
            </div>
            <div class="chart-container">
                <canvas id="revenueChart30"></canvas>
            </div>
        </div>
        
        <div class="xtremecleans-chart-card">
            <div class="chart-header">
                <h2><?php esc_html_e('Orders by State', 'xtremecleans'); ?></h2>
            </div>
            <div class="chart-container">
                <canvas id="stateChart"></canvas>
            </div>
        </div>
        
        <div class="xtremecleans-chart-card">
            <div class="chart-header">
                <h2><?php esc_html_e('Orders by Service', 'xtremecleans'); ?></h2>
            </div>
            <div class="chart-container">
                <canvas id="serviceChart"></canvas>
            </div>
        </div>
    </div>
    
    <!-- Orders Table Section -->
    <div class="xtremecleans-orders-table-wrapper">
        <div class="xtremecleans-table-header">
            <h2><?php esc_html_e('All Orders', 'xtremecleans'); ?></h2>
            <div class="xtremecleans-table-controls">
                <div class="xtremecleans-search-box">
                    <input type="text" 
                           id="orders-search" 
                           class="xtremecleans-search-input" 
                           placeholder="<?php esc_attr_e('Search orders...', 'xtremecleans'); ?>">
                    <span class="dashicons dashicons-search"></span>
                </div>
                <select id="orders-filter-state" class="xtremecleans-filter-select">
                    <option value=""><?php esc_html_e('All States', 'xtremecleans'); ?></option>
                    <?php 
                    $states = array_unique(array_column($orders, 'state'));
                    $states = array_filter($states);
                    sort($states);
                    foreach ($states as $state): 
                    ?>
                        <option value="<?php echo esc_attr($state); ?>"><?php echo esc_html($state); ?></option>
                    <?php endforeach; ?>
                </select>
                <select id="orders-sort" class="xtremecleans-filter-select">
                    <option value="date-desc"><?php esc_html_e('Newest First', 'xtremecleans'); ?></option>
                    <option value="date-asc"><?php esc_html_e('Oldest First', 'xtremecleans'); ?></option>
                    <option value="amount-desc"><?php esc_html_e('Highest Amount', 'xtremecleans'); ?></option>
                    <option value="amount-asc"><?php esc_html_e('Lowest Amount', 'xtremecleans'); ?></option>
                </select>
            </div>
        </div>
        
        <div class="xtremecleans-table-info">
            <span id="orders-count"><?php echo count($orders); ?></span> <?php esc_html_e('order(s) found', 'xtremecleans'); ?>
        </div>
        
        <?php if (empty($orders)): ?>
            <div class="xtremecleans-empty-state">
                <span class="dashicons dashicons-cart"></span>
                <h3><?php esc_html_e('No orders found', 'xtremecleans'); ?></h3>
                <p><?php esc_html_e('Orders will appear here once customers place orders.', 'xtremecleans'); ?></p>
            </div>
        <?php else: ?>
            <div class="xtremecleans-table-responsive">
                <table class="wp-list-table widefat fixed striped xtremecleans-orders-table">
                    <thead>
                        <tr>
                            <th class="col-id"><?php esc_html_e('ID', 'xtremecleans'); ?></th>
                            <th class="col-customer"><?php esc_html_e('Customer', 'xtremecleans'); ?></th>
                            <th class="col-contact"><?php esc_html_e('Contact', 'xtremecleans'); ?></th>
                            <th class="col-location"><?php esc_html_e('Location', 'xtremecleans'); ?></th>
                            <th class="col-appointment"><?php esc_html_e('Appointment', 'xtremecleans'); ?></th>
                            <th class="col-services"><?php esc_html_e('Services', 'xtremecleans'); ?></th>
                            <th class="col-total"><?php esc_html_e('Total', 'xtremecleans'); ?></th>
                            <th class="col-jobber"><?php esc_html_e('Jobber', 'xtremecleans'); ?></th>
                            <th class="col-date"><?php esc_html_e('Date', 'xtremecleans'); ?></th>
                            <th class="col-actions"><?php esc_html_e('Actions', 'xtremecleans'); ?></th>
                        </tr>
                    </thead>
                    <tbody id="orders-tbody">
                        <?php foreach ($orders as $order): 
                            $services = !empty($order['services_json']) ? json_decode($order['services_json'], true) : array();
                            $services_count = is_array($services) ? count($services) : 0;
                            $services_preview = '';
                            if (is_array($services) && !empty($services)) {
                                $service_names = array();
                                foreach (array_slice($services, 0, 2) as $service) {
                                    if (isset($service['service_name'])) {
                                        $service_names[] = esc_html($service['service_name']);
                                    }
                                }
                                $services_preview = implode(', ', $service_names);
                                if (count($services) > 2) {
                                    $services_preview .= ' +' . (count($services) - 2) . ' more';
                                }
                            }
                            $order_data_attr = esc_attr(json_encode($order));
                        ?>
                            <tr data-order-id="<?php echo esc_attr($order['id']); ?>" 
                                data-order-data="<?php echo $order_data_attr; ?>">
                                <td class="col-id">
                                    <strong>#<?php echo esc_html($order['id']); ?></strong>
                                </td>
                                <td class="col-customer">
                                    <strong><?php echo esc_html($order['first_name'] . ' ' . $order['last_name']); ?></strong>
                                </td>
                                <td class="col-contact">
                                    <div class="contact-info">
                                        <a href="mailto:<?php echo esc_attr($order['email']); ?>" class="contact-email">
                                            <?php echo esc_html($order['email']); ?>
                                        </a>
                                        <a href="tel:<?php echo esc_attr($order['phone']); ?>" class="contact-phone">
                                            <?php echo esc_html($order['phone']); ?>
                                        </a>
                                    </div>
                                </td>
                                <td class="col-location">
                                    <?php 
                                    $location_parts = array();
                                    if (!empty($order['city'])) $location_parts[] = esc_html($order['city']);
                                    if (!empty($order['state'])) $location_parts[] = esc_html($order['state']);
                                    if (!empty($order['zip_code'])) $location_parts[] = esc_html($order['zip_code']);
                                    echo !empty($location_parts) ? implode(', ', $location_parts) : '<span class="text-muted">—</span>';
                                    ?>
                                </td>
                                <td class="col-appointment">
                                    <?php 
                                    if (!empty($order['appointment_date'])) {
                                        echo '<div class="appointment-date">' . esc_html(date('M d, Y', strtotime($order['appointment_date']))) . '</div>';
                                        if (!empty($order['appointment_time'])) {
                                            echo '<div class="appointment-time">' . esc_html($order['appointment_time']) . '</div>';
                                        }
                                    } else {
                                        echo '<span class="text-muted">—</span>';
                                    }
                                    ?>
                                </td>
                                <td class="col-services">
                                    <?php if ($services_preview): ?>
                                        <span class="services-badge" title="<?php echo esc_attr($services_preview); ?>">
                                            <?php echo esc_html($services_count); ?> <?php esc_html_e('service(s)', 'xtremecleans'); ?>
                                        </span>
                                    <?php else: ?>
                                        <span class="text-muted">—</span>
                                    <?php endif; ?>
                                </td>
                                <td class="col-total">
                                    <strong class="total-amount">$<?php echo number_format(floatval($order['total_amount']), 2); ?></strong>
                                </td>
                                <td class="col-jobber">
                                    <?php
                                    $sync_status = isset($order['jobber_sync_status']) ? $order['jobber_sync_status'] : 'pending';
                                    $sync_message = isset($order['jobber_sync_message']) ? $order['jobber_sync_message'] : '';
                                    $job_id = isset($order['jobber_job_id']) ? $order['jobber_job_id'] : '';
                                    
                                    if ($sync_status === 'success') {
                                        echo '<span class="jobber-status success" title="' . esc_attr($sync_message) . '">';
                                        echo '<span class="dashicons dashicons-yes-alt" style="color: #46b450;"></span> ';
                                        esc_html_e('Synced', 'xtremecleans');
                                        if ($job_id) {
                                            echo '<br><small style="color: #666;">ID: ' . esc_html($job_id) . '</small>';
                                        }
                                        echo '</span>';
                                    } elseif ($sync_status === 'failed') {
                                        echo '<span class="jobber-status failed" title="' . esc_attr($sync_message) . '">';
                                        echo '<span class="dashicons dashicons-warning" style="color: #dc3232;"></span> ';
                                        esc_html_e('Failed', 'xtremecleans');
                                        if (!empty($sync_message)) {
                                            echo '<br><small class="jobber-sync-error" style="display: block; margin-top: 4px; color: #b32d2e; font-size: 11px; max-width: 200px; line-height: 1.3;">' . esc_html($sync_message) . '</small>';
                                        }
                                        echo '</span>';
                                    } else {
                                        echo '<span class="jobber-status pending" title="' . esc_attr($sync_message ?: 'Pending sync') . '">';
                                        echo '<span class="dashicons dashicons-clock" style="color: #f0b849;"></span> ';
                                        esc_html_e('Pending', 'xtremecleans');
                                        echo '</span>';
                                    }
                                    ?>
                                </td>
                                <td class="col-date">
                                    <?php 
                                    if (!empty($order['created_at'])) {
                                        echo '<div class="order-date">' . esc_html(date('M d, Y', strtotime($order['created_at']))) . '</div>';
                                        echo '<div class="order-time">' . esc_html(date('g:i A', strtotime($order['created_at']))) . '</div>';
                                    }
                                    ?>
                                </td>
                                <td class="col-actions">
                                    <div class="xtremecleans-action-buttons">
                                        <button type="button" 
                                                class="button button-small button-primary view-order-details" 
                                                data-order-id="<?php echo esc_attr($order['id']); ?>"
                                                title="<?php esc_attr_e('View Details', 'xtremecleans'); ?>">
                                            <span class="dashicons dashicons-visibility"></span>
                                        </button>
                                        <button type="button" 
                                                class="button button-small button-primary sync-to-jobber" 
                                                data-order-id="<?php echo esc_attr($order['id']); ?>"
                                                data-order-status="<?php echo esc_attr($sync_status); ?>"
                                                title="<?php esc_attr_e('Push Quote & Job to Jobber CRM', 'xtremecleans'); ?>">
                                            <span class="dashicons dashicons-cloud-upload"></span>
<<<<<<< HEAD
                                            <span class="button-text"><?php esc_html_e('Push to Jobber', 'xtremecleans'); ?></span>
=======
                                            <span class="button-text"><?php esc_html_e('', 'xtremecleans'); ?></span>
>>>>>>> 3378c4f (plugin last vertation updated in admin dashboard)
                                        </button>
                                        <button type="button" 
                                                class="button button-small button-link-delete delete-order" 
                                                data-order-id="<?php echo esc_attr($order['id']); ?>"
                                                title="<?php esc_attr_e('Delete Order', 'xtremecleans'); ?>">
                                            <span class="dashicons dashicons-trash"></span>
                                        </button>
                                    </div>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        <?php endif; ?>
    </div>
    
    <!-- Order Details Modal -->
    <div id="xtremecleans-order-modal" class="xtremecleans-modal">
        <div class="xtremecleans-modal-content">
            <div class="xtremecleans-modal-header">
                <h2><?php esc_html_e('Order Details', 'xtremecleans'); ?></h2>
                <span class="xtremecleans-modal-close">&times;</span>
            </div>
            <div class="xtremecleans-modal-body" id="xtremecleans-order-details">
                <div class="xtremecleans-loading">
                    <span class="spinner is-active"></span>
                    <p><?php esc_html_e('Loading order details...', 'xtremecleans'); ?></p>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
// Chart data from PHP
var xtremecleansChartData = {
    orders30: <?php echo json_encode($stats['orders_by_date_30']); ?>,
    revenue30: <?php echo json_encode($stats['revenue_by_date']); ?>,
    states: <?php echo json_encode($stats['orders_by_state']); ?>,
    services: <?php echo json_encode($stats['orders_by_service']); ?>
};

// Orders data and nonce
var xtremecleansOrdersData = {
    nonce: '<?php echo esc_js($orders_nonce); ?>',
    ajaxUrl: '<?php echo esc_js(admin_url('admin-ajax.php')); ?>'
};
</script>
