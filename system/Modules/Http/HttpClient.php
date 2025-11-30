<?php
/**
 * HttpClient - Make HTTP requests using cURL
 */

namespace Seed\Modules\Http;

class HttpClient {
    private $baseUrl = '';
    private $headers = [];
    private $timeout = 30;
    private $auth = [];
    
    public function __construct($baseUrl = '') {
        $this->baseUrl = rtrim($baseUrl, '/');
    }
    
    // Set base URL
    public function baseUrl($url) {
        $this->baseUrl = rtrim($url, '/');
        return $this;
    }
    
    // Set timeout
    public function timeout($seconds) {
        $this->timeout = $seconds;
        return $this;
    }
    
    // Set header
    public function header($key, $value) {
        $this->headers[$key] = $value;
        return $this;
    }
    
    // Set multiple headers
    public function headers($headers) {
        $this->headers = array_merge($this->headers, $headers);
        return $this;
    }
    
    // Set API key authentication
    public function apiKey($key, $header = 'X-API-Key') {
        $this->headers[$header] = $key;
        return $this;
    }
    
    // Set basic authentication
    public function basicAuth($username, $password) {
        $this->auth = [
            'type' => 'basic',
            'username' => $username,
            'password' => $password
        ];
        return $this;
    }
    
    // Set bearer token authentication
    public function bearerToken($token) {
        $this->headers['Authorization'] = "Bearer {$token}";
        return $this;
    }
    
    // GET request
    public function get($uri, $params = []) {
        return $this->request('GET', $uri, ['query' => $params]);
    }
    
    // POST request
    public function post($uri, $data = [], $asJson = true) {
        return $this->request('POST', $uri, [
            'body' => $data,
            'json' => $asJson
        ]);
    }
    
    // PUT request
    public function put($uri, $data = [], $asJson = true) {
        return $this->request('PUT', $uri, [
            'body' => $data,
            'json' => $asJson
        ]);
    }
    
    // DELETE request
    public function delete($uri, $data = []) {
        return $this->request('DELETE', $uri, [
            'body' => $data,
            'json' => true
        ]);
    }
    
    // PATCH request
    public function patch($uri, $data = [], $asJson = true) {
        return $this->request('PATCH', $uri, [
            'body' => $data,
            'json' => $asJson
        ]);
    }
    
    // Make HTTP request
    private function request($method, $uri, $options = []) {
        $url = $this->buildUrl($uri, $options['query'] ?? []);
        
        $ch = curl_init();
        
        // Set URL
        curl_setopt($ch, CURLOPT_URL, $url);
        
        // Set method
        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, $method);
        
        // Return response instead of outputting
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        
        // Set timeout
        curl_setopt($ch, CURLOPT_TIMEOUT, $this->timeout);
        
        // Set headers
        $headers = $this->buildHeaders($options['json'] ?? false);
        if (!empty($headers)) {
            curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
        }
        
        // Set authentication
        if (!empty($this->auth)) {
            if ($this->auth['type'] === 'basic') {
                curl_setopt($ch, CURLOPT_USERPWD, $this->auth['username'] . ':' . $this->auth['password']);
            }
        }
        
        // Set body
        if (!empty($options['body'])) {
            $body = $options['json'] ?? false
                ? json_encode($options['body'])
                : http_build_query($options['body']);
            
            curl_setopt($ch, CURLOPT_POSTFIELDS, $body);
        }
        
        // Execute request
        $response = curl_exec($ch);
        $statusCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        $error = curl_error($ch);
        
        curl_close($ch);
        
        // Handle error
        if ($error) {
            return new HttpResponse($statusCode, $response, ['error' => $error]);
        }
        
        return new HttpResponse($statusCode, $response);
    }
    
    // Build full URL
    private function buildUrl($uri, $params = []) {
        $url = $this->baseUrl ? $this->baseUrl . '/' . ltrim($uri, '/') : $uri;
        
        if (!empty($params)) {
            $url .= '?' . http_build_query($params);
        }
        
        return $url;
    }
    
    // Build headers array
    private function buildHeaders($isJson = false) {
        $headers = $this->headers;
        
        if ($isJson && !isset($headers['Content-Type'])) {
            $headers['Content-Type'] = 'application/json';
        }
        
        $formatted = [];
        foreach ($headers as $key => $value) {
            $formatted[] = "{$key}: {$value}";
        }
        
        return $formatted;
    }
}

