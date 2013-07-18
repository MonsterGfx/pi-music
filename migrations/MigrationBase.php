<?php

abstract class MigrationBase {

	abstract public static function up();

	abstract public static function down();

}
