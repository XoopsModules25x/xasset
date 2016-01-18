<?php
//need to catch processOptionForm as this needs special processing
if ( isset($_GET['op']) && ($_GET['op'] == 'processOptionForm') && (isset($_GET['ssl'])) && (isset($_GET['url']))) {
  $xoopsOption['nocommon'] = 1;
  require("../../mainfile.php");
  runkit_constant_redefine('XOOPS_URL',base64_decode(urldecode($_GET['url'])));
  unset($xoopsOption['nocommon']);
  require XOOPS_ROOT_PATH."/include/common.php";
} else {
  require("../../mainfile.php");
}

require_once('include/images.php');
require_once('include/functions.php');

define('XASSET_BASE_PATH', XOOPS_ROOT_PATH.'/modules/xasset');
define('XASSET_CLASS_PATH', XASSET_BASE_PATH.'/class');

$xassetCSS = XOOPS_URL.'/modules/xasset/styles/xasset.css';
$xasset_module_header = '<link rel="stylesheet" type="text/css" media="all" href="'.$xassetCSS.'" /><!--[if gte IE 5.5000]><script src="iepngfix.js" language="JavaScript" type="text/javascript"></script><![endif]-->';
