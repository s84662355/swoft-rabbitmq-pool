<?php declare(strict_types=1);


namespace cjhswoftRabbitmq\Contract;

/**
 * Class ConnectorInterface
 *
 * @since 2.0
 */
interface ConnectorInterface
{
    /**
     * @param array $config
     *
     * @return Object
     */
    public function connect(array $config );

    
}
