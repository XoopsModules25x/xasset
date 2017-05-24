<?php

require_once("../../../mainfile.php");

function onSampleClick($packageID, $packageKey) {
  global $xoopsUser;
  //
  $hPackage =& xoops_getmodulehandler('package','xasset');
  $hCommon  =& xoops_getmodulehandler('common','xasset');
  //
  $objResponse = new xajaxResponse();
  //
  if ($hCommon->keyMatches($packageID, $packageKey, $hPackage->_weight)) {
    $oPackage =& $hPackage->get($packageID);
    //
    $objResponse->addAssign("movie_player","innerHTML", '');
    $objResponse->addScriptCall('renderPlayer',$packageID,$oPackage->fileSize(),$xoopsUser ? $hCommon->pspEncrypt($xoopsUser->uid()) : $hCommon->pspEncrypt(0));
  } else {
    $objResponse->addAssign("movie_player","innerHTML", '');
  }
  //
  return $objResponse;
}

$hAjax =& xoops_getmodulehandler('ajax','xasset');
$oAjax =& $hAjax->create();
//
$oAjax->registerFunction('onSampleClick');
$oAjax->processRequests();
