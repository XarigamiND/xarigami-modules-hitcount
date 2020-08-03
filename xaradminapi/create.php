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
 * create a new hitcount item - hook for ('item','create','API')
 *
 * @param $args['objectid'] ID of the object
 * @param $args['extrainfo'] extra information
 * @param $args['modname'] name of the calling module (not used in hook calls)
 * @param $args['itemtype'] optional item type for the item (not used in hook calls)
 * @param $args['hits'] optional hit count for the item (not used in hook calls)
 * @return int hitcount item ID on success, void on failure
 * @throws BAD_PARAM, NO_PERMISSION, DATABASE_ERROR
 */
function hitcount_adminapi_create($args)
{
    extract($args);

    if (!isset($objectid) || !is_numeric($objectid)) {
        $msg = xarML('Invalid #(1) for #(2) function #(3)() in module #(4)',
                    'object ID', 'admin', 'create', 'Hitcount');
         return false;//don't throw otherwise all the next hooks fail
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
                    'module name', 'admin', 'create', 'Hitcount');
         return false;//don't throw otherwise all the next hooks fail
    }

    if (!isset($itemtype) || !is_numeric($itemtype)) {
         if (isset($extrainfo['itemtype']) && is_numeric($extrainfo['itemtype'])) {
             $itemtype = $extrainfo['itemtype'];
         } else {
             $itemtype = 0;
         }
    }

// TODO: re-evaluate this for hook calls !!
    // Security check - important to do this as early on as possible to
    // avoid potential security holes or just too much wasted processing
    if(!xarSecurityCheck('ReadHitcountItem',1,'Item',"$modname:$itemtype:$objectid")) return;

    $dbconn = xarDBGetConn();
    $xartable = xarDBGetTables();
    $hitcounttable = $xartable['hitcount'];

    // Get a new hitcount ID
    $nextId = $dbconn->GenId($hitcounttable);
    // Create new hitcount
    if (!isset($hits) || !is_numeric($hits)) {
         if (isset($extrainfo['hits']) && is_numeric($extrainfo['hits'])) {
             $hits = $extrainfo['hits'];
         } else {
             $hits = 0;
         }
    }
    $query = "INSERT INTO $hitcounttable(xar_hitcountid,
                                       xar_moduleid,
                                       xar_itemtype,
                                       xar_itemid,
                                       xar_hits)
            VALUES (?,?,?,?,?)";
    $bindvars = array($nextId, $modid, $itemtype, $objectid, $hits);

    $result = $dbconn->Execute($query, $bindvars);
    if (!$result) return;

    $hcid = $dbconn->PO_Insert_ID($hitcounttable, 'xar_hitcountid');

    // hmmm, I think we'll skip calling more hooks here... :-)
    //xarModCallHooks('item', 'create', $hcid, 'hitcountid');

    // Return the extra info with the id of the newly created item
    // (not that this will be of any used when called via hooks, but
    // who knows where else this might be used)
    if (!isset($extrainfo)) {
        $extrainfo = array();
    }
    $extrainfo['hcid'] = $hcid;
    return $extrainfo;
}

?>
