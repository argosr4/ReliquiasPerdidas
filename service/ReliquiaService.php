<?php
namespace service;

use generic\Reliquia; // Importa a classe do modelo
use ReliquiaDAO; // Importa a classe DAO (sem namespace explícito)
use Exception; // Importa a classe Exception

class ReliquiaService {
    private $reliquiaDAO;

    public function __construct($db) {
        $this->reliquiaDAO = new ReliquiaDAO($db);
    }

    public function criarReliquia($dados) {
        if (empty($dados['nome'])) {
            throw new Exception("Nome da relíquia é obrigatório.");
        }
        if (empty($dados['epoca'])) {
            throw new Exception("Época da relíquia é obrigatória.");
        }
        if (empty($dados['localizacao_estimada'])) {
            throw new Exception("Localização estimada da relíquia é obrigatória.");
        }

        $reliquia = new Reliquia(null);
        $reliquia->setNome($dados['nome']);
        $reliquia->setEpoca($dados['epoca']);
        $reliquia->setLocalizacaoEstimada($dados['localizacao_estimada']);
        $reliquia->setDescricao($dados['descricao'] ?? null); // Pode ser nulo

        if ($this->reliquiaDAO->create($reliquia)) {
            return ["success" => true, "message" => "Relíquia criada com sucesso"];
        } else {
            throw new Exception("Erro ao criar relíquia.");
        }
    }

    public function obterTodasReliquias() {
        return $this->reliquiaDAO->readAll();
    }

    public function obterReliquia($id) {
        if (empty($id) || !is_numeric($id)) {
            throw new Exception("ID da relíquia inválido.");
        }
        $reliquia = $this->reliquiaDAO->readOne($id);
        if (!$reliquia) {
            throw new Exception("Relíquia não encontrada.");
        }
        return $reliquia;
    }

    public function atualizarReliquia($id, $dados) {
        if (empty($id) || !is_numeric($id)) {
            throw new Exception("ID da relíquia inválido para atualização.");
        }
        if (empty($dados['nome'])) {
            throw new Exception("Nome da relíquia é obrigatório.");
        }
        if (empty($dados['epoca'])) {
            throw new Exception("Época da relíquia é obrigatória.");
        }
        if (empty($dados['localizacao_estimada'])) {
            throw new Exception("Localização estimada da relíquia é obrigatória.");
        }

        $reliquia = new Reliquia($id); // Passa o ID para o construtor
        $reliquia->setNome($dados['nome']);
        $reliquia->setEpoca($dados['epoca']);
        $reliquia->setLocalizacaoEstimada($dados['localizacao_estimada']);
        $reliquia->setDescricao($dados['descricao'] ?? null);

        if ($this->reliquiaDAO->update($reliquia)) {
            return ["success" => true, "message" => "Relíquia atualizada com sucesso"];
        } else {
            throw new Exception("Erro ao atualizar relíquia.");
        }
    }

    public function deletarReliquia($id) {
        if (empty($id) || !is_numeric($id)) {
            throw new Exception("ID da relíquia inválido para exclusão.");
        }
        if ($this->reliquiaDAO->delete($id)) {
            return ["success" => true, "message" => "Relíquia deletada com sucesso"];
        } else {
            throw new Exception("Erro ao deletar relíquia.");
        }
    }

    public function buscarPorNome($nome) {
        if (empty($nome)) {
            throw new Exception("Nome é obrigatório para busca.");
        }
        return $this->reliquiaDAO->searchByName($nome);
    }

    public function buscarPorEpoca($epoca) {
        if (empty($epoca)) {
            throw new Exception("Época é obrigatória para busca.");
        }
        return $this->reliquiaDAO->searchByEpoca($epoca);
    }
}
?>