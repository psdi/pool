<?php

namespace Pool\Core\Model;

use Core\TableGateway\TableGatewayInterface;
use Pool\Core\Object\Text;
use RuntimeException;

class TextTable
{
    /** @var TableGatewayInterface */
    private $tableGateway;

    public function __construct(TableGatewayInterface $tableGateway)
    {
        $this->tableGateway = $tableGateway;
    }

    public function fetchAll()
    {
        return $this->tableGateway->select();
    }

    /**
     * Get a single text data row with the id `$id`
     * 
     * @param int $id Row identifier
     * @throws RuntimeException
     * @return
     */
    public function getText(int $id)
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
     * Save a text row from Text `$text`
     * 
     * @param Text $text
     * @return void
     */
    public function saveText(Text $text): void
    {
        $data = [
            'text' => $text->getText(),
        ];

        $id = $text->getId();

        if (!$id) {
            $this->tableGateway->insert($data);
            return;
        }

        try {
            $this->getText($id);
        } catch (RuntimeException $e) {
            echo $e->getMessage(); // handle error properly
            exit;
        }

        $this->tableGateway->update($data, ['id' => $id]);
    }

    /**
     * Delete a text row with id `$id`
     * 
     * @param int $id
     * @return void
     */
    public function deleteText(int $id): void
    {
        $this->tableGateway->delete(['id' => $id]);
    }
}