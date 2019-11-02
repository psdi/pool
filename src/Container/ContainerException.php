<?php

namespace Pool\Container;

use Exception;
use Psr\Container\ContainerExceptionInterface;

class ContainerException extends Exception implements ContainerExceptionInterface
{
    protected $message = 'An {error} occurred while attempting to get "{id}" from the container.';

    /**
     * Get error message
     * 
     * @param string|mixed $dependency
     * @param \Exception|null $prev
     */
    public function getErrorMessage($dependency = '', \Exception $prev = null)
    {
        $message = strtr($this->message, [
            '{id}' => !empty($dependency) && is_string($dependency) ? $dependency : 'a dependency',
            '{error}' => $prev ? get_class($prev) : 'error',
        ]);

        if ($prev) {
            $message .= "\n" . 'Message: ' . $prev->getMessage();
        }

        return $message;
    }
}