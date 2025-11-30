<?php
/**
 * OpenAIProvider - OpenAI API integration (GPT-4o, GPT-5)
 */

namespace Seed\Modules\AI\Providers;

use Seed\Modules\AI\AIProvider;
use Seed\Modules\AI\AIResponse;
use Seed\Modules\AI\TokenUsage;

class OpenAIProvider extends AIProvider {
    protected $apiUrl = 'https://api.openai.com/v1/chat/completions';
    protected $organization;
    
    // Supported models with metadata
    protected $models = [
        'gpt-4o' => ['max_tokens' => 4096, 'context' => 128000],
        'gpt-4o-mini' => ['max_tokens' => 4096, 'context' => 128000],
        'gpt-5' => ['max_tokens' => 8192, 'context' => 200000],
        'gpt-5-preview' => ['max_tokens' => 8192, 'context' => 200000],
        'gpt-5-turbo' => ['max_tokens' => 4096, 'context' => 128000],
    ];
    
    public function __construct($apiKey, $config = []) {
        parent::__construct($apiKey, $config);
        $this->organization = $config['organization'] ?? '';
    }
    
    // Send request
    public function send($options = []) {
        $payload = $this->buildPayload($options);
        $response = $this->request($this->apiUrl, $payload);
        
        return $this->parseResponse($response);
    }
    
    // Stream response
    public function stream($callback = null) {
        $payload = $this->buildPayload([]);
        $payload['stream'] = true;
        
        if ($callback && is_callable($callback)) {
            // Streaming with callback
            $this->request($this->apiUrl, $payload, $callback);
            return null;
        }
        
        // Return generator for manual iteration
        return $this->streamGenerator($payload);
    }
    
    // Build request payload
    protected function buildPayload($options) {
        $params = $this->prepareRequest($options);
        
        $payload = [
            'model' => $params['model'] ?? $this->model ?? 'gpt-4o',
            'messages' => $this->buildMessages($params)
        ];
        
        // Add optional parameters
        if (isset($params['temperature'])) {
            $payload['temperature'] = (float) $params['temperature'];
        }
        
        if (isset($params['max_tokens'])) {
            $payload['max_tokens'] = (int) $params['max_tokens'];
        }
        
        if (isset($params['top_p'])) {
            $payload['top_p'] = (float) $params['top_p'];
        }
        
        // OpenAI-specific parameters
        if (isset($params['frequency_penalty'])) {
            $payload['frequency_penalty'] = (float) $params['frequency_penalty'];
        }
        
        if (isset($params['presence_penalty'])) {
            $payload['presence_penalty'] = (float) $params['presence_penalty'];
        }
        
        if (isset($params['response_format'])) {
            $payload['response_format'] = $params['response_format'];
        }
        
        if (isset($params['seed'])) {
            $payload['seed'] = (int) $params['seed'];
        }
        
        return $payload;
    }
    
    // Build messages array
    protected function buildMessages($params) {
        $messages = $this->messages;
        
        // Add system prompt if set
        if (isset($params['system']) && $params['system']) {
            array_unshift($messages, [
                'role' => 'system',
                'content' => $params['system']
            ]);
        }
        
        return $messages;
    }
    
    // Map parameters to OpenAI format
    protected function mapParameters($params) {
        // OpenAI uses the same naming as our common params
        return $params;
    }
    
    // Get request headers
    protected function getHeaders() {
        $headers = [
            'Content-Type: application/json',
            'Authorization: Bearer ' . $this->apiKey
        ];
        
        if ($this->organization) {
            $headers[] = 'OpenAI-Organization: ' . $this->organization;
        }
        
        return $headers;
    }
    
    // Parse response
    protected function parseResponse($response) {
        $data = json_decode($response, true);
        
        if (!$data || !isset($data['choices'][0])) {
            throw new \Seed\Modules\AI\Exceptions\AIException('Invalid OpenAI response');
        }
        
        $choice = $data['choices'][0];
        $content = $choice['message']['content'] ?? '';
        $model = $data['model'] ?? $this->model;
        $finishReason = $choice['finish_reason'] ?? null;
        
        // Token usage
        $usage = $data['usage'] ?? [];
        $tokens = new TokenUsage(
            $usage['prompt_tokens'] ?? 0,
            $usage['completion_tokens'] ?? 0,
            $usage['total_tokens'] ?? 0
        );
        
        return new AIResponse($data, $content, $model, $tokens, $finishReason);
    }
    
    // Stream generator (for manual iteration)
    protected function streamGenerator($payload) {
        // Implementation for streaming would go here
        // For now, return regular response
        $response = $this->request($this->apiUrl, $payload);
        yield $this->parseResponse($response)->content();
    }
}

