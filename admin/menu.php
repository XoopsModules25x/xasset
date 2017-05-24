<?php
defined("XOOPS_ROOT_PATH") or die("XOOPS root path not defined");

$path = dirname(dirname(dirname(dirname(__FILE__))));
include_once $path . '/mainfile.php';

$dirname         = basename(dirname(dirname(__FILE__)));
$module_handler  = xoops_gethandler('module');
$module          = $module_handler->getByDirname($dirname);
$pathIcon32      = $module->getInfo('icons32');
$pathModuleAdmin = $module->getInfo('dirmoduleadmin');
$pathLanguage    = $path . $pathModuleAdmin;

if (!file_exists($fileinc = $pathLanguage . '/language/' . $GLOBALS['xoopsConfig']['language'] . '/' . 'main.php')) {
    $fileinc = $pathLanguage . '/language/english/main.php';
}

include_once $fileinc;

$adminmenu = array();
$i=0;
$adminmenu[$i]["title"] = _AM_MODULEADMIN_HOME;
$adminmenu[$i]['link'] = "admin/index.php";
$adminmenu[$i]["icon"]  = $pathIcon32 . '/home.png';
//$i++;
//$adminmenu[$i]['title'] = _AM_MODULEADMIN_HOME;
//$adminmenu[$i]['link'] = "admin/main.php";
//$adminmenu[$i]["icon"]  = $pathIcon32 . '/manage.png';

$i++;
$adminmenu[$i]['title']  = _MI_XASSET_MENU_MANAGE_APPLICATIONS;
$adminmenu[$i]['link']   = 'admin/main.php?op=manageApplications';
$adminmenu[$i]["icon"]  = $pathIcon32 . '/exec.png';
$i++;
$adminmenu[$i]['title']  = _MI_XASSET_MENU_MANAGE_LICENSES;
$adminmenu[$i]['link']   = "admin/main.php?op=manageLicenses";
$adminmenu[$i]["icon"]  = $pathIcon32 . '/folder_txt.png';
$i++;
$adminmenu[$i]['title']  = _MI_XASSET_MENU_MANAGE_LINKS;
$adminmenu[$i]['link']   = "admin/main.php?op=manageLinks";
$adminmenu[$i]["icon"]  = $pathIcon32 . '/addlink.png';
$i++;
$adminmenu[$i]['title']  = _MI_XASSET_MENU_MANAGE_PACKAGES;
$adminmenu[$i]['link']   = "admin/main.php?op=managePackages";
$adminmenu[$i]["icon"]  = $pathIcon32 . '/block.png';
$i++;
$adminmenu[$i]['title']  = _MI_XASSET_MENU_MANAGE_STATS;
$adminmenu[$i]['link']   = "admin/main.php?op=viewDownloadStats";
$adminmenu[$i]["icon"]  = $pathIcon32 . '/stats.png';
$i++;
$adminmenu[$i]['title']  = _MI_XASSET_MENU_MANAGE_REGIONS;
$adminmenu[$i]['link']   = "admin/main.php?op=manageRegion";
$adminmenu[$i]["icon"]  = $pathIcon32 . '/groupmod.png';
$i++;
$adminmenu[$i]['title']  = _MI_XASSET_MENU_MANAGE_COUNTRIES;
$adminmenu[$i]['link']   = "admin/main.php?op=manageCountries";
$adminmenu[$i]["icon"]  = $pathIcon32 . '/languages.png';
$i++;
$adminmenu[$i]['title']  = _MI_XASSET_MENU_MANAGE_ZONES;
$adminmenu[$i]['link']   = "admin/main.php?op=manageZones";
$adminmenu[$i]["icon"]  = $pathIcon32 . '/globe.png';
$i++;
$adminmenu[$i]['title']  = _MI_XASSET_MENU_MANAGE_TAXES;
$adminmenu[$i]['link']   = "admin/main.php?op=manageTaxes";
$adminmenu[$i]["icon"]  = $pathIcon32 . '/calculator.png';
$i++;
$adminmenu[$i]['title']  = _MI_XASSET_MENU_MANAGE_CURRENCIES;
$adminmenu[$i]['link']   = "admin/main.php?op=manageCurrencies";
$adminmenu[$i]["icon"]  = $pathIcon32 . '/cash_stack.png';
$i++;
$adminmenu[$i]['title']  = _MI_XASSET_MENU_MANAGE_GATEWAYS;
$adminmenu[$i]['link']   = "admin/main.php?op=manageGateways";
$adminmenu[$i]["icon"]  = $pathIcon32 . '/delivery.png';
$i++;
$adminmenu[$i]['title']  = _MI_XASSET_MENU_MANAGE_ORDERS;
$adminmenu[$i]['link']   = "admin/main.php?op=orderTracking";
$adminmenu[$i]["icon"]  = $pathIcon32 . '/cart_add.png';
$i++;
$adminmenu[$i]['title']  = _MI_XASSET_MENU_MANAGE_GATE_LOGS;
$adminmenu[$i]['link']   = "admin/main.php?op=gatewayLogs";
$adminmenu[$i]["icon"]  = $pathIcon32 . '/index.png';
$i++;
$adminmenu[$i]['title']  = _MI_XASSET_MENU_MANAGE_MEMBERSHIP;
$adminmenu[$i]['link']   = "admin/main.php?op=membership";
$adminmenu[$i]["icon"]  = $pathIcon32 . '/identity.png';
$i++;
$adminmenu[$i]['title'] = _AM_MODULEADMIN_ABOUT;
$adminmenu[$i]["link"]  = "admin/about.php";
$adminmenu[$i]["icon"]  = $pathIcon32 . '/about.png';
