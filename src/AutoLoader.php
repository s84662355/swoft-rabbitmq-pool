<?php declare(strict_types=1);


namespace cjhswoftRabbitmq;


use function bean;
use ReflectionException;
use Swoft\Bean\Exception\ContainerException;
use Swoft\SwoftComponent;

/**
 * Class AutoLoader
 *
 * @since 2.0
 */
class AutoLoader extends SwoftComponent
{
    /**
     * @return array
     */
    public function getPrefixDirs(): array
    {
        return [
            __NAMESPACE__ => __DIR__,
        ];
    }

    /**
     * @return array
     */
    public function metadata(): array
    {
        return [];
    }

    /**
     * @return array
     * @throws ReflectionException
     * @throws ContainerException
     */
    public function beans(): array
    {
        return [
            'rabbitmq-config'      => [
                'class'    => RabbitmqConfig::class,
                'host'     => '127.0.0.1',
                'port'     => 5672,
                'vhost'    => '/',
                'username' => 'guest',
                'password' => 'guest',
             
            ],
            'rabbitmq.pool' => [
                'class'   => Pool::class,
                'rabbitmqConfig' => bean('rabbitmq-config'),
                'mark'  => 'rabbitmq_pool',
                'minActive' => 10,
                'maxActive' => 10,
            ]
        ];
    }
}
