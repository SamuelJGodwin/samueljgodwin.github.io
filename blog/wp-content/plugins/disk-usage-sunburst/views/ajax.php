<?php

global $RBDUSB_ABSPATH;

require(dirname(__FILE__).'/../inc/scan.php');

echo json_encode(rbdusb_scan($RBDUSB_ABSPATH, $RBDUSB_ABSPATH));
