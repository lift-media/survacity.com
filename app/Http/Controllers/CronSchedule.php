<?php

namespace App\Http\Controllers;

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
								);
						
						$e_content = $email_template->template_body;				
										
						$searchString = array("{first_name}", "{last_name}", "{email}");
						$replaceString   = array($data['first_name'], $data['last_name'], $data['email']);

						$msg = str_replace($searchString, $replaceString,  $e_content);
						$data['msg'] = $msg.$user['signature'];
				/*	echo "<pre>";
					print_r($contacts);
					print_r($data);
					exit;*/
						Mail::queue(['html' =>'emails.email_master'], $data, function ($message) use ($data) {
							$message->from($data['user_from_email'], $data['user_name']);
							$message->subject($data['subject']);
							$message->to($data['email']);
						});	
						
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
