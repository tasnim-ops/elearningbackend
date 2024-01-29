<?php

namespace App\Http\Controllers;

use App\Models\Teacher;
use App\Models\Visioconference;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Mail;
use Twilio\Jwt\AccessToken;
use Twilio\Jwt\Grants\VideoGrant;

class VisioconferenceController extends Controller
{
    // ... (existing code)

    // Your existing methods...

    public function generateAccessToken()
    {
        // Replace this logic with your actual code to generate the access token

        // Account SID and Auth Token from your Twilio account
        $accountSid = 'AC8b8965f3253664df76257c78cc7efb4e';
        $apiKeySid ='SK55dbd79e9195ec7ba3add5ae9926b1c8';
        $apiKeySecret = 'SHRY2QWADi7nKQsDZT03chxCVexEeYHk';

        // Create an access token
        $accessToken = new AccessToken($accountSid, $apiKeySid, $apiKeySecret);

        // Set the identity of the participant (replace 'participant-identity' with an actual identity)
        $identity = 'participant-identity';
        $accessToken->setIdentity($identity);

        // Create a Video grant for the token
        $videoGrant = new VideoGrant();
        
        // Add the grant to the token
        $accessToken->addGrant($videoGrant);

        // Convert the access token to a JWT string
        $token = $accessToken->toJWT();

        return response()->json(['token' => $token]);
    }
}
