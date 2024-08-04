<?php
        // Retrieve settings from Parameter Store
        error_log('Retrieving settings');
        require 'aws/aws-autoloader.php';

        use Aws\SecretsManager\SecretsManagerClient;
        use Aws\Exception\AwsException;
        //$az = file_get_contents('http://169.254.169.254/latest/meta-data/placement/availability-zone');

        $ch = curl_init();

        // get a valid TOKEN
        $headers = array (
                'X-aws-ec2-metadata-token-ttl-seconds: 21600' );
        $url = "http://169.254.169.254/latest/api/token";
        //echo "URL ==> " .  $url;
        curl_setopt( $ch, CURLOPT_HTTPHEADER, $headers );
        curl_setopt( $ch, CURLOPT_RETURNTRANSFER, true );
        curl_setopt( $ch, CURLOPT_CUSTOMREQUEST, "PUT" );
        curl_setopt( $ch, CURLOPT_URL, $url );
        $token = curl_exec( $ch );
        
        //echo "<p> TOKEN :" . $token;
        // then get metadata of the current instance 
        $headers = array (
                'X-aws-ec2-metadata-token: '.$token );
        
        $url = "http://169.254.169.254/latest/meta-data/placement/availability-zone";
        
        curl_setopt( $ch, CURLOPT_URL, $url );
        curl_setopt( $ch, CURLOPT_HTTPHEADER, $headers );
        curl_setopt( $ch, CURLOPT_RETURNTRANSFER, true );
        curl_setopt( $ch, CURLOPT_CUSTOMREQUEST, "GET" );
        $result = curl_exec( $ch );
        $az = curl_exec( $ch );
        
        //echo "<p> RESULT :" . $result;

        $region = substr($az, 0, -1);
        
          // Create a Secrets Manager client
        $secretsManagerClient = new SecretsManagerClient([
          'region' => 'us-east-1', // Replace with your desired region
          'version' => 'latest'
        ]);

        // Secret names from capstone-project-template.yaml
        $databaseUserNameSecret = 'capstone/databaseUsername';
        $databaseNameSecret = 'capstone/databaseName';
        $databasePasswordSecret = 'capstone/databasePassword';
        $auroraEndpointSecret = 'capstone/databaseClusterEndpoint';


        try {
             // Retrieve secrets
          $databaseUserName = getSecretValue($secretsManagerClient, $databaseUserNameSecret);
          $databaseName = getSecretValue($secretsManagerClient, $databaseNameSecret);
          $databasePassword = getSecretValue($secretsManagerClient, $databasePasswordSecret);
          $auroraEndpoint = getSecretValue($secretsManagerClient, $auroraEndpointSecret);


          $un = $databaseUserName;
          $pw = $databasePassword;
          $db = $databaseName;
          $ep = $auroraEndpoint;
        }
        catch (Exception $e) {
          $ep = '';
          $db = '';
          $un = '';
          $pw = '';
        }
      error_log('Settings are: ' . $ep. " / " . $db . " / " . $un . " / " . $pw);
      //echo " Check your Database settings ";
      
      /**
       * Retrieves the secret value from AWS Secrets Manager
       *
       * @param SecretsManagerClient $client
       * @param string $secretName
       * @return string
       */
      function getSecretValue(SecretsManagerClient $client, string $secretName)
      {
          $secretValue = '';
          try {
              $result = $client->getSecretValue([
                  'SecretId' => $secretName,
              ]);
              $secretValue = $result['SecretString'];
          } catch (AwsException $e) {
              // Handle exceptions
              echo "Error retrieving secret '" . $secretName . "': " . $e->getMessage() . PHP_EOL;
          }
          return $secretValue;
      }
?>