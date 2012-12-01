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

class PluginAcewidgetmanager_ModuleWidget_EntityWidget extends Entity
{
    protected function _getDataOneAsArray($sKey, $sSeparateChar = ',')
    {
        $xVal = $this->_getDataOne($sKey);
        if (is_null($xVal)) {
            $aResult = array();
        } elseif (is_array($xVal) AND (sizeof($xVal) == 1) AND isset($xVal[0]) AND strpos($xVal[0], $sSeparateChar)) {
            $aResult = ACA::Str2Array($xVal[0], $sSeparateChar);
        } else {
            $aResult = ACE::Str2Array($xVal, $sSeparateChar);
        }
        return $aResult;
    }

    public function GetParams()
    {
        return (array)$this->_getDataOne('params');
    }

    public function GetParam($sKey)
    {
        $aParams = $this->GetParams();
        if (isset($aParams[$sKey])) {
            return $aParams[$sKey];
        } else {
            return null;
        }
    }

    public function GetPluginName()
    {
        return $this->GetParam('plugin');
    }

    public function GetInclude()
    {
        $xResult = $this->_getDataOneAsArray('on');
        if (is_null($xResult)) $xResult = $this->_getDataOneAsArray('include');
        return $xResult;
    }

    public function GetExclude()
    {
        $xResult = $this->_getDataOneAsArray('off');
        if (is_null($xResult)) $xResult = $this->_getDataOneAsArray('exclude');
        return $xResult;
    }

    public function GetName()
    {
        return $this->_getDataOne('name');
    }

    public function isDisplay()
    {
        $bResult = true;
        $xDisplay = $this->GetDisplay();
        if (!is_null($xDisplay)) {
            if (is_array($xDisplay)) {
                foreach ($xDisplay as $sParamName => $sParamValue) {
                    if ($sParamName == 'date_from' AND $sParamValue) {
                        $bResult = $bResult AND (date('Y-m-d H:i:s') >= $sParamValue);
                    } elseif ($sParamName == 'date_upto' AND $sParamValue) {
                        $bResult = $bResult AND (date('Y-m-d H:i:s') <= $sParamValue);
                    }
                }
            } else {
                $bResult = (bool)$xDisplay;
            }
        }
        return (bool)$bResult AND $this->isCondition();
    }

    public function isCondition()
    {
        $bResult = true;
        $sCondition = $this->GetCondition();
        if (is_string($sCondition) AND $sCondition > '') {
            extract($this->GetParams(), EXTR_SKIP);
            $bResult = eval('return ' . $sCondition . ';');
        }
        return (bool)$bResult;
    }

}

// EOF