<?php

require_once('xassetBaseObject.php');
require_once('crypt.php');

class xassetLicense extends XAssetBaseObject {
  var $weight;
  //
  function xassetLicense($id = null) {
    $this->initVar('id', XOBJ_DTYPE_INT, null, false);
    $this->initVar('uid', XOBJ_DTYPE_INT, null, false);
    $this->initVar('applicationid', XOBJ_DTYPE_INT, null, false);
    $this->initVar('authKey', XOBJ_DTYPE_TXTBOX, null, true, 50);
    $this->initVar('authCode', XOBJ_DTYPE_TXTBOX, null, true, 100);
    $this->initVar('expires', XOBJ_DTYPE_LTIME, null, false);
    $this->initVar('dateIssued', XOBJ_DTYPE_INT, time(), false);
    //
    $this->weight = 2;
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
  function expires($format='l') {
    if ($this->getVar('expires') > 0) {
      return formatTimestamp($this->getVar('expires'), $format);}
    else {
      return '';
    }
  }
  ////////////////////////////////////////////
  function dateIssued($format='l') {
    if ($this->getVar('dateIssued') > 0) {
      return formatTimestamp($this->getVar('dateIssued'), $format); }
    else {
      return '';
    }
  }
  ////////////////////////////////////////////
  function &getApplication() {
    $hApp =& xoops_getmodulehandler('application', 'xasset');

    return $hApp->get($this->getVar('applicationid'));
  }
}

class xassetLicenseHandler extends xassetBaseObjectHandler {
  //vars
  var $_db;
  var $classname = 'xassetlicense';
  var $_dbtable  = 'xasset_license';
  //cons
  function xassetLicenseHandler(&$db)
  {
    $this->_db = $db;
  }
  ///////////////////////////////////////////////////
  function userIsLicensed($uid, $packid) {
    //we have a package id.. find the appid
    $hPack    =& xoops_getmodulehandler('package','xasset');
    $hGrp     =& xoops_getmodulehandler('packageGroup','xasset');
    //
    $packTable = $this->_db->prefix($hPack->_dbtable);
    $grpTable  = $this->_db->prefix($hGrp->_dbtable);
    //
    $sql      = "select grp.applicationid  from $grpTable grp inner join $packTable pack on
                   pack.packagegroupid = grp.id and pack.id = $packid";
    //
    $result = false;
    //
    if ($res = $this->_db->query($sql)) {
      if ($row = $this->_db->fetcharray($res)) {
        $appid = $row['applicationid'];
        //now get a valid license
        $hLic   =& xoops_getmodulehandler('license','xasset');
        $licTable = $this->_db->prefix($hLic->_dbtable);
        //
        $sql    = "select count(*) cnt from $licTable
                   where applicationid = $appid and uid = $uid and expires >= ".time();
        if ($res = $this->_db->query($sql)) {
          if ($row = $this->_db->fetcharray($res)) {
            $result = $row['cnt'] > 0;
          }
        }
      }
    }

    return $result;
  }
  ///////////////////////////////////////////////////
  function getLicensesArray($criteria) {
    global $imagearray;
    //
    $objs = $this->getObjects($criteria,true);
    $ary = array();
    //
    foreach($objs as $obj){
      $actions = '<a href="main.php?op=viewLicense&id='.$obj->getVar('id').'">'.$imagearray['viewlic'].'</a>' .
                 '<a href="main.php?op=editLicense&id='.$obj->getVar('id').'">'.$imagearray['editimg'].'</a>' .
                 '<a href="main.php?op=deleteLicense&id='.$obj->getVar('id').'">'.$imagearray['deleteimg'].'</a>';

      $ary[] = array( 'id'              => $obj->getVar('id'),
                      'name'            => $obj->getVar('name'),
                      'description'     => $obj->getVar('description'),
                      'platform'        => $obj->getVar('version'),
                      'version'         => $obj->getVar('version'),
                      'datePublished'   => $obj->datePublished(),
                      'expires'         => $obj->expires(),
                      'requiresLicense' => $obj->getVar('requiresLicense'),
                      'actions'         => $actions );
    }
    if (count($ary) > 0)  return $ary;
  }
  ///////////////////////////////////////////////////
  function getLicenseSummary() {
    global $imagearray;
    //
    $hLic     =& xoops_getmodulehandler('license','xasset');
    $hApp     =& xoops_getmodulehandler('application','xasset');
    //
    $appTable = $this->_db->prefix($hApp->_dbtable);
    $licTable = $this->_db->prefix($hLic->_dbtable);
    //
    $sql = "select app.id, app.name, count(distinct lic.uid) licenses
            from $appTable app inner join $licTable lic on
              app.id = lic.applicationid
            group by app.id, app.name
            order by app.name";
    //
    $ar = array();
    if ($res = $this->_db->query($sql)) {
      while ($row = $this->_db->fetchArray($res)) {
        $actions = '<a href="index.php?op=viewAppLicenses&id='.$row['id'].'">'.$imagearray['viewlic'].'</a>';/* .
                   '<a href="index.php?op=editLicenses&id='.$row['id'].'">'.$imagearray['editimg'].'</a>' .
                   '<a href="index.php?op=deleteLicense&id='.$row['id'].'">'.$imagearray['deleteimg'].'</a>';  */
        //
        $ar[] = array( 'id'       => $row['id'],
                                   'name'     => '<a href="index.php?op=viewAppLicenses&id='.$row['id'].'">'.$row['name'].'</a>',
                       'licenses' => '<a href="index.php?op=viewAppLicenses&id='.$row['id'].'">'.$row['licenses'].'</a>',
                       'actions'  => $actions );
      }
    }

    return $ar;
  }
  ///////////////////////////////////////////////////
  function getAppLicenses($appid) {
    global $imagearray;
    //
    $userTable = $this->_db->prefix('users');
    $licTable  = $this->_db->prefix('xasset_license');
    //
    $sql = "select u.uid, u.name, count(lic.id) licenses
            from $userTable u inner join $licTable lic on
              u.uid = lic.uid where lic.applicationid = $appid
            group by u.uid, u.name
            order by u.name";
    //
    //
    $ar = array();
    if ($res = $this->_db->query($sql)) {
      while ($row = $this->_db->fetchArray($res)) {
        $actions = '<a href="index.php?op=viewClientLicenses&id='.$row['uid'].'&appid='.$appid.'">'.$imagearray['viewlic'].'</a>';/* .
                   '<a href="index.php?op=editLicenses&id='.$row['id'].'">'.$imagearray['editimg'].'</a>' .
                   '<a href="index.php?op=deleteLicense&id='.$row['id'].'">'.$imagearray['deleteimg'].'</a>';  */
        //
        $ar[] = array( 'uid'       => $row['uid'],
                       'uname'     => '<a href="index.php?op=viewClientLicenses&id='.$row['uid'].'&appid='.$appid.'">'.$row['name'].'</a>',
                       'licenses' => $row['licenses'],
                       'actions'  => $actions );
      }
    }

    return $ar;
  }
  ///////////////////////////////////////////////////
  function getEvalApplicationsArray($uid) {
    $hApp       =& xoops_getmodulehandler('application','xasset');
    $crypt      = new xassetCrypt();
    //
    $appTable   = $this->_db->prefix('xasset_application');
    $licTable   = $this->_db->prefix($this->_dbtable);
    //
    $sql = "select distinct a.id appid, a.name appname, a.datePublished  from $appTable a left join $licTable l on
              a.id = l.applicationid WHERE ((l.uid <> $uid) or (uid is null)) AND a.listInEval = 1";
    //echo $sql;
    //
    $ar  = array();
    $app =& $hApp->create();
    //
    if ($res = $this->_db->query($sql)) {
      while ($row = $this->_db->fetcharray($res)) {
        $ar[]  = array('appid'                => $row['appid'],
                       'appname'              => $row['appname'],
                       'datePublished'        => formatTimestamp($row['datePublished'], 'l'),
                       'cryptKey'             => $crypt->cryptValue($row['appid'],$app->weight));
      }
    }
    //
    return $ar;
  }
  ///////////////////////////////////////////////////
  function getUserApplicationArray($uid, $crit = null) {
    $criteria = new CriteriaCompo(new Criteria('l.uid', $uid));
    $criteria->setSort('a.name');
    if (isset($crit)) $criteria->add($crit);
    //
    return $this->getApplicationArray($crit);
  }
  ///////////////////////////////////////////////////
  function getApplicationArray($crit){
    $hApp       =& xoops_getmodulehandler('application','xasset');
    $crypt      = new xassetCrypt();
    //
    $appTable   = $this->_db->prefix('xasset_application');
    $licTable   = $this->_db->prefix($this->_dbtable);
    //
    $sql = "select distinct a.id appid, a.name appname, a.datePublished, a.richDescription
            from $appTable a inner join $licTable l on
              a.id = l.applicationid";
    $this->postProcessSQL($sql,$crit);
    //
    $ar  = array();
    $app =& $hApp->create();
    //
    if ($res = $this->_db->query($sql)) {
      while ($row = $this->_db->fetcharray($res)) {
        $ar[]  = array('appid'                => $row['appid'],
                       'appname'              => $row['appname'],
                       'datePublished'        => formatTimestamp($row['datePublished'], 'l'),
                       'richDescription'      => $row['richDescription'],
                       'cryptKey'             => $crypt->cryptValue($row['appid'],$app->weight));
      }
    }
    //
    return $ar;
  }
  ///////////////////////////////////////////////////
  function getLicenseArray($crit) {
    global $imagearray;
    //
    $appTable   = $this->_db->prefix('xasset_application');
    $userTable  = $this->_db->prefix('users');
    $licTable   = $this->_db->prefix($this->_dbtable);
    //
    $sql       = "select u.uid, u.uname, a.name, lic.* from $licTable lic inner join $userTable u on
                    lic.uid = u.uid inner join $appTable a on
                    lic.applicationid = a.id ";
    $this->postProcessSQL($sql,$crit);
    //
    $ar = array();
    //
    if ($res = $this->_db->query($sql)) {
      while($row = $this->_db->fetcharray($res)) {
        $appid   = $row['applicationid'];
        $actions = '<a href="main.php?op=viewClientLicenses&id='.$row['uid'].'&appid='.$appid.'">'.$imagearray['viewlic'].'</a>' .
                   '<a href="main.php?op=editClientLicenses&id='.$row['uid'].'">'.$imagearray['editimg'].'</a>' .
                   '<a href="main.php?op=deleteClientLicense&id='.$row['uid'].'">'.$imagearray['deleteimg'].'</a>';
        //
        $ar[] = array( 'uid'       => '<a href="index.php?op=viewClientLicenses&id='.$row['uid'].'&appid='.$appid.'">'.$row['uid'].'</a>',
                       'uname'     => $row['uname'],
                       'licenses'  => $row['licenses'],
                       'actions'   => $actions );
      }
    }

    return $ar;
  }
  ///////////////////////////////////////////////////
  function getClientLicenses($appid, $clientid) {
    global $imagearray;
    //
    $crit = new CriteriaCompo(new Criteria('uid', $clientid));
    $crit->add(new Criteria('applicationid', $appid));
    //
    $lics =& $this->getObjects($crit);
    $ar   = array();
    //
    $crypt = new xassetCrypt();
    //
    foreach($lics as $lic){
      $actions = '<a href="main.php?op=editClientLicense&id='.$lic->getVar('id').'">'.$imagearray['editimg'].'</a>' .
                 '<a href="main.php?op=deleteClientLicense&id='.$lic->getVar('id').'">'.$imagearray['deleteimg'].'</a>';
      //
      $ar[] = array( 'id'         => $lic->getVar('id'),
                     'key'        => $lic->getVar('authKey'),
                     'code'       => $lic->getVar('authCode'),
                     'expires'    => $lic->expires('s'),
                     'dateIssued' => $lic->dateIssued(),
                     'cryptKey'   => $crypt->cryptValue($lic->getVar('id'),$lic->weight),
                     'actions'    => $actions );
    }

    return $ar;
  }
  ///////////////////////////////////////////////////
  function getAllLicensesCount(){
    return $this->getCount();
  }
  ///////////////////////////////////////////////////
  function &getInstance(&$db)
  {
      static $instance;
      if(!isset($instance)) {
          $instance = new xassetLicenseHandler($db);
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
      $sql = sprintf('INSERT INTO %s (id, applicationid, uid, authKey, authCode, expires, dateIssued) VALUES (%u, %u, %s, %s, %s, %u, %u)',
                      $this->_db->prefix($this->_dbtable), $id, $applicationid,  $uid, $this->_db->quoteString($authKey), $this->_db->quoteString($authCode), $expires, $dateIssued);
    } else {
        $sql = sprintf('UPDATE %s SET applicationid = %u, uid = %u, authKey = %s, authCode = %s, expires = %u, dateIssued = %u where id = %u',
                        $this->_db->prefix($this->_dbtable), $applicationid, $uid, $this->_db->quoteString($authKey), $this->_db->quoteString($authCode), $expires, $dateIssued, $id);
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
