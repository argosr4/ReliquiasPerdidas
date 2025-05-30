📜 API RESTful: Inventário de Relíquias Históricas Perdidas

Funcionalidades Principais
Nossa API oferece um conjunto robusto de funcionalidades para você gerenciar o inventário de relíquias perdidas:

Relíquias: Gerenciamento completo (CRUD - Criar, Ler, Atualizar, Deletar) de informações sobre relíquias históricas.
Fontes Históricas: Gerenciamento (CRUD) de referências e fontes de informação associadas a cada relíquia.
Teorias: Gerenciamento (CRUD) de teorias propostas por usuários sobre a localização ou natureza das relíquias.
Autenticação de Usuários: Sistema de registro e login para proteger a criação e modificação de teorias.

🛠️ Tecnologias e Arquitetura
A API foi construída com as seguintes tecnologias e diretrizes de design:

PHP 8.2+: A linguagem de programação principal.
MySQL: O sistema de gerenciamento de banco de dados.
Apache (com mod_rewrite): Servidor web responsável pelo roteamento das requisições.
Ferramentas para Teste: Postman ou Insomnia são recomendadas para testar e interagir com a API.
Padrão MVC: Separação clara de responsabilidades:
Controller: Recebe requisições e retorna respostas.
Service: Contém as regras de negócio da aplicação.
DAO (Data Access Object): Lida com o acesso direto ao banco de dados.
Boas Práticas:
Autoloading: Carregamento automático de classes.
Prepared Statements: Essencial para proteção contra SQL Injection.
Tratamento de Exceções: Para um controle de erros robusto.


📁 Estrutura do Projeto

A organização do projeto segue uma estrutura lógica para facilitar a manutenção e o entendimento:
├── controllers/          # Lógica para receber requisições e retornar respostas (Controller)
├── dao/                  # Camada de acesso direto ao banco de dados (Data Access Object)
├── generic/              # Classes genéricas e utilitárias (Autoload, Singleton, Router, etc.)
├── service/              # Camada intermediária com as regras de negócio da aplicação (Service)
├── sql/                  # Scripts SQL para a criação do banco de dados e tabelas
├── views/                # (Opcional) Arquivos de view PHP, caso haja um frontend básico
├── .htaccess             # Configuração do Apache para reescrita de URLs
├── composer.json         # (Opcional) Configuração do Composer para gerenciamento de dependências
├── index.php             # Ponto de entrada único da aplicação
└── README.md             # Documentação do projeto (este arquivo)
⚙️ Configuração e Execução
Siga os passos abaixo para configurar e rodar a API em seu ambiente local.

1. Pré-requisitos
Certifique-se de ter instalado:
XAMPP (ou Apache, PHP e MySQL configurados separadamente).
PHP 8.2 ou superior.
2. Configuração do Servidor Web (Apache)
Copie o Projeto: Mova toda a pasta da API (api/) para o diretório htdocs do seu XAMPP.
Exemplo: C:\xampp\htdocs\api
Habilite mod_rewrite:
Abra o arquivo httpd.conf do Apache (geralmente em C:\xampp\apache\conf\httpd.conf).
Procure por LoadModule rewrite_module modules/mod_rewrite.so e descomente a linha (remova o # do início) se ela estiver comentada.
Configure AllowOverride:
No mesmo arquivo httpd.conf, localize a diretiva <Directory "C:/xampp/htdocs"> (ou o diretório correspondente à raiz do seu servidor web).
Certifique-se de que AllowOverride All esteja configurado dentro deste bloco.
Snippet de código

<Directory "C:/xampp/htdocs">
    AllowOverride All
    Require all granted
</Directory>
Verifique o .htaccess:
Confirme que o arquivo .htaccess na raiz da sua pasta api/ está configurado corretamente.
IMPORTANTE: Ajuste RewriteBase /api/ para o caminho correto se o seu projeto não estiver na pasta api dentro de htdocs (por exemplo, se estiver em htdocs/meu-projeto-reliquias/, mude para RewriteBase /meu-projeto-reliquias/).
Snippet de código

RewriteEngine On
RewriteBase /api/

RewriteCond %{REQUEST_FILENAME} !-f
RewriteCond %{REQUEST_FILENAME} !-d
RewriteRule ^(.*)$ index.php [L,QSA]
Reinicie o Apache.
3. Configuração do Banco de Dados
Edite generic/MysqlSingleton.php:
Abra este arquivo e insira suas credenciais de conexão com o MySQL:
PHP

private $dsn = 'mysql:host=localhost;dbname=reliquias_perdidas;charset=utf8';
private $usuario = 'root'; // Seu usuário MySQL
private $senha = '';     // Sua senha MySQL
Crie o Banco de Dados e Tabelas:
Acesse o phpMyAdmin (http://localhost/phpmyadmin) ou seu cliente MySQL preferido.
Execute o script SQL localizado em sql/create_tables.sql. Ele criará o banco de dados chamado reliquias_perdidas e todas as tabelas necessárias para a API.


4. Execução da API
Após seguir e completar todos os passos de configuração acima, sua API estará acessível em:

http://localhost/api/

📚 Endpoints da API
A API segue uma estrutura RESTful e utiliza o formato JSON para todas as requisições e respostas.

Estrutura da Resposta
Sucesso (Status HTTP 2xx):
JSON

{
    "success": true,
    "message": "Mensagem de sucesso",
    "data": { /* dados do recurso (opcional) */ }
}
Erro (Status HTTP 4xx / 5xx):
JSON

{
    "error": "Mensagem de erro"
}
1. Autenticação (Usuários)
Gerencie o acesso dos usuários à API.

a. Registrar Usuário
URL: /api/register
Método: POST
Headers:
Content-Type: application/json
Body (JSON):
JSON

{
    "nome": "Nome do Usuário",
    "email": "usuario@example.com",
    "senha": "senha_segura"
}
Respostas:
Sucesso (201 Created): {"success": true, "message": "Usuário registrado com sucesso"}
Erro (400 Bad Request): Ex: {"error": "Email já cadastrado"} ou erros de validação.
b. Login de Usuário
URL: /api/login
Método: POST
Headers:
Content-Type: application/json
Body (JSON):
JSON

{
    "email": "usuario@example.com",
    "senha": "senha_segura"
}
Respostas:
Sucesso (200 OK):
JSON

{
    "message": "Login realizado com sucesso",
    "user": {
        "id": 1,
        "nome": "Nome do Usuário",
        "email": "usuario@example.com"
    }
}
Erro (401 Unauthorized): Ex: {"error": "Email ou senha incorretos."}
c. Logout de Usuário
URL: /api/logout
Método: GET
Respostas:
Sucesso (200 OK): {"message": "Logout realizado com sucesso"}
d. Checar Autenticação
URL: /api/check-auth
Método: GET
Respostas:
Sucesso (200 OK):
JSON

{
    "authenticated": true,
    "user": { /* dados do usuário logado */ }
}
Erro (401 Unauthorized):
JSON

{
    "authenticated": false,
    "error": "Usuário não autenticado"
}
2. Relíquias Históricas
Endpoints para gerenciar as relíquias.

a. Listar Todas as Relíquias
URL: /api/reliquias
Método: GET
Parâmetros (Query String - Opcional):
?buscar=nome&nome={termo_busca}: Busca relíquias por nome.
?buscar=epoca&epoca={termo_busca}: Busca relíquias por época.
Resposta (Sucesso - 200 OK):
JSON

[
    {
        "id": 1,
        "nome": "Arca da Aliança",
        "epoca": "Século XIII a.C.",
        "localizacao_estimada": "Etiópia ou Israel",
        "descricao": "Artefato sagrado..."
    },
    // ... outras relíquias
]
b. Obter uma Relíquia por ID
URL: /api/reliquias/{id}
Método: GET
Respostas:
Sucesso (200 OK): (Retorna o objeto único da relíquia)
Erro (400 Bad Request): Ex: {"error": "ID inválido"} ou {"error": "Relíquia não encontrada"}
c. Criar uma Nova Relíquia
URL: /api/reliquias
Método: POST
Headers:
Content-Type: application/json
Body (JSON):
JSON

{
    "nome": "Nome da Relíquia",
    "epoca": "Período histórico",
    "localizacao_estimada": "Local estimado",
    "descricao": "Descrição detalhada (opcional)"
}
Respostas:
Sucesso (201 Created): {"success": true, "message": "Relíquia criada com sucesso"}
Erro (400 Bad Request): Ex: {"error": "Nome, época e localização estimada são obrigatórios"}
d. Atualizar uma Relíquia Existente
URL: /api/reliquias/{id}
Método: PUT
Headers:
Content-Type: application/json
Body (JSON): Inclua os campos da relíquia que deseja atualizar.
Respostas:
Sucesso (200 OK): {"success": true, "message": "Relíquia atualizada com sucesso"}
Erro (400 Bad Request): Erros de validação ou ID inválido.
e. Deletar uma Relíquia
URL: /api/reliquias/{id}
Método: DELETE
Respostas:
Sucesso (200 OK): {"success": true, "message": "Relíquia deletada com sucesso"}
Erro (400 Bad Request): ID inválido ou erro na exclusão.
3. Fontes Históricas
Endpoints para gerenciar as fontes de informação das relíquias.

a. Listar Fontes por Relíquia
URL: /api/fontes?reliquia_id={id}
Método: GET
Resposta (Sucesso - 200 OK):
JSON

[
    {
        "id": 1,
        "reliquia_id": 1,
        "titulo": "Artigo sobre a Arca",
        "tipo_fonte": "Artigo",
        "link": "http://exemplo.com/artigo-arca"
    },
    // ... outras fontes
]
b. Obter uma Fonte por ID
URL: /api/fontes/{id}
Método: GET
Respostas:
Sucesso (200 OK): (Retorna o objeto único da fonte)
Erro (400 Bad Request): ID inválido ou fonte não encontrada.
c. Criar uma Nova Fonte Histórica
URL: /api/fontes
Método: POST
Headers:
Content-Type: application/json
Body (JSON):
JSON

{
    "reliquia_id": 1,
    "titulo": "Título da Fonte",
    "tipo_fonte": "Livro/Artigo/Website",
    "link": "http://link.da.fonte.com"
}
Respostas:
Sucesso (201 Created): {"success": true, "message": "Fonte histórica criada com sucesso"}
Erro (400 Bad Request): Erros de validação.
d. Atualizar uma Fonte Histórica
URL: /api/fontes/{id}
Método: PUT
Headers:
Content-Type: application/json
Body (JSON): Inclua os campos da fonte que deseja atualizar.
Respostas:
Sucesso (200 OK): {"message": "Fonte histórica atualizada com sucesso"}
Erro (400 Bad Request): Erros de validação.
e. Deletar uma Fonte Histórica
URL: /api/fontes/{id}
Método: DELETE
Respostas:
Sucesso (200 OK): {"message": "Fonte histórica deletada com sucesso"}
Erro (400 Bad Request): ID inválido.
4. Teorias
Endpoints para gerenciar as teorias dos usuários sobre as relíquias.

a. Listar Teorias por Relíquia
URL: /api/teorias?reliquia_id={id}
Método: GET
Resposta (Sucesso - 200 OK):
JSON

[
    {
        "id": 1,
        "reliquia_id": 1,
        "autor": "Usuário Teste",
        "descricao": "Teoria sobre a localização da relíquia X.",
        "user_id": 1,
        "criado_em": "2025-05-30 10:00:00"
    },
    // ... outras teorias
]
b. Obter uma Teoria por ID
URL: /api/teorias/{id}
Método: GET
Respostas:
Sucesso (200 OK): (Retorna o objeto único da teoria)
Erro (400 Bad Request): ID inválido ou teoria não encontrada.
c. Criar uma Nova Teoria
URL: /api/teorias
Método: POST
Headers:
Content-Type: application/json
Requer Autenticação: O usuário deve estar logado (sessão ativa).
Body (JSON):
JSON

{
    "reliquia_id": 1,
    "autor": "Nome do Autor da Teoria", // Opcional, pode ser inferido do usuário logado
    "descricao": "Minha teoria detalhada sobre a relíquia."
}
Respostas:
Sucesso (201 Created): {"success": true, "message": "Teoria criada com sucesso"}
Erro (401 Unauthorized): Se o usuário não estiver logado.
Erro (400 Bad Request): Erros de validação.
d. Atualizar uma Teoria
URL: /api/teorias/{id}
Método: PUT
Headers:
Content-Type: application/json
Body (JSON): Inclua os campos da teoria que deseja atualizar.
Respostas:
Sucesso (200 OK): {"message": "Teoria atualizada com sucesso"}
Erro (400 Bad Request): Erros de validação.
e. Deletar uma Teoria
URL: /api/teorias/{id}
Método: DELETE
Respostas:
Sucesso (200 OK): {"message": "Teoria deletada com sucesso"}
Erro (400 Bad Request): ID inválido.
5. Documentação da API
URL: /api/
Método: GET
Respostas:
Sucesso (200 OK): Retorna um JSON com a estrutura de todos os endpoints e exemplos, conforme definido em generic/Endpoint.php.