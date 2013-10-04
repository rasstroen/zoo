<?php
class Logger
{
	private $log = array();
	private $actions = array();

	public function log($message)
	{
		$this->log[] = $message;
	}

	public function getHtmlLog()
	{
		$sum = 0;
		foreach ($this->actions as &$act) {
			$sum +=$act[0];
			$act = sprintf('%.5f', $act[0]) . '[' . $act[1] . ']';
		}

		$res = '<!--' . implode( "\n" , $this->log) . '-->'."\n\n";
		$res .='<!--TIMINGS-->'."\n\n". '<!--' . implode( "\n" , $this->actions) . '-->';
		return $res;
	}

	public function timing($actionName)
	{
		if (!isset($this->actions[$actionName]) || is_array($this->actions[$actionName])) {
			$this->actions[$actionName] = microtime(true);
		} else
			$this->actions[$actionName] = array((microtime(true) - $this->actions[$actionName]), $actionName);
	}
}