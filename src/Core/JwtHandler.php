<?php

namespace Livramatheus\PlanetgameBack\Core;

class JwtHandler {

    private static function base64url_encode($data) {
        return str_replace(['+', '/', '='], ['-', '_', ''], base64_encode($data));
    }

    public static function createToken(array $payload) {
        $header = [
            'typ' => 'JWT',
            'alg' => 'HS256'
        ];

        $header  = json_encode($header);
        $payload = json_encode($payload);

        $header  = self::base64url_encode($header);
        $payload = self::base64url_encode($payload);

        $signature = hash_hmac('sha256', $header . "." . $payload, getenv('JWT_TOKEN_KEY'), true);
        $signature = self::base64url_encode($signature);

        return $header . '.' . $payload . '.' . $signature;
    }

    public static function checkToken() {
        if (empty(apache_request_headers()['Authorization']) ||!isset(apache_request_headers()['Authorization'])) {
            return false;
        }

        $authorization = apache_request_headers()['Authorization'];

        if (!empty($authorization) && isset($authorization)) {
            $bearer = explode(' ', $authorization);

            if ($bearer[0] != 'Bearer' || count($bearer) != 2) {
                return false;
            }

            $token = explode('.', $bearer[1]);
            
            if (count($token) != 3) {
                return false;
            }

            $header    = $token[0];
            $payload   = $token[1];
            $signature = $token[2];
    
            $validation = hash_hmac('sha256', $header . "." . $payload, getenv('JWT_TOKEN_KEY'), true);
            $validation = self::base64url_encode($validation);
    
            return $signature == $validation;
        }

        return false;
    }

}