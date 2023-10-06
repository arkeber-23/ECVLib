<?php

namespace app\helpers;

use app\libs\Response;

class Helpers
{
    public static function header($data = [])
    {
        Response::view('layout.header', $data);
    }
    public static function footer()
    {
        Response::view('layout.footer');
    }
}
