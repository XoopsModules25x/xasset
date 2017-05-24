<?php

require_once('xassetBaseObject.php');

class xassetOrder extends xassetBaseObject {

  function xassetOrder($id = null) {
    $this->initVar('id', XOBJ_DTYPE_INT, null, false);
    $this->initVar('uid', XOBJ_DTYPE_INT, null, true);
    $this->initVar('user_detail_id', XOBJ_DTYPE_INT, null, false);
        $this->initVar('currency_id', XOBJ_DTYPE_INT, null, false);
    $this->initVar('number', XOBJ_DTYPE_INT, null, false);
    $this->initVar('date', XOBJ_DTYPE_INT, time(), false);
    $this->initVar('status', XOBJ_DTYPE_INT, 0, false);
    $this->initVar('gateway', XOBJ_DTYPE_INT, 0, false);
    $this->initVar('trans_id', XOBJ_DTYPE_TXTBOX, 0, false,200);
    $this->initVar('value', XOBJ_DTYPE_OTHER, 0, false);
    $this->initVar('fee', XOBJ_DTYPE_OTHER, 0, false);
    //
    if (isset($id)) {
      if (is_array($id)) {
        $this->assignVars($id);
      }
    } else {
            $this->setNew();
    }
  }
  ////////////////////////////////////////////  `
  function getOrderNet() {
    $hODetail   =& xoops_getmodulehandler('orderDetail','xasset');
    $hAppProd   =& xoops_getmodulehandler('applicationProduct','xasset');
    $hCurrency  =& xoops_getmodulehandler('currency','xasset');
    //tables
    $thisTable  = $hODetail->_db->prefix($hODetail->_dbtable);
    $apTable    = $hODetail->_db->prefix($hAppProd->_dbtable);
    $curTable   = $hCurrency->_db->prefix($hCurrency->_dbtable);
    //
    $sql        = "select sum(od.qty * (ap.unit_price/c.value)) totalPrice
                   from   $thisTable od inner join $apTable ap on
                     od.app_prod_id = ap.id inner join $curTable c on
                     ap.base_currency_id = c.id";
    $crit     = new CriteriaCompo(new Criteria('order_index_id',$this->getVar('id')));
    //
    $hODetail->postProcessSQL($sql,$crit);
    //
    if ($res = $hODetail->_db->query($sql)) {
      if ($row = $hODetail->_db->fetchArray($res)) {
        return $row['totalPrice'];
      }
    }

    return false;
  }
  ////////////////////////////////////////////  `
    function getOrderTotal($format = 'f'){ //f=full s=short
    $hCurr      =& xoops_getmodulehandler('currency','xasset');
    //
    $cur      =& $hCurr->get($this->getVar('currency_id'));
    //
        $orderNet = $this->getOrderNet();
        $tax      = $this->getOrdertaxTotalSum();
    //
    $total = $tax['amount'] + $orderNet;
    //
    if ($format == 'f'){
      return $cur->valueFormat($total);
    } else if ($format == 's') {
      return $cur->valueOnlyFormat($total);
    }
  }
  ////////////////////////////////////////////  `
  function getOrdertaxTotalSum() {
    $hCurr      =& xoops_getmodulehandler('currency','xasset');
    //
    $cur      =& $hCurr->get($this->getVar('currency_id'));
    $taxArray =& $this->getOrderTax();
    $total    = 0;
    //
    for($i=0;$i<count($taxArray);$i++) {
      $total = $total + floatval($taxArray[$i]['amount']);
    }
    $res = array( 'amount'    => $total,
                  'fmtAmount' => $cur->valueFormat($total),
                  'name'      => 'Total Tax');
    //
    return $res;
  }
  ////////////////////////////////////////////  `
  function getOrderTaxTotal() {
    $hCurr      =& xoops_getmodulehandler('currency','xasset');
    //
    $cur      =& $hCurr->get($this->getVar('currency_id'));
    $taxArray =& $this->getOrderTax();
    $total    = 0;
    //
    $res = array();
    for($i=0;$i<count($taxArray);$i++) {
      $res[] = array('amount'    => $taxArray[$i]['amount'],
                   'fmtAmount' => $cur->valueFormat($taxArray[$i]['amount']),
                   'name'      => $taxArray[$i]['description']);
      //$total =. $taxArray[$i]['amount'];
    }

    return $res;
  }
  ///////////////////////////////////////////////////
  function &getOrderTax() {
    $hIndex     =& xoops_getmodulehandler('order','xasset');
    $hODetail   =& xoops_getmodulehandler('orderDetail','xasset');
    $hAppProd   =& xoops_getmodulehandler('applicationProduct','xasset');
    $hClient    =& xoops_getmodulehandler('userDetails','xasset');
    $hTaxRate   =& xoops_getmodulehandler('taxRate','xasset');
    $hTaxZone   =& xoops_getmodulehandler('taxZone','xasset');
    $hCurr      =& xoops_getmodulehandler('currency','xasset');
    //tables
    $thisTable  = $hIndex->_db->prefix($hIndex->_dbtable);
    $ODetTable  = $hIndex->_db->prefix($hODetail->_dbtable);
    $indexTable = $hIndex->_db->prefix($hIndex->_dbtable);
    $apTable    = $hIndex->_db->prefix($hAppProd->_dbtable);
        $cliTable   = $hIndex->_db->prefix($hClient->_dbtable);
    $rateTable  = $hIndex->_db->prefix($hTaxRate->_dbtable);
    $tzTable    = $hIndex->_db->prefix($hTaxZone->_dbtable);
    //
    $id         = $this->getVar('id');
    $cur        =& $hCurr->get($this->getVar('currency_id'));
    //could have gone the object route but this is far more efficient.
    $sql = "SELECT tr.id, ap.tax_class_id, tr.description, tr.priority, tr.rate
             FROM $ODetTable od
             INNER JOIN $thisTable oi ON (od.order_index_id = oi.id)
             INNER JOIN $cliTable ud ON (oi.user_detail_id = ud.id)
             INNER JOIN $tzTable tz ON (ud.country_id = tz.country_id) AND ((ud.zone_id = tz.zone_id) or (tz.zone_id = 0))
             INNER JOIN $apTable ap ON (od.app_prod_id = ap.id)
             inner join $rateTable tr on (tr.region_id = tz.region_id) and (tr.tax_class_id = ap.tax_class_id)
           where oi.id = $id
           group by tr.id, tax_class_id, tr.description, tr.priority
           order by tr.priority";
    //
    $taxArray = array();
    //
    if ($res = $hIndex->_db->query($sql)) {
      while ($row = $hIndex->_db->fetchArray($res)) {
        $taxArray[$row['priority']][] = $row;
      }
    }// print_r($taxArray);
    //now get order items and apply relevant tax and build into result array
    $prodArray =& $hODetail->getOrderApplicationProducts($id); //print_r($prodArray);
    //repopulate $taxArray with order values.
    foreach($taxArray as $priority=>$aTax) {
      if (isset($subTax))
        unset($subTax);
      for ($j=0;$j<count($prodArray);$j++) {
        $tax = 0;
        for ($i=0;$i<count($aTax);$i++) {
          if ($prodArray[$j]['tax_class_id'] == $aTax[$i]['tax_class_id']) {
            $tax = $tax + $prodArray[$j]['qty'] * $prodArray[$j]['unit_price'] * ($aTax[$i]['rate']/100);
            $subTax[$i] = $subTax[$i] + $prodArray[$j]['qty'] * $prodArray[$j]['unit_price'] * ($aTax[$i]['rate']/100);
            $taxArray[$priority][$i]['amount'] = $subTax[$i];
          }
        }
        $prodArray[$j]['unit_price']    = $prodArray[$j]['unit_price'] + $tax;
      }
    } //print_r($prodArray); print_r($taxArray);
    //finally construct output array
    $aOutTax = array();
    foreach($taxArray as $priority=>$aTaxs) {
      foreach($aTaxs as $key=>$aTax) {
        $aOutTax[] = $aTax;
      }
    } //print_r($aOutTax);
        return $aOutTax;
  }
  ///////////////////////////////////////////////////
    function getOrderDetailsArray() {
        $hODetail   =& xoops_getmodulehandler('orderDetail','xasset');
        $hCurr      =& xoops_getmodulehandler('currency','xasset');
        //
    $currID     = isset($_SESSION['currency_id']) ? $_SESSION['currency_id'] : $this->getVar('currency_id');
    //
        $cur     =& $hCurr->get($currID);
        $details = $hODetail->getOrderDetailsByIndex($this->getVar('id'));
        //now add the currency info
        for($i=0;$i<count($details);$i++) {
            $details[$i]['fmtUnitPrice'] = $cur->valueFormat($details[$i]['unit_price']);
      $details[$i]['fmtTotPrice']  = $cur->valueFormat($details[$i]['totalPrice']);
    }
    //
    return $details;
    }
  ///////////////////////////////////////////////////
  function addOrderItem($product, $qty) {
    //only save if the index record has been posted
    $hODetail =& xoops_getmodulehandler('orderDetail','xasset');
    //check if we ar updating or inserting
    $crit = new CriteriaCompo(new Criteria('order_index_id',$this->getVar('id')));
    $crit->add(new Criteria('app_prod_id',$product));
    //
    if ($orderObjs = $hODetail->getObjects($crit)) {
      $order =& $hODetail->get($orderObjs[0]->getVar('id'));
      $qty   = $qty + $order->getVar('qty');
    }
    else {
      $order =& $hODetail->create();
    }
    //
    $order->setVar('order_index_id',$this->getVar('id'));
    $order->setVar('app_prod_id',$product);
    $order->setVar('qty',$qty);
    //
    return $hODetail->insert($order,true);
  }
  ///////////////////////////////////////////////////
  function &orderDetails() {
    $hODetails =& xoops_getmodulehandler('orderDetail','xasset');

    return $hODetails->getOrderDetailsObjectsByIndex($this->getVar('id'));
  }
  //////////////////////////////////////////////////
  function addOrderProductsToUser() {
    global $xoopsUser;
    //
    $huAppProd  =& xoops_getmodulehandler('userAppProducts','xasset');
    $items      =& $this->orderDetails();
    //
    foreach($items as $item) {
      $huAppProd->addUserProduct($xoopsUser,$item->getVar('id'));
    }
  }
  //////////////////////////////////////////////////
  function processPurchase() {
    $hNotify     =& xoops_getmodulehandler('notificationService','xasset');
    $hProdMember =& xoops_getmodulehandler('applicationProductMemb','xasset');
    //we've got the order ID here. So iterate through order items and:
    //1. Add to xoops groups if required
    $aDetails =& $this->orderDetails();
    foreach($aDetails as $key=>$oDetail) {
      $oAppProduct =& $oDetail->getAppProduct();
      //now check for group membership
      if ($oAppProduct->getVar('add_to_group') > 0) {
        $oUserDetail =& $this->getUserDetail();
        $oUserDetail->addUserToGroup($oAppProduct->getVar('add_to_group'));
        //add to group expiry table
        $hProdMember->AddGroupExpiry($oDetail,$oAppProduct,$oUserDetail);
        //
        if (isset($oUserDetail)) {
          unset($oUserDetail);
        }
      }
      if ($oAppProduct->getVar('add_to_group2') > 0) {
        $oUserDetail =& $this->getUserDetail();
        $oUserDetail->addUserToGroup($oAppProduct->getVar('add_to_group2'));
        //add to group expiry table
        $hProdMember->AddGroupExpiry($oDetail,$oAppProduct,$oUserDetail,'2');
        //
        if (isset($oUserDetail)) {
          unset($oUserDetail);
        }
      }
    }
    //order is complete.. generate order complete email
    $hNotify->order_complete($this);
  }
  //////////////////////////////////////////////////
  function &getUserDetail() {
    $hUserDetail =& xoops_getmodulehandler('userDetails','xasset');

    return $hUserDetail->get($this->getVar('user_detail_id'));
  }
  //////////////////////////////////////////////////
  function getOrderItemsAsText() {
    $oItems =& $this->orderDetails();
    //
    $items = '';
    foreach($oItems as $key=>$oItem) {
      $oAppProd =& $oItem->getAppProduct();
      //
      $product = $oAppProd->getVar('item_description');
      $qty     = $oItem->getVar('qty');
      //
      $items .= "Product : $product\t  Quantity: $qty\n";
    }

    return $items;
  }
  //////////////////////////////////////////////////
  function getSpecialInstructionsAsText() {
    $oItems =& $this->orderDetails();
    //
    $inst = '';
    foreach($oItems as $key=>$oItem) {
      $oAppProd =& $oItem->getAppProduct();
      //
      $inst .= $oAppProd->getVar('extra_instructions')."\n\n";
    }
    if ($inst <> '')
      $inst = "Special Instructions\n\n".$inst;
    //
    return $inst;
  }
  //////////////////////////////////////////////////
  function &getArray() {
    $hOrder =& xoops_getmodulehandler('order','xasset');
    $hCurr   =& xoops_getmodulehandler('currency','xasset');
    //
    $oCurr =& $hCurr->get($this->getVar('currency_id'));
    //
    $ar =& parent::getArray();
    $ar['dateFmt']     = formatTimestamp($this->getVar('date'), 's');
    $ar['statusFmt']   = $hOrder->getStatuByCode($this->getVar('status'));
    $ar['currencyFmt'] = $oCurr->getVar('name');
    //
    return $ar;
  }
  //////////////////////////////////////////////////
  function orderStatusNew() {
    return 1;
  }
  function orderStatusCheckout(){
    return 2;
  }
  function orderStatusGateway(){
    return 3;
  }
  function orderStatusValidate(){
    return 4;
  }
  function orderStatusComplete(){
    return 5;
  }
}

class xassetOrderHandler extends xassetBaseObjectHandler {
  //vars
  var $_db;
  var $classname = 'xassetorder';
  var $_dbtable  = 'xasset_order_index';
  var $_weight   = 332;
  //cons
  function xassetOrderHandler(&$db)
  {
    $this->_db = $db;
  }
  ///////////////////////////////////////////////////
  function &getInstance(&$db)
  {
      static $instance;
      if(!isset($instance)) {
          $instance = new xassetOrderHandler($db);
      }

      return $instance;
  }
  ///////////////////////////////////////////////////
  function getStatuByCode($status) {
    switch ($status) {
      case 1 : return 'In Cart';
      case 2 : return 'Checked Out';
      case 3 : return 'Gone to Gateway';
      case 4 : return 'Validated';
      case 5 : return 'Payment Complete';
    }
  }
  ///////////////////////////////////////////////////
  function transactionExists($transID) {
    $crit = new Criteria('trans_id',$transID);

    return $this->getCount($crit) > 0;
  }
  ///////////////////////////////////////////////////
  function &getUserDownloads($userID) {
    global $imagearray;
    //
    $hOrderDetail   =& xoops_getmodulehandler('orderDetail','xasset');
    $hAppProduct    =& xoops_getmodulehandler('applicationProduct','xasset');
    $hPackGroup     =& xoops_getmodulehandler('packageGroup','xasset');
    $hPackage       =& xoops_getmodulehandler('package','xasset');
    $hUserStats     =& xoops_getmodulehandler('userPackageStats','xasset');
    $hOrder         = $this;
    //
    $crypt = new xassetCrypt();
    //
    $tableOrderDetail = $this->_db->prefix($hOrderDetail->_dbtable);
    $tableAppProduct  = $this->_db->prefix($hAppProduct->_dbtable);
    $tablePackGroup   = $this->_db->prefix($hPackGroup->_dbtable);
    $tablePackage     = $this->_db->prefix($hPackage->_dbtable);
    $tableStats       = $this->_db->prefix($hUserStats->_dbtable);
    $tableOrder       = $this->_db->prefix($hOrder->_dbtable);
    //
    $sql = "select od.qty, oi.status, oi.number, oi.trans_id, oi.date,
                   ap.item_description, ap.max_access, ap.max_days, ap.expires, p.filename,
                   p.serverFilePath, p.filetype, p.id packageID, p.isVideo, count(us.id) downloaded
            from $tableOrderDetail od inner join $tableOrder oi on
              od.order_index_id    = oi.id inner join $tableAppProduct ap on
              od.app_prod_id       = ap.id inner join $tablePackGroup pg ON
              ap.package_group_id  = pg.id inner join $tablePackage p on
              ap.package_group_id  = p.packagegroupid left join $tableStats us on
              us.uid               = oi.uid and us.packageid = p.id
            where oi.user_detail_id = $userID
            group by od.qty, oi.status, oi.number, oi.trans_id, oi.date,
                     ap.item_description, ap.max_access, ap.max_days, ap.expires, p.filename,
                     p.serverFilePath, p.filetype, p.id
            order by p.filename";
    //
    $i = 0;
    $ary = array();
    //
    if ($res = $this->_db->query($sql)) {
      while($row = $this->_db->fetchArray($res)) {
        $ary[$i] = $row;
        $ary[$i]['statusFmt']      = $this->getStatuByCode($row['status']);
        $ary[$i]['packageKey']     = $crypt->cryptValue($row['packageID'],$hPackage->_weight);
        $ary[$i]['datePurchase']   = formatTimestamp($row['date'],'s');
        $ary[$i]['downloadIcon']   = $imagearray['download'];
        //
        if ($row['max_days'] > 0) {
          $ary[$i]['expiresFmt'] = $row['max_days'].' Days from download.';
        }  else {
          $ary[$i]['expiresFmt'] = 'Never';
        }
        //
        $i++;
      }
    }  else {
      echo $sql;
    }

    return $ary;
  }
  ///////////////////////////////////////////////////
  function &getOrders() {
    return $this->getOrderArray();
  }
  ///////////////////////////////////////////////////
  function &getOrderArray($crit = null) {
    if (!isset($crit)) {
      $crit = new Criteria('1',1);
      $crit->setSort('id');
    }
    //
    $hClient   =& xoops_getmodulehandler('userDetails','xasset');
    $hCurrency =& xoops_getmodulehandler('currency','xasset');
    //
    $thisTable   = $this->_db->prefix($this->_dbtable);
    $clientTable = $this->_db->prefix($hClient->_dbtable);
    $curTable    = $this->_db->prefix($hCurrency->_dbtable);
    $userTable   = $this->_db->prefix('users');
    //
    $sql = "select o.*, u.uname, c.first_name, c.last_name, cu.code currency from $thisTable o inner join $clientTable c on
              o.user_detail_id = c.id inner join $userTable u on
              c.uid = u.uid inner join $curTable cu on
              o.currency_id = cu.id";
    //
    $this->postProcessSQL($sql,$crit);
    //
    $ar = array();
    $i  = 0;
    //
    if ($res = $this->_db->query($sql)) {
      while ($row = $this->_db->fetchArray($res)) {
        $ar[$i] = $row;
        $ar[$i]['dateFmt']   = formatTimestamp($row['date'], 's');
        $ar[$i]['statusFmt'] = $this->getStatuByCode($row['status']);
        $ar[$i]['fullName']  = $row['first_name'].' '.$row['last_name'];
        //
        $i++;
      }
    }

    return $ar;
  }
  ///////////////////////////////////////////////////
  function userInCartOrders($uid) {
    $crit = new CriteriaCompo(new Criteria('uid',$uid));
    $crit->add(new Criteria('status',3,'<'));
    //
    $objs = $this->getObjects($crit);
    //
    if (count($objs) > 0) {
      $obj = reset($objs);

      return $obj->getVar('id');
    } else {
      return false;
    }
  }
  ///////////////////////////////////////////////////
  function delete($orderID) {
    //delete items first
    $hOrderDetail =& xoops_getmodulehandler('orderDetail','xasset');
    $hOrderDetail->deleteByOrder($orderID);
    //
    parent::deleteByID($orderID);
  }
  ///////////////////////////////////////////////////
  function insert(&$obj, $force = false)
  {
    if (!parent::insert($obj, $force)) {
      return false;
    }
    // Copy all object vars into local variables
    foreach ($obj->cleanVars as $k => $v) {
      ${$k} = $v;
    }

    // Create query for DB update
    if ($obj->isNew()) {
      // Determine next auto-gen ID for table
      $id = $this->_db->genId($this->_db->prefix($this->_dbtable).'_uid_seq');
      $sql = sprintf( 'INSERT INTO %s (id, user_detail_id, currency_id, uid, number, date, status, gateway, trans_id, value, fee)
                       VALUES (%u, %u, %u, %u, %u, %u, %u, %u, %s, %f, %f)',
                      $this->_db->prefix($this->_dbtable),  $id, $user_detail_id, $currency_id, $uid, $number,
                      $date, $status, $gateway, $this->_db->quoteString($trans_id), $value, $fee);
    } else {
        $sql = sprintf('UPDATE %s SET user_detail_id = %u, currency_id = %u, uid = %u, number = %u, date = %u,
                        status = %u, gateway = %u, trans_id = %s, value = %f, fee = %f where id = %u',
                        $this->_db->prefix($this->_dbtable), $user_detail_id, $currency_id, $uid, $number,
                        $date, $status, $gateway, $this->_db->quoteString($trans_id), $value, $fee, $id);
    }
     //echo $sql;
    // Update DB
    if (false != $force) {
      $result = $this->_db->queryF($sql);
    } else {
      $result = $this->_db->query($sql);
    }

    if (!$result) {
      print_r($this);

      return false;
    }

    //Make sure auto-gen ID is stored correctly in object
    if (empty($id)) {
      $id = $this->_db->getInsertId();
    }
    $obj->assignVar('id', $id);

    return true;
  }
}
