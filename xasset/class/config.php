<?php

require_once('xassetBaseObject.php');

Class xassetConfig extends XoopsObject {

  //cons
  function xassetConfig($id = null)
  {
    $this->initVar('id', XOBJ_DTYPE_INT, null, false);
    $this->initVar('dkey', XOBJ_DTYPE_TXTBOX, null, true, 50);
    $this->initVar('dvalue', XOBJ_DTYPE_TXTBOX, null, true, 100);

    if (isset($id)) {
      if (is_array($id)) {
        $this->assignVars($id);
      }
    } else {
      $this->setNew();
    }
  }
}


Class xassetConfigHandler extends xassetBaseObjectHandler {
  //vars
  var $_db;
  var $classname = 'xassetconfig';
  var $_dbtable = 'xasset_config';
	//cons
  function xassetConfigHandler(&$db)
  {
    $this->_db = $db;
  }
  ///////////////////////////////////////////////////
  function &getInstance(&$db)
  {
      static $instance;
      if(!isset($instance)) {
          $instance = new xassetConfigHandler($db);
      }
      return $instance;
  }
  ///////////////////////////////////////////////////
  function setValue($key, $value) {
		$objs =& $this->getObjects(new Criteria('dkey',$key));
		if (count($objs) > 0) {
			$obj =& $this->get($objs[0]->getVar('id'));
			$obj->setVar('dvalue',$value);
    } else {
      $obj =& $this->create();
      $obj->setVar('dkey',$key);
      $obj->setVar('dvalue',$value);
    }
    return $this->insert($obj);
  }
  ///////////////////////////////////////////////////
  function getValueObj($key) {
    return $this->getObjects(new Criteria('dkey', $key),true);
  }
  ///////////////////////////////////////////////////
  function getValueValue($key) {
    $objs =& $this->getObjects(new Criteria('dkey', $key),true);
    if (count($objs) == 1) {
      foreach($objs as $obj) {
        return $obj->getVar('dvalue');
        exit;
			}
    } else {
			return $this->getValueArray($key);
    }
  }
  ///////////////////////////////////////////////////
  function getValueArray($key) {
    $objs =& $this->getObjects(new Criteria('dkey',$key));
    //
    $ary = array();
    foreach($objs as $obj) {
      $ary[] = $obj->getVar('dvalue');
    }
    return $ary;
  }
  ////////////////////////////////////////////////////
  function getConfigArray() {
    $ary['group_id']          = $this->getGroup();
    $ary['email_group_id']    = $this->getEmailGroup();
    $ary['currency_id']       = $this->getBaseCurrency();
    //
    return $ary;
  }
  ////////////////////////////////////////////////////
  ////////////////////////////////////////////////////////////////////////////////////
  function getGroup() {
    return $this->getValueValue('group_id');
  }
  function setGroup($value) {
		return $this->setValue('group_id',$value);
  }
  ///////////////////////////////////////////////////
  function getEmailGroup() {
    return $this->getValueValue('email_group_id');
  }
  function setEmailGroup($value) {
		return $this->setValue('email_group_id',$value);
  }
  ///////////////////////////////////////////////////
  function getBaseCurrency() {
		$id = $this->getValueValue('currency_id'); 
		if ($id > 0) {
			return $id;
    } else {
      return false;
    }
  }
  ///////////////////////////////////////////////////
	function setBaseCurrency($value) {
		return $this->setValue('currency_id',$value);
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
      $sql = sprintf( 'INSERT INTO %s (id, dkey, dvalue) VALUES (%u, %s, %s)',
                      $this->_db->prefix($this->_dbtable),  $id, $this->_db->quoteString($dkey),
                      $this->_db->quoteString($dvalue));
    } else {
				$sql = sprintf('UPDATE %s SET dkey = %s, dvalue = %s  where id = %u',
												$this->_db->prefix($this->_dbtable), $this->_db->quoteString($dkey),
												$this->_db->quoteString($dvalue), $id);
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
