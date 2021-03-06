<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Laravel Ratchet Configuration
    |--------------------------------------------------------------------------
    |
    | Here you can define the default settings for Laravel Ratchet.
    |
    */

    'class' => \SimonHamp\LaravelEchoRatchetServer\RatchetServer::class,
    'host' => '0.0.0.0',
    'port' => '8080',
    'connectionLimit' => false,
    'throttle' => [
        'onOpen' => '20:1',
        'onMessage' => '20:1',
     ],
    'abortOnMessageThrottle' => false,
    'blackList' => collect(),
    'zmq' => [
        'host' => '127.0.0.1',
        'port' => 5555,
        'method' => \ZMQ::SOCKET_PULL,
    ],
];
