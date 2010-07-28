<?php

require_once('../thirdparty/mailchimp.class.php');
require_once('../config.php');

$api = new MCAPI($mailchimp_api_key);


$listId = '02aeb1161b'; // this is the "EndAnts Users" list.
$merge_vars = array('USERNAME' => 'zackattack'); 
$my_email = 'zackster+mctest3@gmail.com';
$retval = $api->listSubscribe( $listId, $my_email, $merge_vars );

if ($api->errorCode){
	echo "Unable to load listSubscribe()!\n";
	echo "\tCode=".$api->errorCode."\n";
	echo "\tMsg=".$api->errorMessage."\n";
} else {
    echo "Returned: ".$retval."\n";
}

?>
