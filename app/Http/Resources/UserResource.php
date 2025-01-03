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
        $data = [
                'name'=>$this->fullname,
                'emailaddress'=>$this->emailaddress,
                'country_code'=>$this->country_code,
                'phone_number'=>$this->phone_number,
                'country'=>$this->getCountry,
                'fcm_token'=>$this->fcm_token,
            ];
        if ($this->userSubscription){
            $data['subscription'] = new SubscriptionResource($this->userSubscription);
        }else{
            $data['subscription'] = null;
        }

        return  $data;
    }
}
