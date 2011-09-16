<?php
/**
 * Fuel-queue a simple queue module for FuelPHP
 *
 * @package    Queue
 * @author     Kavinsky
 * @license    MIT License
 * @copyright  2011 Ignacio "Kavinsky" Muñoz
 * @link       http://github.com/kavinsky/fuel-queue
 */


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