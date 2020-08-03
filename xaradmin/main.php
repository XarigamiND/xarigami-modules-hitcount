<?php
/**
 * Hitcount
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
 * Add a standard screen upon entry to the module.
 * @return bool true on success of redirect
 */
function hitcount_admin_main()
{
    // Security Check
    if(!xarSecurityCheck('AdminHitcount')) return;
    xarResponseRedirect(xarModURL('hitcount', 'admin', 'modifyconfig'));
    // success
    return true;
}

?>