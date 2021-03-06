<?php

namespace Dex\Microblog;

use Phalcon\Di\DiInterface;
use Phalcon\Loader;
use Phalcon\Mvc\ModuleDefinitionInterface;

class Module implements ModuleDefinitionInterface
{
    public function registerAutoloaders(DiInterface $di = null)
    {
        $loader = new Loader();

        $loader->registerNamespaces([

            'Dex\Microblog\Core\Domain\Event' => __DIR__ . '/Core/Domain/Event',
            'Dex\Microblog\Core\Domain\Model' => __DIR__ . '/Core/Domain/Model',
            'Dex\Microblog\Core\Domain\Repository' => __DIR__ . '/Core/Domain/Repository',
            'Dex\Microblog\Core\Domain\Service' => __DIR__ . '/Core/Domain/Service',

            'Dex\Microblog\Core\Application\Service' => __DIR__ . '/Core/Application/Service',
            'Dex\Microblog\Core\Application\Request' => __DIR__ . '/Core/Application/Request',
            'Dex\Microblog\Core\Application\Response'=> __DIR__ . '/Core/Application/Response',
            'Dex\Microblog\Core\Application\EventSubscriber' => __DIR__ . '/Core/Application/EventSubscriber',

            'Dex\Microblog\Infrastructure\Persistence' => __DIR__ . '/Infrastructure/Persistence',
            'Dex\Microblog\Infrastructure\Persistence\Record'=> __DIR__ . '/Infrastructure/Persistence/Record',

            'Dex\Microblog\Presentation\Web\Controller' => __DIR__ . '/Presentation/Web/Controller',
            'Dex\Microblog\Presentation\Web\Validator' => __DIR__ . '/Presentation/Web/Validator',
            'Dex\Microblog\Presentation\Api\Controller' => __DIR__ . '/Presentation/Api/Controller',

        ]);

        $loader->register();
    }

    /**
     * @param DiInterface|null $di
     */
    public function registerServices(DiInterface $di = null)
    {
        $moduleConfig = require __DIR__ . '/config/config.php';

        $di->get('config')->merge($moduleConfig);

        require_once __DIR__ . '/config/services.php';
    }
}
