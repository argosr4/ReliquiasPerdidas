<?php
namespace Services;

use Generic\Teoria;
use TeoriaDAO;
use Exception;

class TeoriaService {
    private $teoriaDAO;

    public function __construct($db) {
        $this->teoriaDAO = new TeoriaDAO($db);
    }

    public function criarTeoria($dados, $user_id) {
        // ALTERAÇÃO: Lógica encapsulada e uso do user_id vindo do JWT 
        try {
            if (empty($dados['reliquia_id']) || !is_numeric($dados['reliquia_id'])) {
                throw new Exception("ID da relíquia é obrigatório e deve ser numérico.");
            }
            if (empty($dados['descricao'])) {
                throw new Exception("Descrição da teoria é obrigatória.");
            }
            if (empty($user_id) || !is_numeric($user_id)) {
                throw new Exception("ID de usuário inválido, falha na autenticação.");
            }

            $teoria = new Teoria();
            $teoria->setReliquiaId($dados['reliquia_id']);
            $teoria->setAutor($dados['autor'] ?? 'Anônimo');
            $teoria->setDescricao($dados['descricao']);
            $teoria->setUserId($user_id); // Associa a teoria ao usuário do token

            if ($this->teoriaDAO->create($teoria)) {
                return ["success" => true, "message" => "Teoria criada com sucesso"];
            } else {
                throw new Exception("Falha ao criar teoria.");
            }
        } catch (Exception $e) {
            throw $e;
        }
    }

    public function obterTeoriasPorReliquia($reliquia_id) {
        try {
            if (empty($reliquia_id) || !is_numeric($reliquia_id)) {
                throw new Exception("ID da relíquia inválido para busca de teorias.");
            }
            return $this->teoriaDAO->readByReliquia($reliquia_id);
        } catch (Exception $e) {
            throw $e;
        }
    }

    public function atualizarTeoria($id, $dados) {
        try {
            // Validações...
            if (empty($id) || !is_numeric($id)) throw new Exception("ID da teoria inválido.");
            if (empty($dados['descricao'])) throw new Exception("Descrição é obrigatória.");
            
            // Lógica para buscar a teoria e depois atualizar...
            $teoria = new Teoria();
            $teoria->setId($id);
            $teoria->setReliquiaId($dados['reliquia_id']);
            $teoria->setAutor($dados['autor']);
            $teoria->setDescricao($dados['descricao']);

            if ($this->teoriaDAO->update($teoria)) {
                 return ["success" => true, "message" => "Teoria atualizada com sucesso."];
            }
            throw new Exception("Falha ao atualizar teoria.");
        } catch (Exception $e) {
            throw $e;
        }
    }

    public function deletarTeoria($id) {
        try {
            if (empty($id) || !is_numeric($id)) {
                throw new Exception("ID da teoria inválido para exclusão.");
            }
            if ($this->teoriaDAO->delete($id)) {
                return ["success" => true, "message" => "Teoria deletada com sucesso"];
            } else {
                throw new Exception("Falha ao deletar teoria.");
            }
        } catch (Exception $e) {
            throw $e;
        }
    }
}