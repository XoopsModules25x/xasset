<?php

require_once('xassetBaseObject.php');

class xassetGateway extends xassetBaseObject {

  function xassetGateway($id = null) {
    $this->initVar('id', XOBJ_DTYPE_INT, null, false);
    $this->initVar('code', XOBJ_DTYPE_TXTBOX, null, false, 20);
    $this->initVar('enabled', XOBJ_DTYPE_INT, 1, false);
    //
    if (isset($id)) {
      if (is_array($id)) {
        $this->assignVars($id);
      }
    } else {
      $this->setNew();
    }
  }
  ///////////////////////////////////////////////////
  function getDetails() {
		$hgDetail =& xoops_getmodulehandler('gatewayDetail','xasset');
    return $hgDetail->getByIndex($this->getVar('id'));
  }
  ///////////////////////////////////////////////////
  function getDetailArray() {
    $hgDetail =& xoops_getmodulehandler('gatewayDetail','xasset');
    return $hgDetail->getConfigArrayByIndex($this->getVar('id'));
  }
  ///////////////////////////////////////////////////
  function saveConfigValue($key, $value) {
    $hGDetail =& xoops_getmodulehandler('gatewayDetail','xasset');
    return $hGDetail->saveConfigValue($this->getVar('id'),$key,$value);
  }
  ///////////////////////////////////////////////////
  function toggleBinaryValues($values) {
    $hGDetail =& xoops_getmodulehandler('gatewayDetail','xasset');
    //
    $aDetail = $hGDetail->getBinaryConfigArrayByIndex($this->getVar('id'));
    //should have an array of binary fields... check if these exist in the post values array
    foreach($aDetail as $detail) {
      if (isset($values[$detail['gkey']])) {
        $hGDetail->saveConfigValue($this->getVar('id'),$detail['gkey'],true);
      } else {
        $hGDetail->saveConfigValue($this->getVar('id'),$detail['gkey'],false);
			}
    }
  }
}


class xassetGatewayHandler extends xassetBaseObjectHandler {
  //vars
  var $_db;
  var $classname = 'xassetgateway';
  var $_dbtable  = 'xasset_gateway';
  //cons
  function xassetGatewayHandler(&$db)
  {
    $this->_db = $db;
  }
  ///////////////////////////////////////////////////
  function &getInstance(&$db)
  {
      static $instance;
      if(!isset($instance)) {
          $instance = new xassetGatewayHandler($db);
      }
      return $instance;
  }
  ///////////////////////////////////////////////////
  function &getByCode($code){
    $crit = new Criteria('code',$code);
    $objs =& $this->getObjects($crit);
    //
    if (count($objs) == 0){
      $res = false;
      return $res;
		} else {
      $res = current($objs);
			return $res;
    }
  }
  ///////////////////////////////////////////////////
  function &getCodeID($code) {
    $crit = new Criteria('code',$code);
    $objs =& $this->getObjects($crit);
    //
    if (count($objs) == 0){
      $res = false;
      return $res;
    } else if (count($objs) > 0) {
      $obj = current($objs);
      return $obj->getVar('id');
    }
  }
  ///////////////////////////////////////////////////
  function getInstalledGatewayArray() {
    $crit = new Criteria('enabled',true);
    return $this->getGatewayArray($crit);
  }
  ///////////////////////////////////////////////////
  function getInstalledGatewayWithDescArray() {
    $ar = $this->getInstalledGatewayArray();
    for($i=0;$i<count($ar);$i++) {
      $gateway =& $this->getGatewayModuleByID($ar[$i]['id']);
      $ar[$i]['description'] = $gateway->description;
    }
    return $ar;
  }
  ///////////////////////////////////////////////////
  function getGatewayArray($crit = null) {
    global $xoopsModule; 
    // 
    $objs =& $this->getObjects($crit); 
    $ary  = array();
    foreach($objs as $obj) {
      $gateway =& $this->getGatewayModuleByID($obj->ID());
      if ($gateway->version() == $xoopsModule->getVar('version'))
        $ary[] = $obj->getArray();
    }
    return $ary;
  }
  ///////////////////////////////////////////////////
	function &getGatewayModuleByID($id) {
    $gateway =& $this->get($id);
    //
    $directory_array = $this->_getDirectoryListing();
    //
    for ($i=0, $n=sizeof($directory_array); $i<$n; $i++) {
      $file = $directory_array[$i]['file'];
      $class = substr($file, 0, strrpos($file, '.'));
      //
      if (is_object($gateway)) {
        if ($class == $gateway->getVar('code')) {
          require_once($directory_array[$i]['fullPath']);
          $module = new $class;
          if (!is_subclass_of($module,'baseGateway')) {
            unset($module);
          }
          break;
        }
      }
    }
    //
    if (isset($module)){
      return $module;
    } else {
      $res = false;
      return $res;
    }
  }
  ///////////////////////////////////////////////////
  function getGatewayFromPost($post, &$gateway) {
		$directory_array = $this->_getDirectoryListing();
		//
    for ($i=0, $n=sizeof($directory_array); $i<$n; $i++) {
      $file = $directory_array[$i]['file'];
      $class = substr($file, 0, strrpos($file, '.'));
      //
      require_once($directory_array[$i]['fullPath']);
      $module = new $class;
      if (is_subclass_of($module,'baseGateway')) {
        if ($orderID = $module->isThisGateway($post)) {
          $gateway = $module;
          return $orderID;
        } else {
          unset($module);
        }
      }
    }
    return false;
  }
  ///////////////////////////////////////////////////
  function parseGatewayModules() {
    global $xoopsModule; 
    //
		$directory_array = $this->_getDirectoryListing();  
		//include and process payment modules
    $installed_modules = array();
    for ($i=0, $n=sizeof($directory_array); $i<$n; $i++) {
      $file = $directory_array[$i]['file'];
      require_once($directory_array[$i]['fullPath']);
      //get class name based on file
      $class = substr($file, 0, strrpos($file, '.'));
      $module = new $class;
      //check if this class is a subclass of baseGateway
      if (is_subclass_of($module,'baseGateway') && ($xoopsModule->getVar('version') == $module->version())) {
        $installed_modules[] = array( 'id'        => $module->id,
                                      'class'     => $class,
                                      'file'      => $file,
                                      'filePath'  => $directory_array[$i]['fullPath'],
                                      'enabled'   => $module->enabled,
                                      'installed' => $module->installed,
                                      'shortDesc' => $module->shortDesc);
			}
      unset($module);
    }
    return $installed_modules;
  }
  ///////////////////////////////////////////////////
	function enableGateway($class) {
		return $this->switchGateway($class,true);
  }
  ///////////////////////////////////////////////////
  function disableGateway($class) {
		$this->switchGateway($class,false);
    //now delete the gateway record
    if ($obj =& $this->getByCode($class)) {
      //delete data
      $gate =& $this->getGatewayModuleByID($obj->getVar('id'));
      $gate->remove();
      //delete header
      $this->deleteByID($obj->getVar('id'));
    }
  }
  ///////////////////////////////////////////////////
	function switchGateway($class, $switch) {
    if ($obj =& $this->getByCode($class)) {
      $obj->setVar('enabled',$switch);
      return $this->insert($obj);
    } else {
      //could not find in tables... need to install?
      $this->parseGatewayModules();
      //
      $module = new $class;
      return $module->install();
    }
  }
  ///////////////////////////////////////////////////
  function postPaymentDetails($gateID) {
    $gateway =& $this->getGatewayModuleByID($gateID);
    //do we need more info?
    if ($gateway->preprocess()) 
      $gateway->doPreprocess();
    else
      $gateway->postToGateway();
  }
  ///////////////////////////////////////////////////
  function insert(&$obj, $force = false)
  {
    if (!parent::insert($obj, $force)){
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
      $sql = sprintf( 'INSERT INTO %s (id, code, enabled)
                       VALUES (%u, %s, %u)',
											$this->_db->prefix($this->_dbtable),  $id, $this->_db->quoteString($code), $enabled);
    } else {
        $sql = sprintf('UPDATE %s SET code = %s, enabled = %u where id = %u',
                        $this->_db->prefix($this->_dbtable), $this->_db->quoteString($code), $enabled, $id);
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
	///////////////////////////////////////////////////
	/*function _postToGateway($url, $fields) {
		$form = "<html>
						 <body onLoad='document.checkout.submit()'>
						 <form name='checkout' method='post' action='$url'> $fields </form>
						 </body>
						 </html>";
		echo $form;
	}        */
  ///////////////////////////////////////////////////
  function _getDirectoryListing() {
		global $PHP_SELF;
    //
		$file_extension   = '.php';//substr($PHP_SELF, strrpos($PHP_SELF, '.'));
		$module_directory = XASSET_CLASS_PATH .'/gateways/';
    //
    $directory_array = array();
    if ($dir = @dir($module_directory)) {
      while ($file = $dir->read()) {
        if (!is_dir($module_directory . $file)) {
          if (substr($file, strrpos($file, '.')) == $file_extension) {
            $directory_array[] = array( 'file'     => $file,
                                        'fullPath' => $module_directory . $file);
          }
        }
      }
      sort($directory_array);
      $dir->close();
    }
    return $directory_array;
  } 
}


?>
