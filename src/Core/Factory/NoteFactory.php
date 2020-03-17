<?php

namespace Pool\Core\Factory;

use Pool\Core\Object\Note;

class NoteFactory
{
    /**
     * Create a `Note` object
     * 
     * @param array $data
     * @return Note
     */
    public static function create(array $data = []): Note
    {
        $note = new Note();

        $note->setId($data['id'] ?? -1);
        $note->setTitle($data['title'] ?? '');
        $text = TextFactory::create($data['text'] ?? []);
        $note->setText($text);
        if (isset($data['created'])) {
            $note->setCreated(new \DateTime($data['created']));
        }
        if (isset($data['last_updated'])) {
            $note->setLastUpdated(new \DateTime($data['last_updated']));
        }
        $note->setSubmitted((bool) ($data['submitted'] ?? false));

        return $note;
    }
}