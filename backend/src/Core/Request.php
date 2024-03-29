<?php

namespace App\Core;

class Request
{

    public static function getBody()
    {
        $body = file_get_contents('php://input');
        return json_decode($body);
    }

    public static function getRouteParams(): array
    {
        $route = $_SERVER["REQUEST_URI"] ?? '/';
        $pattern = "/\/(\d+)\/?/";
        preg_match_all($pattern, $route, $param);
        return $param[1];
    }
    
}