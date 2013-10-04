<?php
/**
 * Âñå ïóòè îòíîñèòåëüíî index.php
 */
ini_set('display_errors', true);
chdir(dirname(__FILE__).'/../data/');

/**
 * âğåìÿ çàïóñêà ñêğèïòà
 */
$microtime_start = microtime($get_as_float = true);
/**
 * Êåøèğîâàíèå nginx ğàçğóëèâàåòñÿ êîäîì. Ñòğî÷êîé íèæå ïî óìîë÷àíèş óáèâàåì
 * êåøèğîâàíèå nginx
 */
header("X-Accel-Expires: 0");
/**
 * Ñşäà ïğèõîäÿò âñå çàïğîñû
 */
$localconfig_array = array();
/**
 * Ïîñòîÿííûé êîíôèã
 */
require_once '../core/Config.php';
/**
 * Ëîêàëüíûé äëÿ ñåğâåğà êîíôèã
 */
if (file_exists('../project/config.php'))
	require_once '../project/config.php';
/**
 * Èíèöèàëèçèğóåì êîíôèã
 */
$config = new Config($localconfig_array);

/**
 * Íàñòğîéêè ñåğâåğà
 */
ini_set('display_errors', $config->get('display_errors', false));
error_reporting($config->get('error_reporting', 0));

/**
 * Ïîäêëş÷àåì àâòîëîàäåğ
 */
require_once '../core/aloader.php';
$auto_loader = new aLoader();
$auto_loader->setIncludePathes($config);
/**
 * Ïîäêëş÷àåì ôóíêöèè
 */
require_once '../core/include.php';

/**
 * Ñîçäàåì ïğèëîæåíèå, ïåğåäàåì â êîíñòğóêòîğ êîíôèãóğàöèş
 */
$App = App::i($config);
$App->_geo()->regenerate();
