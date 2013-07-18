<?php

class Database {

	// the PDO database connection
	private static $db = null;

	// the VoodOrm object
	private static $voodORM = null;

	/**
	 * Get the PDO object, initializing it if necessary
	 *
	 * @return PDO
	 */
	public static function pdo()
	{
		// check & see if the connection is already open
		if(!Database::$db)
		{
			Database::$db = new PDO(Config::get('database.dsn'));
			Database::$db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
		}

		return Database::$db;
	}

	/**
	 * Get the VoodOrm object, initializing it if necessary
	 *
	 * @return VoodOrm
	 */
	public static function voodORM()
	{
		// check & see if the object already exists
		if(!Database::$voodORM)
			Database::$voodORM = new Voodoo\VoodOrm(Database::pdo());

		return Database::$voodORM;
	}

	/**
	 * Check to see if a table exists in the database
	 *
	 * @param string $table
	 * The table name to check
	 *
	 * @return bool
	 * True if the table exists, false otherwise
	 */
	public static function tableExists($table)
	{
		// the query to run
		$query = "SELECT name FROM sqlite_master WHERE type='table' AND name=:name;";

		// run the query
		return count(Database::query($query, array(':name'=>$table)))>0 ? true : false;
	}

	/**
	 * Execute an arbitrary query with (optional) named parameters
	 *
	 * @param string $query
	 * The query to execute
	 *
	 * @param array $parameters
	 * The parameters, as an associative array of parameter names=>values
	 *
	 * @return bool
	 * True on success, false on failure
	 */
	public static function execute($query, $parameters=null)
	{
		// prepare the statement
		$stmt = Database::pdo()->prepare($query);

		// bind the parameters
		if($parameters)
		{
			foreach($parameters as $key=>$value)
			{
				$stmt->bindValue($key, $value);
			}
		}
		// execute & return the result
		return $stmt->execute();
	}

	/**
	 * Execute a query and return the result set as an array of rows
	 *
	 * @param string $query
	 * The query to execute
	 *
	 * @param array $parameters
	 * The parameters, as an associative array of parameter names=>values
	 *
	 * @return array|bool
	 * The array of rows returned or false if the query fails
	 */
	public static function query($query, $parameters=null)
	{
		// prepare the statement
		$stmt = Database::pdo()->prepare($query);
		// bind the parameters
		if($parameters)
		{
			foreach($parameters as $key=>$value)
				$stmt->bindValue($key,$value);
		}

		$success = $stmt->execute();

		if(!$success)
			return false;

		// get the result set
		return $stmt->fetchAll(PDO::FETCH_ASSOC);
	}
}
