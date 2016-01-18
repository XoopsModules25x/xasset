<?php

require_once('xassetBaseObject.php');

class xassetGatewayLog extends XAssetBaseObject {

  function xassetGatewayLog($id = null) {
    $this->initVar('id', XOBJ_DTYPE_INT, null, false);
    $this->initVar('order_id', XOBJ_DTYPE_INT, null, false);
    $this->initVar('gateway_id', XOBJ_DTYPE_INT, null, false);
    $this->initVar('date', XOBJ_DTYPE_INT, null, false);
    $this->initVar('order_stage', XOBJ_DTYPE_INT, null, false);
    $this->initVar('log_text', XOBJ_DTYPE_TXTBOX, null, false, 50000);

    //
    if (isset($id)) {
      if (is_array($id)) {
        $this->assignVars($id);
      }
    } else {
      $this->setNew();
    }
  }
  function getLogDate($format='l') {
    if ($this->getVar('date') > 0) {
      return formatTimestamp($this->date(''), $format);}
    else {
      return '';
    }
  }
}

class xassetGatewayLogHandler extends xassetBaseObjectHandler {
  //vars
  var $_db;
  var $classname = 'xassetgatewaylog';
  var $_dbtable  = 'xasset_gateway_log';
  //cons
  function xassetGatewayLogHandler(&$db)
  {
    $this->_db = $db;
  }
  ///////////////////////////////////////////////////
  function &getInstance(&$db)
  {
      static $instance;
      if(!isset($instance)) {
          $instance = new xassetGatewayLogHandler($db);
      }

      return $instance;
  }
  ///////////////////////////////////////////////////
    function addLog($orderID, $gatewayID, $orderStage, $log, $force = false) {
        if (is_array($log)) {
      $save = '';
            foreach($log as $key=>$value) {
                $save .= "$key : $value\n";
            }
        } else { $save = $log;}
    //
    $save = addslashes($save);
        //
        $obj  =& $this->create();
        $obj->setVar('order_id',$orderID);
        $obj->setVar('gateway_id',$gatewayID);
        $obj->setVar('date',time());
        $obj->setVar('order_stage',$orderStage);
        $obj->setVar('log_text',$save);
    //
    return $this->insert($obj, $force);
  }
  ///////////////////////////////////////////////////
  function &getLogsByOrder($orderID) {
    $crit = new Criteria('order_id',$orderID);

    return $this->getLogs($crit);
  }
  ///////////////////////////////////////////////////
  function getLogs($criteria = null) {
    global $imagearray;
    //
    $hGateway   =& xoops_getmodulehandler('gateway','xasset');
    $hOrder     =& xoops_getmodulehandler('order','xasset');
    //tables
    $thisTable  = $this->_db->prefix($this->_dbtable);
    $gtTable    = $this->_db->prefix($hGateway->_dbtable);
    $oTable     = $this->_db->prefix($hOrder->_dbtable);
    //
    if (!isset($criteria)) {
      $criteria = new CriteriaCompo();
      $criteria->setSort('date');
    }
    //
    $sql        = "select l.id, l.date, l.order_stage, g.id gateway_id, g.code, o.id order_id, o.uid
                   from $thisTable l inner join $gtTable g on
                     l.gateway_id = g.id inner join $oTable o on
                     l.order_id   = o.id";
    //
    $this->postProcessSQL($sql,$criteria);
    $ary = array();
    //
    if ($res = $this->_db->query($sql)) {
      $i = 0;
      while ($row = $this->_db->fetchArray($res)) {
        $actions = '<a href="main.php?op=removeLogItem&id='.$row['id'].'">'.$imagearray['deleteimg'].'</a>';
        //
        $ary[$i] = $row;
        $ary[$i]['formatDate'] = formatTimestamp($row['date'],'l');
        $ary[$i]['actions']    = $actions;
        //
        $i++;
      }
    }

    return $ary;
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
      $sql = sprintf( 'INSERT INTO %s (id, order_id, gateway_id, date, order_stage, log_text)
											 VALUES (%u, %u, %u, %u, %u, %s)',
                      $this->_db->prefix($this->_dbtable),  $id, $order_id, $gateway_id, $date, $order_stage,
                                            $this->_db->quoteString($log_text));
    } else {
        $sql = sprintf('UPDATE %s SET order_id = %u, gateway_id = %u, date = %u, ordre_stage = %u, log_text = %s where id = %u',
                        $this->_db->prefix($this->_dbtable), $order_id, $gateway_id, $date, $order_stage,
                        $this->_db->quoteString($log_text), $id);
    }
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
