#!/usr/bin/env php
<?php

require_once __DIR__ . '/autoload.php';

use easyphp\core\cli\Commands;


$comand = new Commands($argv);
$exit = $comand->execute();
exit($exit);

