<?php

class MorgueAuth {

    /**
     * wraper function to call an auth implementation if there is one and
     * return the default user if not
     *
     * @returns auth data as a dictionary
     */
    static function get_auth_data() {
        return array("username" => Configuration::get_current_username());
    }
}
