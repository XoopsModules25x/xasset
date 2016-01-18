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

require_once dirname(dirname(dirname(dirname(__FILE__)))) . '/include/cp_header.php';
include_once dirname(__FILE__) . '/admin_header.php';

xoops_cp_header();

    $indexAdmin = new ModuleAdmin();

//----------------------------------

global $xoopsTpl;
//
$hApp       =& xoops_getmodulehandler('application','xasset');
$hLic       =& xoops_getmodulehandler('license','xasset');
$hPack      =& xoops_getmodulehandler('package','xasset');
$hStat      =& xoops_getmodulehandler('userPackageStats','xasset');
$hLinks     =& xoops_getmodulehandler('link','xasset');
//
$applicationsCount     = $hApp->getAllApplicationsCount();
$licensesCount         = $hLic->getAllLicensesCount();
$filesCount            = $hPack->getAllPackagesCount();
$linksCount            = $hLinks->getAllLinksCount();
$downloadsCount        = $hStat->getAllDownloadStats();
//test SSL connectivity
$fp = fsockopen('ssl://www.paypal.com', 443,$errnum,$errstr,30);
if (!$fp)
  $test = array('pass' => false, 'errnum' => $errnum, 'errstr' => $errstr);
else
  $test = array('pass' => true);

//$statistics = calculateStatistics();

$indexAdmin->addInfoBox(_MI_XASSET_DASHBBOARD);

$indexAdmin->addInfoBoxLine(_MI_XASSET_DASHBBOARD, _MI_XASSET_APPLICATIONS, $hApp->getAllApplicationsCount(), 'Green');
$indexAdmin->addInfoBoxLine(_MI_XASSET_DASHBBOARD, _MI_XASSET_LICENSES, $hLic->getAllLicensesCount(), 'Green');
$indexAdmin->addInfoBoxLine(_MI_XASSET_DASHBBOARD, _MI_XASSET_FILES, $hPack->getAllPackagesCount(), 'Green');
$indexAdmin->addInfoBoxLine(_MI_XASSET_DASHBBOARD, _MI_XASSET_LINKS, $hLinks->getAllLinksCount(), 'Green');
$indexAdmin->addInfoBoxLine(_MI_XASSET_DASHBBOARD, _MI_XASSET_DOWNLOADS, $hStat->getAllDownloadStats(), 'Green');
if ($test) {
$indexAdmin->addInfoBoxLine(_MI_XASSET_DASHBBOARD, '<br />Outgoing SSL Support? Yes', 'Green');
} else {
    $indexAdmin->addInfoBoxLine(_MI_XASSET_DASHBBOARD, '<br />Outgoing SSL Support? Failed with codes errnum: '. $errnum." and errstr: ".$errstr, 'Green');
}

    echo $indexAdmin->addNavigation('index.php');
    echo $indexAdmin->renderIndex();

include "admin_footer.php";
