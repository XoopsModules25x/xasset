<?php

class xassetCrypt {
  //
  function cryptValue($value, $weight = 0) {
    $val = $this->sliceValue($value+$weight);
    return $val;
  }
  //
  function sliceValue($value){
    //change this method when public to add more security to encryption method
    $val = md5($value);
    $val = substr($val,5,5);
    $val = md5($val);
    $val = $this->sliceExternal($val,5,5);
    //
    return $val;
  }
  //
  function sliceExternal($key) {
    return substr($key,5,5);
  }
  //
  function keyMatches($value, $extKey, $weight = 0) {
    $intKey = $this->sliceValue($value+$weight);
    //$intKey = $this->sliceExternal($intKey);
    return ($intKey == $extKey);
  }

}

?>
