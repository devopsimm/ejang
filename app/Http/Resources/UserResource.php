<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class UserResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {

        $user = $this->resource['user'];
        $subscription = $this->resource['subscription'];

        $data = [
            'name'=>$user->fullname,
            'emailaddress'=>$user->emailaddress,
            'country_code'=>$user->country_code,
            'phone_number'=>$user->phone_number,
            'country'=>$user->getCountry,
            'fcm_token'=>$user->fcm_token,
            ];
        if ($subscription){
            $data['plan_id']  = $subscription->subscription_id;
        }

        return  $data;
    }
}
