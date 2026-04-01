<?php
/**
 * Google Maps Travel Time – Geocoding & Distance Matrix
 * Strict 1-hour rule: use duration_in_traffic only; any error = fail.
 *
 * @package XtremeCleans
 * @subpackage Google
 * @since 1.0.0
 */

if (!defined('ABSPATH')) {
    exit;
}

class XtremeCleans_Google_Travel {

    const TRAVEL_MAX_MINUTES = 60;
    const GEOCODE_URL = 'https://maps.googleapis.com/maps/api/geocode/json';
    const DISTANCE_MATRIX_URL = 'https://maps.googleapis.com/maps/api/distancematrix/json';

    /**
     * Geocode an address to lat,lng.
     *
     * @param string $address Full address or "address1, city, state zip".
     * @param string $api_key Google API key.
     * @return array|null {'lat' => float, 'lng' => float} or null on failure.
     */
    public static function geocode($address, $api_key) {
        if (empty($address) || empty($api_key)) {
            return null;
        }
        $url = add_query_arg(array(
            'address' => $address,
            'key'     => $api_key,
        ), self::GEOCODE_URL);
        $response = wp_remote_get($url, array('timeout' => 15));
        if (is_wp_error($response)) {
            if (function_exists('xtremecleans_log')) {
                xtremecleans_log('Geocoding request failed: ' . $response->get_error_message(), 'error');
            }
            return null;
        }
        $code = wp_remote_retrieve_response_code($response);
        $body = wp_remote_retrieve_body($response);
        $data = json_decode($body, true);
        if ($code !== 200 || empty($data['status']) || $data['status'] !== 'OK') {
            if (function_exists('xtremecleans_log')) {
                xtremecleans_log('Geocoding API status not OK: ' . (isset($data['status']) ? $data['status'] : $code) . ' ' . substr($body, 0, 200), 'error');
            }
            return null;
        }
        if (empty($data['results'][0]['geometry']['location'])) {
            return null;
        }
        $loc = $data['results'][0]['geometry']['location'];
        return array(
            'lat' => isset($loc['lat']) ? floatval($loc['lat']) : 0,
            'lng' => isset($loc['lng']) ? floatval($loc['lng']) : 0,
        );
    }

    /**
     * Get driving duration in traffic (seconds) from origin to destination at departure time.
     * Strict: only duration_in_traffic; any error or missing field = fail.
     *
     * @param float  $origin_lat      Origin latitude.
     * @param float  $origin_lng      Origin longitude.
     * @param float  $dest_lat        Destination latitude.
     * @param float  $dest_lng        Destination longitude.
     * @param int    $departure_unix  Departure time as Unix timestamp.
     * @param string $api_key         Google API key.
     * @return int|WP_Error Duration in seconds, or WP_Error on any failure.
     */
    public static function get_duration_in_traffic_seconds($origin_lat, $origin_lng, $dest_lat, $dest_lng, $departure_unix, $api_key) {
        if (empty($api_key)) {
            return new WP_Error('missing_key', __('Google API key is not configured.', 'xtremecleans'));
        }
        $origins = $origin_lat . ',' . $origin_lng;
        $destinations = $dest_lat . ',' . $dest_lng;
        $url = add_query_arg(array(
            'origins'        => $origins,
            'destinations'   => $destinations,
            'departure_time' => $departure_unix,
            'traffic_model'  => 'best_guess',
            'mode'           => 'driving',
            'key'            => $api_key,
        ), self::DISTANCE_MATRIX_URL);
        $response = wp_remote_get($url, array('timeout' => 15));
        if (is_wp_error($response)) {
            if (function_exists('xtremecleans_log')) {
                xtremecleans_log('Distance Matrix request failed: ' . $response->get_error_message(), 'error');
            }
            return new WP_Error('network', __('Travel time check failed. Please try another time or contact us.', 'xtremecleans'));
        }
        $code = wp_remote_retrieve_response_code($response);
        $body = wp_remote_retrieve_body($response);
        $data = json_decode($body, true);
        if ($code !== 200) {
            if (function_exists('xtremecleans_log')) {
                xtremecleans_log('Distance Matrix HTTP error: ' . $code . ' ' . substr($body, 0, 300), 'error');
            }
            return new WP_Error('api_error', __('Travel time check failed.', 'xtremecleans'));
        }
        if (empty($data['status']) || $data['status'] !== 'OK') {
            if (function_exists('xtremecleans_log')) {
                xtremecleans_log('Distance Matrix status not OK: ' . (isset($data['status']) ? $data['status'] : '') . ' ' . substr($body, 0, 300), 'error');
            }
            return new WP_Error('api_status', __('Travel time check failed.', 'xtremecleans'));
        }
        if (empty($data['rows'][0]['elements'][0])) {
            if (function_exists('xtremecleans_log')) {
                xtremecleans_log('Distance Matrix missing rows/elements: ' . substr($body, 0, 200), 'error');
            }
            return new WP_Error('api_structure', __('Travel time check failed.', 'xtremecleans'));
        }
        $element = $data['rows'][0]['elements'][0];
        if (empty($element['status']) || $element['status'] !== 'OK') {
            if (function_exists('xtremecleans_log')) {
                xtremecleans_log('Distance Matrix element status not OK: ' . (isset($element['status']) ? $element['status'] : '') . ' ' . substr($body, 0, 200), 'error');
            }
            return new WP_Error('element_status', __('Travel time check failed.', 'xtremecleans'));
        }
        if (!isset($element['duration_in_traffic']['value'])) {
            if (function_exists('xtremecleans_log')) {
                xtremecleans_log('Distance Matrix duration_in_traffic missing. Do not fallback to duration.', 'error');
            }
            return new WP_Error('no_duration_in_traffic', __('Travel time check failed.', 'xtremecleans'));
        }
        $seconds = intval($element['duration_in_traffic']['value']);
        if ($seconds <= 0 || $seconds > 86400) {
            if (function_exists('xtremecleans_log')) {
                xtremecleans_log('Distance Matrix duration_in_traffic invalid: ' . $seconds, 'error');
            }
            return new WP_Error('invalid_duration', __('Travel time check failed.', 'xtremecleans'));
        }
        return $seconds;
    }
}
