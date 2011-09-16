<?php
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
		$this->
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