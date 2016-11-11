<?php

namespace App\Http\Controllers;
use Google_Client;
use Google_Service_Gmail;
use Google_Service_Gmail_Message;
use Illuminate\Http\Request;
use Mail;
use Storage;
use App\Http\Requests;
use App\Http\Controllers\Controller;
use DB;
use App\User;
use App\EmailTemplate;
use App\UsersContact;
use App\Campaign;
use App\CampaignStep;
use Auth;
use Carbon\Carbon;

class CronSchedule extends Controller
{
	public function sendScheduledEmail()
    {
			$user = User::find(2);
			$client = new Google_Client();
			//your gmail tied ClientId (aka Google Console)
			$client->setClientId("940049426237-82lkejqipf899usm67q5tf2m8pr5hepn.apps.googleusercontent.com");
			$client->setClientSecret("-dSjc3faQF4HzT9keWe95auc");
			//your gmail tied ClientId (aka Google Console)
			$client->setRedirectUri("http://survacity.in/google/callback");
			$client->setAccessType('offline');
			$client->setApprovalPrompt('force');
		  
			$client->addScope("https://mail.google.com/");
			//$client->addScope("https://www.googleapis.com/auth/gmail.compose");
			//$client->addScope("https://www.googleapis.com/auth/gmail.modify");
			//$client->addScope("https://www.googleapis.com/auth/gmail.readonly");
			$gmail_access_token = $user->gmail_access_token;
			
 
				//$isAccessCodeExpired = $client->isAccessTokenExpired();
				  
				  
				//if (isset($gmail_access_token) && !empty($gmail_access_token) && $isAccessCodeExpired != 1) {
				if (isset($gmail_access_token) && !empty($gmail_access_token) ) {
				  
					$client->setAccessToken($gmail_access_token);
					$objGMail = new Google_Service_Gmail($client);
				  
					$strSubject = 'Hardware Issue Email from GMail API' . date('M d, Y h:i:s A');
				  
					$strRawMessage = "From: Developer <".$user->from_email.">\r\n";
					$strRawMessage .= "To: Ashish Kumar <ashish.kumar@srmtechsol.com>\r\n";
					$strRawMessage .= 'Subject: =?utf-8?B?' . base64_encode($strSubject) . "?=\r\n";
					$strRawMessage .= "MIME-Version: 1.0\r\n";
					$strRawMessage .= "Content-Type: text/html; charset=utf-8\r\n";
					$strRawMessage .= 'Content-Transfer-Encoding: quoted-printable' . "\r\n\r\n";
					$strRawMessage .= "Hello All,<br><br>

If anyone is having pending issues with the hardware / software since long please send information to me individually so the actions can be taken to resolve the problems on Priority basis.<br><br>

Regards,<br>
Abhishek Tiwari<br>";
				  
					//Users.messages->send - Requires -> Prepare the message in message/rfc822
					try {
						// The message needs to be encoded in Base64URL
						$mime = rtrim(strtr(base64_encode($strRawMessage), '+/', '-_'), '=');
						$msg = new Google_Service_Gmail_Message();
						$msg->setRaw($mime);
				  
						//The special value **me** can be used to indicate the authenticated user.
						$objSentMsg = $objGMail->users_messages->send("me", $msg);
				  
						print('Message sent object');
						print($objSentMsg);
				  
					} catch (Exception $e) {
						echo "HERE";
						print($e->getMessage());
						unset($gmail_access_token);
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
						header("Location: $authUrl");
					}
				}
	}
}
