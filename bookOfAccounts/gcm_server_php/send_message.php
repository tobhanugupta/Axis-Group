<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */
if (isset($_POST["regId"]) && isset($_POST["chknotification"])) {
    $regId = $_POST["regId"];
    $message = $_POST["chknotification"];
    
    include_once './GCM.php';
    
    $gcm = new GCM();

    $registatoin_ids = array($regId);
    $message = array("Message" => $message);

    $result = $gcm->send_notification($registatoin_ids, $message);

    echo $result;
}
?>
