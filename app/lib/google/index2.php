<?php
session_start();
error_reporting(1);


  require_once 'vendor/autoload.php'; // or wherever autoload.php is located

    $client = new Google_Client();
    $client->setClientId("434393483671-lflp4824t1n75gl6lgthidijslquej0h.apps.googleusercontent.com");
    $client->setClientSecret("0csX8X8g_tXK7HSp_4N6eax-");
    $client->setRedirectUri("http://localhost/gmail/");
    $client->setAccessType('offline');
    $client->setApprovalPrompt('force');
 
    $client->addScope("https://mail.google.com/");
    $client->addScope("https://www.googleapis.com/auth/gmail.compose");
    $client->addScope("https://www.googleapis.com/auth/gmail.modify");
    $client->addScope("https://www.googleapis.com/auth/gmail.readonly");

 
if (isset($_REQUEST['code'])) {
    //land when user authenticated
    $code = $_REQUEST['code'];
    $client->authenticate($code);
     echo $client->getAccessToken();
     exit;
    $_SESSION['gmail_access_token'] = $client->getAccessToken();
     
    header("Location: http://localhost/gmail/");
}
 
//$isAccessCodeExpired = $client->isAccessTokenExpired();
 
 
//if (isset($_SESSION['gmail_access_token']) && !empty($_SESSION['gmail_access_token']) && $isAccessCodeExpired != 1) {
if (isset($_SESSION['gmail_access_token'])) {
    //gmail_access_token setted;
     
    $boundary = uniqid(rand(), true);
 
    $client->setAccessToken($_SESSION['gmail_access_token']);            
    $objGMail = new Google_Service_Gmail($client);
     
    $subjectCharset = $charset = 'utf-8';
    $strToMailName = 'Ashish';
    $strToMail = 'ashish.kumar@srmtechsol.com';
    $strSesFromName = 'Developer';
    $strSesFromEmail = 'dev@liftmedia.io';
    $strSubject = 'Test mail using GMail API' . date('M d, Y h:i:s A');
 
    $strRawMessage .= 'To: ' . encodeRecipients($strToMailName . " <" . $strToMail . ">") . "\r\n";
    $strRawMessage .= 'From: '. encodeRecipients($strSesFromName . " <" . $strSesFromEmail . ">") . "\r\n";
 
    $strRawMessage .= 'Subject: =?' . $subjectCharset . '?B?' . base64_encode($strSubject) . "?=\r\n";
    $strRawMessage .= 'MIME-Version: 1.0' . "\r\n";
    $strRawMessage .= 'Content-type: Multipart/Alternative; boundary="' . $boundary . '"' . "\r\n";
 
//  $strRawMessage .= "\r\n--{$boundary}\r\n";
//    $strRawMessage .= 'Content-Type: '. $mimeType .'; name="'. $fileName .'";' . "\r\n";            
//    $strRawMessage .= 'Content-ID: <' . $strSesFromEmail . '>' . "\r\n";            
//    $strRawMessage .= 'Content-Description: ' . $fileName . ';' . "\r\n";
//    $strRawMessage .= 'Content-Disposition: attachment; filename="' . $fileName . '"; size=' . filesize($filePath). ';' . "\r\n";
//    $strRawMessage .= 'Content-Transfer-Encoding: base64' . "\r\n\r\n";
//    $strRawMessage .= chunk_split(base64_encode(file_get_contents($filePath)), 76, "\n") . "\r\n";
//    $strRawMessage .= '--' . $boundary . "\r\n";
 
    $strRawMessage .= "\r\n--{$boundary}\r\n";
    $strRawMessage .= 'Content-Type: text/plain; charset=' . $charset . "\r\n";
    $strRawMessage .= 'Content-Transfer-Encoding: 7bit' . "\r\n\r\n";
    $strRawMessage .= "this is a test!" . "\r\n";
 
    $strRawMessage .= "--{$boundary}\r\n";
    $strRawMessage .= 'Content-Type: text/html; charset=' . $charset . "\r\n";
    $strRawMessage .= 'Content-Transfer-Encoding: quoted-printable' . "\r\n\r\n";
    $strRawMessage .= "this is a tes2t!" . "\r\n";
     
    //Send Mails
    //Prepare the message in message/rfc822
    try {
        // The message needs to be encoded in Base64URL
        $mime = rtrim(strtr(base64_encode($strRawMessage), '+/', '-_'), '=');
        $msg = new Google_Service_Gmail_Message();
        $msg->setRaw($mime);
        $objSentMsg = $objGMail->users_messages->send("me", $msg);
 
        print('Message sent object');
        print($objSentMsg);
 
    } catch (Exception $e) {
        print($e->getMessage());
        unset($_SESSION['gmail_access_token']);
    }
}
else {
	
    // Failed Authentication
    if (isset($_REQUEST['error'])) {
        //header('Location: ./index.php?error_code=1');
        echo "error auth";
    }
    else{
        // Redirects to google for User Authentication
        $authUrl = $client->createAuthUrl();
        //exit;
        header("Location: $authUrl");
    }
}
 
function encodeRecipients($recipient){
    $recipientsCharset = 'utf-8';
    if (preg_match("/(.*)<(.*)>/", $recipient, $regs)) {
        $recipient = '=?' . $recipientsCharset . '?B?'.base64_encode($regs[1]).'?= <'.$regs[2].'>';
    }
    return $recipient;
}
