<?php
/**
 * Admin Shortcodes Template
 *
 * @package XtremeCleans
 * @subpackage Admin Templates
 * @since 1.0.0
 *
 * @var array $shortcodes Shortcodes list
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

<div class="wrap xtremecleans-admin-wrap">
    <div class="xtremecleans-page-header-nav">
        <h1><?php echo esc_html(get_admin_page_title()); ?></h1>
=======
.xtremecleans-page-title {
    font-size: 38px !important;
    font-weight: 600 !important;
    padding-top: 30px !important;
}
</style>

<div class="wrap xtremecleans-admin-wrap">
    <h1 class="xtremecleans-page-title"><?php echo esc_html(get_admin_page_title()); ?></h1>
    <div class="xtremecleans-page-header-nav">

>>>>>>> 3378c4f (plugin last vertation updated in admin dashboard)
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
            <a href="<?php echo esc_url(admin_url('admin.php?page=xtremecleans-shortcodes')); ?>" class="xtremecleans-nav-link active"><?php esc_html_e('Shortcodes', 'xtremecleans'); ?></a>
            <span class="xtremecleans-nav-separator">|</span>
            <a href="<?php echo esc_url(admin_url('admin.php?page=xtremecleans-api-test')); ?>" class="xtremecleans-nav-link"><?php esc_html_e('API Test', 'xtremecleans'); ?></a>
        </div>
    </div>
    
    <div class="xtremecleans-shortcodes-list">
        <?php foreach ($shortcodes as $shortcode): ?>
            <div class="xtremecleans-shortcode-item">
                <h2>[<?php echo esc_html($shortcode['name']); ?>]</h2>
                <p class="description"><?php echo esc_html($shortcode['description']); ?></p>
                
                <div class="xtremecleans-shortcode-example">
                    <strong><?php esc_html_e('Example:', 'xtremecleans'); ?></strong>
                    <code><?php echo esc_html($shortcode['example']); ?></code>
                    <button class="button button-small copy-shortcode" data-shortcode="<?php echo esc_attr($shortcode['example']); ?>">
                        <?php esc_html_e('Copy', 'xtremecleans'); ?>
                    </button>
                </div>
                
                <?php if (!empty($shortcode['attributes'])): ?>
                    <div class="xtremecleans-shortcode-attributes">
                        <strong><?php esc_html_e('Attributes:', 'xtremecleans'); ?></strong>
                        <ul>
                            <?php foreach ($shortcode['attributes'] as $attr => $desc): ?>
                                <li>
                                    <code><?php echo esc_html($attr); ?></code> - <?php echo esc_html($desc); ?>
                                </li>
                            <?php endforeach; ?>
                        </ul>
                    </div>
                <?php endif; ?>
            </div>
        <?php endforeach; ?>
    </div>
</div>

