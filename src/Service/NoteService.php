<?php

namespace Pool\Service;

use Pool\Core\Factory\NoteFactory;
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

    public function select()
    {
        $notes = $this->noteTable->fetchAll();
        $texts = $this->textTable->fetchAll();

        return $this->match($notes, $texts);
    }

    private function match(array $notes, array $texts)
    {
        $list = [];
        $textIds = array_column($texts, 'id');

        foreach ($notes as $note) {
            $index = array_search($note['text_id'], $textIds);
            if ($index === false) {
                continue;
            }
            $note['text'] = $texts[$index] ?? [];
            $list[] = NoteFactory::create($note);
        }

        return $list;
    }

    public function saveNote()
    {
        // validate data
    }
}