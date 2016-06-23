         <?php
ob_start();
session_start($_POST['morgue_sid']);
echo "staritng";

            $msg = '';
            if (isset($_POST['morgue_login']) && !empty($_POST['morgue_username']) 
               && !empty($_POST['morgue_password'])) {
               
               $org = "hootsuite";
               $url = "https://${org}.okta.com/api/v1/authn";
               $data = array("username" => $_POST['morgue_username'],
                             "password" => $_POST['morgue_password'],
                             "relayState" => "/myapp/some/deep/link/i/want/to/return/to",
                             "options" => array("multiOptionalFactorEnroll" => False, "warnBeforePasswordExpired" => False));
               $options = array(
                   "http" => array(
                   "header" => "Accept: application/json\r\nContent-Type: application/json\r\n",
                   "method" => "POST",
                   "content" => http_build_query($data)
                  ));
               $headers = array();
               $headers[] = 'Accept: application/json';
               $headers[] = 'Content-Type: application/json';
               $ch = curl_init();
               curl_setopt($ch, CURLOPT_URL, $url);

curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
curl_setopt($ch, CURLOPT_POST, 1);
               curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
               curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($data));
               $result = curl_exec($ch);
               $result = json_decode($result, true);
               echo curl_error($ch);
               curl_close($ch);
               if (isset($result['status'])) {
                  // very basic, allows for status success and MFA_REQUIRED to proceed
               echo "hello: " . $result['_embedded']['user']['profile']['firstName'] . "." . $result['_embedded']['user']['profile']['lastName'];
                  $_SESSION['valid'] = true;
                  $_SESSION['timeout'] = time();
                  $_SESSION['username'] = $result['_embedded']['user']['profile']['firstName'] . "." . $result['_embedded']['user']['profile']['lastName'];
                  echo "all clear!";
                  header("Location: index.php");
               } else {
                  echo 'You have entered invalid use name and password.';
                  $_SESSION['username'] = "Not Signed In"; 
                  header("Location: /login");
              }
            }
ob_end_flush();

         ?>
