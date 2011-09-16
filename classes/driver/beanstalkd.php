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


class Driver_Beanstalkd
{
	private $_connection = null;
	
	public function __construct()
	{
		$this->_beanstalkd = \BeanStalk::open(array(
			'servers'				=> \Config::get('queue.drivers.beanstalkd.servers'),
			'select'				=> \Config::get('queue.drivers.beanstalkd.select', 'random peek'),
			'conenction_timeout'	=> \Config::get('queue.drivers.beanstalkd.connection_timeout', 0.5),
			'peek_usleep'			=> \Config::get('queue.drivers.beanstalkd.peak_usleep', 2500),
			'connection_retries'	=> \Config::get('queue.drivers.beanstalkd.connection_retries', 3),
			'auto_unyaml' 			=> true,
			
		)); 
	}
	
	public function push($queue, $item)
	{
		
	}
	
	public function pop($queue)
	{
		
	}
	
	public function size($queue)
	{
		
	}
	
	public function enqueue($queue, $class, $args = null)
	{
	
	}
	
	public function reserve($queue)
	{
	
	}
	
	public function queues()
	{
	
	}
}