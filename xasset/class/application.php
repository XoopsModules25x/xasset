<?php

require_once('xassetBaseObject.php');
require_once('crypt.php');

class xassetApplication extends XAssetBaseObject {
  var $weight;
  //
  function xassetApplication($id = null) {
    $this->initVar('id', XOBJ_DTYPE_INT, null, false);                      // will store Xoops user id
    $this->initVar('name', XOBJ_DTYPE_TXTBOX, null, false, 50);
    $this->initVar('description', XOBJ_DTYPE_TXTBOX, null, false, 255);
    $this->initVar('platform', XOBJ_DTYPE_TXTBOX, null, false, 255);
    $this->initVar('version', XOBJ_DTYPE_TXTBOX, null, false, 10);
    $this->initVar('datePublished', XOBJ_DTYPE_INT, null, false, time());
    $this->initVar('requiresLicense', XOBJ_DTYPE_INT, null, false, 1);
    $this->initVar('listInEval', XOBJ_DTYPE_INT, null, false, 0);
    $this->initVar('hasSamples', XOBJ_DTYPE_INT, null, false, 0);
    $this->initVar('richDescription',XOBJ_DTYPE_TXTBOX, null, false, 64000);
    $this->initVar('mainMenu', XOBJ_DTYPE_INT, null, false, 0);
    $this->initVar('menuItem',XOBJ_DTYPE_TXTBOX, null, false, 20);
    $this->initVar('productsVisible', XOBJ_DTYPE_INT, 1, false, 0);
    $this->initVar('image', XOBJ_DTYPE_TXTBOX, null, false, 250);
    $this->initVar('product_list_template',XOBJ_DTYPE_TXTBOX, null, false, 64000);
    //
    $this->weight = 15;
    //
    if (isset($id)) {
      if (is_array($id)) {
        $this->assignVars($id);
      }
    } else {
      $this->setNew();
    }
  }
  ///////////////////////////////////////////////
  function datePublished($format='l') {
    if ($this->getVar('datePublished') > 0) {
      return formatTimestamp($this->getVar('datePublished'), $format);}
    else {
      return '';
    }
  }
  ///////////////////////////////////////////////
  function name() {
    return $this->getVar('name');
  }
  ///////////////////////////////////////////////
  function requiresLicense() {
    return $this->getVar('requiresLicense') == 1;
  }
  ///////////////////////////////////////////////
  function listInEval() {
    return $this->getVar('listInEval') == 1;
  }
  ///////////////////////////////////////////////
  function poductListTemplate() {
    return $this->getVar('product_list_template','n');
  }
  ///////////////////////////////////////////////
  function poductListPage() {
    global $xoopsTpl;
    //
    $template = $this->poductListTemplate();
    //
    $tpl = new XoopsTpl();
    $tpl->assign($xoopsTpl->get_template_vars());
    //
    return $tpl->xoops_fetchFromData($template);
  }
  ///////////////////////////////////////////////
  function getLicenses($uid) {
    $arr = array();
    //
    $id  = intval($this->getVar('id'));
    if (!$id) {
        return arr;
    }
    //
    if ($this->requiresLicense) {
      $hLicense =& xoops_getmodulehandler('license', 'xasset');
      //
      $crit = new CriteriaCompo();
      $crit->add(new Criteria('applicationid', $id));
      $crit->add(new Criteria('uid', $uid));
      $crit->setSort('datePublished');
      //
      $arr      =& $hLicense->getObjects($crit);
    }
    //
    return $arr;
  }
  ///////////////////////////////////////////////
  function getPackageGroups() {
    $arr = array();
    //
    $id  = intval($this->getVar('id'));
    if (!$id) {
        return arr;
    }
    //
    $hpackGroups =& xoops_getmodulehandler('packageGroup','xasset');
    //
    $crit = new CriteriaCompo(new Criteria('applicationid', $id));
    $crit->setSort('datePublished');
    //
    $arr      =& $hpackGroups->getObjects($crit);
    //
    return $arr;
  }
  function getLicenseCount() {
  }
  ///////////////////////////////////////////////
  function getDownloadCount() {
  }
  ///////////////////////////////////////////////
  function getKey() {
    $crypt = new xassetCrypt();

    return $crypt->cryptValue($this->getVar('id'),$this->weight);
  }
}

class xassetApplicationHandler extends xassetBaseObjectHandler {
  //vars
  var $_db;
  var $classname = 'xassetapplication';
  var $_dbtable  = 'xasset_application';
  var $_weight = 15;
  //cons
  function xassetApplicationHandler(&$db)
  {
    $this->_db = $db;
  }
  ///////////////////////////////////////////////////
  function getApplications($criteria){
    //
    $ret =& $this->getObjects($criteria,true);
    //
    return $ret;
  }
  ///////////////////////////////////////////////////
  function getEvalApplicationsArray() {
    $crit = new CriteriaCompo(new Criteria('listInEval',1));

    return $this->getApplicationsArray($crit);
  }
  ///////////////////////////////////////////////////
  function getApplicationsArray($criteria = null){
    global $imagearray;
    //
    $hAppProd =& xoops_getmodulehandler('applicationProduct','xasset');
    //
    $objs  = $this->getApplications($criteria);
    $crypt = new xassetCrypt();
    $ary   = array();
    $i     = 0;
    //
    foreach($objs as $obj){
      $actions = '<a href="main.php?op=viewAppLicenses&id='.$obj->getVar('id').'">'.$imagearray['viewlic'].'</a>' .
                 '<a href="main.php?op=editApplication&id='.$obj->getVar('id').'">'.$imagearray['editimg'].'</a>' .
                 '<a href="main.php?op=deleteApplication&id='.$obj->getVar('id').'">'.$imagearray['deleteimg'].'</a>';

      $ary[$i] = $obj->getArray();
      $ary[$i]['cryptKey']        = $crypt->cryptValue($obj->getVar('id'),$obj->weight);
      $ary[$i]['richDescription'] = parseConstants( $obj->getVar('richDescription','n'),'xasset');
      $ary[$i]['richDescription'] = $this->parseTokens($hAppProd->parseTokens($ary[$i]['richDescription'],$obj),$obj);
      $ary[$i]['actions']         = $actions;
      //
      $i++;
    }

    return $ary;
  }
  ///////////////////////////////////////////////////
  function getApplicationsSummaryArray($criteria = null) {
    global $imagearray;
    //
    $hLic        =& xoops_getmodulehandler('license','xasset');
    $hStats      =& xoops_getmodulehandler('userPackageStats','xasset');
    $hPack       =& xoops_getmodulehandler('package','xasset');
    $hPackGrp    =& xoops_getmodulehandler('packageGroup','xasset');
    $hAppProd    =& xoops_getmodulehandler('applicationProduct','xasset');
    //
    $appTable    = $this->_db->prefix($this->_dbtable);
    $licTable    = $this->_db->prefix($hLic->_dbtable);
    $stTable     = $this->_db->prefix($hStats->_dbtable);
    $pkTable     = $this->_db->prefix($hPack->_dbtable);
    $pgTable     = $this->_db->prefix($hPackGrp->_dbtable);
    //
    $sql         = "select a.id, a.name, count(distinct l.id) licenses, count(distinct s.id) downloads
                    from $appTable a left join $licTable l on
                      a.id = l.applicationid left join $pgTable pg on
                      a.id = pg.applicationid left join $pkTable p on
                      pg.id = p.packagegroupid left join $stTable s on
                      p.id = s.packageid ";
    //
    if (!isset($criteria)){
      $criteria = new CriteriaCompo();
    }
    $criteria->setGroupby('a.id, a.name');
    $this->postProcessSQL($sql,$criteria);
    //
    $crypt = new xassetCrypt();
    $ary   = array();
    //
    if ($res = $this->_db->query($sql)) {
      while ($row = $this->_db->fetcharray($res)) {
        $actions = /*'<a href="main.php?op=viewAppLicenses&id='.$row['id'].'">'.$imagearray['viewlic'].'</a>' . */
                   '<a href="main.php?op=editApplication&id='.$row['id'].'">'.$imagearray['editimg'].'</a>' .
                   '<a href="main.php?op=deleteApplication&id='.$row['id'].'">'.$imagearray['deleteimg'].'</a>';
        $products = $hAppProd->getAppProductArray($row['id'],null,true);
        //
        $ary[]    = array( 'id'          => $row['id'],
                           'name'        => $row['name'],
                           'licenses'    => $row['licenses'],
                           'downloads'   => $row['downloads'],
                           'products'    => $products,
                           'prodCount'   => count($products),
                           'actions'     => $actions );
      }
    }
    //
    return $ary;
  }
  ///////////////////////////////////////////////////
  function getApplicationSelectArray($criteria=null) {
    if (!isset($criteria)) {
      $criteria   = new CriteriaCompo();
      $criteria->setOrder('name'); }
    //
    $objs =& $this->getApplications($criteria);
    //
    $ar = array();
    //
    foreach($objs as $obj) {
      $ar[$obj->getVar('id')] = $obj->getVar('name');
    }
    if (count($ar) > 0) return $ar;
  }
  ///////////////////////////////////////////////////
  function getAllApplicationsCount() {
    return $this->getCount();
  }
  ///////////////////////////////////////////////////
  function deleteApplication($id) {
    return $this->deleteByID($id, true);
  }
  ///////////////////////////////////////////////////
  function &getApplicationMainMenuObjects($uid = false) {
    $hGroup =& xoops_getmodulehandler('applicationGroup','xasset');
    //
    if (!$uid) {
      global $xoopsUser;
      $uid = $xoopsUser ? $xoopsUser->uid() : XOOPS_GROUP_ANONYMOUS;
    }
    //
    $crit = new CriteriaCompo(new Criteria('mainMenu',1));
    $aGrps = $hGroup->getAppObjectsByUID($uid);
    //
    $subCrit = new CriteriaCompo();
    foreach($aGrps as $key=>$group) {
      if($key==0) {
        $subCrit->add(new Criteria('id',$group->getVar('application_id')));
      } else {
        $subCrit->add(new Criteria('id',$group->getVar('application_id'),'or'));
      }
    }
    $crit->add($subCrit);
    //
    $objs =& $this->getObjects($crit);
    //
    return $objs;
  }
  ///////////////////////////////////////////////////
  function getUserApplications($uid = false, $allApps = true) {
    $hGroup =& xoops_getmodulehandler('applicationGroup','xasset');
    //
    if (!$uid) {
      global $xoopsUser;
      $uid = $xoopsUser ? $xoopsUser->uid() : XOOPS_GROUP_ANONYMOUS;
    }
    //
    $crit = new CriteriaCompo();
    if (!$allApps)
      $crit->add(new Criteria('mainMenu',1));
    //
    $aGrps = $hGroup->getAppObjectsByUID($uid);
    //
    $subCrit = new CriteriaCompo();
    foreach($aGrps as $key=>$group) {
      if($key==0) {
        $subCrit->add(new Criteria('id',$group->getVar('application_id')));
      } else {
        $subCrit->add(new Criteria('id',$group->getVar('application_id'),'or'));
      }
    }
    $crit->add($subCrit);
    //
    $objs =& $this->getObjects($crit);
    //
    return $objs;
    
  }
  ///////////////////////////////////////////////////
  function cryptID($id) {
    $crypt = new xassetCrypt();

    return $crypt->cryptValue($id,$this->_weight);
  }
  ///////////////////////////////////////////////////
  function &getInstance(&$db)
  {
      static $instance;
      if(!isset($instance)) {
          $instance = new xassetApplicationHandler($db);
      }

      return $instance;
  }
  ///////////////////////////////////////////////////
  function parseTokens($body, $oApp) {
    if (preg_match_all('/{TAG.(.*?)}/', $body, $matches)) {
      //matchs will be of form {TAG.app.BUY(option)
      foreach($matches[1] as $key=>$match) {
        $replace = $matches[0][$key];
        //matches LIST tag
        if (!(strpos($match,'LIST') === false)) {
          $desc    = $oApp->poductListPage();
          $body = str_replace($replace,$desc,$body);
        }
      }
    }

    return $body;
  }
  ///////////////////////////////////////////////////
  function &getAppImages() {
    $thisTable =$this->_db->prefix($this->_dbtable);
    //
    $sql = "select id, name, image from $thisTable where image is not null";
    $objs =& $this->sqlToArray($sql,true);
    //
    foreach($objs as $key=>$oApp) {
      $ary[$key] = $oApp->getArray();
      $ary[$key]['key'] = $oApp->getKey();
    }

    return $ary;
  }
  ///////////////////////////////////////////////////
  function &seachApplication($terms, $andor, $limit, $offset, $userid) {
    $thisTable = $this->tableName();
    //
    $sql = "select * from $thisTable";
    $crit = new CriteriaCompo();
    //
    if (isset($userid) && ($userid > 0)) { echo $userid;

      return;
    }
    //
    foreach ($terms as $key=>$term) {
      $sub = new CriteriaCompo();
      $sub->add(new Criteria('name',"%$term%",'like'));
      $sub->add(new Criteria('description',"%$term%",'like'),'or');
      $sub->add(new Criteria('richDescription',"%$term%",'like'),'or');
      //
      $crit->add($sub,$andor);
      unset($sub);
    }
    $this->postProcessSQL($sql,$crit);
    $objs =& $this->sqlToArray($sql,true,$limit,$offset);
    //
    return $objs;
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
      $sql = sprintf('INSERT INTO %s (id, name, description, platform, version, datePublished, requiresLicense,
                                      listInEval, hasSamples, richDescription, mainMenu, menuItem, productsVisible,
                                      image, product_list_template)
                      VALUES (%u, %s, %s, %s, %s, %u, %u, %u, %u, %s, %u, %s, %u, %s, %s)',
                      $this->_db->prefix($this->_dbtable), $id, $this->_db->quoteString($name),
                      $this->_db->quoteString($description), $this->_db->quoteString($platform),
                      $this->_db->quoteString($version), $datePublished, $requiresLicense, $listInEval, $hasSamples,
                      $this->_db->quoteString($richDescription), $mainMenu, $this->_db->quoteString($menuItem), $productsVisible,
                      $this->_db->quoteString($image) , $this->_db->quoteString($product_list_template) );
    } else {
        $sql = sprintf('UPDATE %s SET name = %s, description = %s, platform = %s, version = %s, datePublished = %u,
                        requiresLicense = %u, listInEval = %u, hasSamples = %u, richDescription = %s, mainMenu = %u,
                        menuItem = %s, productsVisible = %u, image = %s, product_list_template = %s  where id = %u',
                        $this->_db->prefix($this->_dbtable), $this->_db->quoteString($name),
                        $this->_db->quoteString($description), $this->_db->quoteString($platform),
                        $this->_db->quoteString($version), $datePublished, $requiresLicense, $listInEval, $hasSamples,
                        $this->_db->quoteString($richDescription), $mainMenu,
                        $this->_db->quoteString($menuItem), $productsVisible,
                        $this->_db->quoteString($image), $this->_db->quoteString($product_list_template),  $id);
    }
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
