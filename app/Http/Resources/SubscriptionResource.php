<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class SubscriptionResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {

        return [
            'end_subscription_date' => $this->end_subscription_date,
            'plan_id' => $this->subscription->id,
            'subscription' => $this->subscription->subscription,
            'subscription_price' => $this->subscription->subscription_price,
            'is_paid' => $this->user->is_payment,
        ];
    }
}
