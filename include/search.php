<?php

function xasset_search($queryarray, $andor, $limit, $offset, $userid){
  $hApp      =& xoops_getmodulehandler('application','xasset');
  $hAppProd  =& xoops_getmodulehandler('applicationProduct','xasset');
  $hCommon   =& xoops_getmodulehandler('common','xasset');
  //
  $aApps  =& $hApp->seachApplication($queryarray, $andor, $limit, $offset, $userid);
  $aProds =& $hAppProd->searchApplicationProduct($queryarray, $andor, $limit, $offset, $userid);
  //first the apps
  $ret = array();
  $i   = 0; 
  if (count($aApps) > 0) {
    foreach($aApps as $key=>$oApp) {
      $ret[$i]['image'] = "images/main.png";
      $ret[$i]['link'] = 'index.php?op=product&id='.$oApp->ID().'&key='.$oApp->getKey();
      $ret[$i]['title'] = $oApp->name();
      $ret[$i]['time'] = null;
      $ret[$i]['uid']  = 0;
      $i++;
    }
  }
  //next the products
  if (count($aProds) > 0) {
    foreach($aProds as $key=>$oProduct) {
      $ret[$i]['image'] = "images/claimOwner.png";
      $ret[$i]['link'] = 'index.php?op=product&id='.$oProduct->applicationID().'&key='.$hCommon->cryptValue($oProduct->applicationID(),$hApp->_weight);
      $ret[$i]['title'] = $oProduct->itemDescription();
      $ret[$i]['time'] = null;
      $ret[$i]['uid']  = 0;
      $i++;
    }
  } 
  //
  return $ret;
}
?>