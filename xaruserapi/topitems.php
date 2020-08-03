<?php
/**
 * Hitcount
 *
 * @package modules
 * @copyright (C) 2002-2005 The Digital Development Foundation
 * @license GPL {@link http://www.gnu.org/licenses/gpl.html}

 * @subpackage Xarigami Hitcount Module
 * @copyright (C) 2009-2010 2skies.com
 * @link http://xarigami.com/project/hitcount
 * @author Hitcount Module Development Team
 */

/**
 * get the list of items with top N hits for a module
 *
 * @param $args['modname'] name of the module you want items from
 * @param $args['itemtype'] item type of the items (only 1 type supported per call)
 * @param $args['numitems'] number of items to return
 * @param $args['startnum'] start at this number (1-based)
 * @return array Array('itemid' => $itemid, 'hits' => $hits)
 */
function hitcount_userapi_topitems($args)
{
    // Get arguments from argument array
    extract($args);

    // Argument check
    if (!isset($modname)) {
        xarSessionSetVar('errormsg', _MODARGSERROR);
        return;
    }
    $modid = xarModGetIDFromName($modname);
    if (empty($modid)) {
        xarSessionSetVar('errormsg', _MODARGSERROR);
        return;
    }
    if (empty($itemtype)) {
        $itemtype = 0;
    }

    // Security check
    if(!xarSecurityCheck('ViewHitcountItems',1,'Item',"$modname:$itemtype:All")) return;

    // Database information
    $dbconn = xarDBGetConn();
    $xartable = xarDBGetTables();
    $hitcounttable = $xartable['hitcount'];

    // Get items
    $query = "SELECT xar_itemid, xar_hits
            FROM $hitcounttable
            WHERE xar_moduleid = ?
              AND xar_itemtype = ?
            ORDER BY xar_hits DESC";
    $bindvars = array((int)$modid, (int)$itemtype);

    if (!isset($numitems) || !is_numeric($numitems)) {
        $numitems = 10;
    }
    if (!isset($startnum) || !is_numeric($startnum)) {
        $startnum = 1;
    }

    //$result = $dbconn->Execute($query);
    $result = $dbconn->SelectLimit($query, $numitems, $startnum - 1, $bindvars);
    if (!$result) return;

    $topitems = array();
    while (!$result->EOF) {
        list($id,$hits) = $result->fields;
        $topitems[] = array('itemid' => $id, 'hits' => $hits);
        $result->MoveNext();
    }
    $result->close();

    return $topitems;
}

?>