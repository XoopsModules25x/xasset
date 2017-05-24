<?php

require_once('xassetBaseObject.php');

class xassetCurrency extends XAssetBaseObject {

  function xassetCurrency($id = null) {
    $this->initVar('id', XOBJ_DTYPE_INT, null, false);
    $this->initVar('name', XOBJ_DTYPE_TXTBOX, null, false, 30);
    $this->initVar('code', XOBJ_DTYPE_TXTBOX, null, false, 3);
    $this->initVar('decimal_places', XOBJ_DTYPE_INT, 2, false);
    $this->initVar('symbol_left', XOBJ_DTYPE_TXTBOX, null, false, 10);
    $this->initVar('symbol_right', XOBJ_DTYPE_TXTBOX, null, false, 10);
    $this->initVar('decimal_point', XOBJ_DTYPE_TXTBOX, '.', false, 1);
    $this->initVar('thousands_point', XOBJ_DTYPE_TXTBOX, ',', false, 1);
    $this->initVar('value', XOBJ_DTYPE_OTHER, null, false);
    $this->initVar('enabled', XOBJ_DTYPE_INT, 1, false);
    $this->initVar('updated', XOBJ_DTYPE_INT, null, false);
    //
    if (isset($id)) {
      if (is_array($id)) {
        $this->assignVars($id);
      }
    } else {
      $this->setNew();
    }
  }
  ////////////////////////////////////////
  function lastUpdated($format='l') {
    if ($this->getVar('updated') > 0) {
      return formatTimestamp($this->getVar('updated'), $format);}
    else {
      return '';
    }
  }
  ////////////////////////////////////////
  function valueFormat($value) {
    $val = $this->getVar('symbol_left') .
           number_format( $this->getVar('value') * $value, $this->getVar('decimal_places'),
                          $this->getVar('decimal_point'), $this->getVar('thousands_point') ) .
           $this->getVar('symbol_right');
    //
    return $val;
  }
  ////////////////////////////////////////
  function valueOnlyFormat($value){
    $val = number_format( $this->getVar('value') * $value, $this->getVar('decimal_places'),
                          $this->getVar('decimal_point'), $this->getVar('thousands_point') );
    //
    return $val;
  }
  ////////////////////////////////////////
  function bConvert($value) {
    return $this->getVar('value') * $value * 100;
  }
  ////////////////////////////////////////
  function value() {
    return $this->getVar('value');
  }
}

class xassetCurrencyHandler extends xassetBaseObjectHandler {
  //vars
  var $_db;
  var $classname = 'xassetcurrency';
  var $_dbtable  = 'xasset_currency';
  //cons
  function xassetCurrencyHandler(&$db)
  {
    $this->_db = $db;
  }
  ///////////////////////////////////////////////////
  function &getInstance(&$db)
  {
      static $instance;
      if(!isset($instance)) {
          $instance = new xassetCurrencyHandler($db);
      }

      return $instance;
  }
  ///////////////////////////////////////////////////
  function getSelectArray($criteria = null) {
    if (!isset($criteria)) {
      $criteria   = new CriteriaCompo();
      $criteria->setSort('name'); }
    //
    $criteria->add(new Criteria('enabled',1));
    //
    $objs = $this->getObjects($criteria);
    //
    $ar = array();
    //
    foreach($objs as $obj) {
      $ar[$obj->getVar('id')] = sprintf('%s - %s',$obj->getVar('code'), $obj->getVar('name'));
    }

    return $ar;
  }
  ///////////////////////////////////////////////////
  function getCurrencyArray($criteria = null) {
    global $imagearray;
    //
    if (!isset($criteria)) {
      $criteria   = new CriteriaCompo();
      $criteria->setSort('name'); }
    $criteria->add(new Criteria('enabled',1));
    //
    $objs  = $this->getObjects($criteria);
    $ary   = array();
    $i     = 0;
    //
    foreach($objs as $obj){
      $actions = '<a href="main.php?op=editCurrency&id='.$obj->getVar('id').'">'.$imagearray['viewlic'].'</a>' .
                 '<a href="main.php?op=deleteCurrency&id='.$obj->getVar('id').'">'.$imagearray['deleteimg'].'</a>';
      //
      $ary[$i] = $obj->getArray();
      $ary[$i]['actions'] = $actions;
      $ary[$i]['updatedFmt'] = formattimestamp($obj->getVar('updated'),'s');
      $i++;
    }

    return $ary;
  }
  ///////////////////////////////////////////////////
  function &getByCode($code) {
    $crit = new Criteria('code',$code);
    $objs = $this->getObjects($crit);
    if (count($objs) > 0) {
      return current($objs);
    } else {
      $res = false;

      return $res;
    }
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
  function formatCurrency($sLeft, $sRight, $point, $places, $thousand, $curValue, $value) {
    $val = $sLeft .
           number_format( $curValue * $value, $places,
                          $point, $thousand ) .
           $sRight;
    //
    return $val;
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
      $sql = sprintf( 'INSERT INTO %s (id, name, code, decimal_places, symbol_left, symbol_right, decimal_point, thousands_point, value, updated)
                       VALUES (%u, %s, %s, %u, %s, %s, %s, %s, %f, %u)',
                      $this->_db->prefix($this->_dbtable),  $id, $this->_db->quoteString($name),
                      $this->_db->quoteString($code), $decimal_places, $this->_db->quoteString($symbol_left),
                      $this->_db->quoteString($symbol_right), $this->_db->quoteString($decimal_point),
                      $this->_db->quoteString($thousands_point), $value, $updated);
    } else {
        $sql = sprintf('UPDATE %s SET name = %s, code = %s, decimal_places = %u, symbol_left = %s, symbol_right = %s, decimal_point = %s, thousands_point = %s, value = %f, updated = %u  where id = %u',
                        $this->_db->prefix($this->_dbtable), $this->_db->quoteString($name),
                        $this->_db->quoteString($code), $decimal_places, $this->_db->quoteString($symbol_left),
                        $this->_db->quoteString($symbol_right), $this->_db->quoteString($decimal_point),
                        $this->_db->quoteString($thousands_point), $value, $updated, $id);
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
