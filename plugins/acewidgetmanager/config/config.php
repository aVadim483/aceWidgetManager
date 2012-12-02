<?php
/*---------------------------------------------------------------------------
 * @Plugin Name: aceWidgetManager
 * @Plugin Id: acewidgetmanager
 * @Plugin URI: 
 * @Description: Custom Widgets (LS Blocks) Manager for LiveStreet/ACE
 * @Version:
 * @Author: Vadim Shemarov (aka aVadim)
 * @Author URI: 
 * @LiveStreet Version:
 * @File Name: %%filename%%
 * @License: GNU GPL v2, http://www.gnu.org/licenses/old-licenses/gpl-2.0.html
 *----------------------------------------------------------------------------
 */

/*
PLEASE DO NOT TOUCH CONTENT OF THIS FILE
You can set any configurations you need on config.local.php

ПОЖАЛУЙСТА, НЕ КОРРЕКТИРУЙТЕ СОДЕРЖИМОЕ ЭТОГО ФАЙЛА
Вы можете задать необходимые конфигурации в файле config.local.php
*/
if (defined('WIDGETMANAGER_VERSION')) return array();

define('WIDGETMANAGER_VERSION', '2.0');
define('WIDGETMANAGER_VERSION_BUILD', '212');

$config = array('version' => WIDGETMANAGER_VERSION . '.' . WIDGETMANAGER_VERSION_BUILD);

/**
 * Конфигурация плагина, которая может быть переопределна в файле config.local.php
 */
$config['clear'] = false;

if (is_file(dirname(__FILE__) . '/config.local.php')) {
    include(dirname(__FILE__) . '/config.local.php');
}
return $config;

// EOF