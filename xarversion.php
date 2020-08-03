<?php
/**
 * Hitcount
 *
 * @package modules
 * @copyright (C) 2002-2008 The Digital Development Foundation
 * @license GPL {@link http://www.gnu.org/licenses/gpl.html}
 *
 * @subpackage Xarigami Hitcount Module
 * @copyright (C) 2009-2011 2skies.com
 * @link http://xarigami.com/project/hitcount
 * @author Hitcount Module Development Team
 */

$modversion['name'] = 'hitcount';
$modversion['directory'] = 'hitcount';
$modversion['id'] = '177';
$modversion['version'] = '1.2.3';
$modversion['displayname']    = 'Hitcount';
$modversion['description'] = 'Count displays of module items';
$modversion['credits'] = 'xardocs/credits.txt';
$modversion['help'] = 'xardocs/help.txt';
$modversion['changelog'] = 'xardocs/changelog.txt';
$modversion['homepage'] = 'http://xarigami.com/project/hitcount';
$modversion['license'] = 'xardocs/license.txt';
$modversion['coding'] = 'xardocs/coding.txt';
$modversion['official'] = 1;
$modversion['author'] = 'Jim McDonald';
$modversion['contact'] = 'http://www.mcdee.net/';
$modversion['admin'] = 1;
$modversion['user'] = 1;
$modversion['class'] = 'Utility';
$modversion['category'] = 'Utilities';
$modversion['dependencyinfo']   = array(
                                    0 => array(
                                            'name' => 'core',
                                            'version_ge' => '1.4.0'
                                         )
                                );
if (false) { //Load and translate once
    xarML('Hitcount');
    xarML('Page Hit Count');
}
?>