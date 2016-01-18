<?php

//include "header.php";
//require XOOPS_ROOT_PATH."/header.php";
//include_once 'include/info.php';

$modversion['name'] = _MI_XASSET_MODULE_NAME;
$modversion['version'] = "0.94";
$modversion['description'] = _MI_XASSET_MODULE_DESCRIPTION;
$modversion['credits'] = "PSP, XOOPS Development Team";
$modversion['author'] = "Nazar Aziz - nazar@panthersoftware.com";
$modversion['help']        = 'page=help';
$modversion['license']     = 'GNU GPL 2.0 or later';
$modversion['license_url'] = "www.gnu.org/licenses/gpl-2.0.html";
$modversion['official'] = 0;
$modversion['image'] = "images/xasset_slogo.png";
$modversion['dirname'] = "xasset";

$modversion['dirmoduleadmin'] = '/Frameworks/moduleclasses/moduleadmin';
$modversion['icons16']        = '../../Frameworks/moduleclasses/icons/16';
$modversion['icons32']        = '../../Frameworks/moduleclasses/icons/32';
//about
$modversion['release_date']        = '2013/03/03';
$modversion["module_website_url"]  = "www.xoops.org";
$modversion["module_website_name"] = "XOOPS";
$modversion["module_status"]       = "Alpha 1";
$modversion['min_php']             = '5.2';
$modversion['min_xoops']           = "2.5.5";
$modversion['min_admin']           = '1.1';
$modversion['min_db']              = array(
    'mysql'  => '5.0.7',
    'mysqli' => '5.0.7'
);

// Admin things
$modversion['hasAdmin'] = 1;
$modversion['system_menu'] = 1;
$modversion['adminindex'] = "admin/index.php";
$modversion['adminmenu'] = "admin/menu.php";

//tables
$modversion['sqlfile']['mysql'] = "sql/mysql.sql";
$modversion['tables'][] = "xasset_app_prod_memb";
$modversion['tables'][] = "xasset_app_product";
$modversion['tables'][] = "xasset_application";
$modversion['tables'][] = "xasset_application_groups";
$modversion['tables'][] = "xasset_config";
$modversion['tables'][] = "xasset_country";
$modversion['tables'][] = "xasset_currency";
$modversion['tables'][] = "xasset_gateway";
$modversion['tables'][] = "xasset_gateway_detail";
$modversion['tables'][] = "xasset_gateway_log";
$modversion['tables'][] = "xasset_license";
$modversion['tables'][] = "xasset_links";
$modversion['tables'][] = "xasset_order_detail";
$modversion['tables'][] = "xasset_order_index";
$modversion['tables'][] = "xasset_package";
$modversion['tables'][] = "xasset_packagegroup";
$modversion['tables'][] = "xasset_region";
$modversion['tables'][] = "xasset_tax_class";
$modversion['tables'][] = "xasset_tax_rates";
$modversion['tables'][] = "xasset_tax_zone";
$modversion['tables'][] = "xasset_user_details";
$modversion['tables'][] = "xasset_user_products";
$modversion['tables'][] = "xasset_userpackagestats";
$modversion['tables'][] = "xasset_zone";

// Menu
$modversion['hasMain'] = 1;
//
global $xoopsUser;
//
$hApps  =& xoops_getmodulehandler('application','xasset');
$hUserDetails =& xoops_getmodulehandler('userDetails','xasset');
$hOrder       =& xoops_getmodulehandler('order','xasset');
$hMembers    =& xoops_getmodulehandler('applicationProductMemb','xasset');

$oApps  =& $hApps->getApplicationMainMenuObjects();
$i      = 1;
foreach($oApps as $key=>$oApp) {
  $modversion['sub'][$i]['name'] = $oApp->getVar('menuItem');
  $modversion['sub'][$i]['url'] = 'index.php?op=product&id='.$oApp->getVar('id').'&key='.$hApps->cryptID($oApp->getVar('id'));
  //
  $i++;
}

if ($xoopsUser) {
  if ($oUserDetails =& $hUserDetails->getUserDetailByID($xoopsUser->uid())) {
    $aDownloads =& $oUserDetails->getUserDownloads();
    if (count($aDownloads) > 0) {
      //show only if we have downloads
      $modversion['sub'][$i+1]['name'] = _MI_XASSET_SUBMENU_MY_DOWNLOADS;
      $modversion['sub'][$i+1]['url'] = "index.php?op=showMyDownloads";
    }
  }
  //show only if we have something in our cart
  if ($hOrder->userInCartOrders($xoopsUser->uid()) > 0) {
    $modversion['sub'][$i+2]['name'] = _MI_XASSET_SUBMENU_MY_CART;
    $modversion['sub'][$i+2]['url'] = "order.php?op=showCart";
  }
  $modversion['sub'][$i+3]['name'] = _MI_XASSET_SUBMENU_MY_DETAILS;
  $modversion['sub'][$i+3]['url'] = "order.php?op=showUserDetails";
  //now show my subscriptios
  if ($hMembers->getSubscriberCountByUser($xoopsUser)) {
    $modversion['sub'][$i+4]['name'] = _MI_XASSET_SUBMENU_MY_SUBS;
    $modversion['sub'][$i+4]['url'] = "index.php?op=showUserSubs";
  }
} else {
  $modversion['sub'][$i+3]['name'] = _MI_XASSET_SUBMENU_MY_DETAILS;
  $modversion['sub'][$i+3]['url'] = "order.php?op=askUserDetails";
}

// Smarty
$modversion['use_smarty'] = 1;

// Search
$modversion['hasSearch'] = 1;
$modversion['search']['file'] = "include/search.php";
$modversion['search']['func'] = "xasset_search";

// Templates
$modversion['templates'][1]['file'] = 'xasset_admin_license_add.html';
$modversion['templates'][1]['description'] = _MI_XASSET_VERSION_ADMIN_ADD_LICENSE_BLOCK;
$modversion['templates'][2]['file'] = 'xasset_admin_packagegroup_add.html';
$modversion['templates'][2]['description'] = _MI_XASSET_VERSION_ADMIN_ADD_PACKAGE_GROUP;
$modversion['templates'][3]['file'] = 'xasset_admin_package_add.html';
$modversion['templates'][3]['description'] = _MI_XASSET_VERSION_ADMIN_ADD_PACKAGE_BLOCK;
$modversion['templates'][4]['file'] = 'xasset_admin_links_add.html';
$modversion['templates'][4]['description'] = _MI_XASSET_VERSION_ADMIN_ADD_LINKS;
$modversion['templates'][5]['file'] = 'xasset_admin_application_add.html';
$modversion['templates'][5]['description'] = _MI_XASSET_VERSION_ADMIN_ADDEDIT_APPLICATION;
$modversion['templates'][6]['file'] = 'xasset_admin_user_add.html';
$modversion['templates'][6]['description'] = _MI_XASSET_VERSION_ADMIN_ADDEDIT_USER;
$modversion['templates'][7]['file'] = 'xasset_admin_country_add.html';
$modversion['templates'][7]['description'] = _MI_XASSET_VERSION_COUNTRY_ADD;
$modversion['templates'][8]['file'] = 'xasset_admin_zone_add.html';
$modversion['templates'][8]['description'] = _MI_XASSET_VERSION_ZONE_ADD;
$modversion['templates'][9]['file'] = 'xasset_admin_tax_rate_add.html';
$modversion['templates'][9]['description'] = _MI_XASSET_VERSION_TAX_RATE_ADD;
$modversion['templates'][10]['file'] = 'xasset_admin_tax_class_add.html';
$modversion['templates'][10]['description'] = _MI_XASSET_VERSION_TAX_CLASS_ADD;
$modversion['templates'][11]['file'] = 'xasset_admin_applicaion_product_add.html';
$modversion['templates'][11]['description'] = _MI_XASSET_VERSION_APPLICATION_PRODUCT_ADD;
$modversion['templates'][12]['file'] = 'xasset_admin_currency_add.html';
$modversion['templates'][12]['description'] = _MI_XASSET_VERSION_CURRENCY_ADD;
$modversion['templates'][13]['file'] = 'xasset_admin_region_add.html';
$modversion['templates'][13]['description'] = _MI_XASSET_VERSION_REGION_ADD;
$modversion['templates'][14]['file'] = 'xasset_admin_index.html';
$modversion['templates'][14]['description'] = _MI_XASSET_VERSION_ADMIN_INDEX;
$modversion['templates'][15]['file'] = 'xasset_admin_application_index.html';
$modversion['templates'][15]['description'] = _MI_XASSET_VERSION_ADMIN_APP_MAINTENANCE;
$modversion['templates'][16]['file'] = 'xasset_admin_license_index.html';
$modversion['templates'][16]['description'] = _MI_XASSET_VERSION_ADMIN_LICENCE_MAINTENANCE;
$modversion['templates'][17]['file'] = 'xasset_admin_package_index.html';
$modversion['templates'][17]['description'] = _MI_XASSET_VERSION_ADMIN_PACKAGE_MAINTENANCE;
$modversion['templates'][18]['file'] = 'xasset_admin_license_application.html';
$modversion['templates'][18]['description'] = _MI_XASSET_VERSION_ADMIN_LICENSES_BY_APPLICATION;
$modversion['templates'][19]['file'] = 'xasset_admin_license_client.html';
$modversion['templates'][19]['description'] = _MI_XASSET_VERSION_ADMIN_LICENSES_BY_CLIENTS;
$modversion['templates'][20]['file'] = 'xasset_admin_packages_index.html';
$modversion['templates'][20]['description'] = _MI_XASSET_VERSION_ADMIN_GROUPS_PACKAGES;
$modversion['templates'][21]['file'] = 'xasset_admin_links_index.html';
$modversion['templates'][21]['description'] = _MI_XASSET_VERSION_ADMIN_APPLICATION_LINKS;
$modversion['templates'][22]['file'] = 'xasset_admin_links_edit.html';
$modversion['templates'][22]['description'] = _MI_XASSET_VERSION_ADMIN_EDIT_LINKS;
$modversion['templates'][23]['file'] = 'xasset_admin_user_index.html';
$modversion['templates'][23]['description'] = _MI_XASSET_VERSION_ADMIN_USERS_INDEX;
$modversion['templates'][24]['file'] = 'xasset_admin_packagegroup_edit.html';
$modversion['templates'][24]['description'] = _MI_XASSET_VERSION_ADMIN_EDIT_PACKAGE_GROUP;
$modversion['templates'][25]['file'] = 'xasset_admin_package_edit.html';
$modversion['templates'][25]['description'] = _MI_XASSET_VERSION_ADMIN_PACKAGE_EDIT;
$modversion['templates'][26]['file'] = 'xasset_admin_download_stats_index.html';
$modversion['templates'][26]['description'] = _MI_XASSET_VERSION_ADMIN_DOWNLOAD_STATS_INDEX;
$modversion['templates'][27]['file'] = 'xasset_admin_country_index.html';
$modversion['templates'][27]['description'] = _MI_XASSET_VERSION_COUNTRY_INDEX;
$modversion['templates'][28]['file'] = 'xasset_admin_region_index.html';
$modversion['templates'][28]['description'] = _MI_XASSET_VERSION_REGION_INDEX;
$modversion['templates'][29]['file'] = 'xasset_admin_currency_index.html';
$modversion['templates'][29]['description'] = _MI_XASSET_VERSION_CURRENCY_INDEX;
$modversion['templates'][30]['file'] = 'xasset_admin_zone_index.html';
$modversion['templates'][30]['description'] = _MI_XASSET_VERSION_ZONE_INDEX;
$modversion['templates'][31]['file'] = 'xasset_admin_taxrates_index.html';
$modversion['templates'][31]['description'] = _MI_XASSET_VERSION_TAXRATES_INDEX;
$modversion['templates'][32]['file'] = 'xasset_admin_tax_region_zone.html';
$modversion['templates'][32]['description'] = _MI_XASSET_VERSION_REGIONZONE_INDEX;
$modversion['templates'][33]['file'] = 'xasset_admin_gateway_index.html';
$modversion['templates'][33]['description'] = _MI_XASSET_VERSION_GATEWAY_INDEX;
$modversion['templates'][34]['file'] = 'xasset_admin_config.html';
$modversion['templates'][34]['description'] = _MI_XASSET_VERSION_CONFIG;
$modversion['templates'][35]['file'] = 'xasset_admin_gateway_log_index.html';
$modversion['templates'][35]['description'] = _MI_XASSET_VERSION_GATEWAY_LOGS;
$modversion['templates'][36]['file'] = 'xasset_admin_gateway_log_detail.html';
$modversion['templates'][36]['description'] = _MI_XASSET_VERSION_GATEWAY_DET;
$modversion['templates'][37]['file'] = 'xasset_admin_order_tracking.html';
$modversion['templates'][37]['description'] = _MI_XASSET_VERSION_ORDER_TRACKING;
$modversion['templates'][38]['file'] = 'xasset_admin_support.html';
$modversion['templates'][38]['description'] = _MI_XASSET_VERSION_SUPPORT;
$modversion['templates'][39]['file'] = 'xasset_admin_order_details.html';
$modversion['templates'][39]['description'] = _MI_XASSET_VERSION_ORDER_DETAILS;
$modversion['templates'][40]['file'] = 'xasset_admin_membership_index.html';
$modversion['templates'][40]['description'] = _MI_XASSET_VERSION_ADMIN_MEMBERSHIP;

$modversion['templates'][50]['file'] = 'xasset_order_user_details_add.html';
$modversion['templates'][50]['description'] = _MI_XASSET_VERSION_ORDER_USER_DETAILS_ADD;
$modversion['templates'][51]['file'] = 'xasset_index.html';
$modversion['templates'][51]['description'] = _MI_XASSET_VERSION_INDEX_PAGE;
$modversion['templates'][52]['file'] = 'xasset_license_index.html';
$modversion['templates'][52]['description'] = _MI_XASSET_VERSION_LICENSE_INDEX;
$modversion['templates'][53]['file'] = 'xasset_error.html';
$modversion['templates'][53]['description'] = _MI_XASSET_VERSION_ERROR_PAGE;
$modversion['templates'][54]['file'] = 'xasset_package_index.html';
$modversion['templates'][54]['description'] = _MI_XASSET_VERSION_PACKAGE_INDEX;
$modversion['templates'][55]['file'] = 'xasset_evaluation_index.html';
$modversion['templates'][55]['description'] = _MI_XASSET_VERSION_EVALUATION_INDEX;
$modversion['templates'][56]['file'] = 'xasset_order_index.html';
$modversion['templates'][56]['description'] = _MI_XASSET_VERSION_ORDER_INDEX;
$modversion['templates'][57]['file'] = 'xasset_order_user_details.html';
$modversion['templates'][57]['description'] = _MI_XASSET_VERSION_ORDER_USER_DETAILS;
$modversion['templates'][58]['file'] = 'xasset_order_cart.html';
$modversion['templates'][58]['description'] = _MI_XASSET_VERSION_ORDER_USER_DETAILS_ADD;
$modversion['templates'][59]['file'] = 'xasset_order_checkout.html';
$modversion['templates'][59]['description'] = _MI_XASSET_VERSION_ORDER_CHECKOUT;
$modversion['templates'][60]['file'] = 'xasset_product.html';
$modversion['templates'][60]['description'] = _MI_XASSET_VERSION_PRODUCT;
$modversion['templates'][61]['file'] = 'xasset_downloads.html';
$modversion['templates'][61]['description'] = _MI_XASSET_VERSION_DOWNLOADS;
$modversion['templates'][62]['file'] = 'xasset_order_extra.html';
$modversion['templates'][62]['description'] = _MI_XASSET_VERSION_OEXTRA;
$modversion['templates'][63]['file'] = 'xasset_my_subscriptions.html';
$modversion['templates'][63]['description'] = _MI_XASSET_VERSION_SUBS;
$modversion['templates'][64]['file'] = 'xasset_player_code.html';
$modversion['templates'][64]['description'] = _MI_XASSET_VERSION_PLAYER;
$modversion['templates'][65]['file'] = 'xasset_video_index.html';
$modversion['templates'][65]['description'] = _MI_XASSET_VERSION_VIDEO;
//block templates
$modversion['templates'][90]['file'] = 'xasset_block_download_option.html';
$modversion['templates'][90]['description'] = _MI_XASSET_BLOCK_DOWNOPT;
$modversion['templates'][91]['file'] = 'xasset_block_pics_option.html';
$modversion['templates'][91]['description'] = _MI_XASSET_BLOCK_PICTOPT;
// Blocks
$i=0;
$modversion['blocks'][$i]['file'] = "xasset_blocks.php";
$modversion['blocks'][$i]['name'] = _MI_XASSET_CURRENCY;
$modversion['blocks'][$i]['description'] = _MI_XASSET_CURRENCYD;
$modversion['blocks'][$i]['show_func'] = "b_xasset_currencies";
$modversion['blocks'][$i]['template'] = 'xasset_currencies.html';
$i++;
$modversion['blocks'][$i]['file'] = "xasset_blocks.php";
$modversion['blocks'][$i]['name'] = _MI_XASSET_TOP;
$modversion['blocks'][$i]['description'] = _MI_XASSET_TOPD;
$modversion['blocks'][$i]['show_func'] = "b_xasset_downloads";
$modversion['blocks'][$i]['edit_func'] = "b_xasset_downloads_opt";
$modversion['blocks'][$i]['template'] = 'xasset_top_download.html';
$i++;
$modversion['blocks'][$i]['file'] = "xasset_blocks.php";
$modversion['blocks'][$i]['name'] = _MI_XASSET_APP_PICS;
$modversion['blocks'][$i]['description'] = _MI_XASSET_APP_PICSD;
$modversion['blocks'][$i]['show_func'] = "b_xasset_pics";
$modversion['blocks'][$i]['edit_func'] = "b_xasset_pics_opt";
$modversion['blocks'][$i]['template'] = 'xasset_app_pics.html';
$i++;
$modversion['blocks'][$i]['file'] = "xasset_blocks.php";
$modversion['blocks'][$i]['name'] = _MI_XASSET_APP_APPS;
$modversion['blocks'][$i]['description'] = _MI_XASSET_APP_APPSD;
$modversion['blocks'][$i]['show_func'] = "b_xasset_apps";
$modversion['blocks'][$i]['template'] = 'xasset_apps.html';
$i=0;
$modversion['config'][$i]['name'] = 'prodShowMinLicence';
$modversion['config'][$i]['title'] = '_MI_XASSET_SHOW_MIN_LICENSE';
$modversion['config'][$i]['description'] = '_MI_XASSET_SHOW_MIN_L_DESC';
$modversion['config'][$i]['formtype'] = 'yesno';
$modversion['config'][$i]['valuetype'] = 'int';
$modversion['config'][$i]['default'] = 0;
$i++;
$modversion['config'][$i]['name'] = 'prodShowMaxDownloads';
$modversion['config'][$i]['title'] = '_MI_XASSET_SHOW_MAX_DOWNLOADS';
$modversion['config'][$i]['description'] = '_MI_XASSET_SHOW_MAX_D_DESC';
$modversion['config'][$i]['formtype'] = 'yesno';
$modversion['config'][$i]['valuetype'] = 'int';
$modversion['config'][$i]['default'] = 0;
$i++;
$modversion['config'][$i]['name'] = 'prodShowMaxDays';
$modversion['config'][$i]['title'] = '_MI_XASSET_SHOW_MAX_DAYS';
$modversion['config'][$i]['description'] = '_MI_XASSET_SHOW_MAX_DAYS_DESC';
$modversion['config'][$i]['formtype'] = 'yesno';
$modversion['config'][$i]['valuetype'] = 'int';
$modversion['config'][$i]['default'] = 0;
$i++;
$modversion['config'][$i]['name'] = 'prodShowExpires';
$modversion['config'][$i]['title'] = '_MI_XASSET_SHOW_EXPIRES';
$modversion['config'][$i]['description'] = '_MI_XASSET_SHOW_EXPIRES';
$modversion['config'][$i]['formtype'] = 'yesno';
$modversion['config'][$i]['valuetype'] = 'int';
$modversion['config'][$i]['default'] = 0;
$i++;
$modversion['config'][$i]['name'] = 'memExpireDaysWarn';
$modversion['config'][$i]['title'] = '_MI_XASSET_EXPIRE_WARND';
$modversion['config'][$i]['description'] = '_MI_XASSET_EXPIRE_WARNDE';
$modversion['config'][$i]['formtype'] = 'text';
$modversion['config'][$i]['valuetype'] = 'int';
$modversion['config'][$i]['default'] = 7;
$i++;
$modversion['config'][$i]['name'] = 'orderCompleteWait';
$modversion['config'][$i]['title'] = '_MI_XASSET_ORDERC_CAP';
$modversion['config'][$i]['description'] = '_MI_XASSET_ORDERC_CAPDE';
$modversion['config'][$i]['formtype'] = 'text';
$modversion['config'][$i]['valuetype'] = 'int';
$modversion['config'][$i]['default'] = 10;
$i++;
$modversion['config'][$i]['name'] = 'orderCompleteRedirect';
$modversion['config'][$i]['title'] = '_MI_XASSET_ORDERC_RED';
$modversion['config'][$i]['description'] = '_MI_XASSET_ORDERC_REDDE';
$modversion['config'][$i]['formtype'] = 'text';
$modversion['config'][$i]['valuetype'] = 'string';
$modversion['config'][$i]['default'] = '';
$i++;
$modversion['config'][$i]['name'] = 'encryptKey';
$modversion['config'][$i]['title'] = '_MI_XASSET_ENCRYPT_KEY';
$modversion['config'][$i]['description'] = '_MI_XASSET_ENCRYPT_KEYD';
$modversion['config'][$i]['formtype'] = 'text';
$modversion['config'][$i]['valuetype'] = 'string';
$modversion['config'][$i]['default'] = base64_encode(time());
$i++;
$modversion['config'][$i]['name'] = 'Enablebandwidth';
$modversion['config'][$i]['title'] = '_MI_XASSET_BANDWIDTHENABLE';
$modversion['config'][$i]['description'] = '_MI_XASSET_BANDWIDTHENABLED';
$modversion['config'][$i]['formtype'] = 'yesno';
$modversion['config'][$i]['valuetype'] = 'int';
$modversion['config'][$i]['default'] = 0;
$i++;
$modversion['config'][$i]['name'] = 'bandwidth';
$modversion['config'][$i]['title'] = '_MI_XASSET_BANDWIDTH';
$modversion['config'][$i]['description'] = '_MI_XASSET_BANDWIDTHD';
$modversion['config'][$i]['formtype'] = 'int';
$modversion['config'][$i]['valuetype'] = 'string';
$modversion['config'][$i]['default'] = 128;
$i++;
$modversion['config'][$i]['name'] = 'prodwin_width';
$modversion['config'][$i]['title'] = '_MI_XASSET_PRODWIN_WIDTH';
$modversion['config'][$i]['description'] = '_MI_XASSET_PRODWIN_WIDTHD';
$modversion['config'][$i]['formtype'] = 'int';
$modversion['config'][$i]['valuetype'] = 'int';
$modversion['config'][$i]['default'] = 450;
$i++;
$modversion['config'][$i]['name'] = 'prodwin_height';
$modversion['config'][$i]['title'] = '_MI_XASSET_PRODWIN_HEIGHT';
$modversion['config'][$i]['description'] = '_MI_XASSET_PRODWIN_HEIGHTD';
$modversion['config'][$i]['formtype'] = 'int';
$modversion['config'][$i]['valuetype'] = 'int';
$modversion['config'][$i]['default'] = 380;
$i++;
$modversion['config'][$i]['name'] = 'editor_options';
$modversion['config'][$i]['title'] = "_MI_XASSET_EDITOR_OPTIONS";
$modversion['config'][$i]['description'] = '';
$modversion['config'][$i]['formtype'] = 'select';
$modversion['config'][$i]['valuetype'] = 'text';
$modversion['config'][$i]['default'] = 'dhtml';
xoops_load('xoopseditorhandler');
$editor_handler = XoopsEditorHandler::getInstance();
$modversion['config'][$i]['options'] = array_flip($editor_handler->getList());

//notification
//$modversion['hasNotification'] = 0;

// Email templates
$modversion['_email_tpl'][1]['name'] = 'new_client_purchase';
$modversion['_email_tpl'][1]['category'] = 'client';
$modversion['_email_tpl'][1]['mail_template'] = 'client_newpurchase_notify';
$modversion['_email_tpl'][1]['mail_subject'] = _MI_XASSET_APP_NEW_PURCHASE_NOTIFYSBJ;
$modversion['_email_tpl'][1]['bit_value'] = 0;
$modversion['_email_tpl'][1]['title'] = _MI_XASSET_APP_NEW_PURCHASE_NOTIFY;
$modversion['_email_tpl'][1]['caption'] = _MI_XASSET_APP_NEW_PURCHASE_NOTIFYCAP;
$modversion['_email_tpl'][1]['description'] = _MI_XASSET_APP_NEW_PURCHASE_NOTIFYDSC;

$modversion['_email_tpl'][2]['name'] = 'new_client_purchase';
$modversion['_email_tpl'][2]['category'] = 'admin';
$modversion['_email_tpl'][2]['html'] = true;
$modversion['_email_tpl'][2]['mail_template'] = 'newpurchase_notify';
$modversion['_email_tpl'][2]['mail_subject'] = _MI_XASSET_APP_NEW_PURCHASE_ADMIN_NOTIFYSBJ;
$modversion['_email_tpl'][2]['bit_value'] = 1;
$modversion['_email_tpl'][2]['title'] = _MI_XASSET_APP_NEW_PURCHASE_ADMIN_NOTIFY;
$modversion['_email_tpl'][2]['caption'] = _MI_XASSET_APP_NEW_PURCHASE_ADMIN_NOTIFYCAP;
$modversion['_email_tpl'][2]['description'] = _MI_XASSET_APP_NEW_PURCHASE_ADMIN_NOTIFYDSC;

$modversion['_email_tpl'][3]['name'] = 'new_user';
$modversion['_email_tpl'][3]['category'] = 'client';
$modversion['_email_tpl'][3]['html'] = true;
$modversion['_email_tpl'][3]['mail_template'] = 'new_user';
$modversion['_email_tpl'][3]['mail_subject'] = _MI_XASSET_APP_NEW_USER_NOTIFYSBJ;
$modversion['_email_tpl'][3]['bit_value'] = 2;
$modversion['_email_tpl'][3]['title'] = _MI_XASSET_APP_NEW_USER_NOTIFY;
$modversion['_email_tpl'][3]['caption'] = _MI_XASSET_APP_NEW_USER_NOTIFYCAP;
$modversion['_email_tpl'][3]['description'] = _MI_XASSET_APP_NEW_USER_NOTIFYCAPDSC;

$modversion['_email_tpl'][4]['name'] = 'expire_warning';
$modversion['_email_tpl'][4]['category'] = 'client';
$modversion['_email_tpl'][4]['html'] = true;
$modversion['_email_tpl'][4]['mail_template'] = 'expire_warning';
$modversion['_email_tpl'][4]['mail_subject'] = _MI_XASSET_APP_EXPIRE_WARN_NOTIFYSBJ;
$modversion['_email_tpl'][4]['bit_value'] = 3;
$modversion['_email_tpl'][4]['title'] = _MI_XASSET_APP_EXPIRE_WARN_NOTIFY;
$modversion['_email_tpl'][4]['caption'] = _MI_XASSET_APP_EXPIRE_WARN_NOTIFYCAP;
$modversion['_email_tpl'][4]['description'] = _MI_XASSET_APP_EXPIRE_WARN_NOTIFYDSC;

$modversion['_email_tpl'][5]['name'] = 'expire_membership';
$modversion['_email_tpl'][5]['category'] = 'client';
$modversion['_email_tpl'][5]['html'] = true;
$modversion['_email_tpl'][5]['mail_template'] = 'expire_membership';
$modversion['_email_tpl'][5]['mail_subject'] = _MI_XASSET_APP_EXPIRE_MEMBER_NOTIFYSBJ;
$modversion['_email_tpl'][5]['bit_value'] = 3;
$modversion['_email_tpl'][5]['title'] = _MI_XASSET_APP_EXPIRE_MEMBER_NOTIFY;
$modversion['_email_tpl'][5]['caption'] = _MI_XASSET_APP_EXPIRE_MEMBER_NOTIFYCAP;
$modversion['_email_tpl'][5]['description'] = _MI_XASSET_APP_EXPIRE_MEMBER_NOTIFYDSC;

$modversion['_email_tpl'][6]['name'] = 'order_complete';
$modversion['_email_tpl'][6]['category'] = 'client';
$modversion['_email_tpl'][6]['html'] = true;
$modversion['_email_tpl'][6]['mail_template'] = 'order_complete';
$modversion['_email_tpl'][6]['mail_subject'] = _MI_XASSET_ORDER_COMPLETE_NOTIFYSBJ;
$modversion['_email_tpl'][6]['bit_value'] = 3;
$modversion['_email_tpl'][6]['title'] = _MI_XASSET_ORDER_COMPLETE_NOTIFY;
$modversion['_email_tpl'][6]['caption'] = _MI_XASSET_ORDER_COMPLETE_NOTIFYCAP;
$modversion['_email_tpl'][6]['description'] = _MI_XASSET_ORDER_COMPLETE_NOTIFYDSC;
