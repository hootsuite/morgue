<?php

use Sabre\DAV\Client;
include 'vendor/autoload.php';
class Uploader_AWS_S3 {

    // Where the files will be source from
    public $s3_parent_bucket;
    public $s3_morgue_bucket;
    public $aws_key_id;
    public $aws_secret_access_key;
    public $aws_region;
    public $s3_client_api_version;

    // Please pass in the appropriate stuff
    public function __construct($options = []) {
        foreach ($options as $key => $value) {
                if (property_exists($this, $key)) {
                        $this->$key = $value;
                }
        }
        $this->create_client();
    }

    private function create_client() {
        // Create an S3 client
        $this->client = new \Aws\S3\S3Client([
            'region'  => $this->aws_region,
            'version' => $this->s3_client_api_version,
            'credentials' => [
                'key'    => $this->aws_key_id,
                'secret' => $this->aws_secret_access_key,
            ],
        ]);
    }


    public function send($filepath, $destination_dir = '') {

        $file_name = basename($filepath);
        $file_name = rawurlencode($file_name);

        $result = $this->client->putObject([
            'ACL' => "public-read",
            'SourceFile' => $filepath,
            'Bucket'     => $this->s3_parent_bucket,
            'Key'        => "{$this->s3_morgue_bucket}/{$file_name}",
        ]);

        return array(
            "location" => $result['ObjectURL'],
            "status" => "201"
        );
    }

    private function process_response($response) {

    }
}
