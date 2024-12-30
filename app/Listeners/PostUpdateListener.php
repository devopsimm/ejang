<?php

namespace App\Listeners;

use App\Services\GeneralService;
use App\Services\PostService;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Support\Facades\Cache;

class PostUpdateListener
{
    /**
     * Create the event listener.
     */
    public function __construct()
    {
        //
    }

    /**
     * Handle the event.
     */
    public function handle(object $event): void
    {
        Cache::forget('posts');
        Cache::forget('ContentPositions');
        Cache::forget('homePage');
        Cache::forget('sidebar');
    }
}
