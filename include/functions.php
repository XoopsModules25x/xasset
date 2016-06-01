<?php

///////////////////////////////////////////////////
function parseConstants($body, $moduleName) {
  global $xoopsConfig;   
  //
  $hModule =& xoops_gethandler('module');
  $module =& $hModule->getByDirname($moduleName);
  //
  $tags = array();
  $tags['X_MODULE'] = $module->getVar('name');
  $tags['X_SITEURL'] = XOOPS_URL;
  $tags['X_DOCROOT'] = XOOPS_URL .'/modules/' . $module->getVar('dirname') .'/files/';
  $tags['X_SITENAME'] = $xoopsConfig['sitename'];
  $tags['X_ADMINMAIL'] = $xoopsConfig['adminmail'];
  $tags['X_MODULE_URL'] = XOOPS_URL .'/modules/' . $module->getVar('dirname') .'/';
  //
  foreach($tags as $k=>$v){
      $body = preg_replace('/{'.$k.'}/', $v, $body);
  }
  return $body;
}
///////////////////////////////////////////////////
function getGroupClients() {
  global $xoopsOption;
  //
  $hConfig =& xoops_getmodulehandler('config','xasset');
  $gid     = $hConfig->getGroup();     
  //
  $hMember =& xoops_gethandler('member');
  $users   = $hMember->getUsersByGroup($gid,true);
  //
  $ar = array();
  foreach($users as $user) {
    $ar[$user->getVar('uid')] = $user->getVar('name');
  }     
  //
  return $ar;
}
//////////////////////////////////////////////////////////
function getDateField($name, $date = null) {
  if (!isset($date)) {
    $date = time();
  }
	include_once XOOPS_ROOT_PATH . '/class/xoopsformloader.php';
	//
	if (class_exists('XoopsFormCalendar')) {
  	$cal = new XoopsFormCalendar($name, $name, $date, array(), array('value'=>date("Y-m-d",$date)));
    return $cal->render();
	} else {
//    include_once XOOPS_ROOT_PATH.'/include/calendarjs.php';
    include_once XOOPS_ROOT_PATH . '/modules/xasset/include/calendarjs.php';
    return "<input type='text' name='$name' id='$name' size='11' maxlength='11' value='".date("Y-m-d", $date)."' /><input type='reset' value=' ... ' onclick='return showCalendar(\"".$name."\");'>";	
  }
}
/////////////////////////////////////////
function keyMatches($id,$key,$weight,$error) {
  global $xoopsOption, $xoopsTpl, $xoopsConfig, $xoopsUser, $xoopsLogger, $xoopsUserIsAdmin, $xasset_module_header;
  //
  $crypt = new xassetCrypt();
  //
  if ($crypt->keyMatches($id+$weight,$key)) {
    return true; }
  else {
    $xoopsOption['template_main'] = 'xasset_error.html';
    require_once(XOOPS_ROOT_PATH . "/header.php");
    $xoopsTpl->assign('xasset_error',$error);
    include(XOOPS_ROOT_PATH . "/footer.php");
    return false;
  }
}
///////////////////////////////////////////////
function getKey($id, $weight) {
  $crypt = new xassetCrypt();
  return $crypt->cryptValue($id,$weight);
}
/////////////////////////////////////////
function insertHeaderCountriesJavaScript() {
  $hCommon =& xoops_getmodulehandler('common','xasset');
  return $hCommon->insertHeaderCountriesJavaScript();
}
/////////////////////////////////////////
function insertHeaderCountriesJavaScriptNoAllZones() {
  $hCommon =& xoops_getmodulehandler('common','xasset');
  return $hCommon->insertHeaderCountriesJavaScriptNoAllZones();
}



?>
