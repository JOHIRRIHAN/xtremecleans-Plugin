<?php
/**
 * API Integration Class
 *
 * Handles all API communication and requests
 *
 * @package XtremeCleans
 * @subpackage API
 * @since 1.0.0
 */

// Exit if accessed directly
if (!defined('ABSPATH')) {
    exit;
}

/**
 * XtremeCleans_API Class
 *
 * @since 1.0.0
 */
class XtremeCleans_API {
    
    /**
     * API Base URL
     *
     * @since 1.0.0
     * @var string
     */
    private $api_base_url;
    
    /**
     * API Key
     *
     * @since 1.0.0
     * @var string
     */
    private $api_key;
    
    /**
     * Constructor
     *
     * @since 1.0.0
     */
    public function __construct() {
        $this->refresh_credentials();
    }
    
    /**
     * Refresh API credentials from options
     * Also handles automatic token refresh if token is expired or expiring soon
     *
     * @since 1.0.0
     */
    public function refresh_credentials() {
        // Check for Jobber OAuth first (priority)
        $access_token = get_option('xtremecleans_jobber_access_token', '');
        if (!empty($access_token)) {
            // Check if token is expired or expiring soon (within 5 minutes)
            $token_expires = get_option('xtremecleans_jobber_token_expires', 0);
            $refresh_token = get_option('xtremecleans_jobber_refresh_token', '');
            
            // If token expires in less than 5 minutes (or already expired), refresh it
            if ($token_expires > 0 && $token_expires <= (time() + 300) && !empty($refresh_token)) {
                xtremecleans_log('Access token expiring soon or expired. Refreshing token...', 'info');
                
                // Load frontend class to use refresh method
<<<<<<< HEAD
                if (file_exists(XTREMECLEANS_PLUGIN_DIR . 'core/frontend/class-xtremecleans-frontend.php')) {
                    require_once XTREMECLEANS_PLUGIN_DIR . 'core/frontend/class-xtremecleans-frontend.php';
                    $frontend = XtremeCleans::get_instance()->frontend;
                    if ($frontend && method_exists($frontend, 'refresh_access_token')) {
                        $refresh_result = $frontend->refresh_access_token();
                        if (!is_wp_error($refresh_result) && isset($refresh_result['access_token'])) {
                            $access_token = $refresh_result['access_token'];
                            xtremecleans_log('Token refreshed successfully', 'info');
                        } else {
                            xtremecleans_log('Token refresh failed: ' . (is_wp_error($refresh_result) ? $refresh_result->get_error_message() : 'Unknown error'), 'error');
=======
                // NOTE: Do NOT call XtremeCleans::get_instance() here — it causes infinite recursion
                // during plugin initialization (get_instance → load_dependencies → new API → refresh_credentials → get_instance...)
                if (file_exists(XTREMECLEANS_PLUGIN_DIR . 'core/frontend/class-xtremecleans-frontend.php')) {
                    require_once XTREMECLEANS_PLUGIN_DIR . 'core/frontend/class-xtremecleans-frontend.php';
                    if (class_exists('XtremeCleans_Frontend')) {
                        $frontend = new XtremeCleans_Frontend();
                        if (method_exists($frontend, 'refresh_access_token')) {
                            $refresh_result = $frontend->refresh_access_token();
                            if (!is_wp_error($refresh_result) && isset($refresh_result['access_token'])) {
                                $access_token = $refresh_result['access_token'];
                                xtremecleans_log('Token refreshed successfully', 'info');
                            } else {
                                xtremecleans_log('Token refresh failed: ' . (is_wp_error($refresh_result) ? $refresh_result->get_error_message() : 'Unknown error'), 'error');
                            }
>>>>>>> 3378c4f (plugin last vertation updated in admin dashboard)
                        }
                    }
                }
            }
            
            $this->api_base_url = apply_filters('xtremecleans_jobber_api_base', 'https://api.getjobber.com/api');
            $this->api_key = $access_token;
            return;
        }
        
        // Fallback to legacy API credentials
        $this->api_base_url = xtremecleans_get_option('api_url', '');
        $this->api_key = xtremecleans_get_option('api_key', '');
    }
    
    /**
     * Make API request
     *
     * @since 1.0.0
     * @param string $endpoint API endpoint
     * @param array  $args     Request arguments
     * @param string $method   HTTP method (GET, POST, PUT, DELETE)
     * @return array|WP_Error Response data or error
     */
    public function make_request($endpoint, $args = array(), $method = 'GET') {
        if (empty($this->api_base_url) || empty($this->api_key)) {
            $error = new WP_Error(
                'missing_credentials',
                __('API credentials are not configured.', 'xtremecleans')
            );
            xtremecleans_log('API request failed: Missing credentials', 'error');
            return $error;
        }
        
        $url = trailingslashit($this->api_base_url) . ltrim($endpoint, '/');
        
        $default_args = array(
            'method'  => $method,
            'headers' => $this->get_request_headers($endpoint),
            'timeout' => 30,
        );
        
        // Merge with provided args
        $request_args = wp_parse_args($args, $default_args);
        
        // Handle body data for POST/PUT requests
        if (in_array($method, array('POST', 'PUT', 'PATCH'), true)) {
            if (isset($args['body'])) {
                $request_args['body'] = is_array($args['body']) 
                    ? wp_json_encode($args['body']) 
                    : $args['body'];
            }
        }
        
        // Log request
        xtremecleans_log(
            sprintf('Making %s request to: %s', $method, $url),
            'info'
        );
        
        $response = wp_remote_request($url, $request_args);
        
        if (is_wp_error($response)) {
            xtremecleans_log(
                sprintf('API request error: %s', $response->get_error_message()),
                'error'
            );
            return $response;
        }
        
        $body        = wp_remote_retrieve_body($response);
        $status_code = wp_remote_retrieve_response_code($response);
        
        // Debug status code
        xtremecleans_log("Checking for retry condition. Status: " . $status_code . " (Type: " . gettype($status_code) . ")", 'info');

        // Handle 401 Unauthorized (expired token)
        if ($status_code == 401 && !isset($args['is_retry'])) {
            xtremecleans_log('API returned 401 Unauthorized. Attempting to refresh token and retry...', 'info');
            
            // Try to refresh token
            if (file_exists(XTREMECLEANS_PLUGIN_DIR . 'core/frontend/class-xtremecleans-frontend.php')) {
                require_once XTREMECLEANS_PLUGIN_DIR . 'core/frontend/class-xtremecleans-frontend.php';
                
                // Get plugin instance safely
                if (class_exists('XtremeCleans')) {
                    $plugin = XtremeCleans::get_instance();
                    $frontend = isset($plugin->frontend) ? $plugin->frontend : null;
                    
                    if (!$frontend) {
                         $frontend = new XtremeCleans_Frontend();
                    }
                    
                    if ($frontend && method_exists($frontend, 'refresh_access_token')) {
                        $refresh_result = $frontend->refresh_access_token();
                        
                        if (!is_wp_error($refresh_result) && isset($refresh_result['access_token'])) {
                            xtremecleans_log('Token refreshed successfully during retry. Retrying request...', 'info');
                            
                            // Update API key
                            $this->api_key = $refresh_result['access_token'];
                            
                            // Update request headers with new token
                            $request_args['headers']['Authorization'] = 'Bearer ' . $this->api_key;
                            
                            // Set retry flag to prevent infinite loops
                            $args['is_retry'] = true;
                            
                            // Recursive call with new token
                            return $this->make_request($endpoint, $args, $method);
                        } else {
                            xtremecleans_log('Token refresh failed during retry: ' . (is_wp_error($refresh_result) ? $refresh_result->get_error_message() : 'Unknown error'), 'error');
                        }
                    }
                }
            }
        }
        
        $data = json_decode($body, true);
        
        // Log full response for debugging
        xtremecleans_log(
            sprintf('Jobber API Response: %s | Status: %d | Body: %s', $url, $status_code, substr($body, 0, 500)),
            'info'
        );
        
        if ($status_code >= 200 && $status_code < 300) {
            // Check for GraphQL top-level errors even if status is 200
            if (isset($data['errors']) && !empty($data['errors'])) {
                $error_message = __('GraphQL execution error.', 'xtremecleans');
                if (isset($data['errors'][0]['message'])) {
                    $error_message = $data['errors'][0]['message'];
                }
                
                xtremecleans_log(
                    sprintf('Jobber API GraphQL Error: %s | Status: %d | Full Response: %s', 
                        $error_message, $status_code, $body),
                    'error'
                );
                
                return new WP_Error(
                    'graphql_error',
                    sprintf(__('GraphQL Error: %s', 'xtremecleans'), $error_message),
                    array(
                        'status' => $status_code,
                        'data'  => $data,
                        'body'  => $body,
                    )
                );
            }

            xtremecleans_log(
                sprintf('API request successful: %s (Status: %d)', $url, $status_code),
                'info'
            );
            return $data;
        } else {
            // Extract detailed error message from response
            $error_message = __('API request failed.', 'xtremecleans');
            if (isset($data['error'])) {
                if (is_string($data['error'])) {
                    $error_message = $data['error'];
                } elseif (isset($data['error']['message'])) {
                    $error_message = $data['error']['message'];
                }
            } elseif (isset($data['message'])) {
                $error_message = $data['message'];
            }
            
            $error = new WP_Error(
                'api_error',
                sprintf(__('API request failed: %s (Status: %d)', 'xtremecleans'), $error_message, $status_code),
                array(
                    'status' => $status_code,
                    'data'  => $data,
                    'body'  => $body,
                )
            );
            xtremecleans_log(
                sprintf('API request failed: %s | Status: %d | Error: %s | Full Response: %s', 
                    $url, $status_code, $error_message, $body),
                'error'
            );
            return $error;
        }
    }
    
    /**
     * Get request headers
     *
     * @since 1.0.0
     * @param string $endpoint API endpoint (optional)
     * @return array Request headers
     */
    private function get_request_headers($endpoint = '') {
        $headers = array(
            'Authorization' => 'Bearer ' . $this->api_key,
            'Content-Type'  => 'application/json',
            'Accept'        => 'application/json',
        );
        
        // Jobber GraphQL API requires version header (for GraphQL endpoints)
        // REST API endpoints may use different versioning
        $api_version = apply_filters('xtremecleans_jobber_api_version', '2025-04-16');
        if (!empty($api_version)) {
            // Check if this is a GraphQL endpoint
            $is_graphql = strpos($this->api_base_url, '/graphql') !== false || 
                         strpos($endpoint, '/graphql') !== false ||
                         strpos($endpoint, 'graphql') !== false;
            
            if ($is_graphql) {
                $headers['X-JOBBER-GRAPHQL-VERSION'] = $api_version;
            }
        }
        
        return $headers;
    }
    
    /**
     * GET request
     *
     * @since 1.0.0
     * @param string $endpoint API endpoint
     * @param array  $args     Request arguments
     * @return array|WP_Error
     */
    public function get($endpoint, $args = array()) {
        return $this->make_request($endpoint, $args, 'GET');
    }
    
    /**
     * POST request
     *
     * @since 1.0.0
     * @param string $endpoint API endpoint
     * @param array  $body     Request body
     * @param array  $args     Additional request arguments
     * @return array|WP_Error
     */
    public function post($endpoint, $body = array(), $args = array()) {
        $args['body'] = $body;
        return $this->make_request($endpoint, $args, 'POST');
    }
    
    /**
     * PUT request
     *
     * @since 1.0.0
     * @param string $endpoint API endpoint
     * @param array  $body     Request body
     * @param array  $args     Additional request arguments
     * @return array|WP_Error
     */
    public function put($endpoint, $body = array(), $args = array()) {
        $args['body'] = $body;
        return $this->make_request($endpoint, $args, 'PUT');
    }
    
    /**
     * DELETE request
     *
     * @since 1.0.0
     * @param string $endpoint API endpoint
     * @param array  $args     Request arguments
     * @return array|WP_Error
     */
    public function delete($endpoint, $args = array()) {
        return $this->make_request($endpoint, $args, 'DELETE');
    }
    
    /**
     * Get API base URL
     *
     * @since 1.0.0
     * @return string API base URL
     */
    public function get_api_url() {
        return $this->api_base_url;
    }
    
    /**
     * Check if API is configured
     *
     * @since 1.0.0
     * @return bool True if configured
     */
    public function is_configured() {
        // Check for Jobber OAuth first
        $access_token = get_option('xtremecleans_jobber_access_token', '');
        if (!empty($access_token)) {
            return true;
        }
        
        // Fallback to legacy API credentials
        return !empty($this->api_base_url) && !empty($this->api_key);
    }
    
    /**
     * Execute GraphQL query or mutation
     *
     * @since 1.1.0
     * @param string $query GraphQL query or mutation string
     * @param array  $variables Variables for the query/mutation
     * @return array|WP_Error Response data or error
     */
    public function graphql_query($query, $variables = array()) {
        return $this->graphql_mutation($query, $variables);
    }
    
    /**
     * Execute GraphQL mutation
     *
     * @since 1.1.0
     * @param string $mutation GraphQL mutation string
     * @param array  $variables Variables for the mutation
     * @return array|WP_Error Response data or error
     */
    public function graphql_mutation($mutation, $variables = array()) {
        if (empty($this->api_key)) {
            return new WP_Error(
                'missing_credentials',
                __('Access token is not configured.', 'xtremecleans')
            );
        }
        
        // Ensure we have a valid access token (refresh if needed)
        $this->refresh_credentials();
        
        $graphql_url = 'https://api.getjobber.com/api/graphql';
        
        $api_version = apply_filters('xtremecleans_jobber_api_version', '2025-04-16');
        
        $headers = array(
            'Authorization' => 'Bearer ' . $this->api_key,
            'Content-Type'  => 'application/json',
            'Accept'        => 'application/json',
            'X-JOBBER-GRAPHQL-VERSION' => $api_version,
        );
        
        $body = array(
            'query' => $mutation,
        );
        
        if (!empty($variables)) {
            $body['variables'] = $variables;
        }
        
        xtremecleans_log('GraphQL Mutation: ' . substr($mutation, 0, 100) . '...', 'info');
        
        $response = wp_remote_post($graphql_url, array(
            'headers' => $headers,
            'body'    => wp_json_encode($body),
            'timeout' => 30,
        ));
        
        if (is_wp_error($response)) {
            xtremecleans_log('GraphQL mutation error: ' . $response->get_error_message(), 'error');
            return $response;
        }
        
        $status_code = wp_remote_retrieve_response_code($response);
        $body_text = wp_remote_retrieve_body($response);
        $data = json_decode($body_text, true);
        
        // Log response status and full raw body (CRITICAL for debugging)
        xtremecleans_log('GraphQL Response: Status ' . $status_code . ', Body length: ' . strlen($body_text), 'info');
        xtremecleans_log('=== FULL RAW RESPONSE BODY ===', 'info');
        xtremecleans_log($body_text, 'info');
        xtremecleans_log('=== END RAW RESPONSE BODY ===', 'info');
        
        // Log decoded data structure summary
        if ($data) {
            xtremecleans_log('GraphQL Response Data Structure: ' . wp_json_encode(array(
                'has_data' => isset($data['data']),
                'has_top_level_errors' => isset($data['errors']) && !empty($data['errors']),
                'data_keys' => isset($data['data']) ? array_keys($data['data']) : array(),
            )), 'info');
        } else {
            xtremecleans_log('WARNING: GraphQL response could not be decoded as JSON. Full raw body logged above.', 'error');
        }
        
        if ($status_code >= 200 && $status_code < 300) {
            // CRITICAL: Check for top-level GraphQL errors FIRST
            // GraphQL reports errors in two places:
            // 1. Top-level 'errors' array: authorization, request formatting, or execution issues
            //    When these exist, mutations fail early and data may be null/empty (quote: null, job: null)
            //    userErrors will be empty because validation never runs
            // 2. 'userErrors' array: validation-type issues (missing fields, bad values, etc.)
            //    These occur after the request passes initial checks
            // Even with top-level errors, GraphQL returns 200 status code, so we must check errors array
            if (isset($data['errors']) && !empty($data['errors'])) {
                xtremecleans_log('=== TOP-LEVEL GRAPHQL ERRORS DETECTED ===', 'error');
                xtremecleans_log('EXPLANATION: GraphQL reports errors in two places. Top-level errors array contains', 'error');
                xtremecleans_log('authorization, request formatting, or execution issues. These occur BEFORE validation runs.', 'error');
                xtremecleans_log('When top-level errors exist: quote/job comes back as null, userErrors is empty, and request still returns 200.', 'error');
                xtremecleans_log('This is different from userErrors (validation issues like missing fields or bad values).', 'error');
                xtremecleans_log('GraphiQL always shows these top-level errors, but custom applications must explicitly check for them.', 'error');
                $error_messages = array();
                $error_details = array();
                foreach ($data['errors'] as $index => $error) {
                    $message = isset($error['message']) ? $error['message'] : 'Unknown GraphQL error';
                    $error_messages[] = $message;
                    
                    // Capture full error details including extensions, locations, etc.
                    $error_details[] = $error;
                    
                    xtremecleans_log('Top-level Error #' . ($index + 1) . ': ' . $message, 'error');
                    if (isset($error['extensions'])) {
                        xtremecleans_log('Error Extensions: ' . wp_json_encode($error['extensions']), 'error');
                    }
                    if (isset($error['locations'])) {
                        xtremecleans_log('Error Locations: ' . wp_json_encode($error['locations']), 'error');
                    }
                }
                xtremecleans_log('=== END TOP-LEVEL ERRORS ===', 'error');
                
                $error_msg = implode('; ', $error_messages);
                xtremecleans_log('CRITICAL: GraphQL mutation has top-level errors (authorization/formatting/execution): ' . $error_msg, 'error');
                xtremecleans_log('Full top-level errors array: ' . wp_json_encode($error_details), 'error');
                
                // Return error with full context
                return new WP_Error(
                    'graphql_top_level_errors',
                    sprintf(__('GraphQL top-level errors (authorization/formatting/execution): %s', 'xtremecleans'), $error_msg),
                    array(
                        'errors' => $error_details,
                        'data' => $data,
                        'raw_body' => $body_text,
                        'body' => $body_text, // Keep both for backward compatibility
                    )
                );
            }
            
            // Check for userErrors in mutation response (validation errors)
            // NOTE: We should NOT return WP_Error for userErrors - let the calling code handle them
            // This allows the calling code to check for quote: null or job: null scenarios
            // userErrors are validation-type issues (missing required fields, invalid values, etc.)
            // These occur AFTER the request passes authorization and formatting checks
            if (isset($data['data'])) {
                // Log userErrors but don't return error - let calling code decide
                foreach ($data['data'] as $key => $value) {
                    if (isset($value['userErrors']) && !empty($value['userErrors'])) {
                        xtremecleans_log('=== USERERRORS DETECTED IN ' . strtoupper($key) . ' ===', 'error');
                        xtremecleans_log('NOTE: userErrors are validation issues (missing fields, bad values, etc.).', 'error');
                        xtremecleans_log('These occur AFTER authorization/formatting checks pass. Top-level errors were not present.', 'error');
                        $error_messages = array();
                        foreach ($value['userErrors'] as $error) {
                            $error_messages[] = isset($error['message']) ? $error['message'] : 'Unknown error';
                        }
                        $error_msg = implode('; ', $error_messages);
                        xtremecleans_log('GraphQL userErrors (validation errors) in ' . $key . ': ' . $error_msg, 'error');
                        xtremecleans_log('Full userErrors for ' . $key . ': ' . wp_json_encode($value['userErrors']), 'error');
                        xtremecleans_log('=== END USERERRORS ===', 'error');
                        // Don't return error here - let calling code handle userErrors
                    }
                    
                    // Check for null result with no userErrors (could indicate top-level error was missed)
                    // The value might be the mutation result itself (like quoteCreate or jobCreate)
                    // Check if the main result field (quote, job, etc.) is null
                    $result_field = null;
                    if (isset($value['quote'])) {
                        $result_field = 'quote';
                    } elseif (isset($value['job'])) {
                        $result_field = 'job';
                    } elseif (isset($value['client'])) {
                        $result_field = 'client';
                    }
                    
                    if ($result_field && isset($value[$result_field]) && is_null($value[$result_field])) {
                        $has_user_errors = isset($value['userErrors']) && !empty($value['userErrors']);
                        if (!$has_user_errors) {
                            xtremecleans_log('WARNING: ' . $key . ' mutation returned null ' . $result_field . ' with no userErrors.', 'error');
                            xtremecleans_log('This pattern (null result + empty userErrors) typically indicates a top-level error was present.', 'error');
                            xtremecleans_log('However, no top-level errors were detected - this may indicate an edge case. Full response logged above.', 'error');
                        }
                    } elseif (is_null($value)) {
                        $has_user_errors = isset($data['data'][$key]['userErrors']) && !empty($data['data'][$key]['userErrors']);
                        if (!$has_user_errors) {
                            xtremecleans_log('WARNING: ' . $key . ' mutation result is null with no userErrors.', 'error');
                            xtremecleans_log('This pattern (null result + empty userErrors) typically indicates a top-level error was present.', 'error');
                            xtremecleans_log('However, no top-level errors were detected - this may indicate an edge case. Full response logged above.', 'error');
                        }
                    }
                }
            } else {
                // No data field in response - this is unusual for a 200 response
                xtremecleans_log('WARNING: GraphQL response has status 200 but no "data" field. This may indicate an error. Full response logged above.', 'error');
            }
            
            return $data;
        } else {
            // Non-200 status code - HTTP level error
            xtremecleans_log('=== HTTP ERROR STATUS CODE: ' . $status_code . ' ===', 'error');
            xtremecleans_log('Full raw response body (HTTP error): ' . $body_text, 'error');
            
            $error_message = __('GraphQL mutation failed.', 'xtremecleans');
            if (isset($data['message'])) {
                $error_message = $data['message'];
            } elseif (isset($data['error'])) {
                $error_message = is_string($data['error']) ? $data['error'] : (isset($data['error']['message']) ? $data['error']['message'] : $error_message);
            }
            
            // Check if there are top-level errors in the response even with non-200 status
            if (isset($data['errors']) && !empty($data['errors'])) {
                xtremecleans_log('Top-level errors found in non-200 response: ' . wp_json_encode($data['errors']), 'error');
            }
            
            $error = new WP_Error(
                'graphql_http_error',
                sprintf(__('GraphQL mutation failed: %s (Status: %d)', 'xtremecleans'), $error_message, $status_code),
                array(
                    'status' => $status_code,
                    'data'  => $data,
                    'body'  => $body_text,
                )
            );
            xtremecleans_log('GraphQL mutation HTTP error: ' . $error_message . ' | Status: ' . $status_code, 'error');
            return $error;
        }
    }
}

