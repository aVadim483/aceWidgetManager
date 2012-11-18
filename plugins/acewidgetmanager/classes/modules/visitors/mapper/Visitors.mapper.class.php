<?php
/*---------------------------------------------------------------------------
 * @Plugin Name: aceWidgetManager
 * @Plugin Id: acewidgetmanager
 * @Plugin URI: 
 * @Description: Custom Block Manager for LiveStreet/ACE
 * @Version: 1.5.110
 * @Author: Vadim Shemarov (aka aVadim)
 * @Author URI: 
 * @LiveStreet Version: 0.5
 * @File Name: Visitors.mapper.class.php
 * @License: GNU GPL v2, http://www.gnu.org/licenses/old-licenses/gpl-2.0.html
 *----------------------------------------------------------------------------
 */

class PluginAcewidgetmanager_ModuleVisitors_MapperVisitors extends Mapper
{
    const VIZ_FILE = 'viz.dat'; // имя файла
    const VIZ_PERIOD = 300; // период активности (сек)

    /**
     * Получить массив онлайн-посетителей
     *
     * @param int $nTimePeriod
     * @return array|mixed
     */
    public function GetVisitorsArray($nTimePeriod = 0)
    {
        if (Config::Get('sys.cache.use') AND Config::Get('sys.cache.type') == 'memory') {
            $sData = Engine::getInstance()->Cache_Get(self::VIZ_FILE);
        } else {
            $sFileName = Config::Get('sys.cache.dir') . self::VIZ_FILE;
            $sData = @file_get_contents($sFileName);
        }
        if ($sData) {
            $aViz = @unserialize($sData);
        }
        if (!$sData OR !is_array($aViz)) {
            return array();
        }
        if (!$nTimePeriod) $nTimePeriod = self::VIZ_PERIOD;
        $nSize = sizeof($aViz);
        $aViz = $this->CheckVisitorsArray($aViz, $nTimePeriod);
        if ($nSize != sizeof($aViz)) $this->PutVisitorsArray($aViz);
        return $aViz;
    }

    protected function PutVisitorsArray($aViz)
    {
        $data = serialize($aViz);
        if (Config::Get('sys.cache.use') AND Config::Get('sys.cache.type') == 'memory') {
            Engine::getInstance()->Cache_Set($data, self::VIZ_FILE, array(), self::VIZ_PERIOD);
        } else {
            $sFileName = Config::Get('sys.cache.dir') . self::VIZ_FILE;
            @file_put_contents($sFileName, serialize($aViz));
        }
    }

    protected function CheckVisitorsArray($aViz, $nTimePeriod = 0)
    {
        if (!is_array($aViz)) array();
        if (!$nTimePeriod) $nTimePeriod = self::VIZ_PERIOD;

        asort($aViz);
        $nTimeLine = time() - $nTimePeriod;
        $nOffset = 0;
        foreach ($aViz as $sVizId=>$nTime) {
            if ($nTime < $nTimeLine) $nOffset++;
            else break;
        }
        if ($nOffset) $aViz = array_slice($aViz, $nOffset);
        return $aViz;
    }

    /**
     * Save visitor ID and current time
     *
     * @param $sVisitorId
     * @param int $nTimePeriod
     * @return int
     */
    public function SetVisitor($sVisitorId, $nTimePeriod = 0)
    {
        $aViz = $this->GetVisitorsArray($nTimePeriod);
        if ($sVisitorId) {
            $aViz[$sVisitorId] = time();
            $this->PutVisitorsArray($aViz);
        }
        return sizeof($aViz);
    }

    /**
     * Get count of visitors
     *
     * @param int $nTimePeriod
     * @return int
     */
    public function GetVisitorsCount($nTimePeriod = 0)
    {
        $aViz = $this->GetVisitorsArray($nTimePeriod);
        return sizeof($aViz);
    }

}

// EOF