<?php
/**
 * XtremeCleans Elementor Integration
 *
 * @package XtremeCleans
 * @subpackage Elementor
 * @since 1.1.0
 */

// Exit if accessed directly
if (!defined('ABSPATH')) {
    exit;
}

/**
 * XtremeCleans Elementor Integration Class
 *
 * @since 1.1.0
 */
class XtremeCleans_Elementor {

    /**
     * Instance of this class
     *
     * @var XtremeCleans_Elementor
     */
    private static $instance = null;

    /**
     * Get instance
     *
     * @return XtremeCleans_Elementor
     */
    public static function get_instance() {
        if (null === self::$instance) {
            self::$instance = new self();
        }
        return self::$instance;
    }

    /**
     * Constructor
     */
    public function __construct() {
        $this->init();
    }

    /**
     * Initialize Elementor integration
     */
    private function init() {
        // Check Elementor version compatibility
        if (!$this->is_elementor_compatible()) {
            add_action('admin_notices', [$this, 'elementor_version_notice']);
            return;
        }

        // Register custom widget category
        add_action('elementor/elements/categories_registered', [$this, 'register_category']);

        // Register widgets
        add_action('elementor/widgets/register', [$this, 'register_widgets']);

        // Register assets early to ensure they're available
        add_action('wp_enqueue_scripts', [$this, 'register_assets'], 5);

        // Enqueue styles in editor
        add_action('elementor/editor/after_enqueue_styles', [$this, 'editor_styles']);

        // Enqueue styles in preview
        add_action('elementor/preview/enqueue_styles', [$this, 'preview_styles']);

        // Enqueue scripts in preview
        add_action('elementor/preview/enqueue_scripts', [$this, 'preview_scripts']);
    }

    /**
     * Check if Elementor is compatible
     *
     * @return bool
     */
    private function is_elementor_compatible() {
        // Check if Elementor is installed and activated
        if (!did_action('elementor/loaded')) {
            return false;
        }

        // Check minimum version (Elementor 3.0.0+)
        if (defined('ELEMENTOR_VERSION')) {
            return version_compare(ELEMENTOR_VERSION, '3.0.0', '>=');
        }

        return false;
    }

    /**
     * Show notice if Elementor version is incompatible
     */
    public function elementor_version_notice() {
        if (!current_user_can('manage_options')) {
            return;
        }

        $message = sprintf(
            __('XtremeCleans requires Elementor version 3.0.0 or higher. Please update Elementor to use the XtremeCleans widget.', 'xtremecleans')
        );

        printf(
            '<div class="notice notice-warning is-dismissible"><p>%s</p></div>',
            esc_html($message)
        );
    }

    /**
     * Register assets early
     */
    public function register_assets() {
        // Register CSS
        if (!wp_style_is('xtremecleans-style', 'registered')) {
            wp_register_style(
                'xtremecleans-style',
                XTREMECLEANS_PLUGIN_URL . 'ui/assets/css/xtremecleans.css',
                [],
                XTREMECLEANS_VERSION
            );
        }

        // Register JS
        if (!wp_script_is('xtremecleans-script', 'registered')) {
            $stripe_enabled = false;
            $stripe_deps = ['jquery'];

            // Check if Stripe is enabled
            if (file_exists(XTREMECLEANS_PLUGIN_DIR . 'core/payment/class-xtremecleans-stripe.php')) {
                require_once XTREMECLEANS_PLUGIN_DIR . 'core/payment/class-xtremecleans-stripe.php';
                if (XtremeCleans_Stripe::is_enabled() && XtremeCleans_Stripe::is_configured()) {
                    $stripe_enabled = true;
                    $stripe_deps[] = 'stripe-js';
                }
            }

            wp_register_script(
                'xtremecleans-script',
                XTREMECLEANS_PLUGIN_URL . 'ui/assets/js/xtremecleans.js',
                $stripe_deps,
                XTREMECLEANS_VERSION,
                true
            );

            // Localize script
            wp_localize_script(
                'xtremecleans-script',
                'xtremecleansData',
                [
                    'ajaxUrl' => admin_url('admin-ajax.php'),
                    'nonce' => wp_create_nonce('xtremecleans_add_zip'),
                    'placeOrderNonce' => wp_create_nonce('xtremecleans_place_order'),
                    'homeUrl' => home_url('/'),
                    'stripeEnabled' => $stripe_enabled,
                ]
            );
        }
    }

    /**
     * Register custom widget category
     *
     * @param \Elementor\Elements_Manager $elements_manager Elementor elements manager
     */
    public function register_category($elements_manager) {
        $elements_manager->add_category(
            'xtremecleans',
            [
                'title' => __('XtremeCleans', 'xtremecleans'),
                'icon' => 'eicon-code',
            ]
        );
    }

    /**
     * Register Elementor widgets
     *
     * @param \Elementor\Widgets_Manager $widgets_manager Elementor widgets manager
     */
    public function register_widgets($widgets_manager) {
        // Load widget file
        require_once XTREMECLEANS_PLUGIN_DIR . 'core/elementor/class-xtremecleans-elementor-widget.php';

        // Register widget
        $widgets_manager->register(new XtremeCleans_Elementor_Widget());
    }

    /**
     * Enqueue editor styles
     */
    public function editor_styles() {
        // Ensure assets are registered
        $this->register_assets();
        
        wp_enqueue_style('xtremecleans-style');
    }

    /**
     * Enqueue preview styles
     */
    public function preview_styles() {
        // Ensure assets are registered
        $this->register_assets();
        
        wp_enqueue_style('xtremecleans-style');
    }

    /**
     * Enqueue preview scripts
     */
    public function preview_scripts() {
        // Ensure assets are registered
        $this->register_assets();
        
        wp_enqueue_script('xtremecleans-script');
    }
}
