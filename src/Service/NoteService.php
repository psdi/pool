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

    // https://stackoverflow.com/questions/27493302/how-should-services-communicate-between-each-other/27495720#27495720
    // https://stackoverflow.com/questions/10675512/where-do-i-put-a-database-query-in-mvc
    // https://martinfowler.com/eaaDev/uiArchs.html
    public function saveNote()
    {
        // validate data
    }
}