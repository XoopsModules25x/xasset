<?php

require_once('xassetBaseObject.php');
require_once 'xajax/xajax.inc.php';

class xassetajax extends xajax {
  
  function xprojectajax() {
    parent::xajax();
  }
  //////////////////////////////////////////////////
  function getHeaderCode($location = 'class/xajax') {
    $cbScript = '<script type="text/javascript">
                  function addComboOption(selectId, txt, val)
                  {
                      var objOption = new Option(txt, val);
                      document.getElementById(selectId).options.add(objOption);
                  }
                  </script>';
    return parent::getJavascript($location).$cbScript;
  }
  /////////////////////////////////////////////////
  function registerFunction($function,$url = null) {
    if (isset($url))
      $this->sRequestURI = $url;
    //      
    parent::registerFunction($function,XAJAX_GET);
  }
}


class xassetajaxHandler extends xassetBaseObjectHandler {
  //vars
  var $_db;
  var $classname = 'xassetajax';
  var $_dbtable  = '';
  //cons
  function xassetajaxHandler(&$db)
  {
    $this->_db = $db;
  }
  ///////////////////////////////////////////////////
  function &getInstance(&$db)
  {
      static $instance;
      if(!isset($instance)) {
          $instance = new xassetajaxHandler($db);
      }
      return $instance;
  }
}

?>
