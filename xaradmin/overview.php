<?php
/**
 * Overview displays standard Overview page
 *
 * @package modules
 * @copyright (C) 2002-2006 The Digital Development Foundation
 * @license GPL {@link http://www.gnu.org/licenses/gpl.html}
 *
 * @subpackage Xarigami Hitcount Module
 * @copyright (C) 2009 2skies.com 
 * @link http://xarigami.com/project/hitcount
 * @author Hitcount Module Development Team
 */
/**
 * Overview function that displays the standard Overview page
 *
 * This function shows the overview template, currently admin-main.xd.
 * The template contains overview and help texts
 *
 * @author the Hitcount module development team
 * @return array xarTplModule with $data containing template data
 * @since 4 March 2006
 */
function hitcount_admin_overview()
{
    /* Security Check */
    if(!xarSecurityCheck('AdminHitcount',0)) return;

    $data=array();

    /* if there is a separate overview function return data to it
     * else just call the main function that displays the overview
     */
    $data['menulinks'] = xarModAPIFunc('hitcount','admin','getmenulinks');
    return xarTplModule('hitcount', 'admin', 'main', $data,'main');
}

?>
