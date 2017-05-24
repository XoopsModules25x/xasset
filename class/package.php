<?php

require_once('xassetBaseObject.php');
require_once('crypt.php');

class xassetPackage extends xassetBaseObject {
  var $weight;
  //
  function xassetPackage($id = null) {
    $this->initVar('id', XOBJ_DTYPE_INT, null, false);
    $this->initVar('packagegroupid', XOBJ_DTYPE_INT, null, false);
    $this->initVar('filename', XOBJ_DTYPE_TXTBOX, null, false, 70);
    $this->initVar('filesize', XOBJ_DTYPE_INT, null, false);
    $this->initVar('filetype', XOBJ_DTYPE_TXTBOX, null, false, 10);
    $this->initVar('serverFilePath', XOBJ_DTYPE_TXTBOX, null, false, 255);
    $this->initVar('protected', XOBJ_DTYPE_INT, 1, false);
    $this->initVar('isVideo', XOBJ_DTYPE_INT, 0, false);
    //
    $this->weight = 917;
    //
    if (isset($id)) {
      if (is_array($id)) {
        $this->assignVars($id);
      }
    } else {
      $this->setNew();
    }
  }
  //////////////////////////////////////////////////
  function filePath() {
    return $this->getVar('serverFilePath');
  }
  /////////////////////////////////////////////////
  function fileProtected() {
    return $this->getVar('protected') == 1;
  }
  /////////////////////////////////////////////////
  function fileSize($force = true) {
    if ($force) {
      return filesize($this->filePath());
    } else {
      return $this->getVar('filesize');
    }
  }
  ///////////////////////////////////////////////////
  function getPackageStats() {
    $arr = array();
    //
    $id  = intval($this->getVar('id'));
    if (!$id) {
        return arr;
    }
    //
    $hPackStats =& xoops_getmodulehandler('userPackageStats','xasset');
    //
    $crit = new CriteriaCompo(new Criteria('packageid', $id));
    $crit->setSort('date');
    //
    $arr      = $hPackStats->getObjects($crit);
    //
    return $arr;
  }
  ///////////////////////////////////////////////////
  function getTotalDownloads() {
    $hPackStats =& xoops_getmodulehandler('userPackageStats','xasset');
    $crit = new CriteriaCompo(new Criteria('packageid', $this->ID()));
    //
    return $hPackStats = $hPackStats->getCount($crit);
  }
  ///////////////////////////////////////////////////
  function secureDownloadFile() {
    global $xoopsUser;
    //
    $hClient =& xoops_getmodulehandler('userDetails','xasset');
    //
    if ($xoopsUser) {
      if ($oClient =& $hClient->getUserDetailByID($xoopsUser->uid())) {
        //this is the user.... check if this user has access to this file
        if ($oClient->canDownloadPackage($this->ID(),$error)) {
          $this->downloadFile();
        }  else {
          redirect_header('index.php',5,$error);
        }
      }
    } else {
      redirect_header(XOOPS_URL.'/user.php',5,'Not Logged In.');
    }
  }
  ///////////////////////////////////////////////////
  function incrementDownload() {
    $hStats =& xoops_getmodulehandler('userPackageStats','xasset');
    $hStats->logPackageDownload($this);
  }
  ///////////////////////////////////////////////////
  function downloadFile() {
    global $HTTP_USER_AGENT, $xoopsUser, $xoopsModule;
    //
    $file_saved   = $this->getVar('serverFilePath');
    $file_display = $this->getVar('filename');
    //
    if ( $this->getVar('filetype') <> '')   {
      if (substr_count($file_display,'.') == 0) {
        $file_display = $this->getVar('filename').'.'.$this->getVar('filetype');
      }
    } else if (substr(strrchr($file_display,'.'),1) <> '') { //file extension in file_display itself
      $this->setVar('filetype',strtolower(substr(strrchr($file_display,'.'),1)) );
    }
    //
    $fileSize     = filesize($file_saved);
    //now log the fact that the file has been downloaded
//    $hStats       =& xoops_getmodulehandler('userPackageStats','xasset');
    //now get mime type based on extension
    $mimetype     = 'application/x-download';
    @$extensionToMime = include( XOOPS_ROOT_PATH . '/class/mimetypes.inc.php' );
    if ($this->getVar('filetype') <> '') {
      if (isset( $extensionToMime[$this->getVar('filetype')] ) ) {
        $mimetype     = $extensionToMime[$this->getVar('filetype')];
      }
    }
    $this->incrementDownload();
//    if ($xoopsUser) {
//      $uid = $xoopsUser->getVar('uid'); }
//     else {
//      $uid = 0;
//     }
     //
//    $stats  =& $hStats->create();
//    $stats->setVar('packageid',$this->getVar('id'));
//    $stats->setVar('uid',$uid);
//    $stats->setVar('ip',getenv("REMOTE_ADDR"));
//    $stats->setVar('dns',gethostbyaddr(getenv('REMOTE_ADDR'))); ;
//    $stats->setVar('date',time());
//    $hStats->insert($stats,true);
    //
    if (!headers_sent($filename, $linenum) ) {
      header('Content-Type: '.$mimetype);
      header('Content-Length: ' . $fileSize);
      header('Expires: 0');
      header('Content-Disposition: attachment; filename="'.$file_display.'"');
      if (preg_match("/MSIE ([0-9]\.[0-9]{1,2})/", $HTTP_USER_AGENT)) {
        header('Cache-Control: must-revalidate, post-check=0, pre-check=0');
        header('Pragma: public');
      } else {
        header('Pragma: no-cache');
      }
      readfile($file_saved);
    } else {
      echo "header output started in file: $filename at line number : $linenum. Cannot download file.";
    }
  }
  /////////////////////////////////////////////////
  function &getPackageGroup() {
    $hGrp =& xoops_getmodulehandler('packageGroup','xasset');

    return $hGrp->get($this->getVar('packagegroupid'));
  }
  ///////////////////////////////////////////////
  function getKey() {
    $crypt = new xassetCrypt();

    return $crypt->cryptValue($this->getVar('id'),$this->weight);
  }

}

class xassetPackageHandler extends xassetBaseObjectHandler {
  //vars
  var $_db;
  var $classname = 'xassetpackage';
  var $_dbtable  = 'xasset_package';
  var $_weight   = 917;
  //cons
  function xassetPackageHandler(&$db)
  {
    $this->_db = $db;
  }
  ///////////////////////////////////////////////////
  function getPackagesArray($criteria) {
    if (!isset($criteria)) {
      $criteria   = new CriteriaCompo();
      $criteria->setSort('filename');
    }
    //
    $objs = $this->getObjects($criteria,true);
    $ar   = array();
    //
    foreach($objs as $obj) {
      $ar[] = $obj->getArray();
    }

    return $ar;
  }
  ///////////////////////////////////////////////////
  function getDownloadSummaryByPackageGroupArray($groupid) {
    $crit = new CriteriaCompo(new Criteria('packagegroupid',$groupid));
    $crit->setSort('filename');
    //
    return $this->getDownloadSummary($crit);
  }
  ///////////////////////////////////////////////////
  function getDownloadSummary($crit){
    $hStats =& xoops_getmodulehandler('userPackageStats','xasset');
    //
    $objs = $this->getObjects($crit);
    $ary  = array();
    //
    foreach($objs as $obj) {
      $stats = $hStats->getDownloadStatsByPackageArray($obj->getVar('id'));
      $ary[] = array('id'        => $obj->getVar('id'),
                     'filename'  => $obj->getVar('filename'),
                     'downloads' => $stats,
                     'count'     => count($stats) );
    }

    return $ary;
  }
  ///////////////////////////////////////////////////
  function getGroupPackagesArray($groupID) {
    global $imagearray;
    //
    $crit = new CriteriaCompo(new Criteria('packagegroupid', $groupID));
    $crit->setSort('filename');
    //
    $objs  = $this->getObjects($crit);
    $crypt = new xassetCrypt();
    $ar    = array();
    $i     = 0;
    //
    foreach($objs as $obj){
      $action = '<a href="main.php?op=editPackage&id='.$obj->getVar('id').'">'.$imagearray['editimg'].'</a>' .
                '<a href="main.php?op=deletePackage&id='.$obj->getVar('id').'">'.$imagearray['deleteimg'].'</a>'.
                '<a href="'.XOOPS_URL.'/modules/xasset/index.php?op=downloadPack&packid='.$obj->getVar('id').'&key='.$crypt->cryptValue($obj->getVar('id'),$obj->weight).'">'.$imagearray['online'].'</a>';
      if ($obj->getVar('protected') > 0) {
        $action .= $imagearray['protected'];
      }
      //
      $ar[$i] = $obj->getArray();
      $ar[$i]['actions']   = $action;
      $ar[$i]['cryptKey']  = $crypt->cryptValue($obj->getVar('id'),$obj->weight);
      $i++;
    }

    return $ar;
  }
  ///////////////////////////////////////////////////
  function getAllPackagesCount(){
    return $this->getCount();
  }
  ///////////////////////////////////////////////////
  function getPackageApplication($packID) {
    $hGrp    =& xoops_getmodulehandler('packageGroup','xasset');
    $hApp    =& xoops_getmodulehandler('application','xasset');
    //
    $thisTable = $this->_db->prefix($this->_dbtable);
    $grpTable  = $this->_db->prefix($hGrp->_dbtable);
    $appTable  = $this->_db->prefix($hApp->_dbtable);
    //
    $sql       = "select a.id from $thisTable p inner join $grpTable g on
                    p.packagegroupid = g.id inner join $appTable a on
                    g.applicationid  = a.id where p.id = $packID";
    //
    if ($res = $this->_db->query($sql)) {
      if ($row = $this->_db->fetcharray($res)) {
        return $row['id'];
      } else {
        return false;
      }
    } else {
      return false;
    }
  }
  ///////////////////////////////////////////////////
  function &getProductSamplePackages($oAppProduct) {
    $crit = new CriteriaCompo(new Criteria('packagegroupid', $oAppProduct->sampleGroupID()));
    $objs = $this->getObjects($crit);
    //
    if (count($objs) > 0) {
      return $objs;
    } else {
      $objs = false;

      return $objs;
    }
  }
  ///////////////////////////////////////////////////
  function &getInstance(&$db)
  {
      static $instance;
      if(!isset($instance)) {
          $instance = new xassetPackageHandler($db);
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
      $sql = sprintf('INSERT INTO %s (id, packagegroupid, filename, filesize, filetype, serverFilePath, protected, isVideo)
                      VALUES (%u, %u, %s, %u, %s, %s, %u, %u)',
                      $this->_db->prefix($this->_dbtable),  $id, $packagegroupid, $this->_db->quoteString($filename),
                      $filesize, $this->_db->quoteString($filetype), $this->_db->quoteString($serverFilePath),
                      $protected, $isVideo);
    } else {
        $sql = sprintf('UPDATE %s SET packagegroupid = %u, filename = %s, filesize = %u, filetype = %s,
                        serverFilePath = %s, protected = %u, isVideo = %u where id = %u',
                        $this->_db->prefix($this->_dbtable), $packagegroupid, $this->_db->quoteString($filename),
                        $filesize, $this->_db->quoteString($filetype), $this->_db->quoteString($serverFilePath),
                        $protected, $isVideo, $id);
    }

    // Update DB
    if (false != $force) {
      $result = $this->_db->queryF($sql);
    } else {
      $result = $this->_db->query($sql);
    }

    if (!$result) {
      print_r($this->_db);

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
