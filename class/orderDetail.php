<?php

require_once('xassetBaseObject.php');

class xassetOrderDetail extends XAssetBaseObject {

  function xassetOrderDetail($id = null) {
    $this->initVar('id', XOBJ_DTYPE_INT, null, false);
    $this->initVar('order_index_id', XOBJ_DTYPE_INT, null, false);
    $this->initVar('app_prod_id', XOBJ_DTYPE_INT, null, false);
    $this->initVar('qty', XOBJ_DTYPE_INT, time(), false);
    //
    if (isset($id)) {
      if (is_array($id)) {
        $this->assignVars($id);
      }
    } else {
      $this->setNew();
    }
  }
  //////////////////////////////////////////////////
  function getAppProdID() {
    return $this->getVar('app_prod_id');
  }
  //////////////////////////////////////////////////
  function &getOrderIndex() {
    $idx =& xoops_getmodulehandler('order','xasset');

    return $idx->get($this->getVar('order_index_id'));
  }
  //////////////////////////////////////////////////
  function &getAppProduct() {
    $hProd =& xoops_getmodulehandler('applicationProduct','xasset');

    return $hProd->get($this->getVar('app_prod_id'));
  }
  /////////////////////////////////////////////////
  function getOrderItemDescription() {
    $oAppProd =& $this->getAppProduct();

    return $oAppProd->itemDescription();
  }
}

class xassetOrderDetailHandler extends xassetBaseObjectHandler {
  //vars
  var $_db;
  var $classname = 'xassetorderdetail';
  var $_dbtable  = 'xasset_order_detail';
  //cons
  function xassetOrderDetailHandler(&$db)
  {
    $this->_db = $db;
  }
  ///////////////////////////////////////////////////
  function &getOrderDetailsObjectsByIndex($indexID) {
    $crit = new CriteriaCompo(new Criteria('order_index_id',$indexID));

    return $this->getObjects($crit);
  }
  ///////////////////////////////////////////////////
  function getOrderDetailsByIndex($indexID) {
    $crit = new CriteriaCompo(new Criteria('order_index_id',$indexID));

    return $this->getOrderDetailArray($crit);
  }
  ///////////////////////////////////////////////////
  function getOrderDetailArray($criteria){
    global $imagearray;
    //
    $hAppProd   =& xoops_getmodulehandler('applicationProduct','xasset');
    $hApp       =& xoops_getmodulehandler('application','xasset');
    $hCurr      =& xoops_getmodulehandler('currency','xasset');
    //tables
    $thisTable  = $this->_db->prefix($this->_dbtable);
    $apTable    = $this->_db->prefix($hAppProd->_dbtable);
    $aTable     = $this->_db->prefix($hApp->_dbtable);
    $curTable   = $this->_db->prefix($hCurr->_dbtable);
    //
    $sql        = "select od.id, od.qty, ap.id appProdID, (ap.unit_price/c.value) unit_price,
                          (od.qty * (ap.unit_price/c.value)) totalPrice,
                          ap.item_code, ap.item_description, a.id appID, a.name, a.description, a.version
                   from   $thisTable od inner join $apTable ap on
                     od.app_prod_id = ap.id inner join $aTable a on
                     ap.application_id = a.id inner join $curTable c on
                     ap.base_currency_id = c.id";
    //
    $this->postProcessSQL($sql,$criteria);
    $ary = array();
    //
    if ($res = $this->_db->query($sql)) {
      while ($row = $this->_db->fetchArray($res)) {
        $actions = '<a href="order.php?op=removeOrderItem&id='.$row['id'].'">'.$imagearray['deleteimg'].'</a>';
        //
        $ary[]   = array('id'          => $row['id'],
                         'appProdID'   => $row['appProdID'],
                         'appID'       => $row['appID'],
                         'qty'         => $row['qty'],
                         'unit_price'  => $row['unit_price'],
                         'totalPrice'  => $row['totalPrice'],
                         'item_code'   => $row['item_code'],
                         'name'        => $row['name'],
                         'description' => $row['description'],
                         'prodDescription' => $row['item_description'],
                         'version'     => $row['version'],
                         'actions'     => $actions );
      }
    }

    return $ary;
  }
  ///////////////////////////////////////////////////
  function &getOrderApplicationProducts($orderID) {
    $hAppProd   =& xoops_getmodulehandler('applicationProduct','xasset');
    $hCurrency  =& xoops_getmodulehandler('currency','xasset');
    //
    $thisTable    = $this->_db->prefix($this->_dbtable);
    $appProdTable = $this->_db->prefix($hAppProd->_dbtable);
    $currTable    = $this->_db->prefix($hCurrency->_dbtable);
    //
    $sql = "select ap.*, od.qty, c.value base_exchange
            from $appProdTable ap inner join $thisTable od on
              od.app_prod_id = ap.id inner join $currTable c on
              ap.base_currency_id = c.id
              where od.order_index_id = $orderID
            order by ap.tax_class_id";
    //
    $ary = array();
    //
    if ($res = $this->_db->query($sql)) {
      while ($row = $this->_db->fetchArray($res)) {
        $ary[] = array('id'               => $row['id'],
                       'tax_class_id'     => $row['tax_class_id'],
                       'base_currency_id' => $row['base_currency_id'],
                       'unit_price'       => $row['unit_price']/$row['base_exchange'],
                       //'base_exchange'    => $row['base_exchange'],
                       'qty'              => $row['qty'] );
      }
    }

    return $ary;
  }
  ///////////////////////////////////////////////////
  function updateOrderQty($id, $qty){
    $item =& $this->get($id);
    $item->setVar('qty',$qty);

    return $this->insert($item);
  }
  ///////////////////////////////////////////////////
  function &getInstance(&$db)
  {
      static $instance;
      if(!isset($instance)) {
          $instance = new xassetOrderDetailHandler($db);
      }

      return $instance;
  }
  ///////////////////////////////////////////////////
  function deleteByOrder($orderID) {
    $thisTable = $this->_db->prefix($this->_dbtable);
    $sql = "delete from $thisTable where order_index_id = $orderID";
    //
    $this->_db->query($sql);
  }
  ///////////////////////////////////////////////////
  function insert(&$obj, $force = false)
  {
    parent::insert($obj, $force);
    // Copy all object vars into local variables
    foreach ($obj->cleanVars as $k => $v) {
      ${$k} = $v;
    }

    // Create query for DB update
    if ($obj->isNew()) {
      // Determine next auto-gen ID for table
      $id = $this->_db->genId($this->_db->prefix($this->_dbtable).'_uid_seq');
      $sql = sprintf( 'INSERT INTO %s (id, order_index_id, app_prod_id, qty)
                       VALUES (%u, %u, %u, %u)',
                      $this->_db->prefix($this->_dbtable),  $id, $order_index_id, $app_prod_id, $qty);
    } else {
        $sql = sprintf('UPDATE %s SET order_index_id = %u, app_prod_id = %u, qty = %u where id = %u',
                        $this->_db->prefix($this->_dbtable), $order_index_id, $app_prod_id, $qty, $id);
    }
     //echo $sql;
    // Update DB
    if (false != $force) {
      $result = $this->_db->queryF($sql);
    } else {
      $result = $this->_db->query($sql);
    }

    if (!$result) {
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
