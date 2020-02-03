<?php declare(strict_types=1);


namespace cjhswoftRabbitmq\Connector;

use Swoft\Bean\Annotation\Mapping\Bean;
use cjhswoftRabbitmq\Contract\ConnectorInterface;
use cjhswoftRabbitmq\Exception\RabbitmqException;
use Swoft\Stdlib\Helper\Arr;
use Swoft\Stdlib\Helper\JsonHelper;
use PhpAmqpLib\Connection\AMQPStreamConnection;
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
     * @return  AMQPStreamConnection
     */
    public function connect(array $config):AMQPStreamConnection
    {

        $client = new AMQPStreamConnection($config['host'], $config['port'], $config['username'], $config['password'], $config['vhost']);
        return $client;
    }
}
