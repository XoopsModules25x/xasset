<?php

class baseGateway {
  //private
  var $_hGate;
  var $_hGateDetail;
  var $_returnURL;
  var $_cancelURL;
  var $_validates;
  var $_optionFields;
  var $_version;
  //public
  var $id;
  var $code;
  var $enabled;
  var $installed;
  var $description;
  var $shortDesc;
  var $postURL;
  //cons
  function baseGateway() {
    $this->_hGate       =& xoops_getmodulehandler('gateway','xasset');
    $this->_hGateDetail =& xoops_getmodulehandler('gatewayDetail','xasset');
    //
        $this->_returnURL   = XOOPS_URL.'/modules/xasset/return.php';
    $this->_cancelURL   = XOOPS_URL.'/modules/xasset/cancel.php';
    $this->_validates   = false;
    //
    $this->enabled = false;
    $this->installed = false;
    $this->_optionFields = array();
    //
    $this->initialise();
  }
  /////////////////////////////////////////
  function initialise() {
    if ($gate =& $this->_hGate->getByCode($this->code)) {
      $this->id        = $gate->getVar('id');
      $this->enabled   = $gate->getVar('enabled') == true;
      $this->installed = true;
      //load up constants from db
      $this->loadConstants();
    } else {
      $this->enabled   = false;
      $this->installed = false;
    }
  }
  /////////////////////////////////////////
  function version() {
    return $this->_version;
  }
  /////////////////////////////////////////
  function loadConstants(){
    $consts =& $this->_hGateDetail->getByIndex($this->id);
    foreach($consts as $const){
      if (!defined($const->getVar('gkey'))) {
        define($const->getVar('gkey'),$const->getVar('gvalue')); }
    }
  }
  /////////////////////////////////////////
  function preprocess() {
    return false;
  }
  /////////////////////////////////////////
  function processForm() {
    //this does final validation and constructs the submit form for the payment gateway.
  }
  /////////////////////////////////////////
  function keys() {
    //define key fields here
  }
  /////////////////////////////////////////
  function processPost(&$error) {
    //abstract
  }
  /////////////////////////////////////////
  function extraFields() {
  }
  /////////////////////////////////////////
  function requiresSSL() {
    return false;
  }
  /////////////////////////////////////////
  function processReturn(&$oOrder, $post) {
    //gateway's return URL... process return post from gateway
  }
  /////////////////////////////////////////
  function validateTransaction($orderID, $post) {
  }
  /////////////////////////////////////////
  function validates() {
    return $this->_validates;
  }
  /////////////////////////////////////////
  function isThisGateway($post) {
    //called by verify to determine the gateway type from $_POST... returns order id
    return false;
  }
  /////////////////////////////////////////
  function check(){ //query db to check if enabled and fetch stored header values
    //assume fails
    $this->enabled = false;
    //
    $sql = "select enabled from $this->_tableIndex where code = $code";
    if ($res = $this->_db->query($sql)) {
      if ($row = $this->_db->fetcharray['enabled']) {
        $this->enabled = $row['enabled'] == 1;

        return $this->enabled;
      }
    }
  }
  /////////////////////////////////////////
  function install() {
    //install required key fields.. this will be read by keys()
    //$hGateway = xoops_getmodulehandler('gateway','xasset');
    //
    if ($gate =& $this->_hGate->getByCode($this->code)) {
      $get->setVar('enabled',true);
    } else {
      $gate =& $this->_hGate->create();
      $gate->setVar('code',$this->code);
      $gate->setVar('enabled',true);
    }
    if ($this->_hGate->insert($gate)) {
      $this->id = $gate->getVar('id');

      return true;
    } else {
      return false;
    }
  }
  /////////////////////////////////////////
  function doPreprocess() {
    $this->extraFields();
    $form = $this->drawOptionForm($this->code);
    //
    return $form;
  }
  /////////////////////////////////////////
  function remove() {
    return $this->_hGateDetail->deleteGatewayConfig($this->id, $this->keys());
  }
  //////////////////////////////////////////////////helper functions/////////////////////////////////////////////////
  function drawHidden($name, $value = '', $parameters = '') {
        $field = '<input type="hidden" name="' . $name . '"';
        //
        if (strlen($value)>0) {
            $field .= ' value="' . $value . '"';
        }
        //
        if (isset($parameters)) $field .= ' ' . $parameters;
        $field .= '>';
        //
        return $field;
  }
  /////////////////////////////////////////
  function drawField($name, $type, $label, $value = '', $options = '') {
    switch ($type) {
      case 'text':
        $value   = $value <> '' ? 'value="'.$value.'"' : '';
        $control = '<label for="'.$name.'" style="float:left;width:30%;padding-left:5px;">'.$label.'</label><input type="text" name="'.$name.'" id="'.$name.'" '.$value.'>';
      break;
      case 'select':
        if (is_array($options) && (count($options) > 0)) {
          $option = '';
          foreach($options as $optID=>$optValue) {
            $option .= '<option value="'.$optID.'">'.$optValue.'</option>';
          }
        }
        $control = '<label for="'.$name.'" style="float:left;width:30%;padding-left:5px;">'.$label.'</label><select name="'.$name.'" id="'.$name.'">'.$option.'</select>';
      break;
      case 'area':
        $control = "<textarea name='$name' cols='70' rows='5'>$value</textarea>";
      break;
      case 'box':
        $myts = MyTextSanitizer::getInstance();
        $control = "<table class='outer'><tr><td>".$myts->xoopsCodeDecode($myts->nl2Br($value))."</td></tr></table>";
      break;
    }

    return $control.'<br />';
  }
  ////////////////////////////////////////////
  function drawOption($name) {
    $out = '';
    if (isset($this->_optionFields[$name])) {
      $out = $this->drawfield($name, $this->_optionFields[$name]['type'],$this->_optionFields[$name]['label'],
                                     $this->_optionFields[$name]['value'],$this->_optionFields[$name]['options']);
    }

    return $out;
  }
  /////////////////////////////////////////
  function drawOptionForm($name) {
    $form = '<form id="'.$name.'" name="'.$name.'" method="post" action="order.php?op=postOptionForm">';
    foreach($this->_optionFields as $name => $values) {
      $form .= $this->drawOption($name);
    }
    //draw the submits
    $form .= '<p><input type="submit" value="'._LANG_ORDER_EXTRA_BUT.'" /></p>';
    $form .= '</form>';
    //
    return $form;
  }
  ////////////////////////////////////////////
  function &getOption($name) {
    if (isset($this->_optionFields[$name]))
      return $this->_optionFields[$name];
    else {
      $ret = false;

      return $ret;
    }
  }
  ////////////////////////////////////////////
  function addOption($name, $type, $label, $value = '', $options = array()) {
    $this->_optionFields[$name] = array('type' => $type, 'label' => $label, 'value' => $value, 'options' => $options);
  }
  ////////////////////////////////////////////
  function &yearArray($years = 10) {
    $year = date('Y',time());
    $out = array();
    for ($i = 0; $i < $years; $i++) {
      $out[$year + $i] = $year + $i;
    }

    return $out;
  }
  ///////////////////////////////////////////
  function &monthArray() {
    $out = array('01'=>'Jan','02'=>'Feb','03'=>'Mar','04'=>'Apr','05'=>'May','06'=>'Jun','07'=>'Jul','08'=>'Aug',
                 '09'=>'Sep','10'=>'Oct','11'=>'Nov','12'=>'Dec');

    return $out;
  }
  /////////////////////////////////////////
  function saveField($key, $value, $order, $description, $type = '', $lov = '') {
    if(!$gate =& $this->_hGateDetail->getByCode($this->id, $key)){
      $gate =& $this->_hGateDetail->create();
      $gate->setVar('gateway_id',$this->id);
      $gate->setVar('gorder',$order);
      $gate->setVar('gkey',$key);
      $gate->setVar('description',$description);
      $gate->setVar('gtype',$type);
      $gate->setVar('list_ov',$lov);
    }
    $gate->setVar('gvalue',$value);
    //
    return $this->_hGateDetail->insert($gate);
  }
  /////////////////////////////////////////
  function postToGateway() {
    $fields = $this->processForm();
    $url    = $this->postURL;
    //
    $form = "<html>
             <body onLoad='document.checkout.submit()'>
             <form name='checkout' method='post' action='$url'> $fields </form>
             </body>
             </html>";
    //
    echo $form;
  }
}
