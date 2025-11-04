<?php
/**
 * AI Interface Tests
 */

TestRunner::suite('AI INTERFACE');

use Seed\Modules\AI\AI;
use Seed\Modules\AI\AIProvider;
use Seed\Modules\AI\AIResponse;
use Seed\Modules\AI\TokenUsage;

TestRunner::test("AI core classes exist", function() {
    return class_exists('Seed\Modules\AI\AI') &&
           class_exists('Seed\Modules\AI\AIProvider') &&
           class_exists('Seed\Modules\AI\AIResponse') &&
           class_exists('Seed\Modules\AI\TokenUsage');
});

TestRunner::test("AI provider classes exist", function() {
    return class_exists('Seed\Modules\AI\Providers\OpenAIProvider') &&
           class_exists('Seed\Modules\AI\Providers\ClaudeProvider');
});

TestRunner::test("AI exception classes exist", function() {
    return class_exists('Seed\Modules\AI\Exceptions\AIException') &&
           class_exists('Seed\Modules\AI\Exceptions\RateLimitException') &&
           class_exists('Seed\Modules\AI\Exceptions\InvalidModelException') &&
           class_exists('Seed\Modules\AI\Exceptions\AuthenticationException');
});

TestRunner::test("TokenUsage class works", function() {
    $tokens = new TokenUsage(100, 50);
    return $tokens->prompt() === 100 &&
           $tokens->completion() === 50 &&
           $tokens->total() === 150;
});

TestRunner::test("TokenUsage toArray works", function() {
    $tokens = new TokenUsage(100, 50, 150);
    $array = $tokens->toArray();
    return is_array($array) &&
           $array['prompt'] === 100 &&
           $array['completion'] === 50 &&
           $array['total'] === 150;
});

TestRunner::test("AIResponse class works", function() {
    $tokens = new TokenUsage(100, 50);
    $response = new AIResponse(
        ['raw' => 'data'],
        'Test response',
        'gpt-4o',
        $tokens,
        'stop'
    );
    return $response->content() === 'Test response' &&
           $response->model() === 'gpt-4o' &&
           $response->finishReason() === 'stop';
});

TestRunner::test("AIResponse toArray works", function() {
    $tokens = new TokenUsage(100, 50);
    $response = new AIResponse(['raw'], 'Test', 'gpt-4o', $tokens);
    $array = $response->toArray();
    return is_array($array) && isset($array['content']) && isset($array['tokens']);
});

TestRunner::test("AIResponse toString works", function() {
    $tokens = new TokenUsage(100, 50);
    $response = new AIResponse(['raw'], 'Test response', 'gpt-4o', $tokens);
    return (string)$response === 'Test response';
});

TestRunner::test("AI has provider() method", function() {
    return method_exists('Seed\Modules\AI\AI', 'provider');
});

TestRunner::test("AI has chat() method", function() {
    return method_exists('Seed\Modules\AI\AI', 'chat');
});

