<?php

namespace Easyphp\Core\Logs;

class ErrorLog
{
    public static function notify($message)
    {

        $logFile =  'logs/error.log';
        error_log($message .= "\n", 3, $logFile);
    }
}
