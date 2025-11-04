<?php
/**
 * AI helper functions
 */

use Seed\Modules\AI\AI;

// Get AI provider instance
function ai($provider = null) {
    return AI::provider($provider);
}

// Quick chat with default provider
function ai_chat($prompt, $options = []) {
    return AI::chat($prompt, $options);
}

// OpenAI provider shortcut
function ai_openai() {
    return AI::provider('openai');
}

// Claude provider shortcut
function ai_claude() {
    return AI::provider('claude');
}

