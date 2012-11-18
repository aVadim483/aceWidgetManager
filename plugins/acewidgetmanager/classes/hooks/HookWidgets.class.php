<?php
/*---------------------------------------------------------------------------
* @Module Name: aceWidgetManager
* @Module Id: acewidgetmanager
* @Module URI: 
* @Description: Custom Block Manager for LiveStreet/ACE
* @Version:
* @Author: aVadim
* @Author URI: 
* @LiveStreet Version:
* @File Name: %%filename%%
* @License: GNU GPL v2, http://www.gnu.org/licenses/old-licenses/gpl-2.0.html
*----------------------------------------------------------------------------
*/
class PluginAcewidgetmanager_HookWidgets extends Hook
{
    private $sPlugin = 'acewidgetmanager';

    public function RegisterHook()
    {
        $this->AddHook('init_action', 'InitAction', __CLASS__);
    }

    public function InitAction()
    {
        if (!Config::Get('plugin.' . $this->sPlugin . '.init_action')) {
            $this->PluginAcewidgetmanager_Widget_InitWidgets();
            Config::Set('plugin.' . $this->sPlugin . '.init_action', 1);
        }
    }
}

// EOF