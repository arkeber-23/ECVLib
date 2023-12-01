<?php

declare(strict_types=1);

namespace Kernel\config;

use Kernel\Core\Logger\Errors;

class Config
{

    public function __construct(protected string $timeZone, protected string $environment)
    {
        date_default_timezone_set($this->timeZone);
        $errorsLogger = new Errors('ERRORS LOGGER ENVIRONMENT', 'errors.log', 'debug');
        $this->typeEnvironment($errorsLogger);
        $this->configure();
        $this->initSession();
        $this->setEnvironment($this->environment);
    }

    private function configure(): void
    {
        $protocol = isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on' ? 'https' : 'http';
        $host = $_SERVER['HTTP_HOST'];
        $script_name = isset($_SERVER['ORIG_SCRIPT_NAME']) ? $_SERVER['ORIG_SCRIPT_NAME'] : $_SERVER['SCRIPT_NAME'];

        $base_url = $protocol . '://' . $host . dirname($script_name);
        $base_url = str_replace('\\', '/', $base_url);

        define('BASE_URL', $base_url);
    }

    private function initSession(): void
    {
        session_start();
    }

    private function setEnvironment(string $environment): void
    {
        if (!file_exists($environment)) {
            throw new \Exception("Environment file '$environment' not found. Please check your .env file.");
        }

        $envContent = file_get_contents($environment);
        $envLines = explode("\n", $envContent);
        foreach ($envLines as $line) {
            $line = trim($line);
            if (strpos($line, '#') === 0 || empty($line)) {
                continue;
            }

            list($key, $value) = explode('=', $line, 2);
            $key = trim($key);
            $value = trim($value);

            if ($key && $value) {
                putenv("$key=$value");
            }
        }
    }

    private function typeEnvironment(Errors $logger): void
    {
        $env_actual = getenv('APP_ENV');
        $app_debug = getenv('APP_DEBUG');


        if (in_array($env_actual, ['dev', 'development', 'local']) && $app_debug == 'true') {
            $this->showError();
            return;
        } 
        
        if (in_array($env_actual, ['prod', 'production'])) {

            if (($app_debug == 'false') && version_compare(PHP_VERSION, '8', '>=')) {
                $logger->info('Production environment with debug off (PHP >= 8).');
                error_reporting(E_ALL & ~E_NOTICE & ~E_DEPRECATED & ~E_STRICT & ~E_USER_NOTICE & ~E_USER_DEPRECATED);
                ini_set('display_errors', 'Off');
            } else {
                $logger->emergency('Application environment is not set correctly in production.');
                header('HTTP/1.1 503 Service Unavailable.', true, 503);
                throw new \Exception('The application environment is not set correctly.');
            }
        }
    }

    private function showError(): void
    {
        ini_set('display_errors', 'On');
        ini_set('display_startup_errors', 'On');
        error_reporting(E_ALL);
    }
}
