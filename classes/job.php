<?php
namespace Queue;


class Job
{
	
	public $queue;
	
	public $payload;
	
	
	public function __construct($queue, $payload)
	{
		$this->queue = $queue;
		$this->payload = $payload;
	}
	
	public static function create($queue, $class, $args = null)
	{
		if($args !== null and !is_array($args))
		{
			throw new \Fuel_Exception('Invalid agruments: Supplied $args must be an array.');
		}
		
		$id = md5(uniqid('', true));
		
		Queue::push($queue, array(
			'class' => $class,
			'args'	=> array($args),
			'id'	=> $id,
		));
		
		return $id;
	}
	
	public static function reserve($queue)
	{
		$payload = Queue::pop($queue);
		if(!$payload) {
			return false;
		}
		
		return new Job($queue, $payload);
	}
	
	public function getArguments()
	{
		if(!isset($this->payload['args']))
		{
			return array();
		}
		
		return $this->payload['args'][0];
	}
	
	public function run()
	{
		$class = '\\Fuel\\Task\\'.$this->payload['class'];
		try {
			\Event::trigger('queue.before_run', $this);
			
			if(method_exists($class, 'up'))
			{
				\Oil\Refine::run($this->payload['class'].':up', $this->getArguments());
			}
			
			\Oil\Refine::run($this->payload['class'], $this->getArguments());
			
			if(method_exists($class, 'down'))
			{
				\Oil\Refine::run($this->payload['class'].':down', $this->getArguments());
			}
			
			\Event::trigger('queue.after_run', $this);
			
		}
		catch(Queue_Job_DontPerform $e)
		{
			return false;
		}
		
		return true;
	}
	
	public function __toString()
	{
		$name = array(
			'Queue_Job{'. $this->queue . '}'
		);
		
		if(!empty($this->payload['id']))
		{
			$name[] = 'ID: '. $this->payload['id'];
		}
		$name[] = $this->payload['class'];
		if(!empty($this->payload['args']))
		{
			$name[] = json_encode($this->payload['args']);
		}
		return '(' . implode(' | ', $name) . ')';
	}
}