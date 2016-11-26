<?php

class Uploader {

    public $default_uploader_type = "AWS_S3";
    public $uploaders_path = "upload/lib";
    private $driver;

    /* The options here come from the config.json */
    public function __construct($options) {

        $options['upload_driver'] = $this->default_uploader_type;

        $type = strtolower($options['upload_driver']);
        $class_name = "Uploader_AWS_S3";

        $settings = $options['upload_driver_options'];

        if (!class_exists($class_name)) {
            if ($lib_path = stream_resolve_include_path($this->uploaders_path .
                "/" . $type . ".php")) {
                include $lib_path;
            } else {
                throw new Exception("Could not fine uploader lib: {$this->uploaders_path}");
            }
        }

        error_log("Uploader created with: " . $type . " driver.");

        $this->driver = new $class_name($settings);
    }

    /* Once you have a $driver, use its send method to send your file.
        - Tell it the $file_path to read from.
        - Tell it the $event_id to associate.
       From the send method, we'll get back an array of:
        - location => The published URL of the file.
        - status   => Status code.
       We return that back up.
     */
    public function send_file($file_path, $event_id = 0) {
        $location = $this->driver->send($file_path, $event_id);
        return $location;
    }

}
