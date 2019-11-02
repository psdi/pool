<?php

namespace Pool\Container;

use Psr\Container\NotFoundExceptionInterface;

class NotFoundException extends ContainerException implements NotFoundExceptionInterface
{
    protected $message = 'A dependency with the id "{id}" was not found.';
}