<?php

require_once('xassetBaseObject.php');
require_once('crypt.php');

class xassetApplicationGroup extends XAssetBaseObject {
  var $weight;
  //
  function xassetApplicationGroup($id = null) {
    $this->initVar('id', XOBJ_DTYPE_INT, null, false);
    $this->initVar('application_id', XOBJ_DTYPE_INT, null, false);
    $this->initVar('group_id', XOBJ_DTYPE_INT, null, false);
    //
    $this->weight = 17;
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
  function &getApplication() {
    $app =& xoops_modulehandler('application','xasset');

    return $app->get($this->getVar('applicationid'));
  }
}

class xassetApplicationGroupHandler extends xassetBaseObjectHandler {
  //vars
  var $_db;
  var $classname = 'xassetapplicationgroup';
  var $_dbtable  = 'xasset_application_groups';
  //cons
  function xassetApplicationGroupHandler(&$db)
  {
    $this->_db = $db;
  }
  ///////////////////////////////////////////////////
  function &getInstance(&$db)
  {
      static $instance;
      if(!isset($instance)) {
          $instance = new xassetApplicationGroupHandler($db);
      }

      return $instance;
  }
  ///////////////////////////////////////////////////
  function getGroupIDArray($appID) {
    $crit = new Criteria('application_id',$appID);
    $objs = $this->getObjects($crit);
    //
    $ar = array();
    foreach($objs as $key=>$obj) {
      $ar[$obj->getVar('group_id')] = true;
    }
    //
    return $ar;
  }
  ///////////////////////////////////////////////////
  function &getAppObjectsByUID($uid) {
    $hMember =& xoops_gethandler('member');
    $aGroups = $hMember->getGroupsByUser($uid);
    //filter this if user is register and member of full group then remove the registered group
//    if (count($aGroups)>1) {
//      $tmp = array();
//      foreach($aGroups as $key=>$grp) {
//        if ($grp <> XOOPS_GROUP_USERS) {
//          $tmp[] = $grp;
//        }
//      }
//      $aGroups = $tmp;
//    }
    $crit = new CriteriaCompo();
    //
    if ($uid>0) {
      for($i=0;$i<count($aGroups);$i++) {
        if ($i == 0) {
          $crit->add(new Criteria('group_id',$aGroups[$i]));
        } else {
          $crit->add(new Criteria('group_id',$aGroups[$i]),'or');
        }
      }
    } else {
      $crit->add(new Criteria('group_id',XOOPS_GROUP_ANONYMOUS));
    }
    //
    return $this->getObjects($crit);
  }
  ///////////////////////////////////////////////////
  function getCBGroupString($appID = 0) {
    $hMember =& xoops_gethandler('member');
    //
    $groups  = $hMember->getGroups();
    //
    if ($appID > 0) {
      $aAppGroups = $this->getGroupIDArray($appID);
    } else {
      $aAppGroups = array();
    }
    $grps = '';
    //
    foreach($groups as $group) {
      $name = $group->getVar('name');
      $grpid   = $group->getVar('groupid');
      if (isset($aAppGroups[$group->getVar('groupid')])) {
        $checked = 'checked=checked';
      } else {
        $checked = '';
      }
      //
      $grps .= "<input name='cb[]' type='checkbox' value='$grpid' $checked> $name";
    }

    return $grps;
  }
  ///////////////////////////////////////////////////
  function updateGroup($appID, $aGrps=null) {
    $table = $this->_db->prefix($this->_dbtable);
    $sql = "delete from $table where application_id = $appID";
    //
    $this->_db->queryF($sql);
    //
    if (isset($aGrps)) {
      foreach($aGrps as $key=>$groupid) {
        $grp =& $this->create();
        $grp->setVar('application_id',$appID);
        $grp->setVar('group_id',$groupid);
        //
        $this->insert($grp);
      }
    }
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
      $sql = sprintf( 'INSERT INTO %s (id, application_id, group_id)
                       VALUES (%u, %u, %u)',
                      $this->_db->prefix($this->_dbtable),  $id, $application_id, $group_id);
    } else {
        $sql = sprintf('UPDATE %s SET application_id = %u, group_id = %uwhere id = %u',
                        $this->_db->prefix($this->_dbtable), $application_id, $group_id, $id);
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
