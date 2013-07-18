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
		$query = "SELECT name FROM sqlite_master WHERE type='table' AND name='table_name';";

		// run the query
		return Database::pdo()->query($query)->rowCount() ? true : false;
	}
}
