<?php

require_once('xassetBaseObject.php');

class xassetZone extends XoopsObject {

  function xassetZone($id = null) {
    $this->initVar('id', XOBJ_DTYPE_INT, null, false);
    $this->initVar('country_id', XOBJ_DTYPE_INT, null, false);
    $this->initVar('code', XOBJ_DTYPE_TXTBOX, null, false, 20);
    $this->initVar('name', XOBJ_DTYPE_TXTBOX, null, false, 30);
    //
    if (isset($id)) {
      if (is_array($id)) {
        $this->assignVars($id);
      }
    } else {
      $this->setNew();
    }
  }
  /////////////////////////////////////////////////
  function &getCountry() {
    $country =& xoops_getmodulehandler('country','xasset');

    return $country->get($this->getVar('countryid'));
  }
}

class xassetZoneHandler extends xassetBaseObjectHandler {
  //vars
  var $_db;
  var $classname = 'xassetzone';
  var $_dbtable  = 'xasset_zone';
  //cons
  function xassetZoneHandler(&$db)
  {
    $this->_db = $db;
  }
  ///////////////////////////////////////////////////
  function &getInstance(&$db)
  {
      static $instance;
      if(!isset($instance)) {
          $instance = new xassetZoneHandler($db);
      }

      return $instance;
  }
  ///////////////////////////////////////////////////
  function &getCountryZones() {
    $hCountry =& xoops_getmodulehandler('country','xasset');
    //
    $thisTable = $this->_db->prefix($this->_dbtable);
    $countryTable = $this->_db->prefix($hCountry->_dbtable);
    //
    $sql = "select c.id, c.name, c.iso2, c.iso3, z.id zone_id, z.code zone_code, z.name zone_name
            from $countryTable c left join $thisTable z on
              c.id = z.country_id
            order by c.name";
    //
    if ($res = $this->_db->query($sql)) {
      $cntAry      = array();
      $zoneAry     = array();
      $lastCountry = '';
      //
      while ($row = $this->_db->fetchArray($res)) {
        if ($lastCountry != $row['name']) {
          //add zones array
          if ((count($zoneAry) > 0) && (count($cntAry) > 0)) {
            $cntAry[count($cntAry)-1]['zones'] = $zoneAry;
          }

          $cntAry[] = array('id'       => $row['id'],
                            'name'     => $row['name'],
                            'iso2'     => $row['iso2'],
                            'iso3'     => $row['iso3']);
          //
          unset($zoneAry);
          $zoneAry = array();
          //
          if ($row['zone_id'] > 0) {
            $zoneAry[] = array( 'id'        => $row['zone_id'],
                                'code'      => $row['zone_code'],
                                'name'      => $row['zone_name'] );
          }
          //
          $lastCountry = $row['name'];
        } else {
          $zoneAry[] = array( 'id'        => $row['zone_id'],
                              'code'      => $row['zone_code'],
                              'name'      => $row['zone_name'] );
        }
      }
      //add the last zone array to the country
      if ( (count($zoneAry) > 0) && (count($cntAry)>0) ) {
            $cntAry[count($cntAry)-1]['zones'] = $zoneAry;
      }

      return $cntAry;
    } else {
      $res = false;

      return $res;
    }
  }
  ///////////////////////////////////////////////////
  function getSelectArray($criteria = null, $allZones = true) {
    if (!isset($criteria)) {
      $criteria   = new CriteriaCompo();
      $criteria->setSort('code'); }
    //
    $objs = $this->getObjects($criteria);
    //
    $ar = array();
    if ($allZones) {
      $ar[0] = 'All Zones';
    }
    //
    foreach($objs as $obj) {
      //$ar[$obj->getVar('id')] = sprintf('%s - %s',$obj->getVar('code'),$obj->getVar('name'));
      $ar[$obj->getVar('id')] = $obj->getVar('name');
    }

    return $ar;
  }
  ///////////////////////////////////////////////////
  function &getZonesByCountry($countryID, $allZones=true) {
    $crit = new Criteria('country_id',$countryID);
    $crit->setSort('name');
    //
    $ary = $this->getSelectArray($crit,$allZones);
    //
    return $ary;
  }
  ///////////////////////////////////////////////////
  function &getZonesArray($criteria = null) {
    global $imagearray;
    //
    $objs  = $this->getObjects($criteria);
    $ary   = array();
    //
    $hCnt  =& xoops_getmodulehandler('country','xasset');
    //
    $thisTable = $this->_db->prefix($this->_dbtable);
    $cntTable  = $this->_db->prefix($hCnt->_dbtable);
    //
    $sql       = "select z.*, c.name country_name from $thisTable z inner join $cntTable c on
                    z.country_id = c.id";
    $this->postProcessSQL($sql, $criteria);
    //
    if ($res = $this->_db->query($sql)) {
      while ($row = $this->_db->fetcharray($res)) {
      $actions = '<a href="main.php?op=editZone&id='.$row['id'].'">'.$imagearray['editimg'].'</a>' .
                 '<a href="main.php?op=deleteZone&id='.$row['id'].'">'.$imagearray['deleteimg'].'</a>';
      //
      $ary[] = array( 'id'          => $row['id'],
                      'countryid'   => $row['country_id'],
                      'code'        => $row['code'],
                      'name'        => $row['name'],
                      'countryname' => $row['country_name'],
                      'actions'     => $actions);
      }
    }

    return $ary;
  }
  ///////////////////////////////////////////////////
  function getZoneNameByID($zoneID) {
    $crit = new Criteria('id',$zoneID);
    $objs = $this->getObjects($crit);
    if (count($objs) > 0) {
      $obj = reset($objs);

      return $obj->getVar('name');
    } else {
      return false;
    }
  }
  ///////////////////////////////////////////////////
  function zoneInCountry($countryID, $zoneID) {
    $crit = new CriteriaCompo(new Criteria('country_id',$countryID));
    $crit->add(new Criteria('id',$zoneID));
    //
    $objs = $this->getObjects($crit);
    //
    return count($objs) > 0;
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
      $sql = sprintf( 'INSERT INTO %s (id, country_id, code, name)
                       VALUES (%u, %u, %s, %s)',
                      $this->_db->prefix($this->_dbtable),  $id, $country_id, $this->_db->quoteString($code), $this->_db->quoteString($name));
    } else {
        $sql = sprintf('UPDATE %s SET country_id = %u, code = %s, name = %s where id = %u',
                        $this->_db->prefix($this->_dbtable), $country_id, $this->_db->quoteString($code), $this->_db->quoteString($name), $id);
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
