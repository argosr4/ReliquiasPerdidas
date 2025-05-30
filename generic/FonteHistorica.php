<?php
namespace generic;

class FonteHistorica {
    private $id;
    private $reliquia_id;
    private $titulo;
    private $tipo_fonte;
    private $link;

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

    public function getTitulo() {
        return $this->titulo;
    }
    public function setTitulo($titulo) {
        $this->titulo = $titulo;
    }

    public function getTipoFonte() {
        return $this->tipo_fonte;
    }
    public function setTipoFonte($tipo_fonte) {
        $this->tipo_fonte = $tipo_fonte;
    }

    public function getLink() {
        return $this->link;
    }
    public function setLink($link) {
        $this->link = $link;
    }
}
?>