<?php

namespace Kernel\config;

class CorsConfig
{
    public static function getConfig()
    {
        return [
            'origin' => ['*'],
            'allow_methods' => ['*'],
            'allow_headers' => ['*'],
            'expose_headers' => [],
            'max_age' => 0,
            'supports_credentials' => false,
        ];
    }
}
