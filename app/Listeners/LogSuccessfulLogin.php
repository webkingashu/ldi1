<?php

namespace App\Listeners;

use Illuminate\Auth\Events\Login;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Http\Request;
use App\UserLog;

class LogSuccessfulLogin
{
    /**
     * Create the event listener.
     *
     * @return void
     */
    public function __construct(Request $request)
    {
        $this->request = $request;
    }

    /**
     * Handle the event.
     *
     * @param  Login  $event
     * @return void
     */
    public function handle(Login $event)
    {
        UserLog::create([
        'user_id'     =>  $event->user->id,
        'otp'         =>  $event->user->otp_token,
        'session_id'  =>  session()->getId(),
        'created_at'  =>  date('Y-m-d H:i:s')
    ]);
    }
}
    