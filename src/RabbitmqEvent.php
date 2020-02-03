<?php declare(strict_types=1);


namespace cjhswoftRabbitmq;

/**
 * Class RabbitmqEvent
 *
 * @since 2.0
 */
class RabbitmqEvent
{
    /**
     * Before command
     */
    const BEFORE_COMMAND = 'swoft.rabbitmq.command.before';

    /**
     * After command
     */
    const AFTER_COMMAND = 'swoft.rabbitmq.command.after';
}