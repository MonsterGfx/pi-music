<?php

class Migrate {

	/**
	 * The table name for the migrations.
	 *
	 * If the migration table does not exist the first time a migration is run,
	 * it will be created.
	 */
	private static $table_name = 'sys_migrations';

	/**
	 * Migrate the database up to the greatest defined value
	 */
	public static function up()
	{
		// check to see if the "migration" table exists
		if(!Database::tableExists(Migrate::$table_name))
		{
			// if not, create it
			Database::execute("CREATE TABLE ".Migrate::$table_name." (id INTEGER PRIMARY KEY ASC, last INTEGER);");

			// initialize the value
			Database::execute("INSERT INTO ".Migrate::$table_name." VALUES ( 1, 0 );");
		}

		// get the "migration" model
		$migration = Database::voodORM()->table(Migrate::$table_name)->where('id', 1)->findOne();

		// get the current migration counter
		$value = $migration->last;
		// get the list of migration classes from the config
		$migrations = Config::get('migrations.migrations');

		// make a list of classes to rollback, just in case of error
		$rollback = array();

		// do the following in a try/catch block so we can roll back if need be
		try {
			while($value<max(array_keys($migrations)))
			{
				// increment the counter
				$value++;

				// if a migration exists for the incremented counter, then run it
				if(array_key_exists($value,$migrations))
				{
					// get the class name
					$class = $migrations[$value];

					if($class)
					{
						// run the migration "up" method
						$class::up();



						// add this class to the list of rollbacks, just in case
						$rollback[] = $class;
					}
					else
						throw new Exception("Missing migration class '$class' for entry $value");
				}
				else
					throw new Exception("Missing migration number: $value");
			}
		}
		catch(Exception $e)
		{
			// we need to rollback

			// reverse the rollback array, since items were added to the end
			$rollback = array_reverse($rollback);

			// step through and roll back
			foreach($rollback as $class)
			{
				$class::down();
			}

			// and rethrow the exception
			throw $e;
		}

		// if we get here, then everything succeeded
		// save the last migration counter
		$migration->last = $value;

		// and save the value
		$migration->save();
	}

	/**
	 * Migrate the database down to the specified value (or 0 if no value given)
	 *
	 * @param int $down_to
	 * The database version to migrate down to (default 0)
	 */
	public static function down($down_to=0)
	{
		// get the "migration" model
		$migration = Database::voodORM()->table(Migrate::$table_name)->where('id', 1)->findOne();

		// get the current migration counter
		$value = $migration->last;

		// get the list of migration classes from the config
		$migrations = Config::get('migrations.migrations');

		// make a list of classes to rollback, just in case of error
		$rollback = array();

		// do the following in a try/catch block so we can roll back if need be
		try {
			while($value>$down_to)
			{
				// if a migration exists for the incremented counter, then run it
				if(array_key_exists($value,$migrations))
				{
					// get the class name
					$class = $migrations[$value];

					if($class)
					{
						// run the migration "up" method
						$class::down();

						// add this class to the list of rollbacks, just in case
						$rollback[] = $class;
					}
					else
						throw new Exception("Missing migration class '$class' for entry $value");
				}
				else
					throw new Exception("Missing migration number: $value");

				// decrement the counter
				$value--;
			}
		}
		catch(Exception $e)
		{
			// we need to rollback

			// reverse the rollback array, since items were added to the end
			$rollback = array_reverse($rollback);

			// step through and roll back
			foreach($rollback as $class)
			{
				$class::up();
			}

			// and rethrow the exception
			throw $e;
		}

		// if we get here, then everything succeeded
		// save the last migration counter
		$migration->last = $value;

		// and save the value
		$migration->save();
	}
}