<?php

include('../../../include/cp_header.php');
include_once('admin_header.php');

//require_once('../include/functions.php');

global $xoopsModule;
$module_id = $xoopsModule->getVar('mid');

$op = 'default';

if ( isset( $_REQUEST['op'] ) ) {
  $op = $_REQUEST['op'];
} else if ( isset ($_POST['op']) ) {
	$op = $_POST['op'];
}



switch ( $op )
{
    case 'manageApplications':
      xoops_cp_header();
      manageApplications();
      break;
    //
    case 'addApplication':
      addApplication($_POST);
      break;
    //
    case 'deleteApplication':
      deleteApplication($_GET['id']);
      break;
    //
    case 'doDeleteApplication':
      doDeleteApplication($_POST['id']);
      break;
    //
    case 'editApplication':
      xoops_cp_header();
      editApplication($_GET['id']);
      break;
    //
    case 'manageLicenses':
      xoops_cp_header();
      manageLicenses();
      break;
    //
    case 'addLicense':
      addLicense($_POST);
      break;
    //
    case 'viewAppLicenses':
      xoops_cp_header();
      viewAppLicenses($_GET['id']);
      break;
    //
    case 'viewClientLicenses':
      xoops_cp_header();
      viewClientLicenses($_GET['id'],$_GET['appid']);
      break;
    //
    case 'editClientLicense':
      xoops_cp_header();
      editClientLicense($_GET['id']);
      break;
    //
    case 'deleteClientLicense':
      deleteClientLicense($_GET['id']);
      break;
    //
    case 'managePackages':
      if (isset($_GET['appid'])) $appid = $_GET['appid']; else $appid = 0;
      xoops_cp_header();
      managePackages($appid);
      break;
    //
    case 'addPackageGroup':
      addPackageGroup($_POST);
      break;
    //
    case 'editPackageGroup' :
      xoops_cp_header();
      editPackageGroup($_GET['id'],$_GET['appid']);
      break;
    //
    case 'deletePackageGroup':
      deletePackageGroup($_GET['id']);
      break;
    //
    case 'addPackage':
      addPackage($_POST);
      break;
    //
    case 'deletePackage':
      deletePackage($_GET['id']);
      break;
    //
    case 'doDeletePackage':
      doDeletePackage($_POST['id']);
      break;
    //
    case 'editPackage':
      xoops_cp_header();
      editPackage($_GET['id']);
      break;
    //
    case 'manageLinks':
      xoops_cp_header();
      isset($_GET['appid']) ? manageLinks($_GET['appid']) : manageLinks();
      break;
    //
    case 'addLink':
      addLink($_POST);
      break;
    //
    case 'deleteLink':
      deleteLink($_GET['id']);
      break;
      //
    case 'editLink':
      xoops_cp_header();
      editLink($_GET['id']);
      break;
    //
    case 'viewDownloadStats':
      xoops_cp_header();
      isset($_GET['appid']) ? viewDownloadStats($_GET['appid']) : viewDownloadStats();
      break;
    //
    case 'deleteStat':
      deleteStat($_GET['id']);
      break;
    //
    case 'manageCountries':
      xoops_cp_header();
      manageCountries();
      break;
    //
    case 'addCountry':
      addCountry($_POST);
      break;
    //
    case 'editCountry':
      xoops_cp_header();
      editCountry($_GET['id']);
      break;
    //
    case 'manageZones':
      xoops_cp_header();
      manageZones();
      break;
    //
		case 'addZone':
			addZone($_POST);
      break;
    //
    case 'editZone':
      xoops_cp_header();
      editZone($_GET['id']);
      break;
    //
    case 'deleteZone':
      deleteZone($_GET['id']);
      break;
    //
    case 'manageTaxes':
      xoops_cp_header();
      manageTaxes();
      break;
    //
    case 'addTaxClass':
      addTaxClass($_POST);
      break;
    //
    case 'addTaxRate':
      addTaxRate($_POST);
      break;
    //
    case 'addTaxZone':
      addTaxZone($_POST);
      break;
    //
    case 'editTaxZone':
      xoops_cp_header();
      editTaxZone($_GET['id']);
      break;
    //
    case 'deleteTaxZone':
      deleteTaxZone($_GET['id']);
      break;
    //
    case 'editTaxClass':
      xoops_cp_header();
      editTaxClass($_GET['id']);
      break;
    //
    case 'editTaxRate':
      xoops_cp_header();
      editTaxRate($_GET['id']);
      break;
    //
    case 'deleteTaxClass':
      deleteTaxClass($_GET['id']);
      break;
    //
    case 'deleteTaxRate':
      deleteTaxRate($_GET['id']);
      break;
    //
    case 'manageCurrencies':
      xoops_cp_header();
      manageCurrencies();
      break;
    //
    case 'addCurrency':
      addCurrency($_POST);
      break;
    //
    case 'editCurrency':
      xoops_cp_header();
      editCurrency($_GET['id']);
      break;
    //
    case 'deleteCurrency':
      deleteCurrency($_GET['id']);
      break;
    //
    case 'addAppProduct':
      addAppProduct($_POST);
      break;
    //
    case 'deleteAppProduct':
      deleteAppProduct($_GET['id']);
      break;
    //
    case 'doDeleteAppProduct':
      doDeleteAppproduct($_POST['id']);
      break;
    //
    case 'editAppProduct':
      xoops_cp_header();
      editAppProduct($_GET['id']);
      break;
    //
    case 'manageGateways':
      xoops_cp_header();
      isset($_GET['id']) ? manageGateways($_GET['id']) : manageGateways();
      break;
    //
		case 'toggleGateway':
      xoops_cp_header();
			toggleGateway($_POST);
      break;
    //
    case 'updateGatewayValues':
      xoops_cp_header();
      updateGatewayValues($_POST);
      break;
    //
    case 'gatewayLogs':
      xoops_cp_header();
      gatewayLogs();
    break;
    //
    case 'showLogDetail':
      xoops_cp_header();
      showLogDetail($_GET['id']);
    break;
    //  
    case 'removeLogItem':
      removeLogItem($_GET['id']);
      break;
    //
    case 'config':
      xoops_cp_header();
      config();
      break;
    //
		case 'updateConfig':
      xoops_cp_header();
			updateConfig($_POST);
      break;
    //
    case 'manageRegion':
      xoops_cp_header();
      manageRegion();
      break;
    //
    case 'addRegion':
      addRegion($_POST);
      break;
    //
    case 'editRegion':
      xoops_cp_header();
      editRegion($_GET['id']);
      break;
    //
    case 'deleteRegion':
      deleteRegion($_GET['id']);
      break;
    //
    case 'orderTracking':
      orderTracking();
      break;
    //
    case 'showOrderLogDetail':
      showOrderLogDetail($_GET['id']);
      break;
    //
    case 'checkTables':
      checkTables();
      break;
    //
    case 'support':
      support();
      break;
    //
    case 'opCompOrder':
      opCompOrder($_POST);
      break;
    //
    case 'opDelOrder':
      opDelOrder($_POST);
      break;
    //    
    case 'membership':
      membership();
      break;
    //
    case 'removeFromGroup':
      removeFromGroup($_GET['id']);
      break;
    //
    case 'doRemoveFromGroup':
      doRemoveFromGroup($_POST['id']);
      break;
    //
    case 'default':
      default:
      xoops_cp_header();
      loadIndex();
      break;
}

//////////////////////////////////////////////////////////////////////////////////////////////////

function loadIndex() {
    /*
  global $oAdminButton, $xoopsTpl;
  $xoopsOption['template_main'] = 'xasset_admin_index.html';
  //
  $hApp       =& xoops_getmodulehandler('application','xasset');
  $hLic       =& xoops_getmodulehandler('license','xasset');
  $hPack      =& xoops_getmodulehandler('package','xasset');
  $hStat      =& xoops_getmodulehandler('userPackageStats','xasset');
  $hLinks     =& xoops_getmodulehandler('link','xasset');
  //
  $xasset_index['applications']     = $hApp->getAllApplicationsCount();
  $xasset_index['licenses']         = $hLic->getAllLicensesCount();
  $xasset_index['files']            = $hPack->getAllPackagesCount();
  $xasset_index['links']            = $hLinks->getAllLinksCount();
  $xasset_index['downloads']        = $hStat->getAllDownloadStats(); 
  //test SSL connectivity
  $fp = fsockopen('ssl://www.paypal.com', 443,$errnum,$errstr,30);
  if (!$fp) 
    $test = array('pass' => false, 'errnum' => $errnum, 'errstr' => $errstr);
  else
    $test = array('pass' => true);
  //
//  $xoopsTpl->assign('xasset_navigation',$oAdminButton->renderButtons('index'));
  $xoopsTpl->assign('xasset_index',$xasset_index);
  $xoopsTpl->assign('xasset_test',$test);
  //$xoopsTpl->assign('xasset_applications',$appsArray);
  //

    */
  require(XASSET_ADMIN_PATH.'/admin_footer.php');
  xoops_cp_footer();
}
//////////////////////////////////////////////////
function manageApplications() {
  global $oAdminButton, $xoopsTpl, $xoopsModuleConfig;
    $index_admin = new ModuleAdmin();
    echo $index_admin->addNavigation("main.php?op=manageApplications");

  $xoopsOption['template_main'] = 'xasset_admin_application_index.html';
  //
  $hApps      =& xoops_getmodulehandler('application','xasset');
  $hTaxClass  =& xoops_getmodulehandler('taxClass','xasset');
  $hCurrency  =& xoops_getmodulehandler('currency','xasset');
  $hGroups    =& xoops_getmodulehandler('applicationGroup','xasset');
  $hPackGroup =& xoops_getmodulehandler('packageGroup','xasset');
//  $hEditor    =& xoops_getmodulehandler('editor','xasset');
  $hMember    =& xoops_gethandler('member');
  //
  $classArray = $hTaxClass->getSelectArray();
  $currArray  = $hCurrency->getSelectArray();
  $appsArray  = $hApps->getApplicationSelectArray();
  $aPackages  =& $hPackGroup->getAllGroupsSelectArray();
  //
  $showProdBlock = (count($classArray) > 0) && (count($currArray) > 0) && (count($appsArray) > 0);
  //
  $criteria   = new CriteriaCompo();
  $criteria->setSort('name');
  //
  $ar = array();
  $ar['permission_cbs']  = $hGroups->getCBGroupString();
  $ar['productsVisible'] = true;
  //
  $aMembers   =& $hMember->getGroups();
  $aGroups    = array();
  $aGroups[0] = 'No Action';
  foreach($aMembers as $group) {
    $aGroups[$group->getVar('groupid')] = $group->getVar('name');
  }
  //
  //$dateField = getDateField('expires',time());
  //$xoopsTpl->assign('applications',$hApps->getApplicationsArray($criteria));
  $xoopsTpl->assign('applications',$hApps->getApplicationsSummaryArray($criteria));
//  $xoopsTpl->assign('xasset_navigation',$oAdminButton->renderButtons('manApp'));
  $xoopsTpl->assign('xasset_app_operation','Create an');
  $xoopsTpl->assign('xasset_app_operation_short','create');
  $xoopsTpl->assign('xasset_applications',$appsArray);
  $xoopsTpl->assign('xasset_tax_classes',$classArray);
  $xoopsTpl->assign('xasset_currencies',$currArray);
  $xoopsTpl->assign('xasset_show_prod_block',$showProdBlock);
  $xoopsTpl->assign('xasset_operation','Add an');
  $xoopsTpl->assign('xasset_operation_short','create');
  $xoopsTpl->assign('xasset_date_field',getDateField('expires',time()));
  $xoopsTpl->assign('xasset_expdate_field',getDateField('group_expire_date',time()));
  $xoopsTpl->assign('xasset_app',$ar);
  $xoopsTpl->assign('xasset_xoops_groups',$aGroups);



//  $xoopsTpl->assign('xasset_app_memo_field',$hEditor->slimEditorDraw('richDescription'));


         if (class_exists('XoopsFormEditor')) {
             $configs = array(
                 'name'   => 'richDescription',
                 'value'  => '',
                 'rows'   => 25,
                 'cols'   => '100%',
                 'width'  => '100%',
                 'height' => '250px'
             );
             $editor = new XoopsFormEditor('', $xoopsModuleConfig['editor_options'], $configs, false, $onfailure = 'textarea');
         } else {
             $editor = new XoopsFormDhtmlTextArea('', 'richDescription', $this->getVar('richDescription', 'e'), '100%', '100%');
         }


  $xoopsTpl->assign('xasset_app_memo_field',$editor->render());


//  $xoopsTpl->assign('xasset_appprod_memo_field',$hEditor->slimEditorDraw('item_rich_description'));

    if (class_exists('XoopsFormEditor')) {
        $configs = array(
            'name'   => 'item_rich_description',
            'value'  => '',
            'rows'   => 25,
            'cols'   => '100%',
            'width'  => '100%',
            'height' => '250px'
        );
        $editor = new XoopsFormEditor('', $xoopsModuleConfig['editor_options'], $configs, false, $onfailure = 'textarea');
    } else {
        $editor = new XoopsFormDhtmlTextArea('', 'item_rich_descriptionn', $this->getVar('item_rich_description', 'e'), '100%', '100%');
    }


    $xoopsTpl->assign('xasset_appprod_memo_field',$editor->render());

  $xoopsTpl->assign('xasset_xoops_packages',$aPackages);
  //
  require(XASSET_ADMIN_PATH.'/admin_footer.php');
  xoops_cp_footer();
}
//////////////////////////////////////////////////
function addApplication($post) {
  $hApp =& xoops_getmodulehandler('application','xasset');
  $hGrp =& xoops_getmodulehandler('applicationGroup','xasset');
  //
  if (isset($post['appid'])) {
    $app =& $hApp->get($post['appid']);
  }
  if (!is_object($app)) {
    $app =& $hApp->create();
    $app->setVar('datePublished',time());
  }
  $app->setVarsFromArray($post);   
  $app->setVar('requiresLicense',isset($post['requiresLicense']));
  $app->setVar('listInEval',isset($post['listInEval']));
  $app->setVar('mainMenu',isset($post['mainMenu']));
  $app->setVar('hasSamples',isset($post['hasSamples']));
  $app->setVar('productsVisible',isset($post['productsVisible']));
  //
  if ($hApp->insert($app)) {
    //now save group permissions
    $hGrp->updateGroup($app->getVar('id'),isset($post['cb']) ? $post['cb'] : null);
    redirect_header('main.php?op=manageApplications',3,'Application Added.'); }
}
//////////////////////////////////////////////////
function deleteApplication($id) {
  xoops_cp_header();
  xoops_confirm( array('id'=>$id), 'main.php?op=doDeleteApplication', 'Are you sure you want to delete this Application?','',true);
  xoops_cp_footer();
}
//////////////////////////////////////////////////
function doDeleteApplication($id) {
  $hApp =& xoops_getmodulehandler('application','xasset');
  if ($hApp->deleteApplication($id)) {
    redirect_header('main.php?op=manageApplications',3,'Application Deleted.'); }
}
//////////////////////////////////////////////////
function editApplication($appid){
  global $oAdminButton, $xoopsTpl, $xoopsModuleConfig;
  $xoopsOption['template_main'] = 'xasset_admin_application_add.html';
  //
  $hApp    =& xoops_getmodulehandler('application','xasset');
  $hGroups =& xoops_getmodulehandler('applicationGroup','xasset');
//  $hEditor =& xoops_getmodulehandler('editor','xasset');
  //
  $app  =& $hApp->get($appid);
  //
  $ar =& $app->getArray();
  $ar['permission_cbs'] = $hGroups->getCBGroupString($appid);
  //
//  $xoopsTpl->assign('xasset_navigation',$oAdminButton->renderButtons('manApp'));
  $xoopsTpl->assign('xasset_app_operation','Edit an');
  $xoopsTpl->assign('xasset_app_operation_short','modify');
  $xoopsTpl->assign('xasset_app',$ar);
//  $xoopsTpl->assign('xasset_app_memo_field',$hEditor->slimEditorDraw('richDescription',$ar['richDescription']));


    if (class_exists('XoopsFormEditor')) {
        $configs = array(
            'name'   => 'richDescription',
            'value'  => $ar['richDescription'],
            'rows'   => 25,
            'cols'   => '100%',
            'width'  => '100%',
            'height' => '250px'
        );
        $editor = new XoopsFormEditor('', $xoopsModuleConfig['editor_options'], $configs, false, $onfailure = 'textarea');
    } else {
        $editor = new XoopsFormDhtmlTextArea('', 'richDescription', $this->getVar('richDescription', 'e'), '100%', '100%');
    }


$xoopsTpl->assign('xasset_app_memo_field',$editor->render());


  //$xoopsTpl->assign('xasset_appprod_memo_field',$hEditor->slimEditorDraw('item_rich_description'));
  //
  require(XASSET_ADMIN_PATH.'/admin_footer.php');
  xoops_cp_footer();
}
//////////////////////////////////////////////////
function manageLicenses() {
  global $oAdminButton, $xoopsTpl;
    $index_admin = new ModuleAdmin();
    echo $index_admin->addNavigation("main.php?op=manageLicenses");
  $xoopsOption['template_main'] = 'xasset_admin_license_index.html';
  //
  $hApp =& xoops_getmodulehandler('application','xasset');
  $hLic =& xoops_getmodulehandler('license','xasset');
  //
//  $xoopsTpl->assign('xasset_navigation',$oAdminButton->renderButtons('manLic'));
  $xoopsTpl->assign('xasset_lic_list',$hLic->getLicenseSummary());
  $xoopsTpl->assign('xasset_lic_select',$hApp->getApplicationSelectArray());
  $xoopsTpl->assign('xasset_users',getGroupClients());
  $xoopsTpl->assign('xasset_date_field',getDateField('expires',time()));
  //
  require(XASSET_ADMIN_PATH.'/admin_footer.php');
  xoops_cp_footer();
}
//////////////////////////////////////////////////
function addLicense($post) {
  $hLic =& xoops_getmodulehandler('license','xasset');
  //
  if ( (isset($post['id'])) && ($post['id'] > 0) ) {
    $lic =& $hLic->get($post['id']);
  } else {
    $lic =& $hLic->create();
  }
  $lic->setVar('uid',$post['userid']);
  $lic->setVar('applicationid',$post['appid']);
  $lic->setVar('authKey',$post['key']);
  $lic->setVar('authCode',$post['authCode']);
  $lic->setVar('expires',$post['expires']);
  $lic->setVar('dateIssued',time());
  //
  if ($hLic->insert($lic)) {
    if (isset($post['adminop'])) {
      redirect_header('main.php?op='.$post['adminop'],3,'License Added.'); }
    else {
      redirect_header('main.php?op=manageLicenses ',3,'License Added.');
    }
  }
}
//////////////////////////////////////////////////
function viewAppLicenses($appid) {
  global $oAdminButton, $xoopsTpl;
  $xoopsOption['template_main'] = 'xasset_admin_license_application.html';
  //
  $hLic =& xoops_getmodulehandler('license','xasset');
  $hApp =& xoops_getmodulehandler('application','xasset');
  //
  $app  =& $hApp->get($appid);
  //
//  $xoopsTpl->assign('xasset_navigation',$oAdminButton->renderButtons('manLic'));
  $xoopsTpl->assign('xasset_appid',$appid);
  $xoopsTpl->assign('xasset_lic_appname',$app->getVar('name'));
  $xoopsTpl->assign('xasset_lic_list', $hLic->getAppLicenses($appid));
  $xoopsTpl->assign('xasset_lic_select',$hApp->getApplicationSelectArray());
  $xoopsTpl->assign('xasset_users',getGroupClients());
  $xoopsTpl->assign('adminop',"viewAppLicenses&id=$appid");
  $xoopsTpl->assign('xasset_date_field',getDateField('expires',time()));
  //
  require(XASSET_ADMIN_PATH.'/admin_footer.php');
  xoops_cp_footer();
}
//////////////////////////////////////////////////
function viewClientLicenses($uid, $appid) {
  global $oAdminButton, $xoopsTpl;
  $xoopsOption['template_main'] = 'xasset_admin_license_client.html';
  //
  $hLic =& xoops_getmodulehandler('license','xasset');
  $hApp =& xoops_getmodulehandler('application','xasset');
  $member_handler =& xoops_gethandler('member');
  //
  $lics = $hLic->getClientLicenses($appid,$uid);
  $user =& $member_handler->getUser($uid);
  $app  =& $hApp->get($appid);
  //
//  $xoopsTpl->assign('xasset_navigation',$oAdminButton->renderButtons('manLic'));
  $xoopsTpl->assign('xasset_appid',$appid);
  $xoopsTpl->assign('xasset_userid',$uid);
  $xoopsTpl->assign('xasset_lic_list',$lics);
  $xoopsTpl->assign('xasset_lic_clientname', $user->name());
  $xoopsTpl->assign('xasset_lic_appname',$app->getVar('name'));
  $xoopsTpl->assign('xasset_lic_select',$hApp->getApplicationSelectArray());
  $xoopsTpl->assign('xasset_users',getGroupClients());
  $xoopsTpl->assign('adminop',"viewClientLicenses&id=$uid&appid=$appid");
  //
  require(XASSET_ADMIN_PATH.'/admin_footer.php');
  xoops_cp_footer();
}
//////////////////////////////////////////////////
function editClientLicense($id) {
  global $oAdminButton, $xoopsTpl;
  $xoopsOption['template_main'] = 'xasset_admin_license_add.html';
  //
  $hLic  =& xoops_getmodulehandler('license','xasset');
  $hApp  =& xoops_getmodulehandler('application','xasset');
  //
  $lic   =& $hLic->get($id);
  //
  $xoopsTpl->assign('xasset_operation_short','modify');
  $xoopsTpl->assign('xasset_operation','Modify');
  $xoopsTpl->assign('xasset_users',getGroupClients());
  $xoopsTpl->assign('xasset_license',$lic->getArray());
  $xoopsTpl->assign('xasset_date_field',getDateField('expires',$lic->getVar('expires')));
  $xoopsTpl->assign('adminop','viewClientLicenses&id='.$lic->getVar('uid').'&appid='.$lic->getVar('applicationid'));
  $xoopsTpl->assign('xasset_lic_select',$hApp->getApplicationSelectArray());
  //
  require(XASSET_ADMIN_PATH.'/admin_footer.php');
  xoops_cp_footer();
}
//////////////////////////////////////////////////
function deleteClientLicense($id) {
  $hLic =& xoops_getmodulehandler('license','xasset');
  //
  if ($hLic->deleteByID($id,true)) {
    redirect_header('main.php?op=manageLicenses',3,'License Deleted.');
  }
}
//////////////////////////////////////////////////
function  managePackages($appid = 0) {
  global $oAdminButton, $xoopsTpl;
    $index_admin = new ModuleAdmin();
    echo $index_admin->addNavigation("main.php?op=managePackages");
    $activeAppID=0;
    $activeAppName='';
  $xoopsOption['template_main'] = 'xasset_admin_packages_index.html';
  //
  $hApp     =& xoops_getmodulehandler('application','xasset');
  $hPackGrp =& xoops_getmodulehandler('packageGroup','xasset');
  //
  $criteria   = new CriteriaCompo();
  $criteria->setSort('name');
  //
  $apps = $hApp->getApplicationsArray($criteria);
  //
  if (count($apps) > 0) {
    if ($appid > 0) {
      $cnt = 0;
      while ($apps[$cnt]['id'] <> $appid) {
        $cnt++;
      }
      $activeAppID   = $apps[$cnt]['id'];
      $activeAppName = $apps[$cnt]['name'];
    } else {
      $activeAppID   = $apps[0]['id'];
      $activeAppName = $apps[0]['name'];
    }
  }
  //
//  $xoopsTpl->assign('xasset_navigation',$oAdminButton->renderButtons('manPack'));
  $xoopsTpl->assign('xasset_applications',$apps);
  $xoopsTpl->assign('xasset_active_appname',$activeAppName);
  $xoopsTpl->assign('xasset_active_appid',$activeAppID);
  $xoopsTpl->assign('xasset_app_packagesgroups',$hPackGrp->getApplicationGroupPackages($activeAppID));
  $xoopsTpl->assign('xasset_app_apppackagesselect',$hPackGrp->getApplicationGroupsSelect($activeAppID));
  $xoopsTpl->assign('xasset_operation','Create a');
  $xoopsTpl->assign('xasset_operation_short','create');
  //
  require(XASSET_ADMIN_PATH.'/admin_footer.php');
  xoops_cp_footer();
}
//////////////////////////////////////////////////
function addPackageGroup($post) {
  $hPackGrp =& xoops_getmodulehandler('packageGroup','xasset');
  //
  if ( isset($post['id']) && ($post['id'] > 0) ) {
    $grp =& $hPackGrp->get($post['id']);
    $op  = 'Edited';}
  else {
    $grp =& $hPackGrp->create();
    $op  = 'Created';
  }
  //
  $grp->setVar('applicationid',$post['appid']);
  $grp->setVar('name',$post['name']);
  $grp->setVar('grpDesc',$post['grpDesc']);
  $grp->setVar('version',$post['version']);
  $grp->setVar('datePublished',time());
  //
  if ($hPackGrp->insert($grp)) {
    redirect_header('main.php?op=managePackages&appid='.$post['appid'],3,"Package Group $op.");
  }
}
//////////////////////////////////////////////////
function deletePackage($id) {
  xoops_cp_header();
  xoops_confirm( array('id'=>$id), 'main.php?op=doDeletePackage', 'Are you sure you want to delete this Package?','',true);
}
//////////////////////////////////////////////////
function doDeletePackage($id) {
  $hPack =& xoops_getmodulehandler('package','xasset');
  $hPack->deleteByID($id);
  redirect_header('main.php?op=managePackages',2,'Package Deleted');
}
//////////////////////////////////////////////////
function editPackage($id) {
  global $oAdminButton, $xoopsTpl;
  //
  $xoopsOption['template_main'] = 'xasset_admin_package_edit.html';
  //
  $crit = new CriteriaCompo(new Criteria('id',$id));
  //
  $hPackGrp =& xoops_getmodulehandler('packageGroup','xasset');
  $hPack    =& xoops_getmodulehandler('package','xasset');
  $pack     =  $hPack->getPackagesArray($crit);   
  //
  $appid    = $hPack->getPackageApplication($id);
  //
//  $xoopsTpl->assign('xasset_navigation',$oAdminButton->renderButtons('manPack'));
  $xoopsTpl->assign('xasset_app_apppackagesselect',$hPackGrp->getApplicationGroupsSelect($appid));
  $xoopsTpl->assign('xasset_package',$pack[0]);
  $xoopsTpl->assign('xasset_operation','Edit');
  $xoopsTpl->assign('xasset_operation_short','modify');
  //
  require(XASSET_ADMIN_PATH.'/admin_footer.php');
  xoops_cp_footer();
}
//////////////////////////////////////////////////
function deletePackageGroup($id) {
  $hPackGrp =& xoops_getmodulehandler('packageGroup','xasset');
  $hPackGrp->deleteByID($id,True);
  //
  redirect_header('main.php?op=managePackages',2,'Package Group Deleted');
}
//////////////////////////////////////////////////
function editPackageGroup($id, $appid) {
  global $oAdminButton, $xoopsTpl;
  //
  $xoopsOption['template_main'] = 'xasset_admin_packagegroup_edit.html';
  //
  $hPackGrp  =& xoops_getmodulehandler('packageGroup','xasset');
  //
  $crit = new CriteriaCompo(new Criteria('id', $id));
  $pGrp      = $hPackGrp->getPackageGroupArray($crit);
  //
//  $xoopsTpl->assign('xasset_navigation',$oAdminButton->renderButtons('manPack'));
  $xoopsTpl->assign('xasset_group', $pGrp[0] );
  $xoopsTpl->assign('xasset_active_appid',$pGrp[0]['applicationid']);
  $xoopsTpl->assign('xasset_operation','Edit');
  $xoopsTpl->assign('xasset_operation_short','modify');
  //
  require(XASSET_ADMIN_PATH.'/admin_footer.php');
  xoops_cp_footer();
}
//////////////////////////////////////////////////
function addPackage($post) {
  $hPack =& xoops_getmodulehandler('package','xasset');
  //check if editing or creating
  if ( isset($post['id']) && ($post['id'] > 0) ) {
    $pack =& $hPack->get($post['id']);
    $op   = 'Edited'; }
  else {
    $pack =& $hPack->create();
    $op   = 'Created';
  }                                     
  $pack->setVarsFromArray($post);
  $pack->setVar('packagegroupid',$post['groupid']);
  $pack->setVar('protected',isset($post['protected']));
  $pack->setVar('isVideo',isset($post['isVideo']));
  //
  if ($hPack->insert($pack)) {
    redirect_header('main.php?op=managePackages&appid='.$post['appid'],3,"Package $op.");
  }
}
//////////////////////////////////////////////////
function manageLinks($appid = null) {
  global $oAdminButton, $xoopsTpl;
    $index_admin = new ModuleAdmin();
    echo $index_admin->addNavigation("main.php?op=manageLinks");
  $xoopsOption['template_main'] = 'xasset_admin_links_index.html';
  //
  $hApp  =& xoops_getmodulehandler('application','xasset');
  $hLink =& xoops_getmodulehandler('link','xasset');
  //
  $apps = $hApp->getApplicationsArray();
  //
  if (count($apps) > 0) {
    if ($appid > 0) {
      $cnt = 0;
      while ($apps[$cnt]['id'] <> $appid) {
        $cnt++;
      }
      $activeAppID   = $apps[$cnt]['id'];
      $activeAppName = $apps[$cnt]['name'];
    } else {
      $activeAppID   = $apps[0]['id'];
      $activeAppName = $apps[0]['name'];
    }
  }
  //
//  $xoopsTpl->assign('xasset_navigation',$oAdminButton->renderButtons('manLink'));
  $xoopsTpl->assign('xasset_links',$hLink->getAllLinks());
  $xoopsTpl->assign('xasset_app_apppackagesselect',$hApp->getApplicationSelectArray());
  $xoopsTpl->assign('xasset_link_function','Create a');
  //
  require(XASSET_ADMIN_PATH.'/admin_footer.php');
  xoops_cp_footer();
}
//////////////////////////////////////////////////////
function addLink($post) {
  $hLink =& xoops_getmodulehandler('link','xasset');
  //
  if (isset($post['id'])) {
    $link =& $hLink->get($post['id']);
  }
  if (!is_object($link)) {
    $link =& $hLink->create();
  }
  //
  $link->setVar('applicationid',$post['appid']);
  $link->setVar('name',$post['name']);
  $link->setVar('link',$post['link']);
  //
  if ($hLink->insert($link)) {
    redirect_header('main.php?op=manageLinks',3,'Link Added.');
  }
}
//////////////////////////////////////////////////////
function deleteLink($linkid) {
  $hLink =& xoops_getmodulehandler('link','xasset');
  //
  if ($hLink->deleteByID($linkid)) {
    redirect_header('main.php?op=manageLinks',3,'Link Deleted.');
  }
}
//////////////////////////////////////////////////////
function editLink($linkid) {
  global $oAdminButton, $xoopsTpl;
  $xoopsOption['template_main'] = 'xasset_admin_links_index.html';
  //
  $hLink =& xoops_getmodulehandler('link','xasset');
  $hApp  =& xoops_getmodulehandler('application','xasset');
  //
  $link  =& $hLink->get($linkid);
  //
  $xasset_link['id']    = $link->getVar('id');
  $xasset_link['appid'] = $link->getVar('applicationid');
  $xasset_link['name']  = $link->getVar('name');
  $xasset_link['link']  = $link->getVar('link');
  //
//  $xoopsTpl->assign('xasset_navigation',$oAdminButton->renderButtons('manLink'));
  $xoopsTpl->assign('xasset_app_apppackagesselect',$hApp->getApplicationSelectArray());
  $xoopsTpl->assign('xasset_link_function','Edit');
  $xoopsTpl->assign('xasset_link',$xasset_link);
  //
  require(XASSET_ADMIN_PATH.'/admin_footer.php');
  xoops_cp_footer();
}
//////////////////////////////////////////////////////
function viewDownloadStats($appid = null) {
  global $oAdminButton, $xoopsTpl;
    $index_admin = new ModuleAdmin();
    echo $index_admin->addNavigation("main.php?op=viewDownloadStats");
  $xoopsOption['template_main'] = 'xasset_admin_download_stats_index.html';
  //
  $hPackGrp  =& xoops_getmodulehandler('packageGroup','xasset');
  $hApp      =& xoops_getmodulehandler('application','xasset');
  //
  if (!($appid>0)) {
    $crit = new criteriaCompo();
    $crit->setLimit(1);
    $objs =& $hApp->getApplicationsArray($crit);
    if (count($objs) > 0) {
      $appid = $objs[0]['id'];
    } else {
      $xoopsTpl->assign('xasset_navigation','Error: No applications defined');//hack..will need to do an error screen
      require(XASSET_ADMIN_PATH.'/admin_footer.php');
      xoops_cp_footer();
			exit;
    }
  }
  $app       =& $hApp->get($appid);
  $apps      =  $hApp->getApplicationsSummaryArray();
  //
//  $xoopsTpl->assign('xasset_navigation',$oAdminButton->renderButtons('manStat'));
  $xoopsTpl->assign('xasset_application', $app->getVar('name'));
  $xoopsTpl->assign('xasset_application_id',$appid);
  $xoopsTpl->assign('xasset_stats', $hPackGrp->getDownloadByApplicationSummaryArray($appid));
  $xoopsTpl->assign('xasset_application_list',$apps);
  $xoopsTpl->assign('xasset_application_count',count($apps));
  //
  require(XASSET_ADMIN_PATH.'/admin_footer.php');
  xoops_cp_footer();
}
//////////////////////////////////////////////////////
function deleteStat($id){
  $hStat =& xoops_getmodulehandler('userPackageStats','xasset');
  if ($hStat->deleteByID($id,true)) {
    redirect_header('main.php?op=viewDownloadStats',3,'Stat Deleted.');
  }
}
//////////////////////////////////////////////////////
function manageCountries() {
  global $oAdminButton, $xoopsTpl;
    $index_admin = new ModuleAdmin();
    echo $index_admin->addNavigation("main.php?op=manageCountries");
  $xoopsOption['template_main'] = 'xasset_admin_country_index.html';
  //
  $hCnt  =& xoops_getmodulehandler('country','xasset');
  $cnts = $hCnt->getCountriesArray();
  //
//  $xoopsTpl->assign('xasset_navigation',$oAdminButton->renderButtons('manCount'));
  $xoopsTpl->assign('xasset_operation','Add a');
  $xoopsTpl->assign('xasset_operation_short','create');
  $xoopsTpl->assign('xasset_country_count',count($cnts));
  $xoopsTpl->assign('xasset_countries',$cnts);
  //
  require(XASSET_ADMIN_PATH.'/admin_footer.php');
  xoops_cp_footer();
}
//////////////////////////////////////////////////////
function addCountry($post) {
  $hCnt =& xoops_getmodulehandler('country','xasset');
  //
  if (isset($post['countryid']) && ($post['countryid'] > 0)) {
    $cnt =& $hCnt->get($post['countryid']);}
  else { $cnt =& $hCnt->create();
  }
  //
  $cnt->setVar('name',$post['name']);
  $cnt->setVar('iso2',$post['iso2']);
  $cnt->setVar('iso3',$post['iso3']);
  //
  if ($hCnt->insert($cnt)) {
    redirect_header('main.php?op=manageCountries',3,'Country Added.');
  }
}
//////////////////////////////////////////////////////
function editCountry($id){
  global $oAdminButton, $xoopsTpl;
  $xoopsOption['template_main'] = 'xasset_admin_country_index.html';
  //
  $hCnt  =& xoops_getmodulehandler('country','xasset');
  $cnt   =& $hCnt->get($id);
  //
  $ary['id']     = $cnt->getVar('id');
  $ary['name']   = $cnt->getVar('name');
  $ary['iso2']   = $cnt->getVar('iso2');
  $ary['iso3']   = $cnt->getVar('iso3');
  //
//  $xoopsTpl->assign('xasset_navigation',$oAdminButton->renderButtons('manCount'));
  $xoopsTpl->assign('xasset_country',$ary);
  $xoopsTpl->assign('xasset_operation','Edit');
  $xoopsTpl->assign('xasset_operation_short','modify');
  //
  require(XASSET_ADMIN_PATH.'/admin_footer.php');
  xoops_cp_footer();
}
//////////////////////////////////////////////////////
function manageZones() {
  global $oAdminButton, $xoopsTpl;
    $index_admin = new ModuleAdmin();
    echo $index_admin->addNavigation("main.php?op=manageZones");
  $xoopsOption['template_main'] = 'xasset_admin_zone_index.html';
  //
  $hZone  =& xoops_getmodulehandler('zone','xasset');
  $hCount =& xoops_getmodulehandler('country','xasset');
  //
  $zones  = $hZone->getZonesArray();
  //
//  $xoopsTpl->assign('xasset_navigation',$oAdminButton->renderButtons('manZone'));
  $xoopsTpl->assign('xasset_operation','Add a');
  $xoopsTpl->assign('xasset_operation_short','create');
  $xoopsTpl->assign('xasset_zones_count',count($zones));
  $xoopsTpl->assign('xasset_zones',$zones);
  $xoopsTpl->assign('xasset_countries',$hCount->getCountriesSelect());
  //
  require(XASSET_ADMIN_PATH.'/admin_footer.php');
  xoops_cp_footer();
}
//////////////////////////////////////////////////////
function addZone($post) {
  $hZone =& xoops_getmodulehandler('zone','xasset');
  //
  if ( isset($post['zoneid']) && ($post['zoneid'] > 0) ) {
    $zone =& $hZone->get($post['zoneid']); }
  else {
    $zone =& $hZone->create();
  }
  //
	$zone->setVar('country_id',$post['country_id']);
  $zone->setVar('code',$post['code']);
  $zone->setVar('name',$post['name']);
  //
  if ($hZone->insert($zone)) {
    redirect_header('main.php?op=manageZones',3,'Zone Added.');
  }
}
//////////////////////////////////////////////////////
function editZone($id) {
  global $oAdminButton, $xoopsTpl;
  $xoopsOption['template_main'] = 'xasset_admin_zone_index.html';
  //
  $hZone  =& xoops_getmodulehandler('zone','xasset');
  $hCount =& xoops_getmodulehandler('country','xasset');
  //
  $zone   =& $hZone->get($id);
  //
  $ary['id']         = $zone->getVar('id');
  $ary['country_id'] = $zone->getVar('country_id');
  $ary['code']       = $zone->getVar('code');
  $ary['name']       = $zone->getVar('name');
  //
//  $xoopsTpl->assign('xasset_navigation',$oAdminButton->renderButtons('manZone'));
  $xoopsTpl->assign('xasset_zone',$ary);
  $xoopsTpl->assign('xasset_operation','Edit');
  $xoopsTpl->assign('xasset_operation_short','modify');
  $xoopsTpl->assign('xasset_countries',$hCount->getCountriesSelect());
  //
  require(XASSET_ADMIN_PATH.'/admin_footer.php');
  xoops_cp_footer();
}
//////////////////////////////////////////////////////
function deleteZone($id) {
  $hZone =& xoops_getmodulehandler('zone','xasset');
  if ($hZone->deleteByID($id,true)) {
    redirect_header('main.php?op=manageZones',2,'Zone Deleted.');
  }
}
//////////////////////////////////////////////////////
function manageTaxes() {
  global $oAdminButton, $xoopsTpl;
    $index_admin = new ModuleAdmin();
    echo $index_admin->addNavigation("main.php?op=manageTaxes");
  $xoopsOption['template_main'] = 'xasset_admin_taxrates_index.html';
  //
  $hZone     =& xoops_getmodulehandler('zone','xasset');
  $hCount    =& xoops_getmodulehandler('country','xasset');
  $hTaxClass =& xoops_getmodulehandler('taxClass','xasset');
  $hTaxRate  =& xoops_getmodulehandler('taxRate','xasset');
  $hTaxZone  =& xoops_getmodulehandler('taxZone','xasset');
  $hRegion   =& xoops_getmodulehandler('region','xasset');
  //
  $classes  = $hTaxClass->getClassArray();
  $rates    = $hTaxRate->getRegionOrderedRatesArray();//getRatesArray();
  $classSel = $hTaxClass->getSelectArray();
  //
  $countArray   =& $hCount->getCountriesSelect();
  $regionArray  =& $hRegion->getSelectArray();
  $taxZoneArray =& $hTaxZone->getAllTaxZoneArray();
  //
  $zoneArray = array();
  if ( count($countArray) > 0 ) {
    $tmp        = reset($countArray);
    $tmp = !empty($tmp['id']) ? $tmp['id'] : 0 ; // PHP 5.4 changes how empty() behaves when passed string offsets.
    $zoneArray  =& $hZone->getZonesByCountry($tmp);
  }
  $zoneArrayCnt = count($zoneArray);
  //$zonesScript = $hCount->constructSelectJavascript('zone_id','country_id');
  //insert script into header
  echo insertHeaderCountriesJavaScript();
  //
//  $xoopsTpl->assign('xasset_navigation',$oAdminButton->renderButtons('manTax'));
  $xoopsTpl->assign('xasset_operation','Add a');
  $xoopsTpl->assign('xasset_operation_short','create');
  $xoopsTpl->assign('xasset_class_count',count($classes));
  $xoopsTpl->assign('xasset_rates_count',count($rates));
  $xoopsTpl->assign('xasset_classes',$classes);
  $xoopsTpl->assign('xasset_rates',$rates);
  $xoopsTpl->assign('xasset_tax_classes',$classSel);
  $xoopsTpl->assign('xasset_tax_classes_count',count($classSel));
  $xoopsTpl->assign('xasset_tax_zone_count',count($taxZoneArray));
  $xoopsTpl->assign('xasset_tax_zones',$taxZoneArray);
  //$xoopsTpl->assign('xasset_zones',$zoneArray);
  $xoopsTpl->assign('xasset_countries_select',$countArray);
  $xoopsTpl->assign('xasset_region_select',$regionArray);
  //$xoopsTpl->assign('xasset_regions',$regionArray);
  $xoopsTpl->assign('xasset_zone_select',$zoneArray);
  $xoopsTpl->assign('xasset_show_class',1);
  $xoopsTpl->assign('xasset_show_region', ($zoneArrayCnt > 0) && (count($countArray) > 0) );
  //
  require(XASSET_ADMIN_PATH.'/admin_footer.php');
  xoops_cp_footer();
}
//////////////////////////////////////////////////////
function addTaxClass($post) {
  $hClass =& xoops_getmodulehandler('taxClass','xasset');
  //
  if ( isset($post['taxclassid']) && ($post['taxclassid'] > 0) ) {
    $class =& $hClass->get($post['taxclassid']); }
  else {
    $class =& $hClass->create();
  }
  //
  $class->setVar('code',$post['code']);
  $class->setVar('description',$post['description']);
  //
  if ($hClass->insert($class)) {
    redirect_header('main.php?op=manageTaxes',3,'Tax Class Added.');
  }
}
//////////////////////////////////////////////////////
function addTaxZone($post) {
  $hTZone =& xoops_getmodulehandler('taxZone','xasset');
  //
  if ( (isset($post['taxzoneid'])) && ($post['taxzoneid'] > 0) ) {
    $zone =& $hTZone->get($post['taxzoneid']);
  } else {
    $zone =& $hTZone->create();
  }
  //
  $zone->setVarsFromArray($post);
  //
  if ($hTZone->insert($zone)) {
    redirect_header('main.php?op=manageTaxes',3,'Tax Zone Added.');
  }
}
//////////////////////////////////////////////////////
function editTaxZone($id) {
  global $oAdminButton, $xoopsTpl;
  $xoopsOption['template_main'] = 'xasset_admin_tax_region_zone.html';
  //
  $hZone     =& xoops_getmodulehandler('zone','xasset');
  $hTZone    =& xoops_getmodulehandler('taxZone','xasset');
  $hRegion   =& xoops_getmodulehandler('region','xasset');
  $hCount    =& xoops_getmodulehandler('country','xasset');
  //
  echo insertHeaderCountriesJavaScript();
  //
  $taxZone         =& $hTZone->get($id);
  $countArray   =& $hCount->getCountriesSelect();
  $regionArray  =& $hRegion->getSelectArray();
  //
  $ary    =& $taxZone->getArray();
  //
  if ($ary['country_id'] > 0) {
    $zoneSelect =& $hZone->getZonesByCountry($ary['country_id']);
  }
  //
//  $xoopsTpl->assign('xasset_navigation',$oAdminButton->renderButtons('manTax'));
  $xoopsTpl->assign('xasset_tax_zone',$ary);
  $xoopsTpl->assign('xasset_region_select',$regionArray);
  $xoopsTpl->assign('xasset_countries_select',$countArray);
  $xoopsTpl->assign('xasset_zone_select',$zoneSelect);
  $xoopsTpl->assign('xasset_operation','Edit');
  $xoopsTpl->assign('xasset_operation_short','modify');
  $xoopsTpl->assign('xasset_show_class',1);
  //
  require(XASSET_ADMIN_PATH.'/admin_footer.php');
  xoops_cp_footer();
}
//////////////////////////////////////////////////////
function deleteTaxZone($id) {
  $hZone =& xoops_getmodulehandler('taxZone','xasset');
  if ($hZone->deleteByID($id, true)) {
    redirect_header('main.php?op=manageTaxes',3,'Tax Zone Deleted.');
  }
}
//////////////////////////////////////////////////////
function addTaxRate($post) {
  $hRate =& xoops_getmodulehandler('taxRate','xasset');
  //
  if (isset($post['taxrateid']) && ($post['taxrateid'] > 0) ){
    $rate =& $hRate->get($post['taxrateid']); }
  else {
    $rate =& $hRate->create();
	}
	//
  $rate->setVarsFromArray($post);
  //
  if ($hRate->insert($rate)) {
		redirect_header('main.php?op=manageTaxes',3,'Tax Rate Added.');
  }
}
//////////////////////////////////////////////////////
function editTaxClass($id) {
  global $oAdminButton, $xoopsTpl;
  $xoopsOption['template_main'] = 'xasset_admin_taxrates_index.html';
  //
  $hClass =& xoops_getmodulehandler('taxClass','xasset');
  $class  =& $hClass->get($id);
  //
  $ary['id']          = $class->getVar('id');
  $ary['code']        = $class->getVar('code');
  $ary['description'] = $class->getVar('description');
  //
//  $xoopsTpl->assign('xasset_navigation',$oAdminButton->renderButtons('manTax'));
  $xoopsTpl->assign('xasset_tax_class',$ary);
  $xoopsTpl->assign('xasset_operation','Edit');
  $xoopsTpl->assign('xasset_operation_short','modify');
  $xoopsTpl->assign('xasset_show_class',1);
  //
  require(XASSET_ADMIN_PATH.'/admin_footer.php');
  xoops_cp_footer();
}
//////////////////////////////////////////////////////
function editTaxRate($id) {
  global $oAdminButton, $xoopsTpl;
  $xoopsOption['template_main'] = 'xasset_admin_taxrates_index.html';
  //
  $hRate     =& xoops_getmodulehandler('taxRate','xasset');
  $hRegion   =& xoops_getmodulehandler('region','xasset');
  $hTaxClass =& xoops_getmodulehandler('taxClass','xasset');
  //
  $rate  =& $hRate->get($id);
  //
  $ary =& $rate->getArray();
  //
//  $xoopsTpl->assign('xasset_navigation',$oAdminButton->renderButtons('manTax'));
  $xoopsTpl->assign('xasset_tax_rate',$ary);
  $xoopsTpl->assign('xasset_operation','Edit');
  $xoopsTpl->assign('xasset_operation_short','modify');
  $xoopsTpl->assign('xasset_show_class',0);
  $xoopsTpl->assign('xasset_tax_classes_count',1);
  $xoopsTpl->assign('xasset_tax_classes',$hTaxClass->getSelectArray());
  $xoopsTpl->assign('xasset_region_select',$hRegion->getSelectArray());
  //
  require(XASSET_ADMIN_PATH.'/admin_footer.php');
  xoops_cp_footer();
}
//////////////////////////////////////////////////////
function deleteTaxClass($id) {
  $hClass =& xoops_getmodulehandler('taxClass','xasset');
  if ($hClass->deleteClass($id)) {
    redirect_header('main.php?op=manageTaxes',3,'Tax Class Deleted.');
  }
}
//////////////////////////////////////////////////////
function deleteTaxRate($id) {
  $hRate =& xoops_getmodulehandler('taxRate','xasset');
  if ($hRate->deleteRate($id, true)) {
    redirect_header('main.php?op=manageTaxes',3,'Tax Rate Deleted.');
  }
}
//////////////////////////////////////////////////////
function manageCurrencies() {
  global $oAdminButton, $xoopsTpl;
    $index_admin = new ModuleAdmin();
    echo $index_admin->addNavigation("main.php?op=manageCurrencies");
  $xoopsOption['template_main'] = 'xasset_admin_currency_index.html';
  //
  $hCurrency =& xoops_getmodulehandler('currency','xasset');
  //
  $currs = $hCurrency->getCurrencyArray(); 
  //
//  $xoopsTpl->assign('xasset_navigation',$oAdminButton->renderButtons('manCurr'));
  $xoopsTpl->assign('xasset_operation','Add a');
  $xoopsTpl->assign('xasset_operation_short','create');
  $xoopsTpl->assign('xasset_currencies',$currs);
  $xoopsTpl->assign('xasset_currencies_count',count($currs));
  //
  require(XASSET_ADMIN_PATH.'/admin_footer.php');
  xoops_cp_footer();
}
//////////////////////////////////////////////////////
function addCurrency($post) {
  $hCurrency =& xoops_getmodulehandler('currency','xasset');
  //
  if (isset($post['currencyid']) && ($post['currencyid'] > 0) ){
    $curr =& $hCurrency->get($post['currencyid']);
  } else {
    $curr =& $hCurrency->create();
  }
  //
  $curr->setVar('name',$post['name']);
  $curr->setVar('code',$post['code']);
  $curr->setVar('decimal_places',$post['decimal_places']);
  $curr->setVar('symbol_left',$post['symbol_left']);
  $curr->setVar('symbol_right',$post['symbol_right']);
  $curr->setVar('decimal_point',$post['decimal_point']);
  $curr->setVar('thousands_point',$post['thousands_point']);
  $curr->setVar('value',$post['value']);
  $curr->setVar('updated',time());
  //
  if ($hCurrency->insert($curr)){
    redirect_header('main.php?op=manageCurrencies',2,'Currency Added.');
  }
}
//////////////////////////////////////////////////////
function editCurrency($id) {
  global $oAdminButton, $xoopsTpl;
  $xoopsOption['template_main'] = 'xasset_admin_currency_index.html';
  //
  $hCurrency =& xoops_getmodulehandler('currency','xasset');
  //
  $curr =& $hCurrency->get($id);
  //
  $ar['id']               = $curr->getVar('id');
  $ar['name']             = $curr->getVar('name');
  $ar['code']             = $curr->getVar('code');
  $ar['decimal_places']   = $curr->getVar('decimal_places');
  $ar['symbol_left']      = $curr->getVar('symbol_left');
  $ar['symbol_right']     = $curr->getVar('symbol_right');
  $ar['decimal_point']    = $curr->getVar('decimal_point');
  $ar['thousands_point']  = $curr->getVar('thousands_point');
  $ar['value']            = $curr->getVar('value');
  $ar['updated']          = $curr->getVar('updated');
  //
//  $xoopsTpl->assign('xasset_navigation',$oAdminButton->renderButtons('manCurr'));
  $xoopsTpl->assign('xasset_operation','Edit');
  $xoopsTpl->assign('xasset_operation_short','modify');
  $xoopsTpl->assign('xasset_currency',$ar);
  //
  require(XASSET_ADMIN_PATH.'/admin_footer.php');
  xoops_cp_footer();
}
//////////////////////////////////////////////////////
function deleteCurrency($id) {
  $hCurrency =& xoops_getmodulehandler('currency','xasset');
  if ($hCurrency->deleteByID($id,true)) {
    redirect_header('main.php?op=manageCurrency',3,'Currency Deleted.');
  }
}
//////////////////////////////////////////////////////
function addAppProduct($post) {
  $hAppProd =& xoops_getmodulehandler('applicationProduct','xasset');
  //
  if ( isset($post['appprodid']) && ($post['appprodid'] > 0)) {
    $prod =& $hAppProd->get($post['appprodid']);
  } else {
    $prod =& $hAppProd->create();
  }
  //
  $prod->setVarsFromArray($post);
  $prod->setVar('enabled',isset($post['enabled']));      
  // set expiry date
  if ($prod->getVar('add_to_group') > 0) {
    if ($post['rbGrpExpire'] == -1) {
      $prod->setVar('group_expire_date',$post['expire_days']);
    } else {
      $prod->setVar('group_expire_date',$post['rbGrpExpire']);
    }
  } else {
    $prod->setVar('group_expire_date',0);
  }
  if ($prod->getVar('add_to_group2') > 0) {
    if ($post['rbGrpExpire2'] == -1) {
      $prod->setVar('group_expire_date2',$post['expire_days2']);
    } else {
      $prod->setVar('group_expire_date2',$post['rbGrpExpire2']);
    }
  } else {
    $prod->setVar('group_expire_date2',0);
  }
  //
  if ($hAppProd->insert($prod)){
    redirect_header('main.php?op=manageApplications',2,'Application Product Added.');
  }
}
//////////////////////////////////////////////////////
function deleteAppProduct($id) {
  xoops_cp_header();
  xoops_confirm( array('id'=>$id), 'main.php?op=doDeleteAppProduct', 'Are you sure you want to delete this Application Product?','',true);
}
//////////////////////////////////////////////////////
function doDeleteAppProduct($id) {
  $hAppProd =& xoops_getmodulehandler('applicationProduct','xasset');
  if ($hAppProd->deleteByID($id)) {
    redirect_header('main.php?op=manageApplications',3,'Application Product Deleted.'); }
}
//////////////////////////////////////////////////////
function editAppProduct($id){
  global $oAdminButton, $xoopsTpl, $xoopsModuleConfig;
  $xoopsOption['template_main'] = 'xasset_admin_applicaion_product_add.html';
  //
  $hApps      =& xoops_getmodulehandler('application','xasset');
  $hTaxClass  =& xoops_getmodulehandler('taxClass','xasset');
  $hCurrency  =& xoops_getmodulehandler('currency','xasset');
  $hAppProd   =& xoops_getmodulehandler('applicationProduct','xasset');
  $hPackGroup =& xoops_getmodulehandler('packageGroup','xasset');
//  $hEditor    =& xoops_getmodulehandler('editor','xasset');
  $hMember    =& xoops_gethandler('member');
  //
  $classArray = $hTaxClass->getSelectArray();
  $currArray  = $hCurrency->getSelectArray();
  $appsArray  = $hApps->getApplicationSelectArray();
  //
  $prod       =& $hAppProd->get($id);
  $aPackages  =& $hPackGroup->getAllGroupsSelectArray();
  $ar         =& $prod->getArray();  
  //
  $aMembers   =& $hMember->getGroups();
  $aGroups    = array();
  $aGroups[0] = 'No Action';
  foreach($aMembers as $group) {
    $aGroups[$group->getVar('groupid')] = $group->getVar('name');
  }
  //
  //$dateField = getDateField('expires',$prod->getVar('expires'));
  //
//  $xoopsTpl->assign('xasset_navigation',$oAdminButton->renderButtons('manApp'));
  $xoopsTpl->assign('xasset_operation','Edit');
  $xoopsTpl->assign('xasset_operation_short','modify');
  $xoopsTpl->assign('xasset_app_product',$ar);
  $xoopsTpl->assign('xasset_applications',$appsArray);
  $xoopsTpl->assign('xasset_tax_classes',$classArray);
  $xoopsTpl->assign('xasset_currencies',$currArray);
  $xoopsTpl->assign('xasset_date_field',getDateField('expires',$prod->getVar('expires')));
  $xoopsTpl->assign('xasset_expdate_field',getDateField('group_expire_date',$prod->getVar('group_expire_date')));


//    $xoopsTpl->assign('xasset_appprod_memo_field', $hEditor->slimEditorDraw('item_rich_description', $ar['item_rich_description']));

    if (class_exists('XoopsFormEditor')) {
        $configs = array(
            'name'   => 'item_rich_description',
            'value'  => $ar['item_rich_description'],
            'rows'   => 25,
            'cols'   => '100%',
            'width'  => '100%',
            'height' => '250px'
        );
        $editor  = new XoopsFormEditor('', $xoopsModuleConfig['editor_options'], $configs, false, $onfailure = 'textarea');
    } else {
        $editor = new XoopsFormDhtmlTextArea('', 'item_rich_description', $this->getVar('item_rich_description', 'e'), '100%', '100%');
    }


    $xoopsTpl->assign('xasset_appprod_memo_field', $editor->render());


    $xoopsTpl->assign('xasset_xoops_groups',$aGroups);
  $xoopsTpl->assign('xasset_xoops_packages',$aPackages);
  //
  if ($prod->getVar('group_expire_date') > 0) {
    $xoopsTpl->assign('xasset_date_checked','checked="checked"');
  }
  require(XASSET_ADMIN_PATH.'/admin_footer.php');
  xoops_cp_footer();
}
//////////////////////////////////////////////////////
function manageGateways($id = null) {
  global $oAdminButton, $xoopsTpl;
    $index_admin = new ModuleAdmin();
    echo $index_admin->addNavigation("main.php?op=manageGateways");
  $xoopsOption['template_main'] = 'xasset_admin_gateway_index.html';
  //
  $hGateway  =& xoops_getmodulehandler('gateway','xasset');
  //
  $gateway = 0;
  $gateways  = $hGateway->parseGatewayModules();
  $installed = $hGateway->getInstalledGatewayArray();
  //
  if (isset($id) && ($id > 0)) {
    $gateway =& $hGateway->get($id);
  } else {
    if (count($gateways) > 0) {
      $gateway = reset($installed);
      $gateway =& $hGateway->get($gateway['id']);
    }
	}
	//
	if (is_object($gateway)) {
		$gateConfigs = $gateway->getDetailArray();
		$xoopsTpl->assign('xasset_config',$gateConfigs);
		$xoopsTpl->assign('xasset_gateway_id',$gateway->getVar('id'));
		$xoopsTpl->assign('xasset_gateway_name',$gateway->getVar('code'));
    $xoopsTpl->assign('xasset_gateway_count',1);
	}
	//
//	$xoopsTpl->assign('xasset_navigation',$oAdminButton->renderButtons('manGate'));
	$xoopsTpl->assign('xasset_gateway',$gateways);
	$xoopsTpl->assign('xasset_installed_gateway',$installed);
	//
  require(XASSET_ADMIN_PATH.'/admin_footer.php');
  xoops_cp_footer();
}
//////////////////////////////////////////////////////
function toggleGateway($post) {
  $hGateway  =& xoops_getmodulehandler('gateway','xasset');
  //
  foreach($post['gateway'] as $class) {
    if (isset($post['bEnable'])) {
      $hGateway->enableGateway($class);
    } else if (isset($post['bDisable'])) {
      $hGateway->disableGateway($class);
    }
  }
  //
  redirect_header('main.php?op=manageGateways',2,'Gateway Updated.');
}
//////////////////////////////////////////////////////
function updateGatewayValues($post){
  $id = $post['gateway_id'];
  //
  $hGateway =& xoops_getmodulehandler('gateway','xasset');
  //
  $gateway =& $hGateway->get($id);
  //
  foreach($post['values'] as $key=>$value) {
    $gateway->saveConfigValue($key,$value);
  }
  //now sort binary keys
  $gateway->toggleBinaryValues($post['values']);
  //
  redirect_header("main.php?op=manageGateways&id=$id",2,'Gateway Values Updated.');
}
//////////////////////////////////////////////////////
function gatewayLogs() {
  global $oAdminButton, $xoopsTpl;
    $index_admin = new ModuleAdmin();
    echo $index_admin->addNavigation("main.php?op=gatewayLogs");
  $xoopsOption['template_main'] = 'xasset_admin_gateway_log_index.html';
  //
  $hGateway  =& xoops_getmodulehandler('gatewayLog','xasset');
  //
  $gateLogs  = $hGateway->getLogs();
  //
//  $xoopsTpl->assign('xasset_navigation',$oAdminButton->renderButtons('gateLogs'));
  $xoopsTpl->assign('xasset_logs',$gateLogs);
  //
  require(XASSET_ADMIN_PATH.'/admin_footer.php');
  xoops_cp_footer();
}
//////////////////////////////////////////////////////
function showLogDetail($id) {
  global $oAdminButton, $xoopsTpl;
  $xoopsOption['template_main'] = 'xasset_admin_gateway_log_detail.html';
  //
  $hGateway  =& xoops_getmodulehandler('gatewayLog','xasset');
  $oGateLog  = $hGateway->get($id);  
  $aGateLog  = $oGateLog->getArray();   
  //
//  $xoopsTpl->assign('xasset_navigation',$oAdminButton->renderButtons('gateLogs'));
  $xoopsTpl->assign('xasset_log',$aGateLog);
  //
  require(XASSET_ADMIN_PATH.'/admin_footer.php');
  xoops_cp_footer();
}
//////////////////////////////////////////////////////
function removeLogItem($id) {
  $hGate =& xoops_getmodulehandler('gatewayLog','xasset');
  if ($hRate->deleteByID($id, true)) {
    redirect_header('main.php?op=gatewayLogs',3,'Gatway Log Enry deleted.');
  }
}
//////////////////////////////////////////////////////
function config() {
  global $oAdminButton, $xoopsTpl;
  $xoopsOption['template_main'] = 'xasset_admin_config.html';
  //
  $hConfig =& xoops_getmodulehandler('config','xasset');
  $hCurr   =& xoops_getmodulehandler('currency','xasset');
  $hMember =& xoops_gethandler('member');
  //
  $groups  = $hMember->getGroups();
  $curs    = $hCurr->getCurrencyArray();
  //
  $ar = array();
  foreach($groups as $group) {
    $ar[$group->getVar('groupid')] = $group->getVar('name');
  }
  //
//  $xoopsTpl->assign('xasset_navigation',$oAdminButton->renderButtons('index'));
  $xoopsTpl->assign('xasset_config',$hConfig->getConfigArray());
  $xoopsTpl->assign('xasset_config_currencies',$hCurr->getSelectArray());
  $xoopsTpl->assign('xasset_config_groups',$ar);
  //
  require(XASSET_ADMIN_PATH.'/admin_footer.php');
  xoops_cp_footer();
}
//////////////////////////////////////////////////////
function manageRegion(){
  global $oAdminButton, $xoopsTpl;
    $index_admin = new ModuleAdmin();
    echo $index_admin->addNavigation("main.php?op=manageRegion");
  $xoopsOption['template_main'] = 'xasset_admin_region_index.html';
  //
  $hRegion =& xoops_getmodulehandler('region','xasset');
  //
  $regions = $hRegion->getRegionArray();
  //
//  $xoopsTpl->assign('xasset_navigation',$oAdminButton->renderButtons('manRegion'));
  $xoopsTpl->assign('xasset_operation','Add a');
  $xoopsTpl->assign('xasset_operation_short','create');
  $xoopsTpl->assign('xasset_regions',$regions);
  $xoopsTpl->assign('xasset_region_count',count($regions));
  //
  require(XASSET_ADMIN_PATH.'/admin_footer.php');
  xoops_cp_footer();
}
//////////////////////////////////////////////////////
function addRegion($post) {
  $hRegion =& xoops_getmodulehandler('region','xasset');
  //
  if (isset($post['regionid']) && ($post['regionid'] > 0) ){
    $region =& $hRegion->get($post['regionid']);
  } else {
    $region =& $hRegion->create();
  }
  //
  $region->setVarsFromArray($post);
  //
  if ($hRegion->insert($region)){
    redirect_header('main.php?op=manageRegion',2,'Region Added.');
  }
}
//////////////////////////////////////////////////////
function editRegion($id) {
  global $oAdminButton, $xoopsTpl;
  $xoopsOption['template_main'] = 'xasset_admin_region_index.html';
  //
  $hRegion  =& xoops_getmodulehandler('region','xasset');
  $region   =& $hRegion->get($id);
  //
  $ary =& $region->getArray();
  //
//  $xoopsTpl->assign('xasset_navigation',$oAdminButton->renderButtons('manRegion'));
  $xoopsTpl->assign('xasset_region',$ary);
  $xoopsTpl->assign('xasset_operation','Edit');
  $xoopsTpl->assign('xasset_operation_short','modify');
  //
  require(XASSET_ADMIN_PATH.'/admin_footer.php');
  xoops_cp_footer();
}
//////////////////////////////////////////////////////
function deleteRegion($id) {
  $hRegion =& xoops_getmodulehandler('region','xasset');
  //can we delete if this region is used in tax zones
  if ($hRegion->deleteByID($id,true)) {
    redirect_header('main.php?op=manageRegion',3,'Region Deleted.');
  }
}
//////////////////////////////////////////////////////
function updateConfig($post) {
	$hConfig =& xoops_getmodulehandler('config','xasset');
  //
  $hConfig->setGroup($post['group_id']);
  $hConfig->setEmailGroup($post['email_group_id']);
	$hConfig->setBaseCurrency($post['currencyid']);
	//
  redirect_header('main.php',2,'Configuration Updated.');
}
//////////////////////////////////////////////////////
function orderTracking() {
  global $oAdminButton, $xoopsTpl;
  $xoopsOption['template_main'] = 'xasset_admin_order_tracking.html';
  xoops_cp_header();

    $index_admin = new ModuleAdmin();
    echo $index_admin->addNavigation("main.php?op=orderTracking");

  //
  $hOrder  =& xoops_getmodulehandler('order','xasset');
  $aOrders =& $hOrder->getOrders();
  //
//  $xoopsTpl->assign('xasset_navigation',$oAdminButton->renderButtons('orderTrack'));
  $xoopsTpl->assign('xasset_orders',$aOrders);
  $xoopsTpl->assign('xasset_order_count',count($aOrders)); 
  //
  require(XASSET_ADMIN_PATH.'/admin_footer.php');
  xoops_cp_footer();
}
//////////////////////////////////////////////////////
function showOrderLogDetail($orderID) {
  global $oAdminButton, $xoopsTpl;
  $xoopsOption['template_main'] = 'xasset_admin_order_details.html';
  xoops_cp_header();
  //
  $hOrder  =& xoops_getmodulehandler('order','xasset');
  $hGateway  =& xoops_getmodulehandler('gatewayLog','xasset');
  //
  $oOrder  =& $hOrder->get($orderID);               
  //
  $aOrder       =& $oOrder->getArray();     
  $aOrderDetail =& $oOrder->getOrderDetailsArray(); 
  $aGateLogs    =& $hGateway->getLogsByOrder($orderID);
  //
//  $xoopsTpl->assign('xasset_navigation',$oAdminButton->renderButtons('orderTrack'));
  $xoopsTpl->assign('xasset_order',$aOrder);
  $xoopsTpl->assign('xasset_order_detail',$aOrderDetail);
  $xoopsTpl->assign('xasset_logs', $aGateLogs);
  //
  require(XASSET_ADMIN_PATH.'/admin_footer.php');
  xoops_cp_footer();
}
//////////////////////////////////////////////////////
function checkTables() {
  xoops_cp_header();
  //
  $hCommon =& xoops_getmodulehandler('common','xasset');
  $success = true;
  //check xasset_app_product for enabled field for versiob 0.82
  echo '<p>Please note that the xAsset table checker works with xAsset versions 0.8 and upwards. You must un-install any previous xAsset prior to version 0.8 and install the latest release. To confirm. There is no update path from xAsset versions earlier to 0.8.</p>';
  echo '<p><u>Checking 0.82 table updates</u></p>';
  if (!$hCommon->fieldExists('xasset_app_product','enabled')) {
    $table = $hCommon->_db->prefix('xasset_app_product');
    echo "Upgrading table $table.<br>";
    $sql = "ALTER TABLE $table ADD enabled tinyint(4) DEFAULT '1'";
    if ($hCommon->_db->queryF($sql)) {
      $success = true;
      echo "Upgraded $table.<br>";
    } else {
      $success = false;
      print_r($hCommon->_db->_errors);
    }
  }
  //check xasset_app_product for enabled field for version 0.85
  echo '<p><u>Checking 0.85 table updates</u></p>';
  echo '<p>Checking xasset_app_product table structure</p>';
  if (!$hCommon->fieldExists('xasset_app_product','group_expire_date')) {
    $table = $hCommon->_db->prefix('xasset_app_product');
    echo "Upgrading table $table.<br>";
    $sql = "ALTER TABLE $table ADD group_expire_date int(11) DEFAULT NULL";      
    if ($hCommon->_db->queryF($sql)) {
      $success = true;
      echo "Upgraded $table.<br>";
    } else {
      $success = false;
      print_r($hCommon->_db->_errors);
    }
  }
  echo '<p>Checking if table xasset_app_prod_memb exists</p>';
  if (!$hCommon->tableExists('xasset_app_prod_memb')) {
    $table = $hCommon->_db->prefix('xasset_app_prod_memb');
    echo "Creating table $table<br>";
    $sql = "CREATE TABLE $table (
          `id` int(11) NOT NULL auto_increment,
          `uid` int(11) NOT NULL default '0',
          `order_detail_id` int(11) NOT NULL default '0',
          `group_id` int(11) NOT NULL default '0',
          `expiry_date` int(11) NOT NULL default '0',
           PRIMARY KEY  (`id`),
           UNIQUE KEY `id` (`id`),
           KEY `uid` (`uid`),
           KEY `order_detail_id` (`order_detail_id`),
           KEY `group_id` (`group_id`)
           ) TYPE=MyISAM";
    if ($hCommon->_db->queryF($sql)) {
      $success = true;
      echo "Created $table.<br>";
    } else {
      $success = false;
      print_r($hCommon->_db->_errors);
    }
  }
  //check xasset_app_product for enabled field for version 0.90
  echo '<p><u>Checking 0.90 table updates</u></p>';
  if (!$hCommon->fieldExists('xasset_application','productsVisible')) {
    $table = $hCommon->_db->prefix('xasset_application');
    echo "Upgrading table $table.<br>";
    $sql = "ALTER TABLE $table ADD productsVisible tinyint(4) DEFAULT '1'";
    if ($hCommon->_db->queryF($sql)) {
      $success = true;
      echo "Upgraded $table.<br>";
    } else {
      $success = false;
      print_r($hCommon->_db->_errors);
    }
  }
  if (!$hCommon->fieldExists('xasset_userpackagestats','dns')) {
    $table = $hCommon->_db->prefix('xasset_userpackagestats');
    echo "Upgrading table $table.<br>";
    $sql = "ALTER TABLE $table ADD dns varchar(250) DEFAULT null";
    if ($hCommon->_db->queryF($sql)) {
      $success = true;
      echo "Upgraded $table.<br>";
    } else {
      $success = false;
      print_r($hCommon->_db->_errors);
    }
  }
  //check xasset_app_product for enabled field for version 0.91
  echo '<p><u>Checking 0.91 table updates</u></p>';
  if (!$hCommon->fieldExists('xasset_app_product','add_to_group2')) {
    $table = $hCommon->_db->prefix('xasset_app_product');
    echo "Upgrading table $table.<br>";
    $sql = "ALTER TABLE $table ADD add_to_group2 int(11) DEFAULT NULL"; 
    if ($hCommon->_db->queryF($sql)) {
      $success = true;
      echo "Upgraded $table.<br>";
    } else {
      $success = false;
      print_r($hCommon->_db->_errors);
    }
  }
  if (!$hCommon->fieldExists('xasset_app_product','group_expire_date2')) {
    $table = $hCommon->_db->prefix('xasset_app_product');
    echo "Upgrading table $table.<br>";
    $sql = "ALTER TABLE $table ADD group_expire_date2 int(11) DEFAULT NULL"; 
    if ($hCommon->_db->queryF($sql)) {
      $success = true;
      echo "Upgraded $table.<br>";
    } else {
      $success = false;
      print_r($hCommon->_db->_errors);
    }
  }
  //
  if (!$hCommon->fieldExists('xasset_app_prod_memb','sent_warning')) {
    $table = $hCommon->_db->prefix('xasset_app_prod_memb');
    echo "Upgrading table $table.<br>";
    $sql = "ALTER TABLE $table ADD sent_warning int(11) DEFAULT NULL"; 
    if ($hCommon->_db->queryF($sql)) {
      $success = true;
      echo "Upgraded $table.<br>";
    } else {
      $success = false;
      print_r($hCommon->_db->_errors);
    }
  }
  if (!$hCommon->fieldExists('xasset_app_product','extra_instructions')) {
    $table = $hCommon->_db->prefix('xasset_app_product');
    echo "Upgrading table $table.<br>";
    $sql = "ALTER TABLE $table ADD extra_instructions text"; 
    if ($hCommon->_db->queryF($sql)) {
      $success = true;
      echo "Upgraded $table.<br>";
    } else {
      $success = false;
      print_r($hCommon->_db);
    }
  }
  //check fields for version 0.92
  echo '<p><u>Checking 0.92 table updates</u></p>'; 
  if (!$hCommon->fieldExists('xasset_application','product_list_template')) {
    $table = $hCommon->_db->prefix('xasset_application');
    echo "Upgrading table $table.<br>";
    $sql = "ALTER TABLE $table ADD product_list_template text"; 
    if ($hCommon->_db->queryF($sql)) {
      $success = true;
      echo "Upgraded $table.<br>";
    } else {
      $success = false;
      print_r($hCommon->_db);
    }
  }
  if (!$hCommon->fieldExists('xasset_application','image')) {
    $table = $hCommon->_db->prefix('xasset_application');
    echo "Upgrading table $table.<br>";
    $sql = "ALTER TABLE $table ADD image varchar(250) default NULL"; 
    if ($hCommon->_db->queryF($sql)) {
      $success = true;
      echo "Upgraded $table.<br>";
    } else {
      $success = false;
      print_r($hCommon->_db);
    }
  }
  if (!$hCommon->fieldExists('xasset_package','isVideo')) {
    $table = $hCommon->_db->prefix('xasset_package');
    echo "Upgrading table $table.<br>";
    $sql = "ALTER TABLE $table ADD isVideo tinyint(4) default '0'"; 
    if ($hCommon->_db->queryF($sql)) {
      $success = true;
      echo "Upgraded $table.<br>";
    } else {
      $success = false;
      print_r($hCommon->_db);
    }
  }
  //
  if ($success) {
    echo '<p>Upgrade Complete.</p>';
  } else {
    echo '<p>Errors Encountered.</p>';
  }
  //
  xoops_cp_footer();
}
//////////////////////////////////////////////////////
function support() {
  global $oAdminButton, $xoopsTpl;
  $xoopsOption['template_main'] = 'xasset_admin_support.html';
  xoops_cp_header();
  //
//  $xoopsTpl->assign('xasset_navigation',$oAdminButton->renderButtons('index'));
  //
  require(XASSET_ADMIN_PATH.'/admin_footer.php');
  xoops_cp_footer();
}
//////////////////////////////////////////////////////
function opCompOrder($post) {
  if (count($post['ID']) > 0) {
    $hOrder =& xoops_getmodulehandler('order','xasset');
    //
    foreach($post['ID'] as $key=>$id) {
      $oOrder =& $hOrder->get($id);
      $oOrder->setVar('status',$oOrder->orderStatusComplete());
      $hOrder->insert($oOrder);
      $oOrder->processPurchase();
      //
      unset($oOrder);
    }
    redirect_header('main.php?op=orderTracking',2,'Orders Manually Completed');
  }
}
//////////////////////////////////////////////////////
function opDelOrder($post) {
  if (count($post['ID']) > 0) {
    $hOrder =& xoops_getmodulehandler('order','xasset');
    //
    foreach($post['ID'] as $key=>$id) {
      $hOrder->delete($id);
    }
    redirect_header('main.php?op=orderTracking',2,'Orders Deleted');
  }
}
//////////////////////////////////////////////////////
function membership() {
  global $oAdminButton, $xoopsTpl;

  $xoopsOption['template_main'] = 'xasset_admin_membership_index.html';
  xoops_cp_header();
    $index_admin = new ModuleAdmin();
    echo $index_admin->addNavigation("main.php?op=membership");

  //
//  $xoopsTpl->assign('xasset_navigation',$oAdminButton->renderButtons('manMember'));
  //
  $hMembers =& xoops_getmodulehandler('applicationProductMemb','xasset');
  //
  $aMembers =& $hMembers->getMembers();
  //
  $xoopsTpl->assign('xasset_members',$aMembers);
  //
  require(XASSET_ADMIN_PATH.'/admin_footer.php');
  xoops_cp_footer();
}
//////////////////////////////////////////////////////
function removeFromGroup($id) {  
  xoops_cp_header();
  xoops_confirm( array('id'=>$id), 'main.php?op=doRemoveFromGroup', 'Are you sure you want to remove this member from this group?','',true);
}
//////////////////////////////////////////////////////
function doRemoveFromGroup($id) {  
  $hMembers =& xoops_getmodulehandler('applicationProductMemb','xasset');
  if ($hMembers->removeFromGroup($id)) {
    redirect_header('main.php?op=membership',2,'User removed from Xoops Groups');
  }
}
?>
