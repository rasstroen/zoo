<?php

class Admin_module extends Module
{

	/**
	 * Меню админки
	 *
	 * @return array
	 */
	public function method_list_admin_menu_items()
	{
		$data = array();
		return $data;
	}

	/**
	 * Развернутое меню админки
	 *
	 * @return array
	 */
	public function method_list_admin_full_menu()
	{
		$data = array();
		return $data;
	}

	public function method_list_catalog_tree()
	{
		$data = array();
		$data['catalogues'] = App::i()->_catalog()->getCataloguesTree();
		return $data;
	}

	public function method_list_geo()
	{
		$data = array();
		$all = App::i()->_geo()->getFullTree();
		foreach($all as $geo){
			$data['geo'][$geo['parent_id']][$geo['id']] = $geo;
		}

		$data['current_geo'] = App::i()->_geo()->getByIp();

		return $data;
	}
}