<?php

namespace Pool\TableGateway;

/**
 * Entity model implementation
 * Patterned after: https://docs.zendframework.com/tutorials/getting-started/database-and-models/
 */
interface TableGatewayInterface
{
    /**
     * Used for SELECT queries, accepts an optional array of `where` parameters
     * 
     * @param array $where Optional SQL `where` parameters
     * @param string $orderBy SQL order criterion
     * @param string $direction Order direction; defaults to 'ASC'
     * @return array
     */
    public function select(array $where = [], string $orderBy = '', string $direction = 'ASC');


    /**
     * Used for UPDATE queries; accepts an optional array of `where` parameters
     * 
     * @param array $data Data to be inserted
     * @param array $where SQL `where` parameters
     */
    public function update(array $data, array $where);

    /**
     * Used for INSERT queries
     * 
     * @param array $data Data to be inserted
     */
    public function insert(array $data);

    /**
     * Used for DELETE queries; accepts an optional array of `where` parameters
     * 
     * @param array $where Optional SQL `where` parameters
     */
    public function delete(array $where = []);
}