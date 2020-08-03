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
 * return the field names and correct values for joining on hitcount table
 * example : SELECT ..., $moduleid, $itemid, $hits,...
 *           FROM ...
 *           LEFT JOIN $table
 *               ON $field = <name of itemid field>
 *           WHERE ...
 *               AND $hits > 1000
 *               AND $where
 *
 * @param $args['modname'] name of the module you want items from, or
 * @param $args['modid'] ID of the module you want items from
 * @param $args['itemtype'] item type (optional) or array of itemtypes
 * @param $args['itemids'] optional array of itemids that we are selecting on
 * @return array('table' => '_hitcount',
 *               'field' => '_hitcount.xar_itemid',
 *               'where' => '_hitcount.xar_itemid IN (...)
 *                           AND _hitcount.xar_moduleid = 123',
 *               'moduleid'  => '_hitcount.xar_moduleid',
 *               ...
 *               'hits'  => '_hitcount.xar_hits')
 * @todo MichelV Remove hardcoded table prefix xar_
 */
function hitcount_userapi_leftjoin($args)
{
    // Get arguments from argument array
    extract($args);

    // Optional argument
    if (!isset($modname)) {
        $modname = '';
    } else {
        $modid = xarModGetIDFromName($modname);
    }
    if (!isset($modid)) {
        $modid = '';
    }
    if (!isset($itemids)) {
        $itemids = array();
    }

    // Security check
    if (count($itemids) > 0) {
        foreach ($itemids as $itemid) {
            if(!xarSecurityCheck('ViewHitcountItems',1,'Item',"$modname:All:$itemid")) return;
        }
    } else {
        if(!xarSecurityCheck('ViewHitcountItems',1,'Item',"$modname:All:All")) return;
    }

    // Table definition
    $xartable = xarDBGetTables();
    $dbconn = xarDBGetConn();
    $userstable = $xartable['hitcount'];

    $leftjoin = array();

    // Specify LEFT JOIN ... ON ... [WHERE ...] parts
    $leftjoin['table'] = $xartable['hitcount'];
    $leftjoin['field'] = '';
    if (!empty($modid)) {
        $leftjoin['field'] .= $xartable['hitcount'] . '.xar_moduleid = ' . $modid;
        $leftjoin['field'] .= ' AND ';
    }
    if (isset($itemtype)) { // could be 0 (= most likely)
        if (is_numeric($itemtype)) {
            $leftjoin['field'] .= $xartable['hitcount'] . '.xar_itemtype = ' . $itemtype;
            $leftjoin['field'] .= ' AND ';
        } elseif (is_array($itemtype) && count($itemtype) > 0) {
            $seentype = array();
            foreach ($itemtype as $id) {
                if (empty($id) || !is_numeric($id)) continue;
                $seentype[$id] = 1;
            }
            if (count($seentype) == 1) {
                $itemtypes = array_keys($seentype);
                $leftjoin['field'] .= $xartable['hitcount'] . '.xar_itemtype = ' . $itemtypes[0];
                $leftjoin['field'] .= ' AND ';
            } elseif (count($seentype) > 1) {
                $itemtypes = join(', ', array_keys($seentype));
                $leftjoin['field'] .= $xartable['hitcount'] . '.xar_itemtype IN (' . $itemtypes . ')';
                $leftjoin['field'] .= ' AND ';
            }
        }
    }
    $leftjoin['field'] .= $xartable['hitcount'] . '.xar_itemid';

    if (count($itemids) > 0) {
        $allids = join(', ', $itemids);
        $leftjoin['where'] = $xartable['hitcount'] . '.xar_itemid IN (' . $allids . ')';
/*
        if (!empty($modid)) {
            $leftjoin['where'] .= ' AND ' .
                                  $xartable['hitcount'] . '.xar_moduleid = ' .
                                  $modid;
        }
*/
    } else {
/*
        if (!empty($modid)) {
            $leftjoin['where'] = $xartable['hitcount'] . '.xar_moduleid = ' .
                                 $modid;
        } else {
            $leftjoin['where'] = '';
        }
*/
        $leftjoin['where'] = '';
    }

    // Add available columns in the hitcount table
    $columns = array('moduleid','itemtype','itemid','hits');
    foreach ($columns as $column) {
        $leftjoin[$column] = $xartable['hitcount'] . '.xar_' . $column;
    }

    return $leftjoin;
}

?>
