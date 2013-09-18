<?php
/**
 * Все пути относительно index.php
 */
chdir(dirname(__FILE__));
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
try {
    /**
     * Создаем приложение, передаем в конструктор конфигурацию
     */
    $App = App::i($config);
    /**
     * Начинаем логирование
     */
    $App->startLogging($microtime_start);

    /**
     * Определяем, что будем отдавать - какие модули нужно выполнить, в каком виде
     * (HTML/AJAX/XML) будем отдавать
     */
    $App->prepareResponseConfiguration();
    /**
     * Выполняем модули записи - POST запросы на изменение данных, например
     */
    $App->executeWriteModules();
    /**
     * Выполняем модули чтение
     */
    $App->executeReadModules();
    /**
     * Обрабатываем ошибки
     */
} catch (Exception $e) {
    $App->processException($e);
}
/**
 * Заканчиваем логирование
 */
$App->stopLogging();
/**
 * Выдаем заголовки
 */
$App->sendHeaders();
/**
 * Выдаем контент
 */
echo $App->sendBody();
