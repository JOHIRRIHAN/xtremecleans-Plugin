<?php
/**
 * Admin Settings Template
 *
 * @package XtremeCleans
 * @subpackage Admin Templates
 * @since 1.0.0
 *
 * @var string $active_tab Active settings tab
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
            <a href="<?php echo esc_url(admin_url('admin.php?page=xtremecleans-settings')); ?>" class="xtremecleans-nav-link active"><?php esc_html_e('Settings', 'xtremecleans'); ?></a>
            <span class="xtremecleans-nav-separator">|</span>
            <a href="<?php echo esc_url(admin_url('admin.php?page=xtremecleans-shortcodes')); ?>" class="xtremecleans-nav-link"><?php esc_html_e('Shortcodes', 'xtremecleans'); ?></a>
            <span class="xtremecleans-nav-separator">|</span>
            <a href="<?php echo esc_url(admin_url('admin.php?page=xtremecleans-api-test')); ?>" class="xtremecleans-nav-link"><?php esc_html_e('API Test', 'xtremecleans'); ?></a>
        </div>
    </div>
    
    <nav class="nav-tab-wrapper">
        <a href="?page=xtremecleans-settings&tab=general" class="nav-tab <?php echo $active_tab === 'general' ? 'nav-tab-active' : ''; ?>">
            <?php esc_html_e('General', 'xtremecleans'); ?>
        </a>
        <a href="?page=xtremecleans-settings&tab=jobber" class="nav-tab <?php echo $active_tab === 'jobber' ? 'nav-tab-active' : ''; ?>">
            <?php esc_html_e('Jobber', 'xtremecleans'); ?>
        </a>
        <a href="?page=xtremecleans-settings&tab=payment" class="nav-tab <?php echo $active_tab === 'payment' ? 'nav-tab-active' : ''; ?>">
            <?php esc_html_e('Payment', 'xtremecleans'); ?>
        </a>
        <a href="?page=xtremecleans-settings&tab=email" class="nav-tab <?php echo $active_tab === 'email' ? 'nav-tab-active' : ''; ?>">
            <?php esc_html_e('Email', 'xtremecleans'); ?>
        </a>
        <a href="?page=xtremecleans-settings&tab=display" class="nav-tab <?php echo $active_tab === 'display' ? 'nav-tab-active' : ''; ?>">
            <?php esc_html_e('Display', 'xtremecleans'); ?>
        </a>
    </nav>
    
    <?php settings_errors(); ?>
    
    <form method="post" action="options.php">
        <?php
        if ($active_tab === 'general') {
            settings_fields('xtremecleans_settings_general');
            do_settings_sections('xtremecleans-settings-general');
        } elseif ($active_tab === 'jobber') {
            settings_fields('xtremecleans_settings_jobber');
            do_settings_sections('xtremecleans-settings-jobber');
        } elseif ($active_tab === 'payment') {
            settings_fields('xtremecleans_settings_payment');
            do_settings_sections('xtremecleans-settings-payment');
        } elseif ($active_tab === 'email') {
            settings_fields('xtremecleans_settings_email');
            do_settings_sections('xtremecleans-settings-email');
        } else {
            settings_fields('xtremecleans_settings_display');
            do_settings_sections('xtremecleans-settings-display');
        }
        submit_button();
        ?>
    </form>
</div>

