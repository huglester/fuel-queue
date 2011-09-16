<?php



return array(

	
	'activedriver' => 'db',
	'drivers' => array(
		//
		// DB Driver
		'db' => array(
			'drivername' 	=> 'Driver_DB',
			'tablename'		=> 'queue',
			'connection'	=> false,
		),
		
		//
		// Redis Driver
		'redis' => array(
			'drivername' 	=> 'Driver_Redis',
			'store'			=> 'queue',
		),
		
		'beanstalkd' => array(
			'drivername'	=> 'Driver_Beanstalkd',
			
		),
		
		'mongodb'	=> array(
			'drivername'	=> 'Driver_MongoDB',
			
		),
	),
);