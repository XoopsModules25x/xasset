<?php
//hack to kill referrer check..we don't want this as it comes from gateway and not xoops
define('XOOPS_XMLRPC', 1);
require('header.php');

//this will get called from a payment gateway. Grab the Order ID, Payment Gatway and the post and process it.
//check for valid order id
if ( isset($_SESSION['orderID']) && ($_SESSION['orderID'] > -1) ) {
  $hGateway =& xoops_getmodulehandler('gateway','xasset');
  $hOrder   =& xoops_getmodulehandler('order','xasset');
  $hLog     =& xoops_getmodulehandler('gatewayLog','xasset');
  $hCommon =& xoops_getmodulehandler('common','xasset');
  //
  $order    =& $hOrder->get($_SESSION['orderID']);
  $gateway  =& $hGateway->get($order->getVar('gateway'));
    $payGate  =& $hGateway->getGatewayModuleByID($gateway->getVar('id'));
  //
  $dest = $hCommon->getModuleOption('orderCompleteRedirect') <> '' ? $hCommon->getModuleOption('orderCompleteRedirect') : 'index.php';
  $time = $hCommon->getModuleOption('orderCompleteWait');
  //check if this order is open and if this gateway validates (ie paypal IPN)
  //if this gateway validates then the return does not process the order... just show confirmation
  //to the client.... if gateway validates then the order is handled by verify.php
  if ( $payGate->validates() )  /*&& ($order->getVar('status') == $order->orderStatusValidate()) )*/ {
    unset($_SESSION['orderID']);
        redirect_header($dest,$time,_LANG_ORDER_COMPLETE);
  } else if ( (!$payGate->validates()) && ($order->getVar('status') == $order->orderStatusGateway()) ) {
    $orderStatus = ($payGate->validates() && ($order->getVar('status') == $order->orderStatusValidate())) ||
                   ((!$payGate->validates()) && ($order->getVar('status') == $order->orderStatusGateway()) );
  //$orderStatus = true;
    if ($orderStatus){
        if (isset($gateway)) {
            //save order for log
            $hLog->addLog( $order->getVar('id'), $gateway->getVar('id'),
                                         $order->orderStatusValidate(), $_POST, true);
            $payGate->processReturn($order, $_POST, $errors);
      } else {
            redirect_header($dest,$time,'Code 12. Invalid Payment Gateway Code');
      }
    } else {
        redirect_header($dest,$time,'Code 13. Invalid Order Status. Order Aborted');
    }
  }
} else {
  redirect_header($dest,$time,'Code 14. Unauthorised Access Attempt');
}
//
include(XOOPS_ROOT_PATH . "/footer.php");
