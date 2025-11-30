<?php
/**
 * AIResponse - Unified response wrapper for AI providers
 */

namespace Seed\Modules\AI;

class AIResponse {
    protected $rawResponse;
    protected $content;
    protected $model;
    protected $tokens;
    protected $finishReason;
    
    public function __construct($rawResponse, $content, $model, $tokens, $finishReason = null) {
        $this->rawResponse = $rawResponse;
        $this->content = $content;
        $this->model = $model;
        $this->tokens = $tokens;
        $this->finishReason = $finishReason;
    }
    
    // Get response content/text
    public function content() {
        return $this->content;
    }
    
    // Get raw API response
    public function raw() {
        return $this->rawResponse;
    }
    
    // Get model used
    public function model() {
        return $this->model;
    }
    
    // Get token usage
    public function tokens() {
        return $this->tokens;
    }
    
    // Get finish reason
    public function finishReason() {
        return $this->finishReason;
    }
    
    // Convert to array
    public function toArray() {
        return [
            'content' => $this->content,
            'model' => $this->model,
            'tokens' => $this->tokens->toArray(),
            'finish_reason' => $this->finishReason
        ];
    }
    
    // Magic toString
    public function __toString() {
        return $this->content;
    }
}

