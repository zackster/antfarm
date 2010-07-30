<?php

require_once('../utilities.php');
require_once('../DB.php');
$db = new DB();
var_dump($db->are_email_notifications_disabled(2));
?>