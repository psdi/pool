<?php

namespace Pool\Controller;

use Twig\Environment;

abstract class AbstractController
{
    /** @var Environment $renderer */
    protected $renderer;

    protected $request;

    public function __construct()
    {
    }
}