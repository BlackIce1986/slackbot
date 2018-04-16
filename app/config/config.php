<?php
/*
 * Modified: prepend directory path of current file, because of this file own different ENV under between Apache and command line.
 * NOTE: please remove this comment.
 */
defined('BASE_PATH') || define('BASE_PATH', getenv('BASE_PATH') ?: realpath(dirname(__FILE__) . '/../..'));
defined('APP_PATH') || define('APP_PATH', BASE_PATH . '/app');

return new \Phalcon\Config([
    'database' => [
        'adapter'     => 'Mysql',
        'host'        => 'localhost',
        'username'    => 'slackbot',
        'password'    => '*******',
        'dbname'      => 'slackbot',
        'charset'     => 'utf8',
    ],
    'application' => [
        'appDir'         => APP_PATH . '/',
        'controllersDir' => APP_PATH . '/controllers/',
        'modelsDir'      => APP_PATH . '/models/',
        'migrationsDir'  => APP_PATH . '/migrations/',
        'viewsDir'       => APP_PATH . '/views/',
        'pluginsDir'     => APP_PATH . '/plugins/',
        'libraryDir'     => APP_PATH . '/library/',
        'cacheDir'       => BASE_PATH . '/cache/',

        // This allows the baseUri to be understand project paths that are not in the root directory
        // of the webpspace.  This will break if the public/index.php entry point is moved or
        // possibly if the web server rewrite rules are changed. This can also be set to a static path.
        'baseUri'       => preg_replace('/public([\/\\\\])index.php$/', '', $_SERVER["PHP_SELF"]),
        'siteUrl'      => 'http://138.68.93.195'
    ],
    'slack' => [
        'token' => 'token',
        'bot'   => 'bot-api',
        'botName' => 'Money-money-money'
    ],
    'crypto' => [
        'url'   => 'https://sandbox.cryptopay.me/api/v2',
        'email' => 'vladislav.davarashvili@gmail.com',
        'pass'  => '******'
    ],
    'coinbase' => [
        'api_key' => '922d2287-3367-43bc-b21d-c4667502050c'
    ],
    'blockio' => [
        'btc_api_key'  => 'btc-test-api',
        'ltc_api_key'  => 'ltc-test-api',
        'doge_api_key' => 'doge-test-api',
        '_btc_api_key' => 'btc-api',
        'pin'          => '*********'
    ]
]);
