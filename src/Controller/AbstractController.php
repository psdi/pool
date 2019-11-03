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
}