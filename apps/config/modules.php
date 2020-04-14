<?php

return array(
    'microblog' => [
        'namespace' => 'Dex\microblog',
        'webControllerNamespace' => 'Dex\microblog\Presentation\Web\Controller',
        'apiControllerNamespace' => '',
        'className' => 'Dex\microblog\Module',
        'path' => APP_PATH . '/modules/dashboard/Module.php',
        'defaultRouting' => true,
        'defaultController' => 'index',
        'defaultAction' => 'index'
    ],
);
