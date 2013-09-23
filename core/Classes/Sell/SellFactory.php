<?php

class SellFactory
{
	const SELL_TYPE_ANIMAL = 1;
	const SELL_TYPE_STUFF  = 100;

	private static $sells = array();

	/**
	 * По данным из бд получаем массив товаров - классов, наследуемых от класса Sell.
	 * Класс определяется по полю type товара.
	 *
	 * @param array $rowDatas
	 * @return Sell[]
	 */
	public static function getByRowData(array $rowDatas)
	{
		$sells = array();
		foreach ($rowDatas as $rowData) {
			$sells[$rowData['id']] = self::createTypedSell($rowData);
		}
		return $sells;
	}

	private static function createTypedSell(array $rowData)
	{
		if (!isset(self::$sells[$rowData['id']])) {
			switch ($rowData['type']) {
				case self::SELL_TYPE_ANIMAL:
					$sell = new SellAnimal($rowData);
					break;
				case self::SELL_TYPE_STUFF:
					$sell = new SellStuff($rowData);
					break;
				default:
					$sell = new SellCommon($rowData);
					break;
			}
			self::$sells[$rowData['id']] = $sell;
		}
		return self::$sells[$rowData['id']];
	}
}