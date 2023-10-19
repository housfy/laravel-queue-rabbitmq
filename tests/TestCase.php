<?php

namespace Housfy\LaravelQueueRabbitMQ\Tests;

use Illuminate\Support\Facades\Queue;
use Orchestra\Testbench\TestCase as BaseTestCase;
use PhpAmqpLib\Connection\AMQPLazyConnection;
use Housfy\LaravelQueueRabbitMQ\LaravelQueueRabbitMQServiceProvider;
use Housfy\LaravelQueueRabbitMQ\Queue\RabbitMQQueue;

abstract class TestCase extends BaseTestCase
{
    protected function getPackageProviders($app): array
    {
        return [
            LaravelQueueRabbitMQServiceProvider::class,
        ];
    }

    protected function getEnvironmentSetUp($app): void
    {
        $app['config']->set('queue.default', 'rabbitmq');
        $app['config']->set('queue.connections.rabbitmq', [
            'driver' => 'rabbitmq',
            'queue' => 'default',
            'connection' => AMQPLazyConnection::class,

            'hosts' => [
                [
                    'host' => getenv('HOST'),
                    'port' => getenv('PORT'),
                    'vhost' => '/',
                    'user' => 'guest',
                    'password' => 'guest',
                ],
            ],

            'options' => [
                'ssl_options' => [
                    'cafile' => null,
                    'local_cert' => null,
                    'local_key' => null,
                    'verify_peer' => true,
                    'passphrase' => null,
                ],
            ],

            'worker' => 'default',

        ]);
    }

    protected function connection(string $name = null): RabbitMQQueue
    {
        return Queue::connection($name);
    }
}
