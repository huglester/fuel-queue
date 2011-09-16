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


class Driver_DB
{
	private $_tablename = null;
	
	public function __construct()
	{
		$this->_tablename = \Config::get('queue.drivers.db.tablename', 'queue');
	}
	
	public function push($queue, $item, $priority = 5)
	{

		\DB::insert($this->_tablename, array('queue', 'priority', 'payload'))
					->values(array(
						$queue,
						$priority,
						json_encode($item),						
					))->execute();
		
		return $item['id'];
		
	}
	
	public function pop($queue)
	{
		$item = \DB::select('id', 'payload')
					->from($this->_tablename)
					->where('queue', '=', $queue)
					->order_by('id', 'ASC')
					->order_by('priority', 'DESC')
					->limit(1)
					->execute();
		
					
		if(!$item)
		{
			return;
		}
		
		\DB::delete($this->_tablename)
				->where('id', '=', $item->get('id'))
				->execute();
		
		return json_decode($item->get('payload'), true);
	}
	
	public function size($queue)
	{
		$ret = \DB::select(array('COUNT("id")', 'size'))
					->from($this->_tablename)
					->where('queue', '=', $queue)
					->execute()
					->get('size');
		if($ret < 1)
		{
			return 0;
		}
		
		return $ret;
	}
	
	public function enqueue($queue, $class, $args = null)
	{
		
		$result = Job::create($queue, $class, $args);
		if($result)
		{
			\Event::trigger('queue.after_enqueue', array(
				'class' => $class,
				'args' => $args,
			));
		}
		
		return $result;
	}
	
	public function reserve($queue)
	{
		return Job::reserve($queue);
	}
	
	public function queues()
	{
		$queues = \DB::select('queue')
			->from($this->_tablename)
			->execute();
		
		$raw_q = array();
		foreach($queues as $queue)
		{
			$raw_q[] = $queue['queue'];
		}
		$queues = array_unique($raw_q);
		
		if(!is_array($queues))
		{
			$queues = array();
		}
		return $queues;
	}
}