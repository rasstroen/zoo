<?php
class PDODatabase
{
	/**
	 * @var PDO
	 */
	private $pdo, $dsn;
	private $queryNum = 0;

	function __construct($dsn, $username, $password, $options)
	{
		$this->dsn = $dsn;
		$this->pdo = new PDO($dsn, $username, $password, $options);
	}

	/**
	 * Возвращает массив ассоциативных массивов с результатом выборки
	 * или пустой массив
	 *
	 * @param $query
	 * @param array $parameters
	 * @param null $keyfield
	 * @return array
	 */
	public function sql2array($query, array $parameters = null, $keyfield = null)
	{
		$out = array();
		$result = $this->query($query, $parameters);
		$i = 0;
		while ($data = $result->fetch(PDO::FETCH_ASSOC)) {
			$out[$keyfield ? $data[$keyfield] : $i++] = $data;
		}
		return $out;
	}

	public function sql2row($query, array $parameters = null, $keyfield = null)
	{
		$result = $this->sql2array($query, $parameters, $keyfield);
		return array_shift($result);
	}

	/**
	 * Возвращает значение из бд или null
	 *
	 * @param $query
	 * @param array $parameters
	 * @return mixed|null
	 */
	public function sql2single($query, array $parameters = null)
	{
		$result = $this->query($query, $parameters);
		$data = $result->fetch(PDO::FETCH_ASSOC);
		if (count($data)) return array_pop($data);
		return null;
	}

	/**
	 * @param $query
	 * @param $parameters
	 * @return PDOStatement
	 */
	public function query($query, $parameters = null)
	{
		$this->queryNum++;
		$stmt = $this->pdo->prepare($query);
		App::i()->log($this->dsn . ':' . $stmt->queryString ."\n" .'query parameters:'."\n" . ($parameters?print_r($parameters,1):''));
		App::i()->_logger()->timing($this->dsn . ':' . $stmt->queryString .'['. $this->queryNum. ']', $stmt->queryString);
		if ($stmt->execute($parameters)) {
			App::i()->_logger()->timing($this->dsn . ':' . $stmt->queryString .'['. $this->queryNum. ']', $stmt->queryString);
			return $stmt;
		} else {
			$errorInfo = $stmt->errorInfo();
			App::i()->_logger()->timing($this->dsn . ':' . $stmt->queryString .'['. $this->queryNum. ']', $stmt->queryString);
			throw new ApplicationException('Database error:' . array_pop($errorInfo));
		}
	}

	public function lastId()
	{
		return $this->pdo->lastInsertId();
	}


}