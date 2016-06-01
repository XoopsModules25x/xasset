<?php

require_once('xassetBaseObject.php');
include_once('video/video.php');
//
class xassetVideo extends XAssetBaseObject {
  
  function streamVideo($file, $position) {
    $hCommon =& xoops_getmodulehandler('common','xasset');
    //
    $bandwidth  = $hCommon->getModuleOption('bandwidth');
    //
    $video = new Video();
    $video->setFile($file);
    $video->setBitrate($bandwidth*1024);
    $video->enableThrottle($hCommon->getModuleOption('Enablebandwidth') == 1);  
    $video->streamVideo($position);  
  }
  
}

class xassetVideoHandler extends xassetBaseObjectHandler {
  //
  var $_db;
  var $classname = 'xassetvideo';
  //
  function xassetVideoHandler(&$db)
  {
    //
    $this->_db = $db;
  }
  ///////////////////////////////////////////////////
  function &getInstance(&$db)
  {
    static $instance;
    if(!isset($instance)) {
        $instance = new xassetVideoHandler($db);
    }
    return $instance;
  }
  ///////////////////////////////////////////////////
  function &create() { //override
    $obj = new $this->classname();
    return $obj;
  }
  ///////////////////////////////////////////////////
  function pluginInstalled() {
    if (file_exists('video/video.php')) {
      return true;
    } else {
      return false;
    }
  }
  ///////////////////////////////////////////////////
  function getVideo($id, $token, $position = 0) { 
    $hPackage   =& xoops_getmodulehandler('package','xasset');
    $hUserDetail =& xoops_getmodulehandler('userDetails','xasset');
    $hCommon    =& xoops_getmodulehandler('common','xasset');
    //
    $uid      = $hCommon->pspDecrypt($token); 
    $oPackage =& $hPackage->get($id); 
    //
    if ($uid > 0) { //secure the video
      $oClient  =& $hUserDetail->getUserDetailByID($uid); 
      //
      $dummy = '';
      if ($oClient->canDownloadPackage($oPackage->ID(),$dummy) or (!$oPackage->fileProtected())) {
        $file = $oPackage->filePath();
        $video =& $this->create();
        $video->streamVideo($file,$position);
      }
    } else { //requested by anonymous uid... check if video is protected and stream if it is not
      if (!$oPackage->fileProtected()) {
        $file = $oPackage->filePath();
        $video =& $this->create();
        $video->streamVideo($file,$position);
      }
    }
  }
  /////////////////////////////////////////////
  function buildPlayer($id) {
    $movie_id = $id;
    $filesize = $this->getVideoSize($id);
  }
}

?>