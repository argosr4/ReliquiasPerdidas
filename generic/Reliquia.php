<?php
namespace Generic;

class Reliquia {
    private $id;
    private $nome;
    private $epoca;
    private $localizacao_estimada;
    private $descricao;

    // Getters e Setters...
    public function getId() { return $this->id; }
    public function setId($id) { $this->id = $id; }
    public function getNome() { return $this->nome; }
    public function setNome($nome) { $this->nome = $nome; }
    public function getEpoca() { return $this->epoca; }
    public function setEpoca($epoca) { $this->epoca = $epoca; }
    public function getLocalizacaoEstimada() { return $this->localizacao_estimada; }
    public function setLocalizacaoEstimada($localizacao_estimada) { $this->localizacao_estimada = $localizacao_estimada; }
    public function getDescricao() { return $this->descricao; }
    public function setDescricao($descricao) { $this->descricao = $descricao; }
}