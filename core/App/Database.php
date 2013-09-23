<?php
/**
 * Class Database
 * @property PDODatabase web
 */
class Database
{

	/**
	 *
	 * @var PDODatabase[]
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
		$dbconfigs = App::i()->config->get('db', array());
		$dbc = isset($dbconfigs[$dbname]) ? $dbconfigs[$dbname] : null;
		if (null === $dbc) {
			throw new ApplicationException('No config for db server ' . $dbname);
		}
		$options = array();
		$dsn = 'mysql:host=' . $dbc['dbserver'] . ';dbname=' . $dbc['dbname'];
		return $this->connections[$dbname] = new PDODatabase($dsn, $dbc['username'], $dbc['password'], $options);
	}
}