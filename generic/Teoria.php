<?php
namespace generic;

class Teoria {
    private $id;
    private $reliquia_id;
    private $autor;
    private $descricao;
    private $criado_em;
    private $user_id; // Adicione o user_id

    public function __construct($id = null) {
        $this->id = $id;
    }

    public function getId() {
        return $this->id;
    }
    public function setId($id) {
        $this->id = $id;
    }

    public function getReliquiaId() {
        return $this->reliquia_id;
    }
    public function setReliquiaId($reliquia_id) {
        $this->reliquia_id = $reliquia_id;
    }

    public function getAutor() {
        return $this->autor;
    }
    public function setAutor($autor) {
        $this->autor = $autor;
    }

    public function getDescricao() {
        return $this->descricao;
    }
    public function setDescricao($descricao) {
        $this->descricao = $descricao;
    }

    public function getCriadoEm() {
        return $this->criado_em;
    }
    public function setCriadoEm($criado_em) {
        $this->criado_em = $criado_em;
    }

    public function getUserId() {
        return $this->user_id;
    }
    public function setUserId($user_id) {
        $this->user_id = $user_id;
    }
}
?>