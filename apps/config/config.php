<?php

use Phalcon\Config;

return new Config(
    [
        'mode' => getenv('APP_MODE'), //DEVELOPMENT, PRODUCTION, DEMO

        'url' => [
            'baseUrl' => getenv('BASE_URL'),
        ],

        'application' => [
            'libraryDir' => APP_PATH . "/lib/",
            'cacheDir' => getenv('APP_MODE') == 'PRODUCTION' ? "/cache/" : APP_PATH . "/cache/",
            'mailCacheDir' => getenv('APP_MODE') == 'PRODUCTION' ? "/cache/mail/" : APP_PATH . "/cache/mail/",
        ],

        'database' => [
            'adapter' => getenv('DB_ADAPTER'),
            'host' => getenv('DB_HOST'),
            'username' => getenv('DB_USERNAME'),
            'password' => getenv('DB_PASSWORD'),
            'dbname' => getenv('DB_NAME'),
        ],

        'mail' => [
            'driver' => getenv('MAIL_DRIVER'),
            'username' => getenv('MAIL_SMTP_USERNAME'),
            'password' => getenv('MAIL_SMTP_PASSWORD'),
            'host' => getenv('MAIL_SMTP_HOST'),
            'port' => getenv('MAIL_SMTP_PORT'),
        ],

        'version' => '1.0',
    ]
);
