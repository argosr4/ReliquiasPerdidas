<?php
namespace service;

use generic\FonteHistorica; // Importa a classe do modelo
use FonteHistoricaDAO; // Importa a classe DAO (sem namespace explícito)
use Exception; // Importa a classe Exception

class FonteHistoricaService {
    private $fonteDAO;

    public function __construct($db) {
        $this->fonteDAO = new FonteHistoricaDAO($db);
    }

    public function criarFonte($dados) {
        if (empty($dados['reliquia_id']) || !is_numeric($dados['reliquia_id'])) {
            throw new Exception("ID da relíquia é obrigatório e deve ser numérico.");
        }
        if (empty($dados['titulo'])) {
            throw new Exception("Título da fonte é obrigatório.");
        }

        $fonte = new FonteHistorica(null);
        $fonte->setReliquiaId($dados['reliquia_id']);
        $fonte->setTitulo($dados['titulo']);
        $fonte->setTipoFonte($dados['tipo_fonte'] ?? null); // Pode ser nulo
        $fonte->setLink($dados['link'] ?? null); // Pode ser nulo

        if ($this->fonteDAO->create($fonte)) {
            return ["success" => true, "message" => "Fonte histórica criada com sucesso"];
        } else {
            throw new Exception("Erro ao criar fonte histórica.");
        }
    }

    public function obterFontesPorReliquia($reliquia_id) {
        if (empty($reliquia_id) || !is_numeric($reliquia_id)) {
            throw new Exception("ID da relíquia inválido para busca.");
        }
        return $this->fonteDAO->readByReliquia($reliquia_id);
    }

    public function obterFonte($id) {
        if (empty($id) || !is_numeric($id)) {
            throw new Exception("ID da fonte inválido.");
        }
        $fonte = $this->fonteDAO->readOne($id);
        if (!$fonte) {
            throw new Exception("Fonte histórica não encontrada.");
        }
        return $fonte;
    }

    public function atualizarFonte($id, $dados) {
        if (empty($id) || !is_numeric($id)) {
            throw new Exception("ID da fonte inválido para atualização.");
        }
        if (empty($dados['reliquia_id']) || !is_numeric($dados['reliquia_id'])) {
            throw new Exception("ID da relíquia é obrigatório e deve ser numérico.");
        }
        if (empty($dados['titulo'])) {
            throw new Exception("Título da fonte é obrigatório.");
        }

        $fonte = new FonteHistorica($id); // Passa o ID para o construtor
        $fonte->setReliquiaId($dados['reliquia_id']);
        $fonte->setTitulo($dados['titulo']);
        $fonte->setTipoFonte($dados['tipo_fonte'] ?? null);
        $fonte->setLink($dados['link'] ?? null);

        if ($this->fonteDAO->update($fonte)) {
            return ["success" => true, "message" => "Fonte histórica atualizada com sucesso"];
        } else {
            throw new Exception("Erro ao atualizar fonte histórica.");
        }
    }

    public function deletarFonte($id) {
        if (empty($id) || !is_numeric($id)) {
            throw new Exception("ID da fonte inválido para exclusão.");
        }
        if ($this->fonteDAO->delete($id)) {
            return ["success" => true, "message" => "Fonte histórica deletada com sucesso"];
        } else {
            throw new Exception("Erro ao deletar fonte histórica.");
        }
    }
}
?>