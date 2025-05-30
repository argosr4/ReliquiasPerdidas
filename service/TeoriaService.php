<?php
namespace service;

use generic\Teoria; // Importa a classe do modelo
use TeoriaDAO; // Importa a classe DAO (sem namespace explícito)
use Exception; // Importa a classe Exception

class TeoriaService {
    private $teoriaDAO;

    public function __construct($db) {
        $this->teoriaDAO = new TeoriaDAO($db);
    }

    public function criarTeoria($dados, $user_id) {
        if (empty($dados['reliquia_id']) || !is_numeric($dados['reliquia_id'])) {
            throw new Exception("ID da relíquia é obrigatório e deve ser numérico.");
        }
        if (empty($dados['descricao'])) {
            throw new Exception("Descrição da teoria é obrigatória.");
        }
        if (empty($user_id) || !is_numeric($user_id)) { // Garantir que user_id seja válido
            throw new Exception("ID do usuário é obrigatório e deve ser numérico para criar teoria.");
        }

        $teoria = new Teoria(null);
        $teoria->setReliquiaId($dados['reliquia_id']);
        // O autor pode ser o nome do usuário logado, se você quiser. Por enquanto, usa o campo "autor" dos dados.
        $teoria->setAutor($dados['autor'] ?? 'Anônimo'); // Ou busque o nome do user_id
        $teoria->setDescricao($dados['descricao']);
        $teoria->setUserId($user_id); // Define o ID do usuário que criou a teoria

        if ($this->teoriaDAO->create($teoria)) {
            return ["success" => true, "message" => "Teoria criada com sucesso"];
        } else {
            throw new Exception("Erro ao criar teoria.");
        }
    }

    public function obterTeoriasPorReliquia($reliquia_id) {
        if (empty($reliquia_id) || !is_numeric($reliquia_id)) {
            throw new Exception("ID da relíquia inválido para busca de teorias.");
        }
        return $this->teoriaDAO->readByReliquia($reliquia_id);
    }

    public function obterTeoria($id) {
        if (empty($id) || !is_numeric($id)) {
            throw new Exception("ID da teoria inválido.");
        }
        $teoria = $this->teoriaDAO->readOne($id);
        if (!$teoria) {
            throw new Exception("Teoria não encontrada.");
        }
        return $teoria;
    }

    public function atualizarTeoria($id, $dados) {
        if (empty($id) || !is_numeric($id)) {
            throw new Exception("ID da teoria inválido para atualização.");
        }
        if (empty($dados['reliquia_id']) || !is_numeric($dados['reliquia_id'])) {
            throw new Exception("ID da relíquia é obrigatório e deve ser numérico.");
        }
        if (empty($dados['descricao'])) {
            throw new Exception("Descrição da teoria é obrigatória.");
        }
        // Autor também pode ser atualizado se a lógica permitir
        $autor = $dados['autor'] ?? null;
        if (empty($autor)) {
            throw new Exception("Autor da teoria é obrigatório.");
        }


        $teoria = new Teoria($id); // Passa o ID para o construtor
        $teoria->setReliquiaId($dados['reliquia_id']);
        $teoria->setAutor($autor);
        $teoria->setDescricao($dados['descricao']);

        if ($this->teoriaDAO->update($teoria)) {
            return ["success" => true, "message" => "Teoria atualizada com sucesso"];
        } else {
            throw new Exception("Erro ao atualizar teoria.");
        }
    }

    public function deletarTeoria($id) {
        if (empty($id) || !is_numeric($id)) {
            throw new Exception("ID da teoria inválido para exclusão.");
        }
        if ($this->teoriaDAO->delete($id)) {
            return ["success" => true, "message" => "Teoria deletada com sucesso"];
        } else {
            throw new Exception("Erro ao deletar teoria.");
        }
    }
}
?>