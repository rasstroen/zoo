<?php
class Database
{
	/**
	 * @var array of Database
	 */
	private $connections = array();

	public function __get($dbname)
	{
		return isset($this->connections[$dbname]) ? $this->connections[$dbname] : $this->connect($dbname);
	}

	/**
	 * return PDO
	 * @param $dbname
	 */
	private function connect($dbname)
	{
		$dbconfigs = App::i()->config->get('db',array());
		$dbconfig = isset($dbconfigs[$dbname])?$dbconfigs[$dbname]:null;
		if(null === $dbconfig)
		{
			throw new ApplicationException('No config for db server '.$dbname);
		}
		$options = array();
		return $this->connections[$dbname] = new PDO('mysql:host='.$dbconfig['dbserver'].';dbname='.$dbconfig['dbname'],$dbconfig['username'],$dbconfig['password'],$options);
	}
}