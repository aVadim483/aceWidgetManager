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

class PluginAcewidgetmanager_BlockTemplate extends Block
{

    public function Exec()
    {
        $sBlockContent = $this->GetParam('text');
        $sFileName = $this->GetParam('file');
        if ($sFileName) {
            // if not url and not filepath then file in config/widgets
            if ((strpos($sFileName, '\\') === false) AND (strpos($sFileName, '/') === false)) {
                $aDirs = array_reverse(HelperPlugin::GetPluginConfigPaths());
                foreach ($aDirs as $nKey => $sDir) {
                    $sFile = ACE::FilePath($sDir . '/widgets/' . $sFileName);
                    if (($nKey == sizeof($aDirs)-1) OR is_file($sFile)) {
                        $sFileName = $sFile;
                        break;
                    }
                }
            }
            if (($sText = file_get_contents($sFileName))) {
                $sBlockContent .= $sText;
            }
        }
        $sBlockTitle = $this->GetParam('title');
        $sBlockFooter = $this->GetParam('footer');

        $this->Viewer_Assign('sBlockTitle', $sBlockTitle);
        $this->Viewer_Assign('sBlockContent', $sBlockContent);
        $this->Viewer_Assign('sBlockFooter', $sBlockFooter);
    }
}

// EOF