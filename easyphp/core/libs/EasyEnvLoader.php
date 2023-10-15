<?php 
namespace easyphp\core\libs;

class EasyEnvLoader
{
    private $envFile;

    public function __construct($envFile)
    {
        $this->envFile = $envFile;
    }

    public function load()
    {
        if (!file_exists($this->envFile)) {
            echo "No ha sido posible encontrar el archivo .env";
            return;
        }

        $envContents = file_get_contents($this->envFile);
        $envLines = explode("\n", $envContents);
      
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
