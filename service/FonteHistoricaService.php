<?php
namespace Services;

use Generic\FonteHistorica;
use FonteHistoricaDAO;
use Exception;

class FonteHistoricaService {
    private $fonteDAO;

    public function __construct($db) {
        $this->fonteDAO = new FonteHistoricaDAO($db);
    }

    public function criarFonte($dados) {
        try {
            if (empty($dados['reliquia_id']) || !is_numeric($dados['reliquia_id'])) throw new Exception("ID da relíquia é obrigatório.");
            if (empty($dados['titulo'])) throw new Exception("Título da fonte é obrigatório.");

            $fonte = new FonteHistorica();
            $fonte->setReliquiaId($dados['reliquia_id']);
            $fonte->setTitulo($dados['titulo']);
            $fonte->setTipoFonte($dados['tipo_fonte'] ?? null);
            $fonte->setLink($dados['link'] ?? null);

            if ($this->fonteDAO->create($fonte)) {
                return ["success" => true, "message" => "Fonte histórica criada com sucesso"];
            } else {
                throw new Exception("Falha ao criar fonte histórica.");
            }
        } catch (Exception $e) {
            throw $e;
        }
    }
    
    public function obterFontesPorReliquia($reliquia_id) {
        try {
            if (empty($reliquia_id) || !is_numeric($reliquia_id)) throw new Exception("ID da relíquia inválido.");
            return $this->fonteDAO->readByReliquia($reliquia_id);
        } catch (Exception $e) {
            throw $e;
        }
    }

    public function obterFonte($id) {
        try {
            if (empty($id) || !is_numeric($id)) throw new Exception("ID da fonte inválido.");
            $fonte = $this->fonteDAO->readOne($id);
            if (!$fonte) throw new Exception("Fonte histórica não encontrada.");
            return $fonte;
        } catch (Exception $e) {
            throw $e;
        }
    }

    public function atualizarFonte($id, $dados) {
        try {
            if (empty($id) || !is_numeric($id)) throw new Exception("ID da fonte inválido para atualização.");
            if (empty($dados['reliquia_id']) || !is_numeric($dados['reliquia_id'])) throw new Exception("ID da relíquia é obrigatório.");
            if (empty($dados['titulo'])) throw new Exception("Título da fonte é obrigatório.");

            $fonte = new FonteHistorica();
            $fonte->setId($id);
            $fonte->setReliquiaId($dados['reliquia_id']);
            $fonte->setTitulo($dados['titulo']);
            $fonte->setTipoFonte($dados['tipo_fonte'] ?? null);
            $fonte->setLink($dados['link'] ?? null);

            if ($this->fonteDAO->update($fonte)) {
                return ["success" => true, "message" => "Fonte histórica atualizada com sucesso."];
            } else {
                throw new Exception("Falha ao atualizar a fonte histórica.");
            }
        } catch (Exception $e) {
            throw $e;
        }
    }

    public function deletarFonte($id) {
        try {
            if (empty($id) || !is_numeric($id)) throw new Exception("ID da fonte inválido para exclusão.");

            if ($this->fonteDAO->delete($id)) {
                return ["success" => true, "message" => "Fonte histórica deletada com sucesso."];
            } else {
                throw new Exception("Falha ao deletar a fonte histórica.");
            }
        } catch (Exception $e) {
            throw $e;
        }
    }
}