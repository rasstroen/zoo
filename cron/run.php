<?php
/**
 * Все пути относительно index.php
 */
ini_set('display_errors', true);
chdir(dirname(__FILE__).'/../data/');
/**
 * время запуска скрипта
 */
$microtime_start = microtime($get_as_float = true);
/**
 * Кеширование nginx разруливается кодом. Строчкой ниже по умолчанию убиваем
 * кеширование nginx
 */
header("X-Accel-Expires: 0");
/**
 * Сюда приходят все запросы
 */
$localconfig_array = array();
/**
 * Постоянный конфиг
 */
require_once '../core/Config.php';
/**
 * Локальный для сервера конфиг
 */
if (file_exists('../project/config.php'))
	require_once '../project/config.php';
/**
 * Инициализируем конфиг
 */
$config = new Config($localconfig_array);

/**
 * Настройки сервера
 */
ini_set('display_errors', $config->get('display_errors', false));
error_reporting($config->get('error_reporting', 0));

/**
 * Подключаем автолоадер
 */
require_once '../core/aloader.php';
$auto_loader = new aLoader();
$auto_loader->setIncludePathes($config);
/**
 * Подключаем функции
 */
require_once '../core/include.php';

/**
 * Создаем приложение, передаем в конструктор конфигурацию
 */
$App = App::i($config);
$cronClassName = 'C'.$argv[1];
$cron = new $cronClassName;
/**@var $cron Cron*/
$cron->run();