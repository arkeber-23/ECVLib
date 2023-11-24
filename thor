#!/usr/bin/env php
<?php

require_once __DIR__ . '/vendor/autoload.php';

use Kernel\Cli\Commands;


$comand = new Commands($argv);
$exitCode = $comand->run();
exit($exitCode);

