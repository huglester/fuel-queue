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


namespace Queue;


class Queue
{
	public static $instance = null;
	
	public static function _init()
	{
		\Config::load(include __DIR__.'/../config/queue.php', 'queue');		
		$driver_active = \Config::get('queue.activedriver');		
		$driver_class  = \Config::get('queue.drivers.'.$driver_active.'.drivername');		

		static::$instance = new $driver_class;
	}
	
	public static function push($queue, $item)
	{
		return static::$instance->push($queue, $item);
	}
	
	public static function pop($queue)
	{
		return static::$instance->pop($queue);
	}
	
	public static function size($queue)
	{
		return static::$instance->size($queue);
	}
	
	public static function enqueue($queue, $class, $args = null)
	{
		return static::$instance->enqueue($queue, $class, $args);
	}
	
	public static function reserve($queue)
	{
		return static::$instance->reserve($queue);
	}
	
	/**
	 * Get an array with all know queues.
	 * 
	 * @return array Array of queues.
	 */
	public static function queues()
	{
		return static::$instance->queues();
	}
	
	public static function test()
	{
		
	}
}