<?php
/*---------------------------------------------------------------------------
 * @Plugin Name: aceWidgetManager
 * @Plugin Id: acewidgetmanager
 * @Plugin URI: 
 * @Description: Custom Block Manager for LiveStreet/ACE
 * @Version: 2.0
 * @Author: Vadim Shemarov (aka aVadim)
 * @Author URI: 
 * @LiveStreet Version: 1.0.1
 * @File Name: Visitors.class.php
 * @License: GNU GPL v2, http://www.gnu.org/licenses/old-licenses/gpl-2.0.html
 *----------------------------------------------------------------------------
 */

/**
 * Модуль учета посетителей сайта
 */
class PluginAcewidgetmanager_ModuleVisitors extends Module
{
    private $sPlugin = 'acewidgetmanager';

    /** @var PluginAcewidgetmanager_ModuleVisitors_MapperVisitors */
    protected $oMapper;
    /**
     * Инициализация модуля
     */
    public function Init()
    {
        $this->oMapper = Engine::GetMapper(__CLASS__);
        $this->aConfig = HelperPlugin::GetConfig();
    }

    public function SetVisitor($nTimePeriod = 0)
    {
        //$sVisitorId = ACE::GetVisitorId();
        $sVisitorId = $this->Session_GetId();
        return $this->oMapper->SetVisitor($sVisitorId, $nTimePeriod);
    }

    public function GetVisitorsCount($nTimePeriod = 0, $bSetVisitor = true)
    {
        if ((func_num_args() == 1) AND is_bool($nTimePeriod)) {
            $bSetVisitor = $nTimePeriod;
            $nTimePeriod = 0;
        }
        if ($bSetVisitor) $this->SetVisitor($nTimePeriod);
        return $this->oMapper->GetVisitorsCount($nTimePeriod);
    }

    public function GetVisitorsArray($nTimePeriod = 0, $bSetVisitor = true)
    {
        if ((func_num_args() == 1) AND is_bool($nTimePeriod)) {
            $bSetVisitor = $nTimePeriod;
            $nTimePeriod = 0;
        }
        if ($bSetVisitor) $this->SetVisitor($nTimePeriod);
        return $this->oMapper->GetVisitorsArray($nTimePeriod);
    }

    public function Shutdown()
    {
    }

}
// EOF