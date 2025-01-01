<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\UserResource;
use App\Libraries\Encryption;
use App\Models\Country;
use App\Models\UserSubscription;
use App\Notifications\SendTwoFactorCode;
use Carbon\Carbon;
use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Str;
use Symfony\Component\HttpFoundation\Response;

class AuthController extends Controller
{
    public function register(Request $request)
    {
//        try {
            $CI_Encryption = new Encryption();
            $validated = $request->validate([
                'name' => 'required|string|max:255',
                'emailaddress' => 'required|string|email|max:255|unique:jn_register_users',
                'password' => 'required|string|min:8',
                'country_code'=>'string|max:255',
                'phone_number'=>'required|string|max:255',
                'country'=>'required|int|max:255',
                'fcm_token'=>'required|string|max:255',
                'plan_id'=>'int',
            ], [
                'emailaddress.required' => 'Please provide your email address.',
                'emailaddress.string' => 'Please provide your correct email address.',
                'emailaddress.unique' => 'The email has already been taken.',
            ]);
            $user = User::create([
                'fullname' => $validated['name'],
                'emailaddress' => $validated['emailaddress'],
                'password' => $CI_Encryption->encrypt($validated['password']),
                'phone_number' => $validated['phone_number'],
                'country' => $validated['country'],
                'fcm_token' => $validated['fcm_token'],
            ]);
            $subscription = false;
            if (isset($request->plan_id)){
                if ($validated['plan_id'] != 0){
                    $subscription = UserSubscription::create([
                        'user_id'=>$user->id,
                        'subscription_id'=>$validated['plan_id']
                    ]);
                }
            }

            $token = $user->createToken('auth_token')->plainTextToken;
            return  response([
                'data' => new UserResource(['user' => $user, 'subscription' => $subscription]),
                'token'=>$token,
            ], Response::HTTP_CREATED);
//        }catch (\Exception $e){
//            dd($e->getMessage());
//        }
    }

    public function login(Request $request)
    {
        $CI_Encryption = new Encryption();

        $validated = $request->validate([
            'emailaddress' => 'required|email',
            'password' => 'required',
        ]);


        $user = User::where('emailaddress', $validated['emailaddress'])->first();
        if (!$user) {
            return response()->json(['message' => 'Invalid credentials','status'=>Response::HTTP_BAD_REQUEST], Response::HTTP_BAD_REQUEST);
        }

        $OrgPassword = $CI_Encryption->decrypt($user->password);
        if ($OrgPassword != $validated['password']){
            return response()->json(['message' => 'Invalid credentials'], Response::HTTP_BAD_REQUEST);
        }

        if (isset($request->fcm_token)){
            $user->fcm_token = $request->fcm_token;
            $user->save();
        }


        $token = $user->createToken('auth_token')->plainTextToken;
        $subscription = $user->userSubscription;
        return response()->json([
            'data' => new UserResource(['user' => $user, 'subscription' => $subscription]),
            'token' => $token,
        ],Response::HTTP_ACCEPTED);
    }

    public function logout(Request $request)
    {
        $request->user()->currentAccessToken()->delete();
        return response()->json(['message' => 'Logged out successfully'], Response::HTTP_ACCEPTED);
    }

    public function changePassword(Request $request)
    {
        $CI_Encryption = new Encryption();
        $request->validate([
            'old_password' => 'required|string',
            'new_password' => 'required|string|min:8',
        ]);
        $user = Auth::user();
        $OrgPassword = $CI_Encryption->decrypt($user->password);
        if ($OrgPassword != $request->old_password){
            return response()->json(['message' => 'Old Password is incorrect'], Response::HTTP_BAD_REQUEST);
        }
        $user->password = $CI_Encryption->encrypt($request->new_password);
        $user->save();
        return response()->json([
            'message' => 'Password changed successfully',
        ],Response::HTTP_ACCEPTED);
    }

    public function updateProfile(Request $request)
    {

        $request->validate([
            'name' => 'string|max:255',
            'emailaddress' => 'string|email|max:255',
            'country_code'=>'string|max:255',
            'phone_number'=>'string|max:255',
            'country'=>'int|max:255',
            'fcm_token'=>'string|max:255',
        ], [
            'emailaddress.string' => 'Please provide your correct email address.',
            'emailaddress.unique' => 'The email has already been taken.',
        ]);

        $user = Auth::user();


        if ($request->filled('name')) {
            $user->fullname = $request->name;
        }
        if ($request->filled('emailaddress')) {
            $user->emailaddress = $request->emailaddress;
        }
        if ($request->filled('country_code')) {
            $user->country_code = $request->country_code;
        }
        if ($request->filled('phone_number')) {
            $user->phone_number = $request->phone_number;
        }
        if ($request->filled('country')) {
            $user->country = $request->country;
        }
        if (isset($request->fcm_token)){
            $user->fcm_token = $request->fcm_token;
        }

        $user->save();
        $subscription = $user->userSubscription;
        return response()->json([
            'data' => new UserResource(['user' => $user, 'subscription' => $subscription]),
        ],Response::HTTP_ACCEPTED);
    }

    public function sendOtp(Request $request,$forgetPassword=false){
//        $user = Auth::user();
//        $user->generateTwoFactorCode();
//        $user->notify(new SendTwoFactorCode());


      if ($forgetPassword == 'forget-password'){
          $request->validate([
              'emailaddress' => 'required|string|email|max:255|exists:jn_register_users,emailaddress',
          ], [
              'emailaddress.required' => 'Please provide your email address.',
              'emailaddress.string' => 'Please provide your correct email address.',
          ]);
      }else{
          $request->validate([
              'emailaddress' => 'required|string|email|max:255|unique:jn_register_users',
          ], [
              'emailaddress.required' => 'Please provide your email address.',
              'emailaddress.string' => 'Please provide your correct email address.',
              'emailaddress.unique' => 'The email has already been taken.',
          ]);
      }

      $data = [];
        if ($forgetPassword == 'forget-password') {
            $token = Str::random(60);
            DB::table('password_reset_tokens')->updateOrInsert(
                ['email' => $request->emailaddress],
                [
                    'token' => Hash::make($token),
                    'created_at' => Carbon::now(),
                ]
            );
            $data['token'] =  $token;
        }


        $code = rand(100000, 999999);
        $view = view('otpEmail',compact('code'))->render();
        Mail::html($view, function ($message) use ($request) {
            $message->to($request->emailaddress)
                ->subject('E-jang Two Factor Code');
        });

        $data['data'] = $code;
        $data['message'] = 'OTP sent successfully';
        return response()->json($data,Response::HTTP_ACCEPTED);

    }


    public function verifyOtp(Request $request){
        $user = Auth::user();
        if ($user->verification_code == $request->otp){
            $user->resetTwoFactorCode();
            return response()->json([
                'message' => 'OTP verified',
            ],Response::HTTP_ACCEPTED);
        }else{
            return response()->json([
                'message' => 'OTP invalid',
            ],Response::HTTP_BAD_REQUEST);
        }

    }

    public function getCountries(Request $request){
        $countries = Country::all();
        return response()->json([
            'data' => $countries,
        ],Response::HTTP_ACCEPTED);
    }

    public function forgetPassword(Request $request){
        $CI_Encryption = new Encryption();
        $request->validate([
            'emailaddress' => 'required|string|email|max:255|exists:jn_register_users,emailaddress',
            'token' => 'required|string',
            'password' => 'required|string|min:8',
        ], [
            'emailaddress.required' => 'Please provide your email address.',
            'emailaddress.string' => 'Please provide your correct email address.',
            'emailaddress.unique' => 'The email has already been taken.',
        ]);

        $record = DB::table('password_reset_tokens')->where('email', $request->emailaddress)->first();
        if (!$record) {
            return response()->json([
                'status' => false,
                'message' => 'Invalid token or email.',
            ], 400);
        }
        if (!Hash::check($request->token, $record->token)) {
            return response()->json([
                'status' => false,
                'message' => 'Invalid token.',
            ], 400);
        }
        if (Carbon::parse($record->created_at)->addHour()->isPast()) {
            return response()->json([
                'status' => false,
                'message' => 'Token has expired.',
            ], 400);
        }

        DB::table('password_reset_tokens')->where('email', $request->emailaddress)->delete();


        User::where('emailaddress',$request->emailaddress)->update([
            'password' => $CI_Encryption->encrypt($request->password)
        ]);
        return response()->json([
            'message' => 'Password changed successfully',
        ],Response::HTTP_ACCEPTED);


    }

}










