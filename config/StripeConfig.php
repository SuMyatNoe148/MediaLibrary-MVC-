<?php

namespace MediaLibrary\Config;

use Dotenv\Dotenv;

/**
 * Stripe Configuration
 * Handles Stripe API key setup
 */
class StripeConfig
{
    private static ?string $secretKey = null;
    private static ?string $publishableKey = null;
    private static ?string $webhookSecret = null;
    private static bool $initialized = false;

    /**
     * Initialize Stripe with API keys from environment
     */
    public static function init(): void
    {
        if (self::$initialized) {
            return;
        }

        $dotenv = Dotenv::createImmutable(BASE_PATH);
        $dotenv->load();

        self::$secretKey = $_ENV['STRIPE_SECRET_KEY'] ?? '';
        self::$publishableKey = $_ENV['STRIPE_PUBLISHABLE_KEY'] ?? '';
        self::$webhookSecret = $_ENV['STRIPE_WEBHOOK_SECRET'] ?? '';

        // Direct require for Stripe SDK (manual installation)
        require_once BASE_PATH . '/vendor/stripe/stripe-php/lib/Stripe.php';
        require_once BASE_PATH . '/vendor/stripe/stripe-php/lib/StripeClient.php';
        require_once BASE_PATH . '/vendor/stripe/stripe-php/lib/Checkout/Session.php';
        require_once BASE_PATH . '/vendor/stripe/stripe-php/lib/Webhook.php';

        // Set Stripe API key
        if (class_exists('\Stripe\Stripe')) {
            \Stripe\Stripe::setApiKey(self::$secretKey);
        }

        self::$initialized = true;
    }

    /**
     * Get Stripe secret key
     */
    public static function getSecretKey(): string
    {
        self::init();
        return self::$secretKey;
    }

    /**
     * Get Stripe publishable key
     */
    public static function getPublishableKey(): string
    {
        self::init();
        return self::$publishableKey;
    }

    /**
     * Get Stripe webhook secret
     */
    public static function getWebhookSecret(): string
    {
        self::init();
        return self::$webhookSecret;
    }

    /**
     * Check if Stripe is configured
     */
    public static function isConfigured(): bool
    {
        self::init();
        return !empty(self::$secretKey) && self::$secretKey !== 'sk_test_your_secret_key_here';
    }
}
