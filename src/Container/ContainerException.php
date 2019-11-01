<?php

namespace Pool\Container;

use Psr\Container\ContainerExceptionInterface;

class ContainerException extends Exception implements ContainerExceptionInterface
{
    protected static $message = 'An {error} occurred while attempting to get "{id}" from the container.';

    /**
     * Get error message
     * 
     * @param string|mixed $dependency
     * @param \Exception|null $prev
     */
    public function getMessage($dependency, \Exception $prev = null)
    {
        $message = strtr(self::$message, [
            '{id}' => is_string($dependency) ? $dependency : 'a dependency',
            '{error}' => $prev ? get_class($prev) : 'error',
        ]);

        if ($prev) {
            $message .= "\n" . 'Message: ' . $prev->getMessage();
        }

        return $message;
    }
}