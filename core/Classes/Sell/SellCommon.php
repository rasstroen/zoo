<?php
class SellCommon extends Sell
{
	public function getTitle()
	{
		return $this->rowData['title'];
	}

	public function getPrice($valuteId = false)
	{
		return max(0, min(10000000, (int)$this->load()->rowData['price']));
	}
}