<?php

require_once('xassetBaseObject.php');

class xassetUserDetails extends XAssetBaseObject {

  function xassetUserDetails($id = null) {
    $this->initVar('id', XOBJ_DTYPE_INT, null, false);
    $this->initVar('uid', XOBJ_DTYPE_INT, null, false);
    $this->initVar('zone_id', XOBJ_DTYPE_INT, null, false);
    $this->initVar('country_id', XOBJ_DTYPE_INT, null, true, null, '', 'Country');
    $this->initVar('first_name', XOBJ_DTYPE_TXTBOX, null, false, 50);
    $this->initVar('last_name', XOBJ_DTYPE_TXTBOX, null, false, 50);
    $this->initVar('street_address1', XOBJ_DTYPE_TXTBOX, null, true, 200, '', 'Street Address');
    $this->initVar('street_address2', XOBJ_DTYPE_TXTBOX, null, false, 200);
    $this->initVar('town', XOBJ_DTYPE_TXTBOX, null, true, 30, '', 'Town/City');
    $this->initVar('state', XOBJ_DTYPE_TXTBOX, null, true, 30, '', 'State/County');
    $this->initVar('zip', XOBJ_DTYPE_TXTBOX, null, true, 20,'','Post/Zip Code');
    $this->initVar('tel_no', XOBJ_DTYPE_TXTBOX, null, false, 30);
    $this->initVar('fax_no', XOBJ_DTYPE_TXTBOX, null, false, 30);
    $this->initVar('company_name', XOBJ_DTYPE_TXTBOX, null, false, 100);
    $this->initVar('company_reg', XOBJ_DTYPE_TXTBOX, null, false, 50);
    $this->initVar('vat_no', XOBJ_DTYPE_TXTBOX, null, false, 30);
    $this->initVar('credits', XOBJ_DTYPE_INT, 0, false);
    $this->initVar('client_type', XOBJ_DTYPE_INT, 0, false);
    //
    if (isset($id)) {
      if (is_array($id)) {
        $this->assignVars($id);
      }
    } else {
      $this->setNew();
    }
  }
  //////////////////////////////////////
  function &getZone() {
    $zone =& xoops_getmodulehandler('zone','xasset');

    return $zone->get($this->getVar('zone_id'));
  }
  //////////////////////////////////////
  function uid() {
    return $this->getVar('uid');
  }
  //////////////////////////////////////
  function &getUser() {
    $hUser =& xoops_gethandler('user');

    return $hUser->get($this->uid());
  }
  //////////////////////////////////////
  function email() {
    $oUser =& $this->getUser();

    return $oUser->email();
  }
  //////////////////////////////////////
  function &addressArray() {
    $address = array();
    if ($this->getVar('street_address1') <> '')
      $address[] = $this->getVar('street_address1');
    if ($this->getVar('street_address2') <> '')
      $address[] = $this->getVar('street_address2');
    if ($this->getVar('town') <> '')
      $address[] = $this->getVar('town');
    if ($this->getVar('state') <> '')
      $address[] = $this->getVar('state');
    if ($this->getVar('zip') <> '')
      $address[] = $this->getVar('zip');
    if ($this->getVar('tel_no') <> '')
      $address[] = $this->getVar('tel_no');
    if ($this->getVar('fax_no') <> '')
      $address[] = $this->getVar('fax_no');
    //
    return $address;
  }
  //////////////////////////////////////
  function addCredits($credits = 0) {
    $handler =& xoops_getmodulehandler('userDetails','xasset');
    //
    $this->setVar('credits',$this->getVar('credits') + $credits);
    $handler->insert($this,true);
  }
  //////////////////////////////////////
  function addUserToGroup($groupid) {
    $hMember =& xoops_gethandler('member');
    //
    $aGroups =& $hMember->getGroupsByUser($this->getVar('uid'));
    $aGroups1 = array();
    //
    foreach($aGroups as $key=>$group) {
      $aGroups1[$group] = 1;
    }
    //
    if (!isset($aGroups[$groupid])) {  //echo $groupid .'.'.$this->getVar('uid');
      $hMember->addUserToGroup($groupid,$this->getVar('uid'));
    }
  }
  //////////////////////////////////////
  function &getUserDownloads() {
    $hOrder =& xoops_getmodulehandler('order','xasset');
    //
    return $hOrder->getUserDownloads($this->ID());
  }
  //////////////////////////////////////
  function canDownloadPackage($pPackageID, &$pError) {
    $hPackStats =& xoops_getmodulehandler('userPackageStats','xasset');
    $hThis      =& xoops_getmodulehandler('order','xasset');
    //
    $result    = false;
    $aPackages =& $hThis->getUserDownloads($this->ID());
    foreach($aPackages as $aPackage) {
      if ($aPackage['packageID'] == $pPackageID) {
        if ($aPackage['status'] == 5) {
          $result = true;
          if ($aPackage['max_days'] > 0) {
            $firstDowned = $hPackStats->getFirstDownloadDate($pPackageID,$this->getVar('uid'));
            if ( ($firstDowned + $aPackage['max_days']*60*60*24) < time()) {
              $result = true;
            } else {
              $pError = 'Download period has expired.';
              $result = false;
            }
          }
          if ($aPackage['max_access'] > 0) {
            $downed = $hPackStats->getUserPackageDownloadCount($pPackageID,$this->getVar('uid'));
            if ( $downed < $aPackage['max_access']) {
              $result = true;
            } else {
              $pError = 'Maximum download count exceeded.';
              $result = false;
            }
          }
        }
      }
    }
    //if we get here then client has no access.
    if (!$result) {
      $pError = 'Un-autorised access attempt.';
    }
    //
    return $result;
  }
}

class xassetUserDetailsHandler extends xassetBaseObjectHandler {
  //vars
  var $_db;
  var $classname = 'xassetuserdetails';
  var $_dbtable  = 'xasset_user_details';
  //cons
  function xassetUserDetailsHandler(&$db)
  {
    $this->_db = $db;
  }
  ///////////////////////////////////////////////////
  function &getInstance(&$db)
  {
      static $instance;
      if(!isset($instance)) {
          $instance = new xassetUserDetailsHandler($db);
      }

      return $instance;
  }
  ///////////////////////////////////////////////////
  function &getUserDetailByID($uid) {
    $crit = new CriteriaCompo(new Criteria('uid',$uid));
    $objs =& $this->getObjects($crit);
    if (count($objs) > 0) {
      $obj = reset($objs);

      return $obj;
    } else {
      $res = false;

      return $res;
    }
  }
  ///////////////////////////////////////////////////
  function getUserDetailArraybyUID($id) {
    $crit = new CriteriaCompo(new Criteria('uid',$id));
    $cust = $this->getUserDetailArray($crit);
    if (count($cust) > 0) {
      return $cust[0];
    } else {
      return false;
    }
  }
  ///////////////////////////////////////////////////
  function getUserDetailArray($criteria=null) {
    global $xoopsUser;
    //
    $objs =& $this->getObjects($criteria);
    //
    $ary = array();
    //
    foreach($objs as $obj){
      $ary[] = array( 'id'              => $obj->getVar('id'),
                      'first_name'      => $obj->getVar('first_name'),
                      'last_name'       => $obj->getVar('last_name'),
                      'street_address1' => $obj->getVar('street_address1'),
                      'street_address2' => $obj->getVar('street_address2'),
                      'town'            => $obj->getVar('town'),
                      'state'           => $obj->getVar('state'),
                      'zip'             => $obj->getVar('zip'),
                      'tel_no'          => $obj->getVar('tel_no'),
                      'fax_no'          => $obj->getVar('fax_no'),
                      'email'           => $xoopsUser->email() );
    }

    return $ary;
  }
  ///////////////////////////////////////////////////
  function validatePost($post) {

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
      $sql = sprintf( 'INSERT INTO %s ( id, uid, zone_id, country_id, first_name, last_name, street_address1,
                                       street_address2, town, state, zip, tel_no, fax_no, company_name,
                                       company_reg, vat_no, client_type)
                       VALUES (%u, %u, %u, %u, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %u)',
                      $this->_db->prefix($this->_dbtable),  $id, $uid, $zone_id, $country_id,
                      $this->_db->quoteString($first_name),
                      $this->_db->quoteString($last_name), $this->_db->quoteString($street_address1),
                      $this->_db->quoteString($street_address2), $this->_db->quoteString($town),
                      $this->_db->quoteString($state), $this->_db->quoteString($zip),
                      $this->_db->quoteString($tel_no), $this->_db->quoteString($fax_no),
                      $this->_db->quoteString($company_name), $this->_db->quoteString($company_reg),
                      $this->_db->quoteString($vat_no), $client_type );
    } else {
        $sql = sprintf( 'UPDATE %s SET uid = %u, zone_id = %u, country_id = %u, first_name = %s, last_name = %s,
                         street_address1 = %s, street_address2 = %s, town = %s, state = %s, zip = %s, tel_no = %s,
                         fax_no = %s, company_name = %s, company_reg = %s, vat_no = %s,
                         client_type = %u where id = %u',
                        $this->_db->prefix($this->_dbtable), $uid, $zone_id, $country_id,
                        $this->_db->quoteString($first_name),
                        $this->_db->quoteString($last_name), $this->_db->quoteString($street_address1),
                        $this->_db->quoteString($street_address2), $this->_db->quoteString($town),
                        $this->_db->quoteString($state), $this->_db->quoteString($zip),
                        $this->_db->quoteString($tel_no), $this->_db->quoteString($fax_no),
                        $this->_db->quoteString($company_name), $this->_db->quoteString($company_reg),
                        $this->_db->quoteString($vat_no), $client_type, $id);
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

    return true;
  }
}
