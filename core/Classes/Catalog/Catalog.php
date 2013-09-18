<?php
class Catalog
{
	/**
	 * Отдаем дерево каталога от корня, с вложением $levels подуровней
	 * @param int $levels
	 */
	public function getRootCatalogues($levels = 1)
	{
		$levels = max(1, (int)$levels);
	}

	public function getCatalogIdByName($catalogName)
	{
		$id = App::i()->_db()->web->sql2single('SELECT `id` FROM `catalog` WHERE `name`=?',$catalogName);
		return $id?$id:null;
	}
}