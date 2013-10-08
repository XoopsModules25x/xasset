<?php
class xassetNotificationService extends XoopsObject {
  function xassetNotificationService($id) {
  }
}


class xassetNotificationServiceHandler  extends XoopsObjectHandler {
  var $_ts;
  var $_template_dir = '';
  var $_module;
  var $_db;
  var $classname = 'xassetnotificationservice';
  var $_dbtable  = '';
  /////////////////////////////////////
  function &create()
  {
      return new $this->classname();
  }
  /////////////////////////////////////
  function xassetNotificationServiceHandler(&$db)
  {
    global $xoopsConfig, $xoopsModule;
    //
    $this->_db = $db;
    //
    $hModule =& xoops_gethandler('module');
    //
    $this->_ts     =& MyTextSanitizer::getInstance();
    $this->_template_dir = $this->_getTemplateDir($xoopsConfig['language']);
    //
    if (isset($xoopsModule) && $xoopsModule->getVar('dirname') == 'xasset') {
      $this->_module =& $xoopsModule;
    } else {
      $this->_module =& $hModule->getByDirname('xasset');
    }
  }
  /////////////////////////////////////////////
  function _getEmailTpl($category, $event)
  {  
      $templates =& $this->_module->getInfo('_email_tpl');   // Gets $modversion['_email_tpl'] array from xoops_version.php

      foreach($templates as $tpl){
          if($tpl['category'] == $category && $tpl['name'] == $event){
              return $tpl;
          }
      }
      return false;
  }
  /////////////////////////////////////////////
  function _getUserEmail($uid)
  {
      global $xoopsUser;
      //
      if (is_object($xoopsUser)) { 
	      if($uid == $xoopsUser->getVar('uid')){      // If $uid = current user's uid
	          return $xoopsUser->getVar('email');     // return their email
	      }
      } 
      $hMember =& xoops_gethandler('member');     //otherwise...
      if($member =& $hMember->getUser($uid)) {
          return $member->getVar('email');
    } else {
        return '';
    }
  }
  /////////////////////////////////////////////
  function _sendEventEmail($email_tpl, $toEmails, $tags, $fromEmail = '')
  {
    global $xoopsConfig;
    $tags = array_merge($tags, $this->_getCommonTplVars());         
    $xoopsMailer =& getMailer();
    $xoopsMailer->useMail();
    //set to HTML or plain text email
    $xoopsMailer->multimailer->IsHTML( isset($email_tpl['html']) && $email_tpl['html'] );

    foreach($tags as $k=>$v){
        $xoopsMailer->assign($k, preg_replace("/&amp;/i", '&', $v));
    }
    $xoopsMailer->setTemplateDir($this->_template_dir);             // Set template dir
    $xoopsMailer->setTemplate($email_tpl['mail_template']. ".tpl"); // Set the template to be used
    if (strlen($fromEmail) > 0) {
        $xoopsMailer->setFromEmail($fromEmail);
    } else {
        $xoopsMailer->setFromEmail($xoopsConfig['adminmail']);
    }
    $xoopsMailer->setToEmails($toEmails);                           // Set who the email goes to
    $xoopsMailer->setSubject($email_tpl['mail_subject']);           // Set the subject of the email
    $xoopsMailer->setFromName($xoopsConfig['sitename']);            // Set a from address
    $success = $xoopsMailer->send(true);      
    //
    if (!$success) {
      print_r($xoopsMailer);
    }
    //
    return $success;
  }
  /////////////////////////////////////////////
  function &_getCommonTplVars()
  {
      global $xoopsConfig;
      $tags = array();
      $tags['X_MODULE'] = $this->_module->getVar('name');
      $tags['X_SITEURL'] = XOOPS_URL;
      $tags['X_SITENAME'] = $xoopsConfig['sitename'];
      $tags['X_ADMINMAIL'] = $xoopsConfig['adminmail'];
      $tags['X_MODULE_URL'] = XOOPS_URL .'/modules/' . $this->_module->getVar('dirname') .'/';

      return $tags;
  }
  /////////////////////////////////////////////
  function _getTemplateDir($language)
  {
      $path = XOOPS_ROOT_PATH .'/modules/xasset/language/'. $language .'/mail_template';
      if(is_dir($path)){
          return $path;
      } else {
          return XOOPS_ROOT_PATH .'/modules/xsset/language/english/mail_template';
      }
  }
  //////////////////////////////////////////////////////////////////////////
  function &_getGroupEmails($groupid) {
    $userTable  = $this->_db->prefix('users');
    $groupTable = $this->_db->prefix('groups_users_link');
    //
    $sql = "select distinct email from $userTable u inner join $groupTable g on
              u.uid = g.uid
            where g.groupid = $groupid";
    //
    $aEmails = array();
    if ($res = $this->_db->query($sql)) {
      while($row = $this->_db->fetchArray($res)) {
        $aEmails[] = $row;
      }
    } else {
      echo $sql;
    }
    //
    return $aEmails;
  }
  //////////////////////////////////////////////////////////////////////////
  function &_getSiteAdminEmails() {
    $hCommon =& xoops_getmodulehandler('common','xasset');
    $hConfig =& xoops_getmodulehandler('config','xasset');
    //
    return $hCommon->getGroupEmails($hConfig->getEmailGroup());
  }  
  /////////////////////////////////////////////
  function new_client_purchase($args)  {
    $oOrder      = $args;
    $oUserDetail =& $oOrder->getUserDetail();
    //
    $tags = array();
    $tags['DATE_ORDERED']      = date('l dS of F Y h:i:s A');
    $tags['CLIENT_FIRST_NAME'] = $oUserDetail->getVar('first_name');
    $tags['CLIENT_LAST_NAME']  = $oUserDetail->getVar('last_name');
    $tags['CLIENT_EMAIL']      = $oUserDetail->email();
    $tags['ORDER_ITEMS']       = $oOrder->getOrderItemsAsText();
    $tags['SPECIAL_INSTRUCTIONS'] = $oOrder->getSpecialInstructionsAsText();
    //
    $aAddress =& $oUserDetail->addressArray();
    $address = '';
    foreach($aAddress as $key=>$value) {
      $address .= $value.'<br />';
    }
    $tags['CLIENT_ADDRESS']      = $address;
    //notify client
    if($email_tpl = $this->_getEmailTpl('client', 'new_client_purchase')) {    // Send confirm email to submitter
      $toEmails = $this->_getUserEmail($oUserDetail->getVar('uid'));
      $success = $this->_sendEventEmail($email_tpl, $toEmails, $tags);
    }
    //notify admin
    if($email_tpl = $this->_getEmailTpl('admin', 'new_client_purchase')) {    // Send confirm email to submitter
      $tags['ORDER_ITEMS']       = $this->_ts->nl2Br($oOrder->getOrderItemsAsText());
      $toEmails = $this->_getSiteAdminEmails();
      $success = $this->_sendEventEmail($email_tpl, $toEmails, $tags);
    }
    return $success;
  }
  /////////////////////////////////////////////
  function order_complete($arg) {
    $oOrder      = $arg;
    $oUserDetail =& $oOrder->getUserDetail();
    //
    $tags = array();
    $tags['DATE_ORDERED']      = date('l dS of F Y h:i:s A');
    $tags['CLIENT_FIRST_NAME'] = $oUserDetail->getVar('first_name');
    $tags['CLIENT_LAST_NAME']  = $oUserDetail->getVar('last_name');
    $tags['CLIENT_EMAIL']      = $oUserDetail->email();
    $tags['ORDER_ITEMS']       = $oOrder->getOrderItemsAsText();
    //
    $aAddress =& $oUserDetail->addressArray();
    $address = '';
    foreach($aAddress as $key=>$value) {
      $address .= $value.'<br />';
    }
    $tags['CLIENT_ADDRESS']      = $address;
    //notify client
    if($email_tpl = $this->_getEmailTpl('client', 'order_complete')) {    // Send confirm email to submitter
      $toEmails = $this->_getUserEmail($oUserDetail->getVar('uid'));
      $success = $this->_sendEventEmail($email_tpl, $toEmails, $tags);
    }
    return $success;
  }
  /////////////////////////////////////////////
  function new_user($args) {
    $hCommon  =& xoops_getmodulehandler('common','xasset'); 
    //
    list($oUsr,$password) = $args; 
    //
    $tags = array();
    $tags['USERNAME']           = $oUsr->getVar('uname');
    $tags['PASSWORD']           = $password;
    //send welcome email
    if($email_tpl = $this->_getEmailTpl('client', 'new_user')) {    
      // Send confirm email to submitter
      $toEmails = $oUsr->email();
      $success  = $this->_sendEventEmail($email_tpl, $toEmails, $tags);
    }
    return $success;
  }
  ///////////////////////////////////////////
  function expire_warning($args) {
    $hUserDetail  =& xoops_getmodulehandler('userDetails','xasset');
    $hUser       =& xoops_gethandler('user');
    //
    $oMember      =  $args;
    $xoopsUser    =& $hUser->get($oMember->uid());
    $oUserDetail  =  $hUserDetail->getUserDetailByID($oMember->uid());
    $oOrderDetail =& $oMember->getOrderDetails();
    //
    $tags = array();
    $tags['CLIENT_FIRST_NAME']  = $oUserDetail->getVar('first_name');
    $tags['CLIENT_LAST_NAME']   = $oUserDetail->getVar('last_name');
    $tags['MEMBERSHIP_EXPIRES'] = $oMember->expiryDate('l');
    $tags['PRODUCT']            = $oOrderDetail->getOrderItemDescription();
    //notify client
    if($email_tpl = $this->_getEmailTpl('client', 'expire_warning')) {// Send confirm email to submitter
      $toEmails = $this->_getUserEmail($oMember->uid());
      $success = $this->_sendEventEmail($email_tpl, $toEmails, $tags);
    }   
  }
  ///////////////////////////////////////////
  function expire_account($args) {
    $hMembers     =& xoops_getmodulehandler('applicationProductMemb','xasset');
    $hUserDetail  =& xoops_getmodulehandler('userDetails','xasset');
    $hUser       =& xoops_gethandler('user');
    //
    $aMember      =  $args;
    //
    $oMember      =& $hMembers->get($aMember['id']);
    $xoopsUser    =& $hUser->get($oMember->uid());
    $oUserDetail  =  $hUserDetail->getUserDetailByID($oMember->uid());
    $oOrderDetail =& $oMember->getOrderDetails();
    //
    $tags = array();
    $tags['CLIENT_FIRST_NAME']  = $oUserDetail->getVar('first_name');
    $tags['CLIENT_LAST_NAME']   = $oUserDetail->getVar('last_name');
    $tags['MEMBERSHIP_EXPIRED'] = $oMember->expiryDate('l');
    $tags['PRODUCT']            = $oOrderDetail->getOrderItemDescription();
    //notify client
    if($email_tpl = $this->_getEmailTpl('client', 'expire_membership')) {// Send confirm email to submitter
      $toEmails = $this->_getUserEmail($oMember->uid());
      $success = $this->_sendEventEmail($email_tpl, $toEmails, $tags);
    }   
  }

}
?>
