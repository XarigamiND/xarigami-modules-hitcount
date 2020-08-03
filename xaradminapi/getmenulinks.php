<?php
/**
 * Hitcount
 *
 * @package modules
 * @copyright (C) 2002-2005 The Digital Development Foundation
 * @license GPL {@link http://www.gnu.org/licenses/gpl.html}
 *
 * @subpackage Xarigami Hitcount Module
 * @copyright (C) 2009 2skies.com 
 * @link http://xarigami.com/project/hitcount
 * @author Hitcount Module Development Team
 */
/**
 * utility function pass individual menu items to the admin panels
 *
 * @author the Hitcount module development team
 * @return array Array containing the menulinks for the main menu items.
 */
function hitcount_adminapi_getmenulinks()
{
    $menulinks = array();

    // Security Check
    if (xarSecurityCheck('AdminHitcount', 0)) {
        $menulinks[] = Array('url'   => xarModURL('hitcount','admin','view'),
                              'title' => xarML('View hitcount statistics per module'),
                              'label' => xarML('View Statistics'),
                              'active'=> array('view','delete'));
        $menulinks[] = Array('url' => xarModURL('hitcount','admin','modifyconfig'),
                             'title' => xarML('Modify the configuration for the Hitcount module'),
                             'label' => xarML('Modify Config'),
                             'active' => array('modifyconfig'));
    }

    return $menulinks;
}
?>
