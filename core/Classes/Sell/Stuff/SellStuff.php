<?php
class SellStuff extends Sell
{
	public function getPrice($valuteId = false)
	{
		return max(0, min(10000000, (int)$this->load()->rowData['price']));
	}

	public function getTitle()
	{

	}
}