<?php
                    
require("header.php");       
require_once(XOOPS_ROOT_PATH . "/header.php");
//require_once('class/crypt.php');    

$op = 'default';    

if ( isset( $_GET['op'] ) ) {
  $op = $_GET['op'];
} else if ( isset ($_POST['op']) ) {
  $op = $_POST['op'];
}
           

switch ( $op ) {
  case 'default':
   default:
   //if (isset($_GET['appid'])) $appid = $_GET['appid']; else $appid = 0;
   //loadIndex(/*$appid*/);
   product();
   break;
  //
  case 'viewApplication':
    viewApplication($_GET['appid'],$_GET['key']);
    break;
  //
  case 'viewLicense':
    viewLicense($_GET['lid'],$_GET['key']);
    break;
  //
  case 'viewAppGroups':
    viewAppGroups($_GET['groupid'],$_GET['key']);
    break;
  //
  case 'downloadPack':
    downloadPack($_GET['packid'],$_GET['key']);
    break;
  //
  case 'downloadLicPack':
    downloadLicPack($_GET['id'],$_GET['key']);
    break;
  //
  case 'licensed':
    loadIndex();
    break;
  //
  case 'evaluation':
    if ( (isset($_GET['appid'])) && (isset($_GET['key'])) ) {
      loadEvaluation($_GET['appid'], $_GET['key']);}
    else {
      loadEvaluation();
    }
    break;
  //
	case 'updateCurrency':
		updateCurrency($_POST);
    break;
  //
  case 'product':
    product($_GET['id'], $_GET['key']);
    break;
  //
  case 'showMyDownloads':
    showMyDownloads();
    break;
  //
  case 'downloadSample':
    downloadSample($_GET['id'],$_GET['key']);
    break;
  //
  case 'showUserSubs':
    showUserSubs();
    break;
  //
  case 'getVideo':
    getVideo($_GET['movie_id'], isset($_GET['position']) ? $_GET['position'] : null, $_GET['token']);
    break;
  //
  case 'ViewVideoLic':
    ViewVideoLic($_GET['id'],$_GET['key']);
    break;
  //
  case 'viewProductDescription':
    viewProductDescription($_GET['id'],$_GET['key']);
    break;
  //
  
}
///////////////////////////////////////////////////////////////////////////////////////////////
function loadIndex($appid = 0, $key = '') {
  global $xoopsOption, $xoopsTpl, $xoopsConfig, $xoopsUser, $xoopsLogger, $xoopsUserIsAdmin, $xasset_module_header;
  //
  $xoopsOption['template_main'] = 'xasset_index.html';
  require_once(XOOPS_ROOT_PATH . "/header.php");
  //this only makes sense for resgitered users.... if anon redirect to evaluation page
  if ($xoopsUser) {
    $uid    = $xoopsUser->uid();
  } else {
      redirect_header('index.php?op=evaluation',0,'Redirecting...');
  }
  //
  $hLic     =& xoops_getmodulehandler('license','xasset');
  $hPackGrp =& xoops_getmodulehandler('packageGroup','xasset');
  $hLink    =& xoops_getmodulehandler('link','xasset');
  $hApp     =& xoops_getmodulehandler('application','xasset');
  //
  $userApps = $hLic->getUserApplicationArray($uid);   ;
  $app      =& $hApp->create();
  //
  if (!($appid > 0)) {
    if (count($userApps) > 0) {
      $appid = $userApps[0]['appid'];
      $key   = $userApps[0]['cryptKey'];
    } else { //no dice
      die('No applications found');
    }
  }
  //save to session for currency block
  $_SESSION['application_id'] = $appid;
  //
  if (keyMatches($appid,$key,$app->weight,'Invalid Key. Cannot display Application') ){
    $crit = new CriteriaCompo(new Criteria('id', $appid));
    //
    $appObj  = $hApp->getApplicationsArray($crit);
    $userLic = $hLic->getClientLicenses($appid,$uid);
    $links   = $hLink->getApplicationLinks($appid);
    $appObj  = $appObj[0];
    //
    $xoopsTpl->assign('xoops_module_header',$xasset_module_header);
    $xoopsTpl->assign('xasset_user_applications',$userApps);
    $xoopsTpl->assign('xasset_user_application',$appObj);
    $xoopsTpl->assign('xasset_application_licenses',$userLic);
    $xoopsTpl->assign('xasset_application_groups',$hPackGrp->getApplicationGroupPackages($appid));
    $xoopsTpl->assign('xasset_application_links',$links);
    $xoopsTpl->assign('xasset_links_count',count($links));
    //
    include(XOOPS_ROOT_PATH . "/footer.php");
  }
}
/////////////////////////////////////////
function viewApplication($appid,$key) {
  loadIndex($appid,$key);
}
/////////////////////////////////////////
function viewLicense($licID, $key) {
  global $xoopsOption, $xoopsTpl, $xoopsConfig, $xoopsUser, $xoopsLogger, $xoopsUserIsAdmin, $xasset_module_header;
  //
  $xoopsOption['template_main'] = 'xasset_license_index.html';
  require_once(XOOPS_ROOT_PATH . "/header.php");
  //first check that the key matches requested ID
  $hLic     =& xoops_getmodulehandler('license','xasset');
  //
  $lic      =& $hLic->get($licID);
  //
  if (keyMatches($licID,$key,$lic->weight,'Invalid Key. Cannot display License Information')) {
    $app      =& $lic->getApplication();
    //
    $res['id']        = $lic->getVar('id');
    $res['key']       = $lic->getVar('authKey');
    $res['authCode']  = $lic->getVar('authCode');
    $res['expires']   = $lic->expires();
    $res['dateIssued']= $lic->dateIssued();
    //
    $xoopsTpl->assign('xoops_module_header',$xasset_module_header);
    $xoopsTpl->assign('xasset_license',$res);
    $xoopsTpl->assign('xasset_application',$app->getVar('name'));
    //
    include(XOOPS_ROOT_PATH . "/footer.php");
  }
}
/////////////////////////////////////////
function viewAppGroups($groupid, $key){
  global $xoopsOption, $xoopsTpl, $xoopsConfig, $xoopsUser, $xoopsLogger, $xoopsUserIsAdmin, $xasset_module_header;
  //
  $xoopsOption['template_main'] = 'xasset_package_index.html';
  require_once(XOOPS_ROOT_PATH . "/header.php");
  //
  $hPackGrp  =& xoops_getmodulehandler('packageGroup','xasset');
  //
  $packGrp   =& $hPackGrp->get($groupid);
  $app       =& $packGrp->getApplication();    
  $grp       =  $hPackGrp->getPackageGroup($groupid);
  //
  if (keyMatches($groupid,$key,$packGrp->weight,'Invalid Key. Cannot view Package Group')){

    $xoopsTpl->assign('xoops_module_header',$xasset_module_header);
    $xoopsTpl->assign('xasset_group',$grp[0]);
    $xoopsTpl->assign('xasset_package',$packGrp->getVar('name'));
    $xoopsTpl->assign('xasset_application',$app->getVar('name'));
    //
    include(XOOPS_ROOT_PATH . "/footer.php");
  }
}
/////////////////////////////////////////
function downloadPack($id,$key) {
  global $xoopsOption, $xoopsTpl, $xoopsConfig, $xoopsUser, $xoopsLogger, $xoopsUserIsAdmin, $xasset_module_header;
  //
  $hPackage =& xoops_getmodulehandler('package','xasset');
  //
  $pack     =& $hPackage->get($id);
  //
  if ( keyMatches($id,$key,$pack->weight,'Invalid Key. Cannot download package file') ) {
    if ($pack->getVar('protected') > 0) {
      $pack->secureDownloadFile();
    } else {
      $pack->downloadFile();
    }
  }
}
/////////////////////////////////////////
function downloadLicPack($id,$key) {   
  global $xoopsOption, $xoopsTpl, $xoopsConfig, $xoopsUser, $xoopsLogger, $xoopsUserIsAdmin, $xasset_module_header;
  //
  $hPackage =& xoops_getmodulehandler('package','xasset');
  //
  $pack     =& $hPackage->get($id);
  //
  if ( keyMatches($id,$key,$pack->weight,'Invalid Key. Cannot download package file') ) { 
    $pack->secureDownloadFile();   
  }
}
/////////////////////////////////////////
function downloadSample($id,$key) {
  global $xoopsOption, $xoopsTpl, $xoopsConfig, $xoopsUser, $xoopsLogger, $xoopsUserIsAdmin, $xasset_module_header;
  //
  $hPackage =& xoops_getmodulehandler('package','xasset');
  //
  $pack     =& $hPackage->get($id);
  //
  if ( keyMatches($id,$key,$pack->weight,'Invalid Key. Cannot download package file') ) { 
    $pack->downloadFile();   
  }
}
/////////////////////////////////////////
function loadEvaluation($appid=0, $key=''){
  global $xoopsOption, $xoopsTpl, $xoopsConfig, $xoopsUser, $xoopsLogger, $xoopsUserIsAdmin, $xasset_module_header;
  //
  $xoopsOption['template_main'] = 'xasset_evaluation_index.html';
  require_once(XOOPS_ROOT_PATH . "/header.php");
  //
  if ($xoopsUser){
    $uid    = $xoopsUser->uid();}
  else {
    $uid = 0;
  }
  //
  $hLic     =& xoops_getmodulehandler('license','xasset');
  $hPackGrp =& xoops_getmodulehandler('packageGroup','xasset');
  $hLink    =& xoops_getmodulehandler('link','xasset');
  $hApp     =& xoops_getmodulehandler('application','xasset');
  $hAppProd =& xoops_getmodulehandler('applicationProduct','xasset');
  $hCurr    =& xoops_getmodulehandler('currency','xasset');
  $hConfig  =& xoops_getmodulehandler('config','xasset');
  $hCommon  =& xoops_getmodulehandler('common','xasset');
  //
  if ($uid > 0) {
    $evalApps = $hLic->getEvalApplicationsArray($uid); }
  else {
    $evalApps = $hApp->getEvalApplicationsArray();
  }
  $app      =& $hApp->create();
  //
  if (!($appid > 0)) {
    if (count($evalApps) > 0) {
      $appid = $evalApps[0]['appid'];
      $key   = $evalApps[0]['cryptKey'];
    } else {
      $xoopsOption['template_main'] = 'xasset_error.html';
      $xoopsTpl->assign('xasset_error','No evaluation applications found.');
      include(XOOPS_ROOT_PATH . "/footer.php");
      exit;
    }
  }
  //
  if (keyMatches($appid,$key,$app->weight,'Invalid Key. Cannot display Application') ){
    $crit = new CriteriaCompo(new Criteria('id', $appid));
    //
    $appObj  = $hApp->getApplicationsArray($crit);
    $appObj  = $appObj[0];
    //
    if (isset($_SESSION['currency_id'])) {
      $currid = $_SESSION['currency_id'];
    } else {
      $currid  = $hConfig->getBaseCurrency();
    }
    //
    $links   = $hLink->getApplicationLinks($appid);
    $prods   = $hAppProd->getAppProductArray($appid, $currid);
    $curs    = $hCurr->getSelectArray();
    //
    $showMinLic   = $hCommon->getModuleOption('prodShowMinLicence','xasset');
    $showMaxDowns = $hCommon->getModuleOption('prodShowMaxDownloads','xasset');
    $showMaxDays  = $hCommon->getModuleOption('prodShowMaxDays','xasset');
    $showExpires  = $hCommon->getModuleOption('prodShowExpires','xasset');
    //
    $xoopsTpl->assign('xoops_module_header',$xasset_module_header);
    $xoopsTpl->assign('xasset_applications',$evalApps);
    $xoopsTpl->assign('xasset_application',$appObj);
    $xoopsTpl->assign('xasset_application_groups',$hPackGrp->getApplicationGroupPackages($appid));
    $xoopsTpl->assign('xasset_application_links',$links);
    $xoopsTpl->assign('xasset_links_count',count($links));
    $xoopsTpl->assign('xasset_application_packages',$prods);
    $xoopsTpl->assign('xasset_application_packages_count',count($prods));
    $xoopsTpl->assign('xasset_currency_count',count($curs));
    $xoopsTpl->assign('xasset_currencies',$curs);
    $xoopsTpl->assign('xasset_basecurrency_id',$currid);
    $xoopsTpl->assign('xasset_evaluation_type','eval');
    $xoopsTpl->assign('xasset_show_minlic',$showMinLic);
    $xoopsTpl->assign('xasset_show_maxdowns',$showMaxDowns);
    $xoopsTpl->assign('xasset_show_maxdays',$showMaxDays);
    $xoopsTpl->assign('xasset_show_expires',$showExpires);
    //$xoopsTpl->assign('xasset_base_currency',
    //
    include(XOOPS_ROOT_PATH . "/footer.php");
  }
}
/////////////////////////////////////////
function updateCurrency($post) {
  if (isset($post['currency_id'])) {
    $_SESSION['currency_id'] = $post['currency_id'];
  } else {
    unset($_SESSION['currency_id']);
  }
  //redirect
  //if ($post['type'] == 'eval') {
    if ($post['app_id'] > 0) {
      $hApp =& xoops_getmodulehandler('application','xasset');
      $oApp =& $hApp->get($post['app_id']);
      //
      $url = 'index.php?op=product&id='.$oApp->ID().'&key='.$oApp->getKey();
    } else {
      $url = 'index.php';
    }
    redirect_header($url,1,'Base Currency Updated.');
  //}
}
/////////////////////////////////////////
function product($appid = null, $key = null) {
  global $xoopsOption, $xoopsTpl, $xoopsConfig, $xoopsUser, $xoopsLogger, $xoopsUserIsAdmin, $xasset_module_header;
  //
  $xoopsOption['template_main'] = 'xasset_product.html';
  require_once(XOOPS_ROOT_PATH . "/header.php");
  //
  $hApps =& xoops_getmodulehandler('application','xasset');
  //
  if ($xoopsUser){
    $uid    = $xoopsUser->uid();}
  else {
    $uid = 0;
  }
  //
  $hLic     =& xoops_getmodulehandler('license','xasset');
  $hPackGrp =& xoops_getmodulehandler('packageGroup','xasset');
  $hLink    =& xoops_getmodulehandler('link','xasset');
  $hApps    =& xoops_getmodulehandler('application','xasset');
  $hAppProd =& xoops_getmodulehandler('applicationProduct','xasset');
  $hCurr    =& xoops_getmodulehandler('currency','xasset');
  $hConfig  =& xoops_getmodulehandler('config','xasset');
  $hAjax    =& xoops_getmodulehandler('ajax','xasset'); 
  $hCommon  =& xoops_getmodulehandler('common','xasset');
  //
  $app      =& $hApps->create();
  $aApps    =& $hApps->getApplicationMainMenuObjects();
  //
  if (count($aApps)>0) {
    $oApp   = reset($aApps);
  } else {
    $xoopsOption['template_main'] = 'xasset_error.html';
    $xoopsTpl->assign('xasset_error','No applications defined for this group.');
    include(XOOPS_ROOT_PATH . "/footer.php");
    return;
  }
  //
  if (!($appid > 0)) {
    if (count($aApps) > 0) {
      $appid = $oApp->getVar('id');
      $key   = $oApp->getKey();
    } else {
      $xoopsOption['template_main'] = 'xasset_error.html';
      $xoopsTpl->assign('xasset_error','No application found.');
      include(XOOPS_ROOT_PATH . "/footer.php");
      exit;
    }
  }
  //
  if (keyMatches($appid,$key,$app->weight,'Invalid Key. Cannot display Application') ){
    $crit = new CriteriaCompo(new Criteria('id', $appid));
    //
    if (isset($_SESSION['currency_id'])) {
      $currid = $_SESSION['currency_id'];
    } else {
      $currid  = $hConfig->getBaseCurrency();
    }
    //do ajax stuff
    $oAjax =& $hAjax->create();
    $oAjax->registerFunction('onSampleClick',XOOPS_URL.'/modules/xasset/include/ajax.php');
    //
    $links     = $hLink->getApplicationLinks($appid);
    $prods     = $hAppProd->getAppProductArray($appid, $currid);     
    $curs      = $hCurr->getSelectArray();
    $appGroups = $hPackGrp->getApplicationGroupPackages($appid);
    //
    $showMinLic   = $hCommon->getModuleOption('prodShowMinLicence','xasset');
    $showMaxDowns = $hCommon->getModuleOption('prodShowMaxDownloads','xasset');
    $showMaxDays  = $hCommon->getModuleOption('prodShowMaxDays','xasset');
    $showExpires  = $hCommon->getModuleOption('prodShowExpires','xasset');
    $showSamples  = $oApp->getVar('hasSamples') == 1;
    $aPopup['width']  = $hCommon->getModuleOption('prodwin_width'); 
    $aPopup['height'] = $hCommon->getModuleOption('prodwin_height');
    //
    $xoopsTpl->assign('xoops_module_header',$xasset_module_header . $oAjax->getHeaderCode());
    $xoopsTpl->assign('xasset_application_groups',$appGroups);
    $xoopsTpl->assign('xasset_application_groups_count',count($appGroups));
    $xoopsTpl->assign('xasset_application_links',$links);
    $xoopsTpl->assign('xasset_links_count',count($links));
    $xoopsTpl->assign('xasset_application_packages',$prods);
    $xoopsTpl->assign('xasset_application_packages_count',count($prods));
    $xoopsTpl->assign('xasset_currency_count',count($curs));
    $xoopsTpl->assign('xasset_currencies',$curs);
    $xoopsTpl->assign('xasset_basecurrency_id',$currid);
    $xoopsTpl->assign('xasset_evaluation_type','eval');
    $xoopsTpl->assign('xasset_show_minlic',$showMinLic);
    $xoopsTpl->assign('xasset_show_maxdowns',$showMaxDowns);
    $xoopsTpl->assign('xasset_show_maxdays',$showMaxDays);
    $xoopsTpl->assign('xasset_show_expires',$showExpires);
    $xoopsTpl->assign('xasset_show_samples',$showSamples);
    $xoopsTpl->assign('xasset_popup_dim',$aPopup);
    //
    $appObj  = $hApps->getApplicationsArray($crit);
    $appObj  = reset($appObj);
    //
    $xoopsTpl->assign('xasset_application',$appObj);
  }
  include(XOOPS_ROOT_PATH . "/footer.php");
}
/////////////////////////////////////////
function showMyDownloads() {
  global $xoopsOption, $xoopsTpl, $xoopsConfig, $xoopsUser, $xoopsLogger, $xoopsUserIsAdmin, $xasset_module_header;
  //
  $xoopsOption['template_main'] = 'xasset_downloads.html';
  require_once(XOOPS_ROOT_PATH . "/header.php");
  //get app products purchase by client id from order detail table
  //next get files linked to this. Take into account download limits and expiry dates then display.
  //
  $hUserDetail =& xoops_getmodulehandler('userDetails','xasset');
  //only register userDetail users will be able to access this page
  if ($xoopsUser) {
    if ( $userDetail =& $hUserDetail->getUserDetailByID($xoopsUser->uid()) ) {
      $aDownloads =& $userDetail->getUserDownloads();   
      //
      $xoopsTpl->assign('xasset_downloads',$aDownloads);
    } else {
      redirect_header('order.php?op=showUserDetails',3,'Please Complete User Detail Information Before Proceeding.');
    } 
  } else {
    redirect_header(XOOPS_URL.'/user.php',3,'Not Logged in.');
  }
  //
  include(XOOPS_ROOT_PATH . "/footer.php");
}
//////////////////////////////////////////////////
function showUserSubs() {
  global $xoopsOption, $xoopsTpl, $xoopsConfig, $xoopsUser, $xoopsLogger, $xoopsUserIsAdmin, $xasset_module_header;
  //
  $xoopsOption['template_main'] = 'xasset_my_subscriptions.html';
  require_once(XOOPS_ROOT_PATH . "/header.php");
  //
  $hMembers =& xoops_getmodulehandler('applicationProductMemb','xasset');
  //only register userDetail users will be able to access this page
  if ($xoopsUser) {
    $crit = new Criteria('am.uid',$xoopsUser->uid());
    $crit->setSort('expiry_date');
    //
    $aSubs =& $hMembers->getMembersForSubscription($crit); 
    //
    $xoopsTpl->assign('xoops_module_header',$xasset_module_header);
    $xoopsTpl->assign('xasset_subscriptons',$aSubs);
  } else {
    redirect_header(XOOPS_URL.'/user.php',3,'Not Logged in.');
  }
  //
  include(XOOPS_ROOT_PATH . "/footer.php");
}
////////////////////////////////////////////
function getVideo($id, $position, $token) { 
  $hVideo =& xoops_getmodulehandler('video','xasset');
  $hVideo->getVideo($id,$token,$position);
}
////////////////////////////////////////////
function ViewVideoLic($id, $key) {
  global $xoopsOption, $xoopsTpl, $xoopsConfig, $xoopsUser, $xoopsLogger, $xoopsUserIsAdmin, $xasset_module_header;
  //
  $xoopsOption['template_main'] = 'xasset_video_index.html';
  require_once(XOOPS_ROOT_PATH . "/header.php");
  //
  $hPack   =& xoops_getmodulehandler('package','xasset');
  $hCommon =& xoops_getmodulehandler('common','xasset');
  //
  $oPackage  =& $hPack->get($id);
  //
  if (keyMatches($id,$key,$oPackage->weight,'Invalid Key. Cannot view video')){
    //increment watched flag
    $oPackage->incrementDownload();
    //
    $xoopsTpl->assign('xoops_module_header',$xasset_module_header);
    $xoopsTpl->assign('xasset_movie_id',$id);
    $xoopsTpl->assign('xasset_movie_size',filesize($oPackage->filePath()));
    $xoopsTpl->assign('xasset_token', $xoopsUser ? $hCommon->pspEncrypt($xoopsUser->uid()) : $hCommon->pspEncrypt(0));
    //
    include(XOOPS_ROOT_PATH . "/footer.php");
  }
}
//////////////////////////////////////////////
function viewProductDescription($id, $key) {
  global $xoopsOption, $xoopsTpl, $xoopsConfig, $xoopsUser, $xoopsLogger, $xoopsUserIsAdmin, $xasset_module_header;
  require_once('../../mainfile.php');
  xoops_header();
  //
  $hAppProd =& xoops_getmodulehandler('applicationProduct','xasset');
  $oAppProd =& $hAppProd->get($id);
  //
  if ( keyMatches($id,$key,$oAppProd->weight,'Invalid Key') ) {
    echo '<div id="content">'.$oAppProd->getRichDescription().'</div>';
  } 
  //
  xoops_footer();  
}

?>
