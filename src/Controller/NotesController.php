<?php

namespace Pool\Controller;

use Pool\Http\Request;
use Pool\Service\NoteService;
use Twig\Environment;

class NotesController extends AbstractController
{
    /** @var NoteService */
    private $service;

    public function __construct(Environment $renderer, Request $request, NoteService $noteService)
    {
        parent::__construct($renderer, $request);
        $this->service = $noteService;
    }

    public function index()
    {
        $items = $this->service->select();

        echo $this->renderer->render('index.twig', [
            'items' => $items,
        ]);
        exit;
    }
}
