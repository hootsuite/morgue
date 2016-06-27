      <div class = "container form-signin">
         
         <?php
            $msg = '';
            
            if (isset($_POST['login']) && !empty($_POST['username']) 
               && !empty($_POST['password'])) {
               
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
               echo "hello: " . $result['_embedded']['user']['profile']['firstName'] . "." . $result['_embedded']['user']['profile']['lastName'];
               echo curl_error($ch);
               curl_close($ch);
               if (isset($result['status'])) {
                  // very basic, allows for status success and MFA_REQUIRED to proceed
                  $_SESSION['valid'] = true;
                  $_SESSION['timeout'] = time();
                  $_SESSION['username'] = $result['_embedded']['user']['profile']['firstName'] . "." . $result['_embedded']['user']['profile']['lastName'];
                  echo "all clear!";
               } else {
                  echo 'You have entered invalid use name and password.';
              }
            }

         ?>
      </div> <!-- /container -->
      <!-- form from http://www.tutorialspoint.com/php/php_login_example.htm -->
      <div class = "container">
      
         <form class = "form-signin" role = "form" 
            action = "login_check.php" method = "post">
            <h4 class = "form-signin-heading"><?php echo $msg; ?></h4>
            <input type = "text" class = "form-control" 
               name = "morgue_username" placeholder = "username" 
               required autofocus></br>
            <input type = "password" class = "form-control"
               name = "morgue_password" placeholder = "password" required>
            <button class = "btn btn-lg btn-primary btn-block" type = "submit" 
               name = "morgue_login">Login</button>
         </form>
            
      </div> 
      
