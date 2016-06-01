<?php

require_once('xassetBaseObject.php');

class xassetTaxRate extends XAssetBaseObject {

  function xassetTaxRate($id = null) {
    $this->initVar('id', XOBJ_DTYPE_INT, null, false);
    $this->initVar('region_id', XOBJ_DTYPE_INT, null, false);
    $this->initVar('tax_class_id', XOBJ_DTYPE_INT, null, false);
    $this->initVar('rate', XOBJ_DTYPE_OTHER, 0, false);
    $this->initVar('priority', XOBJ_DTYPE_INT, null, false);
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
  /////////////////////////////////////////
  function &getZone() {
    $zone =& xoops_getmodulehandler('zone','xasset');
    return $zone->get($this->getVar('zoneid'));
  }
  /////////////////////////////////////////
  function &getTaxClass() {
    $class =& xoops_modulehandler('taxClass','xasset');
    return $class->get($this->getVar('taxclassid','xasset'));
  }
}


class xassetTaxRateHandler extends xassetBaseObjectHandler {
  //vars
  var $_db;
  var $classname = 'xassettaxrate';
  var $_dbtable  = 'xasset_tax_rates';
  //cons
  function xassetTaxRateHandler(&$db)
  {
    $this->_db = $db;
  }
  ///////////////////////////////////////////////////
  function &getInstance(&$db)
  {
      static $instance;
      if(!isset($instance)) {
          $instance = new xassetTaxRateHandler($db);
      }
      return $instance;
  }
  ///////////////////////////////////////////////////
  function getRegionOrderedRatesArray() {
    $crit = new CriteriaCompo();
    $crit->setSort('region_id');
    //
    return $this->getRatesArray($crit);
  }
  ///////////////////////////////////////////////////
  function getRatesArray($criteria = null) {
    global $imagearray;
    //
    //$hZone   =& xoops_getmodulehandler('zone','xasset');
    $hRegion =& xoops_getmodulehandler('region','xasset');
    $hClass  =& xoops_getmodulehandler('taxClass','xasset');
    //
    $thisTable   = $this->_db->prefix($this->_dbtable);
    $classTable  = $this->_db->prefix($hClass->_dbtable);
    //$zoneTable  = $this->_db->prefix($hZone->_dbtable);
    $regionTable = $this->_db->prefix($hRegion->_dbtable);
    //
    $sql       = "select r.*, c.code class_code, g.region
                  from $thisTable r inner join $classTable c on
                    r.tax_class_id = c.id inner join $regionTable g on
                    r.region_id = g.id";
    $this->postProcessSQL($sql, $criteria);
    //
    $ary   = array();
    //
    if ($res = $this->_db->query($sql)) {
      while ($row = $this->_db->fetcharray($res)) {
      $actions = '<a href="main.php?op=editTaxRate&id='.$row['id'].'">'.$imagearray['editimg'].'</a>' .
                 '<a href="main.php?op=deleteTaxRate&id='.$row['id'].'">'.$imagearray['deleteimg'].'</a>';
      //
      $ary[] = array( 'id'           => $row['id'],
                      'tax_class_id' => $row['tax_class_id'],
                      'class_code'   => $row['class_code'],
                      'region'       => $row['region'],
                      'region_id'    => $row['region_id'],
                      'rate'         => $row['rate'],
                      'priority'     => $row['priority'],
                      'description'  => $row['description'],
                      'actions'      => $actions);
      }
    }
    return $ary;
  }
  ///////////////////////////////////////////////////
  function deleteByClass($id, $force = false) {
    $thisTable = $this->_db->prefix($this->_dbtable);
    $sql       = "delete from $thisTable where tax_class_id = $id";
    //
    return $this->_db->query($sql,$force);
  }
  ///////////////////////////////////////////////////
  function deleteRate($id, $force = false) {
    return $this->deleteByID($id,$force);
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
      $sql = sprintf( 'INSERT INTO %s (id, region_id, tax_class_id, rate, priority, description)
                       VALUES (%u, %u, %u, %f, %u, %s)',
											$this->_db->prefix($this->_dbtable),  $id, $region_id, $tax_class_id, $rate, $priority, $this->_db->quoteString($description));
    } else {
        $sql = sprintf('UPDATE %s SET region_id = %u, tax_class_id = %u, rate = %f, priority = %u, description = %s where id = %u',
                        $this->_db->prefix($this->_dbtable), $region_id, $tax_class_id, $rate, $priority, $this->_db->quoteString($description), $id);
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

?>
