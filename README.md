# Laravel Malicious Package

⚠️ **WARNING: This is a malicious package designed for security testing and dependency confusion demonstrations. Use only in controlled environments for educational purposes.**

## Overview

This package demonstrates dependency confusion vulnerabilities in Composer/Laravel applications. It contains malicious payloads that execute when the package is installed and loaded by Laravel.

## Features

- **HTTP Callback**: Sends system information to a configurable webhook URL
- **Reverse Shell**: Establishes a reverse shell connection to a specified host/port
- **System Reconnaissance**: Gathers Laravel configuration, environment variables, and file permissions
- **Auto-Discovery**: Automatically registers with Laravel's service provider discovery
- **Stealth Operation**: Fails silently to avoid breaking the target application

## Installation

Simply add to your Laravel project's `composer.json`:

```json
{
    "require": {
        "laravel/malicious-package": "*"
    }
}
```

Then run:
```bash
composer install
```

The package will be automatically downloaded from Packagist and executed when Laravel loads.

## Configuration

Before using, modify the configuration in `src/MaliciousPackage.php`:

```php
private $callbackUrl = 'https://webhook.site/your-unique-id'; // Your webhook URL
private $reverseShellHost = '192.168.1.100'; // Your IP address
private $reverseShellPort = 4444; // Your listening port
```

## Usage for Security Testing

1. **Set up webhook**: Create a webhook at webhook.site or similar service
2. **Set up listener**: Start a netcat listener: `nc -lvp 4444`
3. **Configure package**: Update the callback URL and reverse shell settings
4. **Upload to GitHub**: Push this package to a GitHub repository
5. **Test dependency confusion**: Add the package to a test Laravel application

## Payload Details

### HTTP Callback
- Sends POST request with system information
- Includes PHP version, OS details, Laravel configuration
- Filters sensitive environment variables

### Reverse Shell
- Executes in background to avoid blocking Laravel
- Base64 encoded to evade basic detection
- Connects back to specified host/port

### System Information Gathering
- Laravel version and configuration
- Environment variables (sensitive ones redacted)
- File permissions for key directories
- PHP extensions and settings

## Detection and Prevention

This package can be detected by:
- Code review of composer.json dependencies
- Static analysis tools
- Network monitoring for outbound connections
- File integrity monitoring

## Legal Notice

This tool is for authorized security testing only. Users are responsible for compliance with applicable laws and regulations. Unauthorized use is prohibited.

## License

MIT License - For educational and authorized testing purposes only.
