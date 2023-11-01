<?php

use easyphp\core\libs\EasyEnvLoader;

date_default_timezone_set('America/Guayaquil');

$protocol = isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on' ? 'https' : 'http';
$host = $_SERVER['HTTP_HOST'];
$script_name = isset($_SERVER['ORIG_SCRIPT_NAME']) ? $_SERVER['ORIG_SCRIPT_NAME'] : $_SERVER['SCRIPT_NAME'];

$base_url = $protocol . '://' . $host . dirname($script_name);
$base_url = str_replace('\\', '/', $base_url);

define('BASE_URL', $base_url);

$evn = new EasyEnvLoader('.env');
$evn->load();
