<?php
/**
 * TokenUsage - Token tracking for AI responses
 */

namespace Seed\Modules\AI;

class TokenUsage {
    protected $promptTokens;
    protected $completionTokens;
    protected $totalTokens;
    
    public function __construct($promptTokens, $completionTokens, $totalTokens = null) {
        $this->promptTokens = $promptTokens;
        $this->completionTokens = $completionTokens;
        $this->totalTokens = $totalTokens ?? ($promptTokens + $completionTokens);
    }
    
    // Get prompt tokens
    public function prompt() {
        return $this->promptTokens;
    }
    
    // Get completion tokens
    public function completion() {
        return $this->completionTokens;
    }
    
    // Get total tokens
    public function total() {
        return $this->totalTokens;
    }
    
    // Estimate cost (provider-specific, override in providers)
    public function cost() {
        return null; // Override in specific providers if needed
    }
    
    // Convert to array
    public function toArray() {
        return [
            'prompt' => $this->promptTokens,
            'completion' => $this->completionTokens,
            'total' => $this->totalTokens
        ];
    }
}

