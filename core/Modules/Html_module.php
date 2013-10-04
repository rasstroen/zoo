<?php

class Html_module extends Module
{

	/**
	 * Шапка для любой страницы
	 *
	 * @return array
	 */
	public function  method_show_header_logo()
	{
		$data = array();
		$data['geo'] = App::i()->_geo()->getByIp();
		$data['geos'] =App::i()->_geo()->getFullTree();
		return $data;
	}

	/**
	 * Футер для каждой страницы
	 *
	 * @return array
	 */
	public function method_show_footer()
	{
		$data = array();
		return $data;
	}

	/**
	 * Контент на главной странице
	 *
	 * @return array
	 */
	public function method_show_main()
	{
		$data = array();
		return $data;
	}


}