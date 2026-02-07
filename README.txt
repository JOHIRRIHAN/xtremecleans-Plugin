=== XtremeCleans ===
Contributors: Johir Rihan
Tags: xtremecleans, shortcodes, api, frontend
Requires at least: 5.0
Tested up to: 6.4
Stable tag: 1.1.0
Requires PHP: 7.4
License: GPLv2 or later
License URI: https://www.gnu.org/licenses/gpl-2.0.html

A WordPress plugin for XtremeCleans functionality with shortcodes, API integrations, and frontend features.

== Description ==

XtremeCleans is a comprehensive WordPress plugin that provides:

* **Shortcodes**: Easy-to-use shortcodes for displaying content, buttons, forms, and API data
* **API Integration**: Connect to external APIs with configurable settings
* **Frontend Features**: Beautiful, responsive UI components with modern styling

== Features ==

= Shortcodes =

* `[xtremecleans_info]` - Display information boxes with title and content
* `[xtremecleans_button]` - Create styled buttons with multiple style options
* `[xtremecleans_api_data]` - Display data from API endpoints with caching
* `[xtremecleans_form]` - Contact form with validation

= API Integration =

* Configurable API settings (URL and API Key)
* Support for GET, POST, PUT, DELETE requests
* Automatic caching for API responses
* Error handling and validation

= Frontend Features =

* Responsive design
* Modern CSS styling
* JavaScript form validation
* AJAX form submission support
* Success/error message handling

== Installation ==

1. Upload the `xtremecleans` folder to the `/wp-content/plugins/` directory
2. Activate the plugin through the 'Plugins' menu in WordPress
3. Go to Settings > XtremeCleans to configure API credentials (if needed)

== Usage ==

= Shortcode Examples =

**Info Box:**
`[xtremecleans_info title="My Title" content="My content here"]`

**Button:**
`[xtremecleans_button text="Click Me" url="https://example.com" style="primary"]`

**API Data:**
`[xtremecleans_api_data endpoint="/data" template="table" cache="3600"]`

**Contact Form:**
`[xtremecleans_form title="Contact Us" submit_text="Send Message"]`

= API Configuration =

1. Navigate to Settings > XtremeCleans
2. Enter your API Base URL
3. Enter your API Key
4. Save settings

= Button Styles =

Available button styles:
* `primary` - Blue button (default)
* `secondary` - Gray button
* `success` - Green button

= API Data Templates =

Available templates for API data:
* `default` - Raw data display
* `list` - Unordered list format
* `table` - Table format (for array data)

== Changelog ==

= 1.1.0 =
* Enhanced Jobber API integration with OAuth 2.0 support
* Added automatic token refresh functionality
* Improved error handling and diagnostic messages for Jobber connection
* Added "Fix Scopes Manually" feature for troubleshooting 403 errors
* Enhanced logging system for better debugging
* Fixed deposit amount calculation (hardcoded to $20.00)
* Improved payment processing flow with better error handling
* Added comprehensive data mapping for Jobber sync (client, quote, job)
* Enhanced admin UI with better status indicators
* Added manual sync option for orders to Jobber
* Improved scope tracking and verification

= 1.0.0 =
* Initial release
* Shortcodes implementation
* API integration with settings page
* Frontend features with CSS/JS
* Form handling and validation

