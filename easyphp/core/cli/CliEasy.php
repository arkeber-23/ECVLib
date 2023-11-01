<?php

namespace easyphp\core\cli;

use easyphp\core\db\Database;
use Easyphp\Core\Logs\ErrorLog;

class CliEasy
{

    public static function createAll($name)
    {
        self::createModel($name);
        self::createController($name);
        self::createMigration($name);
    }

    public static function createModel($name)
    {
        $nameModel = ucfirst($name) . 'Model';

        $plantilla = <<<EOT
        <?php

        namespace app\models;
    
        use easyphp\core\models\EasyModel;
    
        class $nameModel extends EasyModel 
        {
            /**
                 * @var string \$table
                 * Nombre de la tabla en la base de datos asociada a este modelo.
             */
            protected \$table = "$name";
    
            public function __construct() {
                parent::__construct();
            } 
        }
        EOT;
        $rute = './app/models/' . $nameModel . '.php';
        try {
            self::createModelFile($rute, $plantilla);
        } catch (\Exception $e) {
            echo "\e[31mError al generar el modelo: {$e->getMessage()}\e[0m\n";
        }
    }
    public static function createController($name)
    {
        $controller = ucfirst($name) . 'Controller';
        $plantilla = <<<EOT
        <?php

        namespace app\controllers;
        
        class $controller
        {
                
        
        }
        EOT;
        $rute = './app/controllers/' . $controller . '.php';
        try {
            self::createModelFile($rute, $plantilla);
        } catch (\Exception $e) {
            echo "\e[31mError al generar el controlador: {$e->getMessage()}\e[0m\n";
        }
    }

    public static function createMigration($name)
    {
        $newName = date('Y_m_d') . '_' . date('His') . '_' . $name;
        $oldname = ucfirst($name);
        $plantilla = <<<EOT
        <?php

            use easyphp\core\\easyorm\GenerateSql;
            use easyphp\core\\easyorm\Table;

            class  $oldname extends GenerateSql
            {
                public function up(){
                    GenerateSql::create('$oldname', function (Table \$table) {
                        \$table->id();
                        \$table->string('nombre');
                        \$table->timeStamps();
                    });
                }
                public function down(){
                   GenerateSql::droptable('$oldname');
                }
            }
        EOT;

        $rute = './app/migrations/' . $newName .  '.php';
        if (!is_dir('./app/migrations/' . $newName)) {
            try {
                self::createModelFile($rute, $plantilla);
            } catch (\Exception $e) {
                echo "\e[31mError al generar la migracion: {$e->getMessage()}\e[0m\n";
            }
        }
    }

    public static function migrate()
    {

        $directory = './app/migrations/';
        $migrationFiles = glob($directory . '*.php');

        foreach ($migrationFiles as $migrationFile) {
            $className = pathinfo($migrationFile, PATHINFO_FILENAME);
            $className = preg_replace('/^[0-9_]+/', '', $className);
            $className = ucfirst($className);


            require_once $migrationFile;
            if (class_exists($className)) {
                $migration = new $className();
                $migration->up();
            }
        }
    }

    public static function rollback()
    {

        $directory = './app/migrations/';
        $migrationFiles = glob($directory . '*.php');

        usort($migrationFiles, function ($a, $b) {
            return filemtime($b) - filemtime($a);
        });


        foreach ($migrationFiles as $migrationFile) {
            $className = pathinfo($migrationFile, PATHINFO_FILENAME);
            $className = preg_replace('/^[0-9_]+/', '', $className);
            $className = ucfirst($className);

            require_once $migrationFile;
            if (class_exists($className)) {
                $migration = new $className();
                $migration->down();
            }
        }
    }

    private static function createModelFile($nombreModelo, $plantilla)
    {
        if (file_put_contents($nombreModelo, $plantilla) !== false) {
            echo "\e[32mArchvivo generado: $nombreModelo\e[0m\n";
        } else {
            echo "\e[31mError al generar el archivo.\e[0m\n";
        }
    }

    public static function command()
    {
        $command = <<<EOT

        \e[32mThor (Generador de archivos en EasyPHP):\e[0m
        Thor es una herramienta de línea de comandos que simplifica la generación de archivos en EasyPHP.

        \e[32mComandos:\e[0m
        \e[34mthor create:model <name>\e[0m - Crea un archivo de modelo en EasyPHP.
        \e[34mthor create:controller <name>\e[0m - Crea un archivo de controlador en EasyPHP.
        \e[34mthor create:migration <name>\e[0m - Crea un archivo de migracion en EasyPHP.
        
        \e[32mMigraciones:\e[0m
        \e[34mthor migrate\e[0m - Ejecuta todas las migraciones
        \e[34mthor rollback\e[0m - Revierte todas las migraciones

        \e[32mAyuda:\e[0m
        \e[34mthor -h\e[0m  Muestra esta ayuda
        EOT;
        echo $command;
    }

    public static function serve()
    {
        $commonPorts = [80, 8080, 8888, 8000, 9090, 3000, 4000, 5000];
        $availablePort = null;
        foreach ($commonPorts as $port) {
            $socket = @fsockopen('localhost', $port);
            if (!$socket) {
                $availablePort = $port;
                break;
            }
            fclose($socket);
        }

        if ($availablePort) {
            $host = 'localhost';
            $docroot = getcwd();

            $command = "php -S $host:$availablePort -t $docroot\\index.php";
            echo "run server http://$host:$availablePort\n";

            exec("$command > /dev/null 2>&1 &");

            echo "\e[34mWeb sever started. Press Ctrl+C to finish.\e[0m\n";
            passthru("php -S $host:$availablePort -t $docroot");
        } else {
            echo "No available port.\n";
        }
    }
}
