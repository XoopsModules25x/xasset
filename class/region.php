<?php

require_once('xassetBaseObject.php');

class xassetRegion extends XAssetBaseObject {

  function xassetRegion($id = null) {
    $this->initVar('id', XOBJ_DTYPE_INT, null, false);
    $this->initVar('region', XOBJ_DTYPE_TXTBOX, null, false, 30);
    $this->initVar('description', XOBJ_DTYPE_TXTBOX, null, false, 200);
    //
    if (isset($id)) {
      if (is_array($id)) {
        $this->assignVars($id);
      }
    } else {
      $this->setNew();
    }
  }
}

class xassetRegionHandler extends xassetBaseObjectHandler {
  //vars
  var $_db;
  var $classname = 'xassetregion';
  var $_dbtable  = 'xasset_region';
  //cons
  function xassetRegionHandler(&$db)
  {
    $this->_db = $db;
  }
  ///////////////////////////////////////////////////
  function &getInstance(&$db)
  {
      static $instance;
      if(!isset($instance)) {
          $instance = new xassetRegionHandler($db);
      }

      return $instance;
  }
  ///////////////////////////////////////////////////
  function getSelectArray($criteria = null) {
    if (!isset($criteria)) {
      $criteria   = new CriteriaCompo();
      $criteria->setSort('region'); }
    //
    $objs = $this->getObjects($criteria);
    //
    $ar = array();
    //
    foreach($objs as $obj) {
      $ar[$obj->getVar('id')] = sprintf('%s - %s',$obj->getVar('region'), $obj->getVar('description'));
    }

    return $ar;
  }
  ///////////////////////////////////////////////////
  function getRegionArray($criteria = null) {
    global $imagearray;
    //
    if (!isset($criteria)) {
      $criteria   = new CriteriaCompo();
      $criteria->setSort('region'); }
    //
    $objs  = $this->getObjects($criteria);
    $ary   = array();
    //
    foreach($objs as $obj){
      $actions = '<a href="main.php?op=editRegion&id='.$obj->getVar('id').'">'.$imagearray['editimg'].'</a>' .
                 '<a href="main.php?op=deleteRegion&id='.$obj->getVar('id').'">'.$imagearray['deleteimg'].'</a>';
      //
      $ary[] = array( 'id'          => $obj->getVar('id'),
                      'region'      => $obj->getVar('region'),
                      'description' => $obj->getVar('description'),
                      'actions'     => $actions);
    }

    return $ary;
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
      $sql = sprintf( 'INSERT INTO %s (id, region, description)
                       VALUES (%u, %s, %s)',
                      $this->_db->prefix($this->_dbtable),  $id, $this->_db->quoteString($region), $this->_db->quoteString($description));
    } else {
        $sql = sprintf('UPDATE %s SET region = %s, description = %s where id = %u',
                        $this->_db->prefix($this->_dbtable), $this->_db->quoteString($region), $this->_db->quoteString($description), $id);
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
