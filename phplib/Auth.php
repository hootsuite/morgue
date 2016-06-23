<?php

class MorgueAuth {

    /**
     * wraper function to call an auth implementation if there is one and
     * return the default user if not
     *
     * @returns auth data as a dictionary
     */
    static function get_auth_data() {
        if (isset($_SESSION["username"])) {
            $admin_data =  array("username" => $_SESSION["username"]);
        } else {
            $admin_data =  array("username" => "Not Signed In");
            // header("Location:index.php");
        }
        return $admin_data;
    }
}
