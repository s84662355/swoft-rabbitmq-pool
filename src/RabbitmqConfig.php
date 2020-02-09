<?php declare(strict_types=1);


namespace cjhswoftRabbitmq;

use function bean;
use ReflectionException;
use Swoft\Bean\Exception\ContainerException;
use cjhswoftRabbitmq\Connection\Connection;
use cjhswoftRabbitmq\Connection\RabbitmqConnection;
use cjhswoftRabbitmq\Connector\RabbitmqConnector;
use cjhswoftRabbitmq\Contract\ConnectorInterface;
use cjhswoftRabbitmq\Exception\RabbitmqException;
use Swoft\Stdlib\Helper\Arr;

/**
 * Class RabbitmqConfig
 *
 * @since 2.0
 */
class RabbitmqConfig
{
 

    /**
     * @var string
     */
    private $host = '127.0.0.1';

    /**
     * @var int
     */
    private $port = 5672;

    /**
     * @var string
     */
    private $vhost = '/';

    /**
     * @var string
     */
    private $username = 'guest';

    /**
     * @var string
     */
    private $password = 'guest';

    
 

    /**
     * @var array
     */
    protected $connections = [];

    /**
     * @param Pool $pool
     *
     * @return Connection
     * @throws RabbitmqException
     * @throws ReflectionException
     * @throws ContainerException
     */
    public function createConnection(Pool $pool): Connection
    {
        $connection = $this->getConnection();
        $connection->initialize($pool, $this);
        $connection->create();

        return $connection;
    }
 
    /**
     * @return ConnectorInterface
     */
    public function getConnector(): ConnectorInterface
    {
       return $this->defaultConnectors() ;
    }

    /**
     * @return Connection
     * @throws RabbitmqException
     * @throws ReflectionException
     * @throws ContainerException
     */
    public function getConnection(): Connection
    {
        return $this->defaultConnections() ;
    }
 


    /**
     * @return RabbitmqConnector
     */
    public function defaultConnectors(): RabbitmqConnector 
    {
        return bean(RabbitmqConnector::class);
    }


    /**
     * @return RabbitmqConnection
     */
    public function defaultConnections(): RabbitmqConnection
    {
        return bean(RabbitmqConnection::class);  
    }


    /**
     * @return string
     */
    public function getHost(): string
    {
        return $this->host;
    }

    /**
     * @return int
     */
    public function getPort(): int
    {
        return (int)$this->port;
    }

 
    /**
     * @return string
     */
    public function getPassword(): string
    {
        return $this->password;
    }

    /**
     * @return string
     */
    public function getVhost(): string
    {
        return $this->vhost;
    }

    /**
     * @return string
     */
    public function getUsername(): string
    {
        return $this->username;
    }
 
}
