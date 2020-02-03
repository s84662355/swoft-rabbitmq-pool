<?php declare(strict_types=1);


namespace cjhswoftRabbitmq;

use ReflectionException;
use Swoft\Bean\BeanFactory;
use Swoft\Bean\Exception\ContainerException;
use Swoft\Connection\Pool\AbstractPool;
use Swoft\Connection\Pool\Contract\ConnectionInterface;
use cjhswoftRabbitmq\Connection\Connection;
use cjhswoftRabbitmq\Connection\ConnectionManager;
use Throwable;
use Exception;
/**
 * Class Pool
 *
 * @since 2.0
 */
class Pool extends AbstractPool
{
    /**
     * Default pool
     */
    const DEFAULT_POOL = 'rabbitmq.pool';

    /**
     * @var RabbitmqConfig
     */
    protected $rabbitmqConfig;

    /**
     * @return ConnectionInterface
     */
    public function createConnection(): ConnectionInterface
    {
        return $this->rabbitmqConfig->createConnection($this);
    }

    /**
     * call magic method
     *
     * @param string $name
     * @param array  $arguments
     *
     * @return Connection
     */
    public function __call(string $name, array $arguments)
    {
        try {
            /* @var ConnectionManager $conManager */
            $conManager = BeanFactory::getBean(ConnectionManager::class);

            $connection = $this->getConnection();

            $connection->setRelease(true);
            $conManager->setConnection($connection);
        } catch (Throwable $e) {
            throw new  Exception(
                sprintf('Pool error is %s file=%s line=%d', $e->getMessage(), $e->getFile(), $e->getLine())
            );
        }

        // Not instanceof Connection
        if (!$connection instanceof Connection) {
            throw new  Exception(
                sprintf('%s is not instanceof %s', get_class($connection), Connection::class)
            );
        }

        ///return call_user_func_array(array($this->client, $method), $parameters);

        return $connection->{$name}(...$arguments);
    }

    /**
     * @return RabbitmqConfig
     */
    public function getRabbitmqConfig():  RabbitmqConfig
    {
        return $this->rabbitmqConfig;
    }
}
