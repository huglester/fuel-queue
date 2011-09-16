<?php
namespace Queue;


class Driver_Redis
{
	private $_redis = null;
	
	public function __construct()
	{
		$this->_redis = \Redis::instance(\Config::get('drivers.redis.store', 'default'));
	}
	
	public function push($queue, $item)
	{
		$this->_redis->sadd('queues', $queue);
		$this->_redis->rpush('queue:' . $queue, json_encode($item));
	}
	
	public function pop($queue)
	{
		$item = $this->_redis->lpop('queue:' . $queue);
		if(!$item)
		{
			return;
		}
		
		return json_decode($item, true);
	}
	
	public function size($queue)
	{
		return $this->_redis->llen('queue:' . $queue);
	}
	
	public function enqueue($queue, $class, $args = null)
	{
		$id = md5(uniqid('', true));
		
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
		$queues = $this->_redis->smembers('queues');
		if(!is_array($queues))
		{
			$queues = array();
		}
		return $queues;
	}
}