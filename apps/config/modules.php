<?php

return array(
    'microblog' => [
        'namespace' => 'Dex\Microblog',
        'webControllerNamespace' => 'Dex\Microblog\Presentation\Web\Controller',
        'apiControllerNamespace' => '',
        'className' => 'Dex\Microblog\Module',
        'path' => APP_PATH . '/modules/microblog/Module.php',
        'defaultRouting' => true,
        'defaultController' => 'user',
        'defaultAction' => 'login'
    ],
);
