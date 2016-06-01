<?php

require_once('xassetBaseObject.php');

class xassetCountry extends XoopsObject {

  function xassetCountry($id = null) {
    $this->initVar('id', XOBJ_DTYPE_INT, null, false);
    $this->initVar('name', XOBJ_DTYPE_TXTBOX, null, false, 75);
    $this->initVar('iso2', XOBJ_DTYPE_TXTBOX, null, false, 2);
    $this->initVar('iso3', XOBJ_DTYPE_TXTBOX, null, false, 3);
    //
    if (isset($id)) {
      if (is_array($id)) {
        $this->assignVars($id);
      }
    } else {
      $this->setNew();
    }
  }
  ////////////////////////////////////////////
  function hasZones() {
    $hZones =& xoops_getmodulehandler('zone','xasset');
    //
    $crit   = new Criteria('country_id',$this->getVar('id'));
    $objCnt = $hZones->getCount($crit);
    //
    return ($objCnt > 0);
  }
  ////////////////////////////////////////////
  function &getZones() {
    $hZones =& xoops_getmodulehandler('zone','xasset');
    //
    $crit   = new Criteria('country_id',$this->getVar('id'));
    return $hZones->getObjects($crit);
  }
  ////////////////////////////////////////////
  function &getZonesSelect() {
    $hZones =& xoops_getmodulehandler('zone','xasset');
    //
    return $hZones->getZonesByCountry($this->getVar('id'),false);
  }
}


class xassetCountryHandler extends xassetBaseObjectHandler {
  //vars
  var $_db;
  var $classname = 'xassetcountry';
  var $_dbtable  = 'xasset_country';
  //cons
  function xassetCountryHandler(&$db)
  {
    $this->_db = $db;
  }
  ///////////////////////////////////////////////////
  function &getInstance(&$db)
  {
      static $instance;
      if(!isset($instance)) {
          $instance = new xassetCountryHandler($db);
      }
      return $instance;
  }
  ///////////////////////////////////////////////////
  function getCountriesArray($criteria = null){
    global $imagearray;
    //
    $objs  =& $this->getObjects($criteria);
    $ary   = array();
    //
    foreach($objs as $obj){
      $actions = '<a href="main.php?op=editCountry&id='.$obj->getVar('id').'">'.$imagearray['editimg'].'</a>' .
                 '<a href="main.php?op=deleteCountry&id='.$obj->getVar('id').'">'.$imagearray['deleteimg'].'</a>';
      //
      $ary[] = array( 'id'        => $obj->getVar('id'),
                      'name'      => $obj->getVar('name'),
                      'iso2'      => $obj->getVar('iso2'),
                      'iso3'      => $obj->getVar('iso3'),
                      'actions'    => $actions);
    }
    return $ary;
  }
  ///////////////////////////////////////////////////
  function getCountriesSelect($criteria=null) {
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
  function constructSelectJavascript($zoneField, $countryField, $allZones = true) {
    $hZone =& xoops_getmodulehandler('zone','xasset');
    //
    $zones =& $hZone->getCountryZones();
    $start = true;
    $first = true;
    //
    $func = "function update_zones(theForm) {
               var NumState = theForm.$zoneField.options.length;
               var SelectedCountry = '';

               while(NumState > 0) {
                 NumState--;
                 theForm.$zoneField.options[NumState] = null;
              }

              SelectedCountry = theForm.$countryField.options[theForm.$countryField.selectedIndex].value;\n";

    foreach($zones as $country){
      $countryid = $country['id'];
      //
      if ($start){
        $func .= "  if (SelectedCountry == '$countryid') {\n";
        $start = false;
      } else {
        $func .= "}  else if (SelectedCountry == '$countryid') {\n";
      }
      //
      if ($allZones) {
        $func .= "theForm.$zoneField.options[0] = new Option('All Zones', '0');\n";
      }
      //
      if (isset($country['zones'])) {
        $func .= "if (theForm.state != null) {theForm.zone_id.style.display  = 'block';}\n";
        $func .= "if (theForm.state != null) {theForm.state.style.display    = 'none';}\n\n";
        $allZones ? $cnt = 1 : $cnt = 0;
        //
        foreach($country['zones'] as $zone) {
          $zoneid = $zone['id'];
          $zone   = $zone['name'];
          //
          $func .= "theForm.$zoneField.options[$cnt] = new Option('$zone', '$zoneid');\n";
          //
          $cnt++;
        }
      } else {
        $func .= "if (theForm.state != null) {theForm.state.style.display   = 'block';}\n";
        $func .= "if (theForm.state != null) {theForm.zone_id.style.display = 'none';}\n";
      }
    }
    if (strlen($func) > 0) {
      $func .= '} }';
      return $func;
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
      $sql = sprintf( 'INSERT INTO %s (id, name, iso2, iso3)
                       VALUES (%u, %s, %s, %s)',
                      $this->_db->prefix($this->_dbtable),  $id, $this->_db->quoteString($name), $this->_db->quoteString($iso2), $this->_db->quoteString($iso3));
    } else {
        $sql = sprintf('UPDATE %s SET name = %s, iso2 = %s, iso3 = %s where id = %u',
                        $this->_db->prefix($this->_dbtable), $this->_db->quoteString($name), $this->_db->quoteString($iso2), $this->_db->quoteString($iso3), $id);
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
