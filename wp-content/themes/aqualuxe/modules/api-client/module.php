<?php
// API Client module: simple, cached HTTP GET with sanitization and WP HTTP API.

if (!function_exists('aqlx_http_get_json')) {
    /**
     * Fetch JSON from a remote URL safely with caching.
     * @param string $url Absolute URL (https recommended).
     * @param int $ttl Cache seconds.
     * @return array{ ok:bool, code?:int, data?:mixed, error?:string }
     */
    function aqlx_http_get_json(string $url, int $ttl = 600): array {
        if (!filter_var($url, FILTER_VALIDATE_URL)) {
            return ['ok' => false, 'error' => 'invalid_url'];
        }
        if (strpos($url, 'http') !== 0) {
            return ['ok' => false, 'error' => 'scheme'];
        }
        $key = 'aqlx_http_' . md5($url);
        $cached = get_transient($key);
        if (is_array($cached)) { return $cached; }
        $resp = wp_remote_get($url, [ 'timeout' => 10, 'redirection' => 3, 'reject_unsafe_urls' => true ]);
        if (is_wp_error($resp)) {
            $out = ['ok' => false, 'error' => $resp->get_error_message()];
            set_transient($key, $out, $ttl);
            return $out;
        }
        $code = (int) wp_remote_retrieve_response_code($resp);
        $body = (string) wp_remote_retrieve_body($resp);
        $data = json_decode($body, true);
        $ok = $code >= 200 && $code < 300 && (is_array($data) || is_object($data));
        $out = $ok ? ['ok' => true, 'code' => $code, 'data' => $data] : ['ok' => false, 'code' => $code, 'error' => 'bad_response'];
        set_transient($key, $out, $ttl);
        return $out;
    }
}

// Optional proxy endpoint (disabled by default); enable via filter to avoid open proxy.
add_action('rest_api_init', function(){
    $enabled = apply_filters('aqualuxe/api/proxy_enabled', false);
    if (!$enabled) return;
    register_rest_route('aqualuxe/v1', '/proxy', [
        'methods' => 'GET',
        'permission_callback' => function(){ return current_user_can('manage_options'); },
        'args' => [ 'url' => [ 'required' => true ] ],
        'callback' => function($req){
            $url = esc_url_raw((string) $req->get_param('url'));
            return aqlx_http_get_json($url);
        }
    ]);
});
