<?php
/**
 * Admin Dashboard Template
 *
 * @package XtremeCleans
 * @subpackage Admin Templates
 * @since 1.0.0
 *
 * @var array $stats Dashboard statistics
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
            <a href="<?php echo esc_url(admin_url('admin.php?page=xtremecleans')); ?>" class="xtremecleans-nav-link active"><?php esc_html_e('Dashboard', 'xtremecleans'); ?></a>
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
            <a href="<?php echo esc_url(admin_url('admin.php?page=xtremecleans-api-test')); ?>" class="xtremecleans-nav-link"><?php esc_html_e('API Test', 'xtremecleans'); ?></a>
        </div>
    </div>
    
    <div class="xtremecleans-dashboard">
        <div class="xtremecleans-stats-grid">
            <div class="xtremecleans-stat-card">
                <div class="stat-icon dashicons dashicons-shortcode"></div>
                <div class="stat-content">
                    <h3><?php echo esc_html($stats['shortcodes_count']); ?></h3>
                    <p><?php esc_html_e('Shortcodes Available', 'xtremecleans'); ?></p>
                </div>
            </div>
            
            <div class="xtremecleans-stat-card">
                <div class="stat-icon dashicons dashicons-admin-links"></div>
                <div class="stat-content">
                    <h3><?php echo $stats['api_configured'] ? esc_html__('Yes', 'xtremecleans') : esc_html__('No', 'xtremecleans'); ?></h3>
                    <p><?php esc_html_e('API Configured', 'xtremecleans'); ?></p>
                </div>
            </div>
            
            <div class="xtremecleans-stat-card">
                <div class="stat-icon dashicons dashicons-email-alt"></div>
                <div class="stat-content">
                    <h3><?php echo esc_html($stats['forms_submitted']); ?></h3>
                    <p><?php esc_html_e('Forms Submitted', 'xtremecleans'); ?></p>
                </div>
            </div>
            
            <div class="xtremecleans-stat-card">
                <div class="stat-icon dashicons dashicons-admin-plugins"></div>
                <div class="stat-content">
                    <h3><?php echo esc_html($stats['plugin_version']); ?></h3>
                    <p><?php esc_html_e('Plugin Version', 'xtremecleans'); ?></p>
                </div>
            </div>
        </div>
        
        <div class="xtremecleans-dashboard-content">
            <div class="xtremecleans-dashboard-main">
                <div class="xtremecleans-widget">
                    <h2><?php esc_html_e('Quick Start', 'xtremecleans'); ?></h2>
                    <p style="color: var(--xtremecleans-gray-600); margin-bottom: var(--xtremecleans-spacing-lg); font-size: 14px;">
                        <?php esc_html_e('Access key features and pages quickly from here.', 'xtremecleans'); ?>
                    </p>
                    <div class="xtremecleans-quick-links">
                        <a href="<?php echo esc_url(admin_url('admin.php?page=xtremecleans-orders')); ?>" class="button button-primary">
                            <?php esc_html_e('View Orders', 'xtremecleans'); ?>
                        </a>
                        <a href="<?php echo esc_url(admin_url('admin.php?page=xtremecleans-zip-zone')); ?>" class="button">
                            <?php esc_html_e('Zip Zone', 'xtremecleans'); ?>
                        </a>
                        <a href="<?php echo esc_url(admin_url('admin.php?page=xtremecleans-service-items')); ?>" class="button">
                            <?php esc_html_e('Service Items', 'xtremecleans'); ?>
                        </a>
                        <a href="<?php echo esc_url(admin_url('admin.php?page=xtremecleans-settings')); ?>" class="button">
                            <?php esc_html_e('Settings', 'xtremecleans'); ?>
                        </a>
                        <a href="<?php echo esc_url(admin_url('admin.php?page=xtremecleans-shortcodes')); ?>" class="button">
                            <?php esc_html_e('Shortcodes', 'xtremecleans'); ?>
                        </a>
                        <a href="<?php echo esc_url(admin_url('admin.php?page=xtremecleans-api-test')); ?>" class="button">
                            <?php esc_html_e('API Test', 'xtremecleans'); ?>
                        </a>
                    </div>
                </div>
                
                <div class="xtremecleans-widget">
                    <h2><?php esc_html_e('Recent Activity', 'xtremecleans'); ?></h2>
                    <div style="padding: var(--xtremecleans-spacing-xl); text-align: center; color: var(--xtremecleans-gray-500);">
                        <span class="dashicons dashicons-chart-line" style="font-size: 48px; width: 48px; height: 48px; margin-bottom: var(--xtremecleans-spacing-md); display: block; opacity: 0.3;"></span>
                        <p style="margin: 0; font-size: 14px;"><?php esc_html_e('Activity log will appear here.', 'xtremecleans'); ?></p>
                    </div>
                </div>
            </div>
            
            <div class="xtremecleans-dashboard-sidebar">
                <div class="xtremecleans-widget">
                    <h2><?php esc_html_e('Navigation', 'xtremecleans'); ?></h2>
                    <div class="xtremecleans-nav-menu">
                        <a href="<?php echo esc_url(admin_url('admin.php?page=xtremecleans')); ?>" class="xtremecleans-nav-item">
                            <span class="dashicons dashicons-dashboard"></span> <?php esc_html_e('Dashboard', 'xtremecleans'); ?>
                        </a>
                        <a href="<?php echo esc_url(admin_url('admin.php?page=xtremecleans-orders')); ?>" class="xtremecleans-nav-item">
                            <span class="dashicons dashicons-cart"></span> <?php esc_html_e('Orders', 'xtremecleans'); ?>
                        </a>
                        <a href="<?php echo esc_url(admin_url('admin.php?page=xtremecleans-zip-zone')); ?>" class="xtremecleans-nav-item">
                            <span class="dashicons dashicons-location"></span> <?php esc_html_e('Zip Zone', 'xtremecleans'); ?>
                        </a>
                        <a href="<?php echo esc_url(admin_url('admin.php?page=xtremecleans-service-items')); ?>" class="xtremecleans-nav-item">
                            <span class="dashicons dashicons-list-view"></span> <?php esc_html_e('Service Items', 'xtremecleans'); ?>
                        </a>
                        <a href="<?php echo esc_url(admin_url('admin.php?page=xtremecleans-settings')); ?>" class="xtremecleans-nav-item">
                            <span class="dashicons dashicons-admin-settings"></span> <?php esc_html_e('Settings', 'xtremecleans'); ?>
                        </a>
                        <a href="<?php echo esc_url(admin_url('admin.php?page=xtremecleans-shortcodes')); ?>" class="xtremecleans-nav-item">
                            <span class="dashicons dashicons-shortcode"></span> <?php esc_html_e('Shortcodes', 'xtremecleans'); ?>
                        </a>
                        <a href="<?php echo esc_url(admin_url('admin.php?page=xtremecleans-api-test')); ?>" class="xtremecleans-nav-item">
                            <span class="dashicons dashicons-admin-links"></span> <?php esc_html_e('API Test', 'xtremecleans'); ?>
                        </a>
                    </div>
                </div>
                
                <div class="xtremecleans-widget">
                    <h2><?php esc_html_e('System Information', 'xtremecleans'); ?></h2>
                    <table class="xtremecleans-info-table">
                        <tr>
                            <td><?php esc_html_e('WordPress Version:', 'xtremecleans'); ?></td>
                            <td><?php echo esc_html(get_bloginfo('version')); ?></td>
                        </tr>
                        <tr>
                            <td><?php esc_html_e('PHP Version:', 'xtremecleans'); ?></td>
                            <td><?php echo esc_html(PHP_VERSION); ?></td>
                        </tr>
                        <tr>
                            <td><?php esc_html_e('Plugin Version:', 'xtremecleans'); ?></td>
                            <td><?php echo esc_html(XTREMECLEANS_VERSION); ?></td>
                        </tr>
                    </table>
                </div>
                
                <div class="xtremecleans-widget">
                    <h2><?php esc_html_e('Need Help?', 'xtremecleans'); ?></h2>
                    <p style="color: var(--xtremecleans-gray-600); margin-bottom: var(--xtremecleans-spacing-md); font-size: 14px; line-height: 1.6;">
                        <?php esc_html_e('Check out the documentation or contact support for assistance.', 'xtremecleans'); ?>
                    </p>
                    <div style="margin-top: var(--xtremecleans-spacing-lg);">
                        <a href="#" class="button" style="width: 100%; text-align: center; justify-content: center; display: inline-flex; align-items: center; gap: var(--xtremecleans-spacing-sm);">
                            <span class="dashicons dashicons-book-alt"></span>
                            <?php esc_html_e('View Documentation', 'xtremecleans'); ?>
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

