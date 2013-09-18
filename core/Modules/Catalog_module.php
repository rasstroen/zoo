<?php

class Catalog_module extends Module
{

	/**
	 * Меню на главной странице
	 *
	 * @return array
	 */
	public function method_list_main_menu_items()
	{
		$data = array();
		App::i()->_catalog()->getRootCatalogues($levels = 3);
		return $data;
	}

	/**
	 * Список популярных категорий на главной
	 *
	 * @return array
	 */
	public function method_list_main_popular_categories()
	{
		$data = array();
		return $data;
	}

	/**
	 * Список подкаталогов каталога первого уровня
	 *
	 * @return array
	 */
	public function  method_list_catalog_childrens()
	{
		$data = array();
		$currentCatalogId = App::i()->_request()->getUriPartByIdx(1);
		if(!is_numeric($currentCatalogId))
			$currentCatalogId = App::i()->_catalog()->getCatalogIdByName($currentCatalogId);
		App::i()->_catalog()->getChildrens($currentCatalogId);
		return $data;
	}

	/**
	 * Список топовых товаров из каталога
	 * @return array
	 */
	public function method_list_catalog_top()
	{
		$data = array();
		return $data;
	}

}