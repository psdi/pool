<?php

namespace Pool\Controller;

class NotesController extends AbstractController
{
    public function index()
    {
        echo $this->renderer->render('@index/main.html.twig', [
            'items' => [],
        ]);
        exit();
    }
}
