<?php
/**
 * Hitcount
 *
 * @package modules
 * @copyright (C) 2002-2005 The Digital Development Foundation
 * @license GPL {@link http://www.gnu.org/licenses/gpl.html}

 * @subpackage Xarigami Hitcount Module
 * @copyright (C) 2009-2011 2skies.com
 * @link http://xarigami.com/project/hitcount
 * @author Hitcount Module Development Team
 */
/**
 * update a hitcount item - used by display hook hitcount_user_display
 *
 * @param $args['modname'] name of the calling module (see _user_display)
 * @param $args['itemtype'] optional item type for the item (or in extrainfo)
 * @param $args['objectid'] ID of the object
 * @param $args['extrainfo'] may contain itemtype
 * @param $args['hits'] (optional) hit count for the item
 * @return int The new hitcount for this item, or void on failure
 * @throws BAD_PARAM, NO_PERMISSION, DATABASE_ERROR
 */
function hitcount_adminapi_update($args)
{
    extract($args);

    if (!isset($objectid) || !is_numeric($objectid)) {
        $msg = xarML('Invalid #(1) for #(2) function #(3)() in module #(4)',
                    'object ID', 'admin', 'update', 'Hitcount');
        return false;
    }

    // When called via hooks, modname will be empty, but we get it from the
    // extrainfo or from the current module
    if (empty($modname) || !is_string($modname)) {
        if (isset($extrainfo) && is_array($extrainfo) &&
            isset($extrainfo['module']) && is_string($extrainfo['module'])) {
            $modname = $extrainfo['module'];
        } else {
            $modname = xarModGetName();
        }
    }
    $modid = xarModGetIDFromName($modname);
    if (empty($modid)) {
        $msg = xarML('Invalid #(1) for #(2) function #(3)() in module #(4)',
                    'module name', 'admin', 'update', 'Hitcount');
         return false;
    }
    if (!isset($itemtype) || !is_numeric($itemtype)) {
         if (isset($extrainfo) && is_array($extrainfo) &&
             isset($extrainfo['itemtype']) && is_numeric($extrainfo['itemtype'])) {
             $itemtype = $extrainfo['itemtype'];
         } else {
             $itemtype = 0;
         }
    }

// TODO: re-evaluate this for hook calls !!
    // Security check - important to do this as early on as possible to
    // avoid potential security holes or just too much wasted processing
    if(!xarSecurityCheck('ReadHitcountItem',1,'Item',"$modname:$itemtype:$objectid")) return;

    if (!xarModAPILoad('hitcount', 'user')) return;

    // get current hit count
    $oldhits = xarModAPIFunc('hitcount',
                            'user',
                            'get',
                            array('objectid' => $objectid,
                                  'itemtype' => $itemtype,
                                  'modname' => $modname));

    // create the item if necessary
    if (!isset($oldhits)) {
        $hcid = xarModAPIFunc('hitcount','admin','create',
                             array('objectid' => $objectid,
                                   'itemtype' => $itemtype,
                                   'modname' => $modname));
        if (!isset($hcid)) {
            return; // throw back whatever it was that failed
        }
    }

    $dbconn = xarDBGetConn();
    $xartable = xarDBGetTables();
    $hitcounttable = $xartable['hitcount'];

    // set to the new hit count
    $bindvars = array();
    if (!empty($hits) && is_numeric($hits)) {
        $bhits = $hits;
    } else {
        $bhits = 'xar_hits + 1';
        $hits = $oldhits + 1;
    }
    $query = "UPDATE $hitcounttable
              SET xar_hits = $bhits
              WHERE xar_moduleid = ?
              AND xar_itemtype = ?
              AND xar_itemid = ?";
    $bindvars = array((int)$modid, (int)$itemtype, (int)$objectid);
    $result = $dbconn->Execute($query,$bindvars);
    if (!$result) return;

    // Return the new hitcount (give or take a few other hits in the meantime)
    return $hits;
}

?>
