<?php

namespace MediaLibrary\Config;

/**
 * Stripe Configuration
 * Handles Stripe API key initialization
 */
class StripeConfig
{
    /**
     * Initialize Stripe with API key from environment
     */
    public static function init(): void
    {
        // Load Stripe SDK
        require_once BASE_PATH . '/vendor/stripe/stripe-php/init.php';

        $secretKey = $_ENV['STRIPE_SECRET_KEY'] ?? null;
        
        if (!$secretKey) {
            // Fallback to .env file if not in $_ENV
            $envFile = __DIR__ . '/../../.env';
            error_log('StripeConfig: Looking for .env at ' . $envFile);
            error_log('StripeConfig: File exists: ' . (file_exists($envFile) ? 'yes' : 'no'));
            
            if (file_exists($envFile)) {
                $envLines = file($envFile, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
                error_log('StripeConfig: Found ' . count($envLines) . ' lines in .env');
                
                foreach ($envLines as $line) {
                    if (strpos($line, 'STRIPE_SECRET_KEY=') === 0) {
                        $secretKey = trim(substr($line, strlen('STRIPE_SECRET_KEY=')));
                        error_log('StripeConfig: Found key, length: ' . strlen($secretKey));
                        break;
                    }
                }
            }
        }
        
        error_log('StripeConfig: Final key value: ' . ($secretKey ? substr($secretKey, 0, 20) . '...' : 'null'));
        
        if (!$secretKey || $secretKey === 'sk_test_your_secret_key_here') {
            throw new \Exception('Stripe secret key not configured');
        }
        
        \Stripe\Stripe::setApiKey($secretKey);
    }

    /**
     * Get publishable key
     */
    public static function getPublishableKey(): string
    {
        $publishableKey = $_ENV['STRIPE_PUBLISHABLE_KEY'] ?? null;
        
        if (!$publishableKey) {
            $envFile = __DIR__ . '/../../.env';
            if (file_exists($envFile)) {
                $envContent = file_get_contents($envFile);
                preg_match('/STRIPE_PUBLISHABLE_KEY=(.+)/', $envContent, $matches);
                $publishableKey = trim($matches[1] ?? '');
            }
        }
        
        return $publishableKey ?: '';
    }
}
