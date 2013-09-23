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
		$this->id      = $rowData['id'];
	}
}