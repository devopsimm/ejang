<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\UserResource;
use App\Libraries\Encryption;
use App\Models\UserMeta;
use App\Models\UserSubscription;
use App\Notifications\SendTwoFactorCode;
use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
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
                'country_code'=>'required|string|max:255',
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
                'country_code' => $validated['country_code'],
                'phone_number' => $validated['phone_number'],
                'country' => $validated['country'],
                'fcm_token' => $validated['fcm_token'],
            ]);
            $subscription = false;
            if ($validated['plan_id'] != 0){
                $subscription = UserSubscription::create([
                    'user_id'=>$user->id,
                    'subscription_id'=>$validated['plan_id']
                ]);
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
            return response()->json(['message' => 'Invalid credentials'], Response::HTTP_BAD_REQUEST);
        }

        $OrgPassword = $CI_Encryption->decrypt($user->password);
        if ($OrgPassword != $validated['password']){
            return response()->json(['message' => 'Invalid credentials'], Response::HTTP_BAD_REQUEST);
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

    public function sendOtp(Request $request){
        $user = Auth::user();
        $user->generateTwoFactorCode();
        $user->notify(new SendTwoFactorCode());
        return response()->json([
            'message' => 'OTP sent successfully',
        ],Response::HTTP_ACCEPTED);

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


}










