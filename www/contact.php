<?php

include_once "sendgrid-php/sendgrid-php.php";
$jsonStr = file_get_contents("../config.json");
$config = json_decode($jsonStr);

if (isset($_POST['email'])) {


    function died($error)
    {

        // your error code can go here

        echo "We are very sorry, but there were error(s) found with the form you submitted. ";

        echo "These errors appear below.<br /><br />";

        echo $error . "<br /><br />";

        echo "Please go back and fix these errors.<br /><br />";

        die();

    }


    // validation expected data exists

    if (!isset($_POST['name']) ||

        !isset($_POST['email']) ||

        !isset($_POST['message'])) {

        died('We are sorry, but there appears to be a problem with the form you submitted.');

    }


    $name = $_POST['name'];
    $email = $_POST['email'];
    $message = $_POST['message'];


    $email_message = "Form details below.\n\n";


    function clean_string($string)
    {

        $bad = array("content-type", "bcc:", "to:", "cc:", "href");

        return str_replace($bad, "", $string);

    }


    $email_message .= "Name: " . clean_string($name) . "\n";

    $email_message .= "Email: " . clean_string($email) . "\n";

    $email_message .= "Message: " . clean_string($message) . "\n";


    // create email headers

    $headers = 'From: ' . $email . "\r\n" .

        'Reply-To: ' . $email . "\r\n" .

        'X-Mailer: PHP/' . phpversion();

//	@mail($email_to, $email_subject, $email_message, $headers);

    $request_body = json_decode('{
  "personalizations": [
    {
      "to": [
        {
          "email": "'.$config->email->to.'"
        }
      ],
      "subject": "Hackathon Gaspésie"
    }
  ],
  "from": {
    "email": "'.$config->email->from.'"
  },
  "content": [
    {
      "type": "text/plain",
      "value": "' . $email_message . '"
    }
  ]
}');

    $apiKey = $config->email->sendgridKey;
    $sg = new \SendGrid($apiKey);

    $response = $sg->client->mail()->send()->post($request_body);

    echo $response->statusCode();
    echo $response->body();
    print_r($response->headers());


    ?>


    <!-- include your own success html here -->


    Merci de nous avoir contacter, nous vous répondrons bientôt.


    <?php

}

?>
