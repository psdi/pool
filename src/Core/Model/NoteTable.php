<?php

namespace Pool\Core\Model;

use Pool\Core\Object\Note;
use Pool\TableGateway\TableGatewayInterface;
use RuntimeException;

class NoteTable
{
    /** @var TableGatewayInterface */
    private $tableGateway;

    public function __construct(TableGatewayInterface $tableGateway)
    {
        $this->tableGateway = $tableGateway;
    }

    public function fetchAll()
    {
        return $this->tableGateway->select([], 'last_updated', 'DESC');
    }

    /**
     * Get a single note data row with the id `$id`
     * 
     * @param int $id Row identifier
     * @throws RuntimeException
     * @return
     */
    public function getNote(int $id)
    {
        $rowset = $this->tableGateway->select(['id' => $id]);
        if (!$rowset) {
            throw new RuntimeException(sprintf(
                'Could not find row with identifier %d',
                $id
            ));
        }
        return $rowset;
    }

    /**
     * Save a text row from Note `$note`
     * 
     * @param Note $note
     * @return void
     */
    public function saveNote(Note $note)
    {
        $data = [
            'title' => $note->getTitle(),
            'created' => $note->getCreated() ?? 'current_timestamp()',
            'submitted' => $note->getSubmitted(),
            'last_updated' => $note->getLastUpdated() ?? 'current_timestamp()',
        ];

        $id = $note->getId();

        if (!$id) {
            $this->tableGateway->insert($data);
            return;
        }

        try {
            $this->getNote($id);
        } catch(RuntimeException $e) {
            echo $e->getMessage(); // handle error properly
            exit;
        }

        $this->tableGateway->update($data, ['id' => $id]);
    }

    /**
     * Delete a note row with id `$id`
     * 
     * @param int $id
     * @return void
     */
    public function deleteNote(int $id)
    {
        $this->tableGateway->delete(['id' => $id]);
    }
}