<?php
declare(strict_types=1);

namespace Kernel\config;

class Config
{

    public function __construct(protected string $timeZone, protected string $environment)
    {
        date_default_timezone_set($this->timeZone);
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
            throw new \Exception('Environment file not found, please check your .env file');
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
}
