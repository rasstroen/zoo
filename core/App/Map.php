<?php

class Map
{

	public static $map = array(
		'index' => array(
			'' => 'index',
		),
		'admin' => array(
			'' => 'admin/main'
		),
		'catalog' => array(
			'%s' => array(
				'' => 'catalog/level1',
			)
		)
	);

	/**
	 *
	 * @param array $request_uri_array массив запроса explode по слешу(a/b/c => ["a","b","c"])
	 * @param type $strict если false, то url a/b/c найдет в конфиге a/b и не найдя c вернет настройки
	 * для a/b (проскипает лишние данные в конце url).
	 * @return string
	 * @throws NotFoundException 404 страница не найдена
	 */
	public function getPageNameByRequest(array $request_uri_array, $strict = true)
	{
		$url_array = count($request_uri_array) ? $request_uri_array : array('index');
		$page_name = false;
		foreach (self::$map as $page => $subparams) {
			if ($url_array[0] === $page) {
				$page_name = $this->getSubpageConfiguration($url_array, $subparams, $strict);
			}
		}
		if (false === $page_name) {
			throw new NotFoundException('404 /' . implode('/', $url_array));
		}
		return $page_name;
	}

	private function getSubpageConfiguration($url_array, $subparams, $max_page_name = '', $strict = true)
	{
		if (!is_array($subparams))
			return $subparams;
		array_shift($url_array);
		$next_url_part = isset($url_array[0]) ? $url_array[0] : '';
		foreach ($subparams as $page => $subparams_) {
			if ($page === '')
				$max_page_name = $subparams_;
			if ($next_url_part === $page) {
				return $this->getSubpageConfiguration($url_array, $subparams_, $max_page_name);
			}
		}

		if (is_numeric($next_url_part)) {
			foreach ($subparams as $page => $subparams_) {
				if ('%d' === $page) {
					return $this->getSubpageConfiguration($url_array, $subparams_, $max_page_name);
				}
			}
		}

		foreach ($subparams as $page => $subparams_) {
			if ('%s' === $page) {
				return $this->getSubpageConfiguration($url_array, $subparams_, $max_page_name);
			}
		}

		return (!is_array($subparams)) ? $subparams : ($strict ? false : $max_page_name);
	}

}