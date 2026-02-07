<?php
/**
 * XtremeCleans Elementor Widget - Professional Edition
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
 * XtremeCleans Elementor Widget Class
 *
 * Professional Elementor widget with comprehensive customization options
 *
 * @since 1.1.0
 */
class XtremeCleans_Elementor_Widget extends \Elementor\Widget_Base {

    /**
     * Get widget name
     *
     * @return string Widget name
     */
    public function get_name() {
        return 'xtremecleans';
    }

    /**
     * Get widget title
     *
     * @return string Widget title
     */
    public function get_title() {
        return __('XtremeCleans Booking', 'xtremecleans');
    }

    /**
     * Get widget icon
     *
     * @return string Widget icon
     */
    public function get_icon() {
        return 'eicon-form-horizontal';
    }

    /**
     * Get widget categories
     *
     * @return array Widget categories
     */
    public function get_categories() {
        return ['xtremecleans', 'general'];
    }

    /**
     * Get widget keywords
     *
     * @return array Widget keywords
     */
    public function get_keywords() {
        return ['xtremecleans', 'cleaning', 'booking', 'form', 'service', 'appointment', 'quote'];
    }

    /**
     * Get style dependencies
     *
     * @return array Style dependencies
     */
    public function get_style_depends() {
        // Ensure frontend styles are loaded
        if (!wp_style_is('xtremecleans-style', 'registered')) {
            wp_register_style(
                'xtremecleans-style',
                XTREMECLEANS_PLUGIN_URL . 'ui/assets/css/xtremecleans.css',
                [],
                XTREMECLEANS_VERSION
            );
        }
        return ['xtremecleans-style'];
    }

    /**
     * Get script dependencies
     *
     * @return array Script dependencies
     */
    public function get_script_depends() {
        // Ensure frontend scripts are loaded
        if (!wp_script_is('xtremecleans-script', 'registered')) {
            wp_register_script(
                'xtremecleans-script',
                XTREMECLEANS_PLUGIN_URL . 'ui/assets/js/xtremecleans.js',
                ['jquery'],
                XTREMECLEANS_VERSION,
                true
            );
        }
        return ['xtremecleans-script'];
    }

    /**
     * Register widget controls
     */
    protected function register_controls() {
        
        // ============================================
        // CONTENT SECTION
        // ============================================
        $this->start_controls_section(
            'content_section',
            [
                'label' => __('Content Settings', 'xtremecleans'),
                'tab' => \Elementor\Controls_Manager::TAB_CONTENT,
            ]
        );

        $this->add_control(
            'info_notice',
            [
                'type' => \Elementor\Controls_Manager::RAW_HTML,
                'raw' => '<div class="elementor-panel-alert elementor-panel-alert-info" style="padding: 15px;">' .
                         '<strong style="display: block; margin-bottom: 8px;">' . esc_html__('XtremeCleans Booking Form', 'xtremecleans') . '</strong>' .
                         '<p style="margin: 0;">' . esc_html__('This widget displays the complete booking form with all features. Configure API settings, services, and other options in the XtremeCleans plugin settings page.', 'xtremecleans') . '</p>' .
                         '</div>',
            ]
        );

        $this->add_control(
            'settings_link',
            [
                'type' => \Elementor\Controls_Manager::RAW_HTML,
                'raw' => '<div style="text-align: center; margin-top: 10px;">' .
                         '<a href="' . esc_url(admin_url('admin.php?page=xtremecleans-settings')) . '" target="_blank" class="elementor-button elementor-button-default" style="text-decoration: none;">' .
                         '<span class="dashicons dashicons-admin-settings" style="vertical-align: middle; margin-right: 5px;"></span>' .
                         esc_html__('Open Plugin Settings', 'xtremecleans') .
                         '</a>' .
                         '</div>',
            ]
        );

        $this->end_controls_section();

        // ============================================
        // HERO SECTION CONTENT
        // ============================================
        $this->start_controls_section(
            'hero_content_section',
            [
                'label' => __('Hero Section', 'xtremecleans'),
                'tab' => \Elementor\Controls_Manager::TAB_CONTENT,
            ]
        );

        $this->add_control(
            'hero_background_image',
            [
                'label' => __('Background Image', 'xtremecleans'),
                'type' => \Elementor\Controls_Manager::MEDIA,
                'default' => [
                    'url' => 'https://xtremecleans.com/wp-content/uploads/2024/12/Carpet-Cleaning-Services-2.jpg',
                ],
                'description' => __('Choose background image for hero section', 'xtremecleans'),
            ]
        );

        $this->add_control(
            'zip_placeholder_text',
            [
                'label' => __('ZIP Code Placeholder', 'xtremecleans'),
                'type' => \Elementor\Controls_Manager::TEXT,
                'default' => __('ZIP Code', 'xtremecleans'),
                'placeholder' => __('Enter placeholder text', 'xtremecleans'),
                'label_block' => true,
            ]
        );

        $this->add_control(
            'continue_button_text',
            [
                'label' => __('Continue Button Text', 'xtremecleans'),
                'type' => \Elementor\Controls_Manager::TEXT,
                'default' => __('CONTINUE', 'xtremecleans'),
                'placeholder' => __('Enter button text', 'xtremecleans'),
                'label_block' => true,
            ]
        );

        $this->end_controls_section();

        // ============================================
        // FORM CONTENT
        // ============================================
        $this->start_controls_section(
            'form_content_section',
            [
                'label' => __('Form Content', 'xtremecleans'),
                'tab' => \Elementor\Controls_Manager::TAB_CONTENT,
            ]
        );

        $this->add_control(
            'lead_form_title',
            [
                'label' => __('Lead Form Title', 'xtremecleans'),
                'type' => \Elementor\Controls_Manager::TEXTAREA,
                'default' => __('Your ZIP code is outside our service area. Please provide your details below', 'xtremecleans'),
                'placeholder' => __('Enter form title', 'xtremecleans'),
                'rows' => 2,
            ]
        );

        $this->add_control(
            'name_label',
            [
                'label' => __('Name Label', 'xtremecleans'),
                'type' => \Elementor\Controls_Manager::TEXT,
                'default' => __('Name', 'xtremecleans'),
                'placeholder' => __('Enter label text', 'xtremecleans'),
            ]
        );

        $this->add_control(
            'name_placeholder',
            [
                'label' => __('Name Placeholder', 'xtremecleans'),
                'type' => \Elementor\Controls_Manager::TEXT,
                'default' => __('Enter your full name', 'xtremecleans'),
                'placeholder' => __('Enter placeholder text', 'xtremecleans'),
            ]
        );

        $this->add_control(
            'email_label',
            [
                'label' => __('Email Label', 'xtremecleans'),
                'type' => \Elementor\Controls_Manager::TEXT,
                'default' => __('Email', 'xtremecleans'),
                'placeholder' => __('Enter label text', 'xtremecleans'),
            ]
        );

        $this->add_control(
            'email_placeholder',
            [
                'label' => __('Email Placeholder', 'xtremecleans'),
                'type' => \Elementor\Controls_Manager::TEXT,
                'default' => __('Enter your email address', 'xtremecleans'),
                'placeholder' => __('Enter placeholder text', 'xtremecleans'),
            ]
        );

        $this->add_control(
            'phone_label',
            [
                'label' => __('Phone Label', 'xtremecleans'),
                'type' => \Elementor\Controls_Manager::TEXT,
                'default' => __('Phone', 'xtremecleans'),
                'placeholder' => __('Enter label text', 'xtremecleans'),
            ]
        );

        $this->add_control(
            'phone_placeholder',
            [
                'label' => __('Phone Placeholder', 'xtremecleans'),
                'type' => \Elementor\Controls_Manager::TEXT,
                'default' => __('Enter your phone number', 'xtremecleans'),
                'placeholder' => __('Enter placeholder text', 'xtremecleans'),
            ]
        );

        $this->add_control(
            'submit_button_text',
            [
                'label' => __('Submit Button Text', 'xtremecleans'),
                'type' => \Elementor\Controls_Manager::TEXT,
                'default' => __('Submit', 'xtremecleans'),
                'placeholder' => __('Enter button text', 'xtremecleans'),
            ]
        );

        $this->end_controls_section();

        // ============================================
        // SERVICE SELECTION CONTENT
        // ============================================
        $this->start_controls_section(
            'service_content_section',
            [
                'label' => __('Service Selection', 'xtremecleans'),
                'tab' => \Elementor\Controls_Manager::TAB_CONTENT,
            ]
        );

        $this->add_control(
            'service_title',
            [
                'label' => __('Service Title', 'xtremecleans'),
                'type' => \Elementor\Controls_Manager::TEXT,
                'default' => __('WHAT CAN WE CLEAN FOR YOU?', 'xtremecleans'),
                'placeholder' => __('Enter title', 'xtremecleans'),
            ]
        );

        $this->add_control(
            'service_instruction',
            [
                'label' => __('Service Instruction', 'xtremecleans'),
                'type' => \Elementor\Controls_Manager::TEXTAREA,
                'default' => __('PLEASE SELECT ALL ITEMS AND SERVICES FROM THE DROP DOWNS BELOW FOR AN ACCURATE QUOTE AND ANY DISCOUNT THAT MAY APPLY.', 'xtremecleans'),
                'placeholder' => __('Enter instruction text', 'xtremecleans'),
                'rows' => 2,
            ]
        );

        $this->add_control(
            'quote_title',
            [
                'label' => __('Quote Title', 'xtremecleans'),
                'type' => \Elementor\Controls_Manager::TEXT,
                'default' => __('YOUR QUOTE', 'xtremecleans'),
                'placeholder' => __('Enter title', 'xtremecleans'),
            ]
        );

        $this->add_control(
            'clear_link_text',
            [
                'label' => __('Clear Link Text', 'xtremecleans'),
                'type' => \Elementor\Controls_Manager::TEXT,
                'default' => __('Clear', 'xtremecleans'),
                'placeholder' => __('Enter link text', 'xtremecleans'),
            ]
        );

        $this->end_controls_section();

        // ============================================
        // CUSTOMER INFO CONTENT
        // ============================================
        $this->start_controls_section(
            'customer_info_content_section',
            [
                'label' => __('Customer Information', 'xtremecleans'),
                'tab' => \Elementor\Controls_Manager::TAB_CONTENT,
            ]
        );

        $this->add_control(
            'info_title',
            [
                'label' => __('Information Title', 'xtremecleans'),
                'type' => \Elementor\Controls_Manager::TEXT,
                'default' => __('YOUR INFORMATION', 'xtremecleans'),
                'placeholder' => __('Enter title', 'xtremecleans'),
            ]
        );

        $this->add_control(
            'first_name_label',
            [
                'label' => __('First Name Label', 'xtremecleans'),
                'type' => \Elementor\Controls_Manager::TEXT,
                'default' => __('First Name', 'xtremecleans'),
                'placeholder' => __('Enter label', 'xtremecleans'),
            ]
        );

        $this->add_control(
            'first_name_placeholder',
            [
                'label' => __('First Name Placeholder', 'xtremecleans'),
                'type' => \Elementor\Controls_Manager::TEXT,
                'default' => __('First Name', 'xtremecleans'),
                'placeholder' => __('Enter placeholder', 'xtremecleans'),
            ]
        );

        $this->add_control(
            'last_name_label',
            [
                'label' => __('Last Name Label', 'xtremecleans'),
                'type' => \Elementor\Controls_Manager::TEXT,
                'default' => __('Last Name', 'xtremecleans'),
                'placeholder' => __('Enter label', 'xtremecleans'),
            ]
        );

        $this->add_control(
            'last_name_placeholder',
            [
                'label' => __('Last Name Placeholder', 'xtremecleans'),
                'type' => \Elementor\Controls_Manager::TEXT,
                'default' => __('Last Name', 'xtremecleans'),
                'placeholder' => __('Enter placeholder', 'xtremecleans'),
            ]
        );

        $this->add_control(
            'email_address_label',
            [
                'label' => __('Email Address Label', 'xtremecleans'),
                'type' => \Elementor\Controls_Manager::TEXT,
                'default' => __('Email Address', 'xtremecleans'),
                'placeholder' => __('Enter label', 'xtremecleans'),
            ]
        );

        $this->add_control(
            'email_address_placeholder',
            [
                'label' => __('Email Address Placeholder', 'xtremecleans'),
                'type' => \Elementor\Controls_Manager::TEXT,
                'default' => __('name@email.com', 'xtremecleans'),
                'placeholder' => __('Enter placeholder', 'xtremecleans'),
            ]
        );

        $this->add_control(
            'phone_label_customer',
            [
                'label' => __('Phone Label', 'xtremecleans'),
                'type' => \Elementor\Controls_Manager::TEXT,
                'default' => __('Phone', 'xtremecleans'),
                'placeholder' => __('Enter label', 'xtremecleans'),
            ]
        );

        $this->add_control(
            'phone_placeholder_customer',
            [
                'label' => __('Phone Placeholder', 'xtremecleans'),
                'type' => \Elementor\Controls_Manager::TEXT,
                'default' => __('(555) 123-4567', 'xtremecleans'),
                'placeholder' => __('Enter placeholder', 'xtremecleans'),
            ]
        );

        $this->add_control(
            'alt_phone_label',
            [
                'label' => __('Alternate Phone Label', 'xtremecleans'),
                'type' => \Elementor\Controls_Manager::TEXT,
                'default' => __('Alternate Phone', 'xtremecleans'),
                'placeholder' => __('Enter label', 'xtremecleans'),
            ]
        );

        $this->add_control(
            'alt_phone_placeholder',
            [
                'label' => __('Alternate Phone Placeholder', 'xtremecleans'),
                'type' => \Elementor\Controls_Manager::TEXT,
                'default' => __('Optional', 'xtremecleans'),
                'placeholder' => __('Enter placeholder', 'xtremecleans'),
            ]
        );

        $this->end_controls_section();

        // ============================================
        // CONTAINER STYLE SECTION
        // ============================================
        $this->start_controls_section(
            'container_style_section',
            [
                'label' => __('Container', 'xtremecleans'),
                'tab' => \Elementor\Controls_Manager::TAB_STYLE,
            ]
        );

        $this->add_group_control(
            \Elementor\Group_Control_Background::get_type(),
            [
                'name' => 'container_background',
                'label' => __('Background', 'xtremecleans'),
                'types' => ['classic', 'gradient'],
                'selector' => '{{WRAPPER}} .xtremecleans-elementor-wrapper',
            ]
        );

        $this->add_responsive_control(
            'container_padding',
            [
                'label' => __('Padding', 'xtremecleans'),
                'type' => \Elementor\Controls_Manager::DIMENSIONS,
                'size_units' => ['px', 'em', '%'],
                'default' => [
                    'unit' => 'px',
                ],
                'selectors' => [
                    '{{WRAPPER}} .xtremecleans-elementor-wrapper' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->add_responsive_control(
            'container_margin',
            [
                'label' => __('Margin', 'xtremecleans'),
                'type' => \Elementor\Controls_Manager::DIMENSIONS,
                'size_units' => ['px', 'em', '%'],
                'default' => [
                    'unit' => 'px',
                ],
                'selectors' => [
                    '{{WRAPPER}} .xtremecleans-elementor-wrapper' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->add_group_control(
            \Elementor\Group_Control_Border::get_type(),
            [
                'name' => 'container_border',
                'label' => __('Border', 'xtremecleans'),
                'selector' => '{{WRAPPER}} .xtremecleans-elementor-wrapper',
            ]
        );

        $this->add_responsive_control(
            'container_border_radius',
            [
                'label' => __('Border Radius', 'xtremecleans'),
                'type' => \Elementor\Controls_Manager::DIMENSIONS,
                'size_units' => ['px', '%'],
                'selectors' => [
                    '{{WRAPPER}} .xtremecleans-elementor-wrapper' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->add_group_control(
            \Elementor\Group_Control_Box_Shadow::get_type(),
            [
                'name' => 'container_box_shadow',
                'label' => __('Box Shadow', 'xtremecleans'),
                'selector' => '{{WRAPPER}} .xtremecleans-elementor-wrapper',
            ]
        );

        $this->end_controls_section();

        // ============================================
        // HERO SECTION STYLE
        // ============================================
        $this->start_controls_section(
            'hero_style_section',
            [
                'label' => __('Hero Section', 'xtremecleans'),
                'tab' => \Elementor\Controls_Manager::TAB_STYLE,
            ]
        );

        $this->add_control(
            'hero_background_overlay',
            [
                'label' => __('Background Overlay', 'xtremecleans'),
                'type' => \Elementor\Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .xtremecleans-hero-overlay' => 'background-color: {{VALUE}};',
                ],
            ]
        );

        $this->add_responsive_control(
            'hero_min_height',
            [
                'label' => __('Min Height', 'xtremecleans'),
                'type' => \Elementor\Controls_Manager::SLIDER,
                'size_units' => ['px', 'vh'],
                'range' => [
                    'px' => [
                        'min' => 200,
                        'max' => 1000,
                        'step' => 10,
                    ],
                    'vh' => [
                        'min' => 20,
                        'max' => 100,
                    ],
                ],
                'selectors' => [
                    '{{WRAPPER}} .xtremecleans-hero-section' => 'min-height: {{SIZE}}{{UNIT}};',
                ],
            ]
        );

        $this->add_responsive_control(
            'hero_padding',
            [
                'label' => __('Padding', 'xtremecleans'),
                'type' => \Elementor\Controls_Manager::DIMENSIONS,
                'size_units' => ['px', 'em', '%'],
                'selectors' => [
                    '{{WRAPPER}} .xtremecleans-hero-section' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->add_control(
            'hero_title_heading',
            [
                'label' => __('Hero Title', 'xtremecleans'),
                'type' => \Elementor\Controls_Manager::HEADING,
                'separator' => 'before',
            ]
        );

        $this->add_control(
            'hero_title_color',
            [
                'label' => __('Title Color', 'xtremecleans'),
                'type' => \Elementor\Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .xtremecleans-hero-title' => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->add_group_control(
            \Elementor\Group_Control_Typography::get_type(),
            [
                'name' => 'hero_title_typography',
                'label' => __('Title Typography', 'xtremecleans'),
                'selector' => '{{WRAPPER}} .xtremecleans-hero-title',
            ]
        );

        $this->add_responsive_control(
            'hero_title_margin',
            [
                'label' => __('Title Margin', 'xtremecleans'),
                'type' => \Elementor\Controls_Manager::DIMENSIONS,
                'size_units' => ['px', 'em'],
                'selectors' => [
                    '{{WRAPPER}} .xtremecleans-hero-title' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->add_control(
            'hero_button_heading',
            [
                'label' => __('Hero Button', 'xtremecleans'),
                'type' => \Elementor\Controls_Manager::HEADING,
                'separator' => 'before',
            ]
        );

        $this->add_group_control(
            \Elementor\Group_Control_Typography::get_type(),
            [
                'name' => 'hero_button_typography',
                'label' => __('Button Typography', 'xtremecleans'),
                'selector' => '{{WRAPPER}} .xtremecleans-hero-button',
            ]
        );

        $this->start_controls_tabs('hero_button_tabs');

        $this->start_controls_tab(
            'hero_button_normal',
            [
                'label' => __('Normal', 'xtremecleans'),
            ]
        );

        $this->add_control(
            'hero_button_color',
            [
                'label' => __('Text Color', 'xtremecleans'),
                'type' => \Elementor\Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .xtremecleans-hero-button' => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->add_control(
            'hero_button_bg_color',
            [
                'label' => __('Background Color', 'xtremecleans'),
                'type' => \Elementor\Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .xtremecleans-hero-button' => 'background-color: {{VALUE}};',
                ],
            ]
        );

        $this->end_controls_tab();

        $this->start_controls_tab(
            'hero_button_hover',
            [
                'label' => __('Hover', 'xtremecleans'),
            ]
        );

        $this->add_control(
            'hero_button_hover_color',
            [
                'label' => __('Text Color', 'xtremecleans'),
                'type' => \Elementor\Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .xtremecleans-hero-button:hover' => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->add_control(
            'hero_button_hover_bg_color',
            [
                'label' => __('Background Color', 'xtremecleans'),
                'type' => \Elementor\Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .xtremecleans-hero-button:hover' => 'background-color: {{VALUE}};',
                ],
            ]
        );

        $this->end_controls_tab();
        $this->end_controls_tabs();

        $this->add_responsive_control(
            'hero_button_padding',
            [
                'label' => __('Button Padding', 'xtremecleans'),
                'type' => \Elementor\Controls_Manager::DIMENSIONS,
                'size_units' => ['px', 'em'],
                'selectors' => [
                    '{{WRAPPER}} .xtremecleans-hero-button' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
                'separator' => 'before',
            ]
        );

        $this->add_responsive_control(
            'hero_button_border_radius',
            [
                'label' => __('Button Border Radius', 'xtremecleans'),
                'type' => \Elementor\Controls_Manager::DIMENSIONS,
                'size_units' => ['px', '%'],
                'selectors' => [
                    '{{WRAPPER}} .xtremecleans-hero-button' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->end_controls_section();

        // ============================================
        // FORM SECTION STYLE
        // ============================================
        $this->start_controls_section(
            'form_style_section',
            [
                'label' => __('Form Styles', 'xtremecleans'),
                'tab' => \Elementor\Controls_Manager::TAB_STYLE,
            ]
        );

        $this->add_group_control(
            \Elementor\Group_Control_Background::get_type(),
            [
                'name' => 'form_background',
                'label' => __('Form Background', 'xtremecleans'),
                'types' => ['classic', 'gradient'],
                'selector' => '{{WRAPPER}} .xtremecleans-form-wrapper, {{WRAPPER}} .xtremecleans-popup-form',
            ]
        );

        $this->add_responsive_control(
            'form_padding',
            [
                'label' => __('Form Padding', 'xtremecleans'),
                'type' => \Elementor\Controls_Manager::DIMENSIONS,
                'size_units' => ['px', 'em'],
                'selectors' => [
                    '{{WRAPPER}} .xtremecleans-form-wrapper' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                    '{{WRAPPER}} .xtremecleans-popup-form' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->add_group_control(
            \Elementor\Group_Control_Border::get_type(),
            [
                'name' => 'form_border',
                'label' => __('Form Border', 'xtremecleans'),
                'selector' => '{{WRAPPER}} .xtremecleans-form-wrapper, {{WRAPPER}} .xtremecleans-popup-form',
            ]
        );

        $this->add_responsive_control(
            'form_border_radius',
            [
                'label' => __('Form Border Radius', 'xtremecleans'),
                'type' => \Elementor\Controls_Manager::DIMENSIONS,
                'size_units' => ['px', '%'],
                'selectors' => [
                    '{{WRAPPER}} .xtremecleans-form-wrapper' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                    '{{WRAPPER}} .xtremecleans-popup-form' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->add_group_control(
            \Elementor\Group_Control_Box_Shadow::get_type(),
            [
                'name' => 'form_box_shadow',
                'label' => __('Form Box Shadow', 'xtremecleans'),
                'selector' => '{{WRAPPER}} .xtremecleans-form-wrapper, {{WRAPPER}} .xtremecleans-popup-form',
            ]
        );

        $this->add_control(
            'form_input_heading',
            [
                'label' => __('Form Inputs', 'xtremecleans'),
                'type' => \Elementor\Controls_Manager::HEADING,
                'separator' => 'before',
            ]
        );

        $this->add_control(
            'form_input_bg_color',
            [
                'label' => __('Input Background', 'xtremecleans'),
                'type' => \Elementor\Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .xtremecleans-form-wrapper input[type="text"],
                     {{WRAPPER}} .xtremecleans-form-wrapper input[type="email"],
                     {{WRAPPER}} .xtremecleans-form-wrapper input[type="tel"],
                     {{WRAPPER}} .xtremecleans-form-wrapper textarea,
                     {{WRAPPER}} .xtremecleans-form-wrapper select,
                     {{WRAPPER}} .xtremecleans-zip-input' => 'background-color: {{VALUE}};',
                ],
            ]
        );

        $this->add_control(
            'form_input_text_color',
            [
                'label' => __('Input Text Color', 'xtremecleans'),
                'type' => \Elementor\Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .xtremecleans-form-wrapper input[type="text"],
                     {{WRAPPER}} .xtremecleans-form-wrapper input[type="email"],
                     {{WRAPPER}} .xtremecleans-form-wrapper input[type="tel"],
                     {{WRAPPER}} .xtremecleans-form-wrapper textarea,
                     {{WRAPPER}} .xtremecleans-form-wrapper select,
                     {{WRAPPER}} .xtremecleans-zip-input' => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->add_control(
            'form_input_border_color',
            [
                'label' => __('Input Border Color', 'xtremecleans'),
                'type' => \Elementor\Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .xtremecleans-form-wrapper input[type="text"],
                     {{WRAPPER}} .xtremecleans-form-wrapper input[type="email"],
                     {{WRAPPER}} .xtremecleans-form-wrapper input[type="tel"],
                     {{WRAPPER}} .xtremecleans-form-wrapper textarea,
                     {{WRAPPER}} .xtremecleans-form-wrapper select,
                     {{WRAPPER}} .xtremecleans-zip-input' => 'border-color: {{VALUE}};',
                ],
            ]
        );

        $this->add_responsive_control(
            'form_input_border_radius',
            [
                'label' => __('Input Border Radius', 'xtremecleans'),
                'type' => \Elementor\Controls_Manager::DIMENSIONS,
                'size_units' => ['px'],
                'selectors' => [
                    '{{WRAPPER}} .xtremecleans-form-wrapper input[type="text"],
                     {{WRAPPER}} .xtremecleans-form-wrapper input[type="email"],
                     {{WRAPPER}} .xtremecleans-form-wrapper input[type="tel"],
                     {{WRAPPER}} .xtremecleans-form-wrapper textarea,
                     {{WRAPPER}} .xtremecleans-form-wrapper select,
                     {{WRAPPER}} .xtremecleans-zip-input' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->add_responsive_control(
            'form_input_padding',
            [
                'label' => __('Input Padding', 'xtremecleans'),
                'type' => \Elementor\Controls_Manager::DIMENSIONS,
                'size_units' => ['px', 'em'],
                'selectors' => [
                    '{{WRAPPER}} .xtremecleans-form-wrapper input[type="text"],
                     {{WRAPPER}} .xtremecleans-form-wrapper input[type="email"],
                     {{WRAPPER}} .xtremecleans-form-wrapper input[type="tel"],
                     {{WRAPPER}} .xtremecleans-form-wrapper textarea,
                     {{WRAPPER}} .xtremecleans-form-wrapper select,
                     {{WRAPPER}} .xtremecleans-zip-input' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->add_group_control(
            \Elementor\Group_Control_Typography::get_type(),
            [
                'name' => 'form_input_typography',
                'label' => __('Input Typography', 'xtremecleans'),
                'selector' => '{{WRAPPER}} .xtremecleans-form-wrapper input,
                               {{WRAPPER}} .xtremecleans-form-wrapper textarea,
                               {{WRAPPER}} .xtremecleans-form-wrapper select,
                               {{WRAPPER}} .xtremecleans-zip-input',
            ]
        );

        $this->add_control(
            'form_button_heading',
            [
                'label' => __('Form Buttons', 'xtremecleans'),
                'type' => \Elementor\Controls_Manager::HEADING,
                'separator' => 'before',
            ]
        );

        $this->add_group_control(
            \Elementor\Group_Control_Typography::get_type(),
            [
                'name' => 'form_button_typography',
                'label' => __('Button Typography', 'xtremecleans'),
                'selector' => '{{WRAPPER}} .xtremecleans-form-wrapper button,
                               {{WRAPPER}} .xtremecleans-form-wrapper .button,
                               {{WRAPPER}} .xtremecleans-form-wrapper input[type="submit"]',
            ]
        );

        $this->start_controls_tabs('form_button_tabs');

        $this->start_controls_tab(
            'form_button_normal',
            [
                'label' => __('Normal', 'xtremecleans'),
            ]
        );

        $this->add_control(
            'form_button_color',
            [
                'label' => __('Text Color', 'xtremecleans'),
                'type' => \Elementor\Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .xtremecleans-form-wrapper button,
                     {{WRAPPER}} .xtremecleans-form-wrapper .button,
                     {{WRAPPER}} .xtremecleans-form-wrapper input[type="submit"]' => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->add_control(
            'form_button_bg_color',
            [
                'label' => __('Background Color', 'xtremecleans'),
                'type' => \Elementor\Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .xtremecleans-form-wrapper button,
                     {{WRAPPER}} .xtremecleans-form-wrapper .button,
                     {{WRAPPER}} .xtremecleans-form-wrapper input[type="submit"]' => 'background-color: {{VALUE}};',
                ],
            ]
        );

        $this->end_controls_tab();

        $this->start_controls_tab(
            'form_button_hover',
            [
                'label' => __('Hover', 'xtremecleans'),
            ]
        );

        $this->add_control(
            'form_button_hover_color',
            [
                'label' => __('Text Color', 'xtremecleans'),
                'type' => \Elementor\Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .xtremecleans-form-wrapper button:hover,
                     {{WRAPPER}} .xtremecleans-form-wrapper .button:hover,
                     {{WRAPPER}} .xtremecleans-form-wrapper input[type="submit"]:hover' => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->add_control(
            'form_button_hover_bg_color',
            [
                'label' => __('Background Color', 'xtremecleans'),
                'type' => \Elementor\Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .xtremecleans-form-wrapper button:hover,
                     {{WRAPPER}} .xtremecleans-form-wrapper .button:hover,
                     {{WRAPPER}} .xtremecleans-form-wrapper input[type="submit"]:hover' => 'background-color: {{VALUE}};',
                ],
            ]
        );

        $this->end_controls_tab();
        $this->end_controls_tabs();

        $this->add_responsive_control(
            'form_button_padding',
            [
                'label' => __('Button Padding', 'xtremecleans'),
                'type' => \Elementor\Controls_Manager::DIMENSIONS,
                'size_units' => ['px', 'em'],
                'selectors' => [
                    '{{WRAPPER}} .xtremecleans-form-wrapper button,
                     {{WRAPPER}} .xtremecleans-form-wrapper .button,
                     {{WRAPPER}} .xtremecleans-form-wrapper input[type="submit"]' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
                'separator' => 'before',
            ]
        );

        $this->add_responsive_control(
            'form_button_border_radius',
            [
                'label' => __('Button Border Radius', 'xtremecleans'),
                'type' => \Elementor\Controls_Manager::DIMENSIONS,
                'size_units' => ['px', '%'],
                'selectors' => [
                    '{{WRAPPER}} .xtremecleans-form-wrapper button,
                     {{WRAPPER}} .xtremecleans-form-wrapper .button,
                     {{WRAPPER}} .xtremecleans-form-wrapper input[type="submit"]' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->end_controls_section();

        // ============================================
        // ADVANCED SECTION
        // ============================================
        $this->start_controls_section(
            'advanced_section',
            [
                'label' => __('Advanced', 'xtremecleans'),
                'tab' => \Elementor\Controls_Manager::TAB_ADVANCED,
            ]
        );

        $this->add_control(
            'custom_css_class',
            [
                'label' => __('Custom CSS Class', 'xtremecleans'),
                'type' => \Elementor\Controls_Manager::TEXT,
                'default' => '',
                'prefix_class' => '',
                'label_block' => true,
                'description' => __('Add custom CSS class to the wrapper', 'xtremecleans'),
            ]
        );

        $this->add_control(
            'custom_css_id',
            [
                'label' => __('Custom CSS ID', 'xtremecleans'),
                'type' => \Elementor\Controls_Manager::TEXT,
                'default' => '',
                'label_block' => true,
                'description' => __('Add custom CSS ID to the wrapper', 'xtremecleans'),
            ]
        );

        $this->end_controls_section();
    }

    /**
     * Render widget output
     */
    protected function render() {
        $settings = $this->get_settings_for_display();
        
        // Check if shortcode exists
        if (!shortcode_exists('xtremecleans')) {
            if (current_user_can('manage_options')) {
                ?>
                <div class="xtremecleans-elementor-error" style="padding: 30px; background: #fff3cd; border: 2px solid #ffc107; border-radius: 8px; text-align: center;">
                    <h3 style="margin: 0 0 10px; color: #856404;"><?php echo esc_html__('XtremeCleans Error', 'xtremecleans'); ?></h3>
                    <p style="margin: 0; color: #856404;"><?php echo esc_html__('The XtremeCleans shortcode is not available. Please check plugin settings.', 'xtremecleans'); ?></p>
                    <a href="<?php echo esc_url(admin_url('admin.php?page=xtremecleans-settings')); ?>" target="_blank" style="display: inline-block; margin-top: 15px; padding: 10px 20px; background: #ffc107; color: #856404; text-decoration: none; border-radius: 4px;">
                        <?php echo esc_html__('Open Settings', 'xtremecleans'); ?>
                    </a>
                </div>
                <?php
            }
            return;
        }

        // Add custom classes and ID
        $wrapper_class = 'xtremecleans-elementor-wrapper';
        if (!empty($settings['custom_css_class'])) {
            $wrapper_class .= ' ' . esc_attr($settings['custom_css_class']);
        }
        
        $wrapper_id = '';
        if (!empty($settings['custom_css_id'])) {
            $wrapper_id = ' id="' . esc_attr($settings['custom_css_id']) . '"';
        }

        // Build shortcode attributes from widget settings
        $shortcode_atts = array();
        
        // Hero section
        if (!empty($settings['hero_background_image']['url'])) {
            $shortcode_atts[] = 'hero_bg="' . esc_url($settings['hero_background_image']['url']) . '"';
        }
        if (!empty($settings['zip_placeholder_text'])) {
            $shortcode_atts[] = 'zip_placeholder="' . esc_attr($settings['zip_placeholder_text']) . '"';
        }
        if (!empty($settings['continue_button_text'])) {
            $shortcode_atts[] = 'continue_btn="' . esc_attr($settings['continue_button_text']) . '"';
        }

        // Form content
        if (!empty($settings['lead_form_title'])) {
            $shortcode_atts[] = 'lead_title="' . esc_attr($settings['lead_form_title']) . '"';
        }
        if (!empty($settings['name_label'])) {
            $shortcode_atts[] = 'name_label="' . esc_attr($settings['name_label']) . '"';
        }
        if (!empty($settings['name_placeholder'])) {
            $shortcode_atts[] = 'name_placeholder="' . esc_attr($settings['name_placeholder']) . '"';
        }
        if (!empty($settings['email_label'])) {
            $shortcode_atts[] = 'email_label="' . esc_attr($settings['email_label']) . '"';
        }
        if (!empty($settings['email_placeholder'])) {
            $shortcode_atts[] = 'email_placeholder="' . esc_attr($settings['email_placeholder']) . '"';
        }
        if (!empty($settings['phone_label'])) {
            $shortcode_atts[] = 'phone_label="' . esc_attr($settings['phone_label']) . '"';
        }
        if (!empty($settings['phone_placeholder'])) {
            $shortcode_atts[] = 'phone_placeholder="' . esc_attr($settings['phone_placeholder']) . '"';
        }
        if (!empty($settings['submit_button_text'])) {
            $shortcode_atts[] = 'submit_btn="' . esc_attr($settings['submit_button_text']) . '"';
        }

        // Service selection
        if (!empty($settings['service_title'])) {
            $shortcode_atts[] = 'service_title="' . esc_attr($settings['service_title']) . '"';
        }
        if (!empty($settings['service_instruction'])) {
            $shortcode_atts[] = 'service_instruction="' . esc_attr($settings['service_instruction']) . '"';
        }
        if (!empty($settings['quote_title'])) {
            $shortcode_atts[] = 'quote_title="' . esc_attr($settings['quote_title']) . '"';
        }
        if (!empty($settings['clear_link_text'])) {
            $shortcode_atts[] = 'clear_text="' . esc_attr($settings['clear_link_text']) . '"';
        }

        // Customer info
        if (!empty($settings['info_title'])) {
            $shortcode_atts[] = 'info_title="' . esc_attr($settings['info_title']) . '"';
        }
        if (!empty($settings['first_name_label'])) {
            $shortcode_atts[] = 'first_name_label="' . esc_attr($settings['first_name_label']) . '"';
        }
        if (!empty($settings['first_name_placeholder'])) {
            $shortcode_atts[] = 'first_name_placeholder="' . esc_attr($settings['first_name_placeholder']) . '"';
        }
        if (!empty($settings['last_name_label'])) {
            $shortcode_atts[] = 'last_name_label="' . esc_attr($settings['last_name_label']) . '"';
        }
        if (!empty($settings['last_name_placeholder'])) {
            $shortcode_atts[] = 'last_name_placeholder="' . esc_attr($settings['last_name_placeholder']) . '"';
        }
        if (!empty($settings['email_address_label'])) {
            $shortcode_atts[] = 'email_address_label="' . esc_attr($settings['email_address_label']) . '"';
        }
        if (!empty($settings['email_address_placeholder'])) {
            $shortcode_atts[] = 'email_address_placeholder="' . esc_attr($settings['email_address_placeholder']) . '"';
        }
        if (!empty($settings['phone_label_customer'])) {
            $shortcode_atts[] = 'phone_label_customer="' . esc_attr($settings['phone_label_customer']) . '"';
        }
        if (!empty($settings['phone_placeholder_customer'])) {
            $shortcode_atts[] = 'phone_placeholder_customer="' . esc_attr($settings['phone_placeholder_customer']) . '"';
        }
        if (!empty($settings['alt_phone_label'])) {
            $shortcode_atts[] = 'alt_phone_label="' . esc_attr($settings['alt_phone_label']) . '"';
        }
        if (!empty($settings['alt_phone_placeholder'])) {
            $shortcode_atts[] = 'alt_phone_placeholder="' . esc_attr($settings['alt_phone_placeholder']) . '"';
        }

        // Build shortcode
        $shortcode = '[xtremecleans';
        if (!empty($shortcode_atts)) {
            $shortcode .= ' ' . implode(' ', $shortcode_atts);
        }
        $shortcode .= ']';
        
        ?>
        <div class="<?php echo esc_attr($wrapper_class); ?>"<?php echo $wrapper_id; ?>>
            <?php echo do_shortcode($shortcode); ?>
        </div>
        <?php
    }

    /**
     * Render widget output in the editor
     */
    protected function content_template() {
        ?>
        <#
        var wrapperClass = 'xtremecleans-elementor-wrapper';
        if (settings.custom_css_class) {
            wrapperClass += ' ' + settings.custom_css_class;
        }
        var wrapperId = settings.custom_css_id ? ' id="' + settings.custom_css_id + '"' : '';
        #>
        <div class="{{{ wrapperClass }}}"{{{ wrapperId }}}>
            <div class="xtremecleans-elementor-placeholder">
                <div class="xtremecleans-placeholder-icon">🧹</div>
                <h3 class="xtremecleans-placeholder-title"><?php echo esc_html__('XtremeCleans Booking Form', 'xtremecleans'); ?></h3>
                <p class="xtremecleans-placeholder-text"><?php echo esc_html__('The complete booking form will be displayed here on the frontend.', 'xtremecleans'); ?></p>
                <div class="xtremecleans-placeholder-badge">
                    <span><?php echo esc_html__('Preview in Frontend', 'xtremecleans'); ?></span>
                </div>
            </div>
        </div>
        <style>
        .xtremecleans-elementor-placeholder {
            padding: 60px 30px;
            background: linear-gradient(135deg, #4caf50 0%, #45a049 100%);
            text-align: center;
            border-radius: 12px;
            color: #fff;
            min-height: 300px;
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
        }
        .xtremecleans-placeholder-icon {
            font-size: 64px;
            margin-bottom: 20px;
            line-height: 1;
        }
        .xtremecleans-placeholder-title {
            margin: 0 0 12px;
            color: #fff;
            font-size: 26px;
            font-weight: 700;
        }
        .xtremecleans-placeholder-text {
            margin: 0 0 25px;
            color: rgba(255,255,255,0.95);
            font-size: 15px;
            max-width: 400px;
        }
        .xtremecleans-placeholder-badge {
            padding: 12px 24px;
            background: rgba(255,255,255,0.25);
            border-radius: 8px;
            display: inline-block;
            backdrop-filter: blur(10px);
        }
        .xtremecleans-placeholder-badge span {
            font-size: 12px;
            text-transform: uppercase;
            letter-spacing: 1.5px;
            font-weight: 600;
        }
        </style>
        <?php
    }
}
