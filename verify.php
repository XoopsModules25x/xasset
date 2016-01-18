<?php
define('XOOPS_XMLRPC', 1);
require('servicemain.php');
//
if (count($_POST) > 0 ) {
    $hGateway =& xoops_getmodulehandler('gateway','xasset');
    $hOrder   =& xoops_getmodulehandler('order','xasset');
    $hLog     =& xoops_getmodulehandler('gatewayLog','xasset');
    //determine the gateway and order id from $_POST
    if ($orderID = $hGateway->getGatewayFromPost($_POST, $gateway)) {
        $order    =& $hOrder->get($orderID);
        //check if this order is open;
        if ($order->getVar('status') == $order->orderStatusGateway()) {
            //log post
            $hLog->addLog( $order->getVar('id'),
                                         $order->getVar('gateway'),
                                         $order->orderStatusValidate(),
                                         $_POST );
            //
            if (isset($gateway)) {
                if ( $gateway->validates() ) {
                    if ( $gateway->validateTransaction($order->getVar('id'), $_POST) ) {
            $hLog->addLog( $order->getVar('id'),
                           $order->getVar('gateway'),
                           $order->orderStatusValidate(),
                           'validated' );
            //
                        $order->setVar('status',$order->orderStatusValidate());
                        $hOrder->insert($order);
            //now process and update order and ticket
            if ($gateway->processReturn($order, $_POST)) {
              $hLog->addLog( $order->getVar('id'),
                           $order->getVar('gateway'),
                           $order->orderStatusValidate(),
                           'processed' );
                        $order->setVar('status',$order->orderStatusComplete());
                        if ($hOrder->insert($order)) {
                            $order->processPurchase();
                        } else {
                $post = print_r($hOrder,true);
                $hLog->addLog( $order->getVar('id'),
                               $order->getVar('gateway'),
                               $order->orderStatusValidate(),
                               "Failed to save order: $post" );
              }
            } else {
              $post = print_r($_POST,true);
              $hLog->addLog( $order->getVar('id'),
                             $order->getVar('gateway'),
                             $order->orderStatusValidate(),
                             "Did not process return from post: $post" );
            }
                    }  else {
            $post = print_r($_POST,true);
            $hLog->addLog( $order->getVar('id'),
                           $order->getVar('gateway'),
                           $order->orderStatusValidate(),
                           "Did not validate from post: $post" );
          }
                }
      } else {
          $hLog->addLog( $order->getVar('id'),
                         $order->getVar('gateway'),
                         $order->orderStatusValidate(),
                         "Gateway does not validate" );
        }
      } else {
        $hLog->addLog( $order->getVar('id'),
                       $order->getVar('gateway'),
                       $order->orderStatusValidate(),
                       "Gateway not found" );
    }
    }
}
