<?php

require_once('xassetBaseObject.php');
//require_once('crypt.php');

class xassetApplicationProductMemb extends XAssetBaseObject {
  var $weight;
  //
  function xassetApplicationProductMemb($id = null) {
    $this->initVar('id', XOBJ_DTYPE_INT, null, false);
    $this->initVar('uid', XOBJ_DTYPE_INT, null, false);
    $this->initVar('order_detail_id', XOBJ_DTYPE_INT, null, false);
    $this->initVar('group_id', XOBJ_DTYPE_INT, null, false);
    $this->initVar('expiry_date', XOBJ_DTYPE_INT, null, false);
    $this->initVar('sent_warning', XOBJ_DTYPE_INT, null, false);
    //
    $this->weight = 17;
    //
    if (isset($id)) {
      if (is_array($id)) {
        $this->assignVars($id);
      }
    } else {
      $this->setNew();
    }
  }
  ////////////////////////////////////////////////////////////
  function uid() {
    return $this->getVar('uid');
  }
  ////////////////////////////////////////////////////////////
  function sentOverADayAgo() {
    //check if it has been 24 hours since
    if ($this->getVar('sent_warning') > 0) {
      return (time() - $this->getVar('sent_warning')) > 60 * 60 * 24;
    } else {
      return true;
    } 
  }
  ///////////////////////////////////////////////////////////
  function setSentWarning() {
    $this->setVar('sent_warning',time());
  }
  ///////////////////////////////////////////////////////////
  function expiryDate($format = 'n') {
    switch ($format) {
      case 'n' : return $this->getVar('expiry_date'); break;
      case 's' : return date('Y-m-d',$this->getVar('expiry_date')); break;
      case 'l' : return date('l dS \of F Y h:i:s A', $this->getVar('expiry_date'));
    }
  }
  ///////////////////////////////////////////////////////////
  function &getOrderDetails() {
    $hOrderDetail =& xoops_getmodulehandler('orderDetail','xasset');
    return $hOrderDetail->get($this->getVar('order_detail_id'));
  }
}


class xassetApplicationProductMembHandler extends xassetBaseObjectHandler {
  //vars
  var $_db;
  var $classname = 'xassetapplicationproductmemb';
  var $_dbtable  = 'xasset_app_prod_memb';
  //cons
  function xassetApplicationProductMembHandler(&$db)
  {
    $this->_db = $db;
  }
  ///////////////////////////////////////////////////
  function &getInstance(&$db)
  {
      static $instance;
      if(!isset($instance)) {
          $instance = new xassetApplicationProductMembHandler($db);
      }
      return $instance;
  }
  ///////////////////////////////////////////////////
  function AddGroupExpiry($oOrdDetail, $oAppProd, $oUserDetails, $group='1') {
    $grpField = $group == '1' ? 'add_to_group' : 'add_to_group' . $group;
    $expField = $group == '1' ? 'group_expire_date' : 'group_expire_date' . $group;
    //
    $qty = $oOrdDetail->getVar('qty');
    //now determine if this is a new entry or an update.
    $crit = new CriteriaCompo(new Criteria('uid',$oUserDetails->uid()));
    $crit->add(new Criteria('group_id',$oAppProd->getVar($grpField)));
    //
    $existing =& $this->getObjects($crit);
    if (count($existing) > 0) {
      $oMember = reset($existing);
      //
      $oMember->setVar('order_detail_id',$oOrdDetail->ID());
      if ($oMember->getVar('expiry_date') < time()) { //membership expired
        $oMember->setVar('expiry_date', time() + $oAppProd->getVar($expField) * $oOrdDetail->getVar('qty') * 60*60*24);
      } else { //valid membership...extend
        $oMember->setVar('expiry_date', $oMember->getVar('expiry_date') + $oAppProd->getVar($expField) * $oOrdDetail->getVar('qty') * 60*60*24);
      }
      //insert
      return $this->insert($oMember);
    } else {
      $obj =& $this->create();
      $obj->setVar('uid',$oUserDetails->uid());
      $obj->setVar('order_detail_id',$oOrdDetail->ID());
      $obj->setVar('group_id',$oAppProd->getVar($grpField));
      $obj->setVar('expiry_date', time() + $oAppProd->getVar($expField) * $oOrdDetail->getVar('qty') * 60*60*24);
      //
      return $this->insert($obj);
    }
  }
  ///////////////////////////////////////////////////
  function getMembers($criteria = null) {
    global $imagearray;
    //
    $hUserDetails  =& xoops_getmodulehandler('gateway','xasset');
    $hMember       =& xoops_getmodulehandler('order','xasset');
    //tables
    $thisTable  = $this->_db->prefix($this->_dbtable);
    $userTable  = $this->_db->prefix('users');
    $groupTable = $this->_db->prefix('groups');
    $linkTable  = $this->_db->prefix('groups_users_link');
    //
    if (!isset($criteria)) {
      $criteria = new CriteriaCompo();
      $criteria->setSort('expiry_date','asc');
    }
    //
    $sql = "select distinct am.*, u.uname, g.name
            from $thisTable am inner join $userTable u on
              am.uid = u.uid inner join $groupTable g ON
              am.group_id = g.groupid inner join $linkTable gl ON
              g.groupid = gl.groupid and gl.uid = u.uid";
    //
    $this->postProcessSQL($sql,$criteria);
    $ary = array(); 
    //
    if ($res = $this->_db->query($sql)) {
      $i = 0;
      while ($row = $this->_db->fetchArray($res)) {
        $actions = '<a href="main.php?op=removeFromGroup&id='.$row['id'].'">'.$imagearray['deleteimg'].'</a>';
        //
        $ary[$i] = $row;
        $ary[$i]['expired']          = $row['expiry_date'] < time();
        $ary[$i]['formatExpiryDate'] = formatTimestamp($row['expiry_date'],'l');
        $ary[$i]['actions']          = $actions;
        //
        $i++;
      }
    } 
    return $ary;
  }
  //////////////////////////////////////////////////
  function getMembersForSubscription($crit) {
    $hAppProd =& xoops_getmodulehandler('applicationProduct','xasset');
    //two step process: first get members then another query to get products and buy now buttons
    $aSubs =& $this->getMembers($crit);
    //
    $cOrder  = new CriteriaCompo();
    foreach($aSubs as $key=>$aSub) {
      $cOrder->add( new Criteria('id',$aSub['order_detail_id']),'or');
    }
    //now get list of appProds and add to return array
    $aAppProds =& $hAppProd->getApplicationProductObjectsByOrderDetail($cOrder);//print_r($aAppProds);
    //iterate through aSubs again and build buy now buttons
    foreach($aSubs as $key=>$aSub) { echo $aSubs[$key]['order_detail_id'];
      $aSubs[$key]['product'] = $aAppProds[$aSubs[$key]['order_detail_id']]->itemDescription();
      $aSubs[$key]['period']  = $aAppProds[$aSubs[$key]['order_detail_id']]->groupExpireDate();
      $aSubs[$key]['buyNow']  = $aAppProds[$aSubs[$key]['order_detail_id']]->getBuyNowButton();
    } 
    return $aSubs;
  }
  ///////////////////////////////////////////////////
  function removeFromGroup($id, $force = false) {
    $hMember =& xoops_gethandler('member');   
    
    $oObj =& $this->get($id); 
    //first remove from xoops group
    if (!$force) {
      $hMember->removeUsersFromGroup($oObj->getVar('group_id'), array($oObj->getVar('uid')));
    } else {
      $table = $this->_db->prefix('groups_users_link');
      $groupid = $oObj->getVar('group_id');
      $uid     = $oObj->getVar('uid');
      //
      $sql   = "delete from $table where groupid = $groupid and uid = $uid";
      return $this->_db->queryF($sql);
    }  
    //last, delete from this table
    return $this->delete($oObj);
  }
  ///////////////////////////////////////////////////
  function getSubscriberCountByUser($xoopsUser) {
    if ($xoopsUser) {
      $crit = new Criteria('uid',$xoopsUser->uid());
      return $this->getCount($crit);
    } else {
      return false;
    }
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
      $sql = sprintf( 'INSERT INTO %s (id, uid, order_detail_id, group_id, expiry_date, sent_warning)
                       VALUES (%u, %u, %u, %u, %u, %u)',
                       $this->_db->prefix($this->_dbtable),  $id, $uid, $order_detail_id, $group_id, $expiry_date, $sent_warning);
    } else {
        $sql = sprintf( 'UPDATE %s SET uid = %u, order_detail_id = %u, group_id = %u, expiry_date = %u, sent_warning = %u where id = %u',
                        $this->_db->prefix($this->_dbtable), $uid, $order_detail_id, $group_id, $expiry_date, $sent_warning, $id);
    }
     //echo $sql;
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
    //
    return true;
  }
}

?>
