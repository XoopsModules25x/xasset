<?php

require_once('xassetBaseObject.php');

class xassetUserAppProducts extends XoopsObject {

  function xassetUserAppProducts($id = null) {
    $this->initVar('id', XOBJ_DTYPE_INT, null, false);
    $this->initVar('application_product_id', XOBJ_DTYPE_INT, null, false);
    $this->initVar('uid', XOBJ_DTYPE_INT, null, false);
    //
    if (isset($id)) {
      if (is_array($id)) {
        $this->assignVars($id);
      }
    } else {
      $this->setNew();
    }
  }
  /////////////////////////////////////////
  function increaseDownCount() {
    $this->setVar('down_count',$this->getVar('down_count')+1);
  }
}


class xassetUserAppProductsHandler extends xassetBaseObjectHandler {
  //vars
  var $_db;
  var $classname = 'xassetuserappproducts';
  var $_dbtable  = 'xasset_user_products';
  //cons
  function xassetUserAppProductsHandler(&$db)
  {
    $this->_db = $db;
  }
  ///////////////////////////////////////////////////
  function &getInstance(&$db)
  {
      static $instance;
      if(!isset($instance)) {
          $instance = new xassetUserAppProductsHandler($db);
      }
      return $instance;
  }
  ///////////////////////////////////////////////////
  function getUserProductMaxDowns($uid, $prodid) {
    $hAppProds =& xoops_getmodulehandler('applicationProduct','xasset');
    //
    $thisTable = $this->_db->prefix($this->_dbtable);
    $prodTable = $this->_db->prefix($hAppProds->_dbtable);
    //
    $sql       = "select sum(ap.max_access) sm from $prodTable ap inner join $thisTable ua on
                    ap.application_product_id = ua.id
                  where ua.application_product_id = $prodid and ua.uid = $uid";
    //
    if ($res = $this->_db->query($sql)) {
      if ($row = $this->_db->fetcharray($res)) {
        return $row['sm'];
      }
    }
    return false;
  }
  ///////////////////////////////////////////////////
  function addUserProduct($uid, $appProdID) {
    $obj =& $this->create();
    $obj->setVar('application_product_id',$appProdID);
    $obj->setVar('uid',$uid);
    //
    return $this->insert($obj);
  }
  ///////////////////////////////////////////////////
  function userHasProduct($uid, $appProdID) {
    $crit = new CriteriaCompo(new Criteria('application_product_id',$appProdID));
    $crit->add(new Crtieria('uid',$uid));
    //
    $objs =& $this->getObjects($crit);
    //
    return count($objs) > 0;
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
      $sql = sprintf( 'INSERT INTO %s (id, uid, application_product_id)
                       VALUES (%u, %u, %u)',
                      $this->_db->prefix($this->_dbtable),  $id, $uid, $application_product_id);
    } else {
        $sql = sprintf('UPDATE %s SET uid = %u, application_poduct_id = %u where id = %u',
                        $this->_db->prefix($this->_dbtable), $uid, $application_poduct_id, $id);
    }
    // Update DB
    if (false != $force) {
      $result = $this->_db->queryF($sql);
    } else {
      $result = $this->_db->query($sql);
    }

    if (!$result) {
      echo $sql;
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

?>
