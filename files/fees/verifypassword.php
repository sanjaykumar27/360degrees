<?php

/*
 * To Verify Password for editing fees calculation
 * By prateek mathur
 * Dated : 16-JUL-2016.
 */
//call the main config file, functions file and header
require_once "../../config/config.php";
require_once DIR_FUNCTIONS;
function processVerifypassword()
{
    if (isset($_REQUEST['password']) && !empty($_REQUEST['password'])) {
        $userid = $_SESSION['userid'];
        $sql = " SELECT `password` FROM `tbluser` WHERE `userid` = '$userid' ";
        $result = dbSelect($sql);
        if (mysqli_num_rows($result) > 0) {
            $row = mysqli_fetch_assoc($result);
            $password = $row['password'];
            if (password_verify($_REQUEST['password'], $password)) {
                echo '1';
            } else {
                echo '0';
            }
        }
    }
}
