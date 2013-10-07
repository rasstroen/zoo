<?php
abstract class Sell
{
	protected $id = null;
	protected $loaded = false;
	protected $rowData = null;

	public function getId()
	{
		return $this->id;
	}

	abstract function getTitle();

	abstract function getPrice($valuteId = false);


	public function __construct(array $rowData = null)
	{
		$this->rowData = $rowData;
		$this->id = $rowData['id'];
	}

	public static function getSellIdByData(array $data, $client_id)
	{
		$str = $data['offer_id'] . $client_id;
		return intval(substr(md5($str), 0, 14), 20);
	}
}