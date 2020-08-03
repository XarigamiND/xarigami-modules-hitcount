<?php
/**
 * Hitcount
 *
 * @package modules
 * @copyright (C) 2002-2005 The Digital Development Foundation
 * @license GPL {@link http://www.gnu.org/licenses/gpl.html}
 *
 * @subpackage Xarigami Hitcount Module
 * @copyright (C) 2009-2011 2skies.com
 * @link http://xarigami.com/project/hitcount
 * @author Hitcount Module Development Team
 */
/**
 * modify configuration
 * @param string phase
 * @return array
 */
function hitcount_admin_modifyconfig()
{
    // Security Check
    if (!xarSecurityCheck('AdminHitcount')) return;

    if (!xarVarFetch('phase', 'str:1:100', $phase, 'modify', XARVAR_NOT_REQUIRED)) return;

    $data['menulinks'] = xarModAPIFunc('hitcount','admin','getmenulinks');
    switch (strtolower($phase)) {
        case 'modify':
        default:

            // Quick Data Array
            $data['authid'] = xarSecGenAuthKey();
            $data['numitems'] = xarModGetVar('hitcount','numitems');
            if (empty($data['numitems'])) {
                $data['numitems'] = 10;
            }
            $data['numstats'] = xarModGetVar('hitcount','numstats');
            if (empty($data['numstats'])) {
                $data['numstats'] = 100;
            }
            $data['showtitle'] = xarModGetVar('hitcount','showtitle');
            if (!empty($data['showtitle'])) {
                $data['showtitle'] = 1;
            }
            $data['countadmin'] = xarModGetVar('hitcount', 'countadmin');
            break;

        case 'update':
            if (!xarVarFetch('countadmin', 'checkbox', $countadmin, false, XARVAR_NOT_REQUIRED)) return;
            if (!xarVarFetch('numitems', 'int', $numitems, 10, XARVAR_NOT_REQUIRED)) return;
            if (!xarVarFetch('numstats', 'int', $numstats, 100, XARVAR_NOT_REQUIRED)) return;
            if (!xarVarFetch('showtitle', 'checkbox', $showtitle, false, XARVAR_NOT_REQUIRED)) return;
            // Confirm authorisation code
            if (!xarSecConfirmAuthKey()) return;
            // Update module variables
            xarModSetVar('hitcount', 'countadmin', $countadmin);
            xarModSetVar('hitcount', 'numitems', $numitems);
            xarModSetVar('hitcount', 'numstats', $numstats);
            xarModSetVar('hitcount', 'showtitle', $showtitle);
            $msg = xarML('Configuration settings were successfully updated.');
            xarTplSetMessage($msg,'status');
            xarResponseRedirect(xarModURL('hitcount', 'admin', 'modifyconfig'));
            // Return
            return true;

            break;
    }

    return $data;
}

?>
