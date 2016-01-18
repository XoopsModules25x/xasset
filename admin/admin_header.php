<?php
/*
 * You may not change or alter any portion of this comment or credits
 * of supporting developers from this source code or any supporting source code
 * which is considered copyrighted (c) material of the original comment or credit authors.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.
 */

/**
 * @copyright    The XOOPS Project http://sourceforge.net/projects/xoops/
 * @license      GNU GPL 2 or later (http://www.gnu.org/licenses/gpl-2.0.html)
 * @package
 * @since
 * @author     XOOPS Development Team
 * @version    $Id $
 */

$path = dirname(dirname(dirname(dirname(__FILE__))));
include_once $path . '/mainfile.php';
include_once $path . '/include/cp_functions.php';
require_once $path . '/include/cp_header.php';

global $xoopsModule;

$thisModuleDir = $GLOBALS['xoopsModule']->getVar('dirname');

//if functions.php file exist
//require_once dirname(dirname(__FILE__)) . '/include/functions.php';

// Load language files
xoops_loadLanguage('admin', $thisModuleDir);
xoops_loadLanguage('modinfo', $thisModuleDir);
xoops_loadLanguage('main', $thisModuleDir);

$pathIcon16 = '../'.$xoopsModule->getInfo('icons16');
$pathIcon32 = '../'.$xoopsModule->getInfo('icons32');
$pathModuleAdmin = $xoopsModule->getInfo('dirmoduleadmin');

include_once $GLOBALS['xoops']->path($pathModuleAdmin.'/moduleadmin.php');

define('XASSET_BASE_PATH', XOOPS_ROOT_PATH.'/modules/xasset');
define('XASSET_CLASS_PATH', XASSET_BASE_PATH.'/class');
define('XASSET_ADMIN_PATH', XASSET_BASE_PATH.'/admin');

require(XASSET_BASE_PATH.'/admin/admin_buttons.php');
require_once(XASSET_BASE_PATH.'/include/functions.php');
require_once(XOOPS_ROOT_PATH.'/class/template.php');

require_once('../include/images.php');

global $xoopsModule;
$module_id = $xoopsModule->getVar('mid');

$myts = &MyTextSanitizer::getInstance();

if ( !isset($xoopsTpl) ) {
    $xoopsTpl = new XoopsTpl(); }
