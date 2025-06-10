<?php
namespace Services;

use Generic\User;
use UserDAO;
use Exception;
use Firebase\JWT\JWT;

class UserService {
    private $userDAO;

    public function __construct($db) {
        $this->userDAO = new UserDAO($db);
    }

    public function register($dados) {
        try {
            if (empty($dados['nome'])) {
                throw new Exception("Nome é obrigatório.");
            }
            if (empty($dados['email']) || !filter_var($dados['email'], FILTER_VALIDATE_EMAIL)) {
                throw new Exception("Email é obrigatório e deve ser válido.");
            }
            if (empty($dados['senha']) || strlen($dados['senha']) < 6) {
                throw new Exception("A senha deve ter no mínimo 6 caracteres.");
            }

            $user = new User();
            $user->setNome($dados['nome']);
            $user->setEmail($dados['email']);
            $user->setSenha(password_hash($dados['senha'], PASSWORD_DEFAULT));

            $this->userDAO->create($user);
            return ["success" => true, "message" => "Usuário registrado com sucesso."];
        } catch (Exception $e) {
            // Repassa a exceção (ex: e-mail duplicado vindo do DAO) para o Controller.
            throw $e;
        }
    }

    /**
     * Valida as credenciais e gera um token JWT em caso de sucesso.
     */
    public function login($email, $senha) {
        try {
            if (empty($email) || empty($senha)) {
                throw new Exception("Email e senha são obrigatórios.");
            }

            $userData = $this->userDAO->findByEmail($email);
            if (!$userData || !password_verify($senha, $userData['senha'])) {
                throw new Exception("Email ou senha incorretos.");
            }

            // ALTERAÇÃO: Geração do Token JWT conforme o requisito 
            $issue_time = time();
            $expiration_time = $issue_time + JWT_EXPIRATION_TIME; // Constante do config.php

            $payload = [
                'iss' => JWT_ISSUER,
                'aud' => JWT_AUDIENCE,
                'iat' => $issue_time,
                'exp' => $expiration_time,
                'data' => [
                    'id' => $userData['id'],
                    'nome' => $userData['nome'],
                    'email' => $userData['email']
                ]
            ];

            $jwt = JWT::encode($payload, JWT_KEY, 'HS256');
            return ['token' => $jwt];
        } catch (Exception $e) {
            throw $e;
        }
    }
}