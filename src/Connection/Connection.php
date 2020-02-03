<?php declare(strict_types=1);


namespace  cjhswoftRabbitmq\Connection;


use function count;
use ReflectionException;
use function sprintf;
use Swoft;
use Swoft\Bean\BeanFactory;
use Swoft\Bean\Exception\ContainerException;
use Swoft\Connection\Pool\AbstractConnection;
use Swoft\Log\Helper\Log;
use cjhswoftRabbitmq\Contract\ConnectionInterface;
use cjhswoftRabbitmq\Exception\RabbitmqException;
use cjhswoftRabbitmq\Pool;
use cjhswoftRabbitmq\RabbitmqConfig;
use cjhswoftRabbitmq\RabbitmqEvent;
use Swoft\Stdlib\Helper\PhpHelper;
use Throwable;
use cjhswoftRabbitmq\RabbitmqConfig;
use PhpAmqpLib\Connection\AMQPStreamConnection;

/**
 * Class Connection
 *
 * @since 2.0
 */
abstract class Connection extends AbstractConnection  
{

    /**
     * @var RabbitmqConfig
     */
    protected $rabbitmq_config;

    /**
     * @var AMQPStreamConnection
     */
    protected $client;

    /**
     * @param Pool    $pool
     * @param RabbitmqConfig $rabbitmq_config
     */
    public function initialize(Pool $pool, RabbitmqConfig $rabbitmq_config)
    {
        $this->pool             = $pool;
        $this->rabbitmq_config  = $rabbitmq_config;
        $this->lastTime = time();

        $this->id = $this->pool->getConnectionId();
    }

    /**
     */
    public function create(): void
    {
        $this->createClient();
    }

    /**
     * Close connection
     */
    public function close(): void
    {
        $this->client->close();
    }


    public function createClient(): void
    {
        $config = [
            'host'           => $this->rabbitmq_config->getHost(),
            'port'           => $this->rabbitmq_config->getPort(),
            'username'       => $this->rabbitmq_config->getUsername()
            'password'       => $this->rabbitmq_config->getPassword(),
            'vhost'          => $this->rabbitmq_config->getVhost(),
        ];

        $this->client = $this->rabbitmq_config->getConnector()->connect($config);
 
    }

    /**
     * @param bool $force
     *
     */
    public function release(bool $force = false): void
    {
        $this->client->closeChannels();
        /* @var ConnectionManager $conManager */
        $conManager = BeanFactory::getBean(ConnectionManager::class);
        $conManager->releaseConnection($this->id);
        

        parent::release($force);
    }

 
 

    /**
     * @return bool
     */
    public function reconnect(): bool
    {
        try {
            $this->client->reconnect();
        } catch (Throwable $e) {
            
            return false;
        }
        return true;
    }

    /**
     * Pass other method calls down to the underlying client.
     *
     * @param string $method
     * @param array  $parameters
     *
     * @return mixed
     */
    public function __call(string $method, array $parameters)
    {
        Swoft::trigger(RabbitmqEvent::BEFORE_COMMAND, null, $method, $parameters);
        $result = call_user_func_array(array($this->client, $method), $parameters);
        Swoft::trigger(RabbitmqEvent::AFTER_COMMAND, null, $method, $parameters, $result);
        return $result;
    }

}
