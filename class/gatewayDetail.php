<?php

require_once('xassetBaseObject.php');

class xassetGatewayDetail extends XoopsObject {

  function xassetGatewayDetail($id = null) {
    $this->initVar('id', XOBJ_DTYPE_INT, null, false);
    $this->initVar('gateway_id', XOBJ_DTYPE_INT, null, false);
    $this->initVar('gkey', XOBJ_DTYPE_TXTBOX, null, false, 30);
    $this->initVar('gvalue', XOBJ_DTYPE_TXTBOX, null, false, 64000);
    $this->initVar('gorder', XOBJ_DTYPE_INT, null, false);
    $this->initVar('description', XOBJ_DTYPE_TXTBOX, null, false, 200);
    $this->initVar('list_ov', XOBJ_DTYPE_TXTBOX, null, false, 200);
    $this->initVar('gtype', XOBJ_DTYPE_TXTBOX, null, false, 1);
    //
    if (isset($id)) {
      if (is_array($id)) {
        $this->assignVars($id);
      }
    } else {
      $this->setNew();
    }
  }
}

class xassetGatewayDetailHandler extends xassetBaseObjectHandler {
  //vars
  var $_db;
  var $classname = 'xassetgatewaydetail';
  var $_dbtable  = 'xasset_gateway_detail';
  //cons
  function xassetGatewayDetailHandler(&$db)
  {
    $this->_db = $db;
  }
  ///////////////////////////////////////////////////
  function &getByIndex($id){
    $crit = new Criteria('gateway_id',$id);

    return $this->getObjects($crit);
  }
  ///////////////////////////////////////////////////
  function &getByCode($id, $code){
    $crit = new CriteriaCompo(new Criteria('gateway_id',$id));
    $crit->add(new Criteria('gkey',$code));
    //
    $objs = $this->getObjects($crit);

    return $objs[reset($objs)];
  }
  ///////////////////////////////////////////////////
  function saveConfigValue($indexID, $key, $value) {
    $crit = new CriteriaCompo(new Criteria('gkey',$key));
    $crit->add(new Criteria('gateway_id',$indexID));
    //
    $detail = $this->getObjects($crit);
    //
    if (count($detail) > 0) {
      $detail = reset($detail);
    } else {
      $detail =& $this->create();
      $detail->setVar('gateway_id',$indexID);
      $detail->setVar('gkey',$key);
    }
    //
    if ($detail->getVar('gvalue') != $value) {
      $detail->setVar('gvalue',$value);
      //
      return $this->insert($detail);
    } else {
      return true;
    }
  }
  ///////////////////////////////////////////////////
  function deleteGatewayConfig($id, $keys) {
    $thisTable = $this->_db->prefix($this->_dbtable);
    $keys      = implode("','",$keys);
    $keys      = "'$keys'";
    //
    $sql = "delete from $thisTable where gateway_id = $id and gkey in ($keys)";

    return $this->_db->queryF($sql);
  }
  ///////////////////////////////////////////////////
  function getBinaryConfigArrayByIndex($indexID) {
    $crit = new CriteriaCompo(new Criteria('gateway_id',$indexID));
    $crit->add(new Criteria('gtype','b'));
    //
    return $this->getConfigArray($crit);
  }
  ///////////////////////////////////////////////////
  function getConfigArrayByIndex($indexID) {
    $crit = new CriteriaCompo(new Criteria('gateway_id',$indexID));
    $crit->setSort('gorder');
    //
    return $this->getConfigArray($crit);
  }
  ///////////////////////////////////////////////////
  function getConfigArray($crit = null) {
    $objs = $this->getObjects($crit);
    $ary  = array();
    //
    foreach($objs as $obj) {
      $ary[]  = array('id'          => $obj->getVar('id'),
                      'gorder'      => $obj->getVar('gorder'),
                      'gkey'        => $obj->getVar('gkey'),
                      'gvalue'      => $obj->getVar('gvalue'),
                      'description' => $obj->getVar('description'),
                      'gtype'       => $obj->getVar('gtype'),
                      'htmlField'   => $this->renderField($obj) );
    }

    return $ary;
  }
  ///////////////////////////////////////////////////
  function renderField($obj) {
    $key   = $obj->getVar('gkey');
    $value = $obj->getVar('gvalue');
    //$size  = $obj->vars[$obj->getVar('dkey')]['maxlength'];
    //
    switch ($obj->getVar('gtype')) {
      case 's':
        return "<input name='values[$key]' type='text' value='$value' size='50'>";
        break;
      //
      case 'b':
        if ($obj->getVar('gvalue')) $checked = 'checked'; else {$checked = '';}

        return "<input name='values[$key]' type='checkbox' value='' $checked> ";
        break;
      //
      case 'a':
        $lov   = explode(',',$obj->getVar('list_ov'));
        $select = "<select name=values[$key]>";
        foreach($lov as $val) {
          if ($val == $value) {
            $selected = 'selected="selected"';
          } else {
            $selected = '';
          }
          //
          $select .= "<option label='$val' value='$val' $selected>$val</option>";
        }

        return $select;
        break;
      case 'x':
        return "<textarea name='values[$key]' cols='70' rows='5'>$value</textarea>";
      break;
    }
  }
  ///////////////////////////////////////////////////
  function &getInstance(&$db)
  {
      static $instance;
      if(!isset($instance)) {
          $instance = new xassetGatewayDetailHandler($db);
      }

      return $instance;
  }
  ///////////////////////////////////////////////////
  function insert(&$obj, $force = false)
  {
    if (!parent::insert($obj, $force)) {
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
      $sql = sprintf( 'INSERT INTO %s (id, gateway_id, gkey, gvalue, gorder, description, list_ov, gtype)
                       VALUES (%u, %u, %s, %s, %u, %s, %s, %s)',
                                            $this->_db->prefix($this->_dbtable),  $id, $gateway_id, $this->_db->quoteString($gkey),
                      $this->_db->quoteString($gvalue), $gorder, $this->_db->quoteString($description),
                      $this->_db->quoteString($list_ov), $this->_db->quoteString($gtype));
    } else {
        $sql = sprintf( 'UPDATE %s SET gateway_id = %u, gkey = %s, gvalue = %s, gorder = %u, description = %s, list_ov = %s,  gtype = %s
                         where id = %u',
                        $this->_db->prefix($this->_dbtable), $gateway_id, $this->_db->quoteString($gkey),
                        $this->_db->quoteString($gvalue), $gorder, $this->_db->quoteString($description),
                        $this->_db->quoteString($list_ov), $this->_db->quoteString($gtype), $id);
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
}
