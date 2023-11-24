<?php

namespace Kernel\Cli;

class ECVLibCli
{

    public static function createAll(string $name)
    {
        self::createModel($name);
        self::createController($name);
        self::createMigration($name);
    }

    public static function  showComandList()
    {
        $command = <<<EOT
        \e[32m \nECVLib version: 0.0.2\e[0m\n
        \e[32mThor (Generador de archivos en ECVLib):\e[0m
        Thor es una herramienta de línea de comandos que simplifica la generación de archivos en ECVLib.

        \e[32mComandos:\e[0m
        \e[34mthor create::model <name>\e[0m - Crea un archivo de modelo en ECVLib.
        \e[34mthor create::controller <name>\e[0m - Crea un archivo de controlador en ECVLib.
        \e[34mthor create::migration <name>\e[0m - Crea un archivo de migracion en ECVLib.
        \e[34mthor create::all <name>\e[0m - Crea un archivo de modelo, controlador y migracion en ECVLib.
        \e[34mthor create::middleware <name>\e[0m - Crea un archivo de middleware en ECVLib.

        \e[32mMigraciones:\e[0m
        \e[34mthor migrate\e[0m - Ejecuta todas las migraciones
        \e[34mthor rollback\e[0m - Revierte todas las migraciones

        \e[32mAyuda:\e[0m
        \e[34mthor -h\e[0m  Muestra esta ayuda\n
        EOT;
        echo $command;
    }

    public static function serve()
    {
        $commonPorts = [8080, 8888, 8000, 9090, 3000, 4000, 5000];
        $availablePort = null;
        foreach ($commonPorts as $port) {
            $socket = @fsockopen('localhost', $port);
            if ($socket === false) {
                $availablePort = $port;
                break;
            }
            fclose($socket);
        }

        if ($availablePort) {
            $host = 'localhost';
            $docroot = getcwd();

            $command = "php -S $host:$availablePort -t $docroot";
            echo "Run server http://$host:$availablePort\n";

            echo "\e[34mWeb server started. Press Ctrl+C to finish.\e[0m\n";

            passthru($command);
        } else {
            echo "No available port.\n";
        }
    }

    public static function  createModel(string $name)
    {
        $nameMdl = ucfirst($name) . "Model";
        $template = <<<EOT
        <?php

        namespace App\Models;

        use Kernel\models\EcvLibModel;

        class $nameMdl extends EcvLibModel
        {
            protected \$table = '{$name}';

            public function __construct()
            {
                parent::__construct();
            }
        }
        EOT;
        $dir = "src/Models/$nameMdl.php";
        try {
            self::saveFile($dir, $template);
        } catch (\Exception $e) {
            echo "\e[31mError: {$e->getMessage()}\e[0m\n";
        }
    }

    public static function createController(string $name)
    {
        $nameCtl = ucfirst($name) . "Controller";
        $template = <<<EOT
        <?php

        namespace App\Controllers;



        class $nameCtl
        {
            public function index()
            {
            }
        }
        EOT;
        $dir = "src/Controllers/$nameCtl.php";
        try {
            self::saveFile($dir, $template);
        } catch (\Exception $e) {
            echo "\e[31mError: {$e->getMessage()}\e[0m\n";
        }
    }

    public static function createMigration(string $name)
    {
        $newName = date('Y_m_d') . '_' . date('His') . '_' . $name;
        $template = <<<EOT
        <?php

        use Kernel\Core\EcvOrm\AdapterSqlGenerator;
        use Kernel\Core\EcvOrm\Table;

        return new class extends AdapterSqlGenerator {

            public function up()
            {
                AdapterSqlGenerator::create('$name', function (Table \$table) {
                    \$table->id();
                    \$table->string('name');
                    \$table->timeStamps();
                });
            }

            public function down()
            {
                AdapterSqlGenerator::dropTable('$name'); 
            }
        };
        EOT;
        $dir = "src/Migrations/$newName.php";
        try {
            self::saveFile($dir, $template);
        } catch (\Exception $e) {
            echo "\e[31mError: {$e->getMessage()}\e[0m\n";
        }
    }

    public static function createMiddleware(string $name)
    {
        $nameMdl = ucfirst($name) . "Middleware";
        $template = <<<EOT
        <?php

        namespace App\Middlewares;

        use Kernel\Core\Libs\Request;
        use Kernel\Core\Libs\Response;
        use Kernel\Core\Middleware\MiddlewareBase;

        class $nameMdl extends MiddlewareBase
        {

                public function handleInput(Request \$request)
                {

                    return \$request;
                }
                public function process(Request \$request, Response \$response, \$next)
                {
                    return \$next(\$request, \$response);
                }

                public function handleOutput(Response \$response)
                {

                    return \$response;
                }
        }
        EOT;

        $dir = "src/Middlewares/$nameMdl.php";
        try {
            self::saveFile($dir, $template);
        } catch (\Exception $e) {
            echo "\e[31mError: {$e->getMessage()}\e[0m\n";
        }
    }



    public static function migrate()
    {
        echo "\n\e[44;97mExecuting migrations......\e[0m\n";

        $directory = './src/Migrations/';
        $migrationFiles = glob($directory . '*.php');

        foreach ($migrationFiles as $migrationFile) {
            $start_time = microtime(true);

            include_once $migrationFile;
            try {
                $migration = (require $migrationFile);
                $migration->up();

                $end_time = microtime(true);
                $execution_time = number_format(($end_time - $start_time) * 1000, 2);
                $nameFile = basename(pathinfo($migrationFile, PATHINFO_FILENAME));
                echo "$nameFile " .  str_repeat('.', 50 - strlen($nameFile))   . "\t$execution_time ms \e[32mDONE\e[0m\n";
            } catch (\Exception $e) {
                echo "\e[31mError: {$e->getMessage()}\e[0m\n";
            }
        }
    }


    public static function rollback()
    {

        echo "\n\e[44;97mExecuting rollback migrations ......\e[0m\n";

        $directory = './src/Migrations/';
        $migrationFiles = glob($directory . '*.php');

        usort($migrationFiles, function ($a, $b) {
            return filemtime($b) - filemtime($a);
        });


        foreach ($migrationFiles as $migrationFile) {
            $start_time = microtime(true);

            include_once $migrationFile;
            try {
                $migration = (require $migrationFile);
                $migration->down();

                $end_time = microtime(true);
                $execution_time = number_format(($end_time - $start_time) * 1000, 2);
                $nameFile = basename(pathinfo($migrationFile, PATHINFO_FILENAME));
                echo "$nameFile " .  str_repeat('.', 50 - strlen($nameFile))   . "\t$execution_time ms \e[32mDONE\e[0m\n";
            } catch (\Exception $e) {
                echo "\e[31mError: {$e->getMessage()}\e[0m\n";
            }
        }
    }



    private static function saveFile(string $path, string $content)
    {
        $dir = dirname($path);
        if (!is_dir($dir)) {
            mkdir($dir, 0777, true);
        }

        if (file_put_contents($path, $content) !== false) {
            echo "\e[32mArchivo generado: $path\e[0m\n";
            return;
        }
        throw new \Exception("Error al generar el archivo: $path");
    }

    private static function showSuccess(string $message)
    {
        echo "\e[32m$message\e[0m\n";
    }

    private static function showError(string $message)
    {
        echo "\e[31m$message\e[0m\n";
    }
}
