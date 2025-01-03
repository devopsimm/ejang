<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UserSubscription extends Model
{
    use HasFactory;

    protected $guarded = [];

    protected $table = 'jn_user_subscription';
    public $timestamps = false;

    public function user(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo('App\Models\User','user_id');
    }

    public function subscription()
    {
        return $this->belongsTo('App\Models\SubscriptionPlan','subscription_id','id');
    }



}
