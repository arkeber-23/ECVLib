<?php

namespace Kernel\Core\Logger;

use Monolog\Handler\StreamHandler;
use Monolog\Logger;
use Psr\Log\LoggerInterface;
use Psr\Log\InvalidArgumentException;
use Psr\Log\LogLevel;

class Errors  implements LoggerInterface
{
    protected $logger;

    public function __construct($name, $nameFile = 'errors.log', $level = 'info')
    {
        $this->logger = new Logger($name);
        $dir = !empty(getenv('LOG_DIR')) ? getenv('LOG_DIR') : __DIR__ . '/../../../Logs';
        if (!is_dir($dir)) {
            mkdir($dir, 0777, true);
        }
        $this->logger->pushHandler(new StreamHandler($dir . '/' . $nameFile, $this->getMonologLevel($level)));
    }

    public function emergency($message, array $context = []): void
    {
        if ((getenv('APP_ENV') == 'dev' || getenv('APP_ENV') == 'development') && getenv('APP_DEBUG') == 'true') {
            $this->showMessageDev($message);
        } else {
            $this->logger->log(LogLevel::EMERGENCY, $message, $context);
        }
    }


    public function alert($message, array $context = []): void
    {
        if ((getenv('APP_ENV') == 'dev' || getenv('APP_ENV') == 'development') && getenv('APP_DEBUG') == 'true') {
            $this->showMessageDev($message);
        } else {
            $this->logger->log(LogLevel::ALERT, $message, $context);
        }
    }

    public function critical($message, array $context = []): void
    {
        if ((getenv('APP_ENV') == 'dev' || getenv('APP_ENV') == 'development') && getenv('APP_DEBUG') == 'true') {
            $this->showMessageDev($message);
        } else {
            $this->logger->log(LogLevel::CRITICAL, $message, $context);
        }
    }

    public function error($message, array $context = []): void
    {
        if ((getenv('APP_ENV') == 'dev' || getenv('APP_ENV') == 'development') && getenv('APP_DEBUG') == 'true') {
            $this->showMessageDev($message);
        } else {
            $this->logger->log(LogLevel::ERROR, $message, $context);
        }
    }

    public function warning($message, array $context = []): void
    {
        if ((getenv('APP_ENV') == 'dev' || getenv('APP_ENV') == 'development') && getenv('APP_DEBUG') == 'true') {
            $this->showMessageDev($message);
        } else {
            $this->logger->log(LogLevel::WARNING, $message, $context);
        }
    }

    public function notice($message, array $context = []): void
    {
        if ((getenv('APP_ENV') == 'dev' || getenv('APP_ENV') == 'development') && getenv('APP_DEBUG') == 'true') {
            $this->showMessageDev($message);
        } else {
            $this->logger->log(LogLevel::NOTICE, $message, $context);
        }
    }

    public function info($message, array $context = []): void
    {
        if ((getenv('APP_ENV') == 'dev' || getenv('APP_ENV') == 'development') && getenv('APP_DEBUG') == 'true') {
            $this->showMessageDev($message);
        } else {
            $this->logger->log(LogLevel::INFO, $message, $context);
        }
    }

    public function debug($message, array $context = []): void
    {
        if ((getenv('APP_ENV') == 'dev' || getenv('APP_ENV') == 'development') && getenv('APP_DEBUG') == 'true') {
            $this->showMessageDev($message);
        } else {
            $this->logger->log(LogLevel::DEBUG, $message, $context);
        }
    }

    public function log($level, $message, array $context = []): void
    {
        $this->logger->log($this->getMonologLevel($level), $message, $context);
    }

    protected function getMonologLevel($level)
    {
        $logLevels = [
            'emergency' => LogLevel::EMERGENCY,
            'alert' => LogLevel::ALERT,
            'critical' => LogLevel::CRITICAL,
            'error' => LogLevel::ERROR,
            'warning' => LogLevel::WARNING,
            'notice' => LogLevel::NOTICE,
            'info' => LogLevel::INFO,
            'debug' => LogLevel::DEBUG,
        ];

        if (!isset($logLevels[$level])) {
            throw new InvalidArgumentException('Invalid log level');
        }

        return $logLevels[$level];
    }

    private function showMessageDev($message)
    {
        echo '<pre>';
        print_r($message);
        echo '</pre>';
    }
}
