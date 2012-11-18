<?php
/*---------------------------------------------------------------------------
 * @Plugin Name: aceWidgetManager
 * @Plugin Id: acewidgetmanager
 * @Plugin URI: 
 * @Description: Custom Block Manager for LiveStreet/ACE
 * @Version:
 * @Author: Vadim Shemarov (aka aVadim)
 * @Author URI: 
 * @LiveStreet Version:
 * @File Name: %%filename%%
 * @License: GNU GPL v2, http://www.gnu.org/licenses/old-licenses/gpl-2.0.html
 *----------------------------------------------------------------------------
 */

class PluginAcewidgetmanager_BlockUsersOnline extends Block
{

    public function Exec()
    {
        $aUsersOnlineParam['users_max'] = $this->GetParam('users_max', 20);
        $aUsersOnlineParam['users_period'] = $this->GetParam('users_period', 5 * 60);
        $aUsersOnlineParam['renew_time'] = $this->GetParam('renew_time', 10);
        $aUsersOnlineParam['show_last_time'] = $this->GetParam('show_last_time');
        $aUsersOnlineParam['show_avatar'] = $this->GetParam('show_avatar', false);
        $aUsersOnlineParam['show_username'] = $this->GetParam('show_username');
        $aUsersOnlineParam['show_login_only'] = $this->GetParam('show_login_only', false);
        $aUsersOnlineParam['show_compact_mode'] = $this->GetParam('show_compact_mode', false);
        if (is_null($aUsersOnlineParam['show_username'])) {
            $aUsersOnlineParam['show_username'] = !($aUsersOnlineParam['show_compact_mode'] AND $aUsersOnlineParam['show_avatar']);
        }

        $nVisitorsOnline = $this->PluginAcewidgetmanager_Visitors_SetVisitor($aUsersOnlineParam['users_period']);
        $this->Viewer_Assign('aUsersOnlineParam', $aUsersOnlineParam);
        $this->Viewer_Assign('nVisitorsOnline', $nVisitorsOnline);
    }
}

// EOF