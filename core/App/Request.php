<?php

class Request
{

	private $_get = array();
	private $_post = array();
	private $_files = array();
	private $_cookie = array();
	private $request_uri_array = array();
	private $request_uri = '';

	function __construct()
	{

		$this->_get    = $_GET;
		$this->_post   = $_POST;
		$this->_files  = $_FILES;
		$this->_cookie = $_COOKIE;
		unset($_GET);
		unset($_POST);
		unset($_FILES);
		unset($_COOKIE);
		/**
		 * Проверяем, правильный ли URL пришел. Если URL необходимо исправить,
		 * функция попросит приложение отредиректить юзера на правильный с 301
		 * кодом.
		 */
		return $this->checkRequestURL();
	}

	public function checkRequestURL()
	{
		$request_url        = isset($_SERVER['REQUEST_URI']) ? $_SERVER['REQUEST_URI'] : '/';
		$this->request_uri  = $request_url;
		$request_url_array  = explode('?', $request_url);
		$request_url        = $request_url_array[0];
		$request_url_params = '';
		if (isset($request_url_array[1]))
			$request_url_params = '?' . $request_url_array[1];
		$request_url_array = explode('/', $request_url);
		array_shift($request_url_array);
		$request_url_array_fixed = array();
		foreach ($request_url_array as $uri_part) {
			if ($uri_part) {
				$request_url_array_fixed[] = trim(htmlspecialchars($uri_part));
			}
		}
		$ideal_url = '/' . implode('/', $request_url_array_fixed);
		// отрезаем последний слеш всегда
		if ($request_url !== $ideal_url) {
			/**
			 * редиректим на правильный url
			 */
			App::i()->addResponseHeader('Location: ' . $ideal_url . $request_url_params, 301);
			App::i()->sendHeaders();
			return false;
		}
		$this->request_uri_array = $request_url_array_fixed;
		return true;
	}

	public function getUriPartByIdx($idx = 0, $default_value = null)
	{
		return isset($this->request_uri_array[$idx]) ? $this->request_uri_array[$idx] : $default_value;
	}

	public function param($request_param_name, $default_value = null)
	{
		$ret = isset($this->_post[$request_param_name]) ? $this->_post[$request_param_name] : null;
		if (null === $ret)
			$ret = isset($this->_get[$request_param_name]) ? $this->_get[$request_param_name] : $default_value;
		return $ret;
	}

	public function getRequestUriArray()
	{
		return $this->request_uri_array;
	}

	public function getRequestUri()
	{
		return '/' . implode('/', $this->getRequestUriArray()) . '/';
	}

}