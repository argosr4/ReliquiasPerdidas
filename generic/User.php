<?php
namespace generic;

class User {
    private $id;
    private $nome;
    private $email;
    private $senha;
    private $criado_em;

    public function __construct($id = null) {
        $this->id = $id;
    }

    public function getId() {
        return $this->id;
    }
    public function setId($id) {
        $this->id = $id;
    }

    public function getNome() {
        return $this->nome;
    }
    public function setNome($nome) {
        $this->nome = $nome;
    }

    public function getEmail() {
        return $this->email;
    }
    public function setEmail($email) {
        $this->email = $email;
    }

    public function getSenha() {
        return $this->senha;
    }
    public function setSenha($senha) {
        $this->senha = $senha;
    }

    public function getCriadoEm() {
        return $this->criado_em;
    }
    public function setCriadoEm($criado_em) {
        $this->criado_em = $criado_em;
    }
}
?>