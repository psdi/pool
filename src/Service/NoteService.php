<?php

namespace Pool\Service;

use Pool\Core\Model\NoteTable;
use Pool\Core\Model\TextTable;

class NoteService
{
    /** @var NoteTable */
    private $noteTable;
    /** @var TextTable */
    private $textTable;

    public function __construct(NoteTable $noteTable, TextTable $textTable)
    {
        $this->noteTable = $noteTable;
        $this->textTable = $textTable;
    }

    public function saveNote()
    {
        // validate data
    }
}