<?php

namespace Pool\Controller;

use Pool\Http\Request;
use Twig\Environment;

abstract class AbstractController
{
    /** @var Environment $renderer */
    protected $renderer;
    /** @var Request $request */
    private $request;

    /**
     * Controller constructor method
     * 
     * @param Environment $renderer Twig environment used to render templates
     * @param Request $request Request object
     */
    public function __construct(Environment $renderer, Request $request)
    {
        $this->renderer = $renderer;
        $this->request = $request;
    }

    /**
     * Retrieve a parameter from Request; return null if unavailable
     * 
     * @param string $name Parameter name
     * @param mixed|null $default Default return value
     */
    public function getParam(string $name, $default = null)
    {
        return $this->request->getAttribute($name, $default);
    }
}