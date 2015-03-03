<?php

require_once('baseGateway.php');

class bill extends baseGateway {
  //
  function bill() {
    //call code first
    $this->code        = 'bill';
    $this->_version     = '93';
    //inherited
    parent::baseGateway();
    //
    $this->shortDesc   = 'Bill Gateway';
    if (defined('BILL_GATEWAY_DESCRIPTION')) {
      $this->description = BILL_GATEWAY_DESCRIPTION;
    }
    $this->postURL = XOOPS_URL.'/modules/xasset/return.php';
    //
    $this->_validates   = false;
  }
  /////////////////////////////////////////////////////
  function keys(){
    $ary = array( 'BILL_ENABLED','BILL_GATEWAY_DESCRIPTION');

    return $ary;
  }
  /////////////////////////////////////////
  function preprocess() {
    return strlen(BILL_GATEWAY_INSTRUCTIONS) > 0;
  }
  /////////////////////////////////////////
  function extraFields() {
    //preprocess for any tags
    $body = BILL_GATEWAY_INSTRUCTIONS;
    if (preg_match('/{ORDERID}/',$body)) {
      $body = preg_replace('/{ORDERID}/',$_SESSION['orderID'],$body);
    }
    $this->addOption('EXTRA','box','', $body);
    //also email user
//    $xoopsMailer =& getMailer();
//    $xoopsMailer->sendMail($email,'Order Instructions',$body,'');
  }
  /////////////////////////////////////////////////////
  function install(){
    parent::install();
    //
    $this->saveField('BILL_ENABLED',true,2,'Enable Bill Module?','b');
    $this->saveField('BILL_GATEWAY_DESCRIPTION','Bill Me',3,'Payment Description','s');
    $this->saveField('BILL_GATEWAY_INSTRUCTIONS','',3,'Extra Instructions (payment address, bank acccount etc)','x');
  }
    ////////////////////////////////////////////////
  function processPost($post, &$errors) {
    $this->postToGateway();
  }
  ////////////////////////////////////////////////
  function processReturn(&$oOrder, $post, &$error) {
    parent::processReturn($oOrder, $post);
    $hCommon =& xoops_getmodulehandler('common','xasset');
    $hOrder  =& xoops_getmodulehandler('order','xasset');
    //
    $oOrder->setVar('trans_id',time());
    $oOrder->setVar('status',$oOrder->orderStatusValidate());
    $oOrder->setVar('value',$oOrder->getOrderTotal('s'));
    //
    $dest = $hCommon->getModuleOption('orderCompleteRedirect') <> '' ? $hCommon->getModuleOption('orderCompleteRedirect') : 'index.php';
    $time = $hCommon->getModuleOption('orderCompleteWait');
    //
    if ($hOrder->insert($oOrder,true)) {
      unset($_SESSION['orderID']);
      //
      redirect_header($dest,$time,_LANG_ORDER_COMPLETE);
    } else {
      redirect_header($dest,$time,'Code 10. Could not save order details.');
    }
  }
}
