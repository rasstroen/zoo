<?php
class Catalog
{
	private $tree = null;
	private $tree_plain = null;

	public function getCataloguesTree()
	{
		if (null == $this->tree) {
			$catalogues = App::i()->_db()->web->sql2array('SELECT * FROM `catalog` ORDER BY `parent_id`', null, 'id');
			foreach ($catalogues as $catalog) {
				$this->tree_plain[$catalog['id']] = $this->tree[$catalog['parent_id']][$catalog['id']] = new CatalogItem($catalog['id'], $catalog);
			}
		}
		return $this->tree;
	}

	public function getCataloguesTreePlain()
	{
		$this->getCataloguesTree();
		return $this->tree_plain;
	}

	public function getCatalogIdByName($catalogName)
	{
		$id = App::i()->_db()->web->sql2single('SELECT `id` FROM `catalog` WHERE `name`=?', array($catalogName));
		return $id ? $id : null;
	}

	public function getCatalogById($catalogId)
	{
		$tree = $this->getCataloguesTreePlain();
		return isset($tree[$catalogId]) ? $tree[$catalogId] : array();
	}

	public function getChildrens($catalogId)
	{
		$tree = $this->getCataloguesTree();
		return isset($tree[$catalogId]) ? $tree[$catalogId] : array();
	}
}