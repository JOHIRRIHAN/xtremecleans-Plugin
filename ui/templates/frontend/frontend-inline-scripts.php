<?php
/**
 * Frontend Inline Scripts Template
 *
 * @package XtremeCleans
 * @subpackage Frontend Templates
 * @since 1.0.0
 */

// Exit if accessed directly
if (!defined('ABSPATH')) {
    exit;
}
?>

<script type="text/javascript">
jQuery(document).ready(function($) {
    // Form validation
    $('.xtremecleans-form').on('submit', function(e) {
        var form = $(this);
        var isValid = true;
        
        form.find('input[required], textarea[required]').each(function() {
            if (!$(this).val().trim()) {
                isValid = false;
                $(this).addClass('error');
            } else {
                $(this).removeClass('error');
            }
        });
        
        if (!isValid) {
            e.preventDefault();
            alert('Please fill in all required fields.');
        }
    });
    
    // Button click tracking (optional)
    $('.xtremecleans-button').on('click', function() {
        // Add analytics or tracking here if needed
        console.log('XtremeCleans button clicked:', $(this).text());
    });
});
</script>

