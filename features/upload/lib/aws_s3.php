<?php

use Sabre\DAV\Client;
include 'vendor/autoload.php';
/*
 * Wraps and adapts Sabre webdav client to our use.
 * By design all files will be uploaded to a directory
 * named the same as the username.
 * In that directory we will attempt to create a directory
 * named for the event_id (some number) and images
 * associated with that event will be placed in that directory.
 *
 * Example.  Username: morgue
 *           Event ID: 13
 *           upload file name: a_graph.png
 * will attempt to MKCOL /morgue/13
 * and then PUT our file into /morgue/13/a_graph.png
 */
class Uploader_AWS_S3 {

    // Where the files will be source from
    public $morgue_bucket = 'hootsuite-ops';

    // Please pass in the appropriate stuff
    public function __construct() {
        $this->create_client();
    }

    private function create_client() {
        // Create an S3 client
        $this->client = new \Aws\S3\S3Client([
            'region'  => 'us-east-1',
            'version' => 'latest',
            'profile' => 'default', // gets credentials from /.aws/credentials
        ]);
    }


    public function send($filepath, $destination_dir = '') {

        $file_name = basename($filepath);
        $file_name = rawurlencode($file_name);

        $result = $this->client->putObject([
            'ACL' => "public-read",
            'SourceFile' => $filepath,
            'Bucket'     => $this->morgue_bucket,
            'Key'        => "morgue/{$file_name}",
        ]);

        return array(
            "location" => $result['ObjectURL'],
            "status" => "201"
        );
    }

    private function process_response($response) {

    }
}
