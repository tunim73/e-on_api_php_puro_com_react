<?php

namespace App\Controllers;

use App\Core\Request;
use App\Core\Response;
use App\Middlewares\AuthMiddleware;
use App\Models\User;
use Firebase\JWT\JWT;

class AuthController
{
    public function login(): void
    {
        $body = Request::getBody();
        $user = new User();
        $user->email = $body->email;

        $userFound = $user->findByEmail();

        if(!$userFound){
            Response::json(404, [
                'error' => true,
                'message' => 'user not found'
            ]);
        }

        if(!password_verify($body->password, $userFound->password)){
            Response::json(data:[
                'error' => true,
                'message' => 'incorrect password'
            ]);
        }

        $payload = get_object_vars($userFound);

        $jwt = JWT::encode($payload, getenv('JWT_KEY'), getenv('JWT_ALG'));

        Response::json(200,[
            "user"=>$userFound,
            "token" => $jwt
        ]);

    }

    public function verifyToken(): void
    {
        $res = AuthMiddleware::verifyToken();

        if(!$res)
            Response::json(401, $res);

        $user = new User();
        $user->id= $_SERVER['USER_ID'];

        $foundUser = $user->findById();

        Response::json(data: ['user'=>$foundUser]);

    }
}