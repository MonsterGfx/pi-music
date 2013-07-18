<?php

return array(

	/**
	 * The commands defined for the command line tool.
	 *
	 * These commands are defined simply as
	 * 		'command' => 'method'
	 *  where 'command' is the command entered on the command line and 'method'
	 * is the method to call.
	 */
	'commands' => array(

		'migrate:up'	=> 'Migrate::up',

		'migrate:down' 	=> 'Migrate::down',

		'music:scan'	=> 'Scan::scanAll',
	),

);