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
		$today = date("Y-m-d H:i:s");
		$time = date("H:i");
		$camp_steps = CampaignStep::where("schedule_date","<=",$today)->where("auto_send_status","=","1")->where("send_status","=","0")->orderBy("scheduled_day","asc")->get();
		$total_camp_steps = count($camp_steps);
		if($total_camp_steps>0)
		{
			// looping found campaign steps
			foreach($camp_steps as $step)
			{				
				// find Team's saved contacts 
				$sendEmail = false;
				
				if($step['schedule_picked']=="Immediate"){
					$sendEmail = true;
				}elseif($step['schedule_picked']=="Best Time"){
					if($time=="00:01")
					{
						$sendEmail = true;
					}					
				}elseif($step['schedule_picked']=="Afternoon"){					
					if($time=="12:01")
					{
						$sendEmail = true;
					}	
				}elseif($step['schedule_picked']=="Evening"){					
					if($time=="16:01")
					{
						$sendEmail = true;
					}	
				}elseif($step['schedule_picked']=="Night"){
					if($time=="20:01")
					{
						$sendEmail = true;
					}	
				}elseif($step['schedule_picked']=="Custom"){
					if($time==$step['schedule_time'])
					{
						$sendEmail = true;
					}	
				}
				
				if($sendEmail==true)
				{
					if($step['group_id']!=0){				
						$contacts = UsersContact::where("group_id","=",$step['group_id'])->orderBy("created_at","asc")->get();
					}else{
						$contactText = explode(",",$step['contact_ids']) ;
						$contacts = UsersContact::whereIn("id",$contactText)->get();						
					}
					
					
					$email_template = EmailTemplate::find($step['template_id']);
					$user = User::find($email_template['user_id']);					
					
					
										
					/*echo "<pre>";
					print_r($contacts);
					exit;*/
					
					foreach($contacts as $contact)
					{
						//echo $step['id'] ." Contact Id--".$contact->id." Name--".$contact->first_name." Email--".$contact->email;
						//echo "<br><br>";
						
						$data = array(
								'email' => $contact['email'],
								'first_name' => $contact['first_name'],
								'last_name' => $contact['last_name'],
								'subject' => $email_template->template_subject,	
								'user_name' => $user->name,	
								'user_from_email' => $user->email,
								'gmail_access_token' = $user->gmail_access_token;	
												
								);
						
						$e_content = $email_template->template_body;				
										
						$searchString = array("{first_name}", "{last_name}", "{email}");
						$replaceString   = array($data['first_name'], $data['last_name'], $data['email']);

						$msg = str_replace($searchString, $replaceString,  $e_content);
						$data['msg'] = $msg.$user['signature'];
						//Start
						if (isset($data['gmail_access_token']) && !empty($data['gmail_access_token']) ) {
							$client = new Google_Client();
							//your gmail tied ClientId (aka Google Console)
							$client->setClientId("940049426237-82lkejqipf899usm67q5tf2m8pr5hepn.apps.googleusercontent.com");
							$client->setClientSecret("-dSjc3faQF4HzT9keWe95auc");
							//your gmail tied ClientId (aka Google Console)
							$client->setRedirectUri("http://survacity.in/google/callback");
							$client->setAccessType('offline');
							$client->setApprovalPrompt('force');
						  
							$client->addScope("https://mail.google.com/");
							
							$client->setAccessToken($data['gmail_access_token']);
							$objGMail = new Google_Service_Gmail($client);
						  
							$strSubject = 'Hardware Issue Email from GMail API' . date('M d, Y h:i:s A');
						  
							$strRawMessage = "From: ".$data['user_name']." <".$data['from_email'].">\r\n";
							$strRawMessage .= "To: ".$data['first_name'].' '.$data['last_name']." <".$data['email'].">\r\n";
							$strRawMessage .= 'Subject: =?utf-8?B?' . base64_encode($strSubject) . "?=\r\n";
							$strRawMessage .= "MIME-Version: 1.0\r\n";
							$strRawMessage .= "Content-Type: text/html; charset=utf-8\r\n";
							$strRawMessage .= 'Content-Transfer-Encoding: quoted-printable' . "\r\n\r\n";
							$strRawMessage .= $data['msg'];
						  
							//Users.messages->send - Requires -> Prepare the message in message/rfc822
							try {
								// The message needs to be encoded in Base64URL
								$mime = rtrim(strtr(base64_encode($strRawMessage), '+/', '-_'), '=');
								$msg = new Google_Service_Gmail_Message();
								$msg->setRaw($mime);
						  
								//The special value **me** can be used to indicate the authenticated user.
								$objSentMsg = $objGMail->users_messages->send("me", $msg);
						  
								print('Message sent object');
								//print($objSentMsg);
						  
							} catch (Exception $e) {
								//echo "HERE";
								print($e->getMessage());
								//unset($gmail_access_token);
							}
						}
						//end
						
						
						
				/*	echo "<pre>";
					print_r($contacts);
					print_r($data);
					exit;
						Mail::queue(['html' =>'emails.email_master'], $data, function ($message) use ($data) {
							$message->from($data['user_from_email'], $data['user_name']);
							$message->subject($data['subject']);
							$message->to($data['email']);
						});	
					*/	
					}
					$camp_step = CampaignStep::find($step['id']);
					$camp_step->send_status = 1;
					$camp_step->save();					
					
					$campaign = Campaign::find($step['campaign_id']);
					$camp_step_arr = CampaignStep::where("campaign_id","=",$campaign->id)->where("send_status","=","1")->get();
					
					if(count($camp_step_arr)==$campaign->total_steps)
					{
						$campaign->status = "1";
						$campaign->save();
					}
					
				}
			}
			
		}
		
	}
    
   
}
