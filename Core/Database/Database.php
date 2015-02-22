<?php 
namespace Core\Database;

/**
 * Basic database class used for common CRUD operations.
 *
 * @author <milos@caenazzo.com>
 */
class Database extends AbstractDatabase
{
    /**
     * Begin database transaction.
     */
    public function beginTransaction()
    {
    	$this->connection->beginTransaction();
    }

    /**
     * Commit database transaction.
     */
    public function commit()
    {
    	$this->connection->commit();
    }

    /**
     * Rollback current database transaction.
     */
    public function rollback()
    {
    	$this->connection->rollBack();	
    }

    /**
     * Classic query method using prepared statements.
     *
     * @param string $query
     * @param array $params
     * @return resource
     */
    public function query($query, array $params = [])
    {
        // Execute query
        $stmt = $this->connection->prepare($query);
       	$stmt->execute($params);
        // Return result resource variable
        return $stmt;
    }

	/**
	 * Select query.
	 *
	 * @param string $query
	 * @param array $params
	 * @param string $fetchMode
	 * @return array
	 */
	public function select($query, array $params = [], $fetchMode = null)
	{
	  	// Execute query	
	  	$stmt = $this->connection->prepare($query);
	  	$stmt->execute($params);
	  	if ($fetchMode !== null) {
			$stmt->setFetchMode($fetchMode);
	  	}
	  	return $stmt->fetchAll();
	}

	/**
	 * Insert query.
	 *
	 * @param string $query
	 * @param array $params
	 * @return int
	 */
	public function insert($query, array $params)
	{
	  	$stmt = $this->connection->prepare($query);
	  	$stmt->execute($params);
	  	return $stmt->rowCount();
	}

	/**
	 * Wrapper for PDO last insert id.
	 *
	 * @param string $name (optional)
	 * @return int
	 */
	public function lastInsertId($name = null)
	{
	  	return $this->connection->lastInsertId($name);
	}

	/**
	 * Update query.
	 *
	 * @param string $query
	 * @param array $params
	 * @return int
	 */
	public function update($query, array $params)
	{
	  	$stmt = $this->connection->prepare($query);
	  	$stmt->execute($params);
	  	return $stmt->rowCount();
	}

	/**
	 * Delete query.
	 *
	 * @param string $query
	 * @param array $params
	 * @return int
	 */
	public function delete($query, array $params)
	{
	  	$stmt = $this->connection->prepare($query);
	  	$stmt->execute($params);
	  	return $stmt->rowCount();
	}

	/**
	 * Count query.
	 *
	 * @param string $query
	 * @param array $params
	 * @return int
	 */
	public function count($query, array $params)
	{
	  	$stmt = $this->connection->prepare($query);
	  	$stmt->execute($params);
	  	return $stmt->fetchColumn();
	}

	/**
	 * Create table in database (MySQL specific).
	 *
	 * @param string $name
	 * @param array $fields Fields array example ['id'=>'INT AUTO_INCREMENT PRIMARY KEY', 'value'=>'varchar(10)']
	 * @param string $options (additional options for table like engine, UTF etc)
     * @return int
	 */
	public function createTable($name, array $fields, $options = null)
	{
		// Make query
		$sql = "CREATE TABLE IF NOT EXISTS $name (";
	    foreach ($fields as $field => $type) {
      		if (preg_match('/PRIMARY KEY/i', $type)) {
        		$pk = $field;
        		$type = str_replace('PRIMARY KEY', '', $type);
      		}
      		$sql .= "$field $type, ";
    	}
    	if (isset($pk)) {
    		$sql = rtrim($sql, ",").' PRIMARY KEY ('.$pk.')';
    	} else {
    		$sql = substr($sql, 0, strlen($sql) - 2);
    	}
    	if ($options === null) {
    		$sql .= ") CHARACTER SET utf8 COLLATE utf8_general_ci;";
        } else {
			$sql .= ")".$options;
        }

		// Execute query
	  	$stmt = $this->connection->prepare($sql);
	  	return $stmt->execute();
	}

	/**
	 * Add index to table column.
	 *
	 * @param string $table
	 * @param string $column
	 * @param string $name
     * @return bool
	 */
	public function addIndex($table, $column, $name)
	{
		$sql = sprintf('ALTER TABLE %s ADD INDEX %s(%s)', $table, $name, $column);

		// Execute query
	  	$stmt = $this->connection->prepare($sql);
	  	return $stmt->execute(); 
	}
}