<?php
require('header.php');
//
redirect_header(XOOPS_URL.'/index.php',3,'The Payment Gateway has indicated that the order has been cancelled');
//
include(XOOPS_ROOT_PATH . "/footer.php");
