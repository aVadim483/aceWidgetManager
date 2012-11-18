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

class PluginAcewidgetmanager_ActionAjax extends PluginAcewidgetmanager_Inherit_ActionAjax
{
    private $sPlugin = 'acewidgetmanager';

    protected function _DateDiff($date)
    {
        $time = time() - strtotime($date);
        $seconds = $time % 60;
        $time = ($time - $seconds) / 60;
        $minutes = $time % 60;
        $time = ($time - $minutes) / 60;
        $hours = $time % 24;
        $time = ($time - $hours) / 24;
        $days = $time;
        $result = '';
        if ($days) $result .= $days . 'd ';
        if ($hours) $result .= $hours . 'h ';
        if ($minutes) $result .= $minutes . 'm ';
        $result .= $seconds . 's';
        return $result;
    }

    protected function RegisterEvent()
    {
        parent::RegisterEvent();
        $this->AddEvent('usersonline', 'EventUsersOnline');
    }

    protected function EventUsersOnline()
    {
        $bStateError = true;
        $sTitle = '';
        $sText = '';
        $aUsersOnline = array();
        $nUsersTotal = 0;

        $nUsersMax = intval($_REQUEST['users_max']);
        $nUsersPeriod = intval($_REQUEST['users_period']);
        $nRenewTime = intval($_REQUEST['renew_time']);
        $nAvatarSize = intval($_REQUEST['avatar_size']);

        if (($aUsersLast = $this->User_GetUsersByDateLast($nUsersMax))) {
            $aUsersOnline = array();
            foreach ($aUsersLast as $oUser) {
                if ($nUsersPeriod == 0 or ($nUsersPeriod and strtotime('+' . $nUsersPeriod . ' seconds', strtotime($oUser->getSession()->GetDateLast())) > time())) {
                    $aUsersOnline[] = array(
                        'login' => $oUser->GetLogin(),
                        'name' => $oUser->GetProfileName() ? $oUser->GetProfileName() : $oUser->GetLogin(),
                        'last' => $oUser->getSession()->GetDateLast(),
                        'time' => $this->_DateDiff($oUser->GetDateLast()),
                        'avatar' => $nAvatarSize ? $oUser->getProfileAvatarPath($nAvatarSize) : '',
                        'link' => $oUser->getUserWebPath(),
                    );
                }
            }
            $nUsersTotal = $this->PluginAcewidgetmanager_Visitors_GetVisitorsCount($nUsersPeriod);
            //$aUsersTotal = Engine::getInstance()->PluginAcewidgetmanager_Visitors_GetVisitorsArray($nUsersPeriod);
            $nUsersCount = sizeof($aUsersOnline);
            if ($nUsersCount > $nUsersTotal) $nUsersTotal = $nUsersCount;
            $bStateError = false;
        } else {
            $sTitle = $this->Lang_Get('error');
            $sText = $this->Lang_Get('system_error');
        }

        $this->Viewer_AssignAjax('bStateError', $bStateError);
        $this->Viewer_AssignAjax('aUsersOnline', $aUsersOnline);
        $this->Viewer_AssignAjax('nUsersCount', $nUsersCount);
        $this->Viewer_AssignAjax('nUsersTotal', $nUsersTotal);

        $this->Viewer_AssignAjax('sTitle', $sTitle);
        $this->Viewer_AssignAjax('sText', $sText);

    }


}

// EOF