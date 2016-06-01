<?php

  //Properties admin page
  define('_MI_DOWNLOADHOME','Download Directory');
  define('_MI_DOWNLOADHOMEDSC','Directory where asset files will be stored and downloaded');
  define('_MI_XASSET_SHOW_MIN_LICENSE','Show Min License.');
  define('_MI_XASSET_SHOW_MIN_L_DESC','Show Min Licenses Column in Evaluation Page.');
  define('_MI_XASSET_SHOW_MAX_DOWNLOADS','Show Max Downloads.');
  define('_MI_XASSET_SHOW_MAX_D_DESC','Show Max Downloads Column in Evaluation Page.');
  define('_MI_XASSET_SHOW_MAX_DAYS','Show Max Days.');
  define('_MI_XASSET_SHOW_MAX_DAYS_DESC','Show Max Days Column in Evaluation Page.');
  define('_MI_XASSET_SHOW_EXPIRES','Show Expires Date.');
  define('_MI_XASSET_SHOW_EXPIRES_DESC','Show Expires Date Column in Evaluation Page.');
  define('_MI_XASSET_SHOW_SAMPLES','Show Samples Column');
  define('_MI_XASSET_EXPIRE_WARND','Membership expiry client warning');
  define('_MI_XASSET_EXPIRE_WARNDE','Send a membership expiry email to you users x days before their membership expires.');
  define('_MI_XASSET_ORDERC_CAP','Order Complete timeout (secs)');
  define('_MI_XASSET_ORDERC_CAPDE','How long the Order Complete redirect page is displayed for');
  define('_MI_XASSET_ORDERC_RED','Order Complete Redirect');
  define('_MI_XASSET_ORDERC_REDDE','The page that the Order Complete redirects to. Leave blank to default to xAsset/index.php');
  define('_MI_XASSET_ENCRYPT_KEY','Secret Encryption Key');
  define('_MI_XASSET_ENCRYPT_KEYD','This key is used to encrypt data to protect your video files. A random key is generated for you.');
  define('_MI_XASSET_BANDWIDTHENABLE','Enable Bandwidth Throttling');
  define('_MI_XASSET_BANDWIDTHENABLED','When enabled xAsset will limit the download rate for video streaming.');
  define('_MI_XASSET_BANDWIDTH','Bandwidth Throttling Rate (EXPERIMENTAL)'); 
  define('_MI_XASSET_BANDWIDTHD','Set the Badnwidth at which FLV Video will be streamed. Value is kilobytes per second per client.'); 
  define('_MI_XASSET_PRODWIN_WIDTH','Product Window Width');
  define('_MI_XASSET_PRODWIN_WIDTHD','Width of the Application Product popup window');
  define('_MI_XASSET_PRODWIN_HEIGHT','Product Window Height');
  define('_MI_XASSET_PRODWIN_HEIGHTD','Height of the Application Product popup window');
  //admin menus
  define('_MI_XASSET_MENU_PREFERENCES', 'Preferences');
  define('_MI_XASSET_MENU_CHECK_TABLES', 'Check Tables');
  define('_MI_XASSET_MENU_MANAGE_APPLICATIONS', 'Applications');
  define('_MI_XASSET_MENU_MANAGE_LICENSES', 'Licenses');
  define('_MI_XASSET_MENU_MANAGE_STATS','Download Stats');
  define('_MI_XASSET_MENU_MANAGE_PACKAGES', 'Packages');
  define('_MI_XASSET_MENU_MANAGE_LINKS', 'Links');
  define('_MI_XASSET_MENU_MANAGE_REGIONS', 'Regions');
  define('_MI_XASSET_MENU_MANAGE_COUNTRIES', 'Countries');
  define('_MI_XASSET_MENU_MANAGE_ZONES', 'Zones');
  define('_MI_XASSET_MENU_MANAGE_TAXES', 'Taxes & Rates');
  define('_MI_XASSET_MENU_MANAGE_CURRENCIES', 'Currencies');
  define('_MI_XASSET_MENU_MANAGE_GATEWAYS', 'Gateways');
  define('_MI_XASSET_MENU_MANAGE_GATE_LOGS', 'Gateway Logs');
  define('_MI_XASSET_MENU_MANAGE_ORDERS','Orders');
  define('_MI_XASSET_MENU_MANAGE_MEMBERSHIP','Memberships');
  //template names
  define('_MI_XASSET_VERSION_ADMIN_INDEX','Admin Index Page');
  define('_MI_XASSET_VERSION_ADMIN_APP_MAINTENANCE','Admin Application Maintenance');
  define('_MI_XASSET_VERSION_ADMIN_LICENCE_MAINTENANCE','Admin License Maintenance');
  define('_MI_XASSET_VERSION_ADMIN_PACKAGE_MAINTENANCE','Admin Package Maintenance');
  define('_MI_XASSET_VERSION_ADMIN_ADD_LICENSE_BLOCK','Admin License add license block');
  define('_MI_XASSET_VERSION_ADMIN_LICENSES_BY_APPLICATION','Admin Licenses by Applications');
  define('_MI_XASSET_VERSION_ADMIN_LICENSES_BY_CLIENTS','Admin Licenses by Clients');
  define('_MI_XASSET_VERSION_ADMIN_GROUPS_PACKAGES','Admin Package Groups and Packages');
  define('_MI_XASSET_VERSION_ADMIN_ADD_PACKAGE_GROUP','Admin Package Add Package Group block');
  define('_MI_XASSET_VERSION_ADMIN_ADD_PACKAGE_BLOCK','Admin Package Add Package block');
  define('_MI_XASSET_VERSION_ADMIN_APPLICATION_LINKS','Admin Application Links');
  define('_MI_XASSET_VERSION_ADMIN_ADD_LINKS','Admin Add Application Links');
  define('_MI_XASSET_VERSION_ADMIN_EDIT_LINKS','Admin Edit Application Links');
  define('_MI_XASSET_VERSION_ADMIN_ADDEDIT_APPLICATION','Admin Add/Edit Application Block');
  define('_MI_XASSET_VERSION_ADMIN_USERS_INDEX','Admin Users Index');
  define('_MI_XASSET_VERSION_ADMIN_ADDEDIT_USER','Admin Add/Edit User Block');
  define('_MI_XASSET_VERSION_ADMIN_EDIT_PACKAGE_GROUP','Admin Package Group Edit Page');
  define('_MI_XASSET_VERSION_ADMIN_PACKAGE_EDIT','Admin Package Edit Page');
  define('_MI_XASSET_VERSION_ADMIN_DOWNLOAD_STATS_INDEX','Admin Download Stats Index Page');
  define('_MI_XASSET_VERSION_INDEX_PAGE','Index Page');
  define('_MI_XASSET_VERSION_LICENSE_INDEX','License Index Page');
  define('_MI_XASSET_VERSION_ERROR_PAGE','Error Page');
  define('_MI_XASSET_VERSION_PACKAGE_INDEX','Package Index Page');
  define('_MI_XASSET_VERSION_EVALUATION_INDEX','Evaluation Applications Index Page');
  define('_MI_XASSET_VERSION_COUNTRY_ADD','Admin Country Add Block');
  define('_MI_XASSET_VERSION_COUNTRY_INDEX','Admin Country Index Page');
  define('_MI_XASSET_VERSION_REGION_INDEX','Region Index Page');
  define('_MI_XASSET_VERSION_REGION_ADD','Add Region Page');
  define('_MI_XASSET_VERSION_ZONE_ADD','Admin Zone Add Block');
  define('_MI_XASSET_VERSION_ZONE_INDEX','Admin Zone Index Page');
  define('_MI_XASSET_VERSION_TAXRATES_INDEX','Admin Taxes & Rates Index Page');
  define('_MI_XASSET_VERSION_TAX_CLASS_ADD','Admin Add Tax Class Block');
  define('_MI_XASSET_VERSION_TAX_RATE_ADD','Admin Add Tax Rate Block');
  define('_MI_XASSET_VERSION_REGIONZONE_INDEX','Admin Add Region Zone Block');
  define('_MI_XASSET_VERSION_APPLICATION_PRODUCT_ADD','Admin Add Application Product Block');
  define('_MI_XASSET_VERSION_CURRENCY_ADD','Admin Currency Add Block');
  define('_MI_XASSET_VERSION_CURRENCY_INDEX','Admin Currency Index Page');
  define('_MI_XASSET_VERSION_GATEWAY_INDEX','Admin Payment Gateway Index Page');
  define('_MI_XASSET_VERSION_ORDER_STAGE1','Order Page - Stage 1');
  define('_MI_XASSET_VERSION_ORDER_USER_DETAILS','Order Page - User Details');
  define('_MI_XASSET_VERSION_ORDER_USER_DETAILS_ADD','Order Page - User Details Add/Edit Block');
  define('_MI_XASSET_VERSION_ORDER_INDEX','Order Index Page');
  define('_MI_XASSET_VERSION_ORDER_CART','Order Cart Index Page');
  define('_MI_XASSET_VERSION_CONFIG','Admin Configuration Index Page');
  define('_MI_XASSET_VERSION_ORDER_CHECKOUT','Order Checkout Page');
  define('_MI_XASSET_VERSION_GATEWAY_LOGS','Gateway Logs Page');
  define('_MI_XASSET_VERSION_GATEWAY_DET','Gateway Logs Detail Page');
  define('_MI_XASSET_VERSION_PRODUCT','Product Page');
  define('_MI_XASSET_VERSION_DOWNLOADS','My Downloads Page');
  define('_MI_XASSET_VERSION_ORDER_TRACKING','Admin Order Tracking Index Page');
  define('_MI_XASSET_VERSION_SUPPORT','Admin Support Index Page');
  define('_MI_XASSET_VERSION_ORDER_DETAILS','Admin Order Details Page');
  define('_MI_XASSET_VERSION_ADMIN_MEMBERSHIP','Admin Membership Index Page');
  define('_MI_XASSET_VERSION_OEXTRA','Order Extra Gateway Information Page');
  define('_MI_XASSET_VERSION_SUBS','My Subscriptions index page');
  define('_MI_XASSET_VERSION_PLAYER','Video player block');
  define('_MI_XASSET_VERSION_VIDEO','Video Index Page');
  define('_MI_XASSET_BLOCK_DOWNOPT','Block - Top Downloads Options');
  define('_MI_XASSET_BLOCK_PICTOPT','Block - Application Picture Options');
  //blocks
  define('_MI_XASSET_CURRENCY','Currencies');
  define('_MI_XASSET_CURRENCYD','Choose your currency');
  define('_MI_XASSET_TOP','Top Downloads');
  define('_MI_XASSET_TOPD','Shows top downloaded/viewed files');
  define('_MI_XASSET_APP_PICS','Application Pictures');
  define('_MI_XASSET_APP_PICSD','Shows an application with a defined picture');
  define('_MI_XASSET_APP_APPS','Applications');
  define('_MI_XASSET_APP_APPSD','List all applications in a block');
  //submenu nams
  define('_MI_XASSET_SUBMENU_MY_DOWNLOAD','Download');
  define('_MI_XASSET_SUBMENU_MY_LICENSED','My Licenses');
  define('_MI_XASSET_SUBMENU_EVALUATION','Infowinner');
  define('_MI_XASSET_SUBMENU_MY_CART','My Cart');
  define('_MI_XASSET_SUBMENU_MY_DETAILS','My Details');
  define('_MI_XASSET_SUBMENU_MY_DOWNLOADS','My Downloads');
  define('_MI_XASSET_SUBMENU_MY_SUBS','My Subscriptions');
  //module xoops_version constants
  define('_MI_XASSET_MODULE_NAME','xAsset - Secure Digital Distribution');
  define('_MI_XASSET_MODULE_DESCRIPTION','Digital Management and Distribution');
  //email temapltes constats
  define('_MI_XASSET_APP_NEW_PURCHASE_NOTIFY','Client : Product Ordered');
  define('_MI_XASSET_APP_NEW_PURCHASE_NOTIFYSBJ','Thank you for your purchase.');
  define('_MI_XASSET_APP_NEW_PURCHASE_NOTIFYCAP','Notify me when I purchase a Product');
  define('_MI_XASSET_APP_NEW_PURCHASE_NOTIFYDSC','Sends notification to client when purchasing a product');

  define('_MI_XASSET_APP_NEW_PURCHASE_ADMIN_NOTIFY','Admin : Product Orders');
  define('_MI_XASSET_APP_NEW_PURCHASE_ADMIN_NOTIFYSBJ','Client has purchased a product.');
  define('_MI_XASSET_APP_NEW_PURCHASE_ADMIN_NOTIFYCAP','Notify me when a client purchases a product.');
  define('_MI_XASSET_APP_NEW_PURCHASE_ADMIN_NOTIFYDSC','Sends admins a notification when a purchase is made');
  
  define('_MI_XASSET_APP_NEW_USER_NOTIFY','Client : Welcome email.');
  define('_MI_XASSET_APP_NEW_USER_NOTIFYSBJ','Registration & Login Information');
  define('_MI_XASSET_APP_NEW_USER_NOTIFYCAP','Notify client of registration & login information.');
  define('_MI_XASSET_APP_NEW_USER_NOTIFYCAPDSC','Sends email to new registered user.'); 

  define('_MI_XASSET_APP_EXPIRE_WARN_NOTIFY','Client : Membership Expiry Warning.');
  define('_MI_XASSET_APP_EXPIRE_WARN_NOTIFYSBJ','Your membership is about to expire.');
  define('_MI_XASSET_APP_EXPIRE_WARN_NOTIFYCAP','Notify client when membership is about to expire.');
  define('_MI_XASSET_APP_EXPIRE_WARN_NOTIFYDSC','Sends email to client when membership is about to expire.'); 

  define('_MI_XASSET_APP_EXPIRE_MEMBER_NOTIFY','Client : Membership Expired.');
  define('_MI_XASSET_APP_EXPIRE_MEMBER_NOTIFYSBJ','Your membership has expired.');
  define('_MI_XASSET_APP_EXPIRE_MEMBER_NOTIFYCAP','Notify client when membership membership expires.');
  define('_MI_XASSET_APP_EXPIRE_MEMBER_NOTIFYDSC','Sends email to client when membership expires.');

  define('_MI_XASSET_ORDER_COMPLETE_NOTIFY','Client : Order Complete.');
  define('_MI_XASSET_ORDER_COMPLETE_NOTIFYSBJ','Your order is complete.');
  define('_MI_XASSET_ORDER_COMPLETE_NOTIFYCAP','Notify client when payments have been received and order is complete.');
  define('_MI_XASSET_ORDER_COMPLETE_NOTIFYDSC','Sends email to client when order is complete.');

define('_MI_XASSET_DASHBBOARD','Dashboard');


define("_MI_XASSET_APPLICATIONS","%s Aplications");
define("_MI_XASSET_LICENSES","%s Licenses");
define("_MI_XASSET_FILES","%s Files");
define("_MI_XASSET_LINKS","%s Links");
define("_MI_XASSET_DOWNLOADS","%s Downloads");
define("_MI_XASSET_EDITOR_OPTIONS","Select your Editor");