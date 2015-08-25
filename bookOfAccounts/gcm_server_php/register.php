<?php

// response json
$json = array();

/**
 * Registering a user device
 * Store reg id in users table
 */
if (isset($_POST["contactID"]) && isset($_POST["regId"]) && isset($_POST["deviceId"])) {
    $userID = $_POST["contactID"];
    $deviceId= $_POST["deviceId"];
    //$email = $_POST["email"];
    $gcm_regid = $_POST["regId"]; // GCM Registration ID
    // Store user details in db
    include_once 'db_functions.php';
    include_once 'GCM.php';

    $db = new DB_Functions();
    $gcm = new GCM();

    $res = $db->storeUser($userID, $gcm_regid,$deviceId);

    $registatoin_ids = array($gcm_regid);
    $message = array("Response" => "msg");

    $result = $gcm->send_notification($registatoin_ids, $message);

    echo $result;
} else {
    // user details missing
}
?>