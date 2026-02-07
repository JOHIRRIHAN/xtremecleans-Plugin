<?php
/**
 * Admin Service Items Template
 *
 * @package XtremeCleans
 * @subpackage Admin Templates
 * @since 1.0.0
 *
 * @var array $service_items Service items data from database
 * @var array $service_names Unique service names for dropdown
 */

// Exit if accessed directly
if (!defined('ABSPATH')) {
    exit;
}

$service_groups = array();
if (!empty($service_items)) {
    foreach ($service_items as $item) {
        $service_name = !empty($item['service_name']) ? $item['service_name'] : __('(No Service Name)', 'xtremecleans');
        
        if (!isset($service_groups[$service_name])) {
            $service_groups[$service_name] = array(
                'service_name' => $service_name,
                'items' => array(),
                'count' => 0,
            );
        }
        
        $service_groups[$service_name]['items'][] = $item;
        $service_groups[$service_name]['count']++;
    }
}
?>

<style>
/* Force Service Items page width - Inline to override everything */
.wrap.xtremecleans-service-items-page,
#wpbody-content .wrap.xtremecleans-service-items-page {
    max-width: 1400px !important;
    width: 1400px !important;
    margin-left: auto !important;
    margin-right: auto !important;
    box-sizing: border-box !important;
}

#wpbody-content:has(.xtremecleans-service-items-page) {
    max-width: 1450px !important;
    width: 100% !important;
    margin-left: auto !important;
    margin-right: auto !important;
}
</style>
<div class="wrap xtremecleans-service-items-page">
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
            <a href="<?php echo esc_url(admin_url('admin.php?page=xtremecleans-service-items')); ?>" class="xtremecleans-nav-link active"><?php esc_html_e('Service Items', 'xtremecleans'); ?></a>
            <span class="xtremecleans-nav-separator">|</span>
            <a href="<?php echo esc_url(admin_url('admin.php?page=xtremecleans-settings')); ?>" class="xtremecleans-nav-link"><?php esc_html_e('Settings', 'xtremecleans'); ?></a>
            <span class="xtremecleans-nav-separator">|</span>
            <a href="<?php echo esc_url(admin_url('admin.php?page=xtremecleans-shortcodes')); ?>" class="xtremecleans-nav-link"><?php esc_html_e('Shortcodes', 'xtremecleans'); ?></a>
            <span class="xtremecleans-nav-separator">|</span>
            <a href="<?php echo esc_url(admin_url('admin.php?page=xtremecleans-api-test')); ?>" class="xtremecleans-nav-link"><?php esc_html_e('API Test', 'xtremecleans'); ?></a>
        </div>
    </div>
    
    <!-- Add New Service Item Form Section -->
    <div class="xtremecleans-add-service-item-form-section">
        <div class="xtremecleans-form-header">
            <h2><?php esc_html_e('Add New Service Item', 'xtremecleans'); ?></h2>
        </div>
        
        <!-- Service Item Form -->
        <form id="xtremecleans-service-item-form" method="post" action="">
            <?php wp_nonce_field('xtremecleans_add_zip', 'xtremecleans_service_item_nonce'); ?>
            <input type="hidden" name="action" value="xtremecleans_add_service_item">
            <input type="hidden" name="item_id" id="service_item_id" value="">
            
        <div class="xtremecleans-form-content">
            <div class="xtremecleans-form-grid">
                <div class="xtremecleans-form-field">
                    <label for="service_item_service_name"><?php esc_html_e('Service Name', 'xtremecleans'); ?> <span class="required">*</span></label>
                    <input type="text" 
                           name="service_name" 
                           id="service_item_service_name" 
                           class="xtremecleans-input" 
                           placeholder="<?php esc_attr_e('e.g., Carpet Cleaning', 'xtremecleans'); ?>"
                           required>
                </div>
                
                <div class="xtremecleans-form-field xtremecleans-full-width">
                    <label><?php esc_html_e('Service Items', 'xtremecleans'); ?> <span class="required">*</span></label>
                    <p class="description"><?php esc_html_e('Add one or more items with their own Clean / Protect / Deodorize prices.', 'xtremecleans'); ?></p>
                    <div class="xtremecleans-multiple-items-container" id="xtremecleans-item-rows-container">
                        <!-- Item rows injected via JavaScript -->
                    </div>
                    <button type="button" id="xtremecleans-add-item-row" class="button button-small" style="margin-top:8px;">
                        <?php esc_html_e('+ Add Another Item', 'xtremecleans'); ?>
                    </button>
                </div>
            </div>
            
            <div class="xtremecleans-form-actions">
                <button type="submit" name="submit" id="submit" class="xtremecleans-submit-btn">
                    <?php esc_html_e('Add Service Items', 'xtremecleans'); ?>
                </button>
                <button type="button" id="xtremecleans-cancel-edit" class="xtremecleans-cancel-btn" style="display:none;">
                    <?php esc_html_e('Cancel', 'xtremecleans'); ?>
                </button>
            </div>
        </div>
        </form>
    </div>
    
    <!-- Service Items Section -->
    <div class="xtremecleans-service-items-table-section">
        <div class="xtremecleans-table-header">
            <h2><?php esc_html_e('All Service Items', 'xtremecleans'); ?></h2>
        </div>
        
        <?php if (empty($service_groups)) : ?>
            <div class="xtremecleans-no-data">
                <?php esc_html_e('No service items found.', 'xtremecleans'); ?>
            </div>
        <?php else : ?>
            <div class="xtremecleans-service-groups-toolbar">
                <div class="xtremecleans-service-group-count">
                    <?php printf(esc_html(_n('%d service found', '%d services found', count($service_groups), 'xtremecleans')), count($service_groups)); ?>
                </div>
                <div class="xtremecleans-service-group-actions">
                    <button type="button" class="button button-small" id="xtremecleans-expand-all">
                        <?php esc_html_e('Expand All', 'xtremecleans'); ?>
                    </button>
                    <button type="button" class="button button-small" id="xtremecleans-collapse-all">
                        <?php esc_html_e('Collapse All', 'xtremecleans'); ?>
                    </button>
                </div>
            </div>
            
            <div class="xtremecleans-service-groups" id="xtremecleans-service-groups">
                <?php foreach ($service_groups as $service_name => $group) : ?>
                    <div class="xtremecleans-service-group-card is-collapsed" data-collapsed="true">
                        <div class="xtremecleans-service-group-header">
                            <div>
                                <h3><?php echo esc_html($service_name); ?></h3>
                                <p class="description">
                                    <?php printf(esc_html(_n('%d item', '%d items', $group['count'], 'xtremecleans')), intval($group['count'])); ?>
                                </p>
                            </div>
                            <button type="button" class="xtremecleans-service-group-toggle is-collapsed" aria-expanded="false">
                                <span class="dashicons dashicons-arrow-down-alt2"></span>
                            </button>
                        </div>
                        <div class="xtremecleans-service-group-content">
                            <div class="xtremecleans-service-group-columns">
                                <span><?php esc_html_e('Item Name', 'xtremecleans'); ?></span>
                                <span><?php esc_html_e('Description', 'xtremecleans'); ?></span>
                                <span><?php esc_html_e('Clean Price', 'xtremecleans'); ?></span>
                                <span><?php esc_html_e('Protect Price', 'xtremecleans'); ?></span>
                                <span><?php esc_html_e('Deodorize Price', 'xtremecleans'); ?></span>
                                <span><?php esc_html_e('Actions', 'xtremecleans'); ?></span>
                            </div>
                            <div class="xtremecleans-service-group-body">
                                <?php foreach ($group['items'] as $item) : ?>
                                    <div class="xtremecleans-service-group-row" data-item-id="<?php echo esc_attr($item['id']); ?>">
                                        <div class="xtremecleans-service-group-cell name">
                                            <strong><?php echo esc_html($item['item_name']); ?></strong>
                                        </div>
                                        <div class="xtremecleans-service-group-cell description">
                                            <?php echo esc_html($item['item_description'] ? $item['item_description'] : '-'); ?>
                                        </div>
                                        <div class="xtremecleans-service-group-cell price">
                                            $<?php echo esc_html(number_format((float)(isset($item['price1_value']) ? $item['price1_value'] : $item['clean_price']), 2)); ?>
                                        </div>
                                        <div class="xtremecleans-service-group-cell price">
                                            $<?php echo esc_html(number_format((float)(isset($item['price2_value']) ? $item['price2_value'] : $item['protect_price']), 2)); ?>
                                        </div>
                                        <div class="xtremecleans-service-group-cell price">
                                            $<?php echo esc_html(number_format((float)(isset($item['price3_value']) ? $item['price3_value'] : $item['deodorize_price']), 2)); ?>
                                        </div>
                                        <div class="xtremecleans-service-group-cell actions">
                                            <button type="button" class="xtremecleans-edit-service-item-btn button button-small" 
                                                    data-id="<?php echo esc_attr($item['id']); ?>"
                                                    data-service-name="<?php echo esc_attr($item['service_name']); ?>"
                                                    data-item-name="<?php echo esc_attr($item['item_name']); ?>"
                                                    data-description="<?php echo esc_attr($item['item_description']); ?>"
                                                    data-price1-value="<?php echo esc_attr(isset($item['price1_value']) ? $item['price1_value'] : $item['clean_price']); ?>"
                                                    data-price2-value="<?php echo esc_attr(isset($item['price2_value']) ? $item['price2_value'] : $item['protect_price']); ?>"
                                                    data-price3-value="<?php echo esc_attr(isset($item['price3_value']) ? $item['price3_value'] : $item['deodorize_price']); ?>">
                                                <?php esc_html_e('Edit', 'xtremecleans'); ?>
                                            </button>
                                            <button type="button" class="xtremecleans-delete-service-item-btn button button-small button-link-delete" 
                                                    data-id="<?php echo esc_attr($item['id']); ?>">
                                                <?php esc_html_e('Delete', 'xtremecleans'); ?>
                                            </button>
                                        </div>
                                    </div>
                                <?php endforeach; ?>
                            </div>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
        <?php endif; ?>
    </div>
</div>

<script type="text/javascript">
jQuery(function($) {
    var ajaxUrl = "<?php echo esc_js( admin_url( 'admin-ajax.php' ) ); ?>";
    var nonce = "<?php echo esc_js( wp_create_nonce( 'xtremecleans_add_zip' ) ); ?>";
    var itemRowCounter = 0;
    var isEditingServiceItem = false;
    var itemRowStrings = {
        item: "<?php echo esc_js( __( 'Item', 'xtremecleans' ) ); ?>",
        remove: "<?php echo esc_js( __( 'Remove', 'xtremecleans' ) ); ?>",
        itemNameLabel: "<?php echo esc_js( __( 'Item Name', 'xtremecleans' ) ); ?>",
        itemDescriptionLabel: "<?php echo esc_js( __( 'Item Description', 'xtremecleans' ) ); ?>",
        itemPlaceholder: "<?php echo esc_js( __( 'e.g., Room', 'xtremecleans' ) ); ?>",
        descriptionPlaceholder: "<?php echo esc_js( __( 'e.g., Standard rooms up to 200 sq ft.', 'xtremecleans' ) ); ?>",
        addButton: "<?php echo esc_js( __( '+ Add Another Item', 'xtremecleans' ) ); ?>",
        processing: "<?php echo esc_js( __( 'Processing...', 'xtremecleans' ) ); ?>",
        addSubmit: "<?php echo esc_js( __( 'Add Service Items', 'xtremecleans' ) ); ?>",
        updateSubmit: "<?php echo esc_js( __( 'Update Service Item', 'xtremecleans' ) ); ?>",
        errorMessage: "<?php echo esc_js( __( 'An error occurred. Please try again.', 'xtremecleans' ) ); ?>",
        requiredItemMessage: "<?php echo esc_js( __( 'Please add at least one item with prices.', 'xtremecleans' ) ); ?>",
        deleteConfirm: "<?php echo esc_js( __( 'Are you sure you want to delete this service item?', 'xtremecleans' ) ); ?>",
        deleteError: "<?php echo esc_js( __( 'Failed to delete service item.', 'xtremecleans' ) ); ?>"
    };
    
    var priceValueLabels = {
        1: "<?php echo esc_js( __( 'Clean Price', 'xtremecleans' ) ); ?>",
        2: "<?php echo esc_js( __( 'Protect Price', 'xtremecleans' ) ); ?>",
        3: "<?php echo esc_js( __( 'Deodorize Price', 'xtremecleans' ) ); ?>"
    };
    
    var priceLabels = {
        1: "<?php echo esc_js( __( 'Clean', 'xtremecleans' ) ); ?>",
        2: "<?php echo esc_js( __( 'Protect', 'xtremecleans' ) ); ?>",
        3: "<?php echo esc_js( __( 'Deodorize', 'xtremecleans' ) ); ?>"
    };
    
    function refreshItemRowControls() {
        var $rows = $('.xtremecleans-multiple-item-row');
        var total = $rows.length;
        
        $rows.each(function(index) {
            $(this).find('.xtremecleans-item-row-title').text(itemRowStrings.item + ' ' + (index + 1));
        });
        
        if (isEditingServiceItem) {
            $('#xtremecleans-add-item-row').hide();
            $('.xtremecleans-remove-item-row').hide();
        } else {
            $('#xtremecleans-add-item-row').show();
            if (total <= 1) {
                $('.xtremecleans-remove-item-row').hide();
            } else {
                $('.xtremecleans-remove-item-row').show();
            }
        }
    }
    
    function createItemRow(rowId, rowData) {
        var data = rowData || {};
        var $row = $('<div>', {
            'class': 'xtremecleans-multiple-item-row',
            'data-row-id': rowId
        });
        
        var $header = $('<div>', { 'class': 'xtremecleans-item-row-header' });
        $('<span>', { 'class': 'xtremecleans-item-row-title', text: itemRowStrings.item + ' ' + rowId }).appendTo($header);
        $('<button>', {
            type: 'button',
            'class': 'xtremecleans-remove-item-row button-link-delete',
            text: itemRowStrings.remove
        }).appendTo($header);
        $row.append($header);
        
        var $grid = $('<div>', { 'class': 'xtremecleans-form-grid' });
        
        var $nameField = $('<div>', { 'class': 'xtremecleans-form-field' });
        $('<label>').html(itemRowStrings.itemNameLabel + ' <span class="required">*</span>').appendTo($nameField);
        $('<input>', {
            type: 'text',
            name: 'item_rows[' + rowId + '][item_name]',
            'class': 'xtremecleans-input xtremecleans-item-name-input',
            placeholder: itemRowStrings.itemPlaceholder,
            required: true,
            value: data.item_name ? data.item_name : ''
        }).appendTo($nameField);
        $grid.append($nameField);
        
        var $descField = $('<div>', { 'class': 'xtremecleans-form-field xtremecleans-full-width' });
        $('<label>').text(itemRowStrings.itemDescriptionLabel).appendTo($descField);
        $('<textarea>', {
            name: 'item_rows[' + rowId + '][item_description]',
            'class': 'xtremecleans-input xtremecleans-item-description-input',
            rows: 2,
            placeholder: itemRowStrings.descriptionPlaceholder,
            text: data.item_description ? data.item_description : ''
        }).appendTo($descField);
        $grid.append($descField);
        
        [1, 2, 3].forEach(function(index) {
            var value = typeof data['price' + index + '_value'] !== 'undefined' ? data['price' + index + '_value'] : '0.00';
            var $priceField = $('<div>', { 'class': 'xtremecleans-form-field' });
            $('<label>').text(priceValueLabels[index] + ' (' + priceLabels[index] + ')').appendTo($priceField);
            
            var $wrapper = $('<div>', { 'class': 'xtremecleans-price-input-wrapper' });
            $('<span>', { 'class': 'xtremecleans-currency', text: '$' }).appendTo($wrapper);
            $('<input>', {
                type: 'number',
                step: '0.01',
                min: '0',
                placeholder: '0.00',
                value: value,
                name: 'item_rows[' + rowId + '][price' + index + '_value]',
                'class': 'xtremecleans-input xtremecleans-price-input xtremecleans-item-price' + index + '-input'
            }).appendTo($wrapper);
            $priceField.append($wrapper);
            $grid.append($priceField);
        });
        
        $row.append($grid);
        return $row;
    }
    
    function addItemRow(rowData) {
        itemRowCounter++;
        var $row = createItemRow(itemRowCounter, rowData);
        $('#xtremecleans-item-rows-container').append($row);
        refreshItemRowControls();
    }
    
    function resetItemRows(rowsData) {
        var $container = $('#xtremecleans-item-rows-container');
        $container.empty();
        itemRowCounter = 0;
        
        if (rowsData && rowsData.length) {
            rowsData.forEach(function(row) {
                addItemRow(row);
            });
        } else {
            addItemRow();
        }
    }
    
    function collectItemsFromRows() {
        var items = [];
        $('.xtremecleans-multiple-item-row').each(function() {
            var $row = $(this);
            var itemName = $row.find('.xtremecleans-item-name-input').val();
            itemName = itemName ? itemName.trim() : '';
            if (!itemName.length) {
                return;
            }
            
            items.push({
                item_name: itemName,
                item_description: $row.find('.xtremecleans-item-description-input').val() || '',
                price1_value: $row.find('.xtremecleans-item-price1-input').val() || '0.00',
                price2_value: $row.find('.xtremecleans-item-price2-input').val() || '0.00',
                price3_value: $row.find('.xtremecleans-item-price3-input').val() || '0.00'
            });
        });
        
        return items;
    }
    
    resetItemRows();
    $('#xtremecleans-add-item-row').text(itemRowStrings.addButton);
    
    $('#xtremecleans-add-item-row').on('click', function() {
        addItemRow();
    });
    
    $(document).on('click', '.xtremecleans-remove-item-row', function() {
        if (isEditingServiceItem) {
            return;
        }
        
        $(this).closest('.xtremecleans-multiple-item-row').remove();
        refreshItemRowControls();
    });
    
    $('#xtremecleans-service-item-form').on('submit', function(e) {
        e.preventDefault();
        
        var $form = $(this);
        var $submitBtn = $form.find('.xtremecleans-submit-btn');
        var itemId = $('#service_item_id').val();
        var isEdit = itemId !== '';
        var submitDefaultLabel = isEdit ? itemRowStrings.updateSubmit : itemRowStrings.addSubmit;
        
        var items = collectItemsFromRows();
        if (!items.length) {
            alert(itemRowStrings.requiredItemMessage);
            return;
        }
        
        var formData = {
            action: isEdit ? 'xtremecleans_update_service_item' : 'xtremecleans_add_service_item',
            nonce: nonce,
            service_name: $('#service_item_service_name').val(),
            items: JSON.stringify(items)
        };
        
        if (isEdit) {
            formData.item_id = itemId;
        }
        
        $submitBtn.prop('disabled', true).text(itemRowStrings.processing);
        
        $.ajax({
            url: ajaxUrl,
            type: 'POST',
            data: formData,
            success: function(response) {
                if (response.success) {
                    alert(response.data.message);
                    location.reload();
                } else {
                    alert(response.data.message || itemRowStrings.errorMessage);
                    $submitBtn.prop('disabled', false).text(submitDefaultLabel);
                }
            },
            error: function() {
                alert(itemRowStrings.errorMessage);
                $submitBtn.prop('disabled', false).text(submitDefaultLabel);
            }
        });
    });
    
    $('.xtremecleans-edit-service-item-btn').on('click', function() {
        var $btn = $(this);
        var itemId = $btn.data('id');
        isEditingServiceItem = true;
        
        $('#service_item_id').val(itemId);
        $('#service_item_service_name').val($btn.data('service-name'));
        
        var rowData = [{
            item_name: $btn.data('item-name') || '',
            item_description: $btn.data('description') || '',
            price1_value: $btn.data('price1-value') || $btn.data('clean-price') || '0.00',
            price2_value: $btn.data('price2-value') || $btn.data('protect-price') || '0.00',
            price3_value: $btn.data('price3-value') || $btn.data('deodorize-price') || '0.00'
        }];
        
        resetItemRows(rowData);
        refreshItemRowControls();
        
        $('#xtremecleans-service-item-form .xtremecleans-submit-btn').text(itemRowStrings.updateSubmit);
        $('#xtremecleans-cancel-edit').show();
        
        $('html, body').animate({
            scrollTop: $('.xtremecleans-add-service-item-form-section').offset().top - 50
        }, 500);
    });
    
    $('#xtremecleans-cancel-edit').on('click', function() {
        isEditingServiceItem = false;
        $('#service_item_id').val('');
        $('#xtremecleans-service-item-form')[0].reset();
        resetItemRows();
        refreshItemRowControls();
        $('#xtremecleans-service-item-form .xtremecleans-submit-btn').text(itemRowStrings.addSubmit);
        $(this).hide();
    });
    
    $('.xtremecleans-delete-service-item-btn').on('click', function() {
        if (!confirm(itemRowStrings.deleteConfirm)) {
            return;
        }
        var $btn = $(this);
        var itemId = $btn.data('id');
        var $row = $btn.closest('tr');
        
        $.ajax({
            url: ajaxUrl,
            type: 'POST',
            data: {
                action: 'xtremecleans_delete_service_item',
                nonce: nonce,
                item_id: itemId
            },
            success: function(response) {
                if (response.success) {
                    $row.fadeOut(300, function() {
                        $(this).remove();
                        if ($('.xtremecleans-service-items-table tbody tr').length === 0) {
                            location.reload();
                        }
                    });
                } else {
                    alert(response.data.message || itemRowStrings.deleteError);
                }
            },
            error: function() {
                alert(itemRowStrings.errorMessage);
            }
        });
    });
    
    function toggleServiceGroup($card, collapse) {
        var collapsed = typeof collapse === 'boolean' ? collapse : $card.attr('data-collapsed') === 'false';
        $card.attr('data-collapsed', collapsed ? 'true' : 'false');
        $card.toggleClass('is-collapsed', collapsed);
        $card.find('.xtremecleans-service-group-toggle')
            .attr('aria-expanded', collapsed ? 'false' : 'true')
            .toggleClass('is-collapsed', collapsed);
    }
    
    $('#xtremecleans-service-groups').on('click', '.xtremecleans-service-group-toggle', function() {
        var $card = $(this).closest('.xtremecleans-service-group-card');
        toggleServiceGroup($card);
    });
    
    $('#xtremecleans-expand-all').on('click', function() {
        $('.xtremecleans-service-group-card').each(function() {
            toggleServiceGroup($(this), false);
        });
    });
    
    $('#xtremecleans-collapse-all').on('click', function() {
        $('.xtremecleans-service-group-card').each(function() {
            toggleServiceGroup($(this), true);
        });
    });
});

// Force Service Items page width - JavaScript override
jQuery(document).ready(function($) {
    function forceServiceItemsWidth() {
        var $wrap = $('.wrap.xtremecleans-service-items-page');
        var $wpbody = $('#wpbody-content');
        
        if ($wrap.length) {
            $wrap.css({
                'max-width': '1400px',
                'width': '1400px',
                'margin-left': 'auto',
                'margin-right': 'auto',
                'box-sizing': 'border-box'
            });
            
            $wpbody.css({
                'max-width': '1450px',
                'width': '100%',
                'margin-left': 'auto',
                'margin-right': 'auto',
                'float': 'none'
            });
        }
    }
    
    forceServiceItemsWidth();
    setTimeout(forceServiceItemsWidth, 100);
    setTimeout(forceServiceItemsWidth, 500);
});
</script>

