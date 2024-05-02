<?php

namespace App\Listeners;

use App\Events\MeritListPartiallyApproveEvent;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;

class SendPartiallyApprovedNotification
{
    /**
     * Create the event listener.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    /**
     * Handle the event.
     *
     * @param  MeritListPartiallyApproveEvent  $event
     * @return void
     */
    public function handle(MeritListPartiallyApproveEvent $event)
    {
        logger(get_class($event)." event fired ");
        logger(get_class($this)." listner hit");
    }
}
