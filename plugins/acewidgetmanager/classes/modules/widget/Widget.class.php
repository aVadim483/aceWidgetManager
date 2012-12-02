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

/**
 * Модуль управления блоками
 */
class PluginAcewidgetmanager_ModuleWidget extends Module
{
    private $sPlugin = 'acewidgetmanager';
    protected $bClearBlocks = false;
    protected $aWidgets = array();
    protected $aConfig = array();

    protected $sCurentPath;

    protected function _str2Array($xVal)
    {
        if (is_array($xVal) AND (sizeof($xVal) == 1) AND isset($xVal[0]) AND strpos($xVal[0], ',')) {
            $aResult = ACE::Str2Array($xVal[0]);
        } else {
            $aResult = ACE::Str2Array($xVal);
        }
        return $aResult;
    }

    protected function _inPath($aPaths)
    {
        // пути типа 'action/event' приводим к виду 'action/event/*'
        foreach($aPaths as $nKey => $sPath) {
            if (!in_array(substr($sPath, -1), array('/', '*'))) {
                $aPaths[$nKey] = $sPath . '/*';
            }
        }
        return ACE::InPath($this->sCurentPath, $aPaths);
    }

    protected function _checkPath($xConfigParam, $bDefault = true)
    {
        $aPaths = $this->_str2Array($xConfigParam);
        return (!$aPaths) ? $bDefault : $this->_inPath($aPaths);
    }

    protected function _loadConfig()
    {
        $this->aConfig = HelperPlugin::GetConfig();
        if (isset($this->aConfig['blocks']) AND !isset($this->aConfig['widgets']))
            $this->aConfig['widgets'] = $this->aConfig['blocks'];
    }

    /**
     * Инициализация модуля
     */
    public function Init()
    {
        $this->_loadConfig();

        Config::Set($this->sPlugin . '.saved.plugin.path.skin', Config::Get('plugin.path.skin'));
        Config::Set('plugin.path.skin', HelperPlugin::GetWebPluginSkin());
        $this->sCurentPath = ACE::CurrentRoute();
        if ($this->sCurentPath == Config::Get('router.config.action_default') . '/') {
            $this->sCurentPath = '/';
        }
    }

    public function InitBlocks()
    {
        return $this->InitWidgets();
    }

    public function InitWidgets()
    {
        // проверяем сброс блоков
        if (isset($this->aConfig['clear'])) {
            if (is_array($this->aConfig['clear'])) {
                // 'include' - 'on'
                if (isset($this->aConfig['clear']['include']) AND !isset($this->aConfig['clear']['on'])) {
                    $this->aConfig['clear']['on'] = $this->aConfig['clear']['include'];
                    unset($this->aConfig['clear']['include']);
                }
                // 'exclude' - 'off'
                if (isset($this->aConfig['clear']['exclude']) AND !isset($this->aConfig['clear']['off'])) {
                    $this->aConfig['clear']['off'] = $this->aConfig['clear']['exclude'];
                    unset($this->aConfig['clear']['exclude']);
                }

                if (!isset($this->aConfig['clear']['on']) AND isset($this->aConfig['clear']['off'])) {
                    $this->bClearBlocks = true;
                } else {
                    $this->bClearBlocks = false;
                }
                if (isset($this->aConfig['clear']['on'])) {
                    $this->bClearBlocks = $this->_checkPath($this->aConfig['clear']['on'], $this->bClearBlocks);
                }
                if (isset($this->aConfig['clear']['off'])) {
                    $this->bClearBlocks = ($this->bClearBlocks AND !$this->_checkPath($this->aConfig['clear']['off'], false));
                }
            } else {
                $this->bClearBlocks = $this->aConfig['clear'];
            }
        }

        if (!isset($this->aConfig['widgets'])) return;

        $aActivePlugins = $this->Plugin_GetActivePlugins();
        foreach ($this->aConfig['widgets'] as $aWidget) {

            /** @var PluginAcewidgetmanager_ModuleWidget_EntityWidget $oWidget */
            $oWidget = Engine::GetEntity('PluginAcewidgetmanager_ModuleWidget_EntityWidget', $aWidget);

            if ($oWidget->GetPluginName() AND !in_array($oWidget->GetPluginName(), $aActivePlugins)) {
                $bDisplay = false;
            } else {
                $bDisplay = $oWidget->isDisplay();
            }
            if ($bDisplay) {
                if ($this->_checkPath($oWidget->GetInclude(), true) AND !$this->_checkPath($oWidget->GetExclude(), false)) {
                    $aCustomWidget['block'] = $aCustomWidget['name'] = $oWidget->GetName();
                    $aCustomWidget['group'] = $oWidget->GetGroup();
                    $aCustomWidget['priority'] = $oWidget->GetPriority();
                    $aCustomWidget['params'] = $oWidget->GetParams();

                    $sFileConfig = Config::Get('path.root.server') . '/config/blocks/' . $oWidget->GetBlockName() . '/config.php';
                    if (file_exists($sFileConfig)) {
                        $aCustomWidget['config'] = $sFileConfig;
                    }
                    else {
                        $aCustomWidget['config'] = '';
                    }

                    if (isset($aWidget['js'])) {
                        if (substr($aWidget['js'], 0, 4) == '___/') $aWidget['js'] = Config::Get('plugin.path.skin') . substr($aWidget['js'], 3);
                        $this->Viewer_AppendScript($aWidget['js']);
                    }

                    $this->aWidgets[] = $aCustomWidget;
                }
            }
        }
    }

    public function Shutdown()
    {
        if ($this->bClearBlocks) {
            $this->Viewer_ClearBlocksAll();
        }
        foreach ($this->aWidgets as $aWidget) {
            if ($aWidget['config']) {
                include_once ($aWidget['config']);
            }
            $this->Viewer_AddBlock($aWidget['group'], $aWidget['name'], $aWidget['params'], $aWidget['priority']);
        }
        $this->Viewer_VarAssign();
        Config::Set('plugin.path.skin', Config::Get($this->sPlugin . '.saved.plugin.path.skin'));
    }

}

// EOF