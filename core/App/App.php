<?php

class App
{

	private $responseHeaders = array();
	private $responseCookie = array();

	/**
	 * Базовая конфигурация
	 * @var Config $config
	 */
	public $config;

	/**
	 * Инстанс класса App
	 * @var App
	 */
	private static $instance = null;
	private $responseData = array();
	private $responseModules = array();

	/**
	 *
	 * @param Config $config
	 * @return App
	 */
	public static function i(Config $config = null)
	{
		if (!isset(self::$instance)) {
			self::$instance = new App();
			self::$instance->config = $config;
		}
		return self::$instance;
	}

	public function getBlockModulesData($block_name)
	{
		return isset($this->responseData[$block_name]) ? $this->responseData[$block_name] : null;
	}

	/**
	 *
	 * @param type $microtime время старта скрипта
	 * @return null
	 */
	public function startLogging($microtime = 0)
	{
		return null;
	}

	/**
	 *
	 * @param type $readmodule_name
	 * @param type $readmodule_action
	 * @param array $params
	 */
	final public function setWriteParams($readmodule_name, $readmodule_action, array $params)
	{
		$key = $readmodule_name . ' ' . $readmodule_action;
		if (isset($this->write_params[$key]))
			$this->write_params[$key] += $params;
		else
			$this->write_params[$key] = $params;
	}

	/**
	 *
	 * @param type $readmodule_name
	 * @param type $readmodule_action
	 * @return type
	 */
	final public function getWriteParams($readmodule_name, $readmodule_action)
	{
		$key = $readmodule_name . ' ' . $readmodule_action;
		return isset($this->write_params[$key]) ? $this->write_params[$key] : array();
	}

	/**
	 *
	 * @return null
	 */
	public function stopLogging()
	{
		return null;
	}

	/**
	 * Какие данные запрашивают,и в каком виде хотят получить эти данные?
	 * Получаем все настройки ответа
	 */
	public function prepareResponseConfiguration()
	{
		$pC = $this->_responseConfiguration();
		/**
		 * Инициализируем с конфигом, определяем страницу
		 */
		$page_name = $pC->getPageNameByRequest();
		/**
		 * Получаем настройки страницы
		 */
		$this->responseSettings = $pC->getResponseSettings($page_name);
		/**
		 * Получаем список модулей для выполнения страницы
		 */
		$this->responseModules = $pC->getResponseModules($page_name);
	}

	public function executeWriteModules()
	{

	}

	public function executeReadModules()
	{
		foreach ($this->responseModules as $block_name => $modules) {
			foreach ($modules as $module) {
				$module_class = ucfirst($module['name']) . '_module';
				$executed_module = new $module_class();
				/* @var $executed_module Module */
				$executed_module->setConfiguration($module);
				$executed_module->run();
				$this->responseData[$block_name][$module['name'] . $module['action'] . $module['mode']] = $executed_module->getResponseObject();
			}
		}
	}

	/**
	 * Обрабатываем исключения - по классу исключения определяем код ошибки для
	 * отдачи по HTTP, логгируем ошибку, выкидываем исключение обратно
	 * @param Exception $e
	 * @throws Exception
	 */
	function processException(Exception $e)
	{
		$error_class = get_class($e);
		$error_message = $error_class . ':' . $e->getMessage() . ' ' . $e->getFile() . ':' . $e->getLine();
		error_log($error_message);
		switch ($error_class) {
			case 'NotFoundException':
				$this->addResponseHeader('HTTP/1.0 404 Page Not Found');
				break;
		}
		$this->sendHeaders();
		throw $e;
	}

	function addResponseHeader($header_message, $header_code = 0)
	{
		$this->responseHeaders[$header_message] = $header_code;
		return $this;
	}

	function addResponseCookie($cookie_name, $cookie_value, $cookie_expire, $cookie_domain)
	{
		$this->responseCookie[$cookie_name] = array($cookie_value, $cookie_expire, $cookie_domain);
		$_COOKIE[$cookie_name] = $cookie_value;
		return $this;
	}

	/**
	 * Отсылаем заголовки ответа в браузер
	 */
	function sendHeaders()
	{
		foreach ($this->responseCookie as $cookie_name => $values)
			setcookie($cookie_name, $values[0], $values[1], '/', $values[2]);
		foreach ($this->responseHeaders as $message => $code)
			if ($code)
				header($message, false, $code);
			else
				header($message, false);

		return $this;
	}

	/**
	 * Отсылаем контент в браузер
	 */
	function sendBody()
	{
		$layout = $this->_responseConfiguration()->getLayout();
		if (false == $layout) {
			throw new MisconfigurationException('no layout');
		}
		if (!is_readable($layout))
			throw new MisconfigurationException('cant open ' . $layout);
		require $layout;
	}

	private function getComponent($name)
	{
		if (empty($this->components[$name])) {
			$this->components[$name] = new $name();
		}
		return $this->components[$name];
	}

	/**
	 * @return Request
	 */
	public function _request()
	{
		return $this->getComponent('Request');
	}

	/**
	 * @return Seo
	 */
	public function _seo()
	{
		return $this->getComponent('Seo');
	}

	/**
	 * @return Catalog
	 */
	public function _catalog()
	{
		return $this->getComponent('Catalog');
	}

	/**
	 * @return Database
	 */
	public function _db()
	{
		return $this->getComponent('Database');
	}

	/**
	 * @return Geo
	 */
	public function _geo()
	{
		return $this->getComponent('Geo');
	}

	/**
	 *
	 * @return ResponseConfiguration
	 */
	public function _responseConfiguration()
	{
		return $this->getComponent('ResponseConfiguration');
	}

}