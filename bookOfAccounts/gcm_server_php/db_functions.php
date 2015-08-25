<?php

class DB_Functions {

    private $db;

    //put your code here
    // constructor
    function __construct() {
        include_once 'db_connect.php';
        // connecting to database
        $this->db = new DB_Connect();
        $this->db->connect();
    }

    // destructor
    function __destruct() {
        
    }

    /**
     * Storing new user
     * returns user details
     */
    public function storeUser($userID, $gcm_regid,$deviceId) {
        // insert user into database
		   $userID=$userID;
		   $gcm_regid=$gcm_regid;
		   $deviceId=$deviceId;
		   $infoQuery=mysql_query("SELECT * FROM GCM_USERS_T WHERE CONTACT_ID=$userID AND GCM_REGID='$gcm_regid'");
  		   $infoQueryResult=mysql_fetch_array($infoQuery);
  		   if(!$infoQueryResult){
				 $result = mysql_query("INSERT INTO GCM_USERS_T(CONTACT_ID,GCM_REGID,CREATED_DATE,device_id) VALUES('$userID','$gcm_regid', NOW(),'$deviceId')");
						      
		  }
		  else{
				mysql_query("UPDATE GCM_USERS_T SET GCM_REGID='$gcm_regid',CONTACT_ID='$userID' WHERE CONTACT_ID=$userID AND GCM_REGID='$gcm_regid' AND device_id='$deviceId'");
		  }
        // check for successful store
        if ($result) {
            // get user details
            $id = mysql_insert_id(); // last inserted id
            $result = mysql_query("SELECT * FROM GCM_USERS_T WHERE ID = $id") or die(mysql_error());
            // return user details
            if (mysql_num_rows($result) > 0) {
                return mysql_fetch_array($result);
            } else {
                return false;
            }
        } else {
            return false;
        }
    }

    /**
     * Get user by email and password
     */
    public function getUserByEmail($email) {
        //$result = mysql_query("SELECT * FROM GCM_USERS_T WHERE email = '$email' LIMIT 1");
        //return $result;
    }

    /**
     * Getting all users
     */
    public function getAllUsers() {
        $result = mysql_query("SELECT * FROM GCM_USERS_T");
        return $result;
    }

    /**
     * Check user is existed or not
     */
    /*public function isUserExisted($email) {
        $result = mysql_query("SELECT email from gcm_users WHERE email = '$email'");
        $no_of_rows = mysql_num_rows($result);
        if ($no_of_rows > 0) {
            
            return true;
        } else {
            
            return false;
        }
    }*/

}

?>