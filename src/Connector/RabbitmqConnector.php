<?php declare(strict_types=1);


namespace cjhswoftRabbitmq\Connector;

use Swoft\Bean\Annotation\Mapping\Bean;
use cjhswoftRabbitmq\Contract\ConnectorInterface;
use cjhswoftRabbitmq\Exception\RabbitmqException;
use Swoft\Stdlib\Helper\Arr;
use Swoft\Stdlib\Helper\JsonHelper;
use PhpAmqpLib\Connection\AMQPStreamConnection;
use cjhswoftRabbitmq\AMQPSwooleConnection;
/**
 * Class RabbitmqConnector
 *
 * @since 2.0
 *
 * @Bean()
 */
class RabbitmqConnector implements ConnectorInterface
{
    /**
     * @param array $config
     *
     * @return  AMQPSwooleConnection
     */
    public function connect(array $config):AMQPSwooleConnection
    {

        $client = new AMQPSwooleConnection($config['host'], $config['port'], $config['username'], $config['password'], $config['vhost']);
        return $client;
    }
}
