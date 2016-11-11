<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Auth;
use App\EmailTemplate;
use App\UsersContact;
use App\Campaign;
use App\CampaignStep;
use Validator;
use Excel;
use Carbon\Carbon;
class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');

        $this->middleware('teamSubscribed');
    }

    /**
     * Show the application dashboard.
     *
     * @return Response
     */
    public function show()
    {
       $currentTeamName = "";
       if(isset(Auth::user()->currentTeam->name)){
			$currentTeamName = Auth::user()->currentTeam->name;
		}
        return view('home',compact("currentTeamName"));
    }
    public function showEmailTemplate()
    {
		$user = Auth::user(); 
		$email_templates = EmailTemplate::where("user_id","=",$user->id)->get();
		return view('manage_email_template',compact("email_templates"));
	}
	public function showAddEmailTemplate()
    {				
		return view('add_email_template');
	}
	public function saveAddEmailTemplate(Request $request)
    {				
		$this->validate($request, [
		'template_name' => 'required',
		'template_subject' => 'required', 
        'template_body' => 'required',                     
		]);
		$user = Auth::user();
		$responses = $request->all();
		$email_template = new EmailTemplate;
		$email_template->user_id = $user->id;
		$email_template->template_name = $responses['template_name'];
		$email_template->template_subject = $responses['template_subject'];
		$email_template->template_body = $responses['template_body'];		
		$email_template->save();
		return redirect("/manage-email-template")->with('status',"Email template added successfully");
	}
	public function editEmailTemplate(Request $request)
    {				
		$email_templates = EmailTemplate::findOrFail($request->id);
		
		return view('edit_email_template',compact('email_templates'));
	}
	public function updateEmailTemplate(Request $request)
    {				
		$this->validate($request, [
		'template_name' => 'required',
		'template_subject' => 'required', 
        'template_body' => 'required',                    
		]);
		
		$user = Auth::user();
		$responses = $request->all();
		$email_template = EmailTemplate::findOrFail($request->id);
		if(isset($email_template))
		{
			//$email_template->user_id = $user->id;
			$email_template->template_name = $responses['template_name'];
			$email_template->template_subject = $responses['template_subject'];
			$email_template->template_body = $responses['template_body'];			
			$email_template->save();
		}
		
		return redirect('/manage-email-template')->with('status',"Email template updated successfully");
	}
	public function deleteEmailTemplate(Request $request)
	{
		EmailTemplate::where('id', $request->id)->delete();
		return redirect('/manage-email-template')->with('status',"Email template deleted successfully");
	}
	public function showImportContacts()
	{		
		$user = Auth::user();
		$current_team = $user->currentTeam;
		$all_teams = $user->teams;
		
		return view('import_contacts', compact("current_team","all_teams"));
	}
	public function saveImportContacts(Request $request)
	{
		$user = Auth::user();
		
		 $this->validate($request, [
		'group_name' => 'required',
		'csv' => 'required',
		]);
		
		if ($request->hasFile('csv')) {
			$group_id = $request->group_name;            
            $file = $request->file('csv');
            $filename = Carbon::now()->format('Y-m-d h:i:s')."_".$file->getClientOriginalName();
            $destinationPath = storage_path('/app');           
            $file->move($destinationPath, $filename);
			
            Excel::load('storage/app/'.$filename, function($reader) use($group_id) {
                $objects = $reader->toObject();
               /* echo "<pre>";
                print_r($objects[0]);
                exit;*/				
                foreach ($objects as $object) {
					$contact = new UsersContact;
					$contact->user_id = Auth::user()->id;
					$contact->group_id = $group_id;
					$contact->first_name = $object['first_name'];
					$contact->last_name = $object['last_name'];
					$contact->management_company = $object['management_company'];
					$contact->name = $object['name'];
					$contact->property_type = $object['property_type'];
					$contact->campus = ($object['on_or_off_campus']=="")?"0":"1";
					$contact->no_of_beds = $object['of_beds'];
					$contact->no_of_units = $object['of_units'];
					$contact->phone = $object['phonedefault'];
					$contact->milestone = $object['milestone'];
					$contact->lead_owner = $object['lead_owner'];
					$contact->property_address = $object['property_address'];
					$contact->property_address2 = $object['address_2'];
					$contact->city = $object['city'];
					$contact->country = $object['country'];
					$contact->state = $object['state'];
					$contact->zipcode = $object['zip_code'];
					$contact->additional_contact = $object['additional_contact'];
					$contact->comments = $object['comments'];
					$contact->email = $object['emaildefault'];
					$contact->email_alternate = $object['emailalternate'];
					$contact->lead_source = $object['lead_source'];
					$contact->current_software = $object['current_software'];
					$contact->url = $object['url'];
					$contact->save();
					
					
				}
			});
		}
		return redirect('/import-contacts')->with('status',"User's contact imported successfully");
	}
	public function showAddContact()
	{
		return view("add_contact");
	}
	public function saveAddContact(Request $request)
	{
		$user = Auth::user();		
		 $this->validate($request, [
			'first_name' => 'required',
			'last_name' => 'required',
			'email' => 'required|email',
		]);
		$responses = $request->all();
		$contact = new UsersContact;
		
		$contact->user_id = $user->id;
		$contact->group_id = $user->currentTeam->id;
		$contact->first_name = $responses['first_name'];
		$contact->last_name = $responses['last_name'];
		$contact->email = $responses['email'];
		$contact->save();
		
		return redirect("listed-contacts")->with("status","User's contact added successfully");
	}
	public function showContacts()
	{
		$user = Auth::user();
		$current_team = $user->currentTeam;
		$all_teams = $user->teams;
		
		$contacts_all = UsersContact::where("first_name","!=","")->where("group_id","=",$user->currentTeam->id)->get();
		return view("listed_contacts",compact("contacts_all","all_teams","current_team"));
	}
	public function showEditContact(Request $request)
	{
		$contact = UsersContact::where("id","=",$request->id)->first();
		return view("edit_contact",compact("contact"));
	}
	public function saveEditContact(Request $request)
	{
		$user = Auth::user();		
		 $this->validate($request, [
			'first_name' => 'required',
			'last_name' => 'required',
			'email' => 'required|email',
		]);
		$responses = $request->all();
		$contact = UsersContact::findOrFail($request->id);
		if($contact)
		{
			$contact->first_name = $responses['first_name'];
			$contact->last_name = $responses['last_name'];
			$contact->email = $responses['email'];
			$contact->save();
		}
		return redirect("listed-contacts")->with("status","User's contact updated successfully");
	}
	public function deleteContact(Request $request)
	{
		UsersContact::where('id', $request->id)->delete();
		return redirect('/listed-contacts')->with('status',"User's contact deleted successfully");
	}
	public function showManageCampaigns()
	{
		$user = Auth::user();
		$campaign = Campaign::where("user_id","=",$user->id)->get();
		return view("manage_campaigns",compact("campaign"));
	}
	public function showCreateCampaign()
	{
		$user = Auth::user();
		$current_team = $user->currentTeam;
		$all_teams = $user->teams;
		$email_templates = EmailTemplate::where("user_id","=",$user->id)->get();
		$resultStyles = '';
		$resultStylesSelected='';
		$contacts_all = UsersContact::where("first_name","!=","")->where("last_name","!=","")->where("email","!=","")->where("user_id","=",$user->id)->get();
		foreach($contacts_all as $contact)
		{
			 $resultStyles .= '{id: '.$contact->id.', name: "'.$contact->email.'"},';
		}
		return view("create_campaign",compact("email_templates","all_teams","current_team","contacts_all","resultStylesSelected"));
	}
	public function showEmails()
	{
		
		return view("manage_emails");
	}
	public function showScheduleSendEmails()
	{
		$user = Auth::user();
		$current_team = $user->currentTeam;
		$all_teams = $user->teams;
		$email_templates = EmailTemplate::where("user_id","=",$user->id)->get();
		$resultStyles = '';
		$resultStylesSelected='';
		$contacts_all = UsersContact::where("email","!=","")->where("group_id","=",$user->currentTeam->id)->get();
		foreach($contacts_all as $contact)
		{
			 $resultStyles .= '{id: '.$contact->id.', name: "'.$contact->email.'"},';
		}
		return view("schedule_send_emails",compact("email_templates","all_teams","current_team","resultStyles","resultStylesSelected"));
	}
	public function saveScheduleSendEmails()
	{
		return redirect("manage-emails");
	}
	public function saveSteps(Request $request)
	{
		$responses = $request->all();
		$user = Auth::user();
		
		$contList = "";
		if(isset($responses['contactList']) && count($responses['contactList'])!="0")
		{		
			$contList = implode(",",$responses['contactList']);
		}
		
		$campaign = Campaign::where("campaign_name","=",$responses['campaign_name'])->where("user_id","=",$user->id)->first();
		if(!isset($campaign)){
			$campaign = new Campaign;
			$campaign->user_id = $user->id;
			$campaign->campaign_name = $responses['campaign_name'];
			$campaign->total_steps = "1";
			$campaign->save();
		}
		$group_name = "0";	
		if($responses['group_name']!="")
		{		
			$group_name = $responses['group_name'];
		}
		$campaign_step = new CampaignStep;
		$campaign_step->campaign_id = $campaign->id;
		$campaign_step->step_no = $responses['step_no'];
		$campaign_step->template_id = $responses['t_name'];
		$campaign_step->group_id = $group_name;
		$campaign_step->step_description = $responses['step_description'];
		$campaign_step->auto_send_status = $responses['auto_send'];
		$campaign_step->schedule_picked = isset($responses['spick'])?$responses['spick']:"";
		$campaign_step->schedule_time = isset($responses['stime'])?$responses['stime']:"";
		if($responses['schedule_date']=="1")
		{
			$sDate = date("Y-m-d H:i:s");
		}else{
			$sDate = date("Y-m-d H:i:s",strtotime("+".$responses['schedule_date']." days"));
		}
		$campaign_step->schedule_date = $sDate;//
		$campaign_step->scheduled_day = $responses['schedule_date'];
		$campaign_step->contact_ids = $contList;
		
		$campaign_step->save();
		
		$campaign_count = CampaignStep::where("campaign_id","=",$campaign->id)->get();
		
		$campaign->total_steps = count($campaign_count);
		$campaign->save();
		
		$returnData = array("camp_id"=>$campaign->id, "step_id"=>$campaign_step->id, "contact_ids"=>$contList,"groupId"=>$group_name);
		return json_encode($returnData);
		
	}
	public function saveEdittedStep(Request $request)
	{		
		$responses = $request->all();
		$user = Auth::user();
		
		
		$contList = "";
		if(isset($responses['contactList']) && count($responses['contactList'])!="0")
		{		
			$contList = implode(",",$responses['contactList']);
		}
		
		$campaign = Campaign::where("id","=",$responses['camp_id'])->where("user_id","=",$user->id)->first();
		
		$group_name = "0";	
		if($responses['group_name']!="")
		{		
			$group_name = $responses['group_name'];
		}	
		$campaign_step = CampaignStep::where("id","=",$responses['step_id'])->first();
		
		$campaign_step->campaign_id = $campaign->id;
		$campaign_step->step_no = $responses['step_no'];
		$campaign_step->template_id = $responses['t_name'];
		$campaign_step->group_id = $group_name;
		$campaign_step->step_description = $responses['step_description'];
		$campaign_step->auto_send_status = $responses['auto_send'];
		$campaign_step->schedule_picked = isset($responses['spick'])?$responses['spick']:"";
		$campaign_step->schedule_time = isset($responses['stime'])?$responses['stime']:"";
		
		$campaign_step->schedule_date = date("Y-m-d H:i:s",strtotime("+".$responses['schedule_date']." days"));
		$campaign_step->scheduled_day = $responses['schedule_date'];
		$campaign_step->contact_ids = $contList;
		$campaign_step->save();
		
		$campaign_count = CampaignStep::where("campaign_id","=",$campaign->id)->get();
		
		$campaign->total_steps = count($campaign_count);
		$campaign->save();
		
		$returnData = array("camp_id"=>$campaign->id, "step_id"=>$campaign_step->id, "contact_ids"=>$contList,"groupId"=>$group_name);
		return json_encode($returnData);
		
	}
	public function deleteStep(Request $request)
	{		
		$responses = $request->all();
		$user = Auth::user();
		$campaign = Campaign::where("id","=",$responses['camp_id'])->where("user_id","=",$user->id)->first();
			
		$campaign_step = CampaignStep::where("id","=",$responses['step_id'])->delete();
	
		$campaign_count = CampaignStep::where("campaign_id","=",$campaign->id)->get();
		$camp_idr = "";
		if(count($campaign_count)==0)
		{
			$campaign = Campaign::where("id","=",$responses['camp_id'])->delete();
			
		}else{		
			$campaign->total_steps = count($campaign_count);
			$campaign->save();
			$camp_idr = $campaign->id;
		}
		
		
		return $camp_idr;
		
	}
	public function showEditCampaign(Request $request)
	{
		$user = Auth::user();	
		$current_team = $user->currentTeam;
		$all_teams = $user->teams;
		$email_templates = EmailTemplate::where("user_id","=",$user->id)->get();	
		$campaign = Campaign::with("campaignStep")->where("id","=",$request->id)->where("user_id","=",$user->id)->first();
		$contacts_all = UsersContact::where("first_name","!=","")->where("last_name","!=","")->where("email","!=","")->where("user_id","=",$user->id)->get();		
		return view("edit_campaign",compact("email_templates","all_teams","current_team","campaign", "contacts_all"));
	}
	public function saveCampaignName(Request $request)
	{
		$responses = $request->all();
		$user = Auth::user();
		$campaign = Campaign::where("id","=",$responses['camp_id'])->where("user_id","=",$user->id)->first();
		$campaign->campaign_name = $responses['campaign_name'];
		$campaign->save();
		return;
	}
	public function getSavedContacts(Request $request)
	{
		$responses = $request->all();
		$user = Auth::user();
		
		$campaign_step = CampaignStep::where("id","=",$responses['step_id'])->where("campaign_id","=",$responses['camp_id'])->first();
		
		if($campaign_step['contact_ids']!="")
		{
			$cIds = explode(",",$campaign_step['contact_ids']);
			$contacts = UsersContact::whereIn('id', $cIds)->get();
			
			
			$resTableHeader = '<table class="table">
							<thead>
							<tr>
							<th>S.No</th>
							<th>First Name</th>
							<th>Last Name</th>
							<th>Email</th>
							</tr>
						</thead>
											
						<tbody>';
						$i=1;
			$resTableBody="";
			foreach($contacts as $contact)
			{
				$resTableBody .="<tr>
						<td>".$i."</td>
						<td>".$contact['first_name']."</td>
						<td>".$contact['last_name']."</td>
						<td>".$contact['email']."</td>
					</tr>";
					$i++;
			}
				$resTableFooter = "	</tbody>
						</table>";
		}
		
		return $resTableHeader.$resTableBody.$resTableFooter;
	}
}
