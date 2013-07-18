<?php

/**
 * The base class for Migrations
 */
abstract class MigrationBase {

	/**
	 * The up method
	 */
	abstract public static function up();

	/**
	 * The down method
	 */
	abstract public static function down();

}
