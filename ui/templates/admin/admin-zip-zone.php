<?php
/**
 * Admin Zip Zone Template
 *
 * @package XtremeCleans
 * @subpackage Admin Templates
 * @since 1.0.0
 *
 * @var array $zip_zones Zip zones data from database
 * @var array $zone_names Unique zone names for dropdown
 * @var array $service_names Unique service names from service items
 */

// Exit if accessed directly
if (!defined('ABSPATH')) {
    exit;
}

$service_names = isset($service_names) && is_array($service_names) ? $service_names : array();
$service_names = array_values(array_unique(array_map('trim', $service_names)));

$zone_names = isset($zone_names) && is_array($zone_names) ? $zone_names : array();
$zone_names = array_values(array_unique(array_map('trim', $zone_names)));

$zone_name_lookup = array();
foreach ($zone_names as $zone_name_label) {
    $slug = strtolower($zone_name_label);
    $zone_name_lookup[$slug] = $zone_name_label;
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

<div class="wrap xtremecleans-zip-zone-page">
    <div class="xtremecleans-page-header-nav">
        <h1><?php echo esc_html(get_admin_page_title()); ?></h1>
        <div class="xtremecleans-page-nav">
            <a href="<?php echo esc_url(admin_url('admin.php?page=xtremecleans')); ?>" class="xtremecleans-nav-link"><?php esc_html_e('Dashboard', 'xtremecleans'); ?></a>
            <span class="xtremecleans-nav-separator">|</span>
            <a href="<?php echo esc_url(admin_url('admin.php?page=xtremecleans-orders')); ?>" class="xtremecleans-nav-link"><?php esc_html_e('Orders', 'xtremecleans'); ?></a>
            <span class="xtremecleans-nav-separator">|</span>
            <a href="<?php echo esc_url(admin_url('admin.php?page=xtremecleans-leads')); ?>" class="xtremecleans-nav-link"><?php esc_html_e('Leads', 'xtremecleans'); ?></a>
            <span class="xtremecleans-nav-separator">|</span>
            <a href="<?php echo esc_url(admin_url('admin.php?page=xtremecleans-zip-zone')); ?>" class="xtremecleans-nav-link active"><?php esc_html_e('Zip Zone', 'xtremecleans'); ?></a>
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
    
    <!-- Add New ZIP Code Form Section -->
    <div class="xtremecleans-add-zip-form-section">
        <h2><?php esc_html_e('Add New ZIP Code', 'xtremecleans'); ?></h2>
        <form id="xtremecleans-zip-form" method="post" action="">
            <?php wp_nonce_field('xtremecleans_add_zip', 'xtremecleans_zip_nonce'); ?>
            <input type="hidden" name="action" value="xtremecleans_add_zip">
            <input type="hidden" name="zone_id" id="zone_id" value="">
            
            <div class="xtremecleans-form-fields-row">
                <div class="xtremecleans-form-field xtremecleans-service-select-field">
                    <label for="service_name"><?php esc_html_e('Service Name', 'xtremecleans'); ?></label>
                    <div class="xtremecleans-service-select-wrapper">
                        <select name="service_name" id="service_name" class="xtremecleans-input"<?php echo empty($service_names) ? ' disabled' : ' required'; ?>>
                            <option value=""><?php esc_html_e('Select service', 'xtremecleans'); ?></option>
                            <?php if (!empty($service_names)) : ?>
                            <?php foreach ($service_names as $name) : ?>
                                <option value="<?php echo esc_attr($name); ?>"><?php echo esc_html($name); ?></option>
                            <?php endforeach; ?>
                            <?php endif; ?>
                        </select>
                        <button type="button" class="button button-small" id="xtremecleans-refresh-services">
                            <?php esc_html_e('Refresh', 'xtremecleans'); ?>
                        </button>
                    </div>
                    <p class="description">
                        <?php esc_html_e('Service names come from the Service Items page. Create or update services there, then refresh this list.', 'xtremecleans'); ?>
                    </p>
                </div>
                
                <div class="xtremecleans-form-field">
                    <label for="zone_name"><?php esc_html_e('Zone Name', 'xtremecleans'); ?></label>
                    <select name="zone_name" id="zone_name" class="xtremecleans-input" <?php echo empty($zone_names) ? 'disabled' : 'required'; ?>>
                        <option value=""><?php esc_html_e('Select Zone', 'xtremecleans'); ?></option>
                        <?php if (!empty($zone_names)) : ?>
                            <?php foreach ($zone_names as $zone_name) : ?>
                                <option value="<?php echo esc_attr(strtolower($zone_name)); ?>">
                                    <?php echo esc_html($zone_name); ?>
                                </option>
                            <?php endforeach; ?>
                        <?php endif; ?>
                    </select>
                    <div class="xtremecleans-zone-name-manager">
                        <input type="text" class="xtremecleans-input" id="xtremecleans-new-zone-name" placeholder="<?php esc_attr_e('e.g., Frederick', 'xtremecleans'); ?>">
                        <button type="button" class="button button-small" id="xtremecleans-add-zone-name-btn">
                            <?php esc_html_e('+ Add Zone Name', 'xtremecleans'); ?>
                        </button>
                    </div>
                    <p class="description"><?php esc_html_e('Zone list is shared everywhere. Add a custom name if it is missing.', 'xtremecleans'); ?></p>
                </div>
                
                <div class="xtremecleans-form-field">
                    <label for="zip_code"><?php esc_html_e('ZIP Code', 'xtremecleans'); ?></label>
                    <input type="text" 
                           name="zip_code" 
                           id="zip_code" 
                           class="xtremecleans-input" 
                           placeholder="12345"
                           maxlength="5" 
                           pattern="[0-9]{5}" 
                           required>
                </div>
                
                <div class="xtremecleans-form-field">
                    <label for="zone_area"><?php esc_html_e('Zone Area', 'xtremecleans'); ?></label>
                    <input type="text" 
                           name="zone_area" 
                           id="zone_area" 
                           class="xtremecleans-input" 
                           placeholder="<?php esc_attr_e('Write area name for this zone', 'xtremecleans'); ?>">
                </div>
                
                <div class="xtremecleans-form-field">
                    <label for="county"><?php esc_html_e('County', 'xtremecleans'); ?></label>
                    <input type="text"
                           name="county"
                           id="county"
                           class="xtremecleans-input"
                           placeholder="<?php esc_attr_e('Enter county name', 'xtremecleans'); ?>">
                </div>
                
                <div class="xtremecleans-form-field">
                    <label for="state"><?php esc_html_e('State', 'xtremecleans'); ?></label>
                    <input type="text"
                           name="state"
                           id="state"
                           class="xtremecleans-input"
                           placeholder="<?php esc_attr_e('Enter state (e.g., MD)', 'xtremecleans'); ?>"
                           maxlength="50">
                </div>
                
                <div class="xtremecleans-form-field">
                    <label for="service_fee"><?php esc_html_e('Service Fee ($)', 'xtremecleans'); ?></label>
                    <input type="text" 
                           name="service_fee" 
                           id="service_fee" 
                           class="xtremecleans-input" 
                           placeholder="<?php esc_attr_e('Enter service fee', 'xtremecleans'); ?>">
                </div>
                
                <div class="xtremecleans-form-field xtremecleans-form-submit-field">
                    <label>&nbsp;</label>
                    <button type="submit" name="submit" id="submit" class="button button-primary xtremecleans-add-zone-btn">
                        <?php esc_html_e('Add New Zone', 'xtremecleans'); ?>
                    </button>
                </div>
            </div>
        </form>
        
        <div class="xtremecleans-service-preview" id="xtremecleans-service-preview">
            <div class="xtremecleans-service-preview-header">
                <div>
                    <h3><?php esc_html_e('Service Items Preview', 'xtremecleans'); ?></h3>
                    <p class="description"><?php esc_html_e('Select a service to see all of its items and pricing.', 'xtremecleans'); ?></p>
                </div>
                <span class="xtremecleans-service-preview-name" id="xtremecleans-service-preview-name">—</span>
            </div>
            <div class="xtremecleans-service-preview-body" id="xtremecleans-service-preview-body">
                <p class="xtremecleans-service-preview-empty"><?php esc_html_e('Choose a service above to preview its items.', 'xtremecleans'); ?></p>
            </div>
        </div>
    </div>
    
    <!-- ZIP Zones Table Section -->
    <div class="xtremecleans-zip-zones-table-section">
        <table class="xtremecleans-zones-table">
            <thead>
                <tr>
                    <th class="column-all-zone"><?php esc_html_e('All Zone', 'xtremecleans'); ?></th>
                    <th class="column-service-name"><?php esc_html_e('Service Name', 'xtremecleans'); ?></th>
                    <th class="column-zone-name"><?php esc_html_e('Zone Name', 'xtremecleans'); ?></th>
                    <th class="column-zip-code"><?php esc_html_e('ZIP Code', 'xtremecleans'); ?></th>
                    <th class="column-zone-area"><?php esc_html_e('Zone Area', 'xtremecleans'); ?></th>
                    <th class="column-county"><?php esc_html_e('County', 'xtremecleans'); ?></th>
                    <th class="column-state"><?php esc_html_e('State', 'xtremecleans'); ?></th>
                    <th class="column-fee"><?php esc_html_e('Fee', 'xtremecleans'); ?></th>
                    <th class="column-actions"><?php esc_html_e('Actions', 'xtremecleans'); ?></th>
                </tr>
            </thead>
            <tbody>
                <?php if (empty($zip_zones)) : ?>
                    <tr>
                        <td colspan="7" class="xtremecleans-no-data">
                            <?php esc_html_e('No zip zones found.', 'xtremecleans'); ?>
                        </td>
                    </tr>
                <?php else : ?>
                    <?php foreach ($zip_zones as $zone) : ?>
                        <?php
                            $zone_slug = isset($zone['zone_name']) ? strtolower($zone['zone_name']) : '';
                            $zone_display_name = '-';
                            if ($zone_slug && isset($zone_name_lookup[$zone_slug])) {
                                $zone_display_name = $zone_name_lookup[$zone_slug];
                            } elseif (!empty($zone['zone_name'])) {
                                $zone_display_name = ucwords($zone['zone_name']);
                            }
                            $zone_county = !empty($zone['county']) ? $zone['county'] : '-';
                            $zone_state = !empty($zone['state']) ? strtoupper($zone['state']) : '-';
                        ?>
                        <tr data-zone-id="<?php echo esc_attr($zone['id']); ?>">
                            <td class="column-all-zone"><?php echo esc_html($zone['id']); ?></td>
                            <td class="column-service-name"><?php echo esc_html(isset($zone['service_name']) && $zone['service_name'] ? $zone['service_name'] : '-'); ?></td>
                            <td class="column-zone-name" data-zone-value="<?php echo esc_attr($zone_slug); ?>">
                                <strong><?php echo esc_html($zone_display_name); ?></strong>
                            </td>
                            <td class="column-zip-code"><?php echo esc_html($zone['zip_code']); ?></td>
                            <td class="column-zone-area"><?php echo esc_html($zone['zone_area']); ?></td>
                            <td class="column-county"><?php echo esc_html($zone_county); ?></td>
                            <td class="column-state"><?php echo esc_html($zone_state); ?></td>
                            <td class="column-fee">
                                <strong>$<?php echo esc_html(number_format((float)$zone['service_fee'], 2)); ?></strong>
                            </td>
                            <td class="column-actions">
                                <button type="button" class="xtremecleans-edit-btn" data-id="<?php echo esc_attr($zone['id']); ?>">
                                    <?php esc_html_e('Edit', 'xtremecleans'); ?>
                                </button>
                                <button type="button" class="xtremecleans-delete-btn" data-id="<?php echo esc_attr($zone['id']); ?>">
                                    <?php esc_html_e('Delete', 'xtremecleans'); ?>
                                </button>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                <?php endif; ?>
            </tbody>
        </table>
        
        <!-- Footer Section -->
        <div class="xtremecleans-table-footer">
            <div class="xtremecleans-total-count">
                <strong><?php esc_html_e('Total:', 'xtremecleans'); ?> <?php echo esc_html(count($zip_zones)); ?> <?php esc_html_e('ZIP code', 'xtremecleans'); ?></strong>
            </div>
            <div class="xtremecleans-footer-actions">
                <!-- Export Button -->
                <a href="<?php echo esc_url(wp_nonce_url(admin_url('admin-post.php?action=xtremecleans_export_zip_zones'), 'xtremecleans_export_zip_zones')); ?>" class="button button-secondary" id="xtremecleans-export-data">
                    <?php esc_html_e('Export Data', 'xtremecleans'); ?>
                </a>
                
                <!-- Import Button -->
                <button type="button" class="button button-secondary" id="xtremecleans-import-trigger">
                    <?php esc_html_e('Import Data', 'xtremecleans'); ?>
                </button>
                
                <!-- Clear All Button -->
                <button type="button" class="button xtremecleans-clear-all-btn" id="xtremecleans-clear-all-data">
                    <?php esc_html_e('Clear All Data', 'xtremecleans'); ?>
                </button>
            </div>
        </div>
        
        <!-- Import Form (Hidden) -->
        <div id="xtremecleans-import-form" style="display: none; margin-top: 20px; padding: 20px; background: #fff; border: 1px solid #c3c4c7; box-shadow: 0 1px 1px rgba(0,0,0,.04);">
            <h3><?php esc_html_e('Import Zip Zone Data', 'xtremecleans'); ?></h3>
            <form method="post" action="<?php echo esc_url(admin_url('admin-post.php')); ?>" enctype="multipart/form-data">
                <?php wp_nonce_field('xtremecleans_import_zip_zones'); ?>
                <input type="hidden" name="action" value="xtremecleans_import_zip_zones">
                
                <p>
                    <label>
                        <input type="file" name="zip_zones_file" accept=".json" required>
                        <span class="description"><?php esc_html_e('Select JSON file exported from another site', 'xtremecleans'); ?></span>
                    </label>
                </p>
                
                <p>
                    <label>
                        <input type="checkbox" name="clear_existing" value="1">
                        <?php esc_html_e('Clear existing data before importing', 'xtremecleans'); ?>
                    </label>
                </p>
                
                <p>
                    <button type="submit" class="button button-primary"><?php esc_html_e('Import', 'xtremecleans'); ?></button>
                    <button type="button" class="button" id="xtremecleans-cancel-import"><?php esc_html_e('Cancel', 'xtremecleans'); ?></button>
                </p>
            </form>
        </div>
        
        <?php
        // Show import messages
        if (isset($_GET['import'])) {
            $import_status = sanitize_text_field($_GET['import']);
            $message_class = 'notice-success';
            $message = '';
            
            if ($import_status === 'success') {
                $imported = isset($_GET['imported']) ? intval($_GET['imported']) : 0;
                $skipped = isset($_GET['skipped']) ? intval($_GET['skipped']) : 0;
                $errors = isset($_GET['errors']) ? intval($_GET['errors']) : 0;
                $message = sprintf(
                    __('Import completed: %d imported, %d skipped, %d errors.', 'xtremecleans'),
                    $imported,
                    $skipped,
                    $errors
                );
            } elseif ($import_status === 'error') {
                $message_class = 'notice-error';
                $message = __('Error uploading file. Please try again.', 'xtremecleans');
            } elseif ($import_status === 'invalid') {
                $message_class = 'notice-error';
                $message = __('Invalid file format. Please upload a valid JSON file.', 'xtremecleans');
            }
            
            if ($message) {
                echo '<div class="notice ' . esc_attr($message_class) . ' is-dismissible"><p>' . esc_html($message) . '</p></div>';
            }
        }
        ?>
    </div>
</div>

<style>
/* Page Background */
.xtremecleans-zip-zone-page {
    margin: 0 0 0 -20px;
    padding: 32px 32px 64px;
    min-height: calc(100vh - 32px);
    background: linear-gradient(135deg, #f5f7ff 0%, #f0fbff 45%, #f6f4ff 100%);
    font-family: "Inter", "Segoe UI", sans-serif;
}

.xtremecleans-zip-zone-page .xtremecleans-add-zip-form-section,
.xtremecleans-zip-zone-page .xtremecleans-zip-zones-table-section {
    max-width: 1360px;
    width: 100%;
    margin: 0 auto;
}

.xtremecleans-zip-zone-page h1 {
    margin: 0 auto 26px;
    max-width: 1360px;
    font-size: 28px;
    font-weight: 600;
    color: #101828;
}

/* Form Section */
.xtremecleans-add-zip-form-section {
    position: relative;
    background: #ffffff;
    border-radius: 20px;
    padding: 32px 36px;
    box-shadow: 0 18px 45px rgba(15, 23, 42, 0.12);
    border: 1px solid rgba(15, 23, 42, 0.08);
    margin-bottom: 32px;
    overflow: hidden;
}

.xtremecleans-add-zip-form-section::after {
    content: "";
    position: absolute;
    inset: 0;
    pointer-events: none;
    border-radius: 20px;
    background: linear-gradient(120deg, rgba(79,70,229,0.08), rgba(14,165,233,0.08), rgba(109,40,217,0.08));
    z-index: 0;
}

.xtremecleans-add-zip-form-section > * {
    position: relative;
    z-index: 1;
}

.xtremecleans-add-zip-form-section h2 {
    margin: 0 0 24px 0;
    font-size: 20px;
    font-weight: 600;
    color: #0f172a;
    display: flex;
    align-items: center;
    gap: 12px;
}

.xtremecleans-add-zip-form-section h2::after {
    content: "";
    flex: 1;
    height: 1px;
    background: linear-gradient(90deg, rgba(15, 118, 255, 0.3), transparent);
}

.xtremecleans-form-fields-row {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(230px, 1fr));
    gap: 20px;
    align-items: flex-end;
}

.xtremecleans-form-field {
    min-width: 0;
}

.xtremecleans-service-select-field {
    grid-column: 1 / -1;
}

.xtremecleans-service-select-wrapper {
    display: flex;
    gap: 12px;
    align-items: center;
    background: rgba(15,23,42,0.03);
    padding: 12px;
    border-radius: 14px;
    border: 1px solid rgba(148, 163, 184, 0.35);
}

.xtremecleans-service-select-wrapper select {
    flex: 1;
}

#xtremecleans-refresh-services {
    flex: 0 0 auto;
    border-radius: 999px;
    border: none;
    padding: 8px 16px;
    font-weight: 600;
    background: #e0f2fe;
    color: #0369a1;
    box-shadow: inset 0 1px 0 rgba(255,255,255,0.7);
}

.xtremecleans-zone-name-manager {
    display: flex;
    gap: 12px;
    margin-top: 8px;
}

.xtremecleans-zone-name-manager .xtremecleans-input {
    flex: 1;
}

.xtremecleans-zone-name-manager .button {
    flex: 0 0 auto;
    border-radius: 10px;
    font-weight: 600;
}

.xtremecleans-form-field label {
    display: block;
    margin-bottom: 6px;
    font-weight: 600;
    font-size: 13px;
    letter-spacing: 0.02em;
    text-transform: uppercase;
    color: #475467;
}

.xtremecleans-form-field .required {
    color: #d63638;
}

.xtremecleans-input {
    width: 100%;
    padding: 12px 14px;
    font-size: 15px;
    color: #0f172a;
    border: 1px solid rgba(15, 23, 42, 0.12);
    border-radius: 12px;
    background-color: #fff;
    box-shadow: 0 0 0 transparent;
    transition: border-color 0.2s ease, box-shadow 0.2s ease, transform 0.2s ease;
}

.xtremecleans-input:focus {
    border-color: #2563eb;
    box-shadow: 0 0 0 3px rgba(37, 99, 235, 0.15);
    outline: none;
    transform: translateY(-1px);
}

.xtremecleans-form-submit-field {
    min-width: 200px;
}

.xtremecleans-add-zone-btn {
    border: none;
    border-radius: 999px;
    padding: 14px 30px;
    font-size: 15px;
    font-weight: 600;
    background: linear-gradient(135deg, #2563eb, #7c3aed);
    color: #fff;
    box-shadow: 0 12px 25px rgba(79,70,229,0.35);
    transition: transform 0.2s ease, box-shadow 0.2s ease;
}

.xtremecleans-add-zone-btn:hover {
    transform: translateY(-2px);
    box-shadow: 0 16px 30px rgba(79,70,229,0.45);
}

.xtremecleans-add-zone-btn:disabled {
    opacity: 0.6;
    cursor: not-allowed;
    box-shadow: none;
}

.xtremecleans-service-preview {
    margin-top: 28px;
    border-radius: 18px;
    background: linear-gradient(180deg, #f8fafc 0%, #ffffff 70%);
    box-shadow: inset 0 1px 0 rgba(255,255,255,0.6), 0 12px 28px rgba(15,23,42,0.08);
    border: 1px solid rgba(148, 163, 184, 0.35);
}

.xtremecleans-service-preview-header {
    display: flex;
    justify-content: space-between;
    align-items: center;
    padding: 22px 26px;
    border-bottom: 1px solid rgba(148,163,184,0.35);
    background: linear-gradient(90deg, rgba(79,70,229,0.05), transparent);
}

.xtremecleans-service-preview-header h3 {
    margin: 0;
    font-size: 17px;
    color: #0f172a;
}

.xtremecleans-service-preview-header .description {
    margin: 6px 0 0;
    font-size: 13px;
    color: #6b7280;
}

.xtremecleans-service-preview-name {
    font-weight: 600;
    color: #2563eb;
    background: #e0f2fe;
    padding: 6px 16px;
    border-radius: 999px;
}

.xtremecleans-service-preview-body {
    padding: 0;
}

.xtremecleans-service-preview-empty {
    margin: 0;
    padding: 24px;
    text-align: center;
    color: #6b7280;
}

.xtremecleans-service-preview-items {
    display: flex;
    flex-direction: column;
}

.xtremecleans-service-preview-item {
    display: grid;
    grid-template-columns: minmax(180px, 2fr) minmax(200px, 3fr) repeat(3, minmax(110px, 1fr));
    gap: 16px;
    padding: 18px 26px;
    border-bottom: 1px solid rgba(226,232,240,0.8);
}

.xtremecleans-service-preview-item:last-child {
    border-bottom: none;
}

.xtremecleans-service-preview-item h4 {
    margin: 0;
    font-size: 15px;
    color: #0f172a;
}

.xtremecleans-service-preview-price {
    font-weight: 600;
    color: #0f172a;
}

.xtremecleans-service-preview-item .description {
    color: #6b7280;
    font-size: 13px;
    margin: 0;
}

.xtremecleans-service-preview-loading {
    padding: 24px;
    text-align: center;
    color: #374151;
    font-weight: 600;
}

/* Table Section */
/* Table Section */
.xtremecleans-zip-zones-table-section {
    background: #fff;
    border-radius: 20px;
    border: 1px solid rgba(15, 23, 42, 0.08);
    box-shadow: 0 22px 45px rgba(15,23,42,0.12);
    padding: 30px 34px 36px;
}

.xtremecleans-zones-table {
    width: 100%;
    border-collapse: separate;
    border-spacing: 0;
    margin: 0;
}

.xtremecleans-zones-table thead {
    background: linear-gradient(90deg, rgba(15,118,255,0.12), rgba(14,165,233,0.12));
}

.xtremecleans-zones-table th {
    padding: 14px 18px;
    text-align: left;
    font-weight: 600;
    font-size: 12px;
    letter-spacing: 0.05em;
    text-transform: uppercase;
    color: #475467;
    border-bottom: 1px solid rgba(148,163,184,0.45);
}

.xtremecleans-zones-table th:first-child {
    border-top-left-radius: 14px;
}

.xtremecleans-zones-table th:last-child {
    border-top-right-radius: 14px;
}

.xtremecleans-zones-table td {
    padding: 18px;
    font-size: 14px;
    color: #0f172a;
    border-bottom: 1px solid rgba(226, 232, 240, 0.7);
    vertical-align: middle;
}

.xtremecleans-zones-table tbody tr {
    transition: background-color 0.3s ease, transform 0.2s ease;
}

.xtremecleans-zones-table tbody tr:hover {
    background-color: rgba(59, 130, 246, 0.06);
}

.xtremecleans-zones-table tbody tr.editing {
    background-color: rgba(251, 191, 36, 0.18);
    border-left: 3px solid #f59e0b;
}

.xtremecleans-zones-table .column-all-zone {
    width: 90px;
    text-align: center;
}

.xtremecleans-zones-table .column-service-name {
    width: 160px;
}

.xtremecleans-zones-table .column-zone-name {
    width: 150px;
}

.xtremecleans-zones-table .column-zip-code {
    width: 120px;
    text-align: center;
}

.xtremecleans-zones-table .column-zone-area {
    width: 210px;
}

.xtremecleans-zones-table .column-county {
    width: 170px;
}

.xtremecleans-zones-table .column-state {
    width: 110px;
    text-align: center;
}

.xtremecleans-zones-table .column-fee {
    width: 140px;
    text-align: right;
}

.xtremecleans-zones-table .column-actions {
    width: 160px;
    text-align: center;
    white-space: nowrap;
}

.xtremecleans-edit-btn,
.xtremecleans-delete-btn,
.xtremecleans-save-inline-btn,
.xtremecleans-cancel-inline-btn {
    margin: 0 4px;
    padding: 8px 18px;
    font-size: 13px;
    font-weight: 600;
    border-radius: 10px;
    border: none;
    cursor: pointer;
    transition: transform 0.2s ease, box-shadow 0.2s ease;
}

.xtremecleans-edit-btn,
.xtremecleans-save-inline-btn {
    background: linear-gradient(135deg, #2563eb, #3b82f6);
    color: #fff;
    box-shadow: 0 10px 20px rgba(37, 99, 235, 0.25);
}

.xtremecleans-edit-btn:hover,
.xtremecleans-save-inline-btn:hover {
    transform: translateY(-1px);
    box-shadow: 0 14px 24px rgba(37, 99, 235, 0.35);
}

.xtremecleans-delete-btn,
.xtremecleans-cancel-inline-btn {
    background: #fff;
    color: #dc2626;
    border: 1px solid rgba(220, 38, 38, 0.4);
    box-shadow: 0 6px 15px rgba(220, 38, 38, 0.12);
}

.xtremecleans-delete-btn:hover,
.xtremecleans-cancel-inline-btn:hover {
    background: #dc2626;
    color: #fff;
    border-color: #dc2626;
    box-shadow: 0 12px 22px rgba(220, 38, 38, 0.3);
}

.xtremecleans-inline-edit {
    border-radius: 10px;
    border: 1px solid rgba(37, 99, 235, 0.5);
    padding: 6px 10px;
    font-size: 13px;
}

.xtremecleans-zones-table tbody tr.editing-mode {
    background-color: rgba(251, 191, 36, 0.18);
    border-left: 3px solid #f59e0b;
}

.xtremecleans-no-data {
    text-align: center;
    padding: 36px !important;
    color: #646970;
}

/* Footer */
.xtremecleans-table-footer {
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin-top: 30px;
    padding-top: 24px;
    border-top: 1px dashed rgba(148, 163, 184, 0.5);
}

.xtremecleans-total-count {
    font-size: 15px;
    font-weight: 600;
    color: #0f172a;
}

.xtremecleans-footer-actions {
    display: flex;
    gap: 14px;
    flex-wrap: wrap;
}

.xtremecleans-footer-actions .button {
    border-radius: 999px;
    padding: 10px 18px;
    box-shadow: 0 8px 18px rgba(15,23,42,0.08);
}

.xtremecleans-clear-all-btn {
    background: linear-gradient(135deg, #f97316, #dc2626);
    border: none;
    color: #fff;
    font-weight: 600;
    box-shadow: 0 12px 24px rgba(249, 115, 22, 0.35);
}

.xtremecleans-clear-all-btn:hover {
    transform: translateY(-1px);
}

/* Responsive */
@media (max-width: 1200px) {
    .xtremecleans-add-zip-form-section,
    .xtremecleans-zip-zones-table-section {
        padding: 26px;
    }
}

@media (max-width: 782px) {
    .xtremecleans-zip-zone-page {
        margin: 0 0 0 -12px;
        padding: 18px;
    }
    
    .xtremecleans-add-zip-form-section,
    .xtremecleans-zip-zones-table-section {
        padding: 20px;
    }
    
    .xtremecleans-form-fields-row {
        grid-template-columns: 1fr;
    }
    
    .xtremecleans-zone-name-manager {
        flex-direction: column;
    }
    
    .xtremecleans-zone-name-manager .button {
        width: 100%;
    }
    
    .xtremecleans-service-preview-item {
        grid-template-columns: 1fr;
    }
    
    .xtremecleans-zones-table th,
    .xtremecleans-zones-table td {
        padding: 12px;
    }
    
    .xtremecleans-table-footer {
        flex-direction: column;
        align-items: flex-start;
        gap: 16px;
    }
    
    .xtremecleans-footer-actions {
        width: 100%;
        justify-content: flex-start;
    }
}
</style>

<script>
jQuery(document).ready(function($) {
    var ajaxUrl = '<?php echo esc_js(admin_url('admin-ajax.php')); ?>';
    var nonce = '<?php echo esc_js(wp_create_nonce('xtremecleans_add_zip')); ?>';
    var zoneNames = <?php echo json_encode($zone_names ? $zone_names : array()); ?>;
    var serviceNames = <?php echo json_encode(array_values($service_names)); ?>;
    var serviceSelectPlaceholder = '<?php echo esc_js(__('Select service', 'xtremecleans')); ?>';
    var serviceSelectEmptyText = '<?php echo esc_js(__('No services available yet. Please add service items first.', 'xtremecleans')); ?>';
    var serviceSelectRequiredMessage = '<?php echo esc_js(__('Please select a service name before adding a ZIP code.', 'xtremecleans')); ?>';
    var serviceSelectDisabledMessage = '<?php echo esc_js(__('Please create a service under Service Items before adding ZIP data.', 'xtremecleans')); ?>';
    var servicePreviewEmptyText = '<?php echo esc_js(__('Choose a service above to preview its items.', 'xtremecleans')); ?>';
    var servicePreviewNoItemsText = '<?php echo esc_js(__('No items found for this service yet.', 'xtremecleans')); ?>';
    var servicePreviewLoadingText = '<?php echo esc_js(__('Loading service items...', 'xtremecleans')); ?>';
    var zoneNamePlaceholder = '<?php echo esc_js(__('Select Zone', 'xtremecleans')); ?>';
    var zoneNameAddEmptyMessage = '<?php echo esc_js(__('Please enter a Zone Name.', 'xtremecleans')); ?>';
    var zoneNameAddExistsMessage = '<?php echo esc_js(__('Zone Name already exists.', 'xtremecleans')); ?>';
    var zoneNameAddSuccessMessage = '<?php echo esc_js(__('Zone Name added successfully.', 'xtremecleans')); ?>';
    var zoneNameAddBtnLabel = '<?php echo esc_js(__('+ Add Zone Name', 'xtremecleans')); ?>';
    var zoneNameAddBtnProcessing = '<?php echo esc_js(__('Adding...', 'xtremecleans')); ?>';
    var priceLabelClean = '<?php echo esc_js(__('Clean', 'xtremecleans')); ?>';
    var priceLabelProtect = '<?php echo esc_js(__('Protect', 'xtremecleans')); ?>';
    var priceLabelDeodorize = '<?php echo esc_js(__('Deodorize', 'xtremecleans')); ?>';
    
    var $serviceSelect = $('#service_name');
    var $zoneSelect = $('#zone_name');
    var $zoneNameInput = $('#xtremecleans-new-zone-name');
    var $zoneNameAddBtn = $('#xtremecleans-add-zone-name-btn');
    var $serviceRefreshBtn = $('#xtremecleans-refresh-services');
    var $servicePreview = $('#xtremecleans-service-preview');
    var $servicePreviewName = $('#xtremecleans-service-preview-name');
    var $servicePreviewBody = $('#xtremecleans-service-preview-body');
    var zoneNameLookup = {};
    
    function escapeHtml(value) {
        return $('<div>').text(value || '').html();
    }
    
    function normalizeZoneValue(value) {
        return value ? String(value).trim().toLowerCase() : '';
    }
    
    function rebuildZoneNameLookup() {
        zoneNameLookup = {};
        (zoneNames || []).forEach(function(name) {
            var slug = normalizeZoneValue(name);
            if (slug) {
                zoneNameLookup[slug] = name;
            }
        });
    }
    
    function titleCase(value) {
        return value ? value.replace(/\b\w/g, function(chr) { return chr.toUpperCase(); }) : '';
    }
    
    function formatZoneLabel(value) {
        var slug = normalizeZoneValue(value);
        if (slug && zoneNameLookup[slug]) {
            return zoneNameLookup[slug];
        }
        if (!value) {
            return '-';
        }
        return titleCase(value);
    }
    
    function buildZoneNameOptions(selectedValue) {
        var options = '<option value="">' + zoneNamePlaceholder + '</option>';
        if (zoneNames && zoneNames.length) {
            zoneNames.forEach(function(name) {
                if (!name) {
                    return;
                }
                var slug = normalizeZoneValue(name);
                var selectedAttr = selectedValue && normalizeZoneValue(selectedValue) === slug ? ' selected' : '';
                options += '<option value="' + escapeHtml(slug) + '"' + selectedAttr + '>' + escapeHtml(name) + '</option>';
            });
        }
        return options;
    }
    
    function populateZoneNameSelect($select, selectedValue) {
        if (!$select || !$select.length) {
            return;
        }
        if (!zoneNames.length) {
            $select.html('<option value="">' + zoneNamePlaceholder + '</option>').prop('disabled', true).removeAttr('required');
            return;
        }
        $select.html(buildZoneNameOptions(selectedValue)).prop('disabled', false).attr('required', 'required');
    }
    
    function createZoneSelectElement(selectedValue) {
        var $select = $('<select class="xtremecleans-inline-edit" data-field="zone_name" style="width: 100%; padding: 4px;"></select>');
        populateZoneNameSelect($select, selectedValue);
        return $select;
    }
    
    function populateServiceDropdown($select, selectedValue, includePlaceholder) {
        if (!$select || !$select.length) {
            return;
        }
        includePlaceholder = includePlaceholder !== false;
        selectedValue = selectedValue || '';
        
        $select.empty();
        
        if (!serviceNames.length) {
            $select.append('<option value="">' + serviceSelectEmptyText + '</option>');
            $select.prop('disabled', true);
            return;
        }
        
        $select.prop('disabled', false);
        if (includePlaceholder) {
            $select.append('<option value="">' + serviceSelectPlaceholder + '</option>');
        }
        
        serviceNames.forEach(function(name) {
            if (!name) {
                return;
            }
            var option = $('<option>', { value: name, text: name });
            if (selectedValue && name.toLowerCase() === selectedValue.toLowerCase()) {
                option.attr('selected', 'selected');
            }
            $select.append(option);
        });
        
        if (selectedValue && !$select.val()) {
            $select.append($('<option>', { value: selectedValue, text: selectedValue, selected: true }));
        }
    }
    
    function createServiceSelect(selectedValue) {
        var $select = $('<select class="xtremecleans-inline-edit" data-field="service_name" style="width: 100%; padding: 4px;"></select>');
        populateServiceDropdown($select, selectedValue, true);
        return $select;
    }
    
    function resetServicePreview() {
        $servicePreviewName.text('—');
        $servicePreviewBody.html('<p class="xtremecleans-service-preview-empty">' + servicePreviewEmptyText + '</p>');
    }
    
    function renderServicePreview(serviceName, items) {
        $servicePreviewName.text(serviceName || '—');
        
        if (!items || !items.length) {
            $servicePreviewBody.html('<p class="xtremecleans-service-preview-empty">' + servicePreviewNoItemsText + '</p>');
            return;
        }
        
        var html = '<div class="xtremecleans-service-preview-items">';
        items.forEach(function(item) {
            var itemName = escapeHtml(item.item_name || '');
            var itemDesc = escapeHtml(item.item_description || '-');
            var cleanPrice = '$' + parseFloat(item.price1_value || 0).toFixed(2);
            var protectPrice = '$' + parseFloat(item.price2_value || 0).toFixed(2);
            var deodorizePrice = '$' + parseFloat(item.price3_value || 0).toFixed(2);
            
            html += '<div class="xtremecleans-service-preview-item">' +
                '<div><h4>' + (itemName || '—') + '</h4></div>' +
                '<div class="description">' + (itemDesc || '-') + '</div>' +
                '<div class="xtremecleans-service-preview-price" data-label="' + priceLabelClean + '">' + cleanPrice + '</div>' +
                '<div class="xtremecleans-service-preview-price" data-label="' + priceLabelProtect + '">' + protectPrice + '</div>' +
                '<div class="xtremecleans-service-preview-price" data-label="' + priceLabelDeodorize + '">' + deodorizePrice + '</div>' +
                '</div>';
        });
        html += '</div>';
        $servicePreviewBody.html(html);
    }
    
    function loadServicePreview(serviceName) {
        if (!serviceName) {
            resetServicePreview();
            return;
        }
        
        $servicePreviewBody.html('<p class="xtremecleans-service-preview-loading">' + servicePreviewLoadingText + '</p>');
        
        $.ajax({
            url: ajaxUrl,
            type: 'POST',
            data: {
                action: 'xtremecleans_get_service_items',
                nonce: nonce,
                service_name: serviceName
            },
            success: function(response) {
                if (response.success && response.data && response.data.service_items) {
                    renderServicePreview(serviceName, response.data.service_items);
                } else {
                    renderServicePreview(serviceName, []);
                }
            },
            error: function() {
                $servicePreviewBody.html('<p class="xtremecleans-service-preview-empty"><?php echo esc_js(__('Unable to load service items. Please try again.', 'xtremecleans')); ?></p>');
            }
        });
    }
    
    function refreshServiceNames(callback) {
        if (!$serviceRefreshBtn.length) {
            return;
        }
        var originalText = $serviceRefreshBtn.data('label') || $serviceRefreshBtn.text();
        $serviceRefreshBtn.data('label', originalText).prop('disabled', true).text('<?php echo esc_js(__('Refreshing...', 'xtremecleans')); ?>');
        
        $.ajax({
            url: ajaxUrl,
            type: 'POST',
            data: {
                action: 'xtremecleans_get_service_names',
                nonce: nonce
            },
            success: function(response) {
                if (response.success && response.data && response.data.service_names) {
                    serviceNames = response.data.service_names;
                    populateServiceDropdown($serviceSelect, '');
                    $('.xtremecleans-inline-service-select').each(function() {
                        var current = $(this).val();
                        populateServiceDropdown($(this), current, true);
                    });
                    if (typeof callback === 'function') {
                        callback();
                    }
                }
            },
            complete: function() {
                $serviceRefreshBtn.prop('disabled', false).text(originalText);
            }
        });
    }
    
    function createServiceSelect(selectedValue) {
        selectedValue = selectedValue || '';
        if (!serviceNames.length) {
            return $('<input>', {
                type: 'text',
                'class': 'xtremecleans-inline-edit',
                'data-field': 'service_name',
                style: 'width: 100%; padding: 4px;',
                value: selectedValue,
                placeholder: '<?php echo esc_js(__('Enter service name', 'xtremecleans')); ?>'
            });
        }
        var $select = $('<select class="xtremecleans-inline-edit xtremecleans-inline-service-select" data-field="service_name" style="width: 100%; padding: 4px;"></select>');
        populateServiceDropdown($select, selectedValue, true);
        return $select;
    }
    
    setZoneNames(zoneNames, '');
    populateServiceDropdown($serviceSelect, '');
    resetServicePreview();
    
    $serviceSelect.on('change', function() {
        var selected = $(this).val();
        loadServicePreview(selected);
    });
    
    $serviceRefreshBtn.on('click', function() {
        refreshServiceNames(function() {
            loadServicePreview($serviceSelect.val());
        });
    });
    
    function setZoneNames(newNames, selectedValue) {
        zoneNames = Array.isArray(newNames) ? newNames.filter(Boolean) : [];
        if (zoneNames.length) {
            zoneNames.sort(function(a, b) {
                return a.toLowerCase().localeCompare(b.toLowerCase());
            });
        }
        rebuildZoneNameLookup();
        populateZoneNameSelect($zoneSelect, typeof selectedValue !== 'undefined' ? selectedValue : $zoneSelect.val());
        $('.xtremecleans-inline-edit[data-field="zone_name"]').each(function() {
            var current = $(this).val();
            populateZoneNameSelect($(this), current);
        });
    }
    
    $zoneNameAddBtn.on('click', function() {
        var newZoneName = $zoneNameInput.val().trim();
        if (!newZoneName.length) {
            showNotice(zoneNameAddEmptyMessage, 'error');
            return;
        }
        
        var originalLabel = $zoneNameAddBtn.data('label') || $zoneNameAddBtn.text();
        $zoneNameAddBtn.data('label', originalLabel).prop('disabled', true).text(zoneNameAddBtnProcessing);
        
        $.ajax({
            url: ajaxUrl,
            type: 'POST',
            data: {
                action: 'xtremecleans_add_zone_name',
                nonce: nonce,
                zone_name: newZoneName
            },
            success: function(response) {
                if (response.success && response.data && response.data.zone_names) {
                    var slugValue = normalizeZoneValue(newZoneName);
                    setZoneNames(response.data.zone_names, slugValue);
                    $zoneSelect.val(slugValue);
                    $zoneNameInput.val('');
                    showNotice(response.data.message || zoneNameAddSuccessMessage, 'success');
                } else {
                    showNotice(response.data && response.data.message ? response.data.message : zoneNameAddExistsMessage, 'error');
                }
            },
            error: function() {
                showNotice(zoneNameAddExistsMessage, 'error');
            },
            complete: function() {
                $zoneNameAddBtn.prop('disabled', false).text(originalLabel);
            }
        });
    });
    
    // Form submission
    $('#xtremecleans-zip-form').on('submit', function(e) {
        e.preventDefault();
        
        var zoneId = $('#zone_id').val();
        var isUpdate = zoneId ? true : false;
        var $form = $(this);
        var $submitBtn = $('#submit');
        var originalText = $submitBtn.text();
        
        // Disable button during submission
        $submitBtn.prop('disabled', true).text('Processing...');
        
        if ($serviceSelect.length && $serviceSelect.prop('disabled')) {
            showNotice(serviceSelectDisabledMessage, 'error');
            $submitBtn.prop('disabled', false).text(originalText);
            return;
        }
        
        var selectedServiceName = $serviceSelect.length ? ($serviceSelect.val() || '') : '';
        if ($serviceSelect.length && !$serviceSelect.prop('disabled') && !selectedServiceName) {
            showNotice(serviceSelectRequiredMessage, 'error');
            $submitBtn.prop('disabled', false).text(originalText);
            return;
        }
        
        // Get form data
        var formData = {
            action: isUpdate ? 'xtremecleans_update_zip_zone' : 'xtremecleans_add_zip_zone',
            nonce: nonce,
            service_name: selectedServiceName,
            zone_name: $('#zone_name').val(),
            zip_code: $('#zip_code').val(),
            zone_area: $('#zone_area').val(),
            county: $('#county').val() || '',
            state: $('#state').val() || '',
            service_fee: $('#service_fee').val() || ''
        };
        
        if (isUpdate) {
            formData.zone_id = zoneId;
        }
        
        // Submit via AJAX
        $.ajax({
            url: ajaxUrl,
            type: 'POST',
            data: formData,
            success: function(response) {
                if (response.success) {
                    // Reset form
                    resetForm();
                    
                    // Add new row to table smoothly
                    if (response.data.zone && !isUpdate) {
                        addZoneRowToTable(response.data.zone);
                    } else if (isUpdate && response.data.zone) {
                        updateZoneRowInTable(response.data.zone);
                    } else {
                        // Fallback: reload if zone data not returned
                        setTimeout(function() {
                            location.reload();
                        }, 1000);
                    }
                    
                    $submitBtn.prop('disabled', false).text(originalText);
                } else {
                    showNotice(response.data.message || 'An error occurred.', 'error');
                    $submitBtn.prop('disabled', false).text(originalText);
                }
            },
            error: function() {
                showNotice('An error occurred. Please try again.', 'error');
                $submitBtn.prop('disabled', false).text(originalText);
            }
        });
    });
    
    // Show notice function
    function showNotice(message, type) {
        var noticeClass = type === 'success' ? 'notice-success' : 'notice-error';
        var notice = $('<div class="notice ' + noticeClass + ' is-dismissible"><p>' + message + '</p></div>');
        $('.xtremecleans-zip-zone-page h1').after(notice);
        
        // Auto dismiss after 3 seconds
        setTimeout(function() {
            notice.fadeOut(300, function() {
                $(this).remove();
            });
        }, 3000);
    }
    
    // Edit button - enable inline editing in table row
    $(document).on('click', '.xtremecleans-edit-btn', function(e) {
        e.preventDefault();
        var $row = $(this).closest('tr');
        var zoneId = $(this).data('id');
        
        // Check if already in edit mode
        if ($row.hasClass('editing-mode')) {
            return;
        }
        
        // Cancel any other editing rows
        $('.xtremecleans-zones-table tbody tr').removeClass('editing-mode').each(function() {
            cancelInlineEdit($(this));
        });
        
        // Get current values
        var serviceName = $row.find('.column-service-name').text().trim();
        var zoneName = normalizeZoneValue($row.find('.column-zone-name').attr('data-zone-value') || $row.find('.column-zone-name strong').text());
        var zipCode = $row.find('.column-zip-code').text().trim();
        var zoneArea = $row.find('.column-zone-area').text().trim();
        var serviceFee = $row.find('.column-fee strong').text().replace('$', '').replace(/,/g, '').trim();
        var county = $row.find('.column-county').text().trim();
        var state = $row.find('.column-state').text().trim();
        
        // Store original values for cancel
        $row.data('original-data', {
            serviceName: serviceName,
            zoneName: zoneName,
            zoneLabel: formatZoneLabel(zoneName),
            zipCode: zipCode,
            zoneArea: zoneArea,
            serviceFee: serviceFee,
            county: county,
            state: state
        });
        
        // Replace cells with input fields
        var $serviceSelect = createServiceSelect(serviceName === '-' ? '' : serviceName);
        $row.find('.column-service-name').html($serviceSelect);
        var $zoneSelectInline = createZoneSelectElement(zoneName);
        $row.find('.column-zone-name').html($zoneSelectInline);
        $row.find('.column-zip-code').html('<input type="text" class="xtremecleans-inline-edit" data-field="zip_code" value="' + zipCode + '" style="width: 100%; padding: 4px;" maxlength="5" pattern="[0-9]{5}">');
        $row.find('.column-zone-area').html('<input type="text" class="xtremecleans-inline-edit" data-field="zone_area" value="' + zoneArea + '" style="width: 100%; padding: 4px;">');
        $row.find('.column-county').html('<input type="text" class="xtremecleans-inline-edit" data-field="county" value="' + (county === '-' ? '' : county) + '" style="width: 100%; padding: 4px;" placeholder="<?php esc_attr_e('Enter county', 'xtremecleans'); ?>">');
        $row.find('.column-state').html('<input type="text" class="xtremecleans-inline-edit" data-field="state" value="' + (state === '-' ? '' : state) + '" style="width: 100%; padding: 4px;" maxlength="50" placeholder="<?php esc_attr_e('Enter state', 'xtremecleans'); ?>">');
        $row.find('.column-fee').html('<input type="text" class="xtremecleans-inline-edit" data-field="service_fee" value="' + serviceFee + '" style="width: 100%; padding: 4px;" placeholder="Enter service fee">');
        
        // Replace action buttons with Save/Cancel
        $row.find('.column-actions').html(
            '<button type="button" class="xtremecleans-save-inline-btn" data-id="' + zoneId + '">Save</button> ' +
            '<button type="button" class="xtremecleans-cancel-inline-btn" data-id="' + zoneId + '">Cancel</button>'
        );
        
        // Add editing class
        $row.addClass('editing-mode');
        
        // Set selected value for zone name dropdown
        $row.find('select[data-field="zone_name"]').val(zoneName.toLowerCase());
    });
    
    // Save inline edit
    $(document).on('click', '.xtremecleans-save-inline-btn', function(e) {
        e.preventDefault();
        var $row = $(this).closest('tr');
        var zoneId = $(this).data('id');
        var $btn = $(this);
        
        // Get values from inline inputs
        var serviceNameField = $row.find('[data-field="service_name"]');
        var serviceName = serviceNameField.length ? serviceNameField.val() : '';
        serviceName = serviceName ? serviceName.trim() : '';
        var zoneName = $row.find('select[data-field="zone_name"]').val() || $row.find('input[data-field="zone_name"]').val();
        var zipCode = $row.find('input[data-field="zip_code"]').val().trim();
        var zoneArea = $row.find('input[data-field="zone_area"]').val().trim();
        var county = $row.find('input[data-field="county"]').val().trim();
        var state = $row.find('input[data-field="state"]').val().trim();
        var serviceFee = $row.find('input[data-field="service_fee"]').val().trim();
        
        // Validate
        if (!zoneName || !zipCode) {
            alert('Zone Name and ZIP Code are required.');
            return;
        }
        
        if (!/^[0-9]{5}$/.test(zipCode)) {
            alert('ZIP Code must be 5 digits.');
            return;
        }
        
        // Disable buttons
        $btn.prop('disabled', true).text('Saving...');
        $row.find('.xtremecleans-cancel-inline-btn').prop('disabled', true);
        
        // Submit via AJAX
        $.ajax({
            url: ajaxUrl,
            type: 'POST',
            data: {
                action: 'xtremecleans_update_zip_zone',
                nonce: nonce,
                zone_id: zoneId,
                service_name: serviceName || '',
                zone_name: zoneName,
                zip_code: zipCode,
                zone_area: zoneArea,
                county: county,
                state: state,
                service_fee: serviceFee || ''
            },
            success: function(response) {
                if (response.success && response.data.zone) {
                    // Update row with new data
                    updateZoneRowInTable(response.data.zone);
                } else {
                    alert(response.data.message || 'Failed to update zone.');
                    $btn.prop('disabled', false).text('Save');
                    $row.find('.xtremecleans-cancel-inline-btn').prop('disabled', false);
                }
            },
            error: function() {
                alert('An error occurred. Please try again.');
                $btn.prop('disabled', false).text('Save');
                $row.find('.xtremecleans-cancel-inline-btn').prop('disabled', false);
            }
        });
    });
    
    // Cancel inline edit
    $(document).on('click', '.xtremecleans-cancel-inline-btn', function(e) {
        e.preventDefault();
        var $row = $(this).closest('tr');
        cancelInlineEdit($row);
    });
    
    // Function to cancel inline editing and restore original values
    function cancelInlineEdit($row) {
        if (!$row.hasClass('editing-mode')) {
            return;
        }
        
        var zoneId = $row.data('zone-id');
        var originalData = $row.data('original-data');
        
        if (originalData) {
            // Restore original values
            var formattedZoneName = originalData.zoneLabel || formatZoneLabel(originalData.zoneName);
            var formattedFee = '$' + parseFloat(originalData.serviceFee || 0).toLocaleString('en-US', {
                minimumFractionDigits: 2,
                maximumFractionDigits: 2
            });
            
            $row.find('.column-service-name').text(originalData.serviceName || '-');
            $row.find('.column-zone-name').attr('data-zone-value', originalData.zoneName).html('<strong>' + formattedZoneName + '</strong>');
            $row.find('.column-zip-code').text(originalData.zipCode);
            $row.find('.column-zone-area').text(originalData.zoneArea);
            $row.find('.column-county').text(originalData.county || '-');
            $row.find('.column-state').text(originalData.state || '-');
            $row.find('.column-fee').html('<strong>' + formattedFee + '</strong>');
        } else {
            // Reload row from server if original data not available
            location.reload();
            return;
        }
        
        // Restore action buttons
        $row.find('.column-actions').html(
            '<button type="button" class="xtremecleans-edit-btn" data-id="' + zoneId + '">Edit</button> ' +
            '<button type="button" class="xtremecleans-delete-btn" data-id="' + zoneId + '">Delete</button>'
        );
        
        // Remove editing class
        $row.removeClass('editing-mode');
        $row.removeData('original-data');
    }
    
    // Cancel edit / Reset form
    function resetForm() {
        $('#zone_id').val('');
        if ($serviceSelect.length) {
            populateServiceDropdown($serviceSelect, '');
            $serviceSelect.val('');
        }
        $('#zone_name').val('');
        $('#zip_code').val('');
        $('#zone_area').val('');
        $('#county').val('');
        $('#state').val('');
        $('#service_fee').val('');
        $('#submit').text('Add New Zone').prop('disabled', false);
        $('.xtremecleans-cancel-edit').remove();
        $('.xtremecleans-zones-table tbody tr').removeClass('editing');
        resetServicePreview();
    }
    
    // Function to add new zone row to table smoothly
    function addZoneRowToTable(zone) {
        var $tbody = $('.xtremecleans-zones-table tbody');
        
        // Remove "no data" row if exists
        $tbody.find('.xtremecleans-no-data').closest('tr').remove();
        
        // Format service fee
        var serviceFee = parseFloat(zone.service_fee || 0).toFixed(2);
        var formattedFee = '$' + parseFloat(serviceFee).toLocaleString('en-US', {
            minimumFractionDigits: 2,
            maximumFractionDigits: 2
        });
        
        // Format zone name
        var zoneName = zone.zone_name || '';
        var zoneSlug = normalizeZoneValue(zoneName);
        var formattedZoneName = formatZoneLabel(zoneSlug || zoneName);
        
        var county = zone.county || '-';
        var state = zone.state ? String(zone.state).toUpperCase() : '-';
        
        // Create new row HTML
        var newRow = '<tr data-zone-id="' + zone.id + '" style="display: none;">' +
            '<td class="column-all-zone">' + zone.id + '</td>' +
            '<td class="column-service-name">' + (zone.service_name || '-') + '</td>' +
            '<td class="column-zone-name" data-zone-value="' + zoneSlug + '"><strong>' + formattedZoneName + '</strong></td>' +
            '<td class="column-zip-code">' + (zone.zip_code || '') + '</td>' +
            '<td class="column-zone-area">' + (zone.zone_area || '') + '</td>' +
            '<td class="column-county">' + county + '</td>' +
            '<td class="column-state">' + state + '</td>' +
            '<td class="column-fee"><strong>' + formattedFee + '</strong></td>' +
            '<td class="column-actions">' +
            '<button type="button" class="xtremecleans-edit-btn" data-id="' + zone.id + '">Edit</button> ' +
            '<button type="button" class="xtremecleans-delete-btn" data-id="' + zone.id + '">Delete</button>' +
            '</td>' +
            '</tr>';
        
        // Add row to table
        $tbody.append(newRow);
        
        // Smooth fade-in animation
        $tbody.find('tr[data-zone-id="' + zone.id + '"]').fadeIn(400, function() {
            $(this).css('display', '');
            // Highlight the new row briefly
            $(this).css('background-color', '#e7f5e7');
            setTimeout(function() {
                $tbody.find('tr[data-zone-id="' + zone.id + '"]').css('background-color', '');
            }, 2000);
        });
        
        // Update total count
        updateTotalCount();
    }
    
    // Function to update zone row in table
    function updateZoneRowInTable(zone) {
        var $row = $('.xtremecleans-zones-table tbody tr[data-zone-id="' + zone.id + '"]');
        
        if ($row.length) {
            // Format service fee
            var serviceFee = parseFloat(zone.service_fee || 0).toFixed(2);
            var formattedFee = '$' + parseFloat(serviceFee).toLocaleString('en-US', {
                minimumFractionDigits: 2,
                maximumFractionDigits: 2
            });
            
            // Format zone name
            var zoneName = zone.zone_name || '';
            var zoneSlug = normalizeZoneValue(zoneName);
            var formattedZoneName = formatZoneLabel(zoneSlug || zoneName);
            
            var county = zone.county || '-';
            var state = zone.state ? String(zone.state).toUpperCase() : '-';
            
            // Update row content (restore from inline edit mode)
            $row.find('.column-service-name').text(zone.service_name || '-');
            $row.find('.column-zone-name').attr('data-zone-value', zoneSlug).html('<strong>' + formattedZoneName + '</strong>');
            $row.find('.column-zip-code').text(zone.zip_code || '');
            $row.find('.column-zone-area').text(zone.zone_area || '');
            $row.find('.column-county').text(county);
            $row.find('.column-state').text(state);
            $row.find('.column-fee').html('<strong>' + formattedFee + '</strong>');
            
            // Restore action buttons
            $row.find('.column-actions').html(
                '<button type="button" class="xtremecleans-edit-btn" data-id="' + zone.id + '">Edit</button> ' +
                '<button type="button" class="xtremecleans-delete-btn" data-id="' + zone.id + '">Delete</button>'
            );
            
            // Remove editing classes
            $row.removeClass('editing editing-mode');
            $row.removeData('original-data');
            
            // Highlight updated row briefly
            $row.css('background-color', '#e7f5e7');
            setTimeout(function() {
                $row.css('background-color', '');
            }, 2000);
        }
    }
    
    // Function to update total count
    function updateTotalCount() {
        var count = $('.xtremecleans-zones-table tbody tr:not(.xtremecleans-no-data)').length;
        $('.xtremecleans-total-count strong').text('Total: ' + count + ' ZIP code' + (count !== 1 ? 's' : ''));
    }
    
    // Cancel edit button
    $(document).on('click', '.xtremecleans-cancel-edit', function(e) {
        e.preventDefault();
        resetForm();
    });
    
    // Delete button
    $(document).on('click', '.xtremecleans-delete-btn', function(e) {
        e.preventDefault();
        var zoneId = $(this).data('id');
        var $row = $(this).closest('tr');
        var $btn = $(this);
        
        if (confirm('Are you sure you want to delete this zip zone? This action cannot be undone.')) {
            $btn.prop('disabled', true).text('Deleting...');
            
            $.ajax({
                url: ajaxUrl,
                type: 'POST',
                data: {
                    action: 'xtremecleans_delete_zip_zone',
                    nonce: nonce,
                    zone_id: zoneId
                },
                success: function(response) {
                    if (response.success) {
                        // Smooth fade-out and remove
                        $row.fadeOut(400, function() {
                            $(this).remove();
                            // Check if table is now empty
                            var remainingRows = $('.xtremecleans-zones-table tbody tr').length;
                            if (remainingRows === 0) {
                                $('.xtremecleans-zones-table tbody').html(
                                    '<tr><td colspan="6" class="xtremecleans-no-data">No zip zones found.</td></tr>'
                                );
                            }
                            // Update total count
                            updateTotalCount();
                        });
                    } else {
                        showNotice(response.data.message || 'Failed to delete zone.', 'error');
                        $btn.prop('disabled', false).text('Delete');
                    }
                },
                error: function() {
                    showNotice('An error occurred. Please try again.', 'error');
                    $btn.prop('disabled', false).text('Delete');
                }
            });
        }
    });
    
    // Clear all data button
    $('#xtremecleans-clear-all-data').on('click', function(e) {
        e.preventDefault();
        var $btn = $(this);
        
        if (confirm('Are you sure you want to clear ALL zip zone data? This action cannot be undone!')) {
            $btn.prop('disabled', true).text('Clearing...');
            
            $.ajax({
                url: ajaxUrl,
                type: 'POST',
                data: {
                    action: 'xtremecleans_clear_all_zones',
                    nonce: nonce
                },
                success: function(response) {
                    if (response.success) {
                        // Clear table and show empty state
                        $('.xtremecleans-zones-table tbody').html(
                            '<tr><td colspan="6" class="xtremecleans-no-data">No zip zones found.</td></tr>'
                        );
                        updateTotalCount();
                    } else {
                        showNotice(response.data.message || 'Failed to clear all zones.', 'error');
                        $btn.prop('disabled', false).text('Clear All Data');
                    }
                },
                error: function() {
                    showNotice('An error occurred. Please try again.', 'error');
                    $btn.prop('disabled', false).text('Clear All Data');
                }
            });
        }
    });
    
    // Update total count
    function updateTotalCount() {
        var count = $('.xtremecleans-zones-table tbody tr:not(.xtremecleans-no-data)').length;
        $('.xtremecleans-total-count strong').html('Total: ' + count + ' ZIP code' + (count !== 1 ? 's' : ''));
        
        // Show no data message if empty
        if (count === 0) {
            $('.xtremecleans-zones-table tbody').html('<tr><td colspan="6" class="xtremecleans-no-data">No zip zones found.</td></tr>');
        }
    }
    
    // Import form toggle
    $('#xtremecleans-import-trigger').on('click', function(e) {
        e.preventDefault();
        $('#xtremecleans-import-form').slideToggle();
    });
    
    $('#xtremecleans-cancel-import').on('click', function(e) {
        e.preventDefault();
        $('#xtremecleans-import-form').slideUp();
        $('#xtremecleans-import-form input[type="file"]').val('');
    });
    
    // ZIP code input validation
    $('#zip_code').on('input', function() {
        this.value = this.value.replace(/[^0-9]/g, '');
        if (this.value.length > 5) {
            this.value = this.value.slice(0, 5);
        }
    });
});
</script>
