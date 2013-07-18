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
}
