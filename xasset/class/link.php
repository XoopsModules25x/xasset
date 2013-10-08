<?php

require_once('xassetBaseObject.php');

class xassetLink extends XoopsObject {

  function xassetLink($id = null) {
    $this->initVar('id', XOBJ_DTYPE_INT, null, false);
    $this->initVar('applicationid', XOBJ_DTYPE_INT, null, true);
    $this->initVar('name', XOBJ_DTYPE_TXTBOX, null, false, 255);
    $this->initVar('link', XOBJ_DTYPE_TXTBOX, null, false, 255);
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
  function getApplication() {
    $hDept =& xoops_getmodulehandler('application', 'xasset');
    return $hDept->get($this->getVar('applicationid'));
  }
  ///////////////////////////////////////////////////
  function getLinkApplication() {
    $id = intval($uid);
    if(!$id){
        return false;
    }
    $hApplication =& xoops_getmodulehandler('application', 'xasset');
    $arr =& $hApplication->getObjects($this->getVar('applicationid'));
    //
    return $arr;
  }
}

class xassetLinkHandler extends xassetBaseObjectHandler {
  //vars
  var $_db;
  var $classname = 'xassetlink';
  var $_dbtable  = 'xasset_links';
  //cons
  function xassetLinkHandler(&$db)
  {
    $this->_db = $db;
  }
  ///////////////////////////////////////////////////
  function getApplicationLinks($appid) {
    $crit = new CriteriaCompo();
    $crit->add(new Criteria('applicationid', $appid));
    $crit->setSort('name');
    //
    return $this->getLinksArray($crit);
  }
  ///////////////////////////////////////////////////
  function getAllLinks() {
    return $this->getLinksArray();
  }
  ///////////////////////////////////////////////////
  function getLinksArray($crit = null){
    global $imagearray;
    //
    $appTable  = $this->_db->prefix('xasset_application');
    $linkTable = $this->_db->prefix($this->_dbtable);
    //
    $sql = "select l.*, a.name appname from $linkTable l inner join $appTable a on
             l.applicationid = a.id ";
    $this->postProcessSQL($sql,$crit);
    //
    $ary = array();
    //
    if ($res = $this->_db->query($sql)){
      while ($row = $this->_db->fetchArray($res)){
        $actions = '<a href="'.$row['link'].'">'.$imagearray['viewlic'].'</a>' .
                   '<a href="main.php?op=editLink&id='.$row['id'].'">'.$imagearray['editimg'].'</a>' .
                   '<a href="main.php?op=deleteLink&id='.$row['id'].'">'.$imagearray['deleteimg'].'</a>';

        $ary[] = array( 'id'             => $row['id'],
                        'applicationid'  => $row['applicationid'],
                        'appname'        => $row['appname'],
                        'name'           => $row['name'],
                        'link'           => '<a href="'.$row['link'].'">'.$row['name'].'</a>',
                        'actions'        => $actions );

      }
    }
    return $ary;
  }
  /////////////////////////////////////////////////////
  function getAllLinksCount() {
    return $this->getCount();
  }
  /////////////////////////////////////////////////////
  function &getInstance(&$db)
  {
      static $instance;
      if(!isset($instance)) {
          $instance = new xassetLinkHandler($db);
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
      $sql = sprintf('INSERT INTO %s (id, applicationid, name, link) VALUES (%u, %u, %s, %s)',
                      $this->_db->prefix($this->_dbtable),  $id, $applicationid, $this->_db->quoteString($name), $this->_db->quoteString($link));
    } else {
      $sql = sprintf('UPDATE %s SET applicationid = %u, name = %s, link = %s where id = %u',
                      $this->_db->prefix($this->_dbtable), $applicationid, $this->_db->quoteString($name), $this->_db->quoteString($link), $id);
    }

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
