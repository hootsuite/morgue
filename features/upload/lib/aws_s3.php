<?php

use Sabre\DAV\Client;
include 'vendor/autoload.php';
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
