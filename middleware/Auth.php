<?php
namespace Middleware;

use Firebase\JWT\JWT;
use Firebase\JWT\Key;
use Exception;

class Auth {
    public static function validateToken() {
        $headers = getallheaders();
        $authHeader = $headers['Authorization'] ?? null;

        if (!$authHeader) {
            throw new Exception("Acesso não autorizado");
        }
        
        if (preg_match('/Bearer\s(\S+)/', $authHeader, $matches)) {
            $token = $matches[1];
        } else {
            throw new Exception("Acesso não autorizado");
        }

        try {
            $decoded = JWT::decode($token, new Key(JWT_KEY, 'HS256'));
            return (array) $decoded->data;
        } catch (Exception $e) {
            throw new Exception("Acesso não autorizado");
        }
    }
}