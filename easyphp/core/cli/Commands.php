<?php

namespace easyphp\core\cli;

use easyphp\core\libs\EasyEnvLoader;

class Commands
{

    private $argv;

    public function __construct($argv)
    {
        $this->argv = $argv;
        $this->loadEnvironment();
    }

    public function execute()
    {
        if (count($this->argv) === 1) {
            $this->showErrorMessage("No se pasaron argumentos por CLI.");
            $this->showHelp();
            return 1;
        }

        $command = $this->argv[1];

        switch ($command) {
            case "serve":
                CliEasy::serve();
                break;
            
            case "create:model":
                $modelName = $this->argv[2] ?? '';
                $this->executeCreateModel($modelName);
                break;

            case "create:controller":
                $controllerName = $this->argv[2] ?? '';
                $this->executeCreateController($controllerName);
                break;

            case "create:migration":
                $migrationName = $this->argv[2] ?? '';
                $this->executeCreateMigration($migrationName);
                break;

            case "rollback":
                $this->executeRollback();
                break;

            case "create:all":
                $all = $this->argv[2] ?? '';
                $this->executeCreateAll($all);
                break;

            case "migrate":
                $this->executeMigrate();
                break;

            case "-h":
            case "--help":
                $this->showHelp();
                break;

            default:
                $this->showErrorMessage("Comando no reconocido.");
                $this->showHelp();
                return 1;
        }

        return 0;
    }

    private function loadEnvironment()
    {
        $easyphp = new EasyEnvLoader('.env');
        $easyphp->load();
    }

    private function showErrorMessage($message)
    {
        echo "\e[31m$message\e[0m\n";
    }

    private function showSuccessMessage($message){
    
        echo "\e[32m$message\e[0m\n";
    }

    private function executeCreateModel($modelName)
    {
        if (!empty($modelName)) {
            CliEasy::createModel($modelName);
            $this->showSuccessMessage("Modelo creado: $modelName");
        } else {
            $this->showErrorMessage("Nombre del modelo faltante.");
            $this->showHelp();
            exit(1);
        }
    }

    private function executeCreateController($controllerName)
    {
        if (!empty($controllerName)) {
            CliEasy::createController($controllerName);
            $this->showSuccessMessage("Controlador creado: $controllerName");
        } else {
            $this->showErrorMessage("Nombre del controlador faltante.");
            $this->showHelp();
            exit(1);
        }
    }

    private function executeCreateMigration($migrationName)
    {
        if (!empty($migrationName)) {
            CliEasy::createMigration($migrationName);
            $this->showSuccessMessage("Migración creada: $migrationName");
        } else {
            $this->showErrorMessage("Nombre de la migración faltante.");
            $this->showHelp();
            exit(1);
        }
    }

    private function executeRollback()
    {
        CliEasy::rollback();
        $this->showSuccessMessage("Migración revertida.");
        exit(0);
    }

    private function executeCreateAll($all)
    {
        if (!empty($all)) {
            CliEasy::createAll($all);
        } else {
            $this->showErrorMessage("Nombre faltante.");
            $this->showHelp();
            exit(1);
        }
    }

    private function executeMigrate()
    {
        CliEasy::migrate();
        exit(0);
    }

    private function showHelp()
    {
        CliEasy::command();
        exit(0);
    }
}

 