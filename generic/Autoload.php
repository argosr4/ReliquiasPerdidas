<?php
function autoloadClasses($className) {
    // Remove o namespace inicial se existir (ex: 'controllers\UserController' -> 'UserController')
    $lastNsPos = strrpos($className, '\\');
    if ($lastNsPos !== false) {
        $className = substr($className, $lastNsPos + 1);
    }

    $directories = [
        '', // Para classes sem namespace explícito (como as classes DAO no seu exemplo)
        'Config/', // Se você tivesse uma pasta Config
        'Models/', // Pode usar esta pasta para seus modelos se preferir
        'dao/',
        'service/',
        'controllers/',
        'generic/', // Para as classes no namespace generic
        'utils/' // Para classes utilitárias
    ];

    foreach ($directories as $directory) {
        // Assume que as classes sem namespace (como DAOs) estão na raiz do respectivo diretório
        // E classes com namespace (como as de 'generic') estão no diretório 'generic/'
        $file = __DIR__ . '/../' . $directory . $className . '.php'; // Caminho relativo da raiz

        // Para classes no namespace 'generic', o arquivo estará em 'generic/Classe.php'
        if ($directory === 'generic/') {
            $file = __DIR__ . '/' . $className . '.php';
        }
         // Para classes em controllers, dao, service, se não usam namespace explicitamente, o arquivo estará em 'controllers/Classe.php'
        if (in_array($directory, ['controllers/', 'dao/', 'service/'])) {
             $file = __DIR__ . '/../' . $directory . $className . '.php';
        }


        if (file_exists($file)) {
            require_once $file;
            return;
        }
    }
}

spl_autoload_register('autoloadClasses');
?>