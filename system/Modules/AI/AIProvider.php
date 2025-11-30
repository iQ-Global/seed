<?php
/**
 * AIProvider - Base class for AI providers
 */

namespace Seed\Modules\AI;

abstract class AIProvider {
    protected $apiKey;
    protected $model;
    protected $messages = [];
    protected $systemPrompt = null;
    protected $config = [];
    
    // Common parameters across providers
    protected $commonParams = ['model', 'temperature', 'max_tokens', 'top_p', 'system'];
    
    public function __construct($apiKey, $config = []) {
        $this->apiKey = $apiKey;
        $this->config = $config;
        $this->model = $config['default_model'] ?? null;
    }
    
    // Set model
    public function model($model) {
        $this->model = $model;
        return $this;
    }
    
    // Set system prompt
    public function system($prompt) {
        $this->systemPrompt = $prompt;
        return $this;
    }
    
    // Add message to conversation
    public function addMessage($role, $content) {
        $this->messages[] = [
            'role' => $role,
            'content' => $content
        ];
        return $this;
    }
    
    // Quick chat (single message)
    public function chat($prompt, $options = []) {
        $this->messages = [];
        $this->addMessage('user', $prompt);
        return $this->send($options);
    }
    
    // Send messages with conversation history
    public function messages($messages) {
        $this->messages = $messages;
        return $this;
    }
    
    // Send request
    abstract public function send($options = []);
    
    // Stream response
    abstract public function stream($callback = null);
    
    // Map parameters to provider-specific format
    abstract protected function mapParameters($params);
    
    // Build request payload
    abstract protected function buildPayload($options);
    
    // Make HTTP request
    protected function request($url, $payload, $stream = false) {
        $ch = curl_init($url);
        
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($payload));
        curl_setopt($ch, CURLOPT_HTTPHEADER, $this->getHeaders());
        
        if ($stream) {
            curl_setopt($ch, CURLOPT_WRITEFUNCTION, function($curl, $data) use ($stream) {
                if ($stream && is_callable($stream)) {
                    $stream($data);
                }
                return strlen($data);
            });
        }
        
        $response = curl_exec($ch);
        $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        
        if (curl_errno($ch)) {
            $error = curl_error($ch);
            curl_close($ch);
            throw new Exceptions\AIException("cURL error: {$error}");
        }
        
        curl_close($ch);
        
        // Handle HTTP errors
        if ($httpCode >= 400) {
            $this->handleHttpError($httpCode, $response);
        }
        
        return $response;
    }
    
    // Get request headers
    abstract protected function getHeaders();
    
    // Handle HTTP errors
    protected function handleHttpError($code, $response) {
        $decoded = json_decode($response, true);
        $message = $decoded['error']['message'] ?? 'Unknown error';
        
        switch ($code) {
            case 401:
            case 403:
                throw new Exceptions\AuthenticationException("Authentication failed: {$message}");
            case 429:
                throw new Exceptions\RateLimitException("Rate limit exceeded: {$message}");
            case 400:
                throw new Exceptions\InvalidModelException("Invalid request: {$message}");
            default:
                throw new Exceptions\AIException("AI API error ({$code}): {$message}");
        }
    }
    
    // Prepare request with common and provider-specific params
    protected function prepareRequest($options) {
        // Add system prompt if set
        if ($this->systemPrompt && !isset($options['system'])) {
            $options['system'] = $this->systemPrompt;
        }
        
        // Add model if set
        if ($this->model && !isset($options['model'])) {
            $options['model'] = $this->model;
        }
        
        return $this->mapParameters($options);
    }
}

