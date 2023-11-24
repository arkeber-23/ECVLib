<?php

namespace Kernel\Cli;

use Kernel\Cli\EcvCli;

class Commands
{

    private $argv;

    public function __construct($argv)
    {
        $this->argv = $argv;
        $this->setEnvironment('.env');
    }

    public function run()
    {
        if (count($this->argv) === 1) {
            $this->showMessageError('Command not found');
            ECVLibCli::showComandList();
            return;
        }

        switch ($this->argv[1]) {

            case 'create::all':
                self::validateArguments('Name is required');
                ECVLibCli::createAll($this->argv[2]);
                break;
            case 'serve':
                ECVLibCli::serve();
                break;
            case 'create::model':
                self::validateArguments('Model name is required');
                ECVLibCli::createModel($this->argv[2]);
                break;
            case 'create::controller':
                self::validateArguments('Controller name is required');
                ECVLibCli::createController($this->argv[2]);
                break;

            case 'create::migration':
                self::validateArguments('Migration name is required');
                ECVLibCli::createMigration($this->argv[2]);
                break;
            case 'create::middleware':
                self::validateArguments('Middleware name is required');
                ECVLibCli::createMiddleware($this->argv[2]);
                break;

            case 'migrate':
                ECVLibCli::migrate();
                break;

            case 'rollback':
                ECVLibCli::rollback();
                break;
            case '-h':
            case '--help':
            case 'help':
                ECVLibCli::showComandList();
                break;
            default:
                $this->showMessageError('Command not found');
                ECVLibCli::showComandList();
                break;
        }
    }

    private function showMessageError(string $message)
    {
        echo "\e[31m" . $message . "\e[0m";
    }

    private  function validateArguments(string $message = '')
    {
        if (!isset($this->argv[2])) {
            self::showMessageError($message);
            ECVLibCli::showComandList();
            return;
        }
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
