<?php

require_once('xassetBaseObject.php');

class xassetTaxZone extends XAssetBaseObject {

  function xassetTaxZone($id = null) {
    $this->initVar('id', XOBJ_DTYPE_INT, null, false);
    $this->initVar('region_id', XOBJ_DTYPE_TXTBOX, null, false, 30);
    $this->initVar('zone_id', XOBJ_DTYPE_INT, null, false);
    $this->initVar('country_id', XOBJ_DTYPE_INT, null, false);
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


class xassetTaxZoneHandler extends xassetBaseObjectHandler {
  //vars
  var $_db;
  var $classname = 'xassettaxzone';
  var $_dbtable  = 'xasset_tax_zone';
  //cons
  function xassetTaxZoneHandler(&$db)
  {
    $this->_db = $db;
  }
  ///////////////////////////////////////////////////
  function &getInstance(&$db)
  {
      static $instance;
      if(!isset($instance)) {
          $instance = new xassetTaxZoneHandler($db);
      }
      return $instance;
  }
  ///////////////////////////////////////////////////
  function getSelectArray($criteria = null) {
    if (!isset($criteria)) {
      $criteria   = new CriteriaCompo();
      $criteria->setSort('name'); }
    //
    $objs =& $this->getObjects($criteria);
    //
    $ar = array();
    //
    foreach($objs as $obj) {
      $ar[$obj->getVar('id')] = $obj->getVar('name');
    }
    return $ar;
  }
  ///////////////////////////////////////////////////
  function getAllTaxZoneArray() {
    global $imagearray;
    //
    $criteria   = new CriteriaCompo();
    $criteria->setSort('region');
    //
    return $this->getArray($criteria);
  }
  ///////////////////////////////////////////////////
  function getArray($criteria) {
    global $imagearray;
    //
    $hRegion  =& xoops_getmodulehandler('region','xasset');
    $hZone    =& xoops_getmodulehandler('zone','xasset');
    $hCountry =& xoops_getmodulehandler('country','xasset');
    //
    $thisTable    = $this->_db->prefix($this->_dbtable);
    $regionTable  = $this->_db->prefix($hRegion->_dbtable);
    $zoneTable    = $this->_db->prefix($hZone->_dbtable);
    $countryTable = $this->_db->prefix($hCountry->_dbtable);
    //
    $sql = "select r.region, tz.id, tz.region_id, tz.country_id, tz.zone_id, c.name country, z.code zone
            from $thisTable tz inner join $regionTable r on
              tz.region_id = r.id inner join $countryTable c on
              tz.country_id = c.id left join $zoneTable z on
              tz.zone_id    = z.id";
    //
    $this->postProcessSQL($sql, $criteria);
    //
    $ary = array();
    //
    if ($res = $this->_db->query($sql)) {
      while ($row = $this->_db->fetchArray($res)) {
        $actions = '<a href="main.php?op=editTaxZone&id='.$row['id'].'">'.$imagearray['editimg'].'</a>' .
                   '<a href="main.php?op=deleteTaxZone&id='.$row['id'].'">'.$imagearray['deleteimg'].'</a>';
        //
        $row['zone'] == '' ? $zone = 'All Zones' : $zone = $row['zone'];
        //
        $ary[] = array( 'id'         => $row['id'],
                        'region'     => $row['region'],
                        'country'    => $row['country'],
                        'zone'       => $zone,
                        'region_id'  => $row['region_id'],
                        'country_id' => $row['country_id'],
                        'zone_id'    => $row['zone_id'],
                        'actions'    => $actions );
      }
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
      $sql = sprintf( 'INSERT INTO %s (id, region_id, zone_id, country_id)
                       VALUES (%u, %u, %u, %u)',
                      $this->_db->prefix($this->_dbtable),  $id, $region_id, $zone_id, $country_id);
    } else {
        $sql = sprintf('UPDATE %s SET region_id = %u, zone_id = %u, country_id = %u where id = %u',
                        $this->_db->prefix($this->_dbtable), $region_id, $zone_id, $country_id, $id);
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

?>
