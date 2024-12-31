<?php

namespace App\Http\Controllers;

use App\Http\Requests\ProfileUpdateRequest;
use App\Libraries\Encryption;
use App\Models\User;
use App\Notifications\SendTwoFactorCode;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Redirect;
use Illuminate\View\View;

class WebController extends Controller
{

    public function index(Request $request)
    {
//        mohsin452@mailinator.com
//        \Illuminate\Support\Facades\Mail::raw('This is a test email to check if SMTP is working.', function ($message) {
//            $message->to('mushahidahmed@mailinator.com')
//                ->subject('Test Email');
//        });
//        return 'Test email sent!';

//        $user = User::find(19);
//        $user->generateTwoFactorCode();
//        $user->notify(new SendTwoFactorCode());
//


    }


}
