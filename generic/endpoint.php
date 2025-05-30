<?php
// generic/Endpoint.php

namespace generic; // Adicione o namespace

class Endpoint {
    public static function getDocumentation() {
        return [
            'api_info' => [
                'name' => 'API Inventário de Relíquias Históricas Perdidas',
                'version' => '1.0.0',
                'description' => 'API para gerenciar inventário de relíquias históricas perdidas',
                'base_url' => 'http://localhost/api' // Ajuste conforme seu ambiente
            ],
            'endpoints' => [
                'reliquias' => [
                    'list_all' => [
                        'method' => 'GET',
                        'url' => '/api/reliquias',
                        'description' => 'Lista todas as relíquias cadastradas',
                        'response_example' => [
                            'data' => [
                                ['id' => 1, 'nome' => 'Arca da Aliança', 'epoca' => 'Século XIII a.C.', 'localizacao_estimada' => 'Etiópia ou Israel', 'descricao' => 'Artefato sagrado...']
                            ]
                        ]
                    ],
                    'get_one' => [
                        'method' => 'GET',
                        'url' => '/api/reliquias/{id}',
                        'description' => 'Obtém uma relíquia específica',
                        'parameters' => ['id' => 'integer']
                    ],
                    'search_by_name' => [
                        'method' => 'GET',
                        'url' => '/api/reliquias?buscar=nome&nome={nome}',
                        'description' => 'Busca relíquias por nome',
                        'parameters' => ['nome' => 'string']
                    ],
                    'search_by_era' => [
                        'method' => 'GET',
                        'url' => '/api/reliquias?buscar=epoca&epoca={epoca}',
                        'description' => 'Busca relíquias por época',
                        'parameters' => ['epoca' => 'string']
                    ],
                    'create' => [
                        'method' => 'POST',
                        'url' => '/api/reliquias',
                        'description' => 'Cria uma nova relíquia',
                        'body_example' => [
                            'nome' => 'Nome da Relíquia',
                            'epoca' => 'Período histórico',
                            'localizacao_estimada' => 'Local estimado',
                            'descricao' => 'Descrição detalhada'
                        ]
                    ],
                    'update' => [
                        'method' => 'PUT',
                        'url' => '/api/reliquias/{id}',
                        'description' => 'Atualiza uma relíquia existente',
                        'parameters' => ['id' => 'integer']
                    ],
                    'delete' => [
                        'method' => 'DELETE',
                        'url' => '/api/reliquias/{id}',
                        'description' => 'Remove uma relíquia',
                        'parameters' => ['id' => 'integer']
                    ]
                ],
                'fontes' => [
                    'list_by_relic' => [
                        'method' => 'GET',
                        'url' => '/api/fontes?reliquia_id={id}',
                        'description' => 'Lista fontes históricas de uma relíquia',
                        'parameters' => ['reliquia_id' => 'integer']
                    ],
                    'get_one' => [
                        'method' => 'GET',
                        'url' => '/api/fontes/{id}',
                        'description' => 'Obtém uma fonte específica'
                    ],
                    'create' => [
                        'method' => 'POST',
                        'url' => '/api/fontes',
                        'description' => 'Cria uma nova fonte histórica',
                        'body_example' => [
                            'reliquia_id' => 1,
                            'titulo' => 'Título da fonte',
                            'tipo_fonte' => 'Tipo da fonte',
                            'link' => 'URL da fonte'
                        ]
                    ],
                    'update' => [
                        'method' => 'PUT',
                        'url' => '/api/fontes/{id}',
                        'description' => 'Atualiza uma fonte histórica'
                    ],
                    'delete' => [
                        'method' => 'DELETE',
                        'url' => '/api/fontes/{id}',
                        'description' => 'Remove uma fonte histórica'
                    ]
                ],
                'teorias' => [
                    'list_by_relic' => [
                        'method' => 'GET',
                        'url' => '/api/teorias?reliquia_id={id}',
                        'description' => 'Lista teorias sobre uma relíquia'
                    ],
                    'get_one' => [
                        'method' => 'GET',
                        'url' => '/api/teorias/{id}',
                        'description' => 'Obtém uma teoria específica'
                    ],
                    'create' => [
                        'method' => 'POST',
                        'url' => '/api/teorias',
                        'description' => 'Cria uma nova teoria (requer autenticação)',
                        'body_example' => [
                            'reliquia_id' => 1,
                            'autor' => 'Nome do autor', // Pode ser preenchido automaticamente pelo usuário logado
                            'descricao' => 'Descrição da teoria'
                        ]
                    ],
                    'update' => [
                        'method' => 'PUT',
                        'url' => '/api/teorias/{id}',
                        'description' => 'Atualiza uma teoria'
                    ],
                    'delete' => [
                        'method' => 'DELETE',
                        'url' => '/api/teorias/{id}',
                        'description' => 'Remove uma teoria'
                    ]
                ],
                'auth' => [
                    'register' => [
                        'method' => 'POST',
                        'url' => '/api/register',
                        'description' => 'Registra um novo usuário',
                        'body_example' => ['nome' => 'Novo Usuário', 'email' => 'novo@email.com', 'senha' => 'senha123']
                    ],
                    'login' => [
                        'method' => 'POST',
                        'url' => '/api/login',
                        'description' => 'Autentica um usuário',
                        'body_example' => ['email' => 'seu@email.com', 'senha' => 'suasenha']
                    ],
                    'logout' => [
                        'method' => 'GET',
                        'url' => '/api/logout',
                        'description' => 'Desconecta o usuário'
                    ],
                    'check_auth' => [
                        'method' => 'GET',
                        'url' => '/api/check-auth',
                        'description' => 'Verifica o status de autenticação do usuário'
                    ]
                ]
            ]
        ];
    }
}
?>