<?php
/**
 * ClaudeProvider - Anthropic Claude API integration (Sonnet 4, 4.5)
 */

namespace Seed\Modules\AI\Providers;

use Seed\Modules\AI\AIProvider;
use Seed\Modules\AI\AIResponse;
use Seed\Modules\AI\TokenUsage;

class ClaudeProvider extends AIProvider {
    protected $apiUrl = 'https://api.anthropic.com/v1/messages';
    protected $apiVersion = '2023-06-01';
    
    // Supported models with metadata
    protected $models = [
        'claude-sonnet-4-20250514' => ['max_tokens' => 8192, 'context' => 200000],
        'claude-sonnet-4.5-20250514' => ['max_tokens' => 8192, 'context' => 200000],
        'claude-sonnet-4-latest' => ['max_tokens' => 8192, 'context' => 200000],
    ];
    
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
            'model' => $params['model'] ?? $this->model ?? 'claude-sonnet-4.5-20250514',
            'max_tokens' => (int) ($params['max_tokens'] ?? 4096),
            'messages' => $this->buildMessages($params)
        ];
        
        // Add system prompt if set (Claude uses separate system field)
        if (isset($params['system']) && $params['system']) {
            $payload['system'] = $params['system'];
        }
        
        // Add optional parameters
        if (isset($params['temperature'])) {
            $payload['temperature'] = (float) $params['temperature'];
        }
        
        if (isset($params['top_p'])) {
            $payload['top_p'] = (float) $params['top_p'];
        }
        
        // Claude-specific parameters
        if (isset($params['top_k'])) {
            $payload['top_k'] = (int) $params['top_k'];
        }
        
        if (isset($params['stop_sequences'])) {
            $payload['stop_sequences'] = $params['stop_sequences'];
        }
        
        if (isset($params['metadata'])) {
            $payload['metadata'] = $params['metadata'];
        }
        
        return $payload;
    }
    
    // Build messages array (Claude format)
    protected function buildMessages($params) {
        // Claude requires alternating user/assistant messages
        // System prompt is handled separately
        return array_filter($this->messages, function($msg) {
            return $msg['role'] !== 'system';
        });
    }
    
    // Map parameters to Claude format
    protected function mapParameters($params) {
        // Most parameters are compatible
        // Claude-specific differences are handled in buildPayload
        return $params;
    }
    
    // Get request headers
    protected function getHeaders() {
        return [
            'Content-Type: application/json',
            'x-api-key: ' . $this->apiKey,
            'anthropic-version: ' . $this->apiVersion
        ];
    }
    
    // Parse response
    protected function parseResponse($response) {
        $data = json_decode($response, true);
        
        if (!$data || !isset($data['content'][0])) {
            throw new \Seed\Modules\AI\Exceptions\AIException('Invalid Claude response');
        }
        
        $content = $data['content'][0]['text'] ?? '';
        $model = $data['model'] ?? $this->model;
        $finishReason = $data['stop_reason'] ?? null;
        
        // Token usage
        $usage = $data['usage'] ?? [];
        $tokens = new TokenUsage(
            $usage['input_tokens'] ?? 0,
            $usage['output_tokens'] ?? 0
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

