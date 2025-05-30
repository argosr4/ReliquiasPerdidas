<?php
namespace service;

use generic\User; // Importa a classe do modelo
use UserDAO; // Importa a classe DAO (sem namespace explícito)
use Exception; // Importa a classe Exception

class UserService {
    private $userDAO;

    public function __construct($db) {
        $this->userDAO = new UserDAO($db);
    }

    public function register($dados) {
        if (empty($dados['nome'])) {
            throw new Exception("Nome é obrigatório.");
        }
        if (empty($dados['email']) || !filter_var($dados['email'], FILTER_VALIDATE_EMAIL)) {
            throw new Exception("Email é obrigatório e deve ser válido.");
        }
        if (empty($dados['senha'])) {
            throw new Exception("Senha é obrigatória.");
        }
        if (strlen($dados['senha']) < 6) { // Exemplo de validação de senha
            throw new Exception("A senha deve ter no mínimo 6 caracteres.");
        }

        // Verifica se o email já existe
        $existingUser = $this->userDAO->findByEmail($dados['email']);
        if ($existingUser) {
            throw new Exception("Email já cadastrado.");
        }

        $user = new User(null);
        $user->setNome($dados['nome']);
        $user->setEmail($dados['email']);
        // Hash da senha antes de salvar
        $user->setSenha(password_hash($dados['senha'], PASSWORD_DEFAULT));

        if ($this->userDAO->create($user)) {
            return ["success" => true, "message" => "Usuário registrado com sucesso"];
        } else {
            throw new Exception("Erro ao registrar usuário.");
        }
    }

    public function login($email, $senha) {
        if (empty($email) || !filter_var($email, FILTER_VALIDATE_EMAIL)) {
            throw new Exception("Email é obrigatório e deve ser válido.");
        }
        if (empty($senha)) {
            throw new Exception("Senha é obrigatória.");
        }

        $userData = $this->userDAO->findByEmail($email);
        if (!$userData) {
            throw new Exception("Email ou senha incorretos."); // Mensagem genérica por segurança
        }

        if (!password_verify($senha, $userData['senha'])) {
            throw new Exception("Email ou senha incorretos."); // Mensagem genérica por segurança
        }

        // Retorna dados do usuário sem a senha
        unset($userData['senha']);
        return $userData;
    }
}
?>