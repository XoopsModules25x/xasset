<?php

require_once('xassetBaseObject.php');
require_once('crypt.php');

class xassetPackageGroup extends xassetBaseObject {
  var $weight;
  //
  function xassetPackageGroup($id = null) {
    $this->initVar('id', XOBJ_DTYPE_INT, null, false);
    $this->initVar('applicationid', XOBJ_DTYPE_INT, null, false);
    $this->initVar('name', XOBJ_DTYPE_TXTBOX, null, false, 50);
    $this->initVar('grpDesc', XOBJ_DTYPE_TXTBOX, null, false, 255);
    $this->initVar('version', XOBJ_DTYPE_TXTBOX, null, false, 10);
    $this->initVar('datePublished', XOBJ_DTYPE_INT, time(), false );
    //
    $this->weight = 3;
    //
    if (isset($id)) {
      if (is_array($id)) {
        $this->assignVars($id);
      }
    } else {
      $this->setNew();
    }
  }
  /////////////////////////////////////////////
  function datePublished($format='l'){
    if ($this->getVar('datePublished') > 0) {
      return formatTimestamp($this->getVar('datePublished'), $format); }
    else {
      return '';
    }
  }
  //////////////////////////////////////////
  function getPackages() {
    $arr = array();
    //
    $id  = intval($this->getVar('id'));
    if (!$id) {
        return arr;
    }
    //
    $hPackages =& xoops_getmodulehandler('package','xasset');
    //
    $crit = new CriteriaCompo(new Criteria('packagegroupid', $id));
    $crit->setSort('filename');
    //
    $arr      =& $hPackages->getObjects($crit);
    //
    return $arr;
  }
  //////////////////////////////////////////
  function getApplication() {
    $hApp =& xoops_getmodulehandler('application', 'xasset');

    return $hApp->get($this->getVar('applicationid'));
  }
}

class xassetPackageGroupHandler extends xassetBaseObjectHandler {
  //vars
  var $_db;
  var $classname = 'xassetpackagegroup';
  var $_dbtable  = 'xasset_packagegroup';
  //cons
  function xassetPackageGroupHandler(&$db)
  {
    $this->_db = $db;
  }
  //////////////////////////////////////////////////////
  function getPackageGroupArray($criteria) {
    if (!isset($criteria)) {
      $criteria   = new CriteriaCompo();
      $criteria->setSort('name');
    }
    //
    $objs =& $this->getObjects($criteria,true);
    $crypt = new xassetCrypt();
    $ar   = array();
    //
    $i = 0;
    foreach($objs as $obj) {
      $ar[$i] = $obj->getArray();
      $ar[$i]['datePublished'] = $obj->datePublished();
      $ar[$i]['cryptKey'] = $crypt->cryptValue($obj->getVar('id'),$obj->weight);
      //
      $i++;
    }

    return $ar;
  }
  ///////////////////////////////////////////////////
  function getApplicationGroupPackages($appid) {
    $crit = new CriteriaCompo(new Criteria('applicationid', $appid));
    $crit->setSort('name');
    //
    return $this->getApplicationGroupArray($crit);
  }
  ///////////////////////////////////////////////////
  function getApplicationGroupAllPackages() {
    return $this->getApplicationGroupArray();
  }
  ///////////////////////////////////////////////////
  function getPackageGroup($id) {
    $crit = new CriteriaCompo(new Criteria('id', $id));
    //
    return $this->getApplicationGroupArray($crit);
  }
  ///////////////////////////////////////////////////
  function getApplicationGroupArray($crit = null) {
    global $imagearray;
    //
    //$crit = new CriteriaCompo(new Criteria('applicationid', $appid));
    //$crit->setSort('name');
    //
    $hPack =& xoops_getmodulehandler('package','xasset');
    //
    $objs = $this->getPackageGroupArray($crit);
    //
    for($i=0;$i<count($objs);$i++) {
      $action = '<a href="main.php?op=editPackageGroup&id='.$objs[$i]['id'].'&appid='.$objs[$i]['applicationid'].'">'.$imagearray['editimg'].'</a>' .
                '<a href="main.php?op=deletePackageGroup&id='.$objs[$i]['id'].'">'.$imagearray['deleteimg'].'</a>';
      //
      $objs[$i]['actions'] = $action;
      //now need to get the packages
      $objs[$i]['packages'] = $hPack->getGroupPackagesArray( $objs[$i]['id'] );
    }

    return $objs;
  }
  ///////////////////////////////////////////////////
  function &getAllGroupsSelectArray() {
    $crit = new CriteriaCompo();
    $crit->setSort('name');
    //
    $ary[0] = 'None';
    $ary = $ary + $this->getGroupsSelectArray($crit);
    //
    return $ary;
  }
  ///////////////////////////////////////////////////
  function getApplicationGroupsSelect($appid) {
    $crit = new CriteriaCompo(new Criteria('applicationid', $appid));
    $crit->setSort('name');
    //
    return $this->getGroupsSelectArray($crit);
  }
  ///////////////////////////////////////////////////
  function getGroupsSelectArray($crit) {
    $objs =& $this->getObjects($crit);
    $ar   = array();
    //
    foreach($objs as $obj) {
      $ar[$obj->getVar('id')] = $obj->getVar('name');
    }

    return $ar;
  }
  ///////////////////////////////////////////////////
  function getDownloadByApplicationSummaryArray($appid) {
    $crit = new CriteriaCompo(new Criteria('applicationid',$appid));

    return $this->getDownloadSummaryArray($crit);
  }
  ///////////////////////////////////////////////////
  function getDownloadSummaryArray($crit=null) {
    if (!isset($crit)) {
      $crit = new CriteriaCompo();
      $crit->setSort('id');
    }
    //
    $hPack  =& xoops_getmodulehandler('package','xasset');
    //
    $objs =& $this->getObjects($crit);
    $ary  = array();
    //
    foreach($objs as $obj){
      $packs   = $hPack->getDownloadSummaryByPackageGroupArray($obj->getVar('id'));
      $ary[]   = array('id'          => $obj->getVar('id'),
                       'name'        => $obj->getVar('name'),
                       'grpDesc'     => $obj->getVar('grpDesc'),
                       'packages'    => $packs);
    }

    return $ary;
  }
  ///////////////////////////////////////////////////
  function &getInstance(&$db)
  {
      static $instance;
      if(!isset($instance)) {
          $instance = new xassetPackageGroupHandler($db);
      }

      return $instance;
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
      $sql = sprintf('INSERT INTO %s (id, applicationid, name, grpDesc, version, datePublished) VALUES (%u, %u, %s, %s, %s, %u)',
                      $this->_db->prefix($this->_dbtable), $id, $applicationid, $this->_db->quoteString($name), $this->_db->quoteString($grpDesc), $this->_db->quoteString($version), $datePublished);
    } else {
        $sql = sprintf('UPDATE %s SET applicationid = %u, name = %s, grpDesc = %s, version = %s, datePublished = %u where id = %u',
                        $this->_db->prefix($this->_dbtable), $applicationid, $this->_db->quoteString($name), $this->_db->quoteString($grpDesc), $this->_db->quoteString($version), $datePublished, $id);
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
