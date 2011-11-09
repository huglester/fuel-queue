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
		if ($queue === null)
		{
			$queue = 'default';
		}
				
		\Cli::write('Processing queue: '.$queue);		
		
		try {
			
			$run_number = 0;
			while(true)
			{
				sleep(5);
				\Cli::write('Starting run '.$run_number);
				
				$queue_size = \Queue::size($queue);
				if ($queue_size < 25)
				{
					$runs = $queue_size;
				}
				
				\Cli::write('Queue size: '.$queue_size);
						
				for($i = 0; $i < $runs; ++$i)
				{				
					// execution environment
					$job = \Queue::pop($queue);							
					\Oil\Refine::run($job['class'], $job['args']);			
				}
				
				$run_number++;
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
		\Cli::write('Usage: php oil r|refine [<queue_name>]');
		\Cli::write('If you dont specify the queue_name then will use "default" queue');
	}
}