<?php
/*---------------------------------------------------------------------------
 * @Plugin Name: aceWidgetManager
 * @Plugin Id: acewidgetmanager
 * @Plugin URI: 
 * @Description: Custom Widget Manager for LiveStreet/ACE
 * @Version:
 * @Author: Vadim Shemarov (aka aVadim)
 * @Author URI: 
 * @LiveStreet Version:
 * @File Name: %%filename%%
 * @License: GNU GPL v2, http://www.gnu.org/licenses/old-licenses/gpl-2.0.html
 *----------------------------------------------------------------------------
 */

class PluginAcewidgetmanager extends Plugin
{
    private $sPlugin = 'acewidgetmanager';

    public $aInherits = array(
        'entity' => array(
            'ModuleWidget_EntityWidget'
        ),
        'action' => array(
            'ActionAjax'
        ),
    );

    /**
     * Активация плагина
     *
     * @return  bool
     */
    public function Activate()
    {
        return true;
    }

    /**
     * Инициализация плагина
     *
     * @return  bool
     */
    public function Init()
    {
        return true;
    }

}

// EOF