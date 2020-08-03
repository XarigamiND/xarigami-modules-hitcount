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
 * Delete hit counts of module items
 * @param int modid
 * @param int itemtype
 * @param int itemid
 * @param str confirm When empty the confirmation page is shown
 * @return bool True on success of deletion
 */
function hitcount_admin_delete()
{
    // Security Check
    if(!xarSecurityCheck('AdminHitcount')) return;

    if(!xarVarFetch('modid',    'isset', $modid,     NULL, XARVAR_DONT_SET)) {return;}
    if(!xarVarFetch('itemtype', 'isset', $itemtype,  NULL, XARVAR_DONT_SET)) {return;}
    if(!xarVarFetch('itemid',   'isset', $itemid,    NULL, XARVAR_DONT_SET)) {return;}
    if(!xarVarFetch('confirm',  'str:1:', $confirm, '', XARVAR_NOT_REQUIRED)) return;

    $data = array();
    $data['menulinks'] = xarModAPIFunc('hitcount','admin','getmenulinks');
    // Check for confirmation.
    if (empty($confirm)) {

        $data['modid'] = $modid;
        $data['itemtype'] = $itemtype;
        $data['itemid'] = $itemid;

        $what = '';
        if (!empty($modid)) {
            $modinfo = xarModGetInfo($modid);
            if (empty($itemtype)) {
                $data['modname'] = ucwords($modinfo['displayname']);
            } else {
                // Get the list of all item types for this module (if any)
                $mytypes = xarModAPIFunc($modinfo['name'],'user','getitemtypes',
                                         // don't throw an exception if this function doesn't exist
                                         array(), 0);
                if (isset($mytypes) && !empty($mytypes[$itemtype])) {
                    $data['modname'] = ucwords($modinfo['displayname']) . ' ' . $itemtype . ' - ' . $mytypes[$itemtype]['label'];
                } else {
                    $data['modname'] = ucwords($modinfo['displayname']) . ' ' . $itemtype;
                }
            }
        }
        // Generate a one-time authorisation code for this operation
        $data['authid'] = xarSecGenAuthKey();
        // Return the template variables defined in this function
        return $data;
    }

    if (!xarSecConfirmAuthKey()) return;
    if (!xarModAPIFunc('hitcount','admin','delete',
                       array('modid' => $modid,
                             'itemtype' => $itemtype,
                             'itemid' => $itemid,
                             'confirm' => $confirm))) {
            $msg = xarML('There was a problem trying to delete the hitcount entry and it was not deleted.');
            xarTplSetMessage($msg,'error');

        return;
    } else {
            $msg = xarML('Hitcount entry was successfully deleted.');
            xarTplSetMessage($msg,'status');
    }
    xarResponseRedirect(xarModURL('hitcount', 'admin', 'view'));
    return true;
}

?>
