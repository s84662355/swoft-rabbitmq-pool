<?php declare(strict_types=1);

namespace cjhswoftRabbitmq;

use Swoft\Bean\BeanFactory;
use Throwable;
use Exception;
use cjhswoftRabbitmq\Connection\Connection;
use cjhswoftRabbitmq\Connection\ConnectionManager;
use cjhswoftRabbitmq\Exception\RabbitmqException;

/**
 * Class Rabbitmq
 */
class Rabbitmq
{
    /**
     * @param string $pool
     *
     * @return Connection
     * @throws RabbitmqException
     */
    public static function connection(string $pool = Pool::DEFAULT_POOL): Connection
    {
        try {
            /* @var ConnectionManager $conManager */
            $conManager = BeanFactory::getBean(ConnectionManager::class);

            /* @var Pool $rabbitmqPool */
            $rabbitmqPool  = BeanFactory::getBean($pool);
            $connection = $rabbitmqPool->getConnection();

            $connection->setRelease(true);
            $conManager->setConnection($connection);
        } catch (Throwable $e) {
            throw new RabbitmqException(
                sprintf('Pool error is %s file=%s line=%d', $e->getMessage(), $e->getFile(), $e->getLine())
            );
        }

        // Not instanceof Connection
        if (!$connection instanceof Connection) {
            throw new  RabbitmqException(
                sprintf('%s is not instanceof %s', get_class($connection), Connection::class)
            );
        }
        return $connection;
    }

    /**
     * @param string $method
     * @param array  $arguments
     *
     * @return mixed
     * @throws RabbitmqException
     */
    public static function __callStatic(string $method, array $arguments)
    {
        $connection = self::connection();
        return $connection->{$method}(...$arguments);
    }
}
