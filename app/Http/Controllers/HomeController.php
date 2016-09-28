<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Auth;
use App\EmailTemplate;
use App\UsersContact;
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
        'template_signature' => 'required',             
		]);
		$user = Auth::user();
		$responses = $request->all();
		$email_template = new EmailTemplate;
		$email_template->user_id = $user->id;
		$email_template->template_name = $responses['template_name'];
		$email_template->template_subject = $responses['template_subject'];
		$email_template->template_body = $responses['template_body'];
		$email_template->template_signature = $responses['template_signature'];
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
        'template_signature' => 'required',             
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
			$email_template->template_signature = $responses['template_signature'];
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
		
		$contacts_all = UsersContact::where("first_name","!=","")->where("group_id","=",$user->currentTeam->id)->take(200)->get();
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
}
