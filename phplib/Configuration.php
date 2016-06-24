<?php

/**
 * a simple way to get configuration
 */
class Configuration {

    static function get_current_username() {
        if (isset($_COOKIE['morgue_username'])) {
            return $_COOKIE['morgue_username'];
        } else {
            return "Not Signed In";
        }
    }

    static function set_current_username($name = null) {
        if (is_null($name)) {
            setCookie('morgue_username', 'Not Signed In', time() + (86400 * 30), "/");
        }
        else {
            setCookie('morgue_username', $name, time() + (86400 * 30), "/");
        }
    }

    /**
     * get the configuration from the JSON files
     *
     * @param name - name of the specific feature to get config for
     *
     * @returns a dictionary object with the config data or an empty array
     */
    static function get_configuration($name = null) {
        $enviroment = getenv('MORGUE_ENVIRONMENT') ?: 'development';
        $configfile = dirname(__FILE__).'/../config/'.$enviroment.'.json';
        $config = json_decode(file_get_contents($configfile), true);
        if (is_null($name)) {
            return $config;
        } else {
            foreach($config["feature"] as $feature) {
                if ($feature['name'] == $name) {
                    return $feature;
                }
            }
            return array();
        }
    }

    /**
     * feature_enabled
     *
     * @param mixed $name
     * @static
     * @access public
     * @return boolean if the named feature is marked as 'enabled' => 'on'
     */
    static function feature_enabled($name = null) {
        if (!$name) {
            return false;
        }
        $c = self::get_configuration($name);
        if ($c['enabled'] === 'on') {
            return true;
        }
        return false;
    }

    /**
     * get_navbar_features
     *
     * @static
     * @access public
     * @return an array of feature data with all enabled nagbar features
     */
    static function get_navbar_features() {
        $navbar_features = array();
        $c = self::get_configuration();
        if (!$c) {
            return $navbar_features;
        }
        foreach ($c['feature'] as $feature) {

            if (array_key_exists('navbar', $feature) &&
                $feature['navbar'] === 'on' &&
                $feature['enabled'] === 'on') {
                $navbar_features[] = $feature;
            }
        }
        return $navbar_features;
    }
}
