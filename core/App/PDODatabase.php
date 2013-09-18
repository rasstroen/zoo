<?php
class PDODatabase
{
	/**
	 * @var PDO
	 */
	private $pdo;

	function __construct($dsn, $username, $password, $options)
	{
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
	private function query($query, $parameters)
	{
		$stmt = $this->pdo->prepare($query);
		if ($stmt->execute($parameters)) {
			return $stmt;
		} else {
			$errorInfo = $stmt->errorInfo();
			throw new ApplicationException('Database error:' . array_pop($errorInfo));
		}
	}


}