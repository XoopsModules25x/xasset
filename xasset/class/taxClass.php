<?php

require_once('xassetBaseObject.php');

class xassetTaxClass extends XoopsObject {

  function xassetTaxClass($id = null) {
    $this->initVar('id', XOBJ_DTYPE_INT, null, false);
    $this->initVar('code', XOBJ_DTYPE_TXTBOX, null, false, 20);
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

class xassetTaxClassHandler extends xassetBaseObjectHandler {
  //vars
  var $_db;
  var $classname = 'xassettaxclass';
  var $_dbtable  = 'xasset_tax_class';
  //cons
  function xassetTaxClassHandler(&$db)
  {
    $this->_db = $db;
  }
  ///////////////////////////////////////////////////
  function &getInstance(&$db)
  {
      static $instance;
      if(!isset($instance)) {
          $instance = new xassetTaxClassHandler($db);
      }

      return $instance;
  }
  ///////////////////////////////////////////////////
  function getSelectArray($criteria = null) {
    if (!isset($criteria)) {
      $criteria   = new CriteriaCompo();
      $criteria->setSort('description'); }
    //
    $objs =& $this->getObjects($criteria);
    //
    $ar = array();
    //
    foreach($objs as $obj) {
      $ar[$obj->getVar('id')] = sprintf('%s - %s',$obj->getVar('code'), $obj->getVar('description'));
    }

    return $ar;
  }
  ///////////////////////////////////////////////////
  function getClassArray($criteria = null) {
    global $imagearray;
    //
    if (!isset($criteria)) {
      $criteria   = new CriteriaCompo();
      $criteria->setSort('description'); }
    //
    $objs  =& $this->getObjects($criteria);
    $ary   = array();
    //
    foreach($objs as $obj){
      $actions = '<a href="main.php?op=editTaxClass&id='.$obj->getVar('id').'">'.$imagearray['editimg'].'</a>' .
                 '<a href="main.php?op=deleteTaxClass&id='.$obj->getVar('id').'">'.$imagearray['deleteimg'].'</a>';
      //
      $ary[] = array( 'id'          => $obj->getVar('id'),
                      'code'        => $obj->getVar('code'),
                      'description' => $obj->getVar('description'),
                      'actions'     => $actions);
    }

    return $ary;
  }
  ///////////////////////////////////////////////////
  function deleteClass($id) {
    //first delete all rates with this class
    $hRate =& xoops_getmodulehandler('taxRate','xasset');
    if ($hRate->deleteByClass($id, true)) {
      //delete class itself
      return $this->deleteByID($id, true);
    } else {
      return false;
    }
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
      $sql = sprintf( 'INSERT INTO %s (id, code, description)
                       VALUES (%u, %s, %s)',
                      $this->_db->prefix($this->_dbtable),  $id, $this->_db->quoteString($code), $this->_db->quoteString($description));
    } else {
        $sql = sprintf('UPDATE %s SET code = %s, description = %s where id = %u',
                        $this->_db->prefix($this->_dbtable), $this->_db->quoteString($code), $this->_db->quoteString($description), $id);
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
