<?php
/**
 * ��� ���� ������������ index.php
 */
ini_set('display_errors', true);
chdir(dirname(__FILE__).'/../data/');

/**
 * ����� ������� �������
 */
$microtime_start = microtime($get_as_float = true);
/**
 * ����������� nginx ������������� �����. �������� ���� �� ��������� �������
 * ����������� nginx
 */
header("X-Accel-Expires: 0");
/**
 * ���� �������� ��� �������
 */
$localconfig_array = array();
/**
 * ���������� ������
 */
require_once '../core/Config.php';
/**
 * ��������� ��� ������� ������
 */
if (file_exists('../project/config.php'))
	require_once '../project/config.php';
/**
 * �������������� ������
 */
$config = new Config($localconfig_array);

/**
 * ��������� �������
 */
ini_set('display_errors', $config->get('display_errors', false));
error_reporting($config->get('error_reporting', 0));

/**
 * ���������� ����������
 */
require_once '../core/aloader.php';
$auto_loader = new aLoader();
$auto_loader->setIncludePathes($config);
/**
 * ���������� �������
 */
require_once '../core/include.php';

/**
 * ������� ����������, �������� � ����������� ������������
 */
$App = App::i($config);
$App->_geo()->regenerate();
