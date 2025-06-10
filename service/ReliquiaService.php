<?php
namespace Services;

use Generic\Reliquia;
use ReliquiaDAO;
use Exception;

class ReliquiaService {
    private $reliquiaDAO;

    public function __construct($db) {
        $this->reliquiaDAO = new ReliquiaDAO($db);
    }

    public function criarReliquia($dados) {
        try {
            if (empty($dados['nome'])) throw new Exception("Nome da relíquia é obrigatório.");
            if (empty($dados['epoca'])) throw new Exception("Época da relíquia é obrigatória.");
            if (empty($dados['localizacao_estimada'])) throw new Exception("Localização estimada da relíquia é obrigatória.");

            $reliquia = new Reliquia();
            $reliquia->setNome($dados['nome']);
            $reliquia->setEpoca($dados['epoca']);
            $reliquia->setLocalizacaoEstimada($dados['localizacao_estimada']);
            $reliquia->setDescricao($dados['descricao'] ?? null);

            if ($this->reliquiaDAO->create($reliquia)) {
                return ["success" => true, "message" => "Relíquia criada com sucesso"];
            } else {
                throw new Exception("Falha ao criar relíquia.");
            }
        } catch (Exception $e) {
            throw $e;
        }
    }
    
    public function obterTodasReliquias() {
        try {
            return $this->reliquiaDAO->readAll();
        } catch (Exception $e) {
            throw $e;
        }
    }

    public function obterReliquia($id) {
        try {
            if (empty($id) || !is_numeric($id)) throw new Exception("ID da relíquia inválido.");
            $reliquia = $this->reliquiaDAO->readOne($id);
            if (!$reliquia) throw new Exception("Relíquia não encontrada.");
            return $reliquia;
        } catch (Exception $e) {
            throw $e;
        }
    }

    public function atualizarReliquia($id, $dados) {
        try {
            if (empty($id) || !is_numeric($id)) throw new Exception("ID da relíquia inválido para atualização.");
            if (empty($dados['nome'])) throw new Exception("Nome da relíquia é obrigatório.");
            if (empty($dados['epoca'])) throw new Exception("Época da relíquia é obrigatória.");
            if (empty($dados['localizacao_estimada'])) throw new Exception("Localização estimada é obrigatória.");

            $reliquia = new Reliquia();
            $reliquia->setId($id);
            $reliquia->setNome($dados['nome']);
            $reliquia->setEpoca($dados['epoca']);
            $reliquia->setLocalizacaoEstimada($dados['localizacao_estimada']);
            $reliquia->setDescricao($dados['descricao'] ?? null);

            if ($this->reliquiaDAO->update($reliquia)) {
                return ["success" => true, "message" => "Relíquia atualizada com sucesso."];
            } else {
                throw new Exception("Falha ao atualizar a relíquia.");
            }
        } catch (Exception $e) {
            throw $e;
        }
    }

    public function deletarReliquia($id) {
        try {
            if (empty($id) || !is_numeric($id)) throw new Exception("ID da relíquia inválido para exclusão.");
            
            if ($this->reliquiaDAO->delete($id)) {
                return ["success" => true, "message" => "Relíquia deletada com sucesso."];
            } else {
                throw new Exception("Falha ao deletar a relíquia.");
            }
        } catch (Exception $e) {
            throw $e;
        }
    }

    public function buscarPorNome($nome) {
        try {
            if (empty($nome)) throw new Exception("O termo de busca para o nome é obrigatório.");
            return $this->reliquiaDAO->searchByName($nome);
        } catch (Exception $e) {
            throw $e;
        }
    }

    public function buscarPorEpoca($epoca) {
        try {
            if (empty($epoca)) throw new Exception("O termo de busca para a época é obrigatório.");
            return $this->reliquiaDAO->searchByEpoca($epoca);
        } catch (Exception $e) {
            throw $e;
        }
    }
}