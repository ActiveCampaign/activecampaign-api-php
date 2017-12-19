<?php
require('./Connector.class.php');
$connect = new AC_Connector('t', 't');
$connect->version = 1;
$connect->debug = false;
$connect->curl('http://ac-data.test', [], 'GET');
