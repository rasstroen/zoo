<?php

/**
 * Какие настройки может содержать модуль?
 * -name - обязательное, имя модуля, оно же - имя в папке модулей /core/modules/имя.php
 * -action - обязательное, действие модуля - list/show/edit/new и т.п. участвует в формировании
 * имени функции в шаблоне
 * -mode - обязательное, поддействие модуля. list/all, list/newest, list/oldest - участвует в формировании
 * имени функции в шаблоне
 * -template - необязательное, в каком файле искать шаблон. по умолчанию равно name,участвует в формировании
 * имени функции в шаблоне
 * -nginx_cache
 * -use_parameters
 * -parameters
 * -ssi
 *
 * для модуля posts/show/index с шаблоном "beauti" шаблон будет находиться /templates/modules/beauti.php а функция называться
 * tp_beauti_show_index
 * для модуля posts/show/index без указания шаблона шаблон будет находиться в /templates/modules/posts.php а функция называться
 * tp_posts_show_index
 */
/**
 * Конфигурация страниц
 */
$pagesconfig = array();
/**
 * Главная страница
 */
$pagesconfig['index'] = array();
$pagesconfig['index']['layout'] = 'index';
$pagesconfig['index']['js'] = array();
$pagesconfig['index']['css'] = array(
	'index'
);
$pagesconfig['index']['blocks'] = array(
	'left' => array(
		array('name' => 'catalog', 'action' => 'list', 'mode' => 'main_menu_items'),
	),
	'content' => array(
		array('name' => 'catalog', 'action' => 'list', 'mode' => 'main_popular_categories'),
	),
);
/**
 * Страница каталога первого уровня
 */
$pagesconfig['catalog/level1'] = array();
$pagesconfig['catalog/level1']['layout'] = 'catalog_level1';
$pagesconfig['catalog/level1']['js'] = array();
$pagesconfig['catalog/level1']['css'] = array(
	'index'
);
$pagesconfig['catalog/level1']['blocks'] = array(
	'left' => array(
		array('name' => 'catalog', 'action' => 'list', 'mode' => 'catalog_childrens'),
	),
	'content' => array(
		array('name' => 'catalog', 'action' => 'list', 'mode' => 'catalog_top'),
	),
);

/**
 * Администрирование
 */

/**
 * Главная страница администрирования
 */
$pagesconfig['admin/main'] = array();
$pagesconfig['admin/main']['layout'] = 'admin';
$pagesconfig['admin/main']['js'] = array();
$pagesconfig['admin/main']['css'] = array(
	'admin'
);
$pagesconfig['admin/main']['blocks'] = array(
	'left' => array(
		array('name' => 'admin', 'action' => 'list', 'mode' => 'admin_menu_items'),
	),
	'content' => array(
		array('name' => 'admin', 'action' => 'list', 'mode' => 'admin_full_menu'),
	),
);
/**
 * Конфигурация умолчаний
 */
$pagesconfig['default'] = array();
/**
 * Конфигурация умолчаний для layout index
 */
$pagesconfig['default']['index'] = array();
$pagesconfig['default']['index']['blocks'] = array();
$pagesconfig['default']['index']['js'] = array('http://code.jquery.com/jquery-2.0.0.min.js');
$pagesconfig['default']['index']['blocks']['header'] = array(array('name' => 'html', 'action' => 'show', 'mode' => 'header_logo'));
$pagesconfig['default']['index']['blocks']['footer'] = array(array('name' => 'html', 'action' => 'show', 'mode' => 'footer'));
/**
 * Конфигурация умолчаний для layout admin
 */
$pagesconfig['default']['admin'] = array();
$pagesconfig['default']['admin']['blocks'] = array();
$pagesconfig['default']['admin']['js'] = array('http://code.jquery.com/jquery-2.0.0.min.js');
$pagesconfig['default']['admin']['blocks']['header'] = array(array('name' => 'html', 'action' => 'show', 'mode' => 'header_logo'));
$pagesconfig['default']['admin']['blocks']['footer'] = array(array('name' => 'html', 'action' => 'show', 'mode' => 'footer'));