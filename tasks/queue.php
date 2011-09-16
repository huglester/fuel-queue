<?php
/**
 * Fuel-queue a simple queue module for FuelPHP
 *
 * @package    Queue
 * @author     Kavinsky
 * @license    MIT License
 * @copyright  2010 - 2011 Ignacio "Kavinsky" Muñoz
 * @link       http://github.com/kavinsky/fuel-queue
 */
namespace Fuel\Tasks;


class Queue
{
	public static function run($queue = null, $runs = 25)
	{
		if ($queue === null OR $queue === 'help')
		{
			static::help();
			return;
		}
		
		$queues = \Queue::queues();
		if (!in_array($queue, $queues))
		{
			throw new \Fuel_Exception('Cannot locate queue: '.$queue);
			return;
		}
		
		\Cli::write('Processing queue: '.$queue);
		
		
		$queue_size = \Queue::size($queue);
		
		if ($queue_size < 25)
		{
			$runs = $queue_size;
		}
		
		\Cli::write('Queue size: '.$queue_size);
		
		try {
			for($i = 0; $i < $runs; ++$i)
			{
				// execution environment				
				$job = \Queue::pop($queue);							
				\Oil\Refine::run($job['class'], $job['args']);			
			}
		}
		catch(Exception $e)
		{
			\Cli::write("Error: ". $e->getMessage());
		}
		
		
		\Cli::write('Total executions: '.$i);
		\Cli::write('Execution completed!');
	}
	
	public static function help()
	{
		\Cli::write('Help ');
	}
}