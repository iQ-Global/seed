<?php
/**
 * AI - Factory/Manager for AI providers
 */

namespace Seed\Modules\AI;

use Seed\Modules\AI\Providers\OpenAIProvider;
use Seed\Modules\AI\Providers\ClaudeProvider;

class AI {
    protected static $providers = [];
    protected static $defaultProvider = null;
    
    // Get AI provider instance
    public static function provider($name = null) {
        $name = $name ?? env('AI_DEFAULT_PROVIDER', 'openai');
        
        if (!isset(self::$providers[$name])) {
            self::$providers[$name] = self::createProvider($name);
        }
        
        return self::$providers[$name];
    }
    
    // Create provider instance
    protected static function createProvider($name) {
        switch ($name) {
            case 'openai':
                return new OpenAIProvider(
                    env('OPENAI_API_KEY'),
                    [
                        'default_model' => env('OPENAI_DEFAULT_MODEL', 'gpt-4o'),
                        'organization' => env('OPENAI_ORGANIZATION', '')
                    ]
                );
                
            case 'claude':
            case 'anthropic':
                return new ClaudeProvider(
                    env('CLAUDE_API_KEY'),
                    [
                        'default_model' => env('CLAUDE_DEFAULT_MODEL', 'claude-sonnet-4.5-20250514')
                    ]
                );
                
            default:
                throw new Exceptions\AIException("Unsupported AI provider: {$name}");
        }
    }
    
    // Quick access methods
    public static function chat($prompt, $options = []) {
        return self::provider()->chat($prompt, $options);
    }
    
    public static function model($model) {
        return self::provider()->model($model);
    }
    
    public static function system($prompt) {
        return self::provider()->system($prompt);
    }
    
    public static function messages($messages) {
        return self::provider()->messages($messages);
    }
}

