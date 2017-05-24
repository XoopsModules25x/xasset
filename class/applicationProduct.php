<?php

require_once('xassetBaseObject.php');
require_once('crypt.php');

class xassetApplicationProduct extends XAssetBaseObject {
  var $weight;
  //
  function xassetApplicationProduct($id = null) {
    $this->initVar('id', XOBJ_DTYPE_INT, null, false);
    $this->initVar('application_id', XOBJ_DTYPE_INT, null, false);
    $this->initVar('tax_class_id', XOBJ_DTYPE_INT, null, false);
    $this->initVar('display_order', XOBJ_DTYPE_INT, 0, false);
    $this->initVar('base_currency_id', XOBJ_DTYPE_INT, null, false);
    $this->initVar('package_group_id', XOBJ_DTYPE_INT, null, false);
    $this->initVar('sample_package_group_id', XOBJ_DTYPE_INT, null, false);
    $this->initVar('item_code', XOBJ_DTYPE_TXTBOX, null, true, 10);
    $this->initVar('item_description', XOBJ_DTYPE_TXTBOX, null, true, 100);
    $this->initVar('unit_price', XOBJ_DTYPE_OTHER, null, false);
    $this->initVar('old_unit_price', XOBJ_DTYPE_OTHER, 0, false);
    $this->initVar('min_unit_count', XOBJ_DTYPE_INT, 0, false);
    $this->initVar('max_access', XOBJ_DTYPE_INT, 0, false);
    $this->initVar('max_days',XOBJ_DTYPE_INT, 0, false);
    $this->initVar('expires', XOBJ_DTYPE_LTIME, null, false);
    $this->initVar('add_to_group',XOBJ_DTYPE_INT, 0, false);
    $this->initVar('add_to_group2',XOBJ_DTYPE_INT, 0, false);
    $this->initVar('item_rich_description', XOBJ_DTYPE_TXTBOX, null, true, 50000);
    $this->initVar('enabled', XOBJ_DTYPE_INT, 1, false);
    $this->initVar('group_expire_date', XOBJ_DTYPE_INT , null, false);
    $this->initVar('group_expire_date2', XOBJ_DTYPE_INT , null, false);
    $this->initVar('extra_instructions', XOBJ_DTYPE_TXTBOX, null, true, 60000);
    //
    $this->weight = 13;
    //
    if (isset($id)) {
      if (is_array($id)) {
        $this->assignVars($id);
      }
    } else {
      $this->setNew();
    }
  }
  /////////////////////////////////////////
  function itemDescription() {
    return $this->getVar('item_description');
  }
  /////////////////////////////////////////
  function packageGroupID() {
    return $this->getVar('package_group_id');
  }
  /////////////////////////////////////////
  function applicationID() {
    return $this->getVar('application_id');
  }
  /////////////////////////////////////////
  function sampleGroupID() {
    return $this->getVar('sample_package_group_id');
  }
  /////////////////////////////////////////
  function &getApplication() {
    $app =& xoops_modulehandler('application','xasset');

    return $app->get($this->getVar('applicationid'));
  }
  /////////////////////////////////////////
  function &getTaxClass() {
    $class =& xoops_modulehandler('taxClass','xasset');

    return $class->get($this->getVar('taxclassid','xasset'));
  }
  /////////////////////////////////////////
  function expires($format='l') {
    if ($this->getVar('expires') > 0) {
      return formatTimestamp($this->getVar('expires'), $format);}
    else {
      return '';
    }
  }
  ////////////////////////////////////////
  function groupExpireDate() {
    return $this->getVar('group_expire_date');
  }
  ///////////////////////////////////////////////
  function getKey() {
    $crypt = new xassetCrypt();

    return $crypt->cryptValue($this->getVar('id'),$this->weight);
  }
  //////////////////////////////////////////////
  function getPrice($format = 's') {
    $hCurrency =& xoops_getmodulehandler('currency','xasset');
    //have two currency choices.. either the chosen currency in $_SESSION or the base currency;
    $curID = isset($_SESSION['currency_id']) ? $_SESSION['currency_id'] : $this->getVar('base_currency_id');
    $oCur    =& $hCurrency->get($curID);
    $oAppCur =& $hCurrency->get($this->getVar('base_currency_id'));
    //
    if ($format == 's') {
      return $oCur->valueOnlyFormat($this->getVar('unit_price')/$oAppCur->value());
    } else if ($format == 'l') {
      return $oCur->valueFormat($this->getVar('unit_price')/$oAppCur->value());
    }
  }
  //////////////////////////////////////////////
  function getRichDescription() {
    return $this->getVar('item_rich_description','n');
  }
  //////////////////////////////////////////////
  function getBuyNowButton($image = '') {
    if ($image == '')
      $image    = XOOPS_URL.'/modules/xasset/images/buyNow.png';
    //
    $button = '<form method="post" action="'.XOOPS_URL.'/modules/xasset/order.php?id='.$this->ID().'&amp;key='.$this->getKey().'&amp;op=addToCart">
                         <input type="image" src="'.$image.'" title="Buy Now" ALT="Buy Now" name="buyNow" style="border:0;background:transparent" />
                         </form>';

    return $button;
  }
  //////////////////////////////////////////////
  function &getArray() {
    $ary =& parent::getArray();
    $ary['product_link'] = $this->getBuyNowButton();
    //
    return $ary;
  }
  ///////////////////////////////////////////////////
  function getVideoPlayer($movie_id = null) {
    global $xoopsUser;
    //
    $hCommon  =& xoops_getmodulehandler('common','xasset');
    $hPackage =& xoops_getmodulehandler('package','xasset');
    //
    if (!isset($movie_id)) {
      //get first sample video from packages
      $aoPackages =& $hPackage->getProductSamplePackages($this);
      if (count($aoPackages) > 0) {
        $oPackage =& $aoPackages[0];
      }
    } else {
      $oPackage =& $hPackage->get($movie_id);
    }
    //
    if (is_object($oPackage)) {
      $tpl = new XoopsTpl();
      $tpl->assign('xasset_movie_id',$oPackage->ID());
      $tpl->assign('xasset_movie_size',$oPackage->fileSize());
      $tpl->assign('xasset_token', $xoopsUser ? $hCommon->pspEncrypt($xoopsUser->uid()) : $hCommon->pspEncrypt(0) );
      //
      return $tpl->fetch('db:xasset_player_code.html');
    } else {
      return '';
    }
  }
}

class xassetApplicationProductHandler extends xassetBaseObjectHandler {
  //vars
  var $_db;
  var $classname = 'xassetapplicationproduct';
  var $_dbtable  = 'xasset_app_product';
  //cons
  function xassetApplicationProductHandler(&$db)
  {
    $this->_db = $db;
  }
  ///////////////////////////////////////////////////
  function &getInstance(&$db)
  {
      static $instance;
      if(!isset($instance)) {
          $instance = new xassetApplicationProductsHandler($db);
      }

      return $instance;
  }
  ///////////////////////////////////////////////////
  function getAppProductArray($appid, $currid = null, $force = false) {
    $crit = new CriteriaCompo(new Criteria('application_id',$appid));
    //
    if (!$force) $crit->add(new Criteria('ap.enabled',1));
    $crit->setSort('display_order');
    //
    return $this->getProductArray($crit, $currid);
  }
  ///////////////////////////////////////////////////
  function getProductArray($criteria, $currid = null) {
    global $imagearray;
    //
    $hApp      =& xoops_getmodulehandler('application','xasset');
    $hClass    =& xoops_getmodulehandler('taxClass','xasset');
    $hCurrency =& xoops_getmodulehandler('currency','xasset');
    //
    $thisTable  = $this->_db->prefix($this->_dbtable);
    $classTable = $this->_db->prefix($hClass->_dbtable);
    $appTable   = $this->_db->prefix($hApp->_dbtable);
    $currTable  = $this->_db->prefix($hCurrency->_dbtable);
    //
    if (isset($currid)) {
      $crit = new Criteria('id',$currid);
      $curs = $hCurrency->getObjects($crit);
    } else {
      $curs       = $hCurrency->getObjects();
    }
    //
    $sql       = "select ap.*, a.name application_name, c.code tax_code, cu.name currency_name, cu.value
                  from $thisTable ap inner join $appTable a on
                    ap.application_id   = a.id inner join $classTable c on
                    ap.tax_class_id     = c.id inner join $currTable cu on
                    ap.base_currency_id = cu.id";
    $this->postProcessSQL($sql, $criteria);
    //
    $ary = array();
    //
    if ($res = $this->_db->query($sql)) {
      $i = 0;
      $hPackage =& xoops_getmodulehandler('package');
      //
      $crypt    = new xassetCrypt();
      $obj      =& $this->create();
      //
      while ($row = $this->_db->fetcharray($res)) {
        $actions = '<a href="main.php?op=editAppProduct&id='.$row['id'].'">'.$imagearray['editimg'].'</a>' .
                   '<a href="main.php?op=deleteAppProduct&id='.$row['id'].'">'.$imagearray['deleteimg'].'</a>';
        //
        $priceAry = array();
        //
        foreach($curs as $cur) {
          $priceAry[] = array( 'currency_id' => $cur->getVar('id'),
                               'baseUnit'    => $row['unit_price'],
                               'curUnit'     => $cur->getVar('value') * ($row['unit_price']/$row['value']),
                               'fmtUnit'     => $cur->valueFormat($row['unit_price']/$row['value']) );
        }
        //
        $ary[$i] = $row;
        $ary[$i]['prices']      =  $priceAry;
        $ary[$i]['expiresDate'] =  formatTimestamp($row['expires'], 's');
        $ary[$i]['key']         =  $crypt->cryptValue($row['id'],$obj->weight);
        $ary[$i]['actions']     =  $actions;
        $ary[$i]['appSamples']  =  $hPackage->getGroupPackagesArray($row['sample_package_group_id']);
        //
        $i++;
        //
        unset($priceAry);
      }
    }

    return $ary;
  }
  ///////////////////////////////////////////////////
  function parseTokens($body, $oApp) {
    if (preg_match_all('/{TAG.(.*?)}/', $body, $matches)) {
      //matchs will be of form {TAG.app.BUY(option)
      foreach($matches[1] as $key=>$match) {
        //check for BUY tag
        $replace = $matches[0][$key];
        if (!(strpos($match,'.BUY') === false)) {
          if (preg_match('/(.*).BUY\[(.*)\]/',$match,$buy)) {
            //here we have a buy with a pointer to a buy now image BUY[http://image...]
            $oAppProd =& $this->getProductByCode($buy[1],$oApp->ID());
            $image    = $buy[2];
          } else if (preg_match('/(.*).BUY/',$match,$buy)) {
            //here were only have app.BUY
            $oAppProd =& $this->getProductByCode($buy[1],$oApp->ID());
            $image    = 'images/buyNow.png';
          }
          if (is_object($oAppProd)) {
            //construct button
            $button = $oAppProd->getBuyNowButton($image);
            //finally replace
            $body = str_replace($replace,$button,$body);
          }
        }
        //matches will be of form {TAG.app.PRICE}
        if (!(strpos($match,'.PRICE') === false)) {
          if (preg_match('/(.*).PRICE/',$match,$buy)) {
            $oAppProd =& $this->getProductByCode($buy[1],$oApp->ID());
            $price    = $oAppProd->getPrice('l');
            //
            $body = str_replace($replace,$price,$body);
          }
        }
        //matches will be of form {TAG.app.DESC}
        if (!(strpos($match,'.DESC') === false)) {
          if (preg_match('/(.*).DESC/',$match,$buy)) {
            $oAppProd =& $this->getProductByCode($buy[1],$oApp->ID());
            $desc    = $oAppProd->getRichDescription();
            //
            $body = str_replace($replace,$desc,$body);
          }
        }
        
        //matches will be of form {TAG.app.VIDEO}
        if (!(strpos($match,'.VIDEO') === false)) {
          if (preg_match('/(.*).VIDEO\[(.*)\]/',$match,$buy)) {
            //here we have a buy with a pointer to a buy now image BUY[http://image...]
            $oAppProd =& $this->getProductByCode($buy[1],$oApp->ID());
            $video    = $buy[2];
          } else if (preg_match('/(.*).VIDEO/',$match,$buy)) {
            //here were only have app.BUY
            $oAppProd =& $this->getProductByCode($buy[1],$oApp->ID());
            $video    = null;
          }
          if (is_object($oAppProd)) {
            //$hPackage =& xoops_getmodulehandler('package','xasset');
            //
            $player = $oAppProd->getVideoPlayer($video);
            $body   = str_replace($replace,$player,$body);
          }
        }
      }
    }

    return $body;
  }
  ///////////////////////////////////////////////////
  function &getProductByCode($pCode, $pAppID) {
    $crit = new CriteriaCompo(new Criteria('application_id',$pAppID));
    $crit->add(new Criteria('item_code',$pCode));
    //
    $aObjs = $this->getObjects($crit);
    //
    if (count($aObjs) > 0) {
      $obj = reset($aObjs);

      return $obj;
    } else {
      $obj = false;

      return $obj;
    }
  }
  ///////////////////////////////////////////////////
  function &getApplicationProductObjectsByOrderDetail($crit) {
    //first get prod_ids
    $hOrderDetail =& xoops_getmodulehandler('orderDetail','xasset');
    $aDetails     = $hOrderDetail->getObjects($crit);
    $crit         = new CriteriaCompo();
    //
    foreach($aDetails as $key=>$oDetail) {
      $crit->add(new Criteria('id',$oDetail->getAppProdID()));
    }
    //now get products
    $aProds = $this->getObjects($crit,true);
    //index by orderDetail
    $ary = array();
    foreach($aDetails as $key=>$oDetail) {
      $ary[$oDetail->ID()] = $aProds[$oDetail->getAppProdID()];
    }

    return $ary;
  }
  ///////////////////////////////////////////////////
  function &searchApplicationProduct($terms, $andor, $limit, $offset, $userid) {
    $thisTable = $this->tableName();
    //
    $sql = "select * from $thisTable";
    $crit = new CriteriaCompo();
    //
    if (isset($userid) && ($userid > 0)) {
      return;
    }
    
    foreach ($terms as $key=>$term) {
      $sub = new CriteriaCompo();
      $sub->add(new Criteria('item_description',"%$term%",'like'));
      $sub->add(new Criteria('item_rich_description',"%$term%",'like'),'or');
      
      $crit->add($sub,$andor);
    }
    $this->postProcessSQL($sql,$crit);
    $objs =& $this->sqlToArray($sql,true,$limit,$offset);
    //
    return $objs;
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
      $sql = sprintf( 'INSERT INTO %s (id, application_id, tax_class_id, display_order, base_currency_id, package_group_id,
                       sample_package_group_id, item_code,
                       item_description, unit_price, old_unit_price, min_unit_count, max_access, max_days, expires, add_to_group,
                       add_to_group2, item_rich_description, enabled, group_expire_date, group_expire_date2, extra_instructions)
                       VALUES (%u, %u, %u, %u, %u, %u, %u, %s, %s, %f, %f, %u, %u, %u, %u, %u, %u, %s, %u, %u, %u, %s)',
                       $this->_db->prefix($this->_dbtable),  $id, $application_id, $tax_class_id, $display_order,
                       $base_currency_id, $package_group_id, $sample_package_group_id, $this->_db->quoteString($item_code),
                       $this->_db->quoteString($item_description), $unit_price, $old_unit_price, $min_unit_count,
                       $max_access, $max_days, $expires, $add_to_group, $add_to_group2,
                       $this->_db->quoteString($item_rich_description), $enabled, $group_expire_date, $group_expire_date2,
                       $this->_db->quoteString($extra_instructions) );
    } else {
        $sql = sprintf('UPDATE %s SET application_id = %u, tax_class_id = %u, display_order = %u, base_currency_id = %u,
                        package_group_id = %u, sample_package_group_id = %u, item_code = %s, item_description = %s, unit_price = %f, old_unit_price = %f,
                        min_unit_count = %u, max_access = %u, max_days = %u, expires = %u, add_to_group = %u, add_to_group2 = %u,
                        item_rich_description = %s, enabled = %u, group_expire_date = %u, group_expire_date2 = %u, extra_instructions = %s where id = %u',
                        $this->_db->prefix($this->_dbtable), $application_id, $tax_class_id, $display_order,
                        $base_currency_id, $package_group_id, $sample_package_group_id, $this->_db->quoteString($item_code),
                        $this->_db->quoteString($item_description), $unit_price, $old_unit_price,
                        $min_unit_count, $max_access, $max_days, $expires, $add_to_group, $add_to_group2,
                        $this->_db->quoteString($item_rich_description), $enabled, $group_expire_date, $group_expire_date2,
                        $this->_db->quoteString($extra_instructions), $id);
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
    //
    return true;
  }
}
