<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\UserResource;
use App\Libraries\Encryption;
use App\Models\Country;
use App\Models\SubscriptionPlan;
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

class SubscriptionController extends Controller
{

    public function getPlans(Request $request){
        $plans = SubscriptionPlan::where('publish_status','1')->get();

        if (!count($plans)){
            return  response([
                'message' => 'Subscription plans are not available',
            ], Response::HTTP_NO_CONTENT);
        }

        return  response([
            'data' => $plans,
        ], Response::HTTP_ACCEPTED);

    }

    public function subscribe(Request $request){
        $user = Auth::user();
        $userSubscription = new UserSubscription();
        $currentDate = Carbon::now();

        $previousSubscription = $userSubscription::where('user_id',$user->id)
            ->where('end_subscription_date', '>=', $currentDate)
            ->get();

        if (count($previousSubscription)){
            return response()->json([
                'message'=>'You already have a subscription',
                'data' => new UserResource($user),
                'url' => 'https://estaging.jang.com.pk/payment-form/'.$user->id.'?type=mobile',
            ],Response::HTTP_BAD_REQUEST);
        }
        if (isset($request->plan_id)){
            if ($request->plan_id != 0){

                $plan = SubscriptionPlan::where('publish_status','1')->where('id',$request->plan_id)->first();

               if (!$plan){
                   return response()->json([
                       'message'=>'Invalid Plan',
                   ],Response::HTTP_NOT_FOUND);
               }

               $months = $plan->subscription;
               $months = explode(' ',$months)[0];

               $expiryDate = Carbon::now()->addMonth($months)->format('Y-m-d');

                UserSubscription::create([
                    'user_id'=>$user->id,
                    'subscription_id'=>$request->plan_id,
                    'end_subscription_date'=>$expiryDate
                ]);
            }
        }

        return response()->json([
            'data' => new UserResource($user),
            'url' => 'https://estaging.jang.com.pk/payment-form/'.$user->id,
        ],Response::HTTP_ACCEPTED);




    }


    public function complete_booking(Request $request)
    {
        /*echo '<pre>';print_r($_POST); echo '</pre>';
        die;*/
        $location_key = $request->location_key;
        $return_location_key = $request->return_location_key;
        $extra_amount = $request->extra_amount;
        $extraIDs = $request->extraIDs;
        $extraAmounts = $request->extraAmounts;
        $vehicleID = $request->vehicleID;
        $type = $request->type;


        if($request->session()->get('user_id') == '')
        {
            if($type == 'login'){
                $email = $request->email_login;
                $password = $request->password_login;
            }
            else{
                $fullname = $request->fullname;
                $email = $request->email;
                $password = $request->password;
                $countrycode = $request->countrycode;
                $citycode = $request->citycode;
                $mobile = $request->mobile;
                $firebasekey = '';
                $registeredfromsource = 'web';
            }

            if($_POST['type'] == 'login'){
                $url = '/api/login';
                $param = array('sessionkey'=>$this->sessionKey,'username'=>$email,'password'=>$password);
                $userData = getData($url,$param,"POST");

                if(isset($userData['error']) && !isset($userData['data'])){
                    echo '0|__|'.$userData['error'];
                    die;
                }

                $request->session()->put('code_verify',$userData['data']['codeverify']);
                $request->session()->put('verificationcode',$userData['data']['mobileCode']);
                $request->session()->put('user_id',$userData['data']['reg_id']);
                $request->session()->put('user_name',$userData['data']['first_name'].' '.$userData['data']['last_name']);
                $request->session()->put('user_email',$userData['data']['email_address']);
                $request->session()->put('user_phone','+'.$userData['data']['mobilecountrycode'].' '.$userData['data']['mobile']);

                $userData2 = array('user_fullname'=>$request->session()->get("user_name"),'user_email'=>$request->session()->get("user_email"),'user_phone'=>$request->session()->get("user_phone") );
            }
            else{
                $codeverify = 0;
                $url = '/api/register';
                $param = array('sessionkey'=>$this->sessionKey,'fullname'=>$fullname,'username'=>$email,'password'=>$password,'mobile'=>$countrycode.$citycode.$mobile,'mobilecountrycode'=>$countrycode,'mobilecitycode'=>$citycode,'mobilenumbersingle'=>$mobile,'registeredfromsource'=>$registeredfromsource,'firebasekey'=>$firebasekey,'codeverify'=>$codeverify);
                $userData = getData($url,$param,"POST");

                if(isset($userData['error'])){
                    echo '0|__|'.$userData['error'];
                    die;
                }

                echo $userData['data']['reg_id'].'gfdgdgdf';
                $request->session()->put('code_verify',$userData['data']['codeverify']);
                $request->session()->put('verificationcode',$userData['data']['mobileCode']);
                $request->session()->put('cuser_id',$userData['data']['reg_id']);
                $request->session()->put('cuser_name',$userData['data']['first_name'].' '.$userData['data']['last_name']);
                $request->session()->put('cuser_email',$userData['data']['email_address']);
                $request->session()->put('cuser_phone','+'.$userData['data']['mobilecountrycode'].' '.$userData['data']['mobile']);
                dd($request->session()->all());
                echo $userData['data']['reg_id']."aaa".$request->session()->get('cuser_id')."<pre>"; print_r($userData); die;
                //$userData2 = array('user_fullname'=>$this->session->userdata("user_name"),'user_email'=>$this->session->userdata("user_email"),'user_phone'=>$this->session->userdata("user_phone") );

                echo '1|__|';
                die;
            }
        }
        else{
            $userData2 = array('user_fullname'=>$this->session->userdata("user_name"),'user_email'=>$this->session->userdata("user_email"),'user_phone'=>$this->session->userdata("user_phone") );
        }
        if($this->session->userdata("cuser_id"))
            $userID = $this->session->userdata("cuser_id");
        else
            $userID = $this->session->userdata("user_id");

        $data['piuckup_type'] = $piuckup_type = $this->session->userdata("piuckup_type");
        $data['location'] = $location =  $this->session->userdata("location");
        $data['locationName'] = $locationName =  $this->session->userdata("locationName");
        $data['return_location'] = $return_location =  $this->session->userdata("return_location");
        $data['fromDate'] = $fromDate =  $this->session->userdata("fromDate");
        $data['pickuptime'] = $pickuptime =  $this->session->userdata("pickuptime");
        $data['toDate'] = $toDate =  $this->session->userdata("toDate");
        $data['returntime'] = $returntime =  $this->session->userdata("returntime");
        $data['promotioncode'] = $promotioncode =  $this->session->userdata("promotioncode");
        $data['userage'] = $userage =  $this->session->userdata("userage");
        $data['licenseissuedata'] = $licenseissuedata =  $this->session->userdata("licenseissuedata");
        $data['fromlocationstr'] = $fromlocationstr =  $this->session->userdata("fromlocationstr");
        $filter_airport = ($isairport==1)?'&isairport=1':'';
        $filter_userage = ($userage==1)?'&userage=1':'';
        $filter_licenseissuedata = ($licenseissuedata==1)?'&licenseissuedata=1':'';

        $loc_url = CRON_URL.'index.php/location/all?sessionkey='.$sessionkey;
        $allLocations = getData($loc_url);
        $data['location_key'] = $location_key;
        $data['return_location_key'] = $return_location_key;
        $data['allLocations'] = $allLocations;

        $city = $data['allLocations']->data[$location_key]->cityID;

        if($return_location != ''){
            $filter_return_location = '&returnlocation='.$return_location.'&returncity='.$data['allLocations']->data[$return_location_key]->cityID;
        }

        $book_url = CRON_URL.'index.php/book?userID='.$userID.'&fromlocationstr='.$fromlocationstr.'&pickupdate='.$fromDate.'&pickuptime='.$pickuptime.'&returndate='.$toDate.'&tariff='.$extraIDs.'&returntime='.$returntime.'&location='.$location.'&city='.$city.'&promotion='.$promotioncode.'&vehicleID='.$vehicleID.$filter_airport.$filter_userage.$filter_licenseissuedata.$filter_return_location;

        $bookingDetail = getData($book_url);

        if(isset($bookingDetail->status) && ($bookingDetail->status == "Error")){
            $this->session->set_flashdata("error_message",$bookingDetail->message);
            redirect(base_url()."index.php/home/booking_info/".$vehicleID);
            die;
        }

        $fleet_url = CRON_URL.'index.php/fleet/?fromDate='.$fromDate.'&pickuptime='.$pickuptime.'&toDate='.$toDate.'&returntime='.$returntime.'&promotioncode='.$promotioncode.$filter_airport.$filter_userage.$filter_licenseissuedata;
        $allFleets = getData($fleet_url);
        $key = array_search_inner($allFleets->data, 'vehicleID', $vehicleID);

        $data['allFleets'] = $allFleets;
        $data['key'] = $key;
        $data['extra_amount'] = $extra_amount;
        $data['extraIDs'] = $extraIDs;
        $data['extraAmounts'] = $extraAmounts;

        $data['fullname'] = $fullname;
        $data['email'] = $email;
        $data['countrycode'] = $countrycode;
        $data['mobile'] = $mobile;

        $data['bookingDetail'] = $bookingDetail;
        $data['userData'] = $userData2;

        $this->session->unset_userdata('piuckup_type');
        $this->session->unset_userdata('location');
        $this->session->unset_userdata('locationName');
        $this->session->unset_userdata('return_location');
        $this->session->unset_userdata('fromDate');
        $this->session->unset_userdata('pickuptime');
        $this->session->unset_userdata('toDate');
        $this->session->unset_userdata('returntime');
        $this->session->unset_userdata('promotioncode');
        $this->session->unset_userdata('userage');
        $this->session->unset_userdata('licenseissuedata');


        if($this->session->userdata("code_verify") < 1){
            $this->session->set_userdata("newuser",1);



            redirect(base_url()."index.php/home/verify");
            die;
        }


        $this->load->view('front/header_front', $data);
        $this->load->view('front/booking_detail_view', $data);
        $this->load->view('front/footer_front', $data);
    }

}










