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
        $this->validate($request, [
            'from_email' => 'required|email',            
        ]);

        $request->user()->forceFill([
            'from_email' => $request->from_email,
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
        return Socialite::driver('google')->redirect();
    }

    /**
     * Obtain the user information from GitHub.
     *
     * @return Response
     */
    public function handleProviderCallback()
    {
        $user = Socialite::driver('google')->user();
        $user->getId();
		$user->getNickname();
		$user->getName();
		$email = $user->getEmail();
		$user->getAvatar();
		
		$userLocal = Auth::user();
		$userLocal->from_email = $email;
		$userLocal->save();
		return redirect("/settings");
        // $user->token;
    }
}
