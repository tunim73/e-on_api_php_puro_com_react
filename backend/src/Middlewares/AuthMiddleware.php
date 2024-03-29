<?php

namespace App\Middlewares;

use App\Core\Request;
use App\Core\Response;
use DomainException;
use Exception;
use Firebase\JWT\JWT;
use Firebase\JWT\Key;
use Firebase\JWT\SignatureInvalidException;
use UnexpectedValueException;

class AuthMiddleware
{
    public static function verifyToken(): bool|array
    {
        try {
            $token  = $_SERVER['HTTP_AUTHORIZATION'];
            if(!$token)
                return false;

            $token = str_replace('Bearer ','', $token);

            if($token === "")
                return false;

            $decodedToken = JWT::decode($token, new Key(getenv('JWT_KEY'), getenv('JWT_ALG')));

            $_SERVER['IS_ADMIN'] = $decodedToken->type;
            $_SERVER['USER_ID'] = $decodedToken->id;

            return [
                'user' => $decodedToken
            ];
        } catch (Exception $exception){
            return false;
        }

    }
}