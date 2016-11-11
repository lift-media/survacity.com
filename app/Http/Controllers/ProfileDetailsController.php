<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Auth;
use Socialite;

class ProfileDetailsController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Update the user's profile details.
     *
     * @return Response
     */
    public function update(Request $request)
    {        
        $request->user()->forceFill([           
            'signature' => $request->signature,
        ])->save();
    }
    
     /**
     * Redirect the user to the GitHub authentication page.
     *
     * @return Response
     */
    public function redirectToProvider()
    {
         $scopes = [
            'https://www.googleapis.com/auth/calendar',
            'https://mail.google.com/',
        ];
        return Socialite::driver('google')
						->with(["access_type" => "offline", "prompt" => "consent select_account"])// Added for token expire immediate
						->scopes($scopes)
						->redirect();
    }

    /**
     * Obtain the user information from GitHub.
     *
     * @return Response
     */
    private function sendMessage($service, $userId, $message) {
		  try {
			$message = $service->users_messages->send($userId, $message);
			print 'Message with ID: ' . $message->getId() . ' sent.';
			return $message;
		  } catch (Exception $e) {
			print 'An error occurred: ' . $e->getMessage();
		  }
	}
    public function handleProviderCallback()
    {
        $user = Socialite::driver('google')->user();        
        $accessToken =  $user->getGmailAccessToken();
		/*
        echo "<pre>";
        print_r($user);
        die;
        $user->getId();
		$user->getNickname();
		$user->getName();
		$user->getAvatar();*/
		$email = $user->getEmail();
		
		
		$userLocal = Auth::user();
		$userLocal->from_email = $email;
		$userLocal->gmail_access_token = $accessToken;
		$userLocal->save();
		
		
		return redirect("/settings");
        
    }
    private function encodeRecipients($recipient){
    $recipientsCharset = 'utf-8';
    if (preg_match("/(.*)<(.*)>/", $recipient, $regs)) {
        $recipient = '=?' . $recipientsCharset . '?B?'.base64_encode($regs[1]).'?= <'.$regs[2].'>';
    }
    return $recipient;
	}
}
